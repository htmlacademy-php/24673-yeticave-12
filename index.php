<?php
require ('helpers.php');

$is_auth = rand(0, 1);

$user_name = 'dez'; // укажите здесь ваше имя

$categories = get_categories();
$products = get_active_lot();

$main = include_template('main.php', $data = ['categories' => $categories, 'products' => $products]);
$layout = include_template('layout.php', $data = ['content' => $main, 'title' => 'Главная',
    'is_auth' => $is_auth, 'user_name' => $user_name, 'categories' => $categories]);

print_r($layout);
