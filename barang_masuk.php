<script>
var keranjang;
var table_barang;
$(function(){
	$("#tgl_tempo").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$("#tgl_masuk").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$('#t_total').autoNumeric('init', {mDec:0});
	$('#t_dibayar').autoNumeric('init', {mDec:0});
	$('#t_dibayar').keyup(function(){
		var total = parseInt($('#total').val());
		var dibayar = $('#t_dibayar').autoNumeric('get');
		$('#dibayar').val(dibayar);
		var kekurangan = total-dibayar;
		if(kekurangan<0){
			$('#t_kekurangan').autoNumeric('set', '0');
			$('#kekurangan').val('0');
			$('#tgl_tempo').val('');
			$('#t_dibayar').autoNumeric('set', total);
			$('#dibayar').val(total);
		}else{
			$('#t_kekurangan').autoNumeric('set', kekurangan);
			$('#kekurangan').val(kekurangan);
		}
	});
	$('#t_kekurangan').autoNumeric('init', {mDec:0});
	keranjang = $('#data_cart').DataTable({
		"bPaginate": false,
		"aProcessing": true,
		"aServerSide": true,
		"ajax": "barang_masuk_proses.php?cart",
			"columns": [
				{ "data": "nama_barang" },
				{ "render": function(data, type, full){
						var harga = parseInt(full['harga_modal']);
						return harga.toLocaleString('en-US') 
				}},
				{ "render": function(data, type, full){
						var qty = parseInt(full['qty']);
						return '<input type="text" tabindex="4" onkeyup="editCart(event, this.value, '+full['kode']+')" value="'+qty+'" style="text-align:center;" size="3" />'
				}},	
				{ "render": function(data, type, full){
						var sub_total = parseInt(full['sub_total']);
						var total = parseInt(full['total']);
						$('#label_total').html('Total : '+total.toLocaleString('en-US'));
						$('#t_total').autoNumeric('set', total);
						$('#total').val(total);
						return sub_total.toLocaleString('en-US') 
				}},		
				{ "render": function(data, type, full){
						return '<a href="javascript:removeCart(\''+full['kode']+'\')" title="hapus" onclick="return confirm(\'yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />' 
				}},
			]
	});
	$('#t_harga').autoNumeric('init', {mDec:0});
	$('#t_harga').keyup(function(e){
		if(e.keyCode == 13){
			addToCart();
		}else{
			if($('#nama_barang').val()!=""){
				var harga = $('#t_harga').autoNumeric('get');
				$('#harga').val(harga);
				var jml = parseInt($('#jml').val());
				var subTotal = harga*jml;
				$('#t_sub').autoNumeric('set', subTotal);
				$('#sub').val(subTotal);
			}
		}
	});
	$('#t_sub').autoNumeric('init', {mDec:0});
	$("#kode").keyup(function(e){
    	if(e.keyCode == 13){
			addToCart();
		}else{
			cek_barang();
		}
	});
	$("#jml").keyup(function(e){
    	if(e.keyCode == 13){
			addToCart();
		}else{
			cek_barang();
		}
	});
	$('#reset_btn').click(function(){
			$.ajax({
						url: 'barang_masuk_proses.php?reset',
						type: 'POST',
						dataType: 'json',
						success: function(response) {
							clear_to_new();
						}            
			});
	});
	$('#fsimpan').submit(function(e){
		if($('#kekurangan').val()!='0' && $('#tgl_tempo').val()==''){
			alert('tgl tempo harap diisi');
			$('#tgl_tempo').focus();
		}else{
			$.ajax({
						url: 'barang_masuk_proses.php',
						type: 'POST',
						data: $(this).serialize(),
						dataType: 'json',
						success: function(response) {
							if(response[0]['status']=='failed'){
								alert(response[0]['pesan']);
							}else{
								clear_to_new();
								cetak_faktur_pembelian(response[0]['no_nota']);
							}
						}            
			});
			
		}
		e.preventDefault();
		return false;
	});
	table_barang = $('#barang').DataTable({
						"ordering":false,
						"aProcessing": true,
						"aServerSide": true,
						"ajax": "barang_proses.php?data",
						"columns": [
							{ "data": "kode" },
							{ "data": "nama_barang" },
							{ "render": function(data, type, full){
									var harga = parseInt(full['harga_modal']);
									return harga.toLocaleString('en-US', {minimumFractionDigits: 2}) 
									}},
							{ "render": function(data, type, full){
									var harga_jual = parseInt(full['harga_jual']);
									return harga_jual.toLocaleString('en-US', {minimumFractionDigits: 2}) 
									}},
							{ "data": "saldo" },
							{ "render": function(data, type, full){
									return '<a href="javascript:pilih(\''+full['kode']+'\')" title="pilih"><img src="images/edit.png" width="20" height="20" /></a>' 
									}},
						]
					}); 
		$.fn.dataTable.ext.errMode = 'none';
});
function clear_to_add(){
	$('#kode').val('');
	$('#nama_barang').val('');
	$('#jml').val('1');
	$('#t_harga').val('');
	$('#harga').val('');
	$('#t_sub').val('');
	$('#sub').val('');
	$('#kode').focus();
}
function cetak_faktur_pembelian(no_nota){
	if($("#cetak").is(':checked')){
		var printWindow=window.open('faktur_barang_masuk.php?no='+no_nota,'','width=700,height=500');
		printWindow.print();
		/*
		var printAndClose = function () {
		  if (printWindow.document.readyState == 'complete') {
			clearInterval(sched);
			printWindow.print();
			printWindow.close();
		  }
		}  
		var sched = setInterval(printAndClose, 200);
		*/
	}
}
function clear_to_new(){
	clear_to_add();
	$('#t_total').val('');
	$('#total').val('');
	$('#t_dibayar').val('');
	$('#dibayar').val('');
	$('#t_kekurangan').val('');
	$('#kekurangan').val('');
	$('#tgl_tempo').val('');
	$('#label_total').html('Total : 0');
	$('#label_barang').html('');
	keranjang.ajax.reload( null, false );
}
function cek_barang(){
	$.ajax({
		url: "barang_masuk_proses.php?data_barang",
		data: {'kode':$("#kode").val()},
		type: 'POST',
		dataType: 'json',
		success: function(datas){
			if(datas[0]==null){
				$('#nama_barang').val('');
				$('#t_harga').val('');
				$('#harga').val('');
				$('#t_sub').val('');
				$('#sub').val('');
			}else{
				$('#nama_barang').val(datas[0]['nama_barang']);
				$('#t_harga').autoNumeric('set', datas[0]['harga_modal']);
				$('#harga').val(datas[0]['harga_modal']);
				if($('#jml').val()!=''){
					var subTotal = parseInt(datas[0]['harga_modal'])*parseInt($('#jml').val())
					$('#t_sub').autoNumeric('set', subTotal);
					$('#sub').val(subTotal);
				}else{
					$('#t_sub').val('');
					$('#sub').val('');
				}
			}
		}
	});
}
function addToCart(){
	if($('#nama_barang').val()!="" && $('#t_sub').val()!=""){
		$.ajax({
			url: "barang_masuk_proses.php?add",
			data: {'kode':$("#kode").val(), 'harga':$("#harga").val(), 'jml':$("#jml").val()},
			type: 'POST',
			dataType: 'json',
			success: function(datas){
				if(datas[0]==null){
					alert('gagal menambahkan barang');
				}else{
					$('#label_barang').html(datas[0]['status']);
					keranjang.ajax.reload( null, false );
					clear_to_add();
				}
			}
		});
	}else{
		$('#label_barang').html('kode tidak ditemukan');
	}
}
function removeCart(kode){
	$.ajax({
			url: "barang_masuk_proses.php?del",
			data: {'kode':kode},
			type: 'POST',
			dataType: 'json',
			success: function(datas){
				if(datas[0]==null){
					alert('gagal menghapus barang');
				}else{
					if(datas[0]['status']=='item barang kosong'){
						$('#label_barang').html(datas[0]['status']);
						keranjang.ajax.reload( null, false );
						clear_to_new();
					}else{
						$('#label_barang').html(datas[0]['status']);
						keranjang.ajax.reload( null, false );
						clear_to_add();
					}
				}
			}
	});
}
function editCart(event, jml, kode){
	if(event.keyCode == 13){
		if(jml!=""){
			$.ajax({
				url: "barang_masuk_proses.php?edt",
				data: {'kode':kode, 'jml':jml},
				type: 'POST',
				dataType: 'json',
				success: function(datas){
					if(datas[0]==null){
						alert('gagal mengedit barang');
					}else{
						$('#label_barang').html(datas[0]['status']);
						keranjang.ajax.reload( null, false );
						clear_to_add();
					}
				}
			});
		}
	}
}
function pencarian(){
	$("#pencarian_modal").modal('toggle');
	table_barang.ajax.reload( null, false );
}
function pilih(kode){
	$('#kode').val(kode);
	cek_barang();
	$("#pencarian_modal").modal('toggle');
}
</script>


        <!-- Main content -->
        <section class="content">
             <div id="message_barang_masuk"></div>
              <div>
              	<div style="float:left;background:#FFFFFF;width:70%;padding:10px;">
                  <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon"><img src="images/barcode.png" width="30" height="20" /></span>
                        <input type="text" name="kode" id="kode" class="form-control" placeholder="kode barang / barcode" autofocus="" autocomplete="off" tabindex="1" />
                        <span class="input-group-addon"><a href="#" onclick="pencarian()"><img src="images/search.png" width="30" height="20" /></a></span>
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon" style="background:#000000;color:#FFFFFF;">Nama Barang</span>
                        <input type="text" name="nama_barang" id="nama_barang" class="form-control" disabled />
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon" style="background:#000000;color:#FFFFFF;">JML</span>
                        <input type="text" name="jml" id="jml" tabindex="2" value="1" class="form-control" placeholder="jumlah item" autocomplete="off" />
                        <span class="input-group-addon" style="margin-left:10px;background:#000000;color:#FFFFFF;">@Harga Modal</span>
                        <input type="text" name="t_harga" id="t_harga" tabindex="3" class="form-control" placeholder="harga modal" autocomplete="off" />
                        <input type="hidden" name="harga" id="harga" />
                        <span class="input-group-addon" style="margin-left:10px;background:#000000;color:#FFFFFF;">Sub Total</span>
                        <input type="text" name="t_sub" id="t_sub" class="form-control" disabled />
                        <input type="hidden" name="sub" id="sub" />
                      </div>
                  </div>
                  <table class="table table-striped table-bordered table-hover" id="data_cart">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>@Harga Modal</th>
                            <th>JML</th>
                            <th>Sub Total</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
                </div>
                <form id="fsimpan">
                <input type="hidden" name="aksi_barang_masuk" value="tambah" />
                <div style="float:right;width:29%;margin-left:1px;">
                	<div style="background:#FFFFFF;padding:10px;">
                    	<div style="font-size:30px;color:#0033FF;" id="label_total">Total : 0</div>
                    	<div style="font-size:20px;font-style:italic;color:#FF0000;" id="label_barang"></div>
                    </div>
                    <div style="background:#FFFFFF;margin-top:10px;padding:10px;text-align:right;">
                    	<div class="form-group">
                                <select name="suplier" required id="suplier" class="form-control" data-provide="typeahead">
                                	<option value="">ketikkan nama suplier</option>
                                    <?php
									$q_sup = mysql_query("select * from suplier order by nama_suplier asc");
									while($h_sup = mysql_fetch_array($q_sup)){
									?>
                                    <option value="<?php echo $h_sup['kode_suplier']; ?>"><?php echo $h_sup['nama_suplier']; ?></option>
                                    <?php } ?>
                                </select>
                        </div>             
                        <div class="form-group">
                            <div class="form-inline">
                                Tgl Masuk
                                <input type="text" name="tgl_masuk" id="tgl_masuk" required class="form-control" value="<?php echo date('d/m/Y'); ?>" tabindex="5" autocomplete="off" />
                            </div>
                        </div>       
                    	<div class="form-group">
                            <div class="form-inline">
                                Total
                                <input type="text" name="t_total" id="t_total" required class="form-control" disabled autocomplete="off" />
                                <input type="hidden" name="total" id="total" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-inline">
                                Dibayar
                                <input type="text" name="t_dibayar" required id="t_dibayar" class="form-control" tabindex="6" autocomplete="off" />
                                <input type="hidden" name="dibayar" id="dibayar" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-inline">
                                Kekurangan
                                <input type="text" name="t_kekurangan" id="t_kekurangan" class="form-control" disabled autocomplete="off" />
                                <input type="hidden" name="kekurangan" id="kekurangan" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-inline">
                                Tgl Tempo
                                <input type="text" name="tgl_tempo" id="tgl_tempo" class="form-control" tabindex="7" autocomplete="off" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-inline">
                                Cetak Faktur
                                <input type="checkbox" name="cetak" id="cetak" value="cetak" checked />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-inline">
                                <input type="submit" value="simpan" tabindex="8" class="btn btn-primary" />
                                <input type="button" id="reset_btn" value="reset form" tabindex="9" class="btn btn-danger" />
                            </div>
                        </div>
                    </div>
                </div>
                </form>
                <div style="clear:both;">&nbsp;</div>
              </div> 
<!-- Modal cari-->
<div class="modal fade" id="pencarian_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width:1000px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Pencarian Barang</h4>
      </div>
      <div class="modal-body" id="data_barang">
        <table id="barang" class="table table-bordered table-hover" cellspacing="0" width="100%">
                	<thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Harga Modal</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Pilih</th>
                        </tr>
                    </thead>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--end modal history-->                             
        </section><!-- /.content -->