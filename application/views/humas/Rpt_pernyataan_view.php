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
			
			var nimmhs = "noform="+$("#txtNoform").val();
			var fakid=0;
			var jurid=0;
			$.ajax({
				type: "POST",
				url: "Rpt_pernyataan_ctr/cari_fakjur/",
				data: nimmhs,
				success: function(data, status) {
					//alert(data);
					var obj = jQuery.parseJSON( data );
					if(obj.length>0) {
						for(var c=0; c < obj.length; c++) {
							$("#txtFakultasMhs").val(obj[c].fakultas);
							$("#txtJurusanMhs").val(obj[c].jurusan);
							fak_id=obj[c].fakultas_id;
							jur_id=obj[c].jurusan_id;
						}
					}
				}
			});
			$("#rowBtn").show();
			$("#rowPrint").hide();
			$("#isi_surat_pernyataan").slideUp(100);
			
			var r = $.Deferred();

			setTimeout(function () {
				r.resolve();
			}, 1500);
			
			return r;
		}
		
		
		$("#btnShowPernyataan").click(function() {
			$("#isi_surat_pernyataan").load("Rpt_pernyataan_ctr/tampil_srt_pernyataan/"+$("#txtNoform").val(), function() {
				$("#rowPrint").show();
				$("#isi_surat_pernyataan").slideDown(100);
			});
		});
		
	});
	
	function printDiv(divName) {
		 var printContents = document.getElementById(divName).innerHTML;
		 var originalContents = document.body.innerHTML;

		 document.body.innerHTML = printContents;
		 window.print();
		 document.body.innerHTML = originalContents;
	}
</script>

<div class='header-popup' >
Cetak Surat Pernyataan
</div>


<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		<form action="" method="POST" name="frmSetMakulMhs" id="frmSetMakulMhs" >
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
						<input type='hidden' class='form-control input-sm' name='txtTahunAjaran' id='txtTahunAjaran' value='<?php echo $ta_pci_active->tahun; ?>' required >
					</td>
				</tr>
				
				<!--<tr>
					<td align='right' >Semester : </td>
					<td>-->
						<?php //echo ($ta_pci_active->semester%2==0?"Genap":"Ganjil"); ?>
						<!--<input type='hidden' class='form-control input-sm' name='txtSemester' id='txtSemester' value='<?php echo $ta_pci_active->semester; ?>' required >
					</td>
				</tr>-->
				
				
				<tr>
					<td align='right' >No Formulir : </td>
					<td>
						<input type='text' class='form-control input-sm' name='txtNoform' id='txtNoform' placeholder='ketik No.form atau nama mahasiswa' required >
					</td>
				</tr>
				
				<?php
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
				
				<tr id='rowBtn' style='display:none;' >
					<td colspan='2' >
						<input type='button' id='btnShowPernyataan' value='Tampil Surat Pernyataan' class='btn btn-primary form-control input-sm' >
					</td>
				</tr>
				
				<tr id='rowPrint' style='display:none;' >
					<td colspan='2' style='background-color:#DEDCEA;' > 
						<button type="button" class="btn btn-default" aria-label="Left Align" onclick="printDiv('isi_surat_pernyataan')" >
						  <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
						</button>
					</td>
				</tr>

				
			</table>
		</form>
	</div>
	
	
	<div id="isi_surat_pernyataan" >
	
	</div>
</div>
