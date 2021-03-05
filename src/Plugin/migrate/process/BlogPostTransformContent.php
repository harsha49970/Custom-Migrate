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
 *   id = "cbiit_blog_post_transform_content"
 * )
 */
class BlogPostTransformContent extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    /*[INSERT IMAGE Ed3cropped.png ALIGN LEFT]*/

    preg_match_all( '/\[INSERT IMAGE ([^\]]*)\]/', $value, $matches);

    $index = 0;
    foreach($matches[0] as $match){
      $match_array = explode(" ", $matches[1][$index]);
      $drupal_db = Database::getConnection('default', 'default');
      $query = $drupal_db->select('migrate_map_blog_embedded_images', 'e');
      $query->join( "media","m1", "e.destid1 = m1.mid");
      $query->join( "media_field_data","m2", "e.destid1 = m2.mid");
      $results = $query
        ->fields('m1', ['uuid'])
        ->condition('m2.name',$match_array[0], "=")
        ->execute()
        ->fetchAll();

      if (!empty($results)) {
        $media_uuid = '';
        foreach ($results as $result) {
          $media_uuid = $result->uuid;
        }

        $image_entity_makrup = '
        <drupal-entity 
           data-align="' . strtolower($match_array[2]) .'" 
           data-embed-button="embed" 
           data-entity-embed-display="view_mode:media.full" 
           data-entity-type="media" 
           data-entity-uuid="' . $media_uuid. '">   
          </drupal-entity>';

        $value = str_replace($match, $image_entity_makrup, $value);
      }else{
        var_dump($match_array[0]);
      }

      $index++;
    }
    return $value;
  }

}
