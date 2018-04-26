import * as mutations from '../../mutation-types'
import * as actions from '../../action-types'
// import tenants from '../../../api/tenants'

export default {
  [ actions.SET_TENANTS ] (context, tenants) {
    context.commit(mutations.SET_TENANTS, tenants)
  }
}
