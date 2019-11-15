<?php

  namespace Drupal\hello\Access;

  use Drupal\Core\Access\AccessResult;
  use Drupal\Core\Routing\Access\AccessInterface;
  use Drupal\Core\Session\AccountInterface;
  use Symfony\Component\Routing\Route;

  /**
   * Determines access to routes based on permissions defined via
   * $module.permissions.yml files.
   */
  class HelloAccessCheck implements AccessInterface {

    /**
     * Checks access.
     *
     * @param \Symfony\Component\Routing\Route $route
     *   The route to check against.
     * @param \Drupal\Core\Session\AccountInterface $account
     *   The currently logged in account.
     *
     * @return \Drupal\Core\Access\AccessResultInterface
     *   The access result.
     */
    public function access(Route $route, AccountInterface $account) {


      $permission = $route->getRequirement('_permission');


      // Allow to conjunct the permissions with OR ('+') or AND (',').
      $split = explode(',', $permission);
      if (count($split) > 1) {
        return AccessResult::allowedIfHasPermissions($account, $split, 'AND');
      }
      else {
        $split = explode('+', $permission);
        return AccessResult::allowedIfHasPermissions($account, $split, 'OR');
      }

      //$nbr_heures = $route->getRequirement('_access_hello');

      $getTime = $account->getAccount()->created;
      $currentTime = \Drupal::service('datetime.time')->getCurrentTime();
      //dump($currentTime);
      $time_need = $currentTime-$getTime;



      if(!$account->isAnonymous() && ($this->time->getCurrentTime() - $getTime > 48 * 3600)
      ){

        return AccessResult::allowed()->cachePerUser();
      }else{
        return AccessResult::forbidden()->cachePerUser();
      }
    }



  }
