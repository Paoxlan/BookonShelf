<?php

function updateOverzicht($boek): void
{
    global $dbh;

    $sql = "SELECT id, aantal_exemplaren FROM boeken WHERE naam = :boek";
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':boek' => $boek));

    $rsBoek = $sth->fetch();

    if ($rsBoek['aantal_exemplaren'] > 0) {
        $sql = "SELECT gebruiker_id, boek_id FROM gereserveerde_boeken WHERE boek_id = :boek_id ORDER BY id ASC";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(':boek_id' => $rsBoek['id']));

        if ($rsReservering = $sth->fetch()) {
            $begindatum = date("y-m-d");
            $sql = "INSERT INTO geleende_boeken (gebruiker_id, boek_id, begindatum) VALUES (:gebruiker_id, :boek_id, :begindatum)";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(
                ':gebruiker_id' => $rsReservering['gebruiker_id'],
                ':boek_id' => $rsReservering['boek_id'],
                ':begindatum' => $begindatum
            ));

            $sql = "DELETE FROM gereserveerde_boeken WHERE gebruiker_id = :gebruiker_id AND boek_id = :boek_id";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(
                ':gebruiker_id' => $rsReservering['gebruiker_id'],
                ':boek_id' => $rsReservering['boek_id']
            ));

            $sql = "UPDATE boeken SET aantal_exemplaren = aantal_exemplaren - 1 WHERE naam = :boek";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(':boek' => $boek));
        }
    }
}