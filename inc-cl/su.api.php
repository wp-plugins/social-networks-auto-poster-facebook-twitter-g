<?php    
//## NextScripts FriendFeed Connection Class
$nxs_snapAPINts[] = array('code'=>'SU', 'lcode'=>'su', 'name'=>'StumbleUpon');

if (!class_exists("nxs_class_SNAP_SU")) { class nxs_class_SNAP_SU {
    
    var $ntCode = 'SU';
    var $ntLCode = 'su';     
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array();
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }    
    function nxs_getSUHeaders($ref, $post=false, $xhr=true){ $hdrsArr = array(); 
      if ($xhr) $hdrsArr['X-Requested-With']='XMLHttpRequest'; 
      $hdrsArr['Connection']='keep-alive'; $hdrsArr['Referer']=$ref;
      $hdrsArr['User-Agent']='Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)';
      if($post) $hdrsArr['Content-Type']='application/x-www-form-urlencoded'; 
      if ($xhr) $hdrsArr['Accept']='application/json, text/javascript, */*; q=0.01'; else $hdrsArr['Accept']='text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
      $hdrsArr['Origin']='http://www.stumbleupon.com';
      if (function_exists('gzdeflate')) $hdrsArr['Accept-Encoding']='gzip,deflate,sdch'; $hdrsArr['Accept-Language']='en-US,en;q=0.8'; $hdrsArr['Accept-Charset']='ISO-8859-1,utf-8;q=0.7,*;q=0.3'; return $hdrsArr;
    }
    function nxs_doCheckSU(){ global $nxs_suCkArray; $hdrsArr = $this->nxs_getSUHeaders('https://www.stumbleupon.com/submit'); $ckArr = $nxs_suCkArray;   
      $response = wp_remote_get('http://www.stumbleupon.com/submit', array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'cookies' => $ckArr));   
      $response['body'] = htmlentities($response['body'], ENT_COMPAT, "UTF-8"); // $response['body'] = htmlentities($response['body']);  prr($response);  die();
      if (isset($response['headers']['location']) && $response['headers']['location']=='/submit/visitor') return 'Bad Saved Login';  
      if ( $response['response']['code']=='200' && stripos($response['body'], 'Add a New Page')!==false){     
        /*echo "You are IN"; */ return false; 
      } else return 'No Saved Login';
      return false;  
    }
    function nxs_doConnectToSU($u, $p){ global $nxs_suCkArray; $hdrsArr = $this->nxs_getSUHeaders('https://www.stumbleupon.com/', false, false); //   echo "LOGGIN";
      $response = wp_remote_get('https://www.stumbleupon.com/login', array('headers' => $hdrsArr)); $p = substr($p, 0, 16);
      if (is_wp_error($response)) { nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($response, true), ''); return "Connection ERROR. Please see log";}
      $contents = $response['body']; $ckArr = $response['cookies']; //$response['body'] = htmlentities($response['body']);  prr($response);    die();       
      $frmTxt = CutFromTo($contents, '<form id="login-form"','</form>'); $md = array(); $flds  = array();  $mids = '';// prr($frmTxt); 
      while (stripos($frmTxt, '<input')!==false){ $inpField = trim(CutFromTo($frmTxt,'<input', '>')); $name = trim(CutFromTo($inpField,'name="', '"'));
        if ( stripos($inpField, '"hidden"')!==false && $name!='' && !in_array($name, $md)) { $md[] = $name; $val = trim(CutFromTo($inpField,'value="', '"')); $flds[$name]= $val; $mids .= "&".$name."=".$val;}
        $frmTxt = substr($frmTxt, stripos($frmTxt, '<input')+8);
      } $flds['user'] = $u; $flds['pass'] = $p; $flds['remember'] = 'true'; $flds['nativeSubmit'] = '0'; $flds['_method'] = 'create'; $flds['_output'] = 'Json';    
      $hdrsArr = $this->nxs_getSUHeaders('https://www.stumbleupon.com/login', true, true);
      $r2 = wp_remote_post( 'https://www.stumbleupon.com/login?_nospa=true', array( 'method' => 'POST', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'body' => $flds, 'cookies' => $ckArr));
      if (is_wp_error($r2)) { nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($r2, true), ''); return "Connection ERROR. Please see log";}
      $ckArr = nxsMergeArraysOV($ckArr, $r2['cookies']); //prr($flds); prr($ckArr); prr($r2); prr($ckArr);   
      if (is_array($r2) && !empty($r2['response']['code']) && $r2['response']['code']=='302') { $hdrsArr = $this->nxs_getSUHeaders('https://www.stumbleupon.com/login', false, false); 
        $r2 = wp_remote_get( 'https://www.stumbleupon.com/settings/profile/', array( 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'cookies' => $ckArr)); 
        if (is_wp_error($r2)) { nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($r2, true), ''); return "Connection ERROR. Please see log";} //prr($r2);
        if (stripos($r2['body'], '<a href="#" class="logout ')!==false) { $nxs_suCkArray = $ckArr; return false; }
      } $resp = json_decode($r2['body'], true);  
      if ($resp['_success']=='1') { $ckArr = nxsMergeArraysOV($ckArr, $r2['cookies']); $nxs_suCkArray = $ckArr; return false; } elseif (isset($resp['_reason'])) { return $resp['_reason']; } else return "ERROR";   
    }
    function nxs_doPostToSU($msg, $lnk, $cat, $tags, $nsfw=false){ global $nxs_suCkArray; $r2 = wp_remote_get($lnk); 
      $hdrsArr = $this->nxs_getSUHeaders('https://www.stumbleupon.com/submit', false, false); $ckArr = $nxs_suCkArray;   
      $response = wp_remote_get('https://www.stumbleupon.com/submit', array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'cookies' => $ckArr));   
      $ckArr2 = nxsMergeArraysOV($ckArr, $response['cookies']); //$nxs_suCkArray = $ckArr;
  
      $contents = $response['body']; //$response['body'] = htmlentities($response['body']);  prr($response);   
      //$ckArr = nxsMergeArraysOV($ckArr, $response['cookies']);  
      $hdrsArr = $this->nxs_getSUHeaders('https://www.stumbleupon.com/submit', true);
      $frmTxt = CutFromTo($contents, '<form method="post" id="submit-form"','</form>'); $md = array(); $flds  = array(); $mids = ''; // prr($contents);
      while (stripos($frmTxt, '<input')!==false){ $inpField = trim(CutFromTo($frmTxt,'<input', '>')); $name = trim(CutFromTo($inpField,'name="', '"'));
        if ( stripos($inpField, '"hidden"')!==false && $name!='' && !in_array($name, $md)) { $md[] = $name; $val = trim(CutFromTo($inpField,'value="', '"')); $flds[$name]= $val; $mids .= "&".$name."=".$val;}
        $frmTxt = substr($frmTxt, stripos($frmTxt, '<input')+8);
      } $flds['url'] = $lnk; $flds['review'] = $msg; $flds['tags'] = $cat; $flds['nsfw'] = $nsfw?'true':'false'; $flds['user-tags'] = $tags;  $flds['_output'] = 'Json';  $flds['_method'] = 'create';  $flds['language'] = 'EN'; 
    
      $r2 = wp_remote_post('https://www.stumbleupon.com/submit', array('method' => 'POST', 'timeout' => 45, 'redirection' => 0, 'headers' => $hdrsArr, 'body' => $flds, 'cookies' => $ckArr)); 
      $resp = json_decode($r2['body'], true); 
  
      if ( isset($resp['_reason']) && is_array($resp['_reason']) && count($resp['_reason'])>0 && stripos($resp['_reason'][0]['message'], 'Failed to add URL')!==false) { sleep(5);
        $r2 = wp_remote_post('https://www.stumbleupon.com/submit', array('method' => 'POST', 'timeout' => 45, 'redirection' => 0, 'headers' => $hdrsArr, 'body' => $flds, 'cookies' => $ckArr)); 
        $resp = json_decode($r2['body'], true);
      }
  
      if (stripos($resp['_error'], 'Invalid token')!==false) { // In case we got the Wrong Cookies
        $r2 = wp_remote_post('https://www.stumbleupon.com/submit', array('method' => 'POST', 'timeout' => 45, 'redirection' => 0, 'headers' => $hdrsArr, 'body' => $flds, 'cookies' => $ckArr2)); 
        $resp = json_decode($r2['body'], true);
    
        if (stripos($resp['_reason'][0]['message'], 'Failed to add URL')!==false) { sleep(5);
          $r2 = wp_remote_post('https://www.stumbleupon.com/submit', array('method' => 'POST', 'timeout' => 45, 'redirection' => 0, 'headers' => $hdrsArr, 'body' => $flds, 'cookies' => $ckArr2)); 
          $resp = json_decode($r2['body'], true); // prr($flds);  prr($resp); //nxs_addToLogN('SU', 'E', '-=DBG=- '.print_r($resp, true)." - #####", $extInfo);
        }    
      } 
  
      if (isset($resp['discovery']['publicid'])) $pageID = $resp['discovery']['publicid']; elseif (isset($resp['discovery']['url']['publicid']))$pageID = $resp['discovery']['url']['publicid'];   
      if ($resp['_success']=='1') { $ckArr = nxsMergeArraysOV($ckArr, $r2['cookies']); $nxs_suCkArray = $ckArr; return array("code"=>"OK", "post_id"=>$pageID); } 
        elseif (isset($resp['_reason'])) { $resp['_reason']['NXS_FIELDS'] = $flds; $resp['_reason']['NXS_RESP'] = $resp;  return $resp['_reason']; } else return "ERROR".print_r($resp, true);   
    }

    function doPostToNT($options, $message){ global $nxs_suCkArray; $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>'');
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['suUName']) || trim($options['suPass'])=='') { $badOut['Error'] = 'Not Configured'; return $badOut; }            
      $pass = (substr($options['suPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['suPass'], 5)):$options['suPass']);      
      //## Format
      if (!empty($message['pText'])) $msg = $message['pText']; else $msg = nxs_doFormatMsg($options['suMsgFormat'], $message);  $urlToGo = (!empty($message['url']))?$message['url']:''; $tags = $message['tags'];
      
      if (isset($options['suSvC'])) $nxs_suCkArray = maybe_unserialize( $options['suSvC']); $loginError = true;
      if (is_array($nxs_suCkArray)) $loginError = $this->nxs_doCheckSU(); if ($loginError!=false) $loginError = $this->nxs_doConnectToSU($options['suUName'], $pass);       
      if ($loginError!==false) { $badOut['Error'] = print_r($loginError, true)." - BAD USER/PASS"; return $badOut; }  
      
      $ret = $this->nxs_doPostToSU($msg, $urlToGo, $options['suCat'], $tags, $options['nsfw']=='1'); // $extInfo .= "++".$msg."|".$link."|".$options['suCat']."|".$tags."|".$options['nsfw'];      
      
      if ($ret=='OK') $ret = array("code"=>"OK", "post_id"=>'');
      if ( (!is_array($ret)) && $ret!='OK') { $badOut['Error'] .= 'Something went wrong - '.print_r($ret, true);  } 
        elseif (isset($ret['code']) && $ret['code']=='OK') return array('isPosted'=>'1', 'postID'=>$ret['post_id'], 'postURL'=>'http://www.stumbleupon.com/content/'.$ret['post_id'].'/comments', 'pDate'=>date('Y-m-d H:i:s')); else $badOut['Error'] .= 'Error - '.print_r($ret, true);
      return $badOut;      
   }    
}}
?>