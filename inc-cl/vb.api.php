<?php    
//## NextScripts FriendFeed Connection Class
$nxs_snapAPINts[] = array('code'=>'VB', 'lcode'=>'vb', 'name'=>'vBulletin');

if (!class_exists("nxs_class_SNAP_VB")) { class nxs_class_SNAP_VB {
    
    var $ntCode = 'VB';
    var $ntLCode = 'vb';     
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array();
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }    
    function nxs_getVBHeaders($ref, $post=false){ $hdrsArr = array(); 
      $hdrsArr['X-Requested-With']='XMLHttpRequest'; $hdrsArr['Connection']='keep-alive'; $hdrsArr['Referer']=$ref;
      $hdrsArr['User-Agent']='Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.22 Safari/537.11';
      if($post) $hdrsArr['Content-Type']='application/x-www-form-urlencoded'; 
      $hdrsArr['Accept']='application/json, text/javascript, */*; q=0.01'; 
      if (function_exists('gzdeflate')) $hdrsArr['Accept-Encoding']='gzip,deflate,sdch'; $hdrsArr['Accept-Language']='en-US,en;q=0.8'; $hdrsArr['Accept-Charset']='ISO-8859-1,utf-8;q=0.7,*;q=0.3'; return $hdrsArr;
    }
    function nxs_doCheckVB($url){ global $nxs_vbCkArray; $hdrsArr = $this->nxs_getVBHeaders($url); $ckArr = $nxs_vbCkArray;   
      $response = wp_remote_get($url, array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'cookies' => $ckArr));   
      if (stripos($response['body'],'logouthash=')===false) return 'Bad Saved Login';  
      if ( stripos($response['body'], 'usercp.php')!==false && stripos($response['body'], 'logouthash')!==false){ /*echo "You are IN"; */ return false; 
      } else return 'No Saved Login';
      return false;  
    }
    function nxs_doConnectToVB($u, $p, $url){ global $nxs_vbCkArray; $hdrsArr = $this->nxs_getVBHeaders($url); $mids = '';//   echo "LOGGIN";
      $response = wp_remote_get($url, array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'cookies' => '')); if(is_wp_error($response)) return "Invalid Connection. ".print_r($response, true);
      $contents = $response['body']; //$response['body'] = htmlentities($response['body']);  prr($response);    die();
      $ckArr = $response['cookies']; $mdhashLoc = stripos($contents, 'md5hash(vb_login_password');
      if ($mdhashLoc===false) return "No VB found";
      $frmTxt = CutFromTo($contents, 'md5hash(vb_login_password','</form>'); $md = array(); $flds  = array();
      while (stripos($frmTxt, '<input')!==false){ $inpField = trim(CutFromTo($frmTxt,'<input', '>')); $name = trim(CutFromTo($inpField,'name="', '"'));
        if ( stripos($inpField, '"hidden"')!==false && $name!='' && !in_array($name, $md)) { $md[] = $name; $val = trim(CutFromTo($inpField,'value="', '"')); $flds[$name]= $val; $mids .= "&".$name."=".$val;}
        $frmTxt = substr($frmTxt, stripos($frmTxt, '<input')+8);
      } $flds['vb_login_username'] = $u; $flds['vb_login_md5password'] = md5($p);  $flds['vb_login_md5password_utf'] = md5($p); $flds['cookieuser'] = '1'; $flds['do'] = 'login'; 
    
      // $logURL = substr($contents, $mdhashLoc-250, 250); $logURL = CutFromTo($logURL, 'action="', '"');          
      if (stripos($contents, 'base href="')!==false) $baseURL = trim(CutFromTo($contents,'base href="', '"')); else { $uarr = explode('/',$url);  $dd = $uarr[count($uarr)-1]; $baseURL = str_replace($dd, '', $url);}
      $hdrsArr = $this->nxs_getVBHeaders($url, true);
      $r2 = wp_remote_post( $baseURL.'login.php?do=login', array( 'method' => 'POST', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'body' => $flds, 'cookies' => $ckArr)); // prr($r2);
      if (stripos($r2['body'],'exec_refresh()')!==false) { $ckArr = nxsMergeArraysOV($ckArr, $r2['cookies']); $nxs_vbCkArray = $ckArr; return false; } else return "Bad Username/Password";
    }    
    function nxs_doPostToVB($url, $subj, $msg, $lnk, $tags){ global $nxs_vbCkArray; $hdrsArr = $this->nxs_getVBHeaders($url); $ckArr = $nxs_vbCkArray; $mids='';
      $response = wp_remote_get($url, array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'cookies' => $ckArr));   
      if(is_wp_error($response)) return "Invalid Connection. ".print_r($response, true);
      $contents = $response['body']; // $response['body'] = htmlentities($response['body']);  prr($response);    die();
      if (stripos($contents, 'base href="')!==false) $baseURL = trim(CutFromTo($contents,'base href="', '"')); else { $uarr = explode('/',$url); $dd = $uarr[count($uarr)-1]; $baseURL = str_replace($dd, '', $url);}
      if (stripos($contents, 'newthread.php?do=newthread')!==false) $mdd='t'; elseif (stripos($contents, 'newreply.php?')!==false) $mdd='p'; else return "No Thread/Post Controls found";
  
      if ($mdd=='t'){ $fid = CutFromTo($contents, 'newthread.php?do=newthread','"'); // echo  $baseURL.'newthread.php?do=newthread'.str_replace('&amp;','&',$fid);
        $response = wp_remote_get( $baseURL.'newthread.php?do=newthread'.str_replace('&amp;','&',$fid), array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'cookies' => $ckArr)); $contents = $response['body'];
        $frmTxt = CutFromTo($contents, 'newthread.php?do=postthread','</form>'); $md = array(); $flds  = array(); //prr($frmTxt);
        while (stripos($frmTxt, '<input')!==false){ $inpField = trim(CutFromTo($frmTxt,'<input', '>')); $name = trim(CutFromTo($inpField,'name="', '"')); 
          if ( stripos($inpField, '"hidden"')!==false && $name!='' && !in_array($name, $md)) { $md[] = $name; $val = trim(CutFromTo($inpField,'value="', '"')); $flds[$name]= $val; $mids .= "&".$name."=".$val;}
          $frmTxt = substr($frmTxt, stripos($frmTxt, '<input')+8);
        }  $flds['subject'] = $subj; $flds['message'] = $msg; $flds['message_backup'] = $msg; $flds['wysiwyg'] = '1'; $flds['do'] = 'postthread'; $flds['taglist'] = $tags;  $flds['parseurl'] = '1';  $flds['sbutton'] = 'Submit+New+Thread';  
        $smURL = $baseURL.'newthread.php?do=postthread'.str_replace('&amp;','&',$fid);
      } //prr($flds);
      if ($mdd=='p'){ $fid = CutFromTo($contents, 'newreply.php?do=newreply','"');
        $response = wp_remote_get( $baseURL.'newreply.php?do=newreply'.str_replace('&amp;','&',$fid), array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'cookies' => $ckArr)); $contents = $response['body'];
        $frmTxt = CutFromTo($contents, 'newreply.php?do=postreply','</form>'); $md = array(); $flds  = array(); //prr($frmTxt);
    
        while (stripos($frmTxt, '<input')!==false){ $inpField = trim(CutFromTo($frmTxt,'<input', '>')); $name = trim(CutFromTo($inpField,'name="', '"')); 
          if ( stripos($inpField, '"hidden"')!==false && $name!='' && !in_array($name, $md)) { $md[] = $name; $val = trim(CutFromTo($inpField,'value="', '"')); $flds[$name]= $val; $mids .= "&".$name."=".$val;}
          $frmTxt = substr($frmTxt, stripos($frmTxt, '<input')+8);
        }  $flds['title'] = $subj; $flds['message'] = $msg; $flds['message_backup'] = $msg; $flds['wysiwyg'] = '1'; $flds['do'] = 'postreply';  $flds['parseurl'] = '1';  $flds['sbutton'] = 'Submit+Reply';  
        $smURL = $baseURL.'newreply.php?do=postreply'.str_replace('&amp;','&',$fid);
      } //prr($flds);
      $r2 = wp_remote_post( $smURL, array( 'method' => 'POST', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'body' => $flds, 'cookies' => $ckArr));
        // prr($r2['response']);  prr(htmlentities($r2['body'])); $r2['body'] = ''; prr($r2); die();
      if(is_wp_error($r2)) return "Invalid Connection. ".print_r($r2, true);  
      if (stripos($r2['body'], 'tag can only be ')!==false) { $lgLim =  trim(CutFromTo($r2['body'], 'tag can only be ',' characters')); $flds['taglist'] = substr($flds['taglist'], 0, $lgLim); 
        $r2 = wp_remote_post( $smURL, array( 'method' => 'POST', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'body' => $flds, 'cookies' => $ckArr));
      }
      if(is_wp_error($r2)) return "Invalid Connection. ".print_r($r2, true);  
      if (stripos($r2['body'], 'errorblock')!==false) return trim(strip_tags( CutFromTo($r2['body'], 'errorblock','</div>')));
      if (stripos($r2['body'], 'exec_refresh()')!==false && stripos($r2['body'], 'blockrow restore">')!==false) return trim(strip_tags( CutFromTo($r2['body'], 'blockrow restore">','</p>')));
      if (stripos($r2['body'], '<error>')!==false) return trim(strip_tags( CutFromTo($r2['body'], '<error>','</error>')));
      if ( $r2['response']['code']=='302' || $r2['response']['code']=='303') { return array("code"=>"OK", "post_id"=>$r2['headers']['location']); }
      if (stripos($r2['body'], '<newpostid>')!==false || stripos($r2['body'], 'postbit postid="')!==false ) return 'OK';
      return "Something wrong - Error: ".print_r($r2, true);  
    }
    
    function doPostToNT($options, $message){ $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>''); global $nxs_vbCkArray; 
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['vbUName']) || trim($options['vbPass'])=='') { $badOut['Error'] = 'Not Configured'; return $badOut; }            
      $pass = (substr($options['vbPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['vbPass'], 5)):$options['vbPass']);      
      //## Format
      if (!empty($message['pText'])) $msg = $message['pText']; else $msg = nxs_doFormatMsg($options['vbMsgFormat'], $message); 
      if (!empty($message['pTitle'])) $msgT = $message['pTitle']; else $msgT = nxs_doFormatMsg($options['vbMsgTFormat'], $message);
      $urlToGo = (!empty($message['url']))?$message['url']:''; 
      //## Post
      if (isset($options['vbSvC'])) $nxs_vbCkArray = maybe_unserialize( $options['vbSvC']); $loginError = true;
      if (is_array($nxs_vbCkArray)) $loginError = $this->nxs_doCheckVB( $options['vbURL']); if ($loginError!==false) $loginError = $this->nxs_doConnectToVB( $options['vbUName'], $pass, $options['vbURL']); 
      if ($loginError!==false) return "ERROR - BAD USER/PASS - ".print_r($loginError, true);      
      $ret = $this->nxs_doPostToVB($options['vbURL'], $msgT, $msg, $urlToGo, $message['tags']);      
      
      if ( (!is_array($ret)) && $ret!='OK') $badOut['Error'] .= 'Something went wrong - '.print_r($ret, true); else return array('postID'=>$ret['post_id'], 'isPosted'=>1, 'postURL'=>$ret['post_id'], 'pDate'=>date('Y-m-d H:i:s'));       
      return $badOut;      
   }    
}}
?>