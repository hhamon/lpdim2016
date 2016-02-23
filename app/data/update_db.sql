ALTER TABLE `blog_post` ADD contentMarkdown text;

ALTER TABLE `blog_post` CHANGE `content` `contentHTML` TEXT;