<?php hak_akses('master'); ?>
<script type="text/javascript">
var table;
$(function(){
	$("#t_harga_modal").autoNumeric("init");
	$("#t_harga_modal").keyup(function(){
		harga = $("#t_harga_modal").autoNumeric('get');
		$("#harga_modal").val(harga);
	});
	$("#t_harga_jual").autoNumeric("init");
	$("#t_harga_jual").keyup(function(){
		harga = $("#t_harga_jual").autoNumeric('get');
		$("#harga_jual").val(harga);
	});
	$("#markup").keyup(function(){
		markup = $('#markup').val();
		harga = $("#t_harga_modal").autoNumeric('get');
		persentase = harga/100;
		harga_jual = parseInt(harga) + (persentase*markup);
		$('#t_harga_jual').autoNumeric('set', harga_jual);
		$('#harga_jual').val(harga_jual);
	});
	$("#t_harga_diskon").autoNumeric("init");
	$("#diskon").keyup(function(){
		diskon = $('#diskon').val();
		harga_jual = $("#t_harga_jual").autoNumeric('get');
		persentase = harga_jual/100;
		harga_diskon = parseInt(harga_jual) - (persentase*diskon);
		$('#t_harga_diskon').autoNumeric('set', harga_diskon);
		$('#harga_diskon').val(harga_diskon);
	});
	$("#batal").click(function(){
		clear();
	});
	table = $('#data').DataTable({
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
									return full['markup']
									}},
							{ "render": function(data, type, full){
									var harga_jual = parseInt(full['harga_jual']);
									return harga_jual.toLocaleString('en-US', {minimumFractionDigits: 2}) 
									}},
							{ "render": function(data, type, full){
									return full['diskon']
									}},
							{ "render": function(data, type, full){
									diskon = full['diskon'];
									harga_jual = full['harga_jual'];
									persentase = harga_jual/100;
									harga_diskon = parseInt(harga_jual) - (persentase*diskon);
									return harga_diskon.toLocaleString('en-US', {minimumFractionDigits: 2}) 
									//return '0'
									}},
							{ "render": function(data, type, full){
									return '<a href="javascript:history(\''+full['kode']+'\')" title="history harga"><img src="images/history.png" width="20" height="20" /></a>' 
									}},
							{ "data": "saldo" },
							{ "render": function(data, type, full){
									return '<a href="javascript:edit(\''+full['kode']+'\')" title="edit"><img src="images/edit.png" width="20" height="20" /></a>&nbsp;<a href="javascript:hapus(\''+full['kode']+'\')" title="hapus" onclick="return confirm(\'yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />&nbsp;<a href="javascript:cetak_barcode(\''+full['kode']+'\', \''+full['saldo']+'\')" title="cetak barcode"><img src="images/barcode.png" width="20" height="20" />' 
									}},
						]
					}); 
	$("#fbarang").submit(function(e){
		$.ajax({
				url: 'barang_proses.php',
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				success: function(response) {
					if(response[0]['status']=='failed'){
						$("#message").html('<div class="box box-solid box-danger">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">error</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
					}else{
						$("#message").html('<div class="box box-solid box-success">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">sukses</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
						  clear();
						  //table.ajax.reload( null, false );
					}
				}            
		 });
		 e.preventDefault();
	});
	$('#csv_barang').change(function(evt){
		upload_barang(evt);
	});
	$.fn.dataTable.ext.errMode = 'none';
});

function clear(){
	$("#aksi").val("tambah");
	$("#kode_lama").val("");
	$("#kode").val("");
	$("#nama_barang").val("");
	$("#t_harga_modal").val("");
	$("#harga_modal").val("");
	$("#t_harga_jual").val("");
	$("#harga_jual").val("");
	$('#markup').val("");
	$('#diskon').val("");
	$('#t_harga_diskon').val("");
	$('#harga_diskon').val("");
	$('#saldo').val('');
	$("#kode").focus();
	table.ajax.reload( null, false );
}
function refresh(){
	table.ajax.reload( null, false );
}	
function history(kode){
	$("#history_modal").modal('toggle');
	$.ajax({
		url: 'barang_proses.php',
		dataType: 'json',
		type: 'POST',
		data: {'aksi':'history', 'id':kode},
		beforeSend: function(){
			$("#loading").show();
		},
		success: function(datas){
			if(datas[0]!==null){
				hasil = '<table class="table table-bordered">';
        		hasil += '<thead>';
        		hasil += '<tr>';
				hasil += '<th>TANGGAL PERUBAHAN</th>';
            	hasil += '<th>HARGA MODAL</th>';
            	hasil += '<th>MARKUP</th>';
				hasil += '<th>HARGA JUAL</th>';
				hasil += '<th>DISKON</th>';
				hasil += '<th>HARGA DISKON</th>';
				hasil += '</tr>';
				hasil += '</thead>';
				hasil += '<tbody>';
				$.each(datas, function(i, data){
					tgl_perubahan = data['tgl_perubahan'].substr(8, 2)+'/'+data['tgl_perubahan'].substr(5, 2)+'/'+data['tgl_perubahan'].substr(0, 4)+' '+data['tgl_perubahan'].substr(11, 8);
					hasil += '<tr>';
					hasil += '<td>'+tgl_perubahan+'</td>';
					harga = parseInt(data['harga']);
					hasil += '<td>'+harga.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					hasil += '<td>'+data['markup']+'</td>';
					harga_jual = parseInt(data['harga_jual']);
					hasil += '<td>'+harga_jual.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					hasil += '<td>'+data['diskon']+'</td>';					
					diskon = data['diskon'];
					persentase = harga_jual/100;
					harga_diskon = parseInt(harga_jual) - (persentase*diskon);
					hasil += '<td>'+harga_diskon.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					hasil += '</tr>';
				});
				hasil += '</tbody>';
				hasil += '</table>';
				$("#history").html(hasil);
			}else{
				$("#history").html('');
			}
			$("#loading").hide();
		}
	});
}
function cetak_barcode(kode, saldo){
	$("#barcode_modal").modal('toggle');
	$('#barcode').val(kode);
	$('#jml').val(saldo);
}
/*
setInterval( function () {
    table.ajax.reload( null, false ); // user paging is not reset on reload
}, 3000 );
*/
function edit(id){
		$.ajax({
			url: "barang_proses.php",
			data: {'aksi':'preview', 'id':id},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#message").html('<img src="images/loading.gif" width="50" height="50" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						$("#message").html("");
						$("#kode").val(datas[0]['kode']);
						$("#nama_barang").val(datas[0]['nama_barang']);
						$('#tipe > option').each(function(){
							if($.trim(this.value)==$.trim(datas[0]['tipe'])){
								$(this).prop('selected', true);
							}
						});
						//$("#tipe").val(datas[0]['tipe']);
					 	//$('#tipe option[value='+datas[0]['tipe']+']').prop('selected', true);
						$("#harga_modal").val(datas[0]['harga_modal']);
						$("#t_harga_modal").autoNumeric('set', datas[0]['harga_modal']);
						$("#harga_jual").val(datas[0]['harga_jual']);
						$("#t_harga_jual").autoNumeric('set', datas[0]['harga_jual']);
						$("#kode_lama").val(datas[0]['kode']);
						$('#saldo').val(datas[0]['saldo']);
						$("#aksi").val('edit');
						$('#markup').val(datas[0]['markup']);
						$('#diskon').val(datas[0]['diskon']);
						diskon = datas[0]['diskon'];
						harga_jual = datas[0]['harga_jual'];
						persentase = harga_jual/100;
						harga_diskon = parseInt(harga_jual) - (persentase*diskon);
						$('#t_harga_diskon').autoNumeric('set', harga_diskon);
						$('#harga_diskon').val(harga_diskon);

				   	}else{
				   		$("#message").html("data yang akan diedit tidak ditemukan");
				   	}				   
			}
		});		
}
function hapus(id){
		$.ajax({
			url: "barang_proses.php",
			data: {'aksi':'hapus', 'id':id},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#message").html('<img src="images/loading.gif" width="50" height="50" />');
			},
			success: function(response){
					if(response[0]['status']=='failed'){
						$("#message").html('<div class="box box-solid box-danger">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">error</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
					}else{
						$("#message").html('<div class="box box-solid box-success">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">sukses</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
						  clear();
						  //table.ajax.reload( null, false );
					}			   
			}
		});		
}
function cetak_barang(){
	window.open('barang_cetak.php', '_blank');
}
function cetak_semua_barcode(){
	window.open('cetak_semua_barcode.php', '_blank');
}
function export_barang(){
	window.open('barang_export.php', '_blank');
}
// The event listener for the file upload
    

    

    // Method that reads and processes the selected file
    function upload_barang(evt) {
    	if (!browserSupportFileUpload()) {
        	alert('browser tidak support csv file!');
        } else {
            var files = evt.target.files; // FileList object
      		var file = files[0];
		  	var output = ''
			  output += '<span style="font-weight:bold;">' + escape(file.name) + '</span><br />\n';
			  output += ' - Ukuran: ' + file.size + ' bytes<br />\n';
			  output += ' - Edit Terakhir: ' + (file.lastModifiedDate ? file.lastModifiedDate.toLocaleDateString() : 'n/a') + '<br />\n';
			  // read the file contents
			  //printTable(file);
	
			 // post the results
			 $('#data_csv_barang').html(output);
			 var reader = new FileReader();
			  reader.readAsText(file);
			  reader.onload = function(event){
				var csv = event.target.result;
				var data = $.csv.toArrays(csv);
				var html = '';
				var to_ajax = "";
				for(var row in data) {
				  html += '<tr>\r\n';
				  var kolom = "";
				  for(var item in data[row]) {
					html += '<td>' + data[row][item] + '</td>\r\n';
					if(kolom==""){
						kolom = "'"+data[row][item]+"'";
					}else{
						kolom += ", '"+data[row][item]+"'";
					}
				  }
				  if(to_ajax==""){
				  	to_ajax = kolom;
				  }else{
				  	to_ajax += "@_"+kolom;
				  }
				  kolom = "";
				  html += '</tr>\r\n';
				}
				$('#preview_csv_barang').html(html);
				import_barang(to_ajax);
			  };
			  reader.onerror = function(){ alert('Tidak Bisa Membaca File ' + file.fileName); };
            
        }
    }
	function import_barang(data_barang){
		$.ajax({
			url: 'barang_proses.php',
			dataType: 'json',
			type: 'POST',
			data: {'aksi':'import', 'data':data_barang},
			beforeSend: function(){
				//$("#loading").show();
			},
			success: function(response){
				
						$("#message").html('<div class="box box-solid box-success">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">sukses</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
						  $('#preview_csv_barang').html('');
						  $('#data_csv_barang').html('');
						  $('#csv_barang').val('');
						  $('#import_barang').modal('toggle');
						  clear();
				
				//$("#loading").hide();
			}
		});
	}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Master Barang
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
             <div id="message"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <form role="form" data-toggle="validator" id="fbarang">
				<input type="hidden" id="aksi" name="aksi" value="tambah" />
				<input type="hidden" id="kode_lama" name="kode_lama" />
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Kode</td>
							  <td>
									<input type="text" id="kode" name="kode" class="form-control" required maxlength="25" />
							  </td>
							</tr>
							<tr class="form-group">
							  <td width="200">Nama Barang</td>
							  <td>
									<input type="text" id="nama_barang" name="nama_barang" class="form-control" required maxlength="255" />
							  </td>
							</tr>
                            <tr>
							  <td width="200">Harga Modal</td>
							  <td>
									<input type="text" id="t_harga_modal" name="t_harga_modal" class="form-control" required />
                                    <input type="hidden" id="harga_modal" name="harga_modal" />
							  </td>
							</tr>
							<tr>
							  <td width="200">Markup %</td>
							  <td>
									<input type="text" id="markup" name="markup" class="form-control" />
							  </td>
							</tr>
                            <tr>
							  <td width="200">Harga Jual</td>
							  <td>
									<input type="text" id="t_harga_jual" name="t_harga_jual" class="form-control" required />
                                    <input type="hidden" id="harga_jual" name="harga_jual" />
							  </td>
							</tr>
							<tr>
							  <td width="200">Diskon %</td>
							  <td>
									<input type="text" id="diskon" name="diskon" class="form-control" />
							  </td>
							</tr>
							<tr>
							  <td width="200">Harga Diskon</td>
							  <td>
									<input type="text" id="t_harga_diskon" name="t_harga_diskon" class="form-control" readonly />
                                    <input type="hidden" id="harga_diskon" name="harga_diskon" />
							  </td>
							</tr>
                            <tr>
                            	<td>Stok</td>
                                <td><input type="number" min="0" name="saldo" id="saldo" class="form-control" /></td>
                            </tr>
							<tr>
							  <td></td>
							  <td>
								<input type="submit" id="simpan" value="Simpan" class="btn btn-primary" />
								<input type="button" id="batal" value="Batal" class="btn btn-default" />
							  </td>
							</tr>
						</table>
                        </form> 
                <input type="button" onclick="cetak_semua_barcode()" value="Cetak Semua Barcode" class="btn btn-danger" />
                <input type="button" onclick="cetak_barang()" value="Cetak" class="btn btn-danger" />
                <input type="button" onclick="export_barang()" value="Export" class="btn btn-danger" />
                <input type="button" value="Import" class="btn btn-danger" data-toggle="modal" data-target="#import_barang" />
                <input type="button" onclick="refresh()" value="Refresh" class="btn btn-danger" />
                <br /><br />
                
                <table id="data" class="table table-bordered table-hover" cellspacing="0" width="100%">
                	<thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Harga Modal</th>
                            <th>Markup %</th>
                            <th>Harga Jual</th>
                            <th>Diskon %</th>
                            <th>Harga Diskon</th>
                            <th>History Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
               </table> 
              </div>
<!-- Modal history-->
<div class="modal fade" id="history_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">History Harga Modal</h4>
      </div>
      <div class="modal-body" id="detail_jadwal">
        <img src="images/loading.gif" width="50" height="50" id="loading" />
        <div id="history"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--end modal history-->   
<!-- Modal import-->
<div class="modal fade" id="import_barang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Import Barang</h4>
      </div>
      <div class="modal-body">
        <div id="dvImportSegments" class="fileupload ">
            <fieldset>
                <legend>Upload CSV File</legend><br />
                format file harus .csv dengan urutan kolom seperti contoh dibawah ini
                <table border="1">
                	<tr>
                    	<th>KODE</th>
                        <th>NAMA BARANG</th>
                        <th>HARGA MODAL</th>
                        <th>MARKUP %</th>
                        <th>HARGA JUAL</th>
                        <th>DISKON %</th>
                        <th>HARGA DISKON</th>
                        <th>STOK</th>
                    </tr>
                    <tr>
                    	<td>1234</td>
                        <td>Nama Barangnya apa</td>
                        <td>20000</td>
                        <td>50</td>
                        <td>30000</td>
                        <td>20</td>
                        <td>24000</td>
                        <td>10</td>
                    </tr>               	
                </table>
                <br />
                <input type="file" name="File Upload" id="csv_barang" accept=".csv" />
            </fieldset>
		</div>
        <output id="data_csv_barang"></output>
        <table id="preview_csv_barang" width="100%" border="1" cellspacing="0">
  		</table>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--end modal history-->  
<!-- Modal history-->
<div class="modal fade" id="barcode_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Print Barcode</h4>
      </div>
      <div class="modal-body" id="detail_jadwal">
      <form method="post" action="cetak_barcode.php" target="_blank" class="form-horizontal">
      	<input type="hidden" name="barcode" id="barcode" />
        <div class="form-group">
        	<div class="form-inline">
        		<label class="col-lg-3 control-label">Jumlah Print :</label>
                <div class="col-lg-5">
                    <input type="number" required name="jml" id="jml" size="3" placeholder="input jumlah print barcode" class="form-control" />
                </div>
            </div>
        </div>
        <div class="form-group">
        	<div class="form-inline">
        		<label class="col-lg-3 control-label">Panjang :</label>
        		<div class="col-lg-5">
                	<input type="text" required name="panjang" id="panjang" value="5" size="3" placeholder="input panjang label (cm)" class="form-control" />
                	cm
                </div>
            </div>
        </div>
        <div class="form-group">
        	<div class="form-inline">
        		<label class="col-lg-3 control-label">lebar :</label>
        		<div class="col-lg-5">
                	<input type="text" required name="lebar" value="2" id="lebar" size="3" placeholder="input lebar label (cm)" class="form-control" />
                	cm
                </div>    
            </div>
        </div>
        <input type="submit" value="Cetak" class="btn btn-danger" />
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--end modal history-->                
        </section><!-- /.content -->