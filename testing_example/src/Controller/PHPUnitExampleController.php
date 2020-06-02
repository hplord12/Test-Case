<?php

namespace Drupal\testing_example\Controller;

use Drupal\examples\Utility\DescriptionTemplateTrait;

/**
 * Controller for PHPUnit description page.
 */
class PHPUnitExampleController {
  public function description() {
     $build = [
        '#markup' => t('Hello World'),
    ];
    return $build;
  }

}
