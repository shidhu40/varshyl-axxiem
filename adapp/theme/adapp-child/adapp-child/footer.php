<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Sydney
 */
?>
			</div>
		</div>
	</div><!-- #content -->

	<?php do_action('sydney_before_footer'); ?>

	<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
		<?php get_sidebar('footer'); ?>
	<?php endif; ?>

    <a class="go-top"><i class="fa fa-angle-up"></i></a>
		
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info container">
			Copyright &copy <script type="text/javascript">
  document.write(new Date().getFullYear());
</script> All Rights Reserved. 
			<div class="powered-by">
			<a href="https://axxiem.com/" rel="designer" target="_blank">Website by Axxiem</a> </div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->

	<?php do_action('sydney_after_footer'); ?>

</div><!-- #page -->

<?php wp_footer(); ?>
<script>
jQuery(document).ready(function ($) {
if(window.matchMedia("(max-width: 767px)").matches){
        // The viewport is less than 768 pixels wide
        jQuery('.toggle_container').hide();
		jQuery('.toggle-btn').addClass('open');
    }
jQuery('.tribe_events_cat-members-only').parents('.axxi_customer').hide();
jQuery('.tribe_events_cat-members-only').parents('.axxi_no_login').hide();
jQuery(document).on('click','.popClick',function(e){
	e.preventDefault();
	var a = document.createElement('a');
   	a.target = '_blank';
    a.href = jQuery(this).attr('href');
	jQuery('.wmpci-popup-close')[0].click();
	 a.click();
	return false;
});
$('body').bind('DOMSubtreeModified', function(){
    jQuery('.tribe_events_cat-members-only').parents('.axxi_customer').hide();
	jQuery('.tribe_events_cat-members-only').parents('.axxi_no_login').hide();
		
		jQuery('.tribe-events-calendar-list__month-separator').each(function(){
		if(jQuery(this).next(".tribe-common-g-row:visible").length == 0){
			jQuery(this).hide();
		}
	});
});	
	jQuery('.tribe-events-calendar-list__month-separator').each(function(){
		if(jQuery(this).next(".tribe-common-g-row:visible").length == 0){
			jQuery(this).hide();
		}
	});	
$('.axxi_customer').each(function () {
            if ($(this).children('.cat_members-only').length == 2){ 
                $(this).hide(); }
   });	
	
var headers = $('#accordion .lsow-active');
var contentAreas = $('#accordion .sow-accordion-panel-content').hide().first().show().end();
var expandLink = $('.accordion-expand-all');
$('.sow-accordion-panel').eq(0).addClass('sow-accordion-panel-open');
// add the accordion functionality
headers.click(function() {
    // close all panels
    contentAreas.slideUp();
    // open the appropriate panel
    $(this).next().slideDown();
    // reset Expand all button
    expandLink.text('+ Expand all')
        .data('isAllOpen', false);
    // stop page scroll
	
    return false;
});

// hook up the expand/collapse all
expandLink.click(function(){
    var isAllOpen = !$(this).data('isAllOpen');
    console.log({isAllOpen: isAllOpen, contentAreas: contentAreas})
    contentAreas[isAllOpen? 'slideDown': 'slideUp']();
    
    expandLink.text(isAllOpen? '- Collapse All': '+ Expand All')
                .data('isAllOpen', isAllOpen); 
	if(isAllOpen){
		$('.sow-accordion-panel').addClass('sow-accordion-panel-open');
	}else{
		$('.sow-accordion-panel').removeClass('sow-accordion-panel-open');
	}
	
});
	$(".sow-accordion").each(function(){
		$(this).find(".sow-accordion-panel-content").eq(0).css("display","block");
	});
	window.addEventListener('hashchange', function() { 
		if (window.location.href.indexOf("#") > -1) {
			jQuery('.sow-accordion-panel').removeClass('sow-accordion-panel-open');
			jQuery('.sow-accordion-panel-content').hide();
			var accordionUrl = window.location.href;
			var res = accordionUrl.split("#");
			jQuery("#"+ res[1]).trigger('click');
		}
	});
});		
</script>
<!-- Google Analytics - OLD tag -->
<script language="JavaScript">

(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){

(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),

m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)

})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-42359423-1', 'demo11.axxiem.com');

ga('send', 'pageview');

</script>
<!-- Global site tag (gtag.js) - Google Analytics - added by google@axxie.com-->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-ZZ25XFSMSM"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-ZZ25XFSMSM');
</script>
<script>
function openNav() {
  document.getElementById("myNav").style.width = "100%";
}

function closeNav() {
  document.getElementById("myNav").style.width = "0%";
}
</script>
<script>
	
jQuery(window).on("load", function () {
	if (jQuery.cookie('the_cookie')) {
		var toggleCookieValue = jQuery.cookie('the_cookie');
		if(toggleCookieValue== 'plus'){
			jQuery('.toggle_container').show();
			jQuery('.toggle-btn').removeClass('open');
		}else{
			jQuery('.toggle_container').hide();
			jQuery('.toggle-btn').addClass('open');
		}
	}
});
jQuery(document).ready(function ($) {
var buttons = $('.next-prev');
/*
if (!$('body').hasClass("page-id-15")) {
	setTimeout(function(){
		 $("html, body").animate({ 
		  scrollTop: $('.bread-crumb').first().offset().top
	  }, 1000);
	}, 500);
} */
$('#scroll-div').prepend($('#scroll-div').find('li:last-child')); // prepend last element
$('#scroll-div').scrollTop(30); // scroll div to position 40 so first (div 10) not visible

$(buttons).each(function(){
  $(this).click(function(){
    var id = $(this).attr('id');
    if(id=="next"){
      $('#scroll-div').append($('#scroll-div').find('li:first-child'));   //do modification first     
    } else {
      $('#scroll-div').prepend($('#scroll-div').find('li:last-child')); 
    }
    $('#scroll-div').stop().animate({scrollTop:30},400,'swing'); // then scroll
  })
})
})
</script>
<script>
jQuery(document).ready(function ($) {
$(document).ready(function(){
    // Display alert message after toggling paragraphs
    $(".toggle-btn").click(function(){
        $(".toggle_container").toggle(150, function(){
            // Code to be executed
           // alert("The toggle effect is completed.");
        });
    });
});
$(document).ready(function () {
	$('.toggle-btn').on('click', function () {
		$(this).toggleClass('open');
		var act = $('.toggle-btn').hasClass("open");
		if(act){
		    $.cookie('the_cookie', 'minus', { expires: 1, path: '/', domain: 'demo11.axxiem.com', secure: true});			
		}else{
		  	 $.cookie('the_cookie', 'plus', { expires: 1,path: '/', domain: 'demo11.axxiem.com', secure: true });	
		}
		var obj = document.createElement("audio");
        obj.src = "https://demo11.axxiem.com/wp-content/uploads/2023/08/mixkit-air-woosh-1489-trim.wav"; 
        obj.play();
    });
});
});	
</script>
</body>
</html>