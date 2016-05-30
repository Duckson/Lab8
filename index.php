<?php
$root = $_SERVER['DOCUMENT_ROOT'] . '/Lab8/';
$db = new mysqli('localhost', 'root', 'root');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$db->query('CREATE DATABASE IF NOT EXISTS Lab8');

$db = new mysqli('localhost', 'root', 'root', 'Lab8');
$db->set_charset('UTF8');
$tables = ['COUNTRIES' => 'COUNTRY', 'FOREIGN_LANGUAGES' => 'LANGUAGE'];

foreach ($tables as $table => $id) {
    $db->query('CREATE TABLE IF NOT EXISTS ' . $table . ' (
                  ' . $id . '_id INT PRIMARY KEY AUTO_INCREMENT,
                  NAME VARCHAR(100) NOT NULL         
);');
}

$db->query('CREATE TABLE IF NOT EXISTS APPLICANTS (
                  APPLICANT_ID INT PRIMARY KEY AUTO_INCREMENT,
                  LAST_NAME  VARCHAR(100) NOT NULL,
                  FIRST_NAME VARCHAR(100) NOT NULL,
                  PATRONYMIC VARCHAR(100) NOT NULL,
                  GENDER VARCHAR(15) NOT NULL,
                  BIRTH_DATE DATE NOT NULL,
                  BIRTH_PLACE VARCHAR(255) NOT NULL,
                  NATION INT NOT NULL,
                  FOREIGN KEY (NATION) REFERENCES COUNTRIES (COUNTRY_ID),
                  NATION_TEXT VARCHAR(100) NULL,
                  DOC_TYPE CHAR(1) NOT NULL,
                  DOC_SERIAL VARCHAR(10) NOT NULL,
                  DOC_NUM VARCHAR(20) NOT NULL,
                  ISSUE_DATE DATE NOT NULL,
                  DOC_GIVER VARCHAR(255) NOT NULL,
                  DOC_NATION INT NULL,
                  FOREIGN KEY (DOC_NATION) REFERENCES COUNTRIES (COUNTRY_ID),
                  DOC_NATION_TEXT VARCHAR(100)  NULL,
                  CITY VARCHAR(50) NULL,
                  STREET VARCHAR(50) NULL,
                  HOUSE_NUM VARCHAR(10) NULL,
                  KORP VARCHAR(10) NULL,
                  APPT VARCHAR(5) NULL,
                  `INDEX` VARCHAR(10) NULL,
                  F_DOC_NATION INT NULL,
                  FOREIGN KEY (F_DOC_NATION) REFERENCES COUNTRIES (COUNTRY_ID),
                  F_DOC_NATION_TEXT VARCHAR(100) NULL,
                  F_CITY  VARCHAR(50) NULL,
                  F_STREET VARCHAR(50) NULL,
                  F_HOUSE_NUM VARCHAR(10) NULL,
                  F_KORP VARCHAR(10) NULL,
                  F_APPT VARCHAR(5) NULL,
                  F_INDEX VARCHAR(10) NULL,
                  EDU_PLACE_TYPE VARCHAR(25) NOT NULL,
                  EDU_NATION INT NOT NULL,
                  FOREIGN KEY (EDU_NATION) REFERENCES COUNTRIES (COUNTRY_ID),
                  EDU_NATION_TEXT VARCHAR(100) NOT NULL,
                  EDU_CITY VARCHAR(50) NOT NULL,
                  EDU_SERIAL VARCHAR(100) NOT NULL,
                  EDU_PLACE_NAME INT NOT NULL,
                  EDU_END_DATE VARCHAR(25) NOT NULL
)');
echo $db->error;

$db->query('CREATE TABLE IF NOT EXISTS APPLICANT_FOREIGN_LANGUAGES (
                  APPLICANT_ID INT,
                  LANGUAGE_ID INT,
                  FOREIGN KEY (APPLICANT_ID) REFERENCES APPLICANTS (APPLICANT_ID),
                  FOREIGN KEY (LANGUAGE_ID) REFERENCES FOREIGN_LANGUAGES (LANGUAGE_ID)
)');
echo $db->error;

$data = checkPost();
$foreign_keys = ['NATION', 'DOC_NATION', 'F_DOC_NATION', 'EDU_NATION'];

if ($data['is_ok']) {
    foreach ($_POST as $key => $value) {
        if($key != 'edu_lang')
        if (substr_count($key, 'nation') == 0 || substr_count($key, 'nation_text') != 0) {
            $keys[] = '`' . strtoupper($key) . '`';
            $values[] = '"' . strtoupper($value) . '"';
        }
    }

    foreach ($foreign_keys as $f_key){
        $keys[] = '`' . strtoupper($f_key) . '`';
        $values[] = '"' . strtoupper($db->query('SELECT COUNTRY_id FROM countries WHERE `NAME`="' . $_POST[strtolower($f_key)] . '"')->fetch_assoc()[0]) . '"';
    }

    $key = join(', ', $keys);
    $value = join(', ', $values);
    $db->query('INSERT INTO APPLICANTS('. $key .') VALUES('. $value .')');
    var_dump($db->insert_id);
    echo $db->error;
    $db->query('INSERT INTO APPLICANT_FOREIGN_LANGUAGES(APPLICANT_ID, LANGUAGE_ID) VALUES('. $db->insert_id . ', "' . $_POST['edu_lang'] . '")');
    echo $db->error;
}

function writeOptions($db, $table){
    $data = $db->query('SELECT NAME FROM '. $table);
    while ($row = $data->fetch_assoc()) {
        $name = $row['NAME'];
        echo '<option value="' . $name . '">' . $name . '</option>';
    }
}

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
        if (preg_match('/^[\p{L}-]+$/u', $_POST[$name])
            != 1 || ucfirst($_POST[$name]) != $_POST[$name]
        ) {
            $data['bads'][$name] = true;
            $is_ok = false;
        }

    if (preg_match('/^[\p{Latin}(0-9)]+$/u', $_POST['doc_serial']) != 1) {
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

