<template>
    <form @submit.prevent="submit">
        <div>
            <VTextField
                id="name"
                v-model="form.name"
                label="Full Name"
                variant="outlined"
                autocomplete="name"
                :hide-details="form.errors.name == undefined"
                :error-messages="form.errors.name"
                append-inner-icon="mdi-account"
            ></VTextField>
        </div>

        <div class="mt-4">
            <VTextField
                id="mobile"
                v-model="form.mobile"
                label="Mobile Number"
                variant="outlined"
                autocomplete="tel"
                :hide-details="form.errors.mobile == undefined"
                :error-messages="form.errors.mobile"
                append-inner-icon="mdi-cellphone"
            ></VTextField>
        </div>

        <div class="mt-4">
            <VTextField
                id="email"
                v-model="form.email"
                label="Email (Optional)"
                variant="outlined"
                autocomplete="off"
                :hide-details="form.errors.email == undefined"
                :error-messages="form.errors.email"
                append-inner-icon="mdi-email"
            ></VTextField>
        </div>
        <VDivider class="border-opacity-75 my-8"></VDivider>
        <div class="mt-4">
            <VAutocomplete
                id="role"
                label="Role"
                v-model="form.role"
                :items="roles"
                variant="outlined"
                :hide-details="form.errors.role == undefined"
                :error-messages="form.errors.role"
                density="compact"
            >
            </VAutocomplete>
        </div>
        <div class="mt-4">
            <VAutocomplete
                id="branch"
                label="Branch"
                v-model="form.branch"
                :items="branches"
                variant="outlined"
                :hide-details="form.errors.branch == undefined"
                :error-messages="form.errors.branch"
                density="compact"
            >
            </VAutocomplete>
        </div>
        <VDivider class="border-opacity-75 my-8"></VDivider>
        <div v-if="props.user" :style="{color: 'orange', backgroundColor: '#3d3d3d', padding: '5px 10px', borderRadius: '5px'}">Leave the Password fields empty if you do not intend to change the Staff's password.</div>
        <div class="mt-4">
            <VTextField
                id="password"
                type="password"
                v-model="form.password"
                label="Password"
                variant="outlined"
                autocomplete="new-password"
                :hide-details="form.errors.password == undefined"
                :error-messages="form.errors.password"
                append-inner-icon="mdi-lock"
            ></VTextField>
        </div>

        <div class="mt-4">
            <VTextField
                id="password_confirmation"
                type="password"
                v-model="form.password_confirmation"
                label="Confirm Password"
                variant="outlined"
                autocomplete="new-password"
                :hide-details="form.errors.password_confirmation == undefined"
                :error-messages="form.errors.password_confirmation"
                append-inner-icon="mdi-lock"
            ></VTextField>
        </div>

        <div class="flex items-center justify-end mt-4">
            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ props.user ? 'Save' : 'Register' }}
            </PrimaryButton>
        </div>
    </form>
</template>

<script setup lang="ts">
import { useForm, usePage, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

interface UserEditor {
    id: Number,
    name: String,
    mobile: Number,
    email: String,
    password: String,
    password_confirmation: String,
    branch: {
        id: Number,
        name: String
    },
    role: {
        id: Number,
        name: String
    }
}

const props = defineProps<{
    user?: UserEditor,
    action: string
}>()

const roles = usePage().props.roles;
const branches = usePage().props.branches;

const form = useForm({
    name: props.user ? props.user.name : "",
    mobile: props.user ? props.user.mobile : "",
    email: props.user ? props.user.email : "",
    password: props.user ? props.user.password : "",
    password_confirmation: props.user ? props.user.password_confirmation : "",
    role: props.user ? (props.user.role ? props.user.role.name : "") : "",
    // role: props.userType,
    branch: props.user ? (props.user.branch ? props.user.branch.name : "") : ""
});

const submit = () => {
    if (props.user) {
        form.put(route('staff.edit', [props.user.id]), {
            // onFinish: () => form.reset('password', 'password_confirmation'),
        });
    } else {
        form.post(route('staff.add'), {
            // onFinish: () => form.reset('password', 'password_confirmation'),
        });
    }
};


</script>

<style lang="scss" scoped>

</style>
