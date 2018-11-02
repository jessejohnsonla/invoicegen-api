<?php


function get_db_connection() {
    return new mysqli("localhost:3306", "root", "!mysql!", "invoicegen"); 
}

function query_close($query) {
    $db = get_db_connection();
    $result = $db->query($query);
    $db->close();
    return $result;
}
?>