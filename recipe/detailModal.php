<?php
require '../utilities/init.php';
require '../utilities/tools.php';

$beerId = $_GET['beerId'];
?>

<style>
    #ingredientList {
        margin-top: 20px;
    }
</style>

<div>
    <h3 id="beerName" class="inline"></h3>
    <h5 id="beerType" class="inline sub-header gray"></h5>
    
    <div id="ingredientList"></div>
    
    <div id="errorMessage" class="alert alert-danger text-center" role="alert" style="display: none;"></div>
</div>

<script>
    $.getJSON("<?= getBaseUrl(); ?>api/beer/getDetail.php", {"beerId": <?= $beerId; ?>}, function(jsonData) {
        if(!jsonData.success) {
            console.log(jsonData);
            showError(jsonData.error);
            return;
        }
        
        var beer = jsonData.result;
        
        $("#beerName").html(beer.name);
        $("#beerType").html(beer.beerType);
        
        console.log(beer);
        
        if(beer.ingredients.length === 0) {
            $("#ingredientList").html("This recipe has no ingredients listed.");
        }
        
        beer.ingredients.forEach(function(ingredient) {
            console.log(ingredient);
            
            var text = $("<p></p>");
            text.html(ingredient.quantity + " " + 
                    ingredient.units + ". " + 
                    ingredient.name);
            
            $("#ingredientList").append(text);
        });
    });
</script>