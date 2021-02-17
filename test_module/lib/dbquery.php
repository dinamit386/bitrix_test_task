<?php

$connection = \Bitrix\Main\Application::getConnection();
$sqlHelper = $connection->getSqlHelper();

if(isset($_POST['upload']))
	{
		$name = $_POST['product-name'];
		$price = $_POST['product-price'];
		$descr = $_POST['product-description'];
		$page_id = 1;

		if(!empty($_FILES['image']['tmp_name']))
		{
			$img = addslashes(file_get_contents($_FILES['image']['tmp_name']));
			$connection->query("INSERT INTO `t_list_products`(page_id, product_price, product_image, product_name, product_description) VALUES ('$page_id', '$price', '$img', '$name', '$descr')");
		}
	}

?>