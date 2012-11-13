<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'DL', 'lcode'=>'dl', 'name'=>'Delicious');

if (!class_exists("nxs_snapClassDL")) { class nxs_snapClassDL {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl, $nxs_plurl, $nxsOne; $code = 'DL'; $lcode = 'dl'; wp_nonce_field( 'ns'.$code, 'ns'.$code.'_wpnonce' ); ?>
    <hr/><div class="nsx_iconedTitle" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $lcode; ?>16.png);">Delicious Settings:           
            <?php $cgpo = count($ntOpts); $mgpo = 1+max(array_keys($ntOpts)); $nxsOne .= "&g=1"; ?>            
              <div class="nsBigText">You have <?php echo $cgpo=='0'?'No':$cgpo; ?> Delicious account<?php if ($cgpo!=1){ ?>s<?php } ?>  </div></div> 
              <?php  //if (function_exists('nxs_doSMAS1')) nxs_doSMAS1($this, $mgpo); else nxs_doSMAS('Google+', 'GP'.$mgpo); ?>
              <?php foreach ($ntOpts as $indx=>$gpo){ if (trim($gpo['nName']=='')) $gpo['nName'] =$gpo['dlUName']; ?>
                <p style="margin: 0px;margin-left: 5px;">
                  <input value="1" id="apDoDL" name="dl[<?php echo $indx; ?>][apDoDL]" onchange="doShowHideBlocks('DL');" type="checkbox" <?php if ((int)$gpo['doDL'] == 1) echo "checked"; ?> /> 
                  <strong>Auto-publish your Posts to your Delicious Account <i style="color: #005800;"><?php if($gpo['nName']!='') echo "(".$gpo['nName'].")"; ?></i>  </strong>                                         
                  &nbsp;&nbsp;<a id="doDL<?php echo $indx; ?>A" href="#" onclick="doShowHideBlocks2('DL<?php echo $indx; ?>');return false;">[Show Settings]</a> &nbsp;&nbsp;
                  <a href="#" onclick="doDelAcct('dl','<?php echo $indx; ?>', '<?php echo $gpo['dlUName']; ?>');return false;">[Remove Account]</a>
                </p>            
                <?php $this->showNTSettings($indx, $gpo);             
              } ?>            
            <?php 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($mgpo){ $gpo = array('nName'=>'', 'doDL'=>'1', 'dlUName'=>'', 'dlPageID'=>'', 'dlAttch'=>'', 'dlPass'=>''); $this->showNTSettings($mgpo, $gpo, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $gpo, $isNew=false){  global $nxs_plurl; ?>
            <div id="doDL<?php echo $ii; ?>Div" <?php if ($isNew){ ?>class="clNewNTSets"<?php } ?> style="max-width: 1000px; background-color: #EBF4FB; background-image: url(<?php echo $nxs_plurl; ?>img/dl-bg.png);  background-position:90% 10%; background-repeat: no-repeat; margin: 10px; border: 1px solid #808080; padding: 10px; display:none;">     <input type="hidden" name="apDoSDL<?php echo $ii; ?>" value="0" id="apDoSDL<?php echo $ii; ?>" />          
            
             <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/dl16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-delicious-social-networks-auto-poster-wordpress/">Detailed Delicious Installation/Configuration Instructions</a></div>
            
            <div style="width:100%;"><strong>Account Nickname:</strong> <i>Just so you can easely identify it</i> </div><input name="dl[<?php echo $ii; ?>][nName]" id="dlnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit',htmlentities($gpo['nName'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /><br/>
            <?php echo nxs_addQTranslSel('dl', $ii, $options['qTLng']); ?><?php echo nxs_addPostingDelaySel('dl', $ii, $gpo['nHrs'], $gpo['nMin']); ?>
            
            <div style="width:100%;"><strong>Delicious Username:</strong> </div><input name="dl[<?php echo $ii; ?>][apDLUName]" id="apDLUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',htmlentities($gpo['dlUName'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />                
            <div style="width:100%;"><strong>Delicious Password:</strong> </div><input name="dl[<?php echo $ii; ?>][apDLPass]" id="apDLPass" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($gpo['dlPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($gpo['dlPass'], 5)):$gpo['dlPass'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />  <br/>                
            
            <?php if ($isNew) { ?> <input type="hidden" name="dl[<?php echo $ii; ?>][apDoDL]" value="1" id="apDoNewDL<?php echo $ii; ?>" /> <?php } ?>
            <br/>            
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText">Post Title Format</strong> (<a href="#" id="apDLTMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apDLTMsgFrmt<?php echo $ii; ?>'); return false;">Show format info</a>)</div>
  
              <input name="dl[<?php echo $ii; ?>][apDLMsgTFrmt]" id="apDLMsgTFrmt" style="width: 50%;" value="<?php if ($isNew) echo "%TITLE%"; else _e(apply_filters('format_to_edit',htmlentities($gpo['dlMsgTFormat'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster'); ?>" onfocus="mxs_showFrmtInfo('apDLTMsgFrmt<?php echo $ii; ?>');" /><?php nxs_doShowHint("apDLTMsgFrmt".$ii); ?>
            </div>   
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText">Post Text Format</strong> (<a href="#" id="apDLMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apDLMsgFrmt<?php echo $ii; ?>'); return false;">Show format info</a>)</div>
              <input name="dl[<?php echo $ii; ?>][apDLMsgFrmt]" id="apDLMsgFrmt" style="width: 50%;" value="<?php if ($isNew) echo "%TEXT%"; else _e(apply_filters('format_to_edit', htmlentities($gpo['dlMsgFormat'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster'); ?>"  onfocus="mxs_showFrmtInfo('apDLMsgFrmt<?php echo $ii; ?>');" /><?php nxs_doShowHint("apDLMsgFrmt".$ii); ?>
            </div><br/>    
            
            <?php if ($gpo['dlPass']!='') { ?>
            <?php wp_nonce_field( 'rePostToDL', 'rePostToDL_wpnonce' ); ?>
            <b>Test your settings:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('DL', '<?php echo $ii; ?>'); return false;">Submit Test Post to Delicious</a>      
               
            <?php } 
            
            ?><div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div></div><?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'DL'; $lcode = 'dl'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apDLUName']) && $pval['apDLUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apDLUName']))   $options[$ii]['dlUName'] = trim($pval['apDLUName']);
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apDLPass']))    $options[$ii]['dlPass'] = 'n5g9a'.nsx_doEncode($pval['apDLPass']); else $options[$ii]['dlPass'] = '';  
        if (isset($pval['apDLMsgFrmt'])) $options[$ii]['dlMsgFormat'] = trim($pval['apDLMsgFrmt']);                                                  
        if (isset($pval['apDLMsgTFrmt'])) $options[$ii]['dlMsgTFormat'] = trim($pval['apDLMsgTFrmt']);                                                  
        if (isset($pval['apDoDL']))      $options[$ii]['doDL'] = $pval['apDoDL']; else $options[$ii]['doDL'] = 0; 
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      }
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID;
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapDL', true));   if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); $doDL = $ntOpt['doDL'];   
        $isAvailDL =  $ntOpt['dlUName']!='' && $ntOpt['dlPass']!=''; $dlMsgFormat = $ntOpt['dlMsgFormat']; $dlMsgTFormat = $ntOpt['dlMsgTFormat'];      
      ?>  
      <tr><th style="text-align:left;" colspan="2">
      <?php if ($isAvailDL) { ?><input class="nxsGrpDoChb" value="1" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="dl[<?php echo $ii; ?>][SNAPincludeDL]" <?php if (($post->post_status == "publish" && $ntOpt['isPosted'] == '1') || ($post->post_status != "publish" && ((int)$doDL == 1)) ) echo 'checked="checked" title="def"';  ?> /> <?php } ?>
      
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/dl16.png);">Delicious - publish to (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailDL) { ?><input alt="<?php echo $ii; ?>" style="float: right;" type="button" class="button" name="rePostToDL_repostButton" id="rePostToDL_button" value="<?php _e('Repost to Delicious', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToDL', 'rePostToDL_wpnonce' ); } ?>
                </td></tr>                
                
                <?php if (!$isAvailDL) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Delicious Account to AutoPost to Delicious</b>
                <?php } elseif ($post->post_status != "publish") { ?> 
               
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;"><?php _e('Title Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $dlMsgTFormat ?>" type="text" name="dl[<?php echo $ii; ?>][SNAPformatT]" size="60px" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apDLTMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apDLTMsgFrmt".$ii); ?></td></tr>
                
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;"><?php _e('Text Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $dlMsgFormat ?>" type="text" name="dl[<?php echo $ii; ?>][SNAPformat]" size="60px" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apDLMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apDLMsgFrmt".$ii); ?></td></tr>
                <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['dlMsgFormat'] = $pMeta['SNAPformat']; 
     if (isset($pMeta['SNAPformatT'])) $optMt['dlMsgTFormat'] = $pMeta['SNAPformatT'];      
     if (isset($pMeta['SNAPincludeDL'])) $optMt['doDL'] = $pMeta['SNAPincludeDL'] == 1?1:0; else { if (isset($pMeta['SNAPformat']))  $optMt['doDL'] = 0; } return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToDL_ajax")) {
  function nxs_rePostToDL_ajax() { check_ajax_referer('rePostToDL');  $postID = $_POST['id']; $options = get_option('NS_SNAutoPoster');  
    foreach ($options['dl'] as $ii=>$two) if ($ii==$_POST['nid']) {   $two['ii'] = $ii; $two['pType'] = 'aj'; //if ($two['gpPageID'].$two['gpUName']==$_POST['nid']) {  
      $gppo =  get_post_meta($postID, 'snapDL', true); $gppo =  maybe_unserialize($gppo);// prr($gppo);
      if (is_array($gppo) && isset($gppo[$ii]) && is_array($gppo[$ii])){ $ntClInst = new nxs_snapClassDL(); $two = $ntClInst->adjMetaOpt($two, $gppo[$ii]);}
      $result = nxs_doPublishToDL($postID, $two); if ($result == 200) die("Successfully sent your post to Delicious."); else die($result);        
    }    
  }
}  
if (!function_exists("doConnectToDelicious")) { function doConnectToDelicious($u, $p){ global $nxs_gCookiesArr;  $nxs_gCookiesArr = array(); $advSettings = array();
  $fldsTxt = 'username='.$u.'&password='.$p;
  $contents = getCurlPageX(' https://www.delicious.com/login ','', false, $fldsTxt, false, $advSettings);   prr($nxs_gCookiesArr);   prr($contents);
}}
if (!function_exists("doPostToDelicious")) { function doPostToDelicious($postID, $options){  global $nxs_gCookiesArr; 

}}
if (!function_exists("nxs_doPublishToDL")) { //## Second Function to Post to DL
  function nxs_doPublishToDL($postID, $options){ $ntCd = 'DL'; $ntCdL = 'dl'; $ntNm = 'Delicious';
      
      $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
      if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        nxs_addToLog($ntCd.' - '.$options['nName'], 'E', '-=Duplicate=- Post ID:'.$postID, 'Not posted. No reason for posting duplicate'); return;
      }           
      
      if ($postID=='0') { echo "Testing ... <br/><br/>"; $link = home_url(); $msgT = 'Test Link from '.$link; } else { $post = get_post($postID); if(!$post) return; $link = get_permalink($postID);  
        $msgFormat = $options['dlMsgFormat']; $msgTFormat = $options['dlMsgTFormat']; $msgT = nsFormatMessage($msgTFormat, $postID);  $msg = nsFormatMessage($msgFormat, $postID); 
        nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); 
      }
      
      $dusername = $options['dlUName']; $pass = urlencode(substr($options['dlPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['dlPass'], 5)):$options['dlPass']);
      $api = "api.del.icio.us/v1"; $link = urlencode($link); $desc = urlencode(substr($msgT, 0, 250)); $ext = urlencode(substr($msg, 0, 1000));
      
      $extInfo = ' | PostID: '.$postID." - ".$post->post_title; $logNT = '<span style="color:#000080">Delicious</span> - '.$options['nName'];
      
      $t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = urlencode(implode(',',$tggs));     $tags = str_replace(' ','+',$tags); 
      $apicall = "https://$dusername:$pass@$api/posts/add?&url=$link&description=$desc&extended=$ext&tags=$tags"; 
      $cnt = wp_remote_get( $apicall, '' );// prr($cnt);      
      
      if(is_wp_error($cnt)) { $error_string = $cnt->get_error_message(); if (stripos($error_string, ' timed out')!==false) { sleep(10); $cnt = wp_remote_get( $apicall, '' );}}      
      if(is_wp_error($cnt)) {
        $ret = 'Something went wrong - '."https://$dusername:*********@$api/posts/add?&url=$link&description=$desc&extended=$ext&tags=$tags"; nxs_addToLog($logNT, 'E', '-=ERROR=- '.$ret. "ERR: ".print_r($cnt, true), $extInfo);
      } else {      
        if (is_array($cnt) &&  stripos($cnt['body'],'code="done"')!==false) { $ret = 'OK'; nxs_metaMarkAsPosted($postID, 'DL', $options['ii']);  nxs_addToLog($logNT, 'M', 'OK - Message Posted ', $extInfo); } 
        elseif (is_array($cnt) &&  stripos($cnt['body'],'item already exists')!==false) { $ret = '..All good, but this link has already been bookmarked..'; nxs_addToLog($logNT, 'M', 'All good, but this link has already been bookmarked', $extInfo); }   
          else { if ($cnt['response']['code']=='401') $ret = " Incorrect Username/Password "; else  $ret = 'Something went wrong - '."https://$dusername:*********@$api/posts/add?&url=$link&description=$desc&extended=$ext&tags=$tags"; nxs_addToLog($logNT, 'E', '-=ERROR=- '.$ret. "ERR: ".print_r($cnt, true), $extInfo);
          }
      }
      if ($ret!='OK') { if ($postID=='0') echo $ret; } else if ($postID=='0') { echo 'OK - Message Posted, please see your Delicious Page'; nxs_addToLog($logNT, 'M', 'OK - TEST Message Posted '); }
      if ($ret == 'OK') return 200; else return $ret;
      
  }
}  
?>