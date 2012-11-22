<?php
/*
 * HypnoticShipper
 *
 * The HypnoticShipper class is skeleton class to be inherented by actual shipping extension.
 * It handle some basics such as settings, availabilities etc
 *
 * @class       HypnoticShipper
 * @version     1.0
 * @package     WooCommerce-Shipper/Classes
 * @author      Andy Zhang
*/

class HypnoticShipper extends WC_Shipping_Method{

    /**
     * @var string
     */
    var $carrier = '';

    /**
     * @var string
     */
    var $endpoint = '';

    /**
     * @var string
     */
    var $dev_endpoint = '';

    /**
    * @var array
    */
    var $allowed_origin_countries = array();

    /**
    * @var array
    */
    var $allowed_currencies = array();

    /**
    * @var array
    */
    var $package_shipping_methods = array();

    /**
    * @var array
    */
    var $letter_shipping_methods = array();

    /**
    * @var array
    */
    var $settings_order = array();

    /**
     * Initialise Gateway Settings Form Fields
     */
    function init_form_fields() {
        global $woocommerce;

        $this->form_fields = array(

            'enabled' => array(
                'title' => __('Enable/Disable', 'hypnoticshipper'),
                'type' => 'checkbox',
                'label' => __('Enable ' . $this->carrier, 'hypnoticshipper'),
                'default' => 'yes'
            ),
            'debug' => array(
                'title' => __('Debug mode', 'woothemes'),
                'type' => 'checkbox',
                'label' => __('Enable debug mode', 'woothemes'),
                'default' => 'no'
            ),
            'title' => array(
                'title' => __('Method Title', 'hypnoticshipper'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'hypnoticshipper'),
                'default' => __($this->carrier, 'hypnoticshipper')
            ),
            'origin' => array(
                'title' => __('Origin Postcode', 'woothemes'),
                'type' => 'text',
                'description' => __('Enter your origin post code.', 'woothemes'),
                'default' => __('', 'woothemes')
            ),
            'shipping_availability' => array(
                'title' => __('Shipping method availability', 'woothemes'),
                'type' => 'select',
                'default' => 'all',
                'class' => 'availability',
                'options' => array(
                    'all' => __('All allowed methods', 'woothemes'),
                    'specific' => __('Specific methods', 'woothemes')
                )
            ),
            'shipping_methods' => array(
                'title' => __('Specific Shipping Methods', 'woothemes'),
                'type' => 'multiselect',
                'class' => 'chosen_select',
                'css' => 'width: 450px;',
                'default' => '',
                'options' => $this->shipping_options

            ),
            'availability' => array(
                'title' => __('Method availability', 'woothemes'),
                'type' => 'select',
                'default' => 'all',
                'class' => 'availability',
                'options' => array(
                    'all' => __('All allowed countries', 'woothemes'),
                    'specific' => __('Specific Countries', 'woothemes')
                )
            ),
            'countries' => array(
                'title' => __('Specific Target Countries', 'woothemes'),
                'type' => 'multiselect',
                'class' => 'chosen_select',
                'css' => 'width: 450px;',
                'default' => '',
                'options' => $woocommerce->countries->countries
            ),

        );

    }

    /**
    * Sort admin fields for displaying in order
    */
    function sort_form_fiels(){

        $fields = array();

        // Merge fields order
        if (empty($this->settings_order)){
            $this->settings_order = array_keys($this->form_fields);
        } else {
            $this->settings_order = array_merge($this->settings_order, array_keys($this->form_fields));
        }

        // Sorting
        foreach( $this->settings_order as $order ){

            if(isset($this->form_fields[$order])){
                $fields[$order] = $this->form_fields[$order];
            }

        }

        $this->form_fields = $fields;

    }

    /**
     * Shipping method available conditions
     */
    function is_available() {
        global $woocommerce;

        if ($this->enabled == "no")
            return false;

        if (!in_array(get_woocommerce_currency(), $this->allowed_currencies))
            return false;

        if (!in_array($woocommerce->countries->get_base_country(), $this->allowed_origin_countries))
            return false;

        if (empty($this->letter_shipping_methods) && empty($this->package_shipping_methods))
            return false;

        return true;

    }

}
?>