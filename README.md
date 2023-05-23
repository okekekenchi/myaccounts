
## Install Passport

1. run migration

2. Create encryption keys needed to generate secure access token.
   Create "personal access" and "password grant" that will be used to generate access tokens

   $ passport:install

   $ fix:passport   |  [ n--rollback ]

3. During deployment, generate encryption keys Passport needs to generate access tokens
   $ passport:keys

4. Register application
   $ passport:client

# get all oauth clients
   axios.get("http://kenmaxi-erp.com/oauth/clients").then(res => console.log(res.data))

# create new client
   const data = {
      name: 'client demo',
      redirect: 'http://demo.com/callback'
   }

   axios.post("http://kenmaxi-erp.com/oauth/clients")
      .then(res => console.log(res.data))
      .catch(err => console.log(err))


### Setup Google credential

Go to the Credentials page.
Click Create credentials > OAuth client ID.
Select the Web application application type.
Name your OAuth 2.0 client and click Create






https://docs.oracle.com/cd/E12454_01/sim/pdf/160/html/store_user_guide/toc.htm


// https://www.itechempires.com/2019/09/how-to-secure-pdf-files-in-laravel-and-allow-access-to-authenticated-users/

// https://www.w3docs.com/snippets/html/how-to-embed-pdf-in-html.html


