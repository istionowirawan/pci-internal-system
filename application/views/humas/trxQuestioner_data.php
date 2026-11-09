<script>
	$(document).ready(function() {
		$("#filter_kriteria").focus();
		
		$(".btnHapus").click(function() {
			var q = confirm("Hapus Data ? \nJika data ini di hapus, maka data questioner untuk siswa ini pun akan ikut terhapus");
			if(q) {
				var trxid = "trxid=" + $(this).attr("alt");
				
				$.ajax({
					type: "POST",
					url: "Trx_questioner_ctr/hapus_questioner_trx/",
					data: trxid,
					success: function(data, status) {
						alert(status);
						$("#content_adm").load("Trx_questioner_ctr/tampil_data");
					}
				});
			}
		});
		
		$("#btnBack").click(function() {
			$("#content_adm").load("Trx_questioner_ctr/index");
		});
		
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
				$("#content_adm").load("Trx_questioner_ctr/tampil_data/"+$("#filter_kriteria").val()+"/"+$("#filter_cari").val());
			}
		});
		
		$("#frm_dataQuestioer").submit(function() {
			$("#filter_btn").click();
			return false;
		});
		
		$(".btnEdit").click(function() {
			$("#content_adm").load("Trx_questioner_ctr/edit_questioner_trx/"+$(this).attr("alt"));
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
Report Data Questioner
</div>

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		<button id='btnBack' class='btn btn-warning' >
			<span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> 
			Back
		</button>
		<br><br>
		
		<form action="" method="POST" name="frm_dataQuestioer" id="frm_dataQuestioer" class="form-inline" >
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
				<td>Tanggal Isi</td>
				<td>Fakultas</td>
				<td>Jurusan</td>
				<td>Asal Sekolah</td>
				<td>Peran Ortu</td>
				<td>Nama Ortu</td>
				<td>Nama Calon</td>
				<td>Tempat lahir</td>
				<td>Tanggal lahir</td>
				<td>Alamat</td>
				<td>Telepon</td>
				<td>HP</td>
				<td>HP Ortu</td>
				<td>Email</td>
				<td>Tanggal Input</td>
				<td title='Jika 1 berarti sudah diinput biodata siswa nya, jika 0 berarti belum diinput biodata siswa' >Take</td>
				<td>Action</td>
			</tr>
			
			
			<?php
			foreach($data_trxQuestioner->result() as $row) {
				echo "<tr>";
				echo "<td>".$row->tanggal_isi."</td>";
				echo "<td>".$row->fakultas."</td>";
				echo "<td>".$row->jurusan."</td>";
				echo "<td>".$row->asal_sekolah."</td>";
				echo "<td>".$row->peran_ortu."</td>";
				echo "<td>".$row->nama_ortu."</td>";
				echo "<td>".$row->nama_calon."</td>";
				echo "<td>".$row->tmp_lahir."</td>";
				echo "<td>".$row->tgl_lahir."</td>";
				echo "<td>".$row->alamat."</td>";
				echo "<td>".$row->telepon."</td>";
				echo "<td>".$row->hp."</td>";
				echo "<td>".$row->hp_ortu."</td>";
				echo "<td>".$row->email."</td>";
				echo "<td>".$row->date_input."</td>";
				echo "<td title='Jika 1, maka sudah di ambil untuk input data siswa, jika 0, maka belum diambil'>".$row->take."</td>";
				echo "<td><input type='button' value='Edit' class='btn btn-primary btnEdit btn-xs btn-block' alt='".$row->id."' title='Edit' >
				<input type='button' value='hapus' class='btn btn-danger btnHapus btn-xs' alt='".$row->id."' title='Hapus' ></td>";
				echo "</tr>";
			}
			?>
			
		</table>
		
		
		
	</div>
	
</div>


