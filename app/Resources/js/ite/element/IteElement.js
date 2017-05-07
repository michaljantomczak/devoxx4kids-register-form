Ite.registerElement('IteElement','*',function(helper,htmlElement,parent){
	"use strict";

	var prv={};
	var pub=this;

	prv.helper=helper;
	prv.element=htmlElement;
	prv.objectId=prv.helper.createObjectId();

	prv.scope=(parent!=undefined?parent:pub);

	pub.getObjectId=function(){
		return prv.objectId;
	}

	pub.getDOMElement=function(){
		return prv.element;
	}

	pub.getHtml=function(){
		return pub.getDOMElement().innerHTML;
	}

	pub.toHtml=function(){
		return pub.getDOMElement().outerHTML;
	}

    pub.setHtml=function(html){
        pub.getDOMElement().innerHTML=html;
        pub.getAll('script').each(function (item) {
            var type=null;
            Ite.try(function () {
                type=item.getAttribute('type');

            }).catch(AttributeNotFoundException,function (e) {
                //ignore
            });
            if(type!=null && type!='text/javascript'){
                return null;
            }
            var code=item.getHtml();
            eval(code);
        });
    };

	pub.setText=function(text){
		pub.getDOMElement().innerText=text;
		pub.getDOMElement().textContent=text;
	}

	pub.getText=function(){
		return pub.getDOMElement().innerText;
	}

	pub.getPositionX=function(){
		return pub.getDOMElement().style.left||0;
	}

	pub.getPositionY=function(){
		return pub.getDOMElement().style.top||0;
	}

	pub.setPositionX=function(x){
		pub.getDOMElement().style.left=x+'px';
	}

	pub.setPositionY=function(y){
		pub.getDOMElement().style.top=y+'px';
	}

	pub.setRotateX=function(x){
		pub.getDOMElement().style.transform='rotateX('+x+'deg)';
		pub.getDOMElement().style.webkitTransform='rotateX('+x+'deg)';
	}

	pub.setRotateY=function(y){
		pub.getDOMElement().style.transform='rotateY('+y+'deg)';
		pub.getDOMElement().style.webkitTransform='rotateY('+y+'deg)';
	}

	pub.setRotate=function(deg){
		pub.getDOMElement().style.transform='rotate('+deg+'deg)';
		pub.getDOMElement().style.webkitTransform='rotate('+deg+'deg)';
	}

	pub.setBackgroundSize=function(x,y){
		pub.getDOMElement().style.backgroundSize=x+'px '+y+'px';
	}

	pub.setBackgroundColor=function(r,g,b,a){
		pub.getDOMElement().style.background='rgba('+r+','+g+','+b+','+a+')';
	}

	pub.getWidth=function(){
		return pub.getDOMElement().offsetWidth;
	}

	pub.setWidth=function(width){
		pub.getDOMElement().style.width=width+'px';
	}

	pub.getHeight=function(){
		return pub.getDOMElement().offsetHeight;
	}

	pub.setHeight=function(height){
		if(height!==null){
			height+='px';
		}
		pub.getDOMElement().style.height=height;
	}

	pub.setMargin=function(top,right,bottom,left){
		pub.getDOMElement().style.margin=top+' '+right+' '+bottom+' '+left;
	}

	pub.setZIndex=function(index){
		pub.getDOMElement().style.zIndex=index;
	}

	pub.setOverflow=function(type){
		pub.getDOMElement().style.overflow=type;
	}

	pub.getDisplay=function(){
		return pub.getDOMElement().style.display;
	}

	pub.getChildPosition=function(){
		var parent=pub.getParent();
		var position=0;
		return parent.getChildren().each(function(item){
			if(item==pub){
				return position;
			}
			position++;
		});
	};

	pub.select=function(){
        if (document.selection) {
            var range = document.body.createTextRange();
            range.moveToElementText(pub.getDOMElement());
            range.select();
        } else if (window.getSelection) {
            var range = document.createRange();
            range.selectNode(pub.getDOMElement());
            window.getSelection().addRange(range);
        }

	};

	pub.getOffsetX=function(){
		var curleft = 0;
		var curtop = 0;
		var obj=pub.getDOMElement();
		if (obj.offsetParent) {
			do {
				curleft += obj.offsetLeft;
				curtop += obj.offsetTop;
			} while (obj = obj.offsetParent);
		}

		return curleft;
	}

	pub.getOffsetY=function(){
		var curleft = 0;
		var curtop = 0;
		var obj=pub.getDOMElement();
		if (obj.offsetParent) {
			do {
				curleft += obj.offsetLeft;
				curtop += obj.offsetTop;
			} while (obj = obj.offsetParent);
		}

		return curtop;
	}

	pub.remove=function(){
		pub.getDOMElement().parentNode.removeChild(pub.getDOMElement());
	}

	pub.getChildren=function(){
		var array=new IteArray();
		var children=pub.getDOMElement().childNodes;
		for(var i=0; i < children.length; i++){
			if(children[i].tagName!=undefined)
				array.push(prv.helper.getObject(children[i]));
		}

		return array;
	}

	pub.getParent=function(pattern){

		if(pattern){
			var all=document.querySelectorAll(pattern);
			var cur=pub.getDOMElement();
			while(cur!=null && !prv.collectionHas(all,cur)){

				cur=cur.parentNode;
			}
			if(cur)
				return prv.helper.getObject(cur);
			else
				throw new ObjectNotFoundException(pattern);
		}
		else{
			var parentNode=pub.getDOMElement().parentNode;
			if(parentNode==document){
				throw new ObjectNotFoundException('');
			}
			return prv.helper.getObject(parentNode);
		}
	}

	pub.replace=function(object){
		pub.getParent().getDOMElement().replaceChild(object.getDOMElement(),pub.getDOMElement());
		prv.element=object.getDOMElement();
		pub=object;
	}

	pub.appendAfter=function(object){
		var node=null;
		Ite.try(function () {
            node=pub.next();
            pub.getParent().getDOMElement().insertBefore(object.getDOMElement(),node.getDOMElement());
        }).catch(ObjectNotFoundException,function () {
            pub.getParent().getDOMElement().appendChild(object.getDOMElement());
        });
	}

    pub.appendBefore=function(object){
        pub.getParent().getDOMElement().insertBefore(object.getDOMElement(),pub.getDOMElement());

    }

    pub.next=function(){
		var nextSibling = pub.getDOMElement().nextSibling;
		while(nextSibling && nextSibling.nodeType != 1) {
			nextSibling = nextSibling.nextSibling;
		}

		if(!nextSibling){
			throw new ObjectNotFoundException();
		}

		return prv.helper.getObject(nextSibling);
	}

	pub.prev=function(index){
    	index=(index===undefined?1:index);

    	var node=pub;
    	for(var i=0; i<index; i++){
    		node=prv.prev(node);
		}

        return node;
	};

    prv.prev=function (node) {
        var previousSibling = node.getDOMElement().previousSibling;
        while(previousSibling && previousSibling.nodeType != 1) {
            previousSibling = previousSibling.previousSibling;
        }

        if(previousSibling===null){
            throw new ObjectNotFoundException();
        }

        return prv.helper.getObject(previousSibling);
    };

    pub.append=function(object,position){
        position=(position==undefined?-1:position);
        var beforeChild=undefined;
        var children=pub.getDOMElement().childNodes;
        var childrenLength=children.length;
        if(position<0){
            childrenLength+=position;
        }

        if(position+1<childrenLength)
            beforeChild=children[position];

        if(typeof object=='object'){
            if(beforeChild)
                pub.getDOMElement().insertBefore(object.getDOMElement(),beforeChild);
            else
                pub.getDOMElement().appendChild(object.getDOMElement());
        }
        else{
            var contener=document.createElement('div');
            contener.innerHTML=object;
            var nodes=contener.childNodes;
            for(var i=0; i < nodes.length; i++){
                if(beforeChild) {
                    pub.getDOMElement().insertBefore(nodes[i], beforeChild);
                }
                else{
                    pub.getDOMElement().appendChild(nodes[i]);
				}
            }

        }

        object.getAll('script').each(function (item) {
            var type=null;
            Ite.try(function () {
                type=item.getAttribute('type');

            }).catch(AttributeNotFoundException,function (e) {
                //ignore
            });
            if(type!=null && type!='text/javascript'){
                return null;
            }
            var code=item.getHtml();
            eval(code);
        });

    };

	pub.getTag=function(){
		return pub.getDOMElement().tagName;
	}

	pub.clearClass=function(){
		pub.getDOMElement().className='';
	}

	pub.addClass=function(className){
		if(pub.getDOMElement().classList)
			pub.getDOMElement().classList.add(className);
		else{
			var currentClassName=pub.getDOMElement().className;
			pub.getDOMElement().className+=(currentClassName!=''?' ':'')+className;			
		}
	}

	pub.removeClass=function(className){
		if(pub.getDOMElement().classList)
			pub.getDOMElement().classList.remove(className);
		else{
			var classString='';
			new IteArray(pub.getDOMElement().className.split(' ')).each(function(value,key){
				if(className!=value){
					if(key!=0)
						classString+=' ';
					classString+=value;

				}
			});

			pub.getDOMElement().className=classString;
		}
	}

	pub.getClass=function(){
		return new IteArray(pub.getDOMElement().className.split(' '));
	}

	pub.isClass=function(className){
		var finded=0;
		var classes=pub.getClass().each(function(item){
			if(className==item)
				return true;
		});

		return classes||false;
	}

	pub.getId=function(){
		return pub.getDOMElement().id;
	}

	pub.setId=function(id){
		pub.getDOMElement().id=id;
	}

	pub.getAttribute=function(name){
		var attribute=pub.getDOMElement().getAttribute(name);
		if(attribute==null){
			throw new AttributeNotFoundException(name);
		}

		return attribute;
	}

	pub.setAttribute=function(name,value){
		return pub.getDOMElement().setAttribute(name,value);
	}

	pub.removeAttribute=function(name){
		return pub.getDOMElement().removeAttribute(name);
	}

	pub.focus=function(){
		pub.getDOMElement().focus();
	}

	pub.isFocus=function(){
		return document.activeElement==pub.getDOMElement();
	}

	pub.blur=function(){
		pub.getDOMElement().blur();
	}

	pub.click=function(){
		pub.getDOMElement().click();
	}

	pub.getAll=function(pattern){
		var result=new IteArray();
		for(var i=0; i <arguments.length; i++){
			var objects=pub.getDOMElement().querySelectorAll(arguments[i]);
			for(var j=0; j <objects.length; j++){

				result.push(prv.helper.getObject(objects[j]));
			}
		}

		return result
	}

	pub.get=function(pattern){
		var object=pub.getDOMElement().querySelector(pattern);

		if(object==null){
			throw new ObjectNotFoundException(pattern);
		}

		return prv.helper.getObject(object);
	}

	pub.fireEvent=function(eventName){
		if(!pub.isDisabled || !pub.isDisabled()){
			if (document.createEvent) {
			    var evt = document.createEvent("HTMLEvents");
				evt.initEvent(eventName, false, true);
				pub.getDOMElement().dispatchEvent(evt);
			}
			else
	    		element.fireEvent('on'+eventName);

		}
	}

	pub.getObjectName=function(){
		var funcNameRegex = /function (.{1,})\(/;
		var results = (funcNameRegex).exec((pub).constructor.toString());
		return (results && results.length > 1) ? results[1] : "";		
	}

	//css
	pub.setDisplay=function(value){
		pub.getDOMElement().style.display=value;
	}
	
	//events
	pub.addEventClick=prv.helper.addEvent(prv.scope,'click');

	pub.addEventChange=prv.helper.addEvent(prv.scope,'change');

	pub.addEventKeyPress=prv.helper.addEvent(prv.scope,'keypress',true);

	pub.addEventKeyDown=prv.helper.addEvent(prv.scope,'keydown',true);

	pub.addEventKeyUp=prv.helper.addEvent(prv.scope,'keyup',true);

	pub.addKeyUp=prv.helper.addEvent(prv.scope,'keyup',true);

	pub.addEventMouseDown=prv.helper.addEvent(prv.scope,'mousedown',true);

	pub.addEventMouseUp=prv.helper.addEvent(prv.scope,'mouseup',true);

	pub.addEventDOMModified=prv.helper.addEvent(prv.scope,'DOMSubtreeModified');

	pub.addEventRightClick=prv.helper.addEvent(prv.scope,'contextmenu');

	pub.toString=function(){
		return 'IteObject';//FIXME tutaj muszą być odczytywane odpowiednie wartości z klas potomnych
	}

	pub.getSelector=function(){
		return '*';
	}

	prv.collectionHas=function(a,b){
	    for(var i = 0, len = a.length; i < len; i ++) {
	        if(a[i] == b) return true;
	    }
	    return false;
	}

});
