<?php

namespace Drupal\feeds_address\Feeds\Target;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\feeds\FieldTargetDefinition;
use Drupal\feeds\Plugin\Type\Target\FieldTargetBase;

/**
 * Defines a link field mapper.
 *
 * @FeedsTarget(
 *   id = "address_country",
 *   field_types = {"address_country"}
 * )
 */
class AddressCountry extends FieldTargetBase {

  /**
   * {@inheritdoc}
   */
  protected static function prepareTarget(FieldDefinitionInterface $field_definition) {
    return FieldTargetDefinition::createFromFieldDefinition($field_definition)
      ->addProperty('value');
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareValue($delta, array &$values) {
    $values['value'] = trim($values['value']);
    $countries = \Drupal::service('address.country_repository')->getList();

    // Support linking to nothing.
    if ($iso2 = array_search($values['value'], $countries)) {
      $values['value'] = $iso2;
    }
    // Detect a schemeless string, map to 'internal:' URI.
    elseif (in_array(strtoupper($values['value']), array_keys($countries))) {
      $values['value'] = strtoupper($values['value']);
    }
    else {
      $values['value'] = "";
    }
  }

}
