<script>
	$(document).ready(function() {
		
		$("#txtFak").change(function() {
			$("#lblFak").text($("#txtFak option:selected").text());
			
			var idfak = "idfak="+$("#txtFak").val();
			$.ajax({
				type: "POST",
				url: "Set_mapelusm_ctr/jurusan_list/",
				data: idfak,
				success: function(data, status) {
					//alert(data);
					var obj = jQuery.parseJSON( data );
					if(obj.length>0) {
						$("#txtJur").empty();
						$("#txtJur").append("<option value=''>== Pilih Jurusan ==</option>");
						for(var c=0; c < obj.length; c++) {
							$("#txtJur").append("<option alt='"+obj[c].id+"' value='"+obj[c].id+"'>"+obj[c].jurusan +"</option>");
						}
						
						$("#rowBtnSimpan").hide();
						$("#rowJurusan").show();
					}
					
				}
			});
			
		});
		
		$("#txtJur").change(function() {
			$("#rowMapelUSM").show();
			
			while(noUrutIsiMapel>0) {
				noUrutIsiMapel--;
				$("#rowDetail"+noUrutIsiMapel).remove();
			}
			
			var datacari = "idfak="+$("#txtFak").val();
			datacari += "&idjur="+$("#txtJur").val();
			datacari += "&ta="+$("#txtTa").val();
			
			$.ajax({
				type: "POST",
				url: "Set_mapelusm_ctr/cari_mapel_usm/",
				data: datacari,
				success: function(data, status) {
					//alert(data);
					var obj = jQuery.parseJSON( data );
					if(obj.length>0) {
						for(var c=0; c < obj.length; c++) {
							//alert(noUrutIsiMapel + " : " + obj[c].nama_makul);
							$("#txtNamaMapel"+(noUrutIsiMapel-1)).val(obj[c].nama_makul);
							
							if(c<obj.length-1) {
								tambah_row_isi_mapel();
							}
						}
					}
					
				}
			});
			
			$("#rowBtnAdd").show();
			$("#rowBtnSimpan").show();
			tambah_row_isi_mapel();
		});
		
		var noUrutIsiMapel=0;
			
		function tambah_row_isi_mapel() {
			var isiTxtMapel = "";
			isiTxtMapel += "<tr id='rowDetail"+noUrutIsiMapel+"' >";
			isiTxtMapel += "<td align='right' width='10px;'><input type='hidden' name='txtNoUrut[]' id='txtNoUrut"+noUrutIsiMapel+"' value='"+(noUrutIsiMapel+1)+"' >";
			isiTxtMapel += "<label>"+(noUrutIsiMapel+1)+". </label></td>";
			isiTxtMapel += "<td><input type='text' name='txtNamaMapel[]' id='txtNamaMapel"+noUrutIsiMapel+"' class='form-control input-sm' placeholder='Masukkan Nama Makul USM' required ></td>";
			isiTxtMapel += "</tr>";
			
			noUrutIsiMapel++;
			$("#isiMapelUSM").append(isiTxtMapel);
		}
		
		$("#btnTambahMapel").click(function() {
			tambah_row_isi_mapel();
		});
		$("#btnKurangMapel").click(function() {
			if(noUrutIsiMapel>1) {
				noUrutIsiMapel--;
				$("#rowDetail"+noUrutIsiMapel).remove();
			}
			return false;
		});
		
		
		$("#btnSimpanMepelUSM").click(function() {
			
			$("#frmSetMapelUSM").submit(function() {
				var dataform = $(this).serialize();
				
				$.ajax({
					type: "POST",
					url: "Set_mapelusm_ctr/simpan_mapelusm/",
					data: dataform,
					success: function(data, status) {
						//alert(data);
						if(data==1) {
							alert("Simpan Makul USM Sukses!")
						} else {
							alert("Simpan Makul USM Gagal!")
						}
						$("#content_adm").load("Set_mapelusm_ctr");
					}
				});
				
				return false;
			});
			
		});
		
	});
</script>

<div class='header-popup' >
SETTING MAPEL UJIAN SARINGAN MASUK
</div>


<div class="isi-popup" >
	
	<form action="" method="POST" name="frmSetMapelUSM" id="frmSetMapelUSM" >
	<table class="table borderless" id='tblSetMapel'  >
		
		<tr>
			<td align='right' width='120px' >Tahun Ajaran : </td>
			<td align='left' ><?php echo $ta_active; ?><input type='hidden' name='txtTa' id='txtTa' value='<?php echo $ta_active; ?>' ></td>
		</tr>
		
		<tr id='rowFakultas' >
			<td align='right' >Fakultas : </td>
			<td>
				<select class='form-control input-sm' name='txtFak' id='txtFak' >
				<option value=''>== Pilih Fakultas ==</option>
				<?php
				foreach($fakultas_list->result() as $fkl) {
					echo "<option value='".$fkl->id."' >".$fkl->fakultas."</option>";
				}
				?>
				</select>	
				<label id='lblFak' style='display:none;' ></label>
			</td>
		</tr>
		
		<tr id='rowJurusan' style='display:none;' >
			<td align='right' >Jurusan : </td>
			<td>
				<select class='form-control input-sm' name='txtJur' id='txtJur' >
				<option value=''>== Pilih Jurusan ==</option>
				</select>	
				<label id='lblJur' style='display:none;' ></label>
			</td>
		</tr>
		
		<tr id='rowMapelUSM' style='display:none;' >
			<td align='left' colspan='2' ><b>Mata Pelajaran USM yang diujikan</b></td>
			<td>
				<tbody id='isiMapelUSM' ></tbody>			
			</td>
		</tr>
			
		<tr id='rowBtnAdd' style='display:none;' >
			<td align='right' ></td>
			<td align='left'>
				<button id='btnTambahMapel' class='btn btn-primary' >
				 <span class="glyphicon glyphicon-plus" aria-hidden="true"> Tambah</span>
				</button>
				<button id='btnKurangMapel' class='btn btn-danger' >
				 <span class="glyphicon glyphicon-minus" aria-hidden="true"> Kurang</span>
				</button>
			</td>
		</tr>
		
		<tr id='rowBtnSimpan' style='display:none;' >
			<td align='center' colspan='2' >
				<button id='btnSimpanMepelUSM' class='btn btn-warning form-control' >
				 <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"> Simpan</span>
				</button>
			</td>
		</tr>
		
	</table>
	</form>	
		
</div>
