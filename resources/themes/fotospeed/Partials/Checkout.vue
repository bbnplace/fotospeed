<template>
    <!-- Checkout Section Start -->
    <section class="checkout-section fix section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="#" method="post">
                        <div class="row g-4">
                            <div class="col-md-5 col-lg-4 col-xl-3">
                                <div class="checkout-radio">
                                    <p class="primary-text">Select any one</p>
                                    <div class="checkout-radio-wrapper">
                                        <div class="checkout-radio-single">
                                            <input type="checkbox" class="form-check-input" id="cCard" name="pay-method" value="Credit/Debit Cards">
                                            <label for="cCard">Credit/Debit Cards</label>
                                        </div>
                                        <div class="checkout-radio-single">
                                            <input type="checkbox" class="form-check-input" id="paypal" name="pay-method" value="PayPal">
                                            <label for="paypal">PayPal</label>
                                        </div>
                                        <div class="checkout-radio-single">
                                            <input type="checkbox" class="form-check-input" id="payoneer" name="pay-method" value="Payoneer">
                                            <label for="payoneer">Payoneer</label>
                                        </div>
                                        <div class="checkout-radio-single">
                                            <input type="checkbox" class="form-check-input" id="visa" name="pay-method" value="Visa">
                                            <label for="visa">Visa</label>
                                        </div>
                                        <div class="checkout-radio-single">
                                            <input type="checkbox" class="form-check-input" id="mastercard" name="pay-method" value="Mastercard">
                                            <label for="mastercard">Mastercard</label>
                                        </div>
                                        <div class="checkout-radio-single">
                                            <input type="checkbox" class="form-check-input" id="fastPay" name="pay-method" value="Fastpay">
                                            <label for="fastPay">Fastpay</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7 col-lg-8 col-xl-9">
                                <div class="checkout-single-wrapper">
                                    <div class="checkout-single boxshado-single">
                                        <h4>Billing address</h4>
                                        <div class="checkout-single-form">
                                            <div class="row g-4">
                                                <div class="col-lg-6">
                                                <div class="input-single">
                                                    <input type="text" name="user-first-name" id="userFirstName" required="" placeholder="First Name">
                                                </div>
                                                </div>
                                                <div class="col-lg-6">
                                                <div class="input-single">
                                                    <input type="text" name="user-last-name" id="userLastName" required="" placeholder="Last Name">
                                                </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="input-single">
                                                        <input type="email" name="user-check-email" id="userCheckEmail" required="" placeholder="Your Email">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="input-single">
                                                        <select class="country-select">
                                                        <option value="usa">USA</option>
                                                        <option value="aus">Aus</option>
                                                        <option value="uk">UK</option>
                                                        <option value="ned">NED</option>
                                                        </select><div class="nice-select country-select" tabindex="0"><span class="current">USA</span><ul class="list"><li data-value="usa" class="option selected">USA</li><li data-value="aus" class="option">Aus</li><li data-value="uk" class="option">UK</li><li data-value="ned" class="option">NED</li></ul></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="input-single">
                                                        <textarea name="user-address" id="userAddress" placeholder="Address"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="checkout-single checkout-single-bg">
                                        <h4>Payment Methods</h4>
                                        <div class="checkout-single-form">
                                            <p class="payment"></p>
                                            <div class="row g-3">
                                            <div class="col-lg-12">
                                                <div class="input-single">
                                                    <label for="userCardNumber">Card number</label>
                                                    <input type="number" name="user-card-number" id="userCardNumber" placeholder="0000 0000 0000 0000">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="input-single">
                                                    <label for="userCardDate">Expiry date</label>
                                                    <input type="text" id="userCardDate" placeholder="DD/MM/YY">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="input-single">
                                                    <label for="userCvc">Cvc / Cvv</label>
                                                    <input type="text" maxlength="3" name="user-card-cvc" id="userCvc" required="" placeholder="3 Digits">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="input-single">
                                                    <label for="userCardName">Name on card</label>
                                                    <input type="text" name="user-card-name" id="userCardName" placeholder="Name">
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="input-single input-check payment-save">
                                            <input type="checkbox" class="form-check-input" name="save-for-next" id="saveForNext">
                                            <label for="saveForNext">Save for my next payment</label>
                                        </div>
                                        <div class="mt-4">
                                            <a href="checkout.html" class="theme-btn">
                                                <span> Payment Now</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>