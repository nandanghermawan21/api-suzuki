<?php if (!defined('BASEPATH')) exit('No direct script allowed');


/**
 * @OA\Schema(schema="LocationModel")
 */
class Location_model extends CI_Model
{
	private $tableName = "location_history";
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
	public $ref;
	public function refField(): string
	{
		return "ref";
	}
	public function refJsonKey(): string
	{
		return "ref";
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


	public function  fromJson($json): Location_model
	{
		$data = new Location_model();

		if (isset($json[$this->idJsonKey()])) {
			$data->id = $json[$this->idJsonKey()];
		}
		if (isset($json[$this->refJsonKey()])) {
			$data->ref = $json[$this->refJsonKey()];
		}
		if (isset($json[$this->latJsonKey()])) {
			$data->lat = $json[$this->latJsonKey()];
		}
		if (isset($json[$this->lonJsonKey()])) {
			$data->lon = $json[$this->lonJsonKey()];
		}

		return $data;
	}

	public function fromRow($row): Location_model
	{
		$data = new Location_model();
		$data->id = $row->{$this->idField()};
		$data->ref = $row->{$this->refField()};
		$data->lat = $row->{$this->latField()};
		$data->lon = $row->{$this->lonField()};

		return $data;
	}

	public function toArray(): array
	{

		$data = array(
			$this->idField() => $this->id,
			$this->refField() => $this->ref,
			$this->latField() => $this->lat,
			$this->lonField() => $this->lon,
		);

		return $data;
	}

	public function toJson(): array
	{
		$data = array(
			$this->idJsonKey() => $this->id,
			$this->refJsonKey() => $this->ref,
			$this->latJsonKey() => $this->lat,
			$this->lonJsonKey() => $this->lon,
		);

		return $data;
	}

	public function  add(): Location_model
	{
		try {
			//generate key
			$this->id = null;

			$this->db->insert($this->tableName, $this->toArray());

			$data = $this->db->get_where($this->tableName, array($this->idField() => $insert_id = $this->db->insert_id()

		));
			$result = $data->result();
			return $this->fromRow($result[0]);
		} catch (Exception $e) {
			throw $e;
		}
	}
}
