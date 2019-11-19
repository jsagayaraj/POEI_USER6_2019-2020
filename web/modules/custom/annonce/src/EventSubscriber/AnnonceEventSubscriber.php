<?php

namespace Drupal\annonce\EventSubscriber;


use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event Subscriber MyEventSubscriber.
 */
class AnnonceEventSubscriber implements EventSubscriberInterface {

    protected $currentUser;
    protected $currentRouteMatch;
    protected $time;
    protected $database;

    public function __construct(AccountProxyInterface $current_user, CurrentRouteMatch $current_route_match, TimeInterface $time, Connection $database)
    {
        $this->currentUser = $current_user;
        $this->currentRouteMatch = $current_route_match;
        $this->time = $time;
        $this->database = $database;
    }
  
  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    // For this example I am using KernelEvents constants (see below a full list).
    $events[KernelEvents::REQUEST][] = ['onRequest'];
    return $events;
  }


  /**
   * Code that should be triggered on event specified 
   */
  public function onRequest(Event $event) {
      if($this->currentRouteMatch->getRouteName() == 'entity.annonce.canonical'){
          //drupal_set_message('test');
        $annonce = $this->currentRouteMatch->getParameter('annonce');
          $this->database->insert('annonce_user_views')->fields([
            'uid' => $this->currentUser->id(),            
            'time' => $this->time->getRequestTime(),
            'aid' => $annonce->id(),
          ])
          ->execute();
      }
  }



}