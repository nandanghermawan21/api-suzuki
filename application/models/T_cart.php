<?php if (!defined('BASEPATH')) exit('No direct script allowed');

/**
 * @OA\Schema(schema="cart")
 */
class T_cart extends CI_Model
{
    public function tableName(): string
    {
        return "t_cart";
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
     * @var string
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
     * @var string
     */
    public $cashierCode;
    public function cashierCodeField(): string
    {
        return "cashier_code";
    }
    public function cashierCodeJsonKey(): string
    {
        return "cashierCode";
    }

    /**
     * @OA\Property()
     * @var datetime
     */
    public $date;
    public function dateField(): string
    {
        return "date";
    }
    public function dateJsonKey(): string
    {
        return "date";
    }
    /**
     * @OA\Property(
     *   property="detail",
     *   type="array",
     *   @OA\Items(ref="#/components/schemas/cartDetail")
     * )
     */
    public $detail;


    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->helper('string');
        $this->load->model('t_cartDetail', 'cartDetail');
        $this->load->model('filemodel', 'filemodel');
    }

    function fromRow($row): T_cart
    {
        $this->id = $row->id;
        $this->code = $row->code;
        $this->cashierCode = $row->cashier_code;
        $this->date = $row->date;
        $this->detail = $this->cartDetail->getByCartCode($row->code);

        return $this;
    }

    public function fromJson($json)
    {
        if (isset($json[$this->idjsonKey()])) {
            $this->id = $json[$this->idjsonKey()];
        }
        if (isset($json[$this->codejsonKey()])) {
            $this->code = $json[$this->codejsonKey()];
        }
        if (isset($json[$this->cashierCodeJsonKey()])) {
            $this->cashierCode = $json[$this->cashierCodeJsonKey()];
        }
        if (isset($json[$this->dateJsonKey()])) {
            $this->date = strtotime($json[$this->dateJsonKey()]);
        }
    }

    public function fromId(String $id): T_cart
    {
        $data = $this->db->get_where($this->tableName(), array('id' => $id));

        return $this->fromRow($data->result()[0]);
    }

    function toArray(): array
    {
        $data = array(
            $this->idField() => $this->id,
            $this->codeField() => $this->code,
            $this->cashierCodeField() => $this->cashierCode,
            $this->dateField() => $this->date->format("Y-m-d H:i:s"),
        );

        return $data;
    }

    function add(): T_cart
    {
        try {
            //generate key
            $this->id = null;
            $this->code = random_string('numeric', 12);
            $this->date = new DateTime();

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
            $this->db->or_like($this->codeField(), $searchKey);
        }

        $this->db->order_by($this->idField(), "desc");
        $this->db->limit($limit, $skip);

        $query = $this->db->get();

        $result = [];
        foreach ($query->result() as $row) {
            $member = new T_cart();
            $result[] = $member->fromRow($row);
        }

        return $result;
    }
}
