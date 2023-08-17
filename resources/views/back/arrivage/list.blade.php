@extends($layout)
<style>
    [type='checkbox']:checked+span:not(.lever):before {
        content: "X";
        color: red;
        padding-left: 10px;
        font-size: 25px;
        top: -4px;
        left: -5px;
        width: 20px;
        height: 22px;
        transform: rotate(0deg)!important;
        -ms-transform-origin: 100% 100%;
        transform-origin: 100% 100%;
        border-top: 2px solid transparent!important;
        border-right: 2px solid transparent!important;
        border-bottom: 2px solid transparent!important;
        border-left: 2px solid transparent!important;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
    }
</style>
@section('content')




    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
        style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Arrivage</span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('bon_list') }}">Liste des bons</a>
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
                    @if (session()->has('validate'))
                        <div class="card-alert card green">
                            <div class="card-content white-text">
                                <p> {{ session()->get('validate') }}</p>
                            </div>
                            <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    @endif
                    <br>
                    <form id="Arrivage" action="{{ route('create_list') }}" method="post">
                        @csrf

                        <span>Feuille de chargement : </span>
                        <div class="input-field" style="width: 30%">
                            <select name="arrivage" class="select2 browser-default" id="selectaff">
                                <option value="">Sélectionner feuille de chargement ...</option>
                                @foreach ($records as $record)
                                    @if ($record->processusArivage->count())
                                        @foreach ($record->processusArivage->unique('id_feuille_charge') as $rec)
                                            <option value="{{ $record->id }}">{{ $record->code }} de l'agence :
                                                {{ $rec->agenceDetail->libelle }} le :
                                                {{ $rec->updated_at }} </option>
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>

                        </div>
                        <button type="button" class="btn btn-info" onclick="openSuppModal() ">Valider réception</button>
                        <span style="color: red">*Les colis que vous avez sélectionner vont passé au stock perdu</span>
                        <button style="visibility: hidden;" type="submit" class="btn btn-info">Valider réception</button>
                        <div class="list-table" id="app">
                            <div class="card">
                                <div class="card-content">
                                    <div class="responsive-table">
                                        <table id="table" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>N° Exp</th>
                                                    {{-- <th>Saisie le</th> --}}
                                                    <th>Date Ram</th>
                                                    <th>Expéditeur</th>
                                                    <th>Origine</th>
                                                    <th>Destinataire</th>
                                                    <th>Destination</th>
                                                    <th>Téléphone</th>
                                                    <th>Etape</th>
                                                    <th>Colis</th>
                                                    <th>Nature</th>
                                                    <th>Fond</th>
                                                    <th>Port</th>
                                                    <th>TTC</th>

                                                </tr>
                                            </thead>
                                            <tbody id="datax">


                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            {{-- <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a href="{{route('bon_create')}}" class="btn-floating btn-large gradient-45deg-light-blue-cyan gradient-shadow"><i class="material-icons">add</i></a></div> --}}
        </div>
    </div>

    <div id="confirmArrivage" class="modal">
        <div class="modal-content">
            <center class="mb-2" id="messageimage">

            </center>

            <h4 style="text-align: center"> Confirmation d'arrivage</h4>
            <div class="text-center" id="messageArrivage">
                Êtes-vous sûr de vouloir valider la l'arrivage ?
            </div>
            <input type="hidden" name="delId" id="delId">
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn grey">Annuler</a>
            <a href="#!" class="waves-effect waves-green btn red" onclick="ConfrmArrivage()">Valider l'arrivage</a>
        </div>
    </div>

@stop

@section('js')
    <script src="/assets/js/scripts/data-tables.js"></script>


    <script>
        function openSuppModal() {
            let numExp = '';

            $("input:checked").each(function() {
                // numExp.push($(this).val());
                numExp += ' ' + $(this).val();
            });
            if (numExp == '') {
                $('#messageArrivage').html("<h5>Êtes-vous sûr de vouloir valider la l'arrivage ?</h5>");
                $('#messageimage').html('<img src="/assets/images/box.png" width="200px" alt="">');
                $('#confirmArrivage').modal('open');
            } else {
                $('#messageArrivage').html(
                    '<h5 style="color:red ; text-align:center">Attention : Les colis que vous avez sélectionner vont passé au stock perdu</h5> <b style ="color:black; text-align:right">Les colis sont :</b> <strong style= "color:blue">' +
                    numExp +
                    '</strong><h6>Vérifier que vous avez sélectionner uniquement les colis qui vont passer au stock perdu.</h6> <h6>Les colis non sélectionné vont passer automatiquement au stock.</h6>'
                );
                $('#messageimage').html('<img src="/assets/images/warning.png" width="200px" alt="">');
                $('#confirmArrivage').modal('open');

            }


        }

        function ConfrmArrivage() {
            $('#Arrivage').submit();
        }



        // Basic Select2 select
        $(".select2").select2({
            dropdownAutoWidth: true,
            width: '100%'
        });

        function suppRecord() {
            window.location.replace("/bon/delete/" + $("#delId").val());
        }
        $(document).ready(function() {
            $('#selectaff').change(function() {
                let cid = $(this).val();
                if (cid != '') {
                    $.ajax({
                        url: '/arrivage/bon/list',
                        type: 'post',
                        data: 'cid=' + cid + '&_token={{ csrf_token() }}',
                        success: function(result) {
                            $('#datax').html(result)
                        }
                    });
                }

            });

            $("#table").DataTable({
                "aaSorting": [
                    [2, "desc"]
                ],
                "language": {
                    url: '/assets/vendors/data-tables/i18n/fr_fr.json'
                }
            });
            setTimeout(function() {
                $('.tooltipped').tooltip();
            }, 2000);

            $("#checkall").change(function(e) {
                $('.checkbox').prop("checked", $('#checkall').prop("checked"));
            });
        });
    </script>








@stop
