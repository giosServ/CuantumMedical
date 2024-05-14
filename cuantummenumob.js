var _GET= function () 
{
	// This function is anonymous, is executed immediately and
	// the return value is assigned to QueryString!
	var query_string = {};
	var query = window.location.search.substring(1);
	var vars = query.split("&");
	for (var i=0;i<vars.length;i++) 
	{
		var pair = vars[i].split("=");
		// If first entry with this name
		if (typeof query_string[pair[0]] === "undefined") 
		{
			query_string[pair[0]] = decodeURIComponent(pair[1]);
			// If second entry with this name
		} 
		else if (typeof query_string[pair[0]] === "string") 
		{
			var arr = [ query_string[pair[0]],decodeURIComponent(pair[1]) ];
			query_string[pair[0]] = arr;
			// If third or later entry with this name
		} 
		else 
		{
			query_string[pair[0]].push(decodeURIComponent(pair[1]));
		}
	} 
	return query_string;
}();


function selectPage(pagename) 
{
    // Set active page
    $(".cuantum_page").removeClass( "active_page" );
    $("#"+pagename).addClass( "active_page" );
    
    // Set active menu
    $(".menubutton").removeClass( "menuselected" );
    $(".menubutton[pageid='"+pagename+"']").addClass( "menuselected" );
    
    $("html, body").animate({ scrollTop: 0 }, "slow");
	
    $(".noticia").show();
    
    refit();
}


$(".menubutton").click( function()          { selectPage( $(this).attr("pageid") ); });
$(".cuantumblockhead").click( function()    { selectPage( $(this).attr("pageid") );  });
$("#cuantumlogo").click( function()         { selectPage( "page_home" );  });


$(".newsnav").click( function()         { selectPage( "page_news" );  });

var currentHomeSlide = 0;
var slideInterval;

function setHomeSlide( num )
{
    $("#page_home .cuantumslidershow").addClass( "slideinvisible" );
    
    $($("#page_home .cuantumslidershow")[num]).removeClass( "slideinvisible" );  
    
     $("#page_home .cuantumslider .slideboles img").attr('src', "imatges/bolabuida.png");
    $($("#page_home .cuantumslider .slideboles img")[num]).attr('src', "imatges/bolaplena.png");
}

$(document).ready( function() 
{   
    function nextHomeSlide()
    {
        clearTimeout( slideInterval );
        currentHomeSlide++;
        currentHomeSlide %= 3;
       
        setHomeSlide(currentHomeSlide);
        slideInterval = setTimeout(nextHomeSlide, 10000);
    }

    //set an interval
    slideInterval = setTimeout(nextHomeSlide, 10000);
     $("#page_home .cuantumslidershow").click( nextHomeSlide );
});

function refit()
{
    if( $("body")[0].clientWidth > 610)
    {
        var w = $( "body" ).width() - (1190);
        $("#cuantumbody")[0].style.marginLeft = (  w > 0) ? (w/2).toString()+"px":  "0px";    
        $("#cuantumbody")[0].style.marginRight = (  w > 0) ? (w/2).toString()+"px":  "0px";    
        $(".cuantumslider").css( 'zoom',  "1" );
    }
    else
      {
            $(".cuantumslider").css( 'zoom',  ($("body")[0].clientWidth / 1190).toString() );
      }

}

$(document).ready( function() 
{ 
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
		selectPage( _GET["activepage"] );

	if (_GET.hasOwnProperty('news'))
	{
		$(".noticia").hide();
		$("[name=news"+_GET["news"]+"]").show();
		delete _GET["news"];
	}
    
    
    $("#cuantumbody").fadeTo(1000,1)
});

$( window ).resize(function() 
{
 refit();
});
