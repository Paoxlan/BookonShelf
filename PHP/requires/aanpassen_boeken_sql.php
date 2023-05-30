<?php
function editTaal($taal, $boek): void {
    global $dbh;

    $sql = "SELECT id FROM talen WHERE taal = :taal";
    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $sth->execute(array(':taal' => $taal));

    if (!($rsTaal = $sth->fetch(PDO::FETCH_ASSOC))) {
        $sql = "INSERT INTO talen (taal) VALUES (:taal)";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(':taal' => $taal));

        $sql = "SELECT id FROM talen WHERE taal = :taal";
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $sth->execute(array(':taal' => $taal));

        $rsTaal = $sth->fetch(PDO::FETCH_ASSOC);

    }
    $sql = "UPDATE boeken SET taal_id = :id WHERE naam = :naam";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':id' => $rsTaal['id'],
        ':naam' => $boek
    ));
}

function editGenre($genre, $boek): void {
    global $dbh;

    $sql = "SELECT id FROM genres WHERE genre = :genre";
    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $sth->execute(array(':genre' => $genre));

    if (!($rsGenre = $sth->fetch(PDO::FETCH_ASSOC))) {
        $sql = "INSERT INTO genres (genre) VALUES (:genre)";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(':genre' => $genre));

        $sql = "SELECT id FROM genres WHERE genre = :genre";
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $sth->execute(array(':genre' => $genre));

        $rsGenre = $sth->fetch(PDO::FETCH_ASSOC);

    }
    $sql = "UPDATE boeken SET genre_id = :id WHERE naam = :naam";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(
        ':id' => $rsGenre['id'],
        ':naam' => $boek
    ));
}

function editSchrijver($schrijver, $boek): void {
    global $dbh;

    $schrijverArr = explode(" ", $schrijver, 3);

    if (!(sizeof($schrijverArr) === 1)) {
        if (sizeof($schrijverArr) === 2) {
                $sql = "SELECT id FROM schrijvers WHERE voornaam = :voornaam AND achternaam = :achternaam";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
            $sth->execute(array(
                ':voornaam' => $schrijverArr[0],
                ':achternaam' => $schrijverArr[1]
            ));
        } else {
            $sql = "SELECT id FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel = :tussenvoegsel AND achternaam = :achternaam";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
            $sth->execute(array(
                ':voornaam' => $schrijverArr[0],
                ':tussenvoegsel' => $schrijverArr[1],
                ':achternaam' => $schrijverArr[2]
            ));
        }

        if (!($rsSchrijver = $sth->fetch(PDO::FETCH_ASSOC))) {
            if (sizeof($schrijverArr) === 2) {
                $sql = "INSERT INTO schrijvers (voornaam, achternaam) VALUES (:voornaam, :achternaam)";
                $sth = $dbh->prepare($sql);
                $sth->execute(array(
                    ':voornaam' => $schrijverArr[0],
                    ':achternaam' => $schrijverArr[1]
                    ));

                $sql = "SELECT id FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel IS NULL AND achternaam = :achternaam";
                $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
                $sth->execute(array(
                    ':voornaam' => $schrijverArr[0],
                    ':achternaam' => $schrijverArr[1]
                    ));
            } else {
                $sql = "INSERT INTO schrijvers (voornaam, tussenvoegsel, achternaam) VALUES (:voornaam, :tussenvoegsel,  :achternaam)";
                $sth = $dbh->prepare($sql);
                $sth->execute(array(
                    ':voornaam' => $schrijverArr[0],
                    ':tussenvoegsel' => $schrijverArr[1],
                    ':achternaam' => $schrijverArr[2]
                ));

                $sql = "SELECT id FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel = :tussenvoegsel AND achternaam = :achternaam";
                $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
                $sth->execute(array(
                    ':voornaam' => $schrijverArr[0],
                    ':tussenvoegsel' => $schrijverArr[1],
                    ':achternaam' => $schrijverArr[2]
                ));
            }

            $rsSchrijver = $sth->fetch(PDO::FETCH_ASSOC);
        }

        $sql = "UPDATE boeken SET schrijver_id = :id WHERE naam = :naam";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':id' => $rsSchrijver['id'],
            ':naam' => $boek
        ));
    }
}

function editSchrijvers($schrijvers, $boek): void {
    global $dbh;

    $sql = "SELECT id FROM boeken WHERE naam = :naam";
    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $sth->execute(array(':naam' => $boek));

    $rsBoek = $sth->fetch(PDO::FETCH_ASSOC);

    $sql = "DELETE FROM boek_schrijvers WHERE boek_id = :id";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':id' => $rsBoek['id']));

    if (!empty($schrijvers)) {
        $schrijversArr = explode(';', $schrijvers);
        foreach ($schrijversArr as $schrijver) {
            $schrijverNaam = explode(" ", $schrijver, 3);
            if (!(sizeof($schrijverNaam) === 1)) {
                if (sizeof($schrijverNaam) === 2) {
                    $sql = "SELECT id FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel IS NULL AND achternaam = :achternaam";
                    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
                    $sth->execute(array(
                        ':voornaam' => $schrijverNaam[0],
                        ':achternaam' => $schrijverNaam[1]
                    ));
                } else {
                    $sql = "SELECT id FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel = :tussenvoegsel AND achternaam = :achternaam";
                    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
                    $sth->execute(array(
                        ':voornaam' => $schrijverNaam[0],
                        ':tussenvoegsel' => $schrijverNaam[1],
                        ':achternaam' => $schrijverNaam[2]
                    ));
                }

                if (!($rsSchrijver = $sth->fetch(PDO::FETCH_ASSOC))) {
                    if (sizeof($schrijverNaam) === 2) {
                        $sql = "INSERT INTO schrijvers (voornaam, achternaam) VALUES (:voornaam, :achternaam)";
                        $sth = $dbh->prepare($sql);
                        $sth->execute(array(
                            ':voornaam' => $schrijverNaam[0],
                            ':achternaam' => $schrijverNaam[1]
                        ));

                        $sql = "SELECT id FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel IS NULL AND achternaam = :achternaam";
                        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
                        $sth->execute(array(
                            ':voornaam' => $schrijverNaam[0],
                            ':achternaam' => $schrijverNaam[1]
                        ));
                    } else {
                        $sql = "INSERT INTO schrijvers (voornaam, tussenvoegsel, achternaam) VALUES (:voornaam, :tussenvoegsel, :achternaam)";
                        $sth = $dbh->prepare($sql);
                        $sth->execute(array(
                            ':voornaam' => $schrijverNaam[0],
                            ':tussenvoegsel' => $schrijverNaam[1],
                            ':achternaam' => $schrijverNaam[2]
                        ));

                        $sql = "SELECT id FROM schrijvers WHERE voornaam = :voornaam AND tussenvoegsel = :tussenvoegsel AND achternaam = :achternaam";
                        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
                        $sth->execute(array(
                            ':voornaam' => $schrijverNaam[0],
                            ':tussenvoegsel' => $schrijverNaam[1],
                            ':achternaam' => $schrijverNaam[2]
                        ));
                    }
                    $rsSchrijver = $sth->fetch(PDO::FETCH_ASSOC);
                }
                $sql = "INSERT INTO boek_schrijvers(boek_id, schrijver_id) VALUES (:boek_id, :schrijver_id)";
                $sth = $dbh->prepare($sql);
                $sth->execute(array(
                    ':boek_id' => $rsBoek['id'],
                    ':schrijver_id' => $rsSchrijver['id']
                ));
            }
        }
    }
}