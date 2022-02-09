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
        $this->load->model('filemodel', 'filemodel');
        $this->load->helper('file', 'file');
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
     *       @OA\Items(ref="#/components/schemas/filemodel")
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
            $ext    = pathinfo($_FILES["media"]["name"], PATHINFO_EXTENSION);
            $size    = $_FILES["media"]["size"];
            $tgl    = date("Y-m-d");

            if ($media["error"] !== UPLOAD_ERR_OK) {
                $this->response("upload gagal", 500);
                exit;
            }

            // filename yang aman
            $currentName = preg_replace("/[^A-Z0-9._-]/i", "_", $media["name"]);
            if ($name == "" || $name == null) {
                $name = $currentName;
            } else {
                $name = $name . "." . pathinfo($currentName)["extension"];
            }

            // menambahkan path
            $name = $path . "/" . $name;

            // create path jika tidak ada
            if (!is_dir(UPLOAD_DIR . "/" . $path)) {
                mkdir(UPLOAD_DIR . "/" . $path, 0777, TRUE);
            }

            // mencegah overwrite filename
            // $i = 0;
            $parts = pathinfo($name);
            // while (file_exists(UPLOAD_DIR . $name)) {
            //     $i++;
            //     $name =  $parts["filename"] . "-" . $i . "." . $parts["extension"];
            // }

            $success = move_uploaded_file($media["tmp_name"], UPLOAD_DIR . $name);

            if ($success) {
                $filemodel = new filemodel();
                $filemodel->filename = $name;
                $filemodel->path = $path;
                $filemodel->extention = $parts["extension"];
                // $filemodel->size = filesize(UPLOAD_DIR . "/" . $path . "/" . $name);
                $filemodel->url = $filemodel->createUrl();
                $filemodel->add();
                $this->response($filemodel, 200);
                exit;
            }
        }
    }
}
