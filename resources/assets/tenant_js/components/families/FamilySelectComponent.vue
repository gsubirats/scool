<template>
    <v-select
            :name="name"
            :label="label"
            autocomplete
            :required="required"
            clearable
            :error-messages="errorMessages"
            @input="input()"
            @blur="blur()"
            :items="families"
            v-model="internalFamily"
            item-text="name"
            item-value="name"
    >
        <template slot="item" slot-scope="{item: family}">
            <v-list-tile-content v-text="family.name"></v-list-tile-content>
        </template>
    </v-select>
</template>

<script>
  export default {
    name: 'FamilySelectComponent',
    model: {
      prop: 'family',
      event: 'input'
    },
    data () {
      return {
        internalFamily: this.family
      }
    },
    props: {
      name: {
        type: String,
        default: 'name'
      },
      label: {
        type: String,
        default: 'Família'
      },
      families: {
        type: Array,
        required: true
      },
      family: {
        required: true
      },
      errorMessages: {
        type: Array,
        required: true
      },
      required: {
        type: Boolean,
        default: true
      }
    },
    watch: {
      family (newFamily) {
        this.internalFamily = newFamily
      }
    },
    methods: {
      input () {
        this.$emit('input', this.internalFamily)
      },
      blur () {
        this.$emit('blur', this.internalFamily)
      }
    }
  }
</script>
