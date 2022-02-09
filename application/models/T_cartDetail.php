<?php if (!defined('BASEPATH')) exit('No direct script allowed');

/**
 * @OA\Schema(schema="cartDetail")
 */

class T_cartDetail extends CI_Model
{
    public function tableName(): string
    {
        return "t_cart_detail";
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
    public $cartCode;
    public function cartCodeField(): string
    {
        return "cart_code";
    }
    public function cartCodeJsonKey(): string
    {
        return "cartCode";
    }
    /**
     * @OA\Property()
     * @var string
     */
    public $trxTypeCode;
    public function trxTypeCodeField(): string
    {
        return "trx_type_code";
    }
    public function trxTypeCodeJsonKey(): string
    {
        return "trxTypeCode";
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
    /**
     * @OA\Property()
     * @var int
     */
    public $capital;
    public function capitalField(): string
    {
        return "capital";
    }
    public function capitalJsonKey(): string
    {
        return "capital";
    }
    /**
     * @OA\Property()
     * @var int
     */
    public $price;
    public function priceField(): string
    {
        return "price";
    }
    public function priceJsonKey(): string
    {
        return "price";
    }

    function fromRow($row): T_cartDetail
    {
        $this->id = $row->id;
        $this->code = $row->code;
        $this->cartCode = $row->cart_code;
        $this->trxTypeCode = $row->trx_type_code;
        $this->data = $row->data;
        $this->capital = $row->capital;
        $this->price = $row->price;
        return $this;
    }

    function fromJson($json): T_cartDetail
    {
        if (isset($json[$this->idjsonKey()])) {
            $this->id = $json[$this->idjsonKey()];
        }
        if (isset($json[$this->codejsonKey()])) {
            $this->code = $json[$this->codejsonKey()];
        }
        if (isset($json[$this->cartCodeJsonKey()])) {
            $this->cartCode = $json[$this->cartCodeJsonKey()];
        }
        if (isset($json[$this->trxTypeCodeJsonKey()])) {
            $this->trxTypeCode = $json[$this->trxTypeCodeJsonKey()];
        }
        if (isset($json[$this->dataJsonKey()])) {
            $this->data = $json[$this->dataJsonKey()];
        }
        if (isset($json[$this->capitalJsonKey()])) {
            $this->capital = $json[$this->capitalJsonKey()];
        }
        if (isset($json[$this->priceJsonKey()])) {
            $this->price = $json[$this->priceJsonKey()];
        }
        return $this;
    }

    public function fromId(String $id): T_cartDetail
    {
        $data = $this->db->get_where($this->tableName(), array('id' => $id));

        return $this->fromRow($data->result()[0]);
    }

    function toArray(): array
    {
        $data = array(
            $this->idField() => $this->id,
            $this->codeField() => $this->code,
            $this->cartCodeField() => $this->cartCode,
            $this->trxTypeCodeField() => $this->trxTypeCode,
            $this->dataField() => $this->data,
            $this->capitalField() => $this->capital,
            $this->priceField() => $this->price,
        );

        return $data;
    }

    function add(): T_cartDetail
    {
        try {
            //generate key
            $this->id = null;
            $this->code = random_string('numeric', 12);

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
            $member = new T_cartDetail();
            $result[] = $member->fromRow($row);
        }

        return $result;
    }

    public function getByCartCode(String $cartCode): array
    {
        $query = $this->db->get_where($this->tableName(), array($this->cartCodeField() => $cartCode));

        $result = [];
        foreach ($query->result() as $row) {
            $cartDetail = new T_cartDetail();
            $result[] = $cartDetail->fromRow($row);
        }

        return  $result;
    }
}
