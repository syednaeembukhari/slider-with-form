function swf_form_submit(formid,lang){
	//console.log('lang',lang);
	var formData = jQuery('#swf_form_'+formid+lang).serialize();
	jQuery('#swf_form_'+formid+lang+ ' .msg' ).html('');
	jQuery('#submit_btn_'+formid+'_'+lang).hide();
	jQuery('#submiting_btn_'+formid+'_'+lang).show();
	jQuery.ajax({
	    type: "post",
	    dataType: "html",
	    url: my_ajax_object.ajax_url,
	    data: formData,
	    success: function(msg){
	    	//console.log('msg',msg);
	    	//console.log('msg',jQuery('#swf_form_' + formid+lang + '' ));
	    	
	    	if(msg=='0'){
	    		jQuery('#submit_btn_'+formid+'_'+lang).show();
	       		jQuery('#swf_form_' + formid+lang + ' .msg' ).html('Error Occured Please try again');
	    	}else if(msg=='1'){
	    		if(lang=='arb')
					jQuery('#swf_form_' + formid+lang + ' .msg' ).html('شكرا لك').addClass('green');
	    		else
	    			jQuery('#swf_form_' + formid+lang + ' .msg' ).html('Thankyou').addClass('green');
	        	setTimeout(function() { 
			       
	        		jQuery('#swf_slider-' + formid+lang).parents().find('.forms_'+formid+lang).hide();
	        		jQuery('#swf_slider-' + formid+lang+ ' .fotorama').removeClass('blur');
	        	
			    }, 3000);
	        	
	        }else
	        {
	        	jQuery('#swf_form_' + formid+lang + ' .msg' ).html(msg);
	        	jQuery('#submit_btn_'+formid+'_'+lang).show();
	        }

			jQuery('#submiting_btn_'+formid+'_'+lang).hide();
	    }
	});
}

function swf_loadSliderAjax(sliderid,lang){
	//console.log('lang',lang);
	jQuery.ajax({
	    type: "post",
	    dataType: "html",
	    url: my_ajax_object.ajax_url,
	    data: {action: 'swfLoadSliderInAjax','sliderid':sliderid,lang:lang},
	    success: function(msg){
			jQuery('#sliderajax_'+sliderid+lang).html(msg);
	    }
	});
}

