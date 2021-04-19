

<!doctype html>
<html lang="en">
  <head>
	<?php require "inc/header.php";?>
  </head>
  <body>
  <?php require "inc/default_layout.php";?>
	<div class='container-fluid' style="margin-top:50px;margin-bottom: 50px;">
		<div class='row'>
			<div class="col-md-4">
				<div class='card'>
					<div class='card-header'>Questions</div>
					<div class='card-body'>
						<ul class='question-navigation'>
							<?php 
								$n = 1;
								foreach ($questions as $qq){
									if ($n == 1){
										$active = "active";
									}else{
										$active = "";
									}
									echo "<li class='".$active."' id='qn_".$n."'><a href='question_".$n."'>".$n."</a></li>";
									$n++;
								}
							?>
						</ul>
					</div>
				</div>
			</div>
			<div class='col-md-8' >
				<div class='card'>
					<div class='card-body '>
					<form id='form1' action="<?php echo base_url("exam/submit");?>" method="post" >
						<div class="questions">
						<?php 
						$x = 1;
							foreach($questions as $q){
								
								$q['incorrect_answers'][] = $q['correct_answer'];
								shuffle($q['incorrect_answers']);
						?>
						<div class='question' id="question_<?php echo $x;?>">
							<input type='hidden' name='question_<?php echo $x;?>' value="<?php echo $q['question'];?>" />
							<input type='hidden' name='correct_answer_<?php echo $x;?>' value="<?php echo $q['correct_answer'];?>"  />
							
							<div class='question-title'><?php echo $q['question'];?></div>
							<div class='question-options'>
								<ul>
									<?php 
										foreach($q['incorrect_answers'] as $o){
											echo "<li><label><input type='radio' name='q_".$x."' id='q_".$x."' value='".$o."' />".$o."</a></li>";
										}
									?>
								</ul>
							</div>
						</div>
						<?php $x++; } ?>
						</div>
						<ul class='footer-btn'>
							<li><a href='#' class='btn btn-raised btn-primary ' onclick="return getPrevQuestion();">Previous</a></li>
							
							<li><a href='#' class='btn btn-raised btn-warning ' onclick="return getNextQuestion();">Save & Next</a></li>
						</ul>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php require "inc/footer.php";?>
    <script>
		var currentQuestion = 1;
		$('.questions #question_1').show();
		$(".question-navigation a").click(function(){
			i = $(this).attr('href');
			//$(this).parent();
			$('.question-navigation li').removeClass('active');
			$(this).parent().removeClass('active').addClass('active');
			$(".questions .question").hide();
			$(".questions #"+i).show();
			return false;
		});

		function getNextQuestion(){
			cq = currentQuestion;
			nextQuestion = currentQuestion+1; 
			currentQuestion = nextQuestion;
			if (nextQuestion <= 10){
				if ($('#q_'+cq+":checked").val() !== undefined ||  $('#q_'+cq+":checked").val() !== ""){
					$('#qn_'+cq).addClass('answered');
				}
				$('.question-navigation li').removeClass('active');
				$('#qn_'+nextQuestion).addClass('active');
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
				$('.question-navigation li').removeClass('active');
				$('#qn_'+nextQuestion).addClass('active');
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
 