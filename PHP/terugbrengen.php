<?php
require 'requires/connection.php';
require 'requires/update_overzicht.php';
global $dbh;
session_start();

if (isset($_POST['boek'])) {
    $sql = "SELECT b.id boek_id, g.id gebruiker_id FROM boeken b INNER JOIN gebruikers g ON g.email = :email WHERE b.naam = :boeknaam";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':email' => $_SESSION['email'],
        ':boeknaam' => $_POST['boek']
    ));

    $rsBoekEmail = $sth->fetch();

    $sql = "DELETE FROM geleende_boeken WHERE boek_id = :boek_id AND gebruiker_id = :gebruiker_id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':boek_id' => $rsBoekEmail['boek_id'],
        ':gebruiker_id' => $rsBoekEmail['gebruiker_id']
    ));

    $sql = "UPDATE boeken SET aantal_exemplaren = aantal_exemplaren + 1 WHERE id = :boek_id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':boek_id' => $rsBoekEmail['boek_id']));

    updateOverzicht($_POST['boek']);
}

header('Location: ../?page=geleende_boeken');