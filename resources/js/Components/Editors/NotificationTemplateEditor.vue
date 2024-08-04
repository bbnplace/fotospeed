
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
         <div class="mt-3">
             <VTextField
                 id="title"
                 v-model="form.title"
                 label="Title"
                 variant="outlined"
                 :hide-details="form.errors.title == undefined"
                 :error-messages="form.errors.title"
             ></VTextField>
         </div>
         <div class="mt-1">
             <VTextarea
                 id="template"
                 v-model="form.template"
                 label="Body"
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
 
 interface NotificationTemplateEditor {
     id?: Number,
     name: String,
     title: String,
     template: String
 }
 
 const props = defineProps<{
     notificationTemplate: NotificationTemplateEditor
 }>()
 
 const form = useForm({
     id: props.notificationTemplate ? props.notificationTemplate.id: "",
     name: props.notificationTemplate ? props.notificationTemplate.name: "",
     title: props.notificationTemplate ? props.notificationTemplate.title: "",
     template: props.notificationTemplate ? props.notificationTemplate.template: "",
 });
 
 const submit = () => {
     if(props.notificationTemplate) {
         form.put(route('notification-template.edit', [props.notificationTemplate.id]), {
             onFinish: () => {},
         });
     } else {
         form.post(route('notification-template.add'), {
             onFinish: () => {},
         });
     }
 };
 </script>
 
 <style lang="scss" scoped>
 
 </style>
 