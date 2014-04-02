<?php    
//## NextScripts deviantART Connection Class

/* 
1. Options

nName - Nickname of the account [Optional] (Presentation purposes only - No affect on functionality)
rdUName - Reddit User Name
rdPass - Reddit User Passord
rdSubReddit - Name of the Sub-Reddit

rdTitleFormat
rdTextFormat

2. Post Info

url
title - [up to 300 characters long] - title of the submission
text

*/
$nxs_snapAPINts[] = array('code'=>'DA', 'lcode'=>'da', 'name'=>'deviantART');

if (!function_exists("doConnectToDeviantART")) { function doConnectToDeviantART($unm, $pass){ $url = "https://www.deviantart.com/users/login";  $hdrsArr = nxs_getDAHeaders('http://www.deviantart.com/');
  $rep = wp_remote_get($url, array( 'headers' => $hdrsArr, 'httpversion' => '1.1')); if (is_wp_error($rep)) {  $badOut = print_r($rep, true)." - ERROR"; return $badOut; }  $ck =  $rep['cookies'];
  $rTok = CutFromTo($rep['body'], 'name="validate_token" value="', '"'); $rKey = CutFromTo($rep['body'], 'name="validate_key" value="', '"'); $ck[0]->value = urlencode($ck[0]->value);
  $hdrsArr = nxs_getDAHeaders('https://www.deviantart.com/users/login', 'https://www.deviantart.com/', true);
  $flds = array('ref' => 'https://www.deviantart.com/users/loggedin', 'username' => $unm, 'password' => $pass, 'remember_me' => '1', 'validate_token' => $rTok, 'validate_key' => $rKey);
  $response = wp_remote_post( $url, array( 'method' => 'POST', 'httpversion' => '1.1', 'timeout' => 45, 'redirection' => 0, 'cookies' => $ck, 'headers' => $hdrsArr, 'body' => $flds));  
  if (is_wp_error($response)) {  $badOut = print_r($response, true)." - ERROR"; return $badOut; }  
  $ck =  $response['cookies']; for($i=0;$i<4;$i++) $ck[$i]->value = urlencode($ck[$i]->value);   
  if (isset($response['headers']['location']) && stripos($response['headers']['location'], 'wrong-password')!==false  ) {  $badOut = "Wrong Password - ERROR"; return $badOut; }  
  if (isset($response['headers']['location']) && ( $response['headers']['location']=='http://www.deviantart.com' || $response['headers']['location']=='https://www.deviantart.com/users/loggedin')) { 
      $hdrsArr = nxs_getDAHeaders('http://www.deviantart.com'); $rep = wp_remote_get( 'http://www.deviantart.com', array( 'headers' => $hdrsArr, 'httpversion' => '1.1', 'cookies' => $ck));// die();  prr($rep);     
      if (is_wp_error($rep)) {  $badOut = print_r($rep, true)." - ERROR"; return $badOut; } $mh = CutFromTo($rep['body'], 'Your Account</b><br><a href="', '"'); $mh = str_ireplace('/journal/', '', $mh);
      return array('mh'=>$mh, 'ck'=>$ck);    
  } else  $badOut = print_r($response, true)." - ERROR"; return $badOut; 
}}

if (!function_exists("nxs_getDAHeaders")) {  function nxs_getDAHeaders($ref, $org='', $post=false, $aj=false){ $hdrsArr = array(); 
 $hdrsArr['Cache-Control']='max-age=0'; $hdrsArr['Connection']='keep-alive'; $hdrsArr['Referer']=$ref;
 $hdrsArr['User-Agent']='Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.22 Safari/537.36';
 if($post===true) $hdrsArr['Content-Type']='application/x-www-form-urlencoded'; 
 if($aj===true) $hdrsArr['X-Requested-With']='XMLHttpRequest'; 
 if ($org!='') $hdrsArr['Origin']=$org; 
 $hdrsArr['Accept']='text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';// $hdrsArr['DNT']='1';
 if (function_exists('gzdeflate')) $hdrsArr['Accept-Encoding']='gzip,deflate,sdch'; 
 $hdrsArr['Accept-Language']='en-US,en;q=0.8'; return $hdrsArr;
}}

if (!class_exists("nxs_class_SNAP_DA")) { class nxs_class_SNAP_DA {
    
    var $ntCode = 'DA';
    var $ntLCode = 'da';
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array(); // return false;
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }       
    
    function doPostToNT($options, $message){ global $nxs_urlLen; $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>'');
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['daUName']) || trim($options['daUName'])=='' || !isset($options['daPass']) || trim($options['daPass'])=='') { $badOut['Error'] = 'No username/password Found'; return $badOut; }      
      //## Format Post
      if (!empty($message['pTitle'])) $title = $message['pTitle']; else $title = nxs_doFormatMsg($options['daTitleFormat'], $message); $title = nsTrnc($title, 300);  
      if (!empty($message['pText'])) $text = $message['pText']; else $text = nxs_doFormatMsg($options['daTextFormat'], $message);       
      //## Make Post            
      $pass = substr($options['daPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['daPass'], 5)):$options['daPass'];  $hdrsArr = nxs_getDAHeaders('http://okapy6.deviantart.com/journal/?edit', 'https://www.deviantart.com');
      $loginInfo = doConnectToDeviantART($options['daUName'], $pass);  if (!is_array($loginInfo))  {  $badOut['Error'] = print_r($loginInfo, true)." - ERROR"; return $badOut; }  
      
      $ck = $loginInfo['ck']; $mh = $loginInfo['mh'];
      $rep = wp_remote_get( $mh.'/journal/?edit', array( 'headers' => $hdrsArr, 'cookies' => $ck)); //prr($rep); die();
      if (is_wp_error($rep)) {  $badOut = print_r($rep, true)." - ERROR"; return $badOut; } 
      $contents = CutFromTo($rep['body'], '<h3 class="journal-editor-create">', '</form>'); // prr($contents);
      
      $md = array(); 
      while (stripos($contents, '"hidden"')!==false){$contents = substr($contents, stripos($contents, '"hidden"')+8); $name = trim(CutFromTo($contents,'name="', '"'));
        if (!in_array($name, $md)) { $md[] = $name; $val = trim(CutFromTo($contents,'value="', '"')); $flds[$name]= urldecode (nxs_decodeEntities($val)); }
      } $flds['subject'] = nsTrnc(nxs_decodeEntities($title), 50); $flds['body'] = trim($text);  $flds['song'] = ''; 
      
      $flds['game'] = ''; $flds['book'] = ''; $flds['food'] = ''; $flds['movie'] = ''; $flds['drink'] = ''; $flds['flip'] = '0'; $flds['featured'] = '1'; 
      $flds['portal'] = '1'; $flds['skinlabel'] = 'No+skin'; $flds['jheader'] = ''; $flds['jcss'] = ''; $flds['jfooter'] = '';       
      $ck2 =  $rep['cookies']; for($i=0;$i<3;$i++) if ($ck[$i]->name=='userinfo') $ck[$i]->value = urlencode($ck2[0]->value);       
      $hdrsArr = nxs_getDAHeaders($mh.'/journal/?edit', $mh, true);
      $response = wp_remote_post($mh.'/journal/?edit', array( 'method' => 'POST', 'httpversion' => '1.1', 'timeout' => 45, 'redirection' => 0, 'headers' => $hdrsArr, 'body' => $flds, 'cookies' => $ck));
      
    //  prr($response);
      
      if ($response['response']['code']=='302') { $hdrsArr = nxs_getDAHeaders('http://okapy6.deviantart.com/journal/?edit');
          $rep = wp_remote_get(  $mh.'/journal/', array( 'headers' => $hdrsArr, 'cookies' => $ck)); 
          $daNewPostURL = CutFromTo($rep['body'], 'a data-deviationid="', '</a>'); $daNewPostURL = CutFromTo($daNewPostURL, 'href="', '"'); $daNewPostID = CutFromTo($rep['body'], 'a data-deviationid="', '"');          
         return array('postID'=>$daNewPostID, 'isPosted'=>1, 'postURL'=>$daNewPostURL, 'pDate'=>date('Y-m-d H:i:s'));          
      } else { $badOut['Error'] .= 'Somethibng is not right';
        return $badOut;
      }
      return $badOut;
    }  
    
}}
?>