<template>
    <v-select
            :name="name"
            :label="label"
            :items="users"
            v-model="internalUser"
            item-text="name"
            chips
            autocomplete
            clearable
            @input="input"
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
            <v-list-tile-avatar>
                <img :src="'/user/' + user.hashid + '/photo'">
            </v-list-tile-avatar>
            <v-list-tile-content>
                <v-list-tile-title v-html="user.name"></v-list-tile-title>
                <v-list-tile-sub-title v-html="user.email"></v-list-tile-sub-title>
            </v-list-tile-content>
        </template>
    </v-select>
</template>

<style>

</style>

<script>
  export default {
    data () {
      return {
        internalUser: this.user
      }
    },
    model: {
      prop: 'user',
      event: 'input'
    },
    props: {
      name: {
        type: String,
        default: 'user'
      },
      user: {
        type: Object
      },
      label: {
        type: String,
        default: 'Escolliu un usuari'
      },
      users: {
        type: Array,
        required: true
      }
    },
    watch: {
      user (newUser) {
        this.internalUser = newUser
      }
    },
    methods: {
      input (value) {
        this.$emit('input', value)
      }
    }
  }
</script>
