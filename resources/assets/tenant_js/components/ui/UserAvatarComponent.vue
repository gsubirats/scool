<template>
    <v-avatar color="grey lighten-4" :size="size" v-if="hashId">
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
        type: String,
        required: true
      }
    }
  }
</script>

<style scoped>

</style>