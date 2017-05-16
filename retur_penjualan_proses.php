<?php
session_start();
/*
$_POST['aksi_penjualan']='tambah';
$_POST['tgl_keluar'] = '30/08/2016';
$_POST['kekurangan']='0';
$_POST['tgl_tempo']='31/08/2016';
$_POST['dibayar']='70000';
$_POST['no_nota']='1';
$_POST['total']='70000';
*/
require_once("koneksi.php");
if(isset($_POST['aksi_penjualan'])){
	if($_POST['aksi_penjualan']=='tambah'){
		$no_nota = escape($_POST['no_nota']);
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
		if($dibayar>escape($_POST['total'])){
			$kembali = escape($_POST['dibayar'])-escape($_POST['total']);
		}else{
			$kembali = '0';
		}
		$a = mysql_query("update penjualan set tgl_keluar='$tgl_keluar', username='$username', dibayar='$dibayar', kekurangan='$kekurangan', tgl_tempo=$tgl_tempo, kembali='$kembali' where no_nota='$no_nota'");
		if($a){
				$hit = mysql_query("select sum(qty) as jml, sum(harga*qty) as total from penjualan_detail where no_nota='$no_nota'");
				$hHit = mysql_fetch_array($hit);
				mysql_query("update penjualan set total='$hHit[total]', jumlah='$hHit[jml]' where no_nota='$no_nota'");
				if($kekurangan=='0'){
					$dibayar = $hHit['total'];
				}
				mysql_query("delete from history_pembayaran_penjualan where no_nota='$no_nota'");
				mysql_query("insert into history_pembayaran_penjualan(tgl_bayar, jumlah_cicil, kekurangan_cicil, no_nota) values('$tgl_keluar', '$dibayar', '$kekurangan', '$no_nota')");
				$data[] = array('status' => 'ok',  'no_nota' => $no_nota);			
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menyimpan transaksi, '.mysql_error());
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
		$no_nota = escape($_POST['no_nota']);
		$a = mysql_query("delete from penjualan where no_nota='$no_nota'");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil menghapus data penjualan');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menghapus data penjualan, '.mysql_error());
			
		}
		echo json_encode($data);
	}
}
if(isset($_GET['cart'])){
	$no = $_GET['no'];
	$a = mysql_query("select * from penjualan p join penjualan_detail d on p.no_nota=d.no_nota join barang b on d.kode=b.kode left join member m on p.kode_member=m.kode_member where p.no_nota='$no'");
	$total = 0;
	while($b = mysql_fetch_array($a)) {
			$total += ($b['harga']*$b['qty']);
			$data['aaData'][] = array('kode' => $b['kode'], 'nama_barang' => $b['nama_barang'], 'qty' => $b['qty'], 'harga_jual' => $b['harga'], 'sub_total' => ($b['harga']*$b['qty']), 'total' => $total, 'nama_member' => $b['nama_member'], 'dibayar' => $b['dibayar'], 'kekurangan' => $b['kekurangan'], 'tgl_tempo' => $b['tgl_tempo'], 'tgl_keluar' => $b['tgl_keluar']);
	}
	echo json_encode($data);	
	
}else if(isset($_GET['add'])){
	$kode = escape($_POST['kode']);
	$harga = escape($_POST['harga']);
	$qty = escape($_POST['jml']);
	$no_nota = $_POST['no_nota'];
	$q = mysql_query("select * from barang where kode='$kode'");
	$data_barang = mysql_fetch_array($q);
	$a = mysql_query("select * from penjualan_detail where no_nota='$no_nota' and kode='$kode'");
	$cek_ada = mysql_num_rows($a);
	if($cek_ada==0){
		mysql_query("insert into penjualan_detail(no_nota, kode, qty, harga_modal, harga) values('$no_nota', '$kode', '$qty', '$data_barang[harga_modal]', '$harga')");
		mysql_query("update history_saldo set saldo=saldo-$qty where kode='$kode'");
		if(mysql_affected_rows()==0){
			mysql_query("insert into history_saldo(kode, tanggal, saldo) values('$kode', now(), '-$qty')");
		}
	}else{
		mysql_query("update penjualan_detail set qty=qty+$qty, harga_modal='$data_barang[harga_modal]', harga='$harga' where no_nota='$no_nota' and kode='$kode'");
		mysql_query("update history_saldo set saldo=saldo-$qty where kode='$kode'");
		if(mysql_affected_rows()==0){
			mysql_query("insert into history_saldo(kode, tanggal, saldo) values('$kode', now(), '-$qty')");
		}
	}
	$data[] = array('status' => $data_barang['nama_barang']);
	echo json_encode($data);
}else if(isset($_GET['del'])){
	$kode = escape($_POST['kode']);
	$no_nota = $_POST['no_nota'];
	$q = mysql_query("select * from barang where kode='$kode'");
	$data_barang = mysql_fetch_array($q);
	$a = mysql_query("select * from penjualan_detail where no_nota='$no_nota' and kode='$kode'");
	$b = mysql_fetch_array($a);
	mysql_query("delete from penjualan_detail where no_nota='$no_nota' and kode='$kode'");
	mysql_query("update history_saldo set saldo=saldo+$b[qty] where kode='$kode'");
	if(mysql_affected_rows()==0){
		mysql_query("insert into history_saldo(kode, tanggal, saldo) values('$kode', now(), '$b[qty]')");
	}
	$data[] = array('status' => $data_barang['nama_barang'].' berhasil dihapus');
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
	$no_nota = $_POST['no_nota'];
	$q = mysql_query("select * from barang where kode='$kode'");
	$data_barang = mysql_fetch_array($q);
	$a = mysql_query("select * from penjualan_detail where no_nota='$no_nota' and kode='$kode'");
	$b = mysql_fetch_array($a);
	mysql_query("update penjualan_detail set qty=$jml where no_nota='$no_nota' and kode='$kode'");
	mysql_query("update history_saldo set saldo=saldo+$b[qty] where kode='$kode'");
	if(mysql_affected_rows()==0){
		mysql_query("insert into history_saldo(kode, tanggal, saldo) values('$kode', now(), '$b[qty]')");
	}
	mysql_query("update history_saldo set saldo=saldo-$jml where kode='$kode'");
	$data[] = array('status' => $data_barang['nama_barang']);
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
}else if(isset($_GET['data'])){
	$a = mysql_query("select * from penjualan p left join member m on p.kode_member=m.kode_member join karyawan k on p.username=k.username order by tgl_keluar desc, no_nota asc");
	$jml = mysql_num_rows($a);
	if($jml==0){
		echo '{
				"sEcho": 1,
				"iTotalRecords": "0",
				"iTotalDisplayRecords": "0",
				"aaData": []
		}';
	}else{
		while($b = mysql_fetch_array($a)){
			$data['aaData'][] = $b;
		}
		echo json_encode($data);
	}	
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