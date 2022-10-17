<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Entity;


function getAliasIblockById( string $code): int
{
    Loader::includeModule("iblock");
    $iblock = \Bitrix\Iblock\IblockTable::getList([
        "filter" => [
            "CODE" => $code,
        ],
        "select" => [
            "ID",
            "CODE"
        ],
    ])->fetch();
    if(!isset ($iblock['ID']) ){
        throw new Exception("Не найден инфоблок с кодом {$code}");
    }
    return (int) $iblock["ID"];
}