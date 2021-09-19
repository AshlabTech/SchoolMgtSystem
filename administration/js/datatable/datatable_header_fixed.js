/*!
   Copyright 2009-2021 SpryMedia Ltd.

 This source file is free software, available under the following license:
   MIT license - http://datatables.net/license/mit

 This source file is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 or FITNESS FOR A PARTICULAR PURPOSE. See the license files for details.

 For details please refer to: http://www.datatables.net
 FixedHeader 3.1.9
 ©2009-2021 SpryMedia Ltd - datatables.net/license
*/
(function(e){"function"===typeof define&&define.amd?define(["jquery","datatables.net"],function(g){return e(g,window,document)}):"object"===typeof exports?module.exports=function(g,h){g||(g=window);h&&h.fn.dataTable||(h=require("datatables.net")(g,h).$);return e(h,g,g.document)}:e(jQuery,window,document)})(function(e,g,h,p){var k=e.fn.dataTable,r=0,l=function(a,b){if(!(this instanceof l))throw"FixedHeader must be initialised with the 'new' keyword.";!0===b&&(b={});a=new k.Api(a);this.c=e.extend(!0,
    {},l.defaults,b);this.s={dt:a,position:{theadTop:0,tbodyTop:0,tfootTop:0,tfootBottom:0,width:0,left:0,tfootHeight:0,theadHeight:0,windowHeight:e(g).height(),visible:!0},headerMode:null,footerMode:null,autoWidth:a.settings()[0].oFeatures.bAutoWidth,namespace:".dtfc"+r++,scrollLeft:{header:-1,footer:-1},enable:!0};this.dom={floatingHeader:null,thead:e(a.table().header()),tbody:e(a.table().body()),tfoot:e(a.table().footer()),header:{host:null,floating:null,placeholder:null},footer:{host:null,floating:null,
    placeholder:null}};this.dom.header.host=this.dom.thead.parent();this.dom.footer.host=this.dom.tfoot.parent();a=a.settings()[0];if(a._fixedHeader)throw"FixedHeader already initialised on table "+a.nTable.id;a._fixedHeader=this;this._constructor()};e.extend(l.prototype,{destroy:function(){this.s.dt.off(".dtfc");e(g).off(this.s.namespace);this.c.header&&this._modeChange("in-place","header",!0);this.c.footer&&this.dom.tfoot.length&&this._modeChange("in-place","footer",!0)},enable:function(a,b){this.s.enable=
    a;if(b||b===p)this._positions(),this._scroll(!0)},enabled:function(){return this.s.enable},headerOffset:function(a){a!==p&&(this.c.headerOffset=a,this.update());return this.c.headerOffset},footerOffset:function(a){a!==p&&(this.c.footerOffset=a,this.update());return this.c.footerOffset},update:function(){var a=this.s.dt.table().node();e(a).is(":visible")?this.enable(!0,!1):this.enable(!1,!1);this._positions();this._scroll(!0)},_constructor:function(){var a=this,b=this.s.dt;e(g).on("scroll"+this.s.namespace,
    function(){a._scroll()}).on("resize"+this.s.namespace,k.util.throttle(function(){a.s.position.windowHeight=e(g).height();a.update()},50));var d=e(".fh-fixedHeader");!this.c.headerOffset&&d.length&&(this.c.headerOffset=d.outerHeight());d=e(".fh-fixedFooter");!this.c.footerOffset&&d.length&&(this.c.footerOffset=d.outerHeight());b.on("column-reorder.dt.dtfc column-visibility.dt.dtfc draw.dt.dtfc column-sizing.dt.dtfc responsive-display.dt.dtfc",function(){a.update()});b.on("destroy.dtfc",function(){a.destroy()});
    this._positions();this._scroll()},_clone:function(a,b){var d=this.s.dt,c=this.dom[a],f="header"===a?this.dom.thead:this.dom.tfoot;!b&&c.floating?c.floating.removeClass("fixedHeader-floating fixedHeader-locked"):(c.floating&&(c.placeholder.remove(),this._unsize(a),c.floating.children().detach(),c.floating.remove()),c.floating=e(d.table().node().cloneNode(!1)).css("table-layout","fixed").attr("aria-hidden","true").removeAttr("id").append(f).appendTo("body"),c.placeholder=f.clone(!1),c.placeholder.find("*[id]").removeAttr("id"),
    c.host.prepend(c.placeholder),this._matchWidths(c.placeholder,c.floating))},_matchWidths:function(a,b){var d=function(n){return e(n,a).map(function(){return 1*e(this).css("width").replace(/[^\d\.]/g,"")}).toArray()},c=function(n,q){e(n,b).each(function(m){e(this).css({width:q[m],minWidth:q[m]})})},f=d("th");d=d("td");c("th",f);c("td",d)},_unsize:function(a){var b=this.dom[a].floating;b&&("footer"===a||"header"===a&&!this.s.autoWidth)?e("th, td",b).css({width:"",minWidth:""}):b&&"header"===a&&e("th, td",
    b).css("min-width","")},_horizontal:function(a,b){var d=this.dom[a],c=this.s.position,f=this.s.scrollLeft;d.floating&&f[a]!==b&&(d.floating.css("left",c.left-b),f[a]=b)},_modeChange:function(a,b,d){var c=this.dom[b],f=this.s.position,n=function(t){c.floating.attr("style",function(v,u){return(u||"")+"width: "+t+"px !important;"})},q=this.dom["footer"===b?"tfoot":"thead"],m=e.contains(q[0],h.activeElement)?h.activeElement:null;m&&m.blur();"in-place"===a?(c.placeholder&&(c.placeholder.remove(),c.placeholder=
    null),this._unsize(b),"header"===b?c.host.prepend(q):c.host.append(q),c.floating&&(c.floating.remove(),c.floating=null)):"in"===a?(this._clone(b,d),c.floating.addClass("fixedHeader-floating").css("header"===b?"top":"bottom",this.c[b+"Offset"]).css("left",f.left+"px"),n(f.width),"footer"===b&&c.floating.css("top","")):"below"===a?(this._clone(b,d),c.floating.addClass("fixedHeader-locked").css("top",f.tfootTop-f.theadHeight).css("left",f.left+"px"),n(f.width)):"above"===a&&(this._clone(b,d),c.floating.addClass("fixedHeader-locked").css("top",
    f.tbodyTop).css("left",f.left+"px"),n(f.width));m&&m!==h.activeElement&&setTimeout(function(){m.focus()},10);this.s.scrollLeft.header=-1;this.s.scrollLeft.footer=-1;this.s[b+"Mode"]=a},_positions:function(){var a=this.s.dt.table(),b=this.s.position,d=this.dom;a=e(a.node());var c=a.children("thead"),f=a.children("tfoot");d=d.tbody;b.visible=a.is(":visible");b.width=a.outerWidth();b.left=a.offset().left;b.theadTop=c.offset().top;b.tbodyTop=d.offset().top;b.tbodyHeight=d.outerHeight();b.theadHeight=
    b.tbodyTop-b.theadTop;f.length?(b.tfootTop=f.offset().top,b.tfootBottom=b.tfootTop+f.outerHeight(),b.tfootHeight=b.tfootBottom-b.tfootTop):(b.tfootTop=b.tbodyTop+d.outerHeight(),b.tfootBottom=b.tfootTop,b.tfootHeight=b.tfootTop)},_scroll:function(a){var b=e(h).scrollTop(),d=e(h).scrollLeft(),c=this.s.position;if(this.c.header){var f=this.s.enable?!c.visible||b<=c.theadTop-this.c.headerOffset?"in-place":b<=c.tfootTop-c.theadHeight-this.c.headerOffset?"in":"below":"in-place";(a||f!==this.s.headerMode)&&
    this._modeChange(f,"header",a);this._horizontal("header",d)}this.c.footer&&this.dom.tfoot.length&&(b=this.s.enable?!c.visible||b+c.windowHeight>=c.tfootBottom+this.c.footerOffset?"in-place":c.windowHeight+b>c.tbodyTop+c.tfootHeight+this.c.footerOffset?"in":"above":"in-place",(a||b!==this.s.footerMode)&&this._modeChange(b,"footer",a),this._horizontal("footer",d))}});l.version="3.1.9";l.defaults={header:!0,footer:!1,headerOffset:0,footerOffset:0};e.fn.dataTable.FixedHeader=l;e.fn.DataTable.FixedHeader=
    l;e(h).on("init.dt.dtfh",function(a,b,d){"dt"===a.namespace&&(a=b.oInit.fixedHeader,d=k.defaults.fixedHeader,!a&&!d||b._fixedHeader||(d=e.extend({},d,a),!1!==a&&new l(b,d)))});k.Api.register("fixedHeader()",function(){});k.Api.register("fixedHeader.adjust()",function(){return this.iterator("table",function(a){(a=a._fixedHeader)&&a.update()})});k.Api.register("fixedHeader.enable()",function(a){return this.iterator("table",function(b){b=b._fixedHeader;a=a!==p?a:!0;b&&a!==b.enabled()&&b.enable(a)})});
    k.Api.register("fixedHeader.enabled()",function(){if(this.context.length){var a=this.context[0]._fixedHeader;if(a)return a.enabled()}return!1});k.Api.register("fixedHeader.disable()",function(){return this.iterator("table",function(a){(a=a._fixedHeader)&&a.enabled()&&a.enable(!1)})});e.each(["header","footer"],function(a,b){k.Api.register("fixedHeader."+b+"Offset()",function(d){var c=this.context;return d===p?c.length&&c[0]._fixedHeader?c[0]._fixedHeader[b+"Offset"]():p:this.iterator("table",function(f){if(f=
    f._fixedHeader)f[b+"Offset"](d)})})});return l});