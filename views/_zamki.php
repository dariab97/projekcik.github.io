<?php
$items = $obj->get();
// Zmienna items zaczytuje dane ze zmiennej obj
if (isset($_POST['submit_update'])) {
    $ret_val = $obj->update();
    if ($ret_val == 1) {
        echo " <script type='text/javascript'>
                alert('Record zaktualizowany pomyślnie')
            </script>";

        $items = $obj->get();
    } else {
        echo " <script type='text/javascript'>
            alert('cos poszlo nie tak')
            </script>";
    }
    $_POST = [];
}

if (isset($_POST['delete'])) {
    $ret_val = $obj->delete();

    if ($ret_val == 1) {
        echo " <script type='text/javascript'>
        alert('Record usyniety pomyślnie')
    </script>";

        $items = $obj->get();
    } else {
        echo " <script type='text/javascript'>
            alert('cos poszlo nie tak')
        </script>";
    }

    $_POST = [];
}

if (isset($_POST['submit_create'])) {
    $ret_val = $obj->create();
    if ($ret_val == 1) {
        echo " <script type='text/javascript'>
                alert('Record dodany pomyślnie')
            </script>";

        $items = $obj->get();
    } else {
        echo " <script type='text/javascript'>
            alert('cos poszlo nie tak')
        </script>";
    }
    $_POST = [];
}

?>

<!-- tytul -->
<h1>
    Lista zamków obronnych w Polsce
</h1>


<!-- mapa -->
<div class="grid-item-right">
    <div id="mymap"></div>
</div>

<!-- dodawanie -->
<div style="width:90vw">
    <!-- utworzenie przycisku otwierającego okno, po którego naciśnięciu strona przewijana jest w sposób płynny do samego góry (top=0) -->
    <button id="button_open_modal_create" class="green-button" onclick='window.scrollTo({top: 0, behavior: "smooth"});'>Dodaj zamek</button>

    <div class="modalBox" id="modal_box_create">
        <button class="button_close_modal_create">X</button>

        <form method="post" id="modal_do_wprowadzania_danych">
            <h2>Dodaj zamek</h2>
            <label for="object_name">Nazwa:</label> <br />
            <input class="form-input" type="text" id="object_name" name="object_name" required />
            <br /><br />

            <label for="object_lat">Szerokość geograficzna:</label> <br />
            <input class="form-input" min="-90" max="90" step="any" type="number" id="object_lat" name="object_lat" required />
            <br /><br />

            <label for="object_lng">Długość geograficzna:</label> <br />
            <input class="form-input" min="-180" max="180" step="any" type="number" id="object_lng" name="object_lng" required />
            <br /><br />

            <label for="object_location">Lokalizacja:</label> <br />
            <input class="form-input" type="text" id="object_location" name="object_location" required />
            <br /><br />

            <label for="object_description">Opis:</label> <br />
            <input class="form-input" type="text" id="object_description" name="object_description" required />
            <br /><br />

            <label for="object_sources">Źródło:</label> <br />
            <input class="form-input" type="text" id="object_sources" name="object_sources" required />
            <br /><br />

            <input type="submit" name="submit_create" value="Dodaj" class="green-button" />
        </form>
    </div>
</div>

<!-- aktualizacja -->
<div style="width:90vw">

    <div class="modalBox" id="modal_box_update">
        <button class="button_close_modal_update">X</button>

        <form method="post" id="modal_do_wprowadzania_danych_update">
            <h2>Aktualizuj zamek</h2>
            <input class="form-input" style="display:none" id="object_id" type="text" name="object_id" required />

            <label for="object_name">Nazwa:</label> <br />
            <input class="form-input" id="object_name" type="text" name="object_name" required />
            <br /><br />
            <label for="object_lat">Szerokość geograficzna:</label> <br />
            <input class="form-input" min="-90" max="90" step="any" id="object_lat" type="number" name="object_lat" required />
            <br /><br />

            <label for="object_lng">Długość geograficzna:</label> <br />
            <input class="form-input" min="-180" max="180" step="any" id="object_lng" type="number" name="object_lng" required />
            <br /><br />

            <label for="object_location">Lokalizacja:</label> <br />
            <input class="form-input" id="object_location" type="text" name="object_location" required />
            <br /><br />

            <label for="object_description">Opis:</label> <br />
            <input class="form-input" id="object_description" type="text" name="object_description" required />
            <br /><br />

            <label for="object_sources">Źródło:</label> <br />
            <input class="form-input" id="object_sources" type="text" name="object_sources" required />
            <br /><br />

            <input type="submit" name="submit_update" value="Aktualizuj" class="green-button" />
        </form>
    </div>
</div>

<!-- wyszukiwarka -->
<div class="form-search">
    <h2>Wyszukiwanie</h2>
    <form>
        <input type="text" name="search-field" placeholder="Wprowadź szukaną frazę" class="form-input" />
        <button id="btnSearch" class="green-button">Szukaj</button>
    </form>

    <!-- lista -->
    <div id="lista">
        <table style="width:100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nazwa</th>
                    <th>Lokalizacja</th>
                    <th>Opis</th>
                    <th style="display:none">Latitude</th>
                    <th style="display:none">Longitude</th>
                    <th style="display:none">Sources</th>
                    <th>Operacje</th>
                </tr>
            </thead>
            <tbody id="tabela">
                <?php while ($item = pg_fetch_object($items)) : ?>
                    <tr class="item">
                        <td><?= $item->object_id ?></td>
                        <td><?= $item->object_name ?></td>
                        <td><?= $item->object_location ?></td>
                        <td><?= $item->object_description ?></td>
                        <!-- wyłączenie z widoku strony trzech kolum -->
                        <td style="display:none"><?= $item->object_lat ?></td>
                        <td style="display:none"><?= $item->object_lng ?></td>
                        <td style="display:none"><?= $item->object_sources ?></td>
                        <td>
                            <form method="post" class="button-form">
                                <button type="submit" class="orange-button" name="update" onclick='window.scrollTo({top: 0, behavior: "smooth"});'>Aktualizuj</button>
                                <input type="submit" class="red-button" name="delete" value="Usuń" onClick="return confirm('Czy na pewno chcesz usunąć?');" />
                                <input type="hidden" value="<?= $item->object_id ?>" name="object_id" />
                            </form>
                            <a href="<?= $item->object_sources ?>" target="_blank" class="blue-button">Wiecej informacji</a>
                            <!-- target="_blank" odpowiada za otworzenie linku w nowej karcie  -->
                        </td>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src=<?php echo "/" . $homeUrl . "lib/leaflet/leaflet.js" ?>></script>
<script src=<?php echo "/" . $homeUrl . "lib/jquery-3.6.0.js" ?>></script>
<script src=<?php echo "/" . $homeUrl . "js/mapa_zamki.js" ?>></script>