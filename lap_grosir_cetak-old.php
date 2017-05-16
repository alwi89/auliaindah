<html>
	<head>
    	<title>laporan transaksi grosir periode <?php echo $_POST['grosirdari']; ?> s/d <?php echo $_POST['grosirsampai']; ?></title>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div style="font-size:18px"><b>Laporan Transaksi Grosir<br />Periode : <?php echo $_POST['grosirdari']; ?> s/d <?php echo $_POST['grosirsampai']; ?><br />Sales : <?php echo $nama_sales; ?><br />Status : <?php echo $status; ?><br />Zidane Cell</b></div><hr />
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
            	<tr>
                	<th>TGL MASUK</th>
                    <th>NO NOTA</th>
                    <th>SALES</th>
                    <th>PENDATA</th>
                    <th>KODE</th>
                    <th>TIPE</th>
                    <th>NAMA BARANG</th>
                    <th>HRG MODAL</th>
                    <th>HRG JUAL</th>
                    <th>QTY</th>
                    <th>SUB TOTAL</th>
                    <th>LABA</th>
               </tr>
               <?php $total=0; $jml=0; $total_laba=0; $dibayar=0; $kekurangan=0; while($b = mysql_fetch_array($a)){ ?>
               <tr>
               		<td><?php echo date('d/m/Y', strtotime($b['tgl_keluar'])); ?></td>
                    <td><?php echo $b['no_nota']; ?></td>
                    <td><?php echo $b['nama_sales']; ?></td>
                    <td><?php echo $b['nama']; ?></td>
                    <td><?php echo $b['kode']; ?></td>
                    <td><?php echo $b['tipe']; ?></td>
                    <td><?php echo $b['nama_barang']; ?></td>
                    <td><?php echo toIdr($b['harga_modal']); ?></td>
                    <td><?php echo toIdr($b['harga']); ?></td>
                    <td><?php echo $b['qty']; ?></td>
                    <td><?php echo toIdr($b['sub_total']); ?></td>
                    <td><?php echo toIdr($b['sub_total']-($b['harga_modal']*$b['qty'])); ?></td>
               </tr>
               <?php $dibayar=$b['dibayar']; $kekurangan=$b['kekurangan']; $total+=$b['sub_total']; $jml+=$b['qty']; $total_laba+=($b['sub_total']-($b['harga_modal']*$b['qty'])); } ?>
               <tr>
               		<td colspan="9" align="right"><b>Total</b></td>
                    <td><b><?php echo $jml; ?></b></td>
                    <td><b><?php echo toIdr($total); ?></b></td>
                    <td><b><?php echo toIdr($total_laba); ?></b></td>
               </tr>
               <tr>
               		<td colspan="9" align="right"><b>Dibayar</b></td>
                    <td colspan="3"><b><?php echo toIdr($dibayar); ?></b></td>
               </tr>
               <tr>
               		<td colspan="9" align="right"><b>Kekurangan</b></td>
                    <td colspan="3"><b><?php echo toIdr($kekurangan); ?></b></td>
               </tr>
            </table>
        </div>
    </body>
</html>