<?php    
//## NextScripts Twitter Connection Class
$nxs_snapAvNts[] = array('code'=>'TW', 'lcode'=>'tw', 'name'=>'Twitter');

if (!class_exists("nxs_snapClassTW")) { class nxs_snapClassTW {
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl, $nxs_plurl; $code = 'TW'; $lcode = 'tw'; wp_nonce_field( 'ns'.$code, 'ns'.$code.'_wpnonce' ); ?>
    <hr/><div class="nsx_iconedTitle" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $lcode; ?>16.png);">Twitter Settings:     <?php $ctwo = count($ntOpts); $mtwo = 1+max(array_keys($ntOpts)); ?>        
    <div class="nsBigText">You have <?php echo $ctwo=='0'?'No':$ctwo; ?> Twitter account<?php if ($ctwo!=1){ ?>s<?php } ?> <!-- set - <a href="#" class="NXSButton" onclick="doShowHideBlocks2('TW<?php echo $mtwo; ?>');return false;">Add new Twitter Account</a> --></div><br/></div>
    <?php // if (function_exists('nxs_doSMAS1')) nxs_doSMAS1($this, $mtwo); else nxs_doSMAS('Twitter', 'TW'.$mtwo);  ?>            
    <?php foreach ($ntOpts as $indx=>$two){ if (trim($two['nName']=='')) $two['nName'] = str_ireplace('https://','', str_ireplace('http://','', $two['twURL'])); ?>            
      <p style="margin: 0px;margin-left: 5px;"><input value="1" id="apDoTW" name="tw[<?php echo $indx; ?>][apDoTW]" type="checkbox" <?php if ((int)$two['doTW'] == 1) echo "checked"; ?> /> 
        <strong>Auto-publish your Posts to your Twitter <i style="color: #005800;"><?php if($two['nName']!='') echo "(".$two['nName'].")"; ?></i></strong>                                 
        &nbsp;&nbsp;<a id="doTW<?php echo $indx; ?>A" href="#" onclick="doShowHideBlocks2('TW<?php echo $indx; ?>');return false;">[Show Settings]</a>&nbsp;&nbsp;
        <a href="#" onclick="doDelAcct('tw','<?php echo $indx; ?>', '<?php echo $two['twURL']; ?>');return false;">[Remove Account]</a>
      </p><?php $this->showNTSettings($indx, $two);             
    } //## END TW Settings 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($mtwo){ $two = array('nName'=>'', 'doTW'=>'1', 'twURL'=>'', 'twConsKey'=>'',  'twConsSec'=>'', 'twAccToken'=>'', 'twAccTokenSec'=>'', 'attchImg'=>0, 'twAttch'=>'', 'twAccTokenSec'=>''); $this->showNTSettings($mtwo, $two, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $two, $isNew=false){ global $nxs_plurl; ?>
    <div id="doTW<?php echo $ii; ?>Div" <?php if ($isNew){ ?>class="clNewNTSets"<?php } ?> style="max-width: 1000px; background-color: #EBF4FB; background-image: url(<?php echo $nxs_plurl; ?>img/tw-bg.png);  background-position:90% 10%; background-repeat: no-repeat; margin: 10px; border: 1px solid #808080; padding: 10px; <?php if ((isset($two['twOK']) && $two['twOK']=='1')||$isNew) { ?>display:none;<?php } ?>">   <input type="hidden" name="apDoSTW<?php echo $ii; ?>" value="0" id="apDoSTW<?php echo $ii; ?>" />      
    
     <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/tw16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-twitter-social-networks-auto-poster-wordpress/">Detailed Twitter Installation/Configuration Instructions</a></div>
    
    <div style="width:100%;"><strong>Account Nickname:</strong> <i>Just so you can easely identify it</i> </div><input name="tw[<?php echo $ii; ?>][nName]" id="twnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($two['nName'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /><br/>
    <?php echo nxs_addQTranslSel('tw', $ii, $two['qTLng']); ?><?php echo nxs_addPostingDelaySel('tw', $ii, $two['nHrs'], $two['nMin']); ?>
    <div style="width:100%;"><strong>Your Twitter URL:</strong> </div><input name="tw[<?php echo $ii; ?>][apTWURL]" id="apTWURL" style="width: 40%;border: 1px solid #ACACAC;" value="<?php _e(apply_filters('format_to_edit', htmlentities($two['twURL'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />
    <div style="width:100%;"><strong>Your Twitter Consumer Key:</strong> </div><input name="tw[<?php echo $ii; ?>][apTWConsKey]" id="apTWConsKey" style="width: 40%; border: 1px solid #ACACAC;" value="<?php _e(apply_filters('format_to_edit', htmlentities($two['twConsKey'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />  
    <div style="width:100%;"><strong>Your Twitter Consumer Secret:</strong> </div><input name="tw[<?php echo $ii; ?>][apTWConsSec]" id="apTWConsSec" style="width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($two['twConsSec'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />
    <div style="width:100%;"><strong>Your Access Token:</strong> </div><input name="tw[<?php echo $ii; ?>][apTWAccToken]" id="apTWAccToken" style="width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($two['twAccToken'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />
    <div style="width:100%;"><strong>Your Access Token Secret:</strong> </div><input name="tw[<?php echo $ii; ?>][apTWAccTokenSec]" id="apTWAccTokenSec" style="width: 40%;" value="<?php  _e(apply_filters('format_to_edit', htmlentities($two['twAccTokenSec'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />
    <?php if ($isNew) { ?> <input type="hidden" name="tw[<?php echo $ii; ?>][apDoTW]" value="1" id="apDoNewTW<?php echo $ii; ?>" /> <?php } ?>
    <br/><br/>
    <p style="margin: 0px;"><input value="1"  id="apLIAttch" type="checkbox" name="tw[<?php echo $ii; ?>][attchImg]"  <?php if ((int)$two['attchImg'] == 1) echo "checked"; ?> /> <strong>Attach Image to Twitter Post</strong></p>
    <br/>
    <strong id="altFormatText">Message Text Format:</strong>
    <div style="width:100%;">
      <p style="font-size: 11px; margin: 0px;">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. &nbsp; %URL% - Inserts the URL of your post. &nbsp; %SURL% - Inserts the <b>Shortened URL</b> of your post. &nbsp;  %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name. <i>Twitter takes only 140 characters.</i></p>
    </div>
    <input name="tw[<?php echo $ii; ?>][apTWMsgFrmt]" id="apTWMsgFrmt" style="width: 50%;" value="<?php if (!$isNew) _e(apply_filters('format_to_edit', htmlentities($two['twMsgFormat'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster'); else echo "%TITLE% - %URL%"; ?>" />
               
    <?php if($two['twAccTokenSec']!='') { ?> <?php wp_nonce_field( 'rePostToTW', 'rePostToTW_wpnonce' ); ?>
      <br/><br/><b>Test your settings:</b>&nbsp;&nbsp;&nbsp; <?php if (!isset($two['twOK']) || $two['twOK']!='1') { ?> <div class="blnkg">=== Submit Test Post to Complete ===&gt;</div> <?php } ?> <a href="#" class="NXSButton" onclick="testPost('TW', '<?php echo $ii; ?>'); return false;">Submit Test Post to Twitter</a> <br/>
      <?php }?>
      <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div>
    </div>
    <?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'TW'; $lcode = 'tw'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apTWURL']) && $pval['apTWURL']!='') { if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apDoTW']))         $options[$ii]['doTW'] = $pval['apDoTW']; else $options[$ii]['doTW'] = 0;
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apTWURL']))        $options[$ii]['twURL'] = trim($pval['apTWURL']);  if ( substr($options[$ii]['twURL'], 0, 4)!='http' )  $options[$ii]['twURL'] = 'http://'.$options[$ii]['twURL'];
        if (isset($pval['apTWConsKey']))    $options[$ii]['twConsKey'] = trim($pval['apTWConsKey']);
        if (isset($pval['apTWConsSec']))    $options[$ii]['twConsSec'] = trim($pval['apTWConsSec']);                                
        if (isset($pval['apTWAccToken']))   $options[$ii]['twAccToken'] = trim($pval['apTWAccToken']);                
        if (isset($pval['apTWAccTokenSec']))$options[$ii]['twAccTokenSec'] = trim($pval['apTWAccTokenSec']);                                
        if (isset($pval['apTWMsgFrmt']))    $options[$ii]['twMsgFormat'] = trim($pval['apTWMsgFrmt']);
        if (isset($pval['attchImg'])) $options[$ii]['attchImg'] = $pval['attchImg']; else $options[$ii]['attchImg'] = 0;                
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      }
    } return $options;
  }    
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID;
    foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapTW', true));  if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); $doTW = $ntOpt['doTW'];  
         $isAvailTW =  $ntOpt['twURL']!='' && $ntOpt['twConsKey']!='' && $ntOpt['twConsSec']!='' && $ntOpt['twAccToken']!=''; $twMsgFormat = $ntOpt['twMsgFormat'];   $isAttchImg = $options['attchImg'];    
      ?>  
      <tr><th style="text-align:left;" colspan="2">
      <?php if ($isAvailTW) { ?><input class="nxsGrpDoChb" value="1" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="tw[<?php echo $ii; ?>][SNAPincludeTW]" <?php if (($post->post_status == "publish" && $ntOpt['isPosted'] == '1') || ($post->post_status != "publish" && ((int)$doTW == 1)) ) echo 'checked="checked" title="def"';  ?> /> <?php } ?>
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/tw16.png);">Twitter - publish to (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th><td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailTW) { ?><input alt="<?php echo $ii; ?>" style="float: right;" type="button" class="button" name="rePostToTW_repostButton" id="rePostToTW_button" value="<?php _e('Repost to Twitter', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToTW', 'rePostToTW_wpnonce' ); } ?>
                </td></tr>
                <?php if (!$isAvailTW) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Twitter Account to AutoPost to Twitter</b>
                <?php }elseif ($post->post_status != "publish") { ?> 
                
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;"><?php _e('Message Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $twMsgFormat ?>" type="text" name="tw[<?php echo $ii; ?>][SNAPformat]" size="60px" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apTWMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apTWMsgFrmt".$ii); ?></td></tr>
                
<tr><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">
                 <input value="1" type="checkbox" name="tw[<?php echo $ii; ?>][attchImg]"  <?php if ((int)$isAttchImg == 1) echo "checked"; ?> /> </th><td><strong>Attach Image to Twitter Post</strong></td> </tr>                  
       <?php } 
    } 
  }
  //#### 
  function adjMetaOpt($optMt, $pMeta){  if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['twMsgFormat'] = $pMeta['SNAPformat']; 
     if (isset($pMeta['attchImg'])) $optMt['attchImg'] = $pMeta['attchImg'] == 1?1:0; else { if (isset($pMeta['attchImg'])) $optMt['attchImg'] = 0; } 
     if (isset($pMeta['SNAPincludeTW'])) $optMt['doTW'] = $pMeta['SNAPincludeTW'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doTW'] = 0; } return $optMt;
  }
}}

if (!function_exists("nxs_rePostToTW_ajax")) {
  function nxs_rePostToTW_ajax() { check_ajax_referer('rePostToTW');  $postID = $_POST['id']; $options = get_option('NS_SNAutoPoster');  
    foreach ($options['tw'] as $ii=>$two) if ($ii==$_POST['nid']) {   $two['ii'] = $ii;  $two['pType'] = 'aj';
      $twpo =  get_post_meta($postID, 'snapTW', true); $twpo =  maybe_unserialize($twpo);
      if (is_array($twpo) && isset($twpo[$ii]) && is_array($twpo[$ii]) && isset($twpo[$ii]['SNAPformat']) ) { $ntClInst = new nxs_snapClassTW(); $two = $ntClInst->adjMetaOpt($two, $twpo[$ii]);} 
      $result = nxs_doPublishToTW($postID, $two); if ($result == 201) {$options['tw'][$ii]['twOK']=1;  update_option('NS_SNAutoPoster', $options); } if ($result == 200) die("Successfully sent your post to Twitter."); else die($result);
    }
  }
} 

if (!function_exists("nxs_doPublishToTW")) { //## Second Function to Post to TW 
  function nxs_doPublishToTW($postID, $options){ $ntCd = 'TW'; $ntCdL = 'tw'; $ntNm = 'Twitter';
    
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        nxs_addToLog($ntCd.' - '.$options['nName'], 'E', '-=Duplicate=- Post ID:'.$postID, 'Not posted. No reason for posting duplicate'); return;
    }  
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); $uln = 0;
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $msg = 'Test Post from '.$blogTitle." - ".rand(1, 155); $uln = strlen($msg);}  
    else{ $post = get_post($postID); if(!$post) return; $twMsgFormat = $options['twMsgFormat'];  nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1'));        
        $twLim = 140; if (stripos($twMsgFormat, '%URL%')!==false || stripos($twMsgFormat, '%SURL%')!==false) $twLim = $twLim - 20; 
        if (stripos($twMsgFormat, '%AUTHORNAME%')!==false) { $aun = $post->post_author;  $aun = get_the_author_meta('display_name', $aun ); $twLim = $twLim - strlen($aun); } 
        
        $noRepl = str_ireplace("%TITLE%", "", $twMsgFormat); $noRepl = str_ireplace("%SITENAME%", "", $noRepl); $noRepl = str_ireplace("%URL%", "", $noRepl);
        $noRepl = str_ireplace("%SURL%", "", $noRepl);$noRepl = str_ireplace("%TEXT%", "", $noRepl);$noRepl = str_ireplace("%FULLTEXT%", "", $noRepl);
        $noRepl = str_ireplace("%AUTHORNAME%", "", $noRepl); $twLim = $twLim - strlen($noRepl); 
        
        if (stripos($twMsgFormat, '%TITLE%')!==false) {
          $title = $post->post_title; $title = nsTrnc($title, $twLim); $twMsgFormat = str_ireplace("%TITLE%", $title, $twMsgFormat); $twLim = $twLim - strlen($title);
        } 
        if (stripos($twMsgFormat, '%SITENAME%')!==false) {
          $siteTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); $siteTitle = nsTrnc($siteTitle, $twLim); $twMsgFormat = str_ireplace("%SITENAME%", $siteTitle, $twMsgFormat); $twLim = $twLim - strlen($siteTitle);
        }
        if (stripos($twMsgFormat, '%TEXT%')!==false) {
          if ($post->post_excerpt!="") $excerpt = apply_filters('the_content', $post->post_excerpt); else $excerpt= apply_filters('the_content', $post->post_content);
          $excerpt = nsTrnc(strip_tags(strip_shortcodes($excerpt)), 300, " ", "...");
          $excerpt = nsTrnc($excerpt, $twLim); $twMsgFormat = str_ireplace("%TEXT%", $excerpt, $twMsgFormat); $twLim = $twLim - strlen($excerpt);
        }
        if (stripos($twMsgFormat, '%FULLTEXT%')!==false) {
          $postContent = apply_filters('the_content', $post->post_content); $postContent = nsTrnc(strip_tags($postContent), $twLim); $twMsgFormat = str_ireplace("%FULLTEXT%", $postContent, $twMsgFormat); $twLim = $twLim - strlen($postContent);
        }          
        $msg = nsFormatMessage($twMsgFormat, $postID);         
    } //prr($msg);
    $extInfo = ' | PostID: '.$postID." - ".$post->post_title; $logNT = '<span style="color:#00FFFF">Twitter</span> - '.$options['nName'];
    require_once ('apis/tmhOAuth.php'); require_once ('apis/tmhUtilities.php'); if ($uln>0) $msg = nsTrnc($msg, 140+$uln); else { $url = get_permalink($postID); $msg = nsTrnc($msg, 120+strlen($url)); }
    $tmhOAuth = new NXS_tmhOAuth(array( 'consumer_key' => $options['twConsKey'], 'consumer_secret' => $options['twConsSec'], 'user_token' => $options['twAccToken'], 'user_secret' => $options['twAccTokenSec']));
    if ($options['attchImg']=='1') { $imgURL = nxs_getPostImage($postID); $img = wp_remote_get($imgURL); $img = $img['body']; 
      $code = $tmhOAuth -> request('POST', 'https://upload.twitter.com/1/statuses/update_with_media.json', array( 'media[]' => $img, 'status' => $msg), true, true);    
    } else $code = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), array('status' =>$msg)); //prr($code); echo "YYY";
    if ($code == 200){if ($postID=='0'){ nxs_addToLog($logNT, 'M', 'OK - TEST Message Posted '); echo 'OK - Message Posted, please see your Twitter Page'; /*NXS_tmhUtilities::pr(json_decode($tmhOAuth->response['response'])); */ return 201;}
      else { nxs_metaMarkAsPosted($postID, 'TW', $options['ii']); nxs_addToLog($logNT, 'M', 'OK - Message Posted ', $extInfo);} 
    } else{ if ($postID=='0') {NXS_tmhUtilities::pr($tmhOAuth->response['response']); prr($msg); } nxs_addToLog($logNT, 'E', '-=ERROR=- '.print_r($tmhOAuth->response['response'], true)." MSG:".print_r($msg, true), $extInfo); 
      $code .= " | ".$tmhOAuth->response['response']." | ".print_r($msg, true);
    }
    return $code;
  }
}

?>