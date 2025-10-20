<template>
    <DefaultLayout>
       <div class="container">
            <Panel snippetTitle="New Order">
                <OrderEditor></OrderEditor>
            </Panel>
        </div>
    </DefaultLayout>
</template>

<script setup>
import DefaultLayout from '../../../Layouts/DefaultLayout.vue';
import OrderEditor from "@/Components/Editors/OrderEditor.vue";
import Panel from "@/Layouts/Shared/Panel.vue";
</script>