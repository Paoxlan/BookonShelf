<?php
include 'includes/bar.inc.php';

require 'PHP/requires/connection.php';
global $dbh;

$sql = "SELECT id FROM gebruikers WHERE email = :email";
$sth = $dbh->prepare($sql);
$sth->execute(array(':email' => $_SESSION['email']));

$rsGebruiker = $sth->fetch();

$sql = "SELECT boete FROM boek_boetes WHERE gebruiker_id = :gebruiker_id";
$sth = $dbh->prepare($sql);
$sth->execute(array(':gebruiker_id' => $rsGebruiker['id']));

$totalBoete = 0;
?>
<div class="main">
    <table class="tableStyle">
        <tr>
            <th>Boek</th>
            <th>Teruggebracht</th>
            <th class="slimmerth">Boete</th>
            <th class="slimmerth"></th>
        </tr>
        <?php
        global $dbh;
        // krijgt informatie uit de boetes en de naam van de boek.
        $sql = "SELECT bb.boek_id, b.naam boeknaam, bb.boete, bb.afbetaald FROM boeken b INNER JOIN boek_boetes bb ON b.id = bb.boek_id WHERE bb.gebruiker_id = :gebruiker_id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(':gebruiker_id' => $rsGebruiker['id']));
        // alle rows
        $rsBoetes = $sth->fetchAll();
        foreach ($rsBoetes as $row) {
            $sql = "SELECT id FROM geleende_boeken WHERE gebruiker_id = :gebruiker_id AND boek_id = :boek_id";
            $sth = $dbh->prepare($sql);
            $sth->execute(array(
                ':gebruiker_id' => $rsGebruiker['id'],
                ':boek_id' => $row['boek_id']
            ));


            echo "<tr>";

            echo "<td><a href='?page=boekinfo&boek={$row['boeknaam']}'>{$row['boeknaam']}</a></td>";
            if ($rsBoek = $sth->fetch()) {
                echo "<td id='statusBad'>Niet teruggebracht!</td>";
            } else {
                echo "<td id='statusGood'>Teruggebracht.</td>";
            }
            $boete = number_format($row['boete'], 2, ',');
            echo "<td id='statusBad'>€ $boete</td>";
            $totalBoete += $row['boete'];

            if ($rsBoek) {
                echo "<td><button id='onbeschikbaarBttnTable'>Boek eerst terugbrengen</button></td>";
            } else {
                if ($row['afbetaald'] === 1) {
                    echo "<td><button id='onbeschikbaarBttnTable'>Onder review.</button></td>";
                } else {
                    echo "<td><form action='PHP/afbetalen.php' method='POST'><input type='hidden' name='boek' value='{$row['boeknaam']}'><button id='afbetalenButton' name='afbetalenButton' type='submit'>Afbetalen</button></form></td>";
                }
            }

            echo "</tr>";
        }
        ?>
    </table>
    <?php
    $totalBoete = number_format($totalBoete, 2, ',');
    echo "<h1>Totale boete:<br>€ $totalBoete</h1>";
    ?>
</div>
