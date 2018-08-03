<?php

/**
 * @see https://codex.wordpress.org/Creating_Options_Pages
 */
class CoopCycleSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    private $page = 'coopcycle-settings';

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
    }

    public function add_plugin_page()
    {
        add_options_page(
            'CoopCycle',
            'CoopCycle',
            'manage_options',
            'coopcycle-settings',
            array($this, 'options_page')
        );
    }

    public function add_action_link($actions)
    {
        $settings = array(
            'settings' => '<a href="' . admin_url('admin.php?page=coopcycle-settings') . '">' . __( 'Settings' ) . '</a>'
        );

        return array_merge($settings, $actions);
    }

    public function options_page()
    {
        ?>
        <div class="wrap">
            <h1>CoopCycle</h1>
            <form method="post" action="options.php">
            <?php
                settings_fields('coopcycle_woocommerce');
                do_settings_sections('coopcycle-settings');
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'coopcycle_woocommerce',
            'coopcycle_base_url'
        );

        register_setting(
            'coopcycle_woocommerce',
            'coopcycle_api_token'
        );

        add_settings_section(
            'coopcycle_woocommerce',
            'General',
            array($this, 'print_section_info'),
            'coopcycle-settings'
        );

        add_settings_field(
            'coopcycle_base_url',
            'Base URL',
            array($this, 'coopcycle_base_url_callback'),
            'coopcycle-settings',
            'coopcycle_woocommerce'
        );

        add_settings_field(
            'coopcycle_api_token',
            'API Token',
            array($this, 'coopcycle_api_token_callback'),
            'coopcycle-settings',
            'coopcycle_woocommerce'
        );
    }

    public function print_section_info()
    {
    }

    public function coopcycle_base_url_callback()
    {
        $option = get_option('coopcycle_base_url');
        ?>
        <input class="regular-text" type="text"
            id="coopcycle_base_url" name="coopcycle_base_url" value="<?php echo isset($option) ? esc_attr($option) : '' ?>" />
        <p class="description">The base URL of the CoopCycle instance</p>
        <?php
    }

    public function coopcycle_api_token_callback()
    {
        $option = get_option('coopcycle_api_token');

        printf(
            '<input class="regular-text" type="text" id="coopcycle_api_token" name="coopcycle_api_token" value="%s" />',
            isset( $option ) ? esc_attr($option) : ''
        );
    }
}