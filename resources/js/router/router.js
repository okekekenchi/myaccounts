import Vue from 'vue'
import VueRouter from 'vue-router'

/**
 * *************Middleware
 */
import auth from '../middleware/auth'
import guest from '../middleware/guest'
// +++++++++++++++++++++++++++++++++++++++++++++++


import PageNotFound from '../views/v1/error/PageNotFound.vue'
import ExpiredPage from '../views/v1/error/ExpiredPage.vue'
import OfflinePage from '../views/v1/error/OfflinePage.vue'
import Unauthorized from '../views/v1/error/Unauthorized.vue'


import Home from "../views/v1/Home.vue"
import PersonalDetail from "../views/v1/PersonalDetail.vue"
import Signature from "../views/v1/Signature.vue"
import Users from "../views/v1/users/User.vue"
import UserDetail from "../views/v1/users/UserDetail.vue"
import Security from "../views/v1/Security.vue"
import ApiKey from "../views/v1/ApiKey.vue"
import DataPrivacy from "../views/v1/DataPrivacy.vue"
import Role from "../views/v1/accesscontrol/Role.vue"
import EditRole from "../views/v1/accesscontrol/EditRole.vue"
import Activity from "../views/v1/Activity.vue"
import PaymentSubscription from "../views/v1/PaymentSubscription.vue"
import About from "../views/v1/About.vue"

import OAuthCallback from '../views/auth/OAuthCallback.vue'


const routes = [

  { path: '/oauth/callback', name: 'oauth.callback',
		component: OAuthCallback,
		meta: {	middleware: [ guest ] }
	},

	{ path: '',
	 	name: 'home',
		component: Home,
		meta: { 
			middleware: [ auth ]
		}
	},

	{ path: '/personal-detail', name: 'personal.detail',
		component: PersonalDetail,
		meta: {
			middleware: [ auth ]
		}
	},

	{ path: '/signature', name: 'signature',
		component: Signature,
		meta: {
			middleware: [ auth ]
		}
	},

	{ path: '/users', name: 'user',
		component: Users,
		meta: { 
			middleware: [ auth ]
		}
	},

	{ path: '/user-detail/:user_id', name: 'user.detail',
		component: UserDetail,
		meta: { 
			middleware: [ auth ]
		}
	},

	{ path: '/security', name: 'security',
		component: Security,
		meta: {
			middleware: [ auth ]
		}
	},

	{ path: '/api-key', name: 'api.key',
		component: ApiKey,
		meta: {
			middleware: [ auth ]
		}
	},

	{ path: '/data-privacy', name: 'data.privacy',
		component: DataPrivacy,
		meta: {
			middleware: [ auth ]
		}
	},

	{ path: '/roles', name: 'role',
		component: Role,
		meta: { 
			middleware: [ auth ]
		}
	},

	{ path: '/role/edit/:role_id', name: 'edit.role',
		component: EditRole,
		meta: { 
			middleware: [ auth ]
		}
	},

	{ path: '/activity-logs', name: 'activity',
		component: Activity,
		meta: { 
			middleware: [ auth ]
		}
	},

	{ path: '/payment-subscriptions', name: 'payment.subscription',
		component: PaymentSubscription,
		meta: { 
			middleware: [ auth ]
		}
	},

	{ path: '/about', name: 'about',
		component: About,
		meta: { 
			middleware: [ auth ]
		}
	},



  { path: '/expired-page', name: 'expired.page',
		component: ExpiredPage,
		meta: { 
			error: true
		}
	},

  { path: '/offline-page', name: 'offline.page',
		component: OfflinePage,
		meta: {
			error: true
		}
	},

  { path: '/unauthorized', name: 'unauthorized',
		component: Unauthorized,
		meta: {
			error: true
		}
	},

  // { path: '*', name: 'pageNotFound', component: PageNotFound, meta: { error: true } },
  { path: '/:NotFound(.*)*', name: 'pageNotFound', component: PageNotFound, meta: { error: true } },

]



Vue.use(VueRouter)

const router = new VueRouter({
	mode: 'history',
	routes,
})




/**
 * @author Markus Oberlehner
 * @link https://markus,oberlehner.net/blog/implementing-a-simple-middleware-with-vue-router/
 * @since 26 August 2018
 * 
 * Creates a nextMiddleware() function which not only
 * runs the default next() callback but also triggers
 * the subsequent Middleware functions
 * 
 * @param {*} context 
 * @param {*} middleware 
 * @param {*} index 
 * @returns 
 */
function nextFactory(context, middleware, index)
{
	const subsequentMiddleware = middleware[index]
	/**
	 * If there is no subsequent middleware,
	 * the default next() callback is returned
	 */
	if (!subsequentMiddleware) return context.next

	return (...parameters) => {
		// Run the default Vue Router next() callback first
		context.next(...parameters)
		// Then run the subsequent middleware with a new 
		// nextMiddleware() callback.
		const nextMiddleware = nextFactory(context, middleware, index + 1)
		subsequentMiddleware({ ...context, next: nextMiddleware})
	}
}

router.beforeEach(async (to, from, next) => {

	if (to.meta.middleware)
	{
		const middleware = Array.isArray(to.meta.middleware)
																	? to.meta.middleware
																	: [to.meta.middleware]

		const context = { from, next, router, to}

		const nextMiddleware = nextFactory(context, middleware, 1)

		return middleware[0]({ ...context, next: nextMiddleware })
	}

	return next()
})

router.onError((e) => {
	// console.log(e)
})

export default router

// https://www.epochconverter.com/
// function currentUnixTimeStamp() {
//     return Math.floor(new Date().getTime()/1000.0)
// }
