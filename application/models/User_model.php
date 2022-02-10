<?php if (!defined('BASEPATH')) exit('No direct script allowed');

/**
 * @OA\Schema(schema="UserModel")
 */
class User_model
{
    /**
     * @OA\Property()
     * @var String
     */
    public $username;
    public function usernameJsonKey(): string
    {
        return "username";
    }

    /**
     * @OA\Property()
     * @var String
     */
    public $password;
    public function passwordJsonKey(): string
    {
        return "password";
    }

    /**
     * @OA\Property()
     * @var String
     */
    public $deviceId;
    public function deviceIdJsonKey(): string
    {
        return "deviceId";
    }

    public function  fromJson($json): User_model
	{
		$data = new User_model();
		if (isset($json[$this->usernameJsonKey()])) {
			$data->id = $json[$this->usernameJsonKey()];
		}
		if (isset($json[$this->passwordJsonKey()])) {
			$data->id = $json[$this->passwordJsonKey()];
		}
		if (isset($json[$this->deviceIdJsonKey()])) {
			$data->id = $json[$this->deviceIdJsonKey()];
		}
		return $data;
	}

}
