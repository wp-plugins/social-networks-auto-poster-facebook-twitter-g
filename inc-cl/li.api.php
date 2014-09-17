<?php    
//########################################
//##    LI API V1/V2 Combined Edition
//########################################
$nxs_snapAPINts[] = array('code'=>'LI', 'lcode'=>'li', 'name'=>'LinkedIn');

if (!class_exists("nxs_class_SNAP_LI")) { class nxs_class_SNAP_LI {
    
    var $ntCode = 'LI';
    var $ntLCode = 'li';     
    
    function postShare($tkn, $msg, $title='', $url='', $imgURL='', $dsc='') { $nURL = 'https://api.linkedin.com/v1/people/~/shares?format=json&oauth2_access_token='.$tkn;  
      $dsc =  nxs_decodeEntitiesFull(strip_tags($dsc));  $msg = strip_tags(nxs_decodeEntitiesFull($msg));  $title =  nxs_decodeEntitiesFull(strip_tags($title));
      $xml = '<?xml version="1.0" encoding="UTF-8"?><share><comment>'.htmlspecialchars($msg, ENT_NOQUOTES, "UTF-8").'</comment>'.
      ($url!=''?'<content><title>'.htmlspecialchars($title, ENT_NOQUOTES, "UTF-8").'</title><submitted-url>'.$url.'</submitted-url><submitted-image-url>'.$imgURL.'</submitted-image-url><description>'.htmlspecialchars($dsc, ENT_NOQUOTES, "UTF-8").'</description></content>':'').
        '<visibility><code>anyone</code></visibility></share>'; $hdrsArr = array();  $hdrsArr['Content-Type']='application/xml';      
      $wprg = array( 'method' => 'POST', 'headers' => $hdrsArr, 'httpversion' => '1.1', 'timeout' => 45, 'redirection' => 0, 'body' => $xml);  $wprg['sslverify'] = false;      
      $response  = wp_remote_post($nURL, $wprg); if (is_wp_error($response) || empty($response['body'])) return "ERROR: ".print_r($response, true);      
      $post = json_decode($response['body'], true); return $post; 
    }
  
    function postToGroup($tkn, $msg, $title, $groupID, $url='', $imgURL='', $dsc='') { $nURL = 'https://api.linkedin.com/v1/groups/'.$groupID.'/posts?oauth2_access_token='.$tkn; 
      $dsc =  nxs_decodeEntitiesFull(strip_tags($dsc));  $msg = strip_tags(nxs_decodeEntitiesFull($msg));  $title =  nxs_decodeEntitiesFull(strip_tags($title));
      $xml = '<?xml version="1.0" encoding="UTF-8"?><post><title>'.htmlspecialchars($title, ENT_NOQUOTES, "UTF-8").'</title>'."\n".'<summary>'.htmlspecialchars($msg, ENT_NOQUOTES, "UTF-8").'</summary>'."\n".'
        '.($url!=''?'<content><title>'.htmlspecialchars($title, ENT_NOQUOTES, "UTF-8").'</title>'."\n".'<submitted-url>'.$url.'</submitted-url>'."\n".'<submitted-image-url>'.$imgURL.'</submitted-image-url>'."\n".'<description>'.htmlspecialchars($dsc, ENT_NOQUOTES, "UTF-8").'</description></content>':'').'</post>'; $hdrsArr = array();  $hdrsArr['Content-Type']='application/xml';      
      $wprg = array( 'method' => 'POST', 'headers' => $hdrsArr, 'httpversion' => '1.1', 'timeout' => 45, 'redirection' => 0, 'body' => $xml);  $wprg['sslverify'] = false;      
      $response  = wp_remote_post($nURL, $wprg);if (is_wp_error($response) || $response['response']['code']!='201') return "ERROR: ".print_r($response, true);      
      return array('updateUrl'=>'https://www.linkedin.com/groups?home=&gid='.$groupID);
    }
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array();
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }        
    function doPostToNT($options, $message){ $badOut = array('postID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>''); $liPostID = '';

      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if ((!isset($options['ulName']) || trim($options['uPass'])=='') && (empty($options['liOAuthVerifier'])))  { $badOut['Error'] = 'Not Configured'; return $badOut; }                  
      if (empty($options['imgSize'])) $options['imgSize'] = ''; if (empty($options['liMsgFormatT'])) $options['liMsgFormatT'] = '%TITLE%'; 
      //## Format
      if (!empty($message['pText'])) $msg = $message['pText']; else $msg = nxs_doFormatMsg($options['liMsgFormat'], $message); 
      if (!empty($message['pTitle'])) $msgT = $message['pTitle']; else $msgT = nxs_doFormatMsg($options['liMsgFormatT'], $message);         
      if ($options['liAttch']=='1') { 
        if (isset($message['imageURL'])) $imgURL = trim(nxs_getImgfrOpt($message['imageURL'], $options['imgSize'])); else $imgURL = '';  if (preg_match("/noImg.\.png/i", $imgURL)) $imgURL = '';           
        if (!empty($message['urlDescr'])) $dsc = $message['urlDescr']; else $dsc = $msg;          
        $dsc = strip_tags($dsc); $dsc = nxs_decodeEntitiesFull($dsc); $dsc = nxs_html_to_utf8($dsc);  $dsc = nsTrnc($dsc, 300);        
      }        
      $msg  = strip_tags($msg); $msg = nxs_html_to_utf8($msg);  $msgT = nxs_html_to_utf8($msgT); $urlToGo = $message['url'];
    
      if (function_exists("doConnectToLinkedIn") && $options['ulName']!='' && $options['uPass']!='') {
        $dusername = $options['ulName']; $pass = (substr($options['uPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['uPass'], 5)):$options['uPass']); // ??? Do we need that??????
        $auth = doConnectToLinkedIn($options['ulName'], $options['uPass'], $options['ii']); if ($auth!=false) { $badOut['Error'] .= "|Auth Error - ".$auth;  return $badOut; }
        $to = $options['uPage']!=''?$options['uPage']:'http://www.linkedin.com/home'; $lnk = array(); $msg = str_ireplace('&nbsp;',' ',$msg);  $msg = nsTrnc(strip_tags($msg), 700);
        if ($options['liAttch']=='1') { $lnk['title'] = $message['urlTitle']; $lnk['postTitle'] = $msgT; $lnk['desc'] =  $message['urlDescr']; $lnk['url'] = $urlToGo; $lnk['img'] = $imgURL; }      
        $ret = doPostToLinkedIn($msg, $lnk, $to); $liPostID = $options['uPage'];
      } else { 
        if (!empty($options['isV2'])) {  //## V2
          if ($options['grpID']!=''){
            try { if ($msgT == '') $msgT = ' '; 
              if($options['liAttch']=='1') $ret = $this->postToGroup($options['liAccessToken'], $msg, $msgT, $options['grpID'], str_replace('&', '&amp;', $urlToGo), $imgURL, $dsc); 
                else $ret = $this->postToGroup($options['liAccessToken'], $msg, $msgT, $options['grpID']); 
              $liPostID= 'http://www.linkedin.com/groups?gid='.$options['grpID'];
            } catch (Exception $o){ $ret="ERROR: ".print_r($o, true); }        
          } else { //echo $msg ."|". nsTrnc($msgT, 200) ."|". $urlToGo ."|". $imgURL ."|". $dsc;
            if($options['liAttch']=='1') $ret = $this->postShare($options['liAccessToken'], $msg, nsTrnc($msgT, 200), str_replace('&', '&amp;', $urlToGo), $imgURL, $dsc); 
              else $ret = $this->postShare($options['liAccessToken'], $msg);
          }  
        } else { //## V1
          require_once ('apis/liOAuth.php'); $linkedin = new nsx_LinkedIn($options['liAPIKey'], $options['liAPISec']);  $linkedin->oauth_verifier = $options['liOAuthVerifier'];
          $linkedin->request_token = new nsx_trOAuthConsumer($options['liOAuthToken'], $options['liOAuthTokenSecret'], 1);     
          $linkedin->access_token = new nsx_trOAuthConsumer($options['liAccessToken'], $options['liAccessTokenSecret'], 1);  
          $msg = nsTrnc($msg, 700); //prr($urlToGo);  $urlToGo = urlencode($urlToGo);   prr($urlToGo); die();
          if ($options['grpID']!=''){
            try{ if ($msgT == '') $msgT = ' '; 
              if($options['liAttch']=='1') $ret = $linkedin->postToGroup($msg, $msgT, $options['grpID'], str_replace('&', '&amp;', $urlToGo), $imgURL, $dsc); 
                else $ret = $linkedin->postToGroup($msg, $msgT, $options['grpID']); 
              $liPostID= 'http://www.linkedin.com/groups?gid='.$options['grpID']; if ($ret=='201') $ret = array('updateUrl'=>$liPostID);
            } catch (Exception $o){ $ret="ERROR: ".print_r($o, true); }        
          } else { //echo $msg ."|". nsTrnc($msgT, 200) ."|". $urlToGo ."|". $imgURL ."|". $dsc;
            try{ if($options['liAttch']=='1') $ret = $linkedin->postShare($msg, nsTrnc($msgT, 200), str_replace('&', '&amp;', $urlToGo), $imgURL, $dsc); else $ret = $linkedin->postShare($msg); } catch (Exception $o){ $ret="ERROR:".print_r($o, true); }             
          }  
        }        
        if ($liPostID=='') $liPostID = $options['liUserInfo'];        
      } // prr($ret);
      if (!is_array($ret) && stripos($ret, '<update-url>')!==false) { $rurl = CutFromTo($ret,'<update-url>','</update-url>'); $ret = array('updateUrl'=>$rurl); }
      if (is_array($ret) && !empty($ret['updateUrl'])) { if (stripos($ret['updateUrl'], 'topic=')!==false) $liPostID = CutFromTo($ret['updateUrl'], 'topic=','&'); else $liPostID = ''; 
        return array('isPosted'=>'1', 'postID'=>$liPostID, 'postURL'=>$ret['updateUrl'], 'pDate'=>date('Y-m-d H:i:s'));  
      } else  { $badOut['Error'] .= print_r($ret, true); }
      return $badOut;     
   }    
}}
?>