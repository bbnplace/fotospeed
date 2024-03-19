<template>
   <form @submit.prevent="submit">
        <div>
            <VTextField
                id="name"
                v-model="form.name"
                label="Group Name"
                variant="outlined"
                autocomplete="name"
                :hide-details="form.errors.name == undefined"
                :error-messages="form.errors.name"
            ></VTextField>
        </div>
        <div class="mt-4">
            <VTextarea
                id="template"
                v-model="form.template"
                label="SMS Template"
                variant="outlined"
                :hide-details="form.errors.template == undefined"
                :error-messages="form.errors.template"
                density="compact"
            ></VTextarea>
        </div>

        <div class="flex items-center justify-end mt-4">

            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </div>
    </form>
</template>

<script setup lang="ts">
import { useForm, usePage, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

interface SMSTemplateEditor {
    id?: Number,
    name: String,
    template: String
}

const props = defineProps<{
    smsTemplate: SMSTemplateEditor
}>()

const form = useForm({
    id: props.smsTemplate ? props.smsTemplate.id: "",
    name: props.smsTemplate ? props.smsTemplate.name: "",
    template: props.smsTemplate ? props.smsTemplate.template: "",
});

const submit = () => {
    if(props.smsTemplate) {
        form.put(route('sms-template.edit', [props.smsTemplate.id]), {
            onFinish: () => {},
        });
    } else {
        form.post(route('sms-template.add'), {
            onFinish: () => {},
        });
    }
};
</script>

<style lang="scss" scoped>

</style>
