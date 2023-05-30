<?php /** @noinspection HtmlUnknownTarget */

$navItems = array(
    'Overzicht' => 'overzicht',
    'Geleende boeken' => 'geleende_boeken',
    "Gereservee-\nrde boeken" => 'gereserveerde_boeken',
    "Openstaan-\nde boetes" => 'openstaande_boetes',
    'Uitloggen' => 'login'
);

echo '<div class="sidebar">';

foreach ($navItems as $label => $link) {
    echo '<a href="?page='. $link .'">'. $label .'</a>';
}

echo '<img class="sidebarphoto" src="images/BookonShelf_Logo.png" alt="BookonShelf">';

echo '</div>';

$page = $_GET['page'] ?? '';

$htmltext = '';

switch ($page) {
    case 'overzicht':
        $htmltext = 'Klik op de naam van een boek om meer informatie te krijgen.';
        break;
    case 'gereserveerde_boeken':
    case 'geleende_boeken':
        $htmltext = 'U kunt maximaal 3 boeken lenen en reserveren.';
        break;
    case 'openstaande_boetes':
        $htmltext = 'Hier ziet u uw openstaande boetes. Let op! Een boek terugbrengen waar u een boete voor heb, betekent niet dat de boete weg gaat.';
        break;
}

if (!empty($htmltext)) {
    echo '<div class="topbar"><p>'. $htmltext .'</p></div>';
}