$(document).ready(function()
{
// start dashboard jquery

$('.latest .latest-hide').click(function(){
	
	$(this).parent().next(".panel-body").slideToggle(300);
	$(this).toggleClass("selected");
	if ($(this).hasClass("selected")) {
		$(this).html("<i class='fa fa-minus pull-right fa-lg'></i>")
	}else
	{
		$(this).html("<i class='fa fa-plus pull-right fa-lg'></i>")
	}
	
}); 
// end dashboard
// place holder hide code
$('[placeholder]').focus(function (){

$(this).attr('data-text', $(this).attr('placeholder'));

$(this).attr('placeholder', '');
}).blur(function (){
$(this).attr('placeholder', $(this).attr('data-text'));
});
// adding required star for update form Astrisk
$('input').each(function(){
	if ($(this).attr('required') === 'required') {
		$(this).after('<span class="astrisk">*</span>')
	}
});
// convert password field to text on hover
$('.show-pass').hover(function(){
	$('.password').attr('type','text');
},function(){
	$('.password').attr('type','password');
});
// Delete confirmation
$('.confirm').click(function(){
	return confirm('Are You Sure');
});

// start categories veiw
// show description or hide them
$(".cat h3").click(function(){
	$(this).toggleClass(".folder_open"); // toggle class of the folder
	$(this).next('.full-view').fadeToggle(300);
	if ($(this).hasClass(".folder_open"))
	{
		$(this).find(".the_folder_open_notopen").html("<i class='fa fa-folder-open'></i>");
	}else {
		$(this).find(".the_folder_open_notopen").html("<i class='fa fa-folder'></i>");
	}
});
// change from full or not full
$('.option span').click(function (){
	$(this).addClass('active').siblings('span').removeClass('active');
	$(".cat h3").addClass('folder_open');
	if ($(this).data('view') === 'classic') {
		$('.cat .full-view').fadeOut(200);
		$(".the_folder_open_notopen").html("<i class='fa fa-folder'></i>");
	}
	else
	{
		$('.cat .full-view').fadeIn(200);
		$(".the_folder_open_notopen").html("<i class='fa fa-folder-open'></i>");
	}
});

// end categories veiw
// start items view
// start show and hide description
$('.items .show_description').click(function(){
	$(this).next('.hide_description').fadeToggle(300);
	$(this).fadeOut(100);
});

// start comment manage
$('.comment_show .comment_func').click(function(){
	$(this).next('div').slideToggle();
	$(this).toggleClass('donehide');
	if ($(this).hasClass('donehide')) 
	{
		$(this).html("<i class='fa fa-minus'></i> Hide");
	}else
	{
		$(this).html("<i class='fa fa-plus'></i> Show");
	}
});

// this function to replace ente with br abd spaces let's work :D
// Very very importamt function
$(".fake_comment").keypress(function(){

		var text_replaced = $(".fake_comment").
		val().replace('*/' , "'><br>").replace(/\n/g, "<br/>").replace(/\s/g,"&nbsp;").replace('/*1*',"<br><img class='img-responsive' style='margin:0 auto;' src='");
		var text_normal = $(".fake_comment").val();
		$("#image_a").click(function(){
			var addImage = $(".fake_comment").val(text_normal + "/*1*photo.png*/");
		});
		document.getElementById('real_comment').value = text_replaced;

});

// end comemnt manage
// start as classic shape and hide description in items site
function classic(){
	$('.cat .full-view').fadeOut(0);
	$('.items .hide_description').fadeOut(0);
	$('.comment_show div').fadeOut(0);
}classic();

// end items view
// start subcategories show delete button
$(".subcats_container").hover(function(){
	$(this).find(".delete_cat").fadeIn(300);
},function(){
	$(this).find(".delete_cat").fadeOut(300);
}
);
// end subcategories
  // Calls the selectBoxIt method on your HTML select box
  $("select").selectBoxIt({
  	    // Uses the jQueryUI 'shake' effect when opening the drop down
    showEffect: "shake",

    // Sets the animation speed to 'slow'
    showEffectSpeed: 'slow',

    // Sets jQueryUI options to shake 1 time when opening the drop down
    showEffectOptions: { times: 1 },

    // Uses the jQueryUI 'explode' effect when closing the drop down
    hideEffect: "explode"

  });
      
  // end Select box

});
