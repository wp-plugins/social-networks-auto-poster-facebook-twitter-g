<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'WP', 'lcode'=>'wp', 'name'=>'WP Based Blog');

if (!class_exists("nxs_snapClassWP")) { class nxs_snapClassWP {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){  global $nxs_plurl; $ntInfo = array('code'=>'WP', 'lcode'=>'wp', 'name'=>'WP Based Blog', 'defNName'=>'dlUName', 'tstReq' => false); ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> <?php wp_nonce_field( 'ns'.$ntInfo['code'], 'ns'.$ntInfo['code'].'_wpnonce' ); ?>
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = str_ireplace('/xmlrpc.php','', str_ireplace('http://','', str_ireplace('https://','', $pbo['wpURL']))); ?>
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
  function showNewNTSettings($mgpo){ $gpo = array('nName'=>'', 'doWP'=>'1', 'wpUName'=>'', 'wpPageID'=>'', 'wpAttch'=>'', 'wpPass'=>'', 'wpURL'=>''); $this->showNTSettings($mgpo, $gpo, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $gpo, $isNew=false){ global $nxs_plurl; ?>
            <div id="doWP<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>" style="background-image: url(<?php echo $nxs_plurl; ?>img/wp-bg.png);  background-position:90% 10%;">     <input type="hidden" name="apDoSWP<?php echo $ii; ?>" value="0" id="apDoSWP<?php echo $ii; ?>" />
            
            <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/wp16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-wp-based-social-networks-auto-poster-wordpress/"><?php $nType="Wordpress"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
            
            <?php if ($isNew){ ?> <br/>You can setup any Wordpress based blog with activated XML-RPC support (WP Admin->Settimgs->Writing->Remote Publishing->Check XML-RPC). Wordpress.com and Blog.com supported as well.<br/><br/> <?php } ?> 
            
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easely identify it', 'nxs_snap'); ?></i> </div><input name="wp[<?php echo $ii; ?>][nName]" id="wpnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($gpo['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('wp', $ii, $gpo['qTLng']); ?><?php echo nxs_addPostingDelaySel('wp', $ii, $gpo['nHrs'], $gpo['nMin']); ?>
            
             <?php if (!$isNew) { ?>
    <div style="width:100%;"><strong><?php _e('Categories', 'nxs_snap'); ?>:</strong>
       <input value="0" id="catSelA<?php echo $ii; ?>" type="radio" name="wp[<?php echo $ii; ?>][catSel]" <?php if ((int)$gpo['catSel'] != 1) echo "checked"; ?> /> All                                  
       <input value="1" id="catSelSWP<?php echo $ii; ?>" type="radio" name="wp[<?php echo $ii; ?>][catSel]" <?php if ((int)$gpo['catSel'] == 1) echo "checked"; ?> /> <a href="#" style="text-decoration: none;" class="showCats" id="nxs_SCA_WP<?php echo $ii; ?>" onclick="jQuery('#catSelSWP<?php echo $ii; ?>').attr('checked', true); jQuery('#tmpCatSelNT').val('WP<?php echo $ii; ?>'); nxs_markCats( jQuery('#nxs_SC_WP<?php echo $ii; ?>').val() ); jQuery('#showCatSel').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false], position: [75, 'auto']}); return false;">Selected<?php if ($gpo['catSelEd']!='') echo "[".(substr_count($gpo['catSelEd'], ",")+1)."]"; ?></a>       
       <input type="hidden" name="wp[<?php echo $ii; ?>][catSelEd]" id="nxs_SC_WP<?php echo $ii; ?>" value="<?php echo $gpo['catSelEd']; ?>" />
    <br/><i><?php _e('Only selected categories will be autoposted to this account', 'nxs_snap'); ?></i></div> 
    <br/>
    <?php } ?>
            
            <div style="width:100%;"><strong>XMLRPC URL:</strong> </div><input name="wp[<?php echo $ii; ?>][apWPURL]" id="apWPURL" style="width: 50%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($gpo['wpURL'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />
            <p style="font-size: 11px; margin: 0px;">Usually its a URL of your Wordpress installation with /xmlrpc.php at the end.<br/> Please use <b style="color: #005800;">http://YourUserName.wordpress.com/xmlrpc.php</b> (replace YourUserName with your user name - for example <i style="color: #005800;">http://nextscripts.wordpress.com/xmlrpc.php</i>) for Wordpress.com blogs. <br/> Please  use <b style="color: #005800;">http://YourUserName.blog.com/xmlrpc.php</b> (replace YourUserName with your user name - for example <i style="color: #005800;">http://nextscripts.blog.com/xmlrpc.php</i> for Blog.com blogs</p>
            
            <div style="width:100%;"><br/><strong>Blog Username:</strong> </div><input name="wp[<?php echo $ii; ?>][apWPUName]" id="apWPUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($gpo['wpUName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
            <div style="width:100%;"><strong>Blog Password:</strong> </div><input name="wp[<?php echo $ii; ?>][apWPPass]" id="apWPPass" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($gpo['wpPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($gpo['wpPass'], 5)):$gpo['wpPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/>                
            
            <?php if ($isNew) { ?> <input type="hidden" name="wp[<?php echo $ii; ?>][apDoWP]" value="1" id="apDoNewWP<?php echo $ii; ?>" /> <?php } ?>
            
            <br/>
              
            <div id="altFormat" style="">
              <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Title Format', 'nxs_snap'); ?></strong>               
(<a href="#" id="apWPMsgTFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apWPMsgTFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)              
              </div>
  <input name="wp[<?php echo $ii; ?>][apWPMsgTFrmt]" id="apWPMsgTFrmt" style="width: 50%;"  onfocus="mxs_showFrmtInfo('apWPMsgTFrmt<?php echo $ii; ?>');"  value="<?php if ($isNew) echo "%TITLE%"; else _e(apply_filters('format_to_edit', htmlentities($gpo['wpMsgTFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?>" /> <?php nxs_doShowHint("apWPMsgTFrmt".$ii); ?>
  
            </div>            
            <div id="altFormat" style="">
              <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Text Format', 'nxs_snap'); ?></strong>               
              (<a href="#" id="apWPMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apWPMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)
              </div>
              
    
  <textarea cols="150" rows="3" id="wp<?php echo $ii; ?>SNAPformat" name="wp[<?php echo $ii; ?>][apWPMsgFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#wp<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apWPMsgFrmt<?php echo $ii; ?>');"><?php if ($isNew) echo "%EXCERPT%"; else _e(apply_filters('format_to_edit', htmlentities($gpo['wpMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?></textarea>
  <?php nxs_doShowHint("apWPMsgFrmt".$ii); ?>
  
            </div><br/>    
            
            <?php if ($gpo['wpPass']!='') { ?>
            <?php wp_nonce_field( 'rePostToWP', 'rePostToWP_wpnonce' ); ?>
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('WP', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>      
               
            <?php } 
            
            ?><div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div></div><?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'WP'; $lcode = 'wp'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apWPUName']) && $pval['apWPUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apWPURL']))   $options[$ii]['wpURL'] = trim($pval['apWPURL']);   if ( substr($options[$ii]['wpURL'], 0, 4)!='http' )  $options[$ii]['wpURL'] = 'http://'.$options[$ii]['wpURL'];
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apWPUName']))   $options[$ii]['wpUName'] = trim($pval['apWPUName']);
        if (isset($pval['apWPPass']))    $options[$ii]['wpPass'] = 'n5g9a'.nsx_doEncode($pval['apWPPass']); else $options[$ii]['wpPass'] = '';  
        if (isset($pval['apWPMsgFrmt'])) $options[$ii]['wpMsgFormat'] = trim($pval['apWPMsgFrmt']);                                                  
        if (isset($pval['apWPMsgTFrmt'])) $options[$ii]['wpMsgTFormat'] = trim($pval['apWPMsgTFrmt']);               
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']);
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
                                           
        if (isset($pval['apDoWP']))      $options[$ii]['doWP'] = $pval['apDoWP']; else $options[$ii]['doWP'] = 0; 
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      }
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID;
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapWP', true));  if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
        $doWP = $ntOpt['doWP'] && (is_array($pMeta) || $ntOpt['catSel']!='1');   
        $isAvailWP =  $ntOpt['wpUName']!='' && $ntOpt['wpPass']!=''; $wpMsgFormat = htmlentities($ntOpt['wpMsgFormat'], ENT_COMPAT, "UTF-8"); $wpMsgTFormat = htmlentities($ntOpt['wpMsgTFormat'], ENT_COMPAT, "UTF-8");      
      ?>  
      <tr><th style="text-align:left;" colspan="2"><?php if ( $ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='' )  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_WP<?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if ($isAvailWP) { ?><input class="nxsGrpDoChb" value="1" id="doWP<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="wp[<?php echo $ii; ?>][doWP]" <?php if ((int)$doWP == 1 ) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="wp[<?php echo $ii; ?>][doWP]" value="<?php echo $doWP;?>"> <?php } ?> <?php } ?>
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/wp16.png);">WP Blog - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailWP) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToWP_repostButton" id="rePostToWP_button" value="<?php _e('Repost to WP Blog', 'nxs_snap') ?>" />
                    <?php wp_nonce_field( 'rePostToWP', 'rePostToWP_wpnonce' ); } ?>
                    
                     <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) { $wpURL = str_ireplace('/xmlrpc.php', '', $ntOpt['wpURL']);
                        if (substr($wpURL, -1)=='/') $wpURL = substr($wpURL, 0, -1);  $wpURL = $wpURL."/";
                        ?> <span id="pstdWP<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
          <a style="font-size: 10px;" href="<?php echo $wpURL; ?>?p=<?php echo $pMeta[$ii]['pgID']; ?>" target="_blank"><?php $nType="Wordpress Blog"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?> <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>                
                
                <?php if (!$isAvailWP) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your WP Blog Account to AutoPost to WP Blogs</b>
                <?php } elseif ($post->post_status != "puZblish") { ?> 
                                
                <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top:6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Title Format:', 'nxs_snap') ?></th>
                <td><input value="<?php echo $wpMsgTFormat ?>" type="text" name="wp[<?php echo $ii; ?>][SNAPformatT]"  style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apWPTMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apWPTMsgFrmt".$ii); ?></td></tr>
                
                <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top:6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Text Format:', 'nxs_snap') ?></th>
                <td>               
                <textarea cols="150" rows="1" id="wp<?php echo $ii; ?>SNAPformat" name="wp[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#wp<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apWPMsgFrmt<?php echo $ii; ?>');"><?php echo $wpMsgFormat; ?></textarea>
                <?php nxs_doShowHint("apWPMsgFrmt".$ii); ?></td></tr>
  
  <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){  if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else $optMt['isPosted'] = '';
    if (isset($pMeta['SNAPformat'])) $optMt['wpMsgFormat'] = $pMeta['SNAPformat']; 
    if (isset($pMeta['SNAPformatT'])) $optMt['wpMsgTFormat'] = $pMeta['SNAPformatT'];  
    if (isset($pMeta['doWP'])) $optMt['doWP'] = $pMeta['doWP'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doWP'] = 0; } 
    if (isset($pMeta['SNAPincludeWP']) && $pMeta['SNAPincludeWP'] == '1' ) $optMt['doWP'] = 1;  
    return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToWP_ajax")) {
  function nxs_rePostToWP_ajax() { check_ajax_referer('rePostToWP');  $postID = $_POST['id']; $options = get_option('NS_SNAutoPoster');  
    foreach ($options['wp'] as $ii=>$two) if ($ii==$_POST['nid']) {   $two['ii'] = $ii;  $two['pType'] = 'aj';//if ($two['gpPageID'].$two['gpUName']==$_POST['nid']) {  
      $gppo =  get_post_meta($postID, 'snapWP', true); $gppo =  maybe_unserialize($gppo);// prr($gppo);
      if (is_array($gppo) && isset($gppo[$ii]) && is_array($gppo[$ii])){ $ntClInst = new nxs_snapClassWP(); $two = $ntClInst->adjMetaOpt($two, $gppo[$ii]); }
      $result = nxs_doPublishToWP($postID, $two); if ($result == 200) die("Successfully sent your post to WP Blog."); else die($result);        
    }    
  }
}  

if (!function_exists("nxs_doPublishToWP")) { //## Second Function to Post to WP
  function nxs_doPublishToWP($postID, $options){ $ntCd = 'WP'; $ntCdL = 'wp'; $ntNm = 'WP Based Blog';
    //if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToWP',  array($postID, $options));      
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
    $logNT = '<span style="color:#1A9EE6">WP</span> - '.$options['nName'];
    $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') {  sleep(5);
         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$uqID); return;
        }
    } 
      $imgURL = nxs_getPostImage($postID);
      $email = $options['wpUName'];  $pass = substr($options['wpPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['wpPass'], 5)):$options['wpPass'];      
      if ($postID=='0') { echo "Testing ... <br/><br/>";  $link = home_url(); $msgT = 'Test Link from '.$link; $msg = 'Test post please ignore'; } else { $post = get_post($postID); if(!$post) return; $link = get_permalink($postID); 
        $msgFormat = $options['wpMsgFormat']; $msg = nsFormatMessage($msgFormat, $postID); $msgTFormat = $options['wpMsgTFormat']; $msgT = nsFormatMessage($msgTFormat, $postID);      
        nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); 
      }
      $dusername = $options['wpUName'];  $link = urlencode($link); $desc = urlencode(substr($msgT, 0, 250)); $ext = urlencode(substr($msg, 0, 1000));
      $t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = implode(',',$tggs);      
      $postCats = wp_get_post_categories($postID); $cats = array();  foreach($postCats as $c){ $cat = get_category($c); $cats[] = str_ireplace('&','&amp;',$cat->name); } // $cats = implode(',',$catsA);

      //## Post   
      require_once ('apis/xmlrpc-client.php'); $nxsToWPclient = new NXS_XMLRPC_Client($options['wpURL']); $nxsToWPclient->debug = false;
      if ($imgURL!=='' && stripos($imgURL, 'http')!==false) {      
        // $handle = fopen($imgURL, "rb"); $filedata = ''; while (!feof($handle)) {$filedata .= fread($handle, 8192);} fclose($handle);
        $filedata = wp_remote_get($imgURL); if (! is_wp_error($filedata) ) $filedata = $filedata['body']; // echo "AWC?";
        $data = array('name'  => 'image-'.$postID.'.jpg', 'type'  => 'image/jpg', 'bits'  => new NXS_XMLRPC_Base64($filedata), true); 
        $status = $nxsToWPclient->query('metaWeblog.newMediaObject', $postID, $options['wpUName'], $pass, $data);  $imgResp = $nxsToWPclient->getResponse();  $gid = $imgResp['id'];
      } else $gid = '';
      
      $params = array(0, $options['wpUName'], $pass, array('software_version')); 
      if (!$nxsToWPclient->query('wp.getOptions', $params)) { $ret = 'Something went wrong - '.$nxsToWPclient->getErrorCode().' : '.$nxsToWPclient->getErrorMessage();} else $ret = 'OK';
      $rwpOpt = $nxsToWPclient->getResponse();  $rwpOpt = $rwpOpt['software_version']['value']; $rwpOpt = floatval($rwpOpt); //prr($rwpOpt);prr($nxsToWPclient);
      
      $extInfo = ' | PostID: '.$postID." - ".$post->post_title; 
      
      if ($rwpOpt==0) { 
        $errMsg = $nxsToWPclient->getErrorMessage(); if ($errMsg!='') $ret = $errMsg; else  $ret = 'XMLRPC is not found or not active. WP admin - Settings - Writing - Enable XML-RPC'; 
      } else if ($rwpOpt<3.0)  $ret = 'XMLRPC is too OLD - '.$rwpOpt.' You need at least 3.0'; else {
       
        if ($rwpOpt>3.3){
          $nxsToWPContent = array('title'=>$msgT, 'description'=>$msg, 'post_status'=>'draft', 'mt_excerpt'=>$post->post_excerpt, 'mt_allow_comments'=>1, 'mt_allow_pings'=>1, 'post_type'=>'post', 'mt_keywords'=>$tags, 'categories'=>($cats), 'custom_fields' =>  $customfields);
          $params = array(0, $options['wpUName'], $pass, $nxsToWPContent, true);
          if (!$nxsToWPclient->query('metaWeblog.newPost', $params)) { $ret = 'Something went wrong - '.$nxsToWPclient->getErrorCode().' : '.$nxsToWPclient->getErrorMessage();} else $ret = 'OK';
          $pid = $nxsToWPclient->getResponse();  
       
          if ($gid!='') {      
            $nxsToWPContent = array('post_thumbnail'=>$gid);  $params = array(0, $options['wpUName'], $pass, $pid, $nxsToWPContent, true);      
            if (!$nxsToWPclient->query('wp.editPost', $params)) { $ret = 'Something went wrong - '.$nxsToWPclient->getErrorCode().' : '.$nxsToWPclient->getErrorMessage();} else $ret = 'OK';
          }
          $nxsToWPContent = array('post_status'=>'publish');  $params = array(0, $options['wpUName'], $pass, $pid, $nxsToWPContent, true);      
          if (!$nxsToWPclient->query('wp.editPost', $params)) { $ret = 'Something went wrong - '.$nxsToWPclient->getErrorCode().' : '.$nxsToWPclient->getErrorMessage();} else $ret = 'OK';
        } else {
          $nxsToWPContent = array('title'=>$msgT, 'description'=>$msg, 'post_status'=>'publish', 'mt_allow_comments'=>1, 'mt_allow_pings'=>1, 'post_type'=>'post', 'mt_keywords'=>$tags, 'categories'=>($cats), 'custom_fields' =>  $customfields);
          $params = array(0, $options['wpUName'], $pass, $nxsToWPContent, true);
          if (!$nxsToWPclient->query('metaWeblog.newPost', $params)) { $ret = 'Something went wrong - '.$nxsToWPclient->getErrorCode().' : '.$nxsToWPclient->getErrorMessage();} else $ret = 'OK';
          $pid = $nxsToWPclient->getResponse();  
        }
      } if ($ret!='OK') { if ($postID=='0') echo $ret; 
        nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo);
      } else { if ($postID=='0') { echo 'OK - Message Posted, please see your WP Blog'; nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); } else 
        { nxs_metaMarkAsPosted($postID, 'WP', $options['ii'], array('isPosted'=>'1', 'pgID'=>$pid, 'pDate'=>date('Y-m-d H:i:s'))); 
          do_action('nxs_actOnWP', array('postID'=>$postID, 'pgID'=>$pid, 'wpURL'=>$options['wpURL'], 'ii'=>$ii)); nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo);
        } 
      }
      if ($ret == 'OK') return 200; else return $ret;
  }
}  
?>