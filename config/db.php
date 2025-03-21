<?php
// db.php

class Database {
    public static function connect() {
        $db = new mysqli("localhost", "root", "", "reportesambientes");
        //$db = new mysqli("localhost", "u357463700_admin_ambiente", "5BeEH;pI", "u357463700_repoambientes");
        $db->query("SET NAMES 'utf8'");
        return $db;
    }
}

?>
