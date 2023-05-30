<?php /** @noinspection HtmlUnknownTarget */
if (!isset($_SESSION['rol'])) {
    header('Location: ?page=login');
}

if ($_SESSION['rol'] == 'admin') {
    $buttonLen = '<button class="aanpassen" type="submit">Aanpassen</button>';
    $buttonRes = $buttonLen;
} else {
    $buttonLen = '<button class="lenen" type="submit">Lenen</button>';
    $buttonRes = '<button class="reserveren" type="submit">Reserveren</button>';
}

$totalGeleendBoeken = 0;

$src = "images/BookonShelf_Logo.png";

require 'PHP/requires/connection.php';
global $dbh;

$sql = 'SELECT naam, schrijver_id, genre_id, `isbn-nummer`, taal_id, aantal_exemplaren, afbeelding FROM boeken';
$query = $dbh->prepare($sql);
$query->execute();

$result = $query->fetchAll();

require 'PHP/requires/boeken_sql.php';
require 'PHP/requires/update_overzicht.php';
?>

<div class="flexcontainer">
    <?php
    foreach ($result as $row) {
        echo '<div>';

        if (!empty($row['afbeelding'])) {
            echo "<img src='{$row['afbeelding']}' alt='boek'>";
        } else {
            echo "<img src='images/Placeholder.png' alt='boek'>";
        }

        echo '<ul>';

        echo "<li><a href='?page=boekinfo&boek={$row['naam']}'>Naam: {$row['naam']}</a></li>";
        echo "<li>Schrijver: " . getSchrijver($row['schrijver_id']) . "</li>";
        echo "<li>Genre: " . getGenre($row['genre_id']) . "</li>";
        echo "<li>ISBN-nummer: {$row['isbn-nummer']}</li>";
        echo "<li>Taal: " . getTaal($row['taal_id']) . "</li><br>";

        if ($_SESSION['rol'] == 'admin') {
            echo "<li><form><input type='hidden' name='page' value='aanpassen'><input type='hidden' name='boek' value='{$row['naam']}'>$buttonLen</form></li>";
        } else {
            $sql = "SELECT t1.id gebruiker_id, t2.id boek_id FROM gebruikers t1 INNER JOIN boeken t2 ON t2.id WHERE t1.email = :email AND t2.naam = :boeknaam";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(
                ':email' => $_SESSION['email'],
                ':boeknaam' => $row['naam']
            ));

            $rsGebruikerBoek = $sth->fetch(PDO::FETCH_ASSOC);

            $sql = "SELECT id FROM geleende_boeken WHERE gebruiker_id = :gebruiker_id AND boek_id = :boek_id";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(
                ':gebruiker_id' => $rsGebruikerBoek["gebruiker_id"],
                ':boek_id' => $rsGebruikerBoek["boek_id"]
            ));

            if (!($rsFoundLenen = $sth->fetch(PDO::FETCH_ASSOC))) {
                $sql = "SELECT aantal_boetes FROM gebruikers WHERE id = :gebruiker_id";
                $sth = $dbh->prepare($sql);
                $sth->execute(array(':gebruiker_id' => $rsGebruikerBoek['gebruiker_id']));

                $rsBoetes = $sth->fetch();

                if ($rsBoetes['aantal_boetes'] < 2) {
                    if ($row['aantal_exemplaren'] <= 0) {
                        $sql = "SELECT id FROM gereserveerde_boeken WHERE gebruiker_id = :gebruiker_id AND boek_id = :boek_id";
                        $sth = $dbh->prepare($sql);
                        $sth->execute(array(
                            ':gebruiker_id' => $rsGebruikerBoek["gebruiker_id"],
                            ':boek_id' => $rsGebruikerBoek["boek_id"]
                        ));

                        if ($rsFoundReserveren = $sth->fetch(PDO::FETCH_ASSOC)) {
                            echo "<li><button class='onbeschikbaar'>Gereserveerd</button></li>";
                        } else {
                            $sql = "SELECT id FROM geleende_boeken WHERE gebruiker_id = :gebruiker_id";
                            $sth = $dbh->prepare($sql);
                            $sth->execute(array(':gebruiker_id' => $rsGebruikerBoek['gebruiker_id']));

                            if (!(($rsAantalBoeken = $sth->rowCount()) == 3)) {
                                echo "<li><form action='PHP/reserveren.php' method='POST'><input type='hidden' name='boek' value='{$row['naam']}'>$buttonRes</form></li>";
                            } else {
                                echo "<li><button class='onbeschikbaar'>Maximaal bereikt</button></li>";
                            }
                        }
                    } else {
                        $sql = "SELECT id FROM boek_boetes WHERE gebruiker_id = :gebruiker_id AND boek_id = :boek_id";
                        $sth = $dbh->prepare($sql);
                        $sth->execute(array(
                            ':gebruiker_id' => $rsGebruikerBoek['gebruiker_id'],
                            ':boek_id' => $rsGebruikerBoek['boek_id']
                        ));
                        if ($rsBoekBoete = $sth->fetch()) {
                            echo "<li><button class='onbeschikbaar'>U moet nog de boete betalen.</button>";
                        } else {
                            echo "<li><form action='PHP/lenen.php' method='POST'><input type='hidden' name='boek' value='{$row['naam']}'>$buttonLen</form></li>";
                        }
                    }
                } else {
                    echo "<li><button class='onbeschikbaar'>Onbeschikbaar tot boetes zijn betaald.</button></li>";
                }
            } else {
                echo "<li><button class='onbeschikbaar'>Geleend</button></li>";
                $totalGeleendBoeken++;
            }
        }

        echo '</ul>';

        echo '</div>';
    }

    $_SESSION['totaalGeleendBoeken'] = $totalGeleendBoeken;
    ?>
</div>