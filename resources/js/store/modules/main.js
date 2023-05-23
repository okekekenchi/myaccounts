let gradient = false,

  themes = [
    {
      color: gradient ? 'linear-gradient(45deg, seagreen, transparent)' : '#817a7a',
      new_button: 'blue darken-4',
      button: 'blue darken-4',
      report_banner: 'blue lighten-5',
      app_bar: 'green darken-4',
      right_nav: 'darkslategrey',

      action_button: 'blue darken-4',
      bread_crumb: 'grey lighten-2',
      right_nav_top: 'grey lighten-2',
      right_nav_middle: 'white',
      right_nav_bottom: 'grey darken-3',
    },
    {
        color: gradient ? 'linear-gradient(45deg, seagreen, transparent)' : '#817a7a',
        new_button: 'blue darken-4',
        button: 'blue darken-4',
        report_banner: 'blue lighten-5',
        app_bar: 'darkslategrey',
        right_nav: 'darkslategrey',
    },
  ]

const state = {
      // appReady: false,

      // {background: black, button: blue, report_banner: 'blue lighten-5'}
      // {background: #a0a0a0, button: 'blue', report_banner: 'blue lighten-5'} darkgrey
      // {background: maroon, button: red}

      theme: themes[0],

      rightDrawer: localStorage.getItem('right_drawer'),
      blur_div: false,
      alert: false,
      message: ''
  },

  getters = {
      // appReady: state => state.appLoaded,
      theme: state => state.theme,
      randomColor: () => '#' + ( Math.random()*0xFFFFFF<<0 ).toString(16),

      rightDrawer(state)
      {
        if (state.rightDrawer == null) {
          localStorage.setItem('right_drawer', true)
          return true
        }
        return (state.rightDrawer === 'true') ? true : false
      },

      blurDiv(state)
      {
        return state.blur_div
      },
      
      alert(state)
      {
        return state.alert
      },
      
      message(state)
      {
        return state.message
      },
  },

  mutations = {
      
      setRightDrawer(state, val) {
        state.rightDrawer = val.toString()
        localStorage.setItem('right_drawer', state.rightDrawer)
      },
      
      blurDiv(state, val) {
        state.blur_div = val
      },
      
      alert(state, val) {
        state.alert = val
      },
      
      notify(state, msg) {
        state.alert = true
        state.message = msg
      },
  },

  actions = {
    
  }

export default {
  state,
  getters,
  mutations,
  actions
};
