<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="rtl">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title> GOTAWSIL </title>

    <link rel="apple-touch-icon" href="/assets/images/favicon/apple-touch-icon-152x152.png" />
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/favicon/favicon-32x32.png" />


    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- BEGIN: VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="/assets/vendors/vendors.min.css">
    <!-- END: VENDOR CSS-->
    <!-- BEGIN: FONT AWESOME-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
        integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <!-- END: FONT AWESOME-->
    <!-- BEGIN: Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="/assets/css/themes/vertical-dark-menu-template/materialize.css">

    <link rel="stylesheet" type="text/css" href="/assets/css/pages/login.css">
    <!-- END: Page Level CSS-->
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="/assets/css/custom/custom.css">
    <!-- END: Custom CSS-->
    <style>

        /* Style of background */
        body{
	margin:0;
	padding:0;
	font-family:"arial",heletica,sans-serif;
	font-size:12px;
    background: #2980b9 url('https://static.tumblr.com/03fbbc566b081016810402488936fbae/pqpk3dn/MRSmlzpj3/tumblr_static_bg3.png') repeat 0 0;
	-webkit-animation: 10s linear 0s normal none infinite animate;
	-moz-animation: 10s linear 0s normal none infinite animate;
	-ms-animation: 10s linear 0s normal none infinite animate;
	-o-animation: 10s linear 0s normal none infinite animate;
	animation: 10s linear 0s normal none infinite animate;

}

@-webkit-keyframes animate {
	from {background-position:0 0;}
	to {background-position: 500px 0;}
}

@-moz-keyframes animate {
	from {background-position:0 0;}
	to {background-position: 500px 0;}
}

@-ms-keyframes animate {
	from {background-position:0 0;}
	to {background-position: 500px 0;}
}

@-o-keyframes animate {
	from {background-position:0 0;}
	to {background-position: 500px 0;}
}

@keyframes animate {
	from {background-position:0 0;}
	to {background-position: 500px 0;}
}

/* End of background */
    .input-icons i {
        position: absolute;
        color: #c81537;
        font-size: 29px;
        line-height: 43px;
        height: 45px;
        width: 25px;
        margin: 0 0 0 4px;
        vertical-align: top;
        display: inline-block;
    }


    .input-icons {
        width: 100%;


    }

    .form-container {
        background-color: rgba(25, 145, 206, 0.4);
        font-family: 'Titillium Web', sans-serif;
        padding: 25px 10px;
        overflow: hidden;
        position: relative;
        margin: auto;
        top: 120px;
        border-radius: 30px;
        vertical-align: middle;
        max-width: 455px;
        z-index: 1;

    }

    .form-container:before {
        content: '';
        /* background: radial-gradient(at 50% 25%, #1991ce 0%, #1e6f98 70%); */
        background-color: #fffc;
        height: 17%;
        width: 100%;
        position: absolute;
        left: 0;
        top: 0;
        z-index: -1;
        clip-path: polygon(0 0, 100% 0%, 100% 100%, 0 90%);

    }

    .form-container .form-icon {
        color: #fff;
        font-size: 55px;
        line-height: 55px;
        text-align: center;
        margin: 0 0 10px;
    }

    .form-container .title {
        color: #fff;
        font-size: 33px;
        font-weight: 500;
        text-align: center;
        text-transform: capitalize;
        letter-spacing: 0.5px;
        margin: 25px;
    }

    .form-container .form-horizontal {
        background: #fff;
        padding: 15px;
        margin: 0 15px 20px;
        box-shadow: 0 0 7px rgba(0, 0, 0, 0.3);
        border-radius: 15px;
    }

    .form-horizontal .form-group {
        background-color: #fff;
        margin: 0 0 15px;
    }

    .form-horizontal .form-group:nth-child(3) {
        margin-bottom: 40px;
    }



    .form-horizontal .form-control {
        color: #555;
        background-color: transparent;
        font-size: 20px;
        letter-spacing: 1px;
        width: calc(100% - 33px);
        height: 45px;
        padding: 0 5px;
        box-shadow: none;
        border: none;
        border-radius: 0;
        display: inline-block;
        transition: all 0.3s;
    }

    .form-horizontal .form-control:focus {
        box-shadow: none;
        border: none;
    }

    .form-horizontal .form-control::placeholder {
        color: #999;
        font-size: 20px;
        font-weight: 300;
        text-transform: capitalize;
    }

    .form-horizontal .forgot-pass {
        font-size: 18px;
        font-weight: 500;
        text-align: center;
        margin: 0 0 15px 0;
        display: block;
    }

    .form-horizontal .forgot-pass a {
        color: #a8ddf8;
        transition: all 0.3s ease 0s;
    }

    .form-horizontal .forgot-pass a:hover {
        color: #0c3448;
    }

    .form-horizontal .btn {
        color: #fff;
        background: #c81537;
        font-size: 20px;
        font-weight: 600;
        text-transform: capitalize;
        letter-spacing: 1px;
        width: 100%;

        margin: auto;
        border: none;
        border-radius: 3px;
        transition: all 0.3s ease;
    }

    .form-horizontal .btn:hover,
    .form-horizontal .btn:focus {
        color: #fff;
        background-color: #1991ce;
        letter-spacing: 4px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3), 0 0 10px rgba(0, 0, 0, 0.3) inset;
        outline: none;
    }

    .form-container .user-signup {
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        text-align: center;
        display: block;
    }

    .form-container .user-signup a {
        color: #a8ddf8;
        transition: all 0.3s ease 0s;
    }

    .form-container .user-signup a:hover {
        color: #0c3448;
        text-shadow: 0 0 1px rgba(0, 0, 0, 0.5);
    }

    ul {
        display: flex;
        margin-top: 30px;
    }

    ul li {
        list-style: none;
    }

    ul li a {
        width: 60px;
        height: 60px;
        background-color: #fff;
        text-align: center;
        line-height: 80px;
        font-size: 30px;
        margin: 0 12px;
        display: block;
        border-radius: 90%;
        position: relative;
        overflow: hidden;
        border: 3px solid #fff;
        z-index: 1;
    }

    ul li a .icon {
        position: relative;
        color: #262626;
        transition: .5s;
        z-index: 3;
        bottom: 20%;
    }

    ul li a:hover .icon {
        color: #fff;
        transform: rotateY(360deg);
    }

    ul li a:before {
        content: "";
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        height: 100%;
        background: #f00;
        transition: .5s;
        z-index: 2;
    }

    ul li a:hover:before {
        top: 0;
    }

    ul li:nth-child(1) a:before {
        background: #3b5999;
    }

    ul li:nth-child(2) a:before {
        background: #55acee;
    }

    ul li:nth-child(3) a:before {
        background: #0077b5;
    }

    ul li:nth-child(4) a:before {
        background: linear-gradient(#e66465, #9198e5);
    }
    body{
        zoom: 80%;
    }
    </style>
</head>
<!-- END: Head-->

<body
    class="vertical-layout page-header-light vertical-menu-collapsible vertical-dark-menu preload-transitions 1-column blank-page blank-page"
    data-open="click" data-menu="vertical-dark-menu" style="background-color :#1991ce;"
    data-col="1-column">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="row">


                    <div class="">
                        <div class="">
                            <div class="row shadow" >
                                <div class="col-md-offset-6 col-md-6 col-sm-offset-6 col-sm-6">
                                    <div class="form-container">
                                        <div class="form-icon">
                                            <a href="{{route('home')}}"><img
                                                    src="/assets/front/logo-hori.png" height="60px"
                                                    alt="" /></a>


                                        </div>

                                        <h3 class="title px-5"></h3>
                                        <form class="form-horizontal" method="POST" action="">
                                            @csrf
                                            <p style="
                                            font-size: 23px;
                                            text-align: center;
                                        ">Nous vous avons envoyé par e-mail des instructions vous indiquant comment réinitialiser votre mot de passe. Consultez votre boîte de réception et cliquez sur le lien fourni.</p>

                                        </form>
                                        <span class="user-signup">Vous n'avez pas de compte ? <a
                                                href="{{route('register')}}"><br> Créer maintenant !</a>

                                            <ul>
                                                <li class="icon-preview col s6 m3">
                                                    <a href="#">
                                                        <i class="fab fa-facebook-f icon"></i> </a>
                                                </li>
                                                <li class="icon-preview col s6 m3">
                                                    <a href="#"><i class="fab fa-twitter icon"></i></a>
                                                </li>
                                                <li class="icon-preview col s6 m3">
                                                    <a href="#"><i class="fab fa-linkedin-in icon"></i></a>
                                                </li>
                                                <li class="icon-preview col s6 m3">
                                                    <a href="#"><i style="font-size: 40px;bottom: 8px;"
                                                            class="fab fa-instagram icon"></i></a>
                                                </li>
                                            </ul>
                                        </span>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="content-overlay"></div>
        </div>
    </div>

    <!-- BEGIN VENDOR JS-->
    <script src="/assets/js/vendors.min.js"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN THEME  JS-->
    <script src="/assets/js/plugins.js"></script>
    <script src="/assets/js/search.js"></script>
    <script src="/assets/js/custom/custom-script.js"></script>
    <!-- END THEME  JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <!-- END PAGE LEVEL JS-->
</body>

</html>
