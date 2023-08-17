@extends($layout)
<link href='/assets/js/lib/main.css' rel='stylesheet'/>
<script src='https://github.com/mozilla-comm/ical.js/releases/download/v1.4.0/ical.js'></script>
<script src='/assets/js/lib/main.js'></script>
<script src='../packages/icalendar/main.global.js'></script>
<script src='/assets/js/lib/locales/fr.js'></script>
<script>

    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var data = {!! $data !!}
        var calendar = new FullCalendar.Calendar(calendarEl, {
            displayEventTime: false,
            initialDate: Date.now(),
            locale: 'fr',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listYear'
            },
            events: data,
        });

        calendar.render();
    });

</script>
<style>

    body {
        margin: 0;
        padding: 0;
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 14px;
    }

    .fc-daygrid-day a {
        color: black;
    }

    #script-warning {
        display: none;
        background: #eee;
        border-bottom: 1px solid #ddd;
        padding: 0 10px;
        line-height: 40px;
        text-align: center;
        font-weight: bold;
        font-size: 12px;
        color: red;
    }

    #loading {
        display: none;
        position: absolute;
        top: 10px;
        right: 10px;
    }

    #calendar {
        max-width: auto;
        margin: 40px auto;
        padding: 30px;
    }

    #app-calendar .card{
        margin: auto;
    }

</style>

@section('content')

    <body>

    <div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
         style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title mt-0 mb-0 "><span>Calandrier</span></h5>
                </div>
                <div class="col s12 m6 l6 right-align-md">
                    <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('user_calendar')}}">Calandrier</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div id='script-warning'>
        <code>ics/feed.ics</code> must be servable
    </div>

    <div id='loading'>loading...</div>
    <div class="container">
        <div id="app-calendar">
            <div class="row">
                <div class="col s12">
                    <div class="card">
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    </body>


@stop
