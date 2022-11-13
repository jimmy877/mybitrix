<?php

use lib\Iblock_category;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */


$cats = new Iblock_category("news");


$arResult["myhuinya"] = $cats->getIblockCategory();

