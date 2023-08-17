<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Ville;

class TaxationRegions extends Model
{
    protected $table = 'taxations_region';
    protected $guarded = [];

    public static function saveData($formData, $newData)
    {

        foreach ($newData as $key => $value) {
            $value = str_replace(',', '.', $value);
            if (isset($formData[$key])) {
                if ($formData[$key] != $newData[$key]) {
                    $keys = explode('_', $key);
                    $taxation = TaxationRegions::where('id_region_exp', $keys[3])
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
                        } else {
                            $taxation->delete();
                        }
                    }
                }
            } elseif (is_numeric($value)) {
                $keys = explode('_', $key);
                $taxation = new TaxationRegions();
                $taxation->sens = $keys[0];
                $taxation->id_ville_dest = $keys[2];
                $taxation->id_clients = $keys[1];
                $taxation->id_region_exp = $keys[3];
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
        $records =  \DB::table("taxations_region")->where("date_desactivation", '=', '')->orWhere("date_desactivation", '=', null)->get();
        if ($client != '0') {
            $records->where("id_clients", '=', $client);
        }
        if ($destination != '0') {
            $records->where("id_region_exp", '=', $destination);
        }
        if ($depart != '0') {
            $records->where("id_ville_dest", '=', $depart);
        }
        $data = $records->where("sens", $sens);
        foreach ($data as $row) {
            if (!is_numeric($row->id_clients)) {
                $row->id_clients = '0';
            }
            $formData[$sens . '_' . $row->id_clients . '_' . $row->id_ville_dest . '_' . $row->id_region_exp] = $row->coefficient;
        }
        return $formData;
    }

    public static function getFormRows($regions,$villes,$formData)
    {

        $formRows = [];
        $depart = request('depart', '0');
        $departName = $depart == '0' ? '' : Ville::find($depart)->libelle ?? '';
        $destination = request('destination', '0');
        $destinationName = $destination == '0' ? '' : Region::find($destination)->libelle ?? '';
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
            foreach ($regions as $region) {
                $formRows[] = [
                    'client' => $clientName,
                    'depart' => $region->libelle,
                    'destination' => $destinationName,
                    'sens' => $sens,
                    'key' => $sens . '_' . $client . '_' . $region->id . '_' . $destination,
                    'value' => $formData[strtoupper($sens) . '_' . $client . '_' . $region->id . '_' . $destination] ?? '',
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

    public function RegionDetailExp()
    {
        return $this->belongsTo(\App\Models\Region::class, 'id_region_exp');
    }


}
