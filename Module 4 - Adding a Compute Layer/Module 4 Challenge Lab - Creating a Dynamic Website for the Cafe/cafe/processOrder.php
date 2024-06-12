<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Caf&eacute; Order Confirmation</title>
<link rel="stylesheet" href="css/styles.css">
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
		<a href="index.php">Home</a> <a href="menu.php">Menu</a> <a
			href="orderHistory.php">Order History</a>
	</div>

	<hr>
	<div class="cursiveText">
		<p>Order Confirmation</p>
	</div>

<?php

// Create a connection to the database.

$conn = new mysqli($db_url, $db_user, $db_password, $db_name);

// Check the connection.

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get order information from submitted form.

$productIds = $_POST["productId"];
$productNames = $_POST["productName"];
$prices = $_POST["price"];
$quantities = $_POST["quantity"];

// Calculate order item amounts and total order amount.

$amounts = new SplFixedArray(sizeof($productIds));
$totalAmount = 0.00;

for ($i = 0; $i < sizeof($amounts); $i++) {

    $amounts[$i] = $prices[$i] * $quantities[$i];
    $totalAmount += $amounts[$i];
}

// Insert ORDER row.

date_default_timezone_set($timeZone);
// date_default_timezone_set("America/New_York");
// date_default_timezone_set("America/Los_Angeles");
// date_default_timezone_set("Europe/London");

$currentTimeStamp = date('Y-m-d H:i:s');

$sql = "INSERT INTO `order` (order_date_time, amount) VALUES ('$currentTimeStamp', $totalAmount)";

if ($conn->query($sql) === TRUE) {
    $orderNumber = $conn->insert_id;
} else {
    die ("Error: " . $sql . "<br>" . $conn->error);
}

// Insert ORDER_ITEM rows.

$itemNo = 1;

for ($i = 0; $i < sizeof($amounts); $i++) {

    if ($amounts[$i] != 0.00) {

        $sql = "INSERT INTO order_item (order_number, order_item_number, product_id, quantity, amount)
                       VALUES ($orderNumber, $itemNo, $productIds[$i], $quantities[$i], $amounts[$i]);";

        if ($conn->query($sql) === TRUE) {
            $itemNo += 1;
        } else {
            die ("Error: " . $sql . "<br>" . $conn->error);
        }
    }
}

// Close the connection.

$conn->close();
?>
	<p class="center">Thank for your order! It will be available for pickup
		within 15 minutes. Your order number and details are shown below.</p>

<?php

echo '<div>';
echo '<p>';
echo '<b>Order Number: ' . $orderNumber . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date: ' . substr($currentTimeStamp, 0, 10)
. '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Time: ' . substr($currentTimeStamp, 11, 8) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Amount: ' . $currency . number_format($totalAmount, 2) . '</b>';
echo '</p>';

echo '<table style="width: 80%">';
echo '<tr>';
echo '<th>Item</th>';
echo '<th>Price</th>';
echo '<th>Quantity</th>';
echo '<th>Amount</th>';
echo '</tr>';

for ($i = 0; $i < sizeof($amounts); $i++) {

    if ($amounts[$i] != 0.00) {

        echo '<tr>';
        echo '<td align="center">' . $productNames[$i] . '</td>';
        echo '<td align="center">' . $currency . $prices[$i]. '</td>';
        echo '<td align="center">' .$quantities[$i] . '</td>';
        echo '<td align="center">' . $currency . number_format($amounts[$i], 2) . '</td>';
        echo '</tr>';

    }
}
echo '</table>';
echo '</div>';

?>
	<br>
	<div id="Copyright" class="center">
		<h5>&copy; 2020, Amazon Web Services, Inc. or its Affiliates. All rights
			reserved.</h5>
	</div>

</body>
</html>
