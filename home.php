<style>
.datepicker{z-index:1151 !important;}
</style>
<script>
$(function(){
	$("#tm_tgl_tempo").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$("#tp_tgl_tempo").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$("#tmt_total").autoNumeric("init");
	$("#tmt_sudahdibayar").autoNumeric("init");
	$("#tmt_dibayar").autoNumeric("init");
	$("#tmt_kekurangan").autoNumeric("init");
	$("#tpt_total").autoNumeric("init");
	$("#tpt_sudahdibayar").autoNumeric("init");
	$("#tpt_dibayar").autoNumeric("init");
	$("#tpt_kekurangan").autoNumeric("init");


	var table_sekarang = $('#data_sekarang').DataTable({
						"bPaginate": false,
						"aProcessing": true,
						"aServerSide": true,
						"ordering":false,
						"ajax": "home_proses.php?data_sekarang",
						"columns": [
							{ "data": "nama_member" },
							{ "data": "alamat" },
							{ "data": "no_tlp" },
							{ "render": function(data, type, full){
									hit_mundur = parseInt(full['hit_mundur']);
									if(hit_mundur=='0'){
										hit_mundur = 'hari ini';
									}else{
										hit_mundur = 'besok';
									}
									return hit_mundur 
									}},
							{ "render": function(data, type, full){
									var total = parseInt(full['kekurangan']);
									return total.toLocaleString('en-US') 
									}},	
							{ "render": function(data, type, full){
									return '<a href="javascript:edit_tagihan_member(\''+full['no_nota']+'\')" title="edit"><img src="images/edit.png" width="20" height="20" /></a>' 
									}},	
						]
					}); 
		setInterval( function () {
			table_sekarang.ajax.reload( null, false ); // user paging is not reset on reload
		}, 3000 );
		var table_suplier_tempo = $('#data_suplier_tempo').DataTable({
						"bPaginate": false,
						"aProcessing": true,
						"aServerSide": true,
						"ordering":false,
						"ajax": "home_proses.php?data_sekarang_suplier",
						"columns": [
							{ "data": "nama_suplier" },
							{ "data": "no_tlp" },
							{ "render": function(data, type, full){
									hit_mundur = parseInt(full['hit_mundur']);
									if(hit_mundur=='0'){
										hit_mundur = 'hari ini';
									}else{
										hit_mundur = 'besok';
									}
									return hit_mundur 
									}},
							{ "render": function(data, type, full){
									var total = parseInt(full['kekurangan']);
									return total.toLocaleString('en-US') 
									}},
							{ "render": function(data, type, full){
									return '<a href="javascript:edit_tagihan_suplier(\''+full['no_nota']+'\')" title="edit"><img src="images/edit.png" width="20" height="20" /></a>' 
									}},			
						]
					}); 
		setInterval( function () {
			table_suplier_tempo.ajax.reload( null, false ); // user paging is not reset on reload
		}, 3000 );
		
	$('#tmt_dibayar').keyup(function(){
		total = $('#tm_total').val();
		dibayar = $('#tmt_dibayar').autoNumeric('get');
		sudah_dibayar = $('#tmt_sudahdibayar').autoNumeric('get');
		kekurangan = total-sudah_dibayar-dibayar;
		$('#tmt_kekurangan').autoNumeric('set', kekurangan);
		$('#tm_kekurangan').val(kekurangan);
		$('#tm_dibayar').val(dibayar);
	});
	$('#tpt_dibayar').keyup(function(){
		total = $('#tp_total').val();
		dibayar = $('#tpt_dibayar').autoNumeric('get');
		sudah_dibayar = $('#tpt_sudahdibayar').autoNumeric('get');
		kekurangan = total-sudah_dibayar-dibayar;
		$('#tpt_kekurangan').autoNumeric('set', kekurangan);
		$('#tp_kekurangan').val(kekurangan);
		$('#tp_dibayar').val(dibayar);
	});
	$("#ftagihan_member").submit(function(e){
		$.ajax({
				url: 'home_proses.php',
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				success: function(response) {
					$('#tagihan_member_modal').modal('hide');
					alert(response[0]['status']);
				}            
		 });
		 e.preventDefault();
	});
	$("#ftagihan_suplier").submit(function(e){
		$.ajax({
				url: 'home_proses.php',
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				success: function(response) {
					$('#tagihan_suplier_modal').modal('hide');
					alert(response[0]['status']);
				}            
		 });
		 e.preventDefault();
	});
	$.fn.dataTable.ext.errMode = 'none';

});
function edit_tagihan_member(no_nota){
	$("#tagihan_member_modal").modal('toggle');
	$.ajax({
			url: "home_proses.php",
			type: 'POST',
			data: {'aksi_home':'edit tm', 'tm_no_nota':no_nota},
			dataType: 'json',
			beforeSend: function(){
				$("#loading_tagihan_member").show();
			},
			success: function(datas){
				$("#loading_tagihan_member").hide();
					if(datas[0]==null){
						alert('data tagihan tidak ditemukan');
					}else{
						tgl_tempo = datas[0]['tgl_tempo'].substr(8, 2)+'/'+datas[0]['tgl_tempo'].substr(5, 2)+'/'+datas[0]['tgl_tempo'].substr(0, 4);
						$('#tm_no_nota').val(datas[0]['no_nota']);
						$('#tm_member').val(datas[0]['nama_member']);
						$('#tm_tgl_tempo').val(tgl_tempo);
						$('#tmt_total').autoNumeric('set', datas[0]['total']);
						$('#tm_total').val(datas[0]['total']);
						$('#tmt_sudahdibayar').autoNumeric('set', datas[0]['dibayar']);
						$('#tmsudahdibayar').val(datas[0]['dibayar']);
						$('#tmt_dibayar').val('');
						$('#tm_dibayar').val('');
						$('#tmt_kekurangan').autoNumeric('set', datas[0]['kekurangan']);
						$('#tm_kekurangan').val(datas[0]['kekurangan']);
						hasil = '<table class="table table-bordered">';
						hasil += '<thead>';
						hasil += '<tr>';
						hasil += '<th>TGL BAYAR</th>';
						hasil += '<th>DIBAYAR</th>';
						hasil += '<th>KEKURANGAN</th>';
						hasil += '</tr>';
						hasil += '</thead>';
						hasil += '<tbody>';
						$.each(datas, function(i, data){
							if(data['jumlah_cicil']!=null){
								tgl_bayar = data['tgl_bayar'].substr(8, 2)+'/'+data['tgl_bayar'].substr(5, 2)+'/'+data['tgl_bayar'].substr(0, 4)+' '+data['tgl_bayar'].substr(11, 8);
								hasil += '<tr>';
								hasil += '<td>'+tgl_bayar+'</td>';
								jumlah_cicil = parseInt(data['jumlah_cicil']);
								hasil += '<td>'+jumlah_cicil.toLocaleString('en-US')+'</td>';
								kekurangan_cicil = parseInt(data['kekurangan_cicil']);
								hasil += '<td>'+kekurangan_cicil.toLocaleString('en-US')+'</td>';
								hasil += '</tr>';
							}
						});
						hasil += '</tbody>';
						hasil += '</table>';
						$("#history_pembayaran_member").html(hasil);
					}
			}
		});
}
function edit_tagihan_suplier(no_nota){
	$("#tagihan_suplier_modal").modal('toggle');
	$.ajax({
			url: "home_proses.php",
			type: 'POST',
			data: {'aksi_home':'edit tp', 'tp_no_nota':no_nota},
			dataType: 'json',
			beforeSend: function(){
				$("#loading_tagihan_suplier").show();
			},
			success: function(datas){
				$("#loading_tagihan_suplier").hide();
					if(datas[0]==null){
						alert('data tagihan tidak ditemukan');
					}else{
						tgl_tempo = datas[0]['tgl_tempo'].substr(8, 2)+'/'+datas[0]['tgl_tempo'].substr(5, 2)+'/'+datas[0]['tgl_tempo'].substr(0, 4);
						$('#tp_no_nota').val(datas[0]['no_nota']);
						$('#tp_suplier').val(datas[0]['nama_suplier']);
						$('#tp_tgl_tempo').val(tgl_tempo);
						$('#tpt_total').autoNumeric('set', datas[0]['total']);
						$('#tp_total').val(datas[0]['total']);
						$('#tpt_sudahdibayar').autoNumeric('set', datas[0]['dibayar']);
						$('#tpsudahdibayar').val(datas[0]['dibayar']);
						$('#tpt_dibayar').val('');
						$('#tp_dibayar').val('');
						$('#tpt_kekurangan').autoNumeric('set', datas[0]['kekurangan']);
						$('#tp_kekurangan').val(datas[0]['kekurangan']);
						hasil = '<table class="table table-bordered">';
						hasil += '<thead>';
						hasil += '<tr>';
						hasil += '<th>TGL BAYAR</th>';
						hasil += '<th>DIBAYAR</th>';
						hasil += '<th>KEKURANGAN</th>';
						hasil += '</tr>';
						hasil += '</thead>';
						hasil += '<tbody>';
						$.each(datas, function(i, data){
							tgl_bayar = data['tgl_bayar'].substr(8, 2)+'/'+data['tgl_bayar'].substr(5, 2)+'/'+data['tgl_bayar'].substr(0, 4)+' '+data['tgl_bayar'].substr(11, 8);
							hasil += '<tr>';
							hasil += '<td>'+tgl_bayar+'</td>';
							jumlah_cicil = parseInt(data['jumlah_cicil']);
							hasil += '<td>'+jumlah_cicil.toLocaleString('en-US')+'</td>';
							kekurangan_cicil = parseInt(data['kekurangan_cicil']);
							hasil += '<td>'+kekurangan_cicil.toLocaleString('en-US')+'</td>';
							hasil += '</tr>';
						});
						hasil += '</tbody>';
						hasil += '</table>';
						$("#history_pembayaran_suplier").html(hasil);
					}
			}
		});
}

</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Beranda
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
              <div style="background:#FFFFFF;padding:15px;">                   
<div class="row">
    <div>
      <div class="box box-solid box-primary">
        <div class="box-header">
          <h3 class="box-title">Member Jatuh Tempo</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
        	<table class="table table-striped table-bordered table-hover" id="data_sekarang">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No Telp</th>                            
                            <th>Tgl Tempo</th>
                            <th>Total Tagihan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
            </table> 
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
    <div class="clearfix"></div>

    <div id="notif_tagihan_suplier">
      <div class="box box-solid box-warning">
        <div class="box-header">
          <h3 class="box-title">Suplier Jatuh Tempo</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table class="table table-striped table-bordered table-hover" id="data_suplier_tempo">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>No Telp</th>
                            <th>Tgl Tempo</th>
                            <th>Total Tagihan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
            </table> 
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
</div>
</div>
<!-- Modal history-->
<div class="modal fade" id="tagihan_member_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Tagihan Member</h4>
      </div>
      <div class="modal-body" id="detail_tagihan_member">
        <img src="images/loading.gif" width="50" height="50" id="loading_tagihan_member" />
        <form id="ftagihan_member">
        <input type="hidden" name="aksi_home" id="aksi_home" value="simpan" />
        <table>
        	<tr>
            	<td>No Nota</td>
                <td><input type="text" id="tm_no_nota" name="tm_no_nota" readonly class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Nama Member / Konter</td>
                <td><input type="text" id="tm_member" name="tm_member" readonly class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Tgl Tempo</td>
                <td><input type="text" id="tm_tgl_tempo" name="tm_tgl_tempo" class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Total Transaksi</td>
                <td><input type="text" id="tmt_total" name="tmt_total" readonly class="form-control" /></td>
				<input type="hidden" id="tm_total" name="tm_total" />
           	</tr>
            <tr>
            	<td>Sudah Dibayar</td>
                <td><input type="text" id="tmt_sudahdibayar" readonly name="tmt_sudahdibayar"  class="form-control" /></td>
                <input type="hidden" id="tmsudahdibayar" name="tmsudahdibayar" />
           	</tr>
            <tr>
            	<td>Dibayar</td>
                <td><input type="text" required id="tmt_dibayar" name="tmt_dibayar"  class="form-control" /></td>
                <input type="hidden" id="tm_dibayar" name="tm_dibayar" />
           	</tr>
            <tr>
            	<td>Kekurangan</td>
                <td><input type="text" id="tmt_kekurangan" name="tmt_kekurangan" readonly  class="form-control" /></td>
                <input type="hidden" id="tm_kekurangan" name="tm_kekurangan" />
           	</tr>
            <tr>
            	<td></td>
                <td><input type="submit" value="Simpan" class="btn btn-primary" /></td>
           	</tr>
        </table>
        </form>     
        <div id="history_pembayaran_member"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal history-->
<div class="modal fade" id="tagihan_suplier_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Tagihan Suplier</h4>
      </div>
      <div class="modal-body" id="detail_tagihan_suplier">
        <img src="images/loading.gif" width="50" height="50" id="loading_tagihan_suplier" />
        <form id="ftagihan_suplier">
        <input type="hidden" name="aksi_home" value="simpan tp" />
        <table>
        	<tr>
            	<td>No Nota</td>
                <td><input type="text" id="tp_no_nota" name="tp_no_nota" readonly class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Nama Suplier</td>
                <td><input type="text" id="tp_suplier" name="tp_suplier" readonly class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Tgl Tempo</td>
                <td><input type="text" id="tp_tgl_tempo" name="tp_tgl_tempo" class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Total Transaksi</td>
                <td><input type="text" id="tpt_total" name="tpt_total" readonly class="form-control" /></td>
				<input type="hidden" id="tp_total" name="tp_total" />
           	</tr>
            <tr>
            	<td>Sudah Dibayar</td>
                <td><input type="text" id="tpt_sudahdibayar" readonly name="tpt_sudahdibayar"  class="form-control" /></td>
                <input type="hidden" id="tpsudahdibayar" name="tpsudahdibayar" />
           	</tr>
            <tr>
            	<td>Dibayar</td>
                <td><input type="text" id="tpt_dibayar" required name="tpt_dibayar"  class="form-control" /></td>
                <input type="hidden" id="tp_dibayar" name="tp_dibayar" />
           	</tr>
            <tr>
            	<td>Kekurangan</td>
                <td><input type="text" id="tpt_kekurangan" name="tpt_kekurangan" readonly  class="form-control" /></td>
                <input type="hidden" id="tp_kekurangan" name="tp_kekurangan" />
           	</tr>
            <tr>
            	<td></td>
                <td><input type="submit" value="Simpan" class="btn btn-primary" /></td>
           	</tr>
        </table>
        </form>      
        <div id="history_pembayaran_suplier"></div>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

        </section><!-- /.content -->