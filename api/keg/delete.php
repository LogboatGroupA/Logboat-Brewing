<?php

require '../init.php';
require '../tools.php';

if(!isLoggedIn()) {
    fail("Only logged in users can delete kegs");
}

$id = (int) $_POST['kegId'];

$query = 'DELETE FROM keg WHERE id = ? LIMIT 1';

$stmt = $link->prepare($query);

$stmt->bind_param("d", $id);

if($stmt->execute()) {
    success();
} else {
    fail($stmt->error);
}