Ite.registerModule('loadMask',function(unsecureContent,secureContent){
	"use strict";
	var prv={};
	var pub=this;
	pub.show=function(message){
		message=message||'Proszę czekać.';
		Ite.setCursor('wait');
		Ite.get('.loader-server').removeClass('hide');
		Ite.get('.loader-server .loader-label').setText(message);
		Ite.get('.loader-server').getDOMElement().style.opacity=1;
		Ite.get('.app-content').getDOMElement().style.opacity=0;
	}

	pub.hide=function(){
		Ite.setCursor('auto');
		Ite.get('.loader-server').getDOMElement().style.opacity=0;
		Ite.get('.loader-server').addClass('hide');
		Ite.get('.app-content').getDOMElement().style.opacity=1;

		Ite.setScrollPosition(0,0);
	}

});