<?php
session_start();
require_once("koneksi.php");
$id_cabang = $_SESSION['cbg'];
/*
$_POST['aksi']='data';
$_POST['dari']='01/03/2016';
$_POST['sampai']='31/03/2016';
*/
if(isset($_POST['aksi'])){
	if($_POST['aksi']=='data'){
		$daris = explode("/", escape($_POST['dari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['sampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$kode_sales = $_POST['lapgrossales'];
		$status = $_POST['stts'];
		if($status=='lunas'){
			$kekurangan='kekurangan=0';
		}else{
			$kekurangan='kekurangan<>0';
		}
		$a = mysql_query("select dibayar, kekurangan, tgl_keluar, m.no_nota, nama_sales, nama, d.kode, tipe, nama_barang, d.harga_modal, d.harga, qty, d.harga*qty sub_total from karyawan k join grosir m on k.username=m.username join grosir_detail d on m.no_nota=d.no_nota left join barang b on d.kode=b.kode join sales c on m.kode_sales=c.kode_sales where m.kode_sales='$kode_sales' and $kekurangan and tgl_keluar between '$dari' and '$sampai' order by tgl_keluar asc, no_nota asc");
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
		$daris = explode("/", escape($_POST['grosirdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['grosirsampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];	
		$kode_sales = $_POST['lapgrossales'];
		$status = $_POST['stts'];
		if($status=='lunas'){
			$kekurangan='kekurangan=0';
		}else{
			$kekurangan='kekurangan<>0';
		}
		$a = mysql_query("select dibayar, kekurangan, tgl_keluar, m.no_nota, nama_sales, nama, d.kode, tipe, nama_barang, d.harga_modal, d.harga, qty, d.harga*qty sub_total from karyawan k join grosir m on k.username=m.username join grosir_detail d on m.no_nota=d.no_nota left join barang b on d.kode=b.kode join sales c on m.kode_sales=c.kode_sales where m.kode_sales='$kode_sales' and $kekurangan and tgl_keluar between '$dari' and '$sampai' order by tgl_keluar asc, no_nota asc");
		$qNama = mysql_query("select nama_sales, nama_cabang from sales s join cabang c on s.id_cabang=c.kode_cabang where kode_sales='$kode_sales'");
		$hNama = mysql_fetch_array($qNama);
		$nama_sales = $hNama['nama_sales'];
		$jml = mysql_num_rows($a);
		if($jml==0){
			echo '<script>alert("data kosong");javascript:history.go(-1);</script>';
		}else{
			require_once('lap_grosir_cetak.php');
		}
	}else if($_POST['aksi']=='Export'){
		$daris = explode("/", escape($_POST['grosirdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['grosirsampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$sales = urlencode($_POST['lapgrossales']);
		$status = urlencode($_POST['stts']);
		header("location:lap_grosir_excel.php?dari=$dari&sampai=$sampai&status=$status&sales=$sales");
	}else if($_POST['aksi']=='data sales'){
		if($_SESSION['jns']=='pusat'){
			$a = mysql_query("select s.*, nama_cabang from sales s join cabang c on s.id_cabang=c.kode_cabang order by nama_cabang asc, nama_sales asc");
		}else{
			$a = mysql_query("select s.*, nama_cabang from sales s join cabang c on s.id_cabang=c.kode_cabang where id_cabang='$id_cabang' order by nama_cabang asc, nama_sales asc");
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