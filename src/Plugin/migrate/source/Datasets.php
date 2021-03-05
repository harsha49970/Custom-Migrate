<?php

namespace Drupal\custom_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Minimalistic example for a SqlBase source plugin.
 *
 * @MigrateSource(
 *   id = "datasets",
 *   source_module = "custom_migrate",
 * )
 */
class Datasets extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('node', 'n');
    $query->condition('n.type', 'dataset', '=');
    $query->leftJoin('field_data_field_dataset_pubmed_id', 'pi', 'n.nid = pi.entity_id');
    $query->leftJoin('field_data_field_dataset_description', 'dd', 'n.nid = dd.entity_id');
    $query->leftJoin('field_data_field_dataset_status', 'ds', 'n.nid = ds.entity_id');
    $query->leftJoin('field_data_field_dataset_key', 'dk', 'n.nid = dk.entity_id');
    $query->leftJoin('field_data_field_dataset_counter', 'dc', 'n.nid = dc.entity_id');
    $query->leftJoin('field_data_field_dataset_collaborative_agmt', 'da', 'n.nid = da.entity_id');
    $query->fields('n', [
          'nid',
          'uid',
          'created',
          'changed',
          'status',
          'title',
        ]);
    $query->fields('pi', [
          'field_dataset_pubmed_id_value',
        ]);
    $query->fields('dd', [
          'field_dataset_description_value',
        ]);
    $query->fields('ds', [
          'field_dataset_status_value',
        ]);
    $query->fields('dk', [
          'field_dataset_key_value',
        ]);
    $query->fields('dc', [
          'field_dataset_counter_value',
        ]);
    $query->fields('da', [
          'field_dataset_collaborative_agmt_value',
        ]);
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'nid' => $this->t('nid' ),
      'uid'    => $this->t('uid'),
      'created'    => $this->t('created'),
      'changed'    => $this->t('changed'),
      'status'    => $this->t('status'),
      'title'    => $this->t('title'),
      'field_dataset_pubmed_id_value'    => $this->t('field_dataset_pubmed_id_value'),
      'field_dataset_description_value'    => $this->t('field_dataset_description_value'),
      'field_dataset_status_value'    => $this->t('field_dataset_status_value'),
      'field_dataset_key_value'    => $this->t('field_dataset_key_value'),
      'field_dataset_counter_value'    => $this->t('field_dataset_counter_value'),
      'field_dataset_collaborative_agmt_value'    => $this->t('field_dataset_collaborative_agmt_value'),
    ];
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'nid' => [
        'type' => 'integer',
        'alias' => 'n',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $nid = $row->getSourceProperty('nid');
    $query = $this->select('field_data_field_dataset_trial_id', 'dts');
    $query->fields('dts', ['field_dataset_trial_id_target_id']);
    $query->condition('dts.entity_id', $nid, '=');
    $record = $query->execute()->fetchAllKeyed();
    $row->setSourceProperty('field_dataset_trial_id', array_keys($record));
    drush_print($row->getSourceProperty('field_dataset_trial_id'));
    $nid = $row->getSourceProperty('nid');
    $query = $this->select('field_data_field_dataset_authembargo_users', 'deu');
    $query->fields('deu', ['field_dataset_authembargo_users_target_id']);
    $query->condition('deu.entity_id', $nid, '=');
    $record = $query->execute()->fetchAllKeyed();
    $row->setSourceProperty('field_dataset_authembargo_users', array_keys($record));
    drush_print($row->getSourceProperty('field_dataset_authembargo_users'));
    return parent::prepareRow($row);
  }
}
