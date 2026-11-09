<script>
	$(document).ready(function() {
		$(".btn-approve").click(function() {
			var userid = $(this).attr("alt");
			$.post("Ms_aktifasi_ctr/aktifasi_user/", {
				userid:userid
			}, function(data, status) {
				$("#content_adm").load("Ms_aktifasi_ctr");
			});
		});
		
		$(".btn-active").click(function() {
			var status = $(this).val();
			var userid = $(this).attr("alt");
			
			$.post("Ms_aktifasi_ctr/block_user/", {
				status:status,
				userid:userid
			}, function(data, status) {
				//alert(data);
				$("#content_adm").load("Ms_aktifasi_ctr");
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
Aktifasi user
</div>

<div class="isi-popup" >
	<div class="table-responsive">
		<form action="" method="POST" name="frmAktifasi" id="frmAktifasi" >
			<table class="table table-hover" >
				<tr align='center' class='header-popup-tbl' >
					<td>Username</td>
					<td>Nama</td>
					<td>Dept</td>
					<td>Jabatan</td>
					<td>Tgl Daftar</td>
					<td width='10%'>Approve</td>
					<td width='10%'>Status</td>
				</tr>
				
				<?php
				foreach($data_non_verfied->result() as $obj) {
					echo "<tr>";
					echo "<td>".$obj->username."</td>";
					echo "<td>".$obj->nama."</td>";
					echo "<td>".$obj->nama_dept."</td>";
					echo "<td>".$obj->jabatan."</td>";
					echo "<td>".$obj->create_date."</td>";
					echo "<td>";
						if($obj->approve_by>0) {
							echo "<label style='color:green;' >
							<span class='glyphicon glyphicon-ok-circle' aria-hidden='true'></span>
							 Verified
							</label>";
						} else {
							echo "<input type='button' value='Approve' class='newbtn btn-approve' alt='".$obj->id."' title='Approve this user' >";
						}
					echo "</td>";
					echo "<td><input type='button' value='".($obj->active==1?"Aktif":"Non-Aktif")."' alt='".$obj->id."' class='newbtn btn-active' title='".($obj->active==1?"Non-aktifkan user ini":"Aktifkan user ini")."' ></td>";
					echo "</tr>";
				}
				?>
				
				<tr><td colspan='7'></td></tr>
				
			</table>
		</form>
	</div>
	
</div>