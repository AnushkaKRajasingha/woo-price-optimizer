<?php
/**
 * @author Anushka Rajasingha
 * @url http://www.anushkar.com
 * @date 09/02/2016
 * @uses Woocommers price Optimizer
 *
 */
if (! class_exists ( 'clsWooPriceOptimizer' )) {
	class clsWooPriceOptimizer extends clsPluginBase {
		use clsDbBase;
		public function __construct() {
			$this->__init ();
		}
		
		/* Implement clsPluginBase */
		public function __init() {
			try {
				
				$enblrndprice = Plugin_Core::$current_plugin_data ['TextDomain'].'_enbl_rnd_price';
				$_enblrndprice = get_option($enblrndprice); //var_dump($_enblrndprice);
				if ($_enblrndprice == 'yes') {
					
				
				
				/* product Data tab */
				add_filter ( 'woocommerce_product_data_tabs', array (
						$this,
						'add_woopo_productdata_tab' 
				), 99, 1 );
				add_action ( 'woocommerce_product_data_panels', array (
						$this,
						'add_woopo_product_write_panel_tabs' 
				) );
				
				add_action( 'woocommerce_process_product_meta', array($this,'woopo_product_meta_fields_save' ));
				
				
				}
				
				/* Add settings Tab */
				add_filter ( 'woocommerce_get_sections_products', array (
						$this,
						'add_woopo_settingstab' 
				) );
				add_filter ( 'woocommerce_get_settings_products', array (
						$this,
						'add_woopo_settings' 
				), 10, 2 );
			} catch ( Exception $e ) {
				$errorlogger = new ErrorLogger ();
				$errorlogger->add_message ( $e->getMessage () );
				exit ();
			}
		}
		
		/* Settings Tab Related */
		public function add_woopo_settingstab($sections) {
			try {
				$sections [Plugin_Core::$current_plugin_data ['TextDomain']] = __ ( Plugin_Core::$current_plugin_data ['Name'], Plugin_Core::$current_plugin_data ['TextDomain'] );
				return $sections;
			} catch ( Exception $e ) {
				$errorlogger = new ErrorLogger ();
				$errorlogger->add_message ( $e->getMessage () );
				exit ();
			}
		}
		public function add_woopo_settings($settings, $section) {
			try {
				if ($section == Plugin_Core::$current_plugin_data ['TextDomain']) {
					$test_settings = array (
							array (
									'title' => __ ( Plugin_Core::$current_plugin_data ['Name'], Plugin_Core::$current_plugin_data ['TextDomain'] ),
									'type' => 'title' 
							),
							
							array (
									'title' => __ ( 'Enable Random Price', 'woocommerce' ),
									'id' => Plugin_Core::$current_plugin_data ['TextDomain'].'_enbl_rnd_price',
									'type' => 'checkbox' ,
									'desc'  => __ ( 'This setting will enable / disable the price optimization feature for single products ', 'woocommerce' ),
									'default' => '1'
							) ,
							array (
									'title' => __ ( 'Enable Visitor Report', 'woocommerce' ),
									'id' => Plugin_Core::$current_plugin_data ['TextDomain'].'_enbl_visitor',
									'type' => 'checkbox' ,
									'desc'   => __ ( 'This setting will enable / disable the vsitor tracking feature of the plugin ', 'woocommerce' ),
									'default' => '1'
							),
							
							array (
									'title' => __ ( 'Track Visitor By Cookie', 'woocommerce' ),
									'id' => Plugin_Core::$current_plugin_data ['TextDomain'].'_enbl_visitor_by_cooke',
									'type' => 'checkbox' ,
									'desc'   => __ ( 'This setting will enable / disable the vsitor tracking by cookie ', 'woocommerce' ),
									'default' => '1'
							),
							
							array (
									'title' => __ ( 'Track Visitor By IP Address', 'woocommerce' ),
									'id' => Plugin_Core::$current_plugin_data ['TextDomain'].'_enbl_visitor_by_ip',
									'type' => 'checkbox' ,
									'desc'   => __ ( 'This setting will enable / disable the vsitor tracking by IP Address ', 'woocommerce' ),
									'default' => '1'
							)
					);
					$test_settings [] = array (
							'type' => 'sectionend',
							'id' => Plugin_Core::$current_plugin_data ['TextDomain'] 
					);
					return $test_settings;
				} else {
					return $settings;
				}
			} catch ( Exception $e ) {
				$errorlogger = new ErrorLogger ();
				$errorlogger->add_message ( $e->getMessage () );
				exit ();
			}
		}
		
		/* Product data tab related */
		public function add_woopo_productdata_tab($product_data_tabs) {
			$product_data_tabs [Plugin_Core::$current_plugin_data ['TextDomain'] . '-tab'] = array (
					'label' => __ ( Plugin_Core::$current_plugin_data ['Name'], Plugin_Core::$current_plugin_data ['TextDomain'] ),
					'target' => Plugin_Core::$current_plugin_data ['TextDomain'] . '_product_data',
					'class' => 'cls_' . Plugin_Core::$current_plugin_data ['TextDomain'] . '_product_data' 
			);
			return $product_data_tabs;
		}
		public function add_woopo_product_write_panel_tabs() {
			global $woocommerce, $post;
			$plugin_text_domain = Plugin_Core::$current_plugin_data ['TextDomain'];
			?>
			<!-- id below must match target registered in above add_my_custom_product_data_tab function -->
			<div id="<?php echo $plugin_text_domain .'_product_data'; ?>"
				class="panel woocommerce_options_panel wc-metaboxes-wrapper <?php echo 'cls_'.$plugin_text_domain .'_product_data_panel'; ?>">
				<style>
				.btn_container_randsaleprice {
    float: right;
    position: absolute;
    top: 0;
    margin-top: 18px;
    padding-top: 5px;
    right: 5em;
}
button.button.remove_randsaleprice{
margin-left:1em;
}

				</style>
				<script type="text/javascript">
					(function($){
						$(document).ready(function(){	
							$elm_counter = 1;						
							$('.product_randsalseprice.wc-metaboxes > p.form-field').each(function(){								
								$id_base =  $('.toolbar > p.form-field').find('input').attr('id');
								$lable_base = $('.toolbar > p.form-field').find('label').html();								
								$btn = $('<button type="button" data-target="'+$id_base+'_ctrl_'+$elm_counter+'" class="button remove_randsaleprice"><?php _e( 'Remove', 'woocommerce' ); ?></button>');
								$elm_counter++;					
								$btn.click(function(){
									console.log('remove');
									$('.'+$(this).attr('data-target')).remove();
									$count_new = 1;
									$('.product_randsalseprice.wc-metaboxes > p.form-field').each(function(){
										$(this).removeClass($(this).find('button').attr('data-target'));
										$(this).attr('id',$id_base+'_ctrl_'+$count_new);
										$(this).find('label').html($lable_base+' '+$count_new);
										$(this).find('input').attr('id',$id_base+'_'+$count_new);
										$(this).find('button.button.remove_randsaleprice').attr('data-target',$(this).attr('id'));
										$(this).addClass($(this).attr('id'));
										$count_new++;
									});
								});								
								$(this).append($btn);
							});						
							
							$('.add_randsaleprice').click(function(){
								if(!$('.toolbar > p.form-field').find('input').val().match(/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/)){ alert('Please enter valid sale price.'); return false; }								
								$elm = $('.toolbar > p.form-field').clone();	
								$id_base = $elm.find('input').attr('id');
								$lable_base = $elm.find('label').html(); 							
								$count = $('.product_randsalseprice.wc-metaboxes > p').length + 1;
								$elm.attr('id',$id_base+'_ctrl_'+$count); $elm.addClass($id_base+'_ctrl_'+$count);
								$elm.find('label').html($lable_base+' '+$count);
								$elm.find('input').attr('id',$id_base+'_'+$count);
								$elm.find('input').attr('name',$id_base+'s[]');
								$elm.append('<button type="button" data-target="'+$elm.attr('id')+'" class="button remove_randsaleprice"><?php _e( 'Remove', 'woocommerce' ); ?></button>');
								$elm.find('button.button.remove_randsaleprice').click(function(){
									console.log('remove');
									$('.'+$(this).data('target')).remove();
									$count_new = 1;
									$('.product_randsalseprice.wc-metaboxes > p.form-field').each(function(){
										$(this).removeClass($(this).attr('id'));
										$(this).attr('id',$id_base+'_ctrl_'+$count_new);
										$(this).find('label').html($lable_base+' '+$count_new);
										$(this).find('input').attr('id',$id_base+'_'+$count_new);
										$(this).find('button.button.remove_randsaleprice').attr('data-target',$(this).attr('id'));
										$(this).addClass($(this).attr('id'));
										$count_new++;
									});
								});
								$('.product_randsalseprice.wc-metaboxes').append($elm);
								console.log('elm added');
							});							
						});
					})(jQuery);
				</script>
				<div class="toolbar toolbar-top">
							      <?php
						$random_sale_price = array (
								'name' => $plugin_text_domain . '_rand_sale_price',
								'id' => $plugin_text_domain . '_rand_sale_price',
								'wrapper_class' => 'show_if_simple meta-box-sortables',
								'label' => __ ( 'Random Sale Price ', 'woocommerce' ),
								'default' => '0.00',
								'desc_tip' => false 
						);
						woocommerce_wp_text_input ( $random_sale_price );
						?>			      
					<div class="btn_container_randsaleprice">	
					<button type="button" class="button add_randsaleprice"><?php _e( 'Add', 'woocommerce' ); ?></button>
					</div>
					<!-- <button type="button" class="button remove_randsaleprice"><?php _e( 'Remove', 'woocommerce' ); ?></button> -->
					
				</div>
				<div class="product_randsalseprice wc-metaboxes">
				<?php 
					$_rand_sale_prices = get_post_meta( $post->ID, $plugin_text_domain . '_rand_sale_prices', true );
					if(is_array($_rand_sale_prices)){
						$counter = 1;
						foreach ($_rand_sale_prices as $_price) {
							$_random_sale_price = array (
								'name' => $plugin_text_domain . '_rand_sale_prices[]',
								'id' => $plugin_text_domain . '_rand_sale_price_'.$counter,
								'wrapper_class' => 'show_if_simple meta-box-sortables '.$plugin_text_domain . '_rand_sale_price_ctrl_'.$counter,
								'label' => __ ( 'Random Sale Price '.$counter, 'woocommerce' ),
								'value' => $_price,
								'desc_tip' => false 
						);
						woocommerce_wp_text_input ( $_random_sale_price );
						$counter++;
						}
					}
				?>
				</div>
				<div class="product_randsalseprice_displaycount">
				<?php 
				$random_sale_price_display = array (
								'name' => $plugin_text_domain . '_rand_sp_displaycount',
								'id' => $plugin_text_domain . '_rand_sp_displaycount',
								'wrapper_class' => 'show_if_simple',
								'label' => __ ( 'No of Display Times Each', 'woocommerce' ),
								'description'       =>__ ( 'Ex : 10 times each price, 3 prices , total 30 times price display.', 'woocommerce' ),
								'default' => '1',
								'desc_tip' => true 
						);
						woocommerce_wp_text_input ( $random_sale_price_display );
						
						?>
				</div>
			</div>
<?php
		}
		
		public function woopo_product_meta_fields_save( $post_id ){
			
			$plugin_text_domain = Plugin_Core::$current_plugin_data ['TextDomain'];
			
			if (isset($_POST[$plugin_text_domain . '_rand_sale_prices'])) {
				update_post_meta($post_id, $plugin_text_domain . '_rand_sale_prices', $_POST[$plugin_text_domain . '_rand_sale_prices']);
			}
			
			if (isset($_POST[$plugin_text_domain . '_rand_sp_displaycount'])) {
				update_post_meta($post_id, $plugin_text_domain . '_rand_sp_displaycount', $_POST[$plugin_text_domain . '_rand_sp_displaycount']);
			}
			
			
			//$errorlogger = new ErrorLogger ();
			//$errorlogger->add_message (Plugin_Utilities::custom_var_dump($_POST[$plugin_text_domain . '_rand_sale_prices']));
		
			//var_dump($_POST[$plugin_text_domain . '_rand_sale_prices']);
			
			//var_dump($_POST[$plugin_text_domain . '_rand_sp_displaycount']);
			
			// This is the case to save custom field data of checkbox. You have to do it as per your custom fields
			//$woo_checkbox = isset( $_POST['_my_custom_field'] ) ? 'yes' : 'no';
			//update_post_meta( $post_id, '_my_custom_field', $woo_checkbox );
		}
	}
}