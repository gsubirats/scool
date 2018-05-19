<template>
    <v-select
            :label="label"
            :items="teachers"
            v-model="teacher"
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
                    <img :src="'/user_photo/' + data.item.hashid">
                </v-avatar>
                {{ data.item.name }}
            </v-chip>
        </template>
        <template slot="item" slot-scope="{ item: teacher }">
            <template v-if="typeof teacher !== 'object'">
                <v-list-tile-content v-text="teacher"></v-list-tile-content>
            </template>
            <template v-else>
                <v-list-tile-avatar>
                    <img :src="'/user_photo/' + teacher.hashid">
                </v-list-tile-avatar>
                <v-list-tile-content>
                    <v-list-tile-title v-html="teacher.name"></v-list-tile-title>
                    <v-list-tile-sub-title v-html="teacher.email"></v-list-tile-sub-title>
                </v-list-tile-content>
            </template>
        </template>
    </v-select>
</template>

<style>

</style>

<script>
  export default {
    model: {
      prop: 'teacher',
      event: 'input'
    },
    props: {
      teacher: {
        type: Object
      },
      label: {
        type: String,
        default: 'Escolliu un professor'
      },
      teachers: {
        type: Array,
        required: true
      }
    },
    methods: {
      input (value) {
        this.$emit('input', value)
      }
    }
  }
</script>
