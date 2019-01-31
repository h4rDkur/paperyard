!function(e){e.fn.waitMe=function(n){return this.each(function(){var a,t,r,o,i,d,l=e(this),s="waitMe",c=!1,f="background-color",g="",u="",p={init:function(){switch(i=e.extend({effect:"bounce",text:"",bg:"rgba(255,255,255,0.7)",color:"#000",maxSize:"",source:"",onClose:function(){}},n),d=(new Date).getMilliseconds(),o=e('<div class="'+s+'" data-waitme_id="'+d+'"></div>'),i.effect){case"none":r=0;break;case"bounce":r=3;break;case"rotateplane":r=1;break;case"stretch":r=5;break;case"orbit":r=2;break;case"roundBounce":r=12;break;case"win8":case"win8_linear":r=5,c=!0;break;case"ios":r=12;break;case"facebook":r=3;break;case"rotation":r=1,f="border-color";break;case"timer":if(r=2,e.isArray(i.color))var p=i.color[0];else p=i.color;g="border-color:"+p;break;case"pulse":r=1,f="border-color";break;case"progressBar":r=1;break;case"bouncePulse":r=3;break;case"img":r=1}if(""!==g&&(g+=";"),r>0){if("img"===i.effect)u='<img src="'+i.source+'">';else for(var v=1;v<=r;++v){if(e.isArray(i.color))null==(p=i.color[v])&&(p="#000");else p=i.color;u+=c?'<div class="'+s+"_progress_elem"+v+'"><div style="'+f+":"+p+'"></div></div>':'<div class="'+s+"_progress_elem"+v+'" style="'+f+":"+p+'"></div>'}t=e('<div class="'+s+"_progress "+i.effect+'" style="'+g+'">'+u+"</div>")}if(i.text&&""===i.maxSize){if(e.isArray(i.color))p=i.color[0];else p=i.color;a=e('<div class="'+s+'_text" style="color:'+p+'">'+i.text+"</div>")}var m=l.find("> ."+s);m&&m.remove();var b=e('<div class="'+s+'_content"></div>');b.append(t,a),o.append(b),"HTML"==l[0].tagName&&(l=e("body")),l.addClass(s+"_container").attr("data-waitme_id",d).append(o),m=l.find("> ."+s);var y=l.find("."+s+"_content");if(m.css({background:i.bg}),y.css({marginTop:-y.outerHeight()/2+"px"}),""!==i.maxSize){var h=t.outerHeight(),w=(t.outerWidth(),h);"img"===i.effect?(t.css({height:i.maxSize+"px"}),t.find(">img").css({maxHeight:"100%"}),y.css({marginTop:-y.outerHeight()/2+"px"})):i.maxSize<w&&b.css({transform:"scale("+i.maxSize/w+")"})}function D(e){y.css({top:"auto",transform:"translateY("+e+"px) translateZ(0)"})}if(l.outerHeight()>e(window).height()){var T=e(window).scrollTop(),x=y.outerHeight(),k=l.offset().top,_=l.outerHeight(),C=T-k+e(window).height()/2;C<0&&(C=Math.abs(C)),C-x>=0&&C+x<=_?(k-T>e(window).height()/2&&(C=x),D(C)):D(C=T>k+_-x?T-k-x:T-k+x),e(document).scroll(function(){var n=e(window).scrollTop()-k+e(window).height()/2;n-x>=0&&n+x<=_&&D(n)})}return m.on("destroyed",function(){i.onClose&&e.isFunction(i.onClose)&&i.onClose(),m.trigger("close")}),e.event.special.destroyed={remove:function(e){e.handler&&e.handler()}},m},hide:function(){var e;e=l.attr("data-waitme_id"),l.removeClass(s+"_container").removeAttr("data-waitme_id"),l.find("."+s+'[data-waitme_id="'+e+'"]').remove()}};return p[n]?p[n].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof n&&n?void 0:p.init.apply(this,arguments)})},e(window).on("load",function(){e("body.waitMe_body").addClass("hideMe"),setTimeout(function(){e("body.waitMe_body").find(".waitMe_container:not([data-waitme_id])").remove(),e("body.waitMe_body").removeClass("waitMe_body hideMe")},200)})}(jQuery),function(e){function n(e,n){return"all"==n?e:e.filter(function(e){return-1!=n.toLowerCase().indexOf(e)})}var a="application/x-dnd",t="application/json",r="Text",o=["move","copy","link"];e.directive("dndDraggable",["$parse","$timeout",function(e,d){return function(l,s,c){s.attr("draggable","true"),c.dndDisableIf&&l.$watch(c.dndDisableIf,function(e){s.attr("draggable",!e)}),s.on("dragstart",function(f){if(f=f.originalEvent||f,"false"==s.attr("draggable"))return!0;i.isDragging=!0,i.itemType=c.dndType&&l.$eval(c.dndType).toLowerCase(),i.dropEffect="none",i.effectAllowed=c.dndEffectAllowed||o[0],f.dataTransfer.effectAllowed=i.effectAllowed;var g=l.$eval(c.dndDraggable),u=a+(i.itemType?"-"+i.itemType:"");try{f.dataTransfer.setData(u,angular.toJson(g))}catch(e){var p=angular.toJson({item:g,type:i.itemType});try{f.dataTransfer.setData(t,p)}catch(e){var v=n(o,i.effectAllowed);f.dataTransfer.effectAllowed=v[0],f.dataTransfer.setData(r,p)}}if(s.addClass("dndDragging"),d(function(){s.addClass("dndDraggingSource")},0),f._dndHandle&&f.dataTransfer.setDragImage&&f.dataTransfer.setDragImage(s[0],0,0),e(c.dndDragstart)(l,{event:f}),c.dndCallback){var m=e(c.dndCallback);i.callback=function(e){return m(l,e||{})}}f.stopPropagation()}),s.on("dragend",function(n){n=n.originalEvent||n,l.$apply(function(){var a=i.dropEffect;e(c[{copy:"dndCopied",link:"dndLinked",move:"dndMoved",none:"dndCanceled"}[a]])(l,{event:n}),e(c.dndDragend)(l,{event:n,dropEffect:a})}),i.isDragging=!1,i.callback=void 0,s.removeClass("dndDragging"),s.removeClass("dndDraggingSource"),n.stopPropagation(),d(function(){s.removeClass("dndDraggingSource")},0)}),s.on("click",function(n){c.dndSelected&&(n=n.originalEvent||n,l.$apply(function(){e(c.dndSelected)(l,{event:n})}),n.stopPropagation())}),s.on("selectstart",function(){this.dragDrop&&this.dragDrop()})}}]),e.directive("dndList",["$parse",function(e){return function(d,l,s){function c(e){if(!e)return r;for(var n=0;n<e.length;n++)if(e[n]==r||e[n]==t||e[n].substr(0,a.length)==a)return e[n];return null}function f(e){return i.isDragging?i.itemType||void 0:e==r||e==t?null:e&&e.substr(a.length+1)||void 0}function g(e){return!w.disabled&&(!(!w.externalSources&&!i.isDragging)&&(!w.allowedTypes||null===e||e&&-1!=w.allowedTypes.indexOf(e)))}function u(e,a){var t=o;return a||(t=n(t,e.dataTransfer.effectAllowed)),i.isDragging&&(t=n(t,i.effectAllowed)),s.dndEffectAllowed&&(t=n(t,s.dndEffectAllowed)),t.length?e.ctrlKey&&-1!=t.indexOf("copy")?"copy":e.altKey&&-1!=t.indexOf("link")?"link":t[0]:"none"}function p(){return b.remove(),l.removeClass("dndDragover"),!0}function v(n,a,t,r,o,l){return e(n)(d,{callback:i.callback,dropEffect:t,event:a,external:!i.isDragging,index:void 0!==o?o:m(),item:l||void 0,type:r})}function m(){return Array.prototype.indexOf.call(h.children,y)}var b=function(){var e;return angular.forEach(l.children(),function(n){var a=angular.element(n);a.hasClass("dndPlaceholder")&&(e=a)}),e||angular.element("<li class='dndPlaceholder'></li>")}();b.remove();var y=b[0],h=l[0],w={};l.on("dragenter",function(e){e=e.originalEvent||e;var n=s.dndAllowedTypes&&d.$eval(s.dndAllowedTypes);w={allowedTypes:angular.isArray(n)&&n.join("|").toLowerCase().split("|"),disabled:s.dndDisableIf&&d.$eval(s.dndDisableIf),externalSources:s.dndExternalSources&&d.$eval(s.dndExternalSources),horizontal:s.dndHorizontalList&&d.$eval(s.dndHorizontalList)};var a=c(e.dataTransfer.types);return!a||!g(f(a))||void e.preventDefault()}),l.on("dragover",function(e){var n=c((e=e.originalEvent||e).dataTransfer.types),a=f(n);if(!n||!g(a))return!0;if(y.parentNode!=h&&l.append(b),e.target!=h){for(var t=e.target;t.parentNode!=h&&t.parentNode;)t=t.parentNode;if(t.parentNode==h&&t!=y){var o=t.getBoundingClientRect();if(w.horizontal)var i=e.clientX<o.left+o.width/2;else i=e.clientY<o.top+o.height/2;h.insertBefore(y,i?t:t.nextSibling)}}var d=n==r,m=u(e,d);return"none"==m?p():s.dndDragover&&!v(s.dndDragover,e,m,a)?p():(e.preventDefault(),d||(e.dataTransfer.dropEffect=m),l.addClass("dndDragover"),e.stopPropagation(),!1)}),l.on("drop",function(e){var n=c((e=e.originalEvent||e).dataTransfer.types),a=f(n);if(!n||!g(a))return!0;e.preventDefault();try{var o=JSON.parse(e.dataTransfer.getData(n))}catch(e){return p()}if((n==r||n==t)&&(a=o.type||void 0,o=o.item,!g(a)))return p();var l=n==r,b=u(e,l);if("none"==b)return p();var y=m();return s.dndDrop&&!(o=v(s.dndDrop,e,b,a,y,o))?p():(i.dropEffect=b,l||(e.dataTransfer.dropEffect=b),!0!==o&&d.$apply(function(){d.$eval(s.dndList).splice(y,0,o)}),v(s.dndInserted,e,b,a,y,o),p(),e.stopPropagation(),!1)}),l.on("dragleave",function(e){e=e.originalEvent||e;var n=document.elementFromPoint(e.clientX,e.clientY);h.contains(n)&&!e._dndPhShown?e._dndPhShown=!0:p()})}}]),e.directive("dndNodrag",function(){return function(e,n,a){n.attr("draggable","true"),n.on("dragstart",function(e){(e=e.originalEvent||e)._dndHandle||(e.dataTransfer.types&&e.dataTransfer.types.length||e.preventDefault(),e.stopPropagation())}),n.on("dragend",function(e){(e=e.originalEvent||e)._dndHandle||e.stopPropagation()})}}),e.directive("dndHandle",function(){return function(e,n,a){n.attr("draggable","true"),n.on("dragstart dragend",function(e){(e=e.originalEvent||e)._dndHandle=!0})}});var i={}}(angular.module("dndLists",[]));
