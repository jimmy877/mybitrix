<?php

namespace php_interface\lib;

use \Bitrix\Tasks\Manager\Task;
use Bitrix\Main\Engine\Response;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Data\Cache;
use Bitrix\Tasks\Access\Model\TaskModel;
use Bitrix\Tasks;

/**
 * Class SprintTasks, для обновления полей спринта
 * @package php_interface\lib
 */
class SprintTasks
{
    /**
     * Функция обработки события добавления комментария в задачи и отправки письма
     * @param $commentId
     * @param $arFields
     */
    function saveChangeTask($commentId, &$arFields)
    {
        \CModule::IncludeModule("tasks");
        \CModule::IncludeModule("forum");
        \CModule::IncludeModule("disk");
        global $USER;
        $cUserEmail = '';

        $result = print_r($arFields,true);

        $filename = __DIR__ . '/upload/text.txt';

        file_put_contents($filename, print_r($result, true));

    }

    /**
     * Функция обработки и отправки писем отмеченным пользователям в комментарии CRM
     */
    public function addCommentTaggedUserCRM ()
    {
        \CModule::IncludeModule("forum");
        \CModule::IncludeModule("disk");
        global $USER;

        $application = \Bitrix\Main\Application::getInstance();
        $request = $application->getContext()->getRequest(); // получим данные о текущем запросе
        $ownerID = $request->getPost('OWNER_ID'); // событие сущности (Лид, Контакт, Сделка..)
        $action = $request->getPost('ACTION');
        $commentText = '';
        if ($action == "SAVE_COMMENT") { // ограничим код по событию добавления комментария
            $commentText = $request->getPost('TEXT'); // текст добавленного комментария
        }

        if ($commentText) {
            preg_match_all("/(\[USER=)([0-9]+)/", $commentText, $matches);
            $users_tagged = [];
            foreach ($matches[2] as $item) {
                $users_tagged[] = $item;
            }
            $taggedUser = array_unique($users_tagged);   // ID отмеченных пользователей в комментарии

            if ($taggedUser) {
                $userID = CurrentUser::get()->getID();
                if ($userID) {
                    $commentText = preg_replace('/\[\/?[a-z]+\]|\[\/?USER=[0-9]+\]|\[.+\/\]|\[.+\d\]/i', '', $commentText);

                    // получение ссылки на файлы прикреплённые к комментарию (функционал пока не доработан)
                    /*$fieldsPath = [];
                    foreach ($arFields['FILES'] as $key => $item) {
                        if (!empty($item)) {
                            $path = \Bitrix\Disk\Driver::getInstance()->getUrlManager()->getUrlToActionShowUfFile($item);

                            $fieldsPath[$key] = '<a href="https://' . $_SERVER["HTTP_HOST"] . $path . '">Ссылка на файл</a>';
                        }
                    }*/

                    // -Название сделки
                    $arTitleDeal = \Bitrix\Crm\DealTable::getList(
                        array(
                            'filter' => array('ID' => $ownerID),
                            'order' => array('ID' => 'ASC'),
                            'limit' => 1
                        )
                    );
                    $arDeals = $arTitleDeal->fetch();
                    $dealTitle = $arDeals['TITLE'];

                    // - Получаем email получателей письма
                    $cUserEmail = self::getUserEmailComment($taggedUser);

                    $dateComment = date("d-m-Y H:i");
                    $userName = CurrentUser::get()->getFullName();
                    $userEmail = CurrentUser::get()->getEmail();
                    $urlRequest = "/crm/deal/details/" . $ownerID . "/";

                    $userEmailUniq = self::uniqueEmailsUser($cUserEmail, $userEmail);
                    if($userEmailUniq) {
                        // Отправка писем отмеченным пользователям
                        foreach ($userEmailUniq as $emailItem) {
                            $arParamsEmail = [
                                'TAGGED_USER' => $emailItem,
                                'TASKS_TITLE' => "Сделка: " . $dealTitle,
                                'USER_NAME' => $userName,
                                'USER_EMAIL' => $userEmail,
                                'USER_ID' => $userID,
                                'REVIEW_LINK' => $urlRequest,
                                'COMMENT_TEXT' => $commentText,
                                'DATE_COMMENT' => $dateComment,
                                'SUBJECT' => 'Сделка [#' . $ownerID . '] ' . $dealTitle . '- Вас отметили в комментарии',
                            ];
                            self::sendEmailUsers($arParamsEmail);
                        }
                    }
                }
            }
        }
    }

    /**
     * Функция получения email отмеченного пользователя
     * @param $taggedUser
     * @return array
     */
    public static function getUserEmailComment($taggedUser){
        global $USER;
        $cUserEmail = [];
        foreach ($taggedUser as $userIdItem) {
            $cUser = $USER::GetList(
                $by = "EMAIL",
                $order = "desc",
                ['ID' => $userIdItem,],
                ['SELECT' => ['EMAIL']]
            )->fetch();
            $cUserEmail[] = $cUser['EMAIL'];
        }
        return $cUserEmail;
    }

    /**
     * Получаем название задачи
     * @param $tasksID
     * @return mixed
     */
    public static function getNameTasks($tasksID){
        $taskInfo = \Bitrix\Tasks\TaskTable::getList(
            array(
                'filter' => array('ID' => $tasksID),
                'order' => array('ID' => 'ASC'),
                'limit' => 1
            )
        );
        $arTask = $taskInfo->fetch();

        return $arTask['TITLE'];
    }

    /**
     * Исключаем отправку писем самому себе
     * @param $arEmail
     * @param $userEmail
     * @return array
     */
    public static function uniqueEmailsUser ($arEmail, $userEmail){
        $arUserEmails = [];
        foreach ($arEmail as $email){
            if ($email != $userEmail){
                $arUserEmails[] = $email;
            }
        }
        if ($arUserEmails) {
            $userEmailsUni = array_unique($arUserEmails);
        }
        return $userEmailsUni;
    }

    /**
     * Отправляет письмо пользователям по заданному почтовому шаблону
     * @param $arParams
     */
    public static function sendEmailUsers($arParams){
        \CEvent::Send('ADD_COMMENT_TAGGED_USER', 's1', $arParams);
    }
}


array(38) {
    ["PRIORITY"]=>
  int(2)
  ["TITLE"]=>
  string(68) "caspian.academy — средства просмотра файлов"
    ["DESCRIPTION"]=>
  string(980) "Необходимые форматы файлов:
.jpg
.pdf
.pptx
.docs

Видео:
- С сервера
- С Ютуба, Рутуба

1. Для презентаций и файлов решение следующее:
В iframe открываете на сайте [URL=https://docs.google.com/viewer?url=b24.caspian.academy/upload/uf/d2d/nnqg8uxuh4ywmugo9twjipxs3s71t8c5/ONE_TOUCH_Caspian_Academy_SMM_11_2019.pptx]https://docs.google.com/viewer?url=b24.caspian.academy/upload/uf/d2d/nnqg8uxuh4ywmugo9twjipxs3s71t8c...[/URL]

2. Для видео:
Использовать компонент bitrix:player

3. Для pdf файлов:
Использовать компонент bitrix:pdf.viewer

[B]ВАЖНО[/B]: Перед сдачей задачи неоходимо провериить, что можно загружать файлы даже большого типа. Например, могут быть большие pdf в 50 страниц."
    ["UF_TASK_WEBDAV_FILES"]=>
  array(1) {
        [0]=>
    string(0) ""
  }
  ["DEADLINE"]=>
  string(19) "02.03.2023 19:00:00"
    ["START_DATE_PLAN"]=>
  string(0) ""
    ["DURATION_TYPE"]=>
  string(4) "days"
    ["END_DATE_PLAN"]=>
  string(0) ""
    ["ALLOW_CHANGE_DEADLINE"]=>
  bool(true)
  ["MATCH_WORK_TIME"]=>
  bool(false)
  ["TASK_CONTROL"]=>
  bool(false)
  ["SE_PARAMETER"]=>
  array(3) {
        [1]=>
    array(3) {
            ["VALUE"]=>
      string(1) "N"
            ["ID"]=>
      string(6) "136253"
            ["CODE"]=>
      string(1) "1"
    }
    [2]=>
    array(3) {
            ["VALUE"]=>
      string(1) "N"
            ["ID"]=>
      string(6) "136254"
            ["CODE"]=>
      string(1) "2"
    }
    [3]=>
    array(3) {
            ["VALUE"]=>
      string(1) "N"
            ["ID"]=>
      string(6) "136255"
            ["CODE"]=>
      string(1) "3"
    }
  }
  ["SE_PROJECT"]=>
  array(1) {
        ["ID"]=>
    string(3) "399"
  }
  ["ALLOW_TIME_TRACKING"]=>
  string(1) "Y"
    ["TIME_ESTIMATE"]=>
  string(5) "14400"
    ["REPLICATE"]=>
  bool(false)
  ["SE_PARENTTASK"]=>
  array(1) {
        ["ID"]=>
    string(5) "24477"
  }
  ["EPIC"]=>
  string(1) "0"
    ["UF_AUTO_159743646329"]=>
  string(1) "0"
    ["UF_AUTO_210647403444"]=>
  string(0) ""
    ["UF_AUTO_288848610725"]=>
  string(1) "1"
    ["UF_AUTO_142615379319"]=>
  string(15) "17.02 — 02.03"
    ["UF_AUTO_261235705831"]=>
  string(0) ""
    ["UF_AUTO_378273767768"]=>
  string(0) ""
    ["CREATED_BY"]=>
  int(2204)
  ["AUDITORS"]=>
  array(1) {
        [0]=>
    int(13)
  }
  ["ACCOMPLICES"]=>
  array(1) {
        [0]=>
    int(2111)
  }
  ["TAGS"]=>
  array(0) {
    }
  ["CHECKLIST"]=>
  array(0) {
    }
  ["DEPENDS_ON"]=>
  array(0) {
    }
  ["GROUP_ID"]=>
  int(399)
  ["RESPONSIBLE_ID"]=>
  int(2185)
  ["OUTLOOK_VERSION"]=>
  int(20)
  ["CHANGED_BY"]=>
  int(13)
  ["CHANGED_DATE"]=>
  string(19) "28.02.2023 21:24:30"
    ["DURATION_PLAN"]=>
  int(0)
  ["ID"]=>
  int(26227)
  ["META:PREV_FIELDS"]=>
  array(89) {
        ["ID"]=>
    string(5) "26227"
        ["TITLE"]=>
    string(68) "caspian.academy — средства просмотра файлов"
        ["DESCRIPTION"]=>
    string(980) "Необходимые форматы файлов:
.jpg
.pdf
.pptx
.docs

Видео:
- С сервера
- С Ютуба, Рутуба

1. Для презентаций и файлов решение следующее:
В iframe открываете на сайте [URL=https://docs.google.com/viewer?url=b24.caspian.academy/upload/uf/d2d/nnqg8uxuh4ywmugo9twjipxs3s71t8c5/ONE_TOUCH_Caspian_Academy_SMM_11_2019.pptx]https://docs.google.com/viewer?url=b24.caspian.academy/upload/uf/d2d/nnqg8uxuh4ywmugo9twjipxs3s71t8c...[/URL]

2. Для видео:
Использовать компонент bitrix:player

3. Для pdf файлов:
Использовать компонент bitrix:pdf.viewer

[B]ВАЖНО[/B]: Перед сдачей задачи неоходимо провериить, что можно загружать файлы даже большого типа. Например, могут быть большие pdf в 50 страниц."
        ["DESCRIPTION_IN_BBCODE"]=>
    string(1) "Y"
        ["DECLINE_REASON"]=>
    NULL
    ["PRIORITY"]=>
    string(1) "2"
        ["STATUS"]=>
    string(1) "2"
        ["NOT_VIEWED"]=>
    string(1) "N"
        ["STATUS_COMPLETE"]=>
    string(1) "1"
        ["REAL_STATUS"]=>
    string(1) "2"
        ["MULTITASK"]=>
    string(1) "N"
        ["STAGE_ID"]=>
    string(1) "0"
        ["RESPONSIBLE_ID"]=>
    string(4) "2185"
        ["RESPONSIBLE_NAME"]=>
    string(12) "Никита"
        ["RESPONSIBLE_LAST_NAME"]=>
    string(14) "Базанов"
        ["RESPONSIBLE_SECOND_NAME"]=>
    string(18) "Андреевич"
        ["RESPONSIBLE_LOGIN"]=>
    string(16) "bna@one-touch.ru"
        ["RESPONSIBLE_WORK_POSITION"]=>
    string(22) "Программист"
        ["RESPONSIBLE_PHOTO"]=>
    string(6) "550328"
        ["DATE_START"]=>
    NULL
    ["DURATION_FACT"]=>
    NULL
    ["TIME_ESTIMATE"]=>
    string(5) "14400"
        ["TIME_SPENT_IN_LOGS"]=>
    NULL
    ["REPLICATE"]=>
    string(1) "N"
        ["DEADLINE"]=>
    string(19) "02.03.2023 19:00:00"
        ["DEADLINE_ORIG"]=>
    string(19) "2023-03-02 19:00:00"
        ["START_DATE_PLAN"]=>
    NULL
    ["END_DATE_PLAN"]=>
    NULL
    ["CREATED_BY"]=>
    string(4) "2204"
        ["CREATED_BY_NAME"]=>
    string(10) "Марго"
        ["CREATED_BY_LAST_NAME"]=>
    string(8) "Росс"
        ["CREATED_BY_SECOND_NAME"]=>
    string(0) ""
        ["CREATED_BY_LOGIN"]=>
    string(16) "rma@one-touch.ru"
        ["CREATED_BY_WORK_POSITION"]=>
    string(15) "Project Manager"
        ["CREATED_BY_PHOTO"]=>
    string(6) "540755"
        ["CREATED_DATE"]=>
    string(19) "28.02.2023 14:02:58"
        ["CHANGED_BY"]=>
    string(2) "13"
        ["CHANGED_DATE"]=>
    string(19) "28.02.2023 21:23:10"
        ["STATUS_CHANGED_BY"]=>
    string(2) "13"
        ["STATUS_CHANGED_DATE"]=>
    string(19) "28.02.2023 21:23:10"
        ["CLOSED_BY"]=>
    NULL
    ["CLOSED_DATE"]=>
    NULL
    ["ACTIVITY_DATE"]=>
    string(19) "28.02.2023 17:35:11"
        ["GUID"]=>
    string(38) "{cc67d777-71a5-4929-a523-42c58e80d684}"
        ["XML_ID"]=>
    NULL
    ["MARK"]=>
    NULL
    ["ALLOW_CHANGE_DEADLINE"]=>
    string(1) "Y"
        ["ALLOW_TIME_TRACKING"]=>
    string(1) "Y"
        ["MATCH_WORK_TIME"]=>
    string(1) "N"
        ["TASK_CONTROL"]=>
    string(1) "N"
        ["ADD_IN_REPORT"]=>
    string(1) "N"
        ["GROUP_ID"]=>
    string(3) "399"
        ["FORUM_TOPIC_ID"]=>
    string(5) "24434"
        ["PARENT_ID"]=>
    string(5) "24477"
        ["COMMENTS_COUNT"]=>
    string(1) "2"
        ["SERVICE_COMMENTS_COUNT"]=>
    string(1) "2"
        ["FORUM_ID"]=>
    string(2) "11"
        ["SITE_ID"]=>
    string(2) "s1"
        ["SUBORDINATE"]=>
    string(1) "Y"
        ["EXCHANGE_MODIFIED"]=>
    NULL
    ["EXCHANGE_ID"]=>
    NULL
    ["OUTLOOK_VERSION"]=>
    string(2) "19"
        ["VIEWED_DATE"]=>
    string(19) "28.02.2023 21:24:25"
        ["DEADLINE_COUNTED"]=>
    string(1) "0"
        ["FORKED_BY_TEMPLATE_ID"]=>
    NULL
    ["FAVORITE"]=>
    string(1) "N"
        ["SORTING"]=>
    string(16) "-2102288.7812500"
        ["DURATION_PLAN_SECONDS"]=>
    string(1) "0"
        ["DURATION_TYPE_ALL"]=>
    string(4) "days"
        ["DURATION_PLAN"]=>
    string(1) "0"
        ["DURATION_TYPE"]=>
    string(4) "days"
        ["IS_MUTED"]=>
    string(1) "Y"
        ["IS_PINNED"]=>
    string(1) "N"
        ["IS_PINNED_IN_GROUP"]=>
    string(1) "N"
        ["UF_CRM_TASK"]=>
    bool(false)
    ["UF_TASK_WEBDAV_FILES"]=>
    array(0) {
        }
    ["UF_MAIL_MESSAGE"]=>
    NULL
    ["UF_AUTO_261235705831"]=>
    NULL
    ["UF_AUTO_142615379319"]=>
    string(15) "17.02 — 02.03"
        ["UF_AUTO_378273767768"]=>
    NULL
    ["UF_AUTO_159743646329"]=>
    string(1) "0"
        ["UF_AUTO_288848610725"]=>
    string(1) "1"
        ["UF_AUTO_210647403444"]=>
    NULL
    ["AUDITORS"]=>
    array(1) {
            [0]=>
      string(2) "13"
    }
    ["ACCOMPLICES"]=>
    array(1) {
            [0]=>
      string(4) "2111"
    }
    ["TAGS"]=>
    array(0) {
        }
    ["CHECKLIST"]=>
    array(0) {
        }
    ["FILES"]=>
    array(0) {
        }
    ["DEPENDS_ON"]=>
    array(0) {
        }
  }
}