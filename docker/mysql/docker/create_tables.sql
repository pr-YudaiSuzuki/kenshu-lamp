DROP DATABASE IF EXISTS kenshu;
CREATE DATABASE kenshu;
USE kenshu;

CREATE TABLE users (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  screen_name   VARCHAR(20) NOT NULL UNIQUE,
  name          VARCHAR(50) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,

  INDEX (screen_name)
);

CREATE TABLE posts (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug         VARCHAR(255) NOT NULL DEFAULT (UUID()) UNIQUE,
  user_id      INT UNSIGNED NOT NULL,
  title        VARCHAR(100) NOT NULL DEFAULT '',
  body         TEXT NOT NULL,
  published_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  is_open      BOOLEAN NOT NULL DEFAULT FALSE,

  INDEX (slug, title, published_at),

  CONSTRAINT fk_user_id_from_posts
    FOREIGN KEY (user_id)
    REFERENCES users (id)
    ON DELETE CASCADE
);

CREATE TABLE images (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  url TEXT NOT NULL
);

CREATE TABLE post_images (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  post_id  INT UNSIGNED,
  image_id INT UNSIGNED,
  is_thumbnail BOOLEAN NOT NULL DEFAULT FALSE,

  CONSTRAINT fk_post_id_from_post_images
    FOREIGN KEY (post_id)
    REFERENCES posts (id)
    ON DELETE CASCADE
);

CREATE TABLE tags (
  id   int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,

  INDEX (name)
);

CREATE TABLE post_tags (
  post_id INT UNSIGNED,
  tag_id  int UNSIGNED,

  PRIMARY KEY (post_id, tag_id),

  CONSTRAINT fk_post_id_from_post_tags
   FOREIGN KEY (post_id)
   REFERENCES posts (id)
   ON DELETE CASCADE,

  CONSTRAINT fk_tag_id_from_post_tags
   FOREIGN KEY (tag_id)
   REFERENCES tags (id)
   ON DELETE CASCADE
);