<?php
session_start();
require_once("koneksi.php");
function pengaturan(){
	$a = mysql_query("select * from pengaturan");
	return mysql_fetch_array($a);
}
require_once("plugins/php-barcode-generator-master/src/BarcodeGenerator.php");
require_once("plugins/php-barcode-generator-master/src/BarcodeGeneratorPNG.php");
$no_nota = $_GET['no'];
$a = mysql_query("select * from barang_masuk b join suplier s on b.kode_suplier=s.kode_suplier join karyawan k on b.username=k.username where no_nota='$no_nota'");
$b = mysql_fetch_array($a);
?>
<div style="float:left;">
<b><?php echo pengaturan()['nama_toko']; ?></b><br />
<?php echo pengaturan()['alamat']; ?><br />
<?php echo pengaturan()['kontak']; ?>
</div>
<div align="center" style="padding:10px;border:1px solid #000000;width:250px;background:#000000;color:#FFFFFF;margin:0px auto;"><b>FAKTUR BARANG MASUK</b></div>
<div style="clear:both;" align="right">
<?php
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($b['no_nota'], $generator::TYPE_CODE_128)) . '">';
?><br />
No Nota : <?php echo $b['no_nota']; ?>
</div>
<div style="border:1px solid #000000;margin-top:10px;clear:both;">
<table width="100%">
    <tr>
    	<td width="20%">No Nota</td>
        <td>: <?php echo $b['no_nota']; ?></td>
    </tr>
    <tr>
    	<td>Tgl Masuk</td>
        <td>: <?php echo date("d/m/Y", strtotime($b['tgl_masuk'])); ?></td>
    </tr>
    <tr>
    	<td>Suplier</td>
        <td>: <?php echo $b['nama_suplier']; ?></td>
    </tr>
    <tr>
    	<td>Data Entry</td>
        <td>: <?php echo $b['nama']; ?></td>
    </tr>
</table>
<table cellspacing="0" cellpadding="3" width="100%">
	<tr>
    	<th style="border-top: 1px solid #000000;border-bottom: 1px solid #000000;border-right:1px solid #000000;">Kode</th>
        <th style="border-top: 1px solid #000000;border-bottom: 1px solid #000000;border-right:1px solid #000000;">Nama Barang</th>
        <th style="border-top: 1px solid #000000;border-bottom: 1px solid #000000;border-right:1px solid #000000;">JML</th>
        <th style="border-top: 1px solid #000000;border-bottom: 1px solid #000000;border-right:1px solid #000000;">@Harga</th>
        <th style="border-top: 1px solid #000000;border-bottom: 1px solid #000000;">Sub Total</th>
    </tr>
    <?php
	$x = mysql_query("select * from barang_masuk_detail d join barang b on d.kode=b.kode where no_nota='$no_nota'");
	$jml = 0;
	$total = 0;
	while($y = mysql_fetch_array($x)){
	?>
	<tr>
    	<td style="border-bottom: 1px solid #000000;border-right:1px solid #000000;"><?php echo $y['kode']; ?></td>
        <td style="border-bottom: 1px solid #000000;border-right:1px solid #000000;"><?php echo $y['nama_barang']; ?></td>
        <td style="border-bottom: 1px solid #000000;border-right:1px solid #000000;"><?php echo $y['qty']; ?></td>
        <td style="border-bottom: 1px solid #000000;border-right:1px solid #000000;"><?php echo toIdr($y['harga']); ?></td>
        <td style="border-bottom: 1px solid #000000;"><?php echo toIdr($y['harga']*$y['qty']); ?></td>
    </tr>
    <?php $jml+=$y['qty'];$total+=($y['harga']*$y['qty']); } ?>
    <tr>
    	<td colspan="3" align="right" style="border-bottom: 1px solid #000000;border-right:1px solid #000000;">Jml : <?php echo $jml; ?> item</td>
        <td colspan="2" align="right" style="border-bottom: 1px solid #000000;">Total : <?php echo toIdr($total); ?></td>
    </tr>
</table>
<table width="100%">
    <tr>
    	<td width="20%">Dibayar</td>
        <td>: <?php echo toIdr($b['dibayar']); ?></td>
    </tr>
    <tr>
    	<td>Kekurangan</td>
        <td>: <?php echo toIdr($b['kekurangan']); ?></td>
    </tr>
    <tr>
    	<td>Tgl Tempo</td>
        <td>: <?php echo $b['tgl_tempo']==NULL?'-':date('d/m/Y', strtotime($b['tgl_tempo'])); ?></td>
    </tr>
<?php
$m = mysql_query("select * from karyawan where username='$_SESSION[usrdridh]'");
$n = mysql_fetch_array($m);
?>    
    <tr>
    	<td>Dicetak Oleh</td>
        <td>: <?php echo $n['nama']; ?></td>
    </tr>
</table>
</div>