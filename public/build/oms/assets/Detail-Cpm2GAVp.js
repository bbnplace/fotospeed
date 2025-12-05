import{Q as f,s as Ee,f as r,v as lt,A as at,r as _,g as h,o as n,w as e,c as m,b as a,F as ke,m as $e,t as d,u as i,a as t,d as o,h as ot,x as I,T as st,i as u,j as Oe,H as nt,I as rt,Z as dt,p as it,e as ut}from"./app-Bkqqm29C.js";import{_ as ct}from"./BackendLayout-VsPlyYQD.js";import{F as pt,_ as vt}from"./OrderForm.vue_vue_type_script_setup_true_lang-BoWZVybd.js";import{_ as ve}from"./Panel.vue_vue_type_script_setup_true_lang-Ir8W8Mj4.js";import{h as H}from"./indigo-lKO-yhzo.js";import{_ as Ue}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{C as ft}from"./CustomerFeedback-6wEvRVVy.js";import"./ApplicationLogo-D1mICvTJ.js";import"./Snackbar.vue_vue_type_script_setup_true_lang-B41KHPZD.js";const mt={key:0},_t={class:"font-bold"},bt={key:1},gt={class:"text-right"},ht={__name:"CommunicationLog",setup(M){const D=f().props.order,s=f().props.auth.user,x=Ee({newMessage:""}),C=r([]);let T=null;const O=async()=>{const b={message:x.newMessage,orderId:D.id},N=(await I.post(route("order.log.write"),b,{headers:{"Content-Type":"application/json"}})).data;N.status!==void 0&&N.status=="success"&&(x.newMessage="",N.data&&C.value.push(N.data))},R=async()=>{const b=await I.get(route("order.log",[D.id]));C.value=b.data},w=(b,V)=>{Notification.permission==="granted"?new Notification(`New message from ${V}`,{body:b,icon:"/favicon.ico"}):console.log(`New message from ${V}: ${b}`)},P=()=>{T=window.Echo.private(`order-chat.${D.id}`),T.listen(".new-message",b=>{console.log("New message received:",b),C.value.push({id:b.id,message:b.message,created_at:b.created_at,user:b.user}),w(b.message,b.user.name)})},k=()=>{T&&(window.Echo.leave(`order-chat.${D.id}`),T=null)};return lt(()=>{R(),P(),Notification.permission==="default"&&Notification.requestPermission()}),at(()=>{k()}),(b,V)=>{const N=_("VCard"),X=_("VTextarea"),E=_("VBtn");return n(),h(ve,{"snippet-title":"Communication Log"},{default:e(()=>[C.value.length?(n(),m("div",mt,[(n(!0),m(ke,null,$e(C.value,(S,Ce)=>(n(),m("div",{key:Ce,class:"task-card px-2"},[a("p",_t,d(i(H)(S.created_at).calendar())+", "+d(S.user.mobile==i(s).mobile?"I":S.user.name)+" wrote:",1),a("p",null,d(S.message),1)]))),128))])):(n(),m("div",bt,[t(N,{class:"mb-2 p-2",color:"grey-lighten-3"},{default:e(()=>[o(" Leave a note for other team members working on this Order. ")]),_:1})])),a("div",null,[a("form",{onSubmit:ot(O,["prevent"])},[t(X,{modelValue:x.newMessage,"onUpdate:modelValue":V[0]||(V[0]=S=>x.newMessage=S),label:"Type Comment",variant:"outlined",density:"compact",rows:"2","max-rows":"4","auto-grow":"",clearable:""},null,8,["modelValue"]),a("div",gt,[t(E,{"prepend-icon":"mdi-note-plus",color:"black",type:"submit"},{default:e(()=>[o("Add Note")]),_:1})])],32)])]),_:1})}}},yt=Ue(ht,[["__scopeId","data-v-52fc2372"]]),wt=a("div",null,"To keep things organized, we recommend creating a dedicated folder to store all assets for this order.",-1),xt={__name:"DownloadAllMediaBtn",props:{files:Array},setup(M){const D=route("file.fetch"),s=r(!1),x=M,C=r(!1),T=(w,P,k)=>{C.value=!0;const b=`${D}?filepath=${encodeURIComponent(w)}&type=${P}`;I.get(b,{responseType:"blob"}).then(V=>{const N=new Blob([V.data],{type:V.headers["content-type"]});pt.saveAs(N,k),C.value=!1})},O=async()=>{s.value=!1,x.files.forEach(w=>{const P=R(w.file.uploadedFile),k=w.file.pageNumber?`page-${w.file.pageNumber}.${P}`:w.file.fileInfo.name;T(w.file.uploadedFile,w.file.fileInfo.type,k)})},R=w=>w.split(".").pop().split(/\#|\?/)[0];return(w,P)=>{const k=_("VCardTitle"),b=_("VCardText"),V=_("VBtn"),N=_("VCardActions"),X=_("VCard"),E=_("VOverlay");return n(),h(V,{color:"blue","prepend-icon":"mdi-download",disabled:C.value},{default:e(()=>[o(" Download All "),t(E,{modelValue:s.value,"onUpdate:modelValue":P[0]||(P[0]=S=>s.value=S),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(X,{class:"p-3","max-width":"400"},{default:e(()=>[t(k,null,{default:e(()=>[o("Heads Up!")]),_:1}),t(b,null,{default:e(()=>[a("p",null,"You're about to start downloading "+d(x.files.length)+" files.",1),wt]),_:1}),t(N,null,{default:e(()=>[t(V,{onClick:O,color:"blue","prepend-icon":"mdi-download",disabled:C.value},{default:e(()=>[o(" Start Download ")]),_:1},8,["disabled"])]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1},8,["disabled"])}}},kt=a("hr",null,null,-1),Ct=a("b",null,"Client",-1),Vt=a("br",null,null,-1),Pt=a("br",null,null,-1),Tt=a("b",null,"Delivery Address",-1),$t=a("br",null,null,-1),Ot=a("b",null,"Processing Branch",-1),Nt=a("br",null,null,-1),Dt=a("b",null,"Origin Branch",-1),St=a("br",null,null,-1),It=a("b",null,"Order Number",-1),Ft=a("br",null,null,-1),Bt={key:0,class:"text-center font-bold mt-1 mb-0"},At={key:1,class:"text-center text-red mt-1 mb-0"},Mt=a("b",null,"Order Name",-1),Rt=a("br",null,null,-1),Et=a("b",null,"Price",-1),Ut=a("br",null,null,-1),Yt={key:0,class:"text-center font-bold"},zt={key:1,class:"text-center text-red mt-1 mb-0"},Ht=a("b",null,"Invoice Status",-1),Lt=a("br",null,null,-1),Wt=a("b",null,"Payment Method",-1),jt=a("br",null,null,-1),qt=a("b",null,"Product",-1),Gt=a("br",null,null,-1),Qt=a("b",null,"Quantity",-1),Zt=a("br",null,null,-1),Jt=a("b",null,"Order Status",-1),Kt=a("br",null,null,-1),Xt=a("b",null,"Current Process",-1),el=a("br",null,null,-1),tl={key:0,class:"my-2"},ll={key:1,class:"text-red"},al=a("b",null,"Order Created",-1),ol=a("br",null,null,-1),sl=a("b",null,"Last Updated",-1),nl=a("br",null,null,-1),rl=a("b",null,"Target Delivery Date",-1),dl=a("br",null,null,-1),il=a("b",null,"WayBill Number",-1),ul=a("br",null,null,-1),cl={key:0,class:"text-center text-red mt-1 mb-0"},pl=a("b",null,"Note",-1),vl=a("br",null,null,-1),fl=a("hr",null,null,-1),ml=a("p",null,[o(" Why do you want to place this order on hold?"),a("br")],-1),_l={key:0,class:"text-center font-bold pt-2"},bl={key:1,class:"text-center text-red mt-1 mb-0"},gl=a("p",null,"Are you sure you want to reactivate this order?",-1),hl={key:0,class:"text-center"},yl={key:1,class:"text-center font-bold"},wl={key:2,class:"text-center text-red mt-1 mb-0"},xl=a("p",null,"Are you sure you want to cancel this order?",-1),kl={key:0,class:"text-center"},Cl={key:1,class:"text-center font-bold"},Vl={key:2,class:"text-center text-red mt-1 mb-0"},Pl={__name:"OrderCard",setup(M){const D=f().props.auth.user,s=f().props.order,x=f().props.orderDetail,C=f().props.hasInvoice,T=f().props.canGenerateInvoice,O=r(f().props.invoicePaid),R=r(f().props.invoice),w=f().props.canEditOrder,P=f().props.canHoldOrder,k=f().props.canCancelOrder,b=f().props.canEditReferenceNumber,V=f().props.canEditPrice,N=f().props.canEditWaybill,X=f().props.canForwardToNextProcess,E=r(!1),S=f().props.isViewingFromProcessingBranch,Ce=f().props.showPriceToProcessingBranch,Ye=f().props.showInvoiceToProcessingBranch,ze=!S||Ce,He=!S||Ye,ee=r(""),te=r(!1),L=r(s.order_status.name),Ve=r(s.process?s.process.name:"-"),Le=s.waybill_number,De=r(s.human_forwarding),Se=f().props.invoice,We=r(Se==null?"":Se.payment_method);r(f().props.canApproveOfflinePayment);const Ie=new Intl.NumberFormat("en-US",{style:"decimal",minimumFractionDigits:0,maximumFractionDigits:0}),W=r("");let fe=null;const je=async()=>{ee.value="",W.value="",te.value=!0;const y={orderId:s.id};fe&&fe.cancel("Request cancelled by user"),fe=I.CancelToken.source();try{const l=await I.put(route("order.cancel",[s.id]),y,{headers:{"Content-Type":"application/json"},cancelToken:fe.token});te.value=!1,ee.value=l.data.response,L.value=l.data.orderStatus}catch(l){te.value=!1,l.response&&l.response.status===422?W.value=l.response.data.message:W.value="Something went wrong! Pls try again later."}setTimeout(()=>{ee.value="",W.value="",E.value=!1},5e3)},qe=st({orderId:s.id}),Ge=()=>{qe.post(route("invoice.create"))},j=r(s.hold_reason),Fe=r(""),le=r(!1),ae=r(!1),$=r(s.paused),q=r(""),Qe=async()=>{q.value="",le.value=!0;const y={reason:j.value};try{const l=await I.put(route("order.hold",[s.id]),y,{headers:{"Content-Type":"application/json"}});le.value=!1,l.data&&l.data.status=="success"&&($.value=!0,ae.value=!1)}catch(l){le.value=!1,l.response&&l.response.status===422?q.value=l.response.data.message:q.value="Something went wrong! Pls try again later."}setTimeout(()=>{q.value="",ae.value=!1},5e3)},Be=r(""),oe=r(!1),se=r(!1),G=r(""),Ze=async()=>{oe.value=!0;const y={};try{const l=await I.put(route("order.reactivate",[s.id]),y,{headers:{"Content-Type":"application/json"}});oe.value=!1,l.data&&l.data.status=="success"&&($.value=!1,j.value="",se.value=!1)}catch(l){oe.value=!1,l.response&&l.response.status===422?G.value=l.response.data.message:G.value="Something went wrong! Pls try again later."}setTimeout(()=>{G.value="",se.value=!1},5e3)},U=r(s.order_number),ne=r(!1),me=r(""),Q=r(""),_e=r(!1),Je=async()=>{Q.value="",ne.value=!0;const y={orderNumber:U.value};try{const l=await I.put(route("order.set-reference",[s.id]),y,{headers:{"Content-Type":"application/json"}});ne.value=!1,l.data&&l.data.status=="success"&&(me.value=l.data.response)}catch(l){ne.value=!1,l.response&&l.response.status===422?Q.value=l.response.data.message:Q.value="Something went wrong! Pls try again later."}setTimeout(()=>{me.value="",Q.value="",_e.value=!1},5e3)},F=r(s.total_cost),re=r(!1),be=r(""),Z=r(""),ge=r(!1),Ke=async()=>{Z.value="",re.value=!0;const y={price:F.value};try{const l=await I.put(route("order.set-price",[s.id]),y,{headers:{"Content-Type":"application/json"}});re.value=!1,l.data&&l.data.status=="success"&&(be.value=l.data.response)}catch(l){re.value=!1,l.response&&l.response.status===422?Z.value=l.response.data.message:Z.value="Something went wrong! Pls try again later."}setTimeout(()=>{be.value="",Z.value="",ge.value=!1},5e3)},de=r(Le),ie=r(!1),he=r(""),J=r(""),ye=r(!1),Xe=async()=>{ie.value=!0,J.value="";const y={waybillNumber:de.value};try{const l=await I.put(route("order.save-waybill",[s.id]),y,{headers:{"Content-Type":"application/json"}});ie.value=!1,l.data&&l.data.status=="success"&&(he.value=l.data.response)}catch(l){ie.value=!1,l.response&&l.response.status===422?J.value=l.response.data.message:J.value="Something went wrong! Pls try again later."}setTimeout(()=>{he.value="",J.value="",ye.value=!1},5e3)},we=r(!1),ue=r(""),K=r(""),et=async()=>{we.value=!0,ue.value="",K.value="";const y={orderId:s.id};try{const l=await I.post(route("order.process.forward"),y,{headers:{"Content-Type":"application/json"}});l.data&&l.data.status=="success"&&(ue.value=l.data.message,Ve.value=l.data.currentProcess,De.value=!1)}catch(l){l.response&&l.response.status===422?K.value=l.response.data.message:K.value="Something went wrong! Pls try again later."}we.value=!1,setTimeout(()=>{ue.value="",K.value=""},7e3)},tt=()=>{const y=s.user.name,l=s.user.mobile,B=s.delivery_address,A=s.source_branch?s.source_branch.name:"-",c=s.processing_branch.name,p=U.value,g=s.name,ce=s.item.name,Y=s.quantity,z=F.value?`₦${Ie.format(F.value)}`:"--",Pe=$.value?"On Hold":L.value,pe=Ve.value,Te=H(s.created_at).format("MMMM DD, YYYY"),xe=H(x.date).format("MMMM DD, YYYY"),v=de.value??"NOT SET",Ae=s.note,Me=window.open("","","height=800,width=900"),Re=f().props.site.name;Me.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Order Card - ${p}</title>
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
                    background: ${$.value?"#ef4444":"#10b981"};
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
                    <h1>${Re}</h1>
                    <div class="order-number">
                        <div class="order-number-label">Order Number</div>
                        <div class="order-number-value">${p}</div>
                    </div>
                </div>
                
                <div class="content">
                    <div class="status-bar">
                        <div>
                            <div class="status-label">Current Status</div>
                            <div class="status-value">${Pe}</div>
                        </div>
                        <div>
                            <div class="status-label">Process</div>
                            <div class="status-value">${pe}</div>
                        </div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Client Information</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Client Name</div>
                                <div class="info-value">${y}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Phone Number</div>
                                <div class="info-value">${l}</div>
                            </div>
                            <div class="info-item full-width">
                                <div class="info-label">Delivery Address</div>
                                <div class="info-value">${B}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Order Details</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Order Name</div>
                                <div class="info-value large">${g}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Product</div>
                                <div class="info-value">${ce}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Quantity</div>
                                <div class="info-value">${Y}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Total Cost</div>
                                <div class="info-value large">${z}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Processing Information</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Processing Branch</div>
                                <div class="info-value">${A}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Origin Branch</div>
                                <div class="info-value">${c}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Order Date</div>
                                <div class="info-value">${Te}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">${L.value==="Delivered"||L.value==="Fulfilled"?"Delivery Date":"Estimated Delivery Date"}</div>
                                <div class="info-value">${xe}</div>
                            </div>
                            <div class="info-item full-width">
                                <div class="info-label">Waybill Number</div>
                                <div class="info-value">${v}</div>
                            </div>
                        </div>
                    </div>
                    
                    ${Ae?`
                        <div class="section">
                            <div class="section-title">Special Notes</div>
                            <div class="notes-box">
                                <div class="notes-content">${Ae}</div>
                            </div>
                        </div>
                    `:""}
                </div>
                
                <div class="footer">
                    <div class="footer-text">This is an official order card from ${Re}</div>
                    <div class="print-date">Printed on ${H().format("MMMM DD, YYYY [at] h:mm A")}</div>
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
    `),Me.document.close()};return(y,l)=>{const B=_("VCardText"),A=_("VCard"),c=_("VCol"),p=_("VRow"),g=_("VBtn"),ce=_("VTextField"),Y=_("VCardActions"),z=_("VOverlay"),Pe=_("v-progress-linear"),pe=_("VCardTitle"),Te=_("VTextarea"),xe=_("v-progress-circular");return n(),h(ve,{snippetTitle:"Order Card",cardColor:$.value?"bg-gray-700 text-white":"bg-white"},{default:e(()=>[j.value&&j.value.length&&$.value?(n(),h(p,{key:0},{default:e(()=>[t(c,{cols:"12"},{default:e(()=>[t(A,{color:"red"},{default:e(()=>[t(B,null,{default:e(()=>[o(d(j.value),1)]),_:1})]),_:1})]),_:1})]),_:1})):u("",!0),t(p,null,{default:e(()=>[t(c,{class:"text-right"},{default:e(()=>[i(w)&&U.value&&U.value.length&&F.value>0?(n(),h(g,{key:0,color:"blue-darken-3","prepend-icon":"mdi-printer",onClick:tt},{default:e(()=>[o(" Print Card ")]),_:1})):u("",!0)]),_:1})]),_:1}),kt,t(p,{id:"order-card"},{default:e(()=>[t(c,{cols:"12",md:"6"},{default:e(()=>[t(p,null,{default:e(()=>[t(c,null,{default:e(()=>[Ct,Vt,o(" "+d(i(s).user.name),1),Pt,o(" "+d(i(s).user.mobile),1)]),_:1})]),_:1}),t(p,null,{default:e(()=>[t(c,null,{default:e(()=>[Tt,$t,o(" "+d(i(s).delivery_address),1)]),_:1})]),_:1}),t(p,null,{default:e(()=>[t(c,null,{default:e(()=>[Ot,Nt,o(" "+d(i(s).source_branch?i(s).source_branch.name:"-"),1)]),_:1})]),_:1}),t(p,null,{default:e(()=>[t(c,null,{default:e(()=>[Dt,St,o(" "+d(i(s).processing_branch.name),1)]),_:1})]),_:1}),t(p,null,{default:e(()=>[t(c,null,{default:e(()=>[It,Ft,o(" "+d(U.value)+" ",1),i(b)&&!$.value?(n(),h(g,{key:0,"prepend-icon":"mdi-pencil",class:"mr-2 no-padding no-border",elevation:"0"},{default:e(()=>[t(z,{modelValue:_e.value,"onUpdate:modelValue":l[2]||(l[2]=v=>_e.value=v),activator:"parent","location-strategy":"connected","scroll-strategy":"static"},{default:e(()=>[t(A,{"max-width":"400",class:"p-1"},{default:e(()=>[t(B,{class:"pb-0"},{default:e(()=>[t(ce,{modelValue:U.value,"onUpdate:modelValue":l[0]||(l[0]=v=>U.value=v),"hide-details":"",id:"order-number",variant:"outlined",label:"Order Number",style:{"min-width":"200px"},loading:ne.value},null,8,["modelValue","loading"]),me.value.length?(n(),m("p",Bt,d(me.value),1)):u("",!0),Q.value.length?(n(),m("p",At,d(Q.value),1)):u("",!0)]),_:1}),t(Y,null,{default:e(()=>[t(g,{color:"blue-darken-1 m-1",onClick:Je,disabled:ne.value},{default:e(()=>[o("Save")]),_:1},8,["disabled"]),t(g,{color:"grey-darken-1 m-1",onClick:l[1]||(l[1]=v=>_e.value=!1)},{default:e(()=>[o("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):u("",!0)]),_:1})]),_:1}),t(p,null,{default:e(()=>[t(c,null,{default:e(()=>[Mt,Rt,o(" "+d(i(s).name),1)]),_:1})]),_:1}),i(ze)?(n(),h(p,{key:0},{default:e(()=>[y.$page.props.auth.user.role!="Customer"?(n(),h(c,{key:0},{default:e(()=>[Et,Ut,o(" ₦"+d(F.value?i(Ie).format(F.value):" --:--")+" ",1),i(V)&&!$.value?(n(),h(g,{key:0,elevation:"0","prepend-icon":"mdi-pencil",class:"mr-2 no-padding no-border"},{default:e(()=>[t(z,{modelValue:ge.value,"onUpdate:modelValue":l[5]||(l[5]=v=>ge.value=v),activator:"parent","location-strategy":"connected","scroll-strategy":"static"},{default:e(()=>[t(A,{"max-width":"400",class:"p-1"},{default:e(()=>[t(B,{class:"pb-0"},{default:e(()=>[t(ce,{modelValue:F.value,"onUpdate:modelValue":l[3]||(l[3]=v=>F.value=v),"hide-details":"",id:"price",type:"number",variant:"outlined",label:"Price",prefix:"₦",style:{width:"200px"},loading:re.value},null,8,["modelValue","loading"]),be.value.length?(n(),m("div",Yt,d(be.value),1)):u("",!0),Z.value.length?(n(),m("p",zt,d(Z.value),1)):u("",!0)]),_:1}),t(Y,null,{default:e(()=>[t(g,{color:"blue-darken-1 m-1",onClick:Ke,disabled:re.value},{default:e(()=>[o("Save")]),_:1},8,["disabled"]),t(g,{color:"grey-darken-1 m-1",onClick:l[4]||(l[4]=v=>ge.value=!1)},{default:e(()=>[o("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):u("",!0)]),_:1})):u("",!0)]),_:1})):u("",!0),F.value&&U.value&&!$.value&&!i(C)&&i(T)&&L.value!="Cancelled"?(n(),h(p,{key:1},{default:e(()=>[t(c,{cols:"12"},{default:e(()=>[t(g,{color:"blue-darken-1",onClick:Ge},{default:e(()=>[o("Issue Invoice")]),_:1})]),_:1})]),_:1})):u("",!0),i(He)&&i(C)?(n(),h(p,{key:2},{default:e(()=>[t(c,{cols:"12"},{default:e(()=>[Ht,Lt,o(" "+d(O.value?"Paid":"Unpaid"),1)]),_:1}),t(c,{cols:"12"},{default:e(()=>[t(i(Oe),{class:"font-bold underline",href:y.route("invoice",[R.value.id])},{default:e(()=>[o("Open Invoice")]),_:1},8,["href"])]),_:1})]),_:1})):u("",!0),i(C)&&O.value?(n(),h(p,{key:3},{default:e(()=>[t(c,{cols:"12"},{default:e(()=>[Wt,jt,o(" "+d(We.value),1)]),_:1})]),_:1})):u("",!0)]),_:1}),t(c,{cols:"12",md:"6"},{default:e(()=>[t(p,null,{default:e(()=>[t(c,null,{default:e(()=>[qt,Gt,o(" "+d(y.$page.props.order.item.name),1)]),_:1})]),_:1}),t(p,null,{default:e(()=>[t(c,null,{default:e(()=>[Qt,Zt,o(" "+d(y.$page.props.order.quantity),1)]),_:1})]),_:1}),t(p,null,{default:e(()=>[t(c,null,{default:e(()=>[Jt,Kt,o(" "+d($.value?"On Hold":L.value),1)]),_:1})]),_:1}),t(p,null,{default:e(()=>[t(c,null,{default:e(()=>[Xt,el,o(" "+d(Ve.value),1)]),_:1})]),_:1}),i(X)&&De.value?(n(),h(p,{key:0},{default:e(()=>[t(c,null,{default:e(()=>[t(g,{"prepend-icon":"mdi-play",color:"blue-darken-3",onClick:et,disabled:we.value},{default:e(()=>[o("Start Next Process")]),_:1},8,["disabled"]),we.value?(n(),m("p",tl,[t(Pe,{color:"red",indeterminate:""})])):u("",!0),K.value.length?(n(),m("p",ll,d(K.value),1)):u("",!0)]),_:1})]),_:1})):u("",!0),ue.value.length?(n(),h(p,{key:1},{default:e(()=>[t(c,null,{default:e(()=>[o(d(ue.value),1)]),_:1})]),_:1})):u("",!0),i(D).isAdmin?(n(),h(p,{key:2},{default:e(()=>[t(c,null,{default:e(()=>[t(i(Oe),{class:"font-bold underline",href:y.route("tasks.order.dashboard",[i(s).id])},{default:e(()=>[o("View Tasks")]),_:1},8,["href"])]),_:1})]),_:1})):u("",!0),t(p,null,{default:e(()=>[t(c,null,{default:e(()=>[al,ol,o(" "+d(i(H)(i(s).created_at).calendar()),1)]),_:1})]),_:1}),t(p,null,{default:e(()=>[t(c,null,{default:e(()=>[sl,nl,o(" "+d(i(H)(i(s).updated_at).fromNow()),1)]),_:1})]),_:1}),t(p,null,{default:e(()=>[t(c,null,{default:e(()=>[rl,dl,o(" "+d(i(H)(i(x).date).format("LL")),1)]),_:1})]),_:1}),t(p,null,{default:e(()=>[t(c,null,{default:e(()=>[il,ul,o(" "+d(de.value??"NOT SET")+" ",1),i(N)&&!$.value?(n(),h(g,{key:0,elevation:"0","prepend-icon":"mdi-pencil",class:"mr-2 no-padding no-border"},{default:e(()=>[t(z,{modelValue:ye.value,"onUpdate:modelValue":l[8]||(l[8]=v=>ye.value=v),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(A,{"max-width":"400",class:"p-1"},{default:e(()=>[t(B,{class:"pb-0"},{default:e(()=>[t(ce,{modelValue:de.value,"onUpdate:modelValue":l[6]||(l[6]=v=>de.value=v),"hide-details":"",id:"order-number",variant:"outlined",label:"Waybill Number",style:{"min-width":"200px"},loading:ie.value},null,8,["modelValue","loading"]),nt(a("div",{class:"text-center font-bold"},d(he.value),513),[[rt,he.value.length]]),J.value.length?(n(),m("p",cl,d(J.value),1)):u("",!0)]),_:1}),t(Y,null,{default:e(()=>[t(g,{color:"blue-darken-1 m-1",onClick:Xe,disabled:ie.value},{default:e(()=>[o("Save")]),_:1},8,["disabled"]),t(g,{color:"grey-darken-1 m-1",onClick:l[7]||(l[7]=v=>ye.value=!1)},{default:e(()=>[o("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):u("",!0)]),_:1})]),_:1})]),_:1})]),_:1}),t(p,null,{default:e(()=>[t(c,null,{default:e(()=>[pl,vl,o(" "+d(i(s).note),1)]),_:1})]),_:1}),fl,t(p,null,{default:e(()=>[t(c,null,{default:e(()=>[!$.value&&i(P)?(n(),h(g,{key:0,color:"grey-darken-3","prepend-icon":"mdi-pause",class:"mr-2 mb-2"},{default:e(()=>[o(" Hold Order "),t(z,{modelValue:ae.value,"onUpdate:modelValue":l[11]||(l[11]=v=>ae.value=v),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(A,{"max-width":"400",class:"p-3"},{default:e(()=>[t(pe,null,{default:e(()=>[o("What's Wrong?")]),_:1}),t(B,null,{default:e(()=>[ml,t(Te,{modelValue:j.value,"onUpdate:modelValue":l[9]||(l[9]=v=>j.value=v),"hide-details":"",id:"hold-order",variant:"outlined",label:"Leave a note for team members",loading:le.value},null,8,["modelValue","loading"]),Fe.value.length?(n(),m("p",_l,d(Fe.value),1)):u("",!0),q.value&&q.value.length?(n(),m("p",bl,d(q.value),1)):u("",!0)]),_:1}),t(Y,null,{default:e(()=>[t(g,{color:"red-darken-1",onClick:Qe,disabled:le.value},{default:e(()=>[o("Continue")]),_:1},8,["disabled"]),t(g,{color:"blue-darken-1",onClick:l[10]||(l[10]=v=>ae.value=!1)},{default:e(()=>[o("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):u("",!0),$.value&&i(D).isAdmin&&L.value!="Cancelled"?(n(),h(g,{key:1,color:"white",class:"mr-2 mb-2","prepend-icon":"mdi-play"},{default:e(()=>[o(" Reactivate "),t(z,{modelValue:se.value,"onUpdate:modelValue":l[13]||(l[13]=v=>se.value=v),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(A,{"max-width":"400",class:"p-3"},{default:e(()=>[t(pe,null,{default:e(()=>[o("Confirm Action!")]),_:1}),t(B,null,{default:e(()=>[gl,oe.value?(n(),m("p",hl,[t(xe,{color:"red",indeterminate:""})])):u("",!0),Be.value.length?(n(),m("p",yl,d(Be.value),1)):u("",!0),G.value&&G.value.length?(n(),m("p",wl,d(G.value),1)):u("",!0)]),_:1}),t(Y,null,{default:e(()=>[t(g,{color:"red-darken-1 m-1",onClick:Ze,disabled:oe.value},{default:e(()=>[o("Yes Proceed")]),_:1},8,["disabled"]),t(g,{color:"blue-darken-1 m-1",onClick:l[12]||(l[12]=v=>se.value=!1)},{default:e(()=>[o("Don't")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):u("",!0),i(k)?(n(),h(g,{key:2,color:"red-darken-1",class:"mr-2 mb-2","prepend-icon":"mdi-cancel"},{default:e(()=>[o(" Cancel Order "),t(z,{modelValue:E.value,"onUpdate:modelValue":l[15]||(l[15]=v=>E.value=v),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(A,{"max-width":"400",class:"p-3"},{default:e(()=>[t(pe,null,{default:e(()=>[o("Heads Up!")]),_:1}),t(B,null,{default:e(()=>[xl,te.value?(n(),m("p",kl,[t(xe,{color:"red",indeterminate:""})])):u("",!0),ee.value.length?(n(),m("p",Cl,d(ee.value),1)):u("",!0),W.value&&W.value.length?(n(),m("p",Vl,d(W.value),1)):u("",!0)]),_:1}),t(Y,null,{default:e(()=>[t(g,{color:"red-darken-1 m-1",onClick:je,disabled:te.value},{default:e(()=>[o("Yes Proceed")]),_:1},8,["disabled"]),t(g,{color:"blue-darken-1 m-1",onClick:l[14]||(l[14]=v=>E.value=!1)},{default:e(()=>[o("Don't")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):u("",!0)]),_:1})]),_:1})]),_:1},8,["cardColor"])}}},Ne=M=>(it("data-v-3df9cede"),M=M(),ut(),M),Tl=Ne(()=>a("thead",null,[a("tr",null,[a("td",null,"Process"),a("td",null,"Completed by"),a("td",null,"Date")])],-1)),$l={key:0,class:"text-right mb-2"},Ol=Ne(()=>a("h3",{class:"text-red"},"No asset was uploaded for this order!",-1)),Nl=Ne(()=>a("p",null,"If you do not have the image files, kindly contact the client using the phone number below.",-1)),Dl={__name:"Detail",setup(M){f().props.order;const D=f().props.orderDetail,s=f().props.activities,x=Ee({orderFiles:D.files}),C=T=>{for(let O=0;O<x.orderFiles.length;O++)x.orderFiles[O].file.id==T.id&&x.orderFiles.splice(O,1)};return(T,O)=>{const R=_("VTable"),w=_("VCol"),P=_("VRow");return n(),m(ke,null,[t(i(dt),{title:"Order"}),t(ct,null,{default:e(()=>[t(i(Oe),{href:T.route("orders"),class:"font-bold"},{default:e(()=>[o("Back")]),_:1},8,["href"]),t(P,null,{default:e(()=>[t(w,{cols:"12",sm:"6"},{default:e(()=>[t(Pl),i(s).length?(n(),h(ve,{key:0,"snippet-title":"Activities"},{default:e(()=>[t(R,null,{default:e(()=>[Tl,a("tbody",null,[(n(!0),m(ke,null,$e(i(s),k=>(n(),m("tr",{key:k.id},[a("td",null,d(k.process.name),1),a("td",null,d(k.staff.name),1),a("td",null,d(i(H)(k.created_at).format("MM/DD/YYYY, h:mm A")),1)]))),128))])]),_:1})]),_:1})):u("",!0)]),_:1}),t(w,{cols:"12",sm:"6"},{default:e(()=>[t(yt),t(ve,{"snippet-title":"Customer Feedback"},{default:e(()=>[t(ft)]),_:1})]),_:1})]),_:1}),t(ve,{"snippet-title":"Order Assets"},{default:e(()=>[x.orderFiles.length?(n(),m("div",$l,[t(xt,{files:x.orderFiles},null,8,["files"])])):u("",!0),x.orderFiles.length?(n(),h(P,{key:1},{default:e(()=>[(n(!0),m(ke,null,$e(x.orderFiles,(k,b)=>(n(),h(w,{cols:"12",lg:"6",key:b},{default:e(()=>[t(vt,{orderImage:k.file,view:"Detail",onPageRemoved:C,onPageDataUpdated:V=>{T.updatePageData(V,k)}},null,8,["orderImage","onPageDataUpdated"])]),_:2},1024))),128))]),_:1})):(n(),h(P,{key:2},{default:e(()=>[t(w,{class:"text-center"},{default:e(()=>[Ol,Nl]),_:1})]),_:1}))]),_:1})]),_:1})],64)}}},Yl=Ue(Dl,[["__scopeId","data-v-3df9cede"]]);export{Yl as default};
