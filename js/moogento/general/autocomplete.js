"use strict";var ConditionOperator;!function(t){t[t.AND=0]="AND",t[t.OR=1]="OR"}(ConditionOperator||(ConditionOperator={}));var AutoComplete=function(){function t(e,o){if(void 0===e&&(e={}),void 0===o&&(o="[data-autocomplete]"),Array.isArray(o))o.forEach(function(o){new t(e,o)});else if("string"==typeof o){var s=document.querySelectorAll(o);Array.prototype.forEach.call(s,function(o){new t(e,o)})}else t.prototype.create(t.merge(t.defaults,e,{DOMResults:document.createElement("div")}),o)}return t.prototype.create=function(e,o){if(e.Input=o,e.Input.nodeName.match(/^INPUT$/i)&&(e.Input.hasAttribute("type")===!1||e.Input.getAttribute("type").match(/^TEXT|SEARCH$/i))){e.Input.setAttribute("autocomplete","off"),e._Position(e),e.Input.parentNode.appendChild(e.DOMResults),e.$Listeners.focus=e._Focus.bind(e),e.$Listeners.keydown=t.prototype.event.bind(null,e),e.$Listeners.blur=e._Blur.bind(e),e.$Listeners.position=e._Position.bind(e),e.$Listeners.destroy=t.prototype.destroy.bind(null,e);for(var s in e.$Listeners)e.Input.addEventListener(s,e.$Listeners[s])}},t.prototype.event=function(e,o){for(var s in e.KeyboardMappings){var i=t.merge({Operator:ConditionOperator.AND},e.KeyboardMappings[s]),r=ConditionOperator.AND==i.Operator;i.Conditions.forEach(function(e){(r===!0&&i.Operator==ConditionOperator.AND||r===!1&&ConditionOperator.OR)&&(e=t.merge({Not:!1},e),e.hasOwnProperty("Is")?r=e.Is==o.keyCode?!e.Not:e.Not:e.hasOwnProperty("From")&&e.hasOwnProperty("To")&&(r=o.keyCode>=e.From&&o.keyCode<=e.To?!e.Not:e.Not))}),r===!0&&i.Callback.bind(e,o)()}},t.prototype.ajax=function(e,o,s){if(void 0===s&&(s=!0),e.$AjaxTimer&&window.clearTimeout(e.$AjaxTimer),s===!0)e.$AjaxTimer=window.setTimeout(t.prototype.ajax.bind(null,e,o,!1),e.Delay);else{e.Request&&e.Request.abort();var i=Object.getOwnPropertyNames(e.HttpHeaders),r=e._HttpMethod(),n=e._Url(),a=e.QueryArg+"="+e._Pre();r.match(/^GET$/i)&&(n+="?"+a),e.Request=new XMLHttpRequest,e.Request.open(r,n,!0);for(var u=i.length-1;u>=0;u--)e.Request.setRequestHeader(i[u],e.HttpHeaders[i[u]]);e.Request.onreadystatechange=o,e.Request.send(a)}},t.prototype.destroy=function(t){for(var e in t.$Listeners)t.Input.removeEventListener(e,t.$Listeners[e]);t.DOMResults.parentNode.removeChild(t.DOMResults)},t.merge=function(){for(var t,e={},o=0;o<arguments.length;o++)for(t in arguments[o])e[t]=arguments[o][t];return e},t.defaults={Delay:150,EmptyMessage:"No result here",HttpHeaders:{"Content-type":"application/x-www-form-urlencoded"},Limit:0,HttpMethod:"GET",QueryArg:"q",Url:null,KeyboardMappings:{Enter:{Conditions:[{Is:13,Not:!1}],Callback:function(t){if(-1!=this.DOMResults.getAttribute("class").indexOf("open")){var e=this.DOMResults.querySelector("li.active");null!==e&&(this._Select(e),this.DOMResults.setAttribute("class","autocomplete")),t.preventDefault()}},Operator:ConditionOperator.AND},KeyUpAndDown:{Conditions:[{Is:38,Not:!1},{Is:40,Not:!1}],Callback:function(t){var e=this.DOMResults.querySelector("li:first-child:not(.locked)"),o=this.DOMResults.querySelector("li.active");if(o){var s=Array.prototype.indexOf.call(o.parentNode.children,o),i=s+(t.keyCode-39),r=this.DOMResults.getElementsByTagName("li").length;0>i?i=r-1:i>=r&&(i=0),o.setAttribute("class",""),o.parentElement.childNodes.item(i).setAttribute("class","active")}else e&&e.setAttribute("class","active")},Operator:ConditionOperator.OR},AlphaNum:{Conditions:[{Is:13,Not:!0},{From:35,To:40,Not:!0}],Callback:function(e){var o=this.Input.getAttribute("data-autocomplete-old-value"),s=this._Pre();""!==s&&(o&&s==o||this.DOMResults.setAttribute("class","autocomplete open"),t.prototype.ajax(this,function(){4==this.Request.readyState&&200==this.Request.status&&(this._Render(this._Post(this.Request.response)),this._Open())}.bind(this)))},Operator:ConditionOperator.AND}},DOMResults:null,Request:null,Input:null,_EmptyMessage:function(){var t="";return t=this.Input.hasAttribute("data-autocomplete-empty-message")?this.Input.getAttribute("data-autocomplete-empty-message"):this.EmptyMessage,t===!1&&(t=""),t},_Limit:function(){var t=this.Input.getAttribute("data-autocomplete-limit");return isNaN(t)?this.Limit:parseInt(t)},_HttpMethod:function(){return this.Input.hasAttribute("data-autocomplete-method")?this.Input.getAttribute("data-autocomplete-method"):this.HttpMethod},_QueryArg:function(){return this.Input.hasAttribute("data-autocomplete-param-name")?this.Input.getAttribute("data-autocomplete-param-name"):this.QueryArg},_Url:function(){return this.Input.hasAttribute("data-autocomplete")?this.Input.getAttribute("data-autocomplete"):this.Url},_Blur:function(t){if(void 0===t&&(t=!1),t)this.DOMResults.setAttribute("class","autocomplete");else{var e=this;setTimeout(function(){e._Blur(!0)},150)}},_Focus:function(){var t=this.Input.getAttribute("data-autocomplete-old-value");t&&this.Input.value==t||this.DOMResults.setAttribute("class","autocomplete open")},_Open:function(){var t=this;Array.prototype.forEach.call(this.DOMResults.getElementsByTagName("li"),function(e){"locked"!=e.getAttribute("class")&&(e.onclick=function(o){t._Select(e)})})},_Position:function(){this.DOMResults.setAttribute("class","autocomplete"),this.DOMResults.setAttribute("style","top:"+(this.Input.offsetTop+this.Input.offsetHeight)+"px;left:"+this.Input.offsetLeft+"px;width:"+this.Input.clientWidth+"px;")},_Render:function(t){var e=document.createElement("ul"),o=document.createElement("li");if("string"==typeof t)if(t.length>0)this.DOMResults.innerHTML=t;else{var s=this._EmptyMessage();""!==s&&(o.innerHTML=s,o.setAttribute("class","locked"),e.appendChild(o))}else{this._Limit()<0&&(t=t.reverse());for(var i=0;i<t.length;i++)o.innerHTML=t[i].Label,o.setAttribute("data-autocomplete-value",t[i].Value),e.appendChild(o),o=document.createElement("li")}this.DOMResults.hasChildNodes()&&this.DOMResults.childNodes[0].remove(),this.DOMResults.appendChild(e)},_Post:function(t){try{var e=[],o=JSON.parse(t);if(0==Object.keys(o).length)return"";if(Array.isArray(o))for(var s=0;s<Object.keys(o).length;s++)e[e.length]={Value:o[s],Label:o[s]};else for(var i in o)e.push({Value:i,Label:o[i]});return e}catch(r){return t}},_Pre:function(){return this.Input.value},_Select:function(t){t.hasAttribute("data-autocomplete-value")?this.Input.value=t.getAttribute("data-autocomplete-value"):this.Input.value=t.innerHTML,this.Input.setAttribute("data-autocomplete-old-value",this.Input.value)},$AjaxTimer:null,$Listeners:{}},t}();