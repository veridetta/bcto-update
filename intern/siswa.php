<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table.min.css">
<script src="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table.min.js"></script>
<div class="card">
    <div class="card-header">
        <p class="h4">Daftar Siswa</p>
    </div>
    <div class="card-body">
        <div id="toolbar">
            <button id="remove" class="btn btn-danger" disabled>
                <i class="fa fa-trash"></i> Delete
            </button>
        </div>
        <table
        id="table"
        data-toolbar="#toolbar"
        data-search="true"
        data-show-refresh="true"
        data-show-toggle="true"
        data-show-fullscreen="true"
        data-show-columns="true"
        data-show-columns-toggle-all="true"
        data-show-export="true"
        data-click-to-select="true"
        data-minimum-count-columns="2"
        data-show-pagination-switch="true"
        data-pagination="true"
        data-id-field="id"
        data-page-list="[10, 25, 50, 100, all]"
        data-show-footer="true"
        data-side-pagination="server"
        data-url="action/siswa_get.php"
        data-response-handler="responseHandler">
        </table>
    </div>
</div>
<script>
  var $table = $('#table')
  var $remove = $('#remove')
  var selections = []

  function getIdSelections() {
    return $.map($table.bootstrapTable('getSelections'), function (row) {
      return row.id
    })
  }

  function responseHandler(res) {
    $.each(res.rows, function (i, row) {
      row.state = $.inArray(row.id, selections) !== -1
    })
    return res
  }


  function operateFormatter(value, row, index) {
    return [
      '<a class="like" href="javascript:void(0)" title="Like">',
      '<i class="fa fa-heart"></i>',
      '</a>  ',
      '<a class="remove" href="javascript:void(0)" title="Remove">',
      '<i class="fa fa-trash"></i>',
      '</a>'
    ].join('')
  }

  window.operateEvents = {
    'click .like': function (e, value, row, index) {
      alert('You click like action, row: ' + JSON.stringify(row))
    },
    'click .remove': function (e, value, row, index) {
      $table.bootstrapTable('remove', {
        field: 'id',
        values: [row.id]
      })
    }
  }

  function totalTextFormatter(data) {
    return 'Total'
  }

  function totalNameFormatter(data) {
    return data.length
  }

  function totalPriceFormatter(data) {
    var field = this.field
    return  data.map(function (row) {
      return +row[field]
    }).reduce(function (sum, i) {
      return sum + i
    }, 0)
  }

  function initTable() {
    $table.bootstrapTable('destroy').bootstrapTable({
      height: 550,
      columns: [
        [{
          field: 'state',
          checkbox: true,
          align: 'center',
          valign: 'middle'
        }, {
          title: 'Id',
          field: 'id',
          visible:false,
          align: 'center',
          valign: 'middle',
          sortable: true
        }, {
          title: 'Nomor',
          field: 'no',
          align: 'center',
          valign: 'middle',
          sortable: false,
          footerFormatter: totalTextFormatter
          
        }, {
          field: 'nama',
          title: 'Nama Siswa',
          sortable: true,
          footerFormatter: totalNameFormatter,
          align: 'center'
        }, {
          field: 'hp',
          title: 'No HP',
          sortable: false,
          footerFormatter: totalNameFormatter,
          align: 'center'
        }, {
          field: 'bintang',
          title: 'Bintang',
          sortable: false,
          align: 'center',
          footerFormatter: totalPriceFormatter
        }, {
          field: 'ikut',
          title: 'Try Out',
          sortable: false,
          align: 'center',
          footerFormatter: totalPriceFormatter
        }, {
          field: 'operate',
          title: 'Action',
          align: 'center',
          clickToSelect: false,
          events: window.operateEvents,
          formatter: operateFormatter
        }]
      ]
    })
    $table.on('check.bs.table uncheck.bs.table ' +
      'check-all.bs.table uncheck-all.bs.table',
    function () {
      $remove.prop('disabled', !$table.bootstrapTable('getSelections').length)

      // save your data, here just save the current page
      selections = getIdSelections()
      // push or splice the selections if you want to save all data selections
    })
    $table.on('all.bs.table', function (e, nama, args) {
      console.log(nama, args)
    })
    $remove.click(function () {
      var ids = getIdSelections()
      $table.bootstrapTable('remove', {
        field: 'id',
        values: ids
      })
      $remove.prop('disabled', true)
    })
  }

  $(function() {
    initTable()

  })
</script>