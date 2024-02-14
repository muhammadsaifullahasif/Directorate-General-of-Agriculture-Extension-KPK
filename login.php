<?php

require_once('config.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login | Directorate General of Agriculture Extension</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
	<!-- icheck bootstrap -->
	<link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
	<div class="login-box">
		<div class="login-logo">
			<img src="dist/img/DG_Agriculture_Extension.png" style="width: 100%;">
			<!-- <a href="index.php"><b>Digi</b>Agri</a> -->
		</div>
		<!-- /.login-logo -->
		<div class="card">
			<div class="card-body login-card-body">
				<p class="login-box-msg">Sign in to start your session</p>

				<?php

				$user_login = '';
				$user_pass = '';

				if(isset($_POST['login_form_btn'])) {
					$user_login = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['user_login'])));
					$user_pass = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['user_pass'])));

					if($user_login != '' && $user_pass != '') {
						$query = mysqli_query($conn, "SELECT * FROM users WHERE user_login='$user_login' && active_status='1' && delete_status='0'");
						if(mysqli_num_rows($query) > 0) {
							$result = mysqli_fetch_assoc($query);
							if(password_verify($user_pass, $result['user_pass'])) {
								echo "<div class='alert alert-success'>Login Successfully</div>";
								$_SESSION['agriculture_user_login'] = $user_login;
								header('location: index.php');
							} else {
								echo "<div class='alert alert-danger'>Incorrect Details</div>";
							}
						} else {
							echo "<div class='alert alert-danger'>No User Found</div>";
						}
					} else {
						echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
					}
				}

				?>

				<form method="post" id="login_form">
					<div class="input-group mb-3">
						<input type="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" value="<?php echo $user_login; ?>" class="form-control" placeholder="Email" id="user_login" name="user_login">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-envelope"></span>
							</div>
						</div>
						<div id="user_login_msg"></div>
					</div>
					<div class="input-group mb-3">
						<input type="password" pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$" value="<?php echo $user_pass; ?>" class="form-control" placeholder="Password" id="user_pass" name="user_pass">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
						<div id="user_pass_msg"></div>
					</div>
					<div class="row">
						<div class="col-8">
							<div class="icheck-primary">
								<input type="checkbox" id="remember">
								<label for="remember">Remember Me</label>
							</div>
						</div><!-- /.col -->
						<div class="col-4">
							<button type="submit" class="btn btn-primary btn-block" name="login_form_btn" id="login_form_btn">Sign In</button>
						</div><!-- /.col -->
					</div>
				</form>
			</div><!-- /.login-card-body -->
		</div>
	</div><!-- /.login-box -->

	<!-- jQuery -->
	<script src="plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="dist/js/adminlte.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){

			$('#user_login').on('focus', function(){
				$('#user_login').removeClass('is-invalid');
				$('#user_login_msg').removeClass('invalid-feedback').text('');
			}).focus();

			$('#user_pass').on('focus', function(){
				$('#user_pass').removeClass('is-invalid');
				$('#user_pass_msg').removeClass('invalid-feedback').text('');
			});

			$('#login_form').on('submit', function(e){
				var user_login = $('#user_login').val();
				var user_pass = $('#user_pass').val();
				var bool = 0;

				if(user_login == '') {
					$('#user_login').addClass('is-invalid');
					$('#user_login_msg').addClass('invalid-feedback').text('Email Required');
					bool = 1;
				} else {
					$('#user_login').removeClass('is-invalid');
					$('#user_login_msg').removeClass('invalid-feedback').text('');
					bool = 0;
				}

				if(user_pass == '') {
					$('#user_pass').addClass('is-invalid');
					$('#user_pass_msg').addClass('invalid-feedback').text('Password Required');
					bool = 1;
				} else {
					$('#user_pass').removeClass('is-invalid');
					$('#user_pass_msg').removeClass('invalid-feedback').text('');
					bool = 0;
				}

				if(bool == 0) {
					return true;
				} else {
					return false;
				}
			});

		});
	</script>
</body>
</html>
