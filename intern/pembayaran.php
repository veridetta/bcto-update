<?php include '../config/connect.php';
$skrg=date("Y-m-d");
$da=mysqli_query($con, "select r.id, r.id_users, r.status, r.nominal, r.saldo, r.tgl, u.nama, u.id, h.nominal as uang, h.jumlah from riwayat_bintang r inner join user u on u.id=r.id_users inner join harga_paket h on h.jumlah=r.nominal where r.status='1' and r.tgl >= CURDATE() && r.tgl < (CURDATE() + INTERVAL 1 DAY) order by r.id desc");
?>
<div class="card">
    <div class="card-header">
        <p class="h4">Riwayat Pembayaran</p>
    </div>
    <div class="card-body">
        <div class="col-12">
            <p class="h4 font-weight-bold text-info">Pemasukan Hari ini </p>
            <table class="table table-striped table-bordered">
                <tr class="bg-success">
                    <td class="font-weight-bold">No</td>
                    <td class="font-weight-bold">Nama Siswa</td>
                    <td class="font-weight-bold">Bintang</td>
                    <td class="font-weight-bold">Nominal</td>
                </tr>
                <?php
                $no=1;
                $t_bintang=0;
                $t_uang=0;
                while($data=mysqli_fetch_array($da)){
                    ?>
                    <tr>
                        <td><?php echo $no;?></td>
                        <td><?php echo $data['nama'];?></td>
                        <td><?php echo $data['nominal'];?></td>
                        <td><?php echo number_format($data['uang'],2,",",".");?></td>
                    </tr>
                    <?php
                $no++;
                $t_bintang+=$data['nominal'];
                $t_uang+=$data['uang'];
                }
                ?>
                <tr class="bg-warning">
                    <td class="font-weight-bold"><?php echo $no-1;?></td>
                    <td  class="font-weight-bold">Total </td>
                    <td class="font-weight-bold"><?php echo $t_bintang;?></td>
                    <td class="font-weight-bold">Rp. <?php echo number_format($t_uang,2,",",".");?></td>
                </tr>
            </table>  
            <p class="h4 font-weight-bold text-info">Pemasukan Kemarin </p>
            <table class="table table-striped table-bordered">
                <tr class="bg-success">
                    <td class="font-weight-bold">No</td>
                    <td class="font-weight-bold">Nama Siswa</td>
                    <td class="font-weight-bold">Bintang</td>
                    <td class="font-weight-bold">Nominal</td>
                </tr>
                <?php
                $nok=1;
                $t_bintangk=0;
                $t_uangk=0;
                $dak=mysqli_query($con, "select r.id, r.id_users, r.status, r.nominal, r.saldo, r.tgl, u.nama, u.id, h.nominal as uang, h.jumlah from riwayat_bintang r inner join user u on u.id=r.id_users inner join harga_paket h on h.jumlah=r.nominal where r.status='1' and r.tgl >= (CURDATE() - INTERVAL 1 DAY) && r.tgl < CURDATE() order by r.id desc");
                while($datak=mysqli_fetch_array($dak)){
                    ?>
                    <tr>
                        <td><?php echo $nok;?></td>
                        <td><?php echo $datak['nama'];?></td>
                        <td><?php echo $datak['nominal'];?></td>
                        <td><?php echo number_format($datak['uang'],2,",",".");?></td>
                    </tr>
                    <?php
                $nok++;
                $t_bintangk+=$datak['nominal'];
                $t_uangk+=$datak['uang'];
                }
                ?>
                <tr class="bg-warning">
                    <td class="font-weight-bold"><?php echo $nok-1;?></td>
                    <td  class="font-weight-bold">Total </td>
                    <td class="font-weight-bold"><?php echo $t_bintangk;?></td>
                    <td class="font-weight-bold">Rp. <?php echo number_format($t_uangk,2,",",".");?></td>
                </tr>
            </table>       
        </div>
    </div>
    <div id="chart"></div>
<div>
<?php
function statistik($tipe,$id){
    include '../config/connect.php';
    $go=1;
    $ses=mysqli_query($con, "select r.id, r.id_users, r.status, r.nominal, r.saldo, r.tgl, u.nama, u.id, h.nominal as uang, h.jumlah from riwayat_bintang r inner join user u on u.id=r.id_users inner join harga_paket h on h.jumlah=r.nominal where r.status='1' and r.tgl >= (CURDATE() + INTERVAL 30 DAY) && r.tgl < (CURDATE() + INTERVAL 1 DAY) order by r.id desc");
    $dat= array(
        "deta"=>array()
    );
    if(mysqli_num_rows($ses)>0){
        while($sesi=mysqli_fetch_array($ses)){
            $ar['name']=$sesi['tgl'];
            $ar['data']=array();
            $dak=array(
                "x"=>"",
                "y"=>""
            );
            $n=1;
            if(mysqli_num_rows($nila)<1){
                $go=0;
            }else{
                while($nilai=mysqli_fetch_array($nila)){
                    //print_r($nilai);
                    $arx['x']="TO".$n;
                    $arx['y']=$nilai['nilai'];
                    array_push($ar["data"], $arx);
                    $n++;
                }
                $go+=1;
            }
            array_push($dat["deta"], $ar);
        }
    }else{
        $go=0;
    }
    if($go>0){
        $dat['data']=$ar;
        echo json_encode($dat['deta']);   
    }else{
        echo "[]";
    }
}
?>
<script>
var options = {
    chart: {
        type: 'area',
        stacked: true,
        foreColor: "#2ecc71"
    },
    events: {
        selection: function (chart, e) {
          console.log(new Date(e.xaxis.min))
        }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      width: 3
    },
    fill: {
      type: 'gradient',
      gradient: {
        enabled: true,
        opacityFrom: 0.55,
        opacityTo: 0
      }
    },
    markers: {
        size: 5,
        colors: ["#000524"],
        strokeColor: "#00BAEC",
        strokeWidth: 3
    },
    tooltip: {
        theme: "dark"
    },
    series: [{
        name: "Series A",
        data: [1.4, 2, 2.5, 1.5, 2.5, 2.8, 3.8, 4.6]
    }],
    xaxis: {
        categories: [2009, 2010, 2011, 2012, 2013, 2014, 2015, 2016]
    }
}
    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>