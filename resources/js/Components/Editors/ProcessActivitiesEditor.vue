<template>
    <Panel snippet-title="Production Processes">
        <v-expansion-panels v-if="productProcesses != undefined && productProcesses.length" ref="sortableContainer">
            <v-expansion-panel v-for="(process, index) in productProcesses" :key="process.name" :data-id="index">
                <v-expansion-panel-title color="blue">{{ process.name }}</v-expansion-panel-title>
                <v-expansion-panel-text class="non-draggable">
                    <h4 class="my-2">Tasks</h4>
                    <template v-if="productProcessActivities[process.name] == undefined || productProcessActivities[process.name].length == 0">
                        <VCard color="grey-darken-4" class="p-3 my-2">
                            You have not defined any task for this process.
                        </VCard>
                    </template>
                    <template v-else>
                        <VCard class="p-2 my-3" color="grey-lighten-5" v-for="(productProcessActivity, index) in productProcessActivities[process.name]" :key="index">
                            <v-card-title class="mb-1" style="font-size: 17px;">Task {{index + 1}}</v-card-title>
                            <VRow>
                                <VCol>
                                    <VTextField
                                        :id="`task-${process.name}-${index}`"
                                        v-model="productProcessActivities[process.name][index].name"
                                        label="Task Name"
                                        variant="outlined"
                                        :rules="[taskNameRule]"
                                        hide-details
                                        autocomplete="off"
                                        bg-color="white"
                                        @blur="updateProcesses"
                                        data-testid="task-name-input"
                                    ></VTextField>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VTextarea
                                        :id="`description-${process.name}-${index}`"
                                        v-model="productProcessActivities[process.name][index].description"
                                        label="Brief Guidelines (Optional)"
                                        variant="outlined"
                                        rows="1"
                                        auto-grow
                                        max-rows="4"
                                        hide-details
                                        density="compact"
                                        autocomplete="off"
                                        bg-color="white"
                                        :rules="[taskDetailRule]"
                                        @blur="updateProcesses"
                                        clearable
                                        data-testid="task-description-input"
                                    ></VTextarea>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VSelect
                                        :id="`team${index}`"
                                        v-model="productProcessActivities[process.name][index].team"
                                        label="Team"
                                        :items="teams"
                                        variant="outlined"
                                        density="compact"
                                        hide-details
                                        autocomplete="off"
                                        bg-color="white"
                                        @update:model-value="updateProcesses"
                                        :rules="[value => !!value || 'This field is required']"
                                    ></VSelect>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol cols="12">
                                    <VCheckbox
                                        v-model="productProcessActivities[process.name][index].audit"
                                        label="Verify Task Completion"
                                        hide-details
                                        @change="updateProcesses"
                                    ></VCheckbox>
                                </VCol>
                            </VRow>
                            <VRow v-if="productProcessActivities[process.name][index].audit">
                                <VCol cols="12">
                                    <VCombobox
                                        v-model="productProcessActivities[process.name][index].checks"
                                        label="Select checks"
                                        :items="verifiables"
                                        multiple
                                        chips
                                        small-chips
                                        variant="outlined"
                                        hide-details
                                        max-errors="5"
                                        density="compact"
                                        @update:model-value="updateProcesses"
                                    ></VCombobox>
                                </VCol>
                            </VRow>
                            <div class="mt-2 text-right">
                                <VBtn
                                    prepend-icon="mdi-minus"
                                    color="grey-darken-3"
                                    @click="removeActivity(process.name, index)"
                                    data-testid="remove-task-btn"
                                    >Remove
                                </VBtn>
                            </div>
                        </VCard>
                    </template>
                    
                    
                    <div class="mt-2">
                        <VBtn
                            prepend-icon="mdi-plus"
                            color="blue-darken-3"
                            @click="addActivity(process.name)"
                            data-testid="add-task-btn"
                            >Add Task
                        </VBtn>
                    </div>
                    
                    <VRow class="my-1">
                        <VCol>
                            <v-checkbox
                                v-model="productProcesses[index].canCancelOrder"
                                label="Allow Order Cancellation during this process"
                                @blur="updateProcesses"
                                hide-details
                                data-testid="can-cancel-checkbox"
                            ></v-checkbox>
                        </VCol>
                    </VRow>
                    <hr/>
                    <VRow>
                        <VCol cols="12">
                            <VRow>
                                <VCol cols="12">
                                    <h4>On Tasks Completion</h4>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <VAutocomplete
                                        :id="`order-status${index}`"
                                        v-model="productProcesses[index].orderStatus"
                                        label="Update Order Status To"
                                        :items="orderStatuses"
                                        variant="outlined"
                                        hide-details
                                        density="compact"
                                        autocomplete="off"
                                        bg-color="white"
                                        @blur="updateProcesses"
                                        data-testid="order-status-input"
                                    ></VAutocomplete>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol>
                                    <v-checkbox
                                        v-model="productProcesses[index].autoStartNextProcess"
                                        label="Trigger Next Process"
                                        hide-details
                                        @change="updateProcesses"
                                        data-testid="auto-start-checkbox"
                                    ></v-checkbox>
                                </VCol>
                            </VRow>
                        </VCol>
                    </VRow>
                    <hr />
                    <VRow>
                        <VCol cols="12">
                            <h4>Coordinating Team</h4>
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol>
                            <VAutocomplete
                                :id="`coordinator${index}`"
                                v-model="productProcesses[index].whoCoordinates"
                                label="Select Coordinator"
                                :items="teams"
                                variant="outlined"
                                hide-details
                                density="compact"
                                autocomplete="off"
                                bg-color="white"
                                @blur="updateProcesses"
                                data-testid="coordinator-input"
                            ></VAutocomplete>
                        </VCol>
                    </VRow>
                    <VRow class="mt-2">
                        <VCol>
                            <VAutocomplete
                                :id="`teamWhatsappTemplate${index}`"
                                v-model="productProcesses[index].teamWhatsappTemplate"
                                label="Team WhatsApp Template"
                                :items="whatsappTemplates"
                                variant="outlined"
                                hide-details
                                density="compact"
                                autocomplete="off"
                                bg-color="white"
                                @blur="updateProcesses"
                                data-testid="team-whatsapp-input"
                            ></VAutocomplete>
                        </VCol>
                    </VRow>
                    <hr/>
                    <VRow>
                        <VCol cols="12">
                            <h4>Engage Customers</h4>
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol cols="12">
                            <VRow>
                                <VCol cols="12" sm="7">
                                    <VAutocomplete
                                        :id="`smsTemplate-${index}`"
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
                                        data-testid="sms-template-input"
                                    ></VAutocomplete>
                                </VCol>
                                <VCol cols="12" sm="5">
                                    <VAutocomplete
                                        :id="`sendSmsAt-${index}`"
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
                                        data-testid="send-sms-at-input"
                                    ></VAutocomplete>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol cols="12" sm="7">
                                    <VAutocomplete
                                        :id="`whatsappTemplate-${index}`"
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
                                        data-testid="whatsapp-template-input"
                                    ></VAutocomplete>
                                </VCol>
                                <VCol cols="12" sm="5">
                                    <VAutocomplete
                                        :id="`sendWhatsappAt-${index}`"
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
                                        data-testid="send-whatsapp-at-input"
                                    ></VAutocomplete>
                                </VCol>
                            </VRow>
                            <VRow>
                                <VCol cols="12" sm="7">
                                    <VAutocomplete
                                        :id="`emailTemplate-${index}`"
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
                                        data-testid="email-template-input"
                                    ></VAutocomplete>
                                </VCol>
                                <VCol cols="12" sm="5">
                                    <VAutocomplete
                                        :id="`sendEmailAt-${index}`"
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
                                        data-testid="send-email-at-input"
                                    ></VAutocomplete>
                                </VCol>
                            </VRow>
                            <hr />
                            <VRow>
                                <VCol cols="12">
                                    <div class="text-right">
                                        <VBtn
                                            prepend-icon="mdi-minus"
                                            color="red"
                                            @click="RemoveProcess(index)"
                                            data-testid="remove-process-btn"
                                        >Remove Process</VBtn>
                                    </div>
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
                data-testid="process-select"
            ></VAutocomplete>
            <div class="mt-3">
                <VBtn
                    prepend-icon="mdi-plus"
                    color="blue-darken-3"
                    :disabled="form.name == ''"
                    @click="saveProcess"
                    data-testid="add-process-btn"
                    >Add
                </VBtn>
            </div>
        </VCard>

        <!-- Auto-save notification positioned at the bottom to avoid layout shift -->
        <div class="d-flex align-center justify-end mt-4" style="min-height: 20px;">
            <VFadeTransition>
                <div v-if="isSaving || saveStatus" class="d-flex align-center">
                    <v-progress-circular
                        v-if="isSaving"
                        indeterminate
                        size="14"
                        width="2"
                        color="grey"
                        class="mr-2"
                    ></v-progress-circular>
                    <span v-if="isSaving" class="text-caption text-grey">Saving changes...</span>
                    <span v-else-if="saveStatus === 'Saved'" class="text-caption text-success">
                        <v-icon size="small" class="mr-1">mdi-check-circle</v-icon>
                        All changes saved
                    </span>
                    <span v-else-if="saveStatus === 'Error'" class="text-caption text-error">
                        <v-icon size="small" class="mr-1">mdi-alert-circle</v-icon>
                        Failed to save
                    </span>
                </div>
            </VFadeTransition>
        </div>
    </Panel>
</template>

<script setup>
import { reactive, onMounted, ref, nextTick } from 'vue';
import { usePage} from "@inertiajs/vue3";
import Panel from "@/Layouts/Shared/Panel.vue";
import axios from 'axios';
import Sortable from 'sortablejs';

const nextProcesses = usePage().props.nextProcesses;
const processes = reactive(usePage().props.processes);
const orderStatuses = usePage().props.orderStatuses;
const teams = reactive(usePage().props.teams);
const retrievedData = usePage().props.item.process_data;
const processData = (retrievedData == undefined || retrievedData == null) 
    ? { processes: [], tasks: {} } 
    : (typeof retrievedData === 'string' ? JSON.parse(retrievedData) : retrievedData);

const emailTemplates = usePage().props.emailTemplates;
const smsTemplates = usePage().props.smsTemplates;
const whatsappTemplates = usePage().props.whatsappTemplates;
const verifiables = usePage().props.verifiables;

const isSaving = ref(false);
const saveStatus = ref('');

const productProcesses = ref(processData.processes || []); // Initial Value to be Populated with fetched data
let productProcessActivities = reactive(processData.tasks || {});

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

const taskNameRule = value => value.length <= 20;
const taskDetailRule = value => !value || value.length <= 75;

const form = reactive({
    name: "",
    next: "",
    newActivity: {
        name: "",
        description: "",
        team: "",
    }
});


const RemoveProcess = (index) => {
    const processName = productProcesses.value[index].name;
    processes.push(processName); // Add the process back to the list of processes

    delete productProcessActivities[processName]; // Delete tasks
    productProcesses.value.splice(index, 1); // Remove the process from the product processes

    updateProcesses();
}

// Add Process: This function is used to add a new process.
const saveProcess = () => {
    const processIndex = processes.indexOf(form.name);
    productProcesses.value.push({
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
const addActivity = processName => {
    if (productProcessActivities[processName] === undefined) {
        productProcessActivities[processName] = [];
    }

    productProcessActivities[processName].push({
        name: "",
        description: "",
        team: "",
    });
    updateProcesses();
}

// Remove Activity: This function is used to remove activity from a Process
const removeActivity = (process, index) => {
    productProcessActivities[process].splice(index, 1);
    updateProcesses();
}

let source = null;
const updateProcesses = async () => {
    isSaving.value = true;
    saveStatus.value = 'Saving';

    // Ensure tasks is a plain object to avoid JSON.stringify issues (e.g. if it became an array)
    const tasksPayload = {};
    if (productProcessActivities) {
        Object.keys(productProcessActivities).forEach(key => {
            tasksPayload[key] = productProcessActivities[key];
        });
    }

    const payload = {
        data: {
            processes: productProcesses.value,
            tasks: tasksPayload
        }
    }

    if(source) source.cancel('Request cancelled by user');
    source = axios.CancelToken.source();
    try {
        const response = await axios.put(route('item.saveprocess', [usePage().props.item.id]), payload, {
            headers: {
                "Content-Type": "application/json"
            },
            cancelToken: source.token
        });
        
        if (response.data.status === 'Saved') {
            saveStatus.value = 'Saved';
            setTimeout(() => {
                if (saveStatus.value === 'Saved') saveStatus.value = '';
            }, 3000);
        } else {
            saveStatus.value = 'Error';
        }
    } catch (error) {
        if (!axios.isCancel(error)) {
            console.error('Failed to save processes:', error);
            saveStatus.value = 'Error';
        }
    } finally {
        isSaving.value = false;
    }
}

const sendAt = ['Start', 'Completion'];

const reorderProcesses = (oldIndex, newIndex) => {
  // Validate indices
  if (oldIndex < 0 || oldIndex >= productProcesses.value.length || newIndex < 0 || newIndex >= productProcesses.value.length) {
    throw new Error('Invalid indices');
  }

  // Remove the item from its old position
  const [item] = productProcesses.value.splice(oldIndex, 1);

  // Insert the item at its new position
  productProcesses.value.splice(newIndex, 0, item);
  
  updateProcesses();
}

const sortableContainer = ref(null);
onMounted(() => {
    nextTick(() => {
        if (sortableContainer.value && sortableContainer.value.$el) {
            Sortable.create(sortableContainer.value.$el, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                filter: '.non-draggable',
                preventOnFilter: false,
                onEnd: (event) => {
                    reorderProcesses(event.oldIndex, event.newIndex);
                },
            });
        }
    })
});

</script>
