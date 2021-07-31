/**
 * Sequencer - A fast(?) fullscreen image-sequence player. (c) 2012 See
 * README.txt or visit github (link below) for details
 * 
 * Author: Andreas Gysin ertdfgcvb.com
 * 
 * @andreasgysin
 * 
 * Project page: http://ertdfgcvb.com/sequencer
 * http://github.com/ertdfgcvb/Sequencer
 */

var Sequencer = (function() {

    var current = -1;
    var imgList = [];
    var progress;
    var playInterval;
    var playDir = 1;

    // configuration defaults
    var config = {
        folder : "", // folder containing the image sequence
        list : [], // list of files
        bgColor : "grey", // page background color
        scaleMode : "contain", // as in CSS3, can be: auto, cover, contain
        mouseDirection : "x", // mouse direction, can be x, -x, y, -y, applies
                                // only if playMode == "mouse"
        playMode : "mouse", // can be: mouse, loop, pong or none (in this case a
                            // nextImage() call has to be made somewhere
        playInterval : 200, // interval in milliseconds beteen each frame,
                            // applies only if playMode != "mouse"
        progressDiam : "120", // progress diameter
        progressFontFamily : "Helvetica, Arial, sans-serif",
        progressFontSize : "2em",
        progressBgColor : "",
        progressFgColor : "yellow",
        progressMode : "bar", // can be: circle, bar, none
        progressHeight : "40px", // if progressMode == "bar"
        progressShowImages : true, // display images while loaded
    }

    function init(customConfig) {
        // config override
        for (prop in customConfig) {
            config[prop] = customConfig[prop];
        }
        window.onload = function() {
            configureBody();
            buildProgress(config.progressMode);
            loadNext();
        }
    }

    function setPlayMode(mode) {
        stop();
        config.playMode = mode;
    }

    function play() {
        stop();
        if (config.playMode == "mouse") {
            document.addEventListener('mousemove', onMouseMove, false);
            document.ontouchmove = function(e) {
                onMouseMove(e.touches[0]);
                return false;
            }
        } else if (config.playMode == "loop" || config.playMode == "pong") {
            playInterval = setInterval(nextImage, config.playInterval);
        }
    }

    function stop() {
        document.removeEventListener('mousemove', onMouseMove);
        if (playInterval) {
            clearInterval(playInterval);
            playInterval == null;
        }
    }

    function nextImage(mode) {
        if (!mode)
            mode = config.playMode;
        if (mode == "pong") {
            current += playDir;
            if (current >= imgList.length - 1) { // current could ev. change
                                                    // by other playmodes, so
                                                    // extra-checks are
                                                    // necessary
                playDir = -1;
                current = imgList.length - 1;
            } else if (current <= 0) {
                playDir = 1;
                current = 0;
            }
            showImage(current);
        } else {
            showImage(++current % imgList.length); // loop
        }
    }

    function onMouseMove(e) {
        var t = imgList.length;
        var m, w;
        if (config.mouseDirection == "x") {
            w = window.innerWidth;
            m = e.pageX;
        } else if (config.mouseDirection == "-x") {
            w = window.innerWidth;
            m = w - e.pageX - 1;
        } else if (config.mouseDirection == "y") {
            w = window.innerHeight;
            m = e.pageY;
        } else if (config.mouseDirection == "-y") {
            w = window.innerHeight;
            m = w - e.pageY - 1;
        }

        var id = Math.min(t, Math.max(0, Math.floor(m / w * t)));
        if (id != current) {
            showImage(id);
            current = id;
            if (progress)
                progress.update(current);
        }
    }

    function buildProgress(mode) {
        if (mode == "circle") {
            progress = document.createElement('div');
            progress.id = "progress";
            progress.style.width = config.progressDiam + "px";
            progress.style.height = config.progressDiam + "px";
            progress.style.lineHeight = config.progressDiam + "px";
            progress.style.textAlign = "center";
            progress.style.color = config.progressFgColor;
            progress.style.backgroundColor = config.progressBgColor;
            progress.style.borderRadius = config.progressDiam / 2 + "px";
            progress.style.position = "fixed";
            progress.style.left = "50%";
            progress.style.top = "80%";
            progress.style.marginTop = -config.progressDiam / 2 + "px";
            progress.style.marginLeft = -config.progressDiam / 2 + "px";
            progress.style.fontFamily = config.progressFontFamily;
            progress.style.fontSize = config.progressFontSize;
            progress.style.zIndex = 1000;
            progress.update = function(num) {
                var t = Math.floor((config.list.length - 1));
                progress.style.width = t + "%";
                var base_url = url.substr(0, url.indexOf('?')); 
                var start = base_url.length - 19
                progress.innerHTML = (num + 1) + ". " + config.list[num].substr(start, 2) + ":" + config.list[num].substr(start + 3, 2) + ":" + config.list[num].substr(start + 6, 2);
            }
            document.body.appendChild(progress);
        } else if (mode == "bar") {
            progress = document.createElement('div');
            progress.id = "progress";
            progress.style.width = "0%";
            progress.style.height = config.progressHeight + "px";
            progress.style.textAlign = "center";
            progress.style.color = config.progressFgColor;
            progress.style.backgroundColor = config.progressBgColor;
            progress.style.position = "fixed";
            progress.style.left = "0";
            progress.style.height = config.progressHeight;
            progress.style.top = "90%";
            progress.style.fontFamily = config.progressFontFamily;
            progress.style.fontSize = config.progressFontSize;
            progress.style.zIndex = 1000;
            progress.update = function(num) {
                var p = Math.round(num / (config.list.length - 1) * 100) + 25;
                progress.style.width = p + "%";
                var url =  config.list[num];
                var base_url = url.substr(0, url.indexOf('?')); 
                var start = base_url.length - 19
                progress.innerHTML = (num + 1) + ". " + config.list[num].substr(start, 2) + ":" + config.list[num].substr(start + 3, 2) + ":" + config.list[num].substr(start + 6, 2);
            }
            document.body.appendChild(progress);
        }
    }

    // 	
    function configureBody() {
        document.body.style.margin = "0";
        document.body.style.padding = "0";
        document.body.style.height = "60%";
        document.body.style.backgroundColor = "grey";
        document.body.style.backgroundRepeat = "no-repeat";
        document.body.style.backgroundPosition = "0px 150px";
        document.body.style.backgroundAttachment = "fixed";
        document.body.style.backgroundSize = config.scaleMode;
    }

    function loadNext() {
        current++;
        var num = current;
        if (num <= config.list.length - 1) {
            var img = new Image();
            img.src = config.folder + config.list[num];
            img.onload = function() {
                imgList.push(this);
                if (config.progressShowImages)
                    showImage(current);
                if (progress)
                    progress.update(current);
                loadNext();
            }
        } else {
            current = imgList.length - 1;
            if (progress) {
                // document.body.removeChild(progress);
                // progress = null;
            }
            setPlayMode(config.playMode);
            play();
        }
    }

    function showImage(id) {
        document.body.style.backgroundImage = "url(" + imgList[id].src + ")";
    }

    return {
        init : init,
        nextImage : nextImage,
        setPlayMode : setPlayMode,
        play : play,
        stop : stop
    };
})();
