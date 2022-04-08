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
	 * @var DateTime
	 */
	public $createDate;
	public function createDateField(): string
	{
		return "create_date";
	}
	public function createDateJsonKey(): string
	{
		return "createDate";
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
	 * @var double
	 */
	public $direction;
	public function directionField(): string
	{
		return "direction";
	}
	public function directionJsonKey(): string
	{
		return "direction";
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
		if (isset($json[$this->createDateJsonKey()])) { //DateTime::ATOM
			$data->createDate =  date_create(date(DATE_ATOM, strtotime($json[$this->createDateJsonKey()])));  //  DateTime::createFromFormat(DateTime::ISO8601, $json[$this->createDateJsonKey()]);
		}
		if (isset($json[$this->latJsonKey()])) {
			$data->lat = $json[$this->latJsonKey()];
		}
		if (isset($json[$this->lonJsonKey()])) {
			$data->lon = $json[$this->lonJsonKey()];
		}
		if (isset($json[$this->directionJsonKey()])) {
			$data->direction = $json[$this->directionJsonKey()];
		}

		return $data;
	}

	public function fromRow($row): Location_model
	{
		$data = new Location_model();
		$data->id = $row->{$this->idField()};
		$data->ref = $row->{$this->refField()};
		$data->createDate = date_create(date(DATE_ATOM, strtotime($row->{$this->createDateField()})));
		$data->lat = $row->{$this->latField()};
		$data->lon = $row->{$this->lonField()};
		$data->direction = $row->{$this->directionField()};

		return $data;
	}

	public function toArray(): array
	{

		$data = array(
			$this->idField() => $this->id,
			$this->refField() => $this->ref,
			$this->createDateField() => $this->createDate->format(DATE_ATOM),  // date_format($this->createDate, DateTime::ATOM),
			$this->latField() => $this->lat,
			$this->lonField() => $this->lon,
			$this->directionField() => $this->direction,
		);

		return $data;
	}

	public function toJson(): array
	{
		$data = array(
			$this->idJsonKey() => (int) $this->id,
			$this->refJsonKey() => $this->ref,
			$this->createDateJsonKey() => $this->createDate->format(DateTime::ATOM),
			$this->latJsonKey() => (float) $this->lat,
			$this->lonJsonKey() =>  (float) $this->lon,
			$this->directionJsonKey() =>  (float) $this->direction,
		);

		return $data;
	}

	public function  add(): Location_model
	{
		try {
			//generate key
			$this->id = null;

			$this->db->insert($this->tableName, $this->toArray());

			$data = $this->db->get_where($this->tableName, array(
				$this->idField() => $this->db->insert_id()

			));
			$result = $data->result();
			return $this->fromRow($result[0]);
		} catch (Exception $e) {
			throw $e;
		}
	}

	function filterRef(String $filter = "%%"): array
	{
		$sql = "SELECT DISTINCT 
		      a.ref,
			  (SELECT b.id FROM location_history b WHERE b.ref = a.ref ORDER BY create_date DESC LIMIT 1 ) as id,
			  (SELECT b.create_date FROM location_history b WHERE b.ref = a.ref ORDER BY create_date DESC LIMIT 1 ) as create_date,
		      (SELECT b.lat FROM location_history b WHERE b.ref = a.ref ORDER BY create_date DESC LIMIT 1 ) as lat,
		      (SELECT b.lon FROM location_history b WHERE b.ref = a.ref ORDER BY create_date DESC LIMIT 1 ) as lon,
		      (SELECT b.direction FROM location_history b WHERE b.ref = a.ref ORDER BY create_date DESC LIMIT 1 ) as direction
		      from location_history a
		      WHERE a.ref like ?
		      ORDER BY a.create_date DESC";
		$query = $this->db->query($sql, array($filter));
		$result = $query->result();

		$data = [];
        for ($i = 0; $i < count($result); $i++) {
			$location = new Location_model();
			$location = $location->fromRow($result[$i]);
			$location = $location->toJson();
            $data[] = $location;
        }

		return $result;
	}
}
