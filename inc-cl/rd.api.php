<?php    
//## NextScripts Twitter Connection Class

/* 
1. Options

nName - Nickname of the account [Optional] (Presentation purposes only - No affect on functionality)
rdUName - Reddit User Name
rdPass - Reddit User Passord
rdSubReddit - Name of the Sub-Reddit
postType - A or T - "Attached link" or "Text"

rdTitleFormat
rdTextFormat

2. Post Info

url
title - [up to 300 characters long] - title of the submission
text

*/
$nxs_snapAPINts[] = array('code'=>'RD', 'lcode'=>'rd', 'name'=>'Reddit');

if (!class_exists("nxs_class_SNAP_RD")) { class nxs_class_SNAP_RD {
    
    var $ntCode = 'RD';
    var $ntLCode = 'rd';
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array(); // return false;
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }     
    function doPostToNT($options, $message){ global $nxs_urlLen; $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>'');
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['rdUName']) || trim($options['rdUName'])=='' || !isset($options['rdPass']) || trim($options['rdPass'])=='') { $badOut['Error'] = 'No username/password Found'; return $badOut; }      
      //## Format Post
      if (!empty($message['pTitle'])) $title = $message['pTitle']; else $title = nxs_doFormatMsg($options['rdTitleFormat'], $message); $title = nsTrnc($title, 300);  
      if (!empty($message['pText'])) $text = $message['pText']; else $text = nxs_doFormatMsg($options['rdTextFormat'], $message);       
      //## Make Post            
      $pass = substr($options['rdPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['rdPass'], 5)):$options['rdPass'];   $hdrsArr = '';      
      $loginInfo = doConnectToRD($options['rdUName'], $pass); if (!is_array($loginInfo))  {  $badOut['Error'] = print_r($loginInfo, true)." - ERROR"; return $badOut; }  
      $mh = $loginInfo['mh']; $ck = $loginInfo['ck']; $post = array('uh'=>$mh, 'sr'=>$options['rdSubReddit'], 'title'=>$title, 'save'=>true);      
      if ($options['postType']=='A') { $post['url'] = $message['url']; $post['kind']='link'; $retNum = 16; } else { $post['text'] = $text; $post['kind']='self'; $retNum = 10; }         
      $url = "http://www.reddit.com/api/submit"; $postParams = array( 'method' => 'POST', 'timeout' => 45, 'redirection' => 0, 'extension'=>'json',  'headers' => $hdrsArr, 'body' => $post, 'cookies' => $ck);      
      $response = wp_remote_post($url, $postParams); // prr($postParams); prr($response);
      if (is_wp_error($response)) {  $badOut['Error'] = print_r($response, true)." - ERROR"; return $badOut; } 
      $response = json_decode($response['body'], true); $rdNewPostID = 'http://www.reddit.com'; // prr($response);
      
      if (!isset($response['jquery']) || !is_array($response['jquery'])) {  $badOut['Error'] = print_r($response, true)." - ERROR"; return $badOut; } 
      $r = $response['jquery']; if (is_array($r[$retNum][3]) && count($r[$retNum][3])>0 && stripos($r[$retNum][3][0], 'http://')!==false) $rdNewPostID = $r[$retNum][3][0];             
      if (isset($r[18]) && is_array($r[18][3]) && count($r[18][3])>0 && stripos($r[18][3][0], 'error.BAD_CAPTCHA')!==false ) {  $badOut['Error'] = "ERROR: Post Rejected. Reddit thinks that you don't have rights to post here"; return $badOut; } 
      if (isset($r[18]) && is_array($r[18][3]) && count($r[18][3])>0 && stripos($r[18][3][0], 'error')!==false ) {  $badOut['Error'] = "ERROR: ".$r[18][3][0]; return $badOut; } 
      if (is_array($r[$retNum][3]) && count($r[$retNum][3])>0 && stripos($r[$retNum][3][0], 'http://')===false) {  $badOut['Error'] = print_r($r[$retNum][3][0], true)." - ERROR"; return $badOut; } 
      if (isset($r[18]) && is_array($r[18][3]) && count($r[18][3])>0 && stripos($r[18][3][0], 'already been submitted')!==false ) $rdNewPostID .= str_ireplace('?already_submitted=true', '', $r[10][3][0]); 
      // echo "ID:".$rdNewPostID;
      if ($rdNewPostID!='http://www.reddit.com') {         
         return array('postID'=>$rdNewPostID, 'isPosted'=>1, 'postURL'=>$rdNewPostID, 'pDate'=>date('Y-m-d H:i:s'));          
      } else { $badOut['Error'] .= print_r($tmhOAuth->response['response'], true)." MSG:".print_r($msg, true); 
        return $badOut;
      }
      return $badOut;
    }  
    
}}
?>