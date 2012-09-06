<?php    
//## NextScripts Twitter Connection Class
$nxs_snapAvNts[] = array('code'=>'TW', 'lcode'=>'tw', 'name'=>'Twitter');

if (!class_exists("nxs_snapClassTW")) { class nxs_snapClassTW {
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl; $code = 'TW'; $lcode = 'tw'; wp_nonce_field( 'ns'.$code, 'ns'.$code.'_wpnonce' ); ?>
    <hr/><div style="font-size: 17px; font-weight: bold; margin-bottom: 15px;">Twitter Settings:     <?php $ctwo = count($ntOpts); $mtwo = 1+max(array_keys($ntOpts)); ?>        
    <div class="nsBigText">You have <?php echo $ctwo=='0'?'No':$ctwo; ?> Twitter account<?php if ($ctwo!=1){ ?>s<?php } ?> <!-- set - <a href="#" class="NXSButton" onclick="doShowHideBlocks2('TW<?php echo $mtwo; ?>');return false;">Add new Twitter Account</a> --></div><br/></div>
    <?php // if (function_exists('nxs_doSMAS1')) nxs_doSMAS1($this, $mtwo); else nxs_doSMAS('Twitter', 'TW'.$mtwo);  ?>            
    <?php foreach ($ntOpts as $indx=>$two){ ?>            
      <p style="margin: 0px;margin-left: 5px;"><input value="1" id="apDoTW" name="tw[<?php echo $indx; ?>][apDoTW]" type="checkbox" <?php if ((int)$two['doTW'] == 1) echo "checked"; ?> /> 
        <strong>Auto-publish your Posts to your Twitter <?php if($two['twURL']!='') echo "(".str_ireplace('https://','', str_ireplace('http://','', $two['twURL'])).")"; ?></strong>                                 
        <a id="doTW<?php echo $indx; ?>A" href="#" onclick="doShowHideBlocks2('TW<?php echo $indx; ?>');return false;">[Show Settings]</a>&nbsp;&nbsp;
        <a href="#" onclick="doDelAcct('tw','<?php echo $indx; ?>', '<?php echo $two['twURL']; ?>');return false;">[Remove Account]</a>
      </p><?php $this->showNTSettings($indx, $two);             
    } //## END TW Settings 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($mtwo){ $two = array('twURL'=>'', 'twConsKey'=>'', 'twConsSec'=>'', 'twAccToken'=>'', 'twAccTokenSec'=>'', 'twAttch'=>'', 'twAccTokenSec'=>''); $this->showNTSettings($mtwo, $two, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $two, $isNew=false){  ?>
    <div id="doTW<?php echo $ii; ?>Div" <?php if ($isNew){ ?>class="clNewNTSets"<?php } ?> style="margin-left: 10px; <?php if ((isset($two['twOK']) && $two['twOK']=='1')||$isNew) { ?>display:none;<?php } ?>">   <input type="hidden" name="apDoSTW<?php echo $ii; ?>" value="0" id="apDoSTW<?php echo $ii; ?>" />      
    <br/>      
    <div style="width:100%;"><strong>Your Twitter URL:</strong> </div><input name="tw[<?php echo $ii; ?>][apTWURL]" id="apTWURL" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$two['twURL']), 'NS_SNAutoPoster') ?>" />
    <div style="width:100%;"><strong>Your Twitter Consumer Key:</strong> </div><input name="tw[<?php echo $ii; ?>][apTWConsKey]" id="apTWConsKey" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$two['twConsKey']), 'NS_SNAutoPoster') ?>" />  
    <div style="width:100%;"><strong>Your Twitter Consumer Secret:</strong> </div><input name="tw[<?php echo $ii; ?>][apTWConsSec]" id="apTWConsSec" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$two['twConsSec']), 'NS_SNAutoPoster') ?>" />
    <div style="width:100%;"><strong>Your Access Token:</strong> </div><input name="tw[<?php echo $ii; ?>][apTWAccToken]" id="apTWAccToken" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$two['twAccToken']), 'NS_SNAutoPoster') ?>" />
    <div style="width:100%;"><strong>Your Access Token Secret:</strong> </div><input name="tw[<?php echo $ii; ?>][apTWAccTokenSec]" id="apTWAccTokenSec" style="width: 30%;" value="<?php  _e(apply_filters('format_to_edit',$two['twAccTokenSec']), 'NS_SNAutoPoster') ?>" />
    <?php if ($isNew) { ?> <input type="hidden" name="tw[<?php echo $ii; ?>][apDoTW]" value="1" id="apDoNewTW<?php echo $ii; ?>" /> <?php } ?>
    <br/><br/>
    <strong id="altFormatText">Message Text Format:</strong>
    <div style="width:100%;">
      <p style="font-size: 11px; margin: 0px;">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. &nbsp; %URL% - Inserts the URL of your post. &nbsp; %SURL% - Inserts the <b>Shortened URL</b> of your post. &nbsp;  %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name. <i>Twitter takes only 140 characters.</i></p>
    </div>
    <input name="tw[<?php echo $ii; ?>][apTWMsgFrmt]" id="apTWMsgFrmt" style="width: 50%;" value="<?php if (!$isNew) _e(apply_filters('format_to_edit',$two['twMsgFormat']), 'NS_SNAutoPoster'); else echo "%TITLE% - %URL%"; ?>" />
               
    <?php if($two['twAccTokenSec']!='') { ?> <?php wp_nonce_field( 'rePostToTW', 'rePostToTW_wpnonce' ); ?>
      <br/><b>Test your settings:</b>&nbsp;&nbsp;&nbsp; <?php if (!isset($two['twOK']) || $two['twOK']!='1') { ?> <div class="blnkg">=== Submit Test Post to Complete ===&gt;</div> <?php } ?> <a href="#" class="NXSButton" onclick="testPost('TW', '<?php echo $ii; ?>'); return false;">Submit Test Post to Twitter</a> <br/><br/>
      <?php }?>
      <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div>
    </div>
    <?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'TW'; $lcode = 'tw'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apTWURL']) && $pval['apTWURL']!='') { if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apDoTW']))         $options[$ii]['doTW'] = $pval['apDoTW']; else $options[$ii]['doTW'] = 0;
        if (isset($pval['apTWURL']))        $options[$ii]['twURL'] = $pval['apTWURL'];
        if (isset($pval['apTWConsKey']))    $options[$ii]['twConsKey'] = $pval['apTWConsKey'];
        if (isset($pval['apTWConsSec']))    $options[$ii]['twConsSec'] = $pval['apTWConsSec'];                                
        if (isset($pval['apTWAccToken']))   $options[$ii]['twAccToken'] = $pval['apTWAccToken'];                
        if (isset($pval['apTWAccTokenSec']))$options[$ii]['twAccTokenSec'] = $pval['apTWAccTokenSec'];                                
        if (isset($pval['apTWMsgFrmt']))    $options[$ii]['twMsgFormat'] = $pval['apTWMsgFrmt'];
      }
    } return $options;
  }    
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ $post_id = $post->ID;
    foreach($ntOpts as $ii=>$ntOpt)  {  $doTW = $ntOpt['doTW'];  $isAvailTW =  $ntOpt['twURL']!='' && $ntOpt['twConsKey']!='' && $ntOpt['twConsSec']!='' && $ntOpt['twAccToken']!='';       
         $t = get_post_meta($post_id, 'SNAP_FormatTW', true);  $twMsgFormat = $t!=''?$t:$ntOpt['twMsgFormat'];      
      ?>  
      <tr><th style="text-align:left;" colspan="2">Twitter AutoPoster (<i style="color: #005800;"><?php echo  str_ireplace('https://','', str_ireplace('http://','', $ntOpt['twURL'])); ?></i>)</th><td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailTW) { ?><input alt="<?php echo $ii; ?>" style="float: right;" type="button" class="button" name="rePostToTW_repostButton" id="rePostToTW_button" value="<?php _e('Repost to Twitter', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToTW', 'rePostToTW_wpnonce' ); } ?>
                </td></tr>
                <?php if (!$isAvailTW) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Twitter Account to AutoPost to Twitter</b>
                <?php }elseif ($post->post_status != "publish") { ?> 
                
                <tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"><input class="nxsGrpDoChb" value="1" type="checkbox" name="tw[<?php echo $ii; ?>][SNAPincludeTW]" <?php if ((int)$doTW == 1) echo "checked"; ?> /></th>
                <td><b><?php _e('Publish this Post to Twitter', 'NS_SPAP'); ?></b></td>
                </tr>                
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:80px; padding-right:10px;"><?php _e('Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $twMsgFormat ?>" type="text" name="tw[<?php echo $ii; ?>][SNAPformat]" size="60px"/></td></tr>
                
                <tr id="altFormat2" style=""><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">Format Options:</th>
                <td style="vertical-align:top; font-size: 9px;" colspan="2">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. <br/> %URL% - Inserts the URL of your post. &nbsp; %SURL% - Inserts the <b>Shortened URL</b> of your post. &nbsp; %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name.</td></tr>
                <?php } 
    }
                
      
  }
  //#### 
  function adjMetaOpt($optMt, $pMeta){
     $optMt['twMsgFormat'] = $pMeta['SNAPformat']; $optMt['doTW'] = $pMeta['SNAPincludeTW'] == 1?1:0; return $optMt;
  }
}}

if (!function_exists("nxs_rePostToTW_ajax")) {
  function nxs_rePostToTW_ajax() { check_ajax_referer('rePostToTW');  $postID = $_POST['id']; $options = get_option('NS_SNAutoPoster');  
    foreach ($options['tw'] as $ii=>$two) if ($ii==$_POST['nid']) {  
      $twpo =  get_post_meta($postID, 'snapTW', true); $twpo =  maybe_unserialize($twpo);
      if (is_array($twpo)) $two['fbMsgFormat'] = $twpo[$ii]['SNAPformat']; 
      $result = nxs_doPublishToTW($postID, $two); if ($result == 201) {$options['tw'][$ii]['twOK']=1;  update_option('NS_SNAutoPoster', $options); } if ($result == 200) die("Successfully sent your post to Twitter."); else die($result);
    }
  }
} 

if (!function_exists("nxs_doPublishToTW")) { //## Second Function to Post to TW 
  function nxs_doPublishToTW($postID, $options){  $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url();  $isPost = isset($_POST["SNAPEdit"]);
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $msg = 'Test Post from '.$blogTitle." - ".rand(1, 155); $uln = strlen($msg);}  
    else{ $post = get_post($postID); //prr($post); die();
      if ($isPost) $twMsgFormat = $_POST['SNAP_FormatTW']; else { $t = get_post_meta($postID, 'SNAP_FormatTW', true); $twMsgFormat = $t!=''?$t:$options['twMsgFormat']; }            
      $twMsgFormat = str_ireplace("%TITLE%", "%STITLE%", $twMsgFormat); $msg = nsFormatMessage($twMsgFormat, $postID); 
      $twMsgFormat = str_ireplace("%URL%", "%URLXXURLXXURLXXURL%", $twMsgFormat); $msg2 = nsFormatMessage($twMsgFormat, $postID); $uln = strlen($msg)-strlen($msg2);         
    }
    require_once ('apis/tmhOAuth.php'); require_once ('apis/tmhUtilities.php'); if ($uln>0) $msg = nsTrnc($msg, 140+$uln); else $msg = nsTrnc($msg, 140); 
    $tmhOAuth = new NXS_tmhOAuth(array( 'consumer_key' => $options['twConsKey'], 'consumer_secret' => $options['twConsSec'], 'user_token' => $options['twAccToken'], 'user_secret' => $options['twAccTokenSec']));
    $code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array('status' =>$msg)); //prr($code); echo "YYY";
    if ($code == 200){if ($postID=='0'){echo 'OK - Message Posted, please see your Twitter Page'; /*NXS_tmhUtilities::pr(json_decode($tmhOAuth->response['response'])); */ return 201;}}else{ NXS_tmhUtilities::pr($tmhOAuth->response['response']);}
    return $code;
  }
}

?>