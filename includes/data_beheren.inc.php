<?php /** @noinspection HtmlUnknownTarget */
if (isset($_SESSION['rol'])) {
    if (!$_SESSION['rol'] == 'admin') {
        header('Location: ?page=login');
    }
} else {
    header('Location: ?page=login');
}
$keuze = "";

if (isset($_GET['keuze'])) {
    $keuze = $_GET['keuze'];
}

require 'PHP/requires/connection.php';
global $dbh;
?>
<div class="main">
    <?php
    if (isset($_SESSION['data_warning'])) {
        echo "<br><label class='alignCenter' id='data_warning'>{$_SESSION['data_warning']}</label>";
        $_SESSION['data_warning'] = null;
    }
    ?>
    <form>
        <br>
        <input type="hidden" name="page" value="data_beheren">
        <label for="choiceList" class="alignCenter">Wat wilt u beheren: </label>
        <select id="choiceList" name="keuze" onchange="this.form.submit()" class="alignCenter">
            <?php
            $options = array(
                "",
                "genres",
                "schrijvers",
                "talen",
                "gebruikers",
                "boetes"
            );

            foreach ($options as $option) {
                if ($option == $_GET['keuze']) {
                    echo "<option value='$option' selected>$option</option>";
                } else {
                    echo "<option value='$option'>$option</option>";
                }
            }
            ?>
        </select>
    </form>
    <br>
    <form action="PHP/data_toevoegen.php" method="POST">
        <?php
        if ($keuze === "genres") {
            ?>
            <label for="genre" class="alignCenter">Genre</label>
            <input type="text" name="genre" id="genre" required class="alignCenter">
            <br>
            <label for="registerd" class="alignCenter">Registreerd</label>
            <input type="checkbox" name="registerd" id="registerd" class="alignCenter">
            <br>
            <button type="submit" name="genreButton" class="alignCenterButton">Toevoegen</button>
            <?php
        }
        if ($keuze === "schrijvers") {
            ?>
            <label for="voornaam" class="alignCenter">Voornaam</label>
            <input type="text" name="voornaam" id="voornaam" required class="alignCenter">
            <br>
            <label for="tussenvoegsel" class="alignCenter">Tussenvoegsel</label>
            <input type="text" name="tussenvoegsel" id="tussenvoegsel" class="alignCenter">
            <br>
            <label for="achternaam" class="alignCenter">Achternaam</label>
            <input type="text" name="achternaam" id="achternaam" required class="alignCenter">
            <br>
            <label for="registerd" class="alignCenter">Registreerd</label>
            <input type="checkbox" name="registerd" id="registerd" class="alignCenter">
            <br>
            <button type="submit" name="schrijverButton" class="alignCenterButton">Toevoegen</button>
            <?php
        }
        if ($keuze === "talen") {
            ?>
            <label for="taal" class="alignCenter">Taal</label>
            <input type="text" name="taal" id="taal" required class="alignCenter">
            <br>
            <label for="registerd" class="alignCenter">Registreerd</label>
            <input type="checkbox" name="registerd" id="registerd" class="alignCenter">
            <br>
            <button type="submit" name="taalButton" class="alignCenterButton">Toevoegen</button>
            <?php
        }
        if ($keuze === "gebruikers") {
            ?>
            <div class="dataToevoegenContainerLeft">
                <label for="voornaam">Voornaam*</label>
                <br>
                <input type="text" id="voornaam" name="voornaam" required><br><br>

                <label for="tussenvoegsel">Tussenvoegsel</label>
                <br>
                <input type="text" id="tussenvoegsel" name="tussenvoegsel"><br><br>

                <label for="achternaam">Achternaam*</label>
                <br>
                <input type="text" id="achternaam" name="achternaam" required><br><br>

                <label for="woonplaats">Woonplaats*</label>
                <br>
                <input type="text" id="woonplaats" name="woonplaats" required><br><br>

                <label for="straat">Straat*</label>
                <br>
                <input type="text" id="straat" name="straat" required>
            </div>
            <div class="dataToevoegenContainerRight">
                <label for="huisnummer">Huisnummer*</label>
                <br>
                <input type="text" id="huisnummer" name="huisnummer" required><br><br>

                <label for="postcode">Postcode*</label>
                <br>
                <input type="text" id="postcode" name="postcode" required><br><br>

                <label for="email">Email*</label>
                <br>
                <input type="email" id="email" name="email" required><br><br>

                <label for="wachtwoord">Wachtwoord*</label>
                <br>
                <input type="password" id="wachtwoord" name="wachtwoord" required><br><br>

                <label for="geboortedatum">Geboortedatum*</label>
                <br>
                <input type="date" id="geboortedatum" name="geboortedatum" required>
            </div>
            <br>
            <label for="rol" class="alignCenterSelect">Rol</label>
            <br>
            <select name="rol" id="rol" class="alignCenterSelect">
                <option value="klant">Klant</option>
                <option value="admin">Admin</option>
            </select>
            <br><br>
            <button type="submit" name="gebruikerButton" class="alignCenterButton">Toevoegen</button>
            <?php
        }
        ?>
    </form>
    <table class="tableStyle">
        <tr>
            <?php
            $isOk = false;
            foreach ($options as $option) {
                if ($option === $keuze) {
                    $isOk = true;
                }
            }
            if ($keuze !== "" && $isOk) {
                if ($keuze !== "boetes") {
                    echo "<th class='idTable'>ID</th>";
                }

                if ($keuze === "genres") {
                    echo "<th>Genre</th>";

                    $sql = "SELECT * FROM genres";
                } elseif ($keuze === "schrijvers") {
                    echo "<th>Voornaam</th>";
                    echo "<th>Tussenvoegsel</th>";
                    echo "<th>Achternaam</th>";

                    $sql = "SELECT * FROM schrijvers";
                } elseif ($keuze === "talen") {
                    echo "<th>Taal</th>";

                    $sql = "SELECT * FROM talen";
                } elseif ($keuze === "gebruikers") {
                    echo "<th>Email</th>";
                    echo "<th>Voornaam</th>";
                    echo "<th>Tussenvoegsel</th>";
                    echo "<th>Achternaam</th>";
                    echo "<th>Rol</th>";

                    $sql = "SELECT g.id, email, voornaam, tussenvoegsel, achternaam, rol FROM gebruikers g INNER JOIN rollen r ON g.rol_id = r.id";
                } else {
                    echo "<th>Email</th>";
                    echo "<th>Voornaam</th>";
                    echo "<th>Tussenvoegsel</th>";
                    echo "<th>Achternaam</th>";
                    echo "<th>Boek</th>";
                    echo "<th class='slimmerth'>Boete</th>";
                    echo "<th>Afbetaald</th>";

                    $sql = "SELECT bb.id, email, voornaam, tussenvoegsel, achternaam, naam, boete, afbetaald FROM gebruikers g INNER JOIN boek_boetes bb on g.id = bb.gebruiker_id INNER JOIN boeken b on bb.boek_id = b.id";
                }
                $sth = $dbh->prepare($sql);
                $sth->execute();

                if ($keuze !== "gebruikers" && $keuze !== "boetes") {
                    echo "<th class='registeredTable'>Geregistreerd</th>";
                }

                if ($keuze !== "boetes") {
                    echo "<th class='slimmerth'></th>";
                    echo "<th class='slimmerth'></th>";
                }

                echo "<tr>";

                foreach ($sth->fetchAll() as $row) {
                    echo "<tr>";

                    if ($keuze !== "boetes") {
                        echo "<td class='idTable'>{$row['id']}</td>";
                    }

                    if ($keuze === "genres") {
                        echo "<td>{$row['genre']}</td>";
                    } elseif ($keuze === "schrijvers") {
                        echo "<td>{$row['voornaam']}</td>";
                        echo "<td>{$row['tussenvoegsel']}</td>";
                        echo "<td>{$row['achternaam']}</td>";
                    } elseif ($keuze === "talen") {
                        echo "<td>{$row['taal']}</td>";
                    } elseif ($keuze === "gebruikers") {
                        echo "<td>{$row['email']}</td>";
                        echo "<td>{$row['voornaam']}</td>";
                        echo "<td>{$row['tussenvoegsel']}</td>";
                        echo "<td>{$row['achternaam']}</td>";
                        echo "<td>{$row['rol']}</td>";
                    } else {
                        echo "<td>{$row['email']}</td>";
                        echo "<td>{$row['voornaam']}</td>";
                        echo "<td>{$row['tussenvoegsel']}</td>";
                        echo "<td>{$row['achternaam']}</td>";
                        echo "<td>{$row['naam']}";
                        $boete = number_format($row['boete'], 2, ',');
                        echo "<td>€ $boete";

                        $afbetaald = "Nee";
                        if ($row['afbetaald'] === 1) {
                            $afbetaald = "Ja";
                        }
                        echo "<td>$afbetaald</td>";
                    }

                    if ($keuze !== "gebruikers" && $keuze !== "boetes") {
                        if ($row['registreerd'] === 0) {
                            echo "<td>Nee</td>";
                        } else {
                            echo "<td>Ja</td>";
                        }
                    }

                    if ($keuze !== "boetes") {
                        echo "<td><form><input type='hidden' name='page' value='data_aanpassen'><input type='hidden' name='id' value='{$row['id']}'><input type='hidden' name='keuze' value='$keuze'><button class='aanpassenTable'>Aanpassen</button></form></td>";

                        echo "<td><form action='PHP/data_verwijderen.php' method='POST'><input type='hidden' name='id' value='{$row['id']}'><button type='submit' name='{$keuze}Button' class='verwijderenTable'>Verwijderen</button></form></td>";
                        echo "</tr>";
                    } else {
                        if ($row['afbetaald'] === 1) {
                            echo "<td><form action='PHP/afbetalen.php' method='POST'><input type='hidden' name='id' value='{$row['id']}'><button type='submit' name='boeteSucButton' class='afbetalenTable'>Afbetaald</button></form></td>";

                            echo "<td><form action='PHP/afbetalen.php' method='POST'><input type='hidden' name='id' value='{$row['id']}'><button type='submit' name='boeteNAfButton' class='verwijderenTable'>Niet Afbetaald</button></form></td>";
                        }
                    }
                }
            } ?>
    </table>
</div>