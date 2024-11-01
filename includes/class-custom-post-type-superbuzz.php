<?php

namespace Superbuzz\Includes;

// prevent public user to directly access your file 
defined('ABSPATH') or die('This page may not be accessed directly.');

// Custom Post Type Function
class Custom_Post_Type_Superbuzz { 

    // menu page function
    public function custom_add_menu_page() {
       
        // Add menu page
        add_menu_page( 
            'SuperBuz - Boost retention traffic and profits using GPT-3 technology',
            'SuperBuzz',
            'manage_options',
            'Superbuzz',
            array($this, 'superbuzz_push_notifications_callback'),
			plugin_dir_url( __DIR__ ) . 'assets/images/superbuzzicon.png',
            2
        ); 	
    }   

    public function superbuzz_push_notifications_callback() {              			
        ?>            
			<div class="header-section">
                <a href="https://www.superbuzz.io/" target="_blank"><img src="<?php echo esc_url(plugin_dir_url( __DIR__ ) . 'assets/images/superbuzzlogo.png'); ?>"></a>				
			</div>
			<div class="privacy-settings-header">

				<nav class="privacy-settings-tabs-wrapper hide-if-no-js tab" aria-label="<?php esc_attr_e( 'Secondary menu' ); ?>">
					<a class="privacy-settings-tab superbuzztabs active"  aria-current="true" onclick="openCity(event, 'Setup')">
						<?php							
							/* translators: Tab heading for Site Health Status page. */
							_ex( 'Setup', 'Privacy Settings' );
						?>
					</a>
					<a class="privacy-settings-tab superbuzztabs" onclick="openCity(event, 'Configuration')">
						<?php
							/* translators: Tab heading for Site Health Status page. */
							_ex( 'Configuration', 'Privacy Settings' );
						?>
					</a>
				</nav>
			</div>
			<!-- tabs scripts -->
			<script>
			function openCity(evt, cityName) {
  				var i, tabcontent, superbuzztabs;
  				tabcontent = document.getElementsByClassName("tabcontent");
  				for (i = 0; i < tabcontent.length; i++) {
    				tabcontent[i].style.display = "none";
  				}
				  superbuzztabs = document.getElementsByClassName("superbuzztabs");
  				for (i = 0; i < superbuzztabs.length; i++) {
    				superbuzztabs[i].className = superbuzztabs[i].className.replace(" active", "");
  				}
  					document.getElementById(cityName).style.display = "block";
  					evt.currentTarget.className += " active";
			}
			</script>
			<!-- end tabs scripts -->
			<!-- <hr class="wp-header-end">	 -->
			<div class="tabcontent" id="Setup" style="display:block;">
                <h3 style="padding: 10px 0px;">
                    Please follow up these steps in order to setup and activate SuperBuzz.
                </h3>
				<p>
					<?php _e( '1.  Create a <a href="https://app.superbuzz.io/register" target="_blank">SuperBuzz</a> account or login into your existing account.' ); ?>		
				</p>
				<p>
					<?php _e( '2. Add your website to your SuperBuzz dashboard.' );?>	
				</p>
				<p>
					<?php _e( '3. Copy Your website App ID from SuperBuzz dashboard and Add it in the configuration page then press Validate.' );?>	
				</p>
			</div>
			<div class="tabcontent" id="Configuration">
			<?php 
			global $wpdb;
            $table_name = $wpdb->prefix . 'superbuzz'; 
            
            $fetchData = $wpdb->get_row($this->remove_backticks(
                $wpdb->prepare('SELECT * FROM %s LIMIT %d', $table_name, 1))
            );           
           
            $apiIDResponse = $fetchData->api_response ?? null;              
            $apiKeyId = $fetchData->app_id ?? null;            
            ?>       
            <div class="wrap">
                <h3 style="padding-bottom: 20px;">
                    Please add bellow your website App ID generated for your website in your SuperBuzz dashboard.                    
                </h3>               
                <form class="superbuzz_api_form" id="superbuzz_api_form">
                    <div class="superbuzz_inputs">
                        <?php					
                        if(isset($apiIDResponse)) {                            
                        ?>           
                            <div class="appIDValidated">      
                                <p>APP ID Validated</p>    
                            </div> 
                            <table class="form-table superbuzz-form-tables" role="presentation">
                                <tr class="form-field form-required">
                                    <th scope="row"><label for="superbuzz"><?php _e( 'APP ID' ); ?> </label></th>
                                    <td style="width:27%;">
                                        <input type="text" class="superbuzz_apikey" id="superbuzz_apikey" name="superbuzz_apikey" value="<?php echo esc_attr($apiKeyId); ?>" disabled>
                                    </td>                           
                                    <td>
                                        <?php submit_button( __( 'Validated' ), 'primary  superbuzz_apikey_validate', 'superbuzz_apikey_validate', true, array( 'id' => 'superbuzz_apikey_validate' , 'disabled' => 'true' ) ); ?>
                                    </td>
                                </tr>                         
                            </table> 
                            <p class="superbuzz_apikey_validations" id="superbuzz_apikey_validation"></p>
                            <?php
                        }else {                      
                            ?>
                            <div class="appIDValidated" style="display:none;">
                            </div>    
                            <table class="form-table superbuzz-form-table" role="presentation">
                                <tr class="form-field form-required">
                                    <th scope="row"><label for="superbuzz"><?php _e( 'APP ID' ); ?> </label></th>
                                    <td>
                                        <input type="text" class="superbuzz_apikey" id="superbuzz_apikey" name="superbuzz_apikey" placeholder="Enter APP ID">
                                    </td>
                                    <td>
                                        <?php submit_button( __( 'Validate' ), 'primary  superbuzz_apikey_submit', 'superbuzz_apikey_submit', true, array( 'id' => 'superbuzz_apikey_submit' ) ); ?>                               
                                    </td>                                    
                                </tr>                         
                            </table> 
                            <div class="serverError" style="display:none;"></div>   
                            <div class="appIDMissing" style="display:none;"></div>
                            <?php
                        }
                    ?>                                       
                    </div>         
                </form>
                 <h3 style="padding-top: 20px;">For any inquiry please contact us at <a href = "mailto: buzz@superbuzz.io"> buzz@superbuzz.io</a></h3>
            </div>   
			</div>			
		<?php   
    }


    public function remove_backticks( $s ) {
		return str_replace("'", "", $s);
	}

}