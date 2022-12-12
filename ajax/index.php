<?php

if(!isset($_GET['ff'])){
    require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
    $APPLICATION->SetTitle("Аякс");
    $APPLICATION->SetPageProperty("isMain","xzx");
    $APPLICATION->SetPageProperty("title","index");
}



    $var = ["one"=>"content1","two"=>"content2"];
    echo json_encode($var);


?>



<?php
    require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>
