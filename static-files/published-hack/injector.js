(function() {
var topBarHTML = '<div class="hackpub-top-bar">' +
	'<div id="newsjack-logo"><a href="http://www.newsjack.in"><img src="http://www.newsjack.in/logos/newsjack.jpg" /></a></div>' +
	'<div id="newsjack-about">' +
	'<h1>You\'ve been Newsjacked!</h1>' +
	'<p>This page was remixed from its <a href="' + hackpubInfo.originalURL + '">original news source</a> using <a href="http://www.newsjack.in">Newsjack</a>.  <a href="http://www.newsjack.in">Create a Newsjack of your own.</a></p>' +
	'<p><a href="' + hackpubInfo.originalURL + '" class="newsjack_original_source">View the original news source.</a></p>' +
	'</div>' +
	'<div id="newsjack-credits">NewsJack is a project of MIT\'s <a href="http://civic.mit.edu">Center for Civic Media</a> by <a href="http://slifty.com">slifty</a> and <a href="http://schock.cc">schock</a>.</div>' + 
//	'<div id="newsjack-license"><a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-sa/3.0/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dct:title" rel="dct:type">NewsJack</span> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>.</div>' +
//	'<div class="close-button"></div>' +
//	'<a href="http://github.com/slifty/newsjack"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://a248.e.akamai.net/assets.github.com/img/4c7dc970b89fd04b81c8e221ba88ff99a06c6b61/687474703a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f77686974655f6666666666662e706e67" alt="Fork me on GitHub"></a>'+
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
