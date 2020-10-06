let topBarNeededWidth;

$(function() {
    spf.init();
    hljs.initHighlighting();


    $(document).on("spfclick", function() {
        NProgress.start();
    });

    let codeword = "";
    let secret = false;

    $(document).on("keydown", function(ev) {
        if(location.pathname == "/en/" || location.pathname == "/de/" || location.pathname == "/en" || location.pathname == "/de") {
            console.log(ev);
            codeword += ev.originalEvent.key.toLowerCase();

            if(codeword == "lgbt" || codeword == "gay" || codeword == "queer") {
                var cssId = 'coloroverride';
                if (!document.getElementById(cssId) && !secret)
                {
                    secret = true;
                    var head  = document.getElementsByTagName('head')[0];
                    var link  = document.createElement('link');
                    link.id   = cssId;
                    link.rel  = 'stylesheet';
                    link.type = 'text/css';
                    link.href = '/assets/styles/colorful-override.css';
                    link.media = 'all';
                    head.appendChild(link);
                }
            }
        }
    });

    $(document).on("spfrequest", function() {
        NProgress.inc();
    });

    $(document).on("spfprocess", function() {
        NProgress.done();
    });

    $(document).on("spfdone", function() {
        if (lazyLoadInstance) {
            lazyLoadInstance.update();
        }
        checkNavBarCollapse();
        checkIsTop();

        if(typeof fitImages !== 'undefined' && jQuery.isFunction(fitImages)) {
            fitImages();
        } else {
            window.fitImagesOnLoad = true;
        }

        NProgress.remove();
        hljs.initHighlighting.called = false;
        hljs.initHighlighting();
    });


    let firstClick = true;
    $(".has-dropdown > a").click(function(ev) {
        if(firstClick) {
            ev.preventDefault();
            firstClick = false;
        } else {
            firstClick = true;
        }
    });

    let lazyLoadInstance = new LazyLoad({
        elements_selector: ".lazy",
        use_native: true,
        callback_loaded: function () {
            if(typeof fitImages !== 'undefined' && jQuery.isFunction(fitImages)) {
                fitImages();
            } else {
                window.fitImagesOnLoad = true;
            }
        }
    });

    $("body").append("<div class=\"offscreenNavigation\"></div>");
    topBarNeededWidth = 0;

    $( ".topBarInner > *" ).each(function (index, el) {
        if($(el).hasClass("offscreenNavigationSwitch")) return;
        if(!$(el).hasClass("flexSpacer")) topBarNeededWidth += $(el).width() + 15 * 2;
        if(!$(el).hasClass("has-dropdown")) {
            if(!$(el).hasClass("flexSpacer")) $(".offscreenNavigation").append("<a href=\""+$(el).attr('href')+"\" class='spf-link'>"+$(el).text()+"</a>");
            else $(".offscreenNavigation").append("<div class=\"offscreenNavigationSpacer\"></div>");
        }
        else {
            let elem = $(el);

            $(".offscreenNavigation").append("<a href=\""+ elem.find("> a").attr('href')+"\" class='spf-link'>"+ elem.find("> a").text()+"</a>");


            elem.find(".dropdown > a").each(function (index, childEl) {
                $(".offscreenNavigation").append("<a href=\""+$(childEl).attr('href')+"\" class='spf-link'>&gt; "+$(childEl).text()+"</a>");
            });
        }
    });

    $(".offscreenNavigationSwitch").click(function() {
        $("body").toggleClass("showOffscreenNavigation");
    });

    $(window).resize(function(ev) {
        checkNavBarCollapse();
    });

    checkNavBarCollapse();

    $(window).scroll(function() {
        checkIsTop();
    });

    checkIsTop();


    if(typeof fitImages !== 'undefined' && jQuery.isFunction(fitImages)) {
        fitImages();
    } else {
        window.fitImagesOnLoad = true;
    }
});

function checkNavBarCollapse() {
    let width = $(window).width() * .9;

    let collapseNavigation = topBarNeededWidth > width;

    if(collapseNavigation) {
        $(body).addClass("useOffscreenNavigation");
    } else {
        $(body).removeClass("useOffscreenNavigation");
    }
}


function isOverflown(element) {
    return element.scrollHeight > element.clientHeight || element.scrollWidth > element.clientWidth;
}

function checkIsTop() {
    if(window.scrollY < 15) {
        $("body").addClass("atTop");
    } else {
        $("body").removeClass("atTop");
    }
}


/*! loadCSS. [c]2017 Filament Group, Inc. MIT License */
/* This file is meant as a standalone workflow for
- testing support for link[rel=preload]
- enabling async CSS loading in browsers that do not support rel=preload
- applying rel preload css once loaded, whether supported or not.
*/
(function( w ){
    "use strict";
    // rel=preload support test
    if( !w.loadCSS ){
        w.loadCSS = function(){};
    }
    // define on the loadCSS obj
    var rp = loadCSS.relpreload = {};
    // rel=preload feature support test
    // runs once and returns a function for compat purposes
    rp.support = (function(){
        var ret;
        try {
            ret = w.document.createElement( "link" ).relList.supports( "preload" );
        } catch (e) {
            ret = false;
        }
        return function(){
            return ret;
        };
    })();

    // if preload isn't supported, get an asynchronous load by using a non-matching media attribute
    // then change that media back to its intended value on load
    rp.bindMediaToggle = function( link ){
        // remember existing media attr for ultimate state, or default to 'all'
        var finalMedia = link.media || "all";

        function enableStylesheet(){
            // unbind listeners
            if( link.addEventListener ){
                link.removeEventListener( "load", enableStylesheet );
            } else if( link.attachEvent ){
                link.detachEvent( "onload", enableStylesheet );
            }
            link.setAttribute( "onload", null );
            link.media = finalMedia;
        }

        // bind load handlers to enable media
        if( link.addEventListener ){
            link.addEventListener( "load", enableStylesheet );
        } else if( link.attachEvent ){
            link.attachEvent( "onload", enableStylesheet );
        }

        // Set rel and non-applicable media type to start an async request
        // note: timeout allows this to happen async to let rendering continue in IE
        setTimeout(function(){
            link.rel = "stylesheet";
            link.media = "only x";
        });
        // also enable media after 3 seconds,
        // which will catch very old browsers (android 2.x, old firefox) that don't support onload on link
        setTimeout( enableStylesheet, 3000 );
    };

    // loop through link elements in DOM
    rp.poly = function(){
        // double check this to prevent external calls from running
        if( rp.support() ){
            return;
        }
        var links = w.document.getElementsByTagName( "link" );
        for( var i = 0; i < links.length; i++ ){
            var link = links[ i ];
            // qualify links to those with rel=preload and as=style attrs
            if( link.rel === "preload" && link.getAttribute( "as" ) === "style" && !link.getAttribute( "data-loadcss" ) ){
                // prevent rerunning on link
                link.setAttribute( "data-loadcss", true );
                // bind listeners to toggle media back
                rp.bindMediaToggle( link );
            }
        }
    };

    // if unsupported, run the polyfill
    if( !rp.support() ){
        // run once at least
        rp.poly();

        // rerun poly on an interval until onload
        var run = w.setInterval( rp.poly, 500 );
        if( w.addEventListener ){
            w.addEventListener( "load", function(){
                rp.poly();
                w.clearInterval( run );
            } );
        } else if( w.attachEvent ){
            w.attachEvent( "onload", function(){
                rp.poly();
                w.clearInterval( run );
            } );
        }
    }


    // commonjs
    if( typeof exports !== "undefined" ){
        exports.loadCSS = loadCSS;
    }
    else {
        w.loadCSS = loadCSS;
    }
}( typeof global !== "undefined" ? global : this ) );
