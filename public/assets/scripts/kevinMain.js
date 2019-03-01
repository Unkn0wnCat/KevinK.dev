$(function() {
    spf.init();


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
        NProgress.remove();
    });
});
