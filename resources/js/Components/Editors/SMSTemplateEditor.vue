
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
                    label="SMS Template"
                    variant="outlined"
                    :hide-details="form.errors.template == undefined"
                    :error-messages="form.errors.template"
                    density="compact"
                ></VTextarea>
            </v-col>
    
            <v-col cols="12" class="flex justify-end">
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
 
 interface SMSTemplateEditor {
     id?: Number,
     name: String,
     template: String,
     usage?: String,
     target?: String,
     timing?: String,
 }
 
 const props = defineProps<{
     smsTemplate: SMSTemplateEditor
 }>()
 
 const usages = usePage().props.usage;
 const targets = usePage().props.targets;
 const timings = usePage().props.timings;

 const form = useForm({
     id: props.smsTemplate ? props.smsTemplate.id: "",
     name: props.smsTemplate ? props.smsTemplate.name: "",
     template: props.smsTemplate ? props.smsTemplate.template: "",
     usage: props.smsTemplate ? props.smsTemplate.usage : "",
     target: props.smsTemplate ? props.smsTemplate.target : "",
     timing: props.smsTemplate ? props.smsTemplate.timing : "",
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
 