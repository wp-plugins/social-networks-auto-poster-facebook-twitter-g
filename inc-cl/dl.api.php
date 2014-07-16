<?php    
//## NextScripts Delicious Connection Class
$nxs_snapAPINts[] = array('code'=>'DL', 'lcode'=>'dl', 'name'=>'Delicious');

if (!class_exists("nxs_class_SNAP_DL")) { class nxs_class_SNAP_DL {
    
    var $ntCode = 'DL';
    var $ntLCode = 'dl';     
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array();
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
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
    
    function getHeaders($ref, $org='', $post=false, $aj=false){ $hdrsArr = array(); 
        $hdrsArr['Cache-Control']='max-age=0'; $hdrsArr['Connection']='keep-alive'; $hdrsArr['Referer']=$ref;
        $hdrsArr['User-Agent']='Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.22 Safari/537.36'; 
        if($post==='j') $hdrsArr['Content-Type']='application/json;charset=UTF-8'; elseif($post===true) $hdrsArr['Content-Type']='application/x-www-form-urlencoded';
        if($aj===true) $hdrsArr['X-Requested-With']='XMLHttpRequest';  if ($org!='') $hdrsArr['Origin']=$org; 
        $hdrsArr['Accept']='text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';// $hdrsArr['DNT']='1';
        if (function_exists('gzdeflate')) $hdrsArr['Accept-Encoding']='gzip,deflate,sdch'; 
        $hdrsArr['Accept-Language']='en-US,en;q=0.8'; return $hdrsArr; 
    }
    
    function doPostToNT($options, $message){ $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>'');
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['dlUName']) || trim($options['dlPass'])=='') { $badOut['Error'] = 'Not Configured'; return $badOut; }      
      $email = $options['dlUName'];  $pass = substr($options['dlPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['dlPass'], 5)):$options['dlPass'];  
      //## Format
      if (!empty($message['pText'])) $msg = $message['pText']; else $msg = nxs_doFormatMsg($options['dlMsgFormat'], $message); 
      if (!empty($message['pTitle'])) $msgT = $message['pTitle']; else $msgT = nxs_doFormatMsg($options['dlMsgTFormat'], $message); $tags = nsTrnc($message['tags'], 195, ',', '');
      
      $link = $message['url']; $desc = substr($msgT, 0, 250); $ext = substr($msg, 0, 1000);      
      $hdrsArr = $this->getHeaders('https://delicious.com','https://delicious.com',true); $flds = array('username'=>$email, 'password'=>base64_encode(strrev($pass)));
      $cnt = wp_remote_post( 'https://avosapi.delicious.com/api/v1/account/login', array( 'method' => 'POST', 'timeout' => 45, 'redirection' => 0, 'body'=>$flds, 'headers' => $hdrsArr) ); 
      if (is_nxs_error($cnt)) {  $badOut = "ERROR (Login Form): ".print_r($rep, true); return $badOut; } $rep = json_decode($cnt['body'], true); 
      
      if ($rep['status']!='success') { $badOut = "ERROR (Login): ".print_r($rep, true); return $badOut; } $ck = $cnt['cookies'];
      $flds = array('url'=>$link, 'description'=>$desc, 'tags'=>$tags, 'note'=>$ext, 'replace'=>'true', 'private'=>'false', 'share'=>'');
      $advSts = array( 'method' => 'POST', 'timeout' => 45, 'redirection' => 0, 'body'=>$flds, 'cookies' => $ck, 'headers' => $hdrsArr); //prr($advSts);
      $cnt = wp_remote_post( 'https://avosapi.delicious.com/api/v1/posts/addoredit', $advSts ); 
      if (is_nxs_error($cnt)) {  $badOut = "ERROR (Login Form): ".print_r($rep, true); return $badOut; } $rep = json_decode($cnt['body'], true); 
      if ($rep['status']!='success') { $badOut = "ERROR (Login): ".print_r($rep, true); return $badOut; } 
      return array('postID'=>md5($message['url']), 'isPosted'=>1, 'postURL'=>'https://delicious.com/link/'.md5($message['url']), 'pDate'=>date('Y-m-d H:i:s'));  
   }    
}}
?>