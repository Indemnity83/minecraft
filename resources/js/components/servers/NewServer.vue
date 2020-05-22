<template>
    <!-- eslint-disable -->
    <div class="bg-white overflow-hidden overflow-hidden shadow rounded-lg">

        <div class="px-4 sm:px-6">
            <h3 class="py-5 text-lg leading-6 font-medium text-gray-900">
                New Server
            </h3>
        </div>

        <div class="border-t border-gray-200 px-4 py-4 sm:px-6 bg-gray-100">

            <div class="mt-5">
                <form class="w-full" @submit.prevent="onSubmit">
                    <fieldset :disabled="busy" :class="{'opacity-50': busy}">
                        <div class="sm:flex mt-4">
                            <label for="name" class="mt-3 w-40 md:w-48 pr-8 block text-sm sm:text-right font-medium leading-5 text-gray-700">
                                Server Name
                            </label>
                            <div class="flex-1">
                                <div class="flex-1 mt-1 max-w-lg relative rounded-md shadow-sm">
                                    <input id="name" v-model="form.name" class="form-input block w-full sm:text-sm sm:leading-5" :class="{'border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red': form.errors.has('name')}"/>
                                </div>
                                <p v-show="form.errors.has('name')" class="mt-1 text-sm text-red-600" id="name-error">
                                    {{ form.errors.get('name') }}
                                </p>
                            </div>
                        </div>

                        <div class="sm:flex mt-4">
                            <label for="name" class="mt-3 w-40 md:w-48 pr-8 block text-sm sm:text-right font-medium leading-5 text-gray-700">
                                Profile
                            </label>
                            <div class="flex-1">
                                <div class="flex-1 mt-1 max-w-lg relative rounded-md shadow-sm">
                                    <input id="profile" v-model="form.profile" class="form-input block w-full sm:text-sm sm:leading-5" :class="{'border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red': form.errors.has('profile')}"/>
                                </div>
                                <p v-show="form.errors.has('profile')" class="mt-1 text-sm text-red-600" id="profile-error">
                                    {{ form.errors.get('profile') }}
                                </p>
                            </div>
                        </div>

                        <div class="sm:flex mt-4">
                            <label for="name" class="mt-3 w-40 md:w-48 pr-8 block text-sm sm:text-right font-medium leading-5 text-gray-700">
                                Version
                            </label>
                            <div class="flex-1">
                                <div class="flex-1 mt-1 max-w-lg relative rounded-md shadow-sm">
                                    <input id="version" v-model="form.version" class="form-input block w-full sm:text-sm sm:leading-5" :class="{'border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red': form.errors.has('version')}"/>
                                </div>
                                <p v-show="form.errors.has('version')" class="mt-1 text-sm text-red-600" id="version-error">
                                    {{ form.errors.get('version') }}
                                </p>
                            </div>
                        </div>

                        <div class="sm:flex mt-4">
                            <label for="name" class="mt-3 w-40 md:w-48 pr-8 block text-sm sm:text-right font-medium leading-5 text-gray-700">
                                Port
                            </label>
                            <div class="flex-1">
                                <div class="flex-1 mt-1 max-w-lg relative rounded-md shadow-sm">
                                    <input id="port" v-model="form.port" class="form-input block w-full sm:text-sm sm:leading-5" :class="{'border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red': form.errors.has('port')}"/>
                                </div>
                                <p v-show="form.errors.has('port')" class="mt-1 text-sm text-red-600" id="port-error">
                                    {{ form.errors.get('port') }}
                                </p>
                            </div>
                        </div>

                        <div class="sm:flex sm:items-center mt-5">
                            <button type="submit" :disabled="busy" class="sm:ml-48 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-400 hover:bg-brand-300 focus:outline-none focus:border-brand-600 focus:shadow-outline-brand active:bg-brand-00 transition ease-in-out duration-150">
                    <span v-if="busy">
                        <font-awesome-icon  :icon="['fas', 'sync-alt']" class="mr-2" spin />
                        Working...
                    </span>
                                <span v-else>
                        Add Server
                    </span>
                            </button>
                        </div>
                    </fieldset>
                </form>

            </div>

        </div>
    </div>
</template>

<script>
	import Form from "../../form";

    export default {
		name: "NewServer",
        data() {
            return {
                busy: false,
                form: new Form({
                    name: '',
                    profile: 'vanilla',
                    version: '',
                    port: 25565,
                }),
            }
        },
        methods: {
            onSubmit() {
                this.busy = true
                this.form.post('/api/servers')
                    .then(response => {
                        this.$parent.$emit('ServerCreated', response)
                        this.busy = false
                    })
                    .catch(error => {
                        this.busy = false
                    })
            },
        },
	}
</script>
