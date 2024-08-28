function helpLink() {
	var newwindow = window.open('help/contact.php','Help','resizable=1,height=640,width=640,scrollbars=1');
	if (window.focus) {
		newwindow.focus()
	}
	if (navigator.appName == "Microsoft Internet Explorer") {
		newwindow.location = newwindow.location;
	}
}