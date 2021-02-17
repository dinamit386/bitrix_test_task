CREATE TABLE IF NOT EXISTS `t_list_products`
(
	`product_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`page_id` int(11) NOT NULL, 
	`product_price` float(11) NOT NULL,
	`product_description` mediumtext NOT NULL,
	`product_name` varchar(20) NOT NULL,
	`product_image` longblob NOT NULL
);