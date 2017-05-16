<?php
require_once('koneksi.php');
session_start();
if(isset($_GET['data_sekarang'])){
		$a = mysql_query("select p.*, nama_member, no_tlp, alamat, datediff(tgl_tempo, curdate()) hit_mundur from penjualan p join member m on p.kode_member=m.kode_member where tgl_tempo BETWEEN curdate() and curdate() + interval 1 day order by tgl_tempo asc, nama_member asc, alamat asc");
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
}else if(isset($_GET['data_sekarang_suplier'])){
		$a = mysql_query("select m.*, nama_suplier, no_tlp, alamat, datediff(tgl_tempo, curdate()) hit_mundur from barang_masuk m join suplier s on m.kode_suplier=s.kode_suplier where tgl_tempo BETWEEN curdate() and curdate() + interval 1 day order by tgl_tempo asc, nama_suplier asc, alamat asc");
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
}else if(isset($_GET['jml'])){
	$qMember = mysql_query("select p.*, nama_member, no_tlp, alamat, datediff(tgl_tempo, curdate()) hit_mundur from penjualan p join member m on p.kode_member=m.kode_member where tgl_tempo BETWEEN curdate() and curdate() + interval 1 day order by tgl_tempo asc, nama_member asc, alamat asc");
	$jmlMember = mysql_num_rows($qMember);
	
	$qSuplier = mysql_query("select m.*, nama_suplier, no_tlp, alamat, datediff(tgl_tempo, curdate()) hit_mundur from barang_masuk m join suplier s on m.kode_suplier=s.kode_suplier where tgl_tempo BETWEEN curdate() and curdate() + interval 1 day order by tgl_tempo asc, nama_suplier asc, alamat asc");
	$jmlSuplier = mysql_num_rows($qSuplier);
	
	$jmlTotal = $jmlMember+$jmlSuplier;
	$data[] = array('jml' => $jmlTotal);	
	echo json_encode($data);
	
}
/*
$_POST['aksi_home']='edit tm';
$_POST['tm_no_nota'] = 'PJ01092016-7';
*/
if(isset($_POST['aksi_home'])){
	if($_POST['aksi_home']=='edit tm'){
		$no_nota = escape($_POST['tm_no_nota']);
		$a = mysql_query("select p.*, tgl_bayar, jumlah_cicil, kekurangan_cicil, nama_member, no_tlp, alamat, datediff(tgl_tempo, curdate()) hit_mundur from penjualan p join member m on p.kode_member=m.kode_member left join history_pembayaran_penjualan h on p.no_nota=h.no_nota where p.no_nota='$no_nota'");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$data[] = $b;
			}
		}
		echo json_encode($data);
	}else if($_POST['aksi_home']=='simpan'){
		$no_nota = escape($_POST['tm_no_nota']);
		$sudahdibayar = escape($_POST['tmsudahdibayar']);
		$dibayar = $sudahdibayar+escape($_POST['tm_dibayar']);
		$tgl_tempos = explode("/", escape($_POST['tm_tgl_tempo']));
		$tgl_tempo = $tgl_tempos[2].'-'.$tgl_tempos[1].'-'.$tgl_tempos[0];
		$kekurangan = escape($_POST['tm_kekurangan']);
		if($kekurangan=='0'){
			$tgl_tempo = 'null';
		}else{
			$tgl_tempo = "'$tgl_tempo'";
		}
		$a = mysql_query("update penjualan set tgl_tempo=$tgl_tempo, dibayar='$dibayar', kekurangan='$kekurangan' where no_nota='$no_nota'");
		if($a){
			mysql_query("insert into history_pembayaran_penjualan(tgl_bayar, jumlah_cicil, kekurangan_cicil, no_nota) values(now(), '$_POST[tm_dibayar]', '$kekurangan', '$no_nota')");
			$data[] = array('status' => 'berhasil menyimpan tagihan');
		}else{
			$data[] = array('status' => 'gagal menyimpan tagihan');
		}
		echo json_encode($data);
	}else if($_POST['aksi_home']=='edit tp'){
		$no_nota = escape($_POST['tp_no_nota']);
		$a = mysql_query("select m.*, tgl_bayar, jumlah_cicil, kekurangan_cicil, nama_suplier, no_tlp, datediff(tgl_tempo, curdate()) hit_mundur from barang_masuk m join suplier s on m.kode_suplier=s.kode_suplier left join history_pembayaran_suplier h on m.no_nota=h.no_nota where m.no_nota='$no_nota'");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$data[] = $b;
			}
		}
		echo json_encode($data);
	}else if($_POST['aksi_home']=='simpan tp'){
		$no_nota = escape($_POST['tp_no_nota']);
		$sudahdibayar = escape($_POST['tpsudahdibayar']);
		$dibayar = $sudahdibayar+escape($_POST['tp_dibayar']);
		$tgl_tempos = explode("/", escape($_POST['tp_tgl_tempo']));
		$tgl_tempo = $tgl_tempos[2].'-'.$tgl_tempos[1].'-'.$tgl_tempos[0];
		$kekurangan = escape($_POST['tp_kekurangan']);
		if($kekurangan=='0'){
			$tgl_tempo = 'null';
		}else{
			$tgl_tempo = "'$tgl_tempo'";
		}
		$a = mysql_query("update barang_masuk set tgl_tempo=$tgl_tempo, dibayar='$dibayar', kekurangan='$kekurangan' where no_nota='$no_nota'");
		if($a){
			mysql_query("insert into history_pembayaran_suplier(tgl_bayar, jumlah_cicil, kekurangan_cicil, no_nota) values(now(), '$_POST[tp_dibayar]', '$kekurangan', '$no_nota')");
			$data[] = array('status' => 'berhasil menyimpan tagihan');
		}else{
			$data[] = array('status' => 'gagal menyimpan tagihan');
		}
		echo json_encode($data);
	}
}
?>