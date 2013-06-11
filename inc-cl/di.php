<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'DI', 'lcode'=>'di', 'name'=>'Diigo');

if (!class_exists("nxs_snapClassDI")) { class nxs_snapClassDI {
  //#### Show Common Settings
  
  function showGenNTSettings($ntOpts){ global $nxs_plurl; $ntInfo = array('code'=>'DI', 'lcode'=>'di', 'name'=>'Diigo', 'defNName'=>'diUName', 'tstReq' => false); ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> <?php wp_nonce_field( 'ns'.$ntInfo['code'], 'ns'.$ntInfo['code'].'_wpnonce' ); ?>
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = $pbo[$ntInfo['defNName']]; ?>
          <p style="margin:0px;margin-left:5px;">
            <input value="1" name="<?php echo $ntInfo['lcode']; ?>[<?php echo $indx; ?>][apDo<?php echo $ntInfo['code']; ?>]" onchange="doShowHideBlocks('<?php echo $ntInfo['code']; ?>');" type="checkbox" <?php if ((int)$pbo['do'.$ntInfo['code']] == 1) echo "checked"; ?> /> <?php if ((int)$pbo['catSel'] == 1) { ?>   <span onmouseout="nxs_hidePopUpInfo('popOnlyCat');" onmouseover="nxs_showPopUpInfo('popOnlyCat', event);"><?php echo "*[".(substr_count($pbo['catSelEd'], ",")+1)."]*" ?></span><?php } ?>
            <strong><?php  _e('Auto-publish to', 'nxs_snap'); ?> <?php echo $ntInfo['name']; ?> <i style="color: #005800;"><?php if($pbo['nName']!='') echo "(".$pbo['nName'].")"; ?></i></strong>
          &nbsp;&nbsp;<?php if ($ntInfo['tstReq'] && (!isset($pbo[$ntInfo['lcode'].'OK']) || $pbo[$ntInfo['lcode'].'OK']=='')){ ?><b style="color: #800000"><?php  _e('Attention requred. Unfinished setup', 'nxs_snap'); ?> ==&gt;</b><?php } ?><a id="do<?php echo $ntInfo['code'].$indx; ?>A" href="#" onclick="doShowHideBlocks2('<?php echo $ntInfo['code'].$indx; ?>');return false;">[<?php  _e('Show Settings', 'nxs_snap'); ?>]</a>&nbsp;&nbsp;
          <a href="#" onclick="doDelAcct('<?php echo $ntInfo['lcode']; ?>', '<?php echo $indx; ?>', '<?php if (isset($pbo['bgBlogID'])) echo $pbo['nName']; ?>');return false;">[<?php  _e('Remove Account', 'nxs_snap'); ?>]</a>
          </p><?php $this->showNTSettings($indx, $pbo);             
        }?>
      </div>
    </div> <?php
  }   
  
  //#### Show NEW Settings Page
  function showNewNTSettings($mgpo){ $options = array('nName'=>'', 'doDI'=>'1', 'diUName'=>'', 'diInclTags'=>'1', 'diAttch'=>'', 'diAPIKey'=>'', 'diPass'=>''); $this->showNTSettings($mgpo, $options, true);}  
  
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; ?>
            <div id="doDI<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew){ ?> clNewNTSets<?php } ?>" style="background-image: url(<?php echo $nxs_plurl; ?>img/di-bg.png);background-position:90% 10%;"> <input type="hidden" name="apDoSDI<?php echo $ii; ?>" value="0" id="apDoSDI<?php echo $ii; ?>" />          
            
             <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/di16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-diigo-social-networks-auto-poster-wordpress/"><?php $nType="Diigo"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
            
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easely identify it', 'nxs_snap'); ?></i> </div><input name="di[<?php echo $ii; ?>][nName]" id="dinName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('di', $ii, $options['qTLng']); ?>
            <?php echo nxs_addPostingDelaySel('di', $ii, $options['nHrs'], $options['nMin']); ?>
            
            <?php if (!$isNew) { ?>
    <div style="width:100%;"><strong><?php _e('Categories', 'nxs_snap'); ?>:</strong>
       <input value="0" id="catSelA<?php echo $ii; ?>" type="radio" name="di[<?php echo $ii; ?>][catSel]" <?php if ((int)$options['catSel'] != 1) echo "checked"; ?> /> All                                  
       <input value="1" id="catSelSDI<?php echo $ii; ?>" type="radio" name="di[<?php echo $ii; ?>][catSel]" <?php if ((int)$options['catSel'] == 1) echo "checked"; ?> /> <a href="#" style="text-decoration: none;" class="showCats" id="nxs_SCA_DI<?php echo $ii; ?>" onclick="jQuery('#catSelSDI<?php echo $ii; ?>').attr('checked', true); jQuery('#tmpCatSelNT').val('DI<?php echo $ii; ?>'); nxs_markCats( jQuery('#nxs_SC_DI<?php echo $ii; ?>').val() ); jQuery('#showCatSel').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false], position: [75, 'auto']}); return false;">Selected<?php if ($options['catSelEd']!='') echo "[".(substr_count($options['catSelEd'], ",")+1)."]"; ?></a>       
       <input type="hidden" name="di[<?php echo $ii; ?>][catSelEd]" id="nxs_SC_DI<?php echo $ii; ?>" value="<?php echo $options['catSelEd']; ?>" />
    <br/><i><?php _e('Only selected categories will be autoposted to this account', 'nxs_snap'); ?></i></div> 
    <br/>
    <?php } ?>
            
                        <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText">Diigo API Key:</strong> <span style="font-size: 11px; margin: 0px;">Get it from <a target="_blank" href="http://www.diigo.com/api_keys/">http://www.diigo.com/api_keys</a>.</span></div>
                <input name="di[<?php echo $ii; ?>][apDIAPIKey]" id="apDIAPIKey" style="width: 60%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['diAPIKey'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/> 
            </div>   
           <div style="width:100%;"><strong>Diigo Username:</strong> </div><input name="di[<?php echo $ii; ?>][apDIUName]" id="apDIUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['diUName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
            <div style="width:100%;"><strong>Diigo Password:</strong> </div><input name="di[<?php echo $ii; ?>][apDIPass]" id="apDIPass" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($options['diPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['diPass'], 5)):$options['diPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/>                
            
            <?php if ($isNew) { ?> <input type="hidden" name="di[<?php echo $ii; ?>][apDoDI]" value="1" id="apDoNewDI<?php echo $ii; ?>" /> <?php } ?><br/>            
            <p style="margin-bottom: 20px;margin-top: 5px;"><input value="1"  id="diInclTags" type="checkbox" name="di[<?php echo $ii; ?>][diInclTags]"  <?php if ((int)$options['diInclTags'] == 1) echo "checked"; ?> /> 
              <strong><?php _e('Post with tags', 'nxs_snap'); ?></strong> <?php _e('Tags from the blogpost will be auto posted to Diigo', 'nxs_snap'); ?>                                
            </p>
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Title Format', 'nxs_snap'); ?></strong> (<a href="#" id="apDIMsgTFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apDIMsgTFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)</div>
              <input name="di[<?php echo $ii; ?>][apDIMsgTFrmt]" id="apDIMsgTFrmt<?php echo $ii; ?>" style="width: 50%;" value="<?php if ($isNew) echo "%TITLE%"; else _e(apply_filters('format_to_edit', htmlentities($options['diMsgTFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?>"  onfocus="mxs_showFrmtInfo('apDIMsgTFrmt<?php echo $ii; ?>');" /><?php nxs_doShowHint("apDIMsgTFrmt".$ii); ?>
            </div><br/> 
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Text Format', 'nxs_snap'); ?></strong> (<a href="#" id="apDIMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apDIMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)</div>
              
              
              <textarea cols="150" rows="3" id="di<?php echo $ii; ?>SNAPformat" name="di[<?php echo $ii; ?>][apDIMsgFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#di<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apDIMsgFrmt<?php echo $ii; ?>');"><?php if ($isNew) echo "%EXCERPT%"; else _e(apply_filters('format_to_edit', htmlentities($options['diMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?></textarea>
              
              <?php nxs_doShowHint("apDIMsgFrmt".$ii); ?>
            </div><br/>    
            
            <?php if ($options['diPass']!='') { ?>
            <?php wp_nonce_field( 'rePostToDI', 'rePostToDI_wpnonce' ); ?>
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('DI', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>            <?php } 
          ?><div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div></div><?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'DI'; $lcode = 'di'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apDIUName']) && $pval['apDIUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apDIUName']))   $options[$ii]['diUName'] = trim($pval['apDIUName']);
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apDIPass']))    $options[$ii]['diPass'] = 'n5g9a'.nsx_doEncode($pval['apDIPass']); else $options[$ii]['diPass'] = '';  
        if (isset($pval['apDIAPIKey'])) $options[$ii]['diAPIKey'] = trim($pval['apDIAPIKey']);                                                  
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']);
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
        
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
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapDI', true));   if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
        $doDI = $ntOpt['doDI'] && (is_array($pMeta) || $ntOpt['catSel']!='1');   
        $isAvailDI =  $ntOpt['diUName']!='' && $ntOpt['diPass']!=''; $diMsgFormat = htmlentities($ntOpt['diMsgFormat'], ENT_COMPAT, "UTF-8"); $diMsgTFormat = htmlentities($ntOpt['diMsgTFormat'], ENT_COMPAT, "UTF-8");      
      ?>  
      <tr><th style="text-align:left;" colspan="2"><?php if ( $ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='' )  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_DI<?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if ($isAvailDI) { ?><input class="nxsGrpDoChb" value="1" id="doDI<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="di[<?php echo $ii; ?>][doDI]" <?php if ((int)$doDI == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="di[<?php echo $ii; ?>][doDI]" value="<?php echo $doDI;?>"> <?php } ?> <?php } ?>
      
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/di16.png);">Diigo - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailDI) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToDI_repostButton" id="rePostToDI_button" value="<?php _e('Repost to Diigo', 'nxs_snap') ?>" />
                    <?php wp_nonce_field( 'rePostToDI', 'rePostToDI_wpnonce' ); } ?>                    
                    <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) {                         
                        ?> <span id="pstdDI<?php echo $ii; ?>" style="float: right; padding-top: 4px; padding-right: 10px;">
          <a style="font-size: 10px;" href="http://www.diigo.com/user/<?php echo $ntOpt['diUName']; ?>" target="_blank"><?php $nType="Diigo"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>                    
                </td></tr>
                <?php if (!$isAvailDI) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Diigo Account to AutoPost to Diigo</b>
                <?php } elseif ($post->post_status != "pubZlish") { ?>                
       <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Title Format:', 'nxs_snap') ?></th>
        <td><input value="<?php echo $diMsgTFormat ?>" type="text" name="di[<?php echo $ii; ?>][SNAPformatT]" style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apDIMsgTFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apDIMsgTFrmt".$ii, '', '58'); ?></td></tr>                
      <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Text Format:', 'nxs_snap') ?></th>
        <td>        
        <textarea cols="150" rows="1" id="di<?php echo $ii; ?>SNAPformat" name="di[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#di<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apDIMsgFrmt<?php echo $ii; ?>');"><?php echo $diMsgFormat; ?></textarea>
        <?php nxs_doShowHint("apDIMsgFrmt".$ii, '', '58'); ?></td></tr>
                <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['diMsgFormat'] = $pMeta['SNAPformat']; 
     if (isset($pMeta['SNAPformatT'])) $optMt['diMsgTFormat'] = $pMeta['SNAPformatT']; 
     if (isset($pMeta['doDI'])) $optMt['doDI'] = $pMeta['doDI'] == 1?1:0; else { if (isset($pMeta['SNAPformat']))  $optMt['doDI'] = 0; } 
     if (isset($pMeta['SNAPincludeDI']) && $pMeta['SNAPincludeDI'] == '1' ) $optMt['doDI'] = 1;  
     return $optMt;
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
  //  if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToDI',  array($postID, $options));  
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
    $logNT = '<span style="color:#000080">Diigo</span> - '.$options['nName'];      
    $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
      $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') { sleep(5);
         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'); return;
      }
    }     
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $link = home_url(); $msg = 'Test Message from '.$link;  $msgT = 'Test Link from '.$link; } else {  
      $post = get_post($postID); if(!$post) return; $link = get_permalink($postID);
      $msgFormat = $options['diMsgFormat']; $diCat = $options['diCat']; $msg = nsFormatMessage($msgFormat, $postID); $msgFormatT = $options['diMsgTFormat']; $msgT = nsFormatMessage($msgFormatT, $postID);       
      nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); 
    } 
    $extInfo = ' | PostID: '.$postID." - ".$post->post_title; 
    //## Actual POST Code        
      $email = $options['diUName'];  $pass = (substr($options['diPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['diPass'], 5)):$options['diPass']);      
      $dusername = $options['diUName']; //$link = urlencode($link); $desc = urlencode(substr($msg, 0, 500));      
      
      $t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = (implode(',',$tggs)); $tags = str_replace(' ','+',$tags);             
      
      $flds = array(); $flds['key']=$options['diAPIKey']; $flds['url']=$link; $flds['title']=nsTrnc($msgT, 250); $flds['desc']=nsTrnc($msg, 250); $flds['tags']=$tags; $flds['shared']='yes';      
      $hdrsArr = nxs_getDIHeaders('https://secure.diigo.com/api/v2/bookmarks', $dusername, $pass, true);
      $cnt = wp_remote_post( 'https://secure.diigo.com/api/v2/bookmarks', array( 'method' => 'POST', 'timeout' => 45, 'redirection' => 0, 'headers' => $hdrsArr, 'body' => $flds));   
      
      if( is_wp_error( $cnt ) ) {
        $ret = 'Something went wrong - '; nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.$ret. "ERR: ".print_r($cnt, true), $extInfo);
      } else {      
        if (is_array($cnt) &&  stripos($cnt['body'],'"code":1')!==false) 
          { $ret = 'OK'; nxs_metaMarkAsPosted($postID, 'DI', $options['ii'], array('isPosted'=>'1', 'pgID'=>'DI', 'pDate'=>date('Y-m-d H:i:s')));  nxs_addToLogN( 'S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo); } 
          else { if ($cnt['response']['code']=='401') $ret = " Incorrect Username/Password "; else  $ret = 'Something went wrong - '; nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.$ret. "ERR: ".print_r($cnt, true), $extInfo);
          }
      }
      if ($ret!='OK') { if ($postID=='0') echo $ret; } else if ($postID=='0') { echo 'OK - Message Posted, please see your Diigo Page'; nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); }
      if ($ret == 'OK') return 200; else return $ret;
      
  }
}  
?>