<template>
    <v-select
            name="user"
            :label="label"
            :items="users"
            v-model="user"
            item-text="name"
            chips
            autocomplete
            clearable
            @change="impersonate"
    >
        <template slot="selection" slot-scope="data">
            <v-chip
                    @input="data.parent.selectItem(data.item)"
                    :selected="data.selected"
                    class="chip--select-multi"
                    :key="JSON.stringify(data.item)"
            >
                <v-avatar>
                    <img :src="'/user/' + data.item.hashid + '/photo'">
                </v-avatar>
                {{ data.item.name }}
            </v-chip>
        </template>
        <template slot="item" slot-scope="{ item: user }">
            <template v-if="typeof user !== 'object'">
                <v-list-tile-content v-text="user"></v-list-tile-content>
            </template>
            <template v-else>
                <v-list-tile-avatar>
                    <img :src="'/user/' + user.hashid + '/photo'">
                </v-list-tile-avatar>
                <v-list-tile-content>
                    <v-list-tile-title v-html="user.name"></v-list-tile-title>
                    <v-list-tile-sub-title v-html="user.email"></v-list-tile-sub-title>
                </v-list-tile-content>
            </template>
        </template>
    </v-select>
</template>

<style>

</style>

<script>
  export default {
    data () {
      return {
        user: {}
      }
    },
    props: {
      users: {
        type: Array,
        required: true
      },
      label: {
        type: String,
        default: "Escolliu l'usuari a suplantar"
      }
    },
    methods: {
      impersonate (user) {
        if (user) {
          if (user) window.location.href = '/impersonate/take/' + user.id
        }
      }
    }
  }
</script>
