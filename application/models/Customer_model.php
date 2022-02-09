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
	public $idField = "id";
	public $idJsonKey = "id";


	/**
	 * @OA\Property()
	 * @var string
	 */
	public $nik;
	public $nikField = "nik";
	public $nikJsonKey = "nik";


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
	public $imageIdJField = "photo_image_id";
	public $imageIdJsonKey = "imageId";


	/**
	 * @OA\Property()
	 * @var string
	 */
	public $fullName;
	public $fullNameField = "full_name";
	public $fullNamseJsonKey = "fullName";


	/**
	 * @OA\Property()
	 * @var string
	 */
	public $genderId;
	public $genderIdField = "gender_id";
	public $genderIdJsonKey = "genderId";


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
	public $cityIdField = "city_id";
	public $cityIdJsonKey = "cityId";


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
	public $phoneNumberField = "phone_number";
	public $phoneNumberJsonKey = "phoneNumber";


	/**
	 * @OA\Property()
	 * @var string
	 */
	public $username;
	public $usernameField = "username";
	public $usernameJsonKey = "username";


	/**
	 * @OA\Property()
	 * @var int
	 */
	public $level;
	public $levelField = "level";
	public $levelJsonKey = "level";


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
		if (isset($json[$this->idJsonKey])) {
			$this->id = $json[$this->idJsonKey];
		}
		if (isset($json[$this->nikJsonKey])) {
			$this->nik = $json[$this->nikJsonKey];
		}
		if (isset($json[$this->imageIdJsonKey])) {
			$this->imageId = $json[$this->imageIdJsonKey];
		}
		if (isset($json[$this->fullNamseJsonKey])) {
			$this->fullName = $json[$this->fullNamseJsonKey];
		}
		if (isset($json[$this->genderIdJsonKey])) {
			$this->genderId = $json[$this->genderIdJsonKey];
		}
		if (isset($json[$this->cityIdJsonKey])) {
			$this->cityId = $json[$this->cityIdJsonKey];
		}
		if (isset($json[$this->phoneNumberJsonKey])) {
			$this->phoneNumber = $json[$this->phoneNumberJsonKey];
		}
		if (isset($json[$this->usernameJsonKey])) {
			$this->username = $json[$this->usernameJsonKey];
		}


		return $this;
	}
}
