<?php
require '../utilities/init.php';
require '../utilities/tools.php';

$conn = Database::getConn();

$data = Database::runQuery("SELECT * FROM keg WHERE id = :id", array("id" => $_GET['kegId']), $conn);
$keg = $data[0]; // Grab the first result (should only be one)
?>
<form id="updateKegForm" method="post" action="<?= getBaseUrl(); ?>api/keg/update.php">
    <input type="hidden" name="kegId" id="kegId" value="<?= $keg['id']; ?>">
    <div id="errorMessage" class="alert alert-danger text-center" role="alert" style="display: none;"></div>
    <div class="form-group">
        <label for="serialNum">Serial Number</label>
        <input type="text" class="form-control" id="serialNum" name="serialNum" maxlength="50" value="<?= $keg['serialNum'] ?>" readonly>
    </div>
    <div class="form-group">
        <label for="brewName">Brew Name</label>
        <select name="brewName" class="form-control">
            <?php
                $beerNames = Database::runQuery("SELECT name from beer ORDER BY name", array(), $conn);
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="customerName">Customer Name</label>
        <select name="customerName" class="form-control">
            <?php
                $customerNames = Database::runQuery("Select firstName, lastName from customer
                JOIN kegorder ON customer.Id 
                JOIN keg ON keg.Id
                WHERE keg.id = kegorder.id AND kegorder.id = customer.id", array(), $conn);
                foreach($customerNames as $customerName){
                    if($customerName['customerId'] == $customerName['customer.id']){
                        $selected = "selected";
                    }
                    else{
                        $selected = "";
                    }
                echo "<option value='{$customerName['customerId']}' $selected>{$customerName['firstName'] . $customerName['lastName'] }</option>";
                }
            ?>
        </select>
    </div>
</form>
