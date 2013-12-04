/*
**	MultiLink.js - demo class for multiple link handling
**	Daniel Smith - javajoint@gmail.com
**
**	Depends on jQuery
**
**  This is a demo - I dont claim it is optimized, etc.
*/

var ML = {};

var refNames = new Object;

//This prototype is provided by the Mozilla foundation and
//is distributed under the MIT license.
//http://www.ibiblio.org/pub/Linux/LICENSES/mit.license

if (!Array.prototype.filter)
{
  Array.prototype.filter = function(fun /*, thisp*/)
  {
    var len = this.length;
    if (typeof fun != "function")
      throw new TypeError();

    var res = new Array();
    var thisp = arguments[1];
    for (var i = 0; i < len; i++)
    {
      if (i in this)
      {
        var val = this[i]; // in case fun mutates this
        if (fun.call(thisp, val, i, this))
          res.push(val);
      }
    }

    return res;
  };
}

if (!Array.prototype.diff) {
	Array.prototype.diff = function(a) {
    return this.filter(function(i) {return !(a.indexOf(i) > -1);});
	};
}


// hash sieve taken from:
// http://www.shamasis.net/2009/09/fast-algorithm-to-find-unique-items-in-javascript-array/
Array.prototype.unique = function() {
    var o = {}, i, l = this.length, r = [];
    for(i=0; i<l;i+=1) o[this[i]] = this[i];
    for(i in o) r.push(o[i]);
    return r;
};


function getIntersect(arr1, arr2) {
    var r = [], o = {}, l = arr2.length, i, v;
    for (i = 0; i < l; i++) {
        o[arr2[i]] = true;
    }
    l = arr1.length;
    for (i = 0; i < l; i++) {
        v = arr1[i];
        if (v in o) {
            r.push(v);
        }
    }
    return r;
}

// -----------  functions above will likely go to their own utils.js...


ML.MultiLink = function(params) {
	this.config = params;
	this.init();
}


ML.MultiLink.prototype.init = function() {
	this.curClickedElem = null;
}


ML.MultiLink.prototype.getCSS = function(x, y) {

	// yes, most of this should be in a style sheet.. will be in
	// the next iteration...
	var theCSS = {
		'z-index'	: 100,
		'display'	: "inline",
		'position'	: "absolute",
		'background' : "white",
		'left'		: x,
		'top'		: y,
		'margin-top': "1em",
		'padding'   : "0px 15px 0px 0px",
		'border'	: "1px solid gray",

		"-webkit-border-radius" : "4px",
		"-moz-border-radius"	: "4px",
		"border-radius"			: "4px",

	    "-moz-box-shadow" : "rgba(0,0,0,0.5) 10px 5px 24px 4px",
	    "-moz-text-shadow": "0px 0px 10px #99ccff",

		"-webkit-box-shadow" : "rgba(0,0,0,0.5) 0px 0px 24px",

		'opacity'	: 0
	};

	return theCSS;
}



ML.MultiLink.prototype.cleanArgList = function(aList) {
	var allElems = [];

	// may need to test here for an object...
	var theElems = aList.toString().split(",");

	$.each(theElems, function() {
		allElems.push(this.replace(/\s+|["']+/g, ""));
	});

	return allElems;
}


// which IDs may have this tag?
ML.MultiLink.prototype.searchTags = function(searchStr) {
	var theConfig = this.config;
	var resultSet = [];

	if (searchStr.charAt(0) == ".") {
		searchStr = searchStr.slice(1);
	}

	for (var key in theConfig) {

		theTags = this.cleanArgList(theConfig[key].tags);

		var foundMatch = 0;
		var numTags = theTags.length;

		for (var i = 0; (i < numTags && foundMatch == 0); i++) {
			if (theTags[i] == searchStr) {
				foundMatch++;
				resultSet.push(key);
			}
		}
	}

	return resultSet;
}


// see if we have a match in JSON
ML.MultiLink.prototype.parseElem = function(theElem) {
	var resultSet = [];
	var myIDsWithTag = [];
	var theConfig = this.config;
	var me = this;

	var tokens = theElem.split(' ');

	// are we looking for an 'AND'?
	var needIntersection = 0;
	// are we looking to remove items?
	var needWithout = 0;
	// are we looking for an 'OR'?
	var needUnion = 0;

	$.each(tokens, function() {
		var curToken = this;
		var firstChar = curToken.charAt(0);

		switch (firstChar) {

			// intersection
			case "+":
				if (curToken.length == 1) {
					needIntersection = 1;
				}
				break;

			// without .. subtraction
			case "-":
				if (curToken.length == 1) {
					needWithout = 1;
				}
				break;

			// "OR"
			case "|":
				if (curToken.length == 1) {
					needUnion = 1;
				}
				break;


			// we're looking for a tag
			case ".":
				curResultSet = me.searchTags(curToken);

				if (needWithout) {
					console.log(resultSet);
					resultSet = resultSet.diff(curResultSet);

				} else if (needIntersection) {
					resultSet = getIntersect(resultSet, curResultSet);

				} else if (needUnion) {
					resultSet = resultSet.concat(curResultSet).unique();

				} else {
					resultSet.push.apply(resultSet, curResultSet);
				}
				needWithout = 0;
				needIntersection = 0;
				needUnion = 0;

				break;

			// the normal case of getting data from an id
			default:
				if (theConfig[curToken] !== undefined) {
					resultSet.push(curToken.toString());
				}
				break;
		}
	});

	return resultSet;
}


ML.MultiLink.prototype.removeDiv = function(elem) {

	$('#linkdiv').stop().animate({opacity: 0},300, function() {
		$('#linkdiv').empty();
		$('#linkdiv').removeAttr("style");
		$('#linkdiv').addClass('hideme');
	});
}


ML.MultiLink.prototype.parseLine = function(theStr) {
		me = this;

		var knownWords = [];

		// if we need to split for tag intersections and diffs later,
		// we provide a consistent space separated string
		myData = theStr.replace(/\s+|["']+/g, "");

		myData = myData.replace(/\-{1,}/g, " - ");
		myData = myData.replace(/\+{1,}/g, " + ");
		myData = myData.replace(/\|{1,}/g, " | ");

		myData = myData.replace(/,{1,}/g, ",");
		myData = myData.replace(/\.{1,}/g, ".");
		myData = myData.replace(/\#{1,}/g, "#");

		var dataElem = myData.split(",");

		$.each(dataElem, function() {
			var curWord = this.toString();

			if (refNames.hasOwnProperty(curWord)) {
				// console.log('already have seen');
				// console.log(curWord);
			} else {

				if (curWord.charAt(0) == "#") {

					refNames[curWord] = 1;

					// go find a list of items elsewhere and bundle them in with
					// our current line...oh, and do it recursively...
					checkline = $(curWord).data('dls-links');
					knownWords.push.apply(knownWords, me.parseLine(checkline));

				} else {
					knownWords.push(curWord);
				}
			}
		});


		return knownWords;
}


ML.MultiLink.prototype.doClick = function(elem, id) {
	var theConfig = this.config;
	var me = this;

	refNames[id] = 1;

	if (this.curClickedElem != null &&
		this.curClickedElem != elem) {

		// we dont call removeDiv().. we're not animating..
		// come back to this..
		$('#linkdiv').empty();
		$('#linkdiv').removeAttr("style");
		$('#linkdiv').addClass('hideme');
	}

	if ($('#linkdiv').hasClass('hideme')) {
 		var x = $(elem).position().left;
 		var y = $(elem).position().top;

		var cssObj = this.getCSS(x, y);

		var linkHTML = "";
		var theTargets = [];

		// normalize the list of targets to parse for...
		var myData = $(elem).data('dls-links')


		dataElem = this.parseLine(myData);

		$.each(dataElem, function(cur) {
			theTargets.push.apply(theTargets, me.parseElem(this));
		});

		allSeen = {};
		$.each(theTargets, function(cur) {
			var myCur = this;

			if (theConfig[myCur] !== undefined  && (allSeen[myCur] === undefined)) {
				var url = theConfig[myCur].url;
				var label = theConfig[myCur].label;

				linkHTML += '<a target="mlwindow" href="' + url + '" style="margin: 4px 8px;line-height:1em;padding:6px;">' + label + '</a> / ';

				allSeen[myCur] = 1;
			}
		});

		linkHTML += "";

		$('#linkdiv').html(linkHTML);
		$('#linkdiv').removeClass('hideme');
		$('#linkdiv').css(cssObj);
		$('#linkdiv').stop().animate({opacity: 1.0},200);

		this.curClickedElem = elem;
	} else {
		this.removeDiv();
		this.curClickedElem = null;
	}

	refNames = {};
}


$(document).ready(function() {
	var configObj = ML.Config.allLinks;
	var myML = new ML.MultiLink(configObj);

	$('.multilink').click(function() {
		myML.doClick(this, $(this).attr("id"));
	});
});
