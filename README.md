# Laravel CQRS Example

Command Query Responsibility Segregation (CQRS) is a pattern in which you separate read and write operations for a data store. In Laravel, implementing CQRS involves creating distinct layers for handling commands (writes) and queries (reads). This is an example to illustrate CQRS in a Laravel environment.

## Create a Command
A command is responsible for encapsulating all the information needed to perform an action. Let's create an interface for our commands.

```
interface Command { }
```

Let's create a command for creating a new user.

```
class CreateUserCommand implements Command
{
    public string $name;
    public string $email;
    public string $password;

    public function __construct(string $name, string $email, string $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }
}
```

## Create a Command Handler

A command handler processes the command. Let's create an interface for our command handlers.

```
interface CommandHandler
{
    public function handle(Command $command): void;
}
```

Let's create a command handler for creating a new user.

```
class CreateUserHandler implements CommandHandler
{
    public function handle(Command $command): void
    {
        if (!$command instanceof CreateUserCommand) {
            throw new \InvalidArgumentException('Invalid command type');
        }

        $user = new User();
        $user->name = $command->name;
        $user->email = $command->email;
        $user->password = Hash::make($command->password);
        $user->save();
    }
}
```

## Create a Query

A query is responsible for fetching data. Let's create an interface for our queries.

```
interface Query { }
```

Let's create a query for fetching a user by email.

```
class GetUserByEmailQuery implements Query
{
    public string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }
}
```

## Create a Query Handler

A query handler processes the query. Let's create an interface for our query handlers.

```
interface QueryHandler
{
    public function handle(Query $query): mixed;
}
```

Let's create a query handler for fetching a user by email.

```
class GetUserByEmailHandler implements QueryHandler
{
    public function handle(Query $query): ?User
    {
        if (!$query instanceof GetUserByEmailQuery) {
            throw new \InvalidArgumentException('Invalid query type');
        }

        return User::where('email', $query->email)->first();
    }
}
```

## Create a Command Bus and Query Bus
The command bus and query bus are responsible for dispatching commands and queries to their respective handlers. We created the following bus classes:

CommandBus.php

```
class CommandBus
{
    protected array $handlers = [];

    public function register(string $command, string $handler): void
    {
        $this->handlers[$command] = $handler;
    }

    public function dispatch(Command $command): mixed
    {
        $handler = $this->handlers[get_class($command)];
        return (new $handler())->handle($command);
    }
}
```

QueryBus.php

```
class QueryBus
{
    protected array $handlers = [];

    public function register(string $query, string $handler): void
    {
        $this->handlers[$query] = $handler;
    }

    public function dispatch(Query $query)
    {
        $handler = $this->handlers[get_class($query)];
        return (new $handler())->handle($query);
    }
}
```

## Register the Handlers
We need to register your command and query handlers. You can do this in a service provider.

```
class CQRSServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CommandBus::class, function(Application $app) {
            $bus = new CommandBus();
            $bus->register(CreateUserCommand::class, CreateUserHandler::class);
            return $bus;
        });

        $this->app->singleton(QueryBus::class, function(Application $app) {
            $bus = new QueryBus();
            $bus->register(GetUserByEmailQuery::class, GetUserByEmailHandler::class);
            $bus->register(GetUsersQuery::class, GetUsersHandler::class);
            return $bus;
        });
    }
}
```

And register the new provider in bootstrap/providers!

## Usage
In your controller it is now possible to inject the Command and Query bus and use them to perform operations or perform queries.

```
class UserController
{
    protected CommandBus $commandBus;
    protected QueryBus $queryBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function create(Request $request)
    {
        $command = new CreateUserCommand(
            $request->input('name'),
            $request->input('email'),
            $request->input('password')
        );
        $this->commandBus->dispatch($command);

        return response()->json(['message' => 'User created successfully']);
    }

    public function getUserByEmail($email)
    {
        $query = new GetUserByEmailQuery($email);
        $user = $this->queryBus->dispatch($query);

        return response()->json($user);
    }

    public function getUsers()
    {
        $query = new GetUsersQuery();
        $user = $this->queryBus->dispatch($query);

        return response()->json($user);
    }
}
```

## Project start

Run ```php artisan migrate``` to create the SQLite db and necessary tables.
Run ```php artisan serve``` to start the app

## Testing 

### Create a new user
```
curl -X POST http://127.0.0.1:8000/users \
-H "Content-Type: application/json" \
-d '{"name": "John Doe", "email": "john@example.com", "password": "password"}'
```

### Fetch the user based on email
```
curl http://127.0.0.1:8000/users/john@example.com
```

### Fetch all users
```
curl http://127.0.0.1:8000/users
```
 





