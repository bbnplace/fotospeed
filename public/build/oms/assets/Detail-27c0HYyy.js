import{Q as f,s as Ue,f as r,v as ot,A as st,r as m,g,o as n,w as e,c as h,b as a,F as Ve,m as $e,t as i,u,a as t,d as o,h as nt,x as F,T as rt,i as p,j as Ne,I as dt,J as it,Z as ut,p as ct,e as pt}from"./app-B68t2wHO.js";import{_ as vt}from"./BackendLayout-BElUAjgf.js";import{F as ft,_ as mt}from"./OrderForm.vue_vue_type_script_setup_true_lang-DEEZ38o4.js";import{_ as fe}from"./Panel.vue_vue_type_script_setup_true_lang-BBMLeVRf.js";import{h as L}from"./indigo-C75bPcgm.js";import{_ as Ye}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{C as _t}from"./CustomerFeedback-CNJb6deo.js";import"./ApplicationLogo-Bb1V6LID.js";import"./Snackbar.vue_vue_type_script_setup_true_lang-C1kZ-FEH.js";const bt={key:0},gt={class:"font-bold"},ht={key:1},yt={class:"text-right"},wt={__name:"CommunicationLog",setup(E){const R=f().props.order,s=f().props.auth.user,x=Ue({newMessage:""}),C=r([]);let O=null;const N=async()=>{const _={message:x.newMessage,orderId:R.id},D=(await F.post(route("order.log.write"),_,{headers:{"Content-Type":"application/json"}})).data;D.status!==void 0&&D.status=="success"&&(x.newMessage="",D.data&&C.value.push(D.data))},A=async()=>{const _=await F.get(route("order.log",[R.id]));C.value=_.data},w=(_,V)=>{Notification.permission==="granted"?new Notification(`New message from ${V}`,{body:_,icon:"/favicon.ico"}):console.log(`New message from ${V}: ${_}`)},P=()=>{O=window.Echo.private(`order-chat.${R.id}`),O.listen(".new-message",_=>{console.log("New message received:",_),C.value.push({id:_.id,message:_.message,created_at:_.created_at,user:_.user}),w(_.message,_.user.name)})},k=()=>{O&&(window.Echo.leave(`order-chat.${R.id}`),O=null)};return ot(()=>{A(),P(),Notification.permission==="default"&&Notification.requestPermission()}),st(()=>{k()}),(_,V)=>{const D=m("VCard"),ee=m("VTextarea"),te=m("VBtn");return n(),g(fe,{"snippet-title":"Communication Log"},{default:e(()=>[C.value.length?(n(),h("div",bt,[(n(!0),h(Ve,null,$e(C.value,(T,me)=>(n(),h("div",{key:me,class:"task-card px-2"},[a("p",gt,i(u(L)(T.created_at).calendar())+", "+i(T.user.mobile==u(s).mobile?"I":T.user.name)+" wrote:",1),a("p",null,i(T.message),1)]))),128))])):(n(),h("div",ht,[t(D,{class:"mb-2 p-2",color:"grey-lighten-3"},{default:e(()=>[o(" Leave a note for other team members working on this Order. ")]),_:1})])),a("div",null,[a("form",{onSubmit:nt(N,["prevent"])},[t(ee,{modelValue:x.newMessage,"onUpdate:modelValue":V[0]||(V[0]=T=>x.newMessage=T),label:"Type Comment",variant:"outlined",density:"compact",rows:"2","max-rows":"4","auto-grow":"",clearable:""},null,8,["modelValue"]),a("div",yt,[t(te,{"prepend-icon":"mdi-note-plus",color:"black",type:"submit"},{default:e(()=>[o("Add Note")]),_:1})])],32)])]),_:1})}}},xt=Ye(wt,[["__scopeId","data-v-52fc2372"]]),kt=a("div",null,"To keep things organized, we recommend creating a dedicated folder to store all assets for this order.",-1),Ct={__name:"DownloadAllMediaBtn",props:{files:Array},setup(E){const R=route("file.fetch"),s=r(!1),x=E,C=r(!1),O=(w,P,k)=>{C.value=!0;const _=`${R}?filepath=${encodeURIComponent(w)}&type=${P}`;F.get(_,{responseType:"blob"}).then(V=>{const D=new Blob([V.data],{type:V.headers["content-type"]});ft.saveAs(D,k),C.value=!1})},N=async()=>{s.value=!1,x.files.forEach(w=>{const P=A(w.file.uploadedFile),k=w.file.pageNumber?`page-${w.file.pageNumber}.${P}`:w.file.fileInfo.name;O(w.file.uploadedFile,w.file.fileInfo.type,k)})},A=w=>w.split(".").pop().split(/\#|\?/)[0];return(w,P)=>{const k=m("VCardTitle"),_=m("VCardText"),V=m("VBtn"),D=m("VCardActions"),ee=m("VCard"),te=m("VOverlay");return n(),g(V,{color:"blue","prepend-icon":"mdi-download",disabled:C.value},{default:e(()=>[o(" Download All "),t(te,{modelValue:s.value,"onUpdate:modelValue":P[0]||(P[0]=T=>s.value=T),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(ee,{class:"p-3","max-width":"400"},{default:e(()=>[t(k,null,{default:e(()=>[o("Heads Up!")]),_:1}),t(_,null,{default:e(()=>[a("p",null,"You're about to start downloading "+i(x.files.length)+" files.",1),kt]),_:1}),t(D,null,{default:e(()=>[t(V,{onClick:N,color:"blue","prepend-icon":"mdi-download",disabled:C.value},{default:e(()=>[o(" Start Download ")]),_:1},8,["disabled"])]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1},8,["disabled"])}}},Vt=a("hr",null,null,-1),Pt=a("b",null,"Client",-1),Ot=a("br",null,null,-1),Tt=a("br",null,null,-1),$t=a("b",null,"Delivery Address",-1),Nt=a("br",null,null,-1),Dt=a("b",null,"Processing Branch",-1),St=a("br",null,null,-1),It=a("b",null,"Origin Branch",-1),Ft=a("br",null,null,-1),Rt=a("b",null,"Order Number",-1),Bt=a("br",null,null,-1),Mt={key:0,class:"text-center font-bold mt-1 mb-0"},Et={key:1,class:"text-center text-red mt-1 mb-0"},At=a("b",null,"Order Name",-1),Ut=a("br",null,null,-1),Yt=a("b",null,"Price",-1),zt=a("br",null,null,-1),Lt={key:0,class:"text-center font-bold"},Ht={key:1,class:"text-center text-red mt-1 mb-0"},Wt=a("b",null,"Invoice Status",-1),jt=a("br",null,null,-1),qt=a("b",null,"Payment Method",-1),Gt=a("br",null,null,-1),Qt=a("b",null,"Product",-1),Jt=a("br",null,null,-1),Zt=a("b",null,"Quantity",-1),Kt=a("br",null,null,-1),Xt=a("b",null,"Order Status",-1),el=a("br",null,null,-1),tl=a("b",null,"Current Process",-1),ll=a("br",null,null,-1),al={key:0,class:"my-2"},ol={key:1,class:"text-red"},sl=a("b",null,"Order Created",-1),nl=a("br",null,null,-1),rl=a("b",null,"Last Updated",-1),dl=a("br",null,null,-1),il=a("b",null,"Target Delivery Date",-1),ul=a("br",null,null,-1),cl=a("b",null,"WayBill Number",-1),pl=a("br",null,null,-1),vl={key:0,class:"text-center text-red mt-1 mb-0"},fl=a("b",null,"Note",-1),ml=a("br",null,null,-1),_l=a("hr",null,null,-1),bl=a("p",null,[o(" Why do you want to place this order on hold?"),a("br")],-1),gl={key:0,class:"text-center font-bold pt-2"},hl={key:1,class:"text-center text-red mt-1 mb-0"},yl=a("p",null,"Are you sure you want to reactivate this order?",-1),wl={key:0,class:"text-center"},xl={key:1,class:"text-center font-bold"},kl={key:2,class:"text-center text-red mt-1 mb-0"},Cl=a("p",null,[o(" Why do you want to cancel this order?"),a("br")],-1),Vl={key:0,class:"text-center font-bold pt-2"},Pl={key:1,class:"text-center text-red mt-1 mb-0"},Ol={__name:"OrderCard",setup(E){const R=f().props.auth.user,s=f().props.order,x=f().props.orderDetail,C=f().props.hasInvoice,O=f().props.canGenerateInvoice,N=r(f().props.invoicePaid),A=r(f().props.invoice),w=f().props.canEditOrder,P=f().props.canHoldOrder,k=f().props.canReactivateOrder,_=f().props.canCancelOrder,V=f().props.canEditReferenceNumber,D=f().props.canEditPrice,ee=f().props.canEditWaybill,te=f().props.canForwardToNextProcess,T=r(!1),me=f().props.isViewingFromProcessingBranch,ze=f().props.showPriceToProcessingBranch,Le=f().props.showInvoiceToProcessingBranch,He=!me||ze,We=!me||Le,G=r(""),le=r(!1),H=r(s.order_status.name),Pe=r(s.process?s.process.name:"-"),je=s.waybill_number,Se=r(s.human_forwarding),Ie=f().props.invoice,qe=r(Ie==null?"":Ie.payment_method);r(f().props.canApproveOfflinePayment);const Fe=new Intl.NumberFormat("en-US",{style:"decimal",minimumFractionDigits:0,maximumFractionDigits:0}),B=r(""),W=r("");let _e=null;const Ge=async()=>{var l,S,I,c;if(G.value="",B.value="",!W.value||W.value.trim()===""){B.value="Please provide a reason for cancellation.";return}le.value=!0;const y={orderId:s.id,reason:W.value};_e&&_e.cancel("Request cancelled by user"),_e=F.CancelToken.source();try{const d=await F.put(route("order.cancel",[s.id]),y,{headers:{"Content-Type":"application/json"},cancelToken:_e.token});le.value=!1,G.value=d.data.response,H.value=d.data.orderStatus}catch(d){le.value=!1,d.response&&d.response.status===422?B.value=((S=(l=d.response.data.errors)==null?void 0:l.reason)==null?void 0:S[0])||d.response.data.message:d.response&&d.response.status===403?B.value=d.response.data.error||"You do not have permission to cancel orders.":B.value=((c=(I=d.response)==null?void 0:I.data)==null?void 0:c.error)||"Something went wrong! Pls try again later."}setTimeout(()=>{G.value&&(G.value="",W.value="",T.value=!1),B.value=""},5e3)},Qe=rt({orderId:s.id}),Je=()=>{Qe.post(route("invoice.create"))},j=r(s.hold_reason),Re=r(""),ae=r(!1),oe=r(!1),$=r(s.paused),q=r(""),Ze=async()=>{q.value="",ae.value=!0;const y={reason:j.value};try{const l=await F.put(route("order.hold",[s.id]),y,{headers:{"Content-Type":"application/json"}});ae.value=!1,l.data&&l.data.status=="success"&&($.value=!0,oe.value=!1)}catch(l){ae.value=!1,l.response&&l.response.status===422?q.value=l.response.data.message:q.value="Something went wrong! Pls try again later."}setTimeout(()=>{q.value="",oe.value=!1},5e3)},Be=r(""),se=r(!1),ne=r(!1),Q=r(""),Ke=async()=>{se.value=!0;const y={};try{const l=await F.put(route("order.reactivate",[s.id]),y,{headers:{"Content-Type":"application/json"}});se.value=!1,l.data&&l.data.status=="success"&&($.value=!1,j.value="",ne.value=!1)}catch(l){se.value=!1,l.response&&l.response.status===422?Q.value=l.response.data.message:Q.value="Something went wrong! Pls try again later."}setTimeout(()=>{Q.value="",ne.value=!1},5e3)},U=r(s.order_number),re=r(!1),be=r(""),J=r(""),ge=r(!1),Xe=async()=>{J.value="",re.value=!0;const y={orderNumber:U.value};try{const l=await F.put(route("order.set-reference",[s.id]),y,{headers:{"Content-Type":"application/json"}});re.value=!1,l.data&&l.data.status=="success"&&(be.value=l.data.response)}catch(l){re.value=!1,l.response&&l.response.status===422?J.value=l.response.data.message:J.value="Something went wrong! Pls try again later."}setTimeout(()=>{be.value="",J.value="",ge.value=!1},5e3)},M=r(s.total_cost),de=r(!1),he=r(""),Z=r(""),ye=r(!1),et=async()=>{Z.value="",de.value=!0;const y={price:M.value};try{const l=await F.put(route("order.set-price",[s.id]),y,{headers:{"Content-Type":"application/json"}});de.value=!1,l.data&&l.data.status=="success"&&(he.value=l.data.response)}catch(l){de.value=!1,l.response&&l.response.status===422?Z.value=l.response.data.message:Z.value="Something went wrong! Pls try again later."}setTimeout(()=>{he.value="",Z.value="",ye.value=!1},5e3)},ie=r(je),ue=r(!1),we=r(""),K=r(""),xe=r(!1),tt=async()=>{ue.value=!0,K.value="";const y={waybillNumber:ie.value};try{const l=await F.put(route("order.save-waybill",[s.id]),y,{headers:{"Content-Type":"application/json"}});ue.value=!1,l.data&&l.data.status=="success"&&(we.value=l.data.response)}catch(l){ue.value=!1,l.response&&l.response.status===422?K.value=l.response.data.message:K.value="Something went wrong! Pls try again later."}setTimeout(()=>{we.value="",K.value="",xe.value=!1},5e3)},ke=r(!1),ce=r(""),X=r(""),lt=async()=>{ke.value=!0,ce.value="",X.value="";const y={orderId:s.id};try{const l=await F.post(route("order.process.forward"),y,{headers:{"Content-Type":"application/json"}});l.data&&l.data.status=="success"&&(ce.value=l.data.message,Pe.value=l.data.currentProcess,Se.value=!1)}catch(l){l.response&&l.response.status===422?X.value=l.response.data.message:X.value="Something went wrong! Pls try again later."}ke.value=!1,setTimeout(()=>{ce.value="",X.value=""},7e3)},at=()=>{const y=s.user.name,l=s.user.mobile,S=s.delivery_address,I=s.source_branch?s.source_branch.name:"-",c=s.processing_branch.name,d=U.value,b=s.name,pe=s.item.name,Y=s.quantity,z=M.value?`₦${Fe.format(M.value)}`:"--",Oe=$.value?"On Hold":H.value,ve=Pe.value,Ce=L(s.created_at).format("MMMM DD, YYYY"),Te=L(x.date).format("MMMM DD, YYYY"),v=ie.value??"NOT SET",Me=s.note,Ee=window.open("","","height=800,width=900"),Ae=f().props.site.name;Ee.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Order Card - ${d}</title>
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
                    <h1>${Ae}</h1>
                    <div class="order-number">
                        <div class="order-number-label">Order Number</div>
                        <div class="order-number-value">${d}</div>
                    </div>
                </div>
                
                <div class="content">
                    <div class="status-bar">
                        <div>
                            <div class="status-label">Current Status</div>
                            <div class="status-value">${Oe}</div>
                        </div>
                        <div>
                            <div class="status-label">Process</div>
                            <div class="status-value">${ve}</div>
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
                                <div class="info-value">${S}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Order Details</div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Order Name</div>
                                <div class="info-value large">${b}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Product</div>
                                <div class="info-value">${pe}</div>
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
                                <div class="info-value">${I}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Origin Branch</div>
                                <div class="info-value">${c}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Order Date</div>
                                <div class="info-value">${Ce}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">${H.value==="Delivered"||H.value==="Fulfilled"?"Delivery Date":"Estimated Delivery Date"}</div>
                                <div class="info-value">${Te}</div>
                            </div>
                            <div class="info-item full-width">
                                <div class="info-label">Waybill Number</div>
                                <div class="info-value">${v}</div>
                            </div>
                        </div>
                    </div>
                    
                    ${Me?`
                        <div class="section">
                            <div class="section-title">Special Notes</div>
                            <div class="notes-box">
                                <div class="notes-content">${Me}</div>
                            </div>
                        </div>
                    `:""}
                </div>
                
                <div class="footer">
                    <div class="footer-text">This is an official order card from ${Ae}</div>
                    <div class="print-date">Printed on ${L().format("MMMM DD, YYYY [at] h:mm A")}</div>
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
    `),Ee.document.close()};return(y,l)=>{const S=m("VCardText"),I=m("VCard"),c=m("VCol"),d=m("VRow"),b=m("VBtn"),pe=m("VTextField"),Y=m("VCardActions"),z=m("VOverlay"),Oe=m("v-progress-linear"),ve=m("VCardTitle"),Ce=m("VTextarea"),Te=m("v-progress-circular");return n(),g(fe,{snippetTitle:"Order Card",cardColor:$.value?"bg-gray-700 text-white":"bg-white"},{default:e(()=>[j.value&&j.value.length&&$.value?(n(),g(d,{key:0},{default:e(()=>[t(c,{cols:"12"},{default:e(()=>[t(I,{color:"red"},{default:e(()=>[t(S,null,{default:e(()=>[o(i(j.value),1)]),_:1})]),_:1})]),_:1})]),_:1})):p("",!0),t(d,null,{default:e(()=>[t(c,{class:"text-right"},{default:e(()=>[u(w)&&U.value&&U.value.length&&M.value>0?(n(),g(b,{key:0,color:"blue-darken-3","prepend-icon":"mdi-printer",onClick:at},{default:e(()=>[o(" Print Card ")]),_:1})):p("",!0)]),_:1})]),_:1}),Vt,t(d,{id:"order-card"},{default:e(()=>[t(c,{cols:"12",md:"6"},{default:e(()=>[t(d,null,{default:e(()=>[t(c,null,{default:e(()=>[Pt,Ot,o(" "+i(u(s).user.name),1),Tt,o(" "+i(u(s).user.mobile),1)]),_:1})]),_:1}),t(d,null,{default:e(()=>[t(c,null,{default:e(()=>[$t,Nt,o(" "+i(u(s).delivery_address),1)]),_:1})]),_:1}),t(d,null,{default:e(()=>[t(c,null,{default:e(()=>[Dt,St,o(" "+i(u(s).source_branch?u(s).source_branch.name:"-"),1)]),_:1})]),_:1}),t(d,null,{default:e(()=>[t(c,null,{default:e(()=>[It,Ft,o(" "+i(u(s).processing_branch.name),1)]),_:1})]),_:1}),t(d,null,{default:e(()=>[t(c,null,{default:e(()=>[Rt,Bt,o(" "+i(U.value)+" ",1),u(V)&&!$.value?(n(),g(b,{key:0,"prepend-icon":"mdi-pencil",class:"mr-2 no-padding no-border",elevation:"0"},{default:e(()=>[t(z,{modelValue:ge.value,"onUpdate:modelValue":l[2]||(l[2]=v=>ge.value=v),activator:"parent","location-strategy":"connected","scroll-strategy":"static"},{default:e(()=>[t(I,{"max-width":"400",class:"p-1"},{default:e(()=>[t(S,{class:"pb-0"},{default:e(()=>[t(pe,{modelValue:U.value,"onUpdate:modelValue":l[0]||(l[0]=v=>U.value=v),"hide-details":"",id:"order-number",variant:"outlined",label:"Order Number",style:{"min-width":"200px"},loading:re.value},null,8,["modelValue","loading"]),be.value.length?(n(),h("p",Mt,i(be.value),1)):p("",!0),J.value.length?(n(),h("p",Et,i(J.value),1)):p("",!0)]),_:1}),t(Y,null,{default:e(()=>[t(b,{color:"blue-darken-1 m-1",onClick:Xe,disabled:re.value},{default:e(()=>[o("Save")]),_:1},8,["disabled"]),t(b,{color:"grey-darken-1 m-1",onClick:l[1]||(l[1]=v=>ge.value=!1)},{default:e(()=>[o("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):p("",!0)]),_:1})]),_:1}),t(d,null,{default:e(()=>[t(c,null,{default:e(()=>[At,Ut,o(" "+i(u(s).name),1)]),_:1})]),_:1}),u(He)?(n(),g(d,{key:0},{default:e(()=>[y.$page.props.auth.user.role!="Customer"?(n(),g(c,{key:0},{default:e(()=>[Yt,zt,o(" ₦"+i(M.value?u(Fe).format(M.value):" --:--")+" ",1),u(D)&&!$.value?(n(),g(b,{key:0,elevation:"0","prepend-icon":"mdi-pencil",class:"mr-2 no-padding no-border"},{default:e(()=>[t(z,{modelValue:ye.value,"onUpdate:modelValue":l[5]||(l[5]=v=>ye.value=v),activator:"parent","location-strategy":"connected","scroll-strategy":"static"},{default:e(()=>[t(I,{"max-width":"400",class:"p-1"},{default:e(()=>[t(S,{class:"pb-0"},{default:e(()=>[t(pe,{modelValue:M.value,"onUpdate:modelValue":l[3]||(l[3]=v=>M.value=v),"hide-details":"",id:"price",type:"number",variant:"outlined",label:"Price",prefix:"₦",style:{width:"200px"},loading:de.value},null,8,["modelValue","loading"]),he.value.length?(n(),h("div",Lt,i(he.value),1)):p("",!0),Z.value.length?(n(),h("p",Ht,i(Z.value),1)):p("",!0)]),_:1}),t(Y,null,{default:e(()=>[t(b,{color:"blue-darken-1 m-1",onClick:et,disabled:de.value},{default:e(()=>[o("Save")]),_:1},8,["disabled"]),t(b,{color:"grey-darken-1 m-1",onClick:l[4]||(l[4]=v=>ye.value=!1)},{default:e(()=>[o("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):p("",!0)]),_:1})):p("",!0)]),_:1})):p("",!0),M.value&&U.value&&!$.value&&!u(C)&&u(O)&&H.value!="Cancelled"?(n(),g(d,{key:1},{default:e(()=>[t(c,{cols:"12"},{default:e(()=>[t(b,{color:"blue-darken-1",onClick:Je},{default:e(()=>[o("Issue Invoice")]),_:1})]),_:1})]),_:1})):p("",!0),u(We)&&u(C)?(n(),g(d,{key:2},{default:e(()=>[t(c,{cols:"12"},{default:e(()=>[Wt,jt,o(" "+i(N.value?"Paid":"Unpaid"),1)]),_:1}),t(c,{cols:"12"},{default:e(()=>[t(u(Ne),{class:"font-bold underline",href:y.route("invoice",[A.value.id])},{default:e(()=>[o("Open Invoice")]),_:1},8,["href"])]),_:1})]),_:1})):p("",!0),u(C)&&N.value?(n(),g(d,{key:3},{default:e(()=>[t(c,{cols:"12"},{default:e(()=>[qt,Gt,o(" "+i(qe.value),1)]),_:1})]),_:1})):p("",!0)]),_:1}),t(c,{cols:"12",md:"6"},{default:e(()=>[t(d,null,{default:e(()=>[t(c,null,{default:e(()=>[Qt,Jt,o(" "+i(y.$page.props.order.item.name),1)]),_:1})]),_:1}),t(d,null,{default:e(()=>[t(c,null,{default:e(()=>[Zt,Kt,o(" "+i(y.$page.props.order.quantity),1)]),_:1})]),_:1}),t(d,null,{default:e(()=>[t(c,null,{default:e(()=>[Xt,el,o(" "+i($.value?"On Hold":H.value),1)]),_:1})]),_:1}),t(d,null,{default:e(()=>[t(c,null,{default:e(()=>[tl,ll,o(" "+i(Pe.value),1)]),_:1})]),_:1}),u(te)&&Se.value?(n(),g(d,{key:0},{default:e(()=>[t(c,null,{default:e(()=>[t(b,{"prepend-icon":"mdi-play",color:"blue-darken-3",onClick:lt,disabled:ke.value},{default:e(()=>[o("Start Next Process")]),_:1},8,["disabled"]),ke.value?(n(),h("p",al,[t(Oe,{color:"red",indeterminate:""})])):p("",!0),X.value.length?(n(),h("p",ol,i(X.value),1)):p("",!0)]),_:1})]),_:1})):p("",!0),ce.value.length?(n(),g(d,{key:1},{default:e(()=>[t(c,null,{default:e(()=>[o(i(ce.value),1)]),_:1})]),_:1})):p("",!0),u(R).isAdmin?(n(),g(d,{key:2},{default:e(()=>[t(c,null,{default:e(()=>[t(u(Ne),{class:"font-bold underline",href:y.route("tasks.order.dashboard",[u(s).id])},{default:e(()=>[o("View Tasks")]),_:1},8,["href"])]),_:1})]),_:1})):p("",!0),t(d,null,{default:e(()=>[t(c,null,{default:e(()=>[sl,nl,o(" "+i(u(L)(u(s).created_at).calendar()),1)]),_:1})]),_:1}),t(d,null,{default:e(()=>[t(c,null,{default:e(()=>[rl,dl,o(" "+i(u(L)(u(s).updated_at).fromNow()),1)]),_:1})]),_:1}),t(d,null,{default:e(()=>[t(c,null,{default:e(()=>[il,ul,o(" "+i(u(L)(u(x).date).format("LL")),1)]),_:1})]),_:1}),t(d,null,{default:e(()=>[t(c,null,{default:e(()=>[cl,pl,o(" "+i(ie.value??"NOT SET")+" ",1),u(ee)&&!$.value?(n(),g(b,{key:0,elevation:"0","prepend-icon":"mdi-pencil",class:"mr-2 no-padding no-border"},{default:e(()=>[t(z,{modelValue:xe.value,"onUpdate:modelValue":l[8]||(l[8]=v=>xe.value=v),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(I,{"max-width":"400",class:"p-1"},{default:e(()=>[t(S,{class:"pb-0"},{default:e(()=>[t(pe,{modelValue:ie.value,"onUpdate:modelValue":l[6]||(l[6]=v=>ie.value=v),"hide-details":"",id:"order-number",variant:"outlined",label:"Waybill Number",style:{"min-width":"200px"},loading:ue.value},null,8,["modelValue","loading"]),dt(a("div",{class:"text-center font-bold"},i(we.value),513),[[it,we.value.length]]),K.value.length?(n(),h("p",vl,i(K.value),1)):p("",!0)]),_:1}),t(Y,null,{default:e(()=>[t(b,{color:"blue-darken-1 m-1",onClick:tt,disabled:ue.value},{default:e(()=>[o("Save")]),_:1},8,["disabled"]),t(b,{color:"grey-darken-1 m-1",onClick:l[7]||(l[7]=v=>xe.value=!1)},{default:e(()=>[o("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):p("",!0)]),_:1})]),_:1})]),_:1})]),_:1}),t(d,null,{default:e(()=>[t(c,null,{default:e(()=>[fl,ml,o(" "+i(u(s).note),1)]),_:1})]),_:1}),_l,t(d,null,{default:e(()=>[t(c,null,{default:e(()=>[!$.value&&u(P)?(n(),g(b,{key:0,color:"grey-darken-3","prepend-icon":"mdi-pause",class:"mr-2 mb-2"},{default:e(()=>[o(" Hold Order "),t(z,{modelValue:oe.value,"onUpdate:modelValue":l[11]||(l[11]=v=>oe.value=v),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(I,{"max-width":"400",class:"p-3"},{default:e(()=>[t(ve,null,{default:e(()=>[o("What's Wrong?")]),_:1}),t(S,null,{default:e(()=>[bl,t(Ce,{modelValue:j.value,"onUpdate:modelValue":l[9]||(l[9]=v=>j.value=v),"hide-details":"",id:"hold-order",variant:"outlined",label:"Leave a note for team members",loading:ae.value},null,8,["modelValue","loading"]),Re.value.length?(n(),h("p",gl,i(Re.value),1)):p("",!0),q.value&&q.value.length?(n(),h("p",hl,i(q.value),1)):p("",!0)]),_:1}),t(Y,null,{default:e(()=>[t(b,{color:"red-darken-1",onClick:Ze,disabled:ae.value},{default:e(()=>[o("Continue")]),_:1},8,["disabled"]),t(b,{color:"blue-darken-1",onClick:l[10]||(l[10]=v=>oe.value=!1)},{default:e(()=>[o("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):p("",!0),$.value&&u(k)&&H.value!="Cancelled"?(n(),g(b,{key:1,color:"white",class:"mr-2 mb-2","prepend-icon":"mdi-play"},{default:e(()=>[o(" Reactivate "),t(z,{modelValue:ne.value,"onUpdate:modelValue":l[13]||(l[13]=v=>ne.value=v),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(I,{"max-width":"400",class:"p-3"},{default:e(()=>[t(ve,null,{default:e(()=>[o("Confirm Action!")]),_:1}),t(S,null,{default:e(()=>[yl,se.value?(n(),h("p",wl,[t(Te,{color:"red",indeterminate:""})])):p("",!0),Be.value.length?(n(),h("p",xl,i(Be.value),1)):p("",!0),Q.value&&Q.value.length?(n(),h("p",kl,i(Q.value),1)):p("",!0)]),_:1}),t(Y,null,{default:e(()=>[t(b,{color:"red-darken-1 m-1",onClick:Ke,disabled:se.value},{default:e(()=>[o("Yes Proceed")]),_:1},8,["disabled"]),t(b,{color:"blue-darken-1 m-1",onClick:l[12]||(l[12]=v=>ne.value=!1)},{default:e(()=>[o("Don't")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):p("",!0),u(_)?(n(),g(b,{key:2,color:"red-darken-1",class:"mr-2 mb-2","prepend-icon":"mdi-cancel"},{default:e(()=>[o(" Cancel Order "),t(z,{modelValue:T.value,"onUpdate:modelValue":l[16]||(l[16]=v=>T.value=v),activator:"parent","location-strategy":"connected","scroll-strategy":"close"},{default:e(()=>[t(I,{"max-width":"400",class:"p-3"},{default:e(()=>[t(ve,null,{default:e(()=>[o("Cancel Order")]),_:1}),t(S,null,{default:e(()=>[Cl,t(Ce,{modelValue:W.value,"onUpdate:modelValue":l[14]||(l[14]=v=>W.value=v),"hide-details":"",id:"cancel-order-reason",variant:"outlined",label:"Reason for cancellation *",loading:le.value},null,8,["modelValue","loading"]),G.value.length?(n(),h("p",Vl,i(G.value),1)):p("",!0),B.value&&B.value.length?(n(),h("p",Pl,i(B.value),1)):p("",!0)]),_:1}),t(Y,null,{default:e(()=>[t(b,{color:"red-darken-1",onClick:Ge,disabled:le.value||!W.value},{default:e(()=>[o("Cancel Order")]),_:1},8,["disabled"]),t(b,{color:"blue-darken-1",onClick:l[15]||(l[15]=v=>T.value=!1)},{default:e(()=>[o("Close")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})):p("",!0)]),_:1})]),_:1})]),_:1},8,["cardColor"])}}},De=E=>(ct("data-v-3df9cede"),E=E(),pt(),E),Tl=De(()=>a("thead",null,[a("tr",null,[a("td",null,"Process"),a("td",null,"Completed by"),a("td",null,"Date")])],-1)),$l={key:0,class:"text-right mb-2"},Nl=De(()=>a("h3",{class:"text-red"},"No asset was uploaded for this order!",-1)),Dl=De(()=>a("p",null,"If you do not have the image files, kindly contact the client using the phone number below.",-1)),Sl={__name:"Detail",setup(E){f().props.order;const R=f().props.orderDetail,s=f().props.activities,x=Ue({orderFiles:R.files}),C=O=>{for(let N=0;N<x.orderFiles.length;N++)x.orderFiles[N].file.id==O.id&&x.orderFiles.splice(N,1)};return(O,N)=>{const A=m("VTable"),w=m("VCol"),P=m("VRow");return n(),h(Ve,null,[t(u(ut),{title:"Order"}),t(vt,null,{default:e(()=>[t(u(Ne),{href:O.route("orders"),class:"font-bold"},{default:e(()=>[o("Back")]),_:1},8,["href"]),t(P,null,{default:e(()=>[t(w,{cols:"12",sm:"6"},{default:e(()=>[t(Ol),u(s).length?(n(),g(fe,{key:0,"snippet-title":"Activities"},{default:e(()=>[t(A,null,{default:e(()=>[Tl,a("tbody",null,[(n(!0),h(Ve,null,$e(u(s),k=>(n(),h("tr",{key:k.id},[a("td",null,i(k.process.name),1),a("td",null,i(k.staff.name),1),a("td",null,i(u(L)(k.created_at).format("MM/DD/YYYY, h:mm A")),1)]))),128))])]),_:1})]),_:1})):p("",!0)]),_:1}),t(w,{cols:"12",sm:"6"},{default:e(()=>[t(xt),t(fe,{"snippet-title":"Customer Feedback"},{default:e(()=>[t(_t)]),_:1})]),_:1})]),_:1}),t(fe,{"snippet-title":"Order Assets"},{default:e(()=>[x.orderFiles.length?(n(),h("div",$l,[t(Ct,{files:x.orderFiles},null,8,["files"])])):p("",!0),x.orderFiles.length?(n(),g(P,{key:1},{default:e(()=>[(n(!0),h(Ve,null,$e(x.orderFiles,(k,_)=>(n(),g(w,{cols:"12",lg:"6",key:_},{default:e(()=>[t(mt,{orderImage:k.file,view:"Detail",onPageRemoved:C,onPageDataUpdated:V=>{O.updatePageData(V,k)}},null,8,["orderImage","onPageDataUpdated"])]),_:2},1024))),128))]),_:1})):(n(),g(P,{key:2},{default:e(()=>[t(w,{class:"text-center"},{default:e(()=>[Nl,Dl]),_:1})]),_:1}))]),_:1})]),_:1})],64)}}},zl=Ye(Sl,[["__scopeId","data-v-3df9cede"]]);export{zl as default};
