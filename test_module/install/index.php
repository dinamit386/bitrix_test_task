<?php

IncludeModuleLangFile(__FILE__);

Class test_module extends CModule
{
    const MODULE_ID = "test_module";

    var $MODULE_ID = "test_module";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $MODULE_GROUP_RIGHTS = "Y";

    function __construct()
    {
        $arModuleVersion = array();

        include(__DIR__.'/version.php');

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->MODULE_NAME = 'Модуль загрузки в каталог';
        $this->MODULE_DESCRIPTION = 'Модуль загружает в каталог товар';
    }


    function InstallDB($arParams = Array())
    {
        global $DB, $DBType;

        var $mod_name

        $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/test_module/install/db/mysql/install.sql'); //test_module заменить на название папки с вашим модулем

        RegisterModule("test_module");

        return true;
    }

    function UnInstallDB($arParams = Array())
    {
        global $DB, $DBType;

        if($_REQUEST["savedata"] !='Y')
        {
            $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/test_module/install/db/mysql/uninstall.sql'); //test_module заменить на название папки с вашим модулем
        }

        UnRegisterModule("test_module");

        return true;
    }

    function InstallEvents()
    {
        return true;
    }

    function UnInstallEvents()
    {
        return true;
    }

    function InstallFiles()
    {

        return true;
    }

    function UnInstallFiles()
    {
        return true;
    }

    function DoInstall()
    {
        $this->InstallFiles();
        $this->InstallDB(false);
    }

    function DoUninstall()
    {
        $this->UnInstallDB(false);
    }
}
?>