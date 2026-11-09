<script>
	$(document).ready(function() {
		$("#infoTahunAjaran").hide();
			
		$(".btn").click(function() {
			var idThnAjr = $(this).attr("title");
			
			$.post("Ms_tahunajaranPCI_ctr/update_thn_ajr_pci/", {
				ThnAjrID:idThnAjr
			}, function(data, status) {
				$("#content_adm").load("Ms_tahunajaranPCI_ctr");
				//location.reload();
			});
							
		});
		
	});
</script>
				

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>

<div class='header-popup' >
Tahun Ajaran ( <?php echo $ta_pci_active->tahun . " Semester " . $ta_pci_active->semester; ?> )
</div>

<div class="isi-popup" >
	<div class="table-responsive">
		<form action="" method="POST" name="frmChangePass" id="frmChangePass" >
			<table class="table table-hover table-striped" >
				<tr align='center' class='header-popup-tbl' style='background-color:#405BB5;color:#ffffff;' >
					<td>Id</td>
					<td>Tahun Ajaran</td>
					<td>Semester</td>
					<td>Status</td>
				</tr>
				
				<?php
				foreach($taPCI_data->result() as $object) {
					echo "<tr align='center'>";
					echo "<td>" . $object->id . "</td>"; 
					echo "<td>" . $object->tahun . "</td>";
					echo "<td>" . ($object->semester==1?"Ganjil":"Genap") . "</td>";
					echo "<td width='20%'>"; 
						if($object->status==0) {
							echo "<button type='button' class='btn btn-info' style='width:100%' title='".$object->id."' >Non-active</button>"; 
						} else {
							echo "<button type='button' class='btn btn-info active' style='width:100%' title='".$object->id."' >Active</button>";
						}
					echo "</td>";
					echo "</tr>";
				}
				?>
				
				<div class="alert alert-success" id="infoTahunAjaran" >
				   <a href="#" class="close" data-dismiss="alert">&times;</a>
				   <strong>Update Success : </strong> Tahun ajaran has been changed 
				</div>
				
				
				<tr><td colspan='4'></td></tr>
				
			</table>
		</form>
	</div>
	
</div>