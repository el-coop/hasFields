<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 08/11/2018
 * Time: 15:29
 */

namespace ElCoop\HasFields;


use App;
use ElCoop\HasFields\Models\Field;

trait HasFields {
	
	protected static $customFields;
	protected static $encryptedFields;

	/**
	 * @return mixed
	 */

	private static function getFieldName (){
		if (property_exists(static::class, 'fieldClass')){
			return static::$fieldClass;
		}
		return static::class;
	}

	public  static function fields() {
		$field = static::getFieldName();
		if (!static::$customFields) {
			static::$customFields = Field::where('form', $field)->orderBy('order')->get();
			static::$encryptedFields = static::$customFields->where('status', 'encrypted')->pluck('id');
		}
		return static::$customFields;
	}
	
	public static function getLastFieldOrder() {
		$field = static::getFieldName();
		return Field::where('form', $field)->max('order');
	}
	
	public function getDataAttribute($values) {
		$values = collect(json_decode($values));
		
		if (!static::$encryptedFields) {
			static::fields();
		}
		
		return $values->map(function ($value, $index) {
			if ($value != '' && static::$encryptedFields->contains($index)) {
				$value = decrypt($value);
			}
			return $value;
		});
	}
	
	public function setDataAttribute($values) {
		if (!static::$encryptedFields) {
			static::fields();
		}
		
		$this->attributes['data'] = collect($values)->map(function ($value, $index) {
			if (static::$encryptedFields->contains($index)) {
				$value = encrypt($value);
			}
			return $value;
		});
		
	}
	
	public function getFieldsData() {
		$field = static::getFieldName();
		
		$dataName = strtolower(substr($field, strrpos($field, '\\') + 1));
		
		return static::fields()->map(function ($item) use ($dataName) {
			$checked = false;
			if ($item->type == 'checkbox'){
				if ($this->data[$item->id] == 1){
					$checked = true;
				}
			}
			return [
				'name' => "{$dataName}[{$item->id}]",
				'label' => $item->{'name_' . App::getLocale()},
				'type' => $item->type,
				'value' => $this->data[$item->id] ?? '',
				'placeholder' => $item->{'placeholder_' . App::getLocale()},
				'checked' => $checked
			];
		});
	}
}
