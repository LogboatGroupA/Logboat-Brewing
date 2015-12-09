<?php
require '../utilities/init.php';
require '../utilities/tools.php';

$data = Database::runQuery("SELECT * FROM keg WHERE id = :id", array("id" => $_GET['kegId']));
$keg = $data[0]; // Grab the first result (should only be one)
?>
<form id="updateKegForm" method="post" action="<?= getBaseUrl(); ?>api/keg/update.php">
    <input type="hidden" name="kegId" id="kegId" value="<?= $keg['id']; ?>">
    <div id="errorMessage" class="alert alert-danger text-center" role="alert" style="display: none;"></div>
    <div class="form-group">
        <label for="serialNum">Serial Number</label>
        <input type="text" class="form-control" id="serialNum" name="serialNum" maxlength="50" value="<?= $keg['serialNum'] ?>" readonly>
    </div>
    <div>
        <label for="brewName">Brew Name</label>
        <--! change to drop down --><input type="text" class="form-control" id="brewName" name="brewName" value="<?= $keg['brewName'] ?>">
    </div>
    <div>
        <label for="customerName">Customer Name</label>
        <input type="text" class="form-control" id="customerName" name="customerName" maxlength="50" value="<?= $keg['customerFirstName'] + "" + $keg['customerLastName']?>">
    </div>
</form>
