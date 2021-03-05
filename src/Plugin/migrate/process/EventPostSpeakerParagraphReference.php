<?php

namespace Drupal\custom_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

use Drupal\Core\Database\Database;



/**
 * Perform custom look up for paragraph
 *
 * @MigrateProcessPlugin(
 *   id = "event_post_speaker_paragraph_reference"
 * )
 *
 * To import multiple paragraphs
 *
 * @code
 * field_paragraph:
 *   plugin: event_post_speaker_paragraph_reference
 *   source: ids
 * @endcode
 *
 */
class EventPostSpeakerParagraphReference extends ProcessPluginBase {
  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $drupal_db = Database::getConnection('default', 'default');
    $paragraphs = [];
    $results = $drupal_db->select('migrate_map_events_bio_para', 'p')
      ->fields('p', ['destid1', 'destid2'])
      ->condition('p.sourceid1', explode(",", $value), 'IN')
      ->execute()
      ->fetchAll();
    if (!empty($results)) {
      foreach ($results as $result) {
        $paragraphs[] = [
          'target_id' => $result->destid1,
          'target_revision_id' => $result->destid2,
        ];
      }
    }

    return $paragraphs;

  }
}
