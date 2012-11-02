(function(jQuery) {
  "use strict";
  
  jQuery.webxraySettings = {
    extend: jQuery.extend,
    url: function(name) {
      if (this[name].match(/^https?:/))
        return this[name];
      return this.baseURI + this[name];
    },
    language: navigator.language,
    baseURI: "hackasaurus/",
    cssURL: "webxray.css",
    preferencesURL: "preferences.html",
    easyRemixDialogURL: "easy-remix-dialog/index.html",
    uprootDialogURL: "uproot-dialog.html",
    hackpubURL: "http://hackpub.hackasaurus.org/",
    hackpubInjectionURL: "published-hack/injector.js"
  };
})(jQuery);
