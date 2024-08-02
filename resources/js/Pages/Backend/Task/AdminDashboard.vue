<template>
    <Head title="Task Dashboard" />
    <BackendLayout>
      <Link class="font-bold" :href="route('order.view', order.id)">Back to Order</Link>
        <Panel :snippet-title="`Unclaimed ${order.name} Tasks`">
            <div>This panel holds {{ order.name }} tasks that has not been picked up by any team member. Click the <b>Accept Task</b> button to pick up a task.</div>
            <VRow v-if="unclaimedTasks.length" class="mt-3">
              <VCol v-for="(task, index) in unclaimedTasks" :key="index" cols="12" sm="6" md="4">
                <VCard :title="task.name" class="p-3" prepend-icon="mdi-checkbox-blank-outline" color="blue-lighten-5">
                  <p>{{ task.description }}</p>
                  <p><b>Task Created:</b> {{ moment(task.created_at).calendar() }}</p>
                  <VBtn color="blue" @click="pickTask(task, index)">Accept Task</VBtn>
                </VCard>
              </VCol>
            </VRow>
        </Panel>
        <Panel snippet-title="Accepted Tasks">
            <div class="kanban-board">
                <VCard class="column" v-for="(tasks, status) in columns" :key="status" :title="status">
                    <div class="task-list" :ref="setRef(status)" :id="status">
                        <div
                        v-for="task in tasks"
                        :key="task.id"
                        class="task-card"
                        @click="showClickedTask(task)"
                        >
                        <h4>{{ task.name }}</h4>
                        <p>{{ task.description }}</p>
                        <p><b>Task Created:</b> {{ moment(task.created_at).calendar() }}</p>
                        <p><b>Team Member:</b> {{ task.user.name }}</p>
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
const unclaimedTasks = ref(usePage().props.unclaimedTasks);


const columns = ref({
  Todo: [],
  Doing: [],
  Done: [],
});
let statusKeys = Object.keys(columns.value);


// Ref container to hold references
const refs = ref({});

// Function to set refs
const setRef = (key) => (el) => {
  if (el) {
    refs.value[key] = el;
  }
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
    updateTaskStatus(draggedItem, fromStatus, toStatus);;
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
const updateTaskStatus = async (task, fromStatus, toStatus) => {
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
        console.log("status updated");
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
}

const pickTask = async (task, index) => {
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

let checkNewTaskInterval = 0;
onMounted(async () => {
  await nextTick(); // Ensure DOM is fully rendered

  statusKeys.forEach((status) => {
    if (refs.value[status]) {
      Sortable.create(refs.value[status], {
        group: 'tasks',
        animation: 150,
        onEnd,
        onMove
      });
    } else {
      console.error(`Ref ${status} is not available.`);
    }
  });

  loadPickedTasks();

  checkNewTaskInterval = setInterval(function () {
    loadNewTasks();
    loadPickedTasks();
  }, 30000)
});

onUnmounted(() => {
  clearInterval(checkNewTaskInterval);
})

</script>

<style scoped>
.kanban-board {
  display: flex;
  justify-content: space-between;
}

.column {
  width: 32%;
  background-color: #f0f0f0;
  padding: 10px;
  border-radius: 8px;
}

.task-list {
  min-height: 200px;
  font-size: 92%;
  user-select: none;
}

.task-card {
  background-color: #fff;
  padding: 15px;
  margin: 10px 0;
  border-radius: 4px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  cursor: grab;
}
</style>
