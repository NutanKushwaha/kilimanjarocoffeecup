var myCodeMirror;

$(document).ready(function(){
	var template_editor = $(TEMPLATE)[0];
	myCodeMirror = CodeMirror.fromTextArea(template_editor, {
		lineNumbers: true,
		lineWrapping: true,
		smartIndent: true,
        mode: "htmlmixed"
	});

	//var tabber1 = new Yetii({id: 'tabs', persist: true});
    $( "#tabs" ).tabs({
		show: function(event, ui) {
			myCodeMirror.refresh();
		}
	});
});
