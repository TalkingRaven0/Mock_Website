<!DOCTYPE html>
<html>

<head>
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="styles.css">

	<title>Welcome to Facepamphlet</title>

</head>

<!-- Input Validation and Session stuff START -->
	<?php
		// define variables and set to empty values
		$error = false;
		$unameErr = $passErr = "";
		$uname = $pass = "";

		if ($_SERVER["REQUEST_METHOD"] == "POST")
		{
			session_start();

			if(empty($_POST["username"]))
			{
				$unameErr = "Username is Empty";
				$error = true;
				$_SESSION['username'] = null;
			}
			else
			{
				$uname = test_input($_POST["username"]);
				$_SESSION['username'] = $uname;
			}

			if(empty($_POST["password"]))
			{
				$passErr = "Password is Empty";
				$error = true;
				$_SESSION['pass'] = null;
			}
			else
			{
				$pass = test_input($_POST["password"]);
				$_SESSION['pass'] = $pass;
			}

			// If there are no errors go to submitlogin.php
			if(!$error)
			{
				include 'dbtemp.php';

				$sql = "SELECT id, username, pass FROM userinfo";
				$result = $conn->query($sql);

				while($row = $result-> fetch_assoc())
				{
					if ( $row["username"] == $uname)
					{
						if($row["pass"] == $pass)
						{
							session_unset();
							$_SESSION['id'] = $row["id"];
							$conn->close();
							// Relocate to Home
							header("Location: Home.php");
						}
						break;
					}

				}
				$passErr = "";
				$unameErr = "Incorrect username or password";
			}

		}

		function test_input($data) {
		  $data = trim($data);
		  $data = stripslashes($data);
		  $data = htmlspecialchars($data);
		  return $data;
		}
	?>

<body>



	<div class="container-fluid" style = "margin: 0;position: absolute;top: 50%;transform: translateY(-50%);">
	  <div class="row">
	  	<div class="col-sm-8">
	      <div class = "jumbotron text-center">

	      	<h1>Welcome to Facepamphlet</h1>
	      	<h2>Log in and continue your miserable online life</h2>

	      </div>
	    </div>
	    <div class="col-sm-4" style="padding-right: 10%;">
	      <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
	      	 
	      	   	<span class="error"> <?php echo $unameErr;?></span> <br>

				Username: <input type="text" name="username" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''?>"><br>

				<span class="error"> <?php echo $passErr;?></span> <br>

				Password: <input type="password" name="password" value="<?php echo isset($_SESSION['pass']) ? $_SESSION['pass'] : ''?>"><br><br>

				<input type="submit" value="Login" style="width: 100%;">
			</form>
			<br>
			<form action = "SignupPage.php">
				Don't have an account yet? <input type="submit" value="Sign Up Now!" style="width: 30%; font-size: 13px;">
			</form>
	    </div>
	  </div>
	</div>
	

	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
</body>
</html>

