<?php if (!defined('BASEPATH')) exit('No direct script allowed');


/**
 * @OA\Schema(schema="ErrorModel")
 */
class Error_model extends CI_Model
{
    /**
     * @OA\Property()
     * @var int
     */
    public $status;

    /**
     * @OA\Property()
     * @var string
     */
    public $message;

    /**
     * @OA\Property()
     * @var string
     */
    public $track;
}
