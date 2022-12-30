/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
        // Define changes to default configuration here.
        // For complete reference see:
        // http://docs.ckeditor.com/#!/api/CKEDITOR.config

        // The toolbar groups arrangement, optimized for two toolbar rows.

        config.toolbarGroups = [
            { name: 'insert' },
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph',   groups: ['indent', 'blocks', 'align', 'bidi' ] },
            { name: 'colors' },
        ];

	config.extraPlugins = 'autocomplete';
	config.skin         = 'moonocolor';

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';
	config.fullpage=true;
        

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';
	
};
