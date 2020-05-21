<template>
    <main class="mt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 lg:flex">
            <div class="flex-1">
                <new-server class="mb-8"></new-server>
                <active-servers :servers="servers" class="mb-8"></active-servers>
            </div>
        </div>
    </main>
</template>

<script>
    import ActiveServers from '../components/servers/ActiveServers';
    import NewServer from '../components/servers/NewServer';

	export default {
		name: "Dashboard.vue",
        components: {
		    ActiveServers,
            NewServer,
        },
        data() {
            return {
                servers: [],
            }
        },
        methods: {
            getServers() {
                axios.get('/api/servers')
                    .then(response => this.servers = response.data)
            },

            listenForLocalEvents() {
                this.$on('ServerCreated', function(server){
                    this.servers.push(server)
                });
            },

            listenForRemoteEvents() {
                Echo.channel('servers')
                    .listen('ServerUpdated', (e) => {
                        this.servers.splice(_.findIndex(this.servers, ['id', e.server.id]), 1, e.server)
                    })
                    .listen('ServerCreated', (e) => {
                        this.servers.push(e.server)
                    });
            }
        },

        created() {
            this.getServers();
            this.listenForLocalEvents();
            this.listenForRemoteEvents();
        },
    }
</script>
