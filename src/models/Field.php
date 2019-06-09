<?php

namespace ElCoop\HasFields\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model {

	protected $casts = [
		'options' => 'array'
	];

	static function getFieldsByType($type, $models) {
		$requiredFields = Field::where('status', $type)->whereIn('form', $models)->get();
		return $requiredFields->mapWithKeys(function ($field) use (&$rules) {
			$dataName = strtolower(substr($field->form, strrpos($field->form, '\\') + 1));
			return ["{$dataName}.{$field->id}" => 'required'];
		});
	}

	static function getRequiredFields(...$models) {
		$requiredFields = Field::where('status', 'required')->whereIn('form', $models)->get();
		return $requiredFields->mapWithKeys(function ($field) use (&$rules) {
			$dataName = strtolower(substr($field->form, strrpos($field->form, '\\') + 1));
			if ($field->type == 'checkbox') {
				return ["{$dataName}.{$field->id}" => 'accepted'];
			}
			return ["{$dataName}.{$field->id}" => 'required'];
		});
	}

	static function getProtectedFields(...$models) {
		$requiredFields = Field::where('status', 'protected')->whereIn('form', $models)->get();
		return $requiredFields->mapWithKeys(function ($field) use (&$rules) {
			$dataName = strtolower(substr($field->form, strrpos($field->form, '\\') + 1));
			if ($field->type == 'checkbox') {
				return ["{$dataName}.{$field->id}" => 'accepted'];
			}
			return ["{$dataName}.{$field->id}" => 'required'];
		});
	}

}
	
