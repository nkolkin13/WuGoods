<?php
	//Setting up connection to database
	$con=mysqli_connect("localhost","root","","wubooksdb");
	//Check connection
	if(mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$sql="CREATE TABLE itemsForSale(sellerName CHAR(30),itemTitle CHAR(30),dollarValue INT)";
	if (mysqli_query($con, $sql)){
		echo "table itemsForSale created successfully";
	} else {
		echo "Error creating database: " . mysqli_error($con);
	}

	// escape variables for security
	$name = mysqli_real_escape_string($con, $_POST['name']);
	$itemName = mysqli_real_escape_string($con, $_POST['itemName']);
	$price = mysqli_real_escape_string($con, $_POST['price']);

	$sql="INSERT INTO itemsForSale (sellerName, itemTitle, dollarValue) VALUES ('$name', '$itemName', '$price')";

	if (!mysqli_query($con,$sql)) {
	  die('Error: ' . mysqli_error($con));
	}
	echo "1 record added";

	mysqli_close($con);

	header("location:postItem.php");   
?>