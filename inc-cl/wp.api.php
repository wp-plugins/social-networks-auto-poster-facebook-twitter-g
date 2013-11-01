<?php    
//## NextScripts FriendFeed Connection Class
$nxs_snapAPINts[] = array('code'=>'WP', 'lcode'=>'wp', 'name'=>'WP Based Blog');

if (!class_exists("nxs_class_SNAP_WP")) { class nxs_class_SNAP_WP {
    
    var $ntCode = 'WP';
    var $ntLCode = 'wp';     
    
    function doPost($options, $message){ if (!is_array($options)) return false; 
      foreach ($options as $ntOpts) $out[] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }    
    function nxs_getLJHeaders($up){ $hdrsArr = array(); 
      $hdrsArr['Cache-Control']='no-cache'; $hdrsArr['Connection']='keep-alive'; 
      $hdrsArr['User-Agent']='SNAP for Wordpress; Ver '.NextScripts_SNAP_Version;
      $hdrsArr['Accept']='text/html, application/xhtml+xml, */*'; $hdrsArr['DNT']='1';
      $hdrsArr['Authorization'] = 'Basic ' . base64_encode("$up");
      if (function_exists('gzdeflate')) $hdrsArr['Accept-Encoding']='gzip,deflate'; $hdrsArr['Accept-Language']='en-US,en;q=0.8'; $hdrsArr['Accept-Charset']='ISO-8859-1,utf-8;q=0.7,*;q=0.3'; return $hdrsArr;
    }
    function doPostToNT($options, $message){ $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>'');
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['wpUName']) || trim($options['wpPass'])=='') { $badOut['Error'] = 'Not Configured'; return $badOut; }            
      $pass = substr($options['wpPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['wpPass'], 5)):$options['wpPass'];      
      //## Format
      $msg = nxs_doFormatMsg($options['wpMsgFormat'], $message); $msgT = nxs_doFormatMsg($options['wpMsgTFormat'], $message);      
      if (isset($message['imageURL'])) $imgURL = trim(nxs_getImgfrOpt($message['imageURL'], $options['imgSize'])); else $imgURL = '';
           
      
      $link = urlencode($message['url']); $ext = urlencode(substr($msg, 0, 1000));
      
      //## Post   
      require_once ('apis/xmlrpc-client.php'); $nxsToWPclient = new NXS_XMLRPC_Client($options['wpURL']); $nxsToWPclient->debug = false;
      if ($imgURL!=='' && stripos($imgURL, 'http')!==false) {      
        // $handle = fopen($imgURL, "rb"); $filedata = ''; while (!feof($handle)) {$filedata .= fread($handle, 8192);} fclose($handle);
        $filedata = wp_remote_get($imgURL); if (! is_wp_error($filedata) ) $filedata = $filedata['body']; // echo "AWC?";
        $data = array('name'  => 'image-'.$message['orID'].'.jpg', 'type'  => 'image/jpg', 'bits'  => new NXS_XMLRPC_Base64($filedata), true); 
        $status = $nxsToWPclient->query('metaWeblog.newMediaObject', $message['orID'], $options['wpUName'], $pass, $data);  $imgResp = $nxsToWPclient->getResponse();  $gid = $imgResp['id'];
      } else $gid = '';
      
      $params = array(0, $options['wpUName'], $pass, array('software_version')); 
      if (!$nxsToWPclient->query('wp.getOptions', $params)) { $ret = 'Something went wrong - '.$nxsToWPclient->getErrorCode().' : '.$nxsToWPclient->getErrorMessage();} else $ret = 'OK';      
      $rwpOpt = $nxsToWPclient->getResponse();  $rwpOpt = $rwpOpt['software_version']['value']; $rwpOpt = floatval($rwpOpt); //prr($rwpOpt);prr($nxsToWPclient);
      //## MAIN Post
      if ($rwpOpt==0) { 
        $errMsg = $nxsToWPclient->getErrorMessage(); if ($errMsg!='') $ret = $errMsg; else  $ret = 'XMLRPC is not found or not active. WP admin - Settings - Writing - Enable XML-RPC'; 
      } else if ($rwpOpt<3.0)  $ret = 'XMLRPC is too OLD - '.$rwpOpt.' You need at least 3.0'; else {
       
        if ($rwpOpt>3.3){
          $nxsToWPContent = array('title'=>$msgT, 'description'=>$msg, 'post_status'=>'draft', 'mt_excerpt'=>$ext, 'mt_allow_comments'=>1, 'mt_allow_pings'=>1, 'post_type'=>'post', 'mt_keywords'=>$message['tags'], 'categories'=>$message['cats'], 'custom_fields' =>  '');
          $params = array(0, $options['wpUName'], $pass, $nxsToWPContent, true);
          if (!$nxsToWPclient->query('metaWeblog.newPost', $params)) { $ret = 'Something went wrong - '.$nxsToWPclient->getErrorCode().' : '.$nxsToWPclient->getErrorMessage();} else $ret = 'OK';
          $pid = $nxsToWPclient->getResponse();  
       
          if ($gid!='') {      
            $nxsToWPContent = array('post_thumbnail'=>$gid);  $params = array(0, $options['wpUName'], $pass, $pid, $nxsToWPContent, true);      
            if (!$nxsToWPclient->query('wp.editPost', $params)) { $ret = 'Something went wrong - '.$nxsToWPclient->getErrorCode().' : '.$nxsToWPclient->getErrorMessage();} else $ret = 'OK';
          }
          $nxsToWPContent = array('post_status'=>'publish');  $params = array(0, $options['wpUName'], $pass, $pid, $nxsToWPContent, true);      
          if (!$nxsToWPclient->query('wp.editPost', $params)) { $ret = 'Something went wrong - '.$nxsToWPclient->getErrorCode().' : '.$nxsToWPclient->getErrorMessage();} else $ret = 'OK';
        } else {
          $nxsToWPContent = array('title'=>$msgT, 'description'=>$msg, 'post_status'=>'publish', 'mt_allow_comments'=>1, 'mt_allow_pings'=>1, 'post_type'=>'post', 'mt_keywords'=>$message['tags'], 'categories'=>$message['cats'], 'custom_fields' => '');
          $params = array(0, $options['wpUName'], $pass, $nxsToWPContent, true);
          if (!$nxsToWPclient->query('metaWeblog.newPost', $params)) { $ret = 'Something went wrong - '.$nxsToWPclient->getErrorCode().' : '.$nxsToWPclient->getErrorMessage();} else $ret = 'OK';
          $pid = $nxsToWPclient->getResponse();  
        }
      }       
      if ($ret!='OK') $badOut['Error'] .= '-=ERROR=- '.print_r($ret, true); else return array('postID'=>$pid, 'isPosted'=>1, 'postURL'=>$pid, 'pDate'=>date('Y-m-d H:i:s'));
      return $badOut;      
   }    
}}
?>