<?php
require_once("OAuth.php");

class nsx_LinkedIn {
  public $base_url = "http://api.linkedin.com";
  public $secure_base_url = "https://api.linkedin.com";
  public $oauth_callback = "oob";
  public $consumer;
  public $request_token;
  public $access_token;
  public $oauth_verifier;
  public $signature_method;
  public $request_token_path;
  public $access_token_path;
  public $authorize_path;
  public $debug = false;
  public $http_code;
  
  function __construct($consumer_key, $consumer_secret, $oauth_callback = NULL) {
    
    if($oauth_callback) {
      $this->oauth_callback = $oauth_callback;
    }
    
    $this->consumer = new nsx_trOAuthConsumer($consumer_key, $consumer_secret, $this->oauth_callback);
    $this->signature_method = new nsx_trOAuthSignatureMethod_HMAC_SHA1();
    $this->request_token_path = $this->secure_base_url . "/uas/oauth/requestToken?scope=r_basicprofile+r_emailaddress+rw_nus+rw_groups";
    $this->access_token_path = $this->secure_base_url . "/uas/oauth/accessToken";
    $this->authorize_path = $this->secure_base_url . "/uas/oauth/authorize";
    
  }

  function getRequestToken() {
    $consumer = $this->consumer;
    $request = nsx_trOAuthRequest::from_consumer_and_token($consumer, NULL, "GET", $this->request_token_path);
    $request->set_parameter("oauth_callback", $this->oauth_callback);
    $request->sign_request($this->signature_method, $consumer, NULL); // prr($request);
    $headers = Array();
    $url = $request->to_url(); // echo "^^^^^";  prr($url); 
    $response = $this->httpRequest($url, $headers, "GET"); //prr($response); 
    if ($response!='') $this->http_code = 200;
    parse_str($response, $response_params); //prr($response_params); echo "!!!!";
    $this->request_token = new nsx_trOAuthConsumer($response_params['oauth_token'], $response_params['oauth_token_secret'], 1); return $this->request_token;
  }

  function generateAuthorizeUrl() {
    $consumer = $this->consumer;
    $request_token = $this->request_token;
    return $this->authorize_path . "?oauth_token=" . $request_token->key;
  }

  function getAccessToken($oauth_verifier) {
    $request = nsx_trOAuthRequest::from_consumer_and_token($this->consumer, $this->request_token, "GET", $this->access_token_path);
    $request->set_parameter("oauth_verifier", $oauth_verifier);
    $request->sign_request($this->signature_method, $this->consumer, $this->request_token);
    $headers = Array();
    $url = $request->to_url(); // echo "==========";
    $response = $this->httpRequest($url, $headers, "GET"); //prr($request);
    parse_str($response, $response_params); // prr($response_params);
    if($debug) {
      echo $response . "\n";
    }
    $this->access_token = new nsx_trOAuthConsumer($response_params['oauth_token'], $response_params['oauth_token_secret'], 1);
  }
  
  function getProfile($resource = "~") {
    $profile_url = $this->base_url . "/v1/people/" . $resource;
    $request = nsx_trOAuthRequest::from_consumer_and_token($this->consumer, $this->access_token, "GET", $profile_url);
    $request->sign_request($this->signature_method, $this->consumer, $this->access_token);
    $auth_header = $request->to_header("https://api.linkedin.com"); # this is the realm
    # This PHP library doesn't generate the header correctly when a realm is not specified.
    # Make sure there is a space and not a comma after OAuth
    // $auth_header = preg_replace("/Authorization\: OAuth\,/", "Authorization: OAuth ", $auth_header);
    // # Make sure there is a space between OAuth attribute
    // $auth_header = preg_replace('/\"\,/', '", ', $auth_header);
    if ($debug) {
      echo $auth_header;
    }
    // $response will now hold the XML document
    $response = $this->httpRequest($profile_url, $auth_header, "GET");
    return $response;
  }
  
  


  function postShare($msg, $title='', $url='', $imgURL='', $dsc='') { $status_url = $this->base_url . "/v1/people/~/shares";  
    $dsc =  nxs_decodeEntitiesFull(strip_tags($dsc));  $msg = strip_tags(nxs_decodeEntitiesFull($msg));  $title =  nxs_decodeEntitiesFull(strip_tags($title));
    $xml = '<?xml version="1.0" encoding="UTF-8"?><share><comment>'.htmlspecialchars($msg, ENT_NOQUOTES, "UTF-8").'</comment>'.
    ($url!=''?'<content><title>'.htmlspecialchars($title, ENT_NOQUOTES, "UTF-8").'</title><submitted-url>'.$url.'</submitted-url><submitted-image-url>'.$imgURL.'</submitted-image-url><description>'.htmlspecialchars($dsc, ENT_NOQUOTES, "UTF-8").'</description></content>':'').
    '<visibility><code>anyone</code></visibility></share>';
    $request = nsx_trOAuthRequest::from_consumer_and_token($this->consumer, $this->access_token, "POST", $status_url);
    $request->sign_request($this->signature_method, $this->consumer, $this->access_token);
    $auth_header = $request->to_header("https://api.linkedin.com");
    if ($debug) echo $auth_header . "\n"; 
    $response = $this->httpRequest($status_url, $auth_header, "POST", $xml); 
    return $response;
  }
  
  function postToGroup($msg, $title, $groupID, $url='', $imgURL='', $dsc='') { $status_url = $this->base_url . "/v1/groups/".$groupID."/posts";  //$debug = true;
    $dsc =  nxs_decodeEntitiesFull(strip_tags($dsc));  $msg = strip_tags(nxs_decodeEntitiesFull($msg));  $title =  nxs_decodeEntitiesFull(strip_tags($title));
    $xml = '<?xml version="1.0" encoding="UTF-8"?><post><title>'.htmlspecialchars($title, ENT_NOQUOTES, "UTF-8").'</title><summary>'.htmlspecialchars($msg, ENT_NOQUOTES, "UTF-8").'</summary>
    '.($url!=''?'<content><title>'.htmlspecialchars($title, ENT_NOQUOTES, "UTF-8").'</title><submitted-url>'.$url.'</submitted-url><submitted-image-url>'.$imgURL.'</submitted-image-url><description>'.htmlspecialchars($dsc, ENT_NOQUOTES, "UTF-8").'</description></content>':'').'</post>';
    
    $request = nsx_trOAuthRequest::from_consumer_and_token($this->consumer, $this->access_token, "POST", $status_url);
    $request->sign_request($this->signature_method, $this->consumer, $this->access_token);
    $auth_header = $request->to_header("https://api.linkedin.com");
    if ($debug) echo $auth_header . "\n"; 
    $response = $this->httpRequest($status_url, $auth_header, "POST", $xml);//prr($response);
    return $response;
  }
  
  function setStatus($status) {
    $status_url = $this->base_url . "/v1/people/~/current-status";
    //echo "Setting status...\n";
    $xml = "<current-status>" . htmlspecialchars($status, ENT_NOQUOTES, "UTF-8") . "</current-status>";
    //echo $xml . "\n";
    $request = nsx_trOAuthRequest::from_consumer_and_token($this->consumer, $this->access_token, "PUT", $status_url);
    $request->sign_request($this->signature_method, $this->consumer, $this->access_token);
    $auth_header = $request->to_header("https://api.linkedin.com");
    if ($debug) {
      echo $auth_header . "\n";
    }
    $response = $this->httpRequest($status_url, $auth_header, "PUT", $xml); // prr($response);
    return $response;
  }
  
  # Parameters should be a query string starting with "?"
  # Example search("?count=10&start=10&company=LinkedIn");
  function search($parameters) {
    $search_url = $this->base_url . "/v1/people/" . $parameters;
    echo "Performing search for: " . $parameters . "\n";
    $request = nsx_trOAuthRequest::from_consumer_and_token($this->consumer, $this->access_token, "GET", $search_url);
    $request->sign_request($this->signature_method, $this->consumer, $this->access_token);
    $auth_header = $request->to_header("https://api.linkedin.com");
    if ($debug) {
      echo $request->get_signature_base_string() . "\n";
      echo $auth_header . "\n";
    }
    $response = $this->httpRequest($search_url, $auth_header, "GET");
    return $response;
  }

  function getCurrentShare($parameters='') { 
    $search_url = $this->base_url . "/v1/people/~/current-share";    
    $request = nsx_trOAuthRequest::from_consumer_and_token($this->consumer, $this->access_token, "GET", $search_url);
    $request->sign_request($this->signature_method, $this->consumer, $this->access_token);
    $auth_header = $request->to_header("https://api.linkedin.com");
    if ($debug) {
      echo $request->get_signature_base_string() . "\n";
      echo $auth_header . "\n";
    }
    $response = $this->httpRequest($search_url, $auth_header, "GET");
    return $response;
  }
  
  function httpRequest($url, $auth_header, $method, $body = NULL) { // $this->debug = true; //if (!is_array($auth_header)) $auth_header = array($auth_header);
    if (!is_array($auth_header)) $auth_header = array($auth_header); 
    if (!$method) $method = "GET"; $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $auth_header); // Set the headers.

    if ($body) { $auth_header[] = "Content-Type: text/xml;charset=utf-8";
      curl_setopt($curl, CURLOPT_POST, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $auth_header);   
    }
 
    $data = curl_exec($curl); $errmsg = curl_error($curl); //prr($data);// die();
    
    //## NextScripts Fix
    if (curl_errno($curl) == 60 || stripos($errmsg, 'SSL')!==false) {  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); $data = curl_exec($curl);}
    if (curl_errno($curl) > 0) { $err = curl_errno($curl); $errmsg = curl_error($curl); prr($errmsg); prr($err);}    
    //## /NextScripts Fix    
    $header = curl_getinfo($curl); curl_close($curl);// prr($header);

    if ($this->debug) echo $data . "\n";    
        if (trim($data)=='' && ($header['http_code']=='201' || $header['http_code']=='200' || $header['http_code']=='202')) $data = '201';
    return $data; 
  }

}