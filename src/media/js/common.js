$(document).ready(function()
{
	// Show all notifications after a 1 second delay
	$('.notification').hide().delay(1000).slideDown(800);

	// Close button hides notifications
	$('.notification .close').click(function()
	{
		$(this).parent('.notification').fadeOut();
	});
});