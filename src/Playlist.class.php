<?php

class Playlist extends HttpClient
{
    public $playlist = null;
    public $uuid = null;
    public function __construct($token, $url, $timeout, $playlist)
    {
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
    public function rename($playlistName)
    {
        return $this->request('/bot/playlists/'.$this->uuid, 'PATCH', [
        "name" => $playlistName,
        ]);
    }
  /**
  * getPlaylistTracks returns the tracks of the given playlist
  *
  * @return array files
  */
    public function getPlaylistTracks()
    {
        return $this->request('/bot/playlists/'.$this->uuid);
    }
  
/**
  * addPlaylistTrack adds a track to the given playlist
  *
  * @param string $trackUUID uuid of the track
  * @return array status
  */
    public function addPlaylistTrack($trackUUID)
    {
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
    public function deleteTrack($trackPosition)
    {
        return $this->request('/bot/playlists/'.$this->uuid.'/'.$trackPosition, 'DELETE');
    }
   
  /**
  * deleteTracks
  *
  * @return array status
  */
    public function deleteTracks()
    {
        $currentTracks = $this->getTracks();
        if ($currentTracks == null or !is_array($currentTracks)) {
            return null;
        }
    
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
    public function delete()
    {
        return $this->request('/bot/playlists/'.$this->uuid, 'DELETE');
    }
}
