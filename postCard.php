<?php

	$sql = "SELECT username FROM userinfo WHERE id = ".$userID;
	$result = $conn->query($sql);
	$userID = $result-> fetch_assoc();
	$userID = $userID['username'];
	$upID = "up".$postID;
	$downID = "down".$postID;

?>

<div id = "postcard">

	<?php

	if(isset($_POST[$upID]) || isset($_POST[$downID]))
	{
		if(isset($_POST[$upID]))
		{
			$operation = "+";
		}
		else
		{
			$operation = "-";
		}
		$sql = "UPDATE posttable SET upvotes = " .$upvotes.$operation. "1 WHERE postID= " .$postID;
		$conn->query($sql);
	}

	if(isset($myposts))
	{
		$postedit = "edit".$postID;
		$confedit = "confirm".$postID;
		$cancel = "cancel".$postID;
		if(!isset($_SESSION[$postedit]))
		{
			$_SESSION[$postedit] = false;
		}

		if (!function_exists('test_input'))
		{
			function test_input($data)
			{
			  $data = trim($data);
			  $data = stripslashes($data);
			  $data = htmlspecialchars($data);
			  return $data;
			}
		}
		

		echo '<div style="float: right">
			<form action = "';echo htmlspecialchars($_SERVER["PHP_SELF"]); echo '" method = "Post">
				<input type="submit" name = "'; echo $postID; echo'" value="Delete Post" class="btn btn-danger">';
				if(!$_SESSION[$postedit])
				{
					echo'<input type="submit" name = "'; echo $postedit; echo'" value="Edit Post" class="btn btn-info">';
				}
			echo'</form>
		</div>
		';

		if(isset($_POST[$postedit]))
		{
			$_SESSION[$postedit] = true;
			header("Refresh:0");
		}

		if(isset($_POST[$cancel]))
		{
			$_SESSION[$postedit] = false;
			header("Refresh:0");
		}

		if(isset($_POST[$postID]))
		{
			$sql = "DELETE FROM posttable WHERE postID = ".$postID;
			$conn->query($sql);
			header("Refresh:0");
		}

		if (isset($_POST[$confedit]))
		{
			$contentedit = test_input($_POST['content']);
			if(empty(trim($contentedit)))
			{
				$msgErr = "You can't post nothing";
			}
			else
			{
				$sql = "UPDATE posttable SET datecreated = NOW(), content= '" .$contentedit. "' WHERE postID= " .$postID;
				$conn->query($sql);
				$_SESSION[$postedit] = false;
				header("Refresh:0");
			}	
		}
	}
	
	?>

	

	Posted by: <?php echo $userID ?> on <?php echo $datecreated ?> <br><br>

	<!-- This whole div is the content -->
	<div>
		<?php
			if(isset($myposts))
			{
				if($_SESSION[$postedit])
				{
					// Edit Form
					echo '<form method="post" action="';echo htmlspecialchars($_SERVER["PHP_SELF"]);echo'" id="postcard">';
						echo $msgErr;
						echo'<textarea name="content" rows="5" style="width: 100%">';echo $content ;echo'</textarea>
						<br><br>
						<input type="submit" name="'; echo $confedit;echo'" value="Edit Post">
						<input type="submit" name="'; echo $cancel;echo'" value="Cancel Edit" class="btn btn-danger">  
					</form>';
				}
				else // Print Content
				{
					echo 'Content: ';echo $content;
				}
			}
			else {echo 'Content: '; echo $content;}
		?>
	</div>
	<br>
	<!-- upvotes -->
	Upvotes: <?php echo $upvotes ?>
	<form onsubmit="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
		<input type="submit" name="<?php echo $upID ?>" value="Upvote" class="btn btn-success">
		<input type="submit" name="<?php echo $downID ?>" value="Downvote" class="btn btn-danger">
	</form>
</div>

