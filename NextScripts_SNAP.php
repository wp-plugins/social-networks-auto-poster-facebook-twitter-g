<?php
/*
Plugin Name: NextScripts: Social Networks Auto-Poster
Plugin URI: http://www.nextscripts.com/social-networks-auto-poster-for-wordpress
Description: This plugin automatically publishes posts from your blog to multiple accounts on Facebook, Twitter, and Google+ profiles and/or pages.
Author: Next Scripts
Version: 3.4.5
Author URI: http://www.nextscripts.com
Text Domain: nxs_snap
Copyright 2012-2014  Next Scripts, Inc
*/
define( 'NextScripts_SNAP_Version' , '3.4.5' );  


if (!class_exists('nxsAPI_GP')){ class nxsAPI_GP{ var $ck = array(); var $debug = false;
    function headers($ref, $org='', $type='GET', $aj=false){  $hdrsArr = array(); 
      $hdrsArr['Cache-Control']='max-age=0'; $hdrsArr['Connection']='keep-alive'; $hdrsArr['Referer']=$ref;
      $hdrsArr['User-Agent']='Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.62 Safari/537.36'; 
      if($type=='JSON') $hdrsArr['Content-Type']='application/json;charset=UTF-8'; elseif($type=='POST') $hdrsArr['Content-Type']='application/x-www-form-urlencoded;charset=UTF-8'; 
        elseif($type=='JS') $hdrsArr['Content-Type']='application/javascript; charset=UTF-8'; elseif($type=='PUT') $hdrsArr['Content-Type']='application/octet-stream';
      if($aj===true) $hdrsArr['X-Requested-With']='XMLHttpRequest';  if ($org!='') $hdrsArr['Origin']=$org; 
      if ($type=='GET') $hdrsArr['Accept']='text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'; else $hdrsArr['Accept']='*/*';
      if (function_exists('gzdeflate')) $hdrsArr['Accept-Encoding']='deflate,sdch'; 
      $hdrsArr['Accept-Language']='en-US,en;q=0.8'; return $hdrsArr;         
    }
    function check(){ $ck = $this->ck;  if (!empty($ck) && is_array($ck)) { } return false; }
    function connect($u,$p,$srv='GP'){ $sslverify = true; if ($this->debug) echo "[".$srv."] L to: ".$srv."<br/>\r\n";
        $err = nxsCheckSSLCurl('https://www.google.com'); if ($err!==false && $err['errNo']=='60') $sslverify = false;  
        if ($srv == 'GP') $lpURL = 'https://accounts.google.com/ServiceLogin?service=oz&continue=https://plus.google.com/?gpsrc%3Dogpy0%26tab%3DwX%26gpcaz%3Dc7578f19&hl=en-US'; 
        if ($srv == 'YT') $lpURL = 'https://accounts.google.com/ServiceLogin?service=oz&checkedDomains=youtube&checkConnection=youtube%3A271%3A1%2Cyoutube%3A69%3A1&continue=https://www.youtube.com/&hl=en-US';   
        if ($srv == 'BG') $lpURL = 'https://accounts.google.com/ServiceLogin?service=blogger&passive=1209600&continue=https://www.blogger.com/home&followup=https://www.blogger.com/home&ltmpl=start';
        $hdrsArr = $this->headers('https://accounts.google.com/'); $rep = nxs_remote_get($lpURL, array('headers' => $hdrsArr, 'httpversion' => '1.1', 'sslverify'=>$sslverify)); 
        if (is_nxs_error($rep)) return false; $ck = $rep['cookies']; $contents = $rep['body']; //if ($this->debug) prr($contents); 
        //## GET HIDDEN FIELDS
        $md = array(); $flds  = array();
        while (stripos($contents, '<input')!==false){ $inpField = trim(CutFromTo($contents,'<input', '>')); $name = trim(CutFromTo($inpField,'name="', '"'));
          if ( stripos($inpField, '"hidden"')!==false && $name!='' && !in_array($name, $md)) { $md[] = $name; $val = trim(CutFromTo($inpField,'value="', '"')); $flds[$name]= $val;}
          $contents = substr($contents, stripos($contents, '<input')+8);          
        } $flds['Email'] = $u; $flds['Passwd'] = $p;  $flds['signIn'] = 'Sign%20in'; $flds['PersistentCookie'] = 'yes'; $flds['rmShown'] = '1'; $flds['pstMsg'] = '1'; // $flds['bgresponse'] = $bg;
        //if ($srv == 'GP' || $srv == 'BG') $advSettings['cdomain']='google.com';
        //## ACTUAL LOGIN    
        $hdrsArr = $this->headers($lpURL, 'https://accounts.google.com', 'POST'); 
        $advSet = array('headers' => $hdrsArr, 'httpversion' => '1.1', 'timeout' => 45, 'redirection' => 0, 'cookies' => $ck, 'body' => $flds, 'sslverify'=>$sslverify);// prr($advSet);
        $rep = nxs_remote_post('https://accounts.google.com/ServiceLoginAuth', $advSet); if (is_nxs_error($rep)) {  $badOut = print_r($rep, true)." - ERROR 3="; return $badOut; } $ck = $rep['cookies']; //prr($rep);
        $unlockCaptchaMsg = "Your Google+ account is locked for the new applications to connect. Please follow this instructions to unlock it: <a href='http://www.nextscripts.com/support-faq/#q21' target='_blank'>http://www.nextscripts.com/support-faq/#q21</a> - Question #2.1.";
        if ($rep['response']['code']=='200' && !empty($rep['body'])) { $rep['body'] = str_ireplace('\'CREATE_CHANNEL_DIALOG_TITLE_IDV_CHALLENGE\': "Verify your identity"', "", $rep['body']);
            if (stripos($rep['body'],'class="error-msg"')!==false) return strip_tags(CutFromTo(CutFromTo($rep['body'],'class="error-msg"','/span>'), '>', '<'));
            if (stripos($rep['body'],'class="captcha-box"')!==false || stripos($rep['body'],'is that really you')!==false || stripos($rep['body'],'Verify your identity')!==false) return $unlockCaptchaMsg;
        }
        if ($rep['response']['code']=='302' && !empty($rep['headers']['location']) && stripos($rep['headers']['location'], 'ServiceLoginAuth')!==false) return 'Incorrect Username/Password ';
        if ($rep['response']['code']=='302' && !empty($rep['headers']['location']) && stripos($rep['headers']['location'], 'LoginVerification')!==false) return $unlockCaptchaMsg;
        if ($rep['response']['code']=='302' && !empty($rep['headers']['location']) && ( stripos($rep['headers']['location'], '/SmsAuth')!==false || stripos($rep['headers']['location'], '/SecondFactor')!==false)) return '<b style="color:#800000;">2-step verification is on.</b> <br/><br/> 2-step verification is not compatible with auto-posting. <br/><br/>Please see more here:<br/> <a href="http://www.nextscripts.com/blog/google-2-step-verification-and-auto-posting" target="_blank">Google+, 2-step verification and auto-posting</a><br/>'; 
        if ($rep['response']['code']=='302' && !empty($rep['headers']['location'])) { 
            if ($srv == 'BG') $rep['headers']['location'] = 'https://accounts.google.com/CheckCookie?checkedDomains=youtube&checkConnection=youtube%3A170%3A1&pstMsg=1&chtml=LoginDoneHtml&service=blogger&continue=https%3A%2F%2Fwww.blogger.com%2Fhome&gidl=CAA'; 
            if ($srv == 'YT') $rep['headers']['location'] = 'https://accounts.google.com/CheckCookie?hl=en-US&checkedDomains=youtube&checkConnection=youtube%3A271%3A1%2Cyoutube%3A69%3A1&pstMsg=1&chtml=LoginDoneHtml&service=oz&continue=https%3A%2F%2Fwww.youtube.com%2F&gidl=CAA';
            if ($srv == 'GP') $rep['headers']['location'] = 'https://accounts.google.com/CheckCookie?hl=en-US&checkedDomains=youtube&checkConnection=youtube%3A179%3A1&pstMsg=1&chtml=LoginDoneHtml&service=oz&continue=https%3A%2F%2Fplus.google.com%2F%3Fgpsrc%3Dogpy0%26tab%3DwX%26gpcaz%3Dc7578f19&gidl=CAA';           
          if ($this->debug) echo "[".$srv."] R to: ".$rep['headers']['location']."<br/>\r\n";  $hdrsArr = $this->headers($lpURL, 'https://accounts.google.com');
          $repLoc = $rep['headers']['location']; 
          $rep = nxs_remote_get($repLoc, array('headers' => $hdrsArr, 'redirection' => 0, 'httpversion' => '1.1', 'cookies' => $ck, 'sslverify'=>$sslverify));     
          if (!is_nxs_error($rep) && $srv == 'YT' && $rep['response']['code']=='302' && !empty($rep['headers']['location'])) { $repLoc = $rep['headers']['location'];             
            $rep = nxs_remote_get($repLoc, array('headers' => $hdrsArr, 'redirection' => 0, 'httpversion' => '1.1', 'cookies' => $ck, 'sslverify'=>$sslverify)); $ck = $rep['cookies'];                             
          } if (is_nxs_error($rep)) {  $badOut = print_r($rep, true)." - ERROR 4="; return $badOut; } $contents = $rep['body']; $rep['body'] = '';          
          //## BG Auth redirect          
          if ($srv != 'GP' && stripos($contents, 'meta http-equiv="refresh"')!==false) {$rURL = htmlspecialchars_decode(CutFromTo($contents,';url=','"')); 
            if ($this->debug) echo "[".$srv."] R to: ".$rURL."<br/>\r\n";  $hdrsArr = $this->headers($repLoc);// prr($hdrsArr);
            $rep = nxs_remote_get($rURL, array('headers' => $hdrsArr, 'redirection' => 0, 'httpversion' => '1.1', 'sslverify'=>$sslverify));//  prr($rep);
            if (is_nxs_error($rep)) {  $badOut = print_r($rep, true)." - ERROR 5="; return $badOut; } $ck = $rep['cookies'];
            if (!empty($rep['headers']['location'])) { $rURL = $rep['headers']['location'];
              $rep = nxs_remote_get($rURL, array('headers' => $hdrsArr, 'redirection' => 0, 'httpversion' => '1.1',  'cookies' => $ck, 'sslverify'=>$sslverify));
              if (is_nxs_error($rep)) {  $badOut = print_r($rep, true)." - ERROR 6="; return $badOut; }              
              if (!empty($rep['headers']['location'])) { $rURL = $rep['headers']['location']; 
                $rep = nxs_remote_get($rURL, array('headers' => $hdrsArr, 'redirection' => 0, 'httpversion' => '1.1',  'cookies' => $ck, 'sslverify'=>$sslverify)); 
                if (is_nxs_error($rep)) {  $badOut = print_r($rep, true)." - ERROR 7="; return $badOut; }
              } if (!empty($rep['headers']['location'])) $ck = $rep['cookies']; else $rep['cookies'] = $ck;
            } $ck = $rep['cookies'];  
          } $this->ck = $ck; return false;  
        } return 'Unexpected Error, Please contact support';  
    }
    
    function urlInfo($url){  $rnds = rndString(13); $url = urlencode($url); /* NXSIDX2 */ $sslverify = false; $ck = $this->ck; 
      $hdrsArr = $this->headers('https://plus.google.com/'); $rep = nxs_remote_get('https://plus.google.com/', array('headers' => $hdrsArr, 'httpversion' => '1.1', 'cookies' => $ck, 'sslverify'=>$sslverify)); 
      if (is_nxs_error($rep)) return false; /* if (!empty($rep['cookies'])) $ck = $rep['cookies']; */ $contents = $rep['body']; $at = CutFromTo($contents, 'csi.gstatic.com/csi","', '",');     
      $spar='f.req=%5B%22'.$url.'%22%2Cfalse%2Cfalse%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Ctrue%5D&at='.$at."&";
      $gurl = 'https://plus.google.com/u/0/_/sharebox/linkpreview/?soc-app=1&cid=0&soc-platform=1&hl=en&rt=j'; $hdrsArr = $this->headers('https://plus.google.com/', 'https://plus.google.com', 'POST', true);
      $advSet = array('headers' => $hdrsArr, 'httpversion' => '1.1', 'timeout' => 45, 'redirection' => 0, 'cookies' => $ck, 'body' => $spar, 'sslverify'=>$sslverify);//  prr($advSet);    
      $rep = nxs_remote_post($gurl, $advSet); if (is_nxs_error($rep)) {  $badOut = print_r($rep, true)." - ERROR"; return $badOut; }  $contents = $rep['body']; $json = prcGSON($contents); 
      if (version_compare(phpversion(), '5.4.0', '>=')) $arr = json_decode($json, true, 512, JSON_BIGINT_AS_STRING); 
        else  { $arr = json_decode($json, true);  if (!is_array($arr)) return; array_walk_recursive($arr,"nxs_jsonFix"); } if (!is_array($arr)) return;      
      if (!isset($arr[0]) || !is_array($arr[0])) return;  if (!empty($arr[0][1][2]) && is_array($arr[0][1][2])) $arr = $arr[0][1]; elseif (!empty($arr[0][0][2]) && is_array($arr[0][0][2])) $arr = $arr[0][0];      
      if (!isset($arr[4]) || !is_array($arr[4])) return; if (!isset($arr[4][0]) || !is_array($arr[4][0])) return; 
      $out['link'] = $arr[4][0][1]; $out['title'] = $arr[4][0][3]; $out['domain'] = $arr[4][0][4];  $out['txt'] = $arr[4][0][7];   
      if (isset($arr[4][0][2]) && trim($arr[4][0][2])!='') $out['fav'] = $arr[4][0][2]; else $out['fav'] = 'https://s2.googleusercontent.com/s2/favicons?domain='.$out['domain'];  
      if (isset($arr[4][0][6][0])) { $out['img'] = $arr[4][0][6][0][8]; $out['imgType'] = $arr[4][0][6][0][1]; } else {
        if (isset($arr[2][1][24][3])) $out['imgType'] = $arr[2][1][24][3];
        if (isset($arr[2][1][41][0])) $out['img'] = $arr[2][1][41][0][1]; elseif (isset($arr[2][1][41][1])) $out['img'] = $arr[2][1][41][1][1];
      } $out['title'] = str_replace('&#39;',"'",$out['title']); $out['txt'] = str_replace('&#39;',"'",$out['txt']);   
      $out['txt'] = html_entity_decode($out['txt'], ENT_COMPAT, 'UTF-8');  $out['title'] = html_entity_decode($out['title'], ENT_COMPAT, 'UTF-8');   
      if (isset($arr[5][0]) && is_array($arr[5][0])){$out['arr'] = $arr[5][0];} 
      if (isset($out['arr'][7])){ $liar = $out['arr'][7]; reset($liar); $liarOne = key($liar); if (!empty($liarOne)) $out['arr'][7][$liarOne][10] = array();}
      return $out;
    }
    function getCCatsGP($commPageID){ $items = '';   $sslverify = false; $ck = $this->ck; 
      $hdrsArr = $this->headers('https://plus.google.com/'); $rep = nxs_remote_get('https://plus.google.com/communities/'.$commPageID, array('headers' => $hdrsArr, 'httpversion' => '1.1', 'timeout' => 45, 'cookies' => $ck, 'sslverify'=>$sslverify)); 
      if (is_nxs_error($rep)) return false; if (!empty($rep['cookies'])) $ck = $rep['cookies']; $contents = $rep['body']; 
      $commPageID2 = '[["'.stripslashes(str_replace('\n', '', CutFromTo($contents, ',,[[["', "]\n]\n]"))); if (substr($commPageID2, -1)=='"') $commPageID2.="]]"; else $commPageID2.="]]]"; 
      $commPageID2 = str_replace('\u0026','&',$commPageID2); $commPageID2 = json_decode($commPageID2);   
      if (is_array($commPageID2)) foreach ($commPageID2 as $cpiItem) if (is_array($cpiItem)) { $val = $cpiItem[0]; $name = $cpiItem[1]; $items .= '<option value="'.$val.'">'.$name.'</option>'; }
      return $items;   
    }
    function postGP($msg, $lnk='', $pageID='', $commPageID='', $commPageCatID=''){ $rnds = rndString(13); $sslverify = false; $ck = $this->ck; $hdrsArr = $this->headers(''); //prr($lnk);// die();
      $pageID = trim($pageID); $commPageID = trim($commPageID); $ownerID = ''; $bigCode = '';  $isPostToPage = $pageID!=''; $isPostToComm = $commPageID!='';   
      if (function_exists('nxs_decodeEntitiesFull')) $msg = nxs_decodeEntitiesFull($msg); if (function_exists('nxs_html_to_utf8')) $msg = nxs_html_to_utf8($msg);
      $msg = str_replace('<br>', "_NXSZZNXS_5Cn", $msg); $msg = str_replace('<br/>', "_NXSZZNXS_5Cn", $msg); $msg = str_replace('<br />', "_NXSZZNXS_5Cn", $msg);     
      $msg = str_replace("\r\n", "\n", $msg); $msg = str_replace("\n\r", "\n", $msg); $msg = str_replace("\r", "\n", $msg); $msg = str_replace("\n", "_NXSZZNXS_5Cn", $msg);  $msg = str_replace('"', '\"', $msg); 
      $msg = urlencode(strip_tags($msg)); $msg = str_replace("_NXSZZNXS_5Cn", "%5Cn", $msg);  
      $msg = str_replace('+', '%20', $msg); $msg = str_replace('%0A%0A', '%20', $msg); $msg = str_replace('%0A', '', $msg); $msg = str_replace('%0D', '%5C', $msg);
      if (!empty($lnk) && !is_array($lnk)) $lnk = $this->urlInfo($lnk);
      if ($lnk=='') $lnk = array('img'=>'', 'link'=>'', 'fav'=>'', 'domain'=>'', 'title'=>'', 'txt'=>'');
      if (!isset($lnk['link']) && !empty($lnk['img'])) { $hdrsArr = $this->headers(''); unset($hdrsArr['Connection']); $rep = nxs_remote_get($lnk['img'], array('headers' => $hdrsArr, 'httpversion' => '1.1', 'timeout' => 45, 'sslverify'=>$sslverify)); //prr($rep);
        if (is_nxs_error($rep)) $lnk['img']=''; elseif ($rep['response']['code']=='200' && !empty($rep['headers']['content-type']) && stripos($rep['headers']['content-type'],'text/html')===false) {    
          if (!empty($rep['headers']['content-length']))  $imgdSize = $rep['headers']['content-length'];
          if ((empty($imgdSize) || $imgdSize == '-1') && !empty($rep['headers']['size_download'])) $imgdSize = $rep['headers']['size_download'];
          if ((empty($imgdSize) || $imgdSize == '-1')){ $ch = curl_init($lnk['img']); curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); curl_setopt($ch, CURLOPT_HEADER, TRUE); curl_setopt($ch, CURLOPT_NOBODY, TRUE);
            $data = curl_exec($ch);  $imgdSize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD); curl_close($ch);  
          } 
          if ((empty($imgdSize) || $imgdSize == '-1')) $imgdSize =  strlen($rep['body']);
          $urlParced = pathinfo($lnk['img']); $remImgURL = $lnk['img']; $remImgURLFilename = nxs_mkImgNm(nxs_clFN($urlParced['basename']), $rep['headers']['content-type']);  $imgData = $rep['body'];        
        } else $lnk['img']=''; 
      }
      if (isset($lnk['img'])) $lnk['img'] = urlencode($lnk['img']); if (isset($lnk['link'])) $lnk['link'] = urlencode($lnk['link']); 
      if (isset($lnk['fav'])) $lnk['fav'] = urlencode($lnk['fav']); if (isset($lnk['domain'])) $lnk['domain'] = urlencode($lnk['domain']);      
      if (isset($lnk['title'])) { $lnk['title'] = (str_replace(Array("\n", "\r"), ' ', $lnk['title']));  $lnk['title'] = rawurlencode(addslashes($lnk['title'])); }    
      if (isset($lnk['txt'])) { $lnk['txt'] = (str_replace(Array("\n", "\r"), ' ', $lnk['txt'])); $lnk['txt'] = rawurlencode( addslashes($lnk['txt'])); }
      $refPage = 'https://plus.google.com/b/'.$pageID.'/'; $rndReqID = rand(1203718, 647379); $rndSpamID = rand(4, 52);
      if ($commPageID!='') { //## Posting to Community      
        if ($pageID!='') $pgIDT = 'u/0/b/'.$pageID.'/'; else $pgIDT = '';
        $gpp = 'https://plus.google.com/'.$pgIDT.'_/sharebox/post/?spam='.$rndSpamID.'&_reqid='.$rndReqID.'&rt=j';            
        $rep = nxs_remote_get('https://plus.google.com/communities/'.$commPageID, array('headers' => $hdrsArr, 'httpversion' => '1.1', 'timeout' => 45, 'cookies' => $ck, 'sslverify'=>$sslverify)); 
        if (is_nxs_error($rep)) {  $badOut = print_r($rep, true)." - ERROR commPageID"; return $badOut; } /* if (!empty($rep['cookies'])) $ck = $rep['cookies']; */ $contents = $rep['body'];
        if (trim($commPageCatID)!='') $commPageID2 = $commPageCatID; else {$commPageID2 = CutFromTo($contents, "AF_initDataCallback({key: '60',", '</script>'); $commPageID2 = CutFromTo($commPageID2, ',,[[["', '"'); }
      } elseif ($pageID!='') { //## Posting to Page
        $gpp = 'https://plus.google.com/b/'.$pageID.'/_/sharebox/post/?spam='.$rndSpamID.'&_reqid='.$rndReqID.'&rt=j';    
        $rep = nxs_remote_get($refPage, array('headers' => $hdrsArr, 'httpversion' => '1.1', 'timeout' => 45, 'cookies' => $ck, 'sslverify'=>$sslverify)); 
        if (is_nxs_error($rep)) {  $badOut = print_r($rep, true)." - ERROR pageID"; return $badOut; } /* if (!empty($rep['cookies'])) $ck = $rep['cookies']; */ $contents = $rep['body'];
      } else { //## Posting to Profile      
        $gpp = 'https://plus.google.com/u/0/_/sharebox/post/?spam='.$rndSpamID.'&soc-app=1&cid=0&soc-platform=1&hl=en&rt=j'; 
        
        $gpp = 'https://plus.google.com/_/sharebox/post/?spam='.$rndSpamID.'&soc-app=1&cid=0&soc-platform=1&hl=pt_BR&ozv=es_oz_20141201.09_p0&rt=j';

        
        $rep = nxs_remote_get('https://plus.google.com/', array('headers' => $hdrsArr, 'httpversion' => '1.1', 'timeout' => 45, 'cookies' => $ck, 'sslverify'=>$sslverify)); 
        if (is_nxs_error($rep)) {  $badOut = print_r($rep, true)." - ERROR Main Page"; return $badOut; } /* if (!empty($rep['cookies'])) $ck = $rep['cookies']; */ $contents = $rep['body'];
        $pageID = CutFromTo($contents, "key: '2'", "]"); /* $pageID = CutFromTo($pageID, 'https://plus.google.com/', '"'); */ $pageID = CutFromTo($pageID, 'data:["', '"');  $refPage = 'https://plus.google.com/'; 
        $refPage = 'https://plus.google.com/_/scs/apps-static/_/js/k=oz.home.en.JYkOx2--Oes.O';     
        //unset($nxs_gCookiesArr['GAPS']); unset($nxs_gCookiesArr['GALX']); unset($nxs_gCookiesArr['RMME']); unset($nxs_gCookiesArr['LSID']);  // We migh still need it ?????
      } // echo $lnk['txt'];         
      if ($rep['response']['code']=='400') return "Invalid Sharebox Page. Something is wrong, please contact support";
      if (stripos($contents,'csi.gstatic.com/csi","')!==false) $at = CutFromTo($contents, 'csi.gstatic.com/csi","', '",'); else {        
        $rep = nxs_remote_get('https://plus.google.com/', array('headers' => $hdrsArr, 'httpversion' => '1.1', 'timeout' => 45, 'cookies' => $ck, 'sslverify'=>$sslverify)); 
        if (is_nxs_error($rep)) {  $badOut = print_r($rep, true)." - ERROR CSI"; return $badOut; } /* if (!empty($rep['cookies'])) $ck = $rep['cookies']; */ $contents = $rep['body']; // prr($rep);
        if (stripos($contents,'csi.gstatic.com/csi","')!==false) $at = CutFromTo($contents, 'csi.gstatic.com/csi","', '",');  else return "Error (NXS): Lost Login info. Please contact support";
      } // prr($lnk);
      //## URL     
      if (!isset($lnk['txt'])) $lnk['txt'] = '';     $txttxt = $lnk['txt'];  $txtStxt = str_replace('%5C', '%5C%5C%5C%5C%5C%5C%5C', $lnk['txt']);
      if ($isPostToComm) $proOrCommTxt = "%5B%22".$commPageID."%22%2C%22".$commPageID2."%22%5D%5D%2C%5B%5B%5Bnull%2Cnull%2Cnull%2C%5B%22".$commPageID."%22%5D%5D%5D"; else $proOrCommTxt = "%5D%2C%5B%5B%5Bnull%2Cnull%2C1%5D%5D%2Cnull";    //  prr($lnk);  
      if (!empty($lnk['link']) && isset($lnk['arr']) ) { 
        $urlInfo = urlencode(str_replace('\/', '/', str_replace('##-KXKZK-##', '\""', str_replace('""', 'null', str_replace('\""', '##-KXKZK-##', json_encode($lnk['arr']))))));
        $spar="f.req=%5B%22".$msg."%22%2C%22oz%3A".$pageID.".".$rnds.".0%22%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Ctrue%2C%5B%5D%2Cfalse%2Cnull%2Cnull%2C%5B%5D%2Cnull%2Cfalse%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cfalse%2Cfalse%2Cfalse%2Cnull%2Cnull%2Cnull%2Cnull%2C".$urlInfo."%2Cnull%2C%5B".$proOrCommTxt."%5D%2Cnull%2Cnull%2C2%2Cnull%2Cnull%2Cnull%2C%22!".$bigCode."%22%2Cnull%2Cnull%2Cnull%2C%5B%5D%2C%5B%5Btrue%5D%5D%2Cnull%2C%5B%5D%5D&at=".$at."&";
        
      }
      //## Video - was here, but now video works like link. So link could be used. 
      //## Image
      elseif(!empty($lnk['img']) && !empty($imgData)) { $pgAddFlds = '';
       //if($isPostToPage) $pgAddFlds = '{"inlined":{"name":"effective_id","content":"'.$pageID.'","contentType":"text/plain"}},{"inlined":{"name":"owner_name","content":"'.$pageID.'","contentType":"text/plain"}},'; else $pgAddFlds = '';
       if ($isPostToComm) $proOrCommTxt = "%5B%22".$commPageID."%22%2C%22".$commPageID2."%22%5D%5D%2C%5B%5B%5Bnull%2Cnull%2Cnull%2C%5B%22".$commPageID."%22%5D%5D%5D"; else $proOrCommTxt = "%5D%2C%5B%5B%5Bnull%2Cnull%2C1%5D%5D%2Cnull";        
       //if (!$isPostToComm) $pgAddFlds = '{"inlined":{"name":"effective_id","content":"'.$pageID.'","contentType":"text/plain"}},{"inlined":{"name":"owner_name","content":"'.$pageID.'","contentType":"text/plain"}},'; else $pgAddFlds = '';
       $iflds = '{"protocolVersion":"0.8","createSessionRequest":{"fields":[{"external":{"name":"file","filename":"'.$remImgURLFilename.'","put":{},"size":'.$imgdSize.'}},{"inlined":{"name":"use_upload_size_pref","content":"true","contentType":"text/plain"}},{"inlined":{"name":"batchid","content":"1389803229361","contentType":"text/plain"}},{"inlined":{"name":"client","content":"sharebox","contentType":"text/plain"}},{"inlined":{"name":"disable_asbe_notification","content":"true","contentType":"text/plain"}},{"inlined":{"name":"album_mode","content":"temporary","contentType":"text/plain"}},'.$pgAddFlds.'{"inlined":{"name":"album_abs_position","content":"0","contentType":"text/plain"}}]}}';
              
       $hdrsArr = $this->headers('', 'https://plus.google.com', 'POST', true); $hdrsArr['X-GUploader-Client-Info']='mechanism=scotty xhr resumable; clientVersion=58505203'; 
       $advSet = array('headers' => $hdrsArr, 'httpversion' => '1.1', 'timeout' => 45, 'redirection' => 0, 'cookies' => $ck, 'body' => $iflds, 'sslverify'=>$sslverify);// prr($advSet);
       $imgReqCnt = nxs_remote_post('https://plus.google.com/_/upload/photos/resumable?authuser=0', $advSet); if (is_nxs_error($imgReqCnt)) {  $badOut = print_r($imgReqCnt, true)." - ERROR IMG"; return $badOut; } 
       //prr($imgReqCnt);
       $gUplURL = str_replace('\u0026', '&', CutFromTo($imgReqCnt['body'], 'putInfo":{"url":"', '"'));  $gUplID = CutFromTo($imgReqCnt['body'], 'upload_id":"', '"');      
       
       $hdrsArr = $this->headers('', 'https://plus.google.com', 'PUT', true); $hdrsArr['X-GUploader-No-308']='yes'; $hdrsArr['X-HTTP-Method-Override']='PUT'; 
       $hdrsArr['Expect']=''; $hdrsArr['Content-Type']='application/octet-stream'; 
       $advSet = array('headers' => $hdrsArr, 'httpversion' => '1.1', 'timeout' => 45, 'redirection' => 0, 'cookies' => $ck, 'body' => $imgData, 'sslverify'=>$sslverify);// prr($advSet);
       $imgUplCnt = nxs_remote_post($gUplURL, $advSet); prr($imgUplCnt); if (is_nxs_error($imgUplCnt)) {  $badOut = print_r($imgUplCnt, true)." - ERROR IMG Upl (Upl URL: ".$gUplURL.", IMG URL: ".urldecode($lnk['img']).", FileName: ".$remImgURLFilename.", FIlesize: ".$imgdSize.")"; return $badOut; } 
       $imgUplCnt = json_decode($imgUplCnt['body'], true);   if (empty($imgUplCnt)) return "Can't upload image: ".$remImgURL;
       if (is_array($imgUplCnt) && isset($imgUplCnt['errorMessage']) && is_array($imgUplCnt['errorMessage']) ) return "Error (500): ".print_r($imgUplCnt['errorMessage'], true);     
       $infoArray = $imgUplCnt['sessionStatus']['additionalInfo']['uploader_service.GoogleRupioAdditionalInfo']['completionInfo']['customerSpecificInfo'];     
       $albumID = $infoArray['albumid']; $photoid =  $infoArray['photoid']; // $albumID = "5969185467353784753";
       $imgUrl = urlencode($infoArray['url']); $imgTitie = $infoArray['title'];          
       $imgUrlX = str_ireplace('https:', '', $infoArray['url']); $imgUrlX = str_ireplace('//lh4.', '//lh3.', $imgUrlX); $imgUrlX = urlencode(str_ireplace('http:', '', $imgUrlX));
       $width = $infoArray['width']; $height = $infoArray['height']; $userID = $infoArray['username'];      
       $intID = $infoArray['albumPageUrl'];  $intID = str_replace('https://picasaweb.google.com/','', $intID);  $intID = str_replace($userID,'', $intID); $intID = str_replace('/','', $intID); // prr($infoArray);
       
     //  $spar="f.req=%5B%22".$msg."%22%2C%22oz%3A".$pageID.".".$rnds.".4%22%2Cnull%2Cnull%2Cnull%2Cnull%2C%22%5B%5D%22%2Cnull%2Cnull%2Ctrue%2C%5B%5D%2Cfalse%2Cnull%2Cnull%2C%5B%5D%2Cnull%2Cfalse%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cfalse%2Cfalse%2Cfalse%2Cnull%2Cnull%2Cnull%2Cnull%2C%5B%5B344%2C339%2C338%2C336%2C335%5D%2Cnull%2Cnull%2Cnull%2C%5B%7B%2239387941%22%3A%5Btrue%2Cfalse%5D%7D%5D%2Cnull%2Cnull%2C%7B%2240655821%22%3A%5B%22https%3A%2F%2Fplus.google.com%2Fphotos%2F".$userID."%2Falbums%2F".$albumID."%2F".$photoid."%22%2C%22".$imgUrlX."%22%2C%22".$imgTitie."%22%2C%22%22%2Cnull%2Cnull%2Cnull%2C%5B%5D%2Cnull%2Cnull%2C%5B%5D%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2C%22".$width."%22%2C%22".$height."%22%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2C%22".$userID."%22%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2C%22".$albumID."%22%2C%22".$photoid."%22%2C%22albumid%3D".$albumID."%26photoid%3D".$photoid."%22%2C1%2C%5B%5D%2Cnull%2Cnull%2Cnull%2Cnull%2C%5B%5D%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2C%5B%5D%5D%7D%5D%2Cnull%2C%5B".$proOrCommTxt."%5D%2Cnull%2Cnull%2C2%2Cnull%2Cnull%2Cnull%2C%22!".$bigCode."%22%2Cnull%2Cnull%2Cnull%2C%5B%22updates%22%5D%2C%5B%5Btrue%5D%5D%2Cnull%2C%5B%5D%5D&at=".$at."&";
       
     $sprJSN = urlencode('["XYXAAXAAXYX","'.$pageID.'.'.$rnds.'.0",null,null,null,null,null,null,null,true,[],false,null,null,[],null,false,null,null,null,null,null,null,null,null,null,null,false,false,false,null,null,null,null,[[344,339,338,336,335],null,null,null,[{"39387941":[true,false]}],null,null,{"40655821":["https://plus.google.com/photos/'.$userID.'/albums/'.$albumID.'/'.$photoid.'","'.urldecode($imgUrlX).'","'.urldecode($imgTitie).'","",null,null,null,[],null,null,[],null,null,null,null,null,null,null,"'.$width.'","'.$height.'",null,null,null,null,null,null,"'.$userID.'",null,null,null,null,null,null,null,null,null,null,"'.$albumID.'","'.$photoid.'","albumid='.$albumID.'&photoid='.$photoid.'",1,[],null,null,null,null,[],null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,[]]}],null,[XXXAAXAAXXX],null,null,2,null,null,null,"!'.$bigCode.'",null,null,null,["updates"],[[true]],null,[]]');     
     $spar="f.req=".str_replace('XYXAAXAAXYX',$msg, str_replace('XXXAAXAAXXX',$proOrCommTxt,$sprJSN))."&at=".$at."&";
    }
    //## Just Message    
    else $spar="f.req=%5B%22".$msg."%22%2C%22oz%3A".$pageID.".".$rnds.".6%22%2Cnull%2Cnull%2Cnull%2Cnull%2C%22%5B%5D%22%2Cnull%2Cnull%2Ctrue%2C%5B%5D%2Cfalse%2Cnull%2Cnull%2C%5B%5D%2Cnull%2Cfalse%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cfalse%2Cfalse%2Cfalse%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2Cnull%2C%5B".$proOrCommTxt."%5D%2Cnull%2Cnull%2C2%2Cnull%2Cnull%2Cnull%2C%22!".$bigCode."%22%2Cnull%2Cnull%2Cnull%2C%5B%5D%2C%5B%5Btrue%5D%5D%2Cnull%2C%5B%5D%5D&at=".$at."&";    
    //## POST
    $spar = str_ireplace('+','%20',$spar); $spar = str_ireplace(':','%3A',$spar);  $hdrsArr = $this->headers('https://plus.google.com', 'https://plus.google.com', 'POST', false); $hdrsArr['X-Same-Domain']='1'; 
    $advSet = array('headers' => $hdrsArr, 'httpversion' => '1.1', 'timeout' => 45, 'redirection' => 0, 'cookies' => $ck, 'body' => $spar, 'sslverify'=>$sslverify);
    $rep = nxs_remote_post($gpp, $advSet); if (is_nxs_error($rep)) {  $badOut = print_r($rep, true)." - ERROR POST"; return $badOut; }  $contents = $rep['body']; prr($gpp);  prr($advSet);    prr($rep);
        
    if ($rep['response']['code']=='403') return "Error: You are not authorized to publish to this page. Are you sure this is even a page? (".$pageID.")";
    if ($rep['response']['code']=='404') return "Error: Page you are posting is not found.<br/><br/> If you have entered your page ID as 117008619877691455570/117008619877691455570, please remove the second copy. It should be one number only - 117008619877691455570";
    if ($rep['response']['code']=='400') return "Error (400): Something is wrong, please contact support";
    if ($rep['response']['code']=='500') return "Error (500): Something is wrong, please contact support";
    if ($rep['response']['code']=='200') { $ret = $rep['body']; $remTxt = CutFromTo($ret,'"{\"','}"'); $ret = str_replace($remTxt, '', $ret); $ret = prcGSON($ret);  $ret = json_decode($ret, true); 
      if (!empty($ret[0][1][1]) && is_array($ret[0][1][1]) && !empty($ret[0][1][1][0][0][21])) $ret = $ret[0][1][1][0][0][21]; 
        elseif (!empty($ret[0][0][1]) && is_array($ret[0][0][1]) && !empty($ret[0][0][1][0][0][21])) $ret = $ret[0][0][1][0][0][21]; 
      return array('isPosted'=>'1', 'postID'=>$ret, 'postURL'=>'https://plus.google.com/'.$ret, 'pDate'=>date('Y-m-d H:i:s'));
    }
    return print_r($contents, true);         
    }
 
    function postBG($blogID, $title, $msg, $tags=''){ $sslverify = false; $rnds = rndString(35); $blogID = trim($blogID); $ck = $this->ck; 
      $gpp = "https://www.blogger.com/blogger.g?blogID=".$blogID; $refPage = "https://www.blogger.com/home";
      $hdrsArr = $this->headers($refPage); $rep = nxs_remote_get($gpp, array('headers' => $hdrsArr, 'httpversion' => '1.1', 'cookies' => $ck, 'sslverify'=>$sslverify)); //prr($ck); prr($rep);// die();
      if (is_nxs_error($rep)) return false; /*if (!empty($rep['cookies'])) $ck = $rep['cookies']; */ $contents = $rep['body']; if ( stripos($contents, 'Error 404')!==false) return "Error: Invalid Blog ID - Blog with ID ".$blogID." Not Found";
      $jjs = CutFromTo($contents, 'BloggerClientFlags=','_layoutOnLoadHandler'); $j69 = ''; // prr($jjs); //  prr($contents); echo "\r\n"; echo "\r\n";    
      for ($i = 54; $i <= 99; $i++) { if ($j69=='' && strpos($jjs, $i.':"')!==false){ $j69 = CutFromTo($jjs, $i.':"','"'); 
        if (strpos($j69, ':')===false || (strpos($j69, '/')!==false) || (strpos($j69, ' ')!==false) || (strpos($j69, '\\')!==false)) $j69 = '';}
      } $gpp = "https://www.blogger.com/blogger_rpc?blogID=".$blogID; $refPage = "https://www.blogger.com/blogger.g?blogID=".$blogID;
      $spar = '{"method":"editPost","params":{"1":1,"2":"","3":"","5":0,"6":0,"7":1,"8":3,"9":0,"10":2,"11":1,"13":0,"14":{"6":""},"15":"en","16":0,"17":{"1":'.date("Y").',"2":'.date("n").',"3":'.date("j").',"4":'.date("G").',"5":'.date("i").'},"20":0,"21":"","22":{"1":1,"2":{"1":0,"2":0,"3":0,"4":0,"5":0,"6":0,"7":0,"8":0,"9":0,"10":"0"}},"23":1},"xsrf":"'.$j69.'"}';      
      $hdrsArr = $this->headers($refPage, 'https://www.blogger.com', 'JS', false); 
      $hdrsArr['X-GWT-Module-Base']='https://www.blogger.com/static/v1/gwt/'; $hdrsArr['X-GWT-Permutation']='906B796BACD31B64BA497BEE3824B344';      
      $advSet = array('headers' => $hdrsArr, 'httpversion' => '1.1', 'timeout' => 45, 'redirection' => 0, 'cookies' => $ck, 'body' => $spar, 'sslverify'=>$sslverify); // prr($advSet);    
      $rep = nxs_remote_post($gpp, $advSet); if (is_nxs_error($rep)) {  $badOut = print_r($rep, true)." - ERROR BG"; return $badOut; }  $contents = $rep['body']; //  prr($rep);   
      $newpostID = CutFromTo($contents, '"result":[null,"', '"');  
      if ($tags!='') $pTags = '["'.$tags.'"]'; else $pTags = ''; $pTags = str_replace('!','',$pTags); $pTags = str_replace('.','',$pTags);
      if (class_exists('DOMDocument')) { $doc = new DOMDocument();  @$doc->loadXML("<QAZX>".$msg."</QAZX>"); $styles = $doc->getElementsByTagName('style');
        if ($styles->length>0) {  foreach ($styles as $style)  $style->nodeValue = str_ireplace("<br/>", "", $style->nodeValue);
          $msg = $doc->saveXML($doc->documentElement, LIBXML_NOEMPTYTAG); $msg = str_ireplace("<QAZX>", "", str_ireplace("</QAZX>", "", $msg)); 
        }
      } $msg = str_replace("'",'"',$msg); $msg = addslashes($msg); $msg = str_replace("\r\n","\n",$msg); $msg = str_replace("\n\r","\n",$msg); $msg = str_replace("\r","\n",$msg); $msg = str_replace("\n",'\n',$msg);  
      $title = strip_tags($title); $title = str_replace("'",'"',$title); $title = addslashes($title); $title = str_replace("\r\n","\n",$title); 
      $title = str_replace("\n\r","\n",$title); $title = str_replace("\r","\n",$title); $title = str_replace("\n",'\n',$title); //echo "~~~~~";  prr($title);
      $spar = '{"method":"editPost","params":{"1":1,"2":"'.$title.'","3":"'.$msg.'","4":"'.$newpostID.'","5":0,"6":0,"7":1,"8":3,"9":0,"10":2,"11":2,'.($pTags!=''?'"12":'.$pTags.',':'').'"13":0,"14":{"6":""},"15":"en","16":0,"17":{"1":'.date("Y").',"2":'.date("n").',"3":'.date("j").',"4":'.date("G").',"5":'.date("i").'},"20":0,"21":"","22":{"1":1,"2":{"1":0,"2":0,"3":0,"4":0,"5":0,"6":0,"7":0,"8":0,"9":0,"10":"0"}},"23":1},"xsrf":"'.$j69.'"}';    
      
      $advSet = array('headers' => $hdrsArr, 'httpversion' => '1.1', 'timeout' => 45, 'redirection' => 0, 'cookies' => $ck, 'body' => $spar, 'sslverify'=>$sslverify); //prr($advSet);    
      $rep = nxs_remote_post($gpp, $advSet); if (is_nxs_error($rep)) {  $badOut = print_r($rep, true)." - ERROR BG2"; return $badOut; }  $contents = $rep['body'];
      
      $retJ = json_decode($contents, true); if (is_array($retJ) && !empty($retJ['result']) && is_array($retJ['result']) ) $postID = $retJ['result'][6]; else $postID = '';
      if ( stripos($contents, '"error":')!==false) { return "Error: ".print_r($contents, true); }
      if ($rep['response']['code']=='200') return array('isPosted'=>'1', 'postID'=>$postID, 'postURL'=>$postID, 'pDate'=>date('Y-m-d H:i:s')); else return print_r($contents, true);        
    }    
    function postYT($msg, $ytUrl, $vURL = '', $ytGPPageID='') { $ck = $this->ck; $sslverify = false; 
      $ytUrl = str_ireplace('/feed','',$ytUrl); if (substr($ytUrl, -1)=='/') $ytUrl = substr($ytUrl, 0, -1); $ytUrl .= '/feed'; $hdrsArr = $this->headers('http://www.youtube.com/');
      if ($ytGPPageID!=''){ $pgURL = 'https://www.youtube.com/signin?authuser=0&action_handle_signin=true&pageid='.$ytGPPageID;      if ($this->debug) echo "[YT] G SW to page: ".$ytGPPageID."<br/>\r\n";
        $rep = nxs_remote_get($pgURL, array('headers' => $hdrsArr, 'httpversion' => '1.1', 'redirection' => 0, 'cookies' => $ck, 'sslverify'=>$sslverify)); if (is_nxs_error($rep)) return "ERROR: ".print_r($rep, true);
        if (!empty($rep['cookies'])) foreach ($rep['cookies'] as $ccN) { $fdn = false; foreach ($ck as $ci=>$cc) if ($ccN->name == $cc->name) { $fdn = true; $ck[$ci] = $ccN;  } if (!$fdn) $ck[] = $ccN; }       
      } $rep = nxs_remote_get($ytUrl, array('headers' => $hdrsArr, 'httpversion' => '1.1', 'redirection' => 0, 'cookies' => $ck, 'sslverify'=>$sslverify)); if (is_nxs_error($rep)) return "ERROR: ".print_r($rep, true);
      //## Merge CK
      if (!empty($rep['cookies'])) foreach ($rep['cookies'] as $ccN) { $fdn = false; foreach ($ck as $ci=>$cc) if ($ccN->name == $cc->name) { $fdn = true; $ck[$ci] = $ccN;  } if (!$fdn) $ck[] = $ccN; }      
      $contents = $rep['body']; $gpPageMsg = "Either BAD YouTube USER/PASS or you are trying to post from the wrong account/page. Make sure you have Google+ page ID if your YouTube account belongs to the page.";
      $actFormCode = 'channel_ajax'; if (stripos($contents, 'action="/c4_feed_ajax?')!==false) $actFormCode = 'c4_feed_ajax';
      if (stripos($contents, 'action="/'.$actFormCode.'?')) $frmData = CutFromTo($contents, 'action="/'.$actFormCode.'?', '</form>'); else { 
        if (stripos($contents, 'property="og:url"')) {  $ytUrl = CutFromTo($contents, 'property="og:url" content="', '"').'/feed'; 
          $rep = nxs_remote_get($ytUrl, array('headers' => $hdrsArr, 'httpversion' => '1.1', 'cookies' => $ck, 'sslverify'=>$sslverify)); if (is_nxs_error($rep)) return "ERROR: ".print_r($rep, true); if (!empty($rep['cookies'])) $ck = $rep['cookies'];  $contents = $rep['body'];        
          if (stripos($contents, 'action="/'.$actFormCode.'?')) $frmData = CutFromTo($contents, 'action="/'.$actFormCode.'?', '</form>'); else return 'OG - Form not found. - '. $gpPageMsg;
        } else { $eMsg = "No Form/No OG - ". $gpPageMsg; return $eMsg; }
      }      
      $md = array(); $flds = array(); if ($vURL!='' && stripos($vURL, 'http')===false) $vURL = 'https://www.youtube.com/watch?v='.$vURL; $msg = strip_tags($msg); $msg = nsTrnc($msg, 500);
      while (stripos($frmData, '"hidden"')!==false){$frmData = substr($frmData, stripos($frmData, '"hidden"')+8); $name = trim(CutFromTo($frmData,'name="', '"'));
        if (!in_array($name, $md)) {$md[] = $name; $val = trim(CutFromTo($frmData,'value="', '"')); $flds[$name]= $val;}
      } $flds['message'] = $msg; $flds['video_url'] = $vURL; // prr($flds);
      $ytGPPageID = 'https://www.youtube.com/channel/'.$ytGPPageID; $hdrsArr = $this->headers($ytGPPageID, 'https://www.youtube.com/', 'POST', false); 
      $hdrsArr['X-YouTube-Page-CL'] = '67741289'; $hdrsArr['X-YouTube-Page-Timestamp'] = date("D M j H:i:s Y", time()-54000)." (".time().")"; //'Thu May 22 00:31:51 2014 (1400743911)';
      $advSet = array('headers' => $hdrsArr, 'httpversion' => '1.1', 'timeout' => 45, 'redirection' => 0, 'cookies' => $ck, 'body' => $flds, 'sslverify'=>$sslverify); //prr($advSet);
      $rep = nxs_remote_post('https://www.youtube.com/'.$actFormCode.'?action_add_bulletin=1', $advSet); 
      if (is_nxs_error($rep)) {  $badOut = print_r($rep, true)." - ERROR YT"; return $badOut; }  $contents = $rep['body'];// prr('https://www.youtube.com/'.$actFormCode.'?action_add_bulletin=1'); prr($rep);
              
      if ($rep['response']['code']=='200' && $contents = '{"code": "SUCCESS"}') return array("isPosted"=>"1", "postID"=>'', 'postURL'=>'', 'pDate'=>date('Y-m-d H:i:s')); else return $rep['response']['code']."|".$contents;     
    }              
}}



$nxs_mLimit = ini_get('memory_limit'); if (strpos($nxs_mLimit, 'G')) {$nxs_mLimit = (int)$nxs_mLimit * 1024;} else {$nxs_mLimit = (int)$nxs_mLimit;}
  if ($nxs_mLimit>0 && $nxs_mLimit<64) { add_filter('plugin_action_links','ns_add_nomem_link', 10, 2 );
if (!function_exists("ns_add_nomem_link")) { function ns_add_nomem_link($links, $file) { global $nxs_mLimit; static $this_plugin; if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
  if ($file == $this_plugin){ $settings_link = '<b style="color:red;">Not Enough Memory allowed for PHP.</b> <br/> You have '.$nxs_mLimit.' MB. You need at least 64MB'; array_unshift($links, $settings_link);} return $links;}}
} else {
    
require_once "nxs_functions.php"; require_once "inc/nxs_functions_adv.php"; require_once "inc/nxs_snap_class.php"; 
//## Include All Available Networks            
//error_reporting(E_ALL); ini_set('display_errors', '1');
global $nxs_snapAvNts, $nxs_snapThisPageUrl, $nxs_snapSetPgURL, $nxs_plurl, $nxs_plpath, $nxs_isWPMU, $nxs_tpWMPU, $nxs_skipSSLCheck;

$nxs_snapSetPgURL = nxs_get_admin_url().'options-general.php?page=NextScripts_SNAP.php'; $nxs_snapThisPageUrl = $nxs_snapSetPgURL; $nxs_plurl = plugin_dir_url(__FILE__); $nxs_plpath = plugin_dir_path(__FILE__); 
$nxs_isWPMU = defined('MULTISITE') && MULTISITE==true; 

if (class_exists("NS_SNAutoPoster")) { nxs_checkAddLogTable(); $plgn_NS_SNAutoPoster = new NS_SNAutoPoster(); }
do_action('nxs_doSomeMore');
if (!isset($nxs_snapAvNts) || !is_array($nxs_snapAvNts)) $nxs_snapAvNts = array(); $nxs_snapAPINts = array(); foreach (glob($nxs_plpath.'inc-cl/*.php') as $filename){  require_once $filename; } 
do_action('nxs_doSomeMoreSecond');
//## Tests
if (isset($_GET['page']) && $_GET['page']=='NextScripts_SNAP.php' && isset($_GET['do']) && $_GET['do']=='test'){ 
  error_reporting(E_ALL); ini_set('error_reporting', E_ALL); ini_set('display_errors', 1); if (function_exists('gzdeflate')) echo "Y"; else echo "N";  echo "Testting... cURL<br/>";
  nxs_cURLTest("http://www.google.com/intl/en/contact/", "HTTP to Google", "Mountain View, CA");
  nxs_cURLTest("https://www.google.com/intl/en/contact/", "HTTPS to Google", "Mountain View, CA");
  nxs_cURLTest("https://www.facebook.com/", "HTTPS to Facebook", 'id="facebook"');
  nxs_cURLTest("https://graph.facebook.com/nextscripts", "HTTPS to API (Graph) Facebook", '270851199672443');  
  nxs_cURLTest("https://www.linkedin.com/", "HTTPS to LinkedIn", 'rel="canonical" href="https://www.linkedin.com/"');
  nxs_cURLTest("https://twitter.com/", "HTTPS to Twitter", '<link rel="canonical" href="https://twitter.com/">');
  nxs_cURLTest("https://www.pinterest.com/", "HTTPS to Pinterest", 'content="Pinterest"');
  nxs_cURLTest("http://www.livejournal.com/", "HTTP to LiveJournal", '1999 LiveJournal');  
  die('Done');
}
if (isset($_GET['page']) && $_GET['page']=='NextScripts_SNAP.php' && isset($_GET['do']) && $_GET['do']=='crtest'){ 
    if (isset($_GET['redo']) && $_GET['redo']=='1'){ delete_option("NXS_cronCheck");  ?><script type="text/javascript">window.location = "<?php echo $nxs_snapSetPgURL; ?>&do=crtest"</script><?php }    
    $cr = get_option('NXS_cronCheck'); if (!empty($cr) && is_array($cr)) { $checks = $cr['cronChecks']; $numChecks = count($checks); echo '<div style="font-family:\'Open Sans\',sans-serif;font-size: 15px;">';
      if ( ($cr['cronCheckStartTime']+900)>(time())) echo "<b>Cron Check is in Progress.....</b> will be finished in ".($cr['cronCheckStartTime']+900-time()).' seconds. Please <input type="button" value="Reload" onClick="location.reload()"> this page to see more results.... <br/><br/>'; else { echo "Cron Check Results:<br/>";
        echo '<span style="color:#761616">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;==== Cron was executed <b>'.$numChecks.'</b> times in 15 minutes ===</span>';
        if ($numChecks>15 || $numChecks<2) echo '<b style="color:#FF0000"><br/><br/>Your WP Cron is not healthy</b><br/><br/><span style="color:#761616">'.(($numChecks>15)?('WP Cron should NOT be executed more then once per minute.'):('WP Cron should be executed at least once in 5-10 minutes.')).'  Some functionality (like auto-reposting) will be disabled.</span><br/><br/><span style="color:#005858; font-weight:bold;">Why this is important?</span><br/><span style="color:#005858">Please see this post: <a href="http://www.nextscripts.com/blog/troubles-wp-cron-existing-posts-auto-reposter/" target="_blank">Troubles with WP Cron and existing posts auto-reposter</a></span><br/><br/><span style="color:#005858; font-weight:bold;">Solution</span><br/><span style="color:#005858">Please see the instructions for the correct WP Cron setup: <a href="http://www.nextscripts.com/tutorials/wp-cron-scheduling-tasks-in-wordpress/" target="_blank">WP-Cron: Scheduling Tasks in WordPress</a></span>'; else  echo '<b style="color:#0000FF"><br/><br/>Your WP Cron is OK</b>';
      }
     ?> <br/><br/><span style="color:#000058; font-weight:normal;">Technical Info:</span> <?php prr($cr);  ?>&nbsp;&nbsp;====&nbsp;<a href="<?php echo $nxs_snapThisPageUrl; ?>&do=crtest&redo=1">Re-do Cron Check</a> (it will take 15 minutes to complete)<?php
    } else echo 'Check is not started yet... Please <input type="button" value="Reload" onClick="location.reload()"> this page in couple minutes.';
     echo '</div>';
    die();
}
//## Delete Account
if (!function_exists("ns_delNT_ajax")) { function ns_delNT_ajax(){ check_ajax_referer('nxsSsPageWPN'); $indx = (int)$_POST['id']; 
  global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
  unset($options[$_POST['nt']][$indx]); if (is_array($options)) { update_option('NS_SNAutoPoster', $options); $plgn_NS_SNAutoPoster->nxs_options = $options; }
}}
if (!function_exists("nsAuthFBSv_ajax")) { function nsAuthFBSv_ajax() { check_ajax_referer('nsFB');  $pgID = $_POST['pgID']; $fbs = array();
  global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options;   
  foreach ($options['fb'] as $two) { if ($two['fbPgID']==$pgID) $two['wfa']=time(); $fbs[] = $two; } $options['fb'] = $fbs; if (is_array($options)) { update_option('NS_SNAutoPoster', $options); $plgn_NS_SNAutoPoster->nxs_options = $options; }
}}  
if (!function_exists("nsGetBoards_ajax")) { 
  function nsGetBoards_ajax() { global $nxs_gCookiesArr; check_ajax_referer('nxsSsPageWPN'); global $plgn_NS_SNAutoPoster; if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
  if (get_magic_quotes_gpc() || $_POST['nxs_mqTest']=="\'") { $_POST['u'] = stripslashes($_POST['u']);  $_POST['p'] = stripslashes($_POST['p']);} $_POST['p'] = trim($_POST['p']); $u = trim($_POST['u']);  
   $loginError = doConnectToPinterest($_POST['u'],  substr($_POST['p'], 0, 5)=='g9c1a'?nsx_doDecode(substr($_POST['p'], 5)):$_POST['p'] );  if ($loginError!==false) {echo $loginError; return "BAD USER/PASS";} 
   $gPNBoards = doGetBoardsFromPinterest();  $options['pn'][$_POST['ii']]['pnBoardsList'] = base64_encode($gPNBoards);
   $options['pn'][$_POST['ii']]['pnSvC'] = serialize($nxs_gCookiesArr); if (is_array($options)) update_option('NS_SNAutoPoster', $options); echo $gPNBoards; die();
  }
}     

if (!function_exists("nxs_getBrdsOrCats_ajax")) { 
  function nxs_getBrdsOrCats_ajax() { global $nxs_gCookiesArr; check_ajax_referer('nxsSsPageWPN'); global $plgn_NS_SNAutoPoster; if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
    if (get_magic_quotes_gpc() || $_POST['nxs_mqTest']=="\'") { $_POST['u'] = stripslashes($_POST['u']);  $_POST['p'] = stripslashes($_POST['p']);} $_POST['p'] = trim($_POST['p']); $u = trim($_POST['u']);  
  
    if ( $_POST['ty']=='pn') { $loginError = doConnectToPinterest($_POST['u'],  substr($_POST['p'], 0, 5)=='g9c1a'?nsx_doDecode(substr($_POST['p'], 5)):$_POST['p'] );  if ($loginError!==false) {echo $loginError; return "BAD USER/PASS";} 
      $gPNBoards = doGetBoardsFromPinterest();  $options['pn'][$_POST['ii']]['pnBoardsList'] = base64_encode($gPNBoards);
      $options['pn'][$_POST['ii']]['pnSvC'] = serialize($nxs_gCookiesArr); if (is_array($options)) update_option('NS_SNAutoPoster', $options); echo $gPNBoards; die();
    }
    if ( $_POST['ty']=='rd') { $loginError = doConnectToRD($_POST['u'],  substr($_POST['p'], 0, 5)=='g9c1a'?nsx_doDecode(substr($_POST['p'], 5)):$_POST['p'] ); if (!is_array($loginError)) { echo $loginError; return "BAD USER/PASS";} 
      $gBoards = doGetSubredditsFromRD(); $options['rd'][$_POST['ii']]['rdSubRedditsList'] = base64_encode($gBoards);
      if (is_array($options)) update_option('NS_SNAutoPoster', $options); echo $gBoards; die();
    }
     
  }
} 


if (!function_exists("nxs_delPostSettings_ajax")) { function nxs_delPostSettings_ajax(){ check_ajax_referer('nxsSsPageWPN'); global $nxs_snapAvNts; $pid = (int)$_POST['pid'];
  foreach ($nxs_snapAvNts as $avNt) delete_post_meta($pid, 'snap'.strtoupper($avNt['code'])); 
  echo "OK"; die();
}}

if (!function_exists("nsGetGPCats_ajax")) { 
  function nsGetGPCats_ajax() { global $nxs_gCookiesArr; check_ajax_referer('nxsSsPageWPN'); global $plgn_NS_SNAutoPoster; if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
  if (get_magic_quotes_gpc() || $_POST['nxs_mqTest']=="\'") { $_POST['u'] = stripslashes($_POST['u']);  $_POST['p'] = stripslashes($_POST['p']);} $_POST['p'] = trim($_POST['p']); $u = trim($_POST['u']);  
   $loginError = doConnectToGooglePlus2($_POST['u'],  substr($_POST['p'], 0, 5)=='g9c1a'?nsx_doDecode(substr($_POST['p'], 5)):$_POST['p'] );  if ($loginError!==false) {echo $loginError; return "BAD USER/PASS";} 
   $gGPCCats = doGetCCatsFromGooglePlus($_POST['c']);  $options['gp'][$_POST['ii']]['gpCCatsList'] = base64_encode($gGPCCats);
   if (is_array($options)) update_option('NS_SNAutoPoster', $options); echo $gGPCCats; die();
  }
}     
if (!function_exists("nsGetWLBoards_ajax")) { 
  function nsGetWLBoards_ajax() { global $nxs_gCookiesArr; check_ajax_referer('nxsSsPageWPN'); global $plgn_NS_SNAutoPoster; if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
  if (get_magic_quotes_gpc() || $_POST['nxs_mqTest']=="\'") { $_POST['u'] = stripslashes($_POST['u']);  $_POST['p'] = stripslashes($_POST['p']);} $_POST['p'] = trim($_POST['p']); $u = trim($_POST['u']);  
   $loginError = doConnectToWaNeLo($_POST['u'],  substr($_POST['p'], 0, 5)=='g9c1a'?nsx_doDecode(substr($_POST['p'], 5)):$_POST['p'] );  if ($loginError!==false) {echo $loginError; return "BAD USER/PASS";} 
   $gWLBoards = doGetBoardsFromWaNeLo();  $options['wl'][$_POST['ii']]['wlBoardsList'] = base64_encode($gWLBoards);
   $options['wl'][$_POST['ii']]['wlSvC'] = serialize($nxs_gCookiesArr); if (is_array($options)) update_option('NS_SNAutoPoster', $options); echo $gWLBoards; die();
  }
}     
//## Set all posts to Include/exclude from/to Auto-Reposting
if (!function_exists("nxs_SetRpstAll_ajax")) { 
 function nxs_SetRpstAll_ajax() { check_ajax_referer('nxsSsPageWPN'); global $plgn_NS_SNAutoPoster; if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options;//  prr($options[$_POST['t']][$_POST['ii']]);   
   if ($_POST['ed']=='X' || $_POST['ed']=='L') { // prr($options[$_POST['t']][$_POST['ii']]); prr($options); die();
     if ($_POST['ed']=='X') { $options[$_POST['t']][$_POST['ii']]['rpstLastPostID'] = ''; 
       $options[$_POST['t']][$_POST['ii']]['rpstLastShTime'] = ''; $options[$_POST['t']][$_POST['ii']]['rpstLastPostTime'] = '';  $options[$_POST['t']][$_POST['ii']]['rpstNxTime'] = ''; 
     } elseif ($_POST['ed']=='L' && trim($_POST['lpid'])!='' && (int)$_POST['lpid'] > 0) { 
         $post = get_post($_POST['lpid']);
         $options[$_POST['t']][$_POST['ii']]['rpstLastPostTime'] = $post->post_date;
         $options[$_POST['t']][$_POST['ii']]['rpstLastPostID'] = trim($_POST['lpid']);     
     }
     if (is_array($options)) { update_option('NS_SNAutoPoster', $options); $plgn_NS_SNAutoPoster->nxs_options = $options; } //  echo "|".$_POST['t'].$_POST['ii']."|"; prr($options[$_POST['t']][$_POST['ii']]);
   } else { 
    if (!empty($options['nxsCPTSeld'])) $tpArray = maybe_unserialize($options['nxsCPTSeld']); if (!is_array($tpArray)) $tpArray = array('post'); else $tpArray[] = 'post'; 
    foreach ($tpArray  as $tp) if (!empty($tp)) { 
    $args = array( 'post_type' => $tp, 'post_status' => 'publish', 'numberposts' => 30, 'offset'=> 0, 'fields'=>'ids' ); $posts = get_posts( $args ); 
    while (count($posts)>0){
      foreach ($posts as $postID){ $pMeta = maybe_unserialize(get_post_meta($postID, 'snap'.strtoupper($_POST['t']), true)); 
        if (!isset($pMeta) || !is_array($pMeta)) $pMeta = array();  if (!isset($pMeta[$_POST['ii']]) || !is_array($pMeta[$_POST['ii']])) $pMeta[$_POST['ii']] = array(); 
        if ($_POST['ed']!='2') $pMeta[$_POST['ii']]['rpstPostIncl'] = $_POST['ed']=='0'?'0':'nxsi'.$_POST['ii'].$_POST['t'];  else {           
            $doPost = true; $exclCats = maybe_unserialize($options['exclCats']); $postCats = wp_get_post_categories($postID);
            foreach ($postCats as $pCat) { if ( (is_array($exclCats)) && in_array($pCat, $exclCats)) $doPost = false; else {$doPost = true; break;}}
            $optMt = $options[$_POST['t']][$_POST['ii']];
            if ( $optMt['catSel']=='1' && trim($optMt['catSelEd'])!='' ) { $inclCats = explode(',',$optMt['catSelEd']); foreach ($postCats as $pCat) { if (!in_array($pCat, $inclCats)) $doPost = false; else {$doPost = true; break;}} }
            $pMeta[$_POST['ii']]['rpstPostIncl'] = $doPost?'nxsi'.$_POST['ii'].$_POST['t']:'0'; 
        } delete_post_meta($postID, 'snap'.strtoupper($_POST['t'])); add_post_meta($postID, 'snap'.strtoupper($_POST['t']), serialize($pMeta));        
      } $args['offset'] = $args['offset']+30;  $posts = get_posts( $args );
    } 
    }
  } echo "OK"; die(); 
}}  
if (!function_exists("nxs_clLgo_ajax")) { function nxs_clLgo_ajax() { check_ajax_referer('nxsSsPageWPN'); global $wpdb;
  //update_option('NS_SNAutoPosterLog', ''); 
  $wpdb->query( 'DELETE FROM '.$wpdb->prefix . 'nxs_log' ); echo "OK";
}} 
if (!function_exists("nxs_rfLgo_ajax")) { function nxs_rfLgo_ajax() { check_ajax_referer('nxsSsPageWPN');  echo "Y:";
  //$log = get_option('NS_SNAutoPosterLog'); $logInfo = maybe_unserialize(get_option('NS_SNAutoPosterLog')); 
  $logInfo = nxs_getnxsLog();
  if (is_array($logInfo))foreach (array_reverse($logInfo) as $logline) { 
            if ($logline['type']=='E') $actSt = "color:#FF0000;"; elseif ($logline['type']=='M') $actSt = "color:#585858;"; elseif ($logline['type']=='BG') $actSt = "color:#008000; font-weight:bold;";
              elseif ($logline['type']=='I') $actSt = "color:#0000FF;"; elseif ($logline['type']=='W') $actSt = "color:#DB7224;"; elseif ($logline['type']=='A') $actSt = "color:#580058;";
              elseif ($logline['type']=='BI') $actSt = "color:#0000FF; font-weight:bold;"; elseif ($logline['type']=='GR') $actSt = "color:#008080;"; 
              elseif ($logline['type']=='S') $actSt = "color:#005800; font-weight:bold;"; else $actSt = "color:#585858;";              
            if ($logline['type']=='E') $msgSt = "color:#FF0000;"; elseif ($logline['type']=='BG') $msgSt = "color:#008000; font-weight:bold;"; else $msgSt = "color:#585858;";                            
            if ($logline['nt']!='') $ntInfo = ' ['.$logline['nt'].'] '; else $ntInfo = '';           
            echo '<snap style="color:#008000">['.$logline['date'].']</snap> - <snap style="'.$actSt.'">['.$logline['act'].']</snap>'.$ntInfo.'-  <snap style="'.$msgSt.'">'.$logline['msg'].'</snap> '.$logline['extInfo'].'<br/>'; 
  }


}} 


//## Initialize the admin panel if the plugin has been activated
if (!function_exists("nxs_AddSUASettings")) { function nxs_AddSUASettings() {  global $plgn_NS_SNAutoPoster, $nxs_plurl; // if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options;       
  add_menu_page('Social Networks Auto Poster', 'Social Networks Auto Poster', 'manage_options', basename(__FILE__), array(&$plgn_NS_SNAutoPoster, 'showSNAP_WPMU_OptionsPage'), $nxs_plurl.'img/snap-icon12.png');  }}
//## Initialize the admin panel if the plugin has been activated
if (!function_exists("NS_SNAutoPoster_ap")) { function NS_SNAutoPoster_ap() { global $plgn_NS_SNAutoPoster, $nxs_plurl; // if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options;       
   if (function_exists('add_options_page')) { add_options_page('Social Networks Auto Poster', 
     '<span style="font-weight:bold; color:#2ecc2e;">{SNAP} </span>Social Networks Auto Poster', 'manage_options', basename(__FILE__), array(&$plgn_NS_SNAutoPoster, 'showSNAutoPosterOptionsPage'));          
}}}
if (!function_exists("NS_SNAutoPoster_apx")) { function NS_SNAutoPoster_apx() { global $plgn_NS_SNAutoPoster, $nxs_plurl;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options;       
   if (function_exists('add_options_page')) { add_options_page('Social Networks Auto Poster', 
     '<span style="font-weight:bold; color:#2ecc2e">{SNAP} </span>Social Networks Auto Poster ', 'manage_options', basename(__FILE__), array(&$plgn_NS_SNAutoPoster, 'showSNAutoPosterOptionsPagex'));     
}}}
//## Main Function to Post 
if (!function_exists("nxs_snapLogPublishTo")) { function nxs_snapLogPublishTo( $new_status, $old_status, $post ) { clean_post_cache( $post->ID );
  if ( $old_status!='publish' && $old_status!='trash' && $new_status == 'publish' ) { nxs_addToLogN('BG', "*** ID: {$post->ID}, Type: {$post->post_type}", '', ' Status Changed: '."{$old_status}_to_{$new_status}".'. Autopost requested.'); 
    nxs_snapPublishTo($post);
  }
}}
if (!function_exists("nxs_snapPublishTo")) { function nxs_snapPublishTo($postArr, $type='', $aj=false) {  global $plgn_NS_SNAutoPoster, $nxs_snapAvNts, $blog_id, $nxs_tpWMPU;  //  echo " | nxs_doSMAS2 | "; prr($postArr);
  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
  if (!empty($_POST['nxs_snapPostOptions'])) { $NXS_POSTX = $_POST['nxs_snapPostOptions'];  $NXS_POST = array(); $NXS_POST = NXS_parseQueryStr($NXS_POSTX); } else $NXS_POST = $_POST;
  if(is_object($postArr)) $postID = $postArr->ID; else { $postID = $postArr; $postArr = get_post($postID);  } $isPost = isset($NXS_POST["snapEdIT"]);  $post = get_post($postID);   
  if ($post->post_status != 'publish') { sleep(5);  $post = get_post($postID); $postArr = $post;
    if ($post->post_status != 'publish') {  nxs_addToLogN('I', 'Cancelled', '', 'Autopost Cancelled - Post is not "Published" Right now - Post ID:('.$postID.') - Current Post status -'.$post->post_status ); return; }
  }  
  //nxs_addToLogN('BG', 'Post Status Changed', '', '-=## Autopost requested.'.($blog_id>1?'BlogID:'.$blog_id:'').' PostID:('.$postID.') Post Type: '.$post->post_type.' ##=-'); 
   //$args=array('public'=>true, '_builtin'=>false);  $output = 'names';  $operator = 'and';  $post_types = array(); ## Removed because some post types are not available from WP Cron
  // if (function_exists('get_post_types')) { $post_types=get_post_types($args, $output, $operator);  ## Removed because some post types are not available from WP Cron
  if ( isset($options['nxsCPTSeld']) && $options['nxsCPTSeld']!='') $nxsCPTSeld = unserialize($options['nxsCPTSeld']);  else $nxsCPTSeld = array(); 
  // if ($post->post_type == 'post' || ($options['useForPages']=='1' && $post->post_type == 'page') || (in_array($post->post_type, $post_types) && in_array($post->post_type, $nxsCPTSeld))) {  ## Removed because some post types are not available from WP Cron
  if ($post->post_type == 'post' || ($options['useForPages']=='1' && $post->post_type == 'page') || (in_array($post->post_type, $nxsCPTSeld))) { 
    if ($isPost && $options['skipSecurity']!='1' && !current_user_can("make_snap_posts") && !current_user_can("manage_options")) { nxs_addToLogN('I', 'Skipped', '', 'Current user can\'t autopost - Post ID:('.$postID.')' ); return; }
    $postUser = $postArr->post_author; 
    if ($options['skipSecurity']!='1' && !user_can( $postUser, "make_snap_posts" ) && !user_can( $postUser, "manage_options")){ nxs_addToLogN('I', 'Skipped', '', 'User ID '.$postUser.' can\'t autopost (see <a target="_blank" href="http://www.nextscripts.com/support-faq/#q17">FAQ #1.7</a>)  - Post ID:('.$postID.')' ); return; } 
    if ($isPost) $plgn_NS_SNAutoPoster->NS_SNAP_SavePostMetaTags($postID); 
    if (function_exists('nxs_doSMAS2')) { nxs_doSMAS2($postArr, $type, $aj); return; } else {
    $options = $plgn_NS_SNAutoPoster->nxs_options;  $ltype=strtolower($type);
    if ($nxs_tpWMPU=='S') { switch_to_blog(1); $plgn_NS_SNAutoPoster = new NS_SNAutoPoster(); $options = $plgn_NS_SNAutoPoster->nxs_options; restore_current_blog(); }
    if (!isset($options['nxsHTDP']) || $options['nxsHTDP']=='S') { if(isset($NXS_POST["snapEdIT"]) && $NXS_POST["snapEdIT"]=='1') { $publtype='S'; $delay = rand(2,10); } else $publtype='A'; } else $publtype = 'I';
    nxs_addToLogN('BG', 'Start =- ', '', '------=========#### NEW AUTO-POST REQUEST '.($blog_id>1?'BlogID:'.$blog_id:'').' PostID:('.$postID.') '.($publtype=='S'?'Scheduled +'.$delay:($publtype=='A'?'Automated':'Immediate')).' ####=========------');
  
    $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted=='1') { nxs_addToLogN('W', 'Skipped', '', 'Already Autoposted - Post ID:('.$postID.')' ); return; }  
    $snap_isEdIT = get_post_meta($postID, 'snapEdIT', true); if ($snap_isEdIT!='1') { $doPost = true; $exclCats = maybe_unserialize($options['exclCats']); $postCats = wp_get_post_categories($postID);
       foreach ($postCats as $pCat) { if ( (is_array($exclCats)) && in_array($pCat, $exclCats)) $doPost = false; else {$doPost = true; break;}}
       if (!$doPost) { nxs_addToLogN('I', 'Skipped', '', 'Automated Post - Category Excluded - Post ID:('.$postID.')' ); return; }
    }    
      
    foreach ($nxs_snapAvNts as $avNt) { 
      if (count($options[$avNt['lcode']])>0) { $clName = 'nxs_snapClass'.$avNt['code']; 
        if ($isPost && isset($NXS_POST[$avNt['lcode']])) $po = $NXS_POST[$avNt['lcode']]; else { $po =  get_post_meta($postID, 'snap'.$avNt['code'], true); $po =  maybe_unserialize($po);}       
        if (isset($po) && is_array($po)) $isPostMeta = true; else { $isPostMeta = false; $po = $options[$avNt['lcode']]; }
        delete_post_meta($postID, 'snap_isAutoPosted'); add_post_meta($postID, 'snap_isAutoPosted', '1');
        $optMt = $options[$avNt['lcode']][0]; if ($isPostMeta) { $ntClInst = new $clName(); $optMt = $ntClInst->adjMetaOpt($optMt, $po[0]); }       
        
         
        
          if ($snap_isEdIT!='1') { $doPost = true; 
            if ( $optMt['catSel']=='1' && trim($optMt['catSelEd'])!='' ) { $inclCats = explode(',',$optMt['catSelEd']); foreach ($postCats as $pCat) { if (!in_array($pCat, $inclCats)) $doPost = false; else {$doPost = true; break;}} 
              if (!$doPost) { nxs_addToLogN('I', 'Skipped', $avNt['name'].' ('.$optMt['nName'].')', '[Automated Post]  - Individual Category Excluded - Post ID:('.$postID.')' ); continue; }
            }
            //## Get tags
            if (!empty($optMt['tagsSel'])) { $inclTags = explode(',',strtolower($optMt['tagsSel'])); $postTags = wp_get_post_tags( $postID, array( 'fields' => 'slugs' ) ); $postCust = array();
              //## Get all custom post types
              foreach ($inclTags as $iTag){ 
                if (strpos($iTag,'|')!==false){ $dd=explode('',$itag); if (empty($postCust[$dd[0]])) $postCust[$dd[0]]=wp_get_object_terms($postID,$dd[0],array('fields'=>'slugs')); 
                  if (!in_array(strtolower($dd[1]), $postCust[$dd[0]])) $doPost = false; else {$doPost = true; break;}
                } else if (!in_array(strtolower($iTag), $postTags)) $doPost = false; else {$doPost = true; break;}              
              }
              //nxs_addToLogN('I', 'Plus 1', '', ' + Post ID:( '.$postID.' - '.print_r($postTags, true).')' );
            
              //foreach ($postTags as $pCat) { if (!in_array(strtolower($pCat), $inclTags)) $doPost = false; else {$doPost = true; break;}} 
              if (!$doPost) { nxs_addToLogN('I', 'Skipped', $avNt['name'].' ('.$optMt['nName'].')', '[Automated Post]  - Tag Excluded - Post ID:('.$postID.') - Included Tags: '.$optMt['tagsSel'].' | Post Tags: '.print_r($postTags, true)." | ".print_r($postCust, true) ); continue; }
            }
          }        
          
          
          
          if ($optMt['do'.$avNt['code']]=='1') { $optMt['ii'] = 0;  
            if ($publtype=='A' && ($optMt['nMin']>0 || $optMt['nHrs']>0 || $optMt['nTime']!='')) $publtype='S';        
            if ($publtype=='S') { if (isset($optMt['nHrs']) && isset($optMt['nMin']) && ($optMt['nHrs']>0 || $optMt['nMin']>0) ) { $delay = $optMt['nMin']*60+$optMt['nHrs']*3600;
                nxs_addToLogN('I', 'Delayed', $avNt['name'].' ('.$optMt['nName'].')', 'Post has been delayed for '.$delay.' Seconds ('.($optMt['nHrs']>0?$optMt['nHrs'].' Hours':'')." ".($optMt['nMin']>0?$optMt['nMin'].' Minutes':'').')' );
              } else $delay = rand(2,10); $optMt['timeToRun'] = time()+$delay; 
              if ($options['ver']>300.330) { $shName = '_nxs_snap_sh_'.$avNt['code'].'0_'.$optMt['timeToRun']; delete_post_meta($postID, $shName); add_post_meta($postID, $shName, $optMt); $args = array($postID, $shName); }
                else $args = array($postID, $optMt);  
              wp_schedule_single_event($optMt['timeToRun'],'ns_doPublishTo'.$avNt['code'], $args); 
                nxs_addToLogN('BI', 'Scheduled', $avNt['name'].' ('.$optMt['nName'].') for '.$optMt['timeToRun']."(".date_i18n('Y-m-d H:i:s', $optMt['timeToRun'] + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS )).")", ' PostID:('.$postID.')' );
            } else { $fname = 'nxs_doPublishTo'.$avNt['code']; $fname($postID, $optMt); }
          } else { nxs_addToLogN('GR', 'Skipped', $avNt['name'].' ('.$optMt['nName'].')', '-=[Unchecked Account]=- - PostID:'.$postID.'' ); }
        }                   
      } } } else { nxs_addToLogN('I', 'Skipped', '', 'Excluded Post Type: '.$post->post_type.' (Post ID: '.$postID.')| NOT IN ('.print_r($nxsCPTSeld, true).')| ALL ('.print_r($post_types, true).')' ); return; }
   if ($isS) restore_current_blog(); 
}} 

//## Add settings link to plugins list
if (!function_exists("ns_add_settings_link")) { function ns_add_settings_link($links, $file) {
    static $this_plugin;
    if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
    if ($file == $this_plugin){
        $settings_link = '<a href="options-general.php?page=NextScripts_SNAP.php">'.__("Settings", "default").'</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}}
//## Actions and filters    

if (!function_exists("nxs_adminInitFunc")) { function nxs_adminInitFunc(){ global $plgn_NS_SNAutoPoster, $nxs_snapThisPageUrl, $pagenow, $nxs_isWPMU; 
  $nxs_snapThisPageUrl = nxs_get_admin_url().($pagenow=='admin.php'?'network/':'').$pagenow.'?page=NextScripts_SNAP.php'; 
  if (function_exists('nxs_getInitUCheck') && (isset($plgn_NS_SNAutoPoster))) { $options = $plgn_NS_SNAutoPoster->nxs_options; if (is_array($options) && count($options)>1) nxs_getInitUCheck($options);  } 
  //## Javascript to Admin Panel        
  if (( ($pagenow=='options-general.php'||$pagenow=='admin.php') && isset($_GET['page']) && ( $_GET['page']=='NextScripts_SNAP.php' || stripos($_GET['page'], 'nxssnap')==0)) ||$pagenow=='post.php'||$pagenow=='post-new.php'){
    if ( isset($_GET['post_type']) && $_GET['post_type']=='page' && isset($options['useForPages']) && $options['useForPages']!=1 ) {} 
      else { add_filter( 'tiny_mce_before_init', 'nxs_tiny_mce_before_init' ); add_action('admin_head', 'jsPostToSNAP'); add_action('admin_head', 'nxs_jsPostToSNAP2'); }
  }
  if (function_exists('nxsDoLic_ajax')) { add_action('wp_ajax_nxsDoLic', 'nxsDoLic_ajax');  } 
}}
if (!function_exists("nxs_adminInitFunc2")) { function nxs_adminInitFunc2(){ global $plgn_NS_SNAutoPoster, $nxs_snapThisPageUrl, $pagenow;   $nxs_snapThisPageUrl = nxs_get_admin_url().($pagenow=='admin.php'?'network/':'').$pagenow.'?page=NextScripts_SNAP.php';  //## Add MEtaBox to Post Edit Page
  if (current_user_can("see_snap_box") || current_user_can("manage_options")) { add_action('add_meta_boxes', array($plgn_NS_SNAutoPoster, 'NS_SNAP_addCustomBoxes'));        
    if (!($pagenow=='options-general.php' && !empty($_GET['page']) && $_GET['page']=='NextScripts_SNAP.php')) add_action( 'admin_bar_menu', 'nxs_toolbar_link_to_mypage', 999 );
  }
}}

function nxs_saveSiteSets_ajax(){ check_ajax_referer('nxssnap'); 
   if ($_POST['sid']=='A'){  global $wpdb; $allBlogs = $wpdb->get_results("SELECT blog_id FROM wp_blogs where blog_id > 1");
     foreach( $allBlogs as $aBlog ) { switch_to_blog($aBlog->blog_id); 
       $options =  get_option('NS_SNAutoPoster'); $options['suaMode'] = $_POST['sset']; update_option('NS_SNAutoPoster', $options);
     }       
   } else { switch_to_blog($_POST['sid']); 
     $options = get_option('NS_SNAutoPoster'); $options['suaMode'] = $_POST['sset']; if( is_super_admin() && $_POST['sid']=='1' && $options['suaMode']!='O') $options['suaMode'] = 'O'; update_option('NS_SNAutoPoster', $options); 
   }
   echo "OK"; die();
}

//## OG:Tags
function nxs_start_ob(){ if (!is_admin()) ob_start( 'nxs_ogtgCallback' );}
function nxs_end_flush_ob(){ if (!is_admin()) @ob_end_flush();}
function nxs_ogtgCallback($content){ global $post, $plgn_NS_SNAutoPoster;  
  if (stripos($content, 'og:title')!==false) $ogOut = "\r\n"; else {
    if (!isset($plgn_NS_SNAutoPoster)) $options = get_option('NS_SNAutoPoster'); else $options = $plgn_NS_SNAutoPoster->nxs_options;    $ogimgs = array();  
    if (!empty($post) && !is_object($post) && int($post)>0) $post = get_post($post); if (empty($options['advFindOGImg'])) $options['advFindOGImg'] = 0;       
    $title = preg_match( '/<title>(.*)<\/title>/', $content, $title_matches );  
    if ($title !== false && count( $title_matches) == 2 ) $ogT ='<meta property="og:title" content="' . $title_matches[1] . '" />'."\r\n"; else {
      if (is_home() || is_front_page() )  $ogT = get_bloginfo( 'name' ); else $ogT = get_the_title();
      $ogT =  '<meta property="og:title" content="' . esc_attr( apply_filters( 'nxsog_title', $ogT ) ) . '" />'."\r\n";          
    }    
    $prcRes = preg_match( '/<meta name="description" content="(.*)"/', $content, $description_matches );    
    if ( $prcRes !== false && count( $description_matches ) == 2 ) $ogD = '<meta property="og:description" content="' . $description_matches[1] . '" />'."\r\n"; {
      if (!empty($post) && is_object($post) && is_singular()) {
        if(has_excerpt($post->ID))$ogD=strip_tags(nxs_snapCleanHTML(get_the_excerpt($post->ID)));else $ogD= str_replace("  ", ' ', str_replace("\r\n", ' ', trim(substr(strip_tags(nxs_snapCleanHTML(strip_shortcodes($post->post_content))), 0, 200))));
      } else $ogD = get_bloginfo('description');  $ogD = preg_replace('/\r\n|\r|\n/m','',$ogD); 
      $ogD = '<meta property="og:description" content="'.esc_attr( apply_filters( 'nxsog_desc', $ogD ) ).'" />'."\r\n";          
    }    
    $ogSN = '<meta property="og:site_name" content="'.get_bloginfo('name').'" />'."\r\n";
    $ogLoc = strtolower(esc_attr(get_locale())); if (strlen($ogLoc)==2) $ogLoc .= "_".strtoupper($ogLoc);
    $ogLoc = '<meta property="og:locale" content="'.$ogLoc.'" />'."\r\n"; $iss = is_home();  
    $ogType = is_singular()?'article':'website'; if($vidsFromPost == false) $ogType = '<meta property="og:type" content="'.esc_attr(apply_filters('nxsog_type', $ogType)).'" />'."\r\n";                  
        
    if (is_home() || is_front_page()) $ogUrl = get_bloginfo( 'url' ); else $ogUrl = 'http' . (is_ssl() ? 's' : '') . "://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $ogUrl = '<meta property="og:url" content="'.esc_url( apply_filters( 'nxsog_url', $ogUrl ) ) . '" />' . "\r\n";
  
    if (!is_home()) { /*
      $vidsFromPost = nsFindVidsInPost($post); if ($vidsFromPost !== false && is_singular()) {  echo '<meta property="og:video" content="http://www.youtube.com/v/'.$vidsFromPost[0].'" />'."\n";  
      echo '<meta property="og:video:type" content="application/x-shockwave-flash" />'."\n";
      echo '<meta property="og:video:width" content="480" />'."\n";
      echo '<meta property="og:video:height" content="360" />'."\n";
      echo '<meta property="og:image" content="http://i2.ytimg.com/vi/'.$vidsFromPost[0].'/mqdefault.jpg" />'."\n";
      echo '<meta property="og:type" content="video" />'."\n"; 
    } */
    
      
      $imgURL = nxs_getPostImage($post->ID, 'full', $options['ogImgDef']); if (!empty($imgURL)) $ogimgs[] = $imgURL;
      $imgsFromPost = nsFindImgsInPost($post, (int)$options['advFindOGImg']==1);           
      if ($imgsFromPost !== false && is_singular() && is_array($ogimgs) && is_array($imgsFromPost))  $ogimgs = array_merge($ogimgs, $imgsFromPost);       
    }       
    //## Add default image to the endof the array
    if ( count($ogimgs)<1 && isset($options['ogImgDef']) && $options['ogImgDef']!='') $ogimgs[] = $options['ogImgDef']; 
    //## Output og:image tags
    if (!empty($ogimgs) && is_array($ogimgs)) foreach ($ogimgs as $ogimage)  $ogImgsOut = '<meta property="og:image" content="'.esc_url(apply_filters('ns_ogimage', $ogimage)).'" />'."\r\n"; 
    $ogOut  = "\r\n".$ogSN.$ogT.$ogD.$ogType.$ogUrl.$ogLoc.$ogImgsOut;
  } $content = str_ireplace('<!-- ## NXSOGTAGS ## -->', $ogOut, $content); 
  return $content;
}
function nxs_addOGTagsPreHolder() { echo "<!-- ## NXS/OG ## --><!-- ## NXSOGTAGS ## --><!-- ## NXS/OG ## -->\n\r";}

if (!function_exists("nxssnap_enqueue_scripts")) { function nxssnap_enqueue_scripts(){ 
  wp_enqueue_script( 'nxssnap-scripts', plugin_dir_url( __FILE__ ) . 'js/js.js', array( 'jquery' ),  NextScripts_SNAP_Version);
  wp_localize_script( 'nxssnap-scripts', 'MyAjax', array( 'ajaxurl' => nxs_get_admin_url( 'admin-ajax.php' ), 'nxsnapWPnonce' => wp_create_nonce( 'nxsnapWPnonce' ),));
}} 

function nxs_noR(&$item, &$key){ $item = is_string($item)?(str_replace("\r","\n",str_replace("\n\r","\n",str_replace("\r\n","\n",$item)))):$item; }

if (!function_exists("nxs_getExpSettings_ajax")) { function nxs_getExpSettings_ajax() { /* check_ajax_referer('nsDN'); */  $filename = preg_replace('/[^a-z0-9\-\_\.]/i','',$_POST['filename']);
 header("Cache-Control: "); header("Content-type: text/plain"); header('Content-Disposition: attachment; filename="'.$filename.'"');
 global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
 //array_walk_recursive($options, 'nxs_addslashes');
 array_walk_recursive($options,"nxs_noR");  $ser = serialize($options); echo $ser;  die();
}}

function cron_add_nxsreposter( $schedules ) { $schedules['nxsreposter'] = array( 'interval' => 90, 'display' => __( 'NXS Reposter' )); return $schedules;} // Do this every 90 seconds

function nxs_showNewPostForm($options, $air = true) { global $nxs_snapAvNts, $nxs_plurl; ?> 
  <div id="nxsNewSNPost" style="width: 880px;">
  
    <div><h2>New Post to the Configured Social Networks</h2></div>
    <div class="nxsNPRow"><label class="nxsNPLabel">Title (Will be used where possible):</label><br/><input id="nxsNPTitle" type="text" size="80"></div>
    <div class="nxsNPRow"><label class="nxsNPLabel">Message:</label><br/><textarea id="nxsNPText" name="textarea" cols="90" rows="8"></textarea></div>
    
    <div class="nxsNPRow"><label class="nxsNPLabel">Post Type:</label><br/><input type="radio" name="nxsNPType"  id="nxsNPTypeT" value="T" checked="checked" /><label class="nxsNPRowSm">Text Post</label><br/>
    
    <br/><input type="radio" name="nxsNPType"  id="nxsNPTypeL" value="A"><label class="nxsNPRowSm">Link Post</label>
      <div class="nxsNPRowSm"><label class="nxsNPLabel">URL (Will be attached where possible, text post will be made where not):</label><br/><input id="nxsNPLink" onfocus="jQuery('#nxsNPTypeL').attr('checked', 'checked')" type="text" size="80" /></div>
    <br/><input type="radio" name="nxsNPType" id="nxsNPTypeI" value="I"><label class="nxsNPRowSm">Image Post</label>
      <div class="nxsNPRowSm"><label class="nxsNPLabel">Image URL (Will be used where possible, text post will be made where not):</label><br/><input id="nxsNPImg" onfocus="jQuery('#nxsNPTypeI').attr('checked', 'checked')" type="text" size="80" /></div>
    </div>
    <div class="nxsNPRow">
      <div class="nxsNPLeft" style="display: inline-block;">
      
      <div id="nxsNPLoaderPost" style="display: none";> <img  src="<?php echo $nxs_plurl; ?>img/ajax-loader-med.gif" /> Posting...., it could take some time...  </div>
      
      <div class="submitX"><input style="font-weight: bold; width: 70px;" type="button" onclick="nxs_doNP();" value="Post">
      <?php if ($air) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<input id="nxsNPCloseBt" style="width: 70px;" class="bClose" type="button" value="Cancel"> <?php } ?>
      </div> 
      
      <div id="nxsNPResult">&nbsp;</div>
      </div>
      <div class="nxsNPRight">
     
    <div class="nxsNPRow">
    <div style="float: right; font-size: 12px;" >
      <a href="#" onclick="jQuery('.nxsNPDoChb').attr('checked','checked'); return false;"><?php  _e('Check All', 'nxs_snap'); ?></a>&nbsp;<a href="#" onclick="jQuery('.nxsNPDoChb').removeAttr('checked'); return false;"><?php _e('Uncheck All', 'nxs_snap'); ?></a>
    </div>
    <label class="nxsNPLabel">Networks:</label><br/> 
    <div class="nxsNPRow" style="font-size: 12px;">
    <?php 
      foreach ($nxs_snapAvNts as $avNt) { $clName = 'nxs_snapClass'.$avNt['code']; $ntClInst = new $clName();
        if ( isset($options[$avNt['lcode']]) && count($options[$avNt['lcode']])>0) { ?>  
              
              <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $avNt['lcode']; ?>16.png);"><?php echo $avNt['name']; ?><br/>
              <?php $ntOpts = $options[$avNt['lcode']]; foreach ($ntOpts as $indx=>$pbo){ ?>
              <input class="nxsNPDoChb" value="<?php echo $avNt['lcode']; ?>--<?php echo $indx; ?>" name="nxsNPNts" type="checkbox" <?php if ((int)$pbo['do'.$avNt['code']] == 1 && $pbo['catSel']!='1') echo "checked"; ?> /> 
                      
             <?php echo $avNt['name']; ?> <i style="color: #005800;"><?php if($pbo['nName']!='') echo "(".$pbo['nName'].")"; ?></i></br>
              
              <?php  }  ?>
              </div>  <?php  
        } } ?> 
      </div>
   
  </div>   
  </div>
  </div> 
  </div> 
  
  <?php    
}
function nxs_doNewNPPost($options){ global $nxs_snapAvNts, $nxs_plurl; $postResults = '';
    if (!empty($_POST['mNts']) && is_array($_POST['mNts'])) { nxs_addToLogN('S', '-=== New Form Post requested ===-', 'Form', count($_POST['mNts']).' Networks', print_r($_POST['mNts'], true));
      $message = array('title'=>'', 'text'=>'', 'siteName'=>'', 'url'=>'', 'imageURL'=>'', 'videoURL'=>'', 'tags'=>'', 'urlDescr'=>'', 'urlTitle'=>'');  
      if (get_magic_quotes_gpc() || $_POST['nxs_mqTest']=="\'") { $_POST['mText'] = stripslashes($_POST['mText']); $_POST['mTitle'] = stripslashes($_POST['mTitle']); }
      $message['pText'] = $_POST['mText'];   $message['pTitle'] = $_POST['mTitle'];
      //## Get URL info
      if (!empty($_POST['mLink']) && substr($_POST['mLink'], 0, 4)=='http') { $message['url'] = $_POST['mLink'];            
        $flds = array('id'=>$message['url'], 'scrape'=>'true');      $response =  wp_remote_post('http://graph.facebook.com', array('body' => $flds)); 
        if (is_wp_error($response)) $badOut['Error'] = print_r($response, true)." - ERROR"; else { $response = json_decode($response['body'], true);  
          if (!empty($response['description'])) $message['urlDescr'] = $response['description'];  if (!empty($response['title'])) $message['urlTitle'] =  $response['title'];
          if (!empty($response['site_name'])) $message['siteName'] = $response['site_name'];
          if (!empty($response['image'][0]['url'])) $message['imageURL'] = $response['image'][0]['url'];
        }
      }
      if (!empty($_POST['mImg']) && substr($_POST['mImg'], 0, 4)=='http') $message['imageURL'] = $_POST['mImg']; 
          
      foreach ($_POST['mNts'] as $ntC){ $ntA = explode('--',$ntC); $ntOpts = $options[$ntA[0]][$ntA[1]]; 
        if (!empty($ntOpts) && is_array($ntOpts)) { $logNT = $ntA[0];  $clName = 'nxs_class_SNAP_'.strtoupper($logNT);                  
          $logNT = '<span style="color:#800000">'.strtoupper($logNT).'</span> - '.$ntOpts['nName'];      
          $ntOpts['postType'] = $_POST['mType']; $ntToPost = new $clName(); $ret = $ntToPost->doPostToNT($ntOpts, $message);      
          if (!is_array($ret) || $ret['isPosted']!='1') { //## Error 
             nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), ''); $postResults .= $logNT ." - Error (Please see log)<br/>";
          } else {  // ## All Good - log it.            
             if (!empty($ret['postURL'])) $extInfo = '<a href="'.$ret['postURL'].'" target="_blank">Post Link</a>'; 
             nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo); $postResults .= $logNT ." - OK - ".$extInfo."<br/>"; 
          }
        }
    } echo "Done. Results:<br/> ".$postResults; }
}

if (!function_exists("nxs_snapAjax")) { function nxs_snapAjax() { check_ajax_referer('nxsSsPageWPN'); global $plgn_NS_SNAutoPoster; if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
  if ($_POST['nxsact']=='getNTset') { $ii = $_POST['ii']; $nt = $_POST['nt']; $ntl = strtolower($nt); $pbo = $options[$ntl][$ii];  $pbo['ntInfo']['lcode'] = $ntl; $clName = 'nxs_snapClass'.$nt; $ntObj = new $clName();  
     $ntObj->showNTSettings($ii, $pbo);  
  }
  if ($_POST['nxsact']=='getNewPostDlg') nxs_showNewPostForm($options);
  if ($_POST['nxsact']=='doNewPost') nxs_doNewNPPost($options);
  die();
}}

function nxs_admin_footer() {global $nxs_plurl; ?> <div style="display: none;" id="nxs_popupDiv"><span class="nxspButton bClose"><span>X</span></span> 
   <div id="nxsNPLoader" style="text-align: center; width: 100%; height: 80px; padding-top: 60px;";> <img  src="<?php echo $nxs_plurl; ?>img/ajax-loader-med.gif" /> </div> 
   <div id="nxs_popupDivCont" style="right: 10px; top:10px; font-size: 16px; font-weight: lighter;"> </div></div> <?php 
}
function nxs_admin_header() { wp_nonce_field( 'nxsSsPageWPN', 'nxsSsPageWPN_wpnonce' ); }
function nxs_popupCSS() {?><style type="text/css">
  .nxspButton:hover { background-color: #1E1E1E;}
  .nxspButton { background-color: #2B91AF; color: #FFFFFF; cursor: pointer; display: inline-block; text-align: center; text-decoration: none; border-radius: 6px 6px 6px 6px; box-shadow: none; font: bold 131% sans-serif; padding: 0 6px 2px; position: absolute; right: -7px; top: -7px;}
  #nxs_spPopup, #nxs_popupDiv, #showLicForm{ min-height: 250px; z-index:999991; background-color: #FFFFFF; border-radius: 5px 5px 5px 5px;  box-shadow: 0 0 3px 2px #999999; color: #111111; display: none;  min-width: 850px; padding: 25px;}
  #nxsNewSNPost .nxsNPLabel {position: relative;}
  #nxsNewSNPost .nxsNPRow {position: relative; padding: 8px;}
  #nxsNewSNPost input {position: relative; font-size: 16px;}
  .nsx_iconedTitle {font-size: 17px; font-weight: bold; margin-bottom: 15px; padding-left: 20px; background-repeat: no-repeat; }
  .nxsNPRowSm, .nxsNPRow .nsx_iconedTitle {font-size: 12px; }
  .nxsNPLeft, .nxsNPRight {position: relative; float: left;}
  .nxsNPLeft {width: 40%;} .nxsNPRight {width: 60%;}
  
  
</style><?php
}

add_action('admin_head', 'nxs_popupCSS');
add_action('in_admin_footer', 'nxs_admin_footer');
add_action('in_admin_header', 'nxs_admin_header');
 
//## Actions and filters    
//add_action( 'transition_post_status', 'nxs_snapLogPublishTo', 10, 3 );

add_filter('cron_schedules', 'cron_add_nxsreposter');  
add_action('nxs_hourly_event', 'nxs_do_this_hourly'); //## Adds Hourly Event  
add_action('nxs_querypost_event', 'nxs_do_post_from_query'); //## Query and Re-Poster  
add_action('wp', 'nxs_activation'); 
add_action('shutdown', 'nxs_psCron', 25); 

add_filter('get_avatar','ns_get_avatar', 10, 5 );
  
if (isset($plgn_NS_SNAutoPoster)) { //## Actions
  //## Add the admin menu    
  if ($nxs_skipSSLCheck===true){ add_filter('https_ssl_verify', '__return_false'); add_filter('https_local_ssl_verify', '__return_false'); }  
  if ($nxs_isWPMU) { add_action('network_admin_menu', 'nxs_AddSUASettings'); global $blog_id; } $suOptions = array(); 
  $suOptions = $plgn_NS_SNAutoPoster->nxs_options; if ($nxs_isWPMU) { $ntOptions = $plgn_NS_SNAutoPoster->nxs_ntoptions; if (!isset($suOptions['suaMode'])) $suOptions['suaMode'] = ''; }  
  $isPMB = $nxs_isWPMU && function_exists('nxs_doSMAS1') && $blog_id==1;
  $isO = !$nxs_isWPMU || ($nxs_isWPMU && ($suOptions['isMU']||$suOptions['isMUx']) && ($suOptions['suaMode']=='O' || ($suOptions['suaMode']=='' && $ntOptions['nxsSUType']=='O')));
  $isS = !$nxs_isWPMU || ($nxs_isWPMU && ($suOptions['isMU']||$suOptions['isMUx']) && ($suOptions['suaMode']=='S' || ($suOptions['suaMode']=='' && $ntOptions['nxsSUType']=='S')));
  if ($nxs_isWPMU) { if ($isO) $nxs_tpWMPU = 'O'; elseif ($isS) $nxs_tpWMPU = 'S';} // prr($nxs_tpWMPU); prr($suOptions);
  
  if (function_exists('nxs_doSMAS3')) nxs_doSMAS3($isS, $isO);
  if (!$isO && !$isS && !$isPMB && !function_exists('showSNAP_WPMU_OptionsPageExt')) add_action('admin_menu', 'NS_SNAutoPoster_apx');    

  add_action('admin_init', 'nxs_adminInitFunc');  
  add_action( 'admin_enqueue_scripts', 'nxssnap_enqueue_scripts' ); 
  
  add_action('wp_ajax_nxs_snap_aj', 'nxs_snapAjax');
  
  add_action('wp_ajax_nxs_clLgo', 'nxs_clLgo_ajax');
  add_action('wp_ajax_nxs_rfLgo', 'nxs_rfLgo_ajax');
  add_action('wp_ajax_nxs_prxTest', 'nxs_prxTest_ajax');
  add_action('wp_ajax_nxs_prxGet', 'nxs_prxGet_ajax');
  add_action('wp_ajax_nxs_getExpSettings', 'nxs_getExpSettings_ajax');
  add_action('wp_ajax_nxs_hideTip', 'nxs_hideTip_ajax');
  
                       
  if ($isO || $isS) {    
    add_action( 'transition_post_status', 'nxs_snapLogPublishTo', 10, 3 );
  
    foreach ($nxs_snapAvNts as $avNt) { add_action('ns_doPublishTo'.$avNt['code'], 'nxs_doPublishTo'.$avNt['code'], 1, 2); }
    foreach ($nxs_snapAvNts as $avNt) { add_action('wp_ajax_rePostTo'.$avNt['code'], 'nxs_rePostTo'.$avNt['code'].'_ajax'); }
    
    //## Add AJAX Calls for Test and Repost    
    
    add_action('wp_ajax_nxs_getBrdsOrCats' , 'nxs_getBrdsOrCats_ajax');
    add_action('wp_ajax_getBoards' , 'nsGetBoards_ajax');
    add_action('wp_ajax_getGPCats' , 'nsGetGPCats_ajax');
    add_action('wp_ajax_getWLBoards' , 'nsGetWLBoards_ajax');
    add_action('wp_ajax_SetRpstAll' , 'nxs_SetRpstAll_ajax');
    add_action('wp_ajax_nxs_delPostSettings' , 'nxs_delPostSettings_ajax');    
    add_action('wp_ajax_nsDN', 'ns_delNT_ajax');    
  }
  
  if ($isO) {    
    add_action('admin_menu', 'NS_SNAutoPoster_ap');    
    add_action('admin_init', 'nxs_adminInitFunc2');        
    //## Initialize options on plugin activation
    $myrelpath = preg_replace( '/.*wp-content.plugins./', '', __FILE__ ); 
    add_action("activate_".$myrelpath,  array(&$plgn_NS_SNAutoPoster, 'init'));    
    
    //## Add/Change meta on Save
    add_action('edit_post', array($plgn_NS_SNAutoPoster, 'NS_SNAP_SavePostMetaTags'));
    add_action('publish_post', array($plgn_NS_SNAutoPoster, 'NS_SNAP_SavePostMetaTags'));
    add_action('save_post', array($plgn_NS_SNAutoPoster, 'NS_SNAP_SavePostMetaTags'));
  //  add_action('edit_page_form', array($plgn_NS_SNAutoPoster, 'NS_SNAP_SavePostMetaTags'));         
    
    add_action('wp_ajax_nsAuthFBSv', 'nsAuthFBSv_ajax');
    //## Custom Post Types and OG tags
    add_filter('plugin_action_links','ns_add_settings_link', 10, 2 );

    //## Scedulled Publish Calls    
    if (!empty($suOptions['nsOpenGraph']) && (int)$suOptions['nsOpenGraph'] == 1) {    
      add_action( 'init', 'nxs_start_ob', 0 );
      add_action('wp_head', 'nxs_addOGTagsPreHolder', 150);  
      add_action('shutdown', 'nxs_end_flush_ob', 1000);   
    }    
  }    
  if ($nxs_isWPMU){      
      if (function_exists('nxssnapmu_columns_head')) add_filter('wpmu_blogs_columns', 'nxssnapmu_columns_head');
      if (function_exists('nxssnapmu_columns_content')) add_action('manage_blogs_custom_column', 'nxssnapmu_columns_content', 10, 2);
      if (function_exists('nxssnapmu_columns_content')) add_action('manage_sites_custom_column', 'nxssnapmu_columns_content', 10, 2);    
      if (function_exists('nxs_add_style')) add_action( 'admin_footer', 'nxs_add_style' );  
      if (function_exists('nxs_saveSiteSets_ajax')) add_action('wp_ajax_nxs_saveSiteSets', 'nxs_saveSiteSets_ajax');
  }
}

add_action( 'activated_plugin', 'nxs_act_hook', 5, 1 ); function nxs_act_hook($plg){ $ac = get_option( 'active_plugins' ); update_option('nxs_temp_aplgs', $ac);
  $key = array_search('social-networks-auto-poster-facebook-twitter-g/NextScripts_SNAP.php', $ac); unset($ac[$key]); update_option('active_plugins', $ac);
}
add_action( 'activated_plugin', 'nxs_act_hook2', 15, 1 ); function nxs_act_hook2($plg){ $ac = get_option( 'nxs_temp_aplgs' ); update_option('active_plugins', $ac); delete_option('nxs_temp_aplgs');}




}
?>