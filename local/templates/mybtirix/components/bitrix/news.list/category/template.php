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
$this->setFrameMode(true);
$this->addExternalCss("/bitrix/css/main/bootstrap.css");
$this->addExternalCss("/bitrix/css/main/font-awesome.css");
$this->addExternalCss($this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css');
?>


<?php if ($arResult["ITEMS"] != null):?>
    <section class="project-count-area brand-bg pad-90">
        <div class="container">
            <div class="row">

                <? foreach ($arResult["ITEMS"] as $item):?>
                    <div class="col-md-3 col-sm-3">
                        <div class="single-count white-text text-center">
                            <?=$item["DETAIL_TEXT"]?>
                            <h2 class="counter"><?=$item["PREVIEW_TEXT"]?></h2>
                            <p><?=$item["NAME"]?></p>
                        </div>
                    </div>
                <?endforeach;?>

            </div>
        </div>
    </section>
    <pre><?php //print_r($arResult);?></pre>

<?php endif;?>