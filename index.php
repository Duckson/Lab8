<?php
$root = $_SERVER['DOCUMENT_ROOT'] . '/Lab8/';
$db = new mysqli('localhost', 'root', 'root', 'Lab8');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
$db->set_charset('UTF8');
$data = checkPost();

if ($data['is_ok']) {
    foreach ($_POST as $key => $value) {
        if ($key != 'edu_lang') {
            $keys[] = '`' . strtoupper($key) . '`';
            $values[] = '"' . $value . '"';
        }
    }

    $key = join(', ', $keys);
    $value = join(', ', $values);
    if ($db->query('INSERT INTO APPLICANTS(' . $key . ') VALUES(' . $value . ')') === TRUE) {
        $data['inserted'] = true;
    } else {
        echo "Error while inserting in APPLICANTS: " . $db->error . '/n';
        $data['inserted'] = false;
    }
    if (!$db->query('INSERT INTO APPLICANT_FOREIGN_LANGUAGES(APPLICANT_ID, LANGUAGE_ID) VALUES('
            . $db->insert_id . ', "' . $_POST['edu_lang'] . '")') === TRUE
    ) {
        echo "Error while inserting in APPLICANT_FOREIGN_LANGUAGES: " . $db->error . '/n';
        $data['inserted'] = false;
    }
}

$result = $db->query('SELECT COUNTRY_id, NAME FROM countries');
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data['countries'][] = $row;
    }
} else echo 'Error: No countries in DB!/n';

$result = $db->query('SELECT LANGUAGE_id, NAME FROM foreign_languages');
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data['languages'][] = $row;
    }
} else echo 'Error: No languages in DB!/n';

function checkPost()
{
    $is_ok = !empty($_POST);
    $required = ['last_name', 'first_name', 'birth_date', 'birth_place', 'doc_serial', 'doc_num',
        'issue_date', 'doc_giver', 'edu_city', 'edu_serial', 'edu_place_name'];
    $data = [];

    foreach ($required as $name) {
        if (empty($_POST[$name])) {
            $is_ok = false;
            $data['bads'][$name] = true;
        }
        $data['all'][$name] = $_POST[$name];
    }

    $names = ['last_name', 'first_name'];
    foreach ($names as $name)
        if (preg_match('/^[a-zA-Zа-яА-Я]+$/u', $_POST[$name])
            != 1 || ucfirst($_POST[$name]) != $_POST[$name]
        ) {
            $data['bads'][$name] = true;
            $is_ok = false;
        }

    if (preg_match('/^[{a-zA-Z}(0-9)]+$/u', $_POST['doc_serial']) != 1) {
        $data['bads']['doc_serial'] = true;
        $is_ok = false;
    }

    if (preg_match('/^[0-9]+$/u', $_POST['doc_num']) != 1) {
        $data['bads']['doc_num'] = true;
        $is_ok = false;
    }

    $data['is_ok'] = $is_ok;
    return $data;
}

function checkColor($name)
{
    if (!empty($_POST)) {
        global $data;
        if ($data['bads'][$name])
            echo 'background-color: #f49797';
    }
}

require($root . 'body.php');

