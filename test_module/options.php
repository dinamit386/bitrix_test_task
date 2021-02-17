<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Bitrix\Main\SiteTable;

$module_id = 'test_module';

$dbqueryroot = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$module_id.'/lib/dbquery.php';
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$module_id.'/include.php');

function create_catalog_test_module()
{
   $ib = new CIBlock;

   $IBLOCK_TYPE = "s1";
   $SITE_ID = "s1";

   $contentGroupId = $this->GetGroupIdByCode("CONTENT");
   $editorGroupId = $this->GetGroupIdByCode("EDITOR");
   $ownerGroupId = $this->GetGroupIdByCode("OWNER");

   $arFields = Array(
      "ACTIVE" => "Y",
      "NAME" => "Каталог",
      "CODE" => "catalog",
      "IBLOCK_TYPE_ID" => $IBLOCK_TYPE,
      "SITE_ID" => $SITE_ID,
      "SORT" => "5",
         "PREVIEW_PICTURE" => array(
               "IS_REQUIRED" => "Y",
               "DEFAULT_VALUE" => array(
                  "SCALE" => "Y", 
                  "WIDTH" => "140",
                  "HEIGHT" => "140", 
                  "IGNORE_ERRORS" => "Y",
                  "METHOD" => "resample", 
                  "COMPRESSION" => "50", 
                  "FROM_DETAIL" => "Y", 
                  "DELETE_WITH_DETAIL" => "Y", 
                  "UPDATE_WITH_DETAIL" => "Y",
               ),
            "DETAIL_TEXT_TYPE" => array( 
               "DEFAULT_VALUE" => "html",
            ),
       ),
   );

      $ID = $ib->Add($arFields);
      if ($ID > 0)
      {
         echo "&mdash; инфоблок \"Каталог товаров\" успешно создан<br />";
      }
      else
      {
         echo "&mdash; ошибка создания инфоблока \"Каталог товаров\"<br />";
         return false;
      }
}

$aTabs = array(
   array(
      'DIV' => 'edit1',
      'TAB' => 'Добавить в каталог',
      'TITLE' => 'Добавьте элемент в каталог'
   ),
   array(
      'DIV' => 'edit2',
      'TAB' => 'Изменить или удалить',
      'TITLE' => 'Удалите или измените элемент каталога'
   )
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);

$tabControl->Begin();

?>

<form method="post" enctype="multipart/form-data">

<?$tabControl->BeginNextTab();?>
      <p>Выберите изображение товара</p><input type="hidden" name="size" value="1000000">
            <div>
               <input type="file" name="image"  style="width: 30vw">
            </div>
            <div>
               <input type="text" name="product-name" placeholder="Название"  style="width: 30vw">
            </div>
            <div>
               <input type="text" name="product-price" placeholder="Цена"  style="width: 30vw">
            </div>
            <div>
               <textarea name="product-description" cols="50" rows="4" placeholder="Описание продукта"  style="width: 30vw"></textarea>
            </div>
            <div>
               <input type="submit" name="upload" value="Добавить в каталог"  style="width: 30vw">
            </div>
</form>
<?$tabControl->BeginNextTab();?>


<form method="post" enctype="multipart/form-data">
   <p>Выберите товар для изменения списка</p>

         <p>Выберите изображение товара</p><input type="hidden" name="size" value="1000000">
            <div>
               <input type="file" name="image"  style="width: 30vw">
            </div>
            <div>
            <input type="text" name="product-id" placeholder="ID элемента"  style="width: 30vw">
            </div>
               <input type="text" name="product-name" placeholder="Название"  style="width: 30vw">
            </div>
            <div>
               <input type="text" name="product-price" placeholder="Цена"  style="width: 30vw">
            </div>
            <div>
               <textarea name="product-description" cols="50" rows="4" placeholder="Описание продукта"  style="width: 30vw"></textarea>
            </div>
            <div>
               <input type="submit" name="change" value="Изменить в каталоге"  style="width: 30vw">
            </div>
      </form>

<form method="post" enctype="multipart/form-data">
   <p>Введите ID элемента из каталога для удаления</p>
            <div>
               <input type="text" name="product-id" placeholder="ID элемента"  style="width: 30vw">
            </div>
            <div>
               <input type="submit" name="delete" value="Удалить из каталога"  style="width: 30vw">
            </div>
<?$tabControl->End();?>
</form>

<?php


$connection = \Bitrix\Main\Application::getConnection();
$sqlHelper = $connection->getSqlHelper();

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
   if(isset($_POST['upload']))
   {
      $name = $_POST['product-name'];
      $price = $_POST['product-price'];
      $descr = $_POST['product-description'];
      $page_id = 1;

      if(empty($name) || empty($price) || empty($descr))
      {
         echo "<p class='error' style='color: red; font-size: 18px; background-color: rgba(0,0,0, 0.2);'>Заполните все поля или добавьте изображение!</p>";
         return false;
      }
      else if(!is_float($price))
      {
         echo "<p class='error' style='color: red; font-size: 18px; background-color: rgba(0,0,0, 0.2);'>Введите цену через точку! Пример 10.00</p>";
         return false;
      }

      if(!empty($_FILES['image']['tmp_name']))
      {
         global $DB;

         $count = 0;

         $DB->query("SELECT COUNT(product_id) as count FROM `t_list_products`");

         $count_final = $count / 5;

         $page_id = $count_final;

         $img = addslashes(file_get_contents($_FILES['image']['tmp_name']));

         $DB->query("INSERT INTO `t_list_products`(page_id, product_price, product_image, product_name, product_description) VALUES ('$page_id', '$price', '$img', '$name', '$descr')");
         echo "<p class='success' style='color:green; font-size: 18px; background-color: rgba(0,0,0, 0.2);'>Элемент добавлен в базу данных!</p>";
      }
   }

   else if(isset($_POST['change']))
   {
      $id = $_POST['product-id'];
      $name = $_POST['product-name'];
      $price = $_POST['product-price'];
      $descr = $_POST['product-description'];

      if(empty($name) || empty($price) || empty($descr))
      {
         echo "<p class='error' style='color: red; font-size: 18px; background-color: rgba(0,0,0, 0.2);'>Заполните все поля или добавьте изображение!</p>";
         return false;
      }
      else if(!is_float($price))
      {
         echo "<p class='error' style='color: red; font-size: 18px; background-color: rgba(0,0,0, 0.2);'>Введите цену через точку! Пример 10.00</p>";
         return false;
      }

      if(!empty($_FILES['image']['tmp_name']))
      {
         global $DB;

         $img = addslashes(file_get_contents($_FILES['image']['tmp_name']));

         $DB->query("SELECT product_price, product_description, product_name, product_image FROM `t_list_products` WHERE product_id = $id");
         $DB->query("UPDATE `t_list_products` SET product_name = $name, product_image = $img, product_price = $price, product_description = $descr WHERE product_id = $id");
         echo "<p class='success' style='color:green; font-size: 18px; background-color: rgba(0,0,0, 0.2);'>Элемент добавлен в базу данных!</p>";
      }
   }
   else if(isset($_POST['delete']))
   {
      $id = $_POST['product-id'];

      $DB->query("DELETE from `t_list_products` where product_id=$id");
   }
}

?>

