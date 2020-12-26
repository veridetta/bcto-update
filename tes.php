<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bagja College Try Out</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="background-color:#f8f8f8">
<?php include 'header.php';?>
<!-- Custom styles for this template -->
<!--<link href="/assets/css/side_bar.css" rel="stylesheet"> -->
<!--<script src="/assets/js/tinymce/tinymce.min.js"></script>-->
<link href="https://www.tiny.cloud/css/codepen.min.css" rel="sylesheet">
<script src="https://cdn.tiny.cloud/1/qagffr3pkuv17a8on1afax661irst1hbr4e6tbv888sz91jc/tinymce/4/tinymce.min.js"></script>
<div class="col-11" style="padding:10px;">
<form method="post" name="artikel" id="artikel" >
    <div class="form-group">
        <div class="input-group">
            <textarea id="inputan" class="inputan form-control" name="isi" style="min-height:50vh;">Siapa</textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <button class="btn button btn-success">SIMPAN</button>
        </div>
    </div>
</form>
</div>
<div class="col-12" id="hasill">
</div>
<script>
$(document).ready(function() {
    tinymce.init({
  selector: 'textarea',
  height: 400,
  menubar: true,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks advcode fullscreen',
    'insertdatetime media table contextmenu powerpaste'
  ],
  toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image code',
  powerpaste_allow_local_images: true,
  powerpaste_word_import: 'prompt',
  powerpaste_html_import: 'prompt',
  content_css: [
    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
    '//www.tiny.cloud/css/codepen.min.css']
});

            $('#artikel').submit(function(e){
                $("#hasill").html('<div class="text-center row row-imbang justify-content-center" style="min-height:100vh;">Loading ....<br></div>');
                var info = $('#hasill');
                e.preventDefault();
                $.ajax({
                url: 'tes_post.php',
                type: 'POST',
                    data: $(this).serialize()
                    })
                .done(function(data){
                    info.html(data);
                })
                .fail(function(){
                    alert('Maaf, submit gagal..');
                });
            });
})

</script>