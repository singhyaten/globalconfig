<?php

namespace Drupal\globalconfig\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\globalconfig\GconfServices;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'GLobal Config Timezone' block.
 *
 * @Block(
 *  id = "gconftimezone",
 *  admin_label = @Translation("GLobal Config Timezone"),
 * )
 */
class Gconf extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The paragraphs behavior plugin manager service.
   *
   * @var \Drupal\globalconfig\GconfServices
   */
  protected $gconfServices;

  /**
   *
   * @param \Drupal\globalconfig\GconfServices $gconf_services
   */

  public function __construct(array $configuration, $plugin_id, $plugin_definition, GconfServices $gconfServices) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->gconfServices = $gconfServices;
  }
  
    /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('globalconfig.timezone')
    );
  }
  
  /**
   * {@inheritdoc}
   */
  public function build() {
	  
	$renderable = [
      '#theme' => 'gconfiguration',
      '#conf_time' => $this->gconfServices->getServiceData(),
      '#conf_zone' => $this->gconfServices->getconfigcityandcountry(),
    ];

    return $renderable;
  }
  
  public function getCacheMaxAge() { return 0; }
}
