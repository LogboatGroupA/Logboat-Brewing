<?php
require '../utilities/init.php';
require '../utilities/tools.php';

$ingredients = Database::runQuery("SELECT ing.id, ing.name, unit.name AS units FROM ingredient AS ing INNER JOIN unit on ing.unitId = unit.id ORDER BY ing.name");

?>

<style>
    #addIngWrapper {
        margin-top: 12px;
    }
</style>

<form id="createBeerRecipeForm" method="post" action="<?php echo getBaseUrl(); ?>api/beer/create.php">
    <div id="errorMessage" class="alert alert-danger text-center" role="alert" style="display: none;"></div>
    <div class="form-group">
        <label for="name">Beer Name</label>
        <input type="text" class="form-control" id="name" name="name" maxlength="50" required>
    </div>
    <div class="form-group">
        <label for="beerTypeId">Beer Type</label>
        <select name="beerTypeId" class="form-control">
            <?php 
            $beerTypes = Database::runQuery("SELECT * FROM beerType ORDER BY name");
            foreach($beerTypes as $beerType) {
                echo "<option value='{$beerType['id']}'>{$beerType['name']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="ingredients">Ingredients</label>
        <div id="firstIng" class="input-multi-container clearfix">
            <select name="ingredient0" class="ingredient form-control input-multi">
                <?php
                foreach($ingredients as $ingredient) {
                    echo "<option value='{$ingredient['id']}'>{$ingredient['name']} ({$ingredient['units']})</option>";
                }
                ?>
            </select>
            <input type="number" class="form-control input-multi" name="quantity" step=".01" value="0.00" min="0.01" max="20.00">
        </div>
        <div id="addIngWrapper">
            <button id="addIngredient" type="button" class="btn btn-xs btn-primary">Add new</button>
        </div>
    </div>
</form>

<script>
    var numIngredients = 1;
    
    $("#addIngredient").click(function() {
        
        $("#firstIng").clone().insertBefore("#addIngWrapper")
                .children("select").attr('name', 'ingredient' + numIngredients++);
    });
</script>