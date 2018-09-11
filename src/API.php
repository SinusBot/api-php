<?php
/**
 * Class API | src/API.php
 *
 * Main class which will be used to connect to the SinusBot
 *
 * @package      SinusBot
 * @author       Max Schmitt <max@schmitt.mx>
 */

namespace SinusBot;

/**
 * Class API
 *
 * API is the main class which will be used to connect to the SinusBot
 */
class API extends RestClient
{
  /**
  * UUID stores the SinusBot Bot UUID
  * @var string
  */
    public $uuid = null;
  /**
  * login logs on to the SinusBot and retrieves the token
  *
  * @param string $username SinusBot username
  * @param string $password SinusBot password
  * @param string $uuid     SinusBot Bot UUID
  * @return boolean success
  */
    public function login($username, $password, $uuid = null)
    {
        $this->uuid = !$uuid?$this->getDefaultBot():$uuid;
        $login = $this->request('/bot/login', 'POST', [
        'username' => $username,
        'password' => $password,
        'botId' => $this->uuid,
        ]);
        if ($login != null and isset($login['token'])) {
            $this->token = $login['token'];
        }
        return $login['success'];
    }

  /**
  * getFiles returns the files for the user account
  *
  * @return array files
  */
    public function getFiles()
    {
        return $this->request('/bot/files');
    }
  
  /**
  * getRadioStations returns the imported radio stations
  *
  * @param string $search optional name of the search query
  * @return array radio stations
  */
    public function getRadioStations($search = "")
    {
        return $this->request('/bot/stations?q='.urlencode($search));
    }
  
  /**
  * getInfo returns the bot infos
  *
  * @return array bot infos
  */
    public function getInfo()
    {
        return $this->request('/bot/info');
    }
  
  /**
  * getPlaylists returns the playlists
  *
  * @return array playlists
  */
    public function getPlaylists()
    {
        $playlists = $this->request('/bot/playlists');
        $out = [];
        foreach ($playlists as $playlist) {
            array_push($out, new Playlist($this, $playlist));
        }
        return $out;
    }
  
  /**
  * createPlaylist creates a new playlist
  *
  * @param string $playlistName name of the playlist
  * @return array status
  */
    public function createPlaylist($playlistName)
    {
        $resp = $this->request('/bot/playlists', 'POST', [
        "name" => $playlistName,
        ]);
        $resp['name'] = $playlistName;
        return new Playlist($this, $resp);
    }

  /**
  * importPlaylist imports a new playlist from youtube-dl
  *
  * @param string $url youtube-dl URL
  * @return array status
  */
    public function importPlaylist($url)
    {
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
    public function moveTrack($trackUUID, $parent = "")
    {
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
    public function editTrack($trackUUID, $title, $artist = "", $album = "")
    {
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
    public function deleteTrack($trackUUID)
    {
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
    public function addURL($url, $title, $parent = "")
    {
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
    public function addFolder($folderName = "Folder", $parent = "")
    {
        return $this->request('/bot/folders', 'POST', [
        "name" => $folderName,
        "parent" => $parent,
        ]);
    }
  
  /**
  * moveFolder
  *
  * @param string $folderUUID folder uuid
  * @param string $parent subfolder UUID, empty value means root folder
  * @return array status
  */
    public function moveFolder($folderUUID, $parent = "")
    {
        return $this->moveTrack($folderUUID, $parent);
    }
  
  /**
  * renameFolder
  *
  * @param string $folderName Folder name
  * @param string $folderUUID uuid of the folder
  * @return array status
  */
    public function renameFolder($folderName, $folderUUID)
    {
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
    public function deleteFolder($folderUUID)
    {
        return $this->deleteTrack($folderUUID);
    }
  
  /**
  * getJobs
  *
  * @return array
  */
    public function getJobs()
    {
        return $this->request('/bot/jobs');
    }
  
  
  /**
  * addJob
  *
  * @param  string  $URL  {YouTube-URL, SoundCloud-URL, Directfile}
  * @return array status
  */
    public function addJob($URL)
    {
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
    public function deleteJob($jobUUID)
    {
        return $this->request('/bot/jobs/'.$jobUUID, 'DELETE');
    }
  
  /**
  * deleteFinishedJobs
  *
  * @return array status
  */
    public function deleteFinishedJobs()
    {
        return $this->request('/bot/jobs', 'DELETE');
    }
  
  /**
  * uploadTrack
  *
  * @param  string  $path  /var/www/song.mp3
  * @return array status
  */
    public function uploadTrack($path)
    {
        return $this->request('/bot/upload', 'POST', file_get_contents($path));
    }
  
  /**
  * getUsers
  *
  * @return User[] users
  */
    public function getUsers()
    {
        $users = $this->request('/bot/users');
        $out = [];
        foreach ($users as $user) {
            array_push($out, new User($this, $user));
        }
        return $out;
    }
  
  /**
  * addUser
  *
  * @param  string   $username    Username
  * @param  string   $password    Password
  * @param  integer  $privileges  Bitmask-Value
  * @return User user object
  */
    public function addUser($username, $password, $privileges = 0)
    {
        $this->request('/bot/users', 'POST', [
        'username'=>$username,
        'password'=>$password,
        'privileges'=>$privileges,
        ]);
        $users = $this->getUsers();
        foreach ($users as $user) {
            if ($user->getName() === $username) {
                return $user;
            }
        }
    }
  /**
  * getUserByUUID
  *
  * @param string $uuid User ID
  * @return User user object
  */
    public function getUserByUUID($uuid)
    {
        $users = $this->getUsers();
        foreach ($users as $user) {
            if ($user->getUUID() === $uuid) {
                return $user;
            }
        }
    }
  /**
  * getUserByName
  *
  * @param string $username Username
  * @return User user object
  */
    public function getUserByName($username)
    {
        $users = $this->getUsers();
        foreach ($users as $user) {
            if ($user->getName() === $username) {
                return $user;
            }
        }
    }
  
  /**
  * getInstances
  *
  * @return []Instance
  * @api
  */
    public function getInstances()
    {
        $instances =  $this->request('/bot/instances');
        $out = [];
        foreach ($instances as $instance) {
            array_push($out, new Instance($this, $instance));
        }
        return $out;
    }
  
  
  /**
  * createInstance
  *
  * @param  string  $nickname  Name of the Bot
  * @param  string  $backend   SinusBot backend (Discord or TS³)
  * @return array status
  */
    public function createInstance($nickname = "SinusBot MusicBot", $backend = "ts3")
    {
        $resp = $this->request('/bot/instances', 'POST', [
        "backend" => $backend,
        "nick" => $nickname,
        ]);
        return $this->getInstanceByUUID($resp['uuid']);
    }
  
  /**
  * getDefaultBot
  *
  * @return string
  */
    public function getDefaultBot()
    {
        $req = $this->request('/botId');
        return (isset($req['defaultBotId'])) ? $req['defaultBotId'] : null;
    }
  
  
  /**
  * getBotLog
  *
  * @return array log
  */
    public function getBotLog()
    {
        return $this->request('/bot/log');
    }
  
  
  /**
  * getThumbnail
  *
  * @param  string  $thumbnail  see getFiles()
  * @return string  url
  */
    public function getThumbnail($thumbnail)
    {
        return $this->url.'/cache/'.$thumbnail;
    }
  
  
  /**
  * __construct
  *
  * @param  string  $url      SinusBot Bot URL
  * @param  string  $timeout  HTTP Timeout which is used to perform HTTP API requests
  * @return void
  */
    public function __construct($url = 'http://127.0.0.1:8087', $timeout = 8000)
    {
        $this->url = $url;
        $this->timeout = $timeout;
    }
  /**
  * getInstanceByUUID
  *
  * @param  string  $uuid     SinusBot instance UUID
  * @return Instance
  */
    public function getInstanceByUUID($uuid)
    {
        $instance = $this->request("/bot/i/".$uuid."/settings");
        $instance['uuid'] = $uuid;
        return new Instance($this, $instance);
    }
}
