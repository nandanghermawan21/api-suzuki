<?php if (!defined('BASEPATH')) exit('No direct script allowed');

/**
 * @OA\Schema(schema="cashier")
 */

class M_cashier extends CI_Model
{
	public function tableName(): string
	{
		return "m_cashier";
	}
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
	 * @var String
	 */
	public $name;
	public function nameField(): string
	{
		return "name";
	}
	public function nameJsonKey(): string
	{
		return "name";
	}
	/**
	 * @OA\Property()
	 * @var String
	 */
	public $code;
	public function codeField(): string
	{
		return "code";
	}
	public function codeJsonKey(): string
	{
		return "code";
	}
	/**
	 * @OA\Property()
	 * @var String
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
	 * @var String
	 */
	public $password;
	public function passwordField(): string
	{
		return "password";
	}
	public function passwordJsonKey(): String
	{
		return "password";
	}
	/**
	 * @OA\Property()
	 * @var int
	 */
	public $status;
	public function statusField(): string
	{
		return "status";
	}
	public function statusJsonKey(): string
	{
		return "status";
	}

	function fromRow($row): M_cashier
	{
		$this->id = $row->id;
		$this->code = $row->code;
		$this->name = $row->name;
		$this->username = $row->username;
		$this->password = $row->password;
		$this->status = $row->status;
		return $this;
	}

	function fromId(String $id): M_cashier
	{
		$data = $this->db->get_where($this->tableName(), array('id' => $id));

		return $this->fromRow($data->result()[0]);
	}

	public function getData(): M_cashier
	{
		$data = $this->db->get_where($this->tableName(), array('id' => $this->id));

		return $this->fromRow($data->result()[0]);
	}

	function toArray(): array
	{
		$data = array(
			$this->idField() => $this->id,
			$this->codeField() => $this->code,
			$this->nameField() => $this->name,
			$this->usernameField() => $this->username,
			$this->passwordField() => $this->password,
			$this->statusField() => $this->status
		);
		return $data;
	}

	function fromJson($json): M_cashier
	{
		if (isset($json[$this->idjsonKey()])) {
			$this->id = $json[$this->idjsonKey()];
		}
		if (isset($json[$this->nameJsonKey()])) {
			$this->name = $json[$this->nameJsonKey()];
		}
		if (isset($json[$this->codejsonKey()])) {
			$this->code = $json[$this->codejsonKey()];
		}
		if (isset($json[$this->usernameJsonKey()])) {
			$this->username = $json[$this->usernameJsonKey()];
		}
		if (isset($json[$this->passwordJsonKey()])) {
			$this->password = $json[$this->passwordJsonKey()];
		}
		if (isset($json[$this->statusjsonKey()])) {
			$this->status = $json[$this->statusJsonKey()];
		}

		return $this;
	}

	function login(String $username, String $password)
	{
		$this->db->select("*");
		$this->db->from($this->tableName());

		$this->db->where($this->usernameField(), $username);

		$query = $this->db->get();
		$count = $this->db->count_all_results();

		if ($count > 0) {
			$this->fromRow($query->result()[0]);
			if ($this->password == sha1($password)) {
				return $this;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}

	function add(): M_cashier
	{
		try {
			//generate key
			$this->id = null;
			$this->code = random_string('numeric', 12);
			$this->password = $this->password == "" ? $this->username :  $this->password;
			$this->password = sha1($this->password);

			if (count($this->db->get_where($this->tableName(), array($this->codeField() => $this->code))->result()) > 0) {
				$this->add();
			}

			$this->db->insert($this->tableName(), $this->toArray());

			$data = $this->db->get_where($this->tableName(), array($this->codeField() => $this->code));

			return $this->fromRow($data->result()[0]);
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function get(String $code = null, String $searchKey = null, int $limit = 0, int $skip = 10): array
	{
		$this->db->select('*');
		$this->db->from($this->tableName());

		if ($code != null && $code != "")
			$this->db->where($this->idField(), $code);

		if ($searchKey != null && $searchKey != "") {
			$this->db->or_like($this->idField(), $searchKey);
			$this->db->or_like($this->usernameField(), $searchKey);
			$this->db->or_like($this->nameField(), $searchKey);
		}

		$this->db->order_by($this->idField(), "desc");
		$this->db->limit($limit, $skip);

		$query = $this->db->get();

		$result = [];
		foreach ($query->result() as $row) {
			$member = new M_cashier();
			$result[] = $member->fromRow($row);
		}

		return $result;
	}

	public function checkUsernameExist(): bool
	{
		$this->db->select('*');
		$this->db->from($this->tableName());

		$this->db->where($this->usernameField(), $this->username);

		$count = $this->db->count_all_results();

		return $count > 0 ? true : false;
	}

	public function changePassword($newPassword): M_cashier
	{
		$this->password = sha1($newPassword);
		$this->db->set($this->passwordField(), $this->password);
		$this->db->where($this->idField(), $this->id);
		$this->db->update($this->tableName());

		return $this->getData();
	}
}
