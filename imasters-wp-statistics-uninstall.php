<div class="wrap">
    <h2><img src="<?php echo plugins_url( 'imasters-wp-statistics/assets/images/imasters32.png' )?>" alt="imasters-ico"/>
<?php
//Unistall Stats
    $deactivate_url = 'plugins.php?action=deactivate&amp;plugin=imasters-wp-statistics/imasters-wp-statistics.php';
    if(function_exists('wp_nonce_url')) {
            $deactivate_url = wp_nonce_url($deactivate_url, 'deactivate-plugin_imasters-wp-statistics/imasters-wp-statistics.php');
    }
    echo __('UNINSTALL iMasters WP Statistics', 'iwps').'</h2>';
    echo '<p><strong>'.sprintf(__('<a href="%s">Click Here</a> To Uninstall And Deactivated Automatically iMasters WP Statistics.', 'iwps'), $deactivate_url).'</strong></p>';
?>
</div>