<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a hello block
 * @Block(
 *  id = "hello_block",
 *  admin_label = @Translation("Hello!")
 * )
 */
class Hello extends BlockBase {
    /**
     * Implements Drupal\Core\Block\BlockBase::build().
     */
    public function build() {
        $date_formatter = \Drupal::service('date.formatter');
        $time = \Drupal::service('datetime.time')->getCurrentTime();
        
        
        $build = [
            '#markup' => $this->t('Welccome, It is %time',[
            '%time' => $date_formatter->format($time, 'custom', 'H:i: s\s'),   
            ]),
        ];
           
        
        return $build;
        // return [
        //     '#markup' => $this->t('Welccome, It is %time', [
        //         '%time' => $date_formatter->format($time, 'custom', 'H:i: s\s'),
        //     ]),
        //     ];
    }

    
}