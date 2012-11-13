<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'FB', 'lcode'=>'fb', 'name'=>'Facebook');

if (!class_exists("nxs_snapClassFB")) { class nxs_snapClassFB {
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl, $nxs_plurl; $code = 'FB'; $lcode = 'fb'; wp_nonce_field( 'ns'.$code, 'ns'.$code.'_wpnonce' ); 
    if ( isset($_GET['code']) && $_GET['code']!='' && ((!isset($_GET['action'])) || $_GET['action']!='gPlusAuth')){  $at = $_GET['code'];  echo "-= This is normal technical authorization info =- <br/><br/><br/>-= Code:".$at;
     //$fbo = array('wfa'=> 1339160000); //foreach ($ntOpts as $two) { if (isset($two['wfa']) && $two['wfa']>$fbo['wfa']) $fbo =  $two; }
     $fbo = $ntOpts[$_GET['acc']]; $wprg = array(); $response = wp_remote_get('https://graph.facebook.com/nextscripts', $wprg); 
     if( is_wp_error( $response) && isset($response->errors['http_request_failed']) && stripos($response->errors['http_request_failed'][0], 'SSL')!==false ) {  prr($response->errors); $wprg['sslverify'] = false; }
     if (isset($fbo['fbPgID'])){ echo "-="; prr($fbo);// die();
      $response  = wp_remote_get('https://graph.facebook.com/oauth/access_token?client_id='.$fbo['fbAppID'].'&redirect_uri='.urlencode($nxs_snapThisPageUrl.'&acc='.$_GET['acc']).'&client_secret='.$fbo['fbAppSec'].'&code='.$at, $wprg); 
      //prr('https://graph.facebook.com/oauth/access_token?client_id='.$fbo['fbAppID'].'&redirect_uri='.urlencode($nxs_snapThisPageUrl).'&client_secret='.$fbo['fbAppSec'].'&code='.$at);
      if ( (is_object($response) && (isset($response->errors))) || (is_array($response) && stripos($response['body'],'"error":')!==false )) { prr($response); die(); }
      parse_str($response['body'], $params);  $at = $params['access_token']; // prr($response); prr($params);
      $response  = wp_remote_get('https://graph.facebook.com/oauth/access_token?client_secret='.$fbo['fbAppSec'].'&client_id='.$fbo['fbAppID'].'&grant_type=fb_exchange_token&fb_exchange_token='.$at, $wprg); 
      if ((is_object($response) && isset($response->errors))) { prr($response); die();}
      parse_str($response['body'], $params); $at = $params['access_token']; $fbo['fbAppAuthToken'] = $at; 
      require_once ('apis/facebook.php'); echo "-= Using API =-<br/>";
      $facebook = new NXS_Facebook(array( 'appId' => $fbo['fbAppID'], 'secret' => $fbo['fbAppSec'], 'cookie' => true)); 
      $facebook -> setAccessToken($fbo['fbAppAuthToken']); $user = $facebook->getUser(); echo "USER:"; prr($user);
      if ($user) {
        try { $page_id = $fbo['fbPgID']; echo "-= Authorizing Page =-";          
          if ( !is_numeric($page_id) && stripos($fbo['fbURL'], '/groups/')!=false) { $fbPgIDR = wp_remote_get('http://www.nextscripts.com/nxs.php?g='.$fbo['fbURL']); 
             $fbPgIDR = trim($fbPgIDR['body']); $page_id = $fbPgIDR!=''?$fbPgIDR:$page_id;
          } $page_info = $facebook->api("/$page_id?fields=access_token"); prr($page_info);
          if( !empty($page_info['access_token']) ) { $fbo['fbAppPageAuthToken'] = $page_info['access_token']; }
        } catch (NXS_FacebookApiException $e) { $errMsg = $e->getMessage(); prr($errMsg);
          if ( stripos($errMsg, 'Unknown fields: access_token')!==false) $fbo['fbAppPageAuthToken'] = $fbo['fbAppAuthToken']; else { echo 'Error:',  $errMsg, "\n"; die(); }
        }
      } else echo "Can't get Facebook User. Please login to Facebook.";                
                                                
      if ($user>0) { $fbo['fbAppAuthUser'] = $user;  $options = get_option('NS_SNAutoPoster');      
        foreach ($options['fb'] as $two) { if ($two['fbPgID']==$fbo['fbPgID']) $fbs[] = $fbo; else $fbs[] = $two; } $options['fb'] = $fbs; // prr($options); die();
         update_option('NS_SNAutoPoster', $options);
        ?><script type="text/javascript">window.location = "<?php echo $nxs_snapThisPageUrl; ?>"</script>      
      <?php } die(); }
    } ?>
    <hr/><div class="nsx_iconedTitle" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $lcode; ?>16.png);">Facebook Settings:   <?php $cfbo = count($ntOpts); $mfbo =  1+max(array_keys($ntOpts)); ?> <?php wp_nonce_field( 'nsFB', 'nsFB_wpnonce' ); ?>
    <div class="nsBigText">You have <?php echo $cfbo=='0'?'No':$cfbo; ?> Facebook account<?php if ($cfbo!=1){ ?>s<?php } ?> <!-- - <a href="#" class="NXSButton" onclick="doShowHideBlocks2('FB<?php echo $mfbo; ?>');return false;">Add new Facebook Account</a> --> </div></div>
    
    <?php // if (function_exists('nxs_doSMAS1')) nxs_doSMAS1($this, $mfbo); else nxs_doSMAS('Facebook', 'FB'.$mfbo); ?>
    
    <?php foreach ($ntOpts as $indx=>$fbo){ $fbo['ii'] = $indx; if (trim($fbo['nName']=='')) $fbo['nName'] = str_ireplace('https://www.facebook.com','', str_ireplace('http://www.facebook.com','', $fbo['fbURL'])); ?>
      <p style="margin: 0px;margin-left: 5px;"><input value="1" id="apDoFB" name="fb[<?php echo $indx; ?>][apDoFB]" type="checkbox" <?php if ((int)$fbo['doFB'] == 1) echo "checked"; ?> /> 
      <strong>Auto-publish your Posts to your Facebook Page or Profile <i style="color: #005800;"><?php if($fbo['nName']!='') echo "(".$fbo['nName'].")"; ?></i> </strong>
      &nbsp;&nbsp;<a id="doFB<?php echo $indx; ?>A" href="#" onclick="doShowHideBlocks2('FB<?php echo $indx; ?>');return false;">[Show Settings]</a>&nbsp;&nbsp;
      <a href="#" onclick="doDelAcct('fb', '<?php echo $indx; ?>', '<?php echo $fbo['fbURL']; ?>');return false;">[Remove Account]</a>
      </p><?php $this->showNTSettings($indx, $fbo);             
    } //## END FB Settings 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($mfbo){ $fbo = array('nName'=>'', 'doFB'=>'1', 'fbURL'=>'', 'fbAppID'=>'','fbPostType'=>'A', 'fbMsgAFormat'=>'', 'fbAppSec'=>'', 'fbAttch'=>'1', 'fbPgID'=>'', 'fbAppAuthUser'=>'', 'fbMsgFormat'=>'New post has been published on %SITENAME%' ); $this->showNTSettings($mfbo, $fbo, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $fbo, $isNew=false){  global $nxs_plurl, $nxs_snapThisPageUrl; if ((int)$fbo['fbAttch']==0 && (!isset($fbo['trPostType']) || $fbo['trPostType']=='')) $fbo['trPostType'] = 'T';  ?>
    <div id="doFB<?php echo $ii; ?>Div" <?php if ($isNew){ ?>class="clNewNTSets"<?php } ?> style="max-width: 1000px; background-color: #EBF4FB; background-image: url(<?php echo $nxs_plurl; ?>img/fb-bg.png);  background-position:90% 10%; background-repeat: no-repeat; margin: 10px; border: 1px solid #808080; padding: 10px; <?php if ((isset($fbo['fbAppAuthUser']) && $fbo['fbAppAuthUser']>1)||$isNew) { ?>display:none;<?php } ?>">   <input type="hidden" name="apDoSFB<?php echo $ii; ?>" value="0" id="apDoSFB<?php echo $ii; ?>" />                                
    <?php if ($isNew) { ?>    <input type="hidden" name="fb[<?php echo $ii; ?>][apDoFB]" value="1" id="apDoNewFB<?php echo $ii; ?>" /> <?php } ?>
    
     <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/fb16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-facebook-social-networks-auto-poster-wordpress/">Detailed Facebook Installation/Configuration Instructions</a></div>
    
    <div style="width:100%;"><strong>Account Nickname:</strong> <i>Just so you can easely identify it</i> </div><input name="fb[<?php echo $ii; ?>][nName]" id="fbnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($fbo['nName'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /><br/>
    <?php echo nxs_addQTranslSel('fb', $ii, $fbo['qTLng']); ?>
    <?php echo nxs_addPostingDelaySel('fb', $ii, $fbo['nHrs'], $fbo['nMin']); ?>
    
    <div style="width:100%;"><strong>Your Facebook URL:</strong> </div>
    <p style="font-size: 11px; margin: 0px;">Could be your Facebook Profile, Facebook Page, Facebook Group</p>
    <input name="fb[<?php echo $ii; ?>][apFBURL]" id="apFBURL" style="width: 50%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($fbo['fbURL'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />                
    <div style="width:100%;"><strong>Your Facebook App ID:</strong> </div><input name="fb[<?php echo $ii; ?>][apFBAppID]" id="apFBAppID" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($fbo['fbAppID'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />  
    <div style="width:100%;"><strong>Your Facebook App Secret:</strong> </div><input name="fb[<?php echo $ii; ?>][apFBAppSec]" id="apFBAppSec" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($fbo['fbAppSec'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /><br/><br/>
    <div id="altFormat">
      <div style="width:100%;"><strong id="altFormatText">Message text Format:</strong> (<a href="#" id="apFBMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apFBMsgFrmt<?php echo $ii; ?>'); return false;">Show format info</a>)</div>
        <input name="fb[<?php echo $ii; ?>][apFBMsgFrmt]" id="apFBMsgFrmt" style="width: 50%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($fbo['fbMsgFormat'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" onfocus="mxs_showFrmtInfo('apFBMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apFBMsgFrmt".$ii); ?><br/>
   </div><br/>
   
      
      <div style="width:100%;"><strong id="altFormatText">Post Type:</strong>&lt;-- (<a id="showShAtt" onmouseout="hidePopShAtt('<?php echo $ii; ?>X');" onmouseover="showPopShAtt('<?php echo $ii; ?>X', event);" onclick="return false;" class="underdash" href="http://www.nextscripts.com/blog/">What's the difference?</a>)  </div>                      
<div style="margin-left: 10px;">
        
        <input type="radio" name="fb[<?php echo $ii; ?>][fbPostType]" value="T" <?php if ($fbo['fbPostType'] == 'T') echo 'checked="checked"'; ?> /> Text Post - <i>just text message</i><br/>                    
        <input type="radio" name="fb[<?php echo $ii; ?>][fbPostType]" value="I" <?php if ($fbo['fbPostType'] == 'I') echo 'checked="checked"'; ?> /> Image Post - <i>big image with text message</i><br/>
        <input type="radio" name="fb[<?php echo $ii; ?>][fbPostType]" value="A" <?php if ( !isset($fbo['fbPostType']) || $fbo['fbPostType'] == '' || $fbo['fbPostType'] == 'A') echo 'checked="checked"'; ?> /> Text Post with "attached" link<br/>

<div style="width:100%; margin-left: 15px;"><strong>Link attachment type:&nbsp;</strong> <input value="2"  id="apFBAttchShare<?php echo $ii; ?>" onchange="doSwitchShAtt(0,<?php echo $ii; ?>);" type="radio" name="fb[<?php echo $ii; ?>][apFBAttch]" <?php if ((int)$fbo['fbAttch'] == 2) echo "checked"; ?> /> 
                Share a link to your blogpost .. or ..                                  
               <input value="1"  id="apFBAttch<?php echo $ii; ?>" onchange="doSwitchShAtt(1,<?php echo $ii; ?>);" type="radio" name="fb[<?php echo $ii; ?>][apFBAttch]"  <?php if ((int)$fbo['fbAttch'] == 1) echo "checked"; ?> /> 
              Attach your blogpost &lt;-- (<a id="showShAtt" onmouseout="hidePopShAtt('<?php echo $ii; ?>');" onmouseover="showPopShAtt('<?php echo $ii; ?>', event);" onclick="return false;" class="underdash" href="http://www.nextscripts.com/blog/">What's the difference?</a>)      
    <div style="margin-bottom: 5px; margin-left: 10px; "><input value="1"  id="apFBAttchAsVid" type="checkbox" name="fb[<?php echo $ii; ?>][apFBAttchAsVid]"  <?php if (isset($fbo['fbAttchAsVid']) && (int)$fbo['fbAttchAsVid'] == 1) echo "checked"; ?> /> 
      <strong>If post has video use it as an attachment thumbnail.</strong> <i>Video will be used for an attachment thumbnail instead of featured image. Only Youtube is supported at this time.</i><br/>
     
    </div>
     <strong>Attachment Text Format:</strong><br/> 
      <input value="1"  id="apFBMsgAFrmtA<?php echo $ii; ?>" <?php if (trim($fbo['fbMsgAFrmt'])=='') echo "checked"; ?> onchange="if (jQuery(this).is(':checked')) { jQuery('#apFBMsgAFrmtDiv<?php echo $ii; ?>').hide(); jQuery('#apFBMsgAFrmt<?php echo $ii; ?>').val(''); }else jQuery('#apFBMsgAFrmtDiv<?php echo $ii; ?>').show();" type="checkbox" name="fb[<?php echo $ii; ?>][apFBMsgAFrmtA]"/> <strong>Auto</strong>
      <i> - Recommended. Info from SEO Plugins will be used, then post excerpt, then post text </i><br/>
      <div id="apFBMsgAFrmtDiv<?php echo $ii; ?>" style="<?php if ($fbo['fbMsgAFrmt']=='') echo "display:none;"; ?>" >&nbsp;&nbsp;&nbsp; Set your own format:<input name="fb[<?php echo $ii; ?>][apFBMsgAFrmt]" id="apFBMsgAFrmt<?php echo $ii; ?>" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($fbo['fbMsgAFrmt'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /><br/></div>
</div><br/>
   </div><br/>
  
<div class="popShAtt" id="popShAtt<?php echo $ii; ?>"><h3>Two ways of attaching post on Facebook</h3><img src="http://cdn.gtln.us/img/nxs/fb2wops.jpg" width="600" height="271" alt="Two ways of attaching post on Facebook"/></div>
<div class="popShAtt" id="popShAtt<?php echo $ii; ?>X"><h3>Facebook Post Types</h3><img src="http://cdn.gtln.us/img/nxs/fbPostTypesDiff6.png" width="600" height="398" alt="Facebook Post Types"/></div>

              
            <?php if ($fbo['fbPgID']!='') {?><div style="width:100%;"><strong>Your Facebook Page ID:</strong> <?php _e(apply_filters('format_to_edit', htmlentities($fbo['fbPgID'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?> </div><?php } ?>
            <?php 
            if($fbo['fbAppSec']=='') { ?>
            <b>Authorize Your Facebook Account</b>. Please click "Update Settings" to be able to Authorize your account.
            <?php } else { if(isset($fbo['fbAppAuthUser']) && $fbo['fbAppAuthUser']>0) { ?>
            Your Facebook Account has been authorized. User ID: <?php _e(apply_filters('format_to_edit', htmlentities($fbo['fbAppAuthUser'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>.
            You can Re- <?php } ?>            
            <a href="https://www.facebook.com/dialog/oauth?client_id=<?php echo $fbo['fbAppID'];?>&client_secret='<?php echo $fbo['fbAppSec'];?>&scope=publish_stream,offline_access,read_stream,manage_pages&redirect_uri=<?php echo urlencode($nxs_snapThisPageUrl.'&acc='.$fbo['ii']);?>">Authorize Your Facebook Account</a> 
            <?php if (!isset($fbo['fbAppAuthUser']) || $fbo['fbAppAuthUser']<1) { ?> <div class="blnkg">&lt;=== Authorize your account ===</div> <?php } ?>
            
            <?php if (!isset($fbo['fbAppAuthUser']) || $fbo['fbAppAuthUser']<1) { ?>
            <br/><br/><i> If you get Facebook message: <b>"Error. An error occurred. Please try again later."</b> or <b>"Error 191"</b> please make sure that domain name in your Facebook App matches your website domain exactly. Please note that for example <b>nextscripts.com</b> and <b style="color:#800000;">www.</b><b>nextscripts.com</b> are different domains.</i> <?php }?>
            <?php } ?>
            
            <?php  if(isset($fbo['fbAppAuthUser']) && $fbo['fbAppAuthUser']>0) { ?>
            <?php wp_nonce_field( 'rePostToFB', 'rePostToFB_wpnonce' ); ?>
            <br/><br/><b>Test your settings:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('FB','<?php echo $ii; ?>'); return false;">Submit Test Post to Facebook</a>         
            <?php }?>
            <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div>
            
          </div>        
        <?php
      
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'FB'; $lcode = 'fb'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apFBAppID']) && $pval['apFBAppID']!='') { if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apDoFB']))         $options[$ii]['doFB'] = $pval['apDoFB']; else $options[$ii]['doFB'] = 0;
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apFBAppID']))      $options[$ii]['fbAppID'] = trim($pval['apFBAppID']);                                
        if (isset($pval['apFBAppSec']))     $options[$ii]['fbAppSec'] = trim($pval['apFBAppSec']);        
        
        if (isset($pval['fbPostType']))     $options[$ii]['fbPostType'] = trim($pval['fbPostType']);        
        if (isset($pval['apFBAttch']))      $options[$ii]['fbAttch'] = $pval['apFBAttch']; else $options[$ii]['fbAttch'] = 0;        
        if (isset($pval['apFBAttchAsVid'])) $options[$ii]['fbAttchAsVid'] = $pval['apFBAttchAsVid']; else $options[$ii]['fbAttchAsVid'] = 0;
        if (isset($pval['apFBMsgFrmt']))    $options[$ii]['fbMsgFormat'] = trim($pval['apFBMsgFrmt']); 
        if (isset($pval['apFBMsgAFrmt']))    $options[$ii]['fbMsgAFrmt'] = trim($pval['apFBMsgAFrmt']); 
        
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
                
        if (isset($pval['apFBURL']))  {  $options[$ii]['fbURL'] = trim($pval['apFBURL']);   if ( substr($options[$ii]['fbURL'], 0, 4)!='http' )  $options[$ii]['fbURL'] = 'http://'.$options[$ii]['fbURL'];
          $fbPgID = $options[$ii]['fbURL']; if (substr($fbPgID, -1)=='/') $fbPgID = substr($fbPgID, 0, -1);  $fbPgID = substr(strrchr($fbPgID, "/"), 1); 
          if (strpos($fbPgID, '?')!==false) $fbPgID = substr($fbPgID, 0, strpos($fbPgID, '?')); 
          $options[$ii]['fbPgID'] = $fbPgID; //echo $fbPgID;
          if (strpos($options[$ii]['fbURL'], '?')!==false) $options[$ii]['fbURL'] = substr($options[$ii]['fbURL'], 0, strpos($options[$ii]['fbURL'], '?'));// prr($pval); prr($options[$ii]); // die();
        }                  
      }
    } return $options;
  } 
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; 
    foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapFB', true));  if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); $doFB = $ntOpt['doFB'];
        $isAvailFB =  $ntOpt['fbURL']!='' && $ntOpt['fbAppID']!='' && $ntOpt['fbAppSec']!=''; $isAttachFB = $ntOpt['fbAttch']; $fbMsgFormat = $ntOpt['fbMsgFormat'];    $fbPostType = $ntOpt['fbPostType'];
      ?>  
      
      <tr><th style="text-align:left;" colspan="2">
      
      <?php if ($isAvailFB) { ?><input class="nxsGrpDoChb" value="1" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="fb[<?php echo $ii; ?>][SNAPincludeFB]" <?php if (($post->post_status == "publish" && $ntOpt['isPosted'] == '1') || ($post->post_status != "publish" && ((int)$doFB == 1)) ) echo 'checked="checked" title="def"';  ?> /> <?php } ?>
      
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/fb16.png);">Facebook - <?php _e('publish to ', 'NS_SPAP'); ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th><td><?php //## Only show RePost button if the post is "published"
    if ($post->post_status == "publish" && $isAvailFB) { ?><input alt="<?php echo $ii; ?>" style="float: right;" type="button" class="button" name="rePostToFB_repostButton" id="rePostToFB_button" value="<?php _e('Repost to Facebook', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToFB', 'rePostToFB_wpnonce' ); } ?>
                </td></tr>
                <?php if (!$isAvailFB) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup and Authorize your Facebook Account to AutoPost to Facebook</b>
                <?php } elseif ($post->post_status != "publish") {?> 
                
                <tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;">
                  
                  <b></b>
                </th>
                <td></td>
                </tr>
                
             <tr><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 0px; padding-right:10px;"> Post Type: </th><td>                
                 

        
        <input type="radio" name="fb[<?php echo $ii; ?>][PostType]" value="T" <?php if ($fbPostType == 'T') echo 'checked="checked"'; ?> /> Text Post  - <i>just text message</i><br/>       
        <input type="radio" name="fb[<?php echo $ii; ?>][PostType]" value="I" <?php if ($fbPostType == 'I') echo 'checked="checked"'; ?> /> Image Post - <i>big image with text message</i><br/>             
        <input type="radio" name="fb[<?php echo $ii; ?>][PostType]" value="A" <?php if ( !isset($fbPostType) || $fbPostType == '' || $fbPostType == 'A') echo 'checked="checked"'; ?> /> Text Post with "attached" link &lt;-- (<a id="showShAtt" onmouseout="hidePopShAtt('<?php echo $ii; ?>');" onmouseover="showPopShAtt('<?php echo $ii; ?>', event);" onclick="return false;" class="underdash" href="http://www.nextscripts.com/blog/">What's the difference?</a>) <br/>

<div style="width:100%; margin-left: 25px;"><strong>Link attachment type:&nbsp;</strong> <input value="2"  id="apFBAttchShare<?php echo $ii; ?>" onchange="doSwitchShAtt(0,<?php echo $ii; ?>);" type="radio" name="fb[<?php echo $ii; ?>][AttachPost]" <?php if ((int)$isAttachFB == 2) echo "checked"; ?> /> 
                Share a link to your blogpost .. or ..                                  
               <input value="1"  id="apFBAttch<?php echo $ii; ?>" onchange="doSwitchShAtt(1,<?php echo $ii; ?>);" type="radio" name="fb[<?php echo $ii; ?>][AttachPost]"  <?php if ((int)$isAttachFB == 1) echo "checked"; ?> /> 
              Attach your blogpost          
</div> 
<div class="popShAtt" id="popShAtt<?php echo $ii; ?>"><h3>Two ways of attaching post on Facebook</h3> <img src="http://cdn.gtln.us/img/nxs/fb2wops.jpg" width="600" height="271" alt="Two ways of attaching post on Facebook"/></div>
<div class="popShAtt" id="popShAtt<?php echo $ii; ?>X"><h3>Facebook Post Types</h3><img src="http://cdn.gtln.us/img/nxs/fbPostTypesDiff6.png" width="600" height="398" alt="Facebook Post Types"/></div>
     </td></tr>
                
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;"><?php _e('Message Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $fbMsgFormat ?>" type="text" name="fb[<?php echo $ii; ?>][SNAPformat]" size="60px" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apFBTMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apFBTMsgFrmt".$ii); ?></td></tr>
                <?php } 
    }
      
  }
  
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['fbMsgFormat'] = $pMeta['SNAPformat'];    
     if (isset($pMeta['AttachPost'])) $optMt['fbAttch'] = ($pMeta['AttachPost'] != '')?$pMeta['AttachPost']:0; else { if (isset($pMeta['SNAPformat'])) $optMt['fbAttch'] = 0; } 
     if (isset($pMeta['PostType'])) $optMt['fbPostType'] = ($pMeta['PostType'] != '')?$pMeta['PostType']:0; else { if (isset($pMeta['SNAPformat'])) $optMt['fbPostType'] = 'T'; } 
     if (isset($pMeta['SNAPincludeFB'])) $optMt['doFB'] = $pMeta['SNAPincludeFB'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doFB'] = 0; } 
     return $optMt;
  }
}}

if (!function_exists("nxs_rePostToFB_ajax")) { function nxs_rePostToFB_ajax() { check_ajax_referer('rePostToFB');  $postID = $_POST['id']; // $result = nsPublishTo($id, 'FB', true);   
    $options = get_option('NS_SNAutoPoster');  foreach ($options['fb'] as $ii=>$fbo) if ($ii==$_POST['nid']) {  $fbo['ii'] = $ii; $fbo['pType'] = 'aj';
      $fbpo =  get_post_meta($postID, 'snapFB', true); /* echo $postID."|"; echo $fbpo; */ $fbpo =  maybe_unserialize($fbpo); // prr($fbpo); 
      if (is_array($fbpo) && isset($fbpo[$ii]) && is_array($fbpo[$ii]) ){ $ntClInst = new nxs_snapClassFB(); $fbo = $ntClInst->adjMetaOpt($fbo, $fbpo[$ii]); } //prr($fbo);
      $result = nxs_doPublishToFB($postID, $fbo); if ($result == 200) die("Successfully sent your post to FaceBook."); else die($result);
    }    
  }
}

if (!function_exists("nxs_doPublishToFB")) { //## Second Function to Post to FB
  function nxs_doPublishToFB($postID, $options){ global $ShownAds; $ntCd = 'FB'; $ntCdL = 'fb'; $ntNm = 'Facebook'; $dsc = ''; require_once ('apis/facebook.php'); 
    $fbWhere = 'feed'; $page_id = $options['fbPgID']; if (isset($ShownAds)) $ShownAdsL = $ShownAds;  
     
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        nxs_addToLog($ntCd.' - '.$options['nName'], 'E', '-=Duplicate=- Post ID:'.$postID, 'Not posted. No reason for posting duplicate'); return;
    }     
  
    if (isset($options['qTLng'])) $lng = $options['qTLng']; else $lng = '';  
    $facebook = new NXS_Facebook(array( 'appId' => $options['fbAppID'], 'secret' => $options['fbAppSec'], 'cookie' => true )); if (!isset($options['fbAppPageAuthToken'])) $options['fbAppPageAuthToken'] = '';
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url();    
    if ($postID=='0') { echo "Testing ... <br/><br/>"; 
    $mssg = array('access_token'  => $options['fbAppPageAuthToken'], 'message' => 'Test Post', 'name' => 'Test Post', 'caption' => 'Test Post', 'link' => home_url(),
       'description' => 'test Post', 'actions' => array(array('name' => $blogTitle, 'link' => home_url())) ); 
    } else { $post = get_post($postID); if(!$post) return; $fbMsgFormat = $options['fbMsgFormat']; $msg = nsFormatMessage($fbMsgFormat, $postID); $fbMsgAFormat = $options['fbMsgAFrmt'];
      $isAttachFB = $options['fbAttch']; $fbPostType = $options['fbPostType']; $isAttachVidFB = $options['fbAttchAsVid'];
      nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); 
      if (($isAttachFB=='1' || $isAttachFB=='2' || $fbPostType=='A' || $fbPostType=='I')) $imgURL = nxs_getPostImage($postID); // prr($options); echo "PP - ".$postID; prr($src);      
      
      if (trim($fbMsgAFormat)!='') {$dsc = nsFormatMessage($fbMsgAFormat, $postID);} else { if (function_exists('aioseop_mrt_fix_meta') && $dsc=='')  $dsc = trim(get_post_meta($postID, '_aioseop_description', true)); 
        if (function_exists('wpseo_admin_init') && $dsc=='') $dsc = trim(get_post_meta($postID, '_yoast_wpseo_opengraph-description', true));  
        if (function_exists('wpseo_admin_init') && $dsc=='') $dsc = trim(get_post_meta($postID, '_yoast_wpseo_metadesc', true));      
        if ($dsc=='') $dsc = trim(apply_filters('the_content', nxs_doQTrans($post->post_excerpt, $lng)));  if ($dsc=='') $dsc = trim(nxs_doQTrans($post->post_excerpt, $lng)); 
        if ($dsc=='') $dsc = trim(apply_filters('the_content', nxs_doQTrans($post->post_content, $lng)));  if ($dsc=='') $dsc = trim(nxs_doQTrans($post->post_content, $lng));  
        if ($dsc=='') $dsc = get_bloginfo('description'); 
      }
      
      $dsc = strip_tags($dsc); $dsc = nxs_decodeEntitiesFull($dsc); $dsc = nsTrnc($dsc, 900, ' ');
      $postSubtitle = home_url();  $msg = strip_tags($msg);  $msg = nxs_decodeEntitiesFull($msg);  $mssg = array('access_token'  => $options['fbAppPageAuthToken'], 'message' => $msg);
      if ($fbPostType=='A' || $fbPostType=='') {
        if (($isAttachFB=='1' || $isAttachFB=='2')) { $attArr = array('name' => nxs_doQTrans($post->post_title, $lng), 'caption' => $postSubtitle, 'link' => get_permalink($postID), 'description' => $dsc); $mssg = array_merge($mssg, $attArr); }      
        if ($isAttachFB=='1') $mssg['actions'] = array(array('name' => $blogTitle, 'link' => home_url()));        
        if (trim($imgURL)!='') $mssg['picture'] = $imgURL;
        if ($isAttachVidFB=='1') {$vids = nsFindVidsInPost($post); if (count($vids)>0) { $mssg['source'] = 'http://www.youtube.com/v/'.$vids[0]; $mssg['picture'] = 'http://img.youtube.com/vi/'.$vids[0].'/0.jpg'; }}      
      } elseif ($fbPostType=='I') { $facebook->setFileUploadSupport(true); $fbWhere = 'photos'; $mssg['url'] = $imgURL; }
    } //  prr($mssg); // prr($options);  //   prr($facebook); echo "/$page_id/feed";
    if (isset($ShownAds)) $ShownAds = $ShownAdsL; // FIX for the quick-adsense plugin
    $extInfo = ' | PostID: '.$postID." - ".nxs_doQTrans($post->post_title, $lng); $logNT = '<span style="color:#0000FF">Facebook</span> - '.$options['nName']; //prr($mssg);
    
    try { $ret = $facebook->api("/$page_id/".$fbWhere,"post", $mssg);} catch (NXS_FacebookApiException $e) { nxs_addToLog($logNT, 'E', '-=ERROR=- '.$e->getMessage(), $extInfo);
      if (stripos($e->getMessage(),'This API call requires a valid app_id')!==false) { $page_id = $options['fbPgID'];
        if ( !is_numeric($page_id) && stripos($options['fbURL'], '/groups/')!=false) { $fbPgIDR = wp_remote_get('http://www.nextscripts.com/nxs.php?g='.$fbo['fbURL']); 
          $fbPgIDR = trim($fbPgIDR['body']); $page_id = $fbPgIDR!=''?$fbPgIDR:$page_id;
        } $page_info = $facebook->api("/$page_id?fields=access_token"); 
        if( !empty($page_info['access_token']) ) { $options['fbAppPageAuthToken'] = $page_info['access_token']; 
          nxs_addToLog($logNT, 'M', 'Personal Auth used instead of Page. Please re-authorize Facebook.');  $ret = $facebook->api("/$page_id/".$fbWhere,"post", $mssg); 
        } else { nxs_addToLog($logNT, 'E', '-=ERROR=- '.$e->getMessage(), $extInfo); return "ERROR:".$e->getMessage();}
      }        
      if ($postID=='0') echo 'Error:',  $e->getMessage(), "\n";  return "ERROR:".$e->getMessage();      
    }   
    if ($postID=='0') { prr($ret); if (isset($ret['id']) && $ret['id']!='') { echo 'OK - Message Posted, please see your Facebook Page '; nxs_addToLog($logNT, 'M', 'Test Message Posted, please see your Facebook Page'); }}
      else { if (isset($ret['id']) && $ret['id']!='') { nxs_metaMarkAsPosted($postID, 'FB', $options['ii'],  array('isPosted'=>'1', 'pgID'=>$ret['id']) ); nxs_addToLog($logNT, 'M', 'OK - Message Posted'.print_r($ret, true), $extInfo); }
        else nxs_addToLog($logNT, 'E', '-=ERROR=- '.print_r($ret, true), $extInfo); 
      }
  }
}

?>