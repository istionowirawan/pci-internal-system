<script>
	$(document).ready(function() {
		$(".clsViewCicil").click(function() {
			
			$("#btnPopup").click();
			var thnajr = $(this).attr("alt");
			$("#modal_content_body").load("Ms_pembayaran_ctr/view_cicil_detail/"+thnajr);
				
		});
		
		$(".clsEditCicil").click(function() {
			var thnajr = $(this).attr("alt");
			if($(this).val()=="Edit") {
				$(this).val('Update');
				$("#txtUangPangkal"+thnajr).show(500);
				$("#lblUangPangkal"+thnajr).hide();
				
				$("#txtUangSemester"+thnajr).show(500);
				$("#lblUangSemester"+thnajr).hide();
				
				$("#txtUangPangkal"+thnajr).focus();
				
				
			} else {
				$(this).val('Edit');
				$("#txtUangPangkal"+thnajr).hide();
				$("#lblUangPangkal"+thnajr).show(500);
				
				$("#txtUangSemester"+thnajr).hide();
				$("#lblUangSemester"+thnajr).show(500);
				
				var isiform = "thnajr=" + thnajr;
				isiform += "&txtUangPangkal="+$("#txtUangPangkal"+thnajr).val();
				isiform += "&txtUangSemester="+$("#txtUangSemester"+thnajr).val();
				
				$.ajax({
					type: "POST",
					url: "Ms_pembayaran_ctr/update_ms_pembayaran/",
					data: isiform,
					success: function(data, status) {
						if(data==1) {
							$("#lblUangPangkal"+thnajr).text(addCommas($("#txtUangPangkal"+thnajr).val()));
							$("#lblUangSemester"+thnajr).text(addCommas($("#txtUangSemester"+thnajr).val()));
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
				
<style>

</style>

<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/back_to_login'</script>";
}
?>

<div class='header-popup' >
Master Pembayaran
</div>

<div class="modal fade data_pembayaran" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" >
		
		<div class="modal-header" style='text-align:left;background-color:#A6B6ED;' >
			<button id='btnCloseModal' type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel" ><b>Detail Cicilan</b></h4>
		</div>
		
		<div id='modal_content_body' class="modal-body" style='color:#000000;' >
			Loading...
		</div>
		
    </div>
  </div>
</div>

<div class="isi-popup" >

	<div class="table-responsive" style='text-align:left;' >
		<button id='btnPopup' class='btn btn-warning' data-toggle="modal" data-target=".data_pembayaran" style='display:none;' >Ambil Data</button>
		
		<table class="table table-bordered table-condensed" id='tblFormulir' >
			<tr style='background-color:#4E64BD;color:#ffffff;text-align:center;'>
			<td>Tahun ajaran</td>
			<td>Uang pangkal</td>
			<td>Uang semester</td>
			<td>Tanggal setting</td>
			<td style='width:10%;' >Detail Cicilan</td>
			<td style='width:10%;'>Edit</td>
			</tr>
			
			<?php
			foreach($data_ms_pembayaran->result() as $row) {
				echo "<tr style='text-align:center;' >
				<td>".$row->tahun_ajaran."</td>
				<td width='15%'>".
					"<label id='lblUangPangkal".$row->tahun_ajaran."' >".number_format($row->uang_pangkal)."</label>
					<input type='text' class='form-control' id='txtUangPangkal".$row->tahun_ajaran."' style='display:none;width:100%;' value='".$row->uang_pangkal."' ></td>
				<td width='15%'>".
					"<label id='lblUangSemester".$row->tahun_ajaran."' >".number_format($row->uang_semester)."</label>	
					<input type='text' class='form-control' id='txtUangSemester".$row->tahun_ajaran."' style='display:none;width:100%;' value='".$row->uang_semester."' >
				</td>
				<td>".$row->date_input."</td>
				<td><input type='button' value='View cicil' class='btn btn-warning btn-sm btn-block clsViewCicil' alt='".$row->tahun_ajaran."' ></td>
				<td><input type='button' value='Edit' class='btn btn-danger btn-sm btn-block clsEditCicil' alt='".$row->tahun_ajaran."' ></td>
				</tr>
				";
			}
			?>	
		</table>
		
	</div>
	
</div>