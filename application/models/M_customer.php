<?php if (!defined('BASEPATH')) exit('No direct script allowed');

/**
 * @OA\Schema(schema="customer")
 */
class M_customer extends CI_Model
{
	private $tableName = "m_user";
	/**
	 * @OA\Property()
	 * @var int
	 */
	public $id;

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $nik;

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $imageUrl;

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $fullName;

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $genderId;

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $cityId;

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $phoneNumber;

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $username;

	/**
	 * @OA\Property()
	 * @var int
	 */
	public $level;

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $token;



	function fromRow($row)
	{
		$data = new M_customer();
		$data->nik = $row->nik;
		$data->imageUrl = $row->photo_image_id;
		$data->fullName = $row->full_name;
		$data->genderId = $row->gender_id;
		$data->cityId = $row->city_id;
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
}
