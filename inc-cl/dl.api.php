<?php    
//## NextScripts Delicious Connection Class
$nxs_snapAPINts[] = array('code'=>'DL', 'lcode'=>'dl', 'name'=>'Delicious');

if (!class_exists("nxs_class_SNAP_DL")) { class nxs_class_SNAP_DL {
    
    var $ntCode = 'DL';
    var $ntLCode = 'dl';     
    
    function doPost($options, $message){ if (!is_array($options)) return false; 
      foreach ($options as $ntOpts) $out[] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }    
    function nxs_getDLHeaders($up){ $hdrsArr = array(); 
      $hdrsArr['Cache-Control']='no-cache'; $hdrsArr['Connection']='keep-alive'; 
      $hdrsArr['User-Agent']='SNAP for Wordpress; Ver 3';
      $hdrsArr['Accept']='text/html, application/xhtml+xml, */*'; $hdrsArr['DNT']='1';
      $hdrsArr['Authorization'] = 'Basic ' . base64_encode("$up");
      if (function_exists('gzdeflate')) $hdrsArr['Accept-Encoding']='gzip,deflate'; 
      $hdrsArr['Accept-Language']='en-US,en;q=0.8'; $hdrsArr['Accept-Charset']='ISO-8859-1,utf-8;q=0.7,*;q=0.3'; return $hdrsArr;
    }
    function doPostToNT($options, $message){ $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>'');
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['dlUName']) || trim($options['dlPass'])=='') { $badOut['Error'] = 'Not Configured'; return $badOut; }      
      $email = $options['dlUName'];  $pass = substr($options['dlPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['dlPass'], 5)):$options['dlPass'];  
      //## Format
      if (!empty($message['pText'])) $msg = $message['pText']; else $msg = nxs_doFormatMsg($options['dlMsgFormat'], $message); 
      if (!empty($message['pTitle'])) $msgT = $message['pTitle']; else $msgT = nxs_doFormatMsg($options['dlMsgTFormat'], $message); $tags = urlencode(nsTrnc($message['tags'], 195, ',', ''));    
      
      $api = "api.del.icio.us/v1"; $link = urlencode($message['url']); $desc = urlencode(substr($msgT, 0, 250)); $ext = urlencode(substr($msg, 0, 1000));            
      $apicall = "https://$api/posts/add?red=api&url=$link&description=$desc&extended=$ext&tags=$tags"; 
      $hdrsArr = $this->nxs_getDLHeaders($email.':'.$pass); $cnt = wp_remote_get( $apicall, array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr) ); // prr($cnt);      
      
      if(is_wp_error($cnt)) { $error_string = $cnt->get_error_message(); if (stripos($error_string, ' timed out')!==false) { sleep(10); 
        $cnt = wp_remote_get( $apicall, array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr) ); }
      }    
      if(is_wp_error($cnt)) {
        $badOut['Error'] .= 'Something went wrong - '."http://$email:*********@$api/posts/add?&url=$link&description=$desc&extended=$ext&tags=$tags | ERR: ".print_r($cnt, true);
      } else {      
        if (is_array($cnt) &&  stripos($cnt['body'],'code="done"')!==false) {
         return array('postID'=>md5($message['url']), 'isPosted'=>1, 'postURL'=>'https://delicious.com/link/'.md5($message['url']), 'pDate'=>date('Y-m-d H:i:s'));          
      }  elseif (is_array($cnt) &&  $cnt['body']=='<?xml version="1.0" encoding="UTF-8"?>') $ret = 'It looks like Delicious  API is Down';
        elseif (is_array($cnt) &&  stripos($cnt['body'],'item already exists')!==false) $ret = '..All good, but this link has already been bookmarked..'; 
          else { if ($cnt['response']['code']=='401') $ret = " Incorrect Username/Password "; else  $ret = 'Something went wrong - '."https://$email:*********@$api/posts/add?&url=$link&description=$desc&extended=$ext&tags=$tags | ERR: ".print_r($cnt, true); }
      }
      $badOut['Error'] .= $ret;  return $badOut;
   }    
}}
?>