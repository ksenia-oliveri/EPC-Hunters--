-- Вариант оптимизации таблиц 
CREATE TABLE `info` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) DEFAULT NULL,  -- Оптимизируем длину
    `desc` TEXT DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4_general_ci; -- Меняем кодировку на utf8mb4 для поддержки всех символов unicode

CREATE TABLE `data` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `date` DATE DEFAULT NULL,
    `value` INT DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4_general_ci;

CREATE TABLE `link` (
    `data_id` INT UNSIGNED NOT NULL,
    `info_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`data_id`, `info_id`),  --Устанавливаем составной первичный ключ
    INDEX (`info_id`),                   --Дополнительный индекс для ускорения поиска по info_id
    CONSTRAINT `fk_link_data` FOREIGN KEY (`data_id`) REFERENCES `data` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_link_info` FOREIGN KEY (`info_id`) REFERENCES `info` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Вариант оптимизации запроса
SELECT *
FROM data
JOIN link ON link.data_id = data.id
JOIN info ON link.info_id = info.id;
