<?php

require 'connection.php';

// Dit php file is gebruikt om sql queries in een functie te zetten zodat ik niet hoef dezelfde codes te
// herhalen.

// Taal functies
function addTaal($taal, $boek): void
{
    global $dbh;

    $sql = "SELECT id, taal FROM talen WHERE taal = :taal";
    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $sth->execute(array(':taal' => $taal));

    if ($rsTaal = $sth->fetch(PDO::FETCH_ASSOC)) {
        $sql = "UPDATE boeken SET taal_id = :id WHERE naam = :boek";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':id' => $rsTaal['id'],
            ':boek' => $boek
        ));
    } else {
        $sql = "INSERT INTO talen (taal) VALUES (:taal) ";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(':taal' => $taal));

        $sql = "SELECT id, taal FROM talen WHERE taal = :taal";
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $sth->execute(array(':taal' => $taal));

        $rsTaal = $sth->fetch(PDO::FETCH_ASSOC);
    }

    $sql = "UPDATE boeken SET taal_id = :id WHERE naam = :boek";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':id' => $rsTaal['id'],
        ':boek' => $boek
    ));
}

function getTaal($id) {
    global $dbh;

    $sql = "SELECT t1.taal FROM talen t1 INNER JOIN boeken t2 WHERE t1.id = :id";
    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $sth->execute(array(':id' => $id));

    $rsTaal = $sth->fetch(PDO::FETCH_ASSOC);

    return $rsTaal['taal'];
}

// Genre functies
function addGenre($genre, $boek): void {
    global $dbh;

    $sql = "SELECT id, genre FROM genres WHERE genre = :genre";
    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $sth->execute(array(':genre' => $genre));

    if ($rsGenre = $sth->fetch(PDO::FETCH_ASSOC)) {
        $sql = "UPDATE boeken SET genre_id = :id WHERE naam = :boek";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':id' => $rsGenre['id'],
            ':boek' => $boek
        ));
    } else {
        $sql = "INSERT INTO genres (genre) VALUES (:genre)";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(':genre' => $genre));

        $sql = "SELECT id, genre FROM genres WHERE genre = :genre";
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $sth->execute(array(':genre' => $genre));

        if ($rsGenre = $sth->fetch(PDO::FETCH_ASSOC)) {
            $sql = "UPDATE boeken SET genre_id = :id WHERE naam = :boek";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(
                ':id' => $rsGenre['id'],
                ':boek' => $boek
            ));
        }
    }
}

function getGenre($id) {
    global $dbh;

    $sql = "SELECT genre FROM genres WHERE id = :id";
    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $sth->execute(array(':id' => $id));

    $rsGenre = $sth->fetch(PDO::FETCH_ASSOC);

    return $rsGenre['genre'];
}

//Schrijver functies
function addSchrijver($schrijver, $boek): void {
    global $dbh;

    $schrijvernaam = explode(" ", $schrijver, 3);

    $sth = $dbh->prepare("SELECT voornaam FROM schrijvers WHERE id = -1");
    $sth->execute();

    if (!(sizeof($schrijvernaam) === 1)) {

        if (sizeof($schrijvernaam) === 3) {
            $sql = "SELECT id, voornaam, tussenvoegsel, achternaam FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel = :tussenvoegsel AND achternaam = :achternaam";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
            $sth->execute(array(
                ':voornaam' => $schrijvernaam[0],
                ':tussenvoegsel' => $schrijvernaam[1],
                ':achternaam' => $schrijvernaam[2]
            ));
        } elseif (sizeof($schrijvernaam) === 2) {
            $sql = "SELECT id, voornaam, achternaam FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel IS NULL AND achternaam = :achternaam";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
            $sth->execute(array(
                ':voornaam' => $schrijvernaam[0],
                ':achternaam' => $schrijvernaam[1]
            ));
        }

        if ($rsSchrijver = $sth->fetch(PDO::FETCH_ASSOC)) {
            $sql = "UPDATE boeken SET schrijver_id = :id WHERE naam = :boek";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(
                ':id' => $rsSchrijver['id'],
                ':boek' => $boek
            ));
        } else {
            if (sizeof($schrijvernaam) === 3) {
                $sql = "INSERT INTO schrijvers (voornaam, tussenvoegsel, achternaam) VALUES (:voornaam, :tussenvoegsel, :achternaam)";
                $sth = $dbh->prepare($sql);
                $sth->execute(array(
                    'voornaam' => $schrijvernaam[0],
                    ':tussenvoegsel' => $schrijvernaam[1],
                    ':achternaam' => $schrijvernaam[2]
                ));

                $sql = "SELECT id FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel = :tussenvoegsel AND achternaam = :achternaam";
                $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
                $sth->execute(array(
                    ':voornaam' => $schrijvernaam[0],
                    ':tussenvoegsel' => $schrijvernaam[1],
                    ':achternaam' => $schrijvernaam[2]
                ));
            } elseif (sizeof($schrijvernaam) === 2) {
                $sql = "INSERT INTO schrijvers (voornaam, achternaam) VALUES (:voornaam, :achternaam)";
                $sth = $dbh->prepare($sql);
                $sth->execute(array(
                    ':voornaam' => $schrijvernaam[0],
                    ':achternaam' => $schrijvernaam[1]
                ));

                $sql = "SELECT id FROM schrijvers WHERE voornaam = :voornaam AND achternaam = :achternaam";
                $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
                $sth->execute(array(
                    ':voornaam' => $schrijvernaam[0],
                    ':achternaam' => $schrijvernaam[1]
                ));
            }

            if ($rsSchrijver = $sth->fetch(PDO::FETCH_ASSOC)) {
                $sql = "UPDATE boeken SET schrijver_id = :id WHERE naam = :boek";
                $sth = $dbh->prepare($sql);
                $sth->execute(array(
                    ':id' => $rsSchrijver['id'],
                    ':boek' => $boek
                ));
            }
        }
    }
}

function getSchrijver($id): string {
    global $dbh;

    if ($id !== null) {
        $sql = "SELECT voornaam, tussenvoegsel, achternaam FROM schrijvers WHERE id = :id";
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $id));

        $rsSchrijver = $sth->fetch(PDO::FETCH_ASSOC);

        if ($rsSchrijver['tussenvoegsel'] === null) {
            return $rsSchrijver['voornaam'] . " " . $rsSchrijver['achternaam'];
        }
        return $rsSchrijver['voornaam'] . " ". $rsSchrijver['tussenvoegsel'] . " " . $rsSchrijver['achternaam'];
    }
    return '?';
}

//Meerdere schrijvers functie
function addSchrijvers($schrijvers, $boek): void {
    global $dbh;

    $schrijversArr = explode(';', $schrijvers);
    foreach ($schrijversArr as $schrijver) {
        $schrijvernaam = explode(" ", $schrijver, 3);

        if (!(sizeof($schrijvernaam) === 1)) {
            if (sizeof($schrijvernaam) === 3) {
                $sql = "SELECT id FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel = :tussenvoegsel AND achternaam = :achternaam";
                $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
                $sth->execute(array(
                    ':voornaam' => $schrijvernaam[0],
                    ':tussenvoegsel' => $schrijvernaam[1],
                    ':achternaam' => $schrijvernaam[2]
                ));
            } else {
                $sql = "SELECT id FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel IS NULL AND achternaam = :achternaam";
                $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
                $sth->execute(array(
                    ':voornaam' => $schrijvernaam[0],
                    ':achternaam' => $schrijvernaam[1]
                ));
            }

            if (!($rsSchrijver = $sth->fetch(PDO::FETCH_ASSOC))) {
                if (sizeof($schrijvernaam) === 3) {
                    $sql = "INSERT INTO schrijvers (voornaam, tussenvoegsel, achternaam)  VALUES (:voornaam, :tussenvoegsel, :achternaam)";
                    $sth = $dbh->prepare($sql);
                    $sth->execute(array(
                        ':voornaam' => $schrijvernaam[0],
                        ':tussenvoegsel' => $schrijvernaam[1],
                        ':achternaam' => $schrijvernaam[2]
                    ));

                    $sql = "SELECT id FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel = :tussenvoegsel AND achternaam = :tussenvoegsel";
                    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
                    $sth->execute(array(
                        ':voornaam' => $schrijvernaam[0],
                        ':tussenvoegsel' => $schrijvernaam[1],
                        ':achternaam' => $schrijvernaam[2]
                    ));
                } elseif (sizeof($schrijvernaam) === 2) {
                    $sql = "INSERT INTO schrijvers (voornaam, achternaam)  VALUES (:voornaam, :achternaam)";
                    $sth = $dbh->prepare($sql);
                    $sth->execute(array(
                        ':voornaam' => $schrijvernaam[0],
                        ':achternaam' => $schrijvernaam[1]
                    ));

                    $sql = "SELECT id FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel IS NULL AND achternaam = :tussenvoegsel";
                    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
                    $sth->execute(array(
                        ':voornaam' => $schrijvernaam[0],
                        ':achternaam' => $schrijvernaam[1]
                    ));
                }
                $rsSchrijver = $sth->fetch(PDO::FETCH_ASSOC);

            }
            $sql = "SELECT id FROM boeken WHERE naam = :naam";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
            $sth->execute(array(':naam' => $boek));

            $rsBoek = $sth->fetch(PDO::FETCH_ASSOC);
            $sql = "INSERT INTO boek_schrijvers (boek_id, schrijver_id) VALUES (:boek_id, :schrijver_id)";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(
                ':boek_id' => $rsBoek['id'],
                ':schrijver_id' => $rsSchrijver['id']
            ));
        }
    }
}

function getSchrijvers($id): void {
    global $dbh;

    $sql = "SELECT voornaam, tussenvoegsel, achternaam FROM schrijvers t1 INNER JOIN boek_schrijvers t2 ON t1.id = t2.schrijver_id WHERE t2.boek_id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':id' => $id));

    if ($result = $sth->fetchAll()) {
        foreach ($result as $row) {
            if ($row['tussenvoegsel'] === null) {
                echo $row['voornaam'] . " " . $row['achternaam'] . ", ";
            } else {
                echo $row['voornaam'] . " " . $row['tussenvoegsel'] . " " . $row['achternaam'] . ", ";
            }
        }
    } else {
        echo '-';
    }
}