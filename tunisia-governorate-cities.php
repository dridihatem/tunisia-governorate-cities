<?php
/**
 * Plugin Name: Tunisia Governorate Cities for WooCommerce
 * Plugin URI: https://github.com/dridihatem/tunisia-governorate-cities
 * Description: Adds Tunisian governorates and cities dropdown to WooCommerce checkout with filtering functionality.
 * Version: 1.0.3
 * Author: Dridi Hatem  
 * Author URI: https://dridihatem.dawebcompany.tn
 * Text Domain: tunisia-governorate-cities
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 * WC requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * 
 * @package Tunisia_Governorate_Cities
 * @since 1.0.0
 * @version 1.0.3
 * 
 * Note: This plugin is incompatible with WooCommerce High-Performance Order Storage (HPOS).
 * HPOS must be disabled for this plugin to function properly.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('TGC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TGC_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('TGC_PLUGIN_VERSION', '1.0.3');

// Main plugin class
class Tunisia_Governorate_Cities {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_get_cities', array($this, 'get_cities_ajax'));
        add_action('wp_ajax_nopriv_get_cities', array($this, 'get_cities_ajax'));
    }
    
    public function init() {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
            return;
        }
        
        // Check for WooCommerce HPOS (High-Performance Order Storage) compatibility
        if ($this->is_hpos_enabled()) {
            add_action('admin_notices', array($this, 'hpos_incompatibility_notice'));
            return;
        }
        
        // Add checkout fields
        add_filter('woocommerce_checkout_fields', array($this, 'add_checkout_fields'));
        add_filter('woocommerce_default_address_fields', array($this, 'add_default_address_fields'));
        
        // Add validation
        add_action('woocommerce_checkout_process', array($this, 'validate_checkout_fields'));
        
        // Save custom fields
        add_action('woocommerce_checkout_update_order_meta', array($this, 'save_checkout_fields'));
        
        // Display in admin
        add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'display_admin_order_fields'));
        add_action('woocommerce_admin_order_data_after_shipping_address', array($this, 'display_admin_order_fields'));
    }
    
    public function woocommerce_missing_notice() {
        echo '<div class="error"><p>' . 
             __('Tunisia Governorate Cities requires WooCommerce to be installed and active.', 'tunisia-governorate-cities') . 
             '</p></div>';
    }
    
    /**
     * Check if WooCommerce HPOS (High-Performance Order Storage) is enabled
     */
    public function is_hpos_enabled() {
        // Check if HPOS is enabled via WooCommerce settings
        if (class_exists('Automattic\WooCommerce\Utilities\FeaturesUtil')) {
            // Use a safer method to check HPOS status
            try {
                // Check if the HPOS feature is enabled via options
                $hpos_enabled = get_option('woocommerce_custom_orders_table_enabled', false);
                if ($hpos_enabled) {
                    return true;
                }
                
                // Alternative check using WooCommerce settings
                $wc_settings = get_option('woocommerce_settings_tab_advanced', array());
                if (isset($wc_settings['custom_orders_table_enabled']) && $wc_settings['custom_orders_table_enabled'] === 'yes') {
                    return true;
                }
            } catch (Exception $e) {
                // If any error occurs, assume HPOS is not enabled
                return false;
            }
        }
        
        // Fallback check for older WooCommerce versions
        if (function_exists('wc_get_container')) {
            try {
                $data_store = wc_get_container()->get(\Automattic\WooCommerce\Internal\DataStores\Orders\OrdersTableDataStore::class);
                return $data_store !== null;
            } catch (Exception $e) {
                return false;
            }
        }
        
        // Additional fallback: check if HPOS tables exist
        global $wpdb;
        $table_name = $wpdb->prefix . 'wc_orders';
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
        
        if ($table_exists) {
            // Check if HPOS is actually being used
            $hpos_enabled = get_option('woocommerce_custom_orders_table_enabled', false);
            return $hpos_enabled;
        }
        
        return false;
    }
    
    /**
     * Display notice when HPOS is enabled
     */
    public function hpos_incompatibility_notice() {
        $message = sprintf(
            __('<strong>Tunisia Governorate Cities</strong> is incompatible with WooCommerce High-Performance Order Storage (HPOS). Please disable HPOS in <a href="%s">WooCommerce > Settings > Advanced > Features</a> to use this plugin.', 'tunisia-governorate-cities'),
            admin_url('admin.php?page=wc-settings&tab=advanced&section=features')
        );
        
        echo '<div class="error"><p>' . $message . '</p></div>';
    }
    
    public function enqueue_scripts() {
        if (is_checkout()) {
            // Enqueue Select2 CSS and JS
            wp_enqueue_style(
                'select2',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                array(),
                '4.1.0'
            );
            
            wp_enqueue_script(
                'select2',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                array('jquery'),
                '4.1.0',
                true
            );
            
            // Enqueue plugin CSS
            wp_enqueue_style(
                'tunisia-governorate-cities',
                TGC_PLUGIN_URL . 'assets/css/tunisia-governorate-cities.css',
                array(),
                TGC_PLUGIN_VERSION
            );
            
            // Enqueue plugin JS
            wp_enqueue_script(
                'tunisia-governorate-cities',
                TGC_PLUGIN_URL . 'assets/js/tunisia-governorate-cities.js',
                array('jquery', 'select2'),
                TGC_PLUGIN_VERSION,
                true
            );
            
            // Localize script
            wp_localize_script('tunisia-governorate-cities', 'tgc_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('tgc_ajax_nonce'),
                'select_city' => __('Select City', 'tunisia-governorate-cities'),
                'error_loading' => __('Error loading cities', 'tunisia-governorate-cities'),
                'select2_placeholder' => __('Search...', 'tunisia-governorate-cities'),
                'no_results' => __('No results found', 'tunisia-governorate-cities')
            ));
        }
    }
    
    public function add_checkout_fields($fields) {
        // Add governorate field
        $fields['billing']['billing_governorate'] = array(
            'label' => __('Governorate', 'tunisia-governorate-cities'),
            'placeholder' => __('Select Governorate', 'tunisia-governorate-cities'),
            'required' => true,
            'class' => array('form-row-wide'),
            'clear' => true,
            'type' => 'select',
            'options' => $this->get_governorates()
        );
        
        // Add city field
        $fields['billing']['billing_city'] = array(
            'label' => __('City', 'tunisia-governorate-cities'),
            'placeholder' => __('Select City', 'tunisia-governorate-cities'),
            'required' => true,
            'class' => array('form-row-wide'),
            'clear' => true,
            'type' => 'select',
            'options' => array('' => __('Select Governorate First', 'tunisia-governorate-cities'))
        );
        
        // Add shipping governorate field
        $fields['shipping']['shipping_governorate'] = array(
            'label' => __('Governorate', 'tunisia-governorate-cities'),
            'placeholder' => __('Select Governorate', 'tunisia-governorate-cities'),
            'required' => false,
            'class' => array('form-row-wide'),
            'clear' => true,
            'type' => 'select',
            'options' => $this->get_governorates()
        );
        
        // Add shipping city field
        $fields['shipping']['shipping_city'] = array(
            'label' => __('City', 'tunisia-governorate-cities'),
            'placeholder' => __('Select City', 'tunisia-governorate-cities'),
            'required' => false,
            'class' => array('form-row-wide'),
            'clear' => true,
            'type' => 'select',
            'options' => array('' => __('Select Governorate First', 'tunisia-governorate-cities'))
        );
        
        return $fields;
    }
    
    public function add_default_address_fields($fields) {
        // Remove default city field as we're replacing it
        unset($fields['city']);
        return $fields;
    }
    
    public function get_governorates() {
        return array(
            '' => __('Select Governorate', 'tunisia-governorate-cities'),
            'ariana' => __('Ariana', 'tunisia-governorate-cities'),
            'beja' => __('Béja', 'tunisia-governorate-cities'),
            'ben_arous' => __('Ben Arous', 'tunisia-governorate-cities'),
            'bizerte' => __('Bizerte', 'tunisia-governorate-cities'),
            'gabes' => __('Gabès', 'tunisia-governorate-cities'),
            'gafsa' => __('Gafsa', 'tunisia-governorate-cities'),
            'jendouba' => __('Jendouba', 'tunisia-governorate-cities'),
            'kairouan' => __('Kairouan', 'tunisia-governorate-cities'),
            'kasserine' => __('Kasserine', 'tunisia-governorate-cities'),
            'kebili' => __('Kébili', 'tunisia-governorate-cities'),
            'kef' => __('Le Kef', 'tunisia-governorate-cities'),
            'mahdia' => __('Mahdia', 'tunisia-governorate-cities'),
            'manouba' => __('Manouba', 'tunisia-governorate-cities'),
            'medenine' => __('Médenine', 'tunisia-governorate-cities'),
            'monastir' => __('Monastir', 'tunisia-governorate-cities'),
            'nabeul' => __('Nabeul', 'tunisia-governorate-cities'),
            'sfax' => __('Sfax', 'tunisia-governorate-cities'),
            'sidi_bouzid' => __('Sidi Bouzid', 'tunisia-governorate-cities'),
            'siliana' => __('Siliana', 'tunisia-governorate-cities'),
            'sousse' => __('Sousse', 'tunisia-governorate-cities'),
            'tataouine' => __('Tataouine', 'tunisia-governorate-cities'),
            'tozeur' => __('Tozeur', 'tunisia-governorate-cities'),
            'tunis' => __('Tunis', 'tunisia-governorate-cities'),
            'zaghouan' => __('Zaghouan', 'tunisia-governorate-cities')
        );
    }
    
    public function get_cities($governorate) {
        $cities = array(
            'ariana' => array(
                'ariana' => __('Ariana', 'tunisia-governorate-cities'),
                'la_marsa' => __('La Marsa', 'tunisia-governorate-cities'),
                'carthage' => __('Carthage', 'tunisia-governorate-cities'),
                'sidi_thabet' => __('Sidi Thabet', 'tunisia-governorate-cities'),
                'raoued' => __('Raoued', 'tunisia-governorate-cities'),
                'kalâat_el_andalous' => __('Kalâat el Andalous', 'tunisia-governorate-cities'),
                'ettadhamen' => __('Ettadhamen', 'tunisia-governorate-cities')
            ),
            'beja' => array(
                'beja' => __('Béja', 'tunisia-governorate-cities'),
                'testour' => __('Testour', 'tunisia-governorate-cities'),
                'nefza' => __('Nefza', 'tunisia-governorate-cities'),
                'thibar' => __('Thibar', 'tunisia-governorate-cities'),
                'amdoun' => __('Amdoun', 'tunisia-governorate-cities'),
                'goubellat' => __('Goubellat', 'tunisia-governorate-cities')
            ),
            'ben_arous' => array(
                'ben_arous' => __('Ben Arous', 'tunisia-governorate-cities'),
                'hammam_lif' => __('Hammam Lif', 'tunisia-governorate-cities'),
                'hammam_chott' => __('Hammam Chott', 'tunisia-governorate-cities'),
                'bou_mhel_el_bassatine' => __('Bou Mhel el Bassatine', 'tunisia-governorate-cities'),
                'el_mourouj' => __('El Mourouj', 'tunisia-governorate-cities'),
                'ezzahra' => __('Ezzahra', 'tunisia-governorate-cities'),
                'rades' => __('Radès', 'tunisia-governorate-cities'),
                'fouchana' => __('Fouchana', 'tunisia-governorate-cities'),
                'mohamedia' => __('Mohamedia', 'tunisia-governorate-cities'),
                'mornag' => __('Mornag', 'tunisia-governorate-cities'),
                'khalidia' => __('Khalidia', 'tunisia-governorate-cities')
            ),
            'bizerte' => array(
                'bizerte' => __('Bizerte', 'tunisia-governorate-cities'),
                'mateur' => __('Mateur', 'tunisia-governorate-cities'),
                'menzel_bourguiba' => __('Menzel Bourguiba', 'tunisia-governorate-cities'),
                'ghardimaou' => __('Ghardimaou', 'tunisia-governorate-cities'),
                'sejnane' => __('Sejnane', 'tunisia-governorate-cities'),
                'utique' => __('Utique', 'tunisia-governorate-cities'),
                'ras_jebel' => __('Ras Jebel', 'tunisia-governorate-cities'),
                'el_alya' => __('El Alya', 'tunisia-governorate-cities')
            ),
            'gabes' => array(
                'gabes' => __('Gabès', 'tunisia-governorate-cities'),
                'medenine' => __('Médenine', 'tunisia-governorate-cities'),
                'matmata' => __('Matmata', 'tunisia-governorate-cities'),
                'el_hamma' => __('El Hamma', 'tunisia-governorate-cities'),
                'ghannouch' => __('Ghannouch', 'tunisia-governorate-cities'),
                'oued_ellil' => __('Oued Ellil', 'tunisia-governorate-cities'),
                'nouvelle_matmata' => __('Nouvelle Matmata', 'tunisia-governorate-cities')
            ),
            'gafsa' => array(
                'gafsa' => __('Gafsa', 'tunisia-governorate-cities'),
                'el_ksar' => __('El Ksar', 'tunisia-governorate-cities'),
                'metlaoui' => __('Metlaoui', 'tunisia-governorate-cities'),
                'redeyef' => __('Redeyef', 'tunisia-governorate-cities'),
                'moularès' => __('Moularès', 'tunisia-governorate-cities'),
                'sidi_aich' => __('Sidi Aich', 'tunisia-governorate-cities'),
                'belkhir' => __('Belkhir', 'tunisia-governorate-cities')
            ),
            'jendouba' => array(
                'jendouba' => __('Jendouba', 'tunisia-governorate-cities'),
                'tabarka' => __('Tabarka', 'tunisia-governorate-cities'),
                'ain_draham' => __('Aïn Draham', 'tunisia-governorate-cities'),
                'fernana' => __('Fernana', 'tunisia-governorate-cities'),
                'beni_mtir' => __('Beni Mtir', 'tunisia-governorate-cities'),
                'ghardimaou' => __('Ghardimaou', 'tunisia-governorate-cities'),
                'bou_salem' => __('Bou Salem', 'tunisia-governorate-cities'),
                'balta_bou_aouene' => __('Balta Bou Aouene', 'tunisia-governorate-cities')
            ),
            'kairouan' => array(
                'kairouan' => __('Kairouan', 'tunisia-governorate-cities'),
                'haffouz' => __('Haffouz', 'tunisia-governorate-cities'),
                'alaa' => __('Alaa', 'tunisia-governorate-cities'),
                'hajeb_el_ayoun' => __('Hajeb El Ayoun', 'tunisia-governorate-cities'),
                'nasrallah' => __('Nasrallah', 'tunisia-governorate-cities'),
                'menzel_mehiri' => __('Menzel Mehiri', 'tunisia-governorate-cities'),
                'el_ouslatia' => __('El Ouslatia', 'tunisia-governorate-cities'),
                'chebika' => __('Chebika', 'tunisia-governorate-cities'),
                'echebbi' => __('Echebbi', 'tunisia-governorate-cities')
            ),
            'kasserine' => array(
                'kasserine' => __('Kasserine', 'tunisia-governorate-cities'),
                'sbeitla' => __('Sbeitla', 'tunisia-governorate-cities'),
                'feriana' => __('Feriana', 'tunisia-governorate-cities'),
                'thala' => __('Thala', 'tunisia-governorate-cities'),
                'hassi_el_ferid' => __('Hassi El Ferid', 'tunisia-governorate-cities'),
                'foussana' => __('Foussana', 'tunisia-governorate-cities'),
                'jedelienne' => __('Jedelienne', 'tunisia-governorate-cities'),
                'el_ayoun' => __('El Ayoun', 'tunisia-governorate-cities'),
                'ezzouhour' => __('Ezzouhour', 'tunisia-governorate-cities'),
                'majel_bel_abbès' => __('Majel Bel Abbès', 'tunisia-governorate-cities'),
                'tajerouine' => __('Tajerouine', 'tunisia-governorate-cities')
            ),
            'kebili' => array(
                'kebili' => __('Kébili', 'tunisia-governorate-cities'),
                'douz' => __('Douz', 'tunisia-governorate-cities'),
                'faouar' => __('Faouar', 'tunisia-governorate-cities'),
                'souk_el_ahed' => __('Souk El Ahed', 'tunisia-governorate-cities')
            ),
            'kef' => array(
                'le_kef' => __('Le Kef', 'tunisia-governorate-cities'),
                'tajerouine' => __('Tajerouine', 'tunisia-governorate-cities'),
                'kalâat_senan' => __('Kalâat Senan', 'tunisia-governorate-cities'),
                'kalâat_khasba' => __('Kalâat Khasba', 'tunisia-governorate-cities'),
                'sakiet_sidi_youssef' => __('Sakiet Sidi Youssef', 'tunisia-governorate-cities'),
                'el_kors' => __('El Kors', 'tunisia-governorate-cities'),
                'jerissa' => __('Jerissa', 'tunisia-governorate-cities'),
                'touiref' => __('Touiref', 'tunisia-governorate-cities'),
                'dahmani' => __('Dahmani', 'tunisia-governorate-cities'),
                'sers' => __('Sers', 'tunisia-governorate-cities'),
                'nadhour' => __('Nadhour', 'tunisia-governorate-cities')
            ),
            'mahdia' => array(
                'mahdia' => __('Mahdia', 'tunisia-governorate-cities'),
                'rejiche' => __('Rejiche', 'tunisia-governorate-cities'),
                'bou_merdes' => __('Bou Merdes', 'tunisia-governorate-cities'),
                'ouled_chamekh' => __('Ouled Chamekh', 'tunisia-governorate-cities'),
                'chorbane' => __('Chorbane', 'tunisia-governorate-cities'),
                'hbira' => __('Hbira', 'tunisia-governorate-cities'),
                'souassi' => __('Souassi', 'tunisia-governorate-cities'),
                'kerker' => __('Kerker', 'tunisia-governorate-cities'),
                'chebba' => __('Chebba', 'tunisia-governorate-cities'),
                'mellouleche' => __('Mellouleche', 'tunisia-governorate-cities'),
                'sidi_alouane' => __('Sidi Alouane', 'tunisia-governorate-cities'),
                'el_jem' => __('El Jem', 'tunisia-governorate-cities')
            ),
            'manouba' => array(
                'manouba' => __('Manouba', 'tunisia-governorate-cities'),
                'den_den' => __('Den Den', 'tunisia-governorate-cities'),
                'douar_hicher' => __('Douar Hicher', 'tunisia-governorate-cities'),
                'oued_ellil' => __('Oued Ellil', 'tunisia-governorate-cities'),
                'mornaguia' => __('Mornaguia', 'tunisia-governorate-cities'),
                'borj_el_amri' => __('Borj El Amri', 'tunisia-governorate-cities'),
                'djedeida' => __('Djedeida', 'tunisia-governorate-cities'),
                'tebourba' => __('Tebourba', 'tunisia-governorate-cities'),
                'el_battan' => __('El Battan', 'tunisia-governorate-cities'),
                'jedaida' => __('Jedaida', 'tunisia-governorate-cities'),
                'kalâat_el_andalous' => __('Kalâat El Andalous', 'tunisia-governorate-cities')
            ),
            'medenine' => array(
                'medenine' => __('Médenine', 'tunisia-governorate-cities'),
                'ben_guerdane' => __('Ben Guerdane', 'tunisia-governorate-cities'),
                'zarzis' => __('Zarzis', 'tunisia-governorate-cities'),
                'djerba_ajim' => __('Djerba Ajim', 'tunisia-governorate-cities'),
                'djerba_midoun' => __('Djerba Midoun', 'tunisia-governorate-cities'),
                'djerba_houmt_souk' => __('Djerba Houmt Souk', 'tunisia-governorate-cities'),
                'sidi_makhlouf' => __('Sidi Makhlouf', 'tunisia-governorate-cities'),
                'bin_qirdane' => __('Bin Qirdane', 'tunisia-governorate-cities'),
                'ajim' => __('Ajim', 'tunisia-governorate-cities'),
                'el_hamma' => __('El Hamma', 'tunisia-governorate-cities'),
                'ghoumrassen' => __('Ghoumrassen', 'tunisia-governorate-cities'),
                'smar' => __('Smar', 'tunisia-governorate-cities')
            ),
            'monastir' => array(
                'monastir' => __('Monastir', 'tunisia-governorate-cities'),
                'khniss' => __('Khniss', 'tunisia-governorate-cities'),
                'jemmal' => __('Jemmal', 'tunisia-governorate-cities'),
                'ksar_hellal' => __('Ksar Hellal', 'tunisia-governorate-cities'),
                'kantaoui' => __('Kantaoui', 'tunisia-governorate-cities'),
                'sahline' => __('Sahline', 'tunisia-governorate-cities'),
                'ouerdenine' => __('Ouerdenine', 'tunisia-governorate-cities'),
                'bembla' => __('Bembla', 'tunisia-governorate-cities'),
                'téboulba' => __('Téboulba', 'tunisia-governorate-cities'),
                'lamta' => __('Lamta', 'tunisia-governorate-cities'),
                'bekalta' => __('Bekalta', 'tunisia-governorate-cities'),
                'touza' => __('Touza', 'tunisia-governorate-cities'),
                'sayada' => __('Sayada', 'tunisia-governorate-cities'),
                'barkoukech' => __('Barkoukech', 'tunisia-governorate-cities')
            ),
            'nabeul' => array(
                'nabeul' => __('Nabeul', 'tunisia-governorate-cities'),
                'hammamet' => __('Hammamet', 'tunisia-governorate-cities'),
                'kelibia' => __('Kelibia', 'tunisia-governorate-cities'),
                'dar_chaabane' => __('Dar Chaabane', 'tunisia-governorate-cities'),
                'takelsa' => __('Takelsa', 'tunisia-governorate-cities'),
                'soliman' => __('Soliman', 'tunisia-governorate-cities'),
                'korbous' => __('Korbous', 'tunisia-governorate-cities'),
                'menzel_temime' => __('Menzel Temime', 'tunisia-governorate-cities'),
                'bni_khiar' => __('Bni Khiar', 'tunisia-governorate-cities'),
                'el_haouaria' => __('El Haouaria', 'tunisia-governorate-cities'),
                'tazarka' => __('Tazarka', 'tunisia-governorate-cities'),
                'mida' => __('Mida', 'tunisia-governorate-cities'),
                'ouled_ellah' => __('Ouled Ellah', 'tunisia-governorate-cities'),
                'korbous' => __('Korbous', 'tunisia-governorate-cities'),
                'azmour' => __('Azmour', 'tunisia-governorate-cities'),
                'hammam_ghezaz' => __('Hammam Ghezaz', 'tunisia-governorate-cities'),
                'el_mida' => __('El Mida', 'tunisia-governorate-cities')
            ),
            'sfax' => array(
                'sfax' => __('Sfax', 'tunisia-governorate-cities'),
                'sakiet_ezzit' => __('Sakiet Ezzit', 'tunisia-governorate-cities'),
                'chihia' => __('Chihia', 'tunisia-governorate-cities'),
                'skhira' => __('Skhira', 'tunisia-governorate-cities'),
                'jebeniana' => __('Jebeniana', 'tunisia-governorate-cities'),
                'agareb' => __('Agareb', 'tunisia-governorate-cities'),
                'el_hencha' => __('El Hencha', 'tunisia-governorate-cities'),
                'gremda' => __('Gremda', 'tunisia-governorate-cities'),
                'thyna' => __('Thyna', 'tunisia-governorate-cities'),
                'kerkenah' => __('Kerkenah', 'tunisia-governorate-cities'),
                'el_amra' => __('El Amra', 'tunisia-governorate-cities'),
                'bir_ali_ben_khalifa' => __('Bir Ali Ben Khalifa', 'tunisia-governorate-cities'),
                'gabès' => __('Gabès', 'tunisia-governorate-cities'),
                'métouia' => __('Métouia', 'tunisia-governorate-cities'),
                'oued_ellil' => __('Oued Ellil', 'tunisia-governorate-cities'),
                'sidi_el_hani' => __('Sidi El Hani', 'tunisia-governorate-cities')
            ),
            'sidi_bouzid' => array(
                'sidi_bouzid' => __('Sidi Bouzid', 'tunisia-governorate-cities'),
                'regueb' => __('Regueb', 'tunisia-governorate-cities'),
                'sidi_ali_ben_aoun' => __('Sidi Ali Ben Aoun', 'tunisia-governorate-cities'),
                'mezouna' => __('Mezouna', 'tunisia-governorate-cities'),
                'oued_ellil' => __('Oued Ellil', 'tunisia-governorate-cities'),
                'bir_el_haffey' => __('Bir El Haffey', 'tunisia-governorate-cities'),
                'celta' => __('Celta', 'tunisia-governorate-cities'),
                'jilma' => __('Jilma', 'tunisia-governorate-cities'),
                'menzel_bouzaiane' => __('Menzel Bouzaiane', 'tunisia-governorate-cities'),
                'souk_jedid' => __('Souk Jedid', 'tunisia-governorate-cities'),
                'sidi_el_hani' => __('Sidi El Hani', 'tunisia-governorate-cities'),
                'ouled_haffouz' => __('Ouled Haffouz', 'tunisia-governorate-cities')
            ),
            'siliana' => array(
                'siliana' => __('Siliana', 'tunisia-governorate-cities'),
                'bou_arada' => __('Bou Arada', 'tunisia-governorate-cities'),
                'gaafour' => __('Gaafour', 'tunisia-governorate-cities'),
                'el_krib' => __('El Krib', 'tunisia-governorate-cities'),
                'sidi_bou_rouis' => __('Sidi Bou Rouis', 'tunisia-governorate-cities'),
                'maktar' => __('Maktar', 'tunisia-governorate-cities'),
                'rouhia' => __('Rouhia', 'tunisia-governorate-cities'),
                'kesra' => __('Kesra', 'tunisia-governorate-cities'),
                'el_aroussa' => __('El Aroussa', 'tunisia-governorate-cities'),
                'bargou' => __('Bargou', 'tunisia-governorate-cities'),
                'le_sers' => __('Le Sers', 'tunisia-governorate-cities'),
                'makthar' => __('Makthar', 'tunisia-governorate-cities')
            ),
            'sousse' => array(
                'sousse' => __('Sousse', 'tunisia-governorate-cities'),
                'kantaoui' => __('Kantaoui', 'tunisia-governorate-cities'),
                'hammam_sousse' => __('Hammam Sousse', 'tunisia-governorate-cities'),
                'akouda' => __('Akouda', 'tunisia-governorate-cities'),
                'kalâa_kebira' => __('Kalâa Kebira', 'tunisia-governorate-cities'),
                'kalâa_sghira' => __('Kalâa Sghira', 'tunisia-governorate-cities'),
                'msaken' => __('Msaken', 'tunisia-governorate-cities'),
                'sidi_bou_ali' => __('Sidi Bou Ali', 'tunisia-governorate-cities'),
                'hergla' => __('Hergla', 'tunisia-governorate-cities'),
                'ennadhour' => __('Ennadhour', 'tunisia-governorate-cities'),
                'boulel' => __('Boulel', 'tunisia-governorate-cities'),
                'sidi_el_heni' => __('Sidi El Heni', 'tunisia-governorate-cities'),
                'messaadine' => __('Messaadine', 'tunisia-governorate-cities'),
                'kantaoui' => __('Kantaoui', 'tunisia-governorate-cities')
            ),
            'tataouine' => array(
                'tataouine' => __('Tataouine', 'tunisia-governorate-cities'),
                'ghomrassen' => __('Ghomrassen', 'tunisia-governorate-cities'),
                'remada' => __('Remada', 'tunisia-governorate-cities'),
                'bir_lahmar' => __('Bir Lahmar', 'tunisia-governorate-cities'),
                'dehiba' => __('Dehiba', 'tunisia-governorate-cities'),
                'smar' => __('Smar', 'tunisia-governorate-cities'),
                'toujane' => __('Toujane', 'tunisia-governorate-cities'),
                'el_borma' => __('El Borma', 'tunisia-governorate-cities')
            ),
            'tozeur' => array(
                'tozeur' => __('Tozeur', 'tunisia-governorate-cities'),
                'nefta' => __('Nefta', 'tunisia-governorate-cities'),
                'degache' => __('Degache', 'tunisia-governorate-cities'),
                'hazoua' => __('Hazoua', 'tunisia-governorate-cities'),
                'el_hamma_du_jérid' => __('El Hamma du Jérid', 'tunisia-governorate-cities'),
                'chebika' => __('Chebika', 'tunisia-governorate-cities'),
                'midès' => __('Midès', 'tunisia-governorate-cities'),
                'tamerza' => __('Tamerza', 'tunisia-governorate-cities')
            ),
            'tunis' => array(
                'tunis' => __('Tunis', 'tunisia-governorate-cities'),
                'le_bardo' => __('Le Bardo', 'tunisia-governorate-cities'),
                'la_goulette' => __('La Goulette', 'tunisia-governorate-cities'),
                'carthage' => __('Carthage', 'tunisia-governorate-cities'),
                'sidi_bou_said' => __('Sidi Bou Said', 'tunisia-governorate-cities'),
                'marsa' => __('Marsa', 'tunisia-governorate-cities'),
                'gammarth' => __('Gammarth', 'tunisia-governorate-cities'),
                'kram' => __('Kram', 'tunisia-governorate-cities'),
                'mohamedia' => __('Mohamedia', 'tunisia-governorate-cities'),
                'el_omrane' => __('El Omrane', 'tunisia-governorate-cities'),
                'el_omrane_superieur' => __('El Omrane Supérieur', 'tunisia-governorate-cities'),
                'bab_souika' => __('Bab Souika', 'tunisia-governorate-cities'),
                'bab_el_bhar' => __('Bab El Bhar', 'tunisia-governorate-cities'),
                'medina' => __('Medina', 'tunisia-governorate-cities'),
                'sejoumi' => __('Sejoumi', 'tunisia-governorate-cities'),
                'el_kabaria' => __('El Kabaria', 'tunisia-governorate-cities'),
                'sidi_hassine' => __('Sidi Hassine', 'tunisia-governorate-cities'),
                'ezzouhour' => __('Ezzouhour', 'tunisia-governorate-cities'),
                'el_menzah' => __('El Menzah', 'tunisia-governorate-cities'),
                'el_menzah_6' => __('El Menzah 6', 'tunisia-governorate-cities'),
                'el_menzah_7' => __('El Menzah 7', 'tunisia-governorate-cities'),
                'el_menzah_8' => __('El Menzah 8', 'tunisia-governorate-cities'),
                'el_menzah_9' => __('El Menzah 9', 'tunisia-governorate-cities'),
                'el_menzah_10' => __('El Menzah 10', 'tunisia-governorate-cities'),
                'el_menzah_11' => __('El Menzah 11', 'tunisia-governorate-cities'),
                'el_menzah_12' => __('El Menzah 12', 'tunisia-governorate-cities'),
                'el_menzah_13' => __('El Menzah 13', 'tunisia-governorate-cities'),
                'el_menzah_14' => __('El Menzah 14', 'tunisia-governorate-cities'),
                'el_menzah_15' => __('El Menzah 15', 'tunisia-governorate-cities'),
                'el_menzah_16' => __('El Menzah 16', 'tunisia-governorate-cities'),
                'el_menzah_17' => __('El Menzah 17', 'tunisia-governorate-cities'),
                'el_menzah_18' => __('El Menzah 18', 'tunisia-governorate-cities'),
                'el_menzah_19' => __('El Menzah 19', 'tunisia-governorate-cities'),
                'el_menzah_20' => __('El Menzah 20', 'tunisia-governorate-cities')
            ),
            'zaghouan' => array(
                'zaghouan' => __('Zaghouan', 'tunisia-governorate-cities'),
                'el_fahs' => __('El Fahs', 'tunisia-governorate-cities'),
                'zriba' => __('Zriba', 'tunisia-governorate-cities'),
                'bir_mcherga' => __('Bir Mcherga', 'tunisia-governorate-cities'),
                'djebel_ouust' => __('Djebel Oust', 'tunisia-governorate-cities'),
                'el_haouaria' => __('El Haouaria', 'tunisia-governorate-cities'),
                'nadhour' => __('Nadhour', 'tunisia-governorate-cities'),
                'saouaf' => __('Saouaf', 'tunisia-governorate-cities')
            )
        );
        
        return isset($cities[$governorate]) ? $cities[$governorate] : array();
    }
    
    public function get_cities_ajax() {
        check_ajax_referer('tgc_nonce', 'nonce');
        
        $governorate = sanitize_text_field($_POST['governorate']);
        $cities = $this->get_cities($governorate);
        
        $options = array('' => __('Select City', 'tunisia-governorate-cities'));
        foreach ($cities as $key => $city) {
            $options[$key] = $city;
        }
        
        wp_send_json_success($options);
    }
    
    public function validate_checkout_fields() {
        if (empty($_POST['billing_governorate'])) {
            wc_add_notice(__('Please select a governorate.', 'tunisia-governorate-cities'), 'error');
        }
        
        if (empty($_POST['billing_city'])) {
            wc_add_notice(__('Please select a city.', 'tunisia-governorate-cities'), 'error');
        }
    }
    
    public function save_checkout_fields($order_id) {
        if (!empty($_POST['billing_governorate'])) {
            update_post_meta($order_id, '_billing_governorate', sanitize_text_field($_POST['billing_governorate']));
        }
        
        if (!empty($_POST['billing_city'])) {
            update_post_meta($order_id, '_billing_city', sanitize_text_field($_POST['billing_city']));
        }
        
        if (!empty($_POST['shipping_governorate'])) {
            update_post_meta($order_id, '_shipping_governorate', sanitize_text_field($_POST['shipping_governorate']));
        }
        
        if (!empty($_POST['shipping_city'])) {
            update_post_meta($order_id, '_shipping_city', sanitize_text_field($_POST['shipping_city']));
        }
    }
    
    public function display_admin_order_fields($order) {
        $billing_governorate = get_post_meta($order->get_id(), '_billing_governorate', true);
        $billing_city = get_post_meta($order->get_id(), '_billing_city', true);
        $shipping_governorate = get_post_meta($order->get_id(), '_shipping_governorate', true);
        $shipping_city = get_post_meta($order->get_id(), '_shipping_city', true);
        
        if ($billing_governorate) {
            $governorates = $this->get_governorates();
            $cities = $this->get_cities($billing_governorate);
            echo '<p><strong>' . __('Billing Governorate', 'tunisia-governorate-cities') . ':</strong> ' . 
                 (isset($governorates[$billing_governorate]) ? $governorates[$billing_governorate] : $billing_governorate) . '</p>';
            echo '<p><strong>' . __('Billing City', 'tunisia-governorate-cities') . ':</strong> ' . 
                 (isset($cities[$billing_city]) ? $cities[$billing_city] : $billing_city) . '</p>';
        }
        
        if ($shipping_governorate) {
            $governorates = $this->get_governorates();
            $cities = $this->get_cities($shipping_governorate);
            echo '<p><strong>' . __('Shipping Governorate', 'tunisia-governorate-cities') . ':</strong> ' . 
                 (isset($governorates[$shipping_governorate]) ? $governorates[$shipping_governorate] : $shipping_governorate) . '</p>';
            echo '<p><strong>' . __('Shipping City', 'tunisia-governorate-cities') . ':</strong> ' . 
                 (isset($cities[$shipping_city]) ? $cities[$shipping_city] : $shipping_city) . '</p>';
        }
    }
}

// Initialize the plugin
new Tunisia_Governorate_Cities(); 