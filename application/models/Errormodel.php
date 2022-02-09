<?php if (!defined('BASEPATH')) exit('No direct script allowed');


/**
 * @OA\Schema(schema="errormodel")
 */
class Errormodel extends CI_Model
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
