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

    <section class="service-area pt-90 pb-60 bg-color">
        <div class="container">

            <div class="row">
                <div class="section-heading text-center mb-70">
                    <?=$arResult["SECTION"]["PATH"][0]['DESCRIPTION']?>
                </div>
            </div>

            <div class="row">
                <? foreach ($arResult["ITEMS"] as $item):?>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="single-service brand-hover radius-4 mb-30 text-center">
                            <div class="service-icon">
                                <?=$item["DETAIL_TEXT"]?>
                            </div>
                            <div class="service-text">
                                <h3><?=$item["NAME"]?></h3>
                                <p><?=$item["PREVIEW_TEXT"]?></p>
                            </div>
                        </div>
                    </div>
                <?endforeach;?>
            </div>
        </div>
    </section>
<?php endif;?>