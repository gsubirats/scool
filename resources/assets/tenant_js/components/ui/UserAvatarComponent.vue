<template>
    <v-avatar color="grey lighten-4" :size="size" v-if="hashId" :tile="tile">
        <img :src="'/user/' + hashId + '/photo'"
             :alt="alt"
             :title="alt">
    </v-avatar>
</template>

<script>
  export default {
    name: 'UserAvatarComponent',
    props: {
      hashId: {
        required: true
      },
      size: {
        type: String,
        default: '40'
      },
      alt: {
        required: true
      },
      tile: {
        type: Boolean,
        default: false
      }
    }
  }
</script>

<style scoped>

</style>