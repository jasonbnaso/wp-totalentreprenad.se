<?php
function ex_divi_child_theme_setup() {

	if ( class_exists('ET_Builder_Module')) {
		class ET_Builder_Module_Nitea_Contact_Form extends ET_Builder_Module {

			function init() {
				$this->name            = esc_html__( 'Nitea Contact Form', 'et_builder' );
				$this->slug            = 'et_pb_contact_form';
				$this->fb_support      = true;
				$this->child_slug      = 'et_pb_contact_field';
				$this->child_item_text = esc_html__( 'Field', 'et_builder' );

				$this->whitelisted_fields = array(
					'email',
					'sender_email_address',
					'phone_number',
					'email_address',
					'email_copy',
					'title',
					'admin_label',
					'module_id',
					'module_class',
					'form_background_color',
					'input_border_radius',
					'submit_button_text',
					'custom_message',
					'use_redirect',
					'redirect_url',
					'success_message',
				);

				$this->fields_defaults = array(
					'email_copy'      => array( 'on' ),
					'use_redirect' => array( 'off' ),
				);

				$this->main_css_element = '%%order_class%%.et_pb_contact_form_container';

				$this->options_toggles = array(
					'general'  => array(
						'toggles' => array(
							'main_content' => esc_html__( 'Text', 'et_builder' ),
							'email'        => esc_html__( 'Email', 'et_builder' ),
							'elements'     => esc_html__( 'Elements', 'et_builder' ),
							'redirect'     => esc_html__( 'Redirect', 'et_builder' ),
							'background'   => esc_html__( 'Background', 'et_builder' ),
						),
					),
				);

				$this->advanced_options = array(
					'fonts' => array(
						'title' => array(
							'label'    => esc_html__( 'Title', 'et_builder' ),
							'css'      => array(
								'main' => "{$this->main_css_element} h1",
							),
						),
						'form_field'   => array(
							'label'    => esc_html__( 'Form Field', 'et_builder' ),
							'css'      => array(
								'main' => array(
									"{$this->main_css_element} .input",
									"{$this->main_css_element} .input::-webkit-input-placeholder",
									"{$this->main_css_element} .input::-moz-placeholder",
									"{$this->main_css_element} .input:-ms-input-placeholder",
									"{$this->main_css_element} .input[type=checkbox] + label",
									"{$this->main_css_element} .input[type=radio] + label",
								),
								'important' => 'plugin_only',
							),
						),
					),
					'border' => array(
						'css'      => array(
							'main' => sprintf(
								'%1$s .input,
								%1$s .input[type="checkbox"] + label i,
								%1$s .input[type="radio"] + label i',
								$this->main_css_element
							),
							'important' => 'plugin_only',
						),
						'settings' => array(
							'color' => 'alpha',
						),
					),
					'button' => array(
						'button' => array(
							'label' => esc_html__( 'Button', 'et_builder' ),
							'css' => array(
								'plugin_main' => "{$this->main_css_element}.et_pb_module .et_pb_button",
							),
						),
					),
				);
				$this->custom_css_options = array(
					'contact_title' => array(
						'label'    => esc_html__( 'Contact Title', 'et_builder' ),
						'selector' => '.et_pb_contact_main_title',
					),
					'contact_button' => array(
						'label'    => esc_html__( 'Contact Button', 'et_builder' ),
						'selector' => '.et_pb_contact_form_container .et_contact_bottom_container .et_pb_contact_submit.et_pb_button',
						'no_space_before_selector' => true,
					),
					'contact_fields' => array(
						'label'    => esc_html__( 'Form Fields', 'et_builder' ),
						'selector' => 'input',
					),
					'text_field' => array(
						'label'    => esc_html__( 'Message Field', 'et_builder' ),
						'selector' => 'textarea.et_pb_contact_message',
					),
				);
			}

			function get_fields() {
				$fields = array(
					'email' => array(
						'label'           => esc_html__( 'Till', 'et_builder' ),
						'type'            => 'text',
						'option_category' => 'basic_option',
						'description'     => et_get_safe_localization( sprintf(
							__( 'Input the email address where messages should be sent.<br /><br /> Note: email delivery and spam prevention are complex processes. We recommend using a delivery service such as <a href="%1$s">Mandrill</a>, <a href="%2$s">SendGrid</a>, or other similar service to ensure the deliverability of messages that are submitted through this form', 'et_builder' ),
							'http://mandrill.com/',
							'https://sendgrid.com/'
						) ),
						'toggle_slug'     => 'email',
					),
					'sender_email_address' => array(
						'label'           => esc_html__( 'Från adress', 'et_builder' ),
						'type'            => 'text',
						'option_category' => 'basic_option',
						'description'     => esc_html__( 'Ange vem mejlet ska skickas från. Viktigt att denna adress får skicka från servern.', 'et_builder' ),
						'toggle_slug'     => 'email',
						'default' 		  => isset(get_option( 'postman_options' )['envelope_sender']) ? get_option( 'postman_options' )['envelope_sender'] : '',
					),
					'email_copy' => array(
						'label'           => esc_html__( 'Kopiafält', 'et_builder' ),
						'type'            => 'yes_no_button',
						'option_category' => 'configuration',
						'options'         => array(
							'off' => esc_html__( 'No', 'et_builder' ),
							'on'  => esc_html__( 'Yes', 'et_builder' ),
						),
						'toggle_slug'     => 'email',
						'description'     => esc_html__( 'Ska användaren kunna få en kopia på mejlet.', 'et_builder' ),
					),
					'title' => array(
						'label'           => esc_html__( 'Title', 'et_builder' ),
						'type'            => 'text',
						'option_category' => 'basic_option',
						'description'     => esc_html__( 'Define a title for your contact form.', 'et_builder' ),
						'toggle_slug'     => 'main_content',
					),
					'custom_message' => array(
						'label'           => esc_html__( 'Message Pattern', 'et_builder' ),
						'type'            => 'textarea',
						'option_category' => 'configuration',
						'description'     => et_get_safe_localization( __( 'Here you can define the custom pattern for the email Message. Fields should be included in following format - <strong>%%field_id%%</strong>. For example if you want to include the field with id = <strong>phone</strong> and field with id = <strong>message</strong>, then you can use the following pattern: <strong>My message is %%message%% and phone number is %%phone%%</strong>. Leave blank for default.', 'et_builder' ) ),
						'toggle_slug'     => 'email',
					),
					'use_redirect' => array(
						'label'           => esc_html__( 'Enable Redirect URL', 'et_builder' ),
						'type'            => 'yes_no_button',
						'option_category' => 'configuration',
						'options'         => array(
							'off' => esc_html__( 'No', 'et_builder' ),
							'on'  => esc_html__( 'Yes', 'et_builder' ),
						),
						'affects' => array(
							'redirect_url',
						),
						'toggle_slug'     => 'redirect',
						'description'     => esc_html__( 'Redirect users after successful form submission.', 'et_builder' ),
					),
					'redirect_url' => array(
						'label'           => esc_html__( 'Redirect URL', 'et_builder' ),
						'type'            => 'text',
						'option_category' => 'configuration',
						'depends_show_if' => 'on',
						'toggle_slug'     => 'redirect',
						'description'     => esc_html__( 'Type the Redirect URL', 'et_builder' ),
					),
					'success_message' => array(
						'label'           => esc_html__( 'Success Message', 'et_builder' ),
						'type'            => 'textarea',
						'option_category' => 'configuration',
						'description'     => esc_html__( 'Type the message you want to display after successful form submission. Leave blank for default', 'et_builder' ),
						'toggle_slug'     => 'main_content',
					),
					'submit_button_text' => array(
						'label'           => esc_html__( 'Submit Button Text', 'et_builder' ),
						'type'            => 'text',
						'option_category' => 'basic_option',
						'description'     => esc_html__( 'Define the text of the form submit button.', 'et_builder' ),
						'toggle_slug'     => 'main_content',
					),
					'form_background_color' => array(
						'label'             => esc_html__( 'Form Background Color', 'et_builder' ),
						'type'              => 'color-alpha',
						'custom_color'      => true,
						'toggle_slug'       => 'background',
					),
					'input_border_radius'   => array(
						'label'             => esc_html__( 'Input Border Radius', 'et_builder' ),
						'type'              => 'range',
						'default'           => '0',
						'range_settings'    => array(
							'min'  => '0',
							'max'  => '100',
							'step' => '1',
						),
						'option_category'   => 'layout',
						'tab_slug'          => 'advanced',
						'toggle_slug'       => 'border',
					),
					'disabled_on' => array(
						'label'           => esc_html__( 'Disable on', 'et_builder' ),
						'type'            => 'multiple_checkboxes',
						'options'         => array(
							'phone'   => esc_html__( 'Phone', 'et_builder' ),
							'tablet'  => esc_html__( 'Tablet', 'et_builder' ),
							'desktop' => esc_html__( 'Desktop', 'et_builder' ),
						),
						'additional_att'  => 'disable_on',
						'option_category' => 'configuration',
						'description'     => esc_html__( 'This will disable the module on selected devices', 'et_builder' ),
						'tab_slug'        => 'custom_css',
						'toggle_slug'     => 'visibility',
					),
					'admin_label' => array(
						'label'       => esc_html__( 'Admin Label', 'et_builder' ),
						'type'        => 'text',
						'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
						'toggle_slug' => 'admin_label',
					),
					'module_id' => array(
						'label'           => esc_html__( 'CSS ID', 'et_builder' ),
						'type'            => 'text',
						'option_category' => 'configuration',
						'tab_slug'        => 'custom_css',
						'toggle_slug'     => 'classes',
						'option_class'    => 'et_pb_custom_css_regular',
					),
					'module_class' => array(
						'label'           => esc_html__( 'CSS Class', 'et_builder' ),
						'type'            => 'text',
						'option_category' => 'configuration',
						'tab_slug'        => 'custom_css',
						'toggle_slug'     => 'classes',
						'option_class'    => 'et_pb_custom_css_regular',
					),
				);
				return $fields;
			}

			function predefined_child_modules() {
				$output = sprintf(
					'[et_pb_contact_field field_title="%1$s" field_type="input" field_id="name" required_mark="on" fullwidth_field="off" /][et_pb_contact_field field_title="%2$s" field_type="email" field_id="email_address" required_mark="on" fullwidth_field="off" /][et_pb_contact_field field_title="%3$s" field_type="input" field_id="phone_number" required_mark="off" fullwidth_field="off" /][et_pb_contact_field field_title="%4$s" field_type="text" field_id="message" required_mark="on" fullwidth_field="on" /]',
					esc_attr__( 'Namn:', 'et_builder' ),
					esc_attr__( 'E-postaddress:', 'et_builder' ),
					esc_attr__( 'Telefon:', 'et_builder' ),
					esc_attr__( 'Meddelande:', 'et_builder' )
				);

				return $output;
			}

			function shortcode_callback( $atts, $content = null, $function_name ) {
				$module_id             = $this->shortcode_atts['module_id'];
				$module_class          = $this->shortcode_atts['module_class'];
				$email                 = $this->shortcode_atts['email'];
				$sender_email_address  = $this->shortcode_atts['sender_email_address'];
				$email_copy  		   = $this->shortcode_atts['email_copy'];
				$title                 = $this->shortcode_atts['title'];
				$form_field_text_color = $this->shortcode_atts['form_field_text_color'];
				$form_background_color = $this->shortcode_atts['form_background_color'];
				$input_border_radius   = $this->shortcode_atts['input_border_radius'];
				$button_custom         = $this->shortcode_atts['custom_button'];
				$custom_icon           = $this->shortcode_atts['button_icon'];
				$submit_button_text    = $this->shortcode_atts['submit_button_text'];
				$custom_message        = $this->shortcode_atts['custom_message'];
				$use_redirect          = $this->shortcode_atts['use_redirect'];
				$redirect_url          = $this->shortcode_atts['redirect_url'];
				$success_message       = $this->shortcode_atts['success_message'];

				global $et_pb_contact_form_num;

				$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

				if ( '' !== $form_field_text_color ) {
					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '%%order_class%% .input[type="checkbox"]:checked + label i:before',
						'declaration' => sprintf(
							'color: %1$s%2$s;',
							esc_html( $form_field_text_color ),
							et_is_builder_plugin_active() ? ' !important' : ''
						),
					) );

					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '%%order_class%% .input[type="radio"]:checked + label i:before',
						'declaration' => sprintf(
							'background-color: %1$s%2$s;',
							esc_html( $form_field_text_color ),
							et_is_builder_plugin_active() ? ' !important' : ''
						),
					) );
				}

				if ( '' !== $form_background_color ) {
					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '%%order_class%% .input, %%order_class%% .input[type="checkbox"] + label i, %%order_class%% .input[type="radio"] + label i',
						'declaration' => sprintf(
							'background-color: %1$s%2$s;',
							esc_html( $form_background_color ),
							et_is_builder_plugin_active() ? ' !important' : ''
						),
					) );
				}

				if ( ! in_array( $input_border_radius, array( '', '0' ) ) ) {
					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '%%order_class%% .input, %%order_class%% .input[type="checkbox"] + label i',
						'declaration' => sprintf(
							'-moz-border-radius: %1$s%2$s; -webkit-border-radius: %1$s%2$s; border-radius: %1$s%2$s;',
							esc_html( et_builder_process_range_value( $input_border_radius ) ),
							et_is_builder_plugin_active() ? ' !important' : ''
						),
					) );
				}

				$success_message = '' !== $success_message ? $success_message : esc_html__( 'Thanks for contacting us', 'et_builder' );

				$et_pb_contact_form_num = $this->shortcode_callback_num();

				$content = $this->shortcode_content;

				$et_error_message = '';
				$et_contact_error = false;
				$current_form_fields = isset( $_POST['et_pb_contact_email_fields_' . $et_pb_contact_form_num] ) ? $_POST['et_pb_contact_email_fields_' . $et_pb_contact_form_num] : '';
				$contact_email = '';
				$processed_fields_values = array();

				$nonce_result = isset( $_POST['_wpnonce-et-pb-contact-form-submitted'] ) && wp_verify_nonce( $_POST['_wpnonce-et-pb-contact-form-submitted'], 'et-pb-contact-form-submit' ) ? true : false;

				// check that the form was submitted and et_pb_contactform_validate field is empty to protect from spam
				if ( $nonce_result && isset( $_POST['et_pb_contactform_submit_' . $et_pb_contact_form_num] ) && empty( $_POST['et_pb_contactform_validate_' . $et_pb_contact_form_num] ) ) {
					if ( '' !== $current_form_fields ) {
						$fields_data_json = str_replace( '\\', '' ,  $current_form_fields );
						$fields_data_array = json_decode( $fields_data_json, true );

						// check all fields on current form and generate error message if needed
						if ( ! empty( $fields_data_array ) ) {
							foreach( $fields_data_array as $index => $value ) {
								// check all the required fields, generate error message if required field is empty
								if ( 'required' === $value['required_mark'] && empty( $_POST[ $value['field_id'] ] ) ) {
									$et_error_message .= sprintf( '<p class="et_pb_contact_error_text">%1$s</p>', esc_html__( 'Make sure you fill in all required fields.', 'et_builder' ) );
									$et_contact_error = true;
									continue;
								}

								// additional check for email field
								if ( 'email' === $value['field_type'] && 'required' === $value['required_mark'] && ! empty( $_POST[ $value['field_id'] ] ) ) {
									$contact_email = sanitize_email( $_POST[ $value['field_id'] ] );
									if ( ! is_email( $contact_email ) ) {
										$et_error_message .= sprintf( '<p class="et_pb_contact_error_text">%1$s</p>', esc_html__( 'Invalid Email.', 'et_builder' ) );
										$et_contact_error = true;
									}
								}

								// prepare the array of processed field values in convenient format
								if ( false === $et_contact_error ) {
									$processed_fields_values[ $value['original_id'] ]['value'] = isset( $_POST[ $value['field_id'] ] ) ? $_POST[ $value['field_id'] ] : '';
									$processed_fields_values[ $value['original_id'] ]['label'] = $value['field_label'];
								}
							}
						}
					} else {
						$et_error_message .= sprintf( '<p class="et_pb_contact_error_text">%1$s</p>', esc_html__( 'Make sure you fill in all required fields.', 'et_builder' ) );
						$et_contact_error = true;
					}
				} else {
					if ( false === $nonce_result && isset( $_POST['et_pb_contactform_submit_' . $et_pb_contact_form_num] ) && empty( $_POST['et_pb_contactform_validate_' . $et_pb_contact_form_num] ) ) {
						$et_error_message .= sprintf( '<p class="et_pb_contact_error_text">%1$s</p>', esc_html__( 'Please refresh the page and try again.', 'et_builder' ) );
					}
					$et_contact_error = true;
				}

				if ( ! $et_contact_error && $nonce_result ) {
					$et_email_to = '' !== $email
						? $email
						: get_site_option( 'admin_email' );

					$et_email_to_email_copy = $processed_fields_values['email_address']['value'];

					$et_site_name = get_option( 'blogname' );

					$contact_name = isset( $processed_fields_values['name'] ) ? stripslashes( sanitize_text_field( $processed_fields_values['name']['value'] ) ) : '';

					if ( '' !== $custom_message ) {
						$message_pattern = et_builder_convert_line_breaks( $custom_message, "\r\n" );

						// insert the data from contact form into the message pattern
						foreach ( $processed_fields_values as $key => $value ) {
							$message_pattern = str_ireplace( "%%{$key}%%", $value['value'], $message_pattern );
						}
					} else {
						// use default message pattern if custom pattern is not defined
						$message_pattern = isset( $processed_fields_values['message']['value'] ) ? $processed_fields_values['message']['value'] : '';

						// Add all custom fields into the message body by default
						foreach ( $processed_fields_values as $key => $value ) {
							if ( ! in_array( $key, array( 'message', 'name', 'email' ) ) ) {
								$message_pattern .= "\r\n";
								$message_pattern .= sprintf(
									'%1$s: %2$s',
									'' !== $value['label'] ? $value['label'] : $key,
									$value['value']
								);
							}
						}
					}

					
					$http_host = str_replace( 'www.', '', $_SERVER['HTTP_HOST'] );

					$header_sender_email_address = '';
					if(isset($sender_email_address) && !empty($sender_email_address)) {
						$header_sender_email_address = $sender_email_address;
					} else if (isset(get_option( 'postman_options' )['envelope_sender']) && !empty(get_option( 'postman_options' )['envelope_sender'])) {
						$header_sender_email_address = get_option( 'postman_options' )['envelope_sender'];
					} else {
						$header_sender_email_address = 'info@'.$http_host;
					} 

					// $headers[] = "From: \"{$contact_name}\" <mail@{$http_host}>";
					$headers[] = "From: \"{$contact_name}\" <{$header_sender_email_address}>";
					$headers[] = "Reply-To: \"{$contact_name}\" <{$contact_email}>";

					add_filter( 'et_get_safe_localization', 'et_allow_ampersand' );

					$email_message = trim( stripslashes( wp_strip_all_tags( $message_pattern ) ) );

					wp_mail( apply_filters( 'et_contact_page_email_to', $et_email_to ),
						et_get_safe_localization( sprintf(
							__( 'New Message From %1$s%2$s', 'et_builder' ),
							sanitize_text_field( html_entity_decode( $et_site_name ) ),
							( '' !== $title ? sprintf( _x( ' - %s', 'contact form title separator', 'et_builder' ), sanitize_text_field( html_entity_decode( $title ) ) ) : '' )
						) ),
						! empty( $email_message ) ? $email_message : ' ',
						apply_filters( 'et_contact_page_headers', $headers, $contact_name, $contact_email )
					);

					if(isset($email_copy) && $email_copy == 'on') {	
						wp_mail( apply_filters( 'et_contact_page_email_to', $et_email_to_email_copy ),
							et_get_safe_localization( sprintf(
								__( 'New Message From %1$s%2$s', 'et_builder' ),
								sanitize_text_field( html_entity_decode( $et_site_name ) ),
								( '' !== $title ? sprintf( _x( ' - %s', 'contact form title separator', 'et_builder' ), sanitize_text_field( html_entity_decode( $title ) ) ) : '' )
							) ),
							! empty( $email_message ) ? $email_message : ' ',
							apply_filters( 'et_contact_page_headers', $headers, $contact_name, $contact_email )
						);
					}

					remove_filter( 'et_get_safe_localization', 'et_allow_ampersand' );

					// $et_error_message = sprintf( '<p>%1$s</p>', esc_html( $success_message ) );
					$et_error_message = sprintf( '<p>%1$s</p>', $success_message );
				}

				$form = '';

				$et_pb_email_copy = sprintf( '
					<!--<div class="et_pb_contact_right">
						<p class="clearfix">
							<label><input type="checkbox" name="et_pb_contact_email_copy_%1$s" value="1" /> Mejla mig en kopia</label>
						</p>
					</div>-->
					<div class="et_pb_contact_right">
						<p class="clearfix">
							<label for="et_pb_contact_email_copy_%1$s" class="et_pb_contact_form_label">Mejla mig en kopia</label>
							<input type="checkbox" id="et_pb_contact_email_copy_%1$s_checkbox" class="input" value="not checked" data-required_mark="required" data-field_type="checkbox" data-original_id="tess">
							<label for="et_pb_contact_email_copy_%1$s_checkbox"><i></i>Mejla mig en kopia</label>
							<input id="et_pb_contact_email_copy_%1$s" name="et_pb_contact_email_copy_%1$s" type="text" value="not checked" data-checked="checked" data-unchecked="not checked">
						</p>
					</div> <!-- .et_pb_contact_right -->
					',
					esc_attr( $et_pb_contact_form_num )
				);

				if ( '' === trim( $content ) ) {
					$content = do_shortcode( $this->predefined_child_modules() );
				}

				if ( $et_contact_error ) {
					// Make sure submit button text is not just a space
					$submit_button_text = trim( $submit_button_text );

					// We can't use `empty( trim() )` because that throws
					// an error on old(er) PHP versions
					if ( empty( $submit_button_text ) ) {
						$submit_button_text = __( 'Submit', 'et_builder' );
					}
					$form = sprintf( '
						<div class="et_pb_contact">
							<form class="et_pb_contact_form clearfix" method="post" action="%1$s">
								%8$s
								<input type="hidden" value="et_contact_proccess" name="et_pb_contactform_submit_%7$s">
								<input type="text" value="" name="et_pb_contactform_validate_%7$s" class="et_pb_contactform_validate_field" />
								<div class="et_contact_bottom_container">
									%2$s
									<button type="submit" class="et_pb_contact_submit et_pb_button%6$s"%5$s>%3$s</button>
								</div>
								%4$s
							</form>
						</div> <!-- .et_pb_contact -->',
						esc_url( get_permalink( get_the_ID() ) ),
						(  'on' === $email_copy ? $et_pb_email_copy : '' ),
						esc_html( $submit_button_text ),
						wp_nonce_field( 'et-pb-contact-form-submit', '_wpnonce-et-pb-contact-form-submitted', true, false ),
						'' !== $custom_icon && 'on' === $button_custom ? sprintf(
							' data-icon="%1$s"',
							esc_attr( et_pb_process_font_icon( $custom_icon ) )
						) : '',
						'' !== $custom_icon && 'on' === $button_custom ? ' et_pb_custom_button_icon' : '',
						esc_attr( $et_pb_contact_form_num ),
						$content
					);
				}

				$output = sprintf( '
					<div id="%4$s" class="et_pb_module et_pb_contact_form_container clearfix%5$s" data-form_unique_num="%6$s"%7$s>
						%1$s
						<div class="et-pb-contact-message">%2$s</div>
						%3$s
					</div> <!-- .et_pb_contact_form_container -->
					',
					( '' !== $title ? sprintf( '<h1 class="et_pb_contact_main_title">%1$s</h1>', esc_html( $title ) ) : '' ),
					'' !== $et_error_message ? $et_error_message : '',
					$form,
					( '' !== $module_id
						? esc_attr( $module_id )
						: esc_attr( 'et_pb_contact_form_' . $et_pb_contact_form_num )
					),
					( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
					esc_attr( $et_pb_contact_form_num ),
					'on' === $use_redirect && '' !== $redirect_url ? sprintf( ' data-redirect_url="%1$s"', esc_attr( $redirect_url ) ) : ''
				);

				return $output;
			}
		}
		$et_builder_module_nitea_contact_form = new ET_Builder_Module_Nitea_Contact_Form();
		add_shortcode( 'et_pb_contact_form', array($et_builder_module_nitea_contact_form, '_shortcode_callback') );
	}

}
add_action('et_builder_ready', 'ex_divi_child_theme_setup');