<?php
require ('helpers.php');

$is_auth = rand(0, 1);

$user_name = 'dez'; // укажите здесь ваше имя

$categories = query("SELECT title, slug FROM categories");
$products = query("SELECT l.id as lot_id, l.title, l.price, l.img, l.date_out, r.price as new_price, c.title as title_cat FROM lot l LEFT JOIN rate r ON l.id = r.lot_id JOIN categories c ON l.cat_id = c.id WHERE l.date_out >= NOW() ORDER BY l.date_up DESC");

$main = include_template('main.php', $data = ['categories' => $categories, 'products' => $products]);
$layout = include_template('layout.php', $data = ['content' => $main, 'title' => 'Главная',
    'is_auth' => $is_auth, 'user_name' => $user_name, 'categories' => $categories]);

print_r($layout);
