<?php
/*
Plugin Name: NextScripts: Social Networks Auto-Poster
Plugin URI: http://www.nextscripts.com/social-networks-auto-poster-for-wordpress
Description: This plugin automatically publishes posts from your blog to multiple accounts on Facebook, Twitter, and Google+ profiles and/or pages.
Author: Next Scripts
Version: 2.0.8
Author URI: http://www.nextscripts.com
Copyright 2012  Next Scripts, Inc
*/
define( 'NextScripts_SNAP_Version' , '2.0.8' ); require_once "nxs_functions.php";    // require_once "nxs_f2.php";  
//## Include All Available Networks
$nxs_snapAvNts = array();  foreach (glob(plugin_dir_path( __FILE__ ).'inc-cl/*.php') as $filename){ include $filename; }
$nxs_snapThisPageUrl = admin_url().'options-general.php?page=NextScripts_SNAP.php'; 

//## Define SNAutoPoster class
if (!class_exists("NS_SNAutoPoster")) {
    class NS_SNAutoPoster {//## General Functions         
        var $dbOptionsName = "NS_SNAutoPoster";       
        var $nxs_options = "";
        function __construct() { $this->nxs_options = $this->getAPOptions();} 
        //## Constructor
        function NS_SNAutoPoster() { }
        //## Initialization function
        function init() { $this->getAPOptions(); }
        //## Administrative Functions
        //## Options loader function
        function getAPOptions() {
            //## Some Default Values
            //$options = array( 'fb''fbAttch'=>1, 'nsOpenGraph'=>1, 'fbMsgFormat'=>'New post has been published on %SITENAME%',  'gpAttch'=>1, 'gpMsgFormat'=>'New post has been published on %SITENAME%', 'twMsgFormat'=>'%TITLE% - %URL%');
            $options = array('nsOpenGraph'=>1);            
            $dbOptions = get_option($this->dbOptionsName); 
            if (!empty($dbOptions))  foreach ($dbOptions as $key => $option) if (trim($key)!='') $options[$key] = $option;  //  prr($options); die();
            //if ( (isset($options['ukver']) && $options['ukver']!='' && isset($options['uklch']) && $options['uklch']!='' && strtotime("+1 day", $options['uklch'])<time()) || (!isset($options['ukver']) || $options['ukver']=='') ) {
            if ( (isset($options['ukver']) && $options['ukver']!='' && isset($options['uklch']) && $options['uklch']!='' && strtotime("+2 hours", $options['uklch'])<time()) || (!isset($options['ukver']) || $options['ukver']=='') ) {
            //if ( (isset($options['ukver']) && $options['ukver']!='' && isset($options['uklch']) && $options['uklch']!='' && strtotime("+15 seconds", $options['uklch'])<time()) || (!isset($options['ukver']) || $options['ukver']=='') ) {
            //if ( (isset($options['ukver']) && $options['ukver']!='' && isset($options['uklch']) && $options['uklch']!='' && strtotime("+5 minutes", $options['uklch'])<time()) || (!isset($options['ukver']) || $options['ukver']=='') ) {
             // $options = nxs_doChAPIU($options); 
             // $options = getRemNSXOption($options);  
             $args = array($options); wp_schedule_single_event(time()+1,'nxs_chAPIU', $args); //echo "CHECK";
            } 
    
    
            if (isset($options['uk']) && $options['uk']!='') { getNSXOption(substr(nsx_doDecode($options['uk']), 5, -2));  } // nxs_doSMAS19();            
          //  echo NXSAPIVER;
            if (defined('NXSAPIVER') && $options['ukver']!=NXSAPIVER){$options['ukver']=NXSAPIVER;  update_option($this->dbOptionsName, $options);}
                        
            $options['isMA'] = function_exists('nxs_doSMAS1') && isset($options['lk']) && isset($options['uk']) && $options['uk']!='';   
            if (!isset($options['isPro']) || $options['isPro']!='1'){ //## Upgrade from non-pro version            
              $optPro = array();foreach ($options as $indx => $opt){                 
                 if (substr($indx, 0, 2)=='fb') $optPro['fb'][0][$indx] = $opt;
                 elseif (substr($indx, 0, 2)=='gp') $optPro['gp'][0][$indx] = $opt;
                 elseif (substr($indx, 0, 2)=='tw') $optPro['tw'][0][$indx] = $opt;
                 elseif (substr($indx, 0, 2)=='tr') $optPro['tr'][0][$indx] = $opt;
                 elseif (substr($indx, 0, 2)=='bg') $optPro['bg'][0][$indx] = $opt;
                 elseif (substr($indx, 0, 2)=='li') $optPro['li'][0][$indx] = $opt;
                 elseif (substr($indx, 0, 2)=='pn') $optPro['pn'][0][$indx] = $opt;
                 elseif ($indx=='doFB') $optPro['fb'][0][$indx] = $opt;
                 elseif ($indx=='doGP') $optPro['gp'][0][$indx] = $opt;
                 elseif ($indx=='doTW') $optPro['tw'][0][$indx] = $opt;
                 elseif ($indx=='doTR') $optPro['tr'][0][$indx] = $opt;
                 elseif ($indx=='doBG') $optPro['bg'][0][$indx] = $opt;
                 elseif ($indx=='doLI') $optPro['li'][0][$indx] = $opt;
                 elseif ($indx=='doPN') $optPro['pn'][0][$indx] = $opt;
                 elseif (trim($indx)!='') $optPro[$indx] = $opt; 
                 if ($options['twAccTokenSec']!='') $optPro['tw'][0]['twOK'] = '1';
                 if ($options['bgBlogID']!='') $optPro['bg'][0]['bgOK'] = '1';
                 $optPro['isPro'] = '1'; 
              } 
              //## Update the options for the panel
              $options = $optPro; update_option($this->dbOptionsName, $options);
            } 
            if(!$options['isMA']) $options = nxs_snapCleanup($options);
            return $options;
        }
        function showSNAutoPosterOptionsPage() { global $nxs_snapAvNts, $nxs_snapThisPageUrl, $nxsOne; $nxsOne = ''; $options = $this->nxs_options; 
          if (isset($_POST['update_NS_SNAutoPoster_settings'])) { if (get_magic_quotes_gpc()) {array_walk_recursive($_POST, 'nsx_stripSlashes');} 
            foreach ($nxs_snapAvNts as $avNt) if (isset($_POST[$avNt['lcode']])) { $clName = 'nxs_snapClass'.$avNt['code']; if (!isset($options[$avNt['lcode']])) $options[$avNt['lcode']] = array(); 
              $ntClInst = new $clName(); $ntOpt = $ntClInst->setNTSettings($_POST[$avNt['lcode']], $options[$avNt['lcode']]); $options[$avNt['lcode']] = $ntOpt;
            }           
            if (isset($_POST['apCats']))      $options['apCats'] = $_POST['apCats'];                
            if (isset($_POST['ogImgDef']))      $options['ogImgDef'] = $_POST['ogImgDef'];
            if (isset($_POST['nsOpenGraph']))   $options['nsOpenGraph'] = $_POST['nsOpenGraph']; else $options['nsOpenGraph'] = 0;                
            if (isset($_POST['nxsCPTSeld']))      $options['nxsCPTSeld'] = serialize($_POST['nxsCPTSeld']);          
            update_option($this->dbOptionsName, $options); // prr($options);
            ?><div class="updated"><p><strong><?php _e("Settings Updated.", "NS_SNAutoPoster");?></strong></p></div><?php           
          }   $isNoNts = true; foreach ($nxs_snapAvNts as $avNt) if (isset($options[$avNt['lcode']]) && is_array($options[$avNt['lcode']]) && count($options[$avNt['lcode']])>0) {$isNoNts = false; break;} 
       //   prr($options);    
          //add_action('admin_head', 'nxs_jsPostToSNAP2');    
          ?>          
           <?php $nxsOne = NextScripts_SNAP_Version; if (defined('NXSAPIVER')) $nxsOne .= " (API Version: ".NXSAPIVER.")"; ?>
           <div style="float:right; padding-top: 10px; padding-right: 10px;">
              <div style="float:right; text-align: center;"><a target="_blank" href="http://www.nextscripts.com"><img src="http://www.nextscripts.net/wp-content/uploads/2012/07/Next_Scripts_Logo2.1-HOR-100px.png"></a><br/>
              <a style="font-weight: normal; font-size: 16px; line-height: 24px;" target="_blank" href="http://www.nextscripts.com/support">[Contact support]</a> 
              <?php if(!$options['isMA']) { ?><br/> <span style="color:#800000;">Ready to to Upgrade to Multiple Accounts Edition<br/> and get Google+ and Pinterest Auto-Posting?</span>
              <br/><a style="font-weight: normal; font-size: 12px; line-height: 24px;" target="_blank" id="showLic" href="#">[Enter your Activation Key]</a>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="http://www.nextscripts.com/social-networks-auto-poster-for-wp-multiple-accounts#getit">[Get Key]</a>  <?php } ?>
              </div>
              <div id="showLicForm"><span class="nxspButton bClose"><span>X</span></span><div style="position: absolute; right: 10px; top:10px; font-family: 'News Cycle'; font-size: 34px; font-weight: lighter;">Activation</div>
              <br/><br/>
              <h3>Multiple Accounts Edition and Google+ and Pinterest Auto-Posting</h3><br/>You can find your key on this page: <a href="http://www.nextscripts.com/mypage">http://www.nextscripts.com/mypage</a>
                <br/><br/> Enter your Key:  <input name="eLic" id="eLic"  style="width: 50%;"/>
                <input type="button" class="button-primary" name="eLicDo" onclick="doLic();" value="Enter" />
                <br/><br/>Your plugin will be automatically upgraded. <?php wp_nonce_field( 'doLic', 'doLic_wpnonce' ); ?>
              </div>
              
           </div>            
           <div class=wrap><h2>Next Scripts: Social Networks Auto Poster Options</h2> Plugin Version: <span style="color:#008000;font-weight: bold;"><?php echo $nxsOne; ?></span> <?php if($options['isMA']) { ?> [Pro - Multiple Accounts Edition]&nbsp;&nbsp;<?php } else {?>
           <span style="color:#800000; font-weight: bold;">[Single Accounts Edition]</span> - <a target="_blank" href="http://www.nextscripts.com/social-networks-auto-poster-for-wp-multiple-accounts">Get Multiple Accounts Edition</a><br/><br/>
           Here you can setup "Social Networks Auto Poster".<br/> You can start by clicking "Add new account" button and choosing the Social Network you would like to add.<?php } ?><br/> Please see the <a target="_blank" href="http://www.nextscripts.com/installation-of-social-networks-auto-poster-for-wordpress">detailed installation instructions</a> (will open in a new tab)
           <?php
           if (!function_exists('curl_init')) {  
               echo ('<br/><b style=\'font-size:16px; color:red;\'>Error: No CURL Found</b> <br/><i>Social Networks AutoPoster needs the CURL PHP extension. Please install it or contact your hosting company to install it.</i><br/>'); 
           }
           ?>
           
           
           
<ul class="nsx_tabs">
    <li><a href="#nsx_tab1">Your Social Networks Accounts</a></li>
    <li><a href="#nsx_tab2">Other Settings</a></li>
</ul>
<form method="post" id="nsStForm" action="<?php echo $nxs_snapThisPageUrl?>">
<div class="nsx_tab_container">
    <div id="nsx_tab1" class="nsx_tab_content"><a href="#" class="NXSButton" id="nxs_snapAddNew">Add new account</a> <div class="nxsInfoMsg"><img style="position: relative; top: 8px;" alt="Arrow" src="http://cdn.gtln.us/img/icons/arrow_l_green_c1.png"/> You can add Facebook, Twitter, Google+, Pinterest, LinkedIn, Tumblr, Blogger/Blogspot accounts</div><br/><br/>
           <div id="nxs_spPopup"><span class="nxspButton bClose"><span>X</span></span>Add New Network: <select onclick="doShowFillBlockX(this.value);" id="nxs_ntType"><option value =""></option>
           <?php foreach ($nxs_snapAvNts as $avNt) { if (!isset($options[$avNt['lcode']]) || count($options[$avNt['lcode']])==0) $mt=0; else $mt = 1+max(array_keys($options[$avNt['lcode']]));
              echo '<option value ="'.$avNt['code'].$mt.'">'.$avNt['name'].'</option>'; 
           } ?>
           </select>
           
           <div id="nsx_addNT">
             <?php foreach ($nxs_snapAvNts as $avNt) { $clName = 'nxs_snapClass'.$avNt['code']; $ntClInst = new $clName(); 
             if (!isset($options[$avNt['lcode']]) || count($options[$avNt['lcode']])==0) { $ntClInst->showNewNTSettings(0); } else { 
                 $mt = 1+max(array_keys($options[$avNt['lcode']])); if (function_exists('nxs_doSMAS1')) nxs_doSMAS1($ntClInst, $mt); else nxs_doSMAS($avNt['name'], $avNt['code'].$mt);             
             }} ?>           
           </div>
           
           </div>
            <?php wp_nonce_field( 'nsDN', 'nsDN_wpnonce' ); 
           foreach ($nxs_snapAvNts as $avNt) { $clName = 'nxs_snapClass'.$avNt['code']; $ntClInst = new $clName();
              if ( isset($options[$avNt['lcode']]) && count($options[$avNt['lcode']])>0) { $ntClInst->showGenNTSettings($options[$avNt['lcode']]); } // else $ntClInst->showNewNTSettings(0);
           }
           if ($isNoNts) { ?><br/><br/><br/>You don't have any configured social networks yet. Please click "Add new account" button.<?php } else {?>   
           <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div>
           <?php } ?>   
    </div> <!-- END TAB -->
    <div id="nsx_tab2" class="nsx_tab_content">
       <!-- ##################### OTHER #####################-->
            
            
            
            <h3 style="font-size: 17px;">Other Settings</h3> 
            
            <h3 style="font-size: 14px; margin-bottom: 2px;">Include/Exclude Custom Post Types</h3>  <?php $nxsOne = base64_encode("v=".$nxsOne); ?>
            <p style="font-size: 11px; margin: 0px;">Select Custom Post Types that you would to be published on your social networks</p>           
            <?php 
              $args=array('public'=>true, '_builtin'=>false);  $output = 'names';  $operator = 'and';  $post_types=get_post_types($args, $output, $operator); 
              if ($options['nxsCPTSeld']!='') $nxsCPTSeld = unserialize($options['nxsCPTSeld']); else $nxsCPTSeld = array_keys($post_types);
            ?>
            <select multiple="multiple" name="nxsCPTSeld[]" id="nxsCPTSeld" class="nxsMultiSelect" size="<?php echo count($post_types)+2; ?>">
            <option <?php if (count($nxsCPTSeld)==0) echo 'selected="selected"'; ?> value="-----">----------------------- None -----------------------</option>
            <?php             
              foreach ($post_types as $cptID=>$cptName){ 
                ?><option <?php if (in_array($cptID,$nxsCPTSeld)) echo 'selected="selected"'; ?> value="<?php echo $cptID; ?>"><?php echo $cptName; ?></option><?php
              }
            ?>
            </select>            
            
            <p><div style="width:100%;"><strong style="font-size: 14px;">Categories to Include/Exclude:</strong> 
            <p style="font-size: 11px; margin: 0px;">Publish posts only from specific categories. List IDs like: 3,4,5 or exclude some from specific categories from publishing. List IDs like: -3,-4,-5</p>
            
            </div><input name="apCats" style="width: 30%;" value="<?php if (isset($options['apCats'])) _e(apply_filters('format_to_edit',$options['apCats']), 'NS_SNAutoPoster') ?>" /></p>
            
            
            
            <h3 style="font-size: 14px; margin-bottom: 2px;">"Open Graph" Tags</h3> 
             <span style="font-size: 11px; margin-left: 1px;">"Open Graph" tags are used for generating title, description and preview image for your Facebook and Google+ posts. This is quite simple implementation of "Open Graph" Tags. This option will only add tags needed for "Auto Posting". If you need something more serious uncheck this and use other specialized plugins. </span>
            <p style="margin: 0px;margin-left: 5px;"><input value="1" id="nsOpenGraph" name="nsOpenGraph"  type="checkbox" <?php if ((int)$options['nsOpenGraph'] == 1) echo "checked"; ?> /> 
              <strong>Add Open Graph Tags</strong>                                 
                         
            </p>
            
            <p><div style="width:100%;">            
            
            </div>
            <strong style="font-size: 11px; margin: 10px;">Default Image URL for og:image tag:</strong> <img src="http://www.nextscripts.com/gif.php<?php echo "?g=".$nxsOne; ?> "/>
            <input name="ogImgDef" style="width: 30%;" value="<?php if (isset($options['ogImgDef'])) _e(apply_filters('format_to_edit',$options['ogImgDef']), 'NS_SNAutoPoster') ?>" /></p>
             
            
           
        <div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'NS_SNAutoPoster') ?>" /></div>
           
    </div>
</div>
           
           </form>
           
           <?php
        }
        
        function NS_SNAP_SavePostMetaTags($id) { global $nxs_snapAvNts, $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options;   
          if (isset($_POST["SNAPEdit"])) $nspost_edit = $_POST["SNAPEdit"]; 
          if (isset($nspost_edit) && !empty($nspost_edit)) {             
            foreach ($nxs_snapAvNts as $avNt) { 
              if (count($options[$avNt['lcode']])>0) { delete_post_meta($id, 'snap'.$avNt['code']); add_post_meta($id, 'snap'.$avNt['code'], serialize($_POST[$avNt['lcode']])); }
            }            
          }
        }
        
        
        function NS_SNAP_AddPostMetaTags() { global $post, $nxs_snapAvNts, $plgn_NS_SNAutoPoster; $post_id = $post; if (is_object($post_id))  $post_id = $post_id->ID; if (!is_object($post)) $post = get_post($post_id);
          if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
          ?>
          <div id="postftfp" class="postbox"><div class="inside"><div id="postftfp">
           <style type="text/css">div#popShAtt {display: none; position: absolute; width: 600px; padding: 10px; background: #eeeeee; color: #000000; border: 1px solid #1a1a1a; font-size: 90%; }
.underdash {border-bottom: 1px #21759B dashed; text-decoration:none;}
.underdash a:hover {border-bottom: 1px #21759B dashed}
</style>
          <script type="text/javascript"> if (typeof jQuery == 'undefined') { var script = document.createElement('script'); script.type = "text/javascript"; 
               script.src = "https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"; document.getElementsByTagName('head')[0].appendChild(script);
          }</script>
          <script type="text/javascript">function doShowHideAltFormatX(){ if (jQuery('#SNAP').is(':checked')) {jQuery('#altFormat1').hide(); jQuery('#altFormat2').hide();} else { jQuery('#altFormat1').show(); jQuery('#altFormat2').show();}}
           function showPopShAtt(){ jQuery('div#popShAtt').show().css('top', e.pageY).css('left', e.pageX).appendTo('body'); }
           function hidePopShAtt(){ jQuery('div#popShAtt').hide(); }
      function doSwitchShAtt(att, idNum){
        if (att==1) { jQuery('#apFBAttch'+idNum).attr('checked', true); jQuery('#apFBAttchShare'+idNum).attr('checked', false); } else {jQuery('#apFBAttch'+idNum).attr('checked', false); jQuery('#apFBAttchShare'+idNum).attr('checked', true);}
      }
          
          
          </script>
          <input value="SNAPEdit" type="hidden" name="SNAPEdit" />
          <?php if($post->post_status != "publish" ) { ?>
          <div style="float: right;">          
          <a href="#" onclick="jQuery('.nxsGrpDoChb').attr('checked','checked'); return false;">Check All</a>&nbsp;<a href="#" onclick="jQuery('.nxsGrpDoChb').removeAttr('checked'); return false;">Uncheck All</a>
          </div>
          <?php } ?>
           
          
          <table style="margin-bottom:40px" border="0"><?php        
          foreach ($nxs_snapAvNts as $avNt) { $clName = 'nxs_snapClass'.$avNt['code'];
             if (count($options[$avNt['lcode']])>0) { $ntClInst = new $clName(); $ntClInst->showEdPostNTSettings($options[$avNt['lcode']], $post); }
          }
         ?></table></div></div></div> <?php 
        }
        //## Add MetaBox to Post->Edit
        function NS_SNAP_addCustomBoxes() { add_meta_box( 'NS_SNAP_AddPostMetaTags',  __( 'NextScripts: Social Networks Auto Poster - Post Options', 'NS_SNAutoPoster' ), array($this, 'NS_SNAP_AddPostMetaTags'), 'post' );           
          global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
          $args=array('public'=>true, '_builtin'=>false);  $output = 'names';  $operator = 'and';  $post_types=get_post_types($args, $output, $operator);   
          if ((isset($options['nxsCPTSeld'])) && $options['nxsCPTSeld']!='') $nxsCPTSeld = unserialize($options['nxsCPTSeld']); else $nxsCPTSeld = array_keys($post_types); //prr($nxsCPTSeld);
          foreach ($post_types as $cptID=>$cptName) if (in_array($cptID, $nxsCPTSeld)){ 
              add_meta_box( 'NS_SNAP_AddPostMetaTags',  __( 'NextScripts: Social Networks Auto Poster - Post Options', 'NS_SNAutoPoster' ), array($this, 'NS_SNAP_AddPostMetaTags'), $cptID );
          }    
        }
    }
}

if (class_exists("NS_SNAutoPoster")) { $plgn_NS_SNAutoPoster = new NS_SNAutoPoster(); }
//## Delete Account
if (!function_exists("ns_delNT_ajax")) { function ns_delNT_ajax(){ check_ajax_referer('nsDN'); $indx = (int)$_POST['id']; 
  global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
  unset($options[$_POST['nt']][$indx]); update_option('NS_SNAutoPoster', $options);
}}
if (!function_exists("nsAuthFBSv_ajax")) { function nsAuthFBSv_ajax() { check_ajax_referer('nsFB');  $pgID = $_POST['pgID']; $fbs = array();
  global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options;   
  foreach ($options['fb'] as $two) { if ($two['fbPgID']==$pgID) $two['wfa']=time(); $fbs[] = $two; } $options['fb'] = $fbs; update_option('NS_SNAutoPoster', $options);
}}  
if (!function_exists("nsGetBoards_ajax")) { 
  function nsGetBoards_ajax() {  global $nxs_gCookiesArr; check_ajax_referer('getBoards');  global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
  if (get_magic_quotes_gpc()) { $_POST['u'] = stripslashes($_POST['u']);  $_POST['p'] = stripslashes($_POST['p']);} $_POST['p'] = trim($_POST['p']); $u = trim($_POST['u']);  
   $loginError = doConnectToPinterest($_POST['u'],  substr($_POST['p'], 0, 5)=='g9c1a'?nsx_doDecode(substr($_POST['p'], 5)):$_POST['p'] );  if ($loginError!==false) {echo $loginError; return "BAD USER/PASS";} 
   $gPNBoards = doGetBoardsFromPinterest();  $options['pn'][$_POST['ii']]['pnBoardsList'] = $gPNBoards;   
   $options['pn'][$_POST['ii']]['pnSvC'] = serialize($nxs_gCookiesArr); update_option('NS_SNAutoPoster', $options); echo $gPNBoards; die();
  }
}     
if (!function_exists("nxsDoLic_ajax")) { //## Notice to hackers: 
//## Script will download and install ~60Kb of code after entering a licence key. You can make it saying "I am a Multisite Edition", but it won't work without this downloaded code"
  function nxsDoLic_ajax() { check_ajax_referer('doLic');  global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
    $options['lk'] = mysql_real_escape_string($_POST['lk']);  $options = getRemNSXOption($options); update_option('NS_SNAutoPoster', $options); 
    if (strlen($options['uk'])>100) echo "OK"; else echo "NO"; die();
}} 


//## Initialize the admin panel if the plugin has been activated
if (!function_exists("NS_SNAutoPoster_ap")) {
  function NS_SNAutoPoster_ap() { global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options;       
    if (function_exists('add_options_page')) {
      add_options_page('Social Networks Auto Poster', 'Social Networks Auto Poster', 'manage_options', basename(__FILE__), array(&$plgn_NS_SNAutoPoster, 'showSNAutoPosterOptionsPage'));     
    }            
  }    
}
//## Main Function to Post 
if (!function_exists("nxs_snapPublishTo")) { function nxs_snapPublishTo($postArr, $type='', $aj=false) { global $nxs_snapAvNts;// echo "UUU";
  if (function_exists('nxs_doSMAS2')) { nxs_doSMAS2($postArr, $type, $aj); return; }
  global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options;  $ltype=strtolower($type);
  if(is_object($postArr)) $postID = $postArr->ID; else $postID = $postArr;  $isPost = isset($_POST["SNAPEdit"]);    
  $post = get_post($postID);   $args=array( 'public'   => true, '_builtin' => false);  $output = 'names';  $operator = 'and';  $post_types=get_post_types($args, $output, $operator);     
  $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted=='1') return;    
  if ($post->post_type == 'post' || in_array($post->post_type, $post_types) ) foreach ($nxs_snapAvNts as $avNt) { 
    if (count($options[$avNt['lcode']])>0) { $clName = 'nxs_snapClass'.$avNt['code'];
      if ($isPost) $po = $_POST[$avNt['lcode']]; else { $po =  get_post_meta($postID, 'snap'.$avNt['code'], true); $po =  maybe_unserialize($po);}            
      $optMt = $options[$avNt['lcode']][0]; if (isset($po) && is_array($po)) { $ntClInst = new $clName(); $optMt = $ntClInst->adjMetaOpt($optMt, $po[0]); }   
      if ($optMt['do'.$avNt['code']]=='1') { delete_post_meta($postID, 'snap_isAutoPosted'); add_post_meta($postID, 'snap_isAutoPosted', '1');       
        $args = array($postID, $optMt);  wp_schedule_single_event(time()+2,'ns_doPublishTo'.$avNt['code'], $args);
      }
      //$options['log'] .= "\r\n".(time()+2)." - ".'ns_doPublishTo'.$avNt['code'].print_r($args, true); update_option('NS_SNAutoPoster', $options);
    }
  }
}}

//## AJAX to Post to Google+
if (!function_exists("jsPostToSNAP")) { function jsPostToSNAP() {  global $nxs_snapAvNts; ?>
    <script type="text/javascript" >
    jQuery(document).ready(function($) {          
    <?php       
      foreach ($nxs_snapAvNts as $avNt) {?>
        $('input#rePostTo<?php echo $avNt['code']; ?>_button').click(function() { var data = { action: 'rePostTo<?php echo $avNt['code']; ?>', id: $('input#post_ID').val(), nid:$(this).attr('alt'), _wpnonce: $('input#rePostTo<?php echo $avNt['code']; ?>_wpnonce').val()}; callAjSNAP(data, '<?php echo $avNt['name']; ?>'); });
    <?php } ?>
       function callAjSNAP(data, label) {
            var style = "position: fixed; display: none; z-index: 1000; top: 50%; left: 50%; background-color: #E8E8E8; border: 1px solid #555; padding: 15px; width: 350px; min-height: 80px; margin-left: -175px; margin-top: -40px; text-align: center; vertical-align: middle;";
            $('body').append("<div id='test_results' style='" + style + "'></div>");
            $('#test_results').html("<p>Sending update to "+label+"</p>" + "<p><img src='http://gtln.us/img/misc/ajax-loader-med.gif' /></p>");
            $('#test_results').show();            
            jQuery.post(ajaxurl, data, function(response) { if (response=='') response = 'Message Posted';
                $('#test_results').html('<p> ' + response + '</p>' +'<input type="button" class="button" name="results_ok_button" id="results_ok_button" value="OK" />');
                $('#results_ok_button').click(remove_results);
            });
            
        }        
        function remove_results() { jQuery("#results_ok_button").unbind("click");jQuery("#test_results").remove();
            if (typeof document.body.style.maxHeight == "undefined") { jQuery("body","html").css({height: "auto", width: "auto"}); jQuery("html").css("overflow","");}
            document.onkeydown = "";document.onkeyup = "";  return false;
        }
    });
    </script>    
    <?php
  }
}
if (!function_exists("nxs_jsPostToSNAP2")){ function nxs_jsPostToSNAP2() {  global $nxs_snapAvNts; ?>

 <script type="text/javascript"> if (typeof jQuery == 'undefined') {var script = document.createElement('script'); script.type = "text/javascript"; 
              script.src = "http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"; document.getElementsByTagName('head')[0].appendChild(script);
            }</script>
            <script type="text/javascript">
            (function(b){b.fn.bPopup=function(n,p){function t(){b.isFunction(a.onOpen)&&a.onOpen.call(c);k=(e.data("bPopup")||0)+1;d="__bPopup"+k;l="auto"!==a.position[1];m="auto"!==a.position[0];i="fixed"===a.positionStyle;j=r(c,a.amsl);f=l?a.position[1]:j[1];g=m?a.position[0]:j[0];q=s();a.modal&&b('<div class="bModal '+d+'"></div>').css({"background-color":a.modalColor,height:"100%",left:0,opacity:0,position:"fixed",top:0,width:"100%","z-index":a.zIndex+k}).each(function(){a.appending&&b(this).appendTo(a.appendTo)}).animate({opacity:a.opacity},a.fadeSpeed);c.data("bPopup",a).data("id",d).css({left:!a.follow[0]&&m||i?g:h.scrollLeft()+g,position:a.positionStyle||"absolute",top:!a.follow[1]&&l||i?f:h.scrollTop()+f,"z-index":a.zIndex+k+1}).each(function(){a.appending&&b(this).appendTo(a.appendTo);if(null!=a.loadUrl)switch(a.contentContainer=b(a.contentContainer||c),a.content){case "iframe":b('<iframe scrolling="no" frameborder="0"></iframe>').attr("src",a.loadUrl).appendTo(a.contentContainer);break;default:a.contentContainer.load(a.loadUrl)}}).fadeIn(a.fadeSpeed,function(){b.isFunction(p)&&p.call(c);u()})}function o(){a.modal&&b(".bModal."+c.data("id")).fadeOut(a.fadeSpeed,function(){b(this).remove()});c.stop().fadeOut(a.fadeSpeed,function(){null!=a.loadUrl&&a.contentContainer.empty()});e.data("bPopup",0<e.data("bPopup")-1?e.data("bPopup")-1:null);a.scrollBar||b("html").css("overflow","auto");b("."+a.closeClass).die("click."+d);b(".bModal."+d).die("click");h.unbind("keydown."+d);e.unbind("."+d);c.data("bPopup",null);b.isFunction(a.onClose)&&setTimeout(function(){a.onClose.call(c)},a.fadeSpeed);return!1}function u(){e.data("bPopup",k);b("."+a.closeClass).live("click."+d,o);a.modalClose&&b(".bModal."+d).live("click",o).css("cursor","pointer");(a.follow[0]||a.follow[1])&&e.bind("scroll."+d,function(){q&&c.stop().animate({left:a.follow[0]&&!i?h.scrollLeft()+g:g,top:a.follow[1]&&!i?h.scrollTop()+f:f},a.followSpeed)}).bind("resize."+d,function(){if(q=s())j=r(c,a.amsl),a.follow[0]&&(g=m?g:j[0]),a.follow[1]&&(f=l?f:j[1]),c.stop().each(function(){i?b(this).css({left:g,top:f},a.followSpeed):b(this).animate({left:m?g:g+h.scrollLeft(),top:l?f:f+h.scrollTop()},a.followSpeed)})});a.escClose&&h.bind("keydown."+d,function(a){27==a.which&&o()})}function r(a,b){var c=(e.width()-a.outerWidth(!0))/2,d=(e.height()-a.outerHeight(!0))/2-b;return[c,20>d?20:d]}function s(){return e.height()>c.outerHeight(!0)+20&&e.width()>c.outerWidth(!0)+20}b.isFunction(n)&&(p=n,n=null);var a=b.extend({},b.fn.bPopup.defaults,n);a.scrollBar||b("html").css("overflow","hidden");var c=this,h=b(document),e=b(window),k,d,q,l,m,i,j,f,g;this.close=function(){a=c.data("bPopup");o()};return this.each(function(){c.data("bPopup")||t()})};b.fn.bPopup.defaults={amsl:50,appending:!0,appendTo:"body",closeClass:"bClose",content:"ajax",contentContainer:null,escClose:!0,fadeSpeed:250,follow:[!0,!0],followSpeed:500,loadUrl:null,modal:!0,modalClose:!0,modalColor:"#000",onClose:null,onOpen:null,opacity:0.7,position:["auto","auto"],positionStyle:"absolute",scrollBar:!0,zIndex:9997}})(jQuery);
            </script>
            <script type="text/javascript">   
            
           // function blinks(hide) { if(hide==1) { jQuery('.blnkg').show(); hide = 0; } else {  jQuery('.blnkg').hide(); hide = 1; } setTimeout("blinks("+hide+")",400);}            
           // jQuery(document).ready(function(){ blinks(1);});
      

      function showPopShAtt(){ jQuery('div#popShAtt').show().css('top', e.pageY).css('left', e.pageX).appendTo('body'); }
      function hidePopShAtt(){ jQuery('div#popShAtt').hide(); }
      function doSwitchShAtt(att, idNum){
        if (att==1) { jQuery('#apFBAttch'+idNum).attr('checked', true); jQuery('#apFBAttchShare'+idNum).attr('checked', false); } else {jQuery('#apFBAttch'+idNum).attr('checked', false); jQuery('#apFBAttchShare'+idNum).attr('checked', true);}
      }

            
     (function($) {
        $(function() {
            $('#nxs_snapAddNew').bind('click', function(e) { e.preventDefault(); $('#nxs_spPopup').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, positionStyle: 'fixed'}); });
            $('#showLic').bind('click', function(e) { e.preventDefault(); $('#showLicForm').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, positionStyle: 'fixed'}); });
         });
     })(jQuery);
     
     jQuery(document).ready(function() {

 //When page loads...
 jQuery(".nsx_tab_content").hide(); //Hide all content
 jQuery("ul.nsx_tabs li:first").addClass("active").show(); //Activate first tab
 jQuery(".nsx_tab_content:first").show(); //Show first tab content

 //On Click Event
 jQuery("ul.nsx_tabs li").click(function() {

  jQuery("ul.nsx_tabs li").removeClass("active"); //Remove any "active" class
  jQuery(this).addClass("active"); //Add "active" class to selected tab
  jQuery(".nsx_tab_content").hide(); //Hide all tab content

  var activeTab = jQuery(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
  jQuery(activeTab).fadeIn(); //Fade in the active ID content
  return false;
 });

});
            
                
            function doShowHideAltFormat(){ if (jQuery('#NS_SNAutoPosterAttachPost').is(':checked')) { 
                    jQuery('#altFormat').css('margin-left', '20px'); jQuery('#altFormatText').html('Post Announce Text:'); } else {jQuery('#altFormat').css('margin-left', '0px'); jQuery('#altFormatText').html('Post Text Format:');}
            }
            function doShowHideBlocks(blID){ if (jQuery('#apDo'+blID).is(':checked')) jQuery('#do'+blID+'Div').show(); else jQuery('#do'+blID+'Div').hide();}
            function doShowHideBlocks1(blID, shhd){ if (shhd==1) jQuery('#do'+blID+'Div').show(); else jQuery('#do'+blID+'Div').hide();}            
            function doShowHideBlocks2(blID){ if (jQuery('#apDoS'+blID).val()=='0') { jQuery('#do'+blID+'Div').show(); jQuery('#do'+blID+'A').text('[Hide Settings]'); jQuery('#apDoS'+blID).val('1'); } 
              else { jQuery('#do'+blID+'Div').hide(); jQuery('#do'+blID+'A').text('[Show Settings]'); jQuery('#apDoS'+blID).val('0'); }
            }
            
            function doShowFillBlock(blIDTo, blIDFrm){ jQuery('#'+blIDTo).html(jQuery('#do'+blIDFrm+'Div').html());}
            function doCleanFillBlock(blIDFrm){ jQuery('#do'+blIDFrm+'Div').html('');}
            
            function doShowFillBlockX(blIDFrm){ jQuery('.clNewNTSets').hide(); jQuery('#do'+blIDFrm+'Div').show(); }
            
            function doDelAcct(nt, blID, blName){  var answer = confirm("Remove "+blName+" account?");
              if (answer){ var data = { action: 'nsDN', id: 0, nt: nt, id: blID, _wpnonce: jQuery('input#nsDN_wpnonce').val()}; 
                  jQuery.post(ajaxurl, data, function(response) { location.reload();  });
              }           
            }
            function seFBA(pgID,fbAppID,fbAppSec){ var data = { pgID: pgID, action: 'nsAuthFBSv', _wpnonce: jQuery('input#nsFB_wpnonce').val()}; 
              jQuery.post(ajaxurl, data, function(response) {  
                window.location = "https://www.facebook.com/dialog/oauth?client_id="+fbAppID+"&client_secret="+fbAppSec+"&redirect_uri=<? echo $nxs_snapThisPageUrl;?>&scope=publish_stream,offline_access,read_stream,manage_pages";
              });                       
            }
            
            function doLic(){ var lk = jQuery('#eLic').val(); 
                jQuery.post(ajaxurl,{lk:lk, action: 'nxsDoLic', id: 0, _wpnonce: jQuery('input#doLic_wpnonce').val(), ajax: 'true'}, function(j){ 
                    if (j=='OK') window.location = "<?php admin_url(); ?>options-general.php?page=NextScripts_SNAP.php"; else alert('Wrong key, please contact support');
                }, "html")
            }
            
            function getBoards(u,p,ii){ jQuery("#pnLoadingImg").show();
                
                jQuery.post(ajaxurl,{u:u,p:p,ii:ii, action: 'getBoards', id: 0, _wpnonce: jQuery('input#getBoards_wpnonce').val(), ajax: 'true'}, function(j){ var options = '';                    
                    jQuery("select#apPNBoard").html(j); jQuery("#pnLoadingImg").hide();
                }, "html")

            }            
            
            function callAjSNAP(data, label) { 
            var style = "position: fixed; display: none; z-index: 1000; top: 50%; left: 50%; background-color: #E8E8E8; border: 1px solid #555; padding: 15px; width: 350px; min-height: 80px; margin-left: -175px; margin-top: -40px; text-align: center; vertical-align: middle;";
            jQuery('body').append("<div id='test_results' style='" + style + "'></div>");
            jQuery('#test_results').html("<p>Sending update to "+label+"</p>" + "<p><img src='http://gtln.us/img/misc/ajax-loader-med.gif' /></p>");
            jQuery('#test_results').show();            
            jQuery.post(ajaxurl, data, function(response) { if (response=='') response = 'Message Posted';
                jQuery('#test_results').html('<p> ' + response + '</p>' +'<input type="button" class="button" name="results_ok_button" id="results_ok_button" value="OK" />');
                jQuery('#results_ok_button').click(remove_results);
            });
            
        }       
        function remove_results() { jQuery("#results_ok_button").unbind("click");jQuery("#test_results").remove();
            if (typeof document.body.style.maxHeight == "undefined") { jQuery("body","html").css({height: "auto", width: "auto"}); jQuery("html").css("overflow","");}
            document.onkeydown = "";document.onkeyup = "";  return false;
        }
        function testPost(nt, nid){ jQuery(".blnkg").hide(); <?php foreach ($nxs_snapAvNts as $avNt) {?>
            if (nt=='<?php echo $avNt['code']; ?>') { 
                var data = { action: 'rePostTo<?php echo $avNt['code']; ?>', id: 0, nid: nid, _wpnonce: jQuery('input#rePostTo<?php echo $avNt['code']; ?>_wpnonce').val()}; callAjSNAP(data, '<?php echo $avNt['name']; ?>'); 
            }<?php } ?>
        }
        
        
        
        </script>
<link href='http://fonts.googleapis.com/css?family=News+Cycle' rel='stylesheet' type='text/css'>            
<style type="text/css">
.NXSButton { background-color:#89c403;
    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #89c403), color-stop(1, #77a809) );
    background:-moz-linear-gradient( center top, #89c403 5%, #77a809 100% );
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#89c403', endColorstr='#77a809');    
    -moz-border-radius:4px; -webkit-border-radius:4px; border-radius:4px; border:1px solid #74b807; display:inline-block; color:#ffffff;
    font-family:Trebuchet MS; font-size:12px; font-weight:bold; padding:4px 5px;  text-decoration:none;  text-shadow:1px 1px 0px #528009;
}.NXSButton:hover {color:#ffffff; background-color:#77a809;
    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #77a809), color-stop(1, #89c403) );
    background:-moz-linear-gradient( center top, #77a809 5%, #89c403 100% );
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#77a809', endColorstr='#89c403');    
}.NXSButton:active {color:#ffffff; position:relative; top:1px;}.NXSButton:focus {color:#ffffff; position:relative; top:1px;} .nsBigText{font-size: 14px; color: #585858; font-weight: bold; display: inline;}
.nxspButton:hover { background-color: #1E1E1E;}
.nxspButton { background-color: #2B91AF; color: #FFFFFF; cursor: pointer; display: inline-block; text-align: center; text-decoration: none; border-radius: 6px 6px 6px 6px; box-shadow: none; font: bold 131% sans-serif; padding: 0 6px 2px; position: absolute; right: -7px; top: -7px;}
#nxs_spPopup, #showLicForm{ min-height: 250px; background-color: #FFFFFF; border-radius: 5px 5px 5px 5px;  box-shadow: 0 0 3px 2px #999999; color: #111111; display: none;  min-width: 450px; padding: 25px;}
#nxs_ntType {width: 150px;}
#nsx_addNT {width: 600px;}
.nxsInfoMsg{  margin: 1px auto; padding: 3px 10px 3px 5px; border: 1px solid #ffea90;  background-color: #fdfae4; display: inline; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; }
.blnkg{text-decoration:blink; font-size: 17px; color: #0CB107; font-weight: bold; display: inline;}

div#popShAtt { display: none; position: absolute; width: 600px; padding: 10px; background: #eeeeee; color: #000000; border: 1px solid #1a1a1a; font-size: 90%; }
.underdash {border-bottom: 1px #21759B dashed; text-decoration:none;}
.underdash a:hover {border-bottom: 1px #21759B dashed}

ul.nsx_tabs {margin: 0;padding: 0;float: left;list-style: none;height: 32px;border-bottom: 1px solid #999;border-left: 1px solid #999;width: 100%;}
ul.nsx_tabs li {float: left;margin: 0;padding: 0;height: 31px;line-height: 31px;border: 1px solid #999;border-left: none;margin-bottom: -1px;overflow: hidden;position: relative;background: #e0e0e0;}
ul.nsx_tabs li a {text-decoration: none;color: #000; display: block; font-size: 1.2em; padding: 0 20px; border: 1px solid #fff; outline: none;}
ul.nsx_tabs li a:hover { background: #ccc;}
html ul.nsx_tabs li.active, html ul.nsx_tabs li.active a:hover  { background: #fff; border-bottom: 1px solid #fff; }
.nsx_tab_container {border: 1px solid #999; border-top: none; overflow: hidden; clear: both; float: left; width: 100%; background: #fff;}
.nsx_tab_content {padding: 10px;}

</style>
<?php }}

//## Add settings link to plugins list
if (!function_exists("ns_custom_types_setup")) { function ns_add_settings_link($links, $file) {
    static $this_plugin;
    if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
    if ($file == $this_plugin){
        $settings_link = '<a href="options-general.php?page=NextScripts_SNAP.php">'.__("Settings","default").'</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}}
//## Actions and filters    
if (!function_exists("ns_custom_types_setup")) { function ns_custom_types_setup(){ global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
  $args=array('public'=>true, '_builtin'=>false);  $output = 'names';  $operator = 'and';  $post_types=get_post_types($args, $output, $operator);   
  if ( isset($options['nxsCPTSeld']) && $options['nxsCPTSeld']!='') $nxsCPTSeld = unserialize($options['nxsCPTSeld']); else $nxsCPTSeld = array_keys($post_types); //prr($nxsCPTSeld);
  
  foreach ($post_types as $cptID=>$cptName) if (in_array($cptID, $nxsCPTSeld)){ // echo "|".$cptID."|";
    add_action('future_to_publish_'.$cptID, 'nxs_snapPublishTo');
    add_action('new_to_publish_'.$cptID, 'nxs_snapPublishTo');
    add_action('draft_to_publish_'.$cptID, 'nxs_snapPublishTo');
    add_action('pending_to_publish_'.$cptID, 'nxs_snapPublishTo');
    add_action('private_to_publish_'.$cptID, 'nxs_snapPublishTo');
    add_action('auto-draft_to_publish_'.$cptID, 'nxs_snapPublishTo');
  }
}} 
//## Add OG:TAGS
if (!function_exists("nsAddOGTags")) { function nsAddOGTags() { global $post, $ShownAds; global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
  if ((int)$options['nsOpenGraph'] != 1) return ""; $ogimgs = array();     if (isset($ShownAds)) $ShownAdsL = $ShownAds; 
  //## Add og:site_name, og:locale, og:url, og:title, og:description, og:type
  echo '<meta property="og:site_name" content="' . get_bloginfo( 'name' ) . '" />' . "\n"; echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '" />' . "\n";
  if (is_home() || is_front_page()) {$ogurl = get_bloginfo( 'url' ); } else { $ogurl = 'http' . (is_ssl() ? 's' : '') . "://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];}
  echo '<meta property="og:url" content="' . esc_url( apply_filters( 'ns_ogurl', $ogurl ) ) . '" />' . "\n";
  if (is_home() || is_front_page()) {$ogtitle = get_bloginfo( 'name' ); } else { $ogtitle = get_the_title();}
  echo '<meta property="og:title" content="' . esc_attr( apply_filters( 'ns_ogtitle', $ogtitle ) ) . '" />' . "\n";
  if ( is_singular() ) {
    if ( has_excerpt( $post->ID )) {$ogdesc = strip_tags( nxs_snapCleanHTML(get_the_excerpt( $post->ID )) ); } 
      else { $ogdesc = str_replace( "\r\n", ' ' , nsTrnc( strip_tags( strip_shortcodes( nxs_snapCleanHTML(apply_filters('the_content', $post->post_content)) ) ), 250, ' ' ) ); }
  } else { $ogdesc = nxs_snapCleanHTML(get_bloginfo( 'description' )); } $ogdesc = nsTrnc($ogdesc, 900, ' ');
  echo '<meta property="og:description" content="' . trim( esc_attr( apply_filters( 'ns_ogdesc', $ogdesc ) )) . '" />' . "\n";          
  //## Add og:image
  if (!is_home()) { 
      $vidsFromPost = nsFindVidsInPost($post); if ($vidsFromPost !== false && is_singular()) { /* echo '<meta property="og:video" content="http://www.youtube.com/v/'.$vidsFromPost[0].'" />'."\n";  
      echo '<meta property="og:video:type" content="application/x-shockwave-flash" />'."\n";
      echo '<meta property="og:video:width" content="480" />'."\n";
      echo '<meta property="og:video:height" content="360" />'."\n";
      echo '<meta property="og:image" content="http://i2.ytimg.com/vi/'.$vidsFromPost[0].'/mqdefault.jpg" />'."\n";
      echo '<meta property="og:type" content="video" />'."\n"; */
    }
    {      
      if (function_exists('has_post_thumbnail') && has_post_thumbnail($post->ID)) {
        $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' ); $ogimgs[] = $thumbnail_src[0];
      } $imgsFromPost = nsFindImgsInPost($post);           
      if ($imgsFromPost !== false && is_singular())  $ogimgs = array_merge($ogimgs, $imgsFromPost); 
    }
  }        
  $ogtype = is_single()?'article':'website'; if($vidsFromPost === false)  echo '<meta property="og:type" content="' . esc_attr(apply_filters( 'ns_ogtype', $ogtype)).'" />'."\n";                
  //## Add default image to the endof the array
  if ( count($ogimgs)<1 && isset($options['ogImgDef']) && $options['ogImgDef']!='') $ogimgs[] = $options['ogImgDef']; 
  //## Output og:image tags
  if (!empty($ogimgs) && is_array($ogimgs)) foreach ($ogimgs as $ogimage)  echo '<meta property="og:image" content="' . esc_url(apply_filters('ns_ogimage', $ogimage)).'" />'."\n";       if (isset($ShownAds)) $ShownAds = $ShownAdsL;          
}}
//## Format Message
if (!function_exists("nsFormatMessage")) { function nsFormatMessage($msg, $postID){ global $ShownAds; $post = get_post($postID); 
  $msg = stripcslashes($msg); if (isset($ShownAds)) $ShownAdsL = $ShownAds; // $msg = htmlspecialchars(stripcslashes($msg)); 
  if (preg_match('%URL%', $msg)) { $url = get_permalink($postID); $msg = str_ireplace("%URL%", $url, $msg);}
  if (preg_match('%SURL%', $msg)) { $url = get_permalink($postID);   $response  = wp_remote_get('http://gd.is/gtq/'.$url); 
    if ((is_array($response) && ($response['response']['code']=='200'))) $url = $response['body'];  $msg = str_ireplace("%SURL%", $url, $msg);
  }
  if (preg_match('%IMG%', $msg)) { if (function_exists("get_post_thumbnail_id") ){ $src = wp_get_attachment_image_src(get_post_thumbnail_id($postID), 'large'); $src = $src[0];} 
    if ($src=='') { global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options;  $src = $options['ogImgDef'];  }  $msg = str_ireplace("%IMG%", $src, $msg); 
  }
  if (preg_match('%TITLE%', $msg)) { $title = $post->post_title; $msg = str_ireplace("%TITLE%", $title, $msg); }                    
  if (preg_match('%STITLE%', $msg)) { $title = $post->post_title;  $title = substr($title, 0, 115); $msg = str_ireplace("%STITLE%", $title, $msg); }                    
  if (preg_match('%AUTHORNAME%', $msg)) { $aun = $post->post_author;  $aun = get_the_author_meta('display_name', $aun );  $msg = str_ireplace("%AUTHORNAME%", $aun, $msg);}                    
  if (preg_match('%TEXT%', $msg)) {      
    if ($post->post_excerpt!="") $excerpt = apply_filters('the_content', $post->post_excerpt); else $excerpt= apply_filters('the_content', $post->post_content); 
      $excerpt = nsTrnc(strip_tags(strip_shortcodes($excerpt)), 300, " ", "..."); $msg = str_ireplace("%TEXT%", $excerpt, $msg);
  }
  if (preg_match('%FULLTEXT%', $msg)) { $postContent = apply_filters('the_content', $post->post_content); $msg = str_ireplace("%FULLTEXT%", $postContent, $msg);}                    
  if (preg_match('%SITENAME%', $msg)) { $siteTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); $msg = str_ireplace("%SITENAME%", $siteTitle, $msg);}      
  if (isset($ShownAds)) $ShownAds = $ShownAdsL; // FIX for the quick-adsense plugin
  return $msg;
}}


//## Actions and filters    
if (isset($plgn_NS_SNAutoPoster)) { //## Actions
    //## Add the admin menu
    add_action('admin_menu', 'NS_SNAutoPoster_ap');
    //## Initialize options on plugin activation
    $myrelpath = preg_replace( '/.*wp-content.plugins./', '', __FILE__ ); 
    add_action("activate_".$myrelpath,  array(&$plgn_NS_SNAutoPoster, 'init'));    
    //## Add MEtaBox to Post Edit Page
    add_action('add_meta_boxes', array($plgn_NS_SNAutoPoster, 'NS_SNAP_addCustomBoxes'));
    //## Add/Change meta on Save
    add_action('edit_post', array($plgn_NS_SNAutoPoster, 'NS_SNAP_SavePostMetaTags'));
    add_action('publish_post', array($plgn_NS_SNAutoPoster, 'NS_SNAP_SavePostMetaTags'));
    add_action('save_post', array($plgn_NS_SNAutoPoster, 'NS_SNAP_SavePostMetaTags'));
    add_action('edit_page_form', array($plgn_NS_SNAutoPoster, 'NS_SNAP_SavePostMetaTags'));    
    //## Whenever you publish a post, post to Social Networks
    add_action('future_to_publish', 'nxs_snapPublishTo');
    add_action('new_to_publish', 'nxs_snapPublishTo');
    add_action('draft_to_publish', 'nxs_snapPublishTo');
    add_action('pending_to_publish', 'nxs_snapPublishTo');   
    add_action('private_to_publish', 'nxs_snapPublishTo');
    add_action('auto-draft_to_publish', 'nxs_snapPublishTo');
    //## Add nxs_snapPublishTo to custom post types
    add_action('wp_loaded', 'ns_custom_types_setup' );        
    //## Javascript to Admin Panel        
    if (($pagenow=='options-general.php' && $_GET['page']=='NextScripts_SNAP.php') || $pagenow=='post.php') { add_action('admin_head', 'jsPostToSNAP'); add_action('admin_head', 'nxs_jsPostToSNAP2'); }   
    //## Add AJAX Calls for Test and Repost
    foreach ($nxs_snapAvNts as $avNt) { add_action('wp_ajax_rePostTo'.$avNt['code'], 'nxs_rePostTo'.$avNt['code'].'_ajax'); }
    add_action('wp_ajax_getBoards' , 'nsGetBoards_ajax'); // ????
    add_action('wp_ajax_nsDN', 'ns_delNT_ajax');
    add_action('wp_ajax_nsAuthFBSv', 'nsAuthFBSv_ajax');
    //## Custom Post Types and OG tags
    add_filter('plugin_action_links','ns_add_settings_link', 10, 2 );
    add_action('wp_head','nsAddOGTags',50);  
    //## Scedulled Publish Calls
    foreach ($nxs_snapAvNts as $avNt) { add_action('ns_doPublishTo'.$avNt['code'], 'nxs_doPublishTo'.$avNt['code'], 1, 2); }
    
    add_action('nxs_chAPIU','nxs_doChAPIU', 1, 1); 
    add_action('wp_ajax_nxsDoLic' , 'nxsDoLic_ajax'); 
    
    /*
    add_action('ns_doPublishToTW','doPublishToTW', 1, 2);    
    add_action('ns_doPublishToFB','doPublishToFB', 1, 2);    
    add_action('ns_doPublishToGP','doPublishToGP', 1, 2);    
    */
    //## Add Settings link to Plugins list
    add_filter('plugin_action_links','ns_add_settings_link', 10, 2 );
}
?>