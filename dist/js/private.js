(()=>{"use strict";class e{constructor(e){this.options=Object.assign({element:null,effect:"zoom",state:"closed",size:"medium",content:null,footer:null,header:null,title:null},e),null==this.options.element&&(this.options.element=document.createElement("div"),this.options.element.classList.add("modal"),this.options.element.innerHTML='\n                <div class="container">\n                    <div class="header">\n                        <button class="close">&times;</button> \n                    </div>\n                    <div class="content"></div>\n                    <div class="footer">\n                        <button class="close">Close</button>\n                    </div>\n                </div>                        \n            ',document.body.appendChild(this.options.element)),this.options.element.querySelector(".container").classList.remove("zoom","slide"),this.options.element.querySelector(".container").classList.add(this.options.effect),null!=this.options.header&&(this.header=this.options.header),null!=this.options.content&&(this.content=this.options.content),null!=this.options.footer&&(this.footer=this.options.footer),null!=this.options.title&&(this.title=this.options.title),this.size=this.options.size,this._eventHandlers()}open(){this.options.state="open",this.options.element.style.display="flex",this.options.element.getBoundingClientRect(),this.options.element.classList.add("open"),document.body.classList.add("merkai-modal-open"),this.options.element.parentNode!==document.body&&document.body.append(this.options.element),this.options.onOpen&&this.options.onOpen(this)}close(){1==document.querySelectorAll(".modal.open").length&&document.body.classList.remove("merkai-modal-open"),this.options.state="closed",this.options.element.classList.remove("open"),this.options.element.style.display="none",this.options.onClose&&this.options.onClose(this)}get state(){return this.options.state}get effect(){return this.options.effect}set effect(e){this.options.effect=e,this.options.element.querySelector(".container").classList.remove("zoom","slide"),this.options.element.querySelector(".container").classList.add(e)}get size(){return this.options.size}set size(e){this.options.size=e,this.options.element.classList.remove("small","large","medium","full"),this.options.element.classList.add(e)}get content(){return this.options.element.querySelector(".content").innerHTML}get contentElement(){return this.options.element.querySelector(".content")}set content(e){e?(this.options.element.querySelector(".content")||this.options.element.querySelector(".container").insertAdjacentHTML("afterbegin",'<div class="content"></div>'),this.options.element.querySelector(".content").innerHTML=e):this.options.element.querySelector(".content").remove()}get header(){return this.options.element.querySelector(".header").innerHTML}get headerElement(){return this.options.element.querySelector(".header")}set header(e){e?(this.options.element.querySelector(".header")||this.options.element.querySelector(".container").insertAdjacentHTML("afterbegin",'<div class="header"></div>'),this.options.element.querySelector(".header").innerHTML=e):this.options.element.querySelector(".header").remove()}get title(){return this.options.element.querySelector(".header .title")?this.options.element.querySelector(".header .title").innerHTML:null}set title(e){this.options.element.querySelector(".header .title")||this.options.element.querySelector(".header").insertAdjacentHTML("afterbegin",'<h3 class="title"></h3>'),this.options.element.querySelector(".header .title").innerHTML=e}get footer(){return this.options.element.querySelector(".footer").innerHTML}get footerElement(){return this.options.element.querySelector(".footer")}set footer(e){e?(this.options.element.querySelector(".footer")||this.options.element.querySelector(".container").insertAdjacentHTML("beforeend",'<div class="footer"></div>'),this.options.element.querySelector(".footer").innerHTML=e):this.options.element.querySelector(".footer").remove()}_eventHandlers(){this.options.element.querySelectorAll(".close").forEach((e=>{e.onclick=e=>{e.preventDefault(),this.close()}}))}static initElements(){document.querySelectorAll("[data-modal]").forEach((t=>{t.addEventListener("click",(a=>{a.preventDefault();let o=document.querySelector(t.dataset.modal),s=new e({element:o});for(let e in o.dataset)s[e]&&(s[e]=o.dataset[e]);s.open()}))}))}static confirm(t,a,o){let s=new e({content:t,header:"",footer:'<button class="success">OK</button><button class="cancel alt">Cancel</button>'});s.footerElement.querySelector(".success").onclick=e=>{e.preventDefault(),a&&a(),s.close()},s.footerElement.querySelector(".cancel").onclick=e=>{e.preventDefault(),o&&o(),s.close()},s.open()}static alert(t,a){let o=new e({content:t,header:"",footer:'<button class="success">OK</button>'});o.footerElement.querySelector(".success").onclick=e=>{e.preventDefault(),a&&a(),o.close()},o.open()}static prompt(t,a,o,s){let n=new e({header:"",footer:'<button class="success">OK</button><button class="cancel alt">Cancel</button>'});n.content=t+`<div class="prompt-input"><input type="text" value="${a}" placeholder="Enter your text..."></div>`,n.footerElement.querySelector(".success").onclick=e=>{e.preventDefault(),o&&o(n.contentElement.querySelector(".prompt-input input").value),n.close()},n.footerElement.querySelector(".cancel").onclick=e=>{e.preventDefault(),s&&s(n.contentElement.querySelector(".prompt-input input").value),n.close()},n.open()}}var t;(t=jQuery)(document).ready((function(){t(".top-up-variants > a").click((function(){let e=t(this).get(0).id.replace("variant_","");t("#top_up_amount").val(e),function(e,t,a,o){const s=document.getElementById("top_up_amount");document.getElementById("topup_message").innerHTML='Your balance will be topped up by $<span id="replenishAmount">'+a+'</span>, commission is $<span id="commissionAmount">'+o+'</span>. You will get <span id="bonusesCounter">0.1</span> bonuses.',s&&s.setAttribute("data-value",e)}(e)})),t(".toggle-autodeposit").click((function(){t(this).toggleClass("checked"),t(this).hasClass("checked")?t("input#autodeposit").attr("value","1"):t("input#autodeposit").attr("value","0")})),t(".card-var").click((function(){t(".card-variants").toggleClass("clicked"),t(".clicked .card-var").click((function(){t(".card-var").removeClass("current-card"),t(this).addClass("current-card"),t("#source-card").attr("value",t(this).attr("data-pan"))}))}))})),(t=>{let a,o,s,n,i;t.ajax({url:merkai_object.ajaxurl,global:!1,type:"GET",data:{action:"merkai_ajax_get_env_structure"},success:function(e){console.log(e),n=e.response.wallet_fixed_commission,o=e.response.wallet_percentage_commission/100,s=1-o,e.response.structure?(i=e.response.structure.bonus_conversion_rate,a=e.response.structure.rewarding_group.rewarding_rules):(i=1,a=[])},error:function(e){console.error("Ошибка AJAX-запроса:",e),n=0,o=0,s=1,i=1,a=[]}});const r=(e,t,a)=>{let o,s=0,n=1/0,i=-1/0,r=1;return e&&e.forEach((e=>{e.operation_type===a&&t>=e.min_amount&&t<=e.max_amount&&(s+=e.value,o=e.value_type,r=e.conversion_rate,e.min_amount<n&&(n=e.min_amount),e.max_amount>i&&(i=e.max_amount))})),{totalValue:"percentage"===o?s/r:s,minAmount:n,maxAmount:i,value_type:o,conversion_rate:r}},l=(e=>{const t=[];return e?(e.forEach((e=>{let a=t.find((t=>t.operation_type===e.operation_type&&t.min_amount===e.min_amount&&t.max_amount===e.max_amount));a?a.value+=e.value:t.push({...e})})),t):null})(a),c=(e,t,a)=>{const o=r(t,e,a).totalValue;return"percentage"===r(t,e,a).value_type?Math.floor(e*(o/100)):o},u=(e,t,a,o)=>{m(e,a,o)},d=e=>{t("#top_up_amount").val()?(t(e.target).addClass("merkai-disabled"),t(`#${e.target.id} .merkai-spinner`).removeClass("merkai-hidden"),t.ajax({url:merkai_object.ajaxurl,type:"POST",data:{action:"merkai_ajax_top_up","ajax-top-up-nonce":t("#ajax-top-up-nonce").val(),amount:t("#top_up_amount").val(),redirect_url:window.location.href},success:function(e){200===e.response.status_code&&JSON.parse(e.response.response).schemas.url?window.location.replace(JSON.parse(e.response.response).schemas.url):t(".topUpModal .message").html(JSON.parse(e.response.response).schemas.message)}}).error((e=>alert(e.response.response))).always((function(){t(`#${e.target.id} .merkai-spinner`).addClass("merkai-hidden"),t(e.target).removeClass("merkai-disabled")}))):t(".topUpModal .message").html("Please enter amount!")},m=(e,a,o)=>{t.ajax({url:merkai_object.ajaxurl,type:"GET",data:{action:"merkai_ajax_get_structure_calculation",amount:parseFloat(e),operation_type:a,wallet_balance_check:o},success:function(e){if(e.response.error)"payment_operation_add_money"==a?t("#topup_message").html("An error has occurred"):"payment_operation_withdraw"==a?t("#withdraw_message").html("An error has occurred"):"payment_operation_for_services"==a&&t("#merkai_payment_bonuses").html("An error has occurred");else{let o=e.response.operations_data[0].commission_amount,s=e.response.operations_data[0].full_amount,n=e.response.operations_data[0].bonuses_amount;"payment_operation_add_money"==a?(n>0?t("#topup_message").html('Your balance will be replenished by $<span id="replenishAmount">'+s+'</span>, commission is $<span id="commissionAmount">'+o+'</span>. You will get <span id="bonusesCounter">'+n+"</span> bonuses."):t("#topup_message").html('Your balance will be replenished by $<span id="replenishAmount">'+s+'</span>, commission is $<span id="commissionAmount">'+o+"</span>."),t("#topup_message").removeClass("loading")):"payment_operation_withdraw"==a?(t("#withdraw_message").html('You will receive a withdrawal for $<span id="withdrawal_amount">'+s+'</span>, commission is $<span id="withdrawal_commission_amount">'+o+"</span>."),t("#withdraw_message").removeClass("loading")):"payment_operation_for_services"==a&&(t("#merkai_payment_bonuses").html("You will get additional "+n+" bonuses for this purchase."),t("#merkai_payment_bonuses").removeClass("loading"))}}}).error((e=>console.log(e)))},p=(e,a,o)=>t.ajax({url:merkai_object.ajaxurl,type:"GET",data:{action:"merkai_ajax_get_structure_calculation",amount:parseFloat(e),operation_type:a,wallet_balance_check:o}}).error((e=>console.log(e))),h=(e,a)=>{t(e.target).addClass("merkai-disabled"),t(".merkai-profile-actions .merkai-spinner").removeClass("merkai-hidden"),t.ajax({url:merkai_object.ajaxurl,type:"POST",data:{action:"merkai_ajax_set_status","ajax-status-nonce":t("#ajax-status-nonce").val(),status:a},success:()=>t(window.location.reload())}).error((e=>alert(e))).always((()=>t(".merkai-profile-actions .merkai-spinner").addClass("merkai-hidden")))};function _(){t.ajax({url:merkai_object.ajaxurl,type:"POST",data:{action:"merkai_ajax_check_balance"},success:function(e){!function(e,a){const o=t(".merkai-balance-value"),s=t(".merkai-bonus-value");o&&o.html(e),s&&s.html(a)}(e.response.balance,e.response.bonuses)}})}function g(e,t){let a;return function(...o){clearTimeout(a),a=setTimeout((()=>e.apply(this,o)),t)}}t("body").hasClass("logged-in")&&setInterval((()=>_()),1e3);const v=g(((e,a,o)=>{parseFloat(e)+a>o?(t("#topup_message").html("When topping up the amount $"+e+" the balance limit will exceed the set value $"+o),t("#top_up_button").attr("disabled","true").addClass("disabled"),t("#topup_message").removeClass("loading")):e<parseFloat(t("#top_up_amount").attr("min"))?(t("#top_up_button").attr("disabled","true").addClass("disabled"),t("#topup_message").html("Please enter amount more than minimum top up amount."),t("#topup_message").removeClass("loading")):(t("#top_up_button").removeAttr("disabled").removeClass("disabled"),u(e,0,"payment_operation_add_money",!1))}),500),b=g(((e,a,o)=>{parseFloat(e)>a?(t("#withdraw_message").html("Insufficient funds. Please check the wallet balance."),t("#withdraw_button").attr("disabled","true").addClass("disabled"),t("#withdraw_message").removeClass("loading")):e<o?(t("#withdraw_button").attr("disabled","true").addClass("disabled"),t("#withdraw_message").html("Please enter amount more than minimum withdrawal amount."),t("#withdraw_message").removeClass("loading")):(t("#withdraw_button").removeAttr("disabled").removeClass("disabled"),u(e,0,"payment_operation_withdraw",!1))}),500),y=g(((e,a,o)=>{let s,n,i,r,l=t("#merkai_after_discount"),c=t("#merkai_discount"),d=t("#merkai_payment_bonuses");a*o<e?(s=a,n=a*o):(s=e/o,n=e),i=(s*o*100/e).toFixed(2),r=(e-n).toFixed(2),l.html(r),c.html(i),u(r,0,"payment_operation_for_services",!1),0!=r?d.show():d.hide(),r==e?(t(".merkai_before_discount").hide(),t(".merkai_discount").hide()):(t(".merkai_before_discount").show(),t(".merkai_discount").show())}),500);t(document).ready((function(){e.initElements();const a=t("#top_up_button"),r=t("#withdraw_button"),m=t("#suspend_button"),g=t("#reactivate_button"),f=t("#block_button"),k=t("#delete_button");function w(){t(".calculate_merkai_bonuses").each((function(){!async function(e,t){if(e&&t){let a,o=t.data("reward")?t.data("reward"):"payment",s=0;try{if("topup"===o)a=await p(e,"payment_operation_add_money",!1),s=+a.response.operations_data[0].bonuses_amount;else if("sum"===o){a=await p(e,"payment_operation_add_money",!1);const t=await p(e,"payment_operation_for_services",!1);s=+a.response.operations_data[0].bonuses_amount+ +t.response.operations_data[0].bonuses_amount}else a=await p(e,"payment_operation_for_services",!1),s=+a.response.operations_data[0].bonuses_amount;200===a.response.status&&s>0?t.html(s):t.closest(".merkai-bonuses-calculation").remove()}catch(e){t.closest(".merkai-bonuses-calculation").remove(),console.log(e)}}else t.closest(".merkai-bonuses-calculation").remove()}(t(this).data("price"),t(this))}))}a.click((e=>d(e))),r.click((e=>(e=>{if(!t("#withdraw_amount").val())return void t(".withdrawModal .message").html("Please enter amount!");t(e.target).addClass("merkai-disabled"),t(`#${e.target.id} .merkai-spinner`).removeClass("merkai-hidden");const a=parseFloat(t("#withdraw_amount").val());parseFloat(t("#witdrawForm .merkai-balance-value").text())<a?(t("#withdraw_message").text("Sorry, can't do ;)"),t(e.target).removeClass("merkai-disabled"),t(`#${e.target.id} .merkai-spinner`).addClass("merkai-hidden")):t.ajax({url:merkai_object.ajaxurl,type:"POST",data:{action:"merkai_ajax_withdraw","ajax-withdraw-nonce":t("#ajax-withdraw-nonce").val(),amount:parseFloat(t("#withdraw_amount").val())},success:function(e){200===e.response.status_code?(t("#withdraw_amount").val(""),t(".withdrawModal .message").text("Success!"),_(),function(){const e=t("#place_order"),a=t(".payment_box.payment_method_merkai").is(":hidden");e&&!a&&t(document.body).trigger("update_checkout")}(),t(".withdrawModal").delay(1e3).fadeOut("fast"),t("body").removeClass("merkai-modal-open")):t(".withdrawModal .message").text("An error occurred. Please reload page and try again!")}}).error((e=>console.log(e))).always((function(){t(`#${e.target.id} .merkai-spinner`).addClass("merkai-hidden"),t(e.target).removeClass("merkai-disabled")}))})(e))),m.click((e=>h(e,"SUSPEND"))),g.click((e=>h(e,"ACTIVE"))),f.click((e=>h(e,"BLOCKED"))),k.click((e=>(e=>{t(e.target).addClass("merkai-disabled"),t(`#${e.target.id} .merkai-spinner`).removeClass("merkai-hidden"),t.ajax({url:merkai_object.ajaxurl,type:"POST",data:{action:"merkai_ajax_delete_wallet","ajax-delete-nonce":t("#ajax-delete-nonce").val()},success:e=>t(window.location.reload())}).error((e=>console.log(e)))})(e))),t("form.checkout").on("change",'input[name="payment_method"]',(function(){t(document.body).trigger("update_checkout")})),t("#top_up_amount").on("keyup change",(e=>{let a=parseFloat(t("#card_balance_limit").text()),o=parseFloat(t(".merkai-balance-value").first().text()),s=parseFloat(e.target.value);t("#topup_message").addClass("loading"),v(s,o,a)})),t(".top-up-variants > a").click((function(){let e=t(this).get(0).id.replace("variant_",""),a=parseFloat(t("#card_balance_limit").text()),o=parseFloat(t(".merkai-balance-value").first().text());t("#top_up_amount").val(e),t("#topup_message").addClass("loading"),v(e,o,a)})),t("#withdraw_amount").on("keyup change",(e=>{let a=parseFloat(t(".merkai-balance-value").first().text()),o=parseFloat(e.target.value);t("#withdraw_message").addClass("loading"),b(o,a,1)})),t(document).on("updated_checkout",(function(){e.initElements(),t("#top_up_button").click((e=>d(e))),_(),function(e,t=window.location.href){e=e.replace(/[\[\]]/g,"\\$&");const a=new RegExp("[?&]"+e+"(=([^&#]*)|&|#|$)").exec(t);return a?a[2]?decodeURIComponent(a[2].replace(/\+/g," ")):"":null}("ans")&&t(".woocommerce-notices-wrapper:first-child").prepend('<div class="woocommerce-message" role="alert">Registration complete. Please check your email, then visit this page again.</div>'),t("#top_up_amount").on("keyup change",(e=>{let a=parseFloat(t("#card_balance_limit").text()),o=parseFloat(t(".merkai-balance-value").first().text()),s=parseFloat(e.target.value);t("#topup_message").addClass("loading"),v(s,o,a)})),t(".top-up-variants > a").click((function(){let e=t(this).get(0).id.replace("variant_","");t("#top_up_amount").val(e),t("#topup_message").addClass("loading"),u(e,0,"payment_operation_add_money",!1)}));const a=t("#bonuses-value"),r=t("#bonuses-input"),m=parseFloat(t(".woocommerce-Price-amount").last().text().replace("$",""));function p(){const e=t("#place_order"),a=t(".payment_box.payment_method_merkai").is(":hidden");if(e&&!a){const a=parseFloat(t(".merkai-balance-value").first().attr("data-balance")),o=parseFloat(t(".merkai-bonus-value").first().attr("data-bonus")),s=parseFloat(t(".woocommerce-Price-amount").last().text().replace("$","")),n=parseFloat(t("#bonuses-value").val());a+o*i<s?(e.addClass("merkai-disabled"),e.text("Please TopUp your Wallet")):a+o*i>=s&&n*i+a<s?(e.addClass("merkai-disabled"),e.text("Please TopUp your Wallet or use your Bonuses")):(e.removeClass("merkai-disabled"),e.text("Place order"))}}a.val(r.val()),t(document).ready((function(){t("#merkai_payment_bonuses").addClass("loading"),y(parseFloat(m),r.val(),parseFloat(i))})),r.on("change",(function(){a.val(r.val()),t("#merkai_payment_bonuses").addClass("loading"),y(parseFloat(m),r.val(),parseFloat(i)),p()})),a.on("change",(function(){r.val(a.val()),t("#merkai_payment_bonuses").addClass("loading"),y(parseFloat(m),r.val(),parseFloat(i)),p()})),t("input[type=range]").on("input",(function(){t(this).trigger("change")})),t(".toggle-autodeposit").click((function(){t(this).toggleClass("checked"),t(this).hasClass("checked")?t("input#autodeposit").attr("value","1"):t("input#autodeposit").attr("value","0")})),t(".card-var").click((function(){t(".card-variants").toggleClass("clicked"),t(".clicked .card-var").click((function(){t(".card-var").removeClass("current-card"),t(this).addClass("current-card"),t("#source-card").attr("value",t(this).attr("data-pan"))}))})),p(),t("#bonuses-value").on("change",(function(){p()})),t('input[type="range"].slider-progress').each((function(){t(this).css("--value",t(this).val()),t(this).css("--min",""==t(this).attr("min")?"0":t(this).attr("min")),t(this).css("--max",""==t(this).attr("max")?"0":t(this).attr("max")),t(this).on("input",(function(){t(this).css("--value",t(this).val()),t("#bonuses-value").trigger("change")}))})),t("#top_up_amount").on("keyup change",(e=>{let a=0,i=parseFloat(e.target.value);c(i,l,"payment_operation_add_money")>0&&(a=c(i,l,"payment_operation_add_money"));let r=((e,t,a,o)=>{{let e=sum*parseFloat(s)-parseFloat(n);return e=Math.round(100*e)/100,e}})(),u=(e=>{let t=e*parseFloat(o)+parseFloat(n);return t=Math.round(100*t)/100,t})(i),d=parseFloat(t("#card_balance_limit").text()),m=parseFloat(t(".merkai-balance-value").first().text());parseFloat(e.target.value)+m>d?(t("#topup_message").html("When topping up the amount $"+i+" the balance limit will exceed the set value $"+d),t("#top_up_button").attr("disabled","true").addClass("disabled")):e.target.value<parseFloat(t("#top_up_amount").attr("min"))?(t("#top_up_button").attr("disabled","true").addClass("disabled"),t("#topup_message").html("Please enter amount more than minimum top up amount.")):(t("#top_up_button").removeAttr("disabled").removeClass("disabled"),a>0?t("#topup_message").html('Your balance will be replenished by $<span id="replenishAmount">'+r+'</span>, commission is $<span id="commissionAmount">'+u+'</span>. You will get <span id="bonusesCounter">'+a+"</span> bonuses."):t("#topup_message").html('Your balance will be replenished by $<span id="replenishAmount">'+r+'</span>, commission is $<span id="commissionAmount">'+u+"</span>."))})),w()})),t(document).on("updated_wc_div",(function(){w()})),t("#show_mini_modal").on("click",(function(){t(".topup_mini_form").toggle(),t(this).toggleClass("active"),t(this).hasClass("active")?t(this).css("transform","rotate(45deg)"):t(this).css("transform","rotate(0deg)")})),w()}))})(jQuery)})();