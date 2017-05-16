<script>
var table_member;
$(function(){
	$("#batal_member").click(function(){
		clear_member();
	});
	table_member = $('#data_member').DataTable({
						"ordering":false,
						"aProcessing": true,
						"aServerSide": true,
						"ajax": "member_proses.php?data",
						"columns": [
							{ "data": "kode_member" },
							{ "data": "nama_member" },
							{ "data": "alamat" },
							{ "data": "no_tlp" },
							{ "render": function(data, type, full){
									return '<a href="javascript:edit_member(\''+full['kode_member']+'\')" title="edit"><img src="images/edit.png" width="20" height="20" /></a>&nbsp;<a href="javascript:hapus_member(\''+full['kode_member']+'\')" title="hapus" onclick="return confirm(\'yakin menghapus?\')"><img src="images/remove.png" width="20" height="20" />' 
									}},
						]
					}); 
	$("#fmember").submit(function(e){
		$.ajax({
				url: 'member_proses.php',
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				success: function(response) {
					if(response[0]['status']=='failed'){
						$("#message_member").html('<div class="box box-solid box-danger">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">error</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
					}else{
						$("#message_member").html('<div class="box box-solid box-success">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">sukses</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
						  clear_member();
						  //table.ajax.reload( null, false );
					}
				}            
		 });
		 e.preventDefault();
	});
	// The event listener for the file upload
    $('#csv_member').change(function(evt){
		upload_member(evt);
	});
	$.fn.dataTable.ext.errMode = 'none';
});
function refresh(){
	table_member.ajax.reload( null, false );
}
function clear_member(){
	$("#aksi_member").val("tambah");
	$("#kode_lama_member").val("");
	$("#kode_member").val("");
	$("#nama_member").val("");
	$("#alamat").val("");
	$("#no_tlp").val("");
	$("#pin_bb").val("");
	$("#kode_member").focus();
	table_member.ajax.reload( null, false );
}	

function edit_member(id){
		$.ajax({
			url: "member_proses.php",
			data: {'aksi_member':'preview', 'id':id},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#message_member").html('<img src="images/loading.gif" width="50" height="50" />');
			},
			success: function(datas){
					if(datas[0]!=null){
						$("#message_member").html("");
						$("#kode_member").val(datas[0]['kode_member']);
						$("#nama_member").val(datas[0]['nama_member']);
						$("#alamat").val(datas[0]['alamat']);
						$("#no_tlp").val(datas[0]['no_tlp']);
						$("#kode_lama_member").val(datas[0]['kode_member']);
						$("#aksi_member").val('edit');
				   	}else{
				   		$("#message_member").html("data yang akan diedit tidak ditemukan");
				   	}				   
			}
		});		
}
function hapus_member(id){
		$.ajax({
			url: "member_proses.php",
			data: {'aksi_member':'hapus', 'id':id},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				$("#message_member").html('<img src="images/loading.gif" width="50" height="50" />');
			},
			success: function(response){
					if(response[0]['status']=='failed'){
						$("#message_member").html('<div class="box box-solid box-danger">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">error</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
					}else{
						$("#message_member").html('<div class="box box-solid box-success">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">sukses</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
						  clear_member();
						  //table.ajax.reload( null, false );
					}			   
			}
		});		
}
function cetak_member(){
	window.open('member_cetak.php', '_blank');
}
function export_member(){
	window.open('member_export.php', '_blank');
}


    

    // Method that reads and processes the selected file
    function upload_member(evt) {
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
			 $('#data_csv_member').html(output);
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
				$('#preview_csv_member').html(html);
				import_member(to_ajax);
			  };
			  reader.onerror = function(){ alert('Tidak Bisa Membaca File ' + file.fileName); };
            
        }
    }
	function import_member(data_member){
		$.ajax({
			url: 'member_proses.php',
			dataType: 'json',
			type: 'POST',
			data: {'aksi_member':'import', 'data':data_member},
			beforeSend: function(){
				//$("#loading").show();
			},
			success: function(response){
				
						$("#message_member").html('<div class="box box-solid box-success">'+
							'<div class="box-header">'+
							  '<h3 class="box-title">sukses</h3>'+
							'</div>'+
							'<div class="box-body">'+response[0]['pesan']+'</div>'+
						  '</div>');
						  $('#preview_csv_member').html('');
						  $('#data_csv_member').html('');
						  $('#csv_member').val('');
						  $('#import_member').modal('toggle');
						  clear_member();
				
				//$("#loading").hide();
			}
		});
	}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Master Member
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
             <div id="message_member"></div>
              <div style="background:#FFFFFF;padding:15px;">
                <form role="form" data-toggle="validator" id="fmember">
				<input type="hidden" id="aksi_member" name="aksi_member" value="tambah" />
				<input type="hidden" id="kode_lama_member" name="kode_lama_member" />
						<table width="100%">
							<tr class="form-group">
							  <td width="200">Kode Member</td>
							  <td>
									<input type="text" id="kode_member" name="kode_member" class="form-control" required maxlength="25" />
							  </td>
							</tr>
							<tr class="form-group">
							  <td width="200">Nama Member</td>
							  <td>
									<input type="text" id="nama_member" name="nama_member" class="form-control" required maxlength="50" />
							  </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">Alamat</td>
							  <td>
									<input type="text" id="alamat" name="alamat" class="form-control" required maxlength="255" />
							  </td>
							</tr>
                            <tr class="form-group">
							  <td width="200">No. Telpon</td>
							  <td>
									<input type="text" id="no_tlp" name="no_tlp" class="form-control" required maxlength="15" />
							  </td>
							</tr>
							<tr>
							  <td></td>
							  <td>
								<input type="submit" id="simpan" value="Simpan" class="btn btn-primary" />
								<input type="button" id="batal_member" value="Batal" class="btn btn-default" />
							  </td>
							</tr>
						</table>
                        </form> 
                <input type="button" onclick="cetak_member()" value="Cetak" class="btn btn-danger" />
                <input type="button" onclick="export_member()" value="Export" class="btn btn-danger" />
                <input type="button" value="Import" class="btn btn-danger" data-toggle="modal" data-target="#import_member" />
                <input type="button" onclick="refresh()" value="Refresh" class="btn btn-danger" />
                <br /><br />                
                <table id="data_member" class="table table-bordered table-hover" cellspacing="0" width="100%">
                	<thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Member</th>
                            <th>Alamat</th>
                            <th>No. Telp</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
               </table> 
              </div>
<!-- Modal import-->
<div class="modal fade" id="import_member" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Import Member</h4>
      </div>
      <div class="modal-body">
        <div id="dvImportSegments" class="fileupload ">
            <fieldset>
                <legend>Upload CSV File</legend><br />
                format file harus .csv dengan urutan kolom seperti contoh dibawah ini
                <table border="1">
                	<tr>
                    	<th>KODE</th>
                        <th>NAMA MEMBER</th>
                        <th>ALAMAT</th>
                        <th>TELPON</th>
                    </tr>
                    <tr>
                    	<td>1234</td>
                        <td>Lita</td>
                        <td>jl. kesejahteraan sosial no 75</td>
                        <td>085642178</td>
                    </tr>               	
                </table>
                <br />
                <input type="file" name="File Upload" id="csv_member" accept=".csv" />
            </fieldset>
		</div>
        <output id="data_csv_member"></output>
        <table id="preview_csv_member" width="100%" border="1" cellspacing="0">
  		</table>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>             
        </section><!-- /.content -->
        