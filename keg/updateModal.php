<?php
require '../utilities/init.php';
require '../utilities/tools.php';

$conn = Database::getConn();

$data = Database::runQuery("SELECT * FROM keg WHERE id = :id", array("id" => $_GET['kegId']), $conn);
$keg = $data[0]; // Grab the first result (should only be one)

$customerData = Database::runQuery("SELECT id, firstName, lastName from customer WHERE id= :id", array("id" => $_GET['customerId']),$conn);
$theCustomer = $customerData[0];

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
                $beerNames = Database::runQuery(//"SELECT name from beer ORDER BY name
                // LEFT OUTER JOIN brew ON  
                // LEFT OUTER JOIN kegorder ON
                // LEFT OUTER JOIN keg ON", array(), $conn);
                "SELECT * from beer",array(),$conn);
                foreach($beerNames as $beerName){
                    echo "<option value='{$beerName['beer.id']}'> {$beerName['name']}</option>";
                }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="customerName">Customer Name</label>
        <select name="customerName" class="form-control">
            <?php
                $customers = Database::runQuery(//"Select firstName, lastName from customer
                //LEFT OUTER JOIN kegorder ON customer.Id 
                //LEFT OUTER JOIN keg ON keg.Id
                //WHERE keg.id = kegorder.id AND kegorder.id = customer.id", array(), $conn);
                "Select * from customer",array(),$conn);
                // foreach($customerNames as $customerName){
                    // if($customerName['customerId'] == $customerName['customer.id']){
                    //     $selected = "selected";
                    // }
                    // else{
                    //     $selected = "";
                    // }
                // echo "<option value='{$customerName['customerId']}' $selected>{$customerName['firstName']}  {$customerName['lastName']}</option>";
                // echo "<option value=''";
                foreach($customers as $customer){
                    if($theCustomer['id'] == $customer['id']){
                        $selected = "selected";
                    }
                    else{
                        $selected = "";
                    }
                    echo "<option value='{$customer['customerId']}' $selected> {$customer['firstName']}  {$customer['lastName']}</option>";
                }
            ?>
        </select>
    </div>
</form>
