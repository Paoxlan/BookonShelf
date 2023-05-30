<?php
require 'requires/connection.php';
global $dbh;

$id = $_POST['id'] ?? -1;
$registered = 0;

if ($id === -1) {
    header("Location: ../?page=data_beheren");
    exit;
}

session_start();

// Genre
if (isset($_POST['genreButton'])) {
    $sql = "SELECT genre FROM genres WHERE genre = :genre";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':genre' => $_POST['genre']));

    if (!($sth->fetch())) {
        $sql = "UPDATE genres SET genre = :genre WHERE id = :id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':genre' => $_POST['genre'],
            ':id' => $id
        ));
    } else {
        $_SESSION['aanpassenWarning'] = 'Genre bestaat al!';
    }
}
if (isset($_POST['genreRegButton'])) {
    if (isset($_POST['genreReg'])) {
        $registered = 1;
    }
    $sql = "UPDATE genres SET registreerd = :reg WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':reg' => $registered,
        ':id' => $id
    ));
}
// Schrijver
if (isset($_POST['voornaamSchButton'])) {
    $sql = "SELECT voornaam, tussenvoegsel, achternaam FROM schrijvers WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':id' => $id));

    $rsSchrijver = $sth->fetch();

    if ($rsSchrijver['tussenvoegsel'] === null) {
        $sql = "SELECT voornaam, tussenvoegsel, achternaam FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel IS NULL AND achternaam = :achternaam";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':voornaam' => $_POST['voornaam'],
            ':achternaam' => $rsSchrijver['achternaam']
        ));
    } else {
        $sql = "SELECT voornaam, tussenvoegsel, achternaam FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel = :tussenvoegsel AND achternaam = :achternaam";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':voornaam' => $_POST['voornaam'],
            ':tussenvoegsel' => $rsSchrijver['tussenvoegsel'],
            ':achternaam' => $rsSchrijver['achternaam']
        ));

    }
    if (!($sth->fetch())) {
        $sql = "UPDATE schrijvers SET voornaam = :voornaam WHERE id = :id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':voornaam' => $_POST['voornaam'],
            ':id' => $id
        ));
    } else {
        $_SESSION['aanpassenWarning'] = 'schrijver kan niet hetzelfde zijn als een andere schrijver!';
    }
}

if (isset($_POST['tussenvoegselSchButton'])) {
    $sql = "SELECT voornaam, tussenvoegsel, achternaam FROM schrijvers WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':id' => $id));

    $rsSchrijver = $sth->fetch();

    $sql = "SELECT voornaam, tussenvoegsel, achternaam FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel = :tussenvoegsel AND achternaam = :achternaam";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':voornaam' => $rsSchrijver['voornaam'],
        ':tussenvoegsel' => $_POST['tussenvoegsel'],
        ':achternaam' => $rsSchrijver['achternaam']
    ));

    if (!($sth->fetch())) {
        if (empty($_POST['tussenvoegsel'])) {
            $sql = "UPDATE schrijvers SET tussenvoegsel = NULL WHERE id = :id";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(':id' => $id));
        } else {
            $sql = "UPDATE schrijvers SET tussenvoegsel = :tussenvoegsel WHERE id = :id";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(
                ':tussenvoegsel' => $_POST['tussenvoegsel'],
                ':id' => $id
            ));
        }

    } else {
        $_SESSION['aanpassenWarning'] = 'schrijver kan niet hetzelfde zijn als een andere schrijver!';
    }
}

if (isset($_POST['achternaamSchButton'])) {
    $sql = "SELECT voornaam, tussenvoegsel, achternaam FROM schrijvers WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':id' => $id));

    $rsSchrijver = $sth->fetch();

    if ($rsSchrijver['tussenvoegsel'] === null) {
        $sql = "SELECT voornaam, tussenvoegsel, achternaam FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel IS NULL AND achternaam = :achternaam";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':voornaam' => $rsSchrijver['voornaam'],
            ':achternaam' => $_POST['achternaam']
        ));
    } else {
        $sql = "SELECT voornaam, tussenvoegsel, achternaam FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel = :tussenvoegsel AND achternaam = :achternaam";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':voornaam' => $rsSchrijver['voornaam'],
            ':tussenvoegsel' => $rsSchrijver['tussenvoegsel'],
            ':achternaam' => $_POST['achternaam']
        ));
    }
    if (!($sth->fetch())) {
        $sql = "UPDATE schrijvers SET achternaam = :achternaam WHERE id = :id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':achternaam' => $_POST['achternaam'],
            ':id' => $id
        ));
    } else {
        $_SESSION['aanpassenWarning'] = 'schrijver kan niet hetzelfde zijn als een andere schrijver!';
    }
}

if (isset($_POST['schrijverRegButton'])) {
    if (isset($_POST['schrijverReg'])) {
        $registered = 1;
    }
    $sql = "UPDATE schrijvers SET registreerd = :reg WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':reg' => $registered,
        ':id' => $id
    ));
}

// Taal

if (isset($_POST['taalButton'])) {
    $sql = "SELECT taal FROM talen WHERE taal = :taal";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':taal' => $_POST['taal']));

    if (!($sth->fetch())) {
        $sql = "UPDATE talen SET taal = :taal WHERE id = :id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':taal' => $_POST['taal'],
            ':id' => $id
        ));
    } else {
        $_SESSION['aanpassenWarning'] = 'Taal bestaat al!';
    }
}
if (isset($_POST['taalRegButton'])) {
    if (isset($_POST['taalReg'])) {
        $registered = 1;
    }
    $sql = "UPDATE talen SET registreerd = :reg WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':reg' => $registered,
        ':id' => $id
    ));
}

// Gebruiker
if (isset($_POST['voornaamGebButton'])) {
    $sql = "UPDATE gebruikers SET voornaam = :voornaam WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':voornaam' => $_POST['voornaam'],
        ':id' => $id
    ));
}
if (isset($_POST['tussenvoegselGebButton'])) {
    $sql = "UPDATE gebruikers SET tussenvoegsel = :tussenvoegsel WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':tussenvoegsel' => $_POST['tussenvoegsel'],
        ':id' => $id
    ));
}
if (isset($_POST['achternaamGebButton'])) {
    $sql = "UPDATE gebruikers SET achternaam = :achternaam WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':achternaam' => $_POST['achternaam'],
        ':id' => $id
    ));
}
if (isset($_POST['woonplaatsButton'])) {
    $sql = "SELECT t1.id, t1.woonplaats FROM woonplaats t1 INNER JOIN gebruikers t2 WHERE t1.woonplaats = :wp";
    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $sth->execute(array(":wp" => $_POST['woonplaats']));

    if ($rswoonplaats = $sth->fetch(PDO::FETCH_ASSOC)) {
        $sql = "UPDATE gebruikers SET woonplaats_id = :wp_id WHERE id = :id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ":wp_id" => $rswoonplaats['id'],
            ":id" => $id
        ));
    } else {
        $sql = "INSERT INTO woonplaats (woonplaats) VALUES (:wp)";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ":wp" => $_POST['woonplaats']
        ));

        $sql = "SELECT t1.id, t1.woonplaats FROM woonplaats t1 INNER JOIN gebruikers t2 WHERE t1.woonplaats = :wp";
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $sth->execute(array(":wp" => $_POST['woonplaats']));

        if ($rswoonplaats = $sth->fetch(PDO::FETCH_ASSOC)) {

            $sql = "UPDATE gebruikers SET woonplaats_id = :wp_id WHERE id = :id";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(
                ":wp_id" => $rswoonplaats['id'],
                ":id" => $id
            ));
        }
    }
}
if (isset($_POST['straatButton'])) {
    $sql = "UPDATE gebruikers SET straat = :straat WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':straat' => $_POST['straat'],
        ':id' => $id
    ));
}
if (isset($_POST['huisnummerButton'])) {
    $sql = "UPDATE gebruikers SET huisnummer = :huisnummer WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':huisnummer' => $_POST['huisnummer'],
        ':id' => $id
    ));
}
if (isset($_POST['postcodeButton'])) {
    $sql = "UPDATE gebruikers SET postcode = :postcode WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':postcode' => $_POST['postcode'],
        ':id' => $id
    ));
}
if (isset($_POST['emailButton'])) {
    $sql = "SELECT id FROM gebruikers WHERE email = :email";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':email' => $_POST['email']));

    if (!($rsEmail = $sth->fetch())) {
        $sql = "SELECT email FROM gebruikers WHERE id = :id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(':id' => $id));

        $rsEmail = $sth->fetch();

        if ($_SESSION['email'] === $rsEmail['email']) {
            $_SESSION['email'] = $_POST['email'];
        }

        $sql = "UPDATE gebruikers SET email = :email WHERE id = :id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':email' => $_POST['email'],
            ':id' => $id
        ));
    } else {
        $_SESSION['aanpassenWarning'] = 'email is al registreerd!';
    }
}
if (isset($_POST['wachtwoordButton'])) {
    $pw = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);

    $sql = "UPDATE gebruikers SET wachtwoord = :pw WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':pw' => $pw,
        ':id' => $id
    ));
}
if (isset($_POST['geboortedatumButton'])) {
    $sql = "UPDATE gebruikers SET geboortedatum = :gd WHERE id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':gd' => $_POST['geboortedatum'],
        ':id' => $id
    ));
}
if (isset($_POST['rolButton'])) {
    $rol_id = 2;
    if ($_POST['rol'] === 'admin') {
        $rol_id = 1;
    }

    $sql = "SELECT rol, email FROM gebruikers g INNER JOIN rollen r ON g.rol_id = r.id WHERE g.id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':id' => $id));

    $rsRol = $sth->fetch();
    if ($rsRol['email'] === $_SESSION['email']) {
        $_SESSION['aanpassenWarning'] = 'U kunt niet zichzelf degraderen!';
    } else {
        $sql = "UPDATE gebruikers SET rol_id = :rol_id WHERE id = :id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':rol_id' => $rol_id,
            ':id' => $id
        ));
    }
}

header("Location: ../?page=data_aanpassen&id=$id&keuze={$_POST['keuze']}");