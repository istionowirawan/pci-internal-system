<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PAHOA PCI PMB</title>
	
	<link rel="stylesheet" href="bootstrap-3.3.4/css/bootstrap.min.css">
	<script src="jquery/jquery.min.js"></script>
	<script src="bootstrap-3.3.4/js/bootstrap.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="css/wira-style.css">
	
	<script>
	$(document).ready(function(){
		$("#txtUsername").focus();
		
		$("#row_title").fadeIn(3000);
		
		$('#btnSignup').click(function() {
			$("#txtUsernameSignup").focus();
		});
		
		$("#frmLogin").hide();
		$("#frmLogin").slideDown(1000);
		
		$("#frmLogin").submit(function() {
			var dataform = $(this).serialize();
			$.ajax({
				type: "POST",
				url: "login/login_cek/",
				data: dataform,
				success: function(data, status) {
					//alert(data);
					if(data==0) {
						$("#msg-login").slideDown(1000, function() {
							$("#msg-login").fadeOut(8000);
						});
						$("#msg-login-isi").html("Invalid Username or password");
						$("#txtUsername").focus();
					} else {
						window.location.href='mainmenu/index';
					}
				}
			});
			
			return false;
		});
		
		function show_warningbox_signup(data) {
			$("#txtInfoMsg").text(data);
			$("#txtInfoBox").slideDown(1000, function() {
				$("#txtInfoBox").fadeOut(5000);
			});
			if(data=="Simpan Sukses") {
				$('#frmSignup')[0].reset();
			}
		}
		
		$("#frmSignup").submit(function() {
			if($("#txtPasswordSignup").val()==$("#txtPasswordSignup2").val()) {
				var dataform = $(this).serialize();
				$.ajax({
					type: "POST",
					url: "login/user_signup/",
					data: dataform,
					success: function(data, status) {
						//alert(data);
						show_warningbox_signup(data);
					}
				});
			} else {
				show_warningbox_signup("Password not match");
			}
			return false;
		});
	});
	</script>
</head>

<?php
if($this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='mainmenu/index'</script>";
}
?>

<body>

<div class="wrapper">
   <img src="image/banner_home.jpg" class="img-responsive ukuran-img" alt="Responsive image" >
</div>

<div class="container-fluid" align="center">
	<br><br>
	
	<div class="row" >
		<div class="col-md-1 col-xs-2 col-sm-2 login-style" >
			<img id='img_logo_pahoa' src="image/logo_circle.png" width="30%" height="30%" style="min-height:100px;min-width:100px;max-height:200px;max-width:200px;" >
		</div>
	</div>

	<!--<hr style="margin-top:15%;" />-->
	
	<div class="row" >
		<div class="col-md-4 col-xs-12 col-sm-12 login-style" >
			
		</div>
		<div class="col-md-4 col-xs-0 col-sm-0"></div>
	</div>
	
	<div class="row" style='margin-top:17%;' >
		<div class="col-md-12 col-xs-12 col-sm-12 login-style" >
			<form action='' method='POST' name='frmLogin' id='frmLogin' class='form-inline' >
			<table class='tbl-form-login' border='0' >
				
				<tr id='row_heading' >
					<td colspan='2' ><b>.: LOGIN PCI SYSTEM</b></td>
					<td><br></td>
					<td rowspan='4' ><img style='margin-left:40px;' src='image/vertical-line.png' height='100px;' width='50px' ></td>
				</tr>
				
				<tr >
					<td colspan='2' align='right'>
						 <div id='row_username' class="form-group">
							<label for="exampleInputName2">Username : </label>
							<input type="text" name='txtUsername' id='txtUsername' class="form-control" placeholder="Username" style='border-radius:0px;height:25px;' required >
						  </div>
					</td>
					<td></td>
					<td id='row_title' rowspan='3' style='vertical-align:top;font-size:20px;font-weight:bold;padding-left:40px;display:none;' >
						PAHOA COLLEGE INDONESIA SYSTEM
						<div style='font-size:10px;' >@ Copyright by IT PAHOA 2016 ::.</div>
					</td>
				</tr>
				
				<tr>
					<td colspan='2' align='right'>
						 <div id='row_password' class="form-group">
							<label for="exampleInputName2">Password : </label>
							<input type="Password" name='txtPassword' id='txtPassword' class="form-control" placeholder="Password" style='border-radius:0px;height:25px;' required >
						  </div>
					</td>
				</tr>
				
				<tr>
					<td></td>
					<td align='right'><input type="submit" class="btn btn-login" value='Login'  ></td>
					<td></td>
				</tr>
				
			</table>
			</form>
		</div>
	</div>
	
	
	<div id='msg-login' style='display:none;' >
		<div class="col-md-12 col-xs-12 col-sm-12" >
			<br>
			<div class="alert alert-danger alert-dismissible" role="alert" style='opacity: 0.5;' >
			<span class="glyphicon glyphicon-warning-sign" aria-hidden="true" ></span>
			<label id='msg-login-isi' ></label>
			</div>
		</div>
	</div>
	
</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style='color:#1A2445;'>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Register New User</h4>
      </div>
      <div class="modal-body">
		<form action='' method='POST' name='frmSignup' id='frmSignup' >
			<table class='table borderless' >
				<tr id='txtInfoBox' style='display:none;' >
					<td colspan='2' style='background-color:red;text-align:center;color:#ffffff;' >
						<span class="glyphicon glyphicon-warning-sign" aria-hidden="true" ></span> 
						<label id='txtInfoMsg' style='text-weight:bold;' >Warning</label>
					</td>
				</tr>
				
				<tr>
					<td>Username : </td>
					<td><input name='txtUsernameSignup' id='txtUsernameSignup' type='text' class='form-control' required ></td>
				</tr>
				
				<tr>
					<td>Password : </td>
					<td><input name='txtPasswordSignup' id='txtPasswordSignup' type='password' class='form-control' required ></td>
				</tr>
				
				<tr>
					<td>Retype Password : </td>
					<td><input name='txtPasswordSignup2' id='txtPasswordSignup2' type='password' class='form-control' required ></td>
				</tr>
				
				<tr>
					<td>Real Name : </td>
					<td><input name='txtNameSignup' id='txtNameSignup' type='text' class='form-control' required ></td>
				</tr>
				
				<tr>
					<td>Department : </td>
					<td>
						<select class='form-control' name='txtDeptSignup' id='txtDeptSignup' required  >
						<?php
						echo "<option value='' >== Choose Department ==</option>";
						foreach($dept->result() as $obj) {
							echo "<option value='".$obj->id."' >".$obj->nama_dept."</option>";
						}
						?>
						</select>
					</td>
				</tr>
				
				<tr>
					<td>Position : </td>
					<td>
						<select class='form-control' name='txtPosSignup' id='txtPosSignup' required >
						<?php
						echo "<option value='' >== Choose Position ==</option>";
						foreach($pos->result() as $obj) {
							echo "<option value='".$obj->level."' >".$obj->jabatan."</option>";
						}
						?>
						</select>
					</td>
				</tr>
				
				<tr>
					<td></td>
					<td><input type='submit' class='btn btn-primary btn-block' value='Signup' ></td>
				</tr>
			
			</table>
		</form>
	  </div>
      
    </div>
  </div>
</div>

<footer class="footer">
  <div class="container" style='text-align:right;font-size:1px;text-weight:bold;' >
	<input type='button' id='btnSignup' value='Register here' class='btn btn-primary' 
		data-toggle="modal" data-target="#myModal" >
  </div>
</footer>


</body>

</html>