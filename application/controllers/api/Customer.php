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
        $this->load->helper('string');
        $this->load->model('Error_model', 'error');
        $this->load->model('Customer_model', 'customer');
        $this->load->model('File_model', 'file');
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
        try {
            $jsonBody  = json_decode(file_get_contents('php://input'), true);
            $customer = $this->customer->fromJson($jsonBody);

            if ($customer->checkUsernameExist() == true) {
                $this->response("Username Is Exist", 400);
            } else if ($customer->checkPhoneExist() == true) {
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
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               @OA\Property(
     *                   property="media",
     *                   description="media",
     *                   type="file",
     *                   @OA\Items(type="string", format="binary")
     *                ),
     *            ),
     *        ),
     *    ),
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
        if ($this->getData()->type == "customer") {
            try {
                $id = $this->getData()->id;
                $path = "customer_photo";
                if (!empty($_FILES["media"])) {
                    $media    = $_FILES["media"];

                    if ($media["error"] !== UPLOAD_ERR_OK) {
                        $this->response("upload gagal", 500);
                        exit;
                    } else {
                        $this->file->upload($path, random_string('alnum', 100) , $media);
                        $this->customer->fromId($id);
                        $this->customer->imageId = $this->file->id;
                        // $customer->update();
                        $this->response($this->customer, 200);
                    }
                }
            } catch (\Exception $e) {
                $error = new Error_model();
                $error->status = 500;
                $error->message = $e->getMessage();
                $this->response($error, 500);
            }
        } else {
            $this->response("forbidden access", 500);
        }
    }
}
