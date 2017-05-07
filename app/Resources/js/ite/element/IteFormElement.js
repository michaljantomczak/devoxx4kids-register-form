Ite.registerElement('IteFormElement','form',function(helper,htmlElement,parent){
	"use strict";
	var prv={};
	var pub=this;

	prv.helper=helper;
	prv.isDirty=false;
	prv.scope=(parent!=undefined?parent:pub);

	prv.eventBeforUpdateData = new IteArray();

	//extend
	Ite.extend(pub,'IteElement',htmlElement,prv.scope);

    pub.construct=function () {
        pub.addEventSubmit(function(e){
        	e.setSystemHandle(true);
            pub.setDirty(true)
        });
    };

    /**
	 * @depreceted
	 */
	pub.setValues=function(values){
		for(var key in values){
			pub.get("[name='"+key+"']").setValue(values[key]);
		}		
	};

	pub.setData=function(values){
		prv.eventBeforUpdateData.each(function (callback) {
			callback.call(pub,values);
		});
		for(var key in values){
			pub.getField(key).setData(values[key]);
		}
	};

	pub.clearData=function(){
		prv.eventBeforUpdateData.each(function (callback) {
			callback.call(pub,{});
		});

		pub.getAll('input','select','textarea').each(function(item){
			item.setData('');
		});
	};

	pub.setDisabled=function (flag) {
		pub.getAll('input[type="submit"]').each(function (item) {
			item.setDisabled(flag);
		});

		pub.getAll('button').each(function (item) {
			var type='submit';
			Ite.try(function () {
				type=item.getAttribute('type');
			}).catch(AttributeNotFoundException,function (e) {
				//ignore
			});

			if(type=='submit'){
				item.setDisabled(flag);
			}
		});

	};

	pub.getValues=function(){
		var values={};
		var contenerBranch={};
		var match;
		pub.getAll('input','select','textarea').each(function(field){
			var fieldName=field.getName();
			if(fieldName!='' && !field.isDisabled() && field.getValue()!=null){
				contenerBranch=values;
				var nameParts=new RegExp('^([^\\[]+)(\\[(.*)\\]){0,1}$','g').exec(fieldName);
				var deep=[nameParts[1]];
				if(nameParts[2]!=undefined){
					var reArray=new RegExp('\\[([^\\[]*)\\]','g');
					while((match=reArray.exec(nameParts[2])) && match[1]!=''){
						deep.push(match[1]);
					}

				}

				for(var i=0; i <deep.length; i++){
					if(contenerBranch[deep[i]]==undefined){

						if(deep[i+1]=='')
							contenerBranch[deep[i]]=[];						
						else
							contenerBranch[deep[i]]={};
					}
					if(deep.length-1==i){

						if(deep[i]=='')//obsługa name[] - bez numerowania
							contenerBranch.push(field.getValue());
						else
							contenerBranch[deep[i]]=field.getValue();

					}
					else{
						contenerBranch=contenerBranch[deep[i]]; //FIXME nie wiem czy tutaj też nie powinno odbywać się przez push gdy deep jest pusty
					}
				}					

			}

		});

		return values;
	}

	//validator
	pub.isDirty=function(){
		return prv.isDirty;
	}

	pub.setDirty=function(flag){
		prv.isDirty=flag;
	}

	/**
	 * Get form field
	 * @param name - field name
	 * @return IteFieldElement
	 */
	pub.getField=function(name){
		return pub.get('[name="'+name+'"]')
	}

	pub.getValidator=function(){
		if(!prv.validator){
			prv.validator=new IteValidator(pub);
			var validatorHelper=prv.helper.validator;
			var defaultRules=validatorHelper.defaultRules;
			for(var kRule in defaultRules){
				try{
					prv.validator.addRule(kRule,defaultRules[kRule]);
				}
				catch(e){
					//ignore
				}
			}

			prv.validator.setFieldListener(validatorHelper.fieldListener);
			prv.validator.setFormListener(validatorHelper.formListener);
		}


		return prv.validator;

	}

	pub.isValid=function(){
		if(prv.validator==undefined){
			throw new ValidatorNotFoundException();
		}

		return prv.validator.isValid();
	}

	pub.checkFieldsValid=function(){
		prv.validator.checkFieldsValid();
	}

	pub.setAction=function(action){
		pub.getDOMElement().action=action;
	}

	pub.setMethod=function(method){
		pub.getDOMElement().method=method;
	}

	pub.setTarget=function(target){
		pub.getDOMElement().target=target;
	}

	pub.submit=function(){
		pub.getDOMElement().submit();
	}
	//events
	pub.addEventSubmit=prv.helper.addEvent(prv.scope,'submit');

	pub.addEventBeforeUpdateData=function (callback) {
		prv.eventBeforUpdateData.push(callback);
	};


});
