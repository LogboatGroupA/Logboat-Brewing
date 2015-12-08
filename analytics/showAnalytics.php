<?php
require '../utilities/init.php';
require '../utilities/tools.php';
if(!isLoggedIn()) {
    header("Location: " . getBaseUrl() . "user/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Logboat Brewing</title>
  <?php require '../utilities/links.php'; ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
  <script src="../utilities/Chart.Scatter.min.js"></script>
  <style>
    canvas{
        width: 100% !important;
        height: auto !important;
    }
  </style>

</head>
<body>
  <?php require '../navbar.php'; ?>
    <div class="container">
      <form id="addBrewFermentation" method="post" action="<?php echo getBaseUrl(); ?>api/anayltics/addAnayltics.php"> <!-- need to send the selected brews info to addAnayltics.php for proper insertion along with this other data-->
        <div class="form-group">
          <label for="value">Value</label>
          <input type"text" class="form-control" id="value" name="value" required>
        </div>
        <!-- Need to add time in the picker or elsewhere because datetime is required -->
        <div class="form-group">
          <label for="fermentationDate">Fermentation Collection Date</label>
          <div class="input-group date datepicker" id="startDatepicker">
              <input type="text" class="form-control" name="fermentationDate">
              <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
              </span>
          </div>
        </div>
        <button type="button" class="btn btn-primary">Submit</button>
      </form>

      <div id="chartContainer">
        <div class="col-md-6">
          <canvas id="chart1"></canvas>
        </div>
        <div class="col-md-6">
          <canvas id="chart2"></canvas>
        </div>
        <script type="text/javascript"> 
          $.get("<?= getBaseUrl(); ?>api/analytics/chartData.php", {"brewId" : <?= (int) $_GET['brewId'] ?>}, function(jsonData) {
            
            var ctx1 = document.getElementById("chart1").getContext("2d");
            var ctx2 = document.getElementById("chart2").getContext("2d");
            var data1 = jsonData.ph;
            var data2 = jsonData.gravity;
            var chartObj1 = new Chart(ctx1).Scatter(data1);
            var chartObj2 = new Chart(ctx2).Scatter(data2);
          });
        </script>
      </div>

    </div>
</body>
</html>