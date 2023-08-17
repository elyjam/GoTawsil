<style>

    @import url(https://fonts.googleapis.com/css?family=Roboto:100,300,400,900,700,500,300,100);

    * {
        margin: 0;
        box-sizing: border-box;

    }

    body {
        background: #E0E0E0;
        font-family: 'Roboto', sans-serif;
        background-image: url('');
        background-repeat: repeat-y;
        background-size: 100%;
    }

    ::selection {
        background: #f31544;
        color: #FFF;
    }

    ::moz-selection {
        background: #f31544;
        color: #FFF;
    }

    h1 {
        font-size: 1.5em;
        color: #222;
    }

    h2 {
        font-size: .9em;
    }

    h3 {
        font-size: 1.2em;
        font-weight: 300;
        line-height: 2em;
    }

    p {
        font-size: .7em;
        color: #666;
        line-height: 1.2em;
    }

    #invoiceholder {
        width: 100%;
        hieght: 100%;
        padding-top: 50px;
    }

    #headerimage {
        z-index: -1;
        position: relative;
        top: -50px;
        height: 350px;
        /*background-image: url('https://mecaluxfr.cdnwm.com/blog/img/zone-tampon-entrepot.1.16.jpg');*/
        background-color: #1991ce;
        -webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, .15), inset 0 -2px 4px rgba(0, 0, 0, .15);
        -moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, .15), inset 0 -2px 4px rgba(0, 0, 0, .15);
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, .15), inset 0 -2px 4px rgba(0, 0, 0, .15);
        overflow: hidden;
        background-attachment: fixed;
        background-size: 1920px 80%;
        background-position: 50% -90%;
    }

    #invoice {
        position: relative;
        top: -290px;
        margin: 0 auto;
        width: 90%;
        background: #FFF;
    }

    [id*='invoice-'] { /* Targets all id with 'col-' */
        border-bottom: 1px solid #EEE;
        padding: 30px;
    }

    #invoice-top {
        min-height: 120px;
    }

    #invoice-mid {
        min-height: 120px;
    }

    #invoice-bot {
        min-height: 250px;
    }

    .logo {
        float: left;
        margin-right: 10px;
        height: 60px;
        width: 60px;
        background: url(/assets/images/gallery/fast-delivery.png) no-repeat;
        background-size: 60px 60px;
    }

    .info {
        display: block;
        float: left;
        margin-left: 20px;
    }

    .title {
        text-align: right;
    }


    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        padding: 5px 0 5px 15px;
        border: 1px solid #EEE
    }


    form {
        float: right;
        margin-top: 30px;
        text-align: right;
    }


    .effect2 {
        position: relative;
    }

    .effect2:before, .effect2:after {
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


    .legal {
        width: 70%;
    }


    .expedition_info .title {
        color: #fff;
        font-size: 25px;
        font-weight: 400;
        text-transform: capitalize;
        margin: 0;
        text-align: center;
    }

    .exp_header {
        background: #c81537;
        padding: 14px 0 14px;
        margin: 0 0 13px;
        border-radius: 100px 0;
        position: relative;
    }

    .prix_header {
        background: #1991ce;
        padding: 14px 0 14px;
        margin: 0 0 13px;
        border-radius: 100px 0;
        position: relative;
    }

    .des_header {
        background: #8d9498;
        padding: 14px 0 14px;
        margin: 0 0 13px;
        border-radius: 100px 0;
        position: relative;
    }

    .panel-heading {
        background: #4b5154;
        padding: 1px;
        border-radius: 20px 20px 0 0;
        margin: 0;

    }

    .panel-heading .title {
        color: #fff;
        font-size: 24px;
        font-weight: 600;
        line-height: 39px;
        text-transform: capitalize;
        margin: 0;
        text-align: center;
    }

    .panel {
        margin-bottom: 30px;
    }

</style>

<link rel="stylesheet" type="text/css" href="/assets/css/custom/custom.css"/>
<!-- BEGIN: VENDOR CSS-->
<link rel="stylesheet" type="text/css" href="/assets/vendors/vendors.min.css"/>
<!-- END: VENDOR CSS-->
<!-- BEGIN: Page Level CSS-->
<link rel="stylesheet" type="text/css" href="/assets/css/themes/vertical-dark-menu-template/materialize.css"/>
<link rel="stylesheet" type="text/css" href="/assets/css/themes/vertical-dark-menu-template/style.css"/>
<div id="invoiceholder">

    <div id="headerimage"></div>
    <div id="invoice" class="effect2">

        <div id="invoice-top">

            <div class="info">
                <div class="logo"></div>
                <h5 style="margin: 0;">Facture Detail</h5>
            </div><!--End Info-->
            <div class="title">
                <h4 style="margin: 0;">N° #{{$record->code}}</h4>
                <p>Date: {{date_format($record->created_at,'W M Y - H:i:s')}}
                </p>
            </div><!--End Title-->
        </div><!--End InvoiceTop-->

        <div id="invoice-mid">

            <div class="row expedition_info">

                <div class="col s12 m6 input-field text-center">
                    <div class="exp_header"><h4 class="title">Facture</h4></div>

                    <ul class="text-center">
                        <li>
                            N° Facture : {{$record->code}}
                        </li>
                        <li>
                            Généneré le : {{$record->created_at}}
                        </li>
                        <li>
                            Date Facture :
                        </li>
                    </ul>
                </div>
                <div class="col s12 m6 input-field text-center">
                    <div class="prix_header"><h4 class="title">Client</h4></div>
                    <ul>
                        <li>
                            Client : {{$record->clientDetail->libelle}}
                        </li>
                        <li>
                            Adresse : {{$record->clientDetail->adresse}}
                        </li>
                        <li>
                            Téléphone : {{$record->clientDetail->telephone}}
                        </li>

                    </ul>
                </div>

            </div>

        </div><!--End Invoice Mid-->

        <div id="invoice-bot">
            <div class="panel">
                <div class="panel-heading">
                    <h4 class="title">Informations</h4>
                </div>

                <table>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Date Réglement</th>
                        <th>Crée par</th>
                        <th>Annulé par</th>
                        <th>Mode Réglement</th>
                        <th>Banque</th>
                        <th>Réf. Réglement	</th>
                        <th>Banque</th>
                        <th>Montant</th>
                        <th>Annuler</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>
                    </tbody>
                </table>

            </div>

            <div class="panel">
                <div class="panel-heading">
{{--                    <h4 class="title">Information affectations</h4>--}}
                </div>

                <table>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Crée le</th>
                        <th>Remis le</th>
                        <th>Editer</th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>

                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    </tbody>
                </table>

            </div>




            {{--            <div id="legalcopy">--}}
            {{--                <p class="legal"><strong>Thank you for your business!</strong>  Payment is expected within 31 days;--}}
            {{--                    please process this invoice within that time. There will be a 5% interest charge per month on late--}}
            {{--                    invoices.--}}
            {{--                </p>--}}
            {{--            </div>--}}

        </div><!--End InvoiceBot-->
    </div><!--End Invoice-->
</div><!-- End Invoice Holder-->
<script src="/assets/js/vendors.min.js"></script>
<!-- BEGIN VENDOR JS-->
<!-- BEGIN THEME  JS-->
<script src="/assets/js/plugins.js"></script>
<script src="/assets/js/search.js"></script>
<script src="/assets/js/custom/custom-script.js"></script>
<script>

</script>
