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

use LukasHron\FileWriter\FileManager;
use PHPUnit\Framework\TestCase;

final class WriterManagerTest extends TestCase
{
    /**
     * @return void
     * @throws \LukasHron\FileWriter\Exception\FileManagerIOException
     * @throws \LukasHron\FileWriter\Exception\FileManagerInvalidArgumentException
     */
    public function testCreateFileAndWrite(): void
    {
        $controlString = (string)time();
        $testFile = sprintf('%s/%s_write.txt', __DIR__, $controlString);

        $fileManager = new FileManager();
        $file = $fileManager->get($testFile);
        $file->write($controlString);
        $fileManager->close($file);

        $this->assertFileExists($testFile);
        $this->assertTrue(file_get_contents($testFile) === $controlString);

        unlink($testFile);
    }

    /**
     * @return void
     * @throws \LukasHron\FileWriter\Exception\FileManagerException
     * @throws \LukasHron\FileWriter\Exception\FileManagerIOException
     */
    public function testAppendContentToFile(): void
    {
        $controlString = (string)time();
        $testFile = sprintf('%s/%s_append.txt', __DIR__, $controlString);

        $fileManager = new FileManager();
        $fileManager->append($testFile, sprintf('%s_1', $controlString));
        $fileManager->append($testFile, sprintf('%s_2', $controlString));
        $fileManager->append($testFile, sprintf('%s_3', $controlString));

        $this->assertTrue(file_get_contents($testFile) === sprintf('%s_1%s_2%s_3', $controlString, $controlString, $controlString));

        unlink($testFile);
    }
}