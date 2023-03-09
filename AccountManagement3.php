<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="styles.css">
	<title>Account Management</title>

</head>

<?php
	session_start();
	// Declare Variables
	$statusMsg = '';
	$Errmsg = "";
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
		

		if(isset($_POST["change"]))
		{		
			$_SESSION['username'] = test_input($_POST["username"]);
			$_SESSION['passwordorg'] = test_input($_POST['passwordorg']);
			$_SESSION['password'] = test_input($_POST["password"]);
			$_SESSION['password2'] = test_input($_POST["password2"]);
			
			if ($_SESSION['password'] != "" && strlen($_SESSION['password']) < 5)
			{
				$Errmsg = "Invalid Password\n";
				$error = true;
			}
			elseif ($_SESSION['password'] != $_SESSION['password2'])
			{
				$Errmsg = "Passwords do not match!";
				$error = true;
			}


			if($_SESSION['username'] != "" && strlen($_SESSION['username']) < 5)
			{
				$Errmsg = $Errmsg."Invalid Username";
				$error = true;
			}

			include 'dbtemp.php';

			$sql = "SELECT pass FROM userinfo WHERE id = ".$_SESSION['id'];
			$result = $conn->query($sql);
			$row = $result-> fetch_assoc();
			if ( $row["pass"] != $_SESSION['passwordorg'])
			{
				$Errmsg = "Please enter your password to make any changes";
				$error = true;							
			}

			// If there are no errors change variables
			if(!$error)
			{

				if($_SESSION['password2'] != "")
				{
					$sql = "UPDATE userinfo SET pass= '" .$_SESSION['password2']. "' WHERE id= " .$_SESSION['id'];
					if ($conn->query($sql) === TRUE) 
					{
						$Errmsg = "Password Updated Successfully\n";
					}
				}

				if($_SESSION['username'] != "")
				{

					$sql = "SELECT id, username, email FROM userinfo";
					$result = $conn->query($sql);
					while($row = $result-> fetch_assoc())
					{
						if ( $row["username"] == $_SESSION['username'])
						{
							if($row['id'] == $_SESSION['id'])
							{
								$Errmsg = "This is your original username";
							}
							else
							{
								$Errmsg = "Username is already taken!";
							}
							$error = true;							
						}
					}

					if(!$error)
					{
						$sql = "UPDATE userinfo SET username= '" .$_SESSION['username']. "' WHERE id= " .$_SESSION['id'];
						if ($conn->query($sql) === TRUE) 
						{
							$Errmsg = $Errmsg."Username Updated Successfully\n";
						}
					}		
				}

				if(!$error)
				{
					$id = $_SESSION['id'];
					session_unset();
					$_SESSION['id'] = $id;
					header("Refresh:0");
				}

				
			}
		}

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
			                if(isset($_SESSION['pic']))
			                {
			                	unlink($_SESSION['pic']);
			                }			                
			                $id = $_SESSION['id'];
							session_unset();
							$_SESSION['id'] = $id;
							header("Refresh:0");

			            }
			            else
			            {
			                $statusMsg = "File upload failed, please try again.";
			            }
			            $conn->close();
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
		<div class = "jumbotron text-center">
			<h1>Account Management</h1>
			<h3>Change your identity into a less useless version of you</h3>
		</div>

		<div class="container-block"><h4> Email: <?php echo $_SESSION['email'] ?></h4></div>

		<div class="container-block">
		  <div class="row">
		    <div class="col-sm-4">
		      	<form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">

		      	   	<span class="error"> <?php echo $Errmsg;?></span> <br>

		      	   	Enter Original Password: <input type="password" name="passwordorg" ><br>

					Change Username: <input type="text" name="username"><br>
					
					Change Password: <input type="password" name="password"><br>

					Repeat New Password: <input type="password" name="password2"><br>

					<input type="submit" name="change" value="Confirm Changes">
				</form>
	    	</div>

	    	<div class="col-sm-2"></div>

		    <div class="col-sm-6">
		      	<h3>Change Profile Picture</h3>
		      	<form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" enctype="multipart/form-data" runat="server">
				    Select Image:
				    <input type="file" name="file" onchange="readURL(this);">
				    <br>
				    <img id="prevpic" src="<?php echo isset($_SESSION['pic']) ? $_SESSION['pic'] : $defaultpic ?>" />
					<br>
					<input type="submit" name="upload" value="Change Profile Picture">
					<?php echo $statusMsg;?></span> <br>
				</form>
				    
		      	
		    </div>

		  </div>
		</div>
	</div>



	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
</body>
</html>

