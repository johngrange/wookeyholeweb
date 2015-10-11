<?php

JHtml::_('behavior.framework');

JHtml::script(Juri::base() . 'media/editors/arkeditor/js/mootools-more-light.js');

JHtml::_('script', 'system/modal.js', true, true);
JHtml::_('stylesheet', 'system/modal.css', array(), true);

class ARKMenuHelper
{
		static  public function getItemId($component, $needles = array(),$identifier = "layout")
		{
			$match = null;
			$component  = JComponentHelper::getComponent($component);
			$app = JFactory::getApplication();
			$menu = $app->getMenu();
		
			$items    = $menu->getItems('component_id', $component->id);

			if ($items) {
				foreach ($needles as $needle => $id) {
					foreach ($items as $item) {
						if ((@$item->query['view'] == $needle) && (@$item->query[$identifier] == $id)) {
							$match = $item->id;
							break;
						}
					}
					if (isset($match)) {
						break;
					}
				}
			}
			return $match ? '&amp;Itemid='.$match : '';
		}

}

$config = $displayData;
?>	
<script type="text/javascript">

CKEDITOR.tools.base64 =
{
    // private property
    _keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

    // public method for encoding
    encode : function (input)
    {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = this._utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output +
            this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
            this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        }

        return output;
    },

    // public method for decoding
    decode : function (input)
    {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        while (i < input.length) {

            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

        }

        output = this._utf8_decode(output);

        return output;

    },

    // private method for UTF-8 encoding
    _utf8_encode : function (string)
    {
        string = string.replace(/\r\n/g,"\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    // private method for UTF-8 decoding
    _utf8_decode : function (utftext)
    {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while ( i < utftext.length ) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i+1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i+1);
                c3 = utftext.charCodeAt(i+2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;

    }
}

CKEDITOR.disableAutoInline = true;

CKEDITOR.enableManualInline = true;

CKEDITOR.autoDisableInline = <?php  echo $config->autoDisableInline;?>;

CKEDITOR.domReady(function(event)
{
 
	var elements = CKEDITOR.document.find('.editable');
	
	var len = elements.count();
	
	function disableNativeLinkClickListener(evt) 
	{
		var domEvent = evt.data;
		domEvent.preventDefault();
	};  
	
   CKEDITOR.toggleEditableContent = function()
   {
        var val = elements.getItem(0).getAttribute('contenteditable') == 'true' ? 'false' : 'true';

        for(var i = 0; i < elements.count();i++)
        {
            elements.getItem(i).setAttribute('contenteditable',val);
        }
    }

	CKEDITOR.inlineAllCustom = function() 
	{
		var el, data;

		for ( var i = 0; i < len; i++ ) 
		{
			el = elements.getItem( i );
					
			// Fire the "inline" event, making it possible to customize
			// the instance settings and eventually cancel the creation.

			data = {
				element: el,
				config: {}
			};

			if ( CKEDITOR.fire( 'inline', data ) !== false )
			{
				var editor = CKEDITOR.inline( el, data.config );
				editor._snapshot = null;
			}	
		}
		
	};
	
	var onClick = CKEDITOR.inlineClick = function(ev)
	{
		
		var element = ev.target || ev.srcElement;
	
		// Find out the div that holds this element.
	
		var name;
		
		while ( element && ( name = element.nodeName.toLowerCase() ) &&
			( name != 'div' || element.className.indexOf( 'editable' ) == -1 ) && name != 'body' )
			element = element.parentNode;
		
		if ( name == 'div' && element.className.indexOf( 'editable' ) != -1 )
		{
			
			var el = CKEDITOR.dom.element.get(element);
			
			var data = {
				element: el,
				config: {}
			};
			
			if (!el.getEditor() && CKEDITOR.fire( 'inline', data ) !== false )
			{
				if ( CKEDITOR.fire( 'inline', data ) !== false  && CKEDITOR.enableManualInline)
				{	
					var editor = CKEDITOR.inline( el, data.config );
					editor._snapshot = null;
					CKEDITOR.once('instanceReady',function()
					{
						var readmoreElement = editor.container.getParent().findOne('.readmore');
						if(readmoreElement)
							readmoreElement.setStyle('display','none');
					
						if(!editor._snapshot)
							editor._snapshot = editor.getSnapshot();	
						editor.loadSnapshot( editor._snapshot );	
					
						var editable = editor.editable();
						if(!editable.getCustomData('hasFirstFocus'))	
						{
									
							var itemid = editable.getCustomData('itemId'),
								type = editable.getCustomData('type'),
								context = editable.getCustomData('context');
									
							var url = 'index.php?option=com_ajax&plugin=inlinecontent&format=json&mode=get&type='+type+'&context='+context+'&id='+itemid;
							
							//Load pre-loader
							var data = {
								'parent': editable,
								'url': editor.config.baseHref+'layouts/joomla/arkeditor/images/712.gif'
							}
							//Fire pre-loader while we load Joomla's article content
							if(type == 'body')
							{
								editor.fire('preloader',data, editor);
				 
								var response = CKEDITOR.ajax.load(url, function(response)
								{
									response = JSON.parse(response || '{}');
									
									if(type == 'title')
										editor.setData(response.data[0].title);
									else
									{
										if(response && response.data)
											editor.setData(response.data[0].data);
										else
										{
											editor.fire('afterPreloader',[], this);
											var readmoreElement = editor.container.getParent().findOne('.readmore');
											if(readmoreElement)
												readmoreElement.setStyle('display','block');
											if(console && response && response.message)
												console.log(response.message);
											alert('Error Loading Data: Please check user permission');
											editor.focusManager.blur();
											editor.editable().$.blur();
											if(CKEDITOR.env.iOS)
											{
												editor.editable().getParent.appendHtml( '<input id="editableFix" style="width:1px;height:1px;border:none;margin:0;padding:0;" tabIndex="-1">');
												var element = CKEDITOR.getById('editableFix');
												element.focus();
												element.$.setSelectionRange(0, 0);
												element.$.blur();
												element.remove();
											}
											return;
										}
											
									}	

									editor._snapshot = editor.getSnapshot();
									editor.loadSnapshot( editor._snapshot );	
									editor.resetDirty();    
									editable.setCustomData('hasFirstFocus',true);
								} );	
							}
							else
							{
								editable.setCustomData('hasFirstFocus',true);
							}		
						}
					});	
				}	
			}	
		}
		
	}
	
	var onFocus = CKEDITOR.inlineFocus = function(ev)
	{
		var parent = ev.sender && ev.sender.$ || null;
		
		// Find out the div that holds this element.
	
		var name;
		
	
		var element = null;
		
		for(i = 0; i < parent.childNodes.length;i++)
		{
			if(parent.childNodes[i].nodeType == 1)
			{
				element = parent.childNodes[i];
				break;
			}
		}
	
		var name = element && element.nodeName.toLowerCase() || '';
	
		if ( name == 'div' && element.className.indexOf( 'editable' ) != -1 )
		{
			var el = CKEDITOR.dom.element.get(element);
			
			var data = {
				element: el,
				config: {}
			};
			
			if (!el.getEditor() && CKEDITOR.fire( 'inline', data ) !== false )
			{
				if ( CKEDITOR.fire( 'inline', data ) !== false  && CKEDITOR.enableManualInline)
				{	
					var editor = CKEDITOR.inline( el, data.config );
					editor._snapshot = null;
					CKEDITOR.once('instanceReady',function()
					{
						var readmoreElement = editor.container.getParent().findOne('.readmore');
						if(readmoreElement)
							readmoreElement.setStyle('display','none');
					
						if(!editor._snapshot)
							editor._snapshot = editor.getSnapshot();	
						editor.loadSnapshot( editor._snapshot );	
					
						var editable = editor.editable();
						if(!editable.getCustomData('hasFirstFocus'))	
						{
									
							var itemid = editable.getCustomData('itemId'),
								type = editable.getCustomData('type'),
								context = editable.getCustomData('context');
									
							var url = 'index.php?option=com_ajax&plugin=inlinecontent&format=json&mode=get&type='+type+'&context='+context+'&id='+itemid;
							
							//Load pre-loader
							var data = {
								'parent': editable,
								'url': editor.config.baseHref+'layouts/joomla/arkeditor/images/712.gif'
							}
							//Fire pre-loader while we load Joomla's article content
							if(type == 'body')
							{
								editor.fire('preloader',data, editor);
				 
								var response = CKEDITOR.ajax.load(url, function(response)
								{
									response = JSON.parse(response || '{}');
									
									if(type == 'title')
										editor.setData(response.data[0].title);
									else
									{
										if(response && response.data)
											editor.setData(response.data[0].data);
										else
										{
											editor.fire('afterPreloader',[], this);
											var readmoreElement = editor.container.getParent().findOne('.readmore');
											if(readmoreElement)
												readmoreElement.setStyle('display','block');
											if(console && response && response.message)
												console.log(response.message);
											alert('Error Loading Data: Please check user permission');
											editor.focusManager.blur();
											editor.editable().$.blur();
											if(CKEDITOR.env.iOS)
											{
												editor.editable().getParent.appendHtml( '<input id="editableFix" style="width:1px;height:1px;border:none;margin:0;padding:0;" tabIndex="-1">');
												var element = CKEDITOR.getById('editableFix');
												element.focus();
												element.$.setSelectionRange(0, 0);
												element.$.blur();
												element.remove();
											}
											return;
										}
											
									}	

									editor._snapshot = editor.getSnapshot();
									editor.loadSnapshot( editor._snapshot );	
									editor.resetDirty();    
									editable.setCustomData('hasFirstFocus',true);
								} );	
							}
							else
							{
								editable.setCustomData('hasFirstFocus',true);
							}		
						}
					});	
				}	
			}	
		}
		
	}
	
	var disableAllNativeLinkClickListener = CKEDITOR.disableAllNativeLinkClickListener = function()
	{
		
		for(var i = 0; i < elements.count();i++)
		{
		  var item = elements.getItem(i);
		  var element = item.getAscendant('a');
		  if(!element)
			continue;
		  element.on('click', disableNativeLinkClickListener);  
		  element.on('focus',onFocus);
		}
	}	
	
	CKEDITOR.removeLinkFocusListener = function()
	{
		for(var i = 0; i < elements.count();i++)
		{
		  var item = elements.getItem(i);
		  var element = item.getAscendant('a');
		  if(!element)
			continue;
		  element.removeListener('focus',onFocus);
		}
	}
	
	CKEDITOR.tools.cleanHtml = function(html)
	{
		var cleanup = CKEDITOR.dom.element.createFromHtml( '<textarea>'+html+'</textarea>', CKEDITOR.document );
		return cleanup.getValue();
	}
		
	CKEDITOR.on( 'instanceCreated', function( event ) 
	{
	
		var editor = event.editor;
		
		if(editor.status == 'loaded')
			return true;
	
		// Customize the editor configurations on "configLoaded" event,
		// which is fired after the configuration file loading and
		// execution. This makes it possible to change the
		// configurations before the editor initialization takes place.

		editor.on( 'configLoaded', function() {

			// Remove unnecessary plugins to make the editor simpler.
			this.config.removePlugins = 'colorbutton,flash,font,' +
				'iframe,newpage,removeformat,' +
				'smiley,specialchar,form';
				
			//this.config.extraAllowedContent = 'hr[class,id]';
			
			if(this.element.getAttribute('data-type') == 'title')
			{
				this.config.toolbar = <?php echo json_encode($config->toolbar_title);  ?>;
				this.config.enterMode = CKEDITOR.ENTER_BR;
				this.config.autoParagraph = false; //catch on instance load
				//this.config.allowedContent = 'xyz';
			}
			else
				this.config.allowedContent = true; 	
				
				
			this.config.stylesheetParser_skipSelectors = /(^body\.|cke_|__|sbox|^input|^textarea|^button|^select|^form|^fieldset|^\.modal-backdrop|^div\.modal|^\.dropdown-backdrop|.chzn)/i;
			this.config.stylesheetParser_validSelectors = /\w*?(\.|#)\w+/; 
			
			var styleSheets = document.styleSheets;
			this.config.contentsCss = [];
			
			for(var i = 0; i < styleSheets.length; i++)
			{
				this.config.contentsCss[i] = styleSheets[i].href;
			}	
		});
		
		editor.on('beforeDestroy', function (event)
		{
			var editable = this.editable();
			
			var itemid = editable.getCustomData('itemId'),
			type = editable.getCustomData('type'),
			context = editable.getCustomData('context');
			itemtype = editable.getCustomData('itemType');
			
			if(!editor._snapshot)
				return;
	
			if(!editable.hasFocus)
				this.loadSnapshot( editor._snapshot );	
								
			var content = this.getData();
			
			var data = {
				'itemId': itemid,
				'type': type,
				'context': context,
				'itemType': itemtype,
				'content': content,
				'updateElement': true
			}
			
			//remove any listeners
			editor.editable().removeListener('click',disableNativeLinkClickListener);
			
			//clear saved snapshot;
			editor._snapshot = null;
		
			this.fire('saveContent',data, this);
		
		});
		
		CKEDITOR.on('instanceLoaded', function(event)
		{
			var editor = event.editor;
			
			if(editor.element.getAttribute('data-type') == 'title')	
			{	
				editor.config.autoParagraph = false; // override component settings
			}	
			editor.config.pasteFromWordCleanupFile = editor.config.baseHref + 'plugins/editors/arkeditor/ckeditor/plugins/pastefromword/filter/'+ editor.config.pasteFromWordCleanupFile + '.js';
		})
		
		
	    <?php foreach((array)$config as $key=>$value) : ?>
			<?php if($key != "formatsource") : ?>
				<?php if(is_array($value)) : ?>
				editor.config.<?php echo $key ?> =  <?php echo json_encode($value).";\n"; ?>
				<?php else : ?>
				editor.config.<?php echo $key ?> =  <?php echo  (is_int($value) ? $value :  "'$value'").";\n"; ?>
				<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	});
   
	 
	CKEDITOR.on( 'instanceCreated', function( event ) 
	{
		var editor = event.editor;
		
		if(editor.status == 'loaded')
			return true;
			
		// Customize the editor configurations on "configLoaded" event,
		// which is fired after the configuration file loading and
		// execution. This makes it possible to change the
		// configurations before the editor initialization takes place.

		editor.on( 'configLoaded', function() {

			// Remove unnecessary plugins to make the editor simpler.
			this.config.removePlugins = 'colorbutton,find,flash,font,' +
				'iframe,newpage,removeformat,' +
				'smiley,specialchar,form';
				
			//this.config.extraAllowedContent = 'hr[class,id]';
			
			if(this.element.getAttribute('data-type') == 'title')
			{
				this.config.toolbar = <?php echo json_encode($config->toolbar_title);  ?>;
				this.config.enterMode = CKEDITOR.ENTER_BR;
				this.config.allowedContent = 'xyz';
				this.config.toolbarName = 'title';
			}
			else
				this.config.allowedContent = true; 	
				
				
			this.config.stylesheetParser_skipSelectors = /(^body\.|cke_|__|sbox|^input|^textarea|^button|^select|^form|^fieldset|^\.modal-backdrop|^div\.modal|^\.dropdown-backdrop|.chzn)/i;
			this.config.stylesheetParser_validSelectors = /\w*?(\.|#)\w+/; 
			
			var styleSheets = document.styleSheets;
			this.config.contentsCss = [];
			
			for(var i = 0; i < styleSheets.length; i++)
			{
				this.config.contentsCss[i] = styleSheets[i].href;
			}	
		});
		
		editor.on('beforeDestroy', function (event)
		{
			var editable = this.editable();
			
			if(!editable)
				return;
			var itemid = editable.getCustomData('itemId'),
			type = editable.getCustomData('type'),
			context = editable.getCustomData('context');
			itemtype = editable.getCustomData('itemType');
			
			if(!editor._snapshot)
				return;
	
			if(!editable.hasFocus)
				this.loadSnapshot( editor._snapshot );	
								
			var content = this.getData();
			
			var data = {
				'itemId': itemid,
				'type': type,
				'context': context,
				'itemType': itemtype,
				'content': content,
				'updateElement': true
			}
			
			//destroy on click event
			
			this.fire('saveContent',data, this);
		
		});
		
	    <?php foreach((array)$config as $key=>$value) : ?>
			<?php if($key != "formatsource") : ?>
				<?php if(is_array($value)) : ?>
				editor.config.<?php echo $key ?> =  <?php echo json_encode($value).";\n"; ?>
				<?php else : ?>
				editor.config.<?php echo $key ?> =  <?php echo  (is_int($value) ? $value :  "'$value'").";\n"; ?>
				<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	});
	
	
	//Add back code
	
	CKEDITOR.on( 'instanceReady', function( event ) 
	{
		var editor = event.editor;
		<?php echo $config->formatsource; ?>
        
        var editable = editor.editable();
		
		
		var itemId = editable.getAttribute('data-id');
		var type = editable.getAttribute('data-type');
		var context = editable.getAttribute('data-context');
		var itemType = editable.getAttribute('data-itemType');
		var versionsURL = 'index.php?option=com_contenthistory&view=history&layout=modal&tmpl=component&item_id=[ITEM_ID]&type_id=[TYPE_ID]&type_alias=[ITEM_TYPE]&<?php echo JSession::getFormToken(); ?>=1&inline=1&editor='+editor.name;
		var pageBreakURL = 'index.php?option=com_content&view=article&layout=pagebreak&tmpl=component&e_name='+editor.name;
		
        editable.setCustomData('itemId',itemId);
		var modTypeId = <?php $typeTable = JTable::getInstance('Contenttype', 'JTable'); echo $typeTable->getTypeId('com_modules.custom'); ?>;
		editable.setCustomData('modTypeId',modTypeId);
		editable.setCustomData('type',type);
		editable.setCustomData('context',context);
		editable.setCustomData('itemType',itemType);
		editable.setCustomData('versionsURL',versionsURL);
		editable.setCustomData('pageBreakURL',pageBreakURL);
		editable.setCustomData('newArticleURL','<?php echo 'index.php?option=com_content&view=form&layout=edit'.ARKMenuHelper::getItemId('com_content',array('form'=>'edit','categories' => NULL));?>');
		
		var urlencode = function(str)
		{
			return encodeURIComponent(str).replace(/~/g, '%7E').replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');

		};
		
		editor.on('focus', function()
        {
            var readmoreElement = this.container.getParent().findOne('.readmore');
            if(readmoreElement)
                readmoreElement.setStyle('display','none');
					
			if(!editor._snapshot)
			{
				editor._snapshot = this.getSnapshot();
	
			}		
			this.loadSnapshot( editor._snapshot );	
		
	
			var editable = this.editable();
			if(!editable.getCustomData('hasFirstFocus'))	
			{
			  var itemid = editable.getCustomData('itemId'),
					type = editable.getCustomData('type'),
					context = editable.getCustomData('context');
				var url = 'index.php?option=com_ajax&plugin=inlinecontent&format=json&mode=get&type='+type+'&context='+context+'&id='+itemid;
				//Load pre-loader
 				var data = {
					'parent': editable,
					'url': editor.config.baseHref+'layouts/joomla/arkeditor/images/712.gif'
				}
				//Fire pre-loader while we load Joomla's article content
				if(type == 'body')
				{
					editor.fire('preloader',data, editor);
								 			
					var response = CKEDITOR.ajax.load(url, function(response)
					{
						response = JSON.parse(response || '{}');
						if(type == 'title')
						{
							if(response && response.data)
								editor.setData(response.data[0].title);
						
						}
						else
						{
						
							if(response && response.data)
							{
								editor.setData(response.data[0].data);
							}
							else
							{
								editor.fire('afterPreloader',[], this);
								var readmoreElement = editor.container.getParent().findOne('.readmore');
								if(readmoreElement)
									readmoreElement.setStyle('display','block');
								if(console && response && response.message)
									console.log(response.message);
								alert('Error Loading Data: Please check user permission');
								editor.focusManager.blur();
								editor.editable().$.blur();
								if(CKEDITOR.env.iOS)
								{
									editor.editable().getParent.appendHtml( '<input id="editableFix" style="width:1px;height:1px;border:none;margin:0;padding:0;" tabIndex="-1">');
									var element = CKEDITOR.getById('editableFix');
									element.focus();
									element.$.setSelectionRange(0, 0);
									element.$.blur();
									element.remove();
								}
								return;
							}		
						}	

						editor._snapshot = editor.getSnapshot();
						editor.loadSnapshot( editor._snapshot );	
						editor.resetDirty();    
						editable.setCustomData('hasFirstFocus',true);

					} );	
				}
				else
				{
					editable.setCustomData('hasFirstFocus',true);
				}
			}
       });

        editor.on('blur', function()
        {
    
			if(!editable.getCustomData('hasFirstFocus')) //Nothing to do
				return;

			var readmoreElement = this.container.getParent().findOne('.readmore');
            if(readmoreElement)
                readmoreElement.setStyle('display','block');
				
			var itemid = editable.getCustomData('itemId'),
				type = editable.getCustomData('type'),
				context = editable.getCustomData('context');
				itemtype = editable.getCustomData('itemType');
				
			this.fire('updateshapshot');
			editor._snapshot = this.getSnapshot();
			
			
			if(type == 'title')
			{
				var temp = CKEDITOR.dom.element.createFromHtml('<div>'+editor._snapshot+'</div>');
				var text = temp.getText();
				editor._snapshot = text;
				this.loadSnapshot( editor._snapshot );
			}
			
			
			if(type == 'body')
			{	
				    var url = 'index.php';
				    var params = 'option=com_ajax&plugin=inlinecontent&format=json&mode=process&context='+context+'&id='+itemid+'&itemtype='+itemtype;
				    data = this.getData();
				    params += '&data='+ urlencode(CKEDITOR.tools.base64.encode(data));
				    var response = CKEDITOR.ajax.post(url,params, function(response)
                    {
                        response = JSON.parse(response || '{}');
						if(response && response.data)
							editor.setData(response.data[0].data);
						else
							if(console && response && response.message)
								console.log(response.message)
                    });	
            
			}
        },null,null,10);
		
		
		editor.on('loadContentVersion',function(event)
		{
			var versionid = event.data.versionId,
			type = event.data.type,
			context = event.data.context;
			itemtype = event.data.itemType;
			data = event.data.content;
	
			if(data)
				this.setData(data);
			else
				alert('Could not load version');
		},null,null,10);
		

			
		editor.on('saveContent',function(event)
		{
			
			var editable = this.editable();
					
			var itemid = event.data.itemId,
			type = event.data.type,
			context = event.data.context;
			itemtype = event.data.itemType;
			data = event.data.content,
			updateElement = event.data.updateElement;

			var url = 'index.php';
			var params = 'option=com_ajax&plugin=inlinecontent&format=json&mode=save&context='+context+'&id='+itemid+'&itemtype='+itemtype;
			if(type == 'title')
				params += '&data[title]='+ data;
			else
			{
				if(!editable.getCustomData('hasFirstFocus')) //Nothing to do so bail out
					return;
				params += '&data[articletext]='+ urlencode(CKEDITOR.tools.base64.encode(data));
			}	
			params +='&type='+type;	
			
			var response = CKEDITOR.ajax.post(url,params);	
			response = JSON.parse(response || '{}');
			if(response && response.data)
			{
				if(updateElement)
				{
					if(type == 'title')
						editor.setData(response.data[0].title);
					else
						editor.setData(response.data[0].data);
				}
				editor._snapshot = editor.getSnapshot();
				editor.loadSnapshot( editor._snapshot );	
                editor.resetDirty();
			}
			else
			{
				if(response && response.message && console)
					console.log(response.message);
			}	
		},null,null,10);
		
			
		editor.on('setData', function (event)
		{
			var datavalue = event.data.dataValue;
		
		})

        //zIndex for Joomla Edit button
        var editBtn = editable.getPrevious(function(elem){return elem.type == CKEDITOR.NODE_ELEMENT});

        if(editBtn && editBtn.hasClass('btn-group') && editBtn.hasClass('pull-right'))
        {
            editBtn.setStyle('z-index',999);
        }
        else if(editBtn)
        {
            var child = editBtn.findOne('.btn-group.pull-right');
      
            if(child)
                child.setStyle('z-index',999);
        }
        
	    editor.on('focus', function()
		{
			if(CKEDITOR.env.iOS)
			{
				var floatspace = this.ui.space('top').getParent().getParent();
				var spaceRect = floatspace.getClientRect();
				var spaceHeight = spaceRect.height;
				var editorPos = this.editable().getDocumentPosition();
				floatspace.setStyle('top',(editorPos.y-spaceHeight)+'px' );
				floatspace.setStyle('position','absolute');
				delete CKEDITOR.document.getWindow().getPrivate().events['scroll'];
			}	
		});
		
		if ( CKEDITOR.env.iOS ) {

			editor.editable().attachListener( editor.document, 'touchend', function(evt) {
				var element = evt.data.getTarget();
				if(element)
				{
					if(element.is('img'))
					{
						editor.focus();
						var selection = editor.getSelection();
						selection.selectElement(element);
						evt.cancel();
					}
				}
			} );
		}
		
		if ( CKEDITOR.env.webkit ) {

			editor.editable().attachListener( editor.document, 'mouseup', function(evt) {
				var element = evt.data.getTarget();
				if(element)
				{
					if(element.is('img'))
					{
						editor.focus();
						var selection = editor.getSelection();
						selection.selectElement(element);
						evt.cancel();
					}
				}
			});
		}

	});
	

	CKEDITOR.addCss( 'body { background: '+ '<?php echo $config->bgcolor; ?>' + ' none;'+ <?php echo $config->textalign;?>+'}' );
	<?php echo $config->ftcolor ? "CKEDITOR.addCss( 'body { color: ". $config->ftcolor." }' );" : ""; ?>
    <?php echo $config->ftfamily ? "CKEDITOR.addCss( 'body { font-family: ". $config->ftfamily." }' );" : ""; ?>  	
	<?php echo $config->ftsize ? "CKEDITOR.addCss( 'body { font-size: ". $config->ftsize." }' );" : ""; ?>  
	
    
	
    //initialize all editor instances
	if( len < 11 && !CKEDITOR.autoDisableInline) 
		CKEDITOR.inlineAllCustom();
	else
	{
		if ( window.addEventListener )
				document.body.addEventListener( 'click', onClick, false );
		else if ( window.attachEvent )
			document.body.attachEvent( 'onclick', onClick );
		
	}
	if(CKEDITOR.autoDisableInline)
	{
		CKEDITOR.fire('autoDisableInline');
	}	
	else
	{	
		disableAllNativeLinkClickListener();
	}	
});

	
function jLoadVersion(id,editorName)
{
	var editor = CKEDITOR.instances[editorName];
	
	editor.focusManager.focus(); // focus the editor
	
	var editable = editor.editable();
	
	var type = editable.getCustomData('type'),
	context = editable.getCustomData('context');
	itemtype = editable.getCustomData('itemType');
	
	var url = 'index.php?option=com_ajax&plugin=inlinecontent&format=json&mode=version&context='+context+'&id='+id+'&itemtype='+itemtype;
	
	var response = CKEDITOR.ajax.load(url);	
		response = JSON.parse(response);
	
	var content = response.data[0].data;
	
	var data = {
		'versionId': id,
		'type': type,
		'context': context,
		'itemType': itemtype,
		'content': content
	}
	
	editor.fire('loadContentVersion',data, editor); // Fire loadContentVersion so that we can manipulate version content if need be before we load the content

}

function jSaveAllInstances()
{
		
	var editors = CKEDITOR.instances;
	
	if(CKEDITOR.currentInstance)
	{
		var instanceName = CKEDITOR.currentInstance.name;
		CKEDITOR.currentInstance.fire('blur');
		editors[instanceName].focusManager.blur();
	}
	
	for(var name in editors)
	{
		var editor = editors[name];
		if(editors[name]._snapshot && editors[name].editable().getCustomData('hasFirstFocus'))
		{
			var editable = editor.editable();
			editor.fire('focus');
		
			var itemid = editable.getCustomData('itemId'),
			type = editable.getCustomData('type'),
			context = editable.getCustomData('context'),
			itemtype = editable.getCustomData('itemType');
			
					
			var content = editor.getData();
			
			var data = {
				'itemId': itemid,
				'type': type,
				'context': context,
				'itemType': itemtype,
				'content': content
			}
			//Fire saveContent event so that we can manipulate editor's content if need be before we save article content in Joomla 
			editor.fire('saveContent',data, editor);
			editor.fire('blur');
			var element = document.querySelector("div.alert");
			element.style.display = 'block';
			window.scrollTo(0,0);
		}	
	}

}

function jDisableOrEnableAllInstances(disableLinks)
{
	var editors = CKEDITOR.instances;
	
	if(CKEDITOR.enableManualInline) 
	{
		
		for(var name in editors)
		{
			var editor = editors[name];
			editor.destroy();
		}	
		CKEDITOR.enableManualInline = false;
		CKEDITOR.removeLinkFocusListener();
		CKEDITOR.toggleEditableContent();
	}
	else
	{
		CKEDITOR.enableManualInline = true;
		CKEDITOR.toggleEditableContent();
		if(disableLinks)
			CKEDITOR.disableAllNativeLinkClickListener();
	}
}



function beforeUnload(evt)
{
	var editors = CKEDITOR.instances;
	var loadPopUp = false;

	for(var name in editors)
	{
		var editor = editors[name];
		if(CKEDITOR.currentInstance)
		{
			if(editor.name == CKEDITOR.currentInstance.name)
			{
				editor.fire('blur');
			}	
		}	
		
		if(editor._snapshot && editor._.previousValue != editor._snapshot)
		{
			loadPopUp = true;
		}
	}
	
	if(loadPopUp)
	{
		var message = 'Some items have not been saved. Your work will be lost. Are you sure you want to navigate away?';
		evt.returnValue = message;
		return message;
	}
}

function beforePageHide(evt)
{
	var editors = CKEDITOR.instances;
	var loadPopUp = false;

		
	for(var name in editors)
	{
		var editor = editors[name];
		if(CKEDITOR.currentInstance)
		{
			if(editor.name == CKEDITOR.currentInstance.name)
			{
				editor.fire('blur');
			}	
		}	
		if(editor._snapshot && editor._.previousValue != editor._snapshot)
		{
			loadPopUp = true;
		}
	}
	if(loadPopUp)
	{
		if(confirm('Some items have not been saved. Your work will be lost. Would you like to save now?'))
		{
			jSaveAllInstances();
		}	
	}
}



function pageHide(evt)
{
	if(evt.persisted)
		beforePageHide.apply(arguments);
}


function ARKEditorUpdateSelectedImageOrLink(instanceName,text)
{
	var editor = CKEDITOR.instances[instanceName];
	
	if(!editor.hasBookMarks)
	{	
		editor.hasBookMarks = function() { return this._bookmarks};
	}
	
	if(!editor.resetBookMarks)
	{	
		editor.resetBookMarks = function() { this._bookmarks = null;};
	}
	
	if(CKEDITOR.env.ie)
	{
		var bookmarks = null;
		
		if( (bookmarks = editor.hasBookMarks()))
		{
			var sel = editor.getSelection();
			sel && sel.selectBookmarks( bookmarks );
			editor.resetBookMarks();
		}
	
	}
	
	if(text.match(/^<a[^>]+?href/i))
	{
		if ( ( element = CKEDITOR.plugins.link.getSelectedLink( editor ) ) && element.hasAttribute( 'href' ) )
		{
				var newElement =  CKEDITOR.dom.element.createFromHtml(text);
				newElement.copyAttributes(element);
				element.data('cke-saved-href',element.getAttribute('href'));
				editor.getSelection().selectElement(element); //content changes so reselect element
				return true;
		}	
	}
	else if (text.match(/^<img/i))
	{
		var selection = editor.getSelection();
		if ( ( element = selection && selection.getSelectedElement()) && element.is( 'img' ) )
		{
				var newElement = CKEDITOR.dom.element.createFromHtml(text);
				newElement.copyAttributes(element);
				element.data('cke-saved-src',element.getAttribute('src'));
				selection.selectElement(element); //content changes so reselect element
				return true;
		}	
		
	}
	
	return false;
}

function jInsertEditorText( text,editor) 
{
	if(!ARKEditorUpdateSelectedImageOrLink(editor,text))
		CKEDITOR.instances[editor].insertHtml( text );
}

if ( window.addEventListener )
{
	if(CKEDITOR.env.iOS)
		window.addEventListener( 'pagehide', pageHide, false );
	else
	{	
		window.addEventListener( 'beforeunload', beforeUnload, false );
	}	

}	
else
    window.attachEvent( 'onbeforeunload', beforeUnload );

	
</script>