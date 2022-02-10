<?php

use Customer_model as GlobalCustomer_model;

if (!defined('BASEPATH')) exit('No direct script allowed');

/**
 * @OA\Schema(schema="CustomerModel")
 */
class Customer_model extends CI_Model
{
	private $tableName = "m_customer";
	/**
	 * @OA\Property()
	 * @var int
	 */
	public $id;
	public function idField(): string
	{
		return "id";
	}
	public function idJsonKey(): string
	{
		return "id";
	}

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $nik;
	public function nikField(): string
	{
		return "nik";
	}
	public function nikJsonKey(): string
	{
		return "nik";
	}

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $imageUrl;

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $imageId;
	public function imageIdJField(): string
	{
		return "photo_image_id";
	}
	public function imageIdJsonKey(): string
	{
		return "imageId";
	}

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $fullName;
	public function fullNameField(): string
	{
		return "full_name";
	}
	public function fullNamseJsonKey(): string
	{
		return "fullName";
	}

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $genderId;
	public function genderIdField(): string
	{
		return "gender_id";
	}
	public function genderIdJsonKey(): string
	{
		return "genderId";
	}

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $genderName;

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $cityId;
	public function cityIdField(): string
	{
		return "city_id";
	}
	public function cityIdJsonKey(): string
	{
		return "cityId";
	}

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $cityName;

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $phoneNumber;
	public function phoneNumberField(): string
	{
		return "phone_number";
	}
	public function phoneNumberJsonKey(): string
	{
		return "phoneNumber";
	}

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $username;
	public function usernameField(): string
	{
		return "username";
	}
	public function usernameJsonKey(): string
	{
		return "username";
	}

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $password;
	public function passwordField(): string
	{
		return "password";
	}
	public function passwordJsonKey(): string
	{
		return "password";
	}

	/**
	 * @OA\Property()
	 * @var int
	 */
	public $level;
	public function levelField(): string
	{
		return "level";
	}
	public function levelJsonKey(): string
	{
		return "level";
	}

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $token;

	function __construct()
	{
		// Construct the parent class
		parent::__construct();
		$this->load->model('File_model', 'file');
		$this->load->model('City_model', 'city');
		$this->load->model('Gender_model', 'gender');
	}

	public function  fromRow($row)
	{
		$this->id = $row->id;
		$this->nik = $row->nik;
		$this->imageId = $row->photo_image_id;
		$this->imageUrl = $this->file->fromId($row->photo_image_id)->createUrl();
		$this->fullName = $row->full_name;
		$this->genderId = $row->gender_id;
		$this->genderName = $this->gender->fromId($row->gender_id)->name;
		$this->cityId = $row->city_id;
		$this->cityName = $this->city->fromId($row->city_id)->name;
		$this->phoneNumber = $row->phone_number;
		$this->phoneNumber = $row->phone_number;
		$this->username = $row->username;
		$this->password = $row->password;
		$this->level =  $row->level;
		
		return $this ;
	}


	public function  get_customer($q)
	{
		return $this->db->get_where($this->tableName, $q);
	}

	public function  fromId($id)
	{
		$data = $this->db->get_where($this->tableName, array($this->idField() => $id));
		$result = $data->result();

		if (count($result) > 0) {
			return $this->fromRow($result[0]);
		} else {
			return new Customer_model();
		}
	}

	public function  fromJson($json): Customer_model
	{
		if (isset($json[$this->idjsonKey()])) {
			$this->id = $json[$this->idjsonKey()];
		}
		if (isset($json[$this->nikJsonKey()])) {
			$this->nik = $json[$this->nikField()];
		}
		if (isset($json[$this->imageIdJsonKey()])) {
			$this->imageId = $json[$this->imageIdJsonKey()];
		}
		if (isset($json[$this->fullNamseJsonKey()])) {
			$this->fullName = $json[$this->fullNamseJsonKey()];
		}
		if (isset($json[$this->genderIdJsonKey()])) {
			$this->genderId = $json[$this->genderIdJsonKey()];
		}
		if (isset($json[$this->cityIdJsonKey()])) {
			$this->cityId = $json[$this->cityIdJsonKey()];
		}
		if (isset($json[$this->phoneNumberJsonKey()])) {
			$this->phoneNumber = $json[$this->phoneNumberJsonKey()];
		}
		if (isset($json[$this->usernameJsonKey()])) {
			$this->username = $json[$this->usernameJsonKey()];
		}
		if (isset($json[$this->passwordJsonKey()])) {
			$this->password = $json[$this->passwordJsonKey()];
		}
		if (isset($json[$this->levelJsonKey()])) {
			$this->level = $json[$this->levelJsonKey()];
		}


		return $this;
	}

	public function  add(): Customer_model
	{
		try {
			//generate key
			$this->id = null;
			$this->password = $this->password == "" ? $this->username :  $this->password;
			$this->password = sha1($this->password);
			$this->level = 1;

			$this->db->insert($this->tableName, $this->toArray());

			$data = $this->db->get_where($this->tableName, array($this->usernameField() => $this->username));

			return $this->fromRow($data->result()[0]);
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function  update(): Customer_model
	{
		try {
			if ($this->id != null) {
				$data = $this->toArray();
				unset($data[$this->idField()]);
				$this->db->update($this->tableName, $data, array(
					$this->idField() => $this->id
				));
				return  $this->fromId($this->id);
			} else {
				return $this;
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function  toArray(): array
	{
		$data = array(
			$this->idField() => $this->id,
			$this->nikField() => $this->nik,
			$this->imageIdJField() => $this->imageId,
			$this->fullNameField() => $this->fullName,
			$this->genderIdField() => $this->genderId,
			$this->cityIdField() => $this->cityId,
			$this->phoneNumberField() => $this->phoneNumber,
			$this->usernameField() => $this->username,
			$this->passwordField() => $this->password,
			$this->levelField() => $this->level,
		);

		return $data;
	}

	public function checkUsernameExist(): bool
	{
		$this->db->select('*');
		$this->db->from($this->tableName);

		$this->db->where($this->usernameField(), $this->username);

		$count = $this->db->count_all_results();

		return $count > 0 ? true : false;
	}

	public function checkPhoneExist(): bool
	{
		$this->db->select('*');
		$this->db->from($this->tableName);

		$this->db->where($this->phoneNumberField(), $this->phoneNumber);

		$count = $this->db->count_all_results();

		return $count > 0 ? true : false;
	}
}

/**
 * @OA\Schema(schema="CustomerRegister")
 */
class CustomerRegister
{
	/**
	 * @OA\Property()
	 * @var String
	 */
	public $nik;

	/**
	 * @OA\Property()
	 * @var String
	 */
	public $fullName;

	/**
	 * @OA\Property()
	 * @var String
	 */
	public $genderId;

	/**
	 * @OA\Property()
	 * @var String
	 */
	public $cityId;

	/**
	 * @OA\Property()
	 * @var String
	 */
	public $phoneNumber;

	/**
	 * @OA\Property()
	 * @var String
	 */
	public $username;

	/**
	 * @OA\Property()
	 * @var String
	 */
	public $password;
}
