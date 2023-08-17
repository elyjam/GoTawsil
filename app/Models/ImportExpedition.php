<?php



namespace App\Models;

class ImportExpedition
{

    public static function getExpeditionsData($fileData){
        $expeditions = [];
        for($i=0; $i<count($fileData); $i++){
            $expeditions[$i]['destinataire'] = $fileData[$i][0];
            $expeditions[$i]['ville'] = strtoupper(trim($fileData[$i][1]));
            $expeditions[$i]['tel'] = $fileData[$i][2];
            $expeditions[$i]['adresse'] = $fileData[$i][3];
            $expeditions[$i]['nbr_colis'] = $fileData[$i][4];
            $expeditions[$i]['montant_fond'] = $fileData[$i][5];
            $expeditions[$i]['num_commande'] = $fileData[$i][6];
            $expeditions[$i]['valeur_declaree'] = $fileData[$i][7];
            $expeditions[$i]['ouverture_colis'] = strtoupper($fileData[$i][8]);
            $expeditions[$i]['paiement_cheque'] = strtoupper($fileData[$i][9]);
        }
        return $expeditions;
    }

    public static function getExpeditionsDataFromForm($data){
        $expeditions = [];
        foreach($data['destinataire'] as $i => $value){

            $expeditions[$i]['destinataire'] = $data['destinataire'][$i];
            $expeditions[$i]['ville'] = strtoupper($data['ville'][$i]);
            $expeditions[$i]['tel'] = $data['tel'][$i];
            $expeditions[$i]['adresse'] = $data['adresse'][$i];
            $expeditions[$i]['nbr_colis'] = $data['nbr_colis'][$i];
            $expeditions[$i]['montant_fond'] = $data['montant_fond'][$i];
            $expeditions[$i]['num_commande'] = $data['num_commande'][$i];
            $expeditions[$i]['valeur_declaree'] = $data['valeur_declaree'][$i];
            $expeditions[$i]['ouverture_colis'] = strtoupper($data['ouverture_colis'][$i]);
            $expeditions[$i]['paiement_cheque'] = strtoupper($data['paiement_cheque'][$i]);
        }
        return $expeditions;
    }

    public static function getErrors($expeditions, $villes, $numExp){

        $errors = [];
        $numerosColis = [];
        foreach($expeditions as $i => $expedition){

            if(strlen(trim($expeditions[$i]['destinataire'])) === 0){
                $errors[$i.'_dest'] = "Destinataire obligatoire";
            }
            if(!isset($villes[$expeditions[$i]['ville']])){
                $errors[$i.'_ville'] = "Ville obligatoire";
            }
            if( (strlen(trim($expeditions[$i]['tel'])) !== 10 && strlen(trim($expeditions[$i]['tel'])) !== 12) || !ctype_digit(trim($expeditions[$i]['tel']))){
                $errors[$i.'_tel'] = "Tél invalide";
            }
            if(strlen(trim($expeditions[$i]['adresse'])) === 0){
                $errors[$i.'_adresse'] = "Adresse invalide invalide";
            }
            if(strlen(trim($expeditions[$i]['nbr_colis'])) === 0 || !ctype_digit(trim($expeditions[$i]['nbr_colis'])) || trim($expeditions[$i]['nbr_colis'])<=0 ) {
                $errors[$i.'_nbr_colis'] = "Nombre Colis invalide";
            }
            if(strlen(trim($expeditions[$i]['montant_fond'])) === 0){
                $errors[$i.'_montant_fond'] = "Montant Fond invalide";
            }
            if($numExp != 'AUTO'){
                if(strlen(trim($expeditions[$i]['num_commande'])) === 0){
                    $errors[$i.'_num_commande'] = "Num Commande invalide";
                }
                elseif(in_array($expeditions[$i]['num_commande'], $numerosColis)){
                    $errors[$i.'_num_commande'] = "Num Commande existe déjà";
                }
                else{
                    if(Expedition::where('num_expedition', trim($expeditions[$i]['num_commande']) )->first() === null){
                        $numerosColis[] = $expeditions[$i]['num_commande'];
                    }
                    else{
                        $errors[$i.'_num_commande'] = "Num Commande existe déjà";
                    }
                }
            }

            if(strlen(trim($expeditions[$i]['valeur_declaree'])) === 0){
                $errors[$i.'_valeur_declaree'] = "Valeur déclarée invalide";
            }

        }
        return $errors;
    }



}
