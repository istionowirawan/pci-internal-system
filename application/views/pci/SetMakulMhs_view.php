<script>
	var arNamaMahasiswa = new Array();
		
	$(document).ready(function() {
		$("#btnAddNew").click(function() {
			$(".divLoading").hide();
			$("#frmSetMakulMhs").slideToggle(500, function() {
				$("#txtNimMahasiswa").focus();
			});
		});
		
		
		$(".btnMapelPilih").click(function() {
			if($(this).css("background-color")=="rgb(255, 255, 255)") {
				$(this).css("background-color", "#FFF466");
			} else {
				$(this).css("background-color", "#FFFFFF");
			}
			hitung_jml_sks();
		});
		
		
		function hitung_jml_sks() {
			var jmlSksPilih = 0;
			$( ".chxClsMapel" ).each(function() {
				if ($(this).prop("checked")==true) {
					jmlSksPilih+= Number($(this).attr("jmlsks")) ;
				}
			});
			$("#txtJmlSks").text(jmlSksPilih);
		}
		
		
		// ++++++++++++++++++++++++++++++++++ AUTOCOMPLETE +++++++++++++++++++++++++++++++++++++++++++++
		$( "#txtNimMahasiswa" ).autocomplete({
			source: arNamaMahasiswa,
			select: function ( event, ui ) {
				event.preventDefault();
				cari_detail_mhs(ui.item.label,ui.item.value);
			},
			focus: function( event, ui ) {
				var str = ui.item.label;
				var posNama = str.indexOf(") ")+2;
				var namaMhs = str.substring(posNama,100);
				$("#txtNimMahasiswa").val(ui.item.value);
				$("#txtNamaMahasiswa").val(namaMhs);
			},
			change: function ( event, ui ) {
				var str = ui.item.label;
				var posNama = str.indexOf(") ")+2;
				var namaMhs = str.substring(posNama,100);
				$("#txtNamaMahasiswa").val(namaMhs);
			}
		});
		
		
		function cari_detail_mhs(isiLabel,isiValue) {
			var str = isiLabel;
			var posNama = str.indexOf(") ")+2;
			var namaMhs = str.substring(posNama,100);
			$("#txtNimMahasiswa").val(isiValue);
			$("#txtNamaMahasiswa").val(namaMhs);
			
			var nimmhs = "nimmhs="+$("#txtNimMahasiswa").val();
			var fakid=0;
			var jurid=0;
			$.ajax({
				type: "POST",
				url: "Set_makul_mhs_ctr/cari_fakjur/",
				data: nimmhs,
				success: function(data, status) {
					//alert(data);
					var obj = jQuery.parseJSON( data );
					if(obj.length>0) {
						for(var c=0; c < obj.length; c++) {
							$("#txtFakultasMhs").val(obj[c].fakultas);
							$("#txtJurusanMhs").val(obj[c].jurusan);
							fak_id=obj[c].fak_id;
							jur_id=obj[c].jur_id;
						}
						
						
						//Cari Makul Mahasiswa Tertentu sesuai dengan jurusan diambil
						var makul_fak_jur = "id_fak="+fak_id;
						makul_fak_jur += "&id_jur="+jur_id;
						$.ajax({
							type: "POST",
							url: "Set_makul_mhs_ctr/cari_makul_fak_jur/",
							data: makul_fak_jur,
							success: function(data, status) {
								//alert(data);
								var obj = jQuery.parseJSON( data );
								if(obj.length>0) {
									var rowMapel = "";
										
									for(var d=0; d < obj.length; d++) {
										rowMapel += "<tr align='center' id='rowIdMakul"+obj[d].id+"' style='cursor:pointer;' alt='"+obj[d].id+"' class='rowMakul' >";
										rowMapel += "<td>"+obj[d].semester+"</td>";
										rowMapel += "<td>"+obj[d].kode_makul+"</td>";
										rowMapel += "<td>"+obj[d].nmkmk+"</td>";
										rowMapel += "<td>"+obj[d].kode_kelas+"</td>";
										rowMapel += "<td>"+obj[d].sks+"</td>";
										rowMapel += "<td>"+obj[d].nama_dosen+"</td>";
										rowMapel += "<td>"+obj[d].nmkls+"</td>";
										rowMapel += "<td style='display:none;' ><input type='checkbox' name='chxMapel[]' id='chxMapel"+obj[d].id+"' alt='"+obj[d].id+"' jmlsks='"+obj[d].sks+"' class='form-control chxClsMapel' value='"+obj[d].id+"' ></td>";
										rowMapel += "</tr>";
									}
									$("#tbl_makul_mhs").html(rowMapel);
									
									$(".tr_detail_makul").show(500);
								}
								
								$(".rowMakul").click(function() {
									var idmakul = $(this).attr("alt");
									if(!$("#chxMapel"+idmakul).prop("checked")) {
										$("#chxMapel"+idmakul).prop("checked",true);
										$("#rowIdMakul"+idmakul).css("background-color","#FFF466");
									} else {
										$("#chxMapel"+idmakul).prop("checked",false);
										$("#rowIdMakul"+idmakul).css("background-color","#FFFFFF");
									}
									hitung_jml_sks();
								});
							}
						});
						
					}
				}
			});
			
			var r = $.Deferred();

			setTimeout(function () {
				r.resolve();
			}, 1500);

			return r;
		}
		
		
		$("#frmSetMakulMhs").submit(function() {
			var dataform = $(this).serialize();
			
			if($("#btnSimpan").val()=="Simpan") {
				
				$.ajax({
					type: "POST",
					url: "Set_makul_mhs_ctr/simpan_makul_mhs/",
					data: dataform,
					success: function(data, status) {
						//alert(data);
						if(data==1) {
							alert("Simpan Makul Mahasiswa Sukses!")
						} else if(data==2) {
							alert("Simpan Gagal!\nMahasiswa ini sudah pernah diinput sebelumnya!\nSilahkan cek data dibawah!");
						} else {
							alert("Simpan Makul Mahasiswa Gagal!")
						}
						$("#content_adm").load("Set_makul_mhs_ctr");
					}
				});
				
			} else {
				//Update data
				
				$.ajax({
					type: "POST",
					url: "Set_makul_mhs_ctr/update_makul_mhs/",
					data: dataform,
					success: function(data, status) {
						alert(data);
						if(data==1) {
							alert("Update Makul Mahasiswa Sukses!")
						} else {
							alert("Update Makul Mahasiswa Gagal!")
						}
						$("#content_adm").load("Set_makul_mhs_ctr");
					}
				});
			}
			return false;
		});
		
		
		$(".btnDetailMakulMhs").click(function() {
			var dataform = "idtrx=" + $(this).attr("alt");
			$("#btnModal").click();
			
			$.ajax({
				type: "POST",
				url: "Set_makul_mhs_ctr/load_makul_mhs/",
				data: dataform,
				success: function(data, status) {
					
					var obj = jQuery.parseJSON( data );
					if(obj.length>0) {
						var isiMakulMhs = "";
						
						isiMakulMhs += "<table class='table table-bordered' >";
						isiMakulMhs += "<tr style='background-color:#6071C6;color:#ffffff;' align='center' >";
						isiMakulMhs += "<td>Kode Matakuliah</td>";
						isiMakulMhs += "<td>Nama Matakuliah</td>";
						isiMakulMhs += "<td>Kelas</td>";
						isiMakulMhs += "<td>SKS</td>";
						isiMakulMhs += "</tr>";
						
						var totSKS = 0;
						for(var c=0; c < obj.length; c++) {
							isiMakulMhs += "<tr align='center' >";
							isiMakulMhs += "<td>"+obj[c].kdmk+"</td>";
							isiMakulMhs += "<td>"+obj[c].nmkmk+"</td>";
							isiMakulMhs += "<td>"+obj[c].kode_kelas +" ("+obj[c].nmkls+")"+"</td>";
							isiMakulMhs += "<td>"+obj[c].sks+"</td>";
							isiMakulMhs += "</tr>";
							totSKS+=Number(obj[c].sks);
						}
						isiMakulMhs += "<tr align='center' style='background-color:#C9302C;color:#ffffff;' >";
						isiMakulMhs += "<td colspan='3' >Total SKS</td>";
						isiMakulMhs += "<td>"+totSKS+"</td>";
						isiMakulMhs += "</tr>";
						
						isiMakulMhs += "</table>";
						
						$(".modal-body").html(isiMakulMhs);
					}
						
				}
			});
			
			return false;
		});
		
		
		
		$(".btnEditMakulMhs").click(function() {
			var dataform = "idtrx=" + $(this).attr("alt");
			
			$.ajax({
				type: "POST",
				url: "Set_makul_mhs_ctr/edit_makul_mhs/",
				data: dataform,
				success: function(data, status) {
					var obj = jQuery.parseJSON( data );
					if(obj.length>0) {
						//bersihkan seleksi pelajaran
						$(".chxClsMapel").prop("checked",false);
						$(".rowMakul").css("background-color","#FFFFFF");
						
						$("#frmSetMakulMhs").show('500');
						//$(".tr_detail_makul").show(500);
						$("#txtNimMahasiswa").attr("readonly","readonly");
						$("#btnSimpan").val("Update");
						
						$("#txtNimMahasiswa").val(obj[0].nim_mhs);
						$("#txtNamaMahasiswa").val(obj[0].nama_depan);
						
						$("#tbl_makul_mhs").hide();
						$(".divLoading").show();
						cari_detail_mhs(" " + obj[0].nama_depan,obj[0].nim_mhs).done(function() {
							$(".divLoading").hide();
							$("#tbl_makul_mhs").slideDown(1000);
							//setting data edit kedalam form
							for(var c=0; c < obj.length; c++) {
								
								$( ".chxClsMapel" ).each(function() {
									if($(this).attr("alt")==obj[c].id_makul_kelas) {
										//alert($(this).attr("alt") + " == " + obj[c].id_makul_kelas);
										$("#chxMapel"+obj[c].id_makul_kelas).prop("checked",true);
										$("#rowIdMakul"+obj[c].id_makul_kelas).css("background-color","#FFF466");
										hitung_jml_sks();
									}
								});
							}
							
						});
						
						 $('html,body').animate({
							scrollTop: 0
						}, 700);
					}
				}
			});
			
		});
		
		
		
	});
</script>

<style>
.divLoading {
	width:100%;
	height:80px;
	background-color:#000000;
	opacity: 0.7;
	color:#ffffff;
	text-align:center;
}
</style>	

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>
	
<div class='header-popup' >
Setting Matapelajaran per Peserta Didik ( <?php echo $ta_pci_active->tahun . " Semester " . $ta_pci_active->semester; ?> )
</div>

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		<button id='btnAddNew' class='btn btn-danger btn-sm' ><span class='glyphicon glyphicon-plus' aria-hidden='true'></span> Setting Mata Pelajaran Peserta Didik</button>
		<br><br>
		<form action="" method="POST" name="frmSetMakulMhs" id="frmSetMakulMhs" style='display:none;' >
			<table class="table table-hover" id='tblKelas' >
				
				<tr id='txtInfoBox' style='display:none;' >
					<td colspan='2' style='background-color:red;text-align:center;color:#ffffff;' >
						<span class="glyphicon glyphicon-warning-sign" aria-hidden="true" ></span> 
						<label id='txtInfoMsg' style='text-weight:bold;' >Warning</label>
					</td>
				</tr>
				
				<tr>
					<td align='right' width='15%' >Tahun Ajaran : </td>
					<td>
						<?php echo $ta_pci_active->tahun; ?>
						<input type='hidden' class='form-control' name='txtTahunAjaran' id='txtTahunAjaran' value='<?php echo $ta_pci_active->tahun; ?>' required >
					</td>
				</tr>
				
				<tr>
					<td align='right' >Semester : </td>
					<td>
						<?php echo ($ta_pci_active->semester%2==0?"Genap":"Ganjil"); ?>
						<input type='hidden' class='form-control' name='txtSemester' id='txtSemester' value='<?php echo $ta_pci_active->semester; ?>' required >
					</td>
				</tr>
				
				
				<tr>
					<td align='right' >Nip : </td>
					<td>
						<input type='text' class='form-control' name='txtNimMahasiswa' id='txtNimMahasiswa' placeholder='ketik nim atau nama mahasiswa' required >
					</td>
				</tr>
				
				<?php
				//print_r($data_mahasiswa->result());
				
				echo "<script>";
				echo "var nourut=0;";
				echo "</script>";
				foreach($data_mahasiswa->result() as $obj) {
					//if($obj->tahun_ajaran!=$ta_pci_active->tahun && $obj->semester!=$ta_pci_active->semester) {
						echo "<script>";
						echo "arNamaMahasiswa[nourut]={ label: '(".$obj->nim . ") ". $obj->nama_depan."', value: '".$obj->nim."' };";
						echo "nourut++;";
						echo "</script>";
					//}
				}
				?>
				
				<tr>
					<td align='right' >Nama : </td>
					<td>
						<input type='text' class='form-control' name='txtNamaMahasiswa' id='txtNamaMahasiswa' readonly >
					</td>
				</tr>
				
				<tr>
					<td align='right' >Fakultas : </td>
					<td>
						<input type='text' class='form-control' name='txtFakultasMhs' id='txtFakultasMhs' readonly >
					</td>
				</tr>
				
				<tr>
					<td align='right' >Jurusan : </td>
					<td>
						<input type='text' class='form-control' name='txtJurusanMhs' id='txtJurusanMhs' readonly >
					</td>
				</tr>
				
				<tr class='tr_detail_makul' style='display:none;' >
					<td align='right' >Pilih Mata Pelajaran Dengan Kelas : </td>
					<td>
						<?php
						$jmlMakul = $data_makul_kelas->num_rows();
						$jmlMakulBagiTiga = ceil($jmlMakul/3);
						
						echo "<table class='table table-bordered table-hover' >";
						
						echo "<tr align='center' style='background-color:#A6B6ED;' >";
						echo "<td>Periode</td>";
						echo "<td>Kode mapel</td>";
						echo "<td>Nama Mata Pelajaran</td>";
						echo "<td>Kelas</td>";
						echo "<td>SKS</td>";
						echo "<td>Pengajar</td>";
						echo "<td>Kelas</td>";
						//echo "<td>Pilih</td>";
						echo "</tr>";
						
						echo "<tbody id='tbl_makul_mhs' ></tbody>";
						echo "<div class='divLoading' ><br><img src='icons/loading.gif'><br>Loading...!!!</div>";
						
						/*foreach($data_makul_kelas->result() as $row) {
							echo "<tr align='center' id='rowIdMakul".$row->id."' style='cursor:pointer;' alt='".$row->id."' class='rowMakul' >";
							echo "<td>".$row->kode_makul."</td>";
							echo "<td>".$row->nmkmk."</td>";
							echo "<td>".$row->kode_kelas." - ".$row->nmkls."</td>";
							echo "<td>".$row->sks."</td>";
							echo "<td>".$row->nama_dosen."</td>";
							echo "<td><input type='checkbox' name='chxMapel[]' id='chxMapel".$row->id."' alt='".$row->id."' jmlsks='".$row->sks."' class='form-control chxClsMapel' value='".$row->id."' ></td>";
							echo "</tr>";
						}*/
						
						echo "</table>";
						?>
					</td>
				</tr>
				
				
				<tr class='tr_detail_makul' style='display:none;' >
					<td align='right'>Total SKS : </td>
					<td><label id='txtJmlSks' name='txtJmlSks' >0</label></td>
				</tr>
				
				<tr class='tr_detail_makul' style='display:none;' >
					<td colspan='2'><input type='submit' class='btn btn-primary btn-sm form-control' name='btnSimpan' id='btnSimpan' value='Simpan' ></td>
				</tr>
				
				
			</table>
		</form>
		
		
		<div id='list_makul_kelas' class="table-responsive" >
			<form action="" method="POST" name="frmMakulKelas" id="frmMakulKelas" >
				<table class="table table-bordered table-striped" >
					<tr align='center' class='header-popup-tbl' style='background-color:#6071C6;' >
						<td>Tahun Ajaran</td>
						<td>Semester</td>
						<td>Nip</td>
						<td>Nama Peserta didik</td>
						<td>Detail Mapel</td>
						<td>Edit</td>
					</tr>
					
					<?php
					foreach($data_makul_mahasiswa->result() as $obj) {
						echo "<tr align='center'>";
						echo "<td>".$obj->tahun_ajaran."</td>";
						echo "<td>".($obj->semester%2==0?"Genap":"Ganjil")."</td>";
						echo "<td>".$obj->nim_mhs."</td>";
						echo "<td>".$obj->nama_depan."</td>";
						echo "<td><input type='button' value='Mapel' alt='".$obj->id."' class='form-control btn-danger btn-sm btn-block btnDetailMakulMhs' title='Detail Makul'  ></td>";
						echo "<td><input type='button' value='Edit' alt='".$obj->id."' class='form-control btn-danger btn-sm btn-block btnEditMakulMhs' title='Hapus data'  ></td>";
						echo "</tr>";
					}
					?>
				</table>
			</form>
		</div>
		
		
		<!-- Modal -->
		<button id='btnModal' type="button" class="btn btn-primary" data-toggle="modal" data-target=".modalDetailMakul" style='display:none;' >Small modal</button>

		<div class="modal fade modalDetailMakul" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Mata pelajaran yang diambil</h4>
					</div>
					
					<div class="modal-body">
					</div>
					
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		
		
	</div>
	
</div>