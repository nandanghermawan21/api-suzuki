<?php if (!defined('BASEPATH')) exit('No direct script allowed');

/**
 * @OA\Schema(schema="user")
 */
class M_user extends CI_Model
{
	/**
	 * @OA\Property()
	 * @var string
	 */
	public $username;

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $token;

	function fromRow($row)
	{
		$data = new M_user();
		$data->username = $row->username;

		return $data;
	}


	function get_user($q)
	{
		return $this->db->get_where('m_user', $q);
	}
}
