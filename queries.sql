-- Добавляем список категории
INSERT INTO categories (title, slug) VALUES ('Доски и лыжи', 'boards'), ('Крепления', 'attachment'), ('Ботинки', 'boots')
, ('Одежда', 'clothing'), ('Инструменты', 'tools'), ('Разное', 'other');

-- Добавляем пользователей
INSERT INTO user (email, name, pass, contact) VALUES ('info@yeticave.ru', 'dez', '123', 'skype: 123qwe'),
('test@mail.ru', 'QQR', 'qwerty123', 'Телфон: 9999999999'),
('test2@mail.ru', 'RRQ', 'ytrewq123', 'Телфон: 9999999998');

-- Добавляем текущие лоты
INSERT INTO lot (title, description, img, price, date_out, rate_step, user_id, cat_id) VALUES
('2014 Rossignol District Snowboard', 'Информация о 2014 Rossignol District Snowboard', 'img/lot-1.jpg', '10999', '2021-01-21 15:00:00', '500', '1', '1'),
('DC Ply Mens 2016/2017 Snowboard', 'Информация о DC Ply Mens 2016/2017 Snowboard', 'img/lot-2.jpg', '159999', '2021-01-22 15:00:00', '250', '2', '1'),
('Крепления Union Contact Pro 2015 года размер L/XL', 'Информация о Крепления Union Contact Pro 2015 года размер L/XL', 'img/lot-3.jpg', '8000', '2021-01-24 15:00:00', '100', '1', '2'),
('Ботинки для сноуборда DC Mutiny Charocal', 'Информация о Ботинки для сноуборда DC Mutiny Charocal', 'img/lot-4.jpg', '10999', '2021-01-28 15:00:00', '50', '2', '3'),
('Куртка для сноуборда DC Mutiny Charoca', 'Информация о Куртка для сноуборда DC Mutiny Charocal', 'img/lot-5.jpg', '7500', '2021-01-26 15:00:00', '150', '1', '4'),
('Маска Oakley Canopy', 'Информация о Маска Oakley Canopy', 'img/lot-6.jpg', '5400', '2021-01-22 15:00:00', '300', '1', '6'),
('Ботинки для сноуборда DC Mutiny Charocal', 'Информация о Ботинки для сноуборда DC Mutiny Charocal', 'img/lot-4.jpg', '10999', '2021-02-01 15:00:00', '50', '2', '3');

-- Добавляем ставки
INSERT INTO rate (price, user_id, lot_id) VALUES ('11499', '2', '1'), ('11049', '1', '4'), ('11999', '3', '1');


-- Получить все категории
SELECT * FROM categories;

-- Получить самые новые лоты, открытые.
SELECT l.id as lot_id, l.title, l.price, l.img, r.price as new_price, c.title as title_cat FROM lot l LEFT JOIN rate r ON l.id = r.lot_id LEFT JOIN categories c ON l.cat_id = c.id WHERE l.date_out > NOW() ORDER BY l.date_up DESC;

-- Получить лот по id
SELECT l.title, l.description, l.img, l.price, l.date_out, c.title as title_cat FROM lot l JOIN categories c ON l.cat_id = c.id WHERE l.id = 5;

-- Обновление лота по его id
UPDATE lot SET title = 'Куртка для сноуборда DC Mutiny Charocal' WHERE id = 5;

-- Получить список ставок для лота с id = 1
SELECT id, price, user_id, date_up FROM rate WHERE lot_id = 1 ORDER BY date_up DESC;
