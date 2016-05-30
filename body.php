<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="Lab7.js"></script>
    <title>Анкета</title>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Анкета</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a onclick="changeDiv(1)">Общие сведения</a></li>
                <li><a onclick="changeDiv(2)">Документ, удостоверяющий личность и гражданство</a></li>
                <li><a onclick="changeDiv(3)">Адрес</a></li>
                <li><a onclick="changeDiv(4)">Образование</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="row-content">
    <?php if (!$data['is_ok'] && !empty($_POST)): ?>
        <p style="color:red">Неправильно заполнены поля!</p>
        <?php if ($data['bads']['first_name'] || $data['bads']['last_name']): ?>
            <p style="color:red">Неправильно заполненны имя\фамлилия!</p>
        <? endif; ?>
        <?php if ($data['bads']['doc_serial'] || $data['bads']['doc_num']): ?>
            <p style="color:red">Неправильно заполненны паспортные данные!</p>
        <? endif; ?>
    <? endif; ?>
    <div class="col-sm-1"></div>
    <div class="col-sm-3">
        <form id="form" action="index.php" method="post">
            <div style="display: none" class="div" id="div1">
                <h3>Общие сведения</h3>
                <span style="color: red">*</span>
                Фамилия: <input style="<?php checkColor('last_name') ?>" class="form-control" name="last_name"
                                value="<?= $_POST['last_name'] ?>" type="text">
                <span style="color: red">*</span>
                Имя: <input style="<?php checkColor('first_name') ?>" class="form-control" name="first_name"
                            value="<?= $_POST['first_name'] ?>" type="text">
                Отчество: <input class="form-control" name="patronymic" value="<?= $_POST['patronymic'] ?>" type="text">
                <span style="color: red">*</span>
                Пол: <select class="form-control" name="gender">
                    <?php
                    $ops = ['Мужской' => 'Мужской', 'Женский' => 'Женский'];
                    foreach ($ops as $val => $name) {
                        echo '<option value="' . $val . '"' . ($_POST['gender'] == $val ? "selected='selected'" : '') . '>' . $name . '</option>';
                    }
                    ?>
                </select>
                <span style="color: red">*</span>
                Дата рождения: <input style="<?php checkColor('birth_date') ?>" class="form-control" name="birth_date"
                                      value="<?= $_POST['birth_date'] ?>"
                                      type="date">
                <span style="color: red">*</span>
                Место рождения (по паспорту):
                <textarea style="<?php checkColor('birth_place') ?>" name="birth_place"
                          class="form-control"><?= $_POST['birth_place'] ?></textarea>
                <span style="color: red">*</span>
                Гражданство: <select id="nation_select1" onclick="checkNation(1)" class="form-control" name="nation">
                    <?php writeOptions($db, 'COUNTRIES') ?>

                </select>
                Гражданство(другое): <input id="nation1" class="form-control" name="nation_text"
                                            value="<?= $_POST['nation_text'] ?>"
                                            type="text" <?= $_POST['nation'] != 'other' ? 'disabled' : empty($_POST['nation']) ? 'disabled' : '' ?>>
            </div>
            <div style="display: none" class="div" id="div2">
                <h3>Документ, удостоверяющий личность и гражданство</h3>
                <span style="color: red">*</span>
                Вид документа: <select class="form-control" name="doc_type">
                    <?php
                    $ops = ['Паспорт гражданина РФ' => 'Паспорт гражданина РФ', 'Паспорт гражданина иностранного государства' => 'Паспорт гражданина иностранного государства'];
                    foreach ($ops as $val => $name) {
                        echo '<option value="' . $val . '"' . ($_POST['doc_type'] == $val ? "selected='selected'" : '') . '>' . $name . '</option>';
                    }
                    ?>
                </select>
                <span style="color: red">*</span>
                Серия: <input style="<?php checkColor('doc_serial') ?>" class="form-control" name="doc_serial"
                              value="<?= $_POST['doc_serial'] ?>" type="text">
                <span style="color: red">*</span>
                Номер: <input style="<?php checkColor('doc_num') ?>" class="form-control" name="doc_num"
                              value="<?= $_POST['doc_num'] ?>" type="text">
                <span style="color: red">*</span>
                Дата выдачи: <input style="<?php checkColor('issue_date') ?>" class="form-control" name="issue_date"
                                    value="<?= $_POST['issue_date'] ?>"
                                    type="date">
                <span style="color: red">*</span>
                Орган, выдавший документ (как в паспорте):
                <textarea style="<?php checkColor('doc_giver') ?>" name="doc_giver"
                          class="form-control"><?= $_POST['doc_giver'] ?></textarea>
            </div>
            <div style="display: none" class="div" id="div3">
                <h3>Адрес по паспорту</h3>
                Страна: <select id="nation_select2" onclick="checkNation(2)" class="form-control" name="doc_nation">
                    <?php writeOptions($db, 'COUNTRIES') ?>

                </select>
                Страна(другая): <input id="nation2" class="form-control" name="doc_nation_text"
                                       value="<?= $_POST['doc_nation_text'] ?>"
                                       type="text" <?= $_POST['doc_nation'] != 'other' ? 'disabled' : empty($_POST['doc_nation']) ? 'disabled' : '' ?>>
                Населённый пункт: <input class="form-control" name="city" value="<?= $_POST['city'] ?>" type="text">
                Улица: <input class="form-control" name="street" value="<?= $_POST['street'] ?>" type="text">
                Номер дома: <input class="form-control" name="house_num" value="<?= $_POST['house_num'] ?>" type="text">
                Корпус: <input class="form-control" name="korp" value="<?= $_POST['korp'] ?>" type="text">
                Квартира: <input class="form-control" name="appt" value="<?= $_POST['appt'] ?>" type="text">
                Индекс: <input class="form-control" name="index" value="<?= $_POST['index'] ?>" type="text">
                <h3>Фактический адрес</h3>
                Страна: <select id="nation_select3" onclick="checkNation(3)" class="form-control" name="f_doc_nation">
                    <?php writeOptions($db, 'COUNTRIES') ?>

                </select>
                Страна(другая): <input id="nation3" class="form-control" name="f_doc_nation_text"
                                       value="<?= $_POST['f_doc_nation_text'] ?>"
                                       type="text" <?= $_POST['f_doc_nation'] != 'other' ? 'disabled' : empty($_POST['f_doc_nation']) ? 'disabled' : '' ?>>
                Населённый пункт: <input class="form-control" name="f_city" value="<?= $_POST['f_city'] ?>" type="text">
                Улица: <input class="form-control" name="f_street" value="<?= $_POST['f_street'] ?>" type="text">
                Номер дома: <input class="form-control" name="f_house_num" value="<?= $_POST['f_house_num'] ?>"
                                   type="text">
                Корпус: <input class="form-control" name="f_korp" value="<?= $_POST['f_korp'] ?>" type="text">
                Квартира: <input class="form-control" name="f_appt" value="<?= $_POST['f_appt'] ?>" type="text">
                Индекс: <input class="form-control" name="f_index" value="<?= $_POST['f_index'] ?>" type="text">
            </div>
            <div style="display: none" class="div" id="div4">
                <h3>Образование</h3>
                Тип учебного заведения: <select class="form-control" name="edu_place_type">
                    <?php
                    $ops = ['Школа' => 'Школа', 'Гимназия' => 'Гимназия', 'Лицей' => 'Лицей', 'Техникум' => 'Техникум',
                        'Высшее учебное учереждение' => 'Высшее учебное учереждение', 'Другое' => 'Другое'];
                    foreach ($ops as $val => $name) {
                        echo '<option value="' . $val . '"' . ($_POST['edu_place_type'] == $val ? "selected='selected'" : '') . '>' . $name . '</option>';
                    }
                    ?>
                </select>
                Страна: <select id="nation_select4" onclick="checkNation(4)" class="form-control" name="edu_nation">
                    <?php writeOptions($db, 'COUNTRIES') ?>

                </select>
                Страна(другая): <input id="nation4" class="form-control" name="edu_nation_text"
                                       value="<?= $_POST['edu_nation_text'] ?>"
                                       type="text" <?= $_POST['edu_nation'] != 'other' ? 'disabled' : empty($_POST['edu_nation']) ? 'disabled' : '' ?>>
                <span style="color: red">*</span>
                Населённый пункт: <input style="<?php checkColor('edu_city') ?>" class="form-control" name="edu_city"
                                         value="<?= $_POST['edu_city'] ?>"
                                         type="text">
                <span style="color: red">*</span>
                Серия: <input style="<?php checkColor('edu_serial') ?>" class="form-control" name="edu_serial"
                              value="<?= $_POST['edu_serial'] ?>" type="text">
                <span style="color: red">*</span>
                Название учебного заведения:
                <textarea style="<?php checkColor('edu_place_name') ?>" name="edu_place_name"
                          class="form-control"><?= $_POST['edu_place_name'] ?></textarea>
                Год окончания: <select class="form-control" name="edu_end_date">
                    <?php
                    $ops = [];
                    for ($i = 1945; $i <= date('Y'); $i++) {
                        $ops[$i] = $i;
                    }
                    foreach ($ops as $val => $name) {
                        echo '<option value="' . $val . '"' . ($_POST['edu_end_date'] == $val ? "selected='selected'" : '') . '>' . $name . '</option>';
                    }
                    ?>
                </select>
                Язык: <select class="form-control" name="edu_lang">
                    <?php writeOptions($db, 'FOREIGN_LANGUAGES') ?>
                </select>
            </div>
            <br><input class="btn btn-primary" type="submit" value="Отправить"> <a class="btn btn-danger"
                                                                                   href="index.php">Очистить</a>
        </form>
    </div>
</div>