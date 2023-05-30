<?php /** @noinspection HtmlUnknownTarget */
if (!isset($_SESSION['rol'])) {
    header('Location: ?page=login');
}
require 'PHP/requires/connection.php';
global $dbh;

$buttonTer = '<button class="terugbrengen" type="submit">Terugbrengen</button>';

$sql = "SELECT b.*, gb.begindatum FROM boeken b INNER JOIN gebruikers g ON g.email = :email INNER JOIN geleende_boeken gb ON gb.gebruiker_id = g.id WHERE b.id = gb.boek_id";
$sth = $dbh->prepare($sql);
$sth->execute(array(':email' => $_SESSION['email']));

$result = $sth->fetchAll();

require 'PHP/requires/boeken_sql.php';
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

        echo "<li><a href='?page=boekinfo&boek={$row['naam']}'>Naam: {$row['naam']}</a>";
        echo "<li>Schrijver: " . getSchrijver($row['schrijver_id']) . "</li>";
        echo "<li>Genre: " . getGenre($row['genre_id']) . "</li>";
        echo "<li>ISBN-nummer: {$row['isbn-nummer']}</li>";
        echo "<li>Taal: " . getTaal($row['taal_id']) . "</li>";

        echo "<li><form action='PHP/terugbrengen.php' method='POST'><input type='hidden' name='boek' value='{$row['naam']}'>$buttonTer</form></li>";

        $beginTime = strtotime($row['begindatum']);
        $calcDate = $beginTime + (21 * 60 * 60 * 24);

        $opleverDate = date('d-m-y', $calcDate);

        $beginDate = date('d-m-y', $beginTime);

        echo "<li>Geleend in: $beginDate<br>Terugbrengen voor: $opleverDate</li>";

        echo '</ul>';
        echo '</div>';
    }
    ?>
</div>