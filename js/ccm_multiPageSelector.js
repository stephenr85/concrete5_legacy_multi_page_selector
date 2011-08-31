//Requires jQuery.Widget factory and jQuery.ui.sortable


(function(){
	var $ = jQuery,
		undefined;
		
	if(typeof console === "undefined"){
		var console = {log:function(){}};
		console.debug = console.info = console.warn = console.error = console.log = function(){};	
	}
	
	var wrapClass = "ccm-multi-page-selector";
		
	ccm_multiPageSelectorAdd = function(cID, cName, field){
		var $field = $(field || ccmActivePageField),
			fieldName = $(ccmActivePageField).attr("dialog-sender"),
			$mpsel = $field.closest("."+wrapClass);

		$mpsel.ccm_multiPageSelector("addItem", cID, cName, true);
	};
	
	var ccm_multiPageSelector = {
		options:{
			itemList:"ul.items",
			itemTemplate:"li.template",
			itemBreadcrumbUrl:CCM_TOOLS_PATH+"/../packages/multi_page_selector/getbreadcrumb"
		},
		_init:function(options){
			var I = this;
			
			this.template = this.element.find(this.options.itemTemplate).remove();
			this.list = this.element.find(this.options.itemList);
			this.list.sortable({handle:".icon,.name", axis:"y"});
			this.list.disableSelection();
			
			this.itemActions = $.extend({}, this.itemActions);
			
			//Delegate item actions
			this.list.delegate("a", "click", function(evt){
				var $a = $(this);				
				if($a.parent(".actions").length){
					var classes = $a.attr("class").split(/\s+/g);
					for(var c = 0; c < classes.length; c++){
						var key = classes[c],
							$item = $a.closest("li");
						if($.isFunction(I.itemActions[key])){
							var args = [key, $item, $item.prevAll("li").length, I.element],
								result = I.itemActions[key].apply($item.get(0), args);
							
							if(result !== false){
								args.splice(0,0,key+"Item");
								I._trigger.apply(I, args);
							}
						}
					}
				}
				
			});
		},
		addItem:function(cID, cName, isAutoLabel, position){
			
			var I = this,
				$item = this.template.clone(),
				$existing = this.list.find("input[value='"+cID+"']");			
			
			if(!$existing.length){			
				$item.find("input:hidden").val(cID);
				$item.find(".name").html(cName);
				
				if(isAutoLabel){
					I.getItemBreadcrumb(cID, function(breadcrumb){
						$item.find(".name").html(breadcrumb);
					});						
				}
				
			}else{
				$item = $existing.closest("li").remove();
			}
			
			if(position < 1){
				this.list.prepend($item);
			}else if(position == null || position > this.list.children("li").length){
				this.list.append($item);	
			}else{
				this.list.children("li").eq(position).insertAfter($item);
			}
			
			this._trigger('addPage', cID, cName, position);
		},
		removeItem:function(cID){
			var $in = this.list.find("input[value='"+cID+"']"),
				$item = $in.closest("li");
			if($item.length){
				$item.remove();
				this._trigger('removeItem', cID);
			}
		},
		
		itemActions:{
			remove:function(action, $item, position, $wrap){
				$wrap.ccm_multiPageSelector("removeItem", $item.find("input").val());
			}	
		},
		addItemAction:function(key, callback){
			
			var $actions = this.template.find(".act"),
				$action = $actions.children("."+key);
			
			//Add action to template, if it doesn't already exist
			if(!$action.length){
				$action = $("<a class=\""+key+"\" title=\""+(key)+"\">"+key+"</a>");
			}
			
			this.itemActions[key] = callback;
			
		},
		getItemBreadcrumb:function(cID, callback){		
			var req = $.get(this.options.itemBreadcrumbUrl, {cID:cID}, callback);
			return req;
		}
	};
	//Create the widget
	$.widget("ccm.ccm_multiPageSelector", ccm_multiPageSelector);
	

})();