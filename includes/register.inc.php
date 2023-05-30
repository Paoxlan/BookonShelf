<?php /** @noinspection HtmlUnknownTarget */
if (!isset($_SESSION['rws'])) {
    $_SESSION['rws'] = false;
}
?>

<div class="register" id="shadowBox">
    <h1>BookonShelf</h1>
    <?php
    if ($_SESSION['rws']) {
        echo '<p id="registerWarningShow">'. $_SESSION['message'] .'</p>';
    } else {
        echo '<p id="registerWarning">Velden met * moeten ingevuld worden.</p>';
    }
    ?>
    <form action="PHP/registreren.php" method="POST" id="registerform">
        <div class="registerLeft">
            <label for="fname">Voornaam*</label>
            <input type="text" id="fname" name="fname"><br><br><br>

            <label for="tssnvoegsel">Tussenvoegsel</label>
            <input type="text" id="tssnvoegsel" name="tssnvoegsel"><br><br><br>

            <label for="lname">Achternaam*</label>
            <input type="text" id="lname" name="lname"><br><br><br>

            <label for="woonplaats">Woonplaats*</label>
            <input type="text" id="woonplaats" name="woonplaats"><br><br><br>

            <label for="straat">Straat*</label>
            <input type="text" id="straat" name="straat">
        </div>
        <div class="registerRight">
            <label for="huisnummer">Huisnummer*</label>
            <input type="text" id="huisnummer" name="huisnummer"><br><br><br>

            <label for="postcode">Postcode*</label>
            <input type="text" id="postcode" name="postcode"><br><br><br>

            <label for="email">Email*</label>
            <input type="email" id="email" name="email"><br><br><br>

            <label for="wachtwoord">Wachtwoord*</label>
            <input type="password" id="wachtwoord" name="wachtwoord"><br><br><br>

            <label for="geboortedatum">Geboortedatum*</label>
            <input type="date" id="geboortedatum" name="geboortedatum">
        </div>
        <div class="registerBttnContainer">
            <button class="registerButton" type="submit" form="registerform">Registreren</button>
        </div>
    </form>
</div>