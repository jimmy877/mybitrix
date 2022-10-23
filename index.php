<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle("Главная");
$APPLICATION->SetPageProperty("isMain","xzx");
$APPLICATION->SetPageProperty("title","index");
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"mainpage_top_slider", 
	array(
		"ACTIVE_DATE_FORMAT" => "",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "N",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "ID",
			1 => "CODE",
			2 => "NAME",
			3 => "SORT",
			4 => "PREVIEW_TEXT",
			5 => "PREVIEW_PICTURE",
			6 => "DETAIL_TEXT",
			7 => "",
		),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "1",
		"IBLOCK_TYPE" => "Main_page",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "N",
		"MEDIA_PROPERTY" => "",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "10",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(
			0 => "link",
			1 => "",
		),
		"SEARCH_PAGE" => "/search/",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SLIDER_PROPERTY" => "",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "",
		"STRICT_SECTION_CHECK" => "N",
		"TEMPLATE_THEME" => "",
		"USE_RATING" => "N",
		"USE_SHARE" => "N",
		"COMPONENT_TEMPLATE" => "mainpage_top_slider"
	),
	false
);?>

<!-- О нас -->
        <section class="who-area-are pad-90" id="about_us">
            <div class="container">
                <h2 class="title-1">Немного о нас</h2>
                <div class="row">
                    <div class="col-md-7">
                        <div class="who-we">
                            <p>Мы в <b>WeCoders</b> считаем, что любую задачу, даже которая кажется «невозможной» можно
                                решить, чем мы успешно и занимаемся! У нас собрались только творческие и ответственные
                                люди,которым под силу решить любую проблему в сфере digital, чтобы помочь другим бизнесам
                                достичь своих целей. </p>
                            <p>Используя накопленный за многие годы опыт работы с крупными мировыми корпорациями, мы создаем
                                творческие инновации, которые обеспечивают реальные результаты. Прокрутите вниз, чтобы узнать
                                немного о том,
                                <a href="#team">кто все это делает</a>
                                .
                            </p>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="about-bg">
                            <img src="img/about/o_nas_text_block.jpg" alt=""/>
                        </div>
                    </div>
                </div>
            </div>
        </section>


<?$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "mainpage_main_activities",
    array(
        "ACTIVE_DATE_FORMAT" => "",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "N",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "N",
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => array(
            0 => "ID",
            1 => "CODE",
            2 => "NAME",
            3 => "SORT",
            4 => "PREVIEW_TEXT",
            5 => "DETAIL_TEXT",
            7 => "",
        ),
        "FILTER_NAME" => "",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => getAliasIblockById("main_activities"),
        "IBLOCK_TYPE" => "Main_page",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "N",
        "MEDIA_PROPERTY" => "",
        "MESSAGE_404" => "",
        "NEWS_COUNT" => "3",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => "",
        "PAGER_TITLE" => "",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "main_page",
        "PREVIEW_TRUNCATE_LEN" => "",
        "PROPERTY_CODE" => [

        ],
        "SEARCH_PAGE" => "/search/",
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SLIDER_PROPERTY" => "",
        "SORT_BY1" => "SORT",
        "SORT_BY2" => "",
        "SORT_ORDER1" => "ASC",
        "SORT_ORDER2" => "",
        "STRICT_SECTION_CHECK" => "N",
        "TEMPLATE_THEME" => "",
        "USE_RATING" => "N",
        "USE_SHARE" => "N",
        "COMPONENT_TEMPLATE" => "mainpage_top_slider"
    ),
    false
);?>


<?$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "mainpage_infografics",
    array(
        "ACTIVE_DATE_FORMAT" => "",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "N",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "N",
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => array(
            0 => "ID",
            1 => "CODE",
            2 => "NAME",
            3 => "SORT",
            4 => "PREVIEW_TEXT",
            5 => "DETAIL_TEXT",
            7 => "",
        ),
        "FILTER_NAME" => "",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => getAliasIblockById("infografics"),
        "IBLOCK_TYPE" => "Main_page",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "N",
        "MEDIA_PROPERTY" => "",
        "MESSAGE_404" => "",
        "NEWS_COUNT" => "4",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => "",
        "PAGER_TITLE" => "",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "main_page",
        "PREVIEW_TRUNCATE_LEN" => "",
        "PROPERTY_CODE" => [

        ],
        "SEARCH_PAGE" => "/search/",
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SLIDER_PROPERTY" => "",
        "SORT_BY1" => "SORT",
        "SORT_BY2" => "",
        "SORT_ORDER1" => "ASC",
        "SORT_ORDER2" => "",
        "STRICT_SECTION_CHECK" => "N",
        "TEMPLATE_THEME" => "",
        "USE_RATING" => "N",
        "USE_SHARE" => "N",
        "COMPONENT_TEMPLATE" => "mainpage_top_slider"
    ),
    false
);?>

<div>fffdfdfdf</div>

<?
    require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>