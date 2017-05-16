<?php
session_start();
require_once('koneksi.php');
require_once("plugins/php-barcode-generator-master/src/BarcodeGenerator.php");
require_once("plugins/php-barcode-generator-master/src/BarcodeGeneratorPNG.php");
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
?>
<html>
	<head>
    	<title>cetak barang</title>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div style="font-size:18px"><b>MASTER BARANG<br />Dicetak Tanggal : <?php echo date('d/m/Y H:i:s'); ?></div><hr />
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
            	<tr>
                	<th>KODE</th>
                    <th>NAMA BARANG</th>
                    <th>HARGA MODAL</th>
                    <th>HARGA JUAL</th>
                    <th>STOK</th>
               </tr>
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
			   ?>
               <tr>
                    <td align="center">
						<?php
						echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($b['kode'], $generator::TYPE_CODE_128)) . '"><br />';
						echo $b['kode'];
						?>
                    </td>
                    <td><?php echo $b['nama_barang']; ?></td>
                    <td><?php echo toIdr($b['harga_modal']); ?></td>
                    <td><?php echo toIdr($b['harga_jual']); ?></td>
                    <td><?php echo $saldo; ?></td>
               </tr>
               <?php } ?>
            </table>
        </div>
    </body>
</html>