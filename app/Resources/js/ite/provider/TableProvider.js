function TableProvider(contener,table,url){
	"use strict";
	var prv={};
	var pub=this;
	prv.maxPage=0;

	pub.init=function(){
		prv.bind();
	};

	pub.execute=function(args,callback){
		Ite.api().execute(contener,url,args,function(data){
			prv.maxPage=data.maxPage;
			var collection=table.getCollection();
			prv.refreshPaginate(data.page,data.maxPage);
			collection.setMetaData('page',data.page);
			callback.call(null,data.records);
		});
	};

	prv.bind=function(){
		var ignore=false;


		Ite.try(function () {
			table.get('tfoot form [role="paginate-prev"]').addEventClick(function(){
				var collection=table.getCollection();
				var currentPage=collection.getMetaData('page');
				if(currentPage>1){
					collection.setMetaData('page',currentPage-1);
					collection.refresh();

				}
			});

		}).catch(ObjectNotFoundException, function (e) {
			//ignore
		})

		Ite.try(function () {
			table.get('tfoot form [role="paginate-next"]').addEventClick(function(){
				var collection=table.getCollection();
				var currentPage=collection.getMetaData('page');
				if(currentPage<prv.maxPage){
					collection.setMetaData('page',currentPage+1);
					collection.refresh();

				}
			});

		}).catch(ObjectNotFoundException, function (e) {
			//ignore
		})

		Ite.try(function(){
			table.get('tfoot form select').addEventChange(function(){
				var collection=table.getCollection();
				collection.setMetaData('page',this.getValue());
				collection.refresh();
			});

		}).catch(ObjectNotFoundException, function(e){
			//ignore
		});


	};

	prv.refreshPaginate=function(page,maxPage){
		var field=null;
		var ignore=false;
		Ite.try(function(){
			field=table.get('tfoot form select');
		}).catch(ObjectNotFoundException, function(e){
			ignore=true;
		});

		if(ignore){
			return;
		}
		
		var collection=field.getCollection();
		collection.clear();
		var records=[];
		for(var i=1; i<=maxPage; i++){
			records.push({id:i,name:i});
		}

		collection.pushAll(records);

		field.setValue(page,true);		

	};

	pub.init.call();

};