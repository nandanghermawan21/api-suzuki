<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Firebase\JWT\JWT;

/**
 * @OA\Info(title="Game Center API", version="0.1")
 */
class Gender extends BD_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('string');
        $this->load->model('Error_model', 'error');
        $this->load->model('Gender_model', 'gender');
    }

    /**
     * @OA\Get(path="/api/gender/getAll",tags={"gender"},
     *   operationId="Get all gender",
     *   @OA\Response(response=200,
     *     description="Get all gender",
     *     @OA\JsonContent(
     *       @OA\Items(ref="#/components/schemas/GenderModel")
     *     ),
     *   ),
     * )
     */
    public function getAll_get()
    {
        try {
            $gender = $this->gender->getAll();

            $this->response($gender, 200);
        } catch (\Exception $e) {
            $error = new Error_model();
            $error->status = 500;
            $error->message = $e->getMessage();
            $this->response($error, 500);
        }
    }
}
