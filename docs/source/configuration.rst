Configuration
=============

The installer reads configuration statements from:

- project package, to configure itself and to build initial config;
- custom libraries installed by composer, to build application piece by piece.

.. note:: The default project package is **comodojo-bundle**.

Project statements
..................

There are two section of the extra field that installer will look for: ``comodojo-installer`` and ``comodojo-configuration``. The first provides configuration for the installer itself, the second for the entire project.

As example, let's dissect the `comodojo/dispatcher default configuration`_ and, in particular, it's extra field.

It contains the two sections described above:

.. code-block:: json

    {
        "extra": {
            "comodojo-installer": {...},
            "comodojo-configuration": {...}
        }
    }

.. topic:: comodojo-installer section

    This section will configure the installer and can be used to extend its functionalities.

    To better undestand each statement, let's look at the commented version of the `comodojo/dispatcher default configuration`_:

    .. code-block:: json

        "comodojo-installer": {
            // what package types installer will look for?
            "package-types": [
                "comodojo-bundle"
            ],
            // this subsection tells installer how to manage the global configuration
            "global-config": {
                // the extra-field where look for configuration statements (see next topic)
                "extra-field": "comodojo-configuration",
                // how the configuration will be persisted
                "persistence": "\\Comodojo\\Installer\\Persistence\\YamlPersistence",
                // parameters to pass to the persister
                "params": {
                    "config-file": "config/comodojo-configuration.yml",
                    "depth": 6
                }
            },
            // this subsection instructs installer to search for specific extra
            // field when a package is recognized as manageable (package-type in [package-types])
            "package-extra": {
                // this defines that each valid package could include a routes field that will be used to
                // build the routing table of the dispatcher
                "routes": {
                    // once found, route statements are processed by a RouteDriver...
                    "driver": "\\Comodojo\\Installer\\Drivers\\RouteDriver",
                    // ...and persisted using the YamlPersistence class...
                    "persistence": "\\Comodojo\\Installer\\Persistence\\YamlPersistence",
                    // ...using this parameters.
                    "params": {
                        "config-file": "config/comodojo-routes.yml"
                    }
                },
                "plugins": {
                    "driver": "\\Comodojo\\Installer\\Drivers\\PluginDriver",
                    "persistence": "\\Comodojo\\Installer\\Persistence\\YamlPersistence",
                    "params": {
                        "config-file": "config/comodojo-plugins.yml"
                    }
                },
                "commands": {
                    "driver": "\\Comodojo\\Installer\\Drivers\\CommandDriver",
                    "persistence": "\\Comodojo\\Installer\\Persistence\\YamlPersistence",
                    "params": {
                        "config-file": "config/comodojo-commands.yml"
                    }
                }
            }
        }

.. topic:: comodojo-configuration section

    The second section contains the ``global-config``, the project's default configuration that will be persisted during project installation and then loaded according to project rules.

    .. code-block:: json

        {
            "comodojo-configuration": {
                "static-config": "config",
                "routing-table-cache": true,
                "routing-table-ttl": 86400,
                "log": {
                    "enable": true,
                    "name": "dispatcher",
                    "providers": {
                        "local" : {
                            "type": "StreamHandler",
                            "level": "info",
                            "stream": "logs/dispatcher.log"
                        }
                    }
                },
                "cache": {
                    "enable": true,
                    "pick_mode": "PICK_FIRST",
                    "providers": {
                        "local": {
                            "type": "Filesystem",
                            "cache_folder": "cache"
                        }
                    }
                }
            }
        }