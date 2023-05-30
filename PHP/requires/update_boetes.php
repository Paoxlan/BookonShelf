<?php

function updateBoetes(): void
{
    global $dbh;
    $sql = "SELECT gebruiker_id, boek_id, begindatum FROM geleende_boeken t1 INNER JOIN gebruikers t2 ON gebruiker_id = t2.id ORDER BY t1.id";
    $sth = $dbh->prepare($sql);
    $sth->execute();

    $rsGeleendeBoeken = $sth->fetchAll();
    // loopt bij elke row
    foreach ($rsGeleendeBoeken as $geleendeBoek) {
        $currentdate = strtotime(date("y-m-d"));
        $begindate = strtotime($geleendeBoek['begindatum']);
        // rekent uit in dagen
        $datediff = ($currentdate - $begindate) / (60 * 60 * 24);
        // je kunt een boek maar 21 dagen lenen.
        $boetedays = $datediff - 21;

        $sql = "SELECT boete, boek_id FROM boek_boetes WHERE gebruiker_id = :gebruiker_id AND boek_id = :boek_id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
            ':gebruiker_id' => $geleendeBoek['gebruiker_id'],
            ':boek_id' => $geleendeBoek['boek_id']
        ));

        $rsBoete = $sth->fetch();
        $boete = 0.25 * $boetedays;

        if ($boete > 10) {
            $boete = 10;
        }

        if ($boetedays > 0) {
            if (!$rsBoete) {
                // Nieuwe row aanmaken aan boek_boetes
                $sql = "INSERT INTO boek_boetes (gebruiker_id, boek_id, boete) VALUES (:gebruiker_id, :boek_id, :boete)";
                $sth = $dbh->prepare($sql);
                $sth->execute(array(
                    ':gebruiker_id' => $geleendeBoek['gebruiker_id'],
                    ':boek_id' => $geleendeBoek['boek_id'],
                    ':boete' => $boete
                ));

                $sql = "UPDATE gebruikers SET aantal_boetes = aantal_boetes + 1 WHERE id = :gebruiker_id";
                $sth = $dbh->prepare($sql);
                $sth->execute(array(':gebruiker_id' => $geleendeBoek['gebruiker_id']));

                $sql = "SELECT aantal_boetes FROM gebruikers WHERE id = :gebruiker_id";
                $sth = $dbh->prepare($sql);
                $sth->execute(array(':gebruiker_id' => $geleendeBoek['gebruiker_id']));

                $rsGebruiker = $sth->fetch();
                if ($rsGebruiker['aantal_boetes'] >= 2) {
                    $sql = "DELETE FROM gereserveerde_boeken WHERE gebruiker_id = :gebruiker_id";
                    $sth = $dbh->prepare($sql);
                    $sth->execute(array(':gebruiker_id' => $geleendeBoek['gebruiker_id']));
                }
            } elseif ($boete != $rsBoete['boete']) {
                // checkt of de value niet hetzelfde is.

                // Updates de boete van de row.
                $sql = "UPDATE boek_boetes SET boete = :boete WHERE gebruiker_id = :gebruiker_id AND boek_id = :boek_id";
                $sth = $dbh->prepare($sql);
                $sth->execute(array(
                    ':boete' => $boete,
                    ':gebruiker_id' => $geleendeBoek['gebruiker_id'],
                    ':boek_id' => $geleendeBoek['boek_id']
                ));
            }
        }
    }
}