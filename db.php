<?php
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
                  DOC_TYPE VARCHAR(30) NOT NULL,
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
                  APPLICANT_ID INT PRIMARY KEY,
                  LANGUAGE_ID INT,
                  FOREIGN KEY (APPLICANT_ID) REFERENCES APPLICANTS (APPLICANT_ID),
                  FOREIGN KEY (LANGUAGE_ID) REFERENCES FOREIGN_LANGUAGES (LANGUAGE_ID)
)');
echo $db->error;