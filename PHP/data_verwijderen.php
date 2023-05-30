<?php
require 'requires/connection.php';
global $dbh;

$id = $_POST['id'];
$datasoort = "";

session_start();

if (isset($_POST['genresButton'])) {
    $datasoort = 'genres';
    $sql = "SELECT id FROM boeken WHERE genre_id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array('id' => $id));

    if (!($rsBoek = $sth->fetch())) {
        $sql = "DELETE FROM genres WHERE id = :id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(':id' => $id));
    } else {
        $_SESSION['data_warning'] = 'Een boek heeft dit gekozen genre nog.';
    }
}
if (isset($_POST['schrijversButton'])) {
    $datasoort = 'schrijvers';
    $sql = "SELECT id FROM boeken WHERE schrijver_id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array('id' => $id));

    if ($rsBoek = $sth->fetch()) {
        $_SESSION['data_warning'] = 'Een boek heeft dit gekozen schrijver nog.';
        header("Location: ../?page=data_beheren&keuze=$datasoort");
        exit;
    }

    $sql = "SELECT id FROM boek_schrijvers WHERE schrijver_id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array('id' => $id));

    if ($rsSchrijvers = $sth->fetch()) {
        $_SESSION['data_warning'] = 'Een boek heeft dit gekozen schrijver nog.';
        header("Location: ../?page=data_beheren&keuze=$datasoort");
        exit;
    }

    $sql = "DELETE FROM schrijvers WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array('id' => $id));
}
if (isset($_POST['talenButton'])) {
    $datasoort = 'talen';

    $sql = "SELECT id FROM boeken WHERE taal_id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array('id' => $id));

    if (!($rsBoek = $sth->fetch())) {
        $sql = "DELETE FROM talen WHERE id = :id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(':id' => $id));
    } else {
        $_SESSION['data_warning'] = 'Een boek heeft dit gekozen taal nog.';
    }
}
if (isset($_POST['gebruikersButton'])) {
    $datasoort = 'gebruikers';

    $sql = "SELECT email FROM gebruikers WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':id' => $id));

    $rsEmail = $sth->fetch();

    if (!($rsEmail['email'] === $_SESSION['email'])) {

        $sql = "SELECT id FROM geleende_boeken WHERE gebruiker_id = :gebruiker_id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array('gebruiker_id' => $id));

        if ($sth->fetch()) {
            $sql = "DELETE FROM geleende_boeken WHERE gebruiker_id = :gebruiker_id";
            $sth = $dbh->prepare($sql);
            $sth->execute(array('gebruiker_id' => $id));
        }

        $sql = "SELECT id FROM gereserveerde_boeken WHERE gebruiker_id = :gebruiker_id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array('gebruiker_id' => $id));

        if ($sth->fetch()) {
            $sql = "DELETE FROM gereserveerde_boeken WHERE gebruiker_id = :gebruiker_id";
            $sth = $dbh->prepare($sql);
            $sth->execute(array('gebruiker_id' => $id));
        }

        $sql = "DELETE FROM gebruikers WHERE id = :id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array('id' => $id));
    } else {
        $_SESSION['data_warning'] = 'U kunt niet zichzelf verwijderen.';
    }
}

header("Location: ../?page=data_beheren&keuze=$datasoort");