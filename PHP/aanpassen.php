<?php
require 'requires/connection.php';
require 'requires/aanpassen_boeken_sql.php';

$newNaam = null;

global $dbh;

session_start();

if (isset($_POST['naam_but'])) {
    if (!empty($_POST['boeknaam'])) {
        $sql = "SELECT naam FROM boeken WHERE naam = :newNaam";
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(":newNaam" => $_POST['boeknaam']));

        if (!$rsNewBoek = $sth->fetch(PDO::FETCH_ASSOC)) {
            $sql = "UPDATE boeken SET naam = :newNaam WHERE naam = :oldNaam";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(
                ":newNaam" => $_POST['boeknaam'],
                ":oldNaam" => $_POST['naam']
            ));

            $newNaam = $_POST['boeknaam'];
        } else {
            $_SESSION['aanpassenWarning'] =  "Boeknaam bestaat al.";
        }
    } else {
        $_SESSION['aanpassenWarning'] = "Boeknaam kan niet leeg zijn.";
    }
}

if (isset($_POST['schrijver_but'])) {
    if (!empty($_POST['schrijver'])) {
        editSchrijver($_POST['schrijver'], $_POST['naam']);
    } else {
        $_SESSION['aanpassenWarning'] = "Schrijver kan niet leeg zijn.";
    }
}

if (isset($_POST['genre_but'])) {
    if (!empty($_POST['genre'])) {
        editGenre($_POST['genre'], $_POST['naam']);
    } else {
        $_SESSION['aanpassenWarning'] = "Genre kan niet leeg zijn.";
    }
}

if (isset($_POST['isbn_but'])) {
    if (is_numeric($_POST['isbn'])) {
        if (!empty($_POST['isbn'])) {
            $sql = "SELECT `isbn-nummer` FROM boeken WHERE naam = :boek";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(":boek" => $_POST['naam']));

            $rsIsbn = $sth->fetch(PDO::FETCH_ASSOC);

            $sql = "SELECT `isbn-nummer` FROM boeken WHERE `isbn-nummer` = :newisbn";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(":newisbn" => $_POST['isbn']));

            if (!$rsNewIsbn = $sth->fetch(PDO::FETCH_ASSOC)) {
                $sql = "UPDATE boeken SET `isbn-nummer` = :newisbn WHERE naam = :boek";
                $sth = $dbh->prepare($sql);
                $sth->execute(array(
                    ':newisbn' => $_POST['isbn'],
                    ':boek' => $_POST['naam']
                ));
            } else {
                $_SESSION['aanpassenWarning'] = "ISBN-nummer bestaat al.";
            }
        } else {
            $_SESSION['aanpassenWarning'] = "ISBN-nummer kan niet leeg zijn.";
        }
    } else {
        $_SESSION['aanpassenWarning'] = "ISBN-nummer heeft een letter.";
    }
}

if (isset($_POST['taal_but'])) {
    if (!empty($_POST['taal'])) {
        editTaal($_POST['taal'], $_POST['naam']);
    } else {
        $_SESSION['aanpassenWarning'] = "Taal kan niet leeg zijn.";
    }
}

if (isset($_POST['pagina_but'])) {
    if ($_POST['pagina'] > 0) {
        $sql = "UPDATE boeken SET `pagina's` = :pagina WHERE naam = :boeknaam";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ":pagina" => $_POST['pagina'],
            ":boeknaam" => $_POST['naam']
        ));
    } else {
        $_SESSION['aanpassenWarning'] = "Aantal pagina's moet boven de nul zijn.";
    }
}

if (isset($_POST['exemplaren_but'])) {
    $sql = "SELECT aantal_exemplaren FROM boeken WHERE naam = :naam";
    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(":naam" => $_POST['naam']));

    $rsAantalExem = $sth->fetch(PDO::FETCH_ASSOC);

    if ($_POST['exemplaren'] >= $rsAantalExem['aantal_exemplaren']) {
        $sql = "UPDATE boeken SET exemplaren = :exemplaren WHERE naam = :naam";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ":exemplaren" => $_POST['exemplaren'],
            ":naam" => $_POST['naam']
        ));
    } else {
        $_SESSION['aanpassenWarning'] = "Huidige exemplaren komen onder de nul!";
    }
}

if (isset($_POST['schrijvers_but'])) {
    editSchrijvers($_POST['schrijvers'], $_POST['naam']);
}

if (isset($_POST['image_but'])) {
    $target_dir = '../images/boekimages/';
    $target_file = $target_dir . basename($_FILES["boekimage"]["name"]);
    $target_file_index = 'images/boekimages/' . basename($_FILES['boekimage']['name']);
    $uploadOk = true;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (file_exists($target_file)) {
        $_SESSION['aanpassenWarning'] = "Afbeelding bestaat al.";
        $uploadOk = false;
    }

    if ($_FILES['boekimage']['size'] > 500000) {
        $_SESSION['aanpassenWarning'] = "Afbeelding is te groot, max 500KBs.";
        $uploadOk = false;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $_SESSION['aanpassenWarning'] = "Afbeeling kan alleen in jpg, jpeg of png.";
        $uploadOk = false;
    }

    if ($uploadOk) {
        if (move_uploaded_file($_FILES['boekimage']['tmp_name'], $target_file)) {
            $sql = "SELECT afbeelding FROM boeken WHERE naam = :naam";
            $sth = $dbh->prepare($sql);
            $sth->execute(array('naam' => $_POST['naam']));

            if ($rsAfbeelding = $sth->fetch(PDO::FETCH_ASSOC)) {
                unlink('../' . $rsAfbeelding['afbeelding']);
            }

            $sql = "UPDATE boeken SET afbeelding = :target_file WHERE naam = :naam";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(
                ':target_file' => $target_file_index,
                ':naam' => $_POST['naam']
            ));
        } else {
            $_SESSION['aanpassenWarning'] = "Afbeelding uploaden is mislukt.";
        }
    }
}

if (isset($newNaam)) {
    header("Location: ../?page=aanpassen&boek=$newNaam");
} else {
    header("Location: ../?page=aanpassen&boek={$_POST['naam']}");
}