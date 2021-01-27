<?php
require ('helpers.php');
$categories = get_categories();
$lot = get_lot($_GET["id"]);

if(!$lot) {
    http_response_code(404);
}

$main = include_template('lot.php', $data = ['categories' => $categories, 'lot' => $lot]);
$layout = include_template('layout.php', $data = ['content' => $main, 'title' => 'Главная', 'categories' => $categories]);

print_r($layout);
