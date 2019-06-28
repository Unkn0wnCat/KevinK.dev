
function fitImages() {
    $(".photoCard:not(.processed)").each(function (index, elem) {

        let el = $(elem);
        let imgEl = el.find("img");
        let placeholderEl = el.find(".placeholder");

        if(imgEl.hasClass("lazy") && !imgEl.hasClass("loaded")) {
            return;
        }

        let imgWidth = imgEl.width();
        let imgHeight = imgEl.height();

        console.log(imgWidth, imgHeight);

        el.css("width", imgWidth*200/imgHeight+"px");
        el.css("flex-grow", imgWidth*200/imgHeight);

        placeholderEl.css("padding-bottom", imgHeight/imgWidth*100+'%');

        el.addClass("processed");
    });

    $(".photoList").addClass("imagesReady");
}

if(window.fitImagesOnLoad) {
    fitImages();
}