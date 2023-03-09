<?php 

	if (session_status() === PHP_SESSION_NONE) 
	{
    	session_start();
	}
	
	if (!isset($_SESSION['id']))
	{
		header("Location: index.php");
		exit();
	}

	if (isset($_GET['logout'])){
		session_unset();
		session_destroy();
		header("Location: index.php");
		exit();
	}	
	
	$defaultpic = "https://www.baytekent.com/wp-content/uploads/2016/12/facebook-default-no-profile-pic1.jpg";
	$targetDir = "uploads/";

	if (!isset($_SESSION['uname']))
	{
		include 'dbtemp.php';

		// Get Data START

		$sql = "SELECT id, username, email, pass, filename FROM userinfo WHERE id = " . $_SESSION['id'];
		$result = $conn->query($sql);
		$row = $result-> fetch_assoc();

		$_SESSION['uname'] = $row['username'];
		$_SESSION['email'] = $row['email'];
		if ($row['filename'] != "")
		{
			$_SESSION['pic'] = $targetDir.$row['filename'];
		}

		$conn->close();
	}
?>

<div class="sidenav">
		<a> <img src="<?php echo isset($_SESSION['pic']) ? $_SESSION['pic'] : $defaultpic ?>" id="profpic"> </a>
		<a> Username: <?php echo $_SESSION['uname'] ?> </a>
		<a> Email: <?php echo $_SESSION['email'] ?> </a>
		<a href="myPosts.php">My Posts</a>
		<a href="AccountManagement.php">Account Management</a>
		<a href="Home.php">Home</a>
		<div id = "button">
			<a href="?logout" class="btn btn-primary btn-block" role="button">Logout</a>
		</div>
	</div>