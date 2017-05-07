Ite.registerModule('dialogBox',function(){
	"use strict";
	var prv={};
	var pub=this;

	pub.createConfirm=function(message,callback){
		var dialogBox=pub.create();
		dialogBox.setTitle('Komunikat');
		dialogBox.setMessage(message);
		dialogBox.setButtonSubmit('Tak',function(){
			callback.call(this,true);
			this.close();
		});

		dialogBox.addButton('Nie',function(){
			callback.call(this,false);
			this.close();
		});

		return dialogBox;
	};


	pub.create=function(){
		return new IteDialogBox();
	};


});

function IteDialogBox(){
	"use strict";

	var pub=this;
	var prv={};
	prv.message;
	prv.buttons=new IteArray();
	prv.closeButton=true;
	prv.eventsRendered=new IteArray();
	prv.eventsClose=new IteArray();
	prv.title='';
	prv.template;
	prv.submit;
	prv.keyBind=null;

	pub.setMessage=function(message){
		prv.message=message;
	};

	pub.setTitle= function (title) {
		prv.title=title;
	};

	pub.addButton=function(label,callback){
		prv.buttons.push([label,callback]);
	};

	pub.setButtonSubmit=function(label,callback) {
		prv.submit=[label,callback];
	};

	pub.setCloseButton=function(flag){
		pub.closeButton=flag;
	};

	pub.close=function(){
		prv.element.remove();
	};

	pub.getElement=function(){
		return prv.element;
	};

	pub.addEventRendered=function(callback){
		prv.eventsRendered.push(callback);
	};

	pub.addEventClose=function(callback){
		prv.eventsClose.push(callback);
	};

	pub.resize=function () {
        prv.resizeListener();
    };

	pub.show=function(){
		var template=[];

		template.push('    <div class="dialog-box">');
        template.push('   <div class="dialog">');
        template.push('    <div class="dialog-header">');
        template.push('   <div class="dialog-title">');
        template.push(prv.title);
        template.push('    </div>');
        template.push('    <a href="#" class="dialog-close"><span class="close"></span></a>');
        template.push('    </div>');
        template.push('    <div class="dialog-body">');
        template.push('    <div class="loading-mask"></div>');
        template.push('    </div>');
        template.push('    <div class="dialog-footer">');
        template.push('    </div>');
        template.push('    </div>');
        template.push('    </div>');

        prv.element=Ite.createObject(template.join(''));

        if(!prv.closeButton){
            prv.element.get('.dialog-header .dialog-close').setDisplay('none');
        }

        if(prv.message){
            prv.element.get('.dialog-body').setHtml(prv.message);

        }

        prv.element.get('.dialog-header .dialog-close').addEventClick(prv.closeButtonListener());
		Ite.get('body').append(prv.element);
		Ite.get('body').getDOMElement().style['overflow']='hidden';

		prv.keyBind=Ite.addEventKeyUp(function (e) {
			var e=e.getOrigin();
			var key=e.keyCode;
			if(key===27){
				pub.close();
			}
        });

        prv.resizeListener();
		prv.element.addEventDOMModified(prv.resizeListener);

		prv.eventsRendered.each(function(item){
			item.call(pub);
		});


	};

	prv.resizeListener=function () {
			var dialog=prv.element.get('.dialog');
            dialog.get('.dialog-body').setHeight(null);

			var height=dialog.getHeight();
            var dialogStyle = window.getComputedStyle(dialog.getDOMElement());
			var maxHeight = dialogStyle.getPropertyValue('max-height');
            var windowHeight=Ite.getHeight();
			if(maxHeight){
				if(maxHeight.indexOf('%')!==-1){
					var percent=maxHeight.substring(0,maxHeight.length-1);
					maxHeight=windowHeight*(percent/100);
				}
				else{
					maxHeight=maxHeight.substring(0,maxHeight.length-2);
				}
			}
			else{
                maxHeight=400;
			}
			if(height>=maxHeight){
                dialog.setHeight(maxHeight);
                height=maxHeight;
			}

			var posY=(windowHeight-height)/2;
			if(posY<0){
				posY=0;
			}

            dialog.get('.dialog-body').setHeight(height-40);

			dialog.getDOMElement().style.marginTop=posY+'px';
    };

	prv.closeButtonListener=function () {
		return function () {
			pub.close();
        }
    };

	pub.close=function () {
        prv.eventsClose.each(function(item){
            item.call(pub);
        });

        if(prv.keyBind){
        	Ite.removeEvent(prv.keyBind);
        	prv.keyBind=null;
		}

        var whichTransitionEvent=function(){
            var t;
            var el = document.createElement('fakeelement');
            var transitions = {
                'transition':'transitionend',
                'OTransition':'oTransitionEnd',
                'MozTransition':'transitionend',
                'WebkitTransition':'webkitTransitionEnd'
            }

            for(t in transitions){
                if( el.style[t] !== undefined ){
                    return transitions[t];
                }
            }
        };

		/* Listen for a transition! */
        var transitionEvent = whichTransitionEvent();

        prv.element.getDOMElement().addEventListener(transitionEvent,function () {
            prv.element.remove();
        });

        prv.element.addClass('dialog-box-close');
        Ite.get('body').getDOMElement().style['overflow']='auto';

        // setTimeout(function () {
            if(prv.element){
                prv.element.remove();
            }
        // },2000); //bugfixed

    };
}