<?php    
//## NextScripts FriendFeed Connection Class
$nxs_snapAPINts[] = array('code'=>'VK', 'lcode'=>'vk', 'name'=>'vKontakte(VK)');

if (!class_exists("nxs_class_SNAP_VK")) { class nxs_class_SNAP_VK {
    
    var $ntCode = 'VK';
    var $ntLCode = 'vk';     
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array();
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }    
    function nxs_uplImgtoVK($imgURL, $options){
      $postUrl = 'https://api.vkontakte.ru/method/photos.getWallUploadServer?gid='.(str_replace('-','',$options['pgIntID'])).'&access_token='.$options['vkAppAuthToken'];
      $response = wp_remote_get($postUrl); $thumbUploadUrl = $response['body'];    
      if (!empty($thumbUploadUrl)) { $thumbUploadUrlObj = json_decode($thumbUploadUrl); $VKuploadUrl = $thumbUploadUrlObj->response->upload_url; }   // prr($thumbUploadUrlObj); echo "UURL=====-----";
      if (!empty($VKuploadUrl)) {    
        // if (stripos($VKuploadUrl, '//pu.vkontakte.ru/c')!==false) { $c = 'c'.CutFromTo($VKuploadUrl, '.ru/c', '/'); $VKuploadUrl = str_ireplace('/pu.','/'.$c.'.',str_ireplace($c.'/','',$VKuploadUrl)); }
        $remImgURL = urldecode($imgURL); $urlParced = pathinfo($remImgURL); $remImgURLFilename = $urlParced['basename']; $imgData = wp_remote_get($remImgURL); $imgData = $imgData['body'];        
        $tmp=array_search('uri', @array_flip(stream_get_meta_data($GLOBALS[mt_rand()]=tmpfile())));  
        if (!is_writable($tmp)) return "Your temporary folder or file (file - ".$tmp.") is not writable. Can't upload image to VK";
        rename($tmp, $tmp.='.png'); register_shutdown_function(create_function('', "unlink('{$tmp}');"));       
        file_put_contents($tmp, $imgData); 
      
        $ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $VKuploadUrl); curl_setopt($ch, CURLOPT_POST, 1); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        global $nxs_skipSSLCheck; if ($nxs_skipSSLCheck===true) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        if (function_exists('curl_file_create')) { $file  = curl_file_create($tmp); curl_setopt($ch, CURLOPT_POSTFIELDS, array('photo' => $file)); } 
          else curl_setopt($ch, CURLOPT_POSTFIELDS, array('photo' => '@' . $tmp));

        $response = curl_exec($ch); $errmsg = curl_error($ch); curl_close($ch); //prr($response);
        
        $uploadResultObj = json_decode($response); // prr($response); //prr($uploadResultObj);
      
        if (!empty($uploadResultObj->server) && !empty($uploadResultObj->photo) && !empty($uploadResultObj->hash)) {
          $postUrl = 'https://api.vkontakte.ru/method/photos.saveWallPhoto?server='.$uploadResultObj->server.'&photo='.$uploadResultObj->photo.'&hash='.$uploadResultObj->hash.'&gid='.(str_replace('-','',$options['pgIntID'])).'&access_token='.$options['vkAppAuthToken'];
          $response = wp_remote_get($postUrl);            
          $resultObject = json_decode($response['body']); //prr($resultObject);
          if (isset($resultObject) && isset($resultObject->response[0]->id)) { return $resultObject->response[0]; } else { return false; }
        }
      }
    }
    function doPostToNT($options, $message){ $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>''); global $nxs_vkCkArray;
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (empty($options['imgSize'])) $options['imgSize'] = '';
      if ((!isset($options['uName']) || trim($options['uPass'])=='') && (!isset($options['vkAppAuthToken']) || trim($options['vkAppAuthToken'])==''))  { $badOut['Error'] = 'Not Configured'; return $badOut; }            
      $pass = (substr($options['uPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['uPass'], 5)):$options['uPass']);            
      //## Format
      if (!empty($message['pText'])) $msg = $message['pText']; else $msg = nxs_doFormatMsg($options['msgFrmt'], $message); $urlToGo = (!empty($message['url']))?$message['url']:'';
      
      $postType = $options['postType'];  //$link = urlencode($link); $desc = urlencode(substr($msg, 0, 500));      
  
      if (isset($message['imageURL'])) $imgURL = trim(nxs_getImgfrOpt($message['imageURL'], $options['imgSize'])); else $imgURL = '';
      $msgOpts = array(); $msgOpts['uid'] =  $options['vkPgID']; // if ($link!='') $msgOpts['link'] = $link;            
      if (!empty($message['videoURL']) && $postType=="I") { $postType='A';  $urlToGo=$message['videoURL']; $msgOpts['vID'] = $vids[0]; }  
      if ($postType=='I' && trim($imgURL)=='') $postType='T';  $msgOpts['type'] = $postType;   
      if (function_exists('nxs_doPostToVK') && $postType=='A' && $urlToGo!='') { 
        //## Login
        if (isset($options['vkSvC'])) $nxs_vkCkArray = maybe_unserialize( $options['vkSvC']); $loginError = true; 
        if (is_array($nxs_vkCkArray)) $loginError = nxs_doCheckVK(); if ($loginError!=false)  { 
           if (!empty($options['vkPh'])) { $replArr = explode(' ... ', $options['vkPhReq']); $ph = $options['vkPh'];           
             $ln = strlen($replArr[0]); if (substr($ph,0,$ln)==$replArr[0]) $ph = substr($ph,$ln);           
             $ln = strlen($replArr[1]); $mln = -$ln;  if (substr($ph,$mln)==$replArr[1]) $ph = substr($ph,$ln,$mln);
           } else $ph = '';  $loginError = nxs_doConnectToVK($options['uName'], $pass, $ph); 
        }  //       prr($loginError);
        if ($loginError!==false) { if (stripos($loginError, 'Phone verification required:')!==false) return $loginError; else return "ERROR - BAD USER/PASS - ".print_r($loginError, true); }
        //## Post        
        $msgOpts['url'] = $urlToGo; $msgOpts['urlTitle'] = $message['urlTitle']; $msgOpts['urlDesc'] = $message['urlDescr']; $msgOpts['imgURL'] = $imgURL; 
        $ret = nxs_doPostToVK($msg, $options['url'], $msgOpts);   
        if (is_array($ret) && !empty($ret['code']) && $ret['code']=='OK') return array('postID'=>$ret['post_id'], 'isPosted'=>1, 'postURL'=>'http://vk.com/wall'.$ret['post_id'], 'pDate'=>date('Y-m-d H:i:s')); 
          else $badOut .= 'ERROR: '.print_r($ret, true);
      } //prr($postType);
      
      if ($postType=='I') { $imgUpld = $this->nxs_uplImgtoVK($imgURL, $options); if (is_object($imgUpld)) { $imgID = $imgUpld->id; $atts[] = $imgID; } else  $badOut['Error'] .= '-=ERROR=- '.print_r($imgUpld, true); }
      if ($postType!='A') { if( $options['addBackLink']=='1') $atts[] = $urlToGo;       
        if (is_array($atts)) $atts = implode(',', $atts);
        
        $postUrl = 'https://api.vkontakte.ru/method/wall.post';
        $postArr = array('owner_id'=>$options['pgIntID'], 'access_token'=>$options['vkAppAuthToken'], 'from_group'=>'1', 'message'=>$msg, 'attachment'=>$atts);
        $response = wp_remote_post($postUrl, array('body' => $postArr)); 
        if ( is_wp_error($response) || (is_object($response) && (isset($response->errors))) || (is_array($response) && stripos($response['body'],'"error":')!==false )) { 
           $badOut['Error'] .= 'Error: '. print_r($response['body'], true);
        } else { $respJ = json_decode($response['body'], true);  $ret = $options['pgIntID'].'_'.$respJ['response']['post_id'];   }
          
      }                                
      if (isset($ret) && $ret!='') return array('postID'=>$ret, 'isPosted'=>1, 'postURL'=>'http://vk.com/wall'.$ret, 'pDate'=>date('Y-m-d H:i:s'), 'err'=>$badOut['Error']);       
      return $badOut;      
   }    
}}
?>