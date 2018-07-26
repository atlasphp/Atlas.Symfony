# Symfony 4 Bundle for Atlas 3

This package makes the [Atlas](http://atlasphp.io) ORM and command-line tooling
available as a bundle for Symfony 4 projects.

## Installation and Configuration

1. In your Symfony 4 project, add `atlas/symfony` as a requirement.

    ```
    composer require atlas/symfony ~1.0
    ```

2. Copy the Atlas config file from the bundle to your project.

    ```
    cp ./vendor/atlas/symfony/Resources/config/atlas.yaml ./config/packages/atlas.yaml
    ```

3. Add these variables to your `.env` file with your DSN, username, and password:

    ```
    ATLAS_PDO_DSN=mysql:host=myhost;dbname=mydatabase
    ATLAS_PDO_USERNAME=myusername
    ATLAS_PDO_PASSWORD=mypassword
    ```

4. Finally, enable the bundle by adding it to your `config/bundles.php` file:

    ```
    return [
        Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
        // ...
        Atlas\Symfony\AtlasBundle::class => ['all' => true],
    ];
    ```

(A Symfony Flex recipe to ease installation is forthcoming.)

> **Note:**
>
> If you are using PHPStorm, you may wish to copy the IDE meta file to your
> project to get full autocompletion on Atlas classes:
>
> ```
> cp ./vendor/atlas/orm/resources/phpstorm.meta.php ./.phpstorm.meta.php
> ```

## Getting Started

### Generating Mappers

First, use the command-line tooling to create the skeleton files for all your
database tables:

```
mkdir src/DataSource
php bin/console atlas:skeleton
```

The `atlas.yaml` file specifies `App\DataSource\` as the namespace,
and `src/DataSource/` as the directory. To change them, modify the
`atlas.cli.config.input` values for `directory` and `namespace` as you see fit.

The database table names will be converted to singular for their relevant
type names in PHP. If you want a different type names for certain tables,
modify the `atlas.cli.transform` values in the `atlas.yaml` file.

When you make changes to the database, re-run the skeleton generator, and the
relevant files will be regenerated.

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

Full documentation for using Atlas is at <http://atlasphp.io/cassini/orm/>.
