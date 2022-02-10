<?php

if (!defined('BASEPATH')) exit('No direct script allowed');
require_once APPPATH . '/libraries/JWT.php';

use \Firebase\JWT\JWT;

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
	public $deviceId;
	public function deviceIdField(): string
	{
		return "device_id";
	}
	public function deviceIdJsonKey(): string
	{
		return "deviceId";
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
		$data->password = $row->password;
		$data->level =  $row->level;
		$data->deviceId =  $row->device_id;

		return $data;
	}


	public function  login(\User_model $user)
	{
		try {
			$query = $this->db->get_where($this->tableName, array(
				$this->usernameField() => $user->username
			));

			if ($query->num_rows() == 0) {
				throw new Exception("username " . $user->username . " not found");
			}

			$customer = $this->fromRow($query->row());
			if (sha1($user->password) == $customer->password) {  //Condition if password matched
				$token['id'] = $customer->id;  //From here
				$token['username'] = $customer->username;
				$token['type'] = "customer";
				$date = new DateTime();
				$token['iat'] = $date->getTimestamp();
				$token['exp'] = $date->getTimestamp() + 60 * 60 * 5; //To here is to generate token
				$output['token'] = JWT::encode($token,  $this->config->item('thekey')); //This is the output token

				//result the user
				$customer->deviceId = $user->deviceId;
				$customer = $customer->update();
				$customer->token = $output['token'];
				return $customer;
			} else {
				throw new Exception("password is invalid");
			}
		} catch (\Exception $e) {
			throw $e;
		}
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
		$data = new Customer_model();
		if (isset($json[$this->idjsonKey()])) {
			$data->id = $json[$this->idjsonKey()];
		}
		if (isset($json[$this->nikJsonKey()])) {
			$data->nik = $json[$this->nikField()];
		}
		if (isset($json[$this->imageIdJsonKey()])) {
			$data->imageId = $json[$this->imageIdJsonKey()];
		}
		if (isset($json[$this->fullNamseJsonKey()])) {
			$data->fullName = $json[$this->fullNamseJsonKey()];
		}
		if (isset($json[$this->genderIdJsonKey()])) {
			$data->genderId = $json[$this->genderIdJsonKey()];
		}
		if (isset($json[$this->cityIdJsonKey()])) {
			$data->cityId = $json[$this->cityIdJsonKey()];
		}
		if (isset($json[$this->phoneNumberJsonKey()])) {
			$data->phoneNumber = $json[$this->phoneNumberJsonKey()];
		}
		if (isset($json[$this->usernameJsonKey()])) {
			$data->username = $json[$this->usernameJsonKey()];
		}
		if (isset($json[$this->passwordJsonKey()])) {
			$data->password = $json[$this->passwordJsonKey()];
		}
		if (isset($json[$this->levelJsonKey()])) {
			$data->level = $json[$this->levelJsonKey()];
		}
		if (isset($json[$this->deviceIdJsonKey()])) {
			$data->deviceId = $json[$this->deviceIdJsonKey()];
		}


		return $data;
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
			$this->deviceIdField() => $this->deviceId,
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

	/**
	 * @OA\Property()
	 * @var String
	 */
	public $deviceId;
}
