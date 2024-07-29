<template>
    <Panel snippet-title="Production Processes">
            <v-expansion-panels v-if="productProcesses.length">
                <v-expansion-panel draggable v-for="(process, index) in productProcesses" :key="index">
                    <v-expansion-panel-title color="blue">{{ process.name }}</v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <h4 class="my-2">Tasks</h4>
                        
                        <template v-if="productProcessActivities[process.name].length">
                            <VCard :title="`Task ${index+1}`" class="p-2 my-3" color="grey-lighten-5" v-for="(productProcessActivity, index) in productProcessActivities[process.name]" :key="index">
                                <VRow>
                                    <VCol>
                                        <VTextField
                                            id="activityName"
                                            v-model="productProcessActivities[process.name][index].name"
                                            label="Task Name"
                                            variant="outlined"
                                            hide-details
                                            autocomplete="off"
                                            bg-color="white"
                                            @blur="updateProcesses"
                                        ></VTextField>
                                    </VCol>
                                </VRow>
                                <VRow>
                                    <VCol>
                                        <VTextarea
                                            id="description"
                                            v-model="productProcessActivities[process.name][index].description"
                                            label="Description (Optional)"
                                            variant="outlined"
                                            hide-details
                                            density="compact"
                                            autocomplete="off"
                                            bg-color="white"
                                            @blur="updateProcesses"
                                        ></VTextarea>
                                    </VCol>
                                </VRow>
                                <VRow>
                                    <VCol>
                                        <VAutocomplete
                                            id="team"
                                            v-model="productProcessActivities[process.name][index].team"
                                            label="Team"
                                            :items="teams"
                                            variant="outlined"
                                            hide-details
                                            density="compact"
                                            autocomplete="off"
                                            bg-color="white"
                                            @blur="updateProcesses"
                                        ></VAutocomplete>
                                    </VCol>
                                </VRow>
                                <div class="mt-2 text-right">
                                    <VBtn
                                        prepend-icon="mdi-minus"
                                        color="grey-darken-3"
                                        @click="removeActivity(process.name, index)"
                                        >Remove
                                    </VBtn>
                                </div>
                            </VCard>
                        </template>
                        
                        <VCard color="grey-darken-4" v-else class="p-3 my-2">
                            You have not defined any task for this process.
                        </VCard>
                        
                        <div class="mt-2">
                            <VBtn
                                prepend-icon="mdi-plus"
                                color="blue-darken-3"
                                @click="addActivity(process.name)"
                                >Add Task
                            </VBtn>
                        </div>
                        <VRow class="my-2">
                            <VCol cols="12" class="font-bold">
                                After all tasks in this process are completed.
                            </VCol>
                            <VCol>
                                <v-checkbox
                                    v-model="productProcesses[index].autoStartNextProcess"
                                    label="Trigger Next Process"
                                ></v-checkbox>
                                <VAutocomplete
                                    id="nextProcess"
                                    v-model="productProcesses[index].nextProcess"
                                    label="Select Process"
                                    :items="nextProcesses"
                                    variant="outlined"
                                    hide-details
                                    density="compact"
                                    aria-autocomplete="off"
                                    autocomplete="off"
                                    bg-color="white"
                                    color="black"
                                    @blur="updateProcesses"
                                ></VAutocomplete>
                            </VCol>
                            <VCol>
                                Send notification to:
                                <VAutocomplete
                                    id="team"
                                    v-model="productProcesses[index].whoCoordinates"
                                    label="Team"
                                    :items="teams"
                                    variant="outlined"
                                    hide-details
                                    density="compact"
                                    autocomplete="off"
                                    bg-color="white"
                                    class="mt-2"
                                    @blur="updateProcesses"
                                ></VAutocomplete>
                            </VCol>
                        </VRow>
                        <VRow class="my-2">
                            <VCol cols="12">
                                <h4>Engage Customer</h4>
                                <VRow>
                                    <VCol cols="12" md="7">
                                        <VAutocomplete
                                            :id="`smsTemplate[${index}]`"
                                            v-model="productProcesses[index].smsTemplate"
                                            label="SMS Template"
                                            :items="smsTemplates"
                                            variant="outlined"
                                            hide-details
                                            density="compact"
                                            aria-autocomplete="off"
                                            autocomplete="off"
                                            bg-color="white"
                                            color="black"
                                            @blur="updateProcesses"
                                        ></VAutocomplete>
                                    </VCol>
                                    <VCol cols="12" md="5">
                                        <VAutocomplete
                                            :id="`sendSmsAt[${index}]`"
                                            v-model="productProcesses[index].sendSmsAt"
                                            label="Send At"
                                            :items="sendAt"
                                            variant="outlined"
                                            hide-details
                                            density="compact"
                                            aria-autocomplete="off"
                                            autocomplete="off"
                                            bg-color="white"
                                            color="black"
                                            @blur="updateProcesses"
                                        ></VAutocomplete>
                                    </VCol>
                                </VRow>
                                <VRow>
                                    <VCol cols="12" md="7">
                                        <VAutocomplete
                                            :id="`whatsappTemplate[${index}]`"
                                            v-model="productProcesses[index].whatsappTemplate"
                                            label="WhatsApp Template"
                                            :items="whatsappTemplates"
                                            variant="outlined"
                                            hide-details
                                            density="compact"
                                            aria-autocomplete="off"
                                            autocomplete="off"
                                            bg-color="white"
                                            color="black"
                                            @blur="updateProcesses"
                                        ></VAutocomplete>
                                    </VCol>
                                    <VCol cols="12" md="5">
                                        <VAutocomplete
                                            :id="`sendWhatsappAt[${index}]`"
                                            v-model="productProcesses[index].sendWhatsappAt"
                                            label="Send At"
                                            :items="sendAt"
                                            variant="outlined"
                                            hide-details
                                            density="compact"
                                            aria-autocomplete="off"
                                            autocomplete="off"
                                            bg-color="white"
                                            color="black"
                                            @blur="updateProcesses"
                                        ></VAutocomplete>
                                    </VCol>
                                </VRow>
                                <VRow>
                                    <VCol cols="12" md="7">
                                        <VAutocomplete
                                            :id="`emailTemplate[${index}]`"
                                            v-model="productProcesses[index].emailTemplate"
                                            label="Email Template"
                                            :items="emailTemplates"
                                            variant="outlined"
                                            hide-details
                                            density="compact"
                                            aria-autocomplete="off"
                                            autocomplete="off"
                                            bg-color="white"
                                            color="black"
                                            @blur="updateProcesses"
                                        ></VAutocomplete>
                                    </VCol>
                                    <VCol cols="12" md="5">
                                        <VAutocomplete
                                            :id="`sendEmailAt[${index}]`"
                                            v-model="productProcesses[index].sendEmailAt"
                                            label="Send At"
                                            :items="sendAt"
                                            variant="outlined"
                                            hide-details
                                            density="compact"
                                            aria-autocomplete="off"
                                            autocomplete="off"
                                            bg-color="white"
                                            color="black"
                                            @blur="updateProcesses"
                                        ></VAutocomplete>
                                    </VCol>
                                </VRow>
                            </VCol>
                        </VRow>
                    </v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>
        <VCard v-else color="grey-darken-4" class="p-3 my-2">
            You have not defined any process for this product.
        </VCard>
        <VCard title="Add Process" color="blue-lighten-5" class="p-3 mt-4">
            <VAutocomplete
                id="name"
                v-model="form.name"
                label="Select Process"
                :items="processes"
                variant="outlined"
                hide-details
                density="compact"
                aria-autocomplete="off"
                autocomplete="off"
                bg-color="white"
                color="black"
                @blur="updateProcesses"
            ></VAutocomplete>
            <div class="mt-3">
                <VBtn
                    prepend-icon="mdi-plus"
                    color="blue-darken-3"
                    :disabled="form.name == ''"
                    @click="saveProcess"
                    >Add
                </VBtn>
            </div>
        </VCard>
    </Panel>
</template>

<script setup>
import { reactive, computed } from 'vue';
import { usePage} from "@inertiajs/vue3";
import Panel from "@/Layouts/Shared/Panel.vue";
import axios from 'axios';

const nextProcesses = usePage().props.nextProcesses;
const processes = reactive(usePage().props.processes);
const teams = usePage().props.teams;
const retrievedData = usePage().props.item.process_data;
const processData = retrievedData == undefined ? [] : JSON.parse(retrievedData);
const emailTemplates = usePage().props.emailTemplates;
const smsTemplates = usePage().props.smsTemplates;
const whatsappTemplates = usePage().props.whatsappTemplates;

const productProcesses = processData.processes ? reactive(processData.processes) :  reactive([]); // Initial Value to be Populated with fetched data
if(processData.processes) {
    processData.processes.forEach(targetProcess => {
        let targetIndex = -1;
        for (let index = 0; index < processes.length; index++) {
            const element = processes[index];
            if (element == targetProcess.name) {
                targetIndex = index;
                break;
            }
        }
        processes.splice(targetIndex, 1);
    });
}

const form = reactive({
    name: "",
    next: "",
    newActivity: {
        name: "",
        description: "",
        team: "",
    }
});

const productProcessActivities = processData.tasks ? reactive(processData.tasks) : reactive({});

// Add Process: This function is used to add a new process.
const saveProcess = () => {
    const processIndex = processes.indexOf(form.name);
    productProcesses.push({
        name: form.name,
        activities: [],
        whoCoordinates: "None",
        autoStartNextProcess: false,
        nextProcess: form.next,
    });

    productProcessActivities[form.name] = [];

    form.name = "";
    processes.splice(processIndex, 1);
    updateProcesses();
}

// Add Activity: This function is used to add new activity to a Process.
const addActivity = process => {
    productProcessActivities[process].push({
        name: "",
        description: "",
        team: "",
    });
}

// Remove Activity: This function is used to remove activity from a Process
const removeActivity = (process, index) => {
    productProcessActivities[process].splice(index, 1);
    updateProcesses();
}

let source = null;
const updateProcesses = async () => {
    const payload = {
        data: {
            processes: productProcesses,
            tasks: productProcessActivities
        }
    }

    if(source) source.cancel('Request cancelled by user');
    source = axios.CancelToken.source();
    const response = await axios.put(route('item.saveprocess', [usePage().props.item.id]), payload, {
        headers: {
            "Content-Type": "application/json"
        },
        cancelToken: source.token
    });
}

const sendAt = ['Start', 'Completion'];
</script>
