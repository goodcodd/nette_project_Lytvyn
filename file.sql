CREATE database `etistudents` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

// 1 частина
CREATE TABLE `users` (
    `user_id` INT NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

// 2 частина
CREATE TABLE `Groups` (
    `group_id` INT NOT NULL AUTO_INCREMENT,
    `group_name` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

// 3 частина
CREATE TABLE user_groups (
     id SERIAL PRIMARY KEY,
     user_id INTEGER NOT NULL REFERENCES users(user_id),
     group_id INTEGER NOT NULL REFERENCES groups(group_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

// 4 частина
ALTER TABLE user_groups ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;
ALTER TABLE user_groups ADD CONSTRAINT fk_group_id FOREIGN KEY (group_id) REFERENCES groups(group_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

// 5 частина
CREATE TABLE `User_Details` (
    `user_id` INT NOT NULL,
    `user_name` VARCHAR(50) NOT NULL,
    `user_lastname` VARCHAR(50) NOT NULL,
    `user_dob` DATE NOT NULL,
    `user_course` INT NOT NULL,
    `user_speciality` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`user_id`),
    CONSTRAINT `fk_user_details_user_id`
        FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

// 6 частина
CREATE TABLE news
(
    `news_id` INT NOT NULL AUTO_INCREMENT,
    `news_title` VARCHAR(255) NOT NULL,
    `news_content` TEXT NOT NULL,
    `news_user_id` INT NOT NULL,
    `news_user_login` VARCHAR(64) NOT NULL,
    `news_date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `news_date_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`news_id`),
    CONSTRAINT `fk_news_user_id`
        FOREIGN KEY (`news_user_id`) REFERENCES `Users` (`user_id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


