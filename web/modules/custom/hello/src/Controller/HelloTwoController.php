<?php

  namespace Drupal\hello\Controller;

  use Drupal\Core\Controller\ControllerBase;
  use Drupal\Core\Link;
  use Drupal\Core\Url;
  use Drupal\user\UserInterface;


  class HelloTwoController extends ControllerBase{

    public function content2($nodetype = NULL){
        //return['#markup' => 'test'];
      //this shows all object type available in drupal
      //ksm($this->entityTypeManager()->getDefinitions());

      // affichage des types de contunu.
      $node_types= \Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple();
      //ksm($node_types);

      $node_type_items = [] ;
      foreach ($node_types as $node_type){
        $url = new Url('hello.hello2', ['nodetype' => $node_type->id()]);
        $node_type_link = new Link($node_type->label(), $url);
        $node_type_items[] = $node_type_link;
      }

      //render array
      $node_type_list = [
        '#theme' => 'item_list',
        '#items' => $node_type_items,
        '#title' => $this->t('Filter by node type'),
      ];

      //Permet de manipuler les noeuds
        $node_storage = \Drupal::entityTypeManager()->getStorage('node');

        //Permet de faire de requetes sur les noeuds
        $query = $node_storage->getquery();

        //Filtre s'il y a un argument dans l'URL
        if($nodetype){
          $query->condition('type', $nodetype);
        }

        //Récupère les ids des noeuds
        $nids = $query->pager(15)->execute();

        //Récupère les noeuds
        $nodes = $node_storage->loadMultiple($nids);

        //ksm($node);
        $items = [];
        foreach($nodes as $node){
            $items[] = $node->toLink();
        }
        $list = [
            '#theme' => 'item_list',
            '#items' => $items,
            ];
        $pager = ['#type' => 'pager'];

        return[
          'node_type_list' => $node_type_list,
          'list' => $list,
          'pager' => $pager,
          '#cache' => ['max-age' => '0'],
          ];
      

        //return ['#markup' => $this->t($this->currentUser()->getAccountName())];
        //return ['#markup' => $this->t('coucou')];

      //return ['#markup' => $this->t("Hello")];
    }

  public function user_connection(UserInterface $user){
    //return['#markup' => 'test'];

    $query = \Drupal::database()->select('hello_user_statistics', 'user_statistics')
              ->fields('user_statistics', ['action', 'time'])
              ->condition('uid', $user->id());
    $result = $query->execute();
    //ksm($result);

    $rows = [];
    $connexions = 0;
    $user = $this->t($this->currentUser()->getAccountName());
    foreach($result as $record){
      //print_r($record);
      $rows[] = [
        $record->action == '1' ? $this->t('Login'): $this->t('Logout'),
        \Drupal::service('date.formatter')->format($record->time),
      ];
      $connexions += $record->action;
    }

    $table = [
        '#type' => 'table',
        '#header' => [$this->t('Action'), $this->t('Time')],
        '#rows' => $rows,
        '#empty' => $this->t('No connections yet'),
      ];


    //return[$table];

      $output_message = array(
        '#theme' => 'hello_module',
        '#user' => $user,
        '#count' => $connexions,

        //"#data" => 'The User ' .$user . ' connect ' .$connexions. ' times',
      );

      return [
        $output_message,
        $table,
        '#cache' => [
          'max-age' => '0',
        ],
      ];
    }





}//end class