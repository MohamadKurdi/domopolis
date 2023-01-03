function passwordToggle(elem){
	let passwdInput = elem.prev('input');
	let eye = elem.children('i');
	
	if (passwdInput.attr('type') === "password") {
		passwdInput.attr('type', 'text');
		eye.removeClass('fa-eye, fa-eye-slash').addClass('fa-eye-slash');
		} else {
		passwdInput.attr('type', 'password');
		eye.removeClass('fa-eye, fa-eye-slash').addClass('fa-eye');
	}
}

$(document).ready(function() {
	$('input[type=password]').after('<span class="password-toggle 1" onclick="passwordToggle($(this));"><i class="fas fa-eye"></i></span>');
});



//  social_auth

var social_auth = {
	
    'googleplus': function(th) {
        
        var button = th;
        
        var text_old = $(button).html();
        var text_loading = $(button).attr('data-loading-text');
        if(text_loading == ''){
            text_loading = 'Loading';
		}
		
        // login googleplus
		
        $(button).html(text_loading);

     	setTimeout(function(){
        	$(button).html(text_old);
        }, 1500);
		
        var googleplus_url = '/index.php?route=module/social_auth/iframeGoogleLogin';
		
        var w = 500;
        var h = 600;
		
        var left = (screen.width - w) / 2;
        var top = (screen.height - h) / 4;
		
		
        window.open(googleplus_url, 'googleplus auth', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
		
		
	},
    'instagram': function(th) {
        
        var button = th;
        
        var text_old = $(button).html();
        var text_loading = $(button).attr('data-loading-text');
        if(text_loading == ''){
            text_loading = 'Loading';
		}
		
        // login instagram
		
        $(button).html(text_loading);

        setTimeout(function(){
        	$(button).html(text_old);
        }, 1500);
		
        var url = '/index.php?route=module/social_auth/iframeInstagramLogin';
		
        var w = 500;
        var h = 600;
		
        var left = (screen.width - w) / 2;
        var top = (screen.height - h) / 4;
		
		
        window.open(url, 'Instagram auth', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
		
		
		},
		'facebook': function(th) {
			
			var button = th;
			
			var text_old = $(button).html();
			var text_loading = $(button).attr('data-loading-text');
			if(text_loading == ''){
				text_loading = 'Loading';
			}
			
			// Facebook instagram
			
			$(button).html(text_loading);

			setTimeout(function(){
	        	$(button).html(text_old);
	        }, 1500);
			
			var url = '/index.php?route=module/social_auth/iframeFacebookLogin';
			
			var w = 500;
			var h = 600;
			
			var left = (screen.width - w) / 2;
			var top = (screen.height - h) / 4;
			
			
			window.open(url, 'Facebook auth', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
			
			
		}
}
