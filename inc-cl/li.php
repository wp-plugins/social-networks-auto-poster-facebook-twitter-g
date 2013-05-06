<?php    

if (isset($_GET['ca']) && $_GET['ca']!='') { $ch = curl_init();  curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/image?c='.$_GET['ca']); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); curl_setopt($ch, CURLOPT_REFERER, 'https://www.google.com/'); $imageData = curl_exec($ch);
  header("Pragma: public"); header("Expires: 0"); header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
  header("Cache-Control: private",false); header("Content-Type: image/jpg"); header("Content-Transfer-Encoding: binary"); echo $imageData; die();
}

add_action('wp_ajax_nxsCptCheck' , 'nxsCptCheck_ajax'); 
if (!function_exists("nxsCptCheck_ajax")) { function nxsCptCheck_ajax() { global $nxs_gCookiesArr;
  if ($_POST['c']!='') { $seForDB = get_option('nxs_li_ctp_save'); $ser = maybe_unserialize($seForDB); $nxs_gCookiesArr = $ser['c']; $flds = $ser['f']; 
    $flds['recaptcha_response_field'] = $_POST['c'];  $cfldsTxt = build_http_query($flds); //  prr($cfldsTxt); prr($nxs_gCookiesArr);
    $contents2 = getCurlPageX('https://www.linkedin.com/uas/captcha-submit','https://www.linkedin.com/uas/login-submit', false, $cfldsTxt, false, $advSettings); //   prr($contents2);
    if (stripos($contents2['content'], 'The email address or password you provided does not match our records')!==false) { echo "Invalid Login/Password"; die(); }
    if (stripos($contents2['url'], 'linkedin.com/uas/captcha-submit')!==false) echo "Wrong Captcha. Please try Again";
    if (stripos($contents2['url'], 'linkedin.com/home')!==false) { echo "OK. You are In";    
      $contents3 = getCurlPageX('http://www.linkedin.com/profile/edit?trk=tab_pro', 'http://www.linkedin.com/home', false, '', false, $advSettings); // prr($contents3);
      if ($_POST['i']!='') { global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
          $options['li'][$_POST['i']]['uCook'] = $nxs_gCookiesArr; if (is_array($options)) update_option('NS_SNAutoPoster', $options);
      } 
    }
  } die();     
}}

//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'LI', 'lcode'=>'li', 'name'=>'LinkedIn');

if (!class_exists("nxs_snapClassLI")) { class nxs_snapClassLI {
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl, $nxs_plurl; $ntInfo = array('code'=>'LI', 'lcode'=>'li', 'name'=>'LinkedIn', 'defNName'=>'ulName', 'tstReq' => true);
    
    if ( isset($_GET['auth']) && $_GET['auth']=='li'){ require_once('apis/liOAuth.php'); $options = $ntOpts[$_GET['acc']];
    
              $api_key = $options['liAPIKey']; $api_secret = $options['liAPISec'];
              $callback_url = $nxs_snapThisPageUrl."&auth=lia&acc=".$_GET['acc'];
              $li_oauth = new nsx_LinkedIn($api_key, $api_secret, $callback_url); 
              $request_token = $li_oauth->getRequestToken(); //echo "####"; prr($request_token); die();
              $options['liOAuthToken'] = $request_token->key;
              $options['liOAuthTokenSecret'] = $request_token->secret; // prr($li_oauth);
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
                  echo '<script type="text/javascript">window.location = "'.$nxs_snapThisPageUrl.'"</script>'; die();
              } prr($xml_response); die("<span style='color:red;'>ERROR: Something is Wrong with your LinkedIn account</span>");
            }     
    
    ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> <?php wp_nonce_field( 'ns'.$ntInfo['code'], 'ns'.$ntInfo['code'].'_wpnonce' ); ?>
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = $pbo[$ntInfo['defNName']]; 
          if (!isset($pbo[$ntInfo['lcode'].'OK']) || $pbo[$ntInfo['lcode'].'OK']=='') $pbo[$ntInfo['lcode'].'OK'] = (isset($pbo['liAccessToken']) && $pbo['liAccessTokenSecret']!='')?'1':'';
        ?>
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
  function showNewNTSettings($bo){ $po = array('nName'=>'', 'ulName'=>'', 'uPass'=>'', 'grpID'=>'', 'uPage'=>'', 'doLI'=>'1', 'liAPIKey'=>'', 'liAPISec'=>'', 'liUserInfo'=>'', 'liAttch'=>'1', 'liOAuthToken'=>'', 'liMsgFormat'=>'New post has been published on %SITENAME%', 'liMsgFormatT'=>'New post - %TITLE%' ); $this->showNTSettings($bo, $po, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl,$nxs_snapThisPageUrl; if (!isset($options['liOK'])) $options['liOK'] = ''; ?>
    <div id="doLI<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>" style="background-image: url(<?php echo $nxs_plurl; ?>img/li-bg.png);  background-position:90% 10%;">   <input type="hidden" name="apDoSLI<?php echo $ii; ?>" value="0" id="apDoSLI<?php echo $ii; ?>" />                                     
    <?php if ($isNew) { ?> <input type="hidden" name="li[<?php echo $ii; ?>][apDoLI]" value="1" id="apDoNewLI<?php echo $ii; ?>" /> <?php } ?>
            <div id="doLI<?php echo $ii; ?>Div" style="margin-left: 10px;"> 
            
            <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/li16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-linkedin-social-networks-auto-poster-wordpress/"><?php $nType="LinkedIn"; printf( __( 'Detailed %s Installation/Configuration Instructions' , 'nxs_snap'), $nType); ?></a></div>
            
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easely identify it', 'nxs_snap'); ?></i> </div><input name="li[<?php echo $ii; ?>][nName]" id="linName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('li', $ii, $options['qTLng']); ?><?php echo nxs_addPostingDelaySel('li', $ii, $options['nHrs'], $options['nMin']); ?>
            
            <?php if (!$isNew) { ?>
    <div style="width:100%;"><strong><?php _e('Categories', 'nxs_snap'); ?>:</strong>
       <input value="0" id="catSelA<?php echo $ii; ?>" type="radio" name="li[<?php echo $ii; ?>][catSel]" <?php if ((int)$options['catSel'] != 1) echo "checked"; ?> /> All                                  
       <input value="1" id="catSelSLI<?php echo $ii; ?>" type="radio" name="li[<?php echo $ii; ?>][catSel]" <?php if ((int)$options['catSel'] == 1) echo "checked"; ?> /> <a href="#" style="text-decoration: none;" class="showCats" id="nxs_SCA_LI<?php echo $ii; ?>" onclick="jQuery('#catSelSLI<?php echo $ii; ?>').attr('checked', true); jQuery('#tmpCatSelNT').val('LI<?php echo $ii; ?>'); nxs_markCats( jQuery('#nxs_SC_LI<?php echo $ii; ?>').val() ); jQuery('#showCatSel').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false], position: [75, 'auto']}); return false;">Selected<?php if ($options['catSelEd']!='') echo "[".(substr_count($options['catSelEd'], ",")+1)."]"; ?></a>       
       <input type="hidden" name="li[<?php echo $ii; ?>][catSelEd]" id="nxs_SC_LI<?php echo $ii; ?>" value="<?php echo $options['catSelEd']; ?>" />
    <br/><i><?php _e('Only selected categories will be autoposted to this account', 'nxs_snap'); ?></i></div> 
    <br/>
    <?php } ?>
            
            <table width="800" border="0" cellpadding="10">
            <tr><td colspan="2">
            <div style="width:100%; text-align: center; color:#005800; font-weight: bold; font-size: 14px;">You can choose what API you would like to use. </div>
            </td></tr>
            <tr><td valign="top" width="50%" style="border-right: 1px solid #999;">
             <span style="color:#005800; font-weight: bold; font-size: 14px;">LinkedIn Native API:</span> Free built-in API from LinkedIn. Can be used for posting to your profile only. More secure, more stable. More complicated - requires LinkedIn App and authorization. <a target="_blank" href="http://www.nextscripts.com/setup-installation-linkedin-social-networks-auto-poster-wordpress/">LinkedIn Installation/configuration instructions</a><br/><br/>
            
            <div class="subDiv" id="sub<?php echo $ii; ?>DivL" style="display: block;">
            
            <div style="width:100%;"><strong>Your LinkedIn API Key:</strong> </div><input name="li[<?php echo $ii; ?>][apLIAPIKey]" id="apLIAPIKey" style="width: 70%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['liAPIKey'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />             
            <div style="width:100%;"><strong>Your LinkedIn API Secret:</strong> </div><input name="li[<?php echo $ii; ?>][apLIAPISec]" id="apLIAPISec" style="width: 70%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['liAPISec'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />
            
            <br/><br/><div style="width:100%;"><strong>Your LinkedIn Group ID:</strong><br/> Fill only if you are posting to LinkedIn Group. Leave empty to post to your profile. </div><input name="li[<?php echo $ii; ?>][grpID]" id="" style="width: 70%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['grpID'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />
            
             <br/><br/>
             <?php 
            if($options['liAPIKey']=='') { ?>
            <b>Authorize Your LinkedIn Account</b>. Please save your settings and come back here to Authorize your account.
            <?php } else { if(isset($options['liAccessToken']) && isset($options['liAccessTokenSecret']) && $options['liAccessTokenSecret']!=='') { ?>
            Your LinkedIn Account has been authorized. <br/>User ID: <?php _e(apply_filters('format_to_edit', $options['liUserInfo']), 'nxs_snap') ?>. 
            <br/>You can Re- <?php } ?>            
            <a  href="<?php echo $nxs_snapThisPageUrl; ?>&auth=li&acc=<?php echo $ii; ?>">Authorize Your LinkedIn Account</a>  
            
            <?php if (!isset($options['liAccessTokenSecret']) || $options['liAccessTokenSecret']=='') { ?> <div class="blnkg">&lt;=== Authorize your account ===</div> <?php } ?>
            
            <?php } ?>
            </div>
             </td><td valign="top" width="50%">
           
            
               
    <span style="color:#005800; font-weight: bold; font-size: 14px;">NextScripts LinkedIn API:</span> Premium API with extended functionality. Can be used for posting to your profile, <b>group page</b> or <b>company page</b>. Less secure - requires your password. Use it only if you need to post to your LinkedIn Company Page.<br/><br/>
            
 <?php if (function_exists("doConnectToLinkedIn")) { ?>
                 
        <div class="subDiv" id="sub<?php echo $ii; ?>DivN" style="display: block;">  <span style="color:#800000; font-size: 14px;"> <b>Beta</b>, please <a target="_blank" href="http://www.nextscripts.com/support/">report</a> any problems.</span><br/><br/>              
          <div style="width:100%;"><strong>Your LinkedIn Page URL:</strong> Could be your company page or group page. Leave empty to post to your own profile.</div><input name="li[<?php echo $ii; ?>][uPage]" id="liuPage" style="width: 90%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['uPage'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />
          <br/>
          <div style="width:100%;"><strong>Your LinkedIn Username/Email:</strong> </div><input name="li[<?php echo $ii; ?>][ulName]" id="liulName" style="width: 70%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['ulName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /> 
          <div style="width:100%;"><strong>Your LinkedIn Password:</strong> </div><input type="password" name="li[<?php echo $ii; ?>][uPass]" id="liuPass" style="width: 75%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['uPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />
          
          </div>
          
          <?php } else { ?> 
          
          You can get NextScripts LinkedIn API <a target="_blank" href="http://www.nextscripts.com/linkedin-api-automated-posting/"><b>here</b></a>.
          
          <?php } ?> 
          
          </td></tr></table>
          
             <br/><br/>    
  
            <div id="altFormat">
              <div style="width:100%;"><strong id="altFormatText"><?php _e('Message text Format', 'nxs_snap'); ?>:</strong> </div>
              <textarea cols="150" rows="3" id="li<?php echo $ii; ?>SNAPformat" name="li[<?php echo $ii; ?>][apLIMsgFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#li<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apLIMsgFrmt<?php echo $ii; ?>');"><?php _e(apply_filters('format_to_edit',htmlentities($options['liMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?></textarea>              
              <?php nxs_doShowHint("apIPMsgFrmt".$ii); ?>              
            </div>              
            
            <p style="margin: 0px;"><input value="1"  id="apLIAttch" onchange="doShowHideAltFormat();" type="checkbox" name="li[<?php echo $ii; ?>][apLIAttch]"  <?php if ((int)$options['liAttch'] == 1) echo "checked"; ?> /> 
              <strong>Publish Posts to LinkedIn as an Attachment</strong>                                 
            </p>           
            
            <div style="margin-left: 10px;">
            
            <strong><?php _e('Attachment Text Format', 'nxs_snap'); ?>:</strong><br/> 
      <input value="1"  id="apLIMsgAFrmtA<?php echo $ii; ?>" <?php if (trim($options['liMsgAFrmt'])=='') echo "checked"; ?> onchange="if (jQuery(this).is(':checked')) { jQuery('#apLIMsgAFrmtDiv<?php echo $ii; ?>').hide(); jQuery('#apLIMsgAFrmt<?php echo $ii; ?>').val(''); }else jQuery('#apLIMsgAFrmtDiv<?php echo $ii; ?>').show();" type="checkbox" name="li[<?php echo $ii; ?>][apLIMsgAFrmtA]"/> <strong><?php _e('Auto', 'nxs_snap'); ?></strong>
      <i> - <?php _e('Recommended. Info from SEO Plugins will be used, then post excerpt, then post text', 'nxs_snap'); ?> </i><br/>
      <div id="apLIMsgAFrmtDiv<?php echo $ii; ?>" style="<?php if ($options['liMsgAFrmt']=='') echo "display:none;"; ?>" >&nbsp;&nbsp;&nbsp; <?php _e('Set your own format', 'nxs_snap'); ?>:<input name="li[<?php echo $ii; ?>][apLIMsgAFrmt]" id="apLIMsgAFrmt<?php echo $ii; ?>" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['liMsgAFrmt'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/></div>
            
            </div>
            
            <br/>
            <div id="altFormat">
              <div style="width:100%;"><strong id="altFormatText"><?php _e('Message title Format (Groups Only)', 'nxs_snap'); ?>:</strong> </div>
              
              <input name="li[<?php echo $ii; ?>][apLIMsgFrmtT]" id="li<?php echo $ii; ?>SNAPformatT" style="width: 50%;" value="<?php if ($isNew) echo "New Post - %TITLE%"; else _e(apply_filters('format_to_edit',htmlentities($options['liMsgFormatT'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?>" onfocus="mxs_showFrmtInfo('apLIMsgFrmtT<?php echo $ii; ?>');" /><?php nxs_doShowHint("apIPMsgFrmt".$ii); ?>
                         
            </div>              
                        
            <br/>    
            
            <?php if($options['liAPIKey']!='' || (isset($options['uPass']) && $options['uPass']!='')) { ?>
            <?php wp_nonce_field( 'rePostToLI', 'rePostToLI_wpnonce' ); ?>
            <br/><b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('LI', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>
            
            <?php if (!isset($options['liOK']) || $options['liOK']=='') { ?> <div class="blnkg">&lt;=== Click "Test" to finish setup ===</div> <?php } ?>
            
              <br/><?php }?>
            <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div>  
            </div>
        </div>
        <?php
      
      
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; 
    foreach ($post as $ii => $pval){ // prr($pval);
      if ( (isset($pval['apLIAPIKey']) && $pval['apLIAPISec']!='') || (isset($pval['uPass']) && $pval['uPass']!='') ) { if (!isset($options[$ii])) $options[$ii] = array();  $options[$ii]['ii'] = $ii;        
        if (isset($pval['apDoLI']))    $options[$ii]['doLI'] = $pval['apDoLI']; else $options[$ii]['doLI'] = 0;
        if (isset($pval['nName']))     $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apLIAPIKey']))$options[$ii]['liAPIKey'] = trim($pval['apLIAPIKey']);                                
        if (isset($pval['apLIAPISec']))$options[$ii]['liAPISec'] = trim($pval['apLIAPISec']);        
        if (isset($pval['apLIAttch'])) $options[$ii]['liAttch'] = $pval['apLIAttch']; else $options[$ii]['liAttch'] = 0;        
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']);
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
        
        if (isset($pval['ulName']))     $options[$ii]['ulName'] = trim($pval['ulName']);        
        if (isset($pval['uPass']))     $options[$ii]['uPass'] = trim($pval['uPass']);        
        if (isset($pval['grpID']))     $options[$ii]['grpID'] = trim($pval['grpID']);                
        if (isset($pval['uPage']))     $options[$ii]['uPage'] = trim($pval['uPage']);                
        if (isset($pval['apLIMsgFrmt'])) $options[$ii]['liMsgFormat'] = trim($pval['apLIMsgFrmt']); 
        if (isset($pval['apLIMsgFrmtT'])) $options[$ii]['liMsgFormatT'] = trim($pval['apLIMsgFrmtT']); 
        if (isset($pval['apLIMsgAFrmt']))    $options[$ii]['liMsgAFrmt'] = trim($pval['apLIMsgAFrmt']); 
        
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      } //prr($options);
    } return $options;
  } 
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; //prr($ntOpts);
    foreach($ntOpts as $ii=>$options)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapLI', true));  if (is_array($pMeta)) $options = $this->adjMetaOpt($options, $pMeta[$ii]); 
    $doLI = $options['doLI'] && (is_array($pMeta) || $options['catSel']!='1'); 
        $isAvailLI =  (isset($options['liOAuthVerifier']) && $options['liOAuthVerifier']!='' && $options['liAccessTokenSecret']!='' && $options['liAccessToken']!='' && $options['liAPIKey']!='') || ($options['ulName']!=='' && $options['uPass']!=='');
        $isAttachLI = $options['liAttch']; $liMsgFormat = htmlentities($options['liMsgFormat'], ENT_COMPAT, "UTF-8"); $liMsgFormatT = htmlentities($options['liMsgFormatT'], ENT_COMPAT, "UTF-8"); 
      ?>  
      
<tr><th style="text-align:left;" colspan="2"><?php if ( $options['catSel']=='1' && trim($options['catSelEd'])!='' )  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_LI<?php echo $ii; ?>" value="<?php echo $options['catSelEd']; ?>" /> <?php } ?>
      <?php if ($isAvailLI) { ?><input class="nxsGrpDoChb" value="1" id="doLI<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="li[<?php echo $ii; ?>][doLI]" <?php if ((int)$doLI == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="li[<?php echo $ii; ?>][doLI]" value="<?php echo $doLI;?>"> <?php } ?> <?php } ?>
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/li16.png);">LinkedIn - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $options['nName']; ?></i>)</div></th><td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailLI) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToLI_repostButton" id="rePostToLI_button" value="<?php _e('Repost to LinkedIn', 'nxs_snap') ?>" />
                    <?php wp_nonce_field( 'rePostToLI', 'rePostToLI_wpnonce' ); } ?>
                    
                    <?php if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) {                         
                        ?> <span id="pstdLI<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
                      <a style="font-size: 10px;" href="<?php if ($options['uPage']!='') echo $options['uPage']; else { echo $pMeta[$ii]['pgID']; } ?>" target="_blank"><?php $nType="LinkedIn"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                    
                </td></tr>
                <?php if (!$isAvailLI) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your LinkedIn Account to AutoPost to LinkedIn</b>
                <?php } elseif ($post->post_status != "puZblish") { ?> 
                
                <tr><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">
                <input value="0"  type="hidden" name="li[<?php echo $ii; ?>][AttachPost]"/>
                <input value="1"  id="SNAP_AttachLI" onchange="doShowHideAltFormatX();" type="checkbox" name="li[<?php echo $ii; ?>][AttachPost]"  <?php if ((int)$isAttachLI == 1) echo "checked"; ?> /> </th><td><strong>Publish Post to LinkedIn as Attachment</strong></td> </tr>               
                
                <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Message Format:', 'nxs_snap') ?></th>
                <td>                
                <textarea cols="150" rows="1" id="li<?php echo $ii; ?>SNAPformat" name="li[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#li<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apLIMsgFrmt<?php echo $ii; ?>');"><?php echo $liMsgFormat; ?></textarea>
                <?php nxs_doShowHint("apLIMsgFrmt".$ii); ?></td></tr>
                
                <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Title Format (Groups Only):', 'nxs_snap') ?></th>
                <td><input value="<?php echo $liMsgFormatT ?>" type="text" name="li[<?php echo $ii; ?>][SNAPformatT]"  style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apLIMsgFrmtT<?php echo $ii; ?>');"/><?php nxs_doShowHint("apLIMsgFrmtT".$ii, '', '58'); ?></td></tr>

                <?php } 
    }      
  }
  
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['liMsgFormat'] = $pMeta['SNAPformat']; if (trim($optMt['liMsgFormat'])=='') $optMt['liMsgFormat'] = '&nbsp;';
     if (isset($pMeta['SNAPformatT'])) $optMt['liMsgFormatT'] = $pMeta['SNAPformatT']; if (trim($optMt['liMsgFormatT'])=='') $optMt['liMsgFormatT'] = '&nbsp;';
     if (isset($pMeta['AttachPost'])) $optMt['liAttch'] = $pMeta['AttachPost'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['liAttch'] = 0; }
     if (isset($pMeta['doLI'])) $optMt['doLI'] = $pMeta['doLI'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doLI'] = 0; } 
     if (isset($pMeta['SNAPincludeLI']) && $pMeta['SNAPincludeLI'] == '1' ) $optMt['doLI'] = 1;  
     return $optMt;
  }
}}

if (!function_exists("nxs_rePostToLI_ajax")) { function nxs_rePostToLI_ajax() {  check_ajax_referer('rePostToLI');  $postID = $_POST['id']; // $result = nsPublishTo($id, 'FB', true);   
      global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
      foreach ($options['li'] as $ii=>$po) if ($ii==$_POST['nid']) {  $po['ii'] = $ii; $po['pType'] = 'aj';
      $mpo =  get_post_meta($postID, 'snapLI', true); $mpo =  maybe_unserialize($mpo);
      if (is_array($mpo) && isset($mpo[$ii]) && is_array($mpo[$ii]) ){ $ntClInst = new nxs_snapClassLI(); $po = $ntClInst->adjMetaOpt($po, $mpo[$ii]); } 
      $result = nxs_doPublishToLI($postID, $po);  if ($result == 200 && ($postID=='0') && $options['li'][$ii]['liOK']!='1') { $options['li'][$ii]['liOK']=1;  update_option('NS_SNAutoPoster', $options); }
      if ($result == 200) die("Successfully sent your post to LinkedIn."); else die($result);
    }    
  }
}

if (!function_exists("nxs_doPublishToLI")) { //## Second Function to Post to LI
  function nxs_doPublishToLI($postID, $options){ global $nxs_gCookiesArr; $ntCd = 'LI'; $ntCdL = 'li'; $ntNm = 'LinkedIn';   
    //if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToLI',  array($postID, $options));  
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
    $logNT = '<span style="color:#000058">LinkedIn</span> - '.$options['nName'];
    $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') {  sleep(5);
         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$uqID); return;
        }
    }
  
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); // prr($options);
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $msgT = 'Test Post from '.$blogTitle;  $link = home_url(); $msg = 'Test Post from '.$blogTitle. " ".$link; $isAttachLI = ''; $title = $blogTitle; }
      else { $post = get_post($postID); if(!$post) return; $liMsgFormat = $options['liMsgFormat'];  $msg = nsFormatMessage($liMsgFormat, $postID);  $msgT = nsFormatMessage($options['liMsgFormatT'], $postID); 
        $link = get_permalink($postID); $isAttachLI = $options['liAttch']; $title = nsTrnc($post->post_title, 200); nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1')); 
    }
    $extInfo = ' | PostID: '.$postID." - ".$post->post_title;     $msgT = nsTrnc($msgT, 200);  
    if ($isAttachLI=='1') { 
      if (trim($options['liMsgAFrmt'])!='') {$dsc = nsFormatMessage($options['liMsgAFrmt'], $postID, $addParams);} else { 
        $src = nxs_getPostImage($postID); $dsc = trim(apply_filters('the_content', $post->post_excerpt)); if ($dsc=='') $dsc = apply_filters('the_content', $post->post_content);  
      } $dsc = strip_tags($dsc); $dsc = nxs_decodeEntitiesFull($dsc); $dsc = nxs_html_to_utf8($dsc);  $dsc = nsTrnc($dsc, 300);        
    }  
    $msg = nxs_html_to_utf8($msg);  $msgT = nxs_html_to_utf8($msgT);
    if (function_exists("doConnectToLinkedIn") && $options['ulName']!='' && $options['uPass']!='') {      
      $auth = doConnectToLinkedIn($options['ulName'], $options['uPass'], $options['ii']); if ($auth!==false) die($auth);
      $to = $options['uPage']!=''?$options['uPage']:'http://www.linkedin.com/home'; $lnk = array(); $msg = str_ireplace('&nbsp;',' ',$msg);  $msg = nsTrnc(strip_tags($msg), 700);
       if ($postID=='0') { $lnk['title'] = get_bloginfo('name'); $lnk['desc'] = get_bloginfo('description'); $lnk['url'] = home_url();  
         } else { if ($isAttachLI=='1') { $lnk['title'] = nsTrnc( strip_tags($post->post_title), 200); $lnk['postTitle'] = $msgT; $lnk['desc'] = $dsc; $lnk['url'] = get_permalink($postID); $lnk['img'] = $src; }} //prr($msg);
      $ret = doPostToLinkedIn($msg, $lnk, $to); $liPostID = $options['uPage'];
    } else { require_once ('apis/liOAuth.php'); $linkedin = new nsx_LinkedIn($options['liAPIKey'], $options['liAPISec']);  $linkedin->oauth_verifier = $options['liOAuthVerifier'];
      $linkedin->request_token = new nsx_trOAuthConsumer($options['liOAuthToken'], $options['liOAuthTokenSecret'], 1);     
      $linkedin->access_token = new nsx_trOAuthConsumer($options['liAccessToken'], $options['liAccessTokenSecret'], 1);  $msg = nsTrnc($msg, 700); 
      if ($options['grpID']!=''){
        try{ //  prr($msgT); prr($msg); prr($options['grpID']); prr($src);  prr($dsc); $purl = get_permalink($postID); prr($purl);
            if($isAttachLI=='1') $ret = $linkedin->postToGroup($msg, $msgT, $options['grpID'], get_permalink($postID), $src, $dsc); else $ret = $linkedin->postToGroup($msg, $msgT, $options['grpID']); 
            $liPostID= 'http://www.linkedin.com/groups?gid='.$options['grpID'];
        } catch (Exception $o){  nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '."Linkedin Status couldn't be updated! - ".print_r($o, true)); $ret="ERROR:".print_r($o, true); }        
      } else {
        try{ if($isAttachLI=='1') $ret = $linkedin->postShare($msg, nsTrnc($post->post_title, 200), get_permalink($postID), $src, $dsc); else $ret = $linkedin->postShare($msg); }
        catch (Exception $o){  nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '."<br/>Linkedin Status couldn't be updated! - ".print_r($o, true)); $ret="ERROR:".print_r($o, true); }     
      }    
      //$liPostID = $linkedin->getCurrentShare(); prr($liPostID);
      if ($liPostID=='') $liPostID = $options['liUserInfo'];
    }
    if (stripos($ret, 'update-url')!==false) { $liPostID = CutFromTo($ret, '<update-url>','</update-url>'); $ret = '201'; }     
    if ($ret!='201') { if ($postID=='0') prr($ret); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo); } 
      else if ($postID=='0') { echo 'OK - Linkedin status updated successfully';  nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); } else 
        { nxs_metaMarkAsPosted($postID, 'LI', $options['ii'], array('isPosted'=>'1', 'pgID'=>$liPostID, 'pDate'=>date('Y-m-d H:i:s')));  nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo); }
    if ($ret=='201') return true; else return 'Something Wrong';
  }
}

?>