<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  sinusbot.class.php
 *   version              :  1.0
 *   last modified        :  06. September 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Manuel Hettche
 *   copyright            :  (C) 2018 TS3index.com
 *   email                :  info@ts3index.com
 *   begin                :  25. Juli 2015 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class HttpClient {
  protected $timeout = NULL;
  protected $token = NULL;
  protected $url = NULL;
  /**
  * request executes a request to the SinusBot API
  *
  * @param string $path    /api/v1/<path>
  * @param string $method  http method
  * @param string $payload http POST payload
  * @param boolean $encoded when not encoded it will be JSON marshalled
  * @return array decoded JSON response
  */
  protected function request($path, $method = "GET", $payload = NULL, $encoded = FALSE) {
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $this->url.'/api/v1'.$path,
        CURLOPT_HTTPHEADER => array(
            "Accept:application/json, text/plain, */*",
            "Content-Type:application/json",
            "Authorization: Bearer ".$this->token
        ),
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT_MS => $this->timeout
    ));
    if ($payload != NULL) {
      if ($encoded) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
      } else {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
      }
    } 
    $data = curl_exec($ch);
    
    if ($data === false) {
      $data = array('success' => false, 'error' => curl_error($ch));
    } else {
      $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      if ($httpcode != 200 AND $httpcode != 201) $data = array('success' => false, 'error' => $this->getError($httpcode));
    }
    
    curl_close($ch);
    return (is_array($data)) ? $data : json_decode($data, TRUE);
  }
    
  
  /**
  * getError returns the string representive to the given http status code
  *
  * @param integer $code http status code
  * @return string http status code string representive
  */  
  private function getError($code = 0) {
    switch ($code) {
        case 100: return 'Continue';
        case 101: return 'Switching Protocols';
        case 200: return 'OK';
        case 201: return 'Created';
        case 202: return 'Accepted';
        case 203: return 'Non-Authoritative Information';
        case 204: return 'No Content';
        case 205: return 'Reset Content';
        case 206: return 'Partial Content';
        case 300: return 'Multiple Choices';
        case 301: return 'Moved Permanently';
        case 302: return 'Moved Temporarily';
        case 303: return 'See Other';
        case 304: return 'Not Modified';
        case 305: return 'Use Proxy';
        case 400: return 'Bad Request';
        case 401: return 'Unauthorized';
        case 402: return 'Payment Required';
        case 403: return 'Forbidden';
        case 404: return 'Not Found';
        case 405: return 'Method Not Allowed';
        case 406: return 'Not Acceptable';
        case 407: return 'Proxy Authentication Required';
        case 408: return 'Request Time-out';
        case 409: return 'Conflict';
        case 410: return 'Gone';
        case 411: return 'Length Required';
        case 412: return 'Precondition Failed';
        case 413: return 'Request Entity Too Large';
        case 414: return 'Request-URI Too Large';
        case 415: return 'Unsupported Media Type';
        case 500: return 'Internal Server Error';
        case 501: return 'Not Implemented';
        case 502: return 'Bad Gateway';
        case 503: return 'Service Unavailable';
        case 504: return 'Gateway Time-out';
        case 505: return 'HTTP Version not supported';
        default: return 'Unknown HTTP status code: ' . $code;
    }
  }
}

class SinusBot extends HttpClient {
  public $uuid = NULL;

  /**
  * login logs in to the SinusBot and fetches the token
  *
  * @param string $username SinusBot username
  * @param string $password SinusBot password
  * @return boolean success
  */
  public function login($username, $password) {
    $login = $this->request('/bot/login', 'POST', [
      'username' => $username,
      'password' => $password,
      'botId' => $this->uuid,
      ]);
    if ($login != NULL AND isset($login['token'])) $this->token = $login['token'];
    return $login['success'];
  }
  
  /**
  * getFiles returns the files for the user account
  *
  * @return array files
  */
  public function getFiles() {
    return $this->request('/bot/files');
  }
  
  /**
  * getRadioStations returns the imported radio stations
  *
  * @param string $search optional name of the radio station
  * @return array stations
  */
  public function getRadioStations($search = "") {
    return $this->request('/bot/stations?q='.urlencode($search));
  }
  
  /**
  * getInfos returns the bot infos
  *
  * @return array bot infos
  */
  public function getInfos() {
    return $this->request('/bot/info');
  }
  
  /**
  * getPlaylists returns the playlists
  *
  * @return array playlists
  */
  public function getPlaylists() {
    $playlists = $this->request('/bot/playlists');
    $out = [];
    foreach ($playlists as $playlist) {
      array_push($out, new Playlist($this->token, $this->url, $this->timeout, $playlist));
    }
    return $out;
  }
  
  /**
  * createPlaylist creates a new playlist
  *
  * @param string $playlistName name of the playlist
  * @return array status
  */
  public function createPlaylist($playlistName) {
    return $this->request('/bot/playlists', 'POST', [
      "name" => $playlistName,
    ]);
  }

  /**
  * importPlaylist imports a new playlist from youtube-dl
  *
  * @param string $url youtube-dl URL
  * @return array status
  */
  public function importPlaylist($url) {
    return $this->request('/bot/playlists', 'POST', [
      "importFrom" => $url,
    ]);
  }
  
  /**
  * moveTrack
  *
  * @param string $trackUUID uuid of the track
  * @param string $parent subfolder UUID, empty value means root folder
  * @return array status
  */
  public function moveTrack($trackUUID, $parent = "") {
    return $this->request('/bot/files/'.$trackUUID, 'PATCH', [
      "parent" => $parent,
    ]);
  }
  
  /**
  * editTrack
  *
  * @param string $trackUUID uuid of the track
  * @param string $title title
  * @param string $artist artist
  * @param string $album album
  * @return array status
  */
  public function editTrack($trackUUID, $title, $artist = "", $album = "") {
    return $this->request('/bot/files/'.$trackUUID, 'PATCH', [
      "displayTitle" => $title,
      "title" => $title,
      "artist" => $artist,
      "album" => $album,
    ]);
  }
  
  /**
  * deleteTrack
  *
  * @param string $trackUUID track uuid
  * @return array status
  */
  public function deleteTrack($trackUUID) {
    return $this->request('/bot/files/'.$trackUUID, 'DELETE');
  }
  
  /**
  * addURL
  *
  * @param string $url stream URL
  * @param string $title track title
  * @param string $parent subfolder UUID, empty value means root folder
  * @return array status
  */
  public function addURL($url, $title, $parent = "") {
    return $this->request('/bot/url', 'POST', [
      "url" => $url,
      "title" => $title,
      "parent" => $parent,
    ]);
  }
  
  /**
  * addFolder
  *
  * @param string $folderName folder name
  * @param string $parent subfolder UUID, empty value means root folder
  * @return array status
  */
  public function addFolder($folderName = "Folder", $parent = "") {
    return $this->request('/bot/folders', 'POST', [
      "name" => $folderName,
      "parent" => $parent,
    ]);
  }
  
  /**
  * moveFolder
  *
  * @param string $folderUUID folder uuid
  * @param string $parent subfolder UUID, empty value = mainfolder
  * @return array status
  */
  public function moveFolder($folderUUID, $parent = "") {
    return $this->moveTrack($folderUUID, $parent);
  }
  
  /**
  * renameFolder
  *
  * @param string $folderName Folder
  * @param string $folderUUID uuid of the folder
  * @return array status
  */
  public function renameFolder($folderName, $folderUUID) {
    return $this->request('/bot/files/'.$folderUUID, 'PATCH', [
      "uuid" => $folderUUID,
      "type" => "folder",
      "title" => $folderName,
    ]);
  }
  
  /**
  * deleteFolder
  *
  * @param string $folderUUID uuid of the folder
  * @return array status
  */
  public function deleteFolder($folderUUID) {
    return $this->deleteTrack($folderUUID);
  }
  
  /**
  * getJobs
  *
  * @return array
  */
  public function getJobs() {
    return $this->request('/bot/jobs');
  }
  
  
  /**
  * addJob
  *
  * @param  string  $URL  {YouTube-URL,SoundCloud-URL,Directfile}
  * @return array status
  */
  public function addJob($URL) {
    return $this->request('/bot/jobs', 'POST', [
      'url'=>$URL,
    ]);
  }
  
  
  /**
  * deleteJob
  *
  * @param string $jobUUID job uuid
  * @return array status
  */
  public function deleteJob($jobUUID) {
    return $this->request('/bot/jobs/'.$jobUUID, 'DELETE');
  }
  
  
  /**
  * deleteFinishedJobs
  *
  * @return array status
  */
  public function deleteFinishedJobs() {
    return $this->request('/bot/jobs', 'DELETE');
  }
  
  
  /**
  * uploadTrack
  *
  * @param  string  $path  /var/www/song.mp3
  * @return array status
  */
  public function uploadTrack($path) {
    return $this->request('/bot/upload', 'POST', file_get_contents($path));
  }
  
  
  
  /**
  * getUsers
  *
  * @return array users
  */
  public function getUsers() {
    return $this->request('/bot/users');
  }
  
  
  /**
  * addUser
  *
  * @param  string   $username    Username
  * @param  string   $password    Password
  * @param  integer  $privileges  Bitmask-Value
  * @return array status
  */
  public function addUser($username, $password, $privileges = 0) {
    return $this->request('/bot/users', 'POST', [
      'username'=>$username,
      'password'=>$password,
      'privileges'=>$privileges,
    ]);
  }
  
  
  /**
  * setUserPassword
  *
  * @param  string   $password  Password
  * @param  string   $userUUID  65f7473e-f820-4114-b3df-1a48adc74aeb
  * @return array status
  */
  public function setUserPassword($password, $userUUID) {
    return $this->request('/bot/users/'.$userUUID, 'PATCH', [
      'password'=>$password,
    ]);
  }
  
  
/**
  * setUserPrivileges
  *
  * @param integer $privileges Bitmask-Value
  * @param string $userUUID 65f7473e-f820-4114-b3df-1a48adc74aeb
  * @return array status
  */
  public function setUserPrivileges($privileges, $userUUID) {
    return $this->request('/bot/users/'.$userUUID, 'PATCH', [
      'privileges'=>$privileges,
    ]);
  }
  
  /**
  * setUserIdentity
  *
  * @param string $identity Zzbfw9S5ttDeAThBhop6TlwCaRo=
  * @param string $userUUID 65f7473e-f820-4114-b3df-1a48adc74aeb
  * @return array status
  */
  public function setUserIdentity($identity, $userUUID) {
    return $this->request('/bot/users/'.$userUUID, 'PATCH', [
      'tsuid'=>$identity,
    ]);
  }
  
  
  /**
  * setUserServergroup
  *
  * @param  string   $groupID   6
  * @param  string   $userUUID  65f7473e-f820-4114-b3df-1a48adc74aeb
  * @return array status
  */
  public function setUserServergroup($groupID, $userUUID) {
    return $this->request('/bot/users/'.$userUUID, 'PATCH', [
      'tsgid'=>$groupID,
    ]);
  }
  
  
  /**
  * deleteUser
  *
  * @param  string  $userUUID  65f7473e-f820-4114-b3df-1a48adc74aeb
  * @return array status
  */
  public function deleteUser($userUUID) {
    return $this->request('/bot/users/'.$userUUID, 'DELETE');
  }
  
  
  /**
  * getInstances
  *
  * @return []Instance
  * @api
  */
  public function getInstances() {
    $instances =  $this->request('/bot/instances');
    $out = [];
    foreach ($instances as $instance) {
        array_push($out, new Instance($this->token, $this->url, $this->timeout, $instance));
    }
    return $out;
  }
  
  
  /**
  * createInstance
  *
  * @param  string  $nickname  Nickname
  * @return array status
  */
  public function createInstance($nickname = "SinusBot MusicBot", $backend = "ts3") {
    return $this->request('/bot/instances', 'POST', [
      "backend" => $backend,
      "nick" => $nickname,
    ]);
  }
  
  /**
  * getDefaultBot
  *
  * @return string
  */
  public function getDefaultBot() {
    $req = $this->request('/botId'); 
    return (isset($req['defaultBotId'])) ? $req['defaultBotId'] : NULL;
  }
  
  
  /**
  * getBotLog
  *
  * @return array log
  */
  public function getBotLog() {
    return $this->request('/bot/log');
  }
  
  
  /**
  * getThumbnail
  *
  * @param  string  $thumbnail  see getFiles()
  * @return string  url
  */
  public function getThumbnail($thumbnail) {
    return $this->url.'/cache/'.$thumbnail;
  }
  
  
  /**
  * __construct
  *
  * @access private
  * @param  string  $url    http://127.0.0.1:8087
  * @param  string  $uuid  4852efdc-9705-4706-e469-cfvf77favf33
  * @return void
  */
  function __construct($url = 'http://127.0.0.1:8087', $uuid = NULL, $timeout = 8000) {
    $this->url = $url;
    $this->timeout = $timeout;
    $this->uuid = ($uuid == NULL) ? $this->getDefaultBot() : $uuid;
  }
}

class Instance extends HttpClient {
  public $uuid = nulL;
  public $instance = NULL;
  function __construct($token, $url, $timeout, $instance) {
    $this->token = $token;
    $this->url = $url;
    $this->timeout = $timeout;
    $this->instance = $instance;
    $this->uuid = $instance["uuid"];
  }
  /**
  * isPlaying
  *
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return boolean
  */
  public function isPlaying() {
    return $this->getStatus()['playing'];
  }
  
  /**
  * isRunning
  *
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return boolean
  */
  public function isRunning() {
    return $this->getStatus()['running'];
  }

  /**
  * deleteInstance
  *
  * @return array status
  */
  public function delete() {
    return $this->request('/bot/instances/'.$this->uuid, 'DELETE');
  }
  
  
  /**
  * spawnInstance
  *
  * @return array status
  */
  public function spawn() {
    return $this->request('/bot/i/'.$this->uuid.'/spawn', 'POST', '');
  }
  
  /**
  * respawnInstance
  *
  * @return array status
  */
  public function respawn() {
    return $this->request('/bot/i/'.$this->uuid.'/respawn', 'POST', '');
  }
  
  /**
  * killInstance
  *
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function kill() {
    return $this->request('/bot/i/'.$this->uuid.'/kill', 'POST', '');
  }
  
  /**
  * getWebStream
  *
  * requires: EnableWebStream = true
  *
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return string  url (opus-encoded-ogg-stream)
  * @api
  */
  public function getWebStream() {
    $token = $this->getWebStreamToken();
    if ($token == NULL) return NULL;
    
    return $this->url.'/api/v1/b/bot/i/'.$this->uuid.'/stream/'.$token;
  }
  
  /**
  * getWebStreamToken
  *
  * requires: EnableWebStream = true
  *
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return string  token
  * @api
  */
  public function getWebStreamToken() {
    $tokenRequest = $this->request('/bot/i/'.$this->uuid.'/streamToken', 'POST', '');
    return (isset($tokenRequest['token'])) ? $tokenRequest['token'] : NULL;
  }
    
  /**
  * getVolume
  *
  * @return integer
  * @api
  */
  public function getVolume() {
    return $this->getStatus()['volume'];
  }
  
  
  /**
  * setVolume
  *
  * @param  string  $volume        {0-100}
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  * @api
  */
  public function setVolume($volume = 50) {
    return $this->request('/bot/i/'.$this->uuid.'/volume/set/'.$volume, 'POST', '');
  }
  
  
  /**
  * setVolumeUp
  *
  * @return array status
  * @api
  */
  public function setVolumeUp() {
    return $this->request('/bot/i/'.$this->uuid.'/volume/up', 'POST', '');
  }
  
  /**
  * setVolumeDown
  *
  * @return array status
  * @api
  */
  public function setVolumeDown() {
    return $this->request('/bot/i/'.$this->uuid.'/volume/down', 'POST', '');
  }

  /**
  * getStatus
  *
  * @return array status
  * @api
  */
  public function getStatus() {
    return $this->request('/bot/i/'.$this->uuid.'/status');;
  }

  /**
  * getInstanceLog
  *
  * @return array log
  * @api
  */
  public function getInstanceLog() {
    return $this->request('/bot/i/'.$this->uuid.'/log');
  }

  /**
  * getSettings
  *
  * @return array users
  * @api
  */
  public function getSettings() {
    return $this->request('/bot/i/'.$this->uuid.'/settings');
  }
  
  /**
  * editSettings
  *
  * @param array $data array of properties
  * @return array status
  * @api
  */
  public function editSettings($data) {
    return $this->request('/bot/i/'.$this->uuid.'/settings', 'POST', $data);
  }
  
  
  /**
  * getChannels
  *
  * @return array channels
  * @api
  */
  public function getChannels() {
    return $this->request('/bot/i/'.$this->uuid.'/channels');
  }
  
  /**
  * uploadAvatar
  *
  * @param string $path /var/www/image.jpg
  * @return array status
  */
  public function uploadAvatar($path) {
    return $this->request('/bot/i/'.$this->uuid.'/avatar', 'POST', file_get_contents($path));
  }
  
  /**
  * deleteAvatar
  *
  * @return array status
  */
  public function deleteAvatar() {
    return $this->request('/bot/i/'.$this->uuid.'/avatar', 'DELETE');
  }

  /**
  * getQueueTracks
  *
  * @return array files
  */
  public function getQueueTracks() {
    return $this->request('/bot/i/'.$this->uuid.'/queue');
  }

  /**
  * appendQueueTrack
  *
  * @param string $trackUUID uuid of the track
  * @return array status
  */
  public function appendQueueTrack($trackUUID) {
    return $this->request('/bot/i/'.$this->uuid.'/queue/append/'.$trackUUID, 'POST', "");
  }
  
  /**
  * prependQueueTrack
  *
  * @param string $trackUUID track uuid
  * @return array status
  */
  public function prependQueueTrack($trackUUID) {
    return $this->request('/bot/i/'.$this->uuid.'/queue/prepend/'.$trackUUID, 'POST', "");
  }
  
  
  /**
  * deleteQueueTrack
  *
  * @param integer $trackPosition  first entry = 0
  * @return array status
  */
  public function deleteQueueTrack($trackPosition, $instanceUUID) {
    $currentTracks = $this->getQueueTracks();
    if ($currentTracks == NULL OR !is_array($currentTracks)) return NULL;
    unset($currentTracks[$trackPosition]);
    
    return $this->request('/bot/i/'.$this->uuid.'/queue', 'PATCH', array_values($currentTracks));
  }
  
  
  /**
  * deleteQueueTracks
  *
  * @return array status
  */
  public function deleteQueueTracks() {
    return $this->request('/bot/i/'.$this->uuid.'/queue', 'PATCH', []);
  }
  
  
  /**
  * say
  *
  * @param  string  $text          Welcome
  * @param  string  $locale        en
  * @return array status
  */
  public function say($text, $locale) {
    return $this->request('/bot/i/'.$this->uuid.'/say', 'POST', [
      "text" => $text,
      "locale" => $locale,
    ]);
  }
  
  /**
  * playTrack
  *
  * @param  string  $trackUUID     6da519a3-5aa3-4f5e-9e2d-81c88e9159ea
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function playTrack($trackUUID) {
    return $this->request('/bot/i/'.$this->uuid.'/play/byId/'.$trackUUID, 'POST', '');
  }
  
  
  /**
  * playURL
  *
  * @param string $url stream url
  * @return array status
  */
  public function playURL($url) {
    return $this->request('/bot/i/'.$this->uuid.'/playUrl?url='.urlencode($url), 'POST', '');
  }
  
  
/**
  * playPlaylist
  *
  * @param string $playlistUUID uuid of a playlist
  * @param string $playlistIndex 0
  * @return array status
  */
  public function playPlaylist($playlistUUID, $playlistIndex = 0) {
    return $this->request('/bot/i/'.$this->uuid.'/play/byList/'.$playlistUUID.'/'.$playlistIndex, 'POST', '');
  }
  
  /**
  * playPrevious
  *
  * @return array status
  */
  public function playPrevious() {
    return $this->request('/bot/i/'.$this->uuid.'/playPrevious', 'POST', '');
  }
  
  
/**
  * playNext
  *
  * @return array status
  */
  public function playNext() {
    return $this->request('/bot/i/'.$this->uuid.'/playNext', 'POST', '');
  }
  
/**
  * playRepeat
  *
  * @param  integer $repeatState   {0=disable,1=enable}
  * @return array status
  */
  public function playRepeat($repeatState = 1) {
    return $this->request('/bot/i/'.$this->uuid.'/repeat/'.$repeatState, 'POST', '');
  }
  
/**
  * playShuffle
  *
  * @param  integer $shuffleState  {0=disable,1=enable}
  * @return array status
  */
  public function playShuffle($shuffleState = 1) {
    return $this->request('/bot/i/'.$this->uuid.'/shuffle/'.$shuffleState, 'POST', '');
  }
  
  
/**
  * stop
  *
  * @return array status
  */
  public function stop() {
    return $this->request('/bot/i/'.$this->uuid.'/stop', 'POST', '');
  }
  
  
/**
  * seekPlayback
  *
  * @param  integer  $position      0
  * @return array status
  */
  public function seekPlayback($position = 0) {
    return $this->request('/bot/i/'.$this->uuid.'/seek/'.$position, 'POST', '');
  }
  
  
/**
  * getPlayedTracks
  *
  * @return array   array of uuids
  */
  public function getPlayedTracks() {
    return $this->request('/bot/i/'.$this->uuid.'/recent', 'POST', '');
  }

}

class Playlist extends HttpClient {
  public $playlist = NULL;
  public $uuid = NULL;
  function __construct($token, $url, $timeout, $playlist) {
    $this->token = $token;
    $this->url = $url;
    $this->timeout = $timeout;
    $this->playlist = $playlist;
    $this->uuid = $playlist["uuid"];
  }
  
  /**
  * rename renames a playlist
  *
  * @param string $playlistName new name for the playlist
  * @return array status
  */
  public function rename($playlistName) {
    return $this->request('/bot/playlists/'.$this->uuid, 'PATCH', [
      "name" => $playlistName,
    ]);
  }
  /**
  * getPlaylistTracks returns the tracks of the given playlist
  *
  * @return array files
  */
  public function getPlaylistTracks() {
    return $this->request('/bot/playlists/'.$this->uuid);
  }
  
/**
  * addPlaylistTrack adds a track to the given playlist
  *
  * @param string $trackUUID uuid of the track
  * @return array status
  */
  public function addPlaylistTrack($trackUUID) {
    return $this->request('/bot/playlists/'.$this->uuid, 'POST', [
      "uuid" => $trackUUID,
    ]);
  }
   
  /**
  * deleteTrack
  *
  * @param integer $trackPosition first entry = 0
  * @return array status
  */
  public function deleteTrack($trackPosition) {
    return $this->request('/bot/playlists/'.$this->uuid.'/'.$trackPosition, 'DELETE');
  }
   
  /**
  * deleteTracks
  *
  * @return array status
  */
  public function deleteTracks() {
    $currentTracks = $this->getTracks();
    if ($currentTracks == NULL OR !is_array($currentTracks)) return NULL;
    
    return $this->request('/bot/bulk/playlist/'.$this->uuid.'/files', 'POST', [
      "op" => "delete",
      "files" => array_keys($currentTracks['entries']),
    ]); 
  }
  /**
  * delete deletes a playlist
  *
  * @return array status
  */
  public function delete() {
    return $this->request('/bot/playlists/'.$this->uuid, 'DELETE');
  }
}
