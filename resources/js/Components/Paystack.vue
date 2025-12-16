<template>
    <form @submit.prevent="payWithPaystack">
        <VBtn
            color="green"
            type="submit"
        >Make Payment</VBtn>
    </form>
</template>

<script setup>
    import PaystackPop from '@paystack/inline-js';

    const props = defineProps({
        data: Object
    })
    const emit = defineEmits(['paymentCompleted', 'paymentError']);

    const paystackData = props.data;

    const payWithPaystack = () => {
        try {
            var handler = PaystackPop.setup({
                key: paystackData.key, // Replace with your Paystack public key
                email: paystackData.email,
                amount: paystackData.amount * 100, // Paystack uses kobo, so multiply amount by 100
                currency: 'NGN',
                ref: paystackData.reference, // Generate a unique reference
                callback: function(response) {
                    // Perform post-payment actions here like emit the response to the parent component
                    emit('paymentCompleted', response);
                },
                onClose: function() {
                    // alert('Payment window closed');
                }
            });
            handler.openIframe();
        } catch (error) {
            console.error('Paystack initialization error:', error);
            emit('paymentError', error);
        }
    };
</script>

<style lang="scss" scoped>

</style>
