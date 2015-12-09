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

        <div class="form-group">
          <label for="fermType">Beer</label>
            <select name="fermType" class="form-control">
                <?php 
                $types = Database::runQuery("SELECT * FROM fermentationType");
                foreach($types as $type) {
                    echo "<option value='{$type['id']}'>{$type['name']}</option>";
                }
                ?>
            </select>
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
          <h2 style="text-align: center;">pH</h2>
          <canvas id="chart1"></canvas>
        </div>
        <div class="col-md-6">
          <h2 style="text-align: center;">Gravity</h2>
          <canvas id="chart2"></canvas>
        </div>
        <script type="text/javascript"> 
          $.get("<?= getBaseUrl(); ?>api/analytics/chartData.php", {"brewId" : <?= (int) $_GET['brewId'] ?>}, function(jsonData) {
            
            if(jsonData.hasOwnProperty('success') && jsonData.success == true) {
              var ctx1 = document.getElementById("chart1").getContext("2d");
              var ctx2 = document.getElementById("chart2").getContext("2d");
              
              //Convert MySQL date strings to JS Date for PH
              var phData = jsonData.result.ph;
              for(var i = 0, len = phData.length; i < len; i++) {
                phData[i].x = new Date(phData[i].x);
              }
              
              //Convert MySQL date strings to JS Date for Gravity
              var gravityData = jsonData.result.gravity;
              for(var i = 0, len = gravityData.length; i < len; i++) {
                gravityData[i].x = new Date(gravityData[i].x);
              }
              
              console.dir(phData);
              
              //Chart Options (For Scale and String Formatting)
              phChartOptions = {
                scaleType: "date",
                scaleDateFormat: "mmmm d",
                scaleTimeFormat: "H:MM",
                scaleDateTimeFormat:"mmm d, yyyy, hh:MM",
                bezierCurve: false,
                responsive: true,
                maintainAspectRatio: true,
                
                scaleOverride: true,
                scaleSteps: 7,
                scaleStepWidth: 2,
                scaleStartValue: 0
              }
              
              gravityChartOptions = {
                scaleType: "date",
                scaleDateFormat: "mmmm d",
                scaleTimeFormat: "H:MM",
                scaleDateTimeFormat:"mmm d, yyyy, hh:MM",
                bezierCurve: false,
                responsive: true,
                maintainAspectRatio: true
              }
              
              var chartObj1 = new Chart(ctx1).Scatter([
                {
                  label: 'pH',
                  strokeColor: '#337AB7',
                  pointColor: '#285F8F',
                  pointStrokeColor: '#fff',
                  datasetFill: true,
                  data: phData
                }
              ], phChartOptions);
              
              var chartObj2 = new Chart(ctx2).Scatter([
                {
                  label: 'Gravity',
                  strokeColor: '#337AB7',
                  pointColor: '#285F8F',
                  pointStrokeColor: '#fff',
                  data: gravityData
                }
              ], gravityChartOptions);
            } else {
              document.getElementById("chartContainer").innerHTML = "<h1 style='text-align: center'>No Data To Graph</h1>";
            }
          });
          
            
          
        </script>
      </div>

    </div>
</body>
</html>