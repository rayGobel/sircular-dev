<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreReturnItemRequest extends Request {

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
            'number'=>'numeric|required',
            'edition_id'=>'required',
            'agent_id' =>'numeric|required',
            'date'=>'required|date'
            ];

        // Rules for edition_id and total arrays
        $edition_ids = $this->request->get('edition_id');
        // Incase of empty edition_ids, premature return
        if(!$edition_ids) {
            return $rules;
        }

        foreach($edition_ids as $key=>$value) {
            $rules['edition_id.'.$key] = 'required|numeric';
            $rules['total.'.$key] = 'required|numeric';
        }

        return $rules;
	}

    /**
     * Override separate edition_id arrays error message
     *
     * @return Array $message
     */
    public function messages()
    {
        $messages = [];
        if (empty($this->request->get('edition_id'))) {
            $messages["edition_id.required"] = "Must supply edition!";
            return $messages;

        }
        foreach($this->request->get('edition_id') as $key=>$val) {
            $messages["edition_id.{$key}.numeric"] = "Invalid Edition ID on entry # {$key}";
            $messages["total.{$key}.numeric"] = "Must enter a number on entry # {$key}";
        }

        return $messages;
    }


}
