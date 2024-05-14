var _GET = function () {
    // This function is anonymous, is executed immediately and
    // the return value is assigned to QueryString!
    var query_string = {};
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        // If first entry with this name
        if (typeof query_string[pair[0]] === "undefined") {
            query_string[pair[0]] = decodeURIComponent(pair[1]);
            // If second entry with this name
        }
        else if (typeof query_string[pair[0]] === "string") {
            var arr = [query_string[pair[0]], decodeURIComponent(pair[1])];
            query_string[pair[0]] = arr;
            // If third or later entry with this name
        }
        else {
            query_string[pair[0]].push(decodeURIComponent(pair[1]));
        }
    }
    return query_string;
}();


function selectPage(pagename) {



    /*
    
    if (pagename === "page_monomers" ) {
    
    var x = document.getElementById("medmenumonomer"); 
    x.style.display = "inline-block";
    
    var y = document.getElementById("medmenumed"); 
    y.style.display = "none";
    
    var z = document.getElementById("medmenudent"); 
    z.style.display = "none";
    }
    
    */

    // Set active page
    $(".cuantum_page").removeClass("active_page");
    $("#" + pagename).addClass("active_page");

    // Set active menu
    $(".menubutton").removeClass("menuselected");
    $(".menubutton[pageid='" + pagename + "']").addClass("menuselected");



    $("html, body").animate({ scrollTop: 0 }, "slow");

    $(".noticia").show();

    // Mostrar el div de cookies_policy específicamente si se selecciona
    if (pagename === "cookies_policy") {
        $("#cookies_policy").show();
    } else {
        $(".noticia").show();
    }

    refit();
}




$(".menubutton").hover(function () {

    var x = document.getElementById("medmenumonomer");
    var y = document.getElementById("medmenumed");
    var z = document.getElementById("medmenudent");

    var v = document.getElementById("menueye");
    var w = document.getElementById("menunail");

    x.style.display = "";
    y.style.display = "";
    z.style.display = "";

    v.style.display = "";
    w.style.display = "";


    d = document.getElementsByClassName("active_page");

    if (d[0].id === "page_monomers") x.style.display = "block";
    if (d[0].id === "page_dent") z.style.display = "block";

    if (d[0].id === "page_eye") v.style.display = "block";
    if (d[0].id === "page_nail") w.style.display = "block";


    //if ( d[0].id === "page_mmed") y.style.display = "block";


    /*
    if ($(this).attr("pageid") === "page_monomers" ) {
    x.style.display = "block";
    //y.style.display = "";
    //z.style.display = "";
    }
    
    if ($(this).attr("pageid") === "page_dent" ) {
    //x.style.display = "";
    //y.style.display = "";
    z.style.display = "block";
    }
    
    */

});



$(".menubutton").click(function () {

    selectPage($(this).attr("pageid"));

});

// Función para mostrar la página de política de cookies cuando se hace clic en el enlace "Más información"
$(".information").click(function (event) {
    // Verificar si el enlace "Más información" fue clicado
    if ($(this).attr("pageid") === "cookies_policy") {
        // Detener el comportamiento predeterminado del enlace
        event.preventDefault();
        // Establecer la página de política de cookies como activa
        selectPage("cookies_policy");
        // Detener el comportamiento predeterminado del enlace
        return false;
    }
});


//$(".menubutton").click( function()          { selectPage( $(this).attr("pageid") ); });
$(".cuantumblockhead").click(function () { selectPage($(this).attr("pageid")); });
$("#cuantumlogo").click(function () { selectPage("page_home"); });


$(".newsnav").click(function () { selectPage("page_news"); });

var currentHomeSlide = 0;
var slideInterval;

function setHomeSlide(num) {
    $("#page_home .cuantumslidershow").addClass("slideinvisible");

    $($("#page_home .cuantumslidershow")[num]).removeClass("slideinvisible");

    $("#page_home .cuantumslider .slideboles img").attr('src', "imatges/bolabuida.png");
    $($("#page_home .cuantumslider .slideboles img")[num]).attr('src', "imatges/bolaplena.png");
}

$(document).ready(function () {
    function nextHomeSlide() {
        clearTimeout(slideInterval);
        currentHomeSlide++;
        currentHomeSlide %= 3;

        setHomeSlide(currentHomeSlide);
        slideInterval = setTimeout(nextHomeSlide, 10000);
    }

    //set an interval
    slideInterval = setTimeout(nextHomeSlide, 10000);
    $("#page_home .cuantumslidershow").click(nextHomeSlide);
});

function refit() {
    if ($("body")[0].clientWidth > 610) {
        var w = $("body").width() - (1190);
        $("#cuantumbody")[0].style.marginLeft = (w > 0) ? (w / 2).toString() + "px" : "0px";
        $("#cuantumbody")[0].style.marginRight = (w > 0) ? (w / 2).toString() + "px" : "0px";
        $(".cuantumslider").css('zoom', "1");
    }
    else {
        var isFirefox = typeof InstallTrigger !== 'undefined';
        if (isFirefox) {
            var vscale = "scale(" + ($("body")[0].clientWidth / 1190).toString() + ")";
            $(".cuantumslider").css('transform-origin', "top left");
            $(".cuantumslider").css('transform', vscale);
            $(".cuantumslider").css('height', '565px');
            $(".cuantumslider").css('margin-left', '0');
            $(".cuantumslider").css('margin-top', '0');
            $(".precuantumslider").css('height', '212px');


        }
        else
            $(".cuantumslider").css('zoom', ($("body")[0].clientWidth / 1190).toString());
    }

}

$(document).ready(function () {
    /*  $.ajax({
          url: "translate.csv",
          async: false,
          success: function (csvd) 
          {
              data = $.csv.toArrays(csvd);
          },
          dataType: "text",
          complete: function () 
          {
              // call a function on complete 
          }
      });
      */
    refit();

    if (_GET.hasOwnProperty('activepage'))
        selectPage(_GET["activepage"]);

    if (_GET.hasOwnProperty('news')) {
        $(".noticia").hide();
        $("[name=news" + _GET["news"] + "]").show();
        delete _GET["news"];
    }


    $("#cuantumbody").fadeTo(1000, 1)
});

$(window).resize(function () {
    refit();
});


