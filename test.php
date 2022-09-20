<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Context;
use \Bitrix\Main\Grid\Panel\Actions;
use \Bitrix\Main\Grid\Panel\Snippet\Onchange;
use \Bitrix\Main\Loader;
use \Onetouch\Paymentshistory\PaymentsHistory;
use \Onetouch\Paymentshistory\PaymentsHistoryTable;
use \Onetouch\Paymentshistory\FilterHelper;

global $APPLICATION;

Loader::includeModule('ui');
Loader::includeModule('onetouch.paymentshistory');

$request = Context::getCurrent()->getRequest();
// Удаление элемента(ов)
/*echo "<pre>";
print_r($request['ID']);
exit();*/

if( !empty($request['ID']) && in_array('1',$USER-> GetUserGroupArray()) /*&& check_bitrix_sessid()*/ ) {
    if($request['action'] == 'delete') {
        PaymentsHistoryTable::delete($request['ID']);
        die();
    } elseif($request['action_button_paymentshistory_list'] == 'delete') {
        $idList = $request['ID'];

        if($request['action_button_paymentshistory_list'] == 'Y') {
            $idList = PaymentsHistoryTable::getList([
                'select' => ['ID']
            ])->fetchAll();
        }

        foreach ($idList as $itemID) {
            $id = is_array($itemID) ? $itemID['ID'] : $itemID;
            PaymentsHistoryTable::delete($id);
        }
    }
}

$parentEntityType = empty($arResult['ENTITY_TYPE']) ? 'ALL' : $arResult['ENTITY_TYPE'];
$parentEntityID = empty($arResult['ENTITY_ID']) ? null : $arResult['ENTITY_ID'];
$export = $request['export'];

if(empty($export)) {
    CJSCore::Init(['jquery']);
}

// Опции грида, фильтры и т.п.
$grid_options = new Bitrix\Main\Grid\Options('paymentshistory_list');
$sort = $grid_options->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
$nav_params = $grid_options->GetNavParams();

$nav = new Bitrix\Main\UI\PageNavigation('paymentshistory_list');
$nav->allowAllRecords(true)
    ->setPageSize($nav_params['nPageSize'])
    ->initFromUri();

$filterOption = new Bitrix\Main\UI\Filter\Options('paymentshistory_filter');
$filterData = $filterOption->getFilter([]);
$filter = [];

// Системный фильтр
if(!empty($parentEntityID) && $parentEntityType == CCrmOwnerType::DealName) {
    $filter['DEAL_ID'] = $parentEntityID;
} elseif(!empty($parentEntityID) && $parentEntityType == CCrmOwnerType::ContactName) {
    $dealForFilterInfo = \Bitrix\Crm\DealTable::getList([
        'filter' => [
            'CONTACT_ID' => $parentEntityID,
        ],
        'select' => [
            "ID",
        ]
    ])->fetchAll();

    if(!empty($dealForFilterInfo)) {
        $filter['DEAL_ID'] = [];
        foreach ($dealForFilterInfo as $dealForFilterItem) {
            $filter['DEAL_ID'][]=$dealForFilterItem['ID'];
        }
    }
} elseif($parentEntityType == 'ALL') {

    // фильтры
    FilterHelper::getNumberField('ID', $filterData, $filter);
    FilterHelper::getSelectField('ENTITY_TYPE', $filterData, $filter);
    FilterHelper::getStringField('ENTITY_NAME', $filterData, $filter);
    FilterHelper::getNumberField('AMOUNT', $filterData, $filter);
    FilterHelper::getUserField('USER_ID', $filterData, $filter);
    FilterHelper::getDateField('TIME_ADDED', $filterData, $filter);

    // псевдофильтры (поля, которых не существует в БД)
    FilterHelper::getStringField('DEAL_NAME', $filterData, $filter);
    FilterHelper::getStringField('CONTACT_NAME', $filterData, $filter);

    if(isset($filter['CONTACT_NAME']) || isset($filter['DEAL_NAME'])) {

        $filterDealIdList = [];
        $firstFilterApplied = false;

        // CONTACT_ID
        if(isset($filter['CONTACT_NAME'])) {

            $contactsForFilterInfo = \Bitrix\Crm\ContactTable::getList([
                'filter' => [
                    'FULL_NAME' => $filter['CONTACT_NAME'],
                ],
                'select' => [
                    "ID",
                ]
            ])->fetchAll();

            $filterContactIdList = [];
            if(!empty($contactsForFilterInfo)) {
                foreach ($contactsForFilterInfo as $contactsForFilterItem) {
                    $filterContactIdList[]=$contactsForFilterItem['ID'];
                }
            }

            if(!empty($filterContactIdList)) {
                $dealForFilterInfo = \Bitrix\Crm\DealTable::getList([
                    'filter' => [
                        'CONTACT_ID' => $filterContactIdList,
                    ],
                    'select' => [
                        "ID",
                    ]
                ])->fetchAll();

                if(!empty($dealForFilterInfo)) {
                    foreach ($dealForFilterInfo as $dealForFilterItem) {
                        $filterDealIdList[]=$dealForFilterItem['ID'];
                    }
                }
            }

            unset($filter['CONTACT_NAME']);
            $firstFilterApplied = true;
        }

        // DEAL_NAME
        if(isset($filter['DEAL_NAME'])) {
            $dealForFilterInfoFilter = [
                'TITLE' => $filter['DEAL_NAME'],
            ];

            if($firstFilterApplied) {
                $dealForFilterInfoFilter['ID'] = $filterDealIdList;
            }

            $dealForFilterInfo = \Bitrix\Crm\DealTable::getList([
                'filter' => $dealForFilterInfoFilter,
                'select' => [
                    "ID",
                ]
            ])->fetchAll();

            $filterDealIdList = [];
            if(!empty($dealForFilterInfo)) {
                foreach ($dealForFilterInfo as $dealForFilterItem) {
                    $filterDealIdList[]=$dealForFilterItem['ID'];
                }
            }

            unset($filter['DEAL_NAME']);
            $firstFilterApplied = true;
        }

        $filter['DEAL_ID'] = $filterDealIdList;
    }

}

$itemsCount = PaymentsHistoryTable::getCount($filter);
$nav->setRecordCount($itemsCount);

// Кнопка удаления
$onchangeDel = new Onchange();
$onchangeDel->addAction(
    [
        'ACTION' => Actions::CALLBACK,
        'CONFIRM' => true,
        'CONFIRM_APPLY_BUTTON'  => 'Подтвердить',
        'DATA' => [
            ['JS' => 'Grid.removeSelected()']
        ]
    ]
);
$onchangeForAll = new Onchange();
$onchangeForAll->addAction(array("ACTION" => Actions::CALLBACK, "DATA" => array(array("JS" => "Grid.confirmForAll()"))));

$asdsadasdasd = [
    'filter' => $filter,
    'select' => [
        "*",
    ],
];

if(empty($export)) {
    $asdsadasdasd['offset'] = $nav->getOffset();
    $asdsadasdasd['limit'] = $nav->getLimit();
    $asdsadasdasd['order'] = $sort['sort'];
}
// Получение данных
$dataList = PaymentsHistoryTable::getList($asdsadasdasd)->fetchAll();
//$dataList = PaymentsHistoryTable::getList()->fetchAll();
//тут код отвечающий за вывод в список инфы
$iBlockProperties = [];

$list = [];
$dealInfo = null;
$paymentTotal = [];
$myItem =[];
/*echo "<pre>";
var_dump($dataList);
die();*/
foreach ($dataList as $dataItem) {

    $dealName = '';
    $contactName = '';
    $userName = '';
    $entityTypeStr = '';
    $entityName = '';
    $paymentType = '';
    $opportunity = 0;

    $contactInfo = null;
    $userInfo = null;

    if(!empty($dataItem['DEAL_ID'])) {
        $dealInfo = \Bitrix\Crm\DealTable::getById($dataItem['DEAL_ID'])->fetch();

        if(!empty($dealInfo)) {
            $dealName = '<a href = "/crm/deal/details/'.$dataItem['DEAL_ID'].'/">'.$dealInfo['TITLE'].'</a>';
            $dealNamePlane = $dealInfo['TITLE'];
        } else {
            $dealName = $dataItem['DEAL_ID'];
        }
    }

    if(!empty($dataItem['USER_ID'])) {
        $userInfo = \Bitrix\Main\UserTable::getById($dataItem['USER_ID'])->fetch();
        if(!empty($userInfo)) {
            $userName = '<a href = "/company/personal/user/'.$dataItem['USER_ID'].'/">'.$userInfo['LAST_NAME'].' '.$userInfo['NAME'].'</a>';
            $userNamePlane = $userInfo['LAST_NAME'].' '.$userInfo['NAME'];
        } else {
            $userName = 'Неизвестно ('.$dataItem['USER_ID'].')';
        }
    }

    if(!empty($dealInfo['CONTACT_ID'])) {
        $contactInfo = \Bitrix\Crm\ContactTable::getById($dealInfo['CONTACT_ID'])->fetch();

        if(!empty($contactInfo)) {
            $contactName = '<a href = "/crm/contact/details/'.$dealInfo['CONTACT_ID'].'/">'.$contactInfo['FULL_NAME'].'</a>';
            $contactNamePlane = $contactInfo['FULL_NAME'];
        } else {
            $contactName = 'Неизвестно ('.$dealInfo['CONTACT_ID'].')';
        }
    }

    if($dataItem['ENTITY_TYPE'] == PaymentsHistory::ENTITY_TYPE_CUSTOM) {
        $entityTypeStr = 'Другое';
        $entityName = $dataItem['ENTITY_NAME'];
    } else if($dataItem['ENTITY_TYPE'] == PaymentsHistory::ENTITY_TYPE_PRODUCT) {
        $entityTypeStr = 'Товар';
        $entityName = $dataItem['ENTITY_NAME'];
    } else if($dataItem['ENTITY_TYPE'] == PaymentsHistory::ENTITY_TYPE_SPECIFICATION) {
        $entityTypeStr = 'Спецификация';
        $entityName = $dataItem['ENTITY_NAME'];
    } else if($dataItem['ENTITY_TYPE'] == PaymentsHistory::ENTITY_TYPE_DEAL) {
        $entityTypeStr = 'Предмет сделки';
        $entityName = $dataItem['ENTITY_NAME'];
    } else {
        $entityTypeStr = $dataItem['ENTITY_TYPE'];
        $entityName = empty($dataItem['ENTITY_NAME']) ? $dataItem['ENTITY_ID'] : $dataItem['ENTITY_NAME'];
    }

    $parentEntityInfoStr = '';
    if($parentEntityType != 'ALL') {
        $parentEntityInfoStr = '&PARENT_ENTITY_TYPE='.$parentEntityType.'&PARENT_ENTITY_ID='.$parentEntityID;
    }

    if($dataItem['PAYMENT_TYPE'] == 1) {
        $paymentType = 'Наличка';
    } else if($dataItem['PAYMENT_TYPE'] == 2) {
        $paymentType = 'Расчетный счет';
    } else if($dataItem['PAYMENT_TYPE'] == 3) {
        $paymentType = 'Карта';
    }

    if(!empty($dealInfo['OPPORTUNITY'])) {
        $opportunity = $dealInfo['OPPORTUNITY'];

        if(!isset($paymentTotal[$dataItem['DEAL_ID']])) {
            $paymentTotal[$dataItem['DEAL_ID']] = 0;
        }
        if($asdsadasdasd['offset']!=0){
            $myItem['myAmount'] = PaymentsHistoryTable::getList([
                "select"=>["AMOUNT"],
                "filter"=>["=DEAL_ID"=>$dataItem['DEAL_ID']]
            ])->fetchAll();
            if(is_array($myItem['myAmount'])){
                (int)$summ=0;
                foreach ($myItem['myAmount'] as $i){
                    $summ +=(int)$i["AMOUNT"];
                }
                $paymentTotal[$dataItem['DEAL_ID']] =(int)$summ;
            }else{
                $paymentTotal[$dataItem['DEAL_ID']] += $dataItem['AMOUNT'];
            }

        }else{
            $paymentTotal[$dataItem['DEAL_ID']] += $dataItem['AMOUNT'];
        }

    }
    $list[]= [
        'data' => [
            'ID' => $dataItem['ID'],
            'DEAL_ID' => $dataItem['DEAL_ID'],
            'DEAL' => $dealName,
            'DEAL_plane' => $dealNamePlane,
            'CONTACT' => $contactName,
            'CONTACT_plane' => $contactNamePlane,
            'ENTITY_TYPE' => $entityTypeStr,
            'ENTITY' => $entityName,
            'PAYMENT_TYPE' => $paymentType,
            'AMOUNT' => $dataItem['AMOUNT'],
            'REMAINS' => 0,
            'OPPORTUNITY' => $opportunity,
            'USER' => $userName,
            'USER_plane' => $userNamePlane,
            'TIME_ADDED' => $dataItem['TIME_ADDED']->toString(),
        ],
        'actions' => [
            [
                'text'    => 'Редактировать',
                'onclick' => 'document.location.href="/payment_history/edit.php?ID='.$dataItem['ID'].$parentEntityInfoStr.'"'
            ],
            [
                'text'    => 'Удалить',
                'onclick' => 'delElement('.$dataItem['ID'].')'
            ]
        ],
    ];

}
foreach ($list as $listKey => $listItem) {
    $paymentRemains = 0;

    $list[$listKey]['data']['REMAINS'] = $list[$listKey]['data']['OPPORTUNITY'] - $paymentTotal[$list[$listKey]['data']['DEAL_ID']];

}
/*echo "<pre>";
print_r($list);
die();*/
// столбцы грида
$paymentsHistoryColumns = [];

$paymentsHistoryColumns []= ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true];

if($parentEntityType == CCrmOwnerType::ContactName || $parentEntityType == 'ALL') {
    $paymentsHistoryColumns []= ['id' => 'DEAL', 'name' => 'Сделка', 'sort' => 'DEAL_ID', 'default' => true];
}
if($parentEntityType == 'ALL') {
    $paymentsHistoryColumns []= ['id' => 'CONTACT', 'name' => 'Контакт', 'default' => true];
}

$paymentsHistoryColumns []= ['id' => 'ENTITY_TYPE', 'name' => 'Тип', 'sort' => 'ENTITY_TYPE', 'default' => true];
$paymentsHistoryColumns []= ['id' => 'ENTITY', 'name' => 'Услуга / Товар', 'sort' => 'ENTITY_ID', 'default' => true];
$paymentsHistoryColumns []= ['id' => 'PAYMENT_TYPE', 'name' => 'Тип оплаты', 'sort' => 'PAYMENT_TYPE', 'default' => true];
$paymentsHistoryColumns []= ['id' => 'AMOUNT', 'name' => 'Сумма, руб.', 'sort' => 'AMOUNT', 'default' => true];
$paymentsHistoryColumns []= ['id' => 'REMAINS', 'name' => 'Остаток, руб.', 'default' => true];
$paymentsHistoryColumns []= ['id' => 'USER', 'name' => 'Добавил(а)', 'sort' => 'USER_ID', 'default' => true];
$paymentsHistoryColumns []= ['id' => 'TIME_ADDED', 'name' => 'Время добавления', 'sort' => 'TIME_ADDED', 'default' => true];

//Экспорт
if($export === 'excel') {
    if(!CModule::IncludeModule("nkhost.phpexcel")){
        exit;
    }

    global $PHPEXCELPATH;
    require_once ($PHPEXCELPATH . '/PHPExcel.php');
    require_once ($PHPEXCELPATH . '/PHPExcel/Writer/Excel2007.php');

    $exportRows = [];
    $cellNumbers = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O'];

    // заголовки
    $exportCells = [];
    foreach($paymentsHistoryColumns as $paymentsHistoryColumnKey => $paymentsHistoryColumn) {
        $cellID = $cellNumbers[$paymentsHistoryColumnKey].'1';
        $exportCells[$cellID]= $paymentsHistoryColumn['name'];
    }
    $exportRows[]= $exportCells;

    foreach($list as $listItemKey => $listItem) {
        // перебираем поля у элемента
        $exportCells = [];
        foreach($paymentsHistoryColumns as $paymentsHistoryColumnKey => $paymentsHistoryColumn) {
            foreach($listItem['data'] as $listFieldKey => $listFieldItem) {
                // находим поле под нужным нам ID и записываем
                if($listFieldKey == $paymentsHistoryColumn['id']) {

                    $cellID = $cellNumbers[$paymentsHistoryColumnKey].($listItemKey+2);

                    if(array_key_exists($listFieldKey.'_plane', $listItem['data'])) {
                        $exportCells[$cellID]= $listItem['data'][$listFieldKey.'_plane'];
                    } else {
                        $exportCells[$cellID]= $listFieldItem;
                    }

                }

            }
        }
        $exportRows[]= $exportCells;
    }

    // Создание таблицы
    $xls = new PHPExcel();
    $xls->setActiveSheetIndex(0);
    $sheet = $xls->getActiveSheet();
    $sheet->setTitle('export');

    foreach($exportRows as $rowNumber => $exportCells) {
        foreach($exportCells as $cellNumber => $cellItem) {
            $sheet->setCellValueExplicit($cellNumber, $cellItem, PHPExcel_Cell_DataType::TYPE_STRING);
        }
    }

    header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=payments.xls");

    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');

    exit;
}

// Вывод
if($parentEntityType == 'ALL') {
    $paymentsHistoryFilterFields = [];
    $paymentsHistoryFilterFields []= ['id' => 'ID', 'name' => 'ID', 'type' => 'number'];
    $paymentsHistoryFilterFields []= ['id' => 'DEAL_NAME', 'name' => 'Название сделки'];
    $paymentsHistoryFilterFields []= ['id' => 'CONTACT_NAME', 'name' => 'Имя контакта'];
    $paymentsHistoryFilterFields []= ['id' => 'ENTITY_TYPE', 'name' => 'Тип', 'type' => 'list', 'items' => ['1' => 'Другое', '2' => 'Продукт', '3' => 'Спецификация'], 'params' => ['multiple' => 'Y']];
    $paymentsHistoryFilterFields []= ['id' => 'ENTITY_NAME', 'name' => 'Название услуги / товара'];
    //$paymentsHistoryFilterFields []= ['id' => 'PAYMENT_TYPE', 'name' => 'Тип оплаты', 'type' => 'list', 'items' => ['1' => 'Наличка', '2' => 'Расчетный счет', '3' => 'Карта']];
    //$paymentsHistoryFilterFields []= ['id' => 'DEAL_TYPE', 'name' => 'Тип сделки'];
    $paymentsHistoryFilterFields []= ['id' => 'AMOUNT', 'name' => 'Сумма, руб.', 'type' => 'number'];
    $paymentsHistoryFilterFields []= ['id' => 'USER_ID', 'name' => 'Пользователь', 'type' => 'dest_selector'];
    $paymentsHistoryFilterFields []= ['id' => 'TIME_ADDED', 'name' => 'Время добавления', 'type' => 'date'];

    $APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
        'FILTER_ID' => 'paymentshistory_filter',
        'GRID_ID' => 'paymentshistory_list',
        'FILTER' => $paymentsHistoryFilterFields,
        'ENABLE_LIVE_SEARCH' => true,
        'ENABLE_LABEL' => true,
        'DISABLE_SEARCH' => true
    ]);
}

?>


<script>
    function delElement(elementId) {
        if(confirm("Вы уверены, что хотите удалить элемент?")) {
            let delUrl = "/payment_history?undefined&ENTITY_TYPE=undefined&ENTITY_ID=undefined&action=delete&ID=" + elementId;
            $.ajax({
                url: delUrl,
                method: 'get',
            });
            let line = document.querySelector("[data-id='"+elementId+"']");
            line.remove();
        }
    }

    $("#workarea-content").css("background-color", "transparent");
</script> <? if($parentEntityType == 'DEAL') : ?> <? $addUrlStr = '/payment_history/edit.php?DEAL_ID='.$parentEntityID.'&PARENT_ENTITY_TYPE='.$parentEntityType.'&PARENT_ENTITY_ID='.$parentEntityID; ?> <a class="ui-btn ui-btn-md ui-btn-primary ui-btn-primary-docs-template" style="margin-top: 18px; margin-bottom: 18px; margin-left: 18px; float: right;" href="<?= $addUrlStr ?>">Добавить</a>
<? endif; ?> <? if($parentEntityType == 'ALL') : ?> <a class="ui-btn ui-btn-md ui-btn-primary ui-btn-primary-docs-template" style="margin-top: 18px; margin-bottom: 18px; margin-left: 18px; float: right;" href="/payment_history/export.php?export=excel" target="_blank">Экспорт в Excel</a> <br>
<? endif; ?>&nbsp;<b>&nbsp;</b>

<?
$editMenu = false;
if(in_array('1',$USER-> GetUserGroupArray()))
{
    $editMenu = true;
}
$APPLICATION->IncludeComponent('bitrix:main.ui.grid', '',
    [ 'GRID_ID' => 'paymentshistory_list',
        'COLUMNS' => $paymentsHistoryColumns,
        'ROWS' => $list,
        'NAV_OBJECT' => $nav,
        'AJAX_MODE' => 'Y',
        'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
        'PAGE_SIZES' => [
            ['NAME' => "5", 'VALUE' => '5'],
            ['NAME' => '10', 'VALUE' => '10'],
            ['NAME' => '20', 'VALUE' => '20'],
            ['NAME' => '50', 'VALUE' => '50'],
            ['NAME' => '100', 'VALUE' => '100']
        ],
        'AJAX_OPTION_JUMP' => 'N',
        'SHOW_ROW_CHECKBOXES' => $parentEntityType == 'ALL',
        'SHOW_CHECK_ALL_CHECKBOXES' => $parentEntityType == 'ALL',
        'SHOW_ROW_ACTIONS_MENU' => $editMenu,
        'SHOW_GRID_SETTINGS_MENU' => true,
        'SHOW_NAVIGATION_PANEL' => true,
        'SHOW_PAGINATION' => true,
        'SHOW_SELECTED_COUNTER' => $parentEntityType == 'ALL',
        'SHOW_TOTAL_COUNTER' => $parentEntityType == 'ALL',
        'SHOW_PAGESIZE' => $parentEntityType == 'ALL',
        'SHOW_ACTION_PANEL' => $parentEntityType == 'ALL',
        'ACTION_PANEL' => [
            'GROUPS' => [
                'TYPE' => [
                    'ITEMS' => [
                        [
                            'ID' => 'delete',
                            'TYPE' => 'BUTTON',
                            'TEXT' => 'Удалить',
                            'CLASS' => 'icon remove',
                            'ONCHANGE' => $onchangeDel->toArray()
                        ],
                        [
                            'ID' => 'actallrows_',
                            'TYPE' => 'CHECKBOX',
                            'CLASS' => 'main-grid-for-all-checkbox',
                            'NAME' => 'action_all_rows_',
                            'VALUE' => 'Y',
                            'ONCHANGE' => $onchangeForAll->toArray()
                        ],
                    ],
                ]
            ],
        ],
        'ALLOW_COLUMNS_SORT' => true,
        'ALLOW_COLUMNS_RESIZE' => true,
        'ALLOW_HORIZONTAL_SCROLL' => true,
        'ALLOW_SORT' => true,
        'ALLOW_PIN_HEADER' => true,
        'AJAX_OPTION_HISTORY' => 'N' ]);


?>
