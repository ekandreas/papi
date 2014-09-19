<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Papi - Property Text
 *
 * @package Papi
 * @version 1.0.0
 */

class PropertyText extends Papi_Property {

 /**
  * Generate the HTML for the property.
  *
  * @since 1.0.0
  */

  public function html () {
    // Property options.
    $options = $this->get_options();

    // Database value. Can be null.
    $value = $this->get_value();

    // Property settings from the page type.
    $settings = $this->get_settings(array(
      'editor' => false,
    ));

    if ($settings->editor) {
      $id = str_replace('[', '', str_replace(']', '', $options->slug)) . '-' . uniqid();
      wp_editor($value, $id, array(
        'textarea_name' => $options->slug
      ));
    } else {
      echo Papi_Html::textarea($value, array(
        'name' => $options->slug,
        'class' => $this->css_classes('papi-property-text')
      ));
    }
  }
}
