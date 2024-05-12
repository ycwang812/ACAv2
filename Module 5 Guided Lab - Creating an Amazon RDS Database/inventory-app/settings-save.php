<!DOCTYPE html>
<html>
<head>
  <title>Inventory System</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
</head>

  <body>
    <div class="container">

      <div class="row">
      <div class="col-md-12">
      <?php include('menu.php'); ?>

      <div class="jumbotron">

      <?php
      
        # Extract fields from input form
        $ep = $_POST['endpoint'];
      	$ep = str_replace(":3306", "", $ep);
      	$db = $_POST['database'];
        $un = $_POST['username'];
        $pw = $_POST['password'];

        # Store settings in Parameter Store
        error_log('Saving settings');
        require 'aws-autoloader.php';
  
        $az = file_get_contents('http://169.254.169.254/latest/meta-data/placement/availability-zone');
        $region = substr($az, 0, -1);
        
        $ssm_client = new Aws\Ssm\SsmClient([
          'version' => 'latest',
          'region'  => $region
        ]);
        
        # Save settings in Parameter Store
        $result = $ssm_client->putParameter([
          'Name' => '/inventory-app/endpoint',
          'Overwrite' => true,
          'Type' => 'String',
          'Value' => $ep
        ]);

        $result = $ssm_client->putParameter([
          'Name' => '/inventory-app/username',
          'Overwrite' => true,
          'Type' => 'String',
          'Value' => $un
        ]);

        $result = $ssm_client->putParameter([
          'Name' => '/inventory-app/password',
          'Overwrite' => true,
          'Type' => 'SecureString',
          'Value' => $pw
        ]);

        $result = $ssm_client->putParameter([
          'Name' => '/inventory-app/db',
          'Overwrite' => true,
          'Type' => 'String',
          'Value' => $db
        ]);

        # Try to connect to database
        $connect = mysqli_connect($ep, $un, $pw);
        if(!$connect) {
          # Failed to connect
          echo "<br /><p>Unable to Establish Database Connection<i>" . mysqli_error($connect) .  "</i></p>";

        } else {

          $dbconnect = mysqli_select_db($connect, $db);
          if(!$dbconnect) {
            # Failed to find database
            echo "<br /><p>Connected to Database but DB not found<i>" . mysqli_error($connect) .  "</i></p>";

          } else {
            # Load initial data
            echo "<br /><p>Loading initial data...</p>";
            exec("mysql -u $un -p$pw -h $ep $db < sql/inventory.sql");
          }
          
          # Send them back to home page
          echo "<script>window.location.href ='/';</script>";

        }

        # Close database connection
        mysqli_close($connect);

      ?>

    </div>
    </div>
  </div>
  </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
  </body>
</html>
