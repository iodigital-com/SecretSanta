webpackJsonp([5],{

/***/ "./node_modules/jquery-smooth-scroll/jquery.smooth-scroll.js":
/*!*******************************************************************!*\
  !*** ./node_modules/jquery-smooth-scroll/jquery.smooth-scroll.js ***!
  \*******************************************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * jQuery Smooth Scroll - v2.2.0 - 2017-05-05
 * https://github.com/kswedberg/jquery-smooth-scroll
 * Copyright (c) 2017 Karl Swedberg
 * Licensed MIT
 */

(function(factory) {
  if (true) {
    // AMD. Register as an anonymous module.
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else if (typeof module === 'object' && module.exports) {
    // CommonJS
    factory(require('jquery'));
  } else {
    // Browser globals
    factory(jQuery);
  }
}(function($) {

  var version = '2.2.0';
  var optionOverrides = {};
  var defaults = {
    exclude: [],
    excludeWithin: [],
    offset: 0,

    // one of 'top' or 'left'
    direction: 'top',

    // if set, bind click events through delegation
    //  supported since jQuery 1.4.2
    delegateSelector: null,

    // jQuery set of elements you wish to scroll (for $.smoothScroll).
    //  if null (default), $('html, body').firstScrollable() is used.
    scrollElement: null,

    // only use if you want to override default behavior
    scrollTarget: null,

    // automatically focus the target element after scrolling to it
    autoFocus: false,

    // fn(opts) function to be called before scrolling occurs.
    // `this` is the element(s) being scrolled
    beforeScroll: function() {},

    // fn(opts) function to be called after scrolling occurs.
    // `this` is the triggering element
    afterScroll: function() {},

    // easing name. jQuery comes with "swing" and "linear." For others, you'll need an easing plugin
    // from jQuery UI or elsewhere
    easing: 'swing',

    // speed can be a number or 'auto'
    // if 'auto', the speed will be calculated based on the formula:
    // (current scroll position - target scroll position) / autoCoeffic
    speed: 400,

    // coefficient for "auto" speed
    autoCoefficient: 2,

    // $.fn.smoothScroll only: whether to prevent the default click action
    preventDefault: true
  };

  var getScrollable = function(opts) {
    var scrollable = [];
    var scrolled = false;
    var dir = opts.dir && opts.dir === 'left' ? 'scrollLeft' : 'scrollTop';

    this.each(function() {
      var el = $(this);

      if (this === document || this === window) {
        return;
      }

      if (document.scrollingElement && (this === document.documentElement || this === document.body)) {
        scrollable.push(document.scrollingElement);

        return false;
      }

      if (el[dir]() > 0) {
        scrollable.push(this);
      } else {
        // if scroll(Top|Left) === 0, nudge the element 1px and see if it moves
        el[dir](1);
        scrolled = el[dir]() > 0;

        if (scrolled) {
          scrollable.push(this);
        }
        // then put it back, of course
        el[dir](0);
      }
    });

    if (!scrollable.length) {
      this.each(function() {
        // If no scrollable elements and <html> has scroll-behavior:smooth because
        // "When this property is specified on the root element, it applies to the viewport instead."
        // and "The scroll-behavior property of the … body element is *not* propagated to the viewport."
        // → https://drafts.csswg.org/cssom-view/#propdef-scroll-behavior
        if (this === document.documentElement && $(this).css('scrollBehavior') === 'smooth') {
          scrollable = [this];
        }

        // If still no scrollable elements, fall back to <body>,
        // if it's in the jQuery collection
        // (doing this because Safari sets scrollTop async,
        // so can't set it to 1 and immediately get the value.)
        if (!scrollable.length && this.nodeName === 'BODY') {
          scrollable = [this];
        }
      });
    }

    // Use the first scrollable element if we're calling firstScrollable()
    if (opts.el === 'first' && scrollable.length > 1) {
      scrollable = [scrollable[0]];
    }

    return scrollable;
  };

  var rRelative = /^([\-\+]=)(\d+)/;

  $.fn.extend({
    scrollable: function(dir) {
      var scrl = getScrollable.call(this, {dir: dir});

      return this.pushStack(scrl);
    },
    firstScrollable: function(dir) {
      var scrl = getScrollable.call(this, {el: 'first', dir: dir});

      return this.pushStack(scrl);
    },

    smoothScroll: function(options, extra) {
      options = options || {};

      if (options === 'options') {
        if (!extra) {
          return this.first().data('ssOpts');
        }

        return this.each(function() {
          var $this = $(this);
          var opts = $.extend($this.data('ssOpts') || {}, extra);

          $(this).data('ssOpts', opts);
        });
      }

      var opts = $.extend({}, $.fn.smoothScroll.defaults, options);

      var clickHandler = function(event) {
        var escapeSelector = function(str) {
          return str.replace(/(:|\.|\/)/g, '\\$1');
        };

        var link = this;
        var $link = $(this);
        var thisOpts = $.extend({}, opts, $link.data('ssOpts') || {});
        var exclude = opts.exclude;
        var excludeWithin = thisOpts.excludeWithin;
        var elCounter = 0;
        var ewlCounter = 0;
        var include = true;
        var clickOpts = {};
        var locationPath = $.smoothScroll.filterPath(location.pathname);
        var linkPath = $.smoothScroll.filterPath(link.pathname);
        var hostMatch = location.hostname === link.hostname || !link.hostname;
        var pathMatch = thisOpts.scrollTarget || (linkPath === locationPath);
        var thisHash = escapeSelector(link.hash);

        if (thisHash && !$(thisHash).length) {
          include = false;
        }

        if (!thisOpts.scrollTarget && (!hostMatch || !pathMatch || !thisHash)) {
          include = false;
        } else {
          while (include && elCounter < exclude.length) {
            if ($link.is(escapeSelector(exclude[elCounter++]))) {
              include = false;
            }
          }

          while (include && ewlCounter < excludeWithin.length) {
            if ($link.closest(excludeWithin[ewlCounter++]).length) {
              include = false;
            }
          }
        }

        if (include) {
          if (thisOpts.preventDefault) {
            event.preventDefault();
          }

          $.extend(clickOpts, thisOpts, {
            scrollTarget: thisOpts.scrollTarget || thisHash,
            link: link
          });

          $.smoothScroll(clickOpts);
        }
      };

      if (options.delegateSelector !== null) {
        this
        .off('click.smoothscroll', options.delegateSelector)
        .on('click.smoothscroll', options.delegateSelector, clickHandler);
      } else {
        this
        .off('click.smoothscroll')
        .on('click.smoothscroll', clickHandler);
      }

      return this;
    }
  });

  var getExplicitOffset = function(val) {
    var explicit = {relative: ''};
    var parts = typeof val === 'string' && rRelative.exec(val);

    if (typeof val === 'number') {
      explicit.px = val;
    } else if (parts) {
      explicit.relative = parts[1];
      explicit.px = parseFloat(parts[2]) || 0;
    }

    return explicit;
  };

  var onAfterScroll = function(opts) {
    var $tgt = $(opts.scrollTarget);

    if (opts.autoFocus && $tgt.length) {
      $tgt[0].focus();

      if (!$tgt.is(document.activeElement)) {
        $tgt.prop({tabIndex: -1});
        $tgt[0].focus();
      }
    }

    opts.afterScroll.call(opts.link, opts);
  };

  $.smoothScroll = function(options, px) {
    if (options === 'options' && typeof px === 'object') {
      return $.extend(optionOverrides, px);
    }
    var opts, $scroller, speed, delta;
    var explicitOffset = getExplicitOffset(options);
    var scrollTargetOffset = {};
    var scrollerOffset = 0;
    var offPos = 'offset';
    var scrollDir = 'scrollTop';
    var aniProps = {};
    var aniOpts = {};

    if (explicitOffset.px) {
      opts = $.extend({link: null}, $.fn.smoothScroll.defaults, optionOverrides);
    } else {
      opts = $.extend({link: null}, $.fn.smoothScroll.defaults, options || {}, optionOverrides);

      if (opts.scrollElement) {
        offPos = 'position';

        if (opts.scrollElement.css('position') === 'static') {
          opts.scrollElement.css('position', 'relative');
        }
      }

      if (px) {
        explicitOffset = getExplicitOffset(px);
      }
    }

    scrollDir = opts.direction === 'left' ? 'scrollLeft' : scrollDir;

    if (opts.scrollElement) {
      $scroller = opts.scrollElement;

      if (!explicitOffset.px && !(/^(?:HTML|BODY)$/).test($scroller[0].nodeName)) {
        scrollerOffset = $scroller[scrollDir]();
      }
    } else {
      $scroller = $('html, body').firstScrollable(opts.direction);
    }

    // beforeScroll callback function must fire before calculating offset
    opts.beforeScroll.call($scroller, opts);

    scrollTargetOffset = explicitOffset.px ? explicitOffset : {
      relative: '',
      px: ($(opts.scrollTarget)[offPos]() && $(opts.scrollTarget)[offPos]()[opts.direction]) || 0
    };

    aniProps[scrollDir] = scrollTargetOffset.relative + (scrollTargetOffset.px + scrollerOffset + opts.offset);

    speed = opts.speed;

    // automatically calculate the speed of the scroll based on distance / coefficient
    if (speed === 'auto') {

      // $scroller[scrollDir]() is position before scroll, aniProps[scrollDir] is position after
      // When delta is greater, speed will be greater.
      delta = Math.abs(aniProps[scrollDir] - $scroller[scrollDir]());

      // Divide the delta by the coefficient
      speed = delta / opts.autoCoefficient;
    }

    aniOpts = {
      duration: speed,
      easing: opts.easing,
      complete: function() {
        onAfterScroll(opts);
      }
    };

    if (opts.step) {
      aniOpts.step = opts.step;
    }

    if ($scroller.length) {
      $scroller.stop().animate(aniProps, aniOpts);
    } else {
      onAfterScroll(opts);
    }
  };

  $.smoothScroll.version = version;
  $.smoothScroll.filterPath = function(string) {
    string = string || '';

    return string
      .replace(/^\//, '')
      .replace(/(?:index|default).[a-zA-Z]{3,4}$/, '')
      .replace(/\/$/, '');
  };

  // default options
  $.fn.smoothScroll.defaults = defaults;

}));



/***/ }),

/***/ "./src/Intracto/SecretSantaBundle/Resources/public/js/party.create.js":
/*!****************************************************************************!*\
  !*** ./src/Intracto/SecretSantaBundle/Resources/public/js/party.create.js ***!
  \****************************************************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($, jQuery) {__webpack_require__(/*! jquery-smooth-scroll */ "./node_modules/jquery-smooth-scroll/jquery.smooth-scroll.js");

exports.addNewParticipant = function (collectionHolder, email, name) {
    addNewParticipant(collectionHolder, email, name);
};

function addNewParticipant(collectionHolder, email, name) {
    // Get participant prototype as defined in attribute data-prototype
    var prototype = collectionHolder.attr('data-prototype');
    // Adjust participant prototype for correct naming
    var number_of_participants = collectionHolder.children().length - 1; // Note, owner is not counted as participant
    var newFormHtml = prototype.replace(/__name__/g, number_of_participants).replace(/__participantcount__/g, number_of_participants + 1);
    // Add new participant to party with animation
    var newForm = $(newFormHtml);
    collectionHolder.append(newForm);

    if (typeof email !== 'undefined' && typeof name !== 'undefined') {
        // email and name provided, fill in the blanks
        $(newForm).find('.participant-mail').attr('value', email);
        $(newForm).find('.participant-name').attr('value', name);
        newForm.show();
    } else {
        newForm.show(300);
    }

    // Handle delete button events
    bindDeleteButtonEvents();
    // Remove disabled state on delete-buttons
    $('.remove-participant').removeClass('disabled');
}
function bindDeleteButtonEvents() {
    // Loop over all delete buttons
    $('button.remove-participant').each(function (i) {
        // Remove any previously binded event
        $(this).off('click');
        // Bind event
        $(this).click(function (e) {
            e.preventDefault();
            $('table tr.participant.not-owner:gt(' + i + ')').each(function (j) {
                // Move values from next row to current row
                var next_row_name = $('table tr.participant.not-owner:eq(' + (i + j + 1) + ') input.participant-name').val();
                var next_row_mail = $('table tr.participant.not-owner:eq(' + (i + j + 1) + ') input.participant-mail').val();
                $('table tr.participant.not-owner:eq(' + (i + j) + ') input.participant-name').val(next_row_name);
                $('table tr.participant.not-owner:eq(' + (i + j) + ') input.participant-mail').val(next_row_mail);
            });
            // Delete last row
            $('table tr.participant.not-owner:last').remove();
            // Remove delete events when deletable participants < 3
            if ($('table tr.participant.not-owner').length < 3) {
                $('table tr.participant.not-owner button.remove-participant').addClass('disabled');
                $('table tr.participant.not-owner button.remove-participant').off('click');
            }
        });
    });
}
/* Variables */
var collectionHolder = $('table.participants tbody');
/* Document Ready */
jQuery(document).ready(function () {
    //Add eventlistener on add-new-participant button
    $('.add-new-participant').click(function (e) {
        e.preventDefault();
        addNewParticipant(collectionHolder);
    });
    // If form has more then 3 participants, provide delete functionality
    if ($('table tr.participant').length > 3) {
        bindDeleteButtonEvents();
        $('.remove-participant').removeClass('disabled');
    }
    // Add smooth scroll
    $('a.btn-started').click(function () {
        $.smoothScroll({
            scrollTarget: '#mysanta'
        });
        return false;
    });
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ })

},["./src/Intracto/SecretSantaBundle/Resources/public/js/party.create.js"]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvanF1ZXJ5LXNtb290aC1zY3JvbGwvanF1ZXJ5LnNtb290aC1zY3JvbGwuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL0ludHJhY3RvL1NlY3JldFNhbnRhQnVuZGxlL1Jlc291cmNlcy9wdWJsaWMvanMvcGFydHkuY3JlYXRlLmpzIl0sIm5hbWVzIjpbInJlcXVpcmUiLCJleHBvcnRzIiwiYWRkTmV3UGFydGljaXBhbnQiLCJjb2xsZWN0aW9uSG9sZGVyIiwiZW1haWwiLCJuYW1lIiwicHJvdG90eXBlIiwiYXR0ciIsIm51bWJlcl9vZl9wYXJ0aWNpcGFudHMiLCJjaGlsZHJlbiIsImxlbmd0aCIsIm5ld0Zvcm1IdG1sIiwicmVwbGFjZSIsIm5ld0Zvcm0iLCIkIiwiYXBwZW5kIiwiZmluZCIsInNob3ciLCJiaW5kRGVsZXRlQnV0dG9uRXZlbnRzIiwicmVtb3ZlQ2xhc3MiLCJlYWNoIiwiaSIsIm9mZiIsImNsaWNrIiwiZSIsInByZXZlbnREZWZhdWx0IiwiaiIsIm5leHRfcm93X25hbWUiLCJ2YWwiLCJuZXh0X3Jvd19tYWlsIiwicmVtb3ZlIiwiYWRkQ2xhc3MiLCJqUXVlcnkiLCJkb2N1bWVudCIsInJlYWR5Iiwic21vb3RoU2Nyb2xsIiwic2Nyb2xsVGFyZ2V0Il0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7O0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQUE7QUFBQTtBQUFBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0EsQ0FBQzs7QUFFRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsK0JBQStCOztBQUUvQjtBQUNBO0FBQ0EsOEJBQThCOztBQUU5QjtBQUNBO0FBQ0E7O0FB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SCxDQWxCRCxFIiwiZmlsZSI6ImpzL3BhcnR5LmNyZWF0ZS4xYWY4M2FiZWExODk3MTM4NWZkZi5qcyIsInNvdXJjZXNDb250ZW50IjpbIi8qIVxuICogalF1ZXJ5IFNtb290aCBTY3JvbGwgLSB2Mi4yLjAgLSAyMDE3LTA1LTA1XG4gKiBodHRwczovL2dpdGh1Yi5jb20va3N3ZWRiZXJnL2pxdWVyeS1zbW9vdGgtc2Nyb2xsXG4gKiBDb3B5cmlnaHQgKGMpIDIwMTcgS2FybCBTd2VkYmVyZ1xuICogTGljZW5zZWQgTUlUXG4gKi9cblxuKGZ1bmN0aW9uKGZhY3RvcnkpIHtcbiAgaWYgKHR5cGVvZiBkZWZpbmUgPT09ICdmdW5jdGlvbicgJiYgZGVmaW5lLmFtZCkge1xuICAgIC8vIEFNRC4gUmVnaXN0ZXIgYXMgYW4gYW5vbnltb3VzIG1vZHVsZS5cbiAgICBkZWZpbmUoWydqcXVlcnknXSwgZmFjdG9yeSk7XG4gIH0gZWxzZSBpZiAodHlwZW9mIG1vZHVsZSA9PT0gJ29iamVjdCcgJiYgbW9kdWxlLmV4cG9ydHMpIHtcbiAgICAvLyBDb21tb25KU1xuICAgIGZhY3RvcnkocmVxdWlyZSgnanF1ZXJ5JykpO1xuICB9IGVsc2Uge1xuICAgIC8vIEJyb3dzZXIgZ2xvYmFsc1xuICAgIGZhY3RvcnkoalF1ZXJ5KTtcbiAgfVxufShmdW5jdGlvbigkKSB7XG5cbiAgdmFyIHZlcnNpb24gPSAnMi4yLjAnO1xuICB2YXIgb3B0aW9uT3ZlcnJpZGVzID0ge307XG4gIHZhciBkZWZhdWx0cyA9IHtcbiAgICBleGNsdWRlOiBbXSxcbiAgICBleGNsdWRlV2l0aGluOiBbXSxcbiAgICBvZmZzZXQ6IDAsXG5cbiAgICAvLyBvbmUgb2YgJ3RvcCcgb3IgJ2xlZnQnXG4gICAgZGlyZWN0aW9uOiAndG9wJyxcblxuICAgIC8vIGlmIHNldCwgYmluZCBjbGljayBldmVudHMgdGhyb3VnaCBkZWxlZ2F0aW9uXG4gICAgLy8gIHN1cHBvcnRlZCBzaW5jZSBqUXVlcnkgMS40LjJcbiAgICBkZWxlZ2F0ZVNlbGVjdG9yOiBudWxsLFxuXG4gICAgLy8galF1ZXJ5IHNldCBvZiBlbGVtZW50cyB5b3Ugd2lzaCB0byBzY3JvbGwgKGZvciAkLnNtb290aFNjcm9sbCkuXG4gICAgLy8gIGlmIG51bGwgKGRlZmF1bHQpLCAkKCdodG1sLCBib2R5JykuZmlyc3RTY3JvbGxhYmxlKCkgaXMgdXNlZC5cbiAgICBzY3JvbGxFbGVtZW50OiBudWxsLFxuXG4gICAgLy8gb25seSB1c2UgaWYgeW91IHdhbnQgdG8gb3ZlcnJpZGUgZGVmYXVsdCBiZWhhdmlvclxuICAgIHNjcm9sbFRhcmdldDogbnVsbCxcblxuICAgIC8vIGF1dG9tYXRpY2FsbHkgZm9jdXMgdGhlIHRhcmdldCBlbGVtZW50IGFmdGVyIHNjcm9sbGluZyB0byBpdFxuICAgIGF1dG9Gb2N1czogZmFsc2UsXG5cbiAgICAvLyBmbihvcHRzKSBmdW5jdGlvbiB0byBiZSBjYWxsZWQgYmVmb3JlIHNjcm9sbGluZyBvY2N1cnMuXG4gICAgLy8gYHRoaXNgIGlzIHRoZSBlbGVtZW50KHMpIGJlaW5nIHNjcm9sbGVkXG4gICAgYmVmb3JlU2Nyb2xsOiBmdW5jdGlvbigpIHt9LFxuXG4gICAgLy8gZm4ob3B0cykgZnVuY3Rpb24gdG8gYmUgY2FsbGVkIGFmdGVyIHNjcm9sbGluZyBvY2N1cnMuXG4gICAgLy8gYHRoaXNgIGlzIHRoZSB0cmlnZ2VyaW5nIGVsZW1lbnRcbiAgICBhZnRlclNjcm9sbDogZnVuY3Rpb24oKSB7fSxcblxuICAgIC8vIGVhc2luZyBuYW1lLiBqUXVlcnkgY29tZXMgd2l0aCBcInN3aW5nXCIgYW5kIFwibGluZWFyLlwiIEZvciBvdGhlcnMsIHlvdSdsbCBuZWVkIGFuIGVhc2luZyBwbHVnaW5cbiAgICAvLyBmcm9tIGpRdWVyeSBVSSBvciBlbHNld2hlcmVcbiAgICBlYXNpbmc6ICdzd2luZycsXG5cbiAgICAvLyBzcGVlZCBjYW4gYmUgYSBudW1iZXIgb3IgJ2F1dG8nXG4gICAgLy8gaWYgJ2F1dG8nLCB0aGUgc3BlZWQgd2lsbCBiZSBjYWxjdWxhdGVkIGJhc2VkIG9uIHRoZSBmb3JtdWxhOlxuICAgIC8vIChjdXJyZW50IHNjcm9sbCBwb3NpdGlvbiAtIHRhcmdldCBzY3JvbGwgcG9zaXRpb24pIC8gYXV0b0NvZWZmaWNcbiAgICBzcGVlZDogNDAwLFxuXG4gICAgLy8gY29lZmZpY2llbnQgZm9yIFwiYXV0b1wiIHNwZWVkXG4gICAgYXV0b0NvZWZmaWNpZW50OiAyLFxuXG4gICAgLy8gJC5mbi5zbW9vdGhTY3JvbGwgb25seTogd2hldGhlciB0byBwcmV2ZW50IHRoZSBkZWZhdWx0IGNsaWNrIGFjdGlvblxuICAgIHByZXZlbnREZWZhdWx0OiB0cnVlXG4gIH07XG5cbiAgdmFyIGdldFNjcm9sbGFibGUgPSBmdW5jdGlvbihvcHRzKSB7XG4gICAgdmFyIHNjcm9sbGFibGUgPSBbXTtcbiAgICB2YXIgc2Nyb2xsZWQgPSBmYWxzZTtcbiAgICB2YXIgZGlyID0gb3B0cy5kaXIgJiYgb3B0cy5kaXIgPT09ICdsZWZ0JyA/ICdzY3JvbGxMZWZ0JyA6ICdzY3JvbGxUb3AnO1xuXG4gICAgdGhpcy5lYWNoKGZ1bmN0aW9uKCkge1xuICAgICAgdmFyIGVsID0gJCh0aGlzKTtcblxuICAgICAgaWYgKHRoaXMgPT09IGRvY3VtZW50IHx8IHRoaXMgPT09IHdpbmRvdykge1xuICAgICAgICByZXR1cm47XG4gICAgICB9XG5cbiAgICAgIGlmIChkb2N1bWVudC5zY3JvbGxpbmdFbGVtZW50ICYmICh0aGlzID09PSBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQgfHwgdGhpcyA9PT0gZG9jdW1lbnQuYm9keSkpIHtcbiAgICAgICAgc2Nyb2xsYWJsZS5wdXNoKGRvY3VtZW50LnNjcm9sbGluZ0VsZW1lbnQpO1xuXG4gICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgIH1cblxuICAgICAgaWYgKGVsW2Rpcl0oKSA+IDApIHtcbiAgICAgICAgc2Nyb2xsYWJsZS5wdXNoKHRoaXMpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgLy8gaWYgc2Nyb2xsKFRvcHxMZWZ0KSA9PT0gMCwgbnVkZ2UgdGhlIGVsZW1lbnQgMXB4IGFuZCBzZWUgaWYgaXQgbW92ZXNcbiAgICAgICAgZWxbZGlyXSgxKTtcbiAgICAgICAgc2Nyb2xsZWQgPSBlbFtkaXJdKCkgPiAwO1xuXG4gICAgICAgIGlmIChzY3JvbGxlZCkge1xuICAgICAgICAgIHNjcm9sbGFibGUucHVzaCh0aGlzKTtcbiAgICAgICAgfVxuICAgICAgICAvLyB0aGVuIHB1dCBpdCBiYWNrLCBvZiBjb3Vyc2VcbiAgICAgICAgZWxbZGlyXSgwKTtcbiAgICAgIH1cbiAgICB9KTtcblxuICAgIGlmICghc2Nyb2xsYWJsZS5sZW5ndGgpIHtcbiAgICAgIHRoaXMuZWFjaChmdW5jdGlvbigpIHtcbiAgICAgICAgLy8gSWYgbm8gc2Nyb2xsYWJsZSBlbGVtZW50cyBhbmQgPGh0bWw+IGhhcyBzY3JvbGwtYmVoYXZpb3I6c21vb3RoIGJlY2F1c2VcbiAgICAgICAgLy8gXCJXaGVuIHRoaXMgcHJvcGVydHkgaXMgc3BlY2lmaWVkIG9uIHRoZSByb290IGVsZW1lbnQsIGl0IGFwcGxpZXMgdG8gdGhlIHZpZXdwb3J0IGluc3RlYWQuXCJcbiAgICAgICAgLy8gYW5kIFwiVGhlIHNjcm9sbC1iZWhhdmlvciBwcm9wZXJ0eSBvZiB0aGUg4oCmIGJvZHkgZWxlbWVudCBpcyAqbm90KiBwcm9wYWdhdGVkIHRvIHRoZSB2aWV3cG9ydC5cIlxuICAgICAgICAvLyDihpIgaHR0cHM6Ly9kcmFmdHMuY3Nzd2cub3JnL2Nzc29tLXZpZXcvI3Byb3BkZWYtc2Nyb2xsLWJlaGF2aW9yXG4gICAgICAgIGlmICh0aGlzID09PSBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQgJiYgJCh0aGlzKS5jc3MoJ3Njcm9sbEJlaGF2aW9yJykgPT09ICdzbW9vdGgnKSB7XG4gICAgICAgICAgc2Nyb2xsYWJsZSA9IFt0aGlzXTtcbiAgICAgICAgfVxuXG4gICAgICAgIC8vIElmIHN0aWxsIG5vIHNjcm9sbGFibGUgZWxlbWVudHMsIGZhbGwgYmFjayB0byA8Ym9keT4sXG4gICAgICAgIC8vIGlmIGl0J3MgaW4gdGhlIGpRdWVyeSBjb2xsZWN0aW9uXG4gICAgICAgIC8vIChkb2luZyB0aGlzIGJlY2F1c2UgU2FmYXJpIHNldHMgc2Nyb2xsVG9wIGFzeW5jLFxuICAgICAgICAvLyBzbyBjYW4ndCBzZXQgaXQgdG8gMSBhbmQgaW1tZWRpYXRlbHkgZ2V0IHRoZSB2YWx1ZS4pXG4gICAgICAgIGlmICghc2Nyb2xsYWJsZS5sZW5ndGggJiYgdGhpcy5ub2RlTmFtZSA9PT0gJ0JPRFknKSB7XG4gICAgICAgICAgc2Nyb2xsYWJsZSA9IFt0aGlzXTtcbiAgICAgICAgfVxuICAgICAgfSk7XG4gICAgfVxuXG4gICAgLy8gVXNlIHRoZSBmaXJzdCBzY3JvbGxhYmxlIGVsZW1lbnQgaWYgd2UncmUgY2FsbGluZyBmaXJzdFNjcm9sbGFibGUoKVxuICAgIGlmIChvcHRzLmVsID09PSAnZmlyc3QnICYmIHNjcm9sbGFibGUubGVuZ3RoID4gMSkge1xuICAgICAgc2Nyb2xsYWJsZSA9IFtzY3JvbGxhYmxlWzBdXTtcbiAgICB9XG5cbiAgICByZXR1cm4gc2Nyb2xsYWJsZTtcbiAgfTtcblxuICB2YXIgclJlbGF0aXZlID0gL14oW1xcLVxcK109KShcXGQrKS87XG5cbiAgJC5mbi5leHRlbmQoe1xuICAgIHNjcm9sbGFibGU6IGZ1bmN0aW9uKGRpcikge1xuICAgICAgdmFyIHNjcmwgPSBnZXRTY3JvbGxhYmxlLmNhbGwodGhpcywge2RpcjogZGlyfSk7XG5cbiAgICAgIHJldHVybiB0aGlzLnB1c2hTdGFjayhzY3JsKTtcbiAgICB9LFxuICAgIGZpcnN0U2Nyb2xsYWJsZTogZnVuY3Rpb24oZGlyKSB7XG4gICAgICB2YXIgc2NybCA9IGdldFNjcm9sbGFibGUuY2FsbCh0aGlzLCB7ZWw6ICdmaXJzdCcsIGRpcjogZGlyfSk7XG5cbiAgICAgIHJldHVybiB0aGlzLnB1c2hTdGFjayhzY3JsKTtcbiAgICB9LFxuXG4gICAgc21vb3RoU2Nyb2xsOiBmdW5jdGlvbihvcHRpb25zLCBleHRyYSkge1xuICAgICAgb3B0aW9ucyA9IG9wdGlvbnMgfHwge307XG5cbiAgICAgIGlmIChvcHRpb25zID09PSAnb3B0aW9ucycpIHtcbiAgICAgICAgaWYgKCFleHRyYSkge1xuICAgICAgICAgIHJldHVybiB0aGlzLmZpcnN0KCkuZGF0YSgnc3NPcHRzJyk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uKCkge1xuICAgICAgICAgIHZhciAkdGhpcyA9ICQodGhpcyk7XG4gICAgICAgICAgdmFyIG9wdHMgPSAkLmV4dGVuZCgkdGhpcy5kYXRhKCdzc09wdHMnKSB8fCB7fSwgZXh0cmEpO1xuXG4gICAgICAgICAgJCh0aGlzKS5kYXRhKCdzc09wdHMnLCBvcHRzKTtcbiAgICAgICAgfSk7XG4gICAgICB9XG5cbiAgICAgIHZhciBvcHRzID0gJC5leHRlbmQoe30sICQuZm4uc21vb3RoU2Nyb2xsLmRlZmF1bHRzLCBvcHRpb25zKTtcblxuICAgICAgdmFyIGNsaWNrSGFuZGxlciA9IGZ1bmN0aW9uKGV2ZW50KSB7XG4gICAgICAgIHZhciBlc2NhcGVTZWxlY3RvciA9IGZ1bmN0aW9uKHN0cikge1xuICAgICAgICAgIHJldHVybiBzdHIucmVwbGFjZSgvKDp8XFwufFxcLykvZywgJ1xcXFwkMScpO1xuICAgICAgICB9O1xuXG4gICAgICAgIHZhciBsaW5rID0gdGhpcztcbiAgICAgICAgdmFyICRsaW5rID0gJCh0aGlzKTtcbiAgICAgICAgdmFyIHRoaXNPcHRzID0gJC5leHRlbmQoe30sIG9wdHMsICRsaW5rLmRhdGEoJ3NzT3B0cycpIHx8IHt9KTtcbiAgICAgICAgdmFyIGV4Y2x1ZGUgPSBvcHRzLmV4Y2x1ZGU7XG4gICAgICAgIHZhciBleGNsdWRlV2l0aGluID0gdGhpc09wdHMuZXhjbHVkZVdpdGhpbjtcbiAgICAgICAgdmFyIGVsQ291bnRlciA9IDA7XG4gICAgICAgIHZhciBld2xDb3VudGVyID0gMDtcbiAgICAgICAgdmFyIGluY2x1ZGUgPSB0cnVlO1xuICAgICAgICB2YXIgY2xpY2tPcHRzID0ge307XG4gICAgICAgIHZhciBsb2NhdGlvblBhdGggPSAkLnNtb290aFNjcm9sbC5maWx0ZXJQYXRoKGxvY2F0aW9uLnBhdGhuYW1lKTtcbiAgICAgICAgdmFyIGxpbmtQYXRoID0gJC5zbW9vdGhTY3JvbGwuZmlsdGVyUGF0aChsaW5rLnBhdGhuYW1lKTtcbiAgICAgICAgdmFyIGhvc3RNYXRjaCA9IGxvY2F0aW9uLmhvc3RuYW1lID09PSBsaW5rLmhvc3RuYW1lIHx8ICFsaW5rLmhvc3RuYW1lO1xuICAgICAgICB2YXIgcGF0aE1hdGNoID0gdGhpc09wdHMuc2Nyb2xsVGFyZ2V0IHx8IChsaW5rUGF0aCA9PT0gbG9jYXRpb25QYXRoKTtcbiAgICAgICAgdmFyIHRoaXNIYXNoID0gZXNjYXBlU2VsZWN0b3IobGluay5oYXNoKTtcblxuICAgICAgICBpZiAodGhpc0hhc2ggJiYgISQodGhpc0hhc2gpLmxlbmd0aCkge1xuICAgICAgICAgIGluY2x1ZGUgPSBmYWxzZTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmICghdGhpc09wdHMuc2Nyb2xsVGFyZ2V0ICYmICghaG9zdE1hdGNoIHx8ICFwYXRoTWF0Y2ggfHwgIXRoaXNIYXNoKSkge1xuICAgICAgICAgIGluY2x1ZGUgPSBmYWxzZTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICB3aGlsZSAoaW5jbHVkZSAmJiBlbENvdW50ZXIgPCBleGNsdWRlLmxlbmd0aCkge1xuICAgICAgICAgICAgaWYgKCRsaW5rLmlzKGVzY2FwZVNlbGVjdG9yKGV4Y2x1ZGVbZWxDb3VudGVyKytdKSkpIHtcbiAgICAgICAgICAgICAgaW5jbHVkZSA9IGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICAgIH1cblxuICAgICAgICAgIHdoaWxlIChpbmNsdWRlICYmIGV3bENvdW50ZXIgPCBleGNsdWRlV2l0aGluLmxlbmd0aCkge1xuICAgICAgICAgICAgaWYgKCRsaW5rLmNsb3Nlc3QoZXhjbHVkZVdpdGhpbltld2xDb3VudGVyKytdKS5sZW5ndGgpIHtcbiAgICAgICAgICAgICAgaW5jbHVkZSA9IGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChpbmNsdWRlKSB7XG4gICAgICAgICAgaWYgKHRoaXNPcHRzLnByZXZlbnREZWZhdWx0KSB7XG4gICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgIH1cblxuICAgICAgICAgICQuZXh0ZW5kKGNsaWNrT3B0cywgdGhpc09wdHMsIHtcbiAgICAgICAgICAgIHNjcm9sbFRhcmdldDogdGhpc09wdHMuc2Nyb2xsVGFyZ2V0IHx8IHRoaXNIYXNoLFxuICAgICAgICAgICAgbGluazogbGlua1xuICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgJC5zbW9vdGhTY3JvbGwoY2xpY2tPcHRzKTtcbiAgICAgICAgfVxuICAgICAgfTtcblxuICAgICAgaWYgKG9wdGlvbnMuZGVsZWdhdGVTZWxlY3RvciAhPT0gbnVsbCkge1xuICAgICAgICB0aGlzXG4gICAgICAgIC5vZmYoJ2NsaWNrLnNtb290aHNjcm9sbCcsIG9wdGlvbnMuZGVsZWdhdGVTZWxlY3RvcilcbiAgICAgICAgLm9uKCdjbGljay5zbW9vdGhzY3JvbGwnLCBvcHRpb25zLmRlbGVnYXRlU2VsZWN0b3IsIGNsaWNrSGFuZGxlcik7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICB0aGlzXG4gICAgICAgIC5vZmYoJ2NsaWNrLnNtb290aHNjcm9sbCcpXG4gICAgICAgIC5vbignY2xpY2suc21vb3Roc2Nyb2xsJywgY2xpY2tIYW5kbGVyKTtcbiAgICAgIH1cblxuICAgICAgcmV0dXJuIHRoaXM7XG4gICAgfVxuICB9KTtcblxuICB2YXIgZ2V0RXhwbGljaXRPZmZzZXQgPSBmdW5jdGlvbih2YWwpIHtcbiAgICB2YXIgZXhwbGljaXQgPSB7cmVsYXRpdmU6ICcnfTtcbiAgICB2YXIgcGFydHMgPSB0eXBlb2YgdmFsID09PSAnc3RyaW5nJyAmJiByUmVsYXRpdmUuZXhlYyh2YWwpO1xuXG4gICAgaWYgKHR5cGVvZiB2YWwgPT09ICdudW1iZXInKSB7XG4gICAgICBleHBsaWNpdC5weCA9IHZhbDtcbiAgICB9IGVsc2UgaWYgKHBhcnRzKSB7XG4gICAgICBleHBsaWNpdC5yZWxhdGl2ZSA9IHBhcnRzWzFdO1xuICAgICAgZXhwbGljaXQucHggPSBwYXJzZUZsb2F0KHBhcnRzWzJdKSB8fCAwO1xuICAgIH1cblxuICAgIHJldHVybiBleHBsaWNpdDtcbiAgfTtcblxuICB2YXIgb25BZnRlclNjcm9sbCA9IGZ1bmN0aW9uKG9wdHMpIHtcbiAgICB2YXIgJHRndCA9ICQob3B0cy5zY3JvbGxUYXJnZXQpO1xuXG4gICAgaWYgKG9wdHMuYXV0b0ZvY3VzICYmICR0Z3QubGVuZ3RoKSB7XG4gICAgICAkdGd0WzBdLmZvY3VzKCk7XG5cbiAgICAgIGlmICghJHRndC5pcyhkb2N1bWVudC5hY3RpdmVFbGVtZW50KSkge1xuICAgICAgICAkdGd0LnByb3Aoe3RhYkluZGV4OiAtMX0pO1xuICAgICAgICAkdGd0WzBdLmZvY3VzKCk7XG4gICAgICB9XG4gICAgfVxuXG4gICAgb3B0cy5hZnRlclNjcm9sbC5jYWxsKG9wdHMubGluaywgb3B0cyk7XG4gIH07XG5cbiAgJC5zbW9vdGhTY3JvbGwgPSBmdW5jdGlvbihvcHRpb25zLCBweCkge1xuICAgIGlmIChvcHRpb25zID09PSAnb3B0aW9ucycgJiYgdHlwZW9mIHB4ID09PSAnb2JqZWN0Jykge1xuICAgICAgcmV0dXJuICQuZXh0ZW5kKG9wdGlvbk92ZXJyaWRlcywgcHgpO1xuICAgIH1cbiAgICB2YXIgb3B0cywgJHNjcm9sbGVyLCBzcGVlZCwgZGVsdGE7XG4gICAgdmFyIGV4cGxpY2l0T2Zmc2V0ID0gZ2V0RXhwbGljaXRPZmZzZXQob3B0aW9ucyk7XG4gICAgdmFyIHNjcm9sbFRhcmdldE9mZnNldCA9IHt9O1xuICAgIHZhciBzY3JvbGxlck9mZnNldCA9IDA7XG4gICAgdmFyIG9mZlBvcyA9ICdvZmZzZXQnO1xuICAgIHZhciBzY3JvbGxEaXIgPSAnc2Nyb2xsVG9wJztcbiAgICB2YXIgYW5pUHJvcHMgPSB7fTtcbiAgICB2YXIgYW5pT3B0cyA9IHt9O1xuXG4gICAgaWYgKGV4cGxpY2l0T2Zmc2V0LnB4KSB7XG4gICAgICBvcHRzID0gJC5leHRlbmQoe2xpbms6IG51bGx9LCAkLmZuLnNtb290aFNjcm9sbC5kZWZhdWx0cywgb3B0aW9uT3ZlcnJpZGVzKTtcbiAgICB9IGVsc2Uge1xuICAgICAgb3B0cyA9ICQuZXh0ZW5kKHtsaW5rOiBudWxsfSwgJC5mbi5zbW9vdGhTY3JvbGwuZGVmYXVsdHMsIG9wdGlvbnMgfHwge30sIG9wdGlvbk92ZXJyaWRlcyk7XG5cbiAgICAgIGlmIChvcHRzLnNjcm9sbEVsZW1lbnQpIHtcbiAgICAgICAgb2ZmUG9zID0gJ3Bvc2l0aW9uJztcblxuICAgICAgICBpZiAob3B0cy5zY3JvbGxFbGVtZW50LmNzcygncG9zaXRpb24nKSA9PT0gJ3N0YXRpYycpIHtcbiAgICAgICAgICBvcHRzLnNjcm9sbEVsZW1lbnQuY3NzKCdwb3NpdGlvbicsICdyZWxhdGl2ZScpO1xuICAgICAgICB9XG4gICAgICB9XG5cbiAgICAgIGlmIChweCkge1xuICAgICAgICBleHBsaWNpdE9mZnNldCA9IGdldEV4cGxpY2l0T2Zmc2V0KHB4KTtcbiAgICAgIH1cbiAgICB9XG5cbiAgICBzY3JvbGxEaXIgPSBvcHRzLmRpcmVjdGlvbiA9PT0gJ2xlZnQnID8gJ3Njcm9sbExlZnQnIDogc2Nyb2xsRGlyO1xuXG4gICAgaWYgKG9wdHMuc2Nyb2xsRWxlbWVudCkge1xuICAgICAgJHNjcm9sbGVyID0gb3B0cy5zY3JvbGxFbGVtZW50O1xuXG4gICAgICBpZiAoIWV4cGxpY2l0T2Zmc2V0LnB4ICYmICEoL14oPzpIVE1MfEJPRFkpJC8pLnRlc3QoJHNjcm9sbGVyWzBdLm5vZGVOYW1lKSkge1xuICAgICAgICBzY3JvbGxlck9mZnNldCA9ICRzY3JvbGxlcltzY3JvbGxEaXJdKCk7XG4gICAgICB9XG4gICAgfSBlbHNlIHtcbiAgICAgICRzY3JvbGxlciA9ICQoJ2h0bWwsIGJvZHknKS5maXJzdFNjcm9sbGFibGUob3B0cy5kaXJlY3Rpb24pO1xuICAgIH1cblxuICAgIC8vIGJlZm9yZVNjcm9sbCBjYWxsYmFjayBmdW5jdGlvbiBtdXN0IGZpcmUgYmVmb3JlIGNhbGN1bGF0aW5nIG9mZnNldFxuICAgIG9wdHMuYmVmb3JlU2Nyb2xsLmNhbGwoJHNjcm9sbGVyLCBvcHRzKTtcblxuICAgIHNjcm9sbFRhcmdldE9mZnNldCA9IGV4cGxpY2l0T2Zmc2V0LnB4ID8gZXhwbGljaXRPZmZzZXQgOiB7XG4gICAgICByZWxhdGl2ZTogJycsXG4gICAgICBweDogKCQob3B0cy5zY3JvbGxUYXJnZXQpW29mZlBvc10oKSAmJiAkKG9wdHMuc2Nyb2xsVGFyZ2V0KVtvZmZQb3NdKClbb3B0cy5kaXJlY3Rpb25dKSB8fCAwXG4gICAgfTtcblxuICAgIGFuaVByb3BzW3Njcm9sbERpcl0gPSBzY3JvbGxUYXJnZXRPZmZzZXQucmVsYXRpdmUgKyAoc2Nyb2xsVGFyZ2V0T2Zmc2V0LnB4ICsgc2Nyb2xsZXJPZmZzZXQgKyBvcHRzLm9mZnNldCk7XG5cbiAgICBzcGVlZCA9IG9wdHMuc3BlZWQ7XG5cbiAgICAvLyBhdXRvbWF0aWNhbGx5IGNhbGN1bGF0ZSB0aGUgc3BlZWQgb2YgdGhlIHNjcm9sbCBiYXNlZCBvbiBkaXN0YW5jZSAvIGNvZWZmaWNpZW50XG4gICAgaWYgKHNwZWVkID09PSAnYXV0bycpIHtcblxuICAgICAgLy8gJHNjcm9sbGVyW3Njcm9sbERpcl0oKSBpcyBwb3NpdGlvbiBiZWZvcmUgc2Nyb2xsLCBhbmlQcm9wc1tzY3JvbGxEaXJdIGlzIHBvc2l0aW9uIGFmdGVyXG4gICAgICAvLyBXaGVuIGRlbHRhIGlzIGdyZWF0ZXIsIHNwZWVkIHdpbGwgYmUgZ3JlYXRlci5cbiAgICAgIGRlbHRhID0gTWF0aC5hYnMoYW5pUHJvcHNbc2Nyb2xsRGlyXSAtICRzY3JvbGxlcltzY3JvbGxEaXJdKCkpO1xuXG4gICAgICAvLyBEaXZpZGUgdGhlIGRlbHRhIGJ5IHRoZSBjb2VmZmljaWVudFxuICAgICAgc3BlZWQgPSBkZWx0YSAvIG9wdHMuYXV0b0NvZWZmaWNpZW50O1xuICAgIH1cblxuICAgIGFuaU9wdHMgPSB7XG4gICAgICBkdXJhdGlvbjogc3BlZWQsXG4gICAgICBlYXNpbmc6IG9wdHMuZWFzaW5nLFxuICAgICAgY29tcGxldGU6IGZ1bmN0aW9uKCkge1xuICAgICAgICBvbkFmdGVyU2Nyb2xsKG9wdHMpO1xuICAgICAgfVxuICAgIH07XG5cbiAgICBpZiAob3B0cy5zdGVwKSB7XG4gICAgICBhbmlPcHRzLnN0ZXAgPSBvcHRzLnN0ZXA7XG4gICAgfVxuXG4gICAgaWYgKCRzY3JvbGxlci5sZW5ndGgpIHtcbiAgICAgICRzY3JvbGxlci5zdG9wKCkuYW5pbWF0ZShhbmlQcm9wcywgYW5pT3B0cyk7XG4gICAgfSBlbHNlIHtcbiAgICAgIG9uQWZ0ZXJTY3JvbGwob3B0cyk7XG4gICAgfVxuICB9O1xuXG4gICQuc21vb3RoU2Nyb2xsLnZlcnNpb24gPSB2ZXJzaW9uO1xuICAkLnNtb290aFNjcm9sbC5maWx0ZXJQYXRoID0gZnVuY3Rpb24oc3RyaW5nKSB7XG4gICAgc3RyaW5nID0gc3RyaW5nIHx8ICcnO1xuXG4gICAgcmV0dXJuIHN0cmluZ1xuICAgICAgLnJlcGxhY2UoL15cXC8vLCAnJylcbiAgICAgIC5yZXBsYWNlKC8oPzppbmRleHxkZWZhdWx0KS5bYS16QS1aXXszLDR9JC8sICcnKVxuICAgICAgLnJlcGxhY2UoL1xcLyQvLCAnJyk7XG4gIH07XG5cbiAgLy8gZGVmYXVsdCBvcHRpb25zXG4gICQuZm4uc21vb3RoU2Nyb2xsLmRlZmF1bHRzID0gZGVmYXVsdHM7XG5cbn0pKTtcblxuXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9ub2RlX21vZHVsZXMvanF1ZXJ5LXNtb290aC1zY3JvbGwvanF1ZXJ5LnNtb290aC1zY3JvbGwuanNcbi8vIG1vZHVsZSBpZCA9IC4vbm9kZV9tb2R1bGVzL2pxdWVyeS1zbW9vdGgtc2Nyb2xsL2pxdWVyeS5zbW9vdGgtc2Nyb2xsLmpzXG4vLyBtb2R1bGUgY2h1bmtzID0gMyA1IiwicmVxdWlyZSgnanF1ZXJ5LXNtb290aC1zY3JvbGwnKTtcclxuXHJcbmV4cG9ydHMuYWRkTmV3UGFydGljaXBhbnQgPSBmdW5jdGlvbihjb2xsZWN0aW9uSG9sZGVyLCBlbWFpbCwgbmFtZSkge1xyXG4gICAgYWRkTmV3UGFydGljaXBhbnQoY29sbGVjdGlvbkhvbGRlciwgZW1haWwsIG5hbWUpO1xyXG59O1xyXG5cclxuZnVuY3Rpb24gYWRkTmV3UGFydGljaXBhbnQoY29sbGVjdGlvbkhvbGRlciwgZW1haWwsIG5hbWUpIHtcclxuICAgIC8vIEdldCBwYXJ0aWNpcGFudCBwcm90b3R5cGUgYXMgZGVmaW5lZCBpbiBhdHRyaWJ1dGUgZGF0YS1wcm90b3R5cGVcclxuICAgIHZhciBwcm90b3R5cGUgPSBjb2xsZWN0aW9uSG9sZGVyLmF0dHIoJ2RhdGEtcHJvdG90eXBlJyk7XHJcbiAgICAvLyBBZGp1c3QgcGFydGljaXBhbnQgcHJvdG90eXBlIGZvciBjb3JyZWN0IG5hbWluZ1xyXG4gICAgdmFyIG51bWJlcl9vZl9wYXJ0aWNpcGFudHMgPSBjb2xsZWN0aW9uSG9sZGVyLmNoaWxkcmVuKCkubGVuZ3RoIC0gMTsgLy8gTm90ZSwgb3duZXIgaXMgbm90IGNvdW50ZWQgYXMgcGFydGljaXBhbnRcclxuICAgIHZhciBuZXdGb3JtSHRtbCA9IHByb3RvdHlwZS5yZXBsYWNlKC9fX25hbWVfXy9nLFxyXG4gICAgICAgIG51bWJlcl9vZl9wYXJ0aWNpcGFudHMpLnJlcGxhY2UoL19fcGFydGljaXBhbnRjb3VudF9fL2csXHJcbiAgICAgICAgbnVtYmVyX29mX3BhcnRpY2lwYW50cyArIDEpO1xyXG4gICAgLy8gQWRkIG5ldyBwYXJ0aWNpcGFudCB0byBwYXJ0eSB3aXRoIGFuaW1hdGlvblxyXG4gICAgdmFyIG5ld0Zvcm0gPSAkKG5ld0Zvcm1IdG1sKTtcclxuICAgIGNvbGxlY3Rpb25Ib2xkZXIuYXBwZW5kKG5ld0Zvcm0pO1xyXG5cclxuICAgIGlmICggKHR5cGVvZihlbWFpbCkhPT0ndW5kZWZpbmVkJykgJiYgKHR5cGVvZihuYW1lKSE9PSd1bmRlZmluZWQnKSApIHtcclxuICAgICAgICAvLyBlbWFpbCBhbmQgbmFtZSBwcm92aWRlZCwgZmlsbCBpbiB0aGUgYmxhbmtzXHJcbiAgICAgICAgJChuZXdGb3JtKS5maW5kKCcucGFydGljaXBhbnQtbWFpbCcpLmF0dHIoJ3ZhbHVlJywgZW1haWwpO1xyXG4gICAgICAgICQobmV3Rm9ybSkuZmluZCgnLnBhcnRpY2lwYW50LW5hbWUnKS5hdHRyKCd2YWx1ZScsIG5hbWUpO1xyXG4gICAgICAgIG5ld0Zvcm0uc2hvdygpO1xyXG4gICAgfSBlbHNlIHtcclxuICAgICAgICBuZXdGb3JtLnNob3coMzAwKTtcclxuICAgIH1cclxuXHJcbiAgICAvLyBIYW5kbGUgZGVsZXRlIGJ1dHRvbiBldmVudHNcclxuICAgIGJpbmREZWxldGVCdXR0b25FdmVudHMoKTtcclxuICAgIC8vIFJlbW92ZSBkaXNhYmxlZCBzdGF0ZSBvbiBkZWxldGUtYnV0dG9uc1xyXG4gICAgJCgnLnJlbW92ZS1wYXJ0aWNpcGFudCcpLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xyXG59XHJcbmZ1bmN0aW9uIGJpbmREZWxldGVCdXR0b25FdmVudHMoKSB7XHJcbiAgICAvLyBMb29wIG92ZXIgYWxsIGRlbGV0ZSBidXR0b25zXHJcbiAgICAkKCdidXR0b24ucmVtb3ZlLXBhcnRpY2lwYW50JykuZWFjaChmdW5jdGlvbiAoaSkge1xyXG4gICAgICAgIC8vIFJlbW92ZSBhbnkgcHJldmlvdXNseSBiaW5kZWQgZXZlbnRcclxuICAgICAgICAkKHRoaXMpLm9mZignY2xpY2snKTtcclxuICAgICAgICAvLyBCaW5kIGV2ZW50XHJcbiAgICAgICAgJCh0aGlzKS5jbGljayhmdW5jdGlvbiAoZSkge1xyXG4gICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAgICQoJ3RhYmxlIHRyLnBhcnRpY2lwYW50Lm5vdC1vd25lcjpndCgnICsgaSArICcpJykuZWFjaChmdW5jdGlvbiAoaikge1xyXG4gICAgICAgICAgICAgICAgLy8gTW92ZSB2YWx1ZXMgZnJvbSBuZXh0IHJvdyB0byBjdXJyZW50IHJvd1xyXG4gICAgICAgICAgICAgICAgdmFyIG5leHRfcm93X25hbWUgPSAkKCd0YWJsZSB0ci5wYXJ0aWNpcGFudC5ub3Qtb3duZXI6ZXEoJyArIChpICsgaiArIDEpICsgJykgaW5wdXQucGFydGljaXBhbnQtbmFtZScpLnZhbCgpO1xyXG4gICAgICAgICAgICAgICAgdmFyIG5leHRfcm93X21haWwgPSAkKCd0YWJsZSB0ci5wYXJ0aWNpcGFudC5ub3Qtb3duZXI6ZXEoJyArIChpICsgaiArIDEpICsgJykgaW5wdXQucGFydGljaXBhbnQtbWFpbCcpLnZhbCgpO1xyXG4gICAgICAgICAgICAgICAgJCgndGFibGUgdHIucGFydGljaXBhbnQubm90LW93bmVyOmVxKCcgKyAoaSArIGopICsgJykgaW5wdXQucGFydGljaXBhbnQtbmFtZScpLnZhbChuZXh0X3Jvd19uYW1lKTtcclxuICAgICAgICAgICAgICAgICQoJ3RhYmxlIHRyLnBhcnRpY2lwYW50Lm5vdC1vd25lcjplcSgnICsgKGkgKyBqKSArICcpIGlucHV0LnBhcnRpY2lwYW50LW1haWwnKS52YWwobmV4dF9yb3dfbWFpbCk7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAvLyBEZWxldGUgbGFzdCByb3dcclxuICAgICAgICAgICAgJCgndGFibGUgdHIucGFydGljaXBhbnQubm90LW93bmVyOmxhc3QnKS5yZW1vdmUoKTtcclxuICAgICAgICAgICAgLy8gUmVtb3ZlIGRlbGV0ZSBldmVudHMgd2hlbiBkZWxldGFibGUgcGFydGljaXBhbnRzIDwgM1xyXG4gICAgICAgICAgICBpZiAoJCgndGFibGUgdHIucGFydGljaXBhbnQubm90LW93bmVyJykubGVuZ3RoIDwgMykge1xyXG4gICAgICAgICAgICAgICAgJCgndGFibGUgdHIucGFydGljaXBhbnQubm90LW93bmVyIGJ1dHRvbi5yZW1vdmUtcGFydGljaXBhbnQnKS5hZGRDbGFzcygnZGlzYWJsZWQnKTtcclxuICAgICAgICAgICAgICAgICQoJ3RhYmxlIHRyLnBhcnRpY2lwYW50Lm5vdC1vd25lciBidXR0b24ucmVtb3ZlLXBhcnRpY2lwYW50Jykub2ZmKCdjbGljaycpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSk7XHJcbiAgICB9KTtcclxufVxyXG4vKiBWYXJpYWJsZXMgKi9cclxudmFyIGNvbGxlY3Rpb25Ib2xkZXIgPSAkKCd0YWJsZS5wYXJ0aWNpcGFudHMgdGJvZHknKTtcclxuLyogRG9jdW1lbnQgUmVhZHkgKi9cclxualF1ZXJ5KGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbiAoKSB7XHJcbiAgICAvL0FkZCBldmVudGxpc3RlbmVyIG9uIGFkZC1uZXctcGFydGljaXBhbnQgYnV0dG9uXHJcbiAgICAkKCcuYWRkLW5ldy1wYXJ0aWNpcGFudCcpLmNsaWNrKGZ1bmN0aW9uIChlKSB7XHJcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgIGFkZE5ld1BhcnRpY2lwYW50KGNvbGxlY3Rpb25Ib2xkZXIpO1xyXG4gICAgfSk7XHJcbiAgICAvLyBJZiBmb3JtIGhhcyBtb3JlIHRoZW4gMyBwYXJ0aWNpcGFudHMsIHByb3ZpZGUgZGVsZXRlIGZ1bmN0aW9uYWxpdHlcclxuICAgIGlmICgkKCd0YWJsZSB0ci5wYXJ0aWNpcGFudCcpLmxlbmd0aCA+IDMpIHtcclxuICAgICAgICBiaW5kRGVsZXRlQnV0dG9uRXZlbnRzKCk7XHJcbiAgICAgICAgJCgnLnJlbW92ZS1wYXJ0aWNpcGFudCcpLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xyXG4gICAgfVxyXG4gICAgLy8gQWRkIHNtb290aCBzY3JvbGxcclxuICAgICQoJ2EuYnRuLXN0YXJ0ZWQnKS5jbGljayhmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgJC5zbW9vdGhTY3JvbGwoe1xyXG4gICAgICAgICAgICBzY3JvbGxUYXJnZXQ6ICcjbXlzYW50YSdcclxuICAgICAgICB9KTtcclxuICAgICAgICByZXR1cm4gZmFsc2U7XHJcbiAgICB9KTtcclxufSk7XHJcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL3NyYy9JbnRyYWN0by9TZWNyZXRTYW50YUJ1bmRsZS9SZXNvdXJjZXMvcHVibGljL2pzL3BhcnR5LmNyZWF0ZS5qcyJdLCJzb3VyY2VSb290IjoiIn0=