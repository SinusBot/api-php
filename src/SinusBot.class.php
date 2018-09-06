<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  sinusbot.class.php
 *   version              :  1.0
 *   last modified        :  06. September 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Manuel Hettche
 *   copyright            :  (C) 2018 SinusBot
 *   email                :  info@ts3index.com
 *   begin                :  25. Juli 2015 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace SinusBot;

class SinusBot extends HttpClient
{
    public $uuid = null;

  /**
  * login logs in to the SinusBot and fetches the token
  *
  * @param string $username SinusBot username
  * @param string $password SinusBot password
  * @return boolean success
  */
    public function login($username, $password)
    {
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
  * @param string $search optional name of the radio station
  * @return array stations
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
    public function createPlaylist($playlistName)
    {
        $resp = $this->request('/bot/playlists', 'POST', [
        "name" => $playlistName,
        ]);
        return new Playlist($this->token, $this->url, $this->timeout, $resp["uuid"]);
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
  * @param string $parent subfolder UUID, empty value = mainfolder
  * @return array status
  */
    public function moveFolder($folderUUID, $parent = "")
    {
        return $this->moveTrack($folderUUID, $parent);
    }
  
  /**
  * renameFolder
  *
  * @param string $folderName Folder
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
  * @param  string  $URL  {YouTube-URL,SoundCloud-URL,Directfile}
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
  * @return array users
  */
    public function getUsers()
    {
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
    public function addUser($username, $password, $privileges = 0)
    {
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
    public function setUserPassword($password, $userUUID)
    {
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
    public function setUserPrivileges($privileges, $userUUID)
    {
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
    public function setUserIdentity($identity, $userUUID)
    {
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
    public function setUserServergroup($groupID, $userUUID)
    {
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
    public function deleteUser($userUUID)
    {
        return $this->request('/bot/users/'.$userUUID, 'DELETE');
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
    public function createInstance($nickname = "SinusBot MusicBot", $backend = "ts3")
    {
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
  * @access private
  * @param  string  $url    http://127.0.0.1:8087
  * @param  string  $uuid  4852efdc-9705-4706-e469-cfvf77favf33
  * @return void
  */
    public function __construct($url = 'http://127.0.0.1:8087', $uuid = null, $timeout = 8000)
    {
        $this->url = $url;
        $this->timeout = $timeout;
        $this->uuid = ($uuid == null) ? $this->getDefaultBot() : $uuid;
    }
}
