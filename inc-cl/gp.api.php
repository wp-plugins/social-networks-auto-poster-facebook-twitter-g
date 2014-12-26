<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAPINts[] = array('code'=>'GP', 'lcode'=>'gp', 'name'=>'Google+');

if (!class_exists("nxs_class_SNAP_GP")) { class nxs_class_SNAP_GP {
    
    var $ntCode = 'GP';
    var $ntLCode = 'gp';     
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array();
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }
    function doPostToNT($options, $message){ $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>''); $lnk = '';
      //## Check API Lib
      // if (!function_exists('doPostToGooglePlus')) if (file_exists('apis/postToGooglePlus.php')) require_once ('apis/postToGooglePlus.php'); elseif (file_exists('/home/_shared/deSrc.php')) require_once ('/home/_shared/deSrc.php'); 
      if (!function_exists('doPostToGooglePlus')) { $badOut['Error'] = 'Google+ API Library not found'; return $badOut; }
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['gpUName']) || trim($options['gpPass'])=='') { $badOut['Error'] = 'Not Configured'; return $badOut; }
      if (empty($options['imgSize'])) $options['imgSize'] = '';
      //## Make Post      
      $gpPostType = $options['postType']; 
      if (!empty($message['pText'])) $msg = $message['pText']; else $msg = nxs_doFormatMsg($options['gpMsgFormat'], $message); // Make "message default"
      if ($gpPostType=='I' || $gpPostType=='A') { if (isset($message['imageURL'])) $imgURL = trim(nxs_getImgfrOpt($message['imageURL'], $options['imgSize'])); else $imgURL = ''; } 
            
      $email = $options['gpUName'];  $pass = substr($options['gpPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['gpPass'], 5)):$options['gpPass'];                   
      $loginError = doConnectToGooglePlus2($email, $pass);  if ($loginError!==false) {  $badOut['Error'] = print_r($loginError, true)." - BAD USER/PASS"; return $badOut; } 
      if ($gpPostType=='I') $lnk = array(); if ($gpPostType=='A') $lnk = doGetGoogleUrlInfo2($message['url']);  if (is_array($lnk) && $imgURL!='') $lnk['img'] = $imgURL; 
      if (is_array($lnk) && $imgURL=='' && $message['noImg']===true) $lnk['img'] = ''; // prr($lnk); prr($message); die();
      if ($gpPostType=='I' && (!empty($message['videoURL']))) { $lnk['video'] = $message['videoURL']; } 
      if (!empty($options['gpPageID']) && empty($options['gpCommID'])) {  $to = $options['gpPageID']; $ret = doPostToGooglePlus2($msg, $lnk, $to);} 
        elseif (!empty($options['gpCommID'])) $ret = doPostToGooglePlus2($msg, $lnk, $options['gpPageID'], $options['gpCommID'], $options['gpCCat']); else $ret = doPostToGooglePlus2($msg, $lnk); 
      if ( (!is_array($ret)) && $ret!='OK') { $badOut['Error'] = print_r($ret, true); } else { return array('isPosted'=>'1',  'postID'=>$ret['post_id'], 'postURL'=>'https://plus.google.com/'.$ret['post_id'], 'pDate'=>date('Y-m-d H:i:s')); }
      return $badOut;
    }
    
}}
?>