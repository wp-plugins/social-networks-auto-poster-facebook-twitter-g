<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'TR', 'lcode'=>'tr', 'name'=>'Tumblr');

if (!class_exists("nxs_snapClassTR")) { class nxs_snapClassTR {
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl; $code = 'TR'; $lcode = 'tr'; wp_nonce_field( 'ns'.$code, 'ns'.$code.'_wpnonce' ); 
    
   if ( isset($_GET['auth']) && $_GET['auth']=='tr'){ require_once('apis/trOAuth.php'); $options = $ntOpts[$_GET['acc']];
     $consumer_key = $options['trConsKey']; $consumer_secret = $options['trConsSec'];
              $callback_url = admin_url()."options-general.php?page=NextScripts_SNAP.php&auth=tra&acc=".$_GET['acc'];
              $tum_oauth = new TumblrOAuth($consumer_key, $consumer_secret);prr($tum_oauth );
              $request_token = $tum_oauth->getRequestToken($callback_url); echo "####"; prr($request_token);
              $options['trOAuthToken'] = $request_token['oauth_token'];
              $options['trOAuthTokenSecret'] = $request_token['oauth_token_secret'];// prr($tum_oauth ); die();
              switch ($tum_oauth->http_code) { case 200: $url = $tum_oauth->getAuthorizeURL($options['trOAuthToken']); $optionsG = get_option('NS_SNAutoPoster'); $optionsG['tr'][$_GET['acc']] = $options;  update_option('NS_SNAutoPoster', $optionsG);
                echo '<script type="text/javascript">window.location = "'.$url.'"</script>'; break; 
                default: echo '<br/><b style="color:red">Could not connect to Tumblr. Refresh the page or try again later.</b>'; die();
              }
              die();
            }
            if ( isset($_GET['auth']) && $_GET['auth']=='tra'){ require_once('apis/trOAuth.php'); $options = $ntOpts[$_GET['acc']];
              $consumer_key = $options['trConsKey']; $consumer_secret = $options['trConsSec'];  
              $tum_oauth = new TumblrOAuth($consumer_key, $consumer_secret, $options['trOAuthToken'], $options['trOAuthTokenSecret']);
              $options['trAccessTocken'] = $tum_oauth->getAccessToken($_REQUEST['oauth_verifier']); // prr($_GET);  prr($_REQUEST);   prr($options['trAccessTocken']);         
              $tum_oauth = new TumblrOAuth($consumer_key, $consumer_secret, $options['trAccessTocken']['oauth_token'], $options['trAccessTocken']['oauth_token_secret']); 
              $optionsG = get_option('NS_SNAutoPoster'); $optionsG['tr'][$_GET['acc']] = $options;  update_option('NS_SNAutoPoster', $optionsG);
              $userinfo = $tum_oauth->get('http://api.tumblr.com/v2/user/info'); prr($userinfo); prr($tum_oauth);// prr($url); die();
              if (is_array($userinfo->response->user->blogs)) {
                foreach ($userinfo->response->user->blogs as $blog){
                  if (stripos($blog->url, $options['trPgID'])!==false) {  echo '<script type="text/javascript">window.location = "'.admin_url().'options-general.php?page=NextScripts_SNAP.php"</script>'; break;  die();}
                } prr($userinfo);
                die("<span style='color:red;'>ERROR: Authorized USER don't have access to the specified blog: <span style='color:darkred; font-weight: bold;'>".$options['trPgID']."</span></span>");
              }
            }
    
    
    ?>    
    <hr/><div style="font-size: 17px;font-weight: bold; margin-bottom: 15px;">Tumblr Settings:   <?php $cfbo = count($ntOpts); $mfbo =  1+max(array_keys($ntOpts)); ?> <?php wp_nonce_field( 'nsFB', 'nsFB_wpnonce' ); ?>
    <div class="nsBigText">You have <?php echo $cfbo=='0'?'No':$cfbo; ?> Tumblr account<?php if ($cfbo!=1){ ?>s<?php } ?> <!-- - <a href="#" class="NXSButton" onclick="doShowHideBlocks2('FB<?php echo $mfbo; ?>');return false;">Add new Facebook Account</a> --> </div></div>
    
    <?php // if (function_exists('nxs_doSMAS1')) nxs_doSMAS1($this, $mfbo); else nxs_doSMAS('Tumblr', 'TR'.$mfbo); ?>
    <?php foreach ($ntOpts as $indx=>$pbo){ ?>
      <p style="margin: 0px;margin-left: 5px;"><input value="1" id="apDoTR" name="tr[<?php echo $indx; ?>][apDoTR]" type="checkbox" <?php if ((int)$pbo['doTR'] == 1) echo "checked"; ?> /> 
      <strong>Auto-publish your Posts to your <?php if(isset($pbo['trURL']) && $pbo['trURL']!='') echo "(".str_ireplace('https://','', str_ireplace('http://','', $pbo['trURL'])).")"; ?> Tumblr Profile</strong>
      <a id="doTR<?php echo $indx; ?>A" href="#" onclick="doShowHideBlocks2('TR<?php echo $indx; ?>');return false;">[Show Settings]</a>&nbsp;&nbsp;
      <a href="#" onclick="doDelAcct('tr', '<?php echo $indx; ?>', '<?php if (isset($pbo['trURL'])) echo $pbo['trURL']; ?>');return false;">[Remove Account]</a>
      </p><?php $this->showNTSettings($indx, $pbo);             
    } //## END TR Settings 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($bo){ $po = array('doTR'=>'1', 'trURL'=>'', 'trPgID'=>'', 'trConsKey'=>'', 'trInclTags'=>'1', 'trConsSec'=>'',  'trOAuthTokenSecret'=>'', 'trAccessTocken'=>'', 'trMsgFormat'=>'<p>New Post has been published on %URL%</p><blockquote><p><strong>%TITLE%</strong></p><p><img src=\'%IMG%\'/></p><p>%FULLTEXT%</p></blockquote>', 'trMsgTFormat'=>'New Post has been published on %SITENAME%' ); $this->showNTSettings($bo, $po, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  ?>
    <div id="doTR<?php echo $ii; ?>Div"<?php if ($isNew){ ?>class="clNewNTSets"<?php } ?> style="margin-left: 10px; <?php if ((isset($options['trOAuthTokenSecret']) && $options['trOAuthTokenSecret']!='')||$isNew) { ?>display:none;<?php } ?>">   <input type="hidden" name="apDoSTR<?php echo $ii; ?>" value="0" id="apDoSTR<?php echo $ii; ?>" />                                     
    <?php if ($isNew) { ?> <input type="hidden" name="tr[<?php echo $ii; ?>][apDoTR]" value="1" id="apDoNewTR<?php echo $ii; ?>" /> <?php } ?>
    
            <br/>
            <div style="width:100%;"><strong>Your Tumblr URL:</strong> </div><input name="tr[<?php echo $ii; ?>][apTRURL]" id="apTRURL" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['trURL']), 'NS_SNAutoPoster') ?>" />                <div style="width:100%;"><strong>Your Tumblr OAuth Consumer Key:</strong> </div><input name="tr[<?php echo $ii; ?>][apTRConsKey]" id="apTRConsKey" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['trConsKey']), 'NS_SNAutoPoster') ?>" />             <div style="width:100%;"><strong>Your Tumblr Secret Key:</strong> </div><input name="tr[<?php echo $ii; ?>][apTRConsSec]" id="apTRConsSec" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['trConsSec']), 'NS_SNAutoPoster') ?>" />
            <br/><br/>
            <strong id="altFormatText">Post Title and Post Text Formats</strong>
            <p style="font-size: 11px; margin: 0px;">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. &nbsp; %URL% - Inserts the URL of your post. &nbsp; %SURL% - Inserts the <b>Shortened URL</b> of your post. &nbsp;  %IMG% - Inserts the featured image. &nbsp;  %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name. HTML is allowed.</p>
            <div style="width:100%;"><strong id="altFormatText">Post Title Format:</strong></div>
              
              <input name="tr[<?php echo $ii; ?>][apTRMsgTFrmt]" id="apTRMsgTFrmt" style="width: 50%;" value="<?php if ($options['trMsgTFormat']!='') _e(apply_filters('format_to_edit', stripcslashes(str_replace('"',"'",$options['trMsgTFormat']))), 'NS_SNAutoPoster'); else echo "New Post has been published on %SITENAME%"; ?>" /><br/>
            
            <div style="width:100%;"><strong id="altFormatText">Post Text Format:</strong> </div>
              
              <input name="tr[<?php echo $ii; ?>][apTRMsgFrmt]" id="apTRMsgFrmt" style="width: 50%;" value="<?php if ($options['trMsgFormat']!='') _e(apply_filters('format_to_edit', stripcslashes(str_replace('"',"'",$options['trMsgFormat']))), 'NS_SNAutoPoster'); else echo "<p>New Post has been published on %URL%</p><blockquote><p><strong>%TITLE%</strong></p><p><img src='%IMG%'/></p><p>%FULLTEXT%</p></blockquote>"; ?>" /><br/>
              
              <p style="margin-bottom: 20px;margin-top: 5px;"><input value="1"  id="trInclTags" type="checkbox" name="tr[<?php echo $ii; ?>][trInclTags]"  <?php if ((int)$options['trInclTags'] == 1) echo "checked"; ?> /> 
              <strong>Post with tags</strong> Tags from the blogpost will be auto posted to Tumblr                                
            </p>
              
              <?php 
            if($options['trConsSec']=='') { ?>
            <b>Authorize Your Tumblr Account</b>. Please save your settings and come back here to Authorize your account.
            <?php } else { if(isset($options['trAccessTocken']) && isset($options['trAccessTocken']['oauth_token_secret']) && $options['trAccessTocken']['oauth_token_secret']!=='') { ?>
            Your Tumblr Account has been authorized. Blog ID: <?php _e(apply_filters('format_to_edit',$options['trPgID']), 'NS_SNAutoPoster') ?>. 
            You can Re- <?php } ?>            
            <a href="<?php echo admin_url();?>options-general.php?page=NextScripts_SNAP.php&auth=tr&acc=<?php echo $ii; ?>">Authorize Your Tumblr Account</a> 
              <?php if (!isset($options['trOAuthTokenSecret']) || $options['trOAuthTokenSecret']=='') { ?> <div class="blnkg">&lt;=== Authorize your account ===</div> <?php } ?>            
            <?php }  ?>            
            
            
            <?php if( isset($options['trOAuthTokenSecret']) && $options['trOAuthTokenSecret']!='') { ?>
            <?php wp_nonce_field( 'rePostToTR', 'rePostToTR_wpnonce' ); ?>
            <br/><br/><b>Test your settings:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('TR', '<?php echo $ii; ?>'); return false;">Submit Test Post to Tumblr</a>  <br/><br/>
            <?php }?>
            <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div>  
            
        </div>
        <?php
      
      
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; //prr($post); die();
    foreach ($post as $ii => $pval){ // prr($pval);
      if (isset($pval['apTRConsKey']) && $pval['apTRConsSec']!='') { if (!isset($options[$ii])) $options[$ii] = array();
        
                if (isset($pval['apTRURL']))  {   $options[$ii]['trURL'] = $pval['apTRURL'];
                  $trPgID = $options[$ii]['trURL']; if (substr($trPgID, -1)=='/') $trPgID = substr($trPgID, 0, -1);  $trPgID = substr(strrchr($trPgID, "/"), 1);
                  $options[$ii]['trPgID'] = $trPgID; //echo $fbPgID;
                }
                if (isset($pval['apDoTR']))         $options[$ii]['doTR'] = $pval['apDoTR']; else $options[$ii]['doTR'] = 0;
                if (isset($pval['apTRConsKey']))    $options[$ii]['trConsKey'] = $pval['apTRConsKey'];
                if (isset($pval['apTRConsSec']))    $options[$ii]['trConsSec'] = $pval['apTRConsSec'];                                
                if (isset($pval['apTRMsgFrmt']))    $options[$ii]['trMsgFormat'] = $pval['apTRMsgFrmt'];                                
                if (isset($pval['apTRMsgTFrmt']))    $options[$ii]['trMsgTFormat'] = $pval['apTRMsgTFrmt'];   
                if (isset($pval['trInclTags']))    $options[$ii]['trInclTags'] = $pval['trInclTags']; else $options[$ii]['trInclTags'] = 0;
                
      } // prr($options);
    } return $options;
  } 
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ $post_id = $post->ID; 
    foreach($ntOpts as $ii=>$options)  {$doTR = $options['doTR']; 
        $isAvailTR =  isset($options['trAccessTocken']) && isset($options['trAccessTocken']['oauth_token_secret']) && $options['trAccessTocken']['oauth_token_secret']!=='';   
       
       $t = get_post_meta($post_id, 'SNAP_FormatTR', true);  $trMsgFormat = $t!=''?$t:$options['trMsgFormat']; $trMsgFormat = stripcslashes(str_replace('"',"'",$trMsgFormat));
       $t = get_post_meta($post_id, 'SNAP_FormatTTR', true); $trMsgTFormat = $t!=''?$t:$options['trMsgTFormat'];
      ?>  
      
      <tr><th style="text-align:left;" colspan="2">Tumblr AutoPoster Options (<i style="color: #005800;"><?php echo str_ireplace('https://','', str_ireplace('http://','', $options['trURL'])); ?></i>) </th><td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailTR) { ?><input style="float: right;" type="button" class="button" name="rePostToTR_repostButton" id="rePostToTR_button" value="<?php _e('Repost to Tumblr', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToTR', 'rePostToTR_wpnonce' ); } ?>
                </td></tr>
                <?php if (!$isAvailTR) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup and authorize your Tumblr Account to AutoPost to Tumblr</b>
                <?php }elseif ($post->post_status != "publish") { ?> 
                
                <tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"><input class="nxsGrpDoChb" value="1" type="checkbox" name="SNAPincludeTR" <?php if ((int)$doTR == 1) echo "checked"; ?> /></th>
                <td><b><?php _e('Publish this Post to Tumblr', 'NS_SPAP'); ?></b></td>
                </tr>       
                         
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:80px; padding-right:10px;"><?php _e('Title Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $trMsgTFormat ?>" type="text" name="SNAP_FormatTTR" size="60px"/></td></tr>
                
                <tr id="altFormat2" style=""><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">Title Format Options:</th>
                <td style="vertical-align:top; font-size: 9px;" colspan="2">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. <br/> %URL% - Inserts the URL of your post. &nbsp; %SURL% - Inserts the <b>Shortened URL</b> of your post. &nbsp; %IMG% - Inserts the featured image. &nbsp;  %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name.</td></tr>
                
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:80px; padding-right:10px;"><?php _e('Text Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $trMsgFormat ?>" type="text" name="SNAP_FormatTR" size="60px"/></td></tr>
                
                <tr id="altFormat2" style=""><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">Text Format Options:</th>
                <td style="vertical-align:top; font-size: 9px;" colspan="2">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. <br/> %URL% - Inserts the URL of your post. &nbsp; %SURL% - Inserts the <b>Shortened URL</b> of your post. &nbsp; %IMG% - Inserts the featured image. &nbsp;  %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name.</td></tr>
                <?php } 
    }
      
  }
  
  function adjMetaOpt($optMt, $pMeta){
     $optMt['trMsgFormat'] = $pMeta['SNAPformat']; $optMt['trMsgTFormat'] = $pMeta['SNAPTformat']; $optMt['doTR'] = $pMeta['SNAPincludeTR'] == 1?1:0; return $optMt;
  }
}}

if (!function_exists("nxs_rePostToTR_ajax")) { function nxs_rePostToTR_ajax() {  check_ajax_referer('rePostToTR');  $postID = $_POST['id']; // $result = nsPublishTo($id, 'FB', true);   
    $options = get_option('NS_SNAutoPoster');  foreach ($options['tr'] as $ii=>$po) if ($ii==$_POST['nid']) {  
      $mpo =  get_post_meta($postID, 'snapTR', true); $mpo =  maybe_unserialize($mpo); 
      if (is_array($mpo) && isset($mpo[$ii]) && is_array($mpo[$ii]) ){ $po['trMsgFormat'] = $mpo[$ii]['SNAPformat']; $po['trMsgTFormat'] = $mpo[$ii]['SNAPTformat']; $po['trAttch'] = $mpo[$ii]['AttachPost'] == 1?1:0; } 
      $result = nxs_doPublishToTR($postID, $po); if ($result == 200) die("Successfully sent your post to Tumblr."); else die($result);
    }    
  }
}

if (!function_exists("nxs_doPublishToTR")) { //## Second Function to Post to TR
  function nxs_doPublishToTR($postID, $options){ $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); $isPost = isset($_POST["SNAPEdit"]); 
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $msg = 'Test Post from '.$blogTitle;  $msgT = 'Test Post from '.$blogTitle;}
    else{        
      if ($isPost) $trMsgFormat = $_POST['SNAP_FormatTR']; else { $t = get_post_meta($postID, 'SNAP_FormatTR', true); $trMsgFormat = $t!=''?$t:$options['trMsgFormat']; } 
      $msg = nsFormatMessage($trMsgFormat, $postID);        
      if ($isPost) $trMsgTFormat = $_POST['SNAP_FormatTTR']; else { $t = get_post_meta($postID, 'SNAP_FormatTTR', true); $trMsgTFormat = $t!=''?$t:$options['trMsgTFormat']; } 
      $msgT = nsFormatMessage($trMsgTFormat, $postID);  
    } 
    require_once('apis/trOAuth.php'); $consumer_key = $options['trConsKey']; $consumer_secret = $options['trConsSec'];
    $tum_oauth = new TumblrOAuth($consumer_key, $consumer_secret, $options['trAccessTocken']['oauth_token'], $options['trAccessTocken']['oauth_token_secret']); //prr($options);
    $trURL = trim(str_ireplace('http://', '', $options['trURL'])); if (substr($trURL,-1)=='/') $trURL = substr($trURL,0,-1); 
    if ($options['trInclTags']=='1'){$t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = implode(',',$tggs); }    
    $post = get_post($postID);  $postDate = $post->post_date_gmt." GMT"; //## Adds date to Tumblr post. Thanks to Kenneth Lecky
    $postinfo = $tum_oauth->post("http://api.tumblr.com/v2/blog/".$trURL."/post", array('type'=>'text', 'title'=>$msgT,  'body'=>$msg, 'tags'=>$tags, 'source'=>get_permalink($postID), 'date'=>$postDate));
    $code = $postinfo->meta->status; //prr($msg); prr($postinfo); echo $code."VVVV"; die("|====");
    if ($code == 201) { if ($postID=='0') { echo 'OK - Message Posted, please see your Tumblr  Page. <br/> Result:'; prr($postinfo->meta); } } else {  prr($postinfo);  }      
    return $code;
  }
}

?>