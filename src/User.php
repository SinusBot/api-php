<?php
/**
 * Class User | src/User.php
 *
 * A single User with his available actions
 *
 * @package      SinusBot
 * @author       Max Schmitt <max@schmitt.mx>
 */

namespace SinusBot;

/**
 * User Instance
 *
 * User represents a single User of the SinusBot
 */
class User extends RestClient
{
  /**
  * UUID holds the User UUID
  * @var array
  */
    public $uuid = null;
  /**
  * User stores the initial received user data
  * @var array
  */
    private $user = null;
  /**
  * __construct
  *
  * @param  string  $token    SinusBot auth token
  * @param  string  $url      SinusBot Bot URL
  * @param  int     $timeout  HTTP Timeout which is used to perform HTTP API requests
  * @param  array   $user     SinusBot User array.
  * @return void
  */
    public function __construct($token, $url, $timeout, $user)
    {
        $this->token = $token;
        $this->url = $url;
        $this->timeout = $timeout;
        $this->uuid = $user['id'];
        $this->user = $user;
    }
    /**
    * getName returns the username
    *
    * @return string username
    * @api
    */
    public function getName()
    {
        return array_key_exists('username', $this->user)?$this->user['username']:'';
    }
    /**
    * getUUID returns the uuid
    *
    * @return string user UUID
    * @api
    */
    public function getUUID()
    {
        return $this->uuid;
    }

    /**
    * setPassword
    *
    * @param  string   $password  Password
    * @return array status
    * @api
    */
    public function setPassword($password)
    {
        return $this->request('/bot/users/'.$this->uuid, 'PATCH', [
        'password'=>$password,
        ]);
    }
 
    /**
    * setPrivileges
    *
    * @param integer $privileges Bitmask-Value
    * @return array  status
    * @api
    */
    public function setPrivileges($privileges)
    {
        return $this->request('/bot/users/'.$this->uuid, 'PATCH', [
        'privileges'=>$privileges,
        ]);
    }

    /**
    * setIdentity
    *
    * @param string $identity teamspeak identity
    * @return array status
    * @api
    */
    public function setIdentity($identity)
    {
        return $this->request('/bot/users/'.$this->uuid, 'PATCH', [
        'tsuid'=>$identity,
        ]);
    }


    /**
    * setServergroup
    *
    * @param  string $groupID   TeamSpeak Group ID
    * @return array  status
    * @api
    */
    public function setServergroup($groupID)
    {
        return $this->request('/bot/users/'.$this->uuid, 'PATCH', [
        'tsgid'=>strval($groupID),
        ]);
    }


    /**
    * delete
    *
    * @return array status
    * @api
    */
    public function delete()
    {
        return $this->request('/bot/users/'.$this->uuid, 'DELETE');
    }
}
