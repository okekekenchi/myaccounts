
export default function guest({next, router})
{
  if (localStorage.access_token && localStorage.refresh_token)
  {
    router.push({ name: 'dashboard'})
  }

  return next()
}

