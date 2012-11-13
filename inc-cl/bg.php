<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'BG', 'lcode'=>'bg', 'name'=>'Blogger');

if (!class_exists("nxs_snapClassBG")) { class nxs_snapClassBG {
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl, $nxs_plurl; $code = 'BG'; $lcode = 'bg'; wp_nonce_field( 'ns'.$code, 'ns'.$code.'_wpnonce' ); 
 
    ?>    
    <hr/><div class="nsx_iconedTitle" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $lcode; ?>16.png);">Blogger Settings:   <?php $cfbo = count($ntOpts); $mfbo =  1+max(array_keys($ntOpts)); ?> <?php wp_nonce_field( 'nsFB', 'nsFB_wpnonce' ); ?>
    <div class="nsBigText">You have <?php echo $cfbo=='0'?'No':$cfbo; ?> Blogger account<?php if ($cfbo!=1){ ?>s<?php } ?> <!-- - <a href="#" class="NXSButton" onclick="doShowHideBlocks2('FB<?php echo $mfbo; ?>');return false;">Add new Facebook Account</a> --> </div></div>    
    <?php // if (function_exists('nxs_doSMAS1')) nxs_doSMAS1($this, $mfbo); else nxs_doSMAS('Blogger', 'BG'.$mfbo); ?>
    <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = $pbo['bgBlogID']; ?>
      <p style="margin: 0px;margin-left: 5px;"><input value="1" id="apDoBG" name="bg[<?php echo $indx; ?>][apDoBG]" onchange="doShowHideBlocks('BG');" type="checkbox" <?php if ((int)$pbo['doBG'] == 1) echo "checked"; ?> /> 
      <strong>Auto-publish your Posts to your Blogger <i style="color: #005800;"><?php if($pbo['nName']!='') echo "(".$pbo['nName'].")"; ?></i></strong>
      &nbsp;&nbsp;<a id="doBG<?php echo $indx; ?>A" href="#" onclick="doShowHideBlocks2('BG<?php echo $indx; ?>');return false;">[Show Settings]</a>&nbsp;&nbsp;
      <a href="#" onclick="doDelAcct('bg', '<?php echo $indx; ?>', '<?php if (isset($pbo['bgBlogID'])) echo $pbo['bgBlogID']; ?>');return false;">[Remove Account]</a>
      </p><?php $this->showNTSettings($indx, $pbo);             
    } //## END TR Settings 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($bo){ $po = array('nName'=>'', 'doBG'=>'1', 'bgUName'=>'', 'bgPass'=>'', 'bgBlogID'=>'', 'bgInclTags'=>'1', 'bgMsgFormat'=>'%FULLTEXT% <br/><a href=\'%URL%\'>%TITLE%</a>', 'bgMsgTFormat'=>'%TITLE%' ); $this->showNTSettings($bo, $po, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; ?>
    <div id="doBG<?php echo $ii; ?>Div" <?php if ($isNew){ ?>class="clNewNTSets"<?php } ?> style="max-width: 1000px; background-color: #EBF4FB; background-image: url(<?php echo $nxs_plurl; ?>img/bg-bg.png);  background-position:90% 10%; background-repeat: no-repeat; margin: 10px; border: 1px solid #808080; padding: 10px; <?php if ((isset($options['bgOK']) && $options['bgOK']!='')||$isNew) { ?>display:none;<?php } ?>">   <input type="hidden" name="apDoSBG<?php echo $ii; ?>" value="0" id="apDoSBG<?php echo $ii; ?>" />                                     
    <?php if ($isNew) { ?> <input type="hidden" name="bg[<?php echo $ii; ?>][apDoBG]" value="1" id="apDoNewBG<?php echo $ii; ?>" /> <?php } ?>
    
    <div style="display: none;"><input name="bg[<?php echo $ii; ?>][apBGPassChr]" id="apBGPassChr" type="password" value="" /></div>
            
            <div id="doBG<?php echo $ii; ?>Div" style="margin-left: 10px;"> <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/bg16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-blogger-social-networks-auto-poster-wordpress/">Detailed Blogger Installation/Configuration Instructions</a></div>
            
            <div style="width:100%;"><strong>Account Nickname:</strong> <i>Just so you can easely identify it</i> </div><input name="bg[<?php echo $ii; ?>][nName]" id="bgnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit',htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /><br/>
            <?php echo nxs_addQTranslSel('bg', $ii, $options['qTLng']); ?>
            <?php echo nxs_addPostingDelaySel('bg', $ii, $options['nHrs'], $options['nMin']); ?>
            
            <div style="width:100%;"><strong>Blogger Username/Email:</strong> </div><input name="bg[<?php echo $ii; ?>][apBGUName]" id="apBGUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',htmlentities($options['bgUName'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />                
            <div style="width:100%;"><strong>Blogger Password:</strong> </div><input name="bg[<?php echo $ii; ?>][apBGPass]" id="apBGPass" autocomplete="off" type="password" style="width: 30%;" value="<?php if (trim($options['bgPass'])!='') _e(apply_filters('format_to_edit', htmlentities(substr($options['bgPass'], 0, 5)=='b4d7s'?nsx_doDecode(substr($options['bgPass'], 5)):$options['bgPass'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />  <br/>                
            <div style="width:100%;"><strong>Blogger Blog ID:</strong> 
            <p style="font-size: 11px; margin: 0px;">Log to your Blogger management panel and look at the URL: http://www.blogger.com/blogger.g?blogID=8959085979163812093#allposts. Your Blog ID will be: 8959085979163812093</p>
            </div><input name="bg[<?php echo $ii; ?>][apBGBlogID]" id="apBGBlogID" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['bgBlogID'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /> 
            <br/><br/>
            
   <div style="width:100%;"><strong id="altFormatText">Post Title Format:</strong> (<a href="#" id="apBGTMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apBGTMsgFrmt<?php echo $ii; ?>'); return false;">Show format info</a>)</div> 
              
              <input name="bg[<?php echo $ii; ?>][apBGMsgTFrmt]" id="apBGMsgTFrmt" style="width: 50%;" value="<?php if ($options['bgMsgTFormat']!='') _e(apply_filters('format_to_edit', htmlentities($options['bgMsgTFormat'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster'); else echo "%TITLE%"; ?>" onfocus="mxs_showFrmtInfo('apBGTMsgFrmt<?php echo $ii; ?>');" /><?php nxs_doShowHint("apBGTMsgFrmt".$ii); ?><br/>
            
            <div id="altFormat" style="">
   <div style="width:100%;"><strong id="altFormatText">Post Text Format:</strong> (<a href="#" id="apBGMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apBGMsgFrmt<?php echo $ii; ?>'); return false;">Show format info</a>) 
   
   HTML is <?php if(!function_exists('doPostToGooglePlus')) {?> <b>NOT</b> <?php } ?> allowed. <?php if(!function_exists('doPostToGooglePlus')) {?> <i>- Blogger "Free API" limitation. Please get <a href="http://www.nextscripts.com/google-plus-automated-posting/#blogger">NextScripts API</a> to allow HTML</i> <?php } ?>   
   </div>
   <input name="bg[<?php echo $ii; ?>][apBGMsgFrmt]" id="apBGMsgFrmt" style="width: 50%;" value="<?php if ($options['bgMsgFormat']!='') _e(apply_filters('format_to_edit',htmlentities($options['bgMsgFormat'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster');  else echo "%FULLTEXT% <br/><a href='%URL%'>%TITLE%</a>"; ?>" onfocus="mxs_showFrmtInfo('apBGMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apBGMsgFrmt".$ii); ?>
            </div>
            
             <p style="margin-bottom: 20px;margin-top: 5px;"><input value="1"  id="bgInclTags" type="checkbox" name="bg[<?php echo $ii; ?>][bgInclTags]"  <?php if ((int)$options['bgInclTags'] == 1) echo "checked"; ?> /> 
              <strong>Post with tags</strong>  Tags from the blogpost will be auto-posted to Blogger/Blogspot                                                               
            </p> 
            
            <?php if ($options['bgPass']!='') { ?>
            <?php wp_nonce_field( 'rePostToBG', 'rePostToBG_wpnonce' ); ?>
            <b>Test your settings:</b>&nbsp;&nbsp;&nbsp; <?php if (!isset($options['bgOK']) || $options['bgOK']!='1') { ?> <div class="blnkg">=== Submit Test Post to Complete ===&gt;</div> <?php } ?> <a href="#" class="NXSButton" onclick="testPost('BG', '<?php echo $ii; ?>'); return false;">Submit Test Post to Blogger</a>         
            <?php } ?>
            
            <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div>
            </div>
        </div>
        <?php
      
      
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; //prr($post); die();
    foreach ($post as $ii => $pval){// prr($pval);
      if (isset($pval['apBGUName']) && trim($pval['apBGUName'])!='' && isset($pval['apBGPass']) && trim($pval['apBGPass'])!='') { if (!isset($options[$ii])) $options[$ii] = array();
        
                if (isset($pval['apDoBG']))   $options[$ii]['doBG'] = $pval['apDoBG']; else $options[$ii]['doBG'] = 0;
                
                if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
                if (isset($pval['apBGUName']))   $options[$ii]['bgUName'] = trim($pval['apBGUName']);
                if (isset($pval['apBGPass']))    $options[$ii]['bgPass'] = 'b4d7s'.nsx_doEncode($pval['apBGPass']); else $options[$ii]['bgPass'] = '';
                if (isset($pval['apBGBlogID']))   $options[$ii]['bgBlogID'] = trim($pval['apBGBlogID']);                
                if (isset($pval['apBGMsgFrmt'])) $options[$ii]['bgMsgFormat'] = trim($pval['apBGMsgFrmt']);                   
                if (isset($pval['apBGMsgTFrmt']))    $options[$ii]['bgMsgTFormat'] = trim($pval['apBGMsgTFrmt']);         
                if (isset($pval['bgInclTags']))    $options[$ii]['bgInclTags'] = $pval['bgInclTags'];  else $options[$ii]['bgInclTags'] = 0;        
                
                if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
                if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
                
      } //prr($options);
    } return $options;
  } 
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; 
    foreach($ntOpts as $ii=>$options){ $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapBG', true)); if (is_array($pMeta)) $options = $this->adjMetaOpt($options, $pMeta[$ii]); $doBG = $options['doBG']; 
       $isAvailBG =  $options['bgUName']!='' && $options['bgPass']!='';  $bgMsgFormat = htmlentities($options['bgMsgFormat'], ENT_COMPAT, "UTF-8");  $bgMsgTFormat = htmlentities($options['bgMsgTFormat'], ENT_COMPAT, "UTF-8");
      ?>  
      
      <tr><th style="text-align:left;" colspan="2">
      <?php if ($isAvailBG) { ?><input class="nxsGrpDoChb" value="1" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="bg[<?php echo $ii; ?>][SNAPincludeBG]" <?php if (($post->post_status == "publish" && $options['isPosted'] == '1') || ($post->post_status != "publish" && ((int)$doBG == 1)) ) echo 'checked="checked" title="def"';  ?> /> <?php } ?>
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/bg16.png);">Blogger - publish to (<i style="color: #005800;"><?php echo $options['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailBG) { ?><input alt="<?php echo $ii; ?>" style="float: right;" type="button" class="button" name="rePostToBG_repostButton" id="rePostToBG_button" value="<?php _e('Repost to Blogger', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToBG', 'rePostToBG_wpnonce' ); } ?>
                </td></tr>
                <?php if (!$isAvailBG) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Blogger Account to AutoPost to Blogger</b>
                <?php } elseif ($post->post_status != "publish") { ?> 
                 <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;"><?php _e('Title Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $bgMsgTFormat ?>" type="text" name="bg[<?php echo $ii; ?>][SNAPTformat]" size="60px" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apBGTMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apBGTMsgFrmt".$ii); ?></td></tr>
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;"><?php _e('Message Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $bgMsgFormat ?>" type="text" name="bg[<?php echo $ii; ?>][SNAPformat]" size="60px" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apBGMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apBGMsgFrmt".$ii); ?></td></tr>
                <?php }
    }      
  }
  
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['bgMsgFormat'] = $pMeta['SNAPformat']; 
     if (isset($pMeta['SNAPTformat'])) $optMt['bgMsgTFormat'] = $pMeta['SNAPTformat'];      
     if (isset($pMeta['SNAPincludeBG'])) $optMt['doBG'] = $pMeta['SNAPincludeBG'] == 1?1:0; else { if (isset($pMeta['SNAPformat']))  $optMt['doBG'] = 0; } 
     return $optMt;
  }
}}

if (!function_exists("nxs_rePostToBG_ajax")) { function nxs_rePostToBG_ajax() {  check_ajax_referer('rePostToBG');  $postID = $_POST['id']; // $result = nsPublishTo($id, 'FB', true);   
      global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
      foreach ($options['bg'] as $ii=>$po) if ($ii==$_POST['nid']) {   $po['ii'] = $ii; $po['pType'] = 'aj';
      $mpo =  get_post_meta($postID, 'snapBG', true); $mpo =  maybe_unserialize($mpo);       
      if (is_array($mpo) && isset($mpo[$ii]) && is_array($mpo[$ii]) ){ $ntClInst = new nxs_snapClassBG(); $po = $ntClInst->adjMetaOpt($po, $mpo[$ii]); } 
      $result = nxs_doPublishToBG($postID, $po);  if ($result == 201) { $options['bg'][$ii]['bgOK']=1;  update_option('NS_SNAutoPoster', $options); }
      
      if ($result == 200) die("Successfully sent your post to Blooger."); else die($result);
    }    
  }
}
if (!function_exists('nsBloggerGetAuth')){ function nsBloggerGetAuth($email, $pass) { $pass = urlencode($pass);
    $ch = curl_init("https://www.google.com/accounts/ClientLogin?Email=$email&Passwd=$pass&service=blogger&accountType=GOOGLE");    
    $headers = array(); $headers[] = 'Accept: text/html, application/xhtml+xml, */*'; 
    $headers[] = 'Connection: Keep-Alive'; $headers[] = 'Accept-Language: en-us'; 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0)");
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,10); curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HEADER,0); curl_setopt($ch, CURLOPT_RETURNTRANSFER ,1);
    $result = curl_exec($ch); $resultArray = curl_getinfo($ch); 
    curl_close($ch); $arr = explode("=",$result); $token = $arr[3]; if (trim($token)=='') die('Incorrect Username/Password'); return $token;
}}
if (!function_exists('nsBloggerNewPost')){ function nsBloggerNewPost($auth, $blogID, $title, $text) {$text = str_ireplace('allowfullscreen','', $text); 
    if (class_exists('DOMDocument')) {$doc = new DOMDocument();  @$doc->loadHTML('<?xml encoding="UTF-8">' .$text); $doc->encoding = 'UTF-8'; $text = $doc->saveHTML(); $text = CutFromTo($text, '<body>', '</body>'); 
      $text = preg_replace('/<br(.*?)\/?>/','<br$1/>',$text);   $text = preg_replace('/<img(.*?)\/?>/','<img$1/>',$text);
      require_once ('apis/htmlNumTable.php');  $text = strtr($text, $HTML401NamedToNumeric);  $title = strtr($title, $HTML401NamedToNumeric);
    }  //  prr($text); 
    $postText = '<entry xmlns="http://www.w3.org/2005/Atom"><title type="text">'.$title.'</title><content type="xhtml">'.$text.'</content></entry>'; //prr($postText);
    $len = strlen($entry); $ch = curl_init("https://www.blogger.com/feeds/$blogID/posts/default"); 
    $headers = array("Content-type: application/atom+xml", "Content-Length: ".strlen($postText), "Authorization: GoogleLogin auth=".$auth, $postText);
    curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, 1);  curl_setopt($ch, CURLOPT_POST, true);  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0)");
    curl_setopt($ch, CURLOPT_HEADER,0); curl_setopt($ch, CURLOPT_RETURNTRANSFER ,1); curl_setopt($ch, CURLINFO_HEADER_OUT, true); 
    $result = curl_exec($ch); curl_close($ch); if (stripos($result,'tag:blogger.com')!==false) return 'OK'; else { prr($result); return false;}
}}
if (!function_exists("nxs_doPublishToBG")) { //## Second Function to Post to BG
  function nxs_doPublishToBG($postID, $options){ $ntCd = 'BG'; $ntCdL = 'bg'; $ntNm = 'Blogger';
      
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); 
    
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        nxs_addToLog($ntCd.' - '.$options['nName'], 'E', '-=Duplicate=- Post ID:'.$postID, 'Not posted. No reason for posting duplicate'); return;
    }     
    
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $msgT = 'Test Post from '.htmlentities($blogTitle);  $link = home_url(); $msg = 'Test Post from '.$blogTitle. " ".$link; }
      else { $post = get_post($postID); if(!$post) return; $msgFormat = $options['bgMsgFormat']; $msg = nsFormatMessage($msgFormat, $postID); 
        $link = get_permalink($postID); $msgTFormat = $options['bgMsgTFormat']; $msgT = nsFormatMessage($msgTFormat, $postID); nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); 
    }
    //## Actual POST Code
    $email = $options['bgUName'];  $pass = substr($options['bgPass'], 0, 5)=='b4d7s'?nsx_doDecode(substr($options['bgPass'], 5)):$options['bgPass']; $blogID = $options['bgBlogID'];
    //echo "###".$auth."|".$blogID."|".$msgT."|".$msg;
    if ($options['bgInclTags']=='1'){$t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = implode('","',$tggs); $tags = nsTrnc($tags, 195, ',', ''); }
    $extInfo = ' | PostID: '.$postID; $logNT = '<span style="color:#F87907">'.$ntNm.'</span> - '.$options['nName']; 
    
    $msg = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $msg); $msg = preg_replace('/<!--(.*)-->/Uis', "", $msg); $nxshf = new NXS_HtmlFixer(); $msg = $nxshf->getFixedHtml($msg);
    
    if (function_exists("doConnectToBlogger")) {$auth = doConnectToBlogger($email, $pass); if ($auth!==false) die($auth);  $ret = doPostToBlogger($blogID, $msgT, $msg, $tags);} 
      else {$auth = nsBloggerGetAuth($email, $pass); $ret = nsBloggerNewPost($auth, $blogID, $msgT, $msg);}
    //## /Actual POST Code
    
    if ($ret!='OK') { if ($postID=='0') echo $ret;  nxs_addToLog($logNT, 'E', '-=ERROR=- '.print_r($ret, true), $extInfo); return  $ret; }
      else { if ($postID=='0') { echo 'OK - Message Posted, please see your '.$ntNm.' Page '; nxs_addToLog($logNT, 'M', 'OK - TEST Message Posted '); return 201;} 
        else { nxs_metaMarkAsPosted($postID, $ntCd, $options['ii']); nxs_addToLog($logNT, 'M', 'OK - Message Posted ', $extInfo);} return 200; }
  }
}

?>