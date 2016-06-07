$(document).ready(function(){
	// HTML markup implementation, overlap mode, push 3 DOM elements
	$( '#menu' ).multilevelpushmenu({
		backText: 'Back1',                                          
		backItemClass: 'backItemClass',                            
		backItemIcon: 'fa fa-angle-right',
		containersToPush: [$( '#pushobj' ), $( '#pushthisobjalso' ), $( '#pushthisobjtoo' )]
	});
});