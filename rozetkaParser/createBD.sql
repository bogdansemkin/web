CREATE DATABASE IF NOT EXISTS `itemdb`;
CREATE TABLE IF NOT EXISTS `items`
(
	  `item_id` INT(3) UNSIGNED  NOT NULL AUTO_INCREMENT COMMENT 'id товара',
	  `item_query` VARCHAR(255) NOT NULL COMMENT 'текст запроса',
	  `item_title` VARCHAR(255) NOT NULL COMMENT 'название товара',
	  `item_uah` INT(11) UNSIGNED NOT NULL COMMENT 'цена гривны',
	  `item_link` VARCHAR(255) NOT NULL COMMENT 'линк на описание товара',
	  `item_date` DATE NOT NULL COMMENT 'дата выполнения запроса',
		CONSTRAINT ixIdItem PRIMARY KEY (item_id),
		CONSTRAINT ixTitle UNIQUE KEY (item_title)  COMMENT 'уникальный ключ названия',
		INDEX ixQuery(item_query)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
