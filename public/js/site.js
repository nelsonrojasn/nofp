function fadeIn(el) {
  el.style.opacity = 0;

  var last = +new Date();
  var tick = function() {
    el.style.opacity = +el.style.opacity + (new Date() - last) / 400;
    last = +new Date();

    if (+el.style.opacity < 1) {
      (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
    }
  };

  tick();
}

function fadeOut(el) {
    var fadeEffect = setInterval(function () {
        if (!el.style.opacity) {
            el.style.opacity = 1;
        }
        if (el.style.opacity > 0) {
            el.style.opacity -= 0.3;
        } else {
            clearInterval(fadeOut);
			if (document.getElementsByTagName('body')[0].contains(el)) {
				document.getElementsByTagName('body')[0].removeChild(el);	
			}
        }
    }, 200);
}


function hideFlash()
{
	var element = document.querySelector('.flash');
	if (element !== null) {
		setTimeout(function(){ 
			fadeOut(element);
		}, 4000);
	}
}

function createFlash(message, status)
{
	var status = status || 'info';
	console.log('creating flash...');
	var iDiv = document.createElement('div');
	iDiv.id = 'flash-content';
	iDiv.className = 'flash ' + status;
	iDiv.style.display = 'block';
	iDiv.innerHTML = message;
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	
	hideFlash();
}


var ajax = {};
ajax.x = function () {
    if (typeof XMLHttpRequest !== 'undefined') {
        return new XMLHttpRequest();
    }
    var versions = [
        "MSXML2.XmlHttp.6.0",
        "MSXML2.XmlHttp.5.0",
        "MSXML2.XmlHttp.4.0",
        "MSXML2.XmlHttp.3.0",
        "MSXML2.XmlHttp.2.0",
        "Microsoft.XmlHttp"
    ];

    var xhr;
    for (var i = 0; i < versions.length; i++) {
        try {
            xhr = new ActiveXObject(versions[i]);
            break;
        } catch (e) {
        }
    }
    return xhr;
};

ajax.send = function (url, callback, method, data, async) {
	var loader = document.querySelector('#loader');
	loader.style.display = 'block';
	
    if (async === undefined) {
        async = true;
    }
    var x = ajax.x();
    x.open(method, url, async);
    x.onreadystatechange = function () {
        if (x.readyState == 4) {
			loader.style.display = 'none';
            callback(x.responseText)
        }
    };
    if (method == 'POST') {
		x.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        x.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    }
    x.send(data)
};

ajax.get = function (url, data, callback, async) {
    var query = [];
    for (var key in data) {
        query.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
    }
    ajax.send(url + (query.length ? '?' + query.join('&') : ''), callback, 'GET', null, async)
};

ajax.post = function (url, data, callback, async) {
    var query = [];
    for (var key in data) {
        query.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
    }
    ajax.send(url, callback, 'POST', query.join('&'), async)
};

