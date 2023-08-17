<?php

namespace EasyCollab\Quicky;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use EasyCollab\Quicky\Models\Quicky;

class QuickyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) { 
            if (isset($_POST['Identifiant'])){
                //\DB::statement(Quicky::getSql());
                Quicky::genMigrationFile();
                Quicky::addRoutes();
                Quicky::genModelFile();
                Quicky::genControllerFile();
                Quicky::genListView();
                Quicky::genCreateView();
                Quicky::genUpdateView();
                \Artisan::call('migrate --force');
                \Artisan::call('route:clear');
            }
        }
        return view('easycollab::create');
    }
}