<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreInvoiceRequest extends Request {

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

        $rules = [
            'agent_id'=>'numeric|required',
            'edition_id'=>'numeric|required',
            'issue_date'=>'required|date_format:d-m-Y'
        ];
        return $rules;
	}

}
