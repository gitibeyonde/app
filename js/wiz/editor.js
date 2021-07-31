
let vh = (window.innerHeight
    || document.documentElement.clientHeight
    || document.body.clientHeight)
    * 0.01;
document.documentElement.style.setProperty('--vh', `${vh}px`);
// We listen to the resize event
window.addEventListener('resize', () => {
    // We execute the same script as before
    let vh = (window.innerHeight
        || document.documentElement.clientHeight
        || document.body.clientHeight)
        * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
});
window.addEventListener('load', () => {
    // We execute the same script as before
    let vh = (window.innerHeight
        || document.documentElement.clientHeight
        || document.body.clientHeight)
        * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
});

var imported = document.createElement('script');
imported.src = '/js/wiz/tidy.js';
document.head.appendChild(imported);


options = {
  "indent":"auto",
  "indent-spaces":2,
  "wrap":80,
  "markup":true,
  "output-xml":false,
  "numeric-entities":true,
  "quote-marks":true,
  "quote-nbsp":false,
  "show-body-only":true,
  "quote-ampersand":false,
  "break-before-br":true,
  "uppercase-tags":false,
  "uppercase-attributes":false,
  "drop-font-tags":true,
  "tidy-mark":false
}


var oDoc = null;
var sDefTxt = null;
var isHTML = true;

function initDoc() {
    oDoc = document.getElementById("htmlEditorPane");
    sDefTxt = oDoc.innerHTML;
    if (document.nodeform.switchMode.checked) {
        setDocMode(true);
        isHTML = true;
    }
}

function formatDoc(sCmd, sValue) {
    console.log("Command = " + sCmd + " value=" + sValue);
    if (validateMode()) {
        if (sCmd == "formatBlock"){
            //insert a div so that it is replaced with the sValue
            document.execCommand("bold", false, null);
            document.execCommand(sCmd, false, sValue);
            document.execCommand("undo", false, null);
        }
        else {
            document.execCommand(sCmd, false, sValue);
        }
        oDoc.focus();
    }
}

function validateMode() {
    if (!document.nodeform.switchMode.checked) {
        return true;
    }
    alert("Uncheck \"HTML\".");
    oDoc.focus();
    return false;
}

function setDocMode(showHTML) {
    console.log("-----------------");
    if (oDoc == null) {
        console.log("oDoc is NULL !!");
        oDoc = document.getElementById("htmlEditorPane");
    }
    console.log("setDocMode" + showHTML);
    console.log("doc inner html" + tidy_html5(oDoc.innerHTML, options));
    var oContent;
    if (showHTML) {
        oContent = document.createTextNode(tidy_html5(oDoc.innerHTML, options));
        oDoc.innerHTML = "";
        var oPre = document.createElement("pre");
        oDoc.contentEditable = false;
        isHTML = false;
        oPre.id = "sourceText";
        oPre.contentEditable = true;
        oPre.appendChild(oContent);
        oDoc.appendChild(oPre);
        document.execCommand("defaultParagraphSeparator", false, "div");
        console.log("HTML text=" + oPre.innerHTML);
    } else {
        if (document.all) {
            oDoc.innerHTML = oDoc.innerText;
            console.log("Doc All Text=" + oDoc.innerHTML);
        } else {
            console.log("Doc TExt" + oDoc.innerHTML);
            oContent = document.createRange();
            oContent.selectNodeContents(oDoc.firstChild);
            oDoc.innerHTML = oContent.toString();
            console.log("Rendered=" + oDoc.innerHTML);
        }
        oDoc.contentEditable = true;
        isHTML = true;
    }
    oDoc.focus();
}

function printDoc() {
    if (!validateMode()) {
        return;
    }
    var oPrntWin = window
        .open(
            "",
            "_blank",
            "width=450,height=470,left=400,top=100,menubar=yes,toolbar=no,location=no,scrollbars=yes");
    oPrntWin.document.open();
    oPrntWin.document
        .write("<!doctype html><html><head><title>Print<\/title><\/head><body onload=\"print();\">"
            + oDoc.innerHTML + "<\/body><\/html>");
    oPrntWin.document.close();
}

function insertAtCursor(text) {
    console.log("isHTML" + isHTML);
    if (isHTML) {
        insertHtmlAtCursor(text, true);
    }
    else {
        insertTextAtCursor(text);
    }
}

function insertTextAtCursor(text) {
    let selection = window.getSelection();
    let range = selection.getRangeAt(0);
    range.deleteContents();
    let node = document.createTextNode(text);
    range.insertNode(node);

    for (let position = 0; position != text.length; position++) {
        selection.modify("move", "right", "character");
    };
}
function insertHtmlAtCursor(html, selectPastedContent) {
    var sel, range;
    if (window.getSelection) {
        // IE9 and non-IE
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();

            // Range.createContextualFragment() would be useful here but is
            // only relatively recently standardized and is not supported in
            // some browsers (IE9, for one)
            var el = document.createElement("div");
            el.innerHTML = html;
            var frag = document.createDocumentFragment(), node, lastNode;
            while ((node = el.firstChild)) {
                lastNode = frag.appendChild(node);
            }
            var firstNode = frag.firstChild;
            range.insertNode(frag);

            // Preserve the selection
            if (lastNode) {
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                if (selectPastedContent) {
                    range.setStartBefore(firstNode);
                } else {
                    range.collapse(true);
                }
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    } else if ((sel = document.selection) && sel.type != "Control") {
        // IE < 9
        var originalRange = sel.createRange();
        originalRange.collapse(true);
        sel.createRange().pasteHTML(html);
        if (selectPastedContent) {
            range = sel.createRange();
            range.setEndPoint("StartToStart", originalRange);
            range.select();
        }
    }
}



$("#htmlEditorPane").on("DOMNodeInserted", $.proxy(function(e) {
    if (e.target.parentNode.getAttribute("contenteditable") === "true") {
        var newTextNode = document.createTextNode("");
        function antiChrome(node) {
            if (node.nodeType == 3) {
                newTextNode.nodeValue += node.nodeValue.replace(/(\r\n|\n|\r)/gm, "")
            }
            else if (node.nodeType == 1 && node.childNodes) {
                for (var i = 0; i < node.childNodes.length; ++i) {
                    antiChrome(node.childNodes[i]);
                }
            }
        }
        antiChrome(e.target);
        console.log(e.target);
        e.target.parentNode.replaceChild(newTextNode, e.target);
    }
}, this));


var editorScrollTop = 0;
var editorScrollLeft = 0;

function setScrollPosition(){
    var elmnt = document.getElementById("htmlEditorPane");
    editorScrollLeft = elmnt.scrollLeft;
    editorScrollTop = elmnt.scrollTop;
}

function enableImageResizeInDiv(id) { // id = htmlEditorPane
    var resizing = false;
    var currentImage;
    var editor = document.getElementById(id);
    var editorRect = editor.getBoundingClientRect();
    
        
    var createDOM = function (elementType, className, styles) {
        let ele = document.createElement(elementType);
        ele.className = className;
        setStyle(ele, styles);
        return ele;
    };
    var setStyle = function (ele, styles) {
        for (key in styles) {
            ele.style[key] = styles[key];
        }
        return ele;
    };
    var removeResizeFrame = function () {
        document.querySelectorAll(".resize-frame,.resizer").forEach((item) => item.parentNode.removeChild(item));
    };
    var offset = function offset(el) {
        const rect = el.getBoundingClientRect(),
        scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
        scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        return { top: rect.top + scrollTop, left: rect.left + scrollLeft }
    };
    var clickImage = function (img) {
        removeResizeFrame();
        currentImage = img;
        
        var imgRect = img.getBoundingClientRect(); //left, top, right, bottom, x, y, width, and height
        var imgHeight = imgRect.height;
        var imgWidth = imgRect.width;

        var top = imgRect.top  - editorRect.top + editorScrollTop;
        var left = imgRect.left  - editorRect.left + editorScrollLeft;

        console.log("click top=" + top + " left " + left);
        console.log("Editor top=" + editorScrollTop + " left " + editorScrollLeft);


        editor.append(createDOM('span', 'resize-frame', {
            margin: '10px',
            position: 'absolute',
            top: (top + imgHeight - 20) + 'px',
            left: (left + imgWidth - 20) + 'px',
            border: 'solid 3px red',
            width: '10px',
            height: '10px',
            cursor: 'se-resize',
            zIndex: 1
        }));

        editor.append(createDOM('span', 'resizer top-border', {
            position: 'absolute',
            top: (top + 10) + 'px',
            left: (left + 10) + 'px',
            border: 'dashed 1px grey',
            width: (imgWidth - 10 )+ 'px',
            height: '0px'
        }));

        editor.append(createDOM('span', 'resizer left-border', {
            position: 'absolute',
            top: (top + 10) + 'px',
            left: (left + 10) + 'px',
            border: 'dashed 1px grey',
            width: '0px',
            height: (imgHeight - 10) + 'px'
        }));

        editor.append(createDOM('span', 'resizer right-border', {
            position: 'absolute',
            top: (top - 10) + 'px',
            left: (left + imgWidth - 10) + 'px',
            border: 'dashed 1px grey',
            width: '0px',
            height: (imgHeight-10) + 'px'
        }));

        editor.append(createDOM('span', 'resizer bottom-border', {
            position: 'absolute',
            top: (top + imgHeight - 10) + 'px',
            left: (left - 10) + 'px',
            border: 'dashed 1px grey',
            width: (imgWidth-10) + 'px',
            height: '0px'
        }));

        document.querySelector('.resize-frame').onmousedown = () => {
            resizing = true;
            return false;
        };
        window.onresize = function(){
            editorRect = editor.getBoundingClientRect();
            refresh();
            currentImage.click();
        };
        editor.onmouseup = () => {
            if (resizing) {
                currentImage.style.width = document.querySelector('.top-border').offsetWidth + 'px';
                currentImage.style.height = document.querySelector('.left-border').offsetHeight + 'px';
                refresh();
                currentImage.click();
                resizing = false;
            }
        };

        editor.onmousemove = (e) => {
            if (currentImage && resizing) {
                let height = e.pageY - offset(currentImage).top;
                let width = e.pageX - offset(currentImage).left;
                height = height < 1 ? 10 : height;
                width = width < 1 ? 10 : width;

                var imgRect = img.getBoundingClientRect();

                const top = imgRect.top  - editorRect.top + editorScrollTop;
                const left = imgRect.left  - editorRect.left + editorScrollLeft;

                console.log("mouse modeve top=" + top + " left " + left);
                setStyle(document.querySelector('.resize-frame'), {
                    top: (top + height - 10) + 'px',
                    left: (left + width - 10) + "px"
                });

                setStyle(document.querySelector('.top-border'), { width: width + "px" });
                setStyle(document.querySelector('.left-border'), { height: height + "px" });
                setStyle(document.querySelector('.right-border'), {
                    left: (left + width) + 'px',
                    height: height + "px"
                });
                setStyle(document.querySelector('.bottom-border'), {
                    top: (top + height) + 'px',
                    width: width + "px"
                });
            }
            return false;
        };
    };
    var bindClickListener = function () {
        editor.querySelectorAll('img').forEach((img, i) => {
            img.onclick = (e) => {
                if (e.target === img) {
                    clickImage(img);
                }
            };
        });
    };
    var refresh = function () {
        bindClickListener();
        removeResizeFrame();
        if (!currentImage) {
            return;
        }
        var img = currentImage;
        var imgHeight = img.offsetHeight;
        var imgWidth = img.offsetWidth;

        var imgRect = img.getBoundingClientRect();

        const top = imgRect.top  - editorRect.top + editorScrollTop;
        const left = imgRect.left  - editorRect.left + editorScrollLeft;
        console.log("refersh top=" + top + " left " + left);

        editor.append(createDOM('span', 'resize-frame', {
            position: 'absolute',
            top: (top + imgHeight) + 'px',
            left: (left + imgWidth) + 'px',
            border: 'solid 2px red',
            width: '6px',
            height: '6px',
            cursor: 'se-resize',
            zIndex: 1
        }));

        editor.append(createDOM('span', 'resizer', {
            position: 'absolute',
            top: (top) + 'px',
            left: (left) + 'px',
            border: 'dashed 1px grey',
            width: imgWidth + 'px',
            height: '0px'
        }));

        editor.append(createDOM('span', 'resizer', {
            position: 'absolute',
            top: (top) + 'px',
            left: (left + imgWidth) + 'px',
            border: 'dashed 1px grey',
            width: '0px',
            height: imgHeight + 'px'
        }));

        editor.append(createDOM('span', 'resizer', {
            position: 'absolute',
            top: (top + imgHeight) + 'px',
            left: (left) + 'px',
            border: 'dashed 1px grey',
            width: imgWidth + 'px',
            height: '0px'
        }));
    };
    var reset = function () {
        if (currentImage != null) {
            currentImage = null;
            resizing = false;
            removeResizeFrame();
        }
        bindClickListener();
    };
    editor.addEventListener('scroll', function () {
        reset();
    }, false);
    editor.addEventListener('mouseup', function (e) {
        if (!resizing) {
            const x = (e.x) ? e.x : e.clientX;
            const y = (e.y) ? e.y : e.clientY;
            let mouseUpElement = document.elementFromPoint(x, y);
            if (mouseUpElement) {
                let matchingElement = null;
                if (mouseUpElement.tagName === 'IMG') {
                    matchingElement = mouseUpElement;
                }
                if (!matchingElement) {
                    reset();
                } else {
                    clickImage(matchingElement);
                }
            }
        }
    });
}