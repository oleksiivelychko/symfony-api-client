# symfony-api-client

### CLI client based on Symfony console skeleton which interacts with remote API.

Available console commands:

📌 Create group.
```
./console.php api:create-group test
```

📌 Update group.
```
./console.php api:update-group 1 newTest
```

📌 Delete group.
```
./console.php api:delete-group 1
```

📌 Create user.
```
./console.php api:create-user test test@test.test
```

📌 Update user (...and add to groups 1,2).
```
./console.php api:create-user 1 test 1,2
```

📌 Delete user.
```
./console.php api:delete-user 1
```

📌 List users in groups.
```
./console.php api:list-group
```

💡 To switch between API versions set the desirable version as environment variable:
```
API_VERSION=2
```