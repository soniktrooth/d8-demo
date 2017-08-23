<?php

namespace Drupal\josh_paragraphs\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemInterface;
use Drupal\kalagraphs\Plugin\Field\FieldFormatter\KalagraphsLinkFormatter;

/**
 * Plugin implementation of the 'josh_link' formatter.
 *
 * @FieldFormatter(
 *   id = "josh_link",
 *   label = @Translation("Josh Link"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class JoshLinkFormatter extends KalagraphsLinkFormatter {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    return [t('Josh\'s Link Field Formatter')];
  }

  /**
   * {@inheritdoc}
   */
  protected function viewValue(FieldItemInterface $item) {

    // Allow Kalagraphs to populate the default template variables.
    $value = parent::viewValue($item);

    // Indicate which theme hook (i.e., twig template) to use for rendering.
    $value['#theme'] = "kalastatic__link";

    // Add component-specific overrides.
    switch ($this->kalagraphsType) {

      // Style the "Sidebar Navigation" links as vertical tabs.
      case 'vertical_tabs':
        $value['#class'][] = 'tab';
        break;

      // Render the Tout CTA component's links as icons.
      case 'tout__cta':
      case 'card__program':
        $value['#theme'] = 'kalastatic__link__icon';
        $value['#class'][] = 'link--icon';
        break;

      // Render tout__list links with no classes.
      case 'tout__list':
        break;

      // Most everything else renders as a button.
      default:
        $value['#class'][] = 'button';
    }

    // Return the individual item render array.
    return $value;
  }

}
