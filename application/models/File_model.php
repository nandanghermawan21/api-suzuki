<?php if (!defined('BASEPATH')) exit('No direct script allowed');


/**
 * @OA\Schema(schema="FileModel")
 */
class File_model extends CI_Model
{

    private  $tableName =  "svc_file";


    /**
     * @OA\Property()
     * @var string
     */
    public $id;
    public function idField(): string
    {
        return "id";
    }
    public function idJsonKey(): string
    {
        return "id";
    }

    /**
     * @OA\Property()
     * @var string
     */
    public $filename;
    public function fileNameField(): string
    {
        return "filename";
    }
    public function fileNameJsonKey(): string
    {
        return "filename";
    }

    /**
     * @OA\Property()
     * @var int
     */
    public $size;
    public function sizeField(): string
    {
        return "size";
    }
    public function sizeJsonKey(): string
    {
        return "size";
    }

    /**
     * @OA\Property()
     * @var string
     */
    public $extention;
    public function extentionField(): string
    {
        return "extention";
    }
    public function extentionJsonKey(): string
    {
        return "extention";
    }

    /**
     * @OA\Property()
     * @var string
     */
    public $path;
    public function pathField(): string
    {
        return "path";
    }
    public function pathJsonKey(): string
    {
        return "path";
    }

    /**
     * @OA\Property()
     * @var string
     */
    public $url;
    public function createUrl(): String
    {
        $this->url =  $this->config->item("upload_url") . $this->path . '/' . $this->filename;
        return $this->url;
    }

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->helper('string');
    }

    public function fromRow($row): File_model
    {
        $this->id = $row->id;
        $this->filename = $row->filename;
        $this->size = $row->size;
        $this->extention = $row->extention;
        $this->path = $row->path;
        $this->url =  $this->createUrl();

        return $this;
    }

    public function toArray(): array
    {
        $data = array(
            $this->idField() => $this->id,
            $this->fileNameField() => $this->filename,
            $this->sizeField() => $this->size,
            $this->extentionField() => $this->extention,
            $this->pathField() => $this->path,
        );

        return $data;
    }

    public function add()
    {
        try {
            //generate key
            $this->id = random_string('numeric', 12);

            $this->db->insert($this->tableName, $this->toArray());

            $data = $this->db->get_where($this->tableName, array($this->idField() => $this->id));

            return $this->fromRow($data->result()[0]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function fromId($id)
    {
        $data = $this->db->get_where($this->tableName, array($this->idField() => $id));

        $result = $data->result();

        if (count($result) > 0) {
            return $this->fromRow($result[0]);
        } else {
            return new File_model();
        }
    }

    public function upload($path, $name, $file)
    {
        if ($file != null) {
            $media    = $file;
            $ext    = pathinfo($file["name"], PATHINFO_EXTENSION);
            $size    = $file["size"];
            // $tgl    = date("Y-m-d");
            
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
                $data = new FileModel();
                $data->filename = $name;
                $data->path = $path;
                $data->extention = $ext;
                $data->size = $size;
                
                // $filemodel->size = filesize(UPLOAD_DIR . "/" . $path . "/" . $name);
                $data->url = $data->createUrl();
                $data->add();
                return $data;
            }
        }
    }

}
