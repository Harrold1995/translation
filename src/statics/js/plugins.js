(function($) { // work as an alias for jQuery()

// Avoid `console` errors in browsers that lack a console.

(function() {

    var method;

    var noop = function () {};

    var methods = [

        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',

        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',

        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',

        'timeline', 'timelineEnd', 'timeStamp', 'trace', 'warn'

    ];

    var length = methods.length;

    var console = (window.console = window.console || {});



    while (length--) {

        method = methods[length];



        // Only stub undefined methods.

        if (!console[method]) {

            console[method] = noop;

        }

    }

}());



// Place any jQuery/helper plugins in here.



/*

selectivizr v1.0.2 - (c) Keith Clark, freely distributable under the terms 

of the MIT license.



selectivizr.com

*/

/* 

  

Notes about this source

-----------------------



 * The #DEBUG_START and #DEBUG_END comments are used to mark blocks of code

   that will be removed prior to building a final release version (using a

   pre-compression script)

  

  

References:

-----------

 

 * CSS Syntax          : http://www.w3.org/TR/2003/WD-css3-syntax-20030813/#style

 * Selectors           : http://www.w3.org/TR/css3-selectors/#selectors

 * IE Compatability    : http://msdn.microsoft.com/en-us/library/cc351024(VS.85).aspx

 * W3C Selector Tests  : http://www.w3.org/Style/CSS/Test/CSS3/Selectors/current/html/tests/

 */

 

(function(win){if(true)return;var doc=document;var root=doc.documentElement;var xhr=getXHRObject();var ieVersion=/MSIE (\d+)/.exec(navigator.userAgent)[1];if(doc.compatMode!="CSS1Compat"||(ieVersion<6||(ieVersion>8||!xhr)))return;var selectorEngines={"NW":"*.Dom.select","MooTools":"$$","DOMAssistant":"*.$","Prototype":"$$","YAHOO":"*.util.Selector.query","Sizzle":"*","jQuery":"*","dojo":"*.query"};var selectorMethod;var enabledWatchers=[];var ie6PatchID=0;var patchIE6MultipleClasses=true;var namespace=

"slvzr";var RE_COMMENT=/(\/\*[^*]*\*+([^\/][^*]*\*+)*\/)\s*/g;var RE_IMPORT=/@import\s*(?:(?:(?:url\(\s*(['"]?)(.*)\1)\s*\))|(?:(['"])(.*)\3))[^;]*;/g;var RE_ASSET_URL=/\burl\(\s*(["']?)(?!data:)([^"')]+)\1\s*\)/g;var RE_PSEUDO_STRUCTURAL=/^:(empty|(first|last|only|nth(-last)?)-(child|of-type))$/;var RE_PSEUDO_ELEMENTS=/:(:first-(?:line|letter))/g;var RE_SELECTOR_GROUP=/(^|})\s*([^\{]*?[\[:][^{]+)/g;var RE_SELECTOR_PARSE=/([ +~>])|(:[a-z-]+(?:\(.*?\)+)?)|(\[.*?\])/g;var RE_LIBRARY_INCOMPATIBLE_PSEUDOS=

/(:not\()?:(hover|enabled|disabled|focus|checked|target|active|visited|first-line|first-letter)\)?/g;var RE_PATCH_CLASS_NAME_REPLACE=/[^\w-]/g;var RE_INPUT_ELEMENTS=/^(INPUT|SELECT|TEXTAREA|BUTTON)$/;var RE_INPUT_CHECKABLE_TYPES=/^(checkbox|radio)$/;var BROKEN_ATTR_IMPLEMENTATIONS=ieVersion>6?/[\$\^*]=(['"])\1/:null;var RE_TIDY_TRAILING_WHITESPACE=/([(\[+~])\s+/g;var RE_TIDY_LEADING_WHITESPACE=/\s+([)\]+~])/g;var RE_TIDY_CONSECUTIVE_WHITESPACE=/\s+/g;var RE_TIDY_TRIM_WHITESPACE=/^\s*((?:[\S\s]*\S)?)\s*$/;

var EMPTY_STRING="";var SPACE_STRING=" ";var PLACEHOLDER_STRING="$1";function patchStyleSheet(cssText){return cssText.replace(RE_PSEUDO_ELEMENTS,PLACEHOLDER_STRING).replace(RE_SELECTOR_GROUP,function(m,prefix,selectorText){var selectorGroups=selectorText.split(",");for(var c=0,cs=selectorGroups.length;c<cs;c++){var selector=normalizeSelectorWhitespace(selectorGroups[c])+SPACE_STRING;var patches=[];selectorGroups[c]=selector.replace(RE_SELECTOR_PARSE,function(match,combinator,pseudo,attribute,index){if(combinator){if(patches.length>

0){applyPatches(selector.substring(0,index),patches);patches=[]}return combinator}else{var patch=pseudo?patchPseudoClass(pseudo):patchAttribute(attribute);if(patch){patches.push(patch);return"."+patch.className}return match}})}return prefix+selectorGroups.join(",")})}function patchAttribute(attr){return!BROKEN_ATTR_IMPLEMENTATIONS||BROKEN_ATTR_IMPLEMENTATIONS.test(attr)?{className:createClassName(attr),applyClass:true}:null}function patchPseudoClass(pseudo){var applyClass=true;var className=createClassName(pseudo.slice(1));

var isNegated=pseudo.substring(0,5)==":not(";var activateEventName;var deactivateEventName;if(isNegated)pseudo=pseudo.slice(5,-1);var bracketIndex=pseudo.indexOf("(");if(bracketIndex>-1)pseudo=pseudo.substring(0,bracketIndex);if(pseudo.charAt(0)==":")switch(pseudo.slice(1)){case "root":applyClass=function(e){return isNegated?e!=root:e==root};break;case "target":if(ieVersion==8){applyClass=function(e){var handler=function(){var hash=location.hash;var hashID=hash.slice(1);return isNegated?hash==EMPTY_STRING||

e.id!=hashID:hash!=EMPTY_STRING&&e.id==hashID};addEvent(win,"hashchange",function(){toggleElementClass(e,className,handler())});return handler()};break}return false;case "checked":applyClass=function(e){if(RE_INPUT_CHECKABLE_TYPES.test(e.type))addEvent(e,"propertychange",function(){if(event.propertyName=="checked")toggleElementClass(e,className,e.checked!==isNegated)});return e.checked!==isNegated};break;case "disabled":isNegated=!isNegated;case "enabled":applyClass=function(e){if(RE_INPUT_ELEMENTS.test(e.tagName)){addEvent(e,

"propertychange",function(){if(event.propertyName=="$disabled")toggleElementClass(e,className,e.$disabled===isNegated)});enabledWatchers.push(e);e.$disabled=e.disabled;return e.disabled===isNegated}return pseudo==":enabled"?isNegated:!isNegated};break;case "focus":activateEventName="focus";deactivateEventName="blur";case "hover":if(!activateEventName){activateEventName="mouseenter";deactivateEventName="mouseleave"}applyClass=function(e){addEvent(e,isNegated?deactivateEventName:activateEventName,function(){toggleElementClass(e,

className,true)});addEvent(e,isNegated?activateEventName:deactivateEventName,function(){toggleElementClass(e,className,false)});return isNegated};break;default:if(!RE_PSEUDO_STRUCTURAL.test(pseudo))return false;break}return{className:className,applyClass:applyClass}}function applyPatches(selectorText,patches){var elms;var domSelectorText=selectorText.replace(RE_LIBRARY_INCOMPATIBLE_PSEUDOS,EMPTY_STRING);if(domSelectorText==EMPTY_STRING||domSelectorText.charAt(domSelectorText.length-1)==SPACE_STRING)domSelectorText+=

"*";try{elms=selectorMethod(domSelectorText)}catch(ex){log("Selector '"+selectorText+"' threw exception '"+ex+"'")}if(elms)for(var d=0,dl=elms.length;d<dl;d++){var elm=elms[d];var cssClasses=elm.className;for(var f=0,fl=patches.length;f<fl;f++){var patch=patches[f];if(!hasPatch(elm,patch))if(patch.applyClass&&(patch.applyClass===true||patch.applyClass(elm)===true))cssClasses=toggleClass(cssClasses,patch.className,true)}elm.className=cssClasses}}function hasPatch(elm,patch){return(new RegExp("(^|\\s)"+

patch.className+"(\\s|$)")).test(elm.className)}function createClassName(className){return namespace+"-"+(ieVersion==6&&patchIE6MultipleClasses?ie6PatchID++:className.replace(RE_PATCH_CLASS_NAME_REPLACE,function(a){return a.charCodeAt(0)}))}function log(message){if(win.console)win.console.log(message)}function trim(text){return text.replace(RE_TIDY_TRIM_WHITESPACE,PLACEHOLDER_STRING)}function normalizeWhitespace(text){return trim(text).replace(RE_TIDY_CONSECUTIVE_WHITESPACE,SPACE_STRING)}function normalizeSelectorWhitespace(selectorText){return normalizeWhitespace(selectorText.replace(RE_TIDY_TRAILING_WHITESPACE,

PLACEHOLDER_STRING).replace(RE_TIDY_LEADING_WHITESPACE,PLACEHOLDER_STRING))}function toggleElementClass(elm,className,on){var oldClassName=elm.className;var newClassName=toggleClass(oldClassName,className,on);if(newClassName!=oldClassName){elm.className=newClassName;elm.parentNode.className+=EMPTY_STRING}}function toggleClass(classList,className,on){var re=RegExp("(^|\\s)"+className+"(\\s|$)");var classExists=re.test(classList);if(on)return classExists?classList:classList+SPACE_STRING+className;else return classExists?

trim(classList.replace(re,PLACEHOLDER_STRING)):classList}function addEvent(elm,eventName,eventHandler){elm.attachEvent("on"+eventName,eventHandler)}function getXHRObject(){if(win.XMLHttpRequest)return new XMLHttpRequest;try{return new ActiveXObject("Microsoft.XMLHTTP")}catch(e){return null}}function loadStyleSheet(url){xhr.open("GET",url,false);xhr.send();return xhr.status==200?xhr.responseText:EMPTY_STRING}function resolveUrl(url,contextUrl){function getProtocolAndHost(url){return url.substring(0,

url.indexOf("/",8))}if(/^https?:\/\//i.test(url))return getProtocolAndHost(contextUrl)==getProtocolAndHost(url)?url:null;if(url.charAt(0)=="/")return getProtocolAndHost(contextUrl)+url;var contextUrlPath=contextUrl.split(/[?#]/)[0];if(url.charAt(0)!="?"&&contextUrlPath.charAt(contextUrlPath.length-1)!="/")contextUrlPath=contextUrlPath.substring(0,contextUrlPath.lastIndexOf("/")+1);return contextUrlPath+url}function parseStyleSheet(url){if(url)return loadStyleSheet(url).replace(RE_COMMENT,EMPTY_STRING).replace(RE_IMPORT,

function(match,quoteChar,importUrl,quoteChar2,importUrl2){return parseStyleSheet(resolveUrl(importUrl||importUrl2,url))}).replace(RE_ASSET_URL,function(match,quoteChar,assetUrl){quoteChar=quoteChar||EMPTY_STRING;return" url("+quoteChar+resolveUrl(assetUrl,url)+quoteChar+") "});return EMPTY_STRING}function init(){var url,stylesheet;var baseTags=doc.getElementsByTagName("BASE");var baseUrl=baseTags.length>0?baseTags[0].href:doc.location.href;for(var c=0;c<doc.styleSheets.length;c++){stylesheet=doc.styleSheets[c];

if(stylesheet.href!=EMPTY_STRING){url=resolveUrl(stylesheet.href,baseUrl);if(url)stylesheet.cssText=patchStyleSheet(parseStyleSheet(url))}}if(enabledWatchers.length>0)setInterval(function(){for(var c=0,cl=enabledWatchers.length;c<cl;c++){var e=enabledWatchers[c];if(e.disabled!==e.$disabled)if(e.disabled){e.disabled=false;e.$disabled=true;e.disabled=true}else e.$disabled=e.disabled}},250)}ContentLoaded(win,function(){for(var engine in selectorEngines){var members,member,context=win;if(win[engine]){members=

selectorEngines[engine].replace("*",engine).split(".");while((member=members.shift())&&(context=context[member]));if(typeof context=="function"){selectorMethod=context;init();return}}}});function ContentLoaded(win,fn){var done=false,top=true,init=function(e){if(e.type=="readystatechange"&&doc.readyState!="complete")return;(e.type=="load"?win:doc).detachEvent("on"+e.type,init,false);if(!done&&(done=true))fn.call(win,e.type||e)},poll=function(){try{root.doScroll("left")}catch(e){setTimeout(poll,50);

return}init("poll")};if(doc.readyState=="complete")fn.call(win,EMPTY_STRING);else{if(doc.createEventObject&&root.doScroll){try{top=!win.frameElement}catch(e){}if(top)poll()}addEvent(doc,"readystatechange",init);addEvent(win,"load",init)}}})(this);



var matched, browser;



jQuery.uaMatch = function( ua ) {

    ua = ua.toLowerCase();



    var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||

        /(webkit)[ \/]([\w.]+)/.exec( ua ) ||

        /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||

        /(msie) ([\w.]+)/.exec( ua ) ||

        ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||

        [];



    return {

        browser: match[ 1 ] || "",

        version: match[ 2 ] || "0"

    };

};



matched = jQuery.uaMatch( navigator.userAgent );

browser = {};



if ( matched.browser ) {

    browser[ matched.browser ] = true;

    browser.version = matched.version;

}



// Chrome is Webkit, but Webkit is also Safari.

if ( browser.chrome ) {

    browser.webkit = true;

} else if ( browser.webkit ) {

    browser.safari = true;

}



jQuery.browser = browser;



	$.fn.hoverClass = function(c) {

		return this.each(function(){

			$(this).hover( 

				function() { $(this).addClass(c);  },

				function() { $(this).removeClass(c); }

			);

		});

	};

	

	$.fn.pressEnter = function(fn) {  



		return this.each(function() {  

			$(this).bind('enterPress', fn);

			$(this).keyup(function(e){

				if(e.keyCode == 13)

				{

				  $(this).trigger("enterPress");

				}

			})

		});  

	 };





 /**

 * image preloader

 */

(function ($) {

    $.imgpreload = function (imgs, settings) {

        settings = $.extend({}, $.fn.imgpreload.defaults, settings instanceof Function ? {

            all: settings

        } : settings);

        if ("string" == typeof imgs) imgs = new Array(imgs);

        var loaded = new Array;

        $.each(imgs, function (i, elem) {

            var img = new Image;

            var url = elem;

            var img_obj = img;

            if ("string" != typeof elem) {

                if($(elem).attr('src')=='undefined'){

					url = $(elem).attr('src');

				}else{

					url = $(elem).attr('data-src');

				}

                img_obj = elem

            }

            $(img).bind("load error", function (e) {

                loaded.push(img_obj);

                $.data(img_obj, "loaded", "error" == e.type ? false : true);

                if (settings.each instanceof Function) settings.each.call(img_obj);

                if (loaded.length >= imgs.length && settings.all instanceof Function) settings.all.call(loaded);

                $(this).unbind("load error")

            });

			

			if($(elem).attr('src')=='undefined'){

            	$(elem).attr('data-src', url)

			}else{

				img.src = url

			}

        })

    };

    $.fn.imgpreload = function (settings) {

        $.imgpreload(this, settings);

        return this

    };

    $.fn.imgpreload.defaults = {

        each: null,

        all: null

    }

})(jQuery);





/**
 * MNMenu
 * Drop down menu
 *
 * Copyright (c) 2013 Marc Nuri
 * Version: 0.0.19
 * Modified: 2014-12-22
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php
 *
 * http://www.marcnuri.com
 */

/**
 * Main plugin function
 * 
 * @param {jQuery} $
 * @returns {undefined}
 */
(function ($) {
    /**
     * Plugin initialization function
     * 
     * @param {object} op
     * @returns {unresolved}
     */
    $.fn.mnmenu = function (op) {
        var $this = $(this);
        ////////////////////////////////////////////////////////////////////////
        //To specify custom level settings without affecting defaults
        var tempLevelSettings = {};
        if (typeof op !== 'undefined' && typeof op.levelSettings !== 'undefined') {
            tempLevelSettings = op.levelSettings;
            delete op.levelSettings;
        }
        ////////////////////////////////////////////////////////////////////////
        var settings = $.extend({}, $.fn.mnmenu.defaults, op);
        $this.data('windowHeight', null);
        $this.data('windowWidth', null);
        ////////////////////////////////////////////////////////////////////////
        //Clone custom level settings so that defaults remain and apply custom;
        settings.levelSettings = $.extend({}, settings.levelSettings, tempLevelSettings);
        ////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////
        // Window resize event for responsive features
        $(window).resize(function () {
            $.fn.mnmenu.windowResize($this, settings);
        });
        ////////////////////////////////////////////////////////////////////////
        this.each(function () {
            var $parentMenu = $(this);
            if ($parentMenu.prop("tagName").toUpperCase() !== "UL") {
                $.error("This function can only be called in <ul> elements.");
            }
            $parentMenu.addClass(settings.menuClassName);
            //Recursion through elements to set default class names and parameters
            $.fn.mnmenu.levelRecursion(settings, $parentMenu, 0);
            //Hide every other submenu (It should be prehidden by css)
            $parentMenu.find("ul").each(function () {
                $(this).css("display", "none");
            });
        });

        //Add event listeners to every LI (When adding and removing html content, events are deleted
        $this.find("li").each(function () {
            var $this = $(this);
            $.fn.mnmenu.addEventListeners($this, settings);
        });
        $.fn.mnmenu.resetView($this, settings);
        return $this;
    };

    /**
     * 
     * @param {type} $menu
     * @param {type} settings
     * @returns {undefined}
     */
    $.fn.mnmenu.windowResize = function ($menu, settings) {
        //Mobile browser triggers window resize event when scrolling (This prevents it)
        //Reset view if Horizontal size changes (Responsive button)
        if (typeof $menu.data('windowWidth') !== 'undefined'
                && $menu.data('windowWidth') !== null
                && $menu.data('windowWidth') === $(window).width()) {
            return;
        }
        $.fn.mnmenu.resetView($menu, settings);
    };

    /**
     * Function called when mouse hovers a menu entry (&lt;li&gt;)
     * @param {jQuery} $menu
     * @param {type} settings
     * @returns {undefined}
     */
    $.fn.mnmenu.mouseEnter = function ($menu, settings) {
        var windowWidth = $(window).width();
        clearTimeout($menu.data('timer'));
        //Add hover class
        $.fn.mnmenu.elementsToHover($menu, settings).each(function () {
            $(this).addClass(settings.hoverClassName);
        });
        $menu.children("ul").each(function () {
            var $this = $(this);
            var $parent = $this.parent("li");
            var $parentContainer = $parent.closest("ul");
            //Stop previous (hiding) animation and display the object
            //Calculations had already been made
            if ($this.is(":animated")) {
                $this.stop(true, true).show();
            }
            //Was hidden
            else {
                //Set Z-Index
                var zindex = 1;
                var current = $this;
                while (current.get(0) !== $(document).get(0)) {
                    var temp = parseInt(current.css("z-index"));
                    if (!isNaN(temp) && temp > zindex) {
                        zindex = temp;
                    }
                    current = current.parent();
                }
                $this.css("z-index", zindex + 1);
                //Calculate+set container position
                // - Find level
                var currentLevel = 0;
                var classList = $this[0].className.split(/\s+/);
                for (var i = 0; i < classList.length; i++) {
                    if (classList[i].indexOf([settings.levelClassPrefix, '-'].join('')) >= 0) {
                        currentLevel = parseInt(classList[i].substring(settings.levelClassPrefix.length + 1));
                    }
                }
                var customLevelSettings = settings.levelSettings[currentLevel];
                if (typeof customLevelSettings === "undefined") {
                    customLevelSettings = settings.levelSettings[0];
                }
                //Horizontal position
                var left = "auto", right = "auto", top = "auto", bottom = "auto";
                //RtL
                if (customLevelSettings.parentAttachmentPosition.toUpperCase().indexOf("W") >= 0
                        && customLevelSettings.attachmentPosition.toUpperCase().indexOf("E") >= 0) {
                    right = $parent.outerWidth() + "px";
                    //Always show on screen (Reversi)
                    if ($parent.offset().left - $this.outerWidth() < 0) {
                        left = $parent.outerWidth() + "px";
                        right = "auto";
                    }
                } else if (customLevelSettings.parentAttachmentPosition.toUpperCase().indexOf("E") >= 0
                        && customLevelSettings.attachmentPosition.toUpperCase().indexOf("E") >= 0) {
                    right = "0px";
                }
                //LtR
                else if (customLevelSettings.parentAttachmentPosition.toUpperCase().indexOf("E") >= 0
                        && customLevelSettings.attachmentPosition.toUpperCase().indexOf("W") >= 0) {
                    left = $parent.outerWidth() + "px";
                    //Always show on screen 
                    //Display Menu in visible area. (Several options)
                    if (($parentContainer.offset().left + $parentContainer.outerWidth() + $this.outerWidth())
                            > windowWidth) {
                        // It doesn't fit to the left of the menu
                        if (($parentContainer.outerWidth() + $this.outerWidth()) > windowWidth) {
                            left = (windowWidth - $this.outerWidth())+"px";
                        }
                        // It all fits to the left of the menu (Reversi)
                        else {
                            left = "auto";
                            right = $parent.outerWidth() + "px";
                        }
                    }
                } else if (customLevelSettings.parentAttachmentPosition.toUpperCase().indexOf("W") >= 0
                        && customLevelSettings.attachmentPosition.toUpperCase().indexOf("W") >= 0) {
                    left = "0px";
                }
                //Vertical Position
                if (customLevelSettings.parentAttachmentPosition.toUpperCase().indexOf("N") >= 0
                        && customLevelSettings.attachmentPosition.toUpperCase().indexOf("S") >= 0) {
                    bottom = $parent.outerHeight() + "px";

                } else if (customLevelSettings.parentAttachmentPosition.toUpperCase().indexOf("S") >= 0
                        && customLevelSettings.attachmentPosition.toUpperCase().indexOf("S") >= 0) {
                    bottom = "0px";
                }
                else if (customLevelSettings.parentAttachmentPosition.toUpperCase().indexOf("S") >= 0
                        && customLevelSettings.attachmentPosition.toUpperCase().indexOf("N") >= 0) {
                    top = $parent.outerHeight() + "px";
                } else if (customLevelSettings.parentAttachmentPosition.toUpperCase().indexOf("N") >= 0
                        && customLevelSettings.attachmentPosition.toUpperCase().indexOf("N") >= 0) {
                    top = "0px";
                }
                $this.css("left", left);
                $this.css("right", right);
                $this.css("top", top);
                $this.css("bottom", bottom);
// OLD WAY                
////////////////////////////////////////////////////////////////////////////////                
//                //Position forNon-level-0 elements
//                if (!$parent.hasClass(
//                        [settings.levelClassPrefix, "-0"].join(""))) {
//                    //Horizontal position
//                    var initialOffset = $parentContainer.offset().left +
//                            $parentContainer.outerWidth();
//                    if (windowWidth < (initialOffset + $this.outerWidth())) {
//                        $this.css("left", "auto");
//                        $this.css("right", (
//                                //Container
//                                        ($parentContainer.outerWidth())
//                                        //Padding
//                                        + ($this.outerWidth() - $this.innerWidth())
//                                        ) + "px");
//                    } else {
//                        $this.css("left", $parentContainer.outerWidth() + "px");
//                        $this.css("right", "auto");
//                    }
//                    //Vertical position
//                    if ($parent.css("position") === "relative") {
//                        $this.css("top", "0px");
//                    } else {
//                        $this.css("top", $parent.position().top + "px");
//                    }
//                }
//                //level-0 elements
//                else {
//                    $this.css("left", "0px");
//                    $this.css("top", $this.closest("li").outerHeight() + "px");
//                }
////////////////////////////////////////////////////////////////////////////////
                $this.slideDown(settings.duration);
            }
        });
    };

    /**
     * Function called when mouse leaves a menu entry (&lt;li&gt;)
     * @param {jQuery} $menu
     * @param {type} settings
     * @returns {undefined}
     */
    $.fn.mnmenu.mouseLeave = function ($menu, settings) {
        clearTimeout($menu.data('timer'));
        //Remove hover class
        $.fn.mnmenu.elementsToHover($menu, settings).each(function () {
            $(this).removeClass(settings.hoverClassName);
        });
        $menu.children("ul").each(function () {
            var $toHide = $(this);
            $menu.data('timer', setTimeout(
                    function () {
                        $toHide.hide(settings.duration);
                    }, settings.delay));
        });
    };


    /**
     * Function called when mouse clicks a menu entry (&lt;li&gt;)
     * 
     * This was a suggestion by akcoder, but this functionality should be aquired by css 
     * I'm reverting
     * @param {jQuery} $menu
     * @param {type} settings
     * @returns {undefined}
     */
    /*
     $.fn.mnmenu.mouseClick = function($menu, settings) {
     //Contribution by https://github.com/akcoder
     //TODO: Rethink function to add customization capabilities (Ajax links, support for href target...)
     clearTimeout($menu.data('timer'));
     var $link = $menu.children('a');
     if ($link.attr('href')) {
     window.location.href = $link.attr('href');
     }
     };
     */

    $.fn.mnmenu.resetView = function ($menu, settings) {
        //Find the responsiveMenu button
        var responsiveSelector = ['li.' + settings.responsiveMenuButtonClass].join('');
        var $responsiveMenu = $menu.find(responsiveSelector).addBack(responsiveSelector);
        if ($responsiveMenu.length !== 0) {
            //Move children to top and remove button
            var $children = $responsiveMenu.children('ul').children();
            $menu.append($children);
            $responsiveMenu.remove();
            $.fn.mnmenu.levelRecursion(settings, $menu, 0);
        }
        //Calculate expanded width
        var menuWidth = 0;
        $menu.find(['li.', settings.levelClassPrefix, '-0'].join('')).each(function () {
            menuWidth += $(this).outerWidth();
        });
        if ($(window).width() < (menuWidth + settings.responsiveMenuWindowWidthFudge)
                && settings.responsiveMenuEnabled === true) {
            //Add responsive button and move children
            var $children = $menu.children();
            var $responsiveMenu = $(["<li class='", settings.responsiveMenuButtonClass,
                " first'>", settings.responsiveMenuButtonLabel,
                "<ul></ul></li>"].join(''));
            $menu.append($responsiveMenu);
            $.fn.mnmenu.addEventListeners($responsiveMenu, settings);
            $responsiveMenu.children('ul').append($children);
            $.fn.mnmenu.levelRecursion(settings, $menu, 0);
        }
        //Set variables for future checks
        $menu.data('windowHeight', $(window).height());
        $menu.data('windowWidth', $(window).width());
    };

    /**
     * Recursive function to traverse the component hierarchy setting attributes 
     * and adding the rest of components such as arrows.
     * 
     * @param {type} settings
     * @param {jQuery} $component
     * @param {int} level
     * @returns {undefined}
     */
    $.fn.mnmenu.levelRecursion = function (settings, $component, level) {
        if ($component.prop("tagName").toUpperCase() === "LI") {
            var middle = true;
            //Add arrows to parent <li>. This can only happen from level 1
            if ($component.parent().children().first().get(0) === $component.get(0)
                    && level > 0) {
                //Add Arrow to parent (just once).
                $component.parent().closest("li").append(
                        $(["<span ", "class='", settings.arrowClassName, "'></span>"].join("")
                                ).append(settings.arrowCharacter));
                //Add FirstClassName to first <li>
                $component.addClass(settings.firstClassName);
                middle = false;
            }
            //component can be first and last (no else if)
            if ($component.parent().children().last().get(0) === $component.get(0)) {
                $component.addClass(settings.lastClassName);
                middle = false;
            }
            if (middle) {
                $component.addClass(settings.middleClassName);
            }
            level++;
        }
        //The component may not have 'li' direct descendants a span or something else may be in the way
        $component.children().each(function () {
            var $currentLevel = $(this);
            //Remove old Level class attribute
            $currentLevel.removeClass([settings.levelClassPrefix, "-", level].join(''));
            $currentLevel.removeClass([settings.levelClassPrefix, "-", (level - 1)].join(''));
            $currentLevel.removeClass([settings.levelClassPrefix, "-", (level + 1)].join(''));
            //Add current level class attribute
            $currentLevel.addClass([settings.levelClassPrefix, "-", level].join(''));
            $.fn.mnmenu.levelRecursion(settings, $currentLevel, level);
        });
    };

    /**
     * Add event listeners to menu li
     * @param {type} $li
     * @param {type} settings
     * @returns {undefined}
     */
    $.fn.mnmenu.addEventListeners = function ($li, settings) {
        if ($.fn.hoverIntent) {
            var $this = $li;
            $this.hoverIntent(
                    function () {
                        $.fn.mnmenu.mouseEnter($(this), settings);
                    },
                    function () {
                        $.fn.mnmenu.mouseLeave($(this), settings);
                    });
            // Revert contribution from ackoder.
            /*
             $this.click(function(e) {
             $.fn.mnmenu.mouseClick($(this), settings);
             e.stopImmediatePropagation();
             });
             */
        } else {
            var $this = $li;
            $this.mouseenter(function () {
                $.fn.mnmenu.mouseEnter($(this), settings);
            });
            $this.mouseleave(function () {
                $.fn.mnmenu.mouseLeave($(this), settings);
            });
            // Revert contribution from ackoder.
            /*
             $this.click(function(e) {
             $.fn.mnmenu.mouseClick($(this), settings);
             e.stopImmediatePropagation();
             });
             */
        }
    };

    /**
     * Returns an array of elements to which to add/remove the "hover" 
     * class when hovered
     * @param {jQuery} $menu
     * @param {type} settings
     * @returns {jQuery}
     */
    $.fn.mnmenu.elementsToHover = function ($menu, settings) {
        //All children which aren't containers (li, span, links...)
        //This makes it easier for styling.
        return $([$menu, $menu.children(":not(ul)")]);
    };

    /**
     * Default plugin options
     */
    $.fn.mnmenu.defaults = {
        //Class for top-level menuName
        menuClassName: "mnmenu",
        //Class for hovered elements
        hoverClassName: "hover",
        //List elements levels
        levelClassPrefix: "level",
        //Class for arrow <span>
        arrowClassName: "arrow",
        arrowCharacter: " &#187;",
        //List elements position in level
        firstClassName: "first",
        middleClassName: "middle",
        lastClassName: "last",
        delay: 150,
        duration: 250,
        defaultParentAttachmentPosition: "NE",
        defaultAttachmentPosition: "NW",
        //Responsive
        responsiveMenuEnabled: true,
        responsiveMenuWindowWidthFudge: 10,
        responsiveMenuButtonClass: "mnresponsive-button",
        responsiveMenuButtonLabel: "Menu"
    };
    $.fn.mnmenu.defaults.levelSettings = {};
    //Define defaultTopLevelSettings
    $.fn.mnmenu.defaults.levelSettings[0] = new MNLevelSettings();
    //Second level settings for default behavior (typical menu)
    $.fn.mnmenu.defaults.levelSettings[1] = new MNLevelSettings();
    $.fn.mnmenu.defaults.levelSettings[1].parentAttachmentPosition = "SW";
    $.fn.mnmenu.defaults.levelSettings[1].attachmentPosition = "NW";
})(jQuery);

function MNLevelSettings() {
    this.parentAttachmentPosition = $.fn.mnmenu.defaults.defaultParentAttachmentPosition;
    this.attachmentPosition = $.fn.mnmenu.defaults.defaultAttachmentPosition;
    this.arrowCharacter = $.fn.mnmenu.defaults.arrowCharacter;
}



/**

 * setMenuEvent

 * mobile menu tab focus

 *

 * Copyright (c) 2014 Christian Niño Duller

 * Version: 0.0.1

 * http://www.bonsaimedia.com.au

 */

$.fn.setMenuEvent = function (btnMenuId) {

    return this.each(function () {

        var navTag = false,
			navTimer,
			clkTmr,
			menuLink = $(btnMenuId),
			mMenu = $(this);
			
        menuLink.click(function () {
            if (!navTag) {
				$(this).addClass('opn');
                mMenu.fadeIn('fast');
                mMenu.attr("tabindex", -1).focus();
				mMenu.find("a").show();
                navTag = true;
            } else {
                setOut();
                navTag = false;
            }
            clearTimeout(navTimer);

        });

		mMenu.find('a').click(function () {
			clkTmr = setTimeout(function () {
				clearTimeout(clkTmr);
            	clearTimeout(navTimer);
			},300);
			clearTimeout(navTimer);
		});

        mMenu.focusout(function () {
			navTimer = setTimeout(function () {
				setOut();
				navTag = false;
			}, 150);
        });		

		function setOut(){
			mMenu.removeAttr("tabindex").slideUp('fast');
			//mMenu.find("div.panel").hide();
			//mMenu.find("a").slideUp();
			menuLink.removeClass('opn');
		};

    })

};





/**

 * setDropdown focus

 *

 * Copyright (c) 2015 Christian Niño Duller

 * Version: 0.0.1

 * http://www.bonsaimedia.com.au

 */

$.fn.setFocusEvent = function (options) {

  // default settings:

  var defaults = {

	  	f_in_Evnt: null,

		f_out_Evnt: null

	};

  var settings = $.extend( {}, defaults, options );

    return this.each(function (e) {

        var navTag = false,

			navTimer,

			clkTmr,

			btn = $(this),

			wrpr = btn.parent(),

			mMenu = $(this);

		btn.click(function() {

			if (!navTag) {

				setIn();

			}else{

				setOut();

			}

			clearTimeout(navTimer);

			return false;

		});

		

		function setIn(){

			wrpr.addClass('focus').attr("tabindex", -2).focus();

			navTag = true;

			if (settings.f_in_Evnt instanceof Function) { settings.f_in_Evnt.call(null, btn); }

		};

		function setOut(){

			wrpr.removeClass('focus').removeAttr("tabindex");

            navTag = false;

			if (settings.f_out_Evnt instanceof Function) { settings.f_out_Evnt.call(null, btn); }

		};

		

		wrpr.find('a').click(function () {

			clkTmr = setTimeout(function () {

				clearTimeout(clkTmr);

            	clearTimeout(navTimer);

			},100);

			clearTimeout(navTimer);

		});

		

        wrpr.focusout(function () {

			navTimer = setTimeout(function () {

				if(wrpr.find('input:focus').length){

					wrpr.attr("tabindex", -2);

					clearTimeout(navTimer);

					return;

				}else{

					setOut();

				}

					

			}, 150);

        });

    })

};





/*!

 * imagesLoaded PACKAGED v4.1.4

 * JavaScript is all like "You images are done yet or what?"

 * MIT License

 */



!function(e,t){"function"==typeof define&&define.amd?define("ev-emitter/ev-emitter",t):"object"==typeof module&&module.exports?module.exports=t():e.EvEmitter=t()}("undefined"!=typeof window?window:this,function(){function e(){}var t=e.prototype;return t.on=function(e,t){if(e&&t){var i=this._events=this._events||{},n=i[e]=i[e]||[];return n.indexOf(t)==-1&&n.push(t),this}},t.once=function(e,t){if(e&&t){this.on(e,t);var i=this._onceEvents=this._onceEvents||{},n=i[e]=i[e]||{};return n[t]=!0,this}},t.off=function(e,t){var i=this._events&&this._events[e];if(i&&i.length){var n=i.indexOf(t);return n!=-1&&i.splice(n,1),this}},t.emitEvent=function(e,t){var i=this._events&&this._events[e];if(i&&i.length){i=i.slice(0),t=t||[];for(var n=this._onceEvents&&this._onceEvents[e],o=0;o<i.length;o++){var r=i[o],s=n&&n[r];s&&(this.off(e,r),delete n[r]),r.apply(this,t)}return this}},t.allOff=function(){delete this._events,delete this._onceEvents},e}),function(e,t){"use strict";"function"==typeof define&&define.amd?define(["ev-emitter/ev-emitter"],function(i){return t(e,i)}):"object"==typeof module&&module.exports?module.exports=t(e,require("ev-emitter")):e.imagesLoaded=t(e,e.EvEmitter)}("undefined"!=typeof window?window:this,function(e,t){function i(e,t){for(var i in t)e[i]=t[i];return e}function n(e){if(Array.isArray(e))return e;var t="object"==typeof e&&"number"==typeof e.length;return t?d.call(e):[e]}function o(e,t,r){if(!(this instanceof o))return new o(e,t,r);var s=e;return"string"==typeof e&&(s=document.querySelectorAll(e)),s?(this.elements=n(s),this.options=i({},this.options),"function"==typeof t?r=t:i(this.options,t),r&&this.on("always",r),this.getImages(),h&&(this.jqDeferred=new h.Deferred),void setTimeout(this.check.bind(this))):void a.error("Bad element for imagesLoaded "+(s||e))}function r(e){this.img=e}function s(e,t){this.url=e,this.element=t,this.img=new Image}var h=e.jQuery,a=e.console,d=Array.prototype.slice;o.prototype=Object.create(t.prototype),o.prototype.options={},o.prototype.getImages=function(){this.images=[],this.elements.forEach(this.addElementImages,this)},o.prototype.addElementImages=function(e){"IMG"==e.nodeName&&this.addImage(e),this.options.background===!0&&this.addElementBackgroundImages(e);var t=e.nodeType;if(t&&u[t]){for(var i=e.querySelectorAll("img"),n=0;n<i.length;n++){var o=i[n];this.addImage(o)}if("string"==typeof this.options.background){var r=e.querySelectorAll(this.options.background);for(n=0;n<r.length;n++){var s=r[n];this.addElementBackgroundImages(s)}}}};var u={1:!0,9:!0,11:!0};return o.prototype.addElementBackgroundImages=function(e){var t=getComputedStyle(e);if(t)for(var i=/url\((['"])?(.*?)\1\)/gi,n=i.exec(t.backgroundImage);null!==n;){var o=n&&n[2];o&&this.addBackground(o,e),n=i.exec(t.backgroundImage)}},o.prototype.addImage=function(e){var t=new r(e);this.images.push(t)},o.prototype.addBackground=function(e,t){var i=new s(e,t);this.images.push(i)},o.prototype.check=function(){function e(e,i,n){setTimeout(function(){t.progress(e,i,n)})}var t=this;return this.progressedCount=0,this.hasAnyBroken=!1,this.images.length?void this.images.forEach(function(t){t.once("progress",e),t.check()}):void this.complete()},o.prototype.progress=function(e,t,i){this.progressedCount++,this.hasAnyBroken=this.hasAnyBroken||!e.isLoaded,this.emitEvent("progress",[this,e,t]),this.jqDeferred&&this.jqDeferred.notify&&this.jqDeferred.notify(this,e),this.progressedCount==this.images.length&&this.complete(),this.options.debug&&a&&a.log("progress: "+i,e,t)},o.prototype.complete=function(){var e=this.hasAnyBroken?"fail":"done";if(this.isComplete=!0,this.emitEvent(e,[this]),this.emitEvent("always",[this]),this.jqDeferred){var t=this.hasAnyBroken?"reject":"resolve";this.jqDeferred[t](this)}},r.prototype=Object.create(t.prototype),r.prototype.check=function(){var e=this.getIsImageComplete();return e?void this.confirm(0!==this.img.naturalWidth,"naturalWidth"):(this.proxyImage=new Image,this.proxyImage.addEventListener("load",this),this.proxyImage.addEventListener("error",this),this.img.addEventListener("load",this),this.img.addEventListener("error",this),void(this.proxyImage.src=this.img.src))},r.prototype.getIsImageComplete=function(){return this.img.complete&&this.img.naturalWidth},r.prototype.confirm=function(e,t){this.isLoaded=e,this.emitEvent("progress",[this,this.img,t])},r.prototype.handleEvent=function(e){var t="on"+e.type;this[t]&&this[t](e)},r.prototype.onload=function(){this.confirm(!0,"onload"),this.unbindEvents()},r.prototype.onerror=function(){this.confirm(!1,"onerror"),this.unbindEvents()},r.prototype.unbindEvents=function(){this.proxyImage.removeEventListener("load",this),this.proxyImage.removeEventListener("error",this),this.img.removeEventListener("load",this),this.img.removeEventListener("error",this)},s.prototype=Object.create(r.prototype),s.prototype.check=function(){this.img.addEventListener("load",this),this.img.addEventListener("error",this),this.img.src=this.url;var e=this.getIsImageComplete();e&&(this.confirm(0!==this.img.naturalWidth,"naturalWidth"),this.unbindEvents())},s.prototype.unbindEvents=function(){this.img.removeEventListener("load",this),this.img.removeEventListener("error",this)},s.prototype.confirm=function(e,t){this.isLoaded=e,this.emitEvent("progress",[this,this.element,t])},o.makeJQueryPlugin=function(t){t=t||e.jQuery,t&&(h=t,h.fn.imagesLoaded=function(e,t){var i=new o(this,e,t);return i.jqDeferred.promise(h(this))})},o.makeJQueryPlugin(),o});



}(window.jQuery));







/*! A fix for the iOS orientationchange zoom bug.

 Script by @scottjehl, rebound by @wilto.

 MIT / GPLv2 License.

*/

(function(w){

	

	// This fix addresses an iOS bug, so return early if the UA claims it's something else.

	var ua = navigator.userAgent;

	if( !( /iPhone|iPad|iPod/.test( navigator.platform ) && /OS [1-5]_[0-9_]* like Mac OS X/i.test(ua) && ua.indexOf( "AppleWebKit" ) > -1 ) ){

		return;

	}



    var doc = w.document;



    if( !doc.querySelector ){ return; }



    var meta = doc.querySelector( "meta[name=viewport]" ),

        initialContent = meta && meta.getAttribute( "content" ),

        disabledZoom = initialContent + ",maximum-scale=1",

        enabledZoom = initialContent + ",maximum-scale=10",

        enabled = true,

		x, y, z, aig;



    if( !meta ){ return; }



    function restoreZoom(){

        meta.setAttribute( "content", enabledZoom );

        enabled = true;

    }



    function disableZoom(){

        meta.setAttribute( "content", disabledZoom );

        enabled = false;

    }

	

    function checkTilt( e ){

		aig = e.accelerationIncludingGravity;

		x = Math.abs( aig.x );

		y = Math.abs( aig.y );

		z = Math.abs( aig.z );

				

		// If portrait orientation and in one of the danger zones

        if( (!w.orientation || w.orientation === 180) && ( x > 7 || ( ( z > 6 && y < 8 || z < 8 && y > 6 ) && x > 5 ) ) ){

			if( enabled ){

				disableZoom();

			}        	

        }

		else if( !enabled ){

			restoreZoom();

        }

    }

	

	w.addEventListener( "orientationchange", restoreZoom, false );

	w.addEventListener( "devicemotion", checkTilt, false );



})( this );

 

 /**
 * Owl Carousel v2.3.4
 * Copyright 2013-2018 David Deutsch
 * Licensed under: SEE LICENSE IN https://github.com/OwlCarousel2/OwlCarousel2/blob/master/LICENSE
 */

!function(h,i,n,a){function l(t,e){this.settings=null,this.options=h.extend({},l.Defaults,e),this.$element=h(t),this._handlers={},this._plugins={},this._supress={},this._current=null,this._speed=null,this._coordinates=[],this._breakpoint=null,this._width=null,this._items=[],this._clones=[],this._mergers=[],this._widths=[],this._invalidated={},this._pipe=[],this._drag={time:null,target:null,pointer:null,stage:{start:null,current:null},direction:null},this._states={current:{},tags:{initializing:["busy"],animating:["busy"],dragging:["interacting"]}},h.each(["onResize","onThrottledResize"],h.proxy(function(t,e){this._handlers[e]=h.proxy(this[e],this)},this)),h.each(l.Plugins,h.proxy(function(t,e){this._plugins[t.charAt(0).toLowerCase()+t.slice(1)]=new e(this)},this)),h.each(l.Workers,h.proxy(function(t,e){this._pipe.push({filter:e.filter,run:h.proxy(e.run,this)})},this)),this.setup(),this.initialize()}l.Defaults={items:3,loop:!1,center:!1,rewind:!1,checkVisibility:!0,mouseDrag:!0,touchDrag:!0,pullDrag:!0,freeDrag:!1,margin:0,stagePadding:0,merge:!1,mergeFit:!0,autoWidth:!1,startPosition:0,rtl:!1,smartSpeed:250,fluidSpeed:!1,dragEndSpeed:!1,responsive:{},responsiveRefreshRate:200,responsiveBaseElement:i,fallbackEasing:"swing",slideTransition:"",info:!1,nestedItemSelector:!1,itemElement:"div",stageElement:"div",refreshClass:"owl-refresh",loadedClass:"owl-loaded",loadingClass:"owl-loading",rtlClass:"owl-rtl",responsiveClass:"owl-responsive",dragClass:"owl-drag",itemClass:"owl-item",stageClass:"owl-stage",stageOuterClass:"owl-stage-outer",grabClass:"owl-grab"},l.Width={Default:"default",Inner:"inner",Outer:"outer"},l.Type={Event:"event",State:"state"},l.Plugins={},l.Workers=[{filter:["width","settings"],run:function(){this._width=this.$element.width()}},{filter:["width","items","settings"],run:function(t){t.current=this._items&&this._items[this.relative(this._current)]}},{filter:["items","settings"],run:function(){this.$stage.children(".cloned").remove()}},{filter:["width","items","settings"],run:function(t){var e=this.settings.margin||"",i=!this.settings.autoWidth,s=this.settings.rtl,n={width:"auto","margin-left":s?e:"","margin-right":s?"":e};!i&&this.$stage.children().css(n),t.css=n}},{filter:["width","items","settings"],run:function(t){var e=(this.width()/this.settings.items).toFixed(3)-this.settings.margin,i=null,s=this._items.length,n=!this.settings.autoWidth,o=[];for(t.items={merge:!1,width:e};s--;)i=this._mergers[s],i=this.settings.mergeFit&&Math.min(i,this.settings.items)||i,t.items.merge=1<i||t.items.merge,o[s]=n?e*i:this._items[s].width();this._widths=o}},{filter:["items","settings"],run:function(){var t=[],e=this._items,i=this.settings,s=Math.max(2*i.items,4),n=2*Math.ceil(e.length/2),o=i.loop&&e.length?i.rewind?s:Math.max(s,n):0,r="",a="";for(o/=2;0<o;)t.push(this.normalize(t.length/2,!0)),r+=e[t[t.length-1]][0].outerHTML,t.push(this.normalize(e.length-1-(t.length-1)/2,!0)),a=e[t[t.length-1]][0].outerHTML+a,o-=1;this._clones=t,h(r).addClass("cloned").appendTo(this.$stage),h(a).addClass("cloned").prependTo(this.$stage)}},{filter:["width","items","settings"],run:function(){for(var t=this.settings.rtl?1:-1,e=this._clones.length+this._items.length,i=-1,s=0,n=0,o=[];++i<e;)s=o[i-1]||0,n=this._widths[this.relative(i)]+this.settings.margin,o.push(s+n*t);this._coordinates=o}},{filter:["width","items","settings"],run:function(){var t=this.settings.stagePadding,e=this._coordinates,i={width:Math.ceil(Math.abs(e[e.length-1]))+2*t,"padding-left":t||"","padding-right":t||""};this.$stage.css(i)}},{filter:["width","items","settings"],run:function(t){var e=this._coordinates.length,i=!this.settings.autoWidth,s=this.$stage.children();if(i&&t.items.merge)for(;e--;)t.css.width=this._widths[this.relative(e)],s.eq(e).css(t.css);else i&&(t.css.width=t.items.width,s.css(t.css))}},{filter:["items"],run:function(){this._coordinates.length<1&&this.$stage.removeAttr("style")}},{filter:["width","items","settings"],run:function(t){t.current=t.current?this.$stage.children().index(t.current):0,t.current=Math.max(this.minimum(),Math.min(this.maximum(),t.current)),this.reset(t.current)}},{filter:["position"],run:function(){this.animate(this.coordinates(this._current))}},{filter:["width","position","items","settings"],run:function(){var t,e,i,s,n=this.settings.rtl?1:-1,o=2*this.settings.stagePadding,r=this.coordinates(this.current())+o,a=r+this.width()*n,h=[];for(i=0,s=this._coordinates.length;i<s;i++)t=this._coordinates[i-1]||0,e=Math.abs(this._coordinates[i])+o*n,(this.op(t,"<=",r)&&this.op(t,">",a)||this.op(e,"<",r)&&this.op(e,">",a))&&h.push(i);this.$stage.children(".active").removeClass("active"),this.$stage.children(":eq("+h.join("), :eq(")+")").addClass("active"),this.$stage.children(".center").removeClass("center"),this.settings.center&&this.$stage.children().eq(this.current()).addClass("center")}}],l.prototype.initializeStage=function(){this.$stage=this.$element.find("."+this.settings.stageClass),this.$stage.length||(this.$element.addClass(this.options.loadingClass),this.$stage=h("<"+this.settings.stageElement+">",{class:this.settings.stageClass}).wrap(h("<div/>",{class:this.settings.stageOuterClass})),this.$element.append(this.$stage.parent()))},l.prototype.initializeItems=function(){var t=this.$element.find(".owl-item");if(t.length)return this._items=t.get().map(function(t){return h(t)}),this._mergers=this._items.map(function(){return 1}),void this.refresh();this.replace(this.$element.children().not(this.$stage.parent())),this.isVisible()?this.refresh():this.invalidate("width"),this.$element.removeClass(this.options.loadingClass).addClass(this.options.loadedClass)},l.prototype.initialize=function(){var t,e,i;(this.enter("initializing"),this.trigger("initialize"),this.$element.toggleClass(this.settings.rtlClass,this.settings.rtl),this.settings.autoWidth&&!this.is("pre-loading"))&&(t=this.$element.find("img"),e=this.settings.nestedItemSelector?"."+this.settings.nestedItemSelector:a,i=this.$element.children(e).width(),t.length&&i<=0&&this.preloadAutoWidthImages(t));this.initializeStage(),this.initializeItems(),this.registerEventHandlers(),this.leave("initializing"),this.trigger("initialized")},l.prototype.isVisible=function(){return!this.settings.checkVisibility||this.$element.is(":visible")},l.prototype.setup=function(){var e=this.viewport(),t=this.options.responsive,i=-1,s=null;t?(h.each(t,function(t){t<=e&&i<t&&(i=Number(t))}),"function"==typeof(s=h.extend({},this.options,t[i])).stagePadding&&(s.stagePadding=s.stagePadding()),delete s.responsive,s.responsiveClass&&this.$element.attr("class",this.$element.attr("class").replace(new RegExp("("+this.options.responsiveClass+"-)\\S+\\s","g"),"$1"+i))):s=h.extend({},this.options),this.trigger("change",{property:{name:"settings",value:s}}),this._breakpoint=i,this.settings=s,this.invalidate("settings"),this.trigger("changed",{property:{name:"settings",value:this.settings}})},l.prototype.optionsLogic=function(){this.settings.autoWidth&&(this.settings.stagePadding=!1,this.settings.merge=!1)},l.prototype.prepare=function(t){var e=this.trigger("prepare",{content:t});return e.data||(e.data=h("<"+this.settings.itemElement+"/>").addClass(this.options.itemClass).append(t)),this.trigger("prepared",{content:e.data}),e.data},l.prototype.update=function(){for(var t=0,e=this._pipe.length,i=h.proxy(function(t){return this[t]},this._invalidated),s={};t<e;)(this._invalidated.all||0<h.grep(this._pipe[t].filter,i).length)&&this._pipe[t].run(s),t++;this._invalidated={},!this.is("valid")&&this.enter("valid")},l.prototype.width=function(t){switch(t=t||l.Width.Default){case l.Width.Inner:case l.Width.Outer:return this._width;default:return this._width-2*this.settings.stagePadding+this.settings.margin}},l.prototype.refresh=function(){this.enter("refreshing"),this.trigger("refresh"),this.setup(),this.optionsLogic(),this.$element.addClass(this.options.refreshClass),this.update(),this.$element.removeClass(this.options.refreshClass),this.leave("refreshing"),this.trigger("refreshed")},l.prototype.onThrottledResize=function(){i.clearTimeout(this.resizeTimer),this.resizeTimer=i.setTimeout(this._handlers.onResize,this.settings.responsiveRefreshRate)},l.prototype.onResize=function(){return!!this._items.length&&(this._width!==this.$element.width()&&(!!this.isVisible()&&(this.enter("resizing"),this.trigger("resize").isDefaultPrevented()?(this.leave("resizing"),!1):(this.invalidate("width"),this.refresh(),this.leave("resizing"),void this.trigger("resized")))))},l.prototype.registerEventHandlers=function(){h.support.transition&&this.$stage.on(h.support.transition.end+".owl.core",h.proxy(this.onTransitionEnd,this)),!1!==this.settings.responsive&&this.on(i,"resize",this._handlers.onThrottledResize),this.settings.mouseDrag&&(this.$element.addClass(this.options.dragClass),this.$stage.on("mousedown.owl.core",h.proxy(this.onDragStart,this)),this.$stage.on("dragstart.owl.core selectstart.owl.core",function(){return!1})),this.settings.touchDrag&&(this.$stage.on("touchstart.owl.core",h.proxy(this.onDragStart,this)),this.$stage.on("touchcancel.owl.core",h.proxy(this.onDragEnd,this)))},l.prototype.onDragStart=function(t){var e=null;3!==t.which&&(h.support.transform?e={x:(e=this.$stage.css("transform").replace(/.*\(|\)| /g,"").split(","))[16===e.length?12:4],y:e[16===e.length?13:5]}:(e=this.$stage.position(),e={x:this.settings.rtl?e.left+this.$stage.width()-this.width()+this.settings.margin:e.left,y:e.top}),this.is("animating")&&(h.support.transform?this.animate(e.x):this.$stage.stop(),this.invalidate("position")),this.$element.toggleClass(this.options.grabClass,"mousedown"===t.type),this.speed(0),this._drag.time=(new Date).getTime(),this._drag.target=h(t.target),this._drag.stage.start=e,this._drag.stage.current=e,this._drag.pointer=this.pointer(t),h(n).on("mouseup.owl.core touchend.owl.core",h.proxy(this.onDragEnd,this)),h(n).one("mousemove.owl.core touchmove.owl.core",h.proxy(function(t){var e=this.difference(this._drag.pointer,this.pointer(t));h(n).on("mousemove.owl.core touchmove.owl.core",h.proxy(this.onDragMove,this)),Math.abs(e.x)<Math.abs(e.y)&&this.is("valid")||(t.preventDefault(),this.enter("dragging"),this.trigger("drag"))},this)))},l.prototype.onDragMove=function(t){var e=null,i=null,s=null,n=this.difference(this._drag.pointer,this.pointer(t)),o=this.difference(this._drag.stage.start,n);this.is("dragging")&&(t.preventDefault(),this.settings.loop?(e=this.coordinates(this.minimum()),i=this.coordinates(this.maximum()+1)-e,o.x=((o.x-e)%i+i)%i+e):(e=this.settings.rtl?this.coordinates(this.maximum()):this.coordinates(this.minimum()),i=this.settings.rtl?this.coordinates(this.minimum()):this.coordinates(this.maximum()),s=this.settings.pullDrag?-1*n.x/5:0,o.x=Math.max(Math.min(o.x,e+s),i+s)),this._drag.stage.current=o,this.animate(o.x))},l.prototype.onDragEnd=function(t){var e=this.difference(this._drag.pointer,this.pointer(t)),i=this._drag.stage.current,s=0<e.x^this.settings.rtl?"left":"right";h(n).off(".owl.core"),this.$element.removeClass(this.options.grabClass),(0!==e.x&&this.is("dragging")||!this.is("valid"))&&(this.speed(this.settings.dragEndSpeed||this.settings.smartSpeed),this.current(this.closest(i.x,0!==e.x?s:this._drag.direction)),this.invalidate("position"),this.update(),this._drag.direction=s,(3<Math.abs(e.x)||300<(new Date).getTime()-this._drag.time)&&this._drag.target.one("click.owl.core",function(){return!1})),this.is("dragging")&&(this.leave("dragging"),this.trigger("dragged"))},l.prototype.closest=function(i,s){var n=-1,o=this.width(),r=this.coordinates();return this.settings.freeDrag||h.each(r,h.proxy(function(t,e){return"left"===s&&e-30<i&&i<e+30?n=t:"right"===s&&e-o-30<i&&i<e-o+30?n=t+1:this.op(i,"<",e)&&this.op(i,">",r[t+1]!==a?r[t+1]:e-o)&&(n="left"===s?t+1:t),-1===n},this)),this.settings.loop||(this.op(i,">",r[this.minimum()])?n=i=this.minimum():this.op(i,"<",r[this.maximum()])&&(n=i=this.maximum())),n},l.prototype.animate=function(t){var e=0<this.speed();this.is("animating")&&this.onTransitionEnd(),e&&(this.enter("animating"),this.trigger("translate")),h.support.transform3d&&h.support.transition?this.$stage.css({transform:"translate3d("+t+"px,0px,0px)",transition:this.speed()/1e3+"s"+(this.settings.slideTransition?" "+this.settings.slideTransition:"")}):e?this.$stage.animate({left:t+"px"},this.speed(),this.settings.fallbackEasing,h.proxy(this.onTransitionEnd,this)):this.$stage.css({left:t+"px"})},l.prototype.is=function(t){return this._states.current[t]&&0<this._states.current[t]},l.prototype.current=function(t){if(t===a)return this._current;if(0===this._items.length)return a;if(t=this.normalize(t),this._current!==t){var e=this.trigger("change",{property:{name:"position",value:t}});e.data!==a&&(t=this.normalize(e.data)),this._current=t,this.invalidate("position"),this.trigger("changed",{property:{name:"position",value:this._current}})}return this._current},l.prototype.invalidate=function(t){return"string"===h.type(t)&&(this._invalidated[t]=!0,this.is("valid")&&this.leave("valid")),h.map(this._invalidated,function(t,e){return e})},l.prototype.reset=function(t){(t=this.normalize(t))!==a&&(this._speed=0,this._current=t,this.suppress(["translate","translated"]),this.animate(this.coordinates(t)),this.release(["translate","translated"]))},l.prototype.normalize=function(t,e){var i=this._items.length,s=e?0:this._clones.length;return!this.isNumeric(t)||i<1?t=a:(t<0||i+s<=t)&&(t=((t-s/2)%i+i)%i+s/2),t},l.prototype.relative=function(t){return t-=this._clones.length/2,this.normalize(t,!0)},l.prototype.maximum=function(t){var e,i,s,n=this.settings,o=this._coordinates.length;if(n.loop)o=this._clones.length/2+this._items.length-1;else if(n.autoWidth||n.merge){if(e=this._items.length)for(i=this._items[--e].width(),s=this.$element.width();e--&&!(s<(i+=this._items[e].width()+this.settings.margin)););o=e+1}else o=n.center?this._items.length-1:this._items.length-n.items;return t&&(o-=this._clones.length/2),Math.max(o,0)},l.prototype.minimum=function(t){return t?0:this._clones.length/2},l.prototype.items=function(t){return t===a?this._items.slice():(t=this.normalize(t,!0),this._items[t])},l.prototype.mergers=function(t){return t===a?this._mergers.slice():(t=this.normalize(t,!0),this._mergers[t])},l.prototype.clones=function(i){var e=this._clones.length/2,s=e+this._items.length,n=function(t){return t%2==0?s+t/2:e-(t+1)/2};return i===a?h.map(this._clones,function(t,e){return n(e)}):h.map(this._clones,function(t,e){return t===i?n(e):null})},l.prototype.speed=function(t){return t!==a&&(this._speed=t),this._speed},l.prototype.coordinates=function(t){var e,i=1,s=t-1;return t===a?h.map(this._coordinates,h.proxy(function(t,e){return this.coordinates(e)},this)):(this.settings.center?(this.settings.rtl&&(i=-1,s=t+1),e=this._coordinates[t],e+=(this.width()-e+(this._coordinates[s]||0))/2*i):e=this._coordinates[s]||0,e=Math.ceil(e))},l.prototype.duration=function(t,e,i){return 0===i?0:Math.min(Math.max(Math.abs(e-t),1),6)*Math.abs(i||this.settings.smartSpeed)},l.prototype.to=function(t,e){var i=this.current(),s=null,n=t-this.relative(i),o=(0<n)-(n<0),r=this._items.length,a=this.minimum(),h=this.maximum();this.settings.loop?(!this.settings.rewind&&Math.abs(n)>r/2&&(n+=-1*o*r),(s=(((t=i+n)-a)%r+r)%r+a)!==t&&s-n<=h&&0<s-n&&(i=s-n,t=s,this.reset(i))):t=this.settings.rewind?(t%(h+=1)+h)%h:Math.max(a,Math.min(h,t)),this.speed(this.duration(i,t,e)),this.current(t),this.isVisible()&&this.update()},l.prototype.next=function(t){t=t||!1,this.to(this.relative(this.current())+1,t)},l.prototype.prev=function(t){t=t||!1,this.to(this.relative(this.current())-1,t)},l.prototype.onTransitionEnd=function(t){if(t!==a&&(t.stopPropagation(),(t.target||t.srcElement||t.originalTarget)!==this.$stage.get(0)))return!1;this.leave("animating"),this.trigger("translated")},l.prototype.viewport=function(){var t;return this.options.responsiveBaseElement!==i?t=h(this.options.responsiveBaseElement).width():i.innerWidth?t=i.innerWidth:n.documentElement&&n.documentElement.clientWidth?t=n.documentElement.clientWidth:console.warn("Can not detect viewport width."),t},l.prototype.replace=function(t){this.$stage.empty(),this._items=[],t&&(t=t instanceof jQuery?t:h(t)),this.settings.nestedItemSelector&&(t=t.find("."+this.settings.nestedItemSelector)),t.filter(function(){return 1===this.nodeType}).each(h.proxy(function(t,e){e=this.prepare(e),this.$stage.append(e),this._items.push(e),this._mergers.push(1*e.find("[data-merge]").addBack("[data-merge]").attr("data-merge")||1)},this)),this.reset(this.isNumeric(this.settings.startPosition)?this.settings.startPosition:0),this.invalidate("items")},l.prototype.add=function(t,e){var i=this.relative(this._current);e=e===a?this._items.length:this.normalize(e,!0),t=t instanceof jQuery?t:h(t),this.trigger("add",{content:t,position:e}),t=this.prepare(t),0===this._items.length||e===this._items.length?(0===this._items.length&&this.$stage.append(t),0!==this._items.length&&this._items[e-1].after(t),this._items.push(t),this._mergers.push(1*t.find("[data-merge]").addBack("[data-merge]").attr("data-merge")||1)):(this._items[e].before(t),this._items.splice(e,0,t),this._mergers.splice(e,0,1*t.find("[data-merge]").addBack("[data-merge]").attr("data-merge")||1)),this._items[i]&&this.reset(this._items[i].index()),this.invalidate("items"),this.trigger("added",{content:t,position:e})},l.prototype.remove=function(t){(t=this.normalize(t,!0))!==a&&(this.trigger("remove",{content:this._items[t],position:t}),this._items[t].remove(),this._items.splice(t,1),this._mergers.splice(t,1),this.invalidate("items"),this.trigger("removed",{content:null,position:t}))},l.prototype.preloadAutoWidthImages=function(t){t.each(h.proxy(function(t,e){this.enter("pre-loading"),e=h(e),h(new Image).one("load",h.proxy(function(t){e.attr("src",t.target.src),e.css("opacity",1),this.leave("pre-loading"),!this.is("pre-loading")&&!this.is("initializing")&&this.refresh()},this)).attr("src",e.attr("src")||e.attr("data-src")||e.attr("data-src-retina"))},this))},l.prototype.destroy=function(){for(var t in this.$element.off(".owl.core"),this.$stage.off(".owl.core"),h(n).off(".owl.core"),!1!==this.settings.responsive&&(i.clearTimeout(this.resizeTimer),this.off(i,"resize",this._handlers.onThrottledResize)),this._plugins)this._plugins[t].destroy();this.$stage.children(".cloned").remove(),this.$stage.unwrap(),this.$stage.children().contents().unwrap(),this.$stage.children().unwrap(),this.$stage.remove(),this.$element.removeClass(this.options.refreshClass).removeClass(this.options.loadingClass).removeClass(this.options.loadedClass).removeClass(this.options.rtlClass).removeClass(this.options.dragClass).removeClass(this.options.grabClass).attr("class",this.$element.attr("class").replace(new RegExp(this.options.responsiveClass+"-\\S+\\s","g"),"")).removeData("owl.carousel")},l.prototype.op=function(t,e,i){var s=this.settings.rtl;switch(e){case"<":return s?i<t:t<i;case">":return s?t<i:i<t;case">=":return s?t<=i:i<=t;case"<=":return s?i<=t:t<=i}},l.prototype.on=function(t,e,i,s){t.addEventListener?t.addEventListener(e,i,s):t.attachEvent&&t.attachEvent("on"+e,i)},l.prototype.off=function(t,e,i,s){t.removeEventListener?t.removeEventListener(e,i,s):t.detachEvent&&t.detachEvent("on"+e,i)},l.prototype.trigger=function(t,e,i,s,n){var o={item:{count:this._items.length,index:this.current()}},r=h.camelCase(h.grep(["on",t,i],function(t){return t}).join("-").toLowerCase()),a=h.Event([t,"owl",i||"carousel"].join(".").toLowerCase(),h.extend({relatedTarget:this},o,e));return this._supress[t]||(h.each(this._plugins,function(t,e){e.onTrigger&&e.onTrigger(a)}),this.register({type:l.Type.Event,name:t}),this.$element.trigger(a),this.settings&&"function"==typeof this.settings[r]&&this.settings[r].call(this,a)),a},l.prototype.enter=function(t){h.each([t].concat(this._states.tags[t]||[]),h.proxy(function(t,e){this._states.current[e]===a&&(this._states.current[e]=0),this._states.current[e]++},this))},l.prototype.leave=function(t){h.each([t].concat(this._states.tags[t]||[]),h.proxy(function(t,e){this._states.current[e]--},this))},l.prototype.register=function(i){if(i.type===l.Type.Event){if(h.event.special[i.name]||(h.event.special[i.name]={}),!h.event.special[i.name].owl){var e=h.event.special[i.name]._default;h.event.special[i.name]._default=function(t){return!e||!e.apply||t.namespace&&-1!==t.namespace.indexOf("owl")?t.namespace&&-1<t.namespace.indexOf("owl"):e.apply(this,arguments)},h.event.special[i.name].owl=!0}}else i.type===l.Type.State&&(this._states.tags[i.name]?this._states.tags[i.name]=this._states.tags[i.name].concat(i.tags):this._states.tags[i.name]=i.tags,this._states.tags[i.name]=h.grep(this._states.tags[i.name],h.proxy(function(t,e){return h.inArray(t,this._states.tags[i.name])===e},this)))},l.prototype.suppress=function(t){h.each(t,h.proxy(function(t,e){this._supress[e]=!0},this))},l.prototype.release=function(t){h.each(t,h.proxy(function(t,e){delete this._supress[e]},this))},l.prototype.pointer=function(t){var e={x:null,y:null};return(t=(t=t.originalEvent||t||i.event).touches&&t.touches.length?t.touches[0]:t.changedTouches&&t.changedTouches.length?t.changedTouches[0]:t).pageX?(e.x=t.pageX,e.y=t.pageY):(e.x=t.clientX,e.y=t.clientY),e},l.prototype.isNumeric=function(t){return!isNaN(parseFloat(t))},l.prototype.difference=function(t,e){return{x:t.x-e.x,y:t.y-e.y}},h.fn.owlCarousel=function(e){var s=Array.prototype.slice.call(arguments,1);return this.each(function(){var t=h(this),i=t.data("owl.carousel");i||(i=new l(this,"object"==typeof e&&e),t.data("owl.carousel",i),h.each(["next","prev","to","destroy","refresh","replace","add","remove"],function(t,e){i.register({type:l.Type.Event,name:e}),i.$element.on(e+".owl.carousel.core",h.proxy(function(t){t.namespace&&t.relatedTarget!==this&&(this.suppress([e]),i[e].apply(this,[].slice.call(arguments,1)),this.release([e]))},i))})),"string"==typeof e&&"_"!==e.charAt(0)&&i[e].apply(i,s)})},h.fn.owlCarousel.Constructor=l}(window.Zepto||window.jQuery,window,document),function(e,i,t,s){var n=function(t){this._core=t,this._interval=null,this._visible=null,this._handlers={"initialized.owl.carousel":e.proxy(function(t){t.namespace&&this._core.settings.autoRefresh&&this.watch()},this)},this._core.options=e.extend({},n.Defaults,this._core.options),this._core.$element.on(this._handlers)};n.Defaults={autoRefresh:!0,autoRefreshInterval:500},n.prototype.watch=function(){this._interval||(this._visible=this._core.isVisible(),this._interval=i.setInterval(e.proxy(this.refresh,this),this._core.settings.autoRefreshInterval))},n.prototype.refresh=function(){this._core.isVisible()!==this._visible&&(this._visible=!this._visible,this._core.$element.toggleClass("owl-hidden",!this._visible),this._visible&&this._core.invalidate("width")&&this._core.refresh())},n.prototype.destroy=function(){var t,e;for(t in i.clearInterval(this._interval),this._handlers)this._core.$element.off(t,this._handlers[t]);for(e in Object.getOwnPropertyNames(this))"function"!=typeof this[e]&&(this[e]=null)},e.fn.owlCarousel.Constructor.Plugins.AutoRefresh=n}(window.Zepto||window.jQuery,window,document),function(a,o,t,e){var i=function(t){this._core=t,this._loaded=[],this._handlers={"initialized.owl.carousel change.owl.carousel resized.owl.carousel":a.proxy(function(t){if(t.namespace&&this._core.settings&&this._core.settings.lazyLoad&&(t.property&&"position"==t.property.name||"initialized"==t.type)){var e=this._core.settings,i=e.center&&Math.ceil(e.items/2)||e.items,s=e.center&&-1*i||0,n=(t.property&&void 0!==t.property.value?t.property.value:this._core.current())+s,o=this._core.clones().length,r=a.proxy(function(t,e){this.load(e)},this);for(0<e.lazyLoadEager&&(i+=e.lazyLoadEager,e.loop&&(n-=e.lazyLoadEager,i++));s++<i;)this.load(o/2+this._core.relative(n)),o&&a.each(this._core.clones(this._core.relative(n)),r),n++}},this)},this._core.options=a.extend({},i.Defaults,this._core.options),this._core.$element.on(this._handlers)};i.Defaults={lazyLoad:!1,lazyLoadEager:0},i.prototype.load=function(t){var e=this._core.$stage.children().eq(t),i=e&&e.find(".owl-lazy");!i||-1<a.inArray(e.get(0),this._loaded)||(i.each(a.proxy(function(t,e){var i,s=a(e),n=1<o.devicePixelRatio&&s.attr("data-src-retina")||s.attr("data-src")||s.attr("data-srcset");this._core.trigger("load",{element:s,url:n},"lazy"),s.is("img")?s.one("load.owl.lazy",a.proxy(function(){s.css("opacity",1),this._core.trigger("loaded",{element:s,url:n},"lazy")},this)).attr("src",n):s.is("source")?s.one("load.owl.lazy",a.proxy(function(){this._core.trigger("loaded",{element:s,url:n},"lazy")},this)).attr("srcset",n):((i=new Image).onload=a.proxy(function(){s.css({"background-image":'url("'+n+'")',opacity:"1"}),this._core.trigger("loaded",{element:s,url:n},"lazy")},this),i.src=n)},this)),this._loaded.push(e.get(0)))},i.prototype.destroy=function(){var t,e;for(t in this.handlers)this._core.$element.off(t,this.handlers[t]);for(e in Object.getOwnPropertyNames(this))"function"!=typeof this[e]&&(this[e]=null)},a.fn.owlCarousel.Constructor.Plugins.Lazy=i}(window.Zepto||window.jQuery,window,document),function(r,i,t,e){var s=function(t){this._core=t,this._previousHeight=null,this._handlers={"initialized.owl.carousel refreshed.owl.carousel":r.proxy(function(t){t.namespace&&this._core.settings.autoHeight&&this.update()},this),"changed.owl.carousel":r.proxy(function(t){t.namespace&&this._core.settings.autoHeight&&"position"===t.property.name&&this.update()},this),"loaded.owl.lazy":r.proxy(function(t){t.namespace&&this._core.settings.autoHeight&&t.element.closest("."+this._core.settings.itemClass).index()===this._core.current()&&this.update()},this)},this._core.options=r.extend({},s.Defaults,this._core.options),this._core.$element.on(this._handlers),this._intervalId=null;var e=this;r(i).on("load",function(){e._core.settings.autoHeight&&e.update()}),r(i).resize(function(){e._core.settings.autoHeight&&(null!=e._intervalId&&clearTimeout(e._intervalId),e._intervalId=setTimeout(function(){e.update()},250))})};s.Defaults={autoHeight:!1,autoHeightClass:"owl-height"},s.prototype.update=function(){var t=this._core._current,e=t+this._core.settings.items,i=this._core.settings.lazyLoad,s=this._core.$stage.children().toArray().slice(t,e),n=[],o=0;r.each(s,function(t,e){n.push(r(e).height())}),(o=Math.max.apply(null,n))<=1&&i&&this._previousHeight&&(o=this._previousHeight),this._previousHeight=o,this._core.$stage.parent().height(o).addClass(this._core.settings.autoHeightClass)},s.prototype.destroy=function(){var t,e;for(t in this._handlers)this._core.$element.off(t,this._handlers[t]);for(e in Object.getOwnPropertyNames(this))"function"!=typeof this[e]&&(this[e]=null)},r.fn.owlCarousel.Constructor.Plugins.AutoHeight=s}(window.Zepto||window.jQuery,window,document),function(c,t,e,i){var s=function(t){this._core=t,this._videos={},this._playing=null,this._handlers={"initialized.owl.carousel":c.proxy(function(t){t.namespace&&this._core.register({type:"state",name:"playing",tags:["interacting"]})},this),"resize.owl.carousel":c.proxy(function(t){t.namespace&&this._core.settings.video&&this.isInFullScreen()&&t.preventDefault()},this),"refreshed.owl.carousel":c.proxy(function(t){t.namespace&&this._core.is("resizing")&&this._core.$stage.find(".cloned .owl-video-frame").remove()},this),"changed.owl.carousel":c.proxy(function(t){t.namespace&&"position"===t.property.name&&this._playing&&this.stop()},this),"prepared.owl.carousel":c.proxy(function(t){if(t.namespace){var e=c(t.content).find(".owl-video");e.length&&(e.css("display","none"),this.fetch(e,c(t.content)))}},this)},this._core.options=c.extend({},s.Defaults,this._core.options),this._core.$element.on(this._handlers),this._core.$element.on("click.owl.video",".owl-video-play-icon",c.proxy(function(t){this.play(t)},this))};s.Defaults={video:!1,videoHeight:!1,videoWidth:!1},s.prototype.fetch=function(t,e){var i=t.attr("data-vimeo-id")?"vimeo":t.attr("data-vzaar-id")?"vzaar":"youtube",s=t.attr("data-vimeo-id")||t.attr("data-youtube-id")||t.attr("data-vzaar-id"),n=t.attr("data-width")||this._core.settings.videoWidth,o=t.attr("data-height")||this._core.settings.videoHeight,r=t.attr("href");if(!r)throw new Error("Missing video URL.");if(-1<(s=r.match(/(http:|https:|)\/\/(player.|www.|app.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com|be\-nocookie\.com)|vzaar\.com)\/(video\/|videos\/|embed\/|channels\/.+\/|groups\/.+\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/))[3].indexOf("youtu"))i="youtube";else if(-1<s[3].indexOf("vimeo"))i="vimeo";else{if(!(-1<s[3].indexOf("vzaar")))throw new Error("Video URL not supported.");i="vzaar"}s=s[6],this._videos[r]={type:i,id:s,width:n,height:o},e.attr("data-video",r),this.thumbnail(t,this._videos[r])},s.prototype.thumbnail=function(e,t){var i,s,n=t.width&&t.height?"width:"+t.width+"px;height:"+t.height+"px;":"",o=e.find("img"),r="src",a="",h=this._core.settings,l=function(t){'<div class="owl-video-play-icon"></div>',i=h.lazyLoad?c("<div/>",{class:"owl-video-tn "+a,srcType:t}):c("<div/>",{class:"owl-video-tn",style:"opacity:1;background-image:url("+t+")"}),e.after(i),e.after('<div class="owl-video-play-icon"></div>')};if(e.wrap(c("<div/>",{class:"owl-video-wrapper",style:n})),this._core.settings.lazyLoad&&(r="data-src",a="owl-lazy"),o.length)return l(o.attr(r)),o.remove(),!1;"youtube"===t.type?(s="//img.youtube.com/vi/"+t.id+"/hqdefault.jpg",l(s)):"vimeo"===t.type?c.ajax({type:"GET",url:"//vimeo.com/api/v2/video/"+t.id+".json",jsonp:"callback",dataType:"jsonp",success:function(t){s=t[0].thumbnail_large,l(s)}}):"vzaar"===t.type&&c.ajax({type:"GET",url:"//vzaar.com/api/videos/"+t.id+".json",jsonp:"callback",dataType:"jsonp",success:function(t){s=t.framegrab_url,l(s)}})},s.prototype.stop=function(){this._core.trigger("stop",null,"video"),this._playing.find(".owl-video-frame").remove(),this._playing.removeClass("owl-video-playing"),this._playing=null,this._core.leave("playing"),this._core.trigger("stopped",null,"video")},s.prototype.play=function(t){var e,i=c(t.target).closest("."+this._core.settings.itemClass),s=this._videos[i.attr("data-video")],n=s.width||"100%",o=s.height||this._core.$stage.height();this._playing||(this._core.enter("playing"),this._core.trigger("play",null,"video"),i=this._core.items(this._core.relative(i.index())),this._core.reset(i.index()),(e=c('<iframe frameborder="0" allowfullscreen mozallowfullscreen webkitAllowFullScreen ></iframe>')).attr("height",o),e.attr("width",n),"youtube"===s.type?e.attr("src","//www.youtube.com/embed/"+s.id+"?autoplay=1&rel=0&v="+s.id):"vimeo"===s.type?e.attr("src","//player.vimeo.com/video/"+s.id+"?autoplay=1"):"vzaar"===s.type&&e.attr("src","//view.vzaar.com/"+s.id+"/player?autoplay=true"),c(e).wrap('<div class="owl-video-frame" />').insertAfter(i.find(".owl-video")),this._playing=i.addClass("owl-video-playing"))},s.prototype.isInFullScreen=function(){var t=e.fullscreenElement||e.mozFullScreenElement||e.webkitFullscreenElement;return t&&c(t).parent().hasClass("owl-video-frame")},s.prototype.destroy=function(){var t,e;for(t in this._core.$element.off("click.owl.video"),this._handlers)this._core.$element.off(t,this._handlers[t]);for(e in Object.getOwnPropertyNames(this))"function"!=typeof this[e]&&(this[e]=null)},c.fn.owlCarousel.Constructor.Plugins.Video=s}(window.Zepto||window.jQuery,window,document),function(r,t,e,i){var s=function(t){this.core=t,this.core.options=r.extend({},s.Defaults,this.core.options),this.swapping=!0,this.previous=void 0,this.next=void 0,this.handlers={"change.owl.carousel":r.proxy(function(t){t.namespace&&"position"==t.property.name&&(this.previous=this.core.current(),this.next=t.property.value)},this),"drag.owl.carousel dragged.owl.carousel translated.owl.carousel":r.proxy(function(t){t.namespace&&(this.swapping="translated"==t.type)},this),"translate.owl.carousel":r.proxy(function(t){t.namespace&&this.swapping&&(this.core.options.animateOut||this.core.options.animateIn)&&this.swap()},this)},this.core.$element.on(this.handlers)};s.Defaults={animateOut:!1,animateIn:!1},s.prototype.swap=function(){if(1===this.core.settings.items&&r.support.animation&&r.support.transition){this.core.speed(0);var t,e=r.proxy(this.clear,this),i=this.core.$stage.children().eq(this.previous),s=this.core.$stage.children().eq(this.next),n=this.core.settings.animateIn,o=this.core.settings.animateOut;this.core.current()!==this.previous&&(o&&(t=this.core.coordinates(this.previous)-this.core.coordinates(this.next),i.one(r.support.animation.end,e).css({left:t+"px"}).addClass("animated owl-animated-out").addClass(o)),n&&s.one(r.support.animation.end,e).addClass("animated owl-animated-in").addClass(n))}},s.prototype.clear=function(t){r(t.target).css({left:""}).removeClass("animated owl-animated-out owl-animated-in").removeClass(this.core.settings.animateIn).removeClass(this.core.settings.animateOut),this.core.onTransitionEnd()},s.prototype.destroy=function(){var t,e;for(t in this.handlers)this.core.$element.off(t,this.handlers[t]);for(e in Object.getOwnPropertyNames(this))"function"!=typeof this[e]&&(this[e]=null)},r.fn.owlCarousel.Constructor.Plugins.Animate=s}(window.Zepto||window.jQuery,window,document),function(s,n,e,t){var i=function(t){this._core=t,this._call=null,this._time=0,this._timeout=0,this._paused=!0,this._handlers={"changed.owl.carousel":s.proxy(function(t){t.namespace&&"settings"===t.property.name?this._core.settings.autoplay?this.play():this.stop():t.namespace&&"position"===t.property.name&&this._paused&&(this._time=0)},this),"initialized.owl.carousel":s.proxy(function(t){t.namespace&&this._core.settings.autoplay&&this.play()},this),"play.owl.autoplay":s.proxy(function(t,e,i){t.namespace&&this.play(e,i)},this),"stop.owl.autoplay":s.proxy(function(t){t.namespace&&this.stop()},this),"mouseover.owl.autoplay":s.proxy(function(){this._core.settings.autoplayHoverPause&&this._core.is("rotating")&&this.pause()},this),"mouseleave.owl.autoplay":s.proxy(function(){this._core.settings.autoplayHoverPause&&this._core.is("rotating")&&this.play()},this),"touchstart.owl.core":s.proxy(function(){this._core.settings.autoplayHoverPause&&this._core.is("rotating")&&this.pause()},this),"touchend.owl.core":s.proxy(function(){this._core.settings.autoplayHoverPause&&this.play()},this)},this._core.$element.on(this._handlers),this._core.options=s.extend({},i.Defaults,this._core.options)};i.Defaults={autoplay:!1,autoplayTimeout:5e3,autoplayHoverPause:!1,autoplaySpeed:!1},i.prototype._next=function(t){this._call=n.setTimeout(s.proxy(this._next,this,t),this._timeout*(Math.round(this.read()/this._timeout)+1)-this.read()),this._core.is("interacting")||e.hidden||this._core.next(t||this._core.settings.autoplaySpeed)},i.prototype.read=function(){return(new Date).getTime()-this._time},i.prototype.play=function(t,e){var i;this._core.is("rotating")||this._core.enter("rotating"),t=t||this._core.settings.autoplayTimeout,i=Math.min(this._time%(this._timeout||t),t),this._paused?(this._time=this.read(),this._paused=!1):n.clearTimeout(this._call),this._time+=this.read()%t-i,this._timeout=t,this._call=n.setTimeout(s.proxy(this._next,this,e),t-i)},i.prototype.stop=function(){this._core.is("rotating")&&(this._time=0,this._paused=!0,n.clearTimeout(this._call),this._core.leave("rotating"))},i.prototype.pause=function(){this._core.is("rotating")&&!this._paused&&(this._time=this.read(),this._paused=!0,n.clearTimeout(this._call))},i.prototype.destroy=function(){var t,e;for(t in this.stop(),this._handlers)this._core.$element.off(t,this._handlers[t]);for(e in Object.getOwnPropertyNames(this))"function"!=typeof this[e]&&(this[e]=null)},s.fn.owlCarousel.Constructor.Plugins.autoplay=i}(window.Zepto||window.jQuery,window,document),function(o,t,e,i){"use strict";var s=function(t){this._core=t,this._initialized=!1,this._pages=[],this._controls={},this._templates=[],this.$element=this._core.$element,this._overrides={next:this._core.next,prev:this._core.prev,to:this._core.to},this._handlers={"prepared.owl.carousel":o.proxy(function(t){t.namespace&&this._core.settings.dotsData&&this._templates.push('<div class="'+this._core.settings.dotClass+'">'+o(t.content).find("[data-dot]").addBack("[data-dot]").attr("data-dot")+"</div>")},this),"added.owl.carousel":o.proxy(function(t){t.namespace&&this._core.settings.dotsData&&this._templates.splice(t.position,0,this._templates.pop())},this),"remove.owl.carousel":o.proxy(function(t){t.namespace&&this._core.settings.dotsData&&this._templates.splice(t.position,1)},this),"changed.owl.carousel":o.proxy(function(t){t.namespace&&"position"==t.property.name&&this.draw()},this),"initialized.owl.carousel":o.proxy(function(t){t.namespace&&!this._initialized&&(this._core.trigger("initialize",null,"navigation"),this.initialize(),this.update(),this.draw(),this._initialized=!0,this._core.trigger("initialized",null,"navigation"))},this),"refreshed.owl.carousel":o.proxy(function(t){t.namespace&&this._initialized&&(this._core.trigger("refresh",null,"navigation"),this.update(),this.draw(),this._core.trigger("refreshed",null,"navigation"))},this)},this._core.options=o.extend({},s.Defaults,this._core.options),this.$element.on(this._handlers)};s.Defaults={nav:!1,navText:['<span aria-label="Previous">&#x2039;</span>','<span aria-label="Next">&#x203a;</span>'],navSpeed:!1,navElement:'button type="button" role="presentation"',navContainer:!1,navContainerClass:"owl-nav",navClass:["owl-prev","owl-next"],slideBy:1,dotClass:"owl-dot",dotsClass:"owl-dots",dots:!0,dotsEach:!1,dotsData:!1,dotsSpeed:!1,dotsContainer:!1},s.prototype.initialize=function(){var t,i=this._core.settings;for(t in this._controls.$relative=(i.navContainer?o(i.navContainer):o("<div>").addClass(i.navContainerClass).appendTo(this.$element)).addClass("disabled"),this._controls.$previous=o("<"+i.navElement+">").addClass(i.navClass[0]).html(i.navText[0]).prependTo(this._controls.$relative).on("click",o.proxy(function(t){this.prev(i.navSpeed)},this)),this._controls.$next=o("<"+i.navElement+">").addClass(i.navClass[1]).html(i.navText[1]).appendTo(this._controls.$relative).on("click",o.proxy(function(t){this.next(i.navSpeed)},this)),i.dotsData||(this._templates=[o('<button role="button">').addClass(i.dotClass).append(o("<span>")).prop("outerHTML")]),this._controls.$absolute=(i.dotsContainer?o(i.dotsContainer):o("<div>").addClass(i.dotsClass).appendTo(this.$element)).addClass("disabled"),this._controls.$absolute.on("click","button",o.proxy(function(t){var e=o(t.target).parent().is(this._controls.$absolute)?o(t.target).index():o(t.target).parent().index();t.preventDefault(),this.to(e,i.dotsSpeed)},this)),this._overrides)this._core[t]=o.proxy(this[t],this)},s.prototype.destroy=function(){var t,e,i,s,n;for(t in n=this._core.settings,this._handlers)this.$element.off(t,this._handlers[t]);for(e in this._controls)"$relative"===e&&n.navContainer?this._controls[e].html(""):this._controls[e].remove();for(s in this.overides)this._core[s]=this._overrides[s];for(i in Object.getOwnPropertyNames(this))"function"!=typeof this[i]&&(this[i]=null)},s.prototype.update=function(){var t,e,i=this._core.clones().length/2,s=i+this._core.items().length,n=this._core.maximum(!0),o=this._core.settings,r=o.center||o.autoWidth||o.dotsData?1:o.dotsEach||o.items;if("page"!==o.slideBy&&(o.slideBy=Math.min(o.slideBy,o.items)),o.dots||"page"==o.slideBy)for(this._pages=[],t=i,e=0;t<s;t++){if(r<=e||0===e){if(this._pages.push({start:Math.min(n,t-i),end:t-i+r-1}),Math.min(n,t-i)===n)break;e=0,0}e+=this._core.mergers(this._core.relative(t))}},s.prototype.draw=function(){var t,e=this._core.settings,i=this._core.items().length<=e.items,s=this._core.relative(this._core.current()),n=e.loop||e.rewind;this._controls.$relative.toggleClass("disabled",!e.nav||i),e.nav&&(this._controls.$previous.toggleClass("disabled",!n&&s<=this._core.minimum(!0)),this._controls.$next.toggleClass("disabled",!n&&s>=this._core.maximum(!0))),this._controls.$absolute.toggleClass("disabled",!e.dots||i),e.dots&&(t=this._pages.length-this._controls.$absolute.children().length,e.dotsData&&0!==t?this._controls.$absolute.html(this._templates.join("")):0<t?this._controls.$absolute.append(new Array(t+1).join(this._templates[0])):t<0&&this._controls.$absolute.children().slice(t).remove(),this._controls.$absolute.find(".active").removeClass("active"),this._controls.$absolute.children().eq(o.inArray(this.current(),this._pages)).addClass("active"))},s.prototype.onTrigger=function(t){var e=this._core.settings;t.page={index:o.inArray(this.current(),this._pages),count:this._pages.length,size:e&&(e.center||e.autoWidth||e.dotsData?1:e.dotsEach||e.items)}},s.prototype.current=function(){var i=this._core.relative(this._core.current());return o.grep(this._pages,o.proxy(function(t,e){return t.start<=i&&t.end>=i},this)).pop()},s.prototype.getPosition=function(t){var e,i,s=this._core.settings;return"page"==s.slideBy?(e=o.inArray(this.current(),this._pages),i=this._pages.length,t?++e:--e,e=this._pages[(e%i+i)%i].start):(e=this._core.relative(this._core.current()),i=this._core.items().length,t?e+=s.slideBy:e-=s.slideBy),e},s.prototype.next=function(t){o.proxy(this._overrides.to,this._core)(this.getPosition(!0),t)},s.prototype.prev=function(t){o.proxy(this._overrides.to,this._core)(this.getPosition(!1),t)},s.prototype.to=function(t,e,i){var s;!i&&this._pages.length?(s=this._pages.length,o.proxy(this._overrides.to,this._core)(this._pages[(t%s+s)%s].start,e)):o.proxy(this._overrides.to,this._core)(t,e)},o.fn.owlCarousel.Constructor.Plugins.Navigation=s}(window.Zepto||window.jQuery,window,document),function(s,n,t,e){"use strict";var i=function(t){this._core=t,this._hashes={},this.$element=this._core.$element,this._handlers={"initialized.owl.carousel":s.proxy(function(t){t.namespace&&"URLHash"===this._core.settings.startPosition&&s(n).trigger("hashchange.owl.navigation")},this),"prepared.owl.carousel":s.proxy(function(t){if(t.namespace){var e=s(t.content).find("[data-hash]").addBack("[data-hash]").attr("data-hash");if(!e)return;this._hashes[e]=t.content}},this),"changed.owl.carousel":s.proxy(function(t){if(t.namespace&&"position"===t.property.name){var i=this._core.items(this._core.relative(this._core.current())),e=s.map(this._hashes,function(t,e){return t===i?e:null}).join();if(!e||n.location.hash.slice(1)===e)return;n.location.hash=e}},this)},this._core.options=s.extend({},i.Defaults,this._core.options),this.$element.on(this._handlers),s(n).on("hashchange.owl.navigation",s.proxy(function(t){var e=n.location.hash.substring(1),i=this._core.$stage.children(),s=this._hashes[e]&&i.index(this._hashes[e]);void 0!==s&&s!==this._core.current()&&this._core.to(this._core.relative(s),!1,!0)},this))};i.Defaults={URLhashListener:!1},i.prototype.destroy=function(){var t,e;for(t in s(n).off("hashchange.owl.navigation"),this._handlers)this._core.$element.off(t,this._handlers[t]);for(e in Object.getOwnPropertyNames(this))"function"!=typeof this[e]&&(this[e]=null)},s.fn.owlCarousel.Constructor.Plugins.Hash=i}(window.Zepto||window.jQuery,window,document),function(n,t,e,o){var r=n("<support>").get(0).style,a="Webkit Moz O ms".split(" "),i={transition:{end:{WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd",transition:"transitionend"}},animation:{end:{WebkitAnimation:"webkitAnimationEnd",MozAnimation:"animationend",OAnimation:"oAnimationEnd",animation:"animationend"}}},s=function(){return!!c("transform")},h=function(){return!!c("perspective")},l=function(){return!!c("animation")};function c(t,i){var s=!1,e=t.charAt(0).toUpperCase()+t.slice(1);return n.each((t+" "+a.join(e+" ")+e).split(" "),function(t,e){if(r[e]!==o)return s=!i||e,!1}),s}function p(t){return c(t,!0)}(function(){return!!c("transition")})()&&(n.support.transition=new String(p("transition")),n.support.transition.end=i.transition.end[n.support.transition]),l()&&(n.support.animation=new String(p("animation")),n.support.animation.end=i.animation.end[n.support.animation]),s()&&(n.support.transform=new String(p("transform")),n.support.transform3d=h())}(window.Zepto||window.jQuery,window,document);

/*
 * jQuery FlexSlider v2.6.1
 * Copyright 2012 WooThemes
 * Contributing Author: Tyler Smith
 */!function($){var e=!0;$.flexslider=function(t,a){var n=$(t);n.vars=$.extend({},$.flexslider.defaults,a);var i=n.vars.namespace,s=window.navigator&&window.navigator.msPointerEnabled&&window.MSGesture,r=("ontouchstart"in window||s||window.DocumentTouch&&document instanceof DocumentTouch)&&n.vars.touch,o="click touchend MSPointerUp keyup",l="",c,d="vertical"===n.vars.direction,u=n.vars.reverse,v=n.vars.itemWidth>0,p="fade"===n.vars.animation,m=""!==n.vars.asNavFor,f={};$.data(t,"flexslider",n),f={init:function(){n.animating=!1,n.currentSlide=parseInt(n.vars.startAt?n.vars.startAt:0,10),isNaN(n.currentSlide)&&(n.currentSlide=0),n.animatingTo=n.currentSlide,n.atEnd=0===n.currentSlide||n.currentSlide===n.last,n.containerSelector=n.vars.selector.substr(0,n.vars.selector.search(" ")),n.slides=$(n.vars.selector,n),n.container=$(n.containerSelector,n),n.count=n.slides.length,n.syncExists=$(n.vars.sync).length>0,"slide"===n.vars.animation&&(n.vars.animation="swing"),n.prop=d?"top":"marginLeft",n.args={},n.manualPause=!1,n.stopped=!1,n.started=!1,n.startTimeout=null,n.transitions=!n.vars.video&&!p&&n.vars.useCSS&&function(){var e=document.createElement("div"),t=["perspectiveProperty","WebkitPerspective","MozPerspective","OPerspective","msPerspective"];for(var a in t)if(void 0!==e.style[t[a]])return n.pfx=t[a].replace("Perspective","").toLowerCase(),n.prop="-"+n.pfx+"-transform",!0;return!1}(),n.ensureAnimationEnd="",""!==n.vars.controlsContainer&&(n.controlsContainer=$(n.vars.controlsContainer).length>0&&$(n.vars.controlsContainer)),""!==n.vars.manualControls&&(n.manualControls=$(n.vars.manualControls).length>0&&$(n.vars.manualControls)),""!==n.vars.customDirectionNav&&(n.customDirectionNav=2===$(n.vars.customDirectionNav).length&&$(n.vars.customDirectionNav)),n.vars.randomize&&(n.slides.sort(function(){return Math.round(Math.random())-.5}),n.container.empty().append(n.slides)),n.doMath(),n.setup("init"),n.vars.controlNav&&f.controlNav.setup(),n.vars.directionNav&&f.directionNav.setup(),n.vars.keyboard&&(1===$(n.containerSelector).length||n.vars.multipleKeyboard)&&$(document).bind("keyup",function(e){var t=e.keyCode;if(!n.animating&&(39===t||37===t)){var a=39===t?n.getTarget("next"):37===t?n.getTarget("prev"):!1;n.flexAnimate(a,n.vars.pauseOnAction)}}),n.vars.mousewheel&&n.bind("mousewheel",function(e,t,a,i){e.preventDefault();var s=0>t?n.getTarget("next"):n.getTarget("prev");n.flexAnimate(s,n.vars.pauseOnAction)}),n.vars.pausePlay&&f.pausePlay.setup(),n.vars.slideshow&&n.vars.pauseInvisible&&f.pauseInvisible.init(),n.vars.slideshow&&(n.vars.pauseOnHover&&n.hover(function(){n.manualPlay||n.manualPause||n.pause()},function(){n.manualPause||n.manualPlay||n.stopped||n.play()}),n.vars.pauseInvisible&&f.pauseInvisible.isHidden()||(n.vars.initDelay>0?n.startTimeout=setTimeout(n.play,n.vars.initDelay):n.play())),m&&f.asNav.setup(),r&&n.vars.touch&&f.touch(),(!p||p&&n.vars.smoothHeight)&&$(window).bind("resize orientationchange focus",f.resize),n.find("img").attr("draggable","false"),setTimeout(function(){n.vars.start(n)},200)},asNav:{setup:function(){n.asNav=!0,n.animatingTo=Math.floor(n.currentSlide/n.move),n.currentItem=n.currentSlide,n.slides.removeClass(i+"active-slide").eq(n.currentItem).addClass(i+"active-slide"),s?(t._slider=n,n.slides.each(function(){var e=this;e._gesture=new MSGesture,e._gesture.target=e,e.addEventListener("MSPointerDown",function(e){e.preventDefault(),e.currentTarget._gesture&&e.currentTarget._gesture.addPointer(e.pointerId)},!1),e.addEventListener("MSGestureTap",function(e){e.preventDefault();var t=$(this),a=t.index();$(n.vars.asNavFor).data("flexslider").animating||t.hasClass("active")||(n.direction=n.currentItem<a?"next":"prev",n.flexAnimate(a,n.vars.pauseOnAction,!1,!0,!0))})})):n.slides.on(o,function(e){e.preventDefault();var t=$(this),a=t.index(),s=t.offset().left-$(n).scrollLeft();0>=s&&t.hasClass(i+"active-slide")?n.flexAnimate(n.getTarget("prev"),!0):$(n.vars.asNavFor).data("flexslider").animating||t.hasClass(i+"active-slide")||(n.direction=n.currentItem<a?"next":"prev",n.flexAnimate(a,n.vars.pauseOnAction,!1,!0,!0))})}},controlNav:{setup:function(){n.manualControls?f.controlNav.setupManual():f.controlNav.setupPaging()},setupPaging:function(){var e="thumbnails"===n.vars.controlNav?"control-thumbs":"control-paging",t=1,a,s;if(n.controlNavScaffold=$('<ol class="'+i+"control-nav "+i+e+'"></ol>'),n.pagingCount>1)for(var r=0;r<n.pagingCount;r++){s=n.slides.eq(r),void 0===s.attr("data-thumb-alt")&&s.attr("data-thumb-alt","");var c=""!==s.attr("data-thumb-alt")?c=' alt="'+s.attr("data-thumb-alt")+'"':"";if(a="thumbnails"===n.vars.controlNav?'<img src="'+s.attr("data-thumb")+'"'+c+"/>":'<a href="#">'+t+"</a>","thumbnails"===n.vars.controlNav&&!0===n.vars.thumbCaptions){var d=s.attr("data-thumbcaption");""!==d&&void 0!==d&&(a+='<span class="'+i+'caption">'+d+"</span>")}n.controlNavScaffold.append("<li>"+a+"</li>"),t++}n.controlsContainer?$(n.controlsContainer).append(n.controlNavScaffold):n.append(n.controlNavScaffold),f.controlNav.set(),f.controlNav.active(),n.controlNavScaffold.delegate("a, img",o,function(e){if(e.preventDefault(),""===l||l===e.type){var t=$(this),a=n.controlNav.index(t);t.hasClass(i+"active")||(n.direction=a>n.currentSlide?"next":"prev",n.flexAnimate(a,n.vars.pauseOnAction))}""===l&&(l=e.type),f.setToClearWatchedEvent()})},setupManual:function(){n.controlNav=n.manualControls,f.controlNav.active(),n.controlNav.bind(o,function(e){if(e.preventDefault(),""===l||l===e.type){var t=$(this),a=n.controlNav.index(t);t.hasClass(i+"active")||(a>n.currentSlide?n.direction="next":n.direction="prev",n.flexAnimate(a,n.vars.pauseOnAction))}""===l&&(l=e.type),f.setToClearWatchedEvent()})},set:function(){var e="thumbnails"===n.vars.controlNav?"img":"a";n.controlNav=$("."+i+"control-nav li "+e,n.controlsContainer?n.controlsContainer:n)},active:function(){n.controlNav.removeClass(i+"active").eq(n.animatingTo).addClass(i+"active")},update:function(e,t){n.pagingCount>1&&"add"===e?n.controlNavScaffold.append($('<li><a href="#">'+n.count+"</a></li>")):1===n.pagingCount?n.controlNavScaffold.find("li").remove():n.controlNav.eq(t).closest("li").remove(),f.controlNav.set(),n.pagingCount>1&&n.pagingCount!==n.controlNav.length?n.update(t,e):f.controlNav.active()}},directionNav:{setup:function(){var e=$('<ul class="'+i+'direction-nav"><li class="'+i+'nav-prev"><a class="'+i+'prev" href="#">'+n.vars.prevText+'</a></li><li class="'+i+'nav-next"><a class="'+i+'next" href="#">'+n.vars.nextText+"</a></li></ul>");n.customDirectionNav?n.directionNav=n.customDirectionNav:n.controlsContainer?($(n.controlsContainer).append(e),n.directionNav=$("."+i+"direction-nav li a",n.controlsContainer)):(n.append(e),n.directionNav=$("."+i+"direction-nav li a",n)),f.directionNav.update(),n.directionNav.bind(o,function(e){e.preventDefault();var t;(""===l||l===e.type)&&(t=$(this).hasClass(i+"next")?n.getTarget("next"):n.getTarget("prev"),n.flexAnimate(t,n.vars.pauseOnAction)),""===l&&(l=e.type),f.setToClearWatchedEvent()})},update:function(){var e=i+"disabled";1===n.pagingCount?n.directionNav.addClass(e).attr("tabindex","-1"):n.vars.animationLoop?n.directionNav.removeClass(e).removeAttr("tabindex"):0===n.animatingTo?n.directionNav.removeClass(e).filter("."+i+"prev").addClass(e).attr("tabindex","-1"):n.animatingTo===n.last?n.directionNav.removeClass(e).filter("."+i+"next").addClass(e).attr("tabindex","-1"):n.directionNav.removeClass(e).removeAttr("tabindex")}},pausePlay:{setup:function(){var e=$('<div class="'+i+'pauseplay"><a href="#"></a></div>');n.controlsContainer?(n.controlsContainer.append(e),n.pausePlay=$("."+i+"pauseplay a",n.controlsContainer)):(n.append(e),n.pausePlay=$("."+i+"pauseplay a",n)),f.pausePlay.update(n.vars.slideshow?i+"pause":i+"play"),n.pausePlay.bind(o,function(e){e.preventDefault(),(""===l||l===e.type)&&($(this).hasClass(i+"pause")?(n.manualPause=!0,n.manualPlay=!1,n.pause()):(n.manualPause=!1,n.manualPlay=!0,n.play())),""===l&&(l=e.type),f.setToClearWatchedEvent()})},update:function(e){"play"===e?n.pausePlay.removeClass(i+"pause").addClass(i+"play").html(n.vars.playText):n.pausePlay.removeClass(i+"play").addClass(i+"pause").html(n.vars.pauseText)}},touch:function(){function e(e){e.stopPropagation(),n.animating?e.preventDefault():(n.pause(),t._gesture.addPointer(e.pointerId),T=0,c=d?n.h:n.w,f=Number(new Date),l=v&&u&&n.animatingTo===n.last?0:v&&u?n.limit-(n.itemW+n.vars.itemMargin)*n.move*n.animatingTo:v&&n.currentSlide===n.last?n.limit:v?(n.itemW+n.vars.itemMargin)*n.move*n.currentSlide:u?(n.last-n.currentSlide+n.cloneOffset)*c:(n.currentSlide+n.cloneOffset)*c)}function a(e){e.stopPropagation();var a=e.target._slider;if(a){var n=-e.translationX,i=-e.translationY;return T+=d?i:n,m=T,y=d?Math.abs(T)<Math.abs(-n):Math.abs(T)<Math.abs(-i),e.detail===e.MSGESTURE_FLAG_INERTIA?void setImmediate(function(){t._gesture.stop()}):void((!y||Number(new Date)-f>500)&&(e.preventDefault(),!p&&a.transitions&&(a.vars.animationLoop||(m=T/(0===a.currentSlide&&0>T||a.currentSlide===a.last&&T>0?Math.abs(T)/c+2:1)),a.setProps(l+m,"setTouch"))))}}function i(e){e.stopPropagation();var t=e.target._slider;if(t){if(t.animatingTo===t.currentSlide&&!y&&null!==m){var a=u?-m:m,n=a>0?t.getTarget("next"):t.getTarget("prev");t.canAdvance(n)&&(Number(new Date)-f<550&&Math.abs(a)>50||Math.abs(a)>c/2)?t.flexAnimate(n,t.vars.pauseOnAction):p||t.flexAnimate(t.currentSlide,t.vars.pauseOnAction,!0)}r=null,o=null,m=null,l=null,T=0}}var r,o,l,c,m,f,g,h,S,y=!1,x=0,b=0,T=0;s?(t.style.msTouchAction="none",t._gesture=new MSGesture,t._gesture.target=t,t.addEventListener("MSPointerDown",e,!1),t._slider=n,t.addEventListener("MSGestureChange",a,!1),t.addEventListener("MSGestureEnd",i,!1)):(g=function(e){n.animating?e.preventDefault():(window.navigator.msPointerEnabled||1===e.touches.length)&&(n.pause(),c=d?n.h:n.w,f=Number(new Date),x=e.touches[0].pageX,b=e.touches[0].pageY,l=v&&u&&n.animatingTo===n.last?0:v&&u?n.limit-(n.itemW+n.vars.itemMargin)*n.move*n.animatingTo:v&&n.currentSlide===n.last?n.limit:v?(n.itemW+n.vars.itemMargin)*n.move*n.currentSlide:u?(n.last-n.currentSlide+n.cloneOffset)*c:(n.currentSlide+n.cloneOffset)*c,r=d?b:x,o=d?x:b,t.addEventListener("touchmove",h,!1),t.addEventListener("touchend",S,!1))},h=function(e){x=e.touches[0].pageX,b=e.touches[0].pageY,m=d?r-b:r-x,y=d?Math.abs(m)<Math.abs(x-o):Math.abs(m)<Math.abs(b-o);var t=500;(!y||Number(new Date)-f>t)&&(e.preventDefault(),!p&&n.transitions&&(n.vars.animationLoop||(m/=0===n.currentSlide&&0>m||n.currentSlide===n.last&&m>0?Math.abs(m)/c+2:1),n.setProps(l+m,"setTouch")))},S=function(e){if(t.removeEventListener("touchmove",h,!1),n.animatingTo===n.currentSlide&&!y&&null!==m){var a=u?-m:m,i=a>0?n.getTarget("next"):n.getTarget("prev");n.canAdvance(i)&&(Number(new Date)-f<550&&Math.abs(a)>50||Math.abs(a)>c/2)?n.flexAnimate(i,n.vars.pauseOnAction):p||n.flexAnimate(n.currentSlide,n.vars.pauseOnAction,!0)}t.removeEventListener("touchend",S,!1),r=null,o=null,m=null,l=null},t.addEventListener("touchstart",g,!1))},resize:function(){!n.animating&&n.is(":visible")&&(v||n.doMath(),p?f.smoothHeight():v?(n.slides.width(n.computedW),n.update(n.pagingCount),n.setProps()):d?(n.viewport.height(n.h),n.setProps(n.h,"setTotal")):(n.vars.smoothHeight&&f.smoothHeight(),n.newSlides.width(n.computedW),n.setProps(n.computedW,"setTotal")))},smoothHeight:function(e){if(!d||p){var t=p?n:n.viewport;e?t.animate({height:n.slides.eq(n.animatingTo).innerHeight()},e):t.innerHeight(n.slides.eq(n.animatingTo).innerHeight())}},sync:function(e){var t=$(n.vars.sync).data("flexslider"),a=n.animatingTo;switch(e){case"animate":t.flexAnimate(a,n.vars.pauseOnAction,!1,!0);break;case"play":t.playing||t.asNav||t.play();break;case"pause":t.pause()}},uniqueID:function(e){return e.filter("[id]").add(e.find("[id]")).each(function(){var e=$(this);e.attr("id",e.attr("id")+"_clone")}),e},pauseInvisible:{visProp:null,init:function(){var e=f.pauseInvisible.getHiddenProp();if(e){var t=e.replace(/[H|h]idden/,"")+"visibilitychange";document.addEventListener(t,function(){f.pauseInvisible.isHidden()?n.startTimeout?clearTimeout(n.startTimeout):n.pause():n.started?n.play():n.vars.initDelay>0?setTimeout(n.play,n.vars.initDelay):n.play()})}},isHidden:function(){var e=f.pauseInvisible.getHiddenProp();return e?document[e]:!1},getHiddenProp:function(){var e=["webkit","moz","ms","o"];if("hidden"in document)return"hidden";for(var t=0;t<e.length;t++)if(e[t]+"Hidden"in document)return e[t]+"Hidden";return null}},setToClearWatchedEvent:function(){clearTimeout(c),c=setTimeout(function(){l=""},3e3)}},n.flexAnimate=function(e,t,a,s,o){if(n.vars.animationLoop||e===n.currentSlide||(n.direction=e>n.currentSlide?"next":"prev"),m&&1===n.pagingCount&&(n.direction=n.currentItem<e?"next":"prev"),!n.animating&&(n.canAdvance(e,o)||a)&&n.is(":visible")){if(m&&s){var l=$(n.vars.asNavFor).data("flexslider");if(n.atEnd=0===e||e===n.count-1,l.flexAnimate(e,!0,!1,!0,o),n.direction=n.currentItem<e?"next":"prev",l.direction=n.direction,Math.ceil((e+1)/n.visible)-1===n.currentSlide||0===e)return n.currentItem=e,n.slides.removeClass(i+"active-slide").eq(e).addClass(i+"active-slide"),!1;n.currentItem=e,n.slides.removeClass(i+"active-slide").eq(e).addClass(i+"active-slide"),e=Math.floor(e/n.visible)}if(n.animating=!0,n.animatingTo=e,t&&n.pause(),n.vars.before(n),n.syncExists&&!o&&f.sync("animate"),n.vars.controlNav&&f.controlNav.active(),v||n.slides.removeClass(i+"active-slide").eq(e).addClass(i+"active-slide"),n.atEnd=0===e||e===n.last,n.vars.directionNav&&f.directionNav.update(),e===n.last&&(n.vars.end(n),n.vars.animationLoop||n.pause()),p)r?(n.slides.eq(n.currentSlide).css({opacity:0,zIndex:1}),n.slides.eq(e).css({opacity:1,zIndex:2}),n.wrapup(c)):(n.slides.eq(n.currentSlide).css({zIndex:1}).animate({opacity:0},n.vars.animationSpeed,n.vars.easing),n.slides.eq(e).css({zIndex:2}).animate({opacity:1},n.vars.animationSpeed,n.vars.easing,n.wrapup));else{var c=d?n.slides.filter(":first").height():n.computedW,g,h,S;v?(g=n.vars.itemMargin,S=(n.itemW+g)*n.move*n.animatingTo,h=S>n.limit&&1!==n.visible?n.limit:S):h=0===n.currentSlide&&e===n.count-1&&n.vars.animationLoop&&"next"!==n.direction?u?(n.count+n.cloneOffset)*c:0:n.currentSlide===n.last&&0===e&&n.vars.animationLoop&&"prev"!==n.direction?u?0:(n.count+1)*c:u?(n.count-1-e+n.cloneOffset)*c:(e+n.cloneOffset)*c,n.setProps(h,"",n.vars.animationSpeed),n.transitions?(n.vars.animationLoop&&n.atEnd||(n.animating=!1,n.currentSlide=n.animatingTo),n.container.unbind("webkitTransitionEnd transitionend"),n.container.bind("webkitTransitionEnd transitionend",function(){clearTimeout(n.ensureAnimationEnd),n.wrapup(c)}),clearTimeout(n.ensureAnimationEnd),n.ensureAnimationEnd=setTimeout(function(){n.wrapup(c)},n.vars.animationSpeed+100)):n.container.animate(n.args,n.vars.animationSpeed,n.vars.easing,function(){n.wrapup(c)})}n.vars.smoothHeight&&f.smoothHeight(n.vars.animationSpeed)}},n.wrapup=function(e){p||v||(0===n.currentSlide&&n.animatingTo===n.last&&n.vars.animationLoop?n.setProps(e,"jumpEnd"):n.currentSlide===n.last&&0===n.animatingTo&&n.vars.animationLoop&&n.setProps(e,"jumpStart")),n.animating=!1,n.currentSlide=n.animatingTo,n.vars.after(n)},n.animateSlides=function(){!n.animating&&e&&n.flexAnimate(n.getTarget("next"))},n.pause=function(){clearInterval(n.animatedSlides),n.animatedSlides=null,n.playing=!1,n.vars.pausePlay&&f.pausePlay.update("play"),n.syncExists&&f.sync("pause")},n.play=function(){n.playing&&clearInterval(n.animatedSlides),n.animatedSlides=n.animatedSlides||setInterval(n.animateSlides,n.vars.slideshowSpeed),n.started=n.playing=!0,n.vars.pausePlay&&f.pausePlay.update("pause"),n.syncExists&&f.sync("play")},n.stop=function(){n.pause(),n.stopped=!0},n.canAdvance=function(e,t){var a=m?n.pagingCount-1:n.last;return t?!0:m&&n.currentItem===n.count-1&&0===e&&"prev"===n.direction?!0:m&&0===n.currentItem&&e===n.pagingCount-1&&"next"!==n.direction?!1:e!==n.currentSlide||m?n.vars.animationLoop?!0:n.atEnd&&0===n.currentSlide&&e===a&&"next"!==n.direction?!1:n.atEnd&&n.currentSlide===a&&0===e&&"next"===n.direction?!1:!0:!1},n.getTarget=function(e){return n.direction=e,"next"===e?n.currentSlide===n.last?0:n.currentSlide+1:0===n.currentSlide?n.last:n.currentSlide-1},n.setProps=function(e,t,a){var i=function(){var a=e?e:(n.itemW+n.vars.itemMargin)*n.move*n.animatingTo,i=function(){if(v)return"setTouch"===t?e:u&&n.animatingTo===n.last?0:u?n.limit-(n.itemW+n.vars.itemMargin)*n.move*n.animatingTo:n.animatingTo===n.last?n.limit:a;switch(t){case"setTotal":return u?(n.count-1-n.currentSlide+n.cloneOffset)*e:(n.currentSlide+n.cloneOffset)*e;case"setTouch":return u?e:e;case"jumpEnd":return u?e:n.count*e;case"jumpStart":return u?n.count*e:e;default:return e}}();return-1*i+"px"}();n.transitions&&(i=d?"translate3d(0,"+i+",0)":"translate3d("+i+",0,0)",a=void 0!==a?a/1e3+"s":"0s",n.container.css("-"+n.pfx+"-transition-duration",a),n.container.css("transition-duration",a)),n.args[n.prop]=i,(n.transitions||void 0===a)&&n.container.css(n.args),n.container.css("transform",i)},n.setup=function(e){if(p)n.slides.css({width:"100%","float":"left",marginRight:"-100%",position:"relative"}),"init"===e&&(r?n.slides.css({opacity:0,display:"block",webkitTransition:"opacity "+n.vars.animationSpeed/1e3+"s ease",zIndex:1}).eq(n.currentSlide).css({opacity:1,zIndex:2}):0==n.vars.fadeFirstSlide?n.slides.css({opacity:0,display:"block",zIndex:1}).eq(n.currentSlide).css({zIndex:2}).css({opacity:1}):n.slides.css({opacity:0,display:"block",zIndex:1}).eq(n.currentSlide).css({zIndex:2}).animate({opacity:1},n.vars.animationSpeed,n.vars.easing)),n.vars.smoothHeight&&f.smoothHeight();else{var t,a;"init"===e&&(n.viewport=$('<div class="'+i+'viewport"></div>').css({overflow:"hidden",position:"relative"}).appendTo(n).append(n.container),n.cloneCount=0,n.cloneOffset=0,u&&(a=$.makeArray(n.slides).reverse(),n.slides=$(a),n.container.empty().append(n.slides))),n.vars.animationLoop&&!v&&(n.cloneCount=2,n.cloneOffset=1,"init"!==e&&n.container.find(".clone").remove(),n.container.append(f.uniqueID(n.slides.first().clone().addClass("clone")).attr("aria-hidden","true")).prepend(f.uniqueID(n.slides.last().clone().addClass("clone")).attr("aria-hidden","true"))),n.newSlides=$(n.vars.selector,n),t=u?n.count-1-n.currentSlide+n.cloneOffset:n.currentSlide+n.cloneOffset,d&&!v?(n.container.height(200*(n.count+n.cloneCount)+"%").css("position","absolute").width("100%"),setTimeout(function(){n.newSlides.css({display:"block"}),n.doMath(),n.viewport.height(n.h),n.setProps(t*n.h,"init")},"init"===e?100:0)):(n.container.width(200*(n.count+n.cloneCount)+"%"),n.setProps(t*n.computedW,"init"),setTimeout(function(){n.doMath(),n.newSlides.css({width:n.computedW,marginRight:n.computedM,"float":"left",display:"block"}),n.vars.smoothHeight&&f.smoothHeight()},"init"===e?100:0))}v||n.slides.removeClass(i+"active-slide").eq(n.currentSlide).addClass(i+"active-slide"),n.vars.init(n)},n.doMath=function(){var e=n.slides.first(),t=n.vars.itemMargin,a=n.vars.minItems,i=n.vars.maxItems;n.w=void 0===n.viewport?n.width():n.viewport.width(),n.h=e.height(),n.boxPadding=e.outerWidth()-e.width(),v?(n.itemT=n.vars.itemWidth+t,n.itemM=t,n.minW=a?a*n.itemT:n.w,n.maxW=i?i*n.itemT-t:n.w,n.itemW=n.minW>n.w?(n.w-t*(a-1))/a:n.maxW<n.w?(n.w-t*(i-1))/i:n.vars.itemWidth>n.w?n.w:n.vars.itemWidth,n.visible=Math.floor(n.w/n.itemW),n.move=n.vars.move>0&&n.vars.move<n.visible?n.vars.move:n.visible,n.pagingCount=Math.ceil((n.count-n.visible)/n.move+1),n.last=n.pagingCount-1,n.limit=1===n.pagingCount?0:n.vars.itemWidth>n.w?n.itemW*(n.count-1)+t*(n.count-1):(n.itemW+t)*n.count-n.w-t):(n.itemW=n.w,n.itemM=t,n.pagingCount=n.count,n.last=n.count-1),n.computedW=n.itemW-n.boxPadding,n.computedM=n.itemM},n.update=function(e,t){n.doMath(),v||(e<n.currentSlide?n.currentSlide+=1:e<=n.currentSlide&&0!==e&&(n.currentSlide-=1),n.animatingTo=n.currentSlide),n.vars.controlNav&&!n.manualControls&&("add"===t&&!v||n.pagingCount>n.controlNav.length?f.controlNav.update("add"):("remove"===t&&!v||n.pagingCount<n.controlNav.length)&&(v&&n.currentSlide>n.last&&(n.currentSlide-=1,n.animatingTo-=1),f.controlNav.update("remove",n.last))),n.vars.directionNav&&f.directionNav.update()},n.addSlide=function(e,t){var a=$(e);n.count+=1,n.last=n.count-1,d&&u?void 0!==t?n.slides.eq(n.count-t).after(a):n.container.prepend(a):void 0!==t?n.slides.eq(t).before(a):n.container.append(a),n.update(t,"add"),n.slides=$(n.vars.selector+":not(.clone)",n),n.setup(),n.vars.added(n)},n.removeSlide=function(e){var t=isNaN(e)?n.slides.index($(e)):e;n.count-=1,n.last=n.count-1,isNaN(e)?$(e,n.slides).remove():d&&u?n.slides.eq(n.last).remove():n.slides.eq(e).remove(),n.doMath(),n.update(t,"remove"),n.slides=$(n.vars.selector+":not(.clone)",n),n.setup(),n.vars.removed(n)},f.init()},$(window).blur(function(t){e=!1}).focus(function(t){e=!0}),$.flexslider.defaults={namespace:"flex-",selector:".slides > li",animation:"fade",easing:"swing",direction:"horizontal",reverse:!1,animationLoop:!0,smoothHeight:!1,startAt:0,slideshow:!0,slideshowSpeed:7e3,animationSpeed:600,initDelay:0,randomize:!1,fadeFirstSlide:!0,thumbCaptions:!1,pauseOnAction:!0,pauseOnHover:!1,pauseInvisible:!0,useCSS:!0,touch:!0,video:!1,controlNav:!0,directionNav:!0,prevText:"Previous",nextText:"Next",keyboard:!0,multipleKeyboard:!1,mousewheel:!1,pausePlay:!1,pauseText:"Pause",playText:"Play",controlsContainer:"",manualControls:"",customDirectionNav:"",sync:"",asNavFor:"",itemWidth:0,itemMargin:0,minItems:1,maxItems:0,move:0,allowOneSlide:!0,start:function(){},before:function(){},after:function(){},end:function(){},added:function(){},removed:function(){},init:function(){}},$.fn.flexslider=function(e){if(void 0===e&&(e={}),"object"==typeof e)return this.each(function(){var t=$(this),a=e.selector?e.selector:".slides > li",n=t.find(a);1===n.length&&e.allowOneSlide===!1||0===n.length?(n.fadeIn(400),e.start&&e.start(t)):void 0===t.data("flexslider")&&new $.flexslider(this,e)});var t=$(this).data("flexslider");switch(e){case"play":t.play();break;case"pause":t.pause();break;case"stop":t.stop();break;case"next":t.flexAnimate(t.getTarget("next"),!0);break;case"prev":case"previous":t.flexAnimate(t.getTarget("prev"),!0);break;default:"number"==typeof e&&t.flexAnimate(e,!0)}}}(jQuery);