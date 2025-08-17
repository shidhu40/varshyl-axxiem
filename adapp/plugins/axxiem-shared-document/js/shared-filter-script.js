jQuery.noConflict();
jQuery(document).ready( function(){ 
	const queryString = window.location.search;
	// Create a new URLSearchParams object
	const urlParams = new URLSearchParams(queryString);
	// Get a specific query parameter value by name
	const catValue = urlParams.get('cat_id');
	if (typeof catValue !== "undefined") {
		filterDocument();
	}
	jQuery(document).on('search keyup', '#myInputText', function () {
    	// search logic here
    	// this function will be executed on click of X (clear button)
		debounce(filterDocument,2000);
	});
	jQuery(document).on('click','.directory-view', function (event) {
		event.preventDefault();
		var cat_id = jQuery(this).attr('data-id');
		jQuery(".filterByCat").val(cat_id).attr("selected","selected");
		jQuery('.document-search').val('');
		filterDocument();
		/*var data = {
			action: 'axxiem_shared_document_directory_view',
			cat_id:cat_id
		};
		jQuery.ajax({
			type: 'POST',
			url: ajax_var.url,
			data: data,
			beforeSend: function () {
				jQuery('#axxi-loader').removeClass('hidden');
				
				jQuery('.filterByCat').val('');
			},
			success: function(response) {
				jQuery('.documents-list-container').html(response);
			},
			complete: function () { // Set our complete callback, adding the .hidden class and hiding the spinner.
				jQuery('#axxi-loader').addClass('hidden')
			},
		}); */
		return false;
	});
	
	jQuery(document).on('click','.recent_document_view', function (event) {
		//event.preventDefault();
		var post_id = jQuery(this).attr('data-id');
		var data = {
			action: 'axxiem_store_shared_recent_directory_view',
			post_id:post_id
		};
		jQuery.ajax({
			type: 'POST',
			url: ajax_var.url,
			data: data,
			success: function(response) {
			},
			complete: function () { // Set our complete callback, adding the .hidden class and hiding the spinner.
			},
		});
	});
	
	jQuery('.adapp-post-ajax-loader').on('click',function(event){
		event.preventDefault();
		var _this = jQuery(this);
		var paged = jQuery(this).parent().siblings('.post-listing').find('.adapp-post-list:last').attr('data-paged');
		paged = parseInt(paged) + 1;
		var limit = jQuery(this).attr('limit');
		var data = {
			action: 'axxiem_adapp_post_ajax_pagination',
			paged:paged,
			limit:limit,
		};
		var dataCount = {
			action: 'axxiem_adapp_post_ajax_count',
			paged:paged,
			limit:limit,
		};
		jQuery.ajax({
		   type: 'POST',
		   url: ajax_var.url,
		   data: data,
		   success: function(response) {
			   jQuery('.post-listing > ul').append(response);
			   jQuery.ajax({
				   type: 'POST',
				   url: ajax_var.url,
				   data: dataCount,
				   success: function(response) {
						var dataResponse = JSON.parse(response);
					   if (dataResponse.post_count == 0) {
						   jQuery(_this).parent().hide();
					   }
				   }
			   });
		   }
		});
	});
});

let timeout;
var debounce = function(func, delay) {
  clearTimeout(timeout);

  timeout = setTimeout(func, delay);
};

function filterDocument() {
	let keyword = jQuery('.document-search').val();
	let sortBy = jQuery('.sortBy').val();
	let filterByCat = jQuery('.filterByCat').val();
	var data = {
		action: 'axxiem_ajax_shared_content',
		keywords:keyword,
		sortBy:sortBy,
		filterByCat:filterByCat
	};
	jQuery.ajax({
		type: 'POST',
		url: ajax_var.url,
		//async: false,
		data: data,
		beforeSend: function () {
			jQuery('#axxi-loader').removeClass('hidden');
			showLoader();
			jQuery('.documents-lists').html('');
		},
		success: function(response) {
			jQuery('.documents-list-container').html(response);
			jQuery('html, body').animate({
				scrollTop: jQuery('.addapp-dashboard').offset().top,
			 }, 100);
		}
	});
}

//Code Added By SA
 /*document.addEventListener('DOMContentLoaded', function() {
	 var clearableInput = document.querySelector('.clearable');
	 var inputContainer = clearableInput.parentElement;

	 clearableInput.addEventListener('input', function() {
		 if (this.value) {
			 inputContainer.classList.add('has-text');
		 } else {
			 inputContainer.classList.remove('has-text');
		 }
	 });

	 inputContainer.addEventListener('click', function(e) {
		 if (e.target !== clearableInput && e.offsetX > clearableInput.clientWidth - 25) {
			 clearableInput.value = '';
			 inputContainer.classList.remove('has-text');
			 clearableInput.focus();
		 }
	 });
 }); */
function showLoader(){
	var elems = document.querySelector("#axxi-loader");
	if(elems !==null){
		document.querySelector('#axxi-loader').classList.remove('hidden');
		return false;
  	}
}