<?php

namespace Drupal\annonce\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Annonce entities.
 */
class AnnonceViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    
    $data['annonce_user_views'] = array();

    $data['annonce_user_views']['table'] = array();

    $data['annonce_user_views']['table']['group'] = t('Annonce history');
    $data['annonce_user_views']['table']['provider'] = 'annonce';
    $data['annonce_user_views']['table']['base'] = array(
      // Identifier (primary) field in this table for Views.
      'field' => 'id',
      // Label in the UI.
      'title' => t('Annonce History'),
      // Longer description in the UI. Required.
      'help' => t('Annonce history contains historical datas and can be related to annonces'),
      'weight' => -10,
    );

    $data['annonce_user_views']['uid'] = [
      'title' => $this->t('Annonce view user ID'),
      'help' => $this->t('Annonce view us ID'),
      'field' => ['id' => 'numeric'],
      'sort' => ['id' => 'standard'],
      'filter' => ['id' => 'numeric'],
      'argument' => ['id' => 'numeric'],
      'relationship' => [
        'base' => 'users_field_data',
        'base field' => 'uid',
        'id' => 'standard',
        'label' => $this->t('Annonce history UID -> User ID'),
      ],        
    ];

    $data['annonce_user_views']['aid'] = [
      'title' => $this->t('Annonce view ID'),
      'help' => $this->t('Annonce Content ID'),
      'field' => ['id' => 'numeric'],
      'sort' => ['id' => 'standard'],
      'filter' => ['id' => 'numeric'],
      'argument' => ['id' => 'numeric'],
      'relationship' => [
        'base' => 'annonce_field_data',
        'base field' => 'id',
        'id' => 'standard',
        'label' => $this->t('Annonce history AID -> Annonce ID'),
      ],        
    ];

    $data['annonce_user_views']['time'] = [
      'title' => $this->t('Annonce view date'),
      'help' => $this->t('The deate The view swas viewed'),
      'field' => ['id' => 'date'],
      'sort' => ['id' => 'date'],
      'filter' => ['id' => 'date'],
    ];

    $data['views']['area'] = array(
      'title' => t('Text area'),
      'help' => t('Provide markup text for the area.'),
      'area' => array(
        // ID of the area handler plugin to use.
        'id' => 'text',
      ),
    );

    return $data;

  }

}

