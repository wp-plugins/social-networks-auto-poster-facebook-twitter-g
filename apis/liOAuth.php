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
    $this->request_token_path = $this->secure_base_url . "/uas/oauth/requestToken";
    $this->access_token_path = $this->secure_base_url . "/uas/oauth/accessToken";
    $this->authorize_path = $this->secure_base_url . "/uas/oauth/authorize";
    
  }

  function getRequestToken() {
    $consumer = $this->consumer;
    $request = nsx_trOAuthRequest::from_consumer_and_token($consumer, NULL, "GET", $this->request_token_path);
    $request->set_parameter("oauth_callback", $this->oauth_callback);
    $request->sign_request($this->signature_method, $consumer, NULL); prr($request);
    $headers = Array();
    $url = $request->to_url();
    $response = $this->httpRequest($url, $headers, "GET"); if ($response!='') $this->http_code = 200;
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
    $url = $request->to_url(); echo "==========";
    $response = $this->httpRequest($url, $headers, "GET"); prr($request);
    parse_str($response, $response_params);  prr($response_params);
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
  
  


  function postShare($msg, $title='', $url='', $imgURL='', $dsc='') { $status_url = $this->base_url . "/v1/people/~/shares";  $dsc =  decode_entities_full(strip_tags($dsc));  $msg = strip_tags(decode_entities_full($msg));
    $xml = '<?xml version="1.0" encoding="UTF-8"?><share><comment>'.htmlspecialchars($msg, ENT_NOQUOTES, "UTF-8").'</comment>'.
    ($url!=''?'<content><title>'.$title.'</title><submitted-url>'.$url.'</submitted-url><submitted-image-url>'.$imgURL.'</submitted-image-url><description>'.htmlspecialchars($dsc, ENT_NOQUOTES, "UTF-8").'</description></content>':'').
    '<visibility><code>anyone</code></visibility></share>';
    $request = nsx_trOAuthRequest::from_consumer_and_token($this->consumer, $this->access_token, "POST", $status_url);
    $request->sign_request($this->signature_method, $this->consumer, $this->access_token);
    $auth_header = $request->to_header("https://api.linkedin.com");
    if ($debug) echo $auth_header . "\n";
    $response = $this->httpRequest($status_url, $auth_header, "POST", $xml); 
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
    $response = $this->httpRequest($status_url, $auth_header, "PUT", $xml); prr($response);
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
  
  function httpRequest($url, $auth_header, $method, $body = NULL) {
    if (!$method) $method = "GET"; $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array($auth_header)); // Set the headers.

    if ($body) {
      curl_setopt($curl, CURLOPT_POST, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array($auth_header, "Content-Type: text/xml;charset=utf-8"));   
    }

    $data = curl_exec($curl); $header = curl_getinfo($curl);  curl_close($curl); 
    if ($this->debug) echo $data . "\n";    
    if (trim($data)=='' && $header['http_code']=='201') $data = '201';
    return $data; 
  }

}

function decode_entities_full($string, $quotes = ENT_COMPAT, $charset = 'utf-8') {
  return html_entity_decode(preg_replace_callback('/&([a-zA-Z][a-zA-Z0-9]+);/', 'convert_entity', $string), $quotes, $charset); 
}
function convert_entity($matches, $destroy = true) {
  static $table = array('quot' => '&#34;','amp' => '&#38;','lt' => '&#60;','gt' => '&#62;','OElig' => '&#338;','oelig' => '&#339;','Scaron' => '&#352;','scaron' => '&#353;','Yuml' => '&#376;','circ' => '&#710;','tilde' => '&#732;','ensp' => '&#8194;','emsp' => '&#8195;','thinsp' => '&#8201;','zwnj' => '&#8204;','zwj' => '&#8205;','lrm' => '&#8206;','rlm' => '&#8207;','ndash' => '&#8211;','mdash' => '&#8212;','lsquo' => '&#8216;','rsquo' => '&#8217;','sbquo' => '&#8218;','ldquo' => '&#8220;','rdquo' => '&#8221;','bdquo' => '&#8222;','dagger' => '&#8224;','Dagger' => '&#8225;','permil' => '&#8240;','lsaquo' => '&#8249;','rsaquo' => '&#8250;','euro' => '&#8364;','fnof' => '&#402;','Alpha' => '&#913;','Beta' => '&#914;','Gamma' => '&#915;','Delta' => '&#916;','Epsilon' => '&#917;','Zeta' => '&#918;','Eta' => '&#919;','Theta' => '&#920;','Iota' => '&#921;','Kappa' => '&#922;','Lambda' => '&#923;','Mu' => '&#924;','Nu' => '&#925;','Xi' => '&#926;','Omicron' => '&#927;','Pi' => '&#928;','Rho' => '&#929;','Sigma' => '&#931;','Tau' => '&#932;','Upsilon' => '&#933;','Phi' => '&#934;','Chi' => '&#935;','Psi' => '&#936;','Omega' => '&#937;','alpha' => '&#945;','beta' => '&#946;','gamma' => '&#947;','delta' => '&#948;','epsilon' => '&#949;','zeta' => '&#950;','eta' => '&#951;','theta' => '&#952;','iota' => '&#953;','kappa' => '&#954;','lambda' => '&#955;','mu' => '&#956;','nu' => '&#957;','xi' => '&#958;','omicron' => '&#959;','pi' => '&#960;','rho' => '&#961;','sigmaf' => '&#962;','sigma' => '&#963;','tau' => '&#964;','upsilon' => '&#965;','phi' => '&#966;','chi' => '&#967;','psi' => '&#968;','omega' => '&#969;','thetasym' => '&#977;','upsih' => '&#978;','piv' => '&#982;','bull' => '&#8226;','hellip' => '&#8230;','prime' => '&#8242;','Prime' => '&#8243;','oline' => '&#8254;','frasl' => '&#8260;','weierp' => '&#8472;','image' => '&#8465;','real' => '&#8476;','trade' => '&#8482;','alefsym' => '&#8501;','larr' => '&#8592;','uarr' => '&#8593;','rarr' => '&#8594;','darr' => '&#8595;','harr' => '&#8596;','crarr' => '&#8629;','lArr' => '&#8656;','uArr' => '&#8657;','rArr' => '&#8658;','dArr' => '&#8659;','hArr' => '&#8660;','forall' => '&#8704;','part' => '&#8706;','exist' => '&#8707;','empty' => '&#8709;','nabla' => '&#8711;','isin' => '&#8712;','notin' => '&#8713;','ni' => '&#8715;','prod' => '&#8719;','sum' => '&#8721;','minus' => '&#8722;','lowast' => '&#8727;','radic' => '&#8730;','prop' => '&#8733;','infin' => '&#8734;','ang' => '&#8736;','and' => '&#8743;','or' => '&#8744;','cap' => '&#8745;','cup' => '&#8746;','int' => '&#8747;','there4' => '&#8756;','sim' => '&#8764;','cong' => '&#8773;','asymp' => '&#8776;','ne' => '&#8800;','equiv' => '&#8801;','le' => '&#8804;','ge' => '&#8805;','sub' => '&#8834;','sup' => '&#8835;','nsub' => '&#8836;','sube' => '&#8838;','supe' => '&#8839;','oplus' => '&#8853;','otimes' => '&#8855;','perp' => '&#8869;','sdot' => '&#8901;','lceil' => '&#8968;','rceil' => '&#8969;','lfloor' => '&#8970;','rfloor' => '&#8971;','lang' => '&#9001;','rang' => '&#9002;','loz' => '&#9674;','spades' => '&#9824;','clubs' => '&#9827;','hearts' => '&#9829;','diams' => '&#9830;','nbsp' => '&#160;','iexcl' => '&#161;','cent' => '&#162;','pound' => '&#163;','curren' => '&#164;','yen' => '&#165;','brvbar' => '&#166;','sect' => '&#167;','uml' => '&#168;','copy' => '&#169;','ordf' => '&#170;','laquo' => '&#171;','not' => '&#172;','shy' => '&#173;','reg' => '&#174;','macr' => '&#175;','deg' => '&#176;','plusmn' => '&#177;','sup2' => '&#178;','sup3' => '&#179;','acute' => '&#180;','micro' => '&#181;','para' => '&#182;','middot' => '&#183;','cedil' => '&#184;','sup1' => '&#185;','ordm' => '&#186;','raquo' => '&#187;','frac14' => '&#188;','frac12' => '&#189;','frac34' => '&#190;','iquest' => '&#191;','Agrave' => '&#192;','Aacute' => '&#193;','Acirc' => '&#194;','Atilde' => '&#195;','Auml' => '&#196;','Aring' => '&#197;','AElig' => '&#198;','Ccedil' => '&#199;','Egrave' => '&#200;','Eacute' => '&#201;','Ecirc' => '&#202;','Euml' => '&#203;','Igrave' => '&#204;','Iacute' => '&#205;','Icirc' => '&#206;','Iuml' => '&#207;','ETH' => '&#208;','Ntilde' => '&#209;','Ograve' => '&#210;','Oacute' => '&#211;','Ocirc' => '&#212;','Otilde' => '&#213;','Ouml' => '&#214;','times' => '&#215;','Oslash' => '&#216;','Ugrave' => '&#217;','Uacute' => '&#218;','Ucirc' => '&#219;','Uuml' => '&#220;','Yacute' => '&#221;','THORN' => '&#222;','szlig' => '&#223;','agrave' => '&#224;','aacute' => '&#225;','acirc' => '&#226;','atilde' => '&#227;','auml' => '&#228;','aring' => '&#229;','aelig' => '&#230;','ccedil' => '&#231;','egrave' => '&#232;','eacute' => '&#233;','ecirc' => '&#234;','euml' => '&#235;','igrave' => '&#236;','iacute' => '&#237;','icirc' => '&#238;','iuml' => '&#239;','eth' => '&#240;','ntilde' => '&#241;','ograve' => '&#242;','oacute' => '&#243;','ocirc' => '&#244;','otilde' => '&#245;','ouml' => '&#246;','divide' => '&#247;','oslash' => '&#248;','ugrave' => '&#249;','uacute' => '&#250;','ucirc' => '&#251;','uuml' => '&#252;','yacute' => '&#253;','thorn' => '&#254;','yuml' => '&#255;');
  if (isset($table[$matches[1]])) return $table[$matches[1]];
  // else 
  return $destroy ? '' : $matches[0];
}