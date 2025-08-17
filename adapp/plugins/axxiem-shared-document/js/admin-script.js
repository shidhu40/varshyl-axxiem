jQuery( function($){
	// on upload button click
	$( 'body' ).on( 'click', '.rudr-upload', function( event ){
		event.preventDefault(); // prevent default link click and page refresh
		
		const button = $(this)
		const imageId = button.next().next().val();
		
		const customUploader = wp.media({
			title: 'Insert image', // modal window title
			library : {
				// uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
				type : 'image'
			},
			button: {
				text: 'Use this image' // button label text
			},
			multiple: false
		}).on( 'select', function() { // it also has "open" and "close" events
			const attachment = customUploader.state().get( 'selection' ).first().toJSON();
			button.removeClass( 'button' ).html( '<img src="' + attachment.url + '">'); // add image instead of "Upload Image"
			button.next().show(); // show "Remove image" link
			button.next().next().val( attachment.id ); // Populate the hidden field with image ID
		})
		
		// already selected images
		customUploader.on( 'open', function() {

			if( imageId ) {
			  const selection = customUploader.state().get( 'selection' )
			  attachment = wp.media.attachment( imageId );
			  attachment.fetch();
			  selection.add( attachment ? [attachment] : [] );
			}
			
		})

		customUploader.open()
	
	});
	// on remove button click
	$( 'body' ).on( 'click', '.rudr-remove', function( event ){
		event.preventDefault();
		const button = $(this);
		button.next().val( '' ); // emptying the hidden field
		button.hide().prev().addClass( 'button' ).html( 'Upload image' ); // replace the image with text
	});
	
	
	var mediaUploader;
	jQuery('#upload-document').click(function(e) {
    e.preventDefault();

    // Create the media frame.
      var file_frame = wp.media.frames.file_frame = wp.media({
		title: 'Select or upload image',
		button: {
            text: 'Select'
		},
         multiple: false  // Set to true to allow multiple files to be selected
      });

		file_frame.on('select', function () {
			 // We set multiple to false so only get one image from the uploader
	 
			 var attachment = file_frame.state().get('selection').first().toJSON();
	 
			jQuery('#showLink').html(attachment.url);
			jQuery('#document_link').val(attachment.url);
			jQuery('#fileError').text("");
		  });
 
      // Finally, open the modal
      file_frame.open();
	});
	jQuery("#removeDoc").on('click',function(){
		jQuery(this).parent().parent().remove();
		jQuery("#uploadDoc").show();
		jQuery('#document_link').val('');
	});	
});
