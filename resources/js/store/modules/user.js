
import axios from '../../services/api'

function refresh_token_payload() {
  return {
    grant_type: 'refresh_token',
    refresh_token: localStorage.refresh_token,
    client_id: process.env.MIX_CLIENT_ID,
    client_secret: process.env.MIX_CLIENT_SECRET
  }
}

const $axios = axios('users'),

    state= {
      user: localStorage.getItem('user') || '{}',
      access_token: localStorage.getItem('access_token'),
      refresh_token: localStorage.getItem('refresh_token'),
    },

    getters= {

      user(state) {
        return ( typeof(state.user) === 'string') ? JSON.parse(state.user) : state.user
      },

      access_token(state) {
        return state.access_token
      },

      refresh_token(state) {
        return state.refresh_token
      }
    },

    mutations= {

      user(state, user) {
        localStorage.setItem('user', JSON.stringify(user))
        state.user = user
      },

      saveProfilePicture(state, url)
      {
        let user = getters.user(state)
        user.profile_picture = url
        localStorage.setItem('user', JSON.stringify(user))
      },
      
      saveSignature(state, { type, signature })
      {
        let user = getters.user(state)
        user.signature[type] = signature
        state.user = user
        localStorage.setItem('user', JSON.stringify(user))
      },

      logout(state)
      {
        localStorage.removeItem('access_token')
        localStorage.removeItem('refresh_token')
      },

      authorized(state, { access_token, refresh_token }) {
        localStorage.setItem('access_token', access_token)
        localStorage.setItem('refresh_token', refresh_token)
      },
    },

    actions = {

      changePassword( { commit }, payload )
      {
        $axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`
        
        return new Promise((resolve, reject) => {
          $axios.post( '/change-password', payload )
            .then( res =>  resolve(res) )
            .catch( error => reject(error) )
        })
      },

      create( { commit }, payload )
      {
        $axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`
        
        return new Promise((resolve, reject) => {
          $axios.post( '/create', payload )
                .then( res =>  resolve(res.data) )
                .catch( error => reject(error) )
        })
      },

      update( { commit }, payload )
      {
        $axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`
        
        return new Promise((resolve, reject) => {
          $axios.put( '/update', payload )
                .then( res =>  resolve(res.data) )
                .catch( error => reject(error) )
        })
      },


      delete( { commit }, { id, type } )
      {
        $axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`

        return new Promise((resolve, reject) => {
          $axios.post( `delete/${type}`, { id } )
                .then( res =>  resolve(res.data) )
                .catch( error => reject(error) )
        })
      },

      restore( { commit }, { id } )
      {
        $axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`

        return new Promise((resolve, reject) => {
          $axios.post( `restore`, { id } )
                .then( res => resolve(res.data) )
                .catch(error => reject(error))
        })
      },
		
      refreshToken(context)
      {      
        return new Promise((resolve, reject) => {
          $axios.post(process.env.MIX_AUTH_SERVER_TOKEN_ENDPOINT, refresh_token_payload())
                .then( res => {
                  context.commit('authorized', res.data)
                  resolve(res)
                })
                .catch(error => reject(error))
        })
      },
		
      async logout(context, payload)
      {
        if (payload.id) {
          await actions.forceLogout(context, payload)
        }
        
        window.location
          .replace(`${process.env.MIX_AUTH_SERVER}/login?continue=${window.location.href}&appid=Account`)
      },
		
      forceLogout(context, payload)
      {
        $axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`

        return new Promise((resolve, reject) => {
          $axios.post( 'force-logout', payload )
                .then( res => resolve(res.data) )
                .catch(error => reject(error))
        })
      },
		
      // Multi factor authentication - mfa
      mfa( context, payload )
      {
        $axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`

        return new Promise((resolve, reject) => {
          $axios.post( 'mfa', payload )
                .then(res => resolve(res))
                .catch(error => reject(error))
        })
      },

      resetPasswordLink( { commit }, { email } )
      {
        $axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`

        return new Promise((resolve, reject) => {
          $axios.post( `email/password-link`, { email } )
                .then( res =>  resolve(res.data) )
                .catch(error => reject(error))
        })
      },

      resetPassword( { commit }, user )
      {
        $axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`
        
        return new Promise((resolve, reject) => {
          $axios.post( `reset/password`, user )
                .then( res =>  resolve(res.data) )
                .catch(error => reject(error))
        })
      },

      sendCreatePasswordLink( context, payload )
      {
        $axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`
        $axios.defaults.baseURL = `${process.env.MIX_AUTH_SERVER_API}/auth`

        return new Promise((resolve, reject) => {
          $axios.post( '/password/email/send', payload )
                .then( res =>  resolve(res.data) )
                .catch(error =>  reject(error) )
        })
      },

      clearTrash( { commit } )
      {
        $axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`

        return new Promise((resolve, reject) => {
          $axios.post( `clear-trash`)
            .then( res =>  resolve(res.data) )
            .catch( error => reject(error))
        })
      },

      saveProfilePicture( { commit }, payload )
      {
        $axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`
        $axios.defaults.headers.common['Content-Type'] = 'multipart/form-data'
        
        return new Promise((resolve, reject) => {
          $axios.post('/profile-picture', payload )
                .then( res => resolve(res) )
                .catch( error => reject(error) )
        })
      },

      saveSignature( { commit }, payload )
      {
        $axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`
        $axios.defaults.headers.common['Content-Type'] = 'multipart/form-data'
        
        return new Promise((resolve, reject) => {
          $axios.post( '/save-signature', payload )
                .then( res => resolve(res.data) )
                .catch( error => reject(error) )
        })
      },

      removeProfilePicture( { commit }, payload )
      {
        $axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`
        
        return new Promise((resolve, reject) => {
          $axios.post( '/remove-profile-image', payload )
                .then( res => resolve(res))
                .catch( error => reject(error) )
        })
      },

      find( { commit }, payload )
      {
        $axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`

        let trashed = 0
        return new Promise((resolve, reject) => {
          $axios.post( `find/${trashed}`, payload)
            .then( res => resolve(res.data) )
            .catch(error => reject(error) )
        })
      },

      list( { commit }, payload )
      {
        $axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`
        
        return new Promise((resolve, reject) => {
          const promise = payload ?
                          $axios.post( `list/${payload.trashed}`,
                                       { selectedColumns: payload.selectedColumns,
                                         filter: payload.filter })
                          : $axios.get( `list` ) // handles array lists from config files

          promise.then( res => resolve(res.data) )
                  .catch(error => reject(error) )
        })
      },
  
      timezone( { commit } )
      {
        $axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`
        
        return new Promise((resolve, reject) => {
          $axios.get( `timezones/list` )
                .then( res => resolve(res.data) )
                .catch(error => reject(error) )
        })
      },

      dataTable( { commit }, { trashed, payload, selectedColumns, filter, parent_id } )
      {
        return new Promise((resolve, reject) => {
          $axios.post( `data-table/${trashed}`, { payload, selectedColumns, filter, parent_id } )
                .then( res => resolve(res.data) )
                .catch(error => reject(error) )
        })
      },

      export( { commit }, { payload, selectedColumns, filters } )
      {
        return new Promise((resolve, reject) => {
          $axios.post( 'export', { payload, selectedColumns, filters } )
                .then( res => resolve(res.data) )
                .catch(error => reject(error) )
        })
      },
    }

export default {
    state,
    getters,
    mutations,
    actions
};
