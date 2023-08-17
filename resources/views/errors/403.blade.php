<style>
@import url('https://fonts.googleapis.com/css2?family=Heebo:wght@500&display=swap');

body {
    background-color: #1991ce;
    color: #fff;
    font-size: 100%;
    padding: 2em;
    line-height: 1.5;
    font-family: 'Heebo', sans-serif;
}

.button {
    font-weight: 300;
    color: #fff;
    font-size: 1.2em;
    text-decoration: none;
    border: 1px solid #efefef;
    padding: .5em;
    border-radius: 3px;
    text-align: center;
    transition: all .3s linear;
}

.button:hover {
    background-color: #c81537;
    color: #fff;
}

p {
    font-size: 2em;
    text-align: center;
    font-weight: 100;
}

img {
    margin-bottom: 30px;
}

h3 {
    width: 50%;
    font-weight: 400;
}

@keyframes unlock-circle {
    0% {
        bottom: 200px;
    }

    25% {
        bottom: 200px;
    }

    50% {
        bottom: 150px;
    }

    75% {
        bottom: 150px;
    }

    100% {
        bottom: 200px;
    }
}

@keyframes unlock-box {
    0% {
        bottom: 280px;
    }

    25% {
        bottom: 280px;
    }

    50% {
        bottom: 230px;
    }

    75% {
        bottom: 230px;
    }

    100% {
        bottom: 280px;
    }
}


.wrapper {
    height: 300px;
    width: 300px;
    margin: auto;
    margin-top: 40px;
}

.base {
    background-color: #c81537;
    width: 200px;
    height: 170px;
    border-radius: 30px;
    margin: 0 auto;
    position: relative;
    top: 100px;
    z-index: 100;
}

.base-bottom {
    background-color: #bdc3c7;
    width: 200px;
    height: 85px;
    border-radius: 0 0 30px 30px;
    top: 85px;
    position: relative;
    opacity: 0.4;
}

.lock-cirlce {
    height: 180px;
    width: 110px;
    border-radius: 45px;
    z-index: 10;
    background-color: #bdc3c7;
    position: relative;
    margin: 0 auto;
    bottom: 150px;
    animation-name: unlock-circle;
    animation-duration: 6s;
    animation-iteration-count: infinite;
}

.lock-circle-inside {
    height: 180px;
    width: 50px;
    border-radius: 30px;
    z-index: 20;
    background-color: #1991ce;
    position: relative;
    margin: 0 auto;
    top: 25px;
}

.lock-box {
    animation-name: unlock-box;
    animation-duration: 6s;
    animation-iteration-count: infinite;
    position: relative;
    height: 50px;
    width: 50px;
    background-color: #1991ce;
    bottom: 230px;
    left: 170px;
    z-index: 80;
}

.lock-inside-top {
    width: 50px;
    height: 50px;
    border-radius: 50px;
    background-color: #bdc3c7;
    z-index: 300;
    position: relative;
    bottom: 45px;
    left: 75px;
}

.lock-inside-bottom {
    width: 30px;
    height: 80px;
    background-color: #bdc3c7;
    z-index: 300;
    position: relative;
    bottom: 85px;
    left: 85px;
}

p {
    margin-top: 0;
    margin-bottom: 5px;
}

h3 {
    margin-inline: 10px !important;
}
</style>


<body>

    <div class="wrapper">
        <div class="base">
            <div class="base-bottom">
            </div>
            <div class="lock-inside-top">
            </div>
            <div class="lock-inside-bottom">
            </div>
        </div>
        <div class="lock-cirlce">
            <div class="lock-circle-inside">
            </div>
        </div>
        <div class="lock-box">
        </div>
    </div>

    <center>


        <p>L'accès à cette page est restreint.</p>
        <h3>La page à laquelle vous essayez d'accéder a un accès restreint. Veuillez consulter votre administrateur
            système.</h3>

            @if(!isset(auth()->user()->role))
            <a class="button" href="{{route('home')}}"><i class="icon-home"></i>Revenir à la page initiale, c'est mieux.</a>
            @elseif(auth()->user()->role == '6' )
            <a class="button" href="{{route('Dashboard_Commercial')}}"><i class="icon-home"></i>Revenir à la page initiale, c'est mieux.</a>
            @elseif(auth()->user()->role == '2' || auth()->user()->role == '5' || auth()->user()->role == '7' || auth()->user()->role == '8')
            <a class="button" href="{{route('Dashboard_Pilotage')}}"><i class="icon-home"></i>Revenir à la page initiale, c'est mieux.</a>
            @elseif(auth()->user()->role == '3')
            <a class="button" href="{{route('Dashboard_Client')}}"><i class="icon-home"></i>Revenir à la page initiale, c'est mieux.</a>
            @elseif(auth()->user()->role == '1')
            <a class="button" href="{{route('admin')}}"><i class="icon-home"></i>Revenir à la page initiale, c'est mieux.</a>
            @else
            <a class="button" href="{{route('home')}}"><i class="icon-home"></i>Revenir à la page initiale, c'est mieux.</a>
            @endif

    </center>
    <img src="/assets/images/logo_GoTawsil_vertical.png" height="70"
        style="position:fixed;margin-top:30px;left:20px;bottom: 0px;opacity: 0.6;">
</body>
