
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
            <v-col cols="12" class="mt-3">
                <VTextField
                    id="title"
                    v-model="form.title"
                    label="Title"
                    variant="outlined"
                    :hide-details="form.errors.title == undefined"
                    :error-messages="form.errors.title"
                ></VTextField>
            </v-col>
            <v-col cols="12">
                <VTextarea
                    id="template"
                    v-model="form.template"
                    label="Body"
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
 