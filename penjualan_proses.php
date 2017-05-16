<?php
session_start();
/*
$_POST['aksi_penjualan']='tambah';
$_POST['tgl_keluar'] = '30/08/2016';
$_POST['kekurangan']='0';
$_POST['tgl_tempo']='31/08/2016';
$_POST['dibayar']='70000';
$_POST['member']='';
$_POST['total']='70000';
*/
require_once("koneksi.php");
if(isset($_POST['aksi_penjualan'])){
	if($_POST['aksi_penjualan']=='tambah'){
		$no_nota = generate_no_nota();
		$tgl_keluars = explode("/", escape($_POST['tgl_keluar']));
		$tgl_keluar = $tgl_keluars[2].'-'.$tgl_keluars[1].'-'.$tgl_keluars[0];
		if($_POST['kekurangan']!='0'){
			$tgl_tempos = explode("/", escape($_POST['tgl_tempo']));
			$tgl_tempo = "'".$tgl_tempos[2].'-'.$tgl_tempos[1].'-'.$tgl_tempos[0]."'";
		}else{
			$tgl_tempo = 'NULL';
		}
		$username = $_SESSION['usrdridh'];
		$dibayar = escape($_POST['dibayar']);
		$kekurangan = escape($_POST['kekurangan']);
		$member = escape($_POST['member']);
		if($dibayar>escape($_POST['total'])){
			$kembali = escape($_POST['dibayar'])-escape($_POST['total']);
		}else{
			$kembali = '0';
		}
		if($member==''){
			$member='NULL';
		}else{
			$member="'$member'";
		}
		$a = mysql_query("insert into penjualan(no_nota, tgl_keluar, username, kode_member, dibayar, kekurangan, tgl_tempo, kembali) values('$no_nota', '$tgl_keluar', '$username', $member, '$dibayar', '$kekurangan', $tgl_tempo, '$kembali')");
		if($a){
			
				$items = explode(',', $_SESSION['bjual']);
				$contents = array();
				foreach ($items as $item) {
					$contents[$item] = (isset($contents[$item])) ? $contents[$item] + 1 : 1;
				}
				$total = 0;
				foreach ($contents as $id=>$qty) {
					$temp = explode('@', $id);
					$id = $temp[0];
					$harga = $temp[1];
					$b = mysql_query("select * from barang where kode='$id'");
					$c = mysql_fetch_array($b);
					$total += ($harga*$qty);
					$d = mysql_query("insert into penjualan_detail(kode, qty, no_nota, harga, harga_modal) values('$c[kode]', '$qty', '$no_nota', '$harga', '$c[harga_modal]')");
					if($d){
						//update pengurangan saldo
						mysql_query("update history_saldo set saldo=saldo-$qty where kode='$c[kode]'");
						if(mysql_affected_rows()==0){
							mysql_query("insert into history_saldo(kode, tanggal, saldo) values('$c[kode]', now(), '-$qty')");
						}
						/*
						$hit = mysql_query("select sum(qty) as jml, sum(harga*qty) as total from penjualan_detail where no_nota='$no_nota'");
						$hHit = mysql_fetch_array($hit);
						mysql_query("update penjualan set total='$hHit[total]', jumlah='$hHit[jml]' where no_nota='$no_nota'");
						mysql_query("insert into history_pembayaran_penjualan(tgl_bayar, jumlah_cicil, kekurangan_cicil, no_nota) values('$tgl_keluar', '$dibayar', '$kekurangan', '$no_nota')");
						unset($_SESSION['bjual']);
						$data[] = array('status' => 'ok',  'no_nota' => $no_nota);
						*/
					}else{
						$data[] = array('status' => 'failed',  'pesan' => 'gagal menyimpan transaksi, '.mysql_error());
					}
				}
				$hit = mysql_query("select sum(qty) as jml, sum(harga*qty) as total from penjualan_detail where no_nota='$no_nota'");
				$hHit = mysql_fetch_array($hit);
				mysql_query("update penjualan set total='$hHit[total]', jumlah='$hHit[jml]' where no_nota='$no_nota'");
				if($kekurangan=='0'){
					$dibayar = $hHit['total'];
				}
				mysql_query("insert into history_pembayaran_penjualan(tgl_bayar, jumlah_cicil, kekurangan_cicil, no_nota) values('$tgl_keluar', '$dibayar', '$kekurangan', '$no_nota')");
				$data[] = array('status' => 'ok',  'no_nota' => $no_nota);
				unset($_SESSION['bjual']);
			
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menyimpan transaksi, '.mysql_error());
		}
		echo json_encode($data);
	}else if($_POST['aksi_penjualan']=='edit'){
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
	}else if($_POST['aksi_penjualan']=='cek'){
		$username = $_POST['username'];
		$a = mysql_query("select * from karyawan where username='$username'");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = array('status' => 'ok');
		}else{
			$data[] = array('status' => 'no');
		}
		echo json_encode($data);
	}else if($_POST['aksi_penjualan']=='data barang'){
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
	}else if($_POST['aksi_penjualan']=='data member'){
		$a = mysql_query("select * from member order by nama_member asc");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$data[] = $b;
			}
			echo json_encode($data);
		}
	}else if($_POST['aksi_penjualan']=='hapus'){
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
//	unset($_SESSION['bkc']);
	if(!isset($_SESSION['bjual'])){
		echo '{
			"sEcho": 1,
			"iTotalRecords": "0",
			"iTotalDisplayRecords": "0",
			"aaData": []
		}';
	}else{
		$items = explode(',', $_SESSION['bjual']);
		$contents = array();
		foreach ($items as $item) {
			$contents[$item] = (isset($contents[$item])) ? $contents[$item] + 1 : 1;
		}
		$total = 0;
		foreach ($contents as $id=>$qty) {
	//		echo '*'.$id.'*';
			$dbarang = explode('@', $id);
			$id = $dbarang[0];
			$harga = $dbarang[1];
			$a = mysql_query("select * from barang where kode='$id'");
			$b = mysql_fetch_array($a);
			$total += ($harga*$qty);
			$data['aaData'][] = array('kode' => $b['kode'], 'nama_barang' => $b['nama_barang'], 'qty' => $qty, 'harga_jual' => $harga, 'sub_total' => ($harga*$qty), 'total' => $total);
		}
		echo json_encode($data);	
	}
}else if(isset($_GET['add'])){
//$_POST['kode']='12345';
//$_POST['
	$q = mysql_query("select * from barang where kode='$_POST[kode]'");
	$cek = mysql_num_rows($q);
	if($cek!=0){
		$kode = escape($_POST['kode']);
		$harga = escape($_POST['harga']);
		$qty = escape($_POST['jml']);
		if(isset($_SESSION['bjual'])){
			$newcart = '';
			$temp = explode(',', $_SESSION['bjual']);
			for($i=0; $i<sizeof($temp); $i++){
				$dcart = explode('@', $temp[$i]);
				if($dcart[0]==$kode){
					if ($newcart != '') {
						$newcart .= ','.$kode.'@'.$harga;
					} else {
						$newcart = $kode.'@'.$harga;
					}
				}else{
					if ($newcart != '') {
						$newcart .= ','.$temp[$i];
					} else {
						$newcart = $temp[$i];
					}
				}
			}
			for($i=1; $i<=$qty; $i++){
				$newcart .= ','.$kode.'@'.$harga;
			}
			$_SESSION['bjual'] = $newcart;
		}else{
			for($i=1; $i<=$qty; $i++){
				if(!isset($_SESSION['bjual'])){
					$_SESSION['bjual'] = $kode.'@'.$harga;
				}else{
					$_SESSION['bjual'] .= ','.$kode.'@'.$harga;
				}
			}
		}		
	}
	$y = mysql_fetch_array($q);
	$data[] = array('status' => $y['nama_barang']);
	echo json_encode($data);
}else if(isset($_GET['del'])){
	$items = explode(',',$_SESSION['bjual']);
	
		$newcart = '';
			foreach ($items as $item) {
				$temp = explode('@', $item);
				$cek = $temp[0];
				if ($_POST['kode'] != $cek) {
					if ($newcart != '') {
						$newcart .= ','.$item;
					} else {
						$newcart = $item;
					}
				}
			}
	
		if($newcart!=''){	
			$_SESSION['bjual'] = $newcart;
			$q = mysql_query("select * from barang where kode='$_POST[kode]'");
			$cek = mysql_num_rows($q);
			$y = mysql_fetch_array($q);
			$data[] = array('status' => $y['nama_barang'].' berhasil dihapus');
		}else{
			unset($_SESSION['bjual']);
			$data[] = array('status' => 'item barang kosong');
		}
	echo json_encode($data);
}else if(isset($_GET['cek'])){
	if(isset($_SESSION['bjual'])){
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
	
	$cart = explode(',',$_SESSION['bjual']);
	$newcart = '';
	$harga = 0;
	for($i=0; $i<sizeof($cart); $i++){
		$temp = explode('@', $cart[$i]);
		$cek = $temp[0];
		if ($kode != $cek) {
			if ($newcart != '') {
				$newcart .= ','.$cart[$i];
			} else {
				$newcart = $cart[$i];
			}
		}else{
			$harga = $temp[1];
		}
	}
	for($i=0; $i<$jml; $i++){
		if($newcart==''){
			$newcart = $kode.'@'.$harga;
		}else{
			$newcart .= ','.$kode.'@'.$harga;
		}
	}
	$_SESSION['bjual'] = $newcart;
	$q = mysql_query("select * from barang where kode='$kode'");
	$cek = mysql_num_rows($q);
	$y = mysql_fetch_array($q);
	$data[] = array('status' => $y['nama_barang']);
	echo json_encode($data);
}else if(isset($_GET['total'])){
	if(!isset($_SESSION['bjual'])){
		$total = 0;
	}else{
		$items = explode(',', $_SESSION['bjual']);
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
	unset($_SESSION['bjual']);
	echo json_encode(array('status' => 'berhasil mereset'));
}
function generate_no_nota(){
	$a = mysql_query("select no_nota from penjualan where tgl_keluar=date(now())");
	$jml = mysql_num_rows($a);
	if($jml==0){
		$no_pembayaran = 'PJ'.date("dmY").'-1';
	}else{
		while($b = mysql_fetch_array($a)){
			$urutans = explode("-", $b['no_nota']);
			$data[] = $urutans[1];
		}
		$no_pembayaran = 'PJ'.date("dmY").'-'.(max($data)+1);		
	}
	return $no_pembayaran;
}
?>