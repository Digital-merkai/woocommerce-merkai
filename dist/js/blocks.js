(()=>{"use strict";var e={};e.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),(()=>{var a;e.g.importScripts&&(a=e.g.location+"");var t=e.g.document;if(!a&&t&&(t.currentScript&&(a=t.currentScript.src),!a)){var r=t.getElementsByTagName("script");if(r.length)for(var m=r.length-1;m>-1&&(!a||!/^http(s?):/.test(a));)a=r[m--].src}if(!a)throw new Error("Automatic publicPath is not supported in this browser");a=a.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),e.p=a})();const a=window.React,t=window.wp.element,r=window.wp.i18n,m=window.wc.wcBlocksRegistry,l=window.wp.htmlEntities,i=window.wc.wcSettings,n=e.p+"images/cashback_ill.527e5b48.png";e.p;const s=e.p+"images/kopybara-logo.a12fba6f.png";function c(){return(0,a.createElement)("section",{className:"merkai"},(0,a.createElement)("div",{className:"merkai-embleme"},(0,a.createElement)("img",{src:s,className:"!merkai-block !merkai-mx-auto"})),(0,a.createElement)("div",{id:"merkai_auth_block",className:"merkai-max-w-4xl merkai-mx-auto merkai-mb-4"},(0,a.createElement)("div",{className:"merkai_login_form visible"},(0,a.createElement)("h2",{className:"merkai-text-center"},"Please, login before contunue!"),(0,a.createElement)("p",{className:"merkai-mb-8 merkai-text-center"},"Not registered? ",(0,a.createElement)("a",{className:"form-toggle-a"},"Sign up!")),(0,a.createElement)("form",{name:"loginform",id:"merkai_loginform",action:"/wp-login.php",method:"post"},(0,a.createElement)("div",{className:"row"},(0,a.createElement)("div",{className:"col"},(0,a.createElement)("label",{htmlFor:"log"},"Login"),(0,a.createElement)("input",{type:"text",name:"log",className:"merkai_input",id:"merkai_user_login"})),(0,a.createElement)("div",{className:"col"},(0,a.createElement)("label",{htmlFor:"pwd"},"Password"),(0,a.createElement)("input",{type:"password",name:"pwd",className:"merkai_input",id:"merkai_user_pass"}))),(0,a.createElement)("p",{className:"row"},(0,a.createElement)("label",null,(0,a.createElement)("input",{name:"rememberme",type:"checkbox",id:"merkai_rememberme",value:"forever"})," Remember me")),(0,a.createElement)("p",{className:"row"},(0,a.createElement)("input",{type:"submit",name:"wp-submit",id:"merkai_wp-submit",className:"merkai_button",value:"Log in"}),(0,a.createElement)("input",{type:"hidden",name:"cfps_cookie",value:"1"})))),(0,a.createElement)("div",{className:"merkai_register_form"},(0,a.createElement)("h2",{className:"merkai-text-center"},"Join Us for your convenience!"),(0,a.createElement)("p",{className:"merkai-text-center merkai-mb-8"},"Already registered? ",(0,a.createElement)("a",{className:"form-toggle-a"},"Log in!")),(0,a.createElement)("form",{name:"registerform",id:"registerform",noValidate:"novalidate",action:"/wp-login.php?action=register",method:"post"},(0,a.createElement)("div",{className:"row"},(0,a.createElement)("div",{className:"col"},(0,a.createElement)("label",{htmlFor:"user_login",className:"for_input"},"Username"),(0,a.createElement)("br",null),(0,a.createElement)("input",{type:"text",name:"user_login",id:"user_login",className:"merkai_input",value:"",autoCapitalize:"off",autoComplete:"username",required:"required"})),(0,a.createElement)("div",{className:"col"},(0,a.createElement)("label",{htmlFor:"user_email",className:"for_input"},"Email"),(0,a.createElement)("br",null),(0,a.createElement)("input",{type:"email",name:"user_email",id:"user_email",className:"merkai_input",value:"",autoComplete:"email",required:"required"}))),(0,a.createElement)("p",{id:"reg_passmail",className:"row"},"Registration confirmation will be emailed to you."),(0,a.createElement)("p",{className:"submit row"},(0,a.createElement)("input",{type:"submit",name:"wp-submit",id:"wp-submit",className:"merkai_button",value:"Register"}))))))}const o=window.ReactDOM,p=({children:e,onClose:t})=>(0,a.createElement)(a.Fragment,null,(0,o.createPortal)((0,a.createElement)("div",{className:"modal medium open"},(0,a.createElement)("div",{className:"close-modal close",onClick:t}),(0,a.createElement)("div",{className:"container"},e)),document.body));p.Header=({children:e,onClose:t})=>(0,a.createElement)("div",{className:"header"},(0,a.createElement)("h3",null,e),(0,a.createElement)("button",{className:"close",onClick:t},"×")),p.Content=({children:e,className:t})=>(0,a.createElement)("div",{className:"content"+(t?" "+t:"")},e);const k=p,u=e.p+"images/mc.cf6ccc88.png",d=e.p+"images/arr_d.dd63e771.png",E=e.p+"images/vs.1343e352.png",g=e.p+"images/plus_b.c366fd48.png",b=e.p+"images/arr_rb.8cd6a8f4.png",N=e.p+"images/i-gr.6c170f32.png";function w({onClose:e}){return(0,a.createElement)(k,{onClose:e},(0,a.createElement)(k.Header,{onClose:e},(0,r.__)("TopUp Wallet")),(0,a.createElement)(k.Content,null,(0,a.createElement)("div",{className:""},(0,a.createElement)("p",{className:"merkai-text-gray-500"},"From"),(0,a.createElement)("div",{className:"card-variants"},(0,a.createElement)("div",{className:"card-var current-card","data-pan":"<?php echo $wallet['card_number']; ?>"},(0,a.createElement)("div",{className:"merkai-flex merkai-flex-row merkai-gap-x-4 merkai-items-center"},(0,a.createElement)("img",{src:u,className:"merkai-h-[30px] merkai-w-[30px] merkai-mr-1 merkai-inline-block"}),(0,a.createElement)("p",null,"1356 5674 2352 2373")),(0,a.createElement)("img",{src:d,className:"merkai-h-[30px] merkai-w-[30px] merkai-inline-block"})),(0,a.createElement)("div",{className:"card-var","data-pan":"3727844328348156"},(0,a.createElement)("div",{className:"merkai-flex merkai-flex-row merkai-gap-x-4 merkai-items-center"},(0,a.createElement)("img",{src:E,className:"merkai-h-[30px] merkai-w-[30px] merkai-mr-1 merkai-inline-block"}),(0,a.createElement)("p",null,"3727 8443 2834 8156")),(0,a.createElement)("img",{src:d,className:"merkai-h-[30px] merkai-w-[30px] merkai-inline-block"})),(0,a.createElement)("div",{className:"card-var","data-pan":""},(0,a.createElement)("div",{className:"merkai-flex merkai-flex-row merkai-gap-x-4 merkai-items-center"},(0,a.createElement)("img",{"data-modal":".paymentMethodModal",src:g,className:"merkai-h-[30px] merkai-w-[30px] merkai-mr-1 merkai-inline-block"}),(0,a.createElement)("p",{"data-modal":".paymentMethodModal"},"Add new payment method")))),(0,a.createElement)("input",{type:"hidden",id:"source-card",name:"source-card",value:""}),(0,a.createElement)("div",{className:"top-up-amount-container merkai-mt-8 lg:merkai-mt-12 merkai-flex merkai-flex-row"},(0,a.createElement)("div",{className:"merkai-text-3xl"},"$"),(0,a.createElement)("input",{type:"number",step:"0.01",className:"merkai-bg-white merkai-border-0 merkai-text-3xl !merkai-p-0 focus:!merkai-outline-none",name:"amount",id:"top_up_amount",placeholder:"0",value:"1000"})),(0,a.createElement)("div",{className:"top-up-variants merkai-flex merkai-flex-row merkai-items-center merkai-mt-8 lg:merkai-mt-12 merkai-gap-x-2"},(0,a.createElement)("a",{className:"merkai-bg-gray-100 merkai-px-4 merkai-py-3 merkai-rounded-lg merkai-text-xl merkai-cursor-pointer",id:"variant_1000"},"$1000"),(0,a.createElement)("a",{className:"merkai-bg-gray-100 merkai-px-4 merkai-py-3 merkai-rounded-lg merkai-text-xl merkai-cursor-pointer",id:"variant_2000"},"$2000"),(0,a.createElement)("a",{className:"merkai-bg-gray-100 merkai-px-4 merkai-py-3 merkai-rounded-lg merkai-text-xl merkai-cursor-pointer",id:"variant_5000"},"$5000"),(0,a.createElement)("a",{className:"merkai-bg-gray-100 merkai-px-4 merkai-py-3 merkai-rounded-lg merkai-text-xl merkai-cursor-pointer",id:"variant_10000"},"$10 000"),(0,a.createElement)("a",{className:"merkai-bg-gray-100 merkai-px-4 merkai-py-3 merkai-rounded-lg merkai-text-xl merkai-cursor-pointer",id:"variant_15000"},"$15 000")),(0,a.createElement)("p",{className:"merkai-flex merkai-flex-row merkai-items-center merkai-mt-4 merkai-gap-x-0.5"},"The sending bank may charge a fee.",(0,a.createElement)("a",{href:"#"},"Here's how to avoid it."),(0,a.createElement)("img",{src:b,className:"merkai-h-[18px] merkai-w-[18px] merkai-inline-block"})),(0,a.createElement)("div",{className:"autodeposit merkai-flex merkai-flex-row merkai-items-center merkai-mt-8 lg:merkai-mt-12 merkai-gap-x-2"},(0,a.createElement)("img",{src:N,className:"merkai-h-[18px] merkai-w-[16px] merkai-mr-1 merkai-inline-block"}),"Autodeposit",(0,a.createElement)("div",{className:"toggle-autodeposit"},(0,a.createElement)("p",null,"ON"),(0,a.createElement)("div",{className:"toggler"}),(0,a.createElement)("p",null,"OFF")),(0,a.createElement)("input",{className:"hidden",value:"0",name:"autodeposit",id:"autodeposit"})),(0,a.createElement)("div",null,(0,a.createElement)("button",{id:"top_up_button",type:"button",className:"merkai-btn-primary"},"Top up",(0,a.createElement)("svg",{className:"merkai-spinner merkai-hidden merkai-animate-spin merkai-ml-4 merkai-h-5 merkai-w-5 merkai-text-white",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24"},(0,a.createElement)("circle",{className:"merkai-opacity-25",cx:"12",cy:"12",r:"10",stroke:"currentColor","stroke-width":"4"}),(0,a.createElement)("path",{className:"merkai-opacity-75",fill:"currentColor",d:"M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"})))))))}const v=(0,i.getSetting)("merkai_data",{}),h=({bonuses:e,setBonuses:r})=>{const[m,l]=(0,t.useState)(!1),[i,s]=(0,t.useState)(v.user);let o;return o=v.wallet.balance<v.cart_total?v.wallet.balance:v.cart_total,i?(0,a.createElement)("div",{className:"merkai-payment-block"},(0,a.createElement)("div",{className:"merkai-grid merkai-grid-cols-[1fr_1fr] merkai-gap-x-8 merkai-items-stretch"},(0,a.createElement)("div",{className:"merkai-card-simulator"},(0,a.createElement)("h3",{className:"!merkai-mb-0"},"Merkai.Pay"),(0,a.createElement)("div",{className:"merkai-flex merkai-flex-row merkai-items-center merkai-text-white merkai-gap-x-8 merkai-text-xl"},(0,a.createElement)("div",null,(0,a.createElement)("p",null,"Balance"),(0,a.createElement)("p",null,"$",(0,a.createElement)("span",{className:"merkai-numbers merkai-balance-value"},v.wallet.balance))),(0,a.createElement)("div",null,(0,a.createElement)("p",null,"Bonuses"),(0,a.createElement)("p",null,(0,a.createElement)("span",{className:"merkai-numbers merkai-bonus-value"},v.wallet.bonuses)))),(0,a.createElement)("div",{className:"merkai-flex merkai-flex-row merkai-items-center merkai-gap-x-2"},(0,a.createElement)("a",{className:"btn-blue",onClick:()=>l(!0)},"Add money"),(0,a.createElement)("a",{className:"btn-white"},"Withdraw"))),(0,a.createElement)("div",{className:"merkai-promo-badge"},(0,a.createElement)("div",{className:"merkai-grid merkai-grid-cols-2 merkai-gap-4"},(0,a.createElement)("div",null,(0,a.createElement)("h3",{className:"!merkai-mb-0"},"Ultimate Cashback"),(0,a.createElement)("p",null,"Make three purchases and get an increased cashback on everything!"),(0,a.createElement)("a",{className:"btn-white merkai-absolute merkai-bottom-4 merkai-left-4",href:"#"},"Read more")),(0,a.createElement)("img",{src:n,className:"object-contain"})))),v.wallet.bonuses&&(0,a.createElement)("div",{className:"merkai-conversion-rate merkai-mt-8"},(0,a.createElement)("h3",null,"How much do you want to pay in bonuses?"),(0,a.createElement)("input",{type:"number",className:"input-text short",name:"bonuses_value",id:"bonuses-value",placeholder:"",onChange:e=>r(e.target.value),value:e}),(0,a.createElement)("input",{type:"range",name:"bonuses_input",id:"bonuses_input",min:"0",max:o,value:e,onChange:e=>r(e.target.value)})),(0,a.createElement)("div",{className:"merkai-flex merkai-flex-row merkai-gap-x-4 merkai-mt-8"},(0,a.createElement)("a",{href:"#"},"Manage Cards"),(0,a.createElement)("a",{href:"#"},"History"),(0,a.createElement)("a",{href:"#"},"Support"),(0,a.createElement)("a",{href:"#"},"Terms")),m&&(0,a.createElement)(w,{onClose:()=>l(!1)})):(0,a.createElement)(c,null)},x=(0,r.__)("Merkai Payment","woocommerce-merkai"),f=(0,l.decodeEntities)(v.title)||x,y={name:"merkai",label:(0,a.createElement)((e=>{const{PaymentMethodLabel:t}=e.components;return(0,a.createElement)(t,{text:f})}),null),content:(0,a.createElement)((e=>{const{eventRegistration:r,emitResponse:m}=e,{onPaymentSetup:l}=r,[i,n]=(0,t.useState)(0);return(0,t.useEffect)((()=>{const e=l((async()=>{const e=i;return e.length?{type:m.responseTypes.SUCCESS,meta:{paymentMethodData:{bonusesValue:e}}}:{type:m.responseTypes.ERROR,message:"There was an error"}}));return()=>{e()}}),[m.responseTypes.ERROR,m.responseTypes.SUCCESS,l,i]),(0,a.createElement)(h,{bonuses:i,setBonuses:n})}),null),edit:(0,a.createElement)((()=>(0,a.createElement)("div",null,"Best Payment method ever")),null),canMakePayment:()=>!0,ariaLabel:f,supports:{features:v.supports}};(0,m.registerPaymentMethod)(y)})();