DROP TABLE IF EXISTS `#__autotweet_channeltypes`;
CREATE TABLE IF NOT EXISTS `#__autotweet_channeltypes` (
  `id` int(11) NOT NULL,
  `name` varchar(64), 
  `description` varchar(1024),
  `max_chars` int(4),
  `auth_url` varchar(255),
  `auth_key` varchar(255),
  `auth_secret` varchar(255),
  `field_keys` varchar(255),
  `field_names` varchar(255),
  `selection_values` varchar(255),
  `own_api_allowed` tinyint(1),
  `api_field_keys` varchar(255),
  `api_field_names` varchar(255),
  PRIMARY KEY  (`id`)
) DEFAULT CHARACTER SET utf8;

INSERT INTO `#__autotweet_channeltypes`
	(`id`, `name`, `description`, `max_chars`) 
	VALUES (1, 'Twitter', 'COM_AUTOTWEET_CHANNEL_TWITTER_DESC', 140);
	
INSERT INTO `#__autotweet_channeltypes`
	(`id`, `name`, `description`, `max_chars`, `auth_url`, `auth_key`, `auth_secret`, `field_keys`, `field_names`, `selection_values`, `own_api_allowed`, `api_field_keys`, `api_field_names`) 
	VALUES (2, 'Facebook', 'COM_AUTOTWEET_CHANNEL_FACEBOOK_DESC', 420, 'https://apps.facebook.com/autotweetsvtw/index.php', 'TXktQXBwLUlE', 'TXktQXBwLVNlY3JldA==', 'id_1,id_2', 'COM_AUTOTWEET_CHANNEL_FACEBOOK_FIELD_ID1,COM_AUTOTWEET_CHANNEL_FACEBOOK_FIELD_ID2', '', 0, 'api_key,api_secret,api_authurl', 'COM_AUTOTWEET_CHANNEL_FACEBOOK_FIELD_APIKEY,COM_AUTOTWEET_CHANNEL_FACEBOOK_FIELD_APISECRET,COM_AUTOTWEET_CHANNEL_FACEBOOK_FIELD_APIAUTHURL');
	
INSERT INTO `#__autotweet_channeltypes`
	(`id`, `name`, `description`, `max_chars`) 
	VALUES (3, 'Mail', 'COM_AUTOTWEET_CHANNEL_MAIL_DESC', 16384);
	
INSERT INTO `#__autotweet_channeltypes`
	(`id`, `name`, `description`, `max_chars`) 
	VALUES (5, 'LinkedIn', 'COM_AUTOTWEET_CHANNEL_LINKEDIN_DESC', 200);

INSERT INTO `#__autotweet_channeltypes`
	(`id`, `name`, `description`, `max_chars`) 
	VALUES (6, 'LinkedIn Group', 'COM_AUTOTWEET_CHANNEL_LINKEDINGROUP_DESC', 200);	

INSERT INTO `#__autotweet_channeltypes`
	(`id`, `name`, `description`, `max_chars`, `auth_url`, `auth_key`, `auth_secret`, `field_keys`, `field_names`, `selection_values`, `own_api_allowed`, `api_field_keys`, `api_field_names`) 
	VALUES (7, 'Facebook Link', 'COM_AUTOTWEET_CHANNEL_FACEBOOKLINK_DESC', 420, 'https://apps.facebook.com/autotweetsvtw/index.php', 'TXktQXBwLUlE', 'TXktQXBwLVNlY3JldA==', 'id_1,id_2', 'COM_AUTOTWEET_CHANNEL_FACEBOOK_FIELD_ID1,COM_AUTOTWEET_CHANNEL_FACEBOOK_FIELD_ID2', '', 0, 'api_key,api_secret,api_authurl', 'COM_AUTOTWEET_CHANNEL_FACEBOOK_FIELD_APIKEY,COM_AUTOTWEET_CHANNEL_FACEBOOK_FIELD_APISECRET,COM_AUTOTWEET_CHANNEL_FACEBOOK_FIELD_APIAUTHURL');

INSERT INTO `#__autotweet_channeltypes`
	(`id`, `name`, `description`, `max_chars`, `auth_url`, `auth_key`, `auth_secret`, `field_keys`, `field_names`, `selection_values`, `own_api_allowed`, `api_field_keys`, `api_field_names`) 
	VALUES (8, 'Facebook Photo', 'COM_AUTOTWEET_CHANNEL_FACEBOOKPHOTO_DESC', 420, 'https://apps.facebook.com/autotweetsvtw/index.php', 'TXktQXBwLUlE', 'TXktQXBwLVNlY3JldA==', 'id_1,id_2', 'COM_AUTOTWEET_CHANNEL_FACEBOOK_FIELD_ID1,COM_AUTOTWEET_CHANNEL_FACEBOOK_FIELD_ID2', '', 0, 'api_key,api_secret,api_authurl', 'COM_AUTOTWEET_CHANNEL_FACEBOOK_FIELD_APIKEY,COM_AUTOTWEET_CHANNEL_FACEBOOK_FIELD_APISECRET,COM_AUTOTWEET_CHANNEL_FACEBOOK_FIELD_APIAUTHURL');
	
INSERT INTO `#__autotweet_channeltypes`
	(`id`, `name`, `description`, `max_chars`) 
	VALUES (10, 'LinkedIn Company', 'COM_AUTOTWEET_CHANNEL_LINKEDINCOMPANY_DESC', 200);
	
INSERT INTO `#__autotweet_channeltypes`
	(`id`, `name`, `description`, `max_chars`) 
	VALUES (11, 'VK (Beta)', 'COM_AUTOTWEET_CHANNEL_VK_DESC', 320);
	
INSERT INTO `#__autotweet_channeltypes`
	(`id`, `name`, `description`, `max_chars`) 
	VALUES (12, 'VK Communities (Beta)', 'COM_AUTOTWEET_CHANNEL_VK_DESC', 320);
	
INSERT INTO `#__autotweet_channeltypes`
	(`id`, `name`, `description`, `max_chars`) 
	VALUES (13, 'Google+ Moments', 'COM_AUTOTWEET_CHANNEL_GPMOMENTS_DESC', 320);
	
INSERT INTO `#__autotweet_channeltypes`
	(`id`, `name`, `description`, `max_chars`) 
	VALUES (14, 'Scoop.it', 'COM_AUTOTWEET_CHANNEL_SCOOPIT_DESC', 420);
	
INSERT INTO `#__autotweet_channeltypes`
	(`id`, `name`, `description`, `max_chars`) 
	VALUES (15, 'Xing', 'COM_AUTOTWEET_CHANNEL_XING_DESC', 420);	
	
INSERT INTO `#__autotweet_channeltypes`
	(`id`, `name`, `description`, `max_chars`) 
	VALUES (16, 'Tumblr', 'COM_AUTOTWEET_CHANNEL_TUMBLR_DESC', 420);

INSERT INTO `#__autotweet_channeltypes`
	(`id`, `name`, `description`, `max_chars`) 
	VALUES (17, 'Google Blogger', 'COM_AUTOTWEET_CHANNEL_BLOGGER_DESC', 420);
		
INSERT INTO `#__autotweet_channeltypes`
	(`id`, `name`, `description`, `max_chars`) 
	VALUES (18, 'JomSocial', 'COM_AUTOTWEET_CHANNEL_JOMSOCIAL_DESC', 420);
	
INSERT INTO `#__autotweet_channeltypes`
	(`id`, `name`, `description`, `max_chars`) 
	VALUES (19, 'EasySocial', 'COM_AUTOTWEET_CHANNEL_EASYSOCIAL_DESC', 420);


DROP TABLE IF EXISTS `#__autotweet_ruletypes`;
CREATE TABLE IF NOT EXISTS `#__autotweet_ruletypes` (
  `id` int(11) NOT NULL,
  `name` varchar(64), 
  `description` varchar(512),
  PRIMARY KEY  (`id`)
) DEFAULT CHARACTER SET utf8;

INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (1, 'category: IN', 'COM_AUTOTWEET_RULE_CATEGORYIN_DESC');

INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (2, 'category: NOT IN', 'COM_AUTOTWEET_RULE_CATEGORYNOTIN_DESC');

INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (3, 'term: OR', 'COM_AUTOTWEET_RULE_TERMOR_DESC');

INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (4, 'term: AND', 'COM_AUTOTWEET_RULE_TERMAND_DESC');
	
INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (5, 'catch all not fits', 'COM_AUTOTWEET_RULE_CATCHALLNOTFITS_DESC');

INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (6, 'word term: OR', 'COM_AUTOTWEET_RULE_WORDTERMOR_DESC');

INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (7, 'word term: AND', 'COM_AUTOTWEET_RULE_WORDTERMAND_DESC');

INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (8, 'regular expression match', 'COM_AUTOTWEET_RULE_REGEX_DESC');

INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (9, 'term: NOT IN', 'COM_AUTOTWEET_RULE_TERMNOTIN_DESC');
	
INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (10, 'word term: NOT IN', 'COM_AUTOTWEET_RULE_WORDTERMNOTIN_DESC');

INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (11, 'author: IN', 'COM_AUTOTWEET_RULE_AUTHORIN_DESC');

INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (12, 'author: NOT IN', 'COM_AUTOTWEET_RULE_AUTHORNOTIN_DESC');

INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (13, 'catch all', 'COM_AUTOTWEET_RULE_CATCHALL_DESC');

INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (14, 'language: IN', 'COM_AUTOTWEET_RULE_LANGUAGEIN_DESC');

INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (15, 'language: NOT IN', 'COM_AUTOTWEET_RULE_LANGUAGENOTIN_DESC');

INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (16, 'access: IN', 'COM_AUTOTWEET_RULE_ACCESSIN_DESC');

INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (17, 'access: NOT IN', 'COM_AUTOTWEET_RULE_ACCESSNOTIN_DESC');
	
INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (18, 'channel scope: IS User', 'COM_AUTOTWEET_RULE_SOURCEBACK_DESC');
	
INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (19, 'channel scope: IS Site', 'COM_AUTOTWEET_RULE_SOURCEBACK_DESC');		

INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (20, 'channel owner: IN group', 'COM_AUTOTWEET_RULE_SOURCEBACK_DESC');		

INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (21, 'channel owner: NOT IN group', 'COM_AUTOTWEET_RULE_SOURCEBACK_DESC');
