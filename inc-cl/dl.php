<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'DL', 'lcode'=>'dl', 'name'=>'Delicious');

if (!class_exists("nxs_snapClassDL")) { class nxs_snapClassDL {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){ global $nxs_plurl; $ntInfo = array('code'=>'DL', 'lcode'=>'dl', 'name'=>'Delicious', 'defNName'=>'dlUName', 'tstReq' => false); ?>    
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
  function showNewNTSettings($mgpo){ $gpo = array('nName'=>'', 'doDL'=>'1', 'dlUName'=>'', 'dlPageID'=>'', 'dlAttch'=>'', 'dlPass'=>''); $this->showNTSettings($mgpo, $gpo, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $gpo, $isNew=false){  global $nxs_plurl; ?>
            <div id="doDL<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>" style="max-width: 1000px; background-color: #EBF4FB; background-image: url(<?php echo $nxs_plurl; ?>img/dl-bg.png);  background-position:90% 10%; background-repeat: no-repeat; margin: 10px; border: 1px solid #808080; padding: 10px; display:none;">     <input type="hidden" name="apDoSDL<?php echo $ii; ?>" value="0" id="apDoSDL<?php echo $ii; ?>" />          
            
             <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/dl16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-delicious-social-networks-auto-poster-wordpress/"><?php $nType="Delicious"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
            
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easely identify it', 'nxs_snap'); ?></i> </div><input name="dl[<?php echo $ii; ?>][nName]" id="dlnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit',htmlentities($gpo['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('dl', $ii, $gpo['qTLng']); ?><?php echo nxs_addPostingDelaySel('dl', $ii, $gpo['nHrs'], $gpo['nMin']); ?>
            
            <?php if (!$isNew) { ?>
    <div style="width:100%;"><strong><?php _e('Categories', 'nxs_snap'); ?>:</strong>
       <input value="0" id="catSelA<?php echo $ii; ?>" type="radio" name="dl[<?php echo $ii; ?>][catSel]" <?php if ((int)$gpo['catSel'] != 1) echo "checked"; ?> /> All                                  
       <input value="1" id="catSelSDL<?php echo $ii; ?>" type="radio" name="dl[<?php echo $ii; ?>][catSel]" <?php if ((int)$gpo['catSel'] == 1) echo "checked"; ?> /> <a href="#" style="text-decoration: none;" class="showCats" id="nxs_SCA_DL<?php echo $ii; ?>" onclick="jQuery('#catSelSDL<?php echo $ii; ?>').attr('checked', true); jQuery('#tmpCatSelNT').val('DL<?php echo $ii; ?>'); nxs_markCats( jQuery('#nxs_SC_DL<?php echo $ii; ?>').val() ); jQuery('#showCatSel').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false], position: [75, 'auto']}); return false;">Selected<?php if ($gpo['catSelEd']!='') echo "[".(substr_count($gpo['catSelEd'], ",")+1)."]"; ?></a>       
       <input type="hidden" name="dl[<?php echo $ii; ?>][catSelEd]" id="nxs_SC_DL<?php echo $ii; ?>" value="<?php echo $gpo['catSelEd']; ?>" />
    <br/><i><?php _e('Only selected categories will be autoposted to this account', 'nxs_snap'); ?></i></div> 
    <br/>
    <?php } ?>
            
            <div style="width:100%;"><strong>Delicious Username:</strong> </div><input name="dl[<?php echo $ii; ?>][apDLUName]" id="apDLUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',htmlentities($gpo['dlUName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
            <div style="width:100%;"><strong>Delicious Password:</strong> </div><input name="dl[<?php echo $ii; ?>][apDLPass]" id="apDLPass" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($gpo['dlPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($gpo['dlPass'], 5)):$gpo['dlPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/>                
            
            <?php if ($isNew) { ?> <input type="hidden" name="dl[<?php echo $ii; ?>][apDoDL]" value="1" id="apDoNewDL<?php echo $ii; ?>" /> <?php } ?>
            <br/>            
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Title Format', 'nxs_snap'); ?></strong> (<a href="#" id="apDLTMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apDLTMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)</div>
  
              <input name="dl[<?php echo $ii; ?>][apDLMsgTFrmt]" id="apDLMsgTFrmt" style="width: 50%;" value="<?php if ($isNew) echo "%TITLE%"; else _e(apply_filters('format_to_edit',htmlentities($gpo['dlMsgTFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?>" onfocus="mxs_showFrmtInfo('apDLTMsgFrmt<?php echo $ii; ?>');" /><?php nxs_doShowHint("apDLTMsgFrmt".$ii); ?>
            </div>   
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Text Format', 'nxs_snap'); ?></strong> (<a href="#" id="apDLMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apDLMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)</div>
  
  <textarea cols="150" rows="3" id="di<?php echo $ii; ?>SNAPformat" name="dl[<?php echo $ii; ?>][apDLMsgFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#dl<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apDLMsgFrmt<?php echo $ii; ?>');"><?php if ($isNew) echo "%EXCERPT%"; else _e(apply_filters('format_to_edit', htmlentities($gpo['dlMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?></textarea>
  
              <?php nxs_doShowHint("apDLMsgFrmt".$ii); ?>
            </div><br/>    
            
            <?php if ($gpo['dlPass']!='') { ?>
            <?php wp_nonce_field( 'rePostToDL', 'rePostToDL_wpnonce' ); ?>
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('DL', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>      
               
            <?php } 
            
            ?><div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div></div><?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'DL'; $lcode = 'dl'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apDLUName']) && $pval['apDLUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apDLUName']))   $options[$ii]['dlUName'] = trim($pval['apDLUName']);
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apDLPass']))    $options[$ii]['dlPass'] = 'n5g9a'.nsx_doEncode($pval['apDLPass']); else $options[$ii]['dlPass'] = '';  
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']);
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
        
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
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapDL', true));   if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
        $doDL = $ntOpt['doDL'] && (is_array($pMeta) || $ntOpt['catSel']!='1');   
        $isAvailDL =  $ntOpt['dlUName']!='' && $ntOpt['dlPass']!=''; $dlMsgFormat = htmlentities($ntOpt['dlMsgFormat'], ENT_COMPAT, "UTF-8"); $dlMsgTFormat = htmlentities($ntOpt['dlMsgTFormat'], ENT_COMPAT, "UTF-8");      
      ?>  
      <tr><th style="text-align:left;" colspan="2"><?php if ( $ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='' )  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_DL<?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if ($isAvailDL) { ?><input class="nxsGrpDoChb" value="1" id="doDL<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="dl[<?php echo $ii; ?>][doDL]" <?php if ((int)$doDL == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="dl[<?php echo $ii; ?>][doDL]" value="<?php echo $doDL;?>"> <?php } ?> <?php } ?>
      
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/dl16.png);">Delicious - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailDL) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToDL_repostButton" id="rePostToDL_button" value="<?php _e('Repost to Delicious', 'nxs_snap') ?>" />
                    <?php wp_nonce_field( 'rePostToDL', 'rePostToDL_wpnonce' ); } ?>
                    
                    <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) {                         
                        ?> <span id="pstdDL<?php echo $ii; ?>" style="float: right; padding-top: 4px; padding-right: 10px;">
          <a style="font-size: 10px;" href="http://delicious.com/<?php echo $ntOpt['dlUName']; ?>" target="_blank"><?php $nType="Delicious"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>                
                
                <?php if (!$isAvailDL) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Delicious Account to AutoPost to Delicious</b>
                <?php } elseif ($post->post_status != "pubZlish") { ?> 
               
                <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Title Format:', 'nxs_snap') ?></th>
                <td><input value="<?php echo $dlMsgTFormat ?>" type="text" name="dl[<?php echo $ii; ?>][SNAPformatT]"  style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apDLTMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apDLTMsgFrmt".$ii, '', '58'); ?></td></tr>
                
                <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Text Format:', 'nxs_snap') ?></th>
                <td>                
                <textarea cols="150" rows="1" id="dl<?php echo $ii; ?>SNAPformat" name="dl[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#dl<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apDLMsgFrmt<?php echo $ii; ?>');"><?php echo $dlMsgFormat; ?></textarea>
                <?php nxs_doShowHint("apDLMsgFrmt".$ii, '', '58'); ?></td></tr>
                <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = ''; 
     if (isset($pMeta['SNAPformat'])) $optMt['dlMsgFormat'] = $pMeta['SNAPformat']; 
     if (isset($pMeta['SNAPformatT'])) $optMt['dlMsgTFormat'] = $pMeta['SNAPformatT'];      
     if (isset($pMeta['doDL'])) $optMt['doDL'] = $pMeta['doDL'] == 1?1:0; else { if (isset($pMeta['SNAPformat']))  $optMt['doDL'] = 0; } 
     if (isset($pMeta['SNAPincludeDL']) && $pMeta['SNAPincludeDL'] == '1' ) $optMt['doDL'] = 1;  
     return $optMt;
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
  $contents = getCurlPageX(' http://www.delicious.com/login ','', false, $fldsTxt, false, $advSettings); //  prr($nxs_gCookiesArr);   prr($contents);
}}
if (!function_exists("doPostToDelicious")) { function doPostToDelicious($postID, $options){  global $nxs_gCookiesArr; 

}}

if (!function_exists("nxs_getDLHeaders")) {  function nxs_getDLHeaders($up){ $hdrsArr = array(); 
 $hdrsArr['Cache-Control']='no-cache'; $hdrsArr['Connection']='keep-alive'; 
 $hdrsArr['User-Agent']='SNAP for Wordpress; Ver '.NextScripts_SNAP_Version;
 $hdrsArr['Accept']='text/html, application/xhtml+xml, */*'; $hdrsArr['DNT']='1';
 $hdrsArr['Authorization'] = 'Basic ' . base64_encode("$up");
 $hdrsArr['Accept-Encoding']='gzip,deflate'; $hdrsArr['Accept-Language']='en-US,en;q=0.8'; $hdrsArr['Accept-Charset']='ISO-8859-1,utf-8;q=0.7,*;q=0.3'; return $hdrsArr;
}}

if (!function_exists("nxs_doPublishToDL")) { //## Second Function to Post to DL
  function nxs_doPublishToDL($postID, $options){ $ntCd = 'DL'; $ntCdL = 'dl'; $ntNm = 'Delicious';  
      // if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToDL',  array($postID, $options));        
      $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
      $logNT = '<span style="color:#000080">Delicious</span> - '.$options['nName'];
      $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
      if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') {  sleep(5);
          nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'); return;
        }
      }            
      
      if ($postID=='0') { echo "Testing ... <br/><br/>"; $link = home_url(); $msgT = 'Test Link from '.$link; } else { $post = get_post($postID); if(!$post) return; $link = get_permalink($postID);  
        $msgFormat = $options['dlMsgFormat']; $msgTFormat = $options['dlMsgTFormat']; $msgT = nsFormatMessage($msgTFormat, $postID);  $msg = nsFormatMessage($msgFormat, $postID); 
        nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); 
      }
      $extInfo = ' | PostID: '.$postID." - ".$post->post_title; 
      $dusername = $options['dlUName']; $pass = (substr($options['dlPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['dlPass'], 5)):$options['dlPass']);
      $api = "api.del.icio.us/v1"; $link = urlencode($link); $desc = urlencode(substr($msgT, 0, 250)); $ext = urlencode(substr($msg, 0, 1000));      
      $t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = urlencode(implode(',',$tggs));     $tags = str_replace(' ','+',$tags); 
      $apicall = "https://$api/posts/add?red=api&url=$link&description=$desc&extended=$ext&tags=$tags";
      $hdrsArr = nxs_getDLHeaders($dusername.':'.$pass); $cnt = wp_remote_get( $apicall, array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr) ); // prr($cnt);      
      
      if(is_wp_error($cnt)) { $error_string = $cnt->get_error_message(); if (stripos($error_string, ' timed out')!==false) { sleep(10); 
        $cnt = wp_remote_get( $apicall, array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr) );}
      }      
      if(is_wp_error($cnt)) {
        $ret = 'Something went wrong - '."http://$dusername:*********@$api/posts/add?&url=$link&description=$desc&extended=$ext&tags=$tags"; nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.$ret. "ERR: ".print_r($cnt, true), $extInfo);
      } else {      
        if (is_array($cnt) &&  stripos($cnt['body'],'code="done"')!==false) 
          { $ret = 'OK'; nxs_metaMarkAsPosted($postID, 'DL', $options['ii'], array('isPosted'=>'1', 'pgID'=>'DL', 'pDate'=>date('Y-m-d H:i:s')));  nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo); } 
        elseif (is_array($cnt) &&  $cnt['body']=='<?xml version="1.0" encoding="UTF-8"?>') { $ret = 'It looks like Delicious  API is Down'; nxs_addToLogN('E', 'Error', $logNT, 'It looks like Delicious API is Down', $extInfo); }    
        elseif (is_array($cnt) &&  stripos($cnt['body'],'item already exists')!==false) { $ret = '..All good, but this link has already been bookmarked..'; nxs_addToLogN('S', 'Skipped', $logNT, 'All good, but this link has already been bookmarked', $extInfo); }   
          else { if ($cnt['response']['code']=='401') $ret = " Incorrect Username/Password "; else  $ret = 'Something went wrong - '."https://$dusername:*********@$api/posts/add?&url=$link&description=$desc&extended=$ext&tags=$tags"; 
            nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.$ret. "ERR: ".print_r($cnt, true), $extInfo);
          }
      }
      if ($ret!='OK') { if ($postID=='0') echo $ret; } else if ($postID=='0') { echo 'OK - Message Posted, please see your Delicious Page'; nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); }
      if ($ret == 'OK') return 200; else return $ret;
      
  }
}  
?>