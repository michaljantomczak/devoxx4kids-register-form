Ite.registerModule('api',function(){
	"use strict";
	var prv={};
	var pub=this;

	pub.init=function(){
	};

	pub.execute=function(action,parameters,callback){
		Ite.ajax().execute('/'+action+'.json','POST',prv.successCallback(callback));
	};

	prv.successCallback=function (callback) {
		return function (data) {
			callback.call(null,data);
        };
    };
});