<?php

/*
  Plugin Name: Contacts Manager
  Description: Plugin creations for dreamstaffing
  Version: 1.0
  Author: Rommel Castro Alonzo
  Author URI: http://www.facebook.com/xrommelxcastrox
 */

class Contacts_Manager {

    public function __construct() {
        add_action('init', array($this, 'create_contact_type'));
        add_action("add_meta_boxes", array($this, "contact_details_box"));
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
        add_meta_box("contact-details-box", "Contact Details", array($this, "contact_details_fields"), "contact", "normal", "high");
    }

    public function contact_details_fields() {
        echo $this->contact_details_fields_html('name', 'Name');
        echo $this->contact_details_fields_html('last-name', 'Last Name');
        echo $this->contact_details_fields_html('email', 'Email');
        echo $this->contact_details_fields_html('birthday', 'Birthday');
    }

    protected function contact_details_fields_html($title, $field_text) {
        global $post;
        $values = get_post_custom($post->ID);
        $value = isset($value['contact_'.$title.'_field']) ? $values['contact_'.$title.'_field'];

        $html = '<label for="contact_'.$title.'_field">'.$field_text.'</label>';
        $html .= '<input type="text" name="contact_'.$title.'_field" id="contact_'.$title.'_field" />';
        $html .= '<br/>';
        return $html;
    }
}

$contacts_Manager = new Contacts_Manager();