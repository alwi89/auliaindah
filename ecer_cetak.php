<?php
session_start();
$id_cabang = $_SESSION['cbg'];
require_once('koneksi.php');
$q_cbg = mysql_query("select * from cabang where kode_cabang='$id_cabang'");
$h_cbg = mysql_fetch_array($q_cbg);
?>
<html moznomarginboxes mozdisallowselectionprint>
	<head>
    	<title>nota ecer <?php echo $_GET['id']; ?></title>
        <style>
		html,body{
			/*
			width:28cm;
			height:24cm;
			/*font-size:14px;*/
		}
		
		</style>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div align="center"><b>NOTA ECER<br /><?php echo $h_cbg['nama_cabang']; ?></b><br /><i>alamat : <?php echo $h_cbg['alamat']; ?></i></div><hr />
            <?php
			$a = mysql_query("select e.*, k.nama from ecer e join karyawan k on e.username=k.username where no_nota='$_GET[id]'");
			$b = mysql_fetch_array($a);
			?>
            <table>
            	<tr>
                	<td>Tgl</td>
                    <td>: <?php echo date('d/m/Y', strtotime($b['tgl_keluar'])); ?></td>
                </tr>
                <tr>
                	<td>Kasir</td>
                    <td>: <?php echo $b['nama']; ?></td>
                </tr>
                <tr>
                	<td>No. Nota</td>
                    <td>: <?php echo $b['no_nota']; ?></td>
                </tr>
            </table>
            <?php
			$c = mysql_query("select d.kode, tipe, nama_barang, d.harga, qty, d.harga*qty sub_total from ecer_detail d join barang b on d.kode=b.kode where no_nota='$_GET[id]'");
			?>
            <table width="100%" cellspacing="0" cellpadding="3" border="1">
            	<tr>
                    <th>KODE</th>
                    <th>TIPE</th>
                    <th>NAMA BARANG</th>
                    <th>HRG</th>
                    <th>QTY</th>
                    <th>SUB</th>
               </tr>
               <?php $total=0; $jml=0; while($d = mysql_fetch_array($c)){ ?>
               <tr>
                    <td><?php echo $d['kode']; ?></td>
                    <td><?php echo $d['tipe']; ?></td>
                    <td><?php echo $d['nama_barang']; ?></td>
                    <td><?php echo toIdr($d['harga']); ?></td>
                    <td><?php echo $d['qty']; ?></td>
                    <td><?php echo toIdr($d['sub_total']); ?></td>
               </tr>
               <?php $total+=$d['sub_total']; $jml+=$d['qty']; } ?>
               <tr>
               		<td colspan="4" align="right"><b>Total</b></td>
                    <td><b><?php echo $jml; ?></b></td>
                    <td><b><?php echo toIdr($total); ?></b></td>
               </tr>
               <tr>
               		<td colspan="5" align="right"><b>Dibayar</b></td>
                    <td><b><?php echo toIdr($b['dibayar']); ?></b></td>
               </tr>
               <tr>
               		<td colspan="5" align="right"><b>Kembali</b></td>
                    <td><b><?php echo toIdr($b['kembali']); ?></b></td>
               </tr>
            </table>
            <br />
            <i>terimakasih atas kunjungan anda, barang yang sudah dibeli tidak dapat ditukar / dikembalikan</i>
        </div>
    </body>
</html>