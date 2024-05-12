<?php
  
  $credentials_check = file_get_contents('http://169.254.169.254/latest/meta-data/iam/security-credentials/');
  if ($credentials_check == ''){
    exit('<span style="color:red">Unable to retrieve AWS credentials. Please assign an IAM Role to this instance.</span>');
  }

  include('get-parameters.php');

  if ($ep == '') {
   echo 'Please configure Settings to connect to database';
  }
  else {
    # Display inventory

    // Set incoming variables
    isset($_REQUEST['mode']) ? $mode=$_REQUEST['mode'] : $mode="";
    isset($_REQUEST['id']) ? $id=urldecode($_REQUEST['id']) : $id="";
    isset($_REQUEST['store']) ? $store=urldecode($_REQUEST['store']) : $store="";
    isset($_REQUEST['item']) ? $item=$_REQUEST['item'] : $item="";
    isset($_REQUEST['quantity']) ? $quantity=$_REQUEST['quantity'] : $quantity="";
  
      
    // Connect to the RDS database
    $connect = mysqli_connect($ep, $un, $pw) or die(mysqli_error($connect));
  
    mysqli_select_db($connect, $db) or die(mysqli_error($connect));
  
  if ( $mode=="add")
   {
   Print '<h2>Add Inventory</h2>
   <p>
   <form action=';
   echo $_SERVER['PHP_SELF'];
   Print '
   method=post>
   <table>
   <tr><td>Store:</td><td><input type="text" name="store" /></td></tr>
   <tr><td>Item:</td><td><input type="text" name="item" /></td></tr>
   <tr><td>Quantity:</td><td><input type="text" name="quantity" /></td></tr>
   <tr><td colspan="2" align="center"><input type="submit" class="blue-button"/></td></tr>
   <input type=hidden name=mode value=added>
   </table>
   </form> <p>';
   }
  
   if ( $mode=="added")
   {
   mysqli_query ($connect, "INSERT INTO inventory (store, item, quantity) VALUES ('$store', '$item', $quantity)");
   }
  
  if ( $mode=="edit")
   {
   Print '<h2>Edit Inventory</h2>
   <p>
   <form action=';
   echo $_SERVER['PHP_SELF'];
   Print '
   method=post>
   <table>
   <tr><td>Store:</td><td><input type="text" value="';
   Print $store;
   print '" name="store" /></td></tr>
   <tr><td>Item:</td><td><input type="text" value="';
   Print $item;
   print '" name="item" /></td></tr>
   <tr><td>Quantity:</td><td><input type="text" value="';
   Print $quantity;
   print '" name="quantity" /></td></tr>
   <tr><td colspan="3" align="center"><input type="submit" class="blue-button" /></td></tr>
   <input type=hidden name=mode value=edited>
   <input type=hidden name=id value=';
   Print $id;
   print '>
   </table>
   </form> <p>';
   }
  
   if ( $mode=="edited")
   {
    error_log("UPDATE inventory SET store = '$store', item = '$item', quantity = $quantity WHERE id = $id");
   mysqli_query ($connect, "UPDATE inventory SET store = '$store', item = '$item', quantity = $quantity WHERE id = $id");
   Print "Data Updated!<p>";
   }
  
  if ( $mode=="remove")
   {
   mysqli_query ($connect, "DELETE FROM inventory where id=$id");
   Print "Entry has been removed <p>";
   }
  
   $data = mysqli_query($connect, "SELECT * FROM inventory ORDER BY id ASC") or die(mysqli_error($connect));
   Print "<table id='inventory' border cellpadding=3>";
   Print "<tr><th width=10/><th width=10/> " .
     "<th>Store</th> " .
     "<th>Item</th> " .
     "<th>Quantity</th></tr>";
   while($info = mysqli_fetch_array( $data ))
   {
   Print "<tr><td><a href=" .$_SERVER['PHP_SELF']. "?id=" . $info['id'] ."&mode=remove><i class='fas fa-trash-alt' style='color:#d82323;'></i></a></td>";
   Print "<td><a href=" .$_SERVER['PHP_SELF']. "?id=" . $info['id'] ."&store=" . urlencode($info['store']) . "&item=" . urlencode($info['item']) . "&quantity=" . $info['quantity'] ."&email=" . "&mode=edit><i class='fas fa-edit'></i></a></td>";
   Print "<td>".$info['store'] . "</td> ";
   Print "<td>".$info['item'] . "</td> ";
   Print "<td>".$info['quantity'] . "</td> ";
   Print "<tr>";
   }
   Print "</table>";
   Print "<br/><a href=" .$_SERVER['PHP_SELF']. "?mode=add class='blue-button'><i class='fas fa-plus'></i> Add Inventory</a>";
  }
?>
