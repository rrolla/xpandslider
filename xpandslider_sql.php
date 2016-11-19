CREATE TABLE `xpand_slider` (
  `xpand_slider_id` int(11) NOT NULL AUTO_INCREMENT,
  `xpand_slider_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `xpand_slider_content` text COLLATE utf8_unicode_ci NOT NULL,
  `xpand_slider_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `xpand_slider_imgs` text COLLATE utf8_unicode_ci NOT NULL,
  `xpand_slider_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `xpand_slider_updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `xpand_slider_opts` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `xpand_slider_class` int(11) NOT NULL,
  `xpand_slider_order` int(11) NOT NULL,
  PRIMARY KEY (`xpand_slider_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;