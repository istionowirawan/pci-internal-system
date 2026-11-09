<script>
	$(document).ready(function() {
		
	});
</script>

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>

<div class='header-popup' >
Detail Biodata Siswa <?php echo $jenis_detail; ?>
</div>


<div class="isi-popup" >
	<div class="table-responsive" style='text-align:left;' >
		
		<table class="table table-hover table-bordered" id='tbl_dataQuestioner' >
			
			<tr style='background-color:#3B53A4;color:#ffffff;text-align:center;' >
			<?php
			if($detail_biodata->num_rows()>0) {
				echo "No formulir : " . $detail_biodata->row(0)->no_formulir;
			}
			foreach($head_detail->result() as $head) {
				if($head->Field=="id" || $head->Field=="id_formulir" || $head->Field=="user_input" || $head->Field=="no_formulir") {
				} else {
					echo "<td>".$head->Field."</td>";
				}
			}
			?>
			</tr>
			
			<tr style='background-color:#3B53A4;color:#ffffff;text-align:center;' >
			<?php
			foreach($detail_biodata->result() as $rows) {
				echo "<tr align='center' >";
				foreach($head_detail->result() as $head2) {
					if($head2->Field=="id" || $head2->Field=="id_formulir" || $head2->Field=="user_input" || $head2->Field=="no_formulir") {
					} else {
						$namafield = $head2->Field;
						if($head2->Type=="decimal(10,0)") {
							echo "<td>".number_format($rows->$namafield)."</td>";
						} else {
							echo "<td>".$rows->$namafield."</td>";
						}
					}
				}
				echo "</tr>";
			}
			?>
			</tr>
			
		</table>
		
	</div>
</div>