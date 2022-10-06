(function() {
    "use strict";
    var isTouchDevice = navigator.userAgent.match(
        /(iPhone|iPod|iPad|Android|BlackBerry|Windows Phone)/
    );

    function dataTable() {
        var table = $(".data-table");

        if (table.length) {
            table.DataTable();
        }
    }

    function autocomplete() {
        if (typeof $.typeahead === "undefined") {
            return false;
        }
        if ($(".autocomplete-control").length) {
            $(".autocomplete-control").each(function(i, item) {
                var $this = $(item),
                    DISPLAY = false,
                    TEMPLATE = false;
                if ($this.data("display")) {
                    DISPLAY = $this
                        .data("display")
                        .split("|")
                        .map((item) => item.trim());
                }
                if ($this.data("template")) {
                    TEMPLATE = $this
                        .data("template")
                        .replace(/\[+([^\][]+)]+/g, "{{$1}}");
                }
                $this.typeahead({
                    order: "asc",
                    source: {
                        medicines: {
                            // Ajax Request
                            display: DISPLAY,
                            template: TEMPLATE,
                            ajax: {
                                url: `${$this.data("url")}`,
                                data: {
                                    search: "{{query}}",
                                },
                            },
                        },
                    },
                    callback: {
                        onClickAfter: function(node, a, item, event) {
                            var CB = $this.data("after-click");
                            CB && eval(CB)(node, item, event, a);
                        },
                    },
                });
            });
        }
    }

    function appSearch() {
        if (typeof $.typeahead === "undefined") {
            return false;
        }
        if ($("form.app-search .topbar-search").length > 0) {
            $("form.app-search .topbar-search").typeahead({
                maxItem: 5,
                order: "asc",
                hint: true,
                emptyTemplate: "<span>No Item Found!</span>",
                source: {
                    items: {
                        href: "{{url}}",
                        display: ["title", "url"],
                        ajax: {
                            url: "/navigation.json",
                        },
                        key: "item",
                        template: function(query, item) {
                            return (
                                '<span class="item-row">' +
                                '<span class="{{icon}}"></span>' +
                                '<span class="ml-2 page-title">{{title}}</span>' +
                                "</span>"
                            );
                        },
                    },
                },
                callback: {
                    onClickAfter: function(node, a, item, event) {
                        event.preventDefault();
                        if (item.href) {
                            window.location.href = item.href;
                        }
                    },
                },
            });
        }
    }

    function selectpicker() {
        var select = $(".selectpicker");

        if (select.length) {
            select.each(function() {
                $(this).selectpicker({
                    style: "",
                    styleBase: "form-control",
                    tickIcon: "icofont-check-alt",
                });
            });
        }
    }

    function rating() {
        var rating = $(".rating");

        if (rating.length) {
            rating.each(function() {
                var item = $(this);
                var readonly = item.data("readonly");
                var reverse = item.data("reverse");

                item.barrating({
                    showSelectedRating: false,
                    readonly: readonly,
                    reverse: reverse,
                });
            });
        }
    }

    function menu() {
        var menu = $(".main-menu");
        var item = menu.find(".item-link");

        item.click(function(event) {
            var li = $(this).closest(".menu-item");

            if (li.hasClass("has-sub")) {
                event.preventDefault();
                li.toggleClass("active");
            }
        });

        let url = window.location.href;
        let $link = $("#navbar2").find('.menu-item a[href="' + url + '"]');
        $link.parent("li").addClass("active");
        $link
            .parents(".sub")
            .parents("li")
            .addClass("active");
    }

    function formValidation() {
        window.addEventListener(
            "load",
            function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName("needs-validation");
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(
                    form
                ) {
                    form.addEventListener(
                        "submit",
                        function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add("was-validated");
                        },
                        false
                    );
                });
            },
            false
        );
    }

    function fileInput() {
        $(".file-input input[type=file]").each(function(i, item) {
            $(item).on("change", function() {
                if (
                    $(item)
                        .parents(".file-input")
                        .find("img.img-placeholder").length <= 0
                ) {
                    return null;
                }
                var file = this.files[0],
                    reader = new FileReader();
                reader.addEventListener(
                    "load",
                    function() {
                        $(item)
                            .parents(".file-input")
                            .find("img.img-placeholder")
                            .attr("src", reader.result);
                    },
                    false
                );
                if (file) {
                    reader.readAsDataURL(file);
                }
            });
        });
    }

    function scroll() {
        var body = $("body");

        $(".main-content").scroll(function() {
            var scroll = $(this).scrollTop();

            if (scroll > 0) {
                body.addClass("scrolled");
            } else {
                body.removeClass("scrolled");
            }
        });
    }

    function settings() {
        var modal = $("#settings");
        var boxed = modal.find("#boxed");
        var bar1Dark = modal.find("#topbar");
        var bar2Dark = modal.find("#sidebar");
        var layout = modal.find("#layout");

        boxed.change(function() {
            $("body")[$(this).is(":checked") ? "addClass" : "removeClass"](
                "boxed"
            );
        });

        bar1Dark.change(function() {
            $("#navbar1")[$(this).is(":checked") ? "addClass" : "removeClass"](
                "dark"
            );
        });

        bar2Dark.change(function() {
            $("#navbar2")[$(this).is(":checked") ? "addClass" : "removeClass"](
                "dark"
            );
        });

        layout.find("option").each(function() {
            if ($("body").hasClass($(this).val())) {
                layout.selectpicker("val", $(this).val());
                $(this).prop("selected", true);
            }
        });
        layout.change(function() {
            var val = $(this).val();

            if (!$("body").hasClass(val)) {
                var lc = window.location.pathname;

                if (val === "horizontal-layout") {
                    window.location.pathname = lc.replace(
                        "/dist",
                        "/dist-horizontal"
                    );
                } else {
                    window.location.pathname = lc.replace(
                        "/dist-horizontal",
                        "/dist"
                    );
                }
            }
        });

        $("#reset-to-default").click(function() {
            $("body").addClass("boxed");
            boxed.prop("checked", true);
            $("#navbar1").removeClass("dark");
            bar1Dark.prop("checked", false);
            $("#navbar2").removeClass("dark");
            bar2Dark.prop("checked", false);
        });
    }

    $(document).ready(function() {
        if (typeof __App !== "undefined") {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": __App.csrf_token,
                    Authorization: `Bearer ${__App?.user?.auth_token}`,
                },
            });
        }
        $(document).ajaxStart(function() {
            /* Do something here */
        });
        $(document).ajaxStop(function() {
            /* Do something here */
        });
        $(document).ajaxError(function() {
            /* Do something here */
        });

        appSearch();
        menu();
        dataTable();
        autocomplete();
        rating();
        formValidation();
        scroll();
        selectpicker();
        settings();
        fileInput();

        $(".alert").alert();
        $('[data-toggle="tooltip"]').tooltip();

        // Open/close sidebar
        $(".navbar-toggle").click(function() {
            $(
                ".app-navbar.vertical, .app-navbar.horizontal-vertical"
            ).toggleClass("opened");
            $(".content-overlay").toggleClass("show");
        });
        $(".content-overlay").click(function() {
            $(
                ".app-navbar.vertical, .app-navbar.horizontal-vertical"
            ).removeClass("opened");
            $(this).removeClass("show");
        });

        // Top navbar actions
        $(".app-actions .dropdown").on("show.bs.dropdown", function() {
            $(".content-overlay").addClass("show");
        });
        $(".app-actions .dropdown").on("hide.bs.dropdown", function() {
            $(".content-overlay").removeClass("show");
        });
        $(document).on(
            "click",
            '.close-image,.image-view:not(".image-view-box img")',
            function() {
                $("div#image-preview").css("display", "none");
            }
        );
        $(document).on("click", ".image-preview", function(e) {
            e.preventDefault();
            var $this = $(this),
                src = $this.attr("src") || $this.find("img").attr("src");
            if (!src) {
                return false;
            }
            if ($("div#image-preview").length > 0) {
                $("div#image-preview")
                    .find("img")
                    .attr("src", src);
                $("div#image-preview").css("display", "flex");
            } else {
                $("body").append(
                    '<div style="display:flex;" class="image-view" id="image-preview"><div class="image-view-box"><span class="close-image">&times;</span><div><img src="' +
                        src +
                        '"></div></div></div>'
                );
            }
        });
    });

    $(window).on("load", function() {
        $.ready.then(function() {
            $("body").addClass("loaded");
        });
    });

    //Window Resize
    (function() {
        var delay = (function() {
            var timer = 0;
            return function(callback, ms) {
                clearTimeout(timer);
                timer = setTimeout(callback, ms);
            };
        })();

        //Functions
        function resizeFunctions() {
            $(
                ".app-navbar.vertical, .app-navbar.horizontal-vertical"
            ).removeClass("opened");
            $(".content-overlay").removeClass("show");
            $(".dropdown.show .dropdown-toggle").dropdown("toggle");
        }

        if (isTouchDevice) {
            $(window).bind("orientationchange", function() {
                delay(function() {
                    resizeFunctions();
                }, 50);
            });
        } else {
            $(window).on("resize", function() {
                delay(function() {
                    resizeFunctions();
                }, 50);
            });
        }

        $(".read-more-box .read-more").on("click", function() {
            let $box = $(this).parents(".read-more-box");
            let text = $box.data("text");
            if (text) {
                $box.text(text);
            }
        });
    })();
})(jQuery);

function initGoogleMap() {
    var map;
    var mapContainer = document.getElementById("google-map");

    if (mapContainer) {
        map = new google.maps.Map(mapContainer, {
            center: { lat: 50.4888786, lng: 30.5553166 },
            zoom: 8,
        });
    }
}

function disableSubmitButton(form) {
    $(form)
        .find("button[type=submit]")
        .attr("disabled", true);
    $(form)
        .find(":input")
        .on("keyup", function() {
            $(form)
                .find("button[type=submit]")
                .removeAttr("disabled");
        });
}

function copyText(text) {
    var textArea = document.createElement("textarea");
    textArea.style.position = "fixed";
    textArea.style.top = 0;
    textArea.style.left = 0;
    textArea.style.width = "2em";
    textArea.style.height = "2em";
    textArea.style.padding = 0;
    textArea.style.border = "none";
    textArea.style.outline = "none";
    textArea.style.boxShadow = "none";
    textArea.style.background = "transparent";

    textArea.value = text;
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        var successful = document.execCommand("copy");
        successful && console.log(`Text Copied: ${text}`);
    } catch (err) {
        console.log("Oops, unable to copy");
    }
    document.body.removeChild(textArea);
}
