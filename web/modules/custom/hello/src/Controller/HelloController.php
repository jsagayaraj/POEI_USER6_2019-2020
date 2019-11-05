<?php

  namespace Drupal\hello\Controller;


  use Drupal\Core\Controller\ControllerBase;

  class HelloController extends ControllerBase{

    public function content(){

      $message = $this->t('you are on the Hello page. Your name is @username', [
        '@username' => $this->currentUser()->getAccountName(),
      ]);
      return ['#markup' => $message];


        //return ['#markup' => $this->t($this->currentUser()->getAccountName())];
        //return ['#markup' => $this->t('coucou')];

      //return ['#markup' => $this->t("Hello")];
    }
  }