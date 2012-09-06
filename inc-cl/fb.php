<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'FB', 'lcode'=>'fb', 'name'=>'Facebook');

if (!class_exists("nxs_snapClassFB")) { class nxs_snapClassFB {
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl; $code = 'FB'; $lcode = 'fb'; wp_nonce_field( 'ns'.$code, 'ns'.$code.'_wpnonce' ); 
    if ( isset($_GET['code']) && $_GET['code']!='' && ((!isset($_GET['action'])) || $_GET['action']!='gPlusAuth')){  $at = $_GET['code'];  echo "Code:".$at; $fbo = array('wfa'=> 1339160000);  
      foreach ($ntOpts as $two) { if (isset($two['wfa']) && $two['wfa']>$fbo['wfa']) $fbo =  $two; }
      if (isset($fbo['fbPgID'])){ echo "-="; prr($fbo);// die();
      $response  = wp_remote_get('https://graph.facebook.com/oauth/access_token?client_id='.$fbo['fbAppID'].'&redirect_uri='.urlencode($nxs_snapThisPageUrl).'&client_secret='.$fbo['fbAppSec'].'&code='.$at); 
      //prr('https://graph.facebook.com/oauth/access_token?client_id='.$fbo['fbAppID'].'&redirect_uri='.urlencode($nxs_snapThisPageUrl).'&client_secret='.$fbo['fbAppSec'].'&code='.$at);
      if ((is_object($response) && isset($response->errors))) { prr($response); die(); }
      parse_str($response['body'], $params);  $at = $params['access_token']; // prr($response); prr($params);
      $response  = wp_remote_get('https://graph.facebook.com/oauth/access_token?client_secret='.$fbo['fbAppSec'].'&client_id='.$fbo['fbAppID'].'&grant_type=fb_exchange_token&fb_exchange_token='.$at); 
      if ((is_object($response) && isset($response->errors))) { prr($response); die();}
      parse_str($response['body'], $params); $at = $params['access_token']; $fbo['fbAppAuthToken'] = $at; 
      require_once ('apis/facebook.php'); echo "-= Using API =-<br/>";
      $facebook = new NXS_Facebook(array( 'appId' => $fbo['fbAppID'], 'secret' => $fbo['fbAppSec'], 'cookie' => true)); 
      $facebook -> setAccessToken($fbo['fbAppAuthToken']); $user = $facebook->getUser(); echo "USER:"; prr($user);
      if ($user) {
        try { $page_id = $fbo['fbPgID']; echo "XE";  $page_info = $facebook->api("/$page_id?fields=access_token"); prr($page_info);
          if( !empty($page_info['access_token']) ) { $fbo['fbAppPageAuthToken'] = $page_info['access_token']; }
        } catch (NXS_FacebookApiException $e) { $errMsg = $e->getMessage(); prr($errMsg);
          if ( stripos($errMsg, 'Unknown fields: access_token')!==false) $fbo['fbAppPageAuthToken'] = $fbo['fbAppAuthToken']; else { echo 'Error:',  $errMsg, "\n"; die(); }
        }
      } else echo "Please login to Facebook";                
                                                
      if ($user>0) $fbo['fbAppAuthUser'] = $user;  $options = get_option('NS_SNAutoPoster');      
      foreach ($options['fb'] as $two) { if ($two['fbPgID']==$fbo['fbPgID']) $fbs[] = $fbo; else $fbs[] = $two; } $options['fb'] = $fbs;
      update_option('NS_SNAutoPoster', $options);
      ?><script type="text/javascript">window.location = "<?php echo site_url(); ?>/wp-admin/options-general.php?page=NextScripts_SNAP.php"</script>
      <?php die(); }
    } ?>
    <hr/><div style="font-size: 17px;font-weight: bold; margin-bottom: 15px;">Facebook Settings:   <?php $cfbo = count($ntOpts); $mfbo =  1+max(array_keys($ntOpts)); ?> <?php wp_nonce_field( 'nsFB', 'nsFB_wpnonce' ); ?>
    <div class="nsBigText">You have <?php echo $cfbo=='0'?'No':$cfbo; ?> Facebook account<?php if ($cfbo!=1){ ?>s<?php } ?> <!-- - <a href="#" class="NXSButton" onclick="doShowHideBlocks2('FB<?php echo $mfbo; ?>');return false;">Add new Facebook Account</a> --> </div></div>
    
    <?php // if (function_exists('nxs_doSMAS1')) nxs_doSMAS1($this, $mfbo); else nxs_doSMAS('Facebook', 'FB'.$mfbo); ?>
    
    <?php foreach ($ntOpts as $indx=>$fbo){ ?>
      <p style="margin: 0px;margin-left: 5px;"><input value="1" id="apDoFB" name="fb[<?php echo $indx; ?>][apDoFB]" type="checkbox" <?php if ((int)$fbo['doFB'] == 1) echo "checked"; ?> /> 
      <strong>Auto-publish your Posts to your <?php if($fbo['fbURL']!='') echo "(".str_ireplace('https://www.facebook.com','', str_ireplace('http://www.facebook.com','', $fbo['fbURL'])).")"; ?> Facebook Page or Profile</strong>
      <a id="doFB<?php echo $indx; ?>A" href="#" onclick="doShowHideBlocks2('FB<?php echo $indx; ?>');return false;">[Show Settings]</a>&nbsp;&nbsp;
      <a href="#" onclick="doDelAcct('fb', '<?php echo $indx; ?>', '<?php echo $fbo['fbURL']; ?>');return false;">[Remove Account]</a>
      </p><?php $this->showNTSettings($indx, $fbo);             
    } //## END FB Settings 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($mfbo){ $fbo = array('doFB'=>'1', 'fbURL'=>'', 'fbAppID'=>'', 'fbAppSec'=>'', 'fbAttch'=>'1', 'fbPgID'=>'', 'fbAppAuthUser'=>'', 'fbMsgFormat'=>'New post has been published on %SITENAME%' ); $this->showNTSettings($mfbo, $fbo, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $fbo, $isNew=false){  ?>
    <div id="doFB<?php echo $ii; ?>Div" <?php if ($isNew){ ?>class="clNewNTSets"<?php } ?> style="margin-left: 10px; <?php if ((isset($fbo['fbAppAuthUser']) && $fbo['fbAppAuthUser']>1)||$isNew) { ?>display:none;<?php } ?>">   <input type="hidden" name="apDoSFB<?php echo $ii; ?>" value="0" id="apDoSFB<?php echo $ii; ?>" />     <br/>                                
    <?php if ($isNew) { ?> <input type="hidden" name="fb[<?php echo $ii; ?>][apDoFB]" value="1" id="apDoNewFB<?php echo $ii; ?>" /> <?php } ?>
    <div style="width:100%;"><strong>Your Facebook URL:</strong> </div>
    <p style="font-size: 11px; margin: 0px;">Could be your Facebook Profile, Facebook Page, Facebook Group</p>
    <input name="fb[<?php echo $ii; ?>][apFBURL]" id="apFBURL" style="width: 50%;" value="<?php _e(apply_filters('format_to_edit',$fbo['fbURL']), 'NS_SNAutoPoster') ?>" />                
    <div style="width:100%;"><strong>Your Facebook App ID:</strong> </div><input name="fb[<?php echo $ii; ?>][apFBAppID]" id="apFBAppID" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$fbo['fbAppID']), 'NS_SNAutoPoster') ?>" />  
    <div style="width:100%;"><strong>Your Facebook App Secret:</strong> </div><input name="fb[<?php echo $ii; ?>][apFBAppSec]" id="apFBAppSec" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$fbo['fbAppSec']), 'NS_SNAutoPoster') ?>" /><br/><br/>
    
    
    <div id="altFormat">
              <div style="width:100%;"><strong id="altFormatText">Message text Format:</strong> 
              <p style="font-size: 11px; margin: 0px;">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. &nbsp; %URL% - Inserts the URL of your post. &nbsp;  %IMG% - Inserts the featured image. &nbsp;  %IMG% - Inserts the featured image. &nbsp;  %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name.</p>
              <input name="fb[<?php echo $ii; ?>][apFBMsgFrmt]" id="apFBMsgFrmt" style="width: 50%;" value="<?php _e(apply_filters('format_to_edit',$fbo['fbMsgFormat']), 'NS_SNAutoPoster') ?>" /><br/>
              
              </div>
            </div>
            
            <br/>
  <p style="margin: 0px;"><input value="2"  id="apFBAttchShare<?php echo $ii; ?>" onchange="doSwitchShAtt(0,<?php echo $ii; ?>);" type="checkbox" name="fb[<?php echo $ii; ?>][apFBAttch]" <?php if ((int)$fbo['fbAttch'] == 2) echo "checked"; ?> /> 
              <strong>Share a link to your blogpost</strong>  .. or ..                                  
               <input value="1"  id="apFBAttch<?php echo $ii; ?>" onchange="doSwitchShAtt(1,<?php echo $ii; ?>);" type="checkbox" name="fb[<?php echo $ii; ?>][apFBAttch]"  <?php if ((int)$fbo['fbAttch'] == 1) echo "checked"; ?> /> 
              <strong>Attach your blogpost</strong> &lt;-- (<a id="showShAtt" onmouseout="hidePopShAtt();" onmouseover="showPopShAtt();" onclick="return false;" class="underdash" href="http://www.nextscripts.com/blog/">What's the difference?</a>)       
                                       
            </p>
      <div id="popShAtt">
        <h3>Two ways of attaching post on Facebook</h3>
        <p>
          &nbsp;
        </p>  <img src="http://cdn.gtln.us/img/nxs/fb2wops.jpg" width="600" height="271" alt="Two ways of attaching post on Facebook"/>
      </div>
      
      
    
  
    <p style="margin: 10px; "><input value="1"  id="apFBAttchAsVid" type="checkbox" name="fb[<?php echo $ii; ?>][apFBAttchAsVid]"  <?php if (isset($fbo['fbAttchAsVid']) && (int)$fbo['fbAttchAsVid'] == 1) echo "checked"; ?> /> 
      <strong>If post has video use it for attachment.</strong> If post has video (youtube only supported at this time) this video will be used for attachment instead of featured image.
    </p>
            
    
              
            <?php if ($fbo['fbPgID']!='') {?><div style="width:100%;"><strong>Your Facebook Page ID:</strong> <?php _e(apply_filters('format_to_edit',$fbo['fbPgID']), 'NS_SNAutoPoster') ?> </div><?php } ?>
            <?php 
            if($fbo['fbAppSec']=='') { ?>
            <b>Authorize Your Facebook Account</b>. Please click "Update Settings" to be able to Authorize your account.
            <?php } else { if(isset($fbo['fbAppAuthUser']) && $fbo['fbAppAuthUser']>0) { ?>
            Your Facebook Account has been authorized. User ID: <?php _e(apply_filters('format_to_edit',$fbo['fbAppAuthUser']), 'NS_SNAutoPoster') ?>.
            You can Re- <?php } ?>            
            <a onclick="seFBA('<?php echo $fbo['fbPgID'];?>', '<?php echo $fbo['fbAppID'];?>', '<?php echo $fbo['fbAppSec'];?>'); return false;" href="#">Authorize Your Facebook Account</a> 
            <?php if (!isset($fbo['fbAppAuthUser']) || $fbo['fbAppAuthUser']<1) { ?> <div class="blnkg">&lt;=== Authorize your account ===</div> <?php } ?>
            
            <?php if (!isset($fbo['fbAppAuthUser']) || $fbo['fbAppAuthUser']<1) { ?>
            <br/><br/><i> If you get Facebook message : <b>"Error. An error occurred. Please try again later."</b> or <b>"Error 191"</b> please make sure that domain name in your Facebook App matches your website domain exactly. Please note that for example <b>nextscripts.com</b> and <b style="color:#800000;">www.</b><b>nextscripts.com</b> are different domains.</i> <?php }?>
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
        if (isset($pval['apFBAppID']))      $options[$ii]['fbAppID'] = $pval['apFBAppID'];                                
        if (isset($pval['apFBAppSec']))     $options[$ii]['fbAppSec'] = $pval['apFBAppSec'];        
        if (isset($pval['apFBAttch']))      $options[$ii]['fbAttch'] = $pval['apFBAttch']; else $options[$ii]['apFBAttch'] = 0;        
        if (isset($pval['apFBAttchAsVid'])) $options[$ii]['fbAttchAsVid'] = $pval['apFBAttchAsVid']; else $options[$ii]['fbAttchAsVid'] = 0;
        if (isset($pval['apFBMsgFrmt']))    $options[$ii]['fbMsgFormat'] = $pval['apFBMsgFrmt']; 
                
        if (isset($pval['apFBURL']))  {  $options[$ii]['fbURL'] = $pval['apFBURL'];   
          $fbPgID = $options[$ii]['fbURL']; if (substr($fbPgID, -1)=='/') $fbPgID = substr($fbPgID, 0, -1);  $fbPgID = substr(strrchr($fbPgID, "/"), 1); 
          if (strpos($fbPgID, '?')!==false) $fbPgID = substr($fbPgID, 0, strpos($fbPgID, '?')); 
          $options[$ii]['fbPgID'] = $fbPgID; //echo $fbPgID;
           if (strpos($options[$ii]['fbURL'], '?')!==false) $options[$ii]['fbURL'] = substr($options[$ii]['fbURL'], 0, strpos($options[$ii]['fbURL'], '?')); //prr($options[$ii]); die();
        }                  
      }
    } return $options;
  } 
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ $post_id = $post->ID; 
    foreach($ntOpts as $ii=>$ntOpt)  {$doFB = $ntOpt['doFB'];$isAvailFB =  $ntOpt['fbURL']!='' && $ntOpt['fbAppID']!='' && $ntOpt['fbAppSec']!='';  
        $t = get_post_meta($post_id, 'SNAP_AttachFB', true);  $isAttachFB = $t!=''?$t:$ntOpt['fbAttch'];
        $t = get_post_meta($post_id, 'SNAP_FormatFB', true);  $fbMsgFormat = $t!=''?$t:$ntOpt['fbMsgFormat'];    
      ?>  
      
      <tr><th style="text-align:left;" colspan="2">Facebook AutoPoster (<i style="color: #005800;"><?php echo str_ireplace('https://www.facebook.com','', str_ireplace('http://www.facebook.com','', $ntOpt['fbURL'])); ?></i>)</th><td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailFB) { ?><input alt="<?php echo $ii; ?>" style="float: right;" type="button" class="button" name="rePostToFB_repostButton" id="rePostToFB_button" value="<?php _e('Repost to Facebook', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToFB', 'rePostToFB_wpnonce' ); } ?>
                </td></tr>
                <?php if (!$isAvailFB) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup and Authorize your Facebook Account to AutoPost to Facebook</b>
                <?php } elseif ($post->post_status != "publish") {?> 
                
                <tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"><input class="nxsGrpDoChb" value="1" type="checkbox" name="fb[<?php echo $ii; ?>][SNAPincludeFB]" <?php if ((int)$doFB == 1) echo "checked"; ?> /></th>
                <td><b><?php _e('Publish this Post to Facebook', 'NS_SPAP'); ?></b></td>
                </tr>
                
                <tr><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">
                
                <input value="2"  id="apFBAttchShare<?php echo $ii; ?>" onchange="doSwitchShAtt(0,<?php echo $ii; ?>);" type="checkbox" name="fb[<?php echo $ii; ?>][AttachPost]" <?php if ((int)$isAttachFB == 2) echo "checked"; ?> /> </th><td>
              <strong>Share a link to your blogpost</strong>
                
                  .. or ..                                  
               <input value="1"  id="apFBAttch<?php echo $ii; ?>" onchange="doSwitchShAtt(1,<?php echo $ii; ?>);" type="checkbox" name="fb[<?php echo $ii; ?>][AttachPost]"  <?php if ((int)$isAttachFB == 1) echo "checked"; ?> /> <strong>Attach your blogpost</strong>&lt;-- (<a id="showShAtt" onmouseout="hidePopShAtt();" onmouseover="showPopShAtt();" onclick="return false;" class="underdash" href="#">What's the difference?</a>) 
                
                <div id="popShAtt">
        <h3>Two ways of attaching post on Facebook</h3>
        <p>
          &nbsp;
        </p>  <img src="http://cdn.gtln.us/img/nxs/fb2wops.jpg" width="600" height="271" alt="Two ways of attaching post on Facebook"/>
      </div>
                
                </td>                </tr>
                
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:80px; padding-right:10px;"><?php _e('Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $fbMsgFormat ?>" type="text" name="fb[<?php echo $ii; ?>][SNAPformat]" size="60px"/></td></tr>
                
                <tr id="altFormat2" style=""><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">Format Options:</th>
                <td style="vertical-align:top; font-size: 9px;" colspan="2">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. <br/> %URL% - Inserts the URL of your post. &nbsp; %IMG% - Inserts the featured image. &nbsp;  %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name.</td></tr>
                <?php } 
    }
      
  }
  
  function adjMetaOpt($optMt, $pMeta){
     $optMt['fbMsgFormat'] = $pMeta['SNAPformat']; $optMt['fbAttch'] = $pMeta['AttachPost'] == 1?1:0; $optMt['doFB'] = $pMeta['SNAPincludeFB'] == 1?1:0; return $optMt;
  }
}}

if (!function_exists("nxs_rePostToFB_ajax")) { function nxs_rePostToFB_ajax() {  check_ajax_referer('rePostToFB');  $postID = $_POST['id']; // $result = nsPublishTo($id, 'FB', true);   
    $options = get_option('NS_SNAutoPoster');  foreach ($options['fb'] as $ii=>$fbo) if ($ii==$_POST['nid']) {  
      $fbpo =  get_post_meta($postID, 'snapFB', true); $fbpo =  maybe_unserialize($fbpo); 
      if (is_array($fbpo) && isset($fbpo[$ii]) && is_array($fbpo[$ii]) ){ $fbo['fbMsgFormat'] = $fbpo[$ii]['SNAPformat']; $fbo['fbAttch'] = $fbpo[$ii]['AttachPost'] == 1?1:0; } 
      $result = nxs_doPublishToFB($postID, $fbo); if ($result == 200) die("Successfully sent your post to FaceBook."); else die($result);
    }    
  }
}

if (!function_exists("nxs_doPublishToFB")) { //## Second Function to Post to FB
  function nxs_doPublishToFB($postID, $options){ global $ShownAds; require_once ('apis/facebook.php'); $page_id = $options['fbPgID'];  $isPost = isset($_POST["SNAPEdit"]); if (isset($ShownAds)) $ShownAdsL = $ShownAds;
    $facebook = new NXS_Facebook(array( 'appId' => $options['fbAppID'], 'secret' => $options['fbAppSec'], 'cookie' => true ));  
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); 
    
    if ($postID=='0') {echo "Testing ... <br/><br/>"; 
    $mssg = array('access_token'  => $options['fbAppPageAuthToken'], 'message' => 'Test Post', 'name' => 'Test Post', 'caption' => 'Test Post', 'link' => home_url(),
       'description' => 'test Post', 'actions' => array(array('name' => $blogTitle, 'link' => home_url())) );  
    } else {$post = get_post($postID); 
      if ($isPost) $fbMsgFormat = $_POST['SNAP_FormatFB']; else { $t = get_post_meta($postID, 'SNAP_FormatFB', true);  $fbMsgFormat = $t!=''?$t:$options['fbMsgFormat'];}
      if ($isPost) $isAttachFB = $_POST['SNAP_AttachFB'];  else { $t = get_post_meta($postID, 'SNAP_AttachFB', true);  $isAttachFB = $t!=''?$t:$options['fbAttch'];}
      $isAttachVidFB = $t!=''?$t:$options['fbAttchAsVid'];
      $msg = nsFormatMessage($fbMsgFormat, $postID);
      if ($isAttachFB=='1' && function_exists("get_post_thumbnail_id") ){ $src = wp_get_attachment_image_src(get_post_thumbnail_id($postID), 'medium'); $src = $src[0];} 
      $dsc = trim(apply_filters('the_content', $post->post_excerpt)); if ($dsc=='') $dsc = apply_filters('the_content', $post->post_content); $dsc = nsTrnc($dsc, 900, ' ');
      $postSubtitle = home_url(); $dsc = strip_tags($dsc);  $msg = strip_tags($msg);  $msg = nxs_decodeEntitiesFull($msg);  $dsc = nxs_decodeEntitiesFull($dsc);
      $mssg = array('access_token'  => $options['fbAppPageAuthToken'], 'message' => $msg, 'name' => $post->post_title, 'caption' => $postSubtitle, 'link' => get_permalink($postID),
       'description' => $dsc, 'actions' => array(array('name' => $blogTitle, 'link' => home_url())) );  
      if (trim($src)!='') $mssg['picture'] = $src;
      if ($isAttachVidFB=='1') {$vids = nsFindVidsInPost($post); if (count($vids)>0) { $mssg['source'] = 'http://www.youtube.com/v/'.$vids[0]; $mssg['picture'] = 'http://img.youtube.com/vi/'.$vids[0].'/0.jpg'; }}      
    }  //  prr($mssg);
    
    if (isset($ShownAds)) $ShownAds = $ShownAdsL; // FIX for the quick-adsense plugin
    try { $ret = $facebook->api("/$page_id/feed","post", $mssg);} catch (NXS_FacebookApiException $e) { echo 'Error:',  $e->getMessage(), "\n";}    
    if ($postID=='0') { prr($ret); echo 'OK - Message Posted, please see your Facebook Page ';}
  }
}

?>