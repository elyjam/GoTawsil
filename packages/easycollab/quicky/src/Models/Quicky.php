<?php
namespace EasyCollab\Quicky\Models;

use EasyCollab\Quicky\Models\FormType;
use Illuminate\Database\Eloquent\Model;

class Quicky extends Model
{
    protected $table = 'users';

    public static function genListView(){

        $th=$td="";
        foreach($_POST['Identifiant'] as $key => $value){
            if ($_POST['del'][$key] == 0 && $_POST['inGrid'][$key]==1 && $_POST['formElement'][$key] != "primary_key" )
            {
                $label=$_POST['Label'][$key];
                $th.="
                                            <th> $label </th>";
            }
        }

        foreach($_POST['Identifiant'] as $key => $value){
            if ($_POST['del'][$key] == 0 && $_POST['inGrid'][$key]==1 && $_POST['formElement'][$key] != "primary_key" )
            {
                $id=$_POST['Identifiant'][$key];
                $type=$_POST['formElement'][$key];
                switch ($type)
                {
                    case "secondary_key":
                        $skvalue=$_POST['skvalue'][$key];
                        $recordVar = strtolower($_POST['skmodel'][$key])."Detail";
                        $td.="
                                            <td> {{ \$record->$recordVar->$skvalue }} </td>";
                        break;
                    default:
                        $td.="
                                            <td> {{ \$record->$id }} </td>";
                        break;
                }

            }
        }
        $contenu=file_get_contents(base_path()."/packages/easycollab/quicky/src/Templates/List.php");
        $contenu=str_replace('{projetId}', strtolower($_POST['projet']), $contenu);
        $contenu=str_replace('{th}', $th, $contenu);
        $contenu=str_replace('{td}', $td, $contenu);

		$contenu=str_replace('{projetId}', strtolower($_POST['projet']), $contenu);
		$contenu=str_replace('{th}', $th, $contenu);
		$contenu=str_replace('{td}', $td, $contenu);
        $path= base_path().'/resources/views/back/'.strtolower($_POST['projet']);
        if (!is_dir($path)) { @mkdir($path); }
        $file=fopen($path.'/list.blade.php','w+');
        fwrite($file,$contenu);
        fclose($file);
    }

    public static function genCreateView(){

        $formTypes="";
        foreach($_POST['Identifiant'] as $key => $value){
            if ($_POST['del'][$key] == 0)
            {
                $id=$_POST['Identifiant'][$key];
                $label=$_POST['Label'][$key];
                $columns = $_POST['colNumber'] ?? 6;
                $type=$_POST['formElement'][$key];
                switch ($type)
                {
                    case "primary_key":
                        break;
                    case "secondary_key":

                        $skkey=$_POST['skkey'][$key];
                        $skvalue=$_POST['skvalue'][$key];
                        $recordVar = strtolower($_POST['skmodel'][$key])."Records";

                        $formTypes .= "
                                    <div class=\"col s$columns input-field\">
                                    <select name='$id' id='$id' class=\"select2 browser-default\">
                                    <option value=''></option>
                                    @foreach (\$$recordVar as \$row)
                                        <option class='option' {{(\$row->$skkey == old('$id')) ? 'selected' : ''}}
                                        value='{{\$row->$skkey}}'> {{\$row->$skvalue}}</option>
                                    @endforeach
                                ";

                        $formTypes.="</select>
                                    <label for=\"$id\"> $label</label>
                                    @error('$id')
                                        <span class=\"helper-text materialize-red-text\">{{ \$message }}</span>
                                    @enderror
                                </div>
                                    ";

                        break;
                    case "text":
                        $formTypes.=FormType::getFormCreateHtml($label, $id, $columns);
                        break;
                    case "select":

                        $formTypes .= "
                                    <div class=\"col s$columns input-field\">
                                    <select name='$id' id='$id' class=\"select2 browser-default\">
                                    <option value=''></option>
                                ";
                                if (isset($_POST['Select_valeur'][$key])){
                                    foreach($_POST['Select_valeur'][$key] as $cle => $valeur){
                                        $option_cle=$_POST['Select_cle'][$key][$cle];
                                        $option_val=$_POST['Select_valeur'][$key][$cle];
                                    $formTypes.="<option value=\"$option_cle\"> $option_val </option> \n";
                                    }
                                }
                        $formTypes.="</select> <label for=\"$id\"> $label</label>
                                    @error('$id')
                                        <span class=\"helper-text materialize-red-text\">{{ \$message }}</span>
                                    @enderror
                                </div>
                                    ";
                        break;

                    case "select_multiple":
                        /*fputs ($createview, "
                        <tr>
                            <td width='30%' height='37' class='form-label' >
                                $label :
                            </td>
                            <td width='70%'>
                                <select  name='$id' id='$id' multiple tabindex=\"3\" data-placeholder=\"Choisir un $label ...\" class=\"chzn-select\"  style='width:95%;' >
                                    ");
                                if (isset($_POST['Select_multiple_valeur'][$key])){
                                    foreach($_POST['Select_multiple_valeur'][$key] as $cle => $valeur){
                                        $option_cle=$_POST['Select_multiple_cle'][$key][$cle];
                                        $option_val=$_POST['Select_multiple_valeur'][$key][$cle];
                                        fputs ($createview, "
                                        <option value='$option_cle'>$option_val</option>");
                                    }
                                }
                        fputs ($createview, "
                                </select>
                            </td>
                        </tr>") ;
                        break;*/
                        $formTypes.=FormType::getFormCreateHtml($label, $id, $columns, "phone");
                    case "textarea":
                        $formTypes.=FormType::getFormCreateHtml($label, $id, $columns, "textarea");
                        break;
                    case "ckeditor":
                        $formTypes.=FormType::getFormCreateHtml($label, $id, $columns, "phone");
                        break;
                    case "radio":
                        $formTypes .= "
                                    <div class=\"col s$columns input-field\">
                                    <p><label><h6>$label :</h6></label></p><p>
                                ";
                        if (isset($_POST['Radio_valeur'][$key])){
                            foreach($_POST['Radio_valeur'][$key] as $cle => $valeur){
                                $option_cle=$_POST['Radio_cle'][$key][$cle];
                                $option_val=$_POST['Radio_valeur'][$key][$cle];
                                $formTypes.="<label><input class='with-gap' name='$id' value='$option_cle' type='radio'><span>$option_val</span></label> \n";
                            }
                        }
                        $formTypes.="</p>
                                    @error('$id')
                                        <span class=\"helper-text materialize-red-text\">{{ \$message }}</span>
                                    @enderror
                                </div>
                                    ";
                        break;
                    case "checkbox":
                        $formTypes.=FormType::getFormCreateHtml($label, $id, $columns, "checkbox");
                        break;
                    case "datepicker":
                        $formTypes.=FormType::getFormCreateHtml($label, $id, $columns, "datepicker");
                        break;
                    case "timepicker":
                        $formTypes.=FormType::getFormCreateHtml($label, $id, $columns, "timepicker");
                        break;
                    case "colorpicker":
                        $formTypes.=FormType::getFormCreateHtml($label, $id, $columns, "colorpicker");
                        break;
                    case "file":
                        $formTypes.=FormType::getFormCreateHtml($label, $id, $columns, "file");
                        break;
                    case "phone":
                        $formTypes.=FormType::getFormCreateHtml($label, $id, $columns, 'phone');
                        break;
                    default:
                        $formTypes.=FormType::getFormCreateHtml($label, $id, $columns);
                        break;
                }
            }
        }

        $contenu=file_get_contents(base_path()."/packages/easycollab/quicky/src/Templates/Create.php");
        $contenu=str_replace('{projetId}', strtolower($_POST['projet']), $contenu);
        $contenu=str_replace('{formTypes}', $formTypes, $contenu);

		$contenu=str_replace('{projetId}', strtolower($_POST['projet']), $contenu);
		$contenu=str_replace('{formTypes}', $formTypes, $contenu);
        $path= base_path().'/resources/views/back/'.strtolower($_POST['projet']);
        if (!is_dir($path)) { @mkdir($path); }
        $file=fopen($path.'/create.blade.php','w+');
        fwrite($file,$contenu);
        fclose($file);
    }


    public static function genUpdateView(){

        $formTypes="";
        
        foreach($_POST['Identifiant'] as $key => $value){
            if ($_POST['del'][$key] == 0)
            {
                $id=$_POST['Identifiant'][$key];
                $label=$_POST['Label'][$key];
                $columns = $_POST['colNumber'] ?? 6;
                $type=$_POST['formElement'][$key];
                switch ($type)
                {
                    case "primary_key":
                        break;
                    case "secondary_key":
                        $skkey=$_POST['skkey'][$key];
                        $skvalue=$_POST['skvalue'][$key];
                        $recordVar = strtolower($_POST['skmodel'][$key])."Records";

                        $formTypes .= "
                                    <div class=\"col s$columns input-field\">
                                    <select name='$id' id='$id' class=\"select2 browser-default\">
                                    <option value=''></option>
                                        @foreach (\$$recordVar as \$row)
                                            <option class='option' {{(\$row->$skkey == old('$id', \$record->$id)) ? 'selected' : ''}}
                                            value='{{\$row->$skkey}}'> {{\$row->$skvalue}}</option>
                                        @endforeach
                                ";
                        $formTypes.="</select>
                                    <label for=\"$id\"> $label</label>
                                    @error('$id')
                                        <span class=\"helper-text materialize-red-text\">{{ \$message }}</span>
                                    @enderror
                                </div>
                                    ";
                        break;
                    case "text":
                        $formTypes.=FormType::getFormUpdateHtml($label, $id, $columns);
                        break;
                    case "select":
                        $formTypes .= "
                                    <div class=\"col s$columns input-field\">
                                    <select name='$id' id='$id' class=\"select2 browser-default\">
                                    <option value=''></option>
                                ";
                                if (isset($_POST['Select_valeur'][$key])){
                                    foreach($_POST['Select_valeur'][$key] as $cle => $valeur){
                                        $option_cle=$_POST['Select_cle'][$key][$cle];
                                        $option_val=$_POST['Select_valeur'][$key][$cle];
                                    $formTypes.="<option value=\"$option_cle\" {{(\$record->$id == '$option_cle') ? 'selected' : ''}}> $option_val </option> \n";
                                    }
                                }
                        $formTypes.="</select> <label for=\"$id\"> $label</label>
                                    @error('$id')
                                        <span class=\"helper-text materialize-red-text\">{{ \$message }}</span>
                                    @enderror
                                </div>
                                    ";
                        break;

                    case "select_multiple":
                        /*fputs ($createview, "
                        <tr>
                            <td width='30%' height='37' class='form-label' >
                                $label :
                            </td>
                            <td width='70%'>
                                <select  name='$id' id='$id' multiple tabindex=\"3\" data-placeholder=\"Choisir un $label ...\" class=\"chzn-select\"  style='width:95%;' >
                                    ");
                                if (isset($_POST['Select_multiple_valeur'][$key])){
                                    foreach($_POST['Select_multiple_valeur'][$key] as $cle => $valeur){
                                        $option_cle=$_POST['Select_multiple_cle'][$key][$cle];
                                        $option_val=$_POST['Select_multiple_valeur'][$key][$cle];
                                        fputs ($createview, "
                                        <option value='$option_cle'>$option_val</option>");
                                    }
                                }
                        fputs ($createview, "
                                </select>
                            </td>
                        </tr>") ;
                        break;*/
                        $formTypes.=FormType::getFormUpdateHtml($label, $id, $columns, "phone");
                    case "textarea":
                        $formTypes.=FormType::getFormUpdateHtml($label, $id, $columns, "textarea");
                        break;
                    case "ckeditor":
                        $formTypes.=FormType::getFormUpdateHtml($label, $id, $columns, "phone");
                        break;
                    case "radio":
                        $formTypes .= "
                                    <div class=\"col s$columns input-field\">
                                    <p><label><h6>$label :</h6></label></p><p>
                                ";
                        if (isset($_POST['Radio_valeur'][$key])){
                            foreach($_POST['Radio_valeur'][$key] as $cle => $valeur){
                                $option_cle=$_POST['Radio_cle'][$key][$cle];
                                $option_val=$_POST['Radio_valeur'][$key][$cle];
                                $formTypes.="<label><input class='with-gap' {{(\$record->$id == '$option_cle') ? 'checked' : ''}} name='$id' value='$option_cle' type='radio'><span>$option_val</span></label> \n";
                            }
                        }
                        $formTypes.="</p>
                                    @error('$id')
                                        <span class=\"helper-text materialize-red-text\">{{ \$message }}</span>
                                    @enderror
                                </div>
                                    ";
                        break;
                    case "checkbox":
                        $formTypes.=FormType::getFormUpdateHtml($label, $id, $columns, "checkbox");
                        break;
                    case "datepicker":
                        $formTypes.=FormType::getFormUpdateHtml($label, $id, $columns, "datepicker");
                        break;
                    case "timepicker":
                        $formTypes.=FormType::getFormUpdateHtml($label, $id, $columns, "timepicker");
                        break;
                    case "colorpicker":
                        $formTypes.=FormType::getFormUpdateHtml($label, $id, $columns, "colorpicker");
                        break;
                    case "file":
                        $formTypes.=FormType::getFormUpdateHtml($label, $id, $columns, "file");
                        break;
                    case "phone":
                        $formTypes.=FormType::getFormUpdateHtml($label, $id, $columns, 'phone');
                        break;
                    default:
                        $formTypes.=FormType::getFormUpdateHtml($label, $id, $columns);
                        break;
                }
            }
        }

        $contenu=file_get_contents(base_path()."/packages/easycollab/quicky/src/Templates/Update.php"); 
		$contenu=str_replace('{projetId}', strtolower($_POST['projet']), $contenu);
		$contenu=str_replace('{formTypes}', $formTypes, $contenu);
        $path= base_path().'/resources/views/back/'.strtolower($_POST['projet']);
        if (!is_dir($path)) { @mkdir($path); }
		$file=fopen($path.'/update.blade.php','w+'); 
		fwrite($file,$contenu); 
		fclose($file);
    }

    public static function addRoutes(){
        $projetId = strtolower($_POST['projet']);
        $controller = ucfirst($_POST['projet'])."Controller";
        $routes = "Route::any('/$projetId/list', '$controller@list')->name('{$projetId}_list');\nRoute::any('/$projetId/create', '$controller@create')->name('{$projetId}_create');\nRoute::any('/$projetId/update/{{$projetId}}', '$controller@update')->name('{$projetId}_update');\nRoute::any('/$projetId/delete/{{$projetId}}', '$controller@delete')->name('{$projetId}_delete');";
        $contenu=file_get_contents(base_path()."/routes/web.php");
        $contenu.="\n\n// $projetId \n".$routes;
        $routesFile=fopen(base_path()."/routes/web.php",'w+');
        fwrite($routesFile,$contenu);
        fclose($routesFile);
    }

    public static function genModelFile(){
        $contenu=file_get_contents(base_path()."/packages/easycollab/quicky/src/Templates/Model.php");
        $contenu=str_replace('{CLASS_NAME}', ucfirst(strtolower($_POST['projet'])) , $contenu);
        $contenu=str_replace('{TABEL_NAME}', strtolower($_POST['projet']).'s', $contenu);
        $fkFunctions = "";
        foreach($_POST['Identifiant'] as $key => $value){
            if ($_POST['del'][$key] == 0){
                $id=$_POST['Identifiant'][$key];
                $type=$_POST['formElement'][$key];

                if($type == 'secondary_key'){
                    $model= strtolower($_POST['skmodel'][$key]);
                    $ucModel = ucfirst(strtolower($_POST['skmodel'][$key]));
                    $fkFunctions .= "\tpublic function {$model}Detail(){
            return \$this->belongsTo(\App\Models\\$ucModel::class, '$id');
        } \n";
                }
            }
        }

        $contenu=str_replace('{FK}', $fkFunctions, $contenu);



        $modelFile=fopen(base_path().'/app/Models/'.ucfirst(strtolower($_POST['projet'])).'.php','w+');
        fwrite($modelFile,$contenu);
        fclose($modelFile);
    }

    public static function genControllerFile(){
        $contenu=file_get_contents(base_path()."/packages/easycollab/quicky/src/Templates/Controller.php");
        $contenu=str_replace('{Model}', ucfirst(strtolower($_POST['projet'])) , $contenu);
        $contenu=str_replace('{projetId}', strtolower($_POST['projet']), $contenu);
        $viewsData = "\n";
        foreach($_POST['Identifiant'] as $key => $value){
            if ($_POST['del'][$key] == 0){
                $id=$_POST['Identifiant'][$key];
                $type=$_POST['formElement'][$key];

                if($type == 'secondary_key'){
                    $model= strtolower($_POST['skmodel'][$key]);
                    $ucModel = ucfirst(strtolower($_POST['skmodel'][$key]));
                    $viewsData .= "\t\t\$viewsData['{$model}Records'] = \App\Models\\$ucModel::all()->where('deleted','0');\n";
                }
            }
        }

        $contenu=str_replace('{viewsData}', $viewsData, $contenu);


        $modelFile=fopen(base_path().'/app/Http/Controllers/'.ucfirst(strtolower($_POST['projet'])).'Controller.php','w+');
        fwrite($modelFile,$contenu);
        fclose($modelFile);
    }

    public static function genMigrationFile(){
		$contenu=file_get_contents(base_path()."/packages/easycollab/quicky/src/Templates/Migration.php"); 
		$contenu=str_replace('{Model}', ucfirst(strtolower($_POST['projet'])) , $contenu); 
		$contenu=str_replace('{projetId}', strtolower($_POST['projet']), $contenu);
        
        $columns = "";
        foreach($_POST['Identifiant'] as $key => $value){
            if ($_POST['del'][$key] == 0){
                $id=$_POST['Identifiant'][$key];
                $type=$_POST['formElement'][$key];
                switch ($type) {
                    /*case "primary_key":
                        $columns.= " `$id` INT( 50 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,";
                        break;*/
                    case "secondary_key":
                        $columns.= "\t\t\t\$table->string('$id')->nullable(true);\n";
                        break;
                    case "text":
                        $columns.= "\t\t\t\$table->string('$id')->nullable(true);\n";
                        break;
                    case "select":
                        $columns.= "\t\t\t\$table->string('$id')->nullable(true);\n";
                        break;
                    case "select_basic":
                        $columns.= "\t\t\t\$table->string('$id')->nullable(true);\n";
                        break;
                    case "select_multiple":
                        $columns.= "\t\t\t\$table->string('$id')->nullable(true);\n";
                        break;
                    case "select_multiple_basic":
                        $columns.= "\t\t\t\$table->string('$id')->nullable(true);\n";
                        break;
                    case "textarea":
                        $columns.= "\t\t\t\$table->longText('$id')->nullable(true);\n";
                        break;
                    case "ckeditor":
                        $columns.= "\t\t\t\$table->longText('$id')->nullable(true);\n";
                        break;
                    case "radio":
                        $columns.= "\t\t\t\$table->string('$id')->nullable(true);\n";
                        break;
                    case "checkbox":
                        $columns.= "\t\t\t\$table->string('$id')->nullable(true);\n";
                        break;
                    case "datepicker":
                        $columns.= "\t\t\t\$table->string('$id')->nullable(true);\n";
                        break;
                    case "colorpicker":
                        $columns.= "\t\t\t\$table->string('$id')->nullable(true);\n";
                        break;
                    case "file":
                        $columns.= "\t\t\t\$table->string('$id')->nullable(true);\n";
                        break;
                    case "phone":
                        $columns.= "\t\t\t\$table->string('$id')->nullable(true);\n";
                        break;
                    case "?":
                        $columns.= "\t\t\t\$table->string('$id')->nullable(true);\n";
                        break;
                    default:
                        $columns.= "\t\t\t\$table->string('$id')->nullable(true);\n";
                        break;
                }
            }
        }

        $contenu=str_replace('{Columns}', $columns, $contenu);

		$modelFile=fopen(base_path().'/database/migrations/'.date("Y_m_d_His")."_".strtolower($_POST['projet'])."_table.php",'w+'); 
		fwrite($modelFile,$contenu); 
		fclose($modelFile);
	}

    public static function getSql()
    {
        $sql="CREATE TABLE `".strtolower($_POST['projet']).'s'."` ( ";
        if(!in_array("primary_key",$_POST['formElement'])){
            $sql.= " `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,";
        }
        foreach($_POST['Identifiant'] as $key => $value){
            if ($_POST['del'][$key] == 0){
                $id=$_POST['Identifiant'][$key];
                $type=$_POST['formElement'][$key];
                switch ($type) {
                    case "primary_key":
                        $sql.= " `$id` INT( 50 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,";
                        break;
                    case "secondary_key":
                        $sql.= " `$id` VARCHAR( 250 ) ,";
                        break;
                    case "text":
                        $sql.= " `$id` VARCHAR( 250 ) ,";
                        break;
                    case "select":
                        $sql.= " `$id` VARCHAR( 250 ) ,";
                        break;
                    case "select_basic":
                        $sql.= " `$id` VARCHAR( 250 ) ,";
                        break;
                    case "select_multiple":
                        $sql.= " `$id` VARCHAR( 250 ) ,";
                        break;
                    case "select_multiple_basic":
                        $sql.= " `$id` VARCHAR( 250 ) ,";
                        break;
                    case "textarea":
                        $sql.= " `$id` TEXT ,";
                        break;
                    case "ckeditor":
                        $sql.= " `$id` TEXT ,";
                        break;
                    case "radio":
                        $sql.= " `$id` VARCHAR( 250 ) ,";
                        break;
                    case "checkbox":
                        $sql.= " `$id` VARCHAR( 250 ) ,";
                        break;
                    case "datepicker":
                        $sql.= " `$id` VARCHAR( 250 ) ,";
                        break;
                    case "colorpicker":
                        $sql.= " `$id` VARCHAR( 250 ) ,";
                        break;
                    case "file":
                        $sql.= " `$id` VARCHAR( 250 ) ,";
                        break;
                    case "phone":
                        $sql.= " `$id` VARCHAR( 250 ) ,";
                        break;
                    case "?":
                        $sql.= " `$id` VARCHAR( 250 ) ,";
                        break;
                    default:
                        $sql.= " `$id` VARCHAR( 250 ) ,";
                        break;
                }
            }
        }
        $sql.= "
                `created_at` timestamp NULL DEFAULT NULL,
                `created_by` int(11) DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `updated_by` int(11) DEFAULT NULL,
                `deleted` int(11) NOT NULL DEFAULT 0,
                `deleted_at` timestamp NULL DEFAULT NULL,
                `deleted_by` int(11) DEFAULT NULL )";
        return $sql;
    }

}