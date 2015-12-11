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
                $.getJSON("../api/keg/getAll.php", function(data) {
                    $("#showAllLoading").fadeOut(400, function() {
                        for(var i = 0, len = data.result.length; i < len; i++) {
                            $("#getAllTable").append("<tr data-beerId = '"+ data.result[i].beerId + "'  data-kegId='" + data.result[i].kegId + "' data-customerId='" + data.result[i].customerId +"'><td>" +
                                                     data.result[i].serialNum +
                                                     "</td><td>" +
                                                     (data.result[i].beerName != null
                                                        ? data.result[i].beerName  
                                                        : "Empty") +
                                                     "</td><td>" +
                                                     (data.result[i].customerFirstName != null && data.result[i].customerLastName != null 
                                                        ? data.result[i].customerFirstName + " " + data.result[i].customerLastName
                                                        : "Not Rented Out") +
                                                     "</td></tr>");
                        }
                        // Set all the rows to open modal
                        $("#getAllTable tbody tr").each(function() {
                            $(this).click(function() {
                                console.log("Clicked");
                                $("#updateModal .modal-body").html("<div style='text-align: center;'><i class='fa fa-beer fa-spin fa-5x text-center'></i></div>");
                                $("#updateModal").modal('toggle');
                                $.get("updateModal.php", {"kegId": $(this).attr("data-kegId"), "customerId" : $(this).attr("data-customerId"), "beerId": $(this).attr("data-beerId")}, function(data) {
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
                    
                    var serialNum = $("#createKegForm input#serialNum").val();
                    
                    if(serialNum === '') {
                        showError("Please enter a keg serial number");
                        
                    } else {
                        $.post("<?= getBaseUrl(); ?>api/keg/create.php", $("#createKegForm").serialize() , function(jsonData) {
                            
                            if(jsonData.success === false) {
                                console.log(jsonData);
                                showError(jsonData.error);
                            } else {
                                window.location = "../keg/showAll.php";
                            }
                        });
                    }
                });
                
                //Modal update button clicked
                $(".modalSave").click(function() {
                
                    var serialNum = $("#updateKegForm input#serialNum").val();
                    
                    if(serialNum === '') {
                        showError("Please enter a keg serial number");
                        
                    } else {
                        $.post("<?= getBaseUrl(); ?>api/keg/update.php", $("#updateKegForm").serialize(), function(jsonData) {
                            
                        if(jsonData.success === false) {
                                console.log(jsonData);
                                showError(jsonData.error);
                            } else {
                                window.location = "../keg/showAll.php";
                            }
                        });
                    }
                });
                
                //Modal delete button clicked
                $(".modalDelete").click(function() {
                    if(confirm("Are you sure you want to delete this keg? This is not reversible!")) {
                        $.post("<?= getBaseUrl(); ?>api/keg/delete.php", {"kegId":$("#updateKegForm > #kegId").val()} , function(jsonData) {
                            
                            if(jsonData.success === false) {
                                console.log(jsonData);
                                showError(jsonData.error);
                            } else {
                                window.location = "../keg/showAll.php";
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
                <a href="<?php echo getBaseUrl(); ?>ingredient/create.php" class="callCreateModal">Add a New Keg</a><br>
                <table id="getAllTable" class="table table-hover">
                    <thead>
                        <th>Keg Serial Num</th><th>Beer</th><th>Rented To</th>
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
                        <h4 class="modal-title" id="modalLabel">Update Keg Form</h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close Without Saving</button>
                        <button type="button" class="modalDelete btn btn-danger">Delete Keg</button> 
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
                        <h4 class="modal-title" id="modalLabel">New Keg Form</h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close Without Saving</button>
                        <button type="button" class="modalCreate btn btn-primary">Add Keg</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
