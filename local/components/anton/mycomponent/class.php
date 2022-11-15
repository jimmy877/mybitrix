<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

class Mycomponent extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $result = $arParams;
        return $result;
    }

    private function _app()
    {
        global $APPLICATION;
        return $APPLICATION;
    }

    private function includeClass ($class_name){
        spl_autoload_register(function ($class_name){
            include __DIR__."/classes/".$class_name.".php";
            if (!class_exists($class_name, false)) {
                throw new LogicException("Unable to load class: $class_name");
            }
        });

        if (class_exists($class_name)) {
            return new $class_name();
        }
        return false;
    }

    public function executeComponent()
    {
        $APPLICATION = $this->_app();
        $url = trim($APPLICATION->GetCurUri(),"/");
        $url =  explode("/", $url);
        $count = count($url);
        if($count <= 1){
            $index = $this->includeClass("IndexPage");
            $index->Index();
            $componentPage = "template";
        }
        if($count > 1){

           $second = $this->includeClass("SecondPage");
           $second->Index();

            $componentPage = array_keys($this -> arParams["SEF_URL_TEMPLATES"])[1];
        }



        //$this->includeComponentTemplate($componentPage);
    }
}