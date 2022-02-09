<?php if (!defined('BASEPATH')) exit('No direct script allowed');

/**
 * @OA\Schema(schema="uom")
 */
class M_uom extends CI_Model
{
    /**
     * @OA\Property()
     * @var int
     */
    public $uomId;

    /**
     * @OA\Property()
     * @var string
     */
    public $uomName;

    /**
     * @OA\Property()
     * @var string
     */
    public $uomCode;

    function fromRow($row)
    {
        $data = new M_uom();
        $data->uomId = $row->uom_id;
        $data->uomName = $row->uom_name;
        $data->uomCode = $row->uom_code;
        return $data;
    }

    function fromJson($json)
    {
        $data = new M_uom();

        if (isset($json['uomId'])) {
            $data->uomId = $json["uomId"];
        }
        if (isset($json['uomName'])) {
            $data->uomName = $json["uomName"];
        }
        if (isset($json['uomCode'])) {
            $data->uomCode = $json["uomCode"];
        }

        return $data;
    }

    function toRow()
    {
        $data = new stdClass();
        $data->uom_id = $this->uomId;
        $data->uom_name = $this->uomName;
        $data->uom_code = $this->uomCode;

        return $data;
    }


    function getAll()
    {
        $query = $this->db->get('m_uom');

        $result = [];
        foreach ($query->result() as $row) {
            array_push($result, $this->fromRow($row));
        }

        return $result;
    }

    function add($uom)
    {
        $this->db->insert('m_uom', $uom->toRow());

        $data = $this->db->get_where('m_category', array('uom_id' => $uom->id));

        return $this->fromRow($data->result()[0]);
    }

    function update($id, \M_uom $uom)
    {
        $data = array(
            'uom_name' => $uom->uomName,
            'uom_code' => $uom->uomCode,
        );

        $this->db->where('uom_id', $id);
        $this->db->update('m_uom', $data);

        $data = $this->db->get_where('m_uom', array('uom_id' => $id));

        return $this->fromRow($data->result()[0]);
    }

    function get($id)
    {
        $data = $this->db->get_where('m_uom', array('uom_id' => $id));
        return $this->fromRow($data->result()[0]);
    }
}
