<?php

namespace Drupal\zipcode_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Plugin implementation of the 'zipcode_default' formatter.
 *
 * @FieldFormatter(
 *   id = "zipcode_default",
 *   label = @Translation("郵便番号"),
 *   field_types = {
 *     "zipcode"
 *   }
 * )
 */
class ZipcodeDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      $elements[$delta] = ['#markup' => $item->value];
    }
    return $elements;
  }
}
