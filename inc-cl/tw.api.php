<?php    
//## NextScripts Twitter Connection Class
$nxs_snapAPINts[] = array('code'=>'TW', 'lcode'=>'tw', 'name'=>'Twitter');

if (!class_exists("nxs_class_SNAP_TW")) { class nxs_class_SNAP_TW {
    
    var $ntCode = 'TW';
    var $ntLCode = 'tw';
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array(); // return false;
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }
    function doPostToNT($options, $message){ global $nxs_urlLen; $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>'');
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['twAccToken']) || trim($options['twAccToken'])=='') { $badOut['Error'] = 'No Auth Token Found'; return $badOut; }
      //## Old Settings Fix
      if ($options['attchImg']=='1') $options['attchImg'] = 'large'; if ($options['attchImg']=='0') $options['attchImg'] = false;
      if (isset($message['img'])) $img = trim($message['img']); else $img = '';     // prr($message);
      //## Format Post
      $msg = nxs_doFormatMsg($options['twMsgFormat'], $message);  if ($options['attchImg']!=false) { $imgURL = trim($message['imgURL'][$options['attchImg']]);
          if ($imgURL=='') $imgURL = trim($message['imgURL']['large']); if ($imgURL=='') $imgURL = trim($message['imgURL']['medium']); 
          if ($imgURL=='') $imgURL = trim($message['imgURL']['original']); if ($imgURL=='') $imgURL = trim($message['imgURL']['thumb']); 
      }
      if ($imgURL=='' && $img=='') $options['attchImg'] = false;   
      //## Make Post
      //$msg = $message['message']; $imgURL = trim($message['imageURL']); $img = trim($message['img']); $nxs_urlLen = $message['urlLength'];           
      if ($options['attchImg']!=false && $img=='' && $imgURL!='' ) {
        if( ini_get('allow_url_fopen') ) { if (getimagesize($imgURL)!==false) { $img = nxs_remote_get($imgURL); if(is_nxs_error($img)) $options['attchImg'] = false; else $img = $img['body']; } else $options['attchImg'] = false; } 
          else { $img = nxs_remote_get($imgURL); if(is_nxs_error($img)) $options['attchImg'] = false; elseif (isset($img['body'])&& trim($img['body'])!='') $img = $img['body'];  else $options['attchImg'] = false; }   
      }  
      if ($options['attchImg']!=false && $img!='') $twLim = 118; else $twLim = 140;
      
      require_once ('apis/tmhOAuth.php'); if ($nxs_urlLen>0) { $msg = nsTrnc($msg, $twLim-22+$nxs_urlLen); } else $msg = nsTrnc($msg, $twLim);
      $tmhOAuth = new NXS_tmhOAuth(array( 'consumer_key' => $options['twConsKey'], 'consumer_secret' => $options['twConsSec'], 'user_token' => $options['twAccToken'], 'user_secret' => $options['twAccTokenSec']));      
      if ($options['attchImg']!=false && $img!='') $code = $tmhOAuth -> request('POST', 'http://upload.twitter.com/1/statuses/update_with_media.json', array( 'media[]' => $img, 'status' => $msg), true, true);    
        else $code = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), array('status' =>$msg));         
      if ( $code=='403' && stripos($tmhOAuth->response['response'], 'User is over daily photo limit')!==false && $options['attchImg']!=false && $img!='') { 
         $badOut['Error'] .= "User is over daily photo limit. Will post without image\r\n"; $code = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), array('status' =>$msg));
      }        
      if ($code == 200){
         $twResp = json_decode($tmhOAuth->response['response'], true);  if (is_array($twResp) && isset($twResp['id_str'])) $twNewPostID = $twResp['id_str'];  
         if (is_array($twResp) && isset($twResp['user'])) $twPageID = $twResp['user']['screen_name'];
         return array('postID'=>$twNewPostID, 'isPosted'=>1, 'postURL'=>'https://twitter.com/'.$twPageID.'/status/'.$twNewPostID, 'pDate'=>date('Y-m-d H:i:s'));          
      } else { $badOut['Error'] .= print_r($tmhOAuth->response['response'], true)." MSG:".print_r($msg, true); 
        return $badOut;
      }
      return $badOut;
    }  
    
}}
?>