<?php
$host = 'localhost';
$usr = "root";
$pwd = '';
$db = "aulia_indah";

mysql_connect($host, $usr, $pwd) or die('gagal koneksi server');
mysql_select_db($db) or die('database error');
function escape($string){
	return mysql_real_escape_string($string);
}
function toIdr($number){
	return number_format($number);	
}
function hak_akses($level){
	if($_SESSION['lvldridh']!=$level) {
		echo '<script>window.location="index.php?h=terlarang"</script>';
	}
}
?>