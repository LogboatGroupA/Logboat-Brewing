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
                $.getJSON("../api/beer/getAll.php", function(data) {
                    
                    $("#showAllLoading").fadeOut("fast", function() {
                        
                        data.result.forEach(function(beer) {
                            $("#getAllTable").append("<tr data-beerId='" + beer.id + "'><td>" +
                                                     beer.name +
                                                     "</td><td>" +
                                                     beer.beerType +
                                                     "</td><td>" + 
                                                     beer.username +
                                                     "</td></tr>");
                        });
                        
                        //Set all the rows to open modal
                        $("#getAllTable tbody tr").each(function() {
                            $(this).click(function() {
                                $("#updateModal .modal-body").html("<div style='text-align: center;'><i class='fa fa-beer fa-spin fa-5x text-center'></i></div>");
                                $("#updateModal").modal('toggle');
                                $.get("updateModal.php", {"beerId": $(this).attr("data-beerId")}, function(data) {
                                    $("#updateModal .modal-body div").fadeOut("fast", function() {
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
                        $("#createModal .modal-body div").fadeOut("fast", function() {
                            $("#createModal .modal-body").hide().html(data).slideDown("slow");
                        });
                    });
                });
                
                //Modal create button clicked
                $(".modalCreate").click(function() {
                    
                    var name = $("#createBeerRecipeForm input#name").val();
                    
                    if(name === '') {
                        showError("Please enter a name for the recipe");
                        
                    } else {
                        $.post("<?= getBaseUrl(); ?>api/beer/create.php", $("#createBeerRecipeForm").serialize() , function(jsonData) {
                            
                            if(jsonData.success === false) {
                                console.log(jsonData);
                                showError(jsonData.error);
                            } else {
                                window.location = "../recipe/showAll.php";
                            }
                        });
                    }
                });
                
                //Modal update button clicked
                $(".modalSave").click(function() {
                
                    var name = $("#updateBeerRecipeForm input#name").val();
                    
                    if(name === '') {
                        showError("Please enter a name for the recipe");
                    
                    } else {
                        $.post("<?= getBaseUrl(); ?>api/beer/update.php", $("#updateBeerRecipeForm").serialize(), function(jsonData) {
                            
                            if(jsonData.success === false) {
                                console.log(jsonData);
                                showError(jsonData.error);
                            } else {
                                window.location = "../recipe/showAll.php";
                            }
                        });
                    }
                });
                
                //Modal delete button clicked
                $(".modalDelete").click(function() {
                    if(confirm("Are you sure you want to delete this recipe? This is not reversable!")) {
                        $.post("<?= getBaseUrl(); ?>api/beer/delete.php", {"beerId":$("#updateBeerRecipeForm > #beerId").val()} , function(jsonData) {
                            
                            if(jsonData.success === false) {
                                console.log(jsonData);
                                showError(jsonData.error);
                            } else {
                                window.location = "../recipe/showAll.php";
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
                <a href="<?php echo getBaseUrl(); ?>recipe/create.php" class="callCreateModal">Add a New Beer Recipe</a>
                <table id="getAllTable" class="table table-hover">
                    <thead>
                        <th>Beer Name</th><th>Type</th><th>Created By</th>
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
                        <h4 class="modal-title" id="modalLabel">Update Beer Recipe Form</h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close Without Saving</button>
                        <button type="button" class="modalDelete btn btn-danger">Delete Recipe</button> 
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
                        <h4 class="modal-title" id="modalLabel">New Beer Recipe Form</h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close Without Saving</button>
                        <button type="button" class="modalCreate btn btn-primary">Create Recipe</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>