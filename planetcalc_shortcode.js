jQuery(document).ready(function($) {
var $planetcalc_dlg = $('<div title="Select a calculator"><fieldset><select style="width:100%" id="planetcalc_calc_list"></select>'
+ '<div style="width:100%"><div id="planetcalc_languages" class="aligncenter"><input type="radio" id="planetcalc_lang_en" name="planetcalc_lang" value="en" checked="checked"><label for="planetcalc_lang_en" style="height:34px"><img src="' + window.Planetcalc.plugin_url + 'gb.png" alt="en"></label><input type="radio" id="planetcalc_lang_es" name="planetcalc_lang"  value="es"><label for="planetcalc_lang_es"  style="height:34px"><img src="' + window.Planetcalc.plugin_url + 'es.png" alt="es"></label><input type="radio" id="planetcalc_lang_ru" name="planetcalc_lang" value="ru"><label for="planetcalc_lang_ru"  style="height:34px"><img src="' + window.Planetcalc.plugin_url + 'ru.png" alt="ru"></label></div></div>'
+ '</fieldset><p>More options with <a href="http://planetcalc.com/accounts/">PLANETCALC Premium</a>.</p></div>');

function fill_select_options( opts ) {
	$list = $planetcalc_dlg.find('#planetcalc_calc_list');
	$list.find('option').remove().end();
	for( var i =0;i<opts.length;++i ) {
		var itm = opts[i];
		$itm = $("<option></option>").attr("value",itm.id);
		if ( itm.id == window.Planetcalc.default_item ) {
       		  $itm.attr("selected","selected");
		}
		$list.append( $itm.text(itm.name) );
	}
}

fill_select_options( window.Planetcalc.en.calculators );

$planetcalc_dlg.find( "#planetcalc_languages" ).buttonset();
$planetcalc_dlg.find( "[name=planetcalc_lang]" ).on("change", function () {
    fill_select_options(window.Planetcalc[this.value].calculators);
});
$planetcalc_dlg.dialog({                   
        'dialogClass'   : 'wp-dialog',           
        'modal'         : true,
        'autoOpen'      : false, 
        'closeOnEscape' : true,      
        'buttons'       : {
            "OK": function() {
                $(this).dialog('close');
                selected = tinyMCE.activeEditor.selection.getContent();
		var cid = $planetcalc_dlg.find("#planetcalc_calc_list option:selected").val();
		var lang = $planetcalc_dlg.find("[name=planetcalc_lang]:checked").val();
                content =  '[planetcalc cid="' + cid + '" language="' + lang + '" width="'+window.Planetcalc.default_width+'" height="'+window.Planetcalc.default_height+'"]';
                tinymce.execCommand('mceInsertContent', false, content);
            }
        }
});
    tinymce.create('tinymce.plugins.planetcalc_plugin', {
        init : function(ed, url) {
                // Register command for when button is clicked
                ed.addCommand('planetcalc_insert_shortcode', function() {
		        $planetcalc_dlg.dialog('open');
                });

            // Register buttons - trigger above command when clicked
            ed.addButton('planetcalc_button', {title : 'Insert PLANETCALC calculator', cmd : 'planetcalc_insert_shortcode', image: url + '/planetcalc.png' });
        },   
    });

    // Register our TinyMCE plugin
    // first parameter is the button ID1
    // second parameter must match the first parameter of the tinymce.create() function above
    tinymce.PluginManager.add('planetcalc_button', tinymce.plugins.planetcalc_plugin);
});
