(function(jQuery) {
  "use strict";

  var locale = "en";

  jQuery.localization.extend(locale, "hud-overlay", {
    "default-html": "<span>Let the Remixing Begin!</span>",
    "element": "element",
    "with": "with",
    "id": "id",
    "and": "and",
    "class": "class",
    "pointing-at": "pointing at",
    "focused-intro": "You are on a",
    "ancestor-intro": "It is inside a"
  });

  jQuery.localization.extend(locale, "input", {
    "unload-blocked": 'You have made unsaved changes to this page.',
    "link-click-blocked": 'If you would like to follow that link, please deactivate the remixer first by pressing ESC.'
  });

  jQuery.localization.extend(locale, "command-manager", {
    "executed": "Busted",
    "undid": 'Unbusted',
    "redid": 'Rebusted',
    "cannot-undo-html": '<span>Nothing left to undo!</span>',
    "cannot-redo-html": '<span>Nothing left to redo!</span>'
  });

  jQuery.localization.extend(locale, "mix-master", {
    "too-big-to-change": "That element is too big to remix. Try selecting a smaller one!",
    "too-big-to-remix-html": "<div>That element is too big to remix. Try selecting a smaller one!</div>",
    "deletion": "deletion",
    "replacement": "replacement"
  });

  jQuery.localization.extend(locale, "dialog-common", {
    "ok": "Commit changes",
    "nevermind": "Nevermind",
    "close": "Close",
    "product-name": "NewsJack"
  });

  jQuery.localization.extend(locale, "mix-master-dialog", {
    "html-header": "Edit the News",
    "skeleton-header": "What Your Browser Sees",
    "rendering-header": "What The World Sees",
    "basic-source-tab": "Basic",
    "advanced-source-tab": "Advanced",
    "title": "Define Your Agenda"
  });
  
  jQuery.localization.extend(locale, "uproot-dialog", {
    "header": "Share Your Remix",
    "intro": "There are two ways you can publish your remix and share it with others.",
    "to-internet": "Publish To Internet",
    "to-internet-desc": "Instantly publish your remix to a URL that anyone can view.",
    "view-html": "View HTML Source",
    "view-html-desc": "Grab the HTML source of your remix and publish it yourself.",
    "publishing": "Publishing...",
    "error": "Sorry, an error occurred. Please email slifty@gmail.com and try again later.",
    "success": "Here is the URL for your remix that anyone can view.",
    "html-source": "Here's the HTML source code of your remix."
  });
  
  jQuery.localization.extend(locale, "style-info", {
    "tap-space-html": 'Tap <div class="webxray-kbd">space bar</div> to edit this style.',
    "style-change": "style change"
  });

  jQuery.localization.extend(locale, "key-names", {
    "LEFT": "←",
    "RIGHT": "→",
    "UP": "↑",
    "DOWN": "↓",
    "ESC": "esc",
    "DELETE": "backspace",
    "DELETE-MacIntel": "delete"
  });

  jQuery.localization.extend(locale, "command-descriptions", {
    "help": "This help reference",
    "quit": "Deactivate remixer",
    "remix": "Replace/remix selected element",
    "css-quasimode": "View/edit computed style of selected element",
    "remove": "Remove selected element",
    "undo": "Undo",
    "redo": "Redo",
    "dom-ascend": "Ascend to parent element",
    "dom-descend": "Descend to child element",
    "uproot": "Publish your remix"
  });

  jQuery.localization.extend(locale, "introduction", {
    "headline": 'Welcome to NewsJack.',
    "explanation": 'This is a tool designed to let you redefine the news. Click on anything and you can edit it. Headlines, pictures, links, text, whatever. When you\'re done click the button below to share your remix with the world.'
  });
})(jQuery);