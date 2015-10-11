
ALTER TABLE `#__autotweet_posts` ADD INDEX `post_postdate` (`postdate`);

ALTER TABLE `#__autotweet_requests` ADD INDEX `req_publish_up` (`publish_up`);
