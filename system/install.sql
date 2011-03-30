CREATE TABLE `captcha_settings` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*~query~*/
insert  into `captcha_settings`(`name`,`value`) values ('inputText','Enter value from image'),('imagePath','tmp/'),('imageExt','.png'),('imageWidth','180'),('imageHeight','50'),('errorText','Captcha is not valid or expired!'),('redirectSuccess',''),('redirectFail','');