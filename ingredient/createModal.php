<?php
require '../utilities/init.php';
require '../utilities/tools.php';

?>
<form id="createIngredientForm" method="post" action="<?php echo getBaseUrl(); ?>api/ingredient/create.php">
    <div id="errorMessage" class="alert alert-danger text-center" role="alert" style="display: none;"></div>
    <div class="form-group">
        <label for="name">Ingredient Name</label>
        <input type="text" class="form-control" id="name" name="name" maxlength="30" required>
    </div>
    <div class="form-group">
        <label for="supplier">Supplier</label>
        <input type="text" class="form-control" id="supplier" name="supplier" maxlength="30">
    </div>
    <div class="form-group">
        <label for="quantity">Quantity</label>
        <input type="number" class="form-control" id="quantity" name="quantity" value='0' required>
    </div>
    <div class="form-group">
        <label for="unitId">Units</label>
        <select name="unitId" class="form-control">
            <?php 
            $units = Database::runQuery("SELECT * FROM unit");
            foreach($units as $unit) {
                echo "<option value='{$unit['id']}'>{$unit['name']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="lowValue">Low Value</label>
        <input type="number" class="form-control" id="lowVale" name="lowValue" value='0' required>
    </div>
</form>
