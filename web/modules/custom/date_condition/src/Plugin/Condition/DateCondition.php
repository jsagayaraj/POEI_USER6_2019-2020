<?php

namespace Drupal\date_condition\Plugin\Condition;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Condition\ConditionPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Date' condition to enable a condition based in module selected status.
 *
 * @Condition(
 *   id = "date_condition",
 *   label = @Translation("Date Condition"),
 *   context = {
 *     "annonce" = @ContextDefinition("entity:annonce", required = FALSE , label = @Translation("annonce"))
 *   }
 * )
 *
 */
class DateCondition extends ConditionPluginBase {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * Creates a new DateCondition object.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {

      $form['start_date'] = [
        '#type' => 'date',
        '#title' => $this->t('Start date'),
        '#default_value' => $this->configuration['start_date'],
        //'#required' => TRUE,
      ];
      $form['end_date'] = [
        '#type' => 'date',
        '#title' => $this->t('End date'),
        '#default_value' => $this->configuration['end_date'],
        //'#required' => TRUE,
      ];
      //return parent::buildConfigurationForm($form, $form_state);
      return $form;
    }
  
  
  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    //$this->configuration['module'] = $form_state->getValue('module');
    $this->configuration['start_date'] = $form_state->getValue('start_date');
    $this->configuration['end_date'] = $form_state->getValue('end_date');
    parent::submitConfigurationForm($form, $form_state);
  }

  
  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'start_date' => '',
      'end_date' => '',
      ] + parent::defaultConfiguration();
  }

  public function validateConfigurationForm(array &$form, FormStateInterface $form_state)
  {
    if(!empty($form_state->getValue('start_date')) && !empty($form_state->getValue('end_date'))){
      
      $start = new DrupalDateTime($form_state->getValue('start_date'));
      $end = new DrupalDateTime($form_state->getValue('end_date'));
      if ($end < $start) {
       $form_state->setErrorByName('end_date', $this->t('end error message'));
      }
    }
  }

  /**
   * Evaluates the condition and returns TRUE or FALSE accordingly.
   *
   * @return bool
   *   TRUE if the condition has been met, FALSE otherwise.
   */
  public function evaluate(){
    $today = new DrupalDateTime('today');
    $start = $this->configuration['start_date'] ? new DrupalDateTime($this->configuration['start_date']) : NULL;
    $end = $this->configuration['end_date'] ? new DrupalDateTime($this->configuration['end_date']) : NULL;

    return (!$start || ($start <= $today)) && (!$end || ($end <= $today));
  }

  /**
   * Provides a human readable summary of the condition's configuration.
   */
  public function summary() {
    $module = $this->getContextValue('module');
    $modules = system_rebuild_module_data();

    $status = ($modules[$module]->status)?t('enabled'):t('disabled');

    return t('The module @module is @status.', ['@module' => $module, '@status' => $status]);
  }

}
