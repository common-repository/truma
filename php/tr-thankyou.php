<?php
      if (!function_exists('add_action'))
      {
          require_once("../../../../wp-config.php");
      }
      $pa_id = get_option('PaId');
      $thankUrl = get_option('thankUrl');
      if (!$thankUrl) {
        $thankUrl=get_option('siteurl');
      }
      $od_id = (int) $_GET['OdId'];
      global $wpdb;
      $table_name = $wpdb->prefix . "donators";
      $name = $wpdb->get_var("SELECT name FROM ".$table_name." WHERE id=".$od_id);
      $status = $wpdb->get_var("SELECT status FROM ".$table_name." WHERE id=".$od_id);
      $amount = $wpdb->get_var("SELECT amount FROM ".$table_name." WHERE id=".$od_id);
      $order_id = str_pad($od_id, 6, "0", STR_PAD_LEFT);
      get_header();
      if($_GET['paId']==$pa_id && $name && ($status=="confirmed from walla" || $status=="saw thank you page")) {
        $wpdb->update( $table_name, array( 'status' => 'saw thank you page'), array( 'ID' => $od_id ), array( '%s', '%s' ), array( '%d' ) );
?>
<div id="content" class="widecolumn">
    <style>
        .thank-you-donator {
            direction: rtl;
            text-align:right;
            padding:3em;
        }
        
        .thank-you-donator h1 {
            padding-bottom:2em;
        }
        
        .thank-you-donator img {
           padding-bottom:1em;
        }
        
        .back-2-site {
            text-align:left;
        }
    </style>
    <div class="thank-you-donator">
        <h1>
            תודה שתרמתם!
        </h1>
        <img src="<?php print get_option('siteurl'); ?>/wp-content/plugins/truma/img/thank-you.jpg" />
        <h2>
            <?php echo get_option('blogname');?> מודה לכם על תרומתכם.
        </h2>
        <div>
            מספר התרומה שלכם הוא <?php print $order_id; ?>.
        </div>
        <div class="back-2-site">
            <a href="<?php print get_option('siteurl'); ?>">חזרה לאתר</a>
        </div>
    </div>
    
<?php
      } else {
        print "שגיאת אבטחה.";
      } 
?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
