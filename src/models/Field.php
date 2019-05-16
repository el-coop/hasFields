<?php

namespace ElCoop\HasFields\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model {
	
	protected $casts = [
		'options' => 'array'
	];

	static function getFieldsByType($type, $models){
			$requiredFields = Field::where('status', $type)->whereIn('form', $models)->get();
			return $requiredFields->mapWithKeys(function ($field) use (&$rules) {
				$dataName = strtolower(substr($field->form, strrpos($field->form, '\\') + 1));
				return ["{$dataName}.{$field->id}" => 'required'];
			});
	}
	
}
	
