<script>
	var arNamaMahasiswa = new Array();
		
	$(document).ready(function() {
		
		// ++++++++++++++++++++++++++++++++++ AUTOCOMPLETE +++++++++++++++++++++++++++++++++++++++++++++
		$( "#txtNoform" ).autocomplete({
			source: arNamaMahasiswa,
			select: function ( event, ui ) {
				event.preventDefault();
				cari_detail_mhs(ui.item.label,ui.item.value);
			},
			focus: function( event, ui ) {
				var str = ui.item.label;
				var posNama = str.indexOf(") ")+2;
				var namaMhs = str.substring(posNama,100);
				$("#txtNoform").val(ui.item.value);
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
			$("#txtNoform").val(isiValue);
			$("#txtNamaMahasiswa").val(namaMhs);
			
			var noform = "noform=" + isiValue;
			
			var fakid=0;
			var jurid=0;
			$.ajax({
				type: "POST",
				url: "Trx_siswabatal_ctr/cari_fakjur/",
				data: noform,
				success: function(data, status) {
					//alert(data);
					var obj = jQuery.parseJSON( data );
					if(obj.length>0) {
						for(var c=0; c < obj.length; c++) {
							$("#txtFakultasMhs").val(obj[c].fakultas);
							$("#txtJurusanMhs").val(obj[c].jurusan);
							fak_id=obj[c].fak_id;
							jur_id=obj[c].jur_id;
							$("#txtAlasan").focus();
						}
					}
				}
			});
			
		}
		
		
		$("#frmSiswaBatal").submit(function() {
			
			var dataform = $(this).serialize();
			
			$.ajax({
				type: "POST",
				url: "Trx_siswabatal_ctr/simpan_siswabatal/",
				data: dataform,
				success: function(data, status) {
					//alert(data);
					if(data==1) {
						alert("Simpan Mahasiswa Batal Masuk Sukses!")
					} else if(data==2) {
						alert("Simpan Mahasiswa Batal Masuk Gagal!\nMahasiswa tersebut sudah ada di Mahasiswa batal masuk");
					} else {
						alert("Simpan Mahasiswa Batal Masuk Gagal!")
					}
					$("#content_adm").load("Trx_siswabatal_ctr");
				}
			});
			
			return false;
		});
		
		$("#btnDataMhsBatal").click(function() {
			$(".modal-body").load("Trx_siswabatal_ctr/data_mhs_batal");
			//$(".modal-body").html("aaa");
		});
		
		
	});
	
</script>
<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>

<div class='header-popup' >
Siswa Batal Masuk
</div>

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		
		<div style='text-align:right;' >
			<button class='btn btn-warning input-sm' id='btnDataMhsBatal' title='Show Mahasiswa Batal Masuk' data-toggle="modal" data-target="#myModal" >
			<span class='glyphicon glyphicon-list' aria-hidden='true'></span> Mahasiswa Batal
			</button>
		</div>
		
		<!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Mahasiswa Batal Masuk</h4>
			  </div>
			  <div class="modal-body">
				...
			  </div>
			</div>
		  </div>
		</div>

		
		<form action="" method="POST" name="frmSiswaBatal" id="frmSiswaBatal" >
			<table class="table table-hover" id='tblSiswaBatal' >
				
				<tr id='txtInfoBox' style='display:none;' >
					<td colspan='2' style='background-color:red;text-align:center;color:#ffffff;' >
						<span class="glyphicon glyphicon-warning-sign" aria-hidden="true" ></span> 
						<label id='txtInfoMsg' style='text-weight:bold;' >Warning</label>
					</td>
				</tr>
				
				<tr>
					<td align='right' width='15%' >Tahun Ajaran : </td>
					<td>
						<?php echo $ta_active->tahun; ?>
						<input type='hidden' class='form-control input-sm' name='txtTahunAjaran' id='txtTahunAjaran' value='<?php echo $ta_active->tahun; ?>' required >
					</td>
				</tr>
				
				<tr>
					<td align='right' >No Formulir : </td>
					<td>
						<input type='text' class='form-control input-sm' name='txtNoform' id='txtNoform' placeholder='ketik No.Form atau nama mahasiswa' required >
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
						echo "arNamaMahasiswa[nourut]={ label: '(".$obj->no_formulir . ") ". $obj->nama_depan."', value: '".$obj->no_formulir."' };";
						echo "nourut++;";
						echo "</script>";
					//}
				}
				?>
				
				<tr>
					<td align='right' >Nama : </td>
					<td>
						<input type='text' class='form-control input-sm' name='txtNamaMahasiswa' id='txtNamaMahasiswa' readonly >
					</td>
				</tr>
				
				<tr>
					<td align='right' >Fakultas : </td>
					<td>
						<input type='text' class='form-control input-sm' name='txtFakultasMhs' id='txtFakultasMhs' readonly >
					</td>
				</tr>
				
				<tr>
					<td align='right' >Jurusan : </td>
					<td>
						<input type='text' class='form-control input-sm' name='txtJurusanMhs' id='txtJurusanMhs' readonly >
					</td>
				</tr>
				
				<tr class='row_txtAlasan' >
					<td align='right' >Alasan : </td>
					<td>
						<Textarea rows='5' class='form-control input-sm' name='txtAlasan' id='txtAlasan' ></textarea>
					</td>
				</tr>
				
				<tr class='row_txtAlasan' >
					<td align='right' ></td>
					<td>
						<input type='submit' class='form-control btn btn-primary input-sm' id='btnSimpan' value='Simpan' ></textarea>
					</td>
				</tr>
				
			</table>
		</form>
		
	</div>
</div>