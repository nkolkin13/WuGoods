<?php
	$errors = array();
	//handle posting the name field
	if (empty($_POST["name"])) {
		$errors['name'] = 'name DNE';
	} else {
		$name = test_input($_POST["name"]);
		// make sure name only has letters and whitespace
		if (!preg_match("/^[a-zA-Z ]*$/", $name)){
			$errors['name'] = 'name included invalid characters';
		}
	}

	//handle posting the item name field
	if (empty($_POST["itemName"])) {
		$errors['itemName'] = 'itemName DNE';
	} else {
		$itemName = test_input($_POST["itemName"]);
		// make sure name only has letters and whitespace
		if (!preg_match("/^[a-zA-Z ]*$/", $itemName)){
			$errors['itemName'] = 'itemName included invalid characters';
		}
	}

	//handle posting the price field
	if (empty($_POST["price"])) {
		$errors['price'] = 'price DNE';
	} else {
		$price = test_input($_POST["price"]);
		// make sure name only has letters and whitespace
		if (!preg_match("/^[0-9\.]{1,}$/", $price)){
			$errors['itemName'] = 'price not a valid integer';
		}
	}		

	if(!$errors){
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
	}

	header("location:postItem.php");   

	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
?>