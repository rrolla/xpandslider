var dataAttributes = [];
var dataAttribute = {};

var arrM = []; // masīvs priekš mazām bildēm
var arrL = []; // masīvs priekš lielām bildēm

function isEmpty(arr){
    if(typeof arr !== "undefined" && arr.length > 0){
        return false;
    }else{
        return true;
    }
}

// Savieno visas masīva arrM vērtības un ieliek iekš input vērtības
function pievienoMval(arrM){

    if(Array.isArray(arrM)){
        var jver = arrM.join();
    }else{
        var jver = arrM;
    }

    var merkisM = $("input[id$=\"xpand-slider-imgs\"]");
    merkisM.val(jver);

}

// Savieno visas masīva arrL vērtības un ieliek iekš input vērtības
function pievienoLval(arrL){
    var jver = arrL.join();
    var merkisL = $("input[id$=\"bilde-l\"]");
    merkisL.val(jver);
}

// atrod mazās bildes no input vērtības
function atrMbildes(force){
    //console.debug("force ir: "+force);

    // force is optional
    // the null value would be the default value
    if(!force) force=false;

    var merkisM = $("input[id$=\"xpand-slider-imgs\"]");

    if(merkisM.length){

        //console.debug(merkisM);

        var bildeM = merkisM.val();

        if(!bildeM){
            merkisM.val("xpandSlider-min-na.png,xpandSlider-na");
        }

        var bildeM = merkisM.val();

        //console.debug(bildeM);

        if(force){
            arrM = bildeM.split(",");
            //console.debug(arrm);
            return arrM;
        }else if(!isEmpty(arrM)){
            //console.debug("masiva jau kaut kas ir!");
            return false;
        }else{
            arrM = bildeM.split(",");
            return arrM;
        }

    }
}

// atrod lielās bildes no input vērtības
function atrLbildes(force){
    //console.debug("force ir: "+force);

    // force is optional
    // the null value would be the default value
    if(!force) force=false;

    var merkisL = $("input[id$=\"xpand-slider-imgs\"]");

    //console.debug(merkisL.length);

    if(merkisL.length){

    //console.debug(merkisL);

        var bildeL = merkisL.val();

        if(!bildeL){
            merkisL.val("l1_eHBhbmRTbGlkZXItbmEuanBn");
        }

        var bildeL = merkisL.val();

        if(force){
            arrL = bildeL.split(",");
            //console.debug(arrl);
            return arrL;
        }else if(!isEmpty(arrL)){
            //console.debug("masiva jau kaut kas ir!");
            return false;
        }else{
            arrL = bildeL.split(",");
            return arrL;
        }

    }
}

// funkcija, kas ģenerē bildes no arrM
function genMbildes(){
    var merkisM = $("input[id$=\"xpand-slider-imgs\"]");
    $("#elfinder").remove();
    $(".debug").remove();
    $("<div id=\"elfinder\"></div>").insertAfter(merkisM);
    arrM = atrMbildes(true);
    //console.debug(arrM);
    //var file = atrLbildes(true);

    if(arrM){

    //$.each(arrM, function(key, val){
        var key = 0;
        $("#elfinder").append("<div class=\"bildite\" data-bildite=\""+key+"\"><div class=\"img-opt\"><div class=\"img-del\" title=\"'.LAN_PLUG_XPNSLD_DELETE.'\"><i class=\"S16 e-delete-16\"></i></div></div><img src=\"'.e_PLUGIN_ABS.XPNSLD_DIR.XPNSLD_IMG_DIR.'"+arrM[0]+"\" data-hash=\""+arrM[1]+"\"  width=\"200\" data-toggle=\"tooltip\" title=\"'.LAN_PLUG_XPNSLD_HELP_IMGS.'\" /></div>");
        $("#elfinder");
    //});

    var bildite = $("div.bildite").first();

    $("<div class=\"bildite-add\" data-bildite=\"add\"><div class=\"img-opt\"><div class=\"img-add btn btn-success\" title=\"'.LAN_PLUG_XPNSLD_ADD_IMG.'\">'.LAN_PLUG_XPNSLD_ADD_IMG.' <i class=\"S16 e-add-16\"></i></div></div></div>").insertBefore(bildite);


    //$("<div class=\"debug\"><button class=\"parM\">kas Masīvā?</button><button class=\"parI\">kas inputā?</button></div>").insertAfter(merkisM);

    }

}

// funkcija, kas sakārto bildes iekš arrM
function sakMbildes(){
    var bildites = $("#elfinder div.bildite");
    var bildes = [];
    bildites.each(function(e){
        var re = "'.e_PLUGIN_ABS.XPNSLD_DIR.'images/";
        var tmb = $(this).find("img").attr("src");
        var bilde = tmb.replace(re, "");
        bildes.push(bilde);
    });
    pievienoMval(bildes);
}

// funkcija, kas sakārto bildes iekš arrL
function sakLbildes(){
    var bildites = $("#elfinder div.bildite");
    var bildes = [];
    bildites.each(function(e){
        var hash = $(this).find("img").data("hash");
        bildes.push(hash);
    });
    pievienoLval(bildes);
}

function pievBildes(files){
    //console.log(files);
    //arrM = atrMbildes(true);
    //arrL = atrLbildes(true);
    var bildMidx = $("div.bildite").last().data("bildite");
    bildMidx++;

    //console.log("masivs: " +arrm);
    //console.log("len: " +len);
    //console.log( "len: " +bilindex );

    //$.each(files, function(key, val){
        //console.log(key);
        //console.log(files);
        var file = files.hash;
        var re = "'.XPNSLD_IMG_DIR.'";
        var tmb = files.tmb;
        tmb = tmb.replace(re, "");

        arrM = (tmb+","+file);

        //var merkisM = $("input[id$=\"xpand-slider-imgs\"]");
        //merkisM.val(arrM);

        //arrM.splice(bildMidx, 0, tmb);
        //arrL.splice(bildMidx, 0, file);

        pievienoMval(arrM);
        //pievienoLval(arrL);

        $("#elfinder").html("<div class=\"bildite\" data-bildite=\""+(bildMidx)+"\"><div class=\"img-opt\"><div class=\"img-del\" title=\"'.LAN_PLUGIN_XPNSLD_DELETE.'\"><i class=\"S16 e-delete-16\"></i></div></div><img src=\"'.e_PLUGIN_ABS.XPNSLD_DIR.XPNSLD_IMG_DIR.'"+tmb+"\" data-hash=\""+file+"\" width=\"200\"  data-toggle=\"tooltip\" title=\"'.LAN_PLUG_XPNSLD_HELP_IMGS.'\" /></div>");
        //$("#elfinder");

        bildMidx++;
    //});

    sortOn();
}

// for debug

$(document).on("click", ".parM", function(){
    //console.debug("tmbs: "+arrM);
    //console.debug("imgs: "+arrL);
    var lenM = arrM.length;
    var lenL = arrL.length;
    //console.debug("tmbs len: "+lenM);
    //console.debug("imgs len: "+lenL);
    //console.debug( atrMbildes() );
    return false;
});

$(document).on("click", ".parI", function(){
    var merkisM = $("input[id$=\"xpand-slider-imgs\"]");
    var merkisL = $("input[id$=\"xpand-slider-imgs\"]");
    merkisM = merkisM.val();
    merkisL = merkisL.val();
    //console.log("tmbs input: "+merkisM);
    //console.log("imgs input: "+merkisL);
    return false;
});

// end for debug


$(document).on("click", "div.img-opt div.img-del", function(){
    if(isEmpty(arrM)){
        arrM = atrMbildes(true);
    }

    //if(isEmpty(arrL)){
        //arrL = atrLbildes(true);
    //}

    var lenM = arrM.length;
    //var lenL = arrL.length;

    var bildite = $(this).closest("div.bildite");
    var bildMidx = bildite.data("bildite");

    //console.log("izdzēsu no arrM: " +arrM[bildMidx]);
    //console.log("izdzēsu no arrL: " +arrL[bildMidx]);

    //console.log("len: " +len);
    //console.log( "index: " +bilindex );

    //var index = $.inArray(bilindex,arrm);

    /*if(lenM != 1){
        arrM.splice(bildMidx, 1);
        arrL.splice(bildMidx, 1);
        pievienoMval(arrM);
        pievienoLval(arrL);
    }else{
        //console.debug("viena bilde palikus");
        arrM.splice(bildMidx, 1);
        pievienoMval(arrM);
        genMbildes();
        arrL.splice(bildMidx, 1);
        pievienoLval(arrL);
        atrLbildes();
    }*/

    arrM = "";
    pievienoMval(arrM);

    bildite.fadeOut("fast", function(){
        $(this).remove();
        genMbildes();
    });
});

function sortOn(){
    $("#elfinder").sortable({
        update: function(event, ui){
            var arr = $(this).sortable("toArray", {attribute: "data-bildite"});
            //console.debug(arr);
            sakMbildes();
            sakLbildes();
        }
    });

    $("#elfinder").disableSelection();
}

$().ready(function(){

    var upd = $("select[id$=\"xpand-slider-updated\"],input[id$=\"xpand-slider-date\"]");
    upd.attr("disabled", "disabled").addClass("disabled");

    $("select[id$=\"xpand-slider-updated\"]").closest("tr").hide();

    $(".maza-bilde").each(function(e){
        var text = $(this).text();
        text = text.replace(/\s/gi,"");
        arrMa = text.split(",");
        //console.log(text);
        $(this).empty();
        $(this).append("<img src=\"'.e_PLUGIN_ABS.XPNSLD_DIR.XPNSLD_IMG_DIR.'"+arrMa[0]+"\">");
    });


    var bildeM = $("input[id$=\"xpand-slider-imgs\"]");
    var bildeL = $("input[id$=\"xpand-slider-imgs\"]");

    bildeM.hide();
    bildeL.hide();

    //bildel = bildel.val();
    //bildem = bildem.val();

    genMbildes();
    atrLbildes();

    sortOn();

    // getter
    //var items = $( "#elfinder" ).sortable( "option", "items" );

    // setter
    //$( "#elfinder" ).sortable( "option", "items", "div.bildite" );

    $("input[id$=\"nosaukums\"]").on("keyup", function(){
        //$(this).css("border", "1px solid red");
        //$("input[id$=\"alias\"]").val($(this).val().seoURL({"transliterate": true, "lowercase": true}));
        //$(".alias-a").html($(this).val().seoURL({"transliterate": true, "lowercase": true}));
    })

});

$(document).on("click", ".img-add", function () {

    var fm = $("<div/>").dialogelfinder({
        url: "controllers/connector.php",
        lang: "en",
        width: 840,
        destroyOnClose: true,
        UTCDate: true,
        dateFormat: 'M d, Y h:i',
        fancyDateFormat: '$1 H:m:i',
        getFileCallback: function (files, fm) {
            console.log(files);
            console.log(fm);
            pievBildes(files);
        },
        commandsOptions: {
            getfile: {
                // send only URL or URL+path if false
                onlyURL: false,
                // allow to return multiple files info
                multiple: false,
                // allow to return folders info
                folders: false,
                // action after callback (close/destroy)
                oncomplete: 'close'
            }
        },
        uiOptions: {
            // toolbar configuration
            toolbar: [
                ["back", "forward"],
                ["reload"],
                ["home", "up"],
                ["mkdir", "mkfile", "upload"],
                ["open", "download", "getfile"],
                ["info"],
                ["quicklook"],
                ["copy", "cut", "paste"],
                ["rm"],
                ["duplicate", "rename", "edit", "resize"],
                ["extract", "archive"],
                ["search"],
                ["view"],
                ["help"]
            ],
            // directories tree options
            tree: {
                // expand current root on init
                openRootOnLoad: true,
                // auto load current dir parents
                syncTree: true
            },
            // navbar options
            navbar: {
                minWidth: 150,
                maxWidth: 500
            },
            // current working directory options
            cwd: {
                // display parent directory in listing as ".."
                oldSchool: false
            }
        }
    }).dialogelfinder("instance");

    fm.bind("upload", function (e) {
        //console.log(e);
    });

});

var easings = ["linear",
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
"easeInOutBounce"];

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
        enum: ["random","simpleFade", "curtainTopLeft", "curtainTopRight", "curtainBottomLeft", "curtainBottomRight", "curtainSliceLeft", "curtainSliceRight", "blindCurtainTopLeft", "blindCurtainTopRight", "blindCurtainBottomLeft", "blindCurtainBottomRight", "blindCurtainSliceBottom", "blindCurtainSliceTop", "stampede", "mosaic", "mosaicReverse", "mosaicRandom", "mosaicSpiral", "mosaicSpiralReverse", "topLeftBottomRight", "bottomRightTopLeft", "bottomLeftTopRight", "bottomLeftTopRight"],
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

setTimeout(function(){

var BrutusinForms = brutusin["json-forms"];
bf = BrutusinForms.create(dataAttributeOptions);

var container = document.getElementById('form-container');
bf.render(container);

}, 1000);

$(document).on("change", "#form-container", function() {
  $('#extra').attr('value', JSON.stringify(bf.getData()));
});