<?php session_start() ?>

<!DOCTYPE html>
<html>

<head>
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="styles.css">

</head>


<body>

<!-- Input Validation and Session stuff START -->
	<?php
		// define variables and set to empty values
		$error = false;
		$unameErr = $emailErr = $passErr = "";
		$uname = $email = $pass = "";

		//File uploading variables

		$fileErr = "";
		$allowtypes = array('jpg','png','jpeg');
		$target_dir = "uploads/";

		if ($_SERVER["REQUEST_METHOD"] == "POST")
		{
			// Some more File upload Variables
			$filetype = pathinfo($target_dir.basename($_FILES['file']['name']), PATHINFO_EXTENSION);

			if(empty($_POST["username"]))
			{
				$unameErr = "Username is Required";
				$error = true;
				$_SESSION['username'] = null;
			}
			else
			{
				$uname = test_input($_POST["username"]);
				$_SESSION['username'] = $uname;
			}

			if(empty($_POST["email"]))
			{
				$emailErr = "Email is Required";
				$error = true;
				$_SESSION['email'] = null;
			}
			else
			{
				if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) 
				{
      				$emailErr = "Invalid email format";
      				$error = true;
  				}
  				$email = test_input($_POST["email"]);
				$_SESSION['email'] = $email;
				
			}

			if(empty($_POST["password"]))
			{
				$passErr = "Password is Required";
				$error = true;
				$_SESSION['pass'] = null;
			}
			else
			{
				$pass = test_input($_POST["password"]);
				$_SESSION['pass'] = $pass;
			}

			// File upload verification

			if (empty($_FILES["file"]["name"]))
			{
				$fileErr = "No file chosen";
				$error = true;
			}
			else
			{
				if (in_array($filetype,$allowtypes))
				{
					$_SESSION['file'] = $_FILES['file']['tmp_name'];
				}
				else
				{
					$fileErr = "Invalid file type";
					$error = true;
				}
			}

			// If there are no errors go to submitlogin.php
			if(!$error)
			{
				header("Location: submitlogin.php");
				exit();
			}

		}

		function test_input($data) {
		  $data = trim($data);
		  $data = stripslashes($data);
		  $data = htmlspecialchars($data);
		  return $data;
		}
	?>
<!-- Input Validation and Session stuff END -->


	<div class = "jumbotron text-center"><h1>Signup Form</h1></div>

	<div class="container">
	  <div class="row">
	    <div class="col-sm-4">
<!-- Sign up Fields START -->
	      <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
	      	   <!--       v               Error Message                     v    -->
				Username: <span class="error"> <?php echo $unameErr;?></span> <br>
				<input type="text" name="username" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''?>"><br>

				Email:<span class="error"> <?php echo $emailErr;?></span> <br>
				<input type="text" name="email"value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''?>"><br>

				Password:<span class="error"> <?php echo $passErr;?></span> <br>
				<input type="password" name="password"value="<?php echo isset($_SESSION['pass']) ? $_SESSION['pass'] : ''?>"><br>

				<input type="submit" value="Submit">
			</form>
<!-- Sign up Fields END -->
	    </div>
	    <div class="col-sm-4">
	      <h3>Image Upload</h3>
	      	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
			    Select Image File to Upload: <span class="error"> <?php echo $fileErr;?></span> <br>
			    <input type="file" name="file" value = "<?php echo $_SESSION['file']?>" >
			    <input type="submit" name="submit" value="Upload">
			</form>
	    </div>
	    <div class="col-sm-4">
	      <h3>Image Preview</h3>
	    </div>
	  </div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
</body>
</html>

