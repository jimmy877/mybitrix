<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Config;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Sale;
use OneTouch\CommonResponse;

CModule::includeModule('premiumbonus');

/**
 * Класс обрабатывающий запросы приходящие с новой корзины (написанной на VUE)
 *
 * Class BasketNew
 */
class BasketNew  extends CBitrixComponent  implements Controllerable {

    /**
     *  массив, ключами которого являются id типа оплаты, а значение массив с id терминалов для которых работает этот тип оплаты
     */
    const PAYMENT_TYPES_WITH_TERMINALS_ID = [
        10 => [
            // Фастлэнд
            //'83982872-f73a-3409-017c-b97cb68404fe', // Кафе "Му-Му": Му-1 (Фрунзенская) старый код (до облака) 282b42ee-2b3c-4fbb-014e-e00028478ad3
            '300e6851-b277-7b4f-0179-a79650addd45', // Кафе "Му-Му": Му-3 (Мясницкая)
            '1d68f56c-ae56-f291-017c-ffc221c719d2', // Кафе "Му-Му": Му-6 (Добрынинская Коровий Вал ул., д. 1) старый код (до облака)  282b42ee-2b3c-4fbb-014e-e0002847896c
            '83982872-f73a-3409-017c-b92702244fd5', // Кафе "Му-Му": Му-7 (Химки) старый код (до облака) 93624865-3697-9738-0155-7d404413305d
            '83982872-f73a-3409-017c-a507eaff2a2e', // Кафе "Му-Му": Му-08 (Бауманская) старый код (до облака) 2d60a276-d50c-94d2-017b-3aec57a6ed28
            '72eefbbe-3ee7-4457-a5b9-05b79a7cecc0', // Кафе "Му-Му": Му-9 (Зеленоград) старый код (до облака) 282b42ee-2b3c-4fbb-014e-e00028478c13
            'c26c1117-b062-cd90-017c-c3afead826aa', // Кафе "Му-Му": Му-10 (Чертановская)  старый код (до облака) 09e58c8e-d072-ca8e-0151-c4bddf9b2c24
            '1d68f56c-ae56-f291-017d-00013a6a5f72', // Кафе "Му-Му": Му-11 (Тимирязевская Яблочкова ул., д. 19г, ТЦ «Депо-Молл», 3-й этаж) старый код (до облака)  64a72453-71de-6cd4-014f-03cc252c1f2b
            'c26c1117-b062-cd90-017c-c3afead80a02', // Кафе "Му-Му": Му-13 (Профсоюзная) старый код (до облака) 0c8b8d87-57c5-1c74-0150-ce2c6cee7f38
            //'c26c1117-b062-cd90-017c-beb99f9e1e87', // Кафе "Му-Му": Му-14 (Кузнецкий мост) старый код (до облака) 2e97125b-1228-a323-0153-08fd4d6638e2
            '1d68f56c-ae56-f291-017c-ffe795cdc7ad', // Кафе "Му-Му": Му-15 (Кафе на Тверской М.Гнездниковский пер., д. 9/8, стр. 7)
            //'83982872-f73a-3409-017c-99adef07f9f3', // Кафе "Му-Му": Му-19 (Марксистская) старый код (до облака) 300e6851-b277-7b4f-0179-84c49968cca3
            '2a2d61b2-9295-7276-0175-ad9ed9f2442c', // Кафе "Му-Му": Му-20 (Теплый стан)
            //'1d68f56c-ae56-f291-017c-ffdb0fb74af6', // Кафе "Му-Му": Му-21 (Сокольники  Сокольническая пл., д. 9, цокольный этаж) старый код (до облака)  1dd52267-7ec6-b4a2-0152-977212e299f9
            '83982872-f73a-3409-017c-9f23339a160b', // Кафе "Му-Му": Му-25 Новокузнецкая (Третьяковская)   старый код (до облака) 300e6851-b277-7b4f-0179-41182ad018e2
            //'83982872-f73a-3409-017c-a591eff34911', // Кафе "Му-Му": Му-26 (Юго-западная) старый код (до облака) 0c8b8d87-57c5-1c74-0150-d7822f970c22 //osman
            //'83982872-f73a-3409-017c-9f23339a48cf', // Кафе "Му-Му": Му-28 (Смоленская Карманицкий пер., 9)  старый код (до облака) 300e6851-b277-7b4f-0179-cbb8f1d30688
            'c26c1117-b062-cd90-017c-beb99f9e00b3', // Кафе "Му-Му": Му-29 (Речной вокзал) старый код (до облака) 282b42ee-2b3c-4fbb-014e-e0002847d69d
            '83982872-f73a-3409-017c-b92702240985', // Кафе "Му-Му": Му-31 (Крылатское) старый код (до облака) 0b0215f5-d366-6dd3-015c-f860fd4947bf
            '1d68f56c-ae56-f291-017c-e702dbe17271', // Кафе "Му-Му": Му-32 (Новогиреево) старый код (до облака) ac7914ee-9f54-1a51-015e-7069a1cb79eb
            '83982872-f73a-3409-017c-a55c03dba609', // Кафе "Му-Му": Му-33 (Бибирево) старый код (до облака) 5840566e-f7e0-3bb6-0150-f17e7d358d71
            'c26c1117-b062-cd90-017c-be7894274e9e', // Кафе "Му-Му": Му-35 (Пролетарская) старый код (до облака) 92206743-560d-c4f6-0153-380d45e0e7d5
            '0a309408-ccbb-473b-015a-27f0838c27c7', // Кафе "Му-Му": Му-36 (Курская)
            '1d68f56c-ae56-f291-017c-ffc221c7d537', // Кафе "Му-Му": Му-37 (Баррикадная Баррикадная ул., д. 21/34, с 3) старый код (до облака) 194046dd-fee7-502f-0178-465c2e4740d7
            'c6cc8db3-77e6-2f08-017d-b3bb0dbeafcb', //  Кафе на Новослободской
            '4a958697-3964-51b9-0171-686f9ab246ad', // Кафе на Профсоюзной  ТЕСТОВОЕ
        ],
        13 => [
            // Золотая корова
            'c26c1117-b062-cd90-017c-be74a6503664', // Кафе "Му-Му": Му-40 (Щелковская) старый код (до облака)  7f565d25-feaf-1db3-0176-46a8bd06aa9e
            '31f238bc-67b9-dfd9-0171-9d23d06a0369', // Кафе на Фрунзенской ТЕСТОВОЕ
        ],
        14 => [
            '1d68f56c-ae56-f291-017c-ffdb0fb74af6',
            '83982872-f73a-3409-017c-a591eff34911',

        ],
        15 => [
            '83982872-f73a-3409-017c-99adef07f9f3',
            '83982872-f73a-3409-017c-9f23339a48cf',
            '83982872-f73a-3409-017c-b97cb68404fe',
            'c26c1117-b062-cd90-017c-beb99f9e1e87'
        ]
    ];

    private static $isDeliveryDelay = false; // флаг отложенной доставки
    private static $timeDelivery = ''; // время отложенной доставки
    private static $deliveryHours = ''; // количество часов на которое нужно сдвинуть ближайший заказ
    // Обязательный метод
    public function configureActions() {
        // Сбрасываем фильтры по-умолчанию (ActionFilter\Authentication и ActionFilter\HttpMethod)
        // Предустановленные фильтры находятся в папке /bitrix/modules/main/lib/engine/actionfilter/
        // Чтобы csrf токен не проверялся нужно убрать ActionFilter\HttpMethod и ActionFilter\Csrf из возвращаемого
        // массива для конкретного ajax метода, недостаточно убрать просто ActionFilter\Csrf,
        // если в ActionFilter\HttpMethod разрешен POST, то префильтр ActionFilter\Csrf автоматически включается.
        return [
            'getOrdersHistory' => [ // Ajax-метод возвращает коллекцию оплат (завершонных и неоплаченых)
                                    'prefilters' => [],
                                    'postfilters' => []
            ],
            'getOrder' => [ // Ajax-метод возвращает данные об оплате поеё id
                            'prefilters' => [],
                            'postfilters' => []
            ],
            'getBasket' => [ // Ajax-метод возвращает все данные для корзины, при первой загрузке или при обновлении страници
                             'prefilters' => [],
                             'postfilters' => []
            ],
            'deleteProduct' => [ // Ajax-метод удаляет выбранную позицию с корзины
                                 'prefilters' => [],
                                 'postfilters' => []
            ],
            'changeProductCount' => [ // Ajax-метод изменяет кол-во позиции в корзине
                                      'prefilters' => [],
                                      'postfilters' => []
            ],
            'setCoupon' => [ // Ajax-метод применить купон
                             'prefilters' => [],
                             'postfilters' => []
            ],
            'removeCoupon' => [ // Ajax-метод убрать купон
                                'prefilters' => [],
                                'postfilters' => []
            ],
            'changeUserAddress' => [ // Ajax-метод поменять адрес пользователя
                                     'prefilters' => [],
                                     'postfilters' => []
            ],
            'changeUserInfo' => [ // Ajax-метод изменить данные пользователя
                                  'prefilters' => [],
                                  'postfilters' => []
            ],
            'setBonuses' => [ // Ajax-метод применить бонусы
                              'prefilters' => [],
                              'postfilters' => []
            ],
            'setGuestCount' => [ // Ajax-метод установить кол-во приборов
                                 'prefilters' => [],
                                 'postfilters' => []
            ],
            'getGuestCount' => [ // Ajax-метод возвращает кол-во приборов
                                 'prefilters' => [],
                                 'postfilters' => []
            ],
            'getOrderInfo' => [ // Ajax-метод возвращает данные о заказе
                                'prefilters' => [],
                                'postfilters' => []
            ],
            'getOrdersHistoryNew' => [ // Ajax-метод возвращает список заказов
                                       'prefilters' => [],
                                       'postfilters' => []
            ],
            'getUserData' => [ // Ajax-метод возвращает информацию о пользователе
                               'prefilters' => [],
                               'postfilters' => []
            ],
        ];
    }

    /**
     * Обертка над глобальной переменной
     * @return CAllMain|CMain
     */
    private function _app() {
        global $APPLICATION;
        return $APPLICATION;
    }

    /**
     * Проверка наличия модулей требуемых для работы компонента
     * @return bool
     * @throws Exception
     */
    private function _checkModules()
    {
        if (!CModule::IncludeModule("iblock")    ) {
            throw new \Exception('Не загружены модули необходимые для работы модуля');
        }
        //подключаем модуль highloadblock
        if(!CModule::IncludeModule("highloadblock")) {
            throw new \Exception('Не загружены модули необходимые для работы модуля');
        }

        return true;
    }

    /**
     * Подготовка параметров компонента
     * @param $arParams
     * @return mixed
     */
    public function onPrepareComponentParams($arParams) {
        $arParams['AJAX_MODE'] = CommonResponse::isAjaxRequest($this->request) ? "Y" : "N";

        return parent::onPrepareComponentParams($arParams);
    }

    /**
     * Точка входа в компонент
     */
    public function executeComponent()  {
        try {
            $this->_checkModules();

            $request = \Bitrix\Main\Context::getCurrent()->getRequest();

            if(CommonResponse::isAjaxRequest($this->request)) {

            } else {
                $this->showComponentPage();
            }

        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * Показ страницы
     */
    protected function showComponentPage() {
        $this->getVacancyPageData($this->arParams);
        $this->includeComponentTemplate();
    }

    /**
     * Возвращение истории заказов
     *
     * @return mixed
     */
    public function getOrdersHistoryAction()
    {
        $result = new CommonResponse();

        try {
            $response = self::getOrdersHistory();

            if(null !== $response) {
                $result->setSuccessData($response);
            } else {
                $result->setSuccessData('Нет данных');
            }
        } catch (Exception $e) {
            $result->setError($e->getMessage(), CommonResponse::ERROR_BAD_DATA);
        }
        return $result->returnResponse();
    }

    /**
     * Получение истории заказов
     *
     * @return array
     */
    private static function getOrdersHistory()
    {
        $ordersEnded = self::getOrders('F');
        $ordersWithoutPay = self::getOrders('N');
        $response = array_merge($ordersWithoutPay, $ordersEnded);
        return $response;
    }

    private static function getOrders($status)
    {
        $parameters = [
            'filter' => [
                'USER_ID' => self::geUserId(),
                'STATUS_ID' => $status,
            ],
            'limit' => 5,
        ];

        $dbRes = \Bitrix\Sale\Order::getList($parameters);

        $response = [];

        while ($order = $dbRes->fetch())
        {
            $count = 0;
            $basket = \Bitrix\Sale\Basket::getList([
                'filter' => [
                    'ORDER_ID' => $order['ID'],
                ],
            ]);
            while($bItem = $basket->Fetch()){
                $count += $bItem['QUANTITY'];
            }
            $response[] = [
                'total' => (int) $order['PRICE'],
                'date' => mb_substr($order['DATE_PAYED'] ? $order['DATE_PAYED']->toString() : $order['DATE_INSERT']->toString() , 0 , 10),
                'goods' => $count,
                'id' => $order['ID'],
                'status' => $status,
            ];
        }
        return $response;
    }

    /**
     * Получение количества бонусов
     */
    public static function getBonuses()
    {
        if (CModule::includeModule('premiumbonus')) {
            return $_SESSION['premium']['premium_discount'];
        }
    }

    /**
     * Установка количества списываемых бонусов
     *
     * @param $selectedPoint
     * @return array|bool
     */
    public function setBonuses($selectedPoint)
    {
        if (CModule::includeModule('premiumbonus')) {
            if ($selectedPoint > 0) {
                $_SESSION['premium']['premium_discount'] = $selectedPoint;
                return true;
            }
            return ['status' => 'fail'];
        } else {
            return ['status' => 'fail'];
        }
    }

    /**
     * Данные определенного заказа, для истории заказов
     */
    public function getOrderAction()
    {
        $result = new CommonResponse();

        try {
            $id = $_GET['id'];

            $response = self::getOrder($id);

            if(null !== $response) {
                $result->setSuccessData($response);
            } else {
                $result->setSuccessData('Нет данных');
            }
        } catch (Exception $e) {
            $result->setError($e->getMessage(), CommonResponse::ERROR_BAD_DATA);
        }
        return $result->returnResponse();
    }

    /**
     * Получение данных о заказе для истории
     *
     * @param $id
     * @return array
     */
    private static function getOrder($id)
    {
        // получение закза
        $basket = \Bitrix\Sale\Basket::getList([
            'filter' => [
                'ORDER_ID' => $id,
            ],
        ]);

        // формируем массив с позициями заказа
        $items = [];
        while($bItem = $basket->Fetch()){
            $items[] = $bItem;
        }

        $response = [];
        foreach ($items as $key => $item) {

            $response[$key] = [
                'img' => self::getProductsImg($item['PRODUCT_ID']),
                'title' => $item['NAME'],
                'weight' => $item['WEIGHT'],
                //'price' => (int) $item['BASE_PRICE'],
                'price' => (int) $item['PRICE'],
                'count' => (int) $item['QUANTITY'],
                //'totalPrice' => ((int) $item['DISCOUNT_PRICE'] * (int) $item['QUANTITY']),
                'totalPrice' => ((int)$item['PRICE'] * (int)$item['QUANTITY']),
                'product_id' => $item['PRODUCT_ID'],
                'base_price' => (int)$item['BASE_PRICE'],
                'discount_price'=> (int)$item['DISCOUNT_PRICE'],
            ];
        }
        return $response;
    }

    /**
     * Получение данных авторизованного пользователя
     */
    public static function getUserData()
    {
        if (CUser::IsAuthorized()) {
            $user = CUser::GetByID(self::geUserId())->arResult[0];
            self::getProduct();
            $data = self::getUserFields($user);
            $data['isAuthorized'] = 1;
        } else {
            $data['isAuthorized'] = 0;
        }
        return $data;
    }

    /**
     * Получение товаров корзины, спискок товаров, общая сумма, информация о типах оплаты
     */
    public function getBasketAction()
    {
        $result = new CommonResponse();

        try {

            $paymentTypes = self::getPaymentTypes();

            $response = [
                'product' => self::getProduct(),
                'address' => self::getAddress(),
                'paymentData' => [
                    'id' => '1_0', // тип оплаты по умолчанию
                ],
                'paymentsTypeList' => $paymentTypes,
                'lawDocuments' => self::getLawDocuments($paymentTypes),
            ];

            //обойдем товары и проставим им выбранные модификаторы, если есть
            foreach ($response['product']['items'] as &$product){
                $product_id = intval($product['id']);
                if ($_SESSION['castomModifier'][$product_id]['idModifier'] != '') {
                    $product['modifiers']=[
                        'name' => $_SESSION['castomModifier'][$product_id]['name'],
                        'amount' => $_SESSION['castomModifier'][$product_id]['amount'],
                    ];
                }
                else{
                    $product['modifiers']=[];
                }
            }


            if(null !== $response) {
                $result->setSuccessData($response);
            } else {
                $result->setSuccessData('Нет данных');
            }
        } catch (Exception $e) {
            $result->setError($e->getMessage(), CommonResponse::ERROR_BAD_DATA);
        }
        return $result->returnResponse();
    }

    /**
     * для каждого юрлица свои документы
     *
     * @param array $paymentTypes
     * @return array
     */
    public static function getLawDocuments(array $paymentTypes)
    {
        $lawDocuments = [
            'oferta' => '/upload/oferta.pdf',
            'supportPolitic' => '/upload/support_politic.pdf',
        ];
        foreach ($paymentTypes as $paymentType) {
            if ($paymentType['val'] == 13) {
                $lawDocuments = [
                    'oferta' => '/upload/oferta_golden_cow.pdf',
                    'supportPolitic' => '/upload/support_politic_golden_cow.pdf',
                ];
            }
        }
        return $lawDocuments;
    }

    /**
     * Получение адреса доставки или кафе в случае самовывоза
     *
     * @return array
     */
    public static function getAddress() {
        $data = json_decode($_COOKIE['delivery_address']);

        $address = [
            'isPickup' => false,
            'data' => [],
        ];
        if (!$data->is_sooner_better) {
            $dateTime = date('d-m-Y H:i', strtotime($data->date_time->date . ' ' . $data->date_time->h . ':' . $data->date_time->m));
            $timeDelivery = [
                'is_sooner_better' => 2,
                'isCorrectDate' => strtotime($dateTime) > strtotime(date('d-m-Y H:i')),
                'date_time' => $dateTime,
            ];
        } else {
            $timeDelivery = [
                'is_sooner_better' => 1,
                'isCorrectDate' => true,
                'date_time' => '',
            ];
        }
        if ($data->is_pickup) {
            $address = [
                'isPickup' => true,
                'isCorrectDate' => true,
                'data' => self::getCafeInfo(),
            ];
        } else {
            $address = [
                'isPickup' => false,
                'isCorrectDate' => true,
                'data' => self::getDeliveryAddress(),
            ];
        }
        return array_merge($address, $timeDelivery);
    }

    /**
     * Запрос на добавления купона к корзине
     */
    public function setCouponAction()
    {
        $result = new CommonResponse();

        try {
            $coupon = $_GET['coupon'];

            self::setCoupon($coupon);

            $response = self::getProduct();

            if(null !== $response) {
                $result->setSuccessData($response);
            } else {
                $result->setSuccessData('Нет данных');
            }
        } catch (Exception $e) {
            $result->setError($e->getMessage(), CommonResponse::ERROR_BAD_DATA);
        }
        return $result->returnResponse();
    }

    /**
     * Применение купона к корзине
     *
     * @param $coupon
     */
    private static function setCoupon($coupon)
    {
        CModule::IncludeModule("sale");

        // применяем купон
        Sale\DiscountCouponsManager::add($coupon);

        // получаем объект корзины для текущего пользователя
        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());

        // получаем объект скидок для корзины
        $discounts = Sale\Discount::loadByBasket($basket);

        // обновляем поля в корзине
        $basket->refreshData(['PRICE','COUPONS']);

        // пересчёт скидок для корзины
        $discounts->calculate();

        // получаем результаты расчёта скидок для корзины
        $res = $discounts->getApplyResult(true);

        $basket->save();
    }

    /**
     * Запрос на удаление купона
     */
    public function removeCouponAction()
    {
        $result = new CommonResponse();

        try {
            $coupon = $_GET['coupon'];
            // если передан параметр all и нет купона, то удаляем все купоны
            if ($_GET['all'] && $coupon === '') {
                foreach (\Bitrix\Sale\DiscountCouponsManager::get() as $objCoupon) {
                    \Bitrix\Sale\DiscountCouponsManager::delete($objCoupon['COUPON']);
                }
            } else {
                // удаляем примененный купон
                \Bitrix\Sale\DiscountCouponsManager::delete($coupon);
            }

            $response = self::getProduct();

            if(null !== $response) {
                $result->setSuccessData($response);
            } else {
                $result->setSuccessData('Нет данных');
            }
        } catch (Exception $e) {
            $result->setError($e->getMessage(), CommonResponse::ERROR_BAD_DATA);
        }
        return $result->returnResponse();
    }

    /**
     * Запрос на изменение количества товара в корзине
     */
    public function changeProductCountAction()
    {
        $result = new CommonResponse();

        try {
            $id = $_GET['id'];
            $count = $_GET['count'];

            self::changeProductCount($id, $count);

            $response = self::getProduct();

            if(null !== $response) {
                $result->setSuccessData($response);
            } else {
                $result->setSuccessData('Нет данных');
            }
        } catch (Exception $e) {
            $result->setError($e->getMessage(), CommonResponse::ERROR_BAD_DATA);
        }
        return $result->returnResponse();
    }

    /**
     * Запрос на удаление товара из корзины
     */
    public function deleteProductAction()
    {
        $result = new CommonResponse();
        try {
            $id = $_GET['id'];
            self::changeProductCount($id);
            $response = self::getProduct();
            if(null !== $response) {
                $result->setSuccessData($response);
            } else {
                $result->setSuccessData('Нет данных');
            }
        } catch (Exception $e) {
            $result->setError($e->getMessage(), CommonResponse::ERROR_BAD_DATA);
        }
        return $result->returnResponse();
    }

    /**
     * Изменение данных о пользователе
     */
    public function changeUserInfoAction()
    {
        $result = new CommonResponse();
        try {
            $name = $_GET['name'];
            $lastName = $_GET['lastName'];
            $email = $_GET['email'];
            $fields = [
                "NAME" => $name,
                "LAST_NAME" => $lastName,
                "EMAIL" => $email
            ];
            $user = new CUser;
            $user->Update(self::geUserId(), $fields);
            $response = self::getUserData();
            if(null !== $response) {
                $result->setSuccessData($response);
            } else {
                $result->setSuccessData('Нет данных');
            }
        } catch (Exception $e) {
            $result->setError($e->getMessage(), CommonResponse::ERROR_BAD_DATA);
        }

        return $result->returnResponse();
    }

    /**
     * Изменение адреса пользователя
     */
    public function changeUserAddressAction()
    {
        $result = new CommonResponse();

        try {
            $city = $_GET['city'];
            $metro = $_GET['metro'];
            $street = $_GET['street'];
            $house = $_GET['house'];
            $housing = $_GET['housing'];
            $building = $_GET['building'];
            $entrace = $_GET['entrace'];
            $floor = $_GET['floor'];
            $apartment = $_GET['apartment'];

            $fields = [
                "PERSONAL_CITY" => $city,
                "PERSONAL_STREET" => $street,
                "UF_METRO" => $metro,
                "UF_HOUSE" => $house,
                "UF_HOUSING" => $housing,
                "UF_BUILDING" => $building,
                "UF_ENTRACE" => $entrace,
                "UF_FLOOR" => $floor,
                "UF_APARTAMENT1" => $apartment,
            ];

            $user = new CUser;
            $user->Update(self::geUserId(), $fields);

            $response = self::getUserData();

            if(null !== $response) {
                $result->setSuccessData($response);
            } else {
                $result->setSuccessData('Нет данных');
            }
        } catch (Exception $e) {
            $result->setError($e->getMessage(), CommonResponse::ERROR_BAD_DATA);
        }

        return $result->returnResponse();
    }

    /**
     * Применение бонусов (сколько бонусов списать)
     */
    public function setBonusesAction()
    {
        $result = new CommonResponse();

        try {
            $bonuses = $_GET['bonuses'];

            self::setBonuses($bonuses);

            $response = self::getProduct();

            if(null !== $response) {
                $result->setSuccessData($response);
            } else {
                $result->setSuccessData('Нет данных');
            }
        } catch (Exception $e) {
            $result->setError($e->getMessage(), CommonResponse::ERROR_BAD_DATA);
        }

        return $result->returnResponse();
    }

    /** TO DO
     * Отправка запроса на создание заказа
     */
    public function requestBasketAction()
    {
        global $USER;

        Bitrix\Main\Loader::includeModule("sale");
        Bitrix\Main\Loader::includeModule("catalog");

        // создаем виртуальный заказ
        $siteId = \Bitrix\Main\Context::getCurrent()->getSite();
        $userId = $USER->isAuthorized() ? $USER->GetID() : appTools::USER_EXPRESS_ID;

        $orderVirtual = \Bitrix\Sale\Order::create($siteId, $userId);

        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());

        $orderVirtual->setBasket($basket);

        // добавляем отгрузку
//        $shipmentCollection = $orderVirtual->getShipmentCollection();
//        foreach ($basket as $basketItem) {
//            $shipment = $shipmentCollection->createItem($basketItem);
//            $shipment->setFields(array(
//                'DELIVERY_ID' => 3,
//                'DELIVERY_NAME' => 'Самовывоз',
//            ));
//            $shipmentItemCollection = $shipment->getShipmentItemCollection();
//            $shipmentItem = $shipmentItemCollection->createItem();
//            $shipmentItem->setQuantity($basket->getQuantity());
//        }
////        $service = Delivery\Services\Manager::getById(Delivery\Services\EmptyDeliveryService::getEmptyDeliveryServiceId());
//
//        $shipmentCollection = $orderVirtual->getShipmentCollection();
//        foreach ($shipmentCollection as $shipment) {
//            if (!$shipment->isSystem()) {
//                var_dump($shipment);
//
//                foreach ($basket as $newBasketItem) {
//                    /** @var \Bitrix\Sale\Shipment $shipment */
//                    $shipmentItemCollection = $shipment->getShipmentItemCollection();
//                    $shipmentItem = $shipmentItemCollection->createItem($newBasketItem);
//                    $shipmentItem->setQuantity(1);
//                    $shipment->setFields(array(
//                        'DELIVERY_ID' => 3,
//                        'DELIVERY_NAME' => 'Самовывоз',
//                    ));
//                }
//                break;
//            }
//        }
        // добавляем оплату
        $paymentCollection = $orderVirtual->getPaymentCollection();
        $service = \Bitrix\Sale\PaySystem\Manager::getObjectById(1);
        $newPayment = $paymentCollection->createItem($service);
        $result = $orderVirtual->save();

//        $discount = $orderVirtual->getDiscount();
//        \Bitrix\Sale\DiscountCouponsManager::clearApply(true);
//        \Bitrix\Sale\DiscountCouponsManager::useSavedCouponsForApply(true);
//        $discount->setOrderRefresh(true);
//        $discount->setApplyResult(array());
//        /** @var \Bitrix\Sale\Basket $basket */
//        if (!($basket = $orderVirtual->getBasket())) {
//            throw new \Bitrix\Main\ObjectNotFoundException('Entity "Basket" not found');
//        }
//        $basket->refreshData(array('PRICE', 'COUPONS'));
//        $discount->calculate();
//        $orderVirtual->save();

    }

    /**
     * Изменение кол-ва позиции в корзине (если количество 0, то выбранная позиция удаляется)
     *
     * @param $id
     * @param int $count
     * @return
     */
    private static function changeProductCount($id, $count = 0)
    {
        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
        foreach ($basket as $item) {
            if ($id == $item->getProductId() && $count > 0) {
                $item->setFields([
                    'QUANTITY' => $count,
                ]);
            } elseif ($id == $item->getProductId() && $count == 0) {
                $item->delete();
            }
        }
        return $basket->save();
    }

    /**
     * Получение всех данных о кафе
     *
     * @return array
     */
    private static function getCafeInfo()
    {
        $deliveryAddressAndDate = getDeliveryAddressAndDate();
        $pointList = getPointList();
        $response = [];
        foreach ($pointList as $point) {
            if ($point['terminal_id'] === $deliveryAddressAndDate['address']['terminal_id']) {
                $response = [
                    'name' => $point['title'],
                    'address' => $point['address'],
                    'workTime' => $point['opening_hours'],
                    'description' => $point['opening_hours'],
                    'phone' => $point['phone_number'],
                    'terminal_id' => $point['terminal_id'],
                    'tableware' => '', // Приборы
                ];
            }
        }
        return $response;
    }

    /**
     * Получение типов оплаты
     *
     * @return mixed
     */
    private static function getPaymentTypes()
    {
        $response = [];
        $paymentTypeList = CSalePaySystem::GetList(false, Array('ACTIVE'=>"Y"));
        if (isset($paymentTypeList)) {
            $response = self::paymentTypesFilter($paymentTypeList);
        }
        return $response;
    }

    /**
     * Фильтр для типов оплаты
     * @param $paymentTypeList
     * @return array
     */
    private static function paymentTypesFilter($paymentTypeList)
    {
        $terminalId = self::getDeliveryAddress()['terminal_id'];
        $response = [];
        foreach ($paymentTypeList->arResult as $paymentType) {
            if (self::paymentTypeFilter($paymentType, $terminalId)) {
                $response[] = [
                    'val' => $paymentType['ID'],
                    'label' => CSalePaySystem::GetByID($paymentType['ID'])['PSA_NAME'],
                    'name' => $paymentType['NAME'],
                    'description' => $paymentType['DESCRIPTION'],
                ];
                if ($paymentType['ID'] == 10 || $paymentType['ID'] == 13) {
                    /*
                    if (preg_match("#ios|mac_powerpc|macintosh|mac os x|iPhone|iPad|iPod|webOS#i", $_SERVER['HTTP_USER_AGENT'], $matches)) {
                          $response[] = [
                              'val' => $paymentType['ID'],
                              'label' => 'Apple Pay',
                              'name' => $paymentType['NAME'],
                              'description' => $paymentType['DESCRIPTION'],
                          ];
                      } else {
                          $response[] = [
                              'val' => $paymentType['ID'],
                              'label' => 'Samsung Pay',
                              'name' => $paymentType['NAME'],
                              'description' => $paymentType['DESCRIPTION'],
                          ];
                          $response[] = [
                              'val' => $paymentType['ID'],
                              'label' => 'Google Pay',
                              'name' => $paymentType['NAME'],
                              'description' => $paymentType['DESCRIPTION'],
                          ];
                      }
                      */
                }
            }
        }
        return $response;
    }

    /**
     * Проверка типа оплаты на соответствие данному терминалу
     *
     * @param $paymentType
     * @param $terminalId
     * @return bool
     */
    public static function paymentTypeFilter($paymentType, $terminalId)
    {
        $paymentTypesTerminalsId = self::PAYMENT_TYPES_WITH_TERMINALS_ID;
        foreach ($paymentTypesTerminalsId as $paymentTypeId => $terminalsIdList) {
            if ($paymentType['ID'] == $paymentTypeId && !in_array($terminalId, $terminalsIdList)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Получение адреса доставки
     *
     * @return array
     */
    private static function getDeliveryAddress()
    {
        if (!empty($_COOKIE['delivery_address'])) {
            $data = json_decode($_COOKIE['delivery_address'])->address;
            $response = [
                'full' => $data->full, //полный адрес
                'city' => $data->city,
                'street' => $data->street,
                'house' => $data->home,
                'housing' => $data->housing, // Корпус
                'building' => $data->block, // Строение
                'floor' => '', // Этаж
                'entrance' => '', // Подъезд
                'apartment' => '', // Квартира
                'code' => '', // Домофон
                'tableware' => '', // Приборы
                'terminal_id' => $data->terminal_id,
            ];
        } else {
            $user = CUser::GetByID(self::geUserId())->arResult[0];
            $data = self::getUserFields($user);
            $response = [
                'full' => '', //полный адрес
                'city' => $data['PERSONAL_CITY'],
                'street' => $data['PERSONAL_STREET'],
                'house' => $data['UF_HOUSE'],
                'housing' => $data['UF_HOUSING'], // Корпус
                'building' => $data['UF_BUILDING'], // Строение
                'floor' => $data['UF_FLOOR'], // Этаж
                'entrance' => $data['UF_ENTRACE'], // Подъезд
                'apartment' => $data['UF_APARTAMENT1'], // Квартира
                'code' => '', // Домофон
                'tableware' => '', // Приборы
                'terminal_id' => '',
            ];
        }
        return $response;
    }

    /**
     * проверяем есть ли доставка
     *
     * @return mixed
     */
    private static function getIsPickup()
    {
        $data = json_decode($_COOKIE['delivery_address'])->is_pickup; //
        $response = $data;
        return $response;
    }

    /**
     * Получение продуктов корзины, суммы к оплате и скидки
     *
     * @return array
     */
    public static function getProduct()
    {
        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());

        $address = self::getAddress();

        $coupon = [];
        if (count($basket) > 0) {
            $discounts = \Bitrix\Sale\Discount::buildFromBasket($basket, new \Bitrix\Sale\Discount\Context\Fuser($basket->getFUserId(true)));
            $r = $discounts->calculate();

            // получение данных о корзине с применёнными купонами
            // не работает
//            if ($r->isSuccess()) {
//                $result = $r->getData();
//                $basket->applyDiscount($result['BASKET_ITEMS']);
//            } else {
//                $error = $r->getErrorMessages();
//            }
            // получение всех примененных купонов
            foreach (\Bitrix\Sale\DiscountCouponsManager::get() as $key => $value) {
                $coupon[] = $key;
            }
        }

        $premiumbonus = new CPremiumBonus;
        $bonusCount = 0;
        if ($premiumbonus->check_auth()) {
            $arr = ['+', '-', '(', ')', ' '];
            $bonusData = $premiumbonus->getWriteOffRequest(str_replace($arr, '', CUser::GetByID(self::geUserId())->arResult[0]['PERSONAL_PHONE'])); // телефон вида 79251234567
            if ($bonusData['success']) {
                $bonusCount = $bonusData['balance'];
            }
        }

        // максимальное число списываемых бонусов - 20% но сумма для оплаты не может быть меньше 950
        $totalPrice = (int) self::getTotalPrice(); //$basket->getPrice() тут не считает скидку
        $maxCountUsabilityBonuses = (int) ($totalPrice / 5);
        if (((int) $totalPrice - $maxCountUsabilityBonuses) < 950) {
            $maxCountUsabilityBonuses = (int) $totalPrice - 950;
            if ($maxCountUsabilityBonuses < 0) {
                $maxCountUsabilityBonuses = 0;
            }
        }
        //$bonusesForAdding = (int) ($basket->getBasePrice() / 20);
        $bonusesForAdding = (int) ($totalPrice / 20);

        $product = [
            'items' => self::getBasketItems(),
            'removed_items' => self::getRemovedBasketItems(),
            'totalPrice' => $totalPrice,
            'discountPrice' => (int) $basket->getPrice(),
            'discount' => (int) $basket->getBasePrice() - $totalPrice,
            'bonuses' => [
                'add' => $bonusesForAdding, //бонусы для начисления
                'max' => $maxCountUsabilityBonuses > $bonusCount ? $bonusCount : $maxCountUsabilityBonuses, //бонусы для списания (если это число больше, чем всего бонусов, то показываем количество бонусов)
                'total' => $bonusCount, //кол-во бонусов юзера
                'pay' => self::getBonuses(), // кол-во примененных бонусов
            ],
            'minPrice' => $address['isPickup'] ? MIN_PRICE_TO_PICKUP_ORDER : MIN_PRICE_TO_ORDER, // если самовывоз то 1 иначе 950
            'coupon' => $coupon,
            'deliveryDelay' => [
                'isDeliveryDelay' => self::$isDeliveryDelay,
                'dateDeliveryDelay' => self::$timeDelivery,
            ],
        ];
        return $product;
    }

    /**
     * получение данных о товарах
     *
     * @return array
     */
    public static function getBasketItems()
    {
        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
        // пересчёт с учётом скидок
        $showPrice = [];
        if (count($basket) > 0) {
            $discounts = \Bitrix\Sale\Discount::buildFromBasket($basket, new \Bitrix\Sale\Discount\Context\Fuser($basket->getFUserId(true)));
            $discounts->calculate();
            $showPrice = $discounts->getShowPrices();
        }
        // получение описания товара
        $items = [];
        foreach ($basket as $item) {
            $structure = '';
            $res = CIBlockElement::GetList([], ["ID"=>$item->getProductId()]);
            if ($ob = $res->GetNext()){
                $structure = $ob['PREVIEW_TEXT'];
            }
            $itemArr = [
                'id' => $item->getProductId(),
                'alias' => $item->getField('DETAIL_PAGE_URL'),
                'img' =>  self::getProductsImg($item->getProductId()),
                'title' => $item->getField('NAME'),
                'weight' => $item->getWeight(),
                'structure' => $structure,
                'price' => $item->getBasePrice(), // базовая цена без скидки
                'discountPrice' => $showPrice['BASKET'][$item->getId()]['REAL_PRICE'], // цена со скидкой
                'discount' => $showPrice['BASKET'][$item->getId()]['REAL_DISCOUNT'], // размер скидки в руб
                'discountPercent' => $showPrice['BASKET'][$item->getId()]['SHOW_DISCOUNT_PERCENT'], // процент скидки
                'count' => $item->getQuantity(),
                'badges' => self::getBadges($item->getProductId()),
            ];
            $items[] = $itemArr;
        }
        return $items;
    }

    /**
     * Получение суммы в корзине с учетом скидок
     *
     * @return int
     */
    public static function getTotalPrice()
    {
        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
        // пересчёт с учётом скидок
        $showPrice = [];
        $totalPrice = 0;
        if (count($basket) > 0) {
            $discounts = \Bitrix\Sale\Discount::buildFromBasket($basket, new \Bitrix\Sale\Discount\Context\Fuser($basket->getFUserId(true)));
            $discounts->calculate();
            $showPrice = $discounts->getShowPrices();
        }
        foreach ($basket as $item) {
            $totalPrice = ($showPrice['BASKET'][$item->getId()]['REAL_PRICE'] * $item->getQuantity()) + $totalPrice;
        }

        return $totalPrice;
    }

    /**
     * Получение товаров, которые попали в стоп-лист
     *
     * @return array
     */

    public static function getRemovedBasketItems()
    {
        $removedItems = actualizationBasket();
        return $removedItems['delBasketItems'];
    }

    /**
     * Получаем бейджи для товара
     *
     * @param $id
     * @return array
     */
    private static function getBadges($id)
    {
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_DELIVERY_DELAY", "PROPERTY_PICKUP", "PROPERTY_TIME_ORDER");
        $arFilter = Array("ID" => intval($id));
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
        $badges = [];
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            if ($arFields['PROPERTY_DELIVERY_DELAY_VALUE']) {
                $badges[] = 'isDeliveryDelay';
                self::$isDeliveryDelay = true;
                $hours = $arFields['PROPERTY_TIME_ORDER_VALUE'];
                self::$deliveryHours = $hours;
                self::$timeDelivery = date('d-m-Y H:i', strtotime( " + $hours hours"));
            }
            if($arFields['PROPERTY_PICKUP_VALUE']){
                $badges[] = 'isPickup';
            }
        }
        return $badges;
    }

    /**
     * Получение нужных полей пользователя
     *
     * @param $user
     * @return mixed
     */
    private static function getUserFields($user)
    {
        $data['LOGIN'] = $user['LOGIN'];
        $data['EMAIL'] = $user['EMAIL'];
        $data['NAME'] = $user['NAME'];
        $data['LAST_NAME'] = $user['LAST_NAME'];
        $data['PERSONAL_PHONE'] = $user['PERSONAL_PHONE'];
        $data['session_id'] = bitrix_sessid();

        $data['PERSONAL_CITY'] = $user['PERSONAL_CITY'];
        $data['UF_METRO'] = $user['UF_METRO'];
        $data['PERSONAL_STREET'] = $user['PERSONAL_STREET'];
        $data['UF_HOUSE'] = $user['UF_HOUSE'];
        $data['UF_HOUSING'] = $user['UF_HOUSING']; // корпус
        $data['UF_BUILDING'] = $user['UF_BUILDING']; // здание
        $data['UF_ENTRACE'] = $user['UF_ENTRACE']; // подъезд
        $data['UF_FLOOR'] = $user['UF_FLOOR']; // этаж
        $data['UF_APARTAMENT1'] = $user['UF_APARTAMENT1']; // квартира

        // данные о предзаказе
        $data['isDeliveryDelay'] = self::$isDeliveryDelay;
        $data['timeDelivery'] = self::$timeDelivery;
        $data['deliveryHours'] = self::$deliveryHours;

        //подписан ли пользователь
        CBitrixComponent::includeComponentClass('custom:user.new');
        $data['isSubscribed'] = UserNew::checkUserSubscribe($user['ID'],$user['EMAIL']);
        // данные о премиум бонусах
        $premiumbonus = new CPremiumBonus;
        $data['BonusAuthorisation'] = (int) $premiumbonus->check_auth();
        $arr = ['+', '-', '(', ')', ' '];
        $bonusData = $premiumbonus->getWriteOffRequest(str_replace($arr, '', $user['PERSONAL_PHONE'])); // телефон вида 79251234567

        if ($bonusData['success']) {
            $data['BonusCount'] = $bonusData['balance'];
        } else {
            $data['BonusCount'] = 0;
        }

        return $data;
    }

    /**
     * Получение id пользователя
     *
     * @return mixed
     */
    private static function geUserId()
    {
        return CUser::GetID();
    }

    /**
     * получение картинки для продукта
     *
     * @param $id
     * @return string
     */
    private static function getProductsImg($id)
    {
        $img = '';
        $prop = CIBlockElement::GetByID($id);
        if($arElement = $prop->Fetch())
        {
            $arFile = CFile::GetFileArray($arElement["PREVIEW_PICTURE"]);
            if($arFile)
                $img = $arFile["SRC"];
        }
        if (!$img) {
            $img = '/local/templates/mumu_v20/i/no-photo-menu1.png';
        }
        return $img;
    }

    /**
     * Изменение данных о кол-ве приборов
     */
    public function setGuestCountAction()
    {
        $result = new CommonResponse();
        $session = \Bitrix\Main\Application::getInstance()->getSession();
        try {
            if (isset($_GET['GuestCount'])) {
                $count = $_GET['GuestCount'];
                $session->set('GuestCount', $count);
                $response = 'Кол-во приборов записанно в сессию';
            } else {
                $response = 'Кол-во приборов не записанно в сессию';
            }
            if (null !== $response) {
                $result->setSuccessData($response);
            } else {
                $result->setSuccessData('Нет данных');
            }
        } catch (Exception $e) {
            $result->setError($e->getMessage(), CommonResponse::ERROR_BAD_DATA);
        }
        return $result->returnResponse();
    }

    /**
     * Возвращение данных о кол-ве приборов
     */
    public function getGuestCountAction()
    {
        $result = new CommonResponse();
        $session = \Bitrix\Main\Application::getInstance()->getSession();
        try {
            $response = null;
            if ($session['GuestCount']>0) {
                $response = $session['GuestCount'];
            }
            if (null !== $response) {
                $result->setSuccessData($response);
            } else {
                $result->setSuccessData('Нет данных');
            }
        } catch (Exception $e) {
            $result->setError($e->getMessage(), CommonResponse::ERROR_BAD_DATA);
        }
        return $result->returnResponse();
    }

    /**
     * Возврашещние информации о заказе
     */
    public function getOrderInfoAction()
    {
        $result = new CommonResponse();
        try {
            //$order->getUserId();
            $id = $_GET['id'];
            $response = null;
            if ($id > 0) {
                $response = self::getOrderInfo($id);
            }
            if (null !== $response) {
                $result->setSuccessData($response);
            } else {
                $result->setSuccessData('Нет данных');
            }
        } catch (Exception $e) {
            $result->setError($e->getMessage(), CommonResponse::ERROR_BAD_DATA);
        }
        return $result->returnResponse();
    }

    /**
     * Получение адреса доставки или кафе в случае самовывоза
     *
     * @return array
     */
    public static function getAddressNew($sJsonDeliveryAddress) {
        $result = [];
        if ($sJsonDeliveryAddress!=''){
            $data = json_decode($sJsonDeliveryAddress);
            $address = [
                'isPickup' => false,
                'data' => [],
            ];
            if (!$data->is_sooner_better) {
                $dateTime = date('d-m-Y H:i', strtotime($data->date_time->date . ' ' . $data->date_time->h . ':' . $data->date_time->m));
                $timeDelivery = [
                    'is_sooner_better' => 2,
                    'isCorrectDate' => strtotime($dateTime) > strtotime(date('d-m-Y H:i')),
                    'date_time' => $dateTime,
                ];
            } else {
                $timeDelivery = [
                    'is_sooner_better' => 1,
                    'isCorrectDate' => true,
                    'date_time' => '',
                ];
            }
            if ($data->is_pickup) {
                $address = [
                    'isPickup' => true,
                    'isCorrectDate' => true,
                    'data' => self::getCafeInfoNew($data->address->terminal_id),
                ];
            } else {
                $address = [
                    'isPickup' => false,
                    'isCorrectDate' => true,
                    //'data' => self::getDeliveryAddress(),
                    'data' => '',
                ];
            }
            return array_merge($address, $timeDelivery);

        }
        return $result;
    }

    /**
     * Получение всех данных о кафе по terminal_id
     *
     * @return array
     */
    private static function getCafeInfoNew($TerminalId)
    {
        $pointList = getPointList();
        $response = [];
        if ($TerminalId!=''){
            foreach ($pointList as $point) {
                if ($point['terminal_id'] === $TerminalId) {
                    $response = [
                        'name' => $point['title'],
                        'address' => $point['address'],
                        'workTime' => $point['opening_hours'],
                        'description' => $point['opening_hours'],
                        'phone' => $point['phone_number'],
                        'terminal_id' => $point['terminal_id'],
                    ];
                }
            }
        }

        return $response;
    }

    /**
     * Получение адреса доставки
     *
     * @return array
     */
    private static function getDeliveryAddressNew($sJsonDeliveryAddress)
    {
        if ($sJsonDeliveryAddress!='') {
            $data = json_decode($sJsonDeliveryAddress)->address;
            $response = [
                'full' => $data->full, //полный адрес
                'city' => $data->city,
                'street' => $data->street,
                'house' => $data->home,
                'housing' => $data->housing, // Корпус
                'building' => $data->block, // Строение
                'floor' => '', // Этаж
                'entrance' => '', // Подъезд
                'apartment' => '', // Квартира
                'code' => '', // Домофон
                'tableware' => '', // Приборы
                'terminal_id' => $data->terminal_id,
            ];
        } else {
            $user = CUser::GetByID(self::geUserId())->arResult[0];
            $data = self::getUserFields($user);
            $response = [
                'full' => '', //полный адрес
                'city' => $data['PERSONAL_CITY'],
                'street' => $data['PERSONAL_STREET'],
                'house' => $data['UF_HOUSE'],
                'housing' => $data['UF_HOUSING'], // Корпус
                'building' => $data['UF_BUILDING'], // Строение
                'floor' => $data['UF_FLOOR'], // Этаж
                'entrance' => $data['UF_ENTRACE'], // Подъезд
                'apartment' => $data['UF_APARTAMENT1'], // Квартира
                'code' => '', // Домофон
                'tableware' => '', // Приборы
                'terminal_id' => '',
            ];
        }
        return $response;
    }

    /**
     * Возвращает список заказов
     *
     * @return mixed
     */
    public function getOrdersHistoryNewAction()
    {
        $result = new CommonResponse();

        try {
            $response = self::getOrdersHistoryNew();

            if(null !== $response) {
                $result->setSuccessData($response);
            } else {
                $result->setSuccessData('Нет данных');
            }
        } catch (Exception $e) {
            $result->setError($e->getMessage(), CommonResponse::ERROR_BAD_DATA);
        }
        return $result->returnResponse();
    }

    /**
     * Получение истории заказов
     *
     * @return array
     */
    public static function getOrdersHistoryNew()
    {
        $parameters = [
            'filter' => [
                'USER_ID' => self::geUserId(),
                'STATUS_ID' => ['F','N'],
            ],
            'order' => ['ID' => 'DESC'],
            //'limit' => 10,
        ];

        $dbRes = \Bitrix\Sale\Order::getList($parameters);

        $response = [];
        $sAddress = '';
        while ($arOrder = $dbRes->fetch())
        {

            $orderInfo['total'] = (int)$arOrder['PRICE'];
            $orderInfo['id'] = $arOrder['ID'];
            $date = $arOrder['DATE_INSERT'];
            $orderInfo['date'] = $date->toString() ;
            $response[] = $orderInfo;
        }
        return $response;

    }

    public function getOrderInfo($id){
        $response = null;
        if ($id > 0) {
            $iDeliveryId = '';
            $order = Sale\Order::load($id);
            $sJsonDeliveryAddress = '';
            $sJsonModifiers = '';
            $modifiers = [];
            $orderUserId =$order->getUserId();

            //сделаем проверку на пользователя
            if (self::checkIdUser($orderUserId)) {
                //св-ва заказа
                $propertyCollection = $order->getPropertyCollection();
                foreach ($propertyCollection as $propertyItem) {
                    $propValue = '';
                    $propCode = $propertyItem->getField('CODE');
                    if ($propCode == 'EMAIL') {
                        $propValue = $propertyItem->getValue();
                    } else {
                        $propValue = $propertyItem->getViewHtml();
                    }

                    if ($propCode == 'DELIVERY_ADDRESS') {
                        $sJsonDeliveryAddress = $propertyItem->getValue();
                    }
                    if ($propCode == 'MODIFIERS') {
                        $sJsonModifiers = $propertyItem->getValue();
                    }

                    $response['props'][mb_strtolower($propertyItem->getField('CODE'))] = $propValue;
                }

                //для определения модификатора
                if ($sJsonModifiers != '') {
                    $modifiers = json_decode($sJsonModifiers, true);
                }

                //платежки заказа
                $paymentCollection = $order->getPaymentCollection();
                foreach ($paymentCollection as $payment) {
                    if (!$payment->isInner()) {
                        $response['payment'] = $payment->getPaymentSystemName();
                    }
                    $isInnerPs = $payment->isInner(); // true, если это оплата с внутреннего счета
                }

                //службы доставки заказа
                $shipmentCollection = $order->getShipmentCollection();
                foreach ($shipmentCollection as $shipment) {
                    $response['delivery'] = $shipment->getDeliveryName();
                    $iDeliveryId = $shipment->getDeliveryId();
                }

                // получим адрес доставки или кафе для самовывоза строкой
                if ($iDeliveryId == 3) { //самовывоз
                    if ($sJsonDeliveryAddress != '') {
                        $data = json_decode($sJsonDeliveryAddress)->address;
                        $sAddress = $data->full; //полный адрес
                    }
                } elseif ($iDeliveryId == 2) { //Доставка курьером
                    $sAddress = $response['props']['city'] . ", " . $response['props']['address'] . " " . $response['props']['house'];
                }
                $response['address_string'] = $sAddress;

                $response['date'] = $order->getDateInsert()->format('d.m.Y');
                $response['date_full'] = FormatDate('l, d F Y, H:i', $order->getDateInsert()->getTimestamp());
                $response['price'] = $order->getPrice(); // Сумма заказа
                $response['discount'] = $order->getDiscountPrice(); // Размер скидки

                //разберем сохраненное св-во в json
                if ($sJsonDeliveryAddress != '') {
                    $response['address'] = self::getAddressNew($sJsonDeliveryAddress);
                }

                //получим товары в заказе
                $response['products'] = self::getOrder($id);

                //получим статус заказа
                $statusId = $order->getField('STATUS_ID');
                switch ($statusId) {
                    case 'N':
                        $statusId = "Принят";
                        break;
                    case 'F':
                        $statusId = "Выполнен";
                        break;
                    case 'DN':
                        $statusId = "Ожидает обработки";
                        break;
                    case 'DF':
                        $statusId = "Отгружен";
                        break;
                }
                $response['status_order'] = $statusId;

                //сопоставим товары в корзине с модификаторами
                foreach ($response['products'] as &$product) {
                    $product_id = intval($product['product_id']);
                    if ($modifiers[$product_id]['idModifier'] != '') {
                        $product['modifiers'] = [
                            'name' => $modifiers[$product['product_id']]['name'],
                            'amount' => $modifiers[$product['product_id']]['amount'],
                        ];
                    } else {
                        $product['modifiers'] = [];
                    }
                }

                //комментарий
                $response['user_description'] = $order->getField('USER_DESCRIPTION');

                //бонусы
                $basket = Sale\Basket::loadItemsForOrder($order);
                //$bonusesForAdding = (int)($basket->getBasePrice() / 20);
                $bonusesForAdding = (int)($basket->getPrice() / 20);

                $response['bonuses'] = [
                    'add' => $bonusesForAdding, //бонусы для начисления
                    'max' => 1, //бонусы для списания (если это число больше, чем всего бонусов, то показываем количество бонусов)
                    'total' => 1, //кол-во бонусов юзера
                    'pay' => 1, // кол-во примененных бонусов
                ];
            }
        }
        return $response;
    }

    /**
     * сделаем проверку совпадает ли текущий пользоватлеь и пользователь для проверки. Чтобы клиент мог просматривать только свою информацию
     * @param $idUserForCheck
     * @return false
     */
    private static function checkIdUser($idUserForCheck)
    {
        $responce = false;
        if ((\Bitrix\Main\Engine\CurrentUser::get()->getId(
            )) && ((int)$idUserForCheck > 0) && (\Bitrix\Main\Engine\CurrentUser::get()->getId() == (int)$idUserForCheck)) {
            $responce = true;
        }
        return $responce;
    }

    /**
     * Данные пользователя
     */
    public function getUserDataAction()
    {
        $result = new CommonResponse();
        try {
            $response = self::getUserData();

            if ($response['isAuthorized']) {
                $result->setSuccessData($response);
            } else {
                $result->setSuccessData('Нет данных');
            }
        } catch (Exception $e) {
            $result->setError($e->getMessage(), CommonResponse::ERROR_BAD_DATA);
        }
        return $result->returnResponse();
    }
}
