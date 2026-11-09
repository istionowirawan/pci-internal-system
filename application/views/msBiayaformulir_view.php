<script>
	$(document).ready(function() {
		$(".btnEditForm").click(function() {
			var idpilih = $(this).attr("title");
			
			if($(this).val()=="Edit") {
				$("#lblBiayaForm"+idpilih).hide(300);
				$("#txtBiayaForm"+idpilih).attr("type","text");
				$("#txtBiayaForm"+idpilih).focus();
				$(this).val('Update Biaya');
			} else {
				var dataform = "thnajar=" + $(this).attr('alt');
				dataform += "&biayaform=" + $("#txtBiayaForm"+idpilih).val();
				
				$.ajax({
					type: "POST",
					url: "Ms_biayaformulir_ctr/update_biayaformulir/",
					data: dataform,
					success: function(data, status) {
						alert(status);
						$("#content_adm").load("Ms_biayaformulir_ctr");
					}
				});
				
				return false;					
			}
			
		});
	});
</script>
				
<style>
</style>

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>

<div class='header-popup' >
Master Biaya Formulir
</div>

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		
		<form action="" method="POST" name="frmBiformulir" id="frmBiformulir" >
			<table class="table table-hover table-bordered" id='tblBiformulir' >
				
				<?php
				echo "<tr style='background-color:blue;color:#ffffff;text-align:center;'>";
				echo "<td>Tahun Ajaran</td>";
				echo "<td>Active</td>";
				echo "<td>Biaya Formulir</td>";
				echo "<td width='150'>Set by</td>";
				echo "<td>Aksi</td>";
				echo "</tr>";
					
				foreach($data_ta->result() as $row) {
					echo "<tr style='text-align:center;' >";
					echo "<td>".$row->tahun."</td>";
					echo "<td>".($row->status==1?"Active":"Not Active")."</td>";
					echo "<td>";
						echo "<label name='lblBiayaForm' id='lblBiayaForm".$row->id."' >Rp. ". number_format($row->biaya)."</label>";
						echo "<input type='hidden' class='form-control' name='txtBiayaForm' id='txtBiayaForm".$row->id."'' >";
					echo "</td>";
					echo "<td>".$row->nama . " (".$row->update_date.")"."</td>";
					echo "<td><input type='button' class='btn btn-primary btn-block btnEditForm' value='Edit' title='".$row->id."' alt='".$row->tahun."' ></td>";
					echo "</tr>";
				}
				?>
				
				
			</table>
		</form>
		
		
		
	</div>
	
</div>