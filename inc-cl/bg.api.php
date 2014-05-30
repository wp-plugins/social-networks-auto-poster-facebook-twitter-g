<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAPINts[] = array('code'=>'BG', 'lcode'=>'bg', 'name'=>'Blogger');

if (!class_exists("nxs_class_SNAP_BG")) { class nxs_class_SNAP_BG {
    
    var $ntCode = 'BG';
    var $ntLCode = 'bg';     
    
    function doPost($options, $message){ if (!is_array($options)) return false; 
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }
    
    function nsBloggerGetAuth($email, $pass) { $pass = urlencode($pass);
      $ch = curl_init("https://www.google.com/accounts/ClientLogin?Email=$email&Passwd=$pass&service=blogger&accountType=GOOGLE");    
      $headers = array(); $headers[] = 'Accept: text/html, application/xhtml+xml, */*'; 
      $headers[] = 'Connection: Keep-Alive'; $headers[] = 'Accept-Language: en-us'; 
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0)");
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,10); curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      curl_setopt($ch, CURLOPT_HEADER,0); curl_setopt($ch, CURLOPT_RETURNTRANSFER ,1);
      global $nxs_skipSSLCheck; if ($nxs_skipSSLCheck===true) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $result = curl_exec($ch); $resultArray = curl_getinfo($ch); $errmsg = curl_error($ch); 
      if (trim($errmsg)!=='' || (is_array($resultArray) && $resultArray['http_code']!='200')) return array('result'=>'Error', 'error'=>"Error: ".$resultArray['http_code']." | Invalid Login ".$errmsg);
      curl_close($ch); $arr = explode("=",$result); $token = $arr[3]; if (trim($token)=='') return false; else return $token;
    }
    function nsBloggerNewPost($auth, $blogID, $title, $text) {$text = str_ireplace('allowfullscreen','', $text); $title = utf8_decode(strip_tags($title)); 
      $text = preg_replace('/<object\b[^>]*>(.*?)<\/object>/is', "", $text);  $text = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', "", $text); $text = utf8_decode($text); 
    
      $postText = '<entry xmlns="http://www.w3.org/2005/Atom"><title type="text">'.$title.'</title><content type="xhtml">'.$text.'</content></entry>'; //prr($postText);
      $ch = curl_init("https://www.blogger.com/feeds/$blogID/posts/default"); 
      $headers = array("Content-type: application/atom+xml", "Content-Length: ".strlen($postText), "Authorization: GoogleLogin auth=".$auth, $postText);
      curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, 1);  curl_setopt($ch, CURLOPT_POST, true);  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0)");
      curl_setopt($ch, CURLOPT_HEADER,0); curl_setopt($ch, CURLOPT_RETURNTRANSFER ,1); curl_setopt($ch, CURLINFO_HEADER_OUT, true); 
      global $nxs_skipSSLCheck; if ($nxs_skipSSLCheck===true) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $result = curl_exec($ch); curl_close($ch); 
      if (stripos($result,'tag:blogger.com')!==false) { $postID = CutFromTo($result, " rel='alternate' type='text/html' href='", "'"); return array("code"=>"OK", "post_id"=>$postID); } else return array("code"=>"ERR", "error"=>$result); 
    }
    
    function doPostToNT($options, $message){ $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>''); //prr($message); prr($options);
      //## Check API Lib
      //if (!function_exists('doConnectToBlogger')) if (file_exists('apis/postToGooglePlus.php')) require_once ('apis/postToGooglePlus.php'); elseif (file_exists('/home/_shared/deSrc.php')) require_once ('/home/_shared/deSrc.php');       
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }   
      if (!isset($options['bgUName']) || trim($options['bgPass'])=='') { $badOut['Error'] = 'Not Configured'; return $badOut; }      
      //## Format
      if (!empty($message['pText'])) $msg = $message['pText']; else $msg = nxs_doFormatMsg($options['bgMsgFormat'], $message); 
      if (!empty($message['pTitle'])) $msgT = $message['pTitle']; else $msgT = nxs_doFormatMsg($options['bgMsgTFormat'], $message); 
      if ($options['bgInclTags']=='1') $tags = nsTrnc($message['tags'], 195, ',', ''); else $tags = ''; 
      //## Check/Fix HTML   
      if (class_exists('DOMDocument')) {$doc = new DOMDocument();  @$doc->loadHTML('<?xml encoding="UTF-8">' .$msg); $doc->encoding = 'UTF-8'; $msg = $doc->saveHTML(); $msg = CutFromTo($msg, '<body>', '</body>'); 
        $msg = preg_replace('/<br(.*?)\/?>/','<br$1/>',$msg);   $msg = preg_replace('/<img(.*?)\/?>/','<img$1/>',$msg);
        require ('apis/htmlNumTable.php');  if (is_array($HTML401NamedToNumeric)) { $msg = strtr($msg, $HTML401NamedToNumeric); $msgT = strtr($msgT, $HTML401NamedToNumeric); }
      }    
      $msg = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $msg); $msg = preg_replace('/<!--(.*)-->/Uis', "", $msg);  $nxshf = new NXS_HtmlFixer(); $nxshf->debug = false; $msg = $nxshf->getFixedHtml($msg);      
      $msg = str_replace("\r\n","\n", $msg); $msg = str_replace("\n\r","\n", $msg); $msg = str_replace("\r","\n", $msg); $msg = str_replace("\n","<br/>", $msg);  
      //## Make Post
      $email = $options['bgUName'];  $pass = substr($options['bgPass'], 0, 5)=='b4d7s'?nsx_doDecode(substr($options['bgPass'], 5)):$options['bgPass']; $blogID = $options['bgBlogID']; // prr($msgT); prr($msg); die();
      if (function_exists("doConnectToBlogger")) { $auth = doConnectToBlogger($email, $pass); if ($auth!==false) $ret = $auth; else $ret = doPostToBlogger($blogID, $msgT, $msg, $tags); } 
       else { $auth = $this->nsBloggerGetAuth($email, $pass); if ($auth===false) $ret = 'Incorrect Username/Password'; else { 
        if (is_array($auth))  $ret = $auth['error']; else { 
         $msgT = str_ireplace('&amp;', '&', $msgT); $msgT = utf8_encode(str_ireplace('&', '&amp;', $msgT)); $msg = utf8_encode($msg); $ret = $this->nsBloggerNewPost($auth, $blogID, $msgT, $msg);
        }
      }} 
      //## Return      
      if (is_array($ret) && $ret['post_id']!='') {
         return array('postID'=>$ret['post_id'], 'isPosted'=>1, 'postURL'=>$ret['post_id'], 'pDate'=>date('Y-m-d H:i:s'));          
      } else { $badOut['Error'] .= print_r($ret, true); 
         return $badOut;
      }
    }
}}
?>