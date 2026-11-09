<!DOCTYPE html>
<html lang="en">
<?php
if(!$this->session->userdata('pmb_username')) {
	echo "<script>window.location.href='back_to_login'</script>";
}
?>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PAHOA PCI PMB</title>
	
	<base href='<?php echo base_url(); ?>'/>
	<!--<link rel="stylesheet" type="text/css" href="DataTables/media/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="DataTables/media/css/dataTables.bootstrap.css">-->
	
	<!--
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" >
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" >
	-->
	
	<script src="jquery/jquery.min.js"></script>
	<script src="bootstrap-3.3.4/js/bootstrap.min.js"></script>
	
	<link href="jquery_ui/jquery-ui.css" rel="stylesheet">
	<script src="jquery_ui/external/jquery/jquery.js"></script>
	<script src="jquery_ui/jquery-ui.js"></script>
	<script src="tinymce/js/tinymce/tinymce.min.js"></script>
	
	
	<script src="DataTables/media/js/jquery.dataTables.js"></script>
	<!--<script src="DataTables/media/js/dataTables.buttons.min.js"></script>-->
	<script src="DataTables/media/js/dataTables.bootstrap.js"></script>
	
	<link rel="stylesheet" type="text/css" href="DataTables/media/css/jquery.dataTables.css">
	
	<!--
	<script src="DataTables/media/js/buttons.flash.min.js"></script>
	<script src="DataTables/media/js/jszip.min.js"></script>
	<script src="DataTables/media/js/pdfmake.min.js"></script>
	<script src="DataTables/media/js/vfs_fonts.js"></script>
	<script src="DataTables/media/js/buttons.html5.min.js"></script>
	<script src="DataTables/media/js/buttons.print.min.js"></script>
	-->
	
	<!--<link rel="stylesheet" type="text/css" href="DataTables/media/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="DataTables/media/css/dataTables.bootstrap.css">-->
	<link rel="stylesheet" href="bootstrap-3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/wira-style.css">
	
	
	<script>
	$(document).ready(function(){
		var dept = '<?php echo $this->session->userdata('pmb_dept'); ?>';
		var jab = '<?php echo $this->session->userdata('pmb_jabatan'); ?>';
		
		if(jab>1) {
			loadAwalMenu();
		} else {
			//ini untuk super admin
			$(".topmenu-sub label" ).removeClass("active");
			$(".panel_setting_awal").load("mainmenu/menu_pci/");
			$("#lblMnPCI").addClass("active");
		}
		
		$("#lblMnPCI").click(function() {
			if($(".panel_setting_awal").is(":visible")==true) {				
				$(".panel_setting_awal").hide(500);
				$(".panel_setting_awal").toggleClass('col-md-2 col-md-0');
				$("#content_adm").toggleClass('col-md-10 col-md-12');
			} else {
				$(".panel_setting_awal").show(500);
				$(".panel_setting_awal").toggleClass('col-md-0 col-md-2');
				$("#content_adm").toggleClass('col-md-12 col-md-10');
			}
		});
		
		function loadAwalMenu() {
			$(".topmenu-sub label" ).removeClass("active");
			$(".topmenu-sub label" ).hide();
			//alert(dept);
			if(dept==1) {
				$(".panel_setting_awal").load("mainmenu/menu_humas/");
				$("#lblMnHumas").addClass("active");
				$("#lblMnHumas").show();
			} else if (dept==3) {
				$(".panel_setting_awal").load("mainmenu/menu_keuangan/");
				$("#lblMnKeuangan").addClass("active");
				$("#lblMnKeuangan").show();
			} else if(dept==4) {
				$(".panel_setting_awal").load("mainmenu/menu_pci/");
				$("#lblMnPCI").addClass("active");
				$("#lblMnPCI").show();
			}
		}
		
		$(".topmenu-sub label").click(function() {
			var menuactive = $(this).html();
			$(".topmenu-sub label" ).removeClass("active");
			$(this).addClass("active");
			
			if(menuactive=="Humas") {
				$(".panel_setting_awal").load("mainmenu/menu_humas/");
			} else if (menuactive=="Keuangan") {
				$(".panel_setting_awal").load("mainmenu/menu_keuangan/");
			} else {
				$(".panel_setting_awal").load("mainmenu/menu_pci/");
			}
		});
		
		tampilawal_content();
		function tampilawal_content() {
			$("#content_adm").hide();
			$("#content_adm").load("mainmenu/tampilan_awal/",function() {
				$("#content_adm").slideDown(1000);
			});
		}
		
		
		$("#idChangePass").click(function() {
			$("#content_adm").load("mainmenu/change_pass/",function() {
				$("#frmChangePass").submit(function() {
					var passCur = $("#txtCurPass").val();
					var passSession = "<?php echo $this->session->userdata('pmb_password'); ?>";
					
					if(passCur==passSession) {
						if($("#txtNewPass1").val()==$("#txtNewPass2").val()) {
							$("#colKeterangan").text("");
							$.post("mainmenu/update_pass/", {
								txtUsername:$("#txtUsername").val(),
								txtNewpass:$("#txtNewPass1").val()
							}, function(data, status) {
								$("#colKeterangan").text(data);
								
								$("#txtUsername").val("");
								$("#txtCurPass").val("");
								$("#txtNewPass1").val("");
								$("#txtNewPass2").val("");
							}); 
							
						} else {
							$("#colKeterangan").text("Password doesn't match");
						}
						
					} else {
						$("#colKeterangan").text("Invalid or wrong old password");
					}
					
					return false;
				});
			});
		});
	});
	</script>
</head>

<body>
<div class="wrapper">
   <img src="image/banner_home.jpg" class="img-responsive ukuran-img" alt="Responsive image" >
</div>

<div class="container-fluid" >

	<nav class="row topmenu-header navbar navbar-default" style='margin-bottom:0px;border-color:transparent;border-radius:0px;' >
	<div class="container-fluid">
		
		<div class="col-md-6" style='padding:3px;' >
			<span class="header-text" style="font-size:25px;text-align:left;padding-left:10px;vertical-align:bottom;" >
				<img src='image/p_logo5.png' width='30' height='30'>
				PAHOA College System ::.
			</span>
			<!--<span class="header-text header-desc" >&nbsp; - PAHOA Elearning</span>-->
		</div>
		
		
	
		<div class="col-md-6" align='right' style='padding:3px;padding-right:10px;' >

			<img style="height:37px;float:right;cursor:pointer" src="photo/thumb/<?php echo $this->session->userdata('pmb_pic'); ?>"   >
			
			<span class="header-username" style="cursor:pointer;">
				<div class="dropdown"  style="z-index:10;" >
					<div class="dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">
						<?php 
						echo " (" . $this->session->userdata('pmb_username') . ") ";
						echo $this->session->userdata('pmb_nama'); 
						?>
					<span class="caret"></span></div>
					<ul class="dropdown-menu" role="menu" aria-labelledby="menu1" style="right:0px;top:30px;">
					  <li role="presentation"><a role="menuitem" tabindex="-1" id="idChangePass" >Change Pass</a></li>
					  <li role="presentation"><a role="menuitem" tabindex="-1" id="idChangePic">Profile</a></li>
					  <li role="presentation"><a role="menuitem" tabindex="-1" id="idFeedInfo">Feed Info</a></li>
					  <li role="presentation" class="divider"></li>
					  <li role="presentation"><a role="menuitem" tabindex="-1" href="mainmenu/logout">Logout</a></li>
					</ul>
				</div>
			</span>
			
			
			<div class="navbar-header" style='float:left;' title='Menu Elarning klik disini' >			  
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu_utama_elearning" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			</div>  
			
			
		</div>
		
		
	</div><!-- /.container-fluid -->	
	</nav>
	

<!-- +++++++++++++++++++++++++++++++++++++++++++++++++ NEW MENU +++++++++++++++++++++++++++++++++++++++++++++++++ -->
	<div class="row" style='background-color:#EEEEEE;padding:0px;'  >
		
		<div class="col-md-9 topmenu-sub" style='background-color:#EEEEEE;height:25px;' >
			<label id='lblMnHumas' class='active' >Humas</label>
			<label id='lblMnKeuangan' >Keuangan</label>
			<label id='lblMnPCI' >PCI</label>
		</div>
		
		<div class="col-md-3" style='background-color:#EEEEEE;text-align:right;height:25px;' >
			<label style='color:#3f5ab5;font-size:12px;' >
			Level Menu : 
			<?php 
			echo $jabatanuser->row(0)->jabatan;
			echo " ( ";
			echo $deptuser->row(0)->nama_dept;
			echo " )";
			?>
			</label>
		</div>
	</div>
<!-- +++++++++++++++++++++++++++++++++++++++++++++++++ END NEW MENU +++++++++++++++++++++++++++++++++++++++++++++++++ -->


	<div class="row" >
		<div class="col-md-2 panel_setting_awal" style="padding:5px;margin:0px;background-color:#dedcea;"  >
			
		</div>
		
		<div id="load_menu_jquery" ></div>
		
		<div class="col-md-10" style="padding:0px;text-align:center;" id="content_adm" >	
		</div>
		
	</div>
	
	
	<div id="dialog_content" title="Detail" ></div>
		
</div>

</body>

</html>