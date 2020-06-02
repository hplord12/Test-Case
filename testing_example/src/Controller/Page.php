<?php

namespace Drupal\testing_example\Controller;

/**
 * Simple page controller for drupal.
 */
class Page {

  public function description() {
     $build = [
        '#markup' => t('Hello Form World'),
    ];
    return $build;
  }

}
