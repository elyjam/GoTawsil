@extends($layout)

@section('content')
    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
        style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Retour de fonds > Gén.Remboursement</span></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="section">
                    <form method="GET">
                        <br>
                        <div class="card">
                            <div class="card-panel">
                                <div class="row">
                                    <div class="col s12 m12">
                                        <div class="row">
                                            <div class="col s4 input-field">
                                                <input id="selection_date" value="{{ $date }}" name="date"
                                                    type="text" placeholder="" class="datepicker" required>
                                                <label for="selection_date">Date sélection </label>
                                                @error('selection_date')
                                                    <span class="helper-text materialize-red-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col s4 input-field">
                                                <button type="submit" class="btn btn-light">Charger </button>
                                                <a href="{{ route('remboursement_create') }}">
                                                    <button type="button" class="btn indigo" style="margin-left: 1rem;">
                                                        Réinitialiser</button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    @if ($date != null)
                        <form method="POST" id="form">
                            @csrf
                            <div class="col s12 display-flex justify-content-end">
                                <button type="button" onclick="$('#type').val(1); $('form').submit();" class="btn green">
                                    <i style="line-height: normal;" class="material-icons">monetization_on</i>
                                    Générer espèces
                                </button>
                                <button type="button" onclick="$('#type').val(2); $('form').submit();" class="btn green"
                                    style="margin-left: 1rem;">
                                    <i style="line-height: normal;" class="material-icons">call_to_action</i> Générer
                                    virements
                                </button>
                            </div>
                            <br><br>
                            @error('clients')
                                <div class="red lighten-1 white-text" style="padding: 5px;">{{ $message }}
                                </div>
                            @enderror
                            <div class="users-list-table">
                                <div class="card">
                                    <div class="card-content">
                                        <!-- datatable start -->
                                        <div class="responsive-table">
                                            <input type="hidden" name="type" id="type">
                                            <table class="data-table" id="table" class="table">
                                                <thead>
                                                    <tr>
                                                        <th width="20px" style="width: 20px !important;">
                                                            <label>
                                                                <input type="checkbox" id='checkall'>
                                                                <span></span>
                                                            </label>
                                                        </th>
                                                        <th>Client</th>
                                                        <th>Montant</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($clients as $client)
                                                        @php
                                                            $client_ = \App\Models\Client::find($client->id);
                                                        @endphp
                                                            {{-- <tr>
                                                                <td>
                                                                    {{
                                                                        $client_->client_Remboursements($date,$client->id)
                                                                    }}
                                                                </td>
                                                            </tr> --}}

                                                            @if ($client_->total_remb($date) >= 100 || $client_->total_remb($date) == 0 )
                                                            <tr>
                                                            <td style="padding-left: 18px;"> <label>
                                                                        <input class="checkbox" value="{{ $client->id }}"
                                                                            type="checkbox" name='clients[]'>
                                                                        <span></span>
                                                                    </label>
                                                                </td>

                                                                <td>{{ $client->libelle }}</td>
                                                                <td>
                                                                    <span
                                                                        class="badge blue">{{ $client_->total_remb($date) }}
                                                                        Dhs</span>
                                                                </td>
                                                            </tr>
                                                            @else
                                                            <tr>
                                                                <td style="padding-left: 18px;">
                                                                    <svg version="1.1" id="Layer_1"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                        x="0px" y="0px" width="22px"
                                                                        viewBox="0 0 512 512"
                                                                        enable-background="new 0 0 512 512"
                                                                        xml:space="preserve">
                                                                        <path fill="#E76E54" opacity="1.000000"
                                                                            stroke="none"
                                                                            d="
                                                                        M1.000001,451.000000
                                                                        C1.000000,321.977356 1.000000,192.954727 1.295092,63.805614
                                                                        C2.046905,63.031387 2.769495,62.440201 2.921138,61.727531
                                                                        C6.037134,47.083389 13.356174,34.532322 23.631138,24.065168
                                                                        C33.816849,13.688938 46.411591,6.722063 60.622433,2.955762
                                                                        C61.524380,2.716719 62.212440,1.670637 63.000000,1.000000
                                                                        C192.022644,1.000000 321.045288,1.000000 450.194580,1.295261
                                                                        C450.800568,2.026112 451.218323,2.732989 451.768219,2.857849
                                                                        C468.072723,6.559752 481.527100,14.848170 492.822754,27.182457
                                                                         C501.730682,36.909477 507.292145,48.216133 511.042023,60.643925
                                                                         C511.313660,61.544247 512.329895,62.219902 513.000000,63.000008
                                                                        C513.000000,192.022644 513.000000,321.045288 512.702881,450.196594
                                                                        C511.950256,450.973907 511.265778,451.560974 511.072388,452.280151
                                                                        C502.830872,482.927582 483.245056,502.301270 452.885315,511.062592
                                                                        C452.133148,511.279633 451.623016,512.335449 451.000000,513.000000
                                                                         C321.977356,513.000000 192.954727,513.000000 63.803596,512.703125
                                                                         C63.026501,511.950684 62.434025,511.229248 61.721035,511.078735
                                                                         C46.699184,507.907562 33.960213,500.242493 23.345444,489.632141
                                                                         C13.323622,479.614594 6.609938,467.243164 2.955825,453.376160
                                                                         C2.718338,452.474884 1.670751,451.787109 1.000001,451.000000
                                                                             M249.500000,240.999084
                                                                        C234.502670,240.999084 219.505325,240.998291 204.507996,240.999298
                                                                        C184.678192,241.000610 164.847824,240.917175 145.018845,241.045929
                                                                            C136.547211,241.100937 129.906891,247.281006 129.073578,255.358246
                                                                        C128.244659,263.393127 133.737381,270.965515 141.793930,272.623199
                                                                        C143.886154,273.053680 146.100418,272.986267 148.258667,272.986908
                                                                        C205.581802,273.004425 262.904938,273.001007 320.228088,273.000824
                                                                        C336.225250,273.000763 352.222565,273.041656 368.219513,272.980347
                                                                        C375.636322,272.951904 381.485596,269.036438 383.909119,262.740204
                                                                        C388.064301,251.945038 380.169250,241.043594 367.979095,241.023895
                                                                        C328.819489,240.960587 289.659698,240.999573 249.500000,240.999084
                                                                         z" />

                                                                        <path fill="#FFFFFF" opacity="1.000000"
                                                                            stroke="none"
                                                                            d="
                                                                         M250.000000,240.999084
                                                                         C289.659698,240.999573 328.819489,240.960587 367.979095,241.023895
                                                                        C380.169250,241.043594 388.064301,251.945038 383.909119,262.740204
                                                                        C381.485596,269.036438 375.636322,272.951904 368.219513,272.980347
                                                                        C352.222565,273.041656 336.225250,273.000763 320.228088,273.000824
                                                                        C262.904938,273.001007 205.581802,273.004425 148.258667,272.986908
                                                                        C146.100418,272.986267 143.886154,273.053680 141.793930,272.623199
                                                                            C133.737381,270.965515 128.244659,263.393127 129.073578,255.358246
                                                                        C129.906891,247.281006 136.547211,241.100937 145.018845,241.045929
                                                                        C164.847824,240.917175 184.678192,241.000610 204.507996,240.999298
                                                                        C219.505325,240.998291 234.502670,240.999084 250.000000,240.999084
                                                                        z" />
                                                                    </svg>
                                                                </td>

                                                                <td>{{ $client->libelle }}</td>
                                                                <td>
                                                                    <span
                                                                        class="badge red">{{ $client_->total_remb($date) }}
                                                                        Dhs</span>
                                                                    <span style="color: red">*il faut dépasser montant de
                                                                        100 dhs pour remboursement </span>
                                                                </td>
                                                            </tr>
                                                            @endif

                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script>
        $(document).ready(function() {
            $("#checkall").change(function(e) {
                $('.checkbox').prop("checked", $('#checkall').prop("checked"));
            });
        });

        $(document).ready(function() {
            $("#table").DataTable({
                "aaSorting": [
                    [0, "desc"]
                ],
                paging: false,
                "language": {
                    url: '/assets/vendors/data-tables/i18n/fr_fr.json'
                }
            });
        });
    </script>
@stop
