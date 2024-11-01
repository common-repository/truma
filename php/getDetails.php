<?php
      if (!function_exists('add_action'))
      {
          require_once("../../../../wp-config.php");
      }
      $pa_id = get_option('PaId');  
      $is_test = get_option('is_test');
      $donation_desc = iconv('utf-8','windows-1255',get_option('donation_desc') );
      $thankUrl = get_option('siteurl')."/wp-content/plugins/truma/php/tr-thankyou.php";
      $od_id = (int) $_GET['OdId'];
     
      global $wpdb;

      $table_name = $wpdb->prefix . "donators";
      $name = $wpdb->get_var("SELECT name FROM ".$table_name." WHERE id=".$od_id);
      $amount = $wpdb->get_var("SELECT amount FROM ".$table_name." WHERE id=".$od_id);
      if($_GET['PaId']==$pa_id && $name && $_GET['OpDesc']=='ReqDtls') {
        header('Content-Type: text/xml');
        print '<?xml version="1.0" encoding="windows-1255" standalone="yes"?>';
        ?>
            <TranWP>
                <PaId><?php echo $pa_id?></PaId>
                <OdId><?php echo $od_id?></OdId>
                <OrderId><?php echo str_pad($od_id, 6, "0", STR_PAD_LEFT)?></OrderId>
                <OrderDesc><![CDATA[<?php echo $donation_desc?>]]></OrderDesc>
                <OrderTotalNIS><?php echo $amount*100?></OrderTotalNIS>
                <NumMaxPayments>1</NumMaxPayments>
                <JumpToOnSuccess_URL>
                    <![CDATA[<?php echo $thankUrl?>]]>
                </JumpToOnSuccess_URL>
                <IsTest><?php echo $is_test ?></IsTest>
            </TranWP>
        <?php
      }  ?>
