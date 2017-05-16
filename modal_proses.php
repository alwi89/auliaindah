<?php
require_once("koneksi.php");
session_start();
if(isset($_POST['aksi'])){
	if($_POST['aksi']=='tambah'){
		$nominal_modal = escape($_POST['nominal_modal']);
		$a = mysql_query("insert into modal_harian(tgl_modal, total_modal) values(date(now()), '$nominal_modal')");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil menambahkan modal harian');
		}else{
			mysql_query("update modal_harian set total_modal='$nominal_modal' where tgl_modal=date(now())");
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil mengedit modal harian');
		}
		echo json_encode($data);
	}
}
if(isset($_GET['data'])){
	$a = mysql_query("select 'pemasukan' jenis, jumlah_cicil nominal from history_pembayaran_penjualan where tgl_bayar=date(now())
union all
select 'pengeluaran' jenis, jumlah_cicil from history_pembayaran_suplier where tgl_bayar=date(now())");
	$jml = mysql_num_rows($a);
	$x = mysql_query("select * from modal_harian where tgl_modal=date(now())");
	$y = mysql_fetch_array($x);
	$modal = $y['total_modal'];
	$sisa = $y['total_modal'];
	if($jml==0){
		$data['aaData'][] = array('jenis' => 'modal awal', 'nominal' => $modal, 'sisa' => $sisa);
		echo json_encode($data);
	}else{
		while($b = mysql_fetch_array($a)){
			if($b['jenis']=='pengeluaran'){
				$sisa -= $b['nominal'];
			}else{
				$sisa += $b['nominal'];
			}
			$data['aaData'][] = array('jenis' => $b['jenis'], 'nominal' => $b['nominal'], 'sisa' => $sisa);
		}
		echo json_encode($data);
	}	
}
?>