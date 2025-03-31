<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Forms\Form;

class AddPriceToSelectOptions extends Migration
{
    public function up()
    {
        // This is a JSON migration - we need to update existing form data
        $forms = Form::all();
        
        foreach ($forms as $form) {
            $properties = $form->properties;
            
            if (isset($properties['fields']) && is_array($properties['fields'])) {
                foreach ($properties['fields'] as &$field) {
                    if ($field['type'] === 'select' && isset($field['options']) && is_array($field['options'])) {
                        foreach ($field['options'] as &$option) {
                            // Add price property if it doesn't exist
                            if (!isset($option['price'])) {
                                $option['price'] = null;
                            }
                        }
                    }
                }
            }
            
            $form->properties = $properties;
            $form->save();
        }
    }

    public function down()
    {
        $forms = Form::all();
        
        foreach ($forms as $form) {
            $properties = $form->properties;
            
            if (isset($properties['fields']) && is_array($properties['fields'])) {
                foreach ($properties['fields'] as &$field) {
                    if ($field['type'] === 'select' && isset($field['options']) && is_array($field['options'])) {
                        foreach ($field['options'] as &$option) {
                            // Remove price property
                            if (isset($option['price'])) {
                                unset($option['price']);
                            }
                        }
                    }
                }
            }
            
            $form->properties = $properties;
            $form->save();
        }
    }
}
