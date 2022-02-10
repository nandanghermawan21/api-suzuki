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
     * @OA\Post(path="/api/Fileservice/upload",tags={"fileService"},
     *   operationId="upload file",
     *   @OA\Parameter(
     *       name="path",
     *       in="query",
     *       required=true,
     *       @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *       name="name",
     *       in="query",
     *       required=true,
     *       @OA\Schema(type="string")
     *   ),
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
     *    @OA\Response(response=200,
     *     description="file info",
     *     @OA\JsonContent(
     *       @OA\Items(ref="#/components/schemas/FileModel")
     *     ),
     *    ),
     *   security={{"token": {}}},
     * )
     */
    public function upload_post()
    {
        $path = $this->input->get("path", true);
        $name = $this->input->get("name", true);

        if (!empty($_FILES["media"])) {
            $media    = $_FILES["media"];

            if ($media["error"] !== UPLOAD_ERR_OK) {
                $this->response("upload gagal", 500);
                exit;
            } else {
                $file = $this->file->upload($path, $name, $media);
                $this->response($file, 200);
            }
        }
    }
}
