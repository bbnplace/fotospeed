import{Q as b,s as Ke,f as o,v as ot,A as nt,r as c,g as p,o as n,w as e,c as k,b as l,F as Ye,m as We,t as u,u as i,a as t,d as s,h as st,x as F,i as d,T as rt,j as qe,G as dt,H as it,Z as ut,p as ct,e as vt}from"./app-Sdk8DpPX.js";import{_ as pt}from"./BackendLayout-BWrzqsN2.js";import{F as mt,_ as ft}from"./OrderForm.vue_vue_type_script_setup_true_lang-C-Lm9uqc.js";import{_ as $e}from"./Panel.vue_vue_type_script_setup_true_lang-UjUSY557.js";import{h as re}from"./indigo-tQ_Gdl2-.js";import{_ as Xe}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{C as _t}from"./CustomerFeedback-DhibYQN1.js";import"./ApplicationLogo-HBwiJvLZ.js";const gt={key:0},bt={class:"font-bold"},ht={key:1},yt={class:"text-right"},wt={__name:"CommunicationLog",setup(Y){const R=b().props.order,r=b().props.auth.user,m=Ke({newMessage:""}),O=o([]);let T=null;const x=async()=>{const h={message:m.newMessage,orderId:R.id},A=(await F.post(route("order.log.write"),h,{headers:{"Content-Type":"application/json"}})).data;A.status!==void 0&&A.status=="success"&&(m.newMessage="",A.data&&O.value.push(A.data))},H=async()=>{const h=await F.get(route("order.log",[R.id]));O.value=h.data},$=(h,C)=>{Notification.permission==="granted"?new Notification(`New message from ${C}`,{body:h,icon:"/favicon.ico"}):console.log(`New message from ${C}: ${h}`)},B=()=>{T=window.Echo.private(`order-chat.${R.id}`),T.listen(".new-message",h=>{console.log("New message received:",h),O.value.push({id:h.id,message:h.message,created_at:h.created_at,user:h.user}),$(h.message,h.user.name)})},V=()=>{T&&(window.Echo.leave(`order-chat.${R.id}`),T=null)};return ot(()=>{H(),B(),Notification.permission==="default"&&Notification.requestPermission()}),nt(()=>{V()}),(h,C)=>{const A=c("VCard"),I=c("VTextarea"),z=c("VBtn");return n(),p($e,{"snippet-title":"Communication Log"},{default:e(()=>[O.value.length?(n(),k("div",gt,[(n(!0),k(Ye,null,We(O.value,(U,de)=>(n(),k("div",{key:de,class:"task-card px-2"},[l("p",bt,u(i(re)(U.created_at).calendar())+", "+u(U.user.mobile==i(r).mobile?"I":U.user.name)+" wrote:",1),l("p",null,u(U.message),1)]))),128))])):(n(),k("div",ht,[t(A,{class:"mb-2 p-2",color:"grey-lighten-3"},{default:e(()=>[s(" Leave a note for other team members working on this Order. ")]),_:1})])),l("div",null,[l("form",{onSubmit:st(x,["prevent"])},[t(I,{modelValue:m.newMessage,"onUpdate:modelValue":C[0]||(C[0]=U=>m.newMessage=U),label:"Type Comment",variant:"outlined",density:"compact",rows:"2","max-rows":"4","auto-grow":"",clearable:""},null,8,["modelValue"]),l("div",yt,[t(z,{"prepend-icon":"mdi-note-plus",color:"black",type:"submit"},{default:e(()=>[s("Add Note")]),_:1})])],32)])]),_:1})}}},xt=Xe(wt,[["__scopeId","data-v-52fc2372"]]),kt=l("div",null,"To keep things organized, we recommend creating a dedicated folder to store all assets for this order.",-1),Vt={__name:"DownloadAllMediaBtn",props:{files:Array},setup(Y){const R=route("file.fetch"),r=o(!1),m=Y,O=o(!1),T=($,B,V)=>{O.value=!0;const h=`${R}?filepath=${encodeURIComponent($)}&type=${B}`;F.get(h,{responseType:"blob"}).then(C=>{const A=new Blob([C.data],{type:C.headers["content-type"]});mt.saveAs(A,V),O.value=!1})},x=async()=>{r.value=!1,m.files.forEach($=>{const B=H($.file.uploadedFile),V=$.file.pageNumber?`page-${$.file.pageNumber}.${B}`:$.file.fileInfo.name;T($.file.uploadedFile,$.file.fileInfo.type,V)})},H=$=>$.split(".").pop().split(/\#|\?/)[0];return($,B)=>{const V=c("VCardTitle"),h=c("VCardText"),C=c("VBtn"),A=c("VCardActions"),I=c("VCard"),z=c("VOverlay");return n(),p(C,{color:"blue","prepend-icon":"mdi-download",disabled:O.value},{default:e(()=>[s(" Download All "),t(z,{modelValue:r.value,"onUpdate:modelValue":B[0]||(B[0]=U=>r.value=U),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(I,{class:"p-3","max-width":"400"},{default:e(()=>[t(V,null,{default:e(()=>[s("Heads Up!")]),_:1}),t(h,null,{default:e(()=>[l("p",null,"You're about to start downloading "+u(m.files.length)+" files.",1),kt]),_:1}),t(A,null,{default:e(()=>[t(C,{onClick:x,color:"blue","prepend-icon":"mdi-download",disabled:O.value},{default:e(()=>[s(" Start Download ")]),_:1},8,["disabled"])]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1},8,["disabled"])}}},Ct=l("h4",null,"From",-1),Pt=l("h4",null,"To",-1),Nt={key:1},Tt={__name:"OfflinePayment",props:{initialData:{type:Object,default:()=>({})}},emits:["statusUpdated"],setup(Y,{emit:R}){var xe,ke,Se,Ve,j,ue,Be,Ue,Re,L,Ce,ee,te,S;const r=R,m=Y,O=b().props.order,T=o(""),x=o(""),H=b().props.paymentMethods,$=b().props.paymentStatuses,B=b().props.banks||[],V=b().props.settings||{},h=o(new Date),C=o(new Date),A=o(!1),I=o(""),z=o(""),U=o(!1),de=o(((xe=m.initialData)==null?void 0:xe.customerBank)||((ke=m.initialData)==null?void 0:ke.customer_bank)||""),be=o(((Se=m.initialData)==null?void 0:Se.customerAccountNumber)||((Ve=m.initialData)==null?void 0:Ve.account_number)||""),he=o(((j=m.initialData)==null?void 0:j.customerAccountName)||((ue=m.initialData)==null?void 0:ue.depositor_name)||""),ye=o(((Be=m.initialData)==null?void 0:Be.organizationBank)||(V==null?void 0:V.bank_name)||""),G=o(((Ue=m.initialData)==null?void 0:Ue.organizationAccountNumber)||(V==null?void 0:V.account_number)||""),Q=o(((Re=m.initialData)==null?void 0:Re.organizationAccountName)||(V==null?void 0:V.org_name)||""),E=o(((L=m.initialData)==null?void 0:L.whoReceivedCash)||""),ie=o(((Ce=m.initialData)==null?void 0:Ce.amountPaid)||((ee=m.initialData)==null?void 0:ee.amount)||O.total_cost),we=o(((te=m.initialData)==null?void 0:te.transactionReference)||((S=m.initialData)==null?void 0:S.transaction_reference)||""),y=o({errors:{}}),Ae=async()=>{const W={orderId:O.id,status:x.value,paymentMethod:T.value,amountPaid:ie.value,customerBank:de.value,customerAccountName:he.value,customerAccountNumber:be.value,organizationBank:ye.value,organizationAccountName:Q.value,organizationAccountNumber:G.value,whoReceivedCash:E.value,paymentDate:h.value,transactionReference:we.value};U.value=!0,I.value="",z.value="",y.value.errors={};try{const v=await F.post(route("order.update-payment"),W,{headers:{"Content-Type":"application/json"}});v.data&&v.data.status=="success"&&(z.value=v.data.message,r("statusUpdated",{paymentMethod:T.value,invoicePaid:x.value=="Paid"}))}catch(v){v.response&&v.response.status===422?(I.value=v.response.data.message,y.value.errors=v.response.data.errors):I.value="Something went wrong! Pls try again later."}U.value=!1};return(W,v)=>{const Ie=c("v-card-title"),ce=c("v-alert"),ae=c("v-select"),D=c("v-col"),Z=c("v-text-field"),q=c("v-textarea"),J=c("v-row"),pe=c("v-combobox"),le=c("v-date-picker"),me=c("v-progress-linear"),He=c("v-card-text"),M=c("v-btn"),ve=c("v-card-actions"),fe=c("v-card"),oe=c("v-overlay");return n(),p(M,{"prepend-icon":"mdi-account-credit-card"},{default:e(()=>[s("Update Payment "),t(oe,{modelValue:A.value,"onUpdate:modelValue":v[13]||(v[13]=f=>A.value=f),activator:"parent","scroll-strategy":"static","location-strategy":"connected"},{default:e(()=>[t(fe,{"min-width":"250","max-width":"500",class:"p-2"},{default:e(()=>[t(Ie,null,{default:e(()=>[s("Update Payment Status")]),_:1}),t(He,{style:{"overflow-y":"auto","max-height":"320px"}},{default:e(()=>[I.value.length?(n(),p(ce,{key:0,type:"error",text:I.value,closable:"",class:"mb-2"},null,8,["text"])):d("",!0),t(J,null,{default:e(()=>[t(D,{cols:"12",class:"mt-2"},{default:e(()=>[t(ae,{modelValue:x.value,"onUpdate:modelValue":v[0]||(v[0]=f=>x.value=f),label:"Select Status",items:i($),variant:"outlined","hide-details":y.value.errors.status==null,"error-messages":y.value.errors.status,density:"compact"},null,8,["modelValue","items","hide-details","error-messages"])]),_:1}),x.value=="Paid"?(n(),p(D,{key:0,cols:"12",sm:"6"},{default:e(()=>[t(Z,{modelValue:ie.value,"onUpdate:modelValue":v[1]||(v[1]=f=>ie.value=f),label:"Amount Paid",prefix:"₦",type:"text",variant:"outlined",density:"compact","hide-details":y.value.errors.amountPaid==null,"error-messages":y.value.errors.amountPaid},null,8,["modelValue","hide-details","error-messages"])]),_:1})):d("",!0),x.value=="Paid"?(n(),p(D,{key:1,cols:"12",sm:"6"},{default:e(()=>[t(ae,{modelValue:T.value,"onUpdate:modelValue":v[2]||(v[2]=f=>T.value=f),label:"Payment Method",items:i(H),variant:"outlined","hide-details":y.value.errors.method==null,"error-messages":y.value.errors.method,density:"compact"},null,8,["modelValue","items","hide-details","error-messages"])]),_:1})):d("",!0),x.value=="Paid"&&T.value=="Cash"?(n(),p(D,{key:2,cols:"12"},{default:e(()=>[t(q,{modelValue:E.value,"onUpdate:modelValue":v[3]||(v[3]=f=>E.value=f),label:"Who Received the Cash?",variant:"outlined","hide-details":y.value.errors.whoReceivedCash==null,"error-messages":y.value.errors.whoReceivedCash,clearable:""},null,8,["modelValue","hide-details","error-messages"])]),_:1})):d("",!0),T.value=="Bank Transfer"&&x.value=="Paid"?(n(),p(D,{key:3,cols:"12",sm:"6"},{default:e(()=>[Ct,t(J,null,{default:e(()=>[t(D,{cols:"12"},{default:e(()=>[t(Z,{modelValue:de.value,"onUpdate:modelValue":v[4]||(v[4]=f=>de.value=f),label:"Customer's Bank",type:"text",variant:"outlined",density:"compact","hide-details":y.value.errors.customerBank==null,"error-messages":y.value.errors.customerBank},null,8,["modelValue","hide-details","error-messages"])]),_:1}),t(D,{cols:"12"},{default:e(()=>[t(Z,{modelValue:be.value,"onUpdate:modelValue":v[5]||(v[5]=f=>be.value=f),label:"Customer's Account Number",type:"tel",variant:"outlined",density:"compact","hide-details":y.value.errors.customerAccountNumber==null,"error-messages":y.value.errors.customerAccountNumber},null,8,["modelValue","hide-details","error-messages"])]),_:1}),t(D,{cols:"12"},{default:e(()=>[t(Z,{modelValue:he.value,"onUpdate:modelValue":v[6]||(v[6]=f=>he.value=f),label:"Customer's Account Name",type:"text",variant:"outlined",density:"compact","hide-details":y.value.errors.customerAccountName==null,"error-messages":y.value.errors.customerAccountName},null,8,["modelValue","hide-details","error-messages"])]),_:1})]),_:1})]),_:1})):d("",!0),T.value=="Bank Transfer"&&x.value=="Paid"?(n(),p(D,{key:4,cols:"12",sm:"6"},{default:e(()=>[Pt,t(J,null,{default:e(()=>[t(D,{cols:"12"},{default:e(()=>[t(pe,{modelValue:ye.value,"onUpdate:modelValue":v[7]||(v[7]=f=>ye.value=f),label:"Organization's Bank",items:i(B),"item-title":"name","item-value":"name",variant:"outlined",density:"compact","hide-details":y.value.errors.organizationBank==null,"error-messages":y.value.errors.organizationBank},null,8,["modelValue","items","hide-details","error-messages"])]),_:1}),t(D,{cols:"12"},{default:e(()=>[t(Z,{modelValue:G.value,"onUpdate:modelValue":v[8]||(v[8]=f=>G.value=f),label:"Organization's Account Number",type:"tel",variant:"outlined",density:"compact","hide-details":y.value.errors.organizationAccountNumber==null,"error-messages":y.value.errors.organizationAccountNumber},null,8,["modelValue","hide-details","error-messages"])]),_:1}),t(D,{cols:"12"},{default:e(()=>[t(Z,{modelValue:Q.value,"onUpdate:modelValue":v[9]||(v[9]=f=>Q.value=f),label:"Organization's Account Name",type:"text",variant:"outlined",density:"compact","hide-details":y.value.errors.organizationAccountName==null,"error-messages":y.value.errors.organizationAccountName},null,8,["modelValue","hide-details","error-messages"])]),_:1})]),_:1})]),_:1})):d("",!0),T.value=="Bank Transfer"&&x.value=="Paid"?(n(),p(D,{key:5,cols:"12"},{default:e(()=>[t(Z,{modelValue:we.value,"onUpdate:modelValue":v[10]||(v[10]=f=>we.value=f),label:"Transaction Reference",type:"text",variant:"outlined",density:"compact","hide-details":y.value.errors.transactionReference==null,"error-messages":y.value.errors.transactionReference},null,8,["modelValue","hide-details","error-messages"])]),_:1})):d("",!0),x.value=="Paid"?(n(),p(D,{key:6,cols:"12"},{default:e(()=>[t(le,{modelValue:h.value,"onUpdate:modelValue":v[11]||(v[11]=f=>h.value=f),max:C.value,"show-adjacent-months":"",title:"Transaction Date","hide-details":y.value.errors.paymentDate==null,"error-messages":y.value.errors.paymentDate},null,8,["modelValue","max","hide-details","error-messages"])]),_:1})):d("",!0),t(D,{cols:"12"},{default:e(()=>[U.value?(n(),p(me,{key:0,indeterminate:"",color:"red"})):d("",!0),z.value.length?(n(),k("p",Nt,u(z.value),1)):d("",!0)]),_:1})]),_:1})]),_:1}),t(ve,null,{default:e(()=>[t(M,{onClick:Ae},{default:e(()=>[s("Update")]),_:1}),t(M,{color:"red",onClick:v[12]||(v[12]=f=>A.value=!A.value)},{default:e(()=>[s("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})}}},Dt=l("hr",null,null,-1),Ot=l("b",null,"Client",-1),$t=l("br",null,null,-1),At=l("br",null,null,-1),St=l("b",null,"Delivery Address",-1),Bt=l("br",null,null,-1),Ut=l("b",null,"Origin Branch",-1),Rt=l("br",null,null,-1),It=l("b",null,"Processing Branch",-1),zt=l("br",null,null,-1),Mt=l("b",null,"Order Number",-1),Ft=l("br",null,null,-1),Et={key:0,class:"text-center font-bold mt-1 mb-0"},Yt={key:1,class:"text-center text-red mt-1 mb-0"},Ht=l("b",null,"Order Name",-1),jt=l("br",null,null,-1),Lt=l("b",null,"Price",-1),Wt=l("br",null,null,-1),qt={key:0,class:"text-center font-bold"},Gt={key:1,class:"text-center text-red mt-1 mb-0"},Qt=l("b",null,"Invoice Status",-1),Zt=l("br",null,null,-1),Jt=l("b",null,"Payment Method",-1),Kt=l("br",null,null,-1),Xt=l("b",null,"Product",-1),ea=l("br",null,null,-1),ta=l("b",null,"Quantity",-1),aa=l("br",null,null,-1),la=l("b",null,"Order Status",-1),oa=l("br",null,null,-1),na=l("b",null,"Current Process",-1),sa=l("br",null,null,-1),ra={key:0,class:"my-2"},da={key:1,class:"text-red"},ia=l("b",null,"Order Created",-1),ua=l("br",null,null,-1),ca=l("b",null,"Last Updated",-1),va=l("br",null,null,-1),pa=l("b",null,"Target Delivery Date",-1),ma=l("br",null,null,-1),fa=l("b",null,"WayBill Number",-1),_a=l("br",null,null,-1),ga={key:0,class:"text-center text-red mt-1 mb-0"},ba=l("b",null,"Note",-1),ha=l("br",null,null,-1),ya=l("hr",null,null,-1),wa=l("p",null,[s(" Why do you want to place this order on hold?"),l("br")],-1),xa={key:0,class:"text-center font-bold pt-2"},ka={key:1,class:"text-center text-red mt-1 mb-0"},Va=l("p",null,"Are you sure you want to reactivate this order?",-1),Ca={key:0,class:"text-center"},Pa={key:1,class:"text-center font-bold"},Na={key:2,class:"text-center text-red mt-1 mb-0"},Ta=l("p",null,"Are you sure you want to cancel this order?",-1),Da={key:0,class:"text-center"},Oa={key:1,class:"text-center font-bold"},$a={key:2,class:"text-center text-red mt-1 mb-0"},Aa={__name:"OrderCard",setup(Y){const R=b().props.auth.user,r=b().props.order,m=b().props.orderDetail,O=b().props.hasInvoice,T=b().props.canGenerateInvoice,x=o(b().props.invoicePaid),H=o(b().props.invoice),$=b().props.canEditOrder,B=b().props.canHoldOrder,V=b().props.canCancelOrder,h=b().props.canEditReferenceNumber,C=b().props.canEditPrice,A=b().props.canEditWaybill,I=b().props.canForwardToNextProcess,z=o(!1),U=b().props.isViewingFromProcessingBranch,de=b().props.showPriceToProcessingBranch,be=b().props.showInvoiceToProcessingBranch,he=!U||de,ye=!U||be,G=o(""),Q=o(!1),E=o(r.order_status.name),ie=o(r.process?r.process.name:"-"),we=r.waybill_number,y=o(r.human_forwarding),Ae=b().props.invoice,xe=o(Ae==null?"":Ae.payment_method),ke=o(b().props.canApproveOfflinePayment),Se=P=>{ke.value=!1,x.value=P.invoicePaid,xe.value=P.paymentMethod},Ve=new Intl.NumberFormat("en-US",{style:"decimal",minimumFractionDigits:0,maximumFractionDigits:0}),j=o("");let ue=null;const Be=async()=>{G.value="",j.value="",Q.value=!0;const P={orderId:r.id};ue&&ue.cancel("Request cancelled by user"),ue=F.CancelToken.source();try{const a=await F.put(route("order.cancel",[r.id]),P,{headers:{"Content-Type":"application/json"},cancelToken:ue.token});Q.value=!1,G.value=a.data.response,E.value=a.data.orderStatus}catch(a){Q.value=!1,a.response&&a.response.status===422?j.value=a.response.data.message:j.value="Something went wrong! Pls try again later."}setTimeout(()=>{G.value="",j.value="",z.value=!1},5e3)},Ue=rt({orderId:r.id}),Re=()=>{Ue.post(route("invoice.create"))},L=o(r.hold_reason),Ce=o(""),ee=o(!1),te=o(!1),S=o(r.paused),W=o(""),v=async()=>{W.value="",ee.value=!0;const P={reason:L.value};try{const a=await F.put(route("order.hold",[r.id]),P,{headers:{"Content-Type":"application/json"}});ee.value=!1,a.data&&a.data.status=="success"&&(S.value=!0,te.value=!1)}catch(a){ee.value=!1,a.response&&a.response.status===422?W.value=a.response.data.message:W.value="Something went wrong! Pls try again later."}setTimeout(()=>{W.value="",te.value=!1},5e3)},Ie=o(""),ce=o(!1),ae=o(!1),D=o(""),Z=async()=>{ce.value=!0;const P={};try{const a=await F.put(route("order.reactivate",[r.id]),P,{headers:{"Content-Type":"application/json"}});ce.value=!1,a.data&&a.data.status=="success"&&(S.value=!1,L.value="",ae.value=!1)}catch(a){ce.value=!1,a.response&&a.response.status===422?D.value=a.response.data.message:D.value="Something went wrong! Pls try again later."}setTimeout(()=>{D.value="",ae.value=!1},5e3)},q=o(r.order_number),J=o(!1),pe=o(""),le=o(""),me=o(!1),He=async()=>{le.value="",J.value=!0;const P={orderNumber:q.value};try{const a=await F.put(route("order.set-reference",[r.id]),P,{headers:{"Content-Type":"application/json"}});J.value=!1,a.data&&a.data.status=="success"&&(pe.value=a.data.response)}catch(a){J.value=!1,a.response&&a.response.status===422?le.value=a.response.data.message:le.value="Something went wrong! Pls try again later."}setTimeout(()=>{pe.value="",le.value="",me.value=!1},5e3)},M=o(r.total_cost),ve=o(!1),fe=o(""),oe=o(""),f=o(!1),et=async()=>{oe.value="",ve.value=!0;const P={price:M.value};try{const a=await F.put(route("order.set-price",[r.id]),P,{headers:{"Content-Type":"application/json"}});ve.value=!1,a.data&&a.data.status=="success"&&(fe.value=a.data.response)}catch(a){ve.value=!1,a.response&&a.response.status===422?oe.value=a.response.data.message:oe.value="Something went wrong! Pls try again later."}setTimeout(()=>{fe.value="",oe.value="",f.value=!1},5e3)},Pe=o(we),Ne=o(!1),ze=o(""),_e=o(""),Me=o(!1),tt=async()=>{Ne.value=!0,_e.value="";const P={waybillNumber:Pe.value};try{const a=await F.put(route("order.save-waybill",[r.id]),P,{headers:{"Content-Type":"application/json"}});Ne.value=!1,a.data&&a.data.status=="success"&&(ze.value=a.data.response)}catch(a){Ne.value=!1,a.response&&a.response.status===422?_e.value=a.response.data.message:_e.value="Something went wrong! Pls try again later."}setTimeout(()=>{ze.value="",_e.value="",Me.value=!1},5e3)},Fe=o(!1),Te=o(""),ge=o(""),at=async()=>{Fe.value=!0,Te.value="",ge.value="";const P={orderId:r.id};try{const a=await F.post(route("order.process.forward"),P,{headers:{"Content-Type":"application/json"}});a.data&&a.data.status=="success"&&(Te.value=a.data.message,ie.value=a.data.currentProcess,y.value=!1)}catch(a){a.response&&a.response.status===422?ge.value=a.response.data.message:ge.value="Something went wrong! Pls try again later."}Fe.value=!1,setTimeout(()=>{Te.value="",ge.value=""},7e3)},lt=()=>{const P=r.user.name,a=r.user.mobile,K=r.delivery_address,X=r.source_branch?r.source_branch.name:"-",_=r.processing_branch.name,g=q.value,N=r.name,De=r.item.name,ne=r.quantity,se=M.value?`₦${Ve.format(M.value)}`:"--",je=S.value?"On Hold":E.value,Oe=ie.value,Le=re(r.created_at).format("MMMM DD, YYYY"),Ee=re(m.date).format("MMMM DD, YYYY"),w=Pe.value??"NOT SET",Qe=r.note,Ze=window.open("","","height=800,width=900"),Je=b().props.site.name;Ze.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Order Card - ${g}</title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    color: #333;
                    background: #f5f5f5;
                    padding: 20px;
                }
                
                .print-container {
                    max-width: 800px;
                    margin: 0 auto;
                    background: white;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    border-radius: 8px;
                    overflow: hidden;
                }
                
                .header {
                    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
                    color: white;
                    padding: 30px;
                    text-align: center;
                }
                
                .header h1 {
                    font-size: 32px;
                    font-weight: 700;
                    margin-bottom: 5px;
                    letter-spacing: -0.5px;
                }
                
                .header .subtitle {
                    font-size: 14px;
                    opacity: 0.9;
                    text-transform: uppercase;
                    letter-spacing: 2px;
                }
                
                .order-number {
                    background: rgba(255,255,255,0.2);
                    padding: 15px 25px;
                    margin-top: 20px;
                    border-radius: 6px;
                    display: inline-block;
                }
                
                .order-number-label {
                    font-size: 12px;
                    opacity: 0.8;
                    margin-bottom: 5px;
                }
                
                .order-number-value {
                    font-size: 24px;
                    font-weight: 700;
                    letter-spacing: 1px;
                }
                
                .content {
                    padding: 0 30px;
                }
                
                .status-bar {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 15px 20px;
                    background: ${S.value?"#ef4444":"#10b981"};
                    color: white;
                    border-radius: 6px;
                    margin-bottom: 30px;
                }
                
                .status-label {
                    font-size: 12px;
                    opacity: 0.9;
                }
                
                .status-value {
                    font-size: 18px;
                    font-weight: 700;
                }
                
                .section {
                    margin-bottom: 30px;
                }
                
                .section-title {
                    font-size: 16px;
                    font-weight: 700;
                    color: #1e3a8a;
                    margin-bottom: 15px;
                    padding-bottom: 8px;
                    border-bottom: 2px solid #e5e7eb;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
                
                .info-grid {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 20px;
                }
                
                .info-item {
                    background: #f9fafb;
                    padding: 15px;
                    border-radius: 6px;
                    border-left: 3px solid #3b82f6;
                }
                
                .info-label {
                    font-size: 11px;
                    color: #6b7280;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    margin-bottom: 6px;
                    font-weight: 600;
                }
                
                .info-value {
                    font-size: 15px;
                    color: #111827;
                    font-weight: 500;
                }
                
                .info-value.large {
                    font-size: 18px;
                    font-weight: 700;
                    color: #1e3a8a;
                }
                
                .full-width {
                    grid-column: 1 / -1;
                }
                
                .notes-box {
                    background: #fef3c7;
                    padding: 15px;
                    border-radius: 6px;
                    border-left: 4px solid #f59e0b;
                    margin-top: 10px;
                }
                
                .notes-content {
                    color: #78350f;
                    font-size: 14px;
                    line-height: 1.6;
                }
                
                .footer {
                    background: #f9fafb;
                    padding: 20px 30px;
                    text-align: center;
                    border-top: 2px solid #e5e7eb;
                }
                
                .footer-text {
                    font-size: 12px;
                    color: #6b7280;
                }
                
                .print-date {
                    font-size: 11px;
                    color: #9ca3af;
                    margin-top: 5px;
                }
                
                @media print {
                    body {
                        background: white;
                        padding: 0;
                    }
                    
                    .print-container {
                        box-shadow: none;
                        border-radius: 0;
                    }
                    
                    @page {
                        margin: 15mm;
                    }
                }
            </style>
        </head>
        <body>
            <div class="print-container">
                <div class="header">
                    <h1>${Je}</h1>
                    <div class="order-number">
                        <div class="order-number-label">Order Number</div>
                        <div class="order-number-value">${g}</div>
                    </div>
                </div>
                
                <div class="content">
                    <div class="status-bar">
                        <div>
                            <div class="status-label">Current Status</div>
                            <div class="status-value">${je}</div>
                        </div>
                        <div>
                            <div class="status-label">Process</div>
                            <div class="status-value">${Oe}</div>
                        </div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Client Information</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Client Name</div>
                                <div class="info-value">${P}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Phone Number</div>
                                <div class="info-value">${a}</div>
                            </div>
                            <div class="info-item full-width">
                                <div class="info-label">Delivery Address</div>
                                <div class="info-value">${K}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Order Details</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Order Name</div>
                                <div class="info-value large">${N}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Product</div>
                                <div class="info-value">${De}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Quantity</div>
                                <div class="info-value">${ne}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Total Cost</div>
                                <div class="info-value large">${se}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Processing Information</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Origin Branch</div>
                                <div class="info-value">${X}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Processing Branch</div>
                                <div class="info-value">${_}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Order Date</div>
                                <div class="info-value">${Le}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">${E.value==="Delivered"||E.value==="Fulfilled"?"Delivery Date":"Estimated Delivery Date"}</div>
                                <div class="info-value">${Ee}</div>
                            </div>
                            <div class="info-item full-width">
                                <div class="info-label">Waybill Number</div>
                                <div class="info-value">${w}</div>
                            </div>
                        </div>
                    </div>
                    
                    ${Qe?`
                        <div class="section">
                            <div class="section-title">Special Notes</div>
                            <div class="notes-box">
                                <div class="notes-content">${Qe}</div>
                            </div>
                        </div>
                    `:""}
                </div>
                
                <div class="footer">
                    <div class="footer-text">This is an official order card from ${Je}</div>
                    <div class="print-date">Printed on ${re().format("MMMM DD, YYYY [at] h:mm A")}</div>
                </div>
            </div>
            
            <script>
                window.onload = function() {
                    window.print();
                    window.onafterprint = function() {
                        window.close();
                    };
                };
            <\/script>
        </body>
        </html>
    `),Ze.document.close()};return(P,a)=>{const K=c("VCardText"),X=c("VCard"),_=c("VCol"),g=c("VRow"),N=c("VBtn"),De=c("VTextField"),ne=c("VCardActions"),se=c("VOverlay"),je=c("v-progress-linear"),Oe=c("VCardTitle"),Le=c("VTextarea"),Ee=c("v-progress-circular");return n(),p($e,{snippetTitle:"Order Card",cardColor:S.value?"bg-gray-700 text-white":"bg-white"},{default:e(()=>[L.value&&L.value.length&&S.value?(n(),p(g,{key:0},{default:e(()=>[t(_,{cols:"12"},{default:e(()=>[t(X,{color:"red"},{default:e(()=>[t(K,null,{default:e(()=>[s(u(L.value),1)]),_:1})]),_:1})]),_:1})]),_:1})):d("",!0),t(g,null,{default:e(()=>[t(_,{class:"text-right"},{default:e(()=>[i($)&&q.value&&q.value.length&&M.value>0?(n(),p(N,{key:0,color:"blue-darken-3","prepend-icon":"mdi-printer",onClick:lt},{default:e(()=>[s(" Print Card ")]),_:1})):d("",!0)]),_:1})]),_:1}),Dt,t(g,{id:"order-card"},{default:e(()=>[t(_,{cols:"12",md:"6"},{default:e(()=>[t(g,null,{default:e(()=>[t(_,null,{default:e(()=>[Ot,$t,s(" "+u(i(r).user.name),1),At,s(" "+u(i(r).user.mobile),1)]),_:1})]),_:1}),t(g,null,{default:e(()=>[t(_,null,{default:e(()=>[St,Bt,s(" "+u(i(r).delivery_address),1)]),_:1})]),_:1}),t(g,null,{default:e(()=>[t(_,null,{default:e(()=>[Ut,Rt,s(" "+u(i(r).source_branch?i(r).source_branch.name:"-"),1)]),_:1})]),_:1}),t(g,null,{default:e(()=>[t(_,null,{default:e(()=>[It,zt,s(" "+u(i(r).processing_branch.name),1)]),_:1})]),_:1}),t(g,null,{default:e(()=>[t(_,null,{default:e(()=>[Mt,Ft,s(" "+u(q.value)+" ",1),i(h)&&!S.value?(n(),p(N,{key:0,"prepend-icon":"mdi-pencil",class:"mr-2 no-padding no-border",elevation:"0"},{default:e(()=>[t(se,{modelValue:me.value,"onUpdate:modelValue":a[2]||(a[2]=w=>me.value=w),activator:"parent","location-strategy":"connected","scroll-strategy":"static"},{default:e(()=>[t(X,{"max-width":"400",class:"p-1"},{default:e(()=>[t(K,{class:"pb-0"},{default:e(()=>[t(De,{modelValue:q.value,"onUpdate:modelValue":a[0]||(a[0]=w=>q.value=w),"hide-details":"",id:"order-number",variant:"outlined",label:"Order Number",style:{"min-width":"200px"},loading:J.value},null,8,["modelValue","loading"]),pe.value.length?(n(),k("p",Et,u(pe.value),1)):d("",!0),le.value.length?(n(),k("p",Yt,u(le.value),1)):d("",!0)]),_:1}),t(ne,null,{default:e(()=>[t(N,{color:"blue-darken-1 m-1",onClick:He,disabled:J.value},{default:e(()=>[s("Save")]),_:1},8,["disabled"]),t(N,{color:"grey-darken-1 m-1",onClick:a[1]||(a[1]=w=>me.value=!1)},{default:e(()=>[s("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):d("",!0)]),_:1})]),_:1}),t(g,null,{default:e(()=>[t(_,null,{default:e(()=>[Ht,jt,s(" "+u(i(r).name),1)]),_:1})]),_:1}),i(he)?(n(),p(g,{key:0},{default:e(()=>[P.$page.props.auth.user.role!="Customer"?(n(),p(_,{key:0},{default:e(()=>[Lt,Wt,s(" ₦"+u(M.value?i(Ve).format(M.value):" --:--")+" ",1),i(C)&&!S.value?(n(),p(N,{key:0,elevation:"0","prepend-icon":"mdi-pencil",class:"mr-2 no-padding no-border"},{default:e(()=>[t(se,{modelValue:f.value,"onUpdate:modelValue":a[5]||(a[5]=w=>f.value=w),activator:"parent","location-strategy":"connected","scroll-strategy":"static"},{default:e(()=>[t(X,{"max-width":"400",class:"p-1"},{default:e(()=>[t(K,{class:"pb-0"},{default:e(()=>[t(De,{modelValue:M.value,"onUpdate:modelValue":a[3]||(a[3]=w=>M.value=w),"hide-details":"",id:"price",type:"number",variant:"outlined",label:"Price",prefix:"₦",style:{width:"200px"},loading:ve.value},null,8,["modelValue","loading"]),fe.value.length?(n(),k("div",qt,u(fe.value),1)):d("",!0),oe.value.length?(n(),k("p",Gt,u(oe.value),1)):d("",!0)]),_:1}),t(ne,null,{default:e(()=>[t(N,{color:"blue-darken-1 m-1",onClick:et,disabled:ve.value},{default:e(()=>[s("Save")]),_:1},8,["disabled"]),t(N,{color:"grey-darken-1 m-1",onClick:a[4]||(a[4]=w=>f.value=!1)},{default:e(()=>[s("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):d("",!0)]),_:1})):d("",!0)]),_:1})):d("",!0),M.value&&q.value&&!S.value&&!i(O)&&i(T)&&E.value!="Cancelled"?(n(),p(g,{key:1},{default:e(()=>[t(_,{cols:"12"},{default:e(()=>[t(N,{color:"blue-darken-1",onClick:Re},{default:e(()=>[s("Issue Invoice")]),_:1})]),_:1})]),_:1})):d("",!0),i(ye)&&i(O)?(n(),p(g,{key:2},{default:e(()=>[t(_,{cols:"12"},{default:e(()=>[Qt,Zt,s(" "+u(x.value?"Paid":"Unpaid"),1)]),_:1}),t(_,{cols:"12"},{default:e(()=>[t(i(qe),{class:"font-bold underline",href:P.route("invoice",[H.value.id])},{default:e(()=>[s("Open Invoice")]),_:1},8,["href"])]),_:1})]),_:1})):d("",!0),i(O)&&x.value?(n(),p(g,{key:3},{default:e(()=>[t(_,{cols:"12"},{default:e(()=>[Jt,Kt,s(" "+u(xe.value),1)]),_:1})]),_:1})):d("",!0),ke.value?(n(),p(g,{key:4},{default:e(()=>[t(_,{cols:"12"},{default:e(()=>[t(Tt,{onStatusUpdated:Se})]),_:1})]),_:1})):d("",!0)]),_:1}),t(_,{cols:"12",md:"6"},{default:e(()=>[t(g,null,{default:e(()=>[t(_,null,{default:e(()=>[Xt,ea,s(" "+u(P.$page.props.order.item.name),1)]),_:1})]),_:1}),t(g,null,{default:e(()=>[t(_,null,{default:e(()=>[ta,aa,s(" "+u(P.$page.props.order.quantity),1)]),_:1})]),_:1}),t(g,null,{default:e(()=>[t(_,null,{default:e(()=>[la,oa,s(" "+u(S.value?"On Hold":E.value),1)]),_:1})]),_:1}),t(g,null,{default:e(()=>[t(_,null,{default:e(()=>[na,sa,s(" "+u(ie.value),1)]),_:1})]),_:1}),i(I)&&y.value?(n(),p(g,{key:0},{default:e(()=>[t(_,null,{default:e(()=>[t(N,{"prepend-icon":"mdi-play",color:"blue-darken-3",onClick:at,disabled:Fe.value},{default:e(()=>[s("Start Next Process")]),_:1},8,["disabled"]),Fe.value?(n(),k("p",ra,[t(je,{color:"red",indeterminate:""})])):d("",!0),ge.value.length?(n(),k("p",da,u(ge.value),1)):d("",!0)]),_:1})]),_:1})):d("",!0),Te.value.length?(n(),p(g,{key:1},{default:e(()=>[t(_,null,{default:e(()=>[s(u(Te.value),1)]),_:1})]),_:1})):d("",!0),i(R).isAdmin?(n(),p(g,{key:2},{default:e(()=>[t(_,null,{default:e(()=>[t(i(qe),{class:"font-bold underline",href:P.route("tasks.order.dashboard",[i(r).id])},{default:e(()=>[s("View Tasks")]),_:1},8,["href"])]),_:1})]),_:1})):d("",!0),t(g,null,{default:e(()=>[t(_,null,{default:e(()=>[ia,ua,s(" "+u(i(re)(i(r).created_at).calendar()),1)]),_:1})]),_:1}),t(g,null,{default:e(()=>[t(_,null,{default:e(()=>[ca,va,s(" "+u(i(re)(i(r).updated_at).fromNow()),1)]),_:1})]),_:1}),t(g,null,{default:e(()=>[t(_,null,{default:e(()=>[pa,ma,s(" "+u(i(re)(i(m).date).format("LL")),1)]),_:1})]),_:1}),t(g,null,{default:e(()=>[t(_,null,{default:e(()=>[fa,_a,s(" "+u(Pe.value??"NOT SET")+" ",1),i(A)&&!S.value?(n(),p(N,{key:0,elevation:"0","prepend-icon":"mdi-pencil",class:"mr-2 no-padding no-border"},{default:e(()=>[t(se,{modelValue:Me.value,"onUpdate:modelValue":a[8]||(a[8]=w=>Me.value=w),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(X,{"max-width":"400",class:"p-1"},{default:e(()=>[t(K,{class:"pb-0"},{default:e(()=>[t(De,{modelValue:Pe.value,"onUpdate:modelValue":a[6]||(a[6]=w=>Pe.value=w),"hide-details":"",id:"order-number",variant:"outlined",label:"Waybill Number",style:{"min-width":"200px"},loading:Ne.value},null,8,["modelValue","loading"]),dt(l("div",{class:"text-center font-bold"},u(ze.value),513),[[it,ze.value.length]]),_e.value.length?(n(),k("p",ga,u(_e.value),1)):d("",!0)]),_:1}),t(ne,null,{default:e(()=>[t(N,{color:"blue-darken-1 m-1",onClick:tt,disabled:Ne.value},{default:e(()=>[s("Save")]),_:1},8,["disabled"]),t(N,{color:"grey-darken-1 m-1",onClick:a[7]||(a[7]=w=>Me.value=!1)},{default:e(()=>[s("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):d("",!0)]),_:1})]),_:1})]),_:1})]),_:1}),t(g,null,{default:e(()=>[t(_,null,{default:e(()=>[ba,ha,s(" "+u(i(r).note),1)]),_:1})]),_:1}),ya,t(g,null,{default:e(()=>[t(_,null,{default:e(()=>[!S.value&&i(B)?(n(),p(N,{key:0,color:"grey-darken-3","prepend-icon":"mdi-pause",class:"mr-2 mb-2"},{default:e(()=>[s(" Hold Order "),t(se,{modelValue:te.value,"onUpdate:modelValue":a[11]||(a[11]=w=>te.value=w),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(X,{"max-width":"400",class:"p-3"},{default:e(()=>[t(Oe,null,{default:e(()=>[s("What's Wrong?")]),_:1}),t(K,null,{default:e(()=>[wa,t(Le,{modelValue:L.value,"onUpdate:modelValue":a[9]||(a[9]=w=>L.value=w),"hide-details":"",id:"hold-order",variant:"outlined",label:"Leave a note for team members",loading:ee.value},null,8,["modelValue","loading"]),Ce.value.length?(n(),k("p",xa,u(Ce.value),1)):d("",!0),W.value&&W.value.length?(n(),k("p",ka,u(W.value),1)):d("",!0)]),_:1}),t(ne,null,{default:e(()=>[t(N,{color:"red-darken-1",onClick:v,disabled:ee.value},{default:e(()=>[s("Continue")]),_:1},8,["disabled"]),t(N,{color:"blue-darken-1",onClick:a[10]||(a[10]=w=>te.value=!1)},{default:e(()=>[s("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):d("",!0),S.value&&i(R).isAdmin&&E.value!="Cancelled"?(n(),p(N,{key:1,color:"white",class:"mr-2 mb-2","prepend-icon":"mdi-play"},{default:e(()=>[s(" Reactivate "),t(se,{modelValue:ae.value,"onUpdate:modelValue":a[13]||(a[13]=w=>ae.value=w),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(X,{"max-width":"400",class:"p-3"},{default:e(()=>[t(Oe,null,{default:e(()=>[s("Confirm Action!")]),_:1}),t(K,null,{default:e(()=>[Va,ce.value?(n(),k("p",Ca,[t(Ee,{color:"red",indeterminate:""})])):d("",!0),Ie.value.length?(n(),k("p",Pa,u(Ie.value),1)):d("",!0),D.value&&D.value.length?(n(),k("p",Na,u(D.value),1)):d("",!0)]),_:1}),t(ne,null,{default:e(()=>[t(N,{color:"red-darken-1 m-1",onClick:Z,disabled:ce.value},{default:e(()=>[s("Yes Proceed")]),_:1},8,["disabled"]),t(N,{color:"blue-darken-1 m-1",onClick:a[12]||(a[12]=w=>ae.value=!1)},{default:e(()=>[s("Don't")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):d("",!0),i(V)?(n(),p(N,{key:2,color:"red-darken-1",class:"mr-2 mb-2","prepend-icon":"mdi-cancel"},{default:e(()=>[s(" Cancel Order "),t(se,{modelValue:z.value,"onUpdate:modelValue":a[15]||(a[15]=w=>z.value=w),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(X,{"max-width":"400",class:"p-3"},{default:e(()=>[t(Oe,null,{default:e(()=>[s("Heads Up!")]),_:1}),t(K,null,{default:e(()=>[Ta,Q.value?(n(),k("p",Da,[t(Ee,{color:"red",indeterminate:""})])):d("",!0),G.value.length?(n(),k("p",Oa,u(G.value),1)):d("",!0),j.value&&j.value.length?(n(),k("p",$a,u(j.value),1)):d("",!0)]),_:1}),t(ne,null,{default:e(()=>[t(N,{color:"red-darken-1 m-1",onClick:Be,disabled:Q.value},{default:e(()=>[s("Yes Proceed")]),_:1},8,["disabled"]),t(N,{color:"blue-darken-1 m-1",onClick:a[14]||(a[14]=w=>z.value=!1)},{default:e(()=>[s("Don't")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):d("",!0)]),_:1})]),_:1})]),_:1},8,["cardColor"])}}},Ge=Y=>(ct("data-v-d6ad9e94"),Y=Y(),vt(),Y),Sa=Ge(()=>l("tr",null,[l("td",null,"Process"),l("td",null,"Completed by"),l("td",null,"Date")],-1)),Ba={key:0,class:"text-right mb-2"},Ua=Ge(()=>l("h3",{class:"text-red"},"No asset was uploaded for this order!",-1)),Ra=Ge(()=>l("p",null,"If you do not have the image files, kindly contact the client using the phone number below.",-1)),Ia={__name:"Detail",setup(Y){b().props.order;const R=b().props.orderDetail,r=b().props.activities,m=Ke({orderFiles:R.files}),O=T=>{for(let x=0;x<m.orderFiles.length;x++)m.orderFiles[x].file.id==T.id&&m.orderFiles.splice(x,1)};return(T,x)=>{const H=c("THead"),$=c("TBody"),B=c("VTable"),V=c("VCol"),h=c("VRow");return n(),k(Ye,null,[t(i(ut),{title:"Order"}),t(pt,null,{default:e(()=>[t(i(qe),{href:T.route("orders"),class:"font-bold"},{default:e(()=>[s("Back")]),_:1},8,["href"]),t(h,null,{default:e(()=>[t(V,{cols:"12",sm:"6"},{default:e(()=>[t(Aa),i(r).length?(n(),p($e,{key:0,"snippet-title":"Activities"},{default:e(()=>[t(B,null,{default:e(()=>[t(H,null,{default:e(()=>[Sa]),_:1}),t($,null,{default:e(()=>[(n(!0),k(Ye,null,We(i(r),C=>(n(),k("tr",{key:C.id},[l("td",null,u(C.process.name),1),l("td",null,u(C.staff.name),1),l("td",null,u(i(re)(C.created_at).format("MM/DD/YYYY, h:mm A")),1)]))),128))]),_:1})]),_:1})]),_:1})):d("",!0)]),_:1}),t(V,{cols:"12",sm:"6"},{default:e(()=>[t(xt),t($e,{"snippet-title":"Customer Feedback"},{default:e(()=>[t(_t)]),_:1})]),_:1})]),_:1}),t($e,{"snippet-title":"Order Assets"},{default:e(()=>[m.orderFiles.length?(n(),k("div",Ba,[t(Vt,{files:m.orderFiles},null,8,["files"])])):d("",!0),m.orderFiles.length?(n(),p(h,{key:1},{default:e(()=>[(n(!0),k(Ye,null,We(m.orderFiles,(C,A)=>(n(),p(V,{cols:"12",lg:"6",key:A},{default:e(()=>[t(ft,{orderImage:C.file,view:"Detail",onPageRemoved:O,onPageDataUpdated:I=>{T.updatePageData(I,C)}},null,8,["orderImage","onPageDataUpdated"])]),_:2},1024))),128))]),_:1})):(n(),p(h,{key:2},{default:e(()=>[t(V,{class:"text-center"},{default:e(()=>[Ua,Ra]),_:1})]),_:1}))]),_:1})]),_:1})],64)}}},Wa=Xe(Ia,[["__scopeId","data-v-d6ad9e94"]]);export{Wa as default};
