
import axios from '../../services/api'

export default function(path)
{
  const api = axios(path),

    state = { },

    getters = { },

    mutations = { },

    actions = {

      create( { commit }, payload )
      {
        api.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`

        return new Promise((resolve, reject) => {
          api.post( '/create', payload )
             .then( res =>  resolve(res.data) )
             .catch( error => reject(error) )
        })
      },
      
      clone( { commit }, payload )
      {
        api.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`

        return new Promise((resolve, reject) => {
          api.post( `clone`, payload )
            .then( res =>  resolve(res.data) )
            .catch(error => reject(error))
        })
      },

      update( { commit }, payload )
      {
        api.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`

        return new Promise((resolve, reject) => {
          api.put( '/update', payload )
                .then( res =>  resolve(res.data) )
                .catch( error => reject(error) )
        })
      },

      store( { commit }, payload )
      {
        api.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`

        return new Promise((resolve, reject) => {
          api.post( '/store', payload )
                .then( res =>  resolve(res.data) )
                .catch( error => reject(error) )
        })
      },

      delete( { commit }, { id, type } )
      {
        api.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`

        return new Promise((resolve, reject) => {
          api.post( `delete/${type}`, { id } )
            .then( res =>  resolve(res.data) )
            .catch( error => reject(error) )
        })
      },

      restore( { commit }, { id } )
      {
        api.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`

        return new Promise((resolve, reject) => {
          api.post( `restore`, { id } )
            .then( res =>  resolve(res.data) )
            .catch(error => reject(error))
        })
      },

      clearTrash( { commit } )
      {
        api.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`

        return new Promise((resolve, reject) => {
          api.post( `clear-trash`)
            .then( res =>  resolve(res.data) )
            .catch( error => reject(error))
        })
      },

      find( { commit }, payload )
      {
        api.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`

        let trashed = 0
        return new Promise((resolve, reject) => {
          api.post( `find/${trashed}`, payload)
            .then( res => resolve(res.data) )
            .catch(error => reject(error) )
        })
      },

      types( { commit } )
      {
        api.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`

        return new Promise((resolve, reject) => {
          api.get( `types` )
                .then( res => resolve(res.data) )
                .catch(error => reject(error) )
        })
      },

      list( { commit }, payload )
      {
        api.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`

        return new Promise((resolve, reject) => {
          const promise = payload ?
                          api.post( `list/${payload.trashed}`,
                                       { selectedColumns: payload.selectedColumns,
                                         filter: payload.filter })
                          : api.get( `list` ) // handles array lists from config files

          promise.then( res => resolve(res.data) )
                  .catch(error => reject(error) )
        })
      },

      dataTable( { commit }, { trashed, payload, selectedColumns, filter, parent_id } )
      {
        return new Promise((resolve, reject) => {
          api.post( `data-table/${trashed}`, { payload, selectedColumns, filter, parent_id } )
                .then( res => resolve(res.data) )
                .catch(error => reject(error) )
        })
      },

      export( { commit }, { payload, selectedColumns, filters } )
      {
        return new Promise((resolve, reject) => {
          api.post( 'export', { payload, selectedColumns, filters } )
                .then( res => resolve(res.data) )
                .catch(error => reject(error) )
        })
      },
      
      import( { commit }, { file } )
      {
        let form_data = new FormData()
        form_data.append('file', file)

        // const $import_axios = axios.create({
        //               baseURL: `${process.env.MIX_API_BASE_URL}/${path}`,
        //               headers: {'Content-Type': 'multipart/form-data',
        //                         'Authorization': `Bearer ${localStorage.getItem('access_token')}` }
        //             })

        api.defaults.headers.common['Content-Type'] = `multipart/form-data`
        api.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('access_token')}`


        return new Promise((resolve, reject) => {
           $import_axios.post( 'import', form_data )                
                        .then( res => resolve(res.data) )
                        .catch(error => reject(error) )
        })
      }
    }

  return {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
  }
} 
