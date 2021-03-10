<?php defined( 'ABSPATH' ) or die( "you do not have access to this page!" );?>

<?php $progress = COMPLIANZ::$wizard->wizard_percentage_complete(); ?>
	<style>
		@keyframes cmplz-load-progress-bar {
			0% { width: 0; }
			100% { width: <?php echo $progress?>%; }
		}
	</style>
	<div class="cmplz-progress-bar">
		<div class="cmplz-progress-bar-value"></div>
	</div>
	<div class="cmplz-grid-progress">
		<div class="cmplz-progress-percentage">
			<?php echo $progress?>%
		</div>
		<div class="cmplz-progress-description">
			<?php if ( $progress < 100
			) {
				printf( __( 'Your website is not ready for your selected regions yet.', 'complianz-gdpr' ),
					cmplz_supported_laws() );
			} else {
				printf( __( 'Well done! Your website is ready for your selected regions.', 'complianz-gdpr' ),
					cmplz_supported_laws() );
			} ?>
		</div>
	</div>

	<div class="rsssl-scroll-container">
			<?php
			$warning_args = array(
				'cache' => false,
			);
			if ( isset($_GET['status']) && $_GET['status'] === 'remaining' ) {
				$warning_args['status'] = array('urgent', 'open');
			}
			$warnings = COMPLIANZ::$admin->get_warnings($warning_args);

			$status_message = '';
			foreach ( $warnings as $id => $warning) {
				$status = $warning['status'];
				if ( $status === 'completed' ) {
					$status_message = __("Completed", 'complianz-gdpr');
				}
				if ( $status === 'open' ) {
					$status_message = __("Open", 'complianz-gdpr');
				}
				if ( $status === 'urgent' ) {
					$status_message = __("Urgent", 'complianz-gdpr');
				}
				?>
				<div class="cmplz-progress-warning-container">
					<div class="cmplz-progress-status-container">
						<span class="cmplz-progress-status cmplz-<?php echo $status?>"><?php echo $status_message?></span>
					</div>
					<div><?php echo $warning['message'] ?></div>
					<div>
						<?php if ( $status === 'open' ) { ?>
						<button type="button" class="cmplz-dismiss-warning" data-warning_id="<?php echo $id?>">
							<span class="cmplz-close-warning-x">X</span>
						</button>
						<?php } ?>
					</div>
				</div>
				<?php
			}
			?>

	</div>
<?php

