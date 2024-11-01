<?php
 /*
 Plugin Name: Truma
 Plugin URI: 
 Version: 0.0.1a
 Author: Rotem Tamir
 Description: Collect donations from Walla! Pay (Israeli PayPal) services. Use at your own risk.*/
 

// Hook for adding admin menus
add_action('admin_menu', 'truma_add_pages');

// action function for above hook
function truma_add_pages() {

    // Add a new top-level menu (ill-advised):
    add_options_page('הגדרות תרומות', 'הגדרות תרומות', 8, __FILE__, 'truma_config');

    // Add a submenu to the custom top-level menu:
    add_management_page('ניהול תרומות', 'ניהול תרומות', 8, __FILE__, 'truma_manage');
}

// truma_options_page() displays the page content for the Test Options submenu
// mt_options_page() displays the page content for the Test Options submenu
function truma_config() {

    // variables for the field and option names 
    $hidden_field_name = 'tr_submit_hidden';

    // Read in existing option value from database
    $paid_val = get_option( 'PaId' );
    $donate_sums_opts = get_option('donate_sums_opts');
    $allow_choose_sum = get_option('allow_choose_sum');
    $donation_desc = get_option('donation_desc');
    $is_test = get_option('is_test');    

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $paid_val = $_POST[ 'pa_id' ];
        $donate_sums_opts = $_POST[ 'donate_sums_opts' ];
        $allow_choose_sum = $_POST[ 'allow_choose_sum' ];
        $donation_desc = $_POST[ 'donation_desc' ];
        $is_test = $_POST[ 'is_test' ];
        if(! $is_test) {
            $is_test="";
        }

        // Save the posted value in the database
        update_option( 'PaId', $paid_val );
        update_option( 'donate_sums_opts', $donate_sums_opts );
        update_option( 'allow_choose_sum', $allow_choose_sum );
        update_option( 'donation_desc', $donation_desc );
        update_option( 'is_test', $is_test );
        

        // Put an options updated message on the screen

?>
<div class="updated"><p><strong>ההגדרות נשמרו.</strong></p></div>
<?php

    }

    // Now display the options editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>הגדרות וואלה! Pay</h2>";

    // options form
    
    ?>

<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p>

מזהה ייחודי בוואלה (PaId):
<input type="text" name="pa_id" value="<?php echo $paid_val; ?>" size="40">
</p>

<p>

תיאור התרומה כפי שיופיע באישור עסקה בוואלה
<input type="text" name="donation_desc" value="<?php echo $donation_desc; ?>" size="40">
</p>

<p>לשלוח לוואלה משתנה IsTest?
<small>אם בחרת כן, וואלה לא תבצע בפועל את הסליקות</small>
<input type="checkbox" name="is_test" value="1"  <?php
if($is_test=="1"){
  echo 'checked="true"';
}
?>/>
</p>

<h2>הגדרות תרומה</h2>

<p>מדרגות תרומה אפשריות: 
<small>מופרד על ידי פסיקים. למשל: 50,100,200,300,1024</small>
<input type="text" name="donate_sums_opts" value="<?php echo $donate_sums_opts; ?>" size="100">
</p>

<p>לאפשר סכום פתוח? 
<small>לאפשר לתורמים לבחור סכום תרומה משלהם.</small>
<input type="checkbox" name="allow_choose_sum" value="allow"  <?php
if($allow_choose_sum=="allow"){
  echo 'checked="true"';
}
?>/>
</p>
<hr />

<p class="submit">
<input type="submit" name="Submit" value="עדכן הגדרות" />
</p>

</form>
</div>

<?php
 
}


// truma_manage_page() displays the page content for the Test Manage submenu
function truma_manage() {
    global $wpdb;
    echo "<h2>מידע על תורמים</h2>";
    $table_name = $wpdb->prefix . "donators";

    $donors = $wpdb->get_results("SELECT * FROM ".$table_name);
?>
<style>
    #donor-table td{
        border:1px solid black;
        padding:1em;
    }
    
    #donor-table tr:hover {
        background: #eee;
    }
</style>
<table border="1" cellpadding="1" id="donor-table">
    <thead>
        <tr>
            <td>id</td>
            <td>מייל</td>
            <td>שם</td>
            <td>טלפון</td>
            <td>סטטוס</td>
            <td>מחרוזת אימות</td>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($donors as $donor) {
              echo "<tr>";
              echo "<td>".$donor->id."</td>";
              echo "<td><a href='mailto:".$donor->email."'>".$donor->email."</td>";
              echo "<td>".$donor->name."</td>";
              echo "<td>".$donor->phone."</td>";
              echo "<td>".$donor->status."</td>";
              echo "<td>".$donor->confirm_string."</td>";
              echo "</tr>";
            }
        ?>
    </tbody>
</table>
<?php
    
}

// [bartag foo="foo-value"]
function truma_form_func($atts) {
  $paid_val = get_option( 'PaId' );
  
  // Prepare Sum Select.
  $donate_sums_opts = get_option('donate_sums_opts');
  $arrSums = explode(",",$donate_sums_opts);
  $select = "<select name='donate_sum'>";
  foreach ($arrSums as $sum) {
    $select .= '<option value="'.$sum.'">'.$sum.' ש"ח</option>';
  }
  $select .= "</select>";
  
  // Prepare sum text box
  $allow_choose_sum = get_option('allow_choose_sum');  
  $custom_sum = "";
  if($allow_choose_sum=="allow") {
    $custom_sum = '
    <p><label for="custom_sum">סכום אחר:</label>
    <input name="custom_sum" id="custom_sum" size="10" /></p>';
  }
  
  $ret='
    <link rel="stylesheet" href="'.get_option('siteurl').'/wp-content/plugins/truma/css/cssform.css" type="text/css" media="screen" />



    <h3>ניתן לשלם בכרטיס אשראי באמצעות מערכת התשלומים המאובטחת <a href="http://www.wallapay.co.il"><img src="'.get_option('siteurl').'/wp-content/plugins/truma/img/wallapay-logo.gif" id="wallapay-logo" /></a>
    (תהליך התשלום כולל הרשמה קצרה ופשוטה למערכת וואלה!Pay)</h3>
			<div class="cssform" >
			
			<form action="wp-content/plugins/truma/php/tr-submit-donate.php" method="post">
			<div>
      <p>
        <label for="sender">שם מלא:</label>
        <input name="name" id="name" size="30" value="" />
      </p>
      <p>
        <label for="email">כתובת דוא"ל:</label>
        <input name="email" id="email" size="30" value="" />
      </p>
      <p>
        <label for="phone">טלפון/סלולרי:</label>
        <input name="phone" id="phone" size="30" value="" />
      </p>      
      <p>  
        <label for="donate_sum">סכום התרומה:</label>
        '.$select.'
      </p>
      '.$custom_sum.'	
			<input type="submit" name="submit" id="contactsubmit" value="שלח" />

			</div>
			</form>
			</div>
      <div class="wallapay-info">

        <img src="'.get_option('siteurl').'/wp-content/plugins/truma/img/pay_botton_s.gif" />
        <span class="ques">האם בטוח להשתמש בכרטיס אשראי?</span> 
        <div class="answ">
            כן בוודאי!<br/>
<p>
            '.get_option('blogname').'
            עושה שימוש בשרותי חברת וואלה! Pay לסליקת כרטיסי אשראי של התורמים.
            מספר כרטיס האשראי ותעודת הזהות שתזינו למערכת וואלה! Pay יישארו חסויים לחלוטין במערכת וואלה! Pay - אנו רק נקבל את תרומתכם ואת הפרטים שתשאירו בטופס לעיל.
            שימוש באמצעי ההגנה הנכונים הופך את הקנייה באמצעות האינטרנט לבטוחה יותר מהקנייה בחנות רגילה !
</p>
<p>             
            אם אין לכם בעיה לתת את מספר כרטיס האשראי שלכם במסעדה או כשאתם מזמינים כרטיסי קולנוע בטלפון, בוואלה! Pay אתם יכולים להיות רגועים אף יותר  -
            השרתים של וואלה! Pay מוגנים באמצעות תוכנת פירוול המבטיחה זרימת מידע מבוקרת, ומונעת חדירות לתוכה.
</p>
<p>              
            וואלה! Pay היא מערכת סגורה לחלוטין המגינה ומאבטחת את המשתמשים. 
</p>
<p>              
            במערכת וואלה! Pay קיימת הקפדה חמורה על הפרדה מוחלטת בין שרת הרשת לבין שרת מסדי הנתונים עליו שמורים נתוני הלקוחות.
            יתרה מזאת, נתוני כרטיסי האשראי עורבלו באמצעות צופן מיוחד כך שאין יכולת ממשית למי שאינו מורשה לשלוף את הנתונים מבסיסי הנתונים של החברה.
</p>
<p> 
            בוואלה! Pay אמשתמשים בפרוטוקול הסטנדרטי של המסחר האלקטרוני - SSL
            פרוטוקול זה "מערבל" את המידע שמועבר במערכת, ובכך הופך אותו לצופן שרק גורמים מורשים יכולים לפענח. הצפנה זו מתרחשת מרגע הכנסת המידע למערכת ועד יצירת הקשר עם הגורמים המאשרים את העיסקה.
</p>
<p>              
            דעו את זכויותיכם:
             אם למרות כל הצעדים המתקדמים הללו יתרחש שימוש לרעה בכרטיס האשראי, חייבת החברה המנפיקה את כרטיס האשראי להחזיר ללקוח את מלוא סכום העיסקה תוך 30! יום - דבר זה מובטח על פי החוק!            
</p>

        </div>
      </div>
      ';

	return $ret;
}
add_shortcode('truma-form', 'truma_form_func');

function truma_install () {
   global $wpdb;
   $table_name = $wpdb->prefix . "donators";
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
      $sql = "CREATE TABLE " . $table_name . " (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          name tinytext NOT NULL,
          email tinytext NOT NULL,
          phone tinytext NULL,
          amount int NOT NULL,
          status varchar(20) NOT NULL DEFAULT 'sent to walla',
          confirm_string tinytext NULL,
          UNIQUE KEY id (id)
        );";
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);

      $insert = "INSERT INTO " . $table_name .
                " (name, email, amount) " .
                "VALUES ('Test Donor','test@donor.com',100)";

      $results = $wpdb->query( $insert );

      
    }
}
register_activation_hook(__FILE__,'truma_install');

?>
