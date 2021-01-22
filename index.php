<?php

$bd = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($bd,"utf8");
if(!$bd) {
    die("Ошибка подключения");
}

require ('helpers.php');

$is_auth = rand(0, 1);

$user_name = 'dez'; // укажите здесь ваше имя

function price_format($price) {
    $price = ceil($price);

    if($price >= 1000) {
        $price = number_format($price, 0, '', ' ');
    }
    $price = $price.' ₽';

    return $price;
}

function get_lot_out_time($date_out) {
    $hours = 0;
    $minutes = 0;

    $date_current = new DateTime();
    $date_out = new DateTime($date_out);

    $diff = $date_current->diff($date_out);

    if (!$diff->invert) {
        $hours = $diff->days * 24 + $diff->h;
        $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
        $minutes = str_pad($diff->i, 2, "0", STR_PAD_LEFT);
    }

    return [$hours, $minutes];
}

function query($bd, $sql, $type = "all") {
    $result = mysqli_query($bd, $sql);

    if(!$result) {
        return $result;
    }

    if($type = "all") {
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $result = mysqli_fetch_assoc($result);
    }

    return $result;

}

$categories = query($bd,"SELECT * FROM categories");
$products = query($bd,"SELECT l.id as lot_id, l.title, l.price, l.img, l.date_out, r.price as new_price, c.title as title_cat FROM lot l LEFT JOIN rate r ON l.id = r.lot_id LEFT JOIN categories c ON l.cat_id = c.id WHERE l.date_out >= NOW() ORDER BY l.date_up DESC");

$main = include_template('main.php', $data = ['categories' => $categories, 'products' => $products]);
$layout = include_template('layout.php', $data = ['content' => $main, 'title' => 'Главная',
    'is_auth' => $is_auth, 'user_name' => $user_name, 'categories' => $categories]);

print_r($layout);
