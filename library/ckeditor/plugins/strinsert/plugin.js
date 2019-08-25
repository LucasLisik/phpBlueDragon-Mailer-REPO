/**
 * @license Copyright © 2013 Stuart Sillitoe <stuart@vericode.co.uk>
 * This work is mine, and yours. You can modify it as you wish.
 *
 * Stuart Sillitoe
 * stuartsillitoe.co.uk
 *
 */

CKEDITOR.plugins.add('strinsert',
{
	requires : ['richcombo'],
	init : function( editor )
	{
		//  array of strings to choose from that'll be inserted into the editor
		var strings = [];
		//strings.push(['aaa', 'bbb', 'ccc']);
		//strings.push(['@@Glossary::displayList()@@', 'Glossary', 'Glossary']);
		//strings.push(['@@CareerCourse::displayList()@@', 'Career Courses', 'Career Courses']);

        strings.push(['[email_email]', '[email_email]', '[email_email]']);
        strings.push(['[email_title]', '[email_title]', '[email_title]']);
        strings.push(['[email_name]', '[email_name]', '[email_name]']);
        strings.push(['[email_lastname]', '[email_lastname]', '[email_lastname]']);
        strings.push(['[email_initial]', '[email_initial]', '[email_initial]']);
        strings.push(['[email_address1]', '[email_address1]', '[email_address1]']);
        strings.push(['[email_address2]', '[email_address2]', '[email_address2]']);
        strings.push(['[email_city]', '[email_city]', '[email_city]']);
        strings.push(['[email_state]', '[email_state]', '[email_state]']);
        strings.push(['[email_postcode]', '[email_postcode]', '[email_postcode]']);
        strings.push(['[email_country]', '[email_country]', '[email_country]']);
        strings.push(['[email_firm]', '[email_firm]', '[email_firm]']);
        strings.push(['[email_jobtitle]', '[email_jobtitle]', '[email_jobtitle]']);
        strings.push(['[email_network]', '[email_network]', '[email_network]']);
        strings.push(['[email_phone]', '[email_phone]', '[email_phone]']);
        strings.push(['[email_fax]', '[email_fax]', '[email_fax]']);
        strings.push(['[email_field1]', '[email_field1]', '[email_field1]']);
        strings.push(['[email_field2]', '[email_field2]', '[email_field2]']);
        strings.push(['[email_field3]', '[email_field3]', '[email_field3]']);
        strings.push(['[email_field4]', '[email_field4]', '[email_field4]']);
        
		// add the menu to the editor
		editor.ui.addRichCombo('strinsert',
		{
			label: 		'Insert Special Label',
			title: 		'Insert Special Label',
			voiceLabel: 'Insert Special Label',
			className: 	'cke_format',
			multiSelect:false,
			panel:
			{
				css: [ editor.config.contentsCss, CKEDITOR.skin.getPath('editor') ],
				voiceLabel: editor.lang.panelVoiceLabel
			},

			init: function()
			{
				this.startGroup( "Insert Special Label" );
				for (var i in strings)
				{
					this.add(strings[i][0], strings[i][1], strings[i][2]);
				}
			},

			onClick: function( value )
			{
				editor.focus();
				editor.fire( 'saveSnapshot' );
				editor.insertHtml(value);
				editor.fire( 'saveSnapshot' );
			}
		});
	}
});