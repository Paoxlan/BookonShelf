<?php
require 'requires/connection.php';
global $dbh;

$boeknaam = $_POST['naam'];

if (isset($_POST['verwijder_but'])) {
    $sql = "SELECT id FROM boeken WHERE naam = :naam";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':naam' => $boeknaam));

    $rsBoekId = $sth->fetch(PDO::FETCH_ASSOC);

    $sql = "DELETE FROM geleende_boeken WHERE boek_id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':id' => $rsBoekId['id']));

    $sql = "SELECT boek_id FROM boek_schrijvers WHERE boek_id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':id' => $rsBoekId['id']));

    if ($rsBoekenSchrijvers = $sth->fetchAll(PDO::FETCH_ASSOC)) {
        $sql = "DELETE FROM boek_schrijvers WHERE boek_id = :id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(':id' => $rsBoekId['id']));
    }

    $sql = "SELECT afbeelding FROM boeken WHERE naam = :naam";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':naam' => $boeknaam));

    if ($rsAfbeelding = $sth->fetch(PDO::FETCH_ASSOC)) {
        $target_file = '../' . $rsAfbeelding['afbeelding'];
        unlink($target_file);
    }

    $sql = "DELETE FROM boeken WHERE naam = :naam";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':naam' => $boeknaam));

    header('Location: ../?page=overzicht');
}