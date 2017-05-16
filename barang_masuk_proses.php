<?php
session_start();
/*
$_POST['aksi_barang_masuk']='tambah';
$_POST['no_nota'] = 'ddd';
$_POST['tgl_masuk'] = '27/03/2016';
$_POST['kekurangan']='100000';
$_POST['tgl_tempo']='29/03/2016';
$_POST['dibayar']='200000';
$_POST['bmsuplier']='';
*/
require_once("koneksi.php");
if(isset($_POST['aksi_barang_masuk'])){
	if($_POST['aksi_barang_masuk']=='tambah'){
		$no_nota = generate_no_nota();
		$tgl_masuks = explode("/", escape($_POST['tgl_masuk']));
		$tgl_masuk = $tgl_masuks[2].'-'.$tgl_masuks[1].'-'.$tgl_masuks[0];
		if($_POST['kekurangan']!='0'){
			$tgl_tempos = explode("/", escape($_POST['tgl_tempo']));
			$tgl_tempo = "'".$tgl_tempos[2].'-'.$tgl_tempos[1].'-'.$tgl_tempos[0]."'";
		}else{
			$tgl_tempo = 'NULL';
		}
		$username = $_SESSION['usrdridh'];
		$dibayar = escape($_POST['dibayar']);
		$kekurangan = escape($_POST['kekurangan']);
		$suplier = escape($_POST['suplier']);
		if($suplier==''){
			$suplier='NULL';
		}else{
			$suplier="'$suplier'";
		}
		if($kekurangan=='0'){
			$dibayar = $_POST['total'];
		}
		$a = mysql_query("insert into barang_masuk(no_nota, tgl_masuk, username, kode_suplier, dibayar, kekurangan, tgl_tempo) values('$no_nota', '$tgl_masuk', '$username', $suplier, '$dibayar', '$kekurangan', $tgl_tempo)");
		if($a){
			$items = explode(',', $_SESSION['bmasuk']);
			$contents = array();
			foreach ($items as $item) {
				$contents[$item] = (isset($contents[$item])) ? $contents[$item] + 1 : 1;
			}
			$total = 0;
			foreach ($contents as $id=>$qty) {
				$b = mysql_query("select * from barang where kode='$id'");
				$c = mysql_fetch_array($b);
				$total += ($c['harga_modal']*$qty);
				$d = mysql_query("insert into barang_masuk_detail(kode, qty, no_nota, harga) values('$c[kode]', '$qty', '$no_nota', '$c[harga_modal]')");
				if($d){
					mysql_query("update history_saldo set saldo=saldo+$qty where kode='$c[kode]'");
					if(mysql_affected_rows()==0){
						mysql_query("insert into history_saldo(kode, tanggal, saldo) values('$c[kode]', now(), '$qty')");
					}
					mysql_query("insert into history_harga(tgl_perubahan, kode, harga) values(now(), '$c[kode]', '$c[harga_modal]')");
					/*
					$hit = mysql_query("select sum(qty) as jml, sum(harga*qty) as total from barang_masuk_detail where no_nota='$no_nota'");
					$hHit = mysql_fetch_array($hit);
					mysql_query("update barang_masuk set total='$hHit[total]', jumlah='$hHit[jml]' where no_nota='$no_nota'");
					mysql_query("insert into history_pembayaran_suplier(tgl_bayar, jumlah_cicil, kekurangan_cicil, no_nota) values('$tgl_masuk', '$dibayar', '$kekurangan', '$no_nota')");
					unset($_SESSION['bmasuk']);
					$data[] = array('status' => 'ok',  'no_nota' => $no_nota);
					*/
				}else{
					//$data[] = array('status' => 'failed',  'pesan' => 'berhasil menyimpan transaksi, gagal menyimpan barang transaksi');
				}
			}
			$hit = mysql_query("select sum(qty) as jml, sum(harga*qty) as total from barang_masuk_detail where no_nota='$no_nota'");
			$hHit = mysql_fetch_array($hit);
			mysql_query("update barang_masuk set total='$hHit[total]', jumlah='$hHit[jml]' where no_nota='$no_nota'");
			mysql_query("insert into history_pembayaran_suplier(tgl_bayar, jumlah_cicil, kekurangan_cicil, no_nota) values('$tgl_masuk', '$dibayar', '$kekurangan', '$no_nota')");
			unset($_SESSION['bmasuk']);
			$data[] = array('status' => 'ok',  'no_nota' => $no_nota);
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menyimpan transaksi, '.mysql_error());
		}
		echo json_encode($data);
	}else if($_POST['aksi_barang_masuk']=='edit'){
		$kode_lama_karyawan = escape($_POST['kode_lama_karyawan']);
		$username = escape($_POST['username']);
		$nama_karyawan = escape($_POST['nama_karyawan']);
		$no_tlp = escape($_POST['no_tlp_karyawan']);
		$pin_bb = escape($_POST['pin_bb_karyawan']);
		$password = escape($_POST['password']);
		$level = escape($_POST['level']);
		$status = escape($_POST['status']);
		$a = mysql_query("update karyawan set username='$username', nama='$nama_karyawan', no_telp='$no_tlp', pin_bb='$pin_bb', password='$password', level='$level', status='$status' where username='$kode_lama_karyawan'");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil mengedit karyawan');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'berhasil mengedit karyawan');
		}
		echo json_encode($data);
	}else if($_POST['aksi_barang_masuk']=='cek'){
		$username = $_POST['username'];
		$a = mysql_query("select * from karyawan where username='$username'");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = array('status' => 'ok');
		}else{
			$data[] = array('status' => 'no');
		}
		echo json_encode($data);
	}else if($_POST['aksi_barang_masuk']=='data barang'){
		$a = mysql_query("select * from barang order by nama_barang asc, tipe asc");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$data[] = $b;
			}
			echo json_encode($data);
		}
	}else if($_POST['aksi_barang_masuk']=='data suplier'){
		$a = mysql_query("select * from suplier order by nama_suplier asc");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$data[] = $b;
			}
			echo json_encode($data);
		}
	}else if($_POST['aksi_barang_masuk']=='hapus'){
		$kode = $_POST['id'];
		$a = mysql_query("delete from karyawan where username='$kode'");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil menghapus karyawan');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menghapus karyawan');
		}
		echo json_encode($data);
	}
}
if(isset($_GET['cart'])){
//unset($_SESSION['bmasuk']);
	if(!isset($_SESSION['bmasuk'])){
		echo '{
			"sEcho": 1,
			"iTotalRecords": "0",
			"iTotalDisplayRecords": "0",
			"aaData": []
		}';
	}else{
		$items = explode(',', $_SESSION['bmasuk']);
		$contents = array();
		foreach ($items as $item) {
			$contents[$item] = (isset($contents[$item])) ? $contents[$item] + 1 : 1;
		}
		$total = 0;
		foreach ($contents as $id=>$qty) {
			$a = mysql_query("select * from barang where kode='$id'");
			$b = mysql_fetch_array($a);
			$total += ($b['harga_modal']*$qty);
			$data['aaData'][] = array('kode' => $b['kode'], 'nama_barang' => $b['nama_barang'], 'qty' => $qty, 'harga_modal' => $b['harga_modal'], 'sub_total' => ($b['harga_modal']*$qty), 'total' => $total);
		}
		echo json_encode($data);	
	}
}else if(isset($_GET['add'])){
	$kode = escape($_POST['kode']);
	$harga = escape($_POST['harga']);
	$qty = escape($_POST['jml']);
	$qUpdateHarga = mysql_query("update barang set harga_modal='$harga' where kode='$kode'");
	$q = mysql_query("select * from barang where kode='$kode'");
	$cek = mysql_num_rows($q);
	if($cek!=0){
		for($i=1; $i<=$qty; $i++){
			if(!isset($_SESSION['bmasuk'])){
				$_SESSION['bmasuk'] = $kode;
			}else{
				$_SESSION['bmasuk'] .= ','.$kode;
			}
		}
	}
	$y = mysql_fetch_array($q);
	$data[] = array('status' => $y['nama_barang']);
	echo json_encode($data);
}else if(isset($_GET['del'])){
	$kode = escape($_POST['kode']);
	$items = explode(',',$_SESSION['bmasuk']);
	$newcart = '';
	foreach ($items as $item) {
		if ($kode != $item) {
			if ($newcart != '') {
				$newcart .= ','.$item;
			} else {
				$newcart = $item;
			}
		}
	}
	if($newcart!=''){	
		$_SESSION['bmasuk'] = $newcart;
		$q = mysql_query("select * from barang where kode='$kode'");
		$cek = mysql_num_rows($q);
		$y = mysql_fetch_array($q);
		$data[] = array('status' => $y['nama_barang'].' berhasil dihapus');
	}else{
		unset($_SESSION['bmasuk']);
		$data[] = array('status' => 'item barang kosong');
	}
	echo json_encode($data);
}else if(isset($_GET['cek'])){
	if(isset($_SESSION['bmasuk'])){
		$data[] = array('status' => 'no');
	}else{
		$data[] = array('status' => 'yes');
	}
	echo json_encode($data);
}else if(isset($_GET['data_barang'])){
	$kode = escape($_POST['kode']);
	$a = mysql_query("select * from barang where BINARY kode='$kode'");
	$jml = mysql_num_rows($a);
	if($jml==0){
		$data[] = NULL;
	}else{
		$data[] = mysql_fetch_array($a);
	}
	echo json_encode($data);
}else if(isset($_GET['edt'])){
	$kode = escape($_POST['kode']);
	$jml = escape($_POST['jml']);
	
	$cart = explode(',',$_SESSION['bmasuk']);
	$newcart = '';
	for($i=0; $i<sizeof($cart); $i++){
		if($kode!=$cart[$i]){
			if($newcart==''){
				$newcart = $cart[$i];
			}else{
				$newcart .= ','.$cart[$i];
			}
		}
	}
	for($i=0; $i<$jml; $i++){
		if($newcart==''){
			$newcart = $kode;
		}else{
			$newcart .= ','.$kode;
		}
	}
	$_SESSION['bmasuk'] = $newcart;
	$q = mysql_query("select * from barang where kode='$kode'");
	$cek = mysql_num_rows($q);
	$y = mysql_fetch_array($q);
	$data[] = array('status' => $y['nama_barang']);
	echo json_encode($data);
}else if(isset($_GET['total'])){
	if(!isset($_SESSION['bmasuk'])){
		$total = 0;
	}else{
		$items = explode(',', $_SESSION['bmasuk']);
		$contents = array();
		foreach ($items as $item) {
			$contents[$item] = (isset($contents[$item])) ? $contents[$item] + 1 : 1;
		}
		$total = 0;
		foreach ($contents as $id=>$qty) {
			$a = mysql_query("select * from barang where kode='$id'");
			$b = mysql_fetch_array($a);
			$total += ($b['harga_modal']*$qty);
		}	
	}
	echo json_encode(array('total' => $total));
}else if(isset($_GET['reset'])){
	unset($_SESSION['bmasuk']);
	echo json_encode(array('status' => 'berhasil mereset'));
}
function generate_no_nota(){
	$a = mysql_query("select no_nota from barang_masuk where tgl_masuk=date(now())");
	$jml = mysql_num_rows($a);
	if($jml==0){
		$no_pembayaran = 'BM'.date("dmY").'-1';
	}else{
		while($b = mysql_fetch_array($a)){
			$urutans = explode("-", $b['no_nota']);
			$data[] = $urutans[1];
		}
		$no_pembayaran = 'BM'.date("dmY").'-'.(max($data)+1);		
	}
	return $no_pembayaran;
}
?>