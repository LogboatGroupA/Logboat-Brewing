<?php
require '../utilities/init.php';
require '../utilities/tools.php';

$ingredients = Database::runQuery("SELECT ing.id, ing.name, unit.name AS units FROM ingredient AS ing INNER JOIN unit on ing.unitId = unit.id ORDER BY ing.name");

?>

<form id="createBeerRecipeForm" method="post" action="<?php echo getBaseUrl(); ?>api/beer/create.php">
    <div id="errorMessage" class="alert alert-danger text-center" role="alert" style="display: none;"></div>
    <div class="form-group">
        <label for="name">Beer Name</label>
        <input type="text" class="form-control" id="name" name="name" maxlength="50" required>
    </div>
    <div class="form-group">
        <div class="clearfix">
            <label for="beerTypeId" class="left">Beer Type</label>
            <button id="newTypeButton" type="button" class="btn btn-xs btn-default right">+</button>
        </div>
        <select id="beerType" name="beerTypeId" class="form-control">
            <?php 
            $beerTypes = Database::runQuery("SELECT * FROM beerType ORDER BY name");
            foreach($beerTypes as $beerType) {
                echo "<option value='{$beerType['id']}'>{$beerType['name']}</option>";
            }
            ?>
        </select>
        <div id="newTypeWrapper" class="input-multi-container clearfix" hidden="true">
            <input id="newTypeName" type="text" class="form-control input-multi" maxlength="50">
            <button id="addNewType" type="button" class="btn btn-xs btn-default input-multi-btn-xs">+</button>
        </div>
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
            <input type="number" class="form-control input-multi" name="quantity0" step=".01" value="0.00" min="0.01" max="20.00">
        </div>
        <div id="addIngWrapper" class="input-multi-add">
            <button id="addIngredient" type="button" class="btn btn-xs btn-primary">Add new</button>
        </div>
    </div>
</form>

<script>
    var numIngredients = 1;
    
    //Clicked when user wants to add new beer type
    $("#newTypeButton").click(function() {
        var newButton = $(this);
        
        if(newButton.html() === "+") {
            $("#beerType").hide();
            $("#newTypeWrapper").show();
            newButton.html("-");
            
        } else {
            $("#beerType").show();
            $("#newTypeWrapper").hide();
            newButton.html("+");
        }
        
        newButton.blur();
    });
    
    //Clicked when user wants save new beer type
    $("#addNewType").click(function() {
        var name = $("#newTypeName").val();
        
        if(name.length === 0) {
            showError("Please provide a name for the new beer type");
            return;
        }
        
        $.post("<?= getBaseUrl(); ?>api/beer/createType.php", {"name": name }, function(jsonData) {
            if(!jsonData.success) {
                console.log(jsonData);
                showError(jsonData.error);
                return;
            }
            
            var option = $("<option></option>");
            option.val(jsonData.result.id);
            option.html(name);
            
            $("#beerType").append(option);
            $("#beerType").val(option.val());
            
            $("#newTypeButton").click();
        });
    });
    
    //Adds a new ingredient input to the recipe form (indefinite number)
    $("#addIngredient").click(function() {
        
        var firstIng = $("#firstIng").clone()
                .attr('id', '').insertBefore("#addIngWrapper");
        
        firstIng.children("select").attr('name', 'ingredient' + numIngredients);
        
        firstIng.children("input").attr('name', 'quantity' + numIngredients++);
    });
</script>