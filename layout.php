<?php
session_start();
$homeUrl = "projekt/";
// require --> PHP sprawdzi, czy plik został już dołączony, a jeśli tak, to nie dołącza (nie wymaga) go ponownie.
require('database/db_class.php');
$obj = new Db_Class(); // instancja obiektu obslugujacego baze danych
?>

<html>

<head>
    <meta charset="utf-8" />
    <title><?php echo $title; ?></title>
    <meta name="description" content="Strona edukacyjna poświęcona lokalizacji i charakterystyce zamków obronnych w Polsce" />
    <meta name="keywords" content="zamek, obronny, ruiny, pałac" />
    <meta name="author" content="Daria" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;1,700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href=<?php echo "/" . $homeUrl . "style/style.css" ?> />
    <link rel="stylesheet" href=<?php echo "/" . $homeUrl . "lib/leaflet/leaflet.css" ?> />

</head>

<body>
    <header>
        <div id="logotyp">
            <h1 class="logo">
                Zamki obronne w Polsce
            </h1>
        </div>
    </header>

    <nav class="menu">
        <a href=<?php echo "/" . $homeUrl . "" ?>>Strona główna</a>
        <a href=<?= "/" . $homeUrl . "pages/zamki.php" ?>>Zamki</a>
        <a href=<?= "/" . $homeUrl . "pg/index.html" ?>>Routing app</a>
        <a href="#">Kontakt</a>
    </nav>
    <!-- chilvew to zmienna, w której miejsce wstawiane będą zawartości strony zdefiniowane w plikach zamek.php i index.php -->
    <div class="content">
        <?php include($childView); ?>
    </div>

</body>

</html>