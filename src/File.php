<?php
/*
 * This file is part of the File-writer package.
 *
 * (c) Lukas Hron <info@lukashron.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace LukasHron\FileWriter;

use LukasHron\FileWriter\Exception\FileManagerInvalidArgumentException;
use LukasHron\FileWriter\Exception\FileManagerIOException;
use function fwrite;
use function is_resource;

final class File
{
    private string $filepath;

    private string $mode;

    private $resource;

    /**
     * @param $resource
     * @throws FileManagerInvalidArgumentException
     */
    public function __construct(string $filepath, string $mode, $resource)
    {
        $this->filepath = $filepath;
        $this->mode = $mode;

        if (!is_resource($resource)) {
            throw new FileManagerInvalidArgumentException('The third parameter in resources handler constructor must by type "resource".');
        }

        $this->resource = $resource;
    }

    /**
     * @return $this
     * @throws FileManagerIOException
     */
    public function write(string $content): self
    {
        if (fwrite($this->resource, $content) === false) {
            throw new FileManagerIOException(
                sprintf('cannot be written to a file "%s"', $this->filepath)
            );
        }

        return $this;
    }


    /**
     * Getters
     */


    public function getFilepath(): string
    {
        return $this->filepath;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function getResource()
    {
        return $this->resource;
    }
}