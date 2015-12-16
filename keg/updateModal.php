<?php
require '../utilities/init.php';
require '../utilities/tools.php';

$conn = Database::getConn();

$data = Database::runQuery("SELECT keg.id as kegId, keg.serialNum as serialNum, kegOrder.id as kegOrderId, kegOrder.customerId as customerId, kegOrder.brewId as brewId, kegOrder.userId as userId FROM kegOrder LEFT OUTER JOIN keg ON keg.id = kegOrder.kegId WHERE kegOrder.id = :id", array("id" => $_GET['kegOrderId']), $conn);
$kegOrder = $data[0]; // Grab the first result (should only be one)
$customerData = Database::runQuery("SELECT id, firstName, lastName from customer WHERE id= :id", array("id" => $_GET['customerId']),$conn);
$theCustomer = $customerData[0];


?>
<form id="updateKegForm" method="post" action="<?= getBaseUrl(); ?>api/keg/update.php">
    <input type="hidden" name="kegOrderId" id="kegOrderId" value="<?= $kegOrder['kegOrderId']; ?>">
    <input type="hidden" name="kegId" id="kegId" value="<?= $kegOrder['kegId']; ?>">
    <div id="errorMessage" class="alert alert-danger text-center" role="alert" style="display: none;"></div>
    <div class="form-group">
        <label for="serialNum">Serial Number</label>
        <input type="text" class="form-control" id="serialNum" name="serialNum" maxlength="50" value="<?= $kegOrder['serialNum'] ?>" readonly>
    </div>
    <div class="form-group">
        <label for="brewId">Brew Name</label>
        <select name="brewId" class="form-control">
            <?php
                $brewNames = Database::runQuery(//"SELECT name from brew ORDER BY name
                // LEFT OUTER JOIN brew ON  
                // LEFT OUTER JOIN kegorder ON
                // LEFT OUTER JOIN keg ON", array(), $conn);
                "SELECT brew.id AS brewId,
                        beer.id AS beerId,
                        beer.name AS beerName
                FROM brew 
                LEFT OUTER JOIN beer ON brew.beerId = beer.id",
                array(),$conn);
                foreach($brewNames as $brewName){
                    if($brewName['brewId'] == $_GET['brewId']){
                        $selected = "selected";
                    }
                    else{
                        $selected = "";
                    }
                    echo "<option value='{$brewName['brewId']}' $selected>{$brewName['beerName']} - {$brewName['brewId']}</option>";
                }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="customerId">Customer Name</label>
        <select name="customerId" class="form-control">
            <?php
                $customers = Database::runQuery("Select * from customer",array(),$conn);
                foreach($customers as $customer){
                    if($theCustomer['id'] == $customer['id']){
                        $selected = "selected";
                    }
                    else{
                        $selected = "";
                    }
                    echo "<option value='{$customer['id']}' $selected> {$customer['firstName']}  {$customer['lastName']}</option>";
                }
            ?>
        </select>
    </div>
</form>
