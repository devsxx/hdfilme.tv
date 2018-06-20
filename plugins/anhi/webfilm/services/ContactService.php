<?php

namespace Anhi\WebFilm\Services;

use Auth, Input, Helper, DB;

use Validator;

use Anhi\Movie\Models\Movie;

use Anhi\Movie\Models\Contact;

class ContactService
{
	
	function save($data)
	{
		$this->validate($data);

		$this->insertContact($data);
	}

	private function insertContact ($data)
	{
		$data['created_at'] = (new \DateTime)->format('Y-m-d h:i:s');
		$result = Contact::insert($data);

		if (!$result)
			throw new \Exception("Failed to insert data");
			
	}

	function getErrors ()
	{
		return $this->validate;
	}

	private function validate ($data)
	{
		$rules = [
			'name' => 'required',
            'email' => 'required|email',
            'content' => 'required',
		];

		$validate = Validator::make($data, $rules);

		$this->validate = $validate;

		if ($validate->fails())
			throw new \Exception("Validations fails");
			
	}
}