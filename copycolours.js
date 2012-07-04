M.copycolours = {
    Y: null,
    courseid: null,
    numsections: null,

    init: function(Y, options) {
        this.Y = Y;
        this.courseid = options.courseid;
        this.numsections = options.numsections;

        this.Y.all('.format_colour_copycolours').each( function(el) {
            var id = el.get('id');
            id = id.split('-');
            if (id.length != 2 || id[0] != 'format_colour_copycolours') {
                return;
            }
            var sectionid = parseInt(id[1]);
            this.Y.on('click', this.show_choose_section, el, this, sectionid);
        }, this);
    },

    show_choose_section: function(e, sectionid) {
        e.preventDefault();
        e.stopPropagation();

        var i;
        var defaultsection = sectionid - 1;
        if (defaultsection < 0) {
            defaultsection = 1;
        }
        content = M.util.get_string('copysrc', 'format_colours')+': ';
        content += '<select name="sectionfrom" id="format_colours_sectionfrom-'+sectionid+'">';
        for (i=0; i<=this.numsections; i++) {
            if (i == sectionid) {
                continue;
            }
            var selected = (defaultsection == i) ? 'selected = "selected" ' : '';
            content += '<option value="'+i+'" '+selected+'>'+i+'</option>';
        }
        content += '</select>';
        var Y = this.Y;
        var self = this;
        var panel = new Y.Panel({
            bodyContent: content,
            width: 350,
            zIndex: 5,
            centered: true,
            modal: true,
            visible: true,
            render: true,
            buttons: [{
                value: M.util.get_string('docopycolours', 'format_colours'),
                action: function(e) {
                    e.preventDefault();
                    panel.hide();
                    var select = Y.one('#format_colours_sectionfrom-'+sectionid);
                    var copyurl = M.cfg.wwwroot+'/course/format/colours/copycolours.php?';
                    copyurl += 'courseid='+self.courseid;
                    copyurl += '&from='+select.get('value');
                    copyurl += '&to='+sectionid;
                    copyurl += '&sesskey='+M.cfg.sesskey;
                    window.location = copyurl;
                },
                section: Y.WidgetStdMod.FOOTER
            },{
                value: M.util.get_string('cancel', 'core'),
                action: function(e) {
                    e.preventDefault();
                    panel.hide();
                },
                section: Y.WidgetStdMod.FOOTER
            }]
        });
    }
}