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
	public function imageUrlJsonKey(): string
	{
		return "imageUrl";
	}

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $imageId;
	public function imageIdField(): string
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
	public function genderNameJsonKey(): string
	{
		return "genderName";
	}


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
	public function cityNameJsonKey(): string
	{
		return "cityName";
	}


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

	public $otp;
	public function otpJsonKey(): string
	{
		return "otp";
	}
	public function otpField(): string
	{
		return "otp";
	}

	public $isVerifiedPhone;
	public function isVerifiedPhoneJsonKey(): string
	{
		return "isVerifiedPhone";
	}
	public function isVerifiedPhoneField(): string
	{
		return "is_verified_phone";
	}

	public $otpValidDate;
	public function otpValidDateJsonKey(): string
	{
		return "otpValidDate";
	}
	public function otpValidDateField(): string
	{
		return "otp_valid_date";
	}


	/**
	 * @OA\Property()
	 * @var double
	 */
	public $lat;
	public function latField(): string
	{
		return "lat";
	}
	public function latJsonKey(): string
	{
		return "lat";
	}

	/**
	 * @OA\Property()
	 * @var double
	 */
	public $lon;
	public function lonField(): string
	{
		return "lon";
	}
	public function lonJsonKey(): string
	{
		return "lon";
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
		$data->id = $row->${$this->idField()};
		$data->nik = $row->${$this->nikField()};
		$data->imageId = $row->${$this->imageIdField()};
		$data->imageUrl = $this->file->fromId($row->${$this->imageIdField()})->createUrl();
		$data->fullName = $row->{$this->fullNameField()};
		$data->genderId = $row->{$this->genderIdField()};
		$data->genderName = $this->gender->fromId($row->{$this->genderIdField()})->name;
		$data->cityId = $row->{$this->cityIdField()};
		$data->cityName = $this->city->fromId($row->{$this->cityIdField()})->name;
		$data->phoneNumber = $row->{$this->phoneNumberField()};
		$data->username = $row->{$this->usernameField()};
		$data->password = $row->{$this->passwordField()};
		$data->level =  $row->{$this->levelField()};
		$data->deviceId =  $row->{$this->deviceIdField()};
		$data->otp = $row->{$this->otpField()};
		$data->isVerifiedPhone = $row->{$this->isVerifiedPhoneField()};
		$data->otpValidDate = date_create(date(DATE_ATOM, strtotime($row->{$this->otpValidDateField()})));
		$data->lat = $row->{$this->latField()};
		$data->lon = $row->{$this->lonField()};

		return $data;
	}


	public function  login(\User_model $user, $direct = false, Customer_model &$refCustomer = null)
	{

		str_replace("", "", "", $dfdf);

		try {
			$query = $this->db->get_where($this->tableName, array(
				$this->usernameField() => $user->username
			));

			if ($query->num_rows() == 0) {
				throw new Exception("username " . $user->username . " not found");
			}

			$customer = $this->fromRow($query->row());
			$refCustomer = $customer;
			$password = $direct == false ? sha1($user->password) : $user->password;
			if ($password == $customer->password) {  //Condition if password matched
				$token['id'] = $customer->id;  //From here
				$token['username'] = $customer->username;
				$token['type'] = "customer";
				$date = new DateTime();
				$token['iat'] = $date->getTimestamp();
				$token['exp'] = $date->getTimestamp() + 60 * 60 * 5; //To here is to generate token
				$output['token'] = JWT::encode($token,  $this->config->item('thekey')); //This is the output token

				//result the user
				$customer->deviceId = $user->deviceId;
				$customer = $customer->update()->toPublic();
				$customer["token"] = $output['token'];
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

	public function  fromUsername($username)
	{
		$data = $this->db->get_where($this->tableName, array($this->usernameField() => $username));
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
		if (isset($json[$this->latJsonKey()])) {
			$data->lat = $json[$this->latJsonKey()];
		}
		if (isset($json[$this->lonJsonKey()])) {
			$data->lon = $json[$this->lonJsonKey()];
		}


		return $data;
	}

	public function toJson(): array
	{
		$data = array(
			$this->idJsonKey() => (int) $this->id,
			$this->nikJsonKey() => $this->nik,
			$this->imageIdJsonKey() => $this->imageId,
			$this->fullNamseJsonKey() => $this->fullName,
			$this->genderIdJsonKey() => $this->genderId,
			$this->cityIdJsonKey() => $this->cityId,
			$this->phoneNumberJsonKey() => $this->phoneNumber,
			$this->usernameJsonKey() => $this->username,
			$this->passwordJsonKey() => $this->password,
			$this->levelJsonKey() => $this->level,
			$this->deviceIdJsonKey() => $this->deviceId,
			$this->otpJsonKey() => $this->otp,
			$this->isVerifiedPhoneJsonKey() => $this->isVerifiedPhone,
			$this->otpValidDateJsonKey() => $this->otpValidDate->format(DateTime::ATOM), 
			$this->latJsonKey() => $this->lat,
			$this->lonJsonKey() => $this->lon,
		);

		return $data;
	}

	public function toPublic(): array
	{
		$data = array(
			$this->idJsonKey() => (int) $this->id,
			$this->nikJsonKey() => $this->nik,
			$this->imageIdJsonKey() => $this->imageId,
			$this->imageUrlJsonKey() =>  $this->file->fromId($this->imageId)->createUrl(),
			$this->fullNamseJsonKey() => $this->fullName,
			$this->genderIdJsonKey() => $this->genderId,
			$this->genderNameJsonKey() => $this->gender->fromId($this->genderId)->name,
			$this->cityIdJsonKey() => $this->cityId,
			$this->cityNameJsonKey() => $this->city->fromId($this->cityId)->name,
			$this->phoneNumberJsonKey() => $this->phoneNumber,
			$this->usernameJsonKey() => $this->username,
			$this->levelJsonKey() => (int) $this->level,
			$this->isVerifiedPhoneJsonKey() => $this->isVerifiedPhone,
			$this->latJsonKey() => $this->lat,
			$this->lonJsonKey() => $this->lon,
		);

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
			$result = $data->result();
			return $this->fromRow($result[0]);
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
			$this->imageIdField() => $this->imageId,
			$this->fullNameField() => $this->fullName,
			$this->genderIdField() => $this->genderId,
			$this->cityIdField() => $this->cityId,
			$this->phoneNumberField() => $this->phoneNumber,
			$this->usernameField() => $this->username,
			$this->passwordField() => $this->password,
			$this->levelField() => $this->level,
			$this->deviceIdField() => $this->deviceId,
			$this->otpField() => $this->otp,
			$this->isVerifiedPhoneField() => $this->isVerifiedPhone,
			$this->otpValidDateField() => $this->otpValidDate->format(DateTime::ATOM),
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

	public function readRegisterJson($json): CustomerRegister
	{
		$data = new CustomerRegister();
		if (isset($json["avatar"])) {
			$data->avatar = $json["avatar"];
		}
		if (isset($json["nik"])) {
			$data->nik = $json["nik"];
		}
		if (isset($json["fullName"])) {
			$data->fullName = [$json["fullName"]];
		}
		if (isset($json["genderId"])) {
			$data->genderId = $json["genderId"];
		}
		if (isset($json["cityId"])) {
			$data->cityId = $json["cityId"];
		}
		if (isset($json["phoneNumber"])) {
			$data->phoneNumber = $json["phoneNumber"];
		}
		if (isset($json["username"])) {
			$data->username = $json["username"];
		}
		if (isset($json["password"])) {
			$data->password = $json["password"];
		}
		if (isset($json["deviceId"])) {
			$data->deviceId = $json["deviceId"];
		}
		return $data;
	}

	public function updateLocation(string $id, float $lat, float $lon)
	{
		try {
			if ($id != null && $lat != null && $lon != null) {
				$data = array(
					$this->latField() => $lat,
					$this->lonField() => $lon,
				);
				$this->db->update($this->tableName, $data, array(
					$this->idField() => $id
				));
				return  $this->fromId($id);
			} else {
				return new Customer_model();
			}
		} catch (Exception $e) {
			throw $e;
		}
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
	public $avatar;

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
