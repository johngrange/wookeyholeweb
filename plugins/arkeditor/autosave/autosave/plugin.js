/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
(function(){CKEDITOR.plugins.add("autosave",{init:function(n){if(n.elementMode==CKEDITOR.ELEMENT_MODE_INLINE&&n.config.enableInlineAutoSave)n.on("blur",function(){var n=this.editable(),t=n.getCustomData("itemId"),i=n.getCustomData("type"),r=n.getCustomData("context"),u=n.getCustomData("itemType"),f=n.getData(),e={itemId:t,type:i,context:r,itemType:u,content:f};this.fire("saveContent",e,this)},null,null,11)}})})()