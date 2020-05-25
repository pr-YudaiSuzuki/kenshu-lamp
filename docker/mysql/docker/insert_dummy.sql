insert into users (screen_name, name, password_hash) values
  ('suzuki', 'SUZUKI', '$2y$10$VibulVO8mbKF3p92lFHgN.wV4jO.410G0H5wg/8Q56mMVZHjKhrHS'),
  ('yudai', 'Yudai', '$2y$10$QAbF5/L5vjtrtqkYlINsvOJ14.8HuTJLTtuKAETPfYNnp45zeJlne');

insert into posts (user_id, title, published_at, is_open, body) values
  (1, 'hello world', '2020-05-01 19:00:00', TRUE,'Eu minim minim nulla do ad proident exercitation duis mollit nulla magna velit. Velit eu excepteur labore sunt cillum minim labore aliquip laboris excepteur eiusmod sint laboris est. Et velit ex voluptate sint officia laboris nulla deserunt ipsum. Ea consequat ex aliquip ullamco officia. Incididunt nostrud amet deserunt elit ut exercitation velit id quis id ea aliquip adipisicing ullamco. Id proident mollit incididunt sit ea aliquip nisi aliqua sint laboris aute labore sunt consequat. Et eu aliqua labore esse adipisicing consectetur cupidatat fugiat.'),
  (1, 'good morning', '2020-05-03 17:00:00', FALSE, 'Elit ipsum esse tempor eu dolore dolore tempor sunt dolore commodo. Officia qui sit ipsum sint ipsum voluptate velit anim eu anim voluptate. Mollit duis adipisicing culpa et aliqua et qui anim cillum officia deserunt id tempor duis. Proident ipsum velit incididunt cupidatat labore adipisicing enim id consectetur elit nulla nulla amet.'),
  (2, 'good evening', '2020-05-07 13:00:00', TRUE, 'Aliquip aute officia cupidatat enim id tempor id Lorem velit eu reprehenderit ad. Ullamco elit enim non deserunt id ipsum nisi ut est. Labore ad occaecat nostrud nisi eiusmod quis proident duis deserunt ad ex. Nulla et id sit eu et voluptate culpa ut. Commodo ut amet qui non eu. Ipsum enim nulla cupidatat eiusmod proident labore sunt. Ex sit eu occaecat nulla reprehenderit.');

insert into images (url) values
  ('4437d0b0d88faca23e28c0db86bcae72.jpg'),
  ('34c9e802a15aad46adbc310b705a96b3.jpg'),
  ('9427c152b2cfec5ea8d6c26871ebb1e5.jpg'),
  ('b532d664b56db543b3eba68a4947dd34.jpg'),
  ('9879a11b73d0d653a39299acf754158e.jpg'),
  ('5f5e712d37055f34689684277bf680a0.jpg');

insert into post_images (post_id, image_id, is_thumbnail) values
  (1,  1, true),
  (1,  2, false),
  (1,  3, false),
  (2, 4, true),
  (2, 5, false),
  (3, 6, true);

insert into tags (name) values
  ('IT'),
  ('AI'),
  ('IoT'),
  ('Game');

insert into post_tags (post_id, tag_id) values
  (1,  1),
  (1,  2),
  (1,  3),
  (2, 1),
  (2, 4);