<?php

namespace App\Http\Requests;

class GenreStoreRequest extends BaseFormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name'				=> 'required|string|max:255|unique:genres,name',
			'description'	=> 'required|string|max:15000',
		];
	}

	/**
	 *  Filters to be applied to the input.
	 *
	 * @return array
	 */
	public function filters()
	{
		return [
			'name'				=> 'trim|capitalize|strip_tags',
			'description'	=> 'trim|strip_tags'
		];
	}
}
