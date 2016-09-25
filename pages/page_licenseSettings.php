<?php include 'page-option-header.php'; $_pluginLicense = new Plugin_lisense(); ?>
<section class="wrapper">
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">
					<h4>
						<?php echo page_title(); ?>
					</h4>
				</header>
				<div class="panel-body">
					<?php if($license_key = $_pluginLicense->_isLicenseActive()){?>
					<h4>Your plugin is already activated.</h4>
					<p>
						License key is :
						<code>
							<?php echo base64_decode($license_key);?>
						</code>
						<?php } ?>
				
				</div>
				<div class="panel-heading tab-bg-dark-navy-blue">
					<div class="form-group">
						<div class="row">
							<div class="col-xs-12 col-sm-3 col-lg-2">
								<button type="button" class="btn btn-success col-xs-12" id="btn_removelicense">Remove License.</button>
							</div>
						</div>
						<div class="clear"></div>
					</div>

				</div>
			</section>
		</div>
	</div>
</section>
<?php include 'page-option-footer.php'; ?>
