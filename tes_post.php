<?php
//print_r($_POST);
$html=$_POST['isi'];
$idsoal='1';
$doc = new DOMDocument();
$doc->loadHTML($html);
$tables = $doc->getElementsByTagName('table');
$posisi = "pictures/soal$idsoal";
$client_id = 'c419515babedb27';
$cilent_secret = '14e6f931567bc234e294d87559e4edf51a51253d';
$token= '70184e627b51dc5592bc2cfb58e0f35c2a6611cc';
function url(){
    return sprintf(
      "%s://%s%s",
      isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
      $_SERVER['SERVER_NAME'],
      $_SERVER['REQUEST_URI']
    );
  }
if(!empty($tables->item(0)->nodeValue)){
    $rows = $tables->item(0)->getElementsByTagName('tr');
    $counter_soal = 0;
    $soal_id = 0;
    $soal_arry=array(
        "data"=>array()
    );
    
    foreach ($rows as $row) {
        $counter_jawaban = 0;
        $isi=array();
        /*** get each column by tag name ***/ 
        $cols = $row->getElementsByTagName('td'); 
        $kosong = 0;
        if(empty($cols->item(1)->nodeValue)){
            $kosong++;
        }
        if(empty($cols->item(2)->nodeValue)){
            $kosong++;
        }
        if(empty($cols->item(3)->nodeValue)){
            $kosong++;
        }
        if($kosong==0){
            $jenis = strtoupper(preg_replace('/\s+/', '',$cols->item(1)->nodeValue));
            $kunci = strtoupper(preg_replace('/\s+/', '',$cols->item(3)->nodeValue));
            $kunci = intval($kunci);

            if($kunci>0){
                $kunci=1;
            }
            if($jenis=='SOAL'){
                // Menyimpan image base64 menjadi file
                $soal = $cols->item(3)->nodeValue;
                $tags = $cols->item(3)->getElementsByTagName('img');
                foreach ($tags as $tag) {
                    $soal_image = $tag->getAttribute('src');
                    if (strpos($soal_image, 'data:image/') !== false) {
                        $soal_image_array = preg_split("@[:;,]+@", $soal_image);
                        $extensi = explode('/', $soal_image_array[1]);
                        $file_name = $posisi.'/'.uniqid().'.'.$extensi[1];
                        if(!is_dir($posisi)){
                            mkdir($posisi);
                        }
                        // menyimpan file dari base64
                        file_put_contents($file_name, base64_decode($soal_image_array[3]));
                        $soal = str_replace($soal_image, url().$file_name, $soal);
                        $url = 'https://api.imgur.com/3/image';
                        $data = $file_name;
                        $data_name = "Bagja-College-".$soal_image;
                        $handle = fopen($data, "r");
                        $file= fread($handle, filesize($data));
                        $headers = array("Authorization: Bearer $token");
                        $data_name="Bagja-College-".uniqid();
                        $pvars  = array('image' =>base64_encode($file), 'title' => 
                        $data_name, 'album' =>'nsb2q7D', 'description' => 'Bagja College Try Out');
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                        CURLOPT_URL=> $url,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_POST => 1,
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_HTTPHEADER => $headers,
                        CURLOPT_POSTFIELDS => $pvars
                        ));
                        $json_returned = curl_exec($curl); // blank response
                        $pms = json_decode($json_returned,true);
                        curl_close ($curl); 
                        //print_r($pms);
                        if(file_exists($file_name)) {
                            unlink($file_name);
                            //echo 'File '.$file_name.' has been deleted';
                        }else{
                            //echo 'Could not delete '.$file_name.', file does not exist';
                        }
                    }else{
                        echo "gagal";
                    }
                }

                // Simpan soal
                $data['isi']= $soal;
                array_push($soal_arry["data"], $data);
                $counter_soal++;
            }else if($jenis=='A'){
                $opsi[] = $cols->item(2)->nodeValue;
                switch($counter_jawaban){
                    case '0':
                        $opsiea['a'] = $opsi[0];
                        array_push($soal_arry["data"], $opsiea);
                        break;
                    case '1':
                        $opsieb['b'] = $opsi[1];
                        array_push($soal_arry["data"], $opsieb);
                        break;
                    case '2':
                        $opsiec['c'] = $opsi[2];
                        array_push($soal_arry["data"], $opsiec);
                        break;
                    case '3':
                        $opsied['d'] = $opsi[3];
                        array_push($soal_arry["data"], $opsied);
                        break;
                    case '4':
                        $opsiee['e'] = $opsi[4];
                        array_push($soal_arry["data"], $opsiee);
                        break;
                }
                //$this->innerHTML($cols->item(2))
                //$data['a'] = $opsi[0];
               //$data['b'] = $opsi[1];
               //$data['c'] = $opsi[2];
                //$data['d'] = $opsi[3];
                // $data['e'] = $opsi[4];
                $counter_jawaban++;
            }else if($jenis=='K'){
                $kuncie['kunci']= $cols->item(2)->nodeValue;
                array_push($soal_arry["data"], $kuncie);
            }else if($jenis=='P'){
                // Menyimpan image base64 menjadi file
                $soal = $cols->item(2)->nodeValue;
                $tags = $cols->item(2)->getElementsByTagName('img');
                foreach ($tags as $tag) {
                    $soal_image = $tag->getAttribute('src');
                    if (strpos($soal_image, 'data:image/') !== false) {
                        $soal_image_array = preg_split("@[:;,]+@", $soal_image);
                        $extensi = explode('/', $soal_image_array[1]);
                        $file_name = $posisi.'/'.uniqid().'.'.$extensi[1];
                        if(!is_dir($posisi)){
                            mkdir($posisi);
                        }
                        // menyimpan file dari base64
                        file_put_contents($file_name, base64_decode($soal_image_array[3]));
                        $soal = str_replace($soal_image, url().$file_name, $soal);
                        $url = 'https://api.imgur.com/3/image';
                        $data = $file_name;
                        $data_name = "Bagja-College-".$soal_image;
                        $handle = fopen($data, "r");
                        $file= fread($handle, filesize($data));
                        $headers = array("Authorization: Bearer $token");
                        $data_name="Bagja-College-".uniqid();
                        $pvars  = array('image' =>base64_encode($file), 'title' => 
                        $data_name, 'album' =>'nsb2q7D', 'description' => 'Bagja College Try Out');
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                        CURLOPT_URL=> $url,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_POST => 1,
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_HTTPHEADER => $headers,
                        CURLOPT_POSTFIELDS => $pvars
                        ));
                        $json_returned = curl_exec($curl); // blank response
                        $pms = json_decode($json_returned,true);
                        curl_close ($curl); 
                        //print_r($pms);
                        if(file_exists($file_name)) {
                            unlink($file_name);
                            //echo 'File '.$file_name.' has been deleted';
                        }else{
                            //echo 'Could not delete '.$file_name.', file does not exist';
                        }
                    }else{
                        echo "gagal";
                    }
                }
                // Simpan soal
                $datax['pembahasan']= $soal;
                array_push($soal_arry["data"], $datax);
                //$counter_soal++;
            }
            //$insert=mysqli_query($con, "insert into soal (id_paket_soal, id_sesi_soal, isi, kunci, pembahasan, a, b, c, d, e, score, jawaban_benar) values('$id_paket_soal','$id_sesi_soal','$isi','$kunci','$pembahasan','$a','$b','$c','$d','$e', '','')");
        }else{
            // menghentikan loop ketika ada yang kosong
            break;
        }
   }
   print_r($soal_arry); 
}
   
?>