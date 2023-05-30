<?php
if ($_SESSION['rol'] == 'admin') {
    include 'includes/bar_admin.inc.php';
} else {
    include 'includes/bar.inc.php';
}

require 'PHP/requires/connection.php';
global $dbh;

$sql = "SELECT id, naam, schrijver_id, genre_id, `isbn-nummer`, taal_id, `pagina's`, exemplaren, afbeelding FROM boeken WHERE naam = :naam";
$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute(array(":naam" => $_GET['boek']));

$result = $sth->fetch(PDO::FETCH_ASSOC);

require 'PHP/requires/boeken_sql.php';
?>

<div class="informatiepage">
    <div class="textbox">
        <br><br><br>
        <p>Naam: <?php echo $result['naam']; ?></p>

        <p>Schrijver: <?php echo getSchrijver($result['schrijver_id']); ?></p>

        <p>Genre: <?php echo getGenre($result['genre_id']); ?></p>

        <p>ISBN-nummer: <?php echo $result['isbn-nummer']; ?></p>

        <p>Taal: <?php echo getTaal($result['taal_id']); ?></p>

        <p>Aantal pagina's: <?php echo $result["pagina's"]; ?></p>

        <p>Aantal exemplaren: <?php echo $result['exemplaren']; ?></p>

        <p>Meerdere schrijvers: <?php getSchrijvers($result['id']); ?></p>
    </div>
</div>
<div>
    <?php if ($result['afbeelding']) {
        echo "<img src='{$result['afbeelding']}' class='informatiepage_image' alt='boek'>";
    } ?>
</div>