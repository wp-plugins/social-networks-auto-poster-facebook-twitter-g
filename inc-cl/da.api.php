<?php    
//## NextScripts deviantART Connection Class

/* 
1. Options

nName - Nickname of the account [Optional] (Presentation purposes only - No affect on functionality)
rdUName - Reddit User Name
rdPass - Reddit User Passord
rdSubReddit - Name of the Sub-Reddit

rdTitleFormat
rdTextFormat

2. Post Info

url
title - [up to 300 characters long] - title of the submission
text

*/
$nxs_snapAPINts[] = array('code'=>'DA', 'lcode'=>'da', 'name'=>'deviantART');

if (!function_exists("doConnectToDeviantART")) { function doConnectToDeviantART($unm, $pass){ }}

if (!class_exists('nxsAPI_DA')){class nxsAPI_DA{ var $ck = array(); var $mh = '';  var $debug = false;
    function headers($ref, $org='', $post=false, $aj=false){  $hdrsArr = array(); 
 $hdrsArr['Cache-Control']='max-age=0'; $hdrsArr['Connection']='keep-alive'; $hdrsArr['Referer']=$ref;
 $hdrsArr['User-Agent']='Mozilla/5.0 (iPad; CPU OS 7_0 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53';
 if($post==true) $hdrsArr['Content-Type']='application/x-www-form-urlencoded'; 
 if($aj==true) $hdrsArr['X-Requested-With']='XMLHttpRequest'; 
 if ($org!='') $hdrsArr['Origin']=$org; 
 $hdrsArr['Accept']='text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';// $hdrsArr['DNT']='1';
 if (function_exists('gzdeflate')) $hdrsArr['Accept-Encoding']='gzip,deflate,sdch'; 
 $hdrsArr['Accept-Language']='en-US,en;q=0.8'; return $hdrsArr;         
    }
    function check(){ $ck = $this->ck;  if (!empty($ck) && is_array($ck)) { $hdrsArr = $this->headers('https://www.deviantart.com'); if ($this->debug) echo "[DA] Checking....;<br/>\r\n";
        $rep = nxs_remote_get('https://www.deviantart.com', array('headers' => $hdrsArr, 'httpversion' => '1.1', 'cookies' => $ck)); 
        if (is_wp_error($rep)) {  $badOut = print_r($rep, true)." - ERROR https://www.deviantart.com is not accessible. "; return $badOut; }  
        $ck2 =  $rep['cookies']; for($i=0;$i<count($ck);$i++) if ($ck[$i]->name=='userinfo') $ck[$i]->value = urlencode($ck2[0]->value);  $this->ck = $ck;
        if (is_nxs_error($rep)) return false; $contents = $rep['body']; //if ($this->debug) prr($contents);
        return stripos($contents, 'https://www.deviantart.com/users/logout')!==false;
      } else return false;
    }
    function connect($u,$p){ $badOut = 'Error: ';
        //## Check if alrady IN
        if (!$this->check()){ if ($this->debug) echo "[DA] NO Saved Data;<br/>\r\n";
          $url = "https://www.deviantart.com/users/login";  $hdrsArr = $this->headers('http://www.deviantart.com/');
          $rep = wp_remote_get($url, array( 'headers' => $hdrsArr, 'httpversion' => '1.1')); if (is_wp_error($rep)) {  $badOut = print_r($rep, true)." - ERROR Login 1"; return $badOut; }  $ck =  $rep['cookies'];
          $rTok = CutFromTo($rep['body'], 'name="validate_token" value="', '"'); $rKey = CutFromTo($rep['body'], 'name="validate_key" value="', '"'); $ck[0]->value = urlencode($ck[0]->value);
          $hdrsArr = $this->headers('https://www.deviantart.com/users/login', 'https://www.deviantart.com/', true);
          $flds = array('ref' => 'https://www.deviantart.com/users/loggedin', 'username' => $u, 'password' => $p, 'remember_me' => '1', 'validate_token' => $rTok, 'validate_key' => $rKey);
          $response = wp_remote_post( $url, array( 'method' => 'POST', 'httpversion' => '1.1', 'timeout' => 45, 'redirection' => 0, 'cookies' => $ck, 'headers' => $hdrsArr, 'body' => $flds));  
          if (is_wp_error($response)) {  $badOut = print_r($response, true)." - ERROR Login 2"; return $badOut; }  
          $ck =  $response['cookies']; for($i=0;$i<4;$i++) $ck[$i]->value = urlencode($ck[$i]->value);   
          if (isset($response['headers']['location']) && stripos($response['headers']['location'], 'wrong-password')!==false  ) {  $badOut = "Wrong Password - ERROR"; return $badOut; }  
          if (isset($response['headers']['location']) && ( $response['headers']['location']=='http://www.deviantart.com' || $response['headers']['location']=='https://www.deviantart.com/users/loggedin')) { 
            $hdrsArr = $this->headers('http://www.deviantart.com'); $rep = wp_remote_get( 'http://www.deviantart.com', array( 'headers' => $hdrsArr, 'httpversion' => '1.1', 'cookies' => $ck));// die();  prr($rep);     
            if (is_wp_error($rep)) {  $badOut = print_r($rep, true)." - ERROR  Login 3"; return $badOut; } $mh = CutFromTo($rep['body'], 'Your Account</b><br><a href="', '"'); $mh = str_ireplace('/journal/', '', $mh);
            $ck2 =  $rep['cookies']; for($i=0;$i<count($ck);$i++) if ($ck[$i]->name=='userinfo') $ck[$i]->value = urlencode($ck2[0]->value);  $this->ck = $ck; $this->mh = $mh; return false;
          } else  $badOut = print_r($response, true)." - ERROR  Login 4"; return $badOut; 
        } else { if ($this->debug) echo "[DA] Saved Data is OK;<br/>\r\n"; return false; }
    }
    function post($post){ $ck = $this->ck; $mh = $this->mh; $hdrsArr = $this->headers('http://www.deviantart.com/'); $badOut = '';
      $advSets = array( 'headers' => $hdrsArr, 'cookies' => $ck); // prr($advSets);
      $rep = wp_remote_get( $mh.'/journal/?edit', $advSets); if (is_wp_error($rep)) {  $badOut = print_r($rep, true)." - ERROR Post 1"; return $badOut; } 
      $contents = CutFromTo($rep['body'], '<h3 class="journal-editor-create">', '</form>'); // prr($contents);      
      $md = array();  while (stripos($contents, '"hidden"')!==false){$contents = substr($contents, stripos($contents, '"hidden"')+8); $name = trim(CutFromTo($contents,'name="', '"'));
        if (!in_array($name, $md)) { $md[] = $name; $val = trim(CutFromTo($contents,'value="', '"')); $flds[$name]= urldecode (nxs_decodeEntities($val)); }
      } $flds['subject'] = nsTrnc(nxs_decodeEntities($post['title']), 50); $flds['body'] = trim($post['text']);  $flds['song'] = '';       
      $flds['game'] = ''; $flds['book'] = ''; $flds['food'] = ''; $flds['movie'] = ''; $flds['drink'] = ''; $flds['flip'] = '0'; $flds['featured'] = '1'; 
      $flds['portal'] = '1'; $flds['skinlabel'] = 'No+skin'; $flds['jheader'] = ''; $flds['jcss'] = ''; $flds['jfooter'] = '';       
      $ck2 =  $rep['cookies']; for($i=0;$i<count($ck);$i++) if ($ck[$i]->name=='userinfo') $ck[$i]->value = urlencode($ck2[0]->value);       
      $fldsOut = $flds;  $ckk = array(); for($i=0;$i<count($ck);$i++)  if ($ck[$i]->name=='userinfo' || $ck[$i]->name=='auth') $ckk[] = $ck[$i]; $ck = $ckk; sleep(16); //## Important.      
      $hdrsArr = $this->headers($mh.'/journal/?edit', $mh, true); $advSets = array( 'method' => 'POST', 'httpversion' => '1.1', 'timeout' => 45, 'redirection' => 0, 'headers' => $hdrsArr, 'body' => $fldsOut, 'cookies' => $ck); 
      $response = wp_remote_post($mh.'/journal/?edit', $advSets); // prr($advSets); prr($response);
      if ($response['response']['code']=='200' && stripos($response['body'],'field_error')!==false) { $eRRMsg = CutFromTo($response['body'],'field_error', '</div>');  $eRRMsg = trim(strip_tags(CutFromTo($eRRMsg."|GGG|",'>', '|GGG|')));
           $badOut = "POST Error: ".$eRRMsg; return $badOut;
      }      
      if ($response['response']['code']=='302') { $hdrsArr = $this->headers($mh);
          $rep = wp_remote_get(  $mh.'/journal/', array( 'headers' => $hdrsArr, 'cookies' => $ck)); 
          $daNewPostURL = CutFromTo($rep['body'], 'a data-deviationid="', '</a>'); $daNewPostURL = CutFromTo($daNewPostURL, 'href="', '"'); $daNewPostID = CutFromTo($rep['body'], 'a data-deviationid="', '"');          
         return array('postID'=>$daNewPostID, 'isPosted'=>1, 'postURL'=>$daNewPostURL, 'pDate'=>date('Y-m-d H:i:s'));          
      } else { $badOut .= 'Somethibng is not right';
        return $badOut;
      }
      return $badOut;         
    }
    
} }

if (!class_exists("nxs_class_SNAP_DA")) { class nxs_class_SNAP_DA {
    
    var $ntCode = 'DA';
    var $ntLCode = 'da';
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array(); // return false;
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }       
    
    function doPostToNT($options, $message){ global $nxs_urlLen; $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>'');
      //## Check settings
      if (!is_array($options)) { $badOut = 'No Options'; return $badOut; }      
      if (!isset($options['daUName']) || trim($options['daUName'])=='' || !isset($options['daPass']) || trim($options['daPass'])=='') { $badOut = 'No username/password Found'; return $badOut; }      
      //## Format Post
      if (!empty($message['pTitle'])) $title = $message['pTitle']; else $title = nxs_doFormatMsg($options['daTitleFormat'], $message); $title = nsTrnc($title, 300);  
      if (!empty($message['pText'])) $text = $message['pText']; else $text = nxs_doFormatMsg($options['daTextFormat'], $message);     
      //## Make Post            
      if (!empty($options['ck'])) $ck = maybe_unserialize($options['ck']); if (!empty($options['mh'])) $mh = maybe_unserialize($options['mh']); 
      $pass = substr($options['daPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['daPass'], 5)):$options['uPass'];
      $nt = new nxsAPI_DA(); $nt->debug = false; if (!empty($ck)) $nt->ck = $ck; if (!empty($mh)) $nt->mh = $mh; $loginErr = $nt->connect($options['daUName'], $pass); 
      if (!$loginErr) { $post = array('title'=>$title, 'text'=>$text ); $ret = $nt->post($post);         
        if (is_array($ret)) { $ret['ck'] = $nt->ck; $ret['mh'] = $nt->mh; return $ret;  } else return print_r($ret, true);
      } else return print_r($loginErr, true);  
    }  
}}
?>