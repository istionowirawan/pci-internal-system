<script>
function printDiv(divName) {
	 var printContents = document.getElementById(divName).innerHTML;
	 var originalContents = document.body.innerHTML;

	 document.body.innerHTML = printContents;
	 window.print();
	 document.body.innerHTML = originalContents;
}
</script>
<div class='header-popup' >
Data Mahasiswa Batal Masuk
</div>

<div class="isi-popup" >
	<p align='right' >
	<button type="button" class="btn btn-default" aria-label="Left Align" onclick="printDiv('isi_Mhs_batal')" >
	  <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
	</button>
	</p>
	
	<div class="table-responsive" style='text-align:left;' id="isi_Mhs_batal" >
		
		<table class="table table-hover table-bordered" id='tblDataMhsBatal' >			
			Tahun Ajaran : <?php echo $ta_active->tahun; ?><br>
			Jumlah Data : <?php echo $mhs_batal->num_rows(); ?><br><br>
			
			<tr align='center' style='background-color:blue;color:#ffffff;' >
			<td>No.</td>
			<td>No Form</td>
			<td>Nama</td>
			<td>Alasan</td>
			<td>Tanggal Input</td>
			</tr>
			
			<?php
			$nomor=1;
			foreach($mhs_batal->result() as $row) {
				echo "<tr align='center' >
				<td>".$nomor."</td>
				<td>".$row->noform."</td>
				<td>".$row->nama_calon."</td>
				<td>".$row->alasan."</td>
				<td>".$row->date_input."</td>
				</tr>";
				$nomor++;
			}
			?>
			
		</table>
	</div>
	
</div>