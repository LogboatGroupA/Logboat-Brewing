<?php
require '../utilities/init.php';
require '../utilities/tools.php';

if (!isLoggedIn()) {
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
            $(document).ready(function () {
                $.getJSON("<?= getBaseUrl(); ?>api/brew/getBrewsForUser.php",
                        {"userId": <?= $user[id]; ?>}, function (data) {
                    if (!data.success) {
                        console.error(data.error);
                        //TODO: handle error in UI
                        return;
                    }

                    var brewList = $("#brewList");
                    brewList.empty();

                    data.result.forEach(function (brew) {
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
                        {"userId": <?= $user[id]; ?>}, function (data) {
                    if (!data.success) {
                        console.error(data.error);
                        //TODO: handle error in UI
                        return;
                    }

                    var beerList = $("#beerList");
                    beerList.empty();

                    data.result.forEach(function (beer) {
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
                        {"userId": <?= $user[id]; ?>}, function (data) {
                    if (!data.success) {
                        console.error(data.error);
                        //TODO: handle error in UI
                        return;
                    }

                    var kegOrderList = $("#kegOrderList");
                    kegOrderList.empty();

                    data.result.forEach(function (KegOrder) {
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
            <h1 class="text-center">
                <?php echo $user['username'];
                    if ($user['isAdmin']) {
                        echo ' (admin)';
                    }
                ?>
            </h1>

            <div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#brews" aria-controls="brews" role="tab" data-toggle="tab">Brews</a></li>
                    <li role="presentation"><a href="#beers" aria-controls="beers" role="tab" data-toggle="tab">Beer Recipes</a></li>
                    <li role="presentation"><a href="#kegOrders" aria-controls="kegOrders" role="tab" data-toggle="tab">Keg Orders</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="brews">
                        <h2>Brews Scheduled</h2>
                        <div id="brewList"></div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="beers">
                        <h2>Beer Recipes Created</h2>
                        <div id="beerList"></div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="kegOrders">
                        <h2>Keg Orders Scheduled</h2>
                        <div id="kegOrderList"></div></div>
                </div>
            </div>
        </div>
    </body>
</html>