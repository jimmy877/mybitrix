<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Главная</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="assets/public/bootstrap/bootstrap.css" rel="stylesheet"  crossorigin="anonymous">
    <link href="assets/public/style/main.css" rel="stylesheet"  crossorigin="anonymous">

</head>
<body id="app">
<script type="application/javascript">
    // new Vue({
    //     data:{ producs:[
    //             { id: 1, title: "My journey with Vue"},
    //             { id: 2, title: 'Blogging with Vue' },
    //             { id: 3, title: 'Why Vue is so fun' }
    //         ]}
    // });

    let data = [
        {
            id: 1,
            title: "Элегантная архитектура",
            subTitle:"под стать вам",
            desc:"Натуральные цвета фасадов сочетаются с природой вокруг комплекса, а дизайн лобби создает атмосферу уюта.",
            img:"assets/public/files/slide1.jpg"
        },
        {
            id: 2,
            title: 'Комфорт и удобство',
            subTitle:"всё под рукой",
            desc:"«Русская Европа» — жилой квартал в Калининграде. Идеальное место для тех, кто считает жизнь в городе преимуществом.",
            img:"assets/public/files/slide2.jpg"
        },
        {
            id: 3,
            title: 'идеальное пространство',
            subTitle:"всё продумано",
            desc:"Ощутите размеренность жизни в окружении природных водоемов, которые видны из окон.",
            img:"assets/public/files/slide1.jpg"
        },
        {
            id: 4,
            title: 'получите вашу квартиру',
            subTitle:"уже сегодня",
            desc:"Льготная ипотека — до 7%, Семейная ипотека — до 6% Стандартная ипотека - до 8%.",
            img:"assets/public/files/slide2.jpg"
        }
    ]

    let testData = [
        { id: 1, title: "One"},
        { id: 2, title: 'Two' },
        { id: 3, title: 'Three' }
    ]
</script>
    <header class="container fixed-top">
        <div class="main-menu row">
            <div class="col-xl-6 col-lg-6 d-none d-sm-none d-lg-flex align-items-center">
                <div class="d-none d-sm-none d-lg-block">
                    <ul class="menu  d-flex">
                        <li><a href="#">О проекте</a></li>
                        <li><a href="#">О компании</a></li>
                        <li><a href="#">Новости</a></li>
                        <li><a href="#">Партнерам</a></li>
                        <li><a href="#">Ход строительства</a></li>
                        <li><a href="#">Ипотека</a></li>
                    </ul>
                </div>

            </div>
            <div class="col-4 col-sm-2 d-flex flex-row justify-content-start align-items-center d-lg-none">
                <div class="menu-burger d-flex flex-column justify-content-center align-items-center">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <div class="logo col-2 col-lg-2 col-sm-6 d-flex align-items-center justify-content-center">
                <img src="assets/public/files/logo1.svg">
            </div>

            <div class="main-phone col-lg-2  d-none d-lg-flex align-items-center justify-content-end">
                8 (800) 511-99-30
            </div>

            <!-- Кнопка Выбора квартиры -->
            <div class=" col-2 col-sm-4 col-lg-2 d-flex align-items-center justify-content-end">
                <div class="find-flat d-flex flex-nowrap align-items-center justify-content-center">
                    <div class="h-animation"></div>
                    <span class="f-ico"></span>
                    <span class="d-none d-sm-flex d-lg-flex ">Выбрать квартиру</span>
                </div>
            </div>
        </div>
    </header>
    <div class="dom-rf"> </div>

    <!-- Первый ээкран сайта -->
    <section class="main-slide row">

           <div class="col-12 d-flex align-items-center justify-content-center flex-wrap">
               <div>
                   <h1> Русская европа </h1>
                   <h2 class="main-slide-h2"> ЖК Адрес счастья</h2>
               </div>
           </div>

    </section>

    <section class="slide-dinamic row">
        <slider class=" col-12"></slider>
    </section>

    <footer>
    </footer>

    <!--<div class="container">
           <div class="row mb-3">
               <div class=" col-sm-1"><div class=" bg-light mystyle">столбец</div></div>
               <div class=" col-sm-1"><div class=" bg-light mystyle">столбец</div></div>
               <div class=" col-sm-1"><div class=" bg-light mystyle">столбец</div></div>
               <div class=" col-sm-1"><div class=" bg-light mystyle">столбец</div></div>
               <div class=" col-sm-1"><div class=" bg-light mystyle">столбец</div></div>
               <div class=" col-sm-1"><div class=" bg-light mystyle">столбец</div></div>
               <div class=" col-sm-1"><div class=" bg-light mystyle">столбец</div></div>
               <div class=" col-sm-1"><div class=" bg-light mystyle">столбец</div></div>
               <div class=" col-sm-1"><div class=" bg-light mystyle">столбец</div></div>
               <div class=" col-sm-1"><div class=" bg-light mystyle">столбец</div></div>
               <div class=" col-sm-1"><div class=" bg-light mystyle">столбец</div></div>
               <div class=" col-sm-1"><div class=" bg-light mystyle">столбец</div></div>
           </div>
           <div class="row gy-3">
               <div class="col-6 col-md-4 "><div class=" bg-light mystyle">столбец</div></div>
               <div class="col-6 col-md-4 "><div class=" bg-light mystyle">столбец</div></div>
               <div class="col-md-4 "><div class=" bg-light mystyle">столбец</div></div>
           </div>
       </div> -->
<script src="https://unpkg.com/http-vue-loader"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@3.0.2/dist/vue.global.js"></script>
<script type="module" src = "assets/public/js/main.js"></script>

<script type="module" src = "assets/public/js/components/slider.js"></script>









</body>
</html>

