<?php
require_once("koneksi.php");
session_start();
if(isset($_POST['aksi'])){
	if($_POST['aksi']=='tambah'){
		$kode = escape($_POST['kode']);
		$nama_barang = escape($_POST['nama_barang']);
		$harga_modal = escape($_POST['harga_modal']);
		$harga_jual = escape($_POST['harga_jual']);
		$markup = escape($_POST['markup']);
		$diskon = escape($_POST['diskon']);
		$saldo = escape($_POST['saldo']);
		$a = mysql_query("insert into barang(kode, nama_barang, harga_jual, harga_modal, markup, diskon) values('$kode', '$nama_barang', '$harga_jual', '$harga_modal', '$markup', '$diskon')");
		if($a){
			mysql_query("insert into history_harga(tgl_perubahan, kode, harga, harga_jual, markup, diskon) values(now(), '$kode', '$harga_modal', '$harga_jual', '$markup', '$diskon')");
			mysql_query("insert into history_saldo(kode, tanggal, saldo) values('$kode', now(), '$saldo')");
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil menambah barang');
		}else{
			$data[] = array('status' => 'failed', 'pesan' => 'gagal menambah barang, '.mysql_error());
		}
		echo json_encode($data);
	}else if($_POST['aksi']=='edit'){
		$kode_lama = escape($_POST['kode_lama']);
		$kode = escape($_POST['kode']);
		$nama_barang = escape($_POST['nama_barang']);
		$harga_jual = escape($_POST['harga_jual']);
		$harga_modal = escape($_POST['harga_modal']);
		$saldo = escape($_POST['saldo']);
		$markup = escape($_POST['markup']);
		$diskon = escape($_POST['diskon']);
		$a = mysql_query("update barang set kode='$kode', nama_barang='$nama_barang', harga_jual='$harga_jual', harga_modal='$harga_modal', markup='$markup', diskon='$diskon' where kode='$kode_lama'");
		if($a){
			mysql_query("insert into history_harga(tgl_perubahan, kode, harga, harga_jual, markup, diskon) values(now(), '$kode', '$harga_modal', '$harga_jual', '$markup', '$diskon')");
			mysql_query("update history_saldo set saldo='$saldo' where kode='$kode'");
			if(mysql_affected_rows()==0){
				mysql_query("insert into history_saldo(kode, tanggal, saldo) values('$kode', now(), '$saldo')");
			}
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil mengedit barang');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal mengedit barang');
		}
		echo json_encode($data);
	}else if($_POST['aksi']=='history'){
		$kode = $_POST['id'];
		$a = mysql_query("select * from history_harga where kode='$kode' order by id_history desc");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$data[] = $b;
			}
		}
		echo json_encode($data);
	}else if($_POST['aksi']=='preview'){
		$kode = $_POST['id'];
		$a = mysql_query("select * from barang b left join history_saldo s on b.kode=s.kode where b.kode='$kode'");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$a = mysql_query("select b.*, '0' saldo from barang b where kode='$kode'");
		}
		$data[] = mysql_fetch_array($a);
		echo json_encode($data);
	}else if($_POST['aksi']=='hapus'){
		$kode = $_POST['id'];
		$a = mysql_query("delete from barang where kode='$kode'");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil menghapus barang');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menghapus barang, '.mysql_error());
		}
		echo json_encode($data);
	}else if($_POST['aksi']=='import'){
		$data_import = explode('@_', $_POST['data']);
		$jml_berhasil = 0;
		$jml_gagal = 0;
		$yang_gagal = "";
		for($i=1; $i<sizeof($data_import); $i++){
			$isi = $data_import[$i];
			$a = mysql_query("insert into barang(kode, nama_barang, harga_modal, harga_jual) values($isi)");
			//$cek = "insert into barang(kode, nama_barang, tipe, harga_modal) values($isi)";
			if($a){
				$kodes = explode(', ', $isi);
				$kode = $kodes[0];
				$harga_modal = $kodes[2];
				$harga_jual = $kodes[3];
				mysql_query("insert into history_harga(tgl_perubahan, kode, harga, harga_jual) values(now(), $kode, $harga_modal, $harga_jual)");
				$jml_berhasil += 1;
			}else{
				$jml_gagal += 1;
				if($yang_gagal==""){
					$yang_gagal = $isi.' =&gt; '.mysql_error();
				}else{
					$yang_gagal .= "<br />$isi".' =&gt; '.mysql_error();
				}
			}
			//$data[] = array('status' => 'ok',  'pesan' => "import berhasil : $jml_berhasil<br />import gagal : $jml_gagal<br />$cek");
		}		
		$data[] = array('status' => 'ok',  'pesan' => "import berhasil : $jml_berhasil<br /><span style='color:red;'>import gagal : $jml_gagal<br />$yang_gagal</span>");
		echo json_encode($data);
	}
}
if(isset($_GET['data'])){
	$a = mysql_query("select * from barang order by nama_barang asc");
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
			$qSaldo = mysql_query("select saldo from history_saldo where kode='$b[kode]'");
			$jmlQuery = mysql_num_rows($qSaldo);
			if($jmlQuery==0){
				$saldo = '0';
			}else{
				$hSaldo = mysql_fetch_array($qSaldo);
				$saldo = $hSaldo['saldo'];
			}
			$data['aaData'][] = array('kode' => $b['kode'], 'nama_barang' => $b['nama_barang'], 'harga_jual' => $b['harga_jual'], 'harga_modal' => $b['harga_modal'], 'saldo' => $saldo, 'markup' => $b['markup'], 'diskon' => $b['diskon']);
		}
		echo json_encode($data);
	}	
}
?>