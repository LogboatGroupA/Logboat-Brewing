<?php

require '../utilities/init.php';
require '../utilities/tools.php';

?>
<!DOCTYPE html>
<html>
<head>
  <?php require '../utilities/links.php'; ?>
  
  <script>
    $(document).ready(function() {
        //Get all of the rows
        $.getJSON("../api/analytics/getBrews.php", function(data) {
            $("#showAllLoading").fadeOut(400, function() {
                for(var i = 0, len = data.result.length; i < len; i++) {
                    $("#getAllTable").append("<tr data-brewId='" + data.result[i].brewId + "'><td>" +
                                             data.result[i].beerName +
                                             "</td><td>" +
                                             data.result[i].brewId +
                                             "</td><td>" + 
                                             data.result[i].brewStart +
                                             "</td><td>" +
                                             data.result[i].brewEnd +
                                             "</td><td>" +
                                             data.result[i].quantity +
                                             "</td></tr>");
                }
                $("#getAllTable tbody tr").click(function(e) {
                      e.preventDefault();
                      window.location = "showAnalytics.php?brewId=" + $(this).attr("data-brewId");
                });
                $("#getAllTable").tablesorter();
            });
            
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
                    <thead>
                        <th>Beer Name</th><th>Brew Id</th><th>Starts</th><th>Ends</th><th>Quantity</th>
                    </thead>
                    <tbody>
                </table>
                <div id="showAllLoading" style="text-align: center;"><i class="fa fa-beer fa-spin fa-5x text-center"></i></div>
            </div>
        </div>
  
  
  
  
  
  
  
  <!--<form id="selectBrewFermentation" method="post" action="<?php echo getBaseUrl(); ?>api/analytics/select.php">-->
  <!--<div class="form-group">-->
  <!--      <label for="brewId">Beer</label>-->
  <!--      <select name="brewId" class="form-control">-->
           <?php 
          //   $brews = Database::runQuery("SELECT brew.id AS brewId,beer.name AS beerName FROM brew LEFT JOIN beer ON brew.beerID = beer.id");
          //   foreach($brews as $brew) {
          //     echo "<option value='{$brew['brewId']}'>{$brew['beerName']} - {$brew[brewId]}</option>";
          //   }
          ?>
        <!--</select>-->
        <!--<select name="fermentationId" class="form-control">-->
          <?php
          //   $types = Database::runQuery("SELECT * from fermentationType");
          //   foreach($types as $type) {
          //     echo "<option value='{$type['id']}'>{$type['name']}</option>";
          //   }
          ?>
        <!--</select>-->
        <!--<button type="button" class="btn btn-primary">Submit</button>-->
  <!--</div>-->
  <!--</form>-->
</body>
</html>