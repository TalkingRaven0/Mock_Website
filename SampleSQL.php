<?php
// THIS FILE IS ONLY FOR UPLOADING STUFF TO THE DATABASE

session_start();

// Variables
$x=$_SESSION['username'];
$y=$_SESSION['email'];
$z=$_SESSION['pass'];
$img=$_SESSION['file'];
$target_dir = "uploads/";
$target_file = $target_dir.basename($img["name"]));

include 'dbtemp.php';

echo "Connected Successfully";

$sql = "INSERT INTO userinfo (username,email,pass,filename, datecreated) VALUES ('$x','$y','$z',$img["name"], NOW())";

if (move_uploaded_file($img["tmp_name"], $target_file))
{
	if ($conn->query($sql) === TRUE)
	{
		echo "File Successfully Uploaded";
	}
}

else
{
	echo"Error in Creating Record". $sql."<br>". $conn->error;
}

// Display Data ONLY FOR TESTING

$sql = "SELECT id, username, email, pass FROM userinfo";
$result = $conn->query($sql);

if ($result-> num_rows > 0)
{
	//Output data of each row
	echo "<table>";
	while($row = $result-> fetch_assoc())
	{
		// Don't Display the Failsafe person
		if ( $row["id"] == '0')
		{
			continue;
		}

		echo "<tr><td> id: ". $row["id"]. "</td><td> username: ". $row["username"]. "</td><td> Email:". $row["email"]. "</td><td> Password: ". $row["pass"]. "</td><tr>";
	}
	echo "</table>";
}
else
{
	echo "0 results";
}

// Display Data END

$conn->close();

// End of Signup Session
session_unset();
session_destroy();

// Insert Go to New webpage here

?>