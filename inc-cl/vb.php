<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'VB', 'lcode'=>'vb', 'name'=>'vBulletin');

if (!class_exists("nxs_snapClassVB")) { class nxs_snapClassVB {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){  global $nxs_plurl; $ntInfo = array('code'=>'VB', 'lcode'=>'vb', 'name'=>'vBulletin', 'defNName'=>'vbUName', 'tstReq' => false); ?>    
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
  function showNewNTSettings($mgpo){ $options = array('nName'=>'', 'doVB'=>'1', 'vbUName'=>'', 'vbInclTags'=>'1', 'vbAttch'=>'', 'vbURL'=>'', 'vbPass'=>''); $options['ntInfo']= array('lcode'=>'vb'); $this->showNTSettings($mgpo, $options, true);}
  
  
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; $nt = $options['ntInfo']['lcode']; $ntU = strtoupper($nt); 
    if (!isset($options['nHrs'])) $options['nHrs'] = 0; if (!isset($options['nMin'])) $options['nMin'] = 0;  if (!isset($options['catSel'])) $options['catSel'] = 0;  if (!isset($options['catSelEd'])) $options['catSelEd'] = ''; 
    if (!isset($options['nDays'])) $options['nDays'] = 0; if (!isset($options['qTLng'])) $options['qTLng'] = '';  ?>
            <div id="doVB<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>">     <input type="hidden" name="apDoSVB<?php echo $ii; ?>" value="0" id="apDoSVB<?php echo $ii; ?>" />          
            
             <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/vb16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-vbulletin-social-networks-auto-poster-wordpress/"><?php $nType="vBulletin"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
            
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easily identify it', 'nxs_snap'); ?></i> </div><input name="vb[<?php echo $ii; ?>][nName]" id="vbnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('vb', $ii, $options['qTLng']); ?>
            
              <br/>
    <ul class="nsx_tabs">
    <li><a href="#nsx<?php echo $nt.$ii ?>_tab1"><?php _e('Account Info', 'nxs_snap'); ?></a></li>    
    <?php if (!$isNew) { ?>  <li><a href="#nsx<?php echo $nt.$ii ?>_tab2"><?php _e('Advanced', 'nxs_snap'); ?></a></li>  <?php } ?>
    </ul>
    <div class="nsx_tab_container"><?php /* ######################## Account Tab ####################### */ ?>
    <div id="nsx<?php echo $nt.$ii ?>_tab1" class="nsx_tab_content" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $nt; ?>-bg.png); background-repeat: no-repeat;  background-position:90% 10%;">
    
            
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
            
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('VB', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>      
               
            <?php } 
            
            ?></div>
            <?php /* ######################## Advanced Tab ####################### */ ?>
    <?php if (!$isNew) { ?>  <div id="nsx<?php echo $nt.$ii ?>_tab2" class="nsx_tab_content">
    
   <?php nxs_showCatTagsCTFilters($nt, $ii, $options); 
      nxs_addPostingDelaySelV3($nt, $ii, $options['nHrs'], $options['nMin'], $options['nDays']); 
      nxs_showRepostSettings($nt, $ii, $options); ?>
            
            
    </div>  <?php } ?> <?php /* #### End of Tab #### */ ?>
    </div><br/> <?php /* #### End of Tabs #### */ ?>
    
    <div class="submitX nxclear" style="padding-bottom: 0px;"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div></div><?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ $code = 'VB'; $lcode = 'vb'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apVBUName']) && $pval['apVBUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apVBUName']))   $options[$ii]['vbUName'] = trim($pval['apVBUName']);
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apVBPass']))    $options[$ii]['vbPass'] = 'n5g9a'.nsx_doEncode($pval['apVBPass']); else $options[$ii]['vbPass'] = '';  
        if (isset($pval['apVBURL'])) $options[$ii]['vbURL'] = trim($pval['apVBURL']);                                                  
        
        if (isset($pval['vbInclTags']))     $options[$ii]['vbInclTags'] = $pval['vbInclTags']; else $options[$ii]['vbInclTags'] = 0;
        if (isset($pval['apVBMsgTFrmt'])) $options[$ii]['vbMsgTFormat'] = trim($pval['apVBMsgTFrmt']);
        if (isset($pval['apVBMsgFrmt'])) $options[$ii]['vbMsgFormat'] = trim($pval['apVBMsgFrmt']);
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']); else $options[$ii]['catSel'] = 0;
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
        
        if (isset($pval['apDoVB']))      $options[$ii]['doVB'] = $pval['apDoVB']; else $options[$ii]['doVB'] = 0; 
        
        $options[$ii] = nxs_adjRpst($options[$ii], $pval);       
        
        if (isset($pval['delayDays'])) $options[$ii]['nDays'] = trim($pval['delayDays']);
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      } elseif ( count($pval)==1 ) if (isset($pval['apDo'.$code])) $options[$ii]['do'.$code] = $pval['apDo'.$code]; else $options[$ii]['do'.$code] = 0; 
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; $nt = 'vb'; $ntU = 'VB';
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapVB', true));   if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
        $doVB = $ntOpt['doVB'] && (is_array($pMeta) || $ntOpt['catSel']!='1');   
        $isAvailVB =  $ntOpt['vbUName']!='' && $ntOpt['vbPass']!=''; $vbMsgFormat = htmlentities($ntOpt['vbMsgFormat'], ENT_COMPAT, "UTF-8"); $vbMsgTFormat = htmlentities($ntOpt['vbMsgTFormat'], ENT_COMPAT, "UTF-8");      
      ?>  
      <tr><th style="text-align:left;" colspan="2">
      <?php if ($ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='')  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if (!empty($ntOpt['tagsSelX'])) { ?>  <input type="hidden" class="nxs_TG" id="nxs_TG_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['tagsSelX']; ?>" /> <?php } ?>
      <?php if ($isAvailVB) { ?><input class="nxsGrpDoChb" value="1" id="doVB<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="vb[<?php echo $ii; ?>][doVB]" <?php if ((int)$doVB == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="vb[<?php echo $ii; ?>][doVB]" value="<?php echo $doVB;?>"> <?php } ?> <?php } ?>
      
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/vb16.png);">vBulletin - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailVB) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToVB_repostButton" id="rePostToVB_button" value="<?php _e('Repost to vBulletin', 'nxs_snap') ?>" />
                    <?php } ?>
                    
                    <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) { $wpURL = str_ireplace('/xmlrpc.php', '', $ntOpt['vbURL']);
                        if (substr($wpURL, -1)=='/') $wpURL = substr($wpURL, 0, -1);  $wpURL = $wpURL."/";
                        ?> <span id="pstdVB<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
          <a style="font-size: 10px;" href="<?php echo $pMeta[$ii]['pgID']; ?>" target="_blank"><?php $nType="vBulletin"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>                
                
                <?php if (!$isAvailVB) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your vBulletin Account to AutoPost to vBulletin</b>
                <?php } else { if ($post->post_status != "publish" && function_exists('nxs_doSMAS5') ) { $ntOpt['postTime'] = get_post_time('U', false, $post_id); nxs_doSMAS5($nt, $ii, $ntOpt); } ?>
                
                <?php if ($ntOpt['rpstOn']=='1') { ?> 
                
                <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow">
                <input value="0"  type="hidden" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"/><input value="nxsi<?php echo $ii; ?>vb" type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"  <?php if (!empty($ntOpt['rpstPostIncl'])) echo "checked"; ?> />
                </th>
                <td> <?php _e('Include in "Auto-Reposting" to this network.', 'nxs_snap') ?>                
                </td></tr> <?php } ?>
               
       <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow"><?php _e('Text Format:', 'nxs_snap') ?></th>
        <td><input value="<?php echo $vbMsgTFormat ?>" type="text" name="vb[<?php echo $ii; ?>][SNAPformatT]"  style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apVBMsgTFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apVBMsgTFrmt".$ii); ?></td></tr>
                
      <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow"><?php _e('Text Format:', 'nxs_snap') ?></th>
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
     if (isset($pMeta['imgToUse'])) $optMt['imgToUse'] = $pMeta['imgToUse'];      
     if (isset($pMeta['timeToRun']))  $optMt['timeToRun'] = $pMeta['timeToRun'];  if (isset($pMeta['rpstPostIncl']))  $optMt['rpstPostIncl'] = $pMeta['rpstPostIncl'];    
     if (isset($pMeta['doVB'])) $optMt['doVB'] = $pMeta['doVB'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doVB'] = 0; } 
     if (isset($pMeta['SNAPincludeVB']) && $pMeta['SNAPincludeVB'] == '1' ) $optMt['doVB'] = 1;  
     return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToVB_ajax")) {
  function nxs_rePostToVB_ajax() { check_ajax_referer('nxsSsPageWPN');  $postID = $_POST['id']; $options = get_option('NS_SNAutoPoster');  
    foreach ($options['vb'] as $ii=>$two) if ($ii==$_POST['nid']) {   $two['ii'] = $ii; $two['pType'] = 'aj'; //if ($two['gpPageID'].$two['gpUName']==$_POST['nid']) {  
      $gppo =  get_post_meta($postID, 'snapVB', true); $gppo =  maybe_unserialize($gppo);// prr($gppo);
      if (is_array($gppo) && isset($gppo[$ii]) && is_array($gppo[$ii])){ $ntClInst = new nxs_snapClassVB(); $two = $ntClInst->adjMetaOpt($two, $gppo[$ii]); }
      $result = nxs_doPublishToVB($postID, $two); if ($result == 200) die("Successfully sent your post to vBulletin."); else die($result);        
    }    
  }
}  

if (!function_exists("nxs_doPublishToVB")) { //## Second Function to Post to VB
  function nxs_doPublishToVB($postID, $options){ global $nxs_vbCkArray, $plgn_NS_SNAutoPoster; $ntCd = 'VB'; $ntCdL = 'vb'; $ntNm = 'vBulletin';
    if (!is_array($options)) $options = maybe_unserialize(get_post_meta($postID, $options, true));
    //if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToVB',  array($postID, $options));  
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); 
    $addParams = nxs_makeURLParams(array('NTNAME'=>$ntNm, 'NTCODE'=>$ntCd, 'POSTID'=>$postID, 'ACCNAME'=>$options['nName']));
    
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
    $logNT = '<span style="color:#000080">vBulletin</span> - '.$options['nName'];      
    $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') {  sleep(5);
         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$uqID); return;
        }
    } 
  
      
      if ($postID=='0') { echo "Testing ... <br/><br/>"; $urlToGo = home_url(); $options['vbMsgFormat'] = 'Test Message from '.$urlToGo;  $options['vbMsgTFormat'] = 'Test Link from '.$urlToGo; } 
        else { $post = get_post($postID); nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); 
          $options['vbMsgFormat'] = nsFormatMessage($options['vbMsgFormat'], $postID, $addParams); $options['vbMsgTFormat'] = nsFormatMessage($options['vbMsgTFormat'], $postID, $addParams);
          //## MyURL - URLToGo code
          if (!isset($options['urlToUse']) || trim($options['urlToUse'])=='') $myurl =  trim(get_post_meta($postID, 'snap_MYURL', true)); if ($myurl!='') $options['urlToUse'] = $myurl;
          if (isset($options['urlToUse']) && trim($options['urlToUse'])!='') { $urlToGo = $options['urlToUse']; $options['useFBGURLInfo'] = true; } else $urlToGo = get_permalink($postID);      
          $gOptions = $plgn_NS_SNAutoPoster->nxs_options; $addURLParams = trim($gOptions['addURLParams']);  if($addURLParams!='') $urlToGo .= (strpos($urlToGo,'?')!==false?'&':'?').$addURLParams;
      }
      $dusername = $options['vbUName']; //$link = urlencode($link); $desc = urlencode(substr($msg, 0, 500));      
      $extInfo = ' | PostID: '.$postID." - ".(!empty($post) && is_object($post)?$post->post_title:'');
      
      //## Message & Format                 
      if ($options['vbInclTags']=='1') { $t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = (implode(', ',$tggs)); /* $tags = str_replace(' ','+',$tags); */ } else $tags = '';
      $message = array('siteName'=>$blogTitle, 'tags'=>$tags);    
      //## Actual Post
      $ntToPost = new nxs_class_SNAP_VB(); $ret = $ntToPost->doPostToNT($options, $message);
      //## Save Session
      if (empty($options['vkSvC']) || serialize($nxs_vbCkArray)!=$options['vbSvC']) { global $plgn_NS_SNAutoPoster;  $gOptions = $plgn_NS_SNAutoPoster->nxs_options;
        if (isset($options['ii']) && $options['ii']!=='')  { $gOptions['vb'][$options['ii']]['vbSvC'] = serialize($nxs_vbCkArray); update_option('NS_SNAutoPoster', $gOptions);  }
        else foreach ($gOptions['vb'] as $ii=>$gpn) { $result = array_diff($options, $gpn); 
          if (!is_array($result) || count($result)<1) { $gOptions['vb'][$ii]['vbSvC'] = serialize($nxs_vbCkArray); update_option('NS_SNAutoPoster', $gOptions); break; }
        }        
      }
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