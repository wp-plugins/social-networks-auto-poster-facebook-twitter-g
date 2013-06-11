<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'PN', 'lcode'=>'pn', 'name'=>'Pinterest');

if (!class_exists("nxs_snapClassPN")) { class nxs_snapClassPN {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){  global $nxs_plurl; $ntInfo = array('code'=>'PN', 'lcode'=>'pn', 'name'=>'Pinterest', 'defNName'=>'pnUName', 'tstReq' => false); ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> <?php wp_nonce_field( 'ns'.$ntInfo['code'], 'ns'.$ntInfo['code'].'_wpnonce' ); ?>
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php if(!function_exists('doPostToPinterest')) {?>  Pinterest doesn't have a built-in API for automated posts yet. <br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/pinterest-automated-posting">library module</a> to be able to publish your content to Pinterest.
        <?php } else foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = $pbo[$ntInfo['defNName']]; ?>
          <p style="margin:0px;margin-left:5px;">
            <input value="1" name="<?php echo $ntInfo['lcode']; ?>[<?php echo $indx; ?>][apDo<?php echo $ntInfo['code']; ?>]" onchange="doShowHideBlocks('<?php echo $ntInfo['code']; ?>');" type="checkbox" <?php if ((int)$pbo['do'.$ntInfo['code']] == 1) echo "checked"; ?> /> <?php if ((int)$pbo['catSel'] == 1) { ?>   <span onmouseout="nxs_hidePopUpInfo('popOnlyCat');" onmouseover="nxs_showPopUpInfo('popOnlyCat', event);"><?php echo "*[".(substr_count($pbo['catSelEd'], ",")+1)."]*" ?></span><?php } ?>
            <strong><?php  _e('Auto-publish to', 'nxs_snap'); ?> <?php echo $ntInfo['name']; ?> <i style="color: #005800;"><?php if($pbo['nName']!='') echo "(".$pbo['nName'].")"; ?></i></strong>
          &nbsp;&nbsp;<?php if ($ntInfo['tstReq'] && (!isset($pbo[$ntInfo['lcode'].'OK']) || $pbo[$ntInfo['lcode'].'OK']=='')){ ?><b style="color: #800000"><?php  _e('Attention requred. Unfinished setup', 'nxs_snap'); ?> ==&gt;</b><?php } ?><a id="do<?php echo $ntInfo['code'].$indx; ?>A" href="#" onclick="doShowHideBlocks2('<?php echo $ntInfo['code'].$indx; ?>');return false;">[<?php  _e('Show Settings', 'nxs_snap'); ?>]</a>&nbsp;&nbsp;
          <a href="#" onclick="doDelAcct('<?php echo $ntInfo['lcode']; ?>', '<?php echo $indx; ?>', '<?php if (isset($pbo['bgBlogID'])) echo $pbo['nName']; ?>');return false;">[<?php  _e('Remove Account', 'nxs_snap'); ?>]</a>
          </p><?php $this->showNTSettings($indx, $pbo);             
        } ?>
      </div>
    </div> <?php 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($mgpo){ $po = array('nName'=>'', 'doPN'=>'1', 'pnUName'=>'', 'pnBoard'=>'', 'gpAttch'=>'', 'pnPass'=>'', 'pnDefImg'=>'', 'pnMsgFormat'=>'', 'pnBoard'=>'', 'pnBoardsList'=>'', 'doPN'=>1); $this->showNTSettings($mgpo, $po, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; ?>
             <div id="doPN<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>" style="background-image: url(<?php echo $nxs_plurl; ?>img/pn-bg.png);  background-position:90% 10%;">     <input type="hidden" name="apDoSPN<?php echo $ii; ?>" value="0" id="apDoSPN<?php echo $ii; ?>" />         
             
             <?php if(!function_exists('doPostToPinterest')) {?><span style="color:#580000; font-size: 16px;"><br/><br/>
            <b>Pinterest API Library not found</b>
             <br/><br/> Pinterest doesn't have a built-in API for automated posts yet.  <br/><br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/pinterest-automated-posting"><b>API Library Module</b></a> to be able to publish your content to Pinterest.</span></div>
            
            <?php return; }; ?>
             
           
            <div id="doPN<?php echo $ii; ?>Div" style="margin-left: 10px;"> <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/pn16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-pinterest-social-networks-auto-poster-wordpress/"><?php $nType="Pinterest"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easely identify it', 'nxs_snap'); ?></i> </div><input name="pn[<?php echo $ii; ?>][nName]" id="pnnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('pn', $ii, $options['qTLng']); ?><?php echo nxs_addPostingDelaySel('pn', $ii, $options['nHrs'], $options['nMin']); ?>
            
             <?php if (!$isNew) { ?>
    <div style="width:100%;"><strong><?php _e('Categories', 'nxs_snap'); ?>:</strong>
       <input value="0" id="catSelA<?php echo $ii; ?>" type="radio" name="pn[<?php echo $ii; ?>][catSel]" <?php if ((int)$options['catSel'] != 1) echo "checked"; ?> /> All                                  
       <input value="1" id="catSelSPN<?php echo $ii; ?>" type="radio" name="pn[<?php echo $ii; ?>][catSel]" <?php if ((int)$options['catSel'] == 1) echo "checked"; ?> /> <a href="#" style="text-decoration: none;" class="showCats" id="nxs_SCA_PN<?php echo $ii; ?>" onclick="jQuery('#catSelSPN<?php echo $ii; ?>').attr('checked', true); jQuery('#tmpCatSelNT').val('PN<?php echo $ii; ?>'); nxs_markCats( jQuery('#nxs_SC_PN<?php echo $ii; ?>').val() ); jQuery('#showCatSel').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false], position: [75, 'auto']}); return false;">Selected<?php if ($options['catSelEd']!='') echo "[".(substr_count($options['catSelEd'], ",")+1)."]"; ?></a>       
       <input type="hidden" name="pn[<?php echo $ii; ?>][catSelEd]" id="nxs_SC_PN<?php echo $ii; ?>" value="<?php echo $options['catSelEd']; ?>" />
    <br/><i><?php _e('Only selected categories will be autoposted to this account', 'nxs_snap'); ?></i></div> 
    <br/>
    <?php } ?>
                  
            <div style="width:100%;"><strong>Pinterest Email:</strong> </div><input name="pn[<?php echo $ii; ?>][apPNUName]" id="apPNUName<?php echo $ii; ?>" class="apPNUName<?php echo $ii; ?>"  style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['pnUName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
            <div style="width:100%;"><strong>Pinterest Password:</strong> </div><input name="pn[<?php echo $ii; ?>][apPNPass]" id="apPNPass<?php echo $ii; ?>" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($options['pnPass'], 0, 5)=='g9c1a'?nsx_doDecode(substr($options['pnPass'], 5)):$options['pnPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/>                
            <div style="width:100%;"><strong>Default Image to Pin:</strong> 
            <p style="font-size: 11px; margin: 0px;">If your post missing Featured Image this will be used instead.</p>
            </div><input name="pn[<?php echo $ii; ?>][apPNDefImg]" id="apPNDefImg" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['pnDefImg'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /> 
            <br/><br/>            
            
            <div style="width:100%;"><strong>Board:</strong> 
            Please <a href="#" onclick="getBoards(jQuery('<?php if ($isNew) echo "#nsx_addNT "; ?>#apPNUName<?php echo $ii; ?>').val(),jQuery('<?php if ($isNew) echo "#nsx_addNT "; ?>#apPNPass<?php echo $ii; ?>').val(), '<?php echo $ii; ?>'); return false;">click here to retrieve your boards</a>
            </div>
            <?php wp_nonce_field( 'getBoards', 'getBoards_wpnonce' ); ?><img id="pnLoadingImg<?php echo $ii; ?>" style="display: none;" src='<?php echo $nxs_plurl; ?>img/ajax-loader-sm.gif' />
            <select name="pn[<?php echo $ii; ?>][apPNBoard]" id="apPNBoard<?php echo $ii; ?>">
            <?php if ($options['pnBoardsList']!=''){ $gPNBoards = $options['pnBoardsList']; if ( base64_encode(base64_decode($gPNBoards)) === $gPNBoards) $gPNBoards = base64_decode($gPNBoards); 
              if ($options['pnBoard']!='') $gPNBoards = str_replace($options['pnBoard'].'"', $options['pnBoard'].'" selected="selected"', $gPNBoards);  echo $gPNBoards;} else { ?>
              <option value="0">None(Click above to retrieve your boards)</option>
            <?php } ?>
            </select>
            
            <br/><br/>            
            
            
            <div id="altFormat" style="">
              <div style="width:100%;"><strong id="altFormatText"><?php _e('Message text Format', 'nxs_snap'); ?>:</strong>  <a href="#" id="apPNMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apPNMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>             
              </div>
              
               <textarea cols="150" rows="3" id="pn<?php echo $ii; ?>SNAPformat" name="pn[<?php echo $ii; ?>][apPNMsgFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#pn<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apPNMsgFrmt<?php echo $ii; ?>');"><?php if ($options['pnMsgFormat']!='') _e(apply_filters('format_to_edit', htmlentities($options['pnMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap');  else echo "%TITLE% - %URL%"; ?></textarea>
              
              <?php nxs_doShowHint("apPNMsgFrmt".$ii); ?>
            </div><br/>    
            <?php if ($isNew) { ?> <input type="hidden" name="pn[<?php echo $ii; ?>][apDoPN]" value="1" id="apDoNewPN<?php echo $ii; ?>" /> <?php } ?>
            <?php if ($options['pnPass']!='') { ?>
            <?php wp_nonce_field( 'rePostToPN', 'rePostToPN_wpnonce' ); ?>
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('PN', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>         
            <?php } ?>
            
            <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div>
            </div>
  </div>
            <?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl;// $code = 'PN'; $lcode = 'pn'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apPNUName']) && $pval['apPNUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apDoPN']))   $options[$ii]['doPN'] = $pval['apDoPN']; else $options[$ii]['doPN'] = 0;
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apPNUName']))   $options[$ii]['pnUName'] = trim($pval['apPNUName']);
        if (isset($pval['apPNPass']))    $options[$ii]['pnPass'] = 'g9c1a'.nsx_doEncode($pval['apPNPass']); else $options[$ii]['pnPass'] = '';
        if (isset($pval['apPNBoard']))   $options[$ii]['pnBoard'] = trim($pval['apPNBoard']);                
        if (isset($pval['apPNDefImg']))  $options[$ii]['pnDefImg'] = trim($pval['apPNDefImg']);
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']);
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
        
        if (isset($pval['apPNMsgFrmt'])) $options[$ii]['pnMsgFormat'] = trim($pval['apPNMsgFrmt']);     
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      }
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID;
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapPN', true));  if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
        $doPN = $ntOpt['doPN'] && (is_array($pMeta) || $ntOpt['catSel']!='1');   
        $isAvailPN =  $ntOpt['pnUName']!='' && $ntOpt['pnPass']!=''; $pnMsgFormat = htmlentities($ntOpt['pnMsgFormat'], ENT_COMPAT, "UTF-8");        
      ?>  
      <tr><th style="text-align:left;" colspan="2"><?php if ( $ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='' )  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_PN<?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if ($isAvailPN) { ?><input class="nxsGrpDoChb" value="1" id="doPN<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="pn[<?php echo $ii; ?>][doPN]" <?php if ((int)$doPN == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="pn[<?php echo $ii; ?>][doPN]" value="<?php echo $doPN;?>"> <?php } ?> <?php } ?>
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/pn16.png);">Pinterest - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailPN) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;"  type="button" class="button" name="rePostToPN_repostButton" id="rePostToPN_button" value="<?php _e('Repost to Pinterest', 'nxs_snap') ?>" />
                    <?php wp_nonce_field( 'rePostToPN', 'rePostToPN_wpnonce' ); } ?>

                    <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) {                         
                        ?> <span id="pstdPN<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
          <a style="font-size: 10px;" href="http://pinterest.com<?php echo $pMeta[$ii]['pgID']; ?>" target="_blank"><?php $nType="Pinterest"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>                
                
                <?php if (!$isAvailPN) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Pinterest Account to AutoPost to Pinterest</b>
                <?php } elseif ($post->post_status != "puZblish") { ?> 
                
                <tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;">Select Board</th>
                <td><select name="pn[<?php echo $ii; ?>][apPNBoard]" id="apPNBoard">
            <?php if ($ntOpt['pnBoardsList']!=''){ $gPNBoards = $ntOpt['pnBoardsList']; if ( base64_encode(base64_decode($gPNBoards)) === $gPNBoards) $gPNBoards = base64_decode($gPNBoards); 
              if ($ntOpt['pnBoard']!='') $gPNBoards = str_replace($ntOpt['pnBoard'].'"', $ntOpt['pnBoard'].'" selected="selected"', $gPNBoards);  echo $gPNBoards;} else { ?>
              <option value="0">None(Click above to retrieve your boards)</option>
            <?php } ?>
            </select></td>
                </tr> 
                              
                <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top:6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Text Message Format:', 'nxs_snap') ?></th>
                <td>                
                <textarea cols="150" rows="1" id="pn<?php echo $ii; ?>SNAPformat" name="pn[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#pn<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apPNMsgFrmt<?php echo $ii; ?>');"><?php echo $pnMsgFormat; ?></textarea>
                <?php nxs_doShowHint("apPNMsgFrmt".$ii); ?></td></tr>
                                
                <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){  if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['pnMsgFormat'] = $pMeta['SNAPformat'];      
     if (isset($pMeta['doPN'])) $optMt['doPN'] = $pMeta['doPN'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doPN'] = 0; }
     if (isset($pMeta['apPNBoard']) && $pMeta['apPNBoard']!='' && $pMeta['apPNBoard']!='0') $optMt['pnBoard'] = $pMeta['apPNBoard']; 
     if (isset($pMeta['SNAPincludePN']) && $pMeta['SNAPincludePN'] == '1' ) $optMt['doPN'] = 1;  
     return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToPN_ajax")) {
  function nxs_rePostToPN_ajax() { check_ajax_referer('rePostToPN');  $postID = $_POST['id']; $options = get_option('NS_SNAutoPoster');  
    foreach ($options['pn'] as $ii=>$two) if ($ii==$_POST['nid']) {    $two['ii'] = $ii; $two['pType'] = 'aj'; //if ($two['gpPageID'].$two['gpUName']==$_POST['nid']) {  
      $po =  get_post_meta($postID, 'snapPN', true); $po =  maybe_unserialize($po);// prr($gppo);
      if (is_array($po) && isset($po[$ii]) && is_array($po[$ii])){ $ntClInst = new nxs_snapClassPN(); $two = $ntClInst->adjMetaOpt($two, $po[$ii]); }
      $result = nxs_doPublishToPN($postID, $two); if ($result == 200) die("Successfully sent your post to Pinterest."); else die($result);        
    }    
  }
}  

if (!function_exists("nxs_doPublishToPN")) { //## Second Function to Post to G+
  function nxs_doPublishToPN($postID, $options){ global $nxs_gCookiesArr; $ntCd = 'PN'; $ntCdL = 'pn'; $ntNm = 'Pinterest';    
    // $backtrace = debug_backtrace(); nxs_addToLogN('W', 'Enter', $ntCd, 'I am here - '.$ntCd."|".print_r($backtrace, true), ''); 
    //if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToPN',  array($postID, $options));  
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10));
    $logNT = '<span style="color:#FA5069">Pinterest</span> - '.$options['nName'];
    $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    $isAttachVid = $options['isAttachVid']; $isAttachVid = '1';
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') {  sleep(5);
         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$uqID); return;
        }
    }  
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); 
    
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $msg = 'Test Post from '.$blogTitle; $link = home_url(); 
      if ($options['pnDefImg']!='') $imgURL = $options['pnDefImg']; else $imgURL ="http://direct.gtln.us/img/nxs/NextScriptsLogoT.png"; 
    }
    else { $post = get_post($postID); if(!$post) return; $pnMsgFormat = $options['pnMsgFormat'];  $msg = nsFormatMessage($pnMsgFormat, $postID); $link = get_permalink($postID); 
      nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); $imgURL = nxs_getPostImage($postID, 'large',  $options['pnDefImg']); //prr($options); echo $imgURL."######"; // echo "WW".$postID."|";
      if ($isAttachVid=='1') { $vids = nsFindVidsInPost($post); if (count($vids)>0) { $vidURL = 'http://www.youtube.com/v/'.$vids[0]; $imgURL = 'http://img.youtube.com/vi/'.$vids[0].'/0.jpg'; }}      
    }
    $email = $options['pnUName']; $boardID = $options['pnBoard'];  $pass = substr($options['pnPass'], 0, 5)=='g9c1a'?nsx_doDecode(substr($options['pnPass'], 5)):$options['pnPass'];// prr($boardID); prr($_POST); die();    
    if (isset($options['pnSvC'])) $nxs_gCookiesArr = maybe_unserialize( $options['pnSvC']); $loginError = true;
    if (is_array($nxs_gCookiesArr)) $loginError = doCheckPinterest(); 
    $extInfo = ' | PostID: '.$postID." - ".$post->post_title; 
    if ($loginError!==false) $loginError = doConnectToPinterest($email, $pass);  if ($loginError!==false) {echo $loginError; nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($loginError, true), $extInfo); return "BAD USER/PASS";}      
    if (serialize($nxs_gCookiesArr)!=$options['pnSvC']) { global $plgn_NS_SNAutoPoster;  $gOptions = $plgn_NS_SNAutoPoster->nxs_options; // prr($gOptions['pn']);
        if (isset($options['ii']) && $options['ii']!=='')  { $gOptions['pn'][$options['ii']]['pnSvC'] = serialize($nxs_gCookiesArr); update_option('NS_SNAutoPoster', $gOptions);  }        
        else foreach ($gOptions['pn'] as $ii=>$gpn) { $result = array_diff($options, $gpn);
          if (!is_array($result) || count($result)<1) { $gOptions['pn'][$ii]['pnSvC'] = serialize($nxs_gCookiesArr); update_option('NS_SNAutoPoster', $gOptions); break; }
        }        
    } // echo "PN SET:".$msg."|".$imgURL."|".$link."|".$boardID;
    if (preg_match ( '/\$(\d+\.\d+)/', $msg, $matches )) $price = $matches[0];
    $ret = doPostToPinterest($msg, $imgURL, $link, $boardID, 'TITLE WHERE IS IT?', $price, $link."/GTH/" ); if ($ret=='OK') $ret = array("code"=>"OK", "post_id"=>'');
    if ( (!is_array($ret)) && $ret!='OK') { if ($postID=='0') echo $ret; nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo); } else { if ($postID=='0') {  nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); echo 'OK - Message Posted, please see your Pinterest Page'; } else { nxs_metaMarkAsPosted($postID, 'PN', $options['ii'], array('isPosted'=>'1', 'pgID'=>$ret['post_id'], 'pDate'=>date('Y-m-d H:i:s'))); nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo);} }
    if ($ret['code']=='OK') return 200; else return $ret;
  }
}  
?>