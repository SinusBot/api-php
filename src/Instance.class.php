<?php
/**
 * Class Instance | src/Instance.class.php
 *
 * A single Instance with his available actions
 *
 * @package      SinusBot
 * @author       Max Schmitt <max@schmitt.mx>
 */

namespace SinusBot;

class Instance extends RestClient
{
    public $uuid = null;
    public $instance = null;
    public function __construct($token, $url, $timeout, $instance)
    {
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
    public function isPlaying()
    {
        return $this->getStatus()['playing'];
    }
  
  /**
  * isRunning
  *
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return boolean
  */
    public function isRunning()
    {
        return $this->getStatus()['running'];
    }

  /**
  * deleteInstance
  *
  * @return array status
  */
    public function delete()
    {
        return $this->request('/bot/instances/'.$this->uuid, 'DELETE');
    }
  
  
  /**
  * spawnInstance
  *
  * @return array status
  */
    public function spawn()
    {
        return $this->request('/bot/i/'.$this->uuid.'/spawn', 'POST', '');
    }
  
  /**
  * respawnInstance
  *
  * @return array status
  */
    public function respawn()
    {
        return $this->request('/bot/i/'.$this->uuid.'/respawn', 'POST', '');
    }
  
  /**
  * killInstance
  *
  * @param  string  $instanceUUID  6421eedc-9705-4706-a269-cf6f38fa1a33
  * @return array status
  */
    public function kill()
    {
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
    public function getWebStream()
    {
        $token = $this->getWebStreamToken();
        if ($token == null) {
            return null;
        }
    
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
    public function getWebStreamToken()
    {
        $tokenRequest = $this->request('/bot/i/'.$this->uuid.'/streamToken', 'POST', '');
        return (isset($tokenRequest['token'])) ? $tokenRequest['token'] : null;
    }
    
  /**
  * getVolume
  *
  * @return integer
  * @api
  */
    public function getVolume()
    {
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
    public function setVolume($volume = 50)
    {
        return $this->request('/bot/i/'.$this->uuid.'/volume/set/'.$volume, 'POST', '');
    }
  
  
  /**
  * setVolumeUp
  *
  * @return array status
  * @api
  */
    public function setVolumeUp()
    {
        return $this->request('/bot/i/'.$this->uuid.'/volume/up', 'POST', '');
    }
  
  /**
  * setVolumeDown
  *
  * @return array status
  * @api
  */
    public function setVolumeDown()
    {
        return $this->request('/bot/i/'.$this->uuid.'/volume/down', 'POST', '');
    }

  /**
  * getStatus
  *
  * @return array status
  * @api
  */
    public function getStatus()
    {
        return $this->request('/bot/i/'.$this->uuid.'/status');
        ;
    }

  /**
  * getInstanceLog
  *
  * @return array log
  * @api
  */
    public function getInstanceLog()
    {
        return $this->request('/bot/i/'.$this->uuid.'/log');
    }

  /**
  * getSettings
  *
  * @return array users
  * @api
  */
    public function getSettings()
    {
        return $this->request('/bot/i/'.$this->uuid.'/settings');
    }
  
  /**
  * setSettings
  *
  * @param array $data array of properties
  * @return array status
  * @api
  */
    public function setSettings($data)
    {
        return $this->request('/bot/i/'.$this->uuid.'/settings', 'POST', $data);
    }
  
  
  /**
  * getChannels
  *
  * @return array channels
  * @api
  */
    public function getChannels()
    {
        return $this->request('/bot/i/'.$this->uuid.'/channels');
    }
  
  /**
  * uploadAvatar
  *
  * @param string $path /var/www/image.jpg
  * @return array status
  */
    public function uploadAvatar($path)
    {
        return $this->request('/bot/i/'.$this->uuid.'/avatar', 'POST', file_get_contents($path));
    }
  
  /**
  * deleteAvatar
  *
  * @return array status
  */
    public function deleteAvatar()
    {
        return $this->request('/bot/i/'.$this->uuid.'/avatar', 'DELETE');
    }

  /**
  * getQueueTracks
  *
  * @return array files
  */
    public function getQueueTracks()
    {
        return $this->request('/bot/i/'.$this->uuid.'/queue');
    }

  /**
  * appendQueueTrack
  *
  * @param string $trackUUID uuid of the track
  * @return array status
  */
    public function appendQueueTrack($trackUUID)
    {
        return $this->request('/bot/i/'.$this->uuid.'/queue/append/'.$trackUUID, 'POST', "");
    }
  
  /**
  * prependQueueTrack
  *
  * @param string $trackUUID track uuid
  * @return array status
  */
    public function prependQueueTrack($trackUUID)
    {
        return $this->request('/bot/i/'.$this->uuid.'/queue/prepend/'.$trackUUID, 'POST', "");
    }
  
  
  /**
  * deleteQueueTrack
  *
  * @param integer $trackPosition  first entry = 0
  * @return array status
  */
    public function deleteQueueTrack($trackPosition, $instanceUUID)
    {
        $currentTracks = $this->getQueueTracks();
        if ($currentTracks == null or !is_array($currentTracks)) {
            return null;
        }
        unset($currentTracks[$trackPosition]);
    
        return $this->request('/bot/i/'.$this->uuid.'/queue', 'PATCH', array_values($currentTracks));
    }
  
  
  /**
  * deleteQueueTracks
  *
  * @return array status
  */
    public function deleteQueueTracks()
    {
        return $this->request('/bot/i/'.$this->uuid.'/queue', 'PATCH', []);
    }
  
  
  /**
  * say
  *
  * @param  string  $text          Welcome
  * @param  string  $locale        en
  * @return array status
  */
    public function say($text, $locale)
    {
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
    public function playTrack($trackUUID)
    {
        return $this->request('/bot/i/'.$this->uuid.'/play/byId/'.$trackUUID, 'POST', '');
    }
  
  
  /**
  * playURL
  *
  * @param string $url stream url
  * @return array status
  */
    public function playURL($url)
    {
        return $this->request('/bot/i/'.$this->uuid.'/playUrl?url='.urlencode($url), 'POST', '');
    }
  
  
/**
  * playPlaylist
  *
  * @param string $playlistUUID uuid of a playlist
  * @param string $playlistIndex 0
  * @return array status
  */
    public function playPlaylist($playlistUUID, $playlistIndex = 0)
    {
        return $this->request('/bot/i/'.$this->uuid.'/play/byList/'.$playlistUUID.'/'.$playlistIndex, 'POST', '');
    }
  
  /**
  * playPrevious
  *
  * @return array status
  */
    public function playPrevious()
    {
        return $this->request('/bot/i/'.$this->uuid.'/playPrevious', 'POST', '');
    }
  
  
/**
  * playNext
  *
  * @return array status
  */
    public function playNext()
    {
        return $this->request('/bot/i/'.$this->uuid.'/playNext', 'POST', '');
    }
  
/**
  * playRepeat
  *
  * @param  integer $repeatState   {0=disable,1=enable}
  * @return array status
  */
    public function playRepeat($repeatState = 1)
    {
        return $this->request('/bot/i/'.$this->uuid.'/repeat/'.$repeatState, 'POST', '');
    }
  
/**
  * playShuffle
  *
  * @param  integer $shuffleState  {0=disable,1=enable}
  * @return array status
  */
    public function playShuffle($shuffleState = 1)
    {
        return $this->request('/bot/i/'.$this->uuid.'/shuffle/'.$shuffleState, 'POST', '');
    }
  
  
/**
  * stop
  *
  * @return array status
  */
    public function stop()
    {
        return $this->request('/bot/i/'.$this->uuid.'/stop', 'POST', '');
    }
  
  
/**
  * seekPlayback
  *
  * @param  integer  $position      0
  * @return array status
  */
    public function seekPlayback($position = 0)
    {
        return $this->request('/bot/i/'.$this->uuid.'/seek/'.$position, 'POST', '');
    }
  
  
/**
  * getPlayedTracks
  *
  * @return array   array of uuids
  */
    public function getPlayedTracks()
    {
        return $this->request('/bot/i/'.$this->uuid.'/recent', 'POST', '');
    }
}
