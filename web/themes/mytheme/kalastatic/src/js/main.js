/**
 * @file
 * JS for SOD.
 */

(function (Drupal, sod, $, window) {
  'use strict';
  Drupal.behaviors.sod = {
    attach: function (context, settings) {
      $(window).on('resize', function () {
        sod.matchHeights();
        sod.flipMenu();
        sod.searchEnable(context);
      });

      sod.replaceLinkIconsWithInlineSVG();
      sod.matchHeights();
      sod.searchEnable(context);
      sod.searchToggle(context);
      sod.flipMenu();
      sod.profileTeaser(context);
      sod.searchSubmitAction();
    }
  };

  sod.profileTeaser = function (context) {
    // Vist the associated url when clicking on the image but keep our anchors
    // clean.
    $('.profile--faculty--teaser', context).once('profile--faculty--teaser').each(function () {
      var $this = $(this);
      var img = $this.find('img').css('cursor', 'pointer');
      var href = $this.find('h3 a').attr('href');
      img.on('click', function () {
        window.location = href;
      });
    });
  };

  sod.flipMenu = function () {
    var navPrimary = $('#nav--primary');
    var navSecondary = $('#nav--secondary');
    if (window.matchMedia('(max-width: 767px)').matches) {
      // We're showing the mobile menu so let's get to work.
      navPrimary.insertBefore(navSecondary);
    }
    else {
      // We're showing the desktop menu so put it back the way it was.
      navPrimary.insertAfter(navSecondary);
    }
  };

  sod.matchHeights = function () {
    var touts = $('.tout--cta');
    if (touts.length > 1) {
      touts.find('.tout__title').matchHeight();
      touts.find('.tout__blurb').matchHeight();
      touts.find('.content__wrapper').matchHeight();
      touts.matchHeight();
    }

    var cards = $('.card--program');
    if (cards.length > 1) {
      cards.find('.content__wrapper').matchHeight();
      cards.find('.link--icon').matchHeight();
      cards.matchHeight();
    }

    var newsTeasers = $('.news__items .views-row');
    if (newsTeasers.length > 1) {
      newsTeasers.find('.field-title').matchHeight();
      newsTeasers.find('.field-content').matchHeight();
      newsTeasers.find('.card').matchHeight();
    }
  };

  sod.replaceLinkIconsWithInlineSVG = function () {
    // Replaces the svgs in the img tag with actual embedded svg,this allows us
    // to use css to style them e.g.for rollovers.
    jQuery('.link--icon .icon').each(function () {
      var $img = jQuery(this);
      var imgID = $img.attr('id');
      var imgClass = $img.attr('class');
      var imgURL = $img.attr('src');

      jQuery.get(imgURL, function (data) {
        // Get the SVG tag, ignore the rest.
        var $svg = jQuery(data).find('svg');

        // Add replaced image's ID to the new SVG.
        if (typeof imgID !== 'undefined') {
          $svg = $svg.attr('id', imgID);
        }
        // Add replaced image's classes to the new SVG.
        if (typeof imgClass !== 'undefined') {
          $svg = $svg.attr('class', imgClass + ' replaced-svg');
        }

        // Remove any invalid XML tags as per http://validator.w3.org.
        $svg = $svg.removeAttr('xmlns:a');

        // Replace image with new SVG.
        $img.replaceWith($svg);
      }, 'xml');
    });
  };

  sod.searchSubmitAction = function () {
    $('.search-block-form form').submit(function (e) {
      e.preventDefault();
      var keys = $('.form-search').val();
      var url = $(this).attr('action') + '?keys=' + keys;
      window.location.href = url;
    });
  };

  sod.searchEnable = function (context) {
    // We need to disable the search form elements on desktop initially but not
    // on mobile. This prevents disappearing focus on tabbing.
    var searchForm = $('[id ^= "search-block-form"]', context);
    var input = searchForm.find('input.form-search');
    var button = searchForm.find('input.form-submit');

    if (window.matchMedia('(min-width: 768px)').matches) {
      input.add(button).attr('disabled', 'disabled');
    }
    else {
      input.add(button).removeAttr('disabled');
    }
  };

  sod.searchToggle = function (context) {
    var searchForm = $('[id ^= "search-block-form"]', context);
    var input = searchForm.find('input.form-search');
    var button = searchForm.find('input.form-submit');

    searchForm.css('display', 'block').once('searchToggle').each(function () {
      // React to click on search link.
      $('.search-toggle').on('click', function (e) {
        e.preventDefault();

        var showClass = 'show';
        // Toggle classes on the form and the clicked link.
        searchForm.toggleClass(showClass).promise().done(function () {
          // Make sure to focus/blur when showing/hiding.
          if (searchForm.hasClass(showClass)) {
            input.removeAttr('disabled').focus();
            button.removeAttr('disabled');
          }
          else {
            input.attr('disabled', 'disabled').blur();
            button.attr('disabled', 'disabled');
          }
        });

        // Toggle active class on trigger.
        $(this).toggleClass('active');

        // Swap out the search icon for a close icon to make it obvious it's a
        // toggle.
        var icon = $(this).find('i.fa');
        icon.toggleClass('fa-search');
        icon.toggleClass('fa-times-circle');
      });
    });
  };

}(Drupal, window.sod = window.sod || {}, window.jQuery, window));
