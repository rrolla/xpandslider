var dataAttributes = [];
var dataAttribute = {};
var easings = [
  "linear",
  "easeInSine",
  "easeOutSine",
  "easeInOutSine",
  "easeInQuad",
  "easeOutQuad",
  "easeInOutQuad",
  "easeInCubic",
  "easeOutCubic",
  "easeInOutCubic",
  "easeInQuart",
  "easeOutQuart",
  "easeInOutQuart",
  "easeInQuint",
  "easeOutQuint",
  "easeInOutQuint",
  "easeInExpo",
  "easeOutExpo",
  "easeInOutExpo",
  "easeInCirc",
  "easeOutCirc",
  "easeInOutCirc",
  "easeInBack",
  "easeOutBack",
  "easeInOutBack",
  "easeInElastic",
  "easeOutElastic",
  "easeInOutElastic",
  "easeInBounce",
  "easeOutBounce",
  "easeInOutBounce"
];

var dataAttributeOptions = {
    $schema: "http://json-schema.org/draft-03/schema#",
    type: "object",
    properties: {
        captionFx: {
            title: "Caption effect",
            description: "Effect for caption",
            type: "string",
            enum: ["moveFromLeft", "moveFomRight", "moveFromTop", "moveFromBottom", "fadeIn", "fadeFromLeft", "fadeFromRight", "fadeFromTop", "fadeFromBottom"],
        },
        link: {
            title: "Link",
            description: "URL where you go by clicking your slide",
            type: "string",
        },
        target: {
            title: "Target",
            description: "Target values for the data-link attribute",
            type: "string",
            enum: ["_blank", "_new", "_parent", "_self", "_top"],
        },
        alignment: {
            title: "Alignment",
            description: "Alignmen to slide",
            type: "string",
            enum: ["topLeft", "topCenter", "topRight", "centerLeft", "center", "centerRight", "bottomLeft", "bottomCenter", "bottomRight"],
        },
        fx: {
            title: "Effect",
            description: "Transition effect to slide",
            type: "string",
            enum: ["random", "simpleFade", "curtainTopLeft", "curtainTopRight", "curtainBottomLeft", "curtainBottomRight", "curtainSliceLeft", "curtainSliceRight", "blindCurtainTopLeft", "blindCurtainTopRight", "blindCurtainBottomLeft", "blindCurtainBottomRight", "blindCurtainSliceBottom", "blindCurtainSliceTop", "stampede", "mosaic", "mosaicReverse", "mosaicRandom", "mosaicSpiral", "mosaicSpiralReverse", "topLeftBottomRight", "bottomRightTopLeft", "bottomLeftTopRight", "bottomLeftTopRight"],
        },
        easing: {
            title: "Easing",
            description: "Easing to slide",
            type: "string",
            enum: easings,
        },
        mobileEasing: {
            title: "Mobile Easing",
            description: "Mobile Easing to slide",
            type: "string",
            enum: easings,
        },
        portrait: {
            title: "Portrait",
            description: "Select true if you don't want that your images are cropped",
            type: "string",
            enum: ["true", "false"],
        },
        slideOn: {
            title: "slideOn",
            description: "Decide if the transition effect will be applied to the current (prev) or the next slide",
            type: "string",
            enum: ["next", "prev", "random"],
        },
    },
};

var bf;
setTimeout(function () {
  var BrutusinForms = brutusin["json-forms"];
  bf = BrutusinForms.create(dataAttributeOptions);

  var container = document.getElementById('form-container');
  if (container) {
    bf.render(container, dataAttribute);
  }
}, 1000);

$(document).on("change", "#form-container", function () {
  $('#extra').attr('value', JSON.stringify(bf.getData()));
});

$(function () {
  $(".footer-cr").each(function () {
    //...set the opacity to 0...
    $(this).css("opacity", 0);
    //..set width same as the image...
    var parent = $(this).parent().width();
    $(this).css("max-width", parent);
    //...get the parent (the wrapper) and set it"s width same as the image width... "
    var imgw = $(this).siblings("img").width();
    $(this).parent().css("max-width", imgw);
    //...set the display to block
    $(this).css("display", "block");
    var offset = parent - imgw;
    $(this).css("left", -offset / 2);
  });

  $(".footer-content").hover(function () {
    //when mouse hover over the wrapper div
    //get it"s children elements with class description "
    //and show it using fadeTo
    $(this).children(".footer-img").stop().fadeTo(500, 0.2);
    $(this).children(".footer-cr").stop().fadeTo(500, 1);
  }, function () {
    //when mouse out of the wrapper div
    //use fadeTo to hide the div
    $(this).children(".footer-cr").stop().fadeTo(500, 0);
    $(this).children(".footer-img").stop().fadeTo(500, 1);
  });
});

var elfinder = {};
$(function () {
  $('.choose-image').click(function() {
    var elfinder = $('<div/>').dialogelfinder({
      debug: xpandSliderDebug,
      url : elFinderConnectorPath,
      lang : 'en',
      destroyOnClose : true,
      handlers : {
        dblclick : function(event, elfinderInstance) {
          event.preventDefault();
          elfinderInstance.exec('getfile')
          .done(function() { elfinderInstance.exec('quicklook'); })
          .fail(function() { elfinderInstance.exec('open'); });
        }
      },
      getFileCallback : function(files, fm) {
        var withoutSlides = files.path.replace("slides", "");
        $('.choose-image').attr('src', thumbPhpPath + '?w=300&h=300&src=' + xpandSliderImagespath + withoutSlides);
        $('.choosed-image').attr('value', withoutSlides);
      },
      commandsOptions : {
        getfile : {
          oncomplete : 'close',
        }
      }
    }).dialogelfinder('instance');
  });
});
