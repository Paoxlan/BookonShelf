<?php /** @noinspection HtmlUnknownTarget */
?>
<div class="toevoegen_container">
    <?php
    if (isset($_SESSION['toevoegenWarningFoto'])) {
        echo "<p id='toevoegenWarning'>{$_SESSION['toevoegenWarningFoto']} Boek is alvast aangemaakt zo u kunt het daar aanpassen.</p>";
    }

    if (isset($_SESSION['toevoegenWarning'])) {
        echo "<p id='toevoegenWarning'>{$_SESSION['toevoegenWarning']}</p>";
    }

    require 'PHP/requires/connection.php';
    global $dbh;

    $_SESSION['toevoegenWarning'] = null;
    $_SESSION['toevoegenWarningFoto'] = null;
    ?>
    <form action="PHP/toevoegen.php" method="POST" enctype="multipart/form-data">
        <label for="boeknaam">Naam*</label><br>
        <input type="text" id="boeknaam" name="boeknaam" autocomplete="off">
        <br><br>

        <label for="schrijver">Schrijver*</label><br>
        <input list="schrijverList" type="text" id="schrijver" name="schrijver" autocomplete="off">
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
        <br><br>

        <label for="genre">Genre*</label><br>
        <input list="genreList" type="text" id="genre" name="genre" autocomplete="off">
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
        <br><br>

        <label for="isbn">ISBN-nummer*</label><br>
        <input type="text" id="isbn" name="isbn" autocomplete="off">
        <br><br>

        <label for="taal">Taal*</label><br>
        <input list="taalList" type="text" id="taal" name="taal" autocomplete="off">
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
        <br><br>

        <label for="pagina">Aantal pagina's*</label><br>
        <input type="text" id="pagina" name="pagina" autocomplete="off">
        <br><br>

        <label for="exemplaren">Aantal examplaren*</label><br>
        <input type="text" id="exemplaren" name="exemplaren" autocomplete="off">
        <br><br>

        <label for="schrijvers">Schrijvers</label><br>
        <input type="text" id="schrijvers" name="schrijvers" autocomplete="off">
        <p>Om meer schrijvers toe te voegen, zet een ';' achter elke schrijver met geen spatie erna in de veld 'Schrijvers'. Anders kan de resultaat verkeerd eruit zien.</p>

        <input type="file" name="boekimage">

        <button type="submit">Toevoegen</button>
    </form>
</div>