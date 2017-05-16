<?php
require_once("koneksi.php");
$aksi = escape($_POST['aksi']);
if($aksi=='biodata'){
	$ada = escape($_POST['ada']);
	$nama = escape($_POST['nama']);
	$alamat = escape($_POST['alamat']);
	$kontak = escape($_POST['kontak']);
	$logo = str_replace(" ", "_", $_FILES['logo']['name']);
	$source = $_FILES['logo']['tmp_name'];
	$dest = "pengaturan/$logo";
	if($ada=='belum'){
		$a = mysql_query("insert into pengaturan(nama_toko, alamat, kontak, logo) values('$nama', '$alamat', '$kontak', '$logo')");
		if($a){
			@copy($source, $dest);
			setcookie("berhasil", "berhasil menambah pengaturan", time()+2);
		}else{
			setcookie("gagal", "gagal menambah pengaturan", time()+2);
		}
	}else{
		$a = mysql_query("update pengaturan set nama_toko='$nama', alamat='$alamat', kontak='$kontak'");
		if($a){
			if(strlen($source)!=0){
				$b = mysql_query("update pengaturan set logo='$logo'");
				if($b){
					@copy($source, $dest);
				}
			}
			setcookie("berhasil", "berhasil menyimpan pengaturan", time()+2);
		}else{
			setcookie("gagal", "gagal menyimpan pengaturan", time()+2);
		}
	}
	header("location:index.php?h=pengaturan");
}else if($aksi=='pengaturan'){
	$pjg_kertas = escape($_POST['pjg_kertas']);
	$pjg_logo = escape($_POST['pjg_logo']);
	$lbr_logo = escape($_POST['lbr_logo']);
	$font_toko = escape($_POST['font_toko']);
	$weight_toko = escape($_POST['weight_toko']);
	$font_alamat = escape($_POST['font_alamat']);
	$weight_alamat = escape($_POST['weight_alamat']);
	$font_kontak = escape($_POST['font_kontak']);
	$weight_kontak = escape($_POST['weight_kontak']);
	$ukuran_font = escape($_POST['ukuran_font']);
	$a = mysql_query("update pengaturan set panjang_nota='$pjg_kertas', panjang_logo='$pjg_logo', lebar_logo='$lbr_logo', font_toko='$font_toko', weight_toko='$weight_toko', font_alamat='$font_alamat', weight_alamat='$weight_alamat', font_kontak='$font_kontak', weight_kontak='$weight_kontak', ukuran_font='$ukuran_font'");
	if($a){
		setcookie("berhasil", "berhasil menyimpan pengaturan", time()+2);
	}else{
		setcookie("gagal", "gagal menyimpan pengaturan", time()+2);
	}
	header("location:index.php?h=pengaturan");
}else if($aksi=='reset'){
	if(isset($_POST['stok'])){
		$a = mysql_query("delete from history_saldo");
		if($a){
			setcookie("berhasil", "berhasil mengosongkan semua stok", time()+2);
		}else{
			setcookie("gagal", "gagal mengosongkan semua stok", time()+2);
		}
	}
	if(isset($_POST['reset'])){
		mysql_query("delete from penjualan");
		mysql_query("delete from barang_masuk");
		mysql_query("delete from member");
		mysql_query("delete from suplier");
		mysql_query("delete from barang");
		mysql_query("delete from modal_harian");
		setcookie("berhasil", "berhasil melakukan reset data", time()+2);
	}
	header("location:index.php?h=pengaturan");
}
?>