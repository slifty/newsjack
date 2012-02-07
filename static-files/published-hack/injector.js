(function() {
var topBarHTML = '<div class="hackpub-top-bar">' +
	'<div class="newsjack-logo"><a href="http://www.newsjack.in"><img src="http://www.newsjack.in/logos/newsjack.jpg" /></a></div>' +
	'<span class="desc">This page is a remix that created using <a href="http://www.newsjack.in" class="goggles">NewsJack</a></span>' +
	'<div class="close-button"></div>' +
	'</div>';

  function whenScriptsLoaded() {
	$(document.head).append('<link rel="stylesheet" href="' +
                            path('top-bar.css') + '">');
    var topBar = $(topBarHTML);
	topBar.hide();
    //var original = $("a.original", topBar);
    //original.attr("href", hackpubInfo.originalURL);
    //original.text(original[0].hostname);

    function removeTopBar() {
      topBar.fadeOut(function() { topBar.remove(); });
    }
    
    $("a.goggles", topBar)
      .attr("href", Webxray.getBookmarkletURL(path('../')))
      .click(removeTopBar);
    $(".close-button", topBar).click(removeTopBar);
    $(document.body).append(topBar);
    setTimeout(function(){ topBar.fadeIn(15000); }, 5000);
  }

  function path(url) {
    var baseURL = hackpubInfo.injectURL.match(/(.*)injector\.js$/)[1];
    return baseURL + url;
  }

  window.addEventListener("load", function onLoad() {
    scriptsToLoad = [
      "../jquery.min.js",
      "../src/get-bookmarklet-url.js"
    ].reverse();

    function loadNextScript() {
      if (scriptsToLoad.length) {
        var scriptPath = scriptsToLoad.pop();
          var script = document.createElement("script");

          script.setAttribute("src", path(scriptPath));
          script.onload = loadNextScript;
          document.body.appendChild(script);
      } else {
        whenScriptsLoaded();
      }
    }

    window.removeEventListener("load", onLoad, false);
    loadNextScript();
  }, false);
})();
