<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'YT', 'lcode'=>'yt', 'name'=>'YouTube');

if (!class_exists("nxs_snapClassYT")) { class nxs_snapClassYT {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){  global $nxs_plurl; $ntInfo = array('code'=>'YT', 'lcode'=>'yt', 'name'=>'YouTube', 'defNName'=>'ytUName', 'tstReq' => false); ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> 
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php if(!function_exists('doPostToGooglePlus')) {?> YouTube doesn't have a built-in API for automated posts yet. The current <a href="http://developers.google.com/+/api/">YouTube API</a> is "Read Only" and can't be used for posting.  <br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/google-plus-automated-posting">library module</a> to be able to publish your content to YouTube. 
        <?php } else foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = $pbo[$ntInfo['defNName']]; ?>
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
          </p><div id="nxsNTSetDiv<?php echo $ntInfo['code'].$indx; ?>"></div><?php // $pbo['ntInfo'] = $ntInfo; $this->showNTSettings($indx, $pbo);             
        }?>
      </div>
    </div> <?php 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($myto){ $options = array('nName'=>'', 'doYT'=>'1', 'ytUName'=>'', 'ytPageID'=>'', 'ytGPPageID'=>'', 'postType'=>'A', 'ytPass'=>''); $options['ntInfo']= array('lcode'=>'yt'); $this->showNTSettings($myto, $options, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; $nt = $options['ntInfo']['lcode']; $ntU = strtoupper($nt); 
    if (!isset($options['nHrs'])) $options['nHrs'] = 0; if (!isset($options['nMin'])) $options['nMin'] = 0;  if (!isset($options['catSel'])) $options['catSel'] = 0;  if (!isset($options['catSelEd'])) $options['catSelEd'] = ''; 
    if (!isset($options['nDays'])) $options['nDays'] = 0; if (!isset($options['qTLng'])) $options['qTLng'] = ''; if (!isset($options['ytGPPageID'])) $options['ytGPPageID'] = '';  ?>
            <div id="doYT<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>">     <input type="hidden" name="apDoSYT<?php echo $ii; ?>" value="0" id="apDoSYT<?php echo $ii; ?>" />             
            <?php if(!function_exists('doPostToGooglePlus')) {?><span style="color:#580000; font-size: 16px;"><br/><br/>
            <b><?php _e('YouTube API Library not found', 'nxs_snap'); ?></b>
             <br/><br/> <?php _e('YouTube doesn\'t have a built-in API for automated posts yet.', 'nxs_snap'); ?> <br/><?php _e('The current <a target="_blank" href="http://developers.google.com/+/api/">YouTube API</a> is "Read Only" and can\'t be used for posting.  <br/><br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/google-plus-automated-posting"><b>API Library Module</b></a> to be able to publish your content to YouTube.', 'nxs_snap'); ?></span></div>
            <?php return; }; ?>            
            <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/yt16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/instructions/youtube-social-networks-auto-poster-wordpress-setup-installation/"><?php $nType="YouTube"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
            
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easily identify it', 'nxs_snap'); ?></i> </div><input name="yt[<?php echo $ii; ?>][nName]" id="ytnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('yt', $ii, $options['qTLng']); ?>
            
              <br/>
    <ul class="nsx_tabs">
    <li><a href="#nsx<?php echo $nt.$ii ?>_tab1"><?php _e('Account Info', 'nxs_snap'); ?></a></li>    
    <?php if (!$isNew) { ?>  <li><a href="#nsx<?php echo $nt.$ii ?>_tab2"><?php _e('Advanced', 'nxs_snap'); ?></a></li>  <?php } ?>
    </ul>
    <div class="nsx_tab_container"><?php /* ######################## Account Tab ####################### */ ?>
    <div id="nsx<?php echo $nt.$ii ?>_tab1" class="nsx_tab_content" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $nt; ?>-bg.png); background-repeat: no-repeat;  background-position:90% 10%;">
    
            
            <div style="width:100%;"><strong>YouTube(Google) Username:</strong> </div><input name="yt[<?php echo $ii; ?>][apYTUName]" id="apYTUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['ytUName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
            <div style="width:100%;"><strong>YouTube(Google) Password:</strong> </div><input name="yt[<?php echo $ii; ?>][apYTPass]" id="apYTPass" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($options['ytPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['ytPass'], 5)):$options['ytPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/>                
            <p><div style="width:100%;"><strong>YouTube Channel Page URL:</strong> 
            
            </div><input name="yt[<?php echo $ii; ?>][apYTPage]" id="apYTPage" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['ytPageID'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /> 
            <br/><br/>
            
            <p><div style="width:100%;"><i style="color: gray;"><strong >Google+ Page ID:</strong>&nbsp;Fill this only if you are posting to youTube as your Google+ page. Please leave this empty otherwise.</i>
            
            </div><input name="yt[<?php echo $ii; ?>][ytGPPageID]" id="ytGPPageID" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['ytGPPageID'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /> 
            <br/><br/>
            
            <div id="altFormat" style="">
              <div style="width:100%;"><strong id="altFormatText"><?php _e('Message text Format', 'nxs_snap'); ?>:</strong> (<a href="#" id="apYTMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apYTMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)
              </div>
              
              <textarea cols="150" rows="3" id="yt<?php echo $ii; ?>SNAPformat" name="yt[<?php echo $ii; ?>][apYTMsgFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#yt<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apYTMsgFrmt<?php echo $ii; ?>');"><?php if ($isNew) _e("New post: %TITLE% - %URL%", 'nxs_snap'); else _e(apply_filters('format_to_edit', htmlentities($options['ytMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?></textarea>
              
              <?php nxs_doShowHint("apYTMsgFrmt".$ii); ?>
            </div><br/>          
          
            <?php if ($isNew) { ?> <input type="hidden" name="yt[<?php echo $ii; ?>][apDoYT]" value="1" id="apDoNewYT<?php echo $ii; ?>" /> <?php } ?>
            <?php if ($options['ytPass']!='') { ?>
            
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('YT', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>              <?php } 
            ?></div>
            <?php /* ######################## Advanced Tab ####################### */ ?>
   <?php if (!$isNew) { ?>   <div id="nsx<?php echo $nt.$ii ?>_tab2" class="nsx_tab_content">
    
   <?php nxs_showCatTagsCTFilters($nt, $ii, $options); 
      nxs_addPostingDelaySelV3($nt, $ii, $options['nHrs'], $options['nMin'], $options['nDays']); 
      nxs_showRepostSettings($nt, $ii, $options); ?>
            
            
    </div>     <?php } ?> <?php /* #### End of Tab #### */ ?>
    </div><br/> <?php /* #### End of Tabs #### */ ?>
    
    <div class="submitX nxclear" style="padding-bottom: 0px;"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div></div><?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ $code = 'YT'; $lcode = 'yt'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apYTUName']) && $pval['apYTUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apYTUName']))   $options[$ii]['ytUName'] = trim($pval['apYTUName']);
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apYTPass']))    $options[$ii]['ytPass'] = 'n5g9a'.nsx_doEncode($pval['apYTPass']); else $options[$ii]['ytPass'] = '';  
        if (isset($pval['apYTPage']))    $options[$ii]['ytPageID'] = trim($pval['apYTPage']);  
        if (isset($pval['ytGPPageID']))    $options[$ii]['ytGPPageID'] = trim($pval['ytGPPageID']);  
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']); else $options[$ii]['catSel'] = 0;
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
                      
        if (isset($pval['postType']))   $options[$ii]['postType'] = $pval['postType'];         
        if (isset($pval['apYTMsgFrmt'])) $options[$ii]['ytMsgFormat'] = trim($pval['apYTMsgFrmt']);                                                  
        if (isset($pval['apDoYT']))      $options[$ii]['doYT'] = $pval['apDoYT']; else $options[$ii]['doYT'] = 0; 
        
        $options[$ii] = nxs_adjRpst($options[$ii], $pval);       
        
        if (isset($pval['delayDays'])) $options[$ii]['nDays'] = trim($pval['delayDays']);
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      } elseif ( count($pval)==1 ) if (isset($pval['apDo'.$code])) $options[$ii]['do'.$code] = $pval['apDo'.$code]; else $options[$ii]['do'.$code] = 0; 
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; $nt = 'yt'; $ntU = 'YT';
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapYT', true));  if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
        $doYT = $ntOpt['doYT'] && (is_array($pMeta) || $ntOpt['catSel']!='1');   
        $isAvailYT =  $ntOpt['ytUName']!='' && $ntOpt['ytPass']!='';   $ytMsgFormat = htmlentities($ntOpt['ytMsgFormat'], ENT_COMPAT, "UTF-8");              
      ?>  
      <tr><th style="text-align:left;" colspan="2">
      <?php if ($ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='')  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if (!empty($ntOpt['tagsSelX'])) { ?>  <input type="hidden" class="nxs_TG" id="nxs_TG_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['tagsSelX']; ?>" /> <?php } ?>
      <?php if ($isAvailYT) { ?><input class="nxsGrpDoChb" value="1" id="doYT<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="yt[<?php echo $ii; ?>][doYT]" <?php if ((int)$doYT == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="yt[<?php echo $ii; ?>][doYT]" value="<?php echo $doYT;?>"> <?php } ?> <?php } ?>
      
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/yt16.png);">YouTube - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailYT) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToYT_repostButton" id="rePostToYT_button" value="<?php _e('Repost to YouTube', 'nxs_snap') ?>" />
                    <?php } ?>
                    
                    <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) { 
                        
                        ?> <span id="pstdYT<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
                      <a style="font-size: 10px;" href="<?php echo $ntOpt['ytPageID']; ?>" target="_blank"><?php $nType="YouTube"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>                
                
                <?php if (!$isAvailYT) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your YouTube Account to AutoPost to YouTube</b>
                <?php } else { if ($post->post_status != "publish" && function_exists('nxs_doSMAS5') ) { nxs_doSMAS5($nt, $ii, $ntOpt); } ?>
                
                <?php if ($ntOpt['rpstOn']=='1') { ?> 
                
                <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow">
                <input value="0"  type="hidden" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"/><input value="nxsi<?php echo $ii; ?>yt" type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"  <?php if (!empty($ntOpt['rpstPostIncl'])) echo "checked"; ?> />
                </th>
                <td> <?php _e('Include in "Auto-Reposting" to this network.', 'nxs_snap') ?>                
                </td></tr> <?php } ?>
                
                
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
    if (isset($pMeta['imgToUse'])) $optMt['imgToUse'] = $pMeta['imgToUse'];      
    if (isset($pMeta['timeToRun']))  $optMt['timeToRun'] = $pMeta['timeToRun'];  if (isset($pMeta['rpstPostIncl']))  $optMt['rpstPostIncl'] = $pMeta['rpstPostIncl'];       
    if (isset($pMeta['postType'])) $optMt['postType'] = $pMeta['postType'];
    if (isset($pMeta['doYT'])) $optMt['doYT'] = $pMeta['doYT'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doYT'] = 0; } 
    if (isset($pMeta['SNAPincludeYT']) && $pMeta['SNAPincludeYT'] == '1' ) $optMt['doYT'] = 1;  
    return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToYT_ajax")) {
  function nxs_rePostToYT_ajax() { check_ajax_referer('nxsSsPageWPN');  $postID = $_POST['id']; global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
    foreach ($options['yt'] as $ii=>$two) if ($ii==$_POST['nid']) {   $two['ii'] = $ii; $two['pType'] = 'aj'; //if ($two['ytPageID'].$two['ytUName']==$_POST['nid']) {  
      $ytpo =  get_post_meta($postID, 'snapYT', true); $ytpo =  maybe_unserialize($ytpo);// prr($ytpo);
      if (is_array($ytpo) && isset($ytpo[$ii]) && is_array($ytpo[$ii])){ $ntClInst = new nxs_snapClassYT(); $two = $ntClInst->adjMetaOpt($two, $ytpo[$ii]); } 
      $result = nxs_doPublishToYT($postID, $two); if ($result == 200) die("Successfully sent your post to YouTube."); else die($result);        
    }    
  }
}  

if (!function_exists("nxs_doPublishToYT")) { //## Second Function to Post to G+
  function nxs_doPublishToYT($postID, $options){ $ntCd = 'YT'; $ntCdL = 'yt'; $ntNm = 'YouTube'; $post = '';  global $nxs_gCookiesArr; $vUrl = '';
      if (!is_array($options)) $options = maybe_unserialize(get_post_meta($postID, $options, true));
      // $backtrace = debug_backtrace(); nxs_addToLogN('W', 'Enter', $ntCd, 'I am here - '.$ntCd."|".print_r($backtrace, true), ''); 
      //if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToYT',  array($postID, $options));
      $addParams = nxs_makeURLParams(array('NTNAME'=>$ntNm, 'NTCODE'=>$ntCd, 'POSTID'=>$postID, 'ACCNAME'=>$options['nName'])); 
      $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); 
      if (empty($options['ytGPPageID'])) $options['ytGPPageID'] = ''; // if (empty($options['imgSize'])) $options['imgSize'] = '';
      if(!function_exists('doConnectToGooglePlus2') || !function_exists('doPostToGooglePlus2')) { nxs_addToLogN('E', 'Error', $ntCd, '-=ERROR=- No G+ API Lib Detected', ''); return "No G+ API Lib Detected";}
      $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
      $logNT = '<span style="color:#800000">YouTube</span> - '.$options['nName'];      
      $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
      if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') {  sleep(5);
           nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$uqID); return;
        }
      }         
      if ($postID=='0') $options['ytMsgFormat'] = 'Test Post, Please Ignore';  else { nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1'));  $post = get_post($postID); if(!$post) return;
        $options['ytMsgFormat'] = nsFormatMessage($options['ytMsgFormat'], $postID, $addParams);// prr($msg); echo $postID;
      }
      $extInfo = ' | PostID: '.$postID." - ".(is_object($post)?$post->post_title:'');
      
      //## Message & Format                 
      $vids = nsFindVidsInPost($post); if (count($vids)>0) $vUrl = $vids[0];
      $message = array('siteName'=>$blogTitle, 'videoURL'=>$vUrl);    
      //## Actual Post
      $ntToPost = new nxs_class_SNAP_YT(); $ret = $ntToPost->doPostToNT($options, $message);
      //## Process Results
      if (!is_array($ret) || $ret['isPosted']!='1') { //## Error 
        if ($postID=='0') prr($ret); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo); 
      } else {  // ## All Good - log it.
        if ($postID=='0')  { nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); echo _e('OK - Message Posted, please see your '.$logNT.' Page. ', 'nxs_snap'); } 
          else  { nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPosted'=>'1', 'pgID'=>$ret['postID'], 'pDate'=>date('Y-m-d H:i:s'))); nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo); }
      }
      //## Return Result
      if ($ret['isPosted']=='1') return 200; else return print_r($ret, true);       
  } 
}  
?>