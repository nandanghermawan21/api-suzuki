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
    public $idField = "id";
    public $idJsonKey =  "id";
    

    /**
     * @OA\Property()
     * @var string
     */
    public $provinceId;
    public $provinceIdField = "province_id";
    public $provinceIdJsonKey = "provinceId";
    

    /**
     * @OA\Property()
     * @var int
     */
    public $name;
    public $nameField = "name";
    public $nameJsonKey = "name";
    


    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->helper('string');
    }

    public function fromRow($row): City_model
    {
        $this->id = $row->id;
        $this->provinceId = $row->province_id;
        $this->name = $row->name;

        return $this;
    }

    public function toArray(): array
    {
        $data = array(
            $this->idField => $this->id,
            $this->provinceIdField => $this->provinceId,
            $this->nameField => $this->name,
        );

        return $data;
    }

    public function fromId($id)
    {
        $data = $this->db->get_where($this->tableName, array($this->idField => $id));

        $result = $data->result();

        if (count($result) > 0) {
            return $this->fromRow($result[0]);
        } else {
            return new City_model();
        }
    }
}
