<?php

namespace PixelYourSite;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function buildAdminUrl( $page, $tab = '', $action = '', $extra = array() ) {
    
    $args = array( 'page' => $page );
    
    if ( $tab ) {
        $args['tab'] = $tab;
    }
    
    if ( $action ) {
        $args['action'] = $action;
    }
    
    $args = array_merge( $args, $extra );
    
    return add_query_arg( $args, admin_url( 'admin.php' ) );
    
}

function getCurrentAdminPage() {
    if(!empty($_GET['page'])) {
        return sanitize_text_field($_GET['page']);
    }
    return '';

}

function getCurrentAdminTab() {
    if(!empty( $_GET['tab'] ) ) {
        return sanitize_text_field($_GET['tab']);
    }
    return 'general';
}

function getCurrentAdminAction() {
    if(!empty( $_GET['action'] ) ) {
        return sanitize_text_field($_GET['action']);
    }
    return '';
}

function getAdminPrimaryNavTabs() {
    
    $tabs = array(
        'general' => array(
            'url'  => buildAdminUrl( 'pixelyoursite' ),
            'name' => 'General',
        ),
        'events'  => array(
            'url'  => buildAdminUrl( 'pixelyoursite', 'events' ),
            'name' => 'Events',
        ),
    );
    
    if ( isWooCommerceActive() ) {
        
        $tabs['woo'] = array(
            'url'  => buildAdminUrl( 'pixelyoursite', 'woo' ),
            'name' => 'WooCommerce',
        );
        
    }
    
    if ( isEddActive() ) {
        
        $tabs['edd'] = array(
            'url'  => buildAdminUrl( 'pixelyoursite', 'edd' ),
            'name' => 'EasyDigitalDownloads',
        );
        
    }
    
    return $tabs;
    
}

function getAdminSecondaryNavTabs() {
    
    $tabs = array(
        'facebook_settings' => array(
            'url'  => buildAdminUrl( 'pixelyoursite', 'facebook_settings' ),
            'name' => 'Facebook Settings',
        ),
        'ga_settings'       => array(
            'url'  => buildAdminUrl( 'pixelyoursite', 'ga_settings' ),
            'name' => 'Google Analytics Settings',
        ),
    );
    
    $tabs = apply_filters( 'pys_admin_secondary_nav_tabs', $tabs );
    
    $tabs['superpack_settings'] = array(
        'url'  => buildAdminUrl( 'pixelyoursite', 'superpack_settings' ),
        'name' => 'Super Pack Settings',
    );
    
    $tabs['head_footer'] = array(
        'url'  => buildAdminUrl( 'pixelyoursite', 'head_footer' ),
        'name' => 'Head & Footer',
    );
    
    $tabs['gdpr'] = array(
        'url'  => buildAdminUrl( 'pixelyoursite', 'gdpr' ),
        'name' => 'GDPR',
    );
    
    return $tabs;
    
}

function cardCollapseBtn() {
    echo '<span class="card-collapse"><i class="fa fa-sliders" aria-hidden="true"></i></span>';
}

/**
 * @param string   $key
 * @param Settings $settings
 */
function renderCollapseTargetAttributes( $key, $settings ) {
    echo 'class="pys_' . $settings->getSlug() . '_' . esc_attr( $key ) . '_panel"';
}

function manageAdminPermissions() {
    global $wp_roles;
    
    $roles = PYS()->getOption( 'admin_permissions', array( 'administrator' ) );
    
    foreach ( $wp_roles->roles as $role => $options ) {
        
        if ( in_array( $role, $roles ) ) {
            $wp_roles->add_cap( $role, 'manage_pys' );
        } else {
            $wp_roles->remove_cap( $role, 'manage_pys' );
        }
        
    }

	if (!isPixelCogActive() ) {

		$event_purchase = get_option('woo_purchase_value_cog');
		$content_value = get_option('woo_content_value_cog');
		$add_to_cart_value = get_option('woo_add_to_cart_value_cog');
		$initiate_checkout_value = get_option('woo_initiate_checkout_value_cog');

		$event_purchase_old = get_option('woo_purchase_value_option');
		$content_value_old = get_option('woo_view_content_value_option');
		$add_to_cart_value_old = get_option('woo_add_to_cart_value_option');
		$initiate_checkout_value_old = get_option('woo_initiate_checkout_value_option');

		if (!$event_purchase) add_option('woo_purchase_value_cog', $event_purchase_old);
		if (!$content_value) add_option('woo_content_value_cog', $content_value_old);
		if (!$add_to_cart_value) add_option('woo_add_to_cart_value_cog', $add_to_cart_value_old);
		if (!$initiate_checkout_value) add_option('woo_initiate_checkout_value_cog', $initiate_checkout_value_old);

		$PixelCogDeActive = array(
			'woo_view_content_value_option' => 'price',
			'woo_add_to_cart_value_option' => 'price',
			'woo_initiate_checkout_value_option' => 'price',
			'woo_purchase_value_option' => 'price'
		);
		// update settings
		PYS()->updateOptions( $PixelCogDeActive );
		PYS()->reloadOptions();
	} else {
		$event_purchase = get_option('woo_purchase_value_cog');
		$content_value = get_option('woo_content_value_cog');
		$add_to_cart_value = get_option('woo_add_to_cart_value_cog');
		$initiate_checkout_value = get_option('woo_initiate_checkout_value_cog');

		if ($initiate_checkout_value == 'cog') {
			$PixelCogDeActive = array(
				'woo_initiate_checkout_value_option' => 'cog'
			);
			// update settings
			PYS()->updateOptions( $PixelCogDeActive );
			PYS()->reloadOptions();
		}

		if ($add_to_cart_value == 'cog') {
			$PixelCogDeActive = array(
				'woo_add_to_cart_value_option' => 'cog'
			);
			// update settings
			PYS()->updateOptions( $PixelCogDeActive );
			PYS()->reloadOptions();
		}

		if ($content_value == 'cog') {
			$PixelCogDeActive = array(
				'woo_view_content_value_option' => 'cog',
			);
			// update settings
			PYS()->updateOptions( $PixelCogDeActive );
			PYS()->reloadOptions();
		}

		if ($event_purchase == 'cog') {
			$PixelCogDeActive = array(
				'woo_purchase_value_option' => 'cog',
			);
			// update settings
			PYS()->updateOptions( $PixelCogDeActive );
			PYS()->reloadOptions();
		}
	}
    
}

function renderPopoverButton( $popover_id ) {
    ?>

    <button type="button" class="btn btn-link" role="button" data-toggle="pys-popover" data-trigger="focus"
            data-placement="right" data-popover_id="<?php esc_attr_e( $popover_id ); ?>">
        <i class="fa fa-info-circle" aria-hidden="true"></i>
    </button>
    
    <?php
}

function renderExternalHelpIcon( $url ) {
    ?>

    <a class="btn btn-link" target="_blank" href="<?php echo esc_url( $url ); ?>">
        <i class="fa fa-info-circle" aria-hidden="true"></i>
    </a>
    
    <?php
}

function purgeCache() {
    
    if ( function_exists( 'w3tc_pgcache_flush' ) ) {    // W3 Total Cache
        
        w3tc_pgcache_flush();
        
    } elseif ( function_exists( 'wp_cache_clean_cache' ) ) {    // WP Super Cache
        global $file_prefix, $supercachedir;
        
        if ( empty( $supercachedir ) && function_exists( 'get_supercache_dir' ) ) {
            $supercachedir = get_supercache_dir();
        }
        
        wp_cache_clean_cache( $file_prefix );
        
    } elseif ( class_exists( 'WpeCommon' ) ) {
        
        if ( method_exists( 'WpeCommon', 'purge_memcached' ) ) {
            \WpeCommon::purge_memcached();
        }
        
        //	    if ( method_exists( 'WpeCommon', 'clear_maxcdn_cache' ) ) {
        //		    \WpeCommon::clear_maxcdn_cache();
        //	    }
        
        if ( method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
            \WpeCommon::purge_varnish_cache();
        }
        
    } elseif ( method_exists( 'WpFastestCache', 'deleteCache' ) ) {
        global $wp_fastest_cache;
        
        if ( ! empty( $wp_fastest_cache ) ) {
            $wp_fastest_cache->deleteCache();
        }
        
    } elseif ( function_exists( 'sg_cachepress_purge_cache' ) ) {
        
        sg_cachepress_purge_cache();
        
    }
    
}

function adminIncompatibleVersionNotice( $pluginName, $minVersion ) {
    ?>

    <div class="notice notice-error">
        <p>You are using incompatible version of <?php esc_html_e( $pluginName ); ?>. PixelYourSite requires at
            least <?php esc_html_e( $pluginName ); ?> <?php echo $minVersion; ?>. Please, update to
            latest version.</p>
    </div>
    
    <?php
}

function adminRenderNotices() {
    
    if ( ! current_user_can( 'manage_pys' ) ) {
        return;
    }
    
    /**
     * Expiration notices
     */
    
    $now = time();
    
    if ( isPinterestActive( false ) && isPinterestVersionIncompatible() ) {
        adminIncompatibleVersionNotice( 'PixelYourSite Pinterest Add-On', PYS_FREE_PINTEREST_MIN_VERSION );
    } elseif ( isPinterestActive() ) {
        $expire_at = Pinterest()->getOption( 'license_expires' );
        
        if ( $expire_at && $now > $expire_at ) {
            adminRenderLicenseExpirationNotice( Pinterest() );
        }
    }

    if ( isBingActive( false ) && isBingVersionIncompatible() ) {
        adminIncompatibleVersionNotice( 'PixelYourSite Bing Add-On', PYS_FREE_BING_MIN_VERSION );
    } elseif ( isBingActive() ) {
        $expire_at = Bing()->getOption( 'license_expires' );

        if ( $expire_at && $now > $expire_at ) {
            adminRenderLicenseExpirationNotice( Bing() );
        }
    }

    /**
     * Pixel ID notices
     */
    
    $facebook_pixel_id = Facebook()->getOption( 'pixel_id' );
    
    if ( Facebook()->enabled() && empty( $facebook_pixel_id ) ) {
        $no_facebook_pixels = true;
    } else {
        $no_facebook_pixels = false;
    }
    
    $ga_tracking_id = GA()->getOption( 'tracking_id' );
    
    if ( GA()->enabled() && empty( $ga_tracking_id ) ) {
        $no_ga_pixels = true;
    } else {
        $no_ga_pixels = false;
    }
    
    $pinterest_pixel_id = Pinterest()->getOption( 'pixel_id' );
    $pinterest_license_status = Pinterest()->getOption( 'license_status' );
    
    if ( isPinterestActive() && Pinterest()->enabled()
         && ! empty( $pinterest_license_status ) // license active or was active before
         && empty( $pinterest_pixel_id ) ) {
        $no_pinterest_pixels = true;
    } else {
        $no_pinterest_pixels = false;
    }
    
    if ( isPinterestActive() ) {
        
        if ( $no_facebook_pixels && $no_ga_pixels && $no_pinterest_pixels ) {
            adminRenderNoPixelsNotice();
        } else {
            
            if ( $no_facebook_pixels ) {
                adminRenderNoPixelNotice( Facebook() );
            }
            
            if ( $no_ga_pixels ) {
                adminRenderNoPixelNotice( GA() );
            }
            
            if ( $no_pinterest_pixels ) {
                adminRenderNoPixelNotice( Pinterest() );
            }
            
        }

        // show notice if licence was never activated
        if (Pinterest()->enabled() && empty($pinterest_license_status)) {
            adminRenderActivatePinterestLicence();
        }
        
    } else {
        
        if ( $no_facebook_pixels && $no_ga_pixels ) {
            adminRenderNoPixelsNotice();
        } else {
            
            if ( $no_facebook_pixels ) {
                adminRenderNoPixelNotice( Facebook() );
            }
            
            if ( $no_ga_pixels ) {
                adminRenderNoPixelNotice( GA() );
            }
            
        }
        
    }

    if ( isBingActive() ) {

        $bing_license_status = Bing()->getOption( 'license_status' );

        // show notice if licence was never activated
        if (Bing()->enabled() && empty($bing_license_status)) {
            adminRenderActivateBingLicence();
        }

    }
    
    /**
     * GDPR
     */
    if ( isCookieLawInfoPluginActivated() && ! PYS()->getOption( 'gdpr_ajax_enabled' ) ) {
        adminGdprAjaxNotEnabledNotice();
    }
}

/**
 * @param Plugin|Settings $plugin
 */
function adminRenderLicenseExpirationNotice( $plugin ) {
    
    if ( 'pixelyoursite' == getCurrentAdminPage() ) {
        return; // do not show notice on plugin pages
    }
    
    $slug = $plugin->getSlug();
    $user_id = get_current_user_id();
    
    // show only if never dismissed or dismissed more than a week ago
    $meta_key = 'pys_' . $slug . '_expiration_notice_dismissed_at';
    $dismissed_at = get_user_meta( $user_id, $meta_key );
    if ( $dismissed_at ) {
        
        if ( is_array( $dismissed_at ) ) {
            $dismissed_at = reset( $dismissed_at );
        }
        
        $week_ago = time() - WEEK_IN_SECONDS;
        
        if ( $week_ago < $dismissed_at ) {
            return;
        }
        
    }
    
    $license_key = $plugin->getOption( 'license_key' );
    
    ?>

    <div class="notice notice-error is-dismissible pys_<?php esc_attr_e( $slug ); ?>_expiration_notice">
        <p>Your <?php echo $plugin->getPluginName(); ?> license key is expired, so you no longer get any
            updates. Don't miss our latest improvements and make sure that everything works smoothly. <a
                    target="_blank" href="https://www.pixelyoursite.com/checkout/?edd_license_key=<?php esc_attr_e(
                $license_key ); ?>&utm_campaign=admin&utm_source=licenses&utm_medium=renew">Click here to update
                now.</a></p>
    </div>

    <script type="text/javascript">
        jQuery(document).on('click', '.pys_<?php esc_attr_e( $slug ); ?>_expiration_notice .notice-dismiss', function () {

            jQuery.ajax({
                url: ajaxurl,
                data: {
                    action: 'pys_notice_dismiss',
                    nonce: '<?php esc_attr_e( wp_create_nonce( 'pys_notice_dismiss' ) ); ?>',
                    user_id: '<?php esc_attr_e( $user_id ); ?>',
                    addon_slug: '<?php esc_attr_e( $slug ); ?>',
                    meta_key: 'expiration_notice'
                }
            })

        })
    </script>
    
    <?php
}

add_action( 'wp_ajax_pys_notice_dismiss', 'PixelYourSite\adminNoticeDismissHandler' );
function adminNoticeDismissHandler() {
    
    if ( empty( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'pys_notice_dismiss' ) ) {
        return;
    }
    
    if ( empty( $_REQUEST['user_id'] ) || empty( $_REQUEST['addon_slug'] ) || empty( $_REQUEST['meta_key'] ) ) {
        return;
    }
    
    // save time when notice was dismissed
    $meta_key = 'pys_' . sanitize_text_field( $_REQUEST['addon_slug'] ) . '_' . sanitize_text_field( $_REQUEST['meta_key'] ) . '_dismissed_at';
    $userId = sanitize_text_field( $_REQUEST['user_id'] );
    update_user_meta($userId, $meta_key, time() );
    
}

function adminRenderActivatePinterestLicence() {

    if ( 'pixelyoursite_licenses' == getCurrentAdminPage() ) {
        return; // do not show notice licenses page
    }

    ?>

    <div class="notice notice-error">
        <p>Activate your Pinterest add-on license: <a href="<?php echo esc_url( buildAdminUrl( 'pixelyoursite_licenses' ) ); ?>">click here</a>.</p>
    </div>

    <?php
}

function adminRenderActivateBingLicence() {

    if ( 'pixelyoursite_licenses' == getCurrentAdminPage() ) {
        return; // do not show notice licenses page
    }

    ?>

    <div class="notice notice-error">
        <p>Activate your PixelYourSite Microsoft UET (Bing) add-on license: <a href="<?php echo esc_url( buildAdminUrl( 'pixelyoursite_licenses' ) ); ?>">click here</a>.</p>
    </div>

    <?php
}

function adminRenderNoPixelsNotice() {
    
    if ( 'pixelyoursite' == getCurrentAdminPage() ) {
        return; // do not show notice on plugin pages
    }
    
    $user_id = get_current_user_id();
    
    // do not show dismissed notice
    $meta_key = 'pys_core_no_pixels_dismissed_at';
    $dismissed_at = get_user_meta( $user_id, $meta_key );
    if ( $dismissed_at ) {
        return;
    }
    
    ?>

    <div class="notice notice-warning is-dismissible pys_core_no_pixels_notice">
        <p>You have no pixel configured with PixelYourSite. You can add the Facebook Pixel, Google Analytics or the
            Pinterest Tag. <a href="<?php echo esc_url( buildAdminUrl( 'pixelyoursite' ) ); ?>">Start tracking
                everything now</a></p>
    </div>

    <script type="text/javascript">
        jQuery(document).on('click', '.pys_core_no_pixels_notice .notice-dismiss', function () {

            jQuery.ajax({
                url: ajaxurl,
                data: {
                    action: 'pys_notice_dismiss',
                    nonce: '<?php esc_attr_e( wp_create_nonce( 'pys_notice_dismiss' ) ); ?>',
                    user_id: '<?php esc_attr_e( $user_id ); ?>',
                    addon_slug: 'core',
                    meta_key: 'no_pixels'
                }
            })

        })
    </script>
    
    <?php
}

/**
 * @param Plugin|Settings $plugin
 */
function adminRenderNoPixelNotice( $plugin ) {
    
    if ( 'pixelyoursite' == getCurrentAdminPage() ) {
        return; // do not show notice on plugin pages
    }
    
    $slug = $plugin->getSlug();
    $user_id = get_current_user_id();
    
    // do not show dismissed notice
    $meta_key = 'pys_' . $slug . '_no_pixel_dismissed_at';
    $dismissed_at = get_user_meta( $user_id, $meta_key );
    if ( $dismissed_at ) {
        return;
    }
    
    ?>

    <div class="notice notice-warning is-dismissible pys_<?php esc_attr_e( $slug ); ?>_no_pixel_notice">
        <?php if ( $slug == 'facebook' ) : ?>

            <p>Add your Facebook pixel ID and start tracking everything with PixelYourSite. <a
                        href="<?php echo esc_url( buildAdminUrl( 'pixelyoursite' ) ); ?>">Click Here</a></p>
        
        <?php elseif ( $slug == 'ga' && ( isWooCommerceActive() || isEddActive() ) ) : ?>

            <p>Add your Google Analytics tracking ID inside PixelYourSite and start tracking everything. Enhanced
                Ecommerce is fully supported for WooCommerce or Easy Digital Downloads. <a
                        href="<?php echo esc_url( buildAdminUrl( 'pixelyoursite' ) ); ?>">Click Here</a></p>

            <p>(If you use another Google Analytics plugin, disable it in order to avoid conflicts)</p>
        
        <?php elseif ( $slug == 'ga' && ! isWooCommerceActive() && ! isEddActive() ) : ?>

            <p>Add your Google Analytics ID inside PixelYourSite and start tracking everything. <a
                        href="<?php echo esc_url( buildAdminUrl( 'pixelyoursite' ) ); ?>">Click Here</a></p>

            <p>(If you use another Google Analytics plugin, disable it in order to avoid conflicts)</p>
        
        <?php elseif ( $slug == 'pinterest' ) : ?>

            <p>Add your Pinterest pixel ID and start tracking everything with PixelYourSite. <a
                        href="<?php echo esc_url( buildAdminUrl( 'pixelyoursite' ) ); ?>">Click Here</a></p>
        
        <?php endif; ?>
    </div>

    <script type="text/javascript">
        jQuery(document).on('click', '.pys_<?php esc_attr_e( $slug ); ?>_no_pixel_notice .notice-dismiss', function () {

            jQuery.ajax({
                url: ajaxurl,
                data: {
                    action: 'pys_notice_dismiss',
                    nonce: '<?php esc_attr_e( wp_create_nonce( 'pys_notice_dismiss' ) ); ?>',
                    user_id: '<?php esc_attr_e( $user_id ); ?>',
                    addon_slug: '<?php esc_attr_e( $slug ); ?>',
                    meta_key: 'no_pixel'
                }
            })

        })
    </script>
    
    <?php
}

function renderDummyTextInput( $placeholder = '' ) {
    ?>

    <input type="text" disabled="disabled" placeholder="<?php esc_html_e( $placeholder ); ?>" class="form-control">
    
    <?php
}

function renderDummyNumberInput() {
    ?>

    <input type="number" disabled="disabled" min="0" max="100" class="form-control">
    
    <?php
}

function renderDummySwitcher() {
    ?>

    <div class="custom-switch disabled">
        <input type="checkbox" value="1" disabled="disabled" class="custom-switch-input">
        <label class="custom-switch-btn"></label>
    </div>
    
    <?php
}

function renderDummyCheckbox( $label, $with_pro_badge = false ) {
    ?>

    <label class="custom-control custom-checkbox <?php echo $with_pro_badge ? 'custom-checkbox-badge' : ''; ?>">
        <input type="checkbox" value="1"
               class="custom-control-input" disabled="disabled">
        <span class="custom-control-indicator"></span>
        <span class="custom-control-description">
            <?php echo wp_kses_post( $label ); ?>
            <?php if ( $with_pro_badge ) {
                renderProBadge();
            } ?>
        </span>
    </label>
    
    <?php
}

function renderDummyRadioInput( $label, $checked = false ) {
    ?>

    <label class="custom-control custom-radio">
        <input type="radio" disabled="disabled"
               class="custom-control-input" <?php checked( $checked ); ?>>
        <span class="custom-control-indicator"></span>
        <span class="custom-control-description"><?php echo wp_kses_post( $label ); ?></span>
    </label>
    
    <?php
}

function renderDummyTagsFields( $tags = array() ) {
    ?>

    <select class="form-control pys-tags-select2" disabled="disabled" style="width: 100%;" multiple>
        
        <?php foreach ( $tags as $tag ) : ?>
            <option value="<?php echo esc_attr( $tag ); ?>" selected>
                <?php echo esc_attr( $tag ); ?>
            </option>
        <?php endforeach; ?>

    </select>
    
    <?php
}

function renderDummySelectInput( $value, $full_width = false ) {
    
    $attr_width = $full_width ? 'width: 100%;' : '';
    
    ?>

    <select class="form-control form-control-sm" disabled="disabled" autocomplete="off" style="<?php esc_attr_e( $attr_width ); ?>">
        <option value="" disabled selected><?php esc_html_e( $value ); ?></option>
    </select>
    
    <?php
}

function renderDummyGoogleAdsConversionLabelInputs() {
    ?>

    <div class="row mt-1 mb-2">
        <div class="col-11 col-offset-left form-inline">
            <label>Add conversion label </label>
            <?php renderDummyTextInput( 'Enter conversion label' ); ?>
            <?php renderProBadge('https://www.pixelyoursite.com/google-ads-tag'); ?>
        </div>
        <div class="col-1">
            <button type="button" class="btn btn-link" role="button" data-toggle="pys-popover" data-trigger="focus"
                    data-placement="right" data-popover_id="google_ads_conversion_label" data-original-title=""
                    title="">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <?php
}

function renderProBadge( $url = null,$label = "Pro Feature" ) {
    
    if ( ! $url ) {
        $url = 'https://www.pixelyoursite.com/';
    }
    
    $url = untrailingslashit( $url ) . '/?utm_source=pys-free-plugin&utm_medium=pro-badge&utm_campaign=pro-feature';
    
    echo '&nbsp;<a href="' . esc_url( $url ) . '" target="_blank" class="badge badge-pill badge-pro">'.$label.' <i class="fa fa-external-link" aria-hidden="true"></i></a>';
}

function renderCogBadge( $label = "You need this plugin" ) {

	$url = 'https://www.pixelyoursite.com/woocommerce-cost-of-goods';

	echo '&nbsp;<a href="' . esc_url( $url ) . '" target="_blank" class="badge badge-pill badge-pro">'.$label.' <i class="fa fa-external-link" aria-hidden="true"></i></a>';
}

function renderSpBadge() {
    echo '&nbsp;<a href="https://www.pixelyoursite.com/super-pack?utm_source=pixelyoursite-free-plugin&utm_medium=plugin&utm_campaign=free-plugin-super-pack" target="_blank" class="badge badge-pill badge-pro">Pro Feature <i class="fa fa-external-link" aria-hidden="true"></i></a>';
}

function renderHfBadge() {
    echo '&nbsp;<a href="https://www.pixelyoursite.com/head-footer-scripts?utm_source=pixelyoursite-free-plugin&utm_medium=plugin&utm_campaign=free-plugin-head-footer" target="_blank" class="badge badge-pill badge-pro">Pro Feature <i class="fa fa-external-link" aria-hidden="true"></i></a>';
}