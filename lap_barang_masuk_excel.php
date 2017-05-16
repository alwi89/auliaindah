<?php
require_once("koneksi.php");
$suplier = urldecode($_GET['suplier']);
if($suplier=='SEMUA'){	
	$nama_suplier = 'SEMUA';
	$query = mysql_query("select tgl_masuk, m.no_nota, nama, d.kode, tipe, nama_barang, harga, qty, harga*qty sub_total from karyawan k join barang_masuk m on k.username=m.username join barang_masuk_detail d on m.no_nota=d.no_nota join barang b on d.kode=b.kode where tgl_masuk between '$_GET[dari]' and '$_GET[sampai]' order by tgl_masuk asc, no_nota asc");
}else{
	$qNama = mysql_query("select nama_suplier from suplier where kode_suplier='$suplier'");
	$hNama = mysql_fetch_array($qNama);
	$nama_suplier = $hNama['nama_suplier'];
	$query = mysql_query("select tgl_masuk, m.no_nota, nama, d.kode, tipe, nama_barang, harga, qty, harga*qty sub_total from karyawan k join barang_masuk m on k.username=m.username join barang_masuk_detail d on m.no_nota=d.no_nota join barang b on d.kode=b.kode where m.kode_suplier='$suplier' and tgl_masuk between '$_GET[dari]' and '$_GET[sampai]' order by tgl_masuk asc, no_nota asc");
}
$jml = mysql_num_rows($query);
//$data = array();
while($qHasil = mysql_fetch_array($query)){
  $data[] = 
    array("TGL MASUK" => $qHasil['tgl_masuk'], "NO NOTA" => $qHasil['no_nota'], 'PENDATA' => $qHasil['nama'], 'KODE' => $qHasil['kode'], 'TIPE' => trim($qHasil['tipe']), 'NAMA BARANG' => $qHasil['nama_barang'], 'HARGA' => toIdr($qHasil['harga']), 'QTY' => $qHasil['qty'], 'SUB TOTAL' => toIdr($qHasil['sub_total']));
}
  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // file name for download
  $filename = "laporan_barang_masuk_$nama_suplier"."_$_GET[dari]_sd_$_GET[sampai]".".xls";

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