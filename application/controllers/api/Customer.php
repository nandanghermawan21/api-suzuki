<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Firebase\JWT\JWT;

/**
 * @OA\Info(title="Game Center API", version="0.1")
 */
class Customer extends BD_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Customer_model', 'customer');
    }

    /**
     * @OA\Post(path="/api/customer/register",tags={"customer"},
     *   operationId="register customer",
     *   @OA\RequestBody(
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/CustomerRegister")
     *     )
     *   ),
     *   @OA\Response(response=200,
     *     description="register customer",
     *     @OA\JsonContent(
     *       @OA\Items(ref="#/components/schemas/CustomerModel")
     *     ),
     *   ),
     * )
     */
    public function register_post()
    {
        $this->auth();
        try {
            $jsonBody  = json_decode(file_get_contents('php://input'), true);
            $customer = $this->customer->fromJson($jsonBody);

            if ($customer->checkUsernameExist() == true) {
                $this->response("Username Is Exist", 400);
            }  else if ($customer->checkPhoneExist() == true) {
                $this->response("Phone Is Exist", 400);
            } else {
                $result = $customer->add();
                $this->response($result, 200);
            }
        } catch (\Exception $e) {
            $error = new errormodel();
            $error->status = 500;
            $error->message = $e->getMessage();
            $this->response($error, 500);
        }
    }

    /**
     * @OA\Post(path="/api/customer/updateImage",tags={"customer"},
     *   operationId="updateImageId customer",
     *   @OA\Parameter(
     *       name="imageId",
     *       in="query",
     *       required=true,
     *       @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response=200,
     *     description="register customer",
     *     @OA\JsonContent(
     *       @OA\Items(ref="#/components/schemas/CustomerModel")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function updateImage_post()
    {
        if ($this->user_data->type == "customer") {
            try {
               $id = $this->user_data->id;
               $imageId = $this->get("imageId", true);
               $customer = $this->customer->fromId($id);
               $customer->imageId = $imageId;
               $customer->update();
               $this->response($customer, 200);
            } catch (\Exception $e) {
                $error = new errormodel();
                $error->status = 500;
                $error->message = $e->getMessage();
                $this->response($error, 500);
            }
        } else {
            $this->response("forbidden access", 500);
        }
    }
}
