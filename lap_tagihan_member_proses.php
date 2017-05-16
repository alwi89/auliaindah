<?php
session_start();
require_once("koneksi.php");
/*
$_POST['aksi']='data member';
$_POST['dari']='01/03/2016';
$_POST['sampai']='31/03/2016';
*/
if(isset($_POST['aksi'])){
	if($_POST['aksi']=='data'){
		$daris = explode("/", escape($_POST['dari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['sampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$member = $_POST['member'];	
		$a = mysql_query("select no_nota, nama_member, no_tlp, pin_bb, total, dibayar, kekurangan, potongan, tgl_tempo from grosir g join member s on g.kode_member=s.kode_member where g.kode_member='$member' and kekurangan<>'0' and tgl_tempo between '$dari' and '$sampai' order by tgl_tempo asc");
		//select no_nota, nama_member, no_tlp, pin_bb, total, dibayar, kekurangan, potongan, tgl_tempo from grosir g join member s on g.kode_member=s.kode_member where kode_member='$member' and kekurangan<>'0' and g.id_cabang='$id_cabang' and tgl_tempo between '$dari' and '$sampai'
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$data[] = $b;
			}
		}
		echo json_encode($data);
	}else if($_POST['aksi']=='Cetak'){
		$daris = explode("/", escape($_POST['tmdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['tmsampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$member = $_POST['laptmmember'];
		if($member=='ALL'){
			$x = mysql_query("select s.*, nama_cabang from member s join cabang c on s.id_cabang=c.kode_cabang where kode_member in(select kode_member from grosir where kekurangan<>'0' and tgl_keluar between '$dari' and '$sampai') order by nama_cabang asc, nama_member asc");
			$nama_member = "semua member dari cabang dan pusat";
		}else if($member=='SEMUA'){
			$x = mysql_query("select s.*, nama_cabang from member s join cabang c on s.id_cabang=c.kode_cabang where id_cabang='$id_cabang' and kode_member in(select kode_member from grosir where kekurangan<>'0' and tgl_keluar between '$dari' and '$sampai') order by nama_cabang asc, nama_member asc");
			$nama_member = "semua member yang dipusat";
		}else{
			$x = mysql_query("select s.*, nama_cabang from member s join cabang c on s.id_cabang=c.kode_cabang where kode_member in(select kode_member from grosir where kekurangan<>'0' and grosir.kode_member='$member' and tgl_keluar between '$dari' and '$sampai') order by nama_cabang asc, nama_member asc");
			$q_nama_member = mysql_query("select * from member where kode_member='$member'");
			$h_nama_member = mysql_fetch_array($q_nama_member);
			$nama_member = $h_nama_member['nama_member'];
		}
		$jml = mysql_num_rows($x);
		if($jml==0){
			echo '<script>alert("data kosong");javascript:history.go(-1);</script>';
		}else{
			require_once('lap_tagihan_member_cetak.php');
		}
	}else if($_POST['aksi']=='Export'){
		$daris = explode("/", escape($_POST['tmdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['tmsampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		header("location:lap_tagihan_member_excel.php?dari=$dari&sampai=$sampai");
	}else if($_POST['aksi']=='data member'){
			$a = mysql_query("select * from member order by nama_member asc");
			$jml = mysql_num_rows($a);
			if($jml==0){
				$data[] = NULL;
			}else{
				while($b = mysql_fetch_array($a)){
					$data[] = array('kode_member' => $b['kode_member'], 'nama_member' => $b['nama_member'], 'alamat' => $b['alamat']);
				}
			}
		echo json_encode($data);
	}else if($_POST['aksi']=='list member'){
		$daris = explode("/", escape($_POST['dari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['sampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$member = $_POST['member'];
		if($member=='ALL'){
			$a = mysql_query("select p.kode_member, no_nota, nama_member, no_tlp, alamat, total, dibayar, kekurangan, tgl_tempo  from member m join penjualan p on m.kode_member=p.kode_member where kekurangan<>'0' and tgl_keluar between '$dari' and '$sampai' order by nama_member asc, alamat asc");
		}else{
			$a = mysql_query("select p.kode_member, no_nota, nama_member, no_tlp, alamat, total, dibayar, kekurangan, tgl_tempo  from member m join penjualan p on m.kode_member=p.kode_member where p.kode_member='$member' and kekurangan<>'0' and tgl_keluar between '$dari' and '$sampai' order by nama_member asc, alamat asc");
		}
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$data[] = $b;
			}
		}
		echo json_encode($data);
	}
	
}
?>