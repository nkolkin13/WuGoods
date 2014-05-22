<!doctype html>
<head>
	<meta charset = "utf-8">
	<title>WashU Goods Exchange</title>
	<meta name = "description" content="Welcome to the Homepage">
	<link rel = "stylesheet" href="css/style.css?v=1">
	<script type = "text/javascript" src="jscripts/fadeEffects.js"></script>
</head>

<body>
	<div id = "wrapper">
		<header>
			<h1>Item Browse</h1>

			<nav>
				<ul>
					<li> <a rel="external" href = "#">Home</a></li>
					<li> <a rel="external" href = "#">Sell</a></li>
					<li> <a rel="external" href = "#">Contact</a></li>
				</ul>
			</nav>
		</header>

		<div id = "itemListings" class="clearfix">
			<form method= "post">
				Seller Name Contains: <input type="text" name="sellerNameQuery"> Item Name Contains: <input type="text" name="itemNameQuery">
				<br>Tags: <input type = "checkbox" name="furnitureCheck" value = "FURN">Furniture
					<input type = "checkbox" name="bookCheck" value = "BOOK">Book
					<input type = "checkbox" name="miscCheck" value = "MISC">Misc
					<br>Sort By: <select name="sortCriteriaDrop">
					<option value="priceASC">Price, Low to High</option>
					<option value="priceDESC">Price, High to Low</option>
				</select>

				<input type="submit" name="postItem" value = "Search">
			</form>
			<?php

				//define search
				$searchQuery = "";
				if (!empty($_POST['sellerNameQuery'])){ $searchQuery .= "AND sellerName LIKE '%".$_POST['sellerNameQuery']."%'"; }
				if (!empty($_POST['itemNameQuery'])){ $searchQuery .= "AND itemTitle LIKE '%".$_POST['itemNameQuery']."%'"; }

				//defines query
				$query = "";
				if(isset($_POST['furnitureCheck'])){
					$query .= " FURN";
				}
				if(isset($_POST['bookCheck'])){
					$query .= " BOOK";
				}
				if(isset($_POST['miscCheck'])){
					$query .= " MISC";
				}

				//defines sorting
				$sortCriteria = "";
				if (!empty($_POST['sortCriteriaDrop'])) {
					if($_POST['sortCriteriaDrop'] == 'priceASC'){
						$sortCriteria = 'ORDER BY dollarValue ASC';
					}
					if($_POST['sortCriteriaDrop'] == 'priceDESC'){
						$sortCriteria = 'ORDER BY dollarValue DESC';
					}
				}
				$con=mysqli_connect("localhost","root","","wubooksdb");
				// Check connection
				if (mysqli_connect_errno()) {
				  echo "Failed to connect to MySQL: " . mysqli_connect_error();
				}
				$result=mysqli_query($con, "SELECT * FROM itemsforsale WHERE category LIKE '%$query%' $searchQuery $sortCriteria");
				while ($row = mysqli_fetch_array($result)){
					echo '<div class ="itemPreview"';
					echo '<h2><a rel="external" href= "#">' . $row["itemTitle"] . "</a></h2>";
					echo "<p>Seller's Name: " .$row["sellerName"] . "</p>"; 
					echo "<p>Price: $" .$row["dollarValue"] . ".00</p>"; 
					echo '<img src= "'.$row["picLocation"]. '" alt = "' .$row["picLocation"]. '">';
					echo "</div>";
				}

			?>
		</div>

		<footer>
			<p>Legal bullshit</p>
		</footer>

	</div>

</body>
</html>
