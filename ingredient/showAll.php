<?php
require '../utilities/init.php';
require '../utilities/tools.php';

if(!isLoggedIn()) {
    header("Location: " . getBaseUrl() . "user/login.php");
    exit();
}
?>
<html>
    <head>
        <title>Logboat Brewing</title>
        <?php require '../utilities/links.php'; ?>
        
        <script>
            $(document).ready(function() {
                //Get all of the rows
                $.getJSON("../api/ingredient/getAll.php", function(data) {
                    $("#showAllLoading").fadeOut(400, function() {
                        for(var i = 0, len = data.result.length; i < len; i++) {
                            if (parseFloat(data.result[i].quantity) <= parseFloat(data.result[i].lowValue))
                            {
                                $("#getAllTable").append("<tr class='danger' data-ingredientId='" + data.result[i].id + "'><td>" +
                                                     data.result[i].name +
                                                     "</td><td>" +
                                                     data.result[i].supplier + 
                                                     "</td><td>" + 
                                                     data.result[i].quantity + 
                                                     "</td><td>" +
                                                     data.result[i].unitName +
                                                     "</td><td>" +
                                                     ( data.result[i].lowValue == null ? "No Low Value Used" : data.result[i].lowValue )+
                                                     "</td></tr>");
                            }
                            else
                            {
                                $("#getAllTable").append("<tr data-ingredientId='" + data.result[i].id + "'><td>" +
                                                     data.result[i].name +
                                                     "</td><td>" +
                                                     data.result[i].supplier + 
                                                     "</td><td>" + 
                                                     data.result[i].quantity + 
                                                     "</td><td>" +
                                                     data.result[i].unitName +
                                                     "</td><td>" +
                                                     ( data.result[i].lowValue == null ? "No Low Value Used" : data.result[i].lowValue )+
                                                     "</td></tr>");
                            }
                        }
                        
                        // Set all the rows to open modal
                        $("#getAllTable tbody tr").each(function() {
                            $(this).click(function() {
                                console.log("Clicked");
                                $("#updateModal .modal-body").html("<div style='text-align: center;'><i class='fa fa-beer fa-spin fa-5x text-center'></i></div>");
                                $("#updateModal").modal('toggle');
                                $.get("updateModal.php", {"ingredientId": $(this).attr("data-ingredientId")}, function(data) {
                                    $("#updateModal .modal-body div").fadeOut(400, function() {
                                        $("#updateModal .modal-body").hide().html(data).slideDown("slow");
                                    });
                                });
                            });
                        });
                        
                        $("#getAllTable").tablesorter();
                    });
                });
                
                //Set Create Modal links
                $(".callCreateModal").click(function(e) {
                    e.preventDefault();
                    $("#createModal .modal-body").html("<div style='text-align: center;'><i class='fa fa-beer fa-spin fa-5x text-center'></i></div>");
                    $("#createModal").modal('toggle');
                    $.get("createModal.php", function(data) {
                        $("#createModal .modal-body div").fadeOut(400, function() {
                            $("#createModal .modal-body").hide().html(data).slideDown("slow");
                        });
                    });
                });
                
                //Modal create button clicked
                $(".modalCreate").click(function() {
                    
                    var name = $("#createIngredientForm input#name").val();
                    
                    if(name === '') {
                        showError("Please enter an ingredient name");
                        
                    } else {
                        $.post("<?= getBaseUrl(); ?>api/ingredient/create.php", $("#createIngredientForm").serialize() , function(jsonData) {
                            
                            if(jsonData.success === false) {
                                console.log(jsonData);
                                showError(jsonData.error);
                            } else {
                                window.location = "../ingredient/showAll.php";
                            }
                        });
                    }
                });
                
                //Modal save button clicked
                $(".modalSave").click(function() {
                
                    var name = $("#updateIngredientForm input#name").val();
                    
                    if(name === '') {
                        showError("Please enter an ingredient name");
                        
                    } else {
                        $.post("<?= getBaseUrl(); ?>api/ingredient/update.php", $("#updateIngredientForm").serialize(), function(jsonData) {
                            
                            if(jsonData.success === false) {
                                console.log(jsonData);
                                showError(jsonData.error);
                            } else {
                                window.location = "../ingredient/showAll.php";
                            }
                        });
                    }
                });
                
                //Modal delete button clicked
                $(".modalDelete").click(function() {
                    if(confirm("Are you sure you want to delete this ingredient? This is not reversible!")) {
                        $.post("<?= getBaseUrl(); ?>api/ingredient/delete.php", {"id":$("#updateIngredientForm > #ingredientId").val()} , function(jsonData) {
                            
                            if(jsonData.success === false) {
                                console.log(jsonData);
                                showError(jsonData.error);
                            } else {
                                window.location = "../ingredient/showAll.php";
                            }
                        });
                    }
                });
                
                function showError(error) {
                    $("#errorMessage")
                            .html(error)
                            .slideDown("fast")
                            .delay(10000)
                            .slideUp(1000);
                }
            });
        </script>
    </head>
    <body>
        <?php require '../navbar.php'; ?>
        <div class="container">
            <div class="row">
                <a href="<?php echo getBaseUrl(); ?>ingredient/create.php" class="callCreateModal">Add a New Ingredient</a>
                <table id="getAllTable" class="table table-hover">
                    <thead>
                        <th>Name</th><th>Supplier</th><th>Quantity</th><th>Units</th><th>Low Value</th>
                    </thead>
                    <tbody>
                </table>
                <div id="showAllLoading" style="text-align: center;"><i class="fa fa-beer fa-spin fa-5x text-center"></i></div>
            </div>
        </div>
        
        <!--Modal Used for clicking on a row-->
        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalLabel">Update Ingredient Form</h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close Without Saving</button>
                        <button type="button" class="modalDelete btn btn-danger">Delete Ingredient</button> 
                        <button type="button" class="modalSave btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!--Modal Used for new ingredient-->
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalLabel">New Ingredient Form</h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close Without Saving</button>
                        <button type="button" class="modalCreate btn btn-primary">Create Ingredient</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
