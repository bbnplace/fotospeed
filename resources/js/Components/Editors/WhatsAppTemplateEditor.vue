<template>
    <form @submit.prevent="submit">
         <v-row>
            <v-col cols="12">
                <VTextField
                    id="name"
                    v-model="form.name"
                    label="Template Name"
                    variant="outlined"
                    autocomplete="name"
                    :hide-details="form.errors.name == undefined"
                    :error-messages="form.errors.name"
                ></VTextField>
            </v-col>
            <v-col cols="12">
                <VTextarea
                    id="template"
                    v-model="form.template"
                    label="Template Body"
                    variant="outlined"
                    :hide-details="form.errors.template == undefined"
                    :error-messages="form.errors.template"
                    density="compact"
                ></VTextarea>
            </v-col>
    
            <v-col class="flex items-center justify-end">
                <v-btn
                    prepend-icon="mdi-content-save"
                    :disabled="form.processing"
                    color="grey-darken-3"
                    type="submit"
                >Save</v-btn>
            </v-col>
         </v-row>
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
 