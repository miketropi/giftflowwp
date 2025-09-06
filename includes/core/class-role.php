<?php
/**
 * User Role Management Class
 *
 * @package GiftFlowWP
 * @subpackage Core
 * @since 1.0.0
 */

namespace GiftFlowWp\Core;

/**
 * Class Role
 * 
 * Handles user role registration and management for the GiftFlowWP plugin
 */
class Role extends Base {
    
    /**
     * Plugin instance
     *
     * @var Role
     */
    private static $instance = null;
    
    /**
     * Get plugin instance
     *
     * @return Role
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        parent::__construct();
        // $this->init_hooks();
        $this->register_roles();
        $this->add_capabilities();
    }
    
    /**
     * Initialize WordPress hooks
     */
    // private function init_hooks() {
    //     add_action('init', array($this, 'register_roles'));
    //     add_action('init', array($this, 'add_capabilities'));
    // }
    
    /**
     * Register custom user roles
     */
    public function register_roles() {
        // Donor role like subscriber role
        add_role(
            'giftflowwp_donor',
            __('Donor', 'giftflowwp'),
            array(
                'read' => true,
            )
        );
    }
    
    /**
     * Add custom capabilities to existing roles
     */
    public function add_capabilities() {
        // No additional capabilities needed for basic setup
    }
    
    /**
     * Assign donor role to user
     *
     * @param int $user_id
     * @return bool
     */
    public function assign_donor_role($user_id) {
        $user = get_user_by('id', $user_id);
        if ($user && !in_array('giftflowwp_donor', $user->roles)) {
            // $user->add_role('giftflowwp_donor');
            // set role
            $user->set_role('giftflowwp_donor');

            // add more role subscriber
            $user->add_role('subscriber');

            return true;
        }
        return false;
    }
    
    /**
     * Remove donor role from user
     *
     * @param int $user_id
     * @return bool
     */
    public function remove_donor_role($user_id) {
        $user = get_user_by('id', $user_id);
        if ($user && in_array('giftflowwp_donor', $user->roles)) {
            $user->remove_role('giftflowwp_donor');
            return true;
        }
        return false;
    }
    
    /**
     * Check if user has donor role
     *
     * @param int $user_id
     * @return bool
     */
    public function user_has_donor_role($user_id) {
        $user = get_user_by('id', $user_id);
        return $user && in_array('giftflowwp_donor', $user->roles);
    }
    
    /**
     * Remove all custom roles on plugin deactivation
     */
    public function remove_roles() {
        remove_role('giftflowwp_donor');
    }
    
    /**
     * Remove custom capabilities from existing roles
     */
    public function remove_capabilities() {
        // No custom capabilities to remove
    }
}

