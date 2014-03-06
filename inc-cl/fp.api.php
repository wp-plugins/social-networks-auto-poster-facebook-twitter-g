<?php    
//## NextScripts Flipboard Connection Class

/* 
1. Options

nName - Nickname of the account [Optional] (Presentation purposes only - No affect on functionality)
rdUName - Reddit User Name
rdPass - Reddit User Passord
rdSubReddit - Name of the Sub-Reddit
postType - A or T - "Attached link" or "Text"

rdTitleFormat
rdTextFormat

2. Post Info

url
title - [up to 300 characters long] - title of the submission
text

*/
$nxs_snapAPINts[] = array('code'=>'FP', 'lcode'=>'fp', 'name'=>'Flipboard');

if (!function_exists("nxs_getFPHeaders")) {  function nxs_getFPHeaders($ref, $org='', $post=false, $aj=false){ $hdrsArr = array(); 
 $hdrsArr['Cache-Control']='max-age=0'; $hdrsArr['Connection']='keep-alive'; $hdrsArr['Referer']=$ref;
 $hdrsArr['User-Agent']='Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.22 Safari/537.36'; 
 if($post==='j') $hdrsArr['Content-Type']='application/json;charset=UTF-8'; elseif($post===true) $hdrsArr['Content-Type']='application/x-www-form-urlencoded';
 if($aj===true) $hdrsArr['X-Requested-With']='XMLHttpRequest';  if ($org!='') $hdrsArr['Origin']=$org; 
 $hdrsArr['Accept']='text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';// $hdrsArr['DNT']='1';
 if (function_exists('gzdeflate')) $hdrsArr['Accept-Encoding']='gzip,deflate,sdch'; 
 $hdrsArr['Accept-Language']='en-US,en;q=0.8'; return $hdrsArr; 
}}

if (!class_exists("nxs_class_SNAP_FP")) { class nxs_class_SNAP_FP {
    
    var $ntCode = 'FP';
    var $ntLCode = 'fp';
    
    function createFile($imgURL) {
      $remImgURL = urldecode($imgURL); $urlParced = pathinfo($remImgURL); $remImgURLFilename = $urlParced['basename']; 
      $imgData = wp_remote_get($remImgURL); if (is_wp_error($imgData)) { $badOut['Error'] = print_r($imgData, true)." - ERROR"; return $badOut; }          
      $imgData = $imgData['body'];
      $tmp=array_search('uri', @array_flip(stream_get_meta_data($GLOBALS[mt_rand()]=tmpfile())));  
      if (!is_writable($tmp)) return "Your temporary folder or file (file - ".$tmp.") is not witable. Can't upload images to Flickr";
      rename($tmp, $tmp.='.png'); register_shutdown_function(create_function('', "unlink('{$tmp}');"));       
      file_put_contents($tmp, $imgData); if (!$tmp) return 'You must specify a path to a file'; if (!file_exists($tmp)) return 'File path specified does not exist';
      if (!is_readable($tmp)) return 'File path specified is not readable';      
      //  $data['name'] = basename($tmp);
      return "@$tmp";
      
    }
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array(); // return false;
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }
    
    function doPostToNT($options, $message){ global $nxs_urlLen; $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>'');
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['uPass']) || trim($options['uPass'])=='') { $badOut['Error'] = 'Not Authorized'; return $badOut; }      
      if (empty($options['imgSize'])) $options['imgSize'] = '';
      //## Format Post
      if (!empty($message['pText'])) $text = $message['pText']; else $text = nxs_doFormatMsg($options['msgFrmt'], $message); 
      //## Make Post            
      if (isset($message['imageURL'])) $imgURL = trim(nxs_getImgfrOpt($message['imageURL'], $options['imgSize'])); else $imgURL = '';       
      //## Make Post   
      if (!empty($options['ck'])){$ck = maybe_unserialize($options['ck']); $loginError = doCheckFlipboard($ck);}
      if (empty($ck) || $loginError!==false) { $pass = substr($options['uPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['uPass'], 5)):$options['uPass'];  
          $loginInfo = doConnectToFlipboard($options['uName'], $pass);  if (!is_array($loginInfo))  {  $badOut['Error'] = print_r($loginInfo, true)." - ERROR"; return $badOut; } $ck = $loginInfo['ck']; 
      } $post = array('url'=>$message['url'], 'mgzURL'=>$options['mgzURL'], 'imgURL'=>$imgURL, 'text'=>$text );
      return doPostToFlipboard($ck, $post);            
    }      
}}
?>