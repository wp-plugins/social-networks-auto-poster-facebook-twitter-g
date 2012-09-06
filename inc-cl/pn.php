<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'PN', 'lcode'=>'pn', 'name'=>'Pinterest');

if (!class_exists("nxs_snapClassPN")) { class nxs_snapClassPN {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl,$nxsOne; $code = 'PN'; $lcode = 'pn'; wp_nonce_field( 'ns'.$code, 'ns'.$code.'_wpnonce' ); ?>
    <hr/><h3 style="font-size: 17px;">Pinterest Settings  
            
            <?php if(!function_exists('doPostToPinterest')) {?></h3>  Pinterest doesn't have a built-in API for automated posts yet. <br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/pinterest-automated-posting">library module</a> to be able to publish your content to Pinterest. <br/><br/>           
            
            <?php } else { 
              $cgpo = count($ntOpts); $mgpo = 1+max(array_keys($ntOpts));  $nxsOne .= "&p=1"; ?>            
              <div class="nsBigText">You have <?php echo $cgpo=='0'?'No':$cgpo; ?> Pinterest account<?php if ($cgpo!=1){ ?>s<?php } ?> <!--- <a href="#" class="NXSButton" onclick="doShowHideBlocks2('PN<?php echo $mgpo; ?>');return false;">Add new Google+ Account</a> --> </div> </h3>                  
              <?php // if (function_exists('nxs_doSMAS1')) nxs_doSMAS1($this, $mgpo); else nxs_doSMAS('Pinterest', 'PN'.$mgpo); ?>
              <?php foreach ($ntOpts as $indx=>$po){  ?>
                <p style="margin: 0px;margin-left: 5px;">
                  <input value="1" id="apDoPN" name="pn[<?php echo $indx; ?>][apDoPN]"  type="checkbox" <?php if ((int)$po['doPN'] == 1) echo "checked"; ?> /> 
                  <strong>Auto-publish your Posts to your <?php if($po['pnUName']!='') echo "(".$po['pnUName'].")"; ?> Pinterest <?php if($po['pnBoard']!='') echo "Board: ".$po['pnBoard']; else {?> Profile <?php } ?> </strong>                                         <a id="doPN<?php echo $indx; ?>A" href="#" onclick="doShowHideBlocks2('PN<?php echo $indx; ?>');return false;">[Show Settings]</a> &nbsp;&nbsp;
                  <a href="#" onclick="doDelAcct('pn','<?php echo $indx; ?>', '<?php echo $po['pnUName']; ?>');return false;">[Remove Account]</a>
                </p>            
                <?php $this->showNTSettings($indx, $po);             
              } ?>                        
            
            <?php } 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($mgpo){ $po = array('pnUName'=>'', 'pnBoard'=>'', 'gpAttch'=>'', 'pnPass'=>'', 'pnDefImg'=>'', 'pnMsgFormat'=>'', 'pnBoard'=>'', 'pnBoardsList'=>'', 'doPN'=>1); $this->showNTSettings($mgpo, $po, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  ?>
             <div id="doPN<?php echo $ii; ?>Div" <?php if ($isNew){ ?>class="clNewNTSets"<?php } ?> style="margin-left: 50px; display:none;">     <input type="hidden" name="apDoSPN<?php echo $ii; ?>" value="0" id="apDoSPN<?php echo $ii; ?>" />         
             
             <?php if(!function_exists('doPostToPinterest')) {?><span style="color:#580000; font-size: 16px;"><br/><br/>
            <b>Pinterest API Library not found</b>
             <br/><br/> Pinterest doesn't have a built-in API for automated posts yet.  <br/><br/>You need to get a special <a target="_blank" href="http://www.nextscripts.com/pinterest-automated-posting"><b>API Library Module</b></a> to be able to publish your content to Pinterest.</span></div>
            
            <?php return; }; ?>
             
           
            <div id="doPN<?php echo $ii; ?>Div" style="margin-left: 10px;"><br/>
                  
            <div style="width:100%;"><strong>Pinterest Username:</strong> </div><input name="pn[<?php echo $ii; ?>][apPNUName]" id="apPNUName<?php echo $ii; ?>" class="apPNUName<?php echo $ii; ?>"  style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['pnUName']), 'NS_SNAutoPoster') ?>" />                
            <div style="width:100%;"><strong>Pinterest Password:</strong> </div><input name="pn[<?php echo $ii; ?>][apPNPass]" id="apPNPass<?php echo $ii; ?>" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', substr($options['pnPass'], 0, 5)=='g9c1a'?nsx_doDecode(substr($options['pnPass'], 5)):$options['pnPass']), 'NS_SNAutoPoster') ?>" />  <br/>                
            <div style="width:100%;"><strong>Defailt Image to Pin:</strong> 
            <p style="font-size: 11px; margin: 0px;">If your post missing Featured Image this will be used instead.</p>
            </div><input name="pn[<?php echo $ii; ?>][apPNDefImg]" id="apPNDefImg" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['pnDefImg']), 'NS_SNAutoPoster') ?>" /> 
            <br/><br/>            
            
            <div style="width:100%;"><strong>Board:</strong> 
            Please <a href="#" onclick="getBoards(jQuery('<?php if ($isNew) echo "#nsx_addNT "; ?>#apPNUName<?php echo $ii; ?>').val(),jQuery('<?php if ($isNew) echo "#nsx_addNT "; ?>#apPNPass<?php echo $ii; ?>').val(), '<?php echo $ii; ?>'); return false;">click here to retreive your boards</a>
            </div>
            <?php wp_nonce_field( 'getBoards', 'getBoards_wpnonce' ); ?><img id="pnLoadingImg" style="display: none;" src='http://gtln.us/img/misc/ajax-loader-sm.gif' />
            <select name="pn[<?php echo $ii; ?>][apPNBoard]" id="apPNBoard">
            <?php if ($options['pnBoardsList']!=''){ $gPNBoards = $options['pnBoardsList']; if ($options['pnBoard']!='') $gPNBoards = str_replace($options['pnBoard'].'"', $options['pnBoard'].'" selected="selected"', $gPNBoards);  echo $gPNBoards;} else { ?>
              <option value="0">None(Click above to retreive your boards)</option>
            <?php } ?>
            </select>
            
            <br/><br/>            
            
            <div id="altFormat" style="">
              <div style="width:100%;"><strong id="altFormatText">Message Text Format:</strong> 
              <p style="font-size: 11px; margin: 0px;">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. &nbsp; %URL% - Inserts the URL of your post. &nbsp; %IMG% - Inserts the featured image. &nbsp; %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name.</p>
              </div><input name="pn[<?php echo $ii; ?>][apPNMsgFrmt]" id="apPNMsgFrmt" style="width: 50%;" value="<?php if ($options['pnMsgFormat']!='') _e(apply_filters('format_to_edit',$options['pnMsgFormat']), 'NS_SNAutoPoster');  else echo "%TITLE% - %URL%"; ?>" />
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
        if (isset($pval['apPNUName']))   $options[$ii]['pnUName'] = $pval['apPNUName'];
        if (isset($pval['apPNPass']))    $options[$ii]['pnPass'] = 'g9c1a'.nsx_doEncode($pval['apPNPass']); else $options[$ii]['pnPass'] = '';
        if (isset($pval['apPNBoard']))   $options[$ii]['pnBoard'] = $pval['apPNBoard'];                
        if (isset($pval['apPNDefImg']))  $options[$ii]['pnDefImg'] = $pval['apPNDefImg'];
        if (isset($pval['apPNMsgFrmt'])) $options[$ii]['pnMsgFormat'] = $pval['apPNMsgFrmt'];     
      }
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ $post_id = $post->ID;
     foreach($ntOpts as $ii=>$ntOpt)  { $doPN = $ntOpt['doPN'];   $isAvailPN =  $ntOpt['pnUName']!='' && $ntOpt['pnPass']!='';
        $t = get_post_meta($post_id, 'SNAP_FormatPN', true);  $pnMsgFormat = $t!=''?$t:$ntOpt['pnMsgFormat'];        
      ?>  
      <tr><th style="text-align:left;" colspan="2">Pinterest AutoPoster (<i style="color: #005800;"><?php echo $ntOpt['pnUName']." - ".$ntOpt['pnBoard']; ?></i>)</th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailPN) { ?><input alt="<?php echo $ii; ?>" style="float: right;" type="button" class="button" name="rePostToPN_repostButton" id="rePostToPN_button" value="<?php _e('Repost to Pinterest', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToPN', 'rePostToPN_wpnonce' ); } ?>
                </td></tr>                
                
                <?php if (!$isAvailPN) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Pinterest Account to AutoPost to Pinterest</b>
                <?php } elseif ($post->post_status != "publish") { ?> 
                
                <tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"><input class="nxsGrpDoChb" value="1" type="checkbox" name="SNAPincludePN" <?php if ((int)$doPN == 1) echo "checked"; ?> /></th>
                <td><b><?php _e('Publish this Post to Pinterest', 'NS_SPAP'); ?></b></td>
                </tr> 
                
                <tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;">Select Board</th>
                <td><select name="apPNBoard" id="apPNBoard">
            <?php if ($ntOpt['pnBoardsList']!=''){ $gPNBoards = $ntOpt['pnBoardsList']; if ($ntOpt['pnBoard']!='') $gPNBoards = str_replace($ntOpt['pnBoard'].'"', $ntOpt['pnBoard'].'" selected="selected"', $gPNBoards);  echo $gPNBoards;} else { ?>
              <option value="0">None(Click above to retreive your boards)</option>
            <?php } ?>
            </select></td>
                </tr> 
                              
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:80px; padding-right:10px;"><?php _e('Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $pnMsgFormat ?>" type="text" name="SNAP_FormatPN" size="60px"/></td></tr>
                
                <tr id="altFormat2" style=""><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">Format Options:</th>
                <td style="vertical-align:top; font-size: 9px;" colspan="2">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. <br/> %URL% - Inserts the URL of your post. &nbsp; %IMG% - Inserts the featured image. &nbsp;  %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name.</td></tr>
                <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){
     $optMt['pnMsgFormat'] = $pMeta['SNAPformat']; $optMt['doPN'] = $pMeta['SNAPincludePN'] == 1?1:0; $optMt['pnBoard'] = $pMeta['apPNBoard']; return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToPN_ajax")) {
  function nxs_rePostToPN_ajax() { check_ajax_referer('rePostToPN');  $postID = $_POST['id']; $options = get_option('NS_SNAutoPoster');  
    foreach ($options['pn'] as $ii=>$two) if ($ii==$_POST['nid']) {   //if ($two['gpPageID'].$two['gpUName']==$_POST['nid']) {  
      $po =  get_post_meta($postID, 'snapPN', true); $po =  maybe_unserialize($po);// prr($gppo);
      if (is_array($po) && isset($po[$ii]) && is_array($po[$ii])){ 
        $two['fbMsgFormat'] = $po[$ii]['SNAPformat']; $two['fbAttch'] = $po[$ii]['AttachPost'] == 1?1:0; 
      }
      $result = nxs_doPublishToPN($postID, $two); if ($result == 200) die("Successfully sent your post to Pinterest."); else die($result);        
    }    
  }
}  

if (!function_exists("nxs_doPublishToPN")) { //## Second Function to Post to G+
  function nxs_doPublishToPN($postID, $options){ global $nxs_gCookiesArr; if ($postID=='0') echo "Testing ... <br/><br/>";  $pnMsgFormat = $options['pnMsgFormat']; 
  
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); $isPost = isset($_POST["SNAPEdit"]); 
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $msg = 'Test Post from '.$blogTitle; $link = home_url(); 
      if ($options['pnDefImg']!='') $imgURL = $options['pnDefImg']; else $imgURL ="http://direct.gtln.us/img/nxs/NextScriptsLogoT.png"; $boardID = $options['pnBoard']; 
    }
    else{        
      if ($isPost) $pnMsgFormat = $_POST['SNAP_FormatPN']; else { $t = get_post_meta($postID, 'SNAP_FormatPN', true); $pnMsgFormat = $t!=''?$t:$options['pnMsgFormat']; } 
      if ($isPost) $boardID = $_POST['apPNBoard']; else { $t = get_post_meta($postID, 'apPNBoard', true); $boardID = $t!=''?$t:$options['pnBoard']; } 
      $msg = nsFormatMessage($pnMsgFormat, $postID); $link = get_permalink($postID);
    } 
    $email = $options['pnUName'];  $pass = substr($options['pnPass'], 0, 5)=='g9c1a'?nsx_doDecode(substr($options['pnPass'], 5)):$options['pnPass'];// prr($boardID); prr($_POST); die();
    
    if ($imgURL=='') if (function_exists("get_post_thumbnail_id") ){ $imgURL = wp_get_attachment_image_src(get_post_thumbnail_id($postID), 'large'); $imgURL = $imgURL[0];} 
    if ($imgURL=='') {$post = get_post($postID); $imgsFromPost = nsFindImgsInPost($post);  if (is_array($imgsFromPost) && count($imgsFromPost)>0) $imgURL = $imgsFromPost[0]; }
    if ($imgURL=='') $imgURL = $options['pnDefImg']; if ($imgURL=='') $imgURL = $options['ogImgDef']; $msg = urlencode($msg);  // prr($msg);
    
    if (isset($options['pnSvC'])) $nxs_gCookiesArr = maybe_unserialize( $options['pnSvC']); $loginError = true;
    if (is_array($nxs_gCookiesArr)) $loginError = doCheckPinterest(); 
    
    if ($loginError!==false) $loginError = doConnectToPinterest($email, $pass);  if ($loginError!==false) {echo $loginError; return "BAD USER/PASS";}  
    
    if (serialize($nxs_gCookiesArr)!=$options['pnSvC']) { global $plgn_NS_SNAutoPoster;  $gOptions = $plgn_NS_SNAutoPoster->nxs_options; // prr($gOptions['pn']);
        foreach ($gOptions['pn'] as $ii=>$gpn) { $result = array_diff($options, $gpn);
          if (!is_array($result) || count($result)<1) { $gOptions['pn'][$ii]['pnSvC'] = serialize($nxs_gCookiesArr); update_option('NS_SNAutoPoster', $gOptions); break; }
        }        
    }
    $ret = doPostToPinterest($msg, $imgURL, $link, $boardID);
    if ($ret!='OK') echo $ret; else if ($postID=='0') echo 'OK - Message Posted, please see your Pinterest Page';
  }
}  
?>