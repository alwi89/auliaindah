<?php hak_akses('master'); ?>
<script type="text/javascript">
$(function(){
	$("#pjdari").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
	$("#pjsampai").datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy',
		todayHighlight: true
	});
});

function data_lap_penjualan(){
	var tm_total_jml = 0;
	var tm_total_subtotal = 0;
	var tm_total_modal = 0;
	var tm_total_laba = 0;
	$.ajax({
		url: 'lap_penjualan_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'data', 'dari':$('#pjdari').val(), 'sampai':$('#pjsampai').val(), 'member': 'SEMUA'},
		beforeSend: function(){
			$("#data_lap_penjualan").html('<img src="images/loading.gif" width="30" height="30" />');
		},
		success: function(datas){
			if(datas[0]!==null){
				var kode_member = "";
				var hasil = "";
				var total_jml = 0;
				var total_subtotal = 0;
				var total_modal = 0;
				var total_laba = 0;
				var no = 1;
				$.each(datas, function(i, data){
					if(kode_member==""){
						kode_member = data['kode_member'];
						hasil = '<b>'+parseInt(no)+'.'+data['nama_member']+' - '+data['alamat']+'</b>';
						no++;
						hasil += '<table width="100%" cellspacing="0" cellpading="5" border="1">';
						hasil += '<thead>';
						hasil += '<tr>';
						hasil += '<th>TGL KELUAR</th>';
						hasil += '<th>NO NOTA</th>';
						hasil += '<th>NAMA BARANG</th>';						
						hasil += '<th>JML</th>';
						hasil += '<th>HARGA MODAL</th>';
						hasil += '<th>HARGA</th>';
						hasil += '<th>TOTAL MODAL</th>';
						hasil += '<th>SUB TOTAL</th>';
						hasil += '<th>KEUNTUNGAN</th>';
						hasil += '<th>DATA ENTRY</th>';
						hasil += '</tr>';
						hasil += '</thead>';
						hasil += '<tbody>';
						tgl_keluar = data['tgl_keluar'].substr(8, 2)+'/'+data['tgl_keluar'].substr(5, 2)+'/'+data['tgl_keluar'].substr(0, 4);
						hasil += '<tr>';
						hasil += '<td>'+tgl_keluar+'</td>';
						hasil += '<td>'+data['no_nota']+'</td>';
						hasil += '<td>'+data['nama_barang']+'</td>';
						hasil += '<td>'+data['qty']+'</td>';
						harga_modal = parseInt(data['harga_modal']);
						hasil += '<td>'+harga_modal.toLocaleString('en-US')+'</td>';
						harga = parseInt(data['harga']);
						hasil += '<td>'+harga.toLocaleString('en-US')+'</td>';
						modal = parseInt(data['total_modal']);
						hasil += '<td>'+modal.toLocaleString('en-US')+'</td>';
						sub_total = parseInt(data['sub_total']);
						hasil += '<td>'+sub_total.toLocaleString('en-US')+'</td>';
						keuntungan = parseInt(data['laba']);
						hasil += '<td>'+keuntungan.toLocaleString('en-US')+'</td>';
						hasil += '<td>'+data['nama']+'</td>';
						hasil += '</tr>';
						total_jml += parseInt(data['qty']);
						total_subtotal += sub_total;
						total_modal += modal;
						total_laba += keuntungan;
						tm_total_jml += parseInt(data['qty']);
						tm_total_subtotal += sub_total;
						tm_total_modal += modal;
						tm_total_laba += keuntungan;
					}else{
						if(kode_member==data['kode_member']){
							kode_member = data['kode_member'];
							tgl_keluar = data['tgl_keluar'].substr(8, 2)+'/'+data['tgl_keluar'].substr(5, 2)+'/'+data['tgl_keluar'].substr(0, 4);
							hasil += '<tr>';
							hasil += '<td>'+tgl_keluar+'</td>';
							hasil += '<td>'+data['no_nota']+'</td>';
							hasil += '<td>'+data['nama_barang']+'</td>';
							hasil += '<td>'+data['qty']+'</td>';
							harga_modal = parseInt(data['harga_modal']);
							hasil += '<td>'+harga_modal.toLocaleString('en-US')+'</td>';
							harga = parseInt(data['harga']);
							hasil += '<td>'+harga.toLocaleString('en-US')+'</td>';
							modal = parseInt(data['total_modal']);
							hasil += '<td>'+modal.toLocaleString('en-US')+'</td>';
							sub_total = parseInt(data['sub_total']);
							hasil += '<td>'+sub_total.toLocaleString('en-US')+'</td>';
							keuntungan = parseInt(data['laba']);
							hasil += '<td>'+keuntungan.toLocaleString('en-US')+'</td>';
							hasil += '<td>'+data['nama']+'</td>';
							hasil += '</tr>';
							total_jml += parseInt(data['qty']);
							total_subtotal += sub_total;
							total_modal += modal;
							total_laba += keuntungan;
							tm_total_jml += parseInt(data['qty']);
							tm_total_subtotal += sub_total;
							tm_total_modal += modal;
							tm_total_laba += keuntungan;
						}else{
							kode_member = data['kode_member'];
							hasil += '<tr>';
							hasil += '<td colspan="3" align="right"><b>Total</b></td>';
							hasil += '<td><b>'+total_jml.toLocaleString('en-US')+'</b></td>';
							hasil += '<td colspan="2"></td>';
							hasil += '<td><b>'+total_modal.toLocaleString('en-US')+'</b></td>';
							hasil += '<td><b>'+total_subtotal.toLocaleString('en-US')+'</b></td>';
							hasil += '<td colspan="2"><b>'+total_laba.toLocaleString('en-US')+'</b></td>';
							hasil += '</tr>';
							hasil += '</tbody>';
							hasil += '</table>';
							total_jml = 0;
							total_subtotal = 0;
							total_modal = 0;
							total_laba = 0;
							
							hasil += '<b>'+parseInt(no)+'.'+data['nama_member']+' - '+data['alamat']+'</b>';
							no++;
							hasil += '<table width="100%" cellspacing="0" cellpading="5" border="1">';
							hasil += '<thead>';
							hasil += '<tr>';
							hasil += '<th>TGL KELUAR</th>';
							hasil += '<th>NO NOTA</th>';
							hasil += '<th>NAMA BARANG</th>';						
							hasil += '<th>JML</th>';
							hasil += '<th>HARGA MODAL</th>';
							hasil += '<th>HARGA</th>';
							hasil += '<th>TOTAL MODAL</th>';
							hasil += '<th>SUB TOTAL</th>';
							hasil += '<th>KEUNTUNGAN</th>';
							hasil += '<th>DATA ENTRY</th>';
							hasil += '</tr>';
							hasil += '</thead>';
							hasil += '<tbody>';
							tgl_keluar = data['tgl_keluar'].substr(8, 2)+'/'+data['tgl_keluar'].substr(5, 2)+'/'+data['tgl_keluar'].substr(0, 4);
							hasil += '<tr>';
							hasil += '<td>'+tgl_keluar+'</td>';
							hasil += '<td>'+data['no_nota']+'</td>';
							hasil += '<td>'+data['nama_barang']+'</td>';
							hasil += '<td>'+data['qty']+'</td>';
							harga_modal = parseInt(data['harga_modal']);
							hasil += '<td>'+harga_modal.toLocaleString('en-US')+'</td>';
							harga = parseInt(data['harga']);
							hasil += '<td>'+harga.toLocaleString('en-US')+'</td>';
							modal = parseInt(data['total_modal']);
							hasil += '<td>'+modal.toLocaleString('en-US')+'</td>';
							sub_total = parseInt(data['sub_total']);
							hasil += '<td>'+sub_total.toLocaleString('en-US')+'</td>';
							keuntungan = parseInt(data['laba']);
							hasil += '<td>'+keuntungan.toLocaleString('en-US')+'</td>';
							hasil += '<td>'+data['nama']+'</td>';
							hasil += '</tr>';
							total_jml += parseInt(data['qty']);
							total_subtotal += sub_total;
							total_modal += modal;
							total_laba += keuntungan;
							tm_total_jml += parseInt(data['qty']);
							tm_total_subtotal += sub_total;
							tm_total_modal += modal;
							tm_total_laba += keuntungan;	
						}
						
					}
					//$('<div id="ltm-'+i+'"></div>').appendTo('#data_lap_penjualan');
					//data_detail_lap_tagihan_suplier(data['kode_member'], "ltm-"+i);
					//data_detail_lap_tagihan_suplier(data['kode_member']);
				});
				hasil += '<tr>';
				hasil += '<td colspan="3" align="right"><b>Total</b></td>';
				hasil += '<td><b>'+total_jml.toLocaleString('en-US')+'</b></td>';
				hasil += '<td colspan="2"></td>';
				hasil += '<td><b>'+total_modal.toLocaleString('en-US')+'</b></td>';
				hasil += '<td><b>'+total_subtotal.toLocaleString('en-US')+'</b></td>';
				hasil += '<td colspan="2"><b>'+total_laba.toLocaleString('en-US')+'</b></td>';
				hasil += '</tr>';
				hasil += '</tbody>';
				hasil += '</table>';
				hasil += '<div align="right" style="color:red;">Jumlah Total Item : '+tm_total_jml.toLocaleString('en-US');
				hasil += '<br />Jumlah Total Transaksi : '+tm_total_subtotal.toLocaleString('en-US');
				var dibayar = parseInt(datas[0]['dibayar']);
				var kekurangan = parseInt(datas[0]['kekurangan']);
				hasil += '<br />Jumlah Total Modal : '+tm_total_modal.toLocaleString('en-US');
				hasil += '<br />Jumlah Total Keuntungan : '+tm_total_laba.toLocaleString('en-US');
				hasil += '<br />Dibayar : '+dibayar.toLocaleString('en-US');
				hasil += '<br />Kekurangan : '+kekurangan.toLocaleString('en-US');
				hasil += '</div>';
				total_jml = 0;
				total_subtotal = 0;
				total_modal = 0;
				total_laba = 0;
				$('#data_lap_penjualan').html(hasil);
				//hasil += '<div align="right"><b><hr />Total Keseluruhan : '+ts_total_jml.toLocaleString('en-US', {minimumFractionDigits: 2});				
				//hasil += '<br />Total Potongan : '+ts_total_potongan.toLocaleString('en-US', {minimumFractionDigits: 2});
				//hasil += '<br />Total Dibayar : '+ts_total_subtotal.toLocaleString('en-US', {minimumFractionDigits: 2});
				//hasil += '<br />Total Kekurangan : '+ts_total_kekurangan.toLocaleString('en-US', {minimumFractionDigits: 2})+"</b></div>";
				//$("#data_lap_penjualan").html(hasil);
			}else{
				$("#data_lap_penjualan").html('tidak ada data');
			}
		}
	});
}
function cetak(){
	var laporan = $('#data_lap_penjualan').html();
	var printWindow = window.open('', '', 'height=500,width=800');
            printWindow.document.write('<html><head><title>cetak laporan penjualan</title>');
            printWindow.document.write('</head><body>');
			printWindow.document.write('<b>Laporan Penjualan</b>');
			printWindow.document.write('<br /><b>Periode : '+$('#pjdari').val()+' s/d '+$('#pjsampai').val()+'</b><br /><br />');
            printWindow.document.write(laporan);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Laporan Penjualan
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
        	<div style="background:#FFFFFF;padding:15px;">
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Dari</td>
							  <td>
									<input type="text" id="pjdari" name="pjdari" class="form-control"  required maxlength="10" />
							  </td>
                              <td>
                              	s/d
                              </td>
                              <td>
                              		<input type="text" id="pjsampai" name="bmsampai" class="form-control"  required maxlength="10" />
                              </td>
							</tr>
							<tr>
							  <td></td>
							  <td colspan="3">
								<input type="button" value="Lihat" onclick="data_lap_penjualan()" class="btn btn-primary" />
                                <input type="button" value="Cetak" onclick="cetak()" name="aksi" class="btn btn-primary" />
                                <!--input type="submit" value="Export" name="aksi" class="btn btn-primary" /-->
							  </td>
							</tr>
						</table>
                        <div id="data_lap_penjualan"></div>
              </div>
        </section><!-- /.content -->