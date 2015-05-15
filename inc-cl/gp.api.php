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
      
      $nt = new nxsAPI_GP(); if(!empty($options['ck'])) $nt->ck = $options['ck'];  $nt->debug = false;  $loginError = $nt->connect($email, $pass);     
      if (!$loginError){ 
         if ($gpPostType=='A') $lnk = $message['url']; elseif ($gpPostType=='I') { $lnk = array(); if ($imgURL!='') $lnk['img'] = $imgURL;  if ($imgURL=='' && $message['noImg']===true) $lnk['img'] = '';
            if (!empty($message['videoURL'])) $lnk['video'] = $message['videoURL']; 
         } $pageID = ''; $comPgID = ''; $comPGCatID = '';
         if (!empty($options['gpPageID']) && empty($options['gpCommID']))  $pageID = $options['gpPageID']; 
         if (!empty($options['gpCommID'])) {$comPgID = $options['gpCommID']; $comPGCatID = $options['gpCCat'];}
         $result = $nt -> postGP($msg, $lnk, $pageID, $comPgID, $comPGCatID);
      } else {  $badOut['Error'] = "Login/Connection Error: ". print_r($loginError, true); return $badOut; }       
      if (is_array($result) && $result['isPosted']=='1') nxs_save_glbNtwrks('gp', $options['ii'], $nt->ck, 'ck');
      return $result;
    }
    
}}
?>