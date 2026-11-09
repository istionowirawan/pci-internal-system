<script>
	$(document).ready(function() {
		
		$("#data_siswa_detail").load("Ms_namamand_ctr/tampil_siswa_nim/"+$("#txtFak").val()+"/"+$("#txtJur").val()+"/"+$("#txtTa").val(), function() {
			$("#btnSimpan").click(function() {
				
				$("#divLoading").show();
				
				$("#frmNim").submit(function() {
					var dataform = $(this).serialize();
					//alert(dataform);
					
					$.ajax({
						type: "POST",
						url: "Ms_namamand_ctr/simpan_namamand/",
						data: dataform,
						success: function(data, status) {
							//alert(data);
							if(data==1) {
								alert("Simpan Sukses!")
							} else {
								alert("Simpan Gagal!")
							}
							$("#content_adm").load("Ms_namamand_ctr");
						}
					});
					
					return false;
				});	
			});
		});
		
	});
</script>
				

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>

<div class='header-popup' >
Master Nama Tionghoa dan No.Telp ( <?php echo $ta_pci_active; ?> )
</div>

<div id='divLoading' style='width:100%;position:absolute;height:100%;background-color:#000000;color:#ffffff;opacity: 0.5;vertical-align: middle;font-size:30px;display:none;' >
	<label style="margin-top:50%;" >Saving...</label>
</div>

<div class="isi-popup" >
	<div class="table-responsive">
		<form action="" method="POST" name="frmNim" id="frmNim" >
			
			<div id='data_siswa_detail' ></div>
		
		</form>
	</div>
	
</div>