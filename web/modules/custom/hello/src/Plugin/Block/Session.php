<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\Plugin\views\filter\Access;

/**
 * Provides a session block
 * @Block(
 *  id = "session_block",
 *  admin_label = @Translation("Session Block!")
 * )
 */
class Session extends BlockBase {
    /**
     * Implements Drupal\Core\Block\BlockBase::build().
     */
    public function build() {
       $database = \Drupal::database();
       //kint($database);
       $session = $database->select('sessions', 's')
                                ->countQuery()
                                ->execute();
        $session_total = $session->fetchField();

        return [
            '#markup' => $this->t('Session active %name', [
                '%name' => $session_total,
                
            ]),
            
        ];

        
    }
    protected function blockAccess(AccountInterface $account) {
      return AccessResult::allowedIfHasPermission($account, 'access hello');
    }


}