<?php hak_akses('master'); ?>
<script type="text/javascript">
$(function(){
	$('#pjg_kertas').keyup(function(){
		$('#preview_nota').css('width', $('#pjg_kertas').val()+'cm');
	});
	$('#pjg_logo').keyup(function(){
		$('#preview_logo').css('width', $('#pjg_logo').val()+'cm');
	});
	$('#lbr_logo').keyup(function(){
		$('#preview_logo').css('height', $('#lbr_logo').val()+'cm');
	});
	$('#font_toko').keyup(function(){
		$('#preview_toko').css('font-size', $('#font_toko').val()+'px');
	});
	$('#bold_toko').click(function(){
		$('#preview_toko').css('font-weight', 'bold');
	});
	$('#normal_toko').click(function(){
		$('#preview_toko').css('font-weight', 'normal');
	});	
	//---
	$('#font_alamat').keyup(function(){
		$('#preview_alamat').css('font-size', $('#font_alamat').val()+'px');
	});
	$('#italic_alamat').click(function(){
		$('#preview_alamat').css('font-style', 'italic');
	});
	$('#normal_alamat').click(function(){
		$('#preview_alamat').css('font-style', 'normal');
	});	
	//---
	$('#font_kontak').keyup(function(){
		$('#preview_kontak').css('font-size', $('#font_kontak').val()+'px');
	});
	$('#italic_kontak').click(function(){
		$('#preview_kontak').css('font-style', 'italic');
	});
	$('#normal_kontak').click(function(){
		$('#preview_kontak').css('font-style', 'normal');
	});
	$('#ukuran_font').keyup(function(){
		$('.isi').css('font-size', $('#ukuran_font').val()+'px');
	});	
});
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Master Pengaturan
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
              <?php if(isset($_COOKIE['berhasil'])){ ?>
             <div class="box box-solid box-success" id="berhasil">
                <div class="box-header">
                  <h3 class="box-title" id="judul_berhasil">sukses</h3>
                </div>
                <div class="box-body" id="pesan_berhasil"><?php echo $_COOKIE['berhasil']; ?></div>
              </div>
            <?php } ?>
            <?php if(isset($_COOKIE['gagal'])){ ?>
              <div class="box box-solid box-danger" id="gagal">
                <div class="box-header">
                  <h3 class="box-title" id="judul_gagal">gagal</h3>
                </div>
                <div class="box-body" id="pesan_gagal"><?php echo $_COOKIE['gagal']; ?></div>
              </div>
            <?php } ?>
              <div style="background:#FFFFFF;padding:15px;">
              	<div style="border:1px solid #000000;padding:10px;">
                <form method="post" action="pengaturan_proses.php">
                <input type="hidden" name="aksi" value="reset" />
                <span style="color:#FF0000;">RESET DATA, KEMBALI KE PENGATURAN AWAL (WARNING : HATI - HATI DALAM PENGGUNAAN, DATA TIDAK DAPAT DIKEMBALIKAN SETELAH DIRESET!)</span><br />
              	<input type="checkbox" name="stok" value="stok" /> kosongkan semua stok
                <br />
                <input type="checkbox" name="reset" value="reset" /> kembali ke setelan awal (semua data dihapus kecuali karyawan&amp;pengaturan)
                <br />
                <input type="submit" value="proses" class="btn btn-danger" onclick="return confirm('yakin mengeksekusi perintah yang dipilih?')" />
                </form>
                </div>
                <hr />
                <b>PENGATURAN SISTEM</b><br />
              	<?php
				$a = mysql_query("select * from pengaturan");
				$b = mysql_fetch_array($a);
				?>
                <form method="post" action="pengaturan_proses.php" enctype="multipart/form-data">
                		<input type="hidden" name="aksi" value="biodata" />
                        <input type="hidden" id="ada" name="ada" value="<?php echo !empty($b)?'ada':'belum'; ?>" />
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Nama Toko</td>
							  <td>
									<input type="text" id="nama" name="nama" class="form-control" value="<?php echo !empty($b)?$b['nama_toko']:''; ?>" required maxlength="50" />
							  </td>
							</tr>
							<tr class="form-group">
							  <td width="200">Alamat Toko</td>
							  <td>
									<input type="text" id="alamat" name="alamat" class="form-control" value="<?php echo !empty($b)?$b['alamat']:''; ?>" required maxlength="255" />
							  </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">Kontak</td>
							  <td>
									<input type="text" id="kontak" name="kontak" class="form-control" required value="<?php echo !empty($b)?$b['kontak']:''; ?>" maxlength="255" />
							  </td>
							</tr>
                            <tr>
							  <td width="200">Logo</td>
							  <td>
                              		<?php echo !empty($b)?'<img src="pengaturan/'.$b['logo'].'" width="100" height="100" />':''; ?>
									<input type="file" name="logo" id="logo" <?php echo !empty($b)?'':'required'; ?> accept="image/*" />
							  </td>
							</tr>
							<tr>
							  <td></td>
							  <td>
								<input type="submit" id="simpan" value="Simpan" class="btn btn-primary" />
							  </td>
							</tr>
						</table>
                        </form> 
                   <div>
                   <hr />
                   <b>PENGATURAN NOTA</b>
                   <form method="post" action="pengaturan_proses.php" enctype="multipart/form-data">
                		<input type="hidden" name="aksi" value="pengaturan" />
                   		<table>
                        	<tr>
                            	<td>Panjang Kertas</td>
                                <td><input type="text" name="pjg_kertas" id="pjg_kertas" placeholder="P" value="<?php echo pengaturan()['panjang_nota']; ?>" size="2" />cm</td>
                            </tr>
                        	<tr>
                            	<td>Ukuran Logo</td>
                                <td><input type="text" name="pjg_logo" id="pjg_logo" placeholder="P" value="<?php echo pengaturan()['panjang_logo']; ?>" size="2" />cm X <input type="text" placeholder="L" name="lbr_logo" id="lbr_logo" size="2" value="<?php echo pengaturan()['lebar_logo']; ?>" />cm</td>
                            </tr>
                            <tr>
                            	<td>Ukuran Font Nama Toko</td>
                                <td>
                                	<input type="text" name="font_toko" id="font_toko" size="2" value="<?php echo pengaturan()['font_toko']; ?>" />px
                                    <input type="radio" name="weight_toko" id="bold_toko" <?php echo pengaturan()['weight_toko']=='bold'?'checked':''; ?> value="bold"  />tebal
                                    <input type="radio" name="weight_toko" id="normal_toko" <?php echo pengaturan()['weight_toko']=='normal'?'checked':''; ?> value="normal"  />normal
                                </td>
                            </tr>
                            <tr>
                            	<td>Ukuran Font Alamat</td>
                                <td>
                                	<input type="text" name="font_alamat" id="font_alamat" size="2" value="<?php echo pengaturan()['font_alamat']; ?>" />px
                                    <input type="radio" name="weight_alamat" id="italic_alamat" <?php echo pengaturan()['weight_alamat']=='italic'?'checked':''; ?> value="italic"  />miring
                                    <input type="radio" name="weight_alamat" id="normal_alamat" <?php echo pengaturan()['weight_alamat']=='normal'?'checked':''; ?> value="normal"  />normal
                                </td>
                            </tr>
                            <tr>
                            	<td>Ukuran Font Kontak</td>
                                <td>
                                	<input type="text" name="font_kontak" id="font_kontak" size="2" value="<?php echo pengaturan()['font_kontak']; ?>" />px
                                    <input type="radio" name="weight_kontak" id="italic_kontak" <?php echo pengaturan()['weight_kontak']=='italic'?'checked':''; ?> value="italic"  />miring
                                    <input type="radio" name="weight_kontak" id="normal_kontak" <?php echo pengaturan()['weight_kontak']=='normal'?'checked':''; ?> value="normal"  />normal
                                </td>
                            </tr>
                            <tr>
                            	<td>Ukuran Font Isi</td>
                                <td>
                                	<input type="text" name="ukuran_font" id="ukuran_font" size="2" value="<?php echo pengaturan()['ukuran_font']; ?>" />px
                                </td>
                            </tr>
                            <tr>
                            	<td></td>
                            	<td><input type="submit" value="Simpan" class="btn btn-primary" /></td>
                            </tr>
                        </table>
                      </form>
                   </div>
                   <hr />
                   <b>PREVIEW NOTA</b>
                   <div id="preview_nota" style="border:1px solid #000000;padding:10px;width:<?php echo pengaturan()['panjang_nota']; ?>cm;">
                        <div style="float:left;">
                        	<img id="preview_logo" src="pengaturan/<?php echo pengaturan()['logo']; ?>" style="width:<?php echo pengaturan()['panjang_logo'] ?>cm;height:<?php echo pengaturan()['lebar_logo']; ?>cm;" />
                        </div>
                        <div>
                            <div id="preview_toko" style="font-size:<?php echo pengaturan()['font_toko']; ?>px;font-weight:<?php echo pengaturan()['weight_toko']; ?>;"><?php echo pengaturan()['nama_toko']; ?></div>
                            <div id="preview_alamat" style="font-size:<?php echo pengaturan()['font_alamat']; ?>px;font-style:<?php echo pengaturan()['weight_alamat']; ?>;"><?php echo pengaturan()['alamat']; ?></div>
                            <div id="preview_kontak" style="font-size:<?php echo pengaturan()['font_kontak']; ?>px;font-style:<?php echo pengaturan()['weight_kontak']; ?>;"><?php echo pengaturan()['kontak']; ?></div>
                        </div>
                        <div class="isi" align="center" style="font-size:<?php echo pengaturan()['ukuran_font']; ?>px;">KS : Noormalita Sari Dewi</div>
                        <div class="isi" style="float:left;width:60%;font-size:<?php echo pengaturan()['ukuran_font']; ?>px;">
                        	12/31/2016<br />
                            Mochammad Alwi                            
                        </div>
                        <div class="isi" style="float:right;width:40%;font-size:<?php echo pengaturan()['ukuran_font']; ?>px;">
                        	<?php
							require_once("plugins/php-barcode-generator-master/src/BarcodeGenerator.php");
							require_once("plugins/php-barcode-generator-master/src/BarcodeGeneratorPNG.php");
							$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
							echo '<img width="100%" id="barcode" src="data:image/png;base64,' . base64_encode($generator->getBarcode('12345678910123', $generator::TYPE_CODE_128)) . '"><br />';
							?>
                        	No : 12345678910123
                        </div>
                        <div class="isi" style="margin-top:10px;font-size:<?php echo pengaturan()['ukuran_font']; ?>px;">
                        	<table border="1" class="isi" width="100%" style="font-size:<?php echo pengaturan()['ukuran_font']; ?>px;">
                            	<tr>
                                	<th>nama barang</th>
                                    <th>qty</th>
                                    <th>harga</th>
                                    <th>sub total</th>
                                </tr>
                                <tr>
                                	<td>contoh 1</td>
                                    <td>1</td>
                                    <td>20,000</td>
                                    <td>20,000</td>
                                </tr>
                                <tr>
                                	<td>contoh 2</td>
                                    <td>3</td>
                                    <td>2,000</td>
                                    <td>6,000</td>
                                </tr>
                                <tr>
                                	<td colspan="2" align="right">Jml : 4</td>
                                    <td colspan="2" align="right">Total : 26,000</td>
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
                                    <td width="30%">: 30,000</td>
                                    <td rowspan="4" width="40%">terimakasih atas kunjungan anda<br />barang yang sudah dibeli tidak dapat ditukar / dikembalikan</td>
                                </tr>
                                <tr>
                                	<td>kekurangan</td>
                                    <td>: -</td>
                                </tr>
                                <tr>
                                	<td>tgl tempo</td>
                                    <td>: -</td>
                                </tr>
                                <tr>
                                	<td>kembali</td>
                                    <td>: 4,000</td>
                                </tr>
                            </table>
                        </div>
                   </div>
                   <div style="clear:both;">&nbsp;</div>
               </div>
        </section><!-- /.content -->