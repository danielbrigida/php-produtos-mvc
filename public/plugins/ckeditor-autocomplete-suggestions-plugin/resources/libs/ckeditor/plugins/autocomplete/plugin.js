CKEDITOR.plugins.add('autocomplete',
				{
					init : function(editor) {
						
						 var autocompleteCommand = editor.addCommand('autocomplete', {
							exec : function(editor) {

								var dummyElement = editor.document
										.createElement('span');
								editor.insertElement(dummyElement);

								var x = 0;
								var y = 0;

								var obj = dummyElement.$;

								while (obj.offsetParent) {
									x += obj.offsetLeft;
									y += obj.offsetTop;
									obj = obj.offsetParent;
								}
								x += obj.offsetLeft;
								y += obj.offsetTop;

								dummyElement.remove();

								editor.contextMenu.show(editor.document
										.getBody(), null, x, y);
							}
						});
					},

					afterInit : function(editor) {
						editor.on('key', function(evt) {
							if (evt.data.keyCode == 27) {
								editor.execCommand('autocomplete');
							}
						});
						
						var firstExecution = true;
						var dataElement = {};
						
						 editor.addCommand('reloadSuggetionBox', {
								exec : function(editor,suggestions) {
									if (editor.contextMenu) {
										dataElement = {};
										editor.addMenuGroup('suggestionBoxGroup');
										$.each(suggestions,function(i, suggestion) {
															var suggestionBoxItem = "suggestionBoxItem"+ i; 
															dataElement[suggestionBoxItem] = CKEDITOR.TRISTATE_OFF;
															editor.addMenuItem(suggestionBoxItem,
																			{
																				id : suggestion.id,
																				label : suggestion.label,
																				group : 'suggestionBoxGroup',
																				icon  : null,
																				onClick : function() {
                                                                                                                                                                    editor.insertHtml(this.id + '&nbsp;');
																				},
																			});
															});

										if(firstExecution == true)
											{
												editor.contextMenu.addListener(function(element) {
													return dataElement;
												});
											firstExecution = false;
											}
									}
								}
						 });
						
						delete editor._.menuItems.paste;
					},
				});