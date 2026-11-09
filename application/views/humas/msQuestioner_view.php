<script>
	$(document).ready(function() {
		$("#btnAddNew").click(function() {
			$("#frmQuestioer").slideToggle(500, function() {
				$("#txtJudulPertanyaan").focus();
			});
		});
		
		$("#frmQuestioer").submit(function() {
			var dataform = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "Ms_questioner_ctr/simpan_questioner_judul/",
				data: dataform,
				success: function(data, status) {
					$("#content_adm").load("Ms_questioner_ctr");
				}
			});
			
			return false;
		});
		
		$(".btnActiveJudul").click(function() {
			var idJudul = "idjudul=" + $(this).attr("alt");
			
			$.ajax({
				type: "POST",
				url: "Ms_questioner_ctr/update_judul_active/",
				data: idJudul,
				success: function(data, status) {
					$("#content_adm").load("Ms_questioner_ctr");
				}
			});
			
			return false;
		});
		
		
		$(".btnEditQuest").click(function() {
			var idJudul = $(this).attr("alt");
			$("#content_adm").load("Ms_questioner_ctr/modify_questioner/"+idJudul);
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
Master Questioner
</div>

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		<button id='btnAddNew' class='btn btn-danger' ><span class='glyphicon glyphicon-plus' aria-hidden='true'></span> Add new Question</button>
		<br><br>
		<form action="" method="POST" name="frmQuestioer" id="frmQuestioer" style='display:none;' >
			<table class="table table-hover" id='tblQuestioner' >
				
				<tr id='txtInfoBox' style='display:none;' >
					<td colspan='2' style='background-color:red;text-align:center;color:#ffffff;' >
						<span class="glyphicon glyphicon-warning-sign" aria-hidden="true" ></span> 
						<label id='txtInfoMsg' style='text-weight:bold;' >Warning</label>
					</td>
				</tr>
				
				<tr>
					<td align='right' width='15%' >Judul Questioner : </td>
					<td><input type='text' class='form-control' name='txtJudulPertanyaan' id='txtJudulPertanyaan' required ></td>
				</tr>
				
				<tr>
					<td align='right' ></td>
					<td><input type='submit' class='btn btn-primary btn-block' value='Simpan Judul Questioner' ></td>
				</tr>
				
			</table>
		</form>
		
		
		
		<div id='list_judul_questioner' class="table-responsive" >
			<form action="" method="POST" name="frmListQuestioner" id="frmListQuestioner" >
				<table class="table table-bordered" >
					<tr align='center' class='header-popup-tbl' >
						<td width='5%'>Kode Questioner</td>
						<td>Judul Questioner</td>
						<td width='10%'>Stat Active</td>
						<td width='10%'>Edit</td>
					</tr>
					
					<?php
					
					foreach($judul_questioner->result() as $obj) {
						echo "<tr align='center'>";
						echo "<td>".$obj->id."</td>";
						echo "<td>".$obj->judul."</td>";
						echo "<td>";
							if($obj->aktif==0) {
								echo "<input type='button' value='Non-active' class='btn btn-danger btn-block btnActiveJudul' alt='".$obj->id."' title='click me to activate this question' >";
							} else {
								echo "<input type='button' value='active' class='btn btn-success btn-block' alt='".$obj->id."' title='You cannot click me, coz i am already active' >";
							}
						echo "</td>";
						echo "<td><input type='button' value='Edit' class='btn btn-primary btn-block btnEditQuest' alt='".$obj->id."' namajudul='".$obj->judul."'  ></td>";
						echo "</tr>";
					}
					?>
				</table>
			</form>
		</div>
		
	</div>
	
</div>