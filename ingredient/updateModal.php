<?php
require '../utilities/init.php';
require '../utilities/tools.php';

$data = Database::runQuery("SELECT * FROM ingredient WHERE id = :id", array("id" => $_GET['ingredientId']));
$data = $data[0]; // Grab the first result (should only be one)

?>
<form id="updateIngredientForm" method="post" action="<?php echo getBaseUrl(); ?>api/ingredient/update.php">
    <input type="hidden" name="id" id="ingredientId" value="<?php echo $data['id']; ?>">
    <div id="errorMessage" class="alert alert-danger text-center" role="alert" style="display: none;"></div>
    <div class="form-group">
        <label for="name">Ingredient Name</label>
        <input type="text" class="form-control" id="name" name="name" maxlength="30" required value="<?php echo $data['name']; ?>">
    </div>
    <div class="form-group">
        <label for="supplier">Supplier</label>
        <input type="text" class="form-control" id="supplier" name="supplier" maxlength="30" value="<?php echo $data['supplier']; ?>">
    </div>
    <div class="form-group">
        <label for="quantity">Quantity</label>
        <input type="number" class="form-control" id="quantity" name="quantity" value='<?php echo $data['quantity'] ?>' required>
    </div>
    <div class="form-group">
        <label for="unitId">Units</label>
        <select name="unitId" class="form-control">
            <?php 
            $units = Database::runQuery("SELECT * FROM unit");
            foreach($units as $unit) {
                if($unit['id'] == $data['unitId']) {
                    $selected = "selected";
                } else {
                    $selected = "";
                }
                echo "<option value='{$unit['id']}' $selected>{$unit['name']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="lowValue">Low Value</label>
        <input type="number" class="form-control" id="lowValue" name="lowValue" value='<?php echo $data['lowValue'] ?>' required>
    </div>
</form>
