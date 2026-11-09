<script>
	$(document).ready(function() {
		$("#filter_kriteria").focus();
		
		$("#filter_kriteria").change(function() {
			if($(this).val()=="all") {
				$("#filter_cari").hide(1000);
			} else {
				$("#filter_cari").show(500, function() {
					$("#filter_cari").focus();
				});
			}
		});
		
		$("#filter_btn").click(function() {
			var do_search = true;
			
			if($("#filter_kriteria").val()!="all") {
				if($("#filter_cari").val()=="") {
					alert("Mohon masukkan kata yang dicari dahulu");
					$("#filter_cari").focus();
					do_search=false;
				}
			}
			
			if(do_search==true) {
				$("#modal_content_body").load("Trx_biodata_ctr/tampil_data/"+$("#filter_kriteria").val()+"/"+$("#filter_cari").val());
			}
		});
		
		$("#frm_dataFormulir").submit(function() {
			$("#filter_btn").click();
			return false;
		});
		
		$(".btnAmbil").click(function() {
			$("#btnCloseModal").click();
			var formid = $(this).attr("alt");
			$("#content_adm").load("Trx_biodata_ctr/edit_formulir_trx/"+formid, function() {
				$("#btnAmbilData").hide(200,function() {
					$("#btnCancelInput").show(300);
				});
				$("#tblBioHeader").show(500,function() {
					$("#frmBioSiswa").slideDown(500,function() {
						$("#txtNamaKeluarga").focus();
					});
				});
				
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

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		
		<form action="" method="POST" name="frm_dataFormulir" id="frm_dataFormulir" class="form-inline" >
			<div class="form-group">
			Filter : 
			<select id='filter_kriteria' class='form-control input-sm' >
			<option value='all' >All</option>
			<option value='fakultas' >fakultas</option>
			<option value='jurusan' >jurusan</option>
			<?php
			foreach($header_trx->result() as $head) {
				if($head->Field!="id" && $head->Field!="fakultas_id" && $head->Field!="jurusan_id") {
					echo "<option value='".$head->Field."' >".$head->Field."</option>";
				}
			}
			?>
			</select>
			<input type='text' id='filter_cari' placeholder='Masukkan kata pencarian' class='form-control input-sm' style='display:none;' >
			<input type='button' id='filter_btn' value='filter' class='btn btn-info btn-sm' ><br><br>
			</div>
		</form>
		
		<table class="table table-hover table-bordered" id='tbl_dataQuestioner' >
			
			<tr id='txtInfoBox' style='display:none;' >
				<td colspan='2' style='background-color:red;text-align:center;color:#ffffff;' >
					<span class="glyphicon glyphicon-warning-sign" aria-hidden="true" ></span> 
					<label id='txtInfoMsg' style='text-weight:bold;' >Warning</label>
				</td>
			</tr>
			
			
			<tr align='center' style='background-color:#7C88AF;color:#ffffff;' >
				<td>Ambil</td>
				<?php
				echo "<td>fakultas</td>
				<td>jurusan</td>";
				foreach($header_trx->result() as $head) {
					if($head->Field!="id" && $head->Field!="fakultas_id" && $head->Field!="jurusan_id" && $head->Field!="user_input" && $head->Field!="date_input" && $head->Field!="questioner_id") {
						echo "<td>".$head->Field."</td>";
					}
				}
				?>
			</tr>
			
			
			<?php
			foreach($data_trxFormulir->result() as $row) {
				echo "<tr>";
				echo "<td><input type='button' value='Ambil' class='btn btn-primary btn-xs btnAmbil' alt='".$row->id."' title='Ambil data ".$row->nama_calon."' ></td>";
				echo "<td>".$row->fakultas."</td>";
				echo "<td>".$row->jurusan."</td>";
				echo "<td>".$row->tanggal_beli."</td>";
				echo "<td>".$row->no_formulir."</td>";
				echo "<td>".$row->asal_sekolah."</td>";
				echo "<td>".$row->peran_ortu."</td>";
				echo "<td>".$row->nama_ortu."</td>";
				echo "<td>".$row->nama_calon."</td>";
				echo "<td>".$row->tmp_lahir."</td>";
				echo "<td>".$row->tgl_lahir."</td>";
				echo "<td>".$row->alamat."</td>";
				echo "<td>".$row->telepon."</td>";
				echo "<td>".$row->hp."</td>";
				echo "<td>".$row->email."</td>";
				echo "<td>".$row->tahun_ajaran."</td>";
				echo "</tr>";
			}
			?>
			
		</table>
		
		
		
	</div>
	
</div>


