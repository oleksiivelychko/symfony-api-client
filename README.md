# symfony-api-client

### CLI client based on Symfony console skeleton which interacts with remote API.

Available console commands:

📌 Create group:
```
./console.php api:create-group [name] [userId userId...]
```

📌 Update group:
```
./console.php api:update-group [id] [name] [userId userId...]
```

📌 Delete group:
```
./console.php api:delete-group [id]
```

📌 Create user.
```
./console.php api:create-user [name] [email]
```

📌 Update user (...and add to groups 1,2).
```
./console.php api:update-user [id] [name] [groupId,groupId...]
```

📌 Delete user.
```
./console.php api:delete-user [id]
```

📌 List users in groups.
```
./console.php api:list-group [id]
```

💡 <a href="https://github.com/oleksiivelychko/symfony-api">API server</a> must be running before use.

💡 To switch between API versions set the desirable version as environment variable:
```
API_VERSION=2
```

💡 In order to debug CLI application in PhpStorm settings **PHP -> Debug**
select `Ignore external connections through unregistered server configurations`

...amd run it before CLI script would be executed:
```
export XDEBUG_MODE=debug XDEBUG_SESSION=1
```

Covered by integration tests.