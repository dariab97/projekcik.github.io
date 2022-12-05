<?php

include_once('connection.php');
class Db_Class
{
    private $table_name = 'dane';

    // Tworzenie nowego rekordu w bazie
    function create()
    {


        $sql = "INSERT INTO \"" . $this->table_name . "\" (object_name, object_wsp, object_location, object_description, object_sources) Values('" . $this->cleanData($_POST['object_name']) . "','POINT(" . $_POST['object_lat'] . " " . $_POST['object_lng'] . ")','" . $this->cleanData($_POST['object_location']) . "','" . $this->cleanData($_POST['object_description']) . "','" . $this->cleanData($_POST['object_sources']) . "')";

        // pomaga przy komunikaciku na stronie -->  Zwraca liczbę rekordów, których dotyczy problem
        return pg_affected_rows(pg_query($sql));
    }

    // Pobieranie danych z bazy
    function get()
    {
        $sql = "SELECT object_id, object_name, object_wsp, object_location, object_description, object_sources, ST_X(object_wsp::geometry) as object_lat, ST_Y(object_wsp::geometry) as object_lng FROM \"" . $this->table_name . "\" ORDER BY object_id DESC";

        return pg_query($sql);
    }

    // Usunięcie rekordu z bazy
    function delete()
    {
        $sql = "DELETE FROM \"" . $this->table_name . "\" WHERE object_id='" . $this->cleanData($_POST['object_id']) . "'";
        return pg_affected_rows(pg_query($sql));
    }

    // Aktualizacja rekordu w bazie
    function update()
    {
        $sql = "UPDATE \"" . $this->table_name . "\" SET object_name='" . $this->cleanData($_POST['object_name']) . "', object_wsp='POINT(" . $_POST['object_lat'] . " " . $_POST['object_lng'] . ")', object_location='" . $this->cleanData($_POST['object_location']) . "', object_description='" . $this->cleanData($_POST['object_description']) . "', object_sources='" . $this->cleanData($_POST['object_sources']) . "' WHERE object_id='" . $this->cleanData($_POST['object_id']) . "'";

        return pg_affected_rows(pg_query($sql));
    }

    // zabezpieczenie - traktowanie wszystkich danych jako tekst 
    function cleanData($val)
    {
        // usuniecie niechcianych znakow z zaptania
        return pg_escape_string($val);
    }
}
