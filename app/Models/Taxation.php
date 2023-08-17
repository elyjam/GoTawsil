<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Ville;

class Taxation extends Model
{
    protected $table = 'taxations';
    protected $guarded = [];

    public static function saveData($formData, $newData)
    {

        foreach ($newData as $key => $value) {

            $value = str_replace(',', '.', $value);

            if (isset($formData[$key])) {

                if ($formData[$key] != $newData[$key]) {

                    $keys = explode('_', $key);

                    $taxation = Taxation::where('id_ville_exp', $keys[3])
                        ->where('id_ville_dest', $keys[2])
                        ->where('sens', $keys[0]);

                    if ($keys[1] != '0') {
                        $taxation->where('id_clients', $keys[1]);
                    }


                    $taxation = $taxation->first();

                    if ($taxation) {

                        if (is_numeric($value)) {
                            $taxation->coefficient = $value;
                            $taxation->save();
                            // dd($taxation);
                        } else {
                            $taxation->delete();
                        }
                    }else{
                        $taxation = new Taxation();
                        $taxation->sens = $keys[0];
                        $taxation->id_ville_dest = $keys[2];
                        $taxation->id_clients = $keys[1];
                        $taxation->id_ville_exp = $keys[3];
                        $taxation->coefficient = $value;
                        $taxation->save();
                    }
                }
            } elseif (is_numeric($value)) {

                $keys = explode('_', $key);
                $taxation = new Taxation();
                $taxation->sens = $keys[0];
                $taxation->id_ville_dest = $keys[2];
                $taxation->id_clients = $keys[1];
                $taxation->id_ville_exp = $keys[3];
                $taxation->coefficient = $value;
                $taxation->save();
            } else {
            }
        }
    }

    public static function getFormData()
    {
        $depart = request('depart', '0');
        $destination = request('destination', '0');
        $sens = request('sens', 'ENVOI');

        $client = request('client', '0');
        $formData = [];
        if (request('depart', '0') == '0' && request('destination', '0') == '0' && request('client', '0') == '0') {
            return $formData;
        }

        $records =  \DB::table("taxations")->where("date_desactivation", '=', '')->orWhere("date_desactivation", '=', null)->get();
        if ($client != '0') {
            $records->where("id_clients", '=', $client);
        }
        if ($destination != '0') {
            $records->where("id_ville_exp", '=', $destination);
        }
        if ($depart != '0') {
            $records->where("id_ville_dest", '=', $depart);
        }

        $data = $records->where("sens", $sens);

        foreach ($data as $row) {
            if (!is_numeric($row->id_clients)) {
                $row->id_clients = '0';
            }
            $formData[$sens . '_' . $row->id_clients . '_' . $row->id_ville_dest . '_' . $row->id_ville_exp] = $row->coefficient;
        }


        return $formData;
    }

    public static function getFormRows($villes, $formData)
    {

        $formRows = [];
        $depart = request('depart', '0');
        $departName = $depart == '0' ? '' : Ville::find($depart)->libelle ?? '';
        $destination = request('destination', '0');
        $destinationName = $destination == '0' ? '' : Ville::find($destination)->libelle ?? '';
        $client = request('client', '0');
        $clientName = $client == '0' ? '' : Client::find($client)->libelle ?? '';
        $sens = request('sens', 'ENVOI');

        if (request('depart', '0') == '0' && request('destination', '0') == '0' && request('client', '0') == '0') {

            return $formRows;
        } elseif ($depart != '0' && $destination != '0') {
            $formRows[] = [
                'client' => $clientName,
                'depart' => $departName,
                'destination' => $destinationName,
                'sens' => $sens,
                'key' => $sens . '_' . $client . '_' . $depart . '_' . $destination,
                'value' => $formData[strtoupper($sens) . '_' . $client . '_' . $depart . '_' . $destination] ?? '',
            ];

            return $formRows;
        } elseif ($depart != '0' && $destination == '0') {

            foreach ($villes as $ville) {

                $formRows[] = [
                    'client' => $clientName,
                    'depart' => $departName,
                    'destination' => $ville->libelle,
                    'sens' => $sens,
                    'key' => $sens . '_' . $client . '_' . $depart . '_' . $ville->id,
                    'value' => $formData[strtoupper($sens) . '_' . $client . '_' . $depart . '_' . $ville->id] ?? '',

                ];
            }
            return $formRows;
        } elseif ($depart == '0' && $destination != '0') {
            foreach ($villes as $ville) {
                $formRows[] = [
                    'client' => $clientName,
                    'depart' => $ville->libelle,
                    'destination' => $destinationName,
                    'sens' => $sens,
                    'key' => $sens . '_' . $client . '_' . $ville->id . '_' . $destination,
                    'value' => $formData[strtoupper($sens) . '_' . $client . '_' . $ville->id . '_' . $destination] ?? '',
                ];
            }
            return $formRows;
        }

        return $formRows;
    }

    public function villeDetailDest()
    {
        return $this->belongsTo(\App\Models\Ville::class, 'id_ville_dest');
    }

    public function villeDetailExp()
    {
        return $this->belongsTo(\App\Models\Ville::class, 'id_ville_exp');
    }

    public static function getPrixColis($client, $ville_exp, $ville_des)
    {

        $ville_region = ville::where('id', $ville_exp)->first();
        // check if client has cofficient
        if (Taxation::where('id_clients', $client)->where('id_ville_exp', $ville_exp)->where('id_ville_dest', $ville_des)->where('sens', 'ENVOI')->first() != null) {
            $taxt = Taxation::where('id_clients', $client)->where('id_ville_exp', $ville_exp)->where('id_ville_dest', $ville_des)->where('sens', 'ENVOI')->first();
            return $taxt->coefficient;
        }
        //check if client has region cofficent
        elseif ($ville_region->RegionDetail() != null && TaxationRegions::where('id_clients', $client)->where('id_region_exp', $ville_region->RegionDetail()->id)->where('id_ville_dest', $ville_des)->where('sens', 'ENVOI')->first() != null && Taxation::where('id_clients', $client)->where('id_ville_exp', $ville_exp)->where('id_ville_dest', $ville_des)->where('sens', 'ENVOI')->first() == null) {
            $taxt = TaxationRegions::where('id_clients', $client)->where('id_region_exp', $ville_region->RegionDetail()->id)->where('id_ville_dest', $ville_des)->where('sens', 'ENVOI')->first();
            return $taxt->coefficient;
        }
        // check if client has cofficient with all villes
        elseif (Taxation::where('id_clients', $client)->where('id_ville_exp', '2')->where('id_ville_dest', $ville_des)->where('sens', 'ENVOI')->first() != null) {
            $taxt = Taxation::where('id_clients', $client)->where('id_ville_exp', '2')->where('id_ville_dest', $ville_des)->where('sens', 'ENVOI')->first();
            return $taxt->coefficient;
        }

        //check if the cities has cofficent
        elseif (Taxation::where('date_desactivation', '')->where('id_clients', '')->where('id_ville_exp', $ville_exp)->where('id_ville_dest', $ville_des)->where('sens', 'ENVOI')->first() != null) {

            $taxt = Taxation::where('date_desactivation', '')->where('id_ville_exp', $ville_exp)->where('id_ville_dest', $ville_des)->where('sens', 'ENVOI')->first();

            return $taxt->coefficient;
        }
    }

    public static function getPrixColis_Retour($client, $ville_exp, $ville_des)
    {

        dd($client, $ville_exp, $ville_des);
        $ville_region = ville::where('id', $ville_exp)->first();
        // check if client has cofficient - retour
        if (Taxation::where('id_clients', $client)->where('id_ville_exp', $ville_exp)->where('id_ville_dest', $ville_des)->where('sens', 'RETOUR')->first() != null) {
            $taxt = Taxation::where('id_clients', $client)->where('id_ville_exp', $ville_exp)->where('id_ville_dest', $ville_des)->where('sens', 'RETOUR')->first();
            return $taxt->coefficient;
        }
        //check if client has region cofficent - retour
        elseif ($ville_region->RegionDetail() != null && TaxationRegions::where('id_clients', $client)->where('id_region_exp', $ville_region->RegionDetail()->id)->where('id_ville_dest', $ville_des)->where('sens', 'RETOUR')->first() != null && Taxation::where('id_clients', $client)->where('id_ville_exp', $ville_exp)->where('id_ville_dest', $ville_des)->where('sens', 'RETOUR')->first() == null) {
            $taxt = TaxationRegions::where('id_clients', $client)->where('id_region_exp', $ville_region->RegionDetail()->id)->where('id_ville_dest', $ville_des)->where('sens', 'RETOUR')->first();
            return $taxt->coefficient;
        }
        // check if client has cofficient with all villes - retour
        elseif (Taxation::where('id_clients', $client)->where('id_ville_exp', $ville_exp)->where('id_ville_dest', '2')->where('sens', 'RETOUR')->first() != null) {
            $taxt = Taxation::where('id_clients', $client)->where('id_ville_exp', $ville_exp)->where('id_ville_dest', '2')->where('sens', 'RETOUR')->first();
            return $taxt->coefficient;
        }
        //check if the cities has cofficent - retour
        elseif (Taxation::where('date_desactivation', '')->where('id_clients', '')->where('id_ville_exp', $ville_exp)->where('id_ville_dest', $ville_des)->where('sens', 'RETOUR')->first() != null) {
            $taxt = Taxation::where('date_desactivation', '')->where('id_ville_exp', $ville_exp)->where('id_ville_dest', $ville_des)->where('sens', 'RETOUR')->first();
            return $taxt->coefficient;
        }
    }
}
