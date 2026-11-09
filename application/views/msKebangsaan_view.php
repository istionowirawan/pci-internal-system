<script>
	$(document).ready(function() {
		$(".btnEditBangsa").click(function() {
			var idEdit = $(this).attr("alt");
			alert(idEdit);
		});
	});
</script>
				

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>

<div class='header-popup' >
Master Kebangsaan
</div>

<div class="isi-popup" >
	<div class="table-responsive">
		<form action="" method="POST" name="frmKebangsaan" id="frmKebangsaan" >
			<table class="table table-hover" >
				<tr align='center' class='header-popup-tbl' >
					<td>Kewarganegaraan</td>
					<td>Symbol mata uang</td>
					<td>Status</td>
					<td>Dibuat oleh</td>
					<td>Dibuat tgl</td>
					<td>Diubah oleh</td>
					<td>Diubah tgl</td>
					<!--<td>Edit</td>-->
				</tr>
				
				<?php
				foreach($data_kebangsaan->result() as $object) {
					echo "<tr align='center'>";
					echo "<td>".$object->citizenship."</td>";
					echo "<td>".$object->currency_symbol."</td>";
					echo "<td>".($object->status==1?'Active':'Nonactive')."</td>";
					echo "<td>".$object->username."</td>";
					echo "<td>".$object->created_date."</td>";
					echo "<td>".$object->userEdit."</td>";
					echo "<td>".$object->updated_date."</td>";
					//echo "<td><input type='button' value='edit' class='btn btn-primary input-sm btnEditBangsa' alt='".$object->nationality_id."' ></td>";
					echo "</tr>";
				}
				?>
				
				<tr><td colspan='4'></td></tr>
				
			</table>
		</form>
	</div>
	
</div>