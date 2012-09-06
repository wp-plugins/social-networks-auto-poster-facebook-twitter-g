<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'DL', 'lcode'=>'dl', 'name'=>'Delicious');

if (!class_exists("nxs_snapClassDL")) { class nxs_snapClassDL {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl, $nxsOne; $code = 'DL'; $lcode = 'dl'; wp_nonce_field( 'ns'.$code, 'ns'.$code.'_wpnonce' ); ?>
    <hr/><div style="font-size: 17px;font-weight: bold; margin-bottom: 15px;">Delicious Settings:           
            <?php $cgpo = count($ntOpts); $mgpo = 1+max(array_keys($ntOpts)); $nxsOne .= "&g=1"; ?>            
              <div class="nsBigText">You have <?php echo $cgpo=='0'?'No':$cgpo; ?> Delicious account<?php if ($cgpo!=1){ ?>s<?php } ?>  </div></div> 
              <?php  //if (function_exists('nxs_doSMAS1')) nxs_doSMAS1($this, $mgpo); else nxs_doSMAS('Google+', 'GP'.$mgpo); ?>
              <?php foreach ($ntOpts as $indx=>$gpo){  ?>
                <p style="margin: 0px;margin-left: 5px;">
                  <input value="1" id="apDoDL" name="dl[<?php echo $indx; ?>][apDoDL]" onchange="doShowHideBlocks('DL');" type="checkbox" <?php if ((int)$gpo['doDL'] == 1) echo "checked"; ?> /> 
                  <strong>Auto-publish your Posts to your <?php if($gpo['dlUName']!='') echo "(".$gpo['dlUName'].")"; ?> Delicious Account </strong>                                         <a id="doDL<?php echo $indx; ?>A" href="#" onclick="doShowHideBlocks2('DL<?php echo $indx; ?>');return false;">[Show Settings]</a> &nbsp;&nbsp;
                  <a href="#" onclick="doDelAcct('dl','<?php echo $indx; ?>', '<?php echo $gpo['dlUName']; ?>');return false;">[Remove Account]</a>
                </p>            
                <?php $this->showNTSettings($indx, $gpo);             
              } ?>            
            <?php 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($mgpo){ $gpo = array('dlUName'=>'', 'dlPageID'=>'', 'dlAttch'=>'', 'dlPass'=>''); $this->showNTSettings($mgpo, $gpo, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $gpo, $isNew=false){  ?>
            <div id="doDL<?php echo $ii; ?>Div" <?php if ($isNew){ ?>class="clNewNTSets"<?php } ?> style="margin-left: 50px; display:none;">     <input type="hidden" name="apDoSDL<?php echo $ii; ?>" value="0" id="apDoSDL<?php echo $ii; ?>" />                      <br/>
            <div style="width:100%;"><strong>Delicious Username:</strong> </div><input name="dl[<?php echo $ii; ?>][apDLUName]" id="apDLUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$gpo['dlUName']), 'NS_SNAutoPoster') ?>" />                
            <div style="width:100%;"><strong>Delicious Password:</strong> </div><input name="dl[<?php echo $ii; ?>][apDLPass]" id="apDLPass" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', substr($gpo['dlPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($gpo['dlPass'], 5)):$gpo['dlPass']), 'NS_SNAutoPoster') ?>" />  <br/>                
            
            <?php if ($isNew) { ?> <input type="hidden" name="dl[<?php echo $ii; ?>][apDoDL]" value="1" id="apDoNewDL<?php echo $ii; ?>" /> <?php } ?>
            <br/> 
            <strong id="altFormatText">Post Title and Post Text Formats</strong>
            <p style="font-size: 11px; margin: 0px;">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. &nbsp; %URL% - Inserts the URL of your post. &nbsp; %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name.</p>
            
            <div id="altFormat" style="">
              <div style="width:100%;"><strong id="altFormatText">Post Title Format</strong>               
              </div><input name="dl[<?php echo $ii; ?>][apDLMsgTFrmt]" id="apDLMsgTFrmt" style="width: 50%;" value="<?php if ($isNew) echo "%TITLE%"; else _e(apply_filters('format_to_edit',$gpo['dlMsgTFormat']), 'NS_SNAutoPoster'); ?>" />
            </div>   
            
            <div id="altFormat" style="">
              <div style="width:100%;"><strong id="altFormatText">Post Text Format</strong> </div>
              <input name="dl[<?php echo $ii; ?>][apDLMsgFrmt]" id="apDLMsgFrmt" style="width: 50%;" value="<?php if ($isNew) echo "%TEXT%"; else _e(apply_filters('format_to_edit',$gpo['dlMsgFormat']), 'NS_SNAutoPoster'); ?>" />
            </div><br/>    
            
            <?php if ($gpo['dlPass']!='') { ?>
            <?php wp_nonce_field( 'rePostToDL', 'rePostToDL_wpnonce' ); ?>
            <b>Test your settings:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('DL', '<?php echo $ii; ?>'); return false;">Submit Test Post to Delicious</a>      
               
            <?php } 
            
            ?><div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div></div><?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'DL'; $lcode = 'dl'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apDLUName']) && $pval['apDLUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apDLUName']))   $options[$ii]['dlUName'] = $pval['apDLUName'];
        if (isset($pval['apDLPass']))    $options[$ii]['dlPass'] = 'n5g9a'.nsx_doEncode($pval['apDLPass']); else $options[$ii]['dlPass'] = '';  
        if (isset($pval['apDLMsgFrmt'])) $options[$ii]['dlMsgFormat'] = $pval['apDLMsgFrmt'];                                                  
        if (isset($pval['apDLMsgTFrmt'])) $options[$ii]['dlMsgTFormat'] = $pval['apDLMsgTFrmt'];                                                  
        if (isset($pval['apDoDL']))      $options[$ii]['doDL'] = $pval['apDoDL']; else $options[$ii]['doDL'] = 0; 
      }
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ $post_id = $post->ID;
     foreach($ntOpts as $ii=>$ntOpt)  { $doDL = $ntOpt['doDL'];   $isAvailDL =  $ntOpt['dlUName']!='' && $ntOpt['dlPass']!='';
        $t = get_post_meta($post_id, 'SNAP_FormatDL', true);  $dlMsgFormat = $t!=''?$t:$ntOpt['dlMsgFormat'];      
        $t = get_post_meta($post_id, 'SNAP_FormatTDL', true);  $dlMsgTFormat = $t!=''?$t:$ntOpt['dlMsgTFormat'];      
      ?>  
      <tr><th style="text-align:left;" colspan="2">Delicious AutoPoster (<i style="color: #005800;"><?php echo $ntOpt['dlUName']; ?></i>)</th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailDL) { ?><input alt="<?php echo $ii; ?>" style="float: right;" type="button" class="button" name="rePostToDL_repostButton" id="rePostToDL_button" value="<?php _e('Repost to Delicious', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToDL', 'rePostToDL_wpnonce' ); } ?>
                </td></tr>                
                
                <?php if (!$isAvailDL) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Delicious Account to AutoPost to Delicious</b>
                <?php } elseif ($post->post_status != "publish") { ?> 
                
                <tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"><input class="nxsGrpDoChb" value="1" type="checkbox" name="dl[<?php echo $ii; ?>][SNAPincludeDL]" <?php if ((int)$doDL == 1) echo "checked"; ?> /></th>
                <td><b><?php _e('Publish this Post to Delicious', 'NS_SPAP'); ?></b> </td>
               </tr>
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:80px; padding-right:10px;"><?php _e('Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $dlMsgTFormat ?>" type="text" name="dl[<?php echo $ii; ?>][SNAPformatT]" size="60px"/></td></tr>
                
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:80px; padding-right:10px;"><?php _e('Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $dlMsgFormat ?>" type="text" name="dl[<?php echo $ii; ?>][SNAPformat]" size="60px"/></td></tr>
                
                <tr id="altFormat2" style=""><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">Format Options:</th>
                <td style="vertical-align:top; font-size: 9px;" colspan="2">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. <br/> %URL% - Inserts the URL of your post. &nbsp; %IMG% - Inserts the featured image. &nbsp;  %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name.</td></tr>

                <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){
     $optMt['dlMsgFormat'] = $pMeta['SNAPformat']; $optMt['dlMsgTFormat'] = $pMeta['SNAPformatT'];  $optMt['doDL'] = $pMeta['SNAPincludeDL'] == 1?1:0; return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToDL_ajax")) {
  function nxs_rePostToDL_ajax() { check_ajax_referer('rePostToDL');  $postID = $_POST['id']; $options = get_option('NS_SNAutoPoster');  
    foreach ($options['dl'] as $ii=>$two) if ($ii==$_POST['nid']) {   //if ($two['gpPageID'].$two['gpUName']==$_POST['nid']) {  
      $gppo =  get_post_meta($postID, 'snapDL', true); $gppo =  maybe_unserialize($gppo);// prr($gppo);
      if (is_array($gppo) && isset($gppo[$ii]) && is_array($gppo[$ii])){ 
        $two['fbMsgFormat'] = $gppo[$ii]['SNAPformat']; $two['fbAttch'] = $gppo[$ii]['AttachPost'] == 1?1:0; 
      }
      $result = nxs_doPublishToDL($postID, $two); if ($result == 200) die("Successfully sent your post to Delicious."); else die($result);        
    }    
  }
}  
if (!function_exists("doConnectToDelicious")) { function doConnectToDelicious($u, $p){ global $nxs_gCookiesArr;  $nxs_gCookiesArr = array(); $advSettings = array();
  $fldsTxt = 'username='.$u.'&password='.$p;
  $contents = getCurlPageX(' https://www.delicious.com/login ','', false, $fldsTxt, false, $advSettings);   prr($nxs_gCookiesArr);   prr($contents);
}}
if (!function_exists("doPostToDelicious")) { function doPostToDelicious($postID, $options){  global $nxs_gCookiesArr; 

}}
if (!function_exists("nxs_doPublishToDL")) { //## Second Function to Post to DL
  function nxs_doPublishToDL($postID, $options){ if ($postID=='0') echo "Testing ... <br/><br/>";  $msgFormat = $options['dlMsgFormat']; $msgTFormat = $options['dlMsgTFormat']; 
      
      if ($isPost) $msgTFormat = $_POST['SNAPformatT']; else { $t = get_post_meta($postID, 'SNAPformatT', true); $msgTFormat = $t!=''?$t:$options['dlMsgTFormat']; } 
      $msgT = nsFormatMessage($msgTFormat, $postID);        
      if ($isPost) $msgFormat = $_POST['SNAPformat']; else { $t = get_post_meta($postID, 'SNAPformat', true); $msgFormat = $t!=''?$t:$options['dlMsgFormat']; } 
      $msg = nsFormatMessage($msgFormat, $postID); 
      
      if (function_exists("get_post_thumbnail_id") ){ $src = wp_get_attachment_image_src(get_post_thumbnail_id($postID), 'thumbnail'); $src = $src[0];}      
      $email = $options['dlUName'];  $pass = substr($options['dlPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['dlPass'], 5)):$options['dlPass'];             
      
      if ($postID=='0') { $link = home_url(); $msgT = 'Test Link from '.$link; } else { $link = get_permalink($postID); $img = $src; }
      $dusername = $options['dlUName']; $api = "api.del.icio.us/v1"; $link = urlencode($link); $desc = urlencode(substr($msgT, 0, 250)); $ext = urlencode(substr($msg, 0, 1000));
      $t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = urlencode(implode(',',$tggs));     $tags = str_replace(' ','+',$tags); 
      $apicall = "https://$dusername:$pass@$api/posts/add?&url=$link&description=$desc&extended=$ext&tags=$tags"; 
      $cnt = wp_remote_get( $apicall, '' ); //prr($cnt['body']);      
      if (stripos($cnt['body'],'code="done"')!==false) $ret = 'OK'; else $ret = 'something went wrong - '."https://$dusername:*********@$api/posts/add?&url=$link&description=$desc&extended=$ext&tags=$tags";      
      if ($ret!='OK') echo $ret; else if ($postID=='0') echo 'OK - Message Posted, please see your Delicious Page';
  }
}  
?>