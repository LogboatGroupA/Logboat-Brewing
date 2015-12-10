<?php
require '../utilities/init.php';
require '../utilities/tools.php';

?>
<form id="createKegForm" method="post" action="<?php echo getBaseUrl(); ?>api/keg/create.php">
    <div id="errorMessage" class="alert alert-danger text-center" role="alert" style="display: none;"></div>
    <div class="form-group">
        <label for="name">Keg Serial Number</label>
        <input type="text" class="form-control" id="serialNum" name="serialNum" maxlength="50" required>
    </div>
</form>
