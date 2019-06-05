$(function() {
    spf.init();
    hljs.initHighlighting();


    $(document).on("spfclick", function() {
        NProgress.start();
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
        NProgress.remove();
        hljs.initHighlighting.called = false;
        hljs.initHighlighting();
    });


    var firstClick = true;
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
        use_native: true
    });
});