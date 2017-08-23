<?php

namespace Drupal\josh_paragraphs\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemInterface;
use Drupal\kalagraphs\Plugin\Field\FieldFormatter\KalagraphsImageFormatter;

/**
 * Plugin implementation of the 'josh_image' formatter.
 *
 * @FieldFormatter(
 *   id = "josh_image",
 *   label = @Translation("Josh Image"),
 *   field_types = {
 *     "image"
 *   }
 * )
 */
class JoshImageFormatter extends KalagraphsImageFormatter {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    return [t('Josh\'s Image Field Formatter')];
  }

  /**
   * {@inheritdoc}
   */
  protected function viewValue(FieldItemInterface $item) {

    // Allow Kalagraphs to populate the default template variables.
    $value = parent::viewValue($item);

    // Indicate which theme hook (i.e., twig template) to use for rendering.
    $value['#theme'] = "kalastatic__image";

xdebug_break();

    // Add component-specific overrides.
    switch ($this->kalagraphsType) {
      // Ask the Megatout to use the "background image" template.
      case 'tout__mega':
      case 'tout--mega--centered':
        $value['#theme'] = 'kalastatic__image__bg';
        $value['#width'] = '800';
        $value['#height'] = '300';
        break;

      case 'hero':
      case 'tout__cta':
      case 'testimonial':
        // $value['#uri'] = $item->entity->getFileUri();
        // $value['#theme'] = 'responsive_image';
        //
        // switch ($this->kalagraphsType) {
        //   case 'hero':
        //     $value['#responsive_image_style_id'] = 'josh_responsive_image';
        //     break;
        //
        // }
        break;

    }

    // Return the individual item render array.
    return $value;
  }

}
