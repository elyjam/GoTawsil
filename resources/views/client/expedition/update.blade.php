@extends($layout)

<style>
    .card-panel {
        padding-top: 34px !important;
        padding-bottom: 34px !important;
    }

    input[type=text]:not(.browser-default):focus:not([readonly]) {
        border: 0 !important;
    }


    @media only screen and (max-width: 1100px) {
        .row hr {

            border: 0;
            height: 1px;
            margin: 30px 0px;
            background-image: linear-gradient(to right, rgba(25, 145, 206, 0), rgba(25, 145, 206, 0.75), rgba(25, 145, 206, 0));

        }
    }

    @media only screen and (min-width: 1100px) {

        .card {
            margin: 1rem 15rem 1rem 15rem !important;

        }

        .input-field.col label {
            left: 3.75rem !important;
        }

        .input-field {

            padding-inline: 50px !important;
        }

        .row hr {
            border: 0;
            height: 1px;
            margin: 40px 0px;
            background-image: linear-gradient(to right, rgba(25, 145, 206, 0), rgba(25, 145, 206, 0.75), rgba(25, 145, 206, 0));
        }


    }

    * {
        box-sizing: border-box;
    }

    .containere {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 30px;
    }

    .radio-tile-group {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .radio-tile-group .input-container {
        position: relative;
        height: 7rem;
        width: 8.5rem;
        margin: 0.5rem;
    }

    .radio-tile-group .input-container .radio-button {
        opacity: 0;
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        margin: 0;
        cursor: pointer;
        pointer-events: visible;
    }

    .radio-tile-group .input-container .radio-tile {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        border: 2px solid #079ad9;
        border-radius: 5px;
        padding: 1rem;
        transition: transform 300ms ease;
    }

    .radio-tile-group .input-container .icon svg {
        fill: #079ad9;
        width: 2.6rem;
        height: 2.6rem;
        margin-bottom: 5px;
    }

    .radio-tile-group .input-container .radio-tile-label {
        text-align: center;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #079ad9;
    }

    .radio-tile-group .input-container .radio-button:checked+.radio-tile {
        background-color: #079ad9;
        border: 2px solid #079ad9;
        color: white;
        transform: scale(1.1, 1.1);
    }

    .radio-tile-group .input-container .radio-button:checked+.radio-tile .icon svg {
        fill: white;
        background-color: #079ad9;
    }

    .radio-tile-group .input-container .radio-button:checked+.radio-tile .radio-tile-label {
        color: white;
        background-color: #079ad9;
    }

</style>

@section('content')
    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg-client.jpg" class="breadcrumbs-bg-image"
        style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Modifier l'expédition</span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('Dashboard_Client') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('expedition_list') }}">Gestion des expeditions</a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Modifier l'expédition</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="section">
                    <form method="POST" action="{{ route('expedition_insert') }}">
                        @csrf
                        <br>
                        <div class="card">
                            <div class="card-panel">
                                <div class="row">
                                    <div class="col s12 m12">
                                        <input name='client' id='client' type="text"
                                            value="{{ auth()->user()->ClientDetail->id }}" hidden>
                                        <div class="row">
                                            <div class="containere">
                                                <div class="radio-tile-group">
                                                    <div class="input-container">
                                                        <input class="radio-button tooltipped" type="radio" name='type'
                                                            value='ECOM' data-position="bottom"
                                                            data-tooltip="COLIS CONTRE REMBOURSEMENT" id='ECOM'
                                                            onchange="changetype(this)"
                                                            {{ $record->type == 'ECOM' ? 'checked' : '' }}>
                                                        <div class="radio-tile">
                                                            <div class="icon bike-icon">
                                                                <svg version="1.1" id="Capa_1"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                                                                    y="0px" viewBox="0 0 60 60"
                                                                    style="enable-background:new 0 0 60 60;"
                                                                    xml:space="preserve">
                                                                    <g>
                                                                        <path
                                                                            d="M49.554,0H37H23H10.446L0,16.713V60h23h14h23V16.713L49.554,0z M48.446,2l8.75,14H37V2H48.446z M30,2h5v14h-5h-5V2H30z
                                                                                                                                                              M25,18h1c0,0.552,0.448,1,1,1s1-0.448,1-1h4c0,0.552,0.448,1,1,1s1-0.448,1-1h1v3.586l-1-1l-4,4l-4-4l-1,1V18z M11.554,2H23v14
                                                                                                                                                              H2.804L11.554,2z M25,58v-2.586l1,1l4-4l4,4l1-1V58H25z M37,58v-7.414l-3,3l-4-4l-4,4l-3-3V58H2V18h21v8.414l3-3l4,4l4-4l3,3V18h21
                                                                                                                                                              v40H37z" />
                                                                        <path d="M20,48h20V30H20V48z M22,32h16v14H22V32z" />
                                                                        <path
                                                                            d="M31,42h-6c-0.552,0-1,0.447-1,1s0.448,1,1,1h6c0.552,0,1-0.447,1-1S31.552,42,31,42z" />
                                                                        <path
                                                                            d="M35,42h-1c-0.552,0-1,0.447-1,1s0.448,1,1,1h1c0.552,0,1-0.447,1-1S35.552,42,35,42z" />
                                                                        <path
                                                                            d="M35,34h-6c-0.552,0-1,0.447-1,1s0.448,1,1,1h6c0.552,0,1-0.447,1-1S35.552,34,35,34z" />
                                                                        <path
                                                                            d="M35,38h-2c-0.552,0-1,0.447-1,1s0.448,1,1,1h2c0.552,0,1-0.447,1-1S35.552,38,35,38z" />
                                                                        <path
                                                                            d="M25,40h2c0.552,0,1-0.447,1-1s-0.448-1-1-1h-2c-0.552,0-1,0.447-1,1S24.448,40,25,40z" />
                                                                        <path
                                                                            d="M25,36h1c0.552,0,1-0.447,1-1s-0.448-1-1-1h-1c-0.552,0-1,0.447-1,1S24.448,36,25,36z" />
                                                                        <path
                                                                            d="M30.71,39.71C30.89,39.52,31,39.26,31,39s-0.11-0.521-0.29-0.71c-0.38-0.37-1.05-0.37-1.42,0C29.11,38.479,29,38.729,29,39
                                                                                                                                                              c0,0.27,0.11,0.52,0.29,0.71C29.48,39.89,29.74,40,30,40C30.26,40,30.52,39.89,30.71,39.71z" />
                                                                        <circle cx="30" cy="3" r="1" />
                                                                        <circle cx="27" cy="6" r="1" />
                                                                        <circle cx="27" cy="12" r="1" />
                                                                        <circle cx="30" cy="9" r="1" />
                                                                        <circle cx="33" cy="6" r="1" />
                                                                        <circle cx="33" cy="12" r="1" />
                                                                        <circle cx="30" cy="15" r="1" />
                                                                        <circle cx="30" cy="21" r="1" />
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                </svg>


                                                            </div>
                                                            <label for="ECOM" class="radio-tile-label">Colis contre
                                                                remboursement</label>
                                                        </div>
                                                    </div>
                                                    <div class="input-container">
                                                        <input class="radio-button tooltipped" type="radio" name='type'
                                                            value='ADM' data-position="bottom"
                                                            data-tooltip="COLIS DÉJÀ PAYER" id='ADM'
                                                            onchange="changetype(this)"
                                                            {{ $record->type == 'ADM' ? 'checked' : '' }}>
                                                        <div class="radio-tile">
                                                            <div class="icon walk-icon">
                                                                <svg version="1.1" id="Capa_1"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                                                                    y="0px" viewBox="0 0 511 511"
                                                                    style="enable-background:new 0 0 511 511;"
                                                                    xml:space="preserve">
                                                                    <g>
                                                                        <path
                                                                            d="M454.962,110.751c-0.018-0.185-0.05-0.365-0.081-0.545c-0.011-0.06-0.016-0.122-0.028-0.182
                                                                                                                                                              c-0.043-0.215-0.098-0.425-0.159-0.632c-0.007-0.025-0.012-0.052-0.02-0.077c-0.065-0.213-0.141-0.421-0.224-0.625
                                                                                                                                                              c-0.008-0.021-0.015-0.043-0.023-0.064c-0.081-0.195-0.173-0.384-0.269-0.57c-0.016-0.031-0.029-0.063-0.045-0.094
                                                                                                                                                              c-0.093-0.173-0.196-0.339-0.301-0.504c-0.027-0.042-0.049-0.086-0.077-0.127c-0.103-0.154-0.216-0.3-0.33-0.446
                                                                                                                                                              c-0.037-0.048-0.07-0.098-0.109-0.145c-0.142-0.173-0.294-0.338-0.451-0.498c-0.015-0.015-0.027-0.031-0.042-0.046l-104-104
                                                                                                                                                              c-0.018-0.018-0.038-0.033-0.057-0.051c-0.156-0.153-0.317-0.301-0.486-0.44c-0.055-0.045-0.113-0.083-0.169-0.126
                                                                                                                                                              c-0.138-0.107-0.275-0.214-0.42-0.311c-0.051-0.034-0.105-0.062-0.156-0.095c-0.156-0.099-0.312-0.197-0.475-0.284
                                                                                                                                                              c-0.036-0.019-0.074-0.035-0.111-0.053c-0.181-0.093-0.365-0.183-0.554-0.262c-0.024-0.01-0.049-0.017-0.074-0.027
                                                                                                                                                              c-0.202-0.081-0.406-0.157-0.616-0.221c-0.027-0.008-0.054-0.013-0.081-0.021c-0.206-0.06-0.415-0.115-0.628-0.158
                                                                                                                                                              c-0.063-0.013-0.128-0.018-0.192-0.029c-0.177-0.031-0.354-0.062-0.536-0.08C344.001,0.013,343.751,0,343.5,0h-248
                                                                                                                                                              C73.72,0,56,17.72,56,39.5v432c0,21.78,17.72,39.5,39.5,39.5h320c21.78,0,39.5-17.72,39.5-39.5v-360
                                                                                                                                                              C455,111.249,454.987,110.999,454.962,110.751z M351,25.606L429.394,104H375.5c-13.509,0-24.5-10.99-24.5-24.5V25.606z M415.5,496
                                                                                                                                                              h-320C81.991,496,71,485.01,71,471.5v-432C71,25.99,81.991,15,95.5,15H336v64.5c0,21.78,17.72,39.5,39.5,39.5H440v352.5
                                                                                                                                                              C440,485.01,429.009,496,415.5,496z" />
                                                                        <path
                                                                            d="M391.5,248h-48.002c-4.142,0-7.5,3.357-7.5,7.5s3.358,7.5,7.5,7.5H391.5c4.142,0,7.5-3.357,7.5-7.5S395.642,248,391.5,248z
                                                                                                                                                              " />
                                                                        <path
                                                                            d="M119.5,263h192.001c4.142,0,7.5-3.357,7.5-7.5s-3.358-7.5-7.5-7.5H119.5c-4.142,0-7.5,3.357-7.5,7.5S115.358,263,119.5,263
                                                                                                                                                              z" />
                                                                        <path
                                                                            d="M391.5,152h-200c-4.142,0-7.5,3.357-7.5,7.5s3.358,7.5,7.5,7.5h200c4.142,0,7.5-3.357,7.5-7.5S395.642,152,391.5,152z" />
                                                                        <path
                                                                            d="M119.5,167h40.003c4.142,0,7.5-3.357,7.5-7.5s-3.358-7.5-7.5-7.5H119.5c-4.142,0-7.5,3.357-7.5,7.5S115.358,167,119.5,167z
                                                                                                                                                              " />
                                                                        <path
                                                                            d="M391.5,344h-152c-4.142,0-7.5,3.357-7.5,7.5s3.358,7.5,7.5,7.5h152c4.142,0,7.5-3.357,7.5-7.5S395.642,344,391.5,344z" />
                                                                        <path
                                                                            d="M207.5,344h-88c-4.142,0-7.5,3.357-7.5,7.5s3.358,7.5,7.5,7.5h88c4.142,0,7.5-3.357,7.5-7.5S211.642,344,207.5,344z" />
                                                                        <path
                                                                            d="M391.5,200h-272c-4.142,0-7.5,3.357-7.5,7.5s3.358,7.5,7.5,7.5h272c4.142,0,7.5-3.357,7.5-7.5S395.642,200,391.5,200z" />
                                                                        <path
                                                                            d="M391.5,296h-272c-4.142,0-7.5,3.357-7.5,7.5s3.358,7.5,7.5,7.5h272c4.142,0,7.5-3.357,7.5-7.5S395.642,296,391.5,296z" />
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                </svg>


                                                            </div>
                                                            <label for="CDP" class="radio-tile-label">Colis déjà payer
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="input-container">
                                                        <input class="radio-button tooltipped" type="radio" name='type'
                                                            value='colis_ech' data-position="bottom"
                                                            data-tooltip="COLIS DÉJÀ PAYER" id='colis_ech'
                                                            onchange="changetype(this)"
                                                            {{ $record->type == 'COLECH' ? 'checked' : '' }}>
                                                        <div class="radio-tile">
                                                            <div class="icon walk-icon">
                                                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                                                                    width="512.000000pt" height="512.000000pt"
                                                                    viewBox="0 0 512.000000 512.000000"
                                                                    preserveAspectRatio="xMidYMid meet">

                                                                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                                                                        stroke="none">
                                                                        <path d="M2395 4993 c-285 -33 -494 -88 -735 -190 -326 -139 -653 -380 -876
                                                                                -645 -159 -190 -179 -231 -129 -264 41 -27 66 -12 141 88 82 110 277 310 387
                                                                                396 350 276 756 442 1206 493 132 15 450 7 586 -15 502 -81 947 -313 1303
                                                                                -680 746 -770 859 -1959 273 -2853 -177 -269 -383 -480 -640 -652 -390 -262
                                                                                -812 -391 -1281 -392 -375 0 -684 70 -1021 232 -136 66 -164 70 -188 28 -23
                                                                                -41 3 -71 111 -125 934 -469 2069 -286 2804 451 385 386 615 858 695 1425 16
                                                                                113 16 461 0 580 -69 514 -277 965 -617 1343 -377 419 -924 701 -1494 772
                                                                                -112 13 -437 19 -525 8z" />
                                                                        <path d="M2560 4383 c-8 -3 -348 -181 -755 -395 -556 -293 -749 -399 -776
                                                                                -427 -27 -28 -40 -52 -48 -93 -8 -39 -11 -305 -9 -924 3 -957 -1 -894 65 -956
                                                                                15 -14 367 -205 782 -424 718 -378 756 -397 807 -397 50 0 89 19 809 398 415
                                                                                219 767 410 781 424 63 63 59 -2 59 991 0 998 4 930 -62 992 -15 14 -363 203
                                                                                -773 419 -716 378 -747 393 -805 396 -33 1 -67 0 -75 -4z m254 -197 c88 -47
                                                                                170 -91 180 -99 17 -13 -21 -34 -425 -237 -244 -123 -458 -231 -476 -241 -17
                                                                                -10 -150 -77 -295 -150 l-263 -132 -183 93 c-100 51 -180 95 -178 97 2 2 322
                                                                                172 712 378 440 232 720 374 738 375 17 0 93 -34 190 -84z m421 -223 c44 -22
                                                                                118 -61 163 -86 l84 -45 -719 -362 c-395 -199 -731 -374 -747 -389 -64 -59
                                                                                -66 -68 -66 -471 l0 -365 -83 83 c-92 91 -125 107 -191 92 -84 -19 -76 -59
                                                                                -76 388 0 303 3 402 13 414 6 9 282 153 612 319 330 167 672 340 760 386 88
                                                                                45 162 82 165 80 3 -1 41 -21 85 -44z m615 -324 c127 -66 230 -122 230 -124 0
                                                                                -11 -1433 -725 -1454 -725 -24 0 -476 218 -476 230 0 7 1442 738 1458 739 7 1
                                                                                116 -53 242 -120z m-2555 -323 l180 -94 5 -428 c4 -323 8 -435 18 -451 27 -48
                                                                                109 -67 187 -44 28 8 35 4 118 -78 l87 -86 55 0 c48 0 58 4 87 33 l33 32 3
                                                                                361 c1 198 6 359 11 357 4 -2 116 -58 249 -126 l242 -123 0 -880 c0 -483 -2
                                                                                -879 -5 -879 -14 0 -1422 747 -1444 766 l-26 22 -3 856 c-2 687 0 856 10 856
                                                                                7 0 94 -43 193 -94z m2863 -760 c-3 -822 -4 -855 -22 -875 -10 -11 -82 -54
                                                                                -160 -95 -78 -40 -380 -200 -673 -354 -293 -155 -550 -290 -572 -302 l-41 -20
                                                                                0 882 0 883 671 339 c369 186 694 351 722 367 29 15 58 28 65 28 10 1 12 -169
                                                                                10 -853z" />
                                                                        <path d="M455 4227 c-14 -21 -12 -39 14 -200 32 -191 45 -227 101 -264 42 -29
                                                                                90 -29 258 -3 170 27 180 29 197 47 23 23 19 70 -8 87 -20 14 -38 13 -172 -9
                                                                                -182 -30 -211 -31 -223 -7 -6 9 -19 78 -31 152 -30 186 -31 188 -48 205 -23
                                                                                23 -70 19 -88 -8z" />
                                                                        <path
                                                                            d="M244 2520 c-44 -17 -47 -31 -35 -157 13 -129 24 -153 72 -153 19 0
                                                                                34 8 44 24 14 21 14 38 4 137 -6 63 -15 121 -20 130 -10 20 -38 28 -65 19z" />
                                                                        <path
                                                                            d="M271 2103 c-12 -14 -19 -33 -17 -42 3 -9 14 -54 26 -101 30 -119 39
                                                                                -134 78 -138 69 -7 75 25 36 175 -34 131 -76 167 -123 106z" />
                                                                        <path
                                                                            d="M390 1730 c-11 -11 -20 -27 -20 -35 0 -24 90 -219 108 -232 34 -27
                                                                                80 -14 95 28 7 20 -86 229 -109 246 -25 19 -51 16 -74 -7z" />
                                                                        <path d="M566 1368 c-9 -12 -16 -28 -16 -34 0 -14 109 -181 134 -206 20 -21
                                                                                69 -24 84 -5 28 35 23 53 -45 153 -36 54 -71 102 -77 106 -23 16 -65 8 -80
                                                                                -14z" />
                                                                        <path d="M804 1045 c-23 -36 -11 -60 79 -152 86 -89 117 -102 150 -65 33 36
                                                                                21 62 -70 154 -97 97 -128 110 -159 63z" />
                                                                        <path
                                                                            d="M1104 777 c-44 -38 -29 -65 80 -147 98 -74 130 -84 159 -52 29 32 21
                                                                                68 -20 98 -21 15 -63 46 -93 69 -74 57 -92 61 -126 32z" />
                                                                    </g>
                                                                </svg>


                                                            </div>
                                                            <label for="colis_ech" class="radio-tile-label">Colis en échange
                                                            </label>
                                                        </div>
                                                    </div>




                                                </div>
                                            </div>


                                            {{-- <div class="col s12 input-field"> --}}
                                            {{-- <p><label><h6>Type de livraison :</h6></label></p><p> --}}
                                            {{-- <label> --}}
                                            {{-- <input class='with-gap' name='type' value='ECOM' id='ECOM' --}}
                                            {{-- type='radio' onchange="changetype(this)" --}}
                                            {{-- checked><span> --}}
                                            {{-- Livraison colis e commerce</span></label> --}}
                                            {{-- <label><input class='with-gap' name='type' value='ADM' id='ADM' --}}
                                            {{-- type='radio' onchange="changetype(this)"><span>Livraison docs administratifs</span></label> --}}
                                            {{-- </p> --}}
                                            {{-- @error('type') --}}
                                            {{-- <span class="helper-text materialize-red-text">{{ $message }}</span> --}}
                                            {{-- @enderror --}}
                                            {{-- </div> --}}
                                        </div>
                                        <div class="row">
                                            <div class="col col m12 s12 px-5 input-field" id='Expeditions'>
                                                <select name="expedition_ech" id='mes_expedition'
                                                    class="select2 browser-default">
                                                    <option value=''></option>
                                                    @foreach ($mes_expedition as $row)
                                                        <option class='option'
                                                            {{ $row->id == old('mes_expeditions') ? 'selected' : '' }}
                                                            value='{{ $row->id }}'> {{ $row->num_expedition }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="text" name="agence"
                                                    value="{{ auth()->user()->ClientDetail->agence }}" hidden>
                                                <label for="mes_expedition"> Mes Expéditions</label>
                                                @error('mes_expedition')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <div class="col col m6 s12 px-5 input-field">
                                                <select name='agence_des' id='agence_des' class="select2 browser-default">
                                                    <option value=''></option>
                                                    @foreach ($agenceRecords as $row)
                                                        <option class='option'
                                                            {{ $row->id == old('agence', $record->agence) ? 'selected' : '' }}
                                                            value='{{ $row->id }}'> {{ $row->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="text" name="agence"
                                                    value="{{ auth()->user()->ClientDetail->agence }}" hidden>
                                                <label for="agence_des"> Destination</label>
                                                @error('agence_des')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col col m6 s12 input-field">
                                                <input id="destinataire" name="destinataire"
                                                    value="{{ old('destinataire', $record->adresse_destinataire) }}"
                                                    autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                                    type="text">
                                                <label for="destinataire"> Destinataire </label>
                                                @error('destinataire')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col m6 s12 input-field">
                                                <input id="adresse_destinataire" name="adresse_destinataire"
                                                    value="{{ old('adresse_destinataire', $record->adresse_destinataire) }}"
                                                    autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                                    type="text">
                                                <label for="adresse_destinataire"> Adresse </label>
                                                @error('adresse_destinataire')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col m6 s12 input-field">
                                                <input id="telephone" name="telephone"
                                                    value="{{ old('telephone', $record->telephone) }}" autocomplete="off"
                                                    readonly onfocus="this.removeAttribute('readonly');" type="text">
                                                <label for="telephone"> Téléphone </label>
                                                @error('telephone')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="row">
                                            <div class="col col m6 s12 input-field">
                                                <input id="colis" name="colis" value="{{ old('colis', $record->colis) }}"
                                                    autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                                    type="text">
                                                <label for="colis"> Nb. Colis </label>
                                                @error('colis')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div id="montant" class="col m6 s12 input-field">
                                                <input id="fond" name="fond" value="{{ old('fond', $record->fond) }}"
                                                    autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                                    type="text">
                                                <label for="fond">Montant</label>
                                                @error('fond')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div id="document" class="col m6 s12 input-field">
                                                <input id="bl" name="bl" value="{{ old('fond', $record->bl) }}"
                                                    autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                                    type="text">
                                                <label for="bl">N° BL</label>
                                                @error('fond')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div id="Indication" class="col m6 s12 input-field">
                                                <input id="Indication" name="Indication" value="{{ old('Indication', $record->Indication) }}"
                                                    autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                                                    type="text">
                                                <label for="Indication">Indication</label>
                                                @error('Indication')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row">

                                            <div class="col col m6 s12 input-field">
                                                <input id="vDeclaree" name="vDeclaree"
                                                    value="{{ old('vDeclaree', $record->vDeclaree) }}" autocomplete="off"
                                                    readonly onfocus="this.removeAttribute('readonly');" type="text">
                                                <label for="vDeclaree"> Valeur Déclarée</label>
                                                <p>(1% de frais supplémentaires pour un remboursement à 100%)</p>
                                                @error('vDeclaree')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col col m6 s12">
                                                <label>
                                                    <input type="checkbox" class="filled-in" id='paiementCheque'
                                                        name="paiementCheque"
                                                        {{ $record->paiementCheque == 'Oui' ? 'checked' : '' }} />
                                                    <span>Paiement Cheque</span>
                                                </label>
                                                <br>
                                                <label>
                                                    <input type="checkbox" class="filled-in" name='ouvertureColis'
                                                        id='ouvertureColis'
                                                        {{ $record->ouvertureColis == 'Oui' ? 'checked' : '' }} />
                                                    <span>Ouverture Colis</span>
                                                </label>
                                                <input type="hidden" name="etape" value="1">
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12 m12">
                                        <div class="col s12 display-flex justify-content-end mt-3">
                                            <a href="{{ route('expedition_list') }}">
                                                <button type="button" class="btn btn-light">Retour
                                                </button>
                                            </a>
                                            <button type="submit" class="btn"
                                                style="margin-left: 1rem; background-color: #1991ce">
                                                Enregistrer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="/assets/js/scripts/data-tables.js"></script>
    <script>

document.getElementById("expeditions").classList.add("activate");

        $(document).ready(function() {

            $('#mes_expedition').change(function() {
                let cid = $(this).val();
                if (cid != '') {
                    $.ajax({
                        url: '/expedition/data',
                        type: 'get',
                        data: 'cid=' + cid + '&_token={{ csrf_token() }}',
                        success: function(result) {
                            $('#destinataire').val(result['destinataire']).next().addClass(
                                "active");
                            $('#adresse_destinataire').val(result['adresse_destinataire'])
                            .next().addClass("active");
                            $('#telephone').val(result['telephone']).next().addClass("active");
                            $('#colis').val(result['colis']).next().addClass("active");
                        }
                    });
                }

            });
        });



        document.getElementById('montant').style.display = 'block';
        document.getElementById('document').style.display = 'none';
        document.getElementById('Expeditions').style.display = 'none';

        function changetype(element) {
            if (element.id == 'ECOM' && element.checked) {
                document.getElementById('montant').style.display = 'block';
                document.getElementById('document').style.display = 'none';
                document.getElementById('Expeditions').style.display = 'none';

            } else if (element.id == 'ADM' && element.checked) {
                document.getElementById('document').style.display = 'block';
                document.getElementById('montant').style.display = 'none';
                document.getElementById('Expeditions').style.display = 'none';
            } else if (element.id == 'colis_ech' && element.checked) {
                document.getElementById('document').style.display = 'block';
                document.getElementById('montant').style.display = 'none';
                document.getElementById('Expeditions').style.display = 'block';
            }
        }
    </script>

@stop
