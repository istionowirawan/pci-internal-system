<script>
	$(document).ready(function() {
		var noUrutAnswer=1;
		create_new_answer();
		$("#txtIssue").focus();
		
		$("#btnAddAnswer").click(function() {
			create_new_answer();
		});
		$("#btnRemoveAnswer").click(function() {
			remove_answer();
		});
		
		$("#btnBack").click(function() {
			$("#content_adm").load("Ms_questioner_ctr");
		});
		
		function create_new_answer() {
			var addAnswerText = "";
			
			addAnswerText += "<tr id='row_answer"+noUrutAnswer+"' >";
			addAnswerText += "<td width='5%' align='right' style='vertical-align:middle;' ><label>"+noUrutAnswer+". </label>";
			addAnswerText += "<input type='hidden' name='txtIssueNo[]' id='txtIssueNo"+noUrutAnswer+"' value='"+noUrutAnswer+"'></td>";
			addAnswerText += "<td><input type='text' class='form-control' name='txtIssueAnswer[]' id='txtIssueAnswer"+noUrutAnswer+"' required ></td>";
			addAnswerText += "</tr>";
			
			$("#col_issue_answer").append(addAnswerText);
			noUrutAnswer++;
		}
		
		function remove_answer() {
			if(noUrutAnswer>2) {
				noUrutAnswer--;
				$("#row_answer"+noUrutAnswer).remove();
			}
		}
		
		
		$("#frmQuestioerIssue").submit(function() {
			var dataform = $(this).serialize();
			
			$.ajax({
				type: "POST",
				url: "Ms_questioner_ctr/simpan_issue_questioner/",
				data: dataform,
				success: function(data, status) {
					//alert(data);
					$("#content_adm").load("Ms_questioner_ctr/modify_questioner/"+$("#txtQuestId").val());
				}
			});
			
			return false;
		});
		
		
		$(".btnDelete").click(function() {
			var idissue = "idissue=" + $(this).attr('alt');
			
			if (confirm('Delete this data?')) {
				$.ajax({
					type: "POST",
					url: "Ms_questioner_ctr/hapus_issue_questioner/",
					data: idissue,
					success: function(data, status) {
						//alert(data);
						$("#content_adm").load("Ms_questioner_ctr/modify_questioner/"+$("#txtQuestId").val());
					}
				});
			}
		});
		
		
		$(".btnDetail").click(function() {
			var idissue = "idissue=" + $(this).attr('alt');
			
			$.ajax({
				type: "POST",
				url: "Ms_questioner_ctr/show_detail_question/",
				data: idissue,
				success: function(data, status) {
					$("#btnDetailIssue").click();
					
					var obj = jQuery.parseJSON( data );
					
					if(obj.length>0) {
						var isiJawaban="";
							
						for(var c=0; c < obj.length; c++) {
							$("#row_id_issue").html(obj[c].id);
							$("#row_question_issue").html(obj[c].issue);
							$("#row_type_issue").html(obj[c].tipe);
							
							if(obj[c].tipe=="Optional") {
								isiJawaban += "<input type='radio' name='txtPreviewOptional' > ";
								isiJawaban += obj[c].answer_detail + "<br>";	
							} else {
								isiJawaban += "<input type='checkbox' name='txtPreviewCheckbox' > ";
								isiJawaban += obj[c].answer_detail + "<br>";
							}
							
							
							$("#row_answer_issue").html(isiJawaban);
						}
					}
			
					
				}
			});
			
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
Add Questioner Issue  
</div>

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		<button id='btnBack' class='btn btn-danger' ><span class='glyphicon glyphicon-circle-arrow-left' aria-hidden='true'></span> Back</button>
		<br><br>
		<form action="" method="POST" name="frmQuestioerIssue" id="frmQuestioerIssue" >
			<table class="table" id='tblQuestioner' >
				
				<tr id='txtInfoBox' style='display:none;' >
					<td colspan='2' style='background-color:red;text-align:center;color:#ffffff;' >
						<span class="glyphicon glyphicon-warning-sign" aria-hidden="true" ></span> 
						<label id='txtInfoMsg' style='text-weight:bold;' >Warning</label>
					</td>
				</tr>
				
				<tr>
					<td align='right' width='15%' >Questioner Id : </td>
					<td>
						<input type='hidden' class='form-control' name='txtQuestId' id='txtQuestId' required value='<?php echo $data_header->row(0)->id; ?>' >
						<?php echo $data_header->row(0)->id; ?>
					</td>
				</tr>
				
				<tr>
					<td align='right' >Questioner Judul : </td>
					<td>
						<input type='hidden' class='form-control' name='txtQuestJudul' id='txtQuestJudul' required value='<?php echo $data_header->row(0)->judul; ?>' >
						<?php echo $data_header->row(0)->judul; ?>
					</td>
				</tr>
				
				<tr>
					<td align='right' >Issue : </td>
					<td><textarea class='form-control' name='txtIssue' id='txtIssue' required ></textarea></td>
				</tr>
				
				<tr>
					<td align='right' >Issue Type : </td>
					<td>
						<input type='radio' name='txtType' id='txtType' value='Optional' title='hanya boleh pilih salah satu' > Optional <br> 
						<input type='radio' name='txtType' id='txtType' value='Checking' title='boleh pilih lebih dari satu' > Checking
					</td>
				</tr>
				
				<tr>
					<td align='right' >Issue Answer : </td>
					<td>
						<table class='table' >
							<tbody id='col_issue_answer' ></tbody>
							
							<tr>
							<td colspan='2'>
								<input type='button' class='btn btn-primary' id='btnAddAnswer' value='add answer' >
								<input type='button' class='btn btn-danger' id='btnRemoveAnswer' value='remove answer' >
							</td>
							</tr>
						</table>
					</td>
				</tr>
				
				
				<tr>
					<td align='right' ></td>
					<td><input type='submit' class='btn btn-primary btn-block' value='Simpan Issue' ></td>
				</tr>
				
			</table>
		</form>
		
		
		
		<div id='list_judul_Issue' class="table-responsive" >
			<form action="" method="POST" name="frmListIssue" id="frmListIssue" >
				<table class="table table-bordered" >
					<tr align='center' class='header-popup-tbl' >
						<td width='5%'>Issue No</td>
						<td>Issue question</td>
						<td width='10%'>Issue type</td>
						<td width='10%'>Total Answer</td>
						<td width='10%'>Preview Detail</td>
						<td width='10%'>Delete</td>
					</tr>
					
					<?php
					$noissue=1;
					if($data_issue->num_rows()>0) {
						foreach($data_issue->result() as $obj) {
							if($obj->id!=NULL) {
								echo "<tr align='center'>";
								echo "<td>".$noissue."</td>";
								echo "<td>".$obj->issue."</td>";
								echo "<td>".$obj->tipe."</td>";
								echo "<td>".$obj->jml_answer."</td>";
								echo "<td><input type='button' value='Preview' class='btn btn-primary btn-block btnDetail' alt='".$obj->id."' ></td>";
								echo "<td><input type='button' value='delete' class='btn btn-danger btn-block btnDelete' alt='".$obj->id."' ></td>";
								echo "</tr>";
								$noissue++;
							}
						}
					}
					?>
				</table>
			</form>
		</div>
		
	</div>
	
	
	<!-- Modal -->
	<button id='btnDetailIssue' type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#detail_issue" style='display:none;' >
	Launch demo modal
	</button>

	<div class="modal fade" id="detail_issue" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  
		  <div class="modal-header" style='background-color:blue;color:#ffffff;' >
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">Detail Issue</h4>
		  </div>
		  
		  <div class="modal-body">
			<table class='table' >
				<tr>
				<td align='right' >Issue Id : </td>
				<td align='left' id='row_id_issue'></td>
				</tr>
				
				<tr>
				<td align='right'>Issue Question : </td>
				<td align='left' id='row_question_issue'></td>
				</tr>
				
				<tr>
				<td align='right'>Issue Type : </td>
				<td align='left' id='row_type_issue'></td>
				</tr>
				
				<tr>
				<td align='right'>Answer : </td>
				<td align='left' id='row_answer_issue'></td>
				</tr>
				
			</table>
		  </div>
		  
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>

</div>
