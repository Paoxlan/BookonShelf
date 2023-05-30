<?php /** @noinspection HtmlUnknownTarget */

$navItems = array(
    'Boeken' => 'overzicht',
    'Boek toevoegen' => 'toevoegen',
    'Data beheren' => 'data_beheren',
    'Uitloggen' => 'login'
);

echo '<div class="sidebar">';

foreach ($navItems as $label => $link) {
    echo '<a href="?page='. $link .'">'. $label .'</a>';
}

echo '<img class="sidebarphoto_admin" src="images/BookonShelf_Logo.png" alt="BookonShelf">';

echo '</div>';