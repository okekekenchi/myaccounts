import store from "../store/store"

export default function auth({next, router})
{
  if (!localStorage.access_token || !localStorage.refresh_token)
  {
    /**
     * If Authenticated but the acces token is not set,
     * the user is redirected to the authentication server for authorization
     */
    store.commit('User/logout')
    window.location.replace(`${process.env.MIX_BASE_URL}/oauth/redirect`)
  }

  return next()
}
