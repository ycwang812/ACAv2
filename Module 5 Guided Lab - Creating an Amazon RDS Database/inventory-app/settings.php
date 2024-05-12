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
      
      <?php include('get-parameters.php'); ?>
      
			<div class="jumbotron">

        <?php include ('settings-form.php');?>
        
      </div>
    </div>
  </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/scripts.js"></script>

</body>
</html>
