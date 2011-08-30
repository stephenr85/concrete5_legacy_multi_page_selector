

$(function(){

	var $label = $("input[name='label']"),
		$existingLabels = $("select[name='existingLabels']").hide();
	
	if($existingLabels.children("option").length){
		
		var labels = [];
		$existingLabels.find("option").each(function(){
			var $opt = $(this);
			if($.trim($opt.val()).length){
				labels.push($(this).text());	
			}			
		});
		
		$label.autocomplete({
			source: labels,
			appendTo:$label.parent()
		});
		
		$existingLabels.change(function(evt){

			var $opt = $existingLabels.find("option:selected");
			if($.trim($opt.val()).length){
				$label.val($opt.text());
			}else{
				$label.val("");	
			}
		});
	
	}else{
		$existingLabels.remove();	
	}

});