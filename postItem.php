<!doctype html>
<head>
	<meta charset = "utf-8">
	<title>WashU Goods Exchange</title>
	<meta name = "description" content="Post Something for Sale">
	<link rel = "stylesheet" href="css/style.css?v=1">
</head>

<body>
	<!--BACKEND--> 
	<?php

		define('SITE_ROOT', realpath(dirname(__FILE__)));
		$errors = array();
		$nameErr = $itemNameErr = $priceErr = $picErr = "";
		$name = $itemName = $price = $itemPicture = $itemPictureLocation = "";

		//inserts data into table
		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			//handle posting the name field
			if (empty($_POST["name"])) {
				$nameErr = "Name is required";
				$errors['name'] = 'name DNE';
			} else {
				$name = test_input($_POST["name"]);
				// make sure name only has letters and whitespace
				if (!preg_match("/^[a-zA-Z ]*$/", $name)){
					$nameErr = "Only letters and whitespace allowed";
					$errors['name'] = 'name included invalid characters';
				}
			}
		
			//handle posting the item name field
			if (empty($_POST["itemName"])) {
				$itemNameErr = "Item name is required";
				$errors['itemName'] = 'itemName DNE';
			} else {
				$itemName = test_input($_POST["itemName"]);
				// make sure name only has letters and whitespace
				if (!preg_match("/^[a-zA-Z ]*$/", $itemName)){
					$itemNameErr = "Only letters and whitespace allowed";
					$errors['itemName'] = 'itemName included invalid characters';
				}
			}

			//handle posting the price field
			if (empty($_POST["price"])) {
				$priceErr = "price is required";
				$errors['price'] = 'price DNE';
			} else {
				$price = test_input($_POST["price"]);
				// make sure name only has letters and whitespace
				if (!preg_match("/^[0-9\.]{1,}$/", $price)){
					$priceErr = "Needs to be a valid price (an integer)";
					$errors['itemName'] = 'price not a valid integer';
				}
			}

			//handle posting the item's picture
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			$temp = explode(".",$_FILES["itemPicture"]["name"]);
			$extension = end($temp);
			$picLocation = "";
			if ((($_FILES["itemPicture"]["type"] == "image/gif")
				||($_FILES["itemPicture"]["type"] == "image/jpeg")
				|| ($_FILES["itemPicture"]["type"] == "image/jpg")
				|| ($_FILES["itemPicture"]["type"] == "image/pjpeg")
				|| ($_FILES["itemPicture"]["type"] == "image/x-png")
				|| ($_FILES["itemPicture"]["type"] == "image/png"))
				&& ($_FILES["itemPicture"]["size"] < 60000)
				&& in_array($extension,$allowedExts)){

				if ($_FILES["itemPicture"]["error"] > 0){
					$picErr = $_FILES["itemPicture"]["error"];
					$errors['itemPicture']=$_FILES["itemPicture"]["error"];
				}else{
					if (file_exists("upload/".$_FILES["itemPicture"]["name"])){
						$picErr = "picture with same name already on server, please rename your file";
						$errors['itemPicture']="picture with same name already on server";
					}else{
						$picLocation = "upload/".$_FILES["itemPicture"]["name"];
						move_uploaded_file($_FILES["itemPicture"]["tmp_name"], SITE_ROOT."/".$picLocation);
					}
				}


			}else{
				$picErr = "No valid image file specified, default image used";
				$picLocation =  "upload/default.jpeg";
			}


			if(!$errors){
				//Setting up connection to database
				$con=mysqli_connect("localhost","root","","wubooksdb");
				//Check connection
				if(mysqli_connect_errno()){
					echo "Failed to connect to MySQL: " . mysqli_connect_error();
				}

				$sql="CREATE TABLE itemsForSale(sellerName CHAR(30),itemTitle CHAR(30),dollarValue INT, picLocation CHAR(255), category CHAR(255))";
				if (mysqli_query($con, $sql)){
					echo "table itemsForSale created successfully";
				} else {
					echo "Error creating database: " . mysqli_error($con);
				}
				$sql="ALTER TABLE itemsForSale ADD UNIQUE INDEX(sellerName, itemTitle)";

				//sets category
				$category = "";
				if(isset($_POST['furnitureCheck'])){
					$category .= " FURN";
				}
				if(isset($_POST['bookCheck'])){
					$category .= " BOOK";
				}
				if(isset($_POST['miscCheck'])){
					$category .= " MISC";
				}

				// escape variables for security
				$name = mysqli_real_escape_string($con, $_POST['name']);
				$itemName = mysqli_real_escape_string($con, $_POST['itemName']);
				$price = mysqli_real_escape_string($con, $_POST['price']);
				$itemPictureLocation= mysqli_real_escape_string($con, $picLocation);
				$itemTags= mysqli_real_escape_string($con, $category);

				$sql="INSERT IGNORE INTO itemsForSale (sellerName, itemTitle, dollarValue, picLocation, category) VALUES ('$name', '$itemName', '$price','$itemPictureLocation','$itemTags')";

				if (!mysqli_query($con,$sql)) {
				  die('Error: ' . mysqli_error($con));
				}
				$itemName = $name = $price = "";

				mysqli_close($con);
			}


		}


		

		function test_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}


	?>
	<!--END BACKEND--> 


	<div id = "wrapper">
		<header>
			<h1>Post an Item for Sale</h1>

			<nav>
				<ul>
					<li> <a rel="external" href = "#">Home</a></li>
					<li> <a rel="external" href = "#">Buy</a></li>
					<li> <a rel="external" href = "#">Contact</a></li>
				</ul>
			</nav>
		</header>

		<div id = "itemSubmission" class="clearfix">
			<p> <span class="error">* Required Field</span><p>
			<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">

				Item Name: <input type="text" name="itemName" value="<?php echo $itemName;?>"> <span class="error">* <?php echo $itemNameErr;?></span> <br>
				Seller Name: <input type="text" name="name" value="<?php echo $name;?>"><span class="error">* <?php echo $nameErr;?></span> <br>
				Price: $<input type="text" name="price" value="<?php echo $price;?>"><span class="error">* <?php echo $priceErr;?></span> <br>
				Picture: <input type="file" name="itemPicture" id="itemPicture"  value = "Upload Picture"><span class="error"><?php echo $picErr;?></span> <br>
				Tags: <br>
					-<input type = "checkbox" name="furnitureCheck" value = "FURN">Furniture<br>
					-<input type = "checkbox" name="bookCheck" value = "BOOK">Book<br>
					-<input type = "checkbox" name="miscCheck" value = "MISC">Misc<br>
				<input type="submit" name="postItem" value = "Post Item">
			</form>
		</div>

		<footer>
			<p>Legal bullshit</p>
		</footer>

	</div>

</body>
</html>
