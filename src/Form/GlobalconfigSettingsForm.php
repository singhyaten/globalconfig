<?php

namespace Drupal\globalconfig\Form;

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for globalconfig module routes.
 */
class GlobalconfigSettingsForm extends ConfigFormBase {
  
  protected $configFactory;
  
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->configFactory = $configFactory;
  }
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'globalconfig_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['globalconfig.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $globalconfig_config = $this->config('globalconfig.settings');
    $form_settings = $globalconfig_config->get('form_settings');
	
    $form['form_settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Global Configuration Form'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    ];
    // Generic forms Contry textfield.
    $form['form_settings']['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      //'#description' => $this->t("Country"),
      '#default_value' => $this->getFormSettingsValue($form_settings, 'country'),
      '#required' => TRUE,
      '#size' => 30,
    ];
    $form['form_settings']['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      //'#description' => $this->t("City"),
      '#default_value' => $this->getFormSettingsValue($form_settings, 'city'),
      '#required' => TRUE,
      '#size' => 30,
    ];
    
  $timezone_list = array('America/Chicago' => 'America/Chicago' , 'America/New_York'=>'America/New_York', 'Asia/Tokyo'=>'Asia/Tokyo', 'Asia/Dubai'=> 'Asia/Dubai', 'Asia/Kolkata'=>'Asia/Kolkata' ,'Europe/Amsterdam'=>'Europe/Amsterdam' ,'Europe/Oslo'=>'Europe/Oslo', 'Europe/London' => 'Europe/London');
  $OptionsArray = timezone_identifiers_list();
    $form['form_settings']['timezone'] = [
	 '#type' => 'select',
	 '#title' => t('Timezone'),
	 '#options' => $timezone_list,
	 '#default_value' => $this->getFormSettingsValue($form_settings, 'timezone'),
	 '#required' => FALSE,
	];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
	parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('globalconfig.settings');

	$checkkeys = array('country','city','timezone');
	$submittedvalues = array();
    // Save all the configuration items from $form_state.
    foreach ($form_state->getValues() as $key => $value) {
      if (in_array($key, $checkkeys)) {
		  $submittedvalues[$key] = $value;
      }
    }
	// Save the forms from $form_state into a 'form_settings' array.
    $config->set('form_settings', $submittedvalues);
    $config->save();
    parent::submitForm($form, $form_state);
  }

 protected function getFormSettingsValue(array $form_settings, string $form_id) {
    // return the saved setting for the form ID.
    if (!empty($form_settings) && isset($form_settings[$form_id])) {
      return $form_settings[$form_id];
    }
    // Default to false.
    else {
      return FALSE;
    }
  }

}
