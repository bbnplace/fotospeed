<template>
    <form @submit.prevent="submit">
         <div>
             <VTextField
                 id="name"
                 v-model="form.name"
                 label="Template Name"
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
                 label="WhatsApp Template"
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
 
 interface WhatsappTemplateEditor {
     id?: Number,
     name: String,
     template: String
 }
 
 const props = defineProps<{
     whatsAppTemplate: WhatsappTemplateEditor
 }>()
 
 const form = useForm({
     id: props.whatsAppTemplate ? props.whatsAppTemplate.id: "",
     name: props.whatsAppTemplate ? props.whatsAppTemplate.name: "",
     template: props.whatsAppTemplate ? props.whatsAppTemplate.template: "",
 });
 
 const submit = () => {
     if(props.whatsAppTemplate) {
         form.put(route('whatsapp-template.edit', [props.whatsAppTemplate.id]), {
             onFinish: () => {},
         });
     } else {
         form.post(route('whatsapp-template.add'), {
             onFinish: () => {},
         });
     }
 };
 </script>
 
 <style lang="scss" scoped>
 
 </style>
 