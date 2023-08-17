<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/confirm/{id}/{token}', 'Auth\RegisterController@confirm')->name('confirm_register');
Route::get('/getfile/{token}/{type}/{id}', 'HomeController@getFile')->name('getFile');

Route::any('/', 'HomeController@index')->name('home');
Route::any('/conditions-utilisation', 'HomeController@conditionsUtilisation')->name('conditions-utilisation');
Route::any('/tarifs', 'HomeController@tarifs')->name('tarifs');
Route::any('/tarifs/list', 'HomeController@tarif_list')->name('tarifs_list');
Route::get('/search', 'HomeController@search_exp')->name('search_exp');
Route::any('/successfull', 'HomeController@successfull')->name('successfull');
Route::any('/ChartsLoad', 'HomeController@chart_data')->name('chart_data');

Route::any('/forget-password', 'forgetPasswordController@forgetPassword')->name('forget_password');
Route::any('/reset-password/{token}', 'forgetPasswordController@getPassword')->name('get_password');
Route::any('/recovery-message', 'forgetPasswordController@recoveryMessage')->name('recoveryMessage');

Route::any('/dashboard', 'AdminController@index')->name('admin')->middleware('check-perm');


Route::any('/taux_livraison_filre', 'AdminController@taux_livraison_filre')->name('taux_livraison_filre')->middleware('check-perm');
Route::any('/Dashboard_Pilotage', 'AdminController@pilotage')->name('Dashboard_Pilotage')->middleware('check-perm');
Route::any('/Dashboard_Pilotage_Livreur', 'AdminController@pilotageLivreur')->name('Dashboard_Pilotage_Livreur')->middleware('check-perm');
Route::any('/Dashboard_Commercial', 'AdminController@commercial')->name('Dashboard_Commercial')->middleware('check-perm');
Route::any('/Dashboard_Client', 'AdminController@dashboard_client')->name('Dashboard_Client');

Route::any('/Souffrance/chargement', 'AdminController@pdf_souf_chargement')->name('Souffrance_chargement')->middleware('check-perm');
Route::any('/Souffrance/arrivage', 'AdminController@pdf_souf_arrivage')->name('Souffrance_arrivage')->middleware('check-perm');
Route::any('/Souffrance/livraison', 'AdminController@pdf_souf_livraison')->name('Souffrance_livraison')->middleware('check-perm');
Route::any('/Souffrance/ramassage', 'AdminController@pdf_souf_ramassage')->name('Souffrance_ramassage')->middleware('check-perm');
Route::any('/pdf/caisses/nonvalidees', 'AdminController@pdf_caisses_nonvalide')->name('pdf_caisses_nonvalide')->middleware('check-perm');
Route::any('/pdf/expeditions/nonremb', 'AdminController@pdf_exp_nonremb')->name('pdf_exp_nonremb')->middleware('check-perm');

Route::any('/pdf/suvi_parville', 'AdminController@suvi_parville')->name('suvi_parville')->middleware('check-perm');

Route::any('/user/calendar', 'CalendarController@list')->name('user_calendar')->middleware('check-perm');
Route::any('/user/list', 'UserController@list')->name('user_list')->middleware('check-perm');
Route::any('/user/api', 'UserController@userApi')->name('user_Api')->middleware('check-perm');
Route::any('/user/create', 'UserController@create')->name('user_create')->middleware('check-perm');
Route::any('/user/update/{user}', 'UserController@update')->name('user_update')->middleware('check-perm');
Route::any('/user/delete/{user}', 'UserController@delete')->name('user_delete')->middleware('check-perm');
Route::any('/user/profil', 'UserController@myProfil')->name('user_profil')->middleware('check-perm');
Route::any('/client/profil', 'UserController@myProfil_client')->name('user_profil_client')->middleware('check-perm');
// autre parametrage
Route::any('/autre_parametrage', 'AutreparametrageController@list')->name('autre_parametrage')->middleware('check-perm');
// ville
Route::any('/ville/list', 'VilleController@list')->name('ville_list')->middleware('check-perm');
Route::any('/ville/create', 'VilleController@create')->name('ville_create')->middleware('check-perm');
Route::any('/ville/update/{ville}', 'VilleController@update')->name('ville_update')->middleware('check-perm');
Route::any('/ville/delete/{ville}', 'VilleController@delete')->name('ville_delete')->middleware('check-perm');
// transporteur
Route::any('/transporteur/list', 'TransporteurController@list')->name('transporteur_list')->middleware('check-perm');
Route::any('/transporteur/create', 'TransporteurController@create')->name('transporteur_create')->middleware('check-perm');
Route::any('/transporteur/update/{transporteur}', 'TransporteurController@update')->name('transporteur_update')->middleware('check-perm');
Route::any('/transporteur/delete/{transporteur}', 'TransporteurController@delete')->name('transporteur_delete')->middleware('check-perm');
// promotion
Route::any('/promotion/list', 'PromotionController@list')->name('promotion_list')->middleware('check-perm');
Route::any('/promotion/create', 'PromotionController@create')->name('promotion_create')->middleware('check-perm');
Route::any('/promotion/seen/{id}', 'PromotionController@promotion_seen')->name('promotion_seen')->middleware('check-perm');
Route::any('/promotion/update/{promotion}', 'PromotionController@update')->name('promotion_update')->middleware('check-perm');
Route::any('/promotion/delete/{promotion}', 'PromotionController@delete')->name('promotion_delete')->middleware('check-perm');
// types_commentaire
Route::any('/types_commentaire/list', 'Types_commentaireController@list')->name('types_commentaire_list')->middleware('check-perm');
Route::any('/types_commentaire/create', 'Types_commentaireController@create')->name('types_commentaire_create')->middleware('check-perm');
Route::any('/types_commentaire/update/{types_commentaire}', 'Types_commentaireController@update')->name('types_commentaire_update')->middleware('check-perm');
Route::any('/types_commentaire/delete/{types_commentaire}', 'Types_commentaireController@delete')->name('types_commentaire_delete')->middleware('check-perm');
// role
Route::any('/role/list', 'RoleController@list')->name('role_list')->middleware('check-perm');
Route::any('/role/create', 'RoleController@create')->name('role_create')->middleware('check-perm');
Route::any('/role/update/{role}', 'RoleController@update')->name('role_update')->middleware('check-perm');
Route::any('/role/delete/{role}', 'RoleController@delete')->name('role_delete')->middleware('check-perm');
// droit
Route::any('/droit/list', 'DroitController@list')->name('droit_list')->middleware('check-perm');
Route::any('/droit/create', 'DroitController@create')->name('droit_create')->middleware('check-perm');
Route::any('/droit/update/{droit}', 'DroitController@update')->name('droit_update')->middleware('check-perm');
Route::any('/droit/delete/{droit}', 'DroitController@delete')->name('droit_delete')->middleware('check-perm');
// fonction
Route::any('/fonction/list', 'FonctionController@list')->name('fonction_list')->middleware('check-perm');
Route::any('/fonction/create', 'FonctionController@create')->name('fonction_create')->middleware('check-perm');
Route::any('/fonction/update/{fonction}', 'FonctionController@update')->name('fonction_update')->middleware('check-perm');
Route::any('/fonction/delete/{fonction}', 'FonctionController@delete')->name('fonction_delete')->middleware('check-perm');
// typesemploye
Route::any('/typesemploye/list', 'TypesemployeController@list')->name('typesemploye_list')->middleware('check-perm');
Route::any('/typesemploye/create', 'TypesemployeController@create')->name('typesemploye_create')->middleware('check-perm');
Route::any('/typesemploye/update/{typesemploye}', 'TypesemployeController@update')->name('typesemploye_update')->middleware('check-perm');
Route::any('/typesemploye/delete/{typesemploye}', 'TypesemployeController@delete')->name('typesemploye_delete')->middleware('check-perm');
// employe
Route::any('/employe/list', 'EmployeController@list')->name('employe_list')->middleware('check-perm');
Route::any('/employe/create', 'EmployeController@create')->name('employe_create')->middleware('check-perm');
Route::any('/employe/update/{employe}', 'EmployeController@update')->name('employe_update')->middleware('check-perm');
Route::any('/employe/delete/{employe}', 'EmployeController@delete')->name('employe_delete')->middleware('check-perm');
Route::any('/employe/affectlivaison/{employe?}', 'EmployeController@affectLivaison')->name('employe_affectLivaison')->middleware('check-perm');
// agence
Route::any('/agence/list', 'AgenceController@list')->name('agence_list')->middleware('check-perm');
Route::any('/agence/create', 'AgenceController@create')->name('agence_create')->middleware('check-perm');
Route::any('/agence/update/{agence}', 'AgenceController@update')->name('agence_update')->middleware('check-perm');
Route::any('/agence/delete/{agence}', 'AgenceController@delete')->name('agence_delete')->middleware('check-perm');
Route::any('/agence/affectlivaison/{agence?}', 'AgenceController@affectLivaison')->name('agence_affectLivaison')->middleware('check-perm');
// banque
Route::any('/banque/list', 'BanqueController@list')->name('banque_list')->middleware('check-perm');
Route::any('/banque/create', 'BanqueController@create')->name('banque_create')->middleware('check-perm');
Route::any('/banque/update/{banque}', 'BanqueController@update')->name('banque_update')->middleware('check-perm');
Route::any('/banque/delete/{banque}', 'BanqueController@delete')->name('banque_delete')->middleware('check-perm');
// categoriesclient
Route::any('/categoriesclient/list', 'CategoriesclientController@list')->name('categoriesclient_list')->middleware('check-perm');
Route::any('/categoriesclient/create', 'CategoriesclientController@create')->name('categoriesclient_create')->middleware('check-perm');
Route::any('/categoriesclient/update/{categoriesclient}', 'CategoriesclientController@update')->name('categoriesclient_update')->middleware('check-perm');
Route::any('/categoriesclient/delete/{categoriesclient}', 'CategoriesclientController@delete')->name('categoriesclient_delete')->middleware('check-perm');
// client
Route::any('/client/list', 'ClientController@list')->name('client_list')->middleware('check-perm');
Route::any('/client/api', 'ClientController@apiClient')->name('api_Client')->middleware('check-perm');
Route::any('/client/new', 'ClientController@new')->name('client_new')->middleware('check-perm');
Route::any('/client/create', 'ClientController@create')->name('client_create')->middleware('check-perm');
Route::any('/client/update/{client}', 'ClientController@update')->name('client_update')->middleware('check-perm');
Route::any('/client/delete/{client}', 'ClientController@delete')->name('client_delete')->middleware('check-perm');
Route::any('/client/remove/{client}', 'ClientController@remove')->name('client_remove')->middleware('check-perm');
Route::any('/client/activate/{user}', 'ClientController@activate')->name('client_activate')->middleware('check-perm');
Route::any('/client/validate/{user}', 'ClientController@validat')->name('client_validate')->middleware('check-perm');
Route::any('/client/print/{client}', 'ClientController@print')->name('client_print')->middleware('check-perm');
Route::any('/client/print_forcer/{client}', 'ClientController@print_forcer')->name('print_forcer')->middleware('check-perm');

//ancien
Route::any('/Historique/Expeditions', 'ClientController@ancien_exps')->name('ancien_exps')->middleware('check-perm');
Route::any('/Historique/Bons', 'ClientController@ancien_bons')->name('ancien_bons')->middleware('check-perm');
Route::any('/Historique/Factures', 'ClientController@ancien_factures')->name('ancien_factures')->middleware('check-perm');
Route::any('/Historique/Remboursements', 'ClientController@ancien_remboursements')->name('ancien_remboursements')->middleware('check-perm');
Route::any('/Historique/expedition/pdf/{expedition}', 'ClientController@ancien_exp_pdf')->name('ancien_exp_pdf')->middleware('check-perm');
Route::any('/Historique/bon/print-detail/{bon}', 'ClientController@ancien_bon_pdf')->name('ancien_bon_pdf')->middleware('check-perm');
Route::any('/Historique/bon/pdf/{bon}', 'ClientController@ancien_bon_exp')->name('ancien_bon_exp')->middleware('check-perm');
Route::any('/Historique/facture/print/{facture}/{type}', 'ClientController@ancien_facture_print')->name('ancien_facture_print')->middleware('check-perm');
Route::any('/Historique/facture/print-detail/{facture}', 'ClientController@printDetail_ancien_facture')->name('printDetail_ancien_facture')->middleware('check-perm');
Route::any('/Historique/remboursement/print-detail/{remboursement}/{paiement?}', 'ClientController@print_renboursement_ancien')->name('print_renboursement_ancien')->middleware('check-perm');
// typereclamation
Route::any('/typereclamation/list', 'TypereclamationController@list')->name('typereclamation_list')->middleware('check-perm');
Route::any('/typereclamation/create', 'TypereclamationController@create')->name('typereclamation_create')->middleware('check-perm');
Route::any('/typereclamation/update/{typereclamation}', 'TypereclamationController@update')->name('typereclamation_update')->middleware('check-perm');
Route::any('/typereclamation/delete/{typereclamation}', 'TypereclamationController@delete')->name('typereclamation_delete')->middleware('check-perm');
// facture
Route::any('/facture/list', 'FactureController@list')->name('facture_list')->middleware('check-perm');
Route::any('/facture/Mylist', 'FactureController@list_client')->name('facture_list_client')->middleware('check-perm');
Route::any('/facture/encompte/{type?}', 'FactureController@list')->name('facture_encompte')->middleware('check-perm');
Route::any('/facture/remboursement/{type?}', 'FactureController@list')->name('facture_remboursement')->middleware('check-perm');
Route::any('/facture/create', 'FactureController@create')->name('facture_create')->middleware('check-perm');
Route::any('/facture/update/{facture}', 'FactureController@update')->name('facture_update')->middleware('check-perm');
Route::any('/facture/delete/{facture}', 'FactureController@delete')->name('facture_delete')->middleware('check-perm');
Route::any('/facture/print/{facture}/{type}', 'FactureController@print')->name('facture_print')->middleware('check-perm')->middleware('pdfClient');
Route::any('/facture/print-detail/{facture}', 'FactureController@printDetail')->name('facture_print_detail')->middleware('check-perm')->middleware('pdfClient');
Route::any('/facture/gen-fact/{type?}', 'FactureController@genFact')->name('facture_gen')->middleware('check-perm');
Route::any('/facture/gen-rem-fact', 'FactureController@genRemFac')->name('facture_rem_gen')->middleware('check-perm');
Route::any('/facture/detail/{facture}', 'FactureController@detail')->name('facture_detail')->middleware('check-perm');
// expedition
Route::any('/expedition/list', 'ExpeditionController@list')->name('expedition_list')->middleware('check-perm');
Route::any('/expedition/api', 'ExpeditionController@api')->name('expedition_api')->middleware('check-perm');
Route::any('/expedition/livraison', 'ExpeditionController@livraison')->name('expedition_livraison')->middleware('check-perm');
Route::any('/expedition/affectation/{type}/{livreur?}', 'ExpeditionController@affectation')->name('expedition_affec_liv')->middleware('check-perm');
Route::any('/expedition/affectation-retours/{type}/{livreur?}', 'ExpeditionController@affectation')->name('expedition_affec_retour')->middleware('check-perm');
Route::any('/expedition/create', 'ExpeditionController@create')->name('expedition_create')->middleware('check-perm');
Route::any('/expedition/detail/{expedition}', 'ExpeditionController@detail')->name('expedition_detail')->middleware('check-perm');
Route::any('/expedition/update/{expedition}', 'ExpeditionController@update')->name('expedition_update')->middleware('check-perm');
Route::any('/expedition/client/update/{expedition}', 'ExpeditionController@update_client')->name('expedition_update_client')->middleware('check-perm');
Route::any('/expedition/delete/{expedition}', 'ExpeditionController@delete')->name('expedition_delete')->middleware('check-perm');
Route::any('/expedition/pdf/{expedition}', 'ExpeditionController@pdf')->name('expedition_pdf')->middleware('check-perm')->middleware('pdfClient');
Route::any('/expedition/qrcode/{expedition}', 'ExpeditionController@qrcode')->name('expedition_qrcode')->middleware('check-perm');
Route::any('/expedition/export', 'ExpeditionController@export')->name('expedition_export')->middleware('check-perm');
Route::any('/expedition/insert', 'ExpeditionController@insert')->name('expedition_insert')->middleware('check-perm');
Route::any('/expedition/suivi', 'ExpeditionController@suiviCommercial')->name('suivi_commerce')->middleware('check-perm');
Route::any('/expedition/import', 'ExpeditionController@import')->name('expedition_import')->middleware('check-perm');
Route::any('/expedition/data', 'ExpeditionController@expeditionEchange')->name('expeditionEchange')->middleware('check-perm');
Route::any('/expedition/change-status/{expedition?}/{status?}/{comment?}', 'ExpeditionController@changeStatus')->name('expedition_change-status')->middleware('check-perm');
Route::any('/expedition/import/print-detail/{bon}', 'ExpeditionController@printChargementDetail')->name('chargement_mass_print_detail')->middleware('check-perm');
Route::any('/expedition/getprixcolis', 'ExpeditionController@getPrixColis')->name('getPrixColis');
Route::any('/expedition/historyExpeditionShow', 'ExpeditionController@historyExpeditionShow')->name('historyExpeditionShow');
Route::any('/expedition/slider/{expedition}', 'ExpeditionController@slider')->name('expedition_slider');
Route::any('/expedition/map/{comment}', 'ExpeditionController@map')->name('expedition_map');
Route::any('/expedition/enechange', 'ExpeditionController@colisEnEchangeList')->name('colisEnEchangeList')->middleware('check-perm');

// cheque
Route::any('/cheque/list', 'ChequeController@list')->name('cheque_list')->middleware('check-perm');
Route::any('/cheque/create', 'ChequeController@create')->name('cheque_create')->middleware('check-perm');
Route::any('/cheque/update/{cheque}', 'ChequeController@update')->name('cheque_update')->middleware('check-perm');
Route::any('/cheque/delete/{cheque}', 'ChequeController@delete')->name('cheque_delete')->middleware('check-perm');
// reclamation
Route::any('/reclamation/list', 'ReclamationController@list')->name('reclamation_list')->middleware('check-perm');
Route::any('/reclamation/create', 'ReclamationController@create')->name('reclamation_create')->middleware('check-perm');
Route::any('/reclamation/detail/{reclamation}', 'ReclamationController@detail')->name('reclamation_detail')->middleware('check-perm');
Route::any('/reclamation/reclamationsuivi', 'ReclamationController@reclamation_message')->name('reclamation_message')->middleware('check-perm');
Route::any('/reclamation/delete/{reclamation}', 'ReclamationController@delete')->name('reclamation_delete')->middleware('check-perm');
Route::any('/reclamation/export', 'ReclamationController@export')->name('reclamation_export')->middleware('check-perm');
Route::any('/reclamation/rouvrir/{Reclamation}', 'ReclamationController@reopen_reclamation')->name('reopen_reclamation')->middleware('check-perm');

Route::any('/reclamation/cloturer/{Reclamation}', 'ReclamationController@cloturer_reclamation')->name('cloturer_reclamation')->middleware('check-perm');
Route::any('/reclamation/cancel/{Reclamation}', 'ReclamationController@cancel_reclamation')->name('cancel_reclamation')->middleware('check-perm');

// Ramassage
Route::any('/bon/list', 'BonController@list')->name('bon_list')->middleware('check-perm');
Route::any('/bon/api', 'BonController@bonApi')->name('bon_api')->middleware('check-perm');
Route::any('/bon/create', 'BonController@create')->name('bon_create')->middleware('check-perm');
Route::any('/bon/pdf/{bon}', 'BonController@pdf_bon')->name('pdf_bon')->middleware('check-perm')->middleware('pdfClient');
Route::any('/bon/update/{bon}', 'BonController@update')->name('bon_update')->middleware('check-perm');
Route::any('/bon/delete/{bon}', 'BonController@delete')->name('bon_delete')->middleware('check-perm');
Route::any('/bon/print-detail/{bon}', 'BonController@printDetail')->name('bon_print_detail')->middleware('check-perm')->middleware('pdfClient');

//list ramassage client
Route::any('/bon/list/ram', 'BonController@listClient')->name('bon_list_ram')->middleware('check-perm');
Route::any('/bon/demande', 'BonController@demandeRamClient')->name('demande_ram__client')->middleware('check-perm');

Route::any('/bon/modifier/{id}', 'BonController@modifBon')->name('modif_bon')->middleware('check-perm');
Route::any('/bon/modif/force/{id}', 'BonController@modifforce')->name('modif_force')->middleware('check-perm');
Route::any('/bon/force/insert/{id}', 'BonController@insertForce')->name('insert_force')->middleware('check-perm');
Route::any('/bon/insert/{id}', 'BonController@insertBon')->name('insert_bon')->middleware('check-perm');
Route::any('/bon/validate/{bon}', 'BonController@validateRam')->name('validate_ram')->middleware('check-perm');
Route::any('/bon/forcevalidate/{bon}', 'BonController@validateForceRam')->name('validate_force_ram')->middleware('check-perm');
Route::any('/bon/forcer/list', 'BonController@listFrocer')->name('forcer_list')->middleware('check-perm');
Route::any('/bon/ram/list', 'BonController@clientRmBon')->name('client_ram_bon')->middleware('check-perm');

// statut
Route::any('/statut/list', 'StatutController@list')->name('statut_list')->middleware('check-perm');
Route::any('/statut/create', 'StatutController@create')->name('statut_create')->middleware('check-perm');
Route::any('/statut/update/{statut}', 'StatutController@update')->name('statut_update')->middleware('check-perm');
Route::any('/statut/delete/{statut}', 'StatutController@delete')->name('statut_delete')->middleware('check-perm');
// remboursement
Route::any('/remboursement/list', 'RemboursementController@list')->name('remboursement_list')->middleware('check-perm');
Route::any('/remboursement/paiements/{remboursement}', 'RemboursementController@paiements')->name('remboursement_paiements')->middleware('check-perm');
Route::any('/remboursement/create', 'RemboursementController@create')->name('remboursement_create')->middleware('check-perm');
Route::any('/remboursement/update/{remboursement}', 'RemboursementController@update')->name('remboursement_update')->middleware('check-perm');
Route::any('/remboursement/delete/{remboursement}', 'RemboursementController@delete')->name('remboursement_delete')->middleware('check-perm');
Route::any('/remboursement/print-detail/{remboursement}/{paiement?}', 'RemboursementController@printDetail')->name('remboursement_print_detail')->middleware('check-perm')->middleware('pdfClient');
Route::any('/remboursement/ordre-virement/{remboursement}', 'RemboursementController@ordreVirement')->name('remboursement_ordre_virement')->middleware('check-perm');
// bordereau
Route::any('/bordereau/list', 'BordereauController@list')->name('bordereau_list')->middleware('check-perm');
Route::any('/bordereau/create', 'BordereauController@create')->name('bordereau_create')->middleware('check-perm');
Route::any('/bordereau/update/{bordereau}', 'BordereauController@update')->name('bordereau_update')->middleware('check-perm');
Route::any('/bordereau/delete/{bordereau}', 'BordereauController@delete')->name('bordereau_delete')->middleware('check-perm');
// caisse
Route::any('/caisse/list', 'CaisseController@list')->name('caisse_list')->middleware('check-perm');
Route::any('/caisse/globals', 'CaisseController@globals')->name('caisse_globals')->middleware('check-perm');
Route::any('/caisse/api', 'CaisseController@api')->name('caisse_api');
Route::any('/caisse/export', 'CaisseController@export')->name('caisse_export');
Route::any('/caisse/versements/{caisse}/{type?}/{rub?}', 'CaisseController@versements')->name('caisse_versements')->middleware('check-perm');
Route::any('/caisse/create', 'CaisseController@create')->name('caisse_create')->middleware('check-perm');
Route::any('/caisse/update/{caisse}', 'CaisseController@update')->name('caisse_update')->middleware('check-perm');
Route::any('/caisse/delete/{caisse}', 'CaisseController@delete')->name('caisse_delete')->middleware('check-perm');
Route::any('/caisse/print/{caisse}', 'CaisseController@print')->name('caisse_print')->middleware('check-perm');
Route::any('/caisse/print-detail/{caisse}', 'CaisseController@printDetail')->name('caisse_print_detail')->middleware('check-perm');
Route::any('/caisse/change-status/{caisse?}/{status?}', 'CaisseController@changeStatus')->name('caisse_change-status')->middleware('check-perm');

// Chargement colis list
Route::any('/chargement/list', 'ChargementColisController@list')->name('chargement_list')->middleware('check-perm');
//chargement des colis ramasse
Route::any('/chargement/create', 'ChargementColisController@create')->name('chargement_create')->middleware('check-perm');
Route::any('/chargement/ville/list', 'ChargementColisController@afficheVille')->name('afficher_list')->middleware('check-perm');
Route::any('/chargement/feuille', 'ChargementColisController@feuilleList')->name('chargement_feuille')->middleware('check-perm');
Route::any('/chargement/print-detail/{chargement}', 'ChargementColisController@printDetail')->name('chargement_print_detail')->middleware('check-perm');


Route::any('/new-subscribers-count', 'Ws\NotificationController@newSubscribersCount')->name('new_subscribers_list')->middleware('check-perm');
Route::any('/new-ramassage-count', 'Ws\NotificationController@newRamassageCount')->name('new_ramassage_list')->middleware('check-perm');
Route::any('/new-reclamation-count', 'Ws\NotificationController@reclamationCount')->name('new_reclamation_list')->middleware('check-perm');
Route::any('/new-reclamation-client-count', 'Ws\NotificationController@reclamationClientCount')->name('new_reclamationClient_list')->middleware('check-perm');
//arrivage
Route::any('/arrivage/list', 'ArrivageController@list')->name('arrivage_list')->middleware('check-perm');
Route::any('/arrivage/create', 'ArrivageController@create')->name('create_list')->middleware('check-perm');
Route::any('/arrivage/bon/list', 'ArrivageController@afficherBon')->name('afficher_list_arr')->middleware('check-perm');

//stock
Route::any('/stock/list', 'ArrivageController@stockList')->name('stock_list')->middleware('check-perm');
Route::any('/stock/api', 'ArrivageController@apiStock')->name('api_Stock')->middleware('check-perm');
Route::any('/stock/perdu/list', 'ArrivageController@stockPerduList')->name('stock_perdu_list')->middleware('check-perm');
Route::any('/stock/retour/{expedition}', 'ArrivageController@expRetour')->name('exp_retour')->middleware('check-perm');
Route::any('/stock/transfert/{expedition}', 'ArrivageController@transfert')->name('exp_transfert')->middleware('check-perm');
Route::any('/stock/transfert/create/{expedition}', 'ArrivageController@transfertCreate')->name('transfert_Create')->middleware('check-perm');
Route::any('/stock/print-stock/{ville}', 'ArrivageController@printStock')->name('stock_print')->middleware('check-perm');
Route::any('/stock/export-stock', 'ArrivageController@export_stock')->name('export_stock')->middleware('check-perm');
Route::any('/stock/retrouver/{expedition}', 'ArrivageController@retrouver')->name('exp_retrouver')->middleware('check-perm');
Route::any('/stock/retrouver/create/{expedition}', 'ArrivageController@retrouverCreate')->name('retrouver_create')->middleware('check-perm');

// typebl
Route::any('/typebl/list', 'TypeblController@list')->name('typebl_list')->middleware('check-perm');
Route::any('/typebl/create', 'TypeblController@create')->name('typebl_create')->middleware('check-perm');
Route::any('/typebl/update/{typebl}', 'TypeblController@update')->name('typebl_update')->middleware('check-perm');
Route::any('/typebl/delete/{typebl}', 'TypeblController@delete')->name('typebl_delete')->middleware('check-perm');

// bonliv
Route::any('/bonliv/list', 'BonlivController@list')->name('bonliv_list')->middleware('check-perm');
Route::any('/bonliv/create', 'BonlivController@create')->name('bonliv_create')->middleware('check-perm');
Route::any('/bonliv/update/{bonliv}', 'BonlivController@update')->name('bonliv_update')->middleware('check-perm');
Route::any('/bonliv/delete/{bonliv}', 'BonlivController@delete')->name('bonliv_delete')->middleware('check-perm');
Route::any('/bonliv/download/{bl}', 'BonlivController@download')->name('bonliv_download')->middleware('check-perm');

// Etat
Route::any('/etat/list', 'EtatController@list')->name('etat_list')->middleware('check-perm');
Route::any('/etat/realisation', 'EtatController@realisation')->name('realisation')->middleware('check-perm');
Route::any('/etat/remboursements', 'EtatController@remboursements')->name('remboursements')->middleware('check-perm');
Route::any('/etat/commission', 'EtatController@commission')->name('commission')->middleware('check-perm');
Route::any('/etat/indicateurs', 'EtatController@indicateurs')->name('indicateurs')->middleware('check-perm');
Route::any('/etat/tarification', 'EtatController@tarification')->name('tarification')->middleware('check-perm');
Route::any('/etat/auditmodification', 'EtatController@auditmodification')->name('auditmodification')->middleware('check-perm');
Route::any('/etat/caisses', 'EtatController@caisses')->name('etat_caisses')->middleware('check-perm');
// taxation
Route::any('/taxation/list', 'TaxationController@list')->name('taxation_list')->middleware('check-perm');

Route::any('/taxation/type', 'TaxationController@taxations_type')->name('taxations_type')->middleware('check-perm');
Route::any('/taxations', 'TaxationController@taxations')->name('taxations')->middleware('check-perm');
Route::any('/taxations/regions', 'TaxationController@taxations_region')->name('taxations_region')->middleware('check-perm');
Route::any('/taxations/commissions', 'TaxationController@commissions')->name('taxations_commissions')->middleware('check-perm');
Route::any('/taxation/create/{idville}', 'TaxationController@create')->name('taxation_create')->middleware('check-perm');
Route::any('/taxation/update/{taxation}', 'TaxationController@update')->name('taxation_update')->middleware('check-perm');
Route::any('/taxation/delete/{taxation}', 'TaxationController@delete')->name('taxation_delete')->middleware('check-perm');

// region
Route::any('/region/list', 'RegionController@list')->name('region_list')->middleware('check-perm');
Route::any('/region/create', 'RegionController@create')->name('region_create')->middleware('check-perm');
Route::any('/region/update/{region}', 'RegionController@update')->name('region_update')->middleware('check-perm');
Route::any('/region/delete/{region}', 'RegionController@delete')->name('region_delete')->middleware('check-perm');
//parameters
Route::any('/parameters/global', 'ParametersController@globale')->name('parameters_globale')->middleware('check-perm');

// ressource
Route::any('/ressource/list', 'RessourceController@list')->name('ressource_list')->middleware('check-perm');
Route::any('/ressource/create', 'RessourceController@create')->name('ressource_create')->middleware('check-perm');
Route::any('/ressource/update/{ressource}', 'RessourceController@update')->name('ressource_update')->middleware('check-perm');
Route::any('/ressource/delete/{ressource}', 'RessourceController@delete')->name('ressource_delete')->middleware('check-perm');

// fonctionnalite
Route::any('/fonctionnalite/list', 'FonctionnaliteController@list')->name('fonctionnalite_list')->middleware('check-perm');
Route::any('/fonctionnalite/create', 'FonctionnaliteController@create')->name('fonctionnalite_create')->middleware('check-perm');
Route::any('/fonctionnalite/update/{fonctionnalite}', 'FonctionnaliteController@update')->name('fonctionnalite_update')->middleware('check-perm');
Route::any('/fonctionnalite/delete/{fonctionnalite}', 'FonctionnaliteController@delete')->name('fonctionnalite_delete')->middleware('check-perm');


// sfacture
Route::any('/sfacture/list', 'SfactureController@list')->name('sfacture_list')->middleware('check-perm');
Route::any('/sfacture/create', 'SfactureController@create')->name('sfacture_create')->middleware('check-perm');
Route::any('/sfacture/update/{sfacture}', 'SfactureController@update')->name('sfacture_update')->middleware('check-perm');
Route::any('/sfacture/delete/{sfacture}', 'SfactureController@delete')->name('sfacture_delete')->middleware('check-perm');


// caissepp
Route::any('/caissepp/list', 'CaisseppController@list')->name('caissepp_list');
Route::any('/caissepp/create', 'CaisseppController@create')->name('caissepp_create');
Route::any('/caissepp/update/{caissepp}', 'CaisseppController@update')->name('caissepp_update');
Route::any('/caissepp/delete/{caissepp}', 'CaisseppController@delete')->name('caissepp_delete');
Route::any('/caissepp/valid/{expedition}', 'CaisseppController@valid')->name('caissepp_valid');
Route::any('/caissepp/export', 'CaisseppController@export')->name('caissepp_export');


// groupstatuts
Route::any('/groupstatuts/list', 'GroupstatutsController@list')->name('groupstatuts_list');
Route::any('/groupstatuts/create', 'GroupstatutsController@create')->name('groupstatuts_create');
Route::any('/groupstatuts/update/{groupstatuts}', 'GroupstatutsController@update')->name('groupstatuts_update');
Route::any('/groupstatuts/delete/{groupstatuts}', 'GroupstatutsController@delete')->name('groupstatuts_delete');
