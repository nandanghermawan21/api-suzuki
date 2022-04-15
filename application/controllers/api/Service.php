<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Service extends BD_Controller
{

    /**
     * @OA\Info(title="Game Center API", version="0.1")
     * @OA\SecurityScheme(
     *   securityScheme="token",
     *   type="apiKey",
     *   name="Authorization",
     *   in="header"
     * )
     */
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        //mendefinisikan folder upload
        define("UPLOAD_DIR", $this->config->item("upload_dir"));
        $this->load->model('Error_model', 'error');
        $this->load->model('Location_model', 'location');
    }


    /**
     * @OA\get(path="/api/Service/sample500",tags={"service"},
     *   operationId="Sample 500",
     *    @OA\Response(response=500,
     *     description="sample response 500",
     *    ),
     *   security={{"token": {}}},
     * )
     */
    public function sample500_get()
    {
        $this->response("internal server error", 500);
    }

    /**
     * @OA\get(path="/api/Service/sample404",tags={"service"},
     *   operationId="Sample 404",
     *    @OA\Response(response=404,
     *     description="sample return 404",
     *    ),
     *   security={{"token": {}}},
     * )
     */
    public function sample404_get()
    {
        $this->response("Page Not Found", 404);
    }

    /**
     * @OA\post(path="/api/Service/savelocation",tags={"service"},
     *   operationId="Save Location Sample",
     *   @OA\RequestBody(
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/LocationModel")
     *     ),
     *   ),
     *   @OA\Response(response=200,
     *     description="save location",
     *     @OA\JsonContent(
     *       @OA\Items(ref="#/components/schemas/LocationModel")
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function savelocation_post()
    {
        $jsonBody  = json_decode(file_get_contents('php://input'), true);
        $location = $this->location->fromJson($jsonBody);

        try {
            $location = $location->add();
            $this->response($location->toJson(), 200);
        } catch (\Exception $e) {
            $error = new Error_model();
            $error->status = 500;
            $error->message = $e->getMessage();
            $this->response($error->message, 500);
        }
    }

    /**
     * @OA\get(path="/api/Service/loadlocation",tags={"service"},
     *   operationId="Load Location Sample",
     *   @OA\Parameter(
     *       name="filter",
     *       in="query",
     *       required=true,
     *       @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response=200,
     *     description="get all location",
     *     @OA\JsonContent(
     *       ref="#/components/schemas/LocationModel"
     *     ),
     *   ),
     *   security={{"token": {}}},
     * )
     */
    public function loadlocation_get()
    {
        $filter = $this->input->get("filter", true);

        try {
            $locations = $this->location->filterRef($filter);

            $this->response($locations, 200);
        } catch (\Exception $e) {
            $error = new Error_model();
            $error->status = 500;
            $error->message = $e->getMessage();
            $this->response($error->message, 500);
        }
    }
}
