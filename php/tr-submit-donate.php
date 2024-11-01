      <?php
      if (!function_exists('add_action'))
      {
          require_once("../../../../wp-config.php");
      }
      global $wpdb;
      $table_name = $wpdb->prefix . "donators";
      if($_POST['custom_sum']) {
        $sum = $_POST['custom_sum'];
      } else {
        $sum = $_POST['donate_sum'];
      }
      $wpdb->insert( $table_name, array( 'name' => $_POST['name'],
                                         'email' => $_POST['email'],
                                         'phone' => $_POST['phone'],
                                         'amount' => $sum,
                                         'status' => 'sent to walla') 
                                         );
      $pa_id = get_option('PaId');
      $thankUrl = get_option('thankUrl');
      $od_id = mysql_insert_id(); 
      header("Location: https://www.wallaPay.co.il/PO/POLogin.aspx?PaId=".$pa_id."&OdId=".$od_id);
      ?>

