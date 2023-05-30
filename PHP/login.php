<?php
require 'requires/connection.php';
global $dbh;

$sql = 'SELECT id, email, wachtwoord, rol_id FROM gebruikers WHERE email = :email';
$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute(array(":email" => $_POST['email']));

session_start();

if ($rsGebruiker = $sth->fetch(PDO::FETCH_ASSOC)) {
    if ($rsGebruiker['email'] === $_POST['email'] && password_verify($_POST['wachtwoord'], $rsGebruiker['wachtwoord'])) {

        $gebruikers_rol_id = $rsGebruiker['rol_id'];

        $sql = 'SELECT rol FROM gebruikers INNER JOIN rollen ON rol_id = rollen.id WHERE :gebruikers_rol_id = rollen.id';
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(":gebruikers_rol_id" => $gebruikers_rol_id));

        $rsRol = $sth->fetch(PDO::FETCH_ASSOC);

        if ($rsRol['rol'] == 'admin') {
            $_SESSION['rol'] = 'admin';
        } else {
            $_SESSION['rol'] = 'klant';
        }
        $_SESSION['login_success'] = true;
        $_SESSION['email'] = $_POST['email'];
        header('Location: ../?page=overzicht');
    } else {
        header('Location: ../?page=login');
        $_SESSION['login_success'] = false;
    }
} else {
    header('Location: ../?page=login');
    $_SESSION['login_success'] = false;
}