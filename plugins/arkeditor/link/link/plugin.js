"use strict";
/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
(function(){function t(n){return n.replace(/\\'/g,"'")}function i(n){return n.replace(/'/g,"\\$&")}function y(n){for(var i,u=n.length,r=[],t=0;t<u;t++)i=n.charCodeAt(t),r.push(i);return"String.fromCharCode("+r.join(",")+")"}function p(n,t){for(var e=n.plugins.link,h=e.compiledProtectionFunction.name,o=e.compiledProtectionFunction.params,f,s,r=[h,"("],u=0;u<o.length;u++)f=o[u].toLowerCase(),s=t[f],u>0&&r.push(","),r.push("'",s?i(encodeURIComponent(t[f])):"","'");return r.push(")"),r.join("")}function w(n){var i=n.config.emailProtection||"",t;return i&&i!="encode"&&(t={},i.replace(/^([^(]+)\(([^)]+)\)$/,function(n,i,r){t.name=i;t.params=[];r.replace(/[^,\s]+/g,function(n){t.params.push(n)})})),t}CKEDITOR.plugins.add("link",{requires:"dialog,fakeobjects",lang:"en,de,es,fi,fr,he,it,nl,ru,zh-cn",icons:"anchor,anchor-rtl,link,unlink",hidpi:!0,onLoad:function(){function t(n){return r.replace(/%1/g,n=="rtl"?"right":"left").replace(/%2/g,"cke_contents_"+n)}var i=CKEDITOR.getUrl(this.path+"images"+(CKEDITOR.env.hidpi?"/hidpi":"")+"/anchor.png"),n="background:url("+i+") no-repeat %1 center;border:1px dotted #00f;background-size:16px;",r=".%2 a.cke_anchor,.%2 a.cke_anchor_empty,.cke_editable.%2 a[name],.cke_editable.%2 a[data-cke-saved-name]{"+n+"padding-%1:18px;cursor:auto;}.%2 img.cke_anchor{"+n+"width:16px;min-height:15px;height:1.15em;vertical-align:text-bottom;}";CKEDITOR.addCss(t("ltr")+t("rtl"))},init:function(n){var t="a[!href]";CKEDITOR.dialog.isTabEnabled(n,"link","advanced")&&(t=t.replace("]",",accesskey,charset,dir,id,lang,name,rel,tabindex,title,type]{*}(*)"));CKEDITOR.dialog.isTabEnabled(n,"link","target")&&(t=t.replace("]",",target,onclick]"));n.addCommand("link",new CKEDITOR.dialogCommand("link",{allowedContent:t,requiredContent:"a[href]"}));n.addCommand("anchor",new CKEDITOR.dialogCommand("anchor",{allowedContent:"a[!name,id]",requiredContent:"a[name]"}));n.addCommand("unlink",new CKEDITOR.unlinkCommand);n.addCommand("removeAnchor",new CKEDITOR.removeAnchorCommand);n.setKeystroke(CKEDITOR.CTRL+76,"link");n.ui.addButton&&(n.ui.addButton("Link",{label:n.lang.link.toolbar,command:"link",toolbar:"links,10"}),n.ui.addButton("Unlink",{label:n.lang.link.unlink,command:"unlink",toolbar:"links,20"}),n.ui.addButton("Anchor",{label:n.lang.link.anchor.toolbar,command:"anchor",toolbar:"links,30"}));CKEDITOR.dialog.add("link",this.path+"dialogs/link.js");CKEDITOR.dialog.add("anchor",this.path+"dialogs/anchor.js");n.on("doubleclick",function(t){var i=CKEDITOR.plugins.link.getSelectedLink(n)||t.data.element;i.isReadOnly()||(i.is("a")?(t.data.dialog=i.getAttribute("name")&&(!i.getAttribute("href")||!i.getChildCount())?"anchor":"link",t.data.link=i):CKEDITOR.plugins.link.tryRestoreFakeAnchor(n,i)&&(t.data.dialog="anchor"))},null,null,0);n.on("doubleclick",function(t){t.data.link&&n.getSelection().selectElement(t.data.link)},null,null,20);n.addMenuItems&&n.addMenuItems({anchor:{label:n.lang.link.anchor.menu,command:"anchor",group:"anchor",order:1},removeAnchor:{label:n.lang.link.anchor.remove,command:"removeAnchor",group:"anchor",order:5},link:{label:n.lang.link.menu,command:"link",group:"link",order:1},unlink:{label:n.lang.link.unlink,command:"unlink",group:"link",order:5}});n.contextMenu&&n.contextMenu.addListener(function(t){var i,r;return!t||t.isReadOnly()?null:(i=CKEDITOR.plugins.link.tryRestoreFakeAnchor(n,t),!i&&!(i=CKEDITOR.plugins.link.getSelectedLink(n)))?null:(r={},i.getAttribute("href")&&i.getChildCount()&&!/(?:[^\/])\/(([^\u0000-\u007F]|[\w-])+\.(?!(?:htm|php|asp|jsp|cfm|pl|cgi))\w+)$/.test(i.getAttribute("href"))&&(r={link:CKEDITOR.TRISTATE_OFF,unlink:CKEDITOR.TRISTATE_OFF}),i&&i.hasAttribute("name")&&(r.anchor=r.removeAnchor=CKEDITOR.TRISTATE_OFF),r)});this.compiledProtectionFunction=w(n)},afterInit:function(n){n.dataProcessor.dataFilter.addRules({elements:{a:function(t){return t.attributes.name?t.children.length?null:n.createFakeParserElement(t,"cke_anchor","anchor"):null}}});var t=n._.elementsPath&&n._.elementsPath.filters;t&&t.push(function(t,i){if(i=="a"&&(CKEDITOR.plugins.link.tryRestoreFakeAnchor(n,t)||t.getAttribute("name")&&(!t.getAttribute("href")||!t.getChildCount())))return"anchor"})}});var r=/^javascript:/,u=/^mailto:([^?]+)(?:\?(.+))?$/,f=/subject=([^;?:@&=$,\/]*)/,e=/body=([^;?:@&=$,\/]*)/,o=/^#(.*)$/,s=/^((?:http|https|ftp|news):\/\/)?(.*)$/,h=/^(_(?:self|top|parent|blank))$/,c=/^javascript:void\(location\.href='mailto:'\+String\.fromCharCode\(([^)]+)\)(?:\+'(.*)')?\)$/,l=/^javascript:([^(]+)\(([^)]+)\)$/,a=/\s*window.open\(\s*this\.href\s*,\s*(?:'([^']*)'|null)\s*,\s*'([^']*)'\s*\)\s*;\s*return\s*false;*\s*/,v=/(?:^|,)([^=]+)=(\d+|yes|no)/gi,n={id:"advId",dir:"advLangDir",accessKey:"advAccessKey",name:"advName",lang:"advLangCode",tabindex:"advTabIndex",title:"advTitle",type:"advContentType","class":"advCSSClasses",charset:"advCharset",style:"advStyles",rel:"advRel"};CKEDITOR.plugins.link={getSelectedLink:function(n){var r=n.getSelection(),i=r.getSelectedElement(),t;return i&&i.is("a")?i:(t=r.getRanges()[0],t)?(t.shrink(CKEDITOR.SHRINK_TEXT),n.elementPath(t.getCommonAncestor()).contains("a",1)):null},getEditorAnchors:function(n){for(var u=n.editable(),f=u.isInline()&&!n.plugins.divarea?n.document:u,e=f.getElementsByTag("a"),o=f.getElementsByTag("img"),i=[],r=0,t;t=e.getItem(r++);)(t.data("cke-saved-name")||t.hasAttribute("name"))&&i.push({name:t.data("cke-saved-name")||t.getAttribute("name"),id:t.getAttribute("id")});for(r=0;t=o.getItem(r++);)(t=this.tryRestoreFakeAnchor(n,t))&&i.push({name:t.getAttribute("name"),id:t.getAttribute("id")});return i},fakeAnchor:!0,tryRestoreFakeAnchor:function(n,t){if(t&&t.data("cke-real-element-type")&&t.data("cke-real-element-type")=="anchor"){var i=n.restoreRealElement(t);if(i.data("cke-saved-name"))return i}},parseLinkAttributes:function(i,y){var w=y&&(y.data("cke-saved-href")||y.getAttribute("href"))||"",st=i.plugins.link.compiledProtectionFunction,ht=i.config.emailProtection,at,ct,lt,tt,p={},it,rt,g,d,ut,nt,b,k,ft,et,ot;if((at=w.match(r))&&(ht=="encode"?w=w.replace(c,function(n,i,r){return"mailto:"+String.fromCharCode.apply(String,i.split(","))+(r&&t(r))}):ht&&w.replace(l,function(n,i,r){var u;if(i==st.name){p.type="email";var f=p.email={},h=/(^')|('$)/g,e=r.match(/[^,\s]+/g),c=e.length,o,s;for(u=0;u<c;u++)s=decodeURIComponent(t(e[u].replace(h,""))),o=st.params[u].toLowerCase(),f[o]=s;f.address=[f.name,f.domain].join("@")}})),p.type||((lt=w.match(o))?(p.type="anchor",p.anchor={},p.anchor.name=p.anchor.id=lt[1]):(ct=w.match(u))?(it=w.match(f),rt=w.match(e),p.type="email",g=p.email={},g.address=ct[1],it&&(g.subject=decodeURIComponent(it[1])),rt&&(g.body=decodeURIComponent(rt[1]))):w&&(tt=w.match(s))&&(p.type="url",p.url={},p.url.protocol=tt[1],p.url.url=tt[2])),y){if(d=y.getAttribute("target"),d)p.target={type:d.match(h)?d:"frame",name:d};else if(ut=y.data("cke-pa-onclick")||y.getAttribute("onclick"),nt=ut&&ut.match(a),nt)for(p.target={type:"popup",name:nt[1]};b=v.exec(nt[2]);)b[2]!="yes"&&b[2]!="1"||b[1]in{height:1,width:1,top:1,left:1}?isFinite(b[2])&&(p.target[b[1]]=b[2]):p.target[b[1]]=!0;k={};for(ft in n)et=y.getAttribute(ft),et&&(k[n[ft]]=et);ot=y.data("cke-saved-name")||k.advName;ot&&(k.advName=ot);CKEDITOR.tools.isEmpty(k)||(p.advanced=k)}return p},getLinkAttributes:function(t,r){var d=t.config.emailProtection||"",u={},g,c,nt,tt,e,l,o,v,h,w,b,k,ft;switch(r.type){case"url":g=r.url&&r.url.protocol!=undefined?r.url.protocol:"http://";c=r.url&&CKEDITOR.tools.trim(r.url.url)||"";u["data-cke-saved-href"]=c.indexOf("/")===0?c:g+c;break;case"anchor":nt=r.anchor&&r.anchor.name;tt=r.anchor&&r.anchor.id;u["data-cke-saved-href"]="#"+(nt||tt||"");break;case"email":e=r.email;l=e.address;switch(d){case"":case"encode":var it=encodeURIComponent(e.subject||""),rt=encodeURIComponent(e.body||""),f=[];it&&f.push("subject="+it);rt&&f.push("body="+rt);f=f.length?"?"+f.join("&"):"";d=="encode"?(o=["javascript:void(location.href='mailto:'+",y(l)],f&&o.push("+'",i(f),"'"),o.push(")")):o=["mailto:",l,f];break;default:v=l.split("@",2);e.name=v[0];e.domain=v[1];o=["javascript:",p(t,e)]}u["data-cke-saved-href"]=o.join("")}if(r.target)if(r.target.type=="popup"){var ut=["window.open(this.href, '",r.target.name||"","', '"],s=["resizable","status","location","toolbar","menubar","fullscreen","scrollbars","dependent"],et=s.length,a=function(n){r.target[n]&&s.push(n+"="+r.target[n])};for(h=0;h<et;h++)s[h]=s[h]+(r.target[s[h]]?"=yes":"=no");a("width");a("left");a("height");a("top");ut.push(s.join(","),"'); return false;");u["data-cke-pa-onclick"]=ut.join("")}else r.target.type!="notSet"&&r.target.name&&(u.target=r.target.name);if(r.advanced){for(w in n)b=r.advanced[n[w]],b&&(u[w]=b);u.name&&(u["data-cke-saved-name"]=u.name)}u["data-cke-saved-href"]&&(u.href=u["data-cke-saved-href"]);k=CKEDITOR.tools.extend({target:1,onclick:1,"data-cke-pa-onclick":1,"data-cke-saved-name":1},n);for(ft in u)delete k[ft];return{set:u,removed:CKEDITOR.tools.objectKeys(k)}}};CKEDITOR.unlinkCommand=function(){};CKEDITOR.unlinkCommand.prototype={exec:function(n){var t=new CKEDITOR.style({element:"a",type:CKEDITOR.STYLE_INLINE,alwaysRemoveElement:1});n.removeStyle(t)},refresh:function(n,t){var i=t.lastElement&&t.lastElement.getAscendant("a",!0);i&&i.getName()=="a"&&i.getAttribute("href")&&i.getChildCount()?this.setState(CKEDITOR.TRISTATE_OFF):this.setState(CKEDITOR.TRISTATE_DISABLED)},contextSensitive:1,startDisabled:1,requiredContent:"a[href]"};CKEDITOR.removeAnchorCommand=function(){};CKEDITOR.removeAnchorCommand.prototype={exec:function(n){var i=n.getSelection(),r=i.createBookmarks(),t;i&&(t=i.getSelectedElement())&&(t.getChildCount()?t.is("a"):CKEDITOR.plugins.link.tryRestoreFakeAnchor(n,t))?t.remove(1):(t=CKEDITOR.plugins.link.getSelectedLink(n))&&(t.hasAttribute("href")?(t.removeAttributes({name:1,"data-cke-saved-name":1}),t.removeClass("cke_anchor")):t.remove(1));i.selectBookmarks(r)},requiredContent:"a[name]"};CKEDITOR.tools.extend(CKEDITOR.config,{linkShowAdvancedTab:!0,linkShowTargetTab:!0})})()