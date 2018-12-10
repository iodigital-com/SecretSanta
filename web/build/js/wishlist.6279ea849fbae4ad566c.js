webpackJsonp([0],{

/***/ "./node_modules/jquery-ui/ui/data.js":
/*!*******************************************!*\
  !*** ./node_modules/jquery-ui/ui/data.js ***!
  \*******************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * jQuery UI :data 1.12.1
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 */

//>>label: :data Selector
//>>group: Core
//>>description: Selects elements which have data stored under the specified key.
//>>docs: http://api.jqueryui.com/data-selector/

( function( factory ) {
	if ( true ) {

		// AMD. Register as an anonymous module.
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [ __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), __webpack_require__(/*! ./version */ "./node_modules/jquery-ui/ui/version.js") ], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {

		// Browser globals
		factory( jQuery );
	}
} ( function( $ ) {
return $.extend( $.expr[ ":" ], {
	data: $.expr.createPseudo ?
		$.expr.createPseudo( function( dataName ) {
			return function( elem ) {
				return !!$.data( elem, dataName );
			};
		} ) :

		// Support: jQuery <1.8
		function( elem, i, match ) {
			return !!$.data( elem, match[ 3 ] );
		}
} );
} ) );


/***/ }),

/***/ "./node_modules/jquery-ui/ui/disable-selection.js":
/*!********************************************************!*\
  !*** ./node_modules/jquery-ui/ui/disable-selection.js ***!
  \********************************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * jQuery UI Disable Selection 1.12.1
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 */

//>>label: disableSelection
//>>group: Core
//>>description: Disable selection of text content within the set of matched elements.
//>>docs: http://api.jqueryui.com/disableSelection/

// This file is deprecated
( function( factory ) {
	if ( true ) {

		// AMD. Register as an anonymous module.
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [ __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), __webpack_require__(/*! ./version */ "./node_modules/jquery-ui/ui/version.js") ], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {

		// Browser globals
		factory( jQuery );
	}
} ( function( $ ) {

return $.fn.extend( {
	disableSelection: ( function() {
		var eventType = "onselectstart" in document.createElement( "div" ) ?
			"selectstart" :
			"mousedown";

		return function() {
			return this.on( eventType + ".ui-disableSelection", function( event ) {
				event.preventDefault();
			} );
		};
	} )(),

	enableSelection: function() {
		return this.off( ".ui-disableSelection" );
	}
} );

} ) );


/***/ }),

/***/ "./node_modules/jquery-ui/ui/ie.js":
/*!*****************************************!*\
  !*** ./node_modules/jquery-ui/ui/ie.js ***!
  \*****************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;( function( factory ) {
	if ( true ) {

		// AMD. Register as an anonymous module.
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [ __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), __webpack_require__(/*! ./version */ "./node_modules/jquery-ui/ui/version.js") ], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {

		// Browser globals
		factory( jQuery );
	}
} ( function( $ ) {

// This file is deprecated
return $.ui.ie = !!/msie [\w.]+/.exec( navigator.userAgent.toLowerCase() );
} ) );


/***/ }),

/***/ "./node_modules/jquery-ui/ui/scroll-parent.js":
/*!****************************************************!*\
  !*** ./node_modules/jquery-ui/ui/scroll-parent.js ***!
  \****************************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * jQuery UI Scroll Parent 1.12.1
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 */

//>>label: scrollParent
//>>group: Core
//>>description: Get the closest ancestor element that is scrollable.
//>>docs: http://api.jqueryui.com/scrollParent/

( function( factory ) {
	if ( true ) {

		// AMD. Register as an anonymous module.
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [ __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), __webpack_require__(/*! ./version */ "./node_modules/jquery-ui/ui/version.js") ], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {

		// Browser globals
		factory( jQuery );
	}
} ( function( $ ) {

return $.fn.scrollParent = function( includeHidden ) {
	var position = this.css( "position" ),
		excludeStaticParent = position === "absolute",
		overflowRegex = includeHidden ? /(auto|scroll|hidden)/ : /(auto|scroll)/,
		scrollParent = this.parents().filter( function() {
			var parent = $( this );
			if ( excludeStaticParent && parent.css( "position" ) === "static" ) {
				return false;
			}
			return overflowRegex.test( parent.css( "overflow" ) + parent.css( "overflow-y" ) +
				parent.css( "overflow-x" ) );
		} ).eq( 0 );

	return position === "fixed" || !scrollParent.length ?
		$( this[ 0 ].ownerDocument || document ) :
		scrollParent;
};

} ) );


/***/ }),

/***/ "./node_modules/jquery-ui/ui/version.js":
/*!**********************************************!*\
  !*** ./node_modules/jquery-ui/ui/version.js ***!
  \**********************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;( function( factory ) {
	if ( true ) {

		// AMD. Register as an anonymous module.
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [ __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js") ], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {

		// Browser globals
		factory( jQuery );
	}
} ( function( $ ) {

$.ui = $.ui || {};

return $.ui.version = "1.12.1";

} ) );


/***/ }),

/***/ "./node_modules/jquery-ui/ui/widget.js":
/*!*********************************************!*\
  !*** ./node_modules/jquery-ui/ui/widget.js ***!
  \*********************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * jQuery UI Widget 1.12.1
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 */

//>>label: Widget
//>>group: Core
//>>description: Provides a factory for creating stateful widgets with a common API.
//>>docs: http://api.jqueryui.com/jQuery.widget/
//>>demos: http://jqueryui.com/widget/

( function( factory ) {
	if ( true ) {

		// AMD. Register as an anonymous module.
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [ __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), __webpack_require__(/*! ./version */ "./node_modules/jquery-ui/ui/version.js") ], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {

		// Browser globals
		factory( jQuery );
	}
}( function( $ ) {

var widgetUuid = 0;
var widgetSlice = Array.prototype.slice;

$.cleanData = ( function( orig ) {
	return function( elems ) {
		var events, elem, i;
		for ( i = 0; ( elem = elems[ i ] ) != null; i++ ) {
			try {

				// Only trigger remove when necessary to save time
				events = $._data( elem, "events" );
				if ( events && events.remove ) {
					$( elem ).triggerHandler( "remove" );
				}

			// Http://bugs.jquery.com/ticket/8235
			} catch ( e ) {}
		}
		orig( elems );
	};
} )( $.cleanData );

$.widget = function( name, base, prototype ) {
	var existingConstructor, constructor, basePrototype;

	// ProxiedPrototype allows the provided prototype to remain unmodified
	// so that it can be used as a mixin for multiple widgets (#8876)
	var proxiedPrototype = {};

	var namespace = name.split( "." )[ 0 ];
	name = name.split( "." )[ 1 ];
	var fullName = namespace + "-" + name;

	if ( !prototype ) {
		prototype = base;
		base = $.Widget;
	}

	if ( $.isArray( prototype ) ) {
		prototype = $.extend.apply( null, [ {} ].concat( prototype ) );
	}

	// Create selector for plugin
	$.expr[ ":" ][ fullName.toLowerCase() ] = function( elem ) {
		return !!$.data( elem, fullName );
	};

	$[ namespace ] = $[ namespace ] || {};
	existingConstructor = $[ namespace ][ name ];
	constructor = $[ namespace ][ name ] = function( options, element ) {

		// Allow instantiation without "new" keyword
		if ( !this._createWidget ) {
			return new constructor( options, element );
		}

		// Allow instantiation without initializing for simple inheritance
		// must use "new" keyword (the code above always passes args)
		if ( arguments.length ) {
			this._createWidget( options, element );
		}
	};

	// Extend with the existing constructor to carry over any static properties
	$.extend( constructor, existingConstructor, {
		version: prototype.version,

		// Copy the object used to create the prototype in case we need to
		// redefine the widget later
		_proto: $.extend( {}, prototype ),

		// Track widgets that inherit from this widget in case this widget is
		// redefined after a widget inherits from it
		_childConstructors: []
	} );

	basePrototype = new base();

	// We need to make the options hash a property directly on the new instance
	// otherwise we'll modify the options hash on the prototype that we're
	// inheriting from
	basePrototype.options = $.widget.extend( {}, basePrototype.options );
	$.each( prototype, function( prop, value ) {
		if ( !$.isFunction( value ) ) {
			proxiedPrototype[ prop ] = value;
			return;
		}
		proxiedPrototype[ prop ] = ( function() {
			function _super() {
				return base.prototype[ prop ].apply( this, arguments );
			}

			function _superApply( args ) {
				return base.prototype[ prop ].apply( this, args );
			}

			return function() {
				var __super = this._super;
				var __superApply = this._superApply;
				var returnValue;

				this._super = _super;
				this._superApply = _superApply;

				returnValue = value.apply( this, arguments );

				this._super = __super;
				this._superApply = __superApply;

				return returnValue;
			};
		} )();
	} );
	constructor.prototype = $.widget.extend( basePrototype, {

		// TODO: remove support for widgetEventPrefix
		// always use the name + a colon as the prefix, e.g., draggable:start
		// don't prefix for widgets that aren't DOM-based
		widgetEventPrefix: existingConstructor ? ( basePrototype.widgetEventPrefix || name ) : name
	}, proxiedPrototype, {
		constructor: constructor,
		namespace: namespace,
		widgetName: name,
		widgetFullName: fullName
	} );

	// If this widget is being redefined then we need to find all widgets that
	// are inheriting from it and redefine all of them so that they inherit from
	// the new version of this widget. We're essentially trying to replace one
	// level in the prototype chain.
	if ( existingConstructor ) {
		$.each( existingConstructor._childConstructors, function( i, child ) {
			var childPrototype = child.prototype;

			// Redefine the child widget using the same prototype that was
			// originally used, but inherit from the new version of the base
			$.widget( childPrototype.namespace + "." + childPrototype.widgetName, constructor,
				child._proto );
		} );

		// Remove the list of existing child constructors from the old constructor
		// so the old child constructors can be garbage collected
		delete existingConstructor._childConstructors;
	} else {
		base._childConstructors.push( constructor );
	}

	$.widget.bridge( name, constructor );

	return constructor;
};

$.widget.extend = function( target ) {
	var input = widgetSlice.call( arguments, 1 );
	var inputIndex = 0;
	var inputLength = input.length;
	var key;
	var value;

	for ( ; inputIndex < inputLength; inputIndex++ ) {
		for ( key in input[ inputIndex ] ) {
			value = input[ inputIndex ][ key ];
			if ( input[ inputIndex ].hasOwnProperty( key ) && value !== undefined ) {

				// Clone objects
				if ( $.isPlainObject( value ) ) {
					target[ key ] = $.isPlainObject( target[ key ] ) ?
						$.widget.extend( {}, target[ key ], value ) :

						// Don't extend strings, arrays, etc. with objects
						$.widget.extend( {}, value );

				// Copy everything else by reference
				} else {
					target[ key ] = value;
				}
			}
		}
	}
	return target;
};

$.widget.bridge = function( name, object ) {
	var fullName = object.prototype.widgetFullName || name;
	$.fn[ name ] = function( options ) {
		var isMethodCall = typeof options === "string";
		var args = widgetSlice.call( arguments, 1 );
		var returnValue = this;

		if ( isMethodCall ) {

			// If this is an empty collection, we need to have the instance method
			// return undefined instead of the jQuery instance
			if ( !this.length && options === "instance" ) {
				returnValue = undefined;
			} else {
				this.each( function() {
					var methodValue;
					var instance = $.data( this, fullName );

					if ( options === "instance" ) {
						returnValue = instance;
						return false;
					}

					if ( !instance ) {
						return $.error( "cannot call methods on " + name +
							" prior to initialization; " +
							"attempted to call method '" + options + "'" );
					}

					if ( !$.isFunction( instance[ options ] ) || options.charAt( 0 ) === "_" ) {
						return $.error( "no such method '" + options + "' for " + name +
							" widget instance" );
					}

					methodValue = instance[ options ].apply( instance, args );

					if ( methodValue !== instance && methodValue !== undefined ) {
						returnValue = methodValue && methodValue.jquery ?
							returnValue.pushStack( methodValue.get() ) :
							methodValue;
						return false;
					}
				} );
			}
		} else {

			// Allow multiple hashes to be passed on init
			if ( args.length ) {
				options = $.widget.extend.apply( null, [ options ].concat( args ) );
			}

			this.each( function() {
				var instance = $.data( this, fullName );
				if ( instance ) {
					instance.option( options || {} );
					if ( instance._init ) {
						instance._init();
					}
				} else {
					$.data( this, fullName, new object( options, this ) );
				}
			} );
		}

		return returnValue;
	};
};

$.Widget = function( /* options, element */ ) {};
$.Widget._childConstructors = [];

$.Widget.prototype = {
	widgetName: "widget",
	widgetEventPrefix: "",
	defaultElement: "<div>",

	options: {
		classes: {},
		disabled: false,

		// Callbacks
		create: null
	},

	_createWidget: function( options, element ) {
		element = $( element || this.defaultElement || this )[ 0 ];
		this.element = $( element );
		this.uuid = widgetUuid++;
		this.eventNamespace = "." + this.widgetName + this.uuid;

		this.bindings = $();
		this.hoverable = $();
		this.focusable = $();
		this.classesElementLookup = {};

		if ( element !== this ) {
			$.data( element, this.widgetFullName, this );
			this._on( true, this.element, {
				remove: function( event ) {
					if ( event.target === element ) {
						this.destroy();
					}
				}
			} );
			this.document = $( element.style ?

				// Element within the document
				element.ownerDocument :

				// Element is window or document
				element.document || element );
			this.window = $( this.document[ 0 ].defaultView || this.document[ 0 ].parentWindow );
		}

		this.options = $.widget.extend( {},
			this.options,
			this._getCreateOptions(),
			options );

		this._create();

		if ( this.options.disabled ) {
			this._setOptionDisabled( this.options.disabled );
		}

		this._trigger( "create", null, this._getCreateEventData() );
		this._init();
	},

	_getCreateOptions: function() {
		return {};
	},

	_getCreateEventData: $.noop,

	_create: $.noop,

	_init: $.noop,

	destroy: function() {
		var that = this;

		this._destroy();
		$.each( this.classesElementLookup, function( key, value ) {
			that._removeClass( value, key );
		} );

		// We can probably remove the unbind calls in 2.0
		// all event bindings should go through this._on()
		this.element
			.off( this.eventNamespace )
			.removeData( this.widgetFullName );
		this.widget()
			.off( this.eventNamespace )
			.removeAttr( "aria-disabled" );

		// Clean up events and states
		this.bindings.off( this.eventNamespace );
	},

	_destroy: $.noop,

	widget: function() {
		return this.element;
	},

	option: function( key, value ) {
		var options = key;
		var parts;
		var curOption;
		var i;

		if ( arguments.length === 0 ) {

			// Don't return a reference to the internal hash
			return $.widget.extend( {}, this.options );
		}

		if ( typeof key === "string" ) {

			// Handle nested keys, e.g., "foo.bar" => { foo: { bar: ___ } }
			options = {};
			parts = key.split( "." );
			key = parts.shift();
			if ( parts.length ) {
				curOption = options[ key ] = $.widget.extend( {}, this.options[ key ] );
				for ( i = 0; i < parts.length - 1; i++ ) {
					curOption[ parts[ i ] ] = curOption[ parts[ i ] ] || {};
					curOption = curOption[ parts[ i ] ];
				}
				key = parts.pop();
				if ( arguments.length === 1 ) {
					return curOption[ key ] === undefined ? null : curOption[ key ];
				}
				curOption[ key ] = value;
			} else {
				if ( arguments.length === 1 ) {
					return this.options[ key ] === undefined ? null : this.options[ key ];
				}
				options[ key ] = value;
			}
		}

		this._setOptions( options );

		return this;
	},

	_setOptions: function( options ) {
		var key;

		for ( key in options ) {
			this._setOption( key, options[ key ] );
		}

		return this;
	},

	_setOption: function( key, value ) {
		if ( key === "classes" ) {
			this._setOptionClasses( value );
		}

		this.options[ key ] = value;

		if ( key === "disabled" ) {
			this._setOptionDisabled( value );
		}

		return this;
	},

	_setOptionClasses: function( value ) {
		var classKey, elements, currentElements;

		for ( classKey in value ) {
			currentElements = this.classesElementLookup[ classKey ];
			if ( value[ classKey ] === this.options.classes[ classKey ] ||
					!currentElements ||
					!currentElements.length ) {
				continue;
			}

			// We are doing this to create a new jQuery object because the _removeClass() call
			// on the next line is going to destroy the reference to the current elements being
			// tracked. We need to save a copy of this collection so that we can add the new classes
			// below.
			elements = $( currentElements.get() );
			this._removeClass( currentElements, classKey );

			// We don't use _addClass() here, because that uses this.options.classes
			// for generating the string of classes. We want to use the value passed in from
			// _setOption(), this is the new value of the classes option which was passed to
			// _setOption(). We pass this value directly to _classes().
			elements.addClass( this._classes( {
				element: elements,
				keys: classKey,
				classes: value,
				add: true
			} ) );
		}
	},

	_setOptionDisabled: function( value ) {
		this._toggleClass( this.widget(), this.widgetFullName + "-disabled", null, !!value );

		// If the widget is becoming disabled, then nothing is interactive
		if ( value ) {
			this._removeClass( this.hoverable, null, "ui-state-hover" );
			this._removeClass( this.focusable, null, "ui-state-focus" );
		}
	},

	enable: function() {
		return this._setOptions( { disabled: false } );
	},

	disable: function() {
		return this._setOptions( { disabled: true } );
	},

	_classes: function( options ) {
		var full = [];
		var that = this;

		options = $.extend( {
			element: this.element,
			classes: this.options.classes || {}
		}, options );

		function processClassString( classes, checkOption ) {
			var current, i;
			for ( i = 0; i < classes.length; i++ ) {
				current = that.classesElementLookup[ classes[ i ] ] || $();
				if ( options.add ) {
					current = $( $.unique( current.get().concat( options.element.get() ) ) );
				} else {
					current = $( current.not( options.element ).get() );
				}
				that.classesElementLookup[ classes[ i ] ] = current;
				full.push( classes[ i ] );
				if ( checkOption && options.classes[ classes[ i ] ] ) {
					full.push( options.classes[ classes[ i ] ] );
				}
			}
		}

		this._on( options.element, {
			"remove": "_untrackClassesElement"
		} );

		if ( options.keys ) {
			processClassString( options.keys.match( /\S+/g ) || [], true );
		}
		if ( options.extra ) {
			processClassString( options.extra.match( /\S+/g ) || [] );
		}

		return full.join( " " );
	},

	_untrackClassesElement: function( event ) {
		var that = this;
		$.each( that.classesElementLookup, function( key, value ) {
			if ( $.inArray( event.target, value ) !== -1 ) {
				that.classesElementLookup[ key ] = $( value.not( event.target ).get() );
			}
		} );
	},

	_removeClass: function( element, keys, extra ) {
		return this._toggleClass( element, keys, extra, false );
	},

	_addClass: function( element, keys, extra ) {
		return this._toggleClass( element, keys, extra, true );
	},

	_toggleClass: function( element, keys, extra, add ) {
		add = ( typeof add === "boolean" ) ? add : extra;
		var shift = ( typeof element === "string" || element === null ),
			options = {
				extra: shift ? keys : extra,
				keys: shift ? element : keys,
				element: shift ? this.element : element,
				add: add
			};
		options.element.toggleClass( this._classes( options ), add );
		return this;
	},

	_on: function( suppressDisabledCheck, element, handlers ) {
		var delegateElement;
		var instance = this;

		// No suppressDisabledCheck flag, shuffle arguments
		if ( typeof suppressDisabledCheck !== "boolean" ) {
			handlers = element;
			element = suppressDisabledCheck;
			suppressDisabledCheck = false;
		}

		// No element argument, shuffle and use this.element
		if ( !handlers ) {
			handlers = element;
			element = this.element;
			delegateElement = this.widget();
		} else {
			element = delegateElement = $( element );
			this.bindings = this.bindings.add( element );
		}

		$.each( handlers, function( event, handler ) {
			function handlerProxy() {

				// Allow widgets to customize the disabled handling
				// - disabled as an array instead of boolean
				// - disabled class as method for disabling individual parts
				if ( !suppressDisabledCheck &&
						( instance.options.disabled === true ||
						$( this ).hasClass( "ui-state-disabled" ) ) ) {
					return;
				}
				return ( typeof handler === "string" ? instance[ handler ] : handler )
					.apply( instance, arguments );
			}

			// Copy the guid so direct unbinding works
			if ( typeof handler !== "string" ) {
				handlerProxy.guid = handler.guid =
					handler.guid || handlerProxy.guid || $.guid++;
			}

			var match = event.match( /^([\w:-]*)\s*(.*)$/ );
			var eventName = match[ 1 ] + instance.eventNamespace;
			var selector = match[ 2 ];

			if ( selector ) {
				delegateElement.on( eventName, selector, handlerProxy );
			} else {
				element.on( eventName, handlerProxy );
			}
		} );
	},

	_off: function( element, eventName ) {
		eventName = ( eventName || "" ).split( " " ).join( this.eventNamespace + " " ) +
			this.eventNamespace;
		element.off( eventName ).off( eventName );

		// Clear the stack to avoid memory leaks (#10056)
		this.bindings = $( this.bindings.not( element ).get() );
		this.focusable = $( this.focusable.not( element ).get() );
		this.hoverable = $( this.hoverable.not( element ).get() );
	},

	_delay: function( handler, delay ) {
		function handlerProxy() {
			return ( typeof handler === "string" ? instance[ handler ] : handler )
				.apply( instance, arguments );
		}
		var instance = this;
		return setTimeout( handlerProxy, delay || 0 );
	},

	_hoverable: function( element ) {
		this.hoverable = this.hoverable.add( element );
		this._on( element, {
			mouseenter: function( event ) {
				this._addClass( $( event.currentTarget ), null, "ui-state-hover" );
			},
			mouseleave: function( event ) {
				this._removeClass( $( event.currentTarget ), null, "ui-state-hover" );
			}
		} );
	},

	_focusable: function( element ) {
		this.focusable = this.focusable.add( element );
		this._on( element, {
			focusin: function( event ) {
				this._addClass( $( event.currentTarget ), null, "ui-state-focus" );
			},
			focusout: function( event ) {
				this._removeClass( $( event.currentTarget ), null, "ui-state-focus" );
			}
		} );
	},

	_trigger: function( type, event, data ) {
		var prop, orig;
		var callback = this.options[ type ];

		data = data || {};
		event = $.Event( event );
		event.type = ( type === this.widgetEventPrefix ?
			type :
			this.widgetEventPrefix + type ).toLowerCase();

		// The original event may come from any element
		// so we need to reset the target on the new event
		event.target = this.element[ 0 ];

		// Copy original event properties over to the new event
		orig = event.originalEvent;
		if ( orig ) {
			for ( prop in orig ) {
				if ( !( prop in event ) ) {
					event[ prop ] = orig[ prop ];
				}
			}
		}

		this.element.trigger( event, data );
		return !( $.isFunction( callback ) &&
			callback.apply( this.element[ 0 ], [ event ].concat( data ) ) === false ||
			event.isDefaultPrevented() );
	}
};

$.each( { show: "fadeIn", hide: "fadeOut" }, function( method, defaultEffect ) {
	$.Widget.prototype[ "_" + method ] = function( element, options, callback ) {
		if ( typeof options === "string" ) {
			options = { effect: options };
		}

		var hasOptions;
		var effectName = !options ?
			method :
			options === true || typeof options === "number" ?
				defaultEffect :
				options.effect || defaultEffect;

		options = options || {};
		if ( typeof options === "number" ) {
			options = { duration: options };
		}

		hasOptions = !$.isEmptyObject( options );
		options.complete = callback;

		if ( options.delay ) {
			element.delay( options.delay );
		}

		if ( hasOptions && $.effects && $.effects.effect[ effectName ] ) {
			element[ method ]( options );
		} else if ( effectName !== method && element[ effectName ] ) {
			element[ effectName ]( options.duration, options.easing, callback );
		} else {
			element.queue( function( next ) {
				$( this )[ method ]();
				if ( callback ) {
					callback.call( element[ 0 ] );
				}
				next();
			} );
		}
	};
} );

return $.widget;

} ) );


/***/ }),

/***/ "./node_modules/jquery-ui/ui/widgets/mouse.js":
/*!****************************************************!*\
  !*** ./node_modules/jquery-ui/ui/widgets/mouse.js ***!
  \****************************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * jQuery UI Mouse 1.12.1
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 */

//>>label: Mouse
//>>group: Widgets
//>>description: Abstracts mouse-based interactions to assist in creating certain widgets.
//>>docs: http://api.jqueryui.com/mouse/

( function( factory ) {
	if ( true ) {

		// AMD. Register as an anonymous module.
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [
			__webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"),
			__webpack_require__(/*! ../ie */ "./node_modules/jquery-ui/ui/ie.js"),
			__webpack_require__(/*! ../version */ "./node_modules/jquery-ui/ui/version.js"),
			__webpack_require__(/*! ../widget */ "./node_modules/jquery-ui/ui/widget.js")
		], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {

		// Browser globals
		factory( jQuery );
	}
}( function( $ ) {

var mouseHandled = false;
$( document ).on( "mouseup", function() {
	mouseHandled = false;
} );

return $.widget( "ui.mouse", {
	version: "1.12.1",
	options: {
		cancel: "input, textarea, button, select, option",
		distance: 1,
		delay: 0
	},
	_mouseInit: function() {
		var that = this;

		this.element
			.on( "mousedown." + this.widgetName, function( event ) {
				return that._mouseDown( event );
			} )
			.on( "click." + this.widgetName, function( event ) {
				if ( true === $.data( event.target, that.widgetName + ".preventClickEvent" ) ) {
					$.removeData( event.target, that.widgetName + ".preventClickEvent" );
					event.stopImmediatePropagation();
					return false;
				}
			} );

		this.started = false;
	},

	// TODO: make sure destroying one instance of mouse doesn't mess with
	// other instances of mouse
	_mouseDestroy: function() {
		this.element.off( "." + this.widgetName );
		if ( this._mouseMoveDelegate ) {
			this.document
				.off( "mousemove." + this.widgetName, this._mouseMoveDelegate )
				.off( "mouseup." + this.widgetName, this._mouseUpDelegate );
		}
	},

	_mouseDown: function( event ) {

		// don't let more than one widget handle mouseStart
		if ( mouseHandled ) {
			return;
		}

		this._mouseMoved = false;

		// We may have missed mouseup (out of window)
		( this._mouseStarted && this._mouseUp( event ) );

		this._mouseDownEvent = event;

		var that = this,
			btnIsLeft = ( event.which === 1 ),

			// event.target.nodeName works around a bug in IE 8 with
			// disabled inputs (#7620)
			elIsCancel = ( typeof this.options.cancel === "string" && event.target.nodeName ?
				$( event.target ).closest( this.options.cancel ).length : false );
		if ( !btnIsLeft || elIsCancel || !this._mouseCapture( event ) ) {
			return true;
		}

		this.mouseDelayMet = !this.options.delay;
		if ( !this.mouseDelayMet ) {
			this._mouseDelayTimer = setTimeout( function() {
				that.mouseDelayMet = true;
			}, this.options.delay );
		}

		if ( this._mouseDistanceMet( event ) && this._mouseDelayMet( event ) ) {
			this._mouseStarted = ( this._mouseStart( event ) !== false );
			if ( !this._mouseStarted ) {
				event.preventDefault();
				return true;
			}
		}

		// Click event may never have fired (Gecko & Opera)
		if ( true === $.data( event.target, this.widgetName + ".preventClickEvent" ) ) {
			$.removeData( event.target, this.widgetName + ".preventClickEvent" );
		}

		// These delegates are required to keep context
		this._mouseMoveDelegate = function( event ) {
			return that._mouseMove( event );
		};
		this._mouseUpDelegate = function( event ) {
			return that._mouseUp( event );
		};

		this.document
			.on( "mousemove." + this.widgetName, this._mouseMoveDelegate )
			.on( "mouseup." + this.widgetName, this._mouseUpDelegate );

		event.preventDefault();

		mouseHandled = true;
		return true;
	},

	_mouseMove: function( event ) {

		// Only check for mouseups outside the document if you've moved inside the document
		// at least once. This prevents the firing of mouseup in the case of IE<9, which will
		// fire a mousemove event if content is placed under the cursor. See #7778
		// Support: IE <9
		if ( this._mouseMoved ) {

			// IE mouseup check - mouseup happened when mouse was out of window
			if ( $.ui.ie && ( !document.documentMode || document.documentMode < 9 ) &&
					!event.button ) {
				return this._mouseUp( event );

			// Iframe mouseup check - mouseup occurred in another document
			} else if ( !event.which ) {

				// Support: Safari <=8 - 9
				// Safari sets which to 0 if you press any of the following keys
				// during a drag (#14461)
				if ( event.originalEvent.altKey || event.originalEvent.ctrlKey ||
						event.originalEvent.metaKey || event.originalEvent.shiftKey ) {
					this.ignoreMissingWhich = true;
				} else if ( !this.ignoreMissingWhich ) {
					return this._mouseUp( event );
				}
			}
		}

		if ( event.which || event.button ) {
			this._mouseMoved = true;
		}

		if ( this._mouseStarted ) {
			this._mouseDrag( event );
			return event.preventDefault();
		}

		if ( this._mouseDistanceMet( event ) && this._mouseDelayMet( event ) ) {
			this._mouseStarted =
				( this._mouseStart( this._mouseDownEvent, event ) !== false );
			( this._mouseStarted ? this._mouseDrag( event ) : this._mouseUp( event ) );
		}

		return !this._mouseStarted;
	},

	_mouseUp: function( event ) {
		this.document
			.off( "mousemove." + this.widgetName, this._mouseMoveDelegate )
			.off( "mouseup." + this.widgetName, this._mouseUpDelegate );

		if ( this._mouseStarted ) {
			this._mouseStarted = false;

			if ( event.target === this._mouseDownEvent.target ) {
				$.data( event.target, this.widgetName + ".preventClickEvent", true );
			}

			this._mouseStop( event );
		}

		if ( this._mouseDelayTimer ) {
			clearTimeout( this._mouseDelayTimer );
			delete this._mouseDelayTimer;
		}

		this.ignoreMissingWhich = false;
		mouseHandled = false;
		event.preventDefault();
	},

	_mouseDistanceMet: function( event ) {
		return ( Math.max(
				Math.abs( this._mouseDownEvent.pageX - event.pageX ),
				Math.abs( this._mouseDownEvent.pageY - event.pageY )
			) >= this.options.distance
		);
	},

	_mouseDelayMet: function( /* event */ ) {
		return this.mouseDelayMet;
	},

	// These are placeholder methods, to be overriden by extending plugin
	_mouseStart: function( /* event */ ) {},
	_mouseDrag: function( /* event */ ) {},
	_mouseStop: function( /* event */ ) {},
	_mouseCapture: function( /* event */ ) { return true; }
} );

} ) );


/***/ }),

/***/ "./node_modules/jquery-ui/ui/widgets/sortable.js":
/*!*******************************************************!*\
  !*** ./node_modules/jquery-ui/ui/widgets/sortable.js ***!
  \*******************************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * jQuery UI Sortable 1.12.1
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 */

//>>label: Sortable
//>>group: Interactions
//>>description: Enables items in a list to be sorted using the mouse.
//>>docs: http://api.jqueryui.com/sortable/
//>>demos: http://jqueryui.com/sortable/
//>>css.structure: ../../themes/base/sortable.css

( function( factory ) {
	if ( true ) {

		// AMD. Register as an anonymous module.
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [
			__webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"),
			__webpack_require__(/*! ./mouse */ "./node_modules/jquery-ui/ui/widgets/mouse.js"),
			__webpack_require__(/*! ../data */ "./node_modules/jquery-ui/ui/data.js"),
			__webpack_require__(/*! ../ie */ "./node_modules/jquery-ui/ui/ie.js"),
			__webpack_require__(/*! ../scroll-parent */ "./node_modules/jquery-ui/ui/scroll-parent.js"),
			__webpack_require__(/*! ../version */ "./node_modules/jquery-ui/ui/version.js"),
			__webpack_require__(/*! ../widget */ "./node_modules/jquery-ui/ui/widget.js")
		], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {

		// Browser globals
		factory( jQuery );
	}
}( function( $ ) {

return $.widget( "ui.sortable", $.ui.mouse, {
	version: "1.12.1",
	widgetEventPrefix: "sort",
	ready: false,
	options: {
		appendTo: "parent",
		axis: false,
		connectWith: false,
		containment: false,
		cursor: "auto",
		cursorAt: false,
		dropOnEmpty: true,
		forcePlaceholderSize: false,
		forceHelperSize: false,
		grid: false,
		handle: false,
		helper: "original",
		items: "> *",
		opacity: false,
		placeholder: false,
		revert: false,
		scroll: true,
		scrollSensitivity: 20,
		scrollSpeed: 20,
		scope: "default",
		tolerance: "intersect",
		zIndex: 1000,

		// Callbacks
		activate: null,
		beforeStop: null,
		change: null,
		deactivate: null,
		out: null,
		over: null,
		receive: null,
		remove: null,
		sort: null,
		start: null,
		stop: null,
		update: null
	},

	_isOverAxis: function( x, reference, size ) {
		return ( x >= reference ) && ( x < ( reference + size ) );
	},

	_isFloating: function( item ) {
		return ( /left|right/ ).test( item.css( "float" ) ) ||
			( /inline|table-cell/ ).test( item.css( "display" ) );
	},

	_create: function() {
		this.containerCache = {};
		this._addClass( "ui-sortable" );

		//Get the items
		this.refresh();

		//Let's determine the parent's offset
		this.offset = this.element.offset();

		//Initialize mouse events for interaction
		this._mouseInit();

		this._setHandleClassName();

		//We're ready to go
		this.ready = true;

	},

	_setOption: function( key, value ) {
		this._super( key, value );

		if ( key === "handle" ) {
			this._setHandleClassName();
		}
	},

	_setHandleClassName: function() {
		var that = this;
		this._removeClass( this.element.find( ".ui-sortable-handle" ), "ui-sortable-handle" );
		$.each( this.items, function() {
			that._addClass(
				this.instance.options.handle ?
					this.item.find( this.instance.options.handle ) :
					this.item,
				"ui-sortable-handle"
			);
		} );
	},

	_destroy: function() {
		this._mouseDestroy();

		for ( var i = this.items.length - 1; i >= 0; i-- ) {
			this.items[ i ].item.removeData( this.widgetName + "-item" );
		}

		return this;
	},

	_mouseCapture: function( event, overrideHandle ) {
		var currentItem = null,
			validHandle = false,
			that = this;

		if ( this.reverting ) {
			return false;
		}

		if ( this.options.disabled || this.options.type === "static" ) {
			return false;
		}

		//We have to refresh the items data once first
		this._refreshItems( event );

		//Find out if the clicked node (or one of its parents) is a actual item in this.items
		$( event.target ).parents().each( function() {
			if ( $.data( this, that.widgetName + "-item" ) === that ) {
				currentItem = $( this );
				return false;
			}
		} );
		if ( $.data( event.target, that.widgetName + "-item" ) === that ) {
			currentItem = $( event.target );
		}

		if ( !currentItem ) {
			return false;
		}
		if ( this.options.handle && !overrideHandle ) {
			$( this.options.handle, currentItem ).find( "*" ).addBack().each( function() {
				if ( this === event.target ) {
					validHandle = true;
				}
			} );
			if ( !validHandle ) {
				return false;
			}
		}

		this.currentItem = currentItem;
		this._removeCurrentsFromItems();
		return true;

	},

	_mouseStart: function( event, overrideHandle, noActivation ) {

		var i, body,
			o = this.options;

		this.currentContainer = this;

		//We only need to call refreshPositions, because the refreshItems call has been moved to
		// mouseCapture
		this.refreshPositions();

		//Create and append the visible helper
		this.helper = this._createHelper( event );

		//Cache the helper size
		this._cacheHelperProportions();

		/*
		 * - Position generation -
		 * This block generates everything position related - it's the core of draggables.
		 */

		//Cache the margins of the original element
		this._cacheMargins();

		//Get the next scrolling parent
		this.scrollParent = this.helper.scrollParent();

		//The element's absolute position on the page minus margins
		this.offset = this.currentItem.offset();
		this.offset = {
			top: this.offset.top - this.margins.top,
			left: this.offset.left - this.margins.left
		};

		$.extend( this.offset, {
			click: { //Where the click happened, relative to the element
				left: event.pageX - this.offset.left,
				top: event.pageY - this.offset.top
			},
			parent: this._getParentOffset(),

			// This is a relative to absolute position minus the actual position calculation -
			// only used for relative positioned helper
			relative: this._getRelativeOffset()
		} );

		// Only after we got the offset, we can change the helper's position to absolute
		// TODO: Still need to figure out a way to make relative sorting possible
		this.helper.css( "position", "absolute" );
		this.cssPosition = this.helper.css( "position" );

		//Generate the original position
		this.originalPosition = this._generatePosition( event );
		this.originalPageX = event.pageX;
		this.originalPageY = event.pageY;

		//Adjust the mouse offset relative to the helper if "cursorAt" is supplied
		( o.cursorAt && this._adjustOffsetFromHelper( o.cursorAt ) );

		//Cache the former DOM position
		this.domPosition = {
			prev: this.currentItem.prev()[ 0 ],
			parent: this.currentItem.parent()[ 0 ]
		};

		// If the helper is not the original, hide the original so it's not playing any role during
		// the drag, won't cause anything bad this way
		if ( this.helper[ 0 ] !== this.currentItem[ 0 ] ) {
			this.currentItem.hide();
		}

		//Create the placeholder
		this._createPlaceholder();

		//Set a containment if given in the options
		if ( o.containment ) {
			this._setContainment();
		}

		if ( o.cursor && o.cursor !== "auto" ) { // cursor option
			body = this.document.find( "body" );

			// Support: IE
			this.storedCursor = body.css( "cursor" );
			body.css( "cursor", o.cursor );

			this.storedStylesheet =
				$( "<style>*{ cursor: " + o.cursor + " !important; }</style>" ).appendTo( body );
		}

		if ( o.opacity ) { // opacity option
			if ( this.helper.css( "opacity" ) ) {
				this._storedOpacity = this.helper.css( "opacity" );
			}
			this.helper.css( "opacity", o.opacity );
		}

		if ( o.zIndex ) { // zIndex option
			if ( this.helper.css( "zIndex" ) ) {
				this._storedZIndex = this.helper.css( "zIndex" );
			}
			this.helper.css( "zIndex", o.zIndex );
		}

		//Prepare scrolling
		if ( this.scrollParent[ 0 ] !== this.document[ 0 ] &&
				this.scrollParent[ 0 ].tagName !== "HTML" ) {
			this.overflowOffset = this.scrollParent.offset();
		}

		//Call callbacks
		this._trigger( "start", event, this._uiHash() );

		//Recache the helper size
		if ( !this._preserveHelperProportions ) {
			this._cacheHelperProportions();
		}

		//Post "activate" events to possible containers
		if ( !noActivation ) {
			for ( i = this.containers.length - 1; i >= 0; i-- ) {
				this.containers[ i ]._trigger( "activate", event, this._uiHash( this ) );
			}
		}

		//Prepare possible droppables
		if ( $.ui.ddmanager ) {
			$.ui.ddmanager.current = this;
		}

		if ( $.ui.ddmanager && !o.dropBehaviour ) {
			$.ui.ddmanager.prepareOffsets( this, event );
		}

		this.dragging = true;

		this._addClass( this.helper, "ui-sortable-helper" );

		// Execute the drag once - this causes the helper not to be visiblebefore getting its
		// correct position
		this._mouseDrag( event );
		return true;

	},

	_mouseDrag: function( event ) {
		var i, item, itemElement, intersection,
			o = this.options,
			scrolled = false;

		//Compute the helpers position
		this.position = this._generatePosition( event );
		this.positionAbs = this._convertPositionTo( "absolute" );

		if ( !this.lastPositionAbs ) {
			this.lastPositionAbs = this.positionAbs;
		}

		//Do scrolling
		if ( this.options.scroll ) {
			if ( this.scrollParent[ 0 ] !== this.document[ 0 ] &&
					this.scrollParent[ 0 ].tagName !== "HTML" ) {

				if ( ( this.overflowOffset.top + this.scrollParent[ 0 ].offsetHeight ) -
						event.pageY < o.scrollSensitivity ) {
					this.scrollParent[ 0 ].scrollTop =
						scrolled = this.scrollParent[ 0 ].scrollTop + o.scrollSpeed;
				} else if ( event.pageY - this.overflowOffset.top < o.scrollSensitivity ) {
					this.scrollParent[ 0 ].scrollTop =
						scrolled = this.scrollParent[ 0 ].scrollTop - o.scrollSpeed;
				}

				if ( ( this.overflowOffset.left + this.scrollParent[ 0 ].offsetWidth ) -
						event.pageX < o.scrollSensitivity ) {
					this.scrollParent[ 0 ].scrollLeft = scrolled =
						this.scrollParent[ 0 ].scrollLeft + o.scrollSpeed;
				} else if ( event.pageX - this.overflowOffset.left < o.scrollSensitivity ) {
					this.scrollParent[ 0 ].scrollLeft = scrolled =
						this.scrollParent[ 0 ].scrollLeft - o.scrollSpeed;
				}

			} else {

				if ( event.pageY - this.document.scrollTop() < o.scrollSensitivity ) {
					scrolled = this.document.scrollTop( this.document.scrollTop() - o.scrollSpeed );
				} else if ( this.window.height() - ( event.pageY - this.document.scrollTop() ) <
						o.scrollSensitivity ) {
					scrolled = this.document.scrollTop( this.document.scrollTop() + o.scrollSpeed );
				}

				if ( event.pageX - this.document.scrollLeft() < o.scrollSensitivity ) {
					scrolled = this.document.scrollLeft(
						this.document.scrollLeft() - o.scrollSpeed
					);
				} else if ( this.window.width() - ( event.pageX - this.document.scrollLeft() ) <
						o.scrollSensitivity ) {
					scrolled = this.document.scrollLeft(
						this.document.scrollLeft() + o.scrollSpeed
					);
				}

			}

			if ( scrolled !== false && $.ui.ddmanager && !o.dropBehaviour ) {
				$.ui.ddmanager.prepareOffsets( this, event );
			}
		}

		//Regenerate the absolute position used for position checks
		this.positionAbs = this._convertPositionTo( "absolute" );

		//Set the helper position
		if ( !this.options.axis || this.options.axis !== "y" ) {
			this.helper[ 0 ].style.left = this.position.left + "px";
		}
		if ( !this.options.axis || this.options.axis !== "x" ) {
			this.helper[ 0 ].style.top = this.position.top + "px";
		}

		//Rearrange
		for ( i = this.items.length - 1; i >= 0; i-- ) {

			//Cache variables and intersection, continue if no intersection
			item = this.items[ i ];
			itemElement = item.item[ 0 ];
			intersection = this._intersectsWithPointer( item );
			if ( !intersection ) {
				continue;
			}

			// Only put the placeholder inside the current Container, skip all
			// items from other containers. This works because when moving
			// an item from one container to another the
			// currentContainer is switched before the placeholder is moved.
			//
			// Without this, moving items in "sub-sortables" can cause
			// the placeholder to jitter between the outer and inner container.
			if ( item.instance !== this.currentContainer ) {
				continue;
			}

			// Cannot intersect with itself
			// no useless actions that have been done before
			// no action if the item moved is the parent of the item checked
			if ( itemElement !== this.currentItem[ 0 ] &&
				this.placeholder[ intersection === 1 ? "next" : "prev" ]()[ 0 ] !== itemElement &&
				!$.contains( this.placeholder[ 0 ], itemElement ) &&
				( this.options.type === "semi-dynamic" ?
					!$.contains( this.element[ 0 ], itemElement ) :
					true
				)
			) {

				this.direction = intersection === 1 ? "down" : "up";

				if ( this.options.tolerance === "pointer" || this._intersectsWithSides( item ) ) {
					this._rearrange( event, item );
				} else {
					break;
				}

				this._trigger( "change", event, this._uiHash() );
				break;
			}
		}

		//Post events to containers
		this._contactContainers( event );

		//Interconnect with droppables
		if ( $.ui.ddmanager ) {
			$.ui.ddmanager.drag( this, event );
		}

		//Call callbacks
		this._trigger( "sort", event, this._uiHash() );

		this.lastPositionAbs = this.positionAbs;
		return false;

	},

	_mouseStop: function( event, noPropagation ) {

		if ( !event ) {
			return;
		}

		//If we are using droppables, inform the manager about the drop
		if ( $.ui.ddmanager && !this.options.dropBehaviour ) {
			$.ui.ddmanager.drop( this, event );
		}

		if ( this.options.revert ) {
			var that = this,
				cur = this.placeholder.offset(),
				axis = this.options.axis,
				animation = {};

			if ( !axis || axis === "x" ) {
				animation.left = cur.left - this.offset.parent.left - this.margins.left +
					( this.offsetParent[ 0 ] === this.document[ 0 ].body ?
						0 :
						this.offsetParent[ 0 ].scrollLeft
					);
			}
			if ( !axis || axis === "y" ) {
				animation.top = cur.top - this.offset.parent.top - this.margins.top +
					( this.offsetParent[ 0 ] === this.document[ 0 ].body ?
						0 :
						this.offsetParent[ 0 ].scrollTop
					);
			}
			this.reverting = true;
			$( this.helper ).animate(
				animation,
				parseInt( this.options.revert, 10 ) || 500,
				function() {
					that._clear( event );
				}
			);
		} else {
			this._clear( event, noPropagation );
		}

		return false;

	},

	cancel: function() {

		if ( this.dragging ) {

			this._mouseUp( new $.Event( "mouseup", { target: null } ) );

			if ( this.options.helper === "original" ) {
				this.currentItem.css( this._storedCSS );
				this._removeClass( this.currentItem, "ui-sortable-helper" );
			} else {
				this.currentItem.show();
			}

			//Post deactivating events to containers
			for ( var i = this.containers.length - 1; i >= 0; i-- ) {
				this.containers[ i ]._trigger( "deactivate", null, this._uiHash( this ) );
				if ( this.containers[ i ].containerCache.over ) {
					this.containers[ i ]._trigger( "out", null, this._uiHash( this ) );
					this.containers[ i ].containerCache.over = 0;
				}
			}

		}

		if ( this.placeholder ) {

			//$(this.placeholder[0]).remove(); would have been the jQuery way - unfortunately,
			// it unbinds ALL events from the original node!
			if ( this.placeholder[ 0 ].parentNode ) {
				this.placeholder[ 0 ].parentNode.removeChild( this.placeholder[ 0 ] );
			}
			if ( this.options.helper !== "original" && this.helper &&
					this.helper[ 0 ].parentNode ) {
				this.helper.remove();
			}

			$.extend( this, {
				helper: null,
				dragging: false,
				reverting: false,
				_noFinalSort: null
			} );

			if ( this.domPosition.prev ) {
				$( this.domPosition.prev ).after( this.currentItem );
			} else {
				$( this.domPosition.parent ).prepend( this.currentItem );
			}
		}

		return this;

	},

	serialize: function( o ) {

		var items = this._getItemsAsjQuery( o && o.connected ),
			str = [];
		o = o || {};

		$( items ).each( function() {
			var res = ( $( o.item || this ).attr( o.attribute || "id" ) || "" )
				.match( o.expression || ( /(.+)[\-=_](.+)/ ) );
			if ( res ) {
				str.push(
					( o.key || res[ 1 ] + "[]" ) +
					"=" + ( o.key && o.expression ? res[ 1 ] : res[ 2 ] ) );
			}
		} );

		if ( !str.length && o.key ) {
			str.push( o.key + "=" );
		}

		return str.join( "&" );

	},

	toArray: function( o ) {

		var items = this._getItemsAsjQuery( o && o.connected ),
			ret = [];

		o = o || {};

		items.each( function() {
			ret.push( $( o.item || this ).attr( o.attribute || "id" ) || "" );
		} );
		return ret;

	},

	/* Be careful with the following core functions */
	_intersectsWith: function( item ) {

		var x1 = this.positionAbs.left,
			x2 = x1 + this.helperProportions.width,
			y1 = this.positionAbs.top,
			y2 = y1 + this.helperProportions.height,
			l = item.left,
			r = l + item.width,
			t = item.top,
			b = t + item.height,
			dyClick = this.offset.click.top,
			dxClick = this.offset.click.left,
			isOverElementHeight = ( this.options.axis === "x" ) || ( ( y1 + dyClick ) > t &&
				( y1 + dyClick ) < b ),
			isOverElementWidth = ( this.options.axis === "y" ) || ( ( x1 + dxClick ) > l &&
				( x1 + dxClick ) < r ),
			isOverElement = isOverElementHeight && isOverElementWidth;

		if ( this.options.tolerance === "pointer" ||
			this.options.forcePointerForContainers ||
			( this.options.tolerance !== "pointer" &&
				this.helperProportions[ this.floating ? "width" : "height" ] >
				item[ this.floating ? "width" : "height" ] )
		) {
			return isOverElement;
		} else {

			return ( l < x1 + ( this.helperProportions.width / 2 ) && // Right Half
				x2 - ( this.helperProportions.width / 2 ) < r && // Left Half
				t < y1 + ( this.helperProportions.height / 2 ) && // Bottom Half
				y2 - ( this.helperProportions.height / 2 ) < b ); // Top Half

		}
	},

	_intersectsWithPointer: function( item ) {
		var verticalDirection, horizontalDirection,
			isOverElementHeight = ( this.options.axis === "x" ) ||
				this._isOverAxis(
					this.positionAbs.top + this.offset.click.top, item.top, item.height ),
			isOverElementWidth = ( this.options.axis === "y" ) ||
				this._isOverAxis(
					this.positionAbs.left + this.offset.click.left, item.left, item.width ),
			isOverElement = isOverElementHeight && isOverElementWidth;

		if ( !isOverElement ) {
			return false;
		}

		verticalDirection = this._getDragVerticalDirection();
		horizontalDirection = this._getDragHorizontalDirection();

		return this.floating ?
			( ( horizontalDirection === "right" || verticalDirection === "down" ) ? 2 : 1 )
			: ( verticalDirection && ( verticalDirection === "down" ? 2 : 1 ) );

	},

	_intersectsWithSides: function( item ) {

		var isOverBottomHalf = this._isOverAxis( this.positionAbs.top +
				this.offset.click.top, item.top + ( item.height / 2 ), item.height ),
			isOverRightHalf = this._isOverAxis( this.positionAbs.left +
				this.offset.click.left, item.left + ( item.width / 2 ), item.width ),
			verticalDirection = this._getDragVerticalDirection(),
			horizontalDirection = this._getDragHorizontalDirection();

		if ( this.floating && horizontalDirection ) {
			return ( ( horizontalDirection === "right" && isOverRightHalf ) ||
				( horizontalDirection === "left" && !isOverRightHalf ) );
		} else {
			return verticalDirection && ( ( verticalDirection === "down" && isOverBottomHalf ) ||
				( verticalDirection === "up" && !isOverBottomHalf ) );
		}

	},

	_getDragVerticalDirection: function() {
		var delta = this.positionAbs.top - this.lastPositionAbs.top;
		return delta !== 0 && ( delta > 0 ? "down" : "up" );
	},

	_getDragHorizontalDirection: function() {
		var delta = this.positionAbs.left - this.lastPositionAbs.left;
		return delta !== 0 && ( delta > 0 ? "right" : "left" );
	},

	refresh: function( event ) {
		this._refreshItems( event );
		this._setHandleClassName();
		this.refreshPositions();
		return this;
	},

	_connectWith: function() {
		var options = this.options;
		return options.connectWith.constructor === String ?
			[ options.connectWith ] :
			options.connectWith;
	},

	_getItemsAsjQuery: function( connected ) {

		var i, j, cur, inst,
			items = [],
			queries = [],
			connectWith = this._connectWith();

		if ( connectWith && connected ) {
			for ( i = connectWith.length - 1; i >= 0; i-- ) {
				cur = $( connectWith[ i ], this.document[ 0 ] );
				for ( j = cur.length - 1; j >= 0; j-- ) {
					inst = $.data( cur[ j ], this.widgetFullName );
					if ( inst && inst !== this && !inst.options.disabled ) {
						queries.push( [ $.isFunction( inst.options.items ) ?
							inst.options.items.call( inst.element ) :
							$( inst.options.items, inst.element )
								.not( ".ui-sortable-helper" )
								.not( ".ui-sortable-placeholder" ), inst ] );
					}
				}
			}
		}

		queries.push( [ $.isFunction( this.options.items ) ?
			this.options.items
				.call( this.element, null, { options: this.options, item: this.currentItem } ) :
			$( this.options.items, this.element )
				.not( ".ui-sortable-helper" )
				.not( ".ui-sortable-placeholder" ), this ] );

		function addItems() {
			items.push( this );
		}
		for ( i = queries.length - 1; i >= 0; i-- ) {
			queries[ i ][ 0 ].each( addItems );
		}

		return $( items );

	},

	_removeCurrentsFromItems: function() {

		var list = this.currentItem.find( ":data(" + this.widgetName + "-item)" );

		this.items = $.grep( this.items, function( item ) {
			for ( var j = 0; j < list.length; j++ ) {
				if ( list[ j ] === item.item[ 0 ] ) {
					return false;
				}
			}
			return true;
		} );

	},

	_refreshItems: function( event ) {

		this.items = [];
		this.containers = [ this ];

		var i, j, cur, inst, targetData, _queries, item, queriesLength,
			items = this.items,
			queries = [ [ $.isFunction( this.options.items ) ?
				this.options.items.call( this.element[ 0 ], event, { item: this.currentItem } ) :
				$( this.options.items, this.element ), this ] ],
			connectWith = this._connectWith();

		//Shouldn't be run the first time through due to massive slow-down
		if ( connectWith && this.ready ) {
			for ( i = connectWith.length - 1; i >= 0; i-- ) {
				cur = $( connectWith[ i ], this.document[ 0 ] );
				for ( j = cur.length - 1; j >= 0; j-- ) {
					inst = $.data( cur[ j ], this.widgetFullName );
					if ( inst && inst !== this && !inst.options.disabled ) {
						queries.push( [ $.isFunction( inst.options.items ) ?
							inst.options.items
								.call( inst.element[ 0 ], event, { item: this.currentItem } ) :
							$( inst.options.items, inst.element ), inst ] );
						this.containers.push( inst );
					}
				}
			}
		}

		for ( i = queries.length - 1; i >= 0; i-- ) {
			targetData = queries[ i ][ 1 ];
			_queries = queries[ i ][ 0 ];

			for ( j = 0, queriesLength = _queries.length; j < queriesLength; j++ ) {
				item = $( _queries[ j ] );

				// Data for target checking (mouse manager)
				item.data( this.widgetName + "-item", targetData );

				items.push( {
					item: item,
					instance: targetData,
					width: 0, height: 0,
					left: 0, top: 0
				} );
			}
		}

	},

	refreshPositions: function( fast ) {

		// Determine whether items are being displayed horizontally
		this.floating = this.items.length ?
			this.options.axis === "x" || this._isFloating( this.items[ 0 ].item ) :
			false;

		//This has to be redone because due to the item being moved out/into the offsetParent,
		// the offsetParent's position will change
		if ( this.offsetParent && this.helper ) {
			this.offset.parent = this._getParentOffset();
		}

		var i, item, t, p;

		for ( i = this.items.length - 1; i >= 0; i-- ) {
			item = this.items[ i ];

			//We ignore calculating positions of all connected containers when we're not over them
			if ( item.instance !== this.currentContainer && this.currentContainer &&
					item.item[ 0 ] !== this.currentItem[ 0 ] ) {
				continue;
			}

			t = this.options.toleranceElement ?
				$( this.options.toleranceElement, item.item ) :
				item.item;

			if ( !fast ) {
				item.width = t.outerWidth();
				item.height = t.outerHeight();
			}

			p = t.offset();
			item.left = p.left;
			item.top = p.top;
		}

		if ( this.options.custom && this.options.custom.refreshContainers ) {
			this.options.custom.refreshContainers.call( this );
		} else {
			for ( i = this.containers.length - 1; i >= 0; i-- ) {
				p = this.containers[ i ].element.offset();
				this.containers[ i ].containerCache.left = p.left;
				this.containers[ i ].containerCache.top = p.top;
				this.containers[ i ].containerCache.width =
					this.containers[ i ].element.outerWidth();
				this.containers[ i ].containerCache.height =
					this.containers[ i ].element.outerHeight();
			}
		}

		return this;
	},

	_createPlaceholder: function( that ) {
		that = that || this;
		var className,
			o = that.options;

		if ( !o.placeholder || o.placeholder.constructor === String ) {
			className = o.placeholder;
			o.placeholder = {
				element: function() {

					var nodeName = that.currentItem[ 0 ].nodeName.toLowerCase(),
						element = $( "<" + nodeName + ">", that.document[ 0 ] );

						that._addClass( element, "ui-sortable-placeholder",
								className || that.currentItem[ 0 ].className )
							._removeClass( element, "ui-sortable-helper" );

					if ( nodeName === "tbody" ) {
						that._createTrPlaceholder(
							that.currentItem.find( "tr" ).eq( 0 ),
							$( "<tr>", that.document[ 0 ] ).appendTo( element )
						);
					} else if ( nodeName === "tr" ) {
						that._createTrPlaceholder( that.currentItem, element );
					} else if ( nodeName === "img" ) {
						element.attr( "src", that.currentItem.attr( "src" ) );
					}

					if ( !className ) {
						element.css( "visibility", "hidden" );
					}

					return element;
				},
				update: function( container, p ) {

					// 1. If a className is set as 'placeholder option, we don't force sizes -
					// the class is responsible for that
					// 2. The option 'forcePlaceholderSize can be enabled to force it even if a
					// class name is specified
					if ( className && !o.forcePlaceholderSize ) {
						return;
					}

					//If the element doesn't have a actual height by itself (without styles coming
					// from a stylesheet), it receives the inline height from the dragged item
					if ( !p.height() ) {
						p.height(
							that.currentItem.innerHeight() -
							parseInt( that.currentItem.css( "paddingTop" ) || 0, 10 ) -
							parseInt( that.currentItem.css( "paddingBottom" ) || 0, 10 ) );
					}
					if ( !p.width() ) {
						p.width(
							that.currentItem.innerWidth() -
							parseInt( that.currentItem.css( "paddingLeft" ) || 0, 10 ) -
							parseInt( that.currentItem.css( "paddingRight" ) || 0, 10 ) );
					}
				}
			};
		}

		//Create the placeholder
		that.placeholder = $( o.placeholder.element.call( that.element, that.currentItem ) );

		//Append it after the actual current item
		that.currentItem.after( that.placeholder );

		//Update the size of the placeholder (TODO: Logic to fuzzy, see line 316/317)
		o.placeholder.update( that, that.placeholder );

	},

	_createTrPlaceholder: function( sourceTr, targetTr ) {
		var that = this;

		sourceTr.children().each( function() {
			$( "<td>&#160;</td>", that.document[ 0 ] )
				.attr( "colspan", $( this ).attr( "colspan" ) || 1 )
				.appendTo( targetTr );
		} );
	},

	_contactContainers: function( event ) {
		var i, j, dist, itemWithLeastDistance, posProperty, sizeProperty, cur, nearBottom,
			floating, axis,
			innermostContainer = null,
			innermostIndex = null;

		// Get innermost container that intersects with item
		for ( i = this.containers.length - 1; i >= 0; i-- ) {

			// Never consider a container that's located within the item itself
			if ( $.contains( this.currentItem[ 0 ], this.containers[ i ].element[ 0 ] ) ) {
				continue;
			}

			if ( this._intersectsWith( this.containers[ i ].containerCache ) ) {

				// If we've already found a container and it's more "inner" than this, then continue
				if ( innermostContainer &&
						$.contains(
							this.containers[ i ].element[ 0 ],
							innermostContainer.element[ 0 ] ) ) {
					continue;
				}

				innermostContainer = this.containers[ i ];
				innermostIndex = i;

			} else {

				// container doesn't intersect. trigger "out" event if necessary
				if ( this.containers[ i ].containerCache.over ) {
					this.containers[ i ]._trigger( "out", event, this._uiHash( this ) );
					this.containers[ i ].containerCache.over = 0;
				}
			}

		}

		// If no intersecting containers found, return
		if ( !innermostContainer ) {
			return;
		}

		// Move the item into the container if it's not there already
		if ( this.containers.length === 1 ) {
			if ( !this.containers[ innermostIndex ].containerCache.over ) {
				this.containers[ innermostIndex ]._trigger( "over", event, this._uiHash( this ) );
				this.containers[ innermostIndex ].containerCache.over = 1;
			}
		} else {

			// When entering a new container, we will find the item with the least distance and
			// append our item near it
			dist = 10000;
			itemWithLeastDistance = null;
			floating = innermostContainer.floating || this._isFloating( this.currentItem );
			posProperty = floating ? "left" : "top";
			sizeProperty = floating ? "width" : "height";
			axis = floating ? "pageX" : "pageY";

			for ( j = this.items.length - 1; j >= 0; j-- ) {
				if ( !$.contains(
						this.containers[ innermostIndex ].element[ 0 ], this.items[ j ].item[ 0 ] )
				) {
					continue;
				}
				if ( this.items[ j ].item[ 0 ] === this.currentItem[ 0 ] ) {
					continue;
				}

				cur = this.items[ j ].item.offset()[ posProperty ];
				nearBottom = false;
				if ( event[ axis ] - cur > this.items[ j ][ sizeProperty ] / 2 ) {
					nearBottom = true;
				}

				if ( Math.abs( event[ axis ] - cur ) < dist ) {
					dist = Math.abs( event[ axis ] - cur );
					itemWithLeastDistance = this.items[ j ];
					this.direction = nearBottom ? "up" : "down";
				}
			}

			//Check if dropOnEmpty is enabled
			if ( !itemWithLeastDistance && !this.options.dropOnEmpty ) {
				return;
			}

			if ( this.currentContainer === this.containers[ innermostIndex ] ) {
				if ( !this.currentContainer.containerCache.over ) {
					this.containers[ innermostIndex ]._trigger( "over", event, this._uiHash() );
					this.currentContainer.containerCache.over = 1;
				}
				return;
			}

			itemWithLeastDistance ?
				this._rearrange( event, itemWithLeastDistance, null, true ) :
				this._rearrange( event, null, this.containers[ innermostIndex ].element, true );
			this._trigger( "change", event, this._uiHash() );
			this.containers[ innermostIndex ]._trigger( "change", event, this._uiHash( this ) );
			this.currentContainer = this.containers[ innermostIndex ];

			//Update the placeholder
			this.options.placeholder.update( this.currentContainer, this.placeholder );

			this.containers[ innermostIndex ]._trigger( "over", event, this._uiHash( this ) );
			this.containers[ innermostIndex ].containerCache.over = 1;
		}

	},

	_createHelper: function( event ) {

		var o = this.options,
			helper = $.isFunction( o.helper ) ?
				$( o.helper.apply( this.element[ 0 ], [ event, this.currentItem ] ) ) :
				( o.helper === "clone" ? this.currentItem.clone() : this.currentItem );

		//Add the helper to the DOM if that didn't happen already
		if ( !helper.parents( "body" ).length ) {
			$( o.appendTo !== "parent" ?
				o.appendTo :
				this.currentItem[ 0 ].parentNode )[ 0 ].appendChild( helper[ 0 ] );
		}

		if ( helper[ 0 ] === this.currentItem[ 0 ] ) {
			this._storedCSS = {
				width: this.currentItem[ 0 ].style.width,
				height: this.currentItem[ 0 ].style.height,
				position: this.currentItem.css( "position" ),
				top: this.currentItem.css( "top" ),
				left: this.currentItem.css( "left" )
			};
		}

		if ( !helper[ 0 ].style.width || o.forceHelperSize ) {
			helper.width( this.currentItem.width() );
		}
		if ( !helper[ 0 ].style.height || o.forceHelperSize ) {
			helper.height( this.currentItem.height() );
		}

		return helper;

	},

	_adjustOffsetFromHelper: function( obj ) {
		if ( typeof obj === "string" ) {
			obj = obj.split( " " );
		}
		if ( $.isArray( obj ) ) {
			obj = { left: +obj[ 0 ], top: +obj[ 1 ] || 0 };
		}
		if ( "left" in obj ) {
			this.offset.click.left = obj.left + this.margins.left;
		}
		if ( "right" in obj ) {
			this.offset.click.left = this.helperProportions.width - obj.right + this.margins.left;
		}
		if ( "top" in obj ) {
			this.offset.click.top = obj.top + this.margins.top;
		}
		if ( "bottom" in obj ) {
			this.offset.click.top = this.helperProportions.height - obj.bottom + this.margins.top;
		}
	},

	_getParentOffset: function() {

		//Get the offsetParent and cache its position
		this.offsetParent = this.helper.offsetParent();
		var po = this.offsetParent.offset();

		// This is a special case where we need to modify a offset calculated on start, since the
		// following happened:
		// 1. The position of the helper is absolute, so it's position is calculated based on the
		// next positioned parent
		// 2. The actual offset parent is a child of the scroll parent, and the scroll parent isn't
		// the document, which means that the scroll is included in the initial calculation of the
		// offset of the parent, and never recalculated upon drag
		if ( this.cssPosition === "absolute" && this.scrollParent[ 0 ] !== this.document[ 0 ] &&
				$.contains( this.scrollParent[ 0 ], this.offsetParent[ 0 ] ) ) {
			po.left += this.scrollParent.scrollLeft();
			po.top += this.scrollParent.scrollTop();
		}

		// This needs to be actually done for all browsers, since pageX/pageY includes this
		// information with an ugly IE fix
		if ( this.offsetParent[ 0 ] === this.document[ 0 ].body ||
				( this.offsetParent[ 0 ].tagName &&
				this.offsetParent[ 0 ].tagName.toLowerCase() === "html" && $.ui.ie ) ) {
			po = { top: 0, left: 0 };
		}

		return {
			top: po.top + ( parseInt( this.offsetParent.css( "borderTopWidth" ), 10 ) || 0 ),
			left: po.left + ( parseInt( this.offsetParent.css( "borderLeftWidth" ), 10 ) || 0 )
		};

	},

	_getRelativeOffset: function() {

		if ( this.cssPosition === "relative" ) {
			var p = this.currentItem.position();
			return {
				top: p.top - ( parseInt( this.helper.css( "top" ), 10 ) || 0 ) +
					this.scrollParent.scrollTop(),
				left: p.left - ( parseInt( this.helper.css( "left" ), 10 ) || 0 ) +
					this.scrollParent.scrollLeft()
			};
		} else {
			return { top: 0, left: 0 };
		}

	},

	_cacheMargins: function() {
		this.margins = {
			left: ( parseInt( this.currentItem.css( "marginLeft" ), 10 ) || 0 ),
			top: ( parseInt( this.currentItem.css( "marginTop" ), 10 ) || 0 )
		};
	},

	_cacheHelperProportions: function() {
		this.helperProportions = {
			width: this.helper.outerWidth(),
			height: this.helper.outerHeight()
		};
	},

	_setContainment: function() {

		var ce, co, over,
			o = this.options;
		if ( o.containment === "parent" ) {
			o.containment = this.helper[ 0 ].parentNode;
		}
		if ( o.containment === "document" || o.containment === "window" ) {
			this.containment = [
				0 - this.offset.relative.left - this.offset.parent.left,
				0 - this.offset.relative.top - this.offset.parent.top,
				o.containment === "document" ?
					this.document.width() :
					this.window.width() - this.helperProportions.width - this.margins.left,
				( o.containment === "document" ?
					( this.document.height() || document.body.parentNode.scrollHeight ) :
					this.window.height() || this.document[ 0 ].body.parentNode.scrollHeight
				) - this.helperProportions.height - this.margins.top
			];
		}

		if ( !( /^(document|window|parent)$/ ).test( o.containment ) ) {
			ce = $( o.containment )[ 0 ];
			co = $( o.containment ).offset();
			over = ( $( ce ).css( "overflow" ) !== "hidden" );

			this.containment = [
				co.left + ( parseInt( $( ce ).css( "borderLeftWidth" ), 10 ) || 0 ) +
					( parseInt( $( ce ).css( "paddingLeft" ), 10 ) || 0 ) - this.margins.left,
				co.top + ( parseInt( $( ce ).css( "borderTopWidth" ), 10 ) || 0 ) +
					( parseInt( $( ce ).css( "paddingTop" ), 10 ) || 0 ) - this.margins.top,
				co.left + ( over ? Math.max( ce.scrollWidth, ce.offsetWidth ) : ce.offsetWidth ) -
					( parseInt( $( ce ).css( "borderLeftWidth" ), 10 ) || 0 ) -
					( parseInt( $( ce ).css( "paddingRight" ), 10 ) || 0 ) -
					this.helperProportions.width - this.margins.left,
				co.top + ( over ? Math.max( ce.scrollHeight, ce.offsetHeight ) : ce.offsetHeight ) -
					( parseInt( $( ce ).css( "borderTopWidth" ), 10 ) || 0 ) -
					( parseInt( $( ce ).css( "paddingBottom" ), 10 ) || 0 ) -
					this.helperProportions.height - this.margins.top
			];
		}

	},

	_convertPositionTo: function( d, pos ) {

		if ( !pos ) {
			pos = this.position;
		}
		var mod = d === "absolute" ? 1 : -1,
			scroll = this.cssPosition === "absolute" &&
				!( this.scrollParent[ 0 ] !== this.document[ 0 ] &&
				$.contains( this.scrollParent[ 0 ], this.offsetParent[ 0 ] ) ) ?
					this.offsetParent :
					this.scrollParent,
			scrollIsRootNode = ( /(html|body)/i ).test( scroll[ 0 ].tagName );

		return {
			top: (

				// The absolute mouse position
				pos.top	+

				// Only for relative positioned nodes: Relative offset from element to offset parent
				this.offset.relative.top * mod +

				// The offsetParent's offset without borders (offset + border)
				this.offset.parent.top * mod -
				( ( this.cssPosition === "fixed" ?
					-this.scrollParent.scrollTop() :
					( scrollIsRootNode ? 0 : scroll.scrollTop() ) ) * mod )
			),
			left: (

				// The absolute mouse position
				pos.left +

				// Only for relative positioned nodes: Relative offset from element to offset parent
				this.offset.relative.left * mod +

				// The offsetParent's offset without borders (offset + border)
				this.offset.parent.left * mod	-
				( ( this.cssPosition === "fixed" ?
					-this.scrollParent.scrollLeft() : scrollIsRootNode ? 0 :
					scroll.scrollLeft() ) * mod )
			)
		};

	},

	_generatePosition: function( event ) {

		var top, left,
			o = this.options,
			pageX = event.pageX,
			pageY = event.pageY,
			scroll = this.cssPosition === "absolute" &&
				!( this.scrollParent[ 0 ] !== this.document[ 0 ] &&
				$.contains( this.scrollParent[ 0 ], this.offsetParent[ 0 ] ) ) ?
					this.offsetParent :
					this.scrollParent,
				scrollIsRootNode = ( /(html|body)/i ).test( scroll[ 0 ].tagName );

		// This is another very weird special case that only happens for relative elements:
		// 1. If the css position is relative
		// 2. and the scroll parent is the document or similar to the offset parent
		// we have to refresh the relative offset during the scroll so there are no jumps
		if ( this.cssPosition === "relative" && !( this.scrollParent[ 0 ] !== this.document[ 0 ] &&
				this.scrollParent[ 0 ] !== this.offsetParent[ 0 ] ) ) {
			this.offset.relative = this._getRelativeOffset();
		}

		/*
		 * - Position constraining -
		 * Constrain the position to a mix of grid, containment.
		 */

		if ( this.originalPosition ) { //If we are not dragging yet, we won't check for options

			if ( this.containment ) {
				if ( event.pageX - this.offset.click.left < this.containment[ 0 ] ) {
					pageX = this.containment[ 0 ] + this.offset.click.left;
				}
				if ( event.pageY - this.offset.click.top < this.containment[ 1 ] ) {
					pageY = this.containment[ 1 ] + this.offset.click.top;
				}
				if ( event.pageX - this.offset.click.left > this.containment[ 2 ] ) {
					pageX = this.containment[ 2 ] + this.offset.click.left;
				}
				if ( event.pageY - this.offset.click.top > this.containment[ 3 ] ) {
					pageY = this.containment[ 3 ] + this.offset.click.top;
				}
			}

			if ( o.grid ) {
				top = this.originalPageY + Math.round( ( pageY - this.originalPageY ) /
					o.grid[ 1 ] ) * o.grid[ 1 ];
				pageY = this.containment ?
					( ( top - this.offset.click.top >= this.containment[ 1 ] &&
						top - this.offset.click.top <= this.containment[ 3 ] ) ?
							top :
							( ( top - this.offset.click.top >= this.containment[ 1 ] ) ?
								top - o.grid[ 1 ] : top + o.grid[ 1 ] ) ) :
								top;

				left = this.originalPageX + Math.round( ( pageX - this.originalPageX ) /
					o.grid[ 0 ] ) * o.grid[ 0 ];
				pageX = this.containment ?
					( ( left - this.offset.click.left >= this.containment[ 0 ] &&
						left - this.offset.click.left <= this.containment[ 2 ] ) ?
							left :
							( ( left - this.offset.click.left >= this.containment[ 0 ] ) ?
								left - o.grid[ 0 ] : left + o.grid[ 0 ] ) ) :
								left;
			}

		}

		return {
			top: (

				// The absolute mouse position
				pageY -

				// Click offset (relative to the element)
				this.offset.click.top -

				// Only for relative positioned nodes: Relative offset from element to offset parent
				this.offset.relative.top -

				// The offsetParent's offset without borders (offset + border)
				this.offset.parent.top +
				( ( this.cssPosition === "fixed" ?
					-this.scrollParent.scrollTop() :
					( scrollIsRootNode ? 0 : scroll.scrollTop() ) ) )
			),
			left: (

				// The absolute mouse position
				pageX -

				// Click offset (relative to the element)
				this.offset.click.left -

				// Only for relative positioned nodes: Relative offset from element to offset parent
				this.offset.relative.left -

				// The offsetParent's offset without borders (offset + border)
				this.offset.parent.left +
				( ( this.cssPosition === "fixed" ?
					-this.scrollParent.scrollLeft() :
					scrollIsRootNode ? 0 : scroll.scrollLeft() ) )
			)
		};

	},

	_rearrange: function( event, i, a, hardRefresh ) {

		a ? a[ 0 ].appendChild( this.placeholder[ 0 ] ) :
			i.item[ 0 ].parentNode.insertBefore( this.placeholder[ 0 ],
				( this.direction === "down" ? i.item[ 0 ] : i.item[ 0 ].nextSibling ) );

		//Various things done here to improve the performance:
		// 1. we create a setTimeout, that calls refreshPositions
		// 2. on the instance, we have a counter variable, that get's higher after every append
		// 3. on the local scope, we copy the counter variable, and check in the timeout,
		// if it's still the same
		// 4. this lets only the last addition to the timeout stack through
		this.counter = this.counter ? ++this.counter : 1;
		var counter = this.counter;

		this._delay( function() {
			if ( counter === this.counter ) {

				//Precompute after each DOM insertion, NOT on mousemove
				this.refreshPositions( !hardRefresh );
			}
		} );

	},

	_clear: function( event, noPropagation ) {

		this.reverting = false;

		// We delay all events that have to be triggered to after the point where the placeholder
		// has been removed and everything else normalized again
		var i,
			delayedTriggers = [];

		// We first have to update the dom position of the actual currentItem
		// Note: don't do it if the current item is already removed (by a user), or it gets
		// reappended (see #4088)
		if ( !this._noFinalSort && this.currentItem.parent().length ) {
			this.placeholder.before( this.currentItem );
		}
		this._noFinalSort = null;

		if ( this.helper[ 0 ] === this.currentItem[ 0 ] ) {
			for ( i in this._storedCSS ) {
				if ( this._storedCSS[ i ] === "auto" || this._storedCSS[ i ] === "static" ) {
					this._storedCSS[ i ] = "";
				}
			}
			this.currentItem.css( this._storedCSS );
			this._removeClass( this.currentItem, "ui-sortable-helper" );
		} else {
			this.currentItem.show();
		}

		if ( this.fromOutside && !noPropagation ) {
			delayedTriggers.push( function( event ) {
				this._trigger( "receive", event, this._uiHash( this.fromOutside ) );
			} );
		}
		if ( ( this.fromOutside ||
				this.domPosition.prev !==
				this.currentItem.prev().not( ".ui-sortable-helper" )[ 0 ] ||
				this.domPosition.parent !== this.currentItem.parent()[ 0 ] ) && !noPropagation ) {

			// Trigger update callback if the DOM position has changed
			delayedTriggers.push( function( event ) {
				this._trigger( "update", event, this._uiHash() );
			} );
		}

		// Check if the items Container has Changed and trigger appropriate
		// events.
		if ( this !== this.currentContainer ) {
			if ( !noPropagation ) {
				delayedTriggers.push( function( event ) {
					this._trigger( "remove", event, this._uiHash() );
				} );
				delayedTriggers.push( ( function( c ) {
					return function( event ) {
						c._trigger( "receive", event, this._uiHash( this ) );
					};
				} ).call( this, this.currentContainer ) );
				delayedTriggers.push( ( function( c ) {
					return function( event ) {
						c._trigger( "update", event, this._uiHash( this ) );
					};
				} ).call( this, this.currentContainer ) );
			}
		}

		//Post events to containers
		function delayEvent( type, instance, container ) {
			return function( event ) {
				container._trigger( type, event, instance._uiHash( instance ) );
			};
		}
		for ( i = this.containers.length - 1; i >= 0; i-- ) {
			if ( !noPropagation ) {
				delayedTriggers.push( delayEvent( "deactivate", this, this.containers[ i ] ) );
			}
			if ( this.containers[ i ].containerCache.over ) {
				delayedTriggers.push( delayEvent( "out", this, this.containers[ i ] ) );
				this.containers[ i ].containerCache.over = 0;
			}
		}

		//Do what was originally in plugins
		if ( this.storedCursor ) {
			this.document.find( "body" ).css( "cursor", this.storedCursor );
			this.storedStylesheet.remove();
		}
		if ( this._storedOpacity ) {
			this.helper.css( "opacity", this._storedOpacity );
		}
		if ( this._storedZIndex ) {
			this.helper.css( "zIndex", this._storedZIndex === "auto" ? "" : this._storedZIndex );
		}

		this.dragging = false;

		if ( !noPropagation ) {
			this._trigger( "beforeStop", event, this._uiHash() );
		}

		//$(this.placeholder[0]).remove(); would have been the jQuery way - unfortunately,
		// it unbinds ALL events from the original node!
		this.placeholder[ 0 ].parentNode.removeChild( this.placeholder[ 0 ] );

		if ( !this.cancelHelperRemoval ) {
			if ( this.helper[ 0 ] !== this.currentItem[ 0 ] ) {
				this.helper.remove();
			}
			this.helper = null;
		}

		if ( !noPropagation ) {
			for ( i = 0; i < delayedTriggers.length; i++ ) {

				// Trigger all delayed events
				delayedTriggers[ i ].call( this, event );
			}
			this._trigger( "stop", event, this._uiHash() );
		}

		this.fromOutside = false;
		return !this.cancelHelperRemoval;

	},

	_trigger: function() {
		if ( $.Widget.prototype._trigger.apply( this, arguments ) === false ) {
			this.cancel();
		}
	},

	_uiHash: function( _inst ) {
		var inst = _inst || this;
		return {
			helper: inst.helper,
			placeholder: inst.placeholder || $( [] ),
			position: inst.position,
			originalPosition: inst.originalPosition,
			offset: inst.positionAbs,
			item: inst.currentItem,
			sender: _inst ? _inst.element : null
		};
	}

} );

} ) );


/***/ }),

/***/ "./src/Intracto/SecretSantaBundle/Resources/public/js/wishlist.js":
/*!************************************************************************!*\
  !*** ./src/Intracto/SecretSantaBundle/Resources/public/js/wishlist.js ***!
  \************************************************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($, jQuery) {__webpack_require__(/*! jquery-ui/ui/widgets/sortable */ "./node_modules/jquery-ui/ui/widgets/sortable.js");
__webpack_require__(/*! jquery-ui/ui/disable-selection */ "./node_modules/jquery-ui/ui/disable-selection.js");

function addNewParticipant(collectionHolder) {

    // remove .noitems if present
    $('.noitems').remove();

    // Get participant prototype as defined in attribute data-prototype
    var prototype = collectionHolder.attr('data-prototype');
    // Adjust participant prototype for correct naming
    var number_of_participants = collectionHolder.children().length; // Note, owner is not counted as participant
    var newFormHtml = prototype.replace(/__name__/g, number_of_participants).replace(/__participantcount__/g, number_of_participants + 1);

    // Add new participant to party with animation
    var newForm = $(newFormHtml);
    collectionHolder.append(newForm);
    newForm.show(300);

    // Handle delete button events
    bindDeleteButtonEvents();

    // Remove disabled state on delete-buttons
    $('.remove-participant').removeClass('disabled');

    // reset ranks
    resetRanks();
}

function bindDeleteButtonEvents() {
    // Loop over all delete buttons
    $('button.remove-participant').each(function (i) {
        // Remove any previously binded event
        $(this).off('click');

        // Bind event
        $(this).click(function (e) {
            e.preventDefault();

            if ($(this).parents("tr.wishlistitem").hasClass('new-row')) {
                $(this).parents("tr.wishlistitem").remove();
                $('.add-new-participant').show();
            }

            var newRowValue = $('.new-row .wishlistitem-description').val();
            if (typeof newRowValue != 'undefined' && newRowValue == '') {
                $('.new-row').remove();
                $('.add-new-participant').show();
            }

            // XXX: last item can't be removed because an empty form can't be saved. This is a workaround
            if ($('.wishlistitem-description').length == 1) {
                $('.wishlistitem-description').val('');
            } else {
                $(this).parents("tr.wishlistitem").remove();
            }

            $('.ajax-response').children().hide();
            $('.ajax-response .empty').hide();
            $('.ajax-response .removed').show();

            resetRanks();
            ajaxSaveWishlist();
        });
    });
}

function resetRanks() {
    $('table.wishlist-items tbody tr').each(function (i) {
        $(this).find('td input[type="hidden"]').val(i + 1);
        $(this).find('td span.rank').text(i + 1);
    });
}

function ajaxSaveWishlist() {
    var newRowValue = $('.new-row .wishlistitem-description').val();
    if (typeof newRowValue != 'undefined' && newRowValue == '') {
        $('.ajax-response .empty').show();

        return false;
    }

    $('.ajax-response .empty').hide();
    var formData = $('#add_item_to_wishlist_form').serializeArray();
    var url = $('#add_item_to_wishlist_form').attr('action');

    $.ajax({
        type: 'POST',
        url: url,
        data: formData
    });
}

/* Variables */
var collectionHolder = $('table.wishlist-items tbody');

/* Document Ready */
jQuery(document).ready(function () {

    //Add eventlistener on add-new-participant button
    $('.add-new-participant').click(function (e) {
        e.preventDefault();

        $('.add-new-participant').hide();

        addNewParticipant(collectionHolder);
    });

    $('.update-participant').click(function (e) {
        e.preventDefault();

        $(this).hide();
        $('.ajax-response').children().hide();
        $('.ajax-response .updated').show();

        ajaxSaveWishlist();
    });

    $('#add_item_to_wishlist_form').submit(function (e) {
        e.preventDefault();

        var submitButton = $(this).find('button[type=submit]');
        submitButton.hide();

        $('.add-new-participant').hide();
        $('.ajax-response').children().hide();
        $('.ajax-response .added').show();
        $('tr.wishlistitem').removeClass('new-row');

        ajaxSaveWishlist();
        addNewParticipant(collectionHolder);
    });

    $('.wishlistitem').on('keydown', '.wishlistitem-description', function () {
        $(this).closest('.wishlistitem').find('button.update-participant').show();
    });

    bindDeleteButtonEvents();
    $('.remove-participant').removeClass('disabled');

    // sortable
    $("table.wishlist-items tbody").sortable({
        stop: function stop() {
            resetRanks();
            ajaxSaveWishlist();
        }
    });

    $('table.wishlist-items tbody').bind('click.sortable mousedown.sortable', function (ev) {
        ev.target.focus();
    });
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ })

},["./src/Intracto/SecretSantaBundle/Resources/public/js/wishlist.js"]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvanF1ZXJ5LXVpL3VpL2RhdGEuanMiLCJ3ZWJwYWNrOi8vLy4vbm9kZV9tb2R1bGVzL2pxdWVyeS11aS91aS9kaXNhYmxlLXNlbGVjdGlvbi5qcyIsIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvanF1ZXJ5LXVpL3VpL2llLmpzIiwid2VicGFjazovLy8uL25vZGVfbW9kdWxlcy9qcXVlcnktdWkvdWkvc2Nyb2xsLXBhcmVudC5qcyIsIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvanF1ZXJ5LXVpL3VpL3ZlcnNpb24uanMiLCJ3ZWJwYWNrOi8vLy4vbm9kZV9tb2R1bGVzL2pxdWVyeS11aS91aS93aWRnZXQuanMiLCJ3ZWJwYWNrOi8vLy4vbm9kZV9tb2R1bGVzL2pxdWVyeS11aS91aS93aWRnZXRzL21vdXNlLmpzIiwid2VicGFjazovLy8uL25vZGVfbW9kdWxlcy9qcXVlcnktdWkvdWkvd2lkZ2V0cy9zb3J0YWJsZS5qcyIsIndlYnBhY2s6Ly8vLi9zcmMvSW50cmFjdG8vU2VjcmV0U2FudGFCdW5kbGUvUmVzb3VyY2VzL3B1YmxpYy9qcy93aXNobGlzdC5qcyJdLCJuYW1lcyI6WyJyZXF1aXJlIiwiYWRkTmV3UGFydGljaXBhbnQiLCJjb2xsZWN0aW9uSG9sZGVyIiwiJCIsInJlbW92ZSIsInByb3RvdHlwZSIsImF0dHIiLCJudW1iZXJfb2ZfcGFydGljaXBhbnRzIiwiY2hpbGRyZW4iLCJsZW5ndGgiLCJuZXdGb3JtSHRtbCIsInJlcGxhY2UiLCJuZXdGb3JtIiwiYXBwZW5kIiwic2hvdyIsImJpbmREZWxldGVCdXR0b25FdmVudHMiLCJyZW1vdmVDbGFzcyIsInJlc2V0UmFua3MiLCJlYWNoIiwiaSIsIm9mZiIsImNsaWNrIiwiZSIsInByZXZlbnREZWZhdWx0IiwicGFyZW50cyIsImhhc0NsYXNzIiwibmV3Um93VmFsdWUiLCJ2YWwiLCJoaWRlIiwiYWpheFNhdmVXaXNobGlzdCIsImZpbmQiLCJ0ZXh0IiwiZm9ybURhdGEiLCJzZXJpYWxpemVBcnJheSIsInVybCIsImFqYXgiLCJ0eXBlIiwiZGF0YSIsImpRdWVyeSIsImRvY3VtZW50IiwicmVhZHkiLCJzdWJtaXQiLCJzdWJtaXRCdXR0b24iLCJvbiIsImNsb3Nlc3QiLCJzb3J0YWJsZSIsInN0b3AiLCJiaW5kIiwiZXYiLCJ0YXJnZXQiLCJmb2N1cyJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7OztBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQUE7QUFBQTtBQUFBO0FBQ0EsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7QUFDQSxDQUFDO0FBQ0Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsR0FBRzs7QUFFSDtBQUNBO0FBQ0E7QUFDQTtBQUNBLENBQUM7QUFDRCxDQUFDOzs7Ozs7Ozs7Ozs7O0FDdENEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFBQTtBQUFBO0FBQUE7QUFDQSxFQUFFOztBQUVGO0FBQ0E7QUFDQTtBQUNBLENBQUM7O0FBRUQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxJQUFJO0FBQ0o7QUFDQSxFQUFFOztBQUVGO0FBQ0E7QUFDQTtBQUNBLENBQUM7O0FBRUQsQ0FBQzs7Ozs7Ozs7Ozs7OztBQzdDRDtBQUNBOztBQUVBO0FBQ0E7QUFBQTtBQUFBO0FBQUE7QUFDQSxFQUFFOztBQUVGO0FBQ0E7QUFDQTtBQUNBLENBQUM7O0FBRUQ7QUFDQTtBQUNBLENBQUM7Ozs7Ozs7Ozs7Ozs7QUNkRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUFBO0FBQUE7QUFBQTtBQUNBLEVBQUU7O0FBRUY7QUFDQTtBQUNBO0FBQ0EsQ0FBQzs7QUFFRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsR0FBRzs7QUFFSDtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxDQUFDOzs7Ozs7Ozs7Ozs7O0FDNUNEO0FBQ0E7O0FBRUE7QUFDQTtBQUFBO0FBQUE7QUFBQTtBQUNBLEVBQUU7O0FBRUY7QUFDQTtBQUNBO0FBQ0EsQ0FBQzs7QUFFRDs7QUFFQTs7QUFFQSxDQUFDOzs7Ozs7Ozs7Ozs7O0FDaEJEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFBQTtBQUFBO0FBQUE7QUFDQSxFQUFFOztBQUVGO0FBQ0E7QUFDQTtBQUNBLENBQUM7O0FBRUQ7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxjQUFjLCtCQUErQjtBQUM3Qzs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0EsSUFBSTtBQUNKO0FBQ0E7QUFDQTtBQUNBLENBQUM7O0FBRUQ7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0Esd0NBQXdDO0FBQ3hDOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxzQkFBc0I7O0FBRXRCO0FBQ0E7QUFDQTtBQUNBLEVBQUU7O0FBRUY7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsNENBQTRDO0FBQzVDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxH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O0FBRUg7QUFDQTtBQUNBLGVBQWUsb0JBQW9CO0FBQ25DO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLEdBQUc7O0FBRUg7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0EsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0gsRUFBRTs7QUFFRjtBQUNBO0FBQ0EsRUFBRTs7QUFFRjtBQUNBO0FBQ0EsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsSUFBSTtBQUNKO0FBQ0E7QUFDQSxHQUFHO0FBQ0gsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEVBQUU7O0FBRUY7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxFQUFFOztBQUVGO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxJQUFJO0FBQ0o7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNILEVBQUU7O0FBRUY7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLElBQUk7QUFDSjtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0gsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxTQUFTLGtDQUFrQztBQUMzQztBQUNBO0FBQ0EsY0FBYztBQUNkOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsY0FBYztBQUNkOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsSUFBSTtBQUNKO0FBQ0E7QUFDQSxDQUFDOztBQUVEOztBQUVBLENBQUM7Ozs7Ozs7Ozs7Ozs7QUM1dEJEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUFBO0FBQUE7QUFBQTtBQUNBLEVBQUU7O0FBRUY7QUFDQTtBQUNBO0FBQ0EsQ0FBQzs7QUFFRDtBQUNBO0FBQ0E7QUFDQSxDQUFDOztBQUVEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEVBQUU7QUFDRjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLElBQUk7QUFDSjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxJQUFJOztBQUVKO0FBQ0EsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxFQUFFOztBQUVGOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLElBQUk7QUFDSjs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBLEVBQUU7O0FBRUY7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLElBQUk7O0FBRUo7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0EsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLEVBQUU7O0FBRUY7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsRUFBRTs7QUFFRjtBQUNBO0FBQ0EsRUFBRTs7QUFFRjtBQUNBLHdDQUF3QztBQUN4Qyx1Q0FBdUM7QUFDdkMsdUNBQXVDO0FBQ3ZDLHlDQUF5QyxhQUFhO0FBQ3RELENBQUM7O0FBRUQsQ0FBQzs7Ozs7Ozs7Ozs7OztBQ2pPRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQUE7QUFBQTtBQUFBO0FBQ0EsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7QUFDQSxDQUFDOztBQUVEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEVBQUU7O0FBRUY7QUFDQTtBQUNBLEVBQUU7O0FBRUY7QUFDQTtBQUNBO0FBQ0EsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTs7QUFFQSxFQUFFOztBQUVGO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSCxFQUFFOztBQUVGO0FBQ0E7O0FBRUEsc0NBQXNDLFFBQVE7QUFDOUM7QUFDQTs7QUFFQTtBQUNBLEVBQUU7O0FBRUY7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLElBQUk7QUFDSjtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUEsRUFBRTs7QUFFRjs7QUFFQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLFdBQVc7QUFDWDtBQUNBO0FBQ0EsSUFBSTtBQUNKOztBQUVBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7O0FBRUg7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSwwQ0FBMEM7QUFDMUM7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0EsaUJBQWlCLHFDQUFxQyxFQUFFO0FBQ3hEOztBQUVBLG9CQUFvQjtBQUNwQjtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLG1CQUFtQjtBQUNuQjtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0Esd0NBQXdDLFFBQVE7QUFDaEQ7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxFQUFFOztBQUVGO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTs7QUFFQSxJQUFJOztBQUVKO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLGtDQUFrQyxRQUFROztBQUUxQztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQSxFQUFFOztBQUVGOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7O0FBRUE7O0FBRUEsRUFBRTs7QUFFRjs7QUFFQTs7QUFFQSwyQ0FBMkMsZUFBZTs7QUFFMUQ7QUFDQTtBQUNBO0FBQ0EsSUFBSTtBQUNKO0FBQ0E7O0FBRUE7QUFDQSw0Q0FBNEMsUUFBUTtBQUNwRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7O0FBRUEscUNBQXFDO0FBQ3JDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLElBQUk7O0FBRUo7QUFDQTtBQUNBLElBQUk7QUFDSjtBQUNBO0FBQ0E7O0FBRUE7O0FBRUEsRUFBRTs7QUFFRjs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7O0FBRUg7QUFDQTtBQUNBOztBQUVBOztBQUVBLEVBQUU7O0FBRUY7O0FBRUE7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0EsR0FBRztBQUNIOztBQUVBLEVBQUU7O0FBRUY7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxHQUFHOztBQUVIO0FBQ0E7QUFDQTtBQUNBLHFEQUFxRDs7QUFFckQ7QUFDQSxFQUFFOztBQUVGO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7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QUNBLEdBQUc7O0FBRUg7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxtQ0FBbUMsUUFBUTtBQUMzQztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQSxFQUFFOztBQUVGOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBLEVBQUU7O0FBRUY7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFVBQVU7QUFDVjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEVBQUU7O0FBRUY7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNUOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLEVBQUU7O0FBRUY7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSCxXQUFXO0FBQ1g7O0FBRUEsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsRUFBRTs7QUFFRjs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLEVBQUU7O0FBRUY7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLEVBQUU7O0FBRUY7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxnQ0FBZ0M7O0FBRWhDO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxFQUFFOztBQUVGOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNB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QWxCO0FBQ0gsS0FiRDs7QUFlQUMsTUFBRSxlQUFGLEVBQW1Cd0MsRUFBbkIsQ0FBc0IsU0FBdEIsRUFBaUMsMkJBQWpDLEVBQThELFlBQVk7QUFDdEV4QyxVQUFFLElBQUYsRUFBUXlDLE9BQVIsQ0FBZ0IsZUFBaEIsRUFBaUNkLElBQWpDLENBQXNDLDJCQUF0QyxFQUFtRWhCLElBQW5FO0FBQ0gsS0FGRDs7QUFJQUM7QUFDQVosTUFBRSxxQkFBRixFQUF5QmEsV0FBekIsQ0FBcUMsVUFBckM7O0FBRUE7QUFDQWIsTUFBRSw0QkFBRixFQUFnQzBDLFFBQWhDLENBQXlDO0FBQ3JDQyxjQUFNLGdCQUFZO0FBQ2Q3QjtBQUNBWTtBQUNIO0FBSm9DLEtBQXpDOztBQU9BMUIsTUFBRSw0QkFBRixFQUFnQzRDLElBQWhDLENBQXFDLG1DQUFyQyxFQUEwRSxVQUFVQyxFQUFWLEVBQWM7QUFDcEZBLFdBQUdDLE1BQUgsQ0FBVUMsS0FBVjtBQUNILEtBRkQ7QUFJSCxDQXZERCxFIiwiZmlsZSI6ImpzL3dpc2hsaXN0LjYyNzllYTg0OWZiYWU0YWQ1NjZjLmpzIiwic291cmNlc0NvbnRlbnQiOlsiLyohXG4gKiBqUXVlcnkgVUkgOmRhdGEgMS4xMi4xXG4gKiBodHRwOi8vanF1ZXJ5dWkuY29tXG4gKlxuICogQ29weXJpZ2h0IGpRdWVyeSBGb3VuZGF0aW9uIGFuZCBvdGhlciBjb250cmlidXRvcnNcbiAqIFJlbGVhc2VkIHVuZGVyIHRoZSBNSVQgbGljZW5zZS5cbiAqIGh0dHA6Ly9qcXVlcnkub3JnL2xpY2Vuc2VcbiAqL1xuXG4vLz4+bGFiZWw6IDpkYXRhIFNlbGVjdG9yXG4vLz4+Z3JvdXA6IENvcmVcbi8vPj5kZXNjcmlwdGlvbjogU2VsZWN0cyBlbGVtZW50cyB3aGljaCBoYXZlIGRhdGEgc3RvcmVkIHVuZGVyIHRoZSBzcGVjaWZpZWQga2V5LlxuLy8+PmRvY3M6IGh0dHA6Ly9hcGkuanF1ZXJ5dWkuY29tL2RhdGEtc2VsZWN0b3IvXG5cbiggZnVuY3Rpb24oIGZhY3RvcnkgKSB7XG5cdGlmICggdHlwZW9mIGRlZmluZSA9PT0gXCJmdW5jdGlvblwiICYmIGRlZmluZS5hbWQgKSB7XG5cblx0XHQvLyBBTUQuIFJlZ2lzdGVyIGFzIGFuIGFub255bW91cyBtb2R1bGUuXG5cdFx0ZGVmaW5lKCBbIFwianF1ZXJ5XCIsIFwiLi92ZXJzaW9uXCIgXSwgZmFjdG9yeSApO1xuXHR9IGVsc2Uge1xuXG5cdFx0Ly8gQnJvd3NlciBnbG9iYWxzXG5cdFx0ZmFjdG9yeSggalF1ZXJ5ICk7XG5cdH1cbn0gKCBmdW5jdGlvbiggJCApIHtcbnJldHVybiAkLmV4dGVuZCggJC5leHByWyBcIjpcIiBdLCB7XG5cdGRhdGE6ICQuZXhwci5jcmVhdGVQc2V1ZG8gP1xuXHRcdCQuZXhwci5jcmVhdGVQc2V1ZG8oIGZ1bmN0aW9uKCBkYXRhTmFtZSApIHtcblx0XHRcdHJldHVybiBmdW5jdGlvbiggZWxlbSApIHtcblx0XHRcdFx0cmV0dXJuICEhJC5kYXRhKCBlbGVtLCBkYXRhTmFtZSApO1xuXHRcdFx0fTtcblx0XHR9ICkgOlxuXG5cdFx0Ly8gU3VwcG9ydDogalF1ZXJ5IDwxLjhcblx0XHRmdW5jdGlvbiggZWxlbSwgaSwgbWF0Y2ggKSB7XG5cdFx0XHRyZXR1cm4gISEkLmRhdGEoIGVsZW0sIG1hdGNoWyAzIF0gKTtcblx0XHR9XG59ICk7XG59ICkgKTtcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vbm9kZV9tb2R1bGVzL2pxdWVyeS11aS91aS9kYXRhLmpzXG4vLyBtb2R1bGUgaWQgPSAuL25vZGVfbW9kdWxlcy9qcXVlcnktdWkvdWkvZGF0YS5qc1xuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCIvKiFcbiAqIGpRdWVyeSBVSSBEaXNhYmxlIFNlbGVjdGlvbiAxLjEyLjFcbiAqIGh0dHA6Ly9qcXVlcnl1aS5jb21cbiAqXG4gKiBDb3B5cmlnaHQgalF1ZXJ5IEZvdW5kYXRpb24gYW5kIG90aGVyIGNvbnRyaWJ1dG9yc1xuICogUmVsZWFzZWQgdW5kZXIgdGhlIE1JVCBsaWNlbnNlLlxuICogaHR0cDovL2pxdWVyeS5vcmcvbGljZW5zZVxuICovXG5cbi8vPj5sYWJlbDogZGlzYWJsZVNlbGVjdGlvblxuLy8+Pmdyb3VwOiBDb3JlXG4vLz4+ZGVzY3JpcHRpb246IERpc2FibGUgc2VsZWN0aW9uIG9mIHRleHQgY29udGVudCB3aXRoaW4gdGhlIHNldCBvZiBtYXRjaGVkIGVsZW1lbnRzLlxuLy8+PmRvY3M6IGh0dHA6Ly9hcGkuanF1ZXJ5dWkuY29tL2Rpc2FibGVTZWxlY3Rpb24vXG5cbi8vIFRoaXMgZmlsZSBpcyBkZXByZWNhdGVkXG4oIGZ1bmN0aW9uKCBmYWN0b3J5ICkge1xuXHRpZiAoIHR5cGVvZiBkZWZpbmUgPT09IFwiZnVuY3Rpb25cIiAmJiBkZWZpbmUuYW1kICkge1xuXG5cdFx0Ly8gQU1ELiBSZWdpc3RlciBhcyBhbiBhbm9ueW1vdXMgbW9kdWxlLlxuXHRcdGRlZmluZSggWyBcImpxdWVyeVwiLCBcIi4vdmVyc2lvblwiIF0sIGZhY3RvcnkgKTtcblx0fSBlbHNlIHtcblxuXHRcdC8vIEJyb3dzZXIgZ2xvYmFsc1xuXHRcdGZhY3RvcnkoIGpRdWVyeSApO1xuXHR9XG59ICggZnVuY3Rpb24oICQgKSB7XG5cbnJldHVybiAkLmZuLmV4dGVuZCgge1xuXHRkaXNhYmxlU2VsZWN0aW9uOiAoIGZ1bmN0aW9uKCkge1xuXHRcdHZhciBldmVudFR5cGUgPSBcIm9uc2VsZWN0c3RhcnRcIiBpbiBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCBcImRpdlwiICkgP1xuXHRcdFx0XCJzZWxlY3RzdGFydFwiIDpcblx0XHRcdFwibW91c2Vkb3duXCI7XG5cblx0XHRyZXR1cm4gZnVuY3Rpb24oKSB7XG5cdFx0XHRyZXR1cm4gdGhpcy5vbiggZXZlbnRUeXBlICsgXCIudWktZGlzYWJsZVNlbGVjdGlvblwiLCBmdW5jdGlvbiggZXZlbnQgKSB7XG5cdFx0XHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHR9ICk7XG5cdFx0fTtcblx0fSApKCksXG5cblx0ZW5hYmxlU2VsZWN0aW9uOiBmdW5jdGlvbigpIHtcblx0XHRyZXR1cm4gdGhpcy5vZmYoIFwiLnVpLWRpc2FibGVTZWxlY3Rpb25cIiApO1xuXHR9XG59ICk7XG5cbn0gKSApO1xuXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9ub2RlX21vZHVsZXMvanF1ZXJ5LXVpL3VpL2Rpc2FibGUtc2VsZWN0aW9uLmpzXG4vLyBtb2R1bGUgaWQgPSAuL25vZGVfbW9kdWxlcy9qcXVlcnktdWkvdWkvZGlzYWJsZS1zZWxlY3Rpb24uanNcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwiKCBmdW5jdGlvbiggZmFjdG9yeSApIHtcblx0aWYgKCB0eXBlb2YgZGVmaW5lID09PSBcImZ1bmN0aW9uXCIgJiYgZGVmaW5lLmFtZCApIHtcblxuXHRcdC8vIEFNRC4gUmVnaXN0ZXIgYXMgYW4gYW5vbnltb3VzIG1vZHVsZS5cblx0XHRkZWZpbmUoIFsgXCJqcXVlcnlcIiwgXCIuL3ZlcnNpb25cIiBdLCBmYWN0b3J5ICk7XG5cdH0gZWxzZSB7XG5cblx0XHQvLyBCcm93c2VyIGdsb2JhbHNcblx0XHRmYWN0b3J5KCBqUXVlcnkgKTtcblx0fVxufSAoIGZ1bmN0aW9uKCAkICkge1xuXG4vLyBUaGlzIGZpbGUgaXMgZGVwcmVjYXRlZFxucmV0dXJuICQudWkuaWUgPSAhIS9tc2llIFtcXHcuXSsvLmV4ZWMoIG5hdmlnYXRvci51c2VyQWdlbnQudG9Mb3dlckNhc2UoKSApO1xufSApICk7XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL25vZGVfbW9kdWxlcy9qcXVlcnktdWkvdWkvaWUuanNcbi8vIG1vZHVsZSBpZCA9IC4vbm9kZV9tb2R1bGVzL2pxdWVyeS11aS91aS9pZS5qc1xuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCIvKiFcbiAqIGpRdWVyeSBVSSBTY3JvbGwgUGFyZW50IDEuMTIuMVxuICogaHR0cDovL2pxdWVyeXVpLmNvbVxuICpcbiAqIENvcHlyaWdodCBqUXVlcnkgRm91bmRhdGlvbiBhbmQgb3RoZXIgY29udHJpYnV0b3JzXG4gKiBSZWxlYXNlZCB1bmRlciB0aGUgTUlUIGxpY2Vuc2UuXG4gKiBodHRwOi8vanF1ZXJ5Lm9yZy9saWNlbnNlXG4gKi9cblxuLy8+PmxhYmVsOiBzY3JvbGxQYXJlbnRcbi8vPj5ncm91cDogQ29yZVxuLy8+PmRlc2NyaXB0aW9uOiBHZXQgdGhlIGNsb3Nlc3QgYW5jZXN0b3IgZWxlbWVudCB0aGF0IGlzIHNjcm9sbGFibGUuXG4vLz4+ZG9jczogaHR0cDovL2FwaS5qcXVlcnl1aS5jb20vc2Nyb2xsUGFyZW50L1xuXG4oIGZ1bmN0aW9uKCBmYWN0b3J5ICkge1xuXHRpZiAoIHR5cGVvZiBkZWZpbmUgPT09IFwiZnVuY3Rpb25cIiAmJiBkZWZpbmUuYW1kICkge1xuXG5cdFx0Ly8gQU1ELiBSZWdpc3RlciBhcyBhbiBhbm9ueW1vdXMgbW9kdWxlLlxuXHRcdGRlZmluZSggWyBcImpxdWVyeVwiLCBcIi4vdmVyc2lvblwiIF0sIGZhY3RvcnkgKTtcblx0fSBlbHNlIHtcblxuXHRcdC8vIEJyb3dzZXIgZ2xvYmFsc1xuXHRcdGZhY3RvcnkoIGpRdWVyeSApO1xuXHR9XG59ICggZnVuY3Rpb24oICQgKSB7XG5cbnJldHVybiAkLmZuLnNjcm9sbFBhcmVudCA9IGZ1bmN0aW9uKCBpbmNsdWRlSGlkZGVuICkge1xuXHR2YXIgcG9zaXRpb24gPSB0aGlzLmNzcyggXCJwb3NpdGlvblwiICksXG5cdFx0ZXhjbHVkZVN0YXRpY1BhcmVudCA9IHBvc2l0aW9uID09PSBcImFic29sdXRlXCIsXG5cdFx0b3ZlcmZsb3dSZWdleCA9IGluY2x1ZGVIaWRkZW4gPyAvKGF1dG98c2Nyb2xsfGhpZGRlbikvIDogLyhhdXRvfHNjcm9sbCkvLFxuXHRcdHNjcm9sbFBhcmVudCA9IHRoaXMucGFyZW50cygpLmZpbHRlciggZnVuY3Rpb24oKSB7XG5cdFx0XHR2YXIgcGFyZW50ID0gJCggdGhpcyApO1xuXHRcdFx0aWYgKCBleGNsdWRlU3RhdGljUGFyZW50ICYmIHBhcmVudC5jc3MoIFwicG9zaXRpb25cIiApID09PSBcInN0YXRpY1wiICkge1xuXHRcdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0XHR9XG5cdFx0XHRyZXR1cm4gb3ZlcmZsb3dSZWdleC50ZXN0KCBwYXJlbnQuY3NzKCBcIm92ZXJmbG93XCIgKSArIHBhcmVudC5jc3MoIFwib3ZlcmZsb3cteVwiICkgK1xuXHRcdFx0XHRwYXJlbnQuY3NzKCBcIm92ZXJmbG93LXhcIiApICk7XG5cdFx0fSApLmVxKCAwICk7XG5cblx0cmV0dXJuIHBvc2l0aW9uID09PSBcImZpeGVkXCIgfHwgIXNjcm9sbFBhcmVudC5sZW5ndGggP1xuXHRcdCQoIHRoaXNbIDAgXS5vd25lckRvY3VtZW50IHx8IGRvY3VtZW50ICkgOlxuXHRcdHNjcm9sbFBhcmVudDtcbn07XG5cbn0gKSApO1xuXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9ub2RlX21vZHVsZXMvanF1ZXJ5LXVpL3VpL3Njcm9sbC1wYXJlbnQuanNcbi8vIG1vZHVsZSBpZCA9IC4vbm9kZV9tb2R1bGVzL2pxdWVyeS11aS91aS9zY3JvbGwtcGFyZW50LmpzXG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsIiggZnVuY3Rpb24oIGZhY3RvcnkgKSB7XG5cdGlmICggdHlwZW9mIGRlZmluZSA9PT0gXCJmdW5jdGlvblwiICYmIGRlZmluZS5hbWQgKSB7XG5cblx0XHQvLyBBTUQuIFJlZ2lzdGVyIGFzIGFuIGFub255bW91cyBtb2R1bGUuXG5cdFx0ZGVmaW5lKCBbIFwianF1ZXJ5XCIgXSwgZmFjdG9yeSApO1xuXHR9IGVsc2Uge1xuXG5cdFx0Ly8gQnJvd3NlciBnbG9iYWxzXG5cdFx0ZmFjdG9yeSggalF1ZXJ5ICk7XG5cdH1cbn0gKCBmdW5jdGlvbiggJCApIHtcblxuJC51aSA9ICQudWkgfHwge307XG5cbnJldHVybiAkLnVpLnZlcnNpb24gPSBcIjEuMTIuMVwiO1xuXG59ICkgKTtcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vbm9kZV9tb2R1bGVzL2pxdWVyeS11aS91aS92ZXJzaW9uLmpzXG4vLyBtb2R1bGUgaWQgPSAuL25vZGVfbW9kdWxlcy9qcXVlcnktdWkvdWkvdmVyc2lvbi5qc1xuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCIvKiFcbiAqIGpRdWVyeSBVSSBXaWRnZXQgMS4xMi4xXG4gKiBodHRwOi8vanF1ZXJ5dWkuY29tXG4gKlxuICogQ29weXJpZ2h0IGpRdWVyeSBGb3VuZGF0aW9uIGFuZCBvdGhlciBjb250cmlidXRvcnNcbiAqIFJlbGVhc2VkIHVuZGVyIHRoZSBNSVQgbGljZW5zZS5cbiAqIGh0dHA6Ly9qcXVlcnkub3JnL2xpY2Vuc2VcbiAqL1xuXG4vLz4+bGFiZWw6IFdpZGdldFxuLy8+Pmdyb3VwOiBDb3JlXG4vLz4+ZGVzY3JpcHRpb246IFByb3ZpZGVzIGEgZmFjdG9yeSBmb3IgY3JlYXRpbmcgc3RhdGVmdWwgd2lkZ2V0cyB3aXRoIGEgY29tbW9uIEFQSS5cbi8vPj5kb2NzOiBodHRwOi8vYXBpLmpxdWVyeXVpLmNvbS9qUXVlcnkud2lkZ2V0L1xuLy8+PmRlbW9zOiBodHRwOi8vanF1ZXJ5dWkuY29tL3dpZGdldC9cblxuKCBmdW5jdGlvbiggZmFjdG9yeSApIHtcblx0aWYgKCB0eXBlb2YgZGVmaW5lID09PSBcImZ1bmN0aW9uXCIgJiYgZGVmaW5lLmFtZCApIHtcblxuXHRcdC8vIEFNRC4gUmVnaXN0ZXIgYXMgYW4gYW5vbnltb3VzIG1vZHVsZS5cblx0XHRkZWZpbmUoIFsgXCJqcXVlcnlcIiwgXCIuL3ZlcnNpb25cIiBdLCBmYWN0b3J5ICk7XG5cdH0gZWxzZSB7XG5cblx0XHQvLyBCcm93c2VyIGdsb2JhbHNcblx0XHRmYWN0b3J5KCBqUXVlcnkgKTtcblx0fVxufSggZnVuY3Rpb24oICQgKSB7XG5cbnZhciB3aWRnZXRVdWlkID0gMDtcbnZhciB3aWRnZXRTbGljZSA9IEFycmF5LnByb3RvdHlwZS5zbGljZTtcblxuJC5jbGVhbkRhdGEgPSAoIGZ1bmN0aW9uKCBvcmlnICkge1xuXHRyZXR1cm4gZnVuY3Rpb24oIGVsZW1zICkge1xuXHRcdHZhciBldmVudHMsIGVsZW0sIGk7XG5cdFx0Zm9yICggaSA9IDA7ICggZWxlbSA9IGVsZW1zWyBpIF0gKSAhPSBudWxsOyBpKysgKSB7XG5cdFx0XHR0cnkge1xuXG5cdFx0XHRcdC8vIE9ubHkgdHJpZ2dlciByZW1vdmUgd2hlbiBuZWNlc3NhcnkgdG8gc2F2ZSB0aW1lXG5cdFx0XHRcdGV2ZW50cyA9ICQuX2RhdGEoIGVsZW0sIFwiZXZlbnRzXCIgKTtcblx0XHRcdFx0aWYgKCBldmVudHMgJiYgZXZlbnRzLnJlbW92ZSApIHtcblx0XHRcdFx0XHQkKCBlbGVtICkudHJpZ2dlckhhbmRsZXIoIFwicmVtb3ZlXCIgKTtcblx0XHRcdFx0fVxuXG5cdFx0XHQvLyBIdHRwOi8vYnVncy5qcXVlcnkuY29tL3RpY2tldC84MjM1XG5cdFx0XHR9IGNhdGNoICggZSApIHt9XG5cdFx0fVxuXHRcdG9yaWcoIGVsZW1zICk7XG5cdH07XG59ICkoICQuY2xlYW5EYXRhICk7XG5cbiQud2lkZ2V0ID0gZnVuY3Rpb24oIG5hbWUsIGJhc2UsIHByb3RvdHlwZSApIHtcblx0dmFyIGV4aXN0aW5nQ29uc3RydWN0b3IsIGNvbnN0cnVjdG9yLCBiYXNlUHJvdG90eXBlO1xuXG5cdC8vIFByb3hpZWRQcm90b3R5cGUgYWxsb3dzIHRoZSBwcm92aWRlZCBwcm90b3R5cGUgdG8gcmVtYWluIHVubW9kaWZpZWRcblx0Ly8gc28gdGhhdCBpdCBjYW4gYmUgdXNlZCBhcyBhIG1peGluIGZvciBtdWx0aXBsZSB3aWRnZXRzICgjODg3Nilcblx0dmFyIHByb3hpZWRQcm90b3R5cGUgPSB7fTtcblxuXHR2YXIgbmFtZXNwYWNlID0gbmFtZS5zcGxpdCggXCIuXCIgKVsgMCBdO1xuXHRuYW1lID0gbmFtZS5zcGxpdCggXCIuXCIgKVsgMSBdO1xuXHR2YXIgZnVsbE5hbWUgPSBuYW1lc3BhY2UgKyBcIi1cIiArIG5hbWU7XG5cblx0aWYgKCAhcHJvdG90eXBlICkge1xuXHRcdHByb3RvdHlwZSA9IGJhc2U7XG5cdFx0YmFzZSA9ICQuV2lkZ2V0O1xuXHR9XG5cblx0aWYgKCAkLmlzQXJyYXkoIHByb3RvdHlwZSApICkge1xuXHRcdHByb3RvdHlwZSA9ICQuZXh0ZW5kLmFwcGx5KCBudWxsLCBbIHt9IF0uY29uY2F0KCBwcm90b3R5cGUgKSApO1xuXHR9XG5cblx0Ly8gQ3JlYXRlIHNlbGVjdG9yIGZvciBwbHVnaW5cblx0JC5leHByWyBcIjpcIiBdWyBmdWxsTmFtZS50b0xvd2VyQ2FzZSgpIF0gPSBmdW5jdGlvbiggZWxlbSApIHtcblx0XHRyZXR1cm4gISEkLmRhdGEoIGVsZW0sIGZ1bGxOYW1lICk7XG5cdH07XG5cblx0JFsgbmFtZXNwYWNlIF0gPSAkWyBuYW1lc3BhY2UgXSB8fCB7fTtcblx0ZXhpc3RpbmdDb25zdHJ1Y3RvciA9ICRbIG5hbWVzcGFjZSBdWyBuYW1lIF07XG5cdGNvbnN0cnVjdG9yID0gJFsgbmFtZXNwYWNlIF1bIG5hbWUgXSA9IGZ1bmN0aW9uKCBvcHRpb25zLCBlbGVtZW50ICkge1xuXG5cdFx0Ly8gQWxsb3cgaW5zdGFudGlhdGlvbiB3aXRob3V0IFwibmV3XCIga2V5d29yZFxuXHRcdGlmICggIXRoaXMuX2NyZWF0ZVdpZGdldCApIHtcblx0XHRcdHJldHVybiBuZXcgY29uc3RydWN0b3IoIG9wdGlvbnMsIGVsZW1lbnQgKTtcblx0XHR9XG5cblx0XHQvLyBBbGxvdyBpbnN0YW50aWF0aW9uIHdpdGhvdXQgaW5pdGlhbGl6aW5nIGZvciBzaW1wbGUgaW5oZXJpdGFuY2Vcblx0XHQvLyBtdXN0IHVzZSBcIm5ld1wiIGtleXdvcmQgKHRoZSBjb2RlIGFib3ZlIGFsd2F5cyBwYXNzZXMgYXJncylcblx0XHRpZiAoIGFyZ3VtZW50cy5sZW5ndGggKSB7XG5cdFx0XHR0aGlzLl9jcmVhdGVXaWRnZXQoIG9wdGlvbnMsIGVsZW1lbnQgKTtcblx0XHR9XG5cdH07XG5cblx0Ly8gRXh0ZW5kIHdpdGggdGhlIGV4aXN0aW5nIGNvbnN0cnVjdG9yIHRvIGNhcnJ5IG92ZXIgYW55IHN0YXRpYyBwcm9wZXJ0aWVzXG5cdCQuZXh0ZW5kKCBjb25zdHJ1Y3RvciwgZXhpc3RpbmdDb25zdHJ1Y3Rvciwge1xuXHRcdHZlcnNpb246IHByb3RvdHlwZS52ZXJzaW9uLFxuXG5cdFx0Ly8gQ29weSB0aGUgb2JqZWN0IHVzZWQgdG8gY3JlYXRlIHRoZSBwcm90b3R5cGUgaW4gY2FzZSB3ZSBuZWVkIHRvXG5cdFx0Ly8gcmVkZWZpbmUgdGhlIHdpZGdldCBsYXRlclxuXHRcdF9wcm90bzogJC5leHRlbmQoIHt9LCBwcm90b3R5cGUgKSxcblxuXHRcdC8vIFRyYWNrIHdpZGdldHMgdGhhdCBpbmhlcml0IGZyb20gdGhpcyB3aWRnZXQgaW4gY2FzZSB0aGlzIHdpZGdldCBpc1xuXHRcdC8vIHJlZGVmaW5lZCBhZnRlciBhIHdpZGdldCBpbmhlcml0cyBmcm9tIGl0XG5cdFx0X2NoaWxkQ29uc3RydWN0b3JzOiBbXVxuXHR9ICk7XG5cblx0YmFzZVByb3RvdHlwZSA9IG5ldyBiYXNlKCk7XG5cblx0Ly8gV2UgbmVlZCB0byBtYWtlIHRoZSBvcHRpb25zIGhhc2ggYSBwcm9wZXJ0eSBkaXJlY3RseSBvbiB0aGUgbmV3IGluc3RhbmNlXG5cdC8vIG90aGVyd2lzZSB3ZSdsbCBtb2RpZnkgdGhlIG9wdGlvbnMgaGFzaCBvbiB0aGUgcHJvdG90eXBlIHRoYXQgd2UncmVcblx0Ly8gaW5oZXJpdGluZyBmcm9tXG5cdGJhc2VQcm90b3R5cGUub3B0aW9ucyA9ICQud2lkZ2V0LmV4dGVuZCgge30sIGJhc2VQcm90b3R5cGUub3B0aW9ucyApO1xuXHQkLmVhY2goIHByb3RvdHlwZSwgZnVuY3Rpb24oIHByb3AsIHZhbHVlICkge1xuXHRcdGlmICggISQuaXNGdW5jdGlvbiggdmFsdWUgKSApIHtcblx0XHRcdHByb3hpZWRQcm90b3R5cGVbIHByb3AgXSA9IHZhbHVlO1xuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblx0XHRwcm94aWVkUHJvdG90eXBlWyBwcm9wIF0gPSAoIGZ1bmN0aW9uKCkge1xuXHRcdFx0ZnVuY3Rpb24gX3N1cGVyKCkge1xuXHRcdFx0XHRyZXR1cm4gYmFzZS5wcm90b3R5cGVbIHByb3AgXS5hcHBseSggdGhpcywgYXJndW1lbnRzICk7XG5cdFx0XHR9XG5cblx0XHRcdGZ1bmN0aW9uIF9zdXBlckFwcGx5KCBhcmdzICkge1xuXHRcdFx0XHRyZXR1cm4gYmFzZS5wcm90b3R5cGVbIHByb3AgXS5hcHBseSggdGhpcywgYXJncyApO1xuXHRcdFx0fVxuXG5cdFx0XHRyZXR1cm4gZnVuY3Rpb24oKSB7XG5cdFx0XHRcdHZhciBfX3N1cGVyID0gdGhpcy5fc3VwZXI7XG5cdFx0XHRcdHZhciBfX3N1cGVyQXBwbHkgPSB0aGlzLl9zdXBlckFwcGx5O1xuXHRcdFx0XHR2YXIgcmV0dXJuVmFsdWU7XG5cblx0XHRcdFx0dGhpcy5fc3VwZXIgPSBfc3VwZXI7XG5cdFx0XHRcdHRoaXMuX3N1cGVyQXBwbHkgPSBfc3VwZXJBcHBseTtcblxuXHRcdFx0XHRyZXR1cm5WYWx1ZSA9IHZhbHVlLmFwcGx5KCB0aGlzLCBhcmd1bWVudHMgKTtcblxuXHRcdFx0XHR0aGlzLl9zdXBlciA9IF9fc3VwZXI7XG5cdFx0XHRcdHRoaXMuX3N1cGVyQXBwbHkgPSBfX3N1cGVyQXBwbHk7XG5cblx0XHRcdFx0cmV0dXJuIHJldHVyblZhbHVlO1xuXHRcdFx0fTtcblx0XHR9ICkoKTtcblx0fSApO1xuXHRjb25zdHJ1Y3Rvci5wcm90b3R5cGUgPSAkLndpZGdldC5leHRlbmQoIGJhc2VQcm90b3R5cGUsIHtcblxuXHRcdC8vIFRPRE86IHJlbW92ZSBzdXBwb3J0IGZvciB3aWRnZXRFdmVudFByZWZpeFxuXHRcdC8vIGFsd2F5cyB1c2UgdGhlIG5hbWUgKyBhIGNvbG9uIGFzIHRoZSBwcmVmaXgsIGUuZy4sIGRyYWdnYWJsZTpzdGFydFxuXHRcdC8vIGRvbid0IHByZWZpeCBmb3Igd2lkZ2V0cyB0aGF0IGFyZW4ndCBET00tYmFzZWRcblx0XHR3aWRnZXRFdmVudFByZWZpeDogZXhpc3RpbmdDb25zdHJ1Y3RvciA/ICggYmFzZVByb3RvdHlwZS53aWRnZXRFdmVudFByZWZpeCB8fCBuYW1lICkgOiBuYW1lXG5cdH0sIHByb3hpZWRQcm90b3R5cGUsIHtcblx0XHRjb25zdHJ1Y3RvcjogY29uc3RydWN0b3IsXG5cdFx0bmFtZXNwYWNlOiBuYW1lc3BhY2UsXG5cdFx0d2lkZ2V0TmFtZTogbmFtZSxcblx0XHR3aWRnZXRGdWxsTmFtZTogZnVsbE5hbWVcblx0fSApO1xuXG5cdC8vIElmIHRoaXMgd2lkZ2V0IGlzIGJlaW5nIHJlZGVmaW5lZCB0aGVuIHdlIG5lZWQgdG8gZmluZCBhbGwgd2lkZ2V0cyB0aGF0XG5cdC8vIGFyZSBpbmhlcml0aW5nIGZyb20gaXQgYW5kIHJlZGVmaW5lIGFsbCBvZiB0aGVtIHNvIHRoYXQgdGhleSBpbmhlcml0IGZyb21cblx0Ly8gdGhlIG5ldyB2ZXJzaW9uIG9mIHRoaXMgd2lkZ2V0LiBXZSdyZSBlc3NlbnRpYWxseSB0cnlpbmcgdG8gcmVwbGFjZSBvbmVcblx0Ly8gbGV2ZWwgaW4gdGhlIHByb3RvdHlwZSBjaGFpbi5cblx0aWYgKCBleGlzdGluZ0NvbnN0cnVjdG9yICkge1xuXHRcdCQuZWFjaCggZXhpc3RpbmdDb25zdHJ1Y3Rvci5fY2hpbGRDb25zdHJ1Y3RvcnMsIGZ1bmN0aW9uKCBpLCBjaGlsZCApIHtcblx0XHRcdHZhciBjaGlsZFByb3RvdHlwZSA9IGNoaWxkLnByb3RvdHlwZTtcblxuXHRcdFx0Ly8gUmVkZWZpbmUgdGhlIGNoaWxkIHdpZGdldCB1c2luZyB0aGUgc2FtZSBwcm90b3R5cGUgdGhhdCB3YXNcblx0XHRcdC8vIG9yaWdpbmFsbHkgdXNlZCwgYnV0IGluaGVyaXQgZnJvbSB0aGUgbmV3IHZlcnNpb24gb2YgdGhlIGJhc2Vcblx0XHRcdCQud2lkZ2V0KCBjaGlsZFByb3RvdHlwZS5uYW1lc3BhY2UgKyBcIi5cIiArIGNoaWxkUHJvdG90eXBlLndpZGdldE5hbWUsIGNvbnN0cnVjdG9yLFxuXHRcdFx0XHRjaGlsZC5fcHJvdG8gKTtcblx0XHR9ICk7XG5cblx0XHQvLyBSZW1vdmUgdGhlIGxpc3Qgb2YgZXhpc3RpbmcgY2hpbGQgY29uc3RydWN0b3JzIGZyb20gdGhlIG9sZCBjb25zdHJ1Y3RvclxuXHRcdC8vIHNvIHRoZSBvbGQgY2hpbGQgY29uc3RydWN0b3JzIGNhbiBiZSBnYXJiYWdlIGNvbGxlY3RlZFxuXHRcdGRlbGV0ZSBleGlzdGluZ0NvbnN0cnVjdG9yLl9jaGlsZENvbnN0cnVjdG9ycztcblx0fSBlbHNlIHtcblx0XHRiYXNlLl9jaGlsZENvbnN0cnVjdG9ycy5wdXNoKCBjb25zdHJ1Y3RvciApO1xuXHR9XG5cblx0JC53aWRnZXQuYnJpZGdlKCBuYW1lLCBjb25zdHJ1Y3RvciApO1xuXG5cdHJldHVybiBjb25zdHJ1Y3Rvcjtcbn07XG5cbiQud2lkZ2V0LmV4dGVuZCA9IGZ1bmN0aW9uKCB0YXJnZXQgKSB7XG5cdHZhciBpbnB1dCA9IHdpZGdldFNsaWNlLmNhbGwoIGFyZ3VtZW50cywgMSApO1xuXHR2YXIgaW5wdXRJbmRleCA9IDA7XG5cdHZhciBpbnB1dExlbmd0aCA9IGlucHV0Lmxlbmd0aDtcblx0dmFyIGtleTtcblx0dmFyIHZhbHVlO1xuXG5cdGZvciAoIDsgaW5wdXRJbmRleCA8IGlucHV0TGVuZ3RoOyBpbnB1dEluZGV4KysgKSB7XG5cdFx0Zm9yICgga2V5IGluIGlucHV0WyBpbnB1dEluZGV4IF0gKSB7XG5cdFx0XHR2YWx1ZSA9IGlucHV0WyBpbnB1dEluZGV4IF1bIGtleSBdO1xuXHRcdFx0aWYgKCBpbnB1dFsgaW5wdXRJbmRleCBdLmhhc093blByb3BlcnR5KCBrZXkgKSAmJiB2YWx1ZSAhPT0gdW5kZWZpbmVkICkge1xuXG5cdFx0XHRcdC8vIENsb25lIG9iamVjdHNcblx0XHRcdFx0aWYgKCAkLmlzUGxhaW5PYmplY3QoIHZhbHVlICkgKSB7XG5cdFx0XHRcdFx0dGFyZ2V0WyBrZXkgXSA9ICQuaXNQbGFpbk9iamVjdCggdGFyZ2V0WyBrZXkgXSApID9cblx0XHRcdFx0XHRcdCQud2lkZ2V0LmV4dGVuZCgge30sIHRhcmdldFsga2V5IF0sIHZhbHVlICkgOlxuXG5cdFx0XHRcdFx0XHQvLyBEb24ndCBleHRlbmQgc3RyaW5ncywgYXJyYXlzLCBldGMuIHdpdGggb2JqZWN0c1xuXHRcdFx0XHRcdFx0JC53aWRnZXQuZXh0ZW5kKCB7fSwgdmFsdWUgKTtcblxuXHRcdFx0XHQvLyBDb3B5IGV2ZXJ5dGhpbmcgZWxzZSBieSByZWZlcmVuY2Vcblx0XHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0XHR0YXJnZXRbIGtleSBdID0gdmFsdWU7XG5cdFx0XHRcdH1cblx0XHRcdH1cblx0XHR9XG5cdH1cblx0cmV0dXJuIHRhcmdldDtcbn07XG5cbiQud2lkZ2V0LmJyaWRnZSA9IGZ1bmN0aW9uKCBuYW1lLCBvYmplY3QgKSB7XG5cdHZhciBmdWxsTmFtZSA9IG9iamVjdC5wcm90b3R5cGUud2lkZ2V0RnVsbE5hbWUgfHwgbmFtZTtcblx0JC5mblsgbmFtZSBdID0gZnVuY3Rpb24oIG9wdGlvbnMgKSB7XG5cdFx0dmFyIGlzTWV0aG9kQ2FsbCA9IHR5cGVvZiBvcHRpb25zID09PSBcInN0cmluZ1wiO1xuXHRcdHZhciBhcmdzID0gd2lkZ2V0U2xpY2UuY2FsbCggYXJndW1lbnRzLCAxICk7XG5cdFx0dmFyIHJldHVyblZhbHVlID0gdGhpcztcblxuXHRcdGlmICggaXNNZXRob2RDYWxsICkge1xuXG5cdFx0XHQvLyBJZiB0aGlzIGlzIGFuIGVtcHR5IGNvbGxlY3Rpb24sIHdlIG5lZWQgdG8gaGF2ZSB0aGUgaW5zdGFuY2UgbWV0aG9kXG5cdFx0XHQvLyByZXR1cm4gdW5kZWZpbmVkIGluc3RlYWQgb2YgdGhlIGpRdWVyeSBpbnN0YW5jZVxuXHRcdFx0aWYgKCAhdGhpcy5sZW5ndGggJiYgb3B0aW9ucyA9PT0gXCJpbnN0YW5jZVwiICkge1xuXHRcdFx0XHRyZXR1cm5WYWx1ZSA9IHVuZGVmaW5lZDtcblx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdHRoaXMuZWFjaCggZnVuY3Rpb24oKSB7XG5cdFx0XHRcdFx0dmFyIG1ldGhvZFZhbHVlO1xuXHRcdFx0XHRcdHZhciBpbnN0YW5jZSA9ICQuZGF0YSggdGhpcywgZnVsbE5hbWUgKTtcblxuXHRcdFx0XHRcdGlmICggb3B0aW9ucyA9PT0gXCJpbnN0YW5jZVwiICkge1xuXHRcdFx0XHRcdFx0cmV0dXJuVmFsdWUgPSBpbnN0YW5jZTtcblx0XHRcdFx0XHRcdHJldHVybiBmYWxzZTtcblx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHRpZiAoICFpbnN0YW5jZSApIHtcblx0XHRcdFx0XHRcdHJldHVybiAkLmVycm9yKCBcImNhbm5vdCBjYWxsIG1ldGhvZHMgb24gXCIgKyBuYW1lICtcblx0XHRcdFx0XHRcdFx0XCIgcHJpb3IgdG8gaW5pdGlhbGl6YXRpb247IFwiICtcblx0XHRcdFx0XHRcdFx0XCJhdHRlbXB0ZWQgdG8gY2FsbCBtZXRob2QgJ1wiICsgb3B0aW9ucyArIFwiJ1wiICk7XG5cdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0aWYgKCAhJC5pc0Z1bmN0aW9uKCBpbnN0YW5jZVsgb3B0aW9ucyBdICkgfHwgb3B0aW9ucy5jaGFyQXQoIDAgKSA9PT0gXCJfXCIgKSB7XG5cdFx0XHRcdFx0XHRyZXR1cm4gJC5lcnJvciggXCJubyBzdWNoIG1ldGhvZCAnXCIgKyBvcHRpb25zICsgXCInIGZvciBcIiArIG5hbWUgK1xuXHRcdFx0XHRcdFx0XHRcIiB3aWRnZXQgaW5zdGFuY2VcIiApO1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdG1ldGhvZFZhbHVlID0gaW5zdGFuY2VbIG9wdGlvbnMgXS5hcHBseSggaW5zdGFuY2UsIGFyZ3MgKTtcblxuXHRcdFx0XHRcdGlmICggbWV0aG9kVmFsdWUgIT09IGluc3RhbmNlICYmIG1ldGhvZFZhbHVlICE9PSB1bmRlZmluZWQgKSB7XG5cdFx0XHRcdFx0XHRyZXR1cm5WYWx1ZSA9IG1ldGhvZFZhbHVlICYmIG1ldGhvZFZhbHVlLmpxdWVyeSA/XG5cdFx0XHRcdFx0XHRcdHJldHVyblZhbHVlLnB1c2hTdGFjayggbWV0aG9kVmFsdWUuZ2V0KCkgKSA6XG5cdFx0XHRcdFx0XHRcdG1ldGhvZFZhbHVlO1xuXHRcdFx0XHRcdFx0cmV0dXJuIGZhbHNlO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0fSApO1xuXHRcdFx0fVxuXHRcdH0gZWxzZSB7XG5cblx0XHRcdC8vIEFsbG93IG11bHRpcGxlIGhhc2hlcyB0byBiZSBwYXNzZWQgb24gaW5pdFxuXHRcdFx0aWYgKCBhcmdzLmxlbmd0aCApIHtcblx0XHRcdFx0b3B0aW9ucyA9ICQud2lkZ2V0LmV4dGVuZC5hcHBseSggbnVsbCwgWyBvcHRpb25zIF0uY29uY2F0KCBhcmdzICkgKTtcblx0XHRcdH1cblxuXHRcdFx0dGhpcy5lYWNoKCBmdW5jdGlvbigpIHtcblx0XHRcdFx0dmFyIGluc3RhbmNlID0gJC5kYXRhKCB0aGlzLCBmdWxsTmFtZSApO1xuXHRcdFx0XHRpZiAoIGluc3RhbmNlICkge1xuXHRcdFx0XHRcdGluc3RhbmNlLm9wdGlvbiggb3B0aW9ucyB8fCB7fSApO1xuXHRcdFx0XHRcdGlmICggaW5zdGFuY2UuX2luaXQgKSB7XG5cdFx0XHRcdFx0XHRpbnN0YW5jZS5faW5pdCgpO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0XHQkLmRhdGEoIHRoaXMsIGZ1bGxOYW1lLCBuZXcgb2JqZWN0KCBvcHRpb25zLCB0aGlzICkgKTtcblx0XHRcdFx0fVxuXHRcdFx0fSApO1xuXHRcdH1cblxuXHRcdHJldHVybiByZXR1cm5WYWx1ZTtcblx0fTtcbn07XG5cbiQuV2lkZ2V0ID0gZnVuY3Rpb24oIC8qIG9wdGlvbnMsIGVsZW1lbnQgKi8gKSB7fTtcbiQuV2lkZ2V0Ll9jaGlsZENvbnN0cnVjdG9ycyA9IFtdO1xuXG4kLldpZGdldC5wcm90b3R5cGUgPSB7XG5cdHdpZGdldE5hbWU6IFwid2lkZ2V0XCIsXG5cdHdpZGdldEV2ZW50UHJlZml4OiBcIlwiLFxuXHRkZWZhdWx0RWxlbWVudDogXCI8ZGl2PlwiLFxuXG5cdG9wdGlvbnM6IHtcblx0XHRjbGFzc2VzOiB7fSxcblx0XHRkaXNhYmxlZDogZmFsc2UsXG5cblx0XHQvLyBDYWxsYmFja3Ncblx0XHRjcmVhdGU6IG51bGxcblx0fSxcblxuXHRfY3JlYXRlV2lkZ2V0OiBmdW5jdGlvbiggb3B0aW9ucywgZWxlbWVudCApIHtcblx0XHRlbGVtZW50ID0gJCggZWxlbWVudCB8fCB0aGlzLmRlZmF1bHRFbGVtZW50IHx8IHRoaXMgKVsgMCBdO1xuXHRcdHRoaXMuZWxlbWVudCA9ICQoIGVsZW1lbnQgKTtcblx0XHR0aGlzLnV1aWQgPSB3aWRnZXRVdWlkKys7XG5cdFx0dGhpcy5ldmVudE5hbWVzcGFjZSA9IFwiLlwiICsgdGhpcy53aWRnZXROYW1lICsgdGhpcy51dWlkO1xuXG5cdFx0dGhpcy5iaW5kaW5ncyA9ICQoKTtcblx0XHR0aGlzLmhvdmVyYWJsZSA9ICQoKTtcblx0XHR0aGlzLmZvY3VzYWJsZSA9ICQoKTtcblx0XHR0aGlzLmNsYXNzZXNFbGVtZW50TG9va3VwID0ge307XG5cblx0XHRpZiAoIGVsZW1lbnQgIT09IHRoaXMgKSB7XG5cdFx0XHQkLmRhdGEoIGVsZW1lbnQsIHRoaXMud2lkZ2V0RnVsbE5hbWUsIHRoaXMgKTtcblx0XHRcdHRoaXMuX29uKCB0cnVlLCB0aGlzLmVsZW1lbnQsIHtcblx0XHRcdFx0cmVtb3ZlOiBmdW5jdGlvbiggZXZlbnQgKSB7XG5cdFx0XHRcdFx0aWYgKCBldmVudC50YXJnZXQgPT09IGVsZW1lbnQgKSB7XG5cdFx0XHRcdFx0XHR0aGlzLmRlc3Ryb3koKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdH1cblx0XHRcdH0gKTtcblx0XHRcdHRoaXMuZG9jdW1lbnQgPSAkKCBlbGVtZW50LnN0eWxlID9cblxuXHRcdFx0XHQvLyBFbGVtZW50IHdpdGhpbiB0aGUgZG9jdW1lbnRcblx0XHRcdFx0ZWxlbWVudC5vd25lckRvY3VtZW50IDpcblxuXHRcdFx0XHQvLyBFbGVtZW50IGlzIHdpbmRvdyBvciBkb2N1bWVudFxuXHRcdFx0XHRlbGVtZW50LmRvY3VtZW50IHx8IGVsZW1lbnQgKTtcblx0XHRcdHRoaXMud2luZG93ID0gJCggdGhpcy5kb2N1bWVudFsgMCBdLmRlZmF1bHRWaWV3IHx8IHRoaXMuZG9jdW1lbnRbIDAgXS5wYXJlbnRXaW5kb3cgKTtcblx0XHR9XG5cblx0XHR0aGlzLm9wdGlvbnMgPSAkLndpZGdldC5leHRlbmQoIHt9LFxuXHRcdFx0dGhpcy5vcHRpb25zLFxuXHRcdFx0dGhpcy5fZ2V0Q3JlYXRlT3B0aW9ucygpLFxuXHRcdFx0b3B0aW9ucyApO1xuXG5cdFx0dGhpcy5fY3JlYXRlKCk7XG5cblx0XHRpZiAoIHRoaXMub3B0aW9ucy5kaXNhYmxlZCApIHtcblx0XHRcdHRoaXMuX3NldE9wdGlvbkRpc2FibGVkKCB0aGlzLm9wdGlvbnMuZGlzYWJsZWQgKTtcblx0XHR9XG5cblx0XHR0aGlzLl90cmlnZ2VyKCBcImNyZWF0ZVwiLCBudWxsLCB0aGlzLl9nZXRDcmVhdGVFdmVudERhdGEoKSApO1xuXHRcdHRoaXMuX2luaXQoKTtcblx0fSxcblxuXHRfZ2V0Q3JlYXRlT3B0aW9uczogZnVuY3Rpb24oKSB7XG5cdFx0cmV0dXJuIHt9O1xuXHR9LFxuXG5cdF9nZXRDcmVhdGVFdmVudERhdGE6ICQubm9vcCxcblxuXHRfY3JlYXRlOiAkLm5vb3AsXG5cblx0X2luaXQ6ICQubm9vcCxcblxuXHRkZXN0cm95OiBmdW5jdGlvbigpIHtcblx0XHR2YXIgdGhhdCA9IHRoaXM7XG5cblx0XHR0aGlzLl9kZXN0cm95KCk7XG5cdFx0JC5lYWNoKCB0aGlzLmNsYXNzZXNFbGVtZW50TG9va3VwLCBmdW5jdGlvbigga2V5LCB2YWx1ZSApIHtcblx0XHRcdHRoYXQuX3JlbW92ZUNsYXNzKCB2YWx1ZSwga2V5ICk7XG5cdFx0fSApO1xuXG5cdFx0Ly8gV2UgY2FuIHByb2JhYmx5IHJlbW92ZSB0aGUgdW5iaW5kIGNhbGxzIGluIDIuMFxuXHRcdC8vIGFsbCBldmVudCBiaW5kaW5ncyBzaG91bGQgZ28gdGhyb3VnaCB0aGlzLl9vbigpXG5cdFx0dGhpcy5lbGVtZW50XG5cdFx0XHQub2ZmKCB0aGlzLmV2ZW50TmFtZXNwYWNlIClcblx0XHRcdC5yZW1vdmVEYXRhKCB0aGlzLndpZGdldEZ1bGxOYW1lICk7XG5cdFx0dGhpcy53aWRnZXQoKVxuXHRcdFx0Lm9mZiggdGhpcy5ldmVudE5hbWVzcGFjZSApXG5cdFx0XHQucmVtb3ZlQXR0ciggXCJhcmlhLWRpc2FibGVkXCIgKTtcblxuXHRcdC8vIENsZWFuIHVwIGV2ZW50cyBhbmQgc3RhdGVzXG5cdFx0dGhpcy5iaW5kaW5ncy5vZmYoIHRoaXMuZXZlbnROYW1lc3BhY2UgKTtcblx0fSxcblxuXHRfZGVzdHJveTogJC5ub29wLFxuXG5cdHdpZGdldDogZnVuY3Rpb24oKSB7XG5cdFx0cmV0dXJuIHRoaXMuZWxlbWVudDtcblx0fSxcblxuXHRvcHRpb246IGZ1bmN0aW9uKCBrZXksIHZhbHVlICkge1xuXHRcdHZhciBvcHRpb25zID0ga2V5O1xuXHRcdHZhciBwYXJ0cztcblx0XHR2YXIgY3VyT3B0aW9uO1xuXHRcdHZhciBpO1xuXG5cdFx0aWYgKCBhcmd1bWVudHMubGVuZ3RoID09PSAwICkge1xuXG5cdFx0XHQvLyBEb24ndCByZXR1cm4gYSByZWZlcmVuY2UgdG8gdGhlIGludGVybmFsIGhhc2hcblx0XHRcdHJldHVybiAkLndpZGdldC5leHRlbmQoIHt9LCB0aGlzLm9wdGlvbnMgKTtcblx0XHR9XG5cblx0XHRpZiAoIHR5cGVvZiBrZXkgPT09IFwic3RyaW5nXCIgKSB7XG5cblx0XHRcdC8vIEhhbmRsZSBuZXN0ZWQga2V5cywgZS5nLiwgXCJmb28uYmFyXCIgPT4geyBmb286IHsgYmFyOiBfX18gfSB9XG5cdFx0XHRvcHRpb25zID0ge307XG5cdFx0XHRwYXJ0cyA9IGtleS5zcGxpdCggXCIuXCIgKTtcblx0XHRcdGtleSA9IHBhcnRzLnNoaWZ0KCk7XG5cdFx0XHRpZiAoIHBhcnRzLmxlbmd0aCApIHtcblx0XHRcdFx0Y3VyT3B0aW9uID0gb3B0aW9uc1sga2V5IF0gPSAkLndpZGdldC5leHRlbmQoIHt9LCB0aGlzLm9wdGlvbnNbIGtleSBdICk7XG5cdFx0XHRcdGZvciAoIGkgPSAwOyBpIDwgcGFydHMubGVuZ3RoIC0gMTsgaSsrICkge1xuXHRcdFx0XHRcdGN1ck9wdGlvblsgcGFydHNbIGkgXSBdID0gY3VyT3B0aW9uWyBwYXJ0c1sgaSBdIF0gfHwge307XG5cdFx0XHRcdFx0Y3VyT3B0aW9uID0gY3VyT3B0aW9uWyBwYXJ0c1sgaSBdIF07XG5cdFx0XHRcdH1cblx0XHRcdFx0a2V5ID0gcGFydHMucG9wKCk7XG5cdFx0XHRcdGlmICggYXJndW1lbnRzLmxlbmd0aCA9PT0gMSApIHtcblx0XHRcdFx0XHRyZXR1cm4gY3VyT3B0aW9uWyBrZXkgXSA9PT0gdW5kZWZpbmVkID8gbnVsbCA6IGN1ck9wdGlvblsga2V5IF07XG5cdFx0XHRcdH1cblx0XHRcdFx0Y3VyT3B0aW9uWyBrZXkgXSA9IHZhbHVlO1xuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0aWYgKCBhcmd1bWVudHMubGVuZ3RoID09PSAxICkge1xuXHRcdFx0XHRcdHJldHVybiB0aGlzLm9wdGlvbnNbIGtleSBdID09PSB1bmRlZmluZWQgPyBudWxsIDogdGhpcy5vcHRpb25zWyBrZXkgXTtcblx0XHRcdFx0fVxuXHRcdFx0XHRvcHRpb25zWyBrZXkgXSA9IHZhbHVlO1xuXHRcdFx0fVxuXHRcdH1cblxuXHRcdHRoaXMuX3NldE9wdGlvbnMoIG9wdGlvbnMgKTtcblxuXHRcdHJldHVybiB0aGlzO1xuXHR9LFxuXG5cdF9zZXRPcHRpb25zOiBmdW5jdGlvbiggb3B0aW9ucyApIHtcblx0XHR2YXIga2V5O1xuXG5cdFx0Zm9yICgga2V5IGluIG9wdGlvbnMgKSB7XG5cdFx0XHR0aGlzLl9zZXRPcHRpb24oIGtleSwgb3B0aW9uc1sga2V5IF0gKTtcblx0XHR9XG5cblx0XHRyZXR1cm4gdGhpcztcblx0fSxcblxuXHRfc2V0T3B0aW9uOiBmdW5jdGlvbigga2V5LCB2YWx1ZSApIHtcblx0XHRpZiAoIGtleSA9PT0gXCJjbGFzc2VzXCIgKSB7XG5cdFx0XHR0aGlzLl9zZXRPcHRpb25DbGFzc2VzKCB2YWx1ZSApO1xuXHRcdH1cblxuXHRcdHRoaXMub3B0aW9uc1sga2V5IF0gPSB2YWx1ZTtcblxuXHRcdGlmICgga2V5ID09PSBcImRpc2FibGVkXCIgKSB7XG5cdFx0XHR0aGlzLl9zZXRPcHRpb25EaXNhYmxlZCggdmFsdWUgKTtcblx0XHR9XG5cblx0XHRyZXR1cm4gdGhpcztcblx0fSxcblxuXHRfc2V0T3B0aW9uQ2xhc3NlczogZnVuY3Rpb24oIHZhbHVlICkge1xuXHRcdHZhciBjbGFzc0tleSwgZWxlbWVudHMsIGN1cnJlbnRFbGVtZW50cztcblxuXHRcdGZvciAoIGNsYXNzS2V5IGluIHZhbHVlICkge1xuXHRcdFx0Y3VycmVudEVsZW1lbnRzID0gdGhpcy5jbGFzc2VzRWxlbWVudExvb2t1cFsgY2xhc3NLZXkgXTtcblx0XHRcdGlmICggdmFsdWVbIGNsYXNzS2V5IF0gPT09IHRoaXMub3B0aW9ucy5jbGFzc2VzWyBjbGFzc0tleSBdIHx8XG5cdFx0XHRcdFx0IWN1cnJlbnRFbGVtZW50cyB8fFxuXHRcdFx0XHRcdCFjdXJyZW50RWxlbWVudHMubGVuZ3RoICkge1xuXHRcdFx0XHRjb250aW51ZTtcblx0XHRcdH1cblxuXHRcdFx0Ly8gV2UgYXJlIGRvaW5nIHRoaXMgdG8gY3JlYXRlIGEgbmV3IGpRdWVyeSBvYmplY3QgYmVjYXVzZSB0aGUgX3JlbW92ZUNsYXNzKCkgY2FsbFxuXHRcdFx0Ly8gb24gdGhlIG5leHQgbGluZSBpcyBnb2luZyB0byBkZXN0cm95IHRoZSByZWZlcmVuY2UgdG8gdGhlIGN1cnJlbnQgZWxlbWVudHMgYmVpbmdcblx0XHRcdC8vIHRyYWNrZWQuIFdlIG5lZWQgdG8gc2F2ZSBhIGNvcHkgb2YgdGhpcyBjb2xsZWN0aW9uIHNvIHRoYXQgd2UgY2FuIGFkZCB0aGUgbmV3IGNsYXNzZXNcblx0XHRcdC8vIGJlbG93LlxuXHRcdFx0ZWxlbWVudHMgPSAkKCBjdXJyZW50RWxlbWVudHMuZ2V0KCkgKTtcblx0XHRcdHRoaXMuX3JlbW92ZUNsYXNzKCBjdXJyZW50RWxlbWVudHMsIGNsYXNzS2V5ICk7XG5cblx0XHRcdC8vIFdlIGRvbid0IHVzZSBfYWRkQ2xhc3MoKSBoZXJlLCBiZWNhdXNlIHRoYXQgdXNlcyB0aGlzLm9wdGlvbnMuY2xhc3Nlc1xuXHRcdFx0Ly8gZm9yIGdlbmVyYXRpbmcgdGhlIHN0cmluZyBvZiBjbGFzc2VzLiBXZSB3YW50IHRvIHVzZSB0aGUgdmFsdWUgcGFzc2VkIGluIGZyb21cblx0XHRcdC8vIF9zZXRPcHRpb24oKSwgdGhpcyBpcyB0aGUgbmV3IHZhbHVlIG9mIHRoZSBjbGFzc2VzIG9wdGlvbiB3aGljaCB3YXMgcGFzc2VkIHRvXG5cdFx0XHQvLyBfc2V0T3B0aW9uKCkuIFdlIHBhc3MgdGhpcyB2YWx1ZSBkaXJlY3RseSB0byBfY2xhc3NlcygpLlxuXHRcdFx0ZWxlbWVudHMuYWRkQ2xhc3MoIHRoaXMuX2NsYXNzZXMoIHtcblx0XHRcdFx0ZWxlbWVudDogZWxlbWVudHMsXG5cdFx0XHRcdGtleXM6IGNsYXNzS2V5LFxuXHRcdFx0XHRjbGFzc2VzOiB2YWx1ZSxcblx0XHRcdFx0YWRkOiB0cnVlXG5cdFx0XHR9ICkgKTtcblx0XHR9XG5cdH0sXG5cblx0X3NldE9wdGlvbkRpc2FibGVkOiBmdW5jdGlvbiggdmFsdWUgKSB7XG5cdFx0dGhpcy5fdG9nZ2xlQ2xhc3MoIHRoaXMud2lkZ2V0KCksIHRoaXMud2lkZ2V0RnVsbE5hbWUgKyBcIi1kaXNhYmxlZFwiLCBudWxsLCAhIXZhbHVlICk7XG5cblx0XHQvLyBJZiB0aGUgd2lkZ2V0IGlzIGJlY29taW5nIGRpc2FibGVkLCB0aGVuIG5vdGhpbmcgaXMgaW50ZXJhY3RpdmVcblx0XHRpZiAoIHZhbHVlICkge1xuXHRcdFx0dGhpcy5fcmVtb3ZlQ2xhc3MoIHRoaXMuaG92ZXJhYmxlLCBudWxsLCBcInVpLXN0YXRlLWhvdmVyXCIgKTtcblx0XHRcdHRoaXMuX3JlbW92ZUNsYXNzKCB0aGlzLmZvY3VzYWJsZSwgbnVsbCwgXCJ1aS1zdGF0ZS1mb2N1c1wiICk7XG5cdFx0fVxuXHR9LFxuXG5cdGVuYWJsZTogZnVuY3Rpb24oKSB7XG5cdFx0cmV0dXJuIHRoaXMuX3NldE9wdGlvbnMoIHsgZGlzYWJsZWQ6IGZhbHNlIH0gKTtcblx0fSxcblxuXHRkaXNhYmxlOiBmdW5jdGlvbigpIHtcblx0XHRyZXR1cm4gdGhpcy5fc2V0T3B0aW9ucyggeyBkaXNhYmxlZDogdHJ1ZSB9ICk7XG5cdH0sXG5cblx0X2NsYXNzZXM6IGZ1bmN0aW9uKCBvcHRpb25zICkge1xuXHRcdHZhciBmdWxsID0gW107XG5cdFx0dmFyIHRoYXQgPSB0aGlzO1xuXG5cdFx0b3B0aW9ucyA9ICQuZXh0ZW5kKCB7XG5cdFx0XHRlbGVtZW50OiB0aGlzLmVsZW1lbnQsXG5cdFx0XHRjbGFzc2VzOiB0aGlzLm9wdGlvbnMuY2xhc3NlcyB8fCB7fVxuXHRcdH0sIG9wdGlvbnMgKTtcblxuXHRcdGZ1bmN0aW9uIHByb2Nlc3NDbGFzc1N0cmluZyggY2xhc3NlcywgY2hlY2tPcHRpb24gKSB7XG5cdFx0XHR2YXIgY3VycmVudCwgaTtcblx0XHRcdGZvciAoIGkgPSAwOyBpIDwgY2xhc3Nlcy5sZW5ndGg7IGkrKyApIHtcblx0XHRcdFx0Y3VycmVudCA9IHRoYXQuY2xhc3Nlc0VsZW1lbnRMb29rdXBbIGNsYXNzZXNbIGkgXSBdIHx8ICQoKTtcblx0XHRcdFx0aWYgKCBvcHRpb25zLmFkZCApIHtcblx0XHRcdFx0XHRjdXJyZW50ID0gJCggJC51bmlxdWUoIGN1cnJlbnQuZ2V0KCkuY29uY2F0KCBvcHRpb25zLmVsZW1lbnQuZ2V0KCkgKSApICk7XG5cdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0Y3VycmVudCA9ICQoIGN1cnJlbnQubm90KCBvcHRpb25zLmVsZW1lbnQgKS5nZXQoKSApO1xuXHRcdFx0XHR9XG5cdFx0XHRcdHRoYXQuY2xhc3Nlc0VsZW1lbnRMb29rdXBbIGNsYXNzZXNbIGkgXSBdID0gY3VycmVudDtcblx0XHRcdFx0ZnVsbC5wdXNoKCBjbGFzc2VzWyBpIF0gKTtcblx0XHRcdFx0aWYgKCBjaGVja09wdGlvbiAmJiBvcHRpb25zLmNsYXNzZXNbIGNsYXNzZXNbIGkgXSBdICkge1xuXHRcdFx0XHRcdGZ1bGwucHVzaCggb3B0aW9ucy5jbGFzc2VzWyBjbGFzc2VzWyBpIF0gXSApO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0fVxuXG5cdFx0dGhpcy5fb24oIG9wdGlvbnMuZWxlbWVudCwge1xuXHRcdFx0XCJyZW1vdmVcIjogXCJfdW50cmFja0NsYXNzZXNFbGVtZW50XCJcblx0XHR9ICk7XG5cblx0XHRpZiAoIG9wdGlvbnMua2V5cyApIHtcblx0XHRcdHByb2Nlc3NDbGFzc1N0cmluZyggb3B0aW9ucy5rZXlzLm1hdGNoKCAvXFxTKy9nICkgfHwgW10sIHRydWUgKTtcblx0XHR9XG5cdFx0aWYgKCBvcHRpb25zLmV4dHJhICkge1xuXHRcdFx0cHJvY2Vzc0NsYXNzU3RyaW5nKCBvcHRpb25zLmV4dHJhLm1hdGNoKCAvXFxTKy9nICkgfHwgW10gKTtcblx0XHR9XG5cblx0XHRyZXR1cm4gZnVsbC5qb2luKCBcIiBcIiApO1xuXHR9LFxuXG5cdF91bnRyYWNrQ2xhc3Nlc0VsZW1lbnQ6IGZ1bmN0aW9uKCBldmVudCApIHtcblx0XHR2YXIgdGhhdCA9IHRoaXM7XG5cdFx0JC5lYWNoKCB0aGF0LmNsYXNzZXNFbGVtZW50TG9va3VwLCBmdW5jdGlvbigga2V5LCB2YWx1ZSApIHtcblx0XHRcdGlmICggJC5pbkFycmF5KCBldmVudC50YXJnZXQsIHZhbHVlICkgIT09IC0xICkge1xuXHRcdFx0XHR0aGF0LmNsYXNzZXNFbGVtZW50TG9va3VwWyBrZXkgXSA9ICQoIHZhbHVlLm5vdCggZXZlbnQudGFyZ2V0ICkuZ2V0KCkgKTtcblx0XHRcdH1cblx0XHR9ICk7XG5cdH0sXG5cblx0X3JlbW92ZUNsYXNzOiBmdW5jdGlvbiggZWxlbWVudCwga2V5cywgZXh0cmEgKSB7XG5cdFx0cmV0dXJuIHRoaXMuX3RvZ2dsZUNsYXNzKCBlbGVtZW50LCBrZXlzLCBleHRyYSwgZmFsc2UgKTtcblx0fSxcblxuXHRfYWRkQ2xhc3M6IGZ1bmN0aW9uKCBlbGVtZW50LCBrZXlzLCBleHRyYSApIHtcblx0XHRyZXR1cm4gdGhpcy5fdG9nZ2xlQ2xhc3MoIGVsZW1lbnQsIGtleXMsIGV4dHJhLCB0cnVlICk7XG5cdH0sXG5cblx0X3RvZ2dsZUNsYXNzOiBmdW5jdGlvbiggZWxlbWVudCwga2V5cywgZXh0cmEsIGFkZCApIHtcblx0XHRhZGQgPSAoIHR5cGVvZiBhZGQgPT09IFwiYm9vbGVhblwiICkgPyBhZGQgOiBleHRyYTtcblx0XHR2YXIgc2hpZnQgPSAoIHR5cGVvZiBlbGVtZW50ID09PSBcInN0cmluZ1wiIHx8IGVsZW1lbnQgPT09IG51bGwgKSxcblx0XHRcdG9wdGlvbnMgPSB7XG5cdFx0XHRcdGV4dHJhOiBzaGlmdCA/IGtleXMgOiBleHRyYSxcblx0XHRcdFx0a2V5czogc2hpZnQgPyBlbGVtZW50IDoga2V5cyxcblx0XHRcdFx0ZWxlbWVudDogc2hpZnQgPyB0aGlzLmVsZW1lbnQgOiBlbGVtZW50LFxuXHRcdFx0XHRhZGQ6IGFkZFxuXHRcdFx0fTtcblx0XHRvcHRpb25zLmVsZW1lbnQudG9nZ2xlQ2xhc3MoIHRoaXMuX2NsYXNzZXMoIG9wdGlvbnMgKSwgYWRkICk7XG5cdFx0cmV0dXJuIHRoaXM7XG5cdH0sXG5cblx0X29uOiBmdW5jdGlvbiggc3VwcHJlc3NEaXNhYmxlZENoZWNrLCBlbGVtZW50LCBoYW5kbGVycyApIHtcblx0XHR2YXIgZGVsZWdhdGVFbGVtZW50O1xuXHRcdHZhciBpbnN0YW5jZSA9IHRoaXM7XG5cblx0XHQvLyBObyBzdXBwcmVzc0Rpc2FibGVkQ2hlY2sgZmxhZywgc2h1ZmZsZSBhcmd1bWVudHNcblx0XHRpZiAoIHR5cGVvZiBzdXBwcmVzc0Rpc2FibGVkQ2hlY2sgIT09IFwiYm9vbGVhblwiICkge1xuXHRcdFx0aGFuZGxlcnMgPSBlbGVtZW50O1xuXHRcdFx0ZWxlbWVudCA9IHN1cHByZXNzRGlzYWJsZWRDaGVjaztcblx0XHRcdHN1cHByZXNzRGlzYWJsZWRDaGVjayA9IGZhbHNlO1xuXHRcdH1cblxuXHRcdC8vIE5vIGVsZW1lbnQgYXJndW1lbnQsIHNodWZmbGUgYW5kIHVzZSB0aGlzLmVsZW1lbnRcblx0XHRpZiAoICFoYW5kbGVycyApIHtcblx0XHRcdGhhbmRsZXJzID0gZWxlbWVudDtcblx0XHRcdGVsZW1lbnQgPSB0aGlzLmVsZW1lbnQ7XG5cdFx0XHRkZWxlZ2F0ZUVsZW1lbnQgPSB0aGlzLndpZGdldCgpO1xuXHRcdH0gZWxzZSB7XG5cdFx0XHRlbGVtZW50ID0gZGVsZWdhdGVFbGVtZW50ID0gJCggZWxlbWVudCApO1xuXHRcdFx0dGhpcy5iaW5kaW5ncyA9IHRoaXMuYmluZGluZ3MuYWRkKCBlbGVtZW50ICk7XG5cdFx0fVxuXG5cdFx0JC5lYWNoKCBoYW5kbGVycywgZnVuY3Rpb24oIGV2ZW50LCBoYW5kbGVyICkge1xuXHRcdFx0ZnVuY3Rpb24gaGFuZGxlclByb3h5KCkge1xuXG5cdFx0XHRcdC8vIEFsbG93IHdpZGdldHMgdG8gY3VzdG9taXplIHRoZSBkaXNhYmxlZCBoYW5kbGluZ1xuXHRcdFx0XHQvLyAtIGRpc2FibGVkIGFzIGFuIGFycmF5IGluc3RlYWQgb2YgYm9vbGVhblxuXHRcdFx0XHQvLyAtIGRpc2FibGVkIGNsYXNzIGFzIG1ldGhvZCBmb3IgZGlzYWJsaW5nIGluZGl2aWR1YWwgcGFydHNcblx0XHRcdFx0aWYgKCAhc3VwcHJlc3NEaXNhYmxlZENoZWNrICYmXG5cdFx0XHRcdFx0XHQoIGluc3RhbmNlLm9wdGlvbnMuZGlzYWJsZWQgPT09IHRydWUgfHxcblx0XHRcdFx0XHRcdCQoIHRoaXMgKS5oYXNDbGFzcyggXCJ1aS1zdGF0ZS1kaXNhYmxlZFwiICkgKSApIHtcblx0XHRcdFx0XHRyZXR1cm47XG5cdFx0XHRcdH1cblx0XHRcdFx0cmV0dXJuICggdHlwZW9mIGhhbmRsZXIgPT09IFwic3RyaW5nXCIgPyBpbnN0YW5jZVsgaGFuZGxlciBdIDogaGFuZGxlciApXG5cdFx0XHRcdFx0LmFwcGx5KCBpbnN0YW5jZSwgYXJndW1lbnRzICk7XG5cdFx0XHR9XG5cblx0XHRcdC8vIENvcHkgdGhlIGd1aWQgc28gZGlyZWN0IHVuYmluZGluZyB3b3Jrc1xuXHRcdFx0aWYgKCB0eXBlb2YgaGFuZGxlciAhPT0gXCJzdHJpbmdcIiApIHtcblx0XHRcdFx0aGFuZGxlclByb3h5Lmd1aWQgPSBoYW5kbGVyLmd1aWQgPVxuXHRcdFx0XHRcdGhhbmRsZXIuZ3VpZCB8fCBoYW5kbGVyUHJveHkuZ3VpZCB8fCAkLmd1aWQrKztcblx0XHRcdH1cblxuXHRcdFx0dmFyIG1hdGNoID0gZXZlbnQubWF0Y2goIC9eKFtcXHc6LV0qKVxccyooLiopJC8gKTtcblx0XHRcdHZhciBldmVudE5hbWUgPSBtYXRjaFsgMSBdICsgaW5zdGFuY2UuZXZlbnROYW1lc3BhY2U7XG5cdFx0XHR2YXIgc2VsZWN0b3IgPSBtYXRjaFsgMiBdO1xuXG5cdFx0XHRpZiAoIHNlbGVjdG9yICkge1xuXHRcdFx0XHRkZWxlZ2F0ZUVsZW1lbnQub24oIGV2ZW50TmFtZSwgc2VsZWN0b3IsIGhhbmRsZXJQcm94eSApO1xuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0ZWxlbWVudC5vbiggZXZlbnROYW1lLCBoYW5kbGVyUHJveHkgKTtcblx0XHRcdH1cblx0XHR9ICk7XG5cdH0sXG5cblx0X29mZjogZnVuY3Rpb24oIGVsZW1lbnQsIGV2ZW50TmFtZSApIHtcblx0XHRldmVudE5hbWUgPSAoIGV2ZW50TmFtZSB8fCBcIlwiICkuc3BsaXQoIFwiIFwiICkuam9pbiggdGhpcy5ldmVudE5hbWVzcGFjZSArIFwiIFwiICkgK1xuXHRcdFx0dGhpcy5ldmVudE5hbWVzcGFjZTtcblx0XHRlbGVtZW50Lm9mZiggZXZlbnROYW1lICkub2ZmKCBldmVudE5hbWUgKTtcblxuXHRcdC8vIENsZWFyIHRoZSBzdGFjayB0byBhdm9pZCBtZW1vcnkgbGVha3MgKCMxMDA1Nilcblx0XHR0aGlzLmJpbmRpbmdzID0gJCggdGhpcy5iaW5kaW5ncy5ub3QoIGVsZW1lbnQgKS5nZXQoKSApO1xuXHRcdHRoaXMuZm9jdXNhYmxlID0gJCggdGhpcy5mb2N1c2FibGUubm90KCBlbGVtZW50ICkuZ2V0KCkgKTtcblx0XHR0aGlzLmhvdmVyYWJsZSA9ICQoIHRoaXMuaG92ZXJhYmxlLm5vdCggZWxlbWVudCApLmdldCgpICk7XG5cdH0sXG5cblx0X2RlbGF5OiBmdW5jdGlvbiggaGFuZGxlciwgZGVsYXkgKSB7XG5cdFx0ZnVuY3Rpb24gaGFuZGxlclByb3h5KCkge1xuXHRcdFx0cmV0dXJuICggdHlwZW9mIGhhbmRsZXIgPT09IFwic3RyaW5nXCIgPyBpbnN0YW5jZVsgaGFuZGxlciBdIDogaGFuZGxlciApXG5cdFx0XHRcdC5hcHBseSggaW5zdGFuY2UsIGFyZ3VtZW50cyApO1xuXHRcdH1cblx0XHR2YXIgaW5zdGFuY2UgPSB0aGlzO1xuXHRcdHJldHVybiBzZXRUaW1lb3V0KCBoYW5kbGVyUHJveHksIGRlbGF5IHx8IDAgKTtcblx0fSxcblxuXHRfaG92ZXJhYmxlOiBmdW5jdGlvbiggZWxlbWVudCApIHtcblx0XHR0aGlzLmhvdmVyYWJsZSA9IHRoaXMuaG92ZXJhYmxlLmFkZCggZWxlbWVudCApO1xuXHRcdHRoaXMuX29uKCBlbGVtZW50LCB7XG5cdFx0XHRtb3VzZWVudGVyOiBmdW5jdGlvbiggZXZlbnQgKSB7XG5cdFx0XHRcdHRoaXMuX2FkZENsYXNzKCAkKCBldmVudC5jdXJyZW50VGFyZ2V0ICksIG51bGwsIFwidWktc3RhdGUtaG92ZXJcIiApO1xuXHRcdFx0fSxcblx0XHRcdG1vdXNlbGVhdmU6IGZ1bmN0aW9uKCBldmVudCApIHtcblx0XHRcdFx0dGhpcy5fcmVtb3ZlQ2xhc3MoICQoIGV2ZW50LmN1cnJlbnRUYXJnZXQgKSwgbnVsbCwgXCJ1aS1zdGF0ZS1ob3ZlclwiICk7XG5cdFx0XHR9XG5cdFx0fSApO1xuXHR9LFxuXG5cdF9mb2N1c2FibGU6IGZ1bmN0aW9uKCBlbGVtZW50ICkge1xuXHRcdHRoaXMuZm9jdXNhYmxlID0gdGhpcy5mb2N1c2FibGUuYWRkKCBlbGVtZW50ICk7XG5cdFx0dGhpcy5fb24oIGVsZW1lbnQsIHtcblx0XHRcdGZvY3VzaW46IGZ1bmN0aW9uKCBldmVudCApIHtcblx0XHRcdFx0dGhpcy5fYWRkQ2xhc3MoICQoIGV2ZW50LmN1cnJlbnRUYXJnZXQgKSwgbnVsbCwgXCJ1aS1zdGF0ZS1mb2N1c1wiICk7XG5cdFx0XHR9LFxuXHRcdFx0Zm9jdXNvdXQ6IGZ1bmN0aW9uKCBldmVudCApIHtcblx0XHRcdFx0dGhpcy5fcmVtb3ZlQ2xhc3MoICQoIGV2ZW50LmN1cnJlbnRUYXJnZXQgKSwgbnVsbCwgXCJ1aS1zdGF0ZS1mb2N1c1wiICk7XG5cdFx0XHR9XG5cdFx0fSApO1xuXHR9LFxuXG5cdF90cmlnZ2VyOiBmdW5jdGlvbiggdHlwZSwgZXZlbnQsIGRhdGEgKSB7XG5cdFx0dmFyIHByb3AsIG9yaWc7XG5cdFx0dmFyIGNhbGxiYWNrID0gdGhpcy5vcHRpb25zWyB0eXBlIF07XG5cblx0XHRkYXRhID0gZGF0YSB8fCB7fTtcblx0XHRldmVudCA9ICQuRXZlbnQoIGV2ZW50ICk7XG5cdFx0ZXZlbnQudHlwZSA9ICggdHlwZSA9PT0gdGhpcy53aWRnZXRFdmVudFByZWZpeCA/XG5cdFx0XHR0eXBlIDpcblx0XHRcdHRoaXMud2lkZ2V0RXZlbnRQcmVmaXggKyB0eXBlICkudG9Mb3dlckNhc2UoKTtcblxuXHRcdC8vIFRoZSBvcmlnaW5hbCBldmVudCBtYXkgY29tZSBmcm9tIGFueSBlbGVtZW50XG5cdFx0Ly8gc28gd2UgbmVlZCB0byByZXNldCB0aGUgdGFyZ2V0IG9uIHRoZSBuZXcgZXZlbnRcblx0XHRldmVudC50YXJnZXQgPSB0aGlzLmVsZW1lbnRbIDAgXTtcblxuXHRcdC8vIENvcHkgb3JpZ2luYWwgZXZlbnQgcHJvcGVydGllcyBvdmVyIHRvIHRoZSBuZXcgZXZlbnRcblx0XHRvcmlnID0gZXZlbnQub3JpZ2luYWxFdmVudDtcblx0XHRpZiAoIG9yaWcgKSB7XG5cdFx0XHRmb3IgKCBwcm9wIGluIG9yaWcgKSB7XG5cdFx0XHRcdGlmICggISggcHJvcCBpbiBldmVudCApICkge1xuXHRcdFx0XHRcdGV2ZW50WyBwcm9wIF0gPSBvcmlnWyBwcm9wIF07XG5cdFx0XHRcdH1cblx0XHRcdH1cblx0XHR9XG5cblx0XHR0aGlzLmVsZW1lbnQudHJpZ2dlciggZXZlbnQsIGRhdGEgKTtcblx0XHRyZXR1cm4gISggJC5pc0Z1bmN0aW9uKCBjYWxsYmFjayApICYmXG5cdFx0XHRjYWxsYmFjay5hcHBseSggdGhpcy5lbGVtZW50WyAwIF0sIFsgZXZlbnQgXS5jb25jYXQoIGRhdGEgKSApID09PSBmYWxzZSB8fFxuXHRcdFx0ZXZlbnQuaXNEZWZhdWx0UHJldmVudGVkKCkgKTtcblx0fVxufTtcblxuJC5lYWNoKCB7IHNob3c6IFwiZmFkZUluXCIsIGhpZGU6IFwiZmFkZU91dFwiIH0sIGZ1bmN0aW9uKCBtZXRob2QsIGRlZmF1bHRFZmZlY3QgKSB7XG5cdCQuV2lkZ2V0LnByb3RvdHlwZVsgXCJfXCIgKyBtZXRob2QgXSA9IGZ1bmN0aW9uKCBlbGVtZW50LCBvcHRpb25zLCBjYWxsYmFjayApIHtcblx0XHRpZiAoIHR5cGVvZiBvcHRpb25zID09PSBcInN0cmluZ1wiICkge1xuXHRcdFx0b3B0aW9ucyA9IHsgZWZmZWN0OiBvcHRpb25zIH07XG5cdFx0fVxuXG5cdFx0dmFyIGhhc09wdGlvbnM7XG5cdFx0dmFyIGVmZmVjdE5hbWUgPSAhb3B0aW9ucyA/XG5cdFx0XHRtZXRob2QgOlxuXHRcdFx0b3B0aW9ucyA9PT0gdHJ1ZSB8fCB0eXBlb2Ygb3B0aW9ucyA9PT0gXCJudW1iZXJcIiA/XG5cdFx0XHRcdGRlZmF1bHRFZmZlY3QgOlxuXHRcdFx0XHRvcHRpb25zLmVmZmVjdCB8fCBkZWZhdWx0RWZmZWN0O1xuXG5cdFx0b3B0aW9ucyA9IG9wdGlvbnMgfHwge307XG5cdFx0aWYgKCB0eXBlb2Ygb3B0aW9ucyA9PT0gXCJudW1iZXJcIiApIHtcblx0XHRcdG9wdGlvbnMgPSB7IGR1cmF0aW9uOiBvcHRpb25zIH07XG5cdFx0fVxuXG5cdFx0aGFzT3B0aW9ucyA9ICEkLmlzRW1wdHlPYmplY3QoIG9wdGlvbnMgKTtcblx0XHRvcHRpb25zLmNvbXBsZXRlID0gY2FsbGJhY2s7XG5cblx0XHRpZiAoIG9wdGlvbnMuZGVsYXkgKSB7XG5cdFx0XHRlbGVtZW50LmRlbGF5KCBvcHRpb25zLmRlbGF5ICk7XG5cdFx0fVxuXG5cdFx0aWYgKCBoYXNPcHRpb25zICYmICQuZWZmZWN0cyAmJiAkLmVmZmVjdHMuZWZmZWN0WyBlZmZlY3ROYW1lIF0gKSB7XG5cdFx0XHRlbGVtZW50WyBtZXRob2QgXSggb3B0aW9ucyApO1xuXHRcdH0gZWxzZSBpZiAoIGVmZmVjdE5hbWUgIT09IG1ldGhvZCAmJiBlbGVtZW50WyBlZmZlY3ROYW1lIF0gKSB7XG5cdFx0XHRlbGVtZW50WyBlZmZlY3ROYW1lIF0oIG9wdGlvbnMuZHVyYXRpb24sIG9wdGlvbnMuZWFzaW5nLCBjYWxsYmFjayApO1xuXHRcdH0gZWxzZSB7XG5cdFx0XHRlbGVtZW50LnF1ZXVlKCBmdW5jdGlvbiggbmV4dCApIHtcblx0XHRcdFx0JCggdGhpcyApWyBtZXRob2QgXSgpO1xuXHRcdFx0XHRpZiAoIGNhbGxiYWNrICkge1xuXHRcdFx0XHRcdGNhbGxiYWNrLmNhbGwoIGVsZW1lbnRbIDAgXSApO1xuXHRcdFx0XHR9XG5cdFx0XHRcdG5leHQoKTtcblx0XHRcdH0gKTtcblx0XHR9XG5cdH07XG59ICk7XG5cbnJldHVybiAkLndpZGdldDtcblxufSApICk7XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL25vZGVfbW9kdWxlcy9qcXVlcnktdWkvdWkvd2lkZ2V0LmpzXG4vLyBtb2R1bGUgaWQgPSAuL25vZGVfbW9kdWxlcy9qcXVlcnktdWkvdWkvd2lkZ2V0LmpzXG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsIi8qIVxuICogalF1ZXJ5IFVJIE1vdXNlIDEuMTIuMVxuICogaHR0cDovL2pxdWVyeXVpLmNvbVxuICpcbiAqIENvcHlyaWdodCBqUXVlcnkgRm91bmRhdGlvbiBhbmQgb3RoZXIgY29udHJpYnV0b3JzXG4gKiBSZWxlYXNlZCB1bmRlciB0aGUgTUlUIGxpY2Vuc2UuXG4gKiBodHRwOi8vanF1ZXJ5Lm9yZy9saWNlbnNlXG4gKi9cblxuLy8+PmxhYmVsOiBNb3VzZVxuLy8+Pmdyb3VwOiBXaWRnZXRzXG4vLz4+ZGVzY3JpcHRpb246IEFic3RyYWN0cyBtb3VzZS1iYXNlZCBpbnRlcmFjdGlvbnMgdG8gYXNzaXN0IGluIGNyZWF0aW5nIGNlcnRhaW4gd2lkZ2V0cy5cbi8vPj5kb2NzOiBodHRwOi8vYXBpLmpxdWVyeXVpLmNvbS9tb3VzZS9cblxuKCBmdW5jdGlvbiggZmFjdG9yeSApIHtcblx0aWYgKCB0eXBlb2YgZGVmaW5lID09PSBcImZ1bmN0aW9uXCIgJiYgZGVmaW5lLmFtZCApIHtcblxuXHRcdC8vIEFNRC4gUmVnaXN0ZXIgYXMgYW4gYW5vbnltb3VzIG1vZHVsZS5cblx0XHRkZWZpbmUoIFtcblx0XHRcdFwianF1ZXJ5XCIsXG5cdFx0XHRcIi4uL2llXCIsXG5cdFx0XHRcIi4uL3ZlcnNpb25cIixcblx0XHRcdFwiLi4vd2lkZ2V0XCJcblx0XHRdLCBmYWN0b3J5ICk7XG5cdH0gZWxzZSB7XG5cblx0XHQvLyBCcm93c2VyIGdsb2JhbHNcblx0XHRmYWN0b3J5KCBqUXVlcnkgKTtcblx0fVxufSggZnVuY3Rpb24oICQgKSB7XG5cbnZhciBtb3VzZUhhbmRsZWQgPSBmYWxzZTtcbiQoIGRvY3VtZW50ICkub24oIFwibW91c2V1cFwiLCBmdW5jdGlvbigpIHtcblx0bW91c2VIYW5kbGVkID0gZmFsc2U7XG59ICk7XG5cbnJldHVybiAkLndpZGdldCggXCJ1aS5tb3VzZVwiLCB7XG5cdHZlcnNpb246IFwiMS4xMi4xXCIsXG5cdG9wdGlvbnM6IHtcblx0XHRjYW5jZWw6IFwiaW5wdXQsIHRleHRhcmVhLCBidXR0b24sIHNlbGVjdCwgb3B0aW9uXCIsXG5cdFx0ZGlzdGFuY2U6IDEsXG5cdFx0ZGVsYXk6IDBcblx0fSxcblx0X21vdXNlSW5pdDogZnVuY3Rpb24oKSB7XG5cdFx0dmFyIHRoYXQgPSB0aGlzO1xuXG5cdFx0dGhpcy5lbGVtZW50XG5cdFx0XHQub24oIFwibW91c2Vkb3duLlwiICsgdGhpcy53aWRnZXROYW1lLCBmdW5jdGlvbiggZXZlbnQgKSB7XG5cdFx0XHRcdHJldHVybiB0aGF0Ll9tb3VzZURvd24oIGV2ZW50ICk7XG5cdFx0XHR9IClcblx0XHRcdC5vbiggXCJjbGljay5cIiArIHRoaXMud2lkZ2V0TmFtZSwgZnVuY3Rpb24oIGV2ZW50ICkge1xuXHRcdFx0XHRpZiAoIHRydWUgPT09ICQuZGF0YSggZXZlbnQudGFyZ2V0LCB0aGF0LndpZGdldE5hbWUgKyBcIi5wcmV2ZW50Q2xpY2tFdmVudFwiICkgKSB7XG5cdFx0XHRcdFx0JC5yZW1vdmVEYXRhKCBldmVudC50YXJnZXQsIHRoYXQud2lkZ2V0TmFtZSArIFwiLnByZXZlbnRDbGlja0V2ZW50XCIgKTtcblx0XHRcdFx0XHRldmVudC5zdG9wSW1tZWRpYXRlUHJvcGFnYXRpb24oKTtcblx0XHRcdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0XHRcdH1cblx0XHRcdH0gKTtcblxuXHRcdHRoaXMuc3RhcnRlZCA9IGZhbHNlO1xuXHR9LFxuXG5cdC8vIFRPRE86IG1ha2Ugc3VyZSBkZXN0cm95aW5nIG9uZSBpbnN0YW5jZSBvZiBtb3VzZSBkb2Vzbid0IG1lc3Mgd2l0aFxuXHQvLyBvdGhlciBpbnN0YW5jZXMgb2YgbW91c2Vcblx0X21vdXNlRGVzdHJveTogZnVuY3Rpb24oKSB7XG5cdFx0dGhpcy5lbGVtZW50Lm9mZiggXCIuXCIgKyB0aGlzLndpZGdldE5hbWUgKTtcblx0XHRpZiAoIHRoaXMuX21vdXNlTW92ZURlbGVnYXRlICkge1xuXHRcdFx0dGhpcy5kb2N1bWVudFxuXHRcdFx0XHQub2ZmKCBcIm1vdXNlbW92ZS5cIiArIHRoaXMud2lkZ2V0TmFtZSwgdGhpcy5fbW91c2VNb3ZlRGVsZWdhdGUgKVxuXHRcdFx0XHQub2ZmKCBcIm1vdXNldXAuXCIgKyB0aGlzLndpZGdldE5hbWUsIHRoaXMuX21vdXNlVXBEZWxlZ2F0ZSApO1xuXHRcdH1cblx0fSxcblxuXHRfbW91c2VEb3duOiBmdW5jdGlvbiggZXZlbnQgKSB7XG5cblx0XHQvLyBkb24ndCBsZXQgbW9yZSB0aGFuIG9uZSB3aWRnZXQgaGFuZGxlIG1vdXNlU3RhcnRcblx0XHRpZiAoIG1vdXNlSGFuZGxlZCApIHtcblx0XHRcdHJldHVybjtcblx0XHR9XG5cblx0XHR0aGlzLl9tb3VzZU1vdmVkID0gZmFsc2U7XG5cblx0XHQvLyBXZSBtYXkgaGF2ZSBtaXNzZWQgbW91c2V1cCAob3V0IG9mIHdpbmRvdylcblx0XHQoIHRoaXMuX21vdXNlU3RhcnRlZCAmJiB0aGlzLl9tb3VzZVVwKCBldmVudCApICk7XG5cblx0XHR0aGlzLl9tb3VzZURvd25FdmVudCA9IGV2ZW50O1xuXG5cdFx0dmFyIHRoYXQgPSB0aGlzLFxuXHRcdFx0YnRuSXNMZWZ0ID0gKCBldmVudC53aGljaCA9PT0gMSApLFxuXG5cdFx0XHQvLyBldmVudC50YXJnZXQubm9kZU5hbWUgd29ya3MgYXJvdW5kIGEgYnVnIGluIElFIDggd2l0aFxuXHRcdFx0Ly8gZGlzYWJsZWQgaW5wdXRzICgjNzYyMClcblx0XHRcdGVsSXNDYW5jZWwgPSAoIHR5cGVvZiB0aGlzLm9wdGlvbnMuY2FuY2VsID09PSBcInN0cmluZ1wiICYmIGV2ZW50LnRhcmdldC5ub2RlTmFtZSA/XG5cdFx0XHRcdCQoIGV2ZW50LnRhcmdldCApLmNsb3Nlc3QoIHRoaXMub3B0aW9ucy5jYW5jZWwgKS5sZW5ndGggOiBmYWxzZSApO1xuXHRcdGlmICggIWJ0bklzTGVmdCB8fCBlbElzQ2FuY2VsIHx8ICF0aGlzLl9tb3VzZUNhcHR1cmUoIGV2ZW50ICkgKSB7XG5cdFx0XHRyZXR1cm4gdHJ1ZTtcblx0XHR9XG5cblx0XHR0aGlzLm1vdXNlRGVsYXlNZXQgPSAhdGhpcy5vcHRpb25zLmRlbGF5O1xuXHRcdGlmICggIXRoaXMubW91c2VEZWxheU1ldCApIHtcblx0XHRcdHRoaXMuX21vdXNlRGVsYXlUaW1lciA9IHNldFRpbWVvdXQoIGZ1bmN0aW9uKCkge1xuXHRcdFx0XHR0aGF0Lm1vdXNlRGVsYXlNZXQgPSB0cnVlO1xuXHRcdFx0fSwgdGhpcy5vcHRpb25zLmRlbGF5ICk7XG5cdFx0fVxuXG5cdFx0aWYgKCB0aGlzLl9tb3VzZURpc3RhbmNlTWV0KCBldmVudCApICYmIHRoaXMuX21vdXNlRGVsYXlNZXQoIGV2ZW50ICkgKSB7XG5cdFx0XHR0aGlzLl9tb3VzZVN0YXJ0ZWQgPSAoIHRoaXMuX21vdXNlU3RhcnQoIGV2ZW50ICkgIT09IGZhbHNlICk7XG5cdFx0XHRpZiAoICF0aGlzLl9tb3VzZVN0YXJ0ZWQgKSB7XG5cdFx0XHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRcdHJldHVybiB0cnVlO1xuXHRcdFx0fVxuXHRcdH1cblxuXHRcdC8vIENsaWNrIGV2ZW50IG1heSBuZXZlciBoYXZlIGZpcmVkIChHZWNrbyAmIE9wZXJhKVxuXHRcdGlmICggdHJ1ZSA9PT0gJC5kYXRhKCBldmVudC50YXJnZXQsIHRoaXMud2lkZ2V0TmFtZSArIFwiLnByZXZlbnRDbGlja0V2ZW50XCIgKSApIHtcblx0XHRcdCQucmVtb3ZlRGF0YSggZXZlbnQudGFyZ2V0LCB0aGlzLndpZGdldE5hbWUgKyBcIi5wcmV2ZW50Q2xpY2tFdmVudFwiICk7XG5cdFx0fVxuXG5cdFx0Ly8gVGhlc2UgZGVsZWdhdGVzIGFyZSByZXF1aXJlZCB0byBrZWVwIGNvbnRleHRcblx0XHR0aGlzLl9tb3VzZU1vdmVEZWxlZ2F0ZSA9IGZ1bmN0aW9uKCBldmVudCApIHtcblx0XHRcdHJldHVybiB0aGF0Ll9tb3VzZU1vdmUoIGV2ZW50ICk7XG5cdFx0fTtcblx0XHR0aGlzLl9tb3VzZVVwRGVsZWdhdGUgPSBmdW5jdGlvbiggZXZlbnQgKSB7XG5cdFx0XHRyZXR1cm4gdGhhdC5fbW91c2VVcCggZXZlbnQgKTtcblx0XHR9O1xuXG5cdFx0dGhpcy5kb2N1bWVudFxuXHRcdFx0Lm9uKCBcIm1vdXNlbW92ZS5cIiArIHRoaXMud2lkZ2V0TmFtZSwgdGhpcy5fbW91c2VNb3ZlRGVsZWdhdGUgKVxuXHRcdFx0Lm9uKCBcIm1vdXNldXAuXCIgKyB0aGlzLndpZGdldE5hbWUsIHRoaXMuX21vdXNlVXBEZWxlZ2F0ZSApO1xuXG5cdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblxuXHRcdG1vdXNlSGFuZGxlZCA9IHRydWU7XG5cdFx0cmV0dXJuIHRydWU7XG5cdH0sXG5cblx0X21vdXNlTW92ZTogZnVuY3Rpb24oIGV2ZW50ICkge1xuXG5cdFx0Ly8gT25seSBjaGVjayBmb3IgbW91c2V1cHMgb3V0c2lkZSB0aGUgZG9jdW1lbnQgaWYgeW91J3ZlIG1vdmVkIGluc2lkZSB0aGUgZG9jdW1lbnRcblx0XHQvLyBhdCBsZWFzdCBvbmNlLiBUaGlzIHByZXZlbnRzIHRoZSBmaXJpbmcgb2YgbW91c2V1cCBpbiB0aGUgY2FzZSBvZiBJRTw5LCB3aGljaCB3aWxsXG5cdFx0Ly8gZmlyZSBhIG1vdXNlbW92ZSBldmVudCBpZiBjb250ZW50IGlzIHBsYWNlZCB1bmRlciB0aGUgY3Vyc29yLiBTZWUgIzc3Nzhcblx0XHQvLyBTdXBwb3J0OiBJRSA8OVxuXHRcdGlmICggdGhpcy5fbW91c2VNb3ZlZCApIHtcblxuXHRcdFx0Ly8gSUUgbW91c2V1cCBjaGVjayAtIG1vdXNldXAgaGFwcGVuZWQgd2hlbiBtb3VzZSB3YXMgb3V0IG9mIHdpbmRvd1xuXHRcdFx0aWYgKCAkLnVpLmllICYmICggIWRvY3VtZW50LmRvY3VtZW50TW9kZSB8fCBkb2N1bWVudC5kb2N1bWVudE1vZGUgPCA5ICkgJiZcblx0XHRcdFx0XHQhZXZlbnQuYnV0dG9uICkge1xuXHRcdFx0XHRyZXR1cm4gdGhpcy5fbW91c2VVcCggZXZlbnQgKTtcblxuXHRcdFx0Ly8gSWZyYW1lIG1vdXNldXAgY2hlY2sgLSBtb3VzZXVwIG9jY3VycmVkIGluIGFub3RoZXIgZG9jdW1lbnRcblx0XHRcdH0gZWxzZSBpZiAoICFldmVudC53aGljaCApIHtcblxuXHRcdFx0XHQvLyBTdXBwb3J0OiBTYWZhcmkgPD04IC0gOVxuXHRcdFx0XHQvLyBTYWZhcmkgc2V0cyB3aGljaCB0byAwIGlmIHlvdSBwcmVzcyBhbnkgb2YgdGhlIGZvbGxvd2luZyBrZXlzXG5cdFx0XHRcdC8vIGR1cmluZyBhIGRyYWcgKCMxNDQ2MSlcblx0XHRcdFx0aWYgKCBldmVudC5vcmlnaW5hbEV2ZW50LmFsdEtleSB8fCBldmVudC5vcmlnaW5hbEV2ZW50LmN0cmxLZXkgfHxcblx0XHRcdFx0XHRcdGV2ZW50Lm9yaWdpbmFsRXZlbnQubWV0YUtleSB8fCBldmVudC5vcmlnaW5hbEV2ZW50LnNoaWZ0S2V5ICkge1xuXHRcdFx0XHRcdHRoaXMuaWdub3JlTWlzc2luZ1doaWNoID0gdHJ1ZTtcblx0XHRcdFx0fSBlbHNlIGlmICggIXRoaXMuaWdub3JlTWlzc2luZ1doaWNoICkge1xuXHRcdFx0XHRcdHJldHVybiB0aGlzLl9tb3VzZVVwKCBldmVudCApO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0fVxuXG5cdFx0aWYgKCBldmVudC53aGljaCB8fCBldmVudC5idXR0b24gKSB7XG5cdFx0XHR0aGlzLl9tb3VzZU1vdmVkID0gdHJ1ZTtcblx0XHR9XG5cblx0XHRpZiAoIHRoaXMuX21vdXNlU3RhcnRlZCApIHtcblx0XHRcdHRoaXMuX21vdXNlRHJhZyggZXZlbnQgKTtcblx0XHRcdHJldHVybiBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuXHRcdH1cblxuXHRcdGlmICggdGhpcy5fbW91c2VEaXN0YW5jZU1ldCggZXZlbnQgKSAmJiB0aGlzLl9tb3VzZURlbGF5TWV0KCBldmVudCApICkge1xuXHRcdFx0dGhpcy5fbW91c2VTdGFydGVkID1cblx0XHRcdFx0KCB0aGlzLl9tb3VzZVN0YXJ0KCB0aGlzLl9tb3VzZURvd25FdmVudCwgZXZlbnQgKSAhPT0gZmFsc2UgKTtcblx0XHRcdCggdGhpcy5fbW91c2VTdGFydGVkID8gdGhpcy5fbW91c2VEcmFnKCBldmVudCApIDogdGhpcy5fbW91c2VVcCggZXZlbnQgKSApO1xuXHRcdH1cblxuXHRcdHJldHVybiAhdGhpcy5fbW91c2VTdGFydGVkO1xuXHR9LFxuXG5cdF9tb3VzZVVwOiBmdW5jdGlvbiggZXZlbnQgKSB7XG5cdFx0dGhpcy5kb2N1bWVudFxuXHRcdFx0Lm9mZiggXCJtb3VzZW1vdmUuXCIgKyB0aGlzLndpZGdldE5hbWUsIHRoaXMuX21vdXNlTW92ZURlbGVnYXRlIClcblx0XHRcdC5vZmYoIFwibW91c2V1cC5cIiArIHRoaXMud2lkZ2V0TmFtZSwgdGhpcy5fbW91c2VVcERlbGVnYXRlICk7XG5cblx0XHRpZiAoIHRoaXMuX21vdXNlU3RhcnRlZCApIHtcblx0XHRcdHRoaXMuX21vdXNlU3RhcnRlZCA9IGZhbHNlO1xuXG5cdFx0XHRpZiAoIGV2ZW50LnRhcmdldCA9PT0gdGhpcy5fbW91c2VEb3duRXZlbnQudGFyZ2V0ICkge1xuXHRcdFx0XHQkLmRhdGEoIGV2ZW50LnRhcmdldCwgdGhpcy53aWRnZXROYW1lICsgXCIucHJldmVudENsaWNrRXZlbnRcIiwgdHJ1ZSApO1xuXHRcdFx0fVxuXG5cdFx0XHR0aGlzLl9tb3VzZVN0b3AoIGV2ZW50ICk7XG5cdFx0fVxuXG5cdFx0aWYgKCB0aGlzLl9tb3VzZURlbGF5VGltZXIgKSB7XG5cdFx0XHRjbGVhclRpbWVvdXQoIHRoaXMuX21vdXNlRGVsYXlUaW1lciApO1xuXHRcdFx0ZGVsZXRlIHRoaXMuX21vdXNlRGVsYXlUaW1lcjtcblx0XHR9XG5cblx0XHR0aGlzLmlnbm9yZU1pc3NpbmdXaGljaCA9IGZhbHNlO1xuXHRcdG1vdXNlSGFuZGxlZCA9IGZhbHNlO1xuXHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cdH0sXG5cblx0X21vdXNlRGlzdGFuY2VNZXQ6IGZ1bmN0aW9uKCBldmVudCApIHtcblx0XHRyZXR1cm4gKCBNYXRoLm1heChcblx0XHRcdFx0TWF0aC5hYnMoIHRoaXMuX21vdXNlRG93bkV2ZW50LnBhZ2VYIC0gZXZlbnQucGFnZVggKSxcblx0XHRcdFx0TWF0aC5hYnMoIHRoaXMuX21vdXNlRG93bkV2ZW50LnBhZ2VZIC0gZXZlbnQucGFnZVkgKVxuXHRcdFx0KSA+PSB0aGlzLm9wdGlvbnMuZGlzdGFuY2Vcblx0XHQpO1xuXHR9LFxuXG5cdF9tb3VzZURlbGF5TWV0OiBmdW5jdGlvbiggLyogZXZlbnQgKi8gKSB7XG5cdFx0cmV0dXJuIHRoaXMubW91c2VEZWxheU1ldDtcblx0fSxcblxuXHQvLyBUaGVzZSBhcmUgcGxhY2Vob2xkZXIgbWV0aG9kcywgdG8gYmUgb3ZlcnJpZGVuIGJ5IGV4dGVuZGluZyBwbHVnaW5cblx0X21vdXNlU3RhcnQ6IGZ1bmN0aW9uKCAvKiBldmVudCAqLyApIHt9LFxuXHRfbW91c2VEcmFnOiBmdW5jdGlvbiggLyogZXZlbnQgKi8gKSB7fSxcblx0X21vdXNlU3RvcDogZnVuY3Rpb24oIC8qIGV2ZW50ICovICkge30sXG5cdF9tb3VzZUNhcHR1cmU6IGZ1bmN0aW9uKCAvKiBldmVudCAqLyApIHsgcmV0dXJuIHRydWU7IH1cbn0gKTtcblxufSApICk7XG5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL25vZGVfbW9kdWxlcy9qcXVlcnktdWkvdWkvd2lkZ2V0cy9tb3VzZS5qc1xuLy8gbW9kdWxlIGlkID0gLi9ub2RlX21vZHVsZXMvanF1ZXJ5LXVpL3VpL3dpZGdldHMvbW91c2UuanNcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwiLyohXG4gKiBqUXVlcnkgVUkgU29ydGFibGUgMS4xMi4xXG4gKiBodHRwOi8vanF1ZXJ5dWkuY29tXG4gKlxuICogQ29weXJpZ2h0IGpRdWVyeSBGb3VuZGF0aW9uIGFuZCBvdGhlciBjb250cmlidXRvcnNcbiAqIFJlbGVhc2VkIHVuZGVyIHRoZSBNSVQgbGljZW5zZS5cbiAqIGh0dHA6Ly9qcXVlcnkub3JnL2xpY2Vuc2VcbiAqL1xuXG4vLz4+bGFiZWw6IFNvcnRhYmxlXG4vLz4+Z3JvdXA6IEludGVyYWN0aW9uc1xuLy8+PmRlc2NyaXB0aW9uOiBFbmFibGVzIGl0ZW1zIGluIGEgbGlzdCB0byBiZSBzb3J0ZWQgdXNpbmcgdGhlIG1vdXNlLlxuLy8+PmRvY3M6IGh0dHA6Ly9hcGkuanF1ZXJ5dWkuY29tL3NvcnRhYmxlL1xuLy8+PmRlbW9zOiBodHRwOi8vanF1ZXJ5dWkuY29tL3NvcnRhYmxlL1xuLy8+PmNzcy5zdHJ1Y3R1cmU6IC4uLy4uL3RoZW1lcy9iYXNlL3NvcnRhYmxlLmNzc1xuXG4oIGZ1bmN0aW9uKCBmYWN0b3J5ICkge1xuXHRpZiAoIHR5cGVvZiBkZWZpbmUgPT09IFwiZnVuY3Rpb25cIiAmJiBkZWZpbmUuYW1kICkge1xuXG5cdFx0Ly8gQU1ELiBSZWdpc3RlciBhcyBhbiBhbm9ueW1vdXMgbW9kdWxlLlxuXHRcdGRlZmluZSggW1xuXHRcdFx0XCJqcXVlcnlcIixcblx0XHRcdFwiLi9tb3VzZVwiLFxuXHRcdFx0XCIuLi9kYXRhXCIsXG5cdFx0XHRcIi4uL2llXCIsXG5cdFx0XHRcIi4uL3Njcm9sbC1wYXJlbnRcIixcblx0XHRcdFwiLi4vdmVyc2lvblwiLFxuXHRcdFx0XCIuLi93aWRnZXRcIlxuXHRcdF0sIGZhY3RvcnkgKTtcblx0fSBlbHNlIHtcblxuXHRcdC8vIEJyb3dzZXIgZ2xvYmFsc1xuXHRcdGZhY3RvcnkoIGpRdWVyeSApO1xuXHR9XG59KCBmdW5jdGlvbiggJCApIHtcblxucmV0dXJuICQud2lkZ2V0KCBcInVpLnNvcnRhYmxlXCIsICQudWkubW91c2UsIHtcblx0dmVyc2lvbjogXCIxLjEyLjFcIixcblx0d2lkZ2V0RXZlbnRQcmVmaXg6IFwic29ydFwiLFxuXHRyZWFkeTogZmFsc2UsXG5cdG9wdGlvbnM6IHtcblx0XHRhcHBlbmRUbzogXCJwYXJlbnRcIixcblx0XHRheGlzOiBmYWxzZSxcblx0XHRjb25uZWN0V2l0aDogZmFsc2UsXG5cdFx0Y29udGFpbm1lbnQ6IGZhbHNlLFxuXHRcdGN1cnNvcjogXCJhdXRvXCIsXG5cdFx0Y3Vyc29yQXQ6IGZhbHNlLFxuXHRcdGRyb3BPbkVtcHR5OiB0cnVlLFxuXHRcdGZvcmNlUGxhY2Vob2xkZXJTaXplOiBmYWxzZSxcblx0XHRmb3JjZUhlbHBlclNpemU6IGZhbHNlLFxuXHRcdGdyaWQ6IGZhbHNlLFxuXHRcdGhhbmRsZTogZmFsc2UsXG5cdFx0aGVscGVyOiBcIm9yaWdpbmFsXCIsXG5cdFx0aXRlbXM6IFwiPiAqXCIsXG5cdFx0b3BhY2l0eTogZmFsc2UsXG5cdFx0cGxhY2Vob2xkZXI6IGZhbHNlLFxuXHRcdHJldmVydDogZmFsc2UsXG5cdFx0c2Nyb2xsOiB0cnVlLFxuXHRcdHNjcm9sbFNlbnNpdGl2aXR5OiAyMCxcblx0XHRzY3JvbGxTcGVlZDogMjAsXG5cdFx0c2NvcGU6IFwiZGVmYXVsdFwiLFxuXHRcdHRvbGVyYW5jZTogXCJpbnRlcnNlY3RcIixcblx0XHR6SW5kZXg6IDEwMDAsXG5cblx0XHQvLyBDYWxsYmFja3Ncblx0XHRhY3RpdmF0ZTogbnVsbCxcblx0XHRiZWZvcmVTdG9wOiBudWxsLFxuXHRcdGNoYW5nZTogbnVsbCxcblx0XHRkZWFjdGl2YXRlOiBudWxsLFxuXHRcdG91dDogbnVsbCxcblx0XHRvdmVyOiBudWxsLFxuXHRcdHJlY2VpdmU6IG51bGwsXG5cdFx0cmVtb3ZlOiBudWxsLFxuXHRcdHNvcnQ6IG51bGwsXG5cdFx0c3RhcnQ6IG51bGwsXG5cdFx0c3RvcDogbnVsbCxcblx0XHR1cGRhdGU6IG51bGxcblx0fSxcblxuXHRfaXNPdmVyQXhpczogZnVuY3Rpb24oIHgsIHJlZmVyZW5jZSwgc2l6ZSApIHtcblx0XHRyZXR1cm4gKCB4ID49IHJlZmVyZW5jZSApICYmICggeCA8ICggcmVmZXJlbmNlICsgc2l6ZSApICk7XG5cdH0sXG5cblx0X2lzRmxvYXRpbmc6IGZ1bmN0aW9uKCBpdGVtICkge1xuXHRcdHJldHVybiAoIC9sZWZ0fHJpZ2h0LyApLnRlc3QoIGl0ZW0uY3NzKCBcImZsb2F0XCIgKSApIHx8XG5cdFx0XHQoIC9pbmxpbmV8dGFibGUtY2VsbC8gKS50ZXN0KCBpdGVtLmNzcyggXCJkaXNwbGF5XCIgKSApO1xuXHR9LFxuXG5cdF9jcmVhdGU6IGZ1bmN0aW9uKCkge1xuXHRcdHRoaXMuY29udGFpbmVyQ2FjaGUgPSB7fTtcblx0XHR0aGlzLl9hZGRDbGFzcyggXCJ1aS1zb3J0YWJsZVwiICk7XG5cblx0XHQvL0dldCB0aGUgaXRlbXNcblx0XHR0aGlzLnJlZnJlc2goKTtcblxuXHRcdC8vTGV0J3MgZGV0ZXJtaW5lIHRoZSBwYXJlbnQncyBvZmZzZXRcblx0XHR0aGlzLm9mZnNldCA9IHRoaXMuZWxlbWVudC5vZmZzZXQoKTtcblxuXHRcdC8vSW5pdGlhbGl6ZSBtb3VzZSBldmVudHMgZm9yIGludGVyYWN0aW9uXG5cdFx0dGhpcy5fbW91c2VJbml0KCk7XG5cblx0XHR0aGlzLl9zZXRIYW5kbGVDbGFzc05hbWUoKTtcblxuXHRcdC8vV2UncmUgcmVhZHkgdG8gZ29cblx0XHR0aGlzLnJlYWR5ID0gdHJ1ZTtcblxuXHR9LFxuXG5cdF9zZXRPcHRpb246IGZ1bmN0aW9uKCBrZXksIHZhbHVlICkge1xuXHRcdHRoaXMuX3N1cGVyKCBrZXksIHZhbHVlICk7XG5cblx0XHRpZiAoIGtleSA9PT0gXCJoYW5kbGVcIiApIHtcblx0XHRcdHRoaXMuX3NldEhhbmRsZUNsYXNzTmFtZSgpO1xuXHRcdH1cblx0fSxcblxuXHRfc2V0SGFuZGxlQ2xhc3NOYW1lOiBmdW5jdGlvbigpIHtcblx0XHR2YXIgdGhhdCA9IHRoaXM7XG5cdFx0dGhpcy5fcmVtb3ZlQ2xhc3MoIHRoaXMuZWxlbWVudC5maW5kKCBcIi51aS1zb3J0YWJsZS1oYW5kbGVcIiApLCBcInVpLXNvcnRhYmxlLWhhbmRsZVwiICk7XG5cdFx0JC5lYWNoKCB0aGlzLml0ZW1zLCBmdW5jdGlvbigpIHtcblx0XHRcdHRoYXQuX2FkZENsYXNzKFxuXHRcdFx0XHR0aGlzLmluc3RhbmNlLm9wdGlvbnMuaGFuZGxlID9cblx0XHRcdFx0XHR0aGlzLml0ZW0uZmluZCggdGhpcy5pbnN0YW5jZS5vcHRpb25zLmhhbmRsZSApIDpcblx0XHRcdFx0XHR0aGlzLml0ZW0sXG5cdFx0XHRcdFwidWktc29ydGFibGUtaGFuZGxlXCJcblx0XHRcdCk7XG5cdFx0fSApO1xuXHR9LFxuXG5cdF9kZXN0cm95OiBmdW5jdGlvbigpIHtcblx0XHR0aGlzLl9tb3VzZURlc3Ryb3koKTtcblxuXHRcdGZvciAoIHZhciBpID0gdGhpcy5pdGVtcy5sZW5ndGggLSAxOyBpID49IDA7IGktLSApIHtcblx0XHRcdHRoaXMuaXRlbXNbIGkgXS5pdGVtLnJlbW92ZURhdGEoIHRoaXMud2lkZ2V0TmFtZSArIFwiLWl0ZW1cIiApO1xuXHRcdH1cblxuXHRcdHJldHVybiB0aGlzO1xuXHR9LFxuXG5cdF9tb3VzZUNhcHR1cmU6IGZ1bmN0aW9uKCBldmVudCwgb3ZlcnJpZGVIYW5kbGUgKSB7XG5cdFx0dmFyIGN1cnJlbnRJdGVtID0gbnVsbCxcblx0XHRcdHZhbGlkSGFuZGxlID0gZmFsc2UsXG5cdFx0XHR0aGF0ID0gdGhpcztcblxuXHRcdGlmICggdGhpcy5yZXZlcnRpbmcgKSB7XG5cdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0fVxuXG5cdFx0aWYgKCB0aGlzLm9wdGlvbnMuZGlzYWJsZWQgfHwgdGhpcy5vcHRpb25zLnR5cGUgPT09IFwic3RhdGljXCIgKSB7XG5cdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0fVxuXG5cdFx0Ly9XZSBoYXZlIHRvIHJlZnJlc2ggdGhlIGl0ZW1zIGRhdGEgb25jZSBmaXJzdFxuXHRcdHRoaXMuX3JlZnJlc2hJdGVtcyggZXZlbnQgKTtcblxuXHRcdC8vRmluZCBvdXQgaWYgdGhlIGNsaWNrZWQgbm9kZSAob3Igb25lIG9mIGl0cyBwYXJlbnRzKSBpcyBhIGFjdHVhbCBpdGVtIGluIHRoaXMuaXRlbXNcblx0XHQkKCBldmVudC50YXJnZXQgKS5wYXJlbnRzKCkuZWFjaCggZnVuY3Rpb24oKSB7XG5cdFx0XHRpZiAoICQuZGF0YSggdGhpcywgdGhhdC53aWRnZXROYW1lICsgXCItaXRlbVwiICkgPT09IHRoYXQgKSB7XG5cdFx0XHRcdGN1cnJlbnRJdGVtID0gJCggdGhpcyApO1xuXHRcdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0XHR9XG5cdFx0fSApO1xuXHRcdGlmICggJC5kYXRhKCBldmVudC50YXJnZXQsIHRoYXQud2lkZ2V0TmFtZSArIFwiLWl0ZW1cIiApID09PSB0aGF0ICkge1xuXHRcdFx0Y3VycmVudEl0ZW0gPSAkKCBldmVudC50YXJnZXQgKTtcblx0XHR9XG5cblx0XHRpZiAoICFjdXJyZW50SXRlbSApIHtcblx0XHRcdHJldHVybiBmYWxzZTtcblx0XHR9XG5cdFx0aWYgKCB0aGlzLm9wdGlvbnMuaGFuZGxlICYmICFvdmVycmlkZUhhbmRsZSApIHtcblx0XHRcdCQoIHRoaXMub3B0aW9ucy5oYW5kbGUsIGN1cnJlbnRJdGVtICkuZmluZCggXCIqXCIgKS5hZGRCYWNrKCkuZWFjaCggZnVuY3Rpb24oKSB7XG5cdFx0XHRcdGlmICggdGhpcyA9PT0gZXZlbnQudGFyZ2V0ICkge1xuXHRcdFx0XHRcdHZhbGlkSGFuZGxlID0gdHJ1ZTtcblx0XHRcdFx0fVxuXHRcdFx0fSApO1xuXHRcdFx0aWYgKCAhdmFsaWRIYW5kbGUgKSB7XG5cdFx0XHRcdHJldHVybiBmYWxzZTtcblx0XHRcdH1cblx0XHR9XG5cblx0XHR0aGlzLmN1cnJlbnRJdGVtID0gY3VycmVudEl0ZW07XG5cdFx0dGhpcy5fcmVtb3ZlQ3VycmVudHNGcm9tSXRlbXMoKTtcblx0XHRyZXR1cm4gdHJ1ZTtcblxuXHR9LFxuXG5cdF9tb3VzZVN0YXJ0OiBmdW5jdGlvbiggZXZlbnQsIG92ZXJyaWRlSGFuZGxlLCBub0FjdGl2YXRpb24gKSB7XG5cblx0XHR2YXIgaSwgYm9keSxcblx0XHRcdG8gPSB0aGlzLm9wdGlvbnM7XG5cblx0XHR0aGlzLmN1cnJlbnRDb250YWluZXIgPSB0aGlzO1xuXG5cdFx0Ly9XZSBvbmx5IG5lZWQgdG8gY2FsbCByZWZyZXNoUG9zaXRpb25zLCBiZWNhdXNlIHRoZSByZWZyZXNoSXRlbXMgY2FsbCBoYXMgYmVlbiBtb3ZlZCB0b1xuXHRcdC8vIG1vdXNlQ2FwdHVyZVxuXHRcdHRoaXMucmVmcmVzaFBvc2l0aW9ucygpO1xuXG5cdFx0Ly9DcmVhdGUgYW5kIGFwcGVuZCB0aGUgdmlzaWJsZSBoZWxwZXJcblx0XHR0aGlzLmhlbHBlciA9IHRoaXMuX2NyZWF0ZUhlbHBlciggZXZlbnQgKTtcblxuXHRcdC8vQ2FjaGUgdGhlIGhlbHBlciBzaXplXG5cdFx0dGhpcy5fY2FjaGVIZWxwZXJQcm9wb3J0aW9ucygpO1xuXG5cdFx0Lypcblx0XHQgKiAtIFBvc2l0aW9uIGdlbmVyYXRpb24gLVxuXHRcdCAqIFRoaXMgYmxvY2sgZ2VuZXJhdGVzIGV2ZXJ5dGhpbmcgcG9zaXRpb24gcmVsYXRlZCAtIGl0J3MgdGhlIGNvcmUgb2YgZHJhZ2dhYmxlcy5cblx0XHQgKi9cblxuXHRcdC8vQ2FjaGUgdGhlIG1hcmdpbnMgb2YgdGhlIG9yaWdpbmFsIGVsZW1lbnRcblx0XHR0aGlzLl9jYWNoZU1hcmdpbnMoKTtcblxuXHRcdC8vR2V0IHRoZSBuZXh0IHNjcm9sbGluZyBwYXJlbnRcblx0XHR0aGlzLnNjcm9sbFBhcmVudCA9IHRoaXMuaGVscGVyLnNjcm9sbFBhcmVudCgpO1xuXG5cdFx0Ly9UaGUgZWxlbWVudCdzIGFic29sdXRlIHBvc2l0aW9uIG9uIHRoZSBwYWdlIG1pbnVzIG1hcmdpbnNcblx0XHR0aGlzLm9mZnNldCA9IHRoaXMuY3VycmVudEl0ZW0ub2Zmc2V0KCk7XG5cdFx0dGhpcy5vZmZzZXQgPSB7XG5cdFx0XHR0b3A6IHRoaXMub2Zmc2V0LnRvcCAtIHRoaXMubWFyZ2lucy50b3AsXG5cdFx0XHRsZWZ0OiB0aGlzLm9mZnNldC5sZWZ0IC0gdGhpcy5tYXJnaW5zLmxlZnRcblx0XHR9O1xuXG5cdFx0JC5leHRlbmQoIHRoaXMub2Zmc2V0LCB7XG5cdFx0XHRjbGljazogeyAvL1doZXJlIHRoZSBjbGljayBoYXBwZW5lZCwgcmVsYXRpdmUgdG8gdGhlIGVsZW1lbnRcblx0XHRcdFx0bGVmdDogZXZlbnQucGFnZVggLSB0aGlzLm9mZnNldC5sZWZ0LFxuXHRcdFx0XHR0b3A6IGV2ZW50LnBhZ2VZIC0gdGhpcy5vZmZzZXQudG9wXG5cdFx0XHR9LFxuXHRcdFx0cGFyZW50OiB0aGlzLl9nZXRQYXJlbnRPZmZzZXQoKSxcblxuXHRcdFx0Ly8gVGhpcyBpcyBhIHJlbGF0aXZlIHRvIGFic29sdXRlIHBvc2l0aW9uIG1pbnVzIHRoZSBhY3R1YWwgcG9zaXRpb24gY2FsY3VsYXRpb24gLVxuXHRcdFx0Ly8gb25seSB1c2VkIGZvciByZWxhdGl2ZSBwb3NpdGlvbmVkIGhlbHBlclxuXHRcdFx0cmVsYXRpdmU6IHRoaXMuX2dldFJlbGF0aXZlT2Zmc2V0KClcblx0XHR9ICk7XG5cblx0XHQvLyBPbmx5IGFmdGVyIHdlIGdvdCB0aGUgb2Zmc2V0LCB3ZSBjYW4gY2hhbmdlIHRoZSBoZWxwZXIncyBwb3NpdGlvbiB0byBhYnNvbHV0ZVxuXHRcdC8vIFRPRE86IFN0aWxsIG5lZWQgdG8gZmlndXJlIG91dCBhIHdheSB0byBtYWtlIHJlbGF0aXZlIHNvcnRpbmcgcG9zc2libGVcblx0XHR0aGlzLmhlbHBlci5jc3MoIFwicG9zaXRpb25cIiwgXCJhYnNvbHV0ZVwiICk7XG5cdFx0dGhpcy5jc3NQb3NpdGlvbiA9IHRoaXMuaGVscGVyLmNzcyggXCJwb3NpdGlvblwiICk7XG5cblx0XHQvL0dlbmVyYXRlIHRoZSBvcmlnaW5hbCBwb3NpdGlvblxuXHRcdHRoaXMub3JpZ2luYWxQb3NpdGlvbiA9IHRoaXMuX2dlbmVyYXRlUG9zaXRpb24oIGV2ZW50ICk7XG5cdFx0dGhpcy5vcmlnaW5hbFBhZ2VYID0gZXZlbnQucGFnZVg7XG5cdFx0dGhpcy5vcmlnaW5hbFBhZ2VZID0gZXZlbnQucGFnZVk7XG5cblx0XHQvL0FkanVzdCB0aGUgbW91c2Ugb2Zmc2V0IHJlbGF0aXZlIHRvIHRoZSBoZWxwZXIgaWYgXCJjdXJzb3JBdFwiIGlzIHN1cHBsaWVkXG5cdFx0KCBvLmN1cnNvckF0ICYmIHRoaXMuX2FkanVzdE9mZnNldEZyb21IZWxwZXIoIG8uY3Vyc29yQXQgKSApO1xuXG5cdFx0Ly9DYWNoZSB0aGUgZm9ybWVyIERPTSBwb3NpdGlvblxuXHRcdHRoaXMuZG9tUG9zaXRpb24gPSB7XG5cdFx0XHRwcmV2OiB0aGlzLmN1cnJlbnRJdGVtLnByZXYoKVsgMCBdLFxuXHRcdFx0cGFyZW50OiB0aGlzLmN1cnJlbnRJdGVtLnBhcmVudCgpWyAwIF1cblx0XHR9O1xuXG5cdFx0Ly8gSWYgdGhlIGhlbHBlciBpcyBub3QgdGhlIG9yaWdpbmFsLCBoaWRlIHRoZSBvcmlnaW5hbCBzbyBpdCdzIG5vdCBwbGF5aW5nIGFueSByb2xlIGR1cmluZ1xuXHRcdC8vIHRoZSBkcmFnLCB3b24ndCBjYXVzZSBhbnl0aGluZyBiYWQgdGhpcyB3YXlcblx0XHRpZiAoIHRoaXMuaGVscGVyWyAwIF0gIT09IHRoaXMuY3VycmVudEl0ZW1bIDAgXSApIHtcblx0XHRcdHRoaXMuY3VycmVudEl0ZW0uaGlkZSgpO1xuXHRcdH1cblxuXHRcdC8vQ3JlYXRlIHRoZSBwbGFjZWhvbGRlclxuXHRcdHRoaXMuX2NyZWF0ZVBsYWNlaG9sZGVyKCk7XG5cblx0XHQvL1NldCBhIGNvbnRhaW5tZW50IGlmIGdpdmVuIGluIHRoZSBvcHRpb25zXG5cdFx0aWYgKCBvLmNvbnRhaW5tZW50ICkge1xuXHRcdFx0dGhpcy5fc2V0Q29udGFpbm1lbnQoKTtcblx0XHR9XG5cblx0XHRpZiAoIG8uY3Vyc29yICYmIG8uY3Vyc29yICE9PSBcImF1dG9cIiApIHsgLy8gY3Vyc29yIG9wdGlvblxuXHRcdFx0Ym9keSA9IHRoaXMuZG9jdW1lbnQuZmluZCggXCJib2R5XCIgKTtcblxuXHRcdFx0Ly8gU3VwcG9ydDogSUVcblx0XHRcdHRoaXMuc3RvcmVkQ3Vyc29yID0gYm9keS5jc3MoIFwiY3Vyc29yXCIgKTtcblx0XHRcdGJvZHkuY3NzKCBcImN1cnNvclwiLCBvLmN1cnNvciApO1xuXG5cdFx0XHR0aGlzLnN0b3JlZFN0eWxlc2hlZXQgPVxuXHRcdFx0XHQkKCBcIjxzdHlsZT4qeyBjdXJzb3I6IFwiICsgby5jdXJzb3IgKyBcIiAhaW1wb3J0YW50OyB9PC9zdHlsZT5cIiApLmFwcGVuZFRvKCBib2R5ICk7XG5cdFx0fVxuXG5cdFx0aWYgKCBvLm9wYWNpdHkgKSB7IC8vIG9wYWNpdHkgb3B0aW9uXG5cdFx0XHRpZiAoIHRoaXMuaGVscGVyLmNzcyggXCJvcGFjaXR5XCIgKSApIHtcblx0XHRcdFx0dGhpcy5fc3RvcmVkT3BhY2l0eSA9IHRoaXMuaGVscGVyLmNzcyggXCJvcGFjaXR5XCIgKTtcblx0XHRcdH1cblx0XHRcdHRoaXMuaGVscGVyLmNzcyggXCJvcGFjaXR5XCIsIG8ub3BhY2l0eSApO1xuXHRcdH1cblxuXHRcdGlmICggby56SW5kZXggKSB7IC8vIHpJbmRleCBvcHRpb25cblx0XHRcdGlmICggdGhpcy5oZWxwZXIuY3NzKCBcInpJbmRleFwiICkgKSB7XG5cdFx0XHRcdHRoaXMuX3N0b3JlZFpJbmRleCA9IHRoaXMuaGVscGVyLmNzcyggXCJ6SW5kZXhcIiApO1xuXHRcdFx0fVxuXHRcdFx0dGhpcy5oZWxwZXIuY3NzKCBcInpJbmRleFwiLCBvLnpJbmRleCApO1xuXHRcdH1cblxuXHRcdC8vUHJlcGFyZSBzY3JvbGxpbmdcblx0XHRpZiAoIHRoaXMuc2Nyb2xsUGFyZW50WyAwIF0gIT09IHRoaXMuZG9jdW1lbnRbIDAgXSAmJlxuXHRcdFx0XHR0aGlzLnNjcm9sbFBhcmVudFsgMCBdLnRhZ05hbWUgIT09IFwiSFRNTFwiICkge1xuXHRcdFx0dGhpcy5vdmVyZmxvd09mZnNldCA9IHRoaXMuc2Nyb2xsUGFyZW50Lm9mZnNldCgpO1xuXHRcdH1cblxuXHRcdC8vQ2FsbCBjYWxsYmFja3Ncblx0XHR0aGlzLl90cmlnZ2VyKCBcInN0YXJ0XCIsIGV2ZW50LCB0aGlzLl91aUhhc2goKSApO1xuXG5cdFx0Ly9SZWNhY2hlIHRoZSBoZWxwZXIgc2l6ZVxuXHRcdGlmICggIXRoaXMuX3ByZXNlcnZlSGVscGVyUHJvcG9ydGlvbnMgKSB7XG5cdFx0XHR0aGlzLl9jYWNoZUhlbHBlclByb3BvcnRpb25zKCk7XG5cdFx0fVxuXG5cdFx0Ly9Qb3N0IFwiYWN0aXZhdGVcIiBldmVudHMgdG8gcG9zc2libGUgY29udGFpbmVyc1xuXHRcdGlmICggIW5vQWN0aXZhdGlvbiApIHtcblx0XHRcdGZvciAoIGkgPSB0aGlzLmNvbnRhaW5lcnMubGVuZ3RoIC0gMTsgaSA+PSAwOyBpLS0gKSB7XG5cdFx0XHRcdHRoaXMuY29udGFpbmVyc1sgaSBdLl90cmlnZ2VyKCBcImFjdGl2YXRlXCIsIGV2ZW50LCB0aGlzLl91aUhhc2goIHRoaXMgKSApO1xuXHRcdFx0fVxuXHRcdH1cblxuXHRcdC8vUHJlcGFyZSBwb3NzaWJsZSBkcm9wcGFibGVzXG5cdFx0aWYgKCAkLnVpLmRkbWFuYWdlciApIHtcblx0XHRcdCQudWkuZGRtYW5hZ2VyLmN1cnJlbnQgPSB0aGlzO1xuXHRcdH1cblxuXHRcdGlmICggJC51aS5kZG1hbmFnZXIgJiYgIW8uZHJvcEJlaGF2aW91ciApIHtcblx0XHRcdCQudWkuZGRtYW5hZ2VyLnByZXBhcmVPZmZzZXRzKCB0aGlzLCBldmVudCApO1xuXHRcdH1cblxuXHRcdHRoaXMuZHJhZ2dpbmcgPSB0cnVlO1xuXG5cdFx0dGhpcy5fYWRkQ2xhc3MoIHRoaXMuaGVscGVyLCBcInVpLXNvcnRhYmxlLWhlbHBlclwiICk7XG5cblx0XHQvLyBFeGVjdXRlIHRoZSBkcmFnIG9uY2UgLSB0aGlzIGNhdXNlcyB0aGUgaGVscGVyIG5vdCB0byBiZSB2aXNpYmxlYmVmb3JlIGdldHRpbmcgaXRzXG5cdFx0Ly8gY29ycmVjdCBwb3NpdGlvblxuXHRcdHRoaXMuX21vdXNlRHJhZyggZXZlbnQgKTtcblx0XHRyZXR1cm4gdHJ1ZTtcblxuXHR9LFxuXG5cdF9tb3VzZURyYWc6IGZ1bmN0aW9uKCBldmVudCApIHtcblx0XHR2YXIgaSwgaXRlbSwgaXRlbUVsZW1lbnQsIGludGVyc2VjdGlvbixcblx0XHRcdG8gPSB0aGlzLm9wdGlvbnMsXG5cdFx0XHRzY3JvbGxlZCA9IGZhbHNlO1xuXG5cdFx0Ly9Db21wdXRlIHRoZSBoZWxwZXJzIHBvc2l0aW9uXG5cdFx0dGhpcy5wb3NpdGlvbiA9IHRoaXMuX2dlbmVyYXRlUG9zaXRpb24oIGV2ZW50ICk7XG5cdFx0dGhpcy5wb3NpdGlvbkFicyA9IHRoaXMuX2NvbnZlcnRQb3NpdGlvblRvKCBcImFic29sdXRlXCIgKTtcblxuXHRcdGlmICggIXRoaXMubGFzdFBvc2l0aW9uQWJzICkge1xuXHRcdFx0dGhpcy5sYXN0UG9zaXRpb25BYnMgPSB0aGlzLnBvc2l0aW9uQWJzO1xuXHRcdH1cblxuXHRcdC8vRG8gc2Nyb2xsaW5nXG5cdFx0aWYgKCB0aGlzLm9wdGlvbnMuc2Nyb2xsICkge1xuXHRcdFx0aWYgKCB0aGlzLnNjcm9sbFBhcmVudFsgMCBdICE9PSB0aGlzLmRvY3VtZW50WyAwIF0gJiZcblx0XHRcdFx0XHR0aGlzLnNjcm9sbFBhcmVudFsgMCBdLnRhZ05hbWUgIT09IFwiSFRNTFwiICkge1xuXG5cdFx0XHRcdGlmICggKCB0aGlzLm92ZXJmbG93T2Zmc2V0LnRvcCArIHRoaXMuc2Nyb2xsUGFyZW50WyAwIF0ub2Zmc2V0SGVpZ2h0ICkgLVxuXHRcdFx0XHRcdFx0ZXZlbnQucGFnZVkgPCBvLnNjcm9sbFNlbnNpdGl2aXR5ICkge1xuXHRcdFx0XHRcdHRoaXMuc2Nyb2xsUGFyZW50WyAwIF0uc2Nyb2xsVG9wID1cblx0XHRcdFx0XHRcdHNjcm9sbGVkID0gdGhpcy5zY3JvbGxQYXJlbnRbIDAgXS5zY3JvbGxUb3AgKyBvLnNjcm9sbFNwZWVkO1xuXHRcdFx0XHR9IGVsc2UgaWYgKCBldmVudC5wYWdlWSAtIHRoaXMub3ZlcmZsb3dPZmZzZXQudG9wIDwgby5zY3JvbGxTZW5zaXRpdml0eSApIHtcblx0XHRcdFx0XHR0aGlzLnNjcm9sbFBhcmVudFsgMCBdLnNjcm9sbFRvcCA9XG5cdFx0XHRcdFx0XHRzY3JvbGxlZCA9IHRoaXMuc2Nyb2xsUGFyZW50WyAwIF0uc2Nyb2xsVG9wIC0gby5zY3JvbGxTcGVlZDtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdGlmICggKCB0aGlzLm92ZXJmbG93T2Zmc2V0LmxlZnQgKyB0aGlzLnNjcm9sbFBhcmVudFsgMCBdLm9mZnNldFdpZHRoICkgLVxuXHRcdFx0XHRcdFx0ZXZlbnQucGFnZVggPCBvLnNjcm9sbFNlbnNpdGl2aXR5ICkge1xuXHRcdFx0XHRcdHRoaXMuc2Nyb2xsUGFyZW50WyAwIF0uc2Nyb2xsTGVmdCA9IHNjcm9sbGVkID1cblx0XHRcdFx0XHRcdHRoaXMuc2Nyb2xsUGFyZW50WyAwIF0uc2Nyb2xsTGVmdCArIG8uc2Nyb2xsU3BlZWQ7XG5cdFx0XHRcdH0gZWxzZSBpZiAoIGV2ZW50LnBhZ2VYIC0gdGhpcy5vdmVyZmxvd09mZnNldC5sZWZ0IDwgby5zY3JvbGxTZW5zaXRpdml0eSApIHtcblx0XHRcdFx0XHR0aGlzLnNjcm9sbFBhcmVudFsgMCBdLnNjcm9sbExlZnQgPSBzY3JvbGxlZCA9XG5cdFx0XHRcdFx0XHR0aGlzLnNjcm9sbFBhcmVudFsgMCBdLnNjcm9sbExlZnQgLSBvLnNjcm9sbFNwZWVkO1xuXHRcdFx0XHR9XG5cblx0XHRcdH0gZWxzZSB7XG5cblx0XHRcdFx0aWYgKCBldmVudC5wYWdlWSAtIHRoaXMuZG9jdW1lbnQuc2Nyb2xsVG9wKCkgPCBvLnNjcm9sbFNlbnNpdGl2aXR5ICkge1xuXHRcdFx0XHRcdHNjcm9sbGVkID0gdGhpcy5kb2N1bWVudC5zY3JvbGxUb3AoIHRoaXMuZG9jdW1lbnQuc2Nyb2xsVG9wKCkgLSBvLnNjcm9sbFNwZWVkICk7XG5cdFx0XHRcdH0gZWxzZSBpZiAoIHRoaXMud2luZG93LmhlaWdodCgpIC0gKCBldmVudC5wYWdlWSAtIHRoaXMuZG9jdW1lbnQuc2Nyb2xsVG9wKCkgKSA8XG5cdFx0XHRcdFx0XHRvLnNjcm9sbFNlbnNpdGl2aXR5ICkge1xuXHRcdFx0XHRcdHNjcm9sbGVkID0gdGhpcy5kb2N1bWVudC5zY3JvbGxUb3AoIHRoaXMuZG9jdW1lbnQuc2Nyb2xsVG9wKCkgKyBvLnNjcm9sbFNwZWVkICk7XG5cdFx0XHRcdH1cblxuXHRcdFx0XHRpZiAoIGV2ZW50LnBhZ2VYIC0gdGhpcy5kb2N1bWVudC5zY3JvbGxMZWZ0KCkgPCBvLnNjcm9sbFNlbnNpdGl2aXR5ICkge1xuXHRcdFx0XHRcdHNjcm9sbGVkID0gdGhpcy5kb2N1bWVudC5zY3JvbGxMZWZ0KFxuXHRcdFx0XHRcdFx0dGhpcy5kb2N1bWVudC5zY3JvbGxMZWZ0KCkgLSBvLnNjcm9sbFNwZWVkXG5cdFx0XHRcdFx0KTtcblx0XHRcdFx0fSBlbHNlIGlmICggdGhpcy53aW5kb3cud2lkdGgoKSAtICggZXZlbnQucGFnZVggLSB0aGlzLmRvY3VtZW50LnNjcm9sbExlZnQoKSApIDxcblx0XHRcdFx0XHRcdG8uc2Nyb2xsU2Vuc2l0aXZpdHkgKSB7XG5cdFx0XHRcdFx0c2Nyb2xsZWQgPSB0aGlzLmRvY3VtZW50LnNjcm9sbExlZnQoXG5cdFx0XHRcdFx0XHR0aGlzLmRvY3VtZW50LnNjcm9sbExlZnQoKSArIG8uc2Nyb2xsU3BlZWRcblx0XHRcdFx0XHQpO1xuXHRcdFx0XHR9XG5cblx0XHRcdH1cblxuXHRcdFx0aWYgKCBzY3JvbGxlZCAhPT0gZmFsc2UgJiYgJC51aS5kZG1hbmFnZXIgJiYgIW8uZHJvcEJlaGF2aW91ciApIHtcblx0XHRcdFx0JC51aS5kZG1hbmFnZXIucHJlcGFyZU9mZnNldHMoIHRoaXMsIGV2ZW50ICk7XG5cdFx0XHR9XG5cdFx0fVxuXG5cdFx0Ly9SZWdlbmVyYXRlIHRoZSBhYnNvbHV0ZSBwb3NpdGlvbiB1c2VkIGZvciBwb3NpdGlvbiBjaGVja3Ncblx0XHR0aGlzLnBvc2l0aW9uQWJzID0gdGhpcy5fY29udmVydFBvc2l0aW9uVG8oIFwiYWJzb2x1dGVcIiApO1xuXG5cdFx0Ly9TZXQgdGhlIGhlbHBlciBwb3NpdGlvblxuXHRcdGlmICggIXRoaXMub3B0aW9ucy5heGlzIHx8IHRoaXMub3B0aW9ucy5heGlzICE9PSBcInlcIiApIHtcblx0XHRcdHRoaXMuaGVscGVyWyAwIF0uc3R5bGUubGVmdCA9IHRoaXMucG9zaXRpb24ubGVmdCArIFwicHhcIjtcblx0XHR9XG5cdFx0aWYgKCAhdGhpcy5vcHRpb25zLmF4aXMgfHwgdGhpcy5vcHRpb25zLmF4aXMgIT09IFwieFwiICkge1xuXHRcdFx0dGhpcy5oZWxwZXJbIDAgXS5zdHlsZS50b3AgPSB0aGlzLnBvc2l0aW9uLnRvcCArIFwicHhcIjtcblx0XHR9XG5cblx0XHQvL1JlYXJyYW5nZVxuXHRcdGZvciAoIGkgPSB0aGlzLml0ZW1zLmxlbmd0aCAtIDE7IGkgPj0gMDsgaS0tICkge1xuXG5cdFx0XHQvL0NhY2hlIHZhcmlhYmxlcyBhbmQgaW50ZXJzZWN0aW9uLCBjb250aW51ZSBpZiBubyBpbnRlcnNlY3Rpb25cblx0XHRcdGl0ZW0gPSB0aGlzLml0ZW1zWyBpIF07XG5cdFx0XHRpdGVtRWxlbWVudCA9IGl0ZW0uaXRlbVsgMCBdO1xuXHRcdFx0aW50ZXJzZWN0aW9uID0gdGhpcy5faW50ZXJzZWN0c1dpdGhQb2ludGVyKCBpdGVtICk7XG5cdFx0XHRpZiAoICFpbnRlcnNlY3Rpb24gKSB7XG5cdFx0XHRcdGNvbnRpbnVlO1xuXHRcdFx0fVxuXG5cdFx0XHQvLyBPbmx5IHB1dCB0aGUgcGxhY2Vob2xkZXIgaW5zaWRlIHRoZSBjdXJyZW50IENvbnRhaW5lciwgc2tpcCBhbGxcblx0XHRcdC8vIGl0ZW1zIGZyb20gb3RoZXIgY29udGFpbmVycy4gVGhpcyB3b3JrcyBiZWNhdXNlIHdoZW4gbW92aW5nXG5cdFx0XHQvLyBhbiBpdGVtIGZyb20gb25lIGNvbnRhaW5lciB0byBhbm90aGVyIHRoZVxuXHRcdFx0Ly8gY3VycmVudENvbnRhaW5lciBpcyBzd2l0Y2hlZCBiZWZvcmUgdGhlIHBsYWNlaG9sZGVyIGlzIG1vdmVkLlxuXHRcdFx0Ly9cblx0XHRcdC8vIFdpdGhvdXQgdGhpcywgbW92aW5nIGl0ZW1zIGluIFwic3ViLXNvcnRhYmxlc1wiIGNhbiBjYXVzZVxuXHRcdFx0Ly8gdGhlIHBsYWNlaG9sZGVyIHRvIGppdHRlciBiZXR3ZWVuIHRoZSBvdXRlciBhbmQgaW5uZXIgY29udGFpbmVyLlxuXHRcdFx0aWYgKCBpdGVtLmluc3RhbmNlICE9PSB0aGlzLmN1cnJlbnRDb250YWluZXIgKSB7XG5cdFx0XHRcdGNvbnRpbnVlO1xuXHRcdFx0fVxuXG5cdFx0XHQvLyBDYW5ub3QgaW50ZXJzZWN0IHdpdGggaXRzZWxmXG5cdFx0XHQvLyBubyB1c2VsZXNzIGFjdGlvbnMgdGhhdCBoYXZlIGJlZW4gZG9uZSBiZWZvcmVcblx0XHRcdC8vIG5vIGFjdGlvbiBpZiB0aGUgaXRlbSBtb3ZlZCBpcyB0aGUgcGFyZW50IG9mIHRoZSBpdGVtIGNoZWNrZWRcblx0XHRcdGlmICggaXRlbUVsZW1lbnQgIT09IHRoaXMuY3VycmVudEl0ZW1bIDAgXSAmJlxuXHRcdFx0XHR0aGlzLnBsYWNlaG9sZGVyWyBpbnRlcnNlY3Rpb24gPT09IDEgPyBcIm5leHRcIiA6IFwicHJldlwiIF0oKVsgMCBdICE9PSBpdGVtRWxlbWVudCAmJlxuXHRcdFx0XHQhJC5jb250YWlucyggdGhpcy5wbGFjZWhvbGRlclsgMCBdLCBpdGVtRWxlbWVudCApICYmXG5cdFx0XHRcdCggdGhpcy5vcHRpb25zLnR5cGUgPT09IFwic2VtaS1keW5hbWljXCIgP1xuXHRcdFx0XHRcdCEkLmNvbnRhaW5zKCB0aGlzLmVsZW1lbnRbIDAgXSwgaXRlbUVsZW1lbnQgKSA6XG5cdFx0XHRcdFx0dHJ1ZVxuXHRcdFx0XHQpXG5cdFx0XHQpIHtcblxuXHRcdFx0XHR0aGlzLmRpcmVjdGlvbiA9IGludGVyc2VjdGlvbiA9PT0gMSA/IFwiZG93blwiIDogXCJ1cFwiO1xuXG5cdFx0XHRcdGlmICggdGhpcy5vcHRpb25zLnRvbGVyYW5jZSA9PT0gXCJwb2ludGVyXCIgfHwgdGhpcy5faW50ZXJzZWN0c1dpdGhTaWRlcyggaXRlbSApICkge1xuXHRcdFx0XHRcdHRoaXMuX3JlYXJyYW5nZSggZXZlbnQsIGl0ZW0gKTtcblx0XHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0XHRicmVhaztcblx0XHRcdFx0fVxuXG5cdFx0XHRcdHRoaXMuX3RyaWdnZXIoIFwiY2hhbmdlXCIsIGV2ZW50LCB0aGlzLl91aUhhc2goKSApO1xuXHRcdFx0XHRicmVhaztcblx0XHRcdH1cblx0XHR9XG5cblx0XHQvL1Bvc3QgZXZlbnRzIHRvIGNvbnRhaW5lcnNcblx0XHR0aGlzLl9jb250YWN0Q29udGFpbmVycyggZXZlbnQgKTtcblxuXHRcdC8vSW50ZXJjb25uZWN0IHdpdGggZHJvcHBhYmxlc1xuXHRcdGlmICggJC51aS5kZG1hbmFnZXIgKSB7XG5cdFx0XHQkLnVpLmRkbWFuYWdlci5kcmFnKCB0aGlzLCBldmVudCApO1xuXHRcdH1cblxuXHRcdC8vQ2FsbCBjYWxsYmFja3Ncblx0XHR0aGlzLl90cmlnZ2VyKCBcInNvcnRcIiwgZXZlbnQsIHRoaXMuX3VpSGFzaCgpICk7XG5cblx0XHR0aGlzLmxhc3RQb3NpdGlvbkFicyA9IHRoaXMucG9zaXRpb25BYnM7XG5cdFx0cmV0dXJuIGZhbHNlO1xuXG5cdH0sXG5cblx0X21vdXNlU3RvcDogZnVuY3Rpb24oIGV2ZW50LCBub1Byb3BhZ2F0aW9uICkge1xuXG5cdFx0aWYgKCAhZXZlbnQgKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXG5cdFx0Ly9JZiB3ZSBhcmUgdXNpbmcgZHJvcHBhYmxlcywgaW5mb3JtIHRoZSBtYW5hZ2VyIGFib3V0IHRoZSBkcm9wXG5cdFx0aWYgKCAkLnVpLmRkbWFuYWdlciAmJiAhdGhpcy5vcHRpb25zLmRyb3BCZWhhdmlvdXIgKSB7XG5cdFx0XHQkLnVpLmRkbWFuYWdlci5kcm9wKCB0aGlzLCBldmVudCApO1xuXHRcdH1cblxuXHRcdGlmICggdGhpcy5vcHRpb25zLnJldmVydCApIHtcblx0XHRcdHZhciB0aGF0ID0gdGhpcyxcblx0XHRcdFx0Y3VyID0gdGhpcy5wbGFjZWhvbGRlci5vZmZzZXQoKSxcblx0XHRcdFx0YXhpcyA9IHRoaXMub3B0aW9ucy5heGlzLFxuXHRcdFx0XHRhbmltYXRpb24gPSB7fTtcblxuXHRcdFx0aWYgKCAhYXhpcyB8fCBheGlzID09PSBcInhcIiApIHtcblx0XHRcdFx0YW5pbWF0aW9uLmxlZnQgPSBjdXIubGVmdCAtIHRoaXMub2Zmc2V0LnBhcmVudC5sZWZ0IC0gdGhpcy5tYXJnaW5zLmxlZnQgK1xuXHRcdFx0XHRcdCggdGhpcy5vZmZzZXRQYXJlbnRbIDAgXSA9PT0gdGhpcy5kb2N1bWVudFsgMCBdLmJvZHkgP1xuXHRcdFx0XHRcdFx0MCA6XG5cdFx0XHRcdFx0XHR0aGlzLm9mZnNldFBhcmVudFsgMCBdLnNjcm9sbExlZnRcblx0XHRcdFx0XHQpO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCAhYXhpcyB8fCBheGlzID09PSBcInlcIiApIHtcblx0XHRcdFx0YW5pbWF0aW9uLnRvcCA9IGN1ci50b3AgLSB0aGlzLm9mZnNldC5wYXJlbnQudG9wIC0gdGhpcy5tYXJnaW5zLnRvcCArXG5cdFx0XHRcdFx0KCB0aGlzLm9mZnNldFBhcmVudFsgMCBdID09PSB0aGlzLmRvY3VtZW50WyAwIF0uYm9keSA/XG5cdFx0XHRcdFx0XHQwIDpcblx0XHRcdFx0XHRcdHRoaXMub2Zmc2V0UGFyZW50WyAwIF0uc2Nyb2xsVG9wXG5cdFx0XHRcdFx0KTtcblx0XHRcdH1cblx0XHRcdHRoaXMucmV2ZXJ0aW5nID0gdHJ1ZTtcblx0XHRcdCQoIHRoaXMuaGVscGVyICkuYW5pbWF0ZShcblx0XHRcdFx0YW5pbWF0aW9uLFxuXHRcdFx0XHRwYXJzZUludCggdGhpcy5vcHRpb25zLnJldmVydCwgMTAgKSB8fCA1MDAsXG5cdFx0XHRcdGZ1bmN0aW9uKCkge1xuXHRcdFx0XHRcdHRoYXQuX2NsZWFyKCBldmVudCApO1xuXHRcdFx0XHR9XG5cdFx0XHQpO1xuXHRcdH0gZWxzZSB7XG5cdFx0XHR0aGlzLl9jbGVhciggZXZlbnQsIG5vUHJvcGFnYXRpb24gKTtcblx0XHR9XG5cblx0XHRyZXR1cm4gZmFsc2U7XG5cblx0fSxcblxuXHRjYW5jZWw6IGZ1bmN0aW9uKCkge1xuXG5cdFx0aWYgKCB0aGlzLmRyYWdnaW5nICkge1xuXG5cdFx0XHR0aGlzLl9tb3VzZVVwKCBuZXcgJC5FdmVudCggXCJtb3VzZXVwXCIsIHsgdGFyZ2V0OiBudWxsIH0gKSApO1xuXG5cdFx0XHRpZiAoIHRoaXMub3B0aW9ucy5oZWxwZXIgPT09IFwib3JpZ2luYWxcIiApIHtcblx0XHRcdFx0dGhpcy5jdXJyZW50SXRlbS5jc3MoIHRoaXMuX3N0b3JlZENTUyApO1xuXHRcdFx0XHR0aGlzLl9yZW1vdmVDbGFzcyggdGhpcy5jdXJyZW50SXRlbSwgXCJ1aS1zb3J0YWJsZS1oZWxwZXJcIiApO1xuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0dGhpcy5jdXJyZW50SXRlbS5zaG93KCk7XG5cdFx0XHR9XG5cblx0XHRcdC8vUG9zdCBkZWFjdGl2YXRpbmcgZXZlbnRzIHRvIGNvbnRhaW5lcnNcblx0XHRcdGZvciAoIHZhciBpID0gdGhpcy5jb250YWluZXJzLmxlbmd0aCAtIDE7IGkgPj0gMDsgaS0tICkge1xuXHRcdFx0XHR0aGlzLmNvbnRhaW5lcnNbIGkgXS5fdHJpZ2dlciggXCJkZWFjdGl2YXRlXCIsIG51bGwsIHRoaXMuX3VpSGFzaCggdGhpcyApICk7XG5cdFx0XHRcdGlmICggdGhpcy5jb250YWluZXJzWyBpIF0uY29udGFpbmVyQ2FjaGUub3ZlciApIHtcblx0XHRcdFx0XHR0aGlzLmNvbnRhaW5lcnNbIGkgXS5fdHJpZ2dlciggXCJvdXRcIiwgbnVsbCwgdGhpcy5fdWlIYXNoKCB0aGlzICkgKTtcblx0XHRcdFx0XHR0aGlzLmNvbnRhaW5lcnNbIGkgXS5jb250YWluZXJDYWNoZS5vdmVyID0gMDtcblx0XHRcdFx0fVxuXHRcdFx0fVxuXG5cdFx0fVxuXG5cdFx0aWYgKCB0aGlzLnBsYWNlaG9sZGVyICkge1xuXG5cdFx0XHQvLyQodGhpcy5wbGFjZWhvbGRlclswXSkucmVtb3ZlKCk7IHdvdWxkIGhhdmUgYmVlbiB0aGUgalF1ZXJ5IHdheSAtIHVuZm9ydHVuYXRlbHksXG5cdFx0XHQvLyBpdCB1bmJpbmRzIEFMTCBldmVudHMgZnJvbSB0aGUgb3JpZ2luYWwgbm9kZSFcblx0XHRcdGlmICggdGhpcy5wbGFjZWhvbGRlclsgMCBdLnBhcmVudE5vZGUgKSB7XG5cdFx0XHRcdHRoaXMucGxhY2Vob2xkZXJbIDAgXS5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKCB0aGlzLnBsYWNlaG9sZGVyWyAwIF0gKTtcblx0XHRcdH1cblx0XHRcdGlmICggdGhpcy5vcHRpb25zLmhlbHBlciAhPT0gXCJvcmlnaW5hbFwiICYmIHRoaXMuaGVscGVyICYmXG5cdFx0XHRcdFx0dGhpcy5oZWxwZXJbIDAgXS5wYXJlbnROb2RlICkge1xuXHRcdFx0XHR0aGlzLmhlbHBlci5yZW1vdmUoKTtcblx0XHRcdH1cblxuXHRcdFx0JC5leHRlbmQoIHRoaXMsIHtcblx0XHRcdFx0aGVscGVyOiBudWxsLFxuXHRcdFx0XHRkcmFnZ2luZzogZmFsc2UsXG5cdFx0XHRcdHJldmVydGluZzogZmFsc2UsXG5cdFx0XHRcdF9ub0ZpbmFsU29ydDogbnVsbFxuXHRcdFx0fSApO1xuXG5cdFx0XHRpZiAoIHRoaXMuZG9tUG9zaXRpb24ucHJldiApIHtcblx0XHRcdFx0JCggdGhpcy5kb21Qb3NpdGlvbi5wcmV2ICkuYWZ0ZXIoIHRoaXMuY3VycmVudEl0ZW0gKTtcblx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdCQoIHRoaXMuZG9tUG9zaXRpb24ucGFyZW50ICkucHJlcGVuZCggdGhpcy5jdXJyZW50SXRlbSApO1xuXHRcdFx0fVxuXHRcdH1cblxuXHRcdHJldHVybiB0aGlzO1xuXG5cdH0sXG5cblx0c2VyaWFsaXplOiBmdW5jdGlvbiggbyApIHtcblxuXHRcdHZhciBpdGVtcyA9IHRoaXMuX2dldEl0ZW1zQXNqUXVlcnkoIG8gJiYgby5jb25uZWN0ZWQgKSxcblx0XHRcdHN0ciA9IFtdO1xuXHRcdG8gPSBvIHx8IHt9O1xuXG5cdFx0JCggaXRlbXMgKS5lYWNoKCBmdW5jdGlvbigpIHtcblx0XHRcdHZhciByZXMgPSAoICQoIG8uaXRlbSB8fCB0aGlzICkuYXR0ciggby5hdHRyaWJ1dGUgfHwgXCJpZFwiICkgfHwgXCJcIiApXG5cdFx0XHRcdC5tYXRjaCggby5leHByZXNzaW9uIHx8ICggLyguKylbXFwtPV9dKC4rKS8gKSApO1xuXHRcdFx0aWYgKCByZXMgKSB7XG5cdFx0XHRcdHN0ci5wdXNoKFxuXHRcdFx0XHRcdCggby5rZXkgfHwgcmVzWyAxIF0gKyBcIltdXCIgKSArXG5cdFx0XHRcdFx0XCI9XCIgKyAoIG8ua2V5ICYmIG8uZXhwcmVzc2lvbiA/IHJlc1sgMSBdIDogcmVzWyAyIF0gKSApO1xuXHRcdFx0fVxuXHRcdH0gKTtcblxuXHRcdGlmICggIXN0ci5sZW5ndGggJiYgby5rZXkgKSB7XG5cdFx0XHRzdHIucHVzaCggby5rZXkgKyBcIj1cIiApO1xuXHRcdH1cblxuXHRcdHJldHVybiBzdHIuam9pbiggXCImXCIgKTtcblxuXHR9LFxuXG5cdHRvQXJyYXk6IGZ1bmN0aW9uKCBvICkge1xuXG5cdFx0dmFyIGl0ZW1zID0gdGhpcy5fZ2V0SXRlbXNBc2pRdWVyeSggbyAmJiBvLmNvbm5lY3RlZCApLFxuXHRcdFx0cmV0ID0gW107XG5cblx0XHRvID0gbyB8fCB7fTtcblxuXHRcdGl0ZW1zLmVhY2goIGZ1bmN0aW9uKCkge1xuXHRcdFx0cmV0LnB1c2goICQoIG8uaXRlbSB8fCB0aGlzICkuYXR0ciggby5hdHRyaWJ1dGUgfHwgXCJpZFwiICkgfHwgXCJcIiApO1xuXHRcdH0gKTtcblx0XHRyZXR1cm4gcmV0O1xuXG5cdH0sXG5cblx0LyogQmUgY2FyZWZ1bCB3aXRoIHRoZSBmb2xsb3dpbmcgY29yZSBmdW5jdGlvbnMgKi9cblx0X2ludGVyc2VjdHNXaXRoOiBmdW5jdGlvbiggaXRlbSApIHtcblxuXHRcdHZhciB4MSA9IHRoaXMucG9zaXRpb25BYnMubGVmdCxcblx0XHRcdHgyID0geDEgKyB0aGlzLmhlbHBlclByb3BvcnRpb25zLndpZHRoLFxuXHRcdFx0eTEgPSB0aGlzLnBvc2l0aW9uQWJzLnRvcCxcblx0XHRcdHkyID0geTEgKyB0aGlzLmhlbHBlclByb3BvcnRpb25zLmhlaWdodCxcblx0XHRcdGwgPSBpdGVtLmxlZnQsXG5cdFx0XHRyID0gbCArIGl0ZW0ud2lkdGgsXG5cdFx0XHR0ID0gaXRlbS50b3AsXG5cdFx0XHRiID0gdCArIGl0ZW0uaGVpZ2h0LFxuXHRcdFx0ZHlDbGljayA9IHRoaXMub2Zmc2V0LmNsaWNrLnRvcCxcblx0XHRcdGR4Q2xpY2sgPSB0aGlzLm9mZnNldC5jbGljay5sZWZ0LFxuXHRcdFx0aXNPdmVyRWxlbWVudEhlaWdodCA9ICggdGhpcy5vcHRpb25zLmF4aXMgPT09IFwieFwiICkgfHwgKCAoIHkxICsgZHlDbGljayApID4gdCAmJlxuXHRcdFx0XHQoIHkxICsgZHlDbGljayApIDwgYiApLFxuXHRcdFx0aXNPdmVyRWxlbWVudFdpZHRoID0gKCB0aGlzLm9wdGlvbnMuYXhpcyA9PT0gXCJ5XCIgKSB8fCAoICggeDEgKyBkeENsaWNrICkgPiBsICYmXG5cdFx0XHRcdCggeDEgKyBkeENsaWNrICkgPCByICksXG5cdFx0XHRpc092ZXJFbGVtZW50ID0gaXNPdmVyRWxlbWVudEhlaWdodCAmJiBpc092ZXJFbGVtZW50V2lkdGg7XG5cblx0XHRpZiAoIHRoaXMub3B0aW9ucy50b2xlcmFuY2UgPT09IFwicG9pbnRlclwiIHx8XG5cdFx0XHR0aGlzLm9wdGlvbnMuZm9yY2VQb2ludGVyRm9yQ29udGFpbmVycyB8fFxuXHRcdFx0KCB0aGlzLm9wdGlvbnMudG9sZXJhbmNlICE9PSBcInBvaW50ZXJcIiAmJlxuXHRcdFx0XHR0aGlzLmhlbHBlclByb3BvcnRpb25zWyB0aGlzLmZsb2F0aW5nID8gXCJ3aWR0aFwiIDogXCJoZWlnaHRcIiBdID5cblx0XHRcdFx0aXRlbVsgdGhpcy5mbG9hdGluZyA/IFwid2lkdGhcIiA6IFwiaGVpZ2h0XCIgXSApXG5cdFx0KSB7XG5cdFx0XHRyZXR1cm4gaXNPdmVyRWxlbWVudDtcblx0XHR9IGVsc2Uge1xuXG5cdFx0XHRyZXR1cm4gKCBsIDwgeDEgKyAoIHRoaXMuaGVscGVyUHJvcG9ydGlvbnMud2lkdGggLyAyICkgJiYgLy8gUmlnaHQgSGFsZlxuXHRcdFx0XHR4MiAtICggdGhpcy5oZWxwZXJQcm9wb3J0aW9ucy53aWR0aCAvIDIgKSA8IHIgJiYgLy8gTGVmdCBIYWxmXG5cdFx0XHRcdHQgPCB5MSArICggdGhpcy5oZWxwZXJQcm9wb3J0aW9ucy5oZWlnaHQgLyAyICkgJiYgLy8gQm90dG9tIEhhbGZcblx0XHRcdFx0eTIgLSAoIHRoaXMuaGVscGVyUHJvcG9ydGlvbnMuaGVpZ2h0IC8gMiApIDwgYiApOyAvLyBUb3AgSGFsZlxuXG5cdFx0fVxuXHR9LFxuXG5cdF9pbnRlcnNlY3RzV2l0aFBvaW50ZXI6IGZ1bmN0aW9uKCBpdGVtICkge1xuXHRcdHZhciB2ZXJ0aWNhbERpcmVjdGlvbiwgaG9yaXpvbnRhbERpcmVjdGlvbixcblx0XHRcdGlzT3ZlckVsZW1lbnRIZWlnaHQgPSAoIHRoaXMub3B0aW9ucy5heGlzID09PSBcInhcIiApIHx8XG5cdFx0XHRcdHRoaXMuX2lzT3ZlckF4aXMoXG5cdFx0XHRcdFx0dGhpcy5wb3NpdGlvbkFicy50b3AgKyB0aGlzLm9mZnNldC5jbGljay50b3AsIGl0ZW0udG9wLCBpdGVtLmhlaWdodCApLFxuXHRcdFx0aXNPdmVyRWxlbWVudFdpZHRoID0gKCB0aGlzLm9wdGlvbnMuYXhpcyA9PT0gXCJ5XCIgKSB8fFxuXHRcdFx0XHR0aGlzLl9pc092ZXJBeGlzKFxuXHRcdFx0XHRcdHRoaXMucG9zaXRpb25BYnMubGVmdCArIHRoaXMub2Zmc2V0LmNsaWNrLmxlZnQsIGl0ZW0ubGVmdCwgaXRlbS53aWR0aCApLFxuXHRcdFx0aXNPdmVyRWxlbWVudCA9IGlzT3ZlckVsZW1lbnRIZWlnaHQgJiYgaXNPdmVyRWxlbWVudFdpZHRoO1xuXG5cdFx0aWYgKCAhaXNPdmVyRWxlbWVudCApIHtcblx0XHRcdHJldHVybiBmYWxzZTtcblx0XHR9XG5cblx0XHR2ZXJ0aWNhbERpcmVjdGlvbiA9IHRoaXMuX2dldERyYWdWZXJ0aWNhbERpcmVjdGlvbigpO1xuXHRcdGhvcml6b250YWxEaXJlY3Rpb24gPSB0aGlzLl9nZXREcmFnSG9yaXpvbnRhbERpcmVjdGlvbigpO1xuXG5cdFx0cmV0dXJuIHRoaXMuZmxvYXRpbmcgP1xuXHRcdFx0KCAoIGhvcml6b250YWxEaXJlY3Rpb24gPT09IFwicmlnaHRcIiB8fCB2ZXJ0aWNhbERpcmVjdGlvbiA9PT0gXCJkb3duXCIgKSA/IDIgOiAxIClcblx0XHRcdDogKCB2ZXJ0aWNhbERpcmVjdGlvbiAmJiAoIHZlcnRpY2FsRGlyZWN0aW9uID09PSBcImRvd25cIiA/IDIgOiAxICkgKTtcblxuXHR9LFxuXG5cdF9pbnRlcnNlY3RzV2l0aFNpZGVzOiBmdW5jdGlvbiggaXRlbSApIHtcblxuXHRcdHZhciBpc092ZXJCb3R0b21IYWxmID0gdGhpcy5faXNPdmVyQXhpcyggdGhpcy5wb3NpdGlvbkFicy50b3AgK1xuXHRcdFx0XHR0aGlzLm9mZnNldC5jbGljay50b3AsIGl0ZW0udG9wICsgKCBpdGVtLmhlaWdodCAvIDIgKSwgaXRlbS5oZWlnaHQgKSxcblx0XHRcdGlzT3ZlclJpZ2h0SGFsZiA9IHRoaXMuX2lzT3ZlckF4aXMoIHRoaXMucG9zaXRpb25BYnMubGVmdCArXG5cdFx0XHRcdHRoaXMub2Zmc2V0LmNsaWNrLmxlZnQsIGl0ZW0ubGVmdCArICggaXRlbS53aWR0aCAvIDIgKSwgaXRlbS53aWR0aCApLFxuXHRcdFx0dmVydGljYWxEaXJlY3Rpb24gPSB0aGlzLl9nZXREcmFnVmVydGljYWxEaXJlY3Rpb24oKSxcblx0XHRcdGhvcml6b250YWxEaXJlY3Rpb24gPSB0aGlzLl9nZXREcmFnSG9yaXpvbnRhbERpcmVjdGlvbigpO1xuXG5cdFx0aWYgKCB0aGlzLmZsb2F0aW5nICYmIGhvcml6b250YWxEaXJlY3Rpb24gKSB7XG5cdFx0XHRyZXR1cm4gKCAoIGhvcml6b250YWxEaXJlY3Rpb24gPT09IFwicmlnaHRcIiAmJiBpc092ZXJSaWdodEhhbGYgKSB8fFxuXHRcdFx0XHQoIGhvcml6b250YWxEaXJlY3Rpb24gPT09IFwibGVmdFwiICYmICFpc092ZXJSaWdodEhhbGYgKSApO1xuXHRcdH0gZWxzZSB7XG5cdFx0XHRyZXR1cm4gdmVydGljYWxEaXJlY3Rpb24gJiYgKCAoIHZlcnRpY2FsRGlyZWN0aW9uID09PSBcImRvd25cIiAmJiBpc092ZXJCb3R0b21IYWxmICkgfHxcblx0XHRcdFx0KCB2ZXJ0aWNhbERpcmVjdGlvbiA9PT0gXCJ1cFwiICYmICFpc092ZXJCb3R0b21IYWxmICkgKTtcblx0XHR9XG5cblx0fSxcblxuXHRfZ2V0RHJhZ1ZlcnRpY2FsRGlyZWN0aW9uOiBmdW5jdGlvbigpIHtcblx0XHR2YXIgZGVsdGEgPSB0aGlzLnBvc2l0aW9uQWJzLnRvcCAtIHRoaXMubGFzdFBvc2l0aW9uQWJzLnRvcDtcblx0XHRyZXR1cm4gZGVsdGEgIT09IDAgJiYgKCBkZWx0YSA+IDAgPyBcImRvd25cIiA6IFwidXBcIiApO1xuXHR9LFxuXG5cdF9nZXREcmFnSG9yaXpvbnRhbERpcmVjdGlvbjogZnVuY3Rpb24oKSB7XG5cdFx0dmFyIGRlbHRhID0gdGhpcy5wb3NpdGlvbkFicy5sZWZ0IC0gdGhpcy5sYXN0UG9zaXRpb25BYnMubGVmdDtcblx0XHRyZXR1cm4gZGVsdGEgIT09IDAgJiYgKCBkZWx0YSA+IDAgPyBcInJpZ2h0XCIgOiBcImxlZnRcIiApO1xuXHR9LFxuXG5cdHJlZnJlc2g6IGZ1bmN0aW9uKCBldmVudCApIHtcblx0XHR0aGlzLl9yZWZyZXNoSXRlbXMoIGV2ZW50ICk7XG5cdFx0dGhpcy5fc2V0SGFuZGxlQ2xhc3NOYW1lKCk7XG5cdFx0dGhpcy5yZWZyZXNoUG9zaXRpb25zKCk7XG5cdFx0cmV0dXJuIHRoaXM7XG5cdH0sXG5cblx0X2Nvbm5lY3RXaXRoOiBmdW5jdGlvbigpIHtcblx0XHR2YXIgb3B0aW9ucyA9IHRoaXMub3B0aW9ucztcblx0XHRyZXR1cm4gb3B0aW9ucy5jb25uZWN0V2l0aC5jb25zdHJ1Y3RvciA9PT0gU3RyaW5nID9cblx0XHRcdFsgb3B0aW9ucy5jb25uZWN0V2l0aCBdIDpcblx0XHRcdG9wdGlvbnMuY29ubmVjdFdpdGg7XG5cdH0sXG5cblx0X2dldEl0ZW1zQXNqUXVlcnk6IGZ1bmN0aW9uKCBjb25uZWN0ZWQgKSB7XG5cblx0XHR2YXIgaSwgaiwgY3VyLCBpbnN0LFxuXHRcdFx0aXRlbXMgPSBbXSxcblx0XHRcdHF1ZXJpZXMgPSBbXSxcblx0XHRcdGNvbm5lY3RXaXRoID0gdGhpcy5fY29ubmVjdFdpdGgoKTtcblxuXHRcdGlmICggY29ubmVjdFdpdGggJiYgY29ubmVjdGVkICkge1xuXHRcdFx0Zm9yICggaSA9IGNvbm5lY3RXaXRoLmxlbmd0aCAtIDE7IGkgPj0gMDsgaS0tICkge1xuXHRcdFx0XHRjdXIgPSAkKCBjb25uZWN0V2l0aFsgaSBdLCB0aGlzLmRvY3VtZW50WyAwIF0gKTtcblx0XHRcdFx0Zm9yICggaiA9IGN1ci5sZW5ndGggLSAxOyBqID49IDA7IGotLSApIHtcblx0XHRcdFx0XHRpbnN0ID0gJC5kYXRhKCBjdXJbIGogXSwgdGhpcy53aWRnZXRGdWxsTmFtZSApO1xuXHRcdFx0XHRcdGlmICggaW5zdCAmJiBpbnN0ICE9PSB0aGlzICYmICFpbnN0Lm9wdGlvbnMuZGlzYWJsZWQgKSB7XG5cdFx0XHRcdFx0XHRxdWVyaWVzLnB1c2goIFsgJC5pc0Z1bmN0aW9uKCBpbnN0Lm9wdGlvbnMuaXRlbXMgKSA/XG5cdFx0XHRcdFx0XHRcdGluc3Qub3B0aW9ucy5pdGVtcy5jYWxsKCBpbnN0LmVsZW1lbnQgKSA6XG5cdFx0XHRcdFx0XHRcdCQoIGluc3Qub3B0aW9ucy5pdGVtcywgaW5zdC5lbGVtZW50IClcblx0XHRcdFx0XHRcdFx0XHQubm90KCBcIi51aS1zb3J0YWJsZS1oZWxwZXJcIiApXG5cdFx0XHRcdFx0XHRcdFx0Lm5vdCggXCIudWktc29ydGFibGUtcGxhY2Vob2xkZXJcIiApLCBpbnN0IF0gKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdH1cblx0XHRcdH1cblx0XHR9XG5cblx0XHRxdWVyaWVzLnB1c2goIFsgJC5pc0Z1bmN0aW9uKCB0aGlzLm9wdGlvbnMuaXRlbXMgKSA/XG5cdFx0XHR0aGlzLm9wdGlvbnMuaXRlbXNcblx0XHRcdFx0LmNhbGwoIHRoaXMuZWxlbWVudCwgbnVsbCwgeyBvcHRpb25zOiB0aGlzLm9wdGlvbnMsIGl0ZW06IHRoaXMuY3VycmVudEl0ZW0gfSApIDpcblx0XHRcdCQoIHRoaXMub3B0aW9ucy5pdGVtcywgdGhpcy5lbGVtZW50IClcblx0XHRcdFx0Lm5vdCggXCIudWktc29ydGFibGUtaGVscGVyXCIgKVxuXHRcdFx0XHQubm90KCBcIi51aS1zb3J0YWJsZS1wbGFjZWhvbGRlclwiICksIHRoaXMgXSApO1xuXG5cdFx0ZnVuY3Rpb24gYWRkSXRlbXMoKSB7XG5cdFx0XHRpdGVtcy5wdXNoKCB0aGlzICk7XG5cdFx0fVxuXHRcdGZvciAoIGkgPSBxdWVyaWVzLmxlbmd0aCAtIDE7IGkgPj0gMDsgaS0tICkge1xuXHRcdFx0cXVlcmllc1sgaSBdWyAwIF0uZWFjaCggYWRkSXRlbXMgKTtcblx0XHR9XG5cblx0XHRyZXR1cm4gJCggaXRlbXMgKTtcblxuXHR9LFxuXG5cdF9yZW1vdmVDdXJyZW50c0Zyb21JdGVtczogZnVuY3Rpb24oKSB7XG5cblx0XHR2YXIgbGlzdCA9IHRoaXMuY3VycmVudEl0ZW0uZmluZCggXCI6ZGF0YShcIiArIHRoaXMud2lkZ2V0TmFtZSArIFwiLWl0ZW0pXCIgKTtcblxuXHRcdHRoaXMuaXRlbXMgPSAkLmdyZXAoIHRoaXMuaXRlbXMsIGZ1bmN0aW9uKCBpdGVtICkge1xuXHRcdFx0Zm9yICggdmFyIGogPSAwOyBqIDwgbGlzdC5sZW5ndGg7IGorKyApIHtcblx0XHRcdFx0aWYgKCBsaXN0WyBqIF0gPT09IGl0ZW0uaXRlbVsgMCBdICkge1xuXHRcdFx0XHRcdHJldHVybiBmYWxzZTtcblx0XHRcdFx0fVxuXHRcdFx0fVxuXHRcdFx0cmV0dXJuIHRydWU7XG5cdFx0fSApO1xuXG5cdH0sXG5cblx0X3JlZnJlc2hJdGVtczogZnVuY3Rpb24oIGV2ZW50ICkge1xuXG5cdFx0dGhpcy5pdGVtcyA9IFtdO1xuXHRcdHRoaXMuY29udGFpbmVycyA9IFsgdGhpcyBdO1xuXG5cdFx0dmFyIGksIGosIGN1ciwgaW5zdCwgdGFyZ2V0RGF0YSwgX3F1ZXJpZXMsIGl0ZW0sIHF1ZXJpZXNMZW5ndGgsXG5cdFx0XHRpdGVtcyA9IHRoaXMuaXRlbXMsXG5cdFx0XHRxdWVyaWVzID0gWyBbICQuaXNGdW5jdGlvbiggdGhpcy5vcHRpb25zLml0ZW1zICkgP1xuXHRcdFx0XHR0aGlzLm9wdGlvbnMuaXRlbXMuY2FsbCggdGhpcy5lbGVtZW50WyAwIF0sIGV2ZW50LCB7IGl0ZW06IHRoaXMuY3VycmVudEl0ZW0gfSApIDpcblx0XHRcdFx0JCggdGhpcy5vcHRpb25zLml0ZW1zLCB0aGlzLmVsZW1lbnQgKSwgdGhpcyBdIF0sXG5cdFx0XHRjb25uZWN0V2l0aCA9IHRoaXMuX2Nvbm5lY3RXaXRoKCk7XG5cblx0XHQvL1Nob3VsZG4ndCBiZSBydW4gdGhlIGZpcnN0IHRpbWUgdGhyb3VnaCBkdWUgdG8gbWFzc2l2ZSBzbG93LWRvd25cblx0XHRpZiAoIGNvbm5lY3RXaXRoICYmIHRoaXMucmVhZHkgKSB7XG5cdFx0XHRmb3IgKCBpID0gY29ubmVjdFdpdGgubGVuZ3RoIC0gMTsgaSA+PSAwOyBpLS0gKSB7XG5cdFx0XHRcdGN1ciA9ICQoIGNvbm5lY3RXaXRoWyBpIF0sIHRoaXMuZG9jdW1lbnRbIDAgXSApO1xuXHRcdFx0XHRmb3IgKCBqID0gY3VyLmxlbmd0aCAtIDE7IGogPj0gMDsgai0tICkge1xuXHRcdFx0XHRcdGluc3QgPSAkLmRhdGEoIGN1clsgaiBdLCB0aGlzLndpZGdldEZ1bGxOYW1lICk7XG5cdFx0XHRcdFx0aWYgKCBpbnN0ICYmIGluc3QgIT09IHRoaXMgJiYgIWluc3Qub3B0aW9ucy5kaXNhYmxlZCApIHtcblx0XHRcdFx0XHRcdHF1ZXJpZXMucHVzaCggWyAkLmlzRnVuY3Rpb24oIGluc3Qub3B0aW9ucy5pdGVtcyApID9cblx0XHRcdFx0XHRcdFx0aW5zdC5vcHRpb25zLml0ZW1zXG5cdFx0XHRcdFx0XHRcdFx0LmNhbGwoIGluc3QuZWxlbWVudFsgMCBdLCBldmVudCwgeyBpdGVtOiB0aGlzLmN1cnJlbnRJdGVtIH0gKSA6XG5cdFx0XHRcdFx0XHRcdCQoIGluc3Qub3B0aW9ucy5pdGVtcywgaW5zdC5lbGVtZW50ICksIGluc3QgXSApO1xuXHRcdFx0XHRcdFx0dGhpcy5jb250YWluZXJzLnB1c2goIGluc3QgKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdH1cblx0XHRcdH1cblx0XHR9XG5cblx0XHRmb3IgKCBpID0gcXVlcmllcy5sZW5ndGggLSAxOyBpID49IDA7IGktLSApIHtcblx0XHRcdHRhcmdldERhdGEgPSBxdWVyaWVzWyBpIF1bIDEgXTtcblx0XHRcdF9xdWVyaWVzID0gcXVlcmllc1sgaSBdWyAwIF07XG5cblx0XHRcdGZvciAoIGogPSAwLCBxdWVyaWVzTGVuZ3RoID0gX3F1ZXJpZXMubGVuZ3RoOyBqIDwgcXVlcmllc0xlbmd0aDsgaisrICkge1xuXHRcdFx0XHRpdGVtID0gJCggX3F1ZXJpZXNbIGogXSApO1xuXG5cdFx0XHRcdC8vIERhdGEgZm9yIHRhcmdldCBjaGVja2luZyAobW91c2UgbWFuYWdlcilcblx0XHRcdFx0aXRlbS5kYXRhKCB0aGlzLndpZGdldE5hbWUgKyBcIi1pdGVtXCIsIHRhcmdldERhdGEgKTtcblxuXHRcdFx0XHRpdGVtcy5wdXNoKCB7XG5cdFx0XHRcdFx0aXRlbTogaXRlbSxcblx0XHRcdFx0XHRpbnN0YW5jZTogdGFyZ2V0RGF0YSxcblx0XHRcdFx0XHR3aWR0aDogMCwgaGVpZ2h0OiAwLFxuXHRcdFx0XHRcdGxlZnQ6IDAsIHRvcDogMFxuXHRcdFx0XHR9ICk7XG5cdFx0XHR9XG5cdFx0fVxuXG5cdH0sXG5cblx0cmVmcmVzaFBvc2l0aW9uczogZnVuY3Rpb24oIGZhc3QgKSB7XG5cblx0XHQvLyBEZXRlcm1pbmUgd2hldGhlciBpdGVtcyBhcmUgYmVpbmcgZGlzcGxheWVkIGhvcml6b250YWxseVxuXHRcdHRoaXMuZmxvYXRpbmcgPSB0aGlzLml0ZW1zLmxlbmd0aCA/XG5cdFx0XHR0aGlzLm9wdGlvbnMuYXhpcyA9PT0gXCJ4XCIgfHwgdGhpcy5faXNGbG9hdGluZyggdGhpcy5pdGVtc1sgMCBdLml0ZW0gKSA6XG5cdFx0XHRmYWxzZTtcblxuXHRcdC8vVGhpcyBoYXMgdG8gYmUgcmVkb25lIGJlY2F1c2UgZHVlIHRvIHRoZSBpdGVtIGJlaW5nIG1vdmVkIG91dC9pbnRvIHRoZSBvZmZzZXRQYXJlbnQsXG5cdFx0Ly8gdGhlIG9mZnNldFBhcmVudCdzIHBvc2l0aW9uIHdpbGwgY2hhbmdlXG5cdFx0aWYgKCB0aGlzLm9mZnNldFBhcmVudCAmJiB0aGlzLmhlbHBlciApIHtcblx0XHRcdHRoaXMub2Zmc2V0LnBhcmVudCA9IHRoaXMuX2dldFBhcmVudE9mZnNldCgpO1xuXHRcdH1cblxuXHRcdHZhciBpLCBpdGVtLCB0LCBwO1xuXG5cdFx0Zm9yICggaSA9IHRoaXMuaXRlbXMubGVuZ3RoIC0gMTsgaSA+PSAwOyBpLS0gKSB7XG5cdFx0XHRpdGVtID0gdGhpcy5pdGVtc1sgaSBdO1xuXG5cdFx0XHQvL1dlIGlnbm9yZSBjYWxjdWxhdGluZyBwb3NpdGlvbnMgb2YgYWxsIGNvbm5lY3RlZCBjb250YWluZXJzIHdoZW4gd2UncmUgbm90IG92ZXIgdGhlbVxuXHRcdFx0aWYgKCBpdGVtLmluc3RhbmNlICE9PSB0aGlzLmN1cnJlbnRDb250YWluZXIgJiYgdGhpcy5jdXJyZW50Q29udGFpbmVyICYmXG5cdFx0XHRcdFx0aXRlbS5pdGVtWyAwIF0gIT09IHRoaXMuY3VycmVudEl0ZW1bIDAgXSApIHtcblx0XHRcdFx0Y29udGludWU7XG5cdFx0XHR9XG5cblx0XHRcdHQgPSB0aGlzLm9wdGlvbnMudG9sZXJhbmNlRWxlbWVudCA/XG5cdFx0XHRcdCQoIHRoaXMub3B0aW9ucy50b2xlcmFuY2VFbGVtZW50LCBpdGVtLml0ZW0gKSA6XG5cdFx0XHRcdGl0ZW0uaXRlbTtcblxuXHRcdFx0aWYgKCAhZmFzdCApIHtcblx0XHRcdFx0aXRlbS53aWR0aCA9IHQub3V0ZXJXaWR0aCgpO1xuXHRcdFx0XHRpdGVtLmhlaWdodCA9IHQub3V0ZXJIZWlnaHQoKTtcblx0XHRcdH1cblxuXHRcdFx0cCA9IHQub2Zmc2V0KCk7XG5cdFx0XHRpdGVtLmxlZnQgPSBwLmxlZnQ7XG5cdFx0XHRpdGVtLnRvcCA9IHAudG9wO1xuXHRcdH1cblxuXHRcdGlmICggdGhpcy5vcHRpb25zLmN1c3RvbSAmJiB0aGlzLm9wdGlvbnMuY3VzdG9tLnJlZnJlc2hDb250YWluZXJzICkge1xuXHRcdFx0dGhpcy5vcHRpb25zLmN1c3RvbS5yZWZyZXNoQ29udGFpbmVycy5jYWxsKCB0aGlzICk7XG5cdFx0fSBlbHNlIHtcblx0XHRcdGZvciAoIGkgPSB0aGlzLmNvbnRhaW5lcnMubGVuZ3RoIC0gMTsgaSA+PSAwOyBpLS0gKSB7XG5cdFx0XHRcdHAgPSB0aGlzLmNvbnRhaW5lcnNbIGkgXS5lbGVtZW50Lm9mZnNldCgpO1xuXHRcdFx0XHR0aGlzLmNvbnRhaW5lcnNbIGkgXS5jb250YWluZXJDYWNoZS5sZWZ0ID0gcC5sZWZ0O1xuXHRcdFx0XHR0aGlzLmNvbnRhaW5lcnNbIGkgXS5jb250YWluZXJDYWNoZS50b3AgPSBwLnRvcDtcblx0XHRcdFx0dGhpcy5jb250YWluZXJzWyBpIF0uY29udGFpbmVyQ2FjaGUud2lkdGggPVxuXHRcdFx0XHRcdHRoaXMuY29udGFpbmVyc1sgaSBdLmVsZW1lbnQub3V0ZXJXaWR0aCgpO1xuXHRcdFx0XHR0aGlzLmNvbnRhaW5lcnNbIGkgXS5jb250YWluZXJDYWNoZS5oZWlnaHQgPVxuXHRcdFx0XHRcdHRoaXMuY29udGFpbmVyc1sgaSBdLmVsZW1lbnQub3V0ZXJIZWlnaHQoKTtcblx0XHRcdH1cblx0XHR9XG5cblx0XHRyZXR1cm4gdGhpcztcblx0fSxcblxuXHRfY3JlYXRlUGxhY2Vob2xkZXI6IGZ1bmN0aW9uKCB0aGF0ICkge1xuXHRcdHRoYXQgPSB0aGF0IHx8IHRoaXM7XG5cdFx0dmFyIGNsYXNzTmFtZSxcblx0XHRcdG8gPSB0aGF0Lm9wdGlvbnM7XG5cblx0XHRpZiAoICFvLnBsYWNlaG9sZGVyIHx8IG8ucGxhY2Vob2xkZXIuY29uc3RydWN0b3IgPT09IFN0cmluZyApIHtcblx0XHRcdGNsYXNzTmFtZSA9IG8ucGxhY2Vob2xkZXI7XG5cdFx0XHRvLnBsYWNlaG9sZGVyID0ge1xuXHRcdFx0XHRlbGVtZW50OiBmdW5jdGlvbigpIHtcblxuXHRcdFx0XHRcdHZhciBub2RlTmFtZSA9IHRoYXQuY3VycmVudEl0ZW1bIDAgXS5ub2RlTmFtZS50b0xvd2VyQ2FzZSgpLFxuXHRcdFx0XHRcdFx0ZWxlbWVudCA9ICQoIFwiPFwiICsgbm9kZU5hbWUgKyBcIj5cIiwgdGhhdC5kb2N1bWVudFsgMCBdICk7XG5cblx0XHRcdFx0XHRcdHRoYXQuX2FkZENsYXNzKCBlbGVtZW50LCBcInVpLXNvcnRhYmxlLXBsYWNlaG9sZGVyXCIsXG5cdFx0XHRcdFx0XHRcdFx0Y2xhc3NOYW1lIHx8IHRoYXQuY3VycmVudEl0ZW1bIDAgXS5jbGFzc05hbWUgKVxuXHRcdFx0XHRcdFx0XHQuX3JlbW92ZUNsYXNzKCBlbGVtZW50LCBcInVpLXNvcnRhYmxlLWhlbHBlclwiICk7XG5cblx0XHRcdFx0XHRpZiAoIG5vZGVOYW1lID09PSBcInRib2R5XCIgKSB7XG5cdFx0XHRcdFx0XHR0aGF0Ll9jcmVhdGVUclBsYWNlaG9sZGVyKFxuXHRcdFx0XHRcdFx0XHR0aGF0LmN1cnJlbnRJdGVtLmZpbmQoIFwidHJcIiApLmVxKCAwICksXG5cdFx0XHRcdFx0XHRcdCQoIFwiPHRyPlwiLCB0aGF0LmRvY3VtZW50WyAwIF0gKS5hcHBlbmRUbyggZWxlbWVudCApXG5cdFx0XHRcdFx0XHQpO1xuXHRcdFx0XHRcdH0gZWxzZSBpZiAoIG5vZGVOYW1lID09PSBcInRyXCIgKSB7XG5cdFx0XHRcdFx0XHR0aGF0Ll9jcmVhdGVUclBsYWNlaG9sZGVyKCB0aGF0LmN1cnJlbnRJdGVtLCBlbGVtZW50ICk7XG5cdFx0XHRcdFx0fSBlbHNlIGlmICggbm9kZU5hbWUgPT09IFwiaW1nXCIgKSB7XG5cdFx0XHRcdFx0XHRlbGVtZW50LmF0dHIoIFwic3JjXCIsIHRoYXQuY3VycmVudEl0ZW0uYXR0ciggXCJzcmNcIiApICk7XG5cdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0aWYgKCAhY2xhc3NOYW1lICkge1xuXHRcdFx0XHRcdFx0ZWxlbWVudC5jc3MoIFwidmlzaWJpbGl0eVwiLCBcImhpZGRlblwiICk7XG5cdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0cmV0dXJuIGVsZW1lbnQ7XG5cdFx0XHRcdH0sXG5cdFx0XHRcdHVwZGF0ZTogZnVuY3Rpb24oIGNvbnRhaW5lciwgcCApIHtcblxuXHRcdFx0XHRcdC8vIDEuIElmIGEgY2xhc3NOYW1lIGlzIHNldCBhcyAncGxhY2Vob2xkZXIgb3B0aW9uLCB3ZSBkb24ndCBmb3JjZSBzaXplcyAtXG5cdFx0XHRcdFx0Ly8gdGhlIGNsYXNzIGlzIHJlc3BvbnNpYmxlIGZvciB0aGF0XG5cdFx0XHRcdFx0Ly8gMi4gVGhlIG9wdGlvbiAnZm9yY2VQbGFjZWhvbGRlclNpemUgY2FuIGJlIGVuYWJsZWQgdG8gZm9yY2UgaXQgZXZlbiBpZiBhXG5cdFx0XHRcdFx0Ly8gY2xhc3MgbmFtZSBpcyBzcGVjaWZpZWRcblx0XHRcdFx0XHRpZiAoIGNsYXNzTmFtZSAmJiAhby5mb3JjZVBsYWNlaG9sZGVyU2l6ZSApIHtcblx0XHRcdFx0XHRcdHJldHVybjtcblx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHQvL0lmIHRoZSBlbGVtZW50IGRvZXNuJ3QgaGF2ZSBhIGFjdHVhbCBoZWlnaHQgYnkgaXRzZWxmICh3aXRob3V0IHN0eWxlcyBjb21pbmdcblx0XHRcdFx0XHQvLyBmcm9tIGEgc3R5bGVzaGVldCksIGl0IHJlY2VpdmVzIHRoZSBpbmxpbmUgaGVpZ2h0IGZyb20gdGhlIGRyYWdnZWQgaXRlbVxuXHRcdFx0XHRcdGlmICggIXAuaGVpZ2h0KCkgKSB7XG5cdFx0XHRcdFx0XHRwLmhlaWdodChcblx0XHRcdFx0XHRcdFx0dGhhdC5jdXJyZW50SXRlbS5pbm5lckhlaWdodCgpIC1cblx0XHRcdFx0XHRcdFx0cGFyc2VJbnQoIHRoYXQuY3VycmVudEl0ZW0uY3NzKCBcInBhZGRpbmdUb3BcIiApIHx8IDAsIDEwICkgLVxuXHRcdFx0XHRcdFx0XHRwYXJzZUludCggdGhhdC5jdXJyZW50SXRlbS5jc3MoIFwicGFkZGluZ0JvdHRvbVwiICkgfHwgMCwgMTAgKSApO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0XHRpZiAoICFwLndpZHRoKCkgKSB7XG5cdFx0XHRcdFx0XHRwLndpZHRoKFxuXHRcdFx0XHRcdFx0XHR0aGF0LmN1cnJlbnRJdGVtLmlubmVyV2lkdGgoKSAtXG5cdFx0XHRcdFx0XHRcdHBhcnNlSW50KCB0aGF0LmN1cnJlbnRJdGVtLmNzcyggXCJwYWRkaW5nTGVmdFwiICkgfHwgMCwgMTAgKSAtXG5cdFx0XHRcdFx0XHRcdHBhcnNlSW50KCB0aGF0LmN1cnJlbnRJdGVtLmNzcyggXCJwYWRkaW5nUmlnaHRcIiApIHx8IDAsIDEwICkgKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdH1cblx0XHRcdH07XG5cdFx0fVxuXG5cdFx0Ly9DcmVhdGUgdGhlIHBsYWNlaG9sZGVyXG5cdFx0dGhhdC5wbGFjZWhvbGRlciA9ICQoIG8ucGxhY2Vob2xkZXIuZWxlbWVudC5jYWxsKCB0aGF0LmVsZW1lbnQsIHRoYXQuY3VycmVudEl0ZW0gKSApO1xuXG5cdFx0Ly9BcHBlbmQgaXQgYWZ0ZXIgdGhlIGFjdHVhbCBjdXJyZW50IGl0ZW1cblx0XHR0aGF0LmN1cnJlbnRJdGVtLmFmdGVyKCB0aGF0LnBsYWNlaG9sZGVyICk7XG5cblx0XHQvL1VwZGF0ZSB0aGUgc2l6ZSBvZiB0aGUgcGxhY2Vob2xkZXIgKFRPRE86IExvZ2ljIHRvIGZ1enp5LCBzZWUgbGluZSAzMTYvMzE3KVxuXHRcdG8ucGxhY2Vob2xkZXIudXBkYXRlKCB0aGF0LCB0aGF0LnBsYWNlaG9sZGVyICk7XG5cblx0fSxcblxuXHRfY3JlYXRlVHJQbGFjZWhvbGRlcjogZnVuY3Rpb24oIHNvdXJjZVRyLCB0YXJnZXRUciApIHtcblx0XHR2YXIgdGhhdCA9IHRoaXM7XG5cblx0XHRzb3VyY2VUci5jaGlsZHJlbigpLmVhY2goIGZ1bmN0aW9uKCkge1xuXHRcdFx0JCggXCI8dGQ+JiMxNjA7PC90ZD5cIiwgdGhhdC5kb2N1bWVudFsgMCBdIClcblx0XHRcdFx0LmF0dHIoIFwiY29sc3BhblwiLCAkKCB0aGlzICkuYXR0ciggXCJjb2xzcGFuXCIgKSB8fCAxIClcblx0XHRcdFx0LmFwcGVuZFRvKCB0YXJnZXRUciApO1xuXHRcdH0gKTtcblx0fSxcblxuXHRfY29udGFjdENvbnRhaW5lcnM6IGZ1bmN0aW9uKCBldmVudCApIHtcblx0XHR2YXIgaSwgaiwgZGlzdCwgaXRlbVdpdGhMZWFzdERpc3RhbmNlLCBwb3NQcm9wZXJ0eSwgc2l6ZVByb3BlcnR5LCBjdXIsIG5lYXJCb3R0b20sXG5cdFx0XHRmbG9hdGluZywgYXhpcyxcblx0XHRcdGlubmVybW9zdENvbnRhaW5lciA9IG51bGwsXG5cdFx0XHRpbm5lcm1vc3RJbmRleCA9IG51bGw7XG5cblx0XHQvLyBHZXQgaW5uZXJtb3N0IGNvbnRhaW5lciB0aGF0IGludGVyc2VjdHMgd2l0aCBpdGVtXG5cdFx0Zm9yICggaSA9IHRoaXMuY29udGFpbmVycy5sZW5ndGggLSAxOyBpID49IDA7IGktLSApIHtcblxuXHRcdFx0Ly8gTmV2ZXIgY29uc2lkZXIgYSBjb250YWluZXIgdGhhdCdzIGxvY2F0ZWQgd2l0aGluIHRoZSBpdGVtIGl0c2VsZlxuXHRcdFx0aWYgKCAkLmNvbnRhaW5zKCB0aGlzLmN1cnJlbnRJdGVtWyAwIF0sIHRoaXMuY29udGFpbmVyc1sgaSBdLmVsZW1lbnRbIDAgXSApICkge1xuXHRcdFx0XHRjb250aW51ZTtcblx0XHRcdH1cblxuXHRcdFx0aWYgKCB0aGlzLl9pbnRlcnNlY3RzV2l0aCggdGhpcy5jb250YWluZXJzWyBpIF0uY29udGFpbmVyQ2FjaGUgKSApIHtcblxuXHRcdFx0XHQvLyBJZiB3ZSd2ZSBhbHJlYWR5IGZvdW5kIGEgY29udGFpbmVyIGFuZCBpdCdzIG1vcmUgXCJpbm5lclwiIHRoYW4gdGhpcywgdGhlbiBjb250aW51ZVxuXHRcdFx0XHRpZiAoIGlubmVybW9zdENvbnRhaW5lciAmJlxuXHRcdFx0XHRcdFx0JC5jb250YWlucyhcblx0XHRcdFx0XHRcdFx0dGhpcy5jb250YWluZXJzWyBpIF0uZWxlbWVudFsgMCBdLFxuXHRcdFx0XHRcdFx0XHRpbm5lcm1vc3RDb250YWluZXIuZWxlbWVudFsgMCBdICkgKSB7XG5cdFx0XHRcdFx0Y29udGludWU7XG5cdFx0XHRcdH1cblxuXHRcdFx0XHRpbm5lcm1vc3RDb250YWluZXIgPSB0aGlzLmNvbnRhaW5lcnNbIGkgXTtcblx0XHRcdFx0aW5uZXJtb3N0SW5kZXggPSBpO1xuXG5cdFx0XHR9IGVsc2Uge1xuXG5cdFx0XHRcdC8vIGNvbnRhaW5lciBkb2Vzbid0IGludGVyc2VjdC4gdHJpZ2dlciBcIm91dFwiIGV2ZW50IGlmIG5lY2Vzc2FyeVxuXHRcdFx0XHRpZiAoIHRoaXMuY29udGFpbmVyc1sgaSBdLmNvbnRhaW5lckNhY2hlLm92ZXIgKSB7XG5cdFx0XHRcdFx0dGhpcy5jb250YWluZXJzWyBpIF0uX3RyaWdnZXIoIFwib3V0XCIsIGV2ZW50LCB0aGlzLl91aUhhc2goIHRoaXMgKSApO1xuXHRcdFx0XHRcdHRoaXMuY29udGFpbmVyc1sgaSBdLmNvbnRhaW5lckNhY2hlLm92ZXIgPSAwO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cblx0XHR9XG5cblx0XHQvLyBJZiBubyBpbnRlcnNlY3RpbmcgY29udGFpbmVycyBmb3VuZCwgcmV0dXJuXG5cdFx0aWYgKCAhaW5uZXJtb3N0Q29udGFpbmVyICkge1xuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblxuXHRcdC8vIE1vdmUgdGhlIGl0ZW0gaW50byB0aGUgY29udGFpbmVyIGlmIGl0J3Mgbm90IHRoZXJlIGFscmVhZHlcblx0XHRpZiAoIHRoaXMuY29udGFpbmVycy5sZW5ndGggPT09IDEgKSB7XG5cdFx0XHRpZiAoICF0aGlzLmNvbnRhaW5lcnNbIGlubmVybW9zdEluZGV4IF0uY29udGFpbmVyQ2FjaGUub3ZlciApIHtcblx0XHRcdFx0dGhpcy5jb250YWluZXJzWyBpbm5lcm1vc3RJbmRleCBdLl90cmlnZ2VyKCBcIm92ZXJcIiwgZXZlbnQsIHRoaXMuX3VpSGFzaCggdGhpcyApICk7XG5cdFx0XHRcdHRoaXMuY29udGFpbmVyc1sgaW5uZXJtb3N0SW5kZXggXS5jb250YWluZXJDYWNoZS5vdmVyID0gMTtcblx0XHRcdH1cblx0XHR9IGVsc2Uge1xuXG5cdFx0XHQvLyBXaGVuIGVudGVyaW5nIGEgbmV3IGNvbnRhaW5lciwgd2Ugd2lsbCBmaW5kIHRoZSBpdGVtIHdpdGggdGhlIGxlYXN0IGRpc3RhbmNlIGFuZFxuXHRcdFx0Ly8gYXBwZW5kIG91ciBpdGVtIG5lYXIgaXRcblx0XHRcdGRpc3QgPSAxMDAwMDtcblx0XHRcdGl0ZW1XaXRoTGVhc3REaXN0YW5jZSA9IG51bGw7XG5cdFx0XHRmbG9hdGluZyA9IGlubmVybW9zdENvbnRhaW5lci5mbG9hdGluZyB8fCB0aGlzLl9pc0Zsb2F0aW5nKCB0aGlzLmN1cnJlbnRJdGVtICk7XG5cdFx0XHRwb3NQcm9wZXJ0eSA9IGZsb2F0aW5nID8gXCJsZWZ0XCIgOiBcInRvcFwiO1xuXHRcdFx0c2l6ZVByb3BlcnR5ID0gZmxvYXRpbmcgPyBcIndpZHRoXCIgOiBcImhlaWdodFwiO1xuXHRcdFx0YXhpcyA9IGZsb2F0aW5nID8gXCJwYWdlWFwiIDogXCJwYWdlWVwiO1xuXG5cdFx0XHRmb3IgKCBqID0gdGhpcy5pdGVtcy5sZW5ndGggLSAxOyBqID49IDA7IGotLSApIHtcblx0XHRcdFx0aWYgKCAhJC5jb250YWlucyhcblx0XHRcdFx0XHRcdHRoaXMuY29udGFpbmVyc1sgaW5uZXJtb3N0SW5kZXggXS5lbGVtZW50WyAwIF0sIHRoaXMuaXRlbXNbIGogXS5pdGVtWyAwIF0gKVxuXHRcdFx0XHQpIHtcblx0XHRcdFx0XHRjb250aW51ZTtcblx0XHRcdFx0fVxuXHRcdFx0XHRpZiAoIHRoaXMuaXRlbXNbIGogXS5pdGVtWyAwIF0gPT09IHRoaXMuY3VycmVudEl0ZW1bIDAgXSApIHtcblx0XHRcdFx0XHRjb250aW51ZTtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdGN1ciA9IHRoaXMuaXRlbXNbIGogXS5pdGVtLm9mZnNldCgpWyBwb3NQcm9wZXJ0eSBdO1xuXHRcdFx0XHRuZWFyQm90dG9tID0gZmFsc2U7XG5cdFx0XHRcdGlmICggZXZlbnRbIGF4aXMgXSAtIGN1ciA+IHRoaXMuaXRlbXNbIGogXVsgc2l6ZVByb3BlcnR5IF0gLyAyICkge1xuXHRcdFx0XHRcdG5lYXJCb3R0b20gPSB0cnVlO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0aWYgKCBNYXRoLmFicyggZXZlbnRbIGF4aXMgXSAtIGN1ciApIDwgZGlzdCApIHtcblx0XHRcdFx0XHRkaXN0ID0gTWF0aC5hYnMoIGV2ZW50WyBheGlzIF0gLSBjdXIgKTtcblx0XHRcdFx0XHRpdGVtV2l0aExlYXN0RGlzdGFuY2UgPSB0aGlzLml0ZW1zWyBqIF07XG5cdFx0XHRcdFx0dGhpcy5kaXJlY3Rpb24gPSBuZWFyQm90dG9tID8gXCJ1cFwiIDogXCJkb3duXCI7XG5cdFx0XHRcdH1cblx0XHRcdH1cblxuXHRcdFx0Ly9DaGVjayBpZiBkcm9wT25FbXB0eSBpcyBlbmFibGVkXG5cdFx0XHRpZiAoICFpdGVtV2l0aExlYXN0RGlzdGFuY2UgJiYgIXRoaXMub3B0aW9ucy5kcm9wT25FbXB0eSApIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHRpZiAoIHRoaXMuY3VycmVudENvbnRhaW5lciA9PT0gdGhpcy5jb250YWluZXJzWyBpbm5lcm1vc3RJbmRleCBdICkge1xuXHRcdFx0XHRpZiAoICF0aGlzLmN1cnJlbnRDb250YWluZXIuY29udGFpbmVyQ2FjaGUub3ZlciApIHtcblx0XHRcdFx0XHR0aGlzLmNvbnRhaW5lcnNbIGlubmVybW9zdEluZGV4IF0uX3RyaWdnZXIoIFwib3ZlclwiLCBldmVudCwgdGhpcy5fdWlIYXNoKCkgKTtcblx0XHRcdFx0XHR0aGlzLmN1cnJlbnRDb250YWluZXIuY29udGFpbmVyQ2FjaGUub3ZlciA9IDE7XG5cdFx0XHRcdH1cblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHRpdGVtV2l0aExlYXN0RGlzdGFuY2UgP1xuXHRcdFx0XHR0aGlzLl9yZWFycmFuZ2UoIGV2ZW50LCBpdGVtV2l0aExlYXN0RGlzdGFuY2UsIG51bGwsIHRydWUgKSA6XG5cdFx0XHRcdHRoaXMuX3JlYXJyYW5nZSggZXZlbnQsIG51bGwsIHRoaXMuY29udGFpbmVyc1sgaW5uZXJtb3N0SW5kZXggXS5lbGVtZW50LCB0cnVlICk7XG5cdFx0XHR0aGlzLl90cmlnZ2VyKCBcImNoYW5nZVwiLCBldmVudCwgdGhpcy5fdWlIYXNoKCkgKTtcblx0XHRcdHRoaXMuY29udGFpbmVyc1sgaW5uZXJtb3N0SW5kZXggXS5fdHJpZ2dlciggXCJjaGFuZ2VcIiwgZXZlbnQsIHRoaXMuX3VpSGFzaCggdGhpcyApICk7XG5cdFx0XHR0aGlzLmN1cnJlbnRDb250YWluZXIgPSB0aGlzLmNvbnRhaW5lcnNbIGlubmVybW9zdEluZGV4IF07XG5cblx0XHRcdC8vVXBkYXRlIHRoZSBwbGFjZWhvbGRlclxuXHRcdFx0dGhpcy5vcHRpb25zLnBsYWNlaG9sZGVyLnVwZGF0ZSggdGhpcy5jdXJyZW50Q29udGFpbmVyLCB0aGlzLnBsYWNlaG9sZGVyICk7XG5cblx0XHRcdHRoaXMuY29udGFpbmVyc1sgaW5uZXJtb3N0SW5kZXggXS5fdHJpZ2dlciggXCJvdmVyXCIsIGV2ZW50LCB0aGlzLl91aUhhc2goIHRoaXMgKSApO1xuXHRcdFx0dGhpcy5jb250YWluZXJzWyBpbm5lcm1vc3RJbmRleCBdLmNvbnRhaW5lckNhY2hlLm92ZXIgPSAxO1xuXHRcdH1cblxuXHR9LFxuXG5cdF9jcmVhdGVIZWxwZXI6IGZ1bmN0aW9uKCBldmVudCApIHtcblxuXHRcdHZhciBvID0gdGhpcy5vcHRpb25zLFxuXHRcdFx0aGVscGVyID0gJC5pc0Z1bmN0aW9uKCBvLmhlbHBlciApID9cblx0XHRcdFx0JCggby5oZWxwZXIuYXBwbHkoIHRoaXMuZWxlbWVudFsgMCBdLCBbIGV2ZW50LCB0aGlzLmN1cnJlbnRJdGVtIF0gKSApIDpcblx0XHRcdFx0KCBvLmhlbHBlciA9PT0gXCJjbG9uZVwiID8gdGhpcy5jdXJyZW50SXRlbS5jbG9uZSgpIDogdGhpcy5jdXJyZW50SXRlbSApO1xuXG5cdFx0Ly9BZGQgdGhlIGhlbHBlciB0byB0aGUgRE9NIGlmIHRoYXQgZGlkbid0IGhhcHBlbiBhbHJlYWR5XG5cdFx0aWYgKCAhaGVscGVyLnBhcmVudHMoIFwiYm9keVwiICkubGVuZ3RoICkge1xuXHRcdFx0JCggby5hcHBlbmRUbyAhPT0gXCJwYXJlbnRcIiA/XG5cdFx0XHRcdG8uYXBwZW5kVG8gOlxuXHRcdFx0XHR0aGlzLmN1cnJlbnRJdGVtWyAwIF0ucGFyZW50Tm9kZSApWyAwIF0uYXBwZW5kQ2hpbGQoIGhlbHBlclsgMCBdICk7XG5cdFx0fVxuXG5cdFx0aWYgKCBoZWxwZXJbIDAgXSA9PT0gdGhpcy5jdXJyZW50SXRlbVsgMCBdICkge1xuXHRcdFx0dGhpcy5fc3RvcmVkQ1NTID0ge1xuXHRcdFx0XHR3aWR0aDogdGhpcy5jdXJyZW50SXRlbVsgMCBdLnN0eWxlLndpZHRoLFxuXHRcdFx0XHRoZWlnaHQ6IHRoaXMuY3VycmVudEl0ZW1bIDAgXS5zdHlsZS5oZWlnaHQsXG5cdFx0XHRcdHBvc2l0aW9uOiB0aGlzLmN1cnJlbnRJdGVtLmNzcyggXCJwb3NpdGlvblwiICksXG5cdFx0XHRcdHRvcDogdGhpcy5jdXJyZW50SXRlbS5jc3MoIFwidG9wXCIgKSxcblx0XHRcdFx0bGVmdDogdGhpcy5jdXJyZW50SXRlbS5jc3MoIFwibGVmdFwiIClcblx0XHRcdH07XG5cdFx0fVxuXG5cdFx0aWYgKCAhaGVscGVyWyAwIF0uc3R5bGUud2lkdGggfHwgby5mb3JjZUhlbHBlclNpemUgKSB7XG5cdFx0XHRoZWxwZXIud2lkdGgoIHRoaXMuY3VycmVudEl0ZW0ud2lkdGgoKSApO1xuXHRcdH1cblx0XHRpZiAoICFoZWxwZXJbIDAgXS5zdHlsZS5oZWlnaHQgfHwgby5mb3JjZUhlbHBlclNpemUgKSB7XG5cdFx0XHRoZWxwZXIuaGVpZ2h0KCB0aGlzLmN1cnJlbnRJdGVtLmhlaWdodCgpICk7XG5cdFx0fVxuXG5cdFx0cmV0dXJuIGhlbHBlcjtcblxuXHR9LFxuXG5cdF9hZGp1c3RPZmZzZXRGcm9tSGVscGVyOiBmdW5jdGlvbiggb2JqICkge1xuXHRcdGlmICggdHlwZW9mIG9iaiA9PT0gXCJzdHJpbmdcIiApIHtcblx0XHRcdG9iaiA9IG9iai5zcGxpdCggXCIgXCIgKTtcblx0XHR9XG5cdFx0aWYgKCAkLmlzQXJyYXkoIG9iaiApICkge1xuXHRcdFx0b2JqID0geyBsZWZ0OiArb2JqWyAwIF0sIHRvcDogK29ialsgMSBdIHx8IDAgfTtcblx0XHR9XG5cdFx0aWYgKCBcImxlZnRcIiBpbiBvYmogKSB7XG5cdFx0XHR0aGlzLm9mZnNldC5jbGljay5sZWZ0ID0gb2JqLmxlZnQgKyB0aGlzLm1hcmdpbnMubGVmdDtcblx0XHR9XG5cdFx0aWYgKCBcInJpZ2h0XCIgaW4gb2JqICkge1xuXHRcdFx0dGhpcy5vZmZzZXQuY2xpY2subGVmdCA9IHRoaXMuaGVscGVyUHJvcG9ydGlvbnMud2lkdGggLSBvYmoucmlnaHQgKyB0aGlzLm1hcmdpbnMubGVmdDtcblx0XHR9XG5cdFx0aWYgKCBcInRvcFwiIGluIG9iaiApIHtcblx0XHRcdHRoaXMub2Zmc2V0LmNsaWNrLnRvcCA9IG9iai50b3AgKyB0aGlzLm1hcmdpbnMudG9wO1xuXHRcdH1cblx0XHRpZiAoIFwiYm90dG9tXCIgaW4gb2JqICkge1xuXHRcdFx0dGhpcy5vZmZzZXQuY2xpY2sudG9wID0gdGhpcy5oZWxwZXJQcm9wb3J0aW9ucy5oZWlnaHQgLSBvYmouYm90dG9tICsgdGhpcy5tYXJnaW5zLnRvcDtcblx0XHR9XG5cdH0sXG5cblx0X2dldFBhcmVudE9mZnNldDogZnVuY3Rpb24oKSB7XG5cblx0XHQvL0dldCB0aGUgb2Zmc2V0UGFyZW50IGFuZCBjYWNoZSBpdHMgcG9zaXRpb25cblx0XHR0aGlzLm9mZnNldFBhcmVudCA9IHRoaXMuaGVscGVyLm9mZnNldFBhcmVudCgpO1xuXHRcdHZhciBwbyA9IHRoaXMub2Zmc2V0UGFyZW50Lm9mZnNldCgpO1xuXG5cdFx0Ly8gVGhpcyBpcyBhIHNwZWNpYWwgY2FzZSB3aGVyZSB3ZSBuZWVkIHRvIG1vZGlmeSBhIG9mZnNldCBjYWxjdWxhdGVkIG9uIHN0YXJ0LCBzaW5jZSB0aGVcblx0XHQvLyBmb2xsb3dpbmcgaGFwcGVuZWQ6XG5cdFx0Ly8gMS4gVGhlIHBvc2l0aW9uIG9mIHRoZSBoZWxwZXIgaXMgYWJzb2x1dGUsIHNvIGl0J3MgcG9zaXRpb24gaXMgY2FsY3VsYXRlZCBiYXNlZCBvbiB0aGVcblx0XHQvLyBuZXh0IHBvc2l0aW9uZWQgcGFyZW50XG5cdFx0Ly8gMi4gVGhlIGFjdHVhbCBvZmZzZXQgcGFyZW50IGlzIGEgY2hpbGQgb2YgdGhlIHNjcm9sbCBwYXJlbnQsIGFuZCB0aGUgc2Nyb2xsIHBhcmVudCBpc24ndFxuXHRcdC8vIHRoZSBkb2N1bWVudCwgd2hpY2ggbWVhbnMgdGhhdCB0aGUgc2Nyb2xsIGlzIGluY2x1ZGVkIGluIHRoZSBpbml0aWFsIGNhbGN1bGF0aW9uIG9mIHRoZVxuXHRcdC8vIG9mZnNldCBvZiB0aGUgcGFyZW50LCBhbmQgbmV2ZXIgcmVjYWxjdWxhdGVkIHVwb24gZHJhZ1xuXHRcdGlmICggdGhpcy5jc3NQb3NpdGlvbiA9PT0gXCJhYnNvbHV0ZVwiICYmIHRoaXMuc2Nyb2xsUGFyZW50WyAwIF0gIT09IHRoaXMuZG9jdW1lbnRbIDAgXSAmJlxuXHRcdFx0XHQkLmNvbnRhaW5zKCB0aGlzLnNjcm9sbFBhcmVudFsgMCBdLCB0aGlzLm9mZnNldFBhcmVudFsgMCBdICkgKSB7XG5cdFx0XHRwby5sZWZ0ICs9IHRoaXMuc2Nyb2xsUGFyZW50LnNjcm9sbExlZnQoKTtcblx0XHRcdHBvLnRvcCArPSB0aGlzLnNjcm9sbFBhcmVudC5zY3JvbGxUb3AoKTtcblx0XHR9XG5cblx0XHQvLyBUaGlzIG5lZWRzIHRvIGJlIGFjdHVhbGx5IGRvbmUgZm9yIGFsbCBicm93c2Vycywgc2luY2UgcGFnZVgvcGFnZVkgaW5jbHVkZXMgdGhpc1xuXHRcdC8vIGluZm9ybWF0aW9uIHdpdGggYW4gdWdseSBJRSBmaXhcblx0XHRpZiAoIHRoaXMub2Zmc2V0UGFyZW50WyAwIF0gPT09IHRoaXMuZG9jdW1lbnRbIDAgXS5ib2R5IHx8XG5cdFx0XHRcdCggdGhpcy5vZmZzZXRQYXJlbnRbIDAgXS50YWdOYW1lICYmXG5cdFx0XHRcdHRoaXMub2Zmc2V0UGFyZW50WyAwIF0udGFnTmFtZS50b0xvd2VyQ2FzZSgpID09PSBcImh0bWxcIiAmJiAkLnVpLmllICkgKSB7XG5cdFx0XHRwbyA9IHsgdG9wOiAwLCBsZWZ0OiAwIH07XG5cdFx0fVxuXG5cdFx0cmV0dXJuIHtcblx0XHRcdHRvcDogcG8udG9wICsgKCBwYXJzZUludCggdGhpcy5vZmZzZXRQYXJlbnQuY3NzKCBcImJvcmRlclRvcFdpZHRoXCIgKSwgMTAgKSB8fCAwICksXG5cdFx0XHRsZWZ0OiBwby5sZWZ0ICsgKCBwYXJzZUludCggdGhpcy5vZmZzZXRQYXJlbnQuY3NzKCBcImJvcmRlckxlZnRXaWR0aFwiICksIDEwICkgfHwgMCApXG5cdFx0fTtcblxuXHR9LFxuXG5cdF9nZXRSZWxhdGl2ZU9mZnNldDogZnVuY3Rpb24oKSB7XG5cblx0XHRpZiAoIHRoaXMuY3NzUG9zaXRpb24gPT09IFwicmVsYXRpdmVcIiApIHtcblx0XHRcdHZhciBwID0gdGhpcy5jdXJyZW50SXRlbS5wb3NpdGlvbigpO1xuXHRcdFx0cmV0dXJuIHtcblx0XHRcdFx0dG9wOiBwLnRvcCAtICggcGFyc2VJbnQoIHRoaXMuaGVscGVyLmNzcyggXCJ0b3BcIiApLCAxMCApIHx8IDAgKSArXG5cdFx0XHRcdFx0dGhpcy5zY3JvbGxQYXJlbnQuc2Nyb2xsVG9wKCksXG5cdFx0XHRcdGxlZnQ6IHAubGVmdCAtICggcGFyc2VJbnQoIHRoaXMuaGVscGVyLmNzcyggXCJsZWZ0XCIgKSwgMTAgKSB8fCAwICkgK1xuXHRcdFx0XHRcdHRoaXMuc2Nyb2xsUGFyZW50LnNjcm9sbExlZnQoKVxuXHRcdFx0fTtcblx0XHR9IGVsc2Uge1xuXHRcdFx0cmV0dXJuIHsgdG9wOiAwLCBsZWZ0OiAwIH07XG5cdFx0fVxuXG5cdH0sXG5cblx0X2NhY2hlTWFyZ2luczogZnVuY3Rpb24oKSB7XG5cdFx0dGhpcy5tYXJnaW5zID0ge1xuXHRcdFx0bGVmdDogKCBwYXJzZUludCggdGhpcy5jdXJyZW50SXRlbS5jc3MoIFwibWFyZ2luTGVmdFwiICksIDEwICkgfHwgMCApLFxuXHRcdFx0dG9wOiAoIHBhcnNlSW50KCB0aGlzLmN1cnJlbnRJdGVtLmNzcyggXCJtYXJnaW5Ub3BcIiApLCAxMCApIHx8IDAgKVxuXHRcdH07XG5cdH0sXG5cblx0X2NhY2hlSGVscGVyUHJvcG9ydGlvbnM6IGZ1bmN0aW9uKCkge1xuXHRcdHRoaXMuaGVscGVyUHJvcG9ydGlvbnMgPSB7XG5cdFx0XHR3aWR0aDogdGhpcy5oZWxwZXIub3V0ZXJXaWR0aCgpLFxuXHRcdFx0aGVpZ2h0OiB0aGlzLmhlbHBlci5vdXRlckhlaWdodCgpXG5cdFx0fTtcblx0fSxcblxuXHRfc2V0Q29udGFpbm1lbnQ6IGZ1bmN0aW9uKCkge1xuXG5cdFx0dmFyIGNlLCBjbywgb3Zlcixcblx0XHRcdG8gPSB0aGlzLm9wdGlvbnM7XG5cdFx0aWYgKCBvLmNvbnRhaW5tZW50ID09PSBcInBhcmVudFwiICkge1xuXHRcdFx0by5jb250YWlubWVudCA9IHRoaXMuaGVscGVyWyAwIF0ucGFyZW50Tm9kZTtcblx0XHR9XG5cdFx0aWYgKCBvLmNvbnRhaW5tZW50ID09PSBcImRvY3VtZW50XCIgfHwgby5jb250YWlubWVudCA9PT0gXCJ3aW5kb3dcIiApIHtcblx0XHRcdHRoaXMuY29udGFpbm1lbnQgPSBbXG5cdFx0XHRcdDAgLSB0aGlzLm9mZnNldC5yZWxhdGl2ZS5sZWZ0IC0gdGhpcy5vZmZzZXQucGFyZW50LmxlZnQsXG5cdFx0XHRcdDAgLSB0aGlzLm9mZnNldC5yZWxhdGl2ZS50b3AgLSB0aGlzLm9mZnNldC5wYXJlbnQudG9wLFxuXHRcdFx0XHRvLmNvbnRhaW5tZW50ID09PSBcImRvY3VtZW50XCIgP1xuXHRcdFx0XHRcdHRoaXMuZG9jdW1lbnQud2lkdGgoKSA6XG5cdFx0XHRcdFx0dGhpcy53aW5kb3cud2lkdGgoKSAtIHRoaXMuaGVscGVyUHJvcG9ydGlvbnMud2lkdGggLSB0aGlzLm1hcmdpbnMubGVmdCxcblx0XHRcdFx0KCBvLmNvbnRhaW5tZW50ID09PSBcImRvY3VtZW50XCIgP1xuXHRcdFx0XHRcdCggdGhpcy5kb2N1bWVudC5oZWlnaHQoKSB8fCBkb2N1bWVudC5ib2R5LnBhcmVudE5vZGUuc2Nyb2xsSGVpZ2h0ICkgOlxuXHRcdFx0XHRcdHRoaXMud2luZG93LmhlaWdodCgpIHx8IHRoaXMuZG9jdW1lbnRbIDAgXS5ib2R5LnBhcmVudE5vZGUuc2Nyb2xsSGVpZ2h0XG5cdFx0XHRcdCkgLSB0aGlzLmhlbHBlclByb3BvcnRpb25zLmhlaWdodCAtIHRoaXMubWFyZ2lucy50b3Bcblx0XHRcdF07XG5cdFx0fVxuXG5cdFx0aWYgKCAhKCAvXihkb2N1bWVudHx3aW5kb3d8cGFyZW50KSQvICkudGVzdCggby5jb250YWlubWVudCApICkge1xuXHRcdFx0Y2UgPSAkKCBvLmNvbnRhaW5tZW50IClbIDAgXTtcblx0XHRcdGNvID0gJCggby5jb250YWlubWVudCApLm9mZnNldCgpO1xuXHRcdFx0b3ZlciA9ICggJCggY2UgKS5jc3MoIFwib3ZlcmZsb3dcIiApICE9PSBcImhpZGRlblwiICk7XG5cblx0XHRcdHRoaXMuY29udGFpbm1lbnQgPSBbXG5cdFx0XHRcdGNvLmxlZnQgKyAoIHBhcnNlSW50KCAkKCBjZSApLmNzcyggXCJib3JkZXJMZWZ0V2lkdGhcIiApLCAxMCApIHx8IDAgKSArXG5cdFx0XHRcdFx0KCBwYXJzZUludCggJCggY2UgKS5jc3MoIFwicGFkZGluZ0xlZnRcIiApLCAxMCApIHx8IDAgKSAtIHRoaXMubWFyZ2lucy5sZWZ0LFxuXHRcdFx0XHRjby50b3AgKyAoIHBhcnNlSW50KCAkKCBjZSApLmNzcyggXCJib3JkZXJUb3BXaWR0aFwiICksIDEwICkgfHwgMCApICtcblx0XHRcdFx0XHQoIHBhcnNlSW50KCAkKCBjZSApLmNzcyggXCJwYWRkaW5nVG9wXCIgKSwgMTAgKSB8fCAwICkgLSB0aGlzLm1hcmdpbnMudG9wLFxuXHRcdFx0XHRjby5sZWZ0ICsgKCBvdmVyID8gTWF0aC5tYXgoIGNlLnNjcm9sbFdpZHRoLCBjZS5vZmZzZXRXaWR0aCApIDogY2Uub2Zmc2V0V2lkdGggKSAtXG5cdFx0XHRcdFx0KCBwYXJzZUludCggJCggY2UgKS5jc3MoIFwiYm9yZGVyTGVmdFdpZHRoXCIgKSwgMTAgKSB8fCAwICkgLVxuXHRcdFx0XHRcdCggcGFyc2VJbnQoICQoIGNlICkuY3NzKCBcInBhZGRpbmdSaWdodFwiICksIDEwICkgfHwgMCApIC1cblx0XHRcdFx0XHR0aGlzLmhlbHBlclByb3BvcnRpb25zLndpZHRoIC0gdGhpcy5tYXJnaW5zLmxlZnQsXG5cdFx0XHRcdGNvLnRvcCArICggb3ZlciA/IE1hdGgubWF4KCBjZS5zY3JvbGxIZWlnaHQsIGNlLm9mZnNldEhlaWdodCApIDogY2Uub2Zmc2V0SGVpZ2h0ICkgLVxuXHRcdFx0XHRcdCggcGFyc2VJbnQoICQoIGNlICkuY3NzKCBcImJvcmRlclRvcFdpZHRoXCIgKSwgMTAgKSB8fCAwICkgLVxuXHRcdFx0XHRcdCggcGFyc2VJbnQoICQoIGNlICkuY3NzKCBcInBhZGRpbmdCb3R0b21cIiApLCAxMCApIHx8IDAgKSAtXG5cdFx0XHRcdFx0dGhpcy5oZWxwZXJQcm9wb3J0aW9ucy5oZWlnaHQgLSB0aGlzLm1hcmdpbnMudG9wXG5cdFx0XHRdO1xuXHRcdH1cblxuXHR9LFxuXG5cdF9jb252ZXJ0UG9zaXRpb25UbzogZnVuY3Rpb24oIGQsIHBvcyApIHtcblxuXHRcdGlmICggIXBvcyApIHtcblx0XHRcdHBvcyA9IHRoaXMucG9zaXRpb247XG5cdFx0fVxuXHRcdHZhciBtb2QgPSBkID09PSBcImFic29sdXRlXCIgPyAxIDogLTEsXG5cdFx0XHRzY3JvbGwgPSB0aGlzLmNzc1Bvc2l0aW9uID09PSBcImFic29sdXRlXCIgJiZcblx0XHRcdFx0ISggdGhpcy5zY3JvbGxQYXJlbnRbIDAgXSAhPT0gdGhpcy5kb2N1bWVudFsgMCBdICYmXG5cdFx0XHRcdCQuY29udGFpbnMoIHRoaXMuc2Nyb2xsUGFyZW50WyAwIF0sIHRoaXMub2Zmc2V0UGFyZW50WyAwIF0gKSApID9cblx0XHRcdFx0XHR0aGlzLm9mZnNldFBhcmVudCA6XG5cdFx0XHRcdFx0dGhpcy5zY3JvbGxQYXJlbnQsXG5cdFx0XHRzY3JvbGxJc1Jvb3ROb2RlID0gKCAvKGh0bWx8Ym9keSkvaSApLnRlc3QoIHNjcm9sbFsgMCBdLnRhZ05hbWUgKTtcblxuXHRcdHJldHVybiB7XG5cdFx0XHR0b3A6IChcblxuXHRcdFx0XHQvLyBUaGUgYWJzb2x1dGUgbW91c2UgcG9zaXRpb25cblx0XHRcdFx0cG9zLnRvcFx0K1xuXG5cdFx0XHRcdC8vIE9ubHkgZm9yIHJlbGF0aXZlIHBvc2l0aW9uZWQgbm9kZXM6IFJlbGF0aXZlIG9mZnNldCBmcm9tIGVsZW1lbnQgdG8gb2Zmc2V0IHBhcmVudFxuXHRcdFx0XHR0aGlzLm9mZnNldC5yZWxhdGl2ZS50b3AgKiBtb2QgK1xuXG5cdFx0XHRcdC8vIFRoZSBvZmZzZXRQYXJlbnQncyBvZmZzZXQgd2l0aG91dCBib3JkZXJzIChvZmZzZXQgKyBib3JkZXIpXG5cdFx0XHRcdHRoaXMub2Zmc2V0LnBhcmVudC50b3AgKiBtb2QgLVxuXHRcdFx0XHQoICggdGhpcy5jc3NQb3NpdGlvbiA9PT0gXCJmaXhlZFwiID9cblx0XHRcdFx0XHQtdGhpcy5zY3JvbGxQYXJlbnQuc2Nyb2xsVG9wKCkgOlxuXHRcdFx0XHRcdCggc2Nyb2xsSXNSb290Tm9kZSA/IDAgOiBzY3JvbGwuc2Nyb2xsVG9wKCkgKSApICogbW9kIClcblx0XHRcdCksXG5cdFx0XHRsZWZ0OiAoXG5cblx0XHRcdFx0Ly8gVGhlIGFic29sdXRlIG1vdXNlIHBvc2l0aW9uXG5cdFx0XHRcdHBvcy5sZWZ0ICtcblxuXHRcdFx0XHQvLyBPbmx5IGZvciByZWxhdGl2ZSBwb3NpdGlvbmVkIG5vZGVzOiBSZWxhdGl2ZSBvZmZzZXQgZnJvbSBlbGVtZW50IHRvIG9mZnNldCBwYXJlbnRcblx0XHRcdFx0dGhpcy5vZmZzZXQucmVsYXRpdmUubGVmdCAqIG1vZCArXG5cblx0XHRcdFx0Ly8gVGhlIG9mZnNldFBhcmVudCdzIG9mZnNldCB3aXRob3V0IGJvcmRlcnMgKG9mZnNldCArIGJvcmRlcilcblx0XHRcdFx0dGhpcy5vZmZzZXQucGFyZW50LmxlZnQgKiBtb2RcdC1cblx0XHRcdFx0KCAoIHRoaXMuY3NzUG9zaXRpb24gPT09IFwiZml4ZWRcIiA/XG5cdFx0XHRcdFx0LXRoaXMuc2Nyb2xsUGFyZW50LnNjcm9sbExlZnQoKSA6IHNjcm9sbElzUm9vdE5vZGUgPyAwIDpcblx0XHRcdFx0XHRzY3JvbGwuc2Nyb2xsTGVmdCgpICkgKiBtb2QgKVxuXHRcdFx0KVxuXHRcdH07XG5cblx0fSxcblxuXHRfZ2VuZXJhdGVQb3NpdGlvbjogZnVuY3Rpb24oIGV2ZW50ICkge1xuXG5cdFx0dmFyIHRvcCwgbGVmdCxcblx0XHRcdG8gPSB0aGlzLm9wdGlvbnMsXG5cdFx0XHRwYWdlWCA9IGV2ZW50LnBhZ2VYLFxuXHRcdFx0cGFnZVkgPSBldmVudC5wYWdlWSxcblx0XHRcdHNjcm9sbCA9IHRoaXMuY3NzUG9zaXRpb24gPT09IFwiYWJzb2x1dGVcIiAmJlxuXHRcdFx0XHQhKCB0aGlzLnNjcm9sbFBhcmVudFsgMCBdICE9PSB0aGlzLmRvY3VtZW50WyAwIF0gJiZcblx0XHRcdFx0JC5jb250YWlucyggdGhpcy5zY3JvbGxQYXJlbnRbIDAgXSwgdGhpcy5vZmZzZXRQYXJlbnRbIDAgXSApICkgP1xuXHRcdFx0XHRcdHRoaXMub2Zmc2V0UGFyZW50IDpcblx0XHRcdFx0XHR0aGlzLnNjcm9sbFBhcmVudCxcblx0XHRcdFx0c2Nyb2xsSXNSb290Tm9kZSA9ICggLyhodG1sfGJvZHkpL2kgKS50ZXN0KCBzY3JvbGxbIDAgXS50YWdOYW1lICk7XG5cblx0XHQvLyBUaGlzIGlzIGFub3RoZXIgdmVyeSB3ZWlyZCBzcGVjaWFsIGNhc2UgdGhhdCBvbmx5IGhhcHBlbnMgZm9yIHJlbGF0aXZlIGVsZW1lbnRzOlxuXHRcdC8vIDEuIElmIHRoZSBjc3MgcG9zaXRpb24gaXMgcmVsYXRpdmVcblx0XHQvLyAyLiBhbmQgdGhlIHNjcm9sbCBwYXJlbnQgaXMgdGhlIGRvY3VtZW50IG9yIHNpbWlsYXIgdG8gdGhlIG9mZnNldCBwYXJlbnRcblx0XHQvLyB3ZSBoYXZlIHRvIHJlZnJlc2ggdGhlIHJlbGF0aXZlIG9mZnNldCBkdXJpbmcgdGhlIHNjcm9sbCBzbyB0aGVyZSBhcmUgbm8ganVtcHNcblx0XHRpZiAoIHRoaXMuY3NzUG9zaXRpb24gPT09IFwicmVsYXRpdmVcIiAmJiAhKCB0aGlzLnNjcm9sbFBhcmVudFsgMCBdICE9PSB0aGlzLmRvY3VtZW50WyAwIF0gJiZcblx0XHRcdFx0dGhpcy5zY3JvbGxQYXJlbnRbIDAgXSAhPT0gdGhpcy5vZmZzZXRQYXJlbnRbIDAgXSApICkge1xuXHRcdFx0dGhpcy5vZmZzZXQucmVsYXRpdmUgPSB0aGlzLl9nZXRSZWxhdGl2ZU9mZnNldCgpO1xuXHRcdH1cblxuXHRcdC8qXG5cdFx0ICogLSBQb3NpdGlvbiBjb25zdHJhaW5pbmcgLVxuXHRcdCAqIENvbnN0cmFpbiB0aGUgcG9zaXRpb24gdG8gYSBtaXggb2YgZ3JpZCwgY29udGFpbm1lbnQuXG5cdFx0ICovXG5cblx0XHRpZiAoIHRoaXMub3JpZ2luYWxQb3NpdGlvbiApIHsgLy9JZiB3ZSBhcmUgbm90IGRyYWdnaW5nIHlldCwgd2Ugd29uJ3QgY2hlY2sgZm9yIG9wdGlvbnNcblxuXHRcdFx0aWYgKCB0aGlzLmNvbnRhaW5tZW50ICkge1xuXHRcdFx0XHRpZiAoIGV2ZW50LnBhZ2VYIC0gdGhpcy5vZmZzZXQuY2xpY2subGVmdCA8IHRoaXMuY29udGFpbm1lbnRbIDAgXSApIHtcblx0XHRcdFx0XHRwYWdlWCA9IHRoaXMuY29udGFpbm1lbnRbIDAgXSArIHRoaXMub2Zmc2V0LmNsaWNrLmxlZnQ7XG5cdFx0XHRcdH1cblx0XHRcdFx0aWYgKCBldmVudC5wYWdlWSAtIHRoaXMub2Zmc2V0LmNsaWNrLnRvcCA8IHRoaXMuY29udGFpbm1lbnRbIDEgXSApIHtcblx0XHRcdFx0XHRwYWdlWSA9IHRoaXMuY29udGFpbm1lbnRbIDEgXSArIHRoaXMub2Zmc2V0LmNsaWNrLnRvcDtcblx0XHRcdFx0fVxuXHRcdFx0XHRpZiAoIGV2ZW50LnBhZ2VYIC0gdGhpcy5vZmZzZXQuY2xpY2subGVmdCA+IHRoaXMuY29udGFpbm1lbnRbIDIgXSApIHtcblx0XHRcdFx0XHRwYWdlWCA9IHRoaXMuY29udGFpbm1lbnRbIDIgXSArIHRoaXMub2Zmc2V0LmNsaWNrLmxlZnQ7XG5cdFx0XHRcdH1cblx0XHRcdFx0aWYgKCBldmVudC5wYWdlWSAtIHRoaXMub2Zmc2V0LmNsaWNrLnRvcCA+IHRoaXMuY29udGFpbm1lbnRbIDMgXSApIHtcblx0XHRcdFx0XHRwYWdlWSA9IHRoaXMuY29udGFpbm1lbnRbIDMgXSArIHRoaXMub2Zmc2V0LmNsaWNrLnRvcDtcblx0XHRcdFx0fVxuXHRcdFx0fVxuXG5cdFx0XHRpZiAoIG8uZ3JpZCApIHtcblx0XHRcdFx0dG9wID0gdGhpcy5vcmlnaW5hbFBhZ2VZICsgTWF0aC5yb3VuZCggKCBwYWdlWSAtIHRoaXMub3JpZ2luYWxQYWdlWSApIC9cblx0XHRcdFx0XHRvLmdyaWRbIDEgXSApICogby5ncmlkWyAxIF07XG5cdFx0XHRcdHBhZ2VZID0gdGhpcy5jb250YWlubWVudCA/XG5cdFx0XHRcdFx0KCAoIHRvcCAtIHRoaXMub2Zmc2V0LmNsaWNrLnRvcCA+PSB0aGlzLmNvbnRhaW5tZW50WyAxIF0gJiZcblx0XHRcdFx0XHRcdHRvcCAtIHRoaXMub2Zmc2V0LmNsaWNrLnRvcCA8PSB0aGlzLmNvbnRhaW5tZW50WyAzIF0gKSA/XG5cdFx0XHRcdFx0XHRcdHRvcCA6XG5cdFx0XHRcdFx0XHRcdCggKCB0b3AgLSB0aGlzLm9mZnNldC5jbGljay50b3AgPj0gdGhpcy5jb250YWlubWVudFsgMSBdICkgP1xuXHRcdFx0XHRcdFx0XHRcdHRvcCAtIG8uZ3JpZFsgMSBdIDogdG9wICsgby5ncmlkWyAxIF0gKSApIDpcblx0XHRcdFx0XHRcdFx0XHR0b3A7XG5cblx0XHRcdFx0bGVmdCA9IHRoaXMub3JpZ2luYWxQYWdlWCArIE1hdGgucm91bmQoICggcGFnZVggLSB0aGlzLm9yaWdpbmFsUGFnZVggKSAvXG5cdFx0XHRcdFx0by5ncmlkWyAwIF0gKSAqIG8uZ3JpZFsgMCBdO1xuXHRcdFx0XHRwYWdlWCA9IHRoaXMuY29udGFpbm1lbnQgP1xuXHRcdFx0XHRcdCggKCBsZWZ0IC0gdGhpcy5vZmZzZXQuY2xpY2subGVmdCA+PSB0aGlzLmNvbnRhaW5tZW50WyAwIF0gJiZcblx0XHRcdFx0XHRcdGxlZnQgLSB0aGlzLm9mZnNldC5jbGljay5sZWZ0IDw9IHRoaXMuY29udGFpbm1lbnRbIDIgXSApID9cblx0XHRcdFx0XHRcdFx0bGVmdCA6XG5cdFx0XHRcdFx0XHRcdCggKCBsZWZ0IC0gdGhpcy5vZmZzZXQuY2xpY2subGVmdCA+PSB0aGlzLmNvbnRhaW5tZW50WyAwIF0gKSA/XG5cdFx0XHRcdFx0XHRcdFx0bGVmdCAtIG8uZ3JpZFsgMCBdIDogbGVmdCArIG8uZ3JpZFsgMCBdICkgKSA6XG5cdFx0XHRcdFx0XHRcdFx0bGVmdDtcblx0XHRcdH1cblxuXHRcdH1cblxuXHRcdHJldHVybiB7XG5cdFx0XHR0b3A6IChcblxuXHRcdFx0XHQvLyBUaGUgYWJzb2x1dGUgbW91c2UgcG9zaXRpb25cblx0XHRcdFx0cGFnZVkgLVxuXG5cdFx0XHRcdC8vIENsaWNrIG9mZnNldCAocmVsYXRpdmUgdG8gdGhlIGVsZW1lbnQpXG5cdFx0XHRcdHRoaXMub2Zmc2V0LmNsaWNrLnRvcCAtXG5cblx0XHRcdFx0Ly8gT25seSBmb3IgcmVsYXRpdmUgcG9zaXRpb25lZCBub2RlczogUmVsYXRpdmUgb2Zmc2V0IGZyb20gZWxlbWVudCB0byBvZmZzZXQgcGFyZW50XG5cdFx0XHRcdHRoaXMub2Zmc2V0LnJlbGF0aXZlLnRvcCAtXG5cblx0XHRcdFx0Ly8gVGhlIG9mZnNldFBhcmVudCdzIG9mZnNldCB3aXRob3V0IGJvcmRlcnMgKG9mZnNldCArIGJvcmRlcilcblx0XHRcdFx0dGhpcy5vZmZzZXQucGFyZW50LnRvcCArXG5cdFx0XHRcdCggKCB0aGlzLmNzc1Bvc2l0aW9uID09PSBcImZpeGVkXCIgP1xuXHRcdFx0XHRcdC10aGlzLnNjcm9sbFBhcmVudC5zY3JvbGxUb3AoKSA6XG5cdFx0XHRcdFx0KCBzY3JvbGxJc1Jvb3ROb2RlID8gMCA6IHNjcm9sbC5zY3JvbGxUb3AoKSApICkgKVxuXHRcdFx0KSxcblx0XHRcdGxlZnQ6IChcblxuXHRcdFx0XHQvLyBUaGUgYWJzb2x1dGUgbW91c2UgcG9zaXRpb25cblx0XHRcdFx0cGFnZVggLVxuXG5cdFx0XHRcdC8vIENsaWNrIG9mZnNldCAocmVsYXRpdmUgdG8gdGhlIGVsZW1lbnQpXG5cdFx0XHRcdHRoaXMub2Zmc2V0LmNsaWNrLmxlZnQgLVxuXG5cdFx0XHRcdC8vIE9ubHkgZm9yIHJlbGF0aXZlIHBvc2l0aW9uZWQgbm9kZXM6IFJlbGF0aXZlIG9mZnNldCBmcm9tIGVsZW1lbnQgdG8gb2Zmc2V0IHBhcmVudFxuXHRcdFx0XHR0aGlzLm9mZnNldC5yZWxhdGl2ZS5sZWZ0IC1cblxuXHRcdFx0XHQvLyBUaGUgb2Zmc2V0UGFyZW50J3Mgb2Zmc2V0IHdpdGhvdXQgYm9yZGVycyAob2Zmc2V0ICsgYm9yZGVyKVxuXHRcdFx0XHR0aGlzLm9mZnNldC5wYXJlbnQubGVmdCArXG5cdFx0XHRcdCggKCB0aGlzLmNzc1Bvc2l0aW9uID09PSBcImZpeGVkXCIgP1xuXHRcdFx0XHRcdC10aGlzLnNjcm9sbFBhcmVudC5zY3JvbGxMZWZ0KCkgOlxuXHRcdFx0XHRcdHNjcm9sbElzUm9vdE5vZGUgPyAwIDogc2Nyb2xsLnNjcm9sbExlZnQoKSApIClcblx0XHRcdClcblx0XHR9O1xuXG5cdH0sXG5cblx0X3JlYXJyYW5nZTogZnVuY3Rpb24oIGV2ZW50LCBpLCBhLCBoYXJkUmVmcmVzaCApIHtcblxuXHRcdGEgPyBhWyAwIF0uYXBwZW5kQ2hpbGQoIHRoaXMucGxhY2Vob2xkZXJbIDAgXSApIDpcblx0XHRcdGkuaXRlbVsgMCBdLnBhcmVudE5vZGUuaW5zZXJ0QmVmb3JlKCB0aGlzLnBsYWNlaG9sZGVyWyAwIF0sXG5cdFx0XHRcdCggdGhpcy5kaXJlY3Rpb24gPT09IFwiZG93blwiID8gaS5pdGVtWyAwIF0gOiBpLml0ZW1bIDAgXS5uZXh0U2libGluZyApICk7XG5cblx0XHQvL1ZhcmlvdXMgdGhpbmdzIGRvbmUgaGVyZSB0byBpbXByb3ZlIHRoZSBwZXJmb3JtYW5jZTpcblx0XHQvLyAxLiB3ZSBjcmVhdGUgYSBzZXRUaW1lb3V0LCB0aGF0IGNhbGxzIHJlZnJlc2hQb3NpdGlvbnNcblx0XHQvLyAyLiBvbiB0aGUgaW5zdGFuY2UsIHdlIGhhdmUgYSBjb3VudGVyIHZhcmlhYmxlLCB0aGF0IGdldCdzIGhpZ2hlciBhZnRlciBldmVyeSBhcHBlbmRcblx0XHQvLyAzLiBvbiB0aGUgbG9jYWwgc2NvcGUsIHdlIGNvcHkgdGhlIGNvdW50ZXIgdmFyaWFibGUsIGFuZCBjaGVjayBpbiB0aGUgdGltZW91dCxcblx0XHQvLyBpZiBpdCdzIHN0aWxsIHRoZSBzYW1lXG5cdFx0Ly8gNC4gdGhpcyBsZXRzIG9ubHkgdGhlIGxhc3QgYWRkaXRpb24gdG8gdGhlIHRpbWVvdXQgc3RhY2sgdGhyb3VnaFxuXHRcdHRoaXMuY291bnRlciA9IHRoaXMuY291bnRlciA/ICsrdGhpcy5jb3VudGVyIDogMTtcblx0XHR2YXIgY291bnRlciA9IHRoaXMuY291bnRlcjtcblxuXHRcdHRoaXMuX2RlbGF5KCBmdW5jdGlvbigpIHtcblx0XHRcdGlmICggY291bnRlciA9PT0gdGhpcy5jb3VudGVyICkge1xuXG5cdFx0XHRcdC8vUHJlY29tcHV0ZSBhZnRlciBlYWNoIERPTSBpbnNlcnRpb24sIE5PVCBvbiBtb3VzZW1vdmVcblx0XHRcdFx0dGhpcy5yZWZyZXNoUG9zaXRpb25zKCAhaGFyZFJlZnJlc2ggKTtcblx0XHRcdH1cblx0XHR9ICk7XG5cblx0fSxcblxuXHRfY2xlYXI6IGZ1bmN0aW9uKCBldmVudCwgbm9Qcm9wYWdhdGlvbiApIHtcblxuXHRcdHRoaXMucmV2ZXJ0aW5nID0gZmFsc2U7XG5cblx0XHQvLyBXZSBkZWxheSBhbGwgZXZlbnRzIHRoYXQgaGF2ZSB0byBiZSB0cmlnZ2VyZWQgdG8gYWZ0ZXIgdGhlIHBvaW50IHdoZXJlIHRoZSBwbGFjZWhvbGRlclxuXHRcdC8vIGhhcyBiZWVuIHJlbW92ZWQgYW5kIGV2ZXJ5dGhpbmcgZWxzZSBub3JtYWxpemVkIGFnYWluXG5cdFx0dmFyIGksXG5cdFx0XHRkZWxheWVkVHJpZ2dlcnMgPSBbXTtcblxuXHRcdC8vIFdlIGZpcnN0IGhhdmUgdG8gdXBkYXRlIHRoZSBkb20gcG9zaXRpb24gb2YgdGhlIGFjdHVhbCBjdXJyZW50SXRlbVxuXHRcdC8vIE5vdGU6IGRvbid0IGRvIGl0IGlmIHRoZSBjdXJyZW50IGl0ZW0gaXMgYWxyZWFkeSByZW1vdmVkIChieSBhIHVzZXIpLCBvciBpdCBnZXRzXG5cdFx0Ly8gcmVhcHBlbmRlZCAoc2VlICM0MDg4KVxuXHRcdGlmICggIXRoaXMuX25vRmluYWxTb3J0ICYmIHRoaXMuY3VycmVudEl0ZW0ucGFyZW50KCkubGVuZ3RoICkge1xuXHRcdFx0dGhpcy5wbGFjZWhvbGRlci5iZWZvcmUoIHRoaXMuY3VycmVudEl0ZW0gKTtcblx0XHR9XG5cdFx0dGhpcy5fbm9GaW5hbFNvcnQgPSBudWxsO1xuXG5cdFx0aWYgKCB0aGlzLmhlbHBlclsgMCBdID09PSB0aGlzLmN1cnJlbnRJdGVtWyAwIF0gKSB7XG5cdFx0XHRmb3IgKCBpIGluIHRoaXMuX3N0b3JlZENTUyApIHtcblx0XHRcdFx0aWYgKCB0aGlzLl9zdG9yZWRDU1NbIGkgXSA9PT0gXCJhdXRvXCIgfHwgdGhpcy5fc3RvcmVkQ1NTWyBpIF0gPT09IFwic3RhdGljXCIgKSB7XG5cdFx0XHRcdFx0dGhpcy5fc3RvcmVkQ1NTWyBpIF0gPSBcIlwiO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0XHR0aGlzLmN1cnJlbnRJdGVtLmNzcyggdGhpcy5fc3RvcmVkQ1NTICk7XG5cdFx0XHR0aGlzLl9yZW1vdmVDbGFzcyggdGhpcy5jdXJyZW50SXRlbSwgXCJ1aS1zb3J0YWJsZS1oZWxwZXJcIiApO1xuXHRcdH0gZWxzZSB7XG5cdFx0XHR0aGlzLmN1cnJlbnRJdGVtLnNob3coKTtcblx0XHR9XG5cblx0XHRpZiAoIHRoaXMuZnJvbU91dHNpZGUgJiYgIW5vUHJvcGFnYXRpb24gKSB7XG5cdFx0XHRkZWxheWVkVHJpZ2dlcnMucHVzaCggZnVuY3Rpb24oIGV2ZW50ICkge1xuXHRcdFx0XHR0aGlzLl90cmlnZ2VyKCBcInJlY2VpdmVcIiwgZXZlbnQsIHRoaXMuX3VpSGFzaCggdGhpcy5mcm9tT3V0c2lkZSApICk7XG5cdFx0XHR9ICk7XG5cdFx0fVxuXHRcdGlmICggKCB0aGlzLmZyb21PdXRzaWRlIHx8XG5cdFx0XHRcdHRoaXMuZG9tUG9zaXRpb24ucHJldiAhPT1cblx0XHRcdFx0dGhpcy5jdXJyZW50SXRlbS5wcmV2KCkubm90KCBcIi51aS1zb3J0YWJsZS1oZWxwZXJcIiApWyAwIF0gfHxcblx0XHRcdFx0dGhpcy5kb21Qb3NpdGlvbi5wYXJlbnQgIT09IHRoaXMuY3VycmVudEl0ZW0ucGFyZW50KClbIDAgXSApICYmICFub1Byb3BhZ2F0aW9uICkge1xuXG5cdFx0XHQvLyBUcmlnZ2VyIHVwZGF0ZSBjYWxsYmFjayBpZiB0aGUgRE9NIHBvc2l0aW9uIGhhcyBjaGFuZ2VkXG5cdFx0XHRkZWxheWVkVHJpZ2dlcnMucHVzaCggZnVuY3Rpb24oIGV2ZW50ICkge1xuXHRcdFx0XHR0aGlzLl90cmlnZ2VyKCBcInVwZGF0ZVwiLCBldmVudCwgdGhpcy5fdWlIYXNoKCkgKTtcblx0XHRcdH0gKTtcblx0XHR9XG5cblx0XHQvLyBDaGVjayBpZiB0aGUgaXRlbXMgQ29udGFpbmVyIGhhcyBDaGFuZ2VkIGFuZCB0cmlnZ2VyIGFwcHJvcHJpYXRlXG5cdFx0Ly8gZXZlbnRzLlxuXHRcdGlmICggdGhpcyAhPT0gdGhpcy5jdXJyZW50Q29udGFpbmVyICkge1xuXHRcdFx0aWYgKCAhbm9Qcm9wYWdhdGlvbiApIHtcblx0XHRcdFx0ZGVsYXllZFRyaWdnZXJzLnB1c2goIGZ1bmN0aW9uKCBldmVudCApIHtcblx0XHRcdFx0XHR0aGlzLl90cmlnZ2VyKCBcInJlbW92ZVwiLCBldmVudCwgdGhpcy5fdWlIYXNoKCkgKTtcblx0XHRcdFx0fSApO1xuXHRcdFx0XHRkZWxheWVkVHJpZ2dlcnMucHVzaCggKCBmdW5jdGlvbiggYyApIHtcblx0XHRcdFx0XHRyZXR1cm4gZnVuY3Rpb24oIGV2ZW50ICkge1xuXHRcdFx0XHRcdFx0Yy5fdHJpZ2dlciggXCJyZWNlaXZlXCIsIGV2ZW50LCB0aGlzLl91aUhhc2goIHRoaXMgKSApO1xuXHRcdFx0XHRcdH07XG5cdFx0XHRcdH0gKS5jYWxsKCB0aGlzLCB0aGlzLmN1cnJlbnRDb250YWluZXIgKSApO1xuXHRcdFx0XHRkZWxheWVkVHJpZ2dlcnMucHVzaCggKCBmdW5jdGlvbiggYyApIHtcblx0XHRcdFx0XHRyZXR1cm4gZnVuY3Rpb24oIGV2ZW50ICkge1xuXHRcdFx0XHRcdFx0Yy5fdHJpZ2dlciggXCJ1cGRhdGVcIiwgZXZlbnQsIHRoaXMuX3VpSGFzaCggdGhpcyApICk7XG5cdFx0XHRcdFx0fTtcblx0XHRcdFx0fSApLmNhbGwoIHRoaXMsIHRoaXMuY3VycmVudENvbnRhaW5lciApICk7XG5cdFx0XHR9XG5cdFx0fVxuXG5cdFx0Ly9Qb3N0IGV2ZW50cyB0byBjb250YWluZXJzXG5cdFx0ZnVuY3Rpb24gZGVsYXlFdmVudCggdHlwZSwgaW5zdGFuY2UsIGNvbnRhaW5lciApIHtcblx0XHRcdHJldHVybiBmdW5jdGlvbiggZXZlbnQgKSB7XG5cdFx0XHRcdGNvbnRhaW5lci5fdHJpZ2dlciggdHlwZSwgZXZlbnQsIGluc3RhbmNlLl91aUhhc2goIGluc3RhbmNlICkgKTtcblx0XHRcdH07XG5cdFx0fVxuXHRcdGZvciAoIGkgPSB0aGlzLmNvbnRhaW5lcnMubGVuZ3RoIC0gMTsgaSA+PSAwOyBpLS0gKSB7XG5cdFx0XHRpZiAoICFub1Byb3BhZ2F0aW9uICkge1xuXHRcdFx0XHRkZWxheWVkVHJpZ2dlcnMucHVzaCggZGVsYXlFdmVudCggXCJkZWFjdGl2YXRlXCIsIHRoaXMsIHRoaXMuY29udGFpbmVyc1sgaSBdICkgKTtcblx0XHRcdH1cblx0XHRcdGlmICggdGhpcy5jb250YWluZXJzWyBpIF0uY29udGFpbmVyQ2FjaGUub3ZlciApIHtcblx0XHRcdFx0ZGVsYXllZFRyaWdnZXJzLnB1c2goIGRlbGF5RXZlbnQoIFwib3V0XCIsIHRoaXMsIHRoaXMuY29udGFpbmVyc1sgaSBdICkgKTtcblx0XHRcdFx0dGhpcy5jb250YWluZXJzWyBpIF0uY29udGFpbmVyQ2FjaGUub3ZlciA9IDA7XG5cdFx0XHR9XG5cdFx0fVxuXG5cdFx0Ly9EbyB3aGF0IHdhcyBvcmlnaW5hbGx5IGluIHBsdWdpbnNcblx0XHRpZiAoIHRoaXMuc3RvcmVkQ3Vyc29yICkge1xuXHRcdFx0dGhpcy5kb2N1bWVudC5maW5kKCBcImJvZHlcIiApLmNzcyggXCJjdXJzb3JcIiwgdGhpcy5zdG9yZWRDdXJzb3IgKTtcblx0XHRcdHRoaXMuc3RvcmVkU3R5bGVzaGVldC5yZW1vdmUoKTtcblx0XHR9XG5cdFx0aWYgKCB0aGlzLl9zdG9yZWRPcGFjaXR5ICkge1xuXHRcdFx0dGhpcy5oZWxwZXIuY3NzKCBcIm9wYWNpdHlcIiwgdGhpcy5fc3RvcmVkT3BhY2l0eSApO1xuXHRcdH1cblx0XHRpZiAoIHRoaXMuX3N0b3JlZFpJbmRleCApIHtcblx0XHRcdHRoaXMuaGVscGVyLmNzcyggXCJ6SW5kZXhcIiwgdGhpcy5fc3RvcmVkWkluZGV4ID09PSBcImF1dG9cIiA/IFwiXCIgOiB0aGlzLl9zdG9yZWRaSW5kZXggKTtcblx0XHR9XG5cblx0XHR0aGlzLmRyYWdnaW5nID0gZmFsc2U7XG5cblx0XHRpZiAoICFub1Byb3BhZ2F0aW9uICkge1xuXHRcdFx0dGhpcy5fdHJpZ2dlciggXCJiZWZvcmVTdG9wXCIsIGV2ZW50LCB0aGlzLl91aUhhc2goKSApO1xuXHRcdH1cblxuXHRcdC8vJCh0aGlzLnBsYWNlaG9sZGVyWzBdKS5yZW1vdmUoKTsgd291bGQgaGF2ZSBiZWVuIHRoZSBqUXVlcnkgd2F5IC0gdW5mb3J0dW5hdGVseSxcblx0XHQvLyBpdCB1bmJpbmRzIEFMTCBldmVudHMgZnJvbSB0aGUgb3JpZ2luYWwgbm9kZSFcblx0XHR0aGlzLnBsYWNlaG9sZGVyWyAwIF0ucGFyZW50Tm9kZS5yZW1vdmVDaGlsZCggdGhpcy5wbGFjZWhvbGRlclsgMCBdICk7XG5cblx0XHRpZiAoICF0aGlzLmNhbmNlbEhlbHBlclJlbW92YWwgKSB7XG5cdFx0XHRpZiAoIHRoaXMuaGVscGVyWyAwIF0gIT09IHRoaXMuY3VycmVudEl0ZW1bIDAgXSApIHtcblx0XHRcdFx0dGhpcy5oZWxwZXIucmVtb3ZlKCk7XG5cdFx0XHR9XG5cdFx0XHR0aGlzLmhlbHBlciA9IG51bGw7XG5cdFx0fVxuXG5cdFx0aWYgKCAhbm9Qcm9wYWdhdGlvbiApIHtcblx0XHRcdGZvciAoIGkgPSAwOyBpIDwgZGVsYXllZFRyaWdnZXJzLmxlbmd0aDsgaSsrICkge1xuXG5cdFx0XHRcdC8vIFRyaWdnZXIgYWxsIGRlbGF5ZWQgZXZlbnRzXG5cdFx0XHRcdGRlbGF5ZWRUcmlnZ2Vyc1sgaSBdLmNhbGwoIHRoaXMsIGV2ZW50ICk7XG5cdFx0XHR9XG5cdFx0XHR0aGlzLl90cmlnZ2VyKCBcInN0b3BcIiwgZXZlbnQsIHRoaXMuX3VpSGFzaCgpICk7XG5cdFx0fVxuXG5cdFx0dGhpcy5mcm9tT3V0c2lkZSA9IGZhbHNlO1xuXHRcdHJldHVybiAhdGhpcy5jYW5jZWxIZWxwZXJSZW1vdmFsO1xuXG5cdH0sXG5cblx0X3RyaWdnZXI6IGZ1bmN0aW9uKCkge1xuXHRcdGlmICggJC5XaWRnZXQucHJvdG90eXBlLl90cmlnZ2VyLmFwcGx5KCB0aGlzLCBhcmd1bWVudHMgKSA9PT0gZmFsc2UgKSB7XG5cdFx0XHR0aGlzLmNhbmNlbCgpO1xuXHRcdH1cblx0fSxcblxuXHRfdWlIYXNoOiBmdW5jdGlvbiggX2luc3QgKSB7XG5cdFx0dmFyIGluc3QgPSBfaW5zdCB8fCB0aGlzO1xuXHRcdHJldHVybiB7XG5cdFx0XHRoZWxwZXI6IGluc3QuaGVscGVyLFxuXHRcdFx0cGxhY2Vob2xkZXI6IGluc3QucGxhY2Vob2xkZXIgfHwgJCggW10gKSxcblx0XHRcdHBvc2l0aW9uOiBpbnN0LnBvc2l0aW9uLFxuXHRcdFx0b3JpZ2luYWxQb3NpdGlvbjogaW5zdC5vcmlnaW5hbFBvc2l0aW9uLFxuXHRcdFx0b2Zmc2V0OiBpbnN0LnBvc2l0aW9uQWJzLFxuXHRcdFx0aXRlbTogaW5zdC5jdXJyZW50SXRlbSxcblx0XHRcdHNlbmRlcjogX2luc3QgPyBfaW5zdC5lbGVtZW50IDogbnVsbFxuXHRcdH07XG5cdH1cblxufSApO1xuXG59ICkgKTtcblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vbm9kZV9tb2R1bGVzL2pxdWVyeS11aS91aS93aWRnZXRzL3NvcnRhYmxlLmpzXG4vLyBtb2R1bGUgaWQgPSAuL25vZGVfbW9kdWxlcy9qcXVlcnktdWkvdWkvd2lkZ2V0cy9zb3J0YWJsZS5qc1xuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCJyZXF1aXJlKCdqcXVlcnktdWkvdWkvd2lkZ2V0cy9zb3J0YWJsZScpO1xucmVxdWlyZSgnanF1ZXJ5LXVpL3VpL2Rpc2FibGUtc2VsZWN0aW9uJyk7XG5cbmZ1bmN0aW9uIGFkZE5ld1BhcnRpY2lwYW50KGNvbGxlY3Rpb25Ib2xkZXIpIHtcblxuICAgIC8vIHJlbW92ZSAubm9pdGVtcyBpZiBwcmVzZW50XG4gICAgJCgnLm5vaXRlbXMnKS5yZW1vdmUoKTtcblxuICAgIC8vIEdldCBwYXJ0aWNpcGFudCBwcm90b3R5cGUgYXMgZGVmaW5lZCBpbiBhdHRyaWJ1dGUgZGF0YS1wcm90b3R5cGVcbiAgICB2YXIgcHJvdG90eXBlID0gY29sbGVjdGlvbkhvbGRlci5hdHRyKCdkYXRhLXByb3RvdHlwZScpO1xuICAgIC8vIEFkanVzdCBwYXJ0aWNpcGFudCBwcm90b3R5cGUgZm9yIGNvcnJlY3QgbmFtaW5nXG4gICAgdmFyIG51bWJlcl9vZl9wYXJ0aWNpcGFudHMgPSBjb2xsZWN0aW9uSG9sZGVyLmNoaWxkcmVuKCkubGVuZ3RoOyAvLyBOb3RlLCBvd25lciBpcyBub3QgY291bnRlZCBhcyBwYXJ0aWNpcGFudFxuICAgIHZhciBuZXdGb3JtSHRtbCA9IHByb3RvdHlwZS5yZXBsYWNlKC9fX25hbWVfXy9nLCBudW1iZXJfb2ZfcGFydGljaXBhbnRzKS5yZXBsYWNlKC9fX3BhcnRpY2lwYW50Y291bnRfXy9nLCBudW1iZXJfb2ZfcGFydGljaXBhbnRzICsgMSk7XG5cbiAgICAvLyBBZGQgbmV3IHBhcnRpY2lwYW50IHRvIHBhcnR5IHdpdGggYW5pbWF0aW9uXG4gICAgdmFyIG5ld0Zvcm0gPSAkKG5ld0Zvcm1IdG1sKTtcbiAgICBjb2xsZWN0aW9uSG9sZGVyLmFwcGVuZChuZXdGb3JtKTtcbiAgICBuZXdGb3JtLnNob3coMzAwKTtcblxuICAgIC8vIEhhbmRsZSBkZWxldGUgYnV0dG9uIGV2ZW50c1xuICAgIGJpbmREZWxldGVCdXR0b25FdmVudHMoKTtcblxuICAgIC8vIFJlbW92ZSBkaXNhYmxlZCBzdGF0ZSBvbiBkZWxldGUtYnV0dG9uc1xuICAgICQoJy5yZW1vdmUtcGFydGljaXBhbnQnKS5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcblxuICAgIC8vIHJlc2V0IHJhbmtzXG4gICAgcmVzZXRSYW5rcygpO1xuXG59XG5cbmZ1bmN0aW9uIGJpbmREZWxldGVCdXR0b25FdmVudHMoKSB7XG4gICAgLy8gTG9vcCBvdmVyIGFsbCBkZWxldGUgYnV0dG9uc1xuICAgICQoJ2J1dHRvbi5yZW1vdmUtcGFydGljaXBhbnQnKS5lYWNoKGZ1bmN0aW9uIChpKSB7XG4gICAgICAgIC8vIFJlbW92ZSBhbnkgcHJldmlvdXNseSBiaW5kZWQgZXZlbnRcbiAgICAgICAgJCh0aGlzKS5vZmYoJ2NsaWNrJyk7XG5cbiAgICAgICAgLy8gQmluZCBldmVudFxuICAgICAgICAkKHRoaXMpLmNsaWNrKGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG5cbiAgICAgICAgICAgIGlmICgkKHRoaXMpLnBhcmVudHMoXCJ0ci53aXNobGlzdGl0ZW1cIikuaGFzQ2xhc3MoJ25ldy1yb3cnKSkge1xuICAgICAgICAgICAgICAgICQodGhpcykucGFyZW50cyhcInRyLndpc2hsaXN0aXRlbVwiKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICAkKCcuYWRkLW5ldy1wYXJ0aWNpcGFudCcpLnNob3coKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgdmFyIG5ld1Jvd1ZhbHVlID0gJCgnLm5ldy1yb3cgLndpc2hsaXN0aXRlbS1kZXNjcmlwdGlvbicpLnZhbCgpO1xuICAgICAgICAgICAgaWYgKHR5cGVvZiBuZXdSb3dWYWx1ZSAhPSAndW5kZWZpbmVkJyAmJiBuZXdSb3dWYWx1ZSA9PSAnJykge1xuICAgICAgICAgICAgICAgICQoJy5uZXctcm93JykucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgJCgnLmFkZC1uZXctcGFydGljaXBhbnQnKS5zaG93KCk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIC8vIFhYWDogbGFzdCBpdGVtIGNhbid0IGJlIHJlbW92ZWQgYmVjYXVzZSBhbiBlbXB0eSBmb3JtIGNhbid0IGJlIHNhdmVkLiBUaGlzIGlzIGEgd29ya2Fyb3VuZFxuICAgICAgICAgICAgaWYgKCQoJy53aXNobGlzdGl0ZW0tZGVzY3JpcHRpb24nKS5sZW5ndGggPT0gMSl7XG4gICAgICAgICAgICAgICAgJCgnLndpc2hsaXN0aXRlbS1kZXNjcmlwdGlvbicpLnZhbCgnJyk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICQodGhpcykucGFyZW50cyhcInRyLndpc2hsaXN0aXRlbVwiKS5yZW1vdmUoKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgJCgnLmFqYXgtcmVzcG9uc2UnKS5jaGlsZHJlbigpLmhpZGUoKTtcbiAgICAgICAgICAgICQoJy5hamF4LXJlc3BvbnNlIC5lbXB0eScpLmhpZGUoKTtcbiAgICAgICAgICAgICQoJy5hamF4LXJlc3BvbnNlIC5yZW1vdmVkJykuc2hvdygpO1xuXG4gICAgICAgICAgICByZXNldFJhbmtzKCk7XG4gICAgICAgICAgICBhamF4U2F2ZVdpc2hsaXN0KCk7XG4gICAgICAgIH0pO1xuICAgIH0pO1xufVxuXG5mdW5jdGlvbiByZXNldFJhbmtzKCkge1xuICAgICQoJ3RhYmxlLndpc2hsaXN0LWl0ZW1zIHRib2R5IHRyJykuZWFjaChmdW5jdGlvbiAoaSkge1xuICAgICAgICAkKHRoaXMpLmZpbmQoJ3RkIGlucHV0W3R5cGU9XCJoaWRkZW5cIl0nKS52YWwoaSArIDEpO1xuICAgICAgICAkKHRoaXMpLmZpbmQoJ3RkIHNwYW4ucmFuaycpLnRleHQoaSArIDEpO1xuICAgIH0pO1xufVxuXG5mdW5jdGlvbiBhamF4U2F2ZVdpc2hsaXN0KCkge1xuICAgIHZhciBuZXdSb3dWYWx1ZSA9ICQoJy5uZXctcm93IC53aXNobGlzdGl0ZW0tZGVzY3JpcHRpb24nKS52YWwoKTtcbiAgICBpZiAodHlwZW9mIG5ld1Jvd1ZhbHVlICE9ICd1bmRlZmluZWQnICYmIG5ld1Jvd1ZhbHVlID09ICcnKSB7XG4gICAgICAgICQoJy5hamF4LXJlc3BvbnNlIC5lbXB0eScpLnNob3coKTtcblxuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgfVxuXG4gICAgJCgnLmFqYXgtcmVzcG9uc2UgLmVtcHR5JykuaGlkZSgpO1xuICAgIHZhciBmb3JtRGF0YSA9ICQoJyNhZGRfaXRlbV90b193aXNobGlzdF9mb3JtJykuc2VyaWFsaXplQXJyYXkoKTtcbiAgICB2YXIgdXJsID0gJCgnI2FkZF9pdGVtX3RvX3dpc2hsaXN0X2Zvcm0nKS5hdHRyKCdhY3Rpb24nKVxuXG4gICAgJC5hamF4KHtcbiAgICAgICAgdHlwZTogJ1BPU1QnLFxuICAgICAgICB1cmw6IHVybCxcbiAgICAgICAgZGF0YTogZm9ybURhdGFcbiAgICB9KTtcbn1cblxuLyogVmFyaWFibGVzICovXG52YXIgY29sbGVjdGlvbkhvbGRlciA9ICQoJ3RhYmxlLndpc2hsaXN0LWl0ZW1zIHRib2R5Jyk7XG5cbi8qIERvY3VtZW50IFJlYWR5ICovXG5qUXVlcnkoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uICgpIHtcblxuICAgIC8vQWRkIGV2ZW50bGlzdGVuZXIgb24gYWRkLW5ldy1wYXJ0aWNpcGFudCBidXR0b25cbiAgICAkKCcuYWRkLW5ldy1wYXJ0aWNpcGFudCcpLmNsaWNrKGZ1bmN0aW9uIChlKSB7XG4gICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcblxuICAgICAgICAkKCcuYWRkLW5ldy1wYXJ0aWNpcGFudCcpLmhpZGUoKTtcblxuICAgICAgICBhZGROZXdQYXJ0aWNpcGFudChjb2xsZWN0aW9uSG9sZGVyKTtcbiAgICB9KTtcblxuICAgICQoJy51cGRhdGUtcGFydGljaXBhbnQnKS5jbGljayhmdW5jdGlvbiAoZSkge1xuICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG5cbiAgICAgICAgJCh0aGlzKS5oaWRlKCk7XG4gICAgICAgICQoJy5hamF4LXJlc3BvbnNlJykuY2hpbGRyZW4oKS5oaWRlKCk7XG4gICAgICAgICQoJy5hamF4LXJlc3BvbnNlIC51cGRhdGVkJykuc2hvdygpO1xuXG4gICAgICAgIGFqYXhTYXZlV2lzaGxpc3QoKTtcbiAgICB9KTtcblxuICAgICQoJyNhZGRfaXRlbV90b193aXNobGlzdF9mb3JtJykuc3VibWl0KGZ1bmN0aW9uIChlKSB7XG4gICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcblxuICAgICAgICB2YXIgc3VibWl0QnV0dG9uID0gJCh0aGlzKS5maW5kKCdidXR0b25bdHlwZT1zdWJtaXRdJyk7XG4gICAgICAgIHN1Ym1pdEJ1dHRvbi5oaWRlKCk7XG5cbiAgICAgICAgJCgnLmFkZC1uZXctcGFydGljaXBhbnQnKS5oaWRlKCk7XG4gICAgICAgICQoJy5hamF4LXJlc3BvbnNlJykuY2hpbGRyZW4oKS5oaWRlKCk7XG4gICAgICAgICQoJy5hamF4LXJlc3BvbnNlIC5hZGRlZCcpLnNob3coKTtcbiAgICAgICAgJCgndHIud2lzaGxpc3RpdGVtJykucmVtb3ZlQ2xhc3MoJ25ldy1yb3cnKTtcblxuICAgICAgICBhamF4U2F2ZVdpc2hsaXN0KCk7XG4gICAgICAgIGFkZE5ld1BhcnRpY2lwYW50KGNvbGxlY3Rpb25Ib2xkZXIpO1xuICAgIH0pO1xuXG4gICAgJCgnLndpc2hsaXN0aXRlbScpLm9uKCdrZXlkb3duJywgJy53aXNobGlzdGl0ZW0tZGVzY3JpcHRpb24nLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICQodGhpcykuY2xvc2VzdCgnLndpc2hsaXN0aXRlbScpLmZpbmQoJ2J1dHRvbi51cGRhdGUtcGFydGljaXBhbnQnKS5zaG93KCk7XG4gICAgfSk7XG5cbiAgICBiaW5kRGVsZXRlQnV0dG9uRXZlbnRzKCk7XG4gICAgJCgnLnJlbW92ZS1wYXJ0aWNpcGFudCcpLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuXG4gICAgLy8gc29ydGFibGVcbiAgICAkKFwidGFibGUud2lzaGxpc3QtaXRlbXMgdGJvZHlcIikuc29ydGFibGUoe1xuICAgICAgICBzdG9wOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICByZXNldFJhbmtzKCk7XG4gICAgICAgICAgICBhamF4U2F2ZVdpc2hsaXN0KCk7XG4gICAgICAgIH1cbiAgICB9KTtcblxuICAgICQoJ3RhYmxlLndpc2hsaXN0LWl0ZW1zIHRib2R5JykuYmluZCgnY2xpY2suc29ydGFibGUgbW91c2Vkb3duLnNvcnRhYmxlJywgZnVuY3Rpb24gKGV2KSB7XG4gICAgICAgIGV2LnRhcmdldC5mb2N1cygpO1xuICAgIH0pO1xuXG59KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL3NyYy9JbnRyYWN0by9TZWNyZXRTYW50YUJ1bmRsZS9SZXNvdXJjZXMvcHVibGljL2pzL3dpc2hsaXN0LmpzIl0sInNvdXJjZVJvb3QiOiIifQ==