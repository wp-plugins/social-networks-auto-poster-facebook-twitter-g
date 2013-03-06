<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'FF', 'lcode'=>'ff', 'name'=>'FriendFeed');

if (!class_exists("nxs_snapClassFF")) { class nxs_snapClassFF {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){ global $nxs_plurl; $ntInfo = array('code'=>'FF', 'lcode'=>'ff', 'name'=>'FriendFeed', 'defNName'=>'ffUName', 'tstReq' => false); ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> <?php wp_nonce_field( 'ns'.$ntInfo['code'], 'ns'.$ntInfo['code'].'_wpnonce' ); ?>
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = $pbo[$ntInfo['defNName']]; ?>
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
  function showNewNTSettings($mgpo){ $gpo = array('nName'=>'', 'doFF'=>'1', 'ffUName'=>'', 'ffPageID'=>'', 'ffAttch'=>'', 'ffPass'=>''); $this->showNTSettings($mgpo, $gpo, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $gpo, $isNew=false){  global $nxs_plurl; ?>
            <div id="doFF<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>" style="max-width: 1000px; background-color: #EBF4FB; background-image: url(<?php echo $nxs_plurl; ?>img/ff-bg.png);  background-position:90% 10%; background-repeat: no-repeat; margin: 10px; border: 1px solid #808080; padding: 10px; display:none;">     <input type="hidden" name="apDoSFF<?php echo $ii; ?>" value="0" id="apDoSFF<?php echo $ii; ?>" />          
            
             <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/ff16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-FriendFeed-social-networks-auto-poster-wordpress/"><?php $nType="FriendFeed"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
            
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easely identify it', 'nxs_snap'); ?></i> </div><input name="ff[<?php echo $ii; ?>][nName]" id="ffnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit',htmlentities($gpo['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('ff', $ii, $gpo['qTLng']); ?><?php echo nxs_addPostingDelaySel('ff', $ii, $gpo['nHrs'], $gpo['nMin']); ?>
            
            <?php if (!$isNew) { ?>
    <div style="width:100%;"><strong><?php _e('Categories', 'nxs_snap'); ?>:</strong>
       <input value="0" id="catSelA<?php echo $ii; ?>" type="radio" name="ff[<?php echo $ii; ?>][catSel]" <?php if ((int)$gpo['catSel'] != 1) echo "checked"; ?> /> All                                  
       <input value="1" id="catSelSFF<?php echo $ii; ?>" type="radio" name="ff[<?php echo $ii; ?>][catSel]" <?php if ((int)$gpo['catSel'] == 1) echo "checked"; ?> /> <a href="#" style="text-decoration: none;" class="showCats" id="nxs_SCA_FF<?php echo $ii; ?>" onclick="jQuery('#catSelSFF<?php echo $ii; ?>').attr('checked', true); jQuery('#tmpCatSelNT').val('FF<?php echo $ii; ?>'); nxs_markCats( jQuery('#nxs_SC_FF<?php echo $ii; ?>').val() ); jQuery('#showCatSel').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false], position: [75, 'auto']}); return false;">Selected<?php if ($gpo['catSelEd']!='') echo "[".(substr_count($gpo['catSelEd'], ",")+1)."]"; ?></a>       
       <input type="hidden" name="ff[<?php echo $ii; ?>][catSelEd]" id="nxs_SC_FF<?php echo $ii; ?>" value="<?php echo $gpo['catSelEd']; ?>" />
    <br/><i><?php _e('Only selected categories will be autoposted to this account', 'nxs_snap'); ?></i></div> 
    <br/>
    <?php } ?>
            
            <div style="width:100%;"><strong>FriendFeed nickname:</strong> <span style="font-size: 11px; margin: 0px;">Get it from <a target="_blank" href="https://friendfeed.com/account/api">https://friendfeed.com/account/api</a>.</span></div><input name="ff[<?php echo $ii; ?>][apFFUName]" id="apFFUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',htmlentities($gpo['ffUName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
            <div style="width:100%;"><strong>FriendFeed remote key:</strong> <span style="font-size: 11px; margin: 0px;">Get it from <a target="_blank" href="https://friendfeed.com/account/api">https://friendfeed.com/account/api</a>.</span>
            
            </div><input name="ff[<?php echo $ii; ?>][apFFPass]" id="apFFPass" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($gpo['ffPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($gpo['ffPass'], 5)):$gpo['ffPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/>        
            
            <div style="width:100%;"><br/><strong>Group ID:</strong> [Optional] Please specify Group ID. <i>Use this <b>only</b> if you are posting NOT to your own feed. </i></div> 
            <input name="ff[<?php echo $ii; ?>][grpID]" id="grpID" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($gpo['grpID'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />         
            
            <?php if ($isNew) { ?> <input type="hidden" name="ff[<?php echo $ii; ?>][apDoFF]" value="1" id="apDoNewFF<?php echo $ii; ?>" /> <?php } ?>
            <br/>   <br/>   
            
             <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Text Format', 'nxs_snap'); ?></strong> (<a href="#" id="apFFMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apFFMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)</div>  
              <input name="ff[<?php echo $ii; ?>][apFFMsgFrmt]" id="apFFMsgFrmt" style="width: 50%;" value="<?php if ($isNew) echo "%TITLE% - %URL% %EXCERPT%"; else _e(apply_filters('format_to_edit',htmlentities($gpo['ffMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?>" onfocus="mxs_showFrmtInfo('apFFMsgFrmt<?php echo $ii; ?>');" /><?php nxs_doShowHint("apFFMsgFrmt".$ii); ?>
            </div>  
    <p style="margin: 0px;"><input value="1"  id="apLIAttch" type="checkbox" name="ff[<?php echo $ii; ?>][attchImg]"  <?php if ((int)$gpo['attchImg'] == 1) echo "checked"; ?> /> <strong><?php _e('Attach Image to FriendFeed Post', 'nxs_snap'); ?></strong></p>
    <br/>
            
            <?php if ($gpo['ffPass']!='') { ?>
            <?php wp_nonce_field( 'rePostToFF', 'rePostToFF_wpnonce' ); ?>
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('FF', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>      
               
            <?php } 
            
            ?><div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div></div><?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'FF'; $lcode = 'ff'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apFFUName']) && $pval['apFFUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apFFUName']))   $options[$ii]['ffUName'] = trim($pval['apFFUName']);
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apFFPass']))    $options[$ii]['ffPass'] = 'n5g9a'.nsx_doEncode($pval['apFFPass']); else $options[$ii]['ffPass'] = '';  
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']);
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
        
        if (isset($pval['apFFMsgFrmt'])) $options[$ii]['ffMsgFormat'] = trim($pval['apFFMsgFrmt']);                                                  
        if (isset($pval['grpID'])) $options[$ii]['grpID'] = trim($pval['grpID']);                                      
        if (isset($pval['attchImg'])) $options[$ii]['attchImg'] = $pval['attchImg']; else $options[$ii]['attchImg'] = 0;                            
        if (isset($pval['apDoFF']))      $options[$ii]['doFF'] = $pval['apDoFF']; else $options[$ii]['doFF'] = 0; 
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      }
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID;
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapFF', true));   if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
        $doFF = $ntOpt['doFF'] && (is_array($pMeta) || $ntOpt['catSel']!='1');   
        $isAvailFF =  $ntOpt['ffUName']!='' && $ntOpt['ffPass']!=''; $ffMsgFormat = htmlentities($ntOpt['ffMsgFormat'], ENT_COMPAT, "UTF-8"); $ffMsgTFormat = htmlentities($ntOpt['ffMsgTFormat'], ENT_COMPAT, "UTF-8");       
        $isAttchImg = $ntOpt['attchImg'];
      ?>  
      <tr><th style="text-align:left;" colspan="2"><?php if ( $ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='' )  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_FF<?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if ($isAvailFF) { ?><input class="nxsGrpDoChb" value="1" id="doFF<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="ff[<?php echo $ii; ?>][doFF]" <?php if ((int)$doFF == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="ff[<?php echo $ii; ?>][doFF]" value="<?php echo $doFF;?>"> <?php } ?> <?php } ?> 
      
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/ff16.png);">FriendFeed - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailFF) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToFF_repostButton" id="rePostToFF_button" value="<?php _e('Repost to FriendFeed', 'nxs_snap') ?>" />
                    <?php wp_nonce_field( 'rePostToFF', 'rePostToFF_wpnonce' ); } ?>
                    
                    <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) {                         
                        ?> <span id="pstdFF<?php echo $ii; ?>" style="float: right; padding-top: 4px; padding-right: 10px;">
          <a style="font-size: 10px;" href="http://www.FriendFeed.com/e/<?php echo $pMeta[$ii]['pgID']; ?>" target="_blank"><?php $nType="FriendFeed"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>       
                
                <tr><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">
                 <input value="0"  type="hidden" name="ff[<?php echo $ii; ?>][attchImg]"/>
                 <input value="1" type="checkbox" name="ff[<?php echo $ii; ?>][attchImg]"  <?php if ((int)$isAttchImg == 1) echo "checked"; ?> /> </th><td><strong>Attach Image to FriendFeed Post</strong></td> </tr>                           
                
                <?php if (!$isAvailFF) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your FriendFeed Account to AutoPost to FriendFeed</b>
                <?php } elseif ($post->post_status != "pubZlish") { ?> 
                
                 <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Title Format:', 'nxs_snap') ?></th>
                <td><input value="<?php echo $ffMsgFormat ?>" type="text" name="ff[<?php echo $ii; ?>][SNAPformatT]"  style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apFFMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apFFMsgFrmt".$ii, '', '58'); ?></td></tr>                
                
                <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = ''; 
     if (isset($pMeta['SNAPformat'])) $optMt['ffMsgFormat'] = $pMeta['SNAPformat']; 
     if (isset($pMeta['attchImg'])) $optMt['attchImg'] = $pMeta['attchImg'] == 1?1:0; else { if (isset($pMeta['attchImg'])) $optMt['attchImg'] = 0; } 
     
     if (isset($pMeta['doFF'])) $optMt['doFF'] = $pMeta['doFF'] == 1?1:0; else { if (isset($pMeta['SNAPformat']))  $optMt['doFF'] = 0; } 
     if (isset($pMeta['SNAPincludeFF']) && $pMeta['SNAPincludeFF'] == '1' ) $optMt['doFF'] = 1;  
     return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToFF_ajax")) {
  function nxs_rePostToFF_ajax() { check_ajax_referer('rePostToFF');  $postID = $_POST['id']; $options = get_option('NS_SNAutoPoster');  
    foreach ($options['ff'] as $ii=>$two) if ($ii==$_POST['nid']) {   $two['ii'] = $ii; $two['pType'] = 'aj'; //if ($two['gpPageID'].$two['gpUName']==$_POST['nid']) {  
      $gppo =  get_post_meta($postID, 'snapFF', true); $gppo =  maybe_unserialize($gppo);// prr($gppo);
      if (is_array($gppo) && isset($gppo[$ii]) && is_array($gppo[$ii])){ $ntClInst = new nxs_snapClassFF(); $two = $ntClInst->adjMetaOpt($two, $gppo[$ii]);}
      $result = nxs_doPublishToFF($postID, $two); if ($result == 200) die("Successfully sent your post to FriendFeed."); else die($result);        
    }    
  }
}  

if (!function_exists("nxs_getFFHeaders")) {  function nxs_getFFHeaders($up){ $hdrsArr = array(); 
 $hdrsArr['Cache-Control']='no-cache'; $hdrsArr['Connection']='keep-alive'; 
 $hdrsArr['User-Agent']='SNAP for Wordpress; Ver '.NextScripts_SNAP_Version;
 $hdrsArr['Accept']='text/html, application/xhtml+xml, */*'; $hdrsArr['DNT']='1';
 $hdrsArr['Content-Type']='application/x-www-form-urlencoded'; 
 $hdrsArr['Authorization'] = 'Basic ' . base64_encode("$up");
 //$hdrsArr['Authorization'] = $up;
 $hdrsArr['Accept-Encoding']='gzip,deflate'; $hdrsArr['Accept-Language']='en-US,en;q=0.8'; $hdrsArr['Accept-Charset']='ISO-8859-1,utf-8;q=0.7,*;q=0.3'; return $hdrsArr;
}}

if (!function_exists("nxs_doPublishToFF")) { //## Second Function to Post to FF
  function nxs_doPublishToFF($postID, $options){ $ntCd = 'FF'; $ntCdL = 'ff'; $ntNm = 'FriendFeed'; 
      //if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToFF',  array($postID, $options));
      
      $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
      $logNT = '<span style="color:#000080">FriendFeed</span> - '.$options['nName'];
      $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
      if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') {  sleep(5);
         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$uqID); return;
        }
      }             
      
      if ($postID=='0') { echo "Testing ... <br/><br/>"; $link = home_url(); $msg = 'Test Link from '.$link; } else { $post = get_post($postID); if(!$post) return; $link = get_permalink($postID);  
        $msgFormat = $options['ffMsgFormat']; $msgTFormat = $options['ffMsgTFormat']; $msgT = nsFormatMessage($msgTFormat, $postID);  $msg = nsFormatMessage($msgFormat, $postID); 
        nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); 
      }
      $extInfo = ' | PostID: '.$postID." - ".$post->post_title;
      $dusername = $options['ffUName']; $pass = (substr($options['ffPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['ffPass'], 5)):$options['ffPass']);
      $link = urlencode($link); $ext = urlencode(substr($msg, 0, 1000));      
      // API/V2 if we ever switch -  $postArr = array('body'=>$msg, 'link'=>'', 'to'=>($options['grpID']!=''?$options['grpID']:'me'), 'image_url'=>($imgURL!=''?$imgURL:'me'), 'short'=>0); prr($postArr);
      
      if ($options['attchImg']=='1') $imgURL = nxs_getPostImage($postID, 'full'); else $imgURL = '';      
      $postArr = array('title'=>$msg, 'image0_link'=>'', 'room'=>($options['grpID']!=''?$options['grpID']:''), 'image0_url'=>($imgURL!=''?$imgURL:''));             
      $apicall = "http://friendfeed.com/api/share";  $hdrsArr = nxs_getFFHeaders($dusername.':'.$pass); 
      $paramcall = array( 'method' => 'POST', 'timeout' => 45, 'redirection' => 0, 'body'=> $postArr,  'headers' => $hdrsArr); 
      
      $cnt = wp_remote_post( $apicall, $paramcall ); // prr(json_decode($cnt['body'], true));
      
      if(is_wp_error($cnt)) {
        $ret = 'Something went wrong - '.""; nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.$ret. "ERR: ".print_r($cnt, true), $extInfo);
      } else {      
        if (is_array($cnt) &&  $cnt['response']['code']=='200' && is_array(json_decode($cnt['body'], true))) 
          { $ret = 'OK'; $retInfo = json_decode($cnt['body'], true);  
            nxs_metaMarkAsPosted($postID, 'FF', $options['ii'], array('isPosted'=>'1', 'pgID'=>$retInfo['entries'][0]['id'], 'pDate'=>date('Y-m-d H:i:s')));  nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo); } 
          else { $ret = "Error: ";
            if ($cnt['response']['code']=='401') $ret .= " Incorrect Username/Password ";             
            $ret .= $cnt['response']['message'];  nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.$ret. " | ERR: ".print_r($cnt, true), $extInfo);            
          }
      }
      if ($ret!='OK') { if ($postID=='0') echo $ret; } else if ($postID=='0') { echo 'OK - Message Posted, please see your FriendFeed Page. '; nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); }
      if ($ret == 'OK') return 200; else return $ret;      
  }
}  
?>