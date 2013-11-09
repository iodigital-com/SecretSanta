/*! Smooth Scroll - v1.4.7 - 2012-10-29
* Copyright (c) 2012 Karl Swedberg; Licensed MIT, GPL */
(function(a){function f(a){return a.replace(/(:|\.)/g,"\\$1")}var b="1.4.7",c={exclude:[],excludeWithin:[],offset:0,direction:"top",scrollElement:null,scrollTarget:null,beforeScroll:function(){},afterScroll:function(){},easing:"swing",speed:400,autoCoefficent:2},d=function(b){var c=[],d=!1,e=b.dir&&b.dir=="left"?"scrollLeft":"scrollTop";return this.each(function(){if(this==document||this==window)return;var b=a(this);b[e]()>0?c.push(this):(b[e](1),d=b[e]()>0,d&&c.push(this),b[e](0))}),c.length||this.each(function(a){this.nodeName==="BODY"&&(c=[this])}),b.el==="first"&&c.length>1&&(c=[c[0]]),c},e="ontouchend"in document;a.fn.extend({scrollable:function(a){var b=d.call(this,{dir:a});return this.pushStack(b)},firstScrollable:function(a){var b=d.call(this,{el:"first",dir:a});return this.pushStack(b)},smoothScroll:function(b){b=b||{};var c=a.extend({},a.fn.smoothScroll.defaults,b),d=a.smoothScroll.filterPath(location.pathname);return this.unbind("click.smoothscroll").bind("click.smoothscroll",function(b){var e=this,g=a(this),h=c.exclude,i=c.excludeWithin,j=0,k=0,l=!0,m={},n=location.hostname===e.hostname||!e.hostname,o=c.scrollTarget||(a.smoothScroll.filterPath(e.pathname)||d)===d,p=f(e.hash);if(!c.scrollTarget&&(!n||!o||!p))l=!1;else{while(l&&j<h.length)g.is(f(h[j++]))&&(l=!1);while(l&&k<i.length)g.closest(i[k++]).length&&(l=!1)}l&&(b.preventDefault(),a.extend(m,c,{scrollTarget:c.scrollTarget||p,link:e}),a.smoothScroll(m))}),this}}),a.smoothScroll=function(b,c){var d,e,f,g,h=0,i="offset",j="scrollTop",k={},l={},m=[];typeof b=="number"?(d=a.fn.smoothScroll.defaults,f=b):(d=a.extend({},a.fn.smoothScroll.defaults,b||{}),d.scrollElement&&(i="position",d.scrollElement.css("position")=="static"&&d.scrollElement.css("position","relative"))),d=a.extend({link:null},d),j=d.direction=="left"?"scrollLeft":j,d.scrollElement?(e=d.scrollElement,h=e[j]()):e=a("html, body").firstScrollable(),d.beforeScroll.call(e,d),f=typeof b=="number"?b:c||a(d.scrollTarget)[i]()&&a(d.scrollTarget)[i]()[d.direction]||0,k[j]=f+h+d.offset,g=d.speed,g==="auto"&&(g=k[j]||e.scrollTop(),g=g/d.autoCoefficent),l={duration:g,easing:d.easing,complete:function(){d.afterScroll.call(d.link,d)}},d.step&&(l.step=d.step),e.length?e.stop().animate(k,l):d.afterScroll.call(d.link,d)},a.smoothScroll.version=b,a.smoothScroll.filterPath=function(a){return a.replace(/^\//,"").replace(/(index|default).[a-zA-Z]{3,4}$/,"").replace(/\/$/,"")},a.fn.smoothScroll.defaults=c})(jQuery);
function addNewEntry(collectionHolder) {
    // Get entry prototype as defined in attribute data-prototype
    var prototype = collectionHolder.attr('data-prototype');
    // Adjust entry prototype for correct naming
    var number_of_entries = collectionHolder.children().length; // Note, owner is not counted as entry
    var newFormHtml = prototype.replace(/__name__/g, number_of_entries).replace(/__entrycount__/g, number_of_entries + 1);

    // Add new entry to pool with animation
    var newForm = $(newFormHtml);
    collectionHolder.append(newForm);
    newForm.show(300);

    // Handle delete button events
    bindDeleteButtonEvents();

    // Remove disabled state on delete-buttons
    $('.remove-entry').removeClass('disabled');
}

function bindDeleteButtonEvents() {
    // Loop over all delete buttons
    $('button.remove-entry').each(function(i) {
        // Remove any previously binded event
        $(this).off('click');

        // Bind event
        $(this).click(function(e) {

            e.preventDefault();

            $('table tr.entry.not-owner:gt(' + i + ')').each(function(j) {
                // Move values from next row to current row
                var next_row_name = $('table tr.entry.not-owner:eq(' + (i + j + 1) + ') input.entry-name').val();
                var next_row_mail = $('table tr.entry.not-owner:eq(' + (i + j + 1) + ') input.entry-mail').val();
                $('table tr.entry.not-owner:eq(' + (i + j) + ') input.entry-name').val(next_row_name);
                $('table tr.entry.not-owner:eq(' + (i + j) + ') input.entry-mail').val(next_row_mail);
            });

            // Delete last row
            $('table tr.entry.not-owner:last').remove();

            // Remove delete events when deletable entries < 3
            if ($('table tr.entry.not-owner').length < 3) {
                $('table tr.entry.not-owner button.remove-entry').addClass('disabled');
                $('table tr.entry.not-owner button.remove-entry').off('click');

            }
        });

    });
}

/* Variables */
var collectionHolder = $('table.entries tbody');

/* Document Ready */
jQuery(document).ready(function() {

    //Add eventlistener on add-new-entry button
    $('.add-new-entry').click(function(e) {
        e.preventDefault();
        addNewEntry(collectionHolder);
    });

    // If form has more then 3 entries, provide delete functionality
    if($('table tr.entry').length > 3){
        bindDeleteButtonEvents();
        $('.remove-entry').removeClass('disabled');
    }

    // Add smooth scroll
    $('a.btn-started').click(function() {
        $.smoothScroll({
            scrollTarget: '#mysanta'
        });
        return false;
    });

});