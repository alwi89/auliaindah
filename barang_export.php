<?php
require_once("koneksi.php");
require_once("plugins/php-barcode-generator-master/src/BarcodeGenerator.php");
require_once("plugins/php-barcode-generator-master/src/BarcodeGeneratorPNG.php");
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
session_start();
$query = mysql_query("select * from barang order by nama_barang asc");
$jml = mysql_num_rows($query);
//$data = array();
while($qHasil = mysql_fetch_array($query)){
	$qSaldo = mysql_query("select saldo from history_saldo where kode='$qHasil[kode]'");
					$jmlQuery = mysql_num_rows($qSaldo);
					if($jmlQuery==0){
						$saldo = '0';
					}else{
						$hSaldo = mysql_fetch_array($qSaldo);
						$saldo = $hSaldo['saldo'];
					}
  //echo '<img id="barcode" src="data:image/png;base64,' . base64_encode($generator->getBarcode($b['kode'], $generator::TYPE_CODE_128)) . '"><br />';
          $diskon = $qHasil['diskon'];
          $harga_jual = $qHasil['harga_jual'];
          $persentase = $harga_jual/100;
          $harga_diskon = $harga_jual-($diskon*$persentase);
  $data[] = 
    array("KODE" => $qHasil['kode'], 'NAMA BARANG' => $qHasil['nama_barang'], 'HARGA MODAL' => toIdr($qHasil['harga_modal']), 'MARKUP' => $qHasil['markup'], 'HARGA JUAL' => toIdr($qHasil['harga_jual']), 'DISKON' => $qHasil['diskon'], 'HARGA DISKON' => $harga_diskon, 'STOK' => $saldo);
}
  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // file name for download
  $filename = "master_barang_".date('Y-m-d').".xls";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");

  $flag = false;
  foreach($data as $row) {
    if(!$flag) {
      // display field/column names as first row
      echo implode("\t", array_keys($row)) . "\n";
      $flag = true;
    }
    array_walk($row, 'cleanData');
    echo implode("\t", array_values($row)) . "\n";
  }

  exit;

?>