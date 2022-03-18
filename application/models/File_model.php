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
        $this->url =  $this->config->item("upload_url") . $this->path . '/' . $this->filename . '.' . $this->extention;
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
        $data = new File_model();
        $data->id = $row->id;
        $data->filename = $row->filename;
        $data->size = $row->size;
        $data->extention = $row->extention;
        $data->path = $row->path;
        $data->url =  $data->createUrl();

        return $data;
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

            return $this;
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

            if (!is_dir($this->config->item("upload_dir") . "/" . $path)) {
                mkdir($this->config->item("upload_dir") . "/" . $path, 0777, TRUE);
            }

            $success = move_uploaded_file($media["tmp_name"], $this->config->item("upload_dir") . $path . "/" .  $name . '.' . $ext);

            $success = true;
            if ($success) {
                $data = new File_model();
                $data->filename = $name;
                $data->path = $path;
                $data->extention = $ext;
                $data->size = $size;
                $data->url = $data->createUrl();
                return $data->add();
            }
        }
    }

    public function save($path, $name, $base64)
    {
        try{

                if (!is_dir($this->config->item("upload_dir") . "/" . $path)) {
                    mkdir($this->config->item("upload_dir") . "/" . $path, 0777, TRUE);
                }

                $file = $this->config->item("upload_dir") . "/" . $path . $name . '.' . $this->getExtention($base64);
              
                $listStr = explode(',', $base64);
                $img = $listStr[1];
                $img = base64_decode($img);

                $success = file_put_contents($file, $img);
                
                if ($success) {
                    $data = new File_model();
                    $data->filename = $name;
                    $data->path = $path;
                    $data->extention = $this->getExtention($base64);
                    $data->size = $file["size"];
                    $data->url = $data->createUrl();
                    return $data->add();
                }
            
        }catch(Exception $e){
            throw $e;
        }
    }

    function base64_to_image($base64_string, $output_file)
    {
        // open the output file for writing
        $ifp = fopen($output_file, 'wb');

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode(',', $base64_string);

        // we could add validation here with ensuring count( $data ) > 1
        fwrite($ifp, base64_decode($data[1]));

        // clean up the file resource
        fclose($ifp);

        return $output_file;
    }

    function getExtention($base64)
    {
        $data = explode(',', $base64);
        switch ($data[0]) {
            case "data:image/png;base64":
                return "png";
                break;
            case "data:image/jpeg;base64":
                return "jeeg";
                break;
            case "data:image/jpg;base64":
                return "jpg";
                break;
            case "data:image/gif;base64":
                return "gif";
                break;
            default:
                return "jpg";
        }
    }

    function check_base64_image($base64) {
        $img = imagecreatefromstring(base64_decode($base64));
        if (!$img) {
            return false;
        }
    
        imagepng($img, 'tmp.png');
        $info = getimagesize('tmp.png');
    
        unlink('tmp.png');
    
        if ($info[0] > 0 && $info[1] > 0 && $info['mime']) {
            return true;
        }
    
        return false;
    }
}
