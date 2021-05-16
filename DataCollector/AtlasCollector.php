<?php
namespace Atlas\Symfony\DataCollector;

use Atlas\Orm\Atlas;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class AtlasCollector extends DataCollector
{
    protected $atlas;

    public function __construct(Atlas $atlas)
    {
        $this->atlas = $atlas;
    }

    public function collect(
        Request $request,
        Response $response,
        \Throwable $exception = null
    ) {
        $this->data = $this->atlas->getQueries();
    }

    public function reset()
    {
        $this->data = [];
    }

    public function getName()
    {
        return 'atlas.collector';
    }

    public function getQueries()
    {
        return $this->data;
    }

    public function getCount()
    {
        return count($this->data);
    }

    public function getDuration()
    {
        return array_reduce($this->data, function ($time, $query) {
            return $time + $query['duration'];
        });
    }
}
