<template>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Les vostres organitzacions</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                    <i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Nom</th>
                        <th>Domini</th>
                        <th>URL de la base de dades</th>
                        <th style="width: 40px">Accions</th>
                    </tr>
                    <tr v-for="tenant in internalTenants" :key="tenant.id">
                        <td>{{tenant.id}}</td>
                        <td>{{tenant.name}}<edit-tenant-icon :tenant="tenant"></edit-tenant-icon></td>
                        <td><a target="_blank" :href="fullUrl(tenant.subdomain)">{{fullUrl (tenant.subdomain)}}</a></td>
                        <td>mysql://{{tenant.username}}@{{tenant.hostname}}:{{tenant.port}}/{{tenant.database}}</td>
                        <td>
                            <test-connection-button :tenant="tenant"></test-connection-button>
                            <test-admin-user-button :tenant="tenant"></test-admin-user-button>
                            <delete-tenant-button :tenant="tenant"></delete-tenant-button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
  import TestConnectionButtonComponent from './TestConnectionButtonComponent'
  import TestAdminUserButtonComponent from './TestAdminUserButtonComponent'
  import DeleteTenantButtonComponent from './DeleteTenantButtonComponent'
  import EditTenantIconComponent from './EditTenantIconComponent'
  import * as types from '../store/mutation-types'
  import { mapGetters } from 'vuex'

  export default {
    components: {
      'test-connection-button': TestConnectionButtonComponent,
      'test-admin-user-button': TestAdminUserButtonComponent,
      'delete-tenant-button': DeleteTenantButtonComponent,
      'edit-tenant-icon': EditTenantIconComponent
    },
    computed: {
      ...mapGetters({
        internalTenants: 'tenants'
      })
    },
    props: {
      tenants: {
        type: Array,
        required: false
      }
    },
    methods: {
      fullUrl (subdomain) {
        return 'http://' + subdomain + '.' + Laravel.app.domain // eslint-disable-line
      }
    },
    mounted () {
      this.$store.commit(types.SET_TENANTS, this.tenants)
    }
  }
</script>
