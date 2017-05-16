<?php
require_once("koneksi.php");
function pengaturan(){
	$a = mysql_query("select * from pengaturan");
	return mysql_fetch_array($a);
}
$no_nota = $_GET['no'];
$a = mysql_query("select * from penjualan p left join member m on p.kode_member=m.kode_member join karyawan k on p.username=k.username where no_nota='$no_nota'");
$b = mysql_fetch_array($a);
?>


<div id="preview_nota" style="border:1px solid #000000;padding:10px;width:<?php echo pengaturan()['panjang_nota']; ?>cm;">
                        <div style="float:left;">
                        	<img id="preview_logo" src="pengaturan/<?php echo pengaturan()['logo']; ?>" style="width:<?php echo pengaturan()['panjang_logo'] ?>cm;height:<?php echo pengaturan()['lebar_logo']; ?>cm;" />
                        </div>
                        <div>
                            <div id="preview_toko" style="font-size:<?php echo pengaturan()['font_toko']; ?>px;font-weight:<?php echo pengaturan()['weight_toko']; ?>;"><?php echo pengaturan()['nama_toko']; ?></div>
                            <div id="preview_alamat" style="font-size:<?php echo pengaturan()['font_alamat']; ?>px;font-style:<?php echo pengaturan()['weight_alamat']; ?>;"><?php echo pengaturan()['alamat']; ?></div>
                            <div id="preview_kontak" style="font-size:<?php echo pengaturan()['font_kontak']; ?>px;font-style:<?php echo pengaturan()['weight_kontak']; ?>;"><?php echo pengaturan()['kontak']; ?></div>
                        </div>
                        <div class="isi" align="center" style="font-size:<?php echo pengaturan()['ukuran_font']; ?>px;">KS : <?php echo $b['nama']; ?></div>
                        <div class="isi" style="float:left;width:60%;font-size:<?php echo pengaturan()['ukuran_font']; ?>px;">
                        	<?php echo date("d/m/Y", strtotime($b['tgl_keluar'])); ?><br />
                            <?php echo $b['nama_member']!=NULL?$b['nama_member']:'-'; ?>                            
                        </div>
                        <div class="isi" style="float:right;width:40%;font-size:<?php echo pengaturan()['ukuran_font']; ?>px;">
                        	<?php
							require_once("plugins/php-barcode-generator-master/src/BarcodeGenerator.php");
							require_once("plugins/php-barcode-generator-master/src/BarcodeGeneratorPNG.php");
							$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
							echo '<img width="100%" id="barcode" src="data:image/png;base64,' . base64_encode($generator->getBarcode($b['no_nota'], $generator::TYPE_CODE_128)) . '"><br />';
							?>
                        	No : <?php echo $b['no_nota']; ?>
                        </div>
                        <div class="isi" style="margin-top:10px;font-size:<?php echo pengaturan()['ukuran_font']; ?>px;">
                        	<table border="1" class="isi" width="100%" style="font-size:<?php echo pengaturan()['ukuran_font']; ?>px;" cellspacing="0">
                            	<tr>
                                	<th>nama barang</th>
                                    <th>qty</th>
                                    <th>harga</th>
                                    <th>sub total</th>
                                </tr>
                                <?php
	$x = mysql_query("select d.kode, qty, d.harga_modal, d.harga, nama_barang from penjualan_detail d join barang b on d.kode=b.kode where no_nota='$no_nota'");
	$jml = 0;
	$total = 0;
	while($y = mysql_fetch_array($x)){
	?>
                                <tr>
                                	<td><?php echo $y['nama_barang']; ?></td>
                                    <td><?php echo $y['qty']; ?></td>
                                    <td><?php echo toIdr($y['harga']); ?></td>
                                    <td><?php echo toIdr($y['harga']*$y['qty']); ?></td>
                                </tr>
                                <?php $jml+=$y['qty'];$total+=($y['harga']*$y['qty']); } ?>
                                <tr>
                                	<td colspan="2" align="right">Jml : <?php echo $jml; ?> item</td>
                                    <td colspan="2" align="right">Total : <?php echo toIdr($total); ?></td>
                                </tr>
                            </table>
<style>
#preview_nota table td{
	padding:0;
}
</style>                            
                            <table class="isi" style="font-size:<?php echo pengaturan()['ukuran_font']; ?>px;">
                            	<tr>
                                	<td width="30%">dibayar</td>
                                    <td width="30%">: <?php echo toIdr($b['dibayar']); ?></td>
                                    <td rowspan="4" width="40%">terimakasih atas kunjungan anda<br />barang yang sudah dibeli tidak dapat ditukar / dikembalikan</td>
                                </tr>
                                <tr>
                                	<td>kekurangan</td>
                                    <td>: <?php echo toIdr($b['kekurangan']); ?></td>
                                </tr>
                                <tr>
                                	<td>tgl tempo</td>
                                    <td>: <?php echo $b['tgl_tempo']==NULL?'-':date('d/m/Y', strtotime($b['tgl_tempo'])); ?></td>
                                </tr>
                                <tr>
                                	<td>kembali</td>
                                    <td>: <?php echo toIdr($b['kembali']); ?></td>
                                </tr>
                            </table>
                        </div>
                   </div>
                   <div style="clear:both;">&nbsp;</div>