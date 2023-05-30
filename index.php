<?php /** @noinspection PhpIncludeInspection */
session_start();

$page = $_GET['page'] ?? 'login';

require 'PHP/requires/connection.php';
require 'PHP/requires/update_boetes.php';

updateBoetes();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>BookonShelf</title>

    <link rel="stylesheet" href="css/BookonShelfStyle.css">
</head>
<?php
if ($page == 'login' || $page == 'register') {
    echo '<body class="beginPage">';

    echo '<div class="container">';
    include 'includes/'. $page .'.inc.php';
    echo '</div>';

    echo '</body>';
} elseif ($page == 'overzicht' || $page == 'geleende_boeken' || $page == 'gereserveerde_boeken') {
    echo '<body>';

    if ($page == 'overzicht' && $_SESSION['rol'] == 'admin') {
        include 'includes/bar_admin.inc.php';
    } else {
        include 'includes/bar.inc.php';
    }
    echo '<div class="main">';

    include 'includes/'. $page .'.inc.php';

    echo '</div>';

    echo '</body>';
} elseif ($page == 'aanpassen' || $page == 'toevoegen' || $page == 'overzicht_admin' || $page == 'data_beheren' || $page == 'data_aanpassen') {
    if ($_SESSION['rol'] == 'admin') {
        echo '<body>';

        include 'includes/bar_admin.inc.php';
        include 'includes/'. $page .'.inc.php';

        echo '</body>';
    } else {
        header('Location: ?page=login');
    }
} elseif ($page == 'boekinfo') {
    echo '<body>';

    include 'includes/'. $page .'.inc.php';

    echo'</body>';
} else {
    echo '<body>';

    $succeeded = include 'includes/'. $page .'.inc.php';

    echo '</body>';

    if ($succeeded === false) {
        header('Location: ?page=login');
    }
}
?>
</html>