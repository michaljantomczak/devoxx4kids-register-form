function IteAjaxRequest(url,method,args,success,fail,factory){
    var pub=this;
    var prv={};

    prv.url=url;
    prv.method=method;
    prv.args=args;
    prv.successListener=success;
    prv.failListener=fail;
    prv.factory=factory;
    prv.connect=null;


    pub.execute=function () {
        if(method!='POST' && method!='GET'){
            throw new InvalidArgumentValueException('method','POST or GET');
        }
        if(args==undefined){
            args={};
        }

        prv.factory.executeEvents(prv.factory.listenerRequests);

        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState==4){

                var headers=prv.factory.decodeHeaders(xmlhttp.getAllResponseHeaders());
                var data=prv.factory.encodeResponseData(xmlhttp.responseText,headers);
                if(xmlhttp.status==200){
                    prv.successListener.call(null,data);
                }
                else{
                    prv.failListener.call(null,xmlhttp.status,data,headers);
                }
                prv.factory.executeEvents(prv.factory.listenerResponses);
            }
        }

        xmlhttp.open(method,url,true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded"); //FIXME ustawiać chyba tylko dla POST
        xmlhttp.setRequestHeader("X-Requested-With","XMLHttpRequest");
        xmlhttp.send(prv.factory.encodeQueryString(args));

        prv.connect=xmlhttp;
    };

    pub.abort=function (executeEvent) {
        if(executeEvent=executeEvent===undefined){
            executeEvent=false;
        }
        var failListener=prv.failListener;
        if(!executeEvent){
            prv.failListener=function () {
            };
        }
        prv.connect.abort();

        prv.failListener=failListener;
    };
}

Ite.registerModule('ajax',function(){
	"use strict";
	var prv={};
	var pub=this;

	pub.POST='POST';
	pub.GET='GET';
	prv.listenerRequests=new IteArray();
	prv.listenerResponses=new IteArray();

	pub.addEventRequest=function(listener){
		prv.listenerRequests.push(listener);
	};

	pub.addEventResponse=function(listener){
		prv.listenerResponses.push(listener);
	};

	pub.addEventFail=function (callback) {
		prv.failListener=callback;
    };

	pub.uploadFile=function(url,file,fields,success,progress,fail){
		prv.executeEvents(prv.listenerRequests);
		var formData = new FormData();
		formData.append('file',file,file.name);
		fields['token']=Ite.api().getSessionId();
		for(var i in fields){
			formData.append(i,fields[i]);
		}

		var xmlhttp=new XMLHttpRequest();

		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4){

				var headers=prv.decodeHeaders(xmlhttp.getAllResponseHeaders());
				var data=prv.encodeResponseData(xmlhttp.responseText,headers);
				if(xmlhttp.status==200){
					success(data);
				}
				else{
					fail(xmlhttp.status,data);
				}
				prv.executeEvents(prv.listenerResponses);

			}
		}

		xmlhttp.open("POST",url,true);
		// xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded"); //FIXME ustawiać chyba tylko dla POST
		//TODO potem to wyrzucić
		// xmlhttp.setRequestHeader("X-CookieAC",'jsid='+Ite.api().getSessionId()+'; path=/'); //FIXME ustawiać chyba tylko dla POST
		
		xmlhttp.send(formData);

	}

	pub.execute=function(url,method,args,async,success,fail){
        var request=new IteAjaxRequest(url,method,args,success,fail,prv);
        request.execute();
        return request;
	};

	prv.executeEvents=function(contener){
		for(var i=0; i <contener.length; i++){
			contener[i].call(null);
		}
	}

	prv.encodeResponseData=function(data,headers){
		var contentType=headers['content-type'];
		try{
			if(contentType!=undefined){
				switch(contentType){
					case 'application/json':
						return JSON.parse(data);
				}
			}

		}
		catch(err){
			return null;
		}

		return data;
	}

	prv.decodeHeaders=function(headers){
		var result={};
		var records=headers.toLowerCase().split("\r\n");
		for(var i=0; i<records.length-1; i++){
			var fields=records[i].split(": ",2);
			result[fields[0]]=fields[1];
		}

		return result;
	}

	prv.encodeQueryString=function(fields){
		var result='';
		var first=true;
		for(var kField in fields){
			if(first){
				first=false;
			}
			else{
				result+='&';
			}

			result+=kField+'='+encodeURIComponent(fields[kField]);
		}
		return result;

	}

});