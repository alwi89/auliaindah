<?php
require_once("koneksi.php");
$username = escape($_POST['username']);
$pwd = escape($_POST['password']);
$a = mysql_query("select * from karyawan where username='$username' and password='$pwd' and status='aktif'");
$cek = mysql_num_rows($a);
if($cek==0){
	setcookie("msg", "username atau password salah", time()+10);
	header("location:login.php");
}else{
	$b = mysql_fetch_array($a);
	session_start();
	$x = mysql_query("select jenis from cabang where kode_cabang='$b[id_cabang]'");
	$y = mysql_fetch_array($x);
	$_SESSION['usrdridh'] = $b['username'];
	$_SESSION['lvldridh'] = $b['level'];
	header("location:index.php");
}
?>