<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'DI', 'lcode'=>'di', 'name'=>'Diigo (Beta)');

if (!class_exists("nxs_snapClassDI")) { class nxs_snapClassDI {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl, $nxs_plurl, $nxsOne; $code = 'DI'; $lcode = 'di'; wp_nonce_field( 'ns'.$code, 'ns'.$code.'_wpnonce' ); ?>
    <hr/><div class="nsx_iconedTitle" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $lcode; ?>16.png);">Diigo Settings:           
            <?php $cgpo = count($ntOpts); $mgpo = 1+max(array_keys($ntOpts)); $nxsOne .= "&g=1"; ?>            
              <div class="nsBigText">You have <?php echo $cgpo=='0'?'No':$cgpo; ?> Diigo account<?php if ($cgpo!=1){ ?>s<?php } ?>  </div></div> 
              <?php  //if (function_exists('nxs_doSMAS1')) nxs_doSMAS1($this, $mgpo); else nxs_doSMAS('Google+', 'GP'.$mgpo); ?>
              <?php foreach ($ntOpts as $indx=>$options){ if (trim($options['nName']=='')) $options['nName'] =$options['diUName']; ?>
                <p style="margin: 0px;margin-left: 5px;">
                  <input value="1" id="apDoDI" name="di[<?php echo $indx; ?>][apDoDI]" onchange="doShowHideBlocks('DI');" type="checkbox" <?php if ((int)$options['doDI'] == 1) echo "checked"; ?> /> 
                  <strong>Auto-publish your Posts to your Diigo Account <i style="color: #005800;"><?php if($options['nName']!='') echo "(".$options['nName'].")"; ?></i>  </strong>                                         
                  &nbsp;&nbsp;<a id="doDI<?php echo $indx; ?>A" href="#" onclick="doShowHideBlocks2('DI<?php echo $indx; ?>');return false;">[Show Settings]</a> &nbsp;&nbsp;
                  <a href="#" onclick="doDelAcct('di','<?php echo $indx; ?>', '<?php echo $options['diUName']; ?>');return false;">[Remove Account]</a>
                </p>            
                <?php $this->showNTSettings($indx, $options);             
              } ?>            
            <?php 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($mgpo){ $options = array('nName'=>'', 'doDI'=>'1', 'diUName'=>'', 'diInclTags'=>'1', 'diAttch'=>'', 'diAPIKey'=>'', 'diPass'=>''); $this->showNTSettings($mgpo, $options, true);}
  
  
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; ?>
            <div id="doDI<?php echo $ii; ?>Div" <?php if ($isNew){ ?>class="clNewNTSets"<?php } ?> style="max-width: 1000px; background-color: #EBF4FB; background-image: url(<?php echo $nxs_plurl; ?>img/di-bg.png);  background-position:90% 10%; background-repeat: no-repeat; margin: 10px; border: 1px solid #808080; padding: 10px; display:none;">     <input type="hidden" name="apDoSDI<?php echo $ii; ?>" value="0" id="apDoSDI<?php echo $ii; ?>" />          
            
             <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/di16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-diigo-social-networks-auto-poster-wordpress/">Detailed Diigo Installation/Configuration Instructions</a></div>
            
            <div style="width:100%;"><strong>Account Nickname:</strong> <i>Just so you can easely identify it</i> </div><input name="di[<?php echo $ii; ?>][nName]" id="dinName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /><br/>
            <?php echo nxs_addQTranslSel('di', $ii, $options['qTLng']); ?>
            <?php echo nxs_addPostingDelaySel('di', $ii, $options['nHrs'], $options['nMin']); ?>
                        <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText">Diigo API Key:</strong> <span style="font-size: 11px; margin: 0px;">Get it from <a target="_blank" href="http://www.diigo.com/api_keys/">http://www.diigo.com/api_keys</a>.</span></div>
                <input name="di[<?php echo $ii; ?>][apDIAPIKey]" id="apDIAPIKey" style="width: 60%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['diAPIKey'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />  <br/> 
            </div>   
           <div style="width:100%;"><strong>Diigo Username:</strong> </div><input name="di[<?php echo $ii; ?>][apDIUName]" id="apDIUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['diUName'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />                
            <div style="width:100%;"><strong>Diigo Password:</strong> </div><input name="di[<?php echo $ii; ?>][apDIPass]" id="apDIPass" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($options['diPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['diPass'], 5)):$options['diPass'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />  <br/>                
            
            <?php if ($isNew) { ?> <input type="hidden" name="di[<?php echo $ii; ?>][apDoDI]" value="1" id="apDoNewDI<?php echo $ii; ?>" /> <?php } ?>
            <br/>            
            
            <p style="margin-bottom: 20px;margin-top: 5px;"><input value="1"  id="diInclTags" type="checkbox" name="di[<?php echo $ii; ?>][diInclTags]"  <?php if ((int)$options['diInclTags'] == 1) echo "checked"; ?> /> 
              <strong>Post with tags</strong> Tags from the blogpost will be auto posted to Diigo                                
            </p>
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText">Post Title Format</strong> (<a href="#" id="apDIMsgTFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apDIMsgTFrmt<?php echo $ii; ?>'); return false;">Show format info</a>)</div>
              <input name="di[<?php echo $ii; ?>][apDIMsgTFrmt]" id="apDIMsgTFrmt" style="width: 50%;" value="<?php if ($isNew) echo "%TITLE%"; else _e(apply_filters('format_to_edit', htmlentities($options['diMsgTFormat'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster'); ?>"  onfocus="mxs_showFrmtInfo('apDIMsgTFrmt<?php echo $ii; ?>');" /><?php nxs_doShowHint("apDIMsgTFrmt".$ii); ?>
            </div><br/> 
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText">Post Text Format</strong> (<a href="#" id="apDIMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apDIMsgFrmt<?php echo $ii; ?>'); return false;">Show format info</a>)</div>
              <input name="di[<?php echo $ii; ?>][apDIMsgFrmt]" id="apDIMsgFrmt" style="width: 50%;" value="<?php if ($isNew) echo "%TEXT%"; else _e(apply_filters('format_to_edit', htmlentities($options['diMsgFormat'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster'); ?>"  onfocus="mxs_showFrmtInfo('apDIMsgFrmt<?php echo $ii; ?>');" /><?php nxs_doShowHint("apDIMsgFrmt".$ii); ?>
            </div><br/>    
            
            <?php if ($options['diPass']!='') { ?>
            <?php wp_nonce_field( 'rePostToDI', 'rePostToDI_wpnonce' ); ?>
            <b>Test your settings:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('DI', '<?php echo $ii; ?>'); return false;">Submit Test Post to Diigo</a>      
               
            <?php } 
            
            ?><div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div></div><?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'DI'; $lcode = 'di'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apDIUName']) && $pval['apDIUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apDIUName']))   $options[$ii]['diUName'] = trim($pval['apDIUName']);
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apDIPass']))    $options[$ii]['diPass'] = 'n5g9a'.nsx_doEncode($pval['apDIPass']); else $options[$ii]['diPass'] = '';  
        if (isset($pval['apDIAPIKey'])) $options[$ii]['diAPIKey'] = trim($pval['apDIAPIKey']);                                                  
        
        if (isset($pval['diInclTags']))     $options[$ii]['diInclTags'] = $pval['diInclTags']; else $options[$ii]['diInclTags'] = 0;
        if (isset($pval['apDIMsgTFrmt'])) $options[$ii]['diMsgTFormat'] = trim($pval['apDIMsgTFrmt']);
        if (isset($pval['apDIMsgFrmt'])) $options[$ii]['diMsgFormat'] = trim($pval['apDIMsgFrmt']);
        if (isset($pval['apDoDI']))      $options[$ii]['doDI'] = $pval['apDoDI']; else $options[$ii]['doDI'] = 0; 
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      }
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID;
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapDI', true));   if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); $doDI = $ntOpt['doDI'];   
        $isAvailDI =  $ntOpt['diUName']!='' && $ntOpt['diPass']!=''; $diMsgFormat = $ntOpt['diMsgFormat']; $diMsgTFormat = $ntOpt['diMsgTFormat'];      
      ?>  
      <tr><th style="text-align:left;" colspan="2">
      <?php if ($isAvailDI) { ?><input class="nxsGrpDoChb" value="1" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="di[<?php echo $ii; ?>][SNAPincludeDI]" <?php if (($post->post_status == "publish" && $ntOpt['isPosted'] == '1') || ($post->post_status != "publish" && ((int)$doDI == 1)) ) echo 'checked="checked" title="def"';  ?> /> <?php } ?>
      
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/di16.png);">Diigo - publish to (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailDI) { ?><input alt="<?php echo $ii; ?>" style="float: right;" type="button" class="button" name="rePostToDI_repostButton" id="rePostToDI_button" value="<?php _e('Repost to Diigo', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToDI', 'rePostToDI_wpnonce' ); } ?>
                </td></tr>                
                
                <?php if (!$isAvailDI) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Diigo Account to AutoPost to Diigo</b>
                <?php } elseif ($post->post_status != "publish") { ?> 
               
       <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;"><?php _e('Text Format:', 'NS_SPAP') ?></th>
        <td><input value="<?php echo $diMsgTFormat ?>" type="text" name="di[<?php echo $ii; ?>][SNAPformatT]" size="60px" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apDIMsgTFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apDIMsgTFrmt".$ii); ?></td></tr>
                
      <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;"><?php _e('Text Format:', 'NS_SPAP') ?></th>
        <td><input value="<?php echo $diMsgFormat ?>" type="text" name="di[<?php echo $ii; ?>][SNAPformat]" size="60px" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apDIMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apDIMsgFrmt".$ii); ?></td></tr>
                <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['diMsgFormat'] = $pMeta['SNAPformat']; 
     if (isset($pMeta['SNAPformatT'])) $optMt['diMsgTFormat'] = $pMeta['SNAPformatT']; 
     if (isset($pMeta['SNAPincludeDI'])) $optMt['doDI'] = $pMeta['SNAPincludeDI'] == 1?1:0; else { if (isset($pMeta['SNAPformat']))  $optMt['doDI'] = 0; } return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToDI_ajax")) {
  function nxs_rePostToDI_ajax() { check_ajax_referer('rePostToDI');  $postID = $_POST['id']; $options = get_option('NS_SNAutoPoster');  
    foreach ($options['di'] as $ii=>$two) if ($ii==$_POST['nid']) {   $two['ii'] = $ii; $two['pType'] = 'aj'; //if ($two['gpPageID'].$two['gpUName']==$_POST['nid']) {  
      $gppo =  get_post_meta($postID, 'snapDI', true); $gppo =  maybe_unserialize($gppo);// prr($gppo);
      if (is_array($gppo) && isset($gppo[$ii]) && is_array($gppo[$ii])){   $ntClInst = new nxs_snapClassDI(); $two = $ntClInst->adjMetaOpt($two, $gppo[$ii]); }
      $result = nxs_doPublishToDI($postID, $two); if ($result == 200) die("Successfully sent your post to Diigo."); else die($result);        
    }    
  }
}  

if (!function_exists("nxs_getDIHeaders")) {  function nxs_getDIHeaders($ref, $uname, $pass, $post=false){ $hdrsArr = array(); 
 $hdrsArr['X-Requested-With']='XMLHttpRequest'; $hdrsArr['Connection']='keep-alive'; $hdrsArr['Referer']=$ref;
 $hdrsArr['User-Agent']='Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.22 Safari/537.11';
 if($post) $hdrsArr['Content-Type']='application/x-www-form-urlencoded'; 
 $hdrsArr['Accept']='application/json, text/javascript, */*; q=0.01'; 
 $hdrsArr['Authorization']= 'Basic '.base64_encode($uname.':'.$pass);
 $hdrsArr['Accept-Encoding']='gzip,deflate,sdch'; $hdrsArr['Accept-Language']='en-US,en;q=0.8'; $hdrsArr['Accept-Charset']='ISO-8859-1,utf-8;q=0.7,*;q=0.3'; return $hdrsArr;
}}
if (!function_exists("nxs_doCheckDI")) {function nxs_doCheckDI($url){ global $nxs_diCkArray; $hdrsArr = nxs_getDIHeaders($url); $ckArr = $nxs_diCkArray;   
  $response = wp_remote_get($url, array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'cookies' => $ckArr));   
  if (stripos($response['body'],'logouthash=')===false) return 'Bad Saved Login';  
  if ( stripos($response['body'], 'usercp.php')!==false && stripos($response['body'], 'logouthash')!==false){ /*echo "You are IN"; */ return false; 
  } else return 'No Saved Login';
  return false;  
}}
if (!function_exists("nxs_doConnectToDI")) {  function nxs_doConnectToDI($u, $p, $url){ global $nxs_diCkArray; $hdrsArr = nxs_getDIHeaders($url, true);    echo "LOGGIN";
    $response = wp_remote_get($url); $contents = $response['body']; //$response['body'] = htmlentities($response['body']);  prr($response);    die();
    $ckArr = $response['cookies']; $mdhashLoc = stripos($contents, 'md5hash(di_login_password');
    if ($mdhashLoc===false) return "No DI found";
    $frmTxt = CutFromTo($contents, 'md5hash(di_login_password','</form>'); $md = array(); $flds  = array();
    while (stripos($frmTxt, '<input')!==false){ $inpField = trim(CutFromTo($frmTxt,'<input', '>')); $name = trim(CutFromTo($inpField,'name="', '"'));
     if ( stripos($inpField, '"hidden"')!==false && $name!='' && !in_array($name, $md)) { $md[] = $name; $val = trim(CutFromTo($inpField,'value="', '"')); $flds[$name]= $val; $mids .= "&".$name."=".$val;}
     $frmTxt = substr($frmTxt, stripos($frmTxt, '<input')+8);
    } $flds['di_login_username'] = $u; $flds['di_login_md5password'] = md5($p);  $flds['di_login_md5password_utf'] = md5($p); $flds['cookieuser'] = '1'; $flds['do'] = 'login'; 
    
    // $logURL = substr($contents, $mdhashLoc-250, 250); $logURL = CutFromTo($logURL, 'action="', '"');    
    if (stripos($contents, 'base href="')!==false) $baseURL = trim(CutFromTo($contents,'base href="', '"')); else { $uarr = explode('/',$url);  $dd = $uarr[count($uarr)-1]; $baseURL = str_replace($dd, '', $url);}
    
    //echo $baseURL.'login.php?do=login'; prr($flds);
    $r2 = wp_remote_post( $baseURL.'login.php?do=login', array( 'method' => 'POST', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'body' => $flds, 'cookies' => $ckArr));
    
    //$r2['body'] = htmlentities($r2['body']);  prr($r2);
    
    if (stripos($r2['body'],'exec_refresh()')!==false) { $ckArr = nxsMergeArraysOV($ckArr, $r2['cookies']); $nxs_diCkArray = $ckArr; return false; } else return "Bad Username/Password";
}}

if (!function_exists("nxs_doPostToDI")) {  function nxs_doPostToDI($url, $subj, $msg, $lnk, $tags){ global $nxs_diCkArray; $hdrsArr = nxs_getDIHeaders($url); $ckArr = $nxs_diCkArray;   
  $response = wp_remote_get($url, array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'cookies' => $ckArr));   
  $contents = $response['body']; //$response['body'] = htmlentities($response['body']);  prr($response);    die();
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
  }
 
  if ($mdd=='p'){ $fid = CutFromTo($contents, 'newreply.php?do=newreply','"');
    $response = wp_remote_get( $baseURL.'newreply.php?do=newreply'.str_replace('&amp;','&',$fid), array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'cookies' => $ckArr)); $contents = $response['body'];
    
    $frmTxt = CutFromTo($contents, 'newreply.php?do=postreply','</form>'); $md = array(); $flds  = array(); //prr($frmTxt);
    
    while (stripos($frmTxt, '<input')!==false){ $inpField = trim(CutFromTo($frmTxt,'<input', '>')); $name = trim(CutFromTo($inpField,'name="', '"')); 
     if ( stripos($inpField, '"hidden"')!==false && $name!='' && !in_array($name, $md)) { $md[] = $name; $val = trim(CutFromTo($inpField,'value="', '"')); $flds[$name]= $val; $mids .= "&".$name."=".$val;}
     $frmTxt = substr($frmTxt, stripos($frmTxt, '<input')+8);
    }  $flds['title'] = $subj; $flds['message'] = $msg; $flds['message_backup'] = $msg; $flds['wysiwyg'] = '1'; $flds['do'] = 'postreply';  $flds['parseurl'] = '1';  $flds['sbutton'] = 'Submit+Reply';  
    $smURL = $baseURL.'newreply.php?do=postreply'.str_replace('&amp;','&',$fid);
  }
 
  //echo $smURL."|"; prr($flds);
  $r2 = wp_remote_post( $smURL, array( 'method' => 'POST', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'body' => $flds, 'cookies' => $ckArr));   
  if (stripos($r2['body'], 'errorblock')!==false) return trim(strip_tags( CutFromTo($r2['body'], 'errorblock','</div>')));
  if (stripos($r2['body'], 'exec_refresh()')!==false && stripos($r2['body'], 'blockrow restore">')!==false) return trim(strip_tags( CutFromTo($r2['body'], 'blockrow restore">','</p>')));
  if (stripos($r2['body'], '<error>')!==false) return trim(strip_tags( CutFromTo($r2['body'], '<error>','</error>')));
  if ( $r2['response']['code']=='302' || $r2['response']['code']=='303') return 'OK';
  if (stripos($r2['body'], '<newpostid>')!==false || stripos($r2['body'], 'postbit postid="')!==false ) return 'OK';
  
  // $r2['body'] = htmlentities($r2['body']);  prr($r2);    die();
  
  return "Something wrong";  
}}

if (!function_exists("nxs_doPublishToDI")) { //## Second Function to Post to DI
  function nxs_doPublishToDI($postID, $options){ global $nxs_diCkArray; $ntCd = 'DI'; $ntCdL = 'di'; $ntNm = 'Diigo';
  
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
      nxs_addToLog($ntCd.' - '.$options['nName'], 'E', '-=Duplicate=- Post ID:'.$postID, 'Not posted. No reason for posting duplicate'); return;
    }
    
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $link = home_url(); $msg = 'Test Message from '.$link;  $msgT = 'Test Link from '.$link; } else {  
      $post = get_post($postID); if(!$post) return; $link = get_permalink($postID);
      $msgFormat = $options['diMsgFormat']; $diCat = $options['diCat']; $msg = nsFormatMessage($msgFormat, $postID); $msgFormatT = $options['diMsgTFormat']; $msgT = nsFormatMessage($msgFormatT, $postID);       
      nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); 
    }  
    
    //## Actual POST Code        
      $email = $options['diUName'];  $pass = (substr($options['diPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['diPass'], 5)):$options['diPass']);      
      $dusername = $options['diUName']; //$link = urlencode($link); $desc = urlencode(substr($msg, 0, 500));      
      $extInfo = ' | PostID: '.$postID." - ".$post->post_title; $logNT = '<span style="color:#000080">Diigo</span> - '.$options['nName'];      
      $t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = (implode(',',$tggs)); $tags = str_replace(' ','+',$tags);             
      
      $flds = array(); $flds['key']=$options['diAPIKey']; $flds['url']=$link; $flds['title']=nsTrnc($msgT, 250); $flds['desc']=nsTrnc($msg, 250); $flds['tags']=$tags; $flds['shared']='yes';      
      $hdrsArr = nxs_getDIHeaders('https://secure.diigo.com/api/v2/bookmarks', $dusername, $pass, true);
      $cnt = wp_remote_post( 'https://secure.diigo.com/api/v2/bookmarks', array( 'method' => 'POST', 'timeout' => 45, 'redirection' => 0, 'headers' => $hdrsArr, 'body' => $flds));   
      
      if( is_wp_error( $cnt ) ) {
        $ret = 'Something went wrong - '; nxs_addToLog($logNT, 'E', '-=ERROR=- '.$ret. "ERR: ".print_r($cnt, true), $extInfo);
      } else {      
        if (is_array($cnt) &&  stripos($cnt['body'],'"code":1')!==false) { $ret = 'OK'; nxs_metaMarkAsPosted($postID, 'DI', $options['ii']);  nxs_addToLog($logNT, 'M', 'OK - Message Posted ', $extInfo); } 
          else { if ($cnt['response']['code']=='401') $ret = " Incorrect Username/Password "; else  $ret = 'Something went wrong - '; nxs_addToLog($logNT, 'E', '-=ERROR=- '.$ret. "ERR: ".print_r($cnt, true), $extInfo);
          }
      }
      if ($ret!='OK') { if ($postID=='0') echo $ret; } else if ($postID=='0') { echo 'OK - Message Posted, please see your Diigo Page'; nxs_addToLog($logNT, 'M', 'OK - TEST Message Posted '); }
      if ($ret == 'OK') return 200; else return $ret;
      
  }
}  
?>