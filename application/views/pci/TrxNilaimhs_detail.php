<script>
	$(document).ready(function() {
		
		$(".hitung_na").change(function() {
			var nim = $(this).attr("alt");
			hitung_na(nim);
		});
		
	});
	
	function hitung_na(nim) {
		var hitungRata = 0;
		
		hitungRata += Number($("#txtTugas"+nim).val()) * Number($("#txtPrcTugas"+nim).val()/100);
		hitungRata += Number($("#txtUTP"+nim).val()) * Number($("#txtPrcUTP"+nim).val()/100);
		hitungRata += Number($("#txtUAP"+nim).val()) * Number($("#txtPrcUAP"+nim).val()/100);
		hitungRata += Number($("#txtAbsenMhs"+nim).val()) * Number($("#txtPrcAbsensi"+nim).val()/100);
		
		$("#lblNA"+nim).text(hitungRata.toFixed(2));
		$("#txtNA"+nim).val($("#lblNA"+nim).text());
		
		var gradeMhs = "E";
		if(hitungRata>=arGrade[0].range_from) {
			gradeMhs="A";
		} else if (hitungRata>=arGrade[1].range_from) {
			gradeMhs="B";
		} else if (hitungRata>=arGrade[2].range_from) {
			gradeMhs="C";
		} else if (hitungRata>=arGrade[3].range_from) {
			gradeMhs="D";
		} else {
			gradeMhs="E";
		}
		
		$("#lblGrade"+nim).text(gradeMhs);
		$("#txtGrade"+nim).val($("#lblGrade"+nim).text());
	}
	
	
</script>
				

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
$jmlMeet = 0;
$percentAbsen = 0;
$percentTgs = 0;
$percentUTS = 0;
$percentUAS = 0;
if($jml_pertemuan_makul->num_rows()>0) {
	foreach($jml_pertemuan_makul->result() as $row) {
		$jmlMeet = $row->pertemuan;
		$percentAbsen = $row->absensi;
		$percentTgs = $row->tugas;
		$percentUTS = $row->utp;
		$percentUAS = $row->uap;
	}
}

echo "<script>";
echo "var arGrade=new Array();";
echo "</script>";
foreach($data_grade->result() as $grd) {
	//echo $grd->grade;
	//echo $grd->range_from;
	//echo $grd->range_to;
	
	echo "<script>";
	echo "arGrade.push({'grade':'".$grd->grade."','range_from':".$grd->range_from.",'range_to':".$grd->range_to."});";
	echo "</script>";
}

?>
		
<form action="" method="POST" name="frmTrxNilaiDetail" id="frmTrxNilaiDetail" style='display:none;' >
	<table class="table table-bordered table-striped" >
		<tr align='center' class='header-popup-tbl' style='background-color:#6071C6;' >
			<td>Nip Peserta Didik</td>
			<td>Nama Peserta Didik</td>
			<td width='100px'>Tugas<br>(<?php echo $percentTgs;?>%)</td>
			<td width='100px'>UTP<br>(<?php echo $percentUTS;?>%)</td>
			<td width='100px'>UAP<br>(<?php echo $percentUAS;?>%)</td>
			<td width='100px'>Absensi <br>(<?php echo $percentAbsen;?>%)</td>
			<td width='80px'>Nilai Akhir</td>
			<td width='80px'>Grade</td>
		</tr>
		
		<?php
		$persen=0;
		$statDataLama=false;
		$statPosting=false;
		$idNilaiHeader=0;
		foreach($datasiswa->result() as $obj) {
			
			$isiTugas="";
			$isiUTP="";
			$isiUAP="";
			$isiAbsenMhs="";
			$isiNA=0;
			
			foreach($isi_nilai->result() as $nil) {
				if($nil->nim_mahasiswa == $obj->nim_mhs) {
					$isiTugas = $nil->tugas;
					$isiUTP = $nil->utp;
					$isiUAP = $nil->uap;
					$statDataLama=true;
					
					if($nil->stat_posting==1) {
						$statPosting=true;
						$idNilaiHeader=$nil->id;
					}
					break;
				}
			}
			
			if($statPosting==false) {
				echo "<tr>";
				
				echo "<td align='center' >".$obj->nim_mhs."<input type='hidden' name='txtNim[]' id='txtNim".$obj->nim_mhs."' value='".$obj->nim_mhs."' ></td>";
				echo "<td align='center' >".$obj->nama_depan."</td>";
				
				echo "<input type='hidden' class='form-control' name='txtPrcTugas[]' alt='".$obj->nim_mhs."' id='txtPrcTugas".$obj->nim_mhs."' value='".$percentTgs."' maxlength='3' >";			
				echo "<input type='hidden' class='form-control' name='txtPrcUTP[]' alt='".$obj->nim_mhs."' id='txtPrcUTP".$obj->nim_mhs."' value='".$percentUTS."' maxlength='3' >";
				echo "<input type='hidden' class='form-control' name='txtPrcUAP[]' alt='".$obj->nim_mhs."' id='txtPrcUAP".$obj->nim_mhs."' value='".$percentUAS."' maxlength='3' >";
				echo "<input type='hidden' class='form-control' name='txtPrcAbsensi[]' alt='".$obj->nim_mhs."' id='txtPrcAbsensi".$obj->nim_mhs."' value='".$percentAbsen."' maxlength='3' >";
				
				echo "<td><input type='text' class='form-control hitung_na' name='txtTugas[]' alt='".$obj->nim_mhs."' id='txtTugas".$obj->nim_mhs."' value='".$isiTugas."' maxlength='5' ></td>";			
				echo "<td><input type='text' class='form-control hitung_na' name='txtUTP[]' alt='".$obj->nim_mhs."' id='txtUTP".$obj->nim_mhs."' value='".$isiUTP."' maxlength='5' ></td>";
				echo "<td><input type='text' class='form-control hitung_na' name='txtUAP[]' alt='".$obj->nim_mhs."' id='txtUAP".$obj->nim_mhs."' value='".$isiUAP."' maxlength='5' ></td>";
				
				$persen=0;
				echo "<td align='center'>";
				foreach($isi_absensi->result() as $row) {
					if($row->nim_mahasiswa==$obj->nim_mhs) {
						$totTidakHadir = $row->sakit + $row->ijin + $row->alpha;
						$persen = round((($jmlMeet-$totTidakHadir)/$jmlMeet)*100,0);
						echo $persen;
						break;
					}
				}
				echo "<input type='hidden' class='form-control' name='txtAbsenMhs[]' id='txtAbsenMhs".$obj->nim_mhs."' value='".$persen."' >";
				echo "</td>";
				
				/*
				$isiNA += $isiTugas * ($percentTgs/100);
				$isiNA += $isiUTP * ($percentUTS/100);
				$isiNA += $isiUAP * ($percentUAS/100);
				$isiNA += ($persen * ($percentAbsen/100));
				*/
				
				echo "<td align='center'>";
					echo "<input type='hidden' class='form-control' name='txtNA[]' id='txtNA".$obj->nim_mhs."' value='".$isiNA."' >";
					echo "<label name='lblNA[]' id='lblNA".$obj->nim_mhs."' ></label>";
				echo "</td>";
				
				echo "<td align='center'>";
					echo "<input type='hidden' class='form-control' name='txtGrade[]' id='txtGrade".$obj->nim_mhs."' value='' >";
					echo "<label name='lblGrade[]' id='lblGrade".$obj->nim_mhs."' ></label>";
				echo "</td>";
				
				echo "</tr>";
				
				echo "<script>";
				echo "hitung_na(".$obj->nim_mhs.");";
				echo "</script>";
			}
		}
		
		if($statPosting==false) {
			echo "<tr align='center'>";
			echo "<td colspan='8' >";
			if($percentUTS==0 || $percentUAS==0) {
				echo "<div class='alert alert-danger' role='alert'>Persentase UTS atau UAP tidak boleh nol</div>";
			} else {
				echo "<input type='button' class='form-control btn btn-danger btn-sm' id='btnSimpan' value='Simpan' style='height:50px;' ></td>";
			}
			echo "</tr>";
		} else {
			echo "<tr>";
			echo "<td colspan='8' align='center'>
			Data telah di Posting / Commit, dan tidak dapat dirubah!<br><br>
			<img src='image/commit_stamp.jpg' width='200' height='100' >
			<br><br> Terima kasih :)
			";
			
			if($this->session->userdata('pmb_dept')==4 && $this->session->userdata('pmb_jabatan')<=5) { 
				echo "
				<hr>
				<input type='button' class='btn btn-danger' value='.:: Undo Posting ::.' id='btnUndoPosting' title='Id Header Nilai : ".$idNilaiHeader."' alt='".$idNilaiHeader."' >
				</td>";
				echo "</tr>";
			}
		}
		
		echo "<input type='hidden' value='".$trx_nilai_id."' name='txtTrxId' id='txtTrxId' >";
			
		if($statDataLama==true && $statPosting==false) {
			echo "<tr align='center'>";
			echo "<td colspan='8' ><input type='button' class='form-control btn btn-warning btn-sm' id='btnPosting' value='COMMIT / POSTING' title='Header Trx Id : ".$trx_nilai_id."' ></td>";
			echo "</tr>";
		}
		?>
	</table>
</form>