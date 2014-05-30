<?php    
//## NextScripts FriendFeed Connection Class
$nxs_snapAPINts[] = array('code'=>'PN', 'lcode'=>'pn', 'name'=>'Pinterest');

if (!class_exists("nxs_class_SNAP_PN")) { class nxs_class_SNAP_PN {
    
    var $ntCode = 'PN';
    var $ntLCode = 'pn';     
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array();
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }    
    function doPostToNT($options, $message){ global $nxs_gCookiesArr; $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>'');
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['pnUName']) || trim($options['pnPass'])=='') { $badOut['Error'] = 'Not Configured'; return $badOut; }            
      $pass = substr($options['pnPass'], 0, 5)=='g9c1a'?nsx_doDecode(substr($options['pnPass'], 5)):$options['pnPass'];
      if (empty($options['imgSize'])) $options['imgSize'] = '';
      //## Format
      if (!empty($message['pText'])) $msg = $message['pText']; else $msg = nxs_doFormatMsg($options['pnMsgFormat'], $message); $boardID = $options['pnBoard'];  // prr($boardID); prr($_POST); die();    
      if (isset($message['imageURL'])) $imgURL = trim(nxs_getImgfrOpt($message['imageURL'], $options['imgSize'])); else $imgURL = ''; if ($imgURL=='') $badOut['Error'] .= 'NO Image.';
      $urlToGo = (!empty($message['url']))?$message['url']:'';
      //## Check for existing session      
      if (isset($options['ck'])) $nxs_gCookiesArr = maybe_unserialize( $options['ck']); $loginError = true; if (is_array($nxs_gCookiesArr)) $loginError = doCheckPinterest(); 
      if ($loginError!==false) $loginError = doConnectToPinterest($options['pnUName'], $pass);  if ($loginError!==false) { $badOut['Error'] = print_r($loginError, true)." - BAD USER/PASS"; return $badOut; } 
      if (preg_match ( '/\$(\d+\.\d+)/', $msg, $matches )) $price = $matches[0];  else $price = '';
      
      if (isset($options['cImgURL']) && $options['cImgURL']=='S' ) $urlToGo = nxs_mkShortURL($urlToGo); elseif (isset($options['cImgURL']) && $options['cImgURL']=='N' ) $urlToGo = '';
      
      $ret = doPostToPinterest($msg, $imgURL, $urlToGo, $boardID, 'TITLE WHERE IS IT?', $price, $urlToGo."/GTH/" ); if ($ret=='OK') $ret = array("code"=>"OK", "post_id"=>'');
      //prr($ret);
      if (is_array($ret) && !empty($ret['post_id'])) return array('postID'=>str_ireplace('/pin/', '', $ret['post_id']), 'isPosted'=>1, 'postURL'=>$ret['post_url'], 'pDate'=>date('Y-m-d H:i:s'));  
        else $badOut['Error'] .= 'Something went wrong - '.print_r($ret, true); 
      return $badOut;      
   }    
}}
?>