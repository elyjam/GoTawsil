<?php
namespace EasyCollab\Quicky\Models;

use Illuminate\Database\Eloquent\Model;

class FormType
{
    public static function getFormCreateHtml($label, $id, $columns, $type="text"){

        switch ($type) {
            case 'text':
                return "                
                                        <div class=\"col s$columns input-field\">
                                            <input id=\"$id\" name=\"$id\" value=\"{{old('$id')}}\" autocomplete=\"off\"
                                            readonly onfocus=\"this.removeAttribute('readonly');\" type=\"text\">
                                            <label for=\"$id\"> $label </label>
                                            @error('$id')
                                                <span class=\"helper-text materialize-red-text\">{{ \$message }}</span>
                                            @enderror
                                        </div>";
                break;
            case 'datepicker':
                return "                
                                        <div class=\"col s$columns input-field\">
                                            <input id=\"$id\" name=\"$id\" value=\"{{old('$id')}}\" autocomplete=\"off\"
                                            readonly onfocus=\"this.removeAttribute('readonly');\" type=\"text\" class=\"datepicker\">
                                            <label for=\"$id\"> $label </label>
                                            @error('$id')
                                                <span class=\"helper-text materialize-red-text\">{{ \$message }}</span>
                                            @enderror
                                        </div>";
                break;
            case 'timepicker':
                return "                
                                        <div class=\"col s$columns input-field\">
                                            <input id=\"$id\" name=\"$id\" value=\"{{old('$id')}}\" autocomplete=\"off\"
                                            readonly onfocus=\"this.removeAttribute('readonly');\" type=\"text\" class=\"timepicker\">
                                            <label for=\"$id\"> $label </label>
                                            @error('$id')
                                                <span class=\"helper-text materialize-red-text\">{{ \$message }}</span>
                                            @enderror
                                        </div>";
                break;
            case 'textarea':
                return "                        
                                        <div class=\"col s$columns input-field\">
                                            <textarea d=\"$id\" name=\"$id\" class=\"materialize-textarea\"></textarea>
                                            <label for=\"$id\"> $label </label>
                                            @error('$id')
                                                <span class=\"helper-text materialize-red-text\">{{ \$message }}</span>
                                            @enderror
                                        </div>";
                break;
            
            default:
            return "                        
                                            <div class=\"col s$columns input-field\">
                                                <input id=\"$id\" name=\"$id\" value=\"{{old('$id')}}\" autocomplete=\"off\"
                                                readonly onfocus=\"this.removeAttribute('readonly');\" type=\"text\">
                                                <label for=\"$id\"> $label </label>
                                                @error('$id')
                                                    <span class=\"helper-text materialize-red-text\">{{ \$message }}</span>
                                                @enderror
                                            </div>";
                break;
        }
    }

    public static function getFormUpdateHtml($label, $id, $columns, $type="text"){

        switch ($type) {
            case 'text':
                return "                
                                        <div class=\"col s$columns input-field\">
                                            <input id=\"$id\" name=\"$id\" value=\"{{old('$id', \$record->$id)}}\" autocomplete=\"off\"
                                            readonly onfocus=\"this.removeAttribute('readonly');\" type=\"text\">
                                            <label for=\"$id\"> $label </label>
                                            @error('$id')
                                                <span class=\"helper-text materialize-red-text\">{{ \$message }}</span>
                                            @enderror
                                        </div>";
                break;
            case 'datepicker':
                return "                
                                        <div class=\"col s$columns input-field\">
                                            <input id=\"$id\" name=\"$id\" value=\"{{old('$id', \$record->$id)}}\" autocomplete=\"off\"
                                            readonly onfocus=\"this.removeAttribute('readonly');\" type=\"text\" class=\"datepicker\">
                                            <label for=\"$id\"> $label </label>
                                            @error('$id')
                                                <span class=\"helper-text materialize-red-text\">{{ \$message }}</span>
                                            @enderror
                                        </div>";
                break;
            case 'timepicker':
                return "                
                                        <div class=\"col s$columns input-field\">
                                            <input id=\"$id\" name=\"$id\" value=\"{{old('$id', \$record->$id)}}\" autocomplete=\"off\"
                                            readonly onfocus=\"this.removeAttribute('readonly');\" type=\"text\" class=\"timepicker\">
                                            <label for=\"$id\"> $label </label>
                                            @error('$id')
                                                <span class=\"helper-text materialize-red-text\">{{ \$message }}</span>
                                            @enderror
                                        </div>";
                break;
            case 'textarea':
                return "                
                                        <div class=\"col s$columns input-field\">
                                            <textarea d=\"$id\" name=\"$id\" class=\"materialize-textarea\">{{old('$id', \$record->$id)}}</textarea>
                                            <label for=\"$id\"> $label </label>
                                            @error('$id')
                                                <span class=\"helper-text materialize-red-text\">{{ \$message }}</span>
                                            @enderror
                                        </div>";
                break;
            
            default:
            return "                        
                                            <div class=\"col s$columns input-field\">
                                                <input id=\"$id\" name=\"$id\" value=\"{{old('$id', \$record->$id)}}\" autocomplete=\"off\"
                                                readonly onfocus=\"this.removeAttribute('readonly');\" type=\"text\">
                                                <label for=\"$id\"> $label </label>
                                                @error('$id')
                                                    <span class=\"helper-text materialize-red-text\">{{ \$message }}</span>
                                                @enderror
                                            </div>";
                break;
        }
        
        
    }
    

}