<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'PK', 'lcode'=>'pk', 'name'=>'Plurk');

if (!class_exists("nxs_snapClassPK")) { class nxs_snapClassPK {
    
  function pkCats() { return '<option value="">:freestyle(None)</option><option value="loves">loves</option><option value="likes">likes</option><option value="shares">shares</option><option value="gives">gives</option><option value="hates">hates</option><option value="wants">wants</option><option value="wishes">wishes</option><option value="needs">needs</option><option value="will">will</option><option value="hopes">hopes</option><option value="asks">asks</option><option value="has">has</option><option value="was">was</option><option value="wonders">wonders</option><option value="feels on">feels</option><option value="thinks">thinks</option><option value="says">says</option><option value="is">is</option>';}  
  //#### Show Common Settings  
  function showGenNTSettings($ntOpts){ global $nxs_snapThisPageUrl, $nxs_plurl; $code = 'PK'; $lcode = 'pk'; wp_nonce_field( 'ns'.$code, 'ns'.$code.'_wpnonce' ); 
   if ( isset($_GET['auth']) && $_GET['auth']=='pk'){ require_once('apis/plurkOAuth.php'); $options = $ntOpts[$_GET['acc']];
              $consumer_key = $options['pkConsKey']; $consumer_secret = $options['pkConsSec'];
              $callback_url = $nxs_snapThisPageUrl."&auth=pka&acc=".$_GET['acc'];
             
              $tum_oauth = new wpPlurkOAuth($consumer_key, $consumer_secret); //prr($tum_oauth);
              $request_token = $tum_oauth->getReqToken($callback_url); 
              $options['pkOAuthToken'] = $request_token['oauth_token'];
              $options['pkOAuthTokenSecret'] = $request_token['oauth_token_secret'];// prr($tum_oauth ); die();

              //prr($tum_oauth); prr($options); die();
              
              switch ($tum_oauth->http_code) { case 200: $url = 'http://www.plurk.com/OAuth/authorize?oauth_token='.$options['pkOAuthToken']; 
                $optionsG = get_option('NS_SNAutoPoster'); $optionsG['pk'][$_GET['acc']] = $options;  update_option('NS_SNAutoPoster', $optionsG);
                echo '<script type="text/javascript">window.location = "'.$url.'"</script>'; break; 
                default: echo '<br/><b style="color:red">Could not connect to Plurk. Refresh the page or try again later.</b>'; die();
              }
              die();
            }
   if ( isset($_GET['auth']) && $_GET['auth']=='pka'){ require_once('apis/plurkOAuth.php'); $options = $ntOpts[$_GET['acc']];
              $consumer_key = $options['pkConsKey']; $consumer_secret = $options['pkConsSec'];
            
              $tum_oauth = new wpPlurkOAuth($consumer_key, $consumer_secret, $options['pkOAuthToken'], $options['pkOAuthTokenSecret']); //prr($tum_oauth);
              $access_token = $tum_oauth->getAccToken($_GET['oauth_verifier']); prr($access_token);
              $options['pkAccessTocken'] = $access_token['oauth_token'];  $options['pkAccessTockenSec'] = $access_token['oauth_token_secret'];
              $optionsG = get_option('NS_SNAutoPoster'); $optionsG['pk'][$_GET['acc']] = $options;  update_option('NS_SNAutoPoster', $optionsG);
              
              $tum_oauth = new wpPlurkOAuth($consumer_key, $consumer_secret, $options['pkAccessTocken'], $options['pkAccessTockenSec']); 
              $uinfo = $tum_oauth->makeReq('http://www.plurk.com/APP/Profile/getOwnProfile', $params); 
              if (is_array($uinfo) && isset($uinfo['user_info'])) $userinfo = $uinfo['user_info']['display_name'];
              
              $options['pkPgID'] = $userinfo; $optionsG = get_option('NS_SNAutoPoster'); $optionsG['pk'][$_GET['acc']] = $options;  update_option('NS_SNAutoPoster', $optionsG);

              if ($options['pkPgID']!='') {  echo '<script type="text/javascript">window.location = "'.$nxs_snapThisPageUrl.'"</script>'; break;  die();}
                else die("<span style='color:red;'>ERROR: Authorization Error: <span style='color:darkred; font-weight: bold;'>".$options['pkPgID']."</span></span>");              
            }
    ?>    
    <hr/><div class="nsx_iconedTitle" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $lcode; ?>16.png);">Plurk Settings:   <?php $cfbo = count($ntOpts); $mfbo =  1+max(array_keys($ntOpts)); ?> <?php wp_nonce_field( 'nsFB', 'nsFB_wpnonce' ); ?>
    <div class="nsBigText">You have <?php echo $cfbo=='0'?'No':$cfbo; ?> Plurk account<?php if ($cfbo!=1){ ?>s<?php } ?> <!-- - <a href="#" class="NXSButton" onclick="doShowHideBlocks2('FB<?php echo $mfbo; ?>');return false;">Add new Facebook Account</a> --> </div></div>
    
    <?php // if (function_exists('nxs_doSMAS1')) nxs_doSMAS1($this, $mfbo); else nxs_doSMAS('Plurk', 'TR'.$mfbo); ?>
    <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = str_ireplace('https://','', str_ireplace('http://','', $pbo['pkURL']));  ?>
      <p style="margin: 0px;margin-left: 5px;"><input value="1" id="apDoPK" name="pk[<?php echo $indx; ?>][apDoPK]" type="checkbox" <?php if ((int)$pbo['doPK'] == 1) echo "checked"; ?> /> 
      <strong>Auto-publish your Posts to your Plurk Blog <i style="color: #005800;"><?php if($pbo['nName']!='') echo "(".$pbo['nName'].")"; ?></i> </strong>
      &nbsp;&nbsp;<a id="doPK<?php echo $indx; ?>A" href="#" onclick="doShowHideBlocks2('PK<?php echo $indx; ?>');return false;">[Show Settings]</a>&nbsp;&nbsp;
      <a href="#" onclick="doDelAcct('pk', '<?php echo $indx; ?>', '<?php if (isset($pbo['pkURL'])) echo $pbo['pkURL']; ?>');return false;">[Remove Account]</a>
      </p><?php $this->showNTSettings($indx, $pbo);             
    } //## END TR Settings 
  }  
  //#### Show NEW Settings Page
  function showNewNTSettings($bo){ $po = array('nName'=>'', 'doPK'=>'1', 'pkURL'=>'', 'pkPgID'=>'', 'pkConsKey'=>'', 'pkInclTags'=>'1', 'cImgURL'=>'R', 'pkConsSec'=>'', 'pkPostType'=>'T', 'pkDefImg'=>'', 'pkOAuthTokenSecret'=>'', 'pkAccessTocken'=>'', 'pkMsgFormat'=>'%TITLE% - %URL%'); $this->showNTSettings($bo, $po, true);}
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl,$nxs_snapThisPageUrl; ?>
    <div id="doPK<?php echo $ii; ?>Div"<?php if ($isNew){ ?>class="clNewNTSets"<?php } ?> style="max-width: 1000px; background-color: #EBF4FB; background-image: url(<?php echo $nxs_plurl; ?>img/pk-bg.png);  background-position:90% 10%; background-repeat: no-repeat; margin: 10px; border: 1px solid #808080; padding: 10px; <?php if ((isset($options['pkOAuthTokenSecret']) && $options['pkOAuthTokenSecret']!='')||$isNew) { ?>display:none;<?php } ?>">   <input type="hidden" name="apDoSPK<?php echo $ii; ?>" value="0" id="apDoSPK<?php echo $ii; ?>" />                                     
    <?php if ($isNew) { ?> <input type="hidden" name="pk[<?php echo $ii; ?>][apDoPK]" value="1" id="apDoNewPK<?php echo $ii; ?>" /> <?php } ?>
    
    <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/pk16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-plurk-social-networks-auto-poster-wordpress/">Detailed Plurk Installation/Configuration Instructions</a></div>
    
            <div style="width:100%;"><strong>Account Nickname:</strong> <i>Just so you can easely identify it</i> </div><input name="pk[<?php echo $ii; ?>][nName]" id="pknName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /><br/>
            <?php echo nxs_addQTranslSel('pk', $ii, $options['qTLng']); ?><?php echo nxs_addPostingDelaySel('pk', $ii, $options['nHrs'], $options['nMin']); ?>
            
            <div style="width:100%;"><strong>Your Plurk URL:</strong> </div><input onchange="nxsPKURLVal(<?php echo $ii; ?>);" name="pk[<?php echo $ii; ?>][apPKURL]" id="apPKURL<?php echo $ii; ?>" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['pkURL'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" /><span style="color: #F00000;" id="apPKURLerr<?php echo $ii; ?>"></span>
            <div style="width:100%;"><strong>Your Plurk App Key:</strong> </div><input name="pk[<?php echo $ii; ?>][apPKConsKey]" id="apPKConsKey" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['pkConsKey'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />             
            <div style="width:100%;"><strong>Your Plurk App Secret:</strong> </div><input name="pk[<?php echo $ii; ?>][apPKConsSec]" id="apPKConsSec" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['pkConsSec'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>" />
            <br/><br/>
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText">Plurk prefix:</strong> </div>
  
            <select name="pk[<?php echo $ii; ?>][Cat]" id="pkCat<?php echo $ii; ?>">
            <?php  $pkCats = $this->pkCats(); 
              if (isset($options['pkCat']) && $options['pkCat']!='') $pkCats = str_replace($options['pkCat'].'"', $options['pkCat'].'" selected="selected"', $pkCats);  echo $pkCats; 
            ?>
            </select>            
            </div>  
            <br/>
    <p style="margin: 0px;"><input value="1"  id="apLIAttch" type="checkbox" name="pk[<?php echo $ii; ?>][attchImg]"  <?php if ((int)$options['attchImg'] == 1) echo "checked"; ?> /> <strong>Attach Image to Plurk Post</strong></p>
    <br/>
            
  <div style="width:100%;"><strong id="altFormatText">Post Text Format:</strong> (<a href="#" id="apPKMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apPKMsgFrmt<?php echo $ii; ?>'); return false;">Show format info</a>) </div>
              
              <input name="pk[<?php echo $ii; ?>][apPKMsgFrmt]" id="apPKMsgFrmt" style="width: 50%;" value="<?php if ($options['pkMsgFormat']!='') _e(apply_filters('format_to_edit', htmlentities($options['pkMsgFormat'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster'); else echo htmlentities("%TITLE% - %URL%"); ?>" onfocus="jQuery('#apPKMsgFrmt<?php echo $ii; ?>Hint').show();" /><br/>
               <?php nxs_doShowHint("apPKMsgFrmt".$ii); ?>
              
              <?php 
            if($options['pkConsSec']=='') { ?>
            <b>Authorize Your Plurk Account</b>. Please save your settings and come back here to Authorize your account.
            <?php } else { if(isset($options['pkAccessTocken']) && isset($options['pkAccessTocken']['oauth_token_secret']) && $options['pkAccessTocken']['oauth_token_secret']!=='') { ?>
            Your Plurk Account has been authorized. Your display name: <?php _e(apply_filters('format_to_edit', htmlentities($options['pkPgID'], ENT_COMPAT, "UTF-8")), 'NS_SNAutoPoster') ?>. 
            You can Re- <?php } ?>            
            <a href="<?php echo $nxs_snapThisPageUrl;?>&auth=pk&acc=<?php echo $ii; ?>">Authorize Your Plurk Account</a> 
              <?php if (!isset($options['pkOAuthTokenSecret']) || $options['pkOAuthTokenSecret']=='') { ?> <div class="blnkg">&lt;=== Authorize your account ===</div> <?php } ?>            
            <?php }  ?>            
            
            
            <?php if( isset($options['pkOAuthTokenSecret']) && $options['pkOAuthTokenSecret']!='') { ?>
            <?php wp_nonce_field( 'rePostToPK', 'rePostToPK_wpnonce' ); ?>
            <br/><br/><b>Test your settings:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('PK', '<?php echo $ii; ?>'); return false;">Submit Test Post to Plurk</a>  <br/><br/>
            <?php }?>
            <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div>  
            
        </div>
        <?php
      
      
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; //prr($post); die();
    foreach ($post as $ii => $pval){ // prr($pval);
      if (isset($pval['apPKConsKey']) && $pval['apPKConsSec']!='') { if (!isset($options[$ii])) $options[$ii] = array();
        
                if (isset($pval['apPKURL']))  {   $options[$ii]['pkURL'] = trim($pval['apPKURL']);  if ( substr($options[$ii]['pkURL'], 0, 4)!='http' )  $options[$ii]['pkURL'] = 'http://'.$options[$ii]['pkURL'];
                  $pkPgID = $options[$ii]['pkURL']; if (substr($pkPgID, -1)=='/') $pkPgID = substr($pkPgID, 0, -1);  $pkPgID = substr(strrchr($pkPgID, "/"), 1);
                  $options[$ii]['pkPgID'] = $pkPgID; //echo $fbPgID;
                }
                if (isset($pval['apDoPK']))         $options[$ii]['doPK'] = $pval['apDoPK']; else $options[$ii]['doPK'] = 0;
                if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
                if (isset($pval['apPKConsKey']))    $options[$ii]['pkConsKey'] = trim($pval['apPKConsKey']);
                if (isset($pval['apPKConsSec']))    $options[$ii]['pkConsSec'] = trim($pval['apPKConsSec']);                                
                if (isset($pval['apPKMsgFrmt']))    $options[$ii]['pkMsgFormat'] = trim($pval['apPKMsgFrmt']);                                
                if (isset($pval['Cat']))      $options[$ii]['pkCat'] = $pval['Cat']; else $options[$ii]['pkCat'] = "";
                if (isset($pval['attchImg'])) $options[$ii]['attchImg'] = $pval['attchImg']; else $options[$ii]['attchImg'] = 0;                
                if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
                if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      } // prr($options);
    } return $options;
  } 
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; 
    foreach($ntOpts as $ii=>$options)  {$pMeta = maybe_unserialize(get_post_meta($post_id, 'snapPK', true));  if (is_array($pMeta)) $options = $this->adjMetaOpt($options, $pMeta[$ii]); $doPK = $options['doPK']; 
       $isAvailPK =  isset($options['pkAccessTocken']) && isset($options['pkAccessTocken']['oauth_token_secret']) && $options['pkAccessTocken']['oauth_token_secret']!=='';          
       $pkMsgFormat = htmlentities($options['pkMsgFormat'], ENT_COMPAT, "UTF-8");  $pkMsgTFormat = htmlentities($options['pkMsgTFormat'], ENT_COMPAT, "UTF-8");
      ?>  
      
      <tr><th style="text-align:left;" colspan="2">
      <?php if ($isAvailPK) { ?><input class="nxsGrpDoChb" value="1" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="pk[<?php echo $ii; ?>][SNAPincludePK]" <?php if (($post->post_status == "publish" && $options['isPosted'] == '1') || ($post->post_status != "publish" && ((int)$doPK == 1)) ) echo 'checked="checked" title="def"';  ?> /> <?php } ?>
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/pk16.png);">Plurk - publish to (<i style="color: #005800;"><?php echo $options['nName']; ?></i>) </div></th><td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailPK) { ?><input alt="<?php echo $ii; ?>" style="float: right;" type="button" class="button" name="rePostToPK_repostButton" id="rePostToPK_button" value="<?php _e('Repost to Plurk', 're-post') ?>" />
                    <?php wp_nonce_field( 'rePostToPK', 'rePostToPK_wpnonce' ); } ?>
                </td></tr>
                <?php if (!$isAvailPK) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup and authorize your Plurk Account to AutoPost to Plurk</b>
                <?php }elseif ($post->post_status != "publish") { ?> 
                         
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;">
                Prefix:
                
                </th>
                <td><select name="pk[<?php echo $ii; ?>][Cat]" id="apPKCat<?php echo $ii; ?>">
            <?php  $pkCats = $this->pkCats(); 
              if ($ntOpt['pkCat']!='') $pkCats = str_replace($ntOpt['pkCat'].'"', $ntOpt['pkCat'].'" selected="selected"', $pkCats);  echo $pkCats; 
            
             ?>
            </select></td></tr>
                
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;"><?php _e('Text Format:', 'NS_SPAP') ?></th>
                <td><input value="<?php echo $pkMsgFormat ?>" type="text" name="pk[<?php echo $ii; ?>][SNAPformat]" size="115" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apPKMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apPKMsgFrmt".$ii); ?></td></tr>
                               
   <?php } 
    }
      
  }
  
  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else $optMt['isPosted'] = '';
     if (isset($pMeta['SNAPformat'])) $optMt['pkMsgFormat'] = $pMeta['SNAPformat']; 
     if (isset($pMeta['Cat'])) $optMt['pkCat'] = $pMeta['Cat'];      
     if (isset($pMeta['SNAPincludePK'])) $optMt['doPK'] = $pMeta['SNAPincludePK'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doPK'] = 0; }return $optMt;
  }
}}

if (!function_exists("nxs_rePostToPK_ajax")) { function nxs_rePostToPK_ajax() {  check_ajax_referer('rePostToPK');  $postID = $_POST['id']; // $result = nsPublishTo($id, 'FB', true);   
    $options = get_option('NS_SNAutoPoster');  foreach ($options['pk'] as $ii=>$po) if ($ii==$_POST['nid']) {   $po['ii'] = $ii; $po['pType'] = 'aj';
      $mpo =  get_post_meta($postID, 'snapPK', true); $mpo =  maybe_unserialize($mpo); 
      if (is_array($mpo) && isset($mpo[$ii]) && is_array($mpo[$ii]) ){ $ntClInst = new nxs_snapClassPN(); $po = $ntClInst->adjMetaOpt($po, $mpo[$ii]); }
      $result = nxs_doPublishToPK($postID, $po); if ($result == 200 || $result == 201) die("Your post has been successfully sent to Plurk."); else { echo $result; die(); }
    }    
  }
}

if (!function_exists("nxs_doPublishToPK")) { //## Second Function to Post to TR
  function nxs_doPublishToPK($postID, $options){ $ntCd = 'PK'; $ntCdL = 'pk'; $ntNm = 'Plurk';
      
    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); 
    
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        nxs_addToLog($ntCd.' - '.$options['nName'], 'E', '-=Duplicate=- Post ID:'.$postID, 'Not posted. No reason for posting duplicate'); return;
    }  
    //## Format
    if ($postID=='0') { echo "Testing ... <br/><br/>"; $msg = 'Test Post from '.$blogTitle;  $msgT = 'Test Post from '.$blogTitle;}
      else{ $post = get_post($postID); if(!$post) return; $pkMsgFormat = $options['pkMsgFormat'];  $msg = nsFormatMessage($pkMsgFormat, $postID); 
        $pkMsgTFormat = $options['pkMsgTFormat'];  nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1'));   
    } 
    //## Post    
    require_once('apis/plurkOAuth.php'); $consumer_key = $options['pkConsKey']; $consumer_secret = $options['pkConsSec'];
    $tum_oauth = new wpPlurkOAuth($consumer_key, $consumer_secret, $options['pkAccessTocken'], $options['pkAccessTockenSec']); 
    $pkURL = trim(str_ireplace('http://', '', $options['pkURL'])); if (substr($pkURL,-1)=='/') $pkURL = substr($pkURL,0,-1);     
    if ($options['pkCat']=='')$options['pkCat'] = ':';
    
    if ($options['attchImg']=='1') { $imgURL = nxs_getPostImage($postID); $msg .= " ".$imgURL; }
    
    $postDate = ($post->post_date_gmt!='0000-00-00 00:00:00'?$post->post_date_gmt:gmdate("Y-m-d H:i:s", strtotime($post->post_date)))." GMT";  //## Adds date to Tumblr post. Thanks to Kenneth Lecky
    $extInfo = ' | PostID: '.$postID." - ".$post->post_title; $logNT = '<span style="color:#014A76">Plurk</span> - '.$options['nName'];
    $postArr = array('content'=>$msg, 'qualifier'=>$options['pkCat']);
    $postinfo = $tum_oauth->makeReq('http://www.plurk.com/APP/Timeline/plurkAdd', $postArr);    
    
    $code = $tum_oauth->http_code;// echo "XX".print_r($code);  prr($postinfo); // prr($msg); prr($postinfo); echo $code."VVVV"; die("|====");
    if ($code == 200) { if ($postID=='0') { nxs_addToLog($logNT, 'M', 'OK - TEST Message Posted '); echo 'OK - Message Posted, please see your Plurk  Page. <br/> Result:'; prr($postinfo); } 
      else { nxs_addToLog($logNT, 'M', 'OK - Message Posted ', $extInfo);  nxs_metaMarkAsPosted($postID, 'PK', $options['ii']);  } } 
    else { nxs_addToLog($logNT, 'E', '-=ERROR=- '.print_r($postinfo, true), $extInfo); if ($postID=='0') prr($postinfo); $code .= " ERROR: - ".$postinfo['error_text']; }
    
    return $code;
  }
}

?>