import axios from 'axios'
import store from '../store/store'

function refresh_token_payload() {
  return {
    grant_type: 'refresh_token',
    refresh_token: localStorage.refresh_token,
    client_id: process.env.MIX_CLIENT_ID,
    client_secret: process.env.MIX_CLIENT_SECRET
  }
}

export default function(path = null)
{
  let $axios = axios.create({
        baseURL: `${process.env.MIX_AUTH_SERVER_API}/${path}`,
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        }
      })


  $axios.interceptors.request.use( request => {

      if (!user.id) {
        window.location
              .replace(`${process.env.MIX_AUTH_SERVER}/login?continue=${window.location.href}`)
      }
      
      return request
    },
    error => {
      Promise.reject(error)
    }
  )

  
  $axios.interceptors.response.use(
    response => response,
    
    async (error) => {

      if(!error) {
        store.commit('notify', 'You are now offline.')
      }
      else if ( error.response.status === 401 &&
                  error.response.data.message === "Unauthenticated." ) {
        /**
         * Possible reasons for this error
         * 1. Token was cleared from DB or has expired
         * 2. Auth bearer token was not sent along side with request
         */
        const original_request = error.config
        return await axios.post(process.env.MIX_AUTH_SERVER_TOKEN_ENDPOINT,
                                  refresh_token_payload())
                    .then( ({data}) => {
                      store.commit('authorized', data)
                      original_request.headers.Authorization = `Bearer ${data.access_token}`
                      return axios.request(original_request)
                    })
                    .catch( () => {
                      store.commit('logout')
                      window.location.reload()
                    })
      }
  
      return Promise.reject(error)
    }
  )

  return $axios
}
