<?php


namespace Drupal\hello\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;




class AdminForm extends ConfigFormBase {

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
    return 'admin_form';
  }

  protected function getEditableConfigNames(){
      return ['hello.settings'];
  }


  public function buildForm(array $form, FormstateInterface $form_state)
  {
    //parent::submitForm($form, $form_state);
    $purge_days_number = $this->config('hello.settings')->get('purge_days_number');
        //form
        $form['purge_days_number'] = [
            '#type' => 'select',
            '#title' => $this->t('How long to keep user activity statistics'),
            '#options' => [
                '0' => $this->t('Never purge'),
                '1' => $this->t('One day'),
                '2' => $this->t('Tow days'),
                '7' => $this->t('One week'),
                '14' => $this->t('Two weeks'),
                '30' => $this->t('one month'),
            ],

            '#default_value' => $purge_days_number,
        ];
    
        return parent::buildForm($form, $form_state);
  }
 

    //submit form
  public function submitForm(array &$form, FormstateInterface $form_state){
       $this->config('hello.settings')
            ->set('purge_days_number', $form_state->getValue('purge_days_number'))
            ->save();
            parent::submitForm($form, $form_state);

  }
}