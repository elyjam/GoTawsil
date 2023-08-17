<?php

namespace App\Http\Controllers;

use DB;
use DataTables;
use App\User;
use App\Models\Role;
use App\Models\Droit;
use App\Models\Ville;
use App\Models\Client;
use App\Models\Employe;
use App\Models\Fonctionnalite;
use App\Models\RefTable;
use App\Models\Region;
use App\Rules\UserClient;
use App\Rules\UserEmploye;
use App\Rules\EmployeClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function getRules()
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'role' => ['required'],
            'last_name' => ['required', 'string', 'max:50'],
            'first_name' => ['required', 'string', 'max:50'],
            'login' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function list(Request $request)
    {

        // if ($request->isMethod('post')) {

        //     if($request->role == 0){

        //         $users = User::fetchAll();
        //         $roles = Role::fetchAll();
        //     }else{

        //         $users = User::fetchAll()->where('role', $request->role);
        //         $roles = Role::fetchAll();
        //     }

        //     return view(
        //         'back/user/list',
        //         [
        //             'users' => $users,
        //             'roles' => $roles
        //         ]
        //     );
        // }
        // $request->flash();
        // $users =  DB::table("users")
        // ->select("*", \DB::raw('users.id as id'), \DB::raw('roles.label as role_label'))
        // ->leftJoin('roles', 'roles.id', '=', 'users.role')
        // ->where('users.deleted', '0')
        // ->get();
        $request->flash();
        $roles = Role::fetchAll();

        return view(
            'back/user/list',
            [

                'roles' => $roles
            ]
        );
    }

    public function userApi(Request $request)
    {

        $formData = array();
        parse_str($request->all()['form'], $formData);

        $query =  DB::table("users")
            ->select(
                "*",
                \DB::raw('users.id as id'),
                \DB::raw('roles.label as role_label')
            )
            ->leftJoin('roles', 'roles.id', '=', 'users.role')
            ->where('users.deleted', '0');
        if (isset($formData['role']) && $formData['role'] != '0') {
            $query->where('roles.id', '=', $formData['role']);
        }

        if (isset($formData['role']) && $formData['role'] == '00') {
            $query->where('roles.id', '!=', 3);
        }

        return Datatables::of($query)->addIndexColumn()
            ->addColumn('photo', function ($record) {
                if ($record->photo != null) {
                    return '<img src="/uploads/photos/' . $record->photo . '"
                class="border-radius-4" alt="profile image" height="32"
                width="32" /> ';
                } else {
                    return '<img src="/uploads/photos/default.png"
                class="border-radius-4" alt="profile image" height="32"
                width="32" /> ';
                }
            })->addColumn('action', function ($record) {

                return '

                    <a href="' . route('user_update', ['user' => $record->id]) . '"><i
                    class="material-icons tooltipped" data-position="top"
                    data-tooltip="Modifier">edit</i></a>

            <a href="#!" onclick="openSuppModal(' . $record->id . ')"><i
                    class="material-icons tooltipped" style="color: #c10027;"
                    data-position="top" data-tooltip="Supprimer">delete</i></a>


                     ';
            })->addColumn('nom', function ($record) {
                return $record->name . ' ' . $record->first_name;
            })->addColumn('login', function ($record) {
                return $record->login;
            })->addColumn('role_label', function ($record) {
                return $record->role_label;
            })->addColumn('created_at', function ($record) {
                return $record->created_at;
            })

            ->rawColumns(['photo', 'action', 'nom', 'login', 'role_label', 'created_at'])
            ->make(true);
    }
    public function create(Request $request)
    {
        $password = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        $username = "GO" . $password;
        $usercheck = DB::table('users')->where('login', '=', $username)->first();

        while ($usercheck != null) {
            $password = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
            $username = "GO" . $password;
            $usercheck = DB::table('users')->where('login', '=', $username)->first();
        }

        if ($request->isMethod('post')) {


            $data = $request->all();

            $rules = [
                // 'name' => ['required', 'string', 'max:50'],
                'employe' => [new UserEmploye()],
                'client' => [new UserClient()],
                // 'first_name' => ['required', 'string', 'max:50'],
                // 'login' => ['required', 'string', 'max:50'],
                // 'password' => ['required', 'string', 'min:8', 'confirmed'],
            ];

            $validator =  Validator::make($data, $rules);
            if ($validator->fails()) {

                return redirect()->back()->withInput()->withErrors($validator);
            } else {

                if ($data['type'] == '2') {

                    $client = Client::where('id',$request->all()["client"])->first();

                    $user = [
                        'name' =>  $client->libelle,
                        'type' => $data['type'],
                        'client' => is_numeric($data['client']) ? $data['client'] : null,
                        'login' => $request->username,
                        'role' => 3,
                        'password' => Hash::make($request->password),
                    ];
                } else {
                    $employe = Employe::where('id',$request->all()["employe"])->first();
                    $user = [
                        'name' => $employe->libelle,
                        'type' => $data['type'],
                        'employe' => is_numeric($data['employe']) ? $data['employe'] : null,
                        'login' => $request->username,
                        'role' => $data['role'],
                        'password' => Hash::make($request->password),
                        'validated' => 1,
                    ];

                    $employe = Employe::find($data['employe']);
                    $employe->update(['role' => $data['role']]);
                }
                User::create($user);
                Redirect::to(route('user_list'))->send();
            }
        }
        return view(
            'back/user/create',
            [
                'roles' => Role::fetchAll(),
                'username' => $username,
                'password' => $password,
                'employes' => Employe::available_employe(),
                'clients' => Client::available_client(),
            ]
        );
    }

    public function update(User $user, Request $request)
    {

        //'droits' =>
        if ($request->isMethod('post')) {

            $data = $request->all();
            $rules = [
                'employe' => [new UserEmploye()],
                'client' => [new UserClient()],
                'login' => ['required', 'string', 'max:50'],
                'password' => ['confirmed'],
            ];

            $validator =  Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {

                $user->type = $request->all()["type"];

                if($request->all()["role"] == '3'){
                    $user->client = is_numeric($request->all()["client"]) ? $request->all()["client"] : null;
                    $client = Client::where('id',$request->all()["client"])->first();
                    $user->name = $client->libelle;

                }else{
                    $user->employe = is_numeric($request->all()["employe"]) ? $request->all()["employe"] : null;
                    $employe = Employe::where('id',$request->all()["employe"])->first();
                    $user->name = $employe->libelle;
                }

                $user->login = $request->all()["login"];
                $user->role = $request->all()["role"];
                if (empty($request->get('password'))) {
                    //$data = $request->except('password');
                    //$user->update($data);
                    $user->save();
                } else {
                    //$data = $request->all();
                    $user->password = Hash::make($data['password']);
                    $user->save();
                }


                if (isset($request->all()['villes'])) {
                    $user->relatedVilles()->sync(Ville::find($request->all()['villes']));
                } else {
                    $user->relatedVilles()->sync([]);
                }

                if (isset($request->all()['regions'])) {
                    $user->relatedRegions()->sync(Region::find($request->all()['regions']));
                } else {
                    $user->relatedRegions()->sync([]);
                }


                if (isset($request->all()['fonctionnalites'])) {
                    $user->fonctionnalites()->sync(Fonctionnalite::find($request->all()['fonctionnalites']));
                } else {
                    $user->fonctionnalites()->sync([]);
                }


                if (isset($request->all()['villes_charger'])) {
                    $user->relatedVillesCharger()->sync(Ville::find($request->all()['villes_charger']));
                } else {
                    $user->relatedVillesCharger()->sync([]);
                }



                Redirect::to(route('user_list'))->send();
            }
        }


        $fonc_ids = $user->fonctionnalites()->allRelatedIds()->toArray();

        if ($user->roleDetail != '') {
            $mes_fonct = $user->roleDetail->fonctionnalites()->allRelatedIds()->toArray();
        } else {
            $mes_fonct = '';
        }







        return view(
            'back/user/update',
            [
                'record' => $user->roleDetail,
                'mes_fonc' => $fonc_ids,
                'roles' => Role::fetchAll(),
                'droits' => Droit::all()->where('deleted', "0"),
                'user' => $user,
                'employes' => Employe::available_employe(),
                'clients' => Client::fetchAll(),
                'villes' => \App\Models\Ville::getVilles(),
                'regions' => \App\Models\Region::getRegions(),
                'fonctionnalites' => \App\Models\Fonctionnalite::all()->where('deleted', "0")->whereNotIn('id', $mes_fonct)
            ]
        );
    }

    public function delete(User $user)
    {
        $user->update(['deleted' => 1, 'deleted_at' => date("Y-m-d H:i:s"), 'deleted_by' => \Auth::user()->id]);
        return redirect()->back()->with('success', "L'enregistrement a été supprimé avec succès");
    }

    public function myProfil(Request $request)
    {

        $data = $request->all();
        $request->flash();
        $roles = Role::fetchAll();
        $rules = [
            'password' => 'confirmed|string|min:8|confirmed',

        ];
        $user = User::where('id', \Auth::user()->id)->first();

        if ($request->isMethod('post')) {
            if (strlen(trim($data['password'])) < 1) {
                unset($rules['password']);
            }
            $validator =  Validator::make($data, $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            if ($user) {
                if (strlen(trim($data['password'])) > 1) {
                    $user->password = Hash::make($data['password']);
                }

                // $user->ClientDetail->email_nolivre = $request->email_nolivre;
                // $user->ClientDetail->email_rembroursement = $request->email_rembroursement;
                // $user->ClientDetail->save();
                if (isset($request->file)) {
                    $fileName = time() . '.' . $request->file->extension();
                    $request->file->move(public_path('uploads/photos'), $fileName);
                    $user->photo = $fileName;
                }
                $user->save();
                $request->session()->flash('success', 'Profil modifié avec succés');
            }
        }
        return view(
            'back/user/profil',
            [
                'roles' => $roles,
                'user' => User::where('id', \Auth::user()->id)->first()
            ]
        );
    }


    public function myProfil_client(Request $request)
    {

        $data = $request->all();
        $request->flash();
        $roles = Role::fetchAll();
        $rules = [
            'password' => 'confirmed|string|min:8|confirmed',
        ];
        $user = User::where('id', \Auth::user()->id)->first();

        if ($request->isMethod('post')) {
            if (strlen(trim($data['password'])) < 1) {
                unset($rules['password']);
            }
            $validator =  Validator::make($data, $rules);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            if ($user) {
                if (strlen(trim($data['password'])) > 1) {
                    $user->password = Hash::make($data['password']);
                }

                $user->ClientDetail->email_nolivre = $request->email_nolivre;
                $user->ClientDetail->email_rembroursement = $request->email_rembroursement;
                $user->ClientDetail->save();
                if (isset($request->file)) {
                    $fileName = time() . '.' . $request->file->extension();
                    $request->file->move(public_path('uploads/photos'), $fileName);
                    $user->photo = $fileName;
                }
                $user->save();
                $request->session()->flash('success', 'Profil modifié avec succés');
            }
        }
        return view(
            'client/profil',
            [
                'roles' => $roles,
                'user' => User::where('id', \Auth::user()->id)->first()
            ]
        );
    }
}
