<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Caf&eacute; Order History</title>
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
		<a href="index.php">Home</a>
		<a href="menu.php">Menu</a>
		<a href="orderHistory.php" class="active">Order History</a>
	</div>

	<hr>
	<div class="cursiveText">
		<p>Order History</p>
	</div>

<?php

// Create a connection to the database.

$conn = new mysqli($db_url, $db_user, $db_password, $db_name);

// Check the connection.
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve all orders in the database.

$sql = "SELECT a.order_number, a.order_date_time, a.amount as order_total,
               b.order_item_number, b.product_id, b.quantity, b.amount as item_amount,
               c.product_name, c.price
        FROM `order` a, order_item b, product c
        WHERE a.order_number = b.order_number
          AND c.id = b.product_id
        ORDER BY a.order_number DESC, b.order_item_number ASC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

    // Display information for each order.

    $previousOrderNumber = 0;
    $firstTime = true;

    while($row = $result->fetch_assoc()) {

        if ($row["order_number"] != $previousOrderNumber) {

            if (!$firstTime) {
                echo '</table>';
                echo '</div>';
                echo '<hr>';
            }

            echo '<div>';
            echo '<p>';
            echo '<b>Order Number: ' . $row["order_number"] . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date: ' . substr($row["order_date_time"], 0, 10)
            . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Time: ' . substr($row["order_date_time"], 11, 8) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Amount: ' . $currency . number_format($row["order_total"], 2) . '</b>';
            echo '</p>';

            echo '<table style="width: 80%">';
            echo '<tr>';
            echo '<th>Item</th>';
            echo '<th>Price</th>';
            echo '<th>Quantity</th>';
            echo '<th>Amount</th>';
            echo '</tr>';

            $previousOrderNumber = $row["order_number"];
            $firstTime = false;
        }
        echo '<tr>';
        echo '<td align="center">' . $row["product_name"] . '</td>';
        echo '<td align="center">' . $currency . $row["price"]. '</td>';
        echo '<td align="center">' .$row["quantity"] . '</td>';
        echo '<td align="center">' . $currency . number_format($row["item_amount"], 2) . '</td>';
        echo '</tr>';

    }

} else {

    echo '<p class="center">You have no orders at this time.</p>';
}

// Close the last table division.

echo '</table>';
echo '</div>';
echo '<hr>';

// Close the connection.

$conn->close();
?>

	<br>
	<div id="Copyright" class="center">
		<h5>&copy; 2020, Amazon Web Services, Inc. or its Affiliates. All rights
			reserved.</h5>
	</div>

</body>
</html>
