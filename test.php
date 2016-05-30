<?php
$test = 'edu_nation_text';

if (substr_count($test, 'nation') == 0 || substr_count($test, 'nation_text') != 0)
    echo 'Shit';
else echo 'good';