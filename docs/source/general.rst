General Concepts
================

.. _comodojo/dispatcher: https://dispatcher.comodojo.org
.. _comodojo/extender: https://extender.comodojo.org
.. _comodojo/comodojo-installer: https://github.com/comodojo/comodojo-installer
.. _comodojo/dispatcher default configuration: https://github.com/comodojo/dispatcher/blob/master/composer.json

Comodojo project packages, like `comodojo/dispatcher`_ or `comodojo/extender`_, are designed to streamline the configuration of frameworks and libraries, automating the build and customization processes. In this way dispatcher can create the routing table dynamically, extender can do the same for tasks.

Considering dispatcher services as an example, these components can be placed inside the project package directly, relative routes can be hardcoded inside default configuration files and the final package can be stored and deployed as a single artifact. In a small installation, perhaps, this could be the right way to organize a project.

But what if your app is composed by hundreds of services? You will need something to help you to build it from small, manageable packages that could be installed independently. And since composer supports a plugin architecture that can be used to extend its capabilities, the comodojo/comodojo-installer package is designed as a plugin to enhance the way composer builds a project, enabling the auto-installation of packages and avoiding internal collisions.

Installer workflow
------------------

The comodojo/comodojo-installer packages is defined as a composer-plugin. Once installed, it extends the behaviour of the routines that define package lifecycle management and project installation.

It hooks to the ``post-create-project-cmd`` composer event first, to create the main configuration and allow custom scripts to be executed. Then, it listen for every package that is installed, updated or removed and activate itself only when the package-type is recognized as *manageable* (see next section for more informations). When this happens, installer read the ``extra`` field inseide *composer.json* and process its statements using drivers (to interpret them) and persisters (to save config data somewhere).

.. note:: If you have cloned your project from github, the installer will manage single packages but will not create main configuration since the ``post-create-project-cmd`` event will never be raised. You will need launch it manually using the command:

    .. code-block:: bash

    composer run-script post-create-project-cmd

Comodojo packages
.................

TBW