<template>
    <div>
        <div class="mb-8 flex justify-start max-w-3xl">
            <h1 class="font-bold text-3xl">
                <inertia-link class="text-primary-400 hover:text-primary-600" :href="route('users')">Users</inertia-link>
                <span class="text-primary-400 font-medium">/</span>
                {{ form.first_name }} {{ form.last_name }}
            </h1>
            <img v-if="user.photo" class="block w-8 h-8 rounded-full ml-4" :src="user.photo" />
        </div>

        <trashed-message v-if="user.deleted_at" class="mb-6" @restore="restore">This user has been deleted.</trashed-message>

        <div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
            <form @submit.prevent="update">
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                    <text-input v-model="form.first_name" :error="form.errors.first_name" class="pr-6 pb-8 w-full lg:w-1/2" label="First Name" />
                    <text-input v-model="form.last_name" :error="form.errors.last_name" class="pr-6 pb-8 w-full lg:w-1/2" label="Last Name" />
                    <text-input v-model="form.email" :error="form.errors.email" class="pr-6 pb-8 w-full lg:w-1/2" label="Email Address" />
                    <phone-input v-model="form.phone" :error="form.errors.phone" class="pr-6 pb-8 w-full lg:w-1/2" autocapitalize="off" label="Phone Number" :initialCountryCode="form.phone_country" @countryCode="phoneCountryCode" />
                    <file-input v-model="form.photo" :error="form.errors.photo" class="pr-6 pb-8 w-full lg:w-1/2" type="file" accept="image/*" label="Photo" />
                    <select-input v-model="form.role" :error="form.errors.role" class="pr-6 pb-8 w-full lg:w-1/2" label="Role">
                        <option v-for="(value, key) in $page.props.settings.roles" :key="key" :value="key" v-text="value"></option>
                    </select-input>
                    <text-input v-model="form.password" :error="form.errors.password" class="pr-6 pb-8 w-full lg:w-1/2" type="text" autocomplete="new-password" label="Password" />
                    <div class="pr-6 pb-8 w-full lg:w-1/2">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn bg-indigo-500 text-center w-full py-3" @click="generatePassword">Generate Password</button>
                    </div>
                </div>
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
                    <button class="text-red-600 hover:underline p-0" tabindex="-1" type="button" @click="destroy">{{ !user.deleted_at ? 'Delete User' : 'Permanently Delete User' }}</button>
                    <loading-button :loading="form.processing" class="btn btn-primary ml-auto" type="submit">Update User</loading-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import PhoneInput from '@/Shared/PhoneInput'
import FileInput from '@/Shared/FileInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
    metaInfo() {
        return {
            title: `${this.form.first_name} ${this.form.last_name}`,
        }
    },
    components: {
        FileInput,
        LoadingButton,
        SelectInput,
        TextInput,
        PhoneInput,
        TrashedMessage,
    },
    layout: Layout,
    props: {
        user: Object,
    },
    remember: 'form',
    data() {
        return {
            form: this.$inertia.form({
                _method: 'put',
                first_name: this.user.first_name,
                last_name: this.user.last_name,
                email: this.user.email,
                role: this.user.role,
                phone: this.user.phone,
                phone_country: this.user.phone_country,
                password: null,
                photo: null,
            }),
        }
    },
    methods: {
        update() {
            this.form.post(this.route('users.update', this.user.id), {
                onSuccess: () => this.form.reset('password', 'photo'),
            })
        },
        destroy() {
            if (confirm('Are you sure you want to delete this user?')) {
                this.$inertia.delete(this.route('users.destroy', this.user.id))
            }
        },
        restore() {
            if (confirm('Are you sure you want to restore this user?')) {
                this.$inertia.put(this.route('users.restore', this.user.id))
            }
        },
        phoneCountryCode(countryCode) {
            this.form.phone_country = countryCode
        }
    },
}
</script>
