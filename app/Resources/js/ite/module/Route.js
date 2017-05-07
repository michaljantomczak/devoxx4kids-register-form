Ite.registerModule('route', function () {
    "use strict";
    var prv = {};
    var pub = this;
    prv.loadTemplateFailListener = new IteArray();
    prv.changeListener = new IteArray();
    prv.changedListener = new IteArray();
    prv.container=null;
    prv.controller=null;
    prv.currentController=null;
    prv.ajaxRequest=null;

    pub.init = function () {

        Ite.addEventClick(prv.clickListener());

        Ite.addEventPopState(prv.popStateListener);

        pub.executeController();
    };

    pub.executeController=function () {
        if(prv.controller){
            prv.currentController=new prv.controller();
            Ite.logger().debug('Executed construct');
            prv.currentController.construct();
            prv.controller=null;
        }
    };

    pub.setController = function (callback) {
        prv.controller = callback;
    };

    pub.addEventChange = function (listener) {
        prv.changeListener.push(listener);
    };

    pub.addEventChanged = function (listener) {
        prv.changedListener.push(listener);
    };

    pub.redirect = function (url, options) {

        prv.execute(url, options);
    };

    prv.execute = function (url, options) {
        if(prv.ajaxRequest){
            prv.ajaxRequest.abort();
        }

        if(prv.currentController && typeof prv.currentController.destruct=='function'){
            Ite.logger().debug('Executed destruct');
            prv.currentController.destruct();
            prv.currentController=null;
        }

        prv.fireEvent(prv.changeListener, []);

        var template=url;
        if(url==='/'){
            template='/index';
        }

        template+='.html';

        if (options['urlData']) {
            var queryString= '?' + prv.encodeUrl(options['urlData']);
            url+=queryString;
            template+=queryString;
        }

        prv.ajaxRequest=Ite.ajax().execute(template, 'GET', {}, true, prv.loadedListener(url),prv.failLoadedListener);
    };

    prv.loadView=function (template) {
        prv.container.setHtml(template);
        pub.executeController();
    };

    prv.failLoadedListener=function (code,data,headers) {
        prv.fireEvent(prv.loadTemplateFailListener,[code,data,headers]);
    };

    prv.fireEvent = function (listeners, data) {
        listeners.each(function (listener) {
            listener.apply(null, data);
        });
    };

    prv.loadedListener = function (url) {
        return function (data) {
            prv.ajaxRequest=null;
            prv.loadView(data);
            prv.fireEvent(prv.changedListener, [url]);
            prv.addHistory(url, data);
        };
    };

    prv.addHistory = function (url, template) {
        Ite.logger().debug(url);
        window.history.pushState({
            url: url,
            template: template,
        }, null, url);

    };

    prv.encodeUrl = function (data) {
        var result = new IteArray();
        for (var name in data) {
            if (!data.hasOwnProperty(name)) {
                continue;
            }
            result.push(name);
            result.push('=');
            result.push(encodeURIComponent(data[name]));
            result.push('&');
        }

        result.remove(result.length() - 1);
        return result.join('');
    };

    pub.setContainer = function (container) {
        prv.container = Ite.get(container);
    }

    pub.addEventLoadTemplateFail = function (callback) {
        prv.loadTemplateFailListener.push(callback);
    }

    prv.getURL = function () {
        var url = document.URL.substring(document.URL.indexOf('/', 9) + 1);

        var endUrlPosition = url.indexOf("?");
        if (endUrlPosition >= 0) {
            url = url.substring(0, endUrlPosition);
        }
        else {
            var endUrlPosition = url.indexOf("#");
            if (endUrlPosition >= 0) {
                url = url.substring(0, endUrlPosition);
            }
        }

        return url;
    };

    prv.clickListener = function () {

        return function (e) {

            e.setSystemHandle(true);
            Ite.try(function () {
                var obj = e.getTarget();
                if (obj.getTag() != 'A') {
                    obj = obj.getParent();
                }
                var href = obj.getAttribute('href');

                var target = '';
                Ite.try(function () {
                    target = obj.getAttribute('target');
                }).catch(AttributeNotFoundException, function (e) {
                    //ignore
                })

                var hrefPart = href.split('#');
                href = hrefPart[0];

                var protocol=href.split(':',2);
                if(protocol.length===2 && protocol[0]!=='http'){
                    return null;
                }
                else if (hrefPart.length == 2) {//ignore event
                    e.setSystemHandle(false);
                }
                else if (href != '' && target != '_blank' && target != '_top') {
                    e.setSystemHandle(false);

                    prv.changeListener.each(function (listener) {
                        listener.call(null);
                    });
                    var options = {};

                    prv.execute(href, options);
                }

            }).catch(AttributeNotFoundException, function (e) {
                //ignore is not anchor;
            }, true).catch(ObjectNotFoundException, function (e) {
                //ignore
            });
        }

    };

    prv.popStateListener = function (e) {
        var state = e.getOrigin().state;
        prv.changeListener.each(function (listener) {
            listener.call(null);
        });
        if (state) {
            prv.restore(state);
        }
        else {
            var url = prv.getURL();

            prv.execute(url, {
                'updateHistory': false
            });


        }
    };

    prv.restore = function (state) {
        prv.container.setHtml(state.template);
        pub.executeController();
    };

});