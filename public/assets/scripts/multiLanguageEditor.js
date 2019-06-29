"use strict";

const multiLanguageEditor = new (function () {
    let count = 0;

    let languages = ["de", "en", "*"];


    function init(inputSel) {
        let input = $(inputSel);

        count++;

        let id;

        if(input.attr("id")) {
            id = input.attr("id");
        } else {
            id = "mleditorsrc"+count;
            input.attr("id", id);
        }

        input.after("<div class='multiLanguageEditorInline' data-input='"+id+"' id='mleditor"+count+"'>");

        let container = $("#mleditor"+count);

        container.append("<input class='input' placeholder='"+(typeof input.attr("placeholder") !== "undefined" ? input.attr("placeholder") : "Text") +"' type='text'>");

        container.append("<div class=\"multiLanguageSwitcherTrigger\">+</div>");
        container.append(   "<div class=\"multiLanguageSwitcher\">" +
            "<div class=\"multiLanguageChooserBox\">" +
            "</div>" +
            "</div>");

        languages.forEach(function (value, index) {
            container.find(".multiLanguageChooserBox").append("<span data-langtag=\""+value+"\" class=\"inactive\"><span>"+value+"</span><span class=\"multiLanguageChooserRemove\">&times;</span></span>");
        });

        initEditor(container, false);
    }


    function initTextarea(inputSel) {
        let input = $(inputSel);

        count++;

        let id;

        if(input.attr("id")) {
            id = input.attr("id");
        } else {
            id = "mleditorsrc"+count;
            input.attr("id", id);
        }

        input.after("<div class='multiLanguageEditorInline' data-input='"+id+"' id='mleditor"+count+"'>");

        let container = $("#mleditor"+count);

        container.append("<textarea class='input' placeholder='"+(typeof input.attr("placeholder") !== "undefined" ? input.attr("placeholder") : "Text") +"' type='text'>");

        container.append("<div class=\"multiLanguageSwitcherTrigger\">+</div>");
        container.append(   "<div class=\"multiLanguageSwitcher\">" +
            "<div class=\"multiLanguageChooserBox\">" +
            "</div>" +
            "</div>");

        languages.forEach(function (value, index) {
            container.find(".multiLanguageChooserBox").append("<span data-langtag=\""+value+"\" class=\"inactive\"><span>"+value+"</span><span class=\"multiLanguageChooserRemove\">&times;</span></span>");
        });

        initEditor(container, true);
    }
    
    function initEditor(containerSel, isTextArea) {
        let container = $(containerSel);

        let currentLang;

        let languageObject = {};

        let srcInput = $("#"+container.attr("data-input"));

        try {
            languageObject = JSON.parse(srcInput.val());

            Object.keys(languageObject).forEach(function (value) {
                let el = container.find(".multiLanguageChooserBox > *[data-langtag=\"" + value + "\"]");

                el.removeClass("inactive");
            });
        } catch (e) {
            languageObject = {};
        }

        function updateTextBox() {
            currentLang = container.find(".multiLanguageChooserBox > *.active").first().attr("data-langtag");

            if(typeof currentLang === 'undefined') {
                currentLang = null;
            }

            if(currentLang == null) {
                container.find(".input").attr("disabled", true);

                container.find(".input").val("");
            } else {
                container.find(".input").removeAttr("disabled");

                container.find(".input").val(languageObject[currentLang]);
            }
        }

        function saveToSrc() {
            srcInput.val(JSON.stringify(languageObject));
        }

        container.find(".multiLanguageSwitcherTrigger").click(function () {
            container.find(".multiLanguageSwitcher").toggleClass("active");
        });

        $("body").click(function (ev) {
            let el = $(ev.target);

            if(el.hasClass("multiLanguageEditorInline") || el.parent().hasClass("multiLanguageEditorInline") || el.parent().parent().hasClass("multiLanguageEditorInline") || el.parent().parent().parent().hasClass("multiLanguageEditorInline") || el.parent().parent().parent().parent().hasClass("multiLanguageEditorInline")) {
                return;
            }
            container.find(".multiLanguageSwitcher").removeClass("active");
        });

        container.find(".multiLanguageChooserBox > *").click(function(ev) {

            //console.log("click");

            let el = $(ev.target);
            let el2 = $(ev.delegateTarget);

            let language = el2.attr("data-langtag");

            if(el.hasClass("multiLanguageChooserRemove") && !el2.hasClass("inactive")) {
                //console.log("Removing", language);

                delete languageObject[language];

                saveToSrc();

                el2.addClass("inactive");
                el2.removeClass("active");
            } else if(el2.hasClass("inactive")) {
                //console.log("Adding", language);

                languageObject[language] = "";

                saveToSrc();

                container.find(".multiLanguageChooserBox > *").removeClass("active");

                el2.removeClass("inactive");
                el2.addClass("active");
            } else if(!el2.hasClass("active")) {
                //console.log("Switching to", language);

                container.find(".multiLanguageChooserBox > *").removeClass("active");

                el2.addClass("active");
            } else {
                //console.log("IDK");
            }

            updateTextBox();

        });
        
        container.find(".input").keyup(function (ev) {
            container.find(".multiLanguageSwitcher").removeClass("active");
            if(currentLang != null) languageObject[currentLang] = container.find(".input").val();


            saveToSrc();
            //console.log(languageObject);
        });

        updateTextBox();
    }

    this.initialize = init;
    this.initializeTextArea = initTextarea;
})();