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

	function fromRow($row)
	{
		$data = new Customer_model();
		$data->id = $row->id;
		$data->nik = $row->nik;
		$data->imageId = $row->photo_image_id;
		$data->imageUrl = $this->file->fromId($row->photo_image_id)->createUrl();
		$data->fullName = $row->full_name;
		$data->genderId = $row->gender_id;
		$data->genderName = $this->gender->fromId($row->gender_id)->name;
		$data->cityId = $row->city_id;
		$data->cityName = $this->city->fromId($row->city_id)->name;
		$data->phoneNumber = $row->phone_number;
		$data->phoneNumber = $row->phone_number;
		$data->username = $row->username;
		$data->level =  $row->level;

		return $data;
	}


	function get_customer($q)
	{
		return $this->db->get_where($this->tableName, $q);
	}

	function fromJson($json): Customer_model
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
			$this->city = $json[$this->cityIdJsonKey()];
		}
		if (isset($json[$this->phoneNumberJsonKey()])) {
			$this->phoneNumber = $json[$this->phoneNumberJsonKey()];
		}
		if (isset($json[$this->usernameJsonKey()])) {
			$this->username = $json[$this->usernameJsonKey()];
		}
		if (isset($json[$this->levelJsonKey()])) {
			$this->level = $json[$this->levelJsonKey()];
		}
		

		return $this;
	}
}
