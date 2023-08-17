@extends($layout)

<style>

    .msger-chat {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
    }

    .msger-chat::-webkit-scrollbar {
        width: 6px;
    }

    .msger-chat::-webkit-scrollbar-track {
        background: #ddd;
    }

    .msger-chat::-webkit-scrollbar-thumb {
        background: #bdbdbd;
    }

    .msg {
        display: flex;
        align-items: flex-end;
        margin-bottom: 10px;
    }

    .msg:last-of-type {
        margin: 0;
    }

    .msg-img {
        width: 50px;
        height: 50px;
        margin-right: 10px;
        background: #ddd;
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
        border-radius: 50%;
    }

    .msg-bubble {
        max-width: 450px;
        padding: 15px;
        border-radius: 15px;
        background: var(--left-msg-bg);
    }

    .msg-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .msg-info-name {
        margin-right: 10px;
        font-weight: bold;
    }

    .msg-info-time {
        font-size: 0.85em;
    }

    .left-msg .msg-bubble {
        border-bottom-left-radius: 0;
    }

    .right-msg {
        flex-direction: row-reverse;
    }

    .right-msg .msg-bubble {
        background: var(--right-msg-bg);
        color: #fff;
        border-bottom-right-radius: 0;
    }

    .right-msg .msg-img {
        margin: 0 0 0 10px;
    }

    .msger-inputarea {
        display: flex;
        padding: 10px;
        border-top: var(--border);
        background: #eee;
    }

    .msger-inputarea * {
        padding: 10px;
        border: none;
        border-radius: 3px;
        font-size: 1em;
    }

    .msger-input {
        flex: 1;
        background: #ddd;
    }

    .msger-send-btn {
        margin-left: 10px;
        background: rgb(0, 196, 65);
        color: #fff;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.23s;
    }

    .msger-send-btn:hover {
        background: rgb(0, 180, 50);
    }

    .msger-chat {

        height: 315px;
        overflow: auto;
        padding: 20px;
    }

</style>
@section('content')
    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
         style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><span>Detail de reclamation</span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('reclamation_list')}}">Liste des reclamations</a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Detail</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col s12">
            <div class="container">
                @if (Session::has('success'))
                    <div class="card-alert card gradient-45deg-deep-orange-orange">
                        <div class="card-content white-text">
                            <p>{{ Session::get('success') }} </p>
                        </div>
                        <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                @elseif (Session::has('reopen'))
                    <div class="card-alert card gradient-45deg-light-blue-cyan">
                        <div class="card-content white-text">
                            <p>{{ Session::get('reopen') }} </p>
                        </div>
                        <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                @endif
                <div class="section">
                    <div class="col s12 m6 ">
                        <h4 class="header valign-wrapper">Réclamation Detail

                        </h4>
                        <ul id="task-card" class="collection with-header">
                            <li class="collection-header " style="background-color: #1991ce">
                                <h5 class="task-card-title mb-3">Réclamation N° {{$record->code}}

                                    @if($record->statut != 3)
                                    {{-- <a class="btn" href="{{route('cancel_reclamation', $record->id)}}"
                                       style="background-color: transparent; border: 1px solid red;color: red;margin-left:10px;box-shadow: none;"><i
                                            class="material-icons right">cancel</i>Annuler</a> --}}

                                            <a class="right tooltipped btn-floating waves-effect waves-light modal-trigger red accent-2" href="#annuler_modal"  data-position="bottom" data-tooltip="Annuler l'expédition">
                                                <i class="material-icons">clear</i>
                                              </a>


                                @endif

                            </h5>

                                <p class="task-card-date">{{$record->created_at}}</p>
                            </li>
                            <li class="collection-item dismissable">

                                <span class="width-100"
                                      style="text-decoration: none;">Client :  {{$record->userDetail->ClientDetail->libelle}}</span>
                            </li>
                            @if($record->userDetail->ClientDetail->commercial != null)
                            <li class="collection-item dismissable">

                                <span class="width-100" style="text-decoration: none;">Commerciale :  {{$record->userDetail->ClientDetail->ComercialDetail->libelle}}</span>
                            </li>
                            @endif
                            <li class="collection-item dismissable">

                                <span class="width-100"
                                      style="text-decoration: none;">Statut :
                                     @if($record->statut == 1)
                                        <span class="badge green">
                                        @elseif($record->statut == 2)
                                                <span class="badge orange">
                                        @elseif($record->statut == 3)
                                                        <span class="badge red">
                                     @endif


                                                            {{$record->getStatut()}}</span> </span>
                            </li>
                            @if($record->cloture_par != Null)
                                <li class="collection-item dismissable">

                                <span class="width-100"
                                      style="text-decoration: none;">Clôturée par :  {{$record->ClotureeParDetail->libelle}}</span>
                                </li>
                            @endif
                            @if($record->cloture_at != Null)
                                <li class="collection-item dismissable">

                                <span class="width-100"
                                      style="text-decoration: none;">Date clôturée :  {{$record->cloture_at}}</span>
                                </li>
                            @endif
                        </ul>

                        <div class="card gradient-45deg-orange-deep-orange gradient-shadow">
                            <div class="card-content black-text">
                                <h5 class="valign-wrapper mt-0 mb-4"><i
                                        class="material-icons orange-text mr-1">error</i>{{$record->typereclamationDetail->libelle}}
                                </h5>
                                <p>
                                    {{$record->description}}
                                </p>
                            </div>

                            <div class="card-action center-align">
                                @if($record->statut == 1)
                                    <a href="{{route('cloturer_reclamation', $record->id)}}"
                                       class="waves-effect waves-light btn gradient-45deg-deep-orange-orange">Clôturer
                                        la réclamation</a>
                                @elseif($record->statut == 2)
                                    <h5 class="brown-text"><i class="material-icons">verified_user</i> Cette reclamation est bien Clôturée</h5>
                                @elseif($record->statut == 3)
                                    <h5 class="red-text"><i class="material-icons">cancel</i> Cette reclamation est bien Annuler </h5>
                                @endif
                            </div>

                        </div>
                    </div>

                    <div class="col s12 m6 animate fadeUp">
                        <h4 class="header">Conversation</h4>
                        @if(!$messagesRecords->isEmpty())


                            <div class="msger-chat">
                                @foreach($messagesRecords as $message)
                                    @if($message->userDetail->role == '3')
                                        <div class="msg left-msg">
                                            {{--                                <div--}}
                                            {{--                                    class="msg-img"--}}
                                            {{--                                    style="background-image: url(https://image.flaticon.com/icons/svg/327/327779.svg)"--}}
                                            {{--                                ></div>--}}

                                            <div class="msg-bubble blue lighten-5">
                                                <div class="msg-info">
                                                    <div
                                                        class="msg-info-name">{{$message->userDetail->first_name}}</div>
                                                    <div class="msg-info-time">{{$message->created_at}}</div>
                                                </div>

                                                <div class="msg-text">
                                                    {{$message->description}}
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($message->userDetail->role == '1')
                                        <div class="msg right-msg">
                                            {{--                                <div--}}
                                            {{--                                    class="msg-img"--}}
                                            {{--                                    style="background-image: url(https://image.flaticon.com/icons/svg/145/145867.svg)"--}}
                                            {{--                                ></div>--}}

                                            <div class="msg-bubble gradient-45deg-indigo-blue">
                                                <div class="msg-info">
                                                    <div
                                                        class="msg-info-name">{{$message->userDetail->first_name}}</div>
                                                    <div class="msg-info-time">{{$message->created_at}}</div>
                                                </div>

                                                <div class="msg-text">
                                                    {{$message->description}}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                        @endif
                        <div id="prefixes" class="card card card-default scrollspy">
                            <div class="card-content">
                                @if($record->statut == 1)
                                    <form method="POST" action="{{route('reclamation_message')}}">
                                        @csrf
                                        <input type="text" name="reclamation_id" value="{{$record->id}}" hidden>
                                        <div class="row">
                                            <div class="input-field col s12">
                                                <i class="material-icons prefix">question_answer</i>
                                                <textarea id="message3" name="message"
                                                          class="materialize-textarea"></textarea>
                                                <label for="message3" class="">Message</label>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <button
                                                        class="btn gradient-45deg-indigo-light-blue waves-effect waves-light right"
                                                        type="submit"
                                                        name="action">Envoyer
                                                        <i class="material-icons right">send</i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @else
                                    <div class="center-align">
                                        <a href="{{route('reopen_reclamation', $record->id)}}"
                                           class="waves-effect waves-light btn gradient-45deg-light-blue-cyan">Rouvrir
                                            la reclamation</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <div class="col s12" style="margin-bottom:30px; ">
            <div class="container">
                <div class="section">
                    <div class="col s12">
                        <a href="#" id="showhistory">Voir l'historique ↓</a>
                        <table id="par" class="striped" hidden>
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Motif</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($historyRecords as $history)
                                <tr>
                                    <td>{{$history->userDetail->name}} {{$history->userDetail->first_name}}</td>
                                    <td>{{$history->created_at}}</td>
                                    <td>{{$history->getStatut($history->statut)}}</td>
                                    <td>{{$history->motif}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="annuler_modal" class="modal ">
        <div class="modal-content">
            <h4>Confirmation d'annulation</h4>
            <div>
                Êtes-vous sûr de vouloir l'expédition ?
            </div>

        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn grey">Fermer</a>
            <a  class="waves-effect waves-green btn red" href="{{route('cancel_reclamation', $record->id)}}">Annuler</a>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
@stop
@section('js')


    <script>
 $(document).ready(function() {
            $('.modal').modal();
        });
        $(document).ready(function () {
            $('#showhistory').on('click', function () {
                if ($('#par').is(':visible')) {
                    $(this).text('Voir l\'historique ↓');
                    $('#par').hide();
                } else {
                    $(this).text("Cacher l'historique ↑");
                    $('#par').show();
                }
            });
        });
    </script>
@stop
