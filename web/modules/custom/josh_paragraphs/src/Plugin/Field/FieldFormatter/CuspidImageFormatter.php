<?php

namespace Drupal\sod_paragraphs\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemInterface;
use Drupal\kalagraphs\Plugin\Field\FieldFormatter\KalagraphsImageFormatter;

/**
 * Plugin implementation of the 'sod_image' formatter.
 *
 * @FieldFormatter(
 *   id = "sod_image",
 *   label = @Translation("School of Dentistry Image"),
 *   field_types = {
 *     "image"
 *   }
 * )
 */
class CuspidImageFormatter extends KalagraphsImageFormatter {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    return [t('School of Dentistry Image Field Formatter')];
  }

  /**
   * {@inheritdoc}
   */
  protected function viewValue(FieldItemInterface $item) {

    // Allow Kalagraphs to populate the default template variables.
    $value = parent::viewValue($item);

    // Indicate which theme hook (i.e., twig template) to use for rendering.
    $value['#theme'] = "kalastatic__image";

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
        $value['#uri'] = $item->entity->getFileUri();
        $value['#theme'] = 'responsive_image';

        switch ($this->kalagraphsType) {
          case 'hero':
            $value['#responsive_image_style_id'] = 'sod_hero';
            break;

          case 'tout__cta':
            $value['#responsive_image_style_id'] = 'sod_tout';
            break;

          case 'testimonial':
            $value['#responsive_image_style_id'] = 'sod_testimonial';
            break;
        }
        break;

    }

    // Return the individual item render array.
    return $value;
  }

}
