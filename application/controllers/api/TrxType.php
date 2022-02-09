<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @OA\Info(title="Game Center API", version="0.1")
 * @OA\SecurityScheme(
 *   securityScheme="token",
 *   type="apiKey",
 *   name="Authorization",
 *   in="header"
 * )
 */
class TrxType extends BD_Controller
{
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->auth();
        $this->load->helper('string');
        $this->load->model("m_trxType", "trxType");
        $this->load->model('errormodel', 'errormodel');
    }

    /**
     * @OA\Post(path="/api/trxType/add",tags={"TransactionType"},
     *   operationId="add trxType",
     *   @OA\RequestBody(
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/trxType")
     *     )
     *   ),
     *   @OA\Response(response=200,
     *     description="add trxType",
     *     @OA\JsonContent(
     *       @OA\Items(ref="#/components/schemas/trxType")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function add_post()
    {
        if ($this->user_data->type == "admin") {
            try {
                $jsonBody  = json_decode(file_get_contents('php://input'), true);
                $trxType = $this->trxType->fromJson($jsonBody);

                if ($trxType->validateCode() == false) {
                    $this->response(array("Code or Name is Exist"), 400);
                } else {
                    $result = $this->trxType->fromJson($jsonBody)->add();
                    $this->response($result, 200);
                }
            } catch (\Exception $e) {
                $error = new errormodel();
                $error->status = 500;
                $error->message = $e->getMessage();
                $this->response($error, 500);
            }
        } else {
            $this->response("Access Denied", 500);
        }
    }

    /**
     * @OA\Get(path="/api/trxType/get",tags={"TransactionType"},
     *   operationId="get trxType",
     *   @OA\Parameter(
     *       name="code",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *       name="searchKey",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *       name="skip",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="int")
     *   ),
     *   @OA\Parameter(
     *       name="limit",
     *       in="query",
     *       required=false,
     *       @OA\Schema(type="int")
     *   ),
     *   @OA\Response(response=200,
     *     description="get trxType",
     *     @OA\JsonContent(type="array",
     *       @OA\Items(ref="#/components/schemas/trxType")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function get_get()
    {
        $code = $this->get("code", true);
        $serchKey = $this->get("searchKey", true);
        $limit = $this->get("limit", true);
        $skip = $this->get("skip", true);
        $data = $this->trxType->get($code, $serchKey, $limit, $skip);
        $this->response($data, 200); // OK (200) being the HTTP response code
    }
}
