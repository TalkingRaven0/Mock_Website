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

		if ($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$_SESSION['username'] = test_input($_POST["username"]);
			$_SESSION['pass'] = test_input($_POST["password"]);
			$_SESSION['email'] = test_input($_POST["email"]);
			$_SESSION['pass2'] = test_input($_POST["password2"]);

			if(empty($_POST["username"]))
			{
				$unameErr = "Username is Required";
				$error = true;
			}
			elseif (strlen($_POST['username']) < 5)
			{
				$unameErr = "Username is too short";
				$_SESSION['username'] = test_input($_POST["username"]);
				$error = true;
			}

			if(empty($_POST["email"]))
			{
				$emailErr = "Email is Required";
				$error = true;
			}
			else
			{
				if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) 
				{
      				$emailErr = "Invalid email format";
      				$error = true;
  				}
				
			}

			if(empty($_POST["password"]))
			{
				$passErr = "Password is Required";
				$error = true;
			}
			elseif (strlen($_POST['password']) < 5)
			{
				$passErr = "Password is too short";
				$error = true;
			}
			else
			{
				if($_SESSION['pass'] != $_SESSION['pass2'])
				{
					$passErr = "Password does not match";
					$error = true;
				}
			}

			// If there are no errors go to submitlogin.php
			if(!$error)
			{
				$x=$_SESSION['username'];
				$y=$_SESSION['email'];
				$z=$_SESSION['pass'];

				include 'dbtemp.php';

				$sql = "SELECT id, username, email FROM userinfo";
				$result = $conn->query($sql);
				while($row = $result-> fetch_assoc())
				{
					if ( $row["username"] == $x)
					{
						$unameErr = "Username is already taken!";
						$error = true;
					}

					if ( $row["email"] == $y)
					{
						$emailErr = "This Email already has an existing account";
						$error = true;
					}
				}

				if (!$error)///// Username does not already exist
				{
					$sql = "INSERT INTO userinfo (username,email,pass, datecreated) VALUES ('$x','$y','$z', NOW())";

					if ($conn->query($sql) === TRUE)
					{

						$sql = "SELECT id, username FROM userinfo";
						$result = $conn->query($sql);

						while($row = $result-> fetch_assoc())
						{
							if ( $row["username"] == $x)
							{
								session_unset();
								$_SESSION['id'] = $row["id"];
								// Relocate to Home
								header("Location: Home.php");
								exit();
								break;
							}
						}
					}
				}

				
				$conn->close();
				
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
	  	<form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
	  	   	<!--       v               Error Message                     v    -->
			Username: <span class="error"> <?php echo $unameErr;?></span> <br>
			<input type="text" name="username" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''?>"><br>

			Email:<span class="error"> <?php echo $emailErr;?></span> <br>
			<input type="text" name="email"value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''?>"><br>

			Password:<span class="error"> <?php echo $passErr;?></span> <br>
			<input type="password" name="password"value="<?php echo isset($_SESSION['pass']) ? $_SESSION['pass'] : ''?>"><br>

			Repeat Password: <input type="password" name="password2"value="<?php echo isset($_SESSION['pass2']) ? $_SESSION['pass2'] : ''?>"><br>

			<input type="submit" value="Submit">
		</form>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
</body>
</html>

