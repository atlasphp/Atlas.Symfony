<?php

/*
 * This file is part of the atlas/symfony package.
 *
 * (c) Atlas for PHP
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atlas\Symfony\Command;

use Atlas\Cli\Skeleton;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * A Symfony Command wrapper around the Skeleton service.
 */
class SkeletonCommand extends Command
{
    /** @var Skeleton */
    protected $skeleton;

    /**
     * Constructor.
     *
     * @param Skeleton $skeleton The Atlas Skeleton service.
     */
    public function __construct(Skeleton $skeleton)
    {
        parent::__construct();
        $this->skeleton = $skeleton;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('atlas:skeleton')
            ->setDescription('Creates the Atlas skeleton files.');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ($this->skeleton)();
    }
}
