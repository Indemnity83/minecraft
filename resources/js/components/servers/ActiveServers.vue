<template>
    <!-- eslint-disable -->
    <div class="bg-white overflow-hidden overflow-hidden shadow rounded-lg">

        <div class="px-4 sm:px-6">
            <h3 class="py-5 text-lg leading-6 font-medium text-gray-900">
                Active Servers
            </h3>
        </div>

        <div class="border-t border-gray-200 bg-gray-100">
            <table class="min-w-full">
                <thead>
                <tr>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Profile
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Version
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Port
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Memory
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white">
                <tr v-for="server in servers" class="hover:bg-blue-50" :key="server.id">
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 leading-5 text-brand-400">
                        <router-link :to="'servers/' + server.id" class="hover:text-brand-600 hover:underline flex items-center">
                            <font-awesome-icon v-if="server.status === 'installing'" :icon="['fas', 'sync-alt']" class="mr-2" spin />
                            {{ server.name }}
                        </router-link>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                        {{ server.profile | capitalize }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                        {{ server.version }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                        {{ server.port }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                        {{ server.memory }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                        {{ server.status | capitalize }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>

<script>
	export default {
		name: "ActiveServers",
        props: ['servers'],
        filters: {
            capitalize: function (value) {
                if (!value) return '';
                value = value.toString();

                return value.charAt(0).toUpperCase() + value.slice(1);
            }
        }
	}
</script>
