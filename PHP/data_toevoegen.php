<?php
require 'requires/connection.php';
global $dbh;

$registered = 0;

session_start();

if (isset($_POST['genreButton'])) {
    if (isset($_POST['registerd'])) {
        $registered = 1;
    }

    $sql = "SELECT genre FROM genres WHERE genre = :genre";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':genre' => $_POST['genre']));

    if ($sth->fetch() !== $_POST['genre']) {
        $sql = "INSERT INTO genres (genre, registreerd) VALUES (:genre, :registreerd)";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':genre' => $_POST['genre'],
            ':registreerd' => $registered
        ));
    } else {
        $_SESSION['data_warning'] = 'Deze genre bestaat al.';
    }

    header('Location: ../?page=data_beheren&keuze=genres');
}
if (isset($_POST['schrijverButton'])) {
    if (isset($_POST['registerd'])) {
        $registered = 1;
    }

    if (empty($_POST['tussenvoegsel'])) {
        $sql = "SELECT voornaam, achternaam FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel IS NULL AND achternaam = :achternaam";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':voornaam' => $_POST['voornaam'],
            ':achternaam' => $_POST['achternaam']
        ));

        if (!($rsSchrijver = $sth->fetch())) {
            $sql = "INSERT INTO schrijvers (voornaam, achternaam, registreerd) VALUES (:voornaam, :achternaam, :registreerd)";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(
                ':voornaam' => $_POST['voornaam'],
                ':achternaam' => $_POST['achternaam'],
                ':registreerd' => $registered
            ));
        } else {
            $_SESSION['data_warning'] = 'Deze schrijver bestaat al.';
        }
    } else {
        $sql = "SELECT voornaam, achternaam FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel = :tussenvoegsel AND achternaam = :achternaam";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':voornaam' => $_POST['voornaam'],
            ':tussenvoegsel' => $_POST['tussenvoegsel'],
            ':achternaam' => $_POST['achternaam']
        ));

        if (!($rsSchrijver = $sth->fetch())) {
            $sql = "INSERT INTO schrijvers (voornaam, tussenvoegsel, achternaam, registreerd) VALUES (:voornaam, :tussenvoegsel, :achternaam, :registreerd)";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(
                ':voornaam' => $_POST['voornaam'],
                ':tussenvoegsel' => $_POST['tussenvoegsel'],
                ':achternaam' => $_POST['achternaam'],
                ':registreerd' => $registered
            ));
        } else {
            $_SESSION['data_warning'] = 'Deze schrijver bestaat al.';
        }
    }
    header('Location: ../?page=data_beheren&keuze=schrijvers');
}
if (isset($_POST['taalButton'])) {
    if (isset($_POST['registerd'])) {
        $registered = 1;
    }

    $sql = "SELECT taal FROM talen WHERE :taal = taal";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':taal' => $_POST['taal']));

    if (!($rsTaal = $sth->fetch())) {
        $sql = "INSERT INTO talen (taal, registreerd) VALUES (:taal, :registreerd)";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':taal' => $_POST['taal'],
            ':registreerd' => $registered
        ));
    } else {
        $_SESSION['data_warning'] = 'Deze taal bestaat al.';
    }
    header('Location: ../?page=data_beheren&keuze=talen');
}
if (isset($_POST['gebruikerButton'])) {
    $userInfo = array(
        'voornaam' => $_POST['voornaam'],
        'tussenvoegsel' => $_POST['tussenvoegsel'],
        'achternaam' => $_POST['achternaam'],
        'woonplaats' => $_POST['woonplaats'],
        'straat' => $_POST['straat'],
        'huisnummer' => $_POST['huisnummer'],
        'postcode' => $_POST['postcode'],
        'email' => $_POST['email'],
        'wachtwoord' => password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT),
        'geboortedatum' => $_POST['geboortedatum']
    );
    $rol = 2;

    if ($_POST['rol'] === 'admin') {
        $rol = 1;
    }

    $sql = 'SELECT email FROM gebruikers WHERE email = :email';
    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(":email" => $userInfo['email']));

    if ($rsEmail = $sth->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['data_warning'] = 'Email is al geregistreerd.';
    } else {
        $sql = 'INSERT INTO gebruikers (email, wachtwoord, rol_id, voornaam, tussenvoegsel, achternaam, straat, huisnummer, postcode, geboortedatum, aantal_boetes) VALUES (:email, :pw, :rol, :vn, :tv, :an, :str, :hn, :pc, :gd, 0)';

        $sth = $dbh->prepare($sql);

        $sth->execute(array(
            ':email' => $userInfo['email'],
            ':pw' => $userInfo['wachtwoord'],
            'rol' => $rol,
            ':vn' => $userInfo['voornaam'],
            ':tv' => $userInfo['tussenvoegsel'],
            ':an' => $userInfo['achternaam'],
            ':str' => $userInfo['straat'],
            ':hn' => $userInfo['huisnummer'],
            ':pc' => $userInfo['postcode'],
            ':gd' => $userInfo['geboortedatum']
        ));

        $sql = "SELECT t1.id, t1.woonplaats FROM woonplaats t1 INNER JOIN gebruikers t2 WHERE t1.woonplaats = :wp";
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $sth->execute(array(":wp" => $userInfo['woonplaats']));

        if ($rswoonplaats = $sth->fetch(PDO::FETCH_ASSOC)) {
            $sql = "UPDATE gebruikers SET woonplaats_id = :id WHERE email = :email";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(
                ":id" => $rswoonplaats['id'],
                ":email" => $userInfo['email']
            ));
        } else {
            $sql = "INSERT INTO woonplaats (woonplaats) VALUES (:wp)";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(
                ":wp" => $userInfo['woonplaats']
            ));

            $sql = "SELECT t1.id, t1.woonplaats FROM woonplaats t1 INNER JOIN gebruikers t2 WHERE t1.woonplaats = :wp";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
            $sth->execute(array(":wp" => $userInfo['woonplaats']));

            if ($rswoonplaats = $sth->fetch(PDO::FETCH_ASSOC)) {

                $sql = "UPDATE gebruikers SET woonplaats_id = :id WHERE email = :email";
                $sth = $dbh->prepare($sql);
                $sth->execute(array(
                    ":id" => $rswoonplaats['id'],
                    ":email" => $userInfo['email']
                ));
            }
        }

        header('Location: ../?page=data_beheren&keuze=gebruikers');
    }
}