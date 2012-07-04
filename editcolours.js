editcolours = {
    init: function(Y, pickerthumb, huethumb) {
        var settings = new Array('headback', 'headfore', 'contentback', 'contentfore', 'link');

        for (var i=0; i<settings.length; i++) {
            var suffix = settings[i];
	    var cont = document.getElementById('yui-picker-'+suffix);
	    cont.innerHTML = '<div id="yui-picker-inner-'+suffix+'" style="width: 400px; height: 200px; position:relative; padding: 0; margin: 0; top: 0; left: 0;"></div>';

	    var picker = new YAHOO.widget.ColorPicker("yui-picker-inner-"+suffix, {
	        images: {
		    PICKER_THUMB: pickerthumb,
		    HUE_THUMB: huethumb
	        },
	        showwebsafe: false,
	        showhexcontrols: false,
	        showhexsummary: false,
	        showrgbcontrols: false,
	        showcontrols: false,
	        txt: {
		    SHOW_CONTROLS: '',
		    HIDE_CONTROLS: ''
	        }
	    });

	    var hexval = document.getElementById('id_'+suffix).value;
	    hexval = hexval.substring(1);
	    var rgb = YAHOO.util.Color.hex2rgb(hexval);
	    picker.setValue(rgb, true);
            picker.textbox = YAHOO.util.Dom.get('id_'+suffix);

	    //listen to rgbChange to be notified about new values
	    picker.on("rgbChange", function(o) {
	        var el = this.textbox;
                if (!el) {
                    return;
                }
	        var newval = '#'+YAHOO.util.Color.rgb2hex(o.newValue);
	        el.value = newval;
	    });

            // Copy the colour into the picker, if the textbox changes
            picker.textbox.picker = picker;
            YAHOO.util.Event.addListener(picker.textbox, "blur", function(o) {
                var hexval = this.value;
                hexval = hexval.substring(1);
                var rgb = YAHOO.util.Color.hex2rgb(hexval);
                this.picker.setValue(rgb, true);
            });

            var recentholder = YAHOO.util.Dom.get('format_colours_recent_'+suffix);
            if (recentholder) {
                YAHOO.util.Dom.setStyle(recentholder, 'display', 'block');
                var recent = recentholder.getElementsByClassName('format_colours_recent');
                for (var j=0; j<recent.length; j++) {
                    var button = recent[j];
                    button.picker = picker;
                    button.textbox = picker.textbox;
                    YAHOO.util.Event.addListener(button, 'click', function(o) {
                        var colour = YAHOO.util.Dom.getStyle(this, 'background');
                        var rgb = colour.match(/\((.*)\)/);
                        if (!rgb) {
                            return;
                        }
                        rgb = rgb[1].split(',');
                        this.picker.setValue(rgb);
                        this.textbox.value = '#'+YAHOO.util.Color.rgb2hex(rgb);
                    });
                }
            }
        }
    }
}
