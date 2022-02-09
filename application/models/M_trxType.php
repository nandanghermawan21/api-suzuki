<?php if (!defined('BASEPATH')) exit('No direct script allowed');

/**
 * @OA\Schema(schema="trxType")
 */
class M_trxType extends CI_Model
{
    public function tableName(): string
    {
        return "m_trx_type";
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
    public $data;
    public function dataField(): string
    {
        return "data";
    }
    public function dataJsonKey(): string
    {
        return "data";
    }

    function fromRow($row): M_trxType
    {
        $this->id = $row->id;
        $this->code = $row->code;
        $this->name = $row->name;
        $this->data = $row->data;
        return $this;
    }

    function fromJson($json): M_trxType
    {
        if (isset($json[$this->idjsonKey()])) {
            $this->id = $json[$this->idjsonKey()];
        }
        if (isset($json[$this->codejsonKey()])) {
            $this->code = $json[$this->codejsonKey()];
        }
        if (isset($json[$this->nameJsonKey()])) {
            $this->name = $json[$this->nameJsonKey()];
        }
        if (isset($json[$this->dataJsonKey()])) {
            $this->data = $json[$this->dataJsonKey()];
        }
        return $this;
    }

    public function fromId(String $id): M_trxType
    {
        $data = $this->db->get_where($this->tableName(), array('id' => $id));

        return $this->fromRow($data->result()[0]);
    }

    function toArray(): array
    {
        $data = array(
            $this->idField() => $this->id,
            $this->codeField() => $this->code,
            $this->nameField() => $this->name,
            $this->dataField() => $this->data,
        );

        return $data;
    }

    function add(): M_trxType
    {
        try {
            //generate key
            $this->id = null;

            $this->db->insert($this->tableName(), $this->toArray());

            $data = $this->db->get_where($this->tableName(), array('code' => $this->code));

            return $this->fromRow($data->result()[0]);
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function validateCode(): bool
    {
        $this->db->select('*');
        $this->db->from($this->tableName());

        $this->db->where($this->codeField(), $this->code);
        $this->db->or_where($this->nameField(), $this->name);

        $count = $this->db->count_all_results();

        return $count > 0 ? false : true;
    }

    public function get(String $code = null, String $searchKey = null, int $limit = 0, int $skip = 10): array
	{
		$this->db->select('*');
		$this->db->from($this->tableName());

		if ($code != null && $code != "")
			$this->db->where($this->idField(), $code);

		if ($searchKey != null && $searchKey != "") {
			$this->db->or_like($this->idField(), $searchKey);
			$this->db->or_like($this->nameField(), $searchKey);
		}

		$this->db->order_by($this->idField(), "desc");
		$this->db->limit($limit, $skip);

		$query = $this->db->get();

		$result = [];
		foreach ($query->result() as $row) {
			$member = new M_trxType();
			$result[] = $member->fromRow($row);
		}

		return $result;
	}
    
}
