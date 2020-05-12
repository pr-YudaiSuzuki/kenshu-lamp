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
  id           BINARY(16) DEFAULT (UUID_TO_BIN(UUID())) PRIMARY KEY,
  user_id      INT UNSIGNED NOT NULL,
  title        VARCHAR(100) NOT NULL DEFAULT '',
  body         TEXT NOT NULL,
  published_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  is_open      BOOLEAN NOT NULL DEFAULT FALSE,

  INDEX (title, published_at),

  CONSTRAINT fk_user_id_from_posts
    FOREIGN KEY (user_id)
    REFERENCES users (id)
    ON DELETE CASCADE
);

CREATE TABLE images (
  id  int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  post_id BINARY(16) NOT NULL,
  url text NOT NULL,

  CONSTRAINT fk_post_id_from_images
   FOREIGN KEY (post_id)
   REFERENCES posts (id)
   ON DELETE CASCADE
);

CREATE TABLE post_image (
  post_id  BINARY(16),
  image_id int UNSIGNED,

  PRIMARY KEY (post_id, image_id),

  CONSTRAINT fk_image_id_from_post_image
    FOREIGN KEY (image_id)
    REFERENCES images (id)
    ON DELETE CASCADE
);

CREATE TABLE tags (
  id   int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,

  INDEX (name)
);

CREATE TABLE post_tags (
  post_id BINARY(16),
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
