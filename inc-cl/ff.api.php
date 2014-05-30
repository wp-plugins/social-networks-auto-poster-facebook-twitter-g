<?php    
//## NextScripts FriendFeed Connection Class
$nxs_snapAPINts[] =  array('code'=>'FF', 'lcode'=>'ff', 'name'=>'FriendFeed');

if (!class_exists("nxs_class_SNAP_FF")) { class nxs_class_SNAP_FF {
    
    var $ntCode = 'FF';
    var $ntLCode = 'ff';     
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array();
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }    
    function nxs_getFFHeaders($up){ $hdrsArr = array(); 
      $hdrsArr['Cache-Control']='no-cache'; $hdrsArr['Connection']='keep-alive'; 
      $hdrsArr['User-Agent']='SNAP for Wordpress; Ver 3';
      $hdrsArr['Accept']='text/html, application/xhtml+xml, */*'; $hdrsArr['DNT']='1';
      $hdrsArr['Content-Type']='application/x-www-form-urlencoded'; 
      $hdrsArr['Authorization'] = 'Basic ' . base64_encode("$up");
      if (function_exists('gzdeflate')) $hdrsArr['Accept-Encoding']='gzip,deflate'; 
      $hdrsArr['Accept-Language']='en-US,en;q=0.8'; $hdrsArr['Accept-Charset']='ISO-8859-1,utf-8;q=0.7,*;q=0.3'; return $hdrsArr;
    }
    function doPostToNT($options, $message){ $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>'');
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['ffUName']) || trim($options['ffPass'])=='') { $badOut['Error'] = 'Not Configured'; return $badOut; }      
      $dusername = $options['ffUName']; $pass = (substr($options['ffPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['ffPass'], 5)):$options['ffPass']);
      if (empty($options['imgSize'])) $options['imgSize'] = '';
      //## Format
      if (!empty($message['pText'])) $msg = $message['pText']; else $msg = nxs_doFormatMsg($options['ffMsgFormat'], $message); 
      if ($options['attchImg']=='1') { if (isset($message['imageURL'])) $imgURL = trim(nxs_getImgfrOpt($message['imageURL'], $options['imgSize'])); else $imgURL = ''; } else $imgURL = '';      
      
      $postArr = array('title'=>$msg, 'image0_link'=>'', 'room'=>($options['grpID']!=''?strtolower($options['grpID']):''), 'image0_url'=>($imgURL!=''?$imgURL:''));             
      $apicall = "http://friendfeed.com/api/share";  $hdrsArr = $this->nxs_getFFHeaders($dusername.':'.$pass); 
      $paramcall = array( 'method' => 'POST', 'timeout' => 45, 'redirection' => 0, 'body'=> $postArr,  'headers' => $hdrsArr); 
      
      $cnt = wp_remote_post( $apicall, $paramcall ); // prr(json_decode($cnt['body'], true));
      
      if(is_wp_error($cnt)) $ret = 'Something went wrong - '.print_r($cnt, true); 
        else { if (is_array($cnt)) $retInfo = json_decode($cnt['body'], true); else $retInfo = false;
        if (is_array($cnt) &&  $cnt['response']['code']=='200' && is_array($retInfo)) return array('postID'=>$retInfo['entries'][0]['id'], 'isPosted'=>1, 'postURL'=>'http://friendfeed.com/e/'.$retInfo['entries'][0]['id'], 'pDate'=>date('Y-m-d H:i:s')); 
          else { $ret = "Error: "; if ($cnt['response']['code']=='401') $ret .= " Incorrect Username/Password "; $ret .= $cnt['response']['message']; }
      } $badOut['Error'] .= $ret;  return $badOut;
   }    
}}
?>