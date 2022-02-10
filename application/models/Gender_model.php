<?php if (!defined('BASEPATH')) exit('No direct script allowed');


/**
 * @OA\Schema(schema="GenderModel")
 */
class Gender_model extends CI_Model
{

    private  $tableName =  "master_gender";


    /**
     * @OA\Property()
     * @var string
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

    public function fromRow($row): Gender_model
    {
        $data = new Gender_model();
        $data->id = $row->id;
        $data->name = $row->name;

        return $data;
    }

    public function toArray(): array
    {
        $data = array(
            $this->idField() => $this->id,
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
            return new Gender_model();
        }
    }

    public function getAll() : array
    {
        $query = $this->db->get($this->tableName);
        
        $result = [];
		foreach ($query->result() as $row) {
			$gender = new Gender_model();
			$result[] = $gender->fromRow($row);
		}

        return $result;
    }
}
