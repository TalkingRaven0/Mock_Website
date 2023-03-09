<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <link rel="icon" type="images/png" href="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ae/Ive_logo_%28Black%29.svg/1609px-Ive_logo_%28Black%29.svg.png">

    <title>Settings | ArchIVE</title>
</head>

<?php
	session_start();
	// Declare Variables
	$statusMsg = '';
	$Errmsgu = $Errmsgp = "";
	$error = false;

	// Declare Function(s)
	function test_input($data)
	{
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	// Change Username and Password

	// Password Confirmation

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{	

		include 'dbtemp.php';

		$sql = "SELECT pass FROM userinfo WHERE id = ".$_SESSION['id'];
		$result = $conn->query($sql);
		$row = $result-> fetch_assoc();

		if(isset($_POST['changeu']))
		{

			$_SESSION['username'] = test_input($_POST["username"]);
			$_SESSION['passwordorgu'] = test_input($_POST['passwordorgu']);

			if(strlen($_SESSION['username']) < 5)
			{
				$Errmsgu = "Invalid Username";
				$error = true;
			}

			if ( $row["pass"] != $_SESSION['passwordorgu'])
			{
				$Errmsgu = "Please enter your password to make any changes";
				$error = true;							
			}

			if(!$error)
			{
				$sql = "SELECT id, username, email FROM userinfo";
				$result = $conn->query($sql);
				while($row = $result-> fetch_assoc())
				{
					if ( $row["username"] == $_SESSION['username'])
					{
						if($row['id'] == $_SESSION['id'])
						{
							$Errmsgu = "This is your original username";
						}
						else
						{
							$Errmsgu = "Username is already taken!";
						}
						$error = true;							
					}
				}
				if(!$error)
				{
					$sql = "UPDATE userinfo SET username= '" .$_SESSION['username']. "' WHERE id= " .$_SESSION['id'];
					if ($conn->query($sql) === TRUE) 
					{
						$Errmsgu = "Username Updated Successfully";
						$id = $_SESSION['id'];
						session_unset();
						$_SESSION['id'] = $id;
						header("Refresh:0");
					}
				}
			}
		}

		if(isset($_POST['changep']))
		{
			$_SESSION['passwordorgp'] = test_input($_POST['passwordorgp']);
			$_SESSION['password'] = test_input($_POST["password"]);
			$_SESSION['password2'] = test_input($_POST["password2"]);

			if ($_SESSION['password'] != "" && strlen($_SESSION['password']) < 5)
			{
				$Errmsgp = "Invalid Password";
				$error = true;
			}
			elseif ($_SESSION['password'] != $_SESSION['password2'])
			{
				$Errmsgp = "Passwords do not match!";
				$error = true;
			}

			if ( $row["pass"] != $_SESSION['passwordorgp'])
			{
				$Errmsgp = "Please enter your password to make any changes";
				$error = true;							
			}

			if(!$error)
			{
				if ($conn->query($sql) === TRUE) 
				{
					$Errmsgp = "Password Updated Successfully";
					$id = $_SESSION['id'];
					session_unset();
					$_SESSION['id'] = $id;
					header("Refresh:0");
				}
			}
		}

		$conn->close();

		// Change Profile Picture
		if(isset($_POST["upload"]))
		{
			if(!empty($_FILES["file"]["name"]))
			{
				// File upload path
				$targetDir = "uploads/";
				$fileName = basename($_FILES["file"]["name"]);
				$targetFilePath = $targetDir . $fileName;
				$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
			    $allowTypes = array('jpg','png','jpeg');

			    if(in_array($fileType, $allowTypes))
			    {
			        // Upload file to server
			        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath))
			        {
			        	
			        	session_start();
			        	$fileName = "pic" . $_SESSION['id'] . "." . $fileType;
			        	rename($targetFilePath, $targetDir.$fileName);
			        	include 'dbtemp.php';
			            // Insert image file name into database
			            $sql = "UPDATE userinfo SET filename= '" .$fileName. "', imgdate= NOW() WHERE id= " .$_SESSION['id'];
						if ($conn->query($sql) === TRUE) 
			            {
			                $statusMsg = "Profile Picture successfully changed";
			                unlink($_SESSION['pic']);
			            }
			            else
			            {
			                $statusMsg = "File upload failed, please try again.";
			            }
			            $conn->close();
			            $id = $_SESSION['id'];
						session_unset();
						$_SESSION['id'] = $id;
						header("Refresh:0");
			        }
			        else
			        {
			            $statusMsg = "Sorry, there was an error uploading your file.";
			        }
			    }
			    else
			    {
			        $statusMsg = 'Invalid Filetype';
			    }
			}
			else
			{
			    $statusMsg = 'Please select a file to upload.';
			}
		}
	}

	

?>

<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#prevpic').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>


<body>
<?php include 'Sidebar.php';?>

<div class="main">
    <div id="banner"><img src="photos/lovedive2.jpg" style="width:100%; height:auto;" alt=""></div>

	<div class="row" style="margin-top: 20px">
		<div class="col-lg-4 col-md-6 col-12 col d-flex justify-content-center" style="margin-top: 20px">
			<div class="card" id="setting" style="width: 300px; padding: 20px;">
				<form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
					<h5 class="card-title">Change Username</h5>
					<input type="password" name="passwordorgu" class="form-control" placeholder="Enter Password"><br>
					<input type="text" name="username" class="form-control" placeholder="New Username"><br><br><br>
					<input type="submit" name="changeu" class="btn btn-primary" style="width: 100%; margin-top:10px;" value="Save Changes">
					<?php echo $Errmsgu ?>
				</form>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-12 col d-flex justify-content-center" style="margin-top: 20px">
			<div class="card" id="setting" style="width: 300px; padding: 20px;">
				<form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
					<h5 class="card-title">Change Password</h5>
					<input type="password" name="passwordorgp" class="form-control" placeholder="Enter Password"><br>
					<input type="password" name="password" class="form-control" placeholder="Enter New Password"><br>
					<input type="password" name="password2" class="form-control" placeholder="Re-enter New Password"><br>
					<input type="submit" name="changep" class="btn btn-primary" style="width: 100%;" value="Save Changes">
					<?php echo $Errmsgp ?>
				</form>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-12 col d-flex justify-content-center" style="margin-top: 20px">
			<div class="card" id="setting" style="width: 300px; padding: 20px;">
				<form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
					<h5 class="card-title">Change Profile Picture</h5>
					<form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" enctype="multipart/form-data" runat="server">
					<img id="prevpic" src="<?php echo isset($_SESSION['pic']) ? $_SESSION['pic'] : $defaultpic ?>" style="height: 100px; width: 100px; display: block; margin-left: auto; margin-right: auto; border-radius: 20px;"><br>
				    <input type="file" name="file" onchange="readURL(this);">
				    <br><br>
					<input type="submit" name="upload" class="btn btn-primary" style="width: 100%;" value="Save Changes">
					<?php echo $statusMsg;?></span> <br>
				</form>
				</form>
			</div>
		</div>
	</div>
		
</div>



	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
</body>
</html>

