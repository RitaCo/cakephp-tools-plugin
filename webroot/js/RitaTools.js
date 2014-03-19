$.noty.defaults = {
    layout: 'top',
    theme: 'ritaTheme',
    type: 'alert',
    text: '', // can be html or string
    dismissQueue: true, // If you want to use queue feature set this true
    template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
    animation: {
        open: {height: 'toggle'},
        close: {height: 'toggle'},
        easing: 'swing',
        speed: 500 // opening & closing animation speed
    },
    timeout: false, // delay for closing event. Set false for sticky notifications
    force: false, // adds notification to the beginning of queue when set to true
    modal: false,
    maxVisible: 5, // you can set max visible notification for dismissQueue true option,
    killer: false, // for close all notifications before show
    closeWith: ['click'], // ['click', 'button', 'hover']
    callback: {
        onShow: function() {},
        afterShow: function() {},
        onClose: function() {},
        afterClose: function() {}
    },
    buttons: false // an array of buttons
};



var alert = {
	options: function(){
		return {
            dismissQueue: true,
            modal       : false,
            maxVisible  : 3,
            timeout     : 2000,
            layout      : 'bottomRight',
            theme       : 'ritaTheme'
		}		
	},
	error: function(msg){
		var opt = this.options();
		l(opt);
		opt.type = 'error';
		opt.text = msg;
		var n = noty(opt);
	},
	info: function(msg){
		var opt = this.options();
		l(opt);
		opt.type = 'information';
		opt.text = msg;
		 var n = noty(opt);
	},
	warning: function(msg){
		var opt = this.options();
		l(opt);
		opt.type = 'warning';
		opt.text = msg;
		
		var n = noty(opt);
	},
	success: function(msg){
		var opt = this.options();
		l(opt);
		opt.type = 'success';
		opt.text = msg;
		 noty(opt);
	}
	
}