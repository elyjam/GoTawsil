<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::any('/m/login', 'MapiController@login')->name('mobile_login')->middleware('cors');
Route::any('/m/expeditions', 'MapiController@expeditions')->name('mobile_expeditions')->middleware('cors')->middleware('check-token');
Route::any('/m/exp_delete', 'MapiController@exp_delete_client')->name('exp_delete_client')->middleware('cors')->middleware('check-token');
Route::any('/m/encaissements', 'MapiController@encaissements')->name('mobile_encaissements')->middleware('cors')->middleware('check-token');
Route::any('/m/commentaires', 'MapiController@commentaires')->name('mobile_commentaires')->middleware('cors')->middleware('check-token');
Route::any('/m/transporteurs', 'MapiController@transporteurs')->name('mobile_transporteurs')->middleware('cors')->middleware('check-token');
Route::any('/m/livreurs', 'MapiController@livreurs')->name('mobile_livreurs')->middleware('cors')->middleware('check-token');
Route::any('/m/villes', 'MapiController@villes')->name('mobile_villes')->middleware('cors')->middleware('check-token');
Route::any('/m/villesChargement', 'MapiController@villesChargement')->name('mobile_villesChargement')->middleware('cors')->middleware('check-token');
Route::any('/m/changeStatus', 'MapiController@changeStatus')->name('mobile_changeStatus')->middleware('cors')->middleware('check-token');
Route::any('/m/expeditionDetail', 'MapiController@expeditionDetail')->name('mobile_expeditionDetail')->middleware('cors')->middleware('check-token');
Route::any('/m/expeditionDetail_affectation', 'MapiController@expeditionDetail_affectation')->name('mobile_expeditionDetail_affectation')->middleware('cors')->middleware('check-token');
Route::any('/m/expeditionDetail_ramassage', 'MapiController@expeditionDetail_ramassage')->name('mobile_expeditionDetail_ramassage')->middleware('cors')->middleware('check-token');
Route::any('/m/expeditionDetail_chargement', 'MapiController@expeditionDetail_chargement')->name('mobile_expeditionDetail_chargement')->middleware('cors')->middleware('check-token');
Route::any('/m/chargements', 'MapiController@chargements')->name('mobile_chargements')->middleware('cors')->middleware('check-token');
Route::any('/m/affectations', 'MapiController@affectations')->name('mobile_affectations')->middleware('cors')->middleware('check-token');
Route::any('/m/arrivages', 'MapiController@arrivages')->name('mobile_affectations')->middleware('cors')->middleware('check-token');
Route::any('/m/ramassages', 'MapiController@ramassages')->name('mobile_ramassages')->middleware('cors')->middleware('check-token');
Route::any('/m/demmanderamassage', 'MapiController@demandeRamClient')->name('demmande_ramassage_mobile')->middleware('cors')->middleware('check-token');
Route::any('/m/reclamationsClient', 'MapiController@reclamationsClient')->name('reclamationsClient')->middleware('cors')->middleware('check-token');
Route::any('/m/reclamationsTypes', 'MapiController@getreclamationTypes')->name('get_reclamation_Types')->middleware('cors')->middleware('check-token');
Route::any('/m/createReclamation', 'MapiController@createReclamation')->name('create_Reclamation')->middleware('cors')->middleware('check-token');
Route::any('/m/getReclaMessages', 'MapiController@getReclaMessages')->name('get_Recla_Messages')->middleware('cors')->middleware('check-token');
Route::any('/m/addMessage', 'MapiController@addMessage')->name('addMessage_reclamation')->middleware('cors')->middleware('check-token');
Route::any('/m/deleteReclamation', 'MapiController@deleteReclamation')->name('delete_Reclamation')->middleware('cors')->middleware('check-token');


Route::any('/m/reclamations', 'MapiController@reclamations')->name('mobile_reclamations')->middleware('cors')->middleware('check-token');
Route::any('/m/list_ramassages', 'MapiController@listRamassages')->name('mobile_list_ramassages')->middleware('cors')->middleware('check-token');
Route::any('/m/remboursements', 'MapiController@remboursements')->name('mobile_remboursements')->middleware('cors')->middleware('check-token');
Route::any('/m/factures', 'MapiController@factures')->name('mobile_factures')->middleware('cors')->middleware('check-token');
Route::any('/m/home-client', 'MapiController@homeClient')->name('mobile_homeClient')->middleware('cors')->middleware('check-token');
Route::any('/m/saisier-expedition', 'MapiController@saisierExpedition')->name('saisier_expedition')->middleware('cors')->middleware('check-token');
Route::any('/m/user-permession', 'MapiController@userExPermession')->name('userEx_Permession')->middleware('cors')->middleware('check-token');
Route::any('/m/get-expedition-livree', 'MapiController@getExpeditionLivree')->name('userEx_Permession')->middleware('cors')->middleware('check-token');
Route::any('/m/reclamation-Client-Notification', 'MapiController@reclamationClientNotification')->name('reclamationClientNotification')->middleware('cors')->middleware('check-token');
Route::any('/m/get-reclamation-client', 'MapiController@getReclamationClient')->name('getReclamationClient')->middleware('cors')->middleware('check-token');


