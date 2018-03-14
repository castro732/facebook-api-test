# Facebook API Test

Facebook open graph API test made with Lumen

## Getting Started

### Cloning the repo
Get the repo from github

```git clone github.com/castro732/facebook-api-test```

### Prerequisites
cd into the folder and install the dependencies

```cd facebook-api-test```

```composer install```

### Serve the application

You can use any server app, but for simplicity we will use php built-in server

```php -S localhost:8008 -t public```

If you use another port, take note as you will need this for the next step.

### Setup .env file

Make a copy of the .env.example file, and fill your data

```APP_URL=``` It's the base url, in this case it should be ```"http://localhost:8008"```

```FB_APP_ID=``` It's the **App ID** of the facebook APP used to access the graph API.

```FB_APP_SECRET=``` It's the **App secret** of the facebook APP used to access the graph API.

You can get your **app ID** and **secret** from ```https://developers.facebook.com/apps```

After this, your .env file should look something like this:

```
APP_URL="http://localhost:8008"
FB_APP_ID=123321
FB_APP_SECRET=123abccba321
```

## How to use it

### Getting the token

#### From your browser
- Just head to the base url and the app will ask you to login to facebook in order to get the access token.
- Once logged in, your token will be saved to the session and will be displayed to you, so you can copy it and use it elsewhere.

#### From an API tool (no session)
- If you do not already have an access token, you need to get one from the browser, as stated previously.

### Accessing the info

#### From your browser
- You can now access ```/profile/facebook/{id}``` where ```{id}``` is a valid facebook account id
- You will get a ```200``` response containing the info for the given id.

For example:
```
{
  "first_name": "Morgan",
  "last_name": "Grice",
  "id": "123"
}
```
- If you use an invalid ```{id}``` you will get a ```422``` response with an error

For example:
```
{
  "error": {
    "message": "(#803) Some of the aliases you requested do not exist: 12332132131321321321"
  }
}
```

#### From an API tool (no session)

- Send a ```GET``` request with the headers ```Accept: application/json``` and ```Authorization: Bearer {your-access-token}```

``` GET /profile/facebook/{id}```

- You will get the same responses as in the browser.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
