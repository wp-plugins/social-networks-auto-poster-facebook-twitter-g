<?php    
//## NextScripts FriendFeed Connection Class
$nxs_snapAPINts[] = array('code'=>'LI', 'lcode'=>'li', 'name'=>'LinkedIn');

if (!class_exists("nxs_class_SNAP_LI")) { class nxs_class_SNAP_LI {
    
    var $ntCode = 'LI';
    var $ntLCode = 'li';     
    
    function doPost($options, $message){ if (!is_array($options)) return false; 
      foreach ($options as $ntOpts) $out[] = $this->doPostToNT($ntOpts, $message);
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
      } else { require_once ('apis/liOAuth.php'); $linkedin = new nsx_LinkedIn($options['liAPIKey'], $options['liAPISec']);  $linkedin->oauth_verifier = $options['liOAuthVerifier'];
        $linkedin->request_token = new nsx_trOAuthConsumer($options['liOAuthToken'], $options['liOAuthTokenSecret'], 1);     
        $linkedin->access_token = new nsx_trOAuthConsumer($options['liAccessToken'], $options['liAccessTokenSecret'], 1);  $msg = nsTrnc($msg, 700); //prr($urlToGo);  $urlToGo = urlencode($urlToGo);   prr($urlToGo); die();
        if ($options['grpID']!=''){
          try{ if ($msgT == '') $msgT = ' '; 
            if($options['liAttch']=='1') $ret = $linkedin->postToGroup($msg, $msgT, $options['grpID'], str_replace('&', '&amp;', $urlToGo), $imgURL, $dsc); else $ret = $linkedin->postToGroup($msg, $msgT, $options['grpID']); 
            $liPostID= 'http://www.linkedin.com/groups?gid='.$options['grpID'];
          } catch (Exception $o){ $ret="ERROR: ".print_r($o, true); }        
        } else { //echo $msg ."|". nsTrnc($msgT, 200) ."|". $urlToGo ."|". $imgURL ."|". $dsc;
          try{ if($options['liAttch']=='1') $ret = $linkedin->postShare($msg, nsTrnc($msgT, 200), str_replace('&', '&amp;', $urlToGo), $imgURL, $dsc); else $ret = $linkedin->postShare($msg); } catch (Exception $o){ $ret="ERROR:".print_r($o, true); } 
        }          
        if ($liPostID=='') $liPostID = $options['liUserInfo'];
      } 
      if (stripos($ret, 'update-url')!==false) { $liPostID = CutFromTo($ret, '<update-url>','</update-url>'); $ret = '201'; }     
      if ($ret!='201') { $badOut['Error'] .= $ret;  } else return  array('isPosted'=>'1', 'postID'=>$liPostID, 'postURL'=>$liPostID, 'pDate'=>date('Y-m-d H:i:s')); 
      return $badOut;     
   }    
}}
?>