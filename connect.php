<?php
/**
 * Include this to connect. Change the dbname to match your database,
 * and make sure your login information is correct after you upload 
 * to csunix or your app will stop working.
 * 
 * Sam Scott, McMaster University, 2025
 */
// My CS1XD3 database details:
// dbname: bozkurte_db
// username: bozkurte_local
// password: HaPKFUwx
try {
    $dbh = new PDO(
        "mysql:host=localhost;dbname=bozkurte_db",
        "bozkurte_local",
        "HaPKFUwx"
    );
} catch (Exception $e) {
    die("ERROR: Couldn't connect. {$e->getMessage()}");
}

?>