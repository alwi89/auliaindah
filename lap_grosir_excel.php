<?php
session_start();
$id_cabang = $_SESSION['cbg'];
require_once("koneksi.php");
$kode_sales = urldecode($_GET['sales']);
$status = urldecode($_GET['status']);
if($status=='lunas'){
	$kekurangan='kekurangan=0';
}else{
	$kekurangan='kekurangan<>0';
}
$qNama = mysql_query("select nama_sales, nama_cabang from sales s join cabang c on s.id_cabang=c.kode_cabang where kode_sales='$kode_sales'");
$hNama = mysql_fetch_array($qNama);
$nama_sales = $hNama['nama_sales'];
$query = mysql_query("select dibayar, kekurangan, tgl_keluar, m.no_nota, nama_sales, nama, d.kode, tipe, nama_barang, d.harga_modal, d.harga, qty, d.harga*qty sub_total from karyawan k join grosir m on k.username=m.username join grosir_detail d on m.no_nota=d.no_nota left join barang b on d.kode=b.kode join sales c on m.kode_sales=c.kode_sales where m.kode_sales='$kode_sales' and $kekurangan and tgl_keluar between '$_GET[dari]' and '$_GET[sampai]' order by tgl_keluar asc, no_nota asc");
$jml = mysql_num_rows($query);
//$data = array();
while($qHasil = mysql_fetch_array($query)){
  $data[] = 
    array("TGL KELUAR" => $qHasil['tgl_keluar'], "NO NOTA" => $qHasil['no_nota'], "SALES" => $qHasil['nama_sales'],  'PENDATA' => $qHasil['nama'], 'KODE' => $qHasil['kode'], 'TIPE' => trim($qHasil['tipe']), 'NAMA BARANG' => $qHasil['nama_barang'], "HRG MODAL" => toIdr($qHasil['harga_modal']),'HRG JUAL' => toIdr($qHasil['harga']), 'QTY' => $qHasil['qty'], 'SUB TOTAL' => toIdr($qHasil['sub_total']), 'LABA' => toIdr($qHasil['sub_total']-($qHasil['harga_modal']*$qHasil['qty'])));
}
  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // file name for download
  $filename = "laporan_grosir_$_GET[dari]_sd_$_GET[sampai]_".$nama_sales."_$status".".xls";

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