<?php

namespace Drupal\zipcode_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'zipcode_default' widget.
 *
 * @FieldWidget(
 *   id = "zipcode_default",
 *   label = @Translation("郵便番号"),
 *   field_types = {
 *     "zipcode"
 *   }
 * )
 */
class ZipcodeDefaultWidget extends WidgetBase
{
  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state)
  {
    // フィールドが必須かどうかをチェック
    $required = $element['#required'] ?? false;
    $required_class = $required ? 'form-required' : '';
    $element['#type'] = 'container';
    $element['#attributes']['class'][] = 'zipcode-widget-container';
    $element['#attached']['library'][] = 'zipcode_field/global-styling';
    $element['custom_label'] = [
      '#markup' => "<label class='zipcode-label form-item__label {$required_class}'>{$element['#title']}</label>",
      '#allowed_tags' => ['label'],
    ];

    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';
    list($zip1, $zip2) = array_pad(explode('-', $value), 2, '');

    $default_zip1 = isset($items[$delta]->zip1) ? $items[$delta]->zip1 : '';
    $default_zip2 = isset($items[$delta]->zip2) ? $items[$delta]->zip2 : '';

    $element['zip1'] = [
      '#type' => 'textfield',
      '#default_value' => isset($zip1) ? $zip1 : $default_zip1,
      '#size' => 5,
      '#maxlength' => 5,
      '#placeholder' => '000',
      '#required' => $required,
      '#element_validate' => [
        [$this, 'validateZipcode1'],
      ],
    ];

    $element['zip_hyphen'] = [
      '#markup' => "<span class='zipcode-widget-hyphen form-item'>-</span>",
      '#allowed_tags' => ['span'],
    ];

    $element['zip2'] = [
      '#type' => 'textfield',
      '#default_value' => isset($zip2) ? $zip2 : $default_zip2,
      '#size' => 4,
      '#maxlength' => 4,
      '#placeholder' => '0000',
      '#required' => $required,
      '#element_validate' => [
        [$this, 'validateZipcode2'],
      ],
    ];

    // カスタムバリデーションを追加
    $element['#element_validate'][] = [$this, 'validateBothZipcodes'];
    return $element;
  }

  /**
   * カスタムバリデーション関数
   */
  public function validateZipcode1(array &$element, FormStateInterface $form_state, array &$complete_form)
  {
    $val = $element['#value'];

    if ($val !== '' && !preg_match('/^\d{3}$/', $val)) {
      $form_state->setError($element, '郵便番号の前半部分は3桁の数字で入力してください');
    }
  }

  public function validateZipcode2(array &$element, FormStateInterface $form_state, array &$complete_form)
  {
    $val = $element['#value'];
    if ($val != '' && !preg_match('/^\d{4}$/', $val)) {
      $form_state->setError($element, '郵便番号の前半部分は4桁の数字で入力してください');
    }
  }


  /**
   * 両方の郵便番号フィールドのカスタムバリデーション関数
   */
  public function validateBothZipcodes(array &$element, FormStateInterface $form_state, array &$complete_form)
  {
  
    $zip1 = $element['zip1']['#value'];
    $zip2 = $element['zip2']['#value'];    
    if (($zip1 != '' && $zip2 == '') || ($zip1 == '' && $zip2 != '')) {
      $form_state->setError($element, '両方の郵便番号フィールドを入力してください。');
    }
  }
}
