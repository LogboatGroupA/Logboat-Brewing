
<?php
require '../init.php';
require '../tools.php';
$query = 
    'SELECT
        ing.id, 
        ing.name, 
        ing.supplier, 
        ing.quantity as quantity,
        ing.unitId, 
        unit.name as unitName,
        ing.lowValue as low
    FROM ingredient AS ing
        LEFT OUTER JOIN unit ON ing.unitId = unit.id
    WHERE (quantity<=low) 
    ORDER BY ing.name';
if(($result = $link->query($query))) {
    $ingredients = array();
    
    while($row = $result->fetch_assoc()) {
        array_push($ingredients, $row);
    }
    
    success($ingredients);
}
fail("Error getting ingredients");
