<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'PN', 'lcode'=>'pn', 'name'=>'Pinterest');

if (!class_exists("nxs_snapClassPN")) { class nxs_snapClassPN { var $ntInfo = array('code'=>'PN', 'lcode'=>'pn', 'name'=>'Pinterest', 'defNName'=>'pnUName', 'tstReq' => false);
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){  global $nxs_plurl;  $ntInfo = $this->ntInfo; 
    $fMsg = 'Pinterest doesn\'t have a built-in API for automated posts yet. <br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/pinterest-automated-posting">library module</a> to be able to publish your content to Pinterest.'; 
    $ntParams = array('ntInfo'=>$ntInfo, 'nxs_plurl'=>$nxs_plurl, 'ntOpts'=>$ntOpts, 'chkField'=>'apPNUName', 'checkFunc' => array('funcName'=>'doPostToPinterest', 'msg'=>$fMsg)); nxs_showListRow($ntParams);   ?>    
   <?php 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($mgpo){ $po = array('nName'=>'', 'doPN'=>'1', 'pnUName'=>'', 'pnBoard'=>'', 'gpAttch'=>'', 'cImgURL'=>'R', 'pnPass'=>'', 'pnDefImg'=>'', 'pnMsgFormat'=>'', 'pnBoard'=>'', 'pnBoardsList'=>'');
    $po['ntInfo']= array('lcode'=>'pn'); $this->showNTSettings($mgpo, $po, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; $nt = $options['ntInfo']['lcode']; $ntU = strtoupper($nt); 
    if (!isset($options['nHrs'])) $options['nHrs'] = 0; if (!isset($options['nMin'])) $options['nMin'] = 0;  if (!isset($options['catSel'])) $options['catSel'] = 0;  if (!isset($options['catSelEd'])) $options['catSelEd'] = ''; 
    if (!isset($options['nDays'])) $options['nDays'] = 0; if (!isset($options['qTLng'])) $options['qTLng'] = '';  ?>
             <div id="doPN<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>">     <input type="hidden" name="apDoSPN<?php echo $ii; ?>" value="0" id="apDoSPN<?php echo $ii; ?>" />         
             
             <?php if (!function_exists('doPostToPinterest') || (defined('d1') && d1==1)) {?><span style="color:#580000; font-size: 16px;"><br/><br/>
            <b>Pinterest API Library not found</b>
             <br/><br/> Pinterest doesn't have a built-in API for automated posts yet.  <br/><br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/pinterest-automated-posting"><b>API Library Module</b></a> to be able to publish your content to Pinterest.</span></div>
            
            <?php return; }; ?>
             
           
            <div id="doPN<?php echo $ii; ?>Div" style="margin-left: 10px;"> <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/pn16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-pinterest-social-networks-auto-poster-wordpress/"><?php $nType="Pinterest"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easily identify it', 'nxs_snap'); ?></i> </div><input name="pn[<?php echo $ii; ?>][nName]" id="pnnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('pn', $ii, $options['qTLng']); ?>
            
             <br/>
    <ul class="nsx_tabs">
    <li><a href="#nsx<?php echo $nt.$ii ?>_tab1"><?php _e('Account Info', 'nxs_snap'); ?></a></li>    
    <?php if (!$isNew) { ?>  <li><a href="#nsx<?php echo $nt.$ii ?>_tab2"><?php _e('Advanced', 'nxs_snap'); ?></a></li>  <?php } ?>
    </ul>
    <div class="nsx_tab_container"><?php /* ######################## Account Tab ####################### */ ?>
    <div id="nsx<?php echo $nt.$ii ?>_tab1" class="nsx_tab_content" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $nt; ?>-bg.png); background-repeat: no-repeat;  background-position:90% 10%;">
    
    
                  
            <div style="width:100%;"><strong>Pinterest Email:</strong> </div><input name="pn[<?php echo $ii; ?>][apPNUName]" id="apPNUName<?php echo $ii; ?>" class="apPNUName<?php echo $ii; ?>"  style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['pnUName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
            <div style="width:100%;"><strong>Pinterest Password:</strong> </div><input name="pn[<?php echo $ii; ?>][apPNPass]" id="apPNPass<?php echo $ii; ?>" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($options['pnPass'], 0, 5)=='g9c1a'?nsx_doDecode(substr($options['pnPass'], 5)):$options['pnPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/>                

                <strong>Clickthrough URL:</strong> 
<p style="margin-bottom: 20px;margin-top: 5px;">
<input type="radio" name="pn[<?php echo $ii; ?>][cImgURL]" value="R" <?php if ( !isset($options['cImgURL']) || $options['cImgURL'] == '' || $options['cImgURL'] == 'R') echo 'checked="checked"'; ?> /> Regular Post URL&nbsp;&nbsp;
<!-- <input type="radio" name="pn[<?php echo $ii; ?>][cImgURL]" value="S" <?php if ($options['cImgURL'] == 'S') echo 'checked="checked"'; ?> /> Shortened Post URL&nbsp;&nbsp; -->
<input type="radio" name="pn[<?php echo $ii; ?>][cImgURL]" value="N" <?php if ($options['cImgURL'] == 'N') echo 'checked="checked"'; ?> /> No Clickthrough URL&nbsp;&nbsp;

            <div style="width:100%;"><strong>Default Image to Pin:</strong> 
            <p style="font-size: 11px; margin: 0px;">If your post does not have any images this will be used instead.</p>
            </div><input name="pn[<?php echo $ii; ?>][apPNDefImg]" id="apPNDefImg" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['pnDefImg'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /> 
            <br/><br/>            
            
            <div style="width:100%;"><strong>Board:</strong> 
            Please <a href="#" onclick="nxs_getPNBoards(jQuery('<?php if ($isNew) echo "#nsx_addNT "; ?>#apPNUName<?php echo $ii; ?>').val(),jQuery('<?php if ($isNew) echo "#nsx_addNT "; ?>#apPNPass<?php echo $ii; ?>').val(), '<?php echo $ii; ?>'); return false;">click here to retrieve your boards</a>
            </div>
            <img id="pnLoadingImg<?php echo $ii; ?>" style="display: none;" src='<?php echo $nxs_plurl; ?>img/ajax-loader-sm.gif' />
            <select name="pn[<?php echo $ii; ?>][apPNBoard]" id="apPNBoard<?php echo $ii; ?>">
            <?php if ($options['pnBoardsList']!=''){ $gPNBoards = $options['pnBoardsList']; if ( base64_encode(base64_decode($gPNBoards)) === $gPNBoards) $gPNBoards = base64_decode($gPNBoards); 
              if ($options['pnBoard']!='') $gPNBoards = str_replace($options['pnBoard'].'"', $options['pnBoard'].'" selected="selected"', $gPNBoards);  echo $gPNBoards;} else { ?>
              <option value="0">None(Click above to retrieve your boards)</option>
            <?php } ?>
            </select>
            
            <br/><br/>            
            
            <div style="margin-bottom: 5px; margin-left: 0px; "><input value="1"  id="isAttachVid" type="checkbox" name="pn[<?php echo $ii; ?>][isAttachVid]"  <?php if (isset($options['isAttachVid']) && (int)$options['isAttachVid'] == 1) echo "checked"; ?> />    <strong><?php _e('If post has a video use it instead of image', 'nxs_snap'); ?></strong> <i><?php _e('Video will be pinned instead of featured image. Only Youtube is supported at this time.', 'nxs_snap'); ?></i>
    <br/></div>
            
            <div id="altFormat" style="">
              <div style="width:100%;"><strong id="altFormatText"><?php _e('Message text Format', 'nxs_snap'); ?>:</strong>  <a href="#" id="apPNMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apPNMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>             
              </div>
              
               <textarea cols="150" rows="3" id="pn<?php echo $ii; ?>SNAPformat" name="pn[<?php echo $ii; ?>][apPNMsgFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#pn<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apPNMsgFrmt<?php echo $ii; ?>');"><?php if ($options['pnMsgFormat']!='') _e(apply_filters('format_to_edit', htmlentities($options['pnMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap');  else echo "%TITLE% - %URL%"; ?></textarea>
              
              <?php nxs_doShowHint("apPNMsgFrmt".$ii); ?>
            </div><br/>    
            <?php if ($isNew) { ?> <input type="hidden" name="pn[<?php echo $ii; ?>][apDoPN]" value="1" id="apDoNewPN<?php echo $ii; ?>" /> <?php } ?>
            <?php if ($options['pnPass']!='') { ?>
            
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('PN', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>         
            <?php } ?>
            
            </div>
      <?php /* ######################## Advanced Tab ####################### */ ?>
   <?php if (!$isNew) { ?>  <div id="nsx<?php echo $nt.$ii ?>_tab2" class="nsx_tab_content">
    
   <?php nxs_showCatTagsCTFilters($nt, $ii, $options); 
      nxs_addPostingDelaySelV3($nt, $ii, $options['nHrs'], $options['nMin'], $options['nDays']); 
      nxs_showRepostSettings($nt, $ii, $options); ?>
            
            
    </div>   <?php } ?> <?php /* #### End of Tab #### */ ?>
    </div><br/> <?php /* #### End of Tabs #### */ ?>
    
    <div class="submitX nxclear" style="padding-bottom: 0px;"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div>
            
            </div>
  </div>
            <?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ $code = $this->ntInfo['code'];
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apPNUName']) && $pval['apPNUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apDoPN']))   $options[$ii]['doPN'] = $pval['apDoPN']; else $options[$ii]['doPN'] = 0;
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apPNUName']))   $options[$ii]['pnUName'] = trim($pval['apPNUName']);
        if (isset($pval['apPNPass']))    $options[$ii]['pnPass'] = 'g9c1a'.nsx_doEncode($pval['apPNPass']); else $options[$ii]['pnPass'] = '';
        if (isset($pval['apPNBoard']))   $options[$ii]['pnBoard'] = trim($pval['apPNBoard']);                
        if (isset($pval['apPNDefImg']))  $options[$ii]['pnDefImg'] = trim($pval['apPNDefImg']);
        if (isset($pval['isAttachVid']))   $options[$ii]['isAttachVid'] = $pval['isAttachVid']; else $options[$ii]['isAttachVid'] = 0;
        if (isset($pval['cImgURL']))        $options[$ii]['cImgURL'] = trim($pval['cImgURL']);   
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']); else $options[$ii]['catSel'] = 0;
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
        
        if (isset($pval['apPNMsgFrmt'])) $options[$ii]['pnMsgFormat'] = trim($pval['apPNMsgFrmt']);     
        
        $options[$ii] = nxs_adjRpst($options[$ii], $pval);       
        
        if (isset($pval['delayDays'])) $options[$ii]['nDays'] = trim($pval['delayDays']);
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      } elseif ( count($pval)==1 ) if (isset($pval['apDo'.$code])) $options[$ii]['do'.$code] = $pval['apDo'.$code]; else $options[$ii]['do'.$code] = 0; 
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; $nt = 'pn'; $ntU = 'PN'; //prr($ntOpts);
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapPN', true)); if (is_array($pMeta) && !empty($pMeta[$ii])) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
        if (empty($ntOpt['imgToUse'])) $ntOpt['imgToUse'] = ''; if (empty($ntOpt['urlToUse'])) $ntOpt['urlToUse'] = '';
        $doPN = $ntOpt['doPN'] && (is_array($pMeta) || $ntOpt['catSel']!='1');   $imgToUse = $ntOpt['imgToUse'];  $urlToUse = $ntOpt['urlToUse'];  
        $isAvailPN =  $ntOpt['pnUName']!='' && $ntOpt['pnPass']!=''; $pnMsgFormat = htmlentities($ntOpt['pnMsgFormat'], ENT_COMPAT, "UTF-8");        
      ?>  
      <tr><th style="text-align:left;" colspan="2">
      <?php if ($ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='')  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if (!empty($ntOpt['tagsSelX'])) { ?>  <input type="hidden" class="nxs_TG" id="nxs_TG_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['tagsSelX']; ?>" /> <?php } ?>
      <?php if ($isAvailPN) { ?><input class="nxsGrpDoChb" value="1" id="doPN<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="pn[<?php echo $ii; ?>][doPN]" <?php if ((int)$doPN == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="pn[<?php echo $ii; ?>][doPN]" value="<?php echo $doPN;?>"> <?php } ?> <?php } ?>
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/pn16.png);">Pinterest - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailPN) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;"  type="button" class="button" name="rePostToPN_repostButton" id="rePostToPN_button" value="<?php _e('Repost to Pinterest', 'nxs_snap') ?>" />
                    <?php } ?>

                    <?php  if (is_array($pMeta) && !empty($pMeta[$ii]) && isset($pMeta[$ii]['postURL']) ) {                         
                        ?> <span id="pstdPN<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
          <a style="font-size: 10px;" href="<?php echo $pMeta[$ii]['postURL']; ?>" target="_blank"><?php $nType="Pinterest"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>                
                
                <?php if (!$isAvailPN) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Pinterest Account to AutoPost to Pinterest</b>
                <?php } else { if ($post->post_status != "publish" && function_exists('nxs_doSMAS5') ) { $ntOpt['postTime'] = get_post_time('U', false, $post_id); nxs_doSMAS5($nt, $ii, $ntOpt); } ?>
                
                <?php if ($ntOpt['rpstOn']=='1') { ?> 
                
                <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow">
                <input value="0"  type="hidden" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"/><input value="nxsi<?php echo $ii; ?>pn" type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"  <?php if (!empty($ntOpt['rpstPostIncl'])) echo "checked"; ?> />
                </th>
                <td> <?php _e('Include in "Auto-Reposting" to this network.', 'nxs_snap') ?>                
                </td></tr> <?php } ?>
                
                <tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;">Select Board</th>
                <td><select name="pn[<?php echo $ii; ?>][apPNBoard]" id="apPNBoard">
            <?php if (!empty($ntOpt['pnBoardsList'])){ $gPNBoards = $ntOpt['pnBoardsList']; if ( base64_encode(base64_decode($gPNBoards)) === $gPNBoards) $gPNBoards = base64_decode($gPNBoards); 
              if ($ntOpt['pnBoard']!='') $gPNBoards = str_replace($ntOpt['pnBoard'].'"', $ntOpt['pnBoard'].'" selected="selected"', $gPNBoards);  echo $gPNBoards;} else { ?>
              <option value="0">None(Click above to retrieve your boards)</option>
            <?php } ?>
            </select></td>
                </tr> 
                              
                <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow"><?php _e('Text Message Format:', 'nxs_snap') ?></th>
                <td>                
                <textarea cols="150" rows="1" id="pn<?php echo $ii; ?>SNAPformat" name="pn[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#pn<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apPNMsgFrmt<?php echo $ii; ?>');"><?php echo $pnMsgFormat; ?></textarea>
                <?php nxs_doShowHint("apPNMsgFrmt".$ii); ?></td></tr>                
                  
                 <?php /* ## Select Image & URL ## */ nxs_showImgToUseDlg($nt, $ii, $imgToUse); nxs_showURLToUseDlg($nt, $ii, $urlToUse); ?>               
                <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){  if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['pnMsgFormat'] = $pMeta['SNAPformat'];      
     if (isset($pMeta['imgToUse'])) $optMt['imgToUse'] = $pMeta['imgToUse']; if (isset($pMeta['urlToUse'])) $optMt['urlToUse'] = $pMeta['urlToUse']; 
     if (isset($pMeta['timeToRun']))  $optMt['timeToRun'] = $pMeta['timeToRun'];  if (isset($pMeta['rpstPostIncl']))  $optMt['rpstPostIncl'] = $pMeta['rpstPostIncl'];    
     if (isset($pMeta['doPN'])) $optMt['doPN'] = $pMeta['doPN'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doPN'] = 0; }
     if (isset($pMeta['apPNBoard']) && $pMeta['apPNBoard']!='' && $pMeta['apPNBoard']!='0') $optMt['pnBoard'] = $pMeta['apPNBoard']; 
     if (isset($pMeta['SNAPincludePN']) && $pMeta['SNAPincludePN'] == '1' ) $optMt['doPN'] = 1;  
     return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToPN_ajax")) {
  function nxs_rePostToPN_ajax() { check_ajax_referer('nxsSsPageWPN');  $postID = $_POST['id']; $options = get_option('NS_SNAutoPoster');  
    foreach ($options['pn'] as $ii=>$two) if ($ii==$_POST['nid']) {    $two['ii'] = $ii; $two['pType'] = 'aj'; //if ($two['gpPageID'].$two['gpUName']==$_POST['nid']) {  
      $po =  get_post_meta($postID, 'snapPN', true); $po =  maybe_unserialize($po);// prr($gppo);
      if (is_array($po) && isset($po[$ii]) && is_array($po[$ii])){ $ntClInst = new nxs_snapClassPN(); $two = $ntClInst->adjMetaOpt($two, $po[$ii]); }
      $result = nxs_doPublishToPN($postID, $two); if ($result == 200) die("Successfully sent your post to Pinterest."); else die($result);        
    }    
  }
}  

if (!function_exists("nxs_doPublishToPN")) { //## Second Function to Post to G+
  function nxs_doPublishToPN($postID, $options){ global $nxs_gCookiesArr, $plgn_NS_SNAutoPoster; $ntCd = 'PN'; $ntCdL = 'pn'; $ntNm = 'Pinterest';   $price = ''; 
    if (!is_array($options)) $options = maybe_unserialize(get_post_meta($postID, $options, true));
    // $backtrace = debug_backtrace(); nxs_addToLogN('W', 'Enter', $ntCd, 'I am here - '.$ntCd."|".print_r($backtrace, true), ''); 
    //if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToPN',  array($postID, $options)); 
    $addParams = nxs_makeURLParams(array('NTNAME'=>$ntNm, 'NTCODE'=>$ntCd, 'POSTID'=>$postID, 'ACCNAME'=>$options['nName'])); 
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); 
    if (empty($options['imgToUse'])) $options['imgToUse'] = ''; if (empty($options['imgSize'])) $options['imgSize'] = ''; if (empty($options['cImgURL'])) $options['cImgURL'] = 'R'; 
    
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10));
    $logNT = '<span style="color:#FA5069">Pinterest</span> - '.$options['nName'];
    $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);  $isAttachVid = $options['isAttachVid']; 
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') {  sleep(5);
         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate |'.$uqID); return;
        }
    }
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $options['pnMsgFormat'] = 'Test Post from '.$blogTitle; $urlToGo = home_url(); 
      if ($options['pnDefImg']!='') $imgURL = $options['pnDefImg']; else $imgURL ="http://direct.gtln.us/img/nxs/NXS-Lama.jpg"; 
    }
    else { $post = get_post($postID); if(!$post) return; $options['pnMsgFormat'] = nsFormatMessage( $options['pnMsgFormat'], $postID, $addParams); 
      //## MyURL - URLToGo code
      if (!isset($options['urlToUse']) || trim($options['urlToUse'])=='') $myurl = trim(get_post_meta($postID, 'snap_MYURL', true)); if ($myurl!='') $options['urlToUse'] = $myurl;
      if (isset($options['urlToUse']) && trim($options['urlToUse'])!='') { $urlToGo = $options['urlToUse']; $options['useFBGURLInfo'] = true; } else $urlToGo = get_permalink($postID);      
      if($addParams!='') $urlToGo .= (strpos($urlToGo,'?')!==false?'&':'?').$addParams;  if (is_object($post)) $urlToGo = apply_filters( 'nxs_adjust_ex_url', $urlToGo, $post->post_content); 
            
      if (!empty($options['imgToUse'])) $imgURL = $options['imgToUse']; else $imgURL = nxs_getPostImage($postID, 'full', $options['pnDefImg']); if (preg_match("/noImg.\.png/i", $imgURL)) $imgURL = ''; 
      if ($isAttachVid=='1') { $vids = nsFindVidsInPost($post); if (count($vids)>0) { $vidURL = 'http://www.youtube.com/v/'.$vids[0]; $imgURL = 'http://img.youtube.com/vi/'.$vids[0].'/0.jpg'; }}         
      $extInfo = ' | PostID: '.$postID." - ".(is_object($post))?$post->post_title:''; 
    }        
    if ($options['cImgURL']=='S') $options['cImgURL'] = 'R'; //## Pinterest no longer allows shorthened URLs.
    //## Post                 
    $message = array('siteName'=>$blogTitle, 'tags'=>'', 'url'=>$urlToGo, 'imageURL'=>$imgURL);// prr($message);
    //## Actual Post
    $ntToPost = new nxs_class_SNAP_PN(); $ret = $ntToPost->doPostToNT($options, $message);
    //## Save Session
    if (serialize($nxs_gCookiesArr)!=$options['ck']) { global $plgn_NS_SNAutoPoster;  $gOptions = $plgn_NS_SNAutoPoster->nxs_options; // prr($gOptions['pn']);
        if (isset($options['ii']) && $options['ii']!=='')  { $gOptions['pn'][$options['ii']]['ck'] = serialize($nxs_gCookiesArr); update_option('NS_SNAutoPoster', $gOptions);  }        
        else foreach ($gOptions['pn'] as $ii=>$gpn) { $result = array_diff($options, $gpn);
          if (!is_array($result) || count($result)<1) { $gOptions['pn'][$ii]['ck'] = serialize($nxs_gCookiesArr); update_option('NS_SNAutoPoster', $gOptions); break; }
        }        
    }    
    //## Process Results
    if (!is_array($ret) || $ret['isPosted']!='1') { //## Error 
      if ($postID=='0') prr($ret); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo); 
    } else {  // ## All Good - log it.
      if ($postID=='0')  { nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); echo _e('OK - Message Posted, please see your '.$logNT.' Page. ', 'nxs_snap'); } 
        else  { nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPosted'=>'1', 'pgID'=>$ret['postID'], 'postURL'=>$ret['postURL'], 'pDate'=>date('Y-m-d H:i:s'))); 
        $extInfo .= ' | <a href="'.$ret['postURL'].'" target="_blank">Post Link</a>';  nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo); }
    } //prr($ret);
    //## Return Result
    if ($ret['isPosted']=='1') return 200; else return print_r($ret, true);     
  }
}  
?>