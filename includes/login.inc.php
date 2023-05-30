<?php /** @noinspection HtmlUnknownTarget */
if (!isset($_SESSION['login_success'])) {
    $_SESSION['login_success'] = true;
}

if (!$_SESSION['login_success']) {
    echo '<div class="loginfailed" id="shadowBox">';
    echo '<p>Inloggen niet gelukt: verkeerde gegevens gebruikt.</p>';
    echo '</div>';
}
?>

<div class="login" id="shadowBox">
    <br>
    <h1>BookonShelf</h1>

    <div class="loginBoxes">
        <form action="PHP/login.php" method="POST" id="loginForm">
            <label for="email">Email</label><br><br>
            <input type="email" class="logininput" id="email" name="email" autocomplete="off"><br><br><br>

            <label for="wachtwoord">Wachtwoord</label><br><br>
            <input type="password" class="logininput" id="wachtwoord" name="wachtwoord"><br><br><br>
        </form>

        <button class="loginButton" type="submit" form="loginForm">Inloggen</button>
    </div>
    <div class="loginBttnContainer">
        <br>
        <a href="?page=register">Geen account?</a>
    </div>
</div>