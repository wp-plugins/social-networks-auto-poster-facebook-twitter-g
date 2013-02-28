<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'VB', 'lcode'=>'vb', 'name'=>'vBulletin');

if (!class_exists("nxs_snapClassVB")) { class nxs_snapClassVB {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){  global $nxs_plurl; $ntInfo = array('code'=>'VB', 'lcode'=>'vb', 'name'=>'vBulletin', 'defNName'=>'vbUName', 'tstReq' => false); ?>    
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
  function showNewNTSettings($mgpo){ $options = array('nName'=>'', 'doVB'=>'1', 'vbUName'=>'', 'vbInclTags'=>'1', 'vbAttch'=>'', 'vbURL'=>'', 'vbPass'=>''); $this->showNTSettings($mgpo, $options, true);}
  
  
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; ?>
            <div id="doVB<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>" style="background-image: url(<?php echo $nxs_plurl; ?>img/vb-bg.png);  background-position:90% 10%;">     <input type="hidden" name="apDoSVB<?php echo $ii; ?>" value="0" id="apDoSVB<?php echo $ii; ?>" />          
            
             <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/vb16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-vbulletin-social-networks-auto-poster-wordpress/"><?php $nType="vBulletin"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
            
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easely identify it', 'nxs_snap'); ?></i> </div><input name="vb[<?php echo $ii; ?>][nName]" id="vbnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('vb', $ii, $options['qTLng']); ?><?php echo nxs_addPostingDelaySel('vb', $ii, $options['nHrs'], $options['nMin']); ?>
            
             <?php if (!$isNew) { ?>
    <div style="width:100%;"><strong><?php _e('Categories', 'nxs_snap'); ?>:</strong>
       <input value="0" id="catSelA<?php echo $ii; ?>" type="radio" name="vb[<?php echo $ii; ?>][catSel]" <?php if ((int)$options['catSel'] != 1) echo "checked"; ?> /> All                                  
       <input value="1" id="catSelSVB<?php echo $ii; ?>" type="radio" name="vb[<?php echo $ii; ?>][catSel]" <?php if ((int)$options['catSel'] == 1) echo "checked"; ?> /> <a href="#" style="text-decoration: none;" class="showCats" id="nxs_SCA_VB<?php echo $ii; ?>" onclick="jQuery('#catSelSVB<?php echo $ii; ?>').attr('checked', true); jQuery('#tmpCatSelNT').val('VB<?php echo $ii; ?>'); nxs_markCats( jQuery('#nxs_SC_VB<?php echo $ii; ?>').val() ); jQuery('#showCatSel').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false], position: [75, 'auto']}); return false;">Selected<?php if ($options['catSelEd']!='') echo "[".(substr_count($options['catSelEd'], ",")+1)."]"; ?></a>       
       <input type="hidden" name="vb[<?php echo $ii; ?>][catSelEd]" id="nxs_SC_VB<?php echo $ii; ?>" value="<?php echo $options['catSelEd']; ?>" />
    <br/><i><?php _e('Only selected categories will be autoposted to this account', 'nxs_snap'); ?></i></div> 
    <br/>
    <?php } ?>
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText">vBulletin URL:</strong> <span style="font-size: 11px; margin: 0px;">Could be Forum URL or Thread URL. Either new thread of new post will be created.</span></div>
                <input name="vb[<?php echo $ii; ?>][apVBURL]" id="apVBURL" style="width: 60%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['vbURL'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/> 
            
            </div>   
            
            <div style="width:100%;"><strong>vBulletin Username:</strong> </div><input name="vb[<?php echo $ii; ?>][apVBUName]" id="apVBUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['vbUName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
            <div style="width:100%;"><strong>vBulletin Password:</strong> </div><input name="vb[<?php echo $ii; ?>][apVBPass]" id="apVBPass" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($options['vbPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['vbPass'], 5)):$options['vbPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/>                
            
            <?php if ($isNew) { ?> <input type="hidden" name="vb[<?php echo $ii; ?>][apDoVB]" value="1" id="apDoNewVB<?php echo $ii; ?>" /> <?php } ?>
            <br/>            
            
            
            
            <p style="margin-bottom: 20px;margin-top: 5px;"><input value="1"  id="vbInclTags" type="checkbox" name="vb[<?php echo $ii; ?>][vbInclTags]"  <?php if ((int)$options['vbInclTags'] == 1) echo "checked"; ?> /> 
              <strong>Post with tags</strong> Tags from the blogpost will be auto posted to vBulletin                                
            </p>
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Title Format', 'nxs_snap'); ?></strong> (<a href="#" id="apVBMsgTFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apVBMsgTFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)</div>
                           
              <textarea cols="150" rows="3" id="vb<?php echo $ii; ?>SNAPformat" name="vb[<?php echo $ii; ?>][apVBMsgTFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#vb<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apVBMsgTFrmt<?php echo $ii; ?>');"><?php if ($isNew) echo "%TITLE%"; else _e(apply_filters('format_to_edit', htmlentities($options['vbMsgTFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?></textarea>                            
              
              <?php nxs_doShowHint("apVBMsgTFrmt".$ii); ?>
              </div><br/> 
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Text Format', 'nxs_snap'); ?></strong> (<a href="#" id="apVBMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apVBMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)</div>
              <input name="vb[<?php echo $ii; ?>][apVBMsgFrmt]" id="apVBMsgFrmt" style="width: 50%;" value="<?php if ($isNew) echo "%EXCERPT%"; else _e(apply_filters('format_to_edit', htmlentities($options['vbMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?>"  onfocus="mxs_showFrmtInfo('apVBMsgFrmt<?php echo $ii; ?>');" /><?php nxs_doShowHint("apVBMsgFrmt".$ii); ?>
            </div><br/>    
            
            <?php if ($options['vbPass']!='') { ?>
            <?php wp_nonce_field( 'rePostToVB', 'rePostToVB_wpnonce' ); ?>
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('VB', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>      
               
            <?php } 
            
            ?><div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div></div><?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'VB'; $lcode = 'vb'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apVBUName']) && $pval['apVBUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apVBUName']))   $options[$ii]['vbUName'] = trim($pval['apVBUName']);
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apVBPass']))    $options[$ii]['vbPass'] = 'n5g9a'.nsx_doEncode($pval['apVBPass']); else $options[$ii]['vbPass'] = '';  
        if (isset($pval['apVBURL'])) $options[$ii]['vbURL'] = trim($pval['apVBURL']);                                                  
        
        if (isset($pval['vbInclTags']))     $options[$ii]['vbInclTags'] = $pval['vbInclTags']; else $options[$ii]['vbInclTags'] = 0;
        if (isset($pval['apVBMsgTFrmt'])) $options[$ii]['vbMsgTFormat'] = trim($pval['apVBMsgTFrmt']);
        if (isset($pval['apVBMsgFrmt'])) $options[$ii]['vbMsgFormat'] = trim($pval['apVBMsgFrmt']);
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']);
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
        
        if (isset($pval['apDoVB']))      $options[$ii]['doVB'] = $pval['apDoVB']; else $options[$ii]['doVB'] = 0; 
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      }
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID;
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapVB', true));   if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
        $doVB = $ntOpt['doVB'] && (is_array($pMeta) || $ntOpt['catSel']!='1');   
        $isAvailVB =  $ntOpt['vbUName']!='' && $ntOpt['vbPass']!=''; $vbMsgFormat = htmlentities($ntOpt['vbMsgFormat'], ENT_COMPAT, "UTF-8"); $vbMsgTFormat = htmlentities($ntOpt['vbMsgTFormat'], ENT_COMPAT, "UTF-8");      
      ?>  
      <tr><th style="text-align:left;" colspan="2"><?php if ( $ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='' )  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_VB<?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if ($isAvailVB) { ?><input class="nxsGrpDoChb" value="1" id="doVB<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="vb[<?php echo $ii; ?>][doVB]" <?php if ((int)$doVB == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="vb[<?php echo $ii; ?>][doVB]" value="<?php echo $doVB;?>"> <?php } ?> <?php } ?>
      
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/vb16.png);">vBulletin - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailVB) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToVB_repostButton" id="rePostToVB_button" value="<?php _e('Repost to vBulletin', 'nxs_snap') ?>" />
                    <?php wp_nonce_field( 'rePostToVB', 'rePostToVB_wpnonce' ); } ?>
                    
                    <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) { $wpURL = str_ireplace('/xmlrpc.php', '', $ntOpt['vbURL']);
                        if (substr($wpURL, -1)=='/') $wpURL = substr($wpURL, 0, -1);  $wpURL = $wpURL."/";
                        ?> <span id="pstdVB<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
          <a style="font-size: 10px;" href="<?php echo $pMeta[$ii]['pgID']; ?>" target="_blank"><?php $nType="vBulletin"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>                
                
                <?php if (!$isAvailVB) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your vBulletin Account to AutoPost to vBulletin</b>
                <?php } elseif ($post->post_status != "puZblish") { ?> 
               
       <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top:6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Text Format:', 'nxs_snap') ?></th>
        <td><input value="<?php echo $vbMsgTFormat ?>" type="text" name="vb[<?php echo $ii; ?>][SNAPformatT]"  style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apVBMsgTFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apVBMsgTFrmt".$ii); ?></td></tr>
                
      <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top:6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Text Format:', 'nxs_snap') ?></th>
        <td>        
        <textarea cols="150" rows="1" id="vb<?php echo $ii; ?>SNAPformat" name="vb[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#vb<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apVBMsgFrmt<?php echo $ii; ?>');"><?php echo $vbMsgFormat; ?></textarea>
        <?php nxs_doShowHint("apVBMsgFrmt".$ii); ?></td></tr>
                <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){  if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['vbMsgFormat'] = $pMeta['SNAPformat']; 
     if (isset($pMeta['SNAPformatT'])) $optMt['vbMsgTFormat'] = $pMeta['SNAPformatT'];
     if (isset($pMeta['doVB'])) $optMt['doVB'] = $pMeta['doVB'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doVB'] = 0; } 
     if (isset($pMeta['SNAPincludeVB']) && $pMeta['SNAPincludeVB'] == '1' ) $optMt['doVB'] = 1;  
     return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToVB_ajax")) {
  function nxs_rePostToVB_ajax() { check_ajax_referer('rePostToVB');  $postID = $_POST['id']; $options = get_option('NS_SNAutoPoster');  
    foreach ($options['vb'] as $ii=>$two) if ($ii==$_POST['nid']) {   $two['ii'] = $ii; $two['pType'] = 'aj'; //if ($two['gpPageID'].$two['gpUName']==$_POST['nid']) {  
      $gppo =  get_post_meta($postID, 'snapVB', true); $gppo =  maybe_unserialize($gppo);// prr($gppo);
      if (is_array($gppo) && isset($gppo[$ii]) && is_array($gppo[$ii])){ $ntClInst = new nxs_snapClassVB(); $two = $ntClInst->adjMetaOpt($two, $gppo[$ii]); }
      $result = nxs_doPublishToVB($postID, $two); if ($result == 200) die("Successfully sent your post to vBulletin."); else die($result);        
    }    
  }
}  

if (!function_exists("nxs_getVBHeaders")) {  function nxs_getVBHeaders($ref, $post=false){ $hdrsArr = array(); 
 $hdrsArr['X-Requested-With']='XMLHttpRequest'; $hdrsArr['Connection']='keep-alive'; $hdrsArr['Referer']=$ref;
 $hdrsArr['User-Agent']='Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.22 Safari/537.11';
 if($post) $hdrsArr['Content-Type']='application/x-www-form-urlencoded'; 
 $hdrsArr['Accept']='application/json, text/javascript, */*; q=0.01'; 
 $hdrsArr['Accept-Encoding']='gzip,deflate,sdch'; $hdrsArr['Accept-Language']='en-US,en;q=0.8'; $hdrsArr['Accept-Charset']='ISO-8859-1,utf-8;q=0.7,*;q=0.3'; return $hdrsArr;
}}
if (!function_exists("nxs_doCheckVB")) {function nxs_doCheckVB($url){ global $nxs_vbCkArray; $hdrsArr = nxs_getVBHeaders($url); $ckArr = $nxs_vbCkArray;   
  $response = wp_remote_get($url, array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'cookies' => $ckArr));   
  if (stripos($response['body'],'logouthash=')===false) return 'Bad Saved Login';  
  if ( stripos($response['body'], 'usercp.php')!==false && stripos($response['body'], 'logouthash')!==false){ /*echo "You are IN"; */ return false; 
  } else return 'No Saved Login';
  return false;  
}}
if (!function_exists("nxs_doConnectToVB")) {  function nxs_doConnectToVB($u, $p, $url){ global $nxs_vbCkArray; $hdrsArr = nxs_getVBHeaders($url, true); //   echo "LOGGIN";
    $response = wp_remote_get($url); if(is_wp_error($response)) { nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($response, true), ''); return "Invalid Connection. Please see log."; }
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
    
    //echo $baseURL.'login.php?do=login'; prr($flds);
    $r2 = wp_remote_post( $baseURL.'login.php?do=login', array( 'method' => 'POST', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'body' => $flds, 'cookies' => $ckArr));
    
    //$r2['body'] = htmlentities($r2['body']);  prr($r2);
    
    if (stripos($r2['body'],'exec_refresh()')!==false) { $ckArr = nxsMergeArraysOV($ckArr, $r2['cookies']); $nxs_vbCkArray = $ckArr; return false; } else return "Bad Username/Password";
}}
if (!function_exists("nxs_doPostToVB")) {  function nxs_doPostToVB($url, $subj, $msg, $lnk, $tags){ global $nxs_vbCkArray; $hdrsArr = nxs_getVBHeaders($url); $ckArr = $nxs_vbCkArray;   
  $response = wp_remote_get($url, array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'cookies' => $ckArr));   
  if(is_wp_error($response)) { nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($response, true), ''); return "Invalid Connection. Please see log."; }
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
  } //prr($flds);
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
  $r2 = wp_remote_post( $smURL, array( 'method' => 'POST', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'body' => $flds, 'cookies' => $ckArr));// prr($r2['response']);   prr(htmlentities($r2['body'])); $r2['body'] = ''; prr($r2); die();
  if (stripos($r2['body'], 'errorblock')!==false) return trim(strip_tags( CutFromTo($r2['body'], 'errorblock','</div>')));
  if (stripos($r2['body'], 'exec_refresh()')!==false && stripos($r2['body'], 'blockrow restore">')!==false) return trim(strip_tags( CutFromTo($r2['body'], 'blockrow restore">','</p>')));
  if (stripos($r2['body'], '<error>')!==false) return trim(strip_tags( CutFromTo($r2['body'], '<error>','</error>')));
  if ( $r2['response']['code']=='302' || $r2['response']['code']=='303') { return array("code"=>"OK", "post_id"=>$r2['headers']['location']); }
  if (stripos($r2['body'], '<newpostid>')!==false || stripos($r2['body'], 'postbit postid="')!==false ) return 'OK';
  
  // $r2['body'] = htmlentities($r2['body']);  prr($r2);    die();
  
  return "Something wrong";  
}}

if (!function_exists("nxs_doPublishToVB")) { //## Second Function to Post to VB
  function nxs_doPublishToVB($postID, $options){ global $nxs_vbCkArray; $ntCd = 'VB'; $ntCdL = 'vb'; $ntNm = 'vBulletin';
    //if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToVB',  array($postID, $options));  
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
    $logNT = '<span style="color:#000080">vBulletin</span> - '.$options['nName'];      
    $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') {  sleep(5);
         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$uqID); return;
        }
    } 
  
      $vbCat = $options['vbCat']; $email = $options['vbUName']; $pass = (substr($options['vbPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['vbPass'], 5)):$options['vbPass']);      
      if ($postID=='0') { echo "Testing ... <br/><br/>"; $link = home_url(); $msg = 'Test Message from '.$link;  $msgT = 'Test Link from '.$link; } 
        else { $post = get_post($postID); if(!$post) return; $link = get_permalink($postID); nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); 
          $msgFormat = $options['vbMsgFormat']; $msg = nsFormatMessage($msgFormat, $postID); $msgFormatT = $options['vbMsgTFormat']; $msgT = nsFormatMessage($msgFormatT, $postID);       
      }
      $dusername = $options['vbUName']; //$link = urlencode($link); $desc = urlencode(substr($msg, 0, 500));      
      $extInfo = ' | PostID: '.$postID." - ".$post->post_title; 
      if ($options['vbInclTags']=='1') { $t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = urlencode(implode(', ',$tggs)); $tags = str_replace(' ','+',$tags); } else $tags = '';
      if (isset($options['vbSvC'])) $nxs_vbCkArray = maybe_unserialize( $options['vbSvC']); $loginError = true;
      if (is_array($nxs_vbCkArray)) $loginError = nxs_doCheckVB( $options['vbURL']); if ($loginError!==false) $loginError = nxs_doConnectToVB($email, $pass, $options['vbURL']); 
      if (serialize($nxs_vbCkArray)!=$options['vbSvC']) { global $plgn_NS_SNAutoPoster;  $gOptions = $plgn_NS_SNAutoPoster->nxs_options;
        if (isset($options['ii']) && $options['ii']!=='')  { $gOptions['vb'][$options['ii']]['vbSvC'] = serialize($nxs_vbCkArray); update_option('NS_SNAutoPoster', $gOptions);  }
        else foreach ($gOptions['vb'] as $ii=>$gpn) { $result = array_diff($options, $gpn); 
          if (!is_array($result) || count($result)<1) { $gOptions['vb'][$ii]['vbSvC'] = serialize($nxs_vbCkArray); update_option('NS_SNAutoPoster', $gOptions); break; }
        }        
      }  //var_dump($loginError);
      if ($loginError!==false) {if ($postID=='0') prr($loginError); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($loginError, true)." - BAD USER/PASS", $extInfo); return " -= BAD USER/PASS =- ";} 
      $ret = nxs_doPostToVB($options['vbURL'], $msgT, $msg, $link, $tags);      
      if ( (!is_array($ret)) && $ret!='OK'){ if ($postID=='0') prr($ret); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo);} 
        else if ($postID=='0')  { nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); echo ' OK - Message Posted, please see your vBulletin Page '; } else 
          { nxs_metaMarkAsPosted($postID, 'VB', $options['ii'], array('isPosted'=>'1', 'pgID'=>$ret['post_id'], 'pDate'=>date('Y-m-d H:i:s'))); nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo); }
      if ($ret['code']=='OK') return 200; else return $ret;
      
  }
}  
?>