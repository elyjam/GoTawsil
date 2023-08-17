<link href='https://fonts.googleapis.com/css?family=Muli' rel='stylesheet'>
<style>
    html,
    body {

        background: #bbe3f7 !important;
        font-family: 'Roboto', sans-serif;

    }

    .slides {
        padding: 0;
        max-width: 609px;
        max-height: 420px;
        display: block;
        margin: 0 auto;
        position: relative;
    }

    .slides * {
        user-select: none;
        -ms-user-select: none;
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        -webkit-touch-callout: none;
    }

    .slides input {
        display: none;
    }

    .slide-container {
        display: block;
    }

    .slide {
        top: 0;
        opacity: 0;
        max-width: 609px;
        max-height: 420px;
        display: block;
        position: absolute;

        transform: scale(0);

        transition: all .7s ease-in-out;
    }

    .slide img {
        max-height: 70vh;
    }

    .nav label {
        width: 200px;
        height: 100%;
        display: none;
        position: absolute;

        opacity: 0;
        z-index: 9;
        cursor: pointer;

        transition: opacity .2s;

        color: #FFF;
        font-size: 156pt;
        text-align: center;
        line-height: 380px;
        font-family: "Varela Round", sans-serif;
        background-color: rgba(255, 255, 255, .3);
        text-shadow: 0px 0px 15px rgb(119, 119, 119);
    }

    .slide:hover+.nav label {
        opacity: 0.5;
    }

    .nav label:hover {
        opacity: 1;
    }

    .nav .next {
        right: 0;
    }

    input:checked+.slide-container .slide {
        opacity: 1;

        transform: scale(1);

        transition: opacity 1s ease-in-out;
    }

    input:checked+.slide-container .nav label {
        display: block;
    }

    .nav-dots {
        width: 100%;
        bottom: 30px;
        height: 11px;
        display: block;
        position: absolute;
        text-align: center;
    }

    .nav-dots .nav-dot {
        top: -5px;
        width: 11px;
        height: 11px;
        margin: 0 4px;
        position: relative;
        border-radius: 100%;
        display: inline-block;
        background-color: rgba(0, 0, 0, 0.6);
    }

    .nav-dots .nav-dot:hover {
        cursor: pointer;
        background-color: rgba(0, 0, 0, 0.8);
    }

    input#img-1:checked~.nav-dots label#img-dot-1,
    input#img-2:checked~.nav-dots label#img-dot-2,
    input#img-3:checked~.nav-dots label#img-dot-3,
    input#img-4:checked~.nav-dots label#img-dot-4,
    input#img-5:checked~.nav-dots label#img-dot-5,
    input#img-6:checked~.nav-dots label#img-dot-6 {
        background: rgba(0, 0, 0, 0.8);
    }


    #invoice {
        position: relative;

        margin: 0;
        width: 100%;
        height: 100%;
        background: #FFF;
    }

    [id*='invoice-'] {
        /* Targets all id with 'col-' */
        border-bottom: 1px solid #EEE;
        padding: 30px;
    }

    .effect2 {
        position: relative;
    }

    .effect2:before,
    .effect2:after {
        z-index: -1;
        position: absolute;
        content: "";
        bottom: 15px;
        left: 10px;
        width: 50%;
        top: 80%;
        max-width: 300px;
        background: #777;
        -webkit-box-shadow: 0 15px 10px #777;
        -moz-box-shadow: 0 15px 10px #777;
        box-shadow: 0 15px 10px #777;
        -webkit-transform: rotate(-3deg);
        -moz-transform: rotate(-3deg);
        -o-transform: rotate(-3deg);
        -ms-transform: rotate(-3deg);
        transform: rotate(-3deg);
    }

    .effect2:after {
        -webkit-transform: rotate(3deg);
        -moz-transform: rotate(3deg);
        -o-transform: rotate(3deg);
        -ms-transform: rotate(3deg);
        transform: rotate(3deg);
        right: 10px;
        left: auto;
    }

    .info {
        display: block;
        float: left;
        margin-left: 20px;
    }

    .title {
        text-align: right;
    }

    h5 {
        font-family: 'Muli', sans-serif;
        font-size: 1.64rem;
        line-height: 110%;
        margin: 0;
        font-weight: 100;
    }

    h4 {
        font-family: 'Muli', sans-serif;
        font-size: 2.28rem;
        line-height: 110%;
        margin: 0;
        font-weight: 100;
    }

    p {
        font-family: 'Muli', sans-serif;
        font-size: .7em;
        color: #666;
        line-height: 1.2em;
        margin-top: 0;
    }

    .logo {
        float: left;
        margin-right: 10px;
        height: 60px;
        width: 60px;
        background: url(/assets/images/gallery/fast-delivery.png) no-repeat;
        background-size: 60px 60px;
    }

    #invoice-top {
        margin-bottom: 80px;
    }
</style>
<div id="invoice" class="effect2">

    <div id="invoice-top">

        <div class="info">
            <div class="logo"></div>
            <h5 style="margin: 0;">Pièces à joindre de l'expédition</h5>
        </div>
        <!--End Info-->
        <div class="title">
            <h4 style="margin: 0;">N° #{{ $record->num_expedition }}</h4>
            <p>Date: {{ date_format($record->created_at, 'W M Y - H:i:s') }}
            </p>
        </div>
        <!--End Title-->
    </div>

    <ul class="slides">

        @foreach ($images as $image)
            <input type="radio" name="radio-btn" id="img-{{ $image->id }}" checked />
            <li class="slide-container">
                <div class="slide">
                    <img class="responsive-img" src="/uploads/expeditions/{{ $image->name }}" />
                </div>
            </li>
        @endforeach

        <li class="nav-dots">
            @foreach ($images as $image)
                <label for="img-{{ $image->id }}" class="nav-dot" id="img-dot-{{ $image->id }}"></label>
            @endforeach
        </li>
    </ul>

</div>
