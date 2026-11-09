<script>
	$(document).ready(function() {
		$("#filter_kriteria").focus();
		
		$(".btnHapus").click(function() {
			var q = confirm("Hapus Data ?");
			if(q) {
				var dataform = "idform=" + $(this).attr("alt");
				
				$.ajax({
					type: "POST",
					url: "Trx_biodata_ctr/hapus_biodata_trx/",
					data: dataform,
					success: function(data, status) {
						var hasil="Hapus Gagal";
						if(data==1) {
							hasil="Hapus Berhasil";
						}
						alert(hasil);
						
						$("#content_adm").load("Trx_biodata_ctr/tampil_data_biodata/1");
					}
				});
			}
		});
		
		$("#btnBack").click(function() {
			$("#content_adm").load("Trx_biodata_ctr/index");
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
				$("#content_adm").load("Trx_biodata_ctr/tampil_data_biodata/0/"+$("#filter_kriteria").val()+"/"+$("#filter_cari").val());
			}
		});
		
		$("#frm_dataBiodata").submit(function() {
			$("#filter_btn").click();
			return false;
		});
		
		$(".btnEdit").click(function() {
			var formid = $(this).attr("alt");
			$("#content_adm").load("Trx_biodata_ctr/edit_biodata_trx/"+formid, function() {
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
		
		
		$(".btnDetPend").click(function(){
			var idform = $(this).attr("alt");
			$(".modal-body").load("Trx_biodata_ctr/show_bio_pend/"+idform);
		});
		$(".btnDetAnggota").click(function(){
			var idform = $(this).attr("alt");
			$(".modal-body").load("Trx_biodata_ctr/show_bio_anggota/"+idform);
		});
		$(".btnDetKeluarga").click(function() {
			var idform = $(this).attr("alt");
			$(".modal-body").load("Trx_biodata_ctr/show_bio_keluarga/"+idform);
		});
		$(".btnDetPembayaran").click(function(){
			var idform = $(this).attr("alt");
			$(".modal-body").load("Trx_biodata_ctr/show_bio_pembayaran/"+idform);
		});
		
		$(".halBiodata").click(function() {
			var nomorHal = $(this).attr("alt");
			$("#content_adm").load("Trx_biodata_ctr/tampil_data_biodata/"+nomorHal);
		});
		
	});
</script>
				
<style>
.halBiodata {
	cursor:pointer;
}
.halBiodata:hover {
	text-decoration:underline;
}
</style>

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>

<div class='header-popup' >
Tampil Data Biodata Siswa
</div>

<div class="modal fade modal_detail_biodata" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg">
	
	<div class="modal-content" style='color:#000000;' >
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">Detail Biodata</h4>
		</div>
		<div class="modal-body">
		</div>
    </div>
	
  </div>
</div>

<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;height:510px;' >
		<!--<button id='btnBack' class='btn btn-warning' >
			<span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> 
			Back
		</button>
		<br><br>-->
		
		<form action="" method="POST" name="frm_dataBiodata" id="frm_dataBiodata" class="form-inline" >
			<div class="form-group">
			Filter : 
			<select id='filter_kriteria' class='form-control input-sm' >
			<option value='all' >All</option>
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
		
		<table class="table table-hover table-bordered  table-striped" id='tbl_dataQuestioner' >
			
			<tr id='txtInfoBox' style='display:none;' >
				<td colspan='2' style='background-color:red;text-align:center;color:#ffffff;' >
					<span class="glyphicon glyphicon-warning-sign" aria-hidden="true" ></span> 
					<label id='txtInfoMsg' style='text-weight:bold;' >Warning</label>
				</td>
			</tr>
			
			
			<tr align='center' style='background-color:#7C88AF;color:#ffffff;' >
				<?php
				if($this->session->userdata('pmb_dept')==1) {
					echo "<td>Edit / hapus</td>";
				}
				
				foreach($header_trx->result() as $head) {
					if($head->Field!="id" && $head->Field!="user_input" && $head->Field!="id_formulir") {
						echo "<td>".$head->Field."</td>";
					}
				}
				echo "<td>Sumber dana</td>";
				
				echo "<td>pendidikan</td>";
				echo "<td>Anggota keluarga</td>";
				echo "<td>Kel.yg bisa di hub</td>";
				echo "<td>Pembayaran</td>";
				?>
			</tr>
			
			
			<?php
			$jmlhal = floor($jmlhal_trxBiodata/$show_row) ;
			
			echo "<nav style='margin:0px;padding:0px;'>
			  <ul class='pagination'>
				<li>
				  <a class='halBiodata' alt='".($hal_active>0?$hal_active-1:0)."' aria-label='Previous'>
					<span aria-hidden='true'>&laquo;</span>
				  </a>
				</li>";
				
				$halAkhir = 5;
				$halAwal = 0;
				if($hal_active<3) {
					$halAwal=0;
					if($jmlhal<5) {
						$halAkhir=$jmlhal;
					} else {
						$halAkhir=5;
					}
				} else if ($hal_active>$jmlhal-4) {
					$halAwal=$jmlhal-4;
					$halAkhir=$jmlhal;
				} else {
					$halAwal=$hal_active-2;
					$halAkhir=$hal_active+2;
				}
				
				for($a = $halAwal; $a <= $halAkhir; $a++) {
					echo "<li class=".($hal_active==$a?'active':'')."><a class='halBiodata' alt='".$a."' > ".($a+1)." </a></li>"; 				
				}
				
			echo "
				<li>
				  <a class='halBiodata' alt='".($hal_active<$jmlhal?$hal_active+1:$jmlhal)."' aria-label='Next'>
					<span aria-hidden='true'>&raquo;</span>
				  </a>
				</li>
			  </ul>
			</nav>";
			
			echo "<span style='position:relative;padding-top:0px;left:50%;'>Jumlah Data : " . $jmlhal_trxBiodata; 
			echo " ( page ".($hal_active+1)." of ".($jmlhal+1)." ) </span>";
		
			foreach($data_trxBiodata->result() as $row) {
				echo "<tr align='center'>";
				
				if($this->session->userdata('pmb_dept')==1) {
					echo "<td><input type='button' value='Edit' class='btn btn-primary btnEdit btn-xs btn-block' alt='".$row->id_formulir."' title='Edit".$row->id_formulir."' >
					<input type='button' value='hapus' class='btn btn-danger btnHapus btn-xs' alt='".$row->id_formulir."' title='Hapus' qid='".$row->id_formulir."' ></td>";
				}
				
				echo "<td>".$row->no_formulir."</td>";
				echo "<td>".$row->nim."</td>";
				echo "<td>".$row->nama_keluarga."</td>";
				echo "<td>".$row->nama_depan."</td>";
				echo "<td>".$row->nama_tionghoa."</td>";
				echo "<td>".$row->telp_baru."</td>";
				echo "<td>".$row->warga_negara."</td>";
				echo "<td>".$row->status_pernikahan."</td>";
				echo "<td>".$row->ktp."</td>";
				echo "<td>".$row->agama."</td>";
				echo "<td>".$row->pendidikan_terakhir."</td>";
				echo "<td>".$row->pekerjaan."</td>";
				echo "<td>".$row->nama_perusahaan."</td>";
				echo "<td>".$row->kuliah_pci_dari."</td>";
				echo "<td>".$row->kuliah_pci_sampai."</td>";
				echo "<td>".$row->kemampuan_mandarin."</td>";
				echo "<td>".$row->program_pendidikan."</td>";
				echo "<td>".$row->date_input."</td>";
				echo "<td>".$row->sumber_dana."</td>";
				
				echo "<td><input type='button' value='Pend' class='btn btn-sm btn-primary btnDetPend' alt='".$row->id_formulir."' title='Detail ".$row->no_formulir."' data-toggle='modal' data-target='.modal_detail_biodata' ></td>";
				echo "<td><input type='button' value='Anggota' class='btn btn-sm btn-primary btnDetAnggota' alt='".$row->id_formulir."' title='Detail ".$row->no_formulir."' data-toggle='modal' data-target='.modal_detail_biodata' ></td>";
				echo "<td><input type='button' value='Keluarga' class='btn btn-sm btn-primary btnDetKeluarga' alt='".$row->id_formulir."' title='Detail ".$row->no_formulir."' data-toggle='modal' data-target='.modal_detail_biodata' ></td>";
				echo "<td><input type='button' value='Pembayaran' class='btn btn-sm btn-primary btnDetPembayaran' alt='".$row->id_formulir."' title='Detail ".$row->no_formulir."' data-toggle='modal' data-target='.modal_detail_biodata' ></td>";
				
				echo "</tr>";
			}
			?>
			
		</table>
		
		
	</div>
	
</div>


