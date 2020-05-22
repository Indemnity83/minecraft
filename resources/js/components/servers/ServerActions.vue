<template>
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="sm:flex sm:items-start sm:justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Server Actions
                    </h3>
                    <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae voluptatibus corrupti atque repudiandae nam.
                        </p>
                    </div>
                </div>
                <div class="mt-5 sm:mt-0 sm:ml-6 sm:flex-shrink-0 sm:flex sm:items-center">
        <span class="inline-flex rounded-md shadow-sm">
          <button v-if="startable" type="button" @click="signalServer('start')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-400 hover:bg-brand-300 focus:outline-none focus:border-brand-500 focus:shadow-outline-brand active:bg-brand-500 transition ease-in-out duration-150">
            Start Server
          </button>

            <button v-if="stoppable" type="button" @click="signalServer('stop')" class="ml-2 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-danger-400 hover:bg-danger-300 focus:outline-none focus:border-danger-500 focus:shadow-outline-danger active:bg-danger-500 transition ease-in-out duration-150">
            Stop Server
          </button>

            <button v-if="busy" type="button" disabled class="cursor-wait ml-2 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-600 bg-gray-400">
                <font-awesome-icon :icon="['fas', 'circle-notch']" class="mr-2 text-base" spin></font-awesome-icon>
                Working
          </button>
        </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	export default {
        name: "ServerActions",

        props: ['server'],

        computed: {
            busy: function () {
                return _.includes([
                    'start pending',
                    'start pending',
                    'restart pending',
                    'starting',
                    'backoff',
                    'stop pending',
                    'stopping',
                    'unknown',
                    'installing',
                ], this.server.status);
            },

            stoppable: function () {
                return _.includes([
                    'running',
                ], this.server.status);
            },

            startable: function () {
                return _.includes([
                    'exited',
                    'stopped',
                    'fatal',
                    'installed',
                ], this.server.status);
            },
        },

        methods: {
            signalServer(signal) {
                axios.put(`/api/servers/${this.server.id}/signal/`, {
                    signal: signal
                })
                    .then(response => {
                        this.$parent.$emit('ServerUpdated', response.data);
                    })
                    .catch(error => {
                        alert(error);
                    });
            },
        }
    }
</script>

