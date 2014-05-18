Criteria Designer
=================

Visual criteria designer form zf2 and doctrine orm 2

Introduction
------------

This criteria designer is an extension of the doctrine collections (but not tested with doctrine dbal and doctrine odm)
wich provide more than 40 comparisons like comparision on agregated result and a visual designer

At this time, there's no documention avalaible, but i will do this as soon as possible, and [a online demo site can be found here](http://www.ilyasabdourahim.com/zf2/criteriadesigner) and more tutorials will come later.

#### Please some contributors are needed for help me on it, it becomes to big for one developper.

Usage
-----

This module will give you a complete exemple for the use of the criteria designer.
You only have this little things:

1. Launching the composer for dependencies (ZF2 v2.2.5 and DoctrineORMModule 0.8)
2. Configuring the doctrine.global.php file for database connection
3. Importing the sql data from the "import sql" folder
4. Declaring the new module in the application.config.php
```
    'modules' => array(
        'DoctrineModule',
        'DoctrineORMModule',
        'CriteriaDesigner'
    ),
```

Enjoy
