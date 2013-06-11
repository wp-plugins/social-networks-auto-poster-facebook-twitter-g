<?php    
//## NextScripts vKontakte(VK) Connection Class
$nxs_snapAvNts[] = array('code'=>'VK', 'lcode'=>'vk', 'name'=>'vKontakte(VK)');

if (!class_exists("nxs_snapClassVK")) { class nxs_snapClassVK {
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){  global $nxs_plurl; $ntInfo = array('code'=>'VK', 'lcode'=>'vk', 'name'=>'vKontakte(VK)', 'defNName'=>'', 'tstReq' => false); ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> <?php wp_nonce_field( 'ns'.$ntInfo['code'], 'ns'.$ntInfo['code'].'_wpnonce' ); ?>
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = str_ireplace('https://vk.com','', str_ireplace('http://vk.com','', $pbo['url'])); ?>
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
  function showNewNTSettings($mNTo){ $nto = array('nName'=>'', 'doVK'=>'1', 'url'=>'', 'vkAppID'=>'', 'imgUpl'=>'1', 'addBackLink'=>'1', 'vkPostType'=>'T', 'msgAFormat'=>'', 'attch'=>'1', 'vkPgID'=>'', 'vkAppAuthUser'=>'', 'msgFrmt'=>'New post has been published on %SITENAME%' ); $this->showNTSettings($mNTo, $nto, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl, $nxs_snapThisPageUrl; if ((int)$options['attch']==0 && (!isset($options['trPostType']) || $options['trPostType']=='')) $options['trPostType'] = 'T';  
    if (!isset($options['nHrs'])) $options['nHrs'] = 0; if (!isset($options['nMin'])) $options['nMin'] = 0;  if (!isset($options['catSel'])) $options['catSel'] = 0;  if (!isset($options['catSelEd'])) $options['catSelEd'] = ''; ?> 
    <div id="doVK<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>" style="background-image: url(<?php echo $nxs_plurl; ?>img/vk-bg.png);  background-position:90% 10%;">   <input type="hidden" name="apDoSVK<?php echo $ii; ?>" value="0" id="apDoSVK<?php echo $ii; ?>" />                                
    <?php if ($isNew) { ?>    <input type="hidden" name="vk[<?php echo $ii; ?>][apDoVK]" value="1" id="apDoNewVK<?php echo $ii; ?>" /> <?php } ?>
    
     <div class="nsx_iconedTitle" style="float: right; max-width: 322px; text-align: right; background-image: url(<?php echo $nxs_plurl; ?>img/vk16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-vkontakte-social-networks-auto-poster-wordpress/"><?php $nType="vKontakte"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a><br/>
     <span style="font-size: 10px;">Please use URL <em style="font-size: 10px; color:#CB4B16;">http://<?php echo $_SERVER["SERVER_NAME"] ?></em> and domain <em style="font-size: 10px; color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em> in your vKontakte(VK) App</span>
     
     </div>
    
    <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easely identify it', 'nxs_snap'); ?></i> </div><input name="vk[<?php echo $ii; ?>][nName]" id="vknName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
    <?php echo nxs_addQTranslSel('vk', $ii, $options['qTLng']); ?>
    <?php echo nxs_addPostingDelaySel('vk', $ii, $options['nHrs'], $options['nMin']); ?>
    
    <?php if (!$isNew) { ?>
    <div style="width:100%;"><strong><?php _e('Categories', 'nxs_snap'); ?>:</strong>
       <input value="0" id="catSelA<?php echo $ii; ?>" type="radio" name="vk[<?php echo $ii; ?>][catSel]" <?php if ((int)$options['catSel'] != 1) echo "checked"; ?> /> All                                  
       <input value="1" id="catSelSVK<?php echo $ii; ?>" type="radio" name="vk[<?php echo $ii; ?>][catSel]" <?php if ((int)$options['catSel'] == 1) echo "checked"; ?> /> <a href="#" style="text-decoration: none;" class="showCats" id="nxs_SCA_VK<?php echo $ii; ?>" onclick="jQuery('#catSelSVK<?php echo $ii; ?>').attr('checked', true); jQuery('#tmpCatSelNT').val('VK<?php echo $ii; ?>'); nxs_markCats( jQuery('#nxs_SC_VK<?php echo $ii; ?>').val() ); jQuery('#showCatSel').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false], position: [75, 'auto']}); return false;">Selected<?php if ($options['catSelEd']!='') echo "[".(substr_count($options['catSelEd'], ",")+1)."]"; ?></a>       
       <input type="hidden" name="vk[<?php echo $ii; ?>][catSelEd]" id="nxs_SC_VK<?php echo $ii; ?>" value="<?php echo $options['catSelEd']; ?>" />
    <br/><i><?php _e('Only selected categories will be autoposted to this account', 'nxs_snap'); ?></i></div> 
    <br/>
    <?php } ?>
    
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
            <!-- <a href="http://api.vkontakte.ru/oauth/authorize?client_id=<?php echo $options['vkAppID'];?>&scope=offline,wall,photos,pages&redirect_uri=<?php echo urlencode($nxs_snapThisPageUrl.'&auth=vk&acc='.$options['ii']);?>">Authorize Your vKontakte(VK) Account</a>  -->
            <?php if (!isset($options['vkAppAuthUser']) || $options['vkAppAuthUser']<1) { ?> <div class="blnkg">&lt;=== <?php _e('Authorize your account', 'nxs_snap'); ?> ===</div> <?php } ?>
            
            <div style="width:100%;"><strong>vKontakte(VK) Auth Response:</strong> </div><input name="vk[<?php echo $ii; ?>][apVKAuthResp]" style="width: 50%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['apVKAuthResp'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/><br/>
            
            <?php } ?>
            
    </div>      
    
    <div style="width:100%; margin-bottom: 5px;"><b style="font-size: 14px;" >NextScripts VK API</b> <?php _e('(It could be used for "Text with attached link" posts)', 'nxs_snap'); ?></div>
    
    <div style="width:100%; margin-left: 15px;">
      <?php if( function_exists("nxs_doPostToVK")) { ?>    
         <div style="width:100%;"><strong>vKontakte(VK) Email:</strong> </div><input name="vk[<?php echo $ii; ?>][uName]" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['uName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  
         <div style="width:100%;"><strong>vKontakte(VK) Password:</strong> </div><input name="vk[<?php echo $ii; ?>][uPass]" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($options['uPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['uPass'], 5)):$options['uPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />    
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
            <?php wp_nonce_field( 'rePostToVK', 'rePostToVK_wpnonce' ); ?>
            <br/><br/><b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('VK','<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>         
            <?php }?>
            <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div>
            
          </div>        
        <?php
      
  } 
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'VK'; $lcode = 'vk'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apVKAppID']) && $pval['apVKAppID']!='') { if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apDoVK']))         $options[$ii]['doVK'] = $pval['apDoVK']; else $options[$ii]['doVK'] = 0;
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apVKAppID']))      $options[$ii]['vkAppID'] = trim($pval['apVKAppID']);                
        
        if (isset($pval['uName']))      $options[$ii]['uName'] = trim($pval['uName']);                                
        if (isset($pval['uPass']))    $options[$ii]['uPass'] = 'n5g9a'.nsx_doEncode($pval['uPass']); else $options[$ii]['uPass'] = '';                 
        
        
        if (isset($pval['apVKAuthResp']))  {   $options[$ii]['apVKAuthResp'] = trim($pval['apVKAuthResp']); 
          $options[$ii]['vkAppAuthToken'] = trim( CutFromTo($pval['apVKAuthResp'].'&', 'access_token=','&')); 
          $options[$ii]['vkAppAuthUser'] = trim( CutFromTo($pval['apVKAuthResp']."&", 'user_id=','&')); 
          $hdrsArr = nxs_getVKHeaders($pval['url']);
          $response = wp_remote_get($pval['url'], array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr)); 
          if (is_wp_error($response)) { echo "ERROR: <br/>"; prr($response); return;} $contents = $response['body'];     
          if (stripos($contents, '"group_id":')!==false) { $options[$ii]['pgIntID'] =  '-'.CutFromTo($contents, '"group_id":', ','); $type='all'; }  
          if (stripos($contents, '"public_id":')!==false) { $options[$ii]['pgIntID'] =  '-'.CutFromTo($contents, '"public_id":', ','); $type='all'; }  
          if (stripos($contents, '"user_id":')!==false) {   $options[$ii]['pgIntID'] =  CutFromTo($contents, '"user_id":', ','); $type='own'; }  
        }
        
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']);
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
        
        if (isset($pval['postType']))     $options[$ii]['postType'] = trim($pval['postType']);
        if (isset($pval['attch']))      $options[$ii]['attch'] = $pval['attch']; else $options[$ii]['attch'] = 0;
        if (isset($pval['attchAsVid'])) $options[$ii]['attchAsVid'] = $pval['attchAsVid']; else $options[$ii]['attchAsVid'] = 0;
        
        if (isset($pval['apVKImgUpl']))     $options[$ii]['imgUpl'] = $pval['apVKImgUpl']; else $options[$ii]['imgUpl'] = 0;
        if (isset($pval['addBackLink']))     $options[$ii]['addBackLink'] = $pval['addBackLink']; else $options[$ii]['addBackLink'] = 0;
        
        if (isset($pval['msgFrmt']))    $options[$ii]['msgFrmt'] = trim($pval['msgFrmt']); 
        if (isset($pval['msgAFormat']))    $options[$ii]['msgAFormat'] = trim($pval['msgAFormat']); 
        
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
                
        if (isset($pval['url']))  {  $options[$ii]['url'] = trim($pval['url']);   if ( substr($options[$ii]['url'], 0, 4)!='http' )  $options[$ii]['url'] = 'http://'.$options[$ii]['url'];
          $vkPgID = $options[$ii]['url']; if (substr($vkPgID, -1)=='/') $vkPgID = substr($vkPgID, 0, -1);  $vkPgID = substr(strrchr($vkPgID, "/"), 1); 
          if (strpos($vkPgID, '?')!==false) $vkPgID = substr($vkPgID, 0, strpos($vkPgID, '?')); 
          $options[$ii]['vkPgID'] = $vkPgID; //echo $vkPgID;
          if (strpos($options[$ii]['url'], '?')!==false) $options[$ii]['url'] = substr($options[$ii]['url'], 0, strpos($options[$ii]['url'], '?'));// prr($pval); prr($options[$ii]); // die();
        }                  
      }
    } return $options;
  } 
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; 
    foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapVK', true));  if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
        $doVK = $ntOpt['doVK'] && (is_array($pMeta) || $ntOpt['catSel']!='1');
        $isAvailVK =  $ntOpt['url']!='' && $ntOpt['vkAppID']!='' || $ntOpt['uPass']!=''; $isAttachVK = $ntOpt['attch']; $msgFrmt = htmlentities($ntOpt['msgFrmt'], ENT_COMPAT, "UTF-8"); $postType = $ntOpt['postType']; 
      ?>
      <tr><th style="text-align:left;" colspan="2"> <?php if ( $ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='' )  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_VK<?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>      
        <?php if ($isAvailVK) { ?><input class="nxsGrpDoChb" value="1" id="doVK<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="vk[<?php echo $ii; ?>][doVK]" <?php if ((int)$doVK == 1) echo 'checked="checked" title="def"';  ?> /> 
        <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="vk[<?php echo $ii; ?>][doVK]" value="<?php echo $doVK;?>"> <?php } ?> <?php } ?>      
        <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/vk16.png);">vKontakte(VK) - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th>
        <td><?php //## Only show RePost button if the post is "published"
        if ($post->post_status == "publish" && $isAvailVK) { ?>
          <input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToVK_repostButton" id="rePostToVK_button" value="<?php _e('Repost to vKontakte(VK)', 'nxs_snap') ?>" />
        <?php wp_nonce_field( 'rePostToVK', 'rePostToVK_wpnonce' ); } ?>
        <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID'])) { ?> <span id="pstdVK<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
             <a style="font-size: 10px;" href="http://vk.com/wall<?php echo $pMeta[$ii]['pgID']; ?>" target="_blank"><?php $nType="vKontakte(VK)"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
           </span>
        <?php } ?>
        </td></tr>
          <?php if (!$isAvailVK) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup and Authorize your vKontakte(VK) Account to AutoPost to vKontakte(VK)</b>
          <?php } elseif ($post->post_status != "puZblish") {?> 
        <tr id="altFormat1" style=""><th scope="row" valign="top" style="vertical-align:top; padding-top:6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Message Format:', 'nxs_snap') ?></th>
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
        </td></tr><?php } 
    }
      
  }
  
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['msgFrmt'] = $pMeta['SNAPformat'];    
     if (isset($pMeta['AttachPost'])) $optMt['attch'] = ($pMeta['AttachPost'] != '')?$pMeta['AttachPost']:0; else { if (isset($pMeta['SNAPformat'])) $optMt['attch'] = 0; } 
     if (isset($pMeta['addBackLink'])) $optMt['addBackLink'] = ($pMeta['addBackLink'] != '')?$pMeta['addBackLink']:0; else { if (isset($pMeta['SNAPformat'])) $optMt['addBackLink'] = 0; } 
     if (isset($pMeta['PostType'])) $optMt['postType'] = ($pMeta['PostType'] != '')?$pMeta['PostType']:0; else { if (isset($pMeta['SNAPformat'])) $optMt['postType'] = 'T'; } 
     if (isset($pMeta['doVK'])) $optMt['doVK'] = $pMeta['doVK'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doVK'] = 0; } 
     if (isset($pMeta['SNAPincludeVK']) && $pMeta['SNAPincludeVK'] == '1' ) $optMt['doVK'] = 1;
     return $optMt;
  }
}}

if (!function_exists("nxs_rePostToVK_ajax")) { function nxs_rePostToVK_ajax() { check_ajax_referer('rePostToVK');  $postID = $_POST['id']; // $result = nsPublishTo($id, 'VK', true);   
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
 $hdrsArr['Accept-Encoding']='gzip,deflate'; $hdrsArr['Accept-Language']='en-US,en;q=0.8'; $hdrsArr['Accept-Charset']='ISO-8859-1,utf-8;q=0.7,*;q=0.3'; return $hdrsArr;
}}

if (!function_exists("nxs_uplImgtoVK")) {  function nxs_uplImgtoVK($imgURL, $options){
    $postUrl = 'https://api.vkontakte.ru/method/photos.getWallUploadServer?gid='.(str_replace('-','',$options['pgIntID'])).'&access_token='.$options['vkAppAuthToken'];
    $response = wp_remote_get($postUrl); $thumbUploadUrl = $response['body'];    
    if (!empty($thumbUploadUrl)) { $thumbUploadUrlObj = json_decode($thumbUploadUrl); $VKuploadUrl = $thumbUploadUrlObj->response->upload_url; }    
    if (!empty($VKuploadUrl)) {                               
      $remImgURL = urldecode($imgURL); $urlParced = pathinfo($remImgURL); $remImgURLFilename = $urlParced['basename']; $imgData = wp_remote_get($remImgURL); $imgData = $imgData['body'];        
      $tmp=array_search('uri', @array_flip(stream_get_meta_data($GLOBALS[mt_rand()]=tmpfile())));  
      if (!is_writable($tmp)) return "Your temporary folder or file (file - ".$tmp.") is not witable. Can't upload image to VK";
      rename($tmp, $tmp.='.png'); register_shutdown_function(create_function('', "unlink('{$tmp}');"));       
      file_put_contents($tmp, $imgData); 
      
      $ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $VKuploadUrl); curl_setopt($ch, CURLOPT_POST, 1); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, array('photo' => '@' . $tmp)); $response = curl_exec($ch); $errmsg = curl_error($ch); curl_close($ch); //prr($response);
        
      $uploadResultObj = json_decode($response); // prr($response); //prr($uploadResultObj);
      
      if (!empty($uploadResultObj->server) && !empty($uploadResultObj->photo) && !empty($uploadResultObj->hash)) {
        $postUrl = 'https://api.vkontakte.ru/method/photos.saveWallPhoto?server='.$uploadResultObj->server.'&photo='.$uploadResultObj->photo.'&hash='.$uploadResultObj->hash.'&gid='.(str_replace('-','',$options['pgIntID'])).'&access_token='.$options['vkAppAuthToken'];
        $response = wp_remote_get($postUrl);            
        $resultObject = json_decode($response['body']); //prr($resultObject);
        if (isset($resultObject) && isset($resultObject->response[0]->id)) { return $resultObject->response[0]; } else { return false; }
      }
   }
}}

if (!function_exists("nxs_doPublishToVK")) { //## Second Function to Post to VK
  function nxs_doPublishToVK($postID, $options){ global $ShownAds, $nxs_vkCkArray; $ntCd = 'VK'; $ntCdL = 'vk'; $ntNm = 'vKontakte(VK)';
      //if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToVK',  array($postID, $options));
      $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
      $logNT = '<span style="color:#000080">vKontakte</span> - '.$options['nName'];      
      $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
      if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') { 
         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$options['pType']); return;
        }
      }
      $email = $options['uName'];  $pass = (substr($options['uPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['uPass'], 5)):$options['uPass']);      
      if ($postID=='0') { echo "Testing ... <br/><br/>"; $link = home_url(); $msg = 'Test Link from '.$link; } else { $post = get_post($postID); if(!$post) return;
        $msgFormat = $options['msgFrmt'];  $msg = strip_tags(nsFormatMessage($msgFormat, $postID)); $link = get_permalink($postID); nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1'));
      } 
      $dusername = $options['uName'];  $postType = $options['postType'];  //$link = urlencode($link); $desc = urlencode(substr($msg, 0, 500));      
      $extInfo = ' | PostID: '.$postID." - ".$post->post_title.' |'.$options['pType']; $imgURL = nxs_getPostImage($postID);
  
      $vids = nsFindVidsInPost($post); if (count($vids)>0) {        
          if (strlen($vids[0])==11) { $vidURL = 'http://www.youtube.com/watch?v='.$vids[0]; $imgURL = 'http://img.youtube.com/vi/'.$vids[0].'/maxresdefault.jpg'; } 
          if (strlen($vids[0])==8) { $vidURL = 'https://secure.vimeo.com/moogaloop.swf?clip_id='.$vids[0].'&autoplay=1';
            //$mssg['source'] = 'http://player.vimeo.com/video/'.$vids[0]; 
            $apiURL = "http://vimeo.com/api/v2/video/".$vids[0].".json?callback=showThumb"; $json = wp_remote_get($apiURL);
            if (!is_wp_error($json)) { $json = $json['body']; $json = str_replace('showThumb(','',$json); $json = str_replace('])',']',$json);  $json = json_decode($json, true); $imgURL = $json[0]['thumbnail_large']; }           
          }
       }      
      $msgOpts = array(); $msgOpts['uid'] =  $options['vkPgID']; // if ($link!='') $msgOpts['link'] = $link;            
      if ($vidURL!='' && $postType=="I") { $postType='A';  $link=$vidURL; $msgOpts['vID'] = $vids[0]; }  
      if ($postType=='I' && trim($imgURL)=='') $postType='T';  $msgOpts['type'] = $postType;      
      if ($postType=='A' && $link!='') {  
        //## Login
        if (isset($options['vkSvC'])) $nxs_vkCkArray = maybe_unserialize( $options['vkSvC']); $loginError = true;
        if (is_array($nxs_vkCkArray)) $loginError = nxs_doCheckVK(); if ($loginError!=false) $loginError = nxs_doConnectToVK($email, $pass); 
        if (serialize($nxs_vkCkArray)!=$options['vkSvC']) { global $plgn_NS_SNAutoPoster;  $gOptions = $plgn_NS_SNAutoPoster->nxs_options;
          if (isset($options['ii']) && $options['ii']!=='')  { $gOptions['vk'][$options['ii']]['vkSvC'] = serialize($nxs_vkCkArray); update_option('NS_SNAutoPoster', $gOptions);  }
          else foreach ($gOptions['vk'] as $ii=>$gpn) { $result = array_diff($options, $gpn); 
            if (!is_array($result) || count($result)<1) { $gOptions['vk'][$ii]['vkSvC'] = serialize($nxs_vkCkArray); update_option('NS_SNAutoPoster', $gOptions); break; }
          }        
        }  
        if ($loginError!==false) {if ($postID=='0') prr($loginError); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($loginError, true)." - BAD USER/PASS", $extInfo); return " -= BAD USER/PASS =- ";}      
        //## Post
        if (trim($fbMsgAFormat)!='') {$dsc = nsFormatMessage($fbMsgAFormat, $postID);} else { if (function_exists('aioseop_mrt_fix_meta') && $dsc=='')  $dsc = trim(get_post_meta($postID, '_aioseop_description', true)); 
          if (function_exists('wpseo_admin_init') && $dsc=='') $dsc = trim(get_post_meta($postID, '_yoast_wpseo_opengraph-description', true));  
          if (function_exists('wpseo_admin_init') && $dsc=='') $dsc = trim(get_post_meta($postID, '_yoast_wpseo_metadesc', true));      
          if ($dsc=='') $dsc = trim(apply_filters('the_content', nxs_doQTrans($post->post_excerpt, $lng)));  if ($dsc=='') $dsc = trim(nxs_doQTrans($post->post_excerpt, $lng)); 
          if ($dsc=='') $dsc = trim(apply_filters('the_content', nxs_doQTrans($post->post_content, $lng)));  if ($dsc=='') $dsc = trim(nxs_doQTrans($post->post_content, $lng));  
          if ($dsc=='') $dsc = get_bloginfo('description'); 
        }  $dsc = strip_tags($dsc); $dsc = nxs_decodeEntitiesFull($dsc); $dsc = nsTrnc($dsc, 900, ' ');
          $msgOpts['url'] = $link; $msgOpts['urlTitle'] = nxs_doQTrans($post->post_title, $lng); $msgOpts['urlDesc'] = $dsc; $msgOpts['imgURL'] = $imgURL; 
        $ret = nxs_doPostToVK($msg, $options['url'], $msgOpts); //  prr($ret);
      } //prr($postType);
      
      if ($postType=='I') { $imgUpld = nxs_uplImgtoVK($imgURL, $options); if (is_object($imgUpld)) { $imgID = $imgUpld->id; $atts[] = $imgID; } else  nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($imgUpld, true), $extInfo); }
      if ($postType!='A') { if( $options['addBackLink']=='1') $atts[] = $link;       
        if (is_array($atts)) $atts = implode(',', $atts);
        
        $postUrl = 'https://api.vkontakte.ru/method/wall.post';
        $postArr = array('owner_id'=>$options['pgIntID'], 'access_token'=>$options['vkAppAuthToken'], 'from_group'=>'1', 'message'=>$msg, 'attachment'=>$atts);
        $response = wp_remote_post($postUrl, array('body' => $postArr)); 
        if ( is_wp_error($response) || (is_object($response) && (isset($response->errors))) || (is_array($response) && stripos($response['body'],'"error":')!==false )) { 
           $ret = $response['body'];
        } else { $respJ = json_decode($response['body'], true);  $ret = array("code"=>"OK", "post_id"=>$options['pgIntID'].'_'.$respJ['response']['post_id']);   }
          
      }                                
      if (is_array($ret) && $ret['code']=='OK') {  if ($postID=='0')  { nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); echo ' OK - Message Posted, please see your VK Page '; } else 
          { nxs_metaMarkAsPosted($postID, 'VK', $options['ii'], array('isPosted'=>'1', 'pgID'=>$ret['post_id'], 'pDate'=>date('Y-m-d H:i:s'))); nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo);  return 200; }          
      } else {if ($postID=='0') prr($ret); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo);}       
  }
}       
?>