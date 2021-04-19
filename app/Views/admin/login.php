<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
    <!-- Material Design for Bootstrap fonts and icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons">

    <!-- Material Design for Bootstrap CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-material-design@4.1.1/dist/css/bootstrap-material-design.min.css" integrity="sha384-wXznGJNEXNG1NFsbm0ugrLFMQPWswR3lds2VeinahP8N0zJw9VWSopbjv2x7WCvX" crossorigin="anonymous">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/main.css');?>" type="text/css"/>
    <title>Admin - FynTune - MCQ </title>
  </head>
  <body>
	<div class='pge' id='login-page'>
		<div class='container'>
			<div class='row'>
				<div class='col-md-4 offset-md-4' >
					<div class='card'>
					<div class='card-body'>
						<h2>Admin Login</h2>
						<form action="#" method="post" onsubmit="return AdminLogin()">
							
							<div class='form-group'>
								<label>Email Address</label>
								<input type="email" name='email' id='email' placeholder="Enter your email address" class='form-control' />
							</div>
							<div class='form-group'>
								<label>Password</label>
								<input type='password' name='password' id='password' placeholder="Password" class='form-control' />
							</div>
							<div class='form-buttons'>
								<input type='submit' name='submit' value=' Login ' class='btn btn-raised btn-primary float-right'/>
							</div>
						</form>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/popper.js@1.12.6/dist/umd/popper.js" integrity="sha384-fA23ZRQ3G/J53mElWqVJEGJzU0sTs+SvzG8fXVWP+kJQ1lwFAOkcUOysnlKJC33U" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap-material-design@4.1.1/dist/js/bootstrap-material-design.js" integrity="sha384-CauSuKpEqAFajSpkdjv3z9t8E7RlpJ1UP0lKM/+NdtSarroVKu069AlsRPKkFBz9" crossorigin="anonymous"></script>
    <script>
		$(document).ready(function() { 
			$('body').bootstrapMaterialDesign(); 
		});
		var isGuestLoggedIn = false;
		function setIsLoggedIn(e){
			if (e.code == 1){
				isGuestLoggedIn = true;
			}else{
				isGuestLoggedIn = false;
			}
		}
		function isLoggedIn(){
			LoggedIn = false;
			$.ajax({
				url : "<?php echo base_url('api/isGuestLoggedIn');?>",
				method:"post",
				data:{<?php echo csrf_token();?>: "<?php echo csrf_hash();?>"},
				success: function(out){
					if (out.code == 1){
					window.location.reload();
					}else{
						alert(out.message);
					}
				}
			});
			return isGuestLoggedIn;
		}
		function GetAllQuestions(){
			$.ajax({
				url 	:"<?php echo base_url('api/getQuestions');?>",
				method	:"post",
				data	:{<?php echo csrf_token();?>: "<?php echo csrf_hash();?>"},
				success	:function(out){
					console.log(out);
				}
			});
		}
		function AdminLogin(){
			email = $('#email').val();
			password = $('#password').val();
			if (email !== "" || password !== "" ){
				$.ajax({
					url : "<?php echo base_url('api/adminLogin');?>",
					method:"post",
					data:{ email: email,password:password, <?php echo csrf_token();?>: "<?php echo csrf_hash();?>"},
					success: function(out){
						if (out.code == 1){
						window.location.reload();
					}else{
						alert(out.message);
					}
					}
				});
			}else{
				alert('Please enter full name & email address');
			}
			return false;
		}
		function showGuestLoginPopup(){
			$('#login-page').show();
		}
		function hideGuestLoginPopup(){
			$('#login-page').hide();
		}
		// isLoggedIn();
		// if (isGuestLoggedIn == true){
		// 	//load the questions 
		// 	console.log('1');
		// 	hideGuestLoginPopup();
		// 	GetAllQuestions();
		// }else{
		// 	showGuestLoginPopup();
		// }
		
	</script>
  </body>
</html>