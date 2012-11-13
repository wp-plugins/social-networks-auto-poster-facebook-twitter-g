<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'TR', 'lcode'=>'tr', 'name'=>'Tumblr');

if (!class_exists("nxs_snapClassTR")) { class nxs_snapClassTR {
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl, $nxs_plurl; $code = 'TR'; $lcode = 'tr'; wp_nonce_field( 'ns'.$code, 'ns'.$code.'_wpnonce' ); 
    
   if ( isset($_GET['auth']) && $_GET['auth']=='tr'){ require_once('apis/trOAuth.php'); $options = $ntOpts[$_GET['acc']];
     $consumer_key = $options['trConsKey']; $consumer_secret = $options['trConsSec'];
              $callback_url = $nxs_snapThisPageUrl."&auth=tra&acc=".$_GET['acc'];
              $tum_oauth = new TumblrOAuth($consumer_key, $consumer_secret);prr($tum_oauth );
              $request_token = $tum_oauth->getRequestToken($callback_url); echo "####"; prr($request_token);
              $options['trOAuthToken'] = $request_token['oauth_token'];
              $options['trOAuthTokenSecret'] = $request_token['oauth_token_secret'];// prr($tum_oauth ); die();
              switch ($tum_oauth->http_code) { case 200: $url = $tum_oauth->getAuthorizeURL($options['trOAuthToken']); $optionsG = get_option('NS_SNAutoPoster'); $optionsG['tr'][$_GET['acc']] = $options;  update_option('NS_SNAutoPoster', $optionsG);
                echo '<script type="text/javascript">window.location = "'.$url.'"</script>'; break; 
                default: echo '<br/><b style="color:red">Could not connect to Tumblr. Refresh the page or try again later.</b>'; die();
              }
              die();
            }
            if ( isset($_GET['auth']) && $_GET['auth']=='tra'){ require_once('apis/trOAuth.php'); $options = $ntOpts[$_GET['acc']];
              $consumer_key = $options['trConsKey']; $consumer_secret = $options['trConsSec'];  
              $tum_oauth = new TumblrOAuth($consumer_key, $consumer_secret, $options['trOAuthToken'], $options['trOAuthTokenSecret']);
              $options['trAccessTocken'] = $tum_oauth->getAccessToken($_REQUEST['oauth_verifier']); // prr($_GET);  prr($_REQUEST);   prr($options['trAccessTocken']);         
              $tum_oauth = new TumblrOAuth($consumer_key, $consumer_secret, $options['trAccessTocken']['oauth_token'], $options['trAccessTocken']['oauth_token_secret']); 
              $optionsG = get_option('NS_SNAutoPoster'); $optionsG['tr'][$_GET['acc']] = $options;  update_option('NS_SNAutoPoster', $optionsG);
              $userinfo = $tum_oauth->get('http://api.tumblr.com/v2/user/info'); prr($userinfo); prr($tum_oauth);// prr($url); die();
              if (is_array($userinfo->response->user->blogs)) {
                foreach ($userinfo->response->user->blogs as $blog){
                  if (stripos($blog->url, $options['trPgID'])!==false) {  echo '<script type="text/javascript">window.location = "'.$nxs_snapThisPageUrl.'"</script>'; break;  die();}
                } prr($userinfo);
                die("<span style='color:red;'>ERROR: Authorized USER don't have access to the specified blog: <span style='color:darkred; font-weight: bold;'>".$options['trPgID']."</span></span>");
              }
            }
    
    
    ?>    
    <hr/><div class="nsx_iconedTitle" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $lcode; ?>16.png);">Tumblr Settings:   <?php $cfbo = count($ntOpts); $mfbo =  1+max(array_keys($ntOpts)); ?> <?php wp_nonce_field( 'nsFB', 'nsFB_wpnonce' ); ?>
    <div class="nsBigText">You have <?php echo $cfbo=='0'?'No':$cfbo; ?> Tumblr account<?php if ($cfbo!=1){ ?>s<?php } ?> <!-- - <a href="#" class="NXSButton" onclick="doShowHideBlocks2('FB<?php echo $mfbo; ?>');return false;">Add new Facebook Account</a> --> </div></div>
    
    <?php // if (function_exists('nxs_doSMAS1')) nxs_doSMAS1($this, $mfbo); else nxs_doSMAS('Tumblr', 'TR'.$mfbo); ?>
    <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = str_ireplace('https://','', str_ireplace('http://','', $pbo['trURL']));  ?>
      <p style="margin: 0px;margin-left: 5px;"><input value="1" id="apDoTR" name="tr[<?php echo $indx; ?>][apDoTR]" type="checkbox" <?php if ((int)$pbo['doTR'] == 1) echo "checked"; ?> /> 
      <strong>Auto-publish your Posts to your Tumblr Blog <i style="color: #005800;"><?php if($pbo['nName']!='') echo "(".$pbo['nName'].")"; ?></i> </strong>
      &nbsp;&nbsp;<a id="doTR<?php echo $indx; ?>A" href="#" onclick="doShowHideBlocks2('TR<?php echo $indx; ?>');return false;">[Show Settings]</a>&nbsp;&nbsp;
      <a href="#" onclick="doDelAcct('tr', '<?php echo $indx; ?>', '<?php if (isset($pbo['trURL'])) echo $pbo['trURL']; ?>');return false;">[Remove Account]</a>
      </p><?php $this->showNTSettings($indx, $pbo);             
    } //## END TR Settings 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($bo){ $po = array('nName'=>'', 'doTR'=>'1', 'trURL'=>'', 'trPgID'=>'', 'trConsKey'=>'', 'trInclTags'=>'1', 'trConsSec'=>'', 'trPostType'=>'T', 'trDefImg'=>'', 'trOAuthTokenSecret'=>'', 'trAccessTocken'=>'', 'trMsgFormat'=>'<p>New Post has been published on %URL%</p><blockquote><p><strong>%TITLE%</strong></p><p><img src=\'%IMG%\'/></p><p>%FULLTEXT%</p></blockquote>', 'trMsgTFormat'=>'New Post has been published on %SITENAME%' ); $this->showNTSettings($bo, $po, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl,$nxs_snapThisPageUrl; ?>
    <div id="doTR<?php echo $ii; ?>Div"<?php if ($isNew){ ?>class="clNewNTSets"<?php } ?> style="max-width: 1000px; background-color: #EBF4FB; background-image: url(<?php echo $nxs_plurl; ?>img/tr-bg.png);  background-position:90% 10%; background-repeat: no-repeat; margin: 10px; border: 1px solid #808080; padding: 10px; <?php if ((isset($options['trOAuthTokenSecret']) && $options['trOAuthTokenSecret']!='')||$isNew) { ?>display:none;<?php } ?>">   <input type="hidden" name="apDoSTR<?php echo $ii; ?>" value="0" id="apDoSTR<?php echo $ii; ?>" />                                     
    <?php if ($isNew) { ?> <input type="hidden" name="tr[<?php echo $ii; ?>][apDoTR]" value="1" id="apDoNewTR<?php echo $ii; ?>" /> <?php } ?>
    
    <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/tr16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-tumblr-social-networks-auto-poster-wordpress/">Detailed Tumblr Installation/Configuration Instructions</a></div>
    
            <div style="width:100%;"><strong>Account Nickname:</strong> <i>Just so you can easely identify it</i> </div><input name="tr[<?php echo $ii; ?>][nName]" id="trnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /><br/>
            <?php echo nxs_addQTranslSel('tr', $ii, $options['qTLng']); ?><?php echo nxs_addPostingDelaySel('tr', $ii, $options['nHrs'], $options['nMin']); ?>
            
            <div style="width:100%;"><strong>Your Tumblr URL:</strong> </div><input onchange="nxsTRURLVal(<?php echo $ii; ?>);" name="tr[<?php echo $ii; ?>][apTRURL]" id="apTRURL<?php echo $ii; ?>" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['trURL'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /><span style="color: #F00000;" id="apTRURLerr<?php echo $ii; ?>"></span>
            <div style="width:100%;"><strong>Your Tumblr OAuth Consumer Key:</strong> </div><input name="tr[<?php echo $ii; ?>][apTRConsKey]" id="apTRConsKey" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['trConsKey'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />             
            <div style="width:100%;"><strong>Your Tumblr Secret Key:</strong> </div><input name="tr[<?php echo $ii; ?>][apTRConsSec]" id="apTRConsSec" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['trConsSec'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />
            <br/><br/>
            
<div style="width:100%;"><strong id="altFormatText">Post Type:</strong></div>                      
<div style="margin-left: 10px;">
    
    <input type="radio" name="tr[<?php echo $ii; ?>][apTRPostType]" value="T" <?php if ($options['trPostType'] != 'I') echo 'checked="checked"'; ?> onchange="nxs_TRSetEnable('T','<?php echo $ii; ?>');" /> Text Post<br/>            

    <div style="width:100%; margin-left: 15px;"><strong id="altFormatText">Text Post Title Format:</strong> (<a href="#" id="apTRTMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apTRTMsgFrmt<?php echo $ii; ?>'); return false;">Show format info</a>)  </div><div onblur="jQuery('#apTRMsgFrmt<?php echo $ii; ?>Hint').hide();">
              <input name="tr[<?php echo $ii; ?>][apTRMsgTFrmt]" id="apTRMsgTFrmt<?php echo $ii; ?>" style="margin-left: 15px; width: 50%;" value="<?php if ($options['trMsgTFormat']!='') _e(apply_filters('format_to_edit', htmlentities($options['trMsgTFormat'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster'); else echo "New Post has been published on %SITENAME%"; ?>"  onfocus="jQuery('#apTRTMsgFrmt<?php echo $ii; ?>Hint').show();"  <?php if ($options['trPostType'] == 'I') echo 'disabled="disabled"'; ?>  /><br/>
              <?php nxs_doShowHint("apTRTMsgFrmt".$ii); ?>
            </div>
            
<input type="radio" name="tr[<?php echo $ii; ?>][apTRPostType]" value="I" <?php if ($options['trPostType'] == 'I') echo 'checked="checked"'; ?> onchange="nxs_TRSetEnable('I','<?php echo $ii; ?>');"/> Image Post<br/>

<div style="width:100%; margin-left: 15px;"><strong>Defailt Image to Post:</strong> 
            <p style="font-size: 11px; margin: 0px;">If your post is missing "Featured Image" and doesn't have any images in the text body this will be used instead.</p>
            </div><input name="tr[<?php echo $ii; ?>][apTRDefImg]" id="apTRDefImg<?php echo $ii; ?>" style=" margin-left: 15px; width: 30%;" <?php if ($options['trPostType'] != 'I') echo 'disabled="disabled"'; ?> value="<?php _e(apply_filters('format_to_edit', htmlentities($options['trDefImg'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /> 
            <br/><br/>

</div>            
            
  <div style="width:100%;"><strong id="altFormatText">Post Text Format:</strong> (<a href="#" id="apTRMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apTRMsgFrmt<?php echo $ii; ?>'); return false;">Show format info</a>) </div>
              
              <input name="tr[<?php echo $ii; ?>][apTRMsgFrmt]" id="apTRMsgFrmt" style="width: 50%;" value="<?php if ($options['trMsgFormat']!='') _e(apply_filters('format_to_edit', htmlentities($options['trMsgFormat'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster'); else echo htmlentities("<p>New Post has been published on %URL%</p><blockquote><p><strong>%TITLE%</strong></p><p><img src=\"%IMG%\"/></p><p>%FULLTEXT%</p></blockquote>"); ?>" onfocus="jQuery('#apTRMsgFrmt<?php echo $ii; ?>Hint').show();" /><br/>
               <?php nxs_doShowHint("apTRMsgFrmt".$ii); ?>
               
              
              <p style="margin-bottom: 20px;margin-top: 5px;"><input value="1"  id="trInclTags" type="checkbox" name="tr[<?php echo $ii; ?>][trInclTags]"  <?php if ((int)$options['trInclTags'] == 1) echo "checked"; ?> /> 
              <strong>Post with tags</strong> Tags from the blogpost will be auto posted to Tumblr                                
            </p>
              
              <?php 
            if($options['trConsSec']=='') { ?>
            <b>Authorize Your Tumblr Account</b>. Please save your settings and come back here to Authorize your account.
            <?php } else { if(isset($options['trAccessTocken']) && isset($options['trAccessTocken']['oauth_token_secret']) && $options['trAccessTocken']['oauth_token_secret']!=='') { ?>
            Your Tumblr Account has been authorized. Blog ID: <?php _e(apply_filters('format_to_edit', htmlentities($options['trPgID'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>. 
            You can Re- <?php } ?>            
            <a href="<?php echo $nxs_snapThisPageUrl;?>&auth=tr&acc=<?php echo $ii; ?>">Authorize Your Tumblr Account</a> 
              <?php if (!isset($options['trOAuthTokenSecret']) || $options['trOAuthTokenSecret']=='') { ?> <div class="blnkg">&lt;=== Authorize your account ===</div> <?php } ?>            
            <?php }  ?>            
            
            
            <?php if( isset($options['trOAuthTokenSecret']) && $options['trOAuthTokenSecret']!='') { ?>
            <?php wp_nonce_field( 'rePostToTR', 'rePostToTR_wpnonce' ); ?>
            <br/><br/><b>Test your settings:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('TR', '<?php echo $ii; ?>'); return false;">Submit Test Post to Tumblr</a>  <br/><br/>
            <?php }?>
            <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div>  
            
        </div>
        <?php
      
      
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; //prr($post); die();
    foreach ($post as $ii => $pval){ // prr($pval);
      if (isset($pval['apTRConsKey']) && $pval['apTRConsSec']!='') { if (!isset($options[$ii])) $options[$ii] = array();
        
                if (isset($pval['apTRURL']))  {   $options[$ii]['trURL'] = trim($pval['apTRURL']);  if ( substr($options[$ii]['trURL'], 0, 4)!='http' )  $options[$ii]['trURL'] = 'http://'.$options[$ii]['trURL'];
                  $trPgID = $options[$ii]['trURL']; if (substr($trPgID, -1)=='/') $trPgID = substr($trPgID, 0, -1);  $trPgID = substr(strrchr($trPgID, "/"), 1);
                  $options[$ii]['trPgID'] = $trPgID; //echo $fbPgID;
                }
                if (isset($pval['apDoTR']))         $options[$ii]['doTR'] = $pval['apDoTR']; else $options[$ii]['doTR'] = 0;
                if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
                if (isset($pval['apTRConsKey']))    $options[$ii]['trConsKey'] = trim($pval['apTRConsKey']);
                if (isset($pval['apTRConsSec']))    $options[$ii]['trConsSec'] = trim($pval['apTRConsSec']);                                
                if (isset($pval['apTRMsgFrmt']))    $options[$ii]['trMsgFormat'] = trim($pval['apTRMsgFrmt']);                                
                if (isset($pval['apTRMsgTFrmt']))   $options[$ii]['trMsgTFormat'] = trim($pval['apTRMsgTFrmt']);   
                if (isset($pval['trInclTags']))     $options[$ii]['trInclTags'] = $pval['trInclTags']; else $options[$ii]['trInclTags'] = 0;
                if (isset($pval['apTRPostType']))   $options[$ii]['trPostType'] = trim($pval['apTRPostType']);   
                if (isset($pval['apTRDefImg']))     $options[$ii]['trDefImg'] = trim($pval['apTRDefImg']);   
                if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
                if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      } // prr($options);
    } return $options;
  } 
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; 
    foreach($ntOpts as $ii=>$options)  {$pMeta = maybe_unserialize(get_post_meta($post_id, 'snapTR', true));  if (is_array($pMeta)) $options = $this->adjMetaOpt($options, $pMeta[$ii]); $doTR = $options['doTR']; 
       $isAvailTR =  isset($options['trAccessTocken']) && isset($options['trAccessTocken']['oauth_token_secret']) && $options['trAccessTocken']['oauth_token_secret']!=='';          
       $trMsgFormat = htmlentities($options['trMsgFormat'], ENT_COMPAT, "UTF-8");  $trMsgTFormat = $options['trMsgTFormat'];
      ?>  
      
      <tr><th style="text-align:left;" colspan="2">
      <?php if ($isAvailTR) { ?><input class="nxsGrpDoChb" value="1" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="tr[<?php echo $ii; ?>][SNAPincludeTR]" <?php if (($post->post_status == "publish" && $options['isPosted'] == '1') || ($post->post_status != "publish" && ((int)$doTR == 1)) ) echo 'checked="checked" title="def"';  ?> /> <?php } ?>
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/tr16.png);">Tumblr - publish to (<i style="color: #005800;"><?php echo $options['nName']; ?></i>) </div></th><td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailTR) { ?><input alt="<?php echo $ii; ?>" style="float: right;" type="button" class="button" name="rePostToTR_repostButton" id="rePostToTR_button" value="<?php _e('Repost to Tumblr', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToTR', 'rePostToTR_wpnonce' ); } ?>
                </td></tr>
                <?php if (!$isAvailTR) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup and authorize your Tumblr Account to AutoPost to Tumblr</b>
                <?php }elseif ($post->post_status != "publish") { ?> 
                         
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;">
                <input type="radio" name="tr[<?php echo $ii; ?>][apTRPostType]" value="T" <?php if ($options['trPostType'] != 'I') echo 'checked="checked"'; ?>  /> <br/>
                
                </th>
                <td><b><?php _e('Text Post. Title Format:', 'NS_SPAP') ?></b><input value="<?php echo $trMsgTFormat ?>" type="text" name="tr[<?php echo $ii; ?>][SNAPTformat]" style="width:270px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apTRTMsgFrmt<?php echo $ii; ?>');"/>&nbsp; .. or .. &nbsp;<input type="radio" name="tr[<?php echo $ii; ?>][apTRPostType]" value="I" <?php if ($options['trPostType'] == 'I') echo 'checked="checked"'; ?>  /> <b>Image Post</b> <?php nxs_doShowHint("apTRTMsgFrmt".$ii); ?> </td></tr>
                
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;"><?php _e('Text Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $trMsgFormat ?>" type="text" name="tr[<?php echo $ii; ?>][SNAPformat]" size="60px" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apTRMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apTRMsgFrmt".$ii); ?></td></tr>
                               
   <?php } 
    }
      
  }
  
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['trMsgFormat'] = $pMeta['SNAPformat']; 
     if (isset($pMeta['SNAPTformat'])) $optMt['trMsgTFormat'] = $pMeta['SNAPTformat']; 
     if (isset($pMeta['apTRPostType'])) $optMt['trPostType'] = $pMeta['apTRPostType']; 
     if (isset($pMeta['AttachPost'])) $optMt['trAttch'] = $pMeta['AttachPost'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['trAttch'] = 0; }
     if (isset($pMeta['SNAPincludeTR'])) $optMt['doTR'] = $pMeta['SNAPincludeTR'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doTR'] = 0; }return $optMt;
  }
}}

if (!function_exists("nxs_rePostToTR_ajax")) { function nxs_rePostToTR_ajax() {  check_ajax_referer('rePostToTR');  $postID = $_POST['id']; // $result = nsPublishTo($id, 'FB', true);   
    $options = get_option('NS_SNAutoPoster');  foreach ($options['tr'] as $ii=>$po) if ($ii==$_POST['nid']) {   $po['ii'] = $ii; $po['pType'] = 'aj';
      $mpo =  get_post_meta($postID, 'snapTR', true); $mpo =  maybe_unserialize($mpo); 
      if (is_array($mpo) && isset($mpo[$ii]) && is_array($mpo[$ii]) ){ $ntClInst = new nxs_snapClassPN(); $po = $ntClInst->adjMetaOpt($po, $mpo[$ii]); }
      $result = nxs_doPublishToTR($postID, $po); if ($result == 200 || $result == 201) die("Your post has been successfully sent to Tumblr."); else { echo $result; die(); }
    }    
  }
}

if (!function_exists("nxs_doPublishToTR")) { //## Second Function to Post to TR
  function nxs_doPublishToTR($postID, $options){ $ntCd = 'TR'; $ntCdL = 'tr'; $ntNm = 'Tumblr';
      
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); 
    
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        nxs_addToLog($ntCd.' - '.$options['nName'], 'E', '-=Duplicate=- Post ID:'.$postID, 'Not posted. No reason for posting duplicate'); return;
    }  
    //## Format
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $msg = 'Test Post from '.$blogTitle;  $msgT = 'Test Post from '.$blogTitle;}
      else{ $post = get_post($postID); if(!$post) return; $trMsgFormat = $options['trMsgFormat'];  $msg = nsFormatMessage($trMsgFormat, $postID); 
        $trMsgTFormat = $options['trMsgTFormat']; $msgT = nsFormatMessage($trMsgTFormat, $postID);  nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1'));   
    } 
    //## Post    
    require_once('apis/trOAuth.php'); $consumer_key = $options['trConsKey']; $consumer_secret = $options['trConsSec'];
    $tum_oauth = new TumblrOAuth($consumer_key, $consumer_secret, $options['trAccessTocken']['oauth_token'], $options['trAccessTocken']['oauth_token_secret']); //prr($options);
    $trURL = trim(str_ireplace('http://', '', $options['trURL'])); if (substr($trURL,-1)=='/') $trURL = substr($trURL,0,-1);     
    if ($options['trInclTags']=='1'){$t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = implode(',',$tggs); }    
    $postDate = ($post->post_date_gmt!='0000-00-00 00:00:00'?$post->post_date_gmt:gmdate("Y-m-d H:i:s", strtotime($post->post_date)))." GMT";  //## Adds date to Tumblr post. Thanks to Kenneth Lecky
    $extInfo = ' | PostID: '.$postID." - ".$post->post_title; $logNT = '<span style="color:#014A76">Tumblr</span> - '.$options['nName'];
    $postArr = array('tags'=>$tags, 'date'=>$postDate);
    if ($options['trPostType']=='I') { $postArr['type'] = 'photo'; $postArr['caption'] = $msg;  $postArr['source'] = nxs_getPostImage($postID, 'large', $options['trDefImg']); $postArr['link'] = get_permalink($postID); } 
      else { $postArr['title'] = $msgT; $postArr['type'] = 'text'; $postArr['source'] = get_permalink($postID); $postArr['body'] = $msg; } 
    
    $postinfo = $tum_oauth->post("http://api.tumblr.com/v2/blog/".$trURL."/post", $postArr); // prr($postArr);
    
    $code = $postinfo->meta->status;// echo "XX".print_r($code);  prr($postinfo); // prr($msg); prr($postinfo); echo $code."VVVV"; die("|====");
    if ($code == 201) { if ($postID=='0') { nxs_addToLog($logNT, 'M', 'OK - TEST Message Posted '); echo 'OK - Message Posted, please see your Tumblr  Page. <br/> Result:'; prr($postinfo->meta); } 
      else { nxs_addToLog($logNT, 'M', 'OK - Message Posted ', $extInfo);  nxs_metaMarkAsPosted($postID, 'TR', $options['ii']);  } } 
    else { nxs_addToLog($logNT, 'E', '-=ERROR=- '.print_r($postinfo, true), $extInfo); if ($postID=='0') prr($postinfo); $code .= " - ".$postinfo->meta->msg.$postinfo->errmsg; }
    
    return $code;
  }
}

?>