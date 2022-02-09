<?php if (!defined('BASEPATH')) exit('No direct script allowed');

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

	function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('File_model', 'file');
    }

	function fromRow($row)
	{
		$data = new Customer_model();
		$data->nik = $row->nik;
		$data->imageUrl = $this->file->fromId($row->photo_image_id)->createUrl();
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
