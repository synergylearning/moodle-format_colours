/*global M*/
/*global document*/
M.editcolours = {
    init: function (Y, pickerthumb, huethumb) {
        "use strict";
        Y.use('yui2-dom', 'yui2-event', 'yui2-element', 'yui2-dragdrop', 'yui2-slider', 'yui2-colorpicker', 'yui2-get', function (Y) {
            var editcolours, YAHOO = Y.YUI2;
            editcolours = {
                init: function (Y, pickerthumb, huethumb) {
                    var settings, i, j, suffix, cont, picker, hexval, rgb, fnrgbchange,
                        fntextchange, fnbuttonclick, recentholder, recent, button;
                    settings = ['headback', 'headfore', 'contentback', 'contentfore', 'link'];

                    fnrgbchange = function (o) {
                        var el, newval;
                        el = this.textbox;
                        if (!el) {
                            return;
                        }
                        newval = '#' + YAHOO.util.Color.rgb2hex(o.newValue);
                        el.value = newval;
                    };

                    fntextchange = function () {
                        var hexval, rgb;
                        hexval = this.value;
                        hexval = hexval.substring(1);
                        rgb = YAHOO.util.Color.hex2rgb(hexval);
                        this.picker.setValue(rgb, true);
                    };

                    fnbuttonclick = function () {
                        var colour, rgb;
                        colour = YAHOO.util.Dom.getStyle(this, 'background');
                        rgb = colour.match(/rgb\(([\d, ]*)\)/);
                        if (!rgb) {
                            return;
                        }
                        rgb = rgb[1].split(',');
                        this.picker.setValue(rgb);
                        this.textbox.value = '#' + YAHOO.util.Color.rgb2hex(rgb);
                    };

                    for (i = 0; i < settings.length; i += 1) {
                        suffix = settings[i];
                        cont = document.getElementById('yui-picker-' + suffix);
                        cont.innerHTML = '<div id="yui-picker-inner-' + suffix + '" style="width: 400px; height: 200px; position:relative; padding: 0; margin: 0; top: 0; left: 0;"></div>';

                        picker = new YAHOO.widget.ColorPicker("yui-picker-inner-" + suffix, {
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

                        hexval = document.getElementById('id_' + suffix).value;
                        hexval = hexval.substring(1);
                        rgb = YAHOO.util.Color.hex2rgb(hexval);
                        picker.setValue(rgb, true);
                        picker.textbox = YAHOO.util.Dom.get('id_' + suffix);

                        //listen to rgbChange to be notified about new values
                        picker.on("rgbChange", fnrgbchange);

                        // Copy the colour into the picker, if the textbox changes
                        picker.textbox.picker = picker;
                        YAHOO.util.Event.addListener(picker.textbox, "blur", fntextchange);

                        recentholder = YAHOO.util.Dom.get('format_colours_recent_' + suffix);
                        if (recentholder) {
                            YAHOO.util.Dom.setStyle(recentholder, 'display', 'block');
                            recent = recentholder.getElementsByClassName('format_colours_recent');
                            for (j = 0; j < recent.length; j += 1) {
                                button = recent[j];
                                button.picker = picker;
                                button.textbox = picker.textbox;
                                YAHOO.util.Event.addListener(button, 'click', fnbuttonclick);
                            }
                        }
                    }
                }
            };

            editcolours.init(Y, pickerthumb, huethumb);
        });
    }
};
