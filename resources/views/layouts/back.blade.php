<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta name="description"
        content="Materialize is a Material Design Admin Template,It's modern, responsive and based on Material Design by Google." />
    <meta name="keywords"
        content="materialize, admin template, dashboard template, flat admin template, responsive admin template, eCommerce dashboard, analytic dashboard" />
    <meta name="author" content="ThemeSelect" />
    <title>
        GOTAWSIL
    </title>
    <link rel="apple-touch-icon" href="/assets/images/favicon/apple-touch-icon-152x152.png" />
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/favicon/favicon-32x32.png" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <!-- BEGIN: VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="/assets/vendors/vendors.min.css" />
    <!-- END: VENDOR CSS-->
    <!-- BEGIN: Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="/assets/css/themes/vertical-dark-menu-template/materialize.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/themes/vertical-dark-menu-template/style.css" />

    <!-- END: Page Level CSS-->
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="/assets/css/custom/custom.css" />

    <link rel="stylesheet" type="text/css" href="/assets/vendors/hover-effects/media-hover-effects.css">

    <link rel="stylesheet" type="text/css" href="/assets/vendors/data-tables/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="/assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendors/data-tables/css/dataTables.checkboxes.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendors/fullcalendar/css/fullcalendar.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendors/fullcalendar/daygrid/daygrid.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendors/fullcalendar/timegrid/timegrid.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/pages/app-calendar.min.css">


    <link rel="stylesheet" href="/assets/vendors/select2/select2.min.css" type="text/css">
    <link rel="stylesheet" href="/assets/vendors/select2/select2-materialize.css" type="text/css">

    <!-- END: Custom CSS-->

    @yield('css')
</head>
<!-- END: Head-->

<style>
    .sidenav li>a>i.material-icons,
    .sidenav li a.collapsible-header>i.material-icons {
        font-size: 24px;
    }

    input[type=number] {
        height: 30px;
        line-height: 30px;
        font-size: 16px;
        padding: 0 8px;
    }

    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        cursor: pointer;
        display: block;
        width: 8px;
        color: #333;
        text-align: center;
        position: relative;

    }

    input[type=number]::-webkit-inner-spin-button {
        opacity: 1;
        background: #eee url('/assets/images/icon/YYySO.png') no-repeat 50% 50%;
        width: 14px;
        height: 14px;
        padding: 4px;
        position: relative;
        right: 4px;
        border-radius: 28px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: rgb(25 145 145) transparent transparent;
    }
</style>

<body
    class="
            vertical-layout
            page-header-light
            vertical-menu-collapsible vertical-dark-menu
            preload-transitions
            2-columns
        "
    data-open="click" data-menu="vertical-dark-menu" data-col="2-columns">
    <!-- BEGIN: Header-->
    <header class="page-topbar" id="header">
        <div class="navbar navbar-fixed">
            <nav
                class="
                    navbar-main navbar-color nav-collapsible navbar-light nav-expanded sideNav-lock
                    ">
                <div class="nav-wrapper">
                    <div class="header-search-wrapper hide-on-med-and-down">
                        <i class="material-icons">search</i>
                        <form action="{{ route('expedition_list') }}">
                            <input class="header-search-input z-depth-2" type="text" name="exp"
                                placeholder="Recherche" data-search="template-list" />
                        </form>
                        <ul class="search-list collection display-none"></ul>
                    </div>
                    <ul class="navbar-list right valign-wrapper">

                        <li class="hide-on-med-and-down">
                            <a class="
                                        waves-effect waves-block waves-light
                                        toggle-fullscreen
                                    "
                                href="javascript:void(0);"><i class="material-icons">settings_overscan</i></a>
                        </li>
                        <li class="hide-on-large-only search-input-wrapper">
                            <a class="
                                        waves-effect waves-block waves-light
                                        search-button
                                    "
                                href="javascript:void(0);"><i class="material-icons">search</i></a>
                        </li>
                        <li>
                            <a class="" href="{{ route('client_new') }}"><i class="material-icons tooltipped"
                                    data-position="bottom" data-tooltip="Nouveaux inscris">highlight<small
                                        class="notification-badge"><span id="new_subscribers">0</small></i></a>
                        </li>
                        @if (auth()->user()->role == '1' || auth()->user()->role == '8')
                            <li>
                                <a class="" href="{{ route('reclamation_list') }}"><i
                                        class="material-icons tooltipped" data-position="bottom"
                                        data-tooltip="Reclamations">announcement<small
                                            class="notification-badge"><span id="new_reclamation">0</small></i></a>
                            </li>
                        @endif
                        @if (auth()->user()->role == '1' || auth()->user()->role == '7')
                            <li>
                                <a class="" href="{{ route('bon_list') }}"><i class="material-icons tooltipped"
                                        data-position="bottom" data-tooltip="Ramassages">notifications<small
                                            class="notification-badge"><span id="new_ramassage">0</small></i></a>
                            </li>
                        @endif
                        <li>

                            <a class="
                                        waves-effect waves-block waves-light
                                        profile-button
                                    "
                                href="javascript:void(0);" data-target="profile-dropdown"
                                style="margin-bottom: 10px;"><span class="avatar-status avatar-online"><img
                                        src="{{ Auth::user()->photo ? '/uploads/photos/' . Auth::user()->photo : '/uploads/photos/default.png' }}"
                                        alt="avatar" /><i></i></span></a>

                        </li>

                    </ul>
                    <!-- translation-button-->
                    <ul class="dropdown-content" id="translation-dropdown">

                        <li class="dropdown-item">
                            <a class="grey-text text-darken-1" href="#!" data-language="fr"><i
                                    class="flag-icon flag-icon-fr"></i>
                                Français</a>
                        </li>

                    </ul>
                    <!-- notifications-dropdown-->
                    <ul class="dropdown-content" id="notifications-dropdown">
                        <li>
                            <h6>
                                NOTIFICATIONS<span class="new badge">5</span>
                            </h6>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="black-text" href="#!"><span
                                    class="
                                            material-icons
                                            icon-bg-circle
                                            cyan
                                            small
                                        ">add_shopping_cart</span>
                                A new order has been placed!</a>
                            <time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">2 hours
                                ago
                            </time>
                        </li>
                        <li>
                            <a class="black-text" href="#!"><span
                                    class="
                                            material-icons
                                            icon-bg-circle
                                            red
                                            small
                                        ">stars</span>
                                Completed the task</a>
                            <time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">3 days
                                ago
                            </time>
                        </li>
                        <li>
                            <a class="black-text" href="#!"><span
                                    class="
                                            material-icons
                                            icon-bg-circle
                                            teal
                                            small
                                        ">settings</span>
                                Settings updated</a>
                            <time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">4 days
                                ago
                            </time>
                        </li>
                        <li>
                            <a class="black-text" href="#!"><span
                                    class="
                                            material-icons
                                            icon-bg-circle
                                            deep-orange
                                            small
                                        ">today</span>
                                Director meeting started</a>
                            <time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">6 days
                                ago
                            </time>
                        </li>
                        <li>
                            <a class="black-text" href="#!"><span
                                    class="
                                            material-icons
                                            icon-bg-circle
                                            amber
                                            small
                                        ">trending_up</span>
                                Generate monthly report</a>
                            <time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">1 week
                                ago
                            </time>
                        </li>
                    </ul>
                    <!-- profile-dropdown   -->
                    <ul class="dropdown-content" id="profile-dropdown" style="width: 260px !important;">

                        <li><a class="grey-text text-darken-1" href="{{ route('user_profil') }}"><i
                            class="material-icons"></i>

                        <i class="material-icons">person_outline</i> Mon profil</a></li>

                        <li><a class="grey-text text-darken-1" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="material-icons">keyboard_tab</i>
                                Se déconnecter</a></li>




                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>


                    </ul>


                </div>
                <nav class="display-none search-sm">
                    <div class="nav-wrapper">
                        <form id="navbarForm">
                            <div class="input-field search-input-sm">
                                <input class="search-box-sm mb-0" type="search" required="" id="search"
                                    placeholder="Recherche" data-search="template-list" />
                                <label class="label-icon" for="search"><i
                                        class="
                                                material-icons
                                                search-sm-icon
                                            ">search</i></label><i
                                    class="material-icons search-sm-close">close</i>
                                <ul
                                    class="
                                            search-list
                                            collection
                                            search-list-sm
                                            display-none
                                        ">
                                </ul>
                            </div>
                        </form>
                    </div>
                </nav>
            </nav>
        </div>
    </header>
    <!-- END: Header-->
    <ul class="display-none" id="default-search-main">
        <li class="auto-suggestion-title">
            <a class="collection-item" href="#">
                <h6 class="search-title">FILES</h6>
            </a>
        </li>
        <li class="auto-suggestion">
            <a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar">
                            <img src="/assets/images/icon/pdf-image.png" width="24" height="30"
                                alt="sample image" />
                        </div>
                        <div class="member-info display-flex flex-column">
                            <span class="black-text">Two new item submitted</span><small class="grey-text">Marketing
                                Manager</small>
                        </div>
                    </div>
                    <div class="status">
                        <small class="grey-text">17kb</small>
                    </div>
                </div>
            </a>
        </li>
        <li class="auto-suggestion">
            <a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar">
                            <img src="/assets/images/icon/doc-image.png" width="24" height="30"
                                alt="sample image" />
                        </div>
                        <div class="member-info display-flex flex-column">
                            <span class="black-text">52 Doc file Generator</span><small class="grey-text">FontEnd
                                Developer</small>
                        </div>
                    </div>
                    <div class="status">
                        <small class="grey-text">550kb</small>
                    </div>
                </div>
            </a>
        </li>
        <li class="auto-suggestion">
            <a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar">
                            <img src="/assets/images/icon/xls-image.png" width="24" height="30"
                                alt="sample image" />
                        </div>
                        <div class="member-info display-flex flex-column">
                            <span class="black-text">25 Xls File Uploaded</span><small class="grey-text">Digital
                                Marketing Manager</small>
                        </div>
                    </div>
                    <div class="status">
                        <small class="grey-text">20kb</small>
                    </div>
                </div>
            </a>
        </li>
        <li class="auto-suggestion">
            <a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar">
                            <img src="/assets/images/icon/jpg-image.png" width="24" height="30"
                                alt="sample image" />
                        </div>
                        <div class="member-info display-flex flex-column">
                            <span class="black-text">Anna Strong</span><small class="grey-text">Web
                                Designer</small>
                        </div>
                    </div>
                    <div class="status">
                        <small class="grey-text">37kb</small>
                    </div>
                </div>
            </a>
        </li>
        <li class="auto-suggestion-title">
            <a class="collection-item" href="#">
                <h6 class="search-title">MEMBERS</h6>
            </a>
        </li>
        <li class="auto-suggestion">
            <a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar">
                            <img class="circle" src="/assets/images/avatar/avatar-7.png" width="30"
                                alt="sample image" />
                        </div>
                        <div class="member-info display-flex flex-column">
                            <span class="black-text">John Doe</span><small class="grey-text">UI
                                designer</small>
                        </div>
                    </div>
                </div>
            </a>
        </li>
        <li class="auto-suggestion">
            <a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar">
                            <img class="circle" src="/assets/images/avatar/avatar-8.png" width="30"
                                alt="sample image" />
                        </div>
                        <div class="member-info display-flex flex-column">
                            <span class="black-text">Michal Clark</span><small class="grey-text">FontEnd
                                Developer</small>
                        </div>
                    </div>
                </div>
            </a>
        </li>
        <li class="auto-suggestion">
            <a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar">
                            <img class="circle" src="/assets/images/avatar/avatar-10.png" width="30"
                                alt="sample image" />
                        </div>
                        <div class="member-info display-flex flex-column">
                            <span class="black-text">Milena Gibson</span><small class="grey-text">Digital
                                Marketing</small>
                        </div>
                    </div>
                </div>
            </a>
        </li>
        <li class="auto-suggestion">
            <a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar">
                            <img class="circle" src="/assets/images/avatar/avatar-12.png" width="30"
                                alt="sample image" />
                        </div>
                        <div class="member-info display-flex flex-column">
                            <span class="black-text">Anna Strong</span><small class="grey-text">Web
                                Designer</small>
                        </div>
                    </div>
                </div>
            </a>
        </li>
    </ul>
    <ul class="display-none" id="page-search-title">
        <li class="auto-suggestion-title">
            <a class="collection-item" href="#">
                <h6 class="search-title">PAGES</h6>
            </a>
        </li>
    </ul>
    <ul class="display-none" id="search-not-found">
        <li class="auto-suggestion">
            <a class="collection-item display-flex align-items-center" href="#"><span
                    class="material-icons">error_outline</span><span class="member-info">No results
                    found.</span></a>
        </li>
    </ul>

    <!-- BEGIN: SideNav-->
    <aside class="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-dark sidenav-active-rounded">
        <div class="brand-sidebar">
            <h1 class="logo-wrapper">
                @if (auth()->user()->role == '1')
                    <a class="brand-logo darken-1" href="{{ route('admin') }}"><img class="hide-on-med-and-down"
                            src="/assets/images/logo/materialize-logo2.png" alt="materialize logo" />
                    @elseif ( auth()->user()->role == '7' || auth()->user()->role == '8')
                        <a class="brand-logo darken-1" href="{{ route('Dashboard_Pilotage') }}"><img
                                class="hide-on-med-and-down" src="/assets/images/logo/materialize-logo2.png"
                                alt="materialize logo" />
                        @elseif (auth()->user()->role == '5' || auth()->user()->role == '2')
                            <a class="brand-logo darken-1" href="{{ route('Dashboard_Pilotage_Livreur') }}"><img
                                    class="hide-on-med-and-down" src="/assets/images/logo/materialize-logo2.png"
                                    alt="materialize logo" />
                            @elseif (auth()->user()->role == '6')
                                <a class="brand-logo darken-1" href="{{ route('Dashboard_Commercial') }}"><img
                                        class="hide-on-med-and-down" src="/assets/images/logo/materialize-logo2.png"
                                        alt="materialize logo" />
                                        @else
                                        <a class="brand-logo darken-1" href="{{ route('expedition_list') }}"><img
                                            class="hide-on-med-and-down" src="/assets/images/logo/materialize-logo2.png"
                                            alt="materialize logo" />
                @endif


                <img class="show-on-medium-and-down hide-on-med-and-up"
                    src="/assets/images/logo/materialize-logo-color.png" alt="materialize logo" /><span
                    class="logo-text hide-on-med-and-down">TAWSIL</span></a><a class="navbar-toggler"
                    href="#"><i class="material-icons">radio_button_checked</i></a>


            </h1>
        </div>
        <ul class="
                    sidenav sidenav-collapsible
                    leftside-navigation
                    collapsible
                    sidenav-fixed
                    menu-shadow ps ps--active-y
                "
            id="slide-out" data-menu="menu-navigation" data-collapsible="accordion">


            @if (\Auth::user()::hasRessource('Menu Tableaux de bord'))
                <li
                    class=" {{ in_array(Route::currentRouteName(), ['admin', 'Dashboard_Pilotage', 'Dashboard_Commercial', 'Dashboard_Pilotage_Livreur']) ? 'active bold open ' : '' }}">
                    <a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i
                            class="material-icons">settings_input_svideo</i><span class="menu-title"
                            data-i18n="eCommerce">Tableaux de bord</span></a>
                    <div class="collapsible-body"
                        style=" {{ in_array(Route::currentRouteName(), ['admin', 'Dashboard_Pilotage', 'Dashboard_Commercial', 'Dashboard_Pilotage_Livreur']) ? 'display: block;' : '' }}">
                        <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                            @if (\Auth::user()::hasRessource('SMenu Dashboard 360'))
                                <li>
                                    <a href="{{ route('admin') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['admin']) ? 'active' : '' }}"><i
                                            class="material-icons ">pie_chart</i><span data-i18n="360">Dashboard
                                            360</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Dashboard Pilotage'))
                                <li>
                                    <a href="{{ route('Dashboard_Pilotage') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['Dashboard_Pilotage']) ? 'active' : '' }}"><i
                                            class="material-icons">show_chart</i><span data-i18n="Pilotage">Dashboard
                                            Pilotage</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Dashboard Pilotage Livreur'))
                                <li>
                                    <a href="{{ route('Dashboard_Pilotage_Livreur') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['Dashboard_Pilotage_Livreur']) ? 'active' : '' }}"><i
                                            class="material-icons">show_chart</i><span
                                            data-i18n="Pilotage_livreur">Dashboard
                                            Pilotage</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Dashboard Commercial'))
                                <li>
                                    <a href="{{ route('Dashboard_Commercial') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['Dashboard_Commercial']) ? 'active' : '' }}"><i
                                            class="material-icons">insert_chart</i><span
                                            data-i18n="Commercial">Dashboard
                                            Commercial</span></a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </li>
            @endif
            <!--<li class="navigation-header" style="color: #1991ce;">
                <a class="navigation-header-text">Applications</a><i
                    class="navigation-header-icon material-icons">more_horiz</i>
            </li>-->

            @if (\Auth::user()::hasRessource('Menu Expéditions'))
                <li
                    class=" {{ in_array(Route::currentRouteName(), ['expedition_create', 'expedition_list', 'suivi_commerce', 'bon_list', 'forcer_list', 'chargement_list', 'chargement_feuille']) ? 'active bold open ' : '' }}">
                    <a class="collapsible-header waves-effect waves-cyan" href="JavaScript:void(0)"><i
                            class="material-icons">folder</i><span class="menu-title"
                            data-i18n="Invoice">Expéditions</span></a>
                    <div class="collapsible-body"
                        style=" {{ in_array(Route::currentRouteName(), ['expedition_create', 'expedition_list', 'suivi_commerce', 'bon_list', 'forcer_list', 'chargement_list', 'chargement_feuille']) ? 'display: block;' : '' }}">
                        <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                            @if (\Auth::user()::hasRessource('SMenu Saisies des colis'))
                                <li>
                                    <a href="{{ route('expedition_create') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['expedition_create']) ? 'active' : '' }}"><i
                                            class="material-icons">control_point</i><span
                                            data-i18n="Invoice List">Saisies des
                                            colis</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Consultations'))
                                <li>
                                    <a href="{{ route('expedition_list') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['expedition_list']) ? 'active' : '' }}"><i
                                            class="material-icons">description</i><span
                                            data-i18n="Invoice View">Consultations</span></a>
                                </li>
                            @endif
                            {{-- <li>
                            <a href="{{route('suivi_commerce')}}"
                        class="{{ in_array(Route::currentRouteName(), ['suivi_commerce'])  ? 'active' : '' }}"><i
                            class="material-icons">remove_red_eye</i><span data-i18n="Invoice Edit">Suivi
                            Commercial</span></a>
            </li> --}}

                            @if (\Auth::user()::hasRessource('SMenu Demandes Ramassage'))
                                <li>
                                    <a href="{{ route('bon_list') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['bon_list']) ? 'active' : '' }}"><i
                                            class="material-icons">phonelink_ring</i><span
                                            data-i18n="Invoice Add">Demandes
                                            Ram</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Forcer Ramassage'))
                                <li>
                                    <a href="{{ route('forcer_list') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['forcer_list']) ? 'active' : '' }}"><i
                                            class="material-icons">fingerprint</i><span data-i18n="Invoice Add">Forcer
                                            Ramassage</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Chargement Colis'))
                                <li>
                                    <a href="{{ route('chargement_list') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['chargement_list']) ? 'active' : '' }}"><i
                                            class="material-icons">local_shipping</i><span
                                            data-i18n="Invoice Add">Chargement
                                            Colis</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Feuille Chargement'))
                                <li>
                                    <a href="{{ route('chargement_feuille') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['chargement_feuille']) ? 'active' : '' }}"><i
                                            class="material-icons">library_books</i><span
                                            data-i18n="Invoice Add">Feuille
                                            Chargement</span></a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            @if (\Auth::user()::hasRessource('Menu Arrivage'))
                <li
                    class=" {{ in_array(Route::currentRouteName(), ['expedition_livraison', 'bonliv_list', 'expedition_affec_liv', 'expedition_affec_retour', 'arrivage_list', 'stock_list', 'stock_perdu_list']) ? 'active bold open ' : '' }}">
                    <a class="collapsible-header waves-effect waves-cyan" href="JavaScript:void(0)"><i
                            class="material-icons">account_balance</i><span class="menu-title"
                            data-i18n="eCommerce">Arrivage</span></a>
                    <div class="collapsible-body"
                        style=" {{ in_array(Route::currentRouteName(), ['expedition_livraison', 'bonliv_list', 'expedition_affec_liv', 'expedition_affec_retour', 'arrivage_list', 'stock_list']) ? 'display: block;' : '' }}">
                        <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                            @if (\Auth::user()::hasRessource('SMenu Arrivage'))
                                <li>
                                    <a href="{{ route('arrivage_list') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['arrivage_list']) ? 'active' : '' }}"><i
                                            class="material-icons">local_shipping</i><span
                                            data-i18n="Products Page">Arrivage</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Stock'))
                                <li>
                                    <a href="{{ route('stock_list') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['stock_list']) ? 'active' : '' }}"><i
                                            class="material-icons">store</i><span data-i18n="Pricing">Stock</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Stock perdu'))
                                <li>
                                    <a href="{{ route('stock_perdu_list') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['stock_perdu_list']) ? 'active' : '' }}"><i
                                            class="material-icons">report_problem</i><span data-i18n="Pricing">Stock
                                            perdu</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Affectation Livr'))
                                <li>
                                    <a href="{{ route('expedition_affec_liv', ['type' => 1]) }}"
                                        class="{{ in_array(Route::currentRouteName(), ['expedition_affec_liv']) ? 'active' : '' }}"><i
                                            class="material-icons">backup</i><span data-i18n="Pricing">Affectations
                                            Livr</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Affectation Retour'))
                                <li>
                                    <a href="{{ route('expedition_affec_retour', ['type' => 2]) }}"
                                        class="{{ in_array(Route::currentRouteName(), ['expedition_affec_retour']) ? 'active' : '' }}"><i
                                            class="material-icons">settings_backup_restore</i><span
                                            data-i18n="Pricing">Affectations
                                            Retour</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Bons de livraison'))
                                <li>
                                    <a href="{{ route('bonliv_list') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['bonliv_list']) ? 'active' : '' }}"><i
                                            class="material-icons">format_list_bulleted</i><span
                                            data-i18n="Pricing">Bons
                                            de
                                            livr</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Encaissements'))
                                <li>
                                    <a href="{{ route('expedition_livraison') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['expedition_livraison']) ? 'active' : '' }}"><i
                                            class="material-icons">attach_money</i><span
                                            data-i18n="Pricing">Encaissements</span></a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            @if (\Auth::user()::hasRessource('Menu Caisse'))
                <!--<li>
            <a href="{{ route('caisse_list') }}"
                class="{{ in_array(Route::currentRouteName(), ['caisse_list']) ? 'active' : '' }}"><i
                    class="material-icons ">monetization_on</i><span class="menu-title">Caisse</span></a>

        </li>-->
            @endif
            @if (\Auth::user()::hasRessource('Menu Caisse'))
                <li
                    class=" {{ in_array(Route::currentRouteName(), ['caisse_globals', 'caisse_list']) ? 'active bold open ' : '' }}">
                    <a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i
                            class="material-icons">monetization_on</i><span class="menu-title"
                            data-i18n="eCommerce">Caisses</span></a>
                    <div class="collapsible-body"
                        style=" {{ in_array(Route::currentRouteName(), ['caissepp_list', 'caisse_list']) ? 'display: block;' : '' }}">
                        <ul class="collapsible collapsible-sub" data-collapsible="accordion">

                            <li>
                                <a href="{{ route('caisse_list') }}"
                                    class="{{ in_array(Route::currentRouteName(), ['caisse_list']) ? 'active' : '' }}"><i
                                        class="material-icons ">location_city</i><span
                                        class="menu-title">Caisse</span></a>

                            </li>
                            @if (\Auth::user()::hasRessource('SMenu Caisse PP'))
                                <li>
                                    <a href="{{ route('caissepp_list') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['caissepp_list']) ? 'active' : '' }}"><i
                                            class="material-icons">public</i><span data-i18n="Pricing">Caisse PP
                                        </span></a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if (\Auth::user()::hasRessource('Menu Retours de fonds'))
                <li
                    class=" {{ in_array(Route::currentRouteName(), ['bordereau_create', 'bordereau_list', 'remboursement_create', 'remboursement_list']) ? 'active bold open ' : '' }}">
                    <a class="collapsible-header waves-effect waves-cyan" href="JavaScript:void(0)"><i
                            class="material-icons">restore</i><span class="menu-title" data-i18n="eCommerce">Retours
                            de
                            fonds</span></a>
                    <div class="collapsible-body"
                        style=" {{ in_array(Route::currentRouteName(), ['bordereau_create', 'bordereau_list', 'remboursement_create', 'remboursement_list']) ? 'display: block;' : '' }}">
                        <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                            <!--<li>
                        <a href="{{ route('bordereau_create') }}"
                            class="{{ in_array(Route::currentRouteName(), ['bordereau_create']) ? 'active' : '' }}"><i
                                class="material-icons">playlist_add</i><span data-i18n="Products Page">Gén.
                                Bordereaux BLs</span></a>
                    </li>
                    <li>
                        <a href="{{ route('bordereau_list') }}"
                            class="{{ in_array(Route::currentRouteName(), ['bordereau_list']) ? 'active' : '' }}"><i
                                class="material-icons">view_list</i><span data-i18n="Pricing">Bordereux
                                BLs</span></a>
                    </li>-->
                            @if (\Auth::user()::hasRessource('SMenu Gen. Remboursement'))
                                <li>
                                    <a href="{{ route('remboursement_create') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['remboursement_create']) ? 'active' : '' }}"><i
                                            class="material-icons">playlist_add</i><span data-i18n="Pricing">Gén.
                                            Remboursement</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Remboursement'))
                                <li>
                                    <a href="{{ route('remboursement_list') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['remboursement_list']) ? 'active' : '' }}"><i
                                            class="material-icons">payment</i><span
                                            data-i18n="Pricing">Remboursement</span></a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            @if (\Auth::user()::hasRessource('Menu Edition & Stat'))
                <li
                    class=" {{ in_array(Route::currentRouteName(), ['user_calendar', 'etat_list']) ? 'active bold open ' : '' }}">
                    <a class="collapsible-header waves-effect waves-cyan" href="JavaScript:void(0)"><i
                            class="material-icons">date_range</i><span class="menu-title"
                            data-i18n="eCommerce">Edition &
                            stat</span></a>
                    <div class="collapsible-body"
                        style=" {{ in_array(Route::currentRouteName(), ['user_calendar', 'etat_list']) ? 'display: block;' : '' }}">
                        <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                            @if (\Auth::user()::hasRessource('SMenu Calendrier'))
                                <li>
                                    <a href="{{ route('user_calendar') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['user_calendar']) ? 'active' : '' }}"><i
                                            class="material-icons">date_range</i><span
                                            data-i18n="Products Page">Calendrier</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Editions'))
                                <li>
                                    <a href="{{ route('etat_list') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['etat_list']) ? 'active' : '' }}"><i
                                            class="material-icons">picture_as_pdf</i><span
                                            data-i18n="Pricing">Editions</span></a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            @if (\Auth::user()::hasRessource('Menu Facturations'))
                <li
                    class=" {{ in_array(Route::currentRouteName(), ['facture_gen', 'facture_rem_gen', 'facture_list']) ? 'active bold open ' : '' }}">
                    <a class="collapsible-header waves-effect waves-cyan" href="JavaScript:void(0)"><i
                            class="material-icons">receipt_long</i><span class="menu-title"
                            data-i18n="eCommerce">Facturations</span></a>
                    <div class="collapsible-body"
                        style=" {{ in_array(Route::currentRouteName(), ['facture_remboursement', 'facture_encompte', 'facture_list']) ? 'display: block;' : '' }}">
                        <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                            @if (\Auth::user()::hasRessource('SMenu Facturation encompte'))
                                <li>
                                    <a href="{{ route('facture_encompte', [1]) }}"
                                        class="{{ in_array(Route::currentRouteName(), ['facture_encompte']) ? 'active' : '' }}"><i
                                            class="material-icons">picture_as_pdf</i><span
                                            data-i18n="Products Page">Factures
                                            encompte</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Facturation Remboursement'))
                                <li>
                                    <a href="{{ route('facture_remboursement', [2]) }}"
                                        class="{{ in_array(Route::currentRouteName(), ['facture_remboursement']) ? 'active' : '' }}"><i
                                            class="material-icons">picture_as_pdf</i><span
                                            data-i18n="Pricing">Factures
                                            remboursement</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Factures'))
                                <!--<li>
                        <a href="{{ route('facture_list') }}"
                            class="{{ in_array(Route::currentRouteName(), ['facture_list']) ? 'active' : '' }}"><i
                                class="material-icons">picture_as_pdf</i><span data-i18n="Pricing">Factures
                                simples</span></a>
                    </li>-->
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            @if (\Auth::user()::hasRessource('Menu Paramétrage'))
                <li
                    class="{{ in_array(Route::currentRouteName(), ['parameters_globale', 'employe_list', 'employe_affectLivaison', 'agence_affectLivaison', 'client_list', 'cheque_list', 'taxations', 'taxations_commissions', 'client_list', 'autre_parametrage']) ? 'active bold open ' : '' }}">
                    <a class="collapsible-header waves-effect waves-cyan" href="JavaScript:void(0)"><i
                            class="material-icons">settings</i><span class="menu-title"
                            data-i18n="eCommerce">Paramétrage</span></a>
                    <div class="collapsible-body">
                        <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                            @if (\Auth::user()::hasRessource('SMenu Paramétrage général'))
                                <li>
                                    <a href="{{ route('parameters_globale') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['parameters_globale']) ? 'active' : '' }}"><i
                                            class="material-icons">build</i><span data-i18n="Pricing">Paramétrage
                                            général</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Collaborateurs'))
                                <li>
                                    <a href="{{ route('employe_list') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['employe_list']) ? 'active' : '' }}"><i
                                            class="material-icons">account_circle</i><span
                                            data-i18n="Products Page">Collaborateurs</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Clients'))
                                <!--<li>
                            <a href="{{ route('agence_affectLivaison') }}"><i
                                    class="material-icons">chevron_right</i><span data-i18n="Pricing">Affect.
                                    Agence</span></a>
                        </li>-->
                                <li>
                                    <a href="{{ route('client_list') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['client_list']) ? 'active' : '' }}"><i
                                            class="material-icons">group</i><span
                                            data-i18n="Pricing">Clients</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Conventions/Tarifs'))
                                <!--<li>
                        <a href="{{ route('cheque_list') }}"
                            class="{{ in_array(Route::currentRouteName(), ['cheque_list']) ? 'active' : '' }}"><i
                                class="material-icons">chevron_right</i><span data-i18n="Pricing">Gestion
                                Chèques</span></a>
                    </li>-->
                                <li>
                                    <a href="{{ route('taxations_type') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['taxations_type']) ? 'active' : '' }}"><i
                                            class="material-icons">dehaze</i><span data-i18n="Pricing">Conventions
                                            /Tarifs</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Promotions'))
                                <li>
                                    <a href="{{ route('promotion_list') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['promotion_list']) ? 'active' : '' }}"><i
                                            class="material-icons">event_note</i><span
                                            data-i18n="Pricing">Promotions</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Commissions Livreurs'))
                                <li>
                                    <a href="{{ route('taxations_commissions') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['taxations_commissions']) ? 'active' : '' }}"><i
                                            class="material-icons">local_atm</i><span data-i18n="Pricing">Commissions
                                            Livreurs</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Autres Paramétrages'))
                                <li>
                                    <a href="{{ route('autre_parametrage') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['autre_parametrage']) ? 'active' : '' }}"><i
                                            class="material-icons">settings_applications</i><span
                                            data-i18n="Pricing">Autres
                                            paramétrages</span></a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            @if (\Auth::user()::hasRessource('Menu Securite'))
                <li
                    class="bold {{ in_array(Route::currentRouteName(), ['user_list', 'user_create', 'user_update', 'role_list', 'role_create', 'role_update', 'droit_list', 'droit_create', 'droit_update']) ? 'active bold open ' : '' }}">
                    <a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i
                            class="material-icons">verified_user</i><span class="menu-title"
                            data-i18n="eCommerce">Securité</span></a>
                    <div class="collapsible-body"
                        style=" {{ in_array(Route::currentRouteName(), ['user_list', 'user_create', 'user_update', 'role_list', 'role_create', 'role_update', 'droit_list', 'droit_create', 'droit_update']) ? 'display: block;' : '' }}">
                        <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                            @if (\Auth::user()::hasRessource('SMenu Utilisateurs'))
                                <li>
                                    <a href="{{ route('user_list') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['user_list', 'user_create', 'user_update']) ? 'active' : '' }}"><i
                                            class="
                                material-icons">people</i><span
                                            data-i18n="Products Page">Utilisateurs</span></a>
                                </li>
                            @endif
                            @if (\Auth::user()::hasRessource('SMenu Roles'))
                                <li>
                                    <a href="{{ route('role_list') }}"
                                        class="{{ in_array(Route::currentRouteName(), ['role_list', 'role_create', 'role_update']) ? 'active' : '' }}"><i
                                            class="material-icons">check_box</i><span
                                            data-i18n="Pricing">Rôles</span></a>
                                </li>
                            @endif
                            <!--<li>
                        <a href="{{ route('droit_list') }}" class="{{ in_array(Route::currentRouteName(), ['droit_list', 'droit_create', 'droit_update']) ? 'active' : '' }}">
                            <i class="material-icons   ">no_encryption</i><span data-i18n="Pricing">Droits</span></a>
                    </li>-->

                        </ul>
                    </div>
                </li>
            @endif

        </ul>
        <div class="navigation-background"></div>
        <a class="
                    sidenav-trigger
                    btn-sidenav-toggle btn-floating btn-medium
                    waves-effect waves-light
                    hide-on-large-only
                "
            href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
    </aside>
    <!-- END: SideNav-->

    <!-- BEGIN: Page Main-->
    <div id="main">
        @yield('content')
    </div>
    <!-- END: Page Main-->

    <!-- BEGIN: Footer-->

    <footer
        class="
                page-footer
                footer footer-static footer-light
                navbar-border navbar-shadow
            ">
        <div class="footer-copyright">
            <div class="container">
                <span>&copy; <?php echo date('Y'); ?>
                    <a href="#" target="_blank">GOTAWSIL</a>
                    All rights reserved.</span><span class="right hide-on-small-only">Design and Developed by
                    <a href="#">GOTAWSIL</a></span>
            </div>
        </div>
    </footer>

    <!-- END: Footer-->
    <!-- BEGIN VENDOR JS-->
    <script src="/assets/js/vendors.min.js"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="/assets/vendors/chartjs/chart.min.js"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN THEME  JS-->
    <script src="/assets/js/plugins.js"></script>
    <script src="/assets/js/search.js"></script>
    <script src="/assets/js/custom/custom-script.js"></script>

    <script src="/assets/vendors/data-tables/js/jquery.dataTables.min.js"></script>
    <script src="/assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/vendors/data-tables/js/datatables.checkboxes.min.js"></script>

    <script src="/assets/vendors/select2/select2.full.min.js"></script>

    <script src="/assets/js/vue3.prod.js"></script>


    <!-- END THEME  JS-->

    <!-- BEGIN PAGE LEVEL JS
     -->
    <!-- END PAGE LEVEL JS-->


    <script>
        $(document).ready(function() {
            // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
            $('.modal').modal();
        })
        $(document).ready(function() {

            $.get("/new-subscribers-count", function(data, status) {
                $('#new_subscribers').html(JSON.parse(data).newSub);
            });
            setInterval(function() {
                $.get("/new-subscribers-count", function(data, status) {
                    $('#new_subscribers').html(JSON.parse(data).newSub);
                });
            }, 5000);


            $.get("/new-ramassage-count", function(data, status) {
                $('#new_ramassage').html(JSON.parse(data).newRam);
            });
            setInterval(function() {
                $.get("/new-ramassage-count", function(data, status) {
                    $('#new_ramassage').html(JSON.parse(data).newRam);
                });
            }, 5000);

            $.get("/new-reclamation-count", function(data, status) {
                $('#new_reclamation').html(JSON.parse(data).newRec);
            });
            setInterval(function() {
                $.get("/new-reclamation-count", function(data, status) {
                    $('#new_reclamation').html(JSON.parse(data).newRec);
                });
            }, 5000);





            $('.timepicker').timepicker({
                twelveHour: false,
                showClearBtn: true,
                autoClose: true,
                showView: 'hours',
                i18n: {
                    months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                        'Septembre', 'Octobre', 'Novembre', 'Décembre'
                    ],

                    monthsShort: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jui', 'Jui', 'Aoû', 'Sep', 'Oct',
                        'Nov', 'Déc'
                    ],
                    weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                    today: 'Aujourd\'hui',
                    cancel: 'Annuler',
                    done: 'OK',
                    clear: 'Effacer'
                }
            });
            $('.datepicker').datepicker({
                firstDay: true,
                format: 'yyyy-mm-dd',
                clear: 'effacer',
                formatSubmit: 'yyyy/mm/dd',
                showClearBtn: true,
                autoClose: true,
                i18n: {
                    months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                        'Septembre', 'Octobre', 'Novembre', 'Décembre'
                    ],
                    monthsShort: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jui', 'Jui', 'Aoû', 'Sep', 'Oct',
                        'Nov', 'Déc'
                    ],
                    weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                    today: 'Aujourd\'hui',
                    cancel: 'Annuler',
                    done: 'OK',
                    clear: 'Effacer'
                }
            });
            $(".select2").select2({
                dropdownAutoWidth: true,
                width: '100%'
            });

            const App = {
                mounted() {
                    this.loadData();
                    $('.tooltipped').tooltip();
                },
                methods: {
                    loadData() {
                        if ($("#list-datatable").length > 0) {
                            $("#list-datatable").DataTable({
                                "aaSorting": [
                                    [0, "desc"]
                                ],
                                "language": {
                                    url: '/assets/vendors/data-tables/i18n/fr_fr.json'
                                }
                            });
                        };
                    },
                },
            }
            Vue.createApp(App).mount('#app');
        });
    </script>

    @yield('js')

</body>

</html>
