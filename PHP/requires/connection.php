<?php
$userdb = 'root';
$passdb = '';

try {
    $dbh = new PDO('mysql:host=localhost;dbname=bookonshelf', $userdb, $passdb);
} catch (PDOException $e) {
    print "Error!: ". $e->getMessage() ."<br/>";
    die();
}
