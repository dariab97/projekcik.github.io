$(document).ready(function () {
  //mapa
  let mymap = L.map("mymap", { center: [52.1, 21.0], zoom: 10 });
  let adresOSM = L.tileLayer(
    "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
  );

  // dodanie własnych danych
  let mojeDane = L.tileLayer.wms("http://127.0.0.1:8080/geoserver/prge/wms", {
    layers: "prge:województwa",
    format: "image/png",
    transparent: "true",
    version: "1.1.1",
  });

  // obsługa warstw
  let baseMaps = {
    "dane z OSM": adresOSM,
    województwa: mojeDane,
  };
  L.control.layers(baseMaps).addTo(mymap);
  mymap.addLayer(adresOSM);

  // generowanie markerow
  // zdefiniowanie rozmiaru i ścieżki do niestandardowej ikony markera
  var iconOptions = {
    iconUrl: "../assets/img/zamek.png",
    iconSize: [20, 20],
  };

  // utworzenie markera za pomocą określonych paramterów
  var customIcon = L.icon(iconOptions);

  // Creating Marker Options
  var markerOptions = {
    title: "Lokalizacja zamku",
    clickable: true,
    draggable: false,
    icon: customIcon,
  };

  // warstwa znacznikow
  var markers = L.layerGroup().addTo(mymap);

  //------------------------------------------------------------------------------

  // wyszukanie wszystkich wierszy z tabeli z danymi
  var tabela = [];
  $("#tabela tr").each((index, tr) => {
    var wiersz = {};

    // wyszukanie wszystkich komórek w wierszu
    $(tr)
      .find("td")
      .each(function (index) {
        // pobranie zawartosc komorki
        var text = $(this).text();
        switch (index) {
          case 0:
            wiersz.object_id = text;
            break;

          case 1:
            wiersz.object_name = text;
            break;

          case 2:
            wiersz.object_location = text;
            break;

          case 3:
            wiersz.object_description = text;
            break;

          case 4:
            wiersz.object_lat = text;
            break;

          case 5:
            wiersz.object_lng = text;
            break;

          case 6:
            wiersz.object_sources = text;
            break;
          case 7: // znajdz przyciski w komórce i po kliknęciu przypisz dane w formularzu
            $(this)
              .find("button")
              .click((event) => {
                event.preventDefault(); //

                $("[name=object_id]").val(wiersz.object_id);
                $("[name=object_name]").val(wiersz.object_name);
                $("[name=object_lat]").val(wiersz.object_lat);
                $("[name=object_lng]").val(wiersz.object_lng);
                $("[name=object_location]").val(wiersz.object_location);
                $("[name=object_description]").val(wiersz.object_description);
                $("[name=object_sources]").val(wiersz.object_sources);

                $("#modal_box_update").show();
                $("#modal_box_create").hide();
              });
            break; // droga wyjścia
        }
      });
    tabela.push(wiersz); // wrzucenie wiersza do listy


    // stworzenie markera
    L.marker([wiersz.object_lat, wiersz.object_lng], markerOptions)
      .bindPopup(`${wiersz.object_name}`)  // Dodawanie wyskakującego okienka do znacznika
      .openPopup()
      .addTo(markers);
  });

  //------------------------------------------------------------------------------

  // obsluga dialogu
  $(".button_close_modal_create").click(() => {
    $("#modal_box_create").hide();
    $("#modal_box_update").hide();
  });

  $("#button_open_modal_create").click(() => {
    $("#modal_box_create").show();
    $("#modal_box_update").hide();

    $("[name=object_name]").val("");
    $("[name=object_lat]").val(0);
    $("[name=object_lng]").val(0);
    $("[name=object_location]").val("");
    $("[name=object_description]").val("");
    $("[name=object_sources]").val("");
  });

  $(".button_close_modal_update").click(() => {
    $("#modal_box_create").hide();
    $("#modal_box_update").hide();
  });

  //------------------------------------------------------------------------------

  var filterList = function (filteredData) {
    // pokazanie wszystkiego
    if (filteredData.length === 0) {
      $("#tabela tr").show();

      return;
    }

    // ukrycie wszystkich
    $("#tabela tr").hide();

    // pokazanie tylko tych co sa w tabeli filteredData
    $("#tabela tr").each((index, tr) => {
      $(tr)
        .find("td")
        .each(function () {
          var id = $(this).text();

          if (filteredData.includes(id)) {
            $(this).closest("tr").show();
          }
        });
    });
  };

  // obsluga wyszukiwania
  $("#btnSearch").click(function (event) {
    event.preventDefault();

    var input = $("input[name=search-field]");
    var inputText = input[0].value.toLowerCase().trim(); // wpisuje tylko małe litery i usuwa wszystkie spacje

    // czyszczenie wyszukiwania
    if (inputText.length === 0) {
      filterList([]);
      return;
    }

    var filteredData = tabela.filter(
      (item) =>
        item.object_name.toLowerCase().includes(inputText) ||
        item.object_location.toLowerCase().includes(inputText) ||
        item.object_description.toLowerCase().includes(inputText)
    );

    // tworzenie listy z id
    var filteredId = filteredData.map((item) => item.object_id);

    console.log(filteredData);
    console.log(filteredId);

    // nic nie wyszukało
    if (filteredId.length == 0) {
      filteredId.push(-1);
    }

    filterList(filteredId);
  });

  //------------------------------------------------------------------------------
});
