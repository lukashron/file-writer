# File writer

Simple wrapper for write to file.

Install
-------
```bash
  $ composer require lukashron/file-writer
```

Basic usage
-----------
```php
    // Create file manager
    $fileManager = new FileManager();
    
    // File handling
    $file = $fileManager->get($testFile);
    $file->write($controlString);
    
    // And close...
    $fileManager->close($file);
```

Develop
-------
```bash
    # PHPRector
    $ docker compose exec app php vendor/bin/rector process
    
    # Tests
    $ docker compose exec app php vendor/bin/phpunit
```

Copyright (c) 2023 Lukas Hron