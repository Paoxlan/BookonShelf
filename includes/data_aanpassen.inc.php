<?php /** @noinspection HtmlUnknownTarget */
if (!isset($_SESSION['rol'])) {
    header('Location: ?page=login');
    exit;
} elseif ($_SESSION['rol'] != 'admin') {
    header('Location: ?page=login');
    exit;
}

$id = $_GET['id'] ?? null;

$voornaam = null;
$tussenvoegsel = null;
$achternaam = null;

if ($id === null) {
    header('Location: ?page=overzicht');
    exit;
}

require 'PHP/requires/connection.php';
global $dbh;
?>
<div class="aanpassen_container">
    <?php
    if (isset($_SESSION['aanpassenWarning'])) {
        echo "<p id='aanpassenWarning'>{$_SESSION['aanpassenWarning']}</p>";
        $_SESSION['aanpassenWarning'] = null;
    }
    ?>
    <form action="PHP/data_aanpassen.php" method="POST">
        <?php
        if ($_GET['keuze'] === 'genres') {
            ?>
            <label for="genre">Genre</label>
            <br>
            <input list="genreList" type="text" name="genre" id="genre">
            <datalist id="genreList">
                <?php
                $sql = "SELECT genre FROM genres WHERE registreerd = 1";
                $sth = $dbh->prepare($sql);
                $sth->execute();

                foreach ($sth->fetchAll() as $row) {
                    echo "<option value='{$row['genre']}'></option>";
                }
                ?>
            </datalist>
            <input type="submit" value="Aanpassen" name="genreButton">
            <br><br><br>

            <label for="genreReg">Registreerd</label>
            <br>
            <input type="checkbox" name="genreReg" id="genreReg">
            <input type="submit" value="Aanpassen" name="genreRegButton">

            <input type="hidden" name="keuze" value="genres">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <?php
        }
        if ($_GET['keuze'] === 'schrijvers') {
            ?>
            <label for="voornaam">Voornaam</label>
            <br>
            <input type="text" name="voornaam" id="voornaam">
            <input type="submit" value="Aanpassen" name="voornaamSchButton">
            <br><br><br>

            <label for="tussenvoegsel">tussenvoegsel</label>
            <br>
            <input type="text" name="tussenvoegsel" id="tussenvoegsel">
            <input type="submit" value="Aanpassen" name="tussenvoegselSchButton">
            <br><br><br>

            <label for="achternaam">Achternaam</label>
            <br>
            <input type="text" name="achternaam" id="achternaam">
            <input type="submit" value="Aanpassen" name="achternaamSchButton">
            <br><br><br>

            <label for="schrijverReg">Registreerd</label>
            <br>
            <input type="checkbox" name="schrijverReg" id="schrijverReg">
            <input type="submit" value="Aanpassen" name="schrijverRegButton">

            <input type="hidden" name="keuze" value="schrijvers">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <?php
        }
        if ($_GET['keuze'] === 'talen') {
            ?>
            <label for="taal">Taal</label>
            <br>
            <input list="taalList" type="text" name="taal" id="taal">
            <datalist id="taalList">
                <?php
                $sql = "SELECT taal FROM talen WHERE registreerd = 1";
                $sth = $dbh->prepare($sql);
                $sth->execute();

                foreach ($sth->fetchAll() as $row) {
                    echo "<option value='{$row['taal']}'></option>";
                }
                ?>
            </datalist>
            <input type="submit" value="Aanpassen" name="taalButton">
            <br><br><br>

            <label for="taalReg">Registreerd</label>
            <br>
            <input type="checkbox" name="taalReg" id="taalReg">
            <input type="submit" value="Aanpassen" name="taalRegButton">

            <input type="hidden" name="keuze" value="talen">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <?php
        }
        if ($_GET['keuze'] === 'gebruikers') {
            ?>
            <label for="voornaam">Voornaam</label>
            <br>
            <input type="text" name="voornaam" id="voornaam">
            <input type="submit" value="Aanpassen" name="voornaamGebButton">
            <br><br><br>

            <label for="tussenvoegsel">Tussenvoegsel</label>
            <br>
            <input type="text" name="tussenvoegsel" id="tussenvoegsel">
            <input type="submit" value="Aanpassen" name="tussenvoegselGebButton">
            <br><br><br>

            <label for="achternaam">Achternaam</label>
            <br>
            <input type="text" name="achternaam" id="achternaam">
            <input type="submit" value="Aanpassen" name="achternaamGebButton">
            <br><br><br>

            <label for="woonplaats">Woonplaats</label>
            <br>
            <input type="text" name="woonplaats" id="woonplaats">
            <input type="submit" value="Aanpassen" name="woonplaatsButton">
            <br><br><br>

            <label for="straat">Straat</label>
            <br>
            <input type="text" name="straat" id="straat">
            <input type="submit" value="Aanpassen" name="straatButton">
            <br><br><br>

            <label for="huisnummer">Huisnummer</label>
            <br>
            <input type="text" name="huisnummer" id="huisnummer">
            <input type="submit" value="Aanpassen" name="huisnummerButton">
            <br><br><br>

            <label for="postcode">Postcode</label>
            <br>
            <input type="text" name="postcode" id="postcode">
            <input type="submit" value="Aanpassen" name="postcodeButton">
            <br><br><br>

            <label for="email">Email</label>
            <br>
            <input type="text" name="email" id="email">
            <input type="submit" value="Aanpassen" name="emailButton">
            <br><br><br>

            <label for="wachtwoord">Wachtwoord</label>
            <br>
            <input type="password" name="wachtwoord" id="wachtwoord">
            <input type="submit" value="Aanpassen" name="wachtwoordButton">
            <br><br><br>

            <label for="geboortedatum">Geboortedatum</label>
            <br>
            <input type="date" name="geboortedatum" id="geboortedatum">
            <input type="submit" value="Aanpassen" name="geboortedatumButton">
            <br><br><br>

            <label for="rol">Rol</label>
            <br>
            <select name="rol" id="rol">
                <option value="klant">Klant</option>
                <option value="admin">Admin</option>
            </select>
            <input type="submit" value="Aanpassen" name="rolButton">
            <br><br><br>

            <input type="hidden" name="keuze" value="gebruikers">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <?php
        }
        ?>
    </form>
</div>
<div class="aanpasseninfo_container">
    <?php
    if ($_GET['keuze'] === 'genres') {
        $sql = "SELECT genre, registreerd FROM genres WHERE id = :id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array('id' => $id));

        $info = $sth->fetch();
        ?>
        <label>Genre</label>
        <p><?php echo $info['genre']; ?></p>

        <label>Registreerd</label>
        <p><?php
            if ($info['registreerd'] === 0) {
                echo "Nee";
            } else {
                echo "Ja";
            }
            ?></p>
        <?php
    }
    if ($_GET['keuze'] === 'schrijvers') {
        $sql = "SELECT voornaam, tussenvoegsel, achternaam, registreerd FROM schrijvers WHERE id = :id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array('id' => $id));

        $info = $sth->fetch();

        $voornaam = $info['voornaam'];
        $tussenvoegsel = $info['tussenvoegsel'];
        $achternaam = $info['achternaam'];
        ?>
        <label>Voornaam</label>
        <p><?php echo $info['voornaam']; ?></p>

        <label>Tussenvoegsel</label>
        <p><?php
            if ($info['tussenvoegsel'] === null || $info['tussenvoegsel'] === '') {
                echo "-";
            } else {
                echo "{$info['tussenvoegsel']}";
            } ?></p>

        <label>Achternaam</label>
        <p><?php echo $info['achternaam']; ?></p>

        <label>Registreerd</label>
        <p><?php
            if ($info['registreerd'] === 0) {
                echo "Nee";
            } else {
                echo "Ja";
            }
            ?></p>
        <?php
    }
    if ($_GET['keuze'] === 'talen') {
        $sql = "SELECT taal, registreerd FROM talen WHERE id = :id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array('id' => $id));

        $info = $sth->fetch();
        ?>
        <label>Taal</label>
        <p><?php echo $info['taal']; ?></p>

        <label>Registreerd</label>
        <p><?php
            if ($info['registreerd'] === 0) {
                echo "Nee";
            } else {
                echo "Ja";
            }
            ?></p>
        <?php
    }
    if ($_GET['keuze'] === 'gebruikers') {
        $sql = "SELECT email, rol, voornaam, tussenvoegsel, achternaam, woonplaats, straat, huisnummer, postcode, geboortedatum FROM gebruikers g INNER JOIN rollen r ON g.rol_id = r.id INNER JOIN woonplaats w on g.woonplaats_id = w.id WHERE g.id = :id";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(':id' => $id));

        $info = $sth->fetch();

        $voornaam = $info['voornaam'];
        $tussenvoegsel = $info['tussenvoegsel'];
        $achternaam = $info['achternaam'];
        ?>
        <label>Voornaam</label>
        <p><?php echo $info['voornaam']; ?></p>

        <label>Tussenvoegsel</label>
        <p><?php if ($info['tussenvoegsel'] === null || $info['tussenvoegsel'] === '') {
                echo "-";
            } else {
                echo "{$info['tussenvoegsel']}";
            } ?></p>

        <label>Achternaam</label>
        <p><?php echo $info['achternaam']; ?></p>

        <label>Woonplaats</label>
        <p><?php echo $info['woonplaats']; ?></p>

        <label>Straat</label>
        <p><?php echo $info['straat']; ?></p>

        <label>Huisnummer</label>
        <p><?php echo $info['huisnummer']; ?></p>

        <label>Postcode</label>
        <p><?php echo $info['postcode']; ?></p>

        <label>Email</label>
        <p><?php echo $info['email']; ?></p>

        <label>Wachtwoord</label>
        <p>***.. (privé)</p>

        <label>Geboortedatum</label>
        <p><?php
            $time = strtotime($info['geboortedatum']);
            echo date('d-m-Y', $time);
            ?></p>
        <label>Rol</label>
        <p><?php echo $info['rol'] ?></p>
        <?php
    }
    ?>
</div>