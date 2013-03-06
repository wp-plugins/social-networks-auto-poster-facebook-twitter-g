<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'YT', 'lcode'=>'yt', 'name'=>'YouTube');

if (!class_exists("nxs_snapClassYT")) { class nxs_snapClassYT {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){  global $nxs_plurl; $ntInfo = array('code'=>'YT', 'lcode'=>'yt', 'name'=>'YouTube', 'defNName'=>'ytUName', 'tstReq' => false); ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> <?php wp_nonce_field( 'ns'.$ntInfo['code'], 'ns'.$ntInfo['code'].'_wpnonce' ); ?>
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php if(!function_exists('doPostToGooglePlus')) {?> YouTube doesn't have a built-in API for automated posts yet. The current <a href="http://developers.google.com/+/api/">YouTube API</a> is "Read Only" and can't be used for posting.  <br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/google-plus-automated-posting">library module</a> to be able to publish your content to YouTube. 
        <?php } else foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = $pbo[$ntInfo['defNName']]; ?>
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
  function showNewNTSettings($myto){ $yto = array('nName'=>'', 'doYT'=>'1', 'ytUName'=>'', 'ytPageID'=>'', 'ytCommID'=>'', 'postType'=>'A', 'ytPass'=>''); $this->showNTSettings($myto, $yto, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $yto, $isNew=false){  global $nxs_plurl; ?>
            <div id="doYT<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>" style="background-image: url(<?php echo $nxs_plurl; ?>img/yt-bg.png);  background-position:90% 10%;">     <input type="hidden" name="apDoSYT<?php echo $ii; ?>" value="0" id="apDoSYT<?php echo $ii; ?>" />             
            <?php if(!function_exists('doPostToGooglePlus')) {?><span style="color:#580000; font-size: 16px;"><br/><br/>
            <b><?php _e('YouTube API Library not found', 'nxs_snap'); ?></b>
             <br/><br/> <?php _e('YouTube doesn\'t have a built-in API for automated posts yet.', 'nxs_snap'); ?> <br/><?php _e('The current <a target="_blank" href="http://developers.google.com/+/api/">YouTube API</a> is "Read Only" and can\'t be used for posting.  <br/><br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/google-plus-automated-posting"><b>API Library Module</b></a> to be able to publish your content to YouTube.', 'nxs_snap'); ?></span></div>
            <?php return; }; ?>            
            <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/yt16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-google-plus-social-networks-auto-poster-wordpress/"><?php $nType="YouTube"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
            
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easely identify it', 'nxs_snap'); ?></i> </div><input name="yt[<?php echo $ii; ?>][nName]" id="ytnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($yto['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('yt', $ii, $yto['qTLng']); ?><?php echo nxs_addPostingDelaySel('yt', $ii, $yto['nHrs'], $yto['nMin']); ?>
            
            <?php if (!$isNew) { ?>
    <div style="width:100%;"><strong><?php _e('Categories', 'nxs_snap'); ?>:</strong>
       <input value="0" id="catSelA<?php echo $ii; ?>" type="radio" name="yt[<?php echo $ii; ?>][catSel]" <?php if ((int)$yto['catSel'] != 1) echo "checked"; ?> /> All                                  
       <input value="1" id="catSelSYT<?php echo $ii; ?>" type="radio" name="yt[<?php echo $ii; ?>][catSel]" <?php if ((int)$yto['catSel'] == 1) echo "checked"; ?> /> <a href="#" style="text-decoration: none;" class="showCats" id="nxs_SCA_YT<?php echo $ii; ?>" onclick="jQuery('#catSelSYT<?php echo $ii; ?>').attr('checked', true); jQuery('#tmpCatSelNT').val('YT<?php echo $ii; ?>'); nxs_markCats( jQuery('#nxs_SC_YT<?php echo $ii; ?>').val() ); jQuery('#showCatSel').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false], position: [75, 'auto']}); return false;">Selected<?php if ($yto['catSelEd']!='') echo "[".(substr_count($yto['catSelEd'], ",")+1)."]"; ?></a>       
       <input type="hidden" name="yt[<?php echo $ii; ?>][catSelEd]" id="nxs_SC_YT<?php echo $ii; ?>" value="<?php echo $yto['catSelEd']; ?>" />
    <br/><i><?php _e('Only selected categories will be autoposted to this account', 'nxs_snap'); ?></i></div> 
    <br/>
    <?php } ?>
            
            <div style="width:100%;"><strong>YouTube(Google) Username:</strong> </div><input name="yt[<?php echo $ii; ?>][apYTUName]" id="apYTUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($yto['ytUName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
            <div style="width:100%;"><strong>YouTube(Google) Password:</strong> </div><input name="yt[<?php echo $ii; ?>][apYTPass]" id="apYTPass" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($yto['ytPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($yto['ytPass'], 5)):$yto['ytPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/>                
            <p><div style="width:100%;"><strong>YouTube Channel Page URL:</strong> 
            
            </div><input name="yt[<?php echo $ii; ?>][apYTPage]" id="apYTPage" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($yto['ytPageID'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /> 
            <br/><br/>
            
            <div id="altFormat" style="">
              <div style="width:100%;"><strong id="altFormatText"><?php _e('Message text Format', 'nxs_snap'); ?>:</strong> (<a href="#" id="apYTMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apYTMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)
              </div>
              
              <textarea cols="150" rows="3" id="yt<?php echo $ii; ?>SNAPformat" name="yt[<?php echo $ii; ?>][apYTMsgFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#yt<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apYTMsgFrmt<?php echo $ii; ?>');"><?php if ($isNew) _e("New post: %TITLE% - %URL%", 'nxs_snap'); else _e(apply_filters('format_to_edit', htmlentities($yto['ytMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?></textarea>
              
              <?php nxs_doShowHint("apYTMsgFrmt".$ii); ?>
            </div><br/>          
          
            <?php if ($isNew) { ?> <input type="hidden" name="yt[<?php echo $ii; ?>][apDoYT]" value="1" id="apDoNewYT<?php echo $ii; ?>" /> <?php } ?>
            <?php if ($yto['ytPass']!='') { ?>
            <?php wp_nonce_field( 'rePostToYT', 'rePostToYT_wpnonce' ); ?>
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('YT', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>              <?php } 
            ?><div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div></div><?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'YT'; $lcode = 'yt'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apYTUName']) && $pval['apYTUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apYTUName']))   $options[$ii]['ytUName'] = trim($pval['apYTUName']);
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apYTPass']))    $options[$ii]['ytPass'] = 'n5g9a'.nsx_doEncode($pval['apYTPass']); else $options[$ii]['ytPass'] = '';  
        if (isset($pval['apYTPage']))    $options[$ii]['ytPageID'] = trim($pval['apYTPage']);  
        if (isset($pval['ytCommID']))    $options[$ii]['ytCommID'] = trim($pval['ytCommID']);  
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']);
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
                      
        if (isset($pval['postType']))   $options[$ii]['postType'] = $pval['postType'];         
        if (isset($pval['apYTMsgFrmt'])) $options[$ii]['ytMsgFormat'] = trim($pval['apYTMsgFrmt']);                                                  
        if (isset($pval['apDoYT']))      $options[$ii]['doYT'] = $pval['apDoYT']; else $options[$ii]['doYT'] = 0; 
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      }
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID;
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapYT', true));  if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
        $doYT = $ntOpt['doYT'] && (is_array($pMeta) || $ntOpt['catSel']!='1');   
        $isAvailYT =  $ntOpt['ytUName']!='' && $ntOpt['ytPass']!='';   $ytMsgFormat = htmlentities($ntOpt['ytMsgFormat'], ENT_COMPAT, "UTF-8");      
        if(!isset($ntOpt['postType']) || $ntOpt['postType']=='') {
            if ((int)$ntOpt['imgPost'] == 1) $ntOpt['postType'] = 'I';
            if ((int)$ntOpt['ytAttch'] == 1 || $isNew) $ntOpt['postType'] = 'A';
        } $ytPostType = $ntOpt['postType'];
      ?>  
      <tr><th style="text-align:left;" colspan="2"><?php if ( $ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='' )  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_YT<?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if ($isAvailYT) { ?><input class="nxsGrpDoChb" value="1" id="doYT<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="yt[<?php echo $ii; ?>][doYT]" <?php if ((int)$doYT == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="yt[<?php echo $ii; ?>][doYT]" value="<?php echo $doYT;?>"> <?php } ?> <?php } ?>
      
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/yt16.png);">YouTube - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailYT) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToYT_repostButton" id="rePostToYT_button" value="<?php _e('Repost to YouTube', 'nxs_snap') ?>" />
                    <?php wp_nonce_field( 'rePostToYT', 'rePostToYT_wpnonce' ); } ?>
                    
                    <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) { 
                        
                        ?> <span id="pstdYT<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
                      <a style="font-size: 10px;" href="<?php echo $ntOpt['ytPageID']; ?>" target="_blank"><?php $nType="YouTube"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>                
                
                <?php if (!$isAvailYT) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your YouTube Account to AutoPost to YouTube</b>
                <?php } elseif ($post->post_status != "puZblish") { ?> 
                
                
                <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top;  padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Message Format:', 'nxs_snap') ?></th>
                <td>
                
                 <?php if (1==1) { ?>
                <textarea cols="150" rows="1" id="yt<?php echo $ii; ?>SNAPformat" name="yt[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#yt<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apYTMsgFrmt<?php echo $ii; ?>');"><?php echo $ytMsgFormat ?></textarea>
                <?php } else { ?>
                <input value="<?php echo $ytMsgFormat ?>" type="text" name="yt[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apYTMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apYTMsgFrmt".$ii); ?>
                <?php } ?>
                
                
                </td></tr>
           <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = ''; 
    if (isset($pMeta['SNAPformat'])) $optMt['ytMsgFormat'] = $pMeta['SNAPformat'];   
    if (isset($pMeta['postType'])) $optMt['postType'] = $pMeta['postType'];
    if (isset($pMeta['doYT'])) $optMt['doYT'] = $pMeta['doYT'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doYT'] = 0; } 
    if (isset($pMeta['SNAPincludeYT']) && $pMeta['SNAPincludeYT'] == '1' ) $optMt['doYT'] = 1;  
    return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToYT_ajax")) {
  function nxs_rePostToYT_ajax() { check_ajax_referer('rePostToYT');  $postID = $_POST['id']; global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
    foreach ($options['yt'] as $ii=>$two) if ($ii==$_POST['nid']) {   $two['ii'] = $ii; $two['pType'] = 'aj'; //if ($two['ytPageID'].$two['ytUName']==$_POST['nid']) {  
      $ytpo =  get_post_meta($postID, 'snapYT', true); $ytpo =  maybe_unserialize($ytpo);// prr($ytpo);
      if (is_array($ytpo) && isset($ytpo[$ii]) && is_array($ytpo[$ii])){ $ntClInst = new nxs_snapClassYT(); $two = $ntClInst->adjMetaOpt($two, $ytpo[$ii]); } 
      $result = nxs_doPublishToYT($postID, $two); if ($result == 200) die("Successfully sent your post to YouTube."); else die($result);        
    }    
  }
}  

if (!function_exists("nxs_doPublishToYT")) { //## Second Function to Post to G+
  function nxs_doPublishToYT($postID, $options){ $ntCd = 'YT'; $ntCdL = 'yt'; $ntNm = 'YouTube';   global $nxs_gCookiesArr;
      // $backtrace = debug_backtrace(); nxs_addToLogN('W', 'Enter', $ntCd, 'I am here - '.$ntCd."|".print_r($backtrace, true), ''); 
      //if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToYT',  array($postID, $options));
      if(!function_exists('doConnectToGooglePlus2') || !function_exists('doPostToGooglePlus2')) { nxs_addToLogN('E', 'Error', $ntCd, '-=ERROR=- No G+ API Lib Detected', ''); return "No G+ API Lib Detected";}
      $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
      $logNT = '<span style="color:#800000">YouTube</span> - '.$options['nName'];      
      $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
      if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') {  sleep(5);
           nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$uqID); return;
        }
      }         
      if ($postID=='0') echo "Testing ... <br/><br/>";  else { nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1'));  $post = get_post($postID); if(!$post) return;}
      $ytMsgFormat = $options['ytMsgFormat'];  $msg = nsFormatMessage($ytMsgFormat, $postID);// prr($msg); echo $postID;
      $extInfo = ' | PostID: '.$postID." - ".$post->post_title;      
      $email = $options['ytUName'];  $pass = substr($options['ytPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['ytPass'], 5)):$options['ytPass'];                   
      
      $loginError = doConnectToGooglePlus2($email, $pass, 'YT');  
      if ($loginError!==false) {if ($postID=='0') echo $loginError; nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($loginError, true)." - BAD USER/PASS", $extInfo); return "BAD USER/PASS";} 
      $url =  get_permalink($postID); if(trim($url)=='') $url = home_url();  $vids = nsFindVidsInPost($post); if (count($vids)>0) $vUrl = $vids[0];
      $ret = doPostToYouTube($msg, $options['ytPageID'], $vUrl); //prr($ret);
      if ($ret=='OK') $ret = array("code"=>"OK", "post_id"=>'');
      if ( (!is_array($ret)) && $ret!='OK') { if ($postID=='0') prr($ret); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo);} 
        else if ($postID=='0')  { nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); echo _e('OK - Message Posted, please see your YouTube Page', 'nxs_snap'); } else 
          { nxs_metaMarkAsPosted($postID, 'YT', $options['ii'], array('isPosted'=>'1', 'pgID'=>$ret['post_id'], 'pDate'=>date('Y-m-d H:i:s'))); nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo); }
      if ($ret['code']=='OK') return 200; else return $ret;
  } 
}  
?>