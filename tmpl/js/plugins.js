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



// Sticky Plugin v1.0.0 for jQuery
// =============
// Author: Anthony Garand
// Improvements by German M. Bravo (Kronuz) and Ruud Kamphuis (ruudk)
// Improvements by Leonardo C. Daronco (daronco)
// Created: 2/14/2011
// Date: 2/12/2012
// Website: http://labs.anthonygarand.com/sticky
// Description: Makes an element on the page stick on the screen as you scroll
//       It will only set the 'top' and 'position' of your element, you
//       might need to adjust the width in some cases.

!function(t){var e={topSpacing:0,bottomSpacing:0,className:"is-sticky",wrapperClassName:"sticky-wrapper",center:!1,getWidthFrom:"",responsiveWidth:!1},i=t(window),n=t(document),s=[],r=i.height(),o=function(){for(var e=i.scrollTop(),o=n.height(),a=o-r,c=e>a?a-e:0,p=0;p<s.length;p++){var l=s[p],d=l.stickyWrapper.offset().top,h=d-l.topSpacing-c;if(h>=e)null!==l.currentTop&&(l.stickyElement.css("position","").css("top",""),l.stickyElement.trigger("sticky-end",[l]).parent().removeClass(l.className),l.currentTop=null);else{var u=o-l.stickyElement.outerHeight()-l.topSpacing-l.bottomSpacing-e-c;0>u?u+=l.topSpacing:u=l.topSpacing,l.currentTop!=u&&(l.stickyElement.css("position","fixed").css("top",u),"undefined"!=typeof l.getWidthFrom&&l.stickyElement.css("width",t(l.getWidthFrom).width()),l.stickyElement.trigger("sticky-start",[l]).parent().addClass(l.className),l.currentTop=u)}}},a=function(){r=i.height();for(var e=0;e<s.length;e++){var n=s[e];"undefined"!=typeof n.getWidthFrom&&n.responsiveWidth===!0&&n.stickyElement.css("width",t(n.getWidthFrom).width())}},c={init:function(i){var n=t.extend({},e,i);return this.each(function(){var i=t(this),r=i.attr("id"),o=(r?r+"-"+e.wrapperClassName:e.wrapperClassName,t("<div></div>").attr("id",r+"-sticky-wrapper").addClass(n.wrapperClassName));i.wrapAll(o),n.center&&i.parent().css({width:i.outerWidth(),marginLeft:"auto",marginRight:"auto"}),"right"==i.css("float")&&i.css({"float":"none"}).parent().css({"float":"right"});var a=i.parent();a.css("height",i.outerHeight()),s.push({topSpacing:n.topSpacing,bottomSpacing:n.bottomSpacing,stickyElement:i,currentTop:null,stickyWrapper:a,className:n.className,getWidthFrom:n.getWidthFrom,responsiveWidth:n.responsiveWidth})})},update:o,unstick:function(){return this.each(function(){for(var e=t(this),i=-1,n=0;n<s.length;n++)s[n].stickyElement.get(0)==e.get(0)&&(i=n);-1!=i&&(s.splice(i,1),e.unwrap(),e.removeAttr("style"))})}};window.addEventListener?(window.addEventListener("scroll",o,!1),window.addEventListener("resize",a,!1)):window.attachEvent&&(window.attachEvent("onscroll",o),window.attachEvent("onresize",a)),t.fn.sticky=function(e){return c[e]?c[e].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof e&&e?void t.error("Method "+e+" does not exist on jQuery.sticky"):c.init.apply(this,arguments)},t.fn.unstick=function(e){return c[e]?c[e].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof e&&e?void t.error("Method "+e+" does not exist on jQuery.sticky"):c.unstick.apply(this,arguments)},t(function(){setTimeout(o,0)})}(jQuery);



