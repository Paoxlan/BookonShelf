<?php
session_start();

require 'requires/connection.php';
global $dbh;

if (isset($_POST['afbetalenButton'])) {
    $gebruikerBoek = array(
        'email' => $_SESSION['email'],
        'boek' => $_POST['boek']
    );

    $sql = "SELECT boek_id, gebruiker_id FROM boek_boetes INNER JOIN boeken b ON boek_boetes.boek_id = b.id INNER JOIN gebruikers g ON boek_boetes.gebruiker_id = g.id WHERE naam = :naam AND email = :email";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':naam' => $gebruikerBoek['boek'],
        ':email' => $gebruikerBoek['email']
    ));

    $boeteRow = $sth->fetch();

    $sql = "UPDATE boek_boetes SET afbetaald = 1 WHERE boek_id = :boek_id AND gebruiker_id = :gebruiker_id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':boek_id' => $boeteRow['boek_id'],
        ':gebruiker_id' => $boeteRow['gebruiker_id']
    ));
    header('Location: ../?page=openstaande_boetes');
}

if (isset($_POST['boeteNAfButton'])) {
    $sql = "UPDATE boek_boetes SET afbetaald = 0 WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':id' => $_POST['id']));

    header('Location: ../?page=data_beheren&keuze=boetes');
}

if (isset($_POST['boeteSucButton'])) {
    $sql = "SELECT gebruiker_id FROM boek_boetes WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array('id' => $_POST['id']));

    $boeteRow = $sth->fetch();

    $sql = "DELETE FROM boek_boetes WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':id' => $_POST['id']));

    $sql = "UPDATE gebruikers SET aantal_boetes = aantal_boetes - 1 WHERE id = :gebruiker_id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':gebruiker_id' => $boeteRow['gebruiker_id']));

    header('Location: ../?page=data_beheren&keuze=boetes');
}