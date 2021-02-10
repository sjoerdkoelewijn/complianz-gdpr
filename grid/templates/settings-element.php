<form id="{page}-{name}" action="" method="post">
	<?php wp_nonce_field( 'complianz_save', 'cmplz_nonce', true, true ) ?>
	<section id="{name}" action="" method="post">
		<div class="cmplz-settings-item {class}" data-id="{index}" {conditions}>
			<div class="cmplz-settings-header-container">
				<div class="cmplz-settings-header">
					<div class="cmplz-settings-title">{header}</div>
					<div class="cmplz-settings-controls">{controls}</div>
				</div>
			</div>

			<div class="cmplz-settings-body">{body}</div>
			<div class="cmplz-settings-footer-container">
				<div class="cmplz-settings-footer">{footer}</div>
			</div>
		</div>
	</section>
</form>
