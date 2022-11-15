<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

?>

<section class="who-area-are pad-90" id="about_us">
    <div class="container">
        <h2 class="title-1">Немного о нас</h2>

        <ul>
            <?foreach ($arResult["SECTIONS"] as $section):?>
                <li><a href="<?=$section["CODE"]?>/"><?=$section["NAME"] ?></a></li>
            <?endforeach;?>
        </ul>
            <div class="col-md-5">
                <div class="about-bg">
                    <img src="img/about/o_nas_text_block.jpg" alt="">
                </div>
            </div>
        </div>
    </div>
</section>
