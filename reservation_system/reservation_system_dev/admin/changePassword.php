<?php 
	include_once 'includes/dbh.inc.php';
	session_start();

  if (isset($_GET['email']) && isset($_GET['token'])) {
    $_SESSION['user_email'] = $_GET['email'];
    $_SESSION['user_token'] = $_GET['token'];

    $sql = $conn->query("SELECT user_id FROM users WHERE
      user_email= '$_SESSION[user_email]' AND token='$_SESSION[user_token]' AND token<>'' AND token_expire > NOW()
    ");

    if ($sql->num_rows == 0) {
      redirectToLoginPage();
    }
  }

		

		if(isset($_POST['newPassword1'])){

			$newPassword = $_POST['newPassword1'];
			$email = $_SESSION['user_email'];

			$newPasswordEncrypted = password_hash($newPassword, PASSWORD_DEFAULT);
			$sql = "UPDATE users SET token='', user_pwd = '$newPasswordEncrypted'
						WHERE user_email='$email'";
						
			$result = mysqli_query($conn, $sql);

			if($result){

        unset($_SESSION['user_email']);
        unset($_SESSION['user_token']);

				echo "
					<script>
						alert('You have successfully changed your password. Click OK to redirect to log in page.');
						window.location = 'login.php';
					</script>
					";
				
			}else{
				echo "
					<script>
						alert('Something went wrong.');
					</script>
					";
			}

		}
	


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password System</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
    <div class="container" style="margin-top: 100px;">
        <div class="row justify-content-center">
            <div class="col-md-4 col-md-offset-4" align="center">
                <div class="form-group well">
                <a href="login.php"><img  width="200px" src="../img/RGR_LOGO.png"></a><br><br>
                <h3>Change password for <br><?php if(isset($_GET['email'])){ echo $_GET['email']; }?></h3><br>
                <form id="resetPassword-form" action = "changePassword.php" method="post">
	                <input type="password" class="form-control" id = "newPassword1" name="newPassword1"  placeholder="Password"><br>
	                <input  type="password" class="form-control" id = "newPassword2" name="newPassword2" placeholder="Confirm password"><br>
	                <input type="submit" name="submit" class="btn btn-success" value="Change password">
                </form>
                <br><br>
                <p id="response"></p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <script type="text/javascript">
        
$(document).ready(function () {


 $(function(){

          $.validator.setDefaults({
            errorClass: 'help-block',
            highlight: function(element) {
              $(element)
                .closest('.form-group')
                .addClass('has-error');
            },
            unhighlight: function(element) {
              $(element)
                .closest('.form-group')
                .removeClass('has-error');
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            }
          });

		 $.validator.addMethod('strongPassword', function(value, element) {
		    return this.optional(element) 
		      || value.length >= 6
		      && /\d/.test(value)
		      && /[a-z]/i.test(value);
		  }, 'Your password must be at least 6 characters long and contain at least one number and one char\.')

         $('#resetPassword-form').validate({
          rules:{

            newPassword1:{
              	required: true,
              	strongPassword: true
            },
            newPassword2: {
            	required: true,
            	equalTo: '#newPassword1'
            }
   
          }
          });
        });

        });
    </script>

  <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-2.1.3.min.js"></script>
  <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js"></script>
  <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/additional-methods.min.js"></script>

</body>
</html>

