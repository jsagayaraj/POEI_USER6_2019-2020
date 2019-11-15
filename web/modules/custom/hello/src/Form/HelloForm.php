<?php
namespace Drupal\hello\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;



class HelloForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * The returned ID should be a unique string that can be a valid PHP function
   * name, since it's used in hook implementation names such as
   * hook_form_FORM_ID_alter().
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'hello_form';
  }

  public function buildForm(array $form, FormstateInterface $form_state){
    // $form['description'] = [
    //     '#type' => 'item',
    //     '#markup' => $this->t('Please enter the title and accept the terms of use of the site.'),
    //   ];
        
    //affiché la message
    if(isset($form_state->getRebuildInfo()['result'])){
        $form['result'] = [
            '#type' => 'html_tag',
            '#tag' => 'h2',
            '#value' => $this->t('Result: ') . $form_state->getRebuildInfo()['result'],
        ];
    }

    //form
        $form['first_value'] = [
                '#type' => 'textfield',
                '#title' => $this->t('First value'),
                '#description' => $this->t('Enter first value.'),
                '#required' => TRUE,
                '#ajax' => array(
                    'callback' => array($this, 'validateTextAjax'),
                    'event' => 'keyup',
                ),
                '#suffix' => '<span id="has-success"></span>'
            ];
  
        $form['operation'] = array(
            '#type' => 'radios',
            '#options' => array(
                'addition' => $this->t('Ajouter'),
                'soustraction' => $this->t('Soustract'),
                'multiplication' => $this->t('Multiply'),
                'division' => $this->t('Divide'),
            )
        );

        $form['second_value'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Second value'),
            '#description' => $this->t('Enter second value.'),
            '#required' => TRUE,
        ];
  
  
      // Group submit handlers in an actions element with a key of "actions" so
      // that it gets styled correctly, and so that other modules may add actions
      // to the form. This is not required, but is convention.
      $form['actions'] = [
        '#type' => 'actions',
      ];
  
      // Add a submit button that handles the submission of the form.
      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Calculate'),
      ];
  
      return $form;
     
  }

//form validation
  public function validateForm(array &$form, FormStateInterface $form_state) {
      $value_1 = $form_state->getValue('first_value');
      $value_2 = $form_state->getValue('second_value');
    //   $css = ['border' => '2px solid green'];
    //   $message = 'please enter only number';

      $response = new AjaxResponse();

    if(!is_numeric($value_1)){
        // $form_state->setErrorByName('first_value', $this->t('First value must be numeric!'));
        $css = ['border' => '2px solid green'];
        $message = $this->t('ok.');
        
    }else{
        $css = ['border' => '2px solid red'];
        $message = $this->t('not valid.');
    }
        $response->AddCommand(new CssCommand('#edit-first-value', $css));
        $response->AddCommand(new HtmlCommand('#has-success', $message));
        return $response;

    // if(!is_numeric($value_2)){
    //     $form_state->setErrorByName('second_value', $this->t('Second value must be numeric!'));
    // }

    
    }

//submit form
  public function submitForm(array &$form, FormstateInterface $form_state){
    $value_1 = $form_state->getValue('first_value');
    $value_2 = $form_state->getValue('second_value');
    
    $i = $form_state->getValue('operation');
    $result="";
    switch ($i) {
        case 'addition':
            $result = $value_1 + $value_2;
            break;
        case 'soustraction':
            $result =$value_1 - $value_2;
            break;
        case 'multiplication':
            $result =$value_1 * $value_2;
            break;
        case 'division':
            $result =$value_1 / $value_2;
            break;
        
    }
    // Get the state class.
    $state = \Drupal::state();
    
     //$this->messenger()->addMessage($result);
     //on passe le résultat
     $form_state->addRebuildInfo('result', $result);
     //reconstruction du formulaire
     $form_state->setRebuild();
    
    //enregistrement de l'heure de soumission avec state API
     \Drupal::state()->set('hello_form_submission_time', REQUEST_TIME);
  }

}
