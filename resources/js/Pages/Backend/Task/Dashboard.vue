<template>
    <Head title="Task Dashboard" />
    <BackendLayout>
        <Panel :snippet-title="`Unclaimed ${user.role} Tasks`">
            <VRow v-if="unclaimedTasks.length" class="mt-3">
              <VCol v-for="(task, index) in unclaimedTasks" :key="index" cols="12" sm="6" md="4">
                <VCard class="p-2 cursor-pointer" hover color="blue-darken-2">
                  <h5 class="mb-0">{{ task.name }}</h5>
                  <p class="my-0"><b>Created:</b> {{ moment(task.created_at).calendar() }}</p>
                  <VOverlay
                      v-model="showOverlay[task.id]"
                      activator="parent"
                      location-strategy="connected"
                      scroll-strategy="close">
                      <VCard class="px-3 py-8 w-96" :title="task.name">
                        <VCardText>
                          <p class="mb-4">{{ task.description }}</p>
                          <p><b>Created:</b> {{ moment(task.created_at).calendar() }}</p>
                        </VCardText>
                        <VCardActions>
                          <VBtn color="blue" @click="pickTask(task, index)">Accept Task</VBtn>
                        </VCardActions>
                      </VCard>
                    </VOverlay>
                </VCard>
              </VCol>
            </VRow>
            <div v-else class="text-grey-darken-2">This panel holds {{ user.role }} Team tasks that has not been picked up by any team member. Click the <b>Accept Task</b> button to pick up a task.</div>
        </Panel>
        <Panel snippet-title="My Tasks">
            <v-alert 
              v-if="acceptedCardAlertMessage.length"
              class="mb-2 p-2"
              :type="acceptedCardAlertType"
              :text="acceptedCardAlertMessage"
              closable
            ></v-alert>
            <div class="kanban-board">
                <VCard class="column" v-for="(tasks, status) in columns" :key="status" :title="status">
                    <div class="task-list" :ref="setRef(status)" :id="status">
                        <div
                        v-for="task in tasks"
                        :key="task.id"
                        :class="`task-card ${task.order.paused ? 'non-draggable bg-gray-500' : ''}`"
                        :ref="setCardRef(task.id)"
                        >
                          <h5 class="mb-0">{{ task.name }}</h5>
                          <p class="my-0 flex "><span class="flex-1"><b>Created</b> {{ moment(task.created_at).calendar() }}</span> <b>{{ task.order.paused ? 'ON HOLD' : '' }}</b></p>
                          <VOverlay
                            v-model="showAcceptedTaskOverlay[task.id]"
                            activator="parent"
                            location-strategy="connected"
                            scroll-strategy="close">
                            <VCard class="px-3 py-8 w-96" :title="task.name">
                              <VCardText>
                                <p class="mb-2">{{ task.description }}</p>
                                <p v-if="taskAuditError[`card-${task.id}`]" class="bg-red-100 text-red p-2">
                                  {{ taskAuditError[`card-${task.id}`].message }}
                                  <ul class="mb-0">
                                    <li class="list-disc my-2" v-for="(incompleteTask, idx) in taskAuditError[`card-${task.id}`].incompleteTasks" :key="idx">{{ incompleteTask }}</li>
                                  </ul>
                                </p>
                                <p><b>Created:</b> {{ moment(task.created_at).calendar() }}</p>
                                <p><b>Updated:</b> {{ moment(task.updated_at).calendar() }}</p>
                                <div>
                                  <div class="pb-2" v-if="!showTaskTransferField[task.id]">
                                    <v-btn
                                      color="blue-darken-3"
                                      prepend-icon="mdi-transfer"
                                      @click="showTaskTransferField[task.id] = !showTaskTransferField[task.id]"
                                    >Transfer Task</v-btn>
                                  </div>
                                  <div class="mt-2" v-if="showTaskTransferField[task.id]">
                                    <Suggest label="Transfer Task To" noDataText="Find team member" variant="outlined" :params="{ allow_cross_branch: true }" :endpoint="route('staff.filter')" @selected="useSelectedStaff" />
                                    <v-progress-linear v-if="transferringTask" class="mt-1" color="red" indeterminate></v-progress-linear>
                                    <p v-if="taskTransferError.length" class="text-red pt-2 mb-0">{{ taskTransferError }}</p>
                                    <v-btn
                                      color="blue-darken-3"
                                      class="mt-1"
                                      :disabled="transferTaskTo.selection == undefined || transferringTask"
                                      @click="initiateTransfer(task)"
                                    >Transfer</v-btn>
                                  </div>
                                </div>
                              </VCardText>
                              <VCardActions>
                                <VBtn
                                  color="blue"
                                  @click="showClickedTask(task)"
                                >Open Order</VBtn>
                                <VBtn
                                  color="red"
                                  @click="showAcceptedTaskOverlay[task.id] = false"
                                >Close</VBtn>
                              </VCardActions>
                            </VCard>
                          </VOverlay>
                        </div>
                    </div>
                </VCard>
            </div>
        </Panel>

        <!-- Cross-branch transfer confirmation dialog -->
        <v-dialog v-model="showCrossBranchDialog" max-width="500px">
          <v-card>
            <v-card-title class="headline">Transfer Task to Different Branch?</v-card-title>
            <v-card-text>
              You are about to transfer this task to <strong>{{ transferTaskTo.selection?.name }}</strong>. 
              The task will be moved outside your branch.
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="grey" text @click="showCrossBranchDialog = false">Cancel</v-btn>
              <v-btn color="blue" text @click="confirmTransfer">Proceed</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
    </BackendLayout>
</template>

<script setup>
import Sortable from 'sortablejs';
import BackendLayout from '@/Layouts/BackendLayout.vue';
import Panel from '@/Layouts/Shared/Panel.vue';
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, nextTick } from 'vue';
import axios from 'axios';
import moment from 'moment';
import Suggest from '@/Components/Suggest.vue';


const user = usePage().props.auth.user;
const endpoints = usePage().props.endpoints;
const unclaimedTasks = ref([]);
const showOverlay = ref([]);
const showAcceptedTaskOverlay = ref([]);
const taskAuditError = ref({});

const showTaskTransferField = ref([]);

const columns = ref({
  Todo: [],
  Doing: [],
  Done: [],
});
let statusKeys = Object.keys(columns.value);


// Ref container to hold references
const refs = ref({});
const cardRefs = ref({});

// Function to set refs
const setRef = (key) => (el) => {
  if (el) {
    refs.value[key] = el;
  }
};

const setCardRef = (key) => (el) => {
  if (el) {
    cardRefs.value[key] = el;
  }
};

let originalState = null;

const onStart = (event) => {
  const { from, oldIndex } = event;
  const fromStatus = from.id;
  const draggedItem = columns.value[fromStatus][oldIndex];

  // Store the original state
  originalState = {
    fromStatus,
    oldIndex,
    item: draggedItem
  };
};

const onEnd = (event) => {
  const { from, to, newIndex, oldIndex } = event;
  

  const fromStatus = from.id;
  const toStatus = to.id;

  const draggedItem = columns.value[fromStatus][oldIndex];
  // console.log(`From position ${oldIndex} to ${newIndex}`);

  if (columns.value[fromStatus] && columns.value[toStatus]) {
    const movedItem = columns.value[fromStatus].splice(oldIndex, 1)[0];
    columns.value[toStatus].splice(newIndex, 0, movedItem);
  } else {
    console.error('Invalid column status:', fromStatus, toStatus);
  }

  if (from.id === to.id) {
    return false;
  }
    updateTaskStatus(draggedItem, fromStatus, toStatus, event);
};

const reverseMove = (event, responseData) => {
  if (originalState) {
    const { fromStatus, oldIndex, item } = originalState;
    const toStatus = event.to.id;  // Current column after the move
    const newIndex = columns.value[toStatus].indexOf(item);

    if (columns.value[toStatus] && columns.value[fromStatus]) {
      // Remove from current position
      columns.value[toStatus].splice(newIndex, 1);
      // Move back to original position
      columns.value[fromStatus].splice(oldIndex, 0, item);

      // console.log(item.id)
      const cardRefName = `card-${item.id}`;
      const cardElement = cardRefs.value[item.id];
      if (cardElement) {
        console.log('Reversed Card: ', cardElement);
        cardElement.classList.add('bg-red-200');
        showAcceptedTaskOverlay.value[item.id] = true;
        taskAuditError.value[`card-${item.id}`] = responseData;
        
      } else {
        console.error('Card Not Found: ', cardRefName);
      }
    } else {
      console.error('Invalid column status:', fromStatus, toStatus);
    }

    // Clear original state
    originalState = null;
  } else {
    console.error('No move to reverse');
  }
};

const onMove = (event) => {
  const { from, to } = event;

  // Prevent moving tasks out of the "Done" column
  if (from.id === 'Done') {
    return false; // This will prevent the item from being dragged
  }
  
  // Optionally, prevent moving tasks into "Done" if needed
  // if (to.id === 'Done') {
  //   return false;
  // }

  return true; // Allow the move
};


// Open the task to be worked on
const showClickedTask = task => {
    router.visit(route('order.view', [task.order_id]))
}

// Update the status of the task on the server
const updateTaskStatus = async (task, fromStatus, toStatus, event) => {
    // console.log('Dragged Item:', task);
    // console.log(`Moved from ${fromStatus} to ${toStatus}`);
    const payload = { task, fromStatus, toStatus }
    const response = await axios.post(endpoints.updateTask, payload, {
      headers: {
        "Content-Type": "application/json"
      }
    })

    const data = response.data;
    if (data.status != undefined) {
      if (data.status == 'success') {
        // console.log("status updated");
        loadNewTasks();
        loadPickedTasks();
      }

      if (data.status.toLowerCase() == 'failed') {
        reverseMove(event, data);
      }
    }
}

// Used to load tasks after the components mount
const loadPickedTasks = async () => {
  const response = await axios.get(endpoints.accepted);
  columns.value = response.data;
}

const loadNewTasks = async () => {
  const response = await axios.get(endpoints.newTasks);
  unclaimedTasks.value = response.data.unclaimedTasks ?? [];
  unclaimedTasks.value.forEach(element => {
    showOverlay[element.id] = false;
  })
}

const pickTask = async (task, index) => {
  showOverlay[task.id] = false;
  
  const payload = {task};

  const response = await axios.post(endpoints.pickTask, payload, {
    headers: {
      "Content-Type": "application/json"
    }
  });

  const data = response.data;
  if (data.status != undefined) {
    if (data.status == 'success') {
      unclaimedTasks.value.splice(index, 1);
      loadNewTasks();
      loadPickedTasks();
    }
  }
}


const transferTaskTo = ref({});
const useSelectedStaff = (data) => {
  transferTaskTo.value = data;
}

const transferringTask = ref(false);
const acceptedCardAlertType = ref('');
const acceptedCardAlertMessage = ref('');
const taskTransferError = ref('');
const showCrossBranchDialog = ref(false);
const currentTaskForTransfer = ref(null);

const initiateTransfer = (task) => {
  currentTaskForTransfer.value = task;
  
  // Check if selected staff is from a different branch
  const selectedStaff = transferTaskTo.value.selection;
  if (selectedStaff && selectedStaff.branch_id !== task.branch_id) {
    // Show confirmation dialog
    showCrossBranchDialog.value = true;
  } else {
    // Same branch, proceed directly
    transferTask(task.id);
  }
};

const confirmTransfer = () => {
  showCrossBranchDialog.value = false;
  transferTask(currentTaskForTransfer.value.id);
};

const transferTask = async (taskId) => {
  taskTransferError.value = "";
  acceptedCardAlertMessage.value = "";
  transferringTask.value = true;
  const payload = {
    taskId: taskId,
    receiverId: transferTaskTo.value.selection.id,
  }

  try {
    const response = await axios.post(route('task.transfer'), payload, {
      headers: {
        "Content-Type": "application/json"
      }
    });

    if(response.status == 200){
      if (response.data.status == "success") {
        loadNewTasks();
        loadPickedTasks();
        showAcceptedTaskOverlay.value[taskId] = false;
        acceptedCardAlertType.value = "success";
        acceptedCardAlertMessage.value = response.data.message;
      } else if (response.data.status == "error") {
        // Handle validation errors from backend
        acceptedCardAlertType.value = "error";
        acceptedCardAlertMessage.value = response.data.message || "Transfer failed.";
      }
    }
  } catch (error) {
    acceptedCardAlertType.value = "error";
    if (error.response && error.response.status === 422) {
      taskTransferError.value = error.response.data.message;
    } else {
      taskTransferError.value = "Something went wrong! Pls try again later.";
    }
  }
  transferringTask.value = false;
}


// let checkNewTaskInterval = 0;
onMounted(async () => {

  await nextTick(); // Ensure DOM is fully rendered

  statusKeys.forEach((status) => {
    if (refs.value[status]) {
      Sortable.create(refs.value[status], {
        group: 'tasks',
        animation: 150,
        filter: ".non-draggable",
        preventOnFilter: false,
        onStart,
        onEnd,
        onMove
      });
    } else {
      console.error(`Ref ${status} is not available.`);
    }
  });

  // Request Notification Permission
  if (Notification.permission !== "granted") {
    Notification.requestPermission();
  }

  // Listen for new tasks
  Echo.private(`App.Models.User.${user.id}`)
    .notification((notification) => {
      if (notification.type === 'App\\Notifications\\NewTaskNotification' || notification.type === 'App\\Notifications\\TaskTransferNotification') {
        // Play sound (optional, simple beep)
        // const audio = new Audio('/path/to/sound.mp3'); audio.play();
        
        // Show Browser Notification
        if (Notification.permission === "granted") {
           new Notification("New Task Assigned", {
              body: notification.message,
              icon: '/images/logo.png' // Adjust path as needed
           });
        }

        // Reload tasks to update UI
        loadNewTasks();
        loadPickedTasks();
        
        // Optional: Show a toast/alert in the UI
        // toast.success(notification.message);
      }
    });

  // Listen for task claims on the role/branch-specific channel
  const taskClaimChannel = `task-claims.${user.role_id}.${user.branch_id}`;
  console.log('Subscribing to task claim channel:', taskClaimChannel);
  console.log('User role_id:', user.role_id, 'User branch_id:', user.branch_id);
  
  Echo.private(taskClaimChannel)
    .listen('.task-claimed', (event) => {
      console.log('✅ Task claimed event received:', event);
      console.log('Event details - task_id:', event.task_id, 'role_id:', event.role_id, 'branch_id:', event.branch_id);
      
      // Find and remove the claimed task from unclaimed tasks
      const taskIndex = unclaimedTasks.value.findIndex(task => task.id === event.task_id);
      console.log('Task index in unclaimed list:', taskIndex);
      
      if (taskIndex !== -1) {
        console.log('Removing task from unclaimed tasks:', unclaimedTasks.value[taskIndex].name);
        unclaimedTasks.value.splice(taskIndex, 1);
        
        // Show browser notification
        if (Notification.permission === "granted") {
          new Notification(`Task Claimed`, {
            body: `"${event.task_name}" was claimed by ${event.claimed_by.name}`,
            icon: '/images/logo.png'
          });
        }
      } else {
        console.warn('Task not found in unclaimed tasks list. Task ID:', event.task_id);
      }
    });

  loadNewTasks();
  loadPickedTasks();
});

onUnmounted(() => {
  // clearInterval(checkNewTaskInterval);
  const taskClaimChannel = `task-claims.${user.role_id}.${user.branch_id}`;
  console.log('Unsubscribing from:', taskClaimChannel);
  Echo.leave(`App.Models.User.${user.id}`);
  Echo.leave(taskClaimChannel);
})



</script>

<style scoped>
  .non-draggable {
    pointer-events: none;
    background: #374151;
    color: white;
  }
</style>
