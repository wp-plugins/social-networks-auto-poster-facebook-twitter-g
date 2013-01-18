<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'GP', 'lcode'=>'gp', 'name'=>'Google+');

if (!class_exists("nxs_snapClassGP")) { class nxs_snapClassGP {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl, $nxs_plurl, $nxsOne; $code = 'GP'; $lcode = 'gp'; wp_nonce_field( 'ns'.$code, 'ns'.$code.'_wpnonce' ); ?>
    <hr/><div class="nsx_iconedTitle" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $lcode; ?>16.png);">Google+ Settings:           
            <?php if(!function_exists('doPostToGooglePlus')) {?> </div>  Google+ doesn't have a built-in API for automated posts yet. The current <a href="http://developers.google.com/+/api/">Google+ API</a> is "Read Only" and can't be used for posting.  <br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/google-plus-automated-posting">library module</a> to be able to publish your content to Google+.
            
            <?php } else { $cgpo = count($ntOpts); $mgpo = 1+max(array_keys($ntOpts)); $nxsOne .= "&g=1"; ?>            
              <div class="nsBigText">You have <?php echo $cgpo=='0'?'No':$cgpo; ?> Google+ account<?php if ($cgpo!=1){ ?>s<?php } ?> <!--- <a href="#" class="NXSButton" onclick="doShowHideBlocks2('GP<?php echo $mgpo; ?>');return false;">Add new Google+ Account</a> --> </div></div> 
              <?php  //if (function_exists('nxs_doSMAS1')) nxs_doSMAS1($this, $mgpo); else nxs_doSMAS('Google+', 'GP'.$mgpo); ?>
              <?php foreach ($ntOpts as $indx=>$gpo){ if (trim($gpo['nName']=='')) { $gpo['nName'] = $gpo['gpUName'];  
                if($gpo['gpPageID']!='') $gpo['nName'] .= "Page: ".$gpo['gpPageID']; elseif ($gpo['gpCommID']!='') $gpo['nName'] .= "Community: ".$gpo['gpCommID']; else $gpo['nName'] .= " Profile"; } ?>
                <p style="margin: 0px;margin-left: 5px;">
                  <input value="1" id="apDoGP" name="gp[<?php echo $indx; ?>][apDoGP]" type="checkbox" <?php if ((int)$gpo['doGP'] == 1) echo "checked"; ?> /> 
                  <strong>Auto-publish your Posts to your Google+ <i style="color: #005800;"><?php if($gpo['nName']!='') echo "(".$gpo['nName'].")"; ?></i> </strong>                                         
                  &nbsp;&nbsp;<a id="doGP<?php echo $indx; ?>A" href="#" onclick="doShowHideBlocks2('GP<?php echo $indx; ?>');return false;">[Show Settings]</a> &nbsp;&nbsp;
                  <a href="#" onclick="doDelAcct('gp','<?php echo $indx; ?>', '<?php echo $gpo['gpUName']; ?>');return false;">[Remove Account]</a>
                </p>            
                <?php $this->showNTSettings($indx, $gpo);             
              } ?>            
            <?php }
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($mgpo){ $gpo = array('nName'=>'', 'doGP'=>'1', 'gpUName'=>'', 'gpPageID'=>'', 'gpCommID'=>'', 'postType'=>'A', 'gpPass'=>''); $this->showNTSettings($mgpo, $gpo, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $gpo, $isNew=false){  global $nxs_plurl; ?>
            <div id="doGP<?php echo $ii; ?>Div" <?php if ($isNew){ ?>class="clNewNTSets"<?php } ?> style="max-width: 1000px; background-color: #EBF4FB; background-image: url(<?php echo $nxs_plurl; ?>img/gp-bg.png);  background-position:90% 10%; background-repeat: no-repeat; margin: 10px; border: 1px solid #808080; padding: 10px; display:none;">     <input type="hidden" name="apDoSGP<?php echo $ii; ?>" value="0" id="apDoSGP<?php echo $ii; ?>" />             
            <?php if(!function_exists('doPostToGooglePlus')) {?><span style="color:#580000; font-size: 16px;"><br/><br/>
            <b>Google+ API Library not found</b>
             <br/><br/> Google+ doesn't have a built-in API for automated posts yet. <br/>The current <a target="_blank" href="http://developers.google.com/+/api/">Google+ API</a> is "Read Only" and can't be used for posting.  <br/><br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/google-plus-automated-posting"><b>API Library Module</b></a> to be able to publish your content to Google+.</span></div>
            
            <?php return; }; ?>
            
            <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/gp16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-google-plus-social-networks-auto-poster-wordpress/">Detailed Google+ Installation/Configuration Instructions</a></div>
            
            <div style="width:100%;"><strong>Account Nickname:</strong> <i>Just so you can easely identify it</i> </div><input name="gp[<?php echo $ii; ?>][nName]" id="gpnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($gpo['nName'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /><br/>
            <?php echo nxs_addQTranslSel('gp', $ii, $gpo['qTLng']); ?><?php echo nxs_addPostingDelaySel('gp', $ii, $gpo['nHrs'], $gpo['nMin']); ?>
            
            <?php if (!$isNew) { ?>
    <div style="width:100%;"><strong>Auto-Post Categories:</strong>
       <input value="0" id="catSelA<?php echo $ii; ?>" type="radio" name="gp[<?php echo $ii; ?>][catSel]" <?php if ((int)$gpo['catSel'] != 1) echo "checked"; ?> /> All                                  
       <input value="1" id="catSelSGP<?php echo $ii; ?>" type="radio" name="gp[<?php echo $ii; ?>][catSel]" <?php if ((int)$gpo['catSel'] == 1) echo "checked"; ?> /> <a href="#" style="text-decoration: none;" class="showCats" id="nxs_SCA_GP<?php echo $ii; ?>" onclick="jQuery('#catSelSGP<?php echo $ii; ?>').attr('checked', true); jQuery('#tmpCatSelNT').val('GP<?php echo $ii; ?>'); nxs_markCats( jQuery('#nxs_SC_GP<?php echo $ii; ?>').val() ); jQuery('#showCatSel').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false], position: [75, 'auto']}); return false;">Selected<?php if ($gpo['catSelEd']!='') echo "[".(substr_count($gpo['catSelEd'], ",")+1)."]"; ?></a>       
       <input type="hidden" name="gp[<?php echo $ii; ?>][catSelEd]" id="nxs_SC_GP<?php echo $ii; ?>" value="<?php echo $gpo['catSelEd']; ?>" />
    </div> 
    <br/>
    <?php } ?>
            
            <div style="width:100%;"><strong>Google+ Username:</strong> </div><input name="gp[<?php echo $ii; ?>][apGPUName]" id="apGPUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($gpo['gpUName'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />                
            <div style="width:100%;"><strong>Google+ Password:</strong> </div><input name="gp[<?php echo $ii; ?>][apGPPass]" id="apGPPass" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($gpo['gpPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($gpo['gpPass'], 5)):$gpo['gpPass'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />  <br/>                
            <p><div style="width:100%;"><strong>Google+ Page ID (Optional - for Google+ Pages Only. <b style="color: #580000;"> Leave Empty to publish to your profile or community</b>):</strong> 
            <p style="font-size: 11px; margin: 0px;">For example if URL of your page is https://plus.google.com/u/0/b/117008619877691455570/ your Page ID is: 117008619877691455570. <b>Leave Empty to publish to your profile or community.</b></p>
            </div><input name="gp[<?php echo $ii; ?>][apGPPage]" id="apGPPage" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($gpo['gpPageID'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /> 
            <br/>
            <p><div style="width:100%;"><strong>Google+ Community ID (Optional - for Google+ Communities Only. <b style="color: #580000;"> Leave Empty to publish to your profile or page</b>):</strong> 
            <p style="font-size: 11px; margin: 0px;">For example if URL of your Community is https://plus.google.com/communities/100396001601096060160 your Page ID is: 100396001601096060160. <b>Leave Empty to publish to your profile or page.</b></p>
            </div><input name="gp[<?php echo $ii; ?>][gpCommID]" id="gpCommID" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($gpo['gpCommID'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /> 
            <br/><br/>
            
            <div id="altFormat" style="">
              <div style="width:100%;"><strong id="altFormatText">Message Text Format:</strong> 
              <p style="font-size: 11px; margin: 0px;">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. &nbsp; %URL% - Inserts the URL of your post. &nbsp; %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name.</p>
              </div><input name="gp[<?php echo $ii; ?>][apGPMsgFrmt]" id="apGPMsgFrmt" style="width: 50%;" value="<?php if ($isNew) echo "New post has been published on %SITENAME%"; else _e(apply_filters('format_to_edit', htmlentities($gpo['gpMsgFormat'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster'); ?>" />
            </div><br/>          
            
             <div style="width:100%;"><strong id="altFormatText">Post Type:</strong>&lt;-- (<a id="showShAtt" onmouseout="hidePopShAtt('<?php echo $ii; ?>XG');" onmouseover="showPopShAtt('<?php echo $ii; ?>XG', event);" onclick="return false;" class="underdash" href="http://www.nextscripts.com/blog/">What's the difference?</a>)  </div>                      
<div style="margin-left: 10px;">
        <?php if(!isset($gpo['postType']) || $gpo['postType']=='') {
            if ((int)$gpo['imgPost'] == 1) $gpo['postType'] = 'I';
            if ((int)$gpo['gpAttch'] == 1 || $isNew) $gpo['postType'] = 'A';
        } ?>
        <input type="radio" name="gp[<?php echo $ii; ?>][postType]" value="T" <?php if ($gpo['postType'] == 'T') echo 'checked="checked"'; ?> /> Text Post - <i>just text message</i><br/>                    
        <input type="radio" name="gp[<?php echo $ii; ?>][postType]" value="I" <?php if ($gpo['postType'] == 'I') echo 'checked="checked"'; ?> /> Google+ Image Post - <i>big image with text message</i><br/>
        <input type="radio" name="gp[<?php echo $ii; ?>][postType]" value="A" <?php if ( !isset($gpo['postType']) || $gpo['postType'] == '' || $gpo['postType'] == 'A') echo 'checked="checked"'; ?> /> Add blogpost to Google+ message as an attachment<br/>
<div class="popShAtt" id="popShAtt<?php echo $ii; ?>XG"><h3>Google+ Post Types</h3><img src="<?php echo $nxs_plurl; ?>img/gpPostTypesDiff6.png" width="600" height="285" alt="Google+ Post Types"/></div>
   </div><br/>      
            
          
            
            <?php if ($isNew) { ?> <input type="hidden" name="gp[<?php echo $ii; ?>][apDoGP]" value="1" id="apDoNewGP<?php echo $ii; ?>" /> <?php } ?>
            <?php if ($gpo['gpPass']!='') { ?>
            <?php wp_nonce_field( 'rePostToGP', 'rePostToGP_wpnonce' ); ?>
            <b>Test your settings:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('GP', '<?php echo $ii; ?>'); return false;">Submit Test Post to Google+</a>      
               
            <?php } 
            
            ?><div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div></div><?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'GP'; $lcode = 'gp'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apGPUName']) && $pval['apGPUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apGPUName']))   $options[$ii]['gpUName'] = trim($pval['apGPUName']);
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apGPPass']))    $options[$ii]['gpPass'] = 'n5g9a'.nsx_doEncode($pval['apGPPass']); else $options[$ii]['gpPass'] = '';  
        if (isset($pval['apGPPage']))    $options[$ii]['gpPageID'] = trim($pval['apGPPage']);  
        if (isset($pval['gpCommID']))    $options[$ii]['gpCommID'] = trim($pval['gpCommID']);  
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']);
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
                      
        if (isset($pval['postType']))   $options[$ii]['postType'] = $pval['postType'];         
        if (isset($pval['apGPMsgFrmt'])) $options[$ii]['gpMsgFormat'] = trim($pval['apGPMsgFrmt']);                                                  
        if (isset($pval['apDoGP']))      $options[$ii]['doGP'] = $pval['apDoGP']; else $options[$ii]['doGP'] = 0; 
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      }
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID;
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapGP', true));  if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); $doGP = $ntOpt['doGP'] && $ntOpt['catSel']!='1';   
        $isAvailGP =  $ntOpt['gpUName']!='' && $ntOpt['gpPass']!='';   $gpMsgFormat = htmlentities($ntOpt['gpMsgFormat'], ENT_COMPAT, "UTF-8");      
        if(!isset($ntOpt['postType']) || $ntOpt['postType']=='') {
            if ((int)$ntOpt['imgPost'] == 1) $ntOpt['postType'] = 'I';
            if ((int)$ntOpt['gpAttch'] == 1 || $isNew) $ntOpt['postType'] = 'A';
        } $gpPostType = $ntOpt['postType'];
      ?>  
      <tr><th style="text-align:left;" colspan="2"><?php if ( $ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='' )  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_GP<?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if ($isAvailGP) { ?><input class="nxsGrpDoChb" value="1" id="doGP<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="gp[<?php echo $ii; ?>][SNAPincludeGP]" <?php if (($post->post_status == "publish" && $ntOpt['isPosted'] == '1') || ($post->post_status != "publish" && ((int)$doGP == 1)) ) echo 'checked="checked" title="def"';  ?> /> <?php } ?>
      
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/gp16.png);">Google+ - publish to (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailGP) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToGP_repostButton" id="rePostToGP_button" value="<?php _e('Repost to Google+', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToGP', 'rePostToGP_wpnonce' ); } ?>
                    
                    <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) { 
                        
                        ?> <span id="pstdGP<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
                      <a style="font-size: 10px;" href="https://plus.google.com/<?php echo $pMeta[$ii]['pgID']; ?>" target="_blank">Posted on Google+ <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>                
                
                <?php if (!$isAvailGP) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Google+ Account to AutoPost to Google+</b>
                <?php } elseif ($post->post_status != "puZblish") { ?> 
                
                <tr><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 0px; padding-right:10px;"> Post Type: <br/>
                (<a id="showShAtt" style="font-weight: normal" onmouseout="hidePopShAtt('<?php echo $ii; ?>XG');" onmouseover="showPopShAtt('<?php echo $ii; ?>XG', event);" onclick="return false;" class="underdash" href="http://www.nextscripts.com/blog/">What's the difference?</a>)
                </th><td>     
        
        <input type="radio" name="gp[<?php echo $ii; ?>][postType]" value="T" <?php if ($gpPostType == 'T') echo 'checked="checked"'; ?> /> Text Post  - <i>just text message</i><br/>       
        <input type="radio" name="gp[<?php echo $ii; ?>][postType]" value="I" <?php if ($gpPostType == 'I') echo 'checked="checked"'; ?> /> Post to Google+ as "Image post" - <i>big image with text message</i><br/>             
        <input type="radio" name="gp[<?php echo $ii; ?>][postType]" value="A" <?php if ( !isset($gpPostType) || $gpPostType == '' || $gpPostType == 'A') echo 'checked="checked"'; ?> />Text Post with "attached" blogpost
        <div class="popShAtt" id="popShAtt<?php echo $ii; ?>XG"><h3>Google+ Post Types</h3><img src="<?php echo $nxs_plurl; ?>img/gpPostTypesDiff6.png" width="600" height="285" alt="Google+ Post Types"/></div>
     </td></tr>
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;"><?php _e('Message Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $gpMsgFormat ?>" type="text" name="gp[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apGPMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apGPMsgFrmt".$ii); ?></td></tr>
           <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = ''; 
    if (isset($pMeta['SNAPformat'])) $optMt['gpMsgFormat'] = $pMeta['SNAPformat'];   
    if (isset($pMeta['postType'])) $optMt['postType'] = $pMeta['postType'];
    if (isset($pMeta['SNAPincludeGP'])) $optMt['doGP'] = $pMeta['SNAPincludeGP'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doGP'] = 0; } return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToGP_ajax")) {
  function nxs_rePostToGP_ajax() { check_ajax_referer('rePostToGP');  $postID = $_POST['id']; global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
    foreach ($options['gp'] as $ii=>$two) if ($ii==$_POST['nid']) {   $two['ii'] = $ii; $two['pType'] = 'aj'; //if ($two['gpPageID'].$two['gpUName']==$_POST['nid']) {  
      $gppo =  get_post_meta($postID, 'snapGP', true); $gppo =  maybe_unserialize($gppo);// prr($gppo);
      if (is_array($gppo) && isset($gppo[$ii]) && is_array($gppo[$ii])){ $ntClInst = new nxs_snapClassGP(); $two = $ntClInst->adjMetaOpt($two, $gppo[$ii]); } 
      $result = nxs_doPublishToGP($postID, $two); if ($result == 200) die("Successfully sent your post to Google+."); else die($result);        
    }    
  }
}  
if (!function_exists("nxs_doPublishToGP")) { //## Second Function to Post to G+
  function nxs_doPublishToGP($postID, $options){ $ntCd = 'GP'; $ntCdL = 'gp'; $ntNm = 'Google+'; 
      if(!function_exists('doConnectToGooglePlus2') || !function_exists('doPostToGooglePlus2')) { nxs_addToLog($logNT, 'E', '-=ERROR=- No G+ API Lib Detected', ''); return "No G+ API Lib Detected";}
      $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
      if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        nxs_addToLog($ntCd.' - '.$options['nName'], 'E', '-=Duplicate=- Post ID:'.$postID, 'Not posted. No reason for posting duplicate'); return;
      }        
      if ($postID=='0') echo "Testing ... <br/><br/>";  else { nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1'));  $post = get_post($postID); if(!$post) return;}
      $gpMsgFormat = $options['gpMsgFormat']; $gpPostType = $options['postType'];      
      $msg = nsFormatMessage($gpMsgFormat, $postID);// prr($msg); echo $postID;
      if ($gpPostType=='A') $imgURL = nxs_getPostImage($postID, 'thumbnail');  if ($gpPostType=='I') $imgURL = nxs_getPostImage($postID, 'full');        
      $email = $options['gpUName'];  $pass = substr($options['gpPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['gpPass'], 5)):$options['gpPass'];             
      $extInfo = ' | PostID: '.$postID; $logNT = '<span style="color:#800000">Google+</span> - '.$options['nName'];      
      $loginError = doConnectToGooglePlus2($email, $pass);  if ($loginError!==false) {if ($postID=='0') echo $loginError; nxs_addToLog($logNT, 'E', '-=ERROR=- '.print_r($loginError, true)." - BAD USER/PASS", $extInfo); return "BAD USER/PASS";} 
      $url =  get_permalink($postID); if(trim($url)=='') $url = home_url();  
      if ($gpPostType=='I') $lnk = array(); if ($gpPostType=='A') $lnk = doGetGoogleUrlInfo2($url);  if (is_array($lnk) && $imgURL!='') $lnk['img'] = $imgURL;      //prr($lnk);
      if (!empty($options['gpPageID'])) {  $to = $options['gpPageID']; $ret = doPostToGooglePlus2($msg, $lnk, $to);} 
        elseif (!empty($options['gpCommID'])) $ret = doPostToGooglePlus2($msg, $lnk, '', $options['gpCommID']); else $ret = doPostToGooglePlus2($msg, $lnk); //prr($ret);
      if ($ret=='OK') $ret = array("code"=>"OK", "post_id"=>'');
      if ( (!is_array($ret)) && $ret!='OK') { if ($postID=='0') prr($ret); nxs_addToLog($logNT, 'E', '-=ERROR=- '.print_r($ret, true), $extInfo);} 
        else if ($postID=='0')  { nxs_addToLog($logNT, 'M', 'OK - TEST Message Posted '); echo 'OK - Message Posted, please see your Google+ Page'; } else 
          { nxs_metaMarkAsPosted($postID, 'GP', $options['ii'], array('isPosted'=>'1', 'pgID'=>$ret['post_id'], 'pDate'=>date('Y-m-d H:i:s'))); nxs_addToLog($logNT, 'M', 'OK - Message Posted ', $extInfo); }
      if ($ret['code']=='OK') return 200; else return $ret;
  } 
}  
?>