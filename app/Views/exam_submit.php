<!doctype html>
<html lang="en">
  <head>
	<?php require "inc/header.php";?>
  </head>
  <body>
	<div class='container'>
		<div class='row'>
			<div class='col-md-8 offset-md-2' style="margin-top:50px;margin-bottom: 50px;">
				<div class='card'>
					<div class='card-body '>
						<h1><?php echo $result['total_correct']." / ".$result['total_questions']; ?></h1>
						<ul class='footer-btn'>
							<li><a href='<?php echo base_url('/');?>' class='btn btn-raised btn-primary '>Re-Attempt</a></li>
							
							<li><a href='<?php echo base_url('/guest/logout');?>' class='btn btn-raised btn-warning '>Logout</a></li>
						</ul>
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
		var currentQuestion = 1;
		$('.questions #question_1').show();
		function getNextQuestion(){
			nextQuestion = currentQuestion+1; 
			currentQuestion = nextQuestion;
			if (nextQuestion <= 10){
				$('.questions .question').hide();
				$('.questions #question_'+nextQuestion).show();
			}else{
				//submit the test 
				if (confirm("Do you want to submit the exam")==true){
					$("#form1").submit();
				}else{
					//do something else
				}
			}
			return false;
		}
		function getPrevQuestion(){
			prevQuestion = currentQuestion-1; 
			currentQuestion = prevQuestion;
			if (prevQuestion >= 1){
				$('.questions .question').hide();
				$('.questions #question_'+prevQuestion).show();
			}else{
				//submit the test 
			}
			return false;
		}
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
					setIsLoggedIn(out);
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
		function GuestLogin(){
			full_name = $('#guest_full_name').val();
			email = $('#guest_email').val();
			phone = $('#guest_phone').val();
			if (full_name !== "" || email !== "" ){
				$.ajax({
					url : "<?php echo base_url('api/guestLogin');?>",
					method:"post",
					data:{full_name: full_name, email: email, phone: phone, <?php echo csrf_token();?>: "<?php echo csrf_hash();?>"},
					success: function(out){
						console.log(out);
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
		isLoggedIn();
		if (isGuestLoggedIn == true){
			//load the questions 
			console.log('1');
			hideGuestLoginPopup();
			GetAllQuestions();
		}else{
			showGuestLoginPopup();
		}
		
	</script>
  </body>
</html>