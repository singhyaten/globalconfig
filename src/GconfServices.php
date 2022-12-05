<?php

/**
 * @file
 * Contains \Drupal\globalconfig\GconfServices.
 */
namespace  Drupal\globalconfig;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * GconfServices.
 */
class GconfServices {

  /**
   * Drupal's settings manager.
   */
  protected $settings;

  /**
   * Constructor.
   */
  public function __construct() {
    $this->settings = \Drupal::config('globalconfig.settings');
  }
	
  public function getconfigcityandcountry() {
	 $city_country = $this->settings->get('form_settings.city').', '.$this->settings->get('form_settings.country');
	return $city_country;
  }
  
  public function getServiceData() {
	 $timezone = $this->settings->get('form_settings.timezone');
	 date_default_timezone_set($timezone);
	//$date = date('l, j F Y h:i A');
	return $date = date('jS M Y - h:i A');
  }

}
