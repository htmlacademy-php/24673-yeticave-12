<?php
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

$categories = [
    "Доски и лыжи",
    "Крепления",
    "Ботинки",
    "Одежда",
    "Инструменты",
    "Разное"
];

$products = [
    0 => [
        "name" => "2014 Rossignol District Snowboard",
        "cat" => "Доски и лыжи",
        "price" => "10999",
        "img" => "img/lot-1.jpg"
    ],
    1 => [
        "name" => "DC Ply Mens 2016/2017 Snowboard",
        "cat" => "Доски и лыжи",
        "price" => "159999",
        "img" => "img/lot-2.jpg"
    ],
    2 => [
        "name" => "Крепления Union Contact Pro 2015 года размер L/XL",
        "cat" => "Крепления",
        "price" => "8000",
        "img" => "img/lot-3.jpg"
    ],
    3 => [
        "name" => "Ботинки для сноуборда DC Mutiny Charocal",
        "cat" => "Ботинки",
        "price" => "10999",
        "img" => "img/lot-4.jpg"
    ],
    4 => [
        "name" => "Куртка для сноуборда DC Mutiny Charocal",
        "cat" => "Одежда",
        "price" => "7500",
        "img" => "img/lot-5.jpg"
    ],
    5 => [
        "name" => "Маска Oakley Canopy",
        "cat" => "Разное",
        "price" => "5400",
        "img" => "img/lot-6.jpg"
    ]
];

$main = include_template('main.php', $data = ['categories' => $categories, 'products' => $products]);
$layout = include_template('layout.php', $data = ['content' => $main, 'title' => 'Главная',
    'is_auth' => $is_auth, 'user_name' => $user_name, 'categories' => $categories]);

print_r($layout);
