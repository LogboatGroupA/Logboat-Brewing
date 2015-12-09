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
                $.getJSON("../api/ingredient/getLow.php", function(data) {
                    $("#showAllLoading").fadeOut(400, function() {
                        for(var i = 0, len = data.result.length; i < len; i++) {
                                $("#getAllTable").append(
                                    "<tr data-ingredientId='" + data.result[i].id + "'><td>" +
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
                        $("#getAllTable").tablesorter();
                    });
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
        <h2 class="text-center">Here are your Low Ingredients</h2>
        <div class="container">
            <div class="row">
                <table id="getAllTable" class="table table-hover">
                    <thead>
                        <th>Name</th><th>Supplier</th><th>Quantity</th><th>Units</th><th>Low Value</th>
                    </thead>
                    <tbody>
                </table>
                <div id="showAllLoading" style="text-align: center;"><i class="fa fa-beer fa-spin fa-5x text-center"></i></div>
            </div>
        </div>
    </body>
</html>
