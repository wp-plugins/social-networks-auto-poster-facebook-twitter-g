<?php    
//## NextScripts FriendFeed Connection Class
$nxs_snapAPINts[] = array('code'=>'YT', 'lcode'=>'yt', 'name'=>'YouTube');

if (!class_exists("nxs_class_SNAP_YT")) { class nxs_class_SNAP_YT {
    
    var $ntCode = 'YT';
    var $ntLCode = 'yt';     
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array();
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }    
    function doPostToNT($options, $message){ $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>'');
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['ytUName']) || trim($options['ytPass'])=='') { $badOut['Error'] = 'Not Configured'; return $badOut; }            
      $pass = substr($options['ytPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['ytPass'], 5)):$options['ytPass'];                   
      //## Format
      if (!empty($message['pText'])) $msg = $message['pText']; else $msg = nxs_doFormatMsg($options['ytMsgFormat'], $message); 
      
      $loginError = doConnectToGooglePlus2($options['ytUName'], $pass, 'YT'); if ($loginError!==false) return "BAD USER/PASS - ".$loginError; 
      
      $ret = doPostToYouTube($msg, $options['ytPageID'], $message['videoURL'], $options['ytGPPageID']); //prr($ret);
      if ($ret=='OK') $ret = array("code"=>"OK", "post_id"=>'');
      if ( (!is_array($ret)) && $ret!='OK') {  $badOut['Error'] .= 'Something went wrong - NO PID '.print_r($ret, true);  } 
        else return array('postID'=>$ret['post_id'], 'isPosted'=>1, 'postURL'=>$ret['post_id'], 'pDate'=>date('Y-m-d H:i:s'));        
      return $badOut;      
   }    
}}
?>