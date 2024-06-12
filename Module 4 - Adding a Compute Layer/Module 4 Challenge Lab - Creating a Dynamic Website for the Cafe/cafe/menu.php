<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Caf&eacute; Menu</title>
<link rel="stylesheet" href="css/styles.css">
<link rel="stylesheet" href="css/menu.css">
</head>

<body class="bodyStyle">

	<div id="header" class="mainHeader">
		<hr>
		<div class="center">Caf&eacute;</div>
	</div>
	<br>
	<?php
		// Get the application environment parameters from the Parameter Store.
		include ('getAppParameters.php');

		// Display the server metadata information if the showServerInfo parameter is true.
		include('serverInfo.php');
	?>
	<hr>
	<div class="topnav">
		<a href="index.php">Home</a>
		<a href="menu.php" class="active">Menu</a>
		<a href="orderHistory.php">Order History</a>
	</div>

<?php

// Create a connection to the database.

$conn = new mysqli($db_url, $db_user, $db_password, $db_name);

// Check the connection.

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all rows from the product table.

$sql = "SELECT a.id, a.product_name, a.description, a.price, b.product_group_number, b.product_group_name, a.image_url
        FROM product a, product_group b
        WHERE b.product_group_number = a.product_group
        ORDER BY b.product_group_number, a.id";

$result = $conn->query($sql);

$numOfItems = $result->num_rows;

if ($numOfItems > 0) {

    // Display each returned item in a form.

	echo '<form id="orderForm" action="processOrder.php" method="post" onsubmit="return validateOrder()">';

	$previousProductGroupNumber = 0;

	// output data of each row
	while($row = $result->fetch_assoc()) {

	    if ($row["product_group_number"] != $previousProductGroupNumber) {

            echo '<hr>';
            echo '<div class="cursiveText">';
            echo '<p>' . $row["product_group_name"] . '</p>';
            echo '</div>';

	        $previousProductGroupNumber = $row["product_group_number"];
	    }

	    $price = number_format($row["price"], 2);

	    echo '	<div class="column">';
	    echo '			<div class="card">';
	    echo '				<img src="' . $row["image_url"] . '" style="width: 100%">';
	    echo '				<div class="container">';
	    echo '					<h2 class="productTitle">' . $row["product_name"] . '</h2>';
	    echo '					<p class="center">' . $currency . $price . '</p>';
	    echo '					<p class="center">' . $row["description"] . '</p>';
	    echo '					<input type="hidden" name="productId[]" value="' . $row["id"] . '">';
	    echo '					<input type="hidden" name="productName[]" value="' . $row["product_name"] . '">';
	    echo '					<input type="hidden" name="price[]" value=' . $price . '>';
	    echo '					<div class="center">';
	    echo '						Quantity: <input name="quantity[]" type="number" min="0" max="15" value="0" maxlength="2" onchange="updateTotal(' . $row["id"] . ', this.value, ' . $price . ')">';
	    echo '					</div>';
	    echo '					<br>';
	    echo '				</div>';
	    echo '			</div>';
	    echo '		</div>';

	}

	echo '<div>';
	echo '	<p class="center">';
	echo '		Order Total: ' . $currency . '<span id="orderTotal"></span>';
	echo '	</p>';
	echo '</div>';
	echo '<br> <input type="Submit" value="Submit Order" class="button">';
	echo '<br> <br> <input type="Reset" value="Reset Order" class="button" onclick="resetForm()">';
	echo '</form>';

} else {
    echo '<br><p class="center">There are no items on the menu.</p>';
}

// Close the connection.

$conn->close();
?>

	<div id="Copyright" class="center">
		<h5>&copy; 2020, Amazon Web Services, Inc. or its Affiliates. All rights reserved.</h5>
	</div>

	<script>

		/* Initialize order total. */

		document.getElementById("orderTotal").innerHTML = "0.00";
<?php
    echo 'var itemTotals = new Array(' . $numOfItems . ');'
?>

		var i;
		for (i = 0; i < itemTotals.length; i++) {
			itemTotals[i]=0.00;
		}

		/* Function to calculate order total */

		function calculateOrderTotal() {

			var orderTotal = 0.00;

			var i;
			for (i = 0; i < itemTotals.length; i++) {
				orderTotal += itemTotals[i];
			}
			return orderTotal;
		}

		/* Function to reset form */

		function resetForm() {

			document.getElementById("orderForm").reset();
			document.getElementById("orderTotal").innerHTML = "0.00";
			var i;
			for (i = 0; i < itemTotals.length; i++) {
			  itemTotals[i] = 0.00;
			}
		}

		/* Function to update order total when quantities change */

		function updateTotal(itemNo, quantity, price) {

			var amount = quantity * price;
			itemTotals[itemNo] = amount;

			var totalAmount = calculateOrderTotal().toFixed(2);
			document.getElementById("orderTotal").innerHTML = totalAmount;

		}

		/* Function to validate the order amount */

		function validateOrder() {

			if (calculateOrderTotal() <= 0.0) {
				alert('Please select at least one item to buy.');
				return false;
			}
		}
	</script>

</body>
</html>
