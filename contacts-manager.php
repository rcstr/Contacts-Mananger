<?php

/*
  Plugin Name: Contacts Manager
  Description: Plugin creations for dreamstaffing
  Version: 1.0
  Author: Rommel Castro Alonzo
  Author URI: http://www.facebook.com/xrommelxcastrox
 */

class Contacts_Manager {

    private $fields;

    public function __construct() {
        add_action('init', array($this, 'create_contact_type'));
        add_action('add_meta_boxes', array($this, 'contact_details_box'));
        add_action('save_post', array($this, 'contact_details_save'));

        //  Set up the fields
        $this->fields = array(
            'name' => array(
                'title' => 'name',
                'field_text' => 'Name'
            ),
            'last_name' => array(
                'title' => 'last_name',
                'field_text' => 'Last Name'
            ),
            'email' => array(
                'title' => 'email',
                'field_text' => 'Email',
                'type' => 'email'
            ),
            'birthday' => array(
                'title' => 'birthday',
                'field_text' => 'Birthday',
                'type' => 'date'
            )
        );
    }

    public function create_contact_type() {
        $labels = array(
            'name' => _x('Contacts', 'post type general name'),
            'singular_name' => _x('Contact', 'post type singular name'),
            'add_new' => _x('Add New', 'contact'),
            'add_new_item' => __('Add New Contact'),
            'edit_item' => __('Edit Contact'),
            'new_item' => __('New Contact'),
            'all_items' => __('All Contacts'),
            'view_item' => __('View Contact'),
            'search_items' => __('Search Contact'),
            'not_found' => __('No contacts found'),
            'not_found_in_trash' => __('No contacts found in Trash'),
            'parent_item_colon' => '',
            'menu_name' => 'Contacts'
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 80,
            'supports' => array('')
        );
        register_post_type('contact', $args);
    }

    public function contact_details_box() {
        add_meta_box("contact-details-box", "Contact Details", array($this, "contact_details_fields_html"), "contact", "normal", "high");
    }

    public function contact_details_fields_html() {
        global $post;
        $html = '';
        $values = get_post_custom($post->ID);
        foreach ($this->fields as $field) {
            $field['value'] = isset($values['contact_' . $field['title'] . '_field']) ? $values['contact_' . $field['title'] . '_field'] : '';
            $html .= '<label for="c-">' . $field['field_text'] . '</label>';
            $html .= '<input type="text" name="contact_' . $field['title'] . '_field" id="contact_' . $field['title'] . '_field" value="' . $field['value'][0] . '" />';
            $html .= '<br/>';
        }
        wp_nonce_field('contact-details-nonce', 'contact-details-nonce');

        echo $html;
    }

    public function contact_details_save($post_id) {
        // Bail if we're doing an auto save  
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // if our nonce isn't there, or we can't verify it, bail 
        if (!isset($_POST['contact-details-nonce']) || !wp_verify_nonce($_POST['contact-details-nonce'], 'contact-details-nonce')) {
            return;
        }

        // if our current user can't edit this post, bail  
        if (!current_user_can('edit_post')) {
            return;
        }

        //  Now we can actually save the data
        foreach($this->fields as $field) {
            if($_POST['contact_' . $field['title'] . '_field']) {
                update_post_meta($post_id, 'contact_' . $field['title'] . '_field', $_POST['contact_' . $field['title'] . '_field']);
            }
        }
    }

}

$contacts_Manager = new Contacts_Manager();