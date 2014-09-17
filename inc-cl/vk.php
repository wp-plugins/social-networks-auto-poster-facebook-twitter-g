<?php    
//## NextScripts vKontakte(VK) Connection Class
$nxs_snapAvNts[] = array('code'=>'VK', 'lcode'=>'vk', 'name'=>'vKontakte(VK)');

if (!class_exists("nxs_snapClassVK")) { class nxs_snapClassVK {
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){  global $nxs_plurl; $ntInfo = array('code'=>'VK', 'lcode'=>'vk', 'name'=>'vKontakte(VK)', 'defNName'=>'', 'tstReq' => false); ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> 
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = str_ireplace('https://vk.com','', str_ireplace('http://vk.com','', $pbo['url'])); ?>
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
  function showNewNTSettings($mNTo){ $nto = array('nName'=>'', 'doVK'=>'1', 'url'=>'', 'vkAppID'=>'', 'imgUpl'=>'1', 'addBackLink'=>'1', 'vkPostType'=>'T', 'msgAFormat'=>'', 'attch'=>'1', 'vkPgID'=>'', 'vkAppAuthUser'=>'', 'msgFrmt'=>'New post has been published on %SITENAME%' ); $nto['ntInfo']= array('lcode'=>'vk'); $this->showNTSettings($mNTo, $nto, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; $nt = $options['ntInfo']['lcode']; $ntU = strtoupper($nt);
    if ((int)$options['attch']==0 && (!isset($options['trPostType']) || $options['trPostType']=='')) $options['trPostType'] = 'T';  if (!isset($options['uName '])) $options['uName '] = ''; if (!isset($options['uPass'])) $options['uPass'] = ''; 
    if (!isset($options['nHrs'])) $options['nHrs'] = 0; if (!isset($options['nMin'])) $options['nMin'] = 0;  if (!isset($options['catSel'])) $options['catSel'] = 0;  if (!isset($options['catSelEd'])) $options['catSelEd'] = ''; 
    if (!isset($options['nDays'])) $options['nDays'] = 0; if (!isset($options['qTLng'])) $options['qTLng'] = ''; if (!isset($options['uName'])) $options['uName'] = '';  if (!isset($options['postType'])) $options['postType'] = ''; ?>
    
    <div id="doVK<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>" style="background-image: url(<?php echo $nxs_plurl; ?>img/vk-bg.png);  background-position:90% 10%;">   <input type="hidden" name="apDoSVK<?php echo $ii; ?>" value="0" id="apDoSVK<?php echo $ii; ?>" />                                
    <?php if ($isNew) { ?>    <input type="hidden" name="vk[<?php echo $ii; ?>][apDoVK]" value="1" id="apDoNewVK<?php echo $ii; ?>" /> <?php } ?>
    
     <div class="nsx_iconedTitle" style="float: right; max-width: 322px; text-align: right; background-image: url(<?php echo $nxs_plurl; ?>img/vk16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-vkontakte-social-networks-auto-poster-wordpress/"><?php $nType="vKontakte"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a><br/>
     <span style="font-size: 10px;">Please use URL <em style="font-size: 10px; color:#CB4B16;">http://<?php echo $_SERVER["SERVER_NAME"] ?></em> and domain <em style="font-size: 10px; color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em> in your vKontakte(VK) App</span>
     
     </div>
    
    <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easily identify it', 'nxs_snap'); ?></i> </div><input name="vk[<?php echo $ii; ?>][nName]" id="vknName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
    <?php echo nxs_addQTranslSel('vk', $ii, $options['qTLng']); ?>
    
     <br/>
    <ul class="nsx_tabs">
    <li><a href="#nsx<?php echo $nt.$ii ?>_tab1"><?php _e('Account Info', 'nxs_snap'); ?></a></li>    
    <?php if (!$isNew) { ?>  <li><a href="#nsx<?php echo $nt.$ii ?>_tab2"><?php _e('Advanced', 'nxs_snap'); ?></a></li>  <?php } ?>
    </ul>
    <div class="nsx_tab_container"><?php /* ######################## Account Tab ####################### */ ?>
    <div id="nsx<?php echo $nt.$ii ?>_tab1" class="nsx_tab_content" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $nt; ?>-bg.png); background-repeat: no-repeat;  background-position:90% 10%;">
    
    
    
    <div style="width:100%;"><strong>vKontakte(VK) URL:</strong> </div>
    <p style="font-size: 11px; margin: 0px;"><?php _e('Could be your vKontakte(VK) Profile or vKontakte(VK) Group Page', 'nxs_snap'); ?></p>
    <input name="vk[<?php echo $ii; ?>][url]" id="apurl" style="width: 50%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['url'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
    
    <div style="width:100%; margin-top: 15px; margin-bottom: 5px;"><b style="font-size: 14px;" >VK API</b> <?php _e('(It could be used for "Text" and "Image" posts)', 'nxs_snap'); ?></div>
    
    <div style="width:100%; margin-left: 15px;">
    
    <div style="width:100%;"><strong>vKontakte(VK) Application ID:</strong> <a href="http://vk.com/editapp?act=create" target="_blank"><?php _e('[Create VK App]', 'nxs_snap'); ?></a> <a href="http://vk.com/apps?act=settings" target="_blank"><?php _e('[Manage VK Apps]', 'nxs_snap'); ?></a> </div> 
    <input name="vk[<?php echo $ii; ?>][apVKAppID]" id="apVKAppID" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['vkAppID'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  
    <br/>
    <?php  if($options['vkAppID']=='') { ?>
            <?php _e('<b>Authorize Your vKontakte(VK) Account</b>. Please click "Update Settings" to be able to Authorize your account.', 'nxs_snap'); ?>
            <?php } else { if(isset($options['vkAppAuthUser']) && $options['vkAppAuthUser']>0) { ?>
            <?php _e('Your vKontakte(VK) Account has been authorized.'); ?> User ID: <?php _e(apply_filters('format_to_edit', htmlentities($options['vkAppAuthUser'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>.
            <?php _e('You can', 'nxs_snap'); ?> Re- <?php } ?>      
            <a target="_blank" href="http://api.vkontakte.ru/oauth/authorize?client_id=<?php echo $options['vkAppID'];?>&scope=offline,wall,photos,pages&redirect_uri=http://api.vkontakte.ru/blank.html&display=page&response_type=token<?php '&auth=vk&acc='.$ii;?>">Authorize Your vKontakte(VK) Account</a>                  
            <?php if (!isset($options['vkAppAuthUser']) || $options['vkAppAuthUser']<1) { ?> <div class="blnkg">&lt;=== <?php _e('Authorize your account', 'nxs_snap'); ?> ===</div> <?php } ?>
            
            <div style="width:100%;"><strong>vKontakte(VK) Auth Response:</strong> </div><input name="vk[<?php echo $ii; ?>][apVKAuthResp]" style="width: 50%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['apVKAuthResp'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/><br/>
            
            <?php } ?>
            
    </div>      
    
    <div style="width:100%; margin-bottom: 5px;"><b style="font-size: 14px;" >NextScripts VK API</b> <?php _e('(It could be used for "Text with attached link" posts)', 'nxs_snap'); ?></div>
    
    <div style="width:100%; margin-left: 15px;">
      <?php if( function_exists("nxs_doPostToVK")) { ?>    
         <div style="width:100%;"><strong>vKontakte(VK) Email:</strong> </div><input name="vk[<?php echo $ii; ?>][uName]" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['uName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  
         <div style="width:100%;"><strong>vKontakte(VK) Password:</strong> </div><input name="vk[<?php echo $ii; ?>][uPass]" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($options['uPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['uPass'], 5)):$options['uPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />    
         <?php if( isset($options['vkPhReq'])) { if (empty($options['vkPh'])) $options['vkPh'] =''; ?>     
           <div style="width:100%;"><strong>vKontakte(VK) Phone Number (<?php _e(apply_filters('format_to_edit', htmlentities($options['vkPhReq'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>) :</strong> </div><input name="vk[<?php echo $ii; ?>][vkPh]" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['vkPh'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /> 
         <?php } ?>
      <?php } else { ?> **** <?php _e('Please upgrade the plugin to "PRO" get NextScripts VK API', 'nxs_snap'); ?> <?php } ?>
    </div>
    <br/>      
    <div id="altFormat">
      <div style="width:100%;"><strong id="altFormatText"><?php _e('Message text Format', 'nxs_snap'); ?>:</strong> (<a href="#" id="msgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('msgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)</div>        
                
         <textarea cols="150" rows="3" id="vkmsgFrmt<?php echo $ii; ?>" name="vk[<?php echo $ii; ?>][msgFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#vk<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('msgFrmt<?php echo $ii; ?>');"><?php _e(apply_filters('format_to_edit', htmlentities($options['msgFrmt'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?></textarea>
        
        <?php nxs_doShowHint("msgFrmt".$ii); ?><br/>
    </div>
    <div >
    <input value="1" type="checkbox" name="vk[<?php echo $ii; ?>][addBackLink]"  <?php if (isset($options['addBackLink']) && (int)$options['addBackLink'] == 1) echo "checked"; ?> /> <?php _e('Add backlink to the post', 'nxs_snap') ?>
    </div>
       <br/>
      <div style="width:100%;"><strong id="altFormatText">Post Type:</strong> &lt;-- (<a id="showShAtt" onmouseout="hidePopShAtt('<?php echo $ii; ?>VKX');" onmouseover="showPopShAtt('<?php echo $ii; ?>VKX', event);" onclick="return false;" class="underdash" href="http://www.nextscripts.com/blog/"><?php _e('What\'s the difference?', 'nxs_snap'); ?></a>) </div>                      
<div style="margin-left: 10px;">
        
        <input type="radio" name="vk[<?php echo $ii; ?>][postType]" value="T" <?php if ($options['postType'] == 'T') echo 'checked="checked"'; ?> /> <?php _e('Text Post', 'nxs_snap'); ?> - <i><?php _e('just text message', 'nxs_snap'); ?></i><br/>                    
        <input type="radio" name="vk[<?php echo $ii; ?>][postType]" value="I" <?php if ($options['postType'] == 'I') echo 'checked="checked"'; ?> /> <?php _e('Image Post', 'nxs_snap'); ?> - <i><?php _e('big image with text message', 'nxs_snap'); ?></i><br/>
        <input type="radio"  <?php if( !function_exists("nxs_doPostToVK")) { ?> disabled="disabled" <?php } ?> name="vk[<?php echo $ii; ?>][postType]" value="A" <?php if ( !isset($options['postType']) || $options['postType'] == '' || $options['postType'] == 'A') echo 'checked="checked"'; ?> /> <span <?php if( !function_exists("nxs_doPostToVK")) { ?>style="color:#C0C0C0;"<?php } ?> ><?php _e('Text Post with "attached" link', 'nxs_snap'); ?></span><br/>
   <?php if( function_exists("nxs_doPostToVK")) { ?>
<div style="width:100%; margin-left: 15px;"><strong><?php _e('Link attachment type:', 'nxs_snap'); ?>&nbsp;</strong> 
    <div style="margin-bottom: 5px; margin-left: 10px; "><input value="1"  id="apattchAsVid" type="checkbox" name="vk[<?php echo $ii; ?>][attchAsVid]"  <?php if (isset($options['attchAsVid']) && (int)$options['attchAsVid'] == 1) echo "checked"; ?> /> 
      <?php _e('<strong>If post has video use it as an attachment thumbnail.</strong> <i>Video will be used for an attachment thumbnail instead of featured image. Only Youtube is supported at this time.</i>', 'nxs_snap'); ?><br/>
     
    </div>
     <strong><?php _e('Attachment Text Format:', 'nxs_snap'); ?></strong><br/> 
      <input value="1"  id="apVKMsgAFrmtA<?php echo $ii; ?>" <?php if (trim($options['msgAFormat'])=='') echo "checked"; ?> onchange="if (jQuery(this).is(':checked')) { jQuery('#apVKMsgAFrmtDiv<?php echo $ii; ?>').hide(); jQuery('#apVKMsgAFrmt<?php echo $ii; ?>').val(''); }else jQuery('#apVKMsgAFrmtDiv<?php echo $ii; ?>').show();" type="checkbox" name="vk[<?php echo $ii; ?>][msgAFormat]"/> <strong><?php _e('Auto', 'nxs_snap'); ?></strong>
      <i> - <?php _e('Recommended. Info from SEO Plugins will be used, then post excerpt, then post text', 'nxs_snap'); ?> </i><br/>
      <div id="apVKMsgAFrmtDiv<?php echo $ii; ?>" style="<?php if ($options['msgAFormat']=='') echo "display:none;"; ?>" >&nbsp;&nbsp;&nbsp; <?php _e('Set your own format:', 'nxs_snap'); ?><input name="vk[<?php echo $ii; ?>][msgAFormat]" id="apVKMsgAFrmt<?php echo $ii; ?>" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['msgAFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/></div>
</div><br/>

<?php } ?>
   </div><br/>  
<div class="popShAtt" style="z-index: 9999" id="popShAtt<?php echo $ii; ?>VKX"><h3>vKontakte(VK) Post Types</h3><img src="<?php echo $nxs_plurl; ?>img/vkPostTypesDiff6.png" width="600" height="257" alt="vKontakte(VK) Post Types"/></div>

              
            <?php if ($options['vkPgID']!='') {?><div style="width:100%;"><strong>Your vKontakte(VK) Page ID:</strong> <?php _e(apply_filters('format_to_edit', htmlentities($options['vkPgID'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?> </div><?php } ?>
            
            <?php  if(isset($options['vkAppAuthUser']) && $options['vkAppAuthUser']>0) { ?>
            
            <br/><br/><b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('VK','<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>         
            <?php }?>
            
            </div>
      <?php /* ######################## Advanced Tab ####################### */ ?>
   <?php if (!$isNew) { ?>   <div id="nsx<?php echo $nt.$ii ?>_tab2" class="nsx_tab_content">
    
   <?php nxs_showCatTagsCTFilters($nt, $ii, $options); 
      nxs_addPostingDelaySelV3($nt, $ii, $options['nHrs'], $options['nMin'], $options['nDays']); 
      nxs_showRepostSettings($nt, $ii, $options); ?>
            
            
    </div>  <?php } ?> <?php /* #### End of Tab #### */ ?>
    </div><br/> <?php /* #### End of Tabs #### */ ?>
    
    <div class="submitX nxclear" style="padding-bottom: 0px;"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div>
            
          </div>        
        <?php
      
  } 
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ $code = 'VK'; $lcode = 'vk'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apVKAppID']) && $pval['apVKAppID']!='') { if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apDoVK']))         $options[$ii]['doVK'] = $pval['apDoVK']; else $options[$ii]['doVK'] = 0;
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apVKAppID']))      $options[$ii]['vkAppID'] = trim($pval['apVKAppID']);                
        
        if (isset($pval['uName']))      $options[$ii]['uName'] = trim($pval['uName']);                                
        if (isset($pval['uPass']))    $options[$ii]['uPass'] = 'n5g9a'.nsx_doEncode($pval['uPass']); else $options[$ii]['uPass'] = '';   
        if (isset($pval['vkPh']))      $options[$ii]['vkPh'] = trim($pval['vkPh']);               
        
        
        if (isset($pval['apVKAuthResp']))  {   $options[$ii]['apVKAuthResp'] = trim($pval['apVKAuthResp']); 
          $options[$ii]['vkAppAuthToken'] = trim( CutFromTo($pval['apVKAuthResp'].'&', 'access_token=','&')); 
          $options[$ii]['vkAppAuthUser'] = trim( CutFromTo($pval['apVKAuthResp']."&", 'user_id=','&')); 
          $hdrsArr = nxs_getVKHeaders($pval['url']);
          $response = wp_remote_get($pval['url'], array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr)); 
          if (is_wp_error($response)) { echo "ERROR: <br/>"; prr($response); return;} $contents = $response['body']; $contents = utf8_decode($contents);    
          if (stripos($contents, '"group_id":')!==false) { $options[$ii]['pgIntID'] =  '-'.CutFromTo($contents, '"group_id":', ','); $type='all'; }  
          if (stripos($contents, '"public_id":')!==false) { $options[$ii]['pgIntID'] =  '-'.CutFromTo($contents, '"public_id":', ','); $type='all'; }  
          if (stripos($contents, '"user_id":')!==false) {   $options[$ii]['pgIntID'] =  CutFromTo($contents, '"user_id":', ','); $type='own'; }  
        }
        
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']); else $options[$ii]['catSel'] = 0;
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
        
        if (isset($pval['postType']))     $options[$ii]['postType'] = trim($pval['postType']);
        if (isset($pval['attch']))      $options[$ii]['attch'] = $pval['attch']; else $options[$ii]['attch'] = 0;
        if (isset($pval['attchAsVid'])) $options[$ii]['attchAsVid'] = $pval['attchAsVid']; else $options[$ii]['attchAsVid'] = 0;
        
        if (isset($pval['apVKImgUpl']))     $options[$ii]['imgUpl'] = $pval['apVKImgUpl']; else $options[$ii]['imgUpl'] = 0;
        if (isset($pval['addBackLink']))     $options[$ii]['addBackLink'] = $pval['addBackLink']; else $options[$ii]['addBackLink'] = 0;
        
        if (isset($pval['msgFrmt']))    $options[$ii]['msgFrmt'] = trim($pval['msgFrmt']); 
        if (isset($pval['msgAFormat']))    $options[$ii]['msgAFormat'] = trim($pval['msgAFormat']); 
        
        $options[$ii] = nxs_adjRpst($options[$ii], $pval);       
        
        if (isset($pval['delayDays'])) $options[$ii]['nDays'] = trim($pval['delayDays']);
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
                
        if (isset($pval['url']))  {  $options[$ii]['url'] = trim($pval['url']);   if ( substr($options[$ii]['url'], 0, 4)!='http' )  $options[$ii]['url'] = 'http://'.$options[$ii]['url'];
          $vkPgID = $options[$ii]['url']; if (substr($vkPgID, -1)=='/') $vkPgID = substr($vkPgID, 0, -1);  $vkPgID = substr(strrchr($vkPgID, "/"), 1); 
          if (strpos($vkPgID, '?')!==false) $vkPgID = substr($vkPgID, 0, strpos($vkPgID, '?')); 
          $options[$ii]['vkPgID'] = $vkPgID; //echo $vkPgID;
          if (strpos($options[$ii]['url'], '?')!==false) $options[$ii]['url'] = substr($options[$ii]['url'], 0, strpos($options[$ii]['url'], '?'));// prr($pval); prr($options[$ii]); // die();
        }                  
      } elseif ( count($pval)==1 ) if (isset($pval['apDo'.$code])) $options[$ii]['do'.$code] = $pval['apDo'.$code]; else $options[$ii]['do'.$code] = 0; 
    } return $options;
  } 
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; $nt = 'vk'; $ntU = 'VK';
    foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapVK', true));  if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
        if (empty($ntOpt['imgToUse'])) $ntOpt['imgToUse'] = ''; if (empty($ntOpt['urlToUse'])) $ntOpt['urlToUse'] = '';
        $doVK = $ntOpt['doVK'] && (is_array($pMeta) || $ntOpt['catSel']!='1');  $imgToUse = $ntOpt['imgToUse'];  $urlToUse = $ntOpt['urlToUse']; 
        $isAvailVK =  $ntOpt['url']!='' && $ntOpt['vkAppID']!='' || $ntOpt['uPass']!=''; $isAttachVK = $ntOpt['attch']; $msgFrmt = htmlentities($ntOpt['msgFrmt'], ENT_COMPAT, "UTF-8"); $postType = $ntOpt['postType']; 
      ?>
      <tr><th style="text-align:left;" colspan="2"> 
      <?php if ($ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='')  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if (!empty($ntOpt['tagsSelX'])) { ?>  <input type="hidden" class="nxs_TG" id="nxs_TG_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['tagsSelX']; ?>" /> <?php } ?>      
        <?php if ($isAvailVK) { ?><input class="nxsGrpDoChb" value="1" id="doVK<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="vk[<?php echo $ii; ?>][doVK]" <?php if ((int)$doVK == 1) echo 'checked="checked" title="def"';  ?> /> 
        <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="vk[<?php echo $ii; ?>][doVK]" value="<?php echo $doVK;?>"> <?php } ?> <?php } ?>      
        <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/vk16.png);">vKontakte(VK) - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th>
        <td><?php //## Only show RePost button if the post is "published"
        if ($post->post_status == "publish" && $isAvailVK) { ?>
          <input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToVK_repostButton" id="rePostToVK_button" value="<?php _e('Repost to vKontakte(VK)', 'nxs_snap') ?>" />
        <?php  } ?>
        <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID'])) { ?> <span id="pstdVK<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
             <a style="font-size: 10px;" href="http://vk.com/wall<?php echo $pMeta[$ii]['pgID']; ?>" target="_blank"><?php $nType="vKontakte(VK)"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
           </span>
        <?php } ?>
        </td></tr>
          <?php if (!$isAvailVK) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup and Authorize your vKontakte(VK) Account to AutoPost to vKontakte(VK)</b>
          <?php } else { if ($post->post_status != "publish" && function_exists('nxs_doSMAS5') ) { $ntOpt['postTime'] = get_post_time('U', false, $post_id); nxs_doSMAS5($nt, $ii, $ntOpt); } ?>
                
                <?php if ($ntOpt['rpstOn']=='1') { ?> 
                
                <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow">
                <input value="0"  type="hidden" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"/><input value="nxsi<?php echo $ii; ?>vk" type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"  <?php if (!empty($ntOpt['rpstPostIncl'])) echo "checked"; ?> /> 
                </th>
                <td> <?php _e('Include in "Auto-Reposting" to this network.', 'nxs_snap') ?>
                </td></tr> <?php } ?>
        <tr id="altFormat1" style=""><th scope="row" valign="top" class="nxsTHRow"><?php _e('Message Format:', 'nxs_snap') ?></th>
          <td>          
          <textarea cols="150" rows="1" id="vk<?php echo $ii; ?>SNAPformat" name="vk[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#vk<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apVKTMsgFrmt<?php echo $ii; ?>');"><?php echo $msgFrmt; ?></textarea>
          
          <?php nxs_doShowHint("apVKTMsgFrmt".$ii); ?>
            <br/><div ><input value="0" type="hidden" name="vk[<?php echo $ii; ?>][addBackLink]" />
              <input value="1" type="checkbox" name="vk[<?php echo $ii; ?>][addBackLink]"  <?php if (isset($ntOpt['addBackLink']) && (int)$ntOpt['addBackLink'] == 1) echo "checked"; ?> /> <?php _e('Add backlink to the post', 'nxs_snap') ?>
            </div>
        </td></tr>
        <tr><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 0px; padding-right:10px;"> <?php _e('Post Type:', 'nxs_snap') ?> <br/>
          (<a id="showShAtt" style="font-weight: normal" onmouseout="hidePopShAtt('<?php echo $ii; ?>VKX');" onmouseover="showPopShAtt('<?php echo $ii; ?>VKX', event);" onclick="return false;" class="underdash" href="http://www.nextscripts.com/blog/"><?php _e('What\'s the difference?', 'nxs_snap'); ?></a>)</th><td>     
          <input type="radio" name="vk[<?php echo $ii; ?>][PostType]" value="T" <?php if ($postType == 'T') echo 'checked="checked"'; ?> /> <?php _e('Text Post', 'nxs_snap') ?> - <i><?php _e('just text message', 'nxs_snap') ?></i><br/>       
          <input type="radio" name="vk[<?php echo $ii; ?>][PostType]" value="I" <?php if ($postType == 'I') echo 'checked="checked"'; ?> /> <?php _e('Image Post', 'nxs_snap') ?> - <i><?php _e('big image with text message', 'nxs_snap') ?></i>       
          <?php if( function_exists("nxs_doPostToVK")) { ?> <br/> 
            <input type="radio" name="vk[<?php echo $ii; ?>][PostType]" value="A" <?php if ( !isset($postType) || $postType == '' || $postType == 'A') echo 'checked="checked"'; ?> /> <?php _e('Text Post with "attached" blogpost', 'nxs_snap') ?>
          <?php } ?><br/><div class="popShAtt" id="popShAtt<?php echo $ii; ?>VKX"><h3>vKontakte(VK) <?php _e('Post Types', 'nxs_snap') ?></h3><img src="<?php echo $nxs_plurl; ?>img/vkPostTypesDiff6.png" width="600" height="257" alt="<?php _e('Post Types', 'nxs_snap') ?>"/></div>
        </td></tr>
        
        <?php /* ## Select Image & URL ## */ nxs_showImgToUseDlg($nt, $ii, $imgToUse); nxs_showURLToUseDlg($nt, $ii, $urlToUse); ?>  
        <?php } 
    }
      
  }
  
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['msgFrmt'] = $pMeta['SNAPformat'];    
     if (isset($pMeta['imgToUse'])) $optMt['imgToUse'] = $pMeta['imgToUse']; if (isset($pMeta['urlToUse'])) $optMt['urlToUse'] = $pMeta['urlToUse']; 
     if (isset($pMeta['AttachPost'])) $optMt['attch'] = ($pMeta['AttachPost'] != '')?$pMeta['AttachPost']:0; else { if (isset($pMeta['SNAPformat'])) $optMt['attch'] = 0; } 
     if (isset($pMeta['addBackLink'])) $optMt['addBackLink'] = ($pMeta['addBackLink'] != '')?$pMeta['addBackLink']:0; else { if (isset($pMeta['SNAPformat'])) $optMt['addBackLink'] = 0; } 
     if (isset($pMeta['PostType'])) $optMt['postType'] = ($pMeta['PostType'] != '')?$pMeta['PostType']:0; else { if (isset($pMeta['SNAPformat'])) $optMt['postType'] = 'T'; } 
     if (isset($pMeta['doVK'])) $optMt['doVK'] = $pMeta['doVK'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doVK'] = 0; } 
     if (isset($pMeta['SNAPincludeVK']) && $pMeta['SNAPincludeVK'] == '1' ) $optMt['doVK'] = 1;
     return $optMt;
  }
}}

if (!function_exists("nxs_rePostToVK_ajax")) { function nxs_rePostToVK_ajax() { check_ajax_referer('nxsSsPageWPN');  $postID = $_POST['id']; // $result = nsPublishTo($id, 'VK', true);   
    $options = get_option('NS_SNAutoPoster');  foreach ($options['vk'] as $ii=>$nto) if ($ii==$_POST['nid']) {  $nto['ii'] = $ii; $nto['pType'] = 'aj';
      $ntpo =  get_post_meta($postID, 'snapVK', true); /* echo $postID."|"; echo $fbpo; */ $ntpo =  maybe_unserialize($ntpo); // prr($ntpo); 
      if (is_array($ntpo) && isset($ntpo[$ii]) && is_array($ntpo[$ii]) ){ $ntClInst = new nxs_snapClassVK(); $nto = $ntClInst->adjMetaOpt($nto, $ntpo[$ii]); } //prr($nto);
      $result = nxs_doPublishToVK($postID, $nto); if ($result == 200) die("Successfully sent your post to vKontakte(VK)."); else die($result);
    }    
  }
}

if (!function_exists("nxs_getVKHeaders")) {  function nxs_getVKHeaders($ref, $post=false, $aj=false){ $hdrsArr = array(); 
 $hdrsArr['Cache-Control']='no-cache'; $hdrsArr['Connection']='keep-alive'; $hdrsArr['Referer']=$ref;
 $hdrsArr['User-Agent']='Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.45 Safari/537.17';
 if($post===true) $hdrsArr['Content-Type']='application/x-www-form-urlencoded'; 
 if($aj===true) $hdrsArr['X-Requested-With']='XMLHttpRequest'; 
 $hdrsArr['Accept']='text/html, application/xhtml+xml, */*'; $hdrsArr['DNT']='1';
 if (function_exists('gzdeflate')) $hdrsArr['Accept-Encoding']='gzip,deflate'; $hdrsArr['Accept-Language']='en-US,en;q=0.8'; $hdrsArr['Accept-Charset']='ISO-8859-1,utf-8;q=0.7,*;q=0.3'; return $hdrsArr;
}}



if (!function_exists("nxs_doPublishToVK")) { //## Second Function to Post to VK
  function nxs_doPublishToVK($postID, $options){ global $ShownAds, $nxs_vkCkArray; $ntCd = 'VK'; $ntCdL = 'vk'; $ntNm = 'vKontakte(VK)'; $vidURL = ''; $imgVURL = ''; $dsc = ''; $lng = '';
      if (!is_array($options)) $options = maybe_unserialize(get_post_meta($postID, $options, true));
      //if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToVK',  array($postID, $options));
      $addParams = nxs_makeURLParams(array('NTNAME'=>$ntNm, 'NTCODE'=>$ntCd, 'POSTID'=>$postID, 'ACCNAME'=>$options['nName']));
      if (empty($options['imgToUse'])) $options['imgToUse'] = ''; if (empty($options['imgSize'])) $options['imgSize'] = '';
      $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); 
      
      $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
      $logNT = '<span style="color:#000080">vKontakte</span> - '.$options['nName'];      
      $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
      if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') { 
         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$options['pType']); return;
        }
      }
      
      if ($postID=='0') { echo "Testing ... <br/><br/>"; $urlToGo = home_url(); $msg = 'Test Link from '.$urlToGo; } else { $post = get_post($postID); if(!$post) return;
        $options['msgFrmt'] = strip_tags(nsFormatMessage($options['msgFrmt'], $postID, $addParams)); 
        //## MyURL - URLToGo code
        if (!isset($options['urlToUse']) || trim($options['urlToUse'])=='') $myurl = trim(get_post_meta($postID, 'snap_MYURL', true)); if ($myurl!='') $options['urlToUse'] = $myurl;
        if (isset($options['urlToUse']) && trim($options['urlToUse'])!='') { $urlToGo = $options['urlToUse']; $options['useFBGURLInfo'] = true; } else $urlToGo = get_permalink($postID);      
        if($addParams!='') $urlToGo .= (strpos($urlToGo,'?')!==false?'&':'?').$addParams; 
        nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1'));
      }       
      $extInfo = ' | PostID: '.$postID." - ".(is_object($post)?$post->post_title:'').' |'.$options['pType'];    
      //## Message & Format                 
      if (trim($options['imgToUse'])!='') $imgURL = $options['imgToUse']; else $imgURL = nxs_getPostImage($postID, 'full'); if (preg_match("/noImg.\.png/i", $imgURL)) $imgURL = '';       
      
      if (function_exists('nxs_doPostToVK')) { $vids = nsFindVidsInPost($post); if (count($vids)>0) {        
          if (strlen($vids[0])==11) { $vidURL = 'http://www.youtube.com/watch?v='.$vids[0]; $imgURL = 'http://img.youtube.com/vi/'.$vids[0].'/maxresdefault.jpg'; } 
          if (strlen($vids[0])==8) { $vidURL = 'https://secure.vimeo.com/moogaloop.swf?clip_id='.$vids[0].'&autoplay=1';
            //$mssg['source'] = 'http://player.vimeo.com/video/'.$vids[0]; 
            $apiURL = "http://vimeo.com/api/v2/video/".$vids[0].".json?callback=showThumb"; $json = wp_remote_get($apiURL);
            if (!is_wp_error($json)) { $json = $json['body']; $json = str_replace('showThumb(','',$json); $json = str_replace('])',']',$json);  $json = json_decode($json, true); $imgVURL = $json[0]['thumbnail_large']; }           
          }
        }      
      }
      if (!empty($options['attchAsVid']) && $options['attchAsVid']=='1' && trim($imgVURL)!='') $imgURL = $imgVURL; 
      
      if ($options['postType']=='A'){
        if (trim($options['msgAFormat'])!='') {$dsc = nsFormatMessage($options['msgAFormat'], $postID, $addParams);} else { 
          if (function_exists('aioseop_mrt_fix_meta') && $dsc=='')  $dsc = trim(get_post_meta($postID, '_aioseop_description', true)); 
          if (function_exists('wpseo_admin_init') && $dsc=='') $dsc = trim(get_post_meta($postID, '_yoast_wpseo_opengraph-description', true));  
          if (function_exists('wpseo_admin_init') && $dsc=='') $dsc = trim(get_post_meta($postID, '_yoast_wpseo_metadesc', true));      
          if (is_object($post) && $dsc=='') $dsc = trim(apply_filters('the_content', nxs_doQTrans($post->post_excerpt, $lng)));  if ($dsc=='') $dsc = trim(nxs_doQTrans($post->post_excerpt, $lng)); 
          if (is_object($post) && $dsc=='') $dsc = trim(apply_filters('the_content', nxs_doQTrans($post->post_content, $lng)));  if ($dsc=='') $dsc = trim(nxs_doQTrans($post->post_content, $lng));  
          if (is_object($post) && $dsc=='') $dsc = get_bloginfo('description'); 
        }  $dsc = strip_tags($dsc); $dsc = nxs_decodeEntitiesFull($dsc); $dsc = nsTrnc($dsc, 900, ' ');
      } else $dsc = '';
      
      $message = array('siteName'=>$blogTitle, 'url'=>$urlToGo, 'imageURL'=>$imgURL, 'videoURL'=>$vidURL, 'urlTitle'=>nxs_doQTrans($post->post_title, $lng), 'urlDescr'=>$dsc);    
      //## Actual Post
      $ntToPost = new nxs_class_SNAP_VK(); $ret = $ntToPost->doPostToNT($options, $message);
      //## Check Phone Req Return            
      if ( is_string($ret) && stripos($ret, 'Phone verification required:')!==false) {  global $plgn_NS_SNAutoPoster;  $gOptions = $plgn_NS_SNAutoPoster->nxs_options;
        $phtext = str_ireplace('Phone verification required: ','',$ret); $ret .= ". Please refresh/reload the SNAP settings page and enter your phone.";
        $gOptions['vk'][$ii]['vkPhReq'] = $phtext; update_option('NS_SNAutoPoster', $gOptions);            
        if ($postID=='0') prr($ret); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true)." - BAD USER/PASS", $extInfo); return " -= BAD USER/PASS - Phone verification required =- ";
      }       
      //## Save Session
      if (empty($options['vkSvC']) || serialize($nxs_vkCkArray)!=$options['vkSvC']) { global $plgn_NS_SNAutoPoster;  $gOptions = $plgn_NS_SNAutoPoster->nxs_options;
          if (isset($options['ii']) && $options['ii']!=='')  { $gOptions['vk'][$options['ii']]['vkSvC'] = serialize($nxs_vkCkArray); update_option('NS_SNAutoPoster', $gOptions);  }
          else foreach ($gOptions['vk'] as $ii=>$gpn) { $result = array_diff($options, $gpn); 
            if (!is_array($result) || count($result)<1) { $gOptions['vk'][$ii]['vkSvC'] = serialize($nxs_vkCkArray); update_option('NS_SNAutoPoster', $gOptions); break; }
          }        
      } 
      //## Process Results
      if (is_array($ret) && !empty($ret['err'])) nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo); 
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