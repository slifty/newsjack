(function() {
var topBarHTML = '<div class="hackpub-top-bar">' +
	'<div id="newsjack-logo"><a href="http://www.newsjack.in"><img src="http://www.newsjack.in/logos/newsjack.jpg" /></a></div>' +
	'<div id="newsjack-about">' +
	'<h1>' + hackpubInfo.dropdownTitle + '</h1>' +
	'<p>This page was remixed from its <a href="' + hackpubInfo.originalURL + '">original source</a>.  <a href="' + hackpubInfo.remixUrl + '">Create a remix of your own.</a></p>' +
	'<p><a href="' + hackpubInfo.originalURL + '" class="newsjack_original_source">View the original source.</a></p>' +
	'</div>' +
	'<div id="newsjack-explanation">'+ hackpubInfo.dropdownExplanation + '</div>' + 
	'</div>';

  function whenScriptsLoaded() {
	$(document.head).append('<link rel="stylesheet" href="' +
                            path('top-bar.css') + '">');
    var topBar = $(topBarHTML);
	topBar.hide();

    function removeTopBar() {
      topBar.fadeOut(function() { topBar.remove(); });
    }
    
    $("a.goggles", topBar)
      .attr("href", Webxray.getBookmarkletURL(path('../')))
      .click(removeTopBar);
    $(".close-button", topBar).click(removeTopBar);
    $(document.body).append(topBar);
    setTimeout(function(){ topBar.slideDown(400); }, 10000);
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
