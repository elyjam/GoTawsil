@extends($layout)

@section('content')
<div id="breadcrumbs-wrapper" data-image="/assets/images/gallery/breadcrumb-bg.jpg" class="breadcrumbs-bg-image"
    style="background-image: url(/assets/images/gallery/breadcrumb-bg.jpg&quot;);">
    <!-- Search for small screen-->
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <h5 class="breadcrumbs-title mt-0 mb-0"><span>Retours BL > Liste des bordereaux</span></h5>
            </div>

        </div>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <div class="container">
            <div class="section">
                <form method="POST" action="{{route('bordereau_create')}}">
                    @csrf
                    <br>
                    <div class="card">
                        <div class="card-panel">
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="row">

                                        <div class="col s4 input-field">
                                            <input id="selection_date" value="{{old('selection_date')}}"
                                                name="selection_date" type="text" placeholder="" class="datepicker">
                                            <label for="selection_date">Date sélection </label>
                                            @error('selection_date')
                                            <span class="helper-text materialize-red-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col s4 input-field">
                                            <a href="{{route('remboursement_list')}}"><button type="button"
                                                    class="btn btn-light">Générer </button></a>
                                            <button type="submit" class="btn indigo" style="margin-left: 1rem;">
                                                Réinitialiser</button>
                                        </div>

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