<script>
	$(document).ready(function() {
		$("#txtFakultas").focus();
		
		$("#txtFakultas").change(function() {
			var idFakultas = "idFakultas=" + $(this).val();
			
			$.ajax({
				type: "POST",
				url: "Trx_questioner_ctr/cari_data_jurusan/",
				data: idFakultas,
				success: function(data, status) {
					var obj = jQuery.parseJSON( data );
									
					if(obj.length>0) {
						$("#txtJurusan").empty();
						$("#txtJurusan").append("<option value=''>== Pilih Jurusan ==</option>");
						for(var c=0; c < obj.length; c++) {
							$("#txtJurusan").append("<option value='"+obj[c].id+"'>"+obj[c].jurusan+"</option>");
						}
					}
				}
			});
			
			return false;
		});
		
		$("#frmQuestioer").submit(function() {
			var isiform = $(this).serialize();
			
			var prosesdo = $("#btnSubmit").val();
			
			if(prosesdo=="Simpan") {
				$.ajax({
					type: "POST",
					url: "Trx_questioner_ctr/simpan_trx_questioner/",
					data: isiform,
					success: function(data, status) {
						alert("Simpan Proses " + status);
						$("#content_adm").load("Trx_questioner_ctr");
					}
				});
			} else {
				$.ajax({
					type: "POST",
					url: "Trx_questioner_ctr/update_trx_questioner/",
					data: isiform,
					success: function(data, status) {
						alert("Update Proses " + status);
						$("#content_adm").load("Trx_questioner_ctr");
					}
				});
			}
			
			return false;
		});
		
		
		$("#btnSeeQuestioner").click(function() {
			$("#content_adm").load("Trx_questioner_ctr/tampil_data");
		});
		
		function show_warningbox_fakultas(data) {
			$("#txtInfoMsg").text(data);
			$("#txtInfoBox").slideDown(1000, function() {
				$("#txtInfoBox").fadeOut(5000);
			});
			if(data=="Simpan Berhasil") {
				$("#content_adm").load("Trx_questioner_ctr");
			}
		}
		
	});
</script>
				
<style>
#row_trx_questioner {
	cursor:pointer;
}
</style>

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>

<div class='header-popup' >
<?php
if(isset($data_trx_questioner)) {
	echo "Edit Data Questioner";
} else {
	echo "Input Data Questioner";
}
?>
</div>

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		<button id='btnSeeQuestioner' class='btn btn-danger' ><span class='glyphicon glyphicon-search' aria-hidden='true'></span> Tampil Questioner Input</button>
		<br><br>
		<form action="" method="POST" name="frmQuestioer" id="frmQuestioer" class="form-inline" >
			<table class="table table-hover" id='tblQuestioner' >
				
				<tr id='txtInfoBox' style='display:none;' >
					<td colspan='2' style='background-color:red;text-align:center;color:#ffffff;' >
						<span class="glyphicon glyphicon-warning-sign" aria-hidden="true" ></span> 
						<label id='txtInfoMsg' style='text-weight:bold;' >Warning</label>
					</td>
				</tr>
				
				<!--<tr>
					<td align='right' >Tanggal Input : </td>
					<td>-->
						<?php 
						//echo date("d F Y");
						?>
					<!--</td>
				</tr>-->
				
				<?php
				echo "<input type='hidden' name='txtIdTrxQuestioner' value='".(isset($data_trx_questioner)?$data_trx_questioner->row(0)->id:"")."' >";
				?>
				
				<tr>
					<td align='right' >Tanggal pengisian : </td>
					<td>
						<div class="form-group">
						<input type='number' name='txtTglIsi' id='txtTglIsi' class='form-control' placeholder='tanggal isi' min='1' max='31' value='<?php echo (isset($data_trx_questioner)?substr($data_trx_questioner->row(0)->tanggal_isi,8,2):date("d")) ?>' required >
						
						<select name='txtBlnIsi' id='txtBlnIsi' class='form-control' required >
							<option value='' >== Bulan Beli Formulir ==</option>
							<?php
							$arBulan[1] = "Januari";
							$arBulan[2] = "Febuari";
							$arBulan[3] = "Maret";
							$arBulan[4] = "April";
							$arBulan[5] = "Mei";
							$arBulan[6] = "Juni";
							$arBulan[7] = "July";
							$arBulan[8] = "Agustus";
							$arBulan[9] = "September";
							$arBulan[10] = "Oktober";
							$arBulan[11] = "November";
							$arBulan[12] = "Desember";

							for($a=1; $a<=12; $a++) {
								if(isset($data_trx_questioner)) {
									if($a == substr($data_trx_questioner->row(0)->tanggal_isi,5,2)) {
										echo "<option value='".$a."' selected >".$arBulan[$a]."</option>";
									} else {
										echo "<option value='".$a."' >".$arBulan[$a]."</option>";
									}
								} else {
									echo "<option value='".$a."' ".($a==date("m")?'selected':'')." >".$arBulan[$a]."</option>";
								}
							}
							?>
						</select>
						
						<input type='number' name='txtTahunIsi' id='txtTahunIsi' class='form-control' placeholder='tahun beli' min='2000' max='2030' maxlength='4' value='<?php echo (isset($data_trx_questioner)?substr($data_trx_questioner->row(0)->tanggal_isi,0,4):date("Y")) ?>' required >
						</div>
					</td>
				</tr>
				
				<tr>
					<td align='right' width='15%' >Fakultas : </td>
					<td>
						<select name='txtFakultas' id='txtFakultas' class='form-control' style='width:100%;' required >
						<option value='' >== Pilih Fakultas ==</option>
						<?php
						foreach ($data_fakultas->result() as $obj) {
							if(isset($data_trx_questioner)) {
								if($data_trx_questioner->row(0)->fakultas_id==$obj->id) {
									echo "<option selected value='".$obj->id."' >".$obj->fakultas."</option>";
								} else {
									echo "<option value='".$obj->id."' >".$obj->fakultas."</option>";
								}
							} else {
								echo "<option value='".$obj->id."' >".$obj->fakultas."</option>";
							}
						}
						?>
						</select>
					</td>
				</tr>
				
				
				<tr>
					<td align='right' >Jurusan : </td>
					<td>
						<select name='txtJurusan' id='txtJurusan' class='form-control' style='width:100%;' required >
						<option value='' >== Pilih Jurusan ==</option>
						<?php
						if(isset($data_jurusan)) {
							foreach($data_jurusan->result() as $jur) {
								if($data_trx_questioner->row(0)->jurusan_id==$jur->id) {
									echo "<option selected value='".$jur->id."' >".$jur->jurusan."</option>";
								} else {
									echo "<option value='".$jur->id."' >".$jur->jurusan."</option>";
								}
							}
						}
						?>
						</select>
					</td>
				</tr>
				
				
				
				<tr>
					<td align='right' >Nama Calon Mahasiswa</td>
					<td>
					<?php
					if(isset($data_trx_questioner)) {
						echo "<input type='text' name='txtCalonMahasiswa' id='txtCalonMahasiswa' class='form-control' style='width:100%;' value='".$data_trx_questioner->row(0)->nama_calon."' required >";
					} else {
						echo "<input type='text' name='txtCalonMahasiswa' id='txtCalonMahasiswa' class='form-control' style='width:100%;' required >";
					}
					?>
					</td>
				</tr>
				
				
				<tr>
					<td align='right' >Tempat & Tgl Lahir</td>
					<td>
						<div class="form-group">
						<input type='text' name='txtTempatLahir' id='txtTempatLahir' class='form-control' placeholder='Tempat lahir' value='<?php echo (isset($data_trx_questioner)?$data_trx_questioner->row(0)->tmp_lahir:""); ?>' required >
						<input type='number' name='txtTglLahir' id='txtTglLahir' class='form-control' placeholder='tanggal' min='1' max='31' value='<?php echo (isset($data_trx_questioner)?substr($data_trx_questioner->row(0)->tgl_lahir,8,2):""); ?>' required >
						
						
						<select name='txtBlnLahir' id='txtBlnLahir' class='form-control' required >
							<option value='' >== Bulan ==</option>
							<?php
							$arBulan[1] = "Januari";
							$arBulan[2] = "Febuari";
							$arBulan[3] = "Maret";
							$arBulan[4] = "April";
							$arBulan[5] = "Mei";
							$arBulan[6] = "Juni";
							$arBulan[7] = "July";
							$arBulan[8] = "Agustus";
							$arBulan[9] = "September";
							$arBulan[10] = "Oktober";
							$arBulan[11] = "November";
							$arBulan[12] = "Desember";
							
							for($a=1; $a<=12; $a++) {
								if(isset($data_trx_questioner)) {
									if($a == substr($data_trx_questioner->row(0)->tgl_lahir,5,2)) {
										echo "<option value='".$a."' selected >".$arBulan[$a]."</option>";
									} else {
										echo "<option value='".$a."' >".$arBulan[$a]."</option>";
									}
								} else {
									echo "<option value='".$a."' >".$arBulan[$a]."</option>";
								}
							}
							?>
						</select>
						
						<input type='number' name='txtTahunLahir' id='txtTahunLahir' class='form-control' placeholder='tahun' maxlength='4' value='<?php echo (isset($data_trx_questioner)?substr($data_trx_questioner->row(0)->tgl_lahir,0,4):""); ?>' required >
						</div>
					</td>
				</tr>
				
				
				<tr>
					<td align='right' >Alamat</td>
					<td><textarea name='txtAlamat' id='txtAlamat' class='form-control' style='width:100%;' required ><?php echo (isset($data_trx_questioner)?$data_trx_questioner->row(0)->alamat:""); ?></textarea></td>
				</tr>
				
				<tr>
					<td align='right' >Telepon</td>
					<td><input type='text' name='txtTelepon' id='txtTelepon' class='form-control' style='width:100%;' value='<?php echo (isset($data_trx_questioner)?$data_trx_questioner->row(0)->telepon:""); ?>' ></td>
				</tr>
					
				<tr>
					<td align='right' >HP / SMS</td>
					<td><input type='text' name='txtHP' id='txtHP' class='form-control'  style='width:100%;' required value='<?php echo (isset($data_trx_questioner)?$data_trx_questioner->row(0)->hp:""); ?>' ></td>
				</tr>
				
				<tr>
					<td align='right' >Email</td>
					<td><input type='email' name='txtEmail' id='txtEmail' class='form-control'  style='width:100%;' value='<?php echo (isset($data_trx_questioner)?$data_trx_questioner->row(0)->email:""); ?>' ></td>
				</tr>
				
				<tr>
					<td align='right' >Asal Sekolah : </td>
					<td>
						<select name='txtAsalSekolah' id='txtAsalSekolah' class='form-control' style='width:100%;' required >
						<option value='' >== Pilih Sekolah Asal ==</option>
						<?php
						if(isset($data_trx_questioner)) {
							if($data_trx_questioner->row(0)->asal_sekolah=='Dalam') {
								echo "<option value='Dalam' selected >Dalam (PAHOA)</option>";
							} else {
								echo "<option value='Dalam' >Dalam (PAHOA)</option>";
							}
							if($data_trx_questioner->row(0)->asal_sekolah=='Luar') {
								echo "<option value='Luar' selected >Luar (NON-PAHOA)</option>";
							} else {
								echo "<option value='Luar' >Luar (NON-PAHOA)</option>";
							}
						} else {
							echo "<option value='Dalam' >Dalam (PAHOA)</option>
							<option value='Luar' >Luar (NON-PAHOA)</option>";
						}
						?>
						
						</select>
					</td>
				</tr>
				
				<tr>
					<td align='right' >Nama Ayah / Ibu / Wali</td>
					<td>
						<select name='txtPeranOrtu' id='txtPeranOrtu' class='form-control' style='width:24%;' required >
						<option value='' >== Pilih Peran Ortu ==</option>
						<?php
						if(isset($data_trx_questioner)) {
							if($data_trx_questioner->row(0)->peran_ortu=='Ayah') {
								echo "<option selected value='Ayah' >Ayah</option>";
							} else {
								echo "<option value='Ayah' >Ayah</option>";
							}
							if($data_trx_questioner->row(0)->peran_ortu=='Ibu') {
								echo "<option selected value='Ibu' >Ibu</option>";
							} else {
								echo "<option value='Ibu' >Ibu</option>";
							}
							if($data_trx_questioner->row(0)->peran_ortu=='Wali') {
								echo "<option selected value='Wali' >Wali</option>";
							} else {
								echo "<option value='Wali' >Wali</option>";
							}
						} else {
							echo "
							<option value='Ayah' >Ayah</option>
							<option value='Ibu' >Ibu</option>
							<option value='Wali' >Wali</option>";
						}
						?>
						</select>
						
						<?php
						if(isset($data_trx_questioner)) {
							echo "<input type='text' name='txtNamaOrtu' id='txtNamaOrtu' class='form-control' style='width:75%;' value='".$data_trx_questioner->row(0)->nama_ortu."' required >";
						} else {
							echo "<input type='text' name='txtNamaOrtu' id='txtNamaOrtu' class='form-control' style='width:75%;' required >";
						}
						?>
					</td>
				</tr>
				
				
				<tr>
					<td align='right' >No. HP Ayah/Ibu/Wali</td>
					<td><input type='text' name='txtHPOrtu' id='txtHPOrtu' class='form-control'  style='width:100%;' required value='<?php echo (isset($data_trx_questioner)?$data_trx_questioner->row(0)->hp_ortu:""); ?>' ></td>
				</tr>
				
				
				<tr  ><td colspan='2'>
					<table class="table" >
						<tr align='center' style='background-color:#FFAF54;color:#ffffff;' id='row_trx_questioner' >
							<td colspan='2' >
								<font size='5'>Questioner </font><br> 
								.:: <?php echo $data_questioner->row(0)->judul; ?> ::. 
								<input type='hidden' name='txtId_soal' id='txtId_soal' value='<?php echo $data_questioner->row(0)->id; ?>' >
								<input type='hidden' name='txtByksoal' id='txtByksoal' value='<?php echo $data_questioner->num_rows(); ?>' >
							</td>
						</tr>
						
						<?php
						$nomor = 1;
						foreach($data_questioner->result() as $row) {
							echo "<tr >
								<td colspan='2' title='".$row->issue_id."' >". $nomor . ". " . $row->issue."</td>
								<input type='hidden' name='txtIssueId[]' value='".$row->issue_id."' >
							</tr>";
							
							$q2="SELECT * FROM ms_questioner_answer WHERE issue_id='".$row->issue_id."' ORDER BY answer_no ASC";
							$query2 = $this->db->query($q2);
							foreach($query2->result() as $ans) {
								echo "<tr >";
									$nama_pil = "txtAns_".$row->issue_id;
									if($row->tipe=='Optional') {
										echo "<td style='width:50px;text-align:right;'>";
										if(isset($data_answer_questioner)) {
											$sama = false;
											foreach($data_answer_questioner->result() as $dt) {
												if($dt->issue_id==$row->issue_id) {
													if(str_replace(";","",$dt->answer)==$ans->id) {
														echo "<input type='radio' name='".$nama_pil."[]' id='".$nama_pil."' title='".$nama_pil."' value='".$ans->id."' checked >";
														$sama = true;
														break;
													}
												}
											}
											if($sama == false) {
												echo "<input type='radio' name='".$nama_pil."[]' id='".$nama_pil."' title='".$nama_pil."' value='".$ans->id."' >";
											}
										} else {
											echo "<input type='radio' name='".$nama_pil."[]' id='".$nama_pil."' title='".$nama_pil."' value='".$ans->id."' >";
										}
										echo "</td>";
									} else {
										echo "<td style='width:50px;text-align:right;'>";
										
										if(isset($data_answer_questioner)) {
											$same = false;
											foreach($data_answer_questioner->result() as $dt) {
												if($dt->issue_id==$row->issue_id) {
													
													$arAnswer = explode(";",$dt->answer);
													for($q=0; $q<count($arAnswer)-1; $q++) {
														if($arAnswer[$q]==$ans->id) {
															echo "<input type='checkbox' name='".$nama_pil."[]' id='".$nama_pil."' title='".$nama_pil."' value='".$ans->id."' checked >";
															$same = true;
															break;
														}
													}
													
												}
											}
											if($same == false) {
												echo "<input type='checkbox' name='".$nama_pil."[]' id='".$nama_pil."' title='".$nama_pil."' value='".$ans->id."' >";
											}
										} else {
											echo "<input type='checkbox' name='".$nama_pil."[]' id='".$nama_pil."' title='".$nama_pil."' value='".$ans->id."' >";
										}
										
										
										echo "</td>";
									}
									echo "<td>". $ans->answer_detail."</td>";
								echo "</tr>";
							}
							
							$nomor++;
						}
						?>
						
					</table>
				</td></tr>
				
				<tr>
					<td colspan='2'>
						<?php
						if(isset($data_trx_questioner)) {
							echo "<input type='submit' id='btnSubmit' class='btn btn-warning btn-block' value='Update' >";
						} else {
							echo "<input type='submit' id='btnSubmit' class='btn btn-primary btn-block' value='Simpan' >";
						}
						?>
					</td>
				</tr>
				
			</table>
		</form>
		
		
	</div>
	
</div>