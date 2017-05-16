<script type="text/javascript">
var table;
$(function(){
	table = $('#data').DataTable({
						"ordering":false,
						"aProcessing": true,
						"aServerSide": true,
						"ajax": "retur_penjualan_proses.php?data",
						"columns": [
							{ "data": "no_nota" },
							{ "render": function(data, type, full){
									var tgl_keluar = full['tgl_keluar'].substr(8, 2)+'/'+full['tgl_keluar'].substr(5, 2)+'/'+full['tgl_keluar'].substr(0, 4);
									return tgl_keluar
									}},
							{ "data": "nama_member" },							
							{ "data": "nama" },
							{ "render": function(data, type, full){
									var total = parseInt(full['total']);
									return total.toLocaleString('en-US') 
									}},
							{ "render": function(data, type, full){
									var dibayar = parseInt(full['dibayar']);
									return dibayar.toLocaleString('en-US') 
									}},
							{ "render": function(data, type, full){
									var kembali = parseInt(full['kembali']);
									return kembali.toLocaleString('en-US') 
									}},		
							{ "render": function(data, type, full){
									var kekurangan = parseInt(full['kekurangan']);
									return kekurangan.toLocaleString('en-US') 
									}},
							{ "render": function(data, type, full){
									return '<a href="javascript:edit(\''+full['no_nota']+'\')" title="edit"><img src="images/edit.png" width="20" height="20" /></a>&nbsp;<a href="javascript:hapus(\''+full['no_nota']+'\')" title="hapus" onclick="return confirm(\'menghapus penjualan maka data pembayaran penjualan tersebut juga akan terhapus dan tidak dapat dikembalikan, tetap yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />&nbsp;<a href="javascript:cetak_nota(\''+full['no_nota']+'\')" title="cetak nota"><img src="images/print.png" width="20" height="20" />' 
									}},
						]
					}); 
	$.fn.dataTable.ext.errMode = 'none';
});

function refresh(){
	table.ajax.reload( null, false );
}	
function detail(kode){
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
				hasil += '<th>HARGA JUAL</th>';
				hasil += '</tr>';
				hasil += '</thead>';
				hasil += '<tbody>';
				$.each(datas, function(i, data){
					tgl_perubahan = data['tgl_perubahan'].substr(8, 2)+'/'+data['tgl_perubahan'].substr(5, 2)+'/'+data['tgl_perubahan'].substr(0, 4)+' '+data['tgl_perubahan'].substr(11, 8);
					hasil += '<tr>';
					hasil += '<td>'+tgl_perubahan+'</td>';
					harga = parseInt(data['harga']);
					hasil += '<td>'+harga.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
					harga_jual = parseInt(data['harga_jual']);
					hasil += '<td>'+harga_jual.toLocaleString('en-US', {minimumFractionDigits: 2})+'</td>';
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
function cetak_nota(kode){
	var printWindow=window.open('nota_penjualan.php?no='+kode,'','width=700,height=500');
		printWindow.print();
}
/*
setInterval( function () {
    table.ajax.reload( null, false ); // user paging is not reset on reload
}, 3000 );
*/
function edit(id){
		window.open('?h=retur_penjualan_form&no='+id, '_self');
}
function hapus(id){
		$.ajax({
			url: "retur_penjualan_proses.php",
			data: {'aksi_penjualan':'hapus', 'no_nota':id},
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
						  table.ajax.reload( null, false );
					}			   
			}
		});		
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
            Retur Penjualan
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
             <div id="message"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <table id="data" class="table table-bordered table-hover" cellspacing="0" width="100%">
                	<thead>
                        <tr>
                            <th>No Nota</th>
                            <th>Tgl Keluar</th>
                            <th>Member</th>
                            <th>Data Entry</th>
                            <th>Total</th>
                            <th>Dibayar</th>
                            <th>Kembali</th>
                            <th>Kekurangan</th>
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
                        <th>HARGA JUAL</th>
                    </tr>
                    <tr>
                    	<td>1234</td>
                        <td>Semen Holcim Kantong 4Kg</td>
                        <td>40000</td>
                        <td>60000</td>
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
      <form method="post" action="cetak_nota.php" target="_blank">
      	<input type="hidden" name="barcode" id="barcode" />
        <input type="number" required name="jml" id="jml" placeholder="input jumlah print barcode" class="form-control" /><br />
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