<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAvNts[] = array('code'=>'SU', 'lcode'=>'su', 'name'=>'StumbleUpon');

if (!class_exists("nxs_snapClassSU")) { class nxs_snapClassSU {
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){  global $nxs_plurl; $ntInfo = array('code'=>'SU', 'lcode'=>'su', 'name'=>'StumbleUpon', 'defNName'=>'suUName', 'tstReq' => false); ?>    
    <div class="nxs_box">
      <div class="nxs_box_header"> 
        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>
          <?php $cbo = count($ntOpts); ?> <?php wp_nonce_field( 'ns'.$ntInfo['code'], 'ns'.$ntInfo['code'].'_wpnonce' ); ?>
          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>
        </div>
      </div>
      <div class="nxs_box_inside">
        <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = $pbo[$ntInfo['defNName']]; ?>
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
  function showNewNTSettings($mgpo){ $options = array('nName'=>'', 'doSU'=>'1', 'suUName'=>'', 'suInclTags'=>'1', 'suAttch'=>'', 'suPass'=>''); $this->showNTSettings($mgpo, $options, true);}
  
  function suCats() { return '<option value="A.I.">A.I.</option><option value="AIDS">AIDS</option><option value="Accounting">Accounting</option><option value="Acting">Acting</option><option value="Action Movies">Action Movies</option><option value="Activism">Activism</option><option value="Adult Humor">Adult Humor</option><option value="Advertising">Advertising</option><option value="Africa">Africa</option><option value="African Americans">African Americans</option><option value="Aging">Aging</option><option value="Agriculture">Agriculture</option><option value="Alcoholic Drinks">Alcoholic Drinks</option><option value="Alternative Energy">Alternative Energy</option><option value="Alternative Health">Alternative Health</option><option value="Alternative News">Alternative News</option><option value="Alternative Rock">Alternative Rock</option><option value="Amateur Radio">Amateur Radio</option><option value="Ambient Music">Ambient Music</option><option value="American Football">American Football</option><option value="American History">American History</option><option value="American Lit.">American Lit.</option><option value="Anarchism">Anarchism</option><option value="Anatomy">Anatomy</option><option value="Ancient History">Ancient History</option><option value="Animals">Animals</option><option value="Animation">Animation</option><option value="Anime">Anime</option><option value="Anthropology">Anthropology</option><option value="Antiaging">Antiaging</option><option value="Antiques">Antiques</option><option value="Archaeology">Archaeology</option><option value="Architecture">Architecture</option><option value="Art History">Art History</option><option value="Arthritis">Arthritis</option><option value="Arts">Arts</option><option value="Asia">Asia</option><option value="Asthma">Asthma</option><option value="Astrology/Psychics">Astrology/Psychics</option><option value="Astronomy">Astronomy</option><option value="Atheist/Agnostic">Atheist/Agnostic</option><option value="Audio Equipment">Audio Equipment</option><option value="Australia">Australia</option><option value="Aviation/Aerospace">Aviation/Aerospace</option><option value="BDSM">BDSM</option><option value="Babes">Babes</option><option value="Babies">Babies</option><option value="Badminton">Badminton</option><option value="Ballet">Ballet</option><option value="Banking">Banking</option><option value="Bargains/Coupons">Bargains/Coupons</option><option value="Baseball">Baseball</option><option value="Basketball">Basketball</option><option value="Beauty">Beauty</option><option value="Beer">Beer</option><option value="Beverages">Beverages</option><option value="Bicycling">Bicycling</option><option value="Billiards">Billiards</option><option value="Biographies">Biographies</option><option value="Biology">Biology</option><option value="Biomechanics">Biomechanics</option><option value="Biotech">Biotech</option><option value="Bird Watching">Bird Watching</option><option value="Birds">Birds</option><option value="Bisexual Culture">Bisexual Culture</option><option value="Bisexual Sex">Bisexual Sex</option><option value="Bizarre/Oddities">Bizarre/Oddities</option><option value="Blues music">Blues music</option><option value="Board Games">Board Games</option><option value="Boating">Boating</option><option value="Bodybuilding">Bodybuilding</option><option value="Books">Books</option><option value="Botany">Botany</option><option value="Bowling">Bowling</option><option value="Boxing">Boxing</option><option value="Brain Disorders">Brain Disorders</option><option value="Brazil">Brazil</option><option value="British Literature">British Literature</option><option value="Britpop">Britpop</option><option value="Buddhism">Buddhism</option><option value="Business">Business</option><option value="C.A.D.">C.A.D.</option><option value="Camping">Camping</option><option value="Canada">Canada</option><option value="Cancer">Cancer</option><option value="Canoeing/Kayaking">Canoeing/Kayaking</option><option value="Capitalism">Capitalism</option><option value="Car Parts">Car Parts</option><option value="Card Games">Card Games</option><option value="Career planning">Career planning</option><option value="Caribbean">Caribbean</option><option value="Cars">Cars</option><option value="Cartoons">Cartoons</option><option value="Catholic">Catholic</option><option value="Cats">Cats</option><option value="Celebrities">Celebrities</option><option value="Cell Phones">Cell Phones</option><option value="Celtic Music">Celtic Music</option><option value="Central America">Central America</option><option value="Chaos/Complexity">Chaos/Complexity</option><option value="Cheerleading">Cheerleading</option><option value="Chemical Eng.">Chemical Eng.</option><option value="Chemistry">Chemistry</option><option value="Chess">Chess</option><option value="Children\'s Books">Children\'s Books</option><option value="China">China</option><option value="Christian Music">Christian Music</option><option value="Christianity">Christianity</option><option value="Christmas">Christmas</option><option value="Cigars">Cigars</option><option value="Civil Engineering">Civil Engineering</option><option value="Classic Films">Classic Films</option><option value="Classic Rock">Classic Rock</option><option value="Classical Music">Classical Music</option><option value="Classical Studies">Classical Studies</option><option value="Climbing">Climbing</option><option value="Clothing">Clothing</option><option value="Coffee">Coffee</option><option value="Cognitive Science">Cognitive Science</option><option value="Cold War">Cold War</option><option value="Collecting">Collecting</option><option value="Comedy Movies">Comedy Movies</option><option value="Comic Books">Comic Books</option><option value="Communism">Communism</option><option value="Computer Graphics">Computer Graphics</option><option value="Computer Hardware">Computer Hardware</option><option value="Computer Science">Computer Science</option><option value="Computer Security">Computer Security</option><option value="Computers">Computers</option><option value="Conservative Politics">Conservative Politics</option><option value="Conspiracies">Conspiracies</option><option value="Construction">Construction</option><option value="Consumer Info">Consumer Info</option><option value="Continuing Education">Continuing Education</option><option value="Counterculture">Counterculture</option><option value="Country music">Country music</option><option value="Crafts">Crafts</option><option value="Cricket">Cricket</option><option value="Crime">Crime</option><option value="Crochet">Crochet</option><option value="Cult Films">Cult Films</option><option value="Culture/Ethnicity">Culture/Ethnicity</option><option value="Cyberculture">Cyberculture</option><option value="DJ\'s/Mixing">DJ\'s/Mixing</option><option value="Dance Music">Dance Music</option><option value="Dancing">Dancing</option><option value="Databases">Databases</option><option value="Dating Tips">Dating Tips</option><option value="Daytrading">Daytrading</option><option value="Dentistry">Dentistry</option><option value="Design">Design</option><option value="Desktop Publishing">Desktop Publishing</option><option value="Diabetes">Diabetes</option><option value="Disabilities">Disabilities</option><option value="Disco">Disco</option><option value="Divorce">Divorce</option><option value="Doctors/Surgeons">Doctors/Surgeons</option><option value="Dogs">Dogs</option><option value="Dolls/Puppets">Dolls/Puppets</option><option value="Drama Movies">Drama Movies</option><option value="Drawing">Drawing</option><option value="Drugs">Drugs</option><option value="Drum\'n\'Bass">Drum\'n\'Bass</option><option value="Eastern Studies">Eastern Studies</option><option value="Eating Disorders">Eating Disorders</option><option value="Ecology">Ecology</option><option value="Ecommerce">Ecommerce</option><option value="Economics">Economics</option><option value="Education">Education</option><option value="Electrical Eng.">Electrical Eng.</option><option value="Electronic Devices">Electronic Devices</option><option value="Electronic Parts">Electronic Parts</option><option value="Electronica/IDM">Electronica/IDM</option><option value="Embedded Systems">Embedded Systems</option><option value="Encryption">Encryption</option><option value="Energy Industry">Energy Industry</option><option value="Entertaining Guests">Entertaining Guests</option><option value="Entrepreneurship">Entrepreneurship</option><option value="Environment">Environment</option><option value="Equestrian/Horses">Equestrian/Horses</option><option value="Ergonomics">Ergonomics</option><option value="Erotic Literature">Erotic Literature</option><option value="Ethics">Ethics</option><option value="Ethnic Music">Ethnic Music</option><option value="Europe">Europe</option><option value="Evolution">Evolution</option><option value="Exotic Pets">Exotic Pets</option><option value="Extreme Sports">Extreme Sports</option><option value="Facebook">Facebook</option><option value="Family">Family</option><option value="Fantasy Books">Fantasy Books</option><option value="Fashion">Fashion</option><option value="Feminism">Feminism</option><option value="Fetish Sexuality">Fetish Sexuality</option><option value="Figure Skating">Figure Skating</option><option value="Film Noir">Film Noir</option><option value="Filmmaking">Filmmaking</option><option value="Financial planning">Financial planning</option><option value="Fine Arts">Fine Arts</option><option value="Firefox">Firefox</option><option value="Fish">Fish</option><option value="Fishing">Fishing</option><option value="Fitness">Fitness</option><option value="Flyfishing">Flyfishing</option><option value="Folk music">Folk music</option><option value="Food/Cooking">Food/Cooking</option><option value="For Kids">For Kids</option><option value="Foreign Films">Foreign Films</option><option value="Forensics">Forensics</option><option value="Forestry">Forestry</option><option value="Forums">Forums</option><option value="France">France</option><option value="Funk">Funk</option><option value="Futurism">Futurism</option><option value="Gadgets">Gadgets</option><option value="Gambling">Gambling</option><option value="Gardening">Gardening</option><option value="Gay Culture">Gay Culture</option><option value="Gay Sex">Gay Sex</option><option value="Genealogy">Genealogy</option><option value="Genetics">Genetics</option><option value="Geography">Geography</option><option value="Geoscience">Geoscience</option><option value="Germany">Germany</option><option value="Glaucoma">Glaucoma</option><option value="Golf">Golf</option><option value="Gospel music">Gospel music</option><option value="Goth Culture">Goth Culture</option><option value="Government">Government</option><option value="Graphic Design">Graphic Design</option><option value="Guitar">Guitar</option><option value="Guns">Guns</option><option value="Gymnastics">Gymnastics</option><option value="Hacking">Hacking</option><option value="Health">Health</option><option value="Heart Conditions">Heart Conditions</option><option value="Heavy metal">Heavy metal</option><option value="Hedonism">Hedonism</option><option value="Hentai Anime">Hentai Anime</option><option value="Hiking">Hiking</option><option value="Hinduism">Hinduism</option><option value="HipHop/Rap">HipHop/Rap</option><option value="History">History</option><option value="Hockey">Hockey</option><option value="Home Business">Home Business</option><option value="Home Improvement">Home Improvement</option><option value="Homebrewing">Homebrewing</option><option value="Homemaking">Homemaking</option><option value="Homeschooling">Homeschooling</option><option value="Horror Movies">Horror Movies</option><option value="Hotels">Hotels</option><option value="House music">House music</option><option value="Humanitarianism">Humanitarianism</option><option value="Humanities">Humanities</option><option value="Humor">Humor</option><option value="Hunting">Hunting</option><option value="IT">IT</option><option value="Independent Film">Independent Film</option><option value="India">India</option><option value="Indie Rock/Pop">Indie Rock/Pop</option><option value="Industrial Design">Industrial Design</option><option value="Industrial Music">Industrial Music</option><option value="Instant Messaging">Instant Messaging</option><option value="Insurance">Insurance</option><option value="Int\'l Development">Int\'l Development</option><option value="Interior Design">Interior Design</option><option value="Internet">Internet</option><option value="Internet Tools">Internet Tools</option><option value="Investing">Investing</option><option value="Ipod">Ipod</option><option value="Iraq">Iraq</option><option value="Ireland">Ireland</option><option value="Islam">Islam</option><option value="Israel">Israel</option><option value="Italy">Italy</option><option value="Japan">Japan</option><option value="Java">Java</option><option value="Jazz">Jazz</option><option value="Jewelry">Jewelry</option><option value="Journalism">Journalism</option><option value="Judaism">Judaism</option><option value="Karaoke">Karaoke</option><option value="Kids">Kids</option><option value="Kinesiology">Kinesiology</option><option value="Knitting">Knitting</option><option value="Korea">Korea</option><option value="Landscaping">Landscaping</option><option value="Latin Music">Latin Music</option><option value="Law">Law</option><option value="Learning Disorders">Learning Disorders</option><option value="Lefthanded">Lefthanded</option><option value="Lesbian Culture">Lesbian Culture</option><option value="Lesbian Sex">Lesbian Sex</option><option value="Liberal Politics">Liberal Politics</option><option value="Liberties/Rights">Liberties/Rights</option><option value="Library Resources">Library Resources</option><option value="Lingerie">Lingerie</option><option value="Linguistics">Linguistics</option><option value="Linux/Unix">Linux/Unix</option><option value="Literature">Literature</option><option value="Live Theatre">Live Theatre</option><option value="Logic">Logic</option><option value="Lounge Music">Lounge Music</option><option value="Luxury">Luxury</option><option value="MacOS">MacOS</option><option value="Machinery">Machinery</option><option value="Magic/Illusions">Magic/Illusions</option><option value="Management/HR">Management/HR</option><option value="Manufacturing">Manufacturing</option><option value="Marine Biology">Marine Biology</option><option value="Marketing">Marketing</option><option value="Married Life">Married Life</option><option value="Martial Arts">Martial Arts</option><option value="Matchmaking">Matchmaking</option><option value="Mathematics">Mathematics</option><option value="Mechanical Eng.">Mechanical Eng.</option><option value="Medical Science">Medical Science</option><option value="Medieval History">Medieval History</option><option value="Memorabilia">Memorabilia</option><option value="Men\'s Issues">Men\'s Issues</option><option value="Mental Health">Mental Health</option><option value="Meteorology">Meteorology</option><option value="Mexico">Mexico</option><option value="Microbiology">Microbiology</option><option value="Middle East">Middle East</option><option value="Military">Military</option><option value="Mining/Metallurgy">Mining/Metallurgy</option><option value="Mobile Computing">Mobile Computing</option><option value="Mormon">Mormon</option><option value="Motor Sports">Motor Sports</option><option value="Motorcycles">Motorcycles</option><option value="Movies">Movies</option><option value="Multimedia">Multimedia</option><option value="Music">Music</option><option value="Music Composition">Music Composition</option><option value="Music Instruments">Music Instruments</option><option value="Music Theory">Music Theory</option><option value="Musicals">Musicals</option><option value="Musician Resources">Musician Resources</option><option value="Mutual Funds">Mutual Funds</option><option value="Mystery Novels">Mystery Novels</option><option value="Mythology">Mythology</option><option value="Nanotech">Nanotech</option><option value="Native Americans">Native Americans</option><option value="Nature">Nature</option><option value="Netherlands">Netherlands</option><option value="Network Security">Network Security</option><option value="Neuroscience">Neuroscience</option><option value="New Age">New Age</option><option value="New York">New York</option><option value="News(General)">News(General)</option><option value="Nightlife">Nightlife</option><option value="Nonprofit/Charity">Nonprofit/Charity</option><option value="Nuclear Science">Nuclear Science</option><option value="Nude Art">Nude Art</option><option value="Nursing">Nursing</option><option value="Nutrition">Nutrition</option><option value="Oceania">Oceania</option><option value="Oldies Music">Oldies Music</option><option value="Online Games">Online Games</option><option value="Open Source">Open Source</option><option value="Opera">Opera</option><option value="Operating Systems">Operating Systems</option><option value="Options/Futures">Options/Futures</option><option value="Orthodox">Orthodox</option><option value="Outdoors">Outdoors</option><option value="P2P">P2P</option><option value="PHP">PHP</option><option value="Paganism">Paganism</option><option value="Painting">Painting</option><option value="Paleontology">Paleontology</option><option value="Paranormal">Paranormal</option><option value="Parenting">Parenting</option><option value="Percussion">Percussion</option><option value="Performing Arts">Performing Arts</option><option value="Peripheral Devices">Peripheral Devices</option><option value="Perl">Perl</option><option value="Personal Sites">Personal Sites</option><option value="Petroleum">Petroleum</option><option value="Pets">Pets</option><option value="Pharmacology">Pharmacology</option><option value="Philosophy">Philosophy</option><option value="Photo Gear">Photo Gear</option><option value="Photography">Photography</option><option value="Photoshop">Photoshop</option><option value="Physical Therapy">Physical Therapy</option><option value="Physics">Physics</option><option value="Physiology">Physiology</option><option value="Poetry">Poetry</option><option value="Poker">Poker</option><option value="Political Science">Political Science</option><option value="Politics">Politics</option><option value="Pop music">Pop music</option><option value="Pornography">Pornography</option><option value="Postmodernism">Postmodernism</option><option value="Pregnancy/Birth">Pregnancy/Birth</option><option value="Programming">Programming</option><option value="Protestant">Protestant</option><option value="Proxy">Proxy</option><option value="Psychiatry">Psychiatry</option><option value="Psychology">Psychology</option><option value="Punk Rock">Punk Rock</option><option value="Puzzles">Puzzles</option><option value="Quilting">Quilting</option><option value="Quizzes">Quizzes</option><option value="Quotes">Quotes</option><option value="Racquetball">Racquetball</option><option value="Radio Broadcasts">Radio Broadcasts</option><option value="Rave Culture">Rave Culture</option><option value="Real Estate">Real Estate</option><option value="Recording Gear">Recording Gear</option><option value="Reggae">Reggae</option><option value="Relationships">Relationships</option><option value="Religion">Religion</option><option value="Research">Research</option><option value="Restaurants">Restaurants</option><option value="Restoration">Restoration</option><option value="Robotics">Robotics</option><option value="Rock music">Rock music</option><option value="Rodeo">Rodeo</option><option value="Roleplaying Games">Roleplaying Games</option><option value="Romance Novels">Romance Novels</option><option value="Rugby">Rugby</option><option value="Running">Running</option><option value="Russia">Russia</option><option value="SEO">SEO</option><option value="Sailing">Sailing</option><option value="Satire">Satire</option><option value="Science">Science</option><option value="Science Fiction">Science Fiction</option><option value="Scientology">Scientology</option><option value="Scouting">Scouting</option><option value="Scrapbooking">Scrapbooking</option><option value="Scuba Diving">Scuba Diving</option><option value="Sculpting">Sculpting</option><option value="Search">Search</option><option value="Self Improvement">Self Improvement</option><option value="Semiconductors">Semiconductors</option><option value="Senior Citizens">Senior Citizens</option><option value="Sewing">Sewing</option><option value="Sex Industry">Sex Industry</option><option value="Sex Toys">Sex Toys</option><option value="Sexual Health">Sexual Health</option><option value="Sexuality">Sexuality</option><option value="Shakespeare">Shakespeare</option><option value="Shareware">Shareware</option><option value="Shopping">Shopping</option><option value="Skateboarding">Skateboarding</option><option value="Skiing">Skiing</option><option value="Skydiving">Skydiving</option><option value="Snowboarding">Snowboarding</option><option value="Soap Operas">Soap Operas</option><option value="Soccer">Soccer</option><option value="Socialism">Socialism</option><option value="Sociology">Sociology</option><option value="Software">Software</option><option value="Songwriting">Songwriting</option><option value="Soul/R&amp;B">Soul/R&amp;B</option><option value="Soundtracks">Soundtracks</option><option value="South America">South America</option><option value="Space Exploration">Space Exploration</option><option value="Spain">Spain</option><option value="Spas">Spas</option><option value="Spirituality">Spirituality</option><option value="Sports(General)">Sports(General)</option><option value="Squash">Squash</option><option value="Statistics">Statistics</option><option value="StumbleUpon">StumbleUpon</option><option value="Subculture">Subculture</option><option value="Substance Abuse">Substance Abuse</option><option value="Sufism">Sufism</option><option value="Sunni">Sunni</option><option value="Supercomputing">Supercomputing</option><option value="Surfing">Surfing</option><option value="Survivalist">Survivalist</option><option value="Swimming">Swimming</option><option value="Swingers">Swingers</option><option value="Tattoos/Piercing">Tattoos/Piercing</option><option value="Taxation">Taxation</option><option value="Tea">Tea</option><option value="Techno">Techno</option><option value="Technology">Technology</option><option value="Teen Life">Teen Life</option><option value="Teen Parenting">Teen Parenting</option><option value="Telecom">Telecom</option><option value="Television">Television</option><option value="Tennis">Tennis</option><option value="Terrorism">Terrorism</option><option value="Toys">Toys</option><option value="Track/Field">Track/Field</option><option value="Trains/Railroads">Trains/Railroads</option><option value="Trance">Trance</option><option value="Transexual Sex">Transexual Sex</option><option value="Transportation">Transportation</option><option value="Travel">Travel</option><option value="TripHop/Downtempo">TripHop/Downtempo</option><option value="UFOs">UFOs</option><option value="UK">UK</option><option value="USA">USA</option><option value="University/College">University/College</option><option value="Vegetarian">Vegetarian</option><option value="Video Equipment">Video Equipment</option><option value="Video Games">Video Games</option><option value="Vintage Cars">Vintage Cars</option><option value="Virtual Reality">Virtual Reality</option><option value="Vocal Music">Vocal Music</option><option value="Volleyball">Volleyball</option><option value="Water Sports">Water Sports</option><option value="Web Development">Web Development</option><option value="Webhosting">Webhosting</option><option value="Weblogs">Weblogs</option><option value="Weddings">Weddings</option><option value="Weight Loss">Weight Loss</option><option value="Wicca">Wicca</option><option value="Windows">Windows</option><option value="Windows Dev">Windows Dev</option><option value="Windsurfing">Windsurfing</option><option value="Wine">Wine</option><option value="Women\'s Issues">Women\'s Issues</option><option value="Woodworking">Woodworking</option><option value="Wrestling">Wrestling</option><option value="Writing">Writing</option><option value="Yoga">Yoga</option><option value="Zoology">Zoology</option>'; }
  //#### Show Unit  Settings
  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl; ?>
            <div id="doSU<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>" style="background-image: url(<?php echo $nxs_plurl; ?>img/su-bg.png);  background-position:90% 10%;">     <input type="hidden" name="apDoSSU<?php echo $ii; ?>" value="0" id="apDoSSU<?php echo $ii; ?>" />          
            
             <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/su16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-stumbleupon-social-networks-auto-poster-wordpress/"><?php $nType="StumbleUpon"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>
            
            <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easely identify it', 'nxs_snap'); ?></i> </div><input name="su[<?php echo $ii; ?>][nName]" id="sunName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>
            <?php echo nxs_addQTranslSel('su', $ii, $options['qTLng']); ?><?php echo nxs_addPostingDelaySel('su', $ii, $options['nHrs'], $options['nMin']); ?>
            
             <?php if (!$isNew) { ?>
    <div style="width:100%;"><strong><?php _e('Categories', 'nxs_snap'); ?>:</strong>
       <input value="0" id="catSelA<?php echo $ii; ?>" type="radio" name="su[<?php echo $ii; ?>][catSel]" <?php if ((int)$options['catSel'] != 1) echo "checked"; ?> /> All                                  
       <input value="1" id="catSelSSU<?php echo $ii; ?>" type="radio" name="su[<?php echo $ii; ?>][catSel]" <?php if ((int)$options['catSel'] == 1) echo "checked"; ?> /> <a href="#" style="text-decoration: none;" class="showCats" id="nxs_SCA_SU<?php echo $ii; ?>" onclick="jQuery('#catSelSSU<?php echo $ii; ?>').attr('checked', true); jQuery('#tmpCatSelNT').val('SU<?php echo $ii; ?>'); nxs_markCats( jQuery('#nxs_SC_SU<?php echo $ii; ?>').val() ); jQuery('#showCatSel').bPopup({ modalClose: false, appendTo: '#nsStForm', opacity: 0.6, follow: [false, false], position: [75, 'auto']}); return false;">Selected<?php if ($options['catSelEd']!='') echo "[".(substr_count($options['catSelEd'], ",")+1)."]"; ?></a>       
       <input type="hidden" name="su[<?php echo $ii; ?>][catSelEd]" id="nxs_SC_SU<?php echo $ii; ?>" value="<?php echo $options['catSelEd']; ?>" />
    <br/><i><?php _e('Only selected categories will be autoposted to this account', 'nxs_snap'); ?></i></div> 
    <br/>
    <?php } ?>
            
            <div style="width:100%;"><strong>StumbleUpon Username:</strong> </div><input name="su[<?php echo $ii; ?>][apSUUName]" id="apSUUName" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['suUName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                
            <div style="width:100%;"><strong>StumbleUpon Password:</strong> </div><input name="su[<?php echo $ii; ?>][apSUPass]" id="apSUPass" type="password" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities(substr($options['suPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['suPass'], 5)):$options['suPass'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  <br/>                
            
            <?php if ($isNew) { ?> <input type="hidden" name="su[<?php echo $ii; ?>][apDoSU]" value="1" id="apDoNewSU<?php echo $ii; ?>" /> <?php } ?>
            <br/>            
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText">StumbleUpon Category:</strong> </div>
  
              <select name="su[<?php echo $ii; ?>][apSUCat]" id="apSUCat<?php echo $ii; ?>"><option value="error" selected="selected" disabled="">Select default StumbleUpon Category</option>
            <?php  $suCats = $this->suCats(); 
              if (isset($options['suCat']) && $options['suCat']!='') $suCats = str_replace('"'.$options['suCat'].'"', '"'.$options['suCat'].'" selected="selected"', $suCats);  echo $suCats; 
            
             ?>
            </select>
            <input value="1"  id="suInclTags" type="checkbox" name="su[<?php echo $ii; ?>][nsfw]"  <?php if ((int)$options['nsfw'] == 1) echo "checked"; ?> /> <strong>NSFW</strong>
            </div>   
            
            <p style="margin-bottom: 20px;margin-top: 5px;"><input value="1"  id="suInclTags" type="checkbox" name="su[<?php echo $ii; ?>][suInclTags]"  <?php if ((int)$options['suInclTags'] == 1) echo "checked"; ?> /> 
              <strong>Post with tags</strong> Tags from the blogpost will be auto posted to StumbleUpon                                
            </p>
            
            <div id="altFormat" style="">
  <div style="width:100%;"><strong id="altFormatText"><?php _e('Post Text Format', 'nxs_snap'); ?></strong> (<a href="#" id="apSUMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apSUMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)</div>
              
              <textarea cols="150" rows="3" id="su<?php echo $ii; ?>SNAPformat" name="su[<?php echo $ii; ?>][apSUMsgFrmt]" style="width:51%;max-width: 650px;" onfocus="jQuery('#su<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apSUMsgFrmt<?php echo $ii; ?>');"><?php if ($isNew) echo "%TITLE% - %EXCERPT%"; else _e(apply_filters('format_to_edit', htmlentities($options['suMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); ?></textarea>
              
              
              <?php nxs_doShowHint("apSUMsgFrmt".$ii); ?>
            </div><br/>    
            
            <?php if ($options['suPass']!='') { ?>
            <?php wp_nonce_field( 'rePostToSU', 'rePostToSU_wpnonce' ); ?>
            <b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('SU', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s' , 'nxs_snap'), $nType); ?></a>      
               
            <?php } 
            
            ?><div class="submit"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div></div><?php
  }
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ global $nxs_snapThisPageUrl; $code = 'SU'; $lcode = 'su'; 
    foreach ($post as $ii => $pval){ 
      if (isset($pval['apSUUName']) && $pval['apSUUName']!=''){ if (!isset($options[$ii])) $options[$ii] = array();
        if (isset($pval['apSUUName']))   $options[$ii]['suUName'] = trim($pval['apSUUName']);
        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);
        if (isset($pval['apSUPass']))    $options[$ii]['suPass'] = 'n5g9a'.nsx_doEncode($pval['apSUPass']); else $options[$ii]['suPass'] = '';  
        if (isset($pval['apSUCat'])) $options[$ii]['suCat'] = trim($pval['apSUCat']);                                                  
        if (isset($pval['suInclTags']))     $options[$ii]['suInclTags'] = $pval['suInclTags']; else $options[$ii]['suInclTags'] = 0;
        if (isset($pval['apSUMsgFrmt'])) $options[$ii]['suMsgFormat'] = trim($pval['apSUMsgFrmt']);             
        
        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']);
        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';
                                             
        if (isset($pval['apDoSU']))      $options[$ii]['doSU'] = $pval['apDoSU']; else $options[$ii]['doSU'] = 0; 
        if (isset($pval['nsfw']))      $options[$ii]['nsfw'] = $pval['nsfw']; else $options[$ii]['nsfw'] = 0; 
        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 
        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 
      }
    } return $options;
  }  
  //#### Show Post->Edit Meta Box Settings
  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID;
     foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapSU', true));   if (is_array($pMeta)) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 
        $doSU = $ntOpt['doSU'] && (is_array($pMeta) || $ntOpt['catSel']!='1');   
        $isAvailSU =  $ntOpt['suUName']!='' && $ntOpt['suPass']!=''; $suMsgFormat = htmlentities($ntOpt['suMsgFormat'], ENT_COMPAT, "UTF-8"); $suMsgTFormat = htmlentities($ntOpt['suMsgTFormat'], ENT_COMPAT, "UTF-8");      
      ?>  
      <tr><th style="text-align:left;" colspan="2"><?php if ( $ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='' )  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_SU<?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>
      <?php if ($isAvailSU) { ?><input class="nxsGrpDoChb" value="1" id="doSU<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="su[<?php echo $ii; ?>][doSU]" <?php if ((int)$doSU == 1) echo 'checked="checked" title="def"';  ?> /> 
      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="su[<?php echo $ii; ?>][doSU]" value="<?php echo $doSU;?>"> <?php } ?> <?php } ?>
      
      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/su16.png);">StumbleUpon - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th> <td><?php //## Only show RePost button if the post is "published"
                    if ($post->post_status == "publish" && $isAvailSU) { ?><input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToSU_repostButton" id="rePostToSU_button" value="<?php _e('Repost to StumbleUpon', 'nxs_snap') ?>" />
                    <?php wp_nonce_field( 'rePostToSU', 'rePostToSU_wpnonce' ); } ?>
                    
                    <?php  if (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) ) { 
                        
                        ?> <span id="pstdSU<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">
                      <a style="font-size: 10px;" href="http://www.stumbleupon.com/content/<?php echo $pMeta[$ii]['pgID']; ?>" target="_blank"><?php $nType="Stumbleupon"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>
                    </span><?php } ?>
                </td></tr>                
                
                <?php if (!$isAvailSU) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your StumbleUpon Account to AutoPost to StumbleUpon</b>
                <?php } elseif ($post->post_status != "puZblish") { ?> 
               
                <tr id="altFormat1" style=""><th scope="row" style="text-align:right; width:60px; padding-right:10px;">Category:</th>
                <td><div id="altFormat" style="">  
              <select name="su[<?php echo $ii; ?>][apSUCat]" id="apSUCat<?php echo $ii; ?>"><option value="error" selected="selected" disabled="">Select default StumbleUpon Category</option>
            <?php  $suCats = $this->suCats();               
              if (isset($ntOpt['suCat']) && $ntOpt['suCat']!='') $suCats = str_replace('"'.$ntOpt['suCat'].'"', '"'.$ntOpt['suCat'].'" selected="selected"', $suCats);  echo $suCats;             
             ?>
            </select> <input value="1"  id="sunsfw<?php echo $ii; ?>" type="checkbox" name="su[<?php echo $ii; ?>][nsfw]"  <?php if ((int)$options['nsfw'] == 1) echo "checked"; ?> /> <strong>NSFW</strong>
            
            </div> </td></tr>
                
                <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top:6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Text Format:', 'nxs_snap') ?></th>
                <td>                
                <textarea cols="150" rows="1" id="su<?php echo $ii; ?>SNAPformat" name="su[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#su<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apSUMsgFrmt<?php echo $ii; ?>');"><?php echo $suMsgFormat; ?></textarea>
                <?php nxs_doShowHint("apSUMsgFrmt".$ii); ?></td></tr>
                <?php } 
     }
  }
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){  if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = '';  
    if (isset($pMeta['nsfw'])) $optMt['nsfw'] = $pMeta['nsfw'];
    if (isset($pMeta['SNAPformat'])) $optMt['suMsgFormat'] = $pMeta['SNAPformat']; 
    if (isset($pMeta['apSUCat'])) $optMt['suCat'] = $pMeta['apSUCat'];     
    if (isset($pMeta['doSU'])) $optMt['doSU'] = $pMeta['doSU'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doSU'] = 0; } 
    if (isset($pMeta['SNAPincludeSU']) && $pMeta['SNAPincludeSU'] == '1' ) $optMt['doSU'] = 1;  
    return $optMt;
  }  
}}
if (!function_exists("nxs_rePostToSU_ajax")) {
  function nxs_rePostToSU_ajax() { check_ajax_referer('rePostToSU');  $postID = $_POST['id']; $options = get_option('NS_SNAutoPoster');  
    foreach ($options['su'] as $ii=>$two) if ($ii==$_POST['nid']) {   $two['ii'] = $ii; $two['pType'] = 'aj'; //if ($two['gpPageID'].$two['gpUName']==$_POST['nid']) {  
      $gppo =  get_post_meta($postID, 'snapSU', true); $gppo =  maybe_unserialize($gppo);// prr($gppo);
      if (is_array($gppo) && isset($gppo[$ii]) && is_array($gppo[$ii])){ $ntClInst = new nxs_snapClassSU(); $two = $ntClInst->adjMetaOpt($two, $gppo[$ii]); }
      $result = nxs_doPublishToSU($postID, $two); if ($result == 200) die("Successfully sent your post to StumbleUpon."); else die($result);        
    }    
  }
}  

if (!function_exists("nxs_getSUHeaders")) {  function nxs_getSUHeaders($ref, $post=false, $xhr=true){ $hdrsArr = array(); 
 if ($xhr) $hdrsArr['X-Requested-With']='XMLHttpRequest'; 
 $hdrsArr['Connection']='keep-alive'; $hdrsArr['Referer']=$ref;
 $hdrsArr['User-Agent']='Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)';
 if($post) $hdrsArr['Content-Type']='application/x-www-form-urlencoded'; 
 if ($xhr) $hdrsArr['Accept']='application/json, text/javascript, */*; q=0.01'; else $hdrsArr['Accept']='text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
 $hdrsArr['Origin']='http://www.stumbleupon.com';
 $hdrsArr['Accept-Encoding']='gzip,deflate,sdch'; $hdrsArr['Accept-Language']='en-US,en;q=0.8'; $hdrsArr['Accept-Charset']='ISO-8859-1,utf-8;q=0.7,*;q=0.3'; return $hdrsArr;
}}
if (!function_exists("nxs_doCheckSU")) {function nxs_doCheckSU(){ global $nxs_suCkArray; $hdrsArr = nxs_getSUHeaders('https://www.stumbleupon.com/submit'); $ckArr = $nxs_suCkArray;   
  $response = wp_remote_get('http://www.stumbleupon.com/submit', array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'cookies' => $ckArr));   
  $response['body'] = htmlentities($response['body'], ENT_COMPAT, "UTF-8"); // $response['body'] = htmlentities($response['body']);  prr($response);  die();
  if (isset($response['headers']['location']) && $response['headers']['location']=='/submit/visitor') return 'Bad Saved Login';  
  if ( $response['response']['code']=='200' && stripos($response['body'], 'Add a New Page')!==false){ 
      
      
      /*echo "You are IN"; */ return false; 
  } else return 'No Saved Login';
  return false;  
}}
if (!function_exists("nxs_doConnectToSU")) {  function nxs_doConnectToSU($u, $p){ global $nxs_suCkArray; $hdrsArr = nxs_getSUHeaders('https://www.stumbleupon.com/login', true); //   echo "LOGGIN";
    $response = wp_remote_get('https://www.stumbleupon.com/login'); if (is_wp_error($response)) { nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($response, true), ''); return "Connection ERROR. Please see log";}
    $contents = $response['body']; //$response['body'] = htmlentities($response['body']);  prr($response);    die();
    $ckArr = $response['cookies']; 
    $frmTxt = CutFromTo($contents, '<form id="login-form"','</form>'); $md = array(); $flds  = array();// prr($frmTxt); 
    while (stripos($frmTxt, '<input')!==false){ $inpField = trim(CutFromTo($frmTxt,'<input', '>')); $name = trim(CutFromTo($inpField,'name="', '"'));
     if ( stripos($inpField, '"hidden"')!==false && $name!='' && !in_array($name, $md)) { $md[] = $name; $val = trim(CutFromTo($inpField,'value="', '"')); $flds[$name]= $val; $mids .= "&".$name."=".$val;}
     $frmTxt = substr($frmTxt, stripos($frmTxt, '<input')+8);
    } $flds['user'] = $u; $flds['pass'] = $p; $flds['remember'] = 'true'; $flds['nativeSubmit'] = '0'; $flds['_method'] = 'create';   
    
    $r2 = wp_remote_post( 'https://www.stumbleupon.com/login', array( 'method' => 'POST', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'body' => $flds, 'cookies' => $ckArr));
    //prr($flds); prr($ckArr); prr($r2);
    $resp = json_decode($r2['body'], true);  
    if ($resp['_success']=='1') { $ckArr = nxsMergeArraysOV($ckArr, $r2['cookies']); $nxs_suCkArray = $ckArr; return false; } elseif (isset($resp['_reason'])) { return $resp['_reason']; } else return "ERROR";   
}}
if (!function_exists("nxs_doPostToSU")) {  function nxs_doPostToSU($msg, $lnk, $cat, $tags, $nsfw=false){ global $nxs_suCkArray; $r2 = wp_remote_get($lnk); 
  $hdrsArr = nxs_getSUHeaders('https://www.stumbleupon.com/submit', false, false); $ckArr = $nxs_suCkArray;   
  $response = wp_remote_get('https://www.stumbleupon.com/submit', array( 'method' => 'GET', 'timeout' => 45, 'redirection' => 0,  'headers' => $hdrsArr, 'cookies' => $ckArr));   
  $ckArr2 = nxsMergeArraysOV($ckArr, $response['cookies']); //$nxs_suCkArray = $ckArr;
  
  $contents = $response['body']; //$response['body'] = htmlentities($response['body']);  prr($response);   
  //$ckArr = nxsMergeArraysOV($ckArr, $response['cookies']);  
  $hdrsArr = nxs_getSUHeaders('https://www.stumbleupon.com/submit', true);
  $frmTxt = CutFromTo($contents, '<form method="post" id="submit-form"','</form>'); $md = array(); $flds  = array(); // prr($contents);
    while (stripos($frmTxt, '<input')!==false){ $inpField = trim(CutFromTo($frmTxt,'<input', '>')); $name = trim(CutFromTo($inpField,'name="', '"'));
     if ( stripos($inpField, '"hidden"')!==false && $name!='' && !in_array($name, $md)) { $md[] = $name; $val = trim(CutFromTo($inpField,'value="', '"')); $flds[$name]= $val; $mids .= "&".$name."=".$val;}
     $frmTxt = substr($frmTxt, stripos($frmTxt, '<input')+8);
    } $flds['url'] = $lnk; $flds['review'] = $msg; $flds['tags'] = $cat; $flds['nsfw'] = $nsfw?'true':'false'; $flds['user-tags'] = $tags;  $flds['_output'] = 'Json';  $flds['_method'] = 'create';  $flds['language'] = 'EN'; 
    
  $r2 = wp_remote_post('https://www.stumbleupon.com/submit', array('method' => 'POST', 'timeout' => 45, 'redirection' => 0, 'headers' => $hdrsArr, 'body' => $flds, 'cookies' => $ckArr)); 
  $resp = json_decode($r2['body'], true); 
  
  if (stripos($resp['_reason'][0]['message'], 'Failed to add URL')!==false) { sleep(5);
    $r2 = wp_remote_post('https://www.stumbleupon.com/submit', array('method' => 'POST', 'timeout' => 45, 'redirection' => 0, 'headers' => $hdrsArr, 'body' => $flds, 'cookies' => $ckArr)); 
    $resp = json_decode($r2['body'], true);
  }
  
  if (stripos($resp['_error'], 'Invalid token')!==false) { // In case we got the Wrong Cookies
    $r2 = wp_remote_post('https://www.stumbleupon.com/submit', array('method' => 'POST', 'timeout' => 45, 'redirection' => 0, 'headers' => $hdrsArr, 'body' => $flds, 'cookies' => $ckArr2)); 
    $resp = json_decode($r2['body'], true);
    
    if (stripos($resp['_reason'][0]['message'], 'Failed to add URL')!==false) { sleep(5);
      $r2 = wp_remote_post('https://www.stumbleupon.com/submit', array('method' => 'POST', 'timeout' => 45, 'redirection' => 0, 'headers' => $hdrsArr, 'body' => $flds, 'cookies' => $ckArr2)); 
      $resp = json_decode($r2['body'], true); // prr($flds);  prr($resp); //nxs_addToLogN('SU', 'E', '-=DBG=- '.print_r($resp, true)." - #####", $extInfo);
    }    
  } 
  
  if (isset($resp['discovery']['publicid'])) $pageID = $resp['discovery']['publicid']; elseif (isset($resp['discovery']['url']['publicid']))$pageID = $resp['discovery']['url']['publicid'];   
  if ($resp['_success']=='1') { $ckArr = nxsMergeArraysOV($ckArr, $r2['cookies']); $nxs_suCkArray = $ckArr; return array("code"=>"OK", "post_id"=>$pageID); } 
    elseif (isset($resp['_reason'])) { $resp['_reason']['NXS_FIELDS'] = $flds; $resp['_reason']['NXS_RESP'] = $resp;  return $resp['_reason']; } else return "ERROR".print_r($resp, true);   
}}

if (!function_exists("nxs_doPublishToSU")) { //## Second Function to Post to SU
  function nxs_doPublishToSU($postID, $options){ global $nxs_suCkArray; $ntCd = 'SU'; $ntCdL = 'su'; $ntNm = 'StumbleUpon';    
    //$backtrace = debug_backtrace(); nxs_addToLogN('W', 'Enter', $ntCd, 'I am here - '.$ntCd."|".print_r($backtrace, true), ''); 
    //if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToSU',  array($postID, $options));   
    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 
    $logNT = '<span style="color:#000080">StumbleUpon</span> - '.$options['nName'];      
    $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     
    if ($options['reset'] != '1' && $options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {
        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') {  sleep(5);
         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$uqID); return;
        }
    }
      $suCat = $options['suCat'];      
      // if (function_exists("get_post_thumbnail_id") ){ $src = wp_get_attachment_image_src(get_post_thumbnail_id($postID), 'thumbnail'); $src = $src[0];}
      $email = $options['suUName'];  $pass = (substr($options['suPass'], 0, 5)=='n5g9a'?nsx_doDecode(substr($options['suPass'], 5)):$options['suPass']);      
      if ($postID=='0') { echo "Testing ... <br/><br/>"; $link = home_url(); $msg = 'Test Link from '.$link; } else { $post = get_post($postID); if(!$post) return;
        $msgFormat = $options['suMsgFormat'];  $msg = nsFormatMessage($msgFormat, $postID); $link = get_permalink($postID); nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1'));
      } 
      $dusername = $options['suUName']; //$link = urlencode($link); $desc = urlencode(substr($msg, 0, 500));      
      $extInfo = ' | PostID: '.$postID." - ".$post->post_title; 
      if ($options['suInclTags']=='1') { $t = wp_get_post_tags($postID); $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = urlencode(implode(',',$tggs)); $tags = str_replace(' ','+',$tags); } else $tags = '';
      if (isset($options['suSvC'])) $nxs_suCkArray = maybe_unserialize( $options['suSvC']); $loginError = true;
      if (is_array($nxs_suCkArray)) $loginError = nxs_doCheckSU(); if ($loginError!=false) $loginError = nxs_doConnectToSU($email, $pass); 
      if (serialize($nxs_suCkArray)!=$options['suSvC']) { global $plgn_NS_SNAutoPoster;  $gOptions = $plgn_NS_SNAutoPoster->nxs_options;
        if (isset($options['ii']) && $options['ii']!=='')  { $gOptions['su'][$options['ii']]['suSvC'] = serialize($nxs_suCkArray); update_option('NS_SNAutoPoster', $gOptions);  }
        else foreach ($gOptions['su'] as $ii=>$gpn) { $result = array_diff($options, $gpn); 
          if (!is_array($result) || count($result)<1) { $gOptions['su'][$ii]['suSvC'] = serialize($nxs_suCkArray); update_option('NS_SNAutoPoster', $gOptions); break; }
        }        
      }  
      if ($loginError!==false) {if ($postID=='0') prr($loginError); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($loginError, true)." - BAD USER/PASS", $extInfo); return " -= BAD USER/PASS =- ";}       
      
      $ret = nxs_doPostToSU($msg, $link, $options['suCat'], $tags, $options['nsfw']=='1'); // $extInfo .= "++".$msg."|".$link."|".$options['suCat']."|".$tags."|".$options['nsfw'];      
      if ($ret=='OK') $ret = array("code"=>"OK", "post_id"=>'');
      if ( (!is_array($ret)) && $ret!='OK') { if ($postID=='0') prr($ret); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo);} 
      elseif ($ret['code']=='OK')  if ($postID=='0')  { nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); echo ' OK - Message Posted, please see your StumbleUpon Page '; } else 
          { nxs_metaMarkAsPosted($postID, 'SU', $options['ii'], array('isPosted'=>'1', 'pgID'=>$ret['post_id'], 'pDate'=>date('Y-m-d H:i:s'))); nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo);  return 200; }
      else { if ($options['reset'] == '1') {
          nxs_addToLogN('E', 'Error', $logNT, '-=ERROR TRY #2=- '.print_r($ret, true), $extInfo.$options['reset']);
          return "ERROR = ".print_r($ret, true);    
        } else { $options['reset'] = '1'; $options['suSvC'] = ''; return nxs_doPublishToSU($postID, $options);  }
      }
      
      
  }
}  
?>