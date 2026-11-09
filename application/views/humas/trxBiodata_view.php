<?php
echo "<script>";
echo "var listSekolah=[];";
echo "</script>";
foreach($data_sekolah->result() as $sc) {
	echo "<script>";
	//echo "listSekolah.push('".$sc->nama_sekolah."');";
	echo "listSekolah.push({ 'label': '".$sc->nama_sekolah."', 'value': '".$sc->id."' });";
	echo "</script>";
}
?>

<script>
	$(document).ready(function() {
		var statusEdit = 0;
		statusEdit = <?php echo (isset($stat_edit)?1:0); ?>;
		
		$("#btnAmbilData").click(function() {
			$("#modal_content_body").load("Trx_biodata_ctr/tampil_data",function() {
				
			});
		});
		
		$("#btnCancelInput").click(function() {
			$("#content_adm").load("Trx_biodata_ctr");
		});
		
		$( "#txtKuliahDari" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"yy-mm-dd",
			showAnim:"drop"
		});
		$( "#txtKuliahHingga" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat:"yy-mm-dd",
			showAnim:"drop"
		});
		
		$(".tab_click").click(function() {
			var tabdipilih = $(this).attr('title');
			
			$(".tab-pane").hide(300);
			$(".frm-all").hide(300);
			if(tabdipilih=="tab_siswa") {
				$("#tab_siswa").show(500);
				$("#frmBioSiswa").show(500);
				$("#txtNamaKeluarga").focus();
			} else if (tabdipilih=="tab_pendidikan") {
				$("#tab_pendidikan").show(500);
				$("#frmBioPendidikan").show(500);
				$("#txtRiwayat0").focus();
			} else if (tabdipilih=="tab_keluarga") {
				$("#tab_keluarga").show(500);
				$("#frmBioAnggota").show(500);				
				$("#txtNamaKeluarga0").focus();
			} else if (tabdipilih=="tab_hubkeluarga") {
				$("#tab_hubkeluarga").show(500);
				$("#frmHubkeluarga").show(500);
				$("#txtNamaKel").focus();
			} else if (tabdipilih=="tab_dana") {
				$("#tab_dana").show(500);
				$("#frmBioDana").show(500);
				$("#txtSumberDana").focus();
			} else if (tabdipilih=="tab_pembayaran") {
				$("#tab_pembayaran").show(500);
				$("#frmBioPmbyr").show(500);
				$("#txtJenisPembayaran").focus();
			}
		});
		
		
		
		
		// +++++++++++++++++++++++++++++++++++++++++ TAB PENDIDIKAN +++++++++++++++++++++++++++++++++++++++++
		var nomorRowTblPend=0;
		if(statusEdit == 1) {
			var idform = "idform=" + $("#txtIdFormulir").val();
			$.ajax({
				type: "POST",
				url: "Trx_biodata_ctr/edit_biodata_pend/",
				data: idform,
				success: function(data, status) {
					var obj = jQuery.parseJSON( data );
					if(obj.length>0) {
						for(var c=0; c < obj.length; c++) {
							addrow_table_pendidikan();
							var notbl=nomorRowTblPend-1;
							$("#txtRiwayat"+notbl).val(obj[c].riwayat);
							$("#txtPendid"+notbl).val(obj[c].sekolah_id);
							$("#txtPeriodeDari"+notbl).val(obj[c].periode_dari);
							$("#txtPeriodeKe"+notbl).val(obj[c].periode_sampai);
							$("#txtJabatan"+notbl).val(obj[c].jabatan);
						}
					}
				}
			});
		} else {
			addrow_table_pendidikan();
		}
		
		 
		function addrow_table_pendidikan() {
			
			var isi = "<tr id='rowpend"+nomorRowTblPend+"' >";
			isi += "<td>";
				isi += "<input type='text' class='form-control input-sm clsRiwayatPend' name='txtRiwayat[]' id='txtRiwayat"+nomorRowTblPend+"' style='width:100%;'  title='Riwayat' nomorurut='"+nomorRowTblPend+"' required >";
				isi += "<input type='hidden' class='form-control input-sm clsRiwayatPendId' name='txtPendid[]' id='txtPendid"+nomorRowTblPend+"' style='width:100%;'  title='Pend ID' >";
			isi += "</td>";
			isi += "<td align='center' >";
				isi += "<input type='text' class='form-control input-sm clsPeriodeDari' name='txtPeriodeDari[]' id='txtPeriodeDari"+nomorRowTblPend+"' placeholder='yyyy' maxlength='4' required >";
				isi += " s.d <input type='text' class='form-control input-sm clsPeriodeKe' name='txtPeriodeKe[]' id='txtPeriodeKe"+nomorRowTblPend+"' placeholder='yyyy' maxlength='4' required >";
			isi += "</td>";
			isi += "<td><input type='text' class='form-control input-sm' name='txtJabatan[]' id='txtJabatan"+nomorRowTblPend+"' style='width:100%;' title='Jabatan' ></td>";
			isi += "</tr>";
			
			$("#isi_tbl_pendidikan").append(isi);
			nomorRowTblPend++;
			
			$( ".clsPeriodeDari" ).datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat:"yy",
				showAnim:"drop",
				yearRange: "2000:2030"
			});
			$( ".clsPeriodeKe" ).datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat:"yy",
				showAnim:"drop",
				yearRange: "2000:2030"
			});
			$(".clsRiwayatPend").autocomplete({
				source: listSekolah
			});
			
			$(".clsRiwayatPend").on('autocompleteselect',function(e,ui) {
				e.preventDefault();
				$(this).val(ui.item.label);
				var urutnopilih = $(this).attr("nomorurut");
				$("#txtPendid"+urutnopilih).val(ui.item.value);
			});
		}
		
		
		
		
		$("#btnAddPend").click(function() {
			addrow_table_pendidikan();
			$("#txtRiwayat"+(nomorRowTblPend-1)).focus();
		});
		$("#btnRemovePend").click(function() {
			if(nomorRowTblPend>1) {
				nomorRowTblPend--;
				$("#rowpend"+nomorRowTblPend).remove();
			}
		});
		// +++++++++++++++++++++++++++++++++++++++++ END TAB PENDIDIKAN +++++++++++++++++++++++++++++++++++++++++
		
		
		
		
		
		// +++++++++++++++++++++++++++++++++++++++++ TAB ANGGOTA KELUARGA +++++++++++++++++++++++++++++++++++++++++
		var nomorRowTblKeluarga=0;
		if(statusEdit == 1) {
			var idform = "idform=" + $("#txtIdFormulir").val();
			$.ajax({
				type: "POST",
				url: "Trx_biodata_ctr/edit_biodata_anggota/",
				data: idform,
				success: function(data, status) {
					var obj = jQuery.parseJSON( data );
					if(obj.length>0) {
						for(var c=0; c < obj.length; c++) {
							addrow_table_keluarga();
							var notbl=nomorRowTblKeluarga-1;
							$("#txtNamaKeluarga"+notbl).val(obj[c].nama);
							$('#txtHubungan'+notbl+' option[value='+obj[c].hubungan+']').attr('selected','selected');
							$("#txtTelpfaks"+notbl).val(obj[c].telepon);
						}
					}
				}
			});
		} else {
			addrow_table_keluarga();
		}
		function addrow_table_keluarga() {
			var isi = "<tr id='rowkeluarga"+nomorRowTblKeluarga+"' >";
			isi += "<td><input type='text' class='form-control input-sm' name='txtNamaKeluarga[]' id='txtNamaKeluarga"+nomorRowTblKeluarga+"' style='width:100%;'  title='Nama anggota keluarga' required ></td>";
			isi += "<td>";
				isi += "<select class='form-control input-sm' name='txtHubungan[]' id='txtHubungan"+nomorRowTblKeluarga+"' style='width:100%;' title='Hubungan keluarga' required >";
				isi += "<option value='' >== hubungan keluarga ==</option>";
				isi += "<option value='Ayah' >Ayah</option>";
				isi += "<option value='Ibu' >Ibu</option>";
				isi += "<option value='Wali' >Wali</option>";
				isi += "<option value='Suami' >Suami</option>";
				isi += "<option value='Istri' >Istri</option>";
				isi += "<option value='Anak' >Anak</option>";
				isi += "<option value='Kakak' >Kakak</option>";
				isi += "<option value='Adik' >Adik</option>";
				isi += "</select>";
			isi += "</td>";
			isi += "<td><input type='text' class='form-control input-sm' name='txtTelpfaks[]' id='txtTelpfaks"+nomorRowTblKeluarga+"' style='width:100%;' title='Telepon atau faks' required ></td>";
			isi += "</tr>";
			
			$("#isi_tbl_keluarga").append(isi);
			nomorRowTblKeluarga++;
		}
		$("#btnAddKeluarga").click(function() {
			addrow_table_keluarga();
			$("#txtNamaKeluarga"+(nomorRowTblKeluarga-1)).focus();
		});
		$("#btnRemoveKeluarga").click(function() {
			if(nomorRowTblKeluarga>1) {
				nomorRowTblKeluarga--;
				$("#rowkeluarga"+nomorRowTblKeluarga).remove();
			}
		});
		// +++++++++++++++++++++++++++++++++++++++++ END TAB ANGGOTA KELUARGA +++++++++++++++++++++++++++++++++++++++++
		
		
		
		
		// +++++++++++++++++++++++++++++++++++++++++ TAB PEMBAYARAN +++++++++++++++++++++++++++++++++++++++++
		var nomorRowTblPembayaran=0;
		if(statusEdit == 1) {
			var idform = "idform=" + $("#txtIdFormulir").val();
			$.ajax({
				type: "POST",
				url: "Trx_biodata_ctr/edit_biodata_pembayaran/",
				data: idform,
				success: function(data, status) {
					var obj = jQuery.parseJSON( data );
					if(obj.length>0) {
						while(nomorRowTblPembayaran>0) {
							nomorRowTblPembayaran--;
							$("#rowbyr"+nomorRowTblPembayaran).remove();
						}
						
						for(var c=0; c < obj.length; c++) {
							$('#txtJenisPembayaran option[value='+obj[c].jenis_bayar+']').attr('selected','selected');
							
							$("#isi_detail_pembayaran").show(1000);
							addrow_table_pembayaran(obj[c].jenis_bayar,obj[c].detail_pembayaran);
							$("#txtNoCicilan"+(nomorRowTblPembayaran-1)).val(obj[c].nomor_pembayaran);
							$("#txtJatuhTempo"+(nomorRowTblPembayaran-1)).val(obj[c].jatuh_tempo);
							hitung_total_cicilan();
						}
						
						$("#txtUangSemester").val(obj[0].uang_semester);
						$("#txtUangPangkal").val(obj[0].uang_pangkal);
						hitung_total_tagihan();	
					}
				}
			});
		}
		$("#txtJenisPembayaran").change(function() {
			var jenispembayaran = $(this).val();
			
			while(nomorRowTblPembayaran>0) {
				nomorRowTblPembayaran--;
				$("#rowbyr"+nomorRowTblPembayaran).remove();
			}
			
			var jenisPmbyr = "jenisPmbyr=" + jenispembayaran;
			$.ajax({
				type: "POST",
				url: "Trx_biodata_ctr/cari_ms_pembayaran/",
				data: jenisPmbyr,
				success: function(data, status) {
					var obj = jQuery.parseJSON( data );
							
					if(obj.length>0) {
						for(var c=0; c < obj.length; c++) {
							if(jenispembayaran=="Cash") {
								$("#isi_detail_pembayaran").show(1000);
								var totCashMaster = Number(obj[c].uang_pangkal)+Number(obj[c].uang_semester);
								addrow_table_pembayaran('Cash',totCashMaster);
							} else if (jenispembayaran=="Kredit") {
								$("#isi_detail_pembayaran").show(1000);
								addrow_table_pembayaran('Kredit',obj[c].harga_cicil);
							}
							
							$("#txtUangSemester").val(obj[c].uang_semester);
							$("#txtUangPangkal").val(obj[c].uang_pangkal);
						}
						
						hitung_total_cicilan();
						hitung_total_tagihan();
					}
					
					if (jenispembayaran=="") {
						$("#btnAddRemoveCicil").hide(500);
						addrow_table_pembayaran('reset');
					}
				}
			});
		});
		
		var angkacicil=1;
		function addrow_table_pembayaran(jnsbyr,nilai_pmbyran) {
			var isi = "<tr id='rowbyr"+nomorRowTblPembayaran+"' >";
			if(jnsbyr=="Cash") {
				isi += "<td align='right' >Cash : </td>";
				$("#btnAddRemoveCicil").hide(500);
				angkacicil=1;
			} else if (jnsbyr=="Kredit") {
				isi += "<td align='right' >Kredit "+angkacicil+" : </td>";
				$("#btnAddRemoveCicil").show(500);
			} else {
				$("#btnAddRemoveCicil").hide(500);
				$("#isi_detail_pembayaran").hide();
				angkacicil=1;
			}
			isi += "<td width='30%'>";
				isi += "<input type='hidden' name='txtNoCicilan[]' id='txtNoCicilan"+nomorRowTblPembayaran+"' class='form-control input-sm' value='"+angkacicil+"' >";
				isi += "<input type='text' name='txtDetailBayar[]' id='txtDetailBayar"+nomorRowTblPembayaran+"' class='form-control input-sm nilaiByrDetail' style='width:100%;' value='"+(nilai_pmbyran!=undefined?nilai_pmbyran:'')+"' required >";
			isi += "</td>";
			isi += "<td>Jatuh Tempo : <input type='text' name='txtJatuhTempo[]' id='txtJatuhTempo"+nomorRowTblPembayaran+"' class='form-control input-sm clsJatuhTempo' placeholder='yyyy-mm-dd' required ></td>";
			isi += "</tr>";
			
			$("#isi_detail_pembayaran").append(isi);
			nomorRowTblPembayaran++;
			
			if(jnsbyr=="Kredit") {
				angkacicil++;
			}
			
			$( ".clsJatuhTempo" ).datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat:"yy-mm-dd",
				showAnim:"drop"
			});
			
			jQuery('.nilaiByrDetail').keyup(function () { 
				this.value = this.value.replace(/[^0-9\.]/g,'');
			});
			
			$(".nilaiByrDetail").change(function() {
				hitung_total_cicilan();		
			});
		}
		
		
			
		$("#txtUangSemester").change(function() {
			hitung_total_tagihan();
		});
		jQuery('#txtUangSemester').keyup(function () { 
			this.value = this.value.replace(/[^0-9\.]/g,'');
		});
		$("#txtUangPangkal").change(function() {
			hitung_total_tagihan();
		});
		jQuery('#txtUangPangkal').keyup(function () { 
			this.value = this.value.replace(/[^0-9\.]/g,'');
		});
		
		function hitung_total_cicilan() {
			var jml_total_cicilan=0;
			$(".nilaiByrDetail").each(function() {
				jml_total_cicilan += Number($(this).val());
			});
			//jml_total_cicilan += Number($("#txtUangSemester").val());
			$("#txtHasilTotCicilan").text(addCommas(jml_total_cicilan));
		}
		function hitung_total_tagihan() {
			var jml_total_tagihan=0;
			jml_total_tagihan += Number($("#txtUangPangkal").val());
			jml_total_tagihan += Number($("#txtUangSemester").val());
			$("#lblTotalTagihan").text(addCommas(jml_total_tagihan));
		}
		
		
		$("#btnAddBayar").click(function() {
			addrow_table_pembayaran("Kredit");
		});
		
		$("#btnRemoveBayar").click(function() {
			if(nomorRowTblPembayaran>1) {
				nomorRowTblPembayaran--;
				angkacicil--;
				$("#rowbyr"+nomorRowTblPembayaran).remove();
				hitung_total_cicilan();
			}
		});
		
		function addCommas(nStr) {
			nStr += '';
			x = nStr.split('.');
			x1 = x[0];
			x2 = x.length > 1 ? '.' + x[1] : '';
			var rgx = /(\d+)(\d{3})/;
			while (rgx.test(x1)) {
				x1 = x1.replace(rgx, '$1' + ',' + '$2');
			}
			return x1 + x2;
		}
		// +++++++++++++++++++++++++++++++++++++++++ TAB PEMBAYARAN +++++++++++++++++++++++++++++++++++++++++
		
		
		
		
		// +++++++++++++++++++++++++++++++++++++++++++++++ SIMPAN +++++++++++++++++++++++++++++++++++++++++++++++
		$("#frmBioSiswa").submit(function() {
			var formdata = $(this).serialize();
			formdata+="&txtNoform="+$("#txtNoFormulir").val();
			formdata+="&idNoform="+$("#txtIdFormulir").val();
			
			
			$.ajax({
				type: "POST",
				url: "Trx_biodata_ctr/simpan_biodata_siswa/",
				data: formdata,
				success: function(data, status) {
					var hasil="Simpan Gagal";
					if(data==1) {
						hasil="Simpan Biodata Siswa Berhasil";
					}
					alert(hasil);
				}
			});
			
			return false;
		});
		
		
		$("#frmBioPendidikan").submit(function() {
			var formdata = $(this).serialize();
			formdata+="&txtNoform="+$("#txtNoFormulir").val();
			formdata+="&idNoform="+$("#txtIdFormulir").val();
			
			$.ajax({
				type: "POST",
				url: "Trx_biodata_ctr/simpan_biodata_pendidikan/",
				data: formdata,
				success: function(data, status) {
					var hasil="Simpan Gagal";
					if(data==1) {
						hasil="Simpan Biodata Pendidikan Berhasil";
					}
					alert(hasil);
				}
			});
			
			return false;
		});
		
		
		$("#frmBioAnggota").submit(function() {
			var formdata = $(this).serialize();
			formdata+="&txtNoform="+$("#txtNoFormulir").val();
			formdata+="&idNoform="+$("#txtIdFormulir").val();
			
			$.ajax({
				type: "POST",
				url: "Trx_biodata_ctr/simpan_biodata_anggota/",
				data: formdata,
				success: function(data, status) {
					var hasil="Simpan Gagal";
					if(data==1) {
						hasil="Simpan Biodata Keluarga Berhasil";
					}
					alert(hasil);
				}
			});
			
			return false;
		});
		
		
		$("#frmHubkeluarga").submit(function() {
			var formdata = $(this).serialize();
			formdata+="&txtNoform="+$("#txtNoFormulir").val();
			formdata+="&idNoform="+$("#txtIdFormulir").val();
			
			$.ajax({
				type: "POST",
				url: "Trx_biodata_ctr/simpan_biodata_keluarga/",
				data: formdata,
				success: function(data, status) {
					var hasil="Simpan Gagal";
					if(data==1) {
						hasil="Simpan Biodata Keluarga Berhasil";
					}
					alert(hasil);
				}
			});
			
			return false;
		});
		
		
		$("#frmBioDana").submit(function() {
			var formdata = $(this).serialize();
			formdata+="&txtNoform="+$("#txtNoFormulir").val();
			formdata+="&idNoform="+$("#txtIdFormulir").val();
			
			$.ajax({
				type: "POST",
				url: "Trx_biodata_ctr/simpan_biodata_dana/",
				data: formdata,
				success: function(data, status) {
					var hasil="Simpan Gagal";
					if(data==1) {
						hasil="Simpan Dana Berhasil";
					}
					alert(hasil);
				}
			});
			
			return false;
		});
		
		
		$("#frmBioPmbyr").submit(function() {
			var formdata = $(this).serialize();
			formdata+="&txtNoform="+$("#txtNoFormulir").val();
			formdata+="&idNoform="+$("#txtIdFormulir").val();
			
			var tot_tagihan = $("#lblTotalTagihan").text().replace(/,/g, "");
			var tot_cicil = $("#txtHasilTotCicilan").text().replace(/,/g, "");
			if(tot_cicil==tot_tagihan) {
				$.ajax({
					type: "POST",
					url: "Trx_biodata_ctr/simpan_biodata_pembayaran/",
					data: formdata,
					success: function(data, status) {
						var hasil="Simpan Gagal";
						if(data==1) {
							hasil="Simpan Dana Berhasil";
						}
						alert(hasil);
					}
				});
			} else {
				alert("Total cicilan harus sama dengan Total tagihan!!!");
			}
			return false;
		});
		
		
		$("#btnSeeBiodata").click(function() {
			$("#content_adm").load("Trx_biodata_ctr/tampil_data_biodata");
		});
		
	});
</script>
				
<style>
.ui-datepicker {
   background: #333;
   border: 1px solid #555;
   color: #EEE;
 }
 
 .ui-datepicker select {
	color:#000;
}

.ui-widget-header {
	background:#000000;
}

.borderless td, .borderless th {
    border: none;
}
</style>

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>

<div class='header-popup' >
INPUT BIODATA SISWA
</div>


<div class="modal fade data_questioner" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" >
		
		<div class="modal-header" style='text-align:left;background-color:#A6B6ED;' >
			<button id='btnCloseModal' type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel" ><b>Ambil Data Formulir</b></h4>
		</div>
		
		<div id='modal_content_body' class="modal-body" style='color:#000000;' >
			Loading...
		</div>
		
    </div>
  </div>
</div>

<div class="isi-popup" >

	<div class="table-responsive" style='text-align:left;' >
		<button id='btnSeeBiodata' class='btn btn-danger' ><span class='glyphicon glyphicon-search' aria-hidden='true'></span> Tampil Data Biodata Siswa</button>
		<button id='btnCancelInput' class='btn btn-danger' style='display:none;' ><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Cancel Input</button>
		<button id='btnAmbilData' class='btn btn-primary' data-toggle="modal" data-target=".data_questioner" >
		<span class='glyphicon glyphicon-search' aria-hidden='true'></span> Ambil Data formulir
		</button>
		
		<br><br>
		
		<table class="table borderless" id='tblBioHeader' style='display:none;' >
			
			<tr id='txtInfoBox' style='display:none;' >
				<td colspan='2' style='background-color:red;text-align:center;color:#ffffff;' >
					<span class="glyphicon glyphicon-warning-sign" aria-hidden="true" ></span> 
					<label id='txtInfoMsg' style='text-weight:bold;' >Warning</label>
				</td>
			</tr>
			
			<tr>
				<td width='15%' >Fakultas</td>
				<td>
					<?php
					if(isset($data_trx_formulir)) {
						foreach ($data_fakultas->result() as $obj) {
							if($data_trx_formulir->row(0)->fakultas_id==$obj->id) {
								echo ": ".$obj->fakultas;
							}	
						}
					}
					?>
				</td>
			</tr>
			
			
			<tr>
				<td>Jurusan</td>
				<td>
					<?php
					if(isset($data_trx_formulir)) {
						foreach($data_jurusan->result() as $jur) {
							if($data_trx_formulir->row(0)->jurusan_id==$jur->id) {
								echo ": ".$jur->jurusan;
							}
						}
					}
					?>
				</td>
			</tr>
			
			
			<tr>
				<td>No Formulir</td>
				<td>
					<?php
					echo ": ".(isset($data_trx_formulir)?$data_trx_formulir->row(0)->no_formulir:'');
					echo "<input type='hidden' name='txtNoFormulir' id='txtNoFormulir' class='form-control input-sm' style='width:100%;' value='".(isset($data_trx_formulir)?$data_trx_formulir->row(0)->no_formulir:'')."' required readonly >";
					echo "<input type='hidden' name='txtIdFormulir' id='txtIdFormulir' class='form-control input-sm' style='width:100%;' value='".(isset($data_trx_formulir)?$data_trx_formulir->row(0)->id:'')."' required readonly >";
					?>
				</td>
			</tr>
			
			<!-- ###################################### TAB LIST ###################################### -->
			<tr>
				<td colspan=2>
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#tab_siswa" aria-controls="home" role="tab" data-toggle="tab" class='tab_click' title='tab_siswa' >Data Diri Siswa</a></li>
					<li role="presentation"><a href="#tab_pendidikan" aria-controls="profile" role="tab" data-toggle="tab" class='tab_click' title='tab_pendidikan' >Pendidikan</a></li>
					<li role="presentation"><a href="#tab_keluarga" aria-controls="messages" role="tab" data-toggle="tab" class='tab_click' title='tab_keluarga' >Anggota keluarga</a></li>
					<li role="presentation"><a href="#tab_hubkeluarga" aria-controls="settings" role="tab" data-toggle="tab" class='tab_click' title='tab_hubkeluarga' >Data keluarga</a></li>
					<li role="presentation"><a href="#tab_dana" aria-controls="settings" role="tab" data-toggle="tab" class='tab_click' title='tab_dana' >Sumber dana</a></li>
					<li role="presentation"><a href="#tab_pembayaran" aria-controls="settings" role="tab" data-toggle="tab" class='tab_click' title='tab_pembayaran' >Pembayaran</a></li>
				</ul>
				</td>
			</tr>
		</table>
		
			
		<form action="" method="POST" name="frmBioSiswa" id="frmBioSiswa" class="form-inline frm-all" style='display:none;' >
			<table class="table table-hover" id='tblBiodataSiswa' >
				
				<tbody role="tabpanel" class="tab-pane" id="tab_siswa">
					<tr>
						<td align='right' width='15%' >Nama Keluarga : </td>
						<td>
							<?php
							echo "<input type='text' name='txtNamaKeluarga' id='txtNamaKeluarga' class='form-control input-sm' style='width:100%;' value='".(isset($stat_edit)?$data_trx_biodata->row(0)->nama_keluarga:'')."' >";
							?>
						</td>
					</tr>
					
					<tr>
						<td align='right' >Nama Depan : </td>
						<td>
							<?php
							if(isset($stat_edit)) {
								echo "<input type='text' name='txtNamaDepan' id='txtNamaDepan' class='form-control input-sm' style='width:100%;' value='".$data_trx_biodata->row(0)->nama_depan."' required >";
							} else {
								echo "<input type='text' name='txtNamaDepan' id='txtNamaDepan' class='form-control input-sm' style='width:100%;' value='".(isset($data_trx_formulir)?$data_trx_formulir->row(0)->nama_calon:'')."' required >";
							}
							?>
						</td>
					</tr>
				
					<tr>
						<td align='right' >Kewarganegaraan : </td>
						<td>
							<select name='txtWarga' id='txtWarga' class='form-control input-sm' style='width:100%;' required >
							<?php
							echo "<option value='' >== Pilih Warga Negara ==</option>";
							if(isset($stat_edit)) {
								if($data_trx_biodata->row(0)->warga_negara=="WNI") {
									echo "<option value='WNI' selected >WNI</option>";
								} else {
									echo "<option value='WNI' >WNI</option>";
								}
								if($data_trx_biodata->row(0)->warga_negara=="WNA") {
									echo "<option value='WNA' selected >WNA</option>";
								} else {
									echo "<option value='WNA' >WNA</option>";
								}
							} else {
								echo "<option value='WNI' >WNI</option>";
								echo "<option value='WNI' >WNA</option>";
							}
							?>
							</select>
						</td>
					</tr>
					
					
					<tr>
						<td align='right' >Tempat / tgl Lahir : </td>
						<td>
							<?php
							if(isset($data_trx_formulir)) {
								$originalDate = $data_trx_formulir->row(0)->tgl_lahir;
								$new_tgl_lahir = date("d F Y", strtotime($originalDate));
								echo $data_trx_formulir->row(0)->tmp_lahir . ", " . $new_tgl_lahir;
							}
							?>
						</td>
					</tr>
					
					<tr>
						<td align='right' >Status Pernikahan : </td>
						<td>
							<select name='txtStatus' id='txtStatus' class='form-control input-sm' style='width:100%;' required >
							<?php
							echo "<option value='' >== Pilih Status Pernikahan ==</option>";
							if(isset($stat_edit)) {
								if($data_trx_biodata->row(0)->status_pernikahan=="Menikah") {
									echo "<option value='Menikah' selected >Menikah</option>";
								} else {
									echo "<option value='Menikah' >Menikah</option>";
								}
								if($data_trx_biodata->row(0)->status_pernikahan=="Belum menikah") {
									echo "<option value='Belum menikah' selected >Belum menikah</option>";
								} else {
									echo "<option value='Belum menikah' >Belum menikah</option>";
								}
							} else {
								echo "<option value='Menikah' >Menikah</option>";
								echo "<option value='Belum menikah' >Belum menikah</option>";
							}
							?>
							</select>
						</td>
					</tr>
					
					<tr>
						<td align='right' >No. KTP : </td>
						<td>
							<?php
							echo "<input type='text' name='txtKTP' id='txtKTP' class='form-control input-sm' style='width:100%;' value='".(isset($stat_edit)?$data_trx_biodata->row(0)->ktp:'')."' >";
							?>
						</td>
					</tr>
					
					<tr>
						<td align='right' >Pilih Agama : </td>
						<td>
							<select name='txtAgama' id='txtAgama' class='form-control input-sm' style='width:100%;' required >
							<?php
							echo "<option value='' >== Pilih Agama ==</option>";
							$arAgama = array('Buddha','Hindu','Islam','Katolik','Konghucu','Kristen','Lainnya');
							for($i=0; $i< count($arAgama); $i++) {
								if(isset($stat_edit)) {
									if($data_trx_biodata->row(0)->agama==$arAgama[$i]) {
										echo "<option selected value='".$arAgama[$i]."' >".$arAgama[$i]."</option>";
									} else {
										echo "<option value='".$arAgama[$i]."' >".$arAgama[$i]."</option>";
									}
								} else {
									echo "<option value='".$arAgama[$i]."' >".$arAgama[$i]."</option>";
								}
							}
							?>
							</select>
						</td>
					</tr>
					
					
					<tr>
						<td align='right' >Pendidikan terakhir : </td>
						<td>
							<select name='txtPendAkhir' id='txtPendAkhir' class='form-control input-sm' style='width:100%;' required >
							<?php
							echo "<option value='' >== Pilih Pendidikan Terakhir ==</option>";
							$arPenAkhir = array('SD','SMP','SMA','D1','D2','D3','S1','S2','S3');
							
							for($i=0; $i< count($arPenAkhir); $i++) {
								if(isset($stat_edit)) {
									if($data_trx_biodata->row(0)->pendidikan_terakhir==$arPenAkhir[$i]) {
										echo "<option selected value='".$arPenAkhir[$i]."' >".$arPenAkhir[$i]."</option>";
									} else {
										echo "<option value='".$arPenAkhir[$i]."' >".$arPenAkhir[$i]."</option>";
									}
								} else {
									echo "<option value='".$arPenAkhir[$i]."' >".$arPenAkhir[$i]."</option>";
								}
							}
							?>
							</select>
						</td>
					</tr>
					
					
					<tr>
						<td align='right' >Pekerjaan sekarang : </td>
						<td>
							<?php
							echo "<input type='text' name='txtPekerjaan' id='txtPekerjaan' class='form-control input-sm' style='width:100%;' value='".(isset($stat_edit)?$data_trx_biodata->row(0)->pekerjaan:'')."' >";
							?>
						</td>
					</tr>
					
					
					<tr>
						<td align='right' >Nama perusahaan sekarang : </td>
						<td>
							<?php
							echo "<input type='text' name='txtPerusahaan' id='txtPerusahaan' class='form-control input-sm' style='width:100%;' value='".(isset($stat_edit)?$data_trx_biodata->row(0)->nama_perusahaan:'')."' >";
							?>
						</td>
					</tr>
					
					<tr>
						<td align='right' >Alamat tempat tinggal : </td>
						<td>
							<?php
							echo (isset($data_trx_formulir)?$data_trx_formulir->row(0)->alamat:'');
							?>
						</td>
					</tr>
					
					
					<tr>
						<td align='right' >Telepon : </td>
						<td>
							<?php
							echo (isset($data_trx_formulir)?$data_trx_formulir->row(0)->telepon:'');
							?>
						</td>
					</tr>
					
					<tr>
						<td align='right' >HP : </td>
						<td>
							<?php
							echo (isset($data_trx_formulir)?$data_trx_formulir->row(0)->hp:'');
							?>
						</td>
					</tr>
					
					<tr>
						<td align='right' >Email : </td>
						<td>
							<?php
							echo (isset($data_trx_formulir)?$data_trx_formulir->row(0)->email:'');
							?>
						</td>
					</tr>
					
					
					<tr>
						<td align='right' >Masa kuliah di PAHOA COLLEGE INDONESIA : </td>
						<td>
							<?php
							echo "Dari : "; 
							echo "<input type='text' name='txtKuliahDari' id='txtKuliahDari' class='form-control input-sm' value='".(isset($stat_edit)?$data_trx_biodata->row(0)->kuliah_pci_dari:'')."' placeholder='format : yyyy-mm-dd' required > ";
							
							echo " Hingga : "; 
							echo "<input type='text' name='txtKuliahHingga' id='txtKuliahHingga' class='form-control input-sm' value='".(isset($stat_edit)?$data_trx_biodata->row(0)->kuliah_pci_sampai:'')."' placeholder='format : yyyy-mm-dd' required  >";
							?>
						</td>
					</tr>
					
					
					<tr>
						<td align='right' >Tingkat kemampuan berbahasa Mandarin</td>
						<td>
							<select name='txtTingkatMandarin' id='txtTingkatMandarin' class='form-control input-sm' style='width:100%;' required >
							<?php
							echo "<option value='' >== Pilih tingkat kemampuan ==</option>";
							$arKemMand = array('Tidak ada','HSK1','HSK2','HSK3','HSK4','HSK5','HSK6');
							
							for($i=0; $i< count($arKemMand); $i++) {
								if(isset($stat_edit)) {
									if($data_trx_biodata->row(0)->kemampuan_mandarin==$arKemMand[$i]) {
										echo "<option selected value='".$arKemMand[$i]."' >".$arKemMand[$i]."</option>";
									} else {
										echo "<option value='".$arKemMand[$i]."' >".$arKemMand[$i]."</option>";
									}
								} else {
									echo "<option value='".$arKemMand[$i]."' >".$arKemMand[$i]."</option>";
								}
							}
							?>
							</select>
						</td>
					</tr>
					
					<tr>
						<td align='right' >Program pendidikan</td>
						<td>
							<select name='txtProgramPend' id='txtProgramPend' class='form-control input-sm' style='width:100%;' required >
							<?php
							echo "<option value='' >== Pilih program pendidikan ==</option>";
							$arProgPend = array('D1','D2','D3','S1');
							
							for($i=0; $i< count($arProgPend); $i++) {
								if(isset($stat_edit)) {
									if($data_trx_biodata->row(0)->program_pendidikan==$arProgPend[$i]) {
										echo "<option selected value='".$arProgPend[$i]."' >".$arProgPend[$i]."</option>";
									} else {
										echo "<option value='".$arProgPend[$i]."' >".$arProgPend[$i]."</option>";
									}
								} else {
									echo "<option value='".$arProgPend[$i]."' >".$arProgPend[$i]."</option>";
								}
							}
							?>
							</select>
						</td>
					</tr>
					
					<tr>
						<td colspan='2' >
							<button type='submit' value='Simpan data diri siswa' class='btn btn-primary btn-block btn-sm' >
							<span class='glyphicon glyphicon-floppy-disk' aria-hidden='true'></span> Simpan data diri siswa
							</button>
						</td>
					</tr>
					
				</tbody>
				
			</table>		
		</form>
			
		
		<form action="" method="POST" name="frmBioPendidikan" id="frmBioPendidikan" class="form-inline frm-all" style='display:none;' >
			<table class="table table-hover" id='tblPendidikan' >
				
				<tbody role="tabpanel" class="tab-pane" id="tab_pendidikan" style='display:none;'  >
					<tr><td colspan='2' class="table-responsive" >
					
					<table class='table table-bordered'>
						<tr style='background-color:#333232;color:#ffffff;' >
							<th style='text-align:center;' >Riwayat pendidikan dan pengalaman kerja</th>
							<th style='text-align:center;' >Periode (dari / ke)</th>
							<th style='text-align:center;' >Jabatan</th>
						</tr>
						
						<tbody id='isi_tbl_pendidikan'>
						</tbody>
						
						<tr>
						<td>
							<button type="button" name='btnAddPend' id='btnAddPend' class="btn btn-primary btn-sm" title='Tambah baris pendidikan' >
							  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Tambah
							</button>
							<button type="button" name='btnRemovePend'  id='btnRemovePend' class="btn btn-danger btn-sm" title='Kurangi baris pendidikan' >
							  <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> Kurangi
							</button>
						</td>
						</tr>
						
					</table>
					
					</td></tr>
					
					<tr>
						<td colspan='2' >
							<button type='submit' value='Simpan Pendidikan' class='btn btn-primary btn-block btn-sm' >
							<span class='glyphicon glyphicon-floppy-disk' aria-hidden='true'></span> Simpan Pendidikan
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
		
		
		<form action="" method="POST" name="frmBioAnggota" id="frmBioAnggota" class="form-inline frm-all" style='display:none;' >
			<table class="table table-hover table-bordered" id='tblKeluarga' >
				<tbody role="tabpanel" class="tab-pane" id="tab_keluarga" style='display:none;' >
					
					<tr style='background-color:#333232;color:#ffffff;' >
						<th style='text-align:center;' >Nama</th>
						<th style='text-align:center;' >Hubungan</th>
						<th style='text-align:center;' >Telp. & Faks.</th>
					</tr>
					
					<tbody id='isi_tbl_keluarga'>
					</tbody>
					
					<tr>
					<td>
						<button type="button" name='btnAddKeluarga' id='btnAddKeluarga' class="btn btn-primary btn-sm" title='Tambah baris keluarga' >
						  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Tambah
						</button>
						<button type="button" name='btnRemoveKeluarga'  id='btnRemoveKeluarga' class="btn btn-danger btn-sm" title='Kurangi baris keluarga' >
						  <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> Kurangi
						</button>
					</td>
					</tr>
					
					<tr>
						<td colspan='3' >
							<button type='submit' value='Simpan Keluarga' class='btn btn-primary btn-block btn-sm' >
							<span class='glyphicon glyphicon-floppy-disk' aria-hidden='true'></span> Simpan Keluarga
							</button>
						</td>
					</tr>
					
				</tbody>
			</table>
		</form>
		
					
		
		<form action="" method="POST" name="frmHubkeluarga" id="frmHubkeluarga" class="form-inline frm-all" style='display:none;' >
			<table class="table table-hover table-bordered" id='tblHubkeluarga' >
				<tbody role="tabpanel" class="tab-pane" id="tab_hubkeluarga" style='display:none;' >
					<tr>
						<td align='center' colspan='2' style='background-color:#333333;color:#ffffff;' >
						Keluarga yang dapat dihubungi pada keadaan darurat selama di PAHOA COLLEGE INDONESIA
						</td>
					</tr>
					
					<tr>
						<td align='right' width='15%'  >* Nama : </td>
						<td><input type='text' class='form-control input-sm' name='txtNamaKel' id='txtNamaKel' style='width:100%;' value='<?php echo (isset($stat_edit)&&$data_trx_biokeluarga->num_rows()>0?$data_trx_biokeluarga->row(0)->nama:""); ?>' required ></td>
					</tr>
					
					<tr>
						<td align='right' >* Kewarganegaraan : </td>
						<td>
							<select class='form-control input-sm' name='txtWarganegaraKel' id='txtWarganegaraKel' style='width:100%;' required >
							<option value='' >== Pilih Warga Negara ==</option>
							<?php
							if(isset($stat_edit)) {
								if($data_trx_biokeluarga->row(0)->warga_negara=="WNI") {
									echo "<option value='WNI' selected >WNI</option>";
								} else {
									echo "<option value='WNI' >WNI</option>";
								}
								if($data_trx_biokeluarga->row(0)->warga_negara=="WNA") {
									echo "<option value='WNA' selected >WNA</option>";
								} else {
									echo "<option value='WNA' >WNA</option>";
								}
							} else {
								echo "<option value='WNI' >WNI</option>";
								echo "<option value='WNA' >WNA</option>";
							}
							?>
							</select>
						</td>
					</tr>
					
					<tr>
						<td align='right' >* Hubungan : </td>
						<td><input type='text' class='form-control input-sm' name='txtHubunganKel' id='txtHubunganKel' style='width:100%;' value='<?php echo (isset($stat_edit)&&$data_trx_biokeluarga->num_rows()>0?$data_trx_biokeluarga->row(0)->hubungan:""); ?>' required ></td>
					</tr>
					
					<tr>
						<td align='right' >* Alamat : </td>
						<td><textarea class='form-control input-sm' name='txtAlamatKel' id='txtAlamatKel' style='width:100%;' required ><?php echo (isset($stat_edit)&&$data_trx_biokeluarga->num_rows()>0?$data_trx_biokeluarga->row(0)->alamat:""); ?></textarea></td>
					</tr>
					
					<tr>
						<td align='right' >Nama atasan langsung : </td>
						<td><input type='text' class='form-control input-sm' name='txtAtasanKel' id='txtAtasanKel' style='width:100%;' value='<?php echo (isset($stat_edit)&&$data_trx_biokeluarga->num_rows()>0?$data_trx_biokeluarga->row(0)->nama_atasan:""); ?>' ></td>
					</tr>
					
					<tr>
						<td align='right' >Telepon rumah : </td>
						<td><input type='text' class='form-control input-sm' name='txtTelpKel' id='txtTelpKel' style='width:100%;' value='<?php echo (isset($stat_edit)&&$data_trx_biokeluarga->num_rows()>0?$data_trx_biokeluarga->row(0)->telepon_rumah:""); ?>' ></td>
					</tr>
					
					<tr>
						<td align='right' >Kantor : </td>
						<td><input type='text' class='form-control input-sm' name='txtTelpkantorKel' id='txtTelpkantorKel' style='width:100%;' value='<?php echo (isset($stat_edit)&&$data_trx_biokeluarga->num_rows()>0?$data_trx_biokeluarga->row(0)->telepon_kantor:""); ?>' ></td>
					</tr>
					
					<tr>
						<td align='right' >Faks : </td>
						<td><input type='text' class='form-control input-sm' name='txtFaksKel' id='txtFaksKel' style='width:100%;' value='<?php echo (isset($stat_edit)&&$data_trx_biokeluarga->num_rows()>0?$data_trx_biokeluarga->row(0)->telepon_faks:""); ?>' ></td>
					</tr>
					
					<tr>
						<td align='right' >Email : </td>
						<td><input type='email' class='form-control input-sm' name='txtEmailKel' id='txtEmailKel' style='width:100%;' value='<?php echo (isset($stat_edit)&&$data_trx_biokeluarga->num_rows()>0?$data_trx_biokeluarga->row(0)->email:""); ?>' ></td>
					</tr>
					
					<tr>
						<td colspan='2' >
							<button type='submit' value='Simpan Hubungan Keluarga' class='btn btn-primary btn-block btn-sm' >
							<span class='glyphicon glyphicon-floppy-disk' aria-hidden='true'></span> Simpan Hubungan Keluarga
							</button>
						</td>
					</tr>
					
				</tbody>
			</table>
		</form>
		
		
		<form action="" method="POST" name="frmBioDana" id="frmBioDana" class="form-inline frm-all" style='display:none;' >
			<table class="table table-hover table-bordered" id='tblDana' >
				<tbody role="tabpanel" class="tab-pane" id="tab_dana" style='display:none;' >
					<tr>
						<td align='right' width='15%' >* Sumber dana : </td>
						<td>
							<select class='form-control input-sm' name='txtSumberDana' id='txtSumberDana' style='width:100%;' required >
							<option value='' >== Pilih sumber dana ==</option>
							<?php
							$arDanaSumber = array('Biaya sendiri','Beasiswa','Biaya perusahaan');
							if(isset($stat_edit)) {
								for($a=0; $a<count($arDanaSumber); $a++) {
									if($data_trx_biodana->row(0)->sumber_dana==$arDanaSumber[$a]) {
										echo "<option value='".$arDanaSumber[$a]."' selected >".$arDanaSumber[$a]."</option>";
									} else {
										echo "<option value='".$arDanaSumber[$a]."' >".$arDanaSumber[$a]."</option>";
									}
								}
							} else {
								for($a=0; $a<count($arDanaSumber); $a++) {
									echo "<option value='".$arDanaSumber[$a]."' >".$arDanaSumber[$a]."</option>";
								}
							}
							?>
							</select>
						</td>
					</tr>
					
					<tr>
						<td colspan='2' >
							<button type='submit' value='Simpan Sumber Dana' class='btn btn-primary btn-block btn-sm' >
							<span class='glyphicon glyphicon-floppy-disk' aria-hidden='true'></span> Simpan Sumber Dana
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
		
		
		<form action="" method="POST" name="frmBioPmbyr" id="frmBioPmbyr" class="form-inline frm-all" style='display:none;' >
			<table class="table table-hover table-bordered" id='tblpmbyr' >
				<tbody role="tabpanel" class="tab-pane" id="tab_pembayaran" style='display:none;' >
					<tr><td colspan='2'>
					
						<table class='table table-hover' >
						
						<tr>
							<td align='right' width='15%' >Jenis pembayaran : </td>
							<td colspan='2'>
								<select class='form-control input-sm' name='txtJenisPembayaran' id='txtJenisPembayaran' style='width:100%;' required >
								<option value='' >== Pilih Jenis Pembayaran ==</option>
								<option value='Cash' >Cash</option>
								<option value='Kredit' >Kredit</option>
								</select>
							</td>
						</tr>
						
						<tbody id='isi_detail_pembayaran' style='display:none;' >
						</tbody>
						
						<tr style='background-color:#A6B6ED;color:#ffffff;' >
						<td align='right'>Total cicilan : </td>
						<td colspan='2' >
							<label name='txtHasilTotCicilan' id='txtHasilTotCicilan' >0</label>
						</td>
						</tr>
						
						
						<tr id='btnAddRemoveCicil' style='display:none;' >
							<td></td>
							<td>
								<button type="button" name='btnAddBayar' id='btnAddBayar' class="btn btn-primary btn-sm" title='Tambah cicilan' >
								  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Tambah
								</button>
								<button type="button" name='btnRemoveBayar'  id='btnRemoveBayar' class="btn btn-danger btn-sm" title='Kurangi cicilan' >
								  <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> Kurangi
								</button>
							</td>
						</tr>
						
						<tr>
							<td align='right' >Tagihan uang pangkal : </td>
							<td colspan='2'>
								<input type='text' class='form-control input-sm' name='txtUangPangkal' id='txtUangPangkal' style='width:100%;' required >
							</td>
						</tr>
						
						<tr>
							<td align='right' >Tagihan uang semester : </td>
							<td colspan='2'>
								<input type='text' class='form-control input-sm' name='txtUangSemester' id='txtUangSemester' style='width:100%;' required >
							</td>
						</tr>
						
						<tr style='background-color:#EF4F4F;color:#ffffff;' >
							<td align='right' >Total Tagihan : </td>
							<td colspan='2'>
								<label name='lblTotalTagihan' id='lblTotalTagihan' >0</label>
							</td>
						</tr>
						
						</table>
					
					</td></tr>
					
					<tr>
						<td colspan='2' >
							<button type='submit' value='Simpan Pembayaran' class='btn btn-primary btn-block btn-sm' >
							<span class='glyphicon glyphicon-floppy-disk' aria-hidden='true'></span> Simpan Pembayaran
							</button>
						</td>
					</tr>
				</tbody>
				
				
			</table>
		</form>
		
		
	</div>
	
</div>