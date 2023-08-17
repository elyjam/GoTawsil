@extends('layouts/front')
@section('content')


</div>
<!-- start the main page -->

<main class="maincontainer">

    <div class="container sousContainer">
        <div class="iconContainer">
            <img src="/assets/front/register.jpg" alt="">
        </div>
        <h2>INSCRIPTION - COMMENCEZ AVEC GO TAWSILL</h2>
        <form>
            <div class="row">
                <div class="row vousEtes">

                    <label for="" style="font-weight:bold">Vous êtes</label>
                    <div class="col col-lg-6 col-sm-12 mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="Professionnel"
                                onclick="javascript:personneCheck()">
                            <label class="form-check-label" for="flexRadioDefault1">
                                Professionnel / Société
                            </label>
                        </div>
                    </div>
                    <div class="col col-lg-6 col-sm-12 mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="Personne"
                                onclick="personneCheck()">
                            <label class="form-check-label" for="flexRadioDefault1">
                                Personne physique
                            </label>
                        </div>
                    </div>

                </div>
                <div class="row typeclient">

                    <label for="" style="font-weight:bold">Type Client</label>
                    <div class="col col-lg-6 col-sm-12 mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                            <label class="form-check-label" for="flexRadioDefault1">
                                Contre paiement (Cash Delivery)
                            </label>
                        </div>
                    </div>
                    <div class="col col-lg-6 col-sm-12 mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                            <label class="form-check-label" for="flexRadioDefault1">
                                Contre document
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row mainform">

                    <div id="Raison" class="col col-lg-6 col-sm-12 mt-3">
                        <label class="form-label">Raison sociale</label>
                        <input type="text" class="form-control" placeholder="" name="Raisonsociale ">
                    </div>
                    <div id="NomPrenom" class="col col-lg-6 col-sm-12 mt-3" style="display: none;">
                        <label class="form-label">Nom et Prénom </label>
                        <input type="text" class="form-control" placeholder="" name="NomPrenom">
                    </div>
                    <div class="col col-lg-6 col-sm-12  mt-3">
                        <label class="form-label">E-mail</label>
                        <input type="email" class="form-control" placeholder="Un e-mail valide" name="email">
                    </div>
                    <div class="col col-sm-12 mt-3">
                        <label class="form-label">Adresse</label>
                        <input type="text" class="form-control" placeholder="" name="adresse">
                    </div>
                    <div class="col col-lg-6 col-sm-12 mt-3">
                        <label class="form-label">Ville</label>
                        <select name="ville" class="form-control">
                            <option>1</option>
                            <option>2</option>
                            <option>2</option>
                            <option>2</option>
                        </select>
                    </div>
                    <div class="col col-lg-6 col-sm-12 mt-3">
                        <label class="form-label">Zone</label>

                        <select name="zone" class="form-control">
                            <option>1</option>
                            <option>2</option>
                        </select>
                    </div>
                    <div class="col col-lg-6 col-sm-12 mt-3">
                        <label class="form-label">Téléphone 1</label>
                        <input type="text" class="form-control" placeholder="0xxxxxxxxx" name="telephone1 ">
                    </div>
                    <div class="col col-lg-6 col-sm-12 mt-3">
                        <label class="form-label">Téléphone 2</label>
                        <input type="text" class="form-control" placeholder="0xxxxxxxxx" name="telephone2">
                    </div>

                    <div class="col col-lg-6 col-sm-12 mt-3" id="RC">
                        <label class="form-label">RC</label>
                        <input type="text" class="form-control" placeholder="6 Caractères " name="rc ">
                    </div>

                    <div class="col col-lg-6 col-sm-12 mt-3" id="ICE">
                        <label class="form-label">ICE</label>
                        <input type="text" class="form-control" placeholder="15 Chiffres " name="ice">
                    </div>
                    <div class="col col-lg-6 col-sm-12 mt-3" id="CIN" style="display: none;">
                        <label class="form-label">CIN</label>
                        <input type="text" class="form-control" placeholder="CIN" name="cin">
                    </div>
                    <div class="col col-sm-12 mt-3">
                        <label class="form-label">RIB</label>
                        <input type="text" class="form-control" placeholder="24 Chiffres" name="rib">
                    </div>

                    <div class="col col-lg-6 col-sm-12 mt-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" class="form-control"
                            placeholder="Au moins 6 caractères (chiffres et Lettres)" name="motDePasse">
                    </div>
                    <div class="col col-lg-6 col-sm-12 mt-3 mb-3">
                        <label class="form-label">Confirmation mot de passe</label>
                        <input type="password" class="form-control" placeholder="Confirmation mot de passe"
                            name="ConfMotDePasse">
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            j'ai lu et j'accepte les <a href='/conditions-utilisation'>conditions d'utilisation </a>.
                        </label>
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                        <label class="form-check-label" for="flexCheckChecked">
                            Je certifie sur l'honneur que les informations communiquées ci-dessus sont exactes.
                        </label>
                    </div>
                    <div class="col col-lg-6 col-sm-12 mt-3 ">
                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</main>
@stop
