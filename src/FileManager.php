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

use LukasHron\FileWriter\Exception\FileManagerException;
use LukasHron\FileWriter\Exception\FileManagerIOException;
use function fopen;
use function fclose;
use function is_resource;
use function sprintf;
use function file_put_contents;

final class FileManager
{
    /**
     * Open for reading only; place the file pointer at the beginning of the file.
     * @var string
     */
    const readMode = 'r';

    /**
     * Open for reading and writing; place the file pointer at the beginning of the file.
     * @var string
     */
    const readAndWriteBeginningMode = 'r+';

    /**
     * Open for writing only; place the file pointer at the beginning of the file and truncate the file to zero length.
     * If the file does not exist, attempt to create it.
     * @var string
     */
    const writeOnlyMode = 'w';

    /**
     * Open for reading and writing; otherwise it has the same behavior as 'w'.
     * @var string
     */
    const readAndWriteMode = 'w+';

    /**
     * Open for writing only; place the file pointer at the end of the file.
     * If the file does not exist, attempt to create it.
     * In this mode, fseek() has no effect, writes are always appended.
     * @var string
     */
    const writeOnlyToEndMode = 'a';

    /**
     * Open for reading and writing; place the file pointer at the end of the file.
     * If the file does not exist, attempt to create it.
     * In this mode, fseek() only affects the reading position, writes are always appended.
     * @var string
     */
    const readAndWriteToEndMode = 'a+';

    /**
     * Create and open for writing only; place the file pointer at the beginning of the file.
     * If the file already exists, the fopen() call will fail by returning false and generating an error of level E_WARNING.
     * If the file does not exist, attempt to create it.
     * This is equivalent to specifying O_EXCL|O_CREAT flags for the underlying open(2) system call.
     * @var string
     */
    const createAndWriteOnlyMode = 'x';

    /**
     * Create and open for reading and writing; otherwise it has the same behavior as 'x'.
     * @var string
     */
    const createAndReadAndWriteMode = 'x+';

    /**
     * Open the file for writing only. If the file does not exist, it is created.
     * If it exists, it is neither truncated (as opposed to 'w'), nor the call to this function fails (as is the case with 'x').
     * The file pointer is positioned on the beginning of the file.
     * This may be useful if it's desired to get an advisory lock (see flock()) before attempting to modify the file,
     * as using 'w' could truncate the file before the lock was obtained (if truncation is desired,
     * ftruncate() can be used after the lock is requested).
     * @var string
     */
    const writeOnlyBeginningMode = 'c';

    /**
     * Open the file for reading and writing; otherwise it has the same behavior as 'c'.
     * @var string
     */
    const readAndWriteOnlyBeginningMode = 'c+';

    /**
     * Set close-on-exec flag on the opened file descriptor. Only available in PHP compiled on POSIX.1-2008 conform systems.
     * @var string
     */
    const closeOnExecMode = 'e';

    /**
     * @throws Exception\FileManagerInvalidArgumentException
     * @throws FileManagerIOException
     */
    public function get(string $filepath, string $mode = self::readAndWriteMode): File
    {
        $this->checkPermission($filepath);

        $resource = fopen($filepath, $mode);

        if (!is_resource($resource)) {
            throw new FileManagerIOException(
                sprintf('The resource "%s" could not be loaded.', $filepath)
            );
        }

        return new File($filepath, $mode, $resource);
    }

    public function close(File $file): void
    {
        fclose($file->getResource());
    }

    /**
     * @throws FileManagerException
     * @throws FileManagerIOException
     */
    public function append(string $filepath, string $content, int $flag = FILE_APPEND | LOCK_EX): void
    {
        $this->checkPermission($filepath);
        if (!file_put_contents($filepath, $content, $flag)) {
            throw new FileManagerException('Could not append data to file.');
        }
    }

    /**
     * @return void
     * @throws FileManagerIOException
     */
    private function checkPermission(string $filepath)
    {
        if (file_exists($filepath) && !is_writable($filepath)) {
            throw new FileManagerIOException('File is not writeable.');
        }
    }
}