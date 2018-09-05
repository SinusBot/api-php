<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  sinusbot.class.php
 *   version              :  0.3
 *   last modified        :  11. October 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Manuel Hettche
 *   copyright            :  (C) 2015 TS3index.com
 *   email                :  info@ts3index.com
 *   begin                :  25. Juli 2015
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *
 *  This class is a powerful library for querying SinusBot from your website.
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class SinusBot {
  public $wiURL = NULL;
  public $wiToken = NULL;
  public $wiTimeout = NULL;
  public $apiURL = NULL;
  public $botUUID = NULL;
  public $instanceUUID = NULL;


/**
  * login
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [token] => uAG0eyJpIjoiYjg0ZmNkZGYtZTdjZS00OGQyLWI2NTQtMmExYTJiZTY3ZDc[...]
  *      [botId] => b115f224-0687-492f-a88c-ccd9a6582f44
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $username  admin
  * @param  string  $password  foobar
  * @return boolean success
  */
  public function login($username, $password) {
    $login = $this->request('/bot/login', 'POST', json_encode(array('username' => $username, 'password' => $password, 'botId' => $this->botUUID)));
    if ($login != NULL AND isset($login['token'])) $this->wiToken = $login['token'];
    return $login;
  }
  
  
/**
  * getFiles
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [0] => Array
  *          (
  *              [uuid] => f1152001-caa1-4237-9bf3-ed6480bb0c0c
  *              [parent] => 
  *              [type] => folder
  *              [title] => Proximity
  *          )
  *      [1] => Array
  *          (
  *              [uuid] => 2340b137-6e1a-4774-a8a5-60eda927d1f0
  *              [parent] => f1152001-caa1-4237-9bf3-ed6480bb0c0c
  *              [type] => 
  *              [codec] => -
  *              [duration] => 177098
  *              [bitrate] => 253588
  *              [channels] => 2
  *              [samplerate] => 44100
  *              [filesize] => 5675506
  *              [filename] => https://www.youtube.com/watch?v=J2Jx7fMRis8
  *              [title] => Major Lazer & DJ Snake - Lean On (ft. MØ)
  *              [artist] => Proximity
  *          )
  *      [2] => Array
  *          (
  *              [uuid] => 6da519a3-5aa3-4f5e-9e2d-81c88e9159ea
  *              [parent] => 
  *              [type] => url
  *              [filename] => http://stream01.iloveradio.de/iloveradio1.mp3
  *              [title] => I Love Radio
  *          )
  *      [...]
  *  )
  * </code>
  *
  * @access public
  * @return array files
  */
  public function getFiles() {
    return $this->request('/bot/files');
  }
  
  
/**
  * getRadioStations
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [0] => Array
  *          (
  *              [n] => I Love 2 Dance (www.iloveradio.de)
  *              [u] => http://87.230.53.70:80/iloveradio2.mp3
  *              [g] => electronic top elektro pop 40 house dance
  *              [b] => 128
  *          )
  *      [1] => Array
  *          (
  *              [n] => I Love Ibiza (www.iloveradio.de)
  *              [u] => http://80.237.157.81:80/iloveradio6.mp3
  *              [g] => house electronic electro deephouse ibiza chillout
  *              [b] => 128
  *          )
  *      [...]
  *  )
  * </code>
  *
  * @access public
  * @param  string  $search  iloveradio
  * @return array stations
  */
  public function getRadioStations($search = "") {
    return $this->request('/bot/stations?q='.urlencode($search));
  }
  
  
/**
  * getStatus
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [v] => 0.9.9-b533b94
  *      [currentTrack] => Array
  *          (
  *              [uuid] => 6da519a3-5aa3-4f5e-9e2d-81c88e9159ea
  *              [parent] => 9ecbe0ed-8963-443a-9e23-4a815aa01dc2
  *              [type] => url
  *              [filename] => http://stream01.iloveradio.de/iloveradio1.mp3
  *              [title] => I Love Radio
  *              [tempArtist] => MARTIN TUNGEVAAG
  *              [tempTitle] => WICKED WONDERLAND
  *          )
  *      [position] => 204852
  *      [running] => 1
  *      [playing] => 1
  *      [playlist] => 
  *      [playlistTrack] => -1
  *      [shuffle] => 
  *      [repeat] => 
  *      [volume] => 5
  *      [needsRestart] => 
  *      [queueLen] => 0
  *      [queueVersion] => 0
  *      [modes] => 1
  *      [downloaded] => 49804057
  *  )
  * </code>
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function getStatus($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/status');;
  }
  
  
/**
  * getInfos
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [bot] => Array
  *          (
  *              [id] => b115f224-0687-492f-a88c-ccd9a6582f44
  *              [alias] => 
  *              [spaceUsed] => 2250578715
  *              [limitSpace] => 0
  *              [limitFiles] => 0
  *              [limitPlaylists] => 20
  *              [limitInstances] => 2
  *              [limitUsers] => 100
  *              [limitDownloadRate] => 0
  *              [limitDownloadSize] => 0
  *              [locked] => 0
  *              [deleted] => 0
  *              [disallowDownload] => 0
  *              [disallowStreaming] => 0
  *              [downloadedBytes] => 14344525900
  *              [statHTTPRequests] => 187734
  *              [statPlayCount] => 46710
  *              [statCommandCount] => 0
  *          )
  *      [usageMemory] => 49.11547
  *      [system] => Array
  *          (
  *              [codecs] => Array
  *                  (
  *                      [0] => Multicolor charset for Commodore 64
  *                      [1] => Apple Intermediate Codec
  *                      [2] => Autodesk RLE
  *                      [...]
  *                  )
  *              [formats] => Array
  *                  (
  *                      [0] => MP3 (MPEG audio layer 3)
  *                      [1] => WAV / WAVE (Waveform Audio)
  *                      [2] => ADTS AAC (Advanced Audio Coding)
  *                      [...]
  *                  )
  *          )
  *  )
  * </code>
  *
  * @access public
  * @return array playlists
  */
  public function getInfos() {
    return $this->request('/bot/info');
  }
  
  
/**
  * getInstanceLog
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [0] => Array
  *          (
  *              [m] => Done playing
  *              [t] => 1437858096
  *              [s] => 5
  *          )
  *      [1] => Array
  *          (
  *              [m] => Playing next from queue: 64c04ea4-f260-4264-9cdc-93ef26b0cffb
  *              [t] => 1437858097
  *              [s] => 3
  *          )
  *      [2] => Array
  *          (
  *              [m] => STOP [admin] OK
  *              [t] => 1437858193
  *              [s] => 3
  *          )
  *      [...]
  *  )
  * </code>
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array log
  */
  public function getInstanceLog($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/log');
  }
  
  
/**
  * getPlaylists
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [0] => Array
  *          (
  *              [uuid] => a5414bca-ba23-435f-bec4-79d7a1cfd138
  *              [name] => Proximity
  *              [count] => 51
  *          )
  *      [1] => Array
  *          (
  *              [uuid] => fb9491d9-b67d-4d5d-94a7-349366532d59
  *              [name] => Radio
  *              [count] => 4
  *          )
  *      [...]
  *  )
  * </code>
  *
  * @access public
  * @return array playlists
  */
  public function getPlaylists() {
    return $this->request('/bot/playlists');
  }
  
  
/**
  * createPlaylist
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *      [uuid] => a5414bca-ba23-435f-bec4-79d7a1cfd138
  *  )
  * </code>
  *
  * @access public
  * @param  string  $playlistName  Playlist
  * @return array status
  */
  public function createPlaylist($playlistName) {
    return $this->request('/bot/playlists', 'POST', json_encode(array("name" => $playlistName)));
  }

/**
 * importPlaylist
 * @access public
 * @param  string $url youtube-dl URL
 * @return array status
 */
  public function importPlaylist($url) {
    return $this->request('/bot/playlists', 'POST', json_encode(array("importFrom" => $url)));
  }
/**
  * renamePlaylist
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $playlistName  Playlist
  * @param  string  $playlistUUID  a5414bca-ba23-435f-bec4-79d7a1cfd138
  * @return array status
  */
  public function renamePlaylist($playlistName, $playlistUUID) {
    return $this->request('/bot/playlists/'.$playlistUUID, 'PATCH', json_encode(array("name" => $playlistName)));
  }
  
  
/**
  * deletePlaylist
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $playlistUUID  a5414bca-ba23-435f-bec4-79d7a1cfd138
  * @return array status
  */
  public function deletePlaylist($playlistUUID) {
    return $this->request('/bot/playlists/'.$playlistUUID, 'DELETE');
  }
  
  
/**
  * getPlaylistTracks
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [uuid] => a5414bca-ba23-435f-bec4-79d7a1cfd138
  *      [name] => Proximity
  *      [entries] => Array
  *          (
  *              [0] => 56e046b5-05e9-4e53-b5be-42a3176f368e
  *              [1] => fdb9d65d-90b0-4ed0-b17f-aff5d627db6d
  *              [2] => 55add8ac-e265-489c-afa7-0bd8877f8b24
  *              [3] => e7dd610c-39b9-4d0b-abbe-54010b696ae7
  *              [...]
  *          )
  *      [count] => 51
  *  )
  * </code>
  *
  * @access public
  * @param  string  $playlistUUID  a5414bca-ba23-435f-bec4-79d7a1cfd138
  * @return array files
  */
  public function getPlaylistTracks($playlistUUID) {
    return $this->request('/bot/playlists/'.$playlistUUID);
  }
  
  
/**
  * addPlaylistTrack
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $trackUUID     2340b137-6e1a-4774-a8a5-60eda927d1f0
  * @param  string  $playlistUUID  a5414bca-ba23-435f-bec4-79d7a1cfd138
  * @return array status
  */
  public function addPlaylistTrack($trackUUID, $playlistUUID) {
    return $this->request('/bot/playlists/'.$playlistUUID, 'POST', json_encode(array("uuid" => $trackUUID)));
  }
  
  
/**
  * deletePlaylistTrack
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  integer  $trackPosition  first entry = 0
  * @param  string   $playlistUUID   a5414bca-ba23-435f-bec4-79d7a1cfd138
  * @return array status
  */
  public function deletePlaylistTrack($trackPosition, $playlistUUID) {
    return $this->request('/bot/playlists/'.$playlistUUID.'/'.$trackPosition, 'DELETE');
  }
  
  
/**
  * deletePlaylistTracks
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string   $playlistUUID   a5414bca-ba23-435f-bec4-79d7a1cfd138
  * @return array status
  */
  public function deletePlaylistTracks($playlistUUID) {
    $currentTracks = $this->getPlaylistTracks($playlistUUID);
    if ($currentTracks == NULL OR !is_array($currentTracks)) return NULL;
    
    return $this->request('/bot/bulk/playlist/'.$playlistUUID.'/files', 'POST', json_encode(array("op" => "delete", "files" => array_keys($currentTracks['entries']))));
  }
  
  
/**
  * getQueueTracks
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [0] => 64c04ea4-f260-4264-9cdc-93ef26b0cffb
  *      [1] => c5c6ca41-258d-4478-8043-c1a5f037c835
  *  )
  * </code>
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array files
  */
  public function getQueueTracks($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/queue');
  }
  
  
/**
  * appendQueueTrack
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $trackUUID     2340b137-6e1a-4774-a8a5-60eda927d1f0
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function appendQueueTrack($trackUUID, $instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/queue/append/'.$trackUUID, 'POST', "");
  }
  
  
/**
  * prependQueueTrack
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $trackUUID     2340b137-6e1a-4774-a8a5-60eda927d1f0
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function prependQueueTrack($trackUUID, $instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/queue/prepend/'.$trackUUID, 'POST', "");
  }
  
  
/**
  * deleteQueueTrack
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  integer  $trackPosition  first entry = 0
  * @param  string   $instanceUUID   6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function deleteQueueTrack($trackPosition, $instanceUUID) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    
    $currentTracks = $this->getQueueTracks($instanceUUID);
    if ($currentTracks == NULL OR !is_array($currentTracks)) return NULL;
    unset($currentTracks[$trackPosition]);
    
    return $this->request('/bot/i/'.$instanceUUID.'/queue', 'PATCH', json_encode(array_values($currentTracks)));
  }
  
  
/**
  * deleteQueueTracks
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string   $instanceUUID   6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function deleteQueueTracks($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/queue', 'PATCH', json_encode(array()));
  }
  
  
/**
  * say
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $text          Welcome
  * @param  string  $locale        en
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function say($text, $locale, $instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/say', 'POST', json_encode(array("text" => $text, "locale" => $locale)));
  }
  
  
/**
  * playTrack
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $trackUUID     6da519a3-5aa3-4f5e-9e2d-81c88e9159ea
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function playTrack($trackUUID, $instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/play/byId/'.$trackUUID, 'POST', '');
  }
  
  
/**
  * playURL
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $url           http://stream06.iloveradio.de/iloveradio1.mp3
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function playURL($url, $instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/playUrl?url='.urlencode($url), 'POST', '');
  }
  
  
/**
  * playPlaylist
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $playlistUUID   a5414bca-ba23-435f-bec4-79d7a1cfd138
  * @param  string  $playlistIndex  0
  * @param  string  $instanceUUID   6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function playPlaylist($playlistUUID, $playlistIndex = 0, $instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/play/byList/'.$playlistUUID.'/'.$playlistIndex, 'POST', '');
  }
  
  
/**
  * playPrevious
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function playPrevious($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/playPrevious', 'POST', '');
  }
  
  
/**
  * playNext
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function playNext($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/playNext', 'POST', '');
  }
  
  
/**
  * playRepeat
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  integer $repeatState   {0=disable,1=enable}
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function playRepeat($repeatState = 1, $instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/repeat/'.$repeatState, 'POST', '');
  }
  
  
/**
  * playShuffle
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  integer $shuffleState  {0=disable,1=enable}
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function playShuffle($shuffleState = 1, $instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/shuffle/'.$shuffleState, 'POST', '');
  }
  
  
/**
  * stop
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function stop($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/stop', 'POST', '');
  }
  
  
/**
  * seekPlayback
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  integer  $position      0
  * @param  string   $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function seekPlayback($position = 0, $instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/seek/'.$position, 'POST', '');
  }
  
  
/**
  * getPlayedTracks
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array   array of uuids
  */
  public function getPlayedTracks($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/recent', 'POST', '');
  }
  
  
/**
  * moveTrack
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $trackUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @param  string  $parent     subfolder UUID, empty value = mainfolder
  * @return array status
  */
  public function moveTrack($trackUUID, $parent = "") {
    return $this->request('/bot/files/'.$trackUUID, 'PATCH', json_encode(array("parent" => $parent)));
  }
  
  
/**
  * editTrack
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $trackUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @param  string  $title      Title
  * @param  string  $artist     Artist
  * @param  string  $album      Album
  * @return array status
  */
  public function editTrack($trackUUID, $title, $artist = "", $album = "") {
    return $this->request('/bot/files/'.$trackUUID, 'PATCH', json_encode(array("displayTitle" => $title, "title" => $title, "artist" => $artist, "album" => $album)));
  }
  
  
/**
  * deleteTrack
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $trackUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function deleteTrack($trackUUID) {
    return $this->request('/bot/files/'.$trackUUID, 'DELETE');
  }
  
  
/**
  * getVolume
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return integer
  */
  public function getVolume($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->getStatus($instanceUUID)['volume'];
  }
  
  
/**
  * setVolume
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $volume        {0-100}
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function setVolume($volume = 50, $instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/volume/set/'.$volume, 'POST', '');
  }
  
  
/**
  * setVolumeUp
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function setVolumeUp($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/volume/up', 'POST', '');
  }
  
  
/**
  * setVolumeDown
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function setVolumeDown($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/volume/down', 'POST', '');
  }
  
  
/**
  * addURL
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *      [uuid] => 1fa468a3-a39c-41c4-bab7-ff9491dbfb12
  *  )
  * </code>
  *
  * @access public
  * @param  string  $url     http://stream06.iloveradio.de/iloveradio1.mp3
  * @param  string  $title   I Love Radio
  * @param  string  $parent  subfolder UUID, empty value = mainfolder
  * @return array status
  */
  public function addURL($url, $title, $parent = "") {
    return $this->request('/bot/url', 'POST', json_encode(array("url" => $url, "title" => $title, "parent" => $parent)));
  }
  
  
/**
  * addFolder
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *      [uuid] => 1fa468a3-a39c-41c4-bab7-ff9491dbfb12
  *  )
  * </code>
  *
  * @access public
  * @param  string  $folderName  Folder
  * @param  string  $parent      subfolder UUID, empty value = mainfolder
  * @return array status
  */
  public function addFolder($folderName = "Folder", $parent = "") {
    return $this->request('/bot/folders', 'POST', json_encode(array("name" => $folderName, "parent" => $parent)));
  }
  
  
/**
  * moveFolder
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $folderUUID  866828b7-5e40-4a41-a3a7-9b4584dc3992
  * @param  string  $parent      subfolder UUID, empty value = mainfolder
  * @return array status
  */
  public function moveFolder($folderUUID, $parent = "") {
    return $this->moveTrack($folderUUID, $parent);
  }
  
  
/**
  * renameFolder
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $folderName  Folder
  * @param  string  $folderUUID  866828b7-5e40-4a41-a3a7-9b4584dc3992
  * @return array status
  */
  public function renameFolder($folderName, $folderUUID) {
    return $this->request('/bot/files/'.$folderUUID, 'PATCH', json_encode(array("uuid" => $folderUUID, "type" => "folder", "title" => $folderName)));
  }
  
  
/**
  * deleteFolder
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $folderUUID  866828b7-5e40-4a41-a3a7-9b4584dc3992
  * @return array status
  */
  public function deleteFolder($folderUUID) {
    return $this->deleteTrack($folderUUID);
  }
  
  
/**
  * getJobs
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [0] => Array
  *          (
  *              [uuid] => 1fa468a3-a39c-41c4-bab7-ff9491dbfb12
  *              [url] => https://www.youtube.com/watch?v=hdMt_yUDpH8
  *              [size] => 3.34MiB
  *              [perc] => 100.0
  *              [status] => success
  *              [trackuuid] => f0cdd335-22e1-4542-b2eb-ba3933fdd7ce
  *              [msg] => 
  *              [eta] => 00:00
  *              [bw] => 61.46MiB
  *              [play] => 
  *              [done] => 1
  *              [temp] => 
  *          )
  *      [...]
  *  )
  * </code>
  *
  * @access public
  * @return array
  */
  public function getJobs() {
    return $this->request('/bot/jobs');
  }
  
  
/**
  * addJob
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *      [uuid] => 1fa468a3-a39c-41c4-bab7-ff9491dbfb12
  *  )
  * </code>
  *
  * @access public
  * @param  string  $URL  {YouTube-URL,SoundCloud-URL,Directfile}
  * @return array status
  */
  public function addJob($URL) {
    return $this->request('/bot/jobs', 'POST', json_encode(array('url'=>$URL)));
  }
  
  
/**
  * deleteJob
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $jobUUID  1fa468a3-a39c-41c4-bab7-ff9491dbfb12
  * @return array status
  */
  public function deleteJob($jobUUID) {
    return $this->request('/bot/jobs/'.$jobUUID, 'DELETE');
  }
  
  
/**
  * deleteFinishedJobs
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @return array status
  */
  public function deleteFinishedJobs() {
    return $this->request('/bot/jobs', 'DELETE');
  }
  
  
/**
  * uploadTrack
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [uuid] => fa4cedf8-e71a-40a3-855e-fd628d68a159
  *      [parent] => 
  *      [type] => 
  *      [path] => 963b1543a5e0fed2680f98c509226e915b84ef082ea5c9336b1f0ee46a5dd5ab
  *      [codec] => -
  *      [duration] => 223268
  *      [bitrate] => 251854
  *      [channels] => 2
  *      [samplerate] => 44100
  *      [filesize] => 7075562
  *      [title] => Wrecking Ball
  *      [artist] => Miley Cyrus
  *      [album] => 
  *      [albumArtist] => 
  *      [thumbnail] => a00bbf5170fb96a8ebb6bc5cae388c0e455d589a56b7e3eb68d58ce7081d960e.jpg
  *  )
  * </code>
  *
  * @access public
  * @param  string  $path  /var/www/song.mp3
  * @return array status
  */
  public function uploadTrack($path) {
    return $this->request('/bot/upload', 'POST', file_get_contents($path));
  }
  
  
/**
  * uploadAvatar
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $path          /var/www/image.jpg
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function uploadAvatar($path, $instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;    
    return $this->request('/bot/i/'.$instanceUUID.'/avatar', 'POST', file_get_contents($path));
  }
  
  
/**
  * deleteAvatar
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function deleteAvatar($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/avatar', 'DELETE');
  }
  
  
/**
  * getUsers
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [0] => Array
  *          (
  *              [id] => 63e42054-8316-4f5f-8099-ca964b891587
  *              [tsuid] => FeVFoq415cz7nJQfAm06WXbljVo=
  *              [tsgid] => 
  *              [locked] => 0
  *              [username] => admin
  *              [passwordTimestamp] => 2015-03-11T18:03:06.438002454+01:00
  *              [created] => 2015-03-02T21:35:46.832374338+01:00
  *              [createdBy] => 
  *              [lastchange] => 2015-03-22T14:51:05.234165657+01:00
  *              [lastchangeBy] => 63e42054-8316-4f5f-8099-ca964b891587
  *              [privileges] => 2147483647
  *              [isAdmin] => 1
  *              [lastLogin] => 2015-07-25T21:47:04.067136065+02:00
  *          )
  *      [...]
  *  )
  * </code>
  *
  * @access public
  * @return array users
  */
  public function getUsers() {
    return $this->request('/bot/users');
  }
  
  
/**
  * addUser
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *      [uuid] => 65f7473e-f820-4114-b3df-1a48adc74aeb
  *  )
  * </code>
  *
  * @access public
  * @param  string   $username    Username
  * @param  string   $password    Password
  * @param  integer  $privileges  Bitmask-Value
  * @return array status
  */
  public function addUser($username, $password, $privileges = 0) {
    return $this->request('/bot/users', 'POST', json_encode(array('username'=>$username, 'password'=>$password, 'privileges'=>$privileges)));
  }
  
  
/**
  * setUserPassword
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [id] => d9da9c9a-8198-4afd-9e49-8934e87fa243
  *      [tsuid] => Zzbfw9S5ttDeAThBhop6TlwCaRo=
  *      [tsgid] => 6
  *      [locked] => 0
  *      [username] => admin
  *      [passwordTimestamp] => 2015-07-26T03:26:31.475953355+02:00
  *      [created] => 
  *      [createdBy] => 
  *      [lastchange] => 
  *      [lastchangeBy] => 
  *      [privileges] => 2147483647
  *      [isAdmin] => 1
  *      [lastLogin] => 
  *  )
  * </code>
  *
  * @access public
  * @param  string   $password  Password
  * @param  string   $userUUID  65f7473e-f820-4114-b3df-1a48adc74aeb
  * @return array status
  */
  public function setUserPassword($password, $userUUID) {
    return $this->request('/bot/users/'.$userUUID, 'PATCH', json_encode(array('password'=>$password)));
  }
  
  
/**
  * setUserPrivileges
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [id] => d9da9c9a-8198-4afd-9e49-8934e87fa243
  *      [tsuid] => Zzbfw9S5ttDeAThBhop6TlwCaRo=
  *      [tsgid] => 6
  *      [locked] => 0
  *      [username] => admin
  *      [passwordTimestamp] => 2015-07-26T03:26:31.475953355+02:00
  *      [created] => 
  *      [createdBy] => 
  *      [lastchange] => 
  *      [lastchangeBy] => 
  *      [privileges] => 2147483647
  *      [isAdmin] => 1
  *      [lastLogin] => 
  *  )
  * </code>
  *
  * @access public
  * @param  integer  $privileges  Bitmask-Value
  * @param  string   $userUUID    65f7473e-f820-4114-b3df-1a48adc74aeb
  * @return array status
  */
  public function setUserPrivileges($privileges, $userUUID) {
    return $this->request('/bot/users/'.$userUUID, 'PATCH', json_encode(array('privileges'=>$privileges)));
  }
  
  
/**
  * setUserIdentity
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [id] => d9da9c9a-8198-4afd-9e49-8934e87fa243
  *      [tsuid] => Zzbfw9S5ttDeAThBhop6TlwCaRo=
  *      [tsgid] => 6
  *      [locked] => 0
  *      [username] => admin
  *      [passwordTimestamp] => 2015-07-26T03:26:31.475953355+02:00
  *      [created] => 
  *      [createdBy] => 
  *      [lastchange] => 
  *      [lastchangeBy] => 
  *      [privileges] => 2147483647
  *      [isAdmin] => 1
  *      [lastLogin] => 
  *  )
  * </code>
  *
  * @access public
  * @param  string   $identity    Zzbfw9S5ttDeAThBhop6TlwCaRo=
  * @param  string   $userUUID    65f7473e-f820-4114-b3df-1a48adc74aeb
  * @return array status
  */
  public function setUserIdentity($identity, $userUUID) {
    return $this->request('/bot/users/'.$userUUID, 'PATCH', json_encode(array('tsuid'=>$identity)));
  }
  
  
/**
  * setUserServergroup
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [id] => d9da9c9a-8198-4afd-9e49-8934e87fa243
  *      [tsuid] => Zzbfw9S5ttDeAThBhop6TlwCaRo=
  *      [tsgid] => 6
  *      [locked] => 0
  *      [username] => admin
  *      [passwordTimestamp] => 2015-07-26T03:26:31.475953355+02:00
  *      [created] => 
  *      [createdBy] => 
  *      [lastchange] => 
  *      [lastchangeBy] => 
  *      [privileges] => 2147483647
  *      [isAdmin] => 1
  *      [lastLogin] => 
  *  )
  * </code>
  *
  * @access public
  * @param  string   $groupID   6
  * @param  string   $userUUID  65f7473e-f820-4114-b3df-1a48adc74aeb
  * @return array status
  */
  public function setUserServergroup($groupID, $userUUID) {
    return $this->request('/bot/users/'.$userUUID, 'PATCH', json_encode(array('tsgid'=>$groupID)));
  }
  
  
/**
  * deleteUser
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $userUUID  65f7473e-f820-4114-b3df-1a48adc74aeb
  * @return array status
  */
  public function deleteUser($userUUID) {
    return $this->request('/bot/users/'.$userUUID, 'DELETE');
  }
  
  
/**
  * getSettings
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [name] => 
  *      [nick] => TS3index.com MusicBot 1
  *      [mode] => 0
  *      [serverHost] => ts3index.com
  *      [serverPort] => 9987
  *      [serverPassword] => 
  *      [channelName] => 4832
  *      [channelPassword] => 
  *      [updateDescription] => 
  *      [announce] => 1
  *      [announceString] => 
  *      [identity] => 40VFvxDQfcVPEUPy+keaN2ejZ6iNmFdLVlnVmAjRSZZcAUqTkZ4AF[...]
  *      [identityLevel] => 8
  *      [enableDucking] => 1
  *      [duckingVolume] => 50
  *      [channelCommander] => 1
  *      [stickToChannel] => 
  *      [ttsExternalURL] => http://translate.google.com/translate_tts?tl=__LOCALE&q=__TEXT
  *      [ttsDefaultLocale] => de
  *      [ignoreChatServer] => 
  *      [ignoreChatPrivate] => 
  *      [ignoreChatChannel] => 
  *      [idleTrack] => 
  *      [startupTrack] => 
  *      [script] => 
  *  )
  * </code>
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array users
  */
  public function getSettings($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/settings');
  }
  
  
/**
  * editSettings
  *
  * <b>Input:</b><br>
  * <code>
  * $data = array();
  * 
  * $data['nick'] = 'New Nickname';
  * $data['serverHost'] = '127.0.0.1';
  * $data['serverPort'] = 9987;
  * </code>
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  array   $data          Properties-Array
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function editSettings($data, $instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/settings', 'POST', json_encode($data));
  }
  
  
/**
  * getChannels
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [0] => Array
  *          (
  *              [id] => 4834
  *              [name] => Lobby
  *              [topic] => 
  *              [parent] => 0
  *              [codec] => 4
  *              [quality] => 10
  *              [maxClients] => -1
  *              [order] => 0
  *              [perm] => 1
  *              [sperm] => 0
  *              [default] => 0
  *              [pw] => 0
  *              [enc] => 1
  *              [clients] => Array
  *                  (
  *                  )
  *          )
  *      [1] => Array
  *          (
  *              [id] => 4833
  *              [name] => Support
  *              [topic] => 
  *              [parent] => 0
  *              [codec] => 4
  *              [quality] => 6
  *              [maxClients] => -1
  *              [order] => 5105
  *              [perm] => 1
  *              [sperm] => 0
  *              [default] => 0
  *              [pw] => 0
  *              [enc] => 1
  *              [clients] => Array
  *                  (
  *                      [0] => Array
  *                          (
  *                              [id] => 39
  *                              [uid] => Zzbfw9S5ttDeAThBhop6TlwCaRo=
  *                              [g] => Array
  *                                  (
  *                                      [0] => Array
  *                                          (
  *                                              [i] => 240
  *                                              [n] => Server Admin
  *                                          )
  *                                      [1] => Array
  *                                          (
  *                                              [i] => 341
  *                                              [n] => Support
  *                                          )
  *                                      [...]
  *                                  )
  *                              [nick] => TS3index.com | Manuel
  *                              [idle] => 24270370
  *                              [recording] => 0
  *                              [outputMuted] => 0
  *                              [outputOnlyMuted] => 0
  *                              [inputMuted] => 0
  *                              [away] => 0
  *                              [ko] => 0
  *                          )
  *                      [...]
  *                  )
  *          )
  *      [...]
  *  )
  * </code>
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array channels
  */
  public function getChannels($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/channels');
  }
  
  
/**
  * getInstances
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [0] => Array
  *          (
  *              [uuid] => d3d41c58-9e81-4fbb-974a-a4446013c735
  *              [name] => MusicBot 1
  *              [nick] => TS3index.com MusicBot 1
  *              [running] => 
  *              [mainInstance] => 1
  *          )
  *      [1] => Array
  *          (
  *              [uuid] => f12ea1f3-0c78-4089-8a1b-529192f4b8d4
  *              [name] => MusicBot 2
  *              [nick] => TS3index.com MusicBot 2
  *              [running] => 
  *              [mainInstance] => 
  *          )
  *      [...]
  *  )
  * </code>
  *
  * @access public
  * @return array instances
  */
  public function getInstances() {
    return $this->request('/bot/instances');
  }
  
  
/**
  * selectInstance
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return boolean
  */
  public function selectInstance($instanceUUID) {
    if ($this->getStatus($instanceUUID) == NULL) {
      return false;
    } else {
      $this->instanceUUID = $instanceUUID;
      return true;
    }
  }
  
  
/**
  * createInstance
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *      [uuid] => 1fa468a3-a39c-41c4-bab7-ff9491dbfb12
  *  )
  * </code>
  *
  * @access public
  * @param  string  $nickname  Nickname
  * @return array status
  */
  public function createInstance($nickname = "TS3index.com MusicBot", $backend = "ts3") {
    return $this->request('/bot/instances', 'POST', json_encode(array("backend" => $backend, "nick" => $nickname)));
  }
  
  
/**
  * deleteInstance
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function deleteInstance($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/instances/'.$instanceUUID, 'DELETE');
  }
  
  
/**
  * spawnInstance
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function spawnInstance($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/spawn', 'POST', '');
  }
  
  
/**
  * respawnInstance
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function respawnInstance($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/respawn', 'POST', '');
  }
  
  
/**
  * killInstance
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [success] => 1
  *  )
  * </code>
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
  public function killInstance($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return $this->request('/bot/i/'.$instanceUUID.'/kill', 'POST', '');
  }
  
  
/**
  * getWebStream
  *
  * requires: EnableWebStream = true
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return string  url (opus-encoded-ogg-stream)
  */
  public function getWebStream($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    $token = $this->getWebStreamToken($instanceUUID);
    if ($token == NULL) return NULL;
    
    return $this->apiURL.'/b/bot/i/'.$instanceUUID.'/stream/'.$token;
  }
  
  
/**
  * getWebStreamToken
  *
  * requires: EnableWebStream = true
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return string  token
  */
  public function getWebStreamToken($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    $tokenRequest = $this->request('/bot/i/'.$instanceUUID.'/streamToken', 'POST', '');
    return (isset($tokenRequest['token'])) ? $tokenRequest['token'] : NULL;
  }
  
  
/**
  * getDefaultBot
  *
  * <b>Output:</b>
  * b115f224-0687-492f-a88c-ccd9a6582f441
  *
  * @access public
  * @return string
  */
  public function getDefaultBot() {
    $botRequest = $this->request('/botId');
    return (isset($botRequest['defaultBotId'])) ? $botRequest['defaultBotId'] : NULL;
  }
  
  
/**
  * getBotLog
  *
  * <b>Output:</b><br>
  * <code>
  *  Array
  *  (
  *      [0] => Array
  *          (
  *              [m] => LOGIN [admin] OK
  *              [t] => 1437853448
  *              [s] => 3
  *          )
  *      [1] => Array
  *          (
  *              [m] => PLAYLIST-ENTRY-ADD [admin] 4e62232e-a025-446b-a781-9563abef628b 2340b137-6e1a-4774-a8a5-60eda927d1f0 OK
  *              [t] => 1437853448
  *              [s] => 3
  *          )
  *      [2] => Array
  *          (
  *              [m] => FILE-DELETE [admin] (011098e6-cece-417e-a38d-297b27598efa) OK
  *              [t] => 1437860391
  *              [s] => 3
  *          )
  *      [...]
  *  )
  * </code>
  *
  * @access public
  * @return array log
  */
  public function getBotLog() {
    return $this->request('/bot/log');
  }
  
  
/**
  * getThumbnail
  *
  * <b>Output:</b>
  * http://127.0.0.1:8087/cache/2e507f2190ec65d23a41e0894bf6a2eb0e050283d6cdbdb089f1b7efcef2449e.jpg
  *
  * @access public
  * @param  string  $thumbnail  see getFiles()
  * @return string  url
  */
  public function getThumbnail($thumbnail) {
    return $this->wiURL.'/cache/'.$thumbnail;
  }
  
  
/**
  * isPlaying
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return boolean
  */
  public function isPlaying($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return (boolean) $this->getStatus($instanceUUID)['playing'];
  }
  
  
/**
  * isRunning
  *
  * @access public
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return boolean
  */
  public function isRunning($instanceUUID = NULL) {
    if ($instanceUUID == NULL) $instanceUUID = $this->instanceUUID;
    return (boolean) $this->getStatus($instanceUUID)['running'];
  }
  
  
//----------------------------------------------------------------------
// Internal Functions
//----------------------------------------------------------------------
  
  
/**
  * __construct
  *
  * @access private
  * @param  string  $wiURL    http://127.0.0.1:8087
  * @param  string  $botUUID  4852efdc-9705-4706-e469-cfvf77favf33
  * @return void
  */
  function __construct($wiURL = 'http://127.0.0.1:8087', $botUUID = NULL, $wiTimeout = 8000) {
    $this->wiURL = $wiURL;
    $this->apiURL = $this->wiURL.'/api/v1';
    $this->wiTimeout = $wiTimeout;
    $this->botUUID = ($botUUID == NULL) ? $this->getDefaultBot() : $botUUID;
  }
  
  
/**
  * __destruct
  * 
  * @access private
  * @return void
  */
  function __destruct() {
  }
  
  
/**
  * __call
  * 
  * @access private
  * @param  string  $name   method name
  * @param  array   $args   method arguments
  * @return void
  */
  function __call($name, $args) {
    return 'Method '.$name.' doesn\'t exist';
  }
  
  
/**
  * request
  *
  * @access private
  * @param  string  $path    /bot/instances
  * @param  string  $method  GET
  * @param  string  $method  NULL
  * @return array
  */
  private function request($path, $method = "GET", $fields = NULL) {
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $this->apiURL.$path,
        CURLOPT_HTTPHEADER => array(
            "Accept:application/json, text/plain, */*",
            "Accept-Encoding:gzip, deflate",
            "Content-Type:application/json",
            "Authorization: Bearer ".$this->wiToken
        ),
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT_MS => $this->wiTimeout
    ));
    if ($fields != NULL) curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); 
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
  * getError
  *
  * @access private
  * @param  integer  $code    401
  * @return string
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
