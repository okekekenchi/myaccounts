import '@mdi/font/css/materialdesignicons.css';
// import '@fortawesome/fontawesome-free/css/all.css';
import 'vuetify/dist/vuetify.min.css';
import Vue from 'vue';
import Vuetify from 'vuetify/lib';

import pl from 'vuetify/lib/locale/pl'
import en from 'vuetify/lib/locale/en'
import es from 'vuetify/lib/locale/es'

Vue.use(Vuetify)

const opts = {
    iconfont: 'mdi',
    locales: { es, pl, en },
    current: 'es',
}

export default new Vuetify(opts)