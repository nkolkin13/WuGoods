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
			<?php
				$con=mysqli_connect("localhost","root","","wubooksdb");
				// Check connection
				if (mysqli_connect_errno()) {
				  echo "Failed to connect to MySQL: " . mysqli_connect_error();
				}
				$result=mysqli_query($con, "SELECT * FROM itemsforsale");
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
