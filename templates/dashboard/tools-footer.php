<?php
	$email = sanitize_email(get_option('admin_email'));
	$user_info = get_userdata(get_current_user_id());
	$nicename = $user_info->user_nicename;
?>
<div class="item-footer">
	<div>
		<div class="cmplz-footer-contents">
			<a class="button button-secondary" href="<?php echo add_query_arg(array('email'=>esc_html($email), 'user'=>$nicename,'website'=>esc_url(site_url())),'https://complianz.io/support')?>" ><?php _e("Support", "complianz-gdpr") ?></a>
			<a class="button button-secondary" href="<?php echo trailingslashit(cmplz_url).'system-status.php' ?>" ><?php _e("System Status", "complianz-gdpr") ?></a>
		</div>
	</div>
</div>
