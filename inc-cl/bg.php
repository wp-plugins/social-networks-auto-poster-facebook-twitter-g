<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'BG', 'lcode'=>'bg', 'name'=>'Blogger');

if (!class_exists("nxs_snapClassBG")) { class nxs_snapClassBG { var $ntInfo = array('code'=>'BG', 'lcode'=>'bg', 'name'=>'Blogger', 'defNName'=>'bgUName', 'tstReq' => true);
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){ global $nxs_plurl; $ntInfo = $this->ntInfo; ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> 
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = $pbo[$ntInfo['defNName']]; ?>
          <p style="margin:0px;margin-left:5px;"><img id="<?php echo $ntInfo['code'].$indx;?>LoadingImg" style="display: none;" src='<?php echo $nxs_plurl; ?>img/ajax-loader-sm.gif' />
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
    </div> <?php //## END Settings 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($bo){ $po = array('nName'=>'', 'doBG'=>'1', 'bgUName'=>'', 'bgPass'=>'', 'bgBlogID'=>'', 'bgInclTags'=>'1', 'bgMsgFormat'=>'%FULLTEXT% <br/><a href=\'%URL%\'>%TITLE%</a>', 'bgMsgTFormat'=>'%TITLE%' ); 
    $po['ntInfo']= array('lcode'=>'bg'); $this->showNTSettings($bo, $po, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; $nt = $options['ntInfo']['lcode']; $ntU = strtoupper($nt); 
    if (!isset($options['nHrs'])) $options['nHrs'] = 0; if (!isset($options['nMin'])) $options['nMin'] = 0;  if (!isset($options['catSel'])) $options['catSel'] = 0;  if (!isset($options['catSelEd'])) $options['catSelEd'] = ''; 
    if (!isset($options['nDays'])) $options['nDays'] = 0; if (!isset($options['qTLng'])) $options['qTLng'] = '';  ?>
    <div id="doBG<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew){ ?> clNewNTSets<?php } ?>"> <input type="hidden" value="0" id="apDoS<?php echo $ntU.$ii; ?>" />
    <?php if ($isNew) { ?> <input type="hidden" name="bg[<?php echo $ii; ?>][apDoBG]" value="1" id="apDoNewBG<?php echo $ii; ?>" /> <?php } ?>
    
    <div style="display: none;"><input name="bg[<?php echo $ii; ?>][apBGPassChr]" id="apBGPassChr" type="password" value="" /></div>
            
            <div id="doBG<?php echo $ii; ?>Div" style="margin-left: 10px;"> <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/bg16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-blogger-social-networks-auto-poster-wordpress/"><?php $nType="Blogger"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
            
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easily identify it', 'nxs_snap'); ?></i> </div><input name="bg[<?php echo $ii; ?>][nName]" id="bgnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit',htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('bg', $ii, $options['qTLng']); ?>
            
            
          <br/>
    <ul class="nsx_tabs">
    <li><a href="#nsx<?php echo $nt.$ii ?>_tab1"><?php _e('Account Info', 'nxs_snap'); ?></a></li>        
    <?php if (!$isNew) { ?>  <li><a href="#nsx<?php echo $nt.$ii ?>_tab2"><?php _e('Advanced', 'nxs_snap'); ?></a></li>  <?php } ?>
    </ul>
    <div class="nsx_tab_container"><?php /* ######################## Account Tab ####################### */ ?>
    <div id="nsx<?php echo $nt.$ii ?>_tab1" class="nsx_tab_content" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $nt; ?>-bg.png); background-repeat: no-repeat;  background-position:90% 10%;">
    
    
            
            <div style="width:100%;"><strong>Blogger Username/Email:</strong> </div><input name="bg[<?php echo $ii; ?>][apBGUName]" id="apBGUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',htmlentities($options['bgUName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
            <div style="width:100%;"><strong>Blogger Password:</strong> </div><input name="bg[<?php echo $ii; ?>][apBGPass]" id="apBGPass" autocomplete="off" type="password" style="width: 30%;" value="<?php if (trim($options['bgPass'])!='') _e(apply_filters('format_to_edit', htmlentities(substr($options['bgPass'], 0, 5)=='b4d7s'?nsx_doDecode(substr($options['bgPass'], 5)):$options['bgPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/>                
            <div style="width:100%;"><strong>Blogger Blog ID:</strong> 
            <p style="font-size: 11px; margin: 0px;"><?php _e('Log to your Blogger management panel and look at the URL of your blog: http://www.blogger.com/blogger.g?blogID=8959085979163812093#allposts. Your Blog ID will be: 8959085979163812093', 'nxs_snap'); ?></p>
            </div><input name="bg[<?php echo $ii; ?>][apBGBlogID]" id="apBGBlogID" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['bgBlogID'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /> 
            <br/><br/>
            
   <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Title Format', 'nxs_snap'); ?>:</strong> (<a href="#" id="apBGTMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apBGTMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)</div> 
              
              <input name="bg[<?php echo $ii; ?>][apBGMsgTFrmt]" id="apBGMsgTFrmt" style="width: 50%;" value="<?php if ($options['bgMsgTFormat']!='') _e(apply_filters('format_to_edit', htmlentities($options['bgMsgTFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); else echo "%TITLE%"; ?>" onfocus="mxs_showFrmtInfo('apBGTMsgFrmt<?php echo $ii; ?>');" /><?php nxs_doShowHint("apBGTMsgFrmt".$ii); ?><br/>
            
            <div id="altFormat" style="">
   <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Text Format', 'nxs_snap'); ?>:</strong> (<a href="#" id="apBGMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apBGMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>) 
   
   <!-- 
   HTML is <?php if(!function_exists('doPostToGooglePlus')) {?> <b>NOT</b> <?php } ?> allowed. <?php if(!function_exists('doPostToGooglePlus')) {?> <i>- Blogger "Free API" limitation. Please get <a href="http://www.nextscripts.com/google-plus-automated-posting/#blogger">NextScripts API</a> to allow HTML</i> <?php } ?>   -->
   </div>  
   
   <textarea cols="150" rows="3" id="bg<?php echo $ii; ?>SNAPformat" name="bg[<?php echo $ii; ?>][apBGMsgFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#bg<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apBGMsgFrmt<?php echo $ii; ?>');"><?php if ($options['bgMsgFormat']!='') _e(apply_filters('format_to_edit',htmlentities($options['bgMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap');  else echo "%FULLTEXT% <br/><a href='%URL%'>%TITLE%</a>"; ?></textarea>
   
   <?php nxs_doShowHint("apBGMsgFrmt".$ii, __('HTML is allowed', 'nxs_snap'));  ?>
            </div>
            
             <p style="margin-bottom: 20px;margin-top: 5px;"><input value="1"  id="bgInclTags" type="checkbox" name="bg[<?php echo $ii; ?>][bgInclTags]"  <?php if ((int)$options['bgInclTags'] == 1) echo "checked"; ?> /> 
              <strong><?php _e('Post with tags', 'nxs_snap'); ?></strong>  <?php _e('Tags from the blogpost will be auto-posted to Blogger/Blogspot', 'nxs_snap'); ?>                                                               
            </p> 
            
            <?php if ($options['bgPass']!='') { ?>
            
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <?php if (!isset($options['bgOK']) || $options['bgOK']!='1') { ?> <div class="blnkg">=== <?php _e('Submit Test Post to Finish Configuration', 'nxs_snap'); ?> ===&gt;</div> <?php } ?> <a href="#" class="NXSButton" onclick="testPost('BG', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>         
            <?php } ?>
            
            </div>
            <?php /* ######################## Advanced Tab ####################### */ ?>
    <?php if (!$isNew) { ?><div id="nsx<?php echo $nt.$ii ?>_tab2" class="nsx_tab_content">
    <?php nxs_showCatTagsCTFilters($nt, $ii, $options); 
      nxs_addPostingDelaySelV3($nt, $ii, $options['nHrs'], $options['nMin'], $options['nDays']); 
      nxs_showRepostSettings($nt, $ii, $options); ?>

            
    </div> <?php } ?>             <?php /* #### End of Tab #### */ ?>
    </div><br/> <?php /* #### End of Tabs #### */ ?>
    
    <div class="submitX nxclear" style="padding-bottom: 0px;"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div>
            
            
            </div>
        </div>
        <?php
      
      
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ $code = $this->ntInfo['code'];
    foreach ($post as $ii => $pval){// prr($pval);
      if (isset($pval['apBGUName']) && trim($pval['apBGUName'])!='' && isset($pval['apBGPass']) && trim($pval['apBGPass'])!='') { if (!isset($options[$ii])) $options[$ii] = array();
        
                if (isset($pval['apDoBG']))   $options[$ii]['doBG'] = $pval['apDoBG']; else $options[$ii]['doBG'] = 0;
                
                if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']); else $options[$ii]['catSel'] = 0;
                if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
                
                if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
                if (isset($pval['apBGUName']))   $options[$ii]['bgUName'] = trim($pval['apBGUName']);
                if (isset($pval['apBGPass']))    $options[$ii]['bgPass'] = 'b4d7s'.nsx_doEncode($pval['apBGPass']); else $options[$ii]['bgPass'] = '';
                if (isset($pval['apBGBlogID']))   $options[$ii]['bgBlogID'] = trim($pval['apBGBlogID']);                
                if (isset($pval['apBGMsgFrmt'])) $options[$ii]['bgMsgFormat'] = trim($pval['apBGMsgFrmt']);                   
                if (isset($pval['apBGMsgTFrmt']))    $options[$ii]['bgMsgTFormat'] = trim($pval['apBGMsgTFrmt']);         
                if (isset($pval['bgInclTags']))    $options[$ii]['bgInclTags'] = $pval['bgInclTags'];  else $options[$ii]['bgInclTags'] = 0;        
                
                $options[$ii] = nxs_adjRpst($options[$ii], $pval);       
        
                if (isset($pval['delayDays'])) $options[$ii]['nDays'] = trim($pval['delayDays']);
                if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
                if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
                
      } elseif ( count($pval)==1 ) if (isset($pval['apDo'.$code])) $options[$ii]['do'.$code] = $pval['apDo'.$code]; else $options[$ii]['do'.$code] = 0; 
    } return $options;
  } 
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID;  $nt = 'bg'; $ntU = 'BG';
    foreach($ntOpts as $ii=>$ntOpt){ $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapBG', true)); if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
       if (empty($ntOpt['imgToUse'])) $ntOpt['imgToUse'] = '';
       $doBG = $ntOpt['doBG'] && (is_array($pMeta) || $ntOpt['catSel']!='1');   $imgToUse = $ntOpt['imgToUse']; 
       $isAvailBG =  $ntOpt['bgUName']!='' && $ntOpt['bgPass']!='';  $bgMsgFormat = htmlentities($ntOpt['bgMsgFormat'], ENT_COMPAT, "UTF-8");  $bgMsgTFormat = htmlentities($ntOpt['bgMsgTFormat'], ENT_COMPAT, "UTF-8");
      ?>  
      
   <tr><th style="text-align:left;" colspan="2">
      <?php if ($ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='')  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if (!empty($ntOpt['tagsSel'])) { ?>  <input type="hidden" class="nxs_TG" id="nxs_TG_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['tagsSel']; ?>" /> <?php } ?>
      <?php if ($isAvailBG) { ?><input class="nxsGrpDoChb" value="1" id="doBG<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="bg[<?php echo $ii; ?>][doBG]" <?php if ((int)$doBG == 1) echo 'checked="checked" title="def"';  ?> /> <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="bg[<?php echo $ii; ?>][doBG]" value="<?php echo $doBG;?>"> <?php } ?> <?php } ?>
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/bg16.png);">Blogger - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td style="min-width: 180px; width: 350px;" ><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailBG) { ?>
                    <input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToBG_repostButton" id="rePostToBG_button" value="<?php _e('Repost to Blogger', 'nxs_snap') ?>" />                    
                    <?php } ?>
                    
                    <?php  if (is_array($pMeta) && !empty($pMeta[$ii]) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) {                         
                        ?> <span id="pstdBG<?php echo $ii; ?>" style="float: right; padding-top: 4px; padding-right: 10px;">
          <a style="font-size: 10px;" href="<?php echo $pMeta[$ii]['pgID']; ?>" target="_blank"><?php $nType="Blogger"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?><?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>                    
                    
                </td></tr>
                <?php if (!$isAvailBG) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b><?php _e('Setup your Blogger Account to AutoPost to Blogger', 'nxs_snap') ?></b>
                <?php }  else { if ($post->post_status != "publish" && function_exists('nxs_doSMAS5') ) { $ntOpt['postTime'] = get_post_time('U', false, $post_id); nxs_doSMAS5($nt, $ii, $ntOpt); } ?>
                
                <?php if ($ntOpt['rpstOn']=='1') { ?> 
                
                <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow">
                <input value="0"  type="hidden" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"/><input value="nxsi<?php echo $ii; ?>bg" type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"  <?php if (!empty($ntOpt['rpstPostIncl'])) echo "checked"; ?> /> 
                </th>
                <td> <?php _e('Include in "Auto-Reposting" to this network.', 'nxs_snap') ?>                
                </td></tr> <?php } ?>
                
                 <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Title Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $bgMsgTFormat ?>" type="text" name="bg[<?php echo $ii; ?>][SNAPTformat]" style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apBGTMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apBGTMsgFrmt".$ii, '', '58'); ?></td></tr>
                <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Message Format:', 'NS_SPAP') ?></th>
                <td>
                <textarea cols="150" rows="1" id="bg<?php echo $ii; ?>SNAPformat" name="bg[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#bg<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apBGMsgFrmt<?php echo $ii; ?>');"><?php echo $bgMsgFormat; ?></textarea>                
                <?php nxs_doShowHint("apBGMsgFrmt".$ii, '', '58'); ?></td></tr>                
                <?php }
    }      
  }
  
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['bgMsgFormat'] = $pMeta['SNAPformat'];  if (isset($pMeta['imgToUse'])) $optMt['imgToUse'] = $pMeta['imgToUse'];      
     if (isset($pMeta['timeToRun']))  $optMt['timeToRun'] = $pMeta['timeToRun'];  if (isset($pMeta['rpstPostIncl']))  $optMt['rpstPostIncl'] = $pMeta['rpstPostIncl'];    
     if (isset($pMeta['SNAPTformat'])) $optMt['bgMsgTFormat'] = $pMeta['SNAPTformat'];      
     if (isset($pMeta['doBG'])) $optMt['doBG'] = $pMeta['doBG'] == 1?1:0; else { if (isset($pMeta['SNAPformat']))  $optMt['doBG'] = 0; } 
     if (isset($pMeta['SNAPincludeBG']) && $pMeta['SNAPincludeBG'] == '1' ) $optMt['doBG'] = 1;  
     return $optMt;
  }
}}

if (!function_exists("nxs_rePostToBG_ajax")) { function nxs_rePostToBG_ajax() {  check_ajax_referer('nxsSsPageWPN');  $postID = $_POST['id']; // $result = nsPublishTo($id, 'FB', true);   
      global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
      foreach ($options['bg'] as $ii=>$po) if ($ii==$_POST['nid']) {   $po['ii'] = $ii; $po['pType'] = 'aj';
      $mpo =  get_post_meta($postID, 'snapBG', true); $mpo =  maybe_unserialize($mpo);       
      if (is_array($mpo) && isset($mpo[$ii]) && is_array($mpo[$ii]) ){ $ntClInst = new nxs_snapClassBG(); $po = $ntClInst->adjMetaOpt($po, $mpo[$ii]); } 
      $result = nxs_doPublishToBG($postID, $po);  if ($result == 200) { $options['bg'][$ii]['bgOK']=1;  update_option('NS_SNAutoPoster', $options); }
      
      if ($result == 200) die("Successfully sent your post to Blogger."); else die($result);
    }    
  }
}

if (!function_exists("nxs_doPublishToBG")) { //## Second Function to Post to BG
  function nxs_doPublishToBG($postID, $options){ $ntCd = 'BG'; $ntCdL = 'bg'; $ntNm = 'Blogger';  if (!is_array($options)) $options = maybe_unserialize(get_post_meta($postID, $options, true));
    //$backtrace = debug_backtrace(); nxs_addToLogN('W', 'Enter', $ntCd, 'I am here - '.$ntCd."|".print_r($backtrace, true), '');  
   // if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToBG',  array($postID, $options));
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url();     
    $addParams = nxs_makeURLParams(array('NTNAME'=>$ntNm, 'NTCODE'=>$ntCd, 'ACCNAME'=>$options['nName'], 'POSTID'=>$postID));
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10));     
    $logNT = '<span style="color:#F87907">'.$ntNm.'</span> - '.$options['nName']; 
    $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);   
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
      $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') {  
         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'); return;
      }
    }         
    
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $options['bgMsgTFormat'] = 'Test Post from '.htmlentities($blogTitle);  $link = home_url(); $options['bgMsgFormat'] = 'Test Post from '.$blogTitle. " ".$link; }
    else { $post = get_post($postID); if(!$post) return;  $options['bgMsgFormat'] = nsFormatMessage($options['bgMsgFormat'], $postID, $addParams); 
       $options['bgMsgTFormat'] = nsFormatMessage($options['bgMsgTFormat'], $postID, $addParams); nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); 
    }
    $extInfo = ' | PostID: '.$postID." - ".(isset($post) && is_object($post)?$post->post_title:'');
    //## Actual POST Code
    if ($options['bgInclTags']=='1'){$t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = implode('","',$tggs);  $tags = nsTrnc($tags, 195, ',', ''); }
    if (substr($tags, -1)=='"') $tags = substr($tags, 0, -1); if (substr($tags, -1)==',') $tags = substr($tags, 0, -1); if (substr($tags, -1)=='"') $tags = substr($tags, 0, -1);   
    //## Set Message
    $message = array('title'=>'', 'announce'=>'', 'text'=>'', 'url'=>'', 'surl'=>'', 'urlDescr'=>'', 'urlTitle'=>'', 'imageURL' => array(), 'videoCode'=>'', 'videoURL'=>'', 'siteName'=>$blogTitle, 'tags'=>$tags, 'cats'=>'', 'authorName'=>'');    
    //## Actual Post
    $ntToPost = new nxs_class_SNAP_BG(); $ret = $ntToPost->doPostToNT($options, $message);
    //## Process Results
    if (!is_array($ret) || $ret['isPosted']!='1') { //## Error 
       if ($postID=='0') prr($ret); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo); 
    } else {  // ## All Good - log it.
      if ($postID=='0')  { nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); echo _e('OK - Message Posted, please see your '.$logNT.' Page. ', 'nxs_snap'); } 
        else  { nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPosted'=>'1', 'pgID'=>$ret['postID'], 'pDate'=>date('Y-m-d H:i:s'))); 
        $extInfo .= ' | <a href="'.$ret['postURL'].'" target="_blank">Post Link</a>'; nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo); }
    }
    //## Return Result
    if ($ret['isPosted']=='1') return 200; else return print_r($ret, true); 
  }
}

?>