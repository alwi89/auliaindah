<style>
.datepicker{z-index:1151 !important;}
</style>
<script type="text/javascript">
$(function(){
	$("#ltmt_total").autoNumeric("init");
	$("#ltmt_sudahdibayar").autoNumeric("init");
	$("#ltmt_dibayar").autoNumeric("init");
	$("#ltmt_kekurangan").autoNumeric("init");
	$("#tmdari").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$("#tmsampai").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$("#ltm_tgl_tempo").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	
	$('#ltmt_dibayar').keyup(function(){
		total = $('#ltm_total').val();
		dibayar = $('#ltmt_dibayar').autoNumeric('get');
		sudah_dibayar = $('#ltmt_sudahdibayar').autoNumeric('get');
		kekurangan = total-sudah_dibayar-dibayar;
		$('#ltmt_kekurangan').autoNumeric('set', kekurangan);
		$('#ltm_kekurangan').val(kekurangan);
		$('#ltm_dibayar').val(dibayar);
	});
	lltgsdata_member();
	$("#lftagihan_member").submit(function(e){
		$.ajax({
				url: 'home_proses.php',
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				success: function(response) {
					$('#ltagihan_member_modal').modal('hide');
					alert(response[0]['status']);
					data_lap_tagihan_member();
				}            
		 });
		 e.preventDefault();
	});
});
function lltgsdata_member(){
	$.ajax({
			url: "lap_tagihan_member_proses.php",
			data: {'aksi':'data member'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#lltgs").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						var hasil = '<select name="laptmmember" id="laptmmember" class="form-control">';
							hasil += '<option value="ALL">semua member</option>';
							$.each(datas, function(i, data){
								hasil += '<option value="'+data['kode_member']+'">'+data['nama_member']+' - '+data['alamat']+'</option>';
							});
						$("#lltgs").html(hasil);
				   }else{
				   		alert('belum ada data member, isi master member terlebih dahulu');
				   }				   
			}
		});
}

function ledit_tagihan_member(no_nota){
	$("#ltagihan_member_modal").modal('toggle');
	$.ajax({
			url: "home_proses.php",
			type: 'POST',
			data: {'aksi_home':'edit tm', 'tm_no_nota':no_nota},
			dataType: 'json',
			beforeSend: function(){
				$("#lloading_tagihan_member").show();
			},
			success: function(datas){
				$("#lloading_tagihan_member").hide();
					if(datas[0]==null){
						alert('data tagihan tidak ditemukan');
					}else{
						tgl_tempo = datas[0]['tgl_tempo'].substr(8, 2)+'/'+datas[0]['tgl_tempo'].substr(5, 2)+'/'+datas[0]['tgl_tempo'].substr(0, 4);
						$('#ltm_no_nota').val(datas[0]['no_nota']);
						$('#ltm_member').val(datas[0]['nama_member']);
						$('#ltm_tgl_tempo').val(tgl_tempo);
						$('#ltmt_total').autoNumeric('set', datas[0]['total']);
						$('#ltm_total').val(datas[0]['total']);
						$('#ltmt_sudahdibayar').autoNumeric('set', datas[0]['dibayar']);
						$('#ltmsudahdibayar').val(datas[0]['dibayar']);
						$('#ltmt_dibayar').val('');
						$('#ltm_dibayar').val('');
						$('#ltmt_kekurangan').autoNumeric('set', datas[0]['kekurangan']);
						$('#ltm_kekurangan').val(datas[0]['kekurangan']);
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
								hasil += '<td>'+jumlah_cicil.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
								kekurangan_cicil = parseInt(data['kekurangan_cicil']);
								hasil += '<td>'+kekurangan_cicil.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
								hasil += '</tr>';
							}
						});
						hasil += '</tbody>';
						hasil += '</table>';
						$("#lhistory_pembayaran_member").html(hasil);
					}
			}
		});
}

function data_lap_tagihan_member(){
	var tm_total_transaksi = 0;
	var tm_total_dibayar = 0;
	var tm_total_kekurangan = 0;
	$.ajax({
		url: 'lap_tagihan_member_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'list member', 'dari':$('#tmdari').val(), 'sampai':$('#tmsampai').val(), 'member':$('#laptmmember').val()},
		beforeSend: function(){
			$("#data_lap_tagihan_member").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			if(datas[0]!==null){
				var kode_member = "";
				var hasil = "";
				var total_transaksi = 0;
				var total_dibayar = 0;
				var total_kekurangan = 0;
				var no = 1;
				$.each(datas, function(i, data){
					if(kode_member==""){
						kode_member = data['kode_member'];
						hasil = '<b>'+parseInt(no)+'.'+data['nama_member']+' - '+data['alamat']+'</b>';
						no++;
						hasil += '<table width="100%" cellspacing="0" cellpading="5" border="1">';
						hasil += '<thead>';
						hasil += '<tr>';
						hasil += '<th>TGL TEMPO</th>';
						hasil += '<th>NO NOTA</th>';
						hasil += '<th>TOTAL</th>';
						hasil += '<th>DIBAYAR</th>';
						hasil += '<th>KEKURANGAN</th>';
						hasil += '<th>AKSI</th>';
						hasil += '</tr>';
						hasil += '</thead>';
						hasil += '<tbody>';
						tgl_tempo = data['tgl_tempo'].substr(8, 2)+'/'+data['tgl_tempo'].substr(5, 2)+'/'+data['tgl_tempo'].substr(0, 4);
						hasil += '<tr>';
						hasil += '<td>'+tgl_tempo+'</td>';
						hasil += '<td>'+data['no_nota']+'</td>';
						total = parseInt(data['total']);
						hasil += '<td>'+total.toLocaleString('en-US')+'</td>';
						total_transaksi += total;
						tm_total_transaksi += total;
						dibayar = parseInt(data['dibayar']);
						hasil += '<td>'+dibayar.toLocaleString('en-US')+'</td>';
						total_dibayar +=  dibayar;
						tm_total_dibayar += dibayar;
						kekurangan = parseInt(data['kekurangan']);
						total_kekurangan += kekurangan;
						tm_total_kekurangan += kekurangan;
						hasil += '<td>'+kekurangan.toLocaleString('en-US')+'</td>';
						hasil += '<td><a href="javascript:ledit_tagihan_member(\''+data['no_nota']+'\')" title="edit"><img src="images/edit.png" width="20" height="20" /></a></td>';
						hasil += '</tr>';
					}else{
						if(kode_member==data['kode_member']){
							kode_member = data['kode_member'];
							tgl_tempo = data['tgl_tempo'].substr(8, 2)+'/'+data['tgl_tempo'].substr(5, 2)+'/'+data['tgl_tempo'].substr(0, 4);
							hasil += '<tr>';
							hasil += '<td>'+tgl_tempo+'</td>';
							hasil += '<td>'+data['no_nota']+'</td>';
							total = parseInt(data['total']);
							hasil += '<td>'+total.toLocaleString('en-US')+'</td>';
							total_transaksi += total;
							tm_total_transaksi += total;
							dibayar = parseInt(data['dibayar']);
							hasil += '<td>'+dibayar.toLocaleString('en-US')+'</td>';
							total_dibayar +=  dibayar;
							tm_total_dibayar += dibayar;
							kekurangan = parseInt(data['kekurangan']);
							total_kekurangan += kekurangan;
							tm_total_kekurangan += kekurangan;
							hasil += '<td>'+kekurangan.toLocaleString('en-US')+'</td>';
							hasil += '<td><a href="javascript:ledit_tagihan_member(\''+data['no_nota']+'\')" title="edit"><img src="images/edit.png" width="20" height="20" /></a></td>';
							hasil += '</tr>';
						}else{
							kode_member = data['kode_member'];
							hasil += '<tr>';
							hasil += '<td colspan="2" align="right"><b>Total</b></td>';
							hasil += '<td><b>'+total_transaksi.toLocaleString('en-US')+'</b></td>';
							hasil += '<td><b>'+total_dibayar.toLocaleString('en-US')+'</b></td>';
							hasil += '<td colspan="2"><b>'+total_kekurangan.toLocaleString('en-US')+'</b></td>';
							hasil += '</tr>';
							hasil += '</tbody>';
							hasil += '</table>';
							total_transaksi = 0;
							total_dibayar = 0;
							total_kekurangan = 0;
							
							hasil += '<b>'+parseInt(no)+'.'+data['nama_member']+' - '+data['alamat']+'</b>';
							no++;
							hasil += '<table width="100%" cellspacing="0" cellpading="5" border="1">';
							hasil += '<thead>';
							hasil += '<tr>';
							hasil += '<th>TGL TEMPO</th>';
							hasil += '<th>NO NOTA</th>';
							hasil += '<th>TOTAL</th>';
							hasil += '<th>DIBAYAR</th>';
							hasil += '<th>KEKURANGAN</th>';
							hasil += '<th>AKSI</th>';
							hasil += '</tr>';
							hasil += '</thead>';
							hasil += '<tbody>';
							tgl_tempo = data['tgl_tempo'].substr(8, 2)+'/'+data['tgl_tempo'].substr(5, 2)+'/'+data['tgl_tempo'].substr(0, 4);
							hasil += '<tr>';
							hasil += '<td>'+tgl_tempo+'</td>';
							hasil += '<td>'+data['no_nota']+'</td>';
							total = parseInt(data['total']);
							hasil += '<td>'+total.toLocaleString('en-US')+'</td>';
							total_transaksi += total;
							tm_total_transaksi += total;
							dibayar = parseInt(data['dibayar']);
							hasil += '<td>'+dibayar.toLocaleString('en-US')+'</td>';
							total_dibayar +=  dibayar;
							tm_total_dibayar += dibayar;
							kekurangan = parseInt(data['kekurangan']);
							total_kekurangan += kekurangan;
							tm_total_kekurangan += kekurangan;
							hasil += '<td>'+kekurangan.toLocaleString('en-US')+'</td>';
							hasil += '<td><a href="javascript:ledit_tagihan_member(\''+data['no_nota']+'\')" title="edit"><img src="images/edit.png" width="20" height="20" /></a></td>';
							hasil += '</tr>';
														
						}
					}
					//$('<div id="ltm-'+i+'"></div>').appendTo('#data_lap_tagihan_member');
					//data_detail_lap_tagihan_member(data['kode_member'], "ltm-"+i);
					//data_detail_lap_tagihan_member(data['kode_member']);
				});
				hasil += '<tr>';
				hasil += '<td colspan="2" align="right"><b>Total</b></td>';
				hasil += '<td><b>'+total_transaksi.toLocaleString('en-US')+'</b></td>';
				hasil += '<td><b>'+total_dibayar.toLocaleString('en-US')+'</b></td>';
				hasil += '<td colspan="2"><b>'+total_kekurangan.toLocaleString('en-US')+'</b></td>';
				hasil += '</tr>';
				hasil += '</tbody>';
				hasil += '</table>';
				hasil += '<div align="right" style="color:red;">Jumlah Total Transaksi : '+tm_total_transaksi.toLocaleString('en-US');
				hasil += '<br />Jumlah Total Dibayar : '+tm_total_dibayar.toLocaleString('en-US');
				hasil += '<br />Jumlah Total Kekurangan : '+tm_total_kekurangan.toLocaleString('en-US');				
				hasil += '</div>';
				total_transaksi = 0;
				total_dibayar = 0;
				total_kekurangan = 0;
				$('#data_lap_tagihan_member').html(hasil);
				//hasil += '<div align="right"><b><hr />Total Keseluruhan : '+ts_total_transaksi.toLocaleString('en-US', {minimumFractionDigits: 2});				
				//hasil += '<br />Total Potongan : '+ts_total_potongan.toLocaleString('en-US', {minimumFractionDigits: 2});
				//hasil += '<br />Total Dibayar : '+ts_total_dibayar.toLocaleString('en-US', {minimumFractionDigits: 2});
				//hasil += '<br />Total Kekurangan : '+ts_total_kekurangan.toLocaleString('en-US', {minimumFractionDigits: 2})+"</b></div>";
				//$("#data_lap_tagihan_member").html(hasil);
			}else{
				$("#data_lap_tagihan_member").html('tidak ada data');
			}
		}
	});
}

function cetak(){
	var laporan = $('#data_lap_tagihan_member').html();
	var printWindow = window.open('', '', 'height=500,width=800');
            printWindow.document.write('<html><head><title>cetak laporan tagihan member</title>');
            printWindow.document.write('</head><body>');
			printWindow.document.write('<b>Laporan Tagihan Member</b>');
			printWindow.document.write('<br /><b>Periode : '+$('#tmdari').val()+' s/d '+$('#tmsampai').val()+'</b><br /><br />');
            printWindow.document.write(laporan);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Laporan Tagihan Member
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
        		<div style="background:#FFFFFF;padding:15px;">
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Dari</td>
							  <td>
									<input type="text" id="tmdari" name="tmdari" class="form-control" required maxlength="10" />
							  </td>
                              <td>
                              	s/d
                              </td>
                              <td>
                              		<input type="text" id="tmsampai" name="tmsampai" class="form-control" required maxlength="10" />
                              </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">Member</td>
							  <td id="lltgs" colspan="3"></td>
							</tr>
							<tr>
							  <td></td>
							  <td colspan="3">
								<input type="button" value="Lihat" onclick="data_lap_tagihan_member()" class="btn btn-primary" />
                                <input type="button" value="Cetak" onclick="cetak()" name="aksi" class="btn btn-primary" />
                                <!--input type="submit" value="Export" name="aksi" class="btn btn-primary" /-->
							  </td>
							</tr>
						</table>
                        <div id="data_lap_tagihan_member"></div>
              </div>
        </section><!-- /.content -->

<!-- Modal history-->
<div class="modal fade" id="ltagihan_member_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Tagihan Member</h4>
      </div>
      <div class="modal-body" id="ldetail_tagihan_member">
        <img src="images/loading.gif" width="50" height="50" id="lloading_tagihan_member" />
        <form id="lftagihan_member">
        <input type="hidden" name="aksi_home" id="laksi_home" value="simpan" />
        <table>
        	<tr>
            	<td>No Nota</td>
                <td><input type="text" id="ltm_no_nota" name="tm_no_nota" readonly class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Nama Member / Konter</td>
                <td><input type="text" id="ltm_member" name="tm_member" readonly class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Tgl Tempo</td>
                <td><input type="text" id="ltm_tgl_tempo" name="tm_tgl_tempo" class="form-control" /></td>
           	</tr>
            <tr>
            	<td>Total Transaksi</td>
                <td><input type="text" id="ltmt_total" name="tmt_total" readonly class="form-control" /></td>
				<input type="hidden" id="ltm_total" name="tm_total" />
           	</tr>
            <tr>
            	<td>Sudah Dibayar</td>
                <td><input type="text" id="ltmt_sudahdibayar" readonly name="tmt_sudahdibayar"  class="form-control" /></td>
                <input type="hidden" id="ltmsudahdibayar" name="tmsudahdibayar" />
           	</tr>
            <tr>
            	<td>Dibayar</td>
                <td><input type="text" id="ltmt_dibayar" required name="tmt_dibayar"  class="form-control" /></td>
                <input type="hidden" id="ltm_dibayar" name="tm_dibayar" />
           	</tr>
            <tr>
            	<td>Kekurangan</td>
                <td><input type="text" id="ltmt_kekurangan" name="tmt_kekurangan" readonly  class="form-control" /></td>
                <input type="hidden" id="ltm_kekurangan" name="tm_kekurangan" />
           	</tr>
            <tr>
            	<td></td>
                <td><input type="submit" value="Simpan" class="btn btn-primary" /></td>
           	</tr>
        </table>
        </form>
        <div id="lhistory_pembayaran_member"></div>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>