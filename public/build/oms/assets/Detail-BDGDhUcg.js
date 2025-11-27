import{Q as h,s as Le,i as o,v as Xe,r as v,f as p,o as s,w as e,c as x,b as l,F as Re,m as ze,t as i,u as c,a as t,d as n,g as et,x as B,h as d,T as tt,j as Fe,D as at,G as lt,Z as ot,p as st,e as nt}from"./app-Ce_FSW7Q.js";import{_ as rt}from"./BackendLayout-CDWLG7cE.js";import{F as dt,_ as it}from"./OrderForm.vue_vue_type_script_setup_true_lang-DLYFlzuh.js";import{_ as we}from"./Panel.vue_vue_type_script_setup_true_lang-ClTKqxeY.js";import{h as ee}from"./indigo-DizSgVMf.js";import{_ as je}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{C as ut}from"./CustomerFeedback-DorOHT1q.js";import"./ApplicationLogo-DK1WH_OI.js";const ct={key:0},vt={class:"font-bold"},pt={key:1},mt={class:"text-right"},ft={__name:"CommunicationLog",setup(Y){const M=h().props.order,r=h().props.auth.user,T=Le({newMessage:""}),k=o([]),P=async()=>{const U={message:T.newMessage,orderId:M.id},N=(await B.post(route("order.log.write"),U,{headers:{"Content-Type":"application/json"}})).data;N.status!==void 0&&N.status=="success"&&(T.newMessage="",D())},D=async()=>{const U=await B.get(route("order.log",[M.id]));k.value=U.data};return Xe(()=>{D()}),(U,V)=>{const N=v("VCard"),O=v("VTextarea"),$=v("VBtn");return s(),p(we,{"snippet-title":"Communication Log"},{default:e(()=>[k.value.length?(s(),x("div",ct,[(s(!0),x(Re,null,ze(k.value,(b,R)=>(s(),x("div",{key:R,class:"task-card px-2"},[l("p",vt,i(c(ee)(b.created_at).calendar())+", "+i(b.user.mobile==c(r).mobile?"I":b.user.name)+" wrote:",1),l("p",null,i(b.message),1)]))),128))])):(s(),x("div",pt,[t(N,{class:"mb-2 p-2",color:"grey-lighten-3"},{default:e(()=>[n(" Leave a note for other team members working on this Order. ")]),_:1})])),l("div",null,[l("form",{onSubmit:et(P,["prevent"])},[t(O,{modelValue:T.newMessage,"onUpdate:modelValue":V[0]||(V[0]=b=>T.newMessage=b),label:"Type Comment",variant:"outlined",density:"compact",rows:"2","max-rows":"4","auto-grow":"",clearable:""},null,8,["modelValue"]),l("div",mt,[t($,{"prepend-icon":"mdi-note-plus",color:"black",type:"submit"},{default:e(()=>[n("Add Note")]),_:1})])],32)])]),_:1})}}},_t=je(ft,[["__scopeId","data-v-b0efe064"]]),gt=l("div",null,"To keep things organized, we recommend creating a dedicated folder to store all assets for this order.",-1),bt={__name:"DownloadAllMediaBtn",props:{files:Array},setup(Y){const M=route("file.fetch"),r=o(!1),T=Y,k=o(!1),P=(V,N,O)=>{k.value=!0;const $=`${M}?filepath=${encodeURIComponent(V)}&type=${N}`;B.get($,{responseType:"blob"}).then(b=>{const R=new Blob([b.data],{type:b.headers["content-type"]});dt.saveAs(R,O),k.value=!1})},D=async()=>{r.value=!1,T.files.forEach(V=>{const N=U(V.file.uploadedFile),O=V.file.pageNumber?`page-${V.file.pageNumber}.${N}`:V.file.fileInfo.name;P(V.file.uploadedFile,V.file.fileInfo.type,O)})},U=V=>V.split(".").pop().split(/\#|\?/)[0];return(V,N)=>{const O=v("VCardTitle"),$=v("VCardText"),b=v("VBtn"),R=v("VCardActions"),H=v("VCard"),F=v("VOverlay");return s(),p(b,{color:"blue","prepend-icon":"mdi-download",disabled:k.value},{default:e(()=>[n(" Download All "),t(F,{modelValue:r.value,"onUpdate:modelValue":N[0]||(N[0]=I=>r.value=I),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(H,{class:"p-3","max-width":"400"},{default:e(()=>[t(O,null,{default:e(()=>[n("Heads Up!")]),_:1}),t($,null,{default:e(()=>[l("p",null,"You're about to start downloading "+i(T.files.length)+" files.",1),gt]),_:1}),t(R,null,{default:e(()=>[t(b,{onClick:D,color:"blue","prepend-icon":"mdi-download",disabled:k.value},{default:e(()=>[n(" Start Download ")]),_:1},8,["disabled"])]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1},8,["disabled"])}}},yt=l("h4",null,"From",-1),ht=l("h4",null,"To",-1),xt={key:1},wt={__name:"OfflinePayment",emits:["statusUpdated"],setup(Y,{emit:M}){const r=M,T=h().props.order,k=o(""),P=o(""),D=h().props.paymentMethods,U=h().props.paymentStatuses,V=o(new Date),N=o(new Date),O=o(!1),$=o(""),b=o(""),R=o(!1),H=o(""),F=o(""),I=o(""),L=o(""),j=o(""),te=o(""),me=o(""),se=o(T.total_cost),ne=o(""),g=o({errors:{}}),ke=async()=>{const Ve={orderId:T.id,status:P.value,paymentMethod:k.value,amountPaid:se.value,customerBank:H.value,customerAccountName:I.value,customerAccountNumber:F.value,organizationBank:L.value,organizationAccountName:te.value,organizationAccountNumber:j.value,whoReceivedCash:me.value,paymentDate:V.value,transactionReference:ne.value};R.value=!0,$.value="",b.value="",g.value.errors={};try{const u=await B.post(route("order.update-payment"),Ve,{headers:{"Content-Type":"application/json"}});u.data&&u.data.status=="success"&&(b.value=u.data.message,r("statusUpdated",{paymentMethod:k.value,invoicePaid:P.value=="Paid"}))}catch(u){u.response&&u.response.status===422?($.value=u.response.data.message,g.value.errors=u.response.data.errors):$.value="Something went wrong! Pls try again later."}R.value=!1};return(Ve,u)=>{const W=v("v-card-title"),re=v("v-alert"),Ce=v("v-select"),S=v("v-col"),E=v("v-text-field"),q=v("v-textarea"),de=v("v-row"),ae=v("v-date-picker"),le=v("v-progress-linear"),A=v("v-card-text"),z=v("v-btn"),Be=v("v-card-actions"),Pe=v("v-card"),oe=v("v-overlay");return s(),p(z,{"prepend-icon":"mdi-account-credit-card"},{default:e(()=>[n("Update Payment "),t(oe,{modelValue:O.value,"onUpdate:modelValue":u[13]||(u[13]=m=>O.value=m),activator:"parent","scroll-strategy":"static","location-strategy":"connected"},{default:e(()=>[t(Pe,{"min-width":"250","max-width":"500",class:"p-2"},{default:e(()=>[t(W,null,{default:e(()=>[n("Update Payment Status")]),_:1}),t(A,{style:{"overflow-y":"auto","max-height":"320px"}},{default:e(()=>[$.value.length?(s(),p(re,{key:0,type:"error",text:$.value,closable:"",class:"mb-2"},null,8,["text"])):d("",!0),t(de,null,{default:e(()=>[t(S,{cols:"12",class:"mt-2"},{default:e(()=>[t(Ce,{modelValue:P.value,"onUpdate:modelValue":u[0]||(u[0]=m=>P.value=m),label:"Select Status",items:c(U),variant:"outlined","hide-details":g.value.errors.status==null,"error-messages":g.value.errors.status,density:"compact"},null,8,["modelValue","items","hide-details","error-messages"])]),_:1}),P.value=="Paid"?(s(),p(S,{key:0,cols:"12",sm:"6"},{default:e(()=>[t(E,{modelValue:se.value,"onUpdate:modelValue":u[1]||(u[1]=m=>se.value=m),label:"Amount Paid",prefix:"₦",type:"text",variant:"outlined",density:"compact","hide-details":g.value.errors.amountPaid==null,"error-messages":g.value.errors.amountPaid},null,8,["modelValue","hide-details","error-messages"])]),_:1})):d("",!0),P.value=="Paid"?(s(),p(S,{key:1,cols:"12",sm:"6"},{default:e(()=>[t(Ce,{modelValue:k.value,"onUpdate:modelValue":u[2]||(u[2]=m=>k.value=m),label:"Payment Method",items:c(D),variant:"outlined","hide-details":g.value.errors.method==null,"error-messages":g.value.errors.method,density:"compact"},null,8,["modelValue","items","hide-details","error-messages"])]),_:1})):d("",!0),P.value=="Paid"&&k.value=="Cash"?(s(),p(S,{key:2,cols:"12"},{default:e(()=>[t(q,{modelValue:me.value,"onUpdate:modelValue":u[3]||(u[3]=m=>me.value=m),label:"Who Received the Cash?",variant:"outlined","hide-details":g.value.errors.whoReceivedCash==null,"error-messages":g.value.errors.whoReceivedCash,clearable:""},null,8,["modelValue","hide-details","error-messages"])]),_:1})):d("",!0),k.value=="Bank Transfer"&&P.value=="Paid"?(s(),p(S,{key:3,cols:"12",sm:"6"},{default:e(()=>[yt,t(de,null,{default:e(()=>[t(S,{cols:"12"},{default:e(()=>[t(E,{modelValue:H.value,"onUpdate:modelValue":u[4]||(u[4]=m=>H.value=m),label:"Customer's Bank",type:"text",variant:"outlined",density:"compact","hide-details":g.value.errors.customerBank==null,"error-messages":g.value.errors.customerBank},null,8,["modelValue","hide-details","error-messages"])]),_:1}),t(S,{cols:"12"},{default:e(()=>[t(E,{modelValue:F.value,"onUpdate:modelValue":u[5]||(u[5]=m=>F.value=m),label:"Customer's Account Number",type:"tel",variant:"outlined",density:"compact","hide-details":g.value.errors.customerAccountNumber==null,"error-messages":g.value.errors.customerAccountNumber},null,8,["modelValue","hide-details","error-messages"])]),_:1}),t(S,{cols:"12"},{default:e(()=>[t(E,{modelValue:I.value,"onUpdate:modelValue":u[6]||(u[6]=m=>I.value=m),label:"Customer's Account Name",type:"text",variant:"outlined",density:"compact","hide-details":g.value.errors.customerAccountName==null,"error-messages":g.value.errors.customerAccountName},null,8,["modelValue","hide-details","error-messages"])]),_:1})]),_:1})]),_:1})):d("",!0),k.value=="Bank Transfer"&&P.value=="Paid"?(s(),p(S,{key:4,cols:"12",sm:"6"},{default:e(()=>[ht,t(de,null,{default:e(()=>[t(S,{cols:"12"},{default:e(()=>[t(E,{modelValue:L.value,"onUpdate:modelValue":u[7]||(u[7]=m=>L.value=m),label:"Organization's Bank",type:"text",variant:"outlined",density:"compact","hide-details":g.value.errors.organizationBank==null,"error-messages":g.value.errors.organizationBank},null,8,["modelValue","hide-details","error-messages"])]),_:1}),t(S,{cols:"12"},{default:e(()=>[t(E,{modelValue:j.value,"onUpdate:modelValue":u[8]||(u[8]=m=>j.value=m),label:"Organization's Account Number",type:"tel",variant:"outlined",density:"compact","hide-details":g.value.errors.organizationAccountNumber==null,"error-messages":g.value.errors.organizationAccountNumber},null,8,["modelValue","hide-details","error-messages"])]),_:1}),t(S,{cols:"12"},{default:e(()=>[t(E,{modelValue:te.value,"onUpdate:modelValue":u[9]||(u[9]=m=>te.value=m),label:"Organization's Account Name",type:"text",variant:"outlined",density:"compact","hide-details":g.value.errors.organizationAccountName==null,"error-messages":g.value.errors.organizationAccountName},null,8,["modelValue","hide-details","error-messages"])]),_:1})]),_:1})]),_:1})):d("",!0),k.value=="Bank Transfer"&&P.value=="Paid"?(s(),p(S,{key:5,cols:"12"},{default:e(()=>[t(E,{modelValue:ne.value,"onUpdate:modelValue":u[10]||(u[10]=m=>ne.value=m),label:"Transaction Reference",type:"text",variant:"outlined",density:"compact","hide-details":g.value.errors.transactionReference==null,"error-messages":g.value.errors.transactionReference},null,8,["modelValue","hide-details","error-messages"])]),_:1})):d("",!0),P.value=="Paid"?(s(),p(S,{key:6,cols:"12"},{default:e(()=>[t(ae,{modelValue:V.value,"onUpdate:modelValue":u[11]||(u[11]=m=>V.value=m),max:N.value,"show-adjacent-months":"",title:"Transaction Date","hide-details":g.value.errors.paymentDate==null,"error-messages":g.value.errors.paymentDate},null,8,["modelValue","max","hide-details","error-messages"])]),_:1})):d("",!0),t(S,{cols:"12"},{default:e(()=>[R.value?(s(),p(le,{key:0,indeterminate:"",color:"red"})):d("",!0),b.value.length?(s(),x("p",xt,i(b.value),1)):d("",!0)]),_:1})]),_:1})]),_:1}),t(Be,null,{default:e(()=>[t(z,{onClick:ke},{default:e(()=>[n("Update")]),_:1}),t(z,{color:"red",onClick:u[12]||(u[12]=m=>O.value=!O.value)},{default:e(()=>[n("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})}}},kt=l("hr",null,null,-1),Vt=l("b",null,"Client",-1),Ct=l("br",null,null,-1),Pt=l("br",null,null,-1),Tt=l("b",null,"Delivery Address",-1),Ot=l("br",null,null,-1),Nt=l("b",null,"Origin Branch",-1),$t=l("br",null,null,-1),St=l("b",null,"Processing Branch",-1),Dt=l("br",null,null,-1),At=l("b",null,"Order Number",-1),Ut=l("br",null,null,-1),Rt={key:0,class:"text-center font-bold mt-1 mb-0"},Bt={key:1,class:"text-center text-red mt-1 mb-0"},Mt=l("b",null,"Order Name",-1),It=l("br",null,null,-1),zt=l("b",null,"Price",-1),Ft=l("br",null,null,-1),Et={key:0,class:"text-center font-bold"},Yt={key:1,class:"text-center text-red mt-1 mb-0"},Ht=l("b",null,"Invoice Status",-1),Lt=l("br",null,null,-1),jt=l("b",null,"Payment Method",-1),Wt=l("br",null,null,-1),qt=l("b",null,"Product",-1),Gt=l("br",null,null,-1),Qt=l("b",null,"Quantity",-1),Zt=l("br",null,null,-1),Jt=l("b",null,"Order Status",-1),Kt=l("br",null,null,-1),Xt=l("b",null,"Current Process",-1),ea=l("br",null,null,-1),ta={key:0,class:"my-2"},aa={key:1,class:"text-red"},la=l("b",null,"Order Created",-1),oa=l("br",null,null,-1),sa=l("b",null,"Last Updated",-1),na=l("br",null,null,-1),ra=l("b",null,"Target Delivery Date",-1),da=l("br",null,null,-1),ia=l("b",null,"WayBill Number",-1),ua=l("br",null,null,-1),ca={key:0,class:"text-center text-red mt-1 mb-0"},va=l("b",null,"Note",-1),pa=l("br",null,null,-1),ma=l("hr",null,null,-1),fa=l("p",null,[n(" Why do you want to place this order on hold?"),l("br")],-1),_a={key:0,class:"text-center font-bold pt-2"},ga={key:1,class:"text-center text-red mt-1 mb-0"},ba=l("p",null,"Are you sure you want to reactivate this order?",-1),ya={key:0,class:"text-center"},ha={key:1,class:"text-center font-bold"},xa={key:2,class:"text-center text-red mt-1 mb-0"},wa=l("p",null,"Are you sure you want to cancel this order?",-1),ka={key:0,class:"text-center"},Va={key:1,class:"text-center font-bold"},Ca={key:2,class:"text-center text-red mt-1 mb-0"},Pa={__name:"OrderCard",setup(Y){const M=h().props.auth.user,r=h().props.order,T=h().props.orderDetail,k=h().props.hasInvoice,P=h().props.canGenerateInvoice,D=o(h().props.invoicePaid),U=o(h().props.invoice),V=h().props.canEditOrder,N=h().props.canHoldOrder,O=h().props.canCancelOrder,$=h().props.canEditReferenceNumber,b=h().props.canEditPrice,R=h().props.canEditWaybill,H=h().props.canForwardToNextProcess,F=o(!1),I=o(""),L=o(!1),j=o(r.order_status.name),te=o(r.process?r.process.name:"-"),me=r.waybill_number,se=o(r.human_forwarding),ne=h().props.invoice,g=o(ne==null?"":ne.payment_method),ke=o(h().props.canApproveOfflinePayment),Ve=w=>{ke.value=!1,D.value=w.invoicePaid,g.value=w.paymentMethod},u=new Intl.NumberFormat("en-US",{style:"decimal",minimumFractionDigits:0,maximumFractionDigits:0}),W=o("");let re=null;const Ce=async()=>{I.value="",W.value="",L.value=!0;const w={orderId:r.id};re&&re.cancel("Request cancelled by user"),re=B.CancelToken.source();try{const a=await B.put(route("order.cancel",[r.id]),w,{headers:{"Content-Type":"application/json"},cancelToken:re.token});L.value=!1,I.value=a.data.response,j.value=a.data.orderStatus}catch(a){L.value=!1,a.response&&a.response.status===422?W.value=a.response.data.message:W.value="Something went wrong! Pls try again later."}setTimeout(()=>{I.value="",W.value="",F.value=!1},5e3)},S=tt({orderId:r.id}),E=()=>{S.post(route("invoice.create"))},q=o(r.hold_reason),de=o(""),ae=o(!1),le=o(!1),A=o(r.paused),z=o(""),Be=async()=>{z.value="",ae.value=!0;const w={reason:q.value};try{const a=await B.put(route("order.hold",[r.id]),w,{headers:{"Content-Type":"application/json"}});ae.value=!1,a.data&&a.data.status=="success"&&(A.value=!0,le.value=!1)}catch(a){ae.value=!1,a.response&&a.response.status===422?z.value=a.response.data.message:z.value="Something went wrong! Pls try again later."}setTimeout(()=>{z.value="",le.value=!1},5e3)},Pe=o(""),oe=o(!1),m=o(!1),ie=o(""),We=async()=>{oe.value=!0;const w={};try{const a=await B.put(route("order.reactivate",[r.id]),w,{headers:{"Content-Type":"application/json"}});oe.value=!1,a.data&&a.data.status=="success"&&(A.value=!1,q.value="",m.value=!1)}catch(a){oe.value=!1,a.response&&a.response.status===422?ie.value=a.response.data.message:ie.value="Something went wrong! Pls try again later."}setTimeout(()=>{ie.value="",m.value=!1},5e3)},J=o(r.order_number),fe=o(!1),Te=o(""),ue=o(""),Oe=o(!1),qe=async()=>{ue.value="",fe.value=!0;const w={orderNumber:J.value};try{const a=await B.put(route("order.set-reference",[r.id]),w,{headers:{"Content-Type":"application/json"}});fe.value=!1,a.data&&a.data.status=="success"&&(Te.value=a.data.response)}catch(a){fe.value=!1,a.response&&a.response.status===422?ue.value=a.response.data.message:ue.value="Something went wrong! Pls try again later."}setTimeout(()=>{Te.value="",ue.value="",Oe.value=!1},5e3)},G=o(r.total_cost),_e=o(!1),Ne=o(""),ce=o(""),$e=o(!1),Ge=async()=>{ce.value="",_e.value=!0;const w={price:G.value};try{const a=await B.put(route("order.set-price",[r.id]),w,{headers:{"Content-Type":"application/json"}});_e.value=!1,a.data&&a.data.status=="success"&&(Ne.value=a.data.response)}catch(a){_e.value=!1,a.response&&a.response.status===422?ce.value=a.response.data.message:ce.value="Something went wrong! Pls try again later."}setTimeout(()=>{Ne.value="",ce.value="",$e.value=!1},5e3)},ge=o(me),be=o(!1),Se=o(""),ve=o(""),De=o(!1),Qe=async()=>{be.value=!0,ve.value="";const w={waybillNumber:ge.value};try{const a=await B.put(route("order.save-waybill",[r.id]),w,{headers:{"Content-Type":"application/json"}});be.value=!1,a.data&&a.data.status=="success"&&(Se.value=a.data.response)}catch(a){be.value=!1,a.response&&a.response.status===422?ve.value=a.response.data.message:ve.value="Something went wrong! Pls try again later."}setTimeout(()=>{Se.value="",ve.value="",De.value=!1},5e3)},Ae=o(!1),ye=o(""),pe=o(""),Ze=async()=>{Ae.value=!0,ye.value="",pe.value="";const w={orderId:r.id};try{const a=await B.post(route("order.process.forward"),w,{headers:{"Content-Type":"application/json"}});a.data&&a.data.status=="success"&&(ye.value=a.data.message,te.value=a.data.currentProcess,se.value=!1)}catch(a){a.response&&a.response.status===422?pe.value=a.response.data.message:pe.value="Something went wrong! Pls try again later."}Ae.value=!1,setTimeout(()=>{ye.value="",pe.value=""},7e3)},Je=()=>{const w=r.user.name,a=r.user.mobile,Q=r.delivery_address,Z=r.source_branch?r.source_branch.name:"-",f=r.processing_branch.name,_=J.value,C=r.name,he=r.item.name,K=r.quantity,X=G.value?`₦${u.format(G.value)}`:"--",Me=A.value?"On Hold":j.value,xe=te.value,Ie=ee(r.created_at).format("MMMM DD, YYYY"),Ue=ee(T.date).format("MMMM DD, YYYY"),y=ge.value??"NOT SET",Ye=r.note,He=window.open("","","height=800,width=900"),Ke=h().props.site.name;He.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Order Card - ${_}</title>
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
                    background: ${A.value?"#ef4444":"#10b981"};
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
                    <h1>${Ke}</h1>
                    <div class="order-number">
                        <div class="order-number-label">Order Number</div>
                        <div class="order-number-value">${_}</div>
                    </div>
                </div>
                
                <div class="content">
                    <div class="status-bar">
                        <div>
                            <div class="status-label">Current Status</div>
                            <div class="status-value">${Me}</div>
                        </div>
                        <div>
                            <div class="status-label">Process</div>
                            <div class="status-value">${xe}</div>
                        </div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Client Information</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Client Name</div>
                                <div class="info-value">${w}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Phone Number</div>
                                <div class="info-value">${a}</div>
                            </div>
                            <div class="info-item full-width">
                                <div class="info-label">Delivery Address</div>
                                <div class="info-value">${Q}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Order Details</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Order Name</div>
                                <div class="info-value large">${C}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Product</div>
                                <div class="info-value">${he}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Quantity</div>
                                <div class="info-value">${K}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Total Cost</div>
                                <div class="info-value large">${X}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Processing Information</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Origin Branch</div>
                                <div class="info-value">${Z}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Processing Branch</div>
                                <div class="info-value">${f}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Order Date</div>
                                <div class="info-value">${Ie}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Target Delivery</div>
                                <div class="info-value">${Ue}</div>
                            </div>
                            <div class="info-item full-width">
                                <div class="info-label">Waybill Number</div>
                                <div class="info-value">${y}</div>
                            </div>
                        </div>
                    </div>
                    
                    ${Ye?`
                        <div class="section">
                            <div class="section-title">Special Notes</div>
                            <div class="notes-box">
                                <div class="notes-content">${Ye}</div>
                            </div>
                        </div>
                    `:""}
                </div>
                
                <div class="footer">
                    <div class="footer-text">This is an official order card from Fotoplanet Professional Photography Services</div>
                    <div class="print-date">Printed on ${ee().format("MMMM DD, YYYY [at] h:mm A")}</div>
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
    `),He.document.close()};return(w,a)=>{const Q=v("VCardText"),Z=v("VCard"),f=v("VCol"),_=v("VRow"),C=v("VBtn"),he=v("VTextField"),K=v("VCardActions"),X=v("VOverlay"),Me=v("v-progress-linear"),xe=v("VCardTitle"),Ie=v("VTextarea"),Ue=v("v-progress-circular");return s(),p(we,{snippetTitle:"Order Card",cardColor:A.value?"bg-gray-700 text-white":"bg-white"},{default:e(()=>[q.value&&q.value.length&&A.value?(s(),p(_,{key:0},{default:e(()=>[t(f,{cols:"12"},{default:e(()=>[t(Z,{color:"red"},{default:e(()=>[t(Q,null,{default:e(()=>[n(i(q.value),1)]),_:1})]),_:1})]),_:1})]),_:1})):d("",!0),t(_,null,{default:e(()=>[t(f,{class:"text-right"},{default:e(()=>[c(V)&&J.value&&J.value.length&&G.value>0?(s(),p(C,{key:0,color:"blue-darken-3","prepend-icon":"mdi-printer",onClick:Je},{default:e(()=>[n(" Print Card ")]),_:1})):d("",!0)]),_:1})]),_:1}),kt,t(_,{id:"order-card"},{default:e(()=>[t(f,{cols:"12",md:"6"},{default:e(()=>[t(_,null,{default:e(()=>[t(f,null,{default:e(()=>[Vt,Ct,n(" "+i(c(r).user.name),1),Pt,n(" "+i(c(r).user.mobile),1)]),_:1})]),_:1}),t(_,null,{default:e(()=>[t(f,null,{default:e(()=>[Tt,Ot,n(" "+i(c(r).delivery_address),1)]),_:1})]),_:1}),t(_,null,{default:e(()=>[t(f,null,{default:e(()=>[Nt,$t,n(" "+i(c(r).source_branch?c(r).source_branch.name:"-"),1)]),_:1})]),_:1}),t(_,null,{default:e(()=>[t(f,null,{default:e(()=>[St,Dt,n(" "+i(c(r).processing_branch.name),1)]),_:1})]),_:1}),t(_,null,{default:e(()=>[t(f,null,{default:e(()=>[At,Ut,n(" "+i(J.value)+" ",1),c($)&&!A.value?(s(),p(C,{key:0,"prepend-icon":"mdi-pencil",class:"mr-2 no-padding no-border",elevation:"0"},{default:e(()=>[t(X,{modelValue:Oe.value,"onUpdate:modelValue":a[2]||(a[2]=y=>Oe.value=y),activator:"parent","location-strategy":"connected","scroll-strategy":"static"},{default:e(()=>[t(Z,{"max-width":"400",class:"p-1"},{default:e(()=>[t(Q,{class:"pb-0"},{default:e(()=>[t(he,{modelValue:J.value,"onUpdate:modelValue":a[0]||(a[0]=y=>J.value=y),"hide-details":"",id:"order-number",variant:"outlined",label:"Order Number",style:{"min-width":"200px"},loading:fe.value},null,8,["modelValue","loading"]),Te.value.length?(s(),x("p",Rt,i(Te.value),1)):d("",!0),ue.value.length?(s(),x("p",Bt,i(ue.value),1)):d("",!0)]),_:1}),t(K,null,{default:e(()=>[t(C,{color:"blue-darken-1 m-1",onClick:qe,disabled:fe.value},{default:e(()=>[n("Save")]),_:1},8,["disabled"]),t(C,{color:"grey-darken-1 m-1",onClick:a[1]||(a[1]=y=>Oe.value=!1)},{default:e(()=>[n("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):d("",!0)]),_:1})]),_:1}),t(_,null,{default:e(()=>[t(f,null,{default:e(()=>[Mt,It,n(" "+i(c(r).name),1)]),_:1})]),_:1}),t(_,null,{default:e(()=>[w.$page.props.auth.user.role!="Customer"?(s(),p(f,{key:0},{default:e(()=>[zt,Ft,n(" ₦"+i(G.value?c(u).format(G.value):" --:--")+" ",1),c(b)&&!A.value?(s(),p(C,{key:0,elevation:"0","prepend-icon":"mdi-pencil",class:"mr-2 no-padding no-border"},{default:e(()=>[t(X,{modelValue:$e.value,"onUpdate:modelValue":a[5]||(a[5]=y=>$e.value=y),activator:"parent","location-strategy":"connected","scroll-strategy":"static"},{default:e(()=>[t(Z,{"max-width":"400",class:"p-1"},{default:e(()=>[t(Q,{class:"pb-0"},{default:e(()=>[t(he,{modelValue:G.value,"onUpdate:modelValue":a[3]||(a[3]=y=>G.value=y),"hide-details":"",id:"price",type:"number",variant:"outlined",label:"Price",prefix:"₦",style:{width:"200px"},loading:_e.value},null,8,["modelValue","loading"]),Ne.value.length?(s(),x("div",Et,i(Ne.value),1)):d("",!0),ce.value.length?(s(),x("p",Yt,i(ce.value),1)):d("",!0)]),_:1}),t(K,null,{default:e(()=>[t(C,{color:"blue-darken-1 m-1",onClick:Ge,disabled:_e.value},{default:e(()=>[n("Save")]),_:1},8,["disabled"]),t(C,{color:"grey-darken-1 m-1",onClick:a[4]||(a[4]=y=>$e.value=!1)},{default:e(()=>[n("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):d("",!0)]),_:1})):d("",!0)]),_:1}),G.value&&J.value&&!A.value&&!c(k)&&c(P)&&j.value!="Cancelled"?(s(),p(_,{key:0},{default:e(()=>[t(f,{cols:"12"},{default:e(()=>[t(C,{color:"blue-darken-1",onClick:E},{default:e(()=>[n("Issue Invoice")]),_:1})]),_:1})]),_:1})):d("",!0),c(k)?(s(),p(_,{key:1},{default:e(()=>[t(f,{cols:"12"},{default:e(()=>[Ht,Lt,n(" "+i(D.value?"Paid":"Unpaid"),1)]),_:1}),t(f,{cols:"12"},{default:e(()=>[t(c(Fe),{class:"font-bold underline",href:w.route("invoice",[U.value.id])},{default:e(()=>[n("Open Invoice")]),_:1},8,["href"])]),_:1})]),_:1})):d("",!0),c(k)&&D.value?(s(),p(_,{key:2},{default:e(()=>[t(f,{cols:"12"},{default:e(()=>[jt,Wt,n(" "+i(g.value),1)]),_:1})]),_:1})):d("",!0),ke.value?(s(),p(_,{key:3},{default:e(()=>[t(f,{cols:"12"},{default:e(()=>[t(wt,{onStatusUpdated:Ve})]),_:1})]),_:1})):d("",!0)]),_:1}),t(f,{cols:"12",md:"6"},{default:e(()=>[t(_,null,{default:e(()=>[t(f,null,{default:e(()=>[qt,Gt,n(" "+i(w.$page.props.order.item.name),1)]),_:1})]),_:1}),t(_,null,{default:e(()=>[t(f,null,{default:e(()=>[Qt,Zt,n(" "+i(w.$page.props.order.quantity),1)]),_:1})]),_:1}),t(_,null,{default:e(()=>[t(f,null,{default:e(()=>[Jt,Kt,n(" "+i(A.value?"On Hold":j.value),1)]),_:1})]),_:1}),t(_,null,{default:e(()=>[t(f,null,{default:e(()=>[Xt,ea,n(" "+i(te.value),1)]),_:1})]),_:1}),c(H)&&se.value?(s(),p(_,{key:0},{default:e(()=>[t(f,null,{default:e(()=>[t(C,{"prepend-icon":"mdi-play",color:"blue-darken-3",onClick:Ze,disabled:Ae.value},{default:e(()=>[n("Start Next Process")]),_:1},8,["disabled"]),Ae.value?(s(),x("p",ta,[t(Me,{color:"red",indeterminate:""})])):d("",!0),pe.value.length?(s(),x("p",aa,i(pe.value),1)):d("",!0)]),_:1})]),_:1})):d("",!0),ye.value.length?(s(),p(_,{key:1},{default:e(()=>[t(f,null,{default:e(()=>[n(i(ye.value),1)]),_:1})]),_:1})):d("",!0),c(M).isAdmin?(s(),p(_,{key:2},{default:e(()=>[t(f,null,{default:e(()=>[t(c(Fe),{class:"font-bold underline",href:w.route("tasks.order.dashboard",[c(r).id])},{default:e(()=>[n("View Tasks")]),_:1},8,["href"])]),_:1})]),_:1})):d("",!0),t(_,null,{default:e(()=>[t(f,null,{default:e(()=>[la,oa,n(" "+i(c(ee)(c(r).created_at).calendar()),1)]),_:1})]),_:1}),t(_,null,{default:e(()=>[t(f,null,{default:e(()=>[sa,na,n(" "+i(c(ee)(c(r).updated_at).fromNow()),1)]),_:1})]),_:1}),t(_,null,{default:e(()=>[t(f,null,{default:e(()=>[ra,da,n(" "+i(c(ee)(c(T).date).format("LL")),1)]),_:1})]),_:1}),t(_,null,{default:e(()=>[t(f,null,{default:e(()=>[ia,ua,n(" "+i(ge.value??"NOT SET")+" ",1),c(R)&&!A.value?(s(),p(C,{key:0,elevation:"0","prepend-icon":"mdi-pencil",class:"mr-2 no-padding no-border"},{default:e(()=>[t(X,{modelValue:De.value,"onUpdate:modelValue":a[8]||(a[8]=y=>De.value=y),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(Z,{"max-width":"400",class:"p-1"},{default:e(()=>[t(Q,{class:"pb-0"},{default:e(()=>[t(he,{modelValue:ge.value,"onUpdate:modelValue":a[6]||(a[6]=y=>ge.value=y),"hide-details":"",id:"order-number",variant:"outlined",label:"Waybill Number",style:{"min-width":"200px"},loading:be.value},null,8,["modelValue","loading"]),at(l("div",{class:"text-center font-bold"},i(Se.value),513),[[lt,Se.value.length]]),ve.value.length?(s(),x("p",ca,i(ve.value),1)):d("",!0)]),_:1}),t(K,null,{default:e(()=>[t(C,{color:"blue-darken-1 m-1",onClick:Qe,disabled:be.value},{default:e(()=>[n("Save")]),_:1},8,["disabled"]),t(C,{color:"grey-darken-1 m-1",onClick:a[7]||(a[7]=y=>De.value=!1)},{default:e(()=>[n("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):d("",!0)]),_:1})]),_:1})]),_:1})]),_:1}),t(_,null,{default:e(()=>[t(f,null,{default:e(()=>[va,pa,n(" "+i(c(r).note),1)]),_:1})]),_:1}),ma,t(_,null,{default:e(()=>[t(f,null,{default:e(()=>[!A.value&&c(N)?(s(),p(C,{key:0,color:"grey-darken-3","prepend-icon":"mdi-pause",class:"mr-2 mb-2"},{default:e(()=>[n(" Hold Order "),t(X,{modelValue:le.value,"onUpdate:modelValue":a[11]||(a[11]=y=>le.value=y),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(Z,{"max-width":"400",class:"p-3"},{default:e(()=>[t(xe,null,{default:e(()=>[n("What's Wrong?")]),_:1}),t(Q,null,{default:e(()=>[fa,t(Ie,{modelValue:q.value,"onUpdate:modelValue":a[9]||(a[9]=y=>q.value=y),"hide-details":"",id:"hold-order",variant:"outlined",label:"Leave a note for team members",loading:ae.value},null,8,["modelValue","loading"]),de.value.length?(s(),x("p",_a,i(de.value),1)):d("",!0),z.value&&z.value.length?(s(),x("p",ga,i(z.value),1)):d("",!0)]),_:1}),t(K,null,{default:e(()=>[t(C,{color:"red-darken-1",onClick:Be,disabled:ae.value},{default:e(()=>[n("Continue")]),_:1},8,["disabled"]),t(C,{color:"blue-darken-1",onClick:a[10]||(a[10]=y=>le.value=!1)},{default:e(()=>[n("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):d("",!0),A.value&&c(M).isAdmin&&j.value!="Cancelled"?(s(),p(C,{key:1,color:"white",class:"mr-2 mb-2","prepend-icon":"mdi-play"},{default:e(()=>[n(" Reactivate "),t(X,{modelValue:m.value,"onUpdate:modelValue":a[13]||(a[13]=y=>m.value=y),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(Z,{"max-width":"400",class:"p-3"},{default:e(()=>[t(xe,null,{default:e(()=>[n("Confirm Action!")]),_:1}),t(Q,null,{default:e(()=>[ba,oe.value?(s(),x("p",ya,[t(Ue,{color:"red",indeterminate:""})])):d("",!0),Pe.value.length?(s(),x("p",ha,i(Pe.value),1)):d("",!0),ie.value&&ie.value.length?(s(),x("p",xa,i(ie.value),1)):d("",!0)]),_:1}),t(K,null,{default:e(()=>[t(C,{color:"red-darken-1 m-1",onClick:We,disabled:oe.value},{default:e(()=>[n("Yes Proceed")]),_:1},8,["disabled"]),t(C,{color:"blue-darken-1 m-1",onClick:a[12]||(a[12]=y=>m.value=!1)},{default:e(()=>[n("Don't")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):d("",!0),c(O)?(s(),p(C,{key:2,color:"red-darken-1",class:"mr-2 mb-2","prepend-icon":"mdi-cancel"},{default:e(()=>[n(" Cancel Order "),t(X,{modelValue:F.value,"onUpdate:modelValue":a[15]||(a[15]=y=>F.value=y),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(Z,{"max-width":"400",class:"p-3"},{default:e(()=>[t(xe,null,{default:e(()=>[n("Heads Up!")]),_:1}),t(Q,null,{default:e(()=>[wa,L.value?(s(),x("p",ka,[t(Ue,{color:"red",indeterminate:""})])):d("",!0),I.value.length?(s(),x("p",Va,i(I.value),1)):d("",!0),W.value&&W.value.length?(s(),x("p",Ca,i(W.value),1)):d("",!0)]),_:1}),t(K,null,{default:e(()=>[t(C,{color:"red-darken-1 m-1",onClick:Ce,disabled:L.value},{default:e(()=>[n("Yes Proceed")]),_:1},8,["disabled"]),t(C,{color:"blue-darken-1 m-1",onClick:a[14]||(a[14]=y=>F.value=!1)},{default:e(()=>[n("Don't")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):d("",!0)]),_:1})]),_:1})]),_:1},8,["cardColor"])}}},Ee=Y=>(st("data-v-d6ad9e94"),Y=Y(),nt(),Y),Ta=Ee(()=>l("tr",null,[l("td",null,"Process"),l("td",null,"Completed by"),l("td",null,"Date")],-1)),Oa={key:0,class:"text-right mb-2"},Na=Ee(()=>l("h3",{class:"text-red"},"No asset was uploaded for this order!",-1)),$a=Ee(()=>l("p",null,"If you do not have the image files, kindly contact the client using the phone number below.",-1)),Sa={__name:"Detail",setup(Y){h().props.order;const M=h().props.orderDetail,r=h().props.activities,T=Le({orderFiles:M.files}),k=P=>{for(let D=0;D<T.orderFiles.length;D++)T.orderFiles[D].file.id==P.id&&T.orderFiles.splice(D,1)};return(P,D)=>{const U=v("THead"),V=v("TBody"),N=v("VTable"),O=v("VCol"),$=v("VRow");return s(),x(Re,null,[t(c(ot),{title:"Order"}),t(rt,null,{default:e(()=>[t(c(Fe),{href:P.route("orders"),class:"font-bold"},{default:e(()=>[n("Back")]),_:1},8,["href"]),t($,null,{default:e(()=>[t(O,{cols:"12",sm:"6"},{default:e(()=>[t(Pa),c(r).length?(s(),p(we,{key:0,"snippet-title":"Activities"},{default:e(()=>[t(N,null,{default:e(()=>[t(U,null,{default:e(()=>[Ta]),_:1}),t(V,null,{default:e(()=>[(s(!0),x(Re,null,ze(c(r),b=>(s(),x("tr",{key:b.id},[l("td",null,i(b.process.name),1),l("td",null,i(b.staff.name),1),l("td",null,i(c(ee)(b.created_at).format("MM/DD/YYYY, h:mm A")),1)]))),128))]),_:1})]),_:1})]),_:1})):d("",!0)]),_:1}),t(O,{cols:"12",sm:"6"},{default:e(()=>[t(_t),t(we,{"snippet-title":"Customer Feedback"},{default:e(()=>[t(ut)]),_:1})]),_:1})]),_:1}),t(we,{"snippet-title":"Order Assets"},{default:e(()=>[T.orderFiles.length?(s(),x("div",Oa,[t(bt,{files:T.orderFiles},null,8,["files"])])):d("",!0),T.orderFiles.length?(s(),p($,{key:1},{default:e(()=>[(s(!0),x(Re,null,ze(T.orderFiles,(b,R)=>(s(),p(O,{cols:"12",lg:"6",key:R},{default:e(()=>[t(it,{orderImage:b.file,view:"Detail",onPageRemoved:k,onPageDataUpdated:H=>{P.updatePageData(H,b)}},null,8,["orderImage","onPageDataUpdated"])]),_:2},1024))),128))]),_:1})):(s(),p($,{key:2},{default:e(()=>[t(O,{class:"text-center"},{default:e(()=>[Na,$a]),_:1})]),_:1}))]),_:1})]),_:1})],64)}}},Fa=je(Sa,[["__scopeId","data-v-d6ad9e94"]]);export{Fa as default};
