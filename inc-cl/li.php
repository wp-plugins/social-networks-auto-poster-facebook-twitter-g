<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'LI', 'lcode'=>'li', 'name'=>'LinkedIn');

if (!class_exists("nxs_snapClassLI")) { class nxs_snapClassLI {
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl, $nxs_plurl; $code = 'LI'; $lcode = 'li'; wp_nonce_field( 'ns'.$code, 'ns'.$code.'_wpnonce' ); 
    
    if ( isset($_GET['auth']) && $_GET['auth']=='li'){ require_once('apis/liOAuth.php'); $options = $ntOpts[$_GET['acc']];
    
              $api_key = $options['liAPIKey']; $api_secret = $options['liAPISec'];
              $callback_url = admin_url()."options-general.php?page=NextScripts_SNAP.php&auth=lia&acc=".$_GET['acc'];
              $li_oauth = new nsx_LinkedIn($api_key, $api_secret, $callback_url); 
              $request_token = $li_oauth->getRequestToken(); //echo "####"; prr($request_token); die();
              $options['liOAuthToken'] = $request_token->key;
              $options['liOAuthTokenSecret'] = $request_token->secret; prr($li_oauth);
              switch ($li_oauth->http_code) { case 200: $url = $li_oauth->generateAuthorizeUrl();   $optionsG = get_option('NS_SNAutoPoster'); $optionsG['li'][$_GET['acc']] = $options;  update_option('NS_SNAutoPoster', $optionsG);
                echo '<script type="text/javascript">window.location = "'.$url.'"</script>'; break; 
                default: echo '<br/><b style="color:red">Could not connect to LinkedIn. Refresh the page or try again later.</b>'; die();
              }die();
            }
    if ( isset($_GET['auth']) && $_GET['auth']=='lia'){ require_once('apis/liOAuth.php');  $options = $ntOpts[$_GET['acc']]; $api_key = $options['liAPIKey']; $api_secret = $options['liAPISec'];
              $li_oauth = new nsx_LinkedIn($api_key, $api_secret); $li_oauth->request_token = new nsx_trOAuthConsumer($options['liOAuthToken'], $options['liOAuthTokenSecret'], 1);              
              $li_oauth->oauth_verifier = $_REQUEST['oauth_verifier'];  $li_oauth->getAccessToken($_REQUEST['oauth_verifier']); $options['liOAuthVerifier'] = $_REQUEST['oauth_verifier'];
              $options['liAccessToken'] = $li_oauth->access_token->key; $options['liAccessTokenSecret'] = $li_oauth->access_token->secret;                            
              try{$xml_response = $li_oauth->getProfile("~:(id,first-name,last-name)");} catch (Exception $o){prr($o); die("<span style='color:red;'>ERROR: Authorization Error</span>");}
              if (stripos($xml_response,'<first-name>')!==false) $userinfo =  CutFromTo($xml_response, '<id>','</id>')." - ".CutFromTo($xml_response, '<first-name>','</first-name>')." ".CutFromTo($xml_response, '<last-name>','</last-name>'); else $userinfo='';              
              if ($userinfo!='') {  $options['liUserInfo'] = $userinfo; $optionsG = get_option('NS_SNAutoPoster'); $optionsG['li'][$_GET['acc']] = $options;  update_option('NS_SNAutoPoster', $optionsG); 
                  echo '<script type="text/javascript">window.location = "'.admin_url().'options-general.php?page=NextScripts_SNAP.php"</script>'; die();
              } prr($xml_response); die("<span style='color:red;'>ERROR: Something is Wrong with your LinkedIn account</span>");
            } 
    
    
    ?>    
    <hr/><div class="nsx_iconedTitle" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $lcode; ?>16.png);">LinkedIn Settings:   <?php $cfbo = count($ntOpts); $mfbo =  1+max(array_keys($ntOpts)); ?> <?php wp_nonce_field( 'nsFB', 'nsFB_wpnonce' ); ?>
    <div class="nsBigText">You have <?php echo $cfbo=='0'?'No':$cfbo; ?> LinkedIn account<?php if ($cfbo!=1){ ?>s<?php } ?> <!-- - <a href="#" class="NXSButton" onclick="doShowHideBlocks2('FB<?php echo $mfbo; ?>');return false;">Add new Facebook Account</a> --> </div></div>    
    <?php // if (function_exists('nxs_doSMAS1')) nxs_doSMAS1($this, $mfbo); else nxs_doSMAS('LinkedIn', 'LI'.$mfbo); ?>
    <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) { $pbo['nName'] = $pbo['liUserInfo'];  if($pbo['liPage']!='') $pbo['nName'] .= "Page: ".$pbo['liPage']; else $pbo['nName'] .= " Profile"; } ?>
      <p style="margin: 0px;margin-left: 5px;"><input value="1" id="apDoLI" name="li[<?php echo $indx; ?>][apDoLI]" type="checkbox" <?php if ((int)$pbo['doLI'] == 1) echo "checked"; ?> /> 
      <strong>Auto-publish your Posts to your LinkedIn <i style="color: #005800;"><?php if($pbo['nName']!='') echo "(".$pbo['nName'].")"; ?></i> </strong>
      &nbsp;&nbsp;<a id="doLI<?php echo $indx; ?>A" href="#" onclick="doShowHideBlocks2('LI<?php echo $indx; ?>');return false;">[Show Settings]</a>&nbsp;&nbsp;
      <a href="#" onclick="doDelAcct('li', '<?php echo $indx; ?>', '<?php if (isset($pbo['liUserInfo'])) echo $pbo['liUserInfo']; ?>');return false;">[Remove Account]</a>
      </p><?php $this->showNTSettings($indx, $pbo);             
    } //## END LI Settings 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($bo){ $po = array('nName'=>'', 'doLI'=>'1', 'liAPIKey'=>'', 'liAPISec'=>'', 'liUserInfo'=>'', 'liAttch'=>'1', 'liOAuthToken'=>'', 'liMsgFormat'=>'New post has been published on %SITENAME%' ); $this->showNTSettings($bo, $po, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; ?>
    <div id="doLI<?php echo $ii; ?>Div" <?php if ($isNew){ ?>class="clNewNTSets"<?php } ?> style="background-color: #EBF4FB; background-image: url(<?php echo $nxs_plurl; ?>img/li-bg.png);  background-position:90% 10%; background-repeat: no-repeat; margin: 10px; border: 1px solid #808080; padding: 10px; <?php if ((isset($options['liAccessToken']) && $options['liAccessTokenSecret']!='')||$isNew) { ?>display:none;<?php } ?>">   <input type="hidden" name="apDoSLI<?php echo $ii; ?>" value="0" id="apDoSLI<?php echo $ii; ?>" />                                     
    <?php if ($isNew) { ?> <input type="hidden" name="li[<?php echo $ii; ?>][apDoLI]" value="1" id="apDoNewLI<?php echo $ii; ?>" /> <?php } ?>
    
            
            <div id="doLI<?php echo $ii; ?>Div" style="margin-left: 10px;"> <br/>
            
            <div style="width:100%;"><strong>Account Nickname:</strong> <i>Just so you can easely identify it</i> </div><input name="li[<?php echo $ii; ?>][nName]" id="linName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit',$options['nName']), 'NS_SNAutoPoster') ?>" /><br/><br/>
            
            <div style="width:100%;"><strong>Your LinkedIn API Key:</strong> </div><input name="li[<?php echo $ii; ?>][apLIAPIKey]" id="apLIAPIKey" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['liAPIKey']), 'NS_SNAutoPoster') ?>" />  
            <div style="width:100%;"><strong>Your LinkedIn API Secret:</strong> </div><input name="li[<?php echo $ii; ?>][apLIAPISec]" id="apLIAPISec" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit',$options['liAPISec']), 'NS_SNAutoPoster') ?>" />
            
             <br/><br/>
            <p style="margin: 0px;"><input value="1"  id="apLIAttch" onchange="doShowHideAltFormat();" type="checkbox" name="li[<?php echo $ii; ?>][apLIAttch]"  <?php if ((int)$options['liAttch'] == 1) echo "checked"; ?> /> 
              <strong>Publish Posts to LinkedIn as an Attachement</strong>                                 
            </p>
            
            <div id="altFormat" style="<?php if ((int)$options['liAttch'] == 1) echo "margin-left: 10px;"; ?> ">
              <div style="width:100%;"><strong id="altFormatText">Message Text Format:</strong> 
              <p style="font-size: 11px; margin: 0px;">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. &nbsp; %URL% - Inserts the URL of your post. &nbsp;  %IMG% - Inserts the featured image. &nbsp;  %IMG% - Inserts the featured image. &nbsp;  %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name.</p>
              </div><input name="li[<?php echo $ii; ?>][apLIMsgFrmt]" id="apLIMsgFrmt" style="width: 50%;" value="<?php _e(apply_filters('format_to_edit',$options['liMsgFormat']), 'NS_SNAutoPoster') ?>" />
            </div><br/>
            
            <br/>
             <?php 
            if($options['liAPIKey']=='') { ?>
            <b>Authorize Your LinkedIn Account</b>. Please save your settings and come back here to Authorize your account.
            <?php } else { if(isset($options['liAccessToken']) && isset($options['liAccessTokenSecret']) && $options['liAccessTokenSecret']!=='') { ?>
            Your LinkedIn Account has been authorized. User ID: <?php _e(apply_filters('format_to_edit',$options['liUserInfo']), 'NS_SNAutoPoster') ?>. 
            You can Re- <?php } ?>            
            <a  href="<?php echo admin_url();?>options-general.php?page=NextScripts_SNAP.php&auth=li&acc=<?php echo $ii; ?>">Authorize Your LinkedIn Account</a>  
            
            <?php if (!isset($options['liAccessTokenSecret']) || $options['liAccessTokenSecret']=='') { ?> <div class="blnkg">&lt;=== Authorize your account ===</div> <?php } ?>
            
            <?php }   ?>            
            
            <?php if($options['liAPIKey']!='') { ?>
            <?php wp_nonce_field( 'rePostToLI', 'rePostToLI_wpnonce' ); ?>
            <br/><br/><b>Test your settings:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('LI', '<?php echo $ii; ?>'); return false;">Submit Test Post to LinkedIn</a>  <br/><br/>
            <?php }?>
            <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div>  
            </div>
        </div>
        <?php
      
      
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; 
    foreach ($post as $ii => $pval){ // prr($pval);
      if (isset($pval['apLIAPIKey']) && $pval['apLIAPISec']!='') { if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apDoLI']))         $options[$ii]['doLI'] = $pval['apDoLI']; else $options[$ii]['doLI'] = 0;
        if (isset($pval['nName']))          $options[$ii]['nName'] = $pval['nName'];
        if (isset($pval['apLIAPIKey']))      $options[$ii]['liAPIKey'] = $pval['apLIAPIKey'];                                
        if (isset($pval['apLIAPISec']))     $options[$ii]['liAPISec'] = $pval['apLIAPISec'];        
        if (isset($pval['apLIAttch']))      $options[$ii]['liAttch'] = $pval['apLIAttch']; else $options[$ii]['liAttch'] = 0;
        
        if (isset($pval['apLIMsgFrmt']))    $options[$ii]['liMsgFormat'] = $pval['apLIMsgFrmt']; 
      } //prr($options);
    } return $options;
  } 
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; 
    foreach($ntOpts as $ii=>$options)  {$doLI = $options['doLI']; 
        $isAvailLI =  $options['liOAuthVerifier']!='' && $options['liAccessTokenSecret']!='' && $options['liAccessToken']!='' && $options['liAPIKey']!='';                        
        $t = get_post_meta($post_id, 'SNAP_AttachLI', true);  $isAttachLI = $t!=''?$t:$options['liAttch'];            
        $t = get_post_meta($post_id, 'SNAP_FormatLI', true);  $liMsgFormat = $t!=''?$t:$options['liMsgFormat'];
      ?>  
      
      <tr><th style="text-align:left;" colspan="2"><div class="nsx_iconedTitle" style="font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/li16.png);">LinkedIn AutoPoster Options (<i style="color: #005800;"><?php echo $options['nName']; ?></i>)</div></th><td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailLI) { ?><input style="float: right;" type="button" class="button" name="rePostToLI_repostButton" id="rePostToLI_button" value="<?php _e('Repost to LinkedIn', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToLI', 'rePostToLI_wpnonce' ); } ?>
                </td></tr>
                <?php if (!$isAvailLI) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your LinkedIn Account to AutoPost to LinkedIn</b>
                <?php }elseif ($post->post_status != "publish") { ?> 
                
                <tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"><input class="nxsGrpDoChb" value="1" type="checkbox" name="li[<?php echo $ii; ?>][SNAPincludeLI]" <?php if ((int)$doLI == 1) echo "checked"; ?> /></th>
                <td><b><?php _e('Publish this Post to LinkedIn', 'NS_SPAP'); ?></b></td>
                </tr> 
                
                <tr><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">
                <input value="1"  id="SNAP_AttachLI" onchange="doShowHideAltFormatX();" type="checkbox" name="li[<?php echo $ii; ?>][AttachPost]"  <?php if ((int)$isAttachLI == 1) echo "checked"; ?> /> </th><td><strong>Publish Post to LinkedIn as Attachement</strong></td>                </tr>               
                
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:80px; padding-right:10px;"><?php _e('Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $liMsgFormat ?>" type="text" name="li[<?php echo $ii; ?>][SNAPformat]" size="60px"/></td></tr>
                
                <tr id="altFormat2" style=""><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">Format Options:</th>
                <td style="vertical-align:top; font-size: 9px;" colspan="2">%SITENAME% - Inserts the Your Blog/Site Name. &nbsp; %TITLE% - Inserts the Title of your post. <br/> %URL% - Inserts the URL of your post. &nbsp; %SURL% - Inserts the <b>Shortened URL</b> of your post. &nbsp; %TEXT% - Inserts the excerpt of your post. &nbsp;  %FULLTEXT% - Inserts the body(text) of your post, %AUTHORNAME% - Inserts the author's name.</td></tr>
                <?php } 
    }
      
  }
  
  function adjMetaOpt($optMt, $pMeta){
     $optMt['liMsgFormat'] = $pMeta['SNAPformat']; $optMt['liAttch'] = $pMeta['AttachPost'] == 1?1:0; $optMt['doLI'] = $pMeta['SNAPincludeLI'] == 1?1:0; return $optMt;
  }
}}

if (!function_exists("nxs_rePostToLI_ajax")) { function nxs_rePostToLI_ajax() {  check_ajax_referer('rePostToLI');  $postID = $_POST['id']; // $result = nsPublishTo($id, 'FB', true);   
    $options = get_option('NS_SNAutoPoster');  foreach ($options['li'] as $ii=>$po) if ($ii==$_POST['nid']) {  
      $mpo =  get_post_meta($postID, 'snapLI', true); $mpo =  maybe_unserialize($mpo); 
      if (is_array($mpo) && isset($mpo[$ii]) && is_array($mpo[$ii]) ){ $po['liMsgFormat'] = $mpo[$ii]['SNAPformat']; $po['liAttch'] = $mpo[$ii]['AttachPost'] == 1?1:0; } 
      $result = nxs_doPublishToLI($postID, $po); if ($result == 200) die("Successfully sent your post to LinkedIn."); else die($result);
    }    
  }
}

if (!function_exists("nxs_doPublishToLI")) { //## Second Function to Post to LI
  function nxs_doPublishToLI($postID, $options){ $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); 
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $msgT = 'Test Post from '.$blogTitle;  $link = home_url(); $msg = 'Test Post from '.$blogTitle. " ".$link; $isAttachLI = ''; }
      else { $liMsgFormat = $options['liMsgFormat'];  $msg = nsFormatMessage($liMsgFormat, $postID); $link = get_permalink($postID); $isAttachLI = $options['liAttch']; }
    require_once ('apis/liOAuth.php'); $linkedin = new nsx_LinkedIn($options['liAPIKey'], $options['liAPISec']);  $linkedin->oauth_verifier = $options['liOAuthVerifier'];
    $linkedin->request_token = new nsx_trOAuthConsumer($options['liOAuthToken'], $options['liOAuthTokenSecret'], 1);     
    $linkedin->access_token = new nsx_trOAuthConsumer($options['liAccessToken'], $options['liAccessTokenSecret'], 1);  $msg = nsTrnc($msg, 700); 
    
    if ($isAttachLI=='1' && function_exists("get_post_thumbnail_id") ){ $src = wp_get_attachment_image_src(get_post_thumbnail_id($postID), 'medium'); $src = $src[0];}         
    if ($isAttachLI=='1') { $post = get_post($postID); $dsc = trim(apply_filters('the_content', $post->post_excerpt)); if ($dsc=='') $dsc = apply_filters('the_content', $post->post_content);  $dsc = nsTrnc($dsc, 250); }
    
    //echo "LI SET: ".$msg." | ".nsTrnc($post->post_title, 200)." | ". get_permalink($postID)." | ". $src." | ".$dsc;    
    try{ if($isAttachLI=='1') $ret = $linkedin->postShare($msg, nsTrnc($post->post_title, 200), get_permalink($postID), $src, $dsc); else $ret = $linkedin->postShare($msg); }
      catch (Exception $o){ echo "<br />Linkedin Status couldn't be updated!</br>"; prr($o); echo '<br />'; $ret="ERROR:"; }          
    if ($ret!='201') echo $ret; else if ($postID=='0') echo 'OK - Linkedin status updated successfully';
    if ($ret=='201') return true; else return 'Something Wrong';
  }
}

?>