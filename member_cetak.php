<?php
session_start();
require_once('koneksi.php');
?>
<html>
	<head>
    	<title>cetak sales</title>
    </head>
    <body onLoad="javascript:print();">
    	<div>
        	<div style="font-size:18px"><b>MASTER MEMBER<br />Dicetak Tanggal : <?php echo date('d/m/Y H:i:s'); ?></div><hr />
            <table width="100%" border="1" cellspacing="0" cellpadding="3">
            	<tr>
                	<th>KODE</th>
                    <th>NAMA MEMBER</th>
                    <th>ALAMAT</th>
                    <th>NO TELPON</th>
               </tr>
               <?php
			   $a = mysql_query("select * from member order by nama_member asc");
			   while($b = mysql_fetch_array($a)){ 
			   ?>
               <tr>
                    <td><?php echo $b['kode_member']; ?></td>
                    <td><?php echo trim($b['nama_member']); ?></td>
                    <td><?php echo $b['alamat']; ?></td>
                    <td><?php echo $b['no_tlp']; ?></td>
               </tr>
               <?php } ?>
            </table>
        </div>
    </body>
</html>