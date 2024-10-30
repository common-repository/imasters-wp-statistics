<?php
//count of users
$all_users = get_users_of_blog();
$count_users = count($all_users);

//Call the function that create the XML tag for construct to Post Publishers by Users
$xml_post_user = $imasters_wp_statistics->get_graph_post_user();

//Call the function that create the XML tag for crontruct chart to Month User
$xml_month_user = $imasters_wp_statistics->get_graph_month_user();

//Call the function that create the XML tag for crontruct chart to Posts per Category
$xml_post_category = $imasters_wp_statistics->get_graph_post_category();

//Call the function that concatenate in a array the counters(Posts, pages, attachments) from blog
$counters = $imasters_wp_statistics->get_counters_usage();

//sets of comments
$pingback = $imasters_wp_statistics->get_pingback();
//$comments_div = $counters['count_comments']->total_comments - $pingback;
$total_comments = $counters['count_comments']->total_comments - $pingback;

//count total posts from database
$total_post = $counters['count_posts']->publish + $counters['count_posts']->private + $counters['count_posts']->draft + $counters['count_posts']->pending + $counters['count_posts']->future;
//count total pages from database
$total_pages = count(get_all_page_ids());
//count total attachments from database
$total_attachment = $imasters_wp_statistics->get_attachment_total();
//sum the totals elements page to get single average
$total_percent = $total_post + $total_attachment + $total_pages;
//pages single average
$post_percent = $total_post/$total_percent;
$page_percent = $total_pages/$total_percent;
$attachment_percent = $total_attachment/$total_percent;
?>
<div id="wpbody-content" style="overflow: hidden;">
    <div class="wrap">
        <h2><img src="<?php echo plugins_url( 'imasters-wp-statistics/assets/images/imasters32.png' )?>" alt="imasters-ico"/><?php _e(' iMasters WP Statistics','iwps')?></h2>
            <div class="metabox-holder">
                <div style="width: 49%;" class="postbox-container">
                    <div class="postbox">
                        <h3 class="hndle"><span><?php _e('Post types - Total Usage from Posts, Pages and Attachments: ','iwps')?></span><span><?php echo $total_percent . '(100%)' ?></span></h3>
                        <div id="post-type" class="inside">The chart will appear within this DIV. This text will be replaced by the chart.</div>
                        <script type="text/javascript">
                            var myChart = new FusionCharts("../wp-content/plugins/imasters-wp-statistics/assets/charts/FCF_Pie3D.swf", "post-type", "550", "480");
                            myChart.setDataXML("<graph showNames='1' decimalPrecision='0' formatNumberScale='0' numberSuffix='%' animation='1'><set name='<?php _e('Posts', 'iwps') ?>' value='<?php echo round($post_percent*100); ?>' color='339999' /><set name='<?php _e('Pages', 'iwps') ?>' value='<?php echo round($page_percent*100); ?>' color='f86c00' /><set name='<?php _e('Attachment', 'iwps') ?>' value='<?php echo round($attachment_percent*100)?>' color='c6131a' /></graph>");
                            myChart.render("post-type");
                        </script>
                    </div>
                    <div class="postbox">
                        <h3 class="hndle"><span><?php _e('Post status - Total Posts: ','iwps')?></span><span><?php echo $total_post; ?></span></h3>
                        <div id="post-status" class="inside">The chart will appear within this DIV. This text will be replaced by the chart.</div>
                           <script type="text/javascript">
                              var myChart = new FusionCharts("../wp-content/plugins/imasters-wp-statistics/assets/charts/FCF_Pie3D.swf", "post-status", "550", "480");
                              myChart.setDataXML("<graph showNames='1' decimalPrecision='0' formatNumberScale='0'><set name='<?php _e('Published', 'iwps') ?>' value='<?php echo $counters['count_posts']->publish; ?>' color='339999' /><set name='<?php _e('Private', 'iwps') ?>' value='<?php echo $counters['count_posts']->private; ?>' color='f86c00' /><set name='<?php _e('Draft', 'iwps') ?>' value='<?php echo $counters['count_posts']->draft; ?>' color='c6131a' /><set name='<?php _e('Pending', 'iwps') ?>' value='<?php echo $counters['count_posts']->pending; ?>' color='DF3F0D' /><set name='<?php _e('Future', 'iwps') ?>' value='<?php echo $counters['count_posts']->future; ?>' color='96834C' /></graph>");
                              myChart.render("post-status");
                           </script>
                    </div>
                </div>
                <div style="width: 99%;" class="postbox-container">
                    <div class="postbox">
                    <h3 class="hndle"><span><?php _e('Users monthly registered','iwps')?><?php echo _e(' - All(', 'iwps').$count_users.')'; ?></span></h3>
                    <div id="month-user" chass="inside">The chart will appear within this DIV. This text will be replaced by the chart.</div>
                           <script type="text/javascript">
                              var myChart = new FusionCharts("../wp-content/plugins/imasters-wp-statistics/assets/charts/FCF_Line.swf", "month-user", "800", "300");
                              myChart.setDataXML("<?php echo $xml_month_user; ?>");
                              myChart.render("month-user");
                           </script>
                    </div>
                </div>
                <div style="width: 99%;" class="postbox-container">
                    <div class="postbox">
                    <h3 class="hndle"><span><?php _e("Post publications by users ",'iwps')?></span></h3>
                    <div id="user-post" chass="inside">The chart will appear within this DIV. This text will be replaced by the chart.</div>
                           <script type="text/javascript">
                              var myChart = new FusionCharts("../wp-content/plugins/imasters-wp-statistics/assets/charts/FCF_Column3D.swf", "user-post", "800", "300");
                              myChart.setDataXML("<?php echo $xml_post_user; ?>");
                              myChart.render("user-post");
                           </script>
                    </div>
                </div>
                <div style="width: 99%;" class="postbox-container">
                    <div class="postbox">
                    <h3 class="hndle"><span><?php _e("Posts per category ",'iwps')?></span></h3>
                    <div id="post-category" chass="inside">The chart will appear within this DIV. This text will be replaced by the chart.</div>
                           <script type="text/javascript">
                              var myChart = new FusionCharts("../wp-content/plugins/imasters-wp-statistics/assets/charts/FCF_Column3D.swf", "post-category", "800", "300");
                              myChart.setDataXML("<?php echo $xml_post_category; ?>");
                              myChart.render("post-category");
                           </script>
                    </div>
                </div>
                <div style="width: 49%;" class="postbox-container">
                    <div class="postbox">
                         <h3 class="hndle"><span><?php _e('Status comments - Total Comments: ','iwps')?></span><span><?php echo $total_comments ?></span></h3>
                        <div id="status-comments" class="inside">The chart will appear within this DIV. This text will be replaced by the chart.</div>
                        <script type="text/javascript">
                            var myChart = new FusionCharts("../wp-content/plugins/imasters-wp-statistics/assets/charts/FCF_Pie3D.swf", "status-comments", "550", "480");
                            myChart.setDataXML("<graph showNames='1' decimalPrecision='0' formatNumberScale='0' animation='1'><set name='<?php _e('Approved', 'iwps') ?>' value='<?php echo $counters['count_comments']->approved; ?>' color='339999' /><set name='<?php _e( 'Moderated', 'iwps') ?>' value='<?php echo $counters['count_comments']->moderated; ?>' color='f86c00' /><set name='<?php _e( 'Spam', 'iwps') ?>' value='<?php echo $counters['count_comments']->spam;?>' color='c6131a' /></graph>");
                            myChart.render("status-comments");
                        </script>
                    </div>
                    <div class="postbox">
                        <h3 class="hndle"><span><?php _e('Comments type - Total Comments: ','iwps')?></span><span><?php echo $total_comments ?></span></h3>
                        <div id="comments-type" class="inside">The chart will appear within this DIV. This text will be replaced by the chart.</div>
                           <script type="text/javascript">
                              var myChart = new FusionCharts("../wp-content/plugins/imasters-wp-statistics/assets/charts/FCF_Pie3D.swf", "comments-type", "550", "480");
                              myChart.setDataXML("<graph showNames='1' decimalPrecision='0' formatNumberScale='0'><set name='<?php _e( 'Comments', 'iwps' ) ?>' value='<?php echo $comments_div; ?>' color='339999' /><set name='<?php _e( 'PingBack', 'iwps') ?>' value='<?php echo $pingback; ?>' color='f86c00' /></graph>");
                              myChart.render("comments-type");
                           </script>
                    </div>
                </div>
            </div>
        <div class="clear"/>
    </div>
</div>