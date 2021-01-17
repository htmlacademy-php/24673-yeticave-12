CREATE DATABASE yeticave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NUll,
  slug VARCHAR(150) NOT NUll UNIQUE
);

INSERT INTO categories (title, slug) VALUES ('Доски и лыжи', 'boards'), ('Крепления', 'attachment'), ('Ботинки', 'boots')
, ('Одежда', 'clothing'), ('Инструменты', 'tools'), ('Разное', 'other');

CREATE TABLE lot (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_up TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  title VARCHAR(150) NOT NUll,
  description TEXT NOT NUll,
  img VARCHAR(150) NOT NUll,
  price INT NOT NUll,
  date_out TIMESTAMP NOT NUll,
  rate_step INT NOT NUll,
  user_id INT NOT NUll,
  user_win_id INT,
  cat_id INT NOT NUll
);

CREATE INDEX i_name ON lot(title);
CREATE INDEX i_description ON lot(description(500));

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_up TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(150) NOT NUll UNIQUE,
  name VARCHAR(150) NOT NUll,
  pass VARCHAR(255) NOT NUll,
  contact TEXT NOT NUll
);

CREATE TABLE rate (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_up TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  price INT NOT NUll,
  user_id INT NOT NUll,
  lot_id INT NOT NUll
);
