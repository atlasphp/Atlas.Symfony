# Symfony 4 Bundle for Atlas 3

This package makes the [Atlas](http://atlasphp.io) ORM and command-line tooling
available as a bundle for Symfony 4 projects.

(Atlas is a data mapper for your persistence layer, not your domain layer.)

## Installation and Configuration

1. In your Symfony 4 project, enable contributor recipes:

    ```
    composer config extra.symfony.allow-contrib true
    ```

2. Require the `atlas/symfony` package; this will activate a Symfony Flex recipe
   as part of the installation:

    ```
    composer require atlas/symfony ~1.0
    ```

3. Edit these new `.env` variables to define your database connection:

    ```
    ATLAS_PDO_DSN=mysql:host=myhost;dbname=mydatabase
    ATLAS_PDO_USERNAME=myusername
    ATLAS_PDO_PASSWORD=mypassword
    ```

> **Note:**
>
> If you are using PHPStorm, you may wish to copy the IDE meta file to your
> project to get full autocompletion on Atlas classes:
>
> ```
> cp ./vendor/atlas/orm/resources/phpstorm.meta.php ./.phpstorm.meta.php
> ```

In the `atlas.yaml` config file, these settings are notable:

- `atlas.orm.atlas.log_queries`: set this to `true` to enable the web profiler
  data collector for query logging.

- `atlas.orm.atlas.transaction_class`: set this to one of the Atlas transaction
  strategy classes, such as `Atlas\\Orm\\Transaction\\AutoTransact`. For more
  information, see the
  [transactions documentation](http://atlasphp.io/cassini/orm/transactions.html#1-1-6-2).

## Getting Started

### Generating Mappers

Use the command-line tooling to create the skeleton files for all your database
tables:

```
mkdir src/DataSource
php bin/console atlas:skeleton
```

The `config/packages/atlas.yaml` file specifies `App\DataSource\` as the
namespace, and `src/DataSource/` as the directory. To change them, modify the
`atlas.cli.config.input` values for `directory` and `namespace` as you see fit.

The database table names will be converted to singular for their relevant
type names in PHP. If you want a different type names for certain tables,
modify the `atlas.cli.transform` values in the `atlas.yaml` file to map a
from table name to a type name.

As you make changes to the database, re-run the skeleton generator, and the
relevant table files will be regenerated.

For more information, see <http://atlasphp.io/cassini/skeleton/>.

### Using Atlas

Now that there are mappers for all the database tables, you can use the Symfony
dependency injection system to autowire Atlas into your classes for you.

```php
namespace App;

use Atlas\Orm\Atlas;
use App\DataSource\Thread\Thread
use App\DataSource\Thread\ThreadRecord;

class ApplicationService
{
    public function __construct(Atlas $atlas)
    {
        $this->atlas = $atlas;
    }

    public function fetchThreadById($thread_id) : ThreadRecord
    {
        return $this->atlas->fetchRecord(Thread::class, $thread_id);
    }
}
```

Full documentation for using Atlas is at <http://atlasphp.io/cassini/orm/>:

- [Defining relationships between mappers](http://atlasphp.io/cassini/orm/relationships.html)

- [Fetching Records and RecordSets](http://atlasphp.io/cassini/orm/reading.html)

- Working with [Records](http://atlasphp.io/cassini/orm/records.html)
  and [RecordSets](http://atlasphp.io/cassini/orm/record-sets.html)

- [Managing transactions](http://atlasphp.io/cassini/orm/transactions.html)

- [Adding behaviors](http://atlasphp.io/cassini/orm/behavior.html)

- [Handling events](http://atlasphp.io/cassini/orm/events.html)

- [Direct lower-level queries](http://atlasphp.io/cassini/orm/direct.html)

- [Other topics](http://atlasphp.io/cassini/orm/other.html) such as custom mapper
  methods, single table inheritance, many-to-many relationships, and automated
  validation

Enjoy!
