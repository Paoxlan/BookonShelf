<?php
require 'requires/connection.php';
global $dbh;

session_start();

if (!($_SESSION['totaalGeleendBoeken'] == 3)) {
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

    if ($_SESSION['totaalGeleendBoeken'] + $count < 3) {
        $begindatum = date("y-m-d");

        $sql = "INSERT INTO geleende_boeken (gebruiker_id, boek_id, begindatum) VALUES (:gebruiker_id, :boek_id, :begindatum)";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':gebruiker_id' => $rsGebruikerBoek['gebruiker_id'],
            ':boek_id' => $rsGebruikerBoek['boek_id'],
            ':begindatum' => $begindatum
        ));

        $sql = "UPDATE boeken SET aantal_exemplaren = aantal_exemplaren - 1 WHERE naam = :boeknaam";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(':boeknaam' => $_POST['boek']));
    }
}

header('Location: ../?page=overzicht');