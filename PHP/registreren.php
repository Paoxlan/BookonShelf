<?php

$userInfo = array(
    'fname' => $_POST['fname'],
    'tssnvoegsel' => $_POST['tssnvoegsel'],
    'lname' => $_POST['lname'],
    'woonplaats' => $_POST['woonplaats'],
    'straat' => $_POST['straat'],
    'huisnummer' => $_POST['huisnummer'],
    'postcode' => $_POST['postcode'],
    'email' => $_POST['email'],
    'wachtwoord' => password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT),
    'geboortedatum' => $_POST['geboortedatum']
);

$checkFailed = false;

foreach ($userInfo as $key => $value) {
    if (empty($value) && $key != 'tssnvoegsel') {
        $checkFailed = true;
    }
}

session_start();

require 'requires/connection.php';
global $dbh;

if ($checkFailed) {
    $_SESSION['rws'] = true;
    $_SESSION['message'] = "Velden met * moeten ingevuld worden!";
    header('Location: ../?page=register');
} else {
    $sql = 'SELECT email FROM gebruikers WHERE email = :email';
    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(":email" => $userInfo['email']));

    if ($rsEmail = $sth->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['rws'] = true;
        $_SESSION['message'] = 'Email is al geregistreerd.';

        header('Location: ../?page=register');
    } else {
        $sql = 'INSERT INTO gebruikers (email, wachtwoord, rol_id, voornaam, tussenvoegsel, achternaam, straat, huisnummer, postcode, geboortedatum, aantal_boetes) VALUES (:email, :pw, 2, :vn, :tv, :an, :str, :hn, :pc, :gd, 0)';

        $sth = $dbh->prepare($sql);

        $sth->execute(array(
            ':email' => $userInfo['email'],
            ':pw' => $userInfo['wachtwoord'],
            ':vn' => $userInfo['fname'],
            ':tv' => $userInfo['tssnvoegsel'],
            ':an' => $userInfo['lname'],
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

        $_SESSION['rws'] = false;

        header('Location: ../?page=login');
    }
}