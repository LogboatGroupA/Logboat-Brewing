<?php
require '../utilities/init.php';
require '../utilities/tools.php';

print_r(isLoggedIn());

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
                $.get("../api/ingredient/getAll.php", function(data) {
                    $("#showAllLoading").fadeOut("slow", function() {
                        for(var i = 0, len = data.result.length; i < len; i++) {
                            $("#getAllTable").append("<tr data-ingredientId='" + data.result[i].id + "'><td>" +
                                                     data.result[i].name +
                                                     "</td><td>" +
                                                     data.result[i].supplier +
                                                     "</td><td>" + 
                                                     data.result[i].quantity +
                                                     "</td><td>" +
                                                     data.result[i].unitName +
                                                     "</td></tr>");
                        }
                        // Set all the rows to open modal
                        $("#getAllTable tr").each(function() {
                            $(this).click(function() {
                                console.log("Clicked");
                                $("#updateModal .modal-body").html("<div style='text-align: center;'><i class='fa fa-circle-o-notch fa-spin fa-5x text-center'></i></div>");
                                $("#updateModal").modal('toggle');
                                $.get("updateModal.php", {"ingredientId": $(this).attr("data-ingredientId")}, function(data) {
                                    $("#updateModal .modal-body div").fadeOut("slow", function() {
                                        $("#updateModal .modal-body").hide().html(data).slideDown("slow");
                                    });
                                });
                            });
                        });
                    });
                    
                });
                
                //Set Create Modal links
                $(".callCreateModal").click(function(e) {
                    e.preventDefault();
                    $("#createModal .modal-body").html("<div style='text-align: center;'><i class='fa fa-circle-o-notch fa-spin fa-5x text-center'></i></div>");
                    $("#createModal").modal('toggle');
                    $.get("createModal.php", function(data) {
                        $("#createModal .modal-body div").fadeOut("slow", function() {
                            $("#createModal .modal-body").hide().html(data).slideDown("slow");
                        });
                    });
                });
                
                // Set modal buttons
                $(".modalSave").click(function() {
                    if($("#updateIngredientForm input#name").val() == '') {
                        $("#errorMessage")
                            .html("Please Enter an Ingredient Name")
                            .slideDown("fast")
                            .delay(10000)
                            .slideUp(1000);
                    } else if ($("#updateIngredientForm input#name").val().length > 100) {
                        $("#errorMessage")
                            .html("Ingredient Name can be no longer than 100 characters")
                            .slideDown("fast")
                            .delay(10000)
                            .slideUp(1000);
                    } else {
                        $.post("../api/ingredient/update.php", $("#updateIngredientForm").serialize(), function(jsonData) {
                            if(jsonData.success === false) {
                                $("#errorMessage")
                                    .html(jsonData.error)
                                    .slideDown("fast")
                                    .delay(10000)
                                    .slideUp(1000);
                            } else {
                                window.location = "../ingredient/showAll.php";
                            }
                        });
                    }
                });
                
                $(".modalDelete").click(function() {
                    if(confirm("Are you sure you want to delete this ingredient? This is not reversable!")) {
                        $.post("../api/ingredient/delete.php", {"id":$("#updateIngredientForm > #ingredientId").val()} , function(jsonData) {
                            if(jsonData.success === false) {
                                $("#errorMessage")
                                    .html(jsonData.error)
                                    .slideDown("fast")
                                    .delay(10000)
                                    .slideUp(1000);
                            } else {
                                window.location = "../ingredient/showAll.php";
                            }
                        });
                    }
                });
                
                $(".modalCreate").click(function() {
                    if($("#createIngredientForm input#name").val() == '') {
                        $("#errorMessage")
                            .html("Please Enter an Ingredient Name")
                            .slideDown("fast")
                            .delay(10000)
                            .slideUp(1000);
                    } else if ($("#createIngredientForm input#name").val().length > 100) {
                        $("#errorMessage")
                            .html("Ingredient Name can be no longer than 100 characters")
                            .slideDown("fast")
                            .delay(10000)
                            .slideUp(1000);
                    } else {
                        $.post("../api/ingredient/create.php", $("#createIngredientForm").serialize() , function(jsonData) {
                            if(jsonData.success === false) {
                                $("#errorMessage")
                                    .html(jsonData.error)
                                    .slideDown("fast")
                                    .delay(10000)
                                    .slideUp(1000);
                            } else {
                                window.location = "../ingredient/showAll.php";
                            }
                        });
                    }
                });
            });
        </script>
    </head>
    <body>
        <?php require '../navbar.php'; ?>
        <div class="container">
            <div class="row">
                <a href="<?php echo getBaseUrl(); ?>ingredient/create.php" class="callCreateModal">Add a New Ingredient</a>
                <table id="getAllTable" class="table table-hover">
                <?php
                
                echo "<th>Name</th><th>Supplier</th><th>Quantity</th><th>Units</th>";
                
                
                ?>
                </table>
                <div id="showAllLoading" style="text-align: center;"><i class="fa fa-circle-o-notch fa-spin fa-5x text-center"></i></div>
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