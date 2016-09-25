<?php include 'page-option-header.php'; ?>
<section class="wrapper licensing_wrapper">
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">
					<h4>
						<?php echo page_title(); ?>
					</h4>
				</header>
				<div class="panel-body">
					<div class="col-md-12">
						<div class="form">
						<form class="form-horizontal" id="frm_pluginactivation" method="post">								
								<div class="form-group ">
									<label for="licensekey" class="control-label col-lg-2">License Key</label>
									<div class="col-lg-10">
										<textarea class=" form-control" id="desc" name="licensekey"
											data-field-value="yes" data-field-name="licensekey"
											minlength="2" type="text"></textarea>
									</div>
								</div>								
								<div class="form-group ">
									<input type="hidden" value="<?php echo wp_create_nonce(Plugin_Core::$current_plugin_data['TextDomain'].'_nonce')?>" name="<?php echo Plugin_Core::$current_plugin_data['TextDomain'];?>_licensekey_nonce"
									data-field-value="yes" data-field-name="<?php echo Plugin_Core::$current_plugin_data['TextDomain'];?>_licensekey_nonce"
									 />									
								</div>
								<input type="submit" value="Submit" style="display: none;" />
							</form>
						</div>
					</div>
					<div class="col-md-12">
						<div class="row">
							<input type="button"
								class="btn btn-primary btn-lg col-md-3 col-xs-12 center-block btn-bottom"
								value="Activate" id="btnpluginactivation" />
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</section>
<?php include 'page-option-footer.php'; ?>