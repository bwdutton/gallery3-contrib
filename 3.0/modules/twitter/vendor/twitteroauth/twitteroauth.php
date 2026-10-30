<?php

/*
 * Abraham Williams (abraham@abrah.am) http://abrah.am
 *
 * The first PHP Library to support OAuth for Twitter's REST API.
 */

/* Load OAuth lib. You can find it at http://oauth.net */
require_once('OAuth.php');

/**
 * Twitter OAuth class
 */
class TwitterOAuth {
  /* Contains the last HTTP status code returned. */
  public $http_code;
  /* Contains the last API call. */
  public $url;
  /* Set up the API root URL. */
  public $host = "https://api.twitter.com/1.1/";
  /* Set timeout default. */
  public $timeout = 30;
  /* Set connect timeout. */
  public $connecttimeout = 30; 
  /* Verify SSL Cert. */
  public $ssl_verifypeer = FALSE;
  /* Respons format. */
  public $format = 'json';
  /* Decode returned json data. */
  public $decode_json = TRUE;
  /* Contains the last HTTP headers returned. */
  public $http_info;
  /* Set the useragnet. */
  public $useragent = 'TwitterOAuth v0.2.0-beta2';
  /* Immediately retry the API call if the response was not successful. */
  //public $retry = TRUE;

  /* caching options */
  /* caching responses for an hour only with GET requests */
  public $cache = 0;
  /* where cache files will be stored if above is set to true */
  public $cacheLocation = '/tmp'; 
  
  /**
   * Set API URLS
   */
  public function accessTokenURL()  { return 'https://api.twitter.com/oauth/access_token'; }
  public function authenticateURL() { return 'https://api.twitter.com/oauth/authenticate'; }
  public function authorizeURL()    { return 'https://api.twitter.com/oauth/authorize'; }
  public function requestTokenURL() { return 'https://api.twitter.com/oauth/request_token'; }

  /**
   * Debug helpers
   */
  public function lastStatusCode() { return $this->http_status; }
  public function lastAPICall() { return $this->last_api_call; }

  /**
   * construct TwitterOAuth object
   */
  public function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) {
    $this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1();
    $this->consumer = new OAuthConsumer($consumer_key, $consumer_secret);
    if (!empty($oauth_token) && !empty($oauth_token_secret)) {
      $this->token = new OAuthConsumer($oauth_token, $oauth_token_secret);
    } else {
      $this->token = NULL;
    }
  }


  /**
   * Get a request_token from Twitter
   *
   * @returns a key/value array containing oauth_token and oauth_token_secret
   */
  public function getRequestToken($oauth_callback) {
    $parameters = [];
    $parameters['oauth_callback'] = $oauth_callback; 
    $request = $this->oAuthRequest($this->requestTokenURL(), 'GET', $parameters);
    $token = OAuthUtil::parse_parameters($request);
    $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
    return $token;
  }

  /**
   * Get the authorize URL
   *
   * @returns a string
   */
  public function getAuthorizeURL($token, $sign_in_with_twitter = TRUE) {
    if (is_array($token)) {
      $token = $token['oauth_token'];
    }
    if (empty($sign_in_with_twitter)) {
      return $this->authorizeURL() . "?oauth_token={$token}";
    } else {
       return $this->authenticateURL() . "?oauth_token={$token}";
    }
  }

  /**
   * Exchange request token and secret for an access token and
   * secret, to sign API calls.
   *
   * @returns array("oauth_token" => "the-access-token",
   *                "oauth_token_secret" => "the-access-secret",
   *                "user_id" => "9436992",
   *                "screen_name" => "abraham")
   */
  public function getAccessToken($oauth_verifier) {
    $parameters = [];
    $parameters['oauth_verifier'] = $oauth_verifier;
    $request = $this->oAuthRequest($this->accessTokenURL(), 'GET', $parameters);
    $token = OAuthUtil::parse_parameters($request);
    $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
    return $token;
  }

  /**
   * One time exchange of username and password for access token and secret.
   *
   * @returns array("oauth_token" => "the-access-token",
   *                "oauth_token_secret" => "the-access-secret",
   *                "user_id" => "9436992",
   *                "screen_name" => "abraham",
   *                "x_auth_expires" => "0")
   */  
  public function getXAuthToken($username, $password) {
    $parameters = [];
    $parameters['x_auth_username'] = $username;
    $parameters['x_auth_password'] = $password;
    $parameters['x_auth_mode'] = 'client_auth';
    $request = $this->oAuthRequest($this->accessTokenURL(), 'POST', $parameters);
    $token = OAuthUtil::parse_parameters($request);
    $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
    return $token;
  }

  /**
   * GET wrapper for oAuthRequest.
   */
  public function get($url, $parameters = []) {
    $response = $this->oAuthRequest($url, 'GET', $parameters);
    if ($this->format === 'json' && $this->decode_json) {
      return json_decode($response);
    }
    return $response;
  }
  
  /**
   * POST wrapper for oAuthRequest.
   */
  public function post($url, $parameters = []) {
    $response = $this->oAuthRequest($url, 'POST', $parameters);
    if ($this->format === 'json' && $this->decode_json) {
      return json_decode($response);
    }
    return $response;
  }

  /**
   * Uploads are handled slightly differently
   */
  public function upload($url, $parameters = []) {
    $response = $this->oAuthRequest($url, 'POST', $parameters, true);
    if ($this->format === 'json' && $this->decode_json) {
      return json_decode($response);
    }
    return $response;
  }

  /**
   * DELETE wrapper for oAuthReqeust.
   */
  public function delete($url, $parameters = []) {
    $response = $this->oAuthRequest($url, 'DELETE', $parameters);
    if ($this->format === 'json' && $this->decode_json) {
      return json_decode($response);
    }
    return $response;
  }

  /**
   * Format and sign an OAuth / API request
   */
  public function oAuthRequest($url, $method, $parameters, $upload = false) {

    if (strrpos($url, 'https://') !== 0 && strrpos($url, 'http://') !== 0) {
      $url = "{$this->host}{$url}.{$this->format}";
    }

    if ($upload) {
      // we only need to sign the oauth_* parameters for this, see
      // https://dev.twitter.com/discussions/1059?page=4
      $signable_parameters = [];
      foreach ($parameters as $k=>&$v)
        if (str_starts_with($k, "oauth_"))
          $signable_parameters[$k] = $v;
      $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $signable_parameters);
      $request->sign_request($this->sha1_method, $this->consumer, $this->token);
      $request->set_parameters($parameters);
    } else {
      $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters);
      $request->sign_request($this->sha1_method, $this->consumer, $this->token);
    }

    return match ($method) {
        'GET' => $this->http($request->to_url(), 'GET'),
        default => $this->http($request->get_normalized_http_url(), $method, $upload ? $request->get_parameters() : $request->to_postdata(), $upload ? $request->to_header() : false),
    };
  }

  /**
   * Make an HTTP request
   *
   * @return API results
   */
  public function http($url, $method, $postfields = NULL, $authorization_header= false) {
    $this->http_info = [];
    
    $ci = curl_init();

    $headers = ['Expect:'];
    /* Curl settings */
    curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
    curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
    curl_setopt($ci, CURLOPT_HEADERFUNCTION, $this->getHeader(...));
    curl_setopt($ci, CURLOPT_HEADER, FALSE);

    switch ($method) {
      case 'POST':
        curl_setopt($ci, CURLOPT_POST, TRUE);
        if ($authorization_header)
          $headers[] = $authorization_header;
        if (!empty($postfields)) {
          curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
        }
        break;
      case 'DELETE':
        curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
        if (!empty($postfields)) {
          $url = "{$url}?{$postfields}";
        }
    }

    curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ci, CURLOPT_URL, $url);
    $response = curl_exec($ci);

    $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
    $this->url = $url;
    curl_close ($ci);
    
    if ($this->cache) {
        $this->cacheFile($response, $this->cache_file_name);
    }
    
    return $response;
  }

  /**
   * Get the header info to store.
   */
  public function getHeader($ch, $header) {
    $i = strpos($header, ':');
    if (!empty($i)) {
      $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
      $value = trim(substr($header, $i + 2));
      $this->http_header[$key] = $value;
    }
    return strlen($header);
  }
  
  	public function checkCache($fileName, $time) {
		$fileName = $this->cacheLocation.'/'.$fileName;
		
		if (!file_exists($fileName)) {
			$this->cache_debug = $fileName . 'does not exist';
			return 0;
		}

		if (!$fileTime = filectime($fileName)) {
			$this->cache_error = 'Could not check the cache time';
			return 0;
		}

		// check if the cache is too old
		if (time() - $fileTime < $time) {
			$this->cache_debug = 'cache file expired';
			return 1;
		}
		
		$this->cache_debug = 'cache file still valid';
		return 0;
	}
  
	public function cacheFile($data, $filename) {
		$new_file = $this->cacheLocation.'/'.$fileName;
		$fh = fopen($new_file, 'w+');
		if (!$fh) {
			$this->cache_error = "Could not open cache file '$filename'";
			return 0;
		}
		if (!fwrite($fh, $data)) {
			$this->cache_error = "Could not write to cache file '$filename'";
			fclose($fh);
			return 0;
		}
		fclose($fh);
		chmod($new_file, 0766);
	    return 1;
	}
  
    public function cacheRetrieve($fileName) {
        error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
		if (!$fh = fopen($this->cacheLocation.'/'.$fileName, 'r')) {
			$this->cache_error = 'Could not open cache file';
			return 0;
		}
		error_reporting(E_ALL ^ E_NOTICE);

		$xml = "";
		while (!feof($fh)) { 
		    $xml .= fread($fh, 1024); 
        }
		fclose($fh);

		return $xml;
    }
}
