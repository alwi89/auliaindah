<?php
session_start();
require_once('koneksi.php');
require_once("plugins/php-barcode-generator-master/src/BarcodeGenerator.php");
require_once("plugins/php-barcode-generator-master/src/BarcodeGeneratorPNG.php");
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
?>
<html>
	<head>
    	<title>cetak barcode</title>
        <style>
		#papan{
			border:1px solid #000000;
			float:left;
			width:<?php echo $_POST['panjang']; ?>cm;
			height:<?php echo $_POST['lebar']; ?>cm;
			padding:5px;
			margin:5px;
			font-size:10px;
		}
		#barcode{
			width:100%;
		}
		</style>
    </head>
    <body onLoad="javascript:print();"> 	
               <?php
			   $barcode = $_POST['barcode'];
			   $jml = $_POST['jml'];
			   $a = mysql_query("select * from barang where kode='$barcode'");
			   $b = mysql_fetch_array($a);
			   for($i=1; $i<=$jml; $i++){
			   ?>
               <div id="papan">
						<?php
						echo $b['nama_barang'].'<br />';
						echo '<img id="barcode" src="data:image/png;base64,' . base64_encode($generator->getBarcode($b['kode'], $generator::TYPE_CODE_128)) . '"><br />';
						echo $b['kode'].'<br />Rp. '.toIdr($b['harga_jual']);
						?>
               </div>
               <?php } ?>
    </body>
</html>