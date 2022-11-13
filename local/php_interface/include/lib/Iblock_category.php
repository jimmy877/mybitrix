<?php

namespace lib;

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use CIBlock;
use CModule;

/** перенести класс попробовать:
 * 1 в свой типа компонент https://thisis-blog.ru/kak-podklyuchit-klass-komponenta-v-drugom-meste-proekta/ с наследованием
 * 2 в папку include
 */
class Iblock_category
{
    private $I_BLOCK_CODE;
    private $I_BLOCK_ID;

    public function __construct($name)
    {
        return $this->I_BLOCK_CODE = $name;
    }

    private function GetIdBlock()
    {
        Loader::includeModule("iblock");
        $iblock = \Bitrix\Iblock\IblockTable::getList([
            "filter" => [
                "CODE" => $this->I_BLOCK_CODE,
            ],
            "select" => [
                "ID",
                "CODE",
                "NAME",
            ],
        ])->fetch();

        return $this->I_BLOCK_ID = $iblock['ID'];
    }

    public function getIblockCategory()
    {
        $result = [];
        $this->GetIdBlock();
        $entity = \Bitrix\Iblock\Model\Section::compileEntityByIblock($this->I_BLOCK_ID);

        $categories = $entity::getList([
            "filter" => [

                "IBLOCK_ID" => $this->I_BLOCK_ID,
                "ACTIVE" => "Y",
                "GLOBAL_ACTIVE" => "Y",
            ],

            "select" => [
                "ID",
                "NAME",
                "CODE",
            ],
        ]);

        while ($arSection = $categories->Fetch()) {
            $result[] = $arSection;
        }
        return $result;
    }

}