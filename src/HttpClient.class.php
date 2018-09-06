<?php

class HttpClient
{
    protected $timeout = null;
    protected $token = null;
    protected $url = null;
  /**
  * request executes a request to the SinusBot API
  *
  * @param string $path    /api/v1/<path>
  * @param string $method  http method
  * @param string $payload http POST payload
  * @param boolean $encoded when not encoded it will be JSON marshalled
  * @return array decoded JSON response
  */
    protected function request($path, $method = "GET", $payload = null, $encoded = false)
    {
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
        if ($payload != null) {
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
            if ($httpcode != 200 and $httpcode != 201) {
                $data = array('success' => false, 'error' => $this->getError($httpcode));
            }
        }
    
        curl_close($ch);
        return (is_array($data)) ? $data : json_decode($data, true);
    }
    
  
  /**
  * getError returns the string representive to the given http status code
  *
  * @param integer $code http status code
  * @return string http status code string representive
  */
    private function getError($code = 0)
    {
        switch ($code) {
            case 100:
                return 'Continue';
            case 101:
                return 'Switching Protocols';
            case 200:
                return 'OK';
            case 201:
                return 'Created';
            case 202:
                return 'Accepted';
            case 203:
                return 'Non-Authoritative Information';
            case 204:
                return 'No Content';
            case 205:
                return 'Reset Content';
            case 206:
                return 'Partial Content';
            case 300:
                return 'Multiple Choices';
            case 301:
                return 'Moved Permanently';
            case 302:
                return 'Moved Temporarily';
            case 303:
                return 'See Other';
            case 304:
                return 'Not Modified';
            case 305:
                return 'Use Proxy';
            case 400:
                return 'Bad Request';
            case 401:
                return 'Unauthorized';
            case 402:
                return 'Payment Required';
            case 403:
                return 'Forbidden';
            case 404:
                return 'Not Found';
            case 405:
                return 'Method Not Allowed';
            case 406:
                return 'Not Acceptable';
            case 407:
                return 'Proxy Authentication Required';
            case 408:
                return 'Request Time-out';
            case 409:
                return 'Conflict';
            case 410:
                return 'Gone';
            case 411:
                return 'Length Required';
            case 412:
                return 'Precondition Failed';
            case 413:
                return 'Request Entity Too Large';
            case 414:
                return 'Request-URI Too Large';
            case 415:
                return 'Unsupported Media Type';
            case 500:
                return 'Internal Server Error';
            case 501:
                return 'Not Implemented';
            case 502:
                return 'Bad Gateway';
            case 503:
                return 'Service Unavailable';
            case 504:
                return 'Gateway Time-out';
            case 505:
                return 'HTTP Version not supported';
            default:
                return 'Unknown HTTP status code: ' . $code;
        }
    }
}

