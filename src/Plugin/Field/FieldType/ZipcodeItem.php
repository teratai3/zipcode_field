<?php

namespace Drupal\zipcode_field\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'zipcode' field type.
 *
 * @FieldType(
 *   id = "zipcode",
 *   label = @Translation("郵便番号"),
 *   description = @Translation("郵便番号の項目を保存します"),
 *   default_widget = "zipcode_default",
 *   default_formatter = "zipcode_default"
 * )
 */
class ZipcodeItem extends FieldItemBase
{

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition)
  {
    return [
      'columns' => [
        'value' => [
          'type' => 'varchar',
          'length' => 255,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition)
  {
    $properties = [];
    $properties['value'] = DataDefinition::create('string')->setLabel('郵便番号')->setRequired(true);
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty()
  {
    $value = $this->get('value')->getValue();
    return $value === null || $value === '';
  }

  /**
   * {@inheritdoc}
   */
  public function setValue($values, $notify = TRUE)
  {

    if (isset($values['zip1']) && isset($values['zip2'])) {
      $values['value'] = $values['zip1'] . '-' . $values['zip2'];
    } elseif (isset($values['value'])) {
      $values['value'] = $values['value']; //更新時だけではなく表示時
    } else {
      $values['value'] = '';
    }


    parent::setValue($values, $notify);
  }

  /**
   * {@inheritdoc}
   */
  // public function getValue()
  // {
  //   dump( $this->get('zip1')->getValue());
  //   exit;
  //   $zip1 = $this->get('zip1')->getValue();
  //   $zip2 = $this->get('zip2')->getValue();

  //   return $zip1 . '-' . $zip2;
  // }
}
