<?php
require_once("koneksi.php");
session_start();
if(isset($_POST['aksi_member'])){
	if($_POST['aksi_member']=='tambah'){
		$kode_member = escape($_POST['kode_member']);
		$nama_member = escape($_POST['nama_member']);
		$alamat = escape($_POST['alamat']);
		$no_tlp = escape($_POST['no_tlp']);
		$a = mysql_query("insert into member(kode_member, nama_member, alamat, no_tlp) values('$kode_member', '$nama_member', '$alamat', '$no_tlp')");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil menambah member');
		}else{
			$data[] = array('status' => 'failed', 'pesan' => 'gagal menambah member, '.mysql_error());
		}
		echo json_encode($data);
	}else if($_POST['aksi_member']=='edit'){
		$kode_lama_member = escape($_POST['kode_lama_member']);
		$kode_member = escape($_POST['kode_member']);
		$nama_member = escape($_POST['nama_member']);
		$alamat = escape($_POST['alamat']);
		$no_tlp = escape($_POST['no_tlp']);
		$a = mysql_query("update member set kode_member='$kode_member', nama_member='$nama_member', alamat='$alamat', no_tlp='$no_tlp' where kode_member='$kode_lama_member'");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil mengedit member');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal mengedit member, '.mysql_error());
		}
		echo json_encode($data);
	}else if($_POST['aksi_member']=='history'){
		$kode = $_POST['id'];
		$a = mysql_query("select * from history_harga where kode='$kode' order by id_history desc");
		while($b = mysql_fetch_array($a)){
			$data[] = $b;
		}
		echo json_encode($data);
	}else if($_POST['aksi_member']=='preview'){
		$kode = $_POST['id'];
		$a = mysql_query("select * from member where kode_member='$kode'");
		$data[] = mysql_fetch_array($a);
		echo json_encode($data);
	}else if($_POST['aksi_member']=='hapus'){
		$kode = $_POST['id'];
		$a = mysql_query("delete from member where kode_member='$kode'");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil menghapus member');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menghapus member, '.mysql_error());
		}
		echo json_encode($data);
	}else if($_POST['aksi_member']=='import'){
		$data_import = explode('@_', $_POST['data']);
		$jml_berhasil = 0;
		$jml_gagal = 0;
		$yang_gagal = "";
		for($i=1; $i<sizeof($data_import); $i++){
			$isi = $data_import[$i];
			$a = mysql_query("insert into member(kode_member, nama_member, alamat, no_tlp) values($isi)");
			//$cek = "insert into barang(kode, nama_barang, tipe, harga_modal) values($isi)";
			if($a){
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
	$a = mysql_query("select * from member order by nama_member asc, alamat asc");
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
?>