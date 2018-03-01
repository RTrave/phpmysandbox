/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * JavaScript library.
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License', or
 * ('at your option) any later version. 
 * (Roman Travé <roman.trave@gmail.com>, 2012)
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Roman Travé <roman.trave@gmail.com>
 */

// Adding an slide animation on bootstrap's dropdowns
$(document).on(".dropdown1", ".dropdown1", function (event) {
    $(event.target).find(">.dropdown-content").slideUp(150);
});
$(document).on(".dropdown:hover", ".dropdown:hover", function (event) {
    $(event.target).find(".dropdown-content").slideDown(150);
});


/**
 * Navbar responsive toggle
 */
function responsiveToggle(navDIV,baseClass) {
    var x = document.getElementById(navDIV);
    if (x.className === baseClass) {
        x.className += " responsive";
    } else {
        x.className = baseClass;
    }
}


/**
 * Classic confirmation/alert of form submission.
 * Do not use it for over/hide layers (use the data-overconfirm attribute).
 */
function mysb_confirm(text) {
    var a = false, b='';
    a= confirm (text);
    if (a)
        return true;
    else
        return false;
}
function mysb_alert(text) {
    var a = false, b='';
    a= alert (text);
    if (a)
        return true;
    else
        return false;
}

/**
 * Hide message tip
 */
function hideMessageTip() {
    var tip = $("div#mysbMessages");
    tip.delay(3000).fadeOut(2000);
}

/**
 * Few show/hide functions
 */
function toggleSlide(cDIV){
    if( $("."+cDIV).css("display")!="none" )
        $("."+cDIV).slideUp(150, function () { jQuery(this).parent().addClass("closed") } );
    else
        $("."+cDIV).slideDown(150, function () { jQuery(this).parent().addClass("open") } );
}


/**
 * Few show/hide functions
 */
function show(vDIV){
    $("#"+vDIV).fadeIn(300);
}
function hide(vDIV){
    $("#"+vDIV).fadeOut(300);
}
function hide_instant(vDIV){
    $("#"+vDIV).fadeOut(0);
}
function show_auto(vDIV){
    if($("#"+vDIV).css("display")=="block")
        $("#"+vDIV).fadeOut(300);
    else
        $("#"+vDIV).fadeIn(300);
}
function toggle_slide(vDIV){
    if( $("#"+vDIV).css("display")!="none" )
        $("#"+vDIV).slideUp(300, function () { jQuery(this).parent().addClass("closed") } );
    else
        $("#"+vDIV).slideDown(300, function () { jQuery(this).parent().addClass("open") } );
}
function toggle_slide150(vDIV){
    if( $("#"+vDIV).css("display")!="none" )
        $("#"+vDIV).slideUp(150, function () { jQuery(this).parent().addClass("closed") } );
    else
        $("#"+vDIV).slideDown(150, function () { jQuery(this).parent().addClass("open") } );
}
function show_slide(vDIV){
    $("#"+vDIV).slideDown(300, function () { jQuery(this).parent().addClass("open") } );
}
function hide_slide(vDIV){
    $("#"+vDIV).slideUp(300, function () { jQuery(this).parent().addClass("closed") } );
}


/**
 * Spin/wait handling
 */

function loadSpin() {
    var opts = {
        color: '#000', // #rgb or #rrggbb or array of colors
        className: 'spinner', // The CSS class to assign to the spinner
        top: '25%', // Top position relative to parent
    };
    var target = document.getElementById("spinlayer"); //
    var spinner = new Spinner(opts).spin(target);
}
function onSpin() {
    $("body").css("cursor","wait");
    $("#spinlayer").css("display","inline-block");
}
function offSpin() {
    $("#spinlayer").css("display","none");
    $("body").css("cursor","auto");
    //window.console&&console.log("offSpin()");
}

/**
 * Overlay handling functions
 */
function prepareOverlay() {
    $("#mysbOverlay").fadeIn(100);
}
function activeOverlay() {
    $("#mysbOverlay").promise().done(function(){
        $("#mysbOverlay").css("display","block");
        //$("#overlay").css("position","fixed");
        $("#mysbModal").fadeIn(300).css("display","inline-block");
        // $("#overlay").css("top","15px");
    });
}
function desactiveOverlay() {
    $("#mysbModal").fadeOut(150);
    $("#mysbOverlay").fadeOut(150);
    $("#mysbModal").promise().done(function(){
        $(".contentWrap").html("...");
    });
    offSpin();
}

/**
 * Overlay windows handling functions
 */
function resizeOverWin() {
  var e = window;
  var a = 'inner';
  if ( !( 'innerWidth' in window ) ) {
    a = 'client'; e = document.documentElement || document.body;
  }

  var mhContent = ((e[ a+'Height' ]-100)*0.8);
  if(mhContent<200) { mhContent = 200; };
  var mhBody = ((e[ a+'Height' ]-150)*0.7);
  if(mhBody<150) { mhBody = 150; };

  [].forEach.call(document.getElementsByClassName("modalContent"), function(el) {
    el.setAttribute("style", "max-height: "+mhContent+"px;");
  });
  [].forEach.call(document.getElementsByClassName("modalBody"), function(el) {
    el.setAttribute("style", "max-height: "+mhBody+"px;");
  });
}
window.addEventListener('resize', function(){resizeOverWin();}, true);


function confirmOverWin(trigger) {
    if(!trigger[0].hasAttribute("data-overconfirm")) return true;
    var str = trigger.attr("data-overconfirm");
    a = confirm( str.replace(/\\n/g,"\n").replace(/\\\'/g,"\'") );
    if (a) return true;
    else return false;
}
function wrapLayerCalls() {
    var wrap = $(".contentWrap");
    $("a.overlayed").off("click");
    $("a.overlayed").click(function(event) {
        event.preventDefault();
        if(!confirmOverWin($(this))) return false;
        onSpin();
        prepareOverlay();
        //window.console&&console.log("LOG1: "+$("#overlay").css("display"));
        if($("#mysbModal").css("display")=="block") {
            $("#mysbModal").fadeOut(50);
        };
        var olink = $(this);
        $("#mysbModal").promise().done(function(){
            wrap.load(olink.attr("href")+"&overlay=1");
        });
    });
    $("form.overlayed").off("submit");
    $("form.overlayed").submit(function(event) {
        if(!confirmOverWin($(this))) return false;
        if($("#mysbModal").css("display")=="block") {
            $("#mysbModal").fadeOut(200);
        };
        onSpin();
        prepareOverlay();
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action')+"&overlay=1", 
            data: $(this).serialize(), 
            success: function(responseText) {
                
                $("#mysbModal").promise().done(function(){
                    wrap.html(responseText);
                });
            }
        });
        return false;
    });
    $(".close").off("click");
    $(".close").click(function(event) {
        event.preventDefault();
        desactiveOverlay();
    });
    $("#mysbOverlay1").off("click");
    $("#mysbOverlay1").click(function(event) {
        event.preventDefault();
        desactiveOverlay();
    });
    $(document).keyup(function(event) {
        if (event.keyCode == 27) {
            event.preventDefault();
            desactiveOverlay();
        }
    });
    $("div#mysbMessages").off("click");
    $("div#mysbMessages").click(function(event) {
        $("div#mysbMessages").css("display","none");
    });
    var hwrap = $("div#hidelayer");
    $("a.hidelayed").off("click");
    $("a.hidelayed").click(function(event) {
        event.preventDefault();
        if(!confirmOverWin($(this))) return false;
        onSpin();
        hwrap.load($(this).attr("href")+"&hidelay=1");
        desactiveOverlay();
    });
    $("form.hidelayed").off("submit");
    $("form.hidelayed").submit(function(event) {
        if(!confirmOverWin($(this))) return false;
        onSpin();
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action')+"&hidelay=1", 
            data: $(this).serialize(), 
            success: function(responseText) {
                hwrap.html(responseText);
                desactiveOverlay();
            }
        });
        return false;
    });
    // hide border-bottom from last row-list element
    //$("div.row:last-child").css("border-bottom","0px");
}

/**
 * Hide message tip
 */
function hideMessageTip() {
    var tip = $("div#mysbMessages");
    tip.delay(3000).fadeOut(2000);
}

/**
 * Load an item
 */
function loadItem(iDiv,iRef) {
    var iwrap = $("div#"+iDiv);
    //window.console&&console.log("loadItem");
    onSpin();
    hide_slide(iDiv);
    iwrap.promise().done(function(){
        this.load(iRef+"&itemlay=1&iid="+iDiv);
        this.promise().done(function(){
            //show(iDiv);
        });
    });
}
