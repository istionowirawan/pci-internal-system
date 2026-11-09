<script>
	$(document).ready(function() {
		$(".btnEditGrade").click(function() {
			var idEdit = $(this).attr("alt");
			
			if($(this).val()=="Edit") {
				$("#txtRangeFrom"+idEdit).show(100);
				$("#txtRangeTo"+idEdit).show(100);
				$("#lblRangeFrom"+idEdit).hide(100);
				$("#lblRangeTo"+idEdit).hide(100);
				$(this).val("Update");
			} else {
				var dataubah = "";
				dataubah += "id="+idEdit;
				dataubah += "&range_from="+$("#txtRangeFrom"+idEdit).val();
				dataubah += "&range_to="+$("#txtRangeTo"+idEdit).val();
				
				$.ajax({
					type: "POST",
					url: "Ms_grade_ctr/update_grade/",
					data: dataubah,
					success: function(data, status) {
						//alert(data);
						if(data==1) {
							//alert("Update Sukses");
						} else {
							alert("Update Gagal");
						}
						$("#txtRangeFrom"+idEdit).hide(100);
						$("#txtRangeTo"+idEdit).hide(100);
						$("#lblRangeFrom"+idEdit).show(100);
						$("#lblRangeTo"+idEdit).show(100);
						
						$(".btnEditGrade").val("Edit");
						$("#content_adm").load("Ms_grade_ctr");
					}
				});
				
				
			}
			
		});
	});
</script>
				

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>

<div class='header-popup' >
Master Grade
</div>

<div class="isi-popup" >
	<div class="table-responsive">
		<form action="" method="POST" name="frmGrade" id="frmGrade" >
			<table class="table table-hover table-bordered table-nonfluid table-striped" >
				<tr align='center' class='header-popup-tbl' style='background-color:blue;' >
					<td width='100' >Grade</td>
					<td width='100' >Range from</td>
					<td width='100' >Range to</td>
					<td width='100' >Edit</td>
				</tr>
				
				<?php
				foreach($data_grade->result() as $object) {
					echo "<tr align='center'>";
					echo "<td>".$object->grade."</td>";
					
					echo "<td>";
					echo "<label id='lblRangeFrom".$object->id."' >".$object->range_from."</label>";
					echo "<input type='number' class='form-control' value='".$object->range_from."' id='txtRangeFrom".$object->id."' style='display:none;' >";
					echo "</td>";
					
					echo "<td>";
					echo "<label id='lblRangeTo".$object->id."' >".$object->range_to."</label>";
					echo "<input type='number' class='form-control' value='".$object->range_to."' id='txtRangeTo".$object->id."' style='display:none;' >";
					echo "</td>";
					
					echo "<td><input type='button' value='Edit' class='btn btn-warning btn-sm btnEditGrade form-control' alt='".$object->id."' ></td>";
					echo "</tr>";
				}
				?>
				
				<tr><td colspan='4'></td></tr>
				
			</table>
		</form>
	</div>
	
</div>