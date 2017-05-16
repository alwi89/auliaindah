<?php hak_akses('master'); ?>
<script type="text/javascript">
$(function(){
	$("#bmdari").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$("#bmsampai").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	lbmdata_suplier();
});
function lbmdata_suplier(){
	$.ajax({
			url: "barang_masuk_proses.php",
			data: {'aksi_barang_masuk':'data suplier'},
			type: 'POST',
			dataType: 'json',
			beforSend: function(){
				$("#llbmsuplier").html('<img src="images/loading.gif" width="20" height="20" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						var hasil = '<select name="lapbmsup" id="lapbmsup" class="form-control">';
						hasil += '<option value="SEMUA">semua</option>'
						$.each(datas, function(i, data){
							hasil += '<option value="'+data['kode_suplier']+'">'+data['nama_suplier']+' - '+data['alamat']+'</option>';
					   	});
						$("#llbmsuplier").html(hasil);
				   }else{
				   		alert('belum ada data suplier, isi master suplier terlebih dahulu');
				   }				   
			}
		});
}
function data_lap_barang_masuk(){
	var tm_total_jml = 0;
	var tm_total_subtotal = 0;
	$.ajax({
		url: 'lap_barang_masuk_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'data', 'dari':$('#bmdari').val(), 'sampai':$('#bmsampai').val(), 'suplier': $('#lapbmsup').val()},
		beforeSend: function(){
			$("#data_lap_barang_masuk").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			if(datas[0]!==null){
				var kode_suplier = "";
				var hasil = "";
				var total_jml = 0;
				var total_subtotal = 0;
				var no = 1;
				$.each(datas, function(i, data){
					if(kode_suplier==""){
						kode_suplier = data['kode_suplier'];
						hasil = '<b>'+parseInt(no)+'.'+data['nama_suplier']+' - '+data['alamat']+'</b>';
						no++;
						hasil += '<table width="100%" cellspacing="0" cellpading="5" border="1">';
						hasil += '<thead>';
						hasil += '<tr>';
						hasil += '<th>TGL MASUK</th>';
						hasil += '<th>NO NOTA</th>';
						hasil += '<th>NAMA BARANG</th>';						
						hasil += '<th>JML</th>';
						hasil += '<th>HARGA</th>';
						hasil += '<th>SUB TOTAL</th>';
						hasil += '<th>DATA ENTRY</th>';
						hasil += '</tr>';
						hasil += '</thead>';
						hasil += '<tbody>';
						tgl_masuk = data['tgl_masuk'].substr(8, 2)+'/'+data['tgl_masuk'].substr(5, 2)+'/'+data['tgl_masuk'].substr(0, 4);
						hasil += '<tr>';
						hasil += '<td>'+tgl_masuk+'</td>';
						hasil += '<td>'+data['no_nota']+'</td>';
						hasil += '<td>'+data['nama_barang']+'</td>';
						hasil += '<td>'+data['qty']+'</td>';
						harga = parseInt(data['harga']);
						hasil += '<td>'+harga.toLocaleString('en-US')+'</td>';
						sub_total = parseInt(data['sub_total']);
						hasil += '<td>'+sub_total.toLocaleString('en-US')+'</td>';
						hasil += '<td>'+data['nama']+'</td>';
						hasil += '</tr>';
						total_jml += parseInt(data['qty']);
						total_subtotal += sub_total;
						tm_total_jml += parseInt(data['qty']);
						tm_total_subtotal += sub_total;
					}else{
						if(kode_suplier==data['kode_suplier']){
							kode_suplier = data['kode_suplier'];
							tgl_masuk = data['tgl_masuk'].substr(8, 2)+'/'+data['tgl_masuk'].substr(5, 2)+'/'+data['tgl_masuk'].substr(0, 4);
							hasil += '<tr>';
							hasil += '<td>'+tgl_masuk+'</td>';
							hasil += '<td>'+data['no_nota']+'</td>';
							hasil += '<td>'+data['nama_barang']+'</td>';
							hasil += '<td>'+data['qty']+'</td>';
							harga = parseInt(data['harga']);
							hasil += '<td>'+harga.toLocaleString('en-US')+'</td>';
							sub_total = parseInt(data['sub_total']);
							hasil += '<td>'+sub_total.toLocaleString('en-US')+'</td>';
							hasil += '<td>'+data['nama']+'</td>';
							hasil += '</tr>';
							total_jml += parseInt(data['qty']);
							total_subtotal += sub_total;
							tm_total_jml += parseInt(data['qty']);
							tm_total_subtotal += sub_total;
						}else{
							kode_suplier = data['kode_suplier'];
							hasil += '<tr>';
							hasil += '<td colspan="3" align="right"><b>Total</b></td>';
							hasil += '<td><b>'+total_jml.toLocaleString('en-US')+'</b></td>';
							hasil += '<td></td>';
							hasil += '<td colspan="2"><b>'+total_subtotal.toLocaleString('en-US')+'</b></td>';
							hasil += '</tr>';
							hasil += '</tbody>';
							hasil += '</table>';
							total_jml = 0;
							total_subtotal = 0;
							
							
							hasil += '<b>'+parseInt(no)+'.'+data['nama_suplier']+' - '+data['alamat']+'</b>';
							no++;
							hasil += '<table width="100%" cellspacing="0" cellpading="5" border="1">';
							hasil += '<thead>';
							hasil += '<tr>';
							hasil += '<th>TGL MASUK</th>';
							hasil += '<th>NO NOTA</th>';
							hasil += '<th>NAMA BARANG</th>';
							hasil += '<th>JML</th>';
							hasil += '<th>HARGA</th>';
							hasil += '<th>SUB TOTAL</th>';
							hasil += '<th>DATA ENTRY</th>';
							hasil += '</tr>';
							hasil += '</thead>';
							hasil += '<tbody>';
							tgl_masuk = data['tgl_masuk'].substr(8, 2)+'/'+data['tgl_masuk'].substr(5, 2)+'/'+data['tgl_masuk'].substr(0, 4);
							hasil += '<tr>';
							hasil += '<td>'+tgl_masuk+'</td>';
							hasil += '<td>'+data['no_nota']+'</td>';
							hasil += '<td>'+data['nama_barang']+'</td>';
							hasil += '<td>'+data['qty']+'</td>';
							harga = parseInt(data['harga']);
							hasil += '<td>'+harga.toLocaleString('en-US')+'</td>';
							sub_total = parseInt(data['sub_total']);
							hasil += '<td>'+sub_total.toLocaleString('en-US')+'</td>';
							hasil += '<td>'+data['nama']+'</td>';
							hasil += '</tr>';
							total_jml += parseInt(data['qty']);
							total_subtotal += sub_total;
							tm_total_jml += parseInt(data['qty']);
							tm_total_subtotal += sub_total;
														
						}
					}
					//$('<div id="ltm-'+i+'"></div>').appendTo('#data_lap_barang_masuk');
					//data_detail_lap_tagihan_suplier(data['kode_suplier'], "ltm-"+i);
					//data_detail_lap_tagihan_suplier(data['kode_suplier']);
				});
				hasil += '<tr>';
				hasil += '<td colspan="3" align="right"><b>Total</b></td>';
				hasil += '<td><b>'+total_jml.toLocaleString('en-US')+'</b></td>';
				hasil += '<td></td>';
				hasil += '<td colspan="2"><b>'+total_subtotal.toLocaleString('en-US')+'</b></td>';
				hasil += '</tr>';
				hasil += '</tbody>';
				hasil += '</table>';
				hasil += '<div align="right" style="color:red;">Jumlah Total Item : '+tm_total_jml.toLocaleString('en-US');
				hasil += '<br />Jumlah Total Transaksi : '+tm_total_subtotal.toLocaleString('en-US');
				var dibayar = parseInt(datas[0]['dibayar']);
				var kekurangan = parseInt(datas[0]['kekurangan']);
				hasil += '<br />Jumlah Total Transaksi : '+tm_total_subtotal.toLocaleString('en-US');
				hasil += '<br />Dibayar : '+dibayar.toLocaleString('en-US');
				hasil += '<br />Kekurangan : '+kekurangan.toLocaleString('en-US');
				hasil += '</div>';
				total_jml = 0;
				total_subtotal = 0;
				
				$('#data_lap_barang_masuk').html(hasil);
				//hasil += '<div align="right"><b><hr />Total Keseluruhan : '+ts_total_jml.toLocaleString('en-US', {minimumFractionDigits: 2});				
				//hasil += '<br />Total Potongan : '+ts_total_potongan.toLocaleString('en-US', {minimumFractionDigits: 2});
				//hasil += '<br />Total Dibayar : '+ts_total_subtotal.toLocaleString('en-US', {minimumFractionDigits: 2});
				//hasil += '<br />Total Kekurangan : '+ts_total_kekurangan.toLocaleString('en-US', {minimumFractionDigits: 2})+"</b></div>";
				//$("#data_lap_barang_masuk").html(hasil);
			}else{
				$("#data_lap_barang_masuk").html('tidak ada data');
			}
		}
	});
}
function cetak(){
	var laporan = $('#data_lap_barang_masuk').html();
	var printWindow = window.open('', '', 'height=500,width=800');
            printWindow.document.write('<html><head><title>cetak laporan barang masuk</title>');
            printWindow.document.write('</head><body>');
			printWindow.document.write('<b>Laporan Barang Masuk</b>');
			printWindow.document.write('<br /><b>Periode : '+$('#bmdari').val()+' s/d '+$('#bmsampai').val()+'</b><br /><br />');
            printWindow.document.write(laporan);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Laporan Barang Masuk
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
        	<div style="background:#FFFFFF;padding:15px;">
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Dari</td>
							  <td>
									<input type="text" id="bmdari" name="bmdari" class="form-control"  required maxlength="10" />
							  </td>
                              <td>
                              	s/d
                              </td>
                              <td>
                              		<input type="text" id="bmsampai" name="bmsampai" class="form-control"  required maxlength="10" />
                              </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">Suplier</td>
							  <td id="llbmsuplier" colspan="3"></td>
							</tr>
							<tr>
							  <td></td>
							  <td colspan="3">
								<input type="button" value="Lihat" onclick="data_lap_barang_masuk()" class="btn btn-primary" />
                                <input type="button" value="Cetak" onclick="cetak()" name="aksi" class="btn btn-primary" />
                                <!--input type="submit" value="Export" name="aksi" class="btn btn-primary" /-->
							  </td>
							</tr>
						</table>
                        <div id="data_lap_barang_masuk"></div>
              </div>
        </section><!-- /.content -->