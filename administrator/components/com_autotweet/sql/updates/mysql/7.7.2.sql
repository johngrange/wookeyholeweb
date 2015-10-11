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
	(`id`, `name`, `description`) VALUES (20, 'channel owner: IN Group', 'COM_AUTOTWEET_RULE_SOURCEBACK_DESC');		
INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (21, 'channel owner: NOT IN Group', 'COM_AUTOTWEET_RULE_SOURCEBACK_DESC');		
INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (22, 'author group: IN', 'COM_AUTOTWEET_RULE_AUTHORIN_DESC');
INSERT INTO `#__autotweet_ruletypes`
	(`id`, `name`, `description`) VALUES (23, 'author group: NOT IN', 'COM_AUTOTWEET_RULE_AUTHORNOTIN_DESC');
	