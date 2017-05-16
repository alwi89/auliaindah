<?php
session_start();
require_once('koneksi.php');
require_once("plugins/php-barcode-generator-master/src/BarcodeGenerator.php");
require_once("plugins/php-barcode-generator-master/src/BarcodeGeneratorPNG.php");
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
?>
<html>
	<head>
    	<title>cetak semua barcode</title>
        <style>	
        body {    
    margin: 0 !important;
    padding: 0 !important;
}
		#papan{
			border:1px solid #000000;
			float:left;
			width:6.1cm;
			height:2.95cm;
			padding:5px;
			
			font-size:10px;
			
			margin-right: 0.1cm;
			margin-bottom: 0.15cm;
			
		}
		#barcode{
			width:100%;
		}
		
		#tepi{
			/*
			border: 1px solid red;
	19.4sition: relative;
			height: auto;
			display: table;
			table-layout: fixed;
			padding-top: 1cm;
			padding-left: 0.4cm;
			*/
		}
		</style>
    </head>
    <body onLoad="javascript:print();"> 	
    			
               <?php
			   $a = mysql_query("select * from barang order by nama_barang asc");
			   while($b = mysql_fetch_array($a)){ 
			   		$qSaldo = mysql_query("select saldo from history_saldo where kode='$b[kode]'");
					$jmlQuery = mysql_num_rows($qSaldo);
					if($jmlQuery==0){
						$saldo = '0';
					}else{
						$hSaldo = mysql_fetch_array($qSaldo);
						$saldo = $hSaldo['saldo'];
					}
			   		for($i=1; $i<=$saldo; $i++){
			   ?>
               <div id="papan">
						<?php
						echo $b['nama_barang'].'<br />';
						echo '<img id="barcode" src="data:image/png;base64,' . base64_encode($generator->getBarcode($b['kode'], $generator::TYPE_CODE_128)) . '"><br />';
						echo $b['kode'].'<br />Rp. '.toIdr($b['harga_jual']);
						?>
               </div>
               <?php } } ?>
               
    </body>
</html>