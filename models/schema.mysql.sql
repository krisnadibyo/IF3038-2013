/* Recreate table if exists */
/* Run "/tests/task.php" to re-seed data */ 
DROP TABLE IF EXISTS `task_tag`;
DROP TABLE IF EXISTS `comment`;
DROP TABLE IF EXISTS `task`;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `category`;
DROP TABLE IF EXISTS `tag`;

CREATE TABLE `user`(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(96) NOT NULL,
    `username` VARCHAR(96) NOT NULL,
    `password` VARCHAR(32) NOT NULL,
    `email` VARCHAR(128) NOT NULL,
    `birthday` DATE NOT NULL,
    `avatar` VARCHAR(128) NOT NULL,
    `bio` TEXT NOT NULL
);

CREATE INDEX `user_email_idx` ON `user` (`email`) USING BTREE;
CREATE INDEX `user_name_idx` ON `user` (`name`) USING BTREE;

CREATE TABLE `category`(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(32) NOT NULL
);

CREATE INDEX `category_name_idx` ON `category` (`name`) USING BTREE;

CREATE TABLE `task`(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(32) NOT NULL,
    `attachment` VARCHAR(255) NOT NULL,
    `deadline` DATE NOT NULL,

    /* FKs */
    `user_id` INTEGER NOT NULL,
    `assignee_id` INTEGER DEFAULT NULL,
    `category_id` INTEGER NOT NULL,
    CONSTRAINT `fk_task_user_id` FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_task_assignee_id` FOREIGN KEY (`assignee_id`)
        REFERENCES `user` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_task_category_id` FOREIGN KEY (`category_id`)
        REFERENCES `category` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE INDEX `task_name_idx` ON `task` (`name`) USING BTREE;
CREATE INDEX `task_user_id_idx` ON `task` (`user_id`) USING BTREE;
CREATE INDEX `task_category_id_idx` ON `task` (`category_id`) USING BTREE;

CREATE TABLE `comment`(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `content` VARCHAR(255) NOT NULL,

    /* FKs */
    `task_id` INTEGER NOT NULL,
    CONSTRAINT `fk_comment_task_id` FOREIGN KEY (`task_id`)
        REFERENCES `task` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE `tag`(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(32) NOT NULL
);

CREATE TABLE `task_tag`(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,

    /* FKs */
    `task_id` INTEGER NOT NULL,
    `tag_id` INTEGER NOT NULL,
    CONSTRAINT `fk_task_tag_task_id` FOREIGN KEY (`task_id`)
        REFERENCES `task` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_task_tag_tag_id` FOREIGN KEY (`tag_id`)
        REFERENCES `tag` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
);

/* For testing purpose */
CREATE TABLE IF NOT EXISTS `hello`(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
    `msg` VARCHAR(255)
);
