<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'GP', 'lcode'=>'gp', 'name'=>'Google+');

if (!class_exists("nxs_snapClassGP")) { class nxs_snapClassGP {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){  global $nxs_plurl; $ntInfo = array('code'=>'GP', 'lcode'=>'gp', 'name'=>'Google+', 'defNName'=>'gpUName', 'tstReq' => false); 
    $fMsg = 'Google+ doesn\'t have a built-in API for automated posts yet. The current <a href="http://developers.google.com/+/api/">Google+ API</a> is "Read Only" and can\'t be used for posting.  <br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/google-plus-automated-posting">library module</a> to be able to publish your content to Google+.';
    $ntParams = array('ntInfo'=>$ntInfo, 'nxs_plurl'=>$nxs_plurl, 'ntOpts'=>$ntOpts, 'chkField'=>'', 'checkFunc' => array('funcName'=>'doPostToGooglePlus', 'msg'=>$fMsg)); nxs_showListRow($ntParams);
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($mgpo){ $options = array('nName'=>'', 'doGP'=>'1', 'gpUName'=>'', 'gpPageID'=>'', 'gpCommID'=>'', 'postType'=>'A', 'gpPass'=>''); $options['ntInfo']= array('lcode'=>'gp'); $this->showNTSettings($mgpo, $options, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; $nt = $options['ntInfo']['lcode']; $ntU = strtoupper($nt);
    if (!isset($options['nHrs'])) $options['nHrs'] = 0; if (!isset($options['nMin'])) $options['nMin'] = 0;  if (!isset($options['catSel'])) $options['catSel'] = 0;  if (!isset($options['catSelEd'])) $options['catSelEd'] = ''; 
    if (!isset($options['nDays'])) $options['nDays'] = 0; if (!isset($options['qTLng'])) $options['qTLng'] = ''; if (!isset($options['gpCCatsList'])) $options['gpCCatsList'] = '';  ?>
            <div id="doGP<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>" >     <input type="hidden" name="apDoSGP<?php echo $ii; ?>" value="0" id="apDoSGP<?php echo $ii; ?>" />             
            <?php if(!function_exists('doPostToGooglePlus') || (defined('d1') && d1==1)) {?><span style="color:#580000; font-size: 16px;"><br/><br/>
            <b><?php _e('Google+ API Library not found', 'nxs_snap'); ?></b>
             <br/><br/> <?php _e('Google+ doesn\'t have a built-in API for automated posts yet.', 'nxs_snap'); ?> <br/><?php _e('The current <a target="_blank" href="http://developers.google.com/+/api/">Google+ API</a> is "Read Only" and can\'t be used for posting.  <br/><br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/google-plus-automated-posting"><b>API Library Module</b></a> to be able to publish your content to Google+.', 'nxs_snap'); ?></span></div>
            <?php return; }; ?>            
            <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/gp16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-google-plus-social-networks-auto-poster-wordpress/"><?php $nType="Google+"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
            
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easily identify it', 'nxs_snap'); ?></i> </div><input name="gp[<?php echo $ii; ?>][nName]" id="gpnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('gp', $ii, $options['qTLng']); ?>
    <br/>
    <ul class="nsx_tabs">
    <li><a href="#nsx<?php echo $nt.$ii ?>_tab1"><?php _e('Account Info', 'nxs_snap'); ?></a></li>    
    <?php if (!$isNew) { ?>  <li><a href="#nsx<?php echo $nt.$ii ?>_tab2"><?php _e('Advanced', 'nxs_snap'); ?></a></li>  <?php } ?>
    </ul>
    <div class="nsx_tab_container"><?php /* ######################## Account Tab ####################### */ ?>
    <div id="nsx<?php echo $nt.$ii ?>_tab1" class="nsx_tab_content" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $nt; ?>-bg.png); background-repeat: no-repeat;  background-position:90% 10%;">
    
    
            
            <div style="width:100%;"><strong>Google+ Login Email:</strong> </div><input name="gp[<?php echo $ii; ?>][apGPUName]" id="apGPUName<?php echo $ii; ?>" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['gpUName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
            <div style="width:100%;"><strong>Google+ Password:</strong> </div><input name="gp[<?php echo $ii; ?>][apGPPass]" id="apGPPass<?php echo $ii; ?>" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($options['gpPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['gpPass'], 5)):$options['gpPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/>                
            <p><div style="width:100%;"><strong>Google+ Page ID (Optional - for Google+ Pages Only. <b style="color: #580000;"> <?php _e('Leave Empty to publish to your profile or community', 'nxs_snap'); ?></b>):</strong> 
            <p style="font-size: 11px; margin: 0px;">For example if URL of your page is https://plus.google.com/u/0/b/117008619877691455570/ your Page ID is: 117008619877691455570. <b><?php _e('Leave Empty to publish to your profile or community.', 'nxs_snap'); ?></b></p>
            </div><input name="gp[<?php echo $ii; ?>][apGPPage]" id="apGPPage" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['gpPageID'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /> 
            <br/>
            <p><div style="width:100%;"><strong>Google+ Community ID (Optional - for Google+ Communities Only. <b style="color: #580000;"> <?php _e('Leave Empty to publish to your profile or page', 'nxs_snap'); ?></b>):</strong> 
            <p style="font-size: 11px; margin: 0px;">For example if URL of your Community is https://plus.google.com/communities/100396001601096060160 your Page ID is: 100396001601096060160. <b><?php _e('Leave Empty to publish to your profile or page', 'nxs_snap'); ?>.</b></p>
            </div><input name="gp[<?php echo $ii; ?>][gpCommID]" id="gpCommID<?php echo $ii; ?>" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['gpCommID'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />&nbsp;&nbsp;
            <a href="#" onclick="getGPCats(jQuery('<?php if ($isNew) echo "#nsx_addNT "; ?>#apGPUName<?php echo $ii; ?>').val(), jQuery('<?php if ($isNew) echo "#nsx_addNT "; ?>#apGPPass<?php echo $ii; ?>').val(), '<?php echo $ii; ?>', jQuery('<?php if ($isNew) echo "#nsx_addNT "; ?>#gpCommID<?php echo $ii; ?>').val()); return false;">
            <?php _e('Retrieve Categories and Page Names', 'nxs_snap'); ?>
            </a>
          <div style="padding-left: 15px; width:100%;"><strong>Community Category:</strong>
            <img id="gpLoadingImg<?php echo $ii; ?>" style="display: none;" src='<?php echo $nxs_plurl; ?>img/ajax-loader-sm.gif' />
            <select name="gp[<?php echo $ii; ?>][apGPCCats]" id="apGPCCats<?php echo $ii; ?>">
            <?php if ($options['gpCCatsList']!=''){ $gGPCats = $options['gpCCatsList']; if ( base64_encode(base64_decode($gGPCats)) === $gGPCats) $gGPCats = base64_decode($gGPCats); 
              if ($options['gpCCat']!='') $gGPCats = str_replace($options['gpCCat'].'"', $options['gpCCat'].'" selected="selected"', $gGPCats);  echo $gGPCats;} else { ?>
              <option value="0">None(Click above to retrieve your categories)</option>
            <?php } ?>
            </select>
          </div>
            
            <br/><br/>
            
            <div id="altFormat" style="">
              <div style="width:100%;"><strong id="altFormatText"><?php _e('Message text Format', 'nxs_snap'); ?>:</strong> (<a href="#" id="apGPMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apGPMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)
              </div>
              
              <textarea cols="150" rows="3" id="gp<?php echo $ii; ?>SNAPformat" name="gp[<?php echo $ii; ?>][apGPMsgFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#gp<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apGPMsgFrmt<?php echo $ii; ?>');"><?php if ($isNew) _e("New post (%TITLE%) has been published on %SITENAME%", 'nxs_snap'); else _e(apply_filters('format_to_edit', htmlentities($options['gpMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?></textarea>
              
              <?php nxs_doShowHint("apGPMsgFrmt".$ii); ?>
            </div><br/>          
            
             <div style="width:100%;"><strong id="altFormatText">Post Type:</strong>&lt;-- (<a id="showShAtt" onmouseout="hidePopShAtt('<?php echo $ii; ?>XG');" onmouseover="showPopShAtt('<?php echo $ii; ?>XG', event);" onclick="return false;" class="underdash" href="http://www.nextscripts.com/blog/"><?php _e('What\'s the difference?', 'nxs_snap'); ?></a>)  </div>                      
<div style="margin-left: 10px;">
        <?php if(!isset($options['postType']) || $options['postType']=='') {
            if ((int)$options['imgPost'] == 1) $options['postType'] = 'I';
            if ((int)$options['gpAttch'] == 1 || $isNew) $options['postType'] = 'A';
        } ?>
        <input type="radio" name="gp[<?php echo $ii; ?>][postType]" value="T" <?php if ($options['postType'] == 'T') echo 'checked="checked"'; ?> /> <?php _e('Text Post', 'nxs_snap'); ?> - <i><?php _e('just text message', 'nxs_snap'); ?></i><br/>                    
        <input type="radio" name="gp[<?php echo $ii; ?>][postType]" value="I" <?php if ($options['postType'] == 'I') echo 'checked="checked"'; ?> /> <?php _e('Google+ Image Post', 'nxs_snap'); ?> - <i><?php _e('big image with text message', 'nxs_snap'); ?></i><br/>
        <input type="radio" name="gp[<?php echo $ii; ?>][postType]" value="A" <?php if ( !isset($options['postType']) || $options['postType'] == '' || $options['postType'] == 'A') echo 'checked="checked"'; ?> /> <?php _e('Add blogpost to Google+ message as an attachment', 'nxs_snap'); ?><br/>
<div class="popShAtt" id="popShAtt<?php echo $ii; ?>XG"><h3><?php _e('Google+ Post Types', 'nxs_snap'); ?></h3><img src="<?php echo $nxs_plurl; ?>img/gpPostTypesDiff6.png" width="600" height="285" alt="<?php _e('Google+ Post Types', 'nxs_snap'); ?>"/></div>
   </div><br/>
            <?php if ($isNew) { ?> <input type="hidden" name="gp[<?php echo $ii; ?>][apDoGP]" value="1" id="apDoNewGP<?php echo $ii; ?>" /> <?php } ?>
            <?php if ($options['gpPass']!='') { ?>
            
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('GP', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>              <?php } 
            ?>
            
             </div>
      <?php /* ######################## Advanced Tab ####################### */ ?>
  <?php if (!$isNew) { ?>  <div id="nsx<?php echo $nt.$ii ?>_tab2" class="nsx_tab_content">
    
    <?php nxs_showCatTagsCTFilters($nt, $ii, $options); 
      nxs_addPostingDelaySelV3($nt, $ii, $options['nHrs'], $options['nMin'], $options['nDays']); 
      nxs_showRepostSettings($nt, $ii, $options); ?>
            
            
    </div>  <?php } ?> <?php /* #### End of Tab #### */ ?>
    </div><br/> <?php /* #### End of Tabs #### */ ?>
    
    <div class="submitX nxclear" style="padding-bottom: 0px;"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div> </div>
            
            <?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){  $code = 'GP'; $lcode = 'gp'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apGPUName']) && $pval['apGPUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apGPUName']))   $options[$ii]['gpUName'] = trim($pval['apGPUName']);
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apGPPass']))    $options[$ii]['gpPass'] = 'n5g9a'.nsx_doEncode($pval['apGPPass']); else $options[$ii]['gpPass'] = '';  
        if (isset($pval['apGPPage']))    $options[$ii]['gpPageID'] = trim($pval['apGPPage']);  
        if (isset($pval['gpCommID']))    $options[$ii]['gpCommID'] = trim($pval['gpCommID']);  
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']); else $options[$ii]['catSel'] = 0;
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';        
        
        if (isset($pval['apGPCCats']))   $options[$ii]['gpCCat'] = trim($pval['apGPCCats']);        
        
        $options[$ii] = nxs_adjRpst($options[$ii], $pval);       
                      
        if (isset($pval['postType']))   $options[$ii]['postType'] = $pval['postType'];         
        if (isset($pval['apGPMsgFrmt'])) $options[$ii]['gpMsgFormat'] = trim($pval['apGPMsgFrmt']);                                                  
        if (isset($pval['apDoGP']))      $options[$ii]['doGP'] = $pval['apDoGP']; else $options[$ii]['doGP'] = 0; 
        if (isset($pval['delayDays'])) $options[$ii]['nDays'] = trim($pval['delayDays']); if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      } elseif ( count($pval)==1 ) if (isset($pval['apDo'.$code])) $options[$ii]['do'.$code] = $pval['apDo'.$code]; else $options[$ii]['do'.$code] = 0; 
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; $nt = 'gp'; $ntU = 'GP'; 
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapGP', true));  if (is_array($pMeta) && isset($pMeta[$ii]) && is_array($pMeta[$ii])) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]);  
        if (empty($ntOpt['imgToUse'])) $ntOpt['imgToUse'] = '';  if (empty($ntOpt['urlToUse'])) $ntOpt['urlToUse'] = '';
        $doGP = $ntOpt['doGP'] && (is_array($pMeta) || $ntOpt['catSel']!='1');    $imgToUse = $ntOpt['imgToUse'];   $urlToUse = $ntOpt['urlToUse']; 
        $isAvailGP =  $ntOpt['gpUName']!='' && $ntOpt['gpPass']!='';   $gpMsgFormat = htmlentities($ntOpt['gpMsgFormat'], ENT_COMPAT, "UTF-8");      
        if(!isset($ntOpt['postType']) || $ntOpt['postType']=='') {
            if ((int)$ntOpt['imgPost'] == 1) $ntOpt['postType'] = 'I';
            if ((int)$ntOpt['gpAttch'] == 1 || $isNew) $ntOpt['postType'] = 'A';
        } $gpPostType = $ntOpt['postType'];
      ?>  
      <tr><th style="text-align:left;" colspan="2">
      <?php if ($ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='')  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if (!empty($ntOpt['tagsSelX'])) { ?>  <input type="hidden" class="nxs_TG" id="nxs_TG_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['tagsSelX']; ?>" /> <?php } ?>
      <?php if ($isAvailGP) { ?><input class="nxsGrpDoChb" value="1" id="doGP<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="gp[<?php echo $ii; ?>][doGP]" <?php if ((int)$doGP == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="gp[<?php echo $ii; ?>][doGP]" value="<?php echo $doGP;?>"> <?php } ?> <?php } ?>
      
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/gp16.png);">Google+ - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailGP) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToGP_repostButton" id="rePostToGP_button" value="<?php _e('Repost to Google+', 'nxs_snap') ?>" />
                    <?php } ?>
                    
                    <?php  if (is_array($pMeta) && isset($pMeta[$ii]) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) { 
                        
                        ?> <span id="pstdGP<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
                      <a style="font-size: 10px;" href="https://plus.google.com/<?php echo $pMeta[$ii]['pgID']; ?>" target="_blank"><?php $nType="Google+"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>                
                
                <?php if (!$isAvailGP) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Google+ Account to AutoPost to Google+</b>
                <?php } else { if ($post->post_status != "publish" && function_exists('nxs_doSMAS5') ) { $ntOpt['postTime'] = get_post_time('U', false, $post_id); nxs_doSMAS5($nt, $ii, $ntOpt); } 
                
                if (((int)$doGP == 1) && $post->post_status == "publish" && isset($ntOpt['timeToRun']) && $ntOpt['timeToRun'] > time()) { ?> <tr><th style="text-align:left; color: purple;" colspan="2">
                ===&gt;&gt;&gt;&gt;&nbsp;<?php _e('Autopost has been schedulled for', 'nxs_snap') ?> <?php echo date('F j, Y, g:i a', $ntOpt['timeToRun']) ?></th> <?php } ?>
                
                <?php if ($ntOpt['rpstOn']=='1') { ?> 
                
                <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow">
                <input value="0"  type="hidden" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"/><input value="nxsi<?php echo $ii; ?>gp" type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"  <?php if (!empty($ntOpt['rpstPostIncl'])) echo "checked"; ?> />
                </th>
                <td> <?php _e('Include in "Auto-Reposting" to this network.', 'nxs_snap') ?>                
                </td></tr> <?php } ?>
                
                <tr><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 0px; padding-right:10px;"> <?php _e('Post Type:', 'nxs_snap') ?> <br/>
                (<a id="showShAtt" style="font-weight: normal" onmouseout="hidePopShAtt('<?php echo $ii; ?>XG');" onmouseover="showPopShAtt('<?php echo $ii; ?>XG', event);" onclick="return false;" class="underdash" href="http://www.nextscripts.com/blog/"><?php _e('What\'s the difference?', 'nxs_snap'); ?></a>)
                </th><td>     
        
        <input type="radio" name="gp[<?php echo $ii; ?>][postType]" value="T" <?php if ($gpPostType == 'T') echo 'checked="checked"'; ?> /> <?php _e('Text Post', 'nxs_snap') ?>  - <i><?php _e('just text message', 'nxs_snap') ?></i><br/>       
        <input type="radio" name="gp[<?php echo $ii; ?>][postType]" value="I" <?php if ($gpPostType == 'I') echo 'checked="checked"'; ?> /> <?php _e('Post to Google+ as "Image post"', 'nxs_snap') ?> - <i><?php _e('big image with text message', 'nxs_snap') ?></i><br/>             
        <input type="radio" name="gp[<?php echo $ii; ?>][postType]" value="A" <?php if ( !isset($gpPostType) || $gpPostType == '' || $gpPostType == 'A') echo 'checked="checked"'; ?> /><?php _e('Text Post with "attached" blogpost', 'nxs_snap') ?>
        <div class="popShAtt" id="popShAtt<?php echo $ii; ?>XG"><h3><?php _e('Google+ Post Types', 'nxs_snap') ?></h3><img src="<?php echo $nxs_plurl; ?>img/gpPostTypesDiff6.png" width="600" height="285" alt="<?php _e('Google+ Post Types', 'nxs_snap') ?>"/></div>
     </td></tr>
         <?php if ($ntOpt['gpCommID']!='') { ?>
     <tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;">Community Category</th>
                <td><select name="gp[<?php echo $ii; ?>][apGPCCat]" id="apGPCCat">
            <?php if (!empty($ntOpt['gpCCatsList'])){ $gCats = $ntOpt['gpCCatsList']; if ( base64_encode(base64_decode($gCats)) === $gCats) $gCats = base64_decode($gCats); 
              if ($ntOpt['gpCCat']!='') $gCats = str_replace($ntOpt['gpCCat'].'"', $ntOpt['gpCCat'].'" selected="selected"', $gCats);  echo $gCats;} else { ?>
              <option value="0">None(Please go to settings and retreive)</option>
            <?php } ?>
            </select></td>
                </tr> 
                       <?php } ?>                
               <?php /* ## Message Format ## */ ?>         
                <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top;  padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Message Format:', 'nxs_snap') ?></th>
                <td>
                
                 <?php if (1==1) { ?>
                <textarea cols="150" rows="1" id="gp<?php echo $ii; ?>SNAPformat" name="gp[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#gp<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apGPMsgFrmt<?php echo $ii; ?>');"><?php echo $gpMsgFormat ?></textarea>
                <?php } else { ?>
                <input value="<?php echo $gpMsgFormat ?>" type="text" name="gp[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apGPMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apGPMsgFrmt".$ii); ?>
                <?php } ?>
                </td></tr>
                <?php /* ## Select Image & URL ## */ nxs_showImgToUseDlg($nt, $ii, $imgToUse); nxs_showURLToUseDlg($nt, $ii, $urlToUse); ?>
           <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = ''; 
    if (isset($pMeta['SNAPformat'])) $optMt['gpMsgFormat'] = $pMeta['SNAPformat'];    
    if (isset($pMeta['imgToUse'])) $optMt['imgToUse'] = $pMeta['imgToUse']; if (isset($pMeta['urlToUse'])) $optMt['urlToUse'] = $pMeta['urlToUse']; 
    if (isset($pMeta['timeToRun']))  $optMt['timeToRun'] = $pMeta['timeToRun'];  if (isset($pMeta['rpstPostIncl']))  $optMt['rpstPostIncl'] = $pMeta['rpstPostIncl'];    
    if (isset($pMeta['postType'])) $optMt['postType'] = $pMeta['postType'];
    if (isset($pMeta['apGPCCat']) && $pMeta['apGPCCat']!='' && $pMeta['apGPCCat']!='0') $optMt['gpCCat'] = $pMeta['apGPCCat']; 
    if (isset($pMeta['doGP'])) $optMt['doGP'] = $pMeta['doGP'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doGP'] = 0; } 
    if (isset($pMeta['SNAPincludeGP']) && $pMeta['SNAPincludeGP'] == '1' ) $optMt['doGP'] = 1;  
    return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToGP_ajax")) {
  function nxs_rePostToGP_ajax() { check_ajax_referer('nxsSsPageWPN');  $postID = $_POST['id']; global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
    foreach ($options['gp'] as $ii=>$two) if ($ii==$_POST['nid']) {   $two['ii'] = $ii; $two['pType'] = 'aj'; //if ($two['gpPageID'].$two['gpUName']==$_POST['nid']) {  
      $gppo =  get_post_meta($postID, 'snapGP', true); $gppo =  maybe_unserialize($gppo);// prr($gppo);
      if (is_array($gppo) && isset($gppo[$ii]) && is_array($gppo[$ii])){ $ntClInst = new nxs_snapClassGP(); $two = $ntClInst->adjMetaOpt($two, $gppo[$ii]); } 
      $result = nxs_doPublishToGP($postID, $two); if ($result == 200) die("Successfully sent your post to Google+."); else die($result);        
    }    
  }
}  
if (!function_exists("nxs_doPublishToGP")) { //## Second Function to Post to G+
  function nxs_doPublishToGP($postID, $options){ $ntCd = 'GP'; $ntCdL = 'gp'; $ntNm = 'Google+';   global $plgn_NS_SNAutoPoster; $ytCode = ''; $imgURL = '';
      if (!is_array($options)) $options = maybe_unserialize(get_post_meta($postID, $options, true));
      if (!function_exists('doPostToGooglePlus') || (defined('d1') && d1==1)) { nxs_addToLogN('E', 'Error', $ntCd, '-=ERROR=- No G+ API Lib Detected', ''); return "No G+ API Lib Detected";}
      $addParams = nxs_makeURLParams(array('NTNAME'=>$ntNm, 'NTCODE'=>$ntCd, 'POSTID'=>$postID, 'ACCNAME'=>$options['nName']));
      if (empty($options['imgToUse'])) $options['imgToUse'] = ''; if (empty($options['imgSize'])) $options['imgSize'] = '';
      
      $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
      $logNT = '<span style="color:#800000">Google+</span> - '.$options['nName'];      
      $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
      if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') {  
           nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$uqID); return;
        }
      }         
      
      $message = array('message'=>'', 'link'=>'', 'imageURL'=>'', 'videoURL'=>'', 'noImg'=>false);  $isNoImg = false;     
      if ($postID=='0') { echo "Testing ... <br/><br/>"; $options['gpMsgFormat'] = "Test Post from ". htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES)." - ".home_url();  $message['url'] = home_url();    
      } else { nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1'));  $post = get_post($postID); if(!$post) return; 
        $gpMsgFormat = $options['gpMsgFormat']; $gpPostType = $options['postType'];  $msg = nsFormatMessage($gpMsgFormat, $postID, $addParams); $options['gpMsgFormat'] = $msg; 
        $extInfo = ' | PostID: '.$postID." - ".(isset($post) && is_object($post)?$post->post_title:'');
        if ($gpPostType=='I') { $vids = nsFindVidsInPost($post, false); if (count($vids)>0) $ytCode = $vids[0]; /* if (trim($ytCode)=='') $options['trPostType']='T'; /* What is it? */ }       
        if ($gpPostType=='A') if (trim($options['imgToUse'])!='') $imgURL = $options['imgToUse']; else $imgURL = nxs_getPostImage($postID, 'medium');  
        if ($gpPostType=='I') if (trim($options['imgToUse'])!='') $imgURL = $options['imgToUse']; else $imgURL = nxs_getPostImage($postID, 'full');      
        if (preg_match("/noImg.\.png/i", $imgURL)) { $imgURL = ''; $isNoImg = true; }
        
        //## MyURL - URLToGo code
        if (!isset($options['urlToUse']) || trim($options['urlToUse'])=='') $myurl = trim(get_post_meta($postID, 'snap_MYURL', true)); if (!empty($myurl)) $options['urlToUse'] = $myurl;
        if (isset($options['urlToUse']) && trim($options['urlToUse'])!='') { $urlToGo = $options['urlToUse']; $options['useFBGURLInfo'] = true; } else $urlToGo = get_permalink($postID);    
        if($addParams!='') $urlToGo .= (strpos($urlToGo,'?')!==false?'&':'?').$addParams; 
        
        $message = array('url'=>$urlToGo, 'imageURL'=>$imgURL, 'videoCode'=>$ytCode, 'noImg'=>$isNoImg); //prr($message); die();
      }            
      //## Actual Post
      $ntToPost = new nxs_class_SNAP_GP(); $ret = $ntToPost->doPostToNT($options, $message);
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