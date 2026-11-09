<?php
mysql_connect("localhost","root","pahoa");
mysql_select_db("pmb");


$q = "SELECT * FROM set_makul_kelas";
$query = mysql_query($q);
while($row = mysql_fetch_array($query)) {
	if(substr($row['kode_makul'],0,3)=="PMB") {
		echo $row['kode_makul'];
		$changeto = "PBM " . substr($row['kode_makul'],-3);
		echo " =>> " . $changeto;
		$cQuery = mysql_query("UPDATE set_makul_kelas SET kode_makul='".$changeto."' WHERE kode_makul='".$row['kode_makul']."'");
		if($cQuery) {
			echo " (DONE)";
		} else {
			echo mysql_error();
		}
		
		echo "<br>";
	}
}

/*
$q = "SELECT * FROM ms_matakuliah";
$query = mysql_query($q);
while($row = mysql_fetch_array($query)) {
	if(substr($row['kdmk'],0,3)=="PMB") {
		echo $row['kdmk'];
		
		$changeto = "PBM " . substr($row['kdmk'],-3);
		echo " =>> " . $changeto;
		$cQuery = mysql_query("UPDATE ms_matakuliah SET kdmk='".$changeto."' WHERE kdmk='".$row['kdmk']."'");
		if($cQuery) {
			echo " (DONE)";
		} else {
			echo mysql_error();
		}
		
		echo "<br>";
	}
}
*/
?>