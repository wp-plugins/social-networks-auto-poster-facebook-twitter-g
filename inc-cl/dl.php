<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'DL', 'lcode'=>'dl', 'name'=>'Delicious');

if (!class_exists("nxs_snapClassDL")) { class nxs_snapClassDL {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){ global $nxs_plurl; $ntInfo = array('code'=>'DL', 'lcode'=>'dl', 'name'=>'Delicious', 'defNName'=>'dlUName', 'tstReq' => false); ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> 
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = $pbo[$ntInfo['defNName']]; ?>
          <p style="margin:0px;margin-left:5px;"> <img id="<?php echo $ntInfo['code'].$indx;?>LoadingImg" style="display: none;" src='<?php echo $nxs_plurl; ?>img/ajax-loader-sm.gif' />
            <input value="0" name="<?php echo $ntInfo['lcode']; ?>[<?php echo $indx; ?>][apDo<?php echo $ntInfo['code']; ?>]" type="hidden" />             
            <?php if ((int)$pbo['do'.$ntInfo['code']] == 1 && isset($pbo['catSel']) && (int)$pbo['catSel'] == 1) { ?> <input type="radio" name="<?php echo $ntInfo['lcode']; ?>[<?php echo $indx; ?>][apDo<?php echo $ntInfo['code']; ?>]" id="rbtn<?php echo $ntInfo['lcode'].$indx; ?>" value="1" checked="checked" onmouseout="nxs_hidePopUpInfo('popOnlyCat');" onmouseover="nxs_showPopUpInfo('popOnlyCat', event);" /> <?php } else { ?>            
            <input value="1" name="<?php echo $ntInfo['lcode']; ?>[<?php echo $indx; ?>][apDo<?php echo $ntInfo['code']; ?>]" type="checkbox" <?php if ((int)$pbo['do'.$ntInfo['code']] == 1 && $pbo['catSel']!='1') echo "checked"; ?> />
           <?php } ?>
            <?php if (isset($pbo['catSel']) && (int)$pbo['catSel'] == 1) { ?> <span onmouseout="nxs_hidePopUpInfo('popOnlyCat');" onmouseover="nxs_showPopUpInfo('popOnlyCat', event);"><?php echo "*[".(substr_count($pbo['catSelEd'], ",")+1)."]*" ?></span><?php } ?>
            <?php if (isset($pbo['rpstOn']) && (int)$pbo['rpstOn'] == 1) { ?> <span onmouseout="nxs_hidePopUpInfo('popReActive');" onmouseover="nxs_showPopUpInfo('popReActive', event);"><?php echo "*[R]*" ?></span><?php } ?>
            <strong><?php  _e('Auto-publish to', 'nxs_snap'); ?> <?php echo $ntInfo['name']; ?> <i style="color: #005800;"><?php if($pbo['nName']!='') echo "(".$pbo['nName'].")"; ?></i></strong>
          &nbsp;&nbsp;<?php if ($ntInfo['tstReq'] && (!isset($pbo[$ntInfo['lcode'].'OK']) || $pbo[$ntInfo['lcode'].'OK']=='')){ ?><b style="color: #800000"><?php  _e('Attention requred. Unfinished setup', 'nxs_snap'); ?> ==&gt;</b><?php } ?><a id="do<?php echo $ntInfo['code'].$indx; ?>AG" href="#" onclick="doGetHideNTBlock('<?php echo $ntInfo['code'];?>' , '<?php echo $indx; ?>');return false;">[<?php  _e('Show Settings', 'nxs_snap'); ?>]</a>&nbsp;&nbsp;
          <a href="#" onclick="doDelAcct('<?php echo $ntInfo['lcode']; ?>', '<?php echo $indx; ?>', '<?php if (isset($pbo['bgBlogID'])) echo $pbo['nName']; ?>');return false;">[<?php  _e('Remove Account', 'nxs_snap'); ?>]</a>
          </p><div id="nxsNTSetDiv<?php echo $ntInfo['code'].$indx; ?>"></div><?php //$pbo['ntInfo'] = $ntInfo; $this->showNTSettings($indx, $pbo);             
        }?>
      </div>
    </div> <?php 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($mgpo){ $options = array('nName'=>'', 'doDL'=>'1', 'dlUName'=>'', 'dlPageID'=>'', 'dlAttch'=>'', 'dlPass'=>''); $options['ntInfo']= array('lcode'=>'dl'); $this->showNTSettings($mgpo, $options, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; $nt = $options['ntInfo']['lcode']; $ntU = strtoupper($nt); 
    if (!isset($options['nHrs'])) $options['nHrs'] = 0; if (!isset($options['nMin'])) $options['nMin'] = 0;  if (!isset($options['catSel'])) $options['catSel'] = 0;  if (!isset($options['catSelEd'])) $options['catSelEd'] = ''; 
    if (!isset($options['nDays'])) $options['nDays'] = 0; if (!isset($options['qTLng'])) $options['qTLng'] = '';  ?>
            <div id="doDL<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>" style="max-width: 1000px; background-color: #EBF4FB; margin: 10px; border: 1px solid #808080; padding: 10px; display:none;">     <input type="hidden" name="apDoSDL<?php echo $ii; ?>" value="0" id="apDoSDL<?php echo $ii; ?>" />          
            
             <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/dl16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-delicious-social-networks-auto-poster-wordpress/"><?php $nType="Delicious"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
            
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easily identify it', 'nxs_snap'); ?></i> </div><input name="dl[<?php echo $ii; ?>][nName]" id="dlnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit',htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('dl', $ii, $options['qTLng']); ?>  
            
            <br/>
    <ul class="nsx_tabs">
    <li><a href="#nsx<?php echo $nt.$ii ?>_tab1"><?php _e('Account Info', 'nxs_snap'); ?></a></li>    
    <?php if (!$isNew) { ?>  <li><a href="#nsx<?php echo $nt.$ii ?>_tab2"><?php _e('Advanced', 'nxs_snap'); ?></a></li>  <?php } ?>
    </ul>
    <div class="nsx_tab_container"><?php /* ######################## Account Tab ####################### */ ?>
    <div id="nsx<?php echo $nt.$ii ?>_tab1" class="nsx_tab_content" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $nt; ?>-bg.png); background-repeat: no-repeat;  background-position:90% 10%;">
    
            
            <div style="width:100%;"><strong>Delicious Username:</strong> </div><input name="dl[<?php echo $ii; ?>][apDLUName]" id="apDLUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',htmlentities($options['dlUName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
            <div style="width:100%;"><strong>Delicious Password:</strong> </div><input name="dl[<?php echo $ii; ?>][apDLPass]" id="apDLPass" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($options['dlPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['dlPass'], 5)):$options['dlPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/>                
            
            <?php if ($isNew) { ?> <input type="hidden" name="dl[<?php echo $ii; ?>][apDoDL]" value="1" id="apDoNewDL<?php echo $ii; ?>" /> <?php } ?>
            <br/>            
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Title Format', 'nxs_snap'); ?></strong> (<a href="#" id="apDLTMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apDLTMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)</div>
  
              <input name="dl[<?php echo $ii; ?>][apDLMsgTFrmt]" id="apDLMsgTFrmt" style="width: 50%;" value="<?php if ($isNew) echo "%TITLE%"; else _e(apply_filters('format_to_edit',htmlentities($options['dlMsgTFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?>" onfocus="mxs_showFrmtInfo('apDLTMsgFrmt<?php echo $ii; ?>');" /><?php nxs_doShowHint("apDLTMsgFrmt".$ii); ?>
            </div>   
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Text Format', 'nxs_snap'); ?></strong> (<a href="#" id="apDLMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apDLMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)</div>
  
  <textarea cols="150" rows="3" id="di<?php echo $ii; ?>SNAPformat" name="dl[<?php echo $ii; ?>][apDLMsgFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#dl<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apDLMsgFrmt<?php echo $ii; ?>');"><?php if ($isNew) echo "%EXCERPT%"; else _e(apply_filters('format_to_edit', htmlentities($options['dlMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?></textarea>
  
              <?php nxs_doShowHint("apDLMsgFrmt".$ii); ?>
            </div><br/>    
            
            <?php if ($options['dlPass']!='') { ?>
            
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('DL', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>      
               
            <?php } 
            
            ?></div>
            <?php /* ######################## Advanced Tab ####################### */ ?>
   <?php if (!$isNew) { ?>  <div id="nsx<?php echo $nt.$ii ?>_tab2" class="nsx_tab_content">
    
    <?php nxs_showCatTagsCTFilters($nt, $ii, $options); 
      nxs_addPostingDelaySelV3($nt, $ii, $options['nHrs'], $options['nMin'], $options['nDays']); 
      nxs_showRepostSettings($nt, $ii, $options); ?> 
            
            
    </div> <?php } ?> <?php /* #### End of Tab #### */ ?>
    </div><br/> <?php /* #### End of Tabs #### */ ?>
    
    <div class="submitX nxclear" style="padding-bottom: 0px;"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div></div><?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){$code = 'DL'; $lcode = 'dl'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apDLUName']) && $pval['apDLUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apDLUName']))   $options[$ii]['dlUName'] = trim($pval['apDLUName']);
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apDLPass']))    $options[$ii]['dlPass'] = 'n5g9a'.nsx_doEncode($pval['apDLPass']); else $options[$ii]['dlPass'] = '';  
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']); else $options[$ii]['catSel'] = 0;
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
        
        if (isset($pval['apDLMsgFrmt'])) $options[$ii]['dlMsgFormat'] = trim($pval['apDLMsgFrmt']);                                                  
        if (isset($pval['apDLMsgTFrmt'])) $options[$ii]['dlMsgTFormat'] = trim($pval['apDLMsgTFrmt']);                                                  
        if (isset($pval['apDoDL']))      $options[$ii]['doDL'] = $pval['apDoDL']; else $options[$ii]['doDL'] = 0; 
         
        $options[$ii] = nxs_adjRpst($options[$ii], $pval);   
        
        if (isset($pval['delayDays'])) $options[$ii]['nDays'] = trim($pval['delayDays']);
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      } elseif ( count($pval)==1 ) if (isset($pval['apDo'.$code])) $options[$ii]['do'.$code] = $pval['apDo'.$code]; else $options[$ii]['do'.$code] = 0; 
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; $nt = 'dl'; $ntU = 'DL';
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapDL', true));   if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
        $doDL = $ntOpt['doDL'] && (is_array($pMeta) || $ntOpt['catSel']!='1');   
        $isAvailDL =  $ntOpt['dlUName']!='' && $ntOpt['dlPass']!=''; $dlMsgFormat = htmlentities($ntOpt['dlMsgFormat'], ENT_COMPAT, "UTF-8"); $dlMsgTFormat = htmlentities($ntOpt['dlMsgTFormat'], ENT_COMPAT, "UTF-8");      
      ?>  
      <tr><th style="text-align:left;" colspan="2">
      <?php if ($ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='')  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if (!empty($ntOpt['tagsSelX'])) { ?>  <input type="hidden" class="nxs_TG" id="nxs_TG_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['tagsSelX']; ?>" /> <?php } ?>
      <?php if ($isAvailDL) { ?><input class="nxsGrpDoChb" value="1" id="doDL<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="dl[<?php echo $ii; ?>][doDL]" <?php if ((int)$doDL == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="dl[<?php echo $ii; ?>][doDL]" value="<?php echo $doDL;?>"> <?php } ?> <?php } ?>
      
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/dl16.png);">Delicious - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailDL) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToDL_repostButton" id="rePostToDL_button" value="<?php _e('Repost to Delicious', 'nxs_snap') ?>" />
                    <?php } ?>
                    
                    <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) {                         
                        ?> <span id="pstdDL<?php echo $ii; ?>" style="float: right; padding-top: 4px; padding-right: 10px;">
          <a style="font-size: 10px;" href="http://delicious.com/<?php echo $ntOpt['dlUName']; ?>" target="_blank"><?php $nType="Delicious"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>                
                
                <?php if (!$isAvailDL) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Delicious Account to AutoPost to Delicious</b>
                <?php } else { if ($post->post_status != "publish" && function_exists('nxs_doSMAS5') ) { $ntOpt['postTime'] = get_post_time('U', false, $post_id); nxs_doSMAS5($nt, $ii, $ntOpt); } ?>
                
                <?php if ($ntOpt['rpstOn']=='1') { ?> 
                
                <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow">
                <input value="0"  type="hidden" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"/><input value="nxsi<?php echo $ii; ?>dl" type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"  <?php if (!empty($ntOpt['rpstPostIncl'])) echo "checked"; ?> /> 
                </th>
                <td> <?php _e('Include in "Auto-Reposting" to this network.', 'nxs_snap') ?>                
                </td></tr> <?php } ?>
                
               
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
     if (isset($pMeta['imgToUse'])) $optMt['imgToUse'] = $pMeta['imgToUse'];      
     if (isset($pMeta['timeToRun']))  $optMt['timeToRun'] = $pMeta['timeToRun'];  if (isset($pMeta['rpstPostIncl']))  $optMt['rpstPostIncl'] = $pMeta['rpstPostIncl'];      
     if (isset($pMeta['doDL'])) $optMt['doDL'] = $pMeta['doDL'] == 1?1:0; else { if (isset($pMeta['SNAPformat']))  $optMt['doDL'] = 0; } 
     if (isset($pMeta['SNAPincludeDL']) && $pMeta['SNAPincludeDL'] == '1' ) $optMt['doDL'] = 1;  
     return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToDL_ajax")) {
  function nxs_rePostToDL_ajax() { check_ajax_referer('nxsSsPageWPN');  $postID = $_POST['id']; $options = get_option('NS_SNAutoPoster');  
    foreach ($options['dl'] as $ii=>$two) if ($ii==$_POST['nid']) {   $two['ii'] = $ii; $two['pType'] = 'aj'; //if ($two['gpPageID'].$two['gpUName']==$_POST['nid']) {  
      $gppo =  get_post_meta($postID, 'snapDL', true); $gppo =  maybe_unserialize($gppo);// prr($gppo);
      if (is_array($gppo) && isset($gppo[$ii]) && is_array($gppo[$ii])){ $ntClInst = new nxs_snapClassDL(); $two = $ntClInst->adjMetaOpt($two, $gppo[$ii]);}
      $result = nxs_doPublishToDL($postID, $two); if ($result == 200) die("Successfully sent your post to Delicious."); else die($result);        
    }    
  }
}  


if (!function_exists("nxs_doPublishToDL")) { //## Second Function to Post to DL
  function nxs_doPublishToDL($postID, $options){ $ntCd = 'DL'; $ntCdL = 'dl'; $ntNm = 'Delicious'; global $plgn_NS_SNAutoPoster;  if (!is_array($options)) $options = maybe_unserialize(get_post_meta($postID, $options, true));
      // if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToDL',  array($postID, $options));      
      $addParams = nxs_makeURLParams(array('NTNAME'=>$ntNm, 'NTCODE'=>$ntCd, 'POSTID'=>$postID, 'ACCNAME'=>$options['nName']));     
      $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url();  
      $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
      $logNT = '<span style="color:#000080">Delicious</span> - '.$options['nName'];
      $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
      if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') {  sleep(5);
          nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'); return;
        }
      }  
      if ($postID=='0') { echo "Testing ... <br/><br/>"; $urlToGo = home_url(); $msgT = 'Test Link from '.$urlToGo; } else { $post = get_post($postID); if(!$post) return; 
        $options['dlMsgTFormat'] = nsFormatMessage($options['dlMsgTFormat'], $postID);  $options['dlMsgFormat'] = nsFormatMessage($options['dlMsgFormat'], $postID); 
        nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); 
        //## MyURL - URLToGo code
        if (!isset($options['urlToUse']) || trim($options['urlToUse'])=='') $myurl =  trim(get_post_meta($postID, 'snap_MYURL', true)); if ($myurl!='') $options['urlToUse'] = $myurl;
        if (isset($options['urlToUse']) && trim($options['urlToUse'])!='') { $urlToGo = $options['urlToUse']; $options['useFBGURLInfo'] = true; } else $urlToGo = get_permalink($postID);      
        $gOptions = $plgn_NS_SNAutoPoster->nxs_options; $addURLParams = trim($gOptions['addURLParams']);  if($addURLParams!='') $urlToGo .= (strpos($urlToGo,'?')!==false?'&':'?').$addURLParams; 
      }
      $extInfo = ' | PostID: '.$postID." - ".(isset($post) && is_object($post)?$post->post_title:'');
      //## Create and Format message
      $t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = implode(',',$tggs);   //  $tags = str_replace(' ','+',$tags); 
      
      $message = array('url'=>$urlToGo, 'surl'=>$urlToGo, 'siteName'=>$blogTitle, 'tags'=>$tags);
      //## Actual Post
      $ntToPost = new nxs_class_SNAP_DL(); $ret = $ntToPost->doPostToNT($options, $message);
      //## Process Results
      if (!is_array($ret) || $ret['isPosted']!='1') { //## Error 
        if ($postID=='0') prr($ret); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo); 
      } else {  // ## All Good - log it.
        if ($postID=='0')  { nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); echo _e('OK - Message Posted, please see your '.$logNT.' Page. ', 'nxs_snap'); } 
          else  { nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPosted'=>'1', 'pgID'=>$ret['postID'], 'pDate'=>date('Y-m-d H:i:s'))); nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo); }
      }
      //## Return Result
      if ($ret['isPosted']=='1') return 200; else return print_r($ret, true);       
  }
}  
?>