<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Подключение к бд
 */
function bd() {
    $bd = mysqli_connect("localhost", "root", "123", "yeticave");
    mysqli_set_charset($bd,"utf8");
    if(!$bd) {
        die("Ошибка подключения");
    }

    return $bd;
}


/**
 * Форматирует цену
 * @param int $price Цена лота
 * @return string Отформатировная цена
 */
function price_format($price) {
    $price = ceil($price);

    if($price >= 1000) {
        $price = number_format($price, 0, '', ' ');
    }
    $price = $price.' ₽';

    return $price;
}

/**
 * Вычисляет сколько осталось часов и минут до окончания лота в формате: ЧЧ:ММ
 * @param string $date_out Дата окончания лота
 * @return array Ассоциативный массив с часами и минутами [ЧЧ. ММ]
 */
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

/**
 * Выполняет запрос в бд и возвращает массив
 * @param string $bd Подключение к бд
 * @param string $sql Запрос к бд
 * @param string $type Тип запроса, если отличен от all извлекает один ряд в виде ассоциативного массива
 * @return array Результат выполнения запроса в бд
 */
function query($sql, $type = "all") {
    $bd = bd();
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

/**
 * Выполняет подготовленое выражение в бд
 * @param string $sql Запрос к бд
 * @param array $params Дополнительные параметры и их тип
 * @return array Результат выполнения запроса в бд
 */
function prepare($sql, $params) {
    $bd = bd();
    $prepare = mysqli_prepare($bd, $sql);
    foreach ($params as $key => $type) {
        $types .= $type["type"];
        $vars[] = $type["value"];
    }

    mysqli_stmt_bind_param($prepare, $types, ...$vars);

    mysqli_stmt_execute($prepare);
    $result = mysqli_stmt_get_result($prepare);

    if($result->num_rows > 1) {
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $result = mysqli_fetch_assoc($result);
    }

    return $result;
}

/**
 * Возвращает весь список категории
 * @return array Результат выполнения запроса в бд
 */
function get_categories() {
    return query("SELECT title, slug FROM categories");
}

/**
 * Возвращает весь список активных лотов
 * @return array Результат выполнения запроса в бд
 */
function get_active_lot() {
    return query("SELECT l.id as lot_id, l.title, l.price, l.img, l.date_out, r.price as new_price, c.title as
title_cat FROM lot l LEFT JOIN rate r ON l.id = r.lot_id JOIN categories c ON l.cat_id = c.id WHERE l.date_out >= NOW()
ORDER BY l.date_up DESC");

}

/**
 * Возвращает информацию по конкретному лоту
 * @param int $id id лота в бд
 * @return array Результат выполнения запроса в бд
 */
function get_lot($id) {
    $params = [
        "id" => [
            "type" => "i",
            "value" => $id
        ]
    ];
    $result = prepare("SELECT l.title, l.description, l.img, l.price, l.date_out, c.title as title_cat FROM lot l
JOIN categories c ON l.cat_id = c.id WHERE l.id = ?", $params);

    return $result;
}

