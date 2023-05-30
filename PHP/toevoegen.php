<?php

$boekInfo = array(
    'naam' => $_POST['boeknaam'],
    'schrijver' => $_POST['schrijver'],
    'genre' => $_POST['genre'],
    'isbn' => $_POST['isbn'],
    'taal' => $_POST['taal'],
    'pagina' => $_POST['pagina'],
    'exemplaren' => $_POST['exemplaren'],
    'schrijvers' => $_POST['schrijvers']
);

$checkFailed = false;

session_start();

if (!is_numeric($boekInfo['isbn'])) {
    $_SESSION['toevoegenWarning'] = $_SESSION['toevoegenWarning'] . 'isbn heeft een letter. ';
    $checkFailed = true;
}

if (!is_numeric($boekInfo['pagina'])) {
    $_SESSION['toevoegenWarning'] = $_SESSION['toevoegenWarning'] . 'Pagina heeft een letter. ';
    $checkFailed = true;
} elseif ($boekInfo['pagina'] <= 0) {
    $_SESSION['toevoegenWarning'] = $_SESSION['toevoegenWarning'] . "Aantal pagina's moet boven de nul zijn. ";
    $checkFailed = true;
}

if (!is_numeric($boekInfo['exemplaren'])) {
    $_SESSION['toevoegenWarning'] = $_SESSION['toevoegenWarning'] . 'Exemplaren heeft een letter. ';
    $checkFailed = true;
} elseif ($boekInfo['exemplaren'] <= 0) {
    $_SESSION['toevoegenWarning'] = $_SESSION['toevoegenWarning'] . 'Aantal exemplaren moet boven de nul zijn. ';
    $checkFailed = true;
}

foreach ($boekInfo as $key => $value) {
    if (empty($value) && $key != 'schrijvers') {
        $checkFailed = true;
        $_SESSION['toevoegenWarning'] = $_SESSION['toevoegenWarning'] . "Alle velden met '*' moeten ingevuld worden. ";
    }
}

require 'requires/connection.php';
global $dbh;
require 'requires/boeken_sql.php';

function imageUpload(): void
{
    global $dbh, $boekInfo;

    $target_dir = '../images/boekimages/';
    $target_file = $target_dir . basename($_FILES["boekimage"]["name"]);
    $target_file_index = 'images/boekimages/' . basename($_FILES["boekimage"]["name"]);
    $uploadOk = true;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (file_exists($target_file)) {
        $_SESSION['toevoegenWarningFoto'] = 'De gekozen bestand is niet een afbeelding.';
        $uploadOk = false;
    }

    if ($_FILES["boekimage"]["size"] > 500000) {
        $_SESSION['toevoegenWarningFoto'] = 'De gekozen bestand is te groot.';
        $uploadOk = false;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $_SESSION['toevoegenWarningFoto'] = "Afbeelding mag alleen in jpg, jpeg en png.";
        $uploadOk = false;
    }

    if ($uploadOk) {
        if (move_uploaded_file($_FILES["boekimage"]["tmp_name"], $target_file)) {
            $sql = "UPDATE boeken SET afbeelding = :target_file WHERE naam = :naam";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(
                ':target_file' => $target_file_index,
                ':naam' => $boekInfo['naam']
            ));
        }
    } else {
        header("Location: ../?page=toevoegen");
    }
}

if (!$checkFailed) {

    $sql = "SELECT naam FROM boeken WHERE naam = :naam";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':naam' => $boekInfo['naam']));

    if (!$rsNaam = $sth->fetch(PDO::FETCH_ASSOC)) {
        $sql = "SELECT `isbn-nummer` FROM boeken WHERE `isbn-nummer` = :isbn";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(':isbn' => $boekInfo['isbn']));

        if (!$rsIsbn = $sth->fetch(PDO::FETCH_ASSOC)) {
            $sql = "INSERT INTO boeken (naam, `isbn-nummer`, `pagina's`, exemplaren, aantal_exemplaren) VALUES (:naam, :isbn, :pagina, :exemplaren, :exemplaren)";

            $sth = $dbh->prepare($sql);

            $sth->execute(array(
                ':naam' => $boekInfo['naam'],
                ':isbn' => $boekInfo['isbn'],
                ':pagina' => $boekInfo['pagina'],
                ':exemplaren' => $boekInfo['exemplaren']
            ));

            addSchrijver($boekInfo['schrijver'], $boekInfo['naam']);
            addGenre($boekInfo['genre'], $boekInfo['naam']);
            addTaal($boekInfo['taal'], $boekInfo['naam']);

            if (!empty($boekInfo['schrijvers'])) {
                addSchrijvers($boekInfo['schrijvers'], $boekInfo['naam']);
            }

            if (is_uploaded_file($_FILES['boekimage']['tmp_name'])) {
                imageUpload();
            }
            header('Location: ../?page=overzicht');
        } else {
            $_SESSION['toevoegenWarning'] = "ISBN-nummer van de boek bestaat al.";
            header('Location: ../?page=toevoegen');
        }
    } else {
        $_SESSION['toevoegenWarning'] = "Naam van de boek bestaat al.";
        header('Location: ../?page=toevoegen');
    }
} else {
    header('Location: ../?page=toevoegen');
}