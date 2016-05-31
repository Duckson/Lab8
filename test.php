<?php
$db = new mysqli('localhost', 'root', 'root', 'Lab8');
$db->set_charset('UTF8');
var_dump($db->query('SELECT COUNTRY_id, `NAME` FROM countries'));