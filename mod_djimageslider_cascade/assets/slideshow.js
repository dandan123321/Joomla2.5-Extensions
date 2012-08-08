var MooSlideshow = new Class({
    Implements: Options,
    options: {
        duration: "long",
        slideMaxWidth: 974,
        slideMinWidth: 90,
        slideClass: "slide",
        nextButton: null,
        prevButton: null
    },
    initialize: function (element, options) {
        this.element = element;
        this.setOptions(options);
        this.duration = this.options.duration;
        this.slideMaxWidth = this.options.slideMaxWidth;
        this.slideClass = this.options.slideClass;
        this.containerWidth = this.element.offsetWidth;
        this.slides = element.getElements("." + this.slideClass);
        this.slideCount = this.slides.length;
        this.slideMinWidth = this.options.slideMinWidth;
        this.current = this.currentOld = 1;

        var _this = this;
        if (Browser.ie) {
            window.addEvent("load", function () {
                _this.bind();
                _this.resize();
            });
        } else {
            this.imagesReady(function () {
                _this.bind();
                _this.resize();
            });
        }
    },

    resize: function () {
        this.containerWidth = this.element.offsetWidth;
        this.slideMaxWidth = this.containerWidth - this.slideMinWidth * (this.slideCount-1);
        if (this.slideMaxWidth > this.options.slideMaxWidth) {
            this.slideMaxWidth = this.options.slideMaxWidth;
            this.slideMinWidth = (this.containerWidth - this.slideMaxWidth) / (this.slideCount - 1);
        }
        this.moveSlides(false);
    },

    imagesReady: function (callback) {
        var container = this.element;
        var imgNoReadyCount = container.getElements("img").length;
        container.getElements("img").each(function (item, index) {
            var img = new Image();
            img.src = item.src;
            img.onload = function () {
                imgNoReadyCount--;
                if (imgNoReadyCount <= 0) {
                    callback();
                }
            }
        });
    },

    bind: function () {
        var _this = this;
        window.addEvent("resize", function () {
            _this.resize();
        });
        this.slides.each(function (item, index) {
            item.slideIndex = index;
            item.setStyle("position", "absolute");
            item.setStyle("left", "0px");
            item.addEvent("mouseover", function () {
                if (_this.current != this.slideIndex) {
                    _this.current = this.slideIndex;
                    _this.moveSlides(true);
                }
            });
        });

        if (this.options.nextButton) {
            this.options.nextButton.addEvent("click", function () {
                if (_this.current < _this.slideCount) {
                    _this.current++;
                    _this.moveSlides(true);
                }
            });
        }

        if (this.options.prevButton) {
            this.options.prevButton.addEvent("click", function () {
                if (_this.current > 0) {
                    _this.current--;
                    _this.moveSlides(true);
                }
            });
        }
    },

    moveSlides: function (animation) {
        var _this = this;
        this.slides.each(function (item, index) {
            var textImg = item.getElement(".hoz-text");
            textImg.setStyle("display", "none");
            textImg.setStyle("opacity", 0);
            if (_this.current != item.slideIndex) {
                item.removeClass("current");
            }
            else
                item.addClass("current");

            var left = 0;
            if (_this.current < item.slideIndex) {
                left = (item.slideIndex - 1) * _this.slideMinWidth + _this.slideMaxWidth;
            } else {
                left = item.slideIndex * _this.slideMinWidth;
            }
            if (animation) {
                item.set("tween", {
                    duration: _this.duration,
                    onComplete: function () {
                        if (_this.current == item.slideIndex) {
                            textImg.setStyle("display", "");
                            textImg.fade("in");
                        }
                    }
                });
                item.tween("left", left.toInt());
            } else {
                item.setStyle("left", left.toInt() + "px");
                if (_this.current == item.slideIndex) {
                    textImg.setStyle("display", "");
                    textImg.setStyle("opacity", 1);
                }
            }
        });
    }
});
