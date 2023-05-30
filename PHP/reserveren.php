<?php
require 'requires/connection.php';
global $dbh;
session_start();

$sql = "SELECT t1.id gebruiker_id, t2.id boek_id FROM gebruikers t1 INNER JOIN boeken t2 ON t2.id WHERE t1.email = :email AND t2.naam = :boeknaam";
$sth = $dbh->prepare($sql);
$sth->execute(array(
    ':email' => $_SESSION['email'],
    ':boeknaam' => $_POST['boek']
));

$rsGebruikerBoek = $sth->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM gereserveerde_boeken WHERE gebruiker_id = :gebruiker_id";
$sth = $dbh->prepare($sql);
$sth->execute(array(':gebruiker_id' => $rsGebruikerBoek['gebruiker_id']));

$count = $sth->rowCount();

$sql = "INSERT INTO gereserveerde_boeken (gebruiker_id, boek_id) VALUES (:gebruiker_id, :boek_id)";
$sth = $dbh->prepare($sql);
$sth->execute(array(
    ':gebruiker_id' => $rsGebruikerBoek['gebruiker_id'],
    ':boek_id' => $rsGebruikerBoek['boek_id']
));

header('Location: ../?page=overzicht');