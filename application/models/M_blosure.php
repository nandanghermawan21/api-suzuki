<?php if (!defined('BASEPATH')) exit('No direct script allowed');

/**
 * @OA\Schema(schema="blosure")
 */
class M_blosure extends CI_Model
{
    public function tableName(): string
    {
        return "m_blosure";
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
    public $validDateStart;
    public function validDateStartField(): string
    {
        return "valid_date_start";
    }
    public function validDateStartJsonKey(): string
    {
        return "validDateStart";
    }

    
}
