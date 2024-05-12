<form name="input" action="settings-save.php" method="post" class="form-horizontal">
  <div class="form-group">
    <label for="endpoint" class="col-sm-2 control-label">Endpoint</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="endpoint" value="<?= $ep ?>">
    </div>
  </div>

  <div class="form-group">
    <label for="database" class="col-sm-2 control-label">Database</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="database" value="<?= $db ?>">
    </div>
  </div>

  <div class="form-group">
    <label for="username" class="col-sm-2 control-label">Username</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="username" value="<?= $un ?>">
    </div>
  </div>

  <div class="form-group">
    <label for="password" class="col-sm-2 control-label">Password</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="password" value="<?= $pw ?>">
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="submit" value="Save" class="btn btn-default"/>
    </div>
  </div>
</form>
