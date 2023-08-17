@extends('layouts/front')

@section('content')

<style>
    .search {
        position: relative;
        box-shadow: 0 0 40px rgba(51, 51, 51, .1)
    }

    .search input {
        height: 60px;
        text-indent: 25px;
        border: 2px solid #d6d4d4
    }

    .search input:focus {
        box-shadow: none;
        border: 2px solid blue
    }

    .search .fa-search {
        position: absolute;
        top: 22px;
        left: 16px
    }

    .search button {
        position: absolute;
        top: 5px;
        right: 5px;
        height: 50px;
        width: 110px;
        background: #1991ce;
    }

</style>
    <div class="full-height">
        <h1 class="text-center mb-5" style="color: #1991ce; padding-top: 130px;">VOS COLIS TROUVERONT TOUJOURS
            DESTINATION</h1>
        <form method="GET" action="{{ route('search_exp')}}" class="d-flex justify-content-center mt-5">
            @csrf
            {{-- <input type="text" class="search_home" name="search_exp" placeholder="Votre numéro de suivi">
            <button type="submit" class="btn track_button">Suivi</button> --}}

            <div class="container">
                <div class="row d-flex justify-content-center align-items-center">
                    <div class="col-md-8">
                        <div class="search">
                            <i class="fa fa-search"></i>
                            <input type="text" name="search_exp" class="form-control" placeholder="Votre numéro de suivi">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


        <div class="scroll">
            <a href="#Accuiel" class="scroll-down d-none d-lg-block text-effect" address="true"><i
                    class="fas fa-arrow-circle-down"></i></a>
        </div>
        <img class="mx-auto d-block" data-aos="fade-up" width="80%" src="/assets/front/homepageGOtawsil.png">
    </div>


    <section id="Accuiel" class="Accuiel container pb-5">
        <p class="text-center p-4 px-md-0"><strong class="text-primary">GO Tawsil</strong> est la nouvelle solution
            de
            livraison
            pour vous et votre activité e-commerce.<br>
            Avec une offre complète, nous livrons n'importe quoi n'importe où, aux meilleurs prix du marché.
            Tentez l'expérience GO Tawsil</p>


        <div class="first_tab d-none d-lg-block">
            <!-- Tab items -->
            <div data-aos="fade-up" style="padding-left: 80px;padding-right: 80px;"
                class=" tabs row align-items-center justify-content-center">
                <div class="col-4 tab-item active ">
                    <center>
                        <div class="experience-go d-flex align-items-center justify-content-center" style="">
                            <svg version="1.0" xmlns="http://www.w3.org/2000/svg" class="svg-icon" width="75pt"
                                viewBox="0 0 155.000000 160.000000" preserveAspectRatio="xMidYMid meet">

                                <g transform="translate(0.000000,160.000000) scale(0.100000,-0.100000)" stroke="none">
                                    <path d="M675 1510 c-226 -38 -446 -241 -505 -465 l-15 -60 -52 -5 -51 -5 80
                                            -140 c44 -77 86 -146 93 -153 13 -14 41 26 151 218 l43 75 -51 3 c-49 3 -50 4
                                            -43 30 35 146 170 285 322 332 117 36 277 23 385 -31 51 -26 150 -120 186
                                            -177 116 -182 93 -433 -53 -591 -68 -73 -118 -107 -197 -136 l-63 -23 -3 -77
                                            -3 -76 48 6 c96 14 258 107 334 193 53 60 112 161 140 237 19 52 22 80 22 205
                                            0 165 -10 206 -83 331 -76 129 -212 240 -358 290 -65 23 -246 33 -327 19z" />
                                    <path d="M714 1317 c-3 -8 -4 -47 -2 -87 2 -56 7 -75 21 -84 33 -21 52 4 55
                                            75 4 91 -2 109 -39 109 -16 0 -32 -6 -35 -13z" />
                                    <path d="M1022 1126 c-28 -18 -98 -73 -156 -121 l-106 -87 -72 46 c-81 52
                                            -135 62 -122 24 12 -38 85 -154 124 -197 54 -59 89 -57 150 8 69 74 211 255
                                            243 311 27 47 28 50 9 50 -11 0 -43 -15 -70 -34z" />
                                    <path d="M1074 824 c-10 -41 10 -50 105 -46 l82 4 -3 31 -3 32 -87 3 -87 3 -7
                                            -27z" />
                                    <path d="M407 721 c-104 -34 -175 -98 -219 -198 -17 -38 -21 -68 -22 -139 0
                                            -80 4 -98 29 -150 38 -78 110 -146 182 -175 86 -34 208 -33 285 3 69 32 132
                                            94 173 168 28 51 30 63 30 155 0 90 -3 105 -28 153 -85 161 -265 238 -430 183z
                                            m226 -81 c55 -28 102 -74 135 -130 23 -39 27 -56 27 -125 0 -89 -12 -119 -72
                                            -186 -109 -120 -289 -125 -406 -11 -101 98 -101 297 0 394 91 87 216 110 316
                                            58z" />
                                    <path d="M480 575 c0 -19 -8 -28 -36 -40 -102 -42 -81 -131 41 -178 45 -17 60
                                            -28 60 -43 0 -16 -7 -19 -55 -19 -30 0 -65 4 -77 8 -18 7 -22 3 -28 -24 -8
                                            -37 1 -46 58 -55 31 -6 37 -11 37 -31 0 -21 4 -24 33 -21 27 2 33 8 35 30 2
                                            20 9 28 23 28 25 0 77 49 84 80 9 40 -26 85 -82 105 -73 26 -93 37 -93 51 0
                                            24 59 30 129 12 17 -4 34 49 18 59 -5 3 -24 9 -41 12 -22 5 -32 13 -34 29 -3
                                            18 -10 22 -38 22 -30 0 -34 -3 -34 -25z" />
                                </g>
                            </svg>
                        </div>

                        <p class="py-2 experience-text text-center px-5"> Economisez du temps et de l'argent</p>
                    </center>
                </div>
                <div class="col-4 tab-item ">
                    <center id="experience">
                        <div class="experience-go d-flex align-items-center justify-content-center" style="">
                            <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="75pt"
                                viewBox="0 0 155.000000 160.000000" preserveAspectRatio="xMidYMid meet">

                                <g transform="translate(0.000000,160.000000) scale(0.100000,-0.100000)" stroke="none">
                                    <path d="M713 1531 c-68 -23 -119 -63 -156 -122 l-30 -48 -126 -40 c-69 -22
                                            -182 -57 -250 -78 -74 -23 -128 -45 -135 -56 -9 -14 5 -32 87 -115 l98 -99
                                            -61 -102 c-34 -56 -60 -108 -57 -115 3 -8 45 -27 94 -43 l88 -30 5 -243 5
                                            -243 252 -95 252 -94 283 94 283 95 3 249 2 250 98 30 c73 22 98 34 100 48 3
                                            13 -25 48 -89 112 l-94 94 95 95 c80 80 93 98 84 112 -7 11 -87 41 -224 83
                                            l-213 65 -36 60 c-79 130 -220 184 -358 136z m169 -53 c61 -18 133 -89 154
                                            -150 30 -90 9 -182 -57 -247 -52 -52 -86 -66 -169 -66 -85 0 -119 14 -174 71
                                            -91 94 -87 233 9 329 69 70 146 91 237 63z m-372 -235 c1 -27 7 -66 15 -88 8
                                            -21 13 -40 11 -42 -1 -1 -67 -25 -147 -52 l-144 -49 -75 73 c-41 41 -72 76
                                            -70 78 5 4 390 125 403 126 4 1 7 -20 7 -46z m787 -29 c90 -27 163 -52 163
                                            -55 0 -3 -33 -37 -73 -76 l-73 -71 -119 41 c-66 22 -121 41 -122 42 -2 1 6 25
                                            17 53 11 29 20 68 20 88 0 25 4 35 13 32 6 -3 85 -27 174 -54z m-691 -184 c67
                                            -59 111 -75 204 -75 89 0 144 19 198 68 l22 21 91 -29 c50 -16 92 -32 94 -35
                                            2 -3 -95 -39 -216 -79 l-219 -72 -220 72 c-120 41 -217 76 -214 79 4 4 204 75
                                            223 79 2 1 19 -12 37 -29z m-104 -171 c125 -41 228 -77 228 -81 0 -4 -20 -41
                                            -45 -82 l-45 -74 -237 79 c-129 43 -238 80 -240 83 -8 8 89 157 101 153 6 -2
                                            113 -37 238 -78z m888 6 c41 -41 71 -75 67 -75 -6 0 -329 -100 -449 -140 l-58
                                            -19 -55 71 c-30 39 -55 74 -55 77 0 6 444 158 465 160 6 1 44 -33 85 -74z
                                            m-451 -295 c8 0 86 23 172 50 86 28 162 50 168 50 8 0 11 -65 11 -214 l0 -214
                                            -240 -80 -240 -80 0 315 0 316 57 -72 c31 -39 63 -71 72 -71z m-194 -478 c-4
                                            -4 -86 25 -337 120 l-78 30 0 210 c0 164 3 209 12 205 109 -40 309 -99 321
                                            -94 9 3 31 32 49 64 l33 58 3 -294 c1 -162 0 -296 -3 -299z" />
                                    <path d="M865 1289 l-83 -81 -31 23 c-25 18 -33 20 -46 9 -8 -7 -15 -18 -15
                                            -24 0 -17 73 -86 91 -86 20 0 209 191 209 212 0 14 -19 28 -37 28 -3 0 -43
                                            -36 -88 -81z" />
                                    <path d="M460 425 c-17 -20 -5 -45 20 -45 11 0 23 7 26 15 6 15 -11 45 -26 45
                                            -4 0 -13 -7 -20 -15z" />
                                    <path d="M571 386 c-19 -22 -2 -44 46 -60 34 -12 50 -13 60 -5 22 19 15 39
                                            -19 53 -60 25 -75 27 -87 12z" />
                                    <path d="M454 295 c-10 -25 12 -40 115 -80 83 -31 96 -34 109 -21 25 25 6 43
                                            -80 76 -112 42 -136 47 -144 25z" />
                                </g>
                            </svg>
                        </div>

                        <p class="py-2 experience-text text-center px-5"> Expédiez simplement vos
                            colis</p>
                    </center>
                </div>
                <div class="col-4 tab-item ">
                    <center>
                        <div class="experience-go d-flex align-items-center justify-content-center" style="">
                            <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="75pt"
                                viewBox="0 0 155.000000 160.000000" preserveAspectRatio="xMidYMid meet">

                                <g transform="translate(0.000000,160.000000) scale(0.100000,-0.100000)" stroke="none">
                                    <path d="M520 1585 c-218 -49 -410 -220 -479 -426 -62 -188 -39 -394 62 -550
                                            30 -48 457 -520 529 -587 l25 -23 210 228 c298 324 305 332 345 398 61 101 83
                                            190 82 330 0 103 -4 131 -27 198 -69 200 -229 354 -432 417 -91 28 -229 35
                                            -315 15z m243 -150 c123 -32 226 -107 297 -217 53 -81 74 -165 68 -275 -6
                                            -108 -33 -182 -96 -266 -175 -229 -511 -250 -716 -44 -168 168 -190 424 -51
                                            615 116 159 316 234 498 187z" />
                                    <path d="M513 1232 l-132 -78 55 -33 55 -32 102 62 c56 34 115 71 130 82 l29
                                            20 -46 28 c-25 16 -49 29 -53 29 -5 0 -67 -35 -140 -78z" />
                                    <path
                                        d="M663 1151 c-67 -42 -122 -79 -123 -83 0 -3 25 -21 55 -38 51 -29 57
                                            -30 82 -17 48 25 243 139 243 142 0 5 -121 75 -128 74 -4 -1 -62 -36 -129 -78z" />
                                    <path d="M360 962 l0 -157 128 -73 c70 -40 131 -73 136 -72 5 0 9 68 10 151
                                            l1 151 -57 34 -58 33 0 -83 c0 -63 -4 -85 -14 -89 -23 -9 -26 2 -26 102 l0 96
                                            -60 32 -60 32 0 -157z" />
                                    <path d="M805 1041 l-130 -77 -3 -157 c-1 -86 -1 -157 1 -157 2 0 63 35 136
                                            78 l132 77 2 158 c1 86 0 157 -3 156 -3 0 -63 -36 -135 -78z m93 -203 c-3 -27
                                            -12 -37 -76 -75 -41 -24 -76 -43 -78 -43 -2 0 -4 15 -4 33 0 29 6 35 73 74 39
                                            22 76 41 80 42 5 1 7 -13 5 -31z" />
                                    <path d="M1425 746 c-20 -17 -27 -28 -20 -35 12 -12 79 35 70 50 -9 14 -17 11
                                            -50 -15z" />
                                    <path d="M1364 667 c-8 -22 0 -82 11 -80 11 3 14 82 3 89 -4 3 -11 -1 -14 -9z" />
                                    <path d="M1390 539 c0 -15 39 -69 51 -69 15 0 10 20 -12 51 -21 29 -39 38 -39
                                            18z" />
                                    <path d="M1470 435 c0 -9 7 -30 16 -47 11 -22 19 -27 27 -19 8 8 6 20 -6 46
                                            -17 36 -37 47 -37 20z" />
                                    <path d="M1493 318 c-24 -39 -33 -62 -25 -70 12 -12 52 38 52 63 0 22 -15 25
                                            -27 7z" />
                                    <path d="M1392 208 c-32 -20 -42 -38 -22 -38 18 0 70 30 70 40 0 18 -17 18
                                            -48 -2z" />
                                    <path d="M1261 154 c-25 -10 -31 -18 -24 -25 15 -15 83 7 83 27 0 18 -16 17
                                            -59 -2z" />
                                    <path d="M1154 130 c-38 -8 -56 -19 -49 -29 5 -9 81 2 92 13 13 13 -13 23 -43
                                            16z" />
                                    <path d="M987 103 c-17 -16 -4 -24 35 -21 55 4 64 28 11 28 -22 0 -43 -3 -46
                                            -7z" />
                                    <path d="M873 83 c-13 -2 -23 -11 -23 -20 0 -12 7 -14 37 -8 44 8 53 13 53 26
                                            0 9 -26 10 -67 2z" />
                                    <path d="M760 42 c-35 -17 -43 -40 -13 -34 38 8 63 23 63 37 0 19 -7 19 -50
                                            -3z" />
                                </g>
                            </svg>
                        </div>
                        <p class="py-2 experience-text text-center px-5"> Suivez vos livraisons en continu</p>
                    </center>
                </div>
                <!--                <div class="tab-item">-->
                <!--                    <i class="tab-icon fas fa-pen-nib"></i>-->
                <!--                    Vue.JS-->
                <!--                </div>-->
                <div class="line"></div>
            </div>

            <!-- Tab content -->
            <div class="px-5 tab-content">
                <div class="tab-pane  active">
                    <div class="row px-5 py-3">
                        <div class="col-md-5 col-12 d-flex align-items-center" data-aos="fade-up-right">
                            <h2 class="text_tabs p-3 ">Profitez de la livraison express en un jour ouvré et
                                moins cher qu'ailleurs.</h2>
                        </div>
                        <div class="col-md-7 col-12 px-5" data-aos="fade-left">
                            <img src="/assets/front/savetime.jpg" class=" img-fluid" alt="">
                        </div>
                    </div>
                </div>
                <div class="tab-pane">
                    <div class="row px-5 py-3">
                        <div class="col-md-7 col-12 px-5">
                            <img src="/assets/front/printorscan.jpg" class="img-fluid " alt="">
                        </div>
                        <div class="col-md-5 col-12  d-flex align-items-center">
                            <h2 class="text_tabs p-3">Imprimez ou scannez l'étiquette d'expédition en 1 seul
                                clic et renseignez les informations..</h2>
                        </div>

                    </div>
                </div>

                <div class="tab-pane">
                    <div class="row px-5 py-3 d-flex align-items-center">
                        <div class="col-md-5 col-12">
                            <h2 class="text_tabs p-3 align-middle">Vos clients seront notifiés par e-mail et peuvent
                                suivre leurs commandes en ligne.</h2>
                        </div>
                        <div class="col-md-7 col-12 px-5">
                            <img src="/assets/front/package.jpg" class="img-fluid" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <!--                <div class="tab-pane">-->
            <!--                    <h2>Vue.js</h2>-->
            <!--                    <p>Vue (pronounced /vjuː/, like view) is a progressive framework for building user interfaces. Unlike other monolithic frameworks, Vue is designed from the ground up to be incrementally adoptable. </p>-->
            <!--                </div>-->
        </div>


        <div class="mobile_tab d-lg-none">
            <h2 class="py-2 experience-text-mobile"> Economisez du temps et de l'argent</h2>
            <div class="row px-1 py-3">
                <div class="col-md-5 col-12 d-flex align-items-center">
                    <p class="text_tabs align-middle p-3 ">Profitez de la livraison express en un jour ouvré et
                        moins cher qu'ailleurs.</p>
                </div>
                <div class="col-md-7 col-12 ">
                    <img src="/assets/front/savetime.jpg" class=" img-fluid" alt="">
                </div>
            </div>
            <h2 class="py-2 experience-text-mobile"> Expédiez simplement vos
                colis</h2>
            <div class="row px-1 py-3">
                <div class="col-md-7 col-12 px-5 d-flex align-items-center">
                    <img src="/assets/front/printorscan.jpg" class="img-fluid " alt="">
                </div>
                <div class="col-md-5 col-12">
                    <p class="text_tabs align-middle p-3">Imprimez ou scannez l'étiquette d'expédition en 1 seul
                        clic et renseignez les informations..</p>
                </div>

            </div>
            <h2 class="py-2 experience-text-mobile "> Suivez vos livraisons en continu</h2>
            <div class="row px-1 py-3 d-flex align-items-center">
                <div class="col-md-5 col-12">
                    <p class="text_tabs p-3 align-middle">Vos clients seront notifiés par e-mail et peuvent
                        suivre leurs commandes en ligne.</p>
                </div>
                <div class="col-md-7 col-12 px-5">
                    <img src="/assets/front/package.jpg" class="img-fluid" alt="">
                </div>
            </div>

        </div>
    </section>
    <div id="services" class="services px-5 py-5">
        <h1 class="Nos_Services">Nos Services</h1>
        <p class="text-center px-5">Nos services de livraison sont réputés dans tout le pays pour être l'un des
            plus fiables,
            sûrs et abordables.</p>

        <div class="container py-2 px-3" data-aos="zoom-in">
            <div class="row">
                <div class="col-xl-4 col-6">
                    <div class="serviceBox">
                        <div class="service-icon">
                            <span><i class="fa fa-box-open"></i></span>
                        </div>
                        <h3 class="title">ENVOYER UN COLIS</h3>
                        <p class="description">Envoyer vos colis en
                            quelques clics en toute
                            securité et partout dans
                            le Maroc.</p>
                    </div>
                </div>
                <div class="col-xl-4 col-6">
                    <div class="serviceBox yellow">
                        <div class="service-icon">
                            <span><i class="fas fa-warehouse"></i></span>
                        </div>
                        <h3 class="title">CHOISIR LE POINT RELAIS</h3>
                        <p class="description">Vos clients peuvent choisir le point relais après leur commande.</p>
                    </div>
                </div>
                <div class="col-xl-4 col-6">
                    <div class="serviceBox red">
                        <div class="service-icon">
                            <span><i class="fas fa-file-signature"></i></span>
                        </div>
                        <h3 class="title">EDITER VOS BORDEREAUX</h3>
                        <p class="description">Imprimer tous vos bordereaux en 1 seul clic.</p>
                    </div>
                </div>
                <div class="col-xl-4 col-6">
                    <div class="serviceBox purple">
                        <div class="service-icon">
                            <span><i class="fas fa-truck-loading"></i></span>
                        </div>
                        <h3 class="title">PROGRAMMER UNE COLLECTE</h3>
                        <p class="description">Un véhicule passe collecter vos colis chaque jour.</p>
                    </div>
                </div>
                <div class="col-xl-4 col-6">
                    <div class="serviceBox gray">
                        <div class="service-icon">
                            <span><i class="fas fa-undo"></i></span>
                        </div>
                        <h3 class="title">FACILITER LES RETOURS</h3>
                        <p class="description">Un véhicule passe collecter vos colis chaque jour.</p>
                    </div>
                </div>
                <div class="col-xl-4 col-6">
                    <div class="serviceBox blue">
                        <div class="service-icon">
                            <span><i class="fas fa-map-marked-alt"></i></span>
                        </div>
                        <h3 class="title">SUIVRE EN TEMPS RÉEL</h3>
                        <p class="description">Des alertes vous informent du statut de vos expéditions.</p>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div class="cheztawsil container px-5 py-5">
        <h1 class="Nos_Services">Chez <span style="color: #1991ce">GO</span><span style="color: #c10027">TAWSIL</span>
        </h1>
        <div class="row">
            <div class="col-xl-6 col-12" data-aos="fade-right">
                <p>Vous bénéficiez de :</p>
                <ul>
                    <li>Livraisons Express sous 24h Chrono.</li>
                    <li>Tracking en temps réel et en toute mobilité.</li>
                    <li>Retour de fonds par virements bancaire.</li>
                    <li>Des agences et points de relais partout
                        au Maroc.
                    </li>
                </ul>

            </div>
            <div class="col-xl-6 col-12" data-aos="fade-up">
                <img class="img_slides" src="/assets/front/chezgotawsil.png" alt="">
            </div>
        </div>
    </div>
    <div style="background-color: #f9f9f9">
        <h1 class="Nos_Services py-4">Points de relais</span>
        </h1>

        <center>


            <iframe style="border-radius: 30px;" class="shadow mb-5"
                src="https://www.google.com/maps/d/u/0/embed?mid=19OhonbgqPRy7bmuSGDJDVPdPGZst3icg" width="640"
                height="550"></iframe>
        </center>
    </div>

@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>

    <script>
        AOS.init();
    </script>
    <script>
        const $ = document.querySelector.bind(document);
        const $$ = document.querySelectorAll.bind(document);

        const tabs = $$(".tab-item");
        const panes = $$(".tab-pane");

        const tabActive = $(".tab-item.active");
        const line = $(".tabs .line");

        // SonDN fixed - Active size wrong size on first load.
        // Original post: https://www.facebook.com/groups/649972919142215/?multi_permalinks=1175881616551340
        requestIdleCallback(function() {
            line.style.left = tabActive.offsetLeft + "px";
            line.style.width = tabActive.offsetWidth + "px";
        });

        tabs.forEach((tab, index) => {
            const pane = panes[index];

            tab.onclick = function() {
                $(".tab-item.active").classList.remove("active");
                $(".tab-pane.active").classList.remove("active");

                line.style.left = this.offsetLeft + "px";
                line.style.width = this.offsetWidth + "px";

                this.classList.add("active");
                pane.classList.add("active");
            };
        });
    </script>
@stop
