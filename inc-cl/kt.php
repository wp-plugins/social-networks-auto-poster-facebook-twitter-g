<?php    
//## NextScripts Facebook Connection Class 
$nxs_snapAvNts[] = array('code'=>'KT', 'lcode'=>'kt', 'name'=>'Kippt');

if (!class_exists("nxs_snapClassKT")) { class nxs_snapClassKT { var $ntInfo = array('code'=>'KT', 'lcode'=>'kt', 'name'=>'Kippt', 'defNName'=>'uName', 'tstReq' => false);
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){  global $nxs_plurl, $nxs_snapSetPgURL;  $ntInfo = $this->ntInfo;    
    $ntParams = array('ntInfo'=>$ntInfo, 'nxs_plurl'=>$nxs_plurl, 'ntOpts'=>$ntOpts, 'chkField'=>'uPass'); nxs_showListRow($ntParams);  
  }   
  
  //#### Show NEW Settings Page
  function showNewNTSettings($mgpo){ $options = array('nName'=>'', 'doKT'=>'1', 'uName'=>'', 'uPass'=>''); $options['ntInfo']= array('lcode'=>'kt'); $this->showNTSettings($mgpo, $options, true);}  
  
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; $nt = $options['ntInfo']['lcode']; $ntU = strtoupper($nt); 
    if (!isset($options['nHrs'])) $options['nHrs'] = 0; if (!isset($options['nMin'])) $options['nMin'] = 0;  if (!isset($options['catSel'])) $options['catSel'] = 0;  if (!isset($options['catSelEd'])) $options['catSelEd'] = ''; 
    if (!isset($options['nDays'])) $options['nDays'] = 0; if (!isset($options['qTLng'])) $options['qTLng'] = '';  ?>
            <div id="doKT<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew){ ?> clNewNTSets<?php } ?>"> <input type="hidden" name="apDoSKT<?php echo $ii; ?>" value="0" id="apDoSKT<?php echo $ii; ?>" />          
            
             <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $nt; ?>16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/instructions/kippt-social-networks-auto-poster-setup-installation/"><?php $nType="Kippt"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
            
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easily identify it', 'nxs_snap'); ?></i> </div><input name="<?php echo $nt; ?>[<?php echo $ii; ?>][nName]" id="nName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel($nt, $ii, $options['qTLng']); ?>
            
              <br/>
    <ul class="nsx_tabs">
    <li><a href="#nsx<?php echo $nt.$ii ?>_tab1"><?php _e('Account Info', 'nxs_snap'); ?></a></li>    
    <?php if (!$isNew) { ?>  <li><a href="#nsx<?php echo $nt.$ii ?>_tab2"><?php _e('Advanced', 'nxs_snap'); ?></a></li>  <?php } ?>
    </ul>
    <div class="nsx_tab_container"><?php /* ######################## Account Tab ####################### */ ?>
    <div id="nsx<?php echo $nt.$ii ?>_tab1" class="nsx_tab_content" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $nt; ?>-bg.png); background-repeat: no-repeat;  background-position:90% 10%;">
    
    
    
           <div style="width:100%;"><strong><?php echo $nType; ?> Username:</strong> </div><input name="<?php echo $nt; ?>[<?php echo $ii; ?>][uName]" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['uName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />    
                       
            <div style="width:100%;"><strong><?php echo $nType; ?> Token:</strong> <span style="font-size: 11px; margin: 0px;">Get it from <a target="_blank" href="http://developers.kippt.com/">developers.kippt.com</a>.</span></div><input name="<?php echo $nt; ?>[<?php echo $ii; ?>][uPass]" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($options['uPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['uPass'], 5)):$options['uPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/>                
            
            <div style="width:100%;"><strong><?php echo $nType; ?> List:</strong> </div><input name="<?php echo $nt; ?>[<?php echo $ii; ?>][list]" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['list'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />    
            <br/><br/>
            <?php if ($isNew) { ?> <input type="hidden" name="<?php echo $nt; ?>[<?php echo $ii; ?>][apDo<?php echo $ntU; ?>]" value="1" id="apDoNew<?php echo $ntU; ?><?php echo $ii; ?>" /> <?php } ?><br/>            
            
            
            <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Title Format', 'nxs_snap'); ?>:</strong> (<a href="#" id="msgFrmtT<?php echo $ntU.$ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('msgFrmtT<?php echo $ntU.$ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)</div>               
              <input name="<?php echo $nt; ?>[<?php echo $ii; ?>][msgTFrmt]" style="width: 50%;" value="<?php if (!empty($options['msgTFrmt'])) _e(apply_filters('format_to_edit', htmlentities($options['msgTFrmt'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); else echo "%TITLE%"; ?>" onfocus="mxs_showFrmtInfo('msgFrmtT<?php echo $ntU.$ii; ?>');" /><?php nxs_doShowHint("msgFrmtT".$ntU.$ii); ?><br/> 
   
    
            
            <div id="altFormat" style="margin-left: 0px;">
              <div style="width:100%;"><strong id="altFormatText"><?php _e('Text Format', 'nxs_snap'); ?>:</strong> (<a href="#" id="msgFrmt<?php echo $ntU; ?><?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('msgFrmt<?php echo $ntU; ?><?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)
              </div>              
              <textarea cols="150" rows="3" id="<?php echo $nt; ?><?php echo $ii; ?>msgFrmt" name="<?php echo $nt; ?>[<?php echo $ii; ?>][msgFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#<?php echo $nt; ?><?php echo $ii; ?>msgFrmt').attr('rows', 6); mxs_showFrmtInfo('msgFrmt<?php echo $ntU.$ii; ?>');"><?php if ($isNew) _e("%EXCERPT%", 'nxs_snap'); else _e(apply_filters('format_to_edit', htmlentities($options['msgFrmt'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?></textarea><?php nxs_doShowHint("msgFrmt".$ntU.$ii); ?>
            </div>   
            
            <?php if ($options['uPass']!='') { ?>
            
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('<?php echo $ntU; ?>', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>            <?php } 
          ?>
          </div>
            <?php /* ######################## Advanced Tab ####################### */ ?>
  <?php if (!$isNew) { ?>  <div id="nsx<?php echo $nt.$ii ?>_tab2" class="nsx_tab_content">
    
    <?php nxs_showCatTagsCTFilters($nt, $ii, $options); 
      nxs_addPostingDelaySelV3($nt, $ii, $options['nHrs'], $options['nMin'], $options['nDays']); 
      nxs_showRepostSettings($nt, $ii, $options); ?>            
            
    </div>  <?php } ?> <?php /* #### End of Tab #### */ ?>
    </div><br/> <?php /* #### End of Tabs #### */ ?>
    
    <div class="submit nxclear" style="padding-bottom: 0px;"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div>
          </div><?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ $code = 'KT'; $lcode = 'kt'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['uName']) && $pval['uName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['uName']))   $options[$ii]['uName'] = trim($pval['uName']);
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['uPass']))    $options[$ii]['uPass'] = 'n5g9a'.nsx_doEncode($pval['uPass']); else $options[$ii]['uPass'] = '';  
        if (isset($pval['list']))          $options[$ii]['list'] = trim($pval['list']);
                
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']); else $options[$ii]['catSel'] = 0;
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
        
        if (isset($pval['msgFrmt'])) $options[$ii]['msgFrmt'] = trim($pval['msgFrmt']);        
        if (isset($pval['msgTFrmt'])) $options[$ii]['msgTFrmt'] = trim($pval['msgTFrmt']);        
        
        if (isset($pval['apDoKT']))      $options[$ii]['doKT'] = $pval['apDoKT']; else $options[$ii]['doKT'] = 0; 
        
        $options[$ii] = nxs_adjRpst($options[$ii], $pval);       
        
        if (isset($pval['delayDays'])) $options[$ii]['nDays'] = trim($pval['delayDays']);
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      } elseif ( count($pval)==1 ) if (isset($pval['apDo'.$code])) $options[$ii]['do'.$code] = $pval['apDo'.$code]; else $options[$ii]['do'.$code] = 0; 
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; $nt = $this->ntInfo['lcode']; $ntU = $this->ntInfo['code'];
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snap'.$ntU, true));  
        if (is_array($pMeta) && isset($pMeta[$ii]) && is_array($pMeta[$ii])) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]);  if (empty($ntOpt['imgToUse'])) $ntOpt['imgToUse'] = ''; 
        $doIT = $ntOpt['do'.$ntU] && (is_array($pMeta) || $ntOpt['catSel']!='1'); $imgToUse = $ntOpt['imgToUse']; if (empty($ntOpt['urlToUse'])) $ntOpt['urlToUse'] = '';  $urlToUse = $ntOpt['urlToUse'];
        $isAvail = $ntOpt['uPass']!='' && $ntOpt['uName']!=''; $msgFormat = htmlentities($ntOpt['msgFrmt'], ENT_COMPAT, "UTF-8"); $msgFormatT = htmlentities($ntOpt['msgTFrmt'], ENT_COMPAT, "UTF-8"); 
      ?>  
      <tr><th style="text-align:left;" colspan="2"> 
      <?php if ($ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='')  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if (!empty($ntOpt['tagsSelX'])) { ?>  <input type="hidden" class="nxs_TG" id="nxs_TG_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['tagsSelX']; ?>" /> <?php } ?>
      <?php if ($isAvail) { ?> <input type="hidden" name="<?php echo $nt; ?>[<?php echo $ii; ?>][do<?php echo $ntU; ?>]" value="<?php echo ($post->post_status == "publish")?$doIT:'0';?>">
        <input class="nxsGrpDoChb" value="1" id="do<?php echo $ntU.$ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][do<?php echo $ntU; ?>]" <?php if ((int)$doIT == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php } ?>
      
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $nt; ?>16.png);"><?php echo $this->ntInfo['name']; ?> - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvail) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostTo<?php echo $ntU; ?>_repostButton" id="rePostTo<?php echo $ntU; ?>_button" value="<?php _e('Repost to '.$this->ntInfo['name'], 'nxs_snap') ?>" />
                    <?php  } ?>
                    
                    <?php  if (is_array($pMeta) && isset($pMeta[$ii]) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) { 
                        
                        ?> <span id="pstd<?php echo $ntU; ?><?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
                      <a style="font-size: 10px;" href="<?php echo $pMeta[$ii]['postURL']; ?>" target="_blank"><?php $nType=$this->ntInfo['name']; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>                
                
                <?php if (!$isAvail) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your <? echo $this->ntInfo['name']; ?> Account to AutoPost to <? echo $this->ntInfo['name']; ?></b></td></tr>
                <?php }  else { if ($post->post_status != "publish" && function_exists('nxs_doSMAS5') ) { $ntOpt['postTime'] = get_post_time('U', false, $post_id); nxs_doSMAS5($nt, $ii, $ntOpt); } ?>
                
                <?php if ($ntOpt['rpstOn']=='1') { ?> 
                
                <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow">
                <input value="0"  type="hidden" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"/><input value="nxsi<?php echo $ii; ?>ap" type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"  <?php if (!empty($ntOpt['rpstPostIncl'])) echo "checked"; ?> /> 
                </th>
                <td> <?php _e('Include in "Auto-Reposting" to this network.', 'nxs_snap') ?>
                </td></tr> <?php } ?>
     
     <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Title Format:', 'NS_SPAP') ?></th>
                  <td><input value="<?php echo $msgFormatT; ?>" type="text" name="<?php echo $nt; ?>[<?php echo $ii; ?>][msgTFrmt]" style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('msgFrmtT<?php echo $nt.$ii; ?>');"/><?php nxs_doShowHint("msgFrmtT".$nt.$ii, '', '58'); ?></td></tr>
     
                <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top;  padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Text Format:', 'nxs_snap') ?></th><td>                
                
                <textarea cols="150" rows="1" id="<?php echo $nt.$ii; ?>msgFrmt" name="<?php echo $nt; ?>[<?php echo $ii; ?>][msgFrmt]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#<?php echo $nt.$ii; ?>msgFrmt').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('msgFrmt<?php echo $nt.$ii; ?>');"><?php echo $msgFormat ?></textarea> <?php nxs_doShowHint("msgFrmt".$nt.$ii, '', '58'); ?>
                
                </td></tr>                
                
                 <?php /* ## Select Image & URL ## */ nxs_showImgToUseDlg($nt, $ii, $imgToUse);  nxs_showURLToUseDlg($nt, $ii, $urlToUse); ?>
       <?php } 

     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = '';
     if (isset($pMeta['msgFrmt'])) $optMt['msgFrmt'] = $pMeta['msgFrmt'];  if (isset($pMeta['msgTFrmt'])) $optMt['msgTFrmt'] = $pMeta['msgTFrmt'];    
     if (isset($pMeta['imgToUse'])) $optMt['imgToUse'] = $pMeta['imgToUse'];      
     if (isset($pMeta['timeToRun']))  $optMt['timeToRun'] = $pMeta['timeToRun'];  if (isset($pMeta['rpstPostIncl']))  $optMt['rpstPostIncl'] = $pMeta['rpstPostIncl'];    
     if (isset($pMeta['doKT'])) $optMt['doKT'] = $pMeta['doKT'] == 1?1:0; else { if (isset($pMeta['SNAPformat']))  $optMt['doKT'] = 0; } 
     if (isset($pMeta['SNAPincludeKT']) && $pMeta['SNAPincludeKT'] == '1' ) $optMt['doKT'] = 1;  
     return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToKT_ajax")) {
  function nxs_rePostToKT_ajax() { check_ajax_referer('nxsSsPageWPN');  $postID = $_POST['id']; $options = get_option('NS_SNAutoPoster');  
    foreach ($options['kt'] as $ii=>$two) if ($ii==$_POST['nid']) {   $two['ii'] = $ii; $two['pType'] = 'aj'; //if ($two['gpPageID'].$two['gpUName']==$_POST['nid']) {  
      $gppo =  get_post_meta($postID, 'snapKT', true); $gppo =  maybe_unserialize($gppo);// prr($gppo);
      if (is_array($gppo) && isset($gppo[$ii]) && is_array($gppo[$ii])){   $ntClInst = new nxs_snapClassKT(); $two = $ntClInst->adjMetaOpt($two, $gppo[$ii]); }
      $result = nxs_doPublishToKT($postID, $two); if ($result == 200) die("Successfully sent your post to Kippt."); else die($result);        
    }    
  }
}  

if (!function_exists("nxs_doPublishToKT")) { //## Second Function to Post to KT
  function nxs_doPublishToKT($postID, $options){ global $nxs_ktCkArray; $ntCd = 'KT'; $ntCdL = 'kt'; $ntNm = 'Kippt'; if (!is_array($options)) $options = maybe_unserialize(get_post_meta($postID, $options, true));
  //  if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToDI',  array($postID, $options));  
    $addParams = nxs_makeURLParams(array('NTNAME'=>$ntNm, 'NTCODE'=>$ntCd, 'POSTID'=>$postID, 'ACCNAME'=>$options['nName']));   
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url();  
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
    $logNT = '<span style="color:#000080">Kippt</span> - '.$options['nName'];      
    $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
      $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') { sleep(5);
         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'); return;
      }
    }     
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $link = home_url();  $options['msgFormat'] = 'Test Message from '.$link;   } else {  
      $post = get_post($postID); if(!$post) return; $link = get_permalink($postID);
      $options['msgFrmt'] = nsFormatMessage($options['msgFrmt'], $postID, $addParams); $options['msgTFrmt'] = nsFormatMessage($options['msgTFrmt'], $postID, $addParams); 
      nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); 
    } 
    $extInfo = ' | PostID: '.$postID." - ".(isset($post) && is_object($post)?$post->post_title:'');
    //## Create and Format message
    $t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = (implode(',',$tggs)); $tags = str_replace(' ','+',$tags);    
    $message = array('url'=>$link, 'surl'=>$link, 'siteName'=>$blogTitle, 'tags'=>$tags);    
    //## Actual Post
    $ntToPost = new nxs_class_SNAP_KT(); $ret = $ntToPost->doPostToNT($options, $message);
    //## Process Results
    if (!is_array($ret) || $ret['isPosted']!='1') { //## Error 
       if ($postID=='0') prr($ret); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo); 
    } else {  // ## All Good - log it.
      if ($postID=='0')  { nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); echo _e('OK - Message Posted, please see your '.$logNT.' Page. ', 'nxs_snap'); } 
        else  {nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPosted'=>'1', 'pgID'=>$ret['postID'], 'postURL'=>$ret['postURL'], 'pDate'=>date('Y-m-d H:i:s')));  
         nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo); 
        }
    }
    //## Return Result
    if ($ret['isPosted']=='1') return 200; else return print_r($ret, true); 
  }
}  
?>