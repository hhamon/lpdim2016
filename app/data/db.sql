DROP TABLE IF EXISTS `blog_post`;

CREATE TABLE `blog_post` (
  `id` INTEGER(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `content` TEXT NOT NULL,
  `html_content` TEXT NOT NULL,
  `published_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

insert into `blog_post` (`title`, `content`, `html_content`, `published_at`)
values
  ('Mon premier post', 'Un super contenu', '<p>Un super contenu</p>', '2014-11-18 09:00:00'),
  ('Mon second post', 'Un autre contenu', '<p>Un autre contenu</p>', null),
  ('Mon troisième post', 'Pas encore publié, secret !!!', '<p>Pas encore publié, secret !!!</p>', '2014-12-25 00:00:00')
;
