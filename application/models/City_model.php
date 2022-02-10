<?php if (!defined('BASEPATH')) exit('No direct script allowed');


/**
 * @OA\Schema(schema="CityModel")
 */
class City_model extends CI_Model
{

    private  $tableName =  "master_city";


    /**
     * @OA\Property()
     * @var string
     */
    public $id;
    private function idField(): string
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
    public $provinceId;
    public function provinceIdField(): string
    {
        return "province_id";
    }
    public function provinceIdJsonKey(): string
    {
        return "provinceId";
    }

    /**
     * @OA\Property()
     * @var int
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


    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->helper('string');
    }

    public function fromRow($row): City_model
    {

        $data = new City_model();
        $data->id = $row->id;
        $data->provinceId = $row->province_id;
        $data->name = str_replace("Kota","",$row->name);

        return $data;
    }

    public function toArray(): array
    {
        $data = array(
            $this->idField() => $this->id,
            $this->provinceIdField() => $this->provinceId,
            $this->nameField() => $this->name,
        );

        return $data;
    }

    public function fromId($id)
    {
        $data = $this->db->get_where($this->tableName, array($this->idField() => $id));

        $result = $data->result();

        if (count($result) > 0) {
            return $this->fromRow($result[0]);
        } else {
            return new City_model();
        }
    }

    public function getAll(): array
    {
        $this->db->select('*');
        $this->db->from($this->tableName);
        $this->db->like($this->nameField(), 'Kota', 'after');
        $this->db->order_by($this->nameField(), "asc");

        $query = $this->db->get();

        $result = [];
        foreach ($query->result() as $row) {
            $city = new City_model();
            $result[] = $city->fromRow($row);
        }

        return $result;
    }
}
