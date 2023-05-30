<?php /** @noinspection HtmlUnknownTarget */
if (!isset($_SESSION['rol'])) {
    header('Location: ?page=login');
} elseif ($_SESSION['rol'] != 'admin') {
    header('Location: ?page=login');
}
?>
<div class="aanpassen_container">
    <?php
    require 'PHP/requires/connection.php';
    global $dbh;

    $sql = "SELECT id, naam, schrijver_id, genre_id, `isbn-nummer`, taal_id, `pagina's`, exemplaren, afbeelding FROM boeken WHERE naam = :naam";
    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(":naam" => $_GET['boek']));

    $result = $sth->fetch(PDO::FETCH_ASSOC);

    if (isset($_SESSION['aanpassenWarning'])) {
        echo "<p id='aanpassenWarning'>{$_SESSION['aanpassenWarning']}</p>";
        $_SESSION['aanpassenWarning'] = null;
    }

    require 'PHP/requires/boeken_sql.php';
    ?>
    <form action="PHP/aanpassen.php" method="POST" enctype="multipart/form-data">
        <label for="boeknaam">Naam</label><br>
        <input type="text" id="boeknaam" name="boeknaam">
        <input type="submit" value="Aanpassen" name="naam_but">
        <br><br><br>

        <label for="schrijver">Schrijver</label><br>
        <input list="schrijverList" type="text" id="schrijver" name="schrijver">
        <datalist id="schrijverList">
            <?php
            $sql = "SELECT voornaam, tussenvoegsel, achternaam FROM schrijvers WHERE registreerd = 1";
            $sth = $dbh->prepare($sql);
            $sth->execute();

            $schrijverRows = $sth->fetchAll();

            foreach ($schrijverRows as $row) {
                if ($row['tussenvoegsel'] === null) {
                    echo "<option value='" . $row['voornaam'] . " " . $row['achternaam'] . "'></option>";
                } else {
                    echo "<option value='" . $row['voornaam'] . " " . $row['tussenvoegsel'] .  " " . $row['achternaam'] . "'></option>";
                }
            }
            ?>
        </datalist>
        <input type="submit" value="Aanpassen" name="schrijver_but">
        <br><br><br>

        <label for="genres">Genre</label><br>
        <input list="genreList" type="text" name="genre" id="genres">
        <datalist id="genreList">
            <?php
            $sql = "SELECT genre FROM genres WHERE registreerd = 1";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
            $sth->execute();

            $genres = $sth->fetchAll();

            foreach ($genres as $genre) {
                echo "<option value='{$genre['genre']}'></option>";
            }
            ?>
        </datalist>
        <input type="submit" value="Aanpassen" name="genre_but">
        <br><br><br>

        <label for="isbn">ISBN-nummer</label><br>
        <input type="text" id="isbn" name="isbn">
        <input type="submit" value="Aanpassen" name="isbn_but">
        <br><br><br>

        <label for="taal">Taal</label><br>
        <input list="taalList" type="text" id="taal" name="taal">
        <datalist id="taalList">
            <?php
            $sql = "SELECT taal FROM talen WHERE registreerd = 1";
            $sth = $dbh->prepare($sql);
            $sth->execute();

            foreach ($sth->fetchAll() as $taal) {
                echo "<option value='{$taal['taal']}'></option>";
            }
            ?>
        </datalist>
        <input type="submit" value='Aanpassen' name="taal_but">
        <br><br><br>

        <label for="pagina">Aantal pagina's</label><br>
        <input type="text" id="pagina" name="pagina">
        <input type="submit" value="Aanpassen" name="pagina_but">
        <br><br><br>

        <label for="exemplaren">Aantal examplaren</label><br>
        <input type="number" id="exemplaren" name="exemplaren">
        <input type="submit" value="Aanpassen" name="exemplaren_but">
        <br><br><br>

        <input type="file" name="boekimage" accept="image/jpeg, image/png">
        <input type="submit" value="Aanpassen" name="image_but">
        <br><br><br>

        <label for="schrijvers">Schrijvers</label><br>
        <input type="text" id="schrijvers" name="schrijvers">
        <input type="submit" value="Aanpassen" name="schrijvers_but">
        <p>Om schrijvers aan te passen, zet een ';' achter elke schrijver met geen spatie erna in de veld
            'Schrijvers'. Anders kan de resultaat verkeerd eruit zien.</p>

        <input type="hidden" value="<?php echo $result['naam'] ?>" name="naam">
    </form>
</div>
<?php
if ($result['afbeelding']) {
    echo "<img class='aanpassenfoto_container' src='{$result['afbeelding']}' alt='boek'>";
}
?>
<div class="aanpasseninfo_container">
    <label>Naam</label>
    <p><?php echo $result['naam']; ?></p>

    <label>Schrijver</label>
    <p><?php echo getSchrijver($result['schrijver_id']); ?></p>

    <label>Genre</label>
    <p><?php echo getGenre($result['genre_id']); ?></p>

    <label>ISBN-nummer</label>
    <p><?php echo $result['isbn-nummer']; ?></p>

    <label>Taal</label>
    <p><?php echo getTaal($result['taal_id']); ?></p>

    <label>Aantal pagina's</label>
    <p><?php echo $result["pagina's"]; ?></p>

    <label>Aantal examplaren</label>
    <p><?php echo $result['exemplaren']; ?></p>

    <label>Schrijvers</label>
    <p><?php getSchrijvers($result['id']); ?></p>

    <form action="PHP/verwijderen.php" method="POST">
        <input type="submit" id="verwijderenButton" value="Verwijderen" name="verwijder_but">
        <input type="hidden" value="<?php echo $result['naam'] ?>" name="naam">
    </form>
</div>