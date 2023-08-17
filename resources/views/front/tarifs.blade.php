@extends('layouts/front')


@section('content')
<style>
    .table-fill {
        max-width: 100% !important;
    }
</style>
    <main>
        <div class="content-header ">
            <center>
                <img src="/assets/front/tarif.jpg" height="350px" class="mx-auto " alt="">
                <h1 class="text-center" style="">VILLES DE LIVRAISON & TARIF</h1>
            </center>
        </div>

        <section class="tarifs container py-5">
            <center>


                <select class="form-select mb-3" name='id_ville' id='id_ville' style="width: 400px;"
                    aria-label="Default select example">
                    <option selected>Choisir votre ville de départ.</option>

                    @foreach ($villeDepart as $row)
                        <option class='option' value='{{ $row->id }}'> {{ $row->libelle }}</option>
                    @endforeach
                </select>


                <select class="form-select mb-3" name='id_ville_dest' id='id_ville_dest' style="width: 400px;"
                    aria-label="Default select example">

                    <option value="0">Toutes les destinations</option>
                    @foreach ($villeRecords as $row)
                        <option class='option' value='{{ $row->id }}'> {{ $row->libelle }}</option>
                    @endforeach
                </select>

                <a type="button" id="chercher" style="width: 400px;" class="btn btn-primary ">Chercher</a>

                <div id="datax">

                </div>
            </center>

        </section>
    </main>
@stop
@section('js')
    <script>
        $(document).ready(function() {

            $('#chercher').click(function() {
                let villeExp = $("#id_ville").val();
                let villeDest = $("#id_ville_dest").val();
                $.ajax({
                    url: '/tarifs/list',
                    type: 'get',
                    data: {
                        'villeExp': villeExp,
                        'villeDest': villeDest
                    },
                    success: function(result) {
                        console.log(result)
                        $('#datax').html(result)
                    }
                });


            });
        });
    </script>
@stop
