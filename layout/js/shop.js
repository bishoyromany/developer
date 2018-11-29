$(document).ready(function(){
	// start main js we need
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

	// end main js we need ffor forms
	// start user nav sign in 
	$(".user_click").click(function(){
		$(this).toggleClass("active_user");
		$(this).children(".user_links").slideToggle();
		$(this).children(".icon_of_toggle").toggleClass("slidup");
		if ($(".icon_of_toggle").hasClass("slidup")) 
		{ $(".icon_of_toggle").html("<i class='fa fa-angle-up down'></i>") }
		else { $(".icon_of_toggle").html('<i class="fa fa-angle-down down"></i>') }
		$(document).click(function(e){
		if(e.target.id != "user_links_menu")
		{
			$(".user_click").removeClass("active_user");
			$(".user_click .user_links").slideUp();
			$(".icon_of_toggle").removeClass("slidup");
			$(".icon_of_toggle").html('<i class="fa fa-angle-down down"></i>');
		}
		});
	});
	// toggle sign in
	$('.login_start').click(function(){
		$(this).toggleClass("active_login");
		$(this).next().fadeToggle();
		$(document).click(function(a){
			if( (a.target.id != "login_start") && (a.target.id != "login_form1") && (a.target.id != "login_form2") && (a.target.id != "login_form") && (a.target.id != "login_form3") )
			{
				$(".login_form").slideUp();
				$(".login_start").removeClass("active_login");
				

			}
		});
	});
	// start show the subcats
	$(".li_underline").hover(function(){
		$(this).find(".subcats_navbar").fadeIn();
	},function(){
		$(this).find(".subcats_navbar").fadeOut();
	});
	// end show subcats
	// add class to categories
	$(".active_categories li a").filter(function(){
    return this.href == location.href.replace(/#.*/, "") && this.id != ("signup");
}).addClass("active_cat");
// end nav bar users sign in
// start sign up page
	$(".caontain_head span").click(function(){
	$(this).addClass("selected").siblings().removeClass("selected");
	$(".form_contain form").hide(50);
	$("." + $(this).data('class')).slideDown();
	});
// end signup page
// this function to replace enter with br abd spaces let's work :D
// Very very importamt function
$(".changer").click(function(){
	$(".faketext").val($(".faketext").val() + $(this).data("text"));
});




 $(".faketext").keyup(function(){
 	var faketext = $(this).val();
 	$(".realtext").val(faketext);
}); 
$(".send_item").click(function(){

	var text_replaced = $(".faketext").val().replace(/\s\s/g, "&nbsp;&nbsp;"),
		text_normal = $(".faketext").val();
	$(".realtext").val(text_replaced);
})



//replace_spaces_enter()
// add item function copy the value
$(".live").keyup(function(){
	$($(this).data("class")).text($(this).val())
});

// start the auto write function
function auto_write()
{
	var text =  $("#auto_write").data("text"),
		i = 0,
		the_auto_write =
	setInterval(function() {
		document.getElementById("auto_write").textContent += text[i];
		i += 1 
		if (i > text.length) { clearInterval(the_auto_write); };
		;},100);
}

// end auto write
// start category hover on the items
$(".more_details").click(function(){
	$(this).parent().parent().parent().parent().find(".pretty_data").slideDown();
});
$(".hide_pretty").click(function(){
	$(this).parent().parent().slideUp();	
	
})
// end category over on the item
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
















function replace_spaces_enter()
{
	var text = $(".change_spaces_enters").text();
	var re = "<br>";
	
	$(".change_spaces_enters").text(text.replace( /\a/g , '<br>'));
}