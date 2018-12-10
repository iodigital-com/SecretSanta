webpackJsonp([3,4],{

/***/ "./node_modules/jquery-csv/src/jquery.csv.js":
/*!***************************************************!*\
  !*** ./node_modules/jquery-csv/src/jquery.csv.js ***!
  \***************************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(jQuery) {/**
 * jQuery-csv (jQuery Plugin)
 *
 * This document is licensed as free software under the terms of the
 * MIT License: http://www.opensource.org/licenses/mit-license.php
 *
 * Acknowledgements:
 * The original design and influence to implement this library as a jquery
 * plugin is influenced by jquery-json (http://code.google.com/p/jquery-json/).
 * If you're looking to use native JSON.Stringify but want additional backwards
 * compatibility for browsers that don't support it, I highly recommend you
 * check it out.
 *
 * A special thanks goes out to rwk@acm.org for providing a lot of valuable
 * feedback to the project including the core for the new FSM
 * (Finite State Machine) parsers. If you're looking for a stable TSV parser
 * be sure to take a look at jquery-tsv (http://code.google.com/p/jquery-tsv/).

 * For legal purposes I'll include the "NO WARRANTY EXPRESSED OR IMPLIED.
 * USE AT YOUR OWN RISK.". Which, in 'layman's terms' means, by using this
 * library you are accepting responsibility if it breaks your code.
 *
 * Legal jargon aside, I will do my best to provide a useful and stable core
 * that can effectively be built on.
 *
 * Copyrighted 2012 by Evan Plaice.
 */

RegExp.escape= function(s) {
    return s.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
};

(function (undefined) {
  'use strict';

  var $;

  // to keep backwards compatibility
  if (typeof jQuery !== 'undefined' && jQuery) {
    $ = jQuery;
  } else {
    $ = {};
  }


  /**
   * jQuery.csv.defaults
   * Encapsulates the method paramater defaults for the CSV plugin module.
   */

  $.csv = {
    defaults: {
      separator:',',
      delimiter:'"',
      headers:true
    },

    hooks: {
      castToScalar: function(value, state) {
        var hasDot = /\./;
        if (isNaN(value)) {
          return value;
        } else {
          if (hasDot.test(value)) {
            return parseFloat(value);
          } else {
            var integer = parseInt(value);
            if(isNaN(integer)) {
              return null;
            } else {
              return integer;
            }
          }
        }
      }
    },

    parsers: {
      parse: function(csv, options) {
        // cache settings
        var separator = options.separator;
        var delimiter = options.delimiter;

        // set initial state if it's missing
        if(!options.state.rowNum) {
          options.state.rowNum = 1;
        }
        if(!options.state.colNum) {
          options.state.colNum = 1;
        }

        // clear initial state
        var data = [];
        var entry = [];
        var state = 0;
        var value = '';
        var exit = false;

        function endOfEntry() {
          // reset the state
          state = 0;
          value = '';

          // if 'start' hasn't been met, don't output
          if(options.start && options.state.rowNum < options.start) {
            // update global state
            entry = [];
            options.state.rowNum++;
            options.state.colNum = 1;
            return;
          }
          
          if(options.onParseEntry === undefined) {
            // onParseEntry hook not set
            data.push(entry);
          } else {
            var hookVal = options.onParseEntry(entry, options.state); // onParseEntry Hook
            // false skips the row, configurable through a hook
            if(hookVal !== false) {
              data.push(hookVal);
            }
          }
          //console.log('entry:' + entry);
          
          // cleanup
          entry = [];

          // if 'end' is met, stop parsing
          if(options.end && options.state.rowNum >= options.end) {
            exit = true;
          }
          
          // update global state
          options.state.rowNum++;
          options.state.colNum = 1;
        }

        function endOfValue() {
          if(options.onParseValue === undefined) {
            // onParseValue hook not set
            entry.push(value);
          } else {
            var hook = options.onParseValue(value, options.state); // onParseValue Hook
            // false skips the row, configurable through a hook
            if(hook !== false) {
              entry.push(hook);
            }
          }
          //console.log('value:' + value);
          // reset the state
          value = '';
          state = 0;
          // update global state
          options.state.colNum++;
        }

        // escape regex-specific control chars
        var escSeparator = RegExp.escape(separator);
        var escDelimiter = RegExp.escape(delimiter);

        // compile the regEx str using the custom delimiter/separator
        var match = /(D|S|\r\n|\n|\r|[^DS\r\n]+)/;
        var matchSrc = match.source;
        matchSrc = matchSrc.replace(/S/g, escSeparator);
        matchSrc = matchSrc.replace(/D/g, escDelimiter);
        match = new RegExp(matchSrc, 'gm');

        // put on your fancy pants...
        // process control chars individually, use look-ahead on non-control chars
        csv.replace(match, function (m0) {
          if(exit) {
            return;
          }
          switch (state) {
            // the start of a value
            case 0:
              // null last value
              if (m0 === separator) {
                value += '';
                endOfValue();
                break;
              }
              // opening delimiter
              if (m0 === delimiter) {
                state = 1;
                break;
              }
              // null last value
              if (/^(\r\n|\n|\r)$/.test(m0)) {
                endOfValue();
                endOfEntry();
                break;
              }
              // un-delimited value
              value += m0;
              state = 3;
              break;

            // delimited input
            case 1:
              // second delimiter? check further
              if (m0 === delimiter) {
                state = 2;
                break;
              }
              // delimited data
              value += m0;
              state = 1;
              break;

            // delimiter found in delimited input
            case 2:
              // escaped delimiter?
              if (m0 === delimiter) {
                value += m0;
                state = 1;
                break;
              }
              // null value
              if (m0 === separator) {
                endOfValue();
                break;
              }
              // end of entry
              if (/^(\r\n|\n|\r)$/.test(m0)) {
                endOfValue();
                endOfEntry();
                break;
              }
              // broken paser?
              throw new Error('CSVDataError: Illegal State [Row:' + options.state.rowNum + '][Col:' + options.state.colNum + ']');

            // un-delimited input
            case 3:
              // null last value
              if (m0 === separator) {
                endOfValue();
                break;
              }
              // end of entry
              if (/^(\r\n|\n|\r)$/.test(m0)) {
                endOfValue();
                endOfEntry();
                break;
              }
              if (m0 === delimiter) {
              // non-compliant data
                throw new Error('CSVDataError: Illegal Quote [Row:' + options.state.rowNum + '][Col:' + options.state.colNum + ']');
              }
              // broken parser?
              throw new Error('CSVDataError: Illegal Data [Row:' + options.state.rowNum + '][Col:' + options.state.colNum + ']');
            default:
              // shenanigans
              throw new Error('CSVDataError: Unknown State [Row:' + options.state.rowNum + '][Col:' + options.state.colNum + ']');
          }
          //console.log('val:' + m0 + ' state:' + state);
        });

        // submit the last entry
        // ignore null last line
        if(entry.length !== 0) {
          endOfValue();
          endOfEntry();
        }

        return data;
      },

      // a csv-specific line splitter
      splitLines: function(csv, options) {
        // cache settings
        var separator = options.separator;
        var delimiter = options.delimiter;

        // set initial state if it's missing
        if(!options.state.rowNum) {
          options.state.rowNum = 1;
        }

        // clear initial state
        var entries = [];
        var state = 0;
        var entry = '';
        var exit = false;

        function endOfLine() {          
          // reset the state
          state = 0;
          
          // if 'start' hasn't been met, don't output
          if(options.start && options.state.rowNum < options.start) {
            // update global state
            entry = '';
            options.state.rowNum++;
            return;
          }
          
          if(options.onParseEntry === undefined) {
            // onParseEntry hook not set
            entries.push(entry);
          } else {
            var hookVal = options.onParseEntry(entry, options.state); // onParseEntry Hook
            // false skips the row, configurable through a hook
            if(hookVal !== false) {
              entries.push(hookVal);
            }
          }

          // cleanup
          entry = '';

          // if 'end' is met, stop parsing
          if(options.end && options.state.rowNum >= options.end) {
            exit = true;
          }
          
          // update global state
          options.state.rowNum++;
        }

        // escape regex-specific control chars
        var escSeparator = RegExp.escape(separator);
        var escDelimiter = RegExp.escape(delimiter);

        // compile the regEx str using the custom delimiter/separator
        var match = /(D|S|\n|\r|[^DS\r\n]+)/;
        var matchSrc = match.source;
        matchSrc = matchSrc.replace(/S/g, escSeparator);
        matchSrc = matchSrc.replace(/D/g, escDelimiter);
        match = new RegExp(matchSrc, 'gm');

        // put on your fancy pants...
        // process control chars individually, use look-ahead on non-control chars
        csv.replace(match, function (m0) {
          if(exit) {
            return;
          }
          switch (state) {
            // the start of a value/entry
            case 0:
              // null value
              if (m0 === separator) {
                entry += m0;
                state = 0;
                break;
              }
              // opening delimiter
              if (m0 === delimiter) {
                entry += m0;
                state = 1;
                break;
              }
              // end of line
              if (m0 === '\n') {
                endOfLine();
                break;
              }
              // phantom carriage return
              if (/^\r$/.test(m0)) {
                break;
              }
              // un-delimit value
              entry += m0;
              state = 3;
              break;

            // delimited input
            case 1:
              // second delimiter? check further
              if (m0 === delimiter) {
                entry += m0;
                state = 2;
                break;
              }
              // delimited data
              entry += m0;
              state = 1;
              break;

            // delimiter found in delimited input
            case 2:
              // escaped delimiter?
              var prevChar = entry.substr(entry.length - 1);
              if (m0 === delimiter && prevChar === delimiter) {
                entry += m0;
                state = 1;
                break;
              }
              // end of value
              if (m0 === separator) {
                entry += m0;
                state = 0;
                break;
              }
              // end of line
              if (m0 === '\n') {
                endOfLine();
                break;
              }
              // phantom carriage return
              if (m0 === '\r') {
                break;
              }
              // broken paser?
              throw new Error('CSVDataError: Illegal state [Row:' + options.state.rowNum + ']');

            // un-delimited input
            case 3:
              // null value
              if (m0 === separator) {
                entry += m0;
                state = 0;
                break;
              }
              // end of line
              if (m0 === '\n') {
                endOfLine();
                break;
              }
              // phantom carriage return
              if (m0 === '\r') {
                break;
              }
              // non-compliant data
              if (m0 === delimiter) {
                throw new Error('CSVDataError: Illegal quote [Row:' + options.state.rowNum + ']');
              }
              // broken parser?
              throw new Error('CSVDataError: Illegal state [Row:' + options.state.rowNum + ']');
            default:
              // shenanigans
              throw new Error('CSVDataError: Unknown state [Row:' + options.state.rowNum + ']');
          }
          //console.log('val:' + m0 + ' state:' + state);
        });

        // submit the last entry
        // ignore null last line
        if(entry !== '') {
          endOfLine();
        }

        return entries;
      },

      // a csv entry parser
      parseEntry: function(csv, options) {
        // cache settings
        var separator = options.separator;
        var delimiter = options.delimiter;
        
        // set initial state if it's missing
        if(!options.state.rowNum) {
          options.state.rowNum = 1;
        }
        if(!options.state.colNum) {
          options.state.colNum = 1;
        }

        // clear initial state
        var entry = [];
        var state = 0;
        var value = '';

        function endOfValue() {
          if(options.onParseValue === undefined) {
            // onParseValue hook not set
            entry.push(value);
          } else {
            var hook = options.onParseValue(value, options.state); // onParseValue Hook
            // false skips the value, configurable through a hook
            if(hook !== false) {
              entry.push(hook);
            }
          }
          // reset the state
          value = '';
          state = 0;
          // update global state
          options.state.colNum++;
        }

        // checked for a cached regEx first
        if(!options.match) {
          // escape regex-specific control chars
          var escSeparator = RegExp.escape(separator);
          var escDelimiter = RegExp.escape(delimiter);
          
          // compile the regEx str using the custom delimiter/separator
          var match = /(D|S|\n|\r|[^DS\r\n]+)/;
          var matchSrc = match.source;
          matchSrc = matchSrc.replace(/S/g, escSeparator);
          matchSrc = matchSrc.replace(/D/g, escDelimiter);
          options.match = new RegExp(matchSrc, 'gm');
        }

        // put on your fancy pants...
        // process control chars individually, use look-ahead on non-control chars
        csv.replace(options.match, function (m0) {
          switch (state) {
            // the start of a value
            case 0:
              // null last value
              if (m0 === separator) {
                value += '';
                endOfValue();
                break;
              }
              // opening delimiter
              if (m0 === delimiter) {
                state = 1;
                break;
              }
              // skip un-delimited new-lines
              if (m0 === '\n' || m0 === '\r') {
                break;
              }
              // un-delimited value
              value += m0;
              state = 3;
              break;

            // delimited input
            case 1:
              // second delimiter? check further
              if (m0 === delimiter) {
                state = 2;
                break;
              }
              // delimited data
              value += m0;
              state = 1;
              break;

            // delimiter found in delimited input
            case 2:
              // escaped delimiter?
              if (m0 === delimiter) {
                value += m0;
                state = 1;
                break;
              }
              // null value
              if (m0 === separator) {
                endOfValue();
                break;
              }
              // skip un-delimited new-lines
              if (m0 === '\n' || m0 === '\r') {
                break;
              }
              // broken paser?
              throw new Error('CSVDataError: Illegal State [Row:' + options.state.rowNum + '][Col:' + options.state.colNum + ']');

            // un-delimited input
            case 3:
              // null last value
              if (m0 === separator) {
                endOfValue();
                break;
              }
              // skip un-delimited new-lines
              if (m0 === '\n' || m0 === '\r') {
                break;
              }
              // non-compliant data
              if (m0 === delimiter) {
                throw new Error('CSVDataError: Illegal Quote [Row:' + options.state.rowNum + '][Col:' + options.state.colNum + ']');
              }
              // broken parser?
              throw new Error('CSVDataError: Illegal Data [Row:' + options.state.rowNum + '][Col:' + options.state.colNum + ']');
            default:
              // shenanigans
              throw new Error('CSVDataError: Unknown State [Row:' + options.state.rowNum + '][Col:' + options.state.colNum + ']');
          }
          //console.log('val:' + m0 + ' state:' + state);
        });

        // submit the last value
        endOfValue();

        return entry;
      }
    },

    helpers: {

      /**
       * $.csv.helpers.collectPropertyNames(objectsArray)
       * Collects all unique property names from all passed objects.
       *
       * @param {Array} objects Objects to collect properties from.
       *
       * Returns an array of property names (array will be empty,
       * if objects have no own properties).
       */
      collectPropertyNames: function (objects) {

        var o, propName, props = [];
        for (o in objects) {
          for (propName in objects[o]) {
            if ((objects[o].hasOwnProperty(propName)) &&
                (props.indexOf(propName) < 0) && 
                (typeof objects[o][propName] !== 'function')) {

              props.push(propName);
            }
          }
        }
        return props;
      }
    },

    /**
     * $.csv.toArray(csv)
     * Converts a CSV entry string to a javascript array.
     *
     * @param {Array} csv The string containing the CSV data.
     * @param {Object} [options] An object containing user-defined options.
     * @param {Character} [separator] An override for the separator character. Defaults to a comma(,).
     * @param {Character} [delimiter] An override for the delimiter character. Defaults to a double-quote(").
     *
     * This method deals with simple CSV strings only. It's useful if you only
     * need to parse a single entry. If you need to parse more than one line,
     * use $.csv2Array instead.
     */
    toArray: function(csv, options, callback) {
      options = (options !== undefined ? options : {});
      var config = {};
      config.callback = ((callback !== undefined && typeof(callback) === 'function') ? callback : false);
      config.separator = 'separator' in options ? options.separator : $.csv.defaults.separator;
      config.delimiter = 'delimiter' in options ? options.delimiter : $.csv.defaults.delimiter;
      var state = (options.state !== undefined ? options.state : {});

      // setup
      options = {
        delimiter: config.delimiter,
        separator: config.separator,
        onParseEntry: options.onParseEntry,
        onParseValue: options.onParseValue,
        state: state
      };

      var entry = $.csv.parsers.parseEntry(csv, options);

      // push the value to a callback if one is defined
      if(!config.callback) {
        return entry;
      } else {
        config.callback('', entry);
      }
    },

    /**
     * $.csv.toArrays(csv)
     * Converts a CSV string to a javascript array.
     *
     * @param {String} csv The string containing the raw CSV data.
     * @param {Object} [options] An object containing user-defined options.
     * @param {Character} [separator] An override for the separator character. Defaults to a comma(,).
     * @param {Character} [delimiter] An override for the delimiter character. Defaults to a double-quote(").
     *
     * This method deals with multi-line CSV. The breakdown is simple. The first
     * dimension of the array represents the line (or entry/row) while the second
     * dimension contains the values (or values/columns).
     */
    toArrays: function(csv, options, callback) {
      options = (options !== undefined ? options : {});
      var config = {};
      config.callback = ((callback !== undefined && typeof(callback) === 'function') ? callback : false);
      config.separator = 'separator' in options ? options.separator : $.csv.defaults.separator;
      config.delimiter = 'delimiter' in options ? options.delimiter : $.csv.defaults.delimiter;

      // setup
      var data = [];
      options = {
        delimiter: config.delimiter,
        separator: config.separator,
        onPreParse: options.onPreParse,
        onParseEntry: options.onParseEntry,
        onParseValue: options.onParseValue,
        onPostParse: options.onPostParse,
        start: options.start,
        end: options.end,
        state: {
          rowNum: 1,
          colNum: 1
        }
      };

      // onPreParse hook
      if(options.onPreParse !== undefined) {
        options.onPreParse(csv, options.state);
      }

      // parse the data
      data = $.csv.parsers.parse(csv, options);

      // onPostParse hook
      if(options.onPostParse !== undefined) {
        options.onPostParse(data, options.state);
      }

      // push the value to a callback if one is defined
      if(!config.callback) {
        return data;
      } else {
        config.callback('', data);
      }
    },

    /**
     * $.csv.toObjects(csv)
     * Converts a CSV string to a javascript object.
     * @param {String} csv The string containing the raw CSV data.
     * @param {Object} [options] An object containing user-defined options.
     * @param {Character} [separator] An override for the separator character. Defaults to a comma(,).
     * @param {Character} [delimiter] An override for the delimiter character. Defaults to a double-quote(").
     * @param {Boolean} [headers] Indicates whether the data contains a header line. Defaults to true.
     *
     * This method deals with multi-line CSV strings. Where the headers line is
     * used as the key for each value per entry.
     */
    toObjects: function(csv, options, callback) {
      options = (options !== undefined ? options : {});
      var config = {};
      config.callback = ((callback !== undefined && typeof(callback) === 'function') ? callback : false);
      config.separator = 'separator' in options ? options.separator : $.csv.defaults.separator;
      config.delimiter = 'delimiter' in options ? options.delimiter : $.csv.defaults.delimiter;
      config.headers = 'headers' in options ? options.headers : $.csv.defaults.headers;
      options.start = 'start' in options ? options.start : 1;
      
      // account for headers
      if(config.headers) {
        options.start++;
      }
      if(options.end && config.headers) {
        options.end++;
      }

      // setup
      var lines = [];
      var data = [];

      options = {
        delimiter: config.delimiter,
        separator: config.separator,
        onPreParse: options.onPreParse,
        onParseEntry: options.onParseEntry,
        onParseValue: options.onParseValue,
        onPostParse: options.onPostParse,
        start: options.start,
        end: options.end,
        state: {
          rowNum: 1,
          colNum: 1
        },
        match: false,
        transform: options.transform
      };

      // fetch the headers
      var headerOptions = {
        delimiter: config.delimiter,
        separator: config.separator,
        start: 1,
        end: 1,
        state: {
          rowNum:1,
          colNum:1
        }
      };

      // onPreParse hook
      if(options.onPreParse !== undefined) {
        options.onPreParse(csv, options.state);
      }

      // parse the csv
      var headerLine = $.csv.parsers.splitLines(csv, headerOptions);
      var headers = $.csv.toArray(headerLine[0], options);

      // fetch the data
      lines = $.csv.parsers.splitLines(csv, options);

      // reset the state for re-use
      options.state.colNum = 1;
      if(headers){
        options.state.rowNum = 2;
      } else {
        options.state.rowNum = 1;
      }
      
      // convert data to objects
      for(var i=0, len=lines.length; i<len; i++) {
        var entry = $.csv.toArray(lines[i], options);
        var object = {};
        for(var j=0; j <headers.length; j++) {
          object[headers[j]] = entry[j];
        }
        if (options.transform !== undefined) {
          data.push(options.transform.call(undefined, object));
        } else {
          data.push(object);
        }
        
        // update row state
        options.state.rowNum++;
      }

      // onPostParse hook
      if(options.onPostParse !== undefined) {
        options.onPostParse(data, options.state);
      }

      // push the value to a callback if one is defined
      if(!config.callback) {
        return data;
      } else {
        config.callback('', data);
      }
    },

     /**
     * $.csv.fromArrays(arrays)
     * Converts a javascript array to a CSV String.
     *
     * @param {Array} arrays An array containing an array of CSV entries.
     * @param {Object} [options] An object containing user-defined options.
     * @param {Character} [separator] An override for the separator character. Defaults to a comma(,).
     * @param {Character} [delimiter] An override for the delimiter character. Defaults to a double-quote(").
     *
     * This method generates a CSV file from an array of arrays (representing entries).
     */
    fromArrays: function(arrays, options, callback) {
      options = (options !== undefined ? options : {});
      var config = {};
      config.callback = ((callback !== undefined && typeof(callback) === 'function') ? callback : false);
      config.separator = 'separator' in options ? options.separator : $.csv.defaults.separator;
      config.delimiter = 'delimiter' in options ? options.delimiter : $.csv.defaults.delimiter;

      var output = '',
          line,
          lineValues,
          i, j;

      for (i = 0; i < arrays.length; i++) {
        line = arrays[i];
        lineValues = [];
        for (j = 0; j < line.length; j++) {
          var strValue = (line[j] === undefined || line[j] === null) ? '' : line[j].toString();
          if (strValue.indexOf(config.delimiter) > -1) {
            strValue = strValue.replace(new RegExp(config.delimiter, 'g'), config.delimiter + config.delimiter);
          }

          var escMatcher = '\n|\r|S|D';
          escMatcher = escMatcher.replace('S', config.separator);
          escMatcher = escMatcher.replace('D', config.delimiter);

          if (strValue.search(escMatcher) > -1) {
            strValue = config.delimiter + strValue + config.delimiter;
          }
          lineValues.push(strValue);
        }
        output += lineValues.join(config.separator) + '\r\n';
      }

      // push the value to a callback if one is defined
      if(!config.callback) {
        return output;
      } else {
        config.callback('', output);
      }
    },

    /**
     * $.csv.fromObjects(objects)
     * Converts a javascript dictionary to a CSV string.
     *
     * @param {Object} objects An array of objects containing the data.
     * @param {Object} [options] An object containing user-defined options.
     * @param {Character} [separator] An override for the separator character. Defaults to a comma(,).
     * @param {Character} [delimiter] An override for the delimiter character. Defaults to a double-quote(").
     * @param {Character} [sortOrder] Sort order of columns (named after
     *   object properties). Use 'alpha' for alphabetic. Default is 'declare',
     *   which means, that properties will _probably_ appear in order they were
     *   declared for the object. But without any guarantee.
     * @param {Character or Array} [manualOrder] Manually order columns. May be
     * a strin in a same csv format as an output or an array of header names
     * (array items won't be parsed). All the properties, not present in
     * `manualOrder` will be appended to the end in accordance with `sortOrder`
     * option. So the `manualOrder` always takes preference, if present.
     *
     * This method generates a CSV file from an array of objects (name:value pairs).
     * It starts by detecting the headers and adding them as the first line of
     * the CSV file, followed by a structured dump of the data.
     */
    fromObjects: function(objects, options, callback) {
      options = (options !== undefined ? options : {});
      var config = {};
      config.callback = ((callback !== undefined && typeof(callback) === 'function') ? callback : false);
      config.separator = 'separator' in options ? options.separator : $.csv.defaults.separator;
      config.delimiter = 'delimiter' in options ? options.delimiter : $.csv.defaults.delimiter;
      config.headers = 'headers' in options ? options.headers : $.csv.defaults.headers;
      config.sortOrder = 'sortOrder' in options ? options.sortOrder : 'declare';
      config.manualOrder = 'manualOrder' in options ? options.manualOrder : [];
      config.transform = options.transform;

      if (typeof config.manualOrder === 'string') {
        config.manualOrder = $.csv.toArray(config.manualOrder, config);
      }

      if (config.transform !== undefined) {
        var origObjects = objects;
        objects = [];

        var i;
        for (i = 0; i < origObjects.length; i++) {
          objects.push(config.transform.call(undefined, origObjects[i]));
        }
      }

      var props = $.csv.helpers.collectPropertyNames(objects);

      if (config.sortOrder === 'alpha') {
        props.sort();
      } // else {} - nothing to do for 'declare' order

      if (config.manualOrder.length > 0) {

        var propsManual = [].concat(config.manualOrder);
        var p;
        for (p = 0; p < props.length; p++) {
          if (propsManual.indexOf( props[p] ) < 0) {
            propsManual.push( props[p] );
          }
        }
        props = propsManual;
      }

      var o, p, line, output = [], propName;
      if (config.headers) {
        output.push(props);
      }

      for (o = 0; o < objects.length; o++) {
        line = [];
        for (p = 0; p < props.length; p++) {
          propName = props[p];
          if (propName in objects[o] && typeof objects[o][propName] !== 'function') {
            line.push(objects[o][propName]);
          } else {
            line.push('');
          }
        }
        output.push(line);
      }

      // push the value to a callback if one is defined
      return $.csv.fromArrays(output, options, config.callback);
    }
  };

  // Maintenance code to maintain backward-compatibility
  // Will be removed in release 1.0
  $.csvEntry2Array = $.csv.toArray;
  $.csv2Array = $.csv.toArrays;
  $.csv2Dictionary = $.csv.toObjects;

  // CommonJS module is defined
  if (typeof module !== 'undefined' && module.exports) {
    module.exports = $.csv;
  }

}).call( this );

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ }),

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

/***/ }),

/***/ "./src/Intracto/SecretSantaBundle/Resources/public/js/party.import.js":
/*!****************************************************************************!*\
  !*** ./src/Intracto/SecretSantaBundle/Resources/public/js/party.import.js ***!
  \****************************************************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($, jQuery) {__webpack_require__(/*! jquery-csv */ "./node_modules/jquery-csv/src/jquery.csv.js");
var createModule = __webpack_require__(/*! ./party.create */ "./src/Intracto/SecretSantaBundle/Resources/public/js/party.create.js");

/* Variables */
var collectionHolder = $('table.participants tbody');
var dropImportCSV = document.getElementById('importCSV');
var errorImportCSV = document.getElementById('errorImportCSV');
var warningImportCSV = document.getElementById('warningImportCSV');

/* Document Ready */
jQuery(document).ready(function () {

    //Add eventlistener on add-new-participant button
    $('.add-import-participant').click(function (e) {
        e.preventDefault();
        $('.row-import-participants').show(300);
    });

    $('.btn-import-cancel').click(function (e) {
        e.preventDefault();
        $('#importCSV').val('');
        $('#errorImportCSV').hide();
        $('#warningImportCSV').hide();
        $('.row-import-participants').hide(300);
    });

    $('.add-import-participant-do').click(function (e) {
        e.preventDefault();

        var participants = $.csv.toArrays($('.add-import-participant-data').val(), {
            headers: false,
            seperator: ',',
            delimiter: '"'
        });

        if (typeof participants[0] === 'undefined') {
            return;
        }

        if (participants[0][1].indexOf('@') == -1) {
            participants.splice(0, 1);
        }

        var added = 0;
        var lookForEmpty = true;
        for (var participant in participants) {

            var email = '';
            var name = '';

            for (var field in participants[participant]) {
                // very basic check, can/should probably be done some other way
                // check if this is an e-mailaddress
                if (email == '' && participants[participant][field].indexOf('@') != -1) {
                    email = participants[participant][field];
                } else {
                    // either e-mail already found, or no @ sign found
                    name = participants[participant][field];
                }
            }

            if (email != '') {
                if (name == '') name = email;

                // check to see if list contains empty participants
                if (lookForEmpty) {
                    // if so, use them, otherwise add new
                    elem = $(collectionHolder).find('.participant-name[value=""],.participant-name:not([value])');
                    if (elem.length > 0) {
                        row = $(elem[0]).parent().parent();
                        $(row).find('.participant-name').attr('value', name);
                        $(row).find('.participant-mail').attr('value', email);
                    } else {
                        // prevent lookup on next iteration
                        lookForEmpty = false;
                        createModule.addNewParticipant(collectionHolder, email, name);
                    }
                } else {
                    createModule.addNewParticipant(collectionHolder, email, name);
                }
                added++;
            }
        }

        if (added > 0) {
            $('.add-import-participant-data').val('');
            $('.row-import-participants').hide(300);
        }
    });

    $('.add-import-participant-data').change(function () {
        // replace tab and ; delimiter with ,
        data = $(this).val().replace(/\t/g, ",").replace(/;/g, ",");
        if (data != $(this).text()) {
            $(this).val(data);
        }
    });
});

dropImportCSV.addEventListener('dragenter', function (e) {
    e.stopPropagation(e);
    e.preventDefault(e);
});

dropImportCSV.addEventListener('dragover', function (e) {
    e.stopPropagation(e);
    e.preventDefault(e);

    return false;
});

dropImportCSV.addEventListener('drop', importCSV, false);

function importCSV(e) {
    e.stopPropagation(e);
    e.preventDefault(e);

    var files = e.dataTransfer.files;
    var number = files.length;

    switch (number) {
        case 1:
            parseFiles(files);
            warningImportCSV.style.display = 'none';
            break;

        default:
            warningImportCSV.style.display = 'block';
            break;
    }
}

function parseFiles(files) {
    var file = files[0];
    var fileName = file['name'];
    var fileExtension = fileName.replace(/^.*\./, '');

    switch (fileExtension) {
        case 'csv':
        case 'txt':
            errorImportCSV.style.display = 'none';

            var reader = new FileReader();

            reader.readAsText(file, 'UTF-8');
            reader.onload = handleReaderLoad;
            break;

        default:
            errorImportCSV.style.display = 'block';
            break;
    }
}

function handleReaderLoad(e) {
    var csv = e.target.result;

    dropImportCSV.value = csv.split(';');
}
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ })

},["./src/Intracto/SecretSantaBundle/Resources/public/js/party.import.js"]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvanF1ZXJ5LWNzdi9zcmMvanF1ZXJ5LmNzdi5qcyIsIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvanF1ZXJ5LXNtb290aC1zY3JvbGwvanF1ZXJ5LnNtb290aC1zY3JvbGwuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL0ludHJhY3RvL1NlY3JldFNhbnRhQnVuZGxlL1Jlc291cmNlcy9wdWJsaWMvanMvcGFydHkuY3JlYXRlLmpzIiwid2VicGFjazovLy8uL3NyYy9JbnRyYWN0by9TZWNyZXRTYW50YUJ1bmRsZS9SZXNvdXJjZXMvcHVibGljL2pzL3BhcnR5LmltcG9ydC5qcyJdLCJuYW1lcyI6WyJyZXF1aXJlIiwiZXhwb3J0cyIsImFkZE5ld1BhcnRpY2lwYW50IiwiY29sbGVjdGlvbkhvbGRlciIsImVtYWlsIiwibmFtZSIsInByb3RvdHlwZSIsImF0dHIiLCJudW1iZXJfb2ZfcGFydGljaXBhbnRzIiwiY2hpbGRyZW4iLCJsZW5ndGgiLCJuZXdGb3JtSHRtbCIsInJlcGxhY2UiLCJuZXdGb3JtIiwiJCIsImFwcGVuZCIsImZpbmQiLCJzaG93IiwiYmluZERlbGV0ZUJ1dHRvbkV2ZW50cyIsInJlbW92ZUNsYXNzIiwiZWFjaCIsImkiLCJvZmYiLCJjbGljayIsImUiLCJwcmV2ZW50RGVmYXVsdCIsImoiLCJuZXh0X3Jvd19uYW1lIiwidmFsIiwibmV4dF9yb3dfbWFpbCIsInJlbW92ZSIsImFkZENsYXNzIiwialF1ZXJ5IiwiZG9jdW1lbnQiLCJyZWFkeSIsInNtb290aFNjcm9sbCIsInNjcm9sbFRhcmdldCIsImNyZWF0ZU1vZHVsZSIsImRyb3BJbXBvcnRDU1YiLCJnZXRFbGVtZW50QnlJZCIsImVycm9ySW1wb3J0Q1NWIiwid2FybmluZ0ltcG9ydENTViIsImhpZGUiLCJwYXJ0aWNpcGFudHMiLCJjc3YiLCJ0b0FycmF5cyIsImhlYWRlcnMiLCJzZXBlcmF0b3IiLCJkZWxpbWl0ZXIiLCJpbmRleE9mIiwic3BsaWNlIiwiYWRkZWQiLCJsb29rRm9yRW1wdHkiLCJwYXJ0aWNpcGFudCIsImZpZWxkIiwiZWxlbSIsInJvdyIsInBhcmVudCIsImNoYW5nZSIsImRhdGEiLCJ0ZXh0IiwiYWRkRXZlbnRMaXN0ZW5lciIsInN0b3BQcm9wYWdhdGlvbiIsImltcG9ydENTViIsImZpbGVzIiwiZGF0YVRyYW5zZmVyIiwibnVtYmVyIiwicGFyc2VGaWxlcyIsInN0eWxlIiwiZGlzcGxheSIsImZpbGUiLCJmaWxlTmFtZSIsImZpbGVFeHRlbnNpb24iLCJyZWFkZXIiLCJGaWxlUmVhZGVyIiwicmVhZEFzVGV4dCIsIm9ubG9hZCIsImhhbmRsZVJlYWRlckxvYWQiLCJ0YXJnZXQiLCJyZXN1bHQiLCJ2YWx1ZSIsInNwbGl0Il0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7O0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSwwQ0FBMEM7QUFDMUM7O0FBRUE7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsV0FBVztBQUNYO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLFdBQVc7QUFDWCxxRUFBcUU7QUFDckU7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxXQUFXO0FBQ1gsa0VBQWtFO0FBQ2xFO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLE9BQU87O0FBRVA7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLDhCO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxXQUFXO0FBQ1gscUVBQXFFO0FBQ3JFO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7O0FBRVQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLE9BQU87O0FBRVA7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFdBQVc7QUFDWCxrRUFBa0U7QUFDbEU7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNB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QTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsQ0FBQzs7Ozs7Ozs7Ozs7Ozs7QUM5OEJEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUFBO0FBQUE7QUFBQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTtBQUNBLENBQUM7O0FBRUQ7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLCtCQUErQjs7QUFFL0I7QUFDQTtBQUNBLDhCQUE4Qjs7QUFFOUI7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsT0FBTztBQUNQO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQSwyQ0FBMkMsU0FBUzs7QUFFcEQ7QUFDQSxLQUFLO0FBQ0w7QUFDQSwyQ0FBMkMsc0JBQXNCOztBQUVqRTtBQUNBLEtBQUs7O0FBRUw7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0Esd0RBQXdEOztBQUV4RDtBQUNBLFNBQVM7QUFDVDs7QUFFQSw0QkFBNEI7O0FBRTVCO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxrQ0FBa0Msa0NBQWtDO0FBQ3BFO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsV0FBVzs7QUFFWDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxPQUFPO0FBQ1A7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLEdBQUc7O0FBRUg7QUFDQSxvQkFBb0I7QUFDcEI7O0FBRUE7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQSxtQkFBbUIsYUFBYTtBQUNoQztBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSx1QkFBdUIsV0FBVztBQUNsQyxLQUFLO0FBQ0wsdUJBQXVCLFdBQVcsMkNBQTJDOztBQUU3RTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsMkNBQTJDLElBQUk7QUFDL0M7QUFDQTs7QUFFQTtBQUNBOztBQUVBLENBQUM7Ozs7Ozs7Ozs7Ozs7O2lEQ3BXRCxtQkFBQUEsQ0FBUSx5RkFBUjs7QUFFQUMsUUFBUUMsaUJBQVIsR0FBNEIsVUFBU0MsZ0JBQVQsRUFBMkJDLEtBQTNCLEVBQWtDQyxJQUFsQyxFQUF3QztBQUNoRUgsc0JBQWtCQyxnQkFBbEIsRUFBb0NDLEtBQXBDLEVBQTJDQyxJQUEzQztBQUNILENBRkQ7O0FBSUEsU0FBU0gsaUJBQVQsQ0FBMkJDLGdCQUEzQixFQUE2Q0MsS0FBN0MsRUFBb0RDLElBQXBELEVBQTBEO0FBQ3REO0FBQ0EsUUFBSUMsWUFBWUgsaUJBQWlCSSxJQUFqQixDQUFzQixnQkFBdEIsQ0FBaEI7QUFDQTtBQUNBLFFBQUlDLHlCQUF5QkwsaUJBQWlCTSxRQUFqQixHQUE0QkMsTUFBNUIsR0FBcUMsQ0FBbEUsQ0FKc0QsQ0FJZTtBQUNyRSxRQUFJQyxjQUFjTCxVQUFVTSxPQUFWLENBQWtCLFdBQWxCLEVBQ2RKLHNCQURjLEVBQ1VJLE9BRFYsQ0FDa0IsdUJBRGxCLEVBRWRKLHlCQUF5QixDQUZYLENBQWxCO0FBR0E7QUFDQSxRQUFJSyxVQUFVQyxFQUFFSCxXQUFGLENBQWQ7QUFDQVIscUJBQWlCWSxNQUFqQixDQUF3QkYsT0FBeEI7O0FBRUEsUUFBTSxPQUFPVCxLQUFQLEtBQWdCLFdBQWpCLElBQWtDLE9BQU9DLElBQVAsS0FBZSxXQUF0RCxFQUFxRTtBQUNqRTtBQUNBUyxVQUFFRCxPQUFGLEVBQVdHLElBQVgsQ0FBZ0IsbUJBQWhCLEVBQXFDVCxJQUFyQyxDQUEwQyxPQUExQyxFQUFtREgsS0FBbkQ7QUFDQVUsVUFBRUQsT0FBRixFQUFXRyxJQUFYLENBQWdCLG1CQUFoQixFQUFxQ1QsSUFBckMsQ0FBMEMsT0FBMUMsRUFBbURGLElBQW5EO0FBQ0FRLGdCQUFRSSxJQUFSO0FBQ0gsS0FMRCxNQUtPO0FBQ0hKLGdCQUFRSSxJQUFSLENBQWEsR0FBYjtBQUNIOztBQUVEO0FBQ0FDO0FBQ0E7QUFDQUosTUFBRSxxQkFBRixFQUF5QkssV0FBekIsQ0FBcUMsVUFBckM7QUFDSDtBQUNELFNBQVNELHNCQUFULEdBQWtDO0FBQzlCO0FBQ0FKLE1BQUUsMkJBQUYsRUFBK0JNLElBQS9CLENBQW9DLFVBQVVDLENBQVYsRUFBYTtBQUM3QztBQUNBUCxVQUFFLElBQUYsRUFBUVEsR0FBUixDQUFZLE9BQVo7QUFDQTtBQUNBUixVQUFFLElBQUYsRUFBUVMsS0FBUixDQUFjLFVBQVVDLENBQVYsRUFBYTtBQUN2QkEsY0FBRUMsY0FBRjtBQUNBWCxjQUFFLHVDQUF1Q08sQ0FBdkMsR0FBMkMsR0FBN0MsRUFBa0RELElBQWxELENBQXVELFVBQVVNLENBQVYsRUFBYTtBQUNoRTtBQUNBLG9CQUFJQyxnQkFBZ0JiLEVBQUUsd0NBQXdDTyxJQUFJSyxDQUFKLEdBQVEsQ0FBaEQsSUFBcUQsMEJBQXZELEVBQW1GRSxHQUFuRixFQUFwQjtBQUNBLG9CQUFJQyxnQkFBZ0JmLEVBQUUsd0NBQXdDTyxJQUFJSyxDQUFKLEdBQVEsQ0FBaEQsSUFBcUQsMEJBQXZELEVBQW1GRSxHQUFuRixFQUFwQjtBQUNBZCxrQkFBRSx3Q0FBd0NPLElBQUlLLENBQTVDLElBQWlELDBCQUFuRCxFQUErRUUsR0FBL0UsQ0FBbUZELGFBQW5GO0FBQ0FiLGtCQUFFLHdDQUF3Q08sSUFBSUssQ0FBNUMsSUFBaUQsMEJBQW5ELEVBQStFRSxHQUEvRSxDQUFtRkMsYUFBbkY7QUFDSCxhQU5EO0FBT0E7QUFDQWYsY0FBRSxxQ0FBRixFQUF5Q2dCLE1BQXpDO0FBQ0E7QUFDQSxnQkFBSWhCLEVBQUUsZ0NBQUYsRUFBb0NKLE1BQXBDLEdBQTZDLENBQWpELEVBQW9EO0FBQ2hESSxrQkFBRSwwREFBRixFQUE4RGlCLFFBQTlELENBQXVFLFVBQXZFO0FBQ0FqQixrQkFBRSwwREFBRixFQUE4RFEsR0FBOUQsQ0FBa0UsT0FBbEU7QUFDSDtBQUNKLFNBaEJEO0FBaUJILEtBckJEO0FBc0JIO0FBQ0Q7QUFDQSxJQUFJbkIsbUJBQW1CVyxFQUFFLDBCQUFGLENBQXZCO0FBQ0E7QUFDQWtCLE9BQU9DLFFBQVAsRUFBaUJDLEtBQWpCLENBQXVCLFlBQVk7QUFDL0I7QUFDQXBCLE1BQUUsc0JBQUYsRUFBMEJTLEtBQTFCLENBQWdDLFVBQVVDLENBQVYsRUFBYTtBQUN6Q0EsVUFBRUMsY0FBRjtBQUNBdkIsMEJBQWtCQyxnQkFBbEI7QUFDSCxLQUhEO0FBSUE7QUFDQSxRQUFJVyxFQUFFLHNCQUFGLEVBQTBCSixNQUExQixHQUFtQyxDQUF2QyxFQUEwQztBQUN0Q1E7QUFDQUosVUFBRSxxQkFBRixFQUF5QkssV0FBekIsQ0FBcUMsVUFBckM7QUFDSDtBQUNEO0FBQ0FMLE1BQUUsZUFBRixFQUFtQlMsS0FBbkIsQ0FBeUIsWUFBWTtBQUNqQ1QsVUFBRXFCLFlBQUYsQ0FBZTtBQUNYQywwQkFBYztBQURILFNBQWY7QUFHQSxlQUFPLEtBQVA7QUFDSCxLQUxEO0FBTUgsQ0FsQkQsRTs7Ozs7Ozs7Ozs7OztpREM1REEsbUJBQUFwQyxDQUFRLCtEQUFSO0FBQ0EsSUFBSXFDLGVBQWUsbUJBQUFyQyxDQUFRLDRGQUFSLENBQW5COztBQUVBO0FBQ0EsSUFBSUcsbUJBQW1CVyxFQUFFLDBCQUFGLENBQXZCO0FBQ0EsSUFBSXdCLGdCQUFnQkwsU0FBU00sY0FBVCxDQUF3QixXQUF4QixDQUFwQjtBQUNBLElBQUlDLGlCQUFpQlAsU0FBU00sY0FBVCxDQUF3QixnQkFBeEIsQ0FBckI7QUFDQSxJQUFJRSxtQkFBbUJSLFNBQVNNLGNBQVQsQ0FBd0Isa0JBQXhCLENBQXZCOztBQUVBO0FBQ0FQLE9BQU9DLFFBQVAsRUFBaUJDLEtBQWpCLENBQXVCLFlBQVk7O0FBRS9CO0FBQ0FwQixNQUFFLHlCQUFGLEVBQTZCUyxLQUE3QixDQUFtQyxVQUFVQyxDQUFWLEVBQWE7QUFDNUNBLFVBQUVDLGNBQUY7QUFDQVgsVUFBRSwwQkFBRixFQUE4QkcsSUFBOUIsQ0FBbUMsR0FBbkM7QUFDSCxLQUhEOztBQUtBSCxNQUFFLG9CQUFGLEVBQXdCUyxLQUF4QixDQUE4QixVQUFVQyxDQUFWLEVBQWE7QUFDdkNBLFVBQUVDLGNBQUY7QUFDQVgsVUFBRSxZQUFGLEVBQWdCYyxHQUFoQixDQUFvQixFQUFwQjtBQUNBZCxVQUFFLGlCQUFGLEVBQXFCNEIsSUFBckI7QUFDQTVCLFVBQUUsbUJBQUYsRUFBdUI0QixJQUF2QjtBQUNBNUIsVUFBRSwwQkFBRixFQUE4QjRCLElBQTlCLENBQW1DLEdBQW5DO0FBQ0gsS0FORDs7QUFRQTVCLE1BQUUsNEJBQUYsRUFBZ0NTLEtBQWhDLENBQXNDLFVBQVVDLENBQVYsRUFBYTtBQUMvQ0EsVUFBRUMsY0FBRjs7QUFFQSxZQUFJa0IsZUFBZTdCLEVBQUU4QixHQUFGLENBQU1DLFFBQU4sQ0FBZS9CLEVBQUUsOEJBQUYsRUFBa0NjLEdBQWxDLEVBQWYsRUFBd0Q7QUFDdkVrQixxQkFBUyxLQUQ4RDtBQUV2RUMsdUJBQVcsR0FGNEQ7QUFHdkVDLHVCQUFXO0FBSDRELFNBQXhELENBQW5COztBQU1BLFlBQUksT0FBT0wsYUFBYSxDQUFiLENBQVAsS0FBNEIsV0FBaEMsRUFBNkM7QUFDekM7QUFDSDs7QUFFRCxZQUFJQSxhQUFhLENBQWIsRUFBZ0IsQ0FBaEIsRUFBbUJNLE9BQW5CLENBQTJCLEdBQTNCLEtBQW1DLENBQUMsQ0FBeEMsRUFBMkM7QUFDdkNOLHlCQUFhTyxNQUFiLENBQW9CLENBQXBCLEVBQXVCLENBQXZCO0FBQ0g7O0FBRUQsWUFBSUMsUUFBUSxDQUFaO0FBQ0EsWUFBSUMsZUFBZSxJQUFuQjtBQUNBLGFBQUssSUFBSUMsV0FBVCxJQUF3QlYsWUFBeEIsRUFBc0M7O0FBRWxDLGdCQUFJdkMsUUFBUSxFQUFaO0FBQ0EsZ0JBQUlDLE9BQU8sRUFBWDs7QUFFQSxpQkFBSyxJQUFJaUQsS0FBVCxJQUFrQlgsYUFBYVUsV0FBYixDQUFsQixFQUE2QztBQUN6QztBQUNBO0FBQ0Esb0JBQUlqRCxTQUFTLEVBQVQsSUFBZXVDLGFBQWFVLFdBQWIsRUFBMEJDLEtBQTFCLEVBQWlDTCxPQUFqQyxDQUF5QyxHQUF6QyxLQUFpRCxDQUFDLENBQXJFLEVBQXdFO0FBQ3BFN0MsNEJBQVF1QyxhQUFhVSxXQUFiLEVBQTBCQyxLQUExQixDQUFSO0FBQ0gsaUJBRkQsTUFFTztBQUNIO0FBQ0FqRCwyQkFBT3NDLGFBQWFVLFdBQWIsRUFBMEJDLEtBQTFCLENBQVA7QUFDSDtBQUNKOztBQUVELGdCQUFJbEQsU0FBUyxFQUFiLEVBQWlCO0FBQ2Isb0JBQUlDLFFBQVEsRUFBWixFQUFnQkEsT0FBT0QsS0FBUDs7QUFFaEI7QUFDQSxvQkFBSWdELFlBQUosRUFBa0I7QUFDZDtBQUNBRywyQkFBT3pDLEVBQUVYLGdCQUFGLEVBQW9CYSxJQUFwQixDQUF5Qiw0REFBekIsQ0FBUDtBQUNBLHdCQUFJdUMsS0FBSzdDLE1BQUwsR0FBYyxDQUFsQixFQUFxQjtBQUNqQjhDLDhCQUFNMUMsRUFBRXlDLEtBQUssQ0FBTCxDQUFGLEVBQVdFLE1BQVgsR0FBb0JBLE1BQXBCLEVBQU47QUFDQTNDLDBCQUFFMEMsR0FBRixFQUFPeEMsSUFBUCxDQUFZLG1CQUFaLEVBQWlDVCxJQUFqQyxDQUFzQyxPQUF0QyxFQUErQ0YsSUFBL0M7QUFDQVMsMEJBQUUwQyxHQUFGLEVBQU94QyxJQUFQLENBQVksbUJBQVosRUFBaUNULElBQWpDLENBQXNDLE9BQXRDLEVBQStDSCxLQUEvQztBQUNILHFCQUpELE1BSU87QUFDSDtBQUNBZ0QsdUNBQWUsS0FBZjtBQUNBZixxQ0FBYW5DLGlCQUFiLENBQStCQyxnQkFBL0IsRUFBaURDLEtBQWpELEVBQXdEQyxJQUF4RDtBQUNIO0FBQ0osaUJBWkQsTUFZTztBQUNIZ0MsaUNBQWFuQyxpQkFBYixDQUErQkMsZ0JBQS9CLEVBQWlEQyxLQUFqRCxFQUF3REMsSUFBeEQ7QUFDSDtBQUNEOEM7QUFDSDtBQUVKOztBQUVELFlBQUlBLFFBQVEsQ0FBWixFQUFlO0FBQ1hyQyxjQUFFLDhCQUFGLEVBQWtDYyxHQUFsQyxDQUFzQyxFQUF0QztBQUNBZCxjQUFFLDBCQUFGLEVBQThCNEIsSUFBOUIsQ0FBbUMsR0FBbkM7QUFDSDtBQUVKLEtBaEVEOztBQWtFQTVCLE1BQUUsOEJBQUYsRUFBa0M0QyxNQUFsQyxDQUF5QyxZQUFZO0FBQ2pEO0FBQ0FDLGVBQU83QyxFQUFFLElBQUYsRUFBUWMsR0FBUixHQUFjaEIsT0FBZCxDQUFzQixLQUF0QixFQUE2QixHQUE3QixFQUFrQ0EsT0FBbEMsQ0FBMEMsSUFBMUMsRUFBZ0QsR0FBaEQsQ0FBUDtBQUNBLFlBQUkrQyxRQUFRN0MsRUFBRSxJQUFGLEVBQVE4QyxJQUFSLEVBQVosRUFBNEI7QUFDeEI5QyxjQUFFLElBQUYsRUFBUWMsR0FBUixDQUFZK0IsSUFBWjtBQUNIO0FBQ0osS0FORDtBQU9ILENBekZEOztBQTJGQXJCLGNBQWN1QixnQkFBZCxDQUErQixXQUEvQixFQUE0QyxVQUFVckMsQ0FBVixFQUFhO0FBQ3JEQSxNQUFFc0MsZUFBRixDQUFrQnRDLENBQWxCO0FBQ0FBLE1BQUVDLGNBQUYsQ0FBaUJELENBQWpCO0FBQ0gsQ0FIRDs7QUFLQWMsY0FBY3VCLGdCQUFkLENBQStCLFVBQS9CLEVBQTJDLFVBQVVyQyxDQUFWLEVBQWE7QUFDcERBLE1BQUVzQyxlQUFGLENBQWtCdEMsQ0FBbEI7QUFDQUEsTUFBRUMsY0FBRixDQUFpQkQsQ0FBakI7O0FBRUEsV0FBTyxLQUFQO0FBQ0gsQ0FMRDs7QUFPQWMsY0FBY3VCLGdCQUFkLENBQStCLE1BQS9CLEVBQXVDRSxTQUF2QyxFQUFrRCxLQUFsRDs7QUFFQSxTQUFTQSxTQUFULENBQW1CdkMsQ0FBbkIsRUFBc0I7QUFDbEJBLE1BQUVzQyxlQUFGLENBQWtCdEMsQ0FBbEI7QUFDQUEsTUFBRUMsY0FBRixDQUFpQkQsQ0FBakI7O0FBRUEsUUFBSXdDLFFBQVF4QyxFQUFFeUMsWUFBRixDQUFlRCxLQUEzQjtBQUNBLFFBQUlFLFNBQVNGLE1BQU10RCxNQUFuQjs7QUFFQSxZQUFRd0QsTUFBUjtBQUNJLGFBQUssQ0FBTDtBQUNJQyx1QkFBV0gsS0FBWDtBQUNBdkIsNkJBQWlCMkIsS0FBakIsQ0FBdUJDLE9BQXZCLEdBQWlDLE1BQWpDO0FBQ0E7O0FBRUo7QUFDSTVCLDZCQUFpQjJCLEtBQWpCLENBQXVCQyxPQUF2QixHQUFpQyxPQUFqQztBQUNBO0FBUlI7QUFVSDs7QUFFRCxTQUFTRixVQUFULENBQW9CSCxLQUFwQixFQUEyQjtBQUN2QixRQUFJTSxPQUFPTixNQUFNLENBQU4sQ0FBWDtBQUNBLFFBQUlPLFdBQVdELEtBQUssTUFBTCxDQUFmO0FBQ0EsUUFBSUUsZ0JBQWdCRCxTQUFTM0QsT0FBVCxDQUFpQixPQUFqQixFQUEwQixFQUExQixDQUFwQjs7QUFFQSxZQUFRNEQsYUFBUjtBQUNJLGFBQUssS0FBTDtBQUNBLGFBQUssS0FBTDtBQUNJaEMsMkJBQWU0QixLQUFmLENBQXFCQyxPQUFyQixHQUErQixNQUEvQjs7QUFFQSxnQkFBSUksU0FBUyxJQUFJQyxVQUFKLEVBQWI7O0FBRUFELG1CQUFPRSxVQUFQLENBQWtCTCxJQUFsQixFQUF3QixPQUF4QjtBQUNBRyxtQkFBT0csTUFBUCxHQUFnQkMsZ0JBQWhCO0FBQ0E7O0FBRUo7QUFDSXJDLDJCQUFlNEIsS0FBZixDQUFxQkMsT0FBckIsR0FBK0IsT0FBL0I7QUFDQTtBQWJSO0FBZUg7O0FBRUQsU0FBU1EsZ0JBQVQsQ0FBMEJyRCxDQUExQixFQUE2QjtBQUN6QixRQUFJb0IsTUFBTXBCLEVBQUVzRCxNQUFGLENBQVNDLE1BQW5COztBQUVBekMsa0JBQWMwQyxLQUFkLEdBQXNCcEMsSUFBSXFDLEtBQUosQ0FBVSxHQUFWLENBQXRCO0FBQ0gsQyIsImZpbGUiOiJqcy9wYXJ0eS5pbXBvcnQuOWY3N2UzYWEzNWRmZTA1NzNmYjYuanMiLCJzb3VyY2VzQ29udGVudCI6WyIvKipcbiAqIGpRdWVyeS1jc3YgKGpRdWVyeSBQbHVnaW4pXG4gKlxuICogVGhpcyBkb2N1bWVudCBpcyBsaWNlbnNlZCBhcyBmcmVlIHNvZnR3YXJlIHVuZGVyIHRoZSB0ZXJtcyBvZiB0aGVcbiAqIE1JVCBMaWNlbnNlOiBodHRwOi8vd3d3Lm9wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL21pdC1saWNlbnNlLnBocFxuICpcbiAqIEFja25vd2xlZGdlbWVudHM6XG4gKiBUaGUgb3JpZ2luYWwgZGVzaWduIGFuZCBpbmZsdWVuY2UgdG8gaW1wbGVtZW50IHRoaXMgbGlicmFyeSBhcyBhIGpxdWVyeVxuICogcGx1Z2luIGlzIGluZmx1ZW5jZWQgYnkganF1ZXJ5LWpzb24gKGh0dHA6Ly9jb2RlLmdvb2dsZS5jb20vcC9qcXVlcnktanNvbi8pLlxuICogSWYgeW91J3JlIGxvb2tpbmcgdG8gdXNlIG5hdGl2ZSBKU09OLlN0cmluZ2lmeSBidXQgd2FudCBhZGRpdGlvbmFsIGJhY2t3YXJkc1xuICogY29tcGF0aWJpbGl0eSBmb3IgYnJvd3NlcnMgdGhhdCBkb24ndCBzdXBwb3J0IGl0LCBJIGhpZ2hseSByZWNvbW1lbmQgeW91XG4gKiBjaGVjayBpdCBvdXQuXG4gKlxuICogQSBzcGVjaWFsIHRoYW5rcyBnb2VzIG91dCB0byByd2tAYWNtLm9yZyBmb3IgcHJvdmlkaW5nIGEgbG90IG9mIHZhbHVhYmxlXG4gKiBmZWVkYmFjayB0byB0aGUgcHJvamVjdCBpbmNsdWRpbmcgdGhlIGNvcmUgZm9yIHRoZSBuZXcgRlNNXG4gKiAoRmluaXRlIFN0YXRlIE1hY2hpbmUpIHBhcnNlcnMuIElmIHlvdSdyZSBsb29raW5nIGZvciBhIHN0YWJsZSBUU1YgcGFyc2VyXG4gKiBiZSBzdXJlIHRvIHRha2UgYSBsb29rIGF0IGpxdWVyeS10c3YgKGh0dHA6Ly9jb2RlLmdvb2dsZS5jb20vcC9qcXVlcnktdHN2LykuXG5cbiAqIEZvciBsZWdhbCBwdXJwb3NlcyBJJ2xsIGluY2x1ZGUgdGhlIFwiTk8gV0FSUkFOVFkgRVhQUkVTU0VEIE9SIElNUExJRUQuXG4gKiBVU0UgQVQgWU9VUiBPV04gUklTSy5cIi4gV2hpY2gsIGluICdsYXltYW4ncyB0ZXJtcycgbWVhbnMsIGJ5IHVzaW5nIHRoaXNcbiAqIGxpYnJhcnkgeW91IGFyZSBhY2NlcHRpbmcgcmVzcG9uc2liaWxpdHkgaWYgaXQgYnJlYWtzIHlvdXIgY29kZS5cbiAqXG4gKiBMZWdhbCBqYXJnb24gYXNpZGUsIEkgd2lsbCBkbyBteSBiZXN0IHRvIHByb3ZpZGUgYSB1c2VmdWwgYW5kIHN0YWJsZSBjb3JlXG4gKiB0aGF0IGNhbiBlZmZlY3RpdmVseSBiZSBidWlsdCBvbi5cbiAqXG4gKiBDb3B5cmlnaHRlZCAyMDEyIGJ5IEV2YW4gUGxhaWNlLlxuICovXG5cblJlZ0V4cC5lc2NhcGU9IGZ1bmN0aW9uKHMpIHtcbiAgICByZXR1cm4gcy5yZXBsYWNlKC9bLVxcL1xcXFxeJCorPy4oKXxbXFxde31dL2csICdcXFxcJCYnKTtcbn07XG5cbihmdW5jdGlvbiAodW5kZWZpbmVkKSB7XG4gICd1c2Ugc3RyaWN0JztcblxuICB2YXIgJDtcblxuICAvLyB0byBrZWVwIGJhY2t3YXJkcyBjb21wYXRpYmlsaXR5XG4gIGlmICh0eXBlb2YgalF1ZXJ5ICE9PSAndW5kZWZpbmVkJyAmJiBqUXVlcnkpIHtcbiAgICAkID0galF1ZXJ5O1xuICB9IGVsc2Uge1xuICAgICQgPSB7fTtcbiAgfVxuXG5cbiAgLyoqXG4gICAqIGpRdWVyeS5jc3YuZGVmYXVsdHNcbiAgICogRW5jYXBzdWxhdGVzIHRoZSBtZXRob2QgcGFyYW1hdGVyIGRlZmF1bHRzIGZvciB0aGUgQ1NWIHBsdWdpbiBtb2R1bGUuXG4gICAqL1xuXG4gICQuY3N2ID0ge1xuICAgIGRlZmF1bHRzOiB7XG4gICAgICBzZXBhcmF0b3I6JywnLFxuICAgICAgZGVsaW1pdGVyOidcIicsXG4gICAgICBoZWFkZXJzOnRydWVcbiAgICB9LFxuXG4gICAgaG9va3M6IHtcbiAgICAgIGNhc3RUb1NjYWxhcjogZnVuY3Rpb24odmFsdWUsIHN0YXRlKSB7XG4gICAgICAgIHZhciBoYXNEb3QgPSAvXFwuLztcbiAgICAgICAgaWYgKGlzTmFOKHZhbHVlKSkge1xuICAgICAgICAgIHJldHVybiB2YWx1ZTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBpZiAoaGFzRG90LnRlc3QodmFsdWUpKSB7XG4gICAgICAgICAgICByZXR1cm4gcGFyc2VGbG9hdCh2YWx1ZSk7XG4gICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIHZhciBpbnRlZ2VyID0gcGFyc2VJbnQodmFsdWUpO1xuICAgICAgICAgICAgaWYoaXNOYU4oaW50ZWdlcikpIHtcbiAgICAgICAgICAgICAgcmV0dXJuIG51bGw7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICByZXR1cm4gaW50ZWdlcjtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICB9LFxuXG4gICAgcGFyc2Vyczoge1xuICAgICAgcGFyc2U6IGZ1bmN0aW9uKGNzdiwgb3B0aW9ucykge1xuICAgICAgICAvLyBjYWNoZSBzZXR0aW5nc1xuICAgICAgICB2YXIgc2VwYXJhdG9yID0gb3B0aW9ucy5zZXBhcmF0b3I7XG4gICAgICAgIHZhciBkZWxpbWl0ZXIgPSBvcHRpb25zLmRlbGltaXRlcjtcblxuICAgICAgICAvLyBzZXQgaW5pdGlhbCBzdGF0ZSBpZiBpdCdzIG1pc3NpbmdcbiAgICAgICAgaWYoIW9wdGlvbnMuc3RhdGUucm93TnVtKSB7XG4gICAgICAgICAgb3B0aW9ucy5zdGF0ZS5yb3dOdW0gPSAxO1xuICAgICAgICB9XG4gICAgICAgIGlmKCFvcHRpb25zLnN0YXRlLmNvbE51bSkge1xuICAgICAgICAgIG9wdGlvbnMuc3RhdGUuY29sTnVtID0gMTtcbiAgICAgICAgfVxuXG4gICAgICAgIC8vIGNsZWFyIGluaXRpYWwgc3RhdGVcbiAgICAgICAgdmFyIGRhdGEgPSBbXTtcbiAgICAgICAgdmFyIGVudHJ5ID0gW107XG4gICAgICAgIHZhciBzdGF0ZSA9IDA7XG4gICAgICAgIHZhciB2YWx1ZSA9ICcnO1xuICAgICAgICB2YXIgZXhpdCA9IGZhbHNlO1xuXG4gICAgICAgIGZ1bmN0aW9uIGVuZE9mRW50cnkoKSB7XG4gICAgICAgICAgLy8gcmVzZXQgdGhlIHN0YXRlXG4gICAgICAgICAgc3RhdGUgPSAwO1xuICAgICAgICAgIHZhbHVlID0gJyc7XG5cbiAgICAgICAgICAvLyBpZiAnc3RhcnQnIGhhc24ndCBiZWVuIG1ldCwgZG9uJ3Qgb3V0cHV0XG4gICAgICAgICAgaWYob3B0aW9ucy5zdGFydCAmJiBvcHRpb25zLnN0YXRlLnJvd051bSA8IG9wdGlvbnMuc3RhcnQpIHtcbiAgICAgICAgICAgIC8vIHVwZGF0ZSBnbG9iYWwgc3RhdGVcbiAgICAgICAgICAgIGVudHJ5ID0gW107XG4gICAgICAgICAgICBvcHRpb25zLnN0YXRlLnJvd051bSsrO1xuICAgICAgICAgICAgb3B0aW9ucy5zdGF0ZS5jb2xOdW0gPSAxO1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgIH1cbiAgICAgICAgICBcbiAgICAgICAgICBpZihvcHRpb25zLm9uUGFyc2VFbnRyeSA9PT0gdW5kZWZpbmVkKSB7XG4gICAgICAgICAgICAvLyBvblBhcnNlRW50cnkgaG9vayBub3Qgc2V0XG4gICAgICAgICAgICBkYXRhLnB1c2goZW50cnkpO1xuICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICB2YXIgaG9va1ZhbCA9IG9wdGlvbnMub25QYXJzZUVudHJ5KGVudHJ5LCBvcHRpb25zLnN0YXRlKTsgLy8gb25QYXJzZUVudHJ5IEhvb2tcbiAgICAgICAgICAgIC8vIGZhbHNlIHNraXBzIHRoZSByb3csIGNvbmZpZ3VyYWJsZSB0aHJvdWdoIGEgaG9va1xuICAgICAgICAgICAgaWYoaG9va1ZhbCAhPT0gZmFsc2UpIHtcbiAgICAgICAgICAgICAgZGF0YS5wdXNoKGhvb2tWYWwpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgIH1cbiAgICAgICAgICAvL2NvbnNvbGUubG9nKCdlbnRyeTonICsgZW50cnkpO1xuICAgICAgICAgIFxuICAgICAgICAgIC8vIGNsZWFudXBcbiAgICAgICAgICBlbnRyeSA9IFtdO1xuXG4gICAgICAgICAgLy8gaWYgJ2VuZCcgaXMgbWV0LCBzdG9wIHBhcnNpbmdcbiAgICAgICAgICBpZihvcHRpb25zLmVuZCAmJiBvcHRpb25zLnN0YXRlLnJvd051bSA+PSBvcHRpb25zLmVuZCkge1xuICAgICAgICAgICAgZXhpdCA9IHRydWU7XG4gICAgICAgICAgfVxuICAgICAgICAgIFxuICAgICAgICAgIC8vIHVwZGF0ZSBnbG9iYWwgc3RhdGVcbiAgICAgICAgICBvcHRpb25zLnN0YXRlLnJvd051bSsrO1xuICAgICAgICAgIG9wdGlvbnMuc3RhdGUuY29sTnVtID0gMTtcbiAgICAgICAgfVxuXG4gICAgICAgIGZ1bmN0aW9uIGVuZE9mVmFsdWUoKSB7XG4gICAgICAgICAgaWYob3B0aW9ucy5vblBhcnNlVmFsdWUgPT09IHVuZGVmaW5lZCkge1xuICAgICAgICAgICAgLy8gb25QYXJzZVZhbHVlIGhvb2sgbm90IHNldFxuICAgICAgICAgICAgZW50cnkucHVzaCh2YWx1ZSk7XG4gICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIHZhciBob29rID0gb3B0aW9ucy5vblBhcnNlVmFsdWUodmFsdWUsIG9wdGlvbnMuc3RhdGUpOyAvLyBvblBhcnNlVmFsdWUgSG9va1xuICAgICAgICAgICAgLy8gZmFsc2Ugc2tpcHMgdGhlIHJvdywgY29uZmlndXJhYmxlIHRocm91Z2ggYSBob29rXG4gICAgICAgICAgICBpZihob29rICE9PSBmYWxzZSkge1xuICAgICAgICAgICAgICBlbnRyeS5wdXNoKGhvb2spO1xuICAgICAgICAgICAgfVxuICAgICAgICAgIH1cbiAgICAgICAgICAvL2NvbnNvbGUubG9nKCd2YWx1ZTonICsgdmFsdWUpO1xuICAgICAgICAgIC8vIHJlc2V0IHRoZSBzdGF0ZVxuICAgICAgICAgIHZhbHVlID0gJyc7XG4gICAgICAgICAgc3RhdGUgPSAwO1xuICAgICAgICAgIC8vIHVwZGF0ZSBnbG9iYWwgc3RhdGVcbiAgICAgICAgICBvcHRpb25zLnN0YXRlLmNvbE51bSsrO1xuICAgICAgICB9XG5cbiAgICAgICAgLy8gZXNjYXBlIHJlZ2V4LXNwZWNpZmljIGNvbnRyb2wgY2hhcnNcbiAgICAgICAgdmFyIGVzY1NlcGFyYXRvciA9IFJlZ0V4cC5lc2NhcGUoc2VwYXJhdG9yKTtcbiAgICAgICAgdmFyIGVzY0RlbGltaXRlciA9IFJlZ0V4cC5lc2NhcGUoZGVsaW1pdGVyKTtcblxuICAgICAgICAvLyBjb21waWxlIHRoZSByZWdFeCBzdHIgdXNpbmcgdGhlIGN1c3RvbSBkZWxpbWl0ZXIvc2VwYXJhdG9yXG4gICAgICAgIHZhciBtYXRjaCA9IC8oRHxTfFxcclxcbnxcXG58XFxyfFteRFNcXHJcXG5dKykvO1xuICAgICAgICB2YXIgbWF0Y2hTcmMgPSBtYXRjaC5zb3VyY2U7XG4gICAgICAgIG1hdGNoU3JjID0gbWF0Y2hTcmMucmVwbGFjZSgvUy9nLCBlc2NTZXBhcmF0b3IpO1xuICAgICAgICBtYXRjaFNyYyA9IG1hdGNoU3JjLnJlcGxhY2UoL0QvZywgZXNjRGVsaW1pdGVyKTtcbiAgICAgICAgbWF0Y2ggPSBuZXcgUmVnRXhwKG1hdGNoU3JjLCAnZ20nKTtcblxuICAgICAgICAvLyBwdXQgb24geW91ciBmYW5jeSBwYW50cy4uLlxuICAgICAgICAvLyBwcm9jZXNzIGNvbnRyb2wgY2hhcnMgaW5kaXZpZHVhbGx5LCB1c2UgbG9vay1haGVhZCBvbiBub24tY29udHJvbCBjaGFyc1xuICAgICAgICBjc3YucmVwbGFjZShtYXRjaCwgZnVuY3Rpb24gKG0wKSB7XG4gICAgICAgICAgaWYoZXhpdCkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgIH1cbiAgICAgICAgICBzd2l0Y2ggKHN0YXRlKSB7XG4gICAgICAgICAgICAvLyB0aGUgc3RhcnQgb2YgYSB2YWx1ZVxuICAgICAgICAgICAgY2FzZSAwOlxuICAgICAgICAgICAgICAvLyBudWxsIGxhc3QgdmFsdWVcbiAgICAgICAgICAgICAgaWYgKG0wID09PSBzZXBhcmF0b3IpIHtcbiAgICAgICAgICAgICAgICB2YWx1ZSArPSAnJztcbiAgICAgICAgICAgICAgICBlbmRPZlZhbHVlKCk7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgLy8gb3BlbmluZyBkZWxpbWl0ZXJcbiAgICAgICAgICAgICAgaWYgKG0wID09PSBkZWxpbWl0ZXIpIHtcbiAgICAgICAgICAgICAgICBzdGF0ZSA9IDE7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgLy8gbnVsbCBsYXN0IHZhbHVlXG4gICAgICAgICAgICAgIGlmICgvXihcXHJcXG58XFxufFxccikkLy50ZXN0KG0wKSkge1xuICAgICAgICAgICAgICAgIGVuZE9mVmFsdWUoKTtcbiAgICAgICAgICAgICAgICBlbmRPZkVudHJ5KCk7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgLy8gdW4tZGVsaW1pdGVkIHZhbHVlXG4gICAgICAgICAgICAgIHZhbHVlICs9IG0wO1xuICAgICAgICAgICAgICBzdGF0ZSA9IDM7XG4gICAgICAgICAgICAgIGJyZWFrO1xuXG4gICAgICAgICAgICAvLyBkZWxpbWl0ZWQgaW5wdXRcbiAgICAgICAgICAgIGNhc2UgMTpcbiAgICAgICAgICAgICAgLy8gc2Vjb25kIGRlbGltaXRlcj8gY2hlY2sgZnVydGhlclxuICAgICAgICAgICAgICBpZiAobTAgPT09IGRlbGltaXRlcikge1xuICAgICAgICAgICAgICAgIHN0YXRlID0gMjtcbiAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAvLyBkZWxpbWl0ZWQgZGF0YVxuICAgICAgICAgICAgICB2YWx1ZSArPSBtMDtcbiAgICAgICAgICAgICAgc3RhdGUgPSAxO1xuICAgICAgICAgICAgICBicmVhaztcblxuICAgICAgICAgICAgLy8gZGVsaW1pdGVyIGZvdW5kIGluIGRlbGltaXRlZCBpbnB1dFxuICAgICAgICAgICAgY2FzZSAyOlxuICAgICAgICAgICAgICAvLyBlc2NhcGVkIGRlbGltaXRlcj9cbiAgICAgICAgICAgICAgaWYgKG0wID09PSBkZWxpbWl0ZXIpIHtcbiAgICAgICAgICAgICAgICB2YWx1ZSArPSBtMDtcbiAgICAgICAgICAgICAgICBzdGF0ZSA9IDE7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgLy8gbnVsbCB2YWx1ZVxuICAgICAgICAgICAgICBpZiAobTAgPT09IHNlcGFyYXRvcikge1xuICAgICAgICAgICAgICAgIGVuZE9mVmFsdWUoKTtcbiAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAvLyBlbmQgb2YgZW50cnlcbiAgICAgICAgICAgICAgaWYgKC9eKFxcclxcbnxcXG58XFxyKSQvLnRlc3QobTApKSB7XG4gICAgICAgICAgICAgICAgZW5kT2ZWYWx1ZSgpO1xuICAgICAgICAgICAgICAgIGVuZE9mRW50cnkoKTtcbiAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAvLyBicm9rZW4gcGFzZXI/XG4gICAgICAgICAgICAgIHRocm93IG5ldyBFcnJvcignQ1NWRGF0YUVycm9yOiBJbGxlZ2FsIFN0YXRlIFtSb3c6JyArIG9wdGlvbnMuc3RhdGUucm93TnVtICsgJ11bQ29sOicgKyBvcHRpb25zLnN0YXRlLmNvbE51bSArICddJyk7XG5cbiAgICAgICAgICAgIC8vIHVuLWRlbGltaXRlZCBpbnB1dFxuICAgICAgICAgICAgY2FzZSAzOlxuICAgICAgICAgICAgICAvLyBudWxsIGxhc3QgdmFsdWVcbiAgICAgICAgICAgICAgaWYgKG0wID09PSBzZXBhcmF0b3IpIHtcbiAgICAgICAgICAgICAgICBlbmRPZlZhbHVlKCk7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgLy8gZW5kIG9mIGVudHJ5XG4gICAgICAgICAgICAgIGlmICgvXihcXHJcXG58XFxufFxccikkLy50ZXN0KG0wKSkge1xuICAgICAgICAgICAgICAgIGVuZE9mVmFsdWUoKTtcbiAgICAgICAgICAgICAgICBlbmRPZkVudHJ5KCk7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgaWYgKG0wID09PSBkZWxpbWl0ZXIpIHtcbiAgICAgICAgICAgICAgLy8gbm9uLWNvbXBsaWFudCBkYXRhXG4gICAgICAgICAgICAgICAgdGhyb3cgbmV3IEVycm9yKCdDU1ZEYXRhRXJyb3I6IElsbGVnYWwgUXVvdGUgW1JvdzonICsgb3B0aW9ucy5zdGF0ZS5yb3dOdW0gKyAnXVtDb2w6JyArIG9wdGlvbnMuc3RhdGUuY29sTnVtICsgJ10nKTtcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAvLyBicm9rZW4gcGFyc2VyP1xuICAgICAgICAgICAgICB0aHJvdyBuZXcgRXJyb3IoJ0NTVkRhdGFFcnJvcjogSWxsZWdhbCBEYXRhIFtSb3c6JyArIG9wdGlvbnMuc3RhdGUucm93TnVtICsgJ11bQ29sOicgKyBvcHRpb25zLnN0YXRlLmNvbE51bSArICddJyk7XG4gICAgICAgICAgICBkZWZhdWx0OlxuICAgICAgICAgICAgICAvLyBzaGVuYW5pZ2Fuc1xuICAgICAgICAgICAgICB0aHJvdyBuZXcgRXJyb3IoJ0NTVkRhdGFFcnJvcjogVW5rbm93biBTdGF0ZSBbUm93OicgKyBvcHRpb25zLnN0YXRlLnJvd051bSArICddW0NvbDonICsgb3B0aW9ucy5zdGF0ZS5jb2xOdW0gKyAnXScpO1xuICAgICAgICAgIH1cbiAgICAgICAgICAvL2NvbnNvbGUubG9nKCd2YWw6JyArIG0wICsgJyBzdGF0ZTonICsgc3RhdGUpO1xuICAgICAgICB9KTtcblxuICAgICAgICAvLyBzdWJtaXQgdGhlIGxhc3QgZW50cnlcbiAgICAgICAgLy8gaWdub3JlIG51bGwgbGFzdCBsaW5lXG4gICAgICAgIGlmKGVudHJ5Lmxlbmd0aCAhPT0gMCkge1xuICAgICAgICAgIGVuZE9mVmFsdWUoKTtcbiAgICAgICAgICBlbmRPZkVudHJ5KCk7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gZGF0YTtcbiAgICAgIH0sXG5cbiAgICAgIC8vIGEgY3N2LXNwZWNpZmljIGxpbmUgc3BsaXR0ZXJcbiAgICAgIHNwbGl0TGluZXM6IGZ1bmN0aW9uKGNzdiwgb3B0aW9ucykge1xuICAgICAgICAvLyBjYWNoZSBzZXR0aW5nc1xuICAgICAgICB2YXIgc2VwYXJhdG9yID0gb3B0aW9ucy5zZXBhcmF0b3I7XG4gICAgICAgIHZhciBkZWxpbWl0ZXIgPSBvcHRpb25zLmRlbGltaXRlcjtcblxuICAgICAgICAvLyBzZXQgaW5pdGlhbCBzdGF0ZSBpZiBpdCdzIG1pc3NpbmdcbiAgICAgICAgaWYoIW9wdGlvbnMuc3RhdGUucm93TnVtKSB7XG4gICAgICAgICAgb3B0aW9ucy5zdGF0ZS5yb3dOdW0gPSAxO1xuICAgICAgICB9XG5cbiAgICAgICAgLy8gY2xlYXIgaW5pdGlhbCBzdGF0ZVxuICAgICAgICB2YXIgZW50cmllcyA9IFtdO1xuICAgICAgICB2YXIgc3RhdGUgPSAwO1xuICAgICAgICB2YXIgZW50cnkgPSAnJztcbiAgICAgICAgdmFyIGV4aXQgPSBmYWxzZTtcblxuICAgICAgICBmdW5jdGlvbiBlbmRPZkxpbmUoKSB7ICAgICAgICAgIFxuICAgICAgICAgIC8vIHJlc2V0IHRoZSBzdGF0ZVxuICAgICAgICAgIHN0YXRlID0gMDtcbiAgICAgICAgICBcbiAgICAgICAgICAvLyBpZiAnc3RhcnQnIGhhc24ndCBiZWVuIG1ldCwgZG9uJ3Qgb3V0cHV0XG4gICAgICAgICAgaWYob3B0aW9ucy5zdGFydCAmJiBvcHRpb25zLnN0YXRlLnJvd051bSA8IG9wdGlvbnMuc3RhcnQpIHtcbiAgICAgICAgICAgIC8vIHVwZGF0ZSBnbG9iYWwgc3RhdGVcbiAgICAgICAgICAgIGVudHJ5ID0gJyc7XG4gICAgICAgICAgICBvcHRpb25zLnN0YXRlLnJvd051bSsrO1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgIH1cbiAgICAgICAgICBcbiAgICAgICAgICBpZihvcHRpb25zLm9uUGFyc2VFbnRyeSA9PT0gdW5kZWZpbmVkKSB7XG4gICAgICAgICAgICAvLyBvblBhcnNlRW50cnkgaG9vayBub3Qgc2V0XG4gICAgICAgICAgICBlbnRyaWVzLnB1c2goZW50cnkpO1xuICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICB2YXIgaG9va1ZhbCA9IG9wdGlvbnMub25QYXJzZUVudHJ5KGVudHJ5LCBvcHRpb25zLnN0YXRlKTsgLy8gb25QYXJzZUVudHJ5IEhvb2tcbiAgICAgICAgICAgIC8vIGZhbHNlIHNraXBzIHRoZSByb3csIGNvbmZpZ3VyYWJsZSB0aHJvdWdoIGEgaG9va1xuICAgICAgICAgICAgaWYoaG9va1ZhbCAhPT0gZmFsc2UpIHtcbiAgICAgICAgICAgICAgZW50cmllcy5wdXNoKGhvb2tWYWwpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgIH1cblxuICAgICAgICAgIC8vIGNsZWFudXBcbiAgICAgICAgICBlbnRyeSA9ICcnO1xuXG4gICAgICAgICAgLy8gaWYgJ2VuZCcgaXMgbWV0LCBzdG9wIHBhcnNpbmdcbiAgICAgICAgICBpZihvcHRpb25zLmVuZCAmJiBvcHRpb25zLnN0YXRlLnJvd051bSA+PSBvcHRpb25zLmVuZCkge1xuICAgICAgICAgICAgZXhpdCA9IHRydWU7XG4gICAgICAgICAgfVxuICAgICAgICAgIFxuICAgICAgICAgIC8vIHVwZGF0ZSBnbG9iYWwgc3RhdGVcbiAgICAgICAgICBvcHRpb25zLnN0YXRlLnJvd051bSsrO1xuICAgICAgICB9XG5cbiAgICAgICAgLy8gZXNjYXBlIHJlZ2V4LXNwZWNpZmljIGNvbnRyb2wgY2hhcnNcbiAgICAgICAgdmFyIGVzY1NlcGFyYXRvciA9IFJlZ0V4cC5lc2NhcGUoc2VwYXJhdG9yKTtcbiAgICAgICAgdmFyIGVzY0RlbGltaXRlciA9IFJlZ0V4cC5lc2NhcGUoZGVsaW1pdGVyKTtcblxuICAgICAgICAvLyBjb21waWxlIHRoZSByZWdFeCBzdHIgdXNpbmcgdGhlIGN1c3RvbSBkZWxpbWl0ZXIvc2VwYXJhdG9yXG4gICAgICAgIHZhciBtYXRjaCA9IC8oRHxTfFxcbnxcXHJ8W15EU1xcclxcbl0rKS87XG4gICAgICAgIHZhciBtYXRjaFNyYyA9IG1hdGNoLnNvdXJjZTtcbiAgICAgICAgbWF0Y2hTcmMgPSBtYXRjaFNyYy5yZXBsYWNlKC9TL2csIGVzY1NlcGFyYXRvcik7XG4gICAgICAgIG1hdGNoU3JjID0gbWF0Y2hTcmMucmVwbGFjZSgvRC9nLCBlc2NEZWxpbWl0ZXIpO1xuICAgICAgICBtYXRjaCA9IG5ldyBSZWdFeHAobWF0Y2hTcmMsICdnbScpO1xuXG4gICAgICAgIC8vIHB1dCBvbiB5b3VyIGZhbmN5IHBhbnRzLi4uXG4gICAgICAgIC8vIHByb2Nlc3MgY29udHJvbCBjaGFycyBpbmRpdmlkdWFsbHksIHVzZSBsb29rLWFoZWFkIG9uIG5vbi1jb250cm9sIGNoYXJzXG4gICAgICAgIGNzdi5yZXBsYWNlKG1hdGNoLCBmdW5jdGlvbiAobTApIHtcbiAgICAgICAgICBpZihleGl0KSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgfVxuICAgICAgICAgIHN3aXRjaCAoc3RhdGUpIHtcbiAgICAgICAgICAgIC8vIHRoZSBzdGFydCBvZiBhIHZhbHVlL2VudHJ5XG4gICAgICAgICAgICBjYXNlIDA6XG4gICAgICAgICAgICAgIC8vIG51bGwgdmFsdWVcbiAgICAgICAgICAgICAgaWYgKG0wID09PSBzZXBhcmF0b3IpIHtcbiAgICAgICAgICAgICAgICBlbnRyeSArPSBtMDtcbiAgICAgICAgICAgICAgICBzdGF0ZSA9IDA7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgLy8gb3BlbmluZyBkZWxpbWl0ZXJcbiAgICAgICAgICAgICAgaWYgKG0wID09PSBkZWxpbWl0ZXIpIHtcbiAgICAgICAgICAgICAgICBlbnRyeSArPSBtMDtcbiAgICAgICAgICAgICAgICBzdGF0ZSA9IDE7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgLy8gZW5kIG9mIGxpbmVcbiAgICAgICAgICAgICAgaWYgKG0wID09PSAnXFxuJykge1xuICAgICAgICAgICAgICAgIGVuZE9mTGluZSgpO1xuICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgIC8vIHBoYW50b20gY2FycmlhZ2UgcmV0dXJuXG4gICAgICAgICAgICAgIGlmICgvXlxcciQvLnRlc3QobTApKSB7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgLy8gdW4tZGVsaW1pdCB2YWx1ZVxuICAgICAgICAgICAgICBlbnRyeSArPSBtMDtcbiAgICAgICAgICAgICAgc3RhdGUgPSAzO1xuICAgICAgICAgICAgICBicmVhaztcblxuICAgICAgICAgICAgLy8gZGVsaW1pdGVkIGlucHV0XG4gICAgICAgICAgICBjYXNlIDE6XG4gICAgICAgICAgICAgIC8vIHNlY29uZCBkZWxpbWl0ZXI/IGNoZWNrIGZ1cnRoZXJcbiAgICAgICAgICAgICAgaWYgKG0wID09PSBkZWxpbWl0ZXIpIHtcbiAgICAgICAgICAgICAgICBlbnRyeSArPSBtMDtcbiAgICAgICAgICAgICAgICBzdGF0ZSA9IDI7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgLy8gZGVsaW1pdGVkIGRhdGFcbiAgICAgICAgICAgICAgZW50cnkgKz0gbTA7XG4gICAgICAgICAgICAgIHN0YXRlID0gMTtcbiAgICAgICAgICAgICAgYnJlYWs7XG5cbiAgICAgICAgICAgIC8vIGRlbGltaXRlciBmb3VuZCBpbiBkZWxpbWl0ZWQgaW5wdXRcbiAgICAgICAgICAgIGNhc2UgMjpcbiAgICAgICAgICAgICAgLy8gZXNjYXBlZCBkZWxpbWl0ZXI/XG4gICAgICAgICAgICAgIHZhciBwcmV2Q2hhciA9IGVudHJ5LnN1YnN0cihlbnRyeS5sZW5ndGggLSAxKTtcbiAgICAgICAgICAgICAgaWYgKG0wID09PSBkZWxpbWl0ZXIgJiYgcHJldkNoYXIgPT09IGRlbGltaXRlcikge1xuICAgICAgICAgICAgICAgIGVudHJ5ICs9IG0wO1xuICAgICAgICAgICAgICAgIHN0YXRlID0gMTtcbiAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAvLyBlbmQgb2YgdmFsdWVcbiAgICAgICAgICAgICAgaWYgKG0wID09PSBzZXBhcmF0b3IpIHtcbiAgICAgICAgICAgICAgICBlbnRyeSArPSBtMDtcbiAgICAgICAgICAgICAgICBzdGF0ZSA9IDA7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgLy8gZW5kIG9mIGxpbmVcbiAgICAgICAgICAgICAgaWYgKG0wID09PSAnXFxuJykge1xuICAgICAgICAgICAgICAgIGVuZE9mTGluZSgpO1xuICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgIC8vIHBoYW50b20gY2FycmlhZ2UgcmV0dXJuXG4gICAgICAgICAgICAgIGlmIChtMCA9PT0gJ1xccicpIHtcbiAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAvLyBicm9rZW4gcGFzZXI/XG4gICAgICAgICAgICAgIHRocm93IG5ldyBFcnJvcignQ1NWRGF0YUVycm9yOiBJbGxlZ2FsIHN0YXRlIFtSb3c6JyArIG9wdGlvbnMuc3RhdGUucm93TnVtICsgJ10nKTtcblxuICAgICAgICAgICAgLy8gdW4tZGVsaW1pdGVkIGlucHV0XG4gICAgICAgICAgICBjYXNlIDM6XG4gICAgICAgICAgICAgIC8vIG51bGwgdmFsdWVcbiAgICAgICAgICAgICAgaWYgKG0wID09PSBzZXBhcmF0b3IpIHtcbiAgICAgICAgICAgICAgICBlbnRyeSArPSBtMDtcbiAgICAgICAgICAgICAgICBzdGF0ZSA9IDA7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgLy8gZW5kIG9mIGxpbmVcbiAgICAgICAgICAgICAgaWYgKG0wID09PSAnXFxuJykge1xuICAgICAgICAgICAgICAgIGVuZE9mTGluZSgpO1xuICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgIC8vIHBoYW50b20gY2FycmlhZ2UgcmV0dXJuXG4gICAgICAgICAgICAgIGlmIChtMCA9PT0gJ1xccicpIHtcbiAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAvLyBub24tY29tcGxpYW50IGRhdGFcbiAgICAgICAgICAgICAgaWYgKG0wID09PSBkZWxpbWl0ZXIpIHtcbiAgICAgICAgICAgICAgICB0aHJvdyBuZXcgRXJyb3IoJ0NTVkRhdGFFcnJvcjogSWxsZWdhbCBxdW90ZSBbUm93OicgKyBvcHRpb25zLnN0YXRlLnJvd051bSArICddJyk7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgLy8gYnJva2VuIHBhcnNlcj9cbiAgICAgICAgICAgICAgdGhyb3cgbmV3IEVycm9yKCdDU1ZEYXRhRXJyb3I6IElsbGVnYWwgc3RhdGUgW1JvdzonICsgb3B0aW9ucy5zdGF0ZS5yb3dOdW0gKyAnXScpO1xuICAgICAgICAgICAgZGVmYXVsdDpcbiAgICAgICAgICAgICAgLy8gc2hlbmFuaWdhbnNcbiAgICAgICAgICAgICAgdGhyb3cgbmV3IEVycm9yKCdDU1ZEYXRhRXJyb3I6IFVua25vd24gc3RhdGUgW1JvdzonICsgb3B0aW9ucy5zdGF0ZS5yb3dOdW0gKyAnXScpO1xuICAgICAgICAgIH1cbiAgICAgICAgICAvL2NvbnNvbGUubG9nKCd2YWw6JyArIG0wICsgJyBzdGF0ZTonICsgc3RhdGUpO1xuICAgICAgICB9KTtcblxuICAgICAgICAvLyBzdWJtaXQgdGhlIGxhc3QgZW50cnlcbiAgICAgICAgLy8gaWdub3JlIG51bGwgbGFzdCBsaW5lXG4gICAgICAgIGlmKGVudHJ5ICE9PSAnJykge1xuICAgICAgICAgIGVuZE9mTGluZSgpO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIGVudHJpZXM7XG4gICAgICB9LFxuXG4gICAgICAvLyBhIGNzdiBlbnRyeSBwYXJzZXJcbiAgICAgIHBhcnNlRW50cnk6IGZ1bmN0aW9uKGNzdiwgb3B0aW9ucykge1xuICAgICAgICAvLyBjYWNoZSBzZXR0aW5nc1xuICAgICAgICB2YXIgc2VwYXJhdG9yID0gb3B0aW9ucy5zZXBhcmF0b3I7XG4gICAgICAgIHZhciBkZWxpbWl0ZXIgPSBvcHRpb25zLmRlbGltaXRlcjtcbiAgICAgICAgXG4gICAgICAgIC8vIHNldCBpbml0aWFsIHN0YXRlIGlmIGl0J3MgbWlzc2luZ1xuICAgICAgICBpZighb3B0aW9ucy5zdGF0ZS5yb3dOdW0pIHtcbiAgICAgICAgICBvcHRpb25zLnN0YXRlLnJvd051bSA9IDE7XG4gICAgICAgIH1cbiAgICAgICAgaWYoIW9wdGlvbnMuc3RhdGUuY29sTnVtKSB7XG4gICAgICAgICAgb3B0aW9ucy5zdGF0ZS5jb2xOdW0gPSAxO1xuICAgICAgICB9XG5cbiAgICAgICAgLy8gY2xlYXIgaW5pdGlhbCBzdGF0ZVxuICAgICAgICB2YXIgZW50cnkgPSBbXTtcbiAgICAgICAgdmFyIHN0YXRlID0gMDtcbiAgICAgICAgdmFyIHZhbHVlID0gJyc7XG5cbiAgICAgICAgZnVuY3Rpb24gZW5kT2ZWYWx1ZSgpIHtcbiAgICAgICAgICBpZihvcHRpb25zLm9uUGFyc2VWYWx1ZSA9PT0gdW5kZWZpbmVkKSB7XG4gICAgICAgICAgICAvLyBvblBhcnNlVmFsdWUgaG9vayBub3Qgc2V0XG4gICAgICAgICAgICBlbnRyeS5wdXNoKHZhbHVlKTtcbiAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgdmFyIGhvb2sgPSBvcHRpb25zLm9uUGFyc2VWYWx1ZSh2YWx1ZSwgb3B0aW9ucy5zdGF0ZSk7IC8vIG9uUGFyc2VWYWx1ZSBIb29rXG4gICAgICAgICAgICAvLyBmYWxzZSBza2lwcyB0aGUgdmFsdWUsIGNvbmZpZ3VyYWJsZSB0aHJvdWdoIGEgaG9va1xuICAgICAgICAgICAgaWYoaG9vayAhPT0gZmFsc2UpIHtcbiAgICAgICAgICAgICAgZW50cnkucHVzaChob29rKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9XG4gICAgICAgICAgLy8gcmVzZXQgdGhlIHN0YXRlXG4gICAgICAgICAgdmFsdWUgPSAnJztcbiAgICAgICAgICBzdGF0ZSA9IDA7XG4gICAgICAgICAgLy8gdXBkYXRlIGdsb2JhbCBzdGF0ZVxuICAgICAgICAgIG9wdGlvbnMuc3RhdGUuY29sTnVtKys7XG4gICAgICAgIH1cblxuICAgICAgICAvLyBjaGVja2VkIGZvciBhIGNhY2hlZCByZWdFeCBmaXJzdFxuICAgICAgICBpZighb3B0aW9ucy5tYXRjaCkge1xuICAgICAgICAgIC8vIGVzY2FwZSByZWdleC1zcGVjaWZpYyBjb250cm9sIGNoYXJzXG4gICAgICAgICAgdmFyIGVzY1NlcGFyYXRvciA9IFJlZ0V4cC5lc2NhcGUoc2VwYXJhdG9yKTtcbiAgICAgICAgICB2YXIgZXNjRGVsaW1pdGVyID0gUmVnRXhwLmVzY2FwZShkZWxpbWl0ZXIpO1xuICAgICAgICAgIFxuICAgICAgICAgIC8vIGNvbXBpbGUgdGhlIHJlZ0V4IHN0ciB1c2luZyB0aGUgY3VzdG9tIGRlbGltaXRlci9zZXBhcmF0b3JcbiAgICAgICAgICB2YXIgbWF0Y2ggPSAvKER8U3xcXG58XFxyfFteRFNcXHJcXG5dKykvO1xuICAgICAgICAgIHZhciBtYXRjaFNyYyA9IG1hdGNoLnNvdXJjZTtcbiAgICAgICAgICBtYXRjaFNyYyA9IG1hdGNoU3JjLnJlcGxhY2UoL1MvZywgZXNjU2VwYXJhdG9yKTtcbiAgICAgICAgICBtYXRjaFNyYyA9IG1hdGNoU3JjLnJlcGxhY2UoL0QvZywgZXNjRGVsaW1pdGVyKTtcbiAgICAgICAgICBvcHRpb25zLm1hdGNoID0gbmV3IFJlZ0V4cChtYXRjaFNyYywgJ2dtJyk7XG4gICAgICAgIH1cblxuICAgICAgICAvLyBwdXQgb24geW91ciBmYW5jeSBwYW50cy4uLlxuICAgICAgICAvLyBwcm9jZXNzIGNvbnRyb2wgY2hhcnMgaW5kaXZpZHVhbGx5LCB1c2UgbG9vay1haGVhZCBvbiBub24tY29udHJvbCBjaGFyc1xuICAgICAgICBjc3YucmVwbGFjZShvcHRpb25zLm1hdGNoLCBmdW5jdGlvbiAobTApIHtcbiAgICAgICAgICBzd2l0Y2ggKHN0YXRlKSB7XG4gICAgICAgICAgICAvLyB0aGUgc3RhcnQgb2YgYSB2YWx1ZVxuICAgICAgICAgICAgY2FzZSAwOlxuICAgICAgICAgICAgICAvLyBudWxsIGxhc3QgdmFsdWVcbiAgICAgICAgICAgICAgaWYgKG0wID09PSBzZXBhcmF0b3IpIHtcbiAgICAgICAgICAgICAgICB2YWx1ZSArPSAnJztcbiAgICAgICAgICAgICAgICBlbmRPZlZhbHVlKCk7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgLy8gb3BlbmluZyBkZWxpbWl0ZXJcbiAgICAgICAgICAgICAgaWYgKG0wID09PSBkZWxpbWl0ZXIpIHtcbiAgICAgICAgICAgICAgICBzdGF0ZSA9IDE7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgLy8gc2tpcCB1bi1kZWxpbWl0ZWQgbmV3LWxpbmVzXG4gICAgICAgICAgICAgIGlmIChtMCA9PT0gJ1xcbicgfHwgbTAgPT09ICdcXHInKSB7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgLy8gdW4tZGVsaW1pdGVkIHZhbHVlXG4gICAgICAgICAgICAgIHZhbHVlICs9IG0wO1xuICAgICAgICAgICAgICBzdGF0ZSA9IDM7XG4gICAgICAgICAgICAgIGJyZWFrO1xuXG4gICAgICAgICAgICAvLyBkZWxpbWl0ZWQgaW5wdXRcbiAgICAgICAgICAgIGNhc2UgMTpcbiAgICAgICAgICAgICAgLy8gc2Vjb25kIGRlbGltaXRlcj8gY2hlY2sgZnVydGhlclxuICAgICAgICAgICAgICBpZiAobTAgPT09IGRlbGltaXRlcikge1xuICAgICAgICAgICAgICAgIHN0YXRlID0gMjtcbiAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAvLyBkZWxpbWl0ZWQgZGF0YVxuICAgICAgICAgICAgICB2YWx1ZSArPSBtMDtcbiAgICAgICAgICAgICAgc3RhdGUgPSAxO1xuICAgICAgICAgICAgICBicmVhaztcblxuICAgICAgICAgICAgLy8gZGVsaW1pdGVyIGZvdW5kIGluIGRlbGltaXRlZCBpbnB1dFxuICAgICAgICAgICAgY2FzZSAyOlxuICAgICAgICAgICAgICAvLyBlc2NhcGVkIGRlbGltaXRlcj9cbiAgICAgICAgICAgICAgaWYgKG0wID09PSBkZWxpbWl0ZXIpIHtcbiAgICAgICAgICAgICAgICB2YWx1ZSArPSBtMDtcbiAgICAgICAgICAgICAgICBzdGF0ZSA9IDE7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgLy8gbnVsbCB2YWx1ZVxuICAgICAgICAgICAgICBpZiAobTAgPT09IHNlcGFyYXRvcikge1xuICAgICAgICAgICAgICAgIGVuZE9mVmFsdWUoKTtcbiAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAvLyBza2lwIHVuLWRlbGltaXRlZCBuZXctbGluZXNcbiAgICAgICAgICAgICAgaWYgKG0wID09PSAnXFxuJyB8fCBtMCA9PT0gJ1xccicpIHtcbiAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAvLyBicm9rZW4gcGFzZXI/XG4gICAgICAgICAgICAgIHRocm93IG5ldyBFcnJvcignQ1NWRGF0YUVycm9yOiBJbGxlZ2FsIFN0YXRlIFtSb3c6JyArIG9wdGlvbnMuc3RhdGUucm93TnVtICsgJ11bQ29sOicgKyBvcHRpb25zLnN0YXRlLmNvbE51bSArICddJyk7XG5cbiAgICAgICAgICAgIC8vIHVuLWRlbGltaXRlZCBpbnB1dFxuICAgICAgICAgICAgY2FzZSAzOlxuICAgICAgICAgICAgICAvLyBudWxsIGxhc3QgdmFsdWVcbiAgICAgICAgICAgICAgaWYgKG0wID09PSBzZXBhcmF0b3IpIHtcbiAgICAgICAgICAgICAgICBlbmRPZlZhbHVlKCk7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgLy8gc2tpcCB1bi1kZWxpbWl0ZWQgbmV3LWxpbmVzXG4gICAgICAgICAgICAgIGlmIChtMCA9PT0gJ1xcbicgfHwgbTAgPT09ICdcXHInKSB7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgLy8gbm9uLWNvbXBsaWFudCBkYXRhXG4gICAgICAgICAgICAgIGlmIChtMCA9PT0gZGVsaW1pdGVyKSB7XG4gICAgICAgICAgICAgICAgdGhyb3cgbmV3IEVycm9yKCdDU1ZEYXRhRXJyb3I6IElsbGVnYWwgUXVvdGUgW1JvdzonICsgb3B0aW9ucy5zdGF0ZS5yb3dOdW0gKyAnXVtDb2w6JyArIG9wdGlvbnMuc3RhdGUuY29sTnVtICsgJ10nKTtcbiAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAvLyBicm9rZW4gcGFyc2VyP1xuICAgICAgICAgICAgICB0aHJvdyBuZXcgRXJyb3IoJ0NTVkRhdGFFcnJvcjogSWxsZWdhbCBEYXRhIFtSb3c6JyArIG9wdGlvbnMuc3RhdGUucm93TnVtICsgJ11bQ29sOicgKyBvcHRpb25zLnN0YXRlLmNvbE51bSArICddJyk7XG4gICAgICAgICAgICBkZWZhdWx0OlxuICAgICAgICAgICAgICAvLyBzaGVuYW5pZ2Fuc1xuICAgICAgICAgICAgICB0aHJvdyBuZXcgRXJyb3IoJ0NTVkRhdGFFcnJvcjogVW5rbm93biBTdGF0ZSBbUm93OicgKyBvcHRpb25zLnN0YXRlLnJvd051bSArICddW0NvbDonICsgb3B0aW9ucy5zdGF0ZS5jb2xOdW0gKyAnXScpO1xuICAgICAgICAgIH1cbiAgICAgICAgICAvL2NvbnNvbGUubG9nKCd2YWw6JyArIG0wICsgJyBzdGF0ZTonICsgc3RhdGUpO1xuICAgICAgICB9KTtcblxuICAgICAgICAvLyBzdWJtaXQgdGhlIGxhc3QgdmFsdWVcbiAgICAgICAgZW5kT2ZWYWx1ZSgpO1xuXG4gICAgICAgIHJldHVybiBlbnRyeTtcbiAgICAgIH1cbiAgICB9LFxuXG4gICAgaGVscGVyczoge1xuXG4gICAgICAvKipcbiAgICAgICAqICQuY3N2LmhlbHBlcnMuY29sbGVjdFByb3BlcnR5TmFtZXMob2JqZWN0c0FycmF5KVxuICAgICAgICogQ29sbGVjdHMgYWxsIHVuaXF1ZSBwcm9wZXJ0eSBuYW1lcyBmcm9tIGFsbCBwYXNzZWQgb2JqZWN0cy5cbiAgICAgICAqXG4gICAgICAgKiBAcGFyYW0ge0FycmF5fSBvYmplY3RzIE9iamVjdHMgdG8gY29sbGVjdCBwcm9wZXJ0aWVzIGZyb20uXG4gICAgICAgKlxuICAgICAgICogUmV0dXJucyBhbiBhcnJheSBvZiBwcm9wZXJ0eSBuYW1lcyAoYXJyYXkgd2lsbCBiZSBlbXB0eSxcbiAgICAgICAqIGlmIG9iamVjdHMgaGF2ZSBubyBvd24gcHJvcGVydGllcykuXG4gICAgICAgKi9cbiAgICAgIGNvbGxlY3RQcm9wZXJ0eU5hbWVzOiBmdW5jdGlvbiAob2JqZWN0cykge1xuXG4gICAgICAgIHZhciBvLCBwcm9wTmFtZSwgcHJvcHMgPSBbXTtcbiAgICAgICAgZm9yIChvIGluIG9iamVjdHMpIHtcbiAgICAgICAgICBmb3IgKHByb3BOYW1lIGluIG9iamVjdHNbb10pIHtcbiAgICAgICAgICAgIGlmICgob2JqZWN0c1tvXS5oYXNPd25Qcm9wZXJ0eShwcm9wTmFtZSkpICYmXG4gICAgICAgICAgICAgICAgKHByb3BzLmluZGV4T2YocHJvcE5hbWUpIDwgMCkgJiYgXG4gICAgICAgICAgICAgICAgKHR5cGVvZiBvYmplY3RzW29dW3Byb3BOYW1lXSAhPT0gJ2Z1bmN0aW9uJykpIHtcblxuICAgICAgICAgICAgICBwcm9wcy5wdXNoKHByb3BOYW1lKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIHByb3BzO1xuICAgICAgfVxuICAgIH0sXG5cbiAgICAvKipcbiAgICAgKiAkLmNzdi50b0FycmF5KGNzdilcbiAgICAgKiBDb252ZXJ0cyBhIENTViBlbnRyeSBzdHJpbmcgdG8gYSBqYXZhc2NyaXB0IGFycmF5LlxuICAgICAqXG4gICAgICogQHBhcmFtIHtBcnJheX0gY3N2IFRoZSBzdHJpbmcgY29udGFpbmluZyB0aGUgQ1NWIGRhdGEuXG4gICAgICogQHBhcmFtIHtPYmplY3R9IFtvcHRpb25zXSBBbiBvYmplY3QgY29udGFpbmluZyB1c2VyLWRlZmluZWQgb3B0aW9ucy5cbiAgICAgKiBAcGFyYW0ge0NoYXJhY3Rlcn0gW3NlcGFyYXRvcl0gQW4gb3ZlcnJpZGUgZm9yIHRoZSBzZXBhcmF0b3IgY2hhcmFjdGVyLiBEZWZhdWx0cyB0byBhIGNvbW1hKCwpLlxuICAgICAqIEBwYXJhbSB7Q2hhcmFjdGVyfSBbZGVsaW1pdGVyXSBBbiBvdmVycmlkZSBmb3IgdGhlIGRlbGltaXRlciBjaGFyYWN0ZXIuIERlZmF1bHRzIHRvIGEgZG91YmxlLXF1b3RlKFwiKS5cbiAgICAgKlxuICAgICAqIFRoaXMgbWV0aG9kIGRlYWxzIHdpdGggc2ltcGxlIENTViBzdHJpbmdzIG9ubHkuIEl0J3MgdXNlZnVsIGlmIHlvdSBvbmx5XG4gICAgICogbmVlZCB0byBwYXJzZSBhIHNpbmdsZSBlbnRyeS4gSWYgeW91IG5lZWQgdG8gcGFyc2UgbW9yZSB0aGFuIG9uZSBsaW5lLFxuICAgICAqIHVzZSAkLmNzdjJBcnJheSBpbnN0ZWFkLlxuICAgICAqL1xuICAgIHRvQXJyYXk6IGZ1bmN0aW9uKGNzdiwgb3B0aW9ucywgY2FsbGJhY2spIHtcbiAgICAgIG9wdGlvbnMgPSAob3B0aW9ucyAhPT0gdW5kZWZpbmVkID8gb3B0aW9ucyA6IHt9KTtcbiAgICAgIHZhciBjb25maWcgPSB7fTtcbiAgICAgIGNvbmZpZy5jYWxsYmFjayA9ICgoY2FsbGJhY2sgIT09IHVuZGVmaW5lZCAmJiB0eXBlb2YoY2FsbGJhY2spID09PSAnZnVuY3Rpb24nKSA/IGNhbGxiYWNrIDogZmFsc2UpO1xuICAgICAgY29uZmlnLnNlcGFyYXRvciA9ICdzZXBhcmF0b3InIGluIG9wdGlvbnMgPyBvcHRpb25zLnNlcGFyYXRvciA6ICQuY3N2LmRlZmF1bHRzLnNlcGFyYXRvcjtcbiAgICAgIGNvbmZpZy5kZWxpbWl0ZXIgPSAnZGVsaW1pdGVyJyBpbiBvcHRpb25zID8gb3B0aW9ucy5kZWxpbWl0ZXIgOiAkLmNzdi5kZWZhdWx0cy5kZWxpbWl0ZXI7XG4gICAgICB2YXIgc3RhdGUgPSAob3B0aW9ucy5zdGF0ZSAhPT0gdW5kZWZpbmVkID8gb3B0aW9ucy5zdGF0ZSA6IHt9KTtcblxuICAgICAgLy8gc2V0dXBcbiAgICAgIG9wdGlvbnMgPSB7XG4gICAgICAgIGRlbGltaXRlcjogY29uZmlnLmRlbGltaXRlcixcbiAgICAgICAgc2VwYXJhdG9yOiBjb25maWcuc2VwYXJhdG9yLFxuICAgICAgICBvblBhcnNlRW50cnk6IG9wdGlvbnMub25QYXJzZUVudHJ5LFxuICAgICAgICBvblBhcnNlVmFsdWU6IG9wdGlvbnMub25QYXJzZVZhbHVlLFxuICAgICAgICBzdGF0ZTogc3RhdGVcbiAgICAgIH07XG5cbiAgICAgIHZhciBlbnRyeSA9ICQuY3N2LnBhcnNlcnMucGFyc2VFbnRyeShjc3YsIG9wdGlvbnMpO1xuXG4gICAgICAvLyBwdXNoIHRoZSB2YWx1ZSB0byBhIGNhbGxiYWNrIGlmIG9uZSBpcyBkZWZpbmVkXG4gICAgICBpZighY29uZmlnLmNhbGxiYWNrKSB7XG4gICAgICAgIHJldHVybiBlbnRyeTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIGNvbmZpZy5jYWxsYmFjaygnJywgZW50cnkpO1xuICAgICAgfVxuICAgIH0sXG5cbiAgICAvKipcbiAgICAgKiAkLmNzdi50b0FycmF5cyhjc3YpXG4gICAgICogQ29udmVydHMgYSBDU1Ygc3RyaW5nIHRvIGEgamF2YXNjcmlwdCBhcnJheS5cbiAgICAgKlxuICAgICAqIEBwYXJhbSB7U3RyaW5nfSBjc3YgVGhlIHN0cmluZyBjb250YWluaW5nIHRoZSByYXcgQ1NWIGRhdGEuXG4gICAgICogQHBhcmFtIHtPYmplY3R9IFtvcHRpb25zXSBBbiBvYmplY3QgY29udGFpbmluZyB1c2VyLWRlZmluZWQgb3B0aW9ucy5cbiAgICAgKiBAcGFyYW0ge0NoYXJhY3Rlcn0gW3NlcGFyYXRvcl0gQW4gb3ZlcnJpZGUgZm9yIHRoZSBzZXBhcmF0b3IgY2hhcmFjdGVyLiBEZWZhdWx0cyB0byBhIGNvbW1hKCwpLlxuICAgICAqIEBwYXJhbSB7Q2hhcmFjdGVyfSBbZGVsaW1pdGVyXSBBbiBvdmVycmlkZSBmb3IgdGhlIGRlbGltaXRlciBjaGFyYWN0ZXIuIERlZmF1bHRzIHRvIGEgZG91YmxlLXF1b3RlKFwiKS5cbiAgICAgKlxuICAgICAqIFRoaXMgbWV0aG9kIGRlYWxzIHdpdGggbXVsdGktbGluZSBDU1YuIFRoZSBicmVha2Rvd24gaXMgc2ltcGxlLiBUaGUgZmlyc3RcbiAgICAgKiBkaW1lbnNpb24gb2YgdGhlIGFycmF5IHJlcHJlc2VudHMgdGhlIGxpbmUgKG9yIGVudHJ5L3Jvdykgd2hpbGUgdGhlIHNlY29uZFxuICAgICAqIGRpbWVuc2lvbiBjb250YWlucyB0aGUgdmFsdWVzIChvciB2YWx1ZXMvY29sdW1ucykuXG4gICAgICovXG4gICAgdG9BcnJheXM6IGZ1bmN0aW9uKGNzdiwgb3B0aW9ucywgY2FsbGJhY2spIHtcbiAgICAgIG9wdGlvbnMgPSAob3B0aW9ucyAhPT0gdW5kZWZpbmVkID8gb3B0aW9ucyA6IHt9KTtcbiAgICAgIHZhciBjb25maWcgPSB7fTtcbiAgICAgIGNvbmZpZy5jYWxsYmFjayA9ICgoY2FsbGJhY2sgIT09IHVuZGVmaW5lZCAmJiB0eXBlb2YoY2FsbGJhY2spID09PSAnZnVuY3Rpb24nKSA/IGNhbGxiYWNrIDogZmFsc2UpO1xuICAgICAgY29uZmlnLnNlcGFyYXRvciA9ICdzZXBhcmF0b3InIGluIG9wdGlvbnMgPyBvcHRpb25zLnNlcGFyYXRvciA6ICQuY3N2LmRlZmF1bHRzLnNlcGFyYXRvcjtcbiAgICAgIGNvbmZpZy5kZWxpbWl0ZXIgPSAnZGVsaW1pdGVyJyBpbiBvcHRpb25zID8gb3B0aW9ucy5kZWxpbWl0ZXIgOiAkLmNzdi5kZWZhdWx0cy5kZWxpbWl0ZXI7XG5cbiAgICAgIC8vIHNldHVwXG4gICAgICB2YXIgZGF0YSA9IFtdO1xuICAgICAgb3B0aW9ucyA9IHtcbiAgICAgICAgZGVsaW1pdGVyOiBjb25maWcuZGVsaW1pdGVyLFxuICAgICAgICBzZXBhcmF0b3I6IGNvbmZpZy5zZXBhcmF0b3IsXG4gICAgICAgIG9uUHJlUGFyc2U6IG9wdGlvbnMub25QcmVQYXJzZSxcbiAgICAgICAgb25QYXJzZUVudHJ5OiBvcHRpb25zLm9uUGFyc2VFbnRyeSxcbiAgICAgICAgb25QYXJzZVZhbHVlOiBvcHRpb25zLm9uUGFyc2VWYWx1ZSxcbiAgICAgICAgb25Qb3N0UGFyc2U6IG9wdGlvbnMub25Qb3N0UGFyc2UsXG4gICAgICAgIHN0YXJ0OiBvcHRpb25zLnN0YXJ0LFxuICAgICAgICBlbmQ6IG9wdGlvbnMuZW5kLFxuICAgICAgICBzdGF0ZToge1xuICAgICAgICAgIHJvd051bTogMSxcbiAgICAgICAgICBjb2xOdW06IDFcbiAgICAgICAgfVxuICAgICAgfTtcblxuICAgICAgLy8gb25QcmVQYXJzZSBob29rXG4gICAgICBpZihvcHRpb25zLm9uUHJlUGFyc2UgIT09IHVuZGVmaW5lZCkge1xuICAgICAgICBvcHRpb25zLm9uUHJlUGFyc2UoY3N2LCBvcHRpb25zLnN0YXRlKTtcbiAgICAgIH1cblxuICAgICAgLy8gcGFyc2UgdGhlIGRhdGFcbiAgICAgIGRhdGEgPSAkLmNzdi5wYXJzZXJzLnBhcnNlKGNzdiwgb3B0aW9ucyk7XG5cbiAgICAgIC8vIG9uUG9zdFBhcnNlIGhvb2tcbiAgICAgIGlmKG9wdGlvbnMub25Qb3N0UGFyc2UgIT09IHVuZGVmaW5lZCkge1xuICAgICAgICBvcHRpb25zLm9uUG9zdFBhcnNlKGRhdGEsIG9wdGlvbnMuc3RhdGUpO1xuICAgICAgfVxuXG4gICAgICAvLyBwdXNoIHRoZSB2YWx1ZSB0byBhIGNhbGxiYWNrIGlmIG9uZSBpcyBkZWZpbmVkXG4gICAgICBpZighY29uZmlnLmNhbGxiYWNrKSB7XG4gICAgICAgIHJldHVybiBkYXRhO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgY29uZmlnLmNhbGxiYWNrKCcnLCBkYXRhKTtcbiAgICAgIH1cbiAgICB9LFxuXG4gICAgLyoqXG4gICAgICogJC5jc3YudG9PYmplY3RzKGNzdilcbiAgICAgKiBDb252ZXJ0cyBhIENTViBzdHJpbmcgdG8gYSBqYXZhc2NyaXB0IG9iamVjdC5cbiAgICAgKiBAcGFyYW0ge1N0cmluZ30gY3N2IFRoZSBzdHJpbmcgY29udGFpbmluZyB0aGUgcmF3IENTViBkYXRhLlxuICAgICAqIEBwYXJhbSB7T2JqZWN0fSBbb3B0aW9uc10gQW4gb2JqZWN0IGNvbnRhaW5pbmcgdXNlci1kZWZpbmVkIG9wdGlvbnMuXG4gICAgICogQHBhcmFtIHtDaGFyYWN0ZXJ9IFtzZXBhcmF0b3JdIEFuIG92ZXJyaWRlIGZvciB0aGUgc2VwYXJhdG9yIGNoYXJhY3Rlci4gRGVmYXVsdHMgdG8gYSBjb21tYSgsKS5cbiAgICAgKiBAcGFyYW0ge0NoYXJhY3Rlcn0gW2RlbGltaXRlcl0gQW4gb3ZlcnJpZGUgZm9yIHRoZSBkZWxpbWl0ZXIgY2hhcmFjdGVyLiBEZWZhdWx0cyB0byBhIGRvdWJsZS1xdW90ZShcIikuXG4gICAgICogQHBhcmFtIHtCb29sZWFufSBbaGVhZGVyc10gSW5kaWNhdGVzIHdoZXRoZXIgdGhlIGRhdGEgY29udGFpbnMgYSBoZWFkZXIgbGluZS4gRGVmYXVsdHMgdG8gdHJ1ZS5cbiAgICAgKlxuICAgICAqIFRoaXMgbWV0aG9kIGRlYWxzIHdpdGggbXVsdGktbGluZSBDU1Ygc3RyaW5ncy4gV2hlcmUgdGhlIGhlYWRlcnMgbGluZSBpc1xuICAgICAqIHVzZWQgYXMgdGhlIGtleSBmb3IgZWFjaCB2YWx1ZSBwZXIgZW50cnkuXG4gICAgICovXG4gICAgdG9PYmplY3RzOiBmdW5jdGlvbihjc3YsIG9wdGlvbnMsIGNhbGxiYWNrKSB7XG4gICAgICBvcHRpb25zID0gKG9wdGlvbnMgIT09IHVuZGVmaW5lZCA/IG9wdGlvbnMgOiB7fSk7XG4gICAgICB2YXIgY29uZmlnID0ge307XG4gICAgICBjb25maWcuY2FsbGJhY2sgPSAoKGNhbGxiYWNrICE9PSB1bmRlZmluZWQgJiYgdHlwZW9mKGNhbGxiYWNrKSA9PT0gJ2Z1bmN0aW9uJykgPyBjYWxsYmFjayA6IGZhbHNlKTtcbiAgICAgIGNvbmZpZy5zZXBhcmF0b3IgPSAnc2VwYXJhdG9yJyBpbiBvcHRpb25zID8gb3B0aW9ucy5zZXBhcmF0b3IgOiAkLmNzdi5kZWZhdWx0cy5zZXBhcmF0b3I7XG4gICAgICBjb25maWcuZGVsaW1pdGVyID0gJ2RlbGltaXRlcicgaW4gb3B0aW9ucyA/IG9wdGlvbnMuZGVsaW1pdGVyIDogJC5jc3YuZGVmYXVsdHMuZGVsaW1pdGVyO1xuICAgICAgY29uZmlnLmhlYWRlcnMgPSAnaGVhZGVycycgaW4gb3B0aW9ucyA/IG9wdGlvbnMuaGVhZGVycyA6ICQuY3N2LmRlZmF1bHRzLmhlYWRlcnM7XG4gICAgICBvcHRpb25zLnN0YXJ0ID0gJ3N0YXJ0JyBpbiBvcHRpb25zID8gb3B0aW9ucy5zdGFydCA6IDE7XG4gICAgICBcbiAgICAgIC8vIGFjY291bnQgZm9yIGhlYWRlcnNcbiAgICAgIGlmKGNvbmZpZy5oZWFkZXJzKSB7XG4gICAgICAgIG9wdGlvbnMuc3RhcnQrKztcbiAgICAgIH1cbiAgICAgIGlmKG9wdGlvbnMuZW5kICYmIGNvbmZpZy5oZWFkZXJzKSB7XG4gICAgICAgIG9wdGlvbnMuZW5kKys7XG4gICAgICB9XG5cbiAgICAgIC8vIHNldHVwXG4gICAgICB2YXIgbGluZXMgPSBbXTtcbiAgICAgIHZhciBkYXRhID0gW107XG5cbiAgICAgIG9wdGlvbnMgPSB7XG4gICAgICAgIGRlbGltaXRlcjogY29uZmlnLmRlbGltaXRlcixcbiAgICAgICAgc2VwYXJhdG9yOiBjb25maWcuc2VwYXJhdG9yLFxuICAgICAgICBvblByZVBhcnNlOiBvcHRpb25zLm9uUHJlUGFyc2UsXG4gICAgICAgIG9uUGFyc2VFbnRyeTogb3B0aW9ucy5vblBhcnNlRW50cnksXG4gICAgICAgIG9uUGFyc2VWYWx1ZTogb3B0aW9ucy5vblBhcnNlVmFsdWUsXG4gICAgICAgIG9uUG9zdFBhcnNlOiBvcHRpb25zLm9uUG9zdFBhcnNlLFxuICAgICAgICBzdGFydDogb3B0aW9ucy5zdGFydCxcbiAgICAgICAgZW5kOiBvcHRpb25zLmVuZCxcbiAgICAgICAgc3RhdGU6IHtcbiAgICAgICAgICByb3dOdW06IDEsXG4gICAgICAgICAgY29sTnVtOiAxXG4gICAgICAgIH0sXG4gICAgICAgIG1hdGNoOiBmYWxzZSxcbiAgICAgICAgdHJhbnNmb3JtOiBvcHRpb25zLnRyYW5zZm9ybVxuICAgICAgfTtcblxuICAgICAgLy8gZmV0Y2ggdGhlIGhlYWRlcnNcbiAgICAgIHZhciBoZWFkZXJPcHRpb25zID0ge1xuICAgICAgICBkZWxpbWl0ZXI6IGNvbmZpZy5kZWxpbWl0ZXIsXG4gICAgICAgIHNlcGFyYXRvcjogY29uZmlnLnNlcGFyYXRvcixcbiAgICAgICAgc3RhcnQ6IDEsXG4gICAgICAgIGVuZDogMSxcbiAgICAgICAgc3RhdGU6IHtcbiAgICAgICAgICByb3dOdW06MSxcbiAgICAgICAgICBjb2xOdW06MVxuICAgICAgICB9XG4gICAgICB9O1xuXG4gICAgICAvLyBvblByZVBhcnNlIGhvb2tcbiAgICAgIGlmKG9wdGlvbnMub25QcmVQYXJzZSAhPT0gdW5kZWZpbmVkKSB7XG4gICAgICAgIG9wdGlvbnMub25QcmVQYXJzZShjc3YsIG9wdGlvbnMuc3RhdGUpO1xuICAgICAgfVxuXG4gICAgICAvLyBwYXJzZSB0aGUgY3N2XG4gICAgICB2YXIgaGVhZGVyTGluZSA9ICQuY3N2LnBhcnNlcnMuc3BsaXRMaW5lcyhjc3YsIGhlYWRlck9wdGlvbnMpO1xuICAgICAgdmFyIGhlYWRlcnMgPSAkLmNzdi50b0FycmF5KGhlYWRlckxpbmVbMF0sIG9wdGlvbnMpO1xuXG4gICAgICAvLyBmZXRjaCB0aGUgZGF0YVxuICAgICAgbGluZXMgPSAkLmNzdi5wYXJzZXJzLnNwbGl0TGluZXMoY3N2LCBvcHRpb25zKTtcblxuICAgICAgLy8gcmVzZXQgdGhlIHN0YXRlIGZvciByZS11c2VcbiAgICAgIG9wdGlvbnMuc3RhdGUuY29sTnVtID0gMTtcbiAgICAgIGlmKGhlYWRlcnMpe1xuICAgICAgICBvcHRpb25zLnN0YXRlLnJvd051bSA9IDI7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBvcHRpb25zLnN0YXRlLnJvd051bSA9IDE7XG4gICAgICB9XG4gICAgICBcbiAgICAgIC8vIGNvbnZlcnQgZGF0YSB0byBvYmplY3RzXG4gICAgICBmb3IodmFyIGk9MCwgbGVuPWxpbmVzLmxlbmd0aDsgaTxsZW47IGkrKykge1xuICAgICAgICB2YXIgZW50cnkgPSAkLmNzdi50b0FycmF5KGxpbmVzW2ldLCBvcHRpb25zKTtcbiAgICAgICAgdmFyIG9iamVjdCA9IHt9O1xuICAgICAgICBmb3IodmFyIGo9MDsgaiA8aGVhZGVycy5sZW5ndGg7IGorKykge1xuICAgICAgICAgIG9iamVjdFtoZWFkZXJzW2pdXSA9IGVudHJ5W2pdO1xuICAgICAgICB9XG4gICAgICAgIGlmIChvcHRpb25zLnRyYW5zZm9ybSAhPT0gdW5kZWZpbmVkKSB7XG4gICAgICAgICAgZGF0YS5wdXNoKG9wdGlvbnMudHJhbnNmb3JtLmNhbGwodW5kZWZpbmVkLCBvYmplY3QpKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBkYXRhLnB1c2gob2JqZWN0KTtcbiAgICAgICAgfVxuICAgICAgICBcbiAgICAgICAgLy8gdXBkYXRlIHJvdyBzdGF0ZVxuICAgICAgICBvcHRpb25zLnN0YXRlLnJvd051bSsrO1xuICAgICAgfVxuXG4gICAgICAvLyBvblBvc3RQYXJzZSBob29rXG4gICAgICBpZihvcHRpb25zLm9uUG9zdFBhcnNlICE9PSB1bmRlZmluZWQpIHtcbiAgICAgICAgb3B0aW9ucy5vblBvc3RQYXJzZShkYXRhLCBvcHRpb25zLnN0YXRlKTtcbiAgICAgIH1cblxuICAgICAgLy8gcHVzaCB0aGUgdmFsdWUgdG8gYSBjYWxsYmFjayBpZiBvbmUgaXMgZGVmaW5lZFxuICAgICAgaWYoIWNvbmZpZy5jYWxsYmFjaykge1xuICAgICAgICByZXR1cm4gZGF0YTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIGNvbmZpZy5jYWxsYmFjaygnJywgZGF0YSk7XG4gICAgICB9XG4gICAgfSxcblxuICAgICAvKipcbiAgICAgKiAkLmNzdi5mcm9tQXJyYXlzKGFycmF5cylcbiAgICAgKiBDb252ZXJ0cyBhIGphdmFzY3JpcHQgYXJyYXkgdG8gYSBDU1YgU3RyaW5nLlxuICAgICAqXG4gICAgICogQHBhcmFtIHtBcnJheX0gYXJyYXlzIEFuIGFycmF5IGNvbnRhaW5pbmcgYW4gYXJyYXkgb2YgQ1NWIGVudHJpZXMuXG4gICAgICogQHBhcmFtIHtPYmplY3R9IFtvcHRpb25zXSBBbiBvYmplY3QgY29udGFpbmluZyB1c2VyLWRlZmluZWQgb3B0aW9ucy5cbiAgICAgKiBAcGFyYW0ge0NoYXJhY3Rlcn0gW3NlcGFyYXRvcl0gQW4gb3ZlcnJpZGUgZm9yIHRoZSBzZXBhcmF0b3IgY2hhcmFjdGVyLiBEZWZhdWx0cyB0byBhIGNvbW1hKCwpLlxuICAgICAqIEBwYXJhbSB7Q2hhcmFjdGVyfSBbZGVsaW1pdGVyXSBBbiBvdmVycmlkZSBmb3IgdGhlIGRlbGltaXRlciBjaGFyYWN0ZXIuIERlZmF1bHRzIHRvIGEgZG91YmxlLXF1b3RlKFwiKS5cbiAgICAgKlxuICAgICAqIFRoaXMgbWV0aG9kIGdlbmVyYXRlcyBhIENTViBmaWxlIGZyb20gYW4gYXJyYXkgb2YgYXJyYXlzIChyZXByZXNlbnRpbmcgZW50cmllcykuXG4gICAgICovXG4gICAgZnJvbUFycmF5czogZnVuY3Rpb24oYXJyYXlzLCBvcHRpb25zLCBjYWxsYmFjaykge1xuICAgICAgb3B0aW9ucyA9IChvcHRpb25zICE9PSB1bmRlZmluZWQgPyBvcHRpb25zIDoge30pO1xuICAgICAgdmFyIGNvbmZpZyA9IHt9O1xuICAgICAgY29uZmlnLmNhbGxiYWNrID0gKChjYWxsYmFjayAhPT0gdW5kZWZpbmVkICYmIHR5cGVvZihjYWxsYmFjaykgPT09ICdmdW5jdGlvbicpID8gY2FsbGJhY2sgOiBmYWxzZSk7XG4gICAgICBjb25maWcuc2VwYXJhdG9yID0gJ3NlcGFyYXRvcicgaW4gb3B0aW9ucyA/IG9wdGlvbnMuc2VwYXJhdG9yIDogJC5jc3YuZGVmYXVsdHMuc2VwYXJhdG9yO1xuICAgICAgY29uZmlnLmRlbGltaXRlciA9ICdkZWxpbWl0ZXInIGluIG9wdGlvbnMgPyBvcHRpb25zLmRlbGltaXRlciA6ICQuY3N2LmRlZmF1bHRzLmRlbGltaXRlcjtcblxuICAgICAgdmFyIG91dHB1dCA9ICcnLFxuICAgICAgICAgIGxpbmUsXG4gICAgICAgICAgbGluZVZhbHVlcyxcbiAgICAgICAgICBpLCBqO1xuXG4gICAgICBmb3IgKGkgPSAwOyBpIDwgYXJyYXlzLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgIGxpbmUgPSBhcnJheXNbaV07XG4gICAgICAgIGxpbmVWYWx1ZXMgPSBbXTtcbiAgICAgICAgZm9yIChqID0gMDsgaiA8IGxpbmUubGVuZ3RoOyBqKyspIHtcbiAgICAgICAgICB2YXIgc3RyVmFsdWUgPSAobGluZVtqXSA9PT0gdW5kZWZpbmVkIHx8IGxpbmVbal0gPT09IG51bGwpID8gJycgOiBsaW5lW2pdLnRvU3RyaW5nKCk7XG4gICAgICAgICAgaWYgKHN0clZhbHVlLmluZGV4T2YoY29uZmlnLmRlbGltaXRlcikgPiAtMSkge1xuICAgICAgICAgICAgc3RyVmFsdWUgPSBzdHJWYWx1ZS5yZXBsYWNlKG5ldyBSZWdFeHAoY29uZmlnLmRlbGltaXRlciwgJ2cnKSwgY29uZmlnLmRlbGltaXRlciArIGNvbmZpZy5kZWxpbWl0ZXIpO1xuICAgICAgICAgIH1cblxuICAgICAgICAgIHZhciBlc2NNYXRjaGVyID0gJ1xcbnxcXHJ8U3xEJztcbiAgICAgICAgICBlc2NNYXRjaGVyID0gZXNjTWF0Y2hlci5yZXBsYWNlKCdTJywgY29uZmlnLnNlcGFyYXRvcik7XG4gICAgICAgICAgZXNjTWF0Y2hlciA9IGVzY01hdGNoZXIucmVwbGFjZSgnRCcsIGNvbmZpZy5kZWxpbWl0ZXIpO1xuXG4gICAgICAgICAgaWYgKHN0clZhbHVlLnNlYXJjaChlc2NNYXRjaGVyKSA+IC0xKSB7XG4gICAgICAgICAgICBzdHJWYWx1ZSA9IGNvbmZpZy5kZWxpbWl0ZXIgKyBzdHJWYWx1ZSArIGNvbmZpZy5kZWxpbWl0ZXI7XG4gICAgICAgICAgfVxuICAgICAgICAgIGxpbmVWYWx1ZXMucHVzaChzdHJWYWx1ZSk7XG4gICAgICAgIH1cbiAgICAgICAgb3V0cHV0ICs9IGxpbmVWYWx1ZXMuam9pbihjb25maWcuc2VwYXJhdG9yKSArICdcXHJcXG4nO1xuICAgICAgfVxuXG4gICAgICAvLyBwdXNoIHRoZSB2YWx1ZSB0byBhIGNhbGxiYWNrIGlmIG9uZSBpcyBkZWZpbmVkXG4gICAgICBpZighY29uZmlnLmNhbGxiYWNrKSB7XG4gICAgICAgIHJldHVybiBvdXRwdXQ7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBjb25maWcuY2FsbGJhY2soJycsIG91dHB1dCk7XG4gICAgICB9XG4gICAgfSxcblxuICAgIC8qKlxuICAgICAqICQuY3N2LmZyb21PYmplY3RzKG9iamVjdHMpXG4gICAgICogQ29udmVydHMgYSBqYXZhc2NyaXB0IGRpY3Rpb25hcnkgdG8gYSBDU1Ygc3RyaW5nLlxuICAgICAqXG4gICAgICogQHBhcmFtIHtPYmplY3R9IG9iamVjdHMgQW4gYXJyYXkgb2Ygb2JqZWN0cyBjb250YWluaW5nIHRoZSBkYXRhLlxuICAgICAqIEBwYXJhbSB7T2JqZWN0fSBbb3B0aW9uc10gQW4gb2JqZWN0IGNvbnRhaW5pbmcgdXNlci1kZWZpbmVkIG9wdGlvbnMuXG4gICAgICogQHBhcmFtIHtDaGFyYWN0ZXJ9IFtzZXBhcmF0b3JdIEFuIG92ZXJyaWRlIGZvciB0aGUgc2VwYXJhdG9yIGNoYXJhY3Rlci4gRGVmYXVsdHMgdG8gYSBjb21tYSgsKS5cbiAgICAgKiBAcGFyYW0ge0NoYXJhY3Rlcn0gW2RlbGltaXRlcl0gQW4gb3ZlcnJpZGUgZm9yIHRoZSBkZWxpbWl0ZXIgY2hhcmFjdGVyLiBEZWZhdWx0cyB0byBhIGRvdWJsZS1xdW90ZShcIikuXG4gICAgICogQHBhcmFtIHtDaGFyYWN0ZXJ9IFtzb3J0T3JkZXJdIFNvcnQgb3JkZXIgb2YgY29sdW1ucyAobmFtZWQgYWZ0ZXJcbiAgICAgKiAgIG9iamVjdCBwcm9wZXJ0aWVzKS4gVXNlICdhbHBoYScgZm9yIGFscGhhYmV0aWMuIERlZmF1bHQgaXMgJ2RlY2xhcmUnLFxuICAgICAqICAgd2hpY2ggbWVhbnMsIHRoYXQgcHJvcGVydGllcyB3aWxsIF9wcm9iYWJseV8gYXBwZWFyIGluIG9yZGVyIHRoZXkgd2VyZVxuICAgICAqICAgZGVjbGFyZWQgZm9yIHRoZSBvYmplY3QuIEJ1dCB3aXRob3V0IGFueSBndWFyYW50ZWUuXG4gICAgICogQHBhcmFtIHtDaGFyYWN0ZXIgb3IgQXJyYXl9IFttYW51YWxPcmRlcl0gTWFudWFsbHkgb3JkZXIgY29sdW1ucy4gTWF5IGJlXG4gICAgICogYSBzdHJpbiBpbiBhIHNhbWUgY3N2IGZvcm1hdCBhcyBhbiBvdXRwdXQgb3IgYW4gYXJyYXkgb2YgaGVhZGVyIG5hbWVzXG4gICAgICogKGFycmF5IGl0ZW1zIHdvbid0IGJlIHBhcnNlZCkuIEFsbCB0aGUgcHJvcGVydGllcywgbm90IHByZXNlbnQgaW5cbiAgICAgKiBgbWFudWFsT3JkZXJgIHdpbGwgYmUgYXBwZW5kZWQgdG8gdGhlIGVuZCBpbiBhY2NvcmRhbmNlIHdpdGggYHNvcnRPcmRlcmBcbiAgICAgKiBvcHRpb24uIFNvIHRoZSBgbWFudWFsT3JkZXJgIGFsd2F5cyB0YWtlcyBwcmVmZXJlbmNlLCBpZiBwcmVzZW50LlxuICAgICAqXG4gICAgICogVGhpcyBtZXRob2QgZ2VuZXJhdGVzIGEgQ1NWIGZpbGUgZnJvbSBhbiBhcnJheSBvZiBvYmplY3RzIChuYW1lOnZhbHVlIHBhaXJzKS5cbiAgICAgKiBJdCBzdGFydHMgYnkgZGV0ZWN0aW5nIHRoZSBoZWFkZXJzIGFuZCBhZGRpbmcgdGhlbSBhcyB0aGUgZmlyc3QgbGluZSBvZlxuICAgICAqIHRoZSBDU1YgZmlsZSwgZm9sbG93ZWQgYnkgYSBzdHJ1Y3R1cmVkIGR1bXAgb2YgdGhlIGRhdGEuXG4gICAgICovXG4gICAgZnJvbU9iamVjdHM6IGZ1bmN0aW9uKG9iamVjdHMsIG9wdGlvbnMsIGNhbGxiYWNrKSB7XG4gICAgICBvcHRpb25zID0gKG9wdGlvbnMgIT09IHVuZGVmaW5lZCA/IG9wdGlvbnMgOiB7fSk7XG4gICAgICB2YXIgY29uZmlnID0ge307XG4gICAgICBjb25maWcuY2FsbGJhY2sgPSAoKGNhbGxiYWNrICE9PSB1bmRlZmluZWQgJiYgdHlwZW9mKGNhbGxiYWNrKSA9PT0gJ2Z1bmN0aW9uJykgPyBjYWxsYmFjayA6IGZhbHNlKTtcbiAgICAgIGNvbmZpZy5zZXBhcmF0b3IgPSAnc2VwYXJhdG9yJyBpbiBvcHRpb25zID8gb3B0aW9ucy5zZXBhcmF0b3IgOiAkLmNzdi5kZWZhdWx0cy5zZXBhcmF0b3I7XG4gICAgICBjb25maWcuZGVsaW1pdGVyID0gJ2RlbGltaXRlcicgaW4gb3B0aW9ucyA/IG9wdGlvbnMuZGVsaW1pdGVyIDogJC5jc3YuZGVmYXVsdHMuZGVsaW1pdGVyO1xuICAgICAgY29uZmlnLmhlYWRlcnMgPSAnaGVhZGVycycgaW4gb3B0aW9ucyA/IG9wdGlvbnMuaGVhZGVycyA6ICQuY3N2LmRlZmF1bHRzLmhlYWRlcnM7XG4gICAgICBjb25maWcuc29ydE9yZGVyID0gJ3NvcnRPcmRlcicgaW4gb3B0aW9ucyA/IG9wdGlvbnMuc29ydE9yZGVyIDogJ2RlY2xhcmUnO1xuICAgICAgY29uZmlnLm1hbnVhbE9yZGVyID0gJ21hbnVhbE9yZGVyJyBpbiBvcHRpb25zID8gb3B0aW9ucy5tYW51YWxPcmRlciA6IFtdO1xuICAgICAgY29uZmlnLnRyYW5zZm9ybSA9IG9wdGlvbnMudHJhbnNmb3JtO1xuXG4gICAgICBpZiAodHlwZW9mIGNvbmZpZy5tYW51YWxPcmRlciA9PT0gJ3N0cmluZycpIHtcbiAgICAgICAgY29uZmlnLm1hbnVhbE9yZGVyID0gJC5jc3YudG9BcnJheShjb25maWcubWFudWFsT3JkZXIsIGNvbmZpZyk7XG4gICAgICB9XG5cbiAgICAgIGlmIChjb25maWcudHJhbnNmb3JtICE9PSB1bmRlZmluZWQpIHtcbiAgICAgICAgdmFyIG9yaWdPYmplY3RzID0gb2JqZWN0cztcbiAgICAgICAgb2JqZWN0cyA9IFtdO1xuXG4gICAgICAgIHZhciBpO1xuICAgICAgICBmb3IgKGkgPSAwOyBpIDwgb3JpZ09iamVjdHMubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICBvYmplY3RzLnB1c2goY29uZmlnLnRyYW5zZm9ybS5jYWxsKHVuZGVmaW5lZCwgb3JpZ09iamVjdHNbaV0pKTtcbiAgICAgICAgfVxuICAgICAgfVxuXG4gICAgICB2YXIgcHJvcHMgPSAkLmNzdi5oZWxwZXJzLmNvbGxlY3RQcm9wZXJ0eU5hbWVzKG9iamVjdHMpO1xuXG4gICAgICBpZiAoY29uZmlnLnNvcnRPcmRlciA9PT0gJ2FscGhhJykge1xuICAgICAgICBwcm9wcy5zb3J0KCk7XG4gICAgICB9IC8vIGVsc2Uge30gLSBub3RoaW5nIHRvIGRvIGZvciAnZGVjbGFyZScgb3JkZXJcblxuICAgICAgaWYgKGNvbmZpZy5tYW51YWxPcmRlci5sZW5ndGggPiAwKSB7XG5cbiAgICAgICAgdmFyIHByb3BzTWFudWFsID0gW10uY29uY2F0KGNvbmZpZy5tYW51YWxPcmRlcik7XG4gICAgICAgIHZhciBwO1xuICAgICAgICBmb3IgKHAgPSAwOyBwIDwgcHJvcHMubGVuZ3RoOyBwKyspIHtcbiAgICAgICAgICBpZiAocHJvcHNNYW51YWwuaW5kZXhPZiggcHJvcHNbcF0gKSA8IDApIHtcbiAgICAgICAgICAgIHByb3BzTWFudWFsLnB1c2goIHByb3BzW3BdICk7XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIHByb3BzID0gcHJvcHNNYW51YWw7XG4gICAgICB9XG5cbiAgICAgIHZhciBvLCBwLCBsaW5lLCBvdXRwdXQgPSBbXSwgcHJvcE5hbWU7XG4gICAgICBpZiAoY29uZmlnLmhlYWRlcnMpIHtcbiAgICAgICAgb3V0cHV0LnB1c2gocHJvcHMpO1xuICAgICAgfVxuXG4gICAgICBmb3IgKG8gPSAwOyBvIDwgb2JqZWN0cy5sZW5ndGg7IG8rKykge1xuICAgICAgICBsaW5lID0gW107XG4gICAgICAgIGZvciAocCA9IDA7IHAgPCBwcm9wcy5sZW5ndGg7IHArKykge1xuICAgICAgICAgIHByb3BOYW1lID0gcHJvcHNbcF07XG4gICAgICAgICAgaWYgKHByb3BOYW1lIGluIG9iamVjdHNbb10gJiYgdHlwZW9mIG9iamVjdHNbb11bcHJvcE5hbWVdICE9PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgICAgICBsaW5lLnB1c2gob2JqZWN0c1tvXVtwcm9wTmFtZV0pO1xuICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBsaW5lLnB1c2goJycpO1xuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBvdXRwdXQucHVzaChsaW5lKTtcbiAgICAgIH1cblxuICAgICAgLy8gcHVzaCB0aGUgdmFsdWUgdG8gYSBjYWxsYmFjayBpZiBvbmUgaXMgZGVmaW5lZFxuICAgICAgcmV0dXJuICQuY3N2LmZyb21BcnJheXMob3V0cHV0LCBvcHRpb25zLCBjb25maWcuY2FsbGJhY2spO1xuICAgIH1cbiAgfTtcblxuICAvLyBNYWludGVuYW5jZSBjb2RlIHRvIG1haW50YWluIGJhY2t3YXJkLWNvbXBhdGliaWxpdHlcbiAgLy8gV2lsbCBiZSByZW1vdmVkIGluIHJlbGVhc2UgMS4wXG4gICQuY3N2RW50cnkyQXJyYXkgPSAkLmNzdi50b0FycmF5O1xuICAkLmNzdjJBcnJheSA9ICQuY3N2LnRvQXJyYXlzO1xuICAkLmNzdjJEaWN0aW9uYXJ5ID0gJC5jc3YudG9PYmplY3RzO1xuXG4gIC8vIENvbW1vbkpTIG1vZHVsZSBpcyBkZWZpbmVkXG4gIGlmICh0eXBlb2YgbW9kdWxlICE9PSAndW5kZWZpbmVkJyAmJiBtb2R1bGUuZXhwb3J0cykge1xuICAgIG1vZHVsZS5leHBvcnRzID0gJC5jc3Y7XG4gIH1cblxufSkuY2FsbCggdGhpcyApO1xuXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9ub2RlX21vZHVsZXMvanF1ZXJ5LWNzdi9zcmMvanF1ZXJ5LmNzdi5qc1xuLy8gbW9kdWxlIGlkID0gLi9ub2RlX21vZHVsZXMvanF1ZXJ5LWNzdi9zcmMvanF1ZXJ5LmNzdi5qc1xuLy8gbW9kdWxlIGNodW5rcyA9IDMiLCIvKiFcbiAqIGpRdWVyeSBTbW9vdGggU2Nyb2xsIC0gdjIuMi4wIC0gMjAxNy0wNS0wNVxuICogaHR0cHM6Ly9naXRodWIuY29tL2tzd2VkYmVyZy9qcXVlcnktc21vb3RoLXNjcm9sbFxuICogQ29weXJpZ2h0IChjKSAyMDE3IEthcmwgU3dlZGJlcmdcbiAqIExpY2Vuc2VkIE1JVFxuICovXG5cbihmdW5jdGlvbihmYWN0b3J5KSB7XG4gIGlmICh0eXBlb2YgZGVmaW5lID09PSAnZnVuY3Rpb24nICYmIGRlZmluZS5hbWQpIHtcbiAgICAvLyBBTUQuIFJlZ2lzdGVyIGFzIGFuIGFub255bW91cyBtb2R1bGUuXG4gICAgZGVmaW5lKFsnanF1ZXJ5J10sIGZhY3RvcnkpO1xuICB9IGVsc2UgaWYgKHR5cGVvZiBtb2R1bGUgPT09ICdvYmplY3QnICYmIG1vZHVsZS5leHBvcnRzKSB7XG4gICAgLy8gQ29tbW9uSlNcbiAgICBmYWN0b3J5KHJlcXVpcmUoJ2pxdWVyeScpKTtcbiAgfSBlbHNlIHtcbiAgICAvLyBCcm93c2VyIGdsb2JhbHNcbiAgICBmYWN0b3J5KGpRdWVyeSk7XG4gIH1cbn0oZnVuY3Rpb24oJCkge1xuXG4gIHZhciB2ZXJzaW9uID0gJzIuMi4wJztcbiAgdmFyIG9wdGlvbk92ZXJyaWRlcyA9IHt9O1xuICB2YXIgZGVmYXVsdHMgPSB7XG4gICAgZXhjbHVkZTogW10sXG4gICAgZXhjbHVkZVdpdGhpbjogW10sXG4gICAgb2Zmc2V0OiAwLFxuXG4gICAgLy8gb25lIG9mICd0b3AnIG9yICdsZWZ0J1xuICAgIGRpcmVjdGlvbjogJ3RvcCcsXG5cbiAgICAvLyBpZiBzZXQsIGJpbmQgY2xpY2sgZXZlbnRzIHRocm91Z2ggZGVsZWdhdGlvblxuICAgIC8vICBzdXBwb3J0ZWQgc2luY2UgalF1ZXJ5IDEuNC4yXG4gICAgZGVsZWdhdGVTZWxlY3RvcjogbnVsbCxcblxuICAgIC8vIGpRdWVyeSBzZXQgb2YgZWxlbWVudHMgeW91IHdpc2ggdG8gc2Nyb2xsIChmb3IgJC5zbW9vdGhTY3JvbGwpLlxuICAgIC8vICBpZiBudWxsIChkZWZhdWx0KSwgJCgnaHRtbCwgYm9keScpLmZpcnN0U2Nyb2xsYWJsZSgpIGlzIHVzZWQuXG4gICAgc2Nyb2xsRWxlbWVudDogbnVsbCxcblxuICAgIC8vIG9ubHkgdXNlIGlmIHlvdSB3YW50IHRvIG92ZXJyaWRlIGRlZmF1bHQgYmVoYXZpb3JcbiAgICBzY3JvbGxUYXJnZXQ6IG51bGwsXG5cbiAgICAvLyBhdXRvbWF0aWNhbGx5IGZvY3VzIHRoZSB0YXJnZXQgZWxlbWVudCBhZnRlciBzY3JvbGxpbmcgdG8gaXRcbiAgICBhdXRvRm9jdXM6IGZhbHNlLFxuXG4gICAgLy8gZm4ob3B0cykgZnVuY3Rpb24gdG8gYmUgY2FsbGVkIGJlZm9yZSBzY3JvbGxpbmcgb2NjdXJzLlxuICAgIC8vIGB0aGlzYCBpcyB0aGUgZWxlbWVudChzKSBiZWluZyBzY3JvbGxlZFxuICAgIGJlZm9yZVNjcm9sbDogZnVuY3Rpb24oKSB7fSxcblxuICAgIC8vIGZuKG9wdHMpIGZ1bmN0aW9uIHRvIGJlIGNhbGxlZCBhZnRlciBzY3JvbGxpbmcgb2NjdXJzLlxuICAgIC8vIGB0aGlzYCBpcyB0aGUgdHJpZ2dlcmluZyBlbGVtZW50XG4gICAgYWZ0ZXJTY3JvbGw6IGZ1bmN0aW9uKCkge30sXG5cbiAgICAvLyBlYXNpbmcgbmFtZS4galF1ZXJ5IGNvbWVzIHdpdGggXCJzd2luZ1wiIGFuZCBcImxpbmVhci5cIiBGb3Igb3RoZXJzLCB5b3UnbGwgbmVlZCBhbiBlYXNpbmcgcGx1Z2luXG4gICAgLy8gZnJvbSBqUXVlcnkgVUkgb3IgZWxzZXdoZXJlXG4gICAgZWFzaW5nOiAnc3dpbmcnLFxuXG4gICAgLy8gc3BlZWQgY2FuIGJlIGEgbnVtYmVyIG9yICdhdXRvJ1xuICAgIC8vIGlmICdhdXRvJywgdGhlIHNwZWVkIHdpbGwgYmUgY2FsY3VsYXRlZCBiYXNlZCBvbiB0aGUgZm9ybXVsYTpcbiAgICAvLyAoY3VycmVudCBzY3JvbGwgcG9zaXRpb24gLSB0YXJnZXQgc2Nyb2xsIHBvc2l0aW9uKSAvIGF1dG9Db2VmZmljXG4gICAgc3BlZWQ6IDQwMCxcblxuICAgIC8vIGNvZWZmaWNpZW50IGZvciBcImF1dG9cIiBzcGVlZFxuICAgIGF1dG9Db2VmZmljaWVudDogMixcblxuICAgIC8vICQuZm4uc21vb3RoU2Nyb2xsIG9ubHk6IHdoZXRoZXIgdG8gcHJldmVudCB0aGUgZGVmYXVsdCBjbGljayBhY3Rpb25cbiAgICBwcmV2ZW50RGVmYXVsdDogdHJ1ZVxuICB9O1xuXG4gIHZhciBnZXRTY3JvbGxhYmxlID0gZnVuY3Rpb24ob3B0cykge1xuICAgIHZhciBzY3JvbGxhYmxlID0gW107XG4gICAgdmFyIHNjcm9sbGVkID0gZmFsc2U7XG4gICAgdmFyIGRpciA9IG9wdHMuZGlyICYmIG9wdHMuZGlyID09PSAnbGVmdCcgPyAnc2Nyb2xsTGVmdCcgOiAnc2Nyb2xsVG9wJztcblxuICAgIHRoaXMuZWFjaChmdW5jdGlvbigpIHtcbiAgICAgIHZhciBlbCA9ICQodGhpcyk7XG5cbiAgICAgIGlmICh0aGlzID09PSBkb2N1bWVudCB8fCB0aGlzID09PSB3aW5kb3cpIHtcbiAgICAgICAgcmV0dXJuO1xuICAgICAgfVxuXG4gICAgICBpZiAoZG9jdW1lbnQuc2Nyb2xsaW5nRWxlbWVudCAmJiAodGhpcyA9PT0gZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50IHx8IHRoaXMgPT09IGRvY3VtZW50LmJvZHkpKSB7XG4gICAgICAgIHNjcm9sbGFibGUucHVzaChkb2N1bWVudC5zY3JvbGxpbmdFbGVtZW50KTtcblxuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICB9XG5cbiAgICAgIGlmIChlbFtkaXJdKCkgPiAwKSB7XG4gICAgICAgIHNjcm9sbGFibGUucHVzaCh0aGlzKTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIC8vIGlmIHNjcm9sbChUb3B8TGVmdCkgPT09IDAsIG51ZGdlIHRoZSBlbGVtZW50IDFweCBhbmQgc2VlIGlmIGl0IG1vdmVzXG4gICAgICAgIGVsW2Rpcl0oMSk7XG4gICAgICAgIHNjcm9sbGVkID0gZWxbZGlyXSgpID4gMDtcblxuICAgICAgICBpZiAoc2Nyb2xsZWQpIHtcbiAgICAgICAgICBzY3JvbGxhYmxlLnB1c2godGhpcyk7XG4gICAgICAgIH1cbiAgICAgICAgLy8gdGhlbiBwdXQgaXQgYmFjaywgb2YgY291cnNlXG4gICAgICAgIGVsW2Rpcl0oMCk7XG4gICAgICB9XG4gICAgfSk7XG5cbiAgICBpZiAoIXNjcm9sbGFibGUubGVuZ3RoKSB7XG4gICAgICB0aGlzLmVhY2goZnVuY3Rpb24oKSB7XG4gICAgICAgIC8vIElmIG5vIHNjcm9sbGFibGUgZWxlbWVudHMgYW5kIDxodG1sPiBoYXMgc2Nyb2xsLWJlaGF2aW9yOnNtb290aCBiZWNhdXNlXG4gICAgICAgIC8vIFwiV2hlbiB0aGlzIHByb3BlcnR5IGlzIHNwZWNpZmllZCBvbiB0aGUgcm9vdCBlbGVtZW50LCBpdCBhcHBsaWVzIHRvIHRoZSB2aWV3cG9ydCBpbnN0ZWFkLlwiXG4gICAgICAgIC8vIGFuZCBcIlRoZSBzY3JvbGwtYmVoYXZpb3IgcHJvcGVydHkgb2YgdGhlIOKApiBib2R5IGVsZW1lbnQgaXMgKm5vdCogcHJvcGFnYXRlZCB0byB0aGUgdmlld3BvcnQuXCJcbiAgICAgICAgLy8g4oaSIGh0dHBzOi8vZHJhZnRzLmNzc3dnLm9yZy9jc3NvbS12aWV3LyNwcm9wZGVmLXNjcm9sbC1iZWhhdmlvclxuICAgICAgICBpZiAodGhpcyA9PT0gZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50ICYmICQodGhpcykuY3NzKCdzY3JvbGxCZWhhdmlvcicpID09PSAnc21vb3RoJykge1xuICAgICAgICAgIHNjcm9sbGFibGUgPSBbdGhpc107XG4gICAgICAgIH1cblxuICAgICAgICAvLyBJZiBzdGlsbCBubyBzY3JvbGxhYmxlIGVsZW1lbnRzLCBmYWxsIGJhY2sgdG8gPGJvZHk+LFxuICAgICAgICAvLyBpZiBpdCdzIGluIHRoZSBqUXVlcnkgY29sbGVjdGlvblxuICAgICAgICAvLyAoZG9pbmcgdGhpcyBiZWNhdXNlIFNhZmFyaSBzZXRzIHNjcm9sbFRvcCBhc3luYyxcbiAgICAgICAgLy8gc28gY2FuJ3Qgc2V0IGl0IHRvIDEgYW5kIGltbWVkaWF0ZWx5IGdldCB0aGUgdmFsdWUuKVxuICAgICAgICBpZiAoIXNjcm9sbGFibGUubGVuZ3RoICYmIHRoaXMubm9kZU5hbWUgPT09ICdCT0RZJykge1xuICAgICAgICAgIHNjcm9sbGFibGUgPSBbdGhpc107XG4gICAgICAgIH1cbiAgICAgIH0pO1xuICAgIH1cblxuICAgIC8vIFVzZSB0aGUgZmlyc3Qgc2Nyb2xsYWJsZSBlbGVtZW50IGlmIHdlJ3JlIGNhbGxpbmcgZmlyc3RTY3JvbGxhYmxlKClcbiAgICBpZiAob3B0cy5lbCA9PT0gJ2ZpcnN0JyAmJiBzY3JvbGxhYmxlLmxlbmd0aCA+IDEpIHtcbiAgICAgIHNjcm9sbGFibGUgPSBbc2Nyb2xsYWJsZVswXV07XG4gICAgfVxuXG4gICAgcmV0dXJuIHNjcm9sbGFibGU7XG4gIH07XG5cbiAgdmFyIHJSZWxhdGl2ZSA9IC9eKFtcXC1cXCtdPSkoXFxkKykvO1xuXG4gICQuZm4uZXh0ZW5kKHtcbiAgICBzY3JvbGxhYmxlOiBmdW5jdGlvbihkaXIpIHtcbiAgICAgIHZhciBzY3JsID0gZ2V0U2Nyb2xsYWJsZS5jYWxsKHRoaXMsIHtkaXI6IGRpcn0pO1xuXG4gICAgICByZXR1cm4gdGhpcy5wdXNoU3RhY2soc2NybCk7XG4gICAgfSxcbiAgICBmaXJzdFNjcm9sbGFibGU6IGZ1bmN0aW9uKGRpcikge1xuICAgICAgdmFyIHNjcmwgPSBnZXRTY3JvbGxhYmxlLmNhbGwodGhpcywge2VsOiAnZmlyc3QnLCBkaXI6IGRpcn0pO1xuXG4gICAgICByZXR1cm4gdGhpcy5wdXNoU3RhY2soc2NybCk7XG4gICAgfSxcblxuICAgIHNtb290aFNjcm9sbDogZnVuY3Rpb24ob3B0aW9ucywgZXh0cmEpIHtcbiAgICAgIG9wdGlvbnMgPSBvcHRpb25zIHx8IHt9O1xuXG4gICAgICBpZiAob3B0aW9ucyA9PT0gJ29wdGlvbnMnKSB7XG4gICAgICAgIGlmICghZXh0cmEpIHtcbiAgICAgICAgICByZXR1cm4gdGhpcy5maXJzdCgpLmRhdGEoJ3NzT3B0cycpO1xuICAgICAgICB9XG5cbiAgICAgICAgcmV0dXJuIHRoaXMuZWFjaChmdW5jdGlvbigpIHtcbiAgICAgICAgICB2YXIgJHRoaXMgPSAkKHRoaXMpO1xuICAgICAgICAgIHZhciBvcHRzID0gJC5leHRlbmQoJHRoaXMuZGF0YSgnc3NPcHRzJykgfHwge30sIGV4dHJhKTtcblxuICAgICAgICAgICQodGhpcykuZGF0YSgnc3NPcHRzJywgb3B0cyk7XG4gICAgICAgIH0pO1xuICAgICAgfVxuXG4gICAgICB2YXIgb3B0cyA9ICQuZXh0ZW5kKHt9LCAkLmZuLnNtb290aFNjcm9sbC5kZWZhdWx0cywgb3B0aW9ucyk7XG5cbiAgICAgIHZhciBjbGlja0hhbmRsZXIgPSBmdW5jdGlvbihldmVudCkge1xuICAgICAgICB2YXIgZXNjYXBlU2VsZWN0b3IgPSBmdW5jdGlvbihzdHIpIHtcbiAgICAgICAgICByZXR1cm4gc3RyLnJlcGxhY2UoLyg6fFxcLnxcXC8pL2csICdcXFxcJDEnKTtcbiAgICAgICAgfTtcblxuICAgICAgICB2YXIgbGluayA9IHRoaXM7XG4gICAgICAgIHZhciAkbGluayA9ICQodGhpcyk7XG4gICAgICAgIHZhciB0aGlzT3B0cyA9ICQuZXh0ZW5kKHt9LCBvcHRzLCAkbGluay5kYXRhKCdzc09wdHMnKSB8fCB7fSk7XG4gICAgICAgIHZhciBleGNsdWRlID0gb3B0cy5leGNsdWRlO1xuICAgICAgICB2YXIgZXhjbHVkZVdpdGhpbiA9IHRoaXNPcHRzLmV4Y2x1ZGVXaXRoaW47XG4gICAgICAgIHZhciBlbENvdW50ZXIgPSAwO1xuICAgICAgICB2YXIgZXdsQ291bnRlciA9IDA7XG4gICAgICAgIHZhciBpbmNsdWRlID0gdHJ1ZTtcbiAgICAgICAgdmFyIGNsaWNrT3B0cyA9IHt9O1xuICAgICAgICB2YXIgbG9jYXRpb25QYXRoID0gJC5zbW9vdGhTY3JvbGwuZmlsdGVyUGF0aChsb2NhdGlvbi5wYXRobmFtZSk7XG4gICAgICAgIHZhciBsaW5rUGF0aCA9ICQuc21vb3RoU2Nyb2xsLmZpbHRlclBhdGgobGluay5wYXRobmFtZSk7XG4gICAgICAgIHZhciBob3N0TWF0Y2ggPSBsb2NhdGlvbi5ob3N0bmFtZSA9PT0gbGluay5ob3N0bmFtZSB8fCAhbGluay5ob3N0bmFtZTtcbiAgICAgICAgdmFyIHBhdGhNYXRjaCA9IHRoaXNPcHRzLnNjcm9sbFRhcmdldCB8fCAobGlua1BhdGggPT09IGxvY2F0aW9uUGF0aCk7XG4gICAgICAgIHZhciB0aGlzSGFzaCA9IGVzY2FwZVNlbGVjdG9yKGxpbmsuaGFzaCk7XG5cbiAgICAgICAgaWYgKHRoaXNIYXNoICYmICEkKHRoaXNIYXNoKS5sZW5ndGgpIHtcbiAgICAgICAgICBpbmNsdWRlID0gZmFsc2U7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoIXRoaXNPcHRzLnNjcm9sbFRhcmdldCAmJiAoIWhvc3RNYXRjaCB8fCAhcGF0aE1hdGNoIHx8ICF0aGlzSGFzaCkpIHtcbiAgICAgICAgICBpbmNsdWRlID0gZmFsc2U7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgd2hpbGUgKGluY2x1ZGUgJiYgZWxDb3VudGVyIDwgZXhjbHVkZS5sZW5ndGgpIHtcbiAgICAgICAgICAgIGlmICgkbGluay5pcyhlc2NhcGVTZWxlY3RvcihleGNsdWRlW2VsQ291bnRlcisrXSkpKSB7XG4gICAgICAgICAgICAgIGluY2x1ZGUgPSBmYWxzZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9XG5cbiAgICAgICAgICB3aGlsZSAoaW5jbHVkZSAmJiBld2xDb3VudGVyIDwgZXhjbHVkZVdpdGhpbi5sZW5ndGgpIHtcbiAgICAgICAgICAgIGlmICgkbGluay5jbG9zZXN0KGV4Y2x1ZGVXaXRoaW5bZXdsQ291bnRlcisrXSkubGVuZ3RoKSB7XG4gICAgICAgICAgICAgIGluY2x1ZGUgPSBmYWxzZTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoaW5jbHVkZSkge1xuICAgICAgICAgIGlmICh0aGlzT3B0cy5wcmV2ZW50RGVmYXVsdCkge1xuICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICB9XG5cbiAgICAgICAgICAkLmV4dGVuZChjbGlja09wdHMsIHRoaXNPcHRzLCB7XG4gICAgICAgICAgICBzY3JvbGxUYXJnZXQ6IHRoaXNPcHRzLnNjcm9sbFRhcmdldCB8fCB0aGlzSGFzaCxcbiAgICAgICAgICAgIGxpbms6IGxpbmtcbiAgICAgICAgICB9KTtcblxuICAgICAgICAgICQuc21vb3RoU2Nyb2xsKGNsaWNrT3B0cyk7XG4gICAgICAgIH1cbiAgICAgIH07XG5cbiAgICAgIGlmIChvcHRpb25zLmRlbGVnYXRlU2VsZWN0b3IgIT09IG51bGwpIHtcbiAgICAgICAgdGhpc1xuICAgICAgICAub2ZmKCdjbGljay5zbW9vdGhzY3JvbGwnLCBvcHRpb25zLmRlbGVnYXRlU2VsZWN0b3IpXG4gICAgICAgIC5vbignY2xpY2suc21vb3Roc2Nyb2xsJywgb3B0aW9ucy5kZWxlZ2F0ZVNlbGVjdG9yLCBjbGlja0hhbmRsZXIpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgdGhpc1xuICAgICAgICAub2ZmKCdjbGljay5zbW9vdGhzY3JvbGwnKVxuICAgICAgICAub24oJ2NsaWNrLnNtb290aHNjcm9sbCcsIGNsaWNrSGFuZGxlcik7XG4gICAgICB9XG5cbiAgICAgIHJldHVybiB0aGlzO1xuICAgIH1cbiAgfSk7XG5cbiAgdmFyIGdldEV4cGxpY2l0T2Zmc2V0ID0gZnVuY3Rpb24odmFsKSB7XG4gICAgdmFyIGV4cGxpY2l0ID0ge3JlbGF0aXZlOiAnJ307XG4gICAgdmFyIHBhcnRzID0gdHlwZW9mIHZhbCA9PT0gJ3N0cmluZycgJiYgclJlbGF0aXZlLmV4ZWModmFsKTtcblxuICAgIGlmICh0eXBlb2YgdmFsID09PSAnbnVtYmVyJykge1xuICAgICAgZXhwbGljaXQucHggPSB2YWw7XG4gICAgfSBlbHNlIGlmIChwYXJ0cykge1xuICAgICAgZXhwbGljaXQucmVsYXRpdmUgPSBwYXJ0c1sxXTtcbiAgICAgIGV4cGxpY2l0LnB4ID0gcGFyc2VGbG9hdChwYXJ0c1syXSkgfHwgMDtcbiAgICB9XG5cbiAgICByZXR1cm4gZXhwbGljaXQ7XG4gIH07XG5cbiAgdmFyIG9uQWZ0ZXJTY3JvbGwgPSBmdW5jdGlvbihvcHRzKSB7XG4gICAgdmFyICR0Z3QgPSAkKG9wdHMuc2Nyb2xsVGFyZ2V0KTtcblxuICAgIGlmIChvcHRzLmF1dG9Gb2N1cyAmJiAkdGd0Lmxlbmd0aCkge1xuICAgICAgJHRndFswXS5mb2N1cygpO1xuXG4gICAgICBpZiAoISR0Z3QuaXMoZG9jdW1lbnQuYWN0aXZlRWxlbWVudCkpIHtcbiAgICAgICAgJHRndC5wcm9wKHt0YWJJbmRleDogLTF9KTtcbiAgICAgICAgJHRndFswXS5mb2N1cygpO1xuICAgICAgfVxuICAgIH1cblxuICAgIG9wdHMuYWZ0ZXJTY3JvbGwuY2FsbChvcHRzLmxpbmssIG9wdHMpO1xuICB9O1xuXG4gICQuc21vb3RoU2Nyb2xsID0gZnVuY3Rpb24ob3B0aW9ucywgcHgpIHtcbiAgICBpZiAob3B0aW9ucyA9PT0gJ29wdGlvbnMnICYmIHR5cGVvZiBweCA9PT0gJ29iamVjdCcpIHtcbiAgICAgIHJldHVybiAkLmV4dGVuZChvcHRpb25PdmVycmlkZXMsIHB4KTtcbiAgICB9XG4gICAgdmFyIG9wdHMsICRzY3JvbGxlciwgc3BlZWQsIGRlbHRhO1xuICAgIHZhciBleHBsaWNpdE9mZnNldCA9IGdldEV4cGxpY2l0T2Zmc2V0KG9wdGlvbnMpO1xuICAgIHZhciBzY3JvbGxUYXJnZXRPZmZzZXQgPSB7fTtcbiAgICB2YXIgc2Nyb2xsZXJPZmZzZXQgPSAwO1xuICAgIHZhciBvZmZQb3MgPSAnb2Zmc2V0JztcbiAgICB2YXIgc2Nyb2xsRGlyID0gJ3Njcm9sbFRvcCc7XG4gICAgdmFyIGFuaVByb3BzID0ge307XG4gICAgdmFyIGFuaU9wdHMgPSB7fTtcblxuICAgIGlmIChleHBsaWNpdE9mZnNldC5weCkge1xuICAgICAgb3B0cyA9ICQuZXh0ZW5kKHtsaW5rOiBudWxsfSwgJC5mbi5zbW9vdGhTY3JvbGwuZGVmYXVsdHMsIG9wdGlvbk92ZXJyaWRlcyk7XG4gICAgfSBlbHNlIHtcbiAgICAgIG9wdHMgPSAkLmV4dGVuZCh7bGluazogbnVsbH0sICQuZm4uc21vb3RoU2Nyb2xsLmRlZmF1bHRzLCBvcHRpb25zIHx8IHt9LCBvcHRpb25PdmVycmlkZXMpO1xuXG4gICAgICBpZiAob3B0cy5zY3JvbGxFbGVtZW50KSB7XG4gICAgICAgIG9mZlBvcyA9ICdwb3NpdGlvbic7XG5cbiAgICAgICAgaWYgKG9wdHMuc2Nyb2xsRWxlbWVudC5jc3MoJ3Bvc2l0aW9uJykgPT09ICdzdGF0aWMnKSB7XG4gICAgICAgICAgb3B0cy5zY3JvbGxFbGVtZW50LmNzcygncG9zaXRpb24nLCAncmVsYXRpdmUnKTtcbiAgICAgICAgfVxuICAgICAgfVxuXG4gICAgICBpZiAocHgpIHtcbiAgICAgICAgZXhwbGljaXRPZmZzZXQgPSBnZXRFeHBsaWNpdE9mZnNldChweCk7XG4gICAgICB9XG4gICAgfVxuXG4gICAgc2Nyb2xsRGlyID0gb3B0cy5kaXJlY3Rpb24gPT09ICdsZWZ0JyA/ICdzY3JvbGxMZWZ0JyA6IHNjcm9sbERpcjtcblxuICAgIGlmIChvcHRzLnNjcm9sbEVsZW1lbnQpIHtcbiAgICAgICRzY3JvbGxlciA9IG9wdHMuc2Nyb2xsRWxlbWVudDtcblxuICAgICAgaWYgKCFleHBsaWNpdE9mZnNldC5weCAmJiAhKC9eKD86SFRNTHxCT0RZKSQvKS50ZXN0KCRzY3JvbGxlclswXS5ub2RlTmFtZSkpIHtcbiAgICAgICAgc2Nyb2xsZXJPZmZzZXQgPSAkc2Nyb2xsZXJbc2Nyb2xsRGlyXSgpO1xuICAgICAgfVxuICAgIH0gZWxzZSB7XG4gICAgICAkc2Nyb2xsZXIgPSAkKCdodG1sLCBib2R5JykuZmlyc3RTY3JvbGxhYmxlKG9wdHMuZGlyZWN0aW9uKTtcbiAgICB9XG5cbiAgICAvLyBiZWZvcmVTY3JvbGwgY2FsbGJhY2sgZnVuY3Rpb24gbXVzdCBmaXJlIGJlZm9yZSBjYWxjdWxhdGluZyBvZmZzZXRcbiAgICBvcHRzLmJlZm9yZVNjcm9sbC5jYWxsKCRzY3JvbGxlciwgb3B0cyk7XG5cbiAgICBzY3JvbGxUYXJnZXRPZmZzZXQgPSBleHBsaWNpdE9mZnNldC5weCA/IGV4cGxpY2l0T2Zmc2V0IDoge1xuICAgICAgcmVsYXRpdmU6ICcnLFxuICAgICAgcHg6ICgkKG9wdHMuc2Nyb2xsVGFyZ2V0KVtvZmZQb3NdKCkgJiYgJChvcHRzLnNjcm9sbFRhcmdldClbb2ZmUG9zXSgpW29wdHMuZGlyZWN0aW9uXSkgfHwgMFxuICAgIH07XG5cbiAgICBhbmlQcm9wc1tzY3JvbGxEaXJdID0gc2Nyb2xsVGFyZ2V0T2Zmc2V0LnJlbGF0aXZlICsgKHNjcm9sbFRhcmdldE9mZnNldC5weCArIHNjcm9sbGVyT2Zmc2V0ICsgb3B0cy5vZmZzZXQpO1xuXG4gICAgc3BlZWQgPSBvcHRzLnNwZWVkO1xuXG4gICAgLy8gYXV0b21hdGljYWxseSBjYWxjdWxhdGUgdGhlIHNwZWVkIG9mIHRoZSBzY3JvbGwgYmFzZWQgb24gZGlzdGFuY2UgLyBjb2VmZmljaWVudFxuICAgIGlmIChzcGVlZCA9PT0gJ2F1dG8nKSB7XG5cbiAgICAgIC8vICRzY3JvbGxlcltzY3JvbGxEaXJdKCkgaXMgcG9zaXRpb24gYmVmb3JlIHNjcm9sbCwgYW5pUHJvcHNbc2Nyb2xsRGlyXSBpcyBwb3NpdGlvbiBhZnRlclxuICAgICAgLy8gV2hlbiBkZWx0YSBpcyBncmVhdGVyLCBzcGVlZCB3aWxsIGJlIGdyZWF0ZXIuXG4gICAgICBkZWx0YSA9IE1hdGguYWJzKGFuaVByb3BzW3Njcm9sbERpcl0gLSAkc2Nyb2xsZXJbc2Nyb2xsRGlyXSgpKTtcblxuICAgICAgLy8gRGl2aWRlIHRoZSBkZWx0YSBieSB0aGUgY29lZmZpY2llbnRcbiAgICAgIHNwZWVkID0gZGVsdGEgLyBvcHRzLmF1dG9Db2VmZmljaWVudDtcbiAgICB9XG5cbiAgICBhbmlPcHRzID0ge1xuICAgICAgZHVyYXRpb246IHNwZWVkLFxuICAgICAgZWFzaW5nOiBvcHRzLmVhc2luZyxcbiAgICAgIGNvbXBsZXRlOiBmdW5jdGlvbigpIHtcbiAgICAgICAgb25BZnRlclNjcm9sbChvcHRzKTtcbiAgICAgIH1cbiAgICB9O1xuXG4gICAgaWYgKG9wdHMuc3RlcCkge1xuICAgICAgYW5pT3B0cy5zdGVwID0gb3B0cy5zdGVwO1xuICAgIH1cblxuICAgIGlmICgkc2Nyb2xsZXIubGVuZ3RoKSB7XG4gICAgICAkc2Nyb2xsZXIuc3RvcCgpLmFuaW1hdGUoYW5pUHJvcHMsIGFuaU9wdHMpO1xuICAgIH0gZWxzZSB7XG4gICAgICBvbkFmdGVyU2Nyb2xsKG9wdHMpO1xuICAgIH1cbiAgfTtcblxuICAkLnNtb290aFNjcm9sbC52ZXJzaW9uID0gdmVyc2lvbjtcbiAgJC5zbW9vdGhTY3JvbGwuZmlsdGVyUGF0aCA9IGZ1bmN0aW9uKHN0cmluZykge1xuICAgIHN0cmluZyA9IHN0cmluZyB8fCAnJztcblxuICAgIHJldHVybiBzdHJpbmdcbiAgICAgIC5yZXBsYWNlKC9eXFwvLywgJycpXG4gICAgICAucmVwbGFjZSgvKD86aW5kZXh8ZGVmYXVsdCkuW2EtekEtWl17Myw0fSQvLCAnJylcbiAgICAgIC5yZXBsYWNlKC9cXC8kLywgJycpO1xuICB9O1xuXG4gIC8vIGRlZmF1bHQgb3B0aW9uc1xuICAkLmZuLnNtb290aFNjcm9sbC5kZWZhdWx0cyA9IGRlZmF1bHRzO1xuXG59KSk7XG5cblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vbm9kZV9tb2R1bGVzL2pxdWVyeS1zbW9vdGgtc2Nyb2xsL2pxdWVyeS5zbW9vdGgtc2Nyb2xsLmpzXG4vLyBtb2R1bGUgaWQgPSAuL25vZGVfbW9kdWxlcy9qcXVlcnktc21vb3RoLXNjcm9sbC9qcXVlcnkuc21vb3RoLXNjcm9sbC5qc1xuLy8gbW9kdWxlIGNodW5rcyA9IDMgNCIsInJlcXVpcmUoJ2pxdWVyeS1zbW9vdGgtc2Nyb2xsJyk7XHJcblxyXG5leHBvcnRzLmFkZE5ld1BhcnRpY2lwYW50ID0gZnVuY3Rpb24oY29sbGVjdGlvbkhvbGRlciwgZW1haWwsIG5hbWUpIHtcclxuICAgIGFkZE5ld1BhcnRpY2lwYW50KGNvbGxlY3Rpb25Ib2xkZXIsIGVtYWlsLCBuYW1lKTtcclxufTtcclxuXHJcbmZ1bmN0aW9uIGFkZE5ld1BhcnRpY2lwYW50KGNvbGxlY3Rpb25Ib2xkZXIsIGVtYWlsLCBuYW1lKSB7XHJcbiAgICAvLyBHZXQgcGFydGljaXBhbnQgcHJvdG90eXBlIGFzIGRlZmluZWQgaW4gYXR0cmlidXRlIGRhdGEtcHJvdG90eXBlXHJcbiAgICB2YXIgcHJvdG90eXBlID0gY29sbGVjdGlvbkhvbGRlci5hdHRyKCdkYXRhLXByb3RvdHlwZScpO1xyXG4gICAgLy8gQWRqdXN0IHBhcnRpY2lwYW50IHByb3RvdHlwZSBmb3IgY29ycmVjdCBuYW1pbmdcclxuICAgIHZhciBudW1iZXJfb2ZfcGFydGljaXBhbnRzID0gY29sbGVjdGlvbkhvbGRlci5jaGlsZHJlbigpLmxlbmd0aCAtIDE7IC8vIE5vdGUsIG93bmVyIGlzIG5vdCBjb3VudGVkIGFzIHBhcnRpY2lwYW50XHJcbiAgICB2YXIgbmV3Rm9ybUh0bWwgPSBwcm90b3R5cGUucmVwbGFjZSgvX19uYW1lX18vZyxcclxuICAgICAgICBudW1iZXJfb2ZfcGFydGljaXBhbnRzKS5yZXBsYWNlKC9fX3BhcnRpY2lwYW50Y291bnRfXy9nLFxyXG4gICAgICAgIG51bWJlcl9vZl9wYXJ0aWNpcGFudHMgKyAxKTtcclxuICAgIC8vIEFkZCBuZXcgcGFydGljaXBhbnQgdG8gcGFydHkgd2l0aCBhbmltYXRpb25cclxuICAgIHZhciBuZXdGb3JtID0gJChuZXdGb3JtSHRtbCk7XHJcbiAgICBjb2xsZWN0aW9uSG9sZGVyLmFwcGVuZChuZXdGb3JtKTtcclxuXHJcbiAgICBpZiAoICh0eXBlb2YoZW1haWwpIT09J3VuZGVmaW5lZCcpICYmICh0eXBlb2YobmFtZSkhPT0ndW5kZWZpbmVkJykgKSB7XHJcbiAgICAgICAgLy8gZW1haWwgYW5kIG5hbWUgcHJvdmlkZWQsIGZpbGwgaW4gdGhlIGJsYW5rc1xyXG4gICAgICAgICQobmV3Rm9ybSkuZmluZCgnLnBhcnRpY2lwYW50LW1haWwnKS5hdHRyKCd2YWx1ZScsIGVtYWlsKTtcclxuICAgICAgICAkKG5ld0Zvcm0pLmZpbmQoJy5wYXJ0aWNpcGFudC1uYW1lJykuYXR0cigndmFsdWUnLCBuYW1lKTtcclxuICAgICAgICBuZXdGb3JtLnNob3coKTtcclxuICAgIH0gZWxzZSB7XHJcbiAgICAgICAgbmV3Rm9ybS5zaG93KDMwMCk7XHJcbiAgICB9XHJcblxyXG4gICAgLy8gSGFuZGxlIGRlbGV0ZSBidXR0b24gZXZlbnRzXHJcbiAgICBiaW5kRGVsZXRlQnV0dG9uRXZlbnRzKCk7XHJcbiAgICAvLyBSZW1vdmUgZGlzYWJsZWQgc3RhdGUgb24gZGVsZXRlLWJ1dHRvbnNcclxuICAgICQoJy5yZW1vdmUtcGFydGljaXBhbnQnKS5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcclxufVxyXG5mdW5jdGlvbiBiaW5kRGVsZXRlQnV0dG9uRXZlbnRzKCkge1xyXG4gICAgLy8gTG9vcCBvdmVyIGFsbCBkZWxldGUgYnV0dG9uc1xyXG4gICAgJCgnYnV0dG9uLnJlbW92ZS1wYXJ0aWNpcGFudCcpLmVhY2goZnVuY3Rpb24gKGkpIHtcclxuICAgICAgICAvLyBSZW1vdmUgYW55IHByZXZpb3VzbHkgYmluZGVkIGV2ZW50XHJcbiAgICAgICAgJCh0aGlzKS5vZmYoJ2NsaWNrJyk7XHJcbiAgICAgICAgLy8gQmluZCBldmVudFxyXG4gICAgICAgICQodGhpcykuY2xpY2soZnVuY3Rpb24gKGUpIHtcclxuICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgICAgICAkKCd0YWJsZSB0ci5wYXJ0aWNpcGFudC5ub3Qtb3duZXI6Z3QoJyArIGkgKyAnKScpLmVhY2goZnVuY3Rpb24gKGopIHtcclxuICAgICAgICAgICAgICAgIC8vIE1vdmUgdmFsdWVzIGZyb20gbmV4dCByb3cgdG8gY3VycmVudCByb3dcclxuICAgICAgICAgICAgICAgIHZhciBuZXh0X3Jvd19uYW1lID0gJCgndGFibGUgdHIucGFydGljaXBhbnQubm90LW93bmVyOmVxKCcgKyAoaSArIGogKyAxKSArICcpIGlucHV0LnBhcnRpY2lwYW50LW5hbWUnKS52YWwoKTtcclxuICAgICAgICAgICAgICAgIHZhciBuZXh0X3Jvd19tYWlsID0gJCgndGFibGUgdHIucGFydGljaXBhbnQubm90LW93bmVyOmVxKCcgKyAoaSArIGogKyAxKSArICcpIGlucHV0LnBhcnRpY2lwYW50LW1haWwnKS52YWwoKTtcclxuICAgICAgICAgICAgICAgICQoJ3RhYmxlIHRyLnBhcnRpY2lwYW50Lm5vdC1vd25lcjplcSgnICsgKGkgKyBqKSArICcpIGlucHV0LnBhcnRpY2lwYW50LW5hbWUnKS52YWwobmV4dF9yb3dfbmFtZSk7XHJcbiAgICAgICAgICAgICAgICAkKCd0YWJsZSB0ci5wYXJ0aWNpcGFudC5ub3Qtb3duZXI6ZXEoJyArIChpICsgaikgKyAnKSBpbnB1dC5wYXJ0aWNpcGFudC1tYWlsJykudmFsKG5leHRfcm93X21haWwpO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgLy8gRGVsZXRlIGxhc3Qgcm93XHJcbiAgICAgICAgICAgICQoJ3RhYmxlIHRyLnBhcnRpY2lwYW50Lm5vdC1vd25lcjpsYXN0JykucmVtb3ZlKCk7XHJcbiAgICAgICAgICAgIC8vIFJlbW92ZSBkZWxldGUgZXZlbnRzIHdoZW4gZGVsZXRhYmxlIHBhcnRpY2lwYW50cyA8IDNcclxuICAgICAgICAgICAgaWYgKCQoJ3RhYmxlIHRyLnBhcnRpY2lwYW50Lm5vdC1vd25lcicpLmxlbmd0aCA8IDMpIHtcclxuICAgICAgICAgICAgICAgICQoJ3RhYmxlIHRyLnBhcnRpY2lwYW50Lm5vdC1vd25lciBidXR0b24ucmVtb3ZlLXBhcnRpY2lwYW50JykuYWRkQ2xhc3MoJ2Rpc2FibGVkJyk7XHJcbiAgICAgICAgICAgICAgICAkKCd0YWJsZSB0ci5wYXJ0aWNpcGFudC5ub3Qtb3duZXIgYnV0dG9uLnJlbW92ZS1wYXJ0aWNpcGFudCcpLm9mZignY2xpY2snKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0pO1xyXG4gICAgfSk7XHJcbn1cclxuLyogVmFyaWFibGVzICovXHJcbnZhciBjb2xsZWN0aW9uSG9sZGVyID0gJCgndGFibGUucGFydGljaXBhbnRzIHRib2R5Jyk7XHJcbi8qIERvY3VtZW50IFJlYWR5ICovXHJcbmpRdWVyeShkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24gKCkge1xyXG4gICAgLy9BZGQgZXZlbnRsaXN0ZW5lciBvbiBhZGQtbmV3LXBhcnRpY2lwYW50IGJ1dHRvblxyXG4gICAgJCgnLmFkZC1uZXctcGFydGljaXBhbnQnKS5jbGljayhmdW5jdGlvbiAoZSkge1xyXG4gICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICBhZGROZXdQYXJ0aWNpcGFudChjb2xsZWN0aW9uSG9sZGVyKTtcclxuICAgIH0pO1xyXG4gICAgLy8gSWYgZm9ybSBoYXMgbW9yZSB0aGVuIDMgcGFydGljaXBhbnRzLCBwcm92aWRlIGRlbGV0ZSBmdW5jdGlvbmFsaXR5XHJcbiAgICBpZiAoJCgndGFibGUgdHIucGFydGljaXBhbnQnKS5sZW5ndGggPiAzKSB7XHJcbiAgICAgICAgYmluZERlbGV0ZUJ1dHRvbkV2ZW50cygpO1xyXG4gICAgICAgICQoJy5yZW1vdmUtcGFydGljaXBhbnQnKS5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcclxuICAgIH1cclxuICAgIC8vIEFkZCBzbW9vdGggc2Nyb2xsXHJcbiAgICAkKCdhLmJ0bi1zdGFydGVkJykuY2xpY2soZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICQuc21vb3RoU2Nyb2xsKHtcclxuICAgICAgICAgICAgc2Nyb2xsVGFyZ2V0OiAnI215c2FudGEnXHJcbiAgICAgICAgfSk7XHJcbiAgICAgICAgcmV0dXJuIGZhbHNlO1xyXG4gICAgfSk7XHJcbn0pO1xyXG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9zcmMvSW50cmFjdG8vU2VjcmV0U2FudGFCdW5kbGUvUmVzb3VyY2VzL3B1YmxpYy9qcy9wYXJ0eS5jcmVhdGUuanMiLCJyZXF1aXJlKCdqcXVlcnktY3N2Jyk7XG52YXIgY3JlYXRlTW9kdWxlID0gcmVxdWlyZSgnLi9wYXJ0eS5jcmVhdGUnKTtcblxuLyogVmFyaWFibGVzICovXG52YXIgY29sbGVjdGlvbkhvbGRlciA9ICQoJ3RhYmxlLnBhcnRpY2lwYW50cyB0Ym9keScpO1xudmFyIGRyb3BJbXBvcnRDU1YgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnaW1wb3J0Q1NWJyk7XG52YXIgZXJyb3JJbXBvcnRDU1YgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZXJyb3JJbXBvcnRDU1YnKTtcbnZhciB3YXJuaW5nSW1wb3J0Q1NWID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3dhcm5pbmdJbXBvcnRDU1YnKTtcblxuLyogRG9jdW1lbnQgUmVhZHkgKi9cbmpRdWVyeShkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24gKCkge1xuXG4gICAgLy9BZGQgZXZlbnRsaXN0ZW5lciBvbiBhZGQtbmV3LXBhcnRpY2lwYW50IGJ1dHRvblxuICAgICQoJy5hZGQtaW1wb3J0LXBhcnRpY2lwYW50JykuY2xpY2soZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAkKCcucm93LWltcG9ydC1wYXJ0aWNpcGFudHMnKS5zaG93KDMwMCk7XG4gICAgfSk7XG5cbiAgICAkKCcuYnRuLWltcG9ydC1jYW5jZWwnKS5jbGljayhmdW5jdGlvbiAoZSkge1xuICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICQoJyNpbXBvcnRDU1YnKS52YWwoJycpO1xuICAgICAgICAkKCcjZXJyb3JJbXBvcnRDU1YnKS5oaWRlKCk7XG4gICAgICAgICQoJyN3YXJuaW5nSW1wb3J0Q1NWJykuaGlkZSgpO1xuICAgICAgICAkKCcucm93LWltcG9ydC1wYXJ0aWNpcGFudHMnKS5oaWRlKDMwMCk7XG4gICAgfSk7XG5cbiAgICAkKCcuYWRkLWltcG9ydC1wYXJ0aWNpcGFudC1kbycpLmNsaWNrKGZ1bmN0aW9uIChlKSB7XG4gICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcblxuICAgICAgICB2YXIgcGFydGljaXBhbnRzID0gJC5jc3YudG9BcnJheXMoJCgnLmFkZC1pbXBvcnQtcGFydGljaXBhbnQtZGF0YScpLnZhbCgpLCB7XG4gICAgICAgICAgICBoZWFkZXJzOiBmYWxzZSxcbiAgICAgICAgICAgIHNlcGVyYXRvcjogJywnLFxuICAgICAgICAgICAgZGVsaW1pdGVyOiAnXCInXG4gICAgICAgIH0pO1xuXG4gICAgICAgIGlmICh0eXBlb2YocGFydGljaXBhbnRzWzBdKSA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChwYXJ0aWNpcGFudHNbMF1bMV0uaW5kZXhPZignQCcpID09IC0xKSB7XG4gICAgICAgICAgICBwYXJ0aWNpcGFudHMuc3BsaWNlKDAsIDEpO1xuICAgICAgICB9XG5cbiAgICAgICAgdmFyIGFkZGVkID0gMDtcbiAgICAgICAgdmFyIGxvb2tGb3JFbXB0eSA9IHRydWU7XG4gICAgICAgIGZvciAodmFyIHBhcnRpY2lwYW50IGluIHBhcnRpY2lwYW50cykge1xuXG4gICAgICAgICAgICB2YXIgZW1haWwgPSAnJztcbiAgICAgICAgICAgIHZhciBuYW1lID0gJyc7XG5cbiAgICAgICAgICAgIGZvciAodmFyIGZpZWxkIGluIHBhcnRpY2lwYW50c1twYXJ0aWNpcGFudF0pIHtcbiAgICAgICAgICAgICAgICAvLyB2ZXJ5IGJhc2ljIGNoZWNrLCBjYW4vc2hvdWxkIHByb2JhYmx5IGJlIGRvbmUgc29tZSBvdGhlciB3YXlcbiAgICAgICAgICAgICAgICAvLyBjaGVjayBpZiB0aGlzIGlzIGFuIGUtbWFpbGFkZHJlc3NcbiAgICAgICAgICAgICAgICBpZiAoZW1haWwgPT0gJycgJiYgcGFydGljaXBhbnRzW3BhcnRpY2lwYW50XVtmaWVsZF0uaW5kZXhPZignQCcpICE9IC0xKSB7XG4gICAgICAgICAgICAgICAgICAgIGVtYWlsID0gcGFydGljaXBhbnRzW3BhcnRpY2lwYW50XVtmaWVsZF07XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgLy8gZWl0aGVyIGUtbWFpbCBhbHJlYWR5IGZvdW5kLCBvciBubyBAIHNpZ24gZm91bmRcbiAgICAgICAgICAgICAgICAgICAgbmFtZSA9IHBhcnRpY2lwYW50c1twYXJ0aWNpcGFudF1bZmllbGRdO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgaWYgKGVtYWlsICE9ICcnKSB7XG4gICAgICAgICAgICAgICAgaWYgKG5hbWUgPT0gJycpIG5hbWUgPSBlbWFpbDtcblxuICAgICAgICAgICAgICAgIC8vIGNoZWNrIHRvIHNlZSBpZiBsaXN0IGNvbnRhaW5zIGVtcHR5IHBhcnRpY2lwYW50c1xuICAgICAgICAgICAgICAgIGlmIChsb29rRm9yRW1wdHkpIHtcbiAgICAgICAgICAgICAgICAgICAgLy8gaWYgc28sIHVzZSB0aGVtLCBvdGhlcndpc2UgYWRkIG5ld1xuICAgICAgICAgICAgICAgICAgICBlbGVtID0gJChjb2xsZWN0aW9uSG9sZGVyKS5maW5kKCcucGFydGljaXBhbnQtbmFtZVt2YWx1ZT1cIlwiXSwucGFydGljaXBhbnQtbmFtZTpub3QoW3ZhbHVlXSknKTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKGVsZW0ubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgcm93ID0gJChlbGVtWzBdKS5wYXJlbnQoKS5wYXJlbnQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICQocm93KS5maW5kKCcucGFydGljaXBhbnQtbmFtZScpLmF0dHIoJ3ZhbHVlJywgbmFtZSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAkKHJvdykuZmluZCgnLnBhcnRpY2lwYW50LW1haWwnKS5hdHRyKCd2YWx1ZScsIGVtYWlsKTtcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vIHByZXZlbnQgbG9va3VwIG9uIG5leHQgaXRlcmF0aW9uXG4gICAgICAgICAgICAgICAgICAgICAgICBsb29rRm9yRW1wdHkgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNyZWF0ZU1vZHVsZS5hZGROZXdQYXJ0aWNpcGFudChjb2xsZWN0aW9uSG9sZGVyLCBlbWFpbCwgbmFtZSk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBjcmVhdGVNb2R1bGUuYWRkTmV3UGFydGljaXBhbnQoY29sbGVjdGlvbkhvbGRlciwgZW1haWwsIG5hbWUpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBhZGRlZCsrO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgIH1cblxuICAgICAgICBpZiAoYWRkZWQgPiAwKSB7XG4gICAgICAgICAgICAkKCcuYWRkLWltcG9ydC1wYXJ0aWNpcGFudC1kYXRhJykudmFsKCcnKTtcbiAgICAgICAgICAgICQoJy5yb3ctaW1wb3J0LXBhcnRpY2lwYW50cycpLmhpZGUoMzAwKTtcbiAgICAgICAgfVxuXG4gICAgfSk7XG5cbiAgICAkKCcuYWRkLWltcG9ydC1wYXJ0aWNpcGFudC1kYXRhJykuY2hhbmdlKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgLy8gcmVwbGFjZSB0YWIgYW5kIDsgZGVsaW1pdGVyIHdpdGggLFxuICAgICAgICBkYXRhID0gJCh0aGlzKS52YWwoKS5yZXBsYWNlKC9cXHQvZywgXCIsXCIpLnJlcGxhY2UoLzsvZywgXCIsXCIpO1xuICAgICAgICBpZiAoZGF0YSAhPSAkKHRoaXMpLnRleHQoKSkge1xuICAgICAgICAgICAgJCh0aGlzKS52YWwoZGF0YSk7XG4gICAgICAgIH1cbiAgICB9KTtcbn0pO1xuXG5kcm9wSW1wb3J0Q1NWLmFkZEV2ZW50TGlzdGVuZXIoJ2RyYWdlbnRlcicsIGZ1bmN0aW9uIChlKSB7XG4gICAgZS5zdG9wUHJvcGFnYXRpb24oZSk7XG4gICAgZS5wcmV2ZW50RGVmYXVsdChlKTtcbn0pO1xuXG5kcm9wSW1wb3J0Q1NWLmFkZEV2ZW50TGlzdGVuZXIoJ2RyYWdvdmVyJywgZnVuY3Rpb24gKGUpIHtcbiAgICBlLnN0b3BQcm9wYWdhdGlvbihlKTtcbiAgICBlLnByZXZlbnREZWZhdWx0KGUpO1xuXG4gICAgcmV0dXJuIGZhbHNlO1xufSk7XG5cbmRyb3BJbXBvcnRDU1YuYWRkRXZlbnRMaXN0ZW5lcignZHJvcCcsIGltcG9ydENTViwgZmFsc2UpO1xuXG5mdW5jdGlvbiBpbXBvcnRDU1YoZSkge1xuICAgIGUuc3RvcFByb3BhZ2F0aW9uKGUpO1xuICAgIGUucHJldmVudERlZmF1bHQoZSk7XG5cbiAgICB2YXIgZmlsZXMgPSBlLmRhdGFUcmFuc2Zlci5maWxlcztcbiAgICB2YXIgbnVtYmVyID0gZmlsZXMubGVuZ3RoO1xuXG4gICAgc3dpdGNoIChudW1iZXIpIHtcbiAgICAgICAgY2FzZSAxOlxuICAgICAgICAgICAgcGFyc2VGaWxlcyhmaWxlcyk7XG4gICAgICAgICAgICB3YXJuaW5nSW1wb3J0Q1NWLnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7XG4gICAgICAgICAgICBicmVhaztcblxuICAgICAgICBkZWZhdWx0OlxuICAgICAgICAgICAgd2FybmluZ0ltcG9ydENTVi5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcbiAgICAgICAgICAgIGJyZWFrO1xuICAgIH1cbn1cblxuZnVuY3Rpb24gcGFyc2VGaWxlcyhmaWxlcykge1xuICAgIHZhciBmaWxlID0gZmlsZXNbMF07XG4gICAgdmFyIGZpbGVOYW1lID0gZmlsZVsnbmFtZSddO1xuICAgIHZhciBmaWxlRXh0ZW5zaW9uID0gZmlsZU5hbWUucmVwbGFjZSgvXi4qXFwuLywgJycpO1xuXG4gICAgc3dpdGNoIChmaWxlRXh0ZW5zaW9uKSB7XG4gICAgICAgIGNhc2UgJ2Nzdic6XG4gICAgICAgIGNhc2UgJ3R4dCc6XG4gICAgICAgICAgICBlcnJvckltcG9ydENTVi5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuXG4gICAgICAgICAgICB2YXIgcmVhZGVyID0gbmV3IEZpbGVSZWFkZXIoKTtcblxuICAgICAgICAgICAgcmVhZGVyLnJlYWRBc1RleHQoZmlsZSwgJ1VURi04Jyk7XG4gICAgICAgICAgICByZWFkZXIub25sb2FkID0gaGFuZGxlUmVhZGVyTG9hZDtcbiAgICAgICAgICAgIGJyZWFrO1xuXG4gICAgICAgIGRlZmF1bHQ6XG4gICAgICAgICAgICBlcnJvckltcG9ydENTVi5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcbiAgICAgICAgICAgIGJyZWFrO1xuICAgIH1cbn1cblxuZnVuY3Rpb24gaGFuZGxlUmVhZGVyTG9hZChlKSB7XG4gICAgdmFyIGNzdiA9IGUudGFyZ2V0LnJlc3VsdDtcblxuICAgIGRyb3BJbXBvcnRDU1YudmFsdWUgPSBjc3Yuc3BsaXQoJzsnKTtcbn1cblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL3NyYy9JbnRyYWN0by9TZWNyZXRTYW50YUJ1bmRsZS9SZXNvdXJjZXMvcHVibGljL2pzL3BhcnR5LmltcG9ydC5qcyJdLCJzb3VyY2VSb290IjoiIn0=