<?php
namespace Drupal\custom_migrate\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Determine the most recent entity revision id given an entity id
 *
 * @MigrateProcessPlugin(
 *   id = "cbiit_key_wrapper"
 * )
 */
class MigrateProcessKeyWrapper extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $new_value = array(
      'cbiit_key_wrapper' => $value,
    );
    $value = $new_value;

    var_dump($value);
    return $value;
  }

}