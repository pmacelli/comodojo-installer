General Concepts
================

.. _comodojo/dispatcher: https://dispatcher.comodojo.org
.. _comodojo/extender: https://extender.comodojo.org
.. _comodojo/comodojo-installer: https://github.com/comodojo/comodojo-installer
.. _comodojo/dispatcher default configuration: https://github.com/comodojo/dispatcher/blob/master/composer.json

Comodojo project packages, like `comodojo/dispatcher`_ or `comodojo/extender`_, are designed to streamline the configuration of frameworks and libraries, automating the build and customization processes. As a result, dispatcher can create the routing table dynamically, extender can do the same for tasks.

As an example, let's consider the definition of routes into dispatcher.

Without an automated installation process, dispatcher services could be defined inside the project package, in a dedicate folder structure accessible using internal composer autoloader. Relative routes have to be hardcoded inside  configuration files and the final package can be stored, versioned and deployed as a single artifact. In a small installation, perhaps, this could be the right way to organize a project.

But what if your application is composed by hundreds of services? And, if it uses plugins, how to reuse code across different installation?

That's where the comodojo-installer comes in, enabling the auto-installation of packages and avoiding internal collisions. In other words, this package enables the installation of application's components (e.g. dispatcher services, plugins, extender tasks) as independent bundles, building a complete application from small, manageable and independent packages.

And since composer supports a plugin architecture that can be used to extend its capabilities, the `comodojo/comodojo-installer`_ package is designed as a composer plugin to enhance the way it builds a project.

Installer workflow
------------------

Since composer supports a plugin architecture that can be used to extend its capabilities, the `comodojo/comodojo-installer`_ package is designed as a composer plugin to enhance the way it builds a project. Once installed, it extends the routines that define package lifecycle management and project installation.

First, it hooks to the ``post-create-project-cmd`` composer event, to create the main configuration and to allow custom scripts to be executed.

Then, it listens for every package that is installed, updated or removed and activates itself when the package-type is recognized as *manageable* (see section :ref:`general-comodojo-packages` for more information). When this happens, installer reads the ``extra`` field inside new package's *composer.json* and processes its statements using *drivers*, to interpret them, and *persisters*, to save config data somewhere (form more information, see sections :ref:`drivers` and :ref:`persistence`).

.. note:: If you have cloned your project from github, the installer will manage packages but will not create main configuration since the ``post-create-project-cmd`` event will not be emitted.
    To launch it manually run:

    .. code-block:: bash

        composer run-script post-create-project-cmd

.. _general-comodojo-packages:

Comodojo packages
.................

TBW
