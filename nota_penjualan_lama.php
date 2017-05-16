<?php
session_start();
require_once("koneksi.php");
require_once("plugins/php-barcode-generator-master/src/BarcodeGenerator.php");
require_once("plugins/php-barcode-generator-master/src/BarcodeGeneratorPNG.php");
$no_nota = $_GET['no'];
$a = mysql_query("select * from penjualan p left join member m on p.kode_member=m.kode_member join karyawan k on p.username=k.username where no_nota='$no_nota'");
$b = mysql_fetch_array($a);
?>
<div style="float:left;">
<b>TB Dira Indah</b><br />
jl. ndiro bantul<br />
telp. 12345
</div>
<div align="center" style="padding:10px;border:1px solid #000000;width:250px;background:#000000;color:#FFFFFF;margin:0px auto;"><b>NOTA PENJUALAN</b></div>
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
    	<td>Tgl Keluar</td>
        <td>: <?php echo date("d/m/Y", strtotime($b['tgl_keluar'])); ?></td>
    </tr>
    <tr>
    	<td>Member</td>
        <td>: <?php echo $b['nama_member']!=NULL?$b['nama_member']:'-'; ?></td>
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
	$x = mysql_query("select d.kode, qty, d.harga_modal, d.harga, nama_barang from penjualan_detail d join barang b on d.kode=b.kode where no_nota='$no_nota'");
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
    	<td width="20%">Kembalian</td>
        <td>: <?php echo toIdr($b['kembali']); ?></td>
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