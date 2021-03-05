<?php
namespace Drupal\custom_migrate\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
use Drupal\Core\Database\Database;

/**
 * Determine the most recent entity revision id given an entity id
 *
 * @MigrateProcessPlugin(
 *   id = "cbiit_events_post_event_content"
 * )
 */
class EventsPostEventContent extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    $body_markup = "";
    if($value[0] != null){
      $body_markup = '<h3> ' . $value[0] . '</h3>';
      if($value[1] != null){
        $drupal_db = Database::getConnection('default', 'default');
        $query = $drupal_db->select('migrate_map_events_videos', 'v');
        $query->join( "media","m", "v.destid1 = m.mid");
        $results = $query
          ->fields('m', ['uuid'])
          ->condition('v.sourceid1',$value[1], "=")
          ->execute()
          ->fetchAll();
        if (!empty($results)) {
          $media_uuid = '';
          foreach ($results as $result) {
            $media_uuid = $result->uuid;
          }

          $body_markup .= '
          <drupal-entity 
           data-align="center" 
           data-embed-button="embed" 
           data-entity-embed-display="view_mode:media.full" 
           data-entity-type="media" 
           data-entity-uuid="' . $media_uuid. '">   
          </drupal-entity>';
        }

        if($value[2]  != null){
          $drupal_db = Database::getConnection('default', 'default');
          $query = $drupal_db->select('migrate_map_events_files', 'f');
          $results = $query
            ->fields('f', ['destid1'])
            ->condition('f.sourceid1',$value[2], "=")
            ->execute()
            ->fetchAll();
          if (!empty($results)) {
            $file_id = '';
            foreach ($results as $result) {
              $file_id = $result->destid1;
            }
            $body_markup .= '<p class="download">[file media_id=' . $file_id . ']' . $value[3] . '[/file]</p>';
          }
        }

      }


    }else{
      $body_markup = "";
    }

    return $body_markup;
  }

}