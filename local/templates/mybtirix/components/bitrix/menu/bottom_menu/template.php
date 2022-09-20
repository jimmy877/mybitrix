<?php if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<ul class="main-menu text-right">
    <?php foreach ($arResult as $arItem): ?>

            <?php if($arItem["SELECTED"]):?>
                <li>
                    <a href="<?=$arItem["LINK"]?>" style="color:#829fde"><?= $arItem["TEXT"]?></a>
                </li>
            <?php else:?>
                <li>
                    <a href="<?=$arItem["LINK"]?>" ><?=$arItem["TEXT"]?></a>
                </li>
            <?php endif;?>

    <?php endforeach; ?>
</ul>

<!--         <li>
            <a href="services.html"> Услуги
                <span class="indicator"><i class="fa fa-angle-down"></i></span></a>
            <ul class="dropdown">
                <li>
                    <a href="services_landing.html">Лендинг</a>
                </li>
                <li>
                    <a href="services_online_shop.html">Интернет-магазин</a>
                </li>
            </ul>
        </li> -->