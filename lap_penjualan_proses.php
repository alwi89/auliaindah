<?php
require_once("koneksi.php");
/*
$_POST['aksi']='data';
$_POST['dari']='01/08/2016';
$_POST['sampai']='30/09/2016';
$_POST['member']='SEMUA';
*/
if(isset($_POST['aksi'])){
	if($_POST['aksi']=='data'){
		$daris = explode("/", escape($_POST['dari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['sampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$member = $_POST['member'];
		if($member=='SEMUA'){	
			$a = mysql_query("select m.kode_member, nama_member, alamat, tgl_keluar, m.no_nota, nama, d.kode, nama_barang, harga, qty, harga*qty sub_total, d.harga_modal, d.harga_modal*qty total_modal, (harga*qty)-(d.harga_modal*qty) laba from karyawan k join penjualan m on k.username=m.username join penjualan_detail d on m.no_nota=d.no_nota join barang b on d.kode=b.kode left join member s on m.kode_member=s.kode_member where tgl_keluar between '$dari' and '$sampai' order by nama_member asc, alamat asc");
		}else{
			$a = mysql_query("select m.kode_member, nama_member, alamat, tgl_keluar, m.no_nota, nama, d.kode, nama_barang, harga, qty, harga*qty sub_total, d.harga_modal, d.harga_modal*qty total_modal, (harga*qty)-(d.harga_modal*qty) laba from karyawan k join penjualan m on k.username=m.username join penjualan_detail d on m.no_nota=d.no_nota join barang b on d.kode=b.kode left join member s on m.kode_member=s.kode_member where m.kode_member='$member' and tgl_keluar between '$dari' and '$sampai' order by nama_member asc, alamat asc");
		}
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				if($member=='SEMUA'){
					$qKekurangan = mysql_query("select sum(kekurangan) kekurangan, sum(dibayar-kembali) dibayar from penjualan where tgl_keluar between '$dari' and '$sampai'");
				}else{
					$qKekurangan = mysql_query("select sum(kekurangan) kekurangan, sum(dibayar-kembali) dibayar from penjualan where kode_member='$member' and tgl_keluar between '$dari' and '$sampai'");
				}
				$jml_kekurangan = mysql_num_rows($qKekurangan);
				if($jml_kekurangan==0){
					$dibayar = 'data tidak ditemukan';
					$kekurangan = 'data tidak ditemukan';
				}else{
					$hKekurangan = mysql_fetch_array($qKekurangan);
					$dibayar = $hKekurangan['dibayar'];
					$kekurangan = $hKekurangan['kekurangan'];
				}
				if($b['nama_member']==NULL){
					$nama_member = "bukan member";
					$alamat = "";
				}else{
					$nama_member = $b['nama_member'];
					$alamat = $b['alamat'];
				}
				$data[] = array('kode_member' => $b['kode_member'], 'nama_member' => $nama_member, 'alamat' => $alamat, 'tgl_keluar' => $b['tgl_keluar'], 'no_nota' => $b['no_nota'], 'nama' => $b['nama'], 'kode' => $b['kode'], 'nama_barang' => $b['nama_barang'], 'harga' => $b['harga'], 'qty' => $b['qty'], 'sub_total' => $b['sub_total'], 'dibayar' => $dibayar, 'kekurangan' => $kekurangan, 'harga_modal' => $b['harga_modal'], 'laba' => $b['laba'], 'total_modal' => $b['total_modal']);
			}
			
		}
//		echo json_encode(array_merge(json_decode($data, true),json_decode($data_kekurangan, true)));
		echo json_encode($data);
	}else if($_POST['aksi']=='Cetak'){
		$daris = explode("/", escape($_POST['pjdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['pjsampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];	
		$member = $_POST['lappjsup'];
		if($member=='SEMUA'){
			$x = mysql_query("select * from member where kode_member in(select kode_member from penjualan where tgl_keluar between '$dari' and '$sampai')");
			$nama_member = "semua";
		}else{
			$x = mysql_query("select * from member where kode_member in(select kode_member from penjualan where kode_member='$member' and tgl_keluar between '$dari' and '$sampai')");
			$m = mysql_query("select nama_member from member where kode_member='$member'");
			$n = mysql_fetch_array($m);
			$nama_member = $n['nama_member'];
		}
		$jml = mysql_num_rows($x);
		if($jml==0){
			echo '<script>alert("data kosong");javascript:history.go(-1);</script>';
		}else{
			require_once('lap_penjualan_cetak.php');
		}
	}else if($_POST['aksi']=='Export'){
		$daris = explode("/", escape($_POST['pjdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['pjsampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$member = $_POST['lappjsup'];
		header("location:lap_penjualan_excel.php?dari=$dari&sampai=$sampai&member=".urlencode($member));
	}else if($_POST['aksi']=='data member'){
		$daris = explode("/", escape($_POST['dari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['sampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$member = $_POST['member'];
		$a = mysql_query("select * from member order by nama_member asc, alamat asc");
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