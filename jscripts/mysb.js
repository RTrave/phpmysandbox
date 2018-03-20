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
 * Few show/hide functions
 */
var slide_toggle = function (vDIV) {
  var slide = document.getElementById(vDIV);
  if(slide==null) return;
  if(!slide.classList.contains("slide")) {
    slide.classList.add("slide");
    slide.classList.add("slide-toggling");
    setTimeout(function(){
      slide.classList.remove("slide-toggling");
      setTimeout(function(){ slide.classList.add("slide"); },500);
      },50);
    return;
  }
  if (slide.classList.contains("slide-toggled")) {
    slide.classList.add("slide-toggling");
    slide.classList.add("slide-untoggling");
    slide.classList.remove("slide-toggled");
    setTimeout(function(){
      slide.classList.remove("slide-untoggling");
      slide.classList.remove("slide-toggling");
      },50);
  } else {
    slide.classList.add("slide-toggling");
    setTimeout(function(){ slide.classList.add("slide-toggled"); },500);
  }
}
var slide_hide = function (vDIV) {
  var slide = document.getElementById(vDIV);
  if(slide==null) return;
  if(!slide.classList.contains("slide")) {
    slide.classList.add("slide");
    slide.classList.add("slide-toggling");
    setTimeout(function(){
      slide.classList.remove("slide-toggling");
      //setTimeout(function(){ slide.classList.add("slide"); },500);
    },50);
    return;
  }
  if (slide.classList.contains("slide-toggled")) {
    slide.classList.add("slide-toggling");
    slide.classList.add("slide-untoggling");
    slide.classList.remove("slide-toggled");
    setTimeout(function(){
      slide.classList.remove("slide-untoggling");
      slide.classList.remove("slide-toggling");
      //setTimeout(function(){ slide.classList.add("slide"); },500);
    },50);
  }
}
var slide_show = function (vDIV) {
  var slide = document.getElementById(vDIV);
  if(slide==null) return;
  if(!slide.classList.contains("slide")) {
    slide.classList.add("slide");
  }
  if (!slide.classList.contains("slide-toggled")) {
    slide.classList.add("slide-toggling");
    setTimeout(function(){ slide.classList.add("slide-toggled"); },500);
  }
}

/**
 * Few show/hide functions !!OBSOLETE!!
 */
function show(vDIV){
  //$("#"+vDIV).fadeIn(300);
  var elem = document.getElementById(vDIV);
  elem.classList.remove("d-hide");
}
function hide(vDIV){
  // $("#"+vDIV).fadeOut(300);
  var elem = document.getElementById(vDIV);
  elem.classList.add("d-hide");
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
  document.body.style.cursor = 'wait';
  document.getElementById('spinlayer').style.display = 'inline-block';
}
function offSpin() {
  document.getElementById('spinlayer').style.display = 'none';
  document.body.style.cursor = 'auto';
}


/**
 * Overlay handling functions
 */
function activeOverlay() {
  var x = document.getElementById("mysbOverlay");
  x.className = "overlay active";
}
function desactiveOverlay() {
  var x = document.getElementById("mysbOverlay");
  x.className = "overlay";
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

// CODE WORK-IN-PROGRESS WITHOUT JQUERY
// pepins pour les script src=""
function insertAndExecute(id, text) {
  domelement = document.getElementById(id);
  domelement.innerHTML = text;
  var scripts = [];

  ret = domelement.childNodes;
  for ( var i = 0; ret[i]; i++ ) {
    if ( scripts && nodeName( ret[i], "script" ) && (!ret[i].type || ret[i].type.toLowerCase() === "text/javascript") ) {
      scripts.push( ret[i].parentNode ? ret[i].parentNode.removeChild( ret[i] ) : ret[i] );
    }
  }

  for(script in scripts) {
    evalScript(scripts[script]);
  }
}
function nodeName( elem, name ) {
  return elem.nodeName && elem.nodeName.toUpperCase() === name.toUpperCase();
}
function evalScript( elem ) {
  data = ( elem.text || elem.textContent || elem.innerHTML || "" );

  var head = document.getElementsByTagName("head")[0] || document.documentElement,
  script = document.createElement("script");
  script.type = "text/javascript";
  if(elem.src) {
    //script.src=elem.src;
    script.setAttribute("src",elem.src);
  };
  script.appendChild( document.createTextNode( data ) );

  head.insertBefore( script, head.firstChild );
  head.removeChild( script );

  if ( elem.parentNode ) {
    elem.parentNode.removeChild( elem );
  }
}



/**
 * Wrap calls to differents layers (always JQuery :/ see code before)
 */

function wrapLayerCalls() {

// CODE WORK-IN-PROGRESS WITHOUT JQUERY
// pepins pour les script src=""
/*
    var modalwrap = document.getElementById("contentWrap");
    [].forEach.call(document.getElementsByTagName('a'), function(el) {
        if(el.classList.contains("overlayed")) {
            el.parentElement.innerHTML = el.parentElement.innerHTML;
        };
    });

    [].forEach.call(document.getElementsByTagName('a'), function(el) {
        if(el.classList.contains("overlayed")) {
            console.log(el.getAttribute("href")+'&overlay=1');
            el.addEventListener('click', function(event){
            event.preventDefault();
            //console.log(this.getAttribute("href")+'&overlay=1');

            if(!confirmOverWin($(this))) return false;
            onSpin();
            prepareOverlay();
            if($("#mysbOverlay").css("display")=="block") {
                desactiveOverlay()
            };
            //var olink = $(this);

            var xhr = new XMLHttpRequest();
            xhr.open('GET', this.getAttribute("href")+'&overlay=1',true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    //alert('User\'s name is ' + xhr.responseText);
                    //modalwrap.innerText = xhr.responseText;
                    insertAndExecute("contentWrap",xhr.responseText);
                    //modalwrap.textContent = xhr.responseText;

                    // activeOverlay();
                    // resizeOverWin();
                    // wrapLayerCalls();
                    // offSpin();
                    console.log('innerOK');
                }
                else {
                    alert('Request failed.  Returned status of ' + xhr.status);
                    offSpin();
                }
            };
            xhr.send();
            });
        };
    });
*/
  var mysbOverlay = document.getElementById("mysbOverlay");
  var wrap = $(".contentWrap");
  $("a.overlayed").off("click");
  $("a.overlayed").click(function(event) {
    event.preventDefault();
    if(!confirmOverWin($(this))) return false;
    onSpin();
    if(mysbOverlay.classList.contains("active")) {
      desactiveOverlay();
    };
    var olink = $(this);
    setTimeout(function(){ wrap.load(olink.attr("href")+"&overlay=1"); },100);
  });

  $("form.overlayed").off("submit");
  $("form.overlayed").submit(function(event) {
    if(!confirmOverWin($(this))) return false;
    onSpin();
    if(mysbOverlay.classList.contains("active")) {
      desactiveOverlay();
    };
    $.ajax({
      type: $(this).attr('method'),
      url: $(this).attr('action')+"&overlay=1",
      data: $(this).serialize(),
      success: function(responseText) {
        setTimeout(function(){ wrap.html(responseText); },100);
      }
    });
    return false;
  });

  $(".close").off("click");
  $(".close").click(function(event) {
    event.preventDefault();
    desactiveOverlay();
  });

  $(document).keyup(function(event) {
    if (event.keyCode == 27) {
      event.preventDefault();
      desactiveOverlay();
    }
  });

  var hwrap = $("div#hidelayer");
  $("a.hidelayed").off("click");
  $("a.hidelayed").click(function(event) {
    event.preventDefault();
    if(!confirmOverWin($(this))) return false;
    onSpin();
    hwrap.load($(this).attr("href")+"&hidelay=1");
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
      }
    });
    return false;
  });
}

/**
 * Hide message tip
 */
function hideMessageTip() {
  var msg = document.getElementById("mysbMessages");
  msg.classList.add("activation");
  setTimeout(function(){ msg.classList.remove("activation"); },1000);
}

/**
 * Load an item
 */
function loadItem(iDiv,iRef) {
  var iwrap = $("div#"+iDiv);
  onSpin();
  slide_hide(iDiv);
  setTimeout(function(){ iwrap.load(iRef+"&itemlay=1&iid="+iDiv); },500);
}

