<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Droit;
use App\Models\Fonctionnalite;
use App\Models\Ville;
use App\Models\Parameter;


class User extends Authenticatable
{
    use Notifiable;

    public function ClientDetail()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client');
    }


    public function EmployeDetail()
    {
        return $this->belongsTo(\App\Models\Employe::class, 'employe');
    }

    public function roleDetail()
    {
        return $this->belongsTo(\App\Models\Role::class, 'role');
    }



    public function droits()
    {
        return $this->belongsToMany(Droit::class, 'users_droits');
    }

    public function fonctionnalites()
    {
        return $this->belongsToMany(Fonctionnalite::class, 'users_fonctionnalites', 'id_user', 'id_fonctionnalite');
    }





    public static function fetchAll()
    {
        return  \DB::table("users")
            ->select("*", \DB::raw('users.id as id'), \DB::raw('roles.label as role_label'))
            ->leftJoin('roles', 'roles.id', '=', 'users.role')
            ->where('users.deleted', '0')
            ->get();
    }

    public static function hasRoute($route)
    {
        return is_numeric(array_search($route, session('user_routes', [])));
    }

    public static function hasRessource($ressource)
    {
        return is_numeric(array_search($ressource, session('user_ressources', [])));
    }

    public static function getRessourcesByFonctionnalites($fonctionnalites)
    {

        $ressources = \DB::table("fonctionnalites_ressources")
            ->select(
                "*",
                \DB::raw('ressources.id as id'),
                \DB::raw('ressources.name as ressource_name')
            )
            ->leftJoin('ressources', 'ressources.id', '=', 'fonctionnalites_ressources.ressource_id')
            ->where('ressources.deleted', '0')
            ->whereIn('fonctionnalite_id', $fonctionnalites)
            ->get();
        return array_column($ressources->toArray(), 'ressource_name');
    }

    public static function getFonctionnalitesByUser($user)
    {

        $fonctionnalites = \DB::table("users_fonctionnalites")
            ->select(
                "*",
                \DB::raw('fonctionnalites.id as id'),
                \DB::raw('fonctionnalites.name as fonctionnalites_name')
            )
            ->leftJoin('fonctionnalites', 'fonctionnalites.id', '=', 'users_fonctionnalites.id_fonctionnalite')
            ->where('fonctionnalites.deleted', '0')
            ->whereIn('id_user', $user)
            ->get();
        return array_column($fonctionnalites->toArray(), 'id');
    }

    public static function getRoutesByFonctionnalites($fonctionnalites)
    {

        $ressources = \DB::table("fonctionnalites_routes")
            ->whereIn('fonctionnalite_id', $fonctionnalites)
            ->get();
        return array_column($ressources->toArray(), 'route');
    }

    public static function getUserVilles()
    {
        $villes = \Auth::user()->relatedVilles()->allRelatedIds()->toArray();
        $region = \Auth::user()->relatedRegions()->allRelatedIds()->toArray();
        $villes_regions = \DB::table("region_villes")
        ->whereIn('id_region', $region)->pluck('id_ville')->toArray();

        $all_villes = array_merge($villes,$villes_regions);

        if (empty($all_villes)) {
            $all_villes = array(
                0 => (int)\Auth::user()->EmployeDetail->agence,
            );
        }
        return array_unique($all_villes);
    }

    public static function getUserVilles_api($user)
    {
        $villes = $user->relatedVilles()->allRelatedIds()->toArray();
        $region = $user->relatedRegions()->allRelatedIds()->toArray();
        $villes_regions = \DB::table("region_villes")
        ->whereIn('id_region', $region)->pluck('id_ville')->toArray();

        $all_villes = array_merge($villes,$villes_regions);

        if (empty($all_villes)) {
            $all_villes = array(
                0 => (int)$user->EmployeDetail->agence,
            );
        }
        return array_unique($all_villes);
    }



    public static function getUserVillesCharger()
    {
        return \Auth::user()->relatedVillesCharger()->allRelatedIds()->toArray();
    }

    public static function getExp_pilotage($user)
    {
        $villes = $user->relatedVilles()->allRelatedIds()->toArray();
        return \DB::table('expeditions')
            ->select('*')
            ->whereIn('etape',[2,9,6,4,10,16,20])
            ->whereIn('agence_des', $villes)
            ->get();
    }

    public static function storeUserData()
    {
        $role = \App\Models\Role::find(\Auth::user()->role);
        $fonctionnalites_role = $role->fonctionnalites()->allRelatedIds()->toArray();
        $fonctionnalites_personel = self::getFonctionnalitesByUser(\Auth::user());

        $fonctionnalites = array_merge($fonctionnalites_role, $fonctionnalites_personel);


        $ressources = self::getRessourcesByFonctionnalites($fonctionnalites);
        $routes = self::getRoutesByFonctionnalites($fonctionnalites);

        session(['user_ressources' => $ressources]);
        session(['user_routes' => $routes]);
        session(['global_parameters' => Parameter::find(1)]);
    }

    public function relatedVilles()
    {
        return $this->belongsToMany(Ville::class, 'villes_users',  'user_id', 'ville_id');
    }

    public function relatedRegions()
    {
        return $this->belongsToMany(Ville::class, 'regions_users', 'user_id', 'region_id');
    }

    public function relatedVillesCharger()
    {
        return $this->belongsToMany(Ville::class, 'villes_users_charger', 'user_id', 'ville_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
