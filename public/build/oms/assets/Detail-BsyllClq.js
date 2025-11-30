import{Q as w,s as Ge,i as o,v as Xe,z as et,r as c,f,o as n,w as e,c as k,b as l,F as Me,m as Ye,t as i,u,a as t,d as s,g as tt,x as E,h as d,T as at,j as He,D as lt,G as ot,Z as nt,p as st,e as rt}from"./app-CScIeqmP.js";import{_ as dt}from"./BackendLayout-BkYLatpp.js";import{F as it,_ as ut}from"./OrderForm.vue_vue_type_script_setup_true_lang-7MXrFisI.js";import{_ as Oe}from"./Panel.vue_vue_type_script_setup_true_lang-iw2RntNE.js";import{h as se}from"./indigo-D5aFsT2T.js";import{_ as Qe}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{C as ct}from"./CustomerFeedback-Dr4o_k12.js";import"./ApplicationLogo-rn_KCN4F.js";const vt={key:0},mt={class:"font-bold"},pt={key:1},ft={class:"text-right"},_t={__name:"CommunicationLog",setup(j){const R=w().props.order,r=w().props.auth.user,m=Ge({newMessage:""}),O=o([]);let D=null;const x=async()=>{const g={message:m.newMessage,orderId:R.id},A=(await E.post(route("order.log.write"),g,{headers:{"Content-Type":"application/json"}})).data;A.status!==void 0&&A.status=="success"&&(m.newMessage="",A.data&&O.value.push(A.data))},L=async()=>{const g=await E.get(route("order.log",[R.id]));O.value=g.data},$=(g,C)=>{Notification.permission==="granted"?new Notification(`New message from ${C}`,{body:g,icon:"/favicon.ico"}):console.log(`New message from ${C}: ${g}`)},B=()=>{D=window.Echo.private(`order-chat.${R.id}`),D.listen(".new-message",g=>{console.log("New message received:",g),O.value.push({id:g.id,message:g.message,created_at:g.created_at,user:g.user}),$(g.message,g.user.name)})},V=()=>{D&&(window.Echo.leave(`order-chat.${R.id}`),D=null)};return Xe(()=>{L(),B(),Notification.permission==="default"&&Notification.requestPermission()}),et(()=>{V()}),(g,C)=>{const A=c("VCard"),z=c("VTextarea"),M=c("VBtn");return n(),f(Oe,{"snippet-title":"Communication Log"},{default:e(()=>[O.value.length?(n(),k("div",vt,[(n(!0),k(Me,null,Ye(O.value,(S,Y)=>(n(),k("div",{key:Y,class:"task-card px-2"},[l("p",mt,i(u(se)(S.created_at).calendar())+", "+i(S.user.mobile==u(r).mobile?"I":S.user.name)+" wrote:",1),l("p",null,i(S.message),1)]))),128))])):(n(),k("div",pt,[t(A,{class:"mb-2 p-2",color:"grey-lighten-3"},{default:e(()=>[s(" Leave a note for other team members working on this Order. ")]),_:1})])),l("div",null,[l("form",{onSubmit:tt(x,["prevent"])},[t(z,{modelValue:m.newMessage,"onUpdate:modelValue":C[0]||(C[0]=S=>m.newMessage=S),label:"Type Comment",variant:"outlined",density:"compact",rows:"2","max-rows":"4","auto-grow":"",clearable:""},null,8,["modelValue"]),l("div",ft,[t(M,{"prepend-icon":"mdi-note-plus",color:"black",type:"submit"},{default:e(()=>[s("Add Note")]),_:1})])],32)])]),_:1})}}},bt=Qe(_t,[["__scopeId","data-v-52fc2372"]]),gt=l("div",null,"To keep things organized, we recommend creating a dedicated folder to store all assets for this order.",-1),ht={__name:"DownloadAllMediaBtn",props:{files:Array},setup(j){const R=route("file.fetch"),r=o(!1),m=j,O=o(!1),D=($,B,V)=>{O.value=!0;const g=`${R}?filepath=${encodeURIComponent($)}&type=${B}`;E.get(g,{responseType:"blob"}).then(C=>{const A=new Blob([C.data],{type:C.headers["content-type"]});it.saveAs(A,V),O.value=!1})},x=async()=>{r.value=!1,m.files.forEach($=>{const B=L($.file.uploadedFile),V=$.file.pageNumber?`page-${$.file.pageNumber}.${B}`:$.file.fileInfo.name;D($.file.uploadedFile,$.file.fileInfo.type,V)})},L=$=>$.split(".").pop().split(/\#|\?/)[0];return($,B)=>{const V=c("VCardTitle"),g=c("VCardText"),C=c("VBtn"),A=c("VCardActions"),z=c("VCard"),M=c("VOverlay");return n(),f(C,{color:"blue","prepend-icon":"mdi-download",disabled:O.value},{default:e(()=>[s(" Download All "),t(M,{modelValue:r.value,"onUpdate:modelValue":B[0]||(B[0]=S=>r.value=S),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(z,{class:"p-3","max-width":"400"},{default:e(()=>[t(V,null,{default:e(()=>[s("Heads Up!")]),_:1}),t(g,null,{default:e(()=>[l("p",null,"You're about to start downloading "+i(m.files.length)+" files.",1),gt]),_:1}),t(A,null,{default:e(()=>[t(C,{onClick:x,color:"blue","prepend-icon":"mdi-download",disabled:O.value},{default:e(()=>[s(" Start Download ")]),_:1},8,["disabled"])]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1},8,["disabled"])}}},yt=l("h4",null,"From",-1),wt=l("h4",null,"To",-1),xt={key:1},kt={__name:"OfflinePayment",props:{initialData:{type:Object,default:()=>({})}},emits:["statusUpdated"],setup(j,{emit:R}){var de,$e,Ae,Se,W,Ve,X,ee,U,q,Ue,Ce,te,ae;const r=R,m=j,O=w().props.order,D=o(""),x=o(""),L=w().props.paymentMethods,$=w().props.paymentStatuses,B=w().props.banks||[],V=w().props.settings||{},g=o(new Date),C=o(new Date),A=o(!1),z=o(""),M=o(""),S=o(!1),Y=o(((de=m.initialData)==null?void 0:de.customerBank)||(($e=m.initialData)==null?void 0:$e.customer_bank)||""),H=o(((Ae=m.initialData)==null?void 0:Ae.customerAccountNumber)||((Se=m.initialData)==null?void 0:Se.account_number)||""),re=o(((W=m.initialData)==null?void 0:W.customerAccountName)||((Ve=m.initialData)==null?void 0:Ve.depositor_name)||""),xe=o(((X=m.initialData)==null?void 0:X.organizationBank)||(V==null?void 0:V.bank_name)||""),pe=o(((ee=m.initialData)==null?void 0:ee.organizationAccountNumber)||(V==null?void 0:V.account_number)||""),fe=o(((U=m.initialData)==null?void 0:U.organizationAccountName)||(V==null?void 0:V.org_name)||""),_e=o(((q=m.initialData)==null?void 0:q.whoReceivedCash)||""),be=o(((Ue=m.initialData)==null?void 0:Ue.amountPaid)||((Ce=m.initialData)==null?void 0:Ce.amount)||O.total_cost),ke=o(((te=m.initialData)==null?void 0:te.transactionReference)||((ae=m.initialData)==null?void 0:ae.transaction_reference)||""),h=o({errors:{}}),Q=async()=>{const Z={orderId:O.id,status:x.value,paymentMethod:D.value,amountPaid:be.value,customerBank:Y.value,customerAccountName:re.value,customerAccountNumber:H.value,organizationBank:xe.value,organizationAccountName:fe.value,organizationAccountNumber:pe.value,whoReceivedCash:_e.value,paymentDate:g.value,transactionReference:ke.value};S.value=!0,z.value="",M.value="",h.value.errors={};try{const v=await E.post(route("order.update-payment"),Z,{headers:{"Content-Type":"application/json"}});v.data&&v.data.status=="success"&&(M.value=v.data.message,r("statusUpdated",{paymentMethod:D.value,invoicePaid:x.value=="Paid"}))}catch(v){v.response&&v.response.status===422?(z.value=v.response.data.message,h.value.errors=v.response.data.errors):z.value="Something went wrong! Pls try again later."}S.value=!1};return(Z,v)=>{const G=c("v-card-title"),ie=c("v-alert"),ue=c("v-select"),T=c("v-col"),F=c("v-text-field"),Ie=c("v-textarea"),I=c("v-row"),ce=c("v-combobox"),ge=c("v-date-picker"),le=c("v-progress-linear"),he=c("v-card-text"),Pe=c("v-btn"),ve=c("v-card-actions"),me=c("v-card"),ye=c("v-overlay");return n(),f(Pe,{"prepend-icon":"mdi-account-credit-card"},{default:e(()=>[s("Update Payment "),t(ye,{modelValue:A.value,"onUpdate:modelValue":v[13]||(v[13]=p=>A.value=p),activator:"parent","scroll-strategy":"static","location-strategy":"connected"},{default:e(()=>[t(me,{"min-width":"250","max-width":"500",class:"p-2"},{default:e(()=>[t(G,null,{default:e(()=>[s("Update Payment Status")]),_:1}),t(he,{style:{"overflow-y":"auto","max-height":"320px"}},{default:e(()=>[z.value.length?(n(),f(ie,{key:0,type:"error",text:z.value,closable:"",class:"mb-2"},null,8,["text"])):d("",!0),t(I,null,{default:e(()=>[t(T,{cols:"12",class:"mt-2"},{default:e(()=>[t(ue,{modelValue:x.value,"onUpdate:modelValue":v[0]||(v[0]=p=>x.value=p),label:"Select Status",items:u($),variant:"outlined","hide-details":h.value.errors.status==null,"error-messages":h.value.errors.status,density:"compact"},null,8,["modelValue","items","hide-details","error-messages"])]),_:1}),x.value=="Paid"?(n(),f(T,{key:0,cols:"12",sm:"6"},{default:e(()=>[t(F,{modelValue:be.value,"onUpdate:modelValue":v[1]||(v[1]=p=>be.value=p),label:"Amount Paid",prefix:"₦",type:"text",variant:"outlined",density:"compact","hide-details":h.value.errors.amountPaid==null,"error-messages":h.value.errors.amountPaid},null,8,["modelValue","hide-details","error-messages"])]),_:1})):d("",!0),x.value=="Paid"?(n(),f(T,{key:1,cols:"12",sm:"6"},{default:e(()=>[t(ue,{modelValue:D.value,"onUpdate:modelValue":v[2]||(v[2]=p=>D.value=p),label:"Payment Method",items:u(L),variant:"outlined","hide-details":h.value.errors.method==null,"error-messages":h.value.errors.method,density:"compact"},null,8,["modelValue","items","hide-details","error-messages"])]),_:1})):d("",!0),x.value=="Paid"&&D.value=="Cash"?(n(),f(T,{key:2,cols:"12"},{default:e(()=>[t(Ie,{modelValue:_e.value,"onUpdate:modelValue":v[3]||(v[3]=p=>_e.value=p),label:"Who Received the Cash?",variant:"outlined","hide-details":h.value.errors.whoReceivedCash==null,"error-messages":h.value.errors.whoReceivedCash,clearable:""},null,8,["modelValue","hide-details","error-messages"])]),_:1})):d("",!0),D.value=="Bank Transfer"&&x.value=="Paid"?(n(),f(T,{key:3,cols:"12",sm:"6"},{default:e(()=>[yt,t(I,null,{default:e(()=>[t(T,{cols:"12"},{default:e(()=>[t(F,{modelValue:Y.value,"onUpdate:modelValue":v[4]||(v[4]=p=>Y.value=p),label:"Customer's Bank",type:"text",variant:"outlined",density:"compact","hide-details":h.value.errors.customerBank==null,"error-messages":h.value.errors.customerBank},null,8,["modelValue","hide-details","error-messages"])]),_:1}),t(T,{cols:"12"},{default:e(()=>[t(F,{modelValue:H.value,"onUpdate:modelValue":v[5]||(v[5]=p=>H.value=p),label:"Customer's Account Number",type:"tel",variant:"outlined",density:"compact","hide-details":h.value.errors.customerAccountNumber==null,"error-messages":h.value.errors.customerAccountNumber},null,8,["modelValue","hide-details","error-messages"])]),_:1}),t(T,{cols:"12"},{default:e(()=>[t(F,{modelValue:re.value,"onUpdate:modelValue":v[6]||(v[6]=p=>re.value=p),label:"Customer's Account Name",type:"text",variant:"outlined",density:"compact","hide-details":h.value.errors.customerAccountName==null,"error-messages":h.value.errors.customerAccountName},null,8,["modelValue","hide-details","error-messages"])]),_:1})]),_:1})]),_:1})):d("",!0),D.value=="Bank Transfer"&&x.value=="Paid"?(n(),f(T,{key:4,cols:"12",sm:"6"},{default:e(()=>[wt,t(I,null,{default:e(()=>[t(T,{cols:"12"},{default:e(()=>[t(ce,{modelValue:xe.value,"onUpdate:modelValue":v[7]||(v[7]=p=>xe.value=p),label:"Organization's Bank",items:u(B),"item-title":"name","item-value":"name",variant:"outlined",density:"compact","hide-details":h.value.errors.organizationBank==null,"error-messages":h.value.errors.organizationBank},null,8,["modelValue","items","hide-details","error-messages"])]),_:1}),t(T,{cols:"12"},{default:e(()=>[t(F,{modelValue:pe.value,"onUpdate:modelValue":v[8]||(v[8]=p=>pe.value=p),label:"Organization's Account Number",type:"tel",variant:"outlined",density:"compact","hide-details":h.value.errors.organizationAccountNumber==null,"error-messages":h.value.errors.organizationAccountNumber},null,8,["modelValue","hide-details","error-messages"])]),_:1}),t(T,{cols:"12"},{default:e(()=>[t(F,{modelValue:fe.value,"onUpdate:modelValue":v[9]||(v[9]=p=>fe.value=p),label:"Organization's Account Name",type:"text",variant:"outlined",density:"compact","hide-details":h.value.errors.organizationAccountName==null,"error-messages":h.value.errors.organizationAccountName},null,8,["modelValue","hide-details","error-messages"])]),_:1})]),_:1})]),_:1})):d("",!0),D.value=="Bank Transfer"&&x.value=="Paid"?(n(),f(T,{key:5,cols:"12"},{default:e(()=>[t(F,{modelValue:ke.value,"onUpdate:modelValue":v[10]||(v[10]=p=>ke.value=p),label:"Transaction Reference",type:"text",variant:"outlined",density:"compact","hide-details":h.value.errors.transactionReference==null,"error-messages":h.value.errors.transactionReference},null,8,["modelValue","hide-details","error-messages"])]),_:1})):d("",!0),x.value=="Paid"?(n(),f(T,{key:6,cols:"12"},{default:e(()=>[t(ge,{modelValue:g.value,"onUpdate:modelValue":v[11]||(v[11]=p=>g.value=p),max:C.value,"show-adjacent-months":"",title:"Transaction Date","hide-details":h.value.errors.paymentDate==null,"error-messages":h.value.errors.paymentDate},null,8,["modelValue","max","hide-details","error-messages"])]),_:1})):d("",!0),t(T,{cols:"12"},{default:e(()=>[S.value?(n(),f(le,{key:0,indeterminate:"",color:"red"})):d("",!0),M.value.length?(n(),k("p",xt,i(M.value),1)):d("",!0)]),_:1})]),_:1})]),_:1}),t(ve,null,{default:e(()=>[t(Pe,{onClick:Q},{default:e(()=>[s("Update")]),_:1}),t(Pe,{color:"red",onClick:v[12]||(v[12]=p=>A.value=!A.value)},{default:e(()=>[s("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})}}},Vt=l("hr",null,null,-1),Ct=l("b",null,"Client",-1),Pt=l("br",null,null,-1),Nt=l("br",null,null,-1),Dt=l("b",null,"Delivery Address",-1),Tt=l("br",null,null,-1),Ot=l("b",null,"Origin Branch",-1),$t=l("br",null,null,-1),At=l("b",null,"Processing Branch",-1),St=l("br",null,null,-1),Ut=l("b",null,"Order Number",-1),Bt=l("br",null,null,-1),Rt={key:0,class:"text-center font-bold mt-1 mb-0"},zt={key:1,class:"text-center text-red mt-1 mb-0"},Mt=l("b",null,"Order Name",-1),It=l("br",null,null,-1),Ft=l("b",null,"Price",-1),Et=l("br",null,null,-1),Yt={key:0,class:"text-center font-bold"},Ht={key:1,class:"text-center text-red mt-1 mb-0"},jt=l("b",null,"Invoice Status",-1),Lt=l("br",null,null,-1),Wt=l("b",null,"Payment Method",-1),qt=l("br",null,null,-1),Gt=l("b",null,"Product",-1),Qt=l("br",null,null,-1),Zt=l("b",null,"Quantity",-1),Jt=l("br",null,null,-1),Kt=l("b",null,"Order Status",-1),Xt=l("br",null,null,-1),ea=l("b",null,"Current Process",-1),ta=l("br",null,null,-1),aa={key:0,class:"my-2"},la={key:1,class:"text-red"},oa=l("b",null,"Order Created",-1),na=l("br",null,null,-1),sa=l("b",null,"Last Updated",-1),ra=l("br",null,null,-1),da=l("b",null,"Target Delivery Date",-1),ia=l("br",null,null,-1),ua=l("b",null,"WayBill Number",-1),ca=l("br",null,null,-1),va={key:0,class:"text-center text-red mt-1 mb-0"},ma=l("b",null,"Note",-1),pa=l("br",null,null,-1),fa=l("hr",null,null,-1),_a=l("p",null,[s(" Why do you want to place this order on hold?"),l("br")],-1),ba={key:0,class:"text-center font-bold pt-2"},ga={key:1,class:"text-center text-red mt-1 mb-0"},ha=l("p",null,"Are you sure you want to reactivate this order?",-1),ya={key:0,class:"text-center"},wa={key:1,class:"text-center font-bold"},xa={key:2,class:"text-center text-red mt-1 mb-0"},ka=l("p",null,"Are you sure you want to cancel this order?",-1),Va={key:0,class:"text-center"},Ca={key:1,class:"text-center font-bold"},Pa={key:2,class:"text-center text-red mt-1 mb-0"},Na={__name:"OrderCard",setup(j){const R=w().props.auth.user,r=w().props.order,m=w().props.orderDetail,O=w().props.hasInvoice,D=w().props.canGenerateInvoice,x=o(w().props.invoicePaid),L=o(w().props.invoice),$=w().props.canEditOrder,B=w().props.canHoldOrder,V=w().props.canCancelOrder,g=w().props.canEditReferenceNumber,C=w().props.canEditPrice,A=w().props.canEditWaybill,z=w().props.canForwardToNextProcess,M=o(!1),S=o(""),Y=o(!1),H=o(r.order_status.name),re=o(r.process?r.process.name:"-"),xe=r.waybill_number,pe=o(r.human_forwarding),fe=w().props.invoice,_e=o(fe==null?"":fe.payment_method),be=o(w().props.canApproveOfflinePayment),ke=P=>{be.value=!1,x.value=P.invoicePaid,_e.value=P.paymentMethod},h=new Intl.NumberFormat("en-US",{style:"decimal",minimumFractionDigits:0,maximumFractionDigits:0}),Q=o("");let de=null;const $e=async()=>{S.value="",Q.value="",Y.value=!0;const P={orderId:r.id};de&&de.cancel("Request cancelled by user"),de=E.CancelToken.source();try{const a=await E.put(route("order.cancel",[r.id]),P,{headers:{"Content-Type":"application/json"},cancelToken:de.token});Y.value=!1,S.value=a.data.response,H.value=a.data.orderStatus}catch(a){Y.value=!1,a.response&&a.response.status===422?Q.value=a.response.data.message:Q.value="Something went wrong! Pls try again later."}setTimeout(()=>{S.value="",Q.value="",M.value=!1},5e3)},Ae=at({orderId:r.id}),Se=()=>{Ae.post(route("invoice.create"))},W=o(r.hold_reason),Ve=o(""),X=o(!1),ee=o(!1),U=o(r.paused),q=o(""),Ue=async()=>{q.value="",X.value=!0;const P={reason:W.value};try{const a=await E.put(route("order.hold",[r.id]),P,{headers:{"Content-Type":"application/json"}});X.value=!1,a.data&&a.data.status=="success"&&(U.value=!0,ee.value=!1)}catch(a){X.value=!1,a.response&&a.response.status===422?q.value=a.response.data.message:q.value="Something went wrong! Pls try again later."}setTimeout(()=>{q.value="",ee.value=!1},5e3)},Ce=o(""),te=o(!1),ae=o(!1),Z=o(""),v=async()=>{te.value=!0;const P={};try{const a=await E.put(route("order.reactivate",[r.id]),P,{headers:{"Content-Type":"application/json"}});te.value=!1,a.data&&a.data.status=="success"&&(U.value=!1,W.value="",ae.value=!1)}catch(a){te.value=!1,a.response&&a.response.status===422?Z.value=a.response.data.message:Z.value="Something went wrong! Pls try again later."}setTimeout(()=>{Z.value="",ae.value=!1},5e3)},G=o(r.order_number),ie=o(!1),ue=o(""),T=o(""),F=o(!1),Ie=async()=>{T.value="",ie.value=!0;const P={orderNumber:G.value};try{const a=await E.put(route("order.set-reference",[r.id]),P,{headers:{"Content-Type":"application/json"}});ie.value=!1,a.data&&a.data.status=="success"&&(ue.value=a.data.response)}catch(a){ie.value=!1,a.response&&a.response.status===422?T.value=a.response.data.message:T.value="Something went wrong! Pls try again later."}setTimeout(()=>{ue.value="",T.value="",F.value=!1},5e3)},I=o(r.total_cost),ce=o(!1),ge=o(""),le=o(""),he=o(!1),Pe=async()=>{le.value="",ce.value=!0;const P={price:I.value};try{const a=await E.put(route("order.set-price",[r.id]),P,{headers:{"Content-Type":"application/json"}});ce.value=!1,a.data&&a.data.status=="success"&&(ge.value=a.data.response)}catch(a){ce.value=!1,a.response&&a.response.status===422?le.value=a.response.data.message:le.value="Something went wrong! Pls try again later."}setTimeout(()=>{ge.value="",le.value="",he.value=!1},5e3)},ve=o(xe),me=o(!1),ye=o(""),p=o(""),Be=o(!1),Ze=async()=>{me.value=!0,p.value="";const P={waybillNumber:ve.value};try{const a=await E.put(route("order.save-waybill",[r.id]),P,{headers:{"Content-Type":"application/json"}});me.value=!1,a.data&&a.data.status=="success"&&(ye.value=a.data.response)}catch(a){me.value=!1,a.response&&a.response.status===422?p.value=a.response.data.message:p.value="Something went wrong! Pls try again later."}setTimeout(()=>{ye.value="",p.value="",Be.value=!1},5e3)},Re=o(!1),Ne=o(""),we=o(""),Je=async()=>{Re.value=!0,Ne.value="",we.value="";const P={orderId:r.id};try{const a=await E.post(route("order.process.forward"),P,{headers:{"Content-Type":"application/json"}});a.data&&a.data.status=="success"&&(Ne.value=a.data.message,re.value=a.data.currentProcess,pe.value=!1)}catch(a){a.response&&a.response.status===422?we.value=a.response.data.message:we.value="Something went wrong! Pls try again later."}Re.value=!1,setTimeout(()=>{Ne.value="",we.value=""},7e3)},Ke=()=>{const P=r.user.name,a=r.user.mobile,J=r.delivery_address,K=r.source_branch?r.source_branch.name:"-",_=r.processing_branch.name,b=G.value,N=r.name,De=r.item.name,oe=r.quantity,ne=I.value?`₦${h.format(I.value)}`:"--",Fe=U.value?"On Hold":H.value,Te=re.value,Ee=se(r.created_at).format("MMMM DD, YYYY"),ze=se(m.date).format("MMMM DD, YYYY"),y=ve.value??"NOT SET",Le=r.note,We=window.open("","","height=800,width=900"),qe=w().props.site.name;We.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Order Card - ${b}</title>
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
                    background: ${U.value?"#ef4444":"#10b981"};
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
                    <h1>${qe}</h1>
                    <div class="order-number">
                        <div class="order-number-label">Order Number</div>
                        <div class="order-number-value">${b}</div>
                    </div>
                </div>
                
                <div class="content">
                    <div class="status-bar">
                        <div>
                            <div class="status-label">Current Status</div>
                            <div class="status-value">${Fe}</div>
                        </div>
                        <div>
                            <div class="status-label">Process</div>
                            <div class="status-value">${Te}</div>
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
                                <div class="info-value">${J}</div>
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
                                <div class="info-value">${oe}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Total Cost</div>
                                <div class="info-value large">${ne}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Processing Information</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Origin Branch</div>
                                <div class="info-value">${K}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Processing Branch</div>
                                <div class="info-value">${_}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Order Date</div>
                                <div class="info-value">${Ee}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">${H.value==="Delivered"||H.value==="Fulfilled"?"Delivery Date":"Estimated Delivery Date"}</div>
                                <div class="info-value">${ze}</div>
                            </div>
                            <div class="info-item full-width">
                                <div class="info-label">Waybill Number</div>
                                <div class="info-value">${y}</div>
                            </div>
                        </div>
                    </div>
                    
                    ${Le?`
                        <div class="section">
                            <div class="section-title">Special Notes</div>
                            <div class="notes-box">
                                <div class="notes-content">${Le}</div>
                            </div>
                        </div>
                    `:""}
                </div>
                
                <div class="footer">
                    <div class="footer-text">This is an official order card from ${qe}</div>
                    <div class="print-date">Printed on ${se().format("MMMM DD, YYYY [at] h:mm A")}</div>
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
    `),We.document.close()};return(P,a)=>{const J=c("VCardText"),K=c("VCard"),_=c("VCol"),b=c("VRow"),N=c("VBtn"),De=c("VTextField"),oe=c("VCardActions"),ne=c("VOverlay"),Fe=c("v-progress-linear"),Te=c("VCardTitle"),Ee=c("VTextarea"),ze=c("v-progress-circular");return n(),f(Oe,{snippetTitle:"Order Card",cardColor:U.value?"bg-gray-700 text-white":"bg-white"},{default:e(()=>[W.value&&W.value.length&&U.value?(n(),f(b,{key:0},{default:e(()=>[t(_,{cols:"12"},{default:e(()=>[t(K,{color:"red"},{default:e(()=>[t(J,null,{default:e(()=>[s(i(W.value),1)]),_:1})]),_:1})]),_:1})]),_:1})):d("",!0),t(b,null,{default:e(()=>[t(_,{class:"text-right"},{default:e(()=>[u($)&&G.value&&G.value.length&&I.value>0?(n(),f(N,{key:0,color:"blue-darken-3","prepend-icon":"mdi-printer",onClick:Ke},{default:e(()=>[s(" Print Card ")]),_:1})):d("",!0)]),_:1})]),_:1}),Vt,t(b,{id:"order-card"},{default:e(()=>[t(_,{cols:"12",md:"6"},{default:e(()=>[t(b,null,{default:e(()=>[t(_,null,{default:e(()=>[Ct,Pt,s(" "+i(u(r).user.name),1),Nt,s(" "+i(u(r).user.mobile),1)]),_:1})]),_:1}),t(b,null,{default:e(()=>[t(_,null,{default:e(()=>[Dt,Tt,s(" "+i(u(r).delivery_address),1)]),_:1})]),_:1}),t(b,null,{default:e(()=>[t(_,null,{default:e(()=>[Ot,$t,s(" "+i(u(r).source_branch?u(r).source_branch.name:"-"),1)]),_:1})]),_:1}),t(b,null,{default:e(()=>[t(_,null,{default:e(()=>[At,St,s(" "+i(u(r).processing_branch.name),1)]),_:1})]),_:1}),t(b,null,{default:e(()=>[t(_,null,{default:e(()=>[Ut,Bt,s(" "+i(G.value)+" ",1),u(g)&&!U.value?(n(),f(N,{key:0,"prepend-icon":"mdi-pencil",class:"mr-2 no-padding no-border",elevation:"0"},{default:e(()=>[t(ne,{modelValue:F.value,"onUpdate:modelValue":a[2]||(a[2]=y=>F.value=y),activator:"parent","location-strategy":"connected","scroll-strategy":"static"},{default:e(()=>[t(K,{"max-width":"400",class:"p-1"},{default:e(()=>[t(J,{class:"pb-0"},{default:e(()=>[t(De,{modelValue:G.value,"onUpdate:modelValue":a[0]||(a[0]=y=>G.value=y),"hide-details":"",id:"order-number",variant:"outlined",label:"Order Number",style:{"min-width":"200px"},loading:ie.value},null,8,["modelValue","loading"]),ue.value.length?(n(),k("p",Rt,i(ue.value),1)):d("",!0),T.value.length?(n(),k("p",zt,i(T.value),1)):d("",!0)]),_:1}),t(oe,null,{default:e(()=>[t(N,{color:"blue-darken-1 m-1",onClick:Ie,disabled:ie.value},{default:e(()=>[s("Save")]),_:1},8,["disabled"]),t(N,{color:"grey-darken-1 m-1",onClick:a[1]||(a[1]=y=>F.value=!1)},{default:e(()=>[s("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):d("",!0)]),_:1})]),_:1}),t(b,null,{default:e(()=>[t(_,null,{default:e(()=>[Mt,It,s(" "+i(u(r).name),1)]),_:1})]),_:1}),t(b,null,{default:e(()=>[P.$page.props.auth.user.role!="Customer"?(n(),f(_,{key:0},{default:e(()=>[Ft,Et,s(" ₦"+i(I.value?u(h).format(I.value):" --:--")+" ",1),u(C)&&!U.value?(n(),f(N,{key:0,elevation:"0","prepend-icon":"mdi-pencil",class:"mr-2 no-padding no-border"},{default:e(()=>[t(ne,{modelValue:he.value,"onUpdate:modelValue":a[5]||(a[5]=y=>he.value=y),activator:"parent","location-strategy":"connected","scroll-strategy":"static"},{default:e(()=>[t(K,{"max-width":"400",class:"p-1"},{default:e(()=>[t(J,{class:"pb-0"},{default:e(()=>[t(De,{modelValue:I.value,"onUpdate:modelValue":a[3]||(a[3]=y=>I.value=y),"hide-details":"",id:"price",type:"number",variant:"outlined",label:"Price",prefix:"₦",style:{width:"200px"},loading:ce.value},null,8,["modelValue","loading"]),ge.value.length?(n(),k("div",Yt,i(ge.value),1)):d("",!0),le.value.length?(n(),k("p",Ht,i(le.value),1)):d("",!0)]),_:1}),t(oe,null,{default:e(()=>[t(N,{color:"blue-darken-1 m-1",onClick:Pe,disabled:ce.value},{default:e(()=>[s("Save")]),_:1},8,["disabled"]),t(N,{color:"grey-darken-1 m-1",onClick:a[4]||(a[4]=y=>he.value=!1)},{default:e(()=>[s("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):d("",!0)]),_:1})):d("",!0)]),_:1}),I.value&&G.value&&!U.value&&!u(O)&&u(D)&&H.value!="Cancelled"?(n(),f(b,{key:0},{default:e(()=>[t(_,{cols:"12"},{default:e(()=>[t(N,{color:"blue-darken-1",onClick:Se},{default:e(()=>[s("Issue Invoice")]),_:1})]),_:1})]),_:1})):d("",!0),u(O)?(n(),f(b,{key:1},{default:e(()=>[t(_,{cols:"12"},{default:e(()=>[jt,Lt,s(" "+i(x.value?"Paid":"Unpaid"),1)]),_:1}),t(_,{cols:"12"},{default:e(()=>[t(u(He),{class:"font-bold underline",href:P.route("invoice",[L.value.id])},{default:e(()=>[s("Open Invoice")]),_:1},8,["href"])]),_:1})]),_:1})):d("",!0),u(O)&&x.value?(n(),f(b,{key:2},{default:e(()=>[t(_,{cols:"12"},{default:e(()=>[Wt,qt,s(" "+i(_e.value),1)]),_:1})]),_:1})):d("",!0),be.value?(n(),f(b,{key:3},{default:e(()=>[t(_,{cols:"12"},{default:e(()=>[t(kt,{onStatusUpdated:ke})]),_:1})]),_:1})):d("",!0)]),_:1}),t(_,{cols:"12",md:"6"},{default:e(()=>[t(b,null,{default:e(()=>[t(_,null,{default:e(()=>[Gt,Qt,s(" "+i(P.$page.props.order.item.name),1)]),_:1})]),_:1}),t(b,null,{default:e(()=>[t(_,null,{default:e(()=>[Zt,Jt,s(" "+i(P.$page.props.order.quantity),1)]),_:1})]),_:1}),t(b,null,{default:e(()=>[t(_,null,{default:e(()=>[Kt,Xt,s(" "+i(U.value?"On Hold":H.value),1)]),_:1})]),_:1}),t(b,null,{default:e(()=>[t(_,null,{default:e(()=>[ea,ta,s(" "+i(re.value),1)]),_:1})]),_:1}),u(z)&&pe.value?(n(),f(b,{key:0},{default:e(()=>[t(_,null,{default:e(()=>[t(N,{"prepend-icon":"mdi-play",color:"blue-darken-3",onClick:Je,disabled:Re.value},{default:e(()=>[s("Start Next Process")]),_:1},8,["disabled"]),Re.value?(n(),k("p",aa,[t(Fe,{color:"red",indeterminate:""})])):d("",!0),we.value.length?(n(),k("p",la,i(we.value),1)):d("",!0)]),_:1})]),_:1})):d("",!0),Ne.value.length?(n(),f(b,{key:1},{default:e(()=>[t(_,null,{default:e(()=>[s(i(Ne.value),1)]),_:1})]),_:1})):d("",!0),u(R).isAdmin?(n(),f(b,{key:2},{default:e(()=>[t(_,null,{default:e(()=>[t(u(He),{class:"font-bold underline",href:P.route("tasks.order.dashboard",[u(r).id])},{default:e(()=>[s("View Tasks")]),_:1},8,["href"])]),_:1})]),_:1})):d("",!0),t(b,null,{default:e(()=>[t(_,null,{default:e(()=>[oa,na,s(" "+i(u(se)(u(r).created_at).calendar()),1)]),_:1})]),_:1}),t(b,null,{default:e(()=>[t(_,null,{default:e(()=>[sa,ra,s(" "+i(u(se)(u(r).updated_at).fromNow()),1)]),_:1})]),_:1}),t(b,null,{default:e(()=>[t(_,null,{default:e(()=>[da,ia,s(" "+i(u(se)(u(m).date).format("LL")),1)]),_:1})]),_:1}),t(b,null,{default:e(()=>[t(_,null,{default:e(()=>[ua,ca,s(" "+i(ve.value??"NOT SET")+" ",1),u(A)&&!U.value?(n(),f(N,{key:0,elevation:"0","prepend-icon":"mdi-pencil",class:"mr-2 no-padding no-border"},{default:e(()=>[t(ne,{modelValue:Be.value,"onUpdate:modelValue":a[8]||(a[8]=y=>Be.value=y),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(K,{"max-width":"400",class:"p-1"},{default:e(()=>[t(J,{class:"pb-0"},{default:e(()=>[t(De,{modelValue:ve.value,"onUpdate:modelValue":a[6]||(a[6]=y=>ve.value=y),"hide-details":"",id:"order-number",variant:"outlined",label:"Waybill Number",style:{"min-width":"200px"},loading:me.value},null,8,["modelValue","loading"]),lt(l("div",{class:"text-center font-bold"},i(ye.value),513),[[ot,ye.value.length]]),p.value.length?(n(),k("p",va,i(p.value),1)):d("",!0)]),_:1}),t(oe,null,{default:e(()=>[t(N,{color:"blue-darken-1 m-1",onClick:Ze,disabled:me.value},{default:e(()=>[s("Save")]),_:1},8,["disabled"]),t(N,{color:"grey-darken-1 m-1",onClick:a[7]||(a[7]=y=>Be.value=!1)},{default:e(()=>[s("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):d("",!0)]),_:1})]),_:1})]),_:1})]),_:1}),t(b,null,{default:e(()=>[t(_,null,{default:e(()=>[ma,pa,s(" "+i(u(r).note),1)]),_:1})]),_:1}),fa,t(b,null,{default:e(()=>[t(_,null,{default:e(()=>[!U.value&&u(B)?(n(),f(N,{key:0,color:"grey-darken-3","prepend-icon":"mdi-pause",class:"mr-2 mb-2"},{default:e(()=>[s(" Hold Order "),t(ne,{modelValue:ee.value,"onUpdate:modelValue":a[11]||(a[11]=y=>ee.value=y),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(K,{"max-width":"400",class:"p-3"},{default:e(()=>[t(Te,null,{default:e(()=>[s("What's Wrong?")]),_:1}),t(J,null,{default:e(()=>[_a,t(Ee,{modelValue:W.value,"onUpdate:modelValue":a[9]||(a[9]=y=>W.value=y),"hide-details":"",id:"hold-order",variant:"outlined",label:"Leave a note for team members",loading:X.value},null,8,["modelValue","loading"]),Ve.value.length?(n(),k("p",ba,i(Ve.value),1)):d("",!0),q.value&&q.value.length?(n(),k("p",ga,i(q.value),1)):d("",!0)]),_:1}),t(oe,null,{default:e(()=>[t(N,{color:"red-darken-1",onClick:Ue,disabled:X.value},{default:e(()=>[s("Continue")]),_:1},8,["disabled"]),t(N,{color:"blue-darken-1",onClick:a[10]||(a[10]=y=>ee.value=!1)},{default:e(()=>[s("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):d("",!0),U.value&&u(R).isAdmin&&H.value!="Cancelled"?(n(),f(N,{key:1,color:"white",class:"mr-2 mb-2","prepend-icon":"mdi-play"},{default:e(()=>[s(" Reactivate "),t(ne,{modelValue:ae.value,"onUpdate:modelValue":a[13]||(a[13]=y=>ae.value=y),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(K,{"max-width":"400",class:"p-3"},{default:e(()=>[t(Te,null,{default:e(()=>[s("Confirm Action!")]),_:1}),t(J,null,{default:e(()=>[ha,te.value?(n(),k("p",ya,[t(ze,{color:"red",indeterminate:""})])):d("",!0),Ce.value.length?(n(),k("p",wa,i(Ce.value),1)):d("",!0),Z.value&&Z.value.length?(n(),k("p",xa,i(Z.value),1)):d("",!0)]),_:1}),t(oe,null,{default:e(()=>[t(N,{color:"red-darken-1 m-1",onClick:v,disabled:te.value},{default:e(()=>[s("Yes Proceed")]),_:1},8,["disabled"]),t(N,{color:"blue-darken-1 m-1",onClick:a[12]||(a[12]=y=>ae.value=!1)},{default:e(()=>[s("Don't")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):d("",!0),u(V)?(n(),f(N,{key:2,color:"red-darken-1",class:"mr-2 mb-2","prepend-icon":"mdi-cancel"},{default:e(()=>[s(" Cancel Order "),t(ne,{modelValue:M.value,"onUpdate:modelValue":a[15]||(a[15]=y=>M.value=y),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(K,{"max-width":"400",class:"p-3"},{default:e(()=>[t(Te,null,{default:e(()=>[s("Heads Up!")]),_:1}),t(J,null,{default:e(()=>[ka,Y.value?(n(),k("p",Va,[t(ze,{color:"red",indeterminate:""})])):d("",!0),S.value.length?(n(),k("p",Ca,i(S.value),1)):d("",!0),Q.value&&Q.value.length?(n(),k("p",Pa,i(Q.value),1)):d("",!0)]),_:1}),t(oe,null,{default:e(()=>[t(N,{color:"red-darken-1 m-1",onClick:$e,disabled:Y.value},{default:e(()=>[s("Yes Proceed")]),_:1},8,["disabled"]),t(N,{color:"blue-darken-1 m-1",onClick:a[14]||(a[14]=y=>M.value=!1)},{default:e(()=>[s("Don't")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):d("",!0)]),_:1})]),_:1})]),_:1},8,["cardColor"])}}},je=j=>(st("data-v-d6ad9e94"),j=j(),rt(),j),Da=je(()=>l("tr",null,[l("td",null,"Process"),l("td",null,"Completed by"),l("td",null,"Date")],-1)),Ta={key:0,class:"text-right mb-2"},Oa=je(()=>l("h3",{class:"text-red"},"No asset was uploaded for this order!",-1)),$a=je(()=>l("p",null,"If you do not have the image files, kindly contact the client using the phone number below.",-1)),Aa={__name:"Detail",setup(j){w().props.order;const R=w().props.orderDetail,r=w().props.activities,m=Ge({orderFiles:R.files}),O=D=>{for(let x=0;x<m.orderFiles.length;x++)m.orderFiles[x].file.id==D.id&&m.orderFiles.splice(x,1)};return(D,x)=>{const L=c("THead"),$=c("TBody"),B=c("VTable"),V=c("VCol"),g=c("VRow");return n(),k(Me,null,[t(u(nt),{title:"Order"}),t(dt,null,{default:e(()=>[t(u(He),{href:D.route("orders"),class:"font-bold"},{default:e(()=>[s("Back")]),_:1},8,["href"]),t(g,null,{default:e(()=>[t(V,{cols:"12",sm:"6"},{default:e(()=>[t(Na),u(r).length?(n(),f(Oe,{key:0,"snippet-title":"Activities"},{default:e(()=>[t(B,null,{default:e(()=>[t(L,null,{default:e(()=>[Da]),_:1}),t($,null,{default:e(()=>[(n(!0),k(Me,null,Ye(u(r),C=>(n(),k("tr",{key:C.id},[l("td",null,i(C.process.name),1),l("td",null,i(C.staff.name),1),l("td",null,i(u(se)(C.created_at).format("MM/DD/YYYY, h:mm A")),1)]))),128))]),_:1})]),_:1})]),_:1})):d("",!0)]),_:1}),t(V,{cols:"12",sm:"6"},{default:e(()=>[t(bt),t(Oe,{"snippet-title":"Customer Feedback"},{default:e(()=>[t(ct)]),_:1})]),_:1})]),_:1}),t(Oe,{"snippet-title":"Order Assets"},{default:e(()=>[m.orderFiles.length?(n(),k("div",Ta,[t(ht,{files:m.orderFiles},null,8,["files"])])):d("",!0),m.orderFiles.length?(n(),f(g,{key:1},{default:e(()=>[(n(!0),k(Me,null,Ye(m.orderFiles,(C,A)=>(n(),f(V,{cols:"12",lg:"6",key:A},{default:e(()=>[t(ut,{orderImage:C.file,view:"Detail",onPageRemoved:O,onPageDataUpdated:z=>{D.updatePageData(z,C)}},null,8,["orderImage","onPageDataUpdated"])]),_:2},1024))),128))]),_:1})):(n(),f(g,{key:2},{default:e(()=>[t(V,{class:"text-center"},{default:e(()=>[Oa,$a]),_:1})]),_:1}))]),_:1})]),_:1})],64)}}},Ea=Qe(Aa,[["__scopeId","data-v-d6ad9e94"]]);export{Ea as default};
