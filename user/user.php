<?php
require '../utilities/init.php';
require '../utilities/tools.php';

if(!isLoggedIn()) {
    header("Location: " . getBaseUrl() . "user/login.php");
    exit();
}

$data = Database::runQuery("SELECT * FROM userSafe WHERE id = :id", array("id" => $_GET['id']));
$user = $data[0]; // Grab the first result (should only be one)
?>
<html>
    <head>
        <title>Logboat Brewing</title>
        <?php require '../utilities/links.php'; ?>
        
        <script>
            $(document).ready(function() {
                $.getJSON("<?= getBaseUrl(); ?>api/brew/getBrewsForUser.php", 
                {"userId": <?= $user[id]; ?>}, function(data) {
                    if(!data.success) {
                        console.error(data.error);
                        //TODO: handle error in UI
                        return;
                    }
                    
                    var brewList = $("#brewList");
                    brewList.empty();
                    
                    data.result.forEach(function(brew) {
                        var brewCell = $("<div></div>");
                        brewCell.addClass("cell-basic clearfix");
                        
                        /**
                         * Beer name
                         */
                        var name = $("<h3>" + brew.beerName + "</h3>");
                        name.addClass("inline no-spacing");
                        
                        brewCell.append(name);
                        
                        brewList.append(brewCell);
                    });
                });
                
                $.getJSON("<?= getBaseUrl(); ?>api/beer/getBeersForUser.php", 
                {"userId": <?= $user[id]; ?>}, function(data) {
                    if(!data.success) {
                        console.error(data.error);
                        //TODO: handle error in UI
                        return;
                    }
                    
                    var beerList = $("#beerList");
                    beerList.empty();
                    
                    data.result.forEach(function(beer) {
                        var brewCell = $("<div></div>");
                        brewCell.addClass("cell-basic clearfix");
                        
                        /**
                         * Beer name
                         */
                        var name = $("<h3>" + beer.name + "</h3>");
                        name.addClass("inline no-spacing");
                        
                        brewCell.append(name);
                        
                        beerList.append(brewCell);
                    });
                });
                
                $.getJSON("<?= getBaseUrl(); ?>api/keg/getKegOrdersForUser.php", 
                {"userId": <?= $user[id]; ?>}, function(data) {
                    if(!data.success) {
                        console.error(data.error);
                        //TODO: handle error in UI
                        return;
                    }
                    
                    var kegOrderList = $("#kegOrderList");
                    kegOrderList.empty();
                    
                    data.result.forEach(function(KegOrder) {
                        var kegOrderCell = $("<div></div>");
                        kegOrderCell.addClass("cell-basic clearfix");
                        
                        /**
                         * Beer name
                         */
                        var name = $("<h3>" + KegOrder.serialNum + "</h3>");
                        name.addClass("inline no-spacing");
                        
                        kegOrderCell.append(name);
                        
                        kegOrderList.append(kegOrderCell);
                    });
                });
            });
        </script>
    </head>
    <body>
        <?php require '../navbar.php'; ?>
        <div class="container">
            <h1 class="text-center"><?php echo $user['username']; if($user['isAdmin']) { echo ' (admin)'; }?></h1>
            
            <h2>Brews Scheduled</h2>
            <div id="brewList"></div>
            
            <h2>Beer Recipes Created</h2>
            <div id="beerList"></div>
            
            <h2>Keg Orders Scheduled</h2>
            <div id="kegOrderList"></div>
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