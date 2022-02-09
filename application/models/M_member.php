<?php if (!defined('BASEPATH')) exit('No direct script allowed');

/**
 * @OA\Schema(schema="member")
 */

class M_member extends CI_Model
{
	public function tableName(): string
	{
		return "m_member";
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
	public function idjsonKey(): string
	{
		return "id";
	}

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $code;
	public function codeField(): string
	{
		return "code";
	}
	public function codejsonKey(): string
	{
		return "code";
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
	 * @var string
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
	 * @var string
	 */
	public $address;
	public function addressField(): string
	{
		return "address";
	}
	public function addressJsonKey(): string
	{
		return "address";
	}

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $phone;
	public function phoneField(): string
	{
		return "phone";
	}
	public function phoneJsonKey(): string
	{
		return "phone";
	}

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $email;
	public function emailField(): string
	{
		return "email";
	}
	public function emailJsonKey(): string
	{
		return "email";
	}

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $imageId;
	public function imageIdField(): string
	{
		return "image_id";
	}
	public function imageIdJsonKey(): string
	{
		return "imageId";
	}

	/**
	 * @OA\Property()
	 * @var string
	 */
	public $imageUrl;

	/**
	 * @OA\Property()
	 * @var date
	 */
	public $dob;
	public function dobField(): string
	{
		return "dob";
	}
	public function dobJsonKey(): string
	{
		return "dob";
	}

	/**
	 * @OA\Property()
	 * @var int
	 */
	public $point;
	public function pointField(): string
	{
		return "point";
	}
	public function pointJsonKey(): string
	{
		return "point";
	}

	/**
	 * @OA\Property()
	 * @var int
	 */
	public $silverTicket;
	public function silverTicketField(): string
	{
		return "silver_ticket";
	}
	public function silverTicketJsonKey(): string
	{
		return "silverTicket";
	}

	/**
	 * @OA\Property()
	 * @var int
	 */
	public $goldTicket;
	public function goldTicketField(): string
	{
		return "gold_ticket";
	}
	public function goldTicketJsonKey(): string
	{
		return "goldTicket";
	}

	function __construct()
	{
		// Construct the parent class
		parent::__construct();
		$this->load->helper('string');
		$this->load->model('filemodel', 'filemodel');
	}

	function fromRow($row): M_member
	{
		$this->id = $row->id;
		$this->code = $row->code;
		$this->username = $row->username;
		$this->password = $row->password;
		$this->name = $row->name;
		$this->address = $row->address;
		$this->phone = $row->phone;
		$this->email = $row->email;
		$this->imageId = $row->image_id;
		$this->imageUrl = $this->filemodel->fromId($this->imageId)->url;
		$this->dob = $row->dob;
		$this->point = (int)$row->point;
		$this->silverTicket = (int)$row->silver_ticket;
		$this->goldTicket = (int)$row->gold_ticket;

		return $this;
	}

	function fromJson($json): M_member
	{
		if (isset($json[$this->idjsonKey()])) {
			$this->id = $json[$this->idjsonKey()];
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
		if (isset($json[$this->nameJsonKey()])) {
			$this->name = $json[$this->nameJsonKey()];
		}
		if (isset($json[$this->addressJsonKey()])) {
			$this->address = $json[$this->addressJsonKey()];
		}
		if (isset($json[$this->phoneJsonKey()])) {
			$this->phone = $json[$this->phoneJsonKey()];
		}
		if (isset($json[$this->emailJsonKey()])) {
			$this->email = $json[$this->emailJsonKey()];
		}
		if (isset($json[$this->imageIdJsonKey()])) {
			$this->imageId = $json[$this->imageIdJsonKey()];
		}
		if (isset($json[$this->dobJsonKey()])) {
			$this->dob = $json[$this->dobJsonKey()];
		}
		if (isset($json[$this->pointJsonKey()])) {
			$this->point = $json[$this->pointJsonKey()];
		}
		if (isset($json[$this->silverTicketJsonKey()])) {
			$this->silverTicket = $json[$this->silverTicketJsonKey()];
		}
		if (isset($json[$this->goldTicketJsonKey()])) {
			$this->goldTicket = $json[$this->goldTicketJsonKey()];
		}

		return $this;
	}

	public function fromId(String $id): M_member
	{
		$data = $this->db->get_where($this->tableName(), array('id' => $id));

		return $this->fromRow($data->result()[0]);
	}

	function toArray(): array
	{
		$data = array(
			$this->idField() => $this->id,
			$this->codeField() => $this->code,
			$this->usernameField() => $this->username,
			$this->passwordField() => $this->password,
			$this->nameField() => $this->name,
			$this->addressField() => $this->address,
			$this->phoneField() => $this->phone,
			$this->emailField() => $this->email,
			$this->imageIdField() => $this->imageId,
			$this->dobField() => $this->dob,
			$this->pointField() => $this->point,
			$this->silverTicketField() => $this->silverTicket,
			$this->goldTicketField() => $this->goldTicket,
		);

		return $data;
	}

	function login(String $username, String $password)
	{
		$this->db->select('*');
		$this->db->from($this->tableName());

		$this->db->where($this->usernameField(), $username);

		$query = $this->db->get();
		$count = $this->db->count_all_results();

		if ($count > 0) {
			$this->fromRow($query->result()[0]);
			// $this->password = $this->password . " <=> " . sha1($password);
			if ($this->password == sha1($password)) {
				return $this;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}

	function add(): M_member
	{
		try {
			//generate key
			$this->id = null;
			$this->code = random_string('numeric', 12);
			$this->password = $this->password == "" ? $this->username :  $this->password;
			$this->password = sha1($this->password);

			if (count($this->db->get_where($this->tableName(), array('code' => $this->code))->result()) > 0) {
				$this->add();
			}

			$this->db->insert($this->tableName(), $this->toArray());

			$data = $this->db->get_where($this->tableName(), array('code' => $this->code));

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
			$this->db->or_like($this->addressField(), $searchKey);
			$this->db->or_like($this->phoneField(), $searchKey);
			$this->db->or_like($this->emailField(), $searchKey);
			$this->db->or_like($this->phoneField(), $searchKey);
		}

		$this->db->order_by($this->idField(), "desc");
		$this->db->limit($limit, $skip);

		$query = $this->db->get();

		$result = [];
		foreach ($query->result() as $row) {
			$member = new M_member();
			$result[] = $member->fromRow($row);
		}

		return $result;
	}

	//get from id first before add point
	public function addPoint(int $point): M_member
	{

		$this->db->set($this->pointField(), $this->point + $point);
		$this->db->where($this->idField(), $this->id);
		$this->db->update($this->tableName());

		return $this->getData();
	}

	//get from id first before add point
	public function removePoint(int $point): M_member
	{

		$this->db->set($this->pointField(), $this->point - $point);
		$this->db->where($this->idField(), $this->id);
		$this->db->update($this->tableName());

		return $this->getData();
	}

	//get from id first before add point
	public function addSilverTicket(int $count): M_member
	{

		$this->db->set($this->silverTicketField(), $this->silverTicket + $count);
		$this->db->where($this->idField(), $this->id);
		$this->db->update($this->tableName());

		return $this->getData();
	}

	//get from id first before add point
	public function buySilverTicket(): M_member
	{

		$this->db->set($this->pointField(), $this->point - $this->config->item('silver_ticket_price'));
		$this->db->set($this->silverTicketField(), $this->silverTicket + 1);
		$this->db->where($this->idField(), $this->id);
		$this->db->update($this->tableName());

		return $this->getData();
	}

	//get from id first before add point
	public function useSilverTicket(): M_member
	{

		$this->db->set($this->silverTicketField(), $this->silverTicket - 1);
		$this->db->where($this->idField(), $this->id);
		$this->db->update($this->tableName());

		return $this->getData();
	}

	//get from id first before add point
	public function addGoldTicket(int $count): M_member
	{
		$this->db->set($this->goldTicketField(), $this->goldTicket + $count);
		$this->db->where($this->idField(), $this->id);
		$this->db->update($this->tableName());

		return $this->getData();
	}

	//get from id first before add point
	public function buyGoldTicket(): M_member
	{

		$this->db->set($this->pointField(), $this->point - $this->config->item('gold_ticket_price'));
		$this->db->set($this->goldTicketField(), $this->goldTicket + 1);
		$this->db->where($this->idField(), $this->id);
		$this->db->update($this->tableName());

		return $this->getData();
	}

	//get from id first before add point
	public function useGoldTicket(): M_member
	{

		$this->db->set($this->goldTicketField(), $this->goldTicket - 1);
		$this->db->where($this->idField(), $this->id);
		$this->db->update($this->tableName());

		return $this->getData();
	}

	public function getData(): M_member
	{
		$data = $this->db->get_where($this->tableName(), array('id' => $this->id));

		return $this->fromRow($data->result()[0]);
	}

	public function validateNewMember(): bool
	{
		$this->db->select('*');
		$this->db->from($this->tableName());

		$this->db->where($this->usernameField(), $this->username);
		$this->db->or_where($this->emailField(), $this->email);
		$this->db->or_where($this->phoneField(), $this->phone);

		$count = $this->db->count_all_results();

		return $count > 0 ? false : true;
	}

	public function checkUsernameExist(): bool
	{
		$this->db->select('*');
		$this->db->from($this->tableName());

		$this->db->where($this->usernameField(), $this->username);

		$count = $this->db->count_all_results();

		return $count > 0 ? true : false;
	}

	public function checkEmailExist(): bool
	{
		$this->db->select('*');
		$this->db->from($this->tableName());

		$this->db->where($this->emailField(), $this->email);

		$count = $this->db->count_all_results();

		return $count > 0 ? true : false;
	}

	public function checkPhoneExist(): bool
	{
		$this->db->select('*');
		$this->db->from($this->tableName());

		$this->db->where($this->phoneField(), $this->phone);

		$count = $this->db->count_all_results();

		return $count > 0 ? true : false;
	}

	public function changePassword($newPassword): M_member
	{
		$this->password = sha1($newPassword);
		$this->db->set($this->passwordField(), $this->password);
		$this->db->where($this->idField(), $this->id);
		$this->db->update($this->tableName());

		return $this->getData();
	}
}
