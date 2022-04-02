<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Fileservice extends BD_Controller
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        //mendefinisikan folder upload
        define("UPLOAD_DIR", $this->config->item("upload_dir"));
        $this->load->model('Error_model', 'error');
        $this->load->model('File_model', 'file');
    }


    /**
     * @OA\get(path="/api/Service/sample500",tags={"service"},
     *   operationId="Sample 500",
     *    @OA\Response(response=200,
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
     *    @OA\Response(response=200,
     *     description="sample return 404",
     *    ),
     *   security={{"token": {}}},
     * )
     */
    public function sample404_get()
    {
        $this->response("Page Not Found", 404);
    }
}
