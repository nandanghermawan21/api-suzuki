<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Firebase\JWT\JWT;

/**
 * @OA\Info(title="Game Center API", version="0.1")
 */
class City extends BD_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('string');
        $this->load->model('Error_model', 'error');
        $this->load->model('City_model', 'city');
    }

    /**
     * @OA\Get(path="/api/city/getAll",tags={"city"},
     *   operationId="get all city",
     *   @OA\Response(response=200,
     *     description="get all city",
     *     @OA\JsonContent(
     *       @OA\Items(ref="#/components/schemas/CityModel")
     *     ),
     *   ),
     * )
     */
    public function getAll_get()
    {
        try {
            $city = $this->city->getAll();

            $this->response($city, 200);
        } catch (\Exception $e) {
            $error = new Error_model();
            $error->status = 500;
            $error->message = $e->getMessage();
            $this->response($error, 500);
        }
    }
}
