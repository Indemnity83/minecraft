<template>
    <!-- eslint-disable -->
    <div class="home">
        <header>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 md:flex justify-between items-center">
                <h2 class="text-2xl font-bold leading-tight text-gray-900">
                    Server Details
                </h2>
                <div class="text-lg uppercase tracking-wide font-light text-gray-700 my-2">
                    <span>{{ server.name }}</span>
                    <span class="ml-8">{{ server.profile }}</span>
                    <span class="ml-8">{{ server.version }}</span>
                    <span class="ml-8">port {{ server.port }}</span>
                    <span class="ml-8">{{ server.status }}</span>
                </div>
            </div>
        </header>
        <main class="mt-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 lg:flex">
                <div class="w-full lg:w-60 mr-8 mb-6">
                    <nav>
                        <a href="#" class="group flex items-center px-3 py-2 text-sm leading-5 font-medium text-gray-900 rounded-md bg-gray-300 hover:text-gray-900 focus:outline-none focus:bg-gray-300 transition ease-in-out duration-150">
                            <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 text-gray-600 group-hover:text-gray-600 group-focus:text-gray-700 transition ease-in-out duration-150" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10M9 21h6"/>
                            </svg>
                            <span class="truncate">
                              Status
                            </span>
                        </a>
                        <a href="#" class="mt-1 group flex items-center px-3 py-2 text-sm leading-5 font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-300 focus:outline-none focus:text-gray-900 focus:bg-gray-200 transition ease-in-out duration-150">
                            <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-600 transition ease-in-out duration-150" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                            <span class="truncate">
                              Environment
                            </span>
                        </a>
                    </nav>
                </div>
                <div class="flex-1">
                    <server-actions :server="server"></server-actions>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
    import ServerActions from "../components/servers/ServerActions";

    export default {
        name: 'Server',

        props: ['serverId'],

        components: {
            ServerActions,
        },

        data() {
            return {
                server: [],
            }
        },

        methods: {
            getServer() {
                axios.get(`/api/servers/${this.serverId}`)
                    .then(response => this.server = response.data)
            },

            listenForLocalEvents() {
                this.$on('ServerUpdated', function(server){
                    console.log(server);
                    if (server.id === this.server.id) {
                        this.server = server
                    }
                });
            },

            listenForRemoteEvents() {
                Echo.channel('servers')
                    .listen('ServerUpdated', (e) => {
                        if (e.server.id === this.server.id) {
                            this.server = e.server
                        }
                    });
            }
        },

        created() {
            this.getServer();
            this.listenForLocalEvents();
            this.listenForRemoteEvents();
        },
    };
</script>
