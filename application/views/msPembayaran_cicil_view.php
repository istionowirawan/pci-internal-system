<script>
	$(document).ready(function() {
		$(".btnEditByrCicil").click(function() {
			var idcicil = $(this).attr("alt");
			
			if($(this).val()=="Edit") {
				$(this).val('Update');
				$("#txtHrgCicil"+idcicil).show(500);
				$("#lblHrgCicil"+idcicil).hide();
			} else {
				var idcicilstr = "idcicil=" + idcicil;
				idcicilstr += "&hrgcicil="+$("#txtHrgCicil"+idcicil).val();
				
				$.ajax({
					type: "POST",
					url: "Ms_pembayaran_ctr/update_ms_cicilan/",
					data: idcicilstr,
					success: function(data, status) {
						if(data==1) {
							$("#btnEditCicil"+idcicil).val('Edit');
							$("#txtHrgCicil"+idcicil).hide();
							$("#lblHrgCicil"+idcicil).show(500);
							$("#lblHrgCicil"+idcicil).text(addCommas($("#txtHrgCicil"+idcicil).val()));
						}
					}
				});
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
	});
</script>

<?php
echo "
<p align='left'>Tahun ajaran : ".$data_ms_pembayaran_cicil->row(0)->tahun_ajaran."</p>
<table class='table table-bordered' >
<tr style='background-color:#333333;color:#ffffff;font-weight:bold;' >
<td>Kredit No.</td>
<td width='20%;'>Harga Cicilan</td>
<td>Set date</td>
<td>Edit</td>
</tr>";
	
foreach($data_ms_pembayaran_cicil->result() as $row) {
	echo "<tr>
	<td>".$row->no_cicil."</td>
	<td>".
		"<label id='lblHrgCicil".$row->id."'>".number_format($row->harga_cicil)."</label>
		<input type='text' value='".$row->harga_cicil."' id='txtHrgCicil".$row->id."' style='display:none;width:100%;' class='form-control' ></td>
	<td>".$row->date_input."</td>
	<td><input type='button' value='Edit' class='btn btn-danger btn-block btnEditByrCicil btn-sm' id='btnEditCicil".$row->id."' alt='".$row->id."' thn='".$row->tahun_ajaran."' ></td>
	</tr>";
}

echo "</table>";
?>