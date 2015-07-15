	var $j = jQuery;
	jQuery('#ContactoRedirect').val(location.href);
		var $form = jQuery("form[id^='ubicual-form']");
		var postUrl = $form.attr("action");
		var postData = getFormData($form);//jQuery.parseJSON($form.serializeArray());
		$form.submit(function(){
			var postData = getFormData($form);
			jQuery.ajax({
			    type: 'POST',
			    url: postUrl,
			    crossDomain: true,
			    data: postData,
			    success: function(responseData, textStatus, jqXHR) {
			    	var json = jQuery.parseJSON(responseData);
			    	jQuery('#ubmsg').html('<p>' + json.message + '</p>');
			    },
			    error: function (responseData, textStatus, errorThrown) {
			        jQuery('#ubmsg').html('<p>Error al enviar los datos.</p>');
			    }
			});
			return false;
		})
	
	function getFormData($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    jQuery.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}