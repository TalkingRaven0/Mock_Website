<!DOCTYPE html>
<html>

<?php session_start(); ?>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="styles.css">
	<title>My Posts</title>

</head>

<?php
	// define variables and set to empty values
	$error = false;
	$myposts = true;
	$msgErr = "";

	if (isset($_POST['post']))
	{

		if(empty($_POST["content"]))
		{
			$msgErr = "You can't post nothing";
			$error = true;
		}


		// If there are no errors go to submitlogin.php
		if(!$error)
		{
			$content=$_POST['content'];
			$id = $_SESSION['id'];

			include 'dbtemp.php';

			if (!$error)///// Username does not already exist
			{
				$sql = "INSERT INTO posttable (userID,content,datecreated) VALUES ('$id','$content', NOW())";

				if ($conn->query($sql) === TRUE)
				{
					$msgErr = 'Post created successfully';
				}
			}
		
			$conn->close();
			
		}

	}
?>

<body>



	<?php include 'Sidebar.php';?>

	<div class="main">
		<div class = "jumbotron text-center">
			<h1>Home Page</h1>
			<p>Continue your miserable online life</p>
		</div>

		<!-- Create New Post   -->
			<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="postcard"> 
				<h3>Create a new post </h3>

				Comment: <?php echo $msgErr ?>

				<br> <textarea name="content" rows="5" placeholder="Enter your useless thoughts here" style="width: 100%"></textarea>
				<br><br>
				<input type="submit" name="post" value="Submit">  
			</form>

		<!-- Posts loop -->

		<?php
			include 'dbtemp.php';

			$sql = "SELECT postID, userID, content, upvotes,datecreated FROM posttable WHERE userID = ". $_SESSION['id'] ." ORDER BY datecreated DESC";
			$array = $conn->query($sql);

			
			while($row = $array-> fetch_assoc())
			{
				$postID = $row['postID'];
				$userID = $row['userID'];
				$content = $row['content'];
				$upvotes = $row['upvotes'];

				$datecreated = $row['datecreated'];
				include 'postCard.php';
			}
		?>


		<?php $conn->close(); ?>

	</div>

	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
</body>
</html>

