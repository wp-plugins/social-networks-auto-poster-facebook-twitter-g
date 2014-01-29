<?php    
//## NextScripts App.net Connection Class

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
$nxs_snapAPINts[] = array('code'=>'SC', 'lcode'=>'sc', 'name'=>'Scoop.It');

if (!class_exists("nxs_class_SNAP_SC")) { class nxs_class_SNAP_SC {
    
    var $ntCode = 'SC';
    var $ntLCode = 'sc';
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array(); // return false;
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }
    function createFile($imgURL, $auth) { $data = array();       
      $remImgURL = urldecode($imgURL); $urlParced = pathinfo($remImgURL); $remImgURLFilename = $urlParced['basename']; 
      $imgData = wp_remote_get($remImgURL); if (is_wp_error($imgData)) { $badOut['Error'] = print_r($imgData, true)." - ERROR"; return $badOut; }          
      $imgData = $imgData['body'];
      $tmp=array_search('uri', @array_flip(stream_get_meta_data($GLOBALS[mt_rand()]=tmpfile())));  
      if (!is_writable($tmp)) return "Your temporary folder or file (file - ".$tmp.") is not witable. Can't upload image to App.Net";
      rename($tmp, $tmp.='.png'); register_shutdown_function(create_function('', "unlink('{$tmp}');"));       
      file_put_contents($tmp, $imgData); if (!$tmp) return 'You must specify a path to a file'; if (!file_exists($tmp)) return 'File path specified does not exist';
      if (!is_readable($tmp)) return 'File path specified is not readable';
      if (!array_key_exists('name', $data)) $data['name'] = basename($tmp);
      if (array_key_exists('mime-type', $data)) { $mimeType = $data['mime-type']; unset($data['mime-type']);} else $mimeType = null;
      if (!array_key_exists('kind', $data)) { $test = @getimagesize($tmp); 
        if ($test && array_key_exists('mime', $test)) { $data['kind'] = 'image'; if (!$mimeType) $mimeType = $test['mime']; } else $data['kind'] = 'other';
      }
      if (!$mimeType && function_exists('finfo_open') ) { $finfo = finfo_open(FILEINFO_MIME_TYPE); $mimeType = finfo_file($finfo, $tmp); finfo_close($finfo); }
      if (!$mimeType) return 'Unable to determine mime type of file, try specifying it explicitly';  $data['type'] = "com.nextscripts.photos";
      $data['content'] = "@$tmp;type=$mimeType";
      $url = "https://alpha-api.app.net/stream/0/files?access_token=".$auth; 

      $ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $url); curl_setopt($ch, CURLOPT_POST, 1); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      global $nxs_skipSSLCheck; if ($nxs_skipSSLCheck===true) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data); $response = curl_exec($ch); $errmsg = curl_error($ch); curl_close($ch); //prr($response);
      if ($errmsg!='') return $errmsg; else $response = json_decode($response, true);
      if (!is_array($response) || !isset($response['meta']) || $response['meta']['code']!='200' || $response['data']['file_token']=='') return print_r($response, true);
      return array('id'=>$response['data']['id'], 'file_token'=>$response['data']['file_token'], 'url'=>$response['data']['url']);
    }

    function doPostToNT($options, $message){ global $nxs_urlLen; $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>'');
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['accessToken']) || trim($options['accessToken'])=='') { $badOut['Error'] = 'Not Authorized'; return $badOut; }      
      if (empty($options['imgSize'])) $options['imgSize'] = '';
      //## Format Post
      if (!empty($message['pText'])) $text = $message['pText']; else $text = nxs_doFormatMsg($options['msgFrmt'], $message);
      if (!empty($message['pTitle'])) $msgT = $message['pTitle']; else $msgT = nxs_doFormatMsg($options['msgTFrmt'], $message); 
      //## Make Post            
      if (isset($message['imageURL'])) $imgURL = trim(nxs_getImgfrOpt($message['imageURL'], $options['imgSize'])); else $imgURL = '';  $postType = $options['postType'];       
      
      require_once('apis/scOAuth.php');   $tum_oauth = new wpScoopITOAuth($options['appKey'], $options['appSec'], $options['accessToken'], $options['accessTokenSec']);
      $tiID = $tum_oauth->makeReq('http://www.scoop.it/api/1/topic', array('urlName'=>$options['topicURL']));  
      if (!empty($tiID) && !empty($tiID['topic']) && !empty($tiID['topic']['id'])) $tiID = $tiID['topic']['id']; else { $badOut['Error'] .= print_r($tiID, true); return $badOut; }
      $postArr = array('action'=>'create', 'title'=>$msgT, 'content'=>$text, 'url'=>$postType=='A'?$message['url']:'', 'imageUrl'=>(($postType=='I' || $postType=='A') && !empty($imgURL))?$imgURL:'', 'topicId'=>$tiID);  
      $postinfo = $tum_oauth->makeReq('http://www.scoop.it/api/1/post', $postArr, 'POST'); // prr($postinfo);
      
      if (is_array($postinfo) && isset($postinfo['post'])) { $apNewPostID = $postinfo['post']['id']; $apNewPostURL = $postinfo['post']['scoopUrl']; 
        if ($options['inclTags']=='1') { $postArr = array('action'=>'edit', 'tag'=>$message['tags'], 'id'=>$apNewPostID);  
          $postinfo = $tum_oauth->makeReq('http://www.scoop.it/api/1/post', $postArr, 'POST'); 
        }
              
      } $code = $tum_oauth->http_code;
      if (!empty($apNewPostID)) {         
         return array('postID'=>$apNewPostID, 'isPosted'=>1, 'postURL'=>$apNewPostURL, 'pDate'=>date('Y-m-d H:i:s'));          
      } else { $badOut['Error'] .= print_r($postinfo, true)." Code:".$tum_oauth->http_code; 
        return $badOut;
      }
      return $badOut;
    }  
    
}}
?>