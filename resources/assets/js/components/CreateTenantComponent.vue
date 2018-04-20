<template>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Organització</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                    <i class="fa fa-times"></i></button>
            </div>
        </div>
        <form role="form" >
            <div class="box-body">
                <div class="form-group">
                    <label for="inputName">Nom</label>
                    <input type="text" class="form-control" id="inputName" v-model="name"
                           placeholder="Nom de l'organització">
                </div>
                <div class="form-group">
                    <label for="inputSubdomain">Subdomini</label>
                    <input type="text" class="form-control" id="inputSubdomain" v-model="subdomain"
                           placeholder="Subdomini">
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary" @click.prevent="create">Crear</button>
            </div>
        </form>
    </div>
</template>

<script>
  export default {
   data() {
     return {
       name: '',
       subdomain: '',
     }
   },
   methods: {
     create() {
       console.log('Creating tenant ' + this.name)
       axios.post('/tenant', {
         'name': this.name,
         'subdomain': this.subdomain,
       }).then((response) => {

       }).catch((error) => {
         console.log(error)
       })
     }
   }
  }
</script>
