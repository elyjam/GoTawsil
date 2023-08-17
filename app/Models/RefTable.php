<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefTable extends Model
{
    protected $table = '';

    function __construct($table) {
        $this->table = 'ref_'.$table;
    }

    public static function fetchAll($table){
        return \DB::table('ref_'.$table)->orderBy('order')->get();
    }

    public static function fetchAllEntities(){
        return \DB::table('ref_entities')
                ->select( '*',
                      \DB::raw('ref_offices.label as office_label')
                    , \DB::raw('ref_entities.id as id')
                    , \DB::raw('ref_entities.label as label')
                    
                )
                ->leftJoin('ref_offices', 'ref_offices.id', '=', 'ref_entities.office')
                ->get();
    }

    public static function fetchAllEnabled($table){
        return \DB::table('ref_'.$table)->where("disabled",'0')->orderBy('order')->get();
    }

    public static function getConst($liste, $valeur){
        
        switch ($liste) {
            case 'yes_no':
                switch ($valeur) {
                    case '1':
                        return "نعم";
                    case '2':
                        return "لا";
                    default:
                        return "";
                }
                break;
            case 'transport_means':
                switch ($valeur) {
                    case '1':
                        return "سيارة المصلحة";
                    case '2':
                        return "سيارة خاصة";
                    case '3':
                        return "حافلة";
                    case '5':
                        return "قطار";
                    case '5':
                        return "طائرة";
                    default:
                        return "";
                }
                break;
            case 'month':
                switch ($valeur) {
                    case '1':
                        return "يناير";
                    case '2':
                        return "فبراير";
                    case '3':
                        return "مارس";
                    case '4':
                        return "أبريل";
                    case '5':
                        return "ماي";
                    case '6':
                        return "يونيو";
                    case '7':
                        return "يوليوز";
                    case '8':
                        return "غشت";
                    case '9':
                        return "شتنبر";
                    case '10':
                        return "أكتوبر";
                    case '11':
                        return "نونبر";
                    case '12':
                        return "دجنبر";
                    default:
                        return "";
                }
                break;
            
            default:
                return "";
                break;
        };
    }
}