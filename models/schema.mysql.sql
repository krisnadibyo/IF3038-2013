/* Recreate table if exists */
/* Run "/tests/task.php" to re-seed data */
DROP TABLE IF EXISTS `tbl_task_tag`;
DROP TABLE IF EXISTS `tbl_comment`;
DROP TABLE IF EXISTS `tbl_task`;
DROP TABLE IF EXISTS `tbl_category`;
DROP TABLE IF EXISTS `tbl_user`;
DROP TABLE IF EXISTS `tbl_tag`;
DROP TABLE IF EXISTS `tbl_hello`;

CREATE TABLE `tbl_user`(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(96) NOT NULL,
    `username` VARCHAR(96) NOT NULL UNIQUE,
    `password` VARCHAR(32) NOT NULL,
    `email` VARCHAR(128) NOT NULL UNIQUE,
    `birthday` DATE NOT NULL,
    `avatar` VARCHAR(128) NOT NULL,
    `bio` TEXT NOT NULL
);

CREATE INDEX `user_username_idx` ON `tbl_user` (`username`) USING BTREE;
CREATE INDEX `user_email_idx` ON `tbl_user` (`email`) USING BTREE;
CREATE INDEX `user_name_idx` ON `tbl_user` (`name`) USING BTREE;

CREATE TABLE `tbl_category`(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(32) NOT NULL,
    `user_id` INTEGER NOT NULL,
    CONSTRAINT `fk_category_user_id` FOREIGN KEY (`user_id`)
        REFERENCES `tbl_user` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE KEY `name_user_id` (`name`, `user_id`)
);

CREATE INDEX `category_name_idx` ON `tbl_category` (`name`) USING BTREE;

CREATE TABLE `tbl_task`(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(32) NOT NULL,
    `attachment` VARCHAR(255) NOT NULL,
    `deadline` DATE NOT NULL,
    `status` INTEGER DEFAULT 0,

    /* FKs */
    `user_id` INTEGER NOT NULL,
    `assignee_id` INTEGER DEFAULT NULL,
    `category_id` INTEGER NOT NULL,
    CONSTRAINT `fk_task_user_id` FOREIGN KEY (`user_id`)
        REFERENCES `tbl_user` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_task_assignee_id` FOREIGN KEY (`assignee_id`)
        REFERENCES `tbl_user` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_task_category_id` FOREIGN KEY (`category_id`)
        REFERENCES `tbl_category` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE INDEX `task_name_idx` ON `tbl_task` (`name`) USING BTREE;
CREATE INDEX `task_user_id_idx` ON `tbl_task` (`user_id`) USING BTREE;
CREATE INDEX `task_category_id_idx` ON `tbl_task` (`category_id`) USING BTREE;

CREATE TABLE `tbl_comment`(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `content` VARCHAR(255) NOT NULL,

    /* FKs */
    `task_id` INTEGER NOT NULL,
    CONSTRAINT `fk_comment_task_id` FOREIGN KEY (`task_id`)
        REFERENCES `tbl_task` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE `tbl_tag`(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(32) NOT NULL UNIQUE
);

CREATE INDEX `tag_name_idx` ON `tbl_tag` (`name`) USING BTREE;

CREATE TABLE `tbl_task_tag`(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,

    /* FKs */
    `task_id` INTEGER NOT NULL,
    `tag_id` INTEGER NOT NULL,
    CONSTRAINT `fk_task_tag_task_id` FOREIGN KEY (`task_id`)
        REFERENCES `tbl_task` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_task_tag_tag_id` FOREIGN KEY (`tag_id`)
        REFERENCES `tbl_tag` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE KEY `task_id_tag_id` (`task_id`, `tag_id`)
);

CREATE INDEX `task_tag_task_id_idx` ON `tbl_task_tag` (`task_id`) USING BTREE;
CREATE INDEX `task_tag_tag_id_idx` ON `tbl_task_tag` (`tag_id`) USING BTREE;

/* For testing purpose */
CREATE TABLE IF NOT EXISTS `tbl_hello`(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
    `msg` VARCHAR(255)
);

