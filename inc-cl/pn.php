<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'PN', 'lcode'=>'pn', 'name'=>'Pinterest');

if (!class_exists("nxs_snapClassPN")) { class nxs_snapClassPN {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl, $nxs_plurl, $nxsOne; $code = 'PN'; $lcode = 'pn'; wp_nonce_field( 'ns'.$code, 'ns'.$code.'_wpnonce' ); ?>
    <hr/><div class="nsx_iconedTitle" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $lcode; ?>16.png);">Pinterest Settings  
            
            <?php if(!function_exists('doPostToPinterest')) {?></div>  Pinterest doesn't have a built-in API for automated posts yet. <br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/pinterest-automated-posting">library module</a> to be able to publish your content to Pinterest. <br/><br/>           
            
            <?php } else { 
              $cgpo = count($ntOpts); $mgpo = 1+max(array_keys($ntOpts));  $nxsOne .= "&p=1"; ?>            
              <div class="nsBigText">You have <?php echo $cgpo=='0'?'No':$cgpo; ?> Pinterest account<?php if ($cgpo!=1){ ?>s<?php } ?> <!--- <a href="#" class="NXSButton" onclick="doShowHideBlocks2('PN<?php echo $mgpo; ?>');return false;">Add new Google+ Account</a> --> </div> </div>                  
              <?php // if (function_exists('nxs_doSMAS1')) nxs_doSMAS1($this, $mgpo); else nxs_doSMAS('Pinterest', 'PN'.$mgpo); ?>
              <?php foreach ($ntOpts as $indx=>$po){ if (trim($po['nName']=='')) { $po['nName'] = $po['pnUName']." Pinterest";  if($po['pnBoard']!='') $po['nName'] .= " Board: ".$po['pnBoard']; else $po['nName'] .= " Profile"; } ?>
                <p style="margin: 0px;margin-left: 5px;">
                  <input value="1" id="apDoPN" name="pn[<?php echo $indx; ?>][apDoPN]"  type="checkbox" <?php if ((int)$po['doPN'] == 1) echo "checked"; ?> /> 
                  <strong>Auto-publish your Posts to your <i style="color: #005800;"><?php if($po['nName']!='') echo "(".$po['nName'].")"; ?></i> </strong>                                         
                  &nbsp;&nbsp;<a id="doPN<?php echo $indx; ?>A" href="#" onclick="doShowHideBlocks2('PN<?php echo $indx; ?>');return false;">[Show Settings]</a> &nbsp;&nbsp;
                  <a href="#" onclick="doDelAcct('pn','<?php echo $indx; ?>', '<?php echo $po['pnUName']; ?>');return false;">[Remove Account]</a>
                </p>            
                <?php $this->showNTSettings($indx, $po);             
              } ?>                        
            
            <?php } 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($mgpo){ $po = array('nName'=>'', 'doPN'=>'1', 'pnUName'=>'', 'pnBoard'=>'', 'gpAttch'=>'', 'pnPass'=>'', 'pnDefImg'=>'', 'pnMsgFormat'=>'', 'pnBoard'=>'', 'pnBoardsList'=>'', 'doPN'=>1); $this->showNTSettings($mgpo, $po, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; ?>
             <div id="doPN<?php echo $ii; ?>Div" <?php if ($isNew){ ?>class="clNewNTSets"<?php } ?> style="max-width: 1000px; background-color: #EBF4FB; background-image: url(<?php echo $nxs_plurl; ?>img/pn-bg.png);  background-position:90% 10%; background-repeat: no-repeat; margin: 10px; border: 1px solid #808080; padding: 10px; display:none;">     <input type="hidden" name="apDoSPN<?php echo $ii; ?>" value="0" id="apDoSPN<?php echo $ii; ?>" />         
             
             <?php if(!function_exists('doPostToPinterest')) {?><span style="color:#580000; font-size: 16px;"><br/><br/>
            <b>Pinterest API Library not found</b>
             <br/><br/> Pinterest doesn't have a built-in API for automated posts yet.  <br/><br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/pinterest-automated-posting"><b>API Library Module</b></a> to be able to publish your content to Pinterest.</span></div>
            
            <?php return; }; ?>
             
           
            <div id="doPN<?php echo $ii; ?>Div" style="margin-left: 10px;"> <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/pn16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-pinterest-social-networks-auto-poster-wordpress/">Detailed Pinterest Installation/Configuration Instructions</a></div>
            <div style="width:100%;"><strong>Account Nickname:</strong> <i>Just so you can easely identify it</i> </div><input name="pn[<?php echo $ii; ?>][nName]" id="pnnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /><br/>
            <?php echo nxs_addQTranslSel('pn', $ii, $options['qTLng']); ?><?php echo nxs_addPostingDelaySel('pn', $ii, $options['nHrs'], $options['nMin']); ?>
                  
            <div style="width:100%;"><strong>Pinterest Email:</strong> </div><input name="pn[<?php echo $ii; ?>][apPNUName]" id="apPNUName<?php echo $ii; ?>" class="apPNUName<?php echo $ii; ?>"  style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['pnUName'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />                
            <div style="width:100%;"><strong>Pinterest Password:</strong> </div><input name="pn[<?php echo $ii; ?>][apPNPass]" id="apPNPass<?php echo $ii; ?>" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($options['pnPass'], 0, 5)=='g9c1a'?nsx_doDecode(substr($options['pnPass'], 5)):$options['pnPass'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />  <br/>                
            <div style="width:100%;"><strong>Default Image to Pin:</strong> 
            <p style="font-size: 11px; margin: 0px;">If your post missing Featured Image this will be used instead.</p>
            </div><input name="pn[<?php echo $ii; ?>][apPNDefImg]" id="apPNDefImg" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['pnDefImg'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /> 
            <br/><br/>            
            
            <div style="width:100%;"><strong>Board:</strong> 
            Please <a href="#" onclick="getBoards(jQuery('<?php if ($isNew) echo "#nsx_addNT "; ?>#apPNUName<?php echo $ii; ?>').val(),jQuery('<?php if ($isNew) echo "#nsx_addNT "; ?>#apPNPass<?php echo $ii; ?>').val(), '<?php echo $ii; ?>'); return false;">click here to retrieve your boards</a>
            </div>
            <?php wp_nonce_field( 'getBoards', 'getBoards_wpnonce' ); ?><img id="pnLoadingImg" style="display: none;" src='http://gtln.us/img/misc/ajax-loader-sm.gif' />
            <select name="pn[<?php echo $ii; ?>][apPNBoard]" id="apPNBoard">
            <?php if ($options['pnBoardsList']!=''){ $gPNBoards = $options['pnBoardsList']; if ($options['pnBoard']!='') $gPNBoards = str_replace($options['pnBoard'].'"', $options['pnBoard'].'" selected="selected"', $gPNBoards);  echo $gPNBoards;} else { ?>
              <option value="0">None(Click above to retrieve your boards)</option>
            <?php } ?>
            </select>
            
            <br/><br/>            
            
            
            <div id="altFormat" style="">
              <div style="width:100%;"><strong id="altFormatText">Message Text Format:</strong>  <a href="#" id="apPNMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apPNMsgFrmt<?php echo $ii; ?>'); return false;">Show format info</a>             
              </div><input  name="pn[<?php echo $ii; ?>][apPNMsgFrmt]" id="apPNMsgFrmt" style="width: 50%;" value="<?php if ($options['pnMsgFormat']!='') _e(apply_filters('format_to_edit', htmlentities($options['pnMsgFormat'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster');  else echo "%TITLE% - %URL%"; ?>" onfocus="mxs_showFrmtInfo('apPNMsgFrmt<?php echo $ii; ?>');"  />
              
              <?php nxs_doShowHint("apPNMsgFrmt".$ii); ?>
            </div><br/>    
            
            <?php if ($options['pnPass']!='') { ?>
            <?php wp_nonce_field( 'rePostToPN', 'rePostToPN_wpnonce' ); ?>
            <b>Test your settings:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('PN', '<?php echo $ii; ?>'); return false;">Submit Test Post to Pinterest</a>         
            <?php } ?>
            
            <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div>
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
        if (isset($pval['apPNMsgFrmt'])) $options[$ii]['pnMsgFormat'] = trim($pval['apPNMsgFrmt']);     
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      }
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID;
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapPN', true));  if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); $doPN = $ntOpt['doPN'];   
        $isAvailPN =  $ntOpt['pnUName']!='' && $ntOpt['pnPass']!=''; $pnMsgFormat = $ntOpt['pnMsgFormat'];        
      ?>  
      <tr><th style="text-align:left;" colspan="2">
      <?php if ($isAvailPN) { ?><input class="nxsGrpDoChb" value="1" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="pn[<?php echo $ii; ?>][SNAPincludePN]" <?php if (($post->post_status == "publish" && $ntOpt['isPosted'] == '1') || ($post->post_status != "publish" && ((int)$doPN == 1)) ) echo 'checked="checked" title="def"';  ?> /> <?php } ?>
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/pn16.png);">Pinterest - publish to (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailPN) { ?><input alt="<?php echo $ii; ?>" style="float: right;" type="button" class="button" name="rePostToPN_repostButton" id="rePostToPN_button" value="<?php _e('Repost to Pinterest', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToPN', 'rePostToPN_wpnonce' ); } ?>
                </td></tr>                
                
                <?php if (!$isAvailPN) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Pinterest Account to AutoPost to Pinterest</b>
                <?php } elseif ($post->post_status != "publish") { ?> 
                
                <tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;">Select Board</th>
                <td><select name="pn[<?php echo $ii; ?>][apPNBoard]" id="apPNBoard">
            <?php if ($ntOpt['pnBoardsList']!=''){ $gPNBoards = $ntOpt['pnBoardsList']; if ($ntOpt['pnBoard']!='') $gPNBoards = str_replace($ntOpt['pnBoard'].'"', $ntOpt['pnBoard'].'" selected="selected"', $gPNBoards);  echo $gPNBoards;} else { ?>
              <option value="0">None(Click above to retrieve your boards)</option>
            <?php } ?>
            </select></td>
                </tr> 
                              
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;"><?php _e('Text Message Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $pnMsgFormat ?>" type="text" name="pn[<?php echo $ii; ?>][SNAPformat]" size="60px" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apPNMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apPNMsgFrmt".$ii); ?></td></tr>
                                
                <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){  if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['pnMsgFormat'] = $pMeta['SNAPformat'];      
     if (isset($pMeta['SNAPincludePN'])) $optMt['doPN'] = $pMeta['SNAPincludePN'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doPN'] = 0; }
     if (isset($pMeta['apPNBoard'])) $optMt['pnBoard'] = $pMeta['apPNBoard']; return $optMt;
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
  
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        nxs_addToLog($ntCd.' - '.$options['nName'], 'E', '-=Duplicate=- Post ID:'.$postID, 'Not posted. No reason for posting duplicate'); return;
    }     
  
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); 
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $msg = 'Test Post from '.$blogTitle; $link = home_url(); 
      if ($options['pnDefImg']!='') $imgURL = $options['pnDefImg']; else $imgURL ="http://direct.gtln.us/img/nxs/NextScriptsLogoT.png"; 
    }
    else { $post = get_post($postID); if(!$post) return; $pnMsgFormat = $options['pnMsgFormat'];  $msg = nsFormatMessage($pnMsgFormat, $postID); $link = get_permalink($postID); 
      nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); $imgURL = nxs_getPostImage($postID, 'large',  $options['ogImgDef']); 
    } 
    
    $email = $options['pnUName']; $boardID = $options['pnBoard'];  $pass = substr($options['pnPass'], 0, 5)=='g9c1a'?nsx_doDecode(substr($options['pnPass'], 5)):$options['pnPass'];// prr($boardID); prr($_POST); die();
    
    if (isset($options['pnSvC'])) $nxs_gCookiesArr = maybe_unserialize( $options['pnSvC']); $loginError = true;
    if (is_array($nxs_gCookiesArr)) $loginError = doCheckPinterest(); 
    $extInfo = ' | PostID: '.$postID." - ".$post->post_title; $logNT = '<span style="color:#FA5069">Pinterest</span> - '.$options['nName'];
    if ($loginError!==false) $loginError = doConnectToPinterest($email, $pass);  if ($loginError!==false) {echo $loginError; nxs_addToLog($logNT, 'E', '-=ERROR=- '.print_r($loginError, true), $extInfo); return "BAD USER/PASS";}  
    
    if (serialize($nxs_gCookiesArr)!=$options['pnSvC']) { global $plgn_NS_SNAutoPoster;  $gOptions = $plgn_NS_SNAutoPoster->nxs_options; // prr($gOptions['pn']);
        if (isset($options['ii']) && $options['ii']!=='')  { $gOptions['pn'][$options['ii']]['pnSvC'] = serialize($nxs_gCookiesArr); update_option('NS_SNAutoPoster', $gOptions);  }        
        else foreach ($gOptions['pn'] as $ii=>$gpn) { $result = array_diff($options, $gpn);
          if (!is_array($result) || count($result)<1) { $gOptions['pn'][$ii]['pnSvC'] = serialize($nxs_gCookiesArr); update_option('NS_SNAutoPoster', $gOptions); break; }
        }        
    } // echo "PN SET:".$msg."|".$imgURL."|".$link."|".$boardID;
    $ret = doPostToPinterest($msg, $imgURL, $link, $boardID);
    if ($ret!='OK') { if ($postID=='0') echo $ret; nxs_addToLog($logNT, 'E', '-=ERROR=- '.print_r($ret, true), $extInfo); } else { if ($postID=='0') {  nxs_addToLog($logNT, 'M', 'OK - TEST Message Posted '); echo 'OK - Message Posted, please see your Pinterest Page'; } else { nxs_metaMarkAsPosted($postID, 'PN', $options['ii']); nxs_addToLog($logNT, 'M', 'OK - Message Posted ', $extInfo);} }
    if ($ret == 'OK') return 200; else return $ret;
  }
}  
?>