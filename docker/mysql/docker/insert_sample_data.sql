insert into users (screen_name, name, password_hash) values
  ('suzuki', 'SUZUKI', 'abcdefg'),
  ('yudai', 'Yudai', '1234567');

insert into posts (user_id, title, body, published_at, is_open) values
  (1, 'hello world',  'hogehogehoge',       '2020-05-01 19:00:00', TRUE),
  (1, 'good morning', 'fugafugafuga',       '2020-05-03 17:00:00', FALSE),
  (2, 'good evening', 'foobarfoobarfoobar', '2020-05-07 13:00:00', TRUE);

insert into images (post_id, url) values
  ((select id from posts where title='hello world'),  'images/abc.jpg'),
  ((select id from posts where title='hello world'),  'images/def.png'),
  ((select id from posts where title='hello world'),  'images/ghi.jpg'),
  ((select id from posts where title='good morning'), 'images/jkl.jpg'),
  ((select id from posts where title='good morning'), 'images/mno.gif'),
  ((select id from posts where title='good evening'), 'images/pqr.jpg');

insert into post_image (post_id, image_id) values
  ((select id from posts where title='hello world'),  1),
  ((select id from posts where title='good morning'), 4),
  ((select id from posts where title='good evening'), 6);

insert into tags (name) values
  ('IT'),
  ('AI'),
  ('IoT'),
  ('Game');

insert into post_tags (post_id, tag_id) values
  ((select id from posts where title='hello world'),  1),
  ((select id from posts where title='hello world'),  2),
  ((select id from posts where title='hello world'),  3),
  ((select id from posts where title='good morning'), 1),
  ((select id from posts where title='good morning'), 4);