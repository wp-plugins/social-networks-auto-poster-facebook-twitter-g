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
    function doPostToNT($options, $message){ $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>'');
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['twAccToken']) || trim($options['twAccToken'])=='') { $badOut['Error'] = 'No Auth Token Found'; return $badOut; }
      //## Make Post
      $msg = $message['message']; $imgURL = trim($message['imageURL']); $img = trim($message['img']); $nxs_urlLen = $message['urlLength'];
      
      if ($options['attchImg']=='1' && $img=='' && $imgURL=='') $options['attchImg'] = 0;   
      if ($options['attchImg']=='1' && $img=='' && $imgURL!='' ) {
        if( ini_get('allow_url_fopen') ) { if (getimagesize($imgURL)!==false) { $img = nxs_remote_get($imgURL); if(is_nxs_error($img)) $options['attchImg'] = 0; else $img = $img['body']; } else $options['attchImg'] = 0; } 
          else { $img = nxs_remote_get($imgURL); if(is_nxs_error($img)) $options['attchImg'] = 0; elseif (isset($img['body'])&& trim($img['body'])!='') $img = $img['body'];  else $options['attchImg'] = 0; }   
      }  
      if ($options['attchImg']=='1' && $img!='') $twLim = 118; else $twLim = 140;
      
      require_once ('apis/tmhOAuth.php'); require_once ('apis/tmhUtilities.php');  if ($nxs_urlLen>0) { $msg = nsTrnc($msg, $twLim-22+$nxs_urlLen); } else $msg = nsTrnc($msg, $twLim);
      $tmhOAuth = new NXS_tmhOAuth(array( 'consumer_key' => $options['twConsKey'], 'consumer_secret' => $options['twConsSec'], 'user_token' => $options['twAccToken'], 'user_secret' => $options['twAccTokenSec']));      
      if ($options['attchImg']=='1' && $img!='') $code = $tmhOAuth -> request('POST', 'http://upload.twitter.com/1/statuses/update_with_media.json', array( 'media[]' => $img, 'status' => $msg), true, true);    
        else $code = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), array('status' =>$msg));         
      if ( $code=='403' && stripos($tmhOAuth->response['response'], 'User is over daily photo limit')!==false && $options['attchImg']=='1' && $img!='') { 
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