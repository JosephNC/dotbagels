import Vue from 'vue'
import VueMeta from 'vue-meta'
import PortalVue from 'portal-vue'
import { App, plugin } from '@inertiajs/inertia-vue'
import { InertiaProgress } from '@inertiajs/progress/src'
import mixins from './mixins'

Vue.config.productionTip = false
Vue.mixin( mixins )
Vue.use(plugin)
Vue.use(PortalVue)
Vue.use(VueMeta)

InertiaProgress.init({
    delay: 250,
    color: '#8da892',
    includeCSS: true,
    showSpinner: true,
})

const el = document.getElementById('app')

new Vue({
    metaInfo: {
        titleTemplate: title => (title ? `${title} - Dot Bagels` : 'Dot Bagels'),
    },
    render: h =>
        h(App, {
            props: {
                initialPage: JSON.parse(el.dataset.page),
                resolveComponent: name => import(`@/Pages/${name}`).then(module => module.default),
            },
        }),
}).$mount(el)
