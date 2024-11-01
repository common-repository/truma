<?php
      if (!function_exists('add_action'))
      {
          require_once("../../../../wp-config.php");
      }
      
      global $wpdb;
      $table_name = $wpdb->prefix . "donators";
      $pa_id = get_option('PaId');
      $od_id = (int) $_GET['OdId'];
      $status = $wpdb->get_var(mysql_escape_string("SELECT status FROM ".$table_name." WHERE id=".$od_id));
      if($_GET['PaId']==$pa_id && $status=="sent to walla") {
        $c_string = mysql_escape_string($_SERVER['QUERY_STRING']);
        $wpdb->update( $table_name, array( 'confirm_string' => $c_string,'status' => 'confirmed from walla'), array( 'ID' => $od_id ), array( '%s', '%s' ), array( '%d' ) );
        $success = "success";
      } else {
        $success = "fail";
      }
header('Content-Type: text/xml');
print '<?xml version="1.0" encoding="windows-1255" standalone="yes"?>';
?>
<TranWPStat>
    <PaId><?php echo $pa_id ?></PaId>
    <OdId><?php echo $od_id ?></OdId>
    <Status><?php echo $success ?></Status>
</TranWPStat>
