<?php if (!defined('BASEPATH')) exit('No direct script allowed');


/**
 * @OA\Schema(schema="LocationModel")
 */
class Location_model extends CI_Model
{
    /**
	 * @OA\lat()
	 * @var double
	 */
	public $lat;
	public function latField(): string
	{
		return "lat";
	}
	public function latJsonKey(): string
	{
		return "lat";
	}

	/**
	 * @OA\lat()
	 * @var double
	 */
	public $lon;
	public function lonField(): string
	{
		return "lon";
	}
	public function lonJsonKey(): string
	{
		return "lon";
	}


    public function  fromJson($json): Location_model
	{
		$data = new Location_model();
		
        if (isset($json[$this->latJsonKey()])) {
			$data->lat = $json[$this->latJsonKey()];
		}
		if (isset($json[$this->lonJsonKey()])) {
			$data->lon = $json[$this->lonJsonKey()];
		}

		return $data;
	}

}