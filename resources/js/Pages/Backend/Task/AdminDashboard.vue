<template>
    <Head title="Task Dashboard" />
    <BackendLayout>
      <Link class="font-bold" :href="route('order.view', order.id)">Back to Order</Link>
        <Panel :snippet-title="`Unclaimed ${order.name} Tasks`">
            <VRow v-if="unclaimedTasks.length" class="mt-0">
              <VCol v-for="(task, index) in unclaimedTasks" :key="index" cols="12" sm="6" md="4">
                <VCard class="p-2 cursor-pointer" hover color="blue-darken-2">
                  <h5 class="mb-0">{{ task.name }}</h5>
                  <p class="mb-0"><b>Created</b> {{ moment(task.created_at).calendar() }}</p>
                  
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
            <div v-else><VIcon icon="mdi-information-outline"></VIcon> This panel holds {{ order.name }} tasks that has not been picked up by any team member.</div>
        </Panel>
        <Panel snippet-title="Accepted Tasks">
            <div class="kanban-board">
                <VCard class="column" v-for="(tasks, status) in columns" :key="status" :title="status">
                    <div class="task-list" :ref="setRef(status)" :id="status">
                        <div
                          v-for="task in tasks"
                          :key="task.id"
                          :class="`task-card ${task.order.paused ? 'non-draggable' : ''}`"
                          :ref="setCardRef(`card-${task.id}`)"
                        >
                          <h5>{{ task.name }}</h5>
                          <p class="mb-0 flex "><span class="flex-1"><b>Created</b> {{ moment(task.created_at).calendar() }}</span> <b>{{ task.order.paused ? 'ON HOLD' : '' }}</b></p>
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
                                    <li class="list-disc my-2" v-for="(incompleteTask, idx) in taskAuditError[`card-${task.id}`].incompleteTasks" :key="idx">{{ incompleteTask }} <Link class="btn btn-secondary mt-2" v-if="incompleteTask == 'Customer Feedback'" :href="route('customer.view', taskAuditError[`card-${task.id}`].customer.id)">Write Feedback</Link></li>
                                  </ul>
                                </p>
                                <p><b>Created:</b> {{ moment(task.created_at).calendar() }}</p>
                                <p><b>Updated:</b> {{ moment(task.updated_at).calendar() }}</p>
                                <p class="mt-3"><b>Team Member:</b> {{ task.user.name }}</p>
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

const user = usePage().props.auth.user;
const order = usePage().props.order;
const endpoints = usePage().props.endpoints;
const unclaimedTasks = ref([]);
const showOverlay = ref([]);
const showAcceptedTaskOverlay = ref([]);
const taskAuditError = ref({});


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
      const cardElement = cardRefs.value[cardRefName];
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

  loadNewTasks();
  loadPickedTasks();

  // checkNewTaskInterval = setInterval(function () {
  //   loadNewTasks();
  //   loadPickedTasks();
  // }, 30000)
});

onUnmounted(() => {
  // clearInterval(checkNewTaskInterval);
})

</script>

<style scoped>
  .non-draggable {
    pointer-events: none;
    background: #374151;
    color: white;
  }

  
</style>
