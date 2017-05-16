<?php hak_akses('master'); ?>
<script type="text/javascript">
var table;
$(function(){
	$("#t_nominal_modal").autoNumeric("init", {mDec:0});
	$("#t_nominal_modal").keyup(function(){
		harga = $("#t_nominal_modal").autoNumeric('get');
		$("#nominal_modal").val(harga);
	});
	table = $('#data').DataTable({
						"bFilter": false,
						"paging":   false,
						"ordering":false,
						"aProcessing": true,
						"aServerSide": true,
						"ajax": "modal_proses.php?data",
						"columns": [
							{ "data": "jenis" },
							{ "render": function(data, type, full){
									var sisa = parseInt(full['sisa']);
									$('#t_sisa').val(sisa.toLocaleString('en-US'));
									var nominal = parseInt(full['nominal']);
									return nominal.toLocaleString('en-US') 
									}},
						]
					}); 
	$("#fmodal").submit(function(e){
		$.ajax({
				url: 'modal_proses.php',
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
						  refresh();
						  //table.ajax.reload( null, false );
					}
				}            
		 });
		 e.preventDefault();
	});
	$.fn.dataTable.ext.errMode = 'none';
});

function refresh(){
	table.ajax.reload( null, false );
}	

function cetak_modal(){
	window.open('modal_cetak.php', '_blank');
}
function export_modal(){
	window.open('modal_export.php', '_blank');
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Modal Hari ini
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">
             <div id="message"></div>
              <div style="background:#FFFFFF;padding:15px;">
              	<?php
				$a = mysql_query("select * from modal_harian where tgl_modal=date(now())");
				$b = mysql_fetch_array($a);
				?>
                <form role="form" data-toggle="validator" id="fmodal">
				<input type="hidden" id="aksi" name="aksi" value="tambah" />
						<table width="100%">
                            <tr>
							  <td width="200">Nominal Modal Awal</td>
							  <td>
									<input type="text" id="t_nominal_modal" name="t_nominal_modal" value="<?php echo $b['total_modal']; ?>" class="form-control" required />
                                    <input type="hidden" id="nominal_modal" name="nominal_modal" value="<?php echo $b['total_modal']; ?>" />
							  </td>
							</tr>
                            <tr>
							  <td width="200">Sisa Modal</td>
							  <td>
									<input type="text" id="t_sisa" name="t_sisa" class="form-control" disabled />
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
                <input type="button" onclick="refresh()" value="Refresh" class="btn btn-danger" />
                <br /><br />
                
                <table id="data" class="table table-bordered table-hover" cellspacing="0" width="100%">
                	<thead>
                        <tr>
                            <th>Jenis</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
               </table> 
              </div>           
        </section><!-- /.content -->