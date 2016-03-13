DROP TABLE IF EXISTS `blog_post`;
DROP TABLE IF EXISTS `user_account`;

CREATE TABLE `blog_post` (
  `id` INTEGER(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `content` TEXT NOT NULL,
  `html_content` TEXT NOT NULL,
  `published_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `user_account` (
  `id` INTEGER(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(30) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `salt` VARCHAR(128) NOT NULL,
  `permissions` VARCHAR(255) NOT NULL,
  `registration_date` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `blog_post` (`title`, `content`, `html_content`, `published_at`)
VALUES
  ('Mon premier post', 'Un super contenu', '<p>Un super contenu</p>', '2014-11-18 09:00:00'),
  ('Mon second post', 'Un autre contenu', '<p>Un autre contenu</p>', null),
  ('Mon troisième post', 'Pas encore publié, secret !!!', '<p>Pas encore publié, secret !!!</p>', '2014-12-25 00:00:00')
;

INSERT INTO `user_account` (`username`, `password`, `salt`, `permissions`, `registration_date`)
VALUES
  -- password: superadmin
  ('superadmin','YmFlMTNjZjgwZmFmZjQ2YjFjMzQyZjM2YTlhNGY1Njc0MDc5ODQ0NDI5ODkzMWYwY2E5YjBiZTY5MTk1NmNlNzE4ZGVjODQxNGI4OWI1NTEzZDA2MDExNDU4YTc5NjlmMzEyZmY5ZjUyYjgyNTk4YWJkNDBkMjgyNzBlNWNlNTI=','Kq1%ud_5eWu=+~=4OQX71f&W~rX9E+x`zXlB+F=\\K%oL\\@JoabPb6p(m21^NiBFhw*pTP~lzKFKq(CqD&fP`I=&z9vPMSV21QNn+vM!STeF3@v5*-oIZ(nN|awj)ZEFY','ADMIN,AUTHOR','2016-03-13 12:48:37'),
  -- password: author2016
  ('author','YzJlNGQxYmIwODNmNWE5MzI5MzEzMDMzNmVkNTdhYzYzNWE4OTNhZTgyMTZhZTU5NGQyMzIzYzBkZTNmYzNjN2I0MDQwZDg5ZGZiZTQ1YWRmYzk3Njg0OGIzZWQzZGM2MWRjMzY1OWIyYmViOGVhMzQ4MDU5NTQ0OWE4ZTlhNjI=','g-/yF%|E79O*KzQH\\TvylsTSJ&HaKb|dO?)!P|+kC6Un/?_D=R)z5ybf_=HJCd6caKa^+FLkH1oY/Y?R*=p4L1EpO1BvDHo(+jkN1k+X!C(U@xxSbnr`N2V|BFrPB8Hv','AUTHOR','2016-03-13 12:49:31'),
  -- password: nobody2016
  ('nobody','OTE5ZDNmZDIxN2RmNmYyMjY5NTczNWU5NzBkOGZkZWRlY2YzZWEzYmVlNTQ3ZmIxZGM1OTczZjQ1M2M0OGMzMDVlNjU1ZTdiOWQwNzlhNmUxNWZkZWRhMTg2MzY3ZDBmYmE5MGZhMGU2NDFhYWE3NGQ4MzBmNjJjNTM4NGQ3YWY=','hMmpBrx-SC1N~\\`i\\fOTx%dC5%vrhx)HC!aPv^Jl~~nsphe`sf3EG#gOSe~zx7-OIH(vxv~)sM%QC74)jv5=7L8w~45SjQbO!??_sjX?3N7_8JZpsva3hNRoXtC(vzB3','USER','2016-03-13 12:50:31')
;
