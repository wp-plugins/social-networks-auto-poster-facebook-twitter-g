<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAPINts[] = array('code'=>'FB', 'lcode'=>'fb', 'name'=>'Facebook');

if (!class_exists("nxs_class_SNAP_FB")) { class nxs_class_SNAP_FB {
    
    var $ntCode = 'FB';
    var $ntLCode = 'fb';
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array(); //return false;
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }
    function doPostToNT($options, $message){ $badOut = array('Warning'=>'', 'Error'=>''); $wprg = array('sslverify'=>false); 
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (empty($options['fbAppAuthToken']) && empty($options['atpKey']) && empty($options['uName'])) { $badOut['Error'] = 'No Auth Token Found/Not configured'; return $badOut; }
      //## Make Post
      
      
      // $facebook = new NXS_Facebook(array( 'appId' => $options['fbAppID'], 'secret' => $options['fbAppSec'], 'cookie' => true )); 
      if (!empty($options['fbAppAuthToken'])) if (!isset($options['fbAppPageAuthToken']) || trim($options['fbAppPageAuthToken'])=='') $options['fbAppPageAuthToken'] = $options['fbAppAuthToken'];
      
      //## Some OLD Format Conversion
      if (!isset($options['attachType']) && isset($options['fbAttch'])) $options['attachType'] = $options['fbAttch'];
      if (!isset($options['postType']) && isset($options['fbPostType'])) $options['postType'] = $options['fbPostType'];  $fbPostType = $options['postType']; //## Compatibility with v <3.2
      if (!isset($options['pgID']) && isset($options['fbPgID'])) $options['pgID'] = $options['fbPgID'];      
      
      if ($fbPostType!='I' && $fbPostType!='T') { $url = $message['url']; $flds = array('id'=>$url, 'scrape'=>'true'); sleep(2); }            
      //## Get URL info.      
      if ($fbPostType!='I' && $fbPostType!='T' && isset($options['useFBGURLInfo']) && $options['useFBGURLInfo']=='1') { 
        $response =  wp_remote_post('http://graph.facebook.com', array('body' => $flds, 'sslverify'=>false ));      
        if (is_wp_error($response)) $badOut['Error'] = print_r($response, true)." - ERROR"; else { $response = json_decode($response['body'], true);     //  prr($response);     die();
            if (!empty($response['description'])) $message['urlDescr'] = $response['description'];  if (!empty($response['title'])) $message['urlTitle'] =  $response['title'];
            if (!empty($response['site_name'])) $message['siteName'] = $response['site_name']; elseif ($message['siteName']=='') $message['siteName'] = $message['title'];
            if (!empty($response['image'][0]['url'])) $message['imageURL'] = $response['image'][0]['url'];
        }
      } // prr($message);
      if (!empty($message['pText'])) $msg = $message['pText']; else $msg = nxs_doFormatMsg($options['fbMsgFormat'], $message); 
      $imgURL = nxs_getImgfrOpt($message['imageURL']); $fbWhere = 'feed'; 
      $attachType = $options['attachType']; if ($attachType=='1') $attachType = 'A'; else $attachType = 'S';
      if ($options['imgUpl']!='2') $options['imgUpl'] = 'T'; else $options['imgUpl'] = 'A'; 
      
      if (stripos($options['fbURL'], '/groups/')!=false) $options['destType'] == 'gr';
      
      if (!empty($options['destType']) && $options['destType'] == 'pr') $page_id = $options['fbAppAuthUser']; else $page_id = $options['pgID'];        
      $msg = strip_tags($msg); $msg = str_ireplace('&lt;(")','<(")', $msg); //## FB Smiles FIX 3
      if (substr($msg, 0, 1)=='@') $msg = ' '.$msg; // ERROR] couldn't open file fix
      
      //## Own App Post
      if (!empty($options['fbAppPageAuthToken'])) {
        if (empty($options['appsecret_proof'])) $options['appsecret_proof'] = hash_hmac('sha256', $options['fbAppPageAuthToken'], $options['fbAppSec']); 
        $mssg = array('access_token'=>$options['fbAppPageAuthToken'], 'appsecret_proof'=>$options['appsecret_proof'], 'method'=>'post', 'message'=>$msg);
        if ($fbPostType=='I' && trim($imgURL)=='') $fbPostType='T';
        if ($fbPostType=='A' && !(preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $message['url']))) { 
            $badOut['Warning'] = 'Unvalid URL: '.$message['url'].'| Will be posting as text message'; $fbPostType='T'; 
        }
        if ($fbPostType=='A' || $fbPostType=='') {
          if (($attachType=='A' || $attachType=='S')) { $attArr = array('name' => $message['urlTitle'], 'caption' => $message['siteName'], 'link' =>$message['url'], 'description' => $message['urlDescr']); 
          $mssg = array_merge($mssg, $attArr); ; }
          if ($attachType=='A') $mssg['actions'] = json_encode(array('name' => $message['siteName'], 'link' =>$message['url']));        
          if (trim($imgURL)!='') $mssg['picture'] = $imgURL;  if (trim($message['videoURL'])!='') $mssg['source'] = $message['videoURL'];        
        } elseif ($fbPostType=='I') { /* $facebook->setFileUploadSupport(true); */ $fbWhere = 'photos'; $mssg['url'] = $imgURL; 
          if ($options['imgUpl']=='T') { //## Try to Post to TImeline
            $aacct = array('access_token'=>$options['fbAppPageAuthToken'], 'appsecret_proof'=>$options['appsecret_proof'], 'method'=>'get');  
            $res = wp_remote_get( "https://graph.facebook.com/$page_id/albums?".http_build_query($aacct, null, '&'),$wprg); 
            if (is_wp_error($res) || empty($res['body'])) $badOut['Error'] = ' [ERROR] '.print_r($res, true); else {
              $albums = json_decode($res['body'], true);  if (empty($albums)) $badOut['Error'] .= "JSON ERROR: ".print_r($res, true); else {
                if (is_array($albums) && is_array($albums["data"])) foreach ($albums["data"] as $album) { if ($album["type"] == "wall") { $chosen_album = $album; break;}}
                if (isset($chosen_album) && isset($chosen_album["id"])) $page_id = $chosen_album["id"];
              }
            }
          }        
        } //$page_id = '1444414072467583';
        //## Actual Post
        $destURL = "https://graph.facebook.com/$page_id/".$fbWhere; // prr($destURL); 
        $response = wp_remote_post( $destURL, array( 'method' => 'POST', 'httpversion' => '1.1', 'timeout' => 45, 'sslverify'=>false, 'redirection' => 0, 'body' => $mssg)); 
      }
      //## Autopost.to
      if (!empty($options['atpKeyZZZZZZZ'])) { $toGo = array('g'=>$msg, 'o'=>$options, 'm'=>$message); $toGo = base64_encode(serialize($toGo));
        $toGo = array('nxsremotepost' => $toGo); 
        $response = wp_remote_post( 'http://autopost.to/post/', array( 'method' => 'POST', 'httpversion' => '1.1', 'sslverify'=>false, 'timeout' => 45, 'redirection' => 0, 'body' => $toGo)); 
        if (is_wp_error($response) || empty($response['body'])) return "ERROR: ".print_r($response, true);
        
        prr($response['body']); die();
      }
      
      if (is_wp_error($response) || empty($response['body'])) return "ERROR: ".print_r($response, true);
      $res = json_decode($response['body'], true); if (empty($res)) return "JSON ERROR: ".print_r($response, true);
      if (!empty($res['error'])) if (!empty($res['error']['message'])) { $badOut['Error'] .= $res['error']['message']; //## Some Known Errors
        if (stripos($res['error']['message'], 'This API call requires a valid app_id')!==false) { 
            if ( !is_numeric($page_id) && stripos($options['fbURL'], '/groups/')!=false) $badOut['Error'] .= ' [ERROR] Unrecognized Facebook Group ID. Please use numeric ID. Please see <a href="http://gd.is/f412">FAQ 4.12</a>'; 
              else $badOut['Error'] .= " [ERROR] (invalid app_id) Authorization Error. <br/>\r\n<br/>\r\n Possible Reasons: <br/>\r\n 1. Your app is not authorized. Please go to the Plugin Settings - Facebook and authorize it.<br/>\r\n 2. The current authorized user have no rights to post to the specified page. Please login to Facebook as the correct user and Re-Authorize the Plugin.<br/>\r\n 3. You clicked 'Skip' or unchecked the 'Manage Pages' or 'Post on your behalf' permissions when Authorization wizard asked you. Please Re-Authorize the Plugin<br/>\r\n"; 
        }
        if (stripos($res['error']['message'], 'Some of the aliases you requested do not exist')!==false) $badOut['Error'] .= '| Please check what do you have in the "Facebook URL" field.';
        if (stripos($res['error']['message'], 'The target user has not authorized this action')!==false) $badOut['Error'] .= '| Please Authorize the plugin from the plugin settings Page - Facebook.';
        
        return $badOut;          
      } else return print_r($res['error'], true);
      if (empty($res['id'])) return print_r($res, true);
      //## All Good!
      $pgID = (isset($res['post_id']) && strpos($res['post_id'],'_')!==false)?$res['post_id']:$res['id']; $pgg = explode('_', $pgID); $postID = $pgg[1];
      $pgURL = 'http://www.facebook.com/'.$options['pgID'].'/posts/'.$postID; 
      return array('isPosted'=>'1', 'postID'=>$pgID, 'postURL'=>$pgURL, 'pDate'=>date('Y-m-d H:i:s'), 'log'=>$badOut);      
    }
}}
?>